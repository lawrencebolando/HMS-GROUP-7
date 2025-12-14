<?php

/**
 * Simple test page to verify IT account login
 * Access this in browser: http://localhost/HMS-ITE311-G7/public/test_login.php
 */

// Load CodeIgniter
require __DIR__ . '/vendor/autoload.php';

$pathsConfig = new \Config\Paths();
$pathsConfig->systemDirectory = __DIR__ . '/system';
$pathsConfig->appDirectory = __DIR__ . '/app';
$pathsConfig->writableDirectory = __DIR__ . '/writable';
$pathsConfig->viewDirectory = __DIR__ . '/app/Views';

$app = require_once __DIR__ . '/system/bootstrap.php';

$email = 'it.james@hospital.com';
$password = 'it1234';

echo "<h1>IT Account Login Test</h1>";
echo "<pre>";

try {
    $userModel = new \App\Models\UserModel();
    
    echo "Testing login for: {$email}\n";
    echo "Password: {$password}\n\n";
    echo str_repeat('=', 50) . "\n\n";
    
    // Test 1: Does user exist?
    echo "Test 1: Does user exist?\n";
    $user = $userModel->where('email', $email)->first();
    
    if (!$user) {
        echo "✗ USER NOT FOUND!\n";
        echo "\nRun this script first: php FINAL_FIX_IT_ACCOUNT.php\n";
        exit;
    }
    
    echo "✓ User found!\n";
    echo "  ID: {$user['id']}\n";
    echo "  Name: {$user['name']}\n";
    echo "  Email: {$user['email']}\n";
    echo "  Role: {$user['role']}\n";
    echo "  Status: {$user['status']}\n\n";
    
    // Test 2: Password verification
    echo "Test 2: Password verification\n";
    $passwordWorks = password_verify($password, $user['password']);
    
    if ($passwordWorks) {
        echo "✓ Password is correct!\n\n";
    } else {
        echo "✗ Password is WRONG!\n";
        echo "  Stored hash: " . substr($user['password'], 0, 30) . "...\n";
        echo "  Run: php FINAL_FIX_IT_ACCOUNT.php\n\n";
        exit;
    }
    
    // Test 3: UserModel verifyPassword
    echo "Test 3: UserModel->verifyPassword()\n";
    $verified = $userModel->verifyPassword($email, $password);
    
    if ($verified) {
        echo "✓✓✓ verifyPassword() WORKS! ✓✓✓\n\n";
        echo "Account is ready for login!\n\n";
        echo "Go to: <a href='/HMS-ITE311-G7/public/login'>Login Page</a>\n";
        echo "Use:\n";
        echo "  Email: {$email}\n";
        echo "  Password: {$password}\n";
    } else {
        echo "✗ verifyPassword() FAILED!\n";
        echo "  This is why login isn't working.\n";
        echo "  But password_verify() works, so the account is correct.\n";
        echo "  There might be a bug in UserModel->verifyPassword()\n";
    }
    
} catch (\Exception $e) {
    echo "ERROR: " . $e->getMessage() . "\n";
}

echo "</pre>";

