<?php

namespace App\Controllers;

use App\Models\PatientModel;
use App\Models\UserModel;
use CodeIgniter\Database\BaseConnection;

class Admissions extends BaseController
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

        // Check if admissions table exists
        $admissionsTableExists = $this->db->tableExists('admissions');
        $error = null;
        
        if (!$admissionsTableExists) {
            $error = "Admissions table does not exist in the database.";
        }

        // Get statistics
        $stats = $this->getAdmissionStats($admissionsTableExists);
        
        // Get admissions list
        $admissions = $this->getAdmissions($admissionsTableExists);
        
        $data = [
            'title' => 'Admissions',
            'user' => [
                'name' => $this->session->get('user_name'),
                'email' => $this->session->get('user_email'),
                'role' => $this->session->get('user_role')
            ],
            'error' => $error,
            'stats' => $stats,
            'admissions' => $admissions
        ];
        
        return view('admissions/index', $data);
    }

    private function getAdmissionStats($tableExists)
    {
        if (!$tableExists) {
            return [
                'total_admissions' => 0,
                'active_admissions' => 0,
                'admitted_today' => 0,
                'discharged_today' => 0
            ];
        }

        try {
            $totalAdmissions = $this->db->table('admissions')->countAllResults();
            $activeAdmissions = $this->db->table('admissions')
                ->where('status', 'active')
                ->countAllResults();
            
            $today = date('Y-m-d');
            $admittedToday = $this->db->table('admissions')
                ->where('admission_date', $today)
                ->countAllResults();
            
            $dischargedToday = $this->db->table('admissions')
                ->where('discharge_date', $today)
                ->where('status', 'discharged')
                ->countAllResults();

            return [
                'total_admissions' => $totalAdmissions,
                'active_admissions' => $activeAdmissions,
                'admitted_today' => $admittedToday,
                'discharged_today' => $dischargedToday
            ];
        } catch (\Exception $e) {
            return [
                'total_admissions' => 0,
                'active_admissions' => 0,
                'admitted_today' => 0,
                'discharged_today' => 0
            ];
        }
    }

    private function getAdmissions($tableExists)
    {
        if (!$tableExists) {
            return [];
        }

        try {
            $admissions = $this->db->table('admissions')
                ->orderBy('admission_date', 'DESC')
                ->orderBy('admission_time', 'DESC')
                ->get()
                ->getResultArray();

            // Enrich with patient and doctor info
            foreach ($admissions as &$admission) {
                if (isset($admission['patient_id'])) {
                    $patient = $this->patientModel->find($admission['patient_id']);
                    $admission['patient_name'] = $patient ? ($patient['first_name'] . ' ' . $patient['last_name']) : 'Unknown';
                } else {
                    $admission['patient_name'] = 'Unknown';
                }

                if (isset($admission['doctor_id'])) {
                    $doctor = $this->userModel->find($admission['doctor_id']);
                    $admission['doctor_name'] = $doctor ? $doctor['name'] : 'Unknown';
                } else {
                    $admission['doctor_name'] = 'Unknown';
                }

                // Format room display
                if (isset($admission['bed']) && !empty($admission['bed'])) {
                    $admission['room'] = ($admission['room'] ?? '') . '-' . $admission['bed'];
                }
            }

            return $admissions;
        } catch (\Exception $e) {
            return [];
        }
    }
}

