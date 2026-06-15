<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddRfidToVisitorLogs extends Migration
{
    public function up()
    {
        // Check if column exists to avoid errors
        if (!$this->db->fieldExists('rfid_uid', 'visitor_logs')) {
            $fields = [
                'rfid_uid' => [
                    'type'       => 'VARCHAR',
                    'constraint' => '50',
                    'after'      => 'tag_id',
                    'null'       => true,
                ],
                'guard_in' => [
                    'type'       => 'VARCHAR',
                    'constraint' => '50',
                    'after'      => 'status',
                    'null'       => true,
                ],
            ];
            $this->forge->addColumn('visitor_logs', $fields);
        }
    }

    public function down()
    {
        $this->forge->dropColumn('visitor_logs', 'rfid_uid');
        $this->forge->dropColumn('visitor_logs', 'guard_in');
    }
}