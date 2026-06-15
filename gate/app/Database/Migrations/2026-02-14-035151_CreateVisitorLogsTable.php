<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateVisitorLogsTable extends Migration
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
            'name' => [
                'type'       => 'VARCHAR',
                'constraint' => '100',
            ],
            'purpose' => [
                'type'       => 'VARCHAR',
                'constraint' => '255',
            ],
            'items' => [
                'type'       => 'TEXT',
                'null'       => true, // Optional items they bring in
            ],
            'valid_id' => [
                'type'       => 'VARCHAR',
                'constraint' => '100', // e.g. "Drivers License #123"
            ],
            'tag_id' => [
                'type'       => 'VARCHAR',
                'constraint' => '50', // The RFID Card UID
            ],
            'status' => [
                'type'       => 'ENUM',
                'constraint' => ['active', 'checked_out'],
                'default'    => 'active',
            ],
            'time_in' => [
                'type' => 'DATETIME',
            ],
            'time_out' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->createTable('visitor_logs');
    }

    public function down()
    {
        $this->forge->dropTable('visitor_logs');
    }
}