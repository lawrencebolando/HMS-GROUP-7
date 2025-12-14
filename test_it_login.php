<?php

/**
 * Test script to verify IT account login
 * 
 * Usage: php test_it_login.php
 */

// Load CodeIgniter
require __DIR__ . '/vendor/autoload.php';

$pathsConfig = new \Config\Paths();
$pathsConfig->systemDirectory = __DIR__ . '/system';
$pathsConfig->appDirectory = __DIR__ . '/app';
$pathsConfig->writableDirectory = __DIR__ . '/writable';
$pathsConfig->viewDirectory = __DIR__ . '/app/Views';

$app = require_once __DIR__ . '/system/bootstrap.php';

echo "=== Testing IT Account Login ===\n\n";

try {
    $db = \Config\Database::connect();
    $userModel = new \App\Models\UserModel();
    
    $email = 'it.james@hospital.com';
    $password = 'it1234';
    
    echo "Testing login for: {$email}\n";
    echo str_repeat('=', 50) . "\n\n";
    
    // Step 1: Check if user exists
    echo "Step 1: Checking if user exists...\n";
    $user = $userModel->where('email', $email)->first();
    
    if (!$user) {
        echo "✗ User NOT FOUND in database!\n";
        echo "\nRun this to create the account:\n";
        echo "  php fix_it_account_direct.php\n";
        exit(1);
    }
    
    echo "✓ User found!\n";
    echo "  ID: {$user['id']}\n";
    echo "  Name: {$user['name']}\n";
    echo "  Email: {$user['email']}\n";
    echo "  Role: {$user['role']}\n";
    echo "  Status: {$user['status']}\n";
    echo "  Password Hash: " . substr($user['password'], 0, 30) . "...\n\n";
    
    // Step 2: Check status
    echo "Step 2: Checking account status...\n";
    if ($user['status'] !== 'active') {
        echo "✗ Account is NOT active! Status: {$user['status']}\n";
        echo "  Fixing status...\n";
        $userModel->update($user['id'], ['status' => 'active']);
        echo "  ✓ Status updated to 'active'\n\n";
    } else {
        echo "✓ Account is active\n\n";
    }
    
    // Step 3: Check role
    echo "Step 3: Checking role...\n";
    $validRoles = ['it', 'it_staff', 'it_admin'];
    if (!in_array($user['role'], $validRoles)) {
        echo "⚠ Role '{$user['role']}' is not a valid IT role\n";
        echo "  Valid roles: " . implode(', ', $validRoles) . "\n";
        echo "  Fixing role...\n";
        $userModel->skipValidation(true);
        $userModel->update($user['id'], ['role' => 'it']);
        $userModel->skipValidation(false);
        echo "  ✓ Role updated to 'it'\n\n";
        // Refresh user data
        $user = $userModel->where('email', $email)->first();
    } else {
        echo "✓ Role is valid: {$user['role']}\n\n";
    }
    
    // Step 4: Test password verification
    echo "Step 4: Testing password verification...\n";
    $passwordWorks = password_verify($password, $user['password']);
    
    if (!$passwordWorks) {
        echo "✗ Password verification FAILED!\n";
        echo "  The stored password hash doesn't match '{$password}'\n";
        echo "  Re-hashing password...\n";
        
        $userModel->skipValidation(true);
        $userModel->update($user['id'], ['password' => $password]);
        $userModel->skipValidation(false);
        
        // Test again
        $user = $userModel->where('email', $email)->first();
        $passwordWorks = password_verify($password, $user['password']);
        
        if ($passwordWorks) {
            echo "  ✓ Password re-hashed and verified!\n\n";
        } else {
            echo "  ✗ Password still doesn't work after re-hash!\n\n";
        }
    } else {
        echo "✓ Password verification PASSED!\n\n";
    }
    
    // Step 5: Test using verifyPassword method
    echo "Step 5: Testing UserModel->verifyPassword() method...\n";
    $verifiedUser = $userModel->verifyPassword($email, $password);
    
    if ($verifiedUser) {
        echo "✓ verifyPassword() method works!\n";
        echo "  User data returned:\n";
        echo "    Name: {$verifiedUser['name']}\n";
        echo "    Role: {$verifiedUser['role']}\n";
        echo "    Status: {$verifiedUser['status']}\n\n";
    } else {
        echo "✗ verifyPassword() method FAILED!\n";
        echo "  This is the method used by the login form.\n\n";
    }
    
    // Final summary
    echo str_repeat('=', 50) . "\n";
    echo "=== FINAL RESULT ===\n";
    
    if ($verifiedUser && $verifiedUser['status'] === 'active') {
        echo "✓✓✓ ACCOUNT IS READY FOR LOGIN! ✓✓✓\n\n";
        echo "Login Credentials:\n";
        echo "  Email: {$email}\n";
        echo "  Password: {$password}\n";
        echo "  URL: /it/dashboard\n\n";
        
        if (!in_array($verifiedUser['role'], ['it', 'it_staff', 'it_admin'])) {
            echo "⚠ WARNING: Role '{$verifiedUser['role']}' may not redirect correctly.\n";
            echo "  Expected: it, it_staff, or it_admin\n";
        }
    } else {
        echo "✗ ACCOUNT IS NOT READY\n";
        if (!$verifiedUser) {
            echo "  - Password verification failed\n";
        }
        if ($verifiedUser && $verifiedUser['status'] !== 'active') {
            echo "  - Account is not active\n";
        }
        echo "\nRun this to fix:\n";
        echo "  php fix_it_account_direct.php\n";
    }
    
} catch (\Exception $e) {
    echo "✗ Error: " . $e->getMessage() . "\n";
    echo "File: " . $e->getFile() . ":" . $e->getLine() . "\n";
}

