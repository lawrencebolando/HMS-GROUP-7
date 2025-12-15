<?php

namespace App\Controllers;

use App\Models\PatientModel;
use App\Models\AppointmentModel;
use App\Models\UserModel;
use App\Models\DepartmentModel;

class Reports extends BaseController
{
    protected $patientModel;
    protected $appointmentModel;
    protected $userModel;
    protected $deptModel;
    protected $session;
    protected $db;

    public function __construct()
    {
        $this->patientModel = new PatientModel();
        $this->appointmentModel = new AppointmentModel();
        $this->userModel = new UserModel();
        $this->deptModel = new DepartmentModel();
        $this->session = session();
        $this->db = \Config\Database::connect();
    }

    public function index()
    {
        // Check if user is logged in
        if (!$this->session->get('is_logged_in')) {
            return redirect()->to('login')->with('error', 'Please login to continue');
        }

        // Get filter parameters
        $reportType = $this->request->getGet('report_type') ?? 'overview';
        $dateFrom = $this->request->getGet('date_from') ?? date('Y-m-01');
        $dateTo = $this->request->getGet('date_to') ?? date('Y-m-d');

        // Get report data based on type
        $reportData = [];
        $stats = [
            'total_patients' => 0,
            'total_appointments' => 0,
            'total_doctors' => 0,
            'total_departments' => 0
        ];

        if ($reportType === 'patients') {
            $reportData = $this->patientModel
                ->where('DATE(created_at) >=', $dateFrom)
                ->where('DATE(created_at) <=', $dateTo)
                ->orderBy('created_at', 'DESC')
                ->findAll();
        } elseif ($reportType === 'appointments') {
            $reportData = $this->appointmentModel
                ->where('appointment_date >=', $dateFrom)
                ->where('appointment_date <=', $dateTo)
                ->orderBy('appointment_date', 'DESC')
                ->findAll();
        }

        // Get overall statistics
        $stats['total_patients'] = $this->patientModel->countAllResults();
        $stats['total_appointments'] = $this->appointmentModel->countAllResults();
        $stats['total_doctors'] = $this->userModel->where('role', 'doctor')->countAllResults();
        $stats['total_departments'] = $this->deptModel->countAllResults();

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
            'stats' => $stats
        ];

        return view('reports/index', $data);
    }
}

