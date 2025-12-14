<?php

/**
 * Simple test page to verify IT account login
 * Access this in browser: http://localhost/HMS-ITE311-G7/public/test_login.php
 */

// Database connection settings (from your config)
$dbHost = 'localhost';
$dbUser = 'root';
$dbPass = '';
$dbName = 'ite-hms-g7';

$email = 'it.james@hospital.com';
$password = 'it1234';

?>
<!DOCTYPE html>
<html>
<head>
    <title>IT Account Login Test</title>
    <style>
        body { font-family: Arial, sans-serif; padding: 20px; background: #f5f5f5; }
        .container { max-width: 800px; margin: 0 auto; background: white; padding: 20px; border-radius: 8px; }
        pre { background: #f0f0f0; padding: 15px; border-radius: 4px; overflow-x: auto; }
        .success { color: green; font-weight: bold; }
        .error { color: red; font-weight: bold; }
        .warning { color: orange; font-weight: bold; }
        a { color: #3b82f6; text-decoration: none; }
        a:hover { text-decoration: underline; }
    </style>
</head>
<body>
    <div class="container">
        <h1>IT Account Login Test</h1>
        <pre>
<?php

try {
    // Connect to database using mysqli
    $conn = new mysqli($dbHost, $dbUser, $dbPass, $dbName);
    
    if ($conn->connect_error) {
        die("<span class='error'>✗ Database connection failed: " . $conn->connect_error . "</span>\n");
    }
    
    echo "Testing login for: {$email}\n";
    echo "Password: {$password}\n\n";
    echo str_repeat('=', 50) . "\n\n";
    
    // Test 1: Does user exist?
    echo "Test 1: Does user exist?\n";
    $stmt = $conn->prepare("SELECT * FROM `users` WHERE `email` = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();
    $stmt->close();
    
    if (!$user) {
        echo "<span class='error'>✗ USER NOT FOUND!</span>\n";
        echo "\nCreating account now...\n";
        
        // Try to update ENUM first
        try {
            $conn->query("ALTER TABLE `users` MODIFY COLUMN `role` ENUM(
                'admin', 'doctor', 'receptionist', 'patient', 
                'nurse', 'lab_technician', 'lab_staff', 'lab', 
                'accountant', 'accounts', 'it', 'it_staff', 'it_admin'
            ) DEFAULT 'patient'");
        } catch (Exception $e) {
            // ENUM might already be updated
        }
        
        // Create account directly
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        $name = 'James Anderson';
        
        $stmt = $conn->prepare("INSERT INTO `users` (`name`, `email`, `password`, `role`, `status`, `created_at`, `updated_at`) 
                                VALUES (?, ?, ?, 'it', 'active', NOW(), NOW())");
        $stmt->bind_param("sss", $name, $email, $hashedPassword);
        
        if ($stmt->execute()) {
            echo "<span class='success'>✓ Account created!</span>\n";
            $stmt->close();
            
            // Get the created user
            $stmt = $conn->prepare("SELECT * FROM `users` WHERE `email` = ?");
            $stmt->bind_param("s", $email);
            $stmt->execute();
            $result = $stmt->get_result();
            $user = $result->fetch_assoc();
            $stmt->close();
        } else {
            if (strpos($stmt->error, 'role') !== false) {
                echo "⚠ 'it' role not supported. Creating with 'admin' then updating...\n";
                $stmt->close();
                
                $stmt = $conn->prepare("INSERT INTO `users` (`name`, `email`, `password`, `role`, `status`, `created_at`, `updated_at`) 
                                       VALUES (?, ?, ?, 'admin', 'active', NOW(), NOW())");
                $stmt->bind_param("sss", $name, $email, $hashedPassword);
                $stmt->execute();
                $stmt->close();
                
                try {
                    $conn->query("ALTER TABLE `users` MODIFY COLUMN `role` ENUM(
                        'admin', 'doctor', 'receptionist', 'patient', 
                        'nurse', 'lab_technician', 'lab_staff', 'lab', 
                        'accountant', 'accounts', 'it', 'it_staff', 'it_admin'
                    ) DEFAULT 'patient'");
                    $conn->query("UPDATE `users` SET `role` = 'it' WHERE `email` = '" . $conn->real_escape_string($email) . "'");
                    echo "<span class='success'>✓ Account created and role updated!</span>\n";
                } catch (Exception $e2) {
                    echo "<span class='warning'>⚠ Could not update role. Account created with 'admin' role.</span>\n";
                }
                
                // Get the created user
                $stmt = $conn->prepare("SELECT * FROM `users` WHERE `email` = ?");
                $stmt->bind_param("s", $email);
                $stmt->execute();
                $result = $stmt->get_result();
                $user = $result->fetch_assoc();
                $stmt->close();
            } else {
                throw new Exception("Failed to create account: " . $stmt->error);
            }
        }
    } else {
        echo "<span class='success'>✓ User found!</span>\n";
    }
    
    echo "\nTest 2: Account Details\n";
    echo "  ID: {$user['id']}\n";
    echo "  Name: {$user['name']}\n";
    echo "  Email: {$user['email']}\n";
    echo "  Role: {$user['role']}\n";
    echo "  Status: {$user['status']}\n\n";
    
    // Test 3: Password verification
    echo "Test 3: Password verification\n";
    $passwordWorks = password_verify($password, $user['password']);
    
    if ($passwordWorks) {
        echo "<span class='success'>✓ Password is correct!</span>\n\n";
    } else {
        echo "<span class='error'>✗ Password is WRONG!</span>\n";
        echo "  Re-hashing password...\n";
        $newHash = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $conn->prepare("UPDATE `users` SET `password` = ? WHERE `email` = ?");
        $stmt->bind_param("ss", $newHash, $email);
        $stmt->execute();
        $stmt->close();
        
        // Get updated user
        $stmt = $conn->prepare("SELECT * FROM `users` WHERE `email` = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();
        $user = $result->fetch_assoc();
        $stmt->close();
        
        $passwordWorks = password_verify($password, $user['password']);
        if ($passwordWorks) {
            echo "<span class='success'>  ✓ Password fixed!</span>\n\n";
        }
    }
    
    // Test 4: Simulate UserModel verifyPassword
    echo "Test 4: Simulating UserModel->verifyPassword()\n";
    // This simulates what UserModel->verifyPassword() does
    $normalizedEmail = strtolower(trim($email));
    $stmt = $conn->prepare("SELECT * FROM `users` WHERE `email` = ?");
    $stmt->bind_param("s", $normalizedEmail);
    $stmt->execute();
    $result = $stmt->get_result();
    $testUser = $result->fetch_assoc();
    $stmt->close();
    
    if ($testUser && password_verify($password, $testUser['password'])) {
        echo "<span class='success'>✓✓✓ Password verification WORKS! ✓✓✓</span>\n\n";
        echo "<span class='success'>Account is ready for login!</span>\n\n";
        echo "Go to: <a href='/HMS-ITE311-G7/public/login' target='_blank'>Login Page</a>\n";
        echo "Use:\n";
        echo "  Email: {$email}\n";
        echo "  Password: {$password}\n\n";
        
        if (in_array($user['role'], ['it', 'it_staff', 'it_admin'])) {
            echo "You will be redirected to: /it/dashboard\n";
        } else {
            echo "<span class='warning'>⚠ Role is '{$user['role']}' - may redirect to different dashboard</span>\n";
        }
    } else {
        echo "<span class='error'>✗ Password verification FAILED!</span>\n";
        echo "  This shouldn't happen if Test 3 passed.\n";
    }
    
    $conn->close();
    
} catch (Exception $e) {
    echo "<span class='error'>ERROR: " . htmlspecialchars($e->getMessage()) . "</span>\n";
    echo "File: " . $e->getFile() . ":" . $e->getLine() . "\n";
}

?>
        </pre>
    </div>
</body>
</html>
