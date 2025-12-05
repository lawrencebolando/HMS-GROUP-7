<?php

namespace App\Controllers;

use App\Models\UserModel;
use App\Models\PatientModel;
use App\Models\AppointmentModel;

class Reception extends BaseController
{
    protected $userModel;
    protected $patientModel;
    protected $appointmentModel;
    protected $session;

    public function __construct()
    {
        $this->userModel = new UserModel();
        $this->patientModel = new PatientModel();
        $this->appointmentModel = new AppointmentModel();
        $this->session = session();
    }

    public function index()
    {
        return $this->dashboard();
    }

    public function dashboard()
    {
        // Check if user is logged in and is a receptionist
        if (!$this->session->get('is_logged_in') || $this->session->get('user_role') !== 'receptionist') {
            return redirect()->to('login')->with('error', 'Please login as receptionist to continue');
        }

        // Get patient statistics
        $totalPatients = $this->patientModel->countAllResults();
        $today = date('Y-m-d');
        $newPatientsToday = $this->patientModel
            ->where('DATE(created_at)', $today)
            ->countAllResults();

        // Get all patients
        $patients = $this->patientModel->orderBy('created_at', 'DESC')->findAll();

        // Get current user data from database to ensure name is up-to-date
        $userId = $this->session->get('user_id');
        $currentUser = $this->userModel->find($userId);

        $data = [
            'title' => 'Dashboard',
            'user' => [
                'name' => $currentUser ? $currentUser['name'] : $this->session->get('user_name'),
                'email' => $currentUser ? $currentUser['email'] : $this->session->get('user_email'),
                'role' => $currentUser ? $currentUser['role'] : $this->session->get('user_role')
            ],
            'stats' => [
                'total_patients' => $totalPatients,
                'new_patients_today' => $newPatientsToday
            ],
            'patients' => $patients
        ];

        return view('reception/dashboard', $data);
    }

    public function patients()
    {
        if (!$this->session->get('is_logged_in') || $this->session->get('user_role') !== 'receptionist') {
            return redirect()->to('login')->with('error', 'Please login as receptionist to continue');
        }

        $patients = $this->patientModel->orderBy('created_at', 'DESC')->findAll();

        $data = [
            'title' => 'Patient Registration',
            'user' => [
                'name' => $this->session->get('user_name'),
                'email' => $this->session->get('user_email'),
                'role' => $this->session->get('user_role')
            ],
            'patients' => $patients
        ];

        return view('reception/patients', $data);
    }

    public function appointments()
    {
        if (!$this->session->get('is_logged_in') || $this->session->get('user_role') !== 'receptionist') {
            return redirect()->to('login')->with('error', 'Please login as receptionist to continue');
        }

        $appointments = $this->appointmentModel
            ->orderBy('appointment_date', 'DESC')
            ->orderBy('appointment_time', 'DESC')
            ->findAll();
        
        // Get patient and doctor details for appointments
        $appointmentsWithDetails = [];
        foreach ($appointments as $appt) {
            $patient = $this->patientModel->find($appt['patient_id']);
            $doctor = $this->userModel->find($appt['doctor_id']);
            $appt['patient'] = $patient;
            $appt['doctor'] = $doctor;
            $appointmentsWithDetails[] = $appt;
        }

        $data = [
            'title' => 'Appointments',
            'user' => [
                'name' => $this->session->get('user_name'),
                'email' => $this->session->get('user_email'),
                'role' => $this->session->get('user_role')
            ],
            'appointments' => $appointmentsWithDetails,
            'patientModel' => $this->patientModel,
            'userModel' => $this->userModel
        ];

        return view('reception/appointments', $data);
    }

    public function reports()
    {
        if (!$this->session->get('is_logged_in') || $this->session->get('user_role') !== 'receptionist') {
            return redirect()->to('login')->with('error', 'Please login as receptionist to continue');
        }

        $data = [
            'title' => 'Reports',
            'user' => [
                'name' => $this->session->get('user_name'),
                'email' => $this->session->get('user_email'),
                'role' => $this->session->get('user_role')
            ]
        ];

        return view('reception/reports', $data);
    }

    public function settings()
    {
        if (!$this->session->get('is_logged_in') || $this->session->get('user_role') !== 'receptionist') {
            return redirect()->to('login')->with('error', 'Please login as receptionist to continue');
        }

        $data = [
            'title' => 'Settings',
            'user' => [
                'name' => $this->session->get('user_name'),
                'email' => $this->session->get('user_email'),
                'role' => $this->session->get('user_role')
            ]
        ];

        return view('reception/settings', $data);
    }
}

