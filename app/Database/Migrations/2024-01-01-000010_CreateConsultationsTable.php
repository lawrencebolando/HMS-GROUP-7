<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateConsultationsTable extends Migration
{
    public function up()
    {
        if ($this->db->tableExists('consultations')) {
            return;
        }

        $this->forge->addField([
            'id' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'consultation_id' => [
                'type'       => 'VARCHAR',
                'constraint' => '50',
            ],
            'appointment_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
                'null'       => true,
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
            'consultation_date' => [
                'type' => 'DATE',
            ],
            'consultation_time' => [
                'type' => 'TIME',
            ],
            'type' => [
                'type'       => 'VARCHAR',
                'constraint' => '100',
            ],
            'diagnosis' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'notes' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'prescription_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
                'null'       => true,
            ],
            'status' => [
                'type'       => 'ENUM',
                'constraint' => ['scheduled', 'completed', 'cancelled'],
                'default'    => 'scheduled',
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
        $this->forge->addUniqueKey('consultation_id');
        $this->forge->addKey(['patient_id', 'doctor_id']);
        $this->forge->createTable('consultations', true);
    }

    public function down()
    {
        $this->forge->dropTable('consultations');
    }
}

