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
                'patient_id' => 'PAT-000001',
                'first_name' => 'John',
                'last_name' => 'Smith',
                'email' => 'john.smith@email.com',
                'phone' => '555-0101',
                'date_of_birth' => '1985-05-15',
                'gender' => 'male',
                'address' => '123 Main St, City',
                'blood_group' => 'O+',
                'status' => 'active'
            ],
            [
                'patient_id' => 'PAT-000002',
                'first_name' => 'Jane',
                'last_name' => 'Doe',
                'email' => 'jane.doe@email.com',
                'phone' => '555-0102',
                'date_of_birth' => '1990-08-22',
                'gender' => 'female',
                'address' => '456 Oak Ave, City',
                'blood_group' => 'A+',
                'status' => 'active'
            ],
            [
                'patient_id' => 'PAT-000003',
                'first_name' => 'Robert',
                'last_name' => 'Williams',
                'email' => 'robert.w@email.com',
                'phone' => '555-0103',
                'date_of_birth' => '1978-12-10',
                'gender' => 'male',
                'address' => '789 Pine Rd, City',
                'blood_group' => 'B+',
                'status' => 'active'
            ],
            [
                'patient_id' => 'PAT-000004',
                'first_name' => 'Lisa',
                'last_name' => 'Anderson',
                'email' => 'lisa.a@email.com',
                'phone' => '555-0104',
                'date_of_birth' => '1992-03-25',
                'gender' => 'female',
                'address' => '321 Elm St, City',
                'blood_group' => 'AB+',
                'status' => 'active'
            ],
            [
                'patient_id' => 'PAT-000005',
                'first_name' => 'David',
                'last_name' => 'Brown',
                'email' => 'david.brown@email.com',
                'phone' => '555-0105',
                'date_of_birth' => '1988-07-18',
                'gender' => 'male',
                'address' => '654 Maple Dr, City',
                'blood_group' => 'O-',
                'status' => 'active'
            ],
            [
                'patient_id' => 'PAT-000006',
                'first_name' => 'Amy',
                'last_name' => 'Taylor',
                'email' => 'amy.taylor@email.com',
                'phone' => '555-0106',
                'date_of_birth' => '1995-11-30',
                'gender' => 'female',
                'address' => '987 Cedar Ln, City',
                'blood_group' => 'A-',
                'status' => 'active'
            ],
        ];

        foreach ($patients as $patient) {
            $existing = $patientModel->where('patient_id', $patient['patient_id'])->first();
            if (!$existing) {
                $patientModel->insert($patient);
            }
        }

        echo "HMS sample data created successfully!\n";
        echo "- Departments: " . count($departments) . "\n";
        echo "- Doctors: " . count($doctors) . "\n";
        echo "- Receptionist: 1\n";
        echo "- Patients: " . count($patients) . "\n";
    }
}

