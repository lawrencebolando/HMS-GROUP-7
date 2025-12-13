<?php
/**
 * Setup Script for Admissions Table
 * 
 * This script will:
 * 1. Run the migration to create the admissions table
 * 2. Seed the table with sample data
 * 
 * Run this from the command line: php setup_admissions.php
 * Or access via browser: http://localhost/HMS-ITE311-G7/setup_admissions.php
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

echo "=== Admissions Table Setup ===\n\n";

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
    
    if (!$db->tableExists('admissions')) {
        $fields = [
            'id' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'admission_id' => [
                'type'       => 'VARCHAR',
                'constraint' => '50',
            ],
            'patient_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
            ],
            'doctor_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
            ],
            'admission_date' => [
                'type' => 'DATE',
            ],
            'admission_time' => [
                'type' => 'TIME',
                'null' => true,
            ],
            'discharge_date' => [
                'type' => 'DATE',
                'null' => true,
            ],
            'discharge_time' => [
                'type' => 'TIME',
                'null' => true,
            ],
            'room' => [
                'type'       => 'VARCHAR',
                'constraint' => '50',
                'null'       => true,
            ],
            'bed' => [
                'type'       => 'VARCHAR',
                'constraint' => '50',
                'null'       => true,
            ],
            'case_type' => [
                'type'       => 'VARCHAR',
                'constraint' => '100',
                'null'       => true,
            ],
            'reason' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'diagnosis' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'status' => [
                'type'       => 'ENUM',
                'constraint' => ['active', 'discharged', 'transferred'],
                'default'    => 'active',
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
        $forge->addUniqueKey('admission_id');
        $forge->addKey(['patient_id', 'doctor_id']);
        $forge->addKey('admission_date');
        $forge->addKey('status');
        $forge->createTable('admissions', true);
        echo "✓ Admissions table created manually\n\n";
    } else {
        echo "✓ Admissions table already exists\n\n";
    }
}

// Step 2: Seed data
$seeder = new \App\Database\Seeds\AdmissionsSeeder();
try {
    echo "Seeding admissions data...\n";
    $seeder->run();
    echo "✓ Seeding completed\n\n";
} catch (\Exception $e) {
    echo "Seeding error: " . $e->getMessage() . "\n";
    echo "You can manually add admissions through the interface.\n\n";
}

echo "=== Setup Complete ===\n";
echo "You can now access admissions at: http://localhost/HMS-ITE311-G7/public/admissions\n";

