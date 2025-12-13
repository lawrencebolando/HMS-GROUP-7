<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class AdmissionsSeeder extends Seeder
{
    public function run()
    {
        $db = \Config\Database::connect();
        
        // Check if admissions table exists
        if (!$db->tableExists('admissions')) {
            echo "Admissions table does not exist. Please run migrations first.\n";
            return;
        }

        // Check if there are already admissions
        $existingCount = $db->table('admissions')->countAllResults();
        if ($existingCount > 0) {
            echo "Admissions already exist. Skipping seeder.\n";
            return;
        }

        // Get some patients and doctors for sample data
        $patients = $db->table('patients')->limit(5)->get()->getResultArray();
        $doctors = $db->table('users')->where('role', 'doctor')->limit(3)->get()->getResultArray();
        
        if (empty($patients) || empty($doctors)) {
            echo "No patients or doctors found. Please add patients and doctors first.\n";
            return;
        }

        // Prepare sample admissions data
        $admissionsData = [];
        
        // Admission 1
        $admissionsData[] = [
            'admission_id' => 'ADM-' . date('Y') . '-001',
            'patient_id' => $patients[0]['id'] ?? 1,
            'doctor_id' => $doctors[0]['id'] ?? 1,
            'admission_date' => date('Y-m-d', strtotime('-5 days')),
            'admission_time' => '10:30:00',
            'discharge_date' => null,
            'discharge_time' => null,
            'room' => '101',
            'bed' => 'A',
            'case_type' => 'Emergency',
            'reason' => 'Chest pain and shortness of breath',
            'diagnosis' => 'Acute myocardial infarction',
            'status' => 'active',
            'notes' => 'Patient admitted for observation and treatment.',
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ];
        
        // Admission 2
        $patient2Id = count($patients) > 1 ? $patients[1]['id'] : $patients[0]['id'];
        $admissionsData[] = [
            'admission_id' => 'ADM-' . date('Y') . '-002',
            'patient_id' => $patient2Id,
            'doctor_id' => $doctors[0]['id'] ?? 1,
            'admission_date' => date('Y-m-d', strtotime('-3 days')),
            'admission_time' => '14:15:00',
            'discharge_date' => null,
            'discharge_time' => null,
            'room' => '205',
            'bed' => 'B',
            'case_type' => 'Scheduled',
            'reason' => 'Scheduled surgery - Appendectomy',
            'diagnosis' => 'Acute appendicitis',
            'status' => 'active',
            'notes' => 'Pre-operative preparation completed.',
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ];
        
        // Admission 3 (Discharged)
        $patient3Id = count($patients) > 2 ? $patients[2]['id'] : $patients[0]['id'];
        $doctor2Id = count($doctors) > 1 ? $doctors[1]['id'] : $doctors[0]['id'];
        $admissionsData[] = [
            'admission_id' => 'ADM-' . date('Y') . '-003',
            'patient_id' => $patient3Id,
            'doctor_id' => $doctor2Id,
            'admission_date' => date('Y-m-d', strtotime('-10 days')),
            'admission_time' => '09:00:00',
            'discharge_date' => date('Y-m-d', strtotime('-2 days')),
            'discharge_time' => '16:00:00',
            'room' => '302',
            'bed' => 'A',
            'case_type' => 'Routine',
            'reason' => 'Pneumonia treatment',
            'diagnosis' => 'Community-acquired pneumonia',
            'status' => 'discharged',
            'notes' => 'Patient recovered well and discharged with medications.',
            'created_at' => date('Y-m-d H:i:s', strtotime('-10 days')),
            'updated_at' => date('Y-m-d H:i:s', strtotime('-2 days')),
        ];
        
        // Admission 4
        $admissionsData[] = [
            'admission_id' => 'ADM-' . date('Y') . '-004',
            'patient_id' => $patients[0]['id'] ?? 1,
            'doctor_id' => $doctors[0]['id'] ?? 1,
            'admission_date' => date('Y-m-d'),
            'admission_time' => '08:00:00',
            'discharge_date' => null,
            'discharge_time' => null,
            'room' => '401',
            'bed' => 'C',
            'case_type' => 'Emergency',
            'reason' => 'Fractured leg from accident',
            'diagnosis' => 'Femur fracture',
            'status' => 'active',
            'notes' => 'Admitted today for surgery.',
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ];
        
        // Admission 5
        $admissionsData[] = [
            'admission_id' => 'ADM-' . date('Y') . '-005',
            'patient_id' => $patient2Id,
            'doctor_id' => $doctors[0]['id'] ?? 1,
            'admission_date' => date('Y-m-d'),
            'admission_time' => '11:30:00',
            'discharge_date' => null,
            'discharge_time' => null,
            'room' => '105',
            'bed' => 'A',
            'case_type' => 'Scheduled',
            'reason' => 'Diabetes management and monitoring',
            'diagnosis' => 'Type 2 Diabetes Mellitus',
            'status' => 'active',
            'notes' => 'Admitted for blood sugar stabilization.',
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ];

        // Insert each admission individually to avoid batch issues
        $inserted = 0;
        foreach ($admissionsData as $admission) {
            try {
                $db->table('admissions')->insert($admission);
                $inserted++;
            } catch (\Exception $e) {
                echo "Error inserting admission {$admission['admission_id']}: " . $e->getMessage() . "\n";
            }
        }
        
        echo "Successfully seeded {$inserted} admissions.\n";
    }
}

