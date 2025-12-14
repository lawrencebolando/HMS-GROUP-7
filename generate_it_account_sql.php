<?php

/**
 * Generate SQL file with correct password hash
 * Run: php generate_it_account_sql.php
 * Then copy the output SQL and run it in phpMyAdmin
 */

$password = 'it1234';
$hashedPassword = password_hash($password, PASSWORD_DEFAULT);

$sql = <<<SQL
-- ============================================
-- COMPLETE SQL TO CREATE IT ACCOUNT
-- Copy and paste this ENTIRE block into phpMyAdmin SQL tab
-- ============================================

-- Step 1: Update ENUM to support 'it' role
ALTER TABLE `users` MODIFY COLUMN `role` ENUM(
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
) DEFAULT 'patient';

-- Step 2: Delete existing account if it exists
DELETE FROM `users` WHERE `email` = 'it.james@hospital.com';

-- Step 3: Create the IT account
-- Password: {$password}
INSERT INTO `users` (`name`, `email`, `password`, `role`, `status`, `created_at`, `updated_at`) 
VALUES (
    'James Anderson',
    'it.james@hospital.com',
    '{$hashedPassword}',
    'it',
    'active',
    NOW(),
    NOW()
);

-- Step 4: Verify the account was created
SELECT 
    id, 
    name, 
    email, 
    role, 
    status
FROM `users` 
WHERE `email` = 'it.james@hospital.com';

-- ============================================
-- Login Credentials:
-- Email: it.james@hospital.com
-- Password: {$password}
-- ============================================
SQL;

echo $sql;
echo "\n\n";
echo "=== Copy the SQL above and run it in phpMyAdmin ===\n";
echo "Or save it to a file and import it.\n";

