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
        
        // Get doctor's patients (from appointments)
        $appointments = $this->appointmentModel->where('doctor_id', $doctorId)->findAll();
        $patientIds = array_unique(array_column($appointments, 'patient_id'));
        
        // Get patient statistics
        $totalPatients = count($patientIds);
        $today = date('Y-m-d');
        $newPatientsToday = $this->appointmentModel
            ->where('doctor_id', $doctorId)
            ->where('appointment_date', $today)
            ->countAllResults();
        
        // Get all patients for the table
        $patients = [];
        if (!empty($patientIds)) {
            $patients = $this->patientModel->whereIn('id', $patientIds)->findAll();
        }

        // Get patient details for display
        $patientsWithDetails = [];
        foreach ($patients as $patient) {
            $patientsWithDetails[] = $patient;
        }

        $data = [
            'title' => 'Dashboard',
            'user' => [
                'name' => $this->session->get('user_name'),
                'email' => $this->session->get('user_email'),
                'role' => $this->session->get('user_role')
            ],
            'stats' => [
                'total_patients' => $totalPatients,
                'new_patients_today' => $newPatientsToday,
                'admitted_patients' => 0, // Placeholder
                'critical_patients' => 0  // Placeholder
            ],
            'patients' => $patientsWithDetails,
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

