<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateLabRequestsTable extends Migration
{
    public function up()
    {
        if ($this->db->tableExists('lab_requests')) {
            return;
        }

        $this->forge->addField([
            'id' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'lab_request_id' => [
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
            'test_type' => [
                'type'       => 'VARCHAR',
                'constraint' => '255',
            ],
            'priority' => [
                'type'       => 'ENUM',
                'constraint' => ['normal', 'urgent', 'emergency'],
                'default'    => 'normal',
            ],
            'notes' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'status' => [
                'type'       => 'ENUM',
                'constraint' => ['pending', 'in_progress', 'completed', 'cancelled'],
                'default'    => 'pending',
            ],
            'requested_date' => [
                'type' => 'DATE',
            ],
            'completed_date' => [
                'type' => 'DATE',
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
        $this->forge->addUniqueKey('lab_request_id');
        $this->forge->addKey(['patient_id', 'doctor_id']);
        $this->forge->createTable('lab_requests', true);
    }

    public function down()
    {
        $this->forge->dropTable('lab_requests');
    }
}

