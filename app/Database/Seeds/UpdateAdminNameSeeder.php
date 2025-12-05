<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;
use App\Models\UserModel;

class UpdateAdminNameSeeder extends Seeder
{
    public function run()
    {
        $userModel = new UserModel();
        
        // Update existing admin account name
        $admin = $userModel->where('email', 'admin@globalhospitals.com')->first();
        
        if ($admin) {
            $userModel->update($admin['id'], ['name' => 'St. Elizabeth Hospital']);
            echo "Admin name updated to St. Elizabeth Hospital!\n";
        } else {
            echo "Admin account not found. Run AdminSeeder first.\n";
        }
    }
}

