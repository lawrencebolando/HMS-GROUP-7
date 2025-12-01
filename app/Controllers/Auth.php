<?php

namespace App\Controllers;

use App\Models\UserModel;
use CodeIgniter\Session\Session;

class Auth extends BaseController
{
    protected $userModel;
    protected $session;

    public function __construct()
    {
        $this->userModel = new UserModel();
        $this->session = session();
    }

    public function login()
    {
        // If already logged in, redirect to dashboard
        if ($this->session->get('user_id')) {
            return redirect()->to('dashboard');
        }
        
        return view('login');
    }

    public function authenticate()
    {
        $email = $this->request->getPost('email');
        $password = $this->request->getPost('password');
        $role = $this->request->getPost('role') ?? 'patient';
        $remember = $this->request->getPost('remember_me') ? true : false;
        
        // Validate input
        if (empty($email) || empty($password)) {
            return redirect()->back()->with('error', 'Email and password are required');
        }
        
        // Verify user credentials
        $user = $this->userModel->verifyPassword($email, $password);
        
        if (!$user) {
            return redirect()->back()->with('error', 'Invalid email or password');
        }
        
        // Check if user role matches selected role (for admin, allow any role)
        // Admin can login with any role selection, but other users must match
        if ($user['role'] !== 'admin' && $user['role'] !== $role) {
            return redirect()->back()->with('error', 'Invalid role selection');
        }
        
        // Check if user is active
        if ($user['status'] !== 'active') {
            return redirect()->back()->with('error', 'Your account is inactive');
        }
        
        // Set session data
        $sessionData = [
            'user_id' => $user['id'],
            'user_name' => $user['name'],
            'user_email' => $user['email'],
            'user_role' => $user['role'],
            'is_logged_in' => true
        ];
        
        $this->session->set($sessionData);
        
        // Debug: Verify session was set
        // Uncomment for debugging: var_dump($this->session->get()); exit;
        
        // Redirect based on role - use same URL format as homepage (no port 8080)
        if ($user['role'] === 'admin') {
            return redirect()->to('dashboard')->with('success', 'Welcome back, ' . $user['name']);
        } elseif ($user['role'] === 'doctor') {
            return redirect()->to('doctor/dashboard');
        } elseif ($user['role'] === 'receptionist') {
            return redirect()->to('receptionist/dashboard');
        } else {
            return redirect()->to('patient/dashboard');
        }
    }
    
    public function logout()
    {
        $this->session->destroy();
        return redirect()->to('login');
    }
}