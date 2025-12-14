<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class PortalAccountsSeeder extends Seeder
{
    public function run()
    {
        $db = \Config\Database::connect();
        $userModel = new \App\Models\UserModel();

        // Check if users table exists
        if (!$db->tableExists('users')) {
            echo "Users table does not exist. Please run migrations first.\n";
            return;
        }

        $accounts = [
            // Nurse Accounts
            [
                'name' => 'Sarah Johnson',
                'email' => 'nurse.sarah@hospital.com',
                'password' => 'nurse123',
                'role' => 'nurse',
                'status' => 'active'
            ],
            [
                'name' => 'Michael Chen',
                'email' => 'nurse.michael@hospital.com',
                'password' => 'nurse123',
                'role' => 'nurse',
                'status' => 'active'
            ],

            // Lab Staff Accounts
            [
                'name' => 'Emily Davis',
                'email' => 'lab.emily@hospital.com',
                'password' => 'lab123',
                'role' => 'lab_technician',
                'status' => 'active'
            ],
            [
                'name' => 'David Wilson',
                'email' => 'lab.david@hospital.com',
                'password' => 'lab123',
                'role' => 'lab_staff',
                'status' => 'active'
            ],

            // Accountant Accounts
            [
                'name' => 'Jennifer Martinez',
                'email' => 'accountant.jennifer@hospital.com',
                'password' => 'accountant123',
                'role' => 'accountant',
                'status' => 'active'
            ],
            [
                'name' => 'Robert Taylor',
                'email' => 'accounts.robert@hospital.com',
                'password' => 'accountant123',
                'role' => 'accounts',
                'status' => 'active'
            ],

            // IT Staff Accounts
            [
                'name' => 'James Anderson',
                'email' => 'it.james@hospital.com',
                'password' => 'it1234',
                'role' => 'it',
                'status' => 'active'
            ],
            [
                'name' => 'Lisa Brown',
                'email' => 'it.lisa@hospital.com',
                'password' => 'it1234',
                'role' => 'it_admin',
                'status' => 'active'
            ]
        ];

        $created = 0;
        $skipped = 0;

        foreach ($accounts as $account) {
            // Check if user already exists
            $existingUser = $userModel->where('email', $account['email'])->first();
            
            if ($existingUser) {
                echo "User {$account['email']} already exists. Skipping...\n";
                $skipped++;
                continue;
            }

            try {
                // Insert user
                $userModel->insert($account);
                echo "✓ Created account: {$account['name']} ({$account['email']}) - Role: {$account['role']}\n";
                $created++;
            } catch (\Exception $e) {
                echo "✗ Error creating account {$account['email']}: " . $e->getMessage() . "\n";
            }
        }

        echo "\n=== Portal Accounts Seeding Complete ===\n";
        echo "Created: {$created} accounts\n";
        echo "Skipped: {$skipped} accounts (already exist)\n\n";

        echo "=== Login Credentials ===\n\n";
        echo "NURSE PORTAL:\n";
        echo "  Email: nurse.sarah@hospital.com\n";
        echo "  Password: nurse123\n";
        echo "  URL: /nurse/dashboard\n\n";

        echo "LAB PORTAL:\n";
        echo "  Email: lab.emily@hospital.com\n";
        echo "  Password: lab123\n";
        echo "  URL: /lab/dashboard\n\n";

        echo "ACCOUNTS PORTAL:\n";
        echo "  Email: accountant.jennifer@hospital.com\n";
        echo "  Password: accountant123\n";
        echo "  URL: /accounts/dashboard\n\n";

        echo "IT PORTAL:\n";
        echo "  Email: it.james@hospital.com\n";
        echo "  Password: it1234\n";
        echo "  URL: /it/dashboard\n\n";
    }
}

