<?php

namespace App\Controllers;

use App\Models\PatientModel;
use App\Models\UserModel;
use CodeIgniter\Database\BaseConnection;

class Laboratory extends BaseController
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
        if (!$this->session->get('is_logged_in') || $this->session->get('user_role') !== 'admin') {
            return redirect()->to('login')->with('error', 'Access denied. Admin only.');
        }

        $labRequestsExists = $this->db->tableExists('lab_requests');
        $labResultsExists = $this->db->tableExists('lab_results');

        $stats = $this->getLaboratoryStats($labRequestsExists, $labResultsExists);
        $testRequests = $this->getRecentTestRequests($labRequestsExists);
        $testResults = $this->getRecentTestResults($labResultsExists);

        $data = [
            'title' => 'Laboratory Dashboard',
            'user' => [
                'name' => $this->session->get('user_name'),
                'email' => $this->session->get('user_email'),
                'role' => $this->session->get('user_role')
            ],
            'lab_requests_exists' => $labRequestsExists,
            'lab_results_exists' => $labResultsExists,
            'stats' => $stats,
            'test_requests' => $testRequests,
            'test_results' => $testResults
        ];

        return view('laboratory/index', $data);
    }

    private function getLaboratoryStats($labRequestsExists, $labResultsExists)
    {
        if (!$labRequestsExists && !$labResultsExists) {
            return [
                'pending_requests' => 0,
                'completed_today' => 0,
                'critical_results' => 0,
                'active_lab_staff' => 0
            ];
        }

        try {
            // Pending Test Requests
            $pendingRequests = 0;
            if ($labRequestsExists) {
                $pendingRequests = $this->db->table('lab_requests')
                    ->where('status', 'pending')
                    ->countAllResults();
            }

            // Completed Tests Today
            $completedToday = 0;
            if ($labResultsExists) {
                $today = date('Y-m-d');
                $completedToday = $this->db->table('lab_results')
                    ->where('released_date', $today)
                    ->countAllResults();
            }

            // Critical Results
            $criticalResults = 0;
            if ($labResultsExists) {
                $criticalResults = $this->db->table('lab_results')
                    ->where('is_critical', 1)
                    ->where('status', 'released')
                    ->countAllResults();
            }

            // Active Lab Staff (users with role 'lab_technician' or 'lab_staff')
            $activeLabStaff = $this->userModel
                ->whereIn('role', ['lab_technician', 'lab_staff', 'lab'])
                ->where('status', 'active')
                ->countAllResults();

            return [
                'pending_requests' => $pendingRequests,
                'completed_today' => $completedToday,
                'critical_results' => $criticalResults,
                'active_lab_staff' => $activeLabStaff
            ];
        } catch (\Exception $e) {
            log_message('error', 'Error fetching laboratory stats: ' . $e->getMessage());
            return [
                'pending_requests' => 0,
                'completed_today' => 0,
                'critical_results' => 0,
                'active_lab_staff' => 0
            ];
        }
    }

    private function getRecentTestRequests($tableExists)
    {
        if (!$tableExists) {
            return [];
        }

        try {
            $requests = $this->db->table('lab_requests')
                ->orderBy('requested_date', 'DESC')
                ->orderBy('created_at', 'DESC')
                ->limit(10)
                ->get()
                ->getResultArray();

            foreach ($requests as &$request) {
                $patient = $this->patientModel->find($request['patient_id']);
                $doctor = $this->userModel->find($request['doctor_id']);

                $request['patient_name'] = $patient ? ($patient['first_name'] . ' ' . $patient['last_name']) : 'Unknown Patient';
                $request['doctor_name'] = $doctor ? $doctor['name'] : 'Unknown Doctor';
                $request['branch'] = 'Main Branch'; // Default branch, can be customized
            }

            return $requests;
        } catch (\Exception $e) {
            log_message('error', 'Error fetching recent test requests: ' . $e->getMessage());
            return [];
        }
    }

    private function getRecentTestResults($tableExists)
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
                $releasedBy = $this->userModel->find($result['released_by']);

                $result['patient_name'] = $patient ? ($patient['first_name'] . ' ' . $patient['last_name']) : 'Unknown Patient';
                $result['released_by_name'] = $releasedBy ? $releasedBy['name'] : 'Unknown';
            }

            return $results;
        } catch (\Exception $e) {
            log_message('error', 'Error fetching recent test results: ' . $e->getMessage());
            return [];
        }
    }
}

