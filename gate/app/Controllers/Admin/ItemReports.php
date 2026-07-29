<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;

class ItemReports extends BaseController
{
    public function index()
    {
        $db = \Config\Database::connect();

        // ---- Missing Items (reported by students, live immediately) ----
        $missingBuilder = $db->table('student_items');
        $missingBuilder->select('student_items.*, students.first_name, students.last_name');
        $missingBuilder->join('students', 'students.id = student_items.student_id', 'left');
        $missingBuilder->where('student_items.status', 'missing');
        $missingBuilder->orderBy('student_items.updated_at', 'DESC');
        $missingReports = $missingBuilder->get()->getResultArray();

        // ---- Resolved Reports (log of previously-missing items, now cleared) ----
        $resolvedBuilder = $db->table('student_items');
        $resolvedBuilder->select('student_items.*, students.first_name, students.last_name');
        $resolvedBuilder->join('students', 'students.id = student_items.student_id', 'left');
        $resolvedBuilder->where('student_items.resolved_at IS NOT NULL', null, false);
        $resolvedBuilder->orderBy('student_items.resolved_at', 'DESC');
        $resolvedReports = $resolvedBuilder->get()->getResultArray();

        // Calculate Overview Counts for the summary cards
        $activeMissingCount = $db->table('student_items')->where('status', 'missing')->countAllResults();
        $resolvedCount      = $db->table('student_items')->where('resolved_at IS NOT NULL')->countAllResults();

        $data = [
            'title'              => 'Reported Items',
            'missingReports'     => $missingReports,
            'resolvedReports'    => $resolvedReports,
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