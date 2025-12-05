<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateInpatientsTable extends Migration
{
    public function up()
    {
        if ($this->db->tableExists('inpatients')) {
            return;
        }

        $this->forge->addField([
            'id' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'inpatient_id' => [
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
            'department_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
                'null'       => true,
            ],
            'room_number' => [
                'type'       => 'VARCHAR',
                'constraint' => '50',
                'null'       => true,
            ],
            'bed_number' => [
                'type'       => 'VARCHAR',
                'constraint' => '50',
                'null'       => true,
            ],
            'admission_date' => [
                'type' => 'DATE',
            ],
            'admission_time' => [
                'type' => 'TIME',
            ],
            'discharge_date' => [
                'type' => 'DATE',
                'null' => true,
            ],
            'discharge_time' => [
                'type' => 'TIME',
                'null' => true,
            ],
            'diagnosis' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'condition' => [
                'type'       => 'ENUM',
                'constraint' => ['stable', 'critical', 'serious', 'fair'],
                'default'    => 'stable',
            ],
            'status' => [
                'type'       => 'ENUM',
                'constraint' => ['admitted', 'discharged', 'transferred'],
                'default'    => 'admitted',
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
        $this->forge->addUniqueKey('inpatient_id');
        $this->forge->addKey(['patient_id', 'doctor_id']);
        $this->forge->createTable('inpatients', true);
    }

    public function down()
    {
        $this->forge->dropTable('inpatients');
    }
}

