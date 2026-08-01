<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;

class ItemReports extends BaseController
{
    public function index()
    {
        $db = \Config\Database::connect();

        // ---- Missing Items filter state ----
        $missingFilter = $this->request->getGet('missing_filter') ?: '7days';
        $missingStart  = $this->request->getGet('missing_start_date');
        $missingEnd    = $this->request->getGet('missing_end_date');

        // ---- Resolved Items filter state ----
        $resolvedFilter = $this->request->getGet('resolved_filter') ?: '7days';
        $resolvedStart  = $this->request->getGet('resolved_start_date');
        $resolvedEnd    = $this->request->getGet('resolved_end_date');

        // ---- Missing Items query ----
        $missingBuilder = $db->table('student_items');
        $missingBuilder->select('student_items.*, students.first_name, students.last_name');
        $missingBuilder->join('students', 'students.id = student_items.student_id', 'left');
        $missingBuilder->where('student_items.status', 'missing');
        $this->applyDateFilter($missingBuilder, 'student_items.updated_at', $missingFilter, $missingStart, $missingEnd);
        $missingBuilder->orderBy('student_items.updated_at', 'DESC');
        $missingReports = $missingBuilder->get()->getResultArray();

        // ---- Resolved Items query (from item_logs history) ----
        $resolvedBuilder = $db->table('item_logs');
        $resolvedBuilder->select('item_logs.id as log_id, item_logs.created_at as resolved_at, student_items.id, student_items.brand_model, student_items.name, student_items.item_name, student_items.serial_number, students.first_name, students.last_name');
        $resolvedBuilder->join('student_items', 'student_items.id = item_logs.item_id', 'left');
        $resolvedBuilder->join('students', 'students.id = student_items.student_id', 'left');
        $resolvedBuilder->where('item_logs.action', 'resolved');
        $this->applyDateFilter($resolvedBuilder, 'item_logs.created_at', $resolvedFilter, $resolvedStart, $resolvedEnd);
        $resolvedBuilder->orderBy('item_logs.created_at', 'DESC');
        $resolvedReports = $resolvedBuilder->get()->getResultArray();

        $activeMissingCount = $db->table('student_items')->where('status', 'missing')->countAllResults();
        $resolvedCount      = $db->table('item_logs')->where('action', 'resolved')->countAllResults();

        $data = [
            'title'              => 'Reported Items',
            'missingReports'     => $missingReports,
            'resolvedReports'    => $resolvedReports,
            'activeMissingCount' => $activeMissingCount,
            'resolvedCount'      => $resolvedCount,
            'missingFilter'      => $missingFilter,
            'missingStartRaw'    => $missingStart,
            'missingEndRaw'      => $missingEnd,
            'resolvedFilter'     => $resolvedFilter,
            'resolvedStartRaw'   => $resolvedStart,
            'resolvedEndRaw'     => $resolvedEnd,
        ];

        return view('Admin/views/item_reports', $data);
    }

    public function resolve($id)
    {
        $db = \Config\Database::connect();

        $db->table('student_items')->where('id', $id)->update([
            'status'      => 'approved',
            'resolved_at' => date('Y-m-d H:i:s')
        ]);

        $db->table('item_logs')->insert([
            'item_id'    => $id,
            'guard_id'   => null,
            'action'     => 'resolved',
            'created_at' => date('Y-m-d H:i:s'),
        ]);

        return redirect()->back()->with('success', 'Report marked as resolved. The item is now active again.');
    }

    private function applyDateFilter($builder, string $column, string $filter, ?string $start, ?string $end)
    {
        switch ($filter) {
            case 'today':
                $builder->where("$column >=", date('Y-m-d 00:00:00'));
                break;
            case '7days':
                $builder->where("$column >=", date('Y-m-d H:i:s', strtotime('-7 days')));
                break;
            case 'month':
                $builder->where("$column >=", date('Y-m-01 00:00:00'));
                break;
            case 'year':
                $builder->where("$column >=", date('Y-01-01 00:00:00'));
                break;
            case 'custom':
                if (!empty($start)) {
                    $builder->where("$column >=", $start . ' 00:00:00');
                }
                if (!empty($end)) {
                    $builder->where("$column <=", $end . ' 23:59:59');
                }
                break;
        }
    }
}