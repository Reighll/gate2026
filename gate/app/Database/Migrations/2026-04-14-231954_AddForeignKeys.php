<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddForeignKeys extends Migration
{
    public function up()
    {
        // 1. Connect student_items to students
        // This ensures an item cannot exist without a valid student ID
        $this->db->query('ALTER TABLE student_items ADD CONSTRAINT fk_student_item FOREIGN KEY (student_id) REFERENCES students(id) ON DELETE CASCADE ON UPDATE CASCADE');

        // 2. Connect visitor_logs to visitor_tags
        // Note: Check your exact column name in visitor_logs (assuming it's rfid_uid)
        $this->db->query('ALTER TABLE visitor_logs ADD CONSTRAINT fk_visitor_log_tag FOREIGN KEY (rfid_uid) REFERENCES visitor_tags(rfid_uid) ON DELETE CASCADE ON UPDATE CASCADE');

        // Add any other connections here! For example, if logs track the guard on duty:
        // $this->db->query('ALTER TABLE visitor_logs ADD CONSTRAINT fk_visitor_log_guard FOREIGN KEY (guard_id) REFERENCES guards(id) ON DELETE SET NULL ON UPDATE CASCADE');
    }

    public function down()
    {
        // Safely severe the connections if we ever need to roll back the database
        $this->db->query('ALTER TABLE student_items DROP FOREIGN KEY fk_student_item');
        $this->db->query('ALTER TABLE visitor_logs DROP FOREIGN KEY fk_visitor_log_tag');
    }
}