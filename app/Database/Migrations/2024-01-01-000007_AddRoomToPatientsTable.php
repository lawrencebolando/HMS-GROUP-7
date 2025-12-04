<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddRoomToPatientsTable extends Migration
{
    public function up()
    {
        $fields = [
            'room' => [
                'type'       => 'VARCHAR',
                'constraint' => '50',
                'null'       => true,
                'after'      => 'blood_group',
            ],
        ];

        $this->forge->addColumn('patients', $fields);
    }

    public function down()
    {
        $this->forge->dropColumn('patients', 'room');
    }
}

