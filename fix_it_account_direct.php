<?php

/**
 * Direct database fix for IT account - bypasses all validation
 * 
 * Usage: php fix_it_account_direct.php
 */

// Load CodeIgniter
require __DIR__ . '/vendor/autoload.php';

$pathsConfig = new \Config\Paths();
$pathsConfig->systemDirectory = __DIR__ . '/system';
$pathsConfig->appDirectory = __DIR__ . '/app';
$pathsConfig->writableDirectory = __DIR__ . '/writable';
$pathsConfig->viewDirectory = __DIR__ . '/app/Views';

$app = require_once __DIR__ . '/system/bootstrap.php';

echo "=== Direct IT Account Fix ===\n\n";

try {
    $db = \Config\Database::connect();
    
    if (!$db->tableExists('users')) {
        echo "✗ Users table does not exist!\n";
        exit(1);
    }
    
    $email = 'it.james@hospital.com';
    $password = 'it1234';
    $name = 'James Anderson';
    $role = 'it';
    
    // Check if account exists
    $existing = $db->table('users')->where('email', $email)->get()->getRowArray();
    
    if ($existing) {
        echo "Account exists. Updating directly in database...\n";
        
        // Hash password directly
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        
        // Update using raw query to bypass validation
        $sql = "UPDATE `users` SET 
                `password` = ?,
                `role` = ?,
                `status` = 'active',
                `name` = ?,
                `updated_at` = NOW()
                WHERE `email` = ?";
        
        $db->query($sql, [$hashedPassword, $role, $name, $email]);
        
        echo "✓ Account updated directly in database\n";
    } else {
        echo "Account does not exist. Creating directly in database...\n";
        
        // Hash password
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        
        // Insert using raw query to bypass validation
        $sql = "INSERT INTO `users` (`name`, `email`, `password`, `role`, `status`, `created_at`, `updated_at`) 
                VALUES (?, ?, ?, ?, 'active', NOW(), NOW())";
        
        try {
            $db->query($sql, [$name, $email, $hashedPassword, $role]);
            echo "✓ Account created directly in database\n";
        } catch (\Exception $e) {
            // If role ENUM doesn't support 'it', try with a different approach
            if (strpos($e->getMessage(), 'role') !== false || strpos($e->getMessage(), 'ENUM') !== false) {
                echo "⚠ Role 'it' not in ENUM. Trying to update ENUM first...\n";
                
                // Try to alter the ENUM
                try {
                    $alterSql = "ALTER TABLE `users` MODIFY COLUMN `role` ENUM(
                        'admin', 
                        'doctor', 
                        'receptionist', 
                        'patient', 
                        'nurse', 
                        'lab_technician', 
                        'lab_staff', 
                        'lab', 
                        'accountant', 
                        'accounts', 
                        'it', 
                        'it_staff', 
                        'it_admin'
                    ) DEFAULT 'patient'";
                    
                    $db->query($alterSql);
                    echo "✓ ENUM updated. Now creating account...\n";
                    
                    // Try insert again
                    $db->query($sql, [$name, $email, $hashedPassword, $role]);
                    echo "✓ Account created!\n";
                } catch (\Exception $e2) {
                    echo "✗ Could not update ENUM: " . $e2->getMessage() . "\n";
                    echo "Please run: php spark migrate\n";
                    exit(1);
                }
            } else {
                throw $e;
            }
        }
    }
    
    // Verify the account
    $user = $db->table('users')->where('email', $email)->get()->getRowArray();
    
    if ($user) {
        echo "\n=== Account Verification ===\n";
        echo "ID: {$user['id']}\n";
        echo "Name: {$user['name']}\n";
        echo "Email: {$user['email']}\n";
        echo "Role: {$user['role']}\n";
        echo "Status: {$user['status']}\n";
        
        // Test password
        $passwordWorks = password_verify($password, $user['password']);
        echo "Password '{$password}' verification: " . ($passwordWorks ? 'PASS ✓' : 'FAIL ✗') . "\n";
        
        if ($passwordWorks && $user['status'] === 'active') {
            echo "\n✓✓✓ IT ACCOUNT IS READY! ✓✓✓\n";
            echo "\nLogin Credentials:\n";
            echo "  Email: {$email}\n";
            echo "  Password: {$password}\n";
            echo "  URL: /it/dashboard\n\n";
        } else {
            if (!$passwordWorks) {
                echo "\n⚠ Password verification failed. Re-hashing...\n";
                $newHash = password_hash($password, PASSWORD_DEFAULT);
                $db->query("UPDATE `users` SET `password` = ? WHERE `email` = ?", [$newHash, $email]);
                echo "✓ Password re-hashed. Try logging in again.\n";
            }
            if ($user['status'] !== 'active') {
                echo "\n⚠ Account is not active. Activating...\n";
                $db->query("UPDATE `users` SET `status` = 'active' WHERE `email` = ?", [$email]);
                echo "✓ Account activated.\n";
            }
        }
    } else {
        echo "✗ Account verification failed - account not found after creation!\n";
    }
    
} catch (\Exception $e) {
    echo "✗ Error: " . $e->getMessage() . "\n";
    echo "File: " . $e->getFile() . ":" . $e->getLine() . "\n";
}

