<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateAdmissionsTable extends Migration
{
    public function up()
    {
        if ($this->db->tableExists('admissions')) {
            return;
        }

        $this->forge->addField([
            'id' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'admission_id' => [
                'type'       => 'VARCHAR',
                'constraint' => '50',
            ],
            'patient_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
            ],
            'doctor_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
            ],
            'admission_date' => [
                'type' => 'DATE',
            ],
            'admission_time' => [
                'type' => 'TIME',
                'null' => true,
            ],
            'discharge_date' => [
                'type' => 'DATE',
                'null' => true,
            ],
            'discharge_time' => [
                'type' => 'TIME',
                'null' => true,
            ],
            'room' => [
                'type'       => 'VARCHAR',
                'constraint' => '50',
                'null'       => true,
            ],
            'bed' => [
                'type'       => 'VARCHAR',
                'constraint' => '50',
                'null'       => true,
            ],
            'case_type' => [
                'type'       => 'VARCHAR',
                'constraint' => '100',
                'null'       => true,
            ],
            'reason' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'diagnosis' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'status' => [
                'type'       => 'ENUM',
                'constraint' => ['active', 'discharged', 'transferred'],
                'default'    => 'active',
            ],
            'notes' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'updated_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addUniqueKey('admission_id');
        $this->forge->addKey(['patient_id', 'doctor_id']);
        $this->forge->addKey('admission_date');
        $this->forge->addKey('status');
        $this->forge->createTable('admissions', true);
    }

    public function down()
    {
        $this->forge->dropTable('admissions');
    }
}

