<?php

namespace App\Controllers;

use App\Models\UserModel;
use App\Models\PatientModel;
use App\Models\AppointmentModel;

class Doctor extends BaseController
{
    protected $userModel;
    protected $patientModel;
    protected $appointmentModel;
    protected $session;

    public function __construct()
    {
        $this->userModel = new UserModel();
        $this->patientModel = new PatientModel();
        $this->appointmentModel = new AppointmentModel();
        $this->session = session();
    }

    public function index()
    {
        return $this->dashboard();
    }

    public function dashboard()
    {
        // Check if user is logged in and is a doctor
        if (!$this->session->get('is_logged_in') || $this->session->get('user_role') !== 'doctor') {
            return redirect()->to('login')->with('error', 'Please login as doctor to continue');
        }

        $doctorId = $this->session->get('user_id');
        $today = date('Y-m-d');
        $yesterday = date('Y-m-d', strtotime('-1 day'));
        $lastMonth = date('Y-m', strtotime('-1 month'));
        $currentMonth = date('Y-m');
        
        // Get today's appointments
        $todayAppointments = $this->appointmentModel
            ->where('doctor_id', $doctorId)
            ->where('appointment_date', $today)
            ->findAll();
        
        // Get yesterday's appointments count
        $yesterdayAppointments = $this->appointmentModel
            ->where('doctor_id', $doctorId)
            ->where('appointment_date', $yesterday)
            ->countAllResults();
        
        $appointmentsChange = count($todayAppointments) - $yesterdayAppointments;
        
        // Get total patients
        $appointments = $this->appointmentModel->where('doctor_id', $doctorId)->findAll();
        $patientIds = array_unique(array_column($appointments, 'patient_id'));
        $totalPatients = count($patientIds);
        
        // Get pending reports (mock - would need reports table)
        $pendingReports = 0;
        $pendingReportsChange = -1; // Mock change
        
        // Get revenue this month (mock - would need billing/invoices table)
        $revenueThisMonth = 0.00;
        $revenueChange = 8; // Mock percentage change
        
        // Get recent appointments (past week)
        $weekAgo = date('Y-m-d', strtotime('-7 days'));
        $recentAppointments = $this->appointmentModel
            ->where('doctor_id', $doctorId)
            ->where('appointment_date >=', $weekAgo)
            ->where('appointment_date <=', $today)
            ->orderBy('appointment_date', 'DESC')
            ->orderBy('appointment_time', 'DESC')
            ->limit(10)
            ->findAll();
        
        // Enrich appointments with patient data
        $todayAppointmentsWithPatients = [];
        foreach ($todayAppointments as $appt) {
            $patient = $this->patientModel->find($appt['patient_id']);
            $appt['patient_name'] = $patient ? ($patient['first_name'] . ' ' . $patient['last_name']) : 'Unknown';
            $todayAppointmentsWithPatients[] = $appt;
        }
        
        $recentAppointmentsWithPatients = [];
        foreach ($recentAppointments as $appt) {
            $patient = $this->patientModel->find($appt['patient_id']);
            $appt['patient_name'] = $patient ? ($patient['first_name'] . ' ' . $patient['last_name']) : 'Unknown';
            $recentAppointmentsWithPatients[] = $appt;
        }

        $data = [
            'title' => 'Dashboard',
            'user' => [
                'name' => $this->session->get('user_name'),
                'email' => $this->session->get('user_email'),
                'role' => $this->session->get('user_role')
            ],
            'stats' => [
                'today_appointments' => count($todayAppointments),
                'appointments_change' => $appointmentsChange,
                'total_patients' => $totalPatients,
                'pending_reports' => $pendingReports,
                'pending_reports_change' => $pendingReportsChange,
                'revenue_this_month' => $revenueThisMonth,
                'revenue_change' => $revenueChange
            ],
            'today_appointments' => $todayAppointmentsWithPatients,
            'recent_appointments' => $recentAppointmentsWithPatients,
            'doctor_name' => $this->session->get('user_name')
        ];

        return view('doctor/dashboard', $data);
    }

    public function patients()
    {
        if (!$this->session->get('is_logged_in') || $this->session->get('user_role') !== 'doctor') {
            return redirect()->to('login')->with('error', 'Please login as doctor to continue');
        }

        $doctorId = $this->session->get('user_id');
        $appointments = $this->appointmentModel->where('doctor_id', $doctorId)->findAll();
        $patientIds = array_unique(array_column($appointments, 'patient_id'));
        
        $patients = [];
        if (!empty($patientIds)) {
            $patients = $this->patientModel->whereIn('id', $patientIds)->findAll();
        }

        $data = [
            'title' => 'Patient Records',
            'user' => [
                'name' => $this->session->get('user_name'),
                'email' => $this->session->get('user_email'),
                'role' => $this->session->get('user_role')
            ],
            'patients' => $patients,
            'doctor_name' => $this->session->get('user_name')
        ];

        return view('doctor/patients', $data);
    }

    public function appointments()
    {
        if (!$this->session->get('is_logged_in') || $this->session->get('user_role') !== 'doctor') {
            return redirect()->to('login')->with('error', 'Please login as doctor to continue');
        }

        $doctorId = $this->session->get('user_id');
        $today = date('Y-m-d');
        
        $todayAppointments = $this->appointmentModel
            ->where('doctor_id', $doctorId)
            ->where('appointment_date', $today)
            ->findAll();
        
        $upcomingAppointments = $this->appointmentModel
            ->where('doctor_id', $doctorId)
            ->where('appointment_date >=', $today)
            ->orderBy('appointment_date', 'ASC')
            ->orderBy('appointment_time', 'ASC')
            ->findAll();

        // Get patient details for appointments
        $todayAppointmentsWithPatients = [];
        foreach ($todayAppointments as $appt) {
            $patient = $this->patientModel->find($appt['patient_id']);
            $appt['patient'] = $patient;
            $todayAppointmentsWithPatients[] = $appt;
        }
        
        $upcomingAppointmentsWithPatients = [];
        foreach ($upcomingAppointments as $appt) {
            $patient = $this->patientModel->find($appt['patient_id']);
            $appt['patient'] = $patient;
            $upcomingAppointmentsWithPatients[] = $appt;
        }

        $data = [
            'title' => 'Appointments',
            'user' => [
                'name' => $this->session->get('user_name'),
                'email' => $this->session->get('user_email'),
                'role' => $this->session->get('user_role')
            ],
            'today_appointments' => $todayAppointmentsWithPatients,
            'upcoming_appointments' => $upcomingAppointmentsWithPatients,
            'doctor_name' => $this->session->get('user_name')
        ];

        return view('doctor/appointments', $data);
    }

    public function prescriptions()
    {
        if (!$this->session->get('is_logged_in') || $this->session->get('user_role') !== 'doctor') {
            return redirect()->to('login')->with('error', 'Please login as doctor to continue');
        }

        $doctorId = $this->session->get('user_id');
        $db = \Config\Database::connect();
        
        // Get patients from appointments
        $appointments = $this->appointmentModel->where('doctor_id', $doctorId)->findAll();
        $patientIds = array_unique(array_column($appointments, 'patient_id'));
        
        $patients = [];
        if (!empty($patientIds)) {
            $patients = $this->patientModel->whereIn('id', $patientIds)->findAll();
        }

        // Get saved prescriptions
        $prescriptions = [];
        if ($db->tableExists('prescriptions')) {
            $prescriptions = $db->table('prescriptions')
                ->where('doctor_id', $doctorId)
                ->orderBy('prescribed_date', 'DESC')
                ->orderBy('created_at', 'DESC')
                ->get()
                ->getResultArray();

            // Enrich prescriptions with patient and medication data
            foreach ($prescriptions as &$prescription) {
                $patient = $this->patientModel->find($prescription['patient_id']);
                $prescription['patient_name'] = $patient ? ($patient['first_name'] . ' ' . $patient['last_name']) : 'Unknown';
                
                // Get medications for this prescription
                if ($db->tableExists('prescription_medications')) {
                    $medications = $db->table('prescription_medications')
                        ->where('prescription_id', $prescription['id'])
                        ->get()
                        ->getResultArray();
                    $prescription['medications'] = $medications;
                } else {
                    $prescription['medications'] = [];
                }
            }
        }

        $data = [
            'title' => 'Prescriptions',
            'user' => [
                'name' => $this->session->get('user_name'),
                'email' => $this->session->get('user_email'),
                'role' => $this->session->get('user_role')
            ],
            'patients' => $patients,
            'prescriptions' => $prescriptions,
            'doctor_name' => $this->session->get('user_name')
        ];

        return view('doctor/prescriptions', $data);
    }

    public function storePrescription()
    {
        if (!$this->session->get('is_logged_in') || $this->session->get('user_role') !== 'doctor') {
            return redirect()->to('login')->with('error', 'Please login as doctor to continue');
        }

        $doctorId = $this->session->get('user_id');
        $db = \Config\Database::connect();

        // Validate required fields
        $patientId = $this->request->getPost('patient_id');
        $diagnosis = $this->request->getPost('diagnosis');
        $medications = $this->request->getPost('medications'); // Array of medications

        if (empty($patientId) || empty($diagnosis)) {
            return redirect()->back()->with('error', 'Patient and diagnosis are required');
        }

        try {
            $db->transStart();

            // Generate prescription ID
            do {
                $prescriptionId = 'RX-' . date('Ymd') . '-' . str_pad(rand(1, 9999), 4, '0', STR_PAD_LEFT);
                $exists = $db->table('prescriptions')->where('prescription_id', $prescriptionId)->get()->getRow();
            } while ($exists);

            // Insert prescription
            $prescriptionData = [
                'prescription_id' => $prescriptionId,
                'patient_id' => $patientId,
                'doctor_id' => $doctorId,
                'diagnosis' => $diagnosis,
                'notes' => $this->request->getPost('notes') ?? '',
                'status' => 'active',
                'prescribed_date' => date('Y-m-d'),
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ];

            $db->table('prescriptions')->insert($prescriptionData);
            $prescriptionDbId = $db->insertID();

            // Insert medications
            if (!empty($medications) && is_array($medications) && $db->tableExists('prescription_medications')) {
                foreach ($medications as $med) {
                    if (!empty($med['medication_name'])) {
                        $medData = [
                            'prescription_id' => $prescriptionDbId,
                            'medication_name' => $med['medication_name'] ?? '',
                            'dosage' => $med['dosage'] ?? '',
                            'frequency' => $med['frequency'] ?? '',
                            'meal_instruction' => $med['meal_instruction'] ?? '',
                            'duration' => $med['duration'] ?? '',
                            'quantity' => $med['quantity'] ?? 1,
                            'notes' => $med['medication_notes'] ?? '',
                            'created_at' => date('Y-m-d H:i:s'),
                            'updated_at' => date('Y-m-d H:i:s')
                        ];
                        $db->table('prescription_medications')->insert($medData);
                    }
                }
            }

            $db->transComplete();

            if ($db->transStatus() === false) {
                return redirect()->back()->with('error', 'Failed to save prescription');
            }

            return redirect()->to('doctor/prescriptions')->with('success', 'Prescription saved successfully');

        } catch (\Exception $e) {
            log_message('error', 'Error saving prescription: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Error saving prescription: ' . $e->getMessage());
        }
    }

    public function viewPrescription($prescriptionId)
    {
        if (!$this->session->get('is_logged_in') || $this->session->get('user_role') !== 'doctor') {
            return redirect()->to('login')->with('error', 'Please login as doctor to continue');
        }

        $doctorId = $this->session->get('user_id');
        $db = \Config\Database::connect();

        // Get prescription
        $prescription = $db->table('prescriptions')
            ->where('id', $prescriptionId)
            ->where('doctor_id', $doctorId)
            ->get()
            ->getRowArray();

        if (!$prescription) {
            return redirect()->to('doctor/prescriptions')->with('error', 'Prescription not found');
        }

        // Get patient details
        $patient = $this->patientModel->find($prescription['patient_id']);

        // Get medications
        $medications = [];
        if ($db->tableExists('prescription_medications')) {
            $medications = $db->table('prescription_medications')
                ->where('prescription_id', $prescriptionId)
                ->get()
                ->getResultArray();
        }

        $data = [
            'title' => 'View Prescription',
            'user' => [
                'name' => $this->session->get('user_name'),
                'email' => $this->session->get('user_email'),
                'role' => $this->session->get('user_role')
            ],
            'prescription' => $prescription,
            'patient' => $patient,
            'medications' => $medications,
            'doctor_name' => $this->session->get('user_name')
        ];

        return view('doctor/view_prescription', $data);
    }

    public function schedule()
    {
        if (!$this->session->get('is_logged_in') || $this->session->get('user_role') !== 'doctor') {
            return redirect()->to('login')->with('error', 'Please login as doctor to continue');
        }

        $doctorId = $this->session->get('user_id');
        
        // Get month and year from query params, default to current month
        $month = $this->request->getGet('month') ?: date('m');
        $year = $this->request->getGet('year') ?: date('Y');
        
        // Validate month and year
        $month = max(1, min(12, (int)$month));
        $year = max(2020, min(2100, (int)$year));
        
        // Get appointments for the selected month
        $startDate = "$year-$month-01";
        $endDate = date('Y-m-t', strtotime($startDate));
        
        $appointments = $this->appointmentModel
            ->where('doctor_id', $doctorId)
            ->where('appointment_date >=', $startDate)
            ->where('appointment_date <=', $endDate)
            ->findAll();
        
        // Organize appointments by date with patient details
        $appointmentsByDate = [];
        foreach ($appointments as $appt) {
            $date = $appt['appointment_date'];
            if (!isset($appointmentsByDate[$date])) {
                $appointmentsByDate[$date] = [];
            }
            // Get patient details
            $patient = $this->patientModel->find($appt['patient_id']);
            $appt['patient'] = $patient;
            $appointmentsByDate[$date][] = $appt;
        }

        $data = [
            'title' => 'My Schedule',
            'user' => [
                'name' => $this->session->get('user_name'),
                'email' => $this->session->get('user_email'),
                'role' => $this->session->get('user_role')
            ],
            'doctor_name' => $this->session->get('user_name'),
            'current_month' => $month,
            'current_year' => $year,
            'appointments_by_date' => $appointmentsByDate
        ];

        return view('doctor/schedule', $data);
    }

    public function addSchedule()
    {
        if (!$this->session->get('is_logged_in') || $this->session->get('user_role') !== 'doctor') {
            return redirect()->to('login')->with('error', 'Please login as doctor to continue');
        }

        // For now, just redirect back with success message
        // In a real implementation, you would save schedule data to a schedules table
        $month = $this->request->getPost('schedule_date') ? date('m', strtotime($this->request->getPost('schedule_date'))) : date('m');
        $year = $this->request->getPost('schedule_date') ? date('Y', strtotime($this->request->getPost('schedule_date'))) : date('Y');
        
        return redirect()->to("doctor/schedule?month=$month&year=$year")->with('success', 'Schedule added successfully!');
    }

    public function updateSchedule()
    {
        if (!$this->session->get('is_logged_in') || $this->session->get('user_role') !== 'doctor') {
            return redirect()->to('login')->with('error', 'Please login as doctor to continue');
        }

        // For now, just redirect back with success message
        // In a real implementation, you would update schedule settings
        $month = $this->request->getGet('month') ?: date('m');
        $year = $this->request->getGet('year') ?: date('Y');
        
        return redirect()->to("doctor/schedule?month=$month&year=$year")->with('success', 'Schedule settings updated successfully!');
    }

    public function consultations()
    {
        if (!$this->session->get('is_logged_in') || $this->session->get('user_role') !== 'doctor') {
            return redirect()->to('login')->with('error', 'Please login as doctor to continue');
        }

        $doctorId = $this->session->get('user_id');
        $consultations = $this->appointmentModel
            ->where('doctor_id', $doctorId)
            ->where('status', 'completed')
            ->orderBy('appointment_date', 'DESC')
            ->orderBy('appointment_time', 'DESC')
            ->findAll();
        
        // Get patient details for consultations
        $consultationsWithPatients = [];
        foreach ($consultations as $consultation) {
            $patient = $this->patientModel->find($consultation['patient_id']);
            $consultation['patient'] = $patient;
            $consultationsWithPatients[] = $consultation;
        }

        $totalConsultations = count($consultations);
        $thisMonth = date('Y-m');
        $thisWeek = date('Y-W');
        
        $monthConsultations = 0;
        $weekConsultations = 0;
        
        foreach ($consultations as $consultation) {
            $apptDate = date('Y-m', strtotime($consultation['appointment_date']));
            if ($apptDate === $thisMonth) {
                $monthConsultations++;
            }
            
            $apptWeek = date('Y-W', strtotime($consultation['appointment_date']));
            if ($apptWeek === $thisWeek) {
                $weekConsultations++;
            }
        }

        $data = [
            'title' => 'Consultations',
            'user' => [
                'name' => $this->session->get('user_name'),
                'email' => $this->session->get('user_email'),
                'role' => $this->session->get('user_role')
            ],
            'consultations' => $consultationsWithPatients,
            'stats' => [
                'total' => $totalConsultations,
                'this_month' => $monthConsultations,
                'this_week' => $weekConsultations
            ],
            'doctor_name' => $this->session->get('user_name')
        ];

        return view('doctor/consultations', $data);
    }

    public function inpatients()
    {
        if (!$this->session->get('is_logged_in') || $this->session->get('user_role') !== 'doctor') {
            return redirect()->to('login')->with('error', 'Please login as doctor to continue');
        }

        $doctorId = $this->session->get('user_id');
        $db = \Config\Database::connect();
        
        // Get inpatients (admissions) for this doctor
        $inpatients = [];
        if ($db->tableExists('admissions')) {
            $inpatients = $db->table('admissions')
                ->where('doctor_id', $doctorId)
                ->where('status', 'active')
                ->orderBy('admission_date', 'DESC')
                ->get()
                ->getResultArray();
            
            // Enrich with patient data
            foreach ($inpatients as &$inpatient) {
                $patient = $this->patientModel->find($inpatient['patient_id']);
                $inpatient['patient_name'] = $patient ? ($patient['first_name'] . ' ' . $patient['last_name']) : 'Unknown';
            }
        }

        $data = [
            'title' => 'Inpatients',
            'user' => [
                'name' => $this->session->get('user_name'),
                'email' => $this->session->get('user_email'),
                'role' => $this->session->get('user_role')
            ],
            'inpatients' => $inpatients,
            'doctor_name' => $this->session->get('user_name')
        ];

        return view('doctor/inpatients', $data);
    }

    public function labs()
    {
        if (!$this->session->get('is_logged_in') || $this->session->get('user_role') !== 'doctor') {
            return redirect()->to('login')->with('error', 'Please login as doctor to continue');
        }

        $doctorId = $this->session->get('user_id');
        $db = \Config\Database::connect();
        
        // Get patients from appointments
        $appointments = $this->appointmentModel->where('doctor_id', $doctorId)->findAll();
        $patientIds = array_unique(array_column($appointments, 'patient_id'));
        
        $patients = [];
        if (!empty($patientIds)) {
            $patients = $this->patientModel->whereIn('id', $patientIds)->findAll();
        }

        // Get saved lab requests
        $labRequests = [];
        if ($db->tableExists('lab_requests')) {
            $labRequests = $db->table('lab_requests')
                ->where('doctor_id', $doctorId)
                ->orderBy('requested_date', 'DESC')
                ->orderBy('created_at', 'DESC')
                ->get()
                ->getResultArray();

            // Enrich lab requests with patient data
            foreach ($labRequests as &$request) {
                $patient = $this->patientModel->find($request['patient_id']);
                $request['patient_name'] = $patient ? ($patient['first_name'] . ' ' . $patient['last_name']) : 'Unknown';
            }
        }

        $data = [
            'title' => 'Lab Requests',
            'user' => [
                'name' => $this->session->get('user_name'),
                'email' => $this->session->get('user_email'),
                'role' => $this->session->get('user_role')
            ],
            'patients' => $patients,
            'lab_requests' => $labRequests,
            'doctor_name' => $this->session->get('user_name')
        ];

        return view('doctor/labs', $data);
    }

    public function storeLabRequest()
    {
        if (!$this->session->get('is_logged_in') || $this->session->get('user_role') !== 'doctor') {
            return redirect()->to('login')->with('error', 'Please login as doctor to continue');
        }

        $doctorId = $this->session->get('user_id');
        $db = \Config\Database::connect();

        // Validate required fields
        $patientId = $this->request->getPost('patient_id');
        $testType = $this->request->getPost('test_type');
        $priority = $this->request->getPost('priority') ?? 'normal';

        if (empty($patientId) || empty($testType)) {
            return redirect()->back()->with('error', 'Patient and test type are required');
        }

        try {
            // Generate lab request ID
            do {
                $labRequestId = 'LAB-' . date('Ymd') . '-' . str_pad(rand(1, 9999), 4, '0', STR_PAD_LEFT);
                $exists = $db->table('lab_requests')->where('lab_request_id', $labRequestId)->get()->getRow();
            } while ($exists);

            // Insert lab request
            $labRequestData = [
                'lab_request_id' => $labRequestId,
                'patient_id' => $patientId,
                'doctor_id' => $doctorId,
                'test_type' => $testType,
                'priority' => strtolower($priority),
                'notes' => $this->request->getPost('notes') ?? '',
                'status' => 'pending',
                'requested_date' => date('Y-m-d'),
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ];

            $db->table('lab_requests')->insert($labRequestData);

            return redirect()->to('doctor/labs')->with('success', 'Lab test request submitted successfully');

        } catch (\Exception $e) {
            log_message('error', 'Error saving lab request: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Error submitting lab request: ' . $e->getMessage());
        }
    }

    public function viewLabRequest($requestId)
    {
        if (!$this->session->get('is_logged_in') || $this->session->get('user_role') !== 'doctor') {
            return redirect()->to('login')->with('error', 'Please login as doctor to continue');
        }

        $doctorId = $this->session->get('user_id');
        $db = \Config\Database::connect();

        // Get lab request
        $labRequest = $db->table('lab_requests')
            ->where('id', $requestId)
            ->where('doctor_id', $doctorId)
            ->get()
            ->getRowArray();

        if (!$labRequest) {
            return redirect()->to('doctor/labs')->with('error', 'Lab request not found');
        }

        // Get patient details
        $patient = $this->patientModel->find($labRequest['patient_id']);

        $data = [
            'title' => 'View Lab Request',
            'user' => [
                'name' => $this->session->get('user_name'),
                'email' => $this->session->get('user_email'),
                'role' => $this->session->get('user_role')
            ],
            'lab_request' => $labRequest,
            'patient' => $patient,
            'doctor_name' => $this->session->get('user_name')
        ];

        return view('doctor/view_lab_request', $data);
    }

    public function settings()
    {
        if (!$this->session->get('is_logged_in') || $this->session->get('user_role') !== 'doctor') {
            return redirect()->to('login')->with('error', 'Please login as doctor to continue');
        }

        $data = [
            'title' => 'Settings',
            'user' => [
                'name' => $this->session->get('user_name'),
                'email' => $this->session->get('user_email'),
                'role' => $this->session->get('user_role')
            ],
            'doctor_name' => $this->session->get('user_name')
        ];

        return view('doctor/settings', $data);
    }

    public function reports()
    {
        if (!$this->session->get('is_logged_in') || $this->session->get('user_role') !== 'doctor') {
            return redirect()->to('login')->with('error', 'Please login as doctor to continue');
        }

        $doctorId = $this->session->get('user_id');
        $db = \Config\Database::connect();

        // Get filter parameters
        $reportType = $this->request->getGet('report_type') ?? 'appointments';
        $dateFrom = $this->request->getGet('date_from') ?? date('Y-m-01');
        $dateTo = $this->request->getGet('date_to') ?? date('Y-m-d');

        // Get all appointments for this doctor (for statistics)
        $allAppointments = $this->appointmentModel
            ->where('doctor_id', $doctorId)
            ->findAll();

        // Get filtered appointments based on date range
        $appointments = $this->appointmentModel
            ->where('doctor_id', $doctorId)
            ->where('appointment_date >=', $dateFrom)
            ->where('appointment_date <=', $dateTo)
            ->orderBy('appointment_date', 'DESC')
            ->orderBy('appointment_time', 'DESC')
            ->findAll();
        
        // Get patient details for appointments
        $appointmentsWithPatients = [];
        foreach ($appointments as $appt) {
            $patient = $this->patientModel->find($appt['patient_id']);
            $appt['patient'] = $patient;
            $appointmentsWithPatients[] = $appt;
        }

        // Calculate statistics
        $totalAppointments = count($appointments);
        
        // Get total prescriptions
        $totalPrescriptions = 0;
        if ($db->tableExists('prescriptions')) {
            $totalPrescriptions = $db->table('prescriptions')
                ->where('doctor_id', $doctorId)
                ->where('prescribed_date >=', $dateFrom)
                ->where('prescribed_date <=', $dateTo)
                ->countAllResults();
        }

        // Get total lab requests
        $totalLabRequests = 0;
        if ($db->tableExists('lab_requests')) {
            $totalLabRequests = $db->table('lab_requests')
                ->where('doctor_id', $doctorId)
                ->where('requested_date >=', $dateFrom)
                ->where('requested_date <=', $dateTo)
                ->countAllResults();
        }

        // Get unique patients count
        $patientIds = array_unique(array_column($appointments, 'patient_id'));
        $totalPatients = count($patientIds);

        $data = [
            'title' => 'Medical Reports',
            'user' => [
                'name' => $this->session->get('user_name'),
                'email' => $this->session->get('user_email'),
                'role' => $this->session->get('user_role')
            ],
            'appointments' => $appointmentsWithPatients,
            'report_type' => $reportType,
            'date_from' => $dateFrom,
            'date_to' => $dateTo,
            'stats' => [
                'total_appointments' => $totalAppointments,
                'total_prescriptions' => $totalPrescriptions,
                'total_lab_requests' => $totalLabRequests,
                'total_patients' => $totalPatients
            ],
            'doctor_name' => $this->session->get('user_name')
        ];

        return view('doctor/reports', $data);
    }

    public function viewPatient($patientId)
    {
        // Check if user is logged in and is a doctor
        if (!$this->session->get('is_logged_in') || $this->session->get('user_role') !== 'doctor') {
            return redirect()->to('login')->with('error', 'Please login as doctor to continue');
        }

        // Get patient details
        $patient = $this->patientModel->find($patientId);
        
        if (!$patient) {
            return redirect()->back()->with('error', 'Patient not found');
        }

        // Get patient's appointments with this doctor
        $doctorId = $this->session->get('user_id');
        $appointments = $this->appointmentModel
            ->where('patient_id', $patientId)
            ->where('doctor_id', $doctorId)
            ->orderBy('appointment_date', 'DESC')
            ->orderBy('appointment_time', 'DESC')
            ->findAll();

        // Get patient's medical history (if available)
        $medicalHistory = [];

        $data = [
            'title' => 'Patient Details',
            'user' => [
                'name' => $this->session->get('user_name'),
                'email' => $this->session->get('user_email'),
                'role' => $this->session->get('user_role')
            ],
            'patient' => $patient,
            'appointments' => $appointments,
            'medical_history' => $medicalHistory
        ];

        return view('doctor/view_patient', $data);
    }
}

