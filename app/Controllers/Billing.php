<?php

namespace App\Controllers;

use App\Models\PatientModel;
use CodeIgniter\Database\BaseConnection;

class Billing extends BaseController
{
    protected $patientModel;
    protected $db;
    protected $session;

    public function __construct()
    {
        $this->patientModel = new PatientModel();
        $this->db = \Config\Database::connect();
        $this->session = session();
    }

    public function index()
    {
        if (!$this->session->get('is_logged_in') || $this->session->get('user_role') !== 'admin') {
            return redirect()->to('login')->with('error', 'Access denied. Admin only.');
        }

        // Check if invoices table exists
        $tableExists = $this->db->tableExists('invoices');
        
        // Get statistics
        $stats = $this->getBillingStats($tableExists);
        
        // Get recent invoices
        $invoices = $this->getRecentInvoices($tableExists);
        
        $data = [
            'title' => 'Billing & Payments',
            'user' => [
                'name' => $this->session->get('user_name'),
                'email' => $this->session->get('user_email'),
                'role' => $this->session->get('user_role')
            ],
            'stats' => $stats,
            'invoices' => $invoices
        ];
        
        return view('billing/index', $data);
    }

    private function getBillingStats($tableExists)
    {
        if (!$tableExists) {
            return [
                'total_revenue' => 0.00,
                'pending_invoices' => 0.00,
                'overdue_payments' => 0.00,
                'this_month' => 0.00
            ];
        }

        try {
            // Total Revenue (all time)
            $totalRevenue = $this->db->table('invoices')
                ->selectSum('amount')
                ->where('status', 'paid')
                ->get()
                ->getRowArray();
            $totalRevenue = floatval($totalRevenue['amount'] ?? 0);
            
            // Pending Invoices
            $pendingInvoices = $this->db->table('invoices')
                ->selectSum('amount')
                ->where('status', 'pending')
                ->get()
                ->getRowArray();
            $pendingInvoices = floatval($pendingInvoices['amount'] ?? 0);
            
            // Overdue Payments
            $today = date('Y-m-d');
            $overduePayments = $this->db->table('invoices')
                ->selectSum('amount')
                ->where('status', 'pending')
                ->where('due_date <', $today)
                ->get()
                ->getRowArray();
            $overduePayments = floatval($overduePayments['amount'] ?? 0);
            
            // This Month
            $monthStart = date('Y-m-01');
            $thisMonth = $this->db->table('invoices')
                ->selectSum('amount')
                ->where('status', 'paid')
                ->where('payment_date >=', $monthStart)
                ->get()
                ->getRowArray();
            $thisMonth = floatval($thisMonth['amount'] ?? 0);

            return [
                'total_revenue' => $totalRevenue,
                'pending_invoices' => $pendingInvoices,
                'overdue_payments' => $overduePayments,
                'this_month' => $thisMonth
            ];
        } catch (\Exception $e) {
            return [
                'total_revenue' => 0.00,
                'pending_invoices' => 0.00,
                'overdue_payments' => 0.00,
                'this_month' => 0.00
            ];
        }
    }

    private function getRecentInvoices($tableExists)
    {
        if (!$tableExists) {
            return [];
        }

        try {
            $invoices = $this->db->table('invoices')
                ->orderBy('invoice_date', 'DESC')
                ->orderBy('created_at', 'DESC')
                ->limit(20)
                ->get()
                ->getResultArray();

            // Enrich with patient info
            foreach ($invoices as &$invoice) {
                if (isset($invoice['patient_id'])) {
                    $patient = $this->patientModel->find($invoice['patient_id']);
                    $invoice['patient_name'] = $patient ? ($patient['first_name'] . ' ' . $patient['last_name']) : 'Unknown';
                } else {
                    $invoice['patient_name'] = 'Unknown';
                }
            }

            return $invoices;
        } catch (\Exception $e) {
            return [];
        }
    }

    public function createBills()
    {
        if (!$this->session->get('is_logged_in') || $this->session->get('user_role') !== 'admin') {
            if ($this->request->isAJAX()) {
                return $this->response->setJSON(['success' => false, 'message' => 'Access denied']);
            }
            return redirect()->to('login')->with('error', 'Access denied. Admin only.');
        }

        // Check if tables exist
        if (!$this->db->tableExists('prescriptions') || !$this->db->tableExists('invoices')) {
            if ($this->request->isAJAX()) {
                return $this->response->setJSON([
                    'success' => false, 
                    'message' => 'Prescriptions or invoices table does not exist. Please run migrations first.'
                ]);
            }
            return redirect()->back()->with('error', 'Required tables do not exist.');
        }

        try {
            // Get all completed prescriptions
            $completedPrescriptions = $this->db->table('prescriptions')
                ->where('status', 'completed')
                ->get()
                ->getResultArray();

            if (empty($completedPrescriptions)) {
                if ($this->request->isAJAX()) {
                    return $this->response->setJSON([
                        'success' => true,
                        'created' => 0,
                        'skipped' => 0,
                        'message' => 'No completed prescriptions found'
                    ]);
                }
                return redirect()->back()->with('info', 'No completed prescriptions found.');
            }

            // Get existing invoices to check which prescriptions already have bills
            $existingInvoices = $this->db->table('invoices')
                ->select('patient_id, invoice_date, description')
                ->get()
                ->getResultArray();

            // Create a map of existing bills by patient_id and date
            $existingBillsMap = [];
            foreach ($existingInvoices as $invoice) {
                if (isset($invoice['patient_id']) && isset($invoice['invoice_date'])) {
                    $key = $invoice['patient_id'] . '_' . $invoice['invoice_date'];
                    $existingBillsMap[$key] = true;
                }
            }

            $created = 0;
            $skipped = 0;

            // Generate invoice ID sequence
            $year = date('Y');
            $lastInvoice = $this->db->table('invoices')
                ->like('invoice_id', "INV-{$year}-", 'after')
                ->orderBy('id', 'DESC')
                ->get(1)
                ->getRowArray();
            
            $sequence = 1;
            if ($lastInvoice && preg_match('/INV-' . $year . '-(\d+)/', $lastInvoice['invoice_id'], $matches)) {
                $sequence = intval($matches[1]) + 1;
            }

            // Process each completed prescription
            foreach ($completedPrescriptions as $prescription) {
                // Check if bill already exists for this prescription
                $prescriptionDate = $prescription['prescribed_date'] ?? date('Y-m-d', strtotime($prescription['created_at']));
                $key = $prescription['patient_id'] . '_' . $prescriptionDate;
                
                if (isset($existingBillsMap[$key])) {
                    $skipped++;
                    continue;
                }

                // Calculate amount (you can customize this based on prescription medications or a fixed fee)
                $amount = 500.00; // Default prescription fee, can be calculated from medications

                // Create invoice
                $invoiceId = 'INV-' . $year . '-' . str_pad($sequence, 4, '0', STR_PAD_LEFT);
                $sequence++;

                $invoiceData = [
                    'invoice_id' => $invoiceId,
                    'patient_id' => $prescription['patient_id'],
                    'invoice_type' => 'Prescription',
                    'amount' => $amount,
                    'status' => 'pending',
                    'invoice_date' => $prescriptionDate,
                    'due_date' => date('Y-m-d', strtotime($prescriptionDate . ' +7 days')),
                    'payment_date' => null,
                    'payment_method' => null,
                    'description' => 'Prescription bill - ' . ($prescription['diagnosis'] ?? 'Medical prescription'),
                    'notes' => 'Generated from completed prescription: ' . ($prescription['prescription_id'] ?? ''),
                    'created_at' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s'),
                ];

                $this->db->table('invoices')->insert($invoiceData);
                $created++;
            }

            if ($this->request->isAJAX()) {
                return $this->response->setJSON([
                    'success' => true,
                    'created' => $created,
                    'skipped' => $skipped,
                    'message' => "Created {$created} bills, skipped {$skipped} (already have bills)"
                ]);
            }

            return redirect()->back()->with('success', "Created {$created} bills, skipped {$skipped} (already have bills)");
        } catch (\Exception $e) {
            if ($this->request->isAJAX()) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Error: ' . $e->getMessage()
                ]);
            }
            return redirect()->back()->with('error', 'Error creating bills: ' . $e->getMessage());
        }
    }

    public function export()
    {
        if (!$this->session->get('is_logged_in') || $this->session->get('user_role') !== 'admin') {
            return redirect()->to('login')->with('error', 'Access denied. Admin only.');
        }

        // Check if invoices table exists
        if (!$this->db->tableExists('invoices')) {
            return redirect()->back()->with('error', 'Invoices table does not exist.');
        }

        try {
            // Get all invoices
            $invoices = $this->db->table('invoices')
                ->orderBy('invoice_date', 'DESC')
                ->orderBy('created_at', 'DESC')
                ->get()
                ->getResultArray();

            // Enrich with patient info
            foreach ($invoices as &$invoice) {
                if (isset($invoice['patient_id'])) {
                    $patient = $this->patientModel->find($invoice['patient_id']);
                    $invoice['patient_name'] = $patient ? ($patient['first_name'] . ' ' . $patient['last_name']) : 'Unknown';
                } else {
                    $invoice['patient_name'] = 'Unknown';
                }
            }

            // Set headers for CSV download
            $filename = 'invoices_export_' . date('Y-m-d_His') . '.csv';
            header('Content-Type: text/csv; charset=utf-8');
            header('Content-Disposition: attachment; filename="' . $filename . '"');
            header('Pragma: no-cache');
            header('Expires: 0');

            // Open output stream
            $output = fopen('php://output', 'w');

            // Add BOM for UTF-8 to help Excel recognize the encoding
            fprintf($output, chr(0xEF).chr(0xBB).chr(0xBF));

            // CSV Headers
            $headers = [
                'Invoice ID',
                'Patient Name',
                'Type',
                'Amount (₱)',
                'Status',
                'Invoice Date',
                'Due Date',
                'Payment Date',
                'Payment Method',
                'Description',
                'Notes'
            ];
            fputcsv($output, $headers);

            // Add data rows
            foreach ($invoices as $invoice) {
                $row = [
                    $invoice['invoice_id'] ?? '',
                    $invoice['patient_name'] ?? 'Unknown',
                    $invoice['invoice_type'] ?? 'Service',
                    number_format($invoice['amount'] ?? 0, 2),
                    ucfirst($invoice['status'] ?? 'pending'),
                    isset($invoice['invoice_date']) ? date('Y-m-d', strtotime($invoice['invoice_date'])) : '',
                    isset($invoice['due_date']) ? date('Y-m-d', strtotime($invoice['due_date'])) : '',
                    isset($invoice['payment_date']) ? date('Y-m-d', strtotime($invoice['payment_date'])) : '',
                    $invoice['payment_method'] ?? '',
                    $invoice['description'] ?? '',
                    $invoice['notes'] ?? ''
                ];
                fputcsv($output, $row);
            }

            fclose($output);
            exit;
        } catch (\Exception $e) {
            log_message('error', 'Error exporting invoices: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Error exporting invoices: ' . $e->getMessage());
        }
    }
}

