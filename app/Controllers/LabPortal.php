<?php

namespace App\Controllers;

use App\Models\PatientModel;
use App\Models\UserModel;
use CodeIgniter\Database\BaseConnection;

class LabPortal extends BaseController
{
    protected $patientModel;
    protected $userModel;
    protected $db;
    protected $session;

    public function __construct()
    {
        $this->patientModel = new PatientModel();
        $this->userModel = new UserModel();
        $this->db = \Config\Database::connect();
        $this->session = session();
    }

    public function index()
    {
        $userRole = $this->session->get('user_role');
        if (!$this->session->get('is_logged_in') || !in_array($userRole, ['lab_technician', 'lab_staff', 'lab'])) {
            return redirect()->to('login')->with('error', 'Access denied. Lab staff only.');
        }

        $labRequestsExists = $this->db->tableExists('lab_requests');
        $labResultsExists = $this->db->tableExists('lab_results');

        $stats = $this->getLabStats($labRequestsExists, $labResultsExists);
        $pendingRequests = $this->getPendingRequests($labRequestsExists);
        $recentResults = $this->getRecentResults($labResultsExists);

        $data = [
            'title' => 'Lab Portal - Dashboard',
            'user' => [
                'name' => $this->session->get('user_name'),
                'email' => $this->session->get('user_email'),
                'role' => $this->session->get('user_role')
            ],
            'stats' => $stats,
            'pending_requests' => $pendingRequests,
            'recent_results' => $recentResults
        ];

        return view('lab_portal/dashboard', $data);
    }

    private function getLabStats($labRequestsExists, $labResultsExists)
    {
        try {
            $pendingTests = 0;
            if ($labRequestsExists) {
                $pendingTests = $this->db->table('lab_requests')
                    ->where('status', 'pending')
                    ->countAllResults();
            }

            $completedToday = 0;
            if ($labResultsExists) {
                $today = date('Y-m-d');
                $completedToday = $this->db->table('lab_results')
                    ->where('released_date', $today)
                    ->countAllResults();
            }

            $urgentTests = 0;
            if ($labRequestsExists) {
                $urgentTests = $this->db->table('lab_requests')
                    ->whereIn('priority', ['urgent', 'emergency'])
                    ->where('status', 'pending')
                    ->countAllResults();
            }

            $criticalTests = 0;
            if ($labResultsExists) {
                $criticalTests = $this->db->table('lab_results')
                    ->where('is_critical', 1)
                    ->where('status', 'released')
                    ->countAllResults();
            }

            return [
                'pending_tests' => $pendingTests,
                'completed_today' => $completedToday,
                'urgent_tests' => $urgentTests,
                'critical_tests' => $criticalTests
            ];
        } catch (\Exception $e) {
            log_message('error', 'Error fetching lab stats: ' . $e->getMessage());
            return [
                'pending_tests' => 0,
                'completed_today' => 0,
                'urgent_tests' => 0,
                'critical_tests' => 0
            ];
        }
    }

    private function getPendingRequests($tableExists)
    {
        if (!$tableExists) {
            return [];
        }

        try {
            $requests = $this->db->table('lab_requests')
                ->where('status', 'pending')
                ->orderBy('priority', 'DESC')
                ->orderBy('requested_date', 'ASC')
                ->limit(10)
                ->get()
                ->getResultArray();

            foreach ($requests as &$request) {
                $patient = $this->patientModel->find($request['patient_id']);
                $doctor = $this->userModel->find($request['doctor_id']);

                $request['patient_name'] = $patient ? ($patient['first_name'] . ' ' . $patient['last_name']) : 'Unknown';
                $request['doctor_name'] = $doctor ? $doctor['name'] : 'Unknown';
            }

            return $requests;
        } catch (\Exception $e) {
            log_message('error', 'Error fetching pending requests: ' . $e->getMessage());
            return [];
        }
    }

    private function getRecentResults($tableExists)
    {
        if (!$tableExists) {
            return [];
        }

        try {
            $results = $this->db->table('lab_results')
                ->orderBy('released_date', 'DESC')
                ->orderBy('released_time', 'DESC')
                ->limit(10)
                ->get()
                ->getResultArray();

            foreach ($results as &$result) {
                $patient = $this->patientModel->find($result['patient_id']);
                $result['patient_name'] = $patient ? ($patient['first_name'] . ' ' . $patient['last_name']) : 'Unknown';
            }

            return $results;
        } catch (\Exception $e) {
            log_message('error', 'Error fetching recent results: ' . $e->getMessage());
            return [];
        }
    }
}

