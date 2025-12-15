<?php

namespace App\Controllers;

use App\Models\AppointmentModel;
use App\Models\PatientModel;
use App\Models\UserModel;
use App\Models\DepartmentModel;
use App\Models\DoctorModel;

class Appointments extends BaseController
{
    protected $appointmentModel;
    protected $patientModel;
    protected $userModel;
    protected $deptModel;
    protected $doctorModel;
    protected $session;

    public function __construct()
    {
        $this->appointmentModel = new AppointmentModel();
        $this->patientModel = new PatientModel();
        $this->userModel = new UserModel();
        $this->deptModel = new DepartmentModel();
        $this->doctorModel = new DoctorModel();
        $this->session = session();
    }

    public function index()
    {
        // Allow both admin and receptionist to view appointments
        if (!$this->session->get('is_logged_in') || !in_array($this->session->get('user_role'), ['admin', 'receptionist'])) {
            return redirect()->to('login')->with('error', 'Please login to continue');
        }

        // Get filter date or use today
        $filterDate = $this->request->getGet('date') ?: date('Y-m-d');
        
        // Normalize date format to ensure consistency
        $dateObj = \DateTime::createFromFormat('Y-m-d', $filterDate);
        if ($dateObj) {
            $filterDate = $dateObj->format('Y-m-d');
        } else {
            $filterDate = date('Y-m-d');
        }
        
        // Get appointments for the selected date
        // Use direct database query to ensure fresh data
        $db = \Config\Database::connect();
        $builder = $db->table('appointments');
        $appointmentsRaw = $builder
            ->where('appointment_date', $filterDate)
            ->orderBy('appointment_time', 'ASC')
            ->get()
            ->getResultArray();
        
        // Convert to array format expected by view
        $appointments = [];
        foreach ($appointmentsRaw as $apt) {
            $appointments[] = $apt;
        }
        
        // Debug: Log appointment count and query
        log_message('debug', 'Appointments query for date ' . $filterDate . ': ' . $builder->getCompiledSelect(false));
        log_message('debug', 'Appointments found: ' . count($appointments));

        // Get related data for appointments
        foreach ($appointments as &$apt) {
            $patient = $this->patientModel->find($apt['patient_id']);
            // Try to get doctor from doctors table first, fallback to users table for backward compatibility
            $doctor = $this->doctorModel->find($apt['doctor_id']);
            if (!$doctor) {
                $doctor = $this->userModel->find($apt['doctor_id']);
                $apt['doctor_name'] = $doctor ? $doctor['name'] : 'Unknown';
            } else {
                $apt['doctor_name'] = $doctor['full_name'] ?? 'Unknown';
            }
            $apt['patient_name'] = $patient ? ($patient['first_name'] . ' ' . $patient['last_name']) : 'Unknown';
            $apt['patient_id_display'] = $patient ? $patient['patient_id'] : 'N/A';
        }

        // Get doctors from users table (appointments use doctor_id from users table)
        $doctors = $this->userModel->where('role', 'doctor')->where('status', 'active')->findAll();
        
        // Format doctors for the view (ensure they have both 'name' and 'full_name' for compatibility)
        $formattedDoctors = [];
        foreach ($doctors as $doctor) {
            $formattedDoctors[] = [
                'id' => $doctor['id'],
                'name' => $doctor['name'],
                'full_name' => $doctor['name'], // For compatibility with view
                'email' => $doctor['email'] ?? '',
                'status' => $doctor['status']
            ];
        }

        $data = [
            'title' => 'Appointment Scheduling',
            'user' => [
                'name' => $this->session->get('user_name'),
                'role' => $this->session->get('user_role')
            ],
            'appointments' => $appointments,
            'filter_date' => $filterDate,
            'patients' => $this->patientModel->findAll(),
            'doctors' => $formattedDoctors,
            'departments' => $this->deptModel->where('status', 'active')->findAll()
        ];

        return view('appointments/index', $data);
    }

    public function store()
    {
        // Only receptionist can create appointments
        if (!$this->session->get('is_logged_in') || $this->session->get('user_role') !== 'receptionist') {
            return redirect()->to('login')->with('error', 'Access denied. Receptionist only.');
        }

        // Validate required fields
        $validation = \Config\Services::validation();
        $validation->setRules([
            'patient_id' => 'required|integer',
            'doctor_id' => 'required|integer',
            'appointment_date' => 'required|valid_date',
            'appointment_time' => 'required',
        ]);

        if (!$validation->run($this->request->getPost())) {
            return redirect()->back()->withInput()->with('errors', $validation->getErrors());
        }

        $data = [
            'patient_id' => $this->request->getPost('patient_id'),
            'doctor_id' => $this->request->getPost('doctor_id'),
            'department_id' => $this->request->getPost('department_id') ?: null,
            'appointment_date' => $this->request->getPost('appointment_date'),
            'appointment_time' => $this->request->getPost('appointment_time'),
            'reason' => trim($this->request->getPost('reason')) ?: null,
            'notes' => trim($this->request->getPost('notes')) ?: null,
            'status' => 'scheduled'
        ];

        try {
            // Ensure date is in correct format
            $appointmentDate = $this->request->getPost('appointment_date');
            if ($appointmentDate) {
                // Normalize date format to Y-m-d
                $dateObj = \DateTime::createFromFormat('Y-m-d', $appointmentDate);
                if ($dateObj) {
                    $data['appointment_date'] = $dateObj->format('Y-m-d');
                }
            }
            
            // Ensure time is in correct format
            $appointmentTime = $this->request->getPost('appointment_time');
            if ($appointmentTime) {
                // Normalize time format to H:i:s
                $timeObj = \DateTime::createFromFormat('H:i:s', $appointmentTime);
                if (!$timeObj) {
                    $timeObj = \DateTime::createFromFormat('H:i', $appointmentTime);
                }
                if ($timeObj) {
                    $data['appointment_time'] = $timeObj->format('H:i:s');
                }
            }
            
            // Log the data being inserted
            log_message('debug', 'Attempting to insert appointment: ' . json_encode($data));
            
            // Insert the appointment using direct database query to ensure it works
            $db = \Config\Database::connect();
            $builder = $db->table('appointments');
            $builder->insert($data);
            $insertId = $db->insertID();
            
            log_message('debug', 'Appointment inserted with ID: ' . $insertId);
            
            if ($insertId) {
                // Verify the appointment was actually saved by querying directly
                $savedAppointment = $db->table('appointments')->where('id', $insertId)->get()->getRowArray();
                log_message('debug', 'Saved appointment retrieved: ' . json_encode($savedAppointment));
                
                if ($savedAppointment) {
                    // Redirect to the appointments page with the date filter set to the appointment date
                    return redirect()->to('appointments?date=' . $data['appointment_date'])->with('success', 'Appointment scheduled successfully! ID: ' . $insertId);
                } else {
                    $errors = ['database' => 'Appointment was inserted but could not be retrieved. Please refresh the page.'];
                    return redirect()->to('appointments?date=' . $data['appointment_date'])->with('errors', $errors);
                }
            } else {
                $dbError = $this->appointmentModel->db->error();
                $errors = ['database' => 'Failed to save appointment: ' . ($dbError['message'] ?? 'Unknown error')];
                if ($this->appointmentModel->errors()) {
                    $errors = array_merge($errors, $this->appointmentModel->errors());
                }
                return redirect()->back()->withInput()->with('errors', $errors);
            }
        } catch (\Exception $e) {
            log_message('error', 'Appointment creation error: ' . $e->getMessage());
            return redirect()->back()->withInput()->with('errors', ['database' => 'Error: ' . $e->getMessage()]);
        }
    }

    public function update($id)
    {
        if (!$this->session->get('is_logged_in') || $this->session->get('user_role') !== 'admin') {
            return redirect()->to('login')->with('error', 'Access denied. Admin only.');
        }

        $data = [
            'patient_id' => $this->request->getPost('patient_id'),
            'doctor_id' => $this->request->getPost('doctor_id'),
            'department_id' => $this->request->getPost('department_id') ?: null,
            'appointment_date' => $this->request->getPost('appointment_date'),
            'appointment_time' => $this->request->getPost('appointment_time'),
            'reason' => trim($this->request->getPost('reason')) ?: null,
            'notes' => trim($this->request->getPost('notes')) ?: null,
            'status' => $this->request->getPost('status') ?: 'scheduled'
        ];

        if ($this->appointmentModel->skipValidation(true)->update($id, $data)) {
            // Redirect to the appointments page with the date filter set to the appointment date
            $appointmentDate = $this->request->getPost('appointment_date');
            return redirect()->to('appointments?date=' . $appointmentDate)->with('success', 'Appointment updated successfully!');
        } else {
            return redirect()->back()->withInput()->with('errors', $this->appointmentModel->errors());
        }
    }

    public function delete($id)
    {
        if (!$this->session->get('is_logged_in') || $this->session->get('user_role') !== 'admin') {
            return redirect()->to('login')->with('error', 'Access denied. Admin only.');
        }

        if ($this->appointmentModel->delete($id)) {
            return redirect()->to('appointments')->with('success', 'Appointment deleted successfully!');
        } else {
            return redirect()->to('appointments')->with('error', 'Failed to delete appointment.');
        }
    }
}

