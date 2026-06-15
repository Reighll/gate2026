<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateVisitorTagsTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'rfid_uid' => [
                'type'       => 'VARCHAR',
                'constraint' => '50',
                'unique'     => true, // The scanned code
            ],
            'pass_number' => [
                'type'       => 'VARCHAR',
                'constraint' => '50', // e.g. "Visitor Pass 01"
            ],
            'status' => [
                'type'       => 'ENUM',
                'constraint' => ['available', 'in_use', 'lost'],
                'default'    => 'available',
            ],
            'created_at' => ['type' => 'DATETIME', 'null' => true],
            'updated_at' => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->createTable('visitor_tags');
    }

    public function down()
    {
        $this->forge->dropTable('visitor_tags');
    }
}