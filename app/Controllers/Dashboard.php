<?php

namespace App\Controllers;

use App\Models\UserModel;
use App\Models\PatientModel;
use App\Models\AppointmentModel;
use App\Models\DepartmentModel;
use App\Models\DoctorModel;

class Dashboard extends BaseController
{
    protected $userModel;
    protected $patientModel;
    protected $appointmentModel;
    protected $deptModel;
    protected $doctorModel;
    protected $session;

    public function __construct()
    {
        $this->userModel = new UserModel();
        $this->patientModel = new PatientModel();
        $this->appointmentModel = new AppointmentModel();
        $this->deptModel = new DepartmentModel();
        $this->doctorModel = new DoctorModel();
        $this->session = session();
    }

    public function index()
    {
        // Check if user is logged in
        if (!$this->session->get('is_logged_in')) {
            return redirect()->to('login')->with('error', 'Please login to continue');
        }
        
        // Check if user is admin
        if ($this->session->get('user_role') !== 'admin') {
            return redirect()->to('login')->with('error', 'Access denied. Admin only.');
        }
        
        // Get dashboard statistics
        $stats = $this->getDashboardStats();
        $todayAppointments = $this->getTodayAppointments();
        $upcomingAppointments = $this->getUpcomingAppointments();
        $recentActivities = $this->getRecentActivities();
        
        $data = [
            'title' => 'Dashboard',
            'user' => [
                'name' => $this->session->get('user_name'),
                'email' => $this->session->get('user_email'),
                'role' => $this->session->get('user_role')
            ],
            'stats' => $stats,
            'today_appointments' => $todayAppointments,
            'upcoming_appointments' => $upcomingAppointments,
            'recent_activities' => $recentActivities
        ];
        
        return view('dashboard', $data);
    }
    
    private function getDashboardStats()
    {
        // Get counts from database
        $totalPatients = $this->patientModel->countAllResults();
        
        // Count all doctors from the doctors table (including all statuses for "Available Doctors" display)
        // This shows total doctors in the system
        $totalDoctors = $this->doctorModel->countAllResults();
        
        $todayDate = date('Y-m-d');
        $todayAppointmentsCount = $this->appointmentModel->where('appointment_date', $todayDate)->countAllResults();
        
        // Calculate percentage changes (mock data for now)
        $patientChange = '+12%';
        $appointmentChange = '+8%';
        $doctorChange = '+0%';
        
        return [
            'total_patients' => $totalPatients,
            'total_doctors' => $totalDoctors,
            'today_appointments' => $todayAppointmentsCount,
            'patient_change' => $patientChange,
            'appointment_change' => $appointmentChange,
            'doctor_change' => $doctorChange,
            'occupied_beds' => 187,
            'total_beds' => 220,
            'bed_percentage' => 85
        ];
    }
    
    private function getTodayAppointments()
    {
        $todayDate = date('Y-m-d');
        $appointments = $this->appointmentModel
            ->where('appointment_date', $todayDate)
            ->orderBy('appointment_time', 'ASC')
            ->findAll(5);
        
        foreach ($appointments as &$apt) {
            $patient = $this->patientModel->find($apt['patient_id']);
            $doctor = $this->userModel->find($apt['doctor_id']);
            $apt['patient_name'] = $patient ? ($patient['first_name'] . ' ' . $patient['last_name']) : 'Unknown';
            $apt['doctor_name'] = $doctor ? $doctor['name'] : 'Unknown';
        }
        
        return $appointments;
    }
    
    private function getUpcomingAppointments()
    {
        $todayDate = date('Y-m-d');
        $appointments = $this->appointmentModel
            ->where('appointment_date >=', $todayDate)
            ->where('status', 'scheduled')
            ->orderBy('appointment_date', 'ASC')
            ->orderBy('appointment_time', 'ASC')
            ->findAll(3);
        
        foreach ($appointments as &$apt) {
            $patient = $this->patientModel->find($apt['patient_id']);
            $doctor = $this->userModel->find($apt['doctor_id']);
            $apt['patient_name'] = $patient ? ($patient['first_name'] . ' ' . $patient['last_name']) : 'Unknown';
            $apt['doctor_name'] = $doctor ? $doctor['name'] : 'Unknown';
        }
        
        return $appointments;
    }
    
    private function getRecentActivities()
    {
        // Get recent appointments as activities
        $appointments = $this->appointmentModel
            ->orderBy('created_at', 'DESC')
            ->findAll(4);
        
        $activities = [];
        foreach ($appointments as $apt) {
            $patient = $this->patientModel->find($apt['patient_id']);
            $doctor = $this->userModel->find($apt['doctor_id']);
            $patientName = $patient ? ($patient['first_name'] . ' ' . $patient['last_name']) : 'Unknown';
            $doctorName = $doctor ? $doctor['name'] : 'Unknown';
            
            $timeAgo = $this->timeAgo($apt['created_at']);
            
            $activities[] = [
                'type' => 'appointment',
                'message' => "New appointment scheduled with {$doctorName}",
                'time' => $timeAgo,
                'icon' => 'fa-bolt',
                'color' => 'blue'
            ];
        }
        
        return $activities;
    }
    
    private function timeAgo($datetime)
    {
        $timestamp = strtotime($datetime);
        $diff = time() - $timestamp;
        
        if ($diff < 60) {
            return $diff . ' seconds ago';
        } elseif ($diff < 3600) {
            return floor($diff / 60) . ' minutes ago';
        } elseif ($diff < 86400) {
            return floor($diff / 3600) . ' hours ago';
        } else {
            return floor($diff / 86400) . ' days ago';
        }
    }
}

