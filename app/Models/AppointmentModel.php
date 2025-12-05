<?php

namespace App\Models;

use CodeIgniter\Model;

class AppointmentModel extends Model
{
    protected $table            = 'appointments';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'appointment_id',
        'patient_id',
        'doctor_id',
        'department_id',
        'appointment_date',
        'appointment_time',
        'reason',
        'status',
        'notes',
        'created_at',
        'updated_at'
    ];

    protected bool $allowEmptyInserts = false;
    protected bool $updateOnlyChanged = true;

    protected array $casts = [];
    protected array $castHandlers = [];

    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';

    protected $validationRules      = [
        'patient_id' => 'required|integer',
        'doctor_id' => 'required|integer',
        'appointment_date' => 'required|valid_date',
        'appointment_time' => 'required',
    ];
    protected $validationMessages   = [];
    protected $skipValidation       = false;
    protected $cleanValidationRules = true;

    protected $allowCallbacks = true;
    protected $beforeInsert   = ['generateAppointmentId'];
    protected $afterInsert    = [];
    protected $beforeUpdate   = [];
    protected $afterUpdate     = [];
    protected $beforeFind      = [];
    protected $afterFind       = [];
    protected $beforeDelete    = [];
    protected $afterDelete     = [];

    protected function generateAppointmentId(array $data)
    {
        // Always generate a unique appointment_id if not provided
        if (!isset($data['data']['appointment_id']) || empty($data['data']['appointment_id'])) {
            // Generate unique appointment ID
            do {
                $appointmentId = 'APT-' . date('Ymd') . '-' . str_pad(rand(1, 9999), 4, '0', STR_PAD_LEFT);
                $exists = $this->where('appointment_id', $appointmentId)->first();
            } while ($exists);
            
            $data['data']['appointment_id'] = $appointmentId;
        }
        return $data;
    }
}

