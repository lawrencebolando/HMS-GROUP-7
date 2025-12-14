<?php
/**
 * Setup Script for Invoices/Billing Table
 * 
 * This script will:
 * 1. Run the migration to create the invoices table
 * 2. Seed the table with sample data
 * 
 * Run this from the command line: php setup_billing.php
 * Or access via browser: http://localhost/HMS-ITE311-G7/setup_billing.php
 */

// Load CodeIgniter
require __DIR__ . '/vendor/autoload.php';

// Get the paths
$pathsConfig = require __DIR__ . '/app/Config/Paths.php';
$paths = new \Config\Paths();

// Load the framework bootstrap
require $paths->systemDirectory . '/Boot.php';

// Bootstrap CodeIgniter
\CodeIgniter\Boot::bootWeb($paths);

echo "=== Invoices/Billing Table Setup ===\n\n";

// Step 1: Run migration
$migration = \Config\Services::migrations();
$migrate = \Config\Services::migrations();

try {
    echo "Running migrations...\n";
    $migrate->setNamespace(null)->latest();
    echo "✓ Migrations completed\n\n";
} catch (\Exception $e) {
    echo "Migration error: " . $e->getMessage() . "\n";
    echo "Attempting to create table manually...\n";
    
    // Try to create table manually
    $db = \Config\Database::connect();
    $forge = \Config\Database::forge();
    
    if (!$db->tableExists('invoices')) {
        $fields = [
            'id' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'invoice_id' => [
                'type'       => 'VARCHAR',
                'constraint' => '50',
            ],
            'patient_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
                'null'       => true,
            ],
            'invoice_type' => [
                'type'       => 'VARCHAR',
                'constraint' => '100',
                'null'       => true,
            ],
            'amount' => [
                'type'       => 'DECIMAL',
                'constraint' => '10,2',
                'default'    => 0.00,
            ],
            'status' => [
                'type'       => 'ENUM',
                'constraint' => ['pending', 'paid', 'overdue', 'cancelled'],
                'default'    => 'pending',
            ],
            'invoice_date' => [
                'type' => 'DATE',
            ],
            'due_date' => [
                'type' => 'DATE',
                'null' => true,
            ],
            'payment_date' => [
                'type' => 'DATE',
                'null' => true,
            ],
            'payment_method' => [
                'type'       => 'VARCHAR',
                'constraint' => '50',
                'null'       => true,
            ],
            'description' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'notes' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'updated_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
        ];
        
        $forge->addField($fields);
        $forge->addKey('id', true);
        $forge->addUniqueKey('invoice_id');
        $forge->addKey('patient_id');
        $forge->addKey('invoice_date');
        $forge->addKey('status');
        $forge->addKey('due_date');
        $forge->createTable('invoices', true);
        echo "✓ Invoices table created manually\n\n";
    } else {
        echo "✓ Invoices table already exists\n\n";
    }
}

// Step 2: Seed data
$seeder = new \App\Database\Seeds\InvoicesSeeder();
try {
    echo "Seeding invoices data...\n";
    $seeder->run();
    echo "✓ Seeding completed\n\n";
} catch (\Exception $e) {
    echo "Seeding error: " . $e->getMessage() . "\n";
    echo "You can manually add invoices through the interface.\n\n";
}

echo "=== Setup Complete ===\n";
echo "You can now access billing at: http://localhost/HMS-ITE311-G7/public/billing\n";

