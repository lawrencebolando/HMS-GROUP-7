<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;
use App\Models\DoctorTypeModel;

class DoctorTypesSeeder extends Seeder
{
    public function run()
    {
        $doctorTypeModel = new DoctorTypeModel();
        
        $doctorTypes = [
            ['type_name' => 'Cardiologist', 'description' => 'Specializes in heart and cardiovascular system', 'status' => 'active'],
            ['type_name' => 'Neurologist', 'description' => 'Specializes in brain and nervous system', 'status' => 'active'],
            ['type_name' => 'Pediatrician', 'description' => 'Specializes in children\'s health', 'status' => 'active'],
            ['type_name' => 'Orthopedic Surgeon', 'description' => 'Specializes in bones and joints', 'status' => 'active'],
            ['type_name' => 'Dermatologist', 'description' => 'Specializes in skin conditions', 'status' => 'active'],
            ['type_name' => 'General Practitioner', 'description' => 'General health and wellness', 'status' => 'active'],
        ];
        
        foreach ($doctorTypes as $type) {
            $existing = $doctorTypeModel->where('type_name', $type['type_name'])->first();
            if (!$existing) {
                $doctorTypeModel->insert($type);
            }
        }
        
        echo "Doctor types created successfully!\n";
    }
}

