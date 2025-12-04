<?php

namespace App\Controllers;

use App\Models\UserModel;

class UpdateAdmin extends BaseController
{
    public function index()
    {
        $userModel = new UserModel();
        
        // Update admin account name
        $admin = $userModel->where('email', 'admin@globalhospitals.com')->first();
        
        if ($admin) {
            $userModel->update($admin['id'], ['name' => 'St. Elizabeth Hospital']);
            echo "Admin name updated to St. Elizabeth Hospital successfully!<br>";
            echo "Please refresh your dashboard page.<br>";
            echo "<a href='" . base_url('dashboard') . "'>Go to Dashboard</a>";
        } else {
            echo "Admin account not found.";
        }
    }
}

