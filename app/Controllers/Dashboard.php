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
        $appointmentsData = $this->getAppointmentsData();
        $labData = $this->getLabData();
        $pharmacyData = $this->getPharmacyData();
        $recentActivities = $this->getRecentActivities();
        $billingData = $this->getBillingData();
        
        // Ensure all data arrays are properly initialized
        $data = [
            'title' => 'Dashboard',
            'user' => [
                'name' => $this->session->get('user_name') ?? 'Admin',
                'email' => $this->session->get('user_email') ?? '',
                'role' => $this->session->get('user_role') ?? 'admin'
            ],
            'stats' => $stats ?? [],
            'appointments' => $appointmentsData ?? ['upcoming' => 0, 'this_week' => 0, 'list' => []],
            'lab' => $labData ?? ['pending' => 0, 'completed_today' => 0, 'critical' => 0, 'tests' => []],
            'pharmacy' => $pharmacyData ?? ['low_stock' => 0, 'expiring_soon' => 0, 'movements_today' => 0, 'movements' => []],
            'recent_activities' => $recentActivities ?? [],
            'billing' => $billingData ?? ['revenue_this_month' => 0, 'outstanding_invoices' => 0, 'payments' => []]
        ];
        
        return view('dashboard', $data);
    }
    
    private function getDashboardStats()
    {
        // Get counts from database
        $totalPatients = $this->patientModel->countAllResults();
        $totalDoctors = $this->doctorModel->countAllResults();
        $totalNurses = $this->userModel->where('role', 'nurse')->countAllResults();
        
        $todayDate = date('Y-m-d');
        $todayAppointmentsCount = $this->appointmentModel->where('appointment_date', $todayDate)->countAllResults();
        $pendingAppointments = $this->appointmentModel->where('status', 'pending')->countAllResults();
        
        // Mock data for features not yet implemented
        $activeLabTests = 0;
        $lowStockMedicines = 0;
        $unpaidBills = 0;
        
        return [
            'total_patients' => $totalPatients,
            'total_doctors' => $totalDoctors,
            'total_nurses' => $totalNurses,
            'today_appointments' => $todayAppointmentsCount,
            'pending_appointments' => $pendingAppointments,
            'active_lab_tests' => $activeLabTests,
            'low_stock_medicines' => $lowStockMedicines,
            'unpaid_bills' => $unpaidBills
        ];
    }
    
    private function getAppointmentsData()
    {
        $todayDate = date('Y-m-d');
        $weekStart = date('Y-m-d', strtotime('monday this week'));
        $weekEnd = date('Y-m-d', strtotime('sunday this week'));
        
        $upcoming = $this->appointmentModel
            ->where('appointment_date >=', $todayDate)
            ->where('status', 'scheduled')
            ->countAllResults();
        
        $thisWeek = $this->appointmentModel
            ->where('appointment_date >=', $weekStart)
            ->where('appointment_date <=', $weekEnd)
            ->countAllResults();
        
        // Get recent appointments for table
        $appointments = $this->appointmentModel
            ->where('appointment_date >=', $todayDate)
            ->orderBy('appointment_date', 'ASC')
            ->orderBy('appointment_time', 'ASC')
            ->findAll(10);
        
        $appointmentsList = [];
        foreach ($appointments as $apt) {
            $patient = $this->patientModel->find($apt['patient_id']);
            $doctor = $this->userModel->find($apt['doctor_id']);
            $appointmentsList[] = [
                'patient_name' => $patient ? ($patient['first_name'] . ' ' . $patient['last_name']) : 'Unknown',
                'doctor_name' => $doctor ? $doctor['name'] : 'Unknown',
                'date' => $apt['appointment_date'],
                'time' => $apt['appointment_time'],
                'status' => $apt['status'] ?? 'scheduled'
            ];
        }
        
        return [
            'upcoming' => $upcoming,
            'this_week' => $thisWeek,
            'list' => $appointmentsList
        ];
    }
    
    private function getLabData()
    {
        // Mock data for lab tests (to be implemented)
        return [
            'pending' => 0,
            'completed_today' => 0,
            'critical' => 0,
            'tests' => []
        ];
    }
    
    private function getPharmacyData()
    {
        // Mock data for pharmacy (to be implemented)
        return [
            'low_stock' => 0,
            'expiring_soon' => 0,
            'movements_today' => 0,
            'movements' => []
        ];
    }
    
    private function getBillingData()
    {
        // Mock data for billing (to be implemented)
        return [
            'revenue_this_month' => 0.00,
            'outstanding_invoices' => 0,
            'payments' => []
        ];
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

