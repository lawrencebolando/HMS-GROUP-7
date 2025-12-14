<?php

namespace App\Controllers;

use App\Models\PatientModel;
use App\Models\UserModel;
use CodeIgniter\Database\BaseConnection;

class NursePortal extends BaseController
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
        if (!$this->session->get('is_logged_in') || $this->session->get('user_role') !== 'nurse') {
            return redirect()->to('login')->with('error', 'Access denied. Nurse only.');
        }

        $nurseId = $this->session->get('user_id');
        
        $stats = $this->getNurseStats($nurseId);
        $tasks = $this->getTodaysTasks($nurseId);
        $patients = $this->getAssignedPatients($nurseId);

        $data = [
            'title' => 'Nurse Portal - Dashboard',
            'user' => [
                'name' => $this->session->get('user_name'),
                'email' => $this->session->get('user_email'),
                'role' => $this->session->get('user_role')
            ],
            'stats' => $stats,
            'tasks' => $tasks,
            'patients' => $patients
        ];

        return view('nurse_portal/dashboard', $data);
    }

    private function getNurseStats($nurseId)
    {
        try {
            // Assigned Patients (mock - would need nurse_patient_assignments table)
            $assignedPatients = 0;
            
            // Pending Medications (mock - would need medication_schedule table)
            $pendingMedications = 0;
            
            // Vital Checks Due (mock - would need vital_checks table)
            $vitalChecksDue = 0;
            
            // Discharges Today (mock - would need admissions table)
            $dischargesToday = 0;
            if ($this->db->tableExists('admissions')) {
                $today = date('Y-m-d');
                $dischargesToday = $this->db->table('admissions')
                    ->where('discharge_date', $today)
                    ->where('status', 'discharged')
                    ->countAllResults();
            }

            return [
                'assigned_patients' => $assignedPatients,
                'pending_medications' => $pendingMedications,
                'vital_checks_due' => $vitalChecksDue,
                'discharges_today' => $dischargesToday
            ];
        } catch (\Exception $e) {
            log_message('error', 'Error fetching nurse stats: ' . $e->getMessage());
            return [
                'assigned_patients' => 0,
                'pending_medications' => 0,
                'vital_checks_due' => 0,
                'discharges_today' => 0
            ];
        }
    }

    private function getTodaysTasks($nurseId)
    {
        // Mock data - would need tasks table
        return [];
    }

    private function getAssignedPatients($nurseId)
    {
        // Mock data - would need nurse_patient_assignments table
        return [];
    }
}

