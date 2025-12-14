<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateInvoicesTable extends Migration
{
    public function up()
    {
        if ($this->db->tableExists('invoices')) {
            return;
        }

        $this->forge->addField([
            'id' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'invoice_id' => [
                'type'       => 'VARCHAR',
                'constraint' => '50',
            ],
            'patient_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
                'null'       => true,
            ],
            'invoice_type' => [
                'type'       => 'VARCHAR',
                'constraint' => '100',
                'null'       => true,
            ],
            'amount' => [
                'type'       => 'DECIMAL',
                'constraint' => '10,2',
                'default'    => 0.00,
            ],
            'status' => [
                'type'       => 'ENUM',
                'constraint' => ['pending', 'paid', 'overdue', 'cancelled'],
                'default'    => 'pending',
            ],
            'invoice_date' => [
                'type' => 'DATE',
            ],
            'due_date' => [
                'type' => 'DATE',
                'null' => true,
            ],
            'payment_date' => [
                'type' => 'DATE',
                'null' => true,
            ],
            'payment_method' => [
                'type'       => 'VARCHAR',
                'constraint' => '50',
                'null'       => true,
            ],
            'description' => [
                'type' => 'TEXT',
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
        $this->forge->addUniqueKey('invoice_id');
        $this->forge->addKey('patient_id');
        $this->forge->addKey('invoice_date');
        $this->forge->addKey('status');
        $this->forge->addKey('due_date');
        $this->forge->createTable('invoices', true);
    }

    public function down()
    {
        $this->forge->dropTable('invoices');
    }
}

