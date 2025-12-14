<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class InvoicesSeeder extends Seeder
{
    public function run()
    {
        $db = \Config\Database::connect();
        
        // Check if table exists
        if (!$db->tableExists('invoices')) {
            echo "Invoices table does not exist. Please run migrations first.\n";
            return;
        }

        // Check if there are already invoices
        $existingCount = $db->table('invoices')->countAllResults();
        if ($existingCount > 0) {
            echo "Invoices already exist. Skipping seeder.\n";
            return;
        }

        // Get some patients for sample data
        $patients = $db->table('patients')->limit(10)->get()->getResultArray();
        
        if (empty($patients)) {
            echo "No patients found. Please add patients first.\n";
            return;
        }

        // Sample invoice types
        $invoiceTypes = [
            'Consultation',
            'Laboratory',
            'Pharmacy',
            'Room Charges',
            'Surgery',
            'Emergency',
            'X-Ray',
            'Prescription'
        ];

        // Sample payment methods
        $paymentMethods = [
            'Cash',
            'Credit Card',
            'Debit Card',
            'Insurance',
            'Bank Transfer'
        ];

        // Generate invoices
        $invoicesData = [];
        
        // Paid invoices
        for ($i = 1; $i <= 8; $i++) {
            $patient = $patients[array_rand($patients)];
            $amount = rand(500, 5000) + (rand(0, 99) / 100);
            $daysAgo = rand(1, 30);
            
            $invoicesData[] = [
                'invoice_id' => 'INV-' . date('Y') . '-' . str_pad($i, 4, '0', STR_PAD_LEFT),
                'patient_id' => $patient['id'],
                'invoice_type' => $invoiceTypes[array_rand($invoiceTypes)],
                'amount' => $amount,
                'status' => 'paid',
                'invoice_date' => date('Y-m-d', strtotime("-{$daysAgo} days")),
                'due_date' => date('Y-m-d', strtotime("-{$daysAgo} days +7 days")),
                'payment_date' => date('Y-m-d', strtotime("-{$daysAgo} days +" . rand(0, 5) . " days")),
                'payment_method' => $paymentMethods[array_rand($paymentMethods)],
                'description' => 'Medical service invoice',
                'notes' => 'Payment received',
                'created_at' => date('Y-m-d H:i:s', strtotime("-{$daysAgo} days")),
                'updated_at' => date('Y-m-d H:i:s', strtotime("-{$daysAgo} days")),
            ];
        }
        
        // Pending invoices
        for ($i = 9; $i <= 12; $i++) {
            $patient = $patients[array_rand($patients)];
            $amount = rand(500, 5000) + (rand(0, 99) / 100);
            $daysAgo = rand(1, 10);
            
            $invoicesData[] = [
                'invoice_id' => 'INV-' . date('Y') . '-' . str_pad($i, 4, '0', STR_PAD_LEFT),
                'patient_id' => $patient['id'],
                'invoice_type' => $invoiceTypes[array_rand($invoiceTypes)],
                'amount' => $amount,
                'status' => 'pending',
                'invoice_date' => date('Y-m-d', strtotime("-{$daysAgo} days")),
                'due_date' => date('Y-m-d', strtotime("+7 days")),
                'payment_date' => null,
                'payment_method' => null,
                'description' => 'Medical service invoice',
                'notes' => 'Awaiting payment',
                'created_at' => date('Y-m-d H:i:s', strtotime("-{$daysAgo} days")),
                'updated_at' => date('Y-m-d H:i:s', strtotime("-{$daysAgo} days")),
            ];
        }
        
        // Overdue invoices
        for ($i = 13; $i <= 15; $i++) {
            $patient = $patients[array_rand($patients)];
            $amount = rand(500, 5000) + (rand(0, 99) / 100);
            $daysAgo = rand(10, 30);
            
            $invoicesData[] = [
                'invoice_id' => 'INV-' . date('Y') . '-' . str_pad($i, 4, '0', STR_PAD_LEFT),
                'patient_id' => $patient['id'],
                'invoice_type' => $invoiceTypes[array_rand($invoiceTypes)],
                'amount' => $amount,
                'status' => 'overdue',
                'invoice_date' => date('Y-m-d', strtotime("-{$daysAgo} days")),
                'due_date' => date('Y-m-d', strtotime("-{$daysAgo} days +7 days")),
                'payment_date' => null,
                'payment_method' => null,
                'description' => 'Medical service invoice',
                'notes' => 'Payment overdue',
                'created_at' => date('Y-m-d H:i:s', strtotime("-{$daysAgo} days")),
                'updated_at' => date('Y-m-d H:i:s', strtotime("-{$daysAgo} days")),
            ];
        }

        // Insert each invoice individually
        $inserted = 0;
        foreach ($invoicesData as $invoice) {
            try {
                $db->table('invoices')->insert($invoice);
                $inserted++;
            } catch (\Exception $e) {
                echo "Error inserting invoice {$invoice['invoice_id']}: " . $e->getMessage() . "\n";
            }
        }
        
        echo "Successfully seeded {$inserted} invoices.\n";
    }
}

