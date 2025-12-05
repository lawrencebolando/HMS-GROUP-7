<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateDoctorsTable extends Migration
{
    public function up()
    {
        if ($this->db->tableExists('doctors')) {
            return;
        }

        $this->forge->addField([
            'id' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'doctor_id' => [
                'type'       => 'VARCHAR',
                'constraint' => '50',
            ],
            'full_name' => [
                'type'       => 'VARCHAR',
                'constraint' => '100',
            ],
            'specialization' => [
                'type'       => 'VARCHAR',
                'constraint' => '100',
            ],
            'department_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
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
            'years_of_experience' => [
                'type'       => 'INT',
                'constraint' => 11,
                'null'       => true,
            ],
            'schedule' => [
                'type'       => 'VARCHAR',
                'constraint' => '100',
                'null'       => true,
            ],
            'rating' => [
                'type'       => 'DECIMAL',
                'constraint' => '3,1',
                'default'    => 0.0,
            ],
            'status' => [
                'type'       => 'ENUM',
                'constraint' => ['active', 'on_leave', 'inactive'],
                'default'    => 'active',
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
        $this->forge->addUniqueKey('doctor_id');
        $this->forge->createTable('doctors', true);
    }

    public function down()
    {
        $this->forge->dropTable('doctors');
    }
}

