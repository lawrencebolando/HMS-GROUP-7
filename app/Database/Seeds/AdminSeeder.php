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
<<<<<<< HEAD
                'name'     => 'St. Elizabeth Hospital, Inc.',
=======
                'name'     => 'St. Elizabeth Hospital',
>>>>>>> 3bfa254a216ebb6a1c45607fb87bcfe8a1c479b4
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

