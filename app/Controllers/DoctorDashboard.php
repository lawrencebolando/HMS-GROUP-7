<?php

namespace App\Controllers;

use App\Models\UserModel;
use App\Models\PatientModel;
use App\Models\AppointmentModel;
use App\Models\DoctorModel;

class DoctorDashboard extends BaseController
{
    protected $userModel;
    protected $patientModel;
    protected $appointmentModel;
    protected $doctorModel;
    protected $session;

    public function __construct()
    {
        $this->userModel = new UserModel();
        $this->patientModel = new PatientModel();
        $this->appointmentModel = new AppointmentModel();
        $this->doctorModel = new DoctorModel();
        $this->session = session();
    }

    public function index()
    {
        // Check if user is logged in
        if (!$this->session->get('is_logged_in')) {
            return redirect()->to('login')->with('error', 'Please login to continue');
        }
        
        // Check if user is doctor
        if ($this->session->get('user_role') !== 'doctor') {
            return redirect()->to('login')->with('error', 'Access denied. Doctor only.');
        }

        // Get doctor information
        $userId = $this->session->get('user_id');
        $userEmail = $this->session->get('user_email');
        
        // Find doctor by email (assuming email matches between users and doctors)
        $doctor = $this->doctorModel->where('email', $userEmail)->first();
        
        if (!$doctor) {
            // If doctor not found in doctors table, use user info
            $doctor = [
                'id' => null,
                'full_name' => $this->session->get('user_name'),
                'specialization' => 'General Practitioner',
                'email' => $userEmail
            ];
        }

        $doctorId = $doctor['id'] ?? null;
        
        // Get dashboard statistics
        $stats = $this->getDashboardStats($doctorId);
        $todayAppointments = $this->getTodayAppointments($doctorId);
        $patientRecords = $this->getPatientRecords($doctorId);
        
        $data = [
            'title' => 'Doctor Dashboard',
            'user' => [
                'name' => $doctor['full_name'] ?? $this->session->get('user_name'),
                'email' => $doctor['email'] ?? $this->session->get('user_email'),
                'role' => $this->session->get('user_role'),
                'specialization' => $doctor['specialization'] ?? 'General Practitioner'
            ],
            'stats' => $stats,
            'today_appointments' => $todayAppointments,
            'patient_records' => $patientRecords
        ];
        
        return view('doctor/dashboard', $data);
    }
    
    private function getDashboardStats($doctorId = null)
    {
        $todayDate = date('Y-m-d');
        
        // Get total patients (patients who have appointments with this doctor)
        if ($doctorId) {
            $totalPatients = $this->appointmentModel
                ->select('DISTINCT(patient_id)')
                ->where('doctor_id', $doctorId)
                ->countAllResults();
            
            // New patients today
            $newPatientsToday = $this->appointmentModel
                ->select('DISTINCT(patient_id)')
                ->where('doctor_id', $doctorId)
                ->where('appointment_date', $todayDate)
                ->countAllResults();
        } else {
            // If doctor not in doctors table, use user_id from session
            $userId = $this->session->get('user_id');
            $totalPatients = $this->appointmentModel
                ->select('DISTINCT(patient_id)')
                ->where('doctor_id', $userId)
                ->countAllResults();
            
            $newPatientsToday = $this->appointmentModel
                ->select('DISTINCT(patient_id)')
                ->where('doctor_id', $userId)
                ->where('appointment_date', $todayDate)
                ->countAllResults();
        }
        
        return [
            'total_patients' => $totalPatients,
            'new_patients_today' => $newPatientsToday,
            'admitted_patients' => 0, // Can be implemented later
            'critical_patients' => 0   // Can be implemented later
        ];
    }
    
    private function getTodayAppointments($doctorId = null)
    {
        $todayDate = date('Y-m-d');
        
        if ($doctorId) {
            $appointments = $this->appointmentModel
                ->where('doctor_id', $doctorId)
                ->where('appointment_date', $todayDate)
                ->orderBy('appointment_time', 'ASC')
                ->findAll();
        } else {
            $userId = $this->session->get('user_id');
            $appointments = $this->appointmentModel
                ->where('doctor_id', $userId)
                ->where('appointment_date', $todayDate)
                ->orderBy('appointment_time', 'ASC')
                ->findAll();
        }
        
        foreach ($appointments as &$apt) {
            $patient = $this->patientModel->find($apt['patient_id']);
            if ($patient) {
                $apt['patient_name'] = trim(($patient['first_name'] ?? '') . ' ' . ($patient['last_name'] ?? ''));
                $apt['patient_age'] = $this->calculateAge($patient['date_of_birth'] ?? null);
                $apt['patient_gender'] = $patient['gender'] ?? 'N/A';
                $apt['patient_contact'] = $patient['phone'] ?? 'N/A';
                $apt['patient_email'] = $patient['email'] ?? 'N/A';
                $apt['patient_blood'] = $patient['blood_group'] ?? 'N/A';
            } else {
                $apt['patient_name'] = 'Unknown';
                $apt['patient_age'] = 'N/A';
                $apt['patient_gender'] = 'N/A';
                $apt['patient_contact'] = 'N/A';
                $apt['patient_email'] = 'N/A';
                $apt['patient_blood'] = 'N/A';
            }
        }
        
        return $appointments;
    }
    
    private function getPatientRecords($doctorId = null)
    {
        if ($doctorId) {
            // Get distinct patients who have appointments with this doctor
            $appointments = $this->appointmentModel
                ->select('patient_id, MAX(created_at) as last_visit')
                ->where('doctor_id', $doctorId)
                ->groupBy('patient_id')
                ->orderBy('last_visit', 'DESC')
                ->findAll();
        } else {
            $userId = $this->session->get('user_id');
            $appointments = $this->appointmentModel
                ->select('patient_id, MAX(created_at) as last_visit')
                ->where('doctor_id', $userId)
                ->groupBy('patient_id')
                ->orderBy('last_visit', 'DESC')
                ->findAll();
        }
        
        $patients = [];
        foreach ($appointments as $apt) {
            $patient = $this->patientModel->find($apt['patient_id']);
            if ($patient) {
                $patient['last_visit'] = $apt['last_visit'];
                $patients[] = $patient;
            }
        }
        
        return $patients;
    }
    
    private function calculateAge($dateOfBirth)
    {
        if (!$dateOfBirth) {
            return 'N/A';
        }
        
        $birthDate = new \DateTime($dateOfBirth);
        $today = new \DateTime();
        $age = $today->diff($birthDate);
        
        return $age->y . ' years';
    }
}

