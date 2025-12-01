<?php

namespace App\Controllers;

use App\Models\DepartmentModel;

class Departments extends BaseController
{
    protected $deptModel;
    protected $session;

    public function __construct()
    {
        $this->deptModel = new DepartmentModel();
        $this->session = session();
    }

    public function index()
    {
        if (!$this->session->get('is_logged_in') || $this->session->get('user_role') !== 'admin') {
            return redirect()->to('login')->with('error', 'Access denied. Admin only.');
        }

        $data = [
            'title' => 'Departments',
            'user' => [
                'name' => $this->session->get('user_name'),
                'role' => $this->session->get('user_role')
            ],
            'departments' => $this->deptModel->orderBy('name', 'ASC')->findAll()
        ];

        return view('departments/index', $data);
    }

    public function store()
    {
        if (!$this->session->get('is_logged_in') || $this->session->get('user_role') !== 'admin') {
            return redirect()->to('login')->with('error', 'Access denied. Admin only.');
        }

        $data = [
            'name' => $this->request->getPost('name'),
            'description' => $this->request->getPost('description'),
            'status' => $this->request->getPost('status') ?? 'active'
        ];

        if ($this->deptModel->insert($data)) {
            return redirect()->to('departments')->with('success', 'Department added successfully!');
        } else {
            return redirect()->back()->withInput()->with('errors', $this->deptModel->errors());
        }
    }

    public function update($id)
    {
        if (!$this->session->get('is_logged_in') || $this->session->get('user_role') !== 'admin') {
            return redirect()->to('login')->with('error', 'Access denied. Admin only.');
        }

        $data = [
            'name' => $this->request->getPost('name'),
            'description' => $this->request->getPost('description'),
            'status' => $this->request->getPost('status')
        ];

        if ($this->deptModel->update($id, $data)) {
            return redirect()->to('departments')->with('success', 'Department updated successfully!');
        } else {
            return redirect()->back()->withInput()->with('errors', $this->deptModel->errors());
        }
    }

    public function delete($id)
    {
        if (!$this->session->get('is_logged_in') || $this->session->get('user_role') !== 'admin') {
            return redirect()->to('login')->with('error', 'Access denied. Admin only.');
        }

        if ($this->deptModel->delete($id)) {
            return redirect()->to('departments')->with('success', 'Department deleted successfully!');
        } else {
            return redirect()->to('departments')->with('error', 'Failed to delete department.');
        }
    }
}

