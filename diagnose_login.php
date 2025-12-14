<?php

/**
 * Comprehensive login diagnostic tool
 * 
 * Usage: php diagnose_login.php
 */

// Load CodeIgniter
require __DIR__ . '/vendor/autoload.php';

$pathsConfig = new \Config\Paths();
$pathsConfig->systemDirectory = __DIR__ . '/system';
$pathsConfig->appDirectory = __DIR__ . '/app';
$pathsConfig->writableDirectory = __DIR__ . '/writable';
$pathsConfig->viewDirectory = __DIR__ . '/app/Views';

$app = require_once __DIR__ . '/system/bootstrap.php';

echo "=== LOGIN DIAGNOSTIC TOOL ===\n\n";

$email = 'it.james@hospital.com';
$password = 'it1234';

try {
    $db = \Config\Database::connect();
    $userModel = new \App\Models\UserModel();
    
    echo "Database: " . $db->database . "\n";
    echo "Testing email: {$email}\n";
    echo "Testing password: {$password}\n\n";
    echo str_repeat('=', 60) . "\n\n";
    
    // Check 1: Does user exist?
    echo "CHECK 1: Does user exist in database?\n";
    $user = $userModel->where('email', $email)->first();
    
    if (!$user) {
        echo "✗ USER NOT FOUND!\n";
        echo "\nSearching for similar emails...\n";
        $allUsers = $db->table('users')->like('email', 'it%')->get()->getResultArray();
        if (empty($allUsers)) {
            echo "  No users found with 'it' in email\n";
        } else {
            echo "  Found users:\n";
            foreach ($allUsers as $u) {
                echo "    - {$u['email']} (Role: {$u['role']})\n";
            }
        }
        
        echo "\n=== SOLUTION ===\n";
        echo "Run: php force_create_it_account.php\n";
        exit(1);
    }
    
    echo "✓ User found!\n";
    echo "  ID: {$user['id']}\n";
    echo "  Name: {$user['name']}\n";
    echo "  Email: {$user['email']}\n";
    echo "  Role: {$user['role']}\n";
    echo "  Status: {$user['status']}\n";
    echo "  Password Hash: " . substr($user['password'], 0, 30) . "...\n\n";
    
    // Check 2: Is account active?
    echo "CHECK 2: Is account active?\n";
    if ($user['status'] !== 'active') {
        echo "✗ Account is NOT active! Status: {$user['status']}\n";
        echo "  Fixing...\n";
        $userModel->update($user['id'], ['status' => 'active']);
        echo "  ✓ Fixed\n\n";
    } else {
        echo "✓ Account is active\n\n";
    }
    
    // Check 3: Password verification
    echo "CHECK 3: Password verification\n";
    $passwordWorks = password_verify($password, $user['password']);
    
    if (!$passwordWorks) {
        echo "✗ Password does NOT match!\n";
        echo "  Stored hash: " . substr($user['password'], 0, 30) . "...\n";
        echo "  Testing password: {$password}\n";
        echo "  Fixing password...\n";
        
        $userModel->skipValidation(true);
        $userModel->update($user['id'], ['password' => $password]);
        $userModel->skipValidation(false);
        
        // Re-fetch
        $user = $userModel->where('email', $email)->first();
        $passwordWorks = password_verify($password, $user['password']);
        
        if ($passwordWorks) {
            echo "  ✓ Password fixed and verified!\n\n";
        } else {
            echo "  ✗ Password still doesn't work after fix!\n\n";
        }
    } else {
        echo "✓ Password matches!\n\n";
    }
    
    // Check 4: UserModel verifyPassword
    echo "CHECK 4: UserModel->verifyPassword() method\n";
    $verified = $userModel->verifyPassword($email, $password);
    
    if ($verified) {
        echo "✓ verifyPassword() returns user data\n";
        echo "  Name: {$verified['name']}\n";
        echo "  Role: {$verified['role']}\n";
        echo "  Status: {$verified['status']}\n\n";
    } else {
        echo "✗ verifyPassword() returns FALSE\n";
        echo "  This is why login is failing!\n\n";
        
        // Debug why
        echo "  Debugging verifyPassword()...\n";
        $testUser = $userModel->where('email', $email)->first();
        if ($testUser) {
            echo "    - User found in database: YES\n";
            $testVerify = password_verify($password, $testUser['password']);
            echo "    - password_verify() works: " . ($testVerify ? 'YES' : 'NO') . "\n";
            
            if (!$testVerify) {
                echo "    - Problem: password_verify() is failing\n";
                echo "    - Fixing password hash...\n";
                $newHash = password_hash($password, PASSWORD_DEFAULT);
                $db->query("UPDATE `users` SET `password` = ? WHERE `email` = ?", [$newHash, $email]);
                echo "    - ✓ Password hash updated\n";
            }
        }
        echo "\n";
    }
    
    // Check 5: Role check
    echo "CHECK 5: Role validation for redirect\n";
    $validRoles = ['it', 'it_staff', 'it_admin'];
    if (in_array($user['role'], $validRoles)) {
        echo "✓ Role is valid for IT portal\n";
        echo "  Will redirect to: /it/dashboard\n\n";
    } else {
        echo "⚠ Role '{$user['role']}' is not in IT roles list\n";
        echo "  Valid IT roles: " . implode(', ', $validRoles) . "\n";
        echo "  Fixing role...\n";
        $userModel->skipValidation(true);
        $userModel->update($user['id'], ['role' => 'it']);
        $userModel->skipValidation(false);
        echo "  ✓ Role updated to 'it'\n\n";
    }
    
    // Final test
    echo str_repeat('=', 60) . "\n";
    echo "FINAL TEST: Complete login simulation\n";
    echo str_repeat('=', 60) . "\n";
    
    $finalTest = $userModel->verifyPassword($email, $password);
    
    if ($finalTest && $finalTest['status'] === 'active') {
        echo "✓✓✓ LOGIN SHOULD WORK NOW! ✓✓✓\n\n";
        echo "Try logging in with:\n";
        echo "  Email: {$email}\n";
        echo "  Password: {$password}\n\n";
        
        if (in_array($finalTest['role'], ['it', 'it_staff', 'it_admin'])) {
            echo "You will be redirected to: /it/dashboard\n";
        } else {
            echo "⚠ Role is '{$finalTest['role']}' - may redirect elsewhere\n";
        }
    } else {
        echo "✗ LOGIN STILL NOT WORKING\n";
        if (!$finalTest) {
            echo "  - verifyPassword() still returns false\n";
        }
        if ($finalTest && $finalTest['status'] !== 'active') {
            echo "  - Account status is not 'active'\n";
        }
        echo "\nPlease check the error logs in: writable/logs/\n";
    }
    
} catch (\Exception $e) {
    echo "✗ Error: " . $e->getMessage() . "\n";
    echo "File: " . $e->getFile() . ":" . $e->getLine() . "\n";
}

