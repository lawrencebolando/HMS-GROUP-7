<?php

namespace App\Models;

use CodeIgniter\Model;

class DoctorModel extends Model
{
    protected $table            = 'doctors';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'doctor_id',
        'full_name',
        'specialization',
        'department_id',
        'phone',
        'email',
        'years_of_experience',
        'schedule',
        'rating',
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
        'full_name' => 'required|min_length[2]|max_length[100]',
        'specialization' => 'required|min_length[2]|max_length[100]',
    ];
    protected $validationMessages   = [];
    protected $skipValidation       = false;
    protected $cleanValidationRules = true;

    protected $allowCallbacks = true;
    protected $beforeInsert   = ['generateDoctorId'];
    protected $afterInsert    = [];
    protected $beforeUpdate   = [];
    protected $afterUpdate     = [];
    protected $beforeFind      = [];
    protected $afterFind       = [];
    protected $beforeDelete    = [];
    protected $afterDelete     = [];

    protected function generateDoctorId(array $data)
    {
        if (!isset($data['data']['doctor_id']) || empty($data['data']['doctor_id'])) {
            do {
                $doctorId = 'DOC-' . str_pad(rand(1, 999999), 6, '0', STR_PAD_LEFT);
                $exists = $this->where('doctor_id', $doctorId)->first();
            } while ($exists);
            
            $data['data']['doctor_id'] = $doctorId;
        }
        return $data;
    }
    
    public function getInitials($fullName)
    {
        $names = explode(' ', $fullName);
        $initials = '';
        foreach ($names as $name) {
            if (!empty($name)) {
                $initials .= strtoupper(substr($name, 0, 1));
            }
        }
        return substr($initials, 0, 3);
    }
}

