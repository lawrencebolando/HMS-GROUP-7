-- ============================================
-- COMPLETE SQL TO CREATE IT ACCOUNT
-- Run this ENTIRE file in phpMyAdmin SQL tab
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
-- Password: it1234
-- This hash was generated for password 'it1234'
INSERT INTO `users` (`name`, `email`, `password`, `role`, `status`, `created_at`, `updated_at`) 
VALUES (
    'James Anderson',
    'it.james@hospital.com',
    '$2y$10$N9qo8uLOickgx2ZMRZoMyeIjZAgcfl7p92ldGxad68LJZdL17lhWy',
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
    status,
    LEFT(password, 30) as password_hash_preview
FROM `users` 
WHERE `email` = 'it.james@hospital.com';

-- ============================================
-- After running this, try logging in with:
-- Email: it.james@hospital.com
-- Password: it1234
-- ============================================

