<?php

/**
 * Setup script for Portal Accounts
 * 
 * This script will create sample accounts for:
 * - Nurse Portal
 * - Lab Portal
 * - Accounts Portal
 * - IT Portal
 * 
 * Usage: php setup_portal_accounts.php
 */

// Load CodeIgniter
require __DIR__ . '/vendor/autoload.php';

// Get the environment file
$pathsConfig = new \Config\Paths();
$pathsConfig->systemDirectory = __DIR__ . '/system';
$pathsConfig->appDirectory = __DIR__ . '/app';
$pathsConfig->writableDirectory = __DIR__ . '/writable';
$pathsConfig->viewDirectory = __DIR__ . '/app/Views';

// Bootstrap CodeIgniter
$app = require_once __DIR__ . '/system/bootstrap.php';

echo "=== Portal Accounts Setup ===\n\n";

// Check database connection
try {
    $db = \Config\Database::connect();
    echo "✓ Database connection successful\n\n";
} catch (\Exception $e) {
    echo "✗ Database connection failed: " . $e->getMessage() . "\n";
    exit(1);
}

// Check if users table exists
if (!$db->tableExists('users')) {
    echo "✗ Users table does not exist. Please run migrations first.\n";
    exit(1);
}

// Check if role column supports new roles
echo "Checking users table structure...\n";
$fields = $db->getFieldData('users');
$roleField = null;
foreach ($fields as $field) {
    if ($field->name === 'role') {
        $roleField = $field;
        break;
    }
}

if ($roleField && $roleField->type === 'enum') {
    echo "✓ Role field is ENUM type\n";
    // Note: If the enum doesn't include new roles, you may need to alter the table
    echo "  Note: If new roles are not supported, you may need to update the migration.\n\n";
} else {
    echo "⚠ Role field structure may need updating\n\n";
}

// Run seeder
echo "Creating portal accounts...\n";
try {
    $seeder = \Config\Database::seeder();
    $seeder->call('PortalAccountsSeeder');
    echo "\n✓ Portal accounts setup completed!\n";
} catch (\Exception $e) {
    echo "Seeding error: " . $e->getMessage() . "\n";
    echo "You may need to run the seeder manually: php spark db:seed PortalAccountsSeeder\n\n";
    
    // Try to create accounts manually
    echo "Attempting to create accounts manually...\n";
    $userModel = new \App\Models\UserModel();
    
    $accounts = [
        [
            'name' => 'Sarah Johnson',
            'email' => 'nurse.sarah@hospital.com',
            'password' => 'nurse123',
            'role' => 'nurse',
            'status' => 'active'
        ],
        [
            'name' => 'Emily Davis',
            'email' => 'lab.emily@hospital.com',
            'password' => 'lab123',
            'role' => 'lab_technician',
            'status' => 'active'
        ],
        [
            'name' => 'Jennifer Martinez',
            'email' => 'accountant.jennifer@hospital.com',
            'password' => 'accountant123',
            'role' => 'accountant',
            'status' => 'active'
        ],
        [
            'name' => 'James Anderson',
            'email' => 'it.james@hospital.com',
            'password' => 'it1234',
            'role' => 'it',
            'status' => 'active'
        ]
    ];

    foreach ($accounts as $account) {
        $existing = $userModel->where('email', $account['email'])->first();
        if (!$existing) {
            try {
                $userModel->insert($account);
                echo "✓ Created: {$account['email']}\n";
            } catch (\Exception $e) {
                echo "✗ Error creating {$account['email']}: " . $e->getMessage() . "\n";
            }
        } else {
            echo "⊘ Already exists: {$account['email']}\n";
        }
    }
}

echo "\n=== Setup Complete ===\n";
echo "You can now login with the created accounts!\n";

