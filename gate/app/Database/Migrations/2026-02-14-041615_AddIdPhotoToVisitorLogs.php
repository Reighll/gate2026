<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddIdPhotoToVisitorLogs extends Migration
{
    public function up()
    {
        $this->forge->addColumn('visitor_logs', [
            'id_photo' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
                'null'       => true,
                'after'      => 'valid_id', // Places it right next to the ID number
            ],
        ]);
    }

    public function down()
    {
        $this->forge->dropColumn('visitor_logs', 'id_photo');
    }
}