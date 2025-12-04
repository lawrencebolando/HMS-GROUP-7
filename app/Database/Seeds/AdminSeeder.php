<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;
use App\Models\UserModel;

class AdminSeeder extends Seeder
{
    public function run()
    {
        $userModel = new UserModel();
        
        // Check if admin already exists
        $existingAdmin = $userModel->where('email', 'admin@globalhospitals.com')->first();
        
        if (!$existingAdmin) {
            $data = [
                'name'     => 'St. Elizabeth Hospital',
                'email'    => 'admin@globalhospitals.com',
                'password' => 'admin123', // Will be hashed by model
                'role'     => 'admin',
                'status'   => 'active',
            ];
            
            $userModel->insert($data);
            echo "Admin account created successfully!\n";
            echo "Email: admin@globalhospitals.com\n";
            echo "Password: admin123\n";
        } else {
            echo "Admin account already exists!\n";
        }
    }
}

