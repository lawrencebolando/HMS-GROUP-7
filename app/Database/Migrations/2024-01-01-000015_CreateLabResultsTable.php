<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateLabResultsTable extends Migration
{
    public function up()
    {
        if ($this->db->tableExists('lab_results')) {
            return;
        }

        $this->forge->addField([
            'id' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'lab_result_id' => [
                'type'       => 'VARCHAR',
                'constraint' => '50',
            ],
            'lab_request_id' => [
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
            'test_type' => [
                'type'       => 'VARCHAR',
                'constraint' => '255',
            ],
            'result_summary' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'detailed_results' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'is_critical' => [
                'type'       => 'TINYINT',
                'constraint' => 1,
                'default'    => 0,
            ],
            'status' => [
                'type'       => 'ENUM',
                'constraint' => ['pending', 'in_progress', 'released', 'cancelled'],
                'default'    => 'pending',
            ],
            'released_by' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
                'null'       => true,
            ],
            'released_date' => [
                'type' => 'DATE',
                'null' => true,
            ],
            'released_time' => [
                'type' => 'TIME',
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
        $this->forge->addUniqueKey('lab_result_id');
        $this->forge->addKey(['patient_id', 'lab_request_id']);
        $this->forge->addKey('released_date');
        $this->forge->addKey('status');
        $this->forge->createTable('lab_results', true);
    }

    public function down()
    {
        $this->forge->dropTable('lab_results');
    }
}

