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
<<<<<<< HEAD
=======
        $role = $this->request->getPost('role') ?? 'patient';
>>>>>>> 3bfa254a216ebb6a1c45607fb87bcfe8a1c479b4
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
        
<<<<<<< HEAD
=======
        // Check if user role matches selected role (for admin, allow any role)
        // Admin can login with any role selection, but other users must match
        if ($user['role'] !== 'admin' && $user['role'] !== $role) {
            return redirect()->back()->with('error', 'Invalid role selection');
        }
        
>>>>>>> 3bfa254a216ebb6a1c45607fb87bcfe8a1c479b4
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
        
<<<<<<< HEAD
        // Redirect based on user's actual role from database
        if ($user['role'] === 'admin') {
            return redirect()->to('dashboard')->with('success', 'Welcome back, ' . $user['name']);
        } elseif ($user['role'] === 'doctor') {
            return redirect()->to('doctor/dashboard')->with('success', 'Welcome back, ' . $user['name']);
        } elseif ($user['role'] === 'receptionist') {
            return redirect()->to('reception/dashboard')->with('success', 'Welcome back, ' . $user['name']);
        } else {
            return redirect()->to('patient/dashboard')->with('success', 'Welcome back, ' . $user['name']);
=======
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
>>>>>>> 3bfa254a216ebb6a1c45607fb87bcfe8a1c479b4
        }
    }
    
    public function logout()
    {
<<<<<<< HEAD
        // Clear session data
        $this->session->remove(['user_id', 'user_name', 'user_email', 'user_role', 'is_logged_in']);
        
        // Destroy session
        $this->session->destroy();
        
        // Redirect to login
        return redirect()->to('login')->with('success', 'You have been logged out successfully.');
=======
        $this->session->destroy();
        return redirect()->to('login');
>>>>>>> 3bfa254a216ebb6a1c45607fb87bcfe8a1c479b4
    }
}