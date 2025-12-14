-- ============================================
-- COMPLETE SQL TO CREATE IT ACCOUNT
-- Copy and paste ALL of this into phpMyAdmin SQL tab
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

-- Step 2: Delete existing account
DELETE FROM `users` WHERE `email` = 'it.james@hospital.com';

-- Step 3: Create the IT account
-- Password: it1234
-- IMPORTANT: First run this command in terminal to get the hash:
-- php -r "echo password_hash('it1234', PASSWORD_DEFAULT);"
-- Then replace HASH_BELOW with the output

INSERT INTO `users` (`name`, `email`, `password`, `role`, `status`, `created_at`, `updated_at`) 
VALUES (
    'James Anderson',
    'it.james@hospital.com',
    HASH_BELOW,
    'it',
    'active',
    NOW(),
    NOW()
);

-- Step 4: Verify account was created
SELECT id, name, email, role, status FROM `users` WHERE `email` = 'it.james@hospital.com';

