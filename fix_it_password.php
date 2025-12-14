<?php

/**
 * Quick fix script to update IT account passwords
 * 
 * This script updates existing IT accounts with the correct 6-character password
 * 
 * Usage: php fix_it_password.php
 */

// Load CodeIgniter
require __DIR__ . '/vendor/autoload.php';

// Get the environment file
$pathsConfig = new \Config\Paths();
$pathsConfig->systemDirectory = __DIR__ . '/system';
$pathsConfig->appDirectory = __DIR__ . '/app';
$pathsConfig->writableDirectory = __DIR__ . '/writable';
$pathsConfig->viewDirectory = __DIR__ . '/app/Views';

// Bootstrap CodeIgniter
$app = require_once __DIR__ . '/system/bootstrap.php';

echo "=== Fix IT Account Passwords ===\n\n";

try {
    $db = \Config\Database::connect();
    $userModel = new \App\Models\UserModel();

    // IT account emails
    $itAccounts = [
        'it.james@hospital.com',
        'it.lisa@hospital.com'
    ];

    $updated = 0;
    $notFound = 0;

    foreach ($itAccounts as $email) {
        $user = $userModel->where('email', $email)->first();
        
        if ($user) {
            // Update password to it1234 (6 characters)
            $userModel->update($user['id'], [
                'password' => 'it1234'
            ]);
            echo "✓ Updated password for: {$email}\n";
            $updated++;
        } else {
            echo "⊘ Account not found: {$email}\n";
            $notFound++;
        }
    }

    echo "\n=== Summary ===\n";
    echo "Updated: {$updated} accounts\n";
    echo "Not found: {$notFound} accounts\n\n";

    if ($updated > 0) {
        echo "✓ IT accounts can now be logged in with password: it1234\n";
    }

} catch (\Exception $e) {
    echo "✗ Error: " . $e->getMessage() . "\n";
    exit(1);
}

