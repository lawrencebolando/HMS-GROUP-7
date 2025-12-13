<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class RoomsSeeder extends Seeder
{
    public function run()
    {
        $db = \Config\Database::connect();
        
        // Check if table exists
        if (!$db->tableExists('rooms')) {
            echo "Rooms table does not exist. Please run migrations first.\n";
            return;
        }

        // Check if there are already rooms
        $existingCount = $db->table('rooms')->countAllResults();
        if ($existingCount > 0) {
            echo "Rooms already exist. Skipping seeder.\n";
            return;
        }

        // Sample room types
        $roomTypes = [
            'Standard',
            'Private',
            'ICU',
            'Emergency',
            'General Ward',
            'Semi-Private'
        ];

        // Generate rooms
        $roomsData = [];
        
        // Floor 1 - Standard Rooms
        for ($i = 101; $i <= 110; $i++) {
            $bedCount = rand(1, 2);
            $availableBeds = rand(0, $bedCount);
            $status = $availableBeds > 0 ? 'available' : 'occupied';
            
            $roomsData[] = [
                'room_number' => (string)$i,
                'room_type' => 'Standard',
                'floor' => 1,
                'bed_count' => $bedCount,
                'available_beds' => $availableBeds,
                'status' => $status,
                'description' => 'Standard patient room',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ];
        }
        
        // Floor 2 - Private Rooms
        for ($i = 201; $i <= 210; $i++) {
            $bedCount = 1;
            $availableBeds = rand(0, 1);
            $status = $availableBeds > 0 ? 'available' : 'occupied';
            
            $roomsData[] = [
                'room_number' => (string)$i,
                'room_type' => 'Private',
                'floor' => 2,
                'bed_count' => $bedCount,
                'available_beds' => $availableBeds,
                'status' => $status,
                'description' => 'Private patient room',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ];
        }
        
        // Floor 3 - ICU Rooms
        for ($i = 301; $i <= 305; $i++) {
            $bedCount = 1;
            $availableBeds = rand(0, 1);
            $status = $availableBeds > 0 ? 'available' : 'occupied';
            
            $roomsData[] = [
                'room_number' => (string)$i,
                'room_type' => 'ICU',
                'floor' => 3,
                'bed_count' => $bedCount,
                'available_beds' => $availableBeds,
                'status' => $status,
                'description' => 'Intensive Care Unit',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ];
        }
        
        // Floor 1 - Emergency Rooms
        for ($i = 111; $i <= 115; $i++) {
            $bedCount = 1;
            $availableBeds = rand(0, 1);
            $status = $availableBeds > 0 ? 'available' : 'occupied';
            
            $roomsData[] = [
                'room_number' => (string)$i,
                'room_type' => 'Emergency',
                'floor' => 1,
                'bed_count' => $bedCount,
                'available_beds' => $availableBeds,
                'status' => $status,
                'description' => 'Emergency room',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ];
        }
        
        // Floor 2 - General Ward
        for ($i = 211; $i <= 220; $i++) {
            $bedCount = rand(2, 4);
            $availableBeds = rand(0, $bedCount);
            $status = $availableBeds > 0 ? 'available' : 'occupied';
            
            $roomsData[] = [
                'room_number' => (string)$i,
                'room_type' => 'General Ward',
                'floor' => 2,
                'bed_count' => $bedCount,
                'available_beds' => $availableBeds,
                'status' => $status,
                'description' => 'General ward room',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ];
        }

        // Insert each room individually
        $inserted = 0;
        foreach ($roomsData as $room) {
            try {
                $db->table('rooms')->insert($room);
                $inserted++;
            } catch (\Exception $e) {
                echo "Error inserting room {$room['room_number']}: " . $e->getMessage() . "\n";
            }
        }
        
        echo "Successfully seeded {$inserted} rooms.\n";
    }
}

