<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateWalkInLabRequestsTable extends Migration
{
    public function up()
    {
        if ($this->db->tableExists('walk_in_lab_requests')) {
            return;
        }

        $this->forge->addField([
            'id' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'request_id' => [
                'type'       => 'VARCHAR',
                'constraint' => '50',
            ],
            'patient_name' => [
                'type'       => 'VARCHAR',
                'constraint' => '200',
            ],
            'contact' => [
                'type'       => 'VARCHAR',
                'constraint' => '50',
                'null'       => true,
            ],
            'phone' => [
                'type'       => 'VARCHAR',
                'constraint' => '20',
                'null'       => true,
            ],
            'email' => [
                'type'       => 'VARCHAR',
                'constraint' => '100',
                'null'       => true,
            ],
            'test_type' => [
                'type'       => 'VARCHAR',
                'constraint' => '200',
            ],
            'priority' => [
                'type'       => 'ENUM',
                'constraint' => ['low', 'normal', 'medium', 'high'],
                'default'    => 'normal',
            ],
            'status' => [
                'type'       => 'ENUM',
                'constraint' => ['pending', 'in_progress', 'completed', 'cancelled'],
                'default'    => 'pending',
            ],
            'request_date' => [
                'type' => 'DATE',
            ],
            'request_time' => [
                'type' => 'TIME',
                'null' => true,
            ],
            'completed_date' => [
                'type' => 'DATE',
                'null' => true,
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
        $this->forge->addUniqueKey('request_id');
        $this->forge->addKey('request_date');
        $this->forge->addKey('status');
        $this->forge->addKey('priority');
        $this->forge->createTable('walk_in_lab_requests', true);
    }

    public function down()
    {
        $this->forge->dropTable('walk_in_lab_requests');
    }
}

