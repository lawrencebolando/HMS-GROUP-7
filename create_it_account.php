<?php

/**
 * Direct script to create/fix IT account
 * 
 * Usage: php create_it_account.php
 */

// Load CodeIgniter
require __DIR__ . '/vendor/autoload.php';

$pathsConfig = new \Config\Paths();
$pathsConfig->systemDirectory = __DIR__ . '/system';
$pathsConfig->appDirectory = __DIR__ . '/app';
$pathsConfig->writableDirectory = __DIR__ . '/writable';
$pathsConfig->viewDirectory = __DIR__ . '/app/Views';

$app = require_once __DIR__ . '/system/bootstrap.php';

echo "=== Creating/Fixing IT Account ===\n\n";

try {
    $db = \Config\Database::connect();
    
    if (!$db->tableExists('users')) {
        echo "✗ Users table does not exist. Please run migrations first.\n";
        exit(1);
    }
    
    $userModel = new \App\Models\UserModel();
    
    // IT account to create/update
    $email = 'it.james@hospital.com';
    $password = 'it1234';
    $name = 'James Anderson';
    $role = 'it';
    
    echo "Looking for account: {$email}\n";
    
    $existing = $userModel->where('email', $email)->first();
    
    if ($existing) {
        echo "✓ Account exists. Updating...\n";
        
        // Update with correct password and role
        $updateData = [
            'password' => $password,
            'role' => $role,
            'status' => 'active',
            'name' => $name
        ];
        
        // Temporarily skip validation to avoid role validation issues
        $userModel->skipValidation(true);
        $userModel->update($existing['id'], $updateData);
        $userModel->skipValidation(false);
        
        echo "✓ Account updated!\n";
    } else {
        echo "Creating new account...\n";
        
        $newUser = [
            'name' => $name,
            'email' => $email,
            'password' => $password,
            'role' => $role,
            'status' => 'active'
        ];
        
        // Temporarily skip validation
        $userModel->skipValidation(true);
        $userModel->insert($newUser);
        $userModel->skipValidation(false);
        
        echo "✓ Account created!\n";
    }
    
    // Verify the account
    $user = $userModel->where('email', $email)->first();
    
    if ($user) {
        echo "\n=== Account Details ===\n";
        echo "Email: {$user['email']}\n";
        echo "Name: {$user['name']}\n";
        echo "Role: {$user['role']}\n";
        echo "Status: {$user['status']}\n";
        
        // Test password
        $passwordWorks = password_verify($password, $user['password']);
        echo "Password verification: " . ($passwordWorks ? 'PASS ✓' : 'FAIL ✗') . "\n";
        
        if ($passwordWorks) {
            echo "\n✓ IT account is ready!\n";
            echo "You can now login with:\n";
            echo "  Email: {$email}\n";
            echo "  Password: {$password}\n";
        } else {
            echo "\n⚠ Password verification failed. Trying to fix...\n";
            $userModel->skipValidation(true);
            $userModel->update($user['id'], ['password' => $password]);
            $userModel->skipValidation(false);
            echo "✓ Password re-hashed. Try logging in again.\n";
        }
    }
    
} catch (\Exception $e) {
    echo "✗ Error: " . $e->getMessage() . "\n";
    if (strpos($e->getMessage(), 'role') !== false) {
        echo "\n⚠ Role validation issue detected.\n";
        echo "The role '{$role}' might not be in the database ENUM.\n";
        echo "You may need to run the migration to update the users table:\n";
        echo "  php spark migrate\n";
    }
}

