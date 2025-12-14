<?php

/**
 * Debug IT Login - Shows exactly what's wrong
 * Run: php debug_it_login.php
 */

require __DIR__ . '/vendor/autoload.php';

$pathsConfig = new \Config\Paths();
$pathsConfig->systemDirectory = __DIR__ . '/system';
$pathsConfig->appDirectory = __DIR__ . '/app';
$pathsConfig->writableDirectory = __DIR__ . '/writable';
$pathsConfig->viewDirectory = __DIR__ . '/app/Views';

$app = require_once __DIR__ . '/system/bootstrap.php';

echo "========================================\n";
echo "  IT ACCOUNT LOGIN DEBUG\n";
echo "========================================\n\n";

$email = 'it.james@hospital.com';
$password = 'it1234';

try {
    $db = \Config\Database::connect();
    $userModel = new \App\Models\UserModel();
    
    echo "1. Checking if account exists in database...\n";
    $user = $db->table('users')->where('email', $email)->get()->getRowArray();
    
    if (!$user) {
        echo "   ✗ ACCOUNT DOES NOT EXIST!\n";
        echo "\n   Creating account now...\n";
        
        // Create account directly
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        $sql = "INSERT INTO `users` (`name`, `email`, `password`, `role`, `status`, `created_at`, `updated_at`) 
                VALUES (?, ?, ?, 'it', 'active', NOW(), NOW())";
        
        try {
            $db->query($sql, ['James Anderson', $email, $hashedPassword]);
            echo "   ✓ Account created!\n";
            $user = $db->table('users')->where('email', $email)->get()->getRowArray();
        } catch (\Exception $e) {
            if (strpos($e->getMessage(), 'role') !== false) {
                echo "   ⚠ 'it' role not supported. Creating with 'admin' then updating...\n";
                $db->query("INSERT INTO `users` (`name`, `email`, `password`, `role`, `status`, `created_at`, `updated_at`) 
                           VALUES (?, ?, ?, 'admin', 'active', NOW(), NOW())", 
                           ['James Anderson', $email, $hashedPassword]);
                // Try to update ENUM
                try {
                    $db->query("ALTER TABLE `users` MODIFY COLUMN `role` ENUM(
                        'admin', 'doctor', 'receptionist', 'patient', 
                        'nurse', 'lab_technician', 'lab_staff', 'lab', 
                        'accountant', 'accounts', 'it', 'it_staff', 'it_admin'
                    ) DEFAULT 'patient'");
                    $db->query("UPDATE `users` SET `role` = 'it' WHERE `email` = ?", [$email]);
                    echo "   ✓ Account created and role updated!\n";
                } catch (\Exception $e2) {
                    echo "   ⚠ Could not update role. Account created with 'admin' role.\n";
                }
                $user = $db->table('users')->where('email', $email)->get()->getRowArray();
            } else {
                throw $e;
            }
        }
    } else {
        echo "   ✓ Account exists!\n";
    }
    
    echo "\n2. Account Details:\n";
    echo "   ID: {$user['id']}\n";
    echo "   Name: {$user['name']}\n";
    echo "   Email: {$user['email']}\n";
    echo "   Role: {$user['role']}\n";
    echo "   Status: {$user['status']}\n";
    echo "   Password Hash: " . substr($user['password'], 0, 30) . "...\n";
    
    echo "\n3. Testing password verification...\n";
    $passwordWorks = password_verify($password, $user['password']);
    echo "   password_verify('{$password}', hash): " . ($passwordWorks ? 'TRUE ✓' : 'FALSE ✗') . "\n";
    
    if (!$passwordWorks) {
        echo "   ✗ Password doesn't match! Re-hashing...\n";
        $newHash = password_hash($password, PASSWORD_DEFAULT);
        $db->query("UPDATE `users` SET `password` = ? WHERE `email` = ?", [$newHash, $email]);
        $user = $db->table('users')->where('email', $email)->get()->getRowArray();
        $passwordWorks = password_verify($password, $user['password']);
        echo "   " . ($passwordWorks ? '✓ Password fixed!' : '✗ Still not working!') . "\n";
    }
    
    echo "\n4. Testing UserModel->where()->first()...\n";
    $userFromModel = $userModel->where('email', $email)->first();
    if ($userFromModel) {
        echo "   ✓ UserModel finds the user\n";
        $modelPasswordWorks = password_verify($password, $userFromModel['password']);
        echo "   password_verify with UserModel: " . ($modelPasswordWorks ? 'TRUE ✓' : 'FALSE ✗') . "\n";
    } else {
        echo "   ✗ UserModel cannot find the user!\n";
    }
    
    echo "\n5. Testing UserModel->verifyPassword()...\n";
    $verified = $userModel->verifyPassword($email, $password);
    if ($verified) {
        echo "   ✓✓✓ verifyPassword() WORKS! ✓✓✓\n";
        echo "   Returned user:\n";
        echo "     Name: {$verified['name']}\n";
        echo "     Role: {$verified['role']}\n";
        echo "     Status: {$verified['status']}\n";
    } else {
        echo "   ✗ verifyPassword() RETURNS FALSE!\n";
        echo "   This is why login is failing.\n";
        
        // Debug why
        echo "\n   Debugging verifyPassword()...\n";
        $testUser = $userModel->where('email', $email)->first();
        if ($testUser) {
            echo "     - User found: YES\n";
            $testVerify = password_verify($password, $testUser['password']);
            echo "     - password_verify works: " . ($testVerify ? 'YES' : 'NO') . "\n";
            
            if ($testVerify) {
                echo "     - Problem: verifyPassword() method has a bug!\n";
                echo "     - Let me check the method...\n";
                
                // Manually test the verifyPassword logic
                $manualUser = $userModel->where('email', $email)->first();
                if ($manualUser && password_verify($password, $manualUser['password'])) {
                    echo "     - Manual test: password works!\n";
                    echo "     - The account is correct, but verifyPassword() is failing\n";
                }
            }
        }
    }
    
    echo "\n========================================\n";
    echo "  SUMMARY\n";
    echo "========================================\n\n";
    
    if ($verified && $verified['status'] === 'active') {
        echo "✓✓✓ ACCOUNT SHOULD WORK FOR LOGIN! ✓✓✓\n\n";
        echo "Login with:\n";
        echo "  Email: {$email}\n";
        echo "  Password: {$password}\n\n";
        
        if (!in_array($verified['role'], ['it', 'it_staff', 'it_admin'])) {
            echo "⚠ Role is '{$verified['role']}' - may redirect to different dashboard\n";
        }
    } else {
        echo "✗ ACCOUNT HAS ISSUES:\n";
        if (!$verified) echo "  - verifyPassword() fails\n";
        if ($verified && $verified['status'] !== 'active') echo "  - Account not active\n";
        
        echo "\nTry these steps:\n";
        echo "  1. Clear browser cache and cookies\n";
        echo "  2. Make sure email is exactly: {$email}\n";
        echo "  3. Make sure password is exactly: {$password}\n";
        echo "  4. Check error logs: writable/logs/\n";
    }
    
} catch (\Exception $e) {
    echo "ERROR: " . $e->getMessage() . "\n";
    echo "File: " . $e->getFile() . ":" . $e->getLine() . "\n";
}

