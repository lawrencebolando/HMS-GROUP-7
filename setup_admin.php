<?php
/**
 * Setup Script for Admin Account
 * 
 * This script will:
 * 1. Run the migration to create the users table
 * 2. Create an admin account
 * 
 * Run this from the command line: php setup_admin.php
 * Or access via browser: http://localhost/HMS-ITE311-G7/setup_admin.php
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

// Now we can use CodeIgniter
$db = \Config\Database::connect();
$forge = \Config\Database::forge();

echo "=== Admin Account Setup ===\n\n";

// Step 1: Check if users table exists, if not create it
try {
    $db->query("SELECT 1 FROM users LIMIT 1");
    echo "✓ Users table already exists\n";
} catch (\Exception $e) {
    echo "Creating users table...\n";
    
    $fields = [
        'id' => [
            'type'           => 'INT',
            'constraint'     => 11,
            'unsigned'       => true,
            'auto_increment' => true,
        ],
        'name' => [
            'type'       => 'VARCHAR',
            'constraint' => '100',
        ],
        'email' => [
            'type'       => 'VARCHAR',
            'constraint' => '100',
        ],
        'password' => [
            'type'       => 'VARCHAR',
            'constraint' => '255',
        ],
        'role' => [
            'type'       => 'ENUM',
            'constraint' => ['admin', 'doctor', 'receptionist', 'patient'],
            'default'    => 'patient',
        ],
        'status' => [
            'type'       => 'ENUM',
            'constraint' => ['active', 'inactive'],
            'default'    => 'active',
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
    $forge->addKey('email', false, true); // Unique key
    $forge->createTable('users', true);
    
    echo "✓ Users table created\n";
}

// Step 2: Create admin account
$userModel = new \App\Models\UserModel();

// Check if admin already exists
$existingAdmin = $userModel->where('email', 'admin@globalhospitals.com')->first();

if (!$existingAdmin) {
    $data = [
        'name'     => 'St. Elizabeth Hospital, Inc.',
        'email'    => 'admin@globalhospitals.com',
        'password' => 'admin123', // Will be hashed by model
        'role'     => 'admin',
        'status'   => 'active',
    ];
    
    $userModel->insert($data);
    echo "✓ Admin account created successfully!\n\n";
    echo "Login Credentials:\n";
    echo "Email: admin@globalhospitals.com\n";
    echo "Password: admin123\n";
    echo "Role: Admin\n\n";
} else {
    echo "✓ Admin account already exists!\n\n";
    echo "Login Credentials:\n";
    echo "Email: admin@globalhospitals.com\n";
    echo "Password: admin123\n";
    echo "Role: Admin\n\n";
}

echo "=== Setup Complete ===\n";
echo "You can now login at: http://localhost/HMS-ITE311-G7/public/login\n";

