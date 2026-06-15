<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;

class ItemReports extends BaseController
{
    public function index()
    {
        $db = \Config\Database::connect();

        $builder = $db->table('student_items');
        $builder->select('student_items.*, students.first_name, students.last_name');
        $builder->join('students', 'students.id = student_items.student_id', 'left');

        // Fetch items currently flagged as missing
        $builder->where('student_items.status', 'missing');
        $builder->orderBy('student_items.updated_at', 'DESC');

        $reports = $builder->get()->getResultArray();

        // NEW: Calculate Overview Counts for the summary cards
        $activeMissingCount = $db->table('student_items')->where('status', 'missing')->countAllResults();
        $flaggedCount       = $db->table('student_items')->where('status', 'flagged')->countAllResults();
        $inactiveCount      = $db->table('student_items')->where('status', 'inactive')->countAllResults();

        $data = [
            'title'              => 'Reported Items',
            'reports'            => $reports,
            'activeMissingCount' => $activeMissingCount,
            'flaggedCount'       => $flaggedCount,
            'inactiveCount'      => $inactiveCount
        ];

        return view('Admin/views/item_reports', $data);
    }

    public function resolve($id)
    {
        $db = \Config\Database::connect();

        // Mark the item as found/approved again
        $db->table('student_items')->where('id', $id)->update(['status' => 'approved']);

        return redirect()->back()->with('success', 'Report marked as RESOLVED. The item is now active again.');
    }
}