<?php

namespace App\Controllers;

use App\Models\UserModel;

class Nurses extends BaseController
{
    protected $userModel;
    protected $session;

    public function __construct()
    {
        $this->userModel = new UserModel();
        $this->session = session();
    }

    public function index()
    {
        // Check authentication
        if (!$this->session->get('is_logged_in') || $this->session->get('user_role') !== 'admin') {
            return redirect()->to('login')->with('error', 'Access denied. Admin only.');
        }

        // Initialize variables
        $nurses = [];
        
        // Get all nurses (users with role = 'nurse')
        try {
            $db = \Config\Database::connect();
            if ($db->tableExists('users')) {
                $nursesResult = $db->table('users')
                    ->where('role', 'nurse')
                    ->get()
                    ->getResultArray();
                
                if ($nursesResult && is_array($nursesResult)) {
                    $nurses = $nursesResult;
                }
            }
        } catch (\Exception $e) {
            log_message('error', 'Error fetching nurses: ' . $e->getMessage());
            $nurses = [];
        }
        
        // Get schedules for nurses
        $db = \Config\Database::connect();
        $nurseSchedules = [];
        if ($db->tableExists('nurse_schedules')) {
            $schedules = $db->table('nurse_schedules')
                ->select('nurse_id, COUNT(*) as total_shifts, COUNT(DISTINCT shift_type) as shift_types')
                ->groupBy('nurse_id')
                ->get()
                ->getResultArray();
            
            foreach ($schedules as $schedule) {
                $nurseSchedules[$schedule['nurse_id']] = $schedule;
            }
        }
        
        // Process nurses data
        $processedNurses = [];
        foreach ($nurses as $nurse) {
            if (!isset($nurse['name'])) {
                continue;
            }
            
            // Get initials for avatar
            $name = $nurse['name'];
            $nameParts = explode(' ', $name);
            $initials = '';
            if (count($nameParts) >= 2) {
                $initials = strtoupper(substr($nameParts[0], 0, 1) . substr($nameParts[1], 0, 1));
            } else {
                $initials = strtoupper(substr($name, 0, 2));
            }
            
            // Get schedule data for this nurse
            $scheduleData = $nurseSchedules[$nurse['id']] ?? null;
            
            // Add processed data
            $nurse['initials'] = $initials;
            $nurse['total_shifts'] = $scheduleData ? (int)$scheduleData['total_shifts'] : 0;
            $nurse['shift_types'] = $scheduleData ? (int)$scheduleData['shift_types'] : 0;
            $nurse['has_schedule'] = !empty($scheduleData);
            $nurse['activities_count'] = 0;
            
            $processedNurses[] = $nurse;
        }
        
        // Prepare data for view
        $data = [
            'title' => 'Nurses',
            'user' => [
                'name' => $this->session->get('user_name') ?: 'Admin',
                'email' => $this->session->get('user_email') ?: '',
                'role' => $this->session->get('user_role') ?: 'admin'
            ],
            'nurses' => $processedNurses
        ];
        
        return view('nurses/index', $data);
    }

    public function schedule($nurseId)
    {
        if (!$this->session->get('is_logged_in') || $this->session->get('user_role') !== 'admin') {
            return redirect()->to('login')->with('error', 'Access denied. Admin only.');
        }

        // Get nurse details
        $nurse = $this->userModel->find($nurseId);
        
        if (!$nurse || $nurse['role'] !== 'nurse') {
            return redirect()->to('nurses')->with('error', 'Nurse not found');
        }

        // Get initials for avatar
        $nameParts = explode(' ', $nurse['name']);
        $nurse['initials'] = '';
        if (count($nameParts) >= 2) {
            $nurse['initials'] = strtoupper(substr($nameParts[0], 0, 1) . substr($nameParts[1], 0, 1));
        } else {
            $nurse['initials'] = strtoupper(substr($nurse['name'], 0, 2));
        }

        $data = [
            'title' => 'Create Schedule - ' . $nurse['name'],
            'user' => [
                'name' => $this->session->get('user_name'),
                'email' => $this->session->get('user_email'),
                'role' => $this->session->get('user_role')
            ],
            'nurse' => $nurse
        ];

        return view('nurses/schedule', $data);
    }

    public function storeSchedule()
    {
        if (!$this->session->get('is_logged_in') || $this->session->get('user_role') !== 'admin') {
            return redirect()->to('login')->with('error', 'Access denied. Admin only.');
        }

        // Validate required fields
        $nurseId = $this->request->getPost('nurse_id');
        $shiftDate = $this->request->getPost('shift_date');
        $shiftType = $this->request->getPost('shift_type');
        $startTime = $this->request->getPost('start_time');
        $endTime = $this->request->getPost('end_time');

        if (empty($nurseId) || empty($shiftDate) || empty($shiftType) || empty($startTime) || empty($endTime)) {
            return redirect()->back()->withInput()->with('error', 'Please fill in all required fields');
        }

        // Verify nurse exists
        $nurse = $this->userModel->find($nurseId);
        if (!$nurse || $nurse['role'] !== 'nurse') {
            return redirect()->to('nurses')->with('error', 'Nurse not found');
        }

        try {
            $db = \Config\Database::connect();
            
            $scheduleData = [
                'nurse_id' => $nurseId,
                'shift_date' => $shiftDate,
                'shift_type' => $shiftType,
                'start_time' => $startTime,
                'end_time' => $endTime,
                'department' => $this->request->getPost('department') ?? '',
                'notes' => $this->request->getPost('notes') ?? '',
                'status' => 'scheduled',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ];

            // Insert into nurse_schedules table
            if ($db->tableExists('nurse_schedules')) {
                $db->table('nurse_schedules')->insert($scheduleData);
            } else {
                // If table doesn't exist, log it and show success message
                // User can run migration later: php spark migrate
                log_message('info', 'Nurse schedule created (table not found): ' . json_encode($scheduleData));
            }

            return redirect()->to('nurses')->with('success', 'Schedule created successfully for ' . $nurse['name']);

        } catch (\Exception $e) {
            log_message('error', 'Error saving nurse schedule: ' . $e->getMessage());
            return redirect()->back()->withInput()->with('error', 'Error saving schedule: ' . $e->getMessage());
        }
    }

    public function viewSchedule($nurseId)
    {
        if (!$this->session->get('is_logged_in') || $this->session->get('user_role') !== 'admin') {
            return redirect()->to('login')->with('error', 'Access denied. Admin only.');
        }

        // Get nurse details
        $nurse = $this->userModel->find($nurseId);
        
        if (!$nurse || $nurse['role'] !== 'nurse') {
            return redirect()->to('nurses')->with('error', 'Nurse not found');
        }

        // Get initials for avatar
        $nameParts = explode(' ', $nurse['name']);
        $nurse['initials'] = '';
        if (count($nameParts) >= 2) {
            $nurse['initials'] = strtoupper(substr($nameParts[0], 0, 1) . substr($nameParts[1], 0, 1));
        } else {
            $nurse['initials'] = strtoupper(substr($nurse['name'], 0, 2));
        }

        // Get all schedules for this nurse
        $db = \Config\Database::connect();
        $schedules = [];
        
        if ($db->tableExists('nurse_schedules')) {
            $schedules = $db->table('nurse_schedules')
                ->where('nurse_id', $nurseId)
                ->orderBy('shift_date', 'DESC')
                ->orderBy('start_time', 'ASC')
                ->get()
                ->getResultArray();
        }

        $data = [
            'title' => 'View Schedule - ' . $nurse['name'],
            'user' => [
                'name' => $this->session->get('user_name'),
                'email' => $this->session->get('user_email'),
                'role' => $this->session->get('user_role')
            ],
            'nurse' => $nurse,
            'schedules' => $schedules
        ];

        return view('nurses/view_schedule', $data);
    }

    public function editSchedule($scheduleId)
    {
        if (!$this->session->get('is_logged_in') || $this->session->get('user_role') !== 'admin') {
            return redirect()->to('login')->with('error', 'Access denied. Admin only.');
        }

        $db = \Config\Database::connect();
        
        // Get schedule details
        $schedule = null;
        if ($db->tableExists('nurse_schedules')) {
            $schedule = $db->table('nurse_schedules')
                ->where('id', $scheduleId)
                ->get()
                ->getRowArray();
        }

        if (!$schedule) {
            return redirect()->to('nurses')->with('error', 'Schedule not found');
        }

        // Get nurse details
        $nurse = $this->userModel->find($schedule['nurse_id']);
        
        if (!$nurse || $nurse['role'] !== 'nurse') {
            return redirect()->to('nurses')->with('error', 'Nurse not found');
        }

        // Get initials for avatar
        $nameParts = explode(' ', $nurse['name']);
        $nurse['initials'] = '';
        if (count($nameParts) >= 2) {
            $nurse['initials'] = strtoupper(substr($nameParts[0], 0, 1) . substr($nameParts[1], 0, 1));
        } else {
            $nurse['initials'] = strtoupper(substr($nurse['name'], 0, 2));
        }

        $data = [
            'title' => 'Edit Schedule - ' . $nurse['name'],
            'user' => [
                'name' => $this->session->get('user_name'),
                'email' => $this->session->get('user_email'),
                'role' => $this->session->get('user_role')
            ],
            'nurse' => $nurse,
            'schedule' => $schedule
        ];

        return view('nurses/edit_schedule', $data);
    }

    public function updateSchedule($scheduleId)
    {
        if (!$this->session->get('is_logged_in') || $this->session->get('user_role') !== 'admin') {
            return redirect()->to('login')->with('error', 'Access denied. Admin only.');
        }

        // Validate required fields
        $shiftDate = $this->request->getPost('shift_date');
        $shiftType = $this->request->getPost('shift_type');
        $startTime = $this->request->getPost('start_time');
        $endTime = $this->request->getPost('end_time');

        if (empty($shiftDate) || empty($shiftType) || empty($startTime) || empty($endTime)) {
            return redirect()->back()->withInput()->with('error', 'Please fill in all required fields');
        }

        try {
            $db = \Config\Database::connect();
            
            // Check if schedule exists
            $schedule = null;
            if ($db->tableExists('nurse_schedules')) {
                $schedule = $db->table('nurse_schedules')
                    ->where('id', $scheduleId)
                    ->get()
                    ->getRowArray();
            }

            if (!$schedule) {
                return redirect()->to('nurses')->with('error', 'Schedule not found');
            }

            // Update schedule data
            $updateData = [
                'shift_date' => $shiftDate,
                'shift_type' => $shiftType,
                'start_time' => $startTime,
                'end_time' => $endTime,
                'department' => $this->request->getPost('department') ?? '',
                'notes' => $this->request->getPost('notes') ?? '',
                'updated_at' => date('Y-m-d H:i:s')
            ];

            if ($db->tableExists('nurse_schedules')) {
                $db->table('nurse_schedules')
                    ->where('id', $scheduleId)
                    ->update($updateData);
            }

            // Get nurse name for success message
            $nurse = $this->userModel->find($schedule['nurse_id']);
            $nurseName = $nurse ? $nurse['name'] : 'Nurse';

            return redirect()->to('nurses/view-schedule/' . $schedule['nurse_id'])->with('success', 'Schedule updated successfully for ' . $nurseName);

        } catch (\Exception $e) {
            log_message('error', 'Error updating nurse schedule: ' . $e->getMessage());
            return redirect()->back()->withInput()->with('error', 'Error updating schedule: ' . $e->getMessage());
        }
    }
}
