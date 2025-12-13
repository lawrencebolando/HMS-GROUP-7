<?php

namespace App\Controllers;

use App\Models\PatientModel;
use CodeIgniter\Database\BaseConnection;

class WalkIn extends BaseController
{
    protected $patientModel;
    protected $db;
    protected $session;

    public function __construct()
    {
        $this->patientModel = new PatientModel();
        $this->db = \Config\Database::connect();
        $this->session = session();
    }

    public function index()
    {
        if (!$this->session->get('is_logged_in') || $this->session->get('user_role') !== 'admin') {
            return redirect()->to('login')->with('error', 'Access denied. Admin only.');
        }

        // Check if walk_in_lab_requests table exists
        $tableExists = $this->db->tableExists('walk_in_lab_requests');
        
        // Get statistics
        $stats = $this->getWalkInStats($tableExists);
        
        // Get walk-in requests list
        $requests = $this->getWalkInRequests($tableExists);
        
        $data = [
            'title' => 'Walk In - Lab Tests',
            'user' => [
                'name' => $this->session->get('user_name'),
                'email' => $this->session->get('user_email'),
                'role' => $this->session->get('user_role')
            ],
            'stats' => $stats,
            'requests' => $requests
        ];
        
        return view('walkin/index', $data);
    }

    private function getWalkInStats($tableExists)
    {
        if (!$tableExists) {
            return [
                'total' => 0,
                'pending' => 0,
                'in_progress' => 0,
                'completed' => 0
            ];
        }

        try {
            $total = $this->db->table('walk_in_lab_requests')->countAllResults();
            $pending = $this->db->table('walk_in_lab_requests')
                ->where('status', 'pending')
                ->countAllResults();
            $inProgress = $this->db->table('walk_in_lab_requests')
                ->where('status', 'in_progress')
                ->countAllResults();
            $completed = $this->db->table('walk_in_lab_requests')
                ->where('status', 'completed')
                ->countAllResults();

            return [
                'total' => $total,
                'pending' => $pending,
                'in_progress' => $inProgress,
                'completed' => $completed
            ];
        } catch (\Exception $e) {
            return [
                'total' => 0,
                'pending' => 0,
                'in_progress' => 0,
                'completed' => 0
            ];
        }
    }

    private function getWalkInRequests($tableExists)
    {
        if (!$tableExists) {
            return [];
        }

        try {
            $requests = $this->db->table('walk_in_lab_requests')
                ->orderBy('request_date', 'DESC')
                ->orderBy('created_at', 'DESC')
                ->get()
                ->getResultArray();

            return $requests;
        } catch (\Exception $e) {
            return [];
        }
    }

    public function store()
    {
        if (!$this->session->get('is_logged_in') || $this->session->get('user_role') !== 'admin') {
            if ($this->request->isAJAX()) {
                return $this->response->setJSON(['success' => false, 'message' => 'Access denied']);
            }
            return redirect()->to('login')->with('error', 'Access denied. Admin only.');
        }

        // Check if table exists
        if (!$this->db->tableExists('walk_in_lab_requests')) {
            if ($this->request->isAJAX()) {
                return $this->response->setJSON(['success' => false, 'message' => 'Walk-in lab requests table does not exist. Please run migrations first.']);
            }
            return redirect()->back()->with('error', 'Walk-in lab requests table does not exist.');
        }

        // Validate input
        $validation = \Config\Services::validation();
        $validation->setRules([
            'patient_name' => 'required|min_length[2]|max_length[200]',
            'test_type' => 'required|max_length[200]',
            'priority' => 'permit_empty|in_list[low,normal,medium,high]',
            'notes' => 'permit_empty'
        ]);

        if (!$validation->run($this->request->getPost())) {
            if ($this->request->isAJAX()) {
                return $this->response->setJSON(['success' => false, 'message' => 'Validation failed: ' . implode(', ', $validation->getErrors())]);
            }
            return redirect()->back()->withInput()->with('errors', $validation->getErrors());
        }

        // Generate request ID
        $year = date('Y');
        $lastRequest = $this->db->table('walk_in_lab_requests')
            ->like('request_id', "WIL-{$year}-", 'after')
            ->orderBy('id', 'DESC')
            ->get(1)
            ->getRowArray();
        
        $sequence = 1;
        if ($lastRequest && preg_match('/WIL-' . $year . '-(\d+)/', $lastRequest['request_id'], $matches)) {
            $sequence = intval($matches[1]) + 1;
        }
        $requestId = 'WIL-' . $year . '-' . str_pad($sequence, 3, '0', STR_PAD_LEFT);

        // Prepare data
        $phone = $this->request->getPost('phone');
        $email = $this->request->getPost('email');
        $contact = $phone ?: $email;
        
        $data = [
            'request_id' => $requestId,
            'patient_name' => $this->request->getPost('patient_name'),
            'contact' => $contact,
            'phone' => $phone,
            'email' => $email,
            'test_type' => $this->request->getPost('test_type'),
            'priority' => $this->request->getPost('priority') ?? 'normal',
            'status' => 'pending',
            'request_date' => date('Y-m-d'),
            'request_time' => date('H:i:s'),
            'completed_date' => null,
            'notes' => $this->request->getPost('notes') ?? null,
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ];

        try {
            $this->db->table('walk_in_lab_requests')->insert($data);
            
            if ($this->request->isAJAX()) {
                return $this->response->setJSON(['success' => true, 'message' => 'Walk-in lab request created successfully']);
            }
            return redirect()->to('walk-in')->with('success', 'Walk-in lab request created successfully');
        } catch (\Exception $e) {
            if ($this->request->isAJAX()) {
                return $this->response->setJSON(['success' => false, 'message' => 'Error: ' . $e->getMessage()]);
            }
            return redirect()->back()->withInput()->with('error', 'Error creating request: ' . $e->getMessage());
        }
    }
}

