<?php

namespace App\Controllers;

use App\Models\DoctorTypeModel;

class DoctorTypes extends BaseController
{
    protected $doctorTypeModel;
    protected $session;

    public function __construct()
    {
        $this->doctorTypeModel = new DoctorTypeModel();
        $this->session = session();
    }

    public function index()
    {
        if (!$this->session->get('is_logged_in') || $this->session->get('user_role') !== 'admin') {
            return redirect()->to('login')->with('error', 'Access denied. Admin only.');
        }

        $data = [
            'title' => 'Doctor Types',
            'user' => [
                'name' => $this->session->get('user_name'),
                'role' => $this->session->get('user_role')
            ],
            'doctor_types' => $this->doctorTypeModel->orderBy('type_name', 'ASC')->findAll()
        ];

        return view('doctor_types/index', $data);
    }

    public function create()
    {
        if (!$this->session->get('is_logged_in') || $this->session->get('user_role') !== 'admin') {
            return redirect()->to('login')->with('error', 'Access denied. Admin only.');
        }

        $data = [
            'title' => 'Add New Doctor Type',
            'user' => [
                'name' => $this->session->get('user_name'),
                'role' => $this->session->get('user_role')
            ]
        ];

        return view('doctor_types/create', $data);
    }

    public function store()
    {
        if (!$this->session->get('is_logged_in') || $this->session->get('user_role') !== 'admin') {
            return redirect()->to('login')->with('error', 'Access denied. Admin only.');
        }

        $data = [
            'type_name' => trim($this->request->getPost('type_name')),
            'description' => trim($this->request->getPost('description')) ?: null,
            'status' => 'active'
        ];

        if ($this->doctorTypeModel->insert($data)) {
            return redirect()->to('doctor-types')->with('success', 'Doctor type added successfully!');
        } else {
            return redirect()->back()->withInput()->with('errors', $this->doctorTypeModel->errors());
        }
    }

    public function edit($id)
    {
        if (!$this->session->get('is_logged_in') || $this->session->get('user_role') !== 'admin') {
            return redirect()->to('login')->with('error', 'Access denied. Admin only.');
        }

        $doctorType = $this->doctorTypeModel->find($id);
        if (!$doctorType) {
            return redirect()->to('doctor-types')->with('error', 'Doctor type not found.');
        }

        $data = [
            'title' => 'Edit Doctor Type',
            'user' => [
                'name' => $this->session->get('user_name'),
                'role' => $this->session->get('user_role')
            ],
            'doctor_type' => $doctorType
        ];

        return view('doctor_types/edit', $data);
    }

    public function update($id)
    {
        if (!$this->session->get('is_logged_in') || $this->session->get('user_role') !== 'admin') {
            return redirect()->to('login')->with('error', 'Access denied. Admin only.');
        }

        $data = [
            'type_name' => trim($this->request->getPost('type_name')),
            'description' => trim($this->request->getPost('description')) ?: null,
            'status' => $this->request->getPost('status')
        ];

        if ($this->doctorTypeModel->update($id, $data)) {
            return redirect()->to('doctor-types')->with('success', 'Doctor type updated successfully!');
        } else {
            return redirect()->back()->withInput()->with('errors', $this->doctorTypeModel->errors());
        }
    }

    public function delete($id)
    {
        if (!$this->session->get('is_logged_in') || $this->session->get('user_role') !== 'admin') {
            return redirect()->to('login')->with('error', 'Access denied. Admin only.');
        }

        if ($this->doctorTypeModel->delete($id)) {
            return redirect()->to('doctor-types')->with('success', 'Doctor type deleted successfully!');
        } else {
            return redirect()->to('doctor-types')->with('error', 'Failed to delete doctor type.');
        }
    }
}

