<?php

namespace App\Controllers;

<<<<<<< HEAD
use App\Models\UserModel;

class Doctors extends BaseController
{
    protected $userModel;
=======
use App\Models\DoctorModel;
use App\Models\DepartmentModel;
use App\Models\DoctorTypeModel;
use App\Models\AppointmentModel;

class Doctors extends BaseController
{
    protected $doctorModel;
    protected $deptModel;
    protected $doctorTypeModel;
    protected $appointmentModel;
>>>>>>> 3bfa254a216ebb6a1c45607fb87bcfe8a1c479b4
    protected $session;

    public function __construct()
    {
<<<<<<< HEAD
        $this->userModel = new UserModel();
=======
        $this->doctorModel = new DoctorModel();
        $this->deptModel = new DepartmentModel();
        $this->doctorTypeModel = new DoctorTypeModel();
        $this->appointmentModel = new AppointmentModel();
>>>>>>> 3bfa254a216ebb6a1c45607fb87bcfe8a1c479b4
        $this->session = session();
    }

    public function index()
    {
        if (!$this->session->get('is_logged_in') || $this->session->get('user_role') !== 'admin') {
            return redirect()->to('login')->with('error', 'Access denied. Admin only.');
        }

<<<<<<< HEAD
        $data = [
            'title' => 'Consultation Doctors',
=======
        // Get search query
        $search = $this->request->getGet('search');
        
        // Get all doctors with department info
        $doctors = $this->doctorModel->findAll();
        
        // Filter by search if provided
        if ($search) {
            $doctors = array_filter($doctors, function($doctor) use ($search) {
                $searchLower = strtolower($search);
                return strpos(strtolower($doctor['full_name']), $searchLower) !== false ||
                       strpos(strtolower($doctor['specialization']), $searchLower) !== false;
            });
        }
        
        // Add department and patient count info
        foreach ($doctors as &$doctor) {
            if ($doctor['department_id']) {
                $dept = $this->deptModel->find($doctor['department_id']);
                $doctor['department_name'] = $dept ? $dept['name'] : 'N/A';
            } else {
                $doctor['department_name'] = 'N/A';
            }
            
            // Count patients today for this doctor
            $today = date('Y-m-d');
            $doctor['patients_today'] = $this->appointmentModel
                ->where('doctor_id', $doctor['id'])
                ->where('appointment_date', $today)
                ->countAllResults();
        }
        
        // Get stats
        $stats = [
            'active_doctors' => $this->doctorModel->where('status', 'active')->countAllResults(),
            'on_leave' => $this->doctorModel->where('status', 'on_leave')->countAllResults(),
            'patients_today' => $this->appointmentModel->where('appointment_date', date('Y-m-d'))->countAllResults(),
            'departments' => $this->deptModel->countAllResults()
        ];

        $data = [
            'title' => 'Doctor Management',
>>>>>>> 3bfa254a216ebb6a1c45607fb87bcfe8a1c479b4
            'user' => [
                'name' => $this->session->get('user_name'),
                'role' => $this->session->get('user_role')
            ],
<<<<<<< HEAD
            'doctors' => $this->userModel->where('role', 'doctor')->findAll()
=======
            'doctors' => array_values($doctors),
            'stats' => $stats,
            'search' => $search,
            'departments' => $this->deptModel->where('status', 'active')->findAll(),
            'doctor_types' => $this->doctorTypeModel->where('status', 'active')->findAll()
>>>>>>> 3bfa254a216ebb6a1c45607fb87bcfe8a1c479b4
        ];

        return view('doctors/index', $data);
    }

<<<<<<< HEAD
    public function create()
    {
        if (!$this->session->get('is_logged_in') || $this->session->get('user_role') !== 'admin') {
            return redirect()->to('login')->with('error', 'Access denied. Admin only.');
        }

        $data = [
            'title' => 'Add New Doctor',
            'user' => [
                'name' => $this->session->get('user_name'),
                'role' => $this->session->get('user_role')
            ]
        ];

        return view('doctors/create', $data);
    }

=======
>>>>>>> 3bfa254a216ebb6a1c45607fb87bcfe8a1c479b4
    public function store()
    {
        if (!$this->session->get('is_logged_in') || $this->session->get('user_role') !== 'admin') {
            return redirect()->to('login')->with('error', 'Access denied. Admin only.');
        }

        $data = [
<<<<<<< HEAD
            'name' => $this->request->getPost('name'),
            'email' => $this->request->getPost('email'),
            'password' => $this->request->getPost('password'),
            'role' => 'doctor',
            'status' => 'active'
        ];

        if ($this->userModel->insert($data)) {
            return redirect()->to('doctors')->with('success', 'Doctor added successfully!');
        } else {
            return redirect()->back()->withInput()->with('errors', $this->userModel->errors());
=======
            'full_name' => trim($this->request->getPost('full_name')),
            'specialization' => trim($this->request->getPost('specialization')),
            'department_id' => $this->request->getPost('department_id') ?: null,
            'phone' => trim($this->request->getPost('phone')) ?: null,
            'email' => trim($this->request->getPost('email')) ?: null,
            'years_of_experience' => $this->request->getPost('years_of_experience') ?: null,
            'schedule' => trim($this->request->getPost('schedule')) ?: null,
            'rating' => 0.0,
            'status' => 'active'
        ];

        if ($this->doctorModel->skipValidation(true)->insert($data)) {
            return redirect()->to('doctors')->with('success', 'Doctor added successfully!');
        } else {
            return redirect()->back()->withInput()->with('errors', $this->doctorModel->errors());
>>>>>>> 3bfa254a216ebb6a1c45607fb87bcfe8a1c479b4
        }
    }

    public function edit($id)
    {
        if (!$this->session->get('is_logged_in') || $this->session->get('user_role') !== 'admin') {
            return redirect()->to('login')->with('error', 'Access denied. Admin only.');
        }

<<<<<<< HEAD
        $doctor = $this->userModel->find($id);
        if (!$doctor || $doctor['role'] !== 'doctor') {
=======
        $doctor = $this->doctorModel->find($id);
        if (!$doctor) {
>>>>>>> 3bfa254a216ebb6a1c45607fb87bcfe8a1c479b4
            return redirect()->to('doctors')->with('error', 'Doctor not found.');
        }

        $data = [
            'title' => 'Edit Doctor',
            'user' => [
                'name' => $this->session->get('user_name'),
                'role' => $this->session->get('user_role')
            ],
<<<<<<< HEAD
            'doctor' => $doctor
=======
            'doctor' => $doctor,
            'departments' => $this->deptModel->where('status', 'active')->findAll(),
            'doctor_types' => $this->doctorTypeModel->where('status', 'active')->findAll()
>>>>>>> 3bfa254a216ebb6a1c45607fb87bcfe8a1c479b4
        ];

        return view('doctors/edit', $data);
    }

    public function update($id)
    {
        if (!$this->session->get('is_logged_in') || $this->session->get('user_role') !== 'admin') {
            return redirect()->to('login')->with('error', 'Access denied. Admin only.');
        }

        $data = [
<<<<<<< HEAD
            'name' => $this->request->getPost('name'),
            'email' => $this->request->getPost('email'),
            'status' => $this->request->getPost('status')
        ];

        // Only update password if provided
        if ($this->request->getPost('password')) {
            $data['password'] = $this->request->getPost('password');
        }

        if ($this->userModel->update($id, $data)) {
            return redirect()->to('doctors')->with('success', 'Doctor updated successfully!');
        } else {
            return redirect()->back()->withInput()->with('errors', $this->userModel->errors());
=======
            'full_name' => trim($this->request->getPost('full_name')),
            'specialization' => trim($this->request->getPost('specialization')),
            'department_id' => $this->request->getPost('department_id') ?: null,
            'phone' => trim($this->request->getPost('phone')) ?: null,
            'email' => trim($this->request->getPost('email')) ?: null,
            'years_of_experience' => $this->request->getPost('years_of_experience') ?: null,
            'schedule' => trim($this->request->getPost('schedule')) ?: null,
            'status' => $this->request->getPost('status')
        ];

        if ($this->doctorModel->skipValidation(true)->update($id, $data)) {
            return redirect()->to('doctors')->with('success', 'Doctor updated successfully!');
        } else {
            return redirect()->back()->withInput()->with('errors', $this->doctorModel->errors());
>>>>>>> 3bfa254a216ebb6a1c45607fb87bcfe8a1c479b4
        }
    }

    public function delete($id)
    {
        if (!$this->session->get('is_logged_in') || $this->session->get('user_role') !== 'admin') {
            return redirect()->to('login')->with('error', 'Access denied. Admin only.');
        }

<<<<<<< HEAD
        if ($this->userModel->delete($id)) {
=======
        if ($this->doctorModel->delete($id)) {
>>>>>>> 3bfa254a216ebb6a1c45607fb87bcfe8a1c479b4
            return redirect()->to('doctors')->with('success', 'Doctor deleted successfully!');
        } else {
            return redirect()->to('doctors')->with('error', 'Failed to delete doctor.');
        }
    }
}

