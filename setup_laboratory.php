<?php

/**
 * Setup script for Laboratory module
 * 
 * This script will:
 * 1. Run the lab_results table migration
 * 2. Seed the lab_requests and lab_results tables with sample data
 * 
 * Usage: php setup_laboratory.php
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

echo "=== Laboratory Module Setup ===\n\n";

// Check database connection
try {
    $db = \Config\Database::connect();
    echo "✓ Database connection successful\n\n";
} catch (\Exception $e) {
    echo "✗ Database connection failed: " . $e->getMessage() . "\n";
    exit(1);
}

// Run migrations
echo "Running migrations...\n";
try {
    $migrate = \Config\Services::migrations();
    $migrate->setNamespace(null);
    $migrate->latest();
    echo "✓ Migrations completed\n\n";
} catch (\Exception $e) {
    echo "Migration error: " . $e->getMessage() . "\n";
    echo "Attempting to create lab_results table manually...\n";
    
    // Try to create table manually if migration fails
    $forge = \Config\Database::forge();
    
    if (!$db->tableExists('lab_results')) {
        $fields = [
            'id' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'lab_result_id' => [
                'type'       => 'VARCHAR',
                'constraint' => '50',
            ],
            'lab_request_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
                'null'       => true,
            ],
            'patient_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
            ],
            'test_type' => [
                'type'       => 'VARCHAR',
                'constraint' => '255',
            ],
            'result_summary' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'detailed_results' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'is_critical' => [
                'type'       => 'TINYINT',
                'constraint' => 1,
                'default'    => 0,
            ],
            'status' => [
                'type'       => 'ENUM',
                'constraint' => ['pending', 'in_progress', 'released', 'cancelled'],
                'default'    => 'pending',
            ],
            'released_by' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
                'null'       => true,
            ],
            'released_date' => [
                'type' => 'DATE',
                'null' => true,
            ],
            'released_time' => [
                'type' => 'TIME',
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
        $forge->addUniqueKey('lab_result_id');
        $forge->addKey(['patient_id', 'lab_request_id']);
        $forge->addKey('released_date');
        $forge->addKey('status');
        $forge->createTable('lab_results', true);
        echo "✓ Created lab_results table manually\n\n";
    }
}

// Run seeder
echo "Seeding laboratory data...\n";
try {
    $seeder = \Config\Database::seeder();
    $seeder->call('LabDataSeeder');
    echo "✓ Seeding completed\n\n";
} catch (\Exception $e) {
    echo "Seeding error: " . $e->getMessage() . "\n";
    echo "You may need to run the seeder manually: php spark db:seed LabDataSeeder\n\n";
}

echo "=== Laboratory Module Setup Complete ===\n";
echo "You can now access the Laboratory Dashboard at: /laboratory\n";

