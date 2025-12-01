<?php

namespace App\Controllers;

use App\Models\UserModel;
use App\Models\PatientModel;
use App\Models\AppointmentModel;
use App\Models\DepartmentModel;

class Dashboard extends BaseController
{
    protected $userModel;
    protected $patientModel;
    protected $appointmentModel;
    protected $deptModel;
    protected $session;

    public function __construct()
    {
        $this->userModel = new UserModel();
        $this->patientModel = new PatientModel();
        $this->appointmentModel = new AppointmentModel();
        $this->deptModel = new DepartmentModel();
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
        $data = [
            'title' => 'Dashboard',
            'user' => [
                'name' => $this->session->get('user_name'),
                'email' => $this->session->get('user_email'),
                'role' => $this->session->get('user_role')
            ],
            'stats' => $this->getDashboardStats()
        ];
        
        return view('dashboard', $data);
    }
    
    private function getDashboardStats()
    {
        // Get counts from database
        $totalPatients = $this->patientModel->countAllResults();
        $totalDoctors = $this->userModel->where('role', 'doctor')->countAllResults();
        $totalAdmins = $this->userModel->where('role', 'admin')->countAllResults();
        $totalReceptionists = $this->userModel->where('role', 'receptionist')->countAllResults();
        
        return [
            'total_patients' => $totalPatients,
            'total_doctors' => $totalDoctors,
            'total_admins' => $totalAdmins,
            'total_receptionists' => $totalReceptionists
        ];
    }
}

