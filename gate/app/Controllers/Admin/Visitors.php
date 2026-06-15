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

        // Stats
        $total_tags = $tagModel->countAllResults();

        // Auto-Generate Label: "Visitor Pass X"
        // We find the highest number in existing passes to assume the next one
        // (This prevents "Visitor Pass 5" appearing twice if you deleted #4)
        $next_number = $total_tags + 1;
        $next_label  = "Visitor Pass " . $next_number;

        $data = [
            'visitors_inside' => $logModel->where('status', 'active')->countAllResults(),
            'slots_available' => $tagModel->where('status', 'available')->countAllResults(),
            'total_tags'      => $total_tags,
            'next_label'      => $next_label,
            'logs'            => $logModel->orderBy('time_in', 'DESC')->findAll(),
            'tags'            => $tagModel->findAll()
        ];

        return view('Admin/views/visitors', $data);
    }

    public function addTag()
    {
        $tagModel = new VisitorTagModel();
        $rfid = $this->request->getPost('rfid_uid');

        // --- VALIDATION START ---

        // 1. Check if this specific RFID is already in the database
        $existingTag = $tagModel->where('rfid_uid', $rfid)->first();

        if ($existingTag) {
            // STOP! It is a duplicate.
            return redirect()->back()->with('error', 'DUPLICATE: This card is already registered as "' . $existingTag['pass_number'] . '"');
        }

        // --- VALIDATION END ---

        $data = [
            'pass_number' => $this->request->getPost('pass_number'),
            'rfid_uid'    => $rfid,
            'status'      => 'available'
        ];

        $tagModel->save($data);

        return redirect()->to('admin/visitors')->with('success', 'New Visitor Pass registered successfully.');
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

        // Verify the log exists
        if ($logModel->find($id)) {
            $logModel->update($id, [
                'time_out' => date('Y-m-d H:i:s'),
                'status'   => 'checked_out' // Changes it from 'active' to checked out
            ]);

            return redirect()->to('admin/visitors')->with('success', 'Visitor has been manually checked out.');
        }

        return redirect()->to('admin/visitors')->with('error', 'Visitor log not found.');
    }
}