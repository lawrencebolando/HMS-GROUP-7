<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateRoomsTable extends Migration
{
    public function up()
    {
        if ($this->db->tableExists('rooms')) {
            return;
        }

        $this->forge->addField([
            'id' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'room_number' => [
                'type'       => 'VARCHAR',
                'constraint' => '50',
            ],
            'room_type' => [
                'type'       => 'VARCHAR',
                'constraint' => '100',
                'null'       => true,
            ],
            'floor' => [
                'type'       => 'INT',
                'constraint' => 11,
                'null'       => true,
            ],
            'bed_count' => [
                'type'       => 'INT',
                'constraint' => 11,
                'default'    => 1,
            ],
            'available_beds' => [
                'type'       => 'INT',
                'constraint' => 11,
                'default'    => 1,
            ],
            'status' => [
                'type'       => 'ENUM',
                'constraint' => ['available', 'occupied', 'maintenance', 'reserved'],
                'default'    => 'available',
            ],
            'description' => [
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
        $this->forge->addUniqueKey('room_number');
        $this->forge->addKey('status');
        $this->forge->addKey('floor');
        $this->forge->createTable('rooms', true);
    }

    public function down()
    {
        $this->forge->dropTable('rooms');
    }
}

