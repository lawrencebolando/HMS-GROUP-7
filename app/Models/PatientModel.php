<?php

namespace App\Models;

use CodeIgniter\Model;

class PatientModel extends Model
{
    protected $table            = 'patients';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'patient_id',
        'first_name',
        'last_name',
        'email',
        'phone',
        'date_of_birth',
        'gender',
        'address',
        'blood_group',
        'status',
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
        'first_name' => 'required|min_length[2]|max_length[100]',
        'last_name' => 'required|min_length[2]|max_length[100]',
        'email' => 'permit_empty|valid_email',
    ];
    protected $validationMessages   = [];
    protected $skipValidation       = false;
    protected $cleanValidationRules = true;

    protected $allowCallbacks = true;
    protected $beforeInsert   = ['generatePatientId'];
    protected $afterInsert    = [];
    protected $beforeUpdate   = [];
    protected $afterUpdate     = [];
    protected $beforeFind      = [];
    protected $afterFind       = [];
    protected $beforeDelete    = [];
    protected $afterDelete     = [];

    protected function generatePatientId(array $data)
    {
        // Always generate a unique patient_id if not provided
        if (!isset($data['data']['patient_id']) || empty($data['data']['patient_id'])) {
            // Generate unique patient ID
            do {
                $patientId = 'PAT-' . str_pad(rand(1, 999999), 6, '0', STR_PAD_LEFT);
                $exists = $this->where('patient_id', $patientId)->first();
            } while ($exists);
            
            $data['data']['patient_id'] = $patientId;
        }
        return $data;
    }

    public function getFullName($patient)
    {
        return trim(($patient['first_name'] ?? '') . ' ' . ($patient['last_name'] ?? ''));
    }
}

