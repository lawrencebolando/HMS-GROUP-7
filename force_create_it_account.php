<?php

/**
 * Force create IT account using raw SQL - guaranteed to work
 * 
 * Usage: php force_create_it_account.php
 */

// Load CodeIgniter
require __DIR__ . '/vendor/autoload.php';

$pathsConfig = new \Config\Paths();
$pathsConfig->systemDirectory = __DIR__ . '/system';
$pathsConfig->appDirectory = __DIR__ . '/app';
$pathsConfig->writableDirectory = __DIR__ . '/writable';
$pathsConfig->viewDirectory = __DIR__ . '/app/Views';

$app = require_once __DIR__ . '/system/bootstrap.php';

echo "=== FORCE CREATE IT ACCOUNT ===\n\n";

try {
    $db = \Config\Database::connect();
    
    if (!$db->tableExists('users')) {
        echo "✗ Users table does not exist!\n";
        exit(1);
    }
    
    $email = 'it.james@hospital.com';
    $password = 'it1234';
    $name = 'James Anderson';
    
    // First, delete existing account if it exists
    echo "Step 1: Removing any existing account...\n";
    $db->query("DELETE FROM `users` WHERE `email` = ?", [$email]);
    echo "✓ Cleaned up\n\n";
    
    // Hash the password
    echo "Step 2: Hashing password...\n";
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
    echo "✓ Password hashed: " . substr($hashedPassword, 0, 20) . "...\n\n";
    
    // Try to insert with 'it' role first
    echo "Step 3: Creating account with 'it' role...\n";
    try {
        $sql = "INSERT INTO `users` (`name`, `email`, `password`, `role`, `status`, `created_at`, `updated_at`) 
                VALUES (?, ?, ?, 'it', 'active', NOW(), NOW())";
        $db->query($sql, [$name, $email, $hashedPassword]);
        echo "✓ Account created with 'it' role!\n\n";
    } catch (\Exception $e) {
        // If 'it' role fails, try 'admin' role first, then we'll update it
        if (strpos($e->getMessage(), 'role') !== false || strpos($e->getMessage(), 'ENUM') !== false) {
            echo "⚠ 'it' role not supported. Creating with 'admin' role first...\n";
            
            $sql = "INSERT INTO `users` (`name`, `email`, `password`, `role`, `status`, `created_at`, `updated_at`) 
                    VALUES (?, ?, ?, 'admin', 'active', NOW(), NOW())";
            $db->query($sql, [$name, $email, $hashedPassword]);
            echo "✓ Account created with 'admin' role\n";
            
            // Now try to update the ENUM and change role
            echo "\nStep 4: Updating ENUM to support 'it' role...\n";
            try {
                $alterSql = "ALTER TABLE `users` MODIFY COLUMN `role` ENUM(
                    'admin', 'doctor', 'receptionist', 'patient', 
                    'nurse', 'lab_technician', 'lab_staff', 'lab', 
                    'accountant', 'accounts', 'it', 'it_staff', 'it_admin'
                ) DEFAULT 'patient'";
                $db->query($alterSql);
                echo "✓ ENUM updated\n";
                
                // Now update role to 'it'
                echo "\nStep 5: Changing role to 'it'...\n";
                $db->query("UPDATE `users` SET `role` = 'it' WHERE `email` = ?", [$email]);
                echo "✓ Role changed to 'it'\n\n";
            } catch (\Exception $e2) {
                echo "⚠ Could not update ENUM: " . $e2->getMessage() . "\n";
                echo "  Account created with 'admin' role - you can use it to login\n";
                echo "  Then manually update the role in database or run migration\n\n";
            }
        } else {
            throw $e;
        }
    }
    
    // Verify the account
    echo "Step 6: Verifying account...\n";
    $user = $db->table('users')->where('email', $email)->get()->getRowArray();
    
    if ($user) {
        echo "✓ Account verified!\n";
        echo "  ID: {$user['id']}\n";
        echo "  Name: {$user['name']}\n";
        echo "  Email: {$user['email']}\n";
        echo "  Role: {$user['role']}\n";
        echo "  Status: {$user['status']}\n\n";
        
        // Test password
        echo "Step 7: Testing password...\n";
        $passwordWorks = password_verify($password, $user['password']);
        
        if ($passwordWorks) {
            echo "✓✓✓ PASSWORD WORKS! ✓✓✓\n\n";
            
            // Test with UserModel
            echo "Step 8: Testing with UserModel...\n";
            $userModel = new \App\Models\UserModel();
            $verified = $userModel->verifyPassword($email, $password);
            
            if ($verified) {
                echo "✓✓✓ USERMODEL VERIFICATION WORKS! ✓✓✓\n\n";
                echo "=== SUCCESS ===\n";
                echo "Account is ready for login!\n\n";
                echo "Login Credentials:\n";
                echo "  Email: {$email}\n";
                echo "  Password: {$password}\n";
                
                if ($user['role'] === 'it' || $user['role'] === 'it_staff' || $user['role'] === 'it_admin') {
                    echo "  Will redirect to: /it/dashboard\n";
                } else {
                    echo "  ⚠ Role is '{$user['role']}' - may redirect to different dashboard\n";
                    echo "  You can still login, but might need to manually go to /it/dashboard\n";
                }
            } else {
                echo "✗ UserModel verification failed\n";
                echo "  But password_verify() works, so there might be an issue with UserModel\n";
            }
        } else {
            echo "✗ Password verification failed!\n";
            echo "  Re-hashing password...\n";
            $newHash = password_hash($password, PASSWORD_DEFAULT);
            $db->query("UPDATE `users` SET `password` = ? WHERE `email` = ?", [$newHash, $email]);
            echo "  ✓ Password re-hashed. Try logging in now.\n";
        }
    } else {
        echo "✗ Account verification failed!\n";
    }
    
} catch (\Exception $e) {
    echo "✗ Error: " . $e->getMessage() . "\n";
    echo "File: " . $e->getFile() . ":" . $e->getLine() . "\n";
    echo "\nStack trace:\n" . $e->getTraceAsString() . "\n";
}

