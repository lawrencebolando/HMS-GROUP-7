<?php

namespace App\Controllers;

use App\Models\PatientModel;
use CodeIgniter\Database\BaseConnection;

class AccountsPortal extends BaseController
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
        $userRole = $this->session->get('user_role');
        if (!$this->session->get('is_logged_in') || !in_array($userRole, ['accountant', 'accounts'])) {
            return redirect()->to('login')->with('error', 'Access denied. Accountant only.');
        }

        $invoicesExists = $this->db->tableExists('invoices');

        $stats = $this->getAccountsStats($invoicesExists);
        $pendingBills = $this->getPendingBills($invoicesExists);
        $recentPayments = $this->getRecentPayments($invoicesExists);
        $insuranceClaims = $this->getInsuranceClaims();

        $data = [
            'title' => 'Accounts Portal - Dashboard',
            'user' => [
                'name' => $this->session->get('user_name'),
                'email' => $this->session->get('user_email'),
                'role' => $this->session->get('user_role')
            ],
            'stats' => $stats,
            'pending_bills' => $pendingBills,
            'recent_payments' => $recentPayments,
            'insurance_claims' => $insuranceClaims
        ];

        return view('accounts_portal/dashboard', $data);
    }

    private function getAccountsStats($invoicesExists)
    {
        try {
            $todayRevenue = 0;
            $pendingBills = 0;
            $insuranceClaims = 8; // Mock data
            $overduePayments = 0;

            if ($invoicesExists) {
                $today = date('Y-m-d');
                $todayRevenue = $this->db->table('invoices')
                    ->selectSum('amount')
                    ->where('status', 'paid')
                    ->where('payment_date', $today)
                    ->get()
                    ->getRowArray();
                $todayRevenue = floatval($todayRevenue['amount'] ?? 0);

                $pendingBills = $this->db->table('invoices')
                    ->where('status', 'pending')
                    ->countAllResults();

                $overduePayments = $this->db->table('invoices')
                    ->where('status', 'pending')
                    ->where('due_date <', $today)
                    ->countAllResults();
            }

            return [
                'today_revenue' => $todayRevenue,
                'pending_bills' => $pendingBills,
                'insurance_claims' => $insuranceClaims,
                'overdue_payments' => $overduePayments
            ];
        } catch (\Exception $e) {
            log_message('error', 'Error fetching accounts stats: ' . $e->getMessage());
            return [
                'today_revenue' => 0,
                'pending_bills' => 0,
                'insurance_claims' => 0,
                'overdue_payments' => 0
            ];
        }
    }

    private function getPendingBills($tableExists)
    {
        if (!$tableExists) {
            return [];
        }

        try {
            $bills = $this->db->table('invoices')
                ->where('status', 'pending')
                ->orderBy('due_date', 'ASC')
                ->limit(10)
                ->get()
                ->getResultArray();

            foreach ($bills as &$bill) {
                $patient = $this->patientModel->find($bill['patient_id']);
                $bill['patient_name'] = $patient ? ($patient['first_name'] . ' ' . $patient['last_name']) : 'Unknown';
            }

            return $bills;
        } catch (\Exception $e) {
            log_message('error', 'Error fetching pending bills: ' . $e->getMessage());
            return [];
        }
    }

    private function getRecentPayments($tableExists)
    {
        if (!$tableExists) {
            return [];
        }

        try {
            $payments = $this->db->table('invoices')
                ->where('status', 'paid')
                ->orderBy('payment_date', 'DESC')
                ->limit(10)
                ->get()
                ->getResultArray();

            foreach ($payments as &$payment) {
                $patient = $this->patientModel->find($payment['patient_id']);
                $payment['patient_name'] = $patient ? ($patient['first_name'] . ' ' . $patient['last_name']) : 'Unknown';
            }

            return $payments;
        } catch (\Exception $e) {
            log_message('error', 'Error fetching recent payments: ' . $e->getMessage());
            return [];
        }
    }

    private function getInsuranceClaims()
    {
        // Mock data - would need insurance_claims table
        return [];
    }
}

