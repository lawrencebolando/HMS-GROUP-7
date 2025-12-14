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
        $appointments = $this->appointmentModel->where('doctor_id', $doctorId)->findAll();
        $patientIds = array_unique(array_column($appointments, 'patient_id'));
        
        $patients = [];
        if (!empty($patientIds)) {
            $patients = $this->patientModel->whereIn('id', $patientIds)->findAll();
        }

        $data = [
            'title' => 'Prescriptions',
            'user' => [
                'name' => $this->session->get('user_name'),
                'email' => $this->session->get('user_email'),
                'role' => $this->session->get('user_role')
            ],
            'patients' => $patients,
            'doctor_name' => $this->session->get('user_name')
        ];

        return view('doctor/prescriptions', $data);
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
        $appointments = $this->appointmentModel->where('doctor_id', $doctorId)->findAll();
        $patientIds = array_unique(array_column($appointments, 'patient_id'));
        
        $patients = [];
        if (!empty($patientIds)) {
            $patients = $this->patientModel->whereIn('id', $patientIds)->findAll();
        }

        $data = [
            'title' => 'Lab Requests',
            'user' => [
                'name' => $this->session->get('user_name'),
                'email' => $this->session->get('user_email'),
                'role' => $this->session->get('user_role')
            ],
            'patients' => $patients,
            'doctor_name' => $this->session->get('user_name')
        ];

        return view('doctor/labs', $data);
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
        $appointments = $this->appointmentModel
            ->where('doctor_id', $doctorId)
            ->orderBy('appointment_date', 'DESC')
            ->findAll();
        
        // Get patient details for reports
        $appointmentsWithPatients = [];
        foreach ($appointments as $appt) {
            $patient = $this->patientModel->find($appt['patient_id']);
            $appt['patient'] = $patient;
            $appointmentsWithPatients[] = $appt;
        }

        $data = [
            'title' => 'Medical Reports',
            'user' => [
                'name' => $this->session->get('user_name'),
                'email' => $this->session->get('user_email'),
                'role' => $this->session->get('user_role')
            ],
            'appointments' => $appointmentsWithPatients,
            'doctor_name' => $this->session->get('user_name')
        ];

        return view('doctor/reports', $data);
    }
}

