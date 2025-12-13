<?php
/**
 * Setup Script for Rooms Table
 * 
 * This script will:
 * 1. Run the migration to create the rooms table
 * 2. Seed the table with sample data
 * 
 * Run this from the command line: php setup_rooms.php
 * Or access via browser: http://localhost/HMS-ITE311-G7/setup_rooms.php
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

echo "=== Rooms Table Setup ===\n\n";

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
    
    if (!$db->tableExists('rooms')) {
        $fields = [
            'id' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'room_number' => [
                'type'       => 'VARCHAR',
                'constraint' => '50',
            ],
            'room_type' => [
                'type'       => 'VARCHAR',
                'constraint' => '100',
                'null'       => true,
            ],
            'floor' => [
                'type'       => 'INT',
                'constraint' => 11,
                'null'       => true,
            ],
            'bed_count' => [
                'type'       => 'INT',
                'constraint' => 11,
                'default'    => 1,
            ],
            'available_beds' => [
                'type'       => 'INT',
                'constraint' => 11,
                'default'    => 1,
            ],
            'status' => [
                'type'       => 'ENUM',
                'constraint' => ['available', 'occupied', 'maintenance', 'reserved'],
                'default'    => 'available',
            ],
            'description' => [
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
        $forge->addUniqueKey('room_number');
        $forge->addKey('status');
        $forge->addKey('floor');
        $forge->createTable('rooms', true);
        echo "✓ Rooms table created manually\n\n";
    } else {
        echo "✓ Rooms table already exists\n\n";
    }
}

// Step 2: Seed data
$seeder = new \App\Database\Seeds\RoomsSeeder();
try {
    echo "Seeding rooms data...\n";
    $seeder->run();
    echo "✓ Seeding completed\n\n";
} catch (\Exception $e) {
    echo "Seeding error: " . $e->getMessage() . "\n";
    echo "You can manually add rooms through the interface.\n\n";
}

echo "=== Setup Complete ===\n";
echo "You can now access rooms at: http://localhost/HMS-ITE311-G7/public/rooms\n";

