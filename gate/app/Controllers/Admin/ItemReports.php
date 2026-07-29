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
        $builder->groupStart();
        $builder->where('student_items.status', 'missing');
        $builder->orWhere('student_items.resolved_at IS NOT NULL', null, false);
        $builder->groupEnd();
        $builder->orderBy('student_items.updated_at', 'DESC');
        $allReports = $builder->get()->getResultArray();

        $activeMissingCount = $db->table('student_items')->where('status', 'missing')->countAllResults();
        $resolvedCount      = $db->table('student_items')->where('resolved_at IS NOT NULL')->countAllResults();

        $data = [
            'title'              => 'Reported Items',
            'allReports'         => $allReports,
            'activeMissingCount' => $activeMissingCount,
            'resolvedCount'      => $resolvedCount
        ];

        return view('Admin/views/item_reports', $data);
    }

    /**
     * Resolve a missing item report -> item goes back to normal/active status.
     */
    public function resolve($id)
    {
        $db = \Config\Database::connect();

        $db->table('student_items')->where('id', $id)->update([
            'status'      => 'approved',
            'resolved_at' => date('Y-m-d H:i:s')
        ]);

        return redirect()->back()->with('success', 'Report marked as resolved. The item is now active again.');
    }
}