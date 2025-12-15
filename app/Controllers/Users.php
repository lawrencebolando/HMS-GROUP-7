<?php

namespace App\Controllers;

use App\Models\UserModel;

class Users extends BaseController
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

        // Get all users
        $users = $this->userModel->orderBy('created_at', 'DESC')->findAll();
        
        // Add additional info for each user
        foreach ($users as &$user) {
            // Get initials for avatar
            $nameParts = explode(' ', $user['name']);
            $user['initials'] = '';
            if (count($nameParts) >= 2) {
                $user['initials'] = strtoupper(substr($nameParts[0], 0, 1) . substr($nameParts[1], 0, 1));
            } else {
                $user['initials'] = strtoupper(substr($user['name'], 0, 2));
            }
        }
        
        $data = [
            'title' => 'Users Management',
            'user' => [
                'name' => $this->session->get('user_name'),
                'email' => $this->session->get('user_email'),
                'role' => $this->session->get('user_role')
            ],
            'users' => $users
        ];
        
        return view('users/index', $data);
    }

    public function create()
    {
        if (!$this->session->get('is_logged_in') || $this->session->get('user_role') !== 'admin') {
            return redirect()->to('login')->with('error', 'Access denied. Admin only.');
        }

        $data = [
            'title' => 'Create User',
            'user' => [
                'name' => $this->session->get('user_name'),
                'email' => $this->session->get('user_email'),
                'role' => $this->session->get('user_role')
            ]
        ];
        
        return view('users/create', $data);
    }

    public function store()
    {
        if (!$this->session->get('is_logged_in') || $this->session->get('user_role') !== 'admin') {
            return redirect()->to('login')->with('error', 'Access denied. Admin only.');
        }

        $data = [
            'name' => $this->request->getPost('name'),
            'email' => $this->request->getPost('email'),
            'password' => $this->request->getPost('password'),
            'role' => $this->request->getPost('role'),
            'status' => $this->request->getPost('status') ?: 'active'
        ];

        if ($this->userModel->insert($data)) {
            return redirect()->to('users')->with('success', 'User created successfully!');
        } else {
            return redirect()->back()->withInput()->with('errors', $this->userModel->errors());
        }
    }

    public function edit($id)
    {
        if (!$this->session->get('is_logged_in') || $this->session->get('user_role') !== 'admin') {
            return redirect()->to('login')->with('error', 'Access denied. Admin only.');
        }

        $user = $this->userModel->find($id);
        
        if (!$user) {
            return redirect()->to('users')->with('error', 'User not found.');
        }

        $data = [
            'title' => 'Edit User',
            'user' => [
                'name' => $this->session->get('user_name'),
                'email' => $this->session->get('user_email'),
                'role' => $this->session->get('user_role')
            ],
            'editUser' => $user
        ];
        
        return view('users/edit', $data);
    }

    public function update($id)
    {
        if (!$this->session->get('is_logged_in') || $this->session->get('user_role') !== 'admin') {
            return redirect()->to('login')->with('error', 'Access denied. Admin only.');
        }

        $data = [
            'name' => $this->request->getPost('name'),
            'email' => $this->request->getPost('email'),
            'role' => $this->request->getPost('role'),
            'status' => $this->request->getPost('status') ?: 'active'
        ];

        // Only update password if provided
        if ($this->request->getPost('password')) {
            $data['password'] = $this->request->getPost('password');
        }

        if ($this->userModel->update($id, $data)) {
            return redirect()->to('users')->with('success', 'User updated successfully!');
        } else {
            return redirect()->back()->withInput()->with('errors', $this->userModel->errors());
        }
    }

    public function delete($id)
    {
        if (!$this->session->get('is_logged_in') || $this->session->get('user_role') !== 'admin') {
            return redirect()->to('login')->with('error', 'Access denied. Admin only.');
        }

        if ($this->userModel->delete($id)) {
            return redirect()->to('users')->with('success', 'User deleted successfully!');
        } else {
            return redirect()->to('users')->with('error', 'Failed to delete user.');
        }
    }
}

