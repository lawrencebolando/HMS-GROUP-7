<?php
/**
 * Setup Script for Walk-In Lab Requests Table
 * 
 * This script will:
 * 1. Run the migration to create the walk_in_lab_requests table
 * 2. Seed the table with sample data
 * 
 * Run this from the command line: php setup_walkin.php
 * Or access via browser: http://localhost/HMS-ITE311-G7/setup_walkin.php
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

echo "=== Walk-In Lab Requests Table Setup ===\n\n";

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
    
    if (!$db->tableExists('walk_in_lab_requests')) {
        $fields = [
            'id' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'request_id' => [
                'type'       => 'VARCHAR',
                'constraint' => '50',
            ],
            'patient_name' => [
                'type'       => 'VARCHAR',
                'constraint' => '200',
            ],
            'contact' => [
                'type'       => 'VARCHAR',
                'constraint' => '50',
                'null'       => true,
            ],
            'phone' => [
                'type'       => 'VARCHAR',
                'constraint' => '20',
                'null'       => true,
            ],
            'email' => [
                'type'       => 'VARCHAR',
                'constraint' => '100',
                'null'       => true,
            ],
            'test_type' => [
                'type'       => 'VARCHAR',
                'constraint' => '200',
            ],
            'priority' => [
                'type'       => 'ENUM',
                'constraint' => ['low', 'normal', 'medium', 'high'],
                'default'    => 'normal',
            ],
            'status' => [
                'type'       => 'ENUM',
                'constraint' => ['pending', 'in_progress', 'completed', 'cancelled'],
                'default'    => 'pending',
            ],
            'request_date' => [
                'type' => 'DATE',
            ],
            'request_time' => [
                'type' => 'TIME',
                'null' => true,
            ],
            'completed_date' => [
                'type' => 'DATE',
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
        $forge->addUniqueKey('request_id');
        $forge->addKey('request_date');
        $forge->addKey('status');
        $forge->addKey('priority');
        $forge->createTable('walk_in_lab_requests', true);
        echo "✓ Walk-in lab requests table created manually\n\n";
    } else {
        echo "✓ Walk-in lab requests table already exists\n\n";
    }
}

// Step 2: Seed data
$seeder = new \App\Database\Seeds\WalkInLabRequestsSeeder();
try {
    echo "Seeding walk-in lab requests data...\n";
    $seeder->run();
    echo "✓ Seeding completed\n\n";
} catch (\Exception $e) {
    echo "Seeding error: " . $e->getMessage() . "\n";
    echo "You can manually add requests through the interface.\n\n";
}

echo "=== Setup Complete ===\n";
echo "You can now access walk-in lab requests at: http://localhost/HMS-ITE311-G7/public/walk-in\n";

