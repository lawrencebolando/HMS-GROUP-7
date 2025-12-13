<?php

namespace App\Controllers;

use App\Models\UserModel;

class Nurses extends BaseController
{
    protected $userModel;
    protected $session;

    public function __construct()
    {
        $this->userModel = new UserModel();
        $this->session = session();
    }

    public function index()
    {
        if (!$this->session->get('is_logged_in') || $this->session->get('user_role') !== 'admin') {
            return redirect()->to('login')->with('error', 'Access denied. Admin only.');
        }

        // Get all nurses (users with role = 'nurse')
        $nurses = $this->userModel->where('role', 'nurse')->findAll();
        
        // Add additional info for each nurse
        foreach ($nurses as &$nurse) {
            // Get initials for avatar
            $nameParts = explode(' ', $nurse['name']);
            $nurse['initials'] = '';
            if (count($nameParts) >= 2) {
                $nurse['initials'] = strtoupper(substr($nameParts[0], 0, 1) . substr($nameParts[1], 0, 1));
            } else {
                $nurse['initials'] = strtoupper(substr($nurse['name'], 0, 2));
            }
            
            // Mock data for shifts (to be implemented with actual schedule system)
            $nurse['total_shifts'] = 0;
            $nurse['shift_types'] = 0;
            $nurse['has_schedule'] = false;
            $nurse['activities_count'] = 0;
        }
        
        $data = [
            'title' => 'Nurses',
            'user' => [
                'name' => $this->session->get('user_name'),
                'email' => $this->session->get('user_email'),
                'role' => $this->session->get('user_role')
            ],
            'nurses' => $nurses
        ];
        
        return view('nurses/index', $data);
    }
}

