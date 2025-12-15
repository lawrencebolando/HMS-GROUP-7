<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class UpdateUsersTableRoles extends Migration
{
    public function up()
    {
        // Check if users table exists
        if (!$this->db->tableExists('users')) {
            return;
        }

        // Get current role column definition
        $fields = $this->db->getFieldData('users');
        $currentRoleType = null;
        foreach ($fields as $field) {
            if ($field->name === 'role') {
                $currentRoleType = $field->type;
                break;
            }
        }

        // If role is ENUM, we need to modify it
        // Note: MySQL doesn't support direct ENUM modification, so we'll use ALTER TABLE
        // This is a workaround for existing databases
        
        // For MySQL/MariaDB, we need to alter the column
        $db = \Config\Database::connect();
        
        // Check database driver
        if ($db->DBDriver === 'MySQLi' || $db->DBDriver === 'MySQL') {
            // Modify the ENUM to include new roles
            $sql = "ALTER TABLE `users` MODIFY COLUMN `role` ENUM(
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
                'it_admin',
                'pharmacy',
                'pharmacist',
                'pharmacy_staff'
            ) DEFAULT 'patient'";
            
            try {
                $this->db->query($sql);
                log_message('info', 'Updated users.role ENUM to include new portal roles');
            } catch (\Exception $e) {
                log_message('error', 'Error updating users.role ENUM: ' . $e->getMessage());
                // If it fails, it might be because the column already has the new values
                // or the database doesn't support this operation
            }
        }
    }

    public function down()
    {
        // Revert to original roles only
        if (!$this->db->tableExists('users')) {
            return;
        }

        $db = \Config\Database::connect();
        
        if ($db->DBDriver === 'MySQLi' || $db->DBDriver === 'MySQL') {
            $sql = "ALTER TABLE `users` MODIFY COLUMN `role` ENUM(
                'admin', 
                'doctor', 
                'receptionist', 
                'patient'
            ) DEFAULT 'patient'";
            
            try {
                $this->db->query($sql);
                log_message('info', 'Reverted users.role ENUM to original roles');
            } catch (\Exception $e) {
                log_message('error', 'Error reverting users.role ENUM: ' . $e->getMessage());
            }
        }
    }
}

