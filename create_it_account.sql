-- Direct SQL to create IT account
-- Run this in phpMyAdmin SQL tab

-- First, update the ENUM to support 'it' role
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

-- Delete existing account if it exists
DELETE FROM `users` WHERE `email` = 'it.james@hospital.com';

-- Create the IT account
-- Password: it1234
-- NOTE: You need to generate the password hash first!
-- Run this PHP command to get the hash: php -r "echo password_hash('it1234', PASSWORD_DEFAULT);"
-- Then replace HASH_HERE below with the generated hash

INSERT INTO `users` (`name`, `email`, `password`, `role`, `status`, `created_at`, `updated_at`) 
VALUES (
    'James Anderson',
    'it.james@hospital.com',
    HASH_HERE, -- Replace with hash from: php -r "echo password_hash('it1234', PASSWORD_DEFAULT);"
    'it',
    'active',
    NOW(),
    NOW()
);

-- Verify the account was created
SELECT id, name, email, role, status FROM `users` WHERE `email` = 'it.james@hospital.com';

