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

        $today = date('Y-m-d');
        $yesterday = date('Y-m-d', strtotime('-1 day'));
        $db = \Config\Database::connect();
        
        // Get patient statistics
        $totalPatients = $this->patientModel->countAllResults();
        $newPatientsToday = $this->patientModel
            ->where('DATE(created_at)', $today)
            ->countAllResults();
        
        $newPatientsYesterday = $this->patientModel
            ->where('DATE(created_at)', $yesterday)
            ->countAllResults();
        
        $newPatientsChange = $newPatientsToday - $newPatientsYesterday;
        
        // Get appointments statistics
        $appointmentsToday = $this->appointmentModel
            ->where('appointment_date', $today)
            ->countAllResults();
        
        // Get walk-ins (from walk_in_lab_requests table)
        $walkInsToday = 0;
        if ($db->tableExists('walk_in_lab_requests')) {
            $walkInsToday = $db->table('walk_in_lab_requests')
                ->where('DATE(request_date)', $today)
                ->countAllResults();
        }
        
        // Get discharged patients (from admissions table if exists)
        $dischargedToday = 0;
        if ($db->tableExists('admissions')) {
            $dischargedToday = $db->table('admissions')
                ->where('DATE(discharge_date)', $today)
                ->where('status', 'discharged')
                ->countAllResults();
        }
        
        // Get today's appointments breakdown
        $confirmedAppointments = $this->appointmentModel
            ->where('appointment_date', $today)
            ->where('status', 'scheduled')
            ->countAllResults();
        
        $pendingAppointments = $this->appointmentModel
            ->where('appointment_date', $today)
            ->where('status', 'pending')
            ->countAllResults();
        
        $cancelledAppointments = $this->appointmentModel
            ->where('appointment_date', $today)
            ->where('status', 'cancelled')
            ->countAllResults();
        
        // Get upcoming appointments
        $upcomingAppointments = $this->appointmentModel
            ->where('appointment_date >', $today)
            ->orderBy('appointment_date', 'ASC')
            ->orderBy('appointment_time', 'ASC')
            ->limit(5)
            ->findAll();
        
        // Enrich upcoming appointments with patient data
        $upcomingAppointmentsWithPatients = [];
        foreach ($upcomingAppointments as $appt) {
            $patient = $this->patientModel->find($appt['patient_id']);
            $appt['patient_name'] = $patient ? ($patient['first_name'] . ' ' . $patient['last_name']) : 'Unknown';
            $upcomingAppointmentsWithPatients[] = $appt;
        }
        
        // Get current user data from database
        $userId = $this->session->get('user_id');
        $currentUser = $this->userModel->find($userId);
        
        // Generate employee ID (mock - in real system this would come from database)
        $employeeId = 'RC-' . str_pad($userId, 4, '0', STR_PAD_LEFT) . '-' . rand(100, 999);

        $data = [
            'title' => 'Dashboard',
            'user' => [
                'name' => $currentUser ? $currentUser['name'] : $this->session->get('user_name'),
                'email' => $currentUser ? $currentUser['email'] : $this->session->get('user_email'),
                'role' => $currentUser ? $currentUser['role'] : $this->session->get('user_role'),
                'employee_id' => $employeeId
            ],
            'stats' => [
                'new_patients_today' => $newPatientsToday,
                'new_patients_change' => $newPatientsChange,
                'appointments' => $appointmentsToday,
                'walkins' => $walkInsToday,
                'discharged' => $dischargedToday
            ],
            'appointments' => [
                'total' => $appointmentsToday,
                'confirmed' => $confirmedAppointments,
                'pending' => $pendingAppointments,
                'cancelled' => $cancelledAppointments
            ],
            'upcoming_appointments' => $upcomingAppointmentsWithPatients
        ];

        return view('reception/dashboard', $data);
    }

    public function patients()
    {
        if (!$this->session->get('is_logged_in') || $this->session->get('user_role') !== 'receptionist') {
            return redirect()->to('login')->with('error', 'Please login as receptionist to continue');
        }

        // Get patient statistics from the shared patients table
        // This is the same table that admin views, so all patients are shared
        $db = \Config\Database::connect();
        $totalPatients = $db->table('patients')->countAllResults();
        $today = date('Y-m-d');
        $newPatientsToday = $db->table('patients')
            ->where('DATE(created_at)', $today)
            ->countAllResults();
        
        // Get all patients from the shared database table
        $patients = $db->table('patients')
            ->orderBy('created_at', 'DESC')
            ->get()
            ->getResultArray();

        $data = [
            'title' => 'Patient Registration',
            'user' => [
                'name' => $this->session->get('user_name'),
                'email' => $this->session->get('user_email'),
                'role' => $this->session->get('user_role')
            ],
            'patients' => $patients,
            'stats' => [
                'total_patients' => $totalPatients,
                'new_patients_today' => $newPatientsToday
            ]
        ];

        return view('reception/patients', $data);
    }

    public function appointments()
    {
        if (!$this->session->get('is_logged_in') || $this->session->get('user_role') !== 'receptionist') {
            return redirect()->to('login')->with('error', 'Please login as receptionist to continue');
        }

        $today = date('Y-m-d');
        
        // Get today's appointments
        $todayAppointments = $this->appointmentModel
            ->where('appointment_date', $today)
            ->orderBy('appointment_time', 'ASC')
            ->findAll();
        
        // Get upcoming appointments
        $upcomingAppointments = $this->appointmentModel
            ->where('appointment_date >', $today)
            ->orderBy('appointment_date', 'ASC')
            ->orderBy('appointment_time', 'ASC')
            ->findAll();
        
        // Enrich appointments with patient and doctor details
        $todayAppointmentsWithDetails = [];
        foreach ($todayAppointments as $appt) {
            $patient = $this->patientModel->find($appt['patient_id']);
            $doctor = $this->userModel->find($appt['doctor_id']);
            $appt['patient'] = $patient;
            $appt['doctor'] = $doctor;
            $todayAppointmentsWithDetails[] = $appt;
        }
        
        $upcomingAppointmentsWithDetails = [];
        foreach ($upcomingAppointments as $appt) {
            $patient = $this->patientModel->find($appt['patient_id']);
            $doctor = $this->userModel->find($appt['doctor_id']);
            $appt['patient'] = $patient;
            $appt['doctor'] = $doctor;
            $upcomingAppointmentsWithDetails[] = $appt;
        }
        
        // Count pending check-ins (appointments scheduled but not checked in)
        $pendingCheckins = $this->appointmentModel
            ->where('appointment_date', $today)
            ->where('status', 'pending')
            ->countAllResults();

        $data = [
            'title' => 'Appointments',
            'user' => [
                'name' => $this->session->get('user_name'),
                'email' => $this->session->get('user_email'),
                'role' => $this->session->get('user_role')
            ],
            'today_appointments' => $todayAppointmentsWithDetails,
            'upcoming_appointments' => $upcomingAppointmentsWithDetails,
            'stats' => [
                'today_appointments' => count($todayAppointmentsWithDetails),
                'pending_checkins' => $pendingCheckins
            ]
        ];

        return view('reception/appointments', $data);
    }

    public function createAppointment()
    {
        if (!$this->session->get('is_logged_in') || $this->session->get('user_role') !== 'receptionist') {
            return redirect()->to('login')->with('error', 'Please login as receptionist to continue');
        }

        $db = \Config\Database::connect();
        
        // Get all patients
        $patients = $this->patientModel->orderBy('first_name', 'ASC')->findAll();
        
        // Get all doctors (from users table with role 'doctor' or from doctors table)
        $doctors = [];
        $doctorUsers = $this->userModel->where('role', 'doctor')->where('status', 'active')->findAll();
        
        // Also try to get from doctors table if it exists
        if ($db->tableExists('doctors')) {
            $doctorModel = new \App\Models\DoctorModel();
            $doctorsFromTable = $doctorModel->where('status', 'active')->findAll();
            foreach ($doctorsFromTable as $doc) {
                $doctors[] = [
                    'id' => $doc['id'],
                    'name' => $doc['full_name'],
                    'specialization' => $doc['specialization'] ?? ''
                ];
            }
        } else {
            // Use users table
            foreach ($doctorUsers as $doc) {
                $doctors[] = [
                    'id' => $doc['id'],
                    'name' => $doc['name'],
                    'specialization' => 'General Practitioner'
                ];
            }
        }
        
        // Get rooms (for OPD clinic rooms)
        $rooms = [];
        if ($db->tableExists('rooms')) {
            $rooms = $db->table('rooms')
                ->where('status', 'available')
                ->orWhere('status', 'reserved')
                ->orderBy('room_number', 'ASC')
                ->get()
                ->getResultArray();
        }

        $data = [
            'title' => 'Add New Appointment',
            'user' => [
                'name' => $this->session->get('user_name'),
                'email' => $this->session->get('user_email'),
                'role' => $this->session->get('user_role')
            ],
            'patients' => $patients,
            'doctors' => $doctors,
            'rooms' => $rooms
        ];

        return view('reception/create_appointment', $data);
    }

    public function storeAppointment()
    {
        if (!$this->session->get('is_logged_in') || $this->session->get('user_role') !== 'receptionist') {
            return redirect()->to('login')->with('error', 'Please login as receptionist to continue');
        }

        // Validate required fields
        $validation = \Config\Services::validation();
        $validation->setRules([
            'patient_id' => 'required|integer',
            'doctor_id' => 'required|integer',
            'appointment_date' => 'required',
            'appointment_time' => 'required',
            'status' => 'required'
        ]);

        if (!$validation->run($this->request->getPost())) {
            return redirect()->back()->withInput()->with('errors', $validation->getErrors());
        }

        $data = [
            'patient_id' => $this->request->getPost('patient_id'),
            'doctor_id' => $this->request->getPost('doctor_id'),
            'appointment_date' => $this->request->getPost('appointment_date'),
            'appointment_time' => $this->request->getPost('appointment_time'),
            'status' => $this->request->getPost('status'),
            'notes' => trim($this->request->getPost('notes')) ?: null,
            'room' => $this->request->getPost('room') ?: null
        ];

        try {
            $insertId = $this->appointmentModel->skipValidation(true)->insert($data);
            if ($insertId) {
                // Redirect to main appointments page with the appointment date
                $appointmentDate = $this->request->getPost('appointment_date');
                return redirect()->to('appointments?date=' . $appointmentDate)->with('success', 'Appointment scheduled successfully!');
            } else {
                $dbError = $this->appointmentModel->db->error();
                $errors = ['database' => 'Failed to save appointment: ' . ($dbError['message'] ?? 'Unknown error')];
                return redirect()->back()->withInput()->with('errors', $errors);
            }
        } catch (\Exception $e) {
            return redirect()->back()->withInput()->with('errors', ['database' => 'Error: ' . $e->getMessage()]);
        }
    }

    public function followUps()
    {
        if (!$this->session->get('is_logged_in') || $this->session->get('user_role') !== 'receptionist') {
            return redirect()->to('login')->with('error', 'Please login as receptionist to continue');
        }

        $today = date('Y-m-d');
        
        // Get today's follow-ups (show all appointments for today as follow-ups)
        // In a real system, you'd filter by appointment_type or a follow_up flag
        $todayFollowups = $this->appointmentModel
            ->where('appointment_date', $today)
            ->orderBy('appointment_time', 'ASC')
            ->findAll();
        
        // Get upcoming follow-ups (show all upcoming appointments)
        $upcomingFollowups = $this->appointmentModel
            ->where('appointment_date >', $today)
            ->orderBy('appointment_date', 'ASC')
            ->orderBy('appointment_time', 'ASC')
            ->findAll();
        
        // Enrich follow-ups with patient and doctor details
        $todayFollowupsWithDetails = [];
        foreach ($todayFollowups as $followup) {
            $patient = $this->patientModel->find($followup['patient_id']);
            $doctor = $this->userModel->find($followup['doctor_id']);
            $followup['patient'] = $patient;
            $followup['doctor'] = $doctor;
            $todayFollowupsWithDetails[] = $followup;
        }
        
        $upcomingFollowupsWithDetails = [];
        foreach ($upcomingFollowups as $followup) {
            $patient = $this->patientModel->find($followup['patient_id']);
            $doctor = $this->userModel->find($followup['doctor_id']);
            $followup['patient'] = $patient;
            $followup['doctor'] = $doctor;
            $upcomingFollowupsWithDetails[] = $followup;
        }
        
        // Count pending check-ins
        $pendingCheckins = $this->appointmentModel
            ->where('appointment_date', $today)
            ->where('status', 'pending')
            ->countAllResults();

        $data = [
            'title' => 'Follow-ups',
            'user' => [
                'name' => $this->session->get('user_name'),
                'email' => $this->session->get('user_email'),
                'role' => $this->session->get('user_role')
            ],
            'today_followups' => $todayFollowupsWithDetails,
            'upcoming_followups' => $upcomingFollowupsWithDetails,
            'stats' => [
                'today_followups' => count($todayFollowupsWithDetails),
                'pending_checkins' => $pendingCheckins
            ]
        ];

        return view('reception/follow-ups', $data);
    }

    public function reports()
    {
        if (!$this->session->get('is_logged_in') || $this->session->get('user_role') !== 'receptionist') {
            return redirect()->to('login')->with('error', 'Please login as receptionist to continue');
        }

        // Get filter parameters
        $reportType = $this->request->getGet('report_type') ?? 'new_patients';
        $dateFrom = $this->request->getGet('date_from') ?? date('Y-m-01');
        $dateTo = $this->request->getGet('date_to') ?? date('Y-m-d');
        
        // Get report data based on type
        $reportData = [];
        $summary = [
            'new_patients' => 0,
            'total_appointments' => 0,
            'checkins' => 0
        ];
        
        if ($reportType === 'new_patients') {
            // Get new patients in date range
            $reportData = $this->patientModel
                ->where('DATE(created_at) >=', $dateFrom)
                ->where('DATE(created_at) <=', $dateTo)
                ->orderBy('created_at', 'DESC')
                ->findAll();
            
            $summary['new_patients'] = count($reportData);
        }
        
        // Get summary statistics
        $summary['total_appointments'] = $this->appointmentModel
            ->where('appointment_date >=', $dateFrom)
            ->where('appointment_date <=', $dateTo)
            ->countAllResults();
        
        $summary['checkins'] = $this->appointmentModel
            ->where('appointment_date >=', $dateFrom)
            ->where('appointment_date <=', $dateTo)
            ->where('status', 'completed')
            ->countAllResults();

        $data = [
            'title' => 'Reports',
            'user' => [
                'name' => $this->session->get('user_name'),
                'email' => $this->session->get('user_email'),
                'role' => $this->session->get('user_role')
            ],
            'report_type' => $reportType,
            'date_from' => $dateFrom,
            'date_to' => $dateTo,
            'report_data' => $reportData,
            'summary' => $summary
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

