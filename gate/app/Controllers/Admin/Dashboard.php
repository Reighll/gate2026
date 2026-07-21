<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;

class Dashboard extends BaseController
{
    public function index()
    {
        $db = \Config\Database::connect();

        // 1. Get the requested filter from the dropdown (Default to 'today')
        $filter       = $this->request->getGet('filter') ?? 'today';
        $startDateRaw = $this->request->getGet('start_date');
        $endDateRaw   = $this->request->getGet('end_date');

        // 2. Calculate the exact Date Range based on the filter
        $startDate = date('Y-m-d 00:00:00');
        $endDate   = date('Y-m-d 23:59:59');

        if ($filter === '7days') {
            $startDate = date('Y-m-d 00:00:00', strtotime('-7 days'));
        } elseif ($filter === 'month') {
            $startDate = date('Y-m-01 00:00:00');
        } elseif ($filter === 'year') {
            $startDate = date('Y-01-01 00:00:00');
        } elseif ($filter === 'custom') {
            // Fallback to today if they selected custom but left it blank
            $startDate = ($startDateRaw ?: date('Y-m-d')) . ' 00:00:00';
            $endDate   = ($endDateRaw ?: date('Y-m-d')) . ' 23:59:59';
        }

        // 3. Count Student Entries (Filtered)
        $studentEntryCount = 0;
        if ($db->tableExists('item_logs')) {
            $studentEntryCount = $db->table('item_logs')
                ->where('action', 'time_in')
                ->where('created_at >=', $startDate)
                ->where('created_at <=', $endDate)
                ->countAllResults();
        }

        // 4. Count Visitor Entries (Filtered)
        $visitorEntryCount = 0;
        if ($db->tableExists('visitor_logs')) {
            $visitorEntryCount = $db->table('visitor_logs')
                ->where('time_in >=', $startDate)
                ->where('time_in <=', $endDate)
                ->countAllResults();
        }

        // 5. Count Item Reports (Filtered)
        $itemReportsCount = $db->table('student_items')
            ->where('status', 'missing')
            ->where('updated_at >=', $startDate)
            ->where('updated_at <=', $endDate)
            ->countAllResults();

        // ==========================================
        // ALL-TIME TOTALS (No Date Filtering)
        // ==========================================
        $totalStudentEntries = 0;
        if ($db->tableExists('item_logs')) {
            $totalStudentEntries = $db->table('item_logs')->where('action', 'time_in')->countAllResults();
        }

        $totalVisitorEntries = 0;
        if ($db->tableExists('visitor_logs')) {
            $totalVisitorEntries = $db->table('visitor_logs')->countAllResults();
        }

        $totalItemReports = clone $db->table('student_items'); // Create isolated instance
        $totalItemReports = $db->table('student_items')->where('status', 'missing')->countAllResults();


        // ==========================================
        // 6. FETCH MASTER SCAN HISTORY LOGS (Live Feed)
        // ==========================================
        $recentLogs = [];
        if ($db->tableExists('item_logs')) {
            $builder = $db->table('item_logs');

            $builder->select('
                item_logs.*, 
                student_items.brand_model, 
                student_items.serial_number, 
                students.first_name, 
                students.last_name, 
                students.student_number,
                students.department,
                guards.first_name AS guard_first_name,
                guards.last_name AS guard_last_name
            ');
            $builder->join('student_items', 'student_items.id = item_logs.item_id', 'left');
            $builder->join('students', 'students.id = student_items.student_id', 'left');
            $builder->join('guards', 'guards.id = item_logs.guard_id', 'left');

            // Filter the history log by the exact same date filter chosen above!
            $builder->where('item_logs.created_at >=', $startDate);
            $builder->where('item_logs.created_at <=', $endDate);
            $builder->orderBy('item_logs.created_at', 'DESC');
            $builder->limit(50); // Kept at 50 so it's a healthy size for a live feed

            $recentLogs = $builder->get()->getResultArray();
        }

        // Pass all data to the view
        $data = [
            'title'               => 'Admin Dashboard',
            'filter'              => $filter,
            'startDateRaw'        => $startDateRaw,
            'endDateRaw'          => $endDateRaw,
            'studentEntryCount'   => $studentEntryCount,
            'visitorEntryCount'   => $visitorEntryCount,
            'itemReportsCount'    => $itemReportsCount,
            'totalStudentEntries' => $totalStudentEntries,
            'totalVisitorEntries' => $totalVisitorEntries,
            'totalItemReports'    => $totalItemReports,

            // Replaced old entries with the new comprehensive logs array
            'recentLogs'          => $recentLogs
        ];

        return view('Admin/views/dashboard', $data);
    }
}