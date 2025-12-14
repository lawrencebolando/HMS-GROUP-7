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
        $remember = $this->request->getPost('remember_me') ? true : false;
        
        if (empty($email) || empty($password)) {
            return redirect()->back()->with('error', 'Email and password are required');
        }
        
        // Normalize email (trim and lowercase)
        $email = strtolower(trim($email));
        
        $user = $this->userModel->verifyPassword($email, $password);
        
        if (!$user) {
            // Debug: Check if user exists at all
            $userExists = $this->userModel->where('email', $email)->first();
            if ($userExists) {
                // Test password manually
                $passwordMatch = password_verify($password, $userExists['password']);
                log_message('error', "Login failed for {$email}: User exists but password doesn't match. Password verify: " . ($passwordMatch ? 'true' : 'false'));
            } else {
                log_message('error', "Login failed for {$email}: User not found");
            }
            return redirect()->back()->with('error', 'Invalid email or password');
        }
        

        if ($user['status'] !== 'active') {
            return redirect()->back()->with('error', 'Your account is inactive');
        }
        
        $sessionData = [
            'user_id' => $user['id'],
            'user_name' => $user['name'],
            'user_email' => $user['email'],
            'user_role' => $user['role'],
            'is_logged_in' => true
        ];
        
        $this->session->set($sessionData);
        
        if ($user['role'] === 'admin') {
            return redirect()->to('dashboard')->with('success', 'Welcome back, ' . $user['name']);
        } elseif ($user['role'] === 'doctor') {
            return redirect()->to('doctor/dashboard')->with('success', 'Welcome back, ' . $user['name']);
        } elseif ($user['role'] === 'receptionist') {
            return redirect()->to('reception/dashboard')->with('success', 'Welcome back, ' . $user['name']);
        } elseif ($user['role'] === 'nurse') {
            return redirect()->to('nurse/dashboard')->with('success', 'Welcome back, ' . $user['name']);
        } elseif (in_array($user['role'], ['lab_technician', 'lab_staff', 'lab'])) {
            return redirect()->to('lab/dashboard')->with('success', 'Welcome back, ' . $user['name']);
        } elseif (in_array($user['role'], ['accountant', 'accounts'])) {
            return redirect()->to('accounts/dashboard')->with('success', 'Welcome back, ' . $user['name']);
        } elseif (in_array($user['role'], ['it', 'it_staff', 'it_admin'])) {
            return redirect()->to('it/dashboard')->with('success', 'Welcome back, ' . $user['name']);
        } else {
            return redirect()->to('patient/dashboard')->with('success', 'Welcome back, ' . $user['name']);
        }
    }
    
    public function logout()
    {
        // Clear session data
        $this->session->remove(['user_id', 'user_name', 'user_email', 'user_role', 'is_logged_in']);
        
        // Destroy session
        $this->session->destroy();
        
        // Redirect to login
        return redirect()->to('login')->with('success', 'You have been logged out successfully.');
    }
}