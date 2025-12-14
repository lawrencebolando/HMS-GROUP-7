<?php

/**
 * Diagnostic script to check IT account status
 * 
 * Usage: php check_it_account.php
 */

// Load CodeIgniter
require __DIR__ . '/vendor/autoload.php';

$pathsConfig = new \Config\Paths();
$pathsConfig->systemDirectory = __DIR__ . '/system';
$pathsConfig->appDirectory = __DIR__ . '/app';
$pathsConfig->writableDirectory = __DIR__ . '/writable';
$pathsConfig->viewDirectory = __DIR__ . '/app/Views';

$app = require_once __DIR__ . '/system/bootstrap.php';

echo "=== IT Account Diagnostic ===\n\n";

try {
    $db = \Config\Database::connect();
    $userModel = new \App\Models\UserModel();

    $itEmails = [
        'it.james@hospital.com',
        'it.lisa@hospital.com'
    ];

    foreach ($itEmails as $email) {
        echo "Checking: {$email}\n";
        echo str_repeat('-', 50) . "\n";
        
        $user = $userModel->where('email', $email)->first();
        
        if (!$user) {
            echo "✗ Account NOT FOUND in database\n";
            echo "  Creating account now...\n";
            
            $newUser = [
                'name' => $email === 'it.james@hospital.com' ? 'James Anderson' : 'Lisa Brown',
                'email' => $email,
                'password' => 'it1234',
                'role' => $email === 'it.james@hospital.com' ? 'it' : 'it_admin',
                'status' => 'active'
            ];
            
            try {
                $userModel->insert($newUser);
                echo "✓ Account created successfully!\n";
                $user = $userModel->where('email', $email)->first();
            } catch (\Exception $e) {
                echo "✗ Error creating account: " . $e->getMessage() . "\n";
                continue;
            }
        } else {
            echo "✓ Account found\n";
        }
        
        if ($user) {
            echo "  ID: {$user['id']}\n";
            echo "  Name: {$user['name']}\n";
            echo "  Email: {$user['email']}\n";
            echo "  Role: {$user['role']}\n";
            echo "  Status: {$user['status']}\n";
            echo "  Password Hash: " . substr($user['password'], 0, 20) . "...\n";
            
            // Test password verification
            $testPassword = 'it1234';
            $passwordMatch = password_verify($testPassword, $user['password']);
            echo "  Password 'it1234' matches: " . ($passwordMatch ? 'YES ✓' : 'NO ✗') . "\n";
            
            if (!$passwordMatch) {
                echo "  Updating password...\n";
                $userModel->update($user['id'], ['password' => 'it1234']);
                echo "  ✓ Password updated\n";
            }
            
            // Check if role is correct
            $validRoles = ['it', 'it_staff', 'it_admin'];
            if (!in_array($user['role'], $validRoles)) {
                echo "  ⚠ Role '{$user['role']}' is not a valid IT role\n";
                echo "  Updating role to 'it'...\n";
                $userModel->update($user['id'], ['role' => 'it']);
                echo "  ✓ Role updated\n";
            }
            
            // Check if status is active
            if ($user['status'] !== 'active') {
                echo "  ⚠ Account is not active\n";
                echo "  Updating status to 'active'...\n";
                $userModel->update($user['id'], ['status' => 'active']);
                echo "  ✓ Status updated\n";
            }
        }
        
        echo "\n";
    }
    
    echo "=== Summary ===\n";
    echo "IT accounts should now be ready to use.\n";
    echo "Try logging in with:\n";
    echo "  Email: it.james@hospital.com\n";
    echo "  Password: it1234\n\n";
    
} catch (\Exception $e) {
    echo "✗ Error: " . $e->getMessage() . "\n";
    echo "Stack trace:\n" . $e->getTraceAsString() . "\n";
}

