<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;
use App\Models\DepartmentModel;
use App\Models\UserModel;
use App\Models\PatientModel;
use App\Models\AppointmentModel;

class HMSDataSeeder extends Seeder
{
    public function run()
    {
        try {
            $deptModel = new DepartmentModel();
            $userModel = new UserModel();
            $patientModel = new PatientModel();
            $appointmentModel = new AppointmentModel();

            // Create Departments
            $departments = [
                ['name' => 'Cardiology', 'description' => 'Heart and cardiovascular system', 'status' => 'active'],
                ['name' => 'Neurology', 'description' => 'Brain and nervous system', 'status' => 'active'],
                ['name' => 'Orthopedics', 'description' => 'Bones and joints', 'status' => 'active'],
                ['name' => 'Pediatrics', 'description' => 'Children\'s health', 'status' => 'active'],
                ['name' => 'General Medicine', 'description' => 'General health and wellness', 'status' => 'active'],
            ];

            foreach ($departments as $dept) {
                $existing = $deptModel->where('name', $dept['name'])->first();
                if (!$existing) {
                    $deptModel->insert($dept);
                }
            }

            // Create Sample Doctors
            $doctors = [
                ['name' => 'Dr. Sarah Johnson', 'email' => 'sarah.johnson@hospital.com', 'password' => 'doctor123', 'role' => 'doctor', 'status' => 'active'],
                ['name' => 'Dr. Michael Chen', 'email' => 'michael.chen@hospital.com', 'password' => 'doctor123', 'role' => 'doctor', 'status' => 'active'],
                ['name' => 'Dr. Emily Davis', 'email' => 'emily.davis@hospital.com', 'password' => 'doctor123', 'role' => 'doctor', 'status' => 'active'],
            ];

            foreach ($doctors as $doctor) {
                $existing = $userModel->where('email', $doctor['email'])->first();
                if (!$existing) {
                    $userModel->insert($doctor);
                }
            }

            // Create Sample Receptionist
            $receptionist = [
                'name' => 'Maria Garcia',
                'email' => 'maria.garcia@hospital.com',
                'password' => 'reception123',
                'role' => 'receptionist',
                'status' => 'active'
            ];

            $existing = $userModel->where('email', $receptionist['email'])->first();
            if (!$existing) {
                $userModel->insert($receptionist);
            }

            // Create Sample Patients
            $patients = [
                [
                    'first_name' => 'John',
                    'last_name' => 'Doe',
                    'email' => 'john.doe@example.com',
                    'phone' => '1234567890',
                    'date_of_birth' => '1985-05-15',
                    'gender' => 'Male',
                    'address' => '123 Main Street, City',
                    'blood_group' => 'O+',
                    'status' => 'active'
                ],
                [
                    'first_name' => 'Jane',
                    'last_name' => 'Smith',
                    'email' => 'jane.smith@example.com',
                    'phone' => '0987654321',
                    'date_of_birth' => '1990-08-22',
                    'gender' => 'Female',
                    'address' => '456 Oak Avenue, City',
                    'blood_group' => 'A+',
                    'status' => 'active'
                ],
                [
                    'first_name' => 'Robert',
                    'last_name' => 'Johnson',
                    'email' => 'robert.johnson@example.com',
                    'phone' => '1122334455',
                    'date_of_birth' => '1978-12-10',
                    'gender' => 'Male',
                    'address' => '789 Pine Road, City',
                    'blood_group' => 'B+',
                    'status' => 'active'
                ],
                [
                    'first_name' => 'Emily',
                    'last_name' => 'Williams',
                    'email' => 'emily.williams@example.com',
                    'phone' => '5566778899',
                    'date_of_birth' => '1992-03-25',
                    'gender' => 'Female',
                    'address' => '321 Elm Street, City',
                    'blood_group' => 'AB+',
                    'status' => 'active'
                ],
                [
                    'first_name' => 'Michael',
                    'last_name' => 'Brown',
                    'email' => 'michael.brown@example.com',
                    'phone' => '9988776655',
                    'date_of_birth' => '1988-07-18',
                    'gender' => 'Male',
                    'address' => '654 Maple Drive, City',
                    'blood_group' => 'O-',
                    'status' => 'active'
                ]
            ];

            foreach ($patients as $patient) {
                // Check by email if available, otherwise by name combination
                $existing = null;
                if (!empty($patient['email'])) {
                    $existing = $patientModel->where('email', $patient['email'])->first();
                }
                if (!$existing) {
                    $existing = $patientModel
                        ->where('first_name', $patient['first_name'])
                        ->where('last_name', $patient['last_name'])
                        ->first();
                }
                if (!$existing) {
                    $patientModel->insert($patient);
                }
            }

            // Get all doctors and patients for creating appointments
            $allDoctors = $userModel->where('role', 'doctor')->findAll();
            $allPatients = $patientModel->findAll();
            $allDepartments = $deptModel->findAll();

            // Create Sample Appointments
            $today = date('Y-m-d');
            $tomorrow = date('Y-m-d', strtotime('+1 day'));
            $nextWeek = date('Y-m-d', strtotime('+7 days'));
            $nextMonth = date('Y-m-d', strtotime('+30 days'));

            $appointments = [];

            // Today's appointments (2-3 appointments)
            if (!empty($allDoctors) && !empty($allPatients) && !empty($allDepartments)) {
                // Appointment 1 - Today morning
                $appointments[] = [
                    'patient_id' => $allPatients[0]['id'],
                    'doctor_id' => $allDoctors[0]['id'],
                    'department_id' => $allDepartments[0]['id'],
                    'appointment_date' => $today,
                    'appointment_time' => '09:00:00',
                    'reason' => 'Regular checkup',
                    'status' => 'scheduled',
                    'notes' => 'Follow-up appointment'
                ];

                // Appointment 2 - Today afternoon
                if (count($allPatients) > 1 && count($allDoctors) > 1) {
                    $appointments[] = [
                        'patient_id' => $allPatients[1]['id'],
                        'doctor_id' => $allDoctors[1]['id'],
                        'department_id' => count($allDepartments) > 1 ? $allDepartments[1]['id'] : $allDepartments[0]['id'],
                        'appointment_date' => $today,
                        'appointment_time' => '14:30:00',
                        'reason' => 'Consultation',
                        'status' => 'scheduled',
                        'notes' => 'Initial consultation'
                    ];
                }

                // Appointment 3 - Today evening
                if (count($allPatients) > 2 && count($allDoctors) > 0) {
                    $appointments[] = [
                        'patient_id' => $allPatients[2]['id'],
                        'doctor_id' => $allDoctors[0]['id'],
                        'department_id' => count($allDepartments) > 2 ? $allDepartments[2]['id'] : $allDepartments[0]['id'],
                        'appointment_date' => $today,
                        'appointment_time' => '16:00:00',
                        'reason' => 'Follow-up visit',
                        'status' => 'scheduled',
                        'notes' => null
                    ];
                }

                // Upcoming appointments - Tomorrow
                if (count($allPatients) > 0 && count($allDoctors) > 1) {
                    $appointments[] = [
                        'patient_id' => $allPatients[0]['id'],
                        'doctor_id' => $allDoctors[1]['id'],
                        'department_id' => $allDepartments[0]['id'],
                        'appointment_date' => $tomorrow,
                        'appointment_time' => '10:00:00',
                        'reason' => 'Lab results review',
                        'status' => 'scheduled',
                        'notes' => 'Review test results'
                    ];
                }

                // Upcoming appointments - Next week
                if (count($allPatients) > 1 && count($allDoctors) > 2) {
                    $appointments[] = [
                        'patient_id' => $allPatients[1]['id'],
                        'doctor_id' => $allDoctors[2]['id'],
                        'department_id' => count($allDepartments) > 1 ? $allDepartments[1]['id'] : $allDepartments[0]['id'],
                        'appointment_date' => $nextWeek,
                        'appointment_time' => '11:30:00',
                        'reason' => 'Specialist consultation',
                        'status' => 'scheduled',
                        'notes' => 'Specialist referral'
                    ];
                }

                // Upcoming appointments - Next month
                if (count($allPatients) > 2 && count($allDoctors) > 0) {
                    $appointments[] = [
                        'patient_id' => $allPatients[2]['id'],
                        'doctor_id' => $allDoctors[0]['id'],
                        'department_id' => count($allDepartments) > 2 ? $allDepartments[2]['id'] : $allDepartments[0]['id'],
                        'appointment_date' => $nextMonth,
                        'appointment_time' => '15:00:00',
                        'reason' => 'Annual checkup',
                        'status' => 'scheduled',
                        'notes' => 'Annual physical examination'
                    ];
                }

                // Insert appointments
                foreach ($appointments as $appointment) {
                    // Check if appointment already exists (by patient, doctor, date, and time)
                    $existing = $appointmentModel
                        ->where('patient_id', $appointment['patient_id'])
                        ->where('doctor_id', $appointment['doctor_id'])
                        ->where('appointment_date', $appointment['appointment_date'])
                        ->where('appointment_time', $appointment['appointment_time'])
                        ->first();
                    
                    if (!$existing) {
                        $appointmentModel->skipValidation(true)->insert($appointment);
                    }
                }
            }

            echo "HMS sample data created successfully!\n";
            echo "- Departments: " . count($departments) . "\n";
            echo "- Doctors: " . count($doctors) . "\n";
            echo "- Receptionist: 1\n";
            echo "- Patients: " . count($patients) . "\n";
            echo "- Appointments: " . count($appointments) . "\n";
        } catch (\Exception $e) {
            echo "Error creating HMS sample data: " . $e->getMessage() . "\n";
            echo "Please check your database connection settings.\n";
            throw $e;
        }
    }
}

