<?php

namespace App\Controllers;

use App\Models\AppointmentModel;
use App\Models\PatientModel;
use App\Models\UserModel;
use App\Models\DepartmentModel;

class Appointments extends BaseController
{
    protected $appointmentModel;
    protected $patientModel;
    protected $userModel;
    protected $deptModel;
    protected $session;

    public function __construct()
    {
        $this->appointmentModel = new AppointmentModel();
        $this->patientModel = new PatientModel();
        $this->userModel = new UserModel();
        $this->deptModel = new DepartmentModel();
        $this->session = session();
    }

    public function index()
    {
        if (!$this->session->get('is_logged_in') || $this->session->get('user_role') !== 'admin') {
            return redirect()->to('login')->with('error', 'Access denied. Admin only.');
        }

        // Get filter date or use today
        $filterDate = $this->request->getGet('date') ?: date('Y-m-d');
        
        // Get appointments for the selected date
        $appointments = $this->appointmentModel
            ->where('appointment_date', $filterDate)
            ->orderBy('appointment_time', 'ASC')
            ->findAll();

        // Get related data for appointments
        foreach ($appointments as &$apt) {
            $patient = $this->patientModel->find($apt['patient_id']);
            $doctor = $this->userModel->find($apt['doctor_id']);
            $apt['patient_name'] = $patient ? ($patient['first_name'] . ' ' . $patient['last_name']) : 'Unknown';
            $apt['patient_id_display'] = $patient ? $patient['patient_id'] : 'N/A';
            $apt['doctor_name'] = $doctor ? $doctor['name'] : 'Unknown';
        }

        $data = [
            'title' => 'Appointment Scheduling',
            'user' => [
                'name' => $this->session->get('user_name'),
                'role' => $this->session->get('user_role')
            ],
            'appointments' => $appointments,
            'filter_date' => $filterDate,
            'patients' => $this->patientModel->findAll(),
            'doctors' => $this->userModel->where('role', 'doctor')->findAll(),
            'departments' => $this->deptModel->where('status', 'active')->findAll()
        ];

        return view('appointments/index', $data);
    }

    public function store()
    {
        if (!$this->session->get('is_logged_in') || $this->session->get('user_role') !== 'admin') {
            return redirect()->to('login')->with('error', 'Access denied. Admin only.');
        }

        // Validate required fields
        $validation = \Config\Services::validation();
        $validation->setRules([
            'patient_id' => 'required|integer',
            'doctor_id' => 'required|integer',
            'appointment_date' => 'required|valid_date',
            'appointment_time' => 'required',
        ]);

        if (!$validation->run($this->request->getPost())) {
            return redirect()->back()->withInput()->with('errors', $validation->getErrors());
        }

        $data = [
            'patient_id' => $this->request->getPost('patient_id'),
            'doctor_id' => $this->request->getPost('doctor_id'),
            'department_id' => $this->request->getPost('department_id') ?: null,
            'appointment_date' => $this->request->getPost('appointment_date'),
            'appointment_time' => $this->request->getPost('appointment_time'),
            'reason' => trim($this->request->getPost('reason')) ?: null,
            'notes' => trim($this->request->getPost('notes')) ?: null,
            'status' => 'scheduled'
        ];

        try {
            if ($this->appointmentModel->skipValidation(true)->insert($data)) {
                return redirect()->to('appointments')->with('success', 'Appointment scheduled successfully!');
            } else {
                $dbError = $this->appointmentModel->db->error();
                $errors = ['database' => 'Failed to save appointment: ' . ($dbError['message'] ?? 'Unknown error')];
                return redirect()->back()->withInput()->with('errors', $errors);
            }
        } catch (\Exception $e) {
            return redirect()->back()->withInput()->with('errors', ['database' => 'Error: ' . $e->getMessage()]);
        }
    }

    public function update($id)
    {
        if (!$this->session->get('is_logged_in') || $this->session->get('user_role') !== 'admin') {
            return redirect()->to('login')->with('error', 'Access denied. Admin only.');
        }

        $data = [
            'patient_id' => $this->request->getPost('patient_id'),
            'doctor_id' => $this->request->getPost('doctor_id'),
            'department_id' => $this->request->getPost('department_id') ?: null,
            'appointment_date' => $this->request->getPost('appointment_date'),
            'appointment_time' => $this->request->getPost('appointment_time'),
            'reason' => trim($this->request->getPost('reason')) ?: null,
            'notes' => trim($this->request->getPost('notes')) ?: null,
            'status' => $this->request->getPost('status') ?: 'scheduled'
        ];

        if ($this->appointmentModel->skipValidation(true)->update($id, $data)) {
            return redirect()->to('appointments')->with('success', 'Appointment updated successfully!');
        } else {
            return redirect()->back()->withInput()->with('errors', $this->appointmentModel->errors());
        }
    }

    public function delete($id)
    {
        if (!$this->session->get('is_logged_in') || $this->session->get('user_role') !== 'admin') {
            return redirect()->to('login')->with('error', 'Access denied. Admin only.');
        }

        if ($this->appointmentModel->delete($id)) {
            return redirect()->to('appointments')->with('success', 'Appointment deleted successfully!');
        } else {
            return redirect()->to('appointments')->with('error', 'Failed to delete appointment.');
        }
    }
}

