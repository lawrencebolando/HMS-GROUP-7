<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class LabDataSeeder extends Seeder
{
    public function run()
    {
        $db = \Config\Database::connect();
        
        // Check if tables exist
        if (!$db->tableExists('lab_requests')) {
            echo "Lab requests table does not exist. Please run migrations first.\n";
            return;
        }

        if (!$db->tableExists('lab_results')) {
            echo "Lab results table does not exist. Please run migrations first.\n";
            return;
        }

        // Get existing patients and doctors
        $patients = $db->table('patients')->limit(10)->get()->getResultArray();
        $doctors = $db->table('users')->where('role', 'doctor')->limit(5)->get()->getResultArray();
        $labStaff = $db->table('users')->whereIn('role', ['lab_technician', 'lab_staff', 'lab'])->limit(3)->get()->getResultArray();

        if (empty($patients) || empty($doctors)) {
            echo "No patients or doctors found. Please seed patients and doctors first.\n";
            return;
        }

        // Sample test types
        $testTypes = [
            'Complete Blood Count (CBC)',
            'Blood Glucose Test',
            'Lipid Profile',
            'Liver Function Test',
            'Kidney Function Test',
            'Thyroid Function Test',
            'Urine Analysis',
            'Stool Analysis',
            'X-Ray Chest',
            'Ultrasound Abdomen',
            'ECG',
            'CT Scan Head',
            'MRI Brain'
        ];

        // Check if there are already lab requests
        $existingRequests = $db->table('lab_requests')->countAllResults();
        if ($existingRequests > 0) {
            echo "Lab requests already exist. Skipping lab requests seeder.\n";
        } else {
            // Create sample lab requests
            $year = date('Y');
            $requestSequence = 1;

            for ($i = 0; $i < 8; $i++) {
                $patient = $patients[array_rand($patients)];
                $doctor = $doctors[array_rand($doctors)];
                $testType = $testTypes[array_rand($testTypes)];
                $priority = ['normal', 'urgent', 'emergency'][array_rand(['normal', 'urgent', 'emergency'])];
                $status = ['pending', 'in_progress', 'completed', 'cancelled'][array_rand(['pending', 'in_progress', 'completed'])];
                
                $requestId = 'LAB-' . $year . '-' . str_pad($requestSequence, 4, '0', STR_PAD_LEFT);
                $requestSequence++;

                $requestDate = date('Y-m-d', strtotime('-' . rand(0, 30) . ' days'));
                $completedDate = ($status === 'completed') ? date('Y-m-d', strtotime($requestDate . ' +' . rand(1, 5) . ' days')) : null;

                $db->table('lab_requests')->insert([
                    'lab_request_id' => $requestId,
                    'patient_id' => $patient['id'],
                    'doctor_id' => $doctor['id'],
                    'test_type' => $testType,
                    'priority' => $priority,
                    'status' => $status,
                    'requested_date' => $requestDate,
                    'completed_date' => $completedDate,
                    'notes' => 'Sample lab request for testing purposes.',
                    'created_at' => date('Y-m-d H:i:s', strtotime($requestDate)),
                    'updated_at' => date('Y-m-d H:i:s')
                ]);
            }

            echo "✓ Created 8 lab requests\n";
        }

        // Check if there are already lab results
        $existingResults = $db->table('lab_results')->countAllResults();
        if ($existingResults > 0) {
            echo "Lab results already exist. Skipping lab results seeder.\n";
        } else {
            // Get completed lab requests
            $completedRequests = $db->table('lab_requests')
                ->where('status', 'completed')
                ->get()
                ->getResultArray();

            // Create sample lab results
            $year = date('Y');
            $resultSequence = 1;

            // Create results for some completed requests
            foreach (array_slice($completedRequests, 0, 5) as $request) {
                $patient = $db->table('patients')->where('id', $request['patient_id'])->get()->getRowArray();
                if (!$patient) continue;

                $resultId = 'RES-' . $year . '-' . str_pad($resultSequence, 4, '0', STR_PAD_LEFT);
                $resultSequence++;

                $isCritical = (rand(0, 10) < 2); // 20% chance of critical
                $releasedBy = !empty($labStaff) ? $labStaff[array_rand($labStaff)]['id'] : null;
                $releasedDate = date('Y-m-d', strtotime($request['completed_date'] ?? $request['requested_date']));
                $releasedTime = date('H:i:s', strtotime('+' . rand(1, 8) . ' hours', strtotime('09:00:00')));

                $resultSummaries = [
                    'Complete Blood Count (CBC)' => 'All parameters within normal range.',
                    'Blood Glucose Test' => 'Fasting glucose: 95 mg/dL (Normal)',
                    'Lipid Profile' => 'Cholesterol levels within acceptable range.',
                    'Liver Function Test' => 'Liver enzymes slightly elevated, follow-up recommended.',
                    'Kidney Function Test' => 'Kidney function normal.',
                    'Thyroid Function Test' => 'TSH levels within normal range.',
                    'Urine Analysis' => 'No abnormalities detected.',
                    'Stool Analysis' => 'Normal findings.',
                    'X-Ray Chest' => 'Clear lung fields, no acute findings.',
                    'Ultrasound Abdomen' => 'Normal organ sizes and echogenicity.',
                    'ECG' => 'Normal sinus rhythm.',
                    'CT Scan Head' => 'No acute intracranial abnormalities.',
                    'MRI Brain' => 'Normal brain parenchyma.'
                ];

                $resultSummary = $resultSummaries[$request['test_type']] ?? 'Test completed. Results reviewed.';
                if ($isCritical) {
                    $resultSummary = 'CRITICAL: ' . $resultSummary . ' Immediate attention required.';
                }

                $db->table('lab_results')->insert([
                    'lab_result_id' => $resultId,
                    'lab_request_id' => $request['id'],
                    'patient_id' => $request['patient_id'],
                    'test_type' => $request['test_type'],
                    'result_summary' => $resultSummary,
                    'detailed_results' => 'Detailed test results and analysis data.',
                    'is_critical' => $isCritical ? 1 : 0,
                    'status' => 'released',
                    'released_by' => $releasedBy,
                    'released_date' => $releasedDate,
                    'released_time' => $releasedTime,
                    'notes' => 'Results reviewed and released.',
                    'created_at' => date('Y-m-d H:i:s', strtotime($releasedDate)),
                    'updated_at' => date('Y-m-d H:i:s')
                ]);
            }

            // Create some additional results not linked to requests
            for ($i = 0; $i < 3; $i++) {
                $patient = $patients[array_rand($patients)];
                $testType = $testTypes[array_rand($testTypes)];
                $isCritical = (rand(0, 10) < 1); // 10% chance
                $releasedBy = !empty($labStaff) ? $labStaff[array_rand($labStaff)]['id'] : null;
                $releasedDate = date('Y-m-d', strtotime('-' . rand(0, 7) . ' days'));
                $releasedTime = date('H:i:s', strtotime('+' . rand(1, 8) . ' hours', strtotime('09:00:00')));

                $resultId = 'RES-' . $year . '-' . str_pad($resultSequence, 4, '0', STR_PAD_LEFT);
                $resultSequence++;

                $resultSummaries = [
                    'Complete Blood Count (CBC)' => 'All parameters within normal range.',
                    'Blood Glucose Test' => 'Fasting glucose: 95 mg/dL (Normal)',
                    'Lipid Profile' => 'Cholesterol levels within acceptable range.',
                    'Liver Function Test' => 'Liver enzymes slightly elevated, follow-up recommended.',
                    'Kidney Function Test' => 'Kidney function normal.',
                    'Thyroid Function Test' => 'TSH levels within normal range.',
                    'Urine Analysis' => 'No abnormalities detected.',
                    'Stool Analysis' => 'Normal findings.',
                    'X-Ray Chest' => 'Clear lung fields, no acute findings.',
                    'Ultrasound Abdomen' => 'Normal organ sizes and echogenicity.',
                    'ECG' => 'Normal sinus rhythm.',
                    'CT Scan Head' => 'No acute intracranial abnormalities.',
                    'MRI Brain' => 'Normal brain parenchyma.'
                ];

                $resultSummary = $resultSummaries[$testType] ?? 'Test completed. Results reviewed.';
                if ($isCritical) {
                    $resultSummary = 'CRITICAL: ' . $resultSummary . ' Immediate attention required.';
                }

                $db->table('lab_results')->insert([
                    'lab_result_id' => $resultId,
                    'lab_request_id' => null,
                    'patient_id' => $patient['id'],
                    'test_type' => $testType,
                    'result_summary' => $resultSummary,
                    'detailed_results' => 'Detailed test results and analysis data.',
                    'is_critical' => $isCritical ? 1 : 0,
                    'status' => 'released',
                    'released_by' => $releasedBy,
                    'released_date' => $releasedDate,
                    'released_time' => $releasedTime,
                    'notes' => 'Results reviewed and released.',
                    'created_at' => date('Y-m-d H:i:s', strtotime($releasedDate)),
                    'updated_at' => date('Y-m-d H:i:s')
                ]);
            }

            echo "✓ Created lab results\n";
        }

        echo "\n✓ Lab data seeding completed!\n";
    }
}

