<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class WalkInLabRequestsSeeder extends Seeder
{
    public function run()
    {
        $db = \Config\Database::connect();
        
        // Check if table exists
        if (!$db->tableExists('walk_in_lab_requests')) {
            echo "Walk-in lab requests table does not exist. Please run migrations first.\n";
            return;
        }

        // Check if there are already requests
        $existingCount = $db->table('walk_in_lab_requests')->countAllResults();
        if ($existingCount > 0) {
            echo "Walk-in lab requests already exist. Skipping seeder.\n";
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
            'X-Ray',
            'Ultrasound'
        ];

        // Sample requests
        $requestsData = [];
        
        // Request 1 - Pending
        $requestsData[] = [
            'request_id' => 'WIL-' . date('Y') . '-001',
            'patient_name' => 'John Doe',
            'contact' => 'john.doe@email.com',
            'phone' => '+1234567890',
            'email' => 'john.doe@email.com',
            'test_type' => $testTypes[0],
            'priority' => 'normal',
            'status' => 'pending',
            'request_date' => date('Y-m-d'),
            'request_time' => '09:30:00',
            'completed_date' => null,
            'notes' => 'Routine health check-up',
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ];
        
        // Request 2 - In Progress
        $requestsData[] = [
            'request_id' => 'WIL-' . date('Y') . '-002',
            'patient_name' => 'Jane Smith',
            'contact' => '+1234567891',
            'phone' => '+1234567891',
            'email' => 'jane.smith@email.com',
            'test_type' => $testTypes[1],
            'priority' => 'high',
            'status' => 'in_progress',
            'request_date' => date('Y-m-d', strtotime('-1 day')),
            'request_time' => '14:15:00',
            'completed_date' => null,
            'notes' => 'Fasting blood sugar test - urgent',
            'created_at' => date('Y-m-d H:i:s', strtotime('-1 day')),
            'updated_at' => date('Y-m-d H:i:s'),
        ];
        
        // Request 3 - Completed
        $requestsData[] = [
            'request_id' => 'WIL-' . date('Y') . '-003',
            'patient_name' => 'Robert Johnson',
            'contact' => 'robert.j@email.com',
            'phone' => '+1234567892',
            'email' => 'robert.j@email.com',
            'test_type' => $testTypes[3],
            'priority' => 'normal',
            'status' => 'completed',
            'request_date' => date('Y-m-d', strtotime('-3 days')),
            'request_time' => '10:00:00',
            'completed_date' => date('Y-m-d', strtotime('-2 days')),
            'notes' => 'Pre-employment medical check',
            'created_at' => date('Y-m-d H:i:s', strtotime('-3 days')),
            'updated_at' => date('Y-m-d H:i:s', strtotime('-2 days')),
        ];
        
        // Request 4 - Pending
        $requestsData[] = [
            'request_id' => 'WIL-' . date('Y') . '-004',
            'patient_name' => 'Maria Garcia',
            'contact' => '+1234567893',
            'phone' => '+1234567893',
            'email' => 'maria.g@email.com',
            'test_type' => $testTypes[6],
            'priority' => 'medium',
            'status' => 'pending',
            'request_date' => date('Y-m-d'),
            'request_time' => '11:45:00',
            'completed_date' => null,
            'notes' => 'Urinary tract infection symptoms',
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ];
        
        // Request 5 - Completed
        $requestsData[] = [
            'request_id' => 'WIL-' . date('Y') . '-005',
            'patient_name' => 'David Wilson',
            'contact' => 'david.w@email.com',
            'phone' => '+1234567894',
            'email' => 'david.w@email.com',
            'test_type' => $testTypes[2],
            'priority' => 'normal',
            'status' => 'completed',
            'request_date' => date('Y-m-d', strtotime('-5 days')),
            'request_time' => '08:30:00',
            'completed_date' => date('Y-m-d', strtotime('-4 days')),
            'notes' => 'Annual health screening',
            'created_at' => date('Y-m-d H:i:s', strtotime('-5 days')),
            'updated_at' => date('Y-m-d H:i:s', strtotime('-4 days')),
        ];

        // Insert each request individually
        $inserted = 0;
        foreach ($requestsData as $request) {
            try {
                $db->table('walk_in_lab_requests')->insert($request);
                $inserted++;
            } catch (\Exception $e) {
                echo "Error inserting request {$request['request_id']}: " . $e->getMessage() . "\n";
            }
        }
        
        echo "Successfully seeded {$inserted} walk-in lab requests.\n";
    }
}

