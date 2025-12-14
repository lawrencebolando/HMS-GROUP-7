<?php

/**
 * Direct MySQL connection - bypasses CodeIgniter completely
 * This will definitely work if MySQL is accessible
 * 
 * Usage: php create_it_account_mysql.php
 */

echo "=== Direct MySQL IT Account Creation ===\n\n";

// Try to get database config from .env or use defaults
$host = 'localhost';
$user = 'root';
$pass = '';
$dbname = '';

// Try to read .env file
if (file_exists(__DIR__ . '/.env')) {
    $env = file_get_contents(__DIR__ . '/.env');
    if (preg_match('/database\.default\.hostname\s*=\s*(.+)/', $env, $matches)) {
        $host = trim($matches[1]);
    }
    if (preg_match('/database\.default\.username\s*=\s*(.+)/', $env, $matches)) {
        $user = trim($matches[1]);
    }
    if (preg_match('/database\.default\.password\s*=\s*(.+)/', $env, $matches)) {
        $pass = trim($matches[1]);
    }
    if (preg_match('/database\.default\.database\s*=\s*(.+)/', $env, $matches)) {
        $dbname = trim($matches[1]);
    }
}

// Use the database from config
if (empty($dbname)) {
    $dbname = 'ite-hms-g7'; // From Database.php config
    echo "Using database from config: {$dbname}\n";
}

if (empty($dbname)) {
    echo "✗ Could not determine database name.\n";
    echo "Please edit this script and set \$dbname manually.\n";
    exit(1);
}

echo "Connecting to database...\n";
echo "  Host: {$host}\n";
echo "  User: {$user}\n";
echo "  Database: {$dbname}\n\n";

$conn = @mysqli_connect($host, $user, $pass, $dbname);

if (!$conn) {
    echo "✗ Connection failed: " . mysqli_connect_error() . "\n";
    echo "\nPlease check:\n";
    echo "  1. MySQL is running\n";
    echo "  2. Database '{$dbname}' exists\n";
    echo "  3. Username and password are correct\n";
    exit(1);
}

echo "✓ Connected to database!\n\n";

// Check if users table exists
$result = mysqli_query($conn, "SHOW TABLES LIKE 'users'");
if (mysqli_num_rows($result) == 0) {
    echo "✗ 'users' table does not exist!\n";
    echo "Please run migrations first: php spark migrate\n";
    mysqli_close($conn);
    exit(1);
}

echo "✓ 'users' table exists\n\n";

// Account details
$email = 'it.james@hospital.com';
$password = 'it1234';
$name = 'James Anderson';
$role = 'it';
$hashedPassword = password_hash($password, PASSWORD_DEFAULT);

echo "Account Details:\n";
echo "  Email: {$email}\n";
echo "  Password: {$password}\n";
echo "  Name: {$name}\n";
echo "  Role: {$role}\n";
echo "  Password Hash: " . substr($hashedPassword, 0, 30) . "...\n\n";

// Delete existing account
echo "Step 1: Removing existing account (if any)...\n";
mysqli_query($conn, "DELETE FROM `users` WHERE `email` = '" . mysqli_real_escape_string($conn, $email) . "'");
echo "✓ Cleaned up\n\n";

// Check role ENUM
echo "Step 2: Checking role ENUM...\n";
$result = mysqli_query($conn, "SHOW COLUMNS FROM `users` WHERE Field = 'role'");
$row = mysqli_fetch_assoc($result);
$enumValues = $row['Type'];

if (strpos($enumValues, "'it'") === false) {
    echo "⚠ 'it' role not in ENUM. Updating...\n";
    $alterSql = "ALTER TABLE `users` MODIFY COLUMN `role` ENUM(
        'admin', 'doctor', 'receptionist', 'patient', 
        'nurse', 'lab_technician', 'lab_staff', 'lab', 
        'accountant', 'accounts', 'it', 'it_staff', 'it_admin'
    ) DEFAULT 'patient'";
    
    if (mysqli_query($conn, $alterSql)) {
        echo "✓ ENUM updated\n\n";
    } else {
        echo "✗ Failed to update ENUM: " . mysqli_error($conn) . "\n";
        echo "  Trying to create account with 'admin' role first...\n";
        $role = 'admin';
    }
} else {
    echo "✓ 'it' role is supported\n\n";
}

// Create account
echo "Step 3: Creating account...\n";
$sql = "INSERT INTO `users` (`name`, `email`, `password`, `role`, `status`, `created_at`, `updated_at`) 
        VALUES (
            '" . mysqli_real_escape_string($conn, $name) . "',
            '" . mysqli_real_escape_string($conn, $email) . "',
            '" . mysqli_real_escape_string($conn, $hashedPassword) . "',
            '{$role}',
            'active',
            NOW(),
            NOW()
        )";

if (mysqli_query($conn, $sql)) {
    echo "✓ Account created!\n\n";
    
    // If we used 'admin' role, try to change it to 'it'
    if ($role === 'admin') {
        echo "Step 4: Changing role from 'admin' to 'it'...\n";
        // Try to update ENUM again
        $alterSql = "ALTER TABLE `users` MODIFY COLUMN `role` ENUM(
            'admin', 'doctor', 'receptionist', 'patient', 
            'nurse', 'lab_technician', 'lab_staff', 'lab', 
            'accountant', 'accounts', 'it', 'it_staff', 'it_admin'
        ) DEFAULT 'patient'";
        
        if (mysqli_query($conn, $alterSql)) {
            mysqli_query($conn, "UPDATE `users` SET `role` = 'it' WHERE `email` = '" . mysqli_real_escape_string($conn, $email) . "'");
            echo "✓ Role changed to 'it'\n\n";
        }
    }
} else {
    echo "✗ Failed to create account: " . mysqli_error($conn) . "\n";
    mysqli_close($conn);
    exit(1);
}

// Verify account
echo "Step 5: Verifying account...\n";
$result = mysqli_query($conn, "SELECT * FROM `users` WHERE `email` = '" . mysqli_real_escape_string($conn, $email) . "'");
$user = mysqli_fetch_assoc($result);

if ($user) {
    echo "✓ Account verified!\n";
    echo "  ID: {$user['id']}\n";
    echo "  Name: {$user['name']}\n";
    echo "  Email: {$user['email']}\n";
    echo "  Role: {$user['role']}\n";
    echo "  Status: {$user['status']}\n\n";
    
    // Test password
    echo "Step 6: Testing password...\n";
    if (password_verify($password, $user['password'])) {
        echo "✓✓✓ PASSWORD WORKS! ✓✓✓\n\n";
        
        echo "=== SUCCESS ===\n";
        echo "Account is ready for login!\n\n";
        echo "Login Credentials:\n";
        echo "  Email: {$email}\n";
        echo "  Password: {$password}\n";
        echo "  URL: /it/dashboard\n\n";
        
        if ($user['role'] !== 'it' && $user['role'] !== 'it_staff' && $user['role'] !== 'it_admin') {
            echo "⚠ Role is '{$user['role']}' - you may need to manually update it to 'it'\n";
            echo "  Or the ENUM doesn't support 'it' role yet\n";
        }
    } else {
        echo "✗ Password verification failed!\n";
        echo "  This shouldn't happen. Re-hashing...\n";
        $newHash = password_hash($password, PASSWORD_DEFAULT);
        mysqli_query($conn, "UPDATE `users` SET `password` = '" . mysqli_real_escape_string($conn, $newHash) . "' WHERE `email` = '" . mysqli_real_escape_string($conn, $email) . "'");
        echo "  ✓ Password re-hashed. Try logging in now.\n";
    }
} else {
    echo "✗ Account verification failed!\n";
}

mysqli_close($conn);

echo "\n=== Done ===\n";

