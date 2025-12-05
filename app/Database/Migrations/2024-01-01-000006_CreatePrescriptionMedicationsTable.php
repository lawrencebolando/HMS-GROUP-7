<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreatePrescriptionMedicationsTable extends Migration
{
    public function up()
    {
        if ($this->db->tableExists('prescription_medications')) {
            return;
        }

        $this->forge->addField([
            'id' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'prescription_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
            ],
            'medication_name' => [
                'type'       => 'VARCHAR',
                'constraint' => '255',
            ],
            'dosage' => [
                'type'       => 'VARCHAR',
                'constraint' => '100',
            ],
            'frequency' => [
                'type'       => 'VARCHAR',
                'constraint' => '100',
            ],
            'meal_instruction' => [
                'type'       => 'VARCHAR',
                'constraint' => '50',
                'null'       => true,
            ],
            'duration' => [
                'type'       => 'VARCHAR',
                'constraint' => '100',
            ],
            'quantity' => [
                'type'       => 'INT',
                'constraint' => 11,
                'default'    => 1,
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
        $this->forge->addKey('prescription_id');
        $this->forge->createTable('prescription_medications', true);
    }

    public function down()
    {
        $this->forge->dropTable('prescription_medications');
    }
}

