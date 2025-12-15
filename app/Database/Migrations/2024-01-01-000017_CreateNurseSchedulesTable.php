<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateNurseSchedulesTable extends Migration
{
    public function up()
    {
        if ($this->db->tableExists('nurse_schedules')) {
            return;
        }

        $this->forge->addField([
            'id' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'nurse_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
            ],
            'shift_date' => [
                'type' => 'DATE',
            ],
            'shift_type' => [
                'type'       => 'VARCHAR',
                'constraint' => '50',
            ],
            'start_time' => [
                'type' => 'TIME',
            ],
            'end_time' => [
                'type' => 'TIME',
            ],
            'department' => [
                'type'       => 'VARCHAR',
                'constraint' => '255',
                'null'       => true,
            ],
            'notes' => [
                'type' => 'TEXT',
                'null' => true,
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
        $this->forge->addKey(['nurse_id', 'shift_date']);
        $this->forge->createTable('nurse_schedules', true);
    }

    public function down()
    {
        $this->forge->dropTable('nurse_schedules');
    }
}

