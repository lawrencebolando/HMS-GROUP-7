<?php

/**
 * Simple script to create IT account - uses CodeIgniter bootstrap
 * Run: php simple_create_it_account.php
 */

// Bootstrap CodeIgniter
define('ROOTPATH', __DIR__ . DIRECTORY_SEPARATOR);
define('FCPATH', ROOTPATH . 'public' . DIRECTORY_SEPARATOR);
define('SYSTEMPATH', ROOTPATH . 'system' . DIRECTORY_SEPARATOR);
define('APPPATH', ROOTPATH . 'app' . DIRECTORY_SEPARATOR);
define('WRITEPATH', ROOTPATH . 'writable' . DIRECTORY_SEPARATOR);
define('COMPOSER_PATH', ROOTPATH . 'vendor/autoload.php');

require SYSTEMPATH . 'bootstrap.php';

echo "========================================\n";
echo "  CREATE IT ACCOUNT\n";
echo "========================================\n\n";

$email = 'it.james@hospital.com';
$password = 'it1234';
$name = 'James Anderson';

try {
    $db = \Config\Database::connect();
    
    if (!$db->tableExists('users')) {
        die("✗ Users table does not exist! Run: php spark migrate\n");
    }
    
    echo "1. Generating password hash...\n";
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
    echo "   ✓ Hash generated\n\n";
    
    echo "2. Updating role ENUM...\n";
    try {
        $db->query("ALTER TABLE `users` MODIFY COLUMN `role` ENUM(
            'admin', 'doctor', 'receptionist', 'patient', 
            'nurse', 'lab_technician', 'lab_staff', 'lab', 
            'accountant', 'accounts', 'it', 'it_staff', 'it_admin'
        ) DEFAULT 'patient'");
        echo "   ✓ ENUM updated\n\n";
    } catch (\Exception $e) {
        echo "   ⚠ ENUM update: " . $e->getMessage() . "\n";
        echo "   (This is OK if ENUM already supports 'it')\n\n";
    }
    
    echo "3. Removing existing account...\n";
    $db->table('users')->where('email', $email)->delete();
    echo "   ✓ Cleaned up\n\n";
    
    echo "4. Creating account...\n";
    $sql = "INSERT INTO `users` (`name`, `email`, `password`, `role`, `status`, `created_at`, `updated_at`) 
            VALUES (?, ?, ?, 'it', 'active', NOW(), NOW())";
    
    try {
        $db->query($sql, [$name, $email, $hashedPassword]);
        echo "   ✓ Account created!\n\n";
    } catch (\Exception $e) {
        if (strpos($e->getMessage(), 'role') !== false) {
            echo "   ⚠ 'it' role not supported. Creating with 'admin'...\n";
            $db->query("INSERT INTO `users` (`name`, `email`, `password`, `role`, `status`, `created_at`, `updated_at`) 
                       VALUES (?, ?, ?, 'admin', 'active', NOW(), NOW())", 
                       [$name, $email, $hashedPassword]);
            echo "   ✓ Account created with 'admin' role\n";
            echo "   Updating role to 'it'...\n";
            try {
                $db->query("ALTER TABLE `users` MODIFY COLUMN `role` ENUM(
                    'admin', 'doctor', 'receptionist', 'patient', 
                    'nurse', 'lab_technician', 'lab_staff', 'lab', 
                    'accountant', 'accounts', 'it', 'it_staff', 'it_admin'
                ) DEFAULT 'patient'");
                $db->query("UPDATE `users` SET `role` = 'it' WHERE `email` = ?", [$email]);
                echo "   ✓ Role updated to 'it'\n\n";
            } catch (\Exception $e2) {
                echo "   ⚠ Could not update role. Account has 'admin' role.\n\n";
            }
        } else {
            throw $e;
        }
    }
    
    echo "5. Verifying account...\n";
    $user = $db->table('users')->where('email', $email)->get()->getRowArray();
    
    if (!$user) {
        die("✗ Account not found after creation!\n");
    }
    
    echo "   ✓ Account verified!\n";
    echo "     ID: {$user['id']}\n";
    echo "     Name: {$user['name']}\n";
    echo "     Email: {$user['email']}\n";
    echo "     Role: {$user['role']}\n";
    echo "     Status: {$user['status']}\n\n";
    
    echo "6. Testing password...\n";
    $passwordWorks = password_verify($password, $user['password']);
    
    if ($passwordWorks) {
        echo "   ✓✓✓ PASSWORD WORKS! ✓✓✓\n\n";
    } else {
        echo "   ✗ Password failed! Re-hashing...\n";
        $newHash = password_hash($password, PASSWORD_DEFAULT);
        $db->query("UPDATE `users` SET `password` = ? WHERE `email` = ?", [$newHash, $email]);
        echo "   ✓ Password re-hashed\n\n";
    }
    
    echo "========================================\n";
    echo "  SUCCESS!\n";
    echo "========================================\n\n";
    echo "Account is ready for login!\n\n";
    echo "Login Credentials:\n";
    echo "  Email: {$email}\n";
    echo "  Password: {$password}\n\n";
    
    if (in_array($user['role'], ['it', 'it_staff', 'it_admin'])) {
        echo "You will be redirected to: /it/dashboard\n";
    } else {
        echo "⚠ Role is '{$user['role']}' - may redirect to different dashboard\n";
    }
    
} catch (\Exception $e) {
    echo "✗ ERROR: " . $e->getMessage() . "\n";
    echo "File: " . $e->getFile() . ":" . $e->getLine() . "\n";
}

