<?php

namespace App\Controllers;

use App\Models\PatientModel;

class Patients extends BaseController
{
    protected $patientModel;
    protected $session;

    public function __construct()
    {
        $this->patientModel = new PatientModel();
        $this->session = session();
    }

    public function index()
    {
        // Allow both admin and receptionist to access
        if (!$this->session->get('is_logged_in') || !in_array($this->session->get('user_role'), ['admin', 'receptionist'])) {
            return redirect()->to('login')->with('error', 'Please login to continue');
        }

        // Get all patients from the same database table (shared between admin and receptionist)
        // Use direct query to ensure fresh data
        $db = \Config\Database::connect();
        $patients = $db->table('patients')
            ->orderBy('created_at', 'DESC')
            ->get()
            ->getResultArray();

        $data = [
            'title' => 'Patient Management',
            'user' => [
                'name' => $this->session->get('user_name'),
                'role' => $this->session->get('user_role')
            ],
            'patients' => $patients
        ];

        return view('patients/index', $data);
    }

    public function selectType()
    {
        // Only receptionist can access patient creation
        if (!$this->session->get('is_logged_in') || $this->session->get('user_role') !== 'receptionist') {
            return redirect()->to('login')->with('error', 'Access denied. Receptionist only.');
        }

        return view('patients/select_type');
    }

    public function create()
    {
        // Only receptionist can create patients
        if (!$this->session->get('is_logged_in') || $this->session->get('user_role') !== 'receptionist') {
            return redirect()->to('login')->with('error', 'Access denied. Receptionist only.');
        }

        $patientType = $this->request->getGet('type') ?? 'outpatient';
        $db = \Config\Database::connect();
        
        // Get doctors (from users table with role 'doctor' or from doctors table)
        $doctors = [];
        $userModel = new \App\Models\UserModel();
        $doctorUsers = $userModel->where('role', 'doctor')->where('status', 'active')->findAll();
        
        // Also try to get from doctors table if it exists
        if ($db->tableExists('doctors')) {
            $doctorModel = new \App\Models\DoctorModel();
            $doctorsFromTable = $doctorModel->where('status', 'active')->findAll();
            foreach ($doctorsFromTable as $doc) {
                $doctors[] = [
                    'id' => $doc['id'],
                    'name' => $doc['full_name'],
                    'specialization' => $doc['specialization'] ?? ''
                ];
            }
        } else {
            // Use users table
            foreach ($doctorUsers as $doc) {
                $doctors[] = [
                    'id' => $doc['id'],
                    'name' => $doc['name'],
                    'specialization' => 'General Practitioner'
                ];
            }
        }
        
        // Get rooms
        $rooms = [];
        if ($db->tableExists('rooms')) {
            $rooms = $db->table('rooms')
                ->where('status', 'available')
                ->orWhere('status', 'reserved')
                ->orderBy('room_number', 'ASC')
                ->get()
                ->getResultArray();
        }

        $data = [
            'title' => $patientType === 'inpatient' ? 'Add New Inpatient' : 'Add New Patient',
            'user' => [
                'name' => $this->session->get('user_name'),
                'role' => $this->session->get('user_role')
            ],
            'patient_type' => $patientType,
            'doctors' => $doctors,
            'rooms' => $rooms
        ];

        return view('patients/create', $data);
    }

    public function store()
    {
        // Only receptionist can create patients
        if (!$this->session->get('is_logged_in') || $this->session->get('user_role') !== 'receptionist') {
            return redirect()->to('login')->with('error', 'Access denied. Receptionist only.');
        }

        $patientType = $this->request->getPost('patient_type') ?? 'outpatient';
        $isInpatient = $patientType === 'inpatient';

        // Validate required fields
        $validation = \Config\Services::validation();
        $rules = [
            'first_name' => 'required|min_length[2]|max_length[100]',
            'last_name' => 'required|min_length[2]|max_length[100]',
            'email' => 'permit_empty|valid_email',
            'phone' => 'required',
            'date_of_birth' => 'required',
            'gender' => 'required',
        ];

        if ($isInpatient) {
            $rules['admission_date'] = 'required';
            $rules['admission_time'] = 'required';
            $rules['admission_type'] = 'required';
            $rules['attending_doctor'] = 'required';
            $rules['room_ward'] = 'required';
            $rules['medical_concern'] = 'required';
            $rules['province'] = 'required';
            $rules['city'] = 'required';
            $rules['barangay'] = 'required';
        } else {
            // Outpatient validation
            $rules['province'] = 'required';
            $rules['city'] = 'required';
            $rules['barangay'] = 'required';
            $rules['medical_concern'] = 'required';
        }

        $validation->setRules($rules);

        if (!$validation->run($this->request->getPost())) {
            return redirect()->back()->withInput()->with('errors', $validation->getErrors());
        }

        // Build address for outpatient or from address fields for inpatient
        $address = '';
        if ($isInpatient) {
            $province = $this->request->getPost('province');
            $city = $this->request->getPost('city');
            $barangay = $this->request->getPost('barangay');
            if ($province && $city && $barangay) {
                $address = trim("{$barangay}, {$city}, {$province}");
            }
        } else {
            // Outpatient: build address from province, city, barangay
            $province = $this->request->getPost('province');
            $city = $this->request->getPost('city');
            $barangay = $this->request->getPost('barangay');
            if ($province && $city && $barangay) {
                $address = trim("{$barangay}, {$city}, {$province}");
            } else {
                $address = trim($this->request->getPost('address')) ?: null;
            }
        }

        $patientData = [
            'first_name' => trim($this->request->getPost('first_name')),
            'last_name' => trim($this->request->getPost('last_name')),
            'email' => trim($this->request->getPost('email')) ?: null,
            'phone' => trim($this->request->getPost('phone')),
            'date_of_birth' => $this->request->getPost('date_of_birth'),
            'gender' => $this->request->getPost('gender'),
            'address' => $address,
            'blood_group' => $this->request->getPost('blood_group') ?: null,
            'status' => 'active'
        ];

        // Insert patient
        try {
            $db = \Config\Database::connect();
            $db->transStart();
            
            if ($this->patientModel->skipValidation(true)->insert($patientData)) {
                $patientId = $this->patientModel->getInsertID();
                
                // If inpatient, create admission record
                if ($isInpatient && $db->tableExists('admissions')) {
                    // Generate admission ID
                    $admissionId = 'ADM-' . date('Ymd') . '-' . str_pad($patientId, 4, '0', STR_PAD_LEFT);
                    
                    // Build comprehensive notes from all additional fields
                    $notes = [];
                    
                    // Vital Signs
                    if ($this->request->getPost('temperature')) {
                        $notes[] = "Temperature: " . $this->request->getPost('temperature') . "°C";
                    }
                    if ($this->request->getPost('blood_pressure')) {
                        $notes[] = "BP: " . $this->request->getPost('blood_pressure');
                    }
                    if ($this->request->getPost('heart_rate')) {
                        $notes[] = "HR: " . $this->request->getPost('heart_rate') . " bpm";
                    }
                    if ($this->request->getPost('oxygen_saturation')) {
                        $notes[] = "SpO2: " . $this->request->getPost('oxygen_saturation') . "%";
                    }
                    if ($this->request->getPost('respiratory_rate')) {
                        $notes[] = "RR: " . $this->request->getPost('respiratory_rate') . " bpm";
                    }
                    if ($this->request->getPost('weight')) {
                        $notes[] = "Weight: " . $this->request->getPost('weight') . " kg";
                    }
                    if ($this->request->getPost('height')) {
                        $notes[] = "Height: " . $this->request->getPost('height') . " cm";
                    }
                    if ($this->request->getPost('bmi')) {
                        $notes[] = "BMI: " . $this->request->getPost('bmi');
                    }
                    
                    // Medical Information
                    if ($this->request->getPost('allergies')) {
                        $notes[] = "Allergies: " . $this->request->getPost('allergies');
                    }
                    if ($this->request->getPost('current_medications')) {
                        $notes[] = "Current Medications: " . $this->request->getPost('current_medications');
                    }
                    if ($this->request->getPost('medical_history')) {
                        $notes[] = "Medical History: " . $this->request->getPost('medical_history');
                    }
                    if ($this->request->getPost('insurance_provider')) {
                        $notes[] = "Insurance: " . $this->request->getPost('insurance_provider');
                    }
                    if ($this->request->getPost('department')) {
                        $notes[] = "Department: " . $this->request->getPost('department');
                    }
                    if ($this->request->getPost('admission_notes')) {
                        $notes[] = "Admission Notes: " . $this->request->getPost('admission_notes');
                    }
                    
                    // Emergency Contact
                    if ($this->request->getPost('emergency_contact_name')) {
                        $notes[] = "Emergency Contact: " . $this->request->getPost('emergency_contact_name');
                        if ($this->request->getPost('emergency_contact_phone')) {
                            $notes[] = "Contact #: " . $this->request->getPost('emergency_contact_phone');
                        }
                        if ($this->request->getPost('emergency_contact_relationship')) {
                            $notes[] = "Relationship: " . $this->request->getPost('emergency_contact_relationship');
                        }
                        if ($this->request->getPost('emergency_contact_address')) {
                            $notes[] = "EC Address: " . $this->request->getPost('emergency_contact_address');
                        }
                    }
                    
                    $admissionData = [
                        'admission_id' => $admissionId,
                        'patient_id' => $patientId,
                        'doctor_id' => $this->request->getPost('attending_doctor'),
                        'admission_date' => $this->request->getPost('admission_date'),
                        'admission_time' => $this->request->getPost('admission_time'),
                        'room' => $this->request->getPost('room_ward'),
                        'bed' => $this->request->getPost('bed_number') ?: null,
                        'case_type' => $this->request->getPost('admission_type'),
                        'reason' => $this->request->getPost('medical_concern'),
                        'diagnosis' => $this->request->getPost('medical_concern'), // Can be updated later
                        'status' => 'active',
                        'notes' => !empty($notes) ? implode("\n", $notes) : null,
                        'created_at' => date('Y-m-d H:i:s'),
                        'updated_at' => date('Y-m-d H:i:s')
                    ];
                    
                    $db->table('admissions')->insert($admissionData);
                    
                    // Update room availability if bed was assigned
                    if ($this->request->getPost('bed_number') && $this->request->getPost('room_ward')) {
                        $roomNumber = $this->request->getPost('room_ward');
                        $room = $db->table('rooms')->where('room_number', $roomNumber)->get()->getRowArray();
                        if ($room) {
                            $newAvailableBeds = max(0, ($room['available_beds'] ?? 1) - 1);
                            $newStatus = $newAvailableBeds > 0 ? 'available' : 'occupied';
                            $db->table('rooms')
                                ->where('room_number', $roomNumber)
                                ->update([
                                    'available_beds' => $newAvailableBeds,
                                    'status' => $newStatus,
                                    'updated_at' => date('Y-m-d H:i:s')
                                ]);
                        }
                    }
                }
                
                $db->transComplete();
                
                if ($db->transStatus() === false) {
                    throw new \Exception('Database transaction failed');
                }
                
                // Redirect based on user role
                $userRole = $this->session->get('user_role');
                if ($isInpatient) {
                    $redirectUrl = 'admissions';
                } else {
                    // Receptionist should go to their patients page, admin to patients page
                    $redirectUrl = ($userRole === 'receptionist') ? 'reception/patients' : 'patients';
                }
                return redirect()->to($redirectUrl)->with('success', $isInpatient ? 'Inpatient registered and admitted successfully!' : 'Patient added successfully!');
            } else {
                $dbError = $this->patientModel->db->error();
                $errors = ['database' => 'Failed to save patient: ' . ($dbError['message'] ?? 'Unknown error')];
                return redirect()->back()->withInput()->with('errors', $errors);
            }
        } catch (\Exception $e) {
            return redirect()->back()->withInput()->with('errors', ['database' => 'Error: ' . $e->getMessage()]);
        }
    }

    public function edit($id)
    {
        if (!$this->session->get('is_logged_in') || $this->session->get('user_role') !== 'admin') {
            return redirect()->to('login')->with('error', 'Access denied. Admin only.');
        }

        $patient = $this->patientModel->find($id);
        if (!$patient) {
            return redirect()->to('patients')->with('error', 'Patient not found.');
        }

        $data = [
            'title' => 'Edit Patient',
            'user' => [
                'name' => $this->session->get('user_name'),
                'role' => $this->session->get('user_role')
            ],
            'patient' => $patient
        ];

        return view('patients/edit', $data);
    }

    public function update($id)
    {
        if (!$this->session->get('is_logged_in') || $this->session->get('user_role') !== 'admin') {
            return redirect()->to('login')->with('error', 'Access denied. Admin only.');
        }

        $data = [
            'first_name' => $this->request->getPost('first_name'),
            'last_name' => $this->request->getPost('last_name'),
            'email' => $this->request->getPost('email'),
            'phone' => $this->request->getPost('phone'),
            'date_of_birth' => $this->request->getPost('date_of_birth'),
            'gender' => $this->request->getPost('gender'),
            'address' => $this->request->getPost('address'),
            'blood_group' => $this->request->getPost('blood_group'),
            'room' => trim($this->request->getPost('room')) ?: null,
            'status' => $this->request->getPost('status')
        ];

        if ($this->patientModel->update($id, $data)) {
            return redirect()->to('patients')->with('success', 'Patient updated successfully!');
        } else {
            return redirect()->back()->withInput()->with('errors', $this->patientModel->errors());
        }
    }

    public function delete($id)
    {
        if (!$this->session->get('is_logged_in') || $this->session->get('user_role') !== 'admin') {
            return redirect()->to('login')->with('error', 'Access denied. Admin only.');
        }

        if ($this->patientModel->delete($id)) {
            return redirect()->to('patients')->with('success', 'Patient deleted successfully!');
        } else {
            return redirect()->to('patients')->with('error', 'Failed to delete patient.');
        }
    }
}

