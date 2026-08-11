<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\VisitorLogModel;
use App\Models\VisitorTagModel;

class Visitors extends BaseController
{
    public function index()
    {
        $logModel = new VisitorLogModel();
        $tagModel = new VisitorTagModel();

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

        // Stats
        $total_tags = $tagModel->countAllResults();

        // Auto-Generate Label: "Visitor Pass X"
        // We find the highest number in existing passes to assume the next one
        // (This prevents "Visitor Pass 5" appearing twice if you deleted #4)
        $next_number = $total_tags + 1;
        $next_label  = "Visitor Pass " . $next_number;

        // Apply the date filter to the history logs only (stats/tags stay all-time)
        $logs = $logModel->orderBy('time_in', 'DESC')
            ->where('time_in >=', $startDate)
            ->where('time_in <=', $endDate)
            ->findAll();

        $data = [
            'visitors_inside' => $logModel->where('status', 'active')->countAllResults(),
            'slots_available' => $tagModel->where('status', 'available')->countAllResults(),
            'total_tags'      => $total_tags,
            'next_label'      => $next_label,
            'filter'          => $filter,
            'startDateRaw'    => $startDateRaw,
            'endDateRaw'      => $endDateRaw,
            'logs'            => $logs,
            'tags'            => $tagModel->findAll()
        ];

        return view('Admin/views/visitors', $data);
    }

    public function addTag()
    {
        $tagModel = new VisitorTagModel();
        $db = \Config\Database::connect();

        // 1. Get the RFID from the scanner
        $rfid = trim((string) $this->request->getPost('rfid'));

        if (empty($rfid)) {
            return redirect()->back()->with('error', 'RFID Tag cannot be empty. Please scan a card.');
        }

        // --- VALIDATION: Duplicate & Student Tag Checks ---
        $existingTag = $tagModel->where('rfid_uid', $rfid)->first();
        if ($existingTag) {
            return redirect()->back()->with('error', 'DUPLICATE: This card is already registered as "' . $existingTag['pass_number'] . '"');
        }

        if ($db->tableExists('student_items') && $db->fieldExists('rfid', 'student_items')) {
            $studentItemCheck = $db->table('student_items')->where('rfid', $rfid)->countAllResults();
            if ($studentItemCheck > 0) {
                return redirect()->back()->with('error', 'DENIED: This RFID Tag is currently assigned to a Student Item.');
            }
        }

        // --- SMART AUTO-NAMING LOGIC ---
        // Fetch all current tags to figure out which numbers are in use
        $allTags = $tagModel->findAll();
        $usedNumbers = [];

        foreach ($allTags as $tag) {
            // Extract the integer from names like "Visitor Pass 1" or "Visitor Pass 4"
            if (preg_match('/Visitor Pass (\d+)/i', $tag['pass_number'], $matches)) {
                $usedNumbers[] = (int)$matches[1];
            }
        }

        // Find the lowest missing number starting from 1
        $nextNumber = 1;
        while (in_array($nextNumber, $usedNumbers)) {
            $nextNumber++;
        }

        $autoPassName = 'Visitor Pass ' . $nextNumber;

        // --- SAVE NEW TAG ---
        $data = [
            'pass_number' => $autoPassName,
            'rfid_uid'    => $rfid,
            'status'      => 'available'
        ];

        $tagModel->save($data);

        return redirect()->to('admin/visitors')->with('success', 'Success! ' . $autoPassName . ' registered.');
    }

    // AJAX Handler: Checks if RFID exists without reloading page
    public function checkTag()
    {
        if (!$this->request->isAJAX()) {
            return $this->response->setStatusCode(403);
        }

        $rfid = $this->request->getGet('rfid');
        $tagModel = new VisitorTagModel();
        $existing = $tagModel->where('rfid_uid', $rfid)->first();

        return $this->response->setJSON([
            'exists' => (bool)$existing,
            'pass_number' => $existing ? $existing['pass_number'] : null
        ]);
    }

    public function deleteTag($id)
    {
        $tagModel = new VisitorTagModel();
        $tagModel->delete($id);
        return redirect()->to('admin/visitors')->with('success', 'Visitor Pass deleted.');
    }

    /**
     * Manually check out a visitor if they forgot to tap their card on the way out.
     */
    public function forceCheckout($id)
    {
        $logModel = new VisitorLogModel();
        $tagModel = new VisitorTagModel();
        $db = \Config\Database::connect();

        $log = $logModel->find($id);

        if ($log) {
            $logModel->update($id, [
                'time_out' => date('Y-m-d H:i:s'),
                'status'   => 'checked_out'
            ]);

            // Free up the tag now that this visitor has been checked out —
            // mirrors the normal tap-out flow in Guard\Dashboard::checkIn().
            $logFields = $db->getFieldNames('visitor_logs');
            $logRfidColumn = in_array('rfid_uid', $logFields) ? 'rfid_uid' : (in_array('rfid', $logFields) ? 'rfid' : null);
            $logRfid = $logRfidColumn ? ($log[$logRfidColumn] ?? null) : null;

            if ($logRfid) {
                $tagModel->where('rfid_uid', $logRfid)->set(['status' => 'available'])->update();
            }

            return redirect()->to('admin/visitors')->with('success', 'Visitor has been manually checked out.');
        }

        return redirect()->to('admin/visitors')->with('error', 'Visitor log not found.');
    }
    public function deleteLog($id)
    {
        $logModel = new \App\Models\VisitorLogModel();

        // Safety check: Does the ID exist?
        if ($logModel->find($id)) {
            $logModel->delete($id);
            return redirect()->to('admin/visitors')->with('success', 'Visitor log entry deleted.');
        }

        return redirect()->to('admin/visitors')->with('error', 'Log entry not found.');
    }
}