<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;

class ItemReports extends BaseController
{
    public function index()
    {
        $db = \Config\Database::connect();

        // ---- Pending Reports (newly submitted, awaiting admin review) ----
        $pendingBuilder = $db->table('student_items');
        $pendingBuilder->select('student_items.*, students.first_name, students.last_name');
        $pendingBuilder->join('students', 'students.id = student_items.student_id', 'left');
        $pendingBuilder->where('student_items.status', 'flagged');
        $pendingBuilder->orderBy('student_items.updated_at', 'DESC');
        $pendingReports = $pendingBuilder->get()->getResultArray();

        // ---- Missing Items (already approved, actively missing) ----
        $missingBuilder = $db->table('student_items');
        $missingBuilder->select('student_items.*, students.first_name, students.last_name');
        $missingBuilder->join('students', 'students.id = student_items.student_id', 'left');
        $missingBuilder->where('student_items.status', 'missing');
        $missingBuilder->orderBy('student_items.updated_at', 'DESC');
        $missingReports = $missingBuilder->get()->getResultArray();

        // NEW: Calculate Overview Counts for the summary cards
        $activeMissingCount = $db->table('student_items')->where('status', 'missing')->countAllResults();
        $flaggedCount       = $db->table('student_items')->where('status', 'flagged')->countAllResults();
        $inactiveCount      = $db->table('student_items')->where('status', 'inactive')->countAllResults();

        $data = [
            'title'              => 'Reported Items',
            'pendingReports'     => $pendingReports,
            'missingReports'     => $missingReports,
            'activeMissingCount' => $activeMissingCount,
            'flaggedCount'       => $flaggedCount,
            'inactiveCount'      => $inactiveCount
        ];

        return view('Admin/views/item_reports', $data);
    }

    /**
     * Approve a pending (flagged) report -> it becomes an active "missing" listing.
     */
    public function approve($id)
    {
        $db = \Config\Database::connect();

        $db->table('student_items')->where('id', $id)->update(['status' => 'missing']);

        return redirect()->back()->with('success', 'Report approved. The item is now listed as missing.');
    }

    /**
     * Resolve a missing item report -> item goes back to normal/active status.
     */
    public function resolve($id)
    {
        $db = \Config\Database::connect();

        // Mark the item as found/approved again
        $db->table('student_items')->where('id', $id)->update(['status' => 'approved']);

        return redirect()->back()->with('success', 'Report marked as resolved. The item is now active again.');
    }
}