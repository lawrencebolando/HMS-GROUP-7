<?php

namespace App\Controllers;

use App\Models\PatientModel;

class Patients extends BaseController
{
    protected $patientModel;
    protected $session;

    public function __construct()
    {
        $this->patientModel = new PatientModel();
        $this->session = session();
    }

    public function index()
    {
        if (!$this->session->get('is_logged_in') || $this->session->get('user_role') !== 'admin') {
            return redirect()->to('login')->with('error', 'Access denied. Admin only.');
        }

        $data = [
            'title' => 'Patient Management',
            'user' => [
                'name' => $this->session->get('user_name'),
                'role' => $this->session->get('user_role')
            ],
            'patients' => $this->patientModel->orderBy('created_at', 'DESC')->findAll()
        ];

        return view('patients/index', $data);
    }

    public function create()
    {
        if (!$this->session->get('is_logged_in') || $this->session->get('user_role') !== 'admin') {
            return redirect()->to('login')->with('error', 'Access denied. Admin only.');
        }

        $data = [
            'title' => 'Add New Patient',
            'user' => [
                'name' => $this->session->get('user_name'),
                'role' => $this->session->get('user_role')
            ]
        ];

        return view('patients/create', $data);
    }

    public function store()
    {
        if (!$this->session->get('is_logged_in') || $this->session->get('user_role') !== 'admin') {
            return redirect()->to('login')->with('error', 'Access denied. Admin only.');
        }

        // Validate required fields
        $validation = \Config\Services::validation();
        $validation->setRules([
            'first_name' => 'required|min_length[2]|max_length[100]',
            'last_name' => 'required|min_length[2]|max_length[100]',
            'email' => 'permit_empty|valid_email',
        ]);

        if (!$validation->run($this->request->getPost())) {
            return redirect()->back()->withInput()->with('errors', $validation->getErrors());
        }

        $data = [
            'first_name' => trim($this->request->getPost('first_name')),
            'last_name' => trim($this->request->getPost('last_name')),
            'email' => trim($this->request->getPost('email')) ?: null,
            'phone' => trim($this->request->getPost('phone')) ?: null,
            'date_of_birth' => $this->request->getPost('date_of_birth') ?: null,
            'gender' => $this->request->getPost('gender') ?: null,
            'address' => trim($this->request->getPost('address')) ?: null,
            'blood_group' => $this->request->getPost('blood_group') ?: null,
            'status' => 'active'
        ];

        // Insert with validation disabled (we validated manually above)
        try {
            if ($this->patientModel->skipValidation(true)->insert($data)) {
                return redirect()->to('patients')->with('success', 'Patient added successfully!');
            } else {
                $dbError = $this->patientModel->db->error();
                $errors = ['database' => 'Failed to save patient: ' . ($dbError['message'] ?? 'Unknown error')];
                return redirect()->back()->withInput()->with('errors', $errors);
            }
        } catch (\Exception $e) {
            return redirect()->back()->withInput()->with('errors', ['database' => 'Error: ' . $e->getMessage()]);
        }
    }

    public function edit($id)
    {
        if (!$this->session->get('is_logged_in') || $this->session->get('user_role') !== 'admin') {
            return redirect()->to('login')->with('error', 'Access denied. Admin only.');
        }

        $patient = $this->patientModel->find($id);
        if (!$patient) {
            return redirect()->to('patients')->with('error', 'Patient not found.');
        }

        $data = [
            'title' => 'Edit Patient',
            'user' => [
                'name' => $this->session->get('user_name'),
                'role' => $this->session->get('user_role')
            ],
            'patient' => $patient
        ];

        return view('patients/edit', $data);
    }

    public function update($id)
    {
        if (!$this->session->get('is_logged_in') || $this->session->get('user_role') !== 'admin') {
            return redirect()->to('login')->with('error', 'Access denied. Admin only.');
        }

        $data = [
            'first_name' => $this->request->getPost('first_name'),
            'last_name' => $this->request->getPost('last_name'),
            'email' => $this->request->getPost('email'),
            'phone' => $this->request->getPost('phone'),
            'date_of_birth' => $this->request->getPost('date_of_birth'),
            'gender' => $this->request->getPost('gender'),
            'address' => $this->request->getPost('address'),
            'blood_group' => $this->request->getPost('blood_group'),
            'status' => $this->request->getPost('status')
        ];

        if ($this->patientModel->update($id, $data)) {
            return redirect()->to('patients')->with('success', 'Patient updated successfully!');
        } else {
            return redirect()->back()->withInput()->with('errors', $this->patientModel->errors());
        }
    }

    public function delete($id)
    {
        if (!$this->session->get('is_logged_in') || $this->session->get('user_role') !== 'admin') {
            return redirect()->to('login')->with('error', 'Access denied. Admin only.');
        }

        if ($this->patientModel->delete($id)) {
            return redirect()->to('patients')->with('success', 'Patient deleted successfully!');
        } else {
            return redirect()->to('patients')->with('error', 'Failed to delete patient.');
        }
    }
}

