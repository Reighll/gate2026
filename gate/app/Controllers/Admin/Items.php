<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\StudentItemModel;
use App\Models\StudentModel; // Added to fetch student email addresses

class Items extends BaseController
{
    /**
     * Loads the main items dashboard
     */
    public function index()
    {
        $db = \Config\Database::connect();

        // Fetch all items and join with students table to get their names and numbers
        $builder = $db->table('student_items');
        $builder->select('student_items.*, students.first_name, students.last_name, students.student_number');
        $builder->join('students', 'students.id = student_items.student_id', 'left');
        $builder->orderBy('student_items.created_at', 'DESC');

        $items = $builder->get()->getResultArray();

        // Calculate Overview Counts for the summary cards
        $pendingItemsCount  = $db->table('student_items')->where('status', 'pending')->countAllResults();
        $approvedItemsCount = $db->table('student_items')->where('status', 'approved')->countAllResults();
        $rejectedItemsCount = $db->table('student_items')->where('status', 'rejected')->countAllResults();
        $archivedItemsCount = $db->table('student_items')->where('status', 'archived')->countAllResults();

        $data = [
            'title' => 'Manage Student Items',
            'items' => $items,
            'pendingItemsCount'  => $pendingItemsCount,
            'approvedItemsCount' => $approvedItemsCount,
            'rejectedItemsCount' => $rejectedItemsCount,
            'archivedItemsCount' => $archivedItemsCount
        ];

        return view('Admin/views/items', $data);
    }

    /**
     * Handle the form submission when Admin approves an item and scans the RFID/NFC card
     */
    public function approveItem($id = null)
    {
        $model = new StudentItemModel();
        $db = \Config\Database::connect();

        // 1. Fetch the item to ensure it exists
        $item = $model->find($id);
        if (!$item) {
            return redirect()->to('/admin/items')->with('error', 'Item not found.');
        }

        // Grab the scanned ID using 'rfid' specifically
        $scannedTag = trim((string) $this->request->getPost('rfid'));

        // 2. Check if the RFID tag is empty
        if (empty($scannedTag)) {
            return redirect()->back()->with('error', 'RFID Tag cannot be empty. Please scan a card.');
        }

        // 3. Check if this RFID tag is already assigned to another Student Item
        if ($db->fieldExists('rfid', 'student_items')) {
            $existingItem = $model->where('rfid', $scannedTag)->where('id !=', $id)->first();
            if ($existingItem) {
                return redirect()->back()->with('error', 'This RFID Tag is already assigned to another student item.');
            }
        }

        // 4. Check if this RFID tag belongs to a Visitor
        if ($db->tableExists('visitor_tags')) {
            $visitorColumn = $db->fieldExists('rfid', 'visitor_tags') ? 'rfid' :
                ($db->fieldExists('nfc_tag', 'visitor_tags') ? 'nfc_tag' : null);

            if ($visitorColumn) {
                $visitorCheck = $db->table('visitor_tags')->where($visitorColumn, $scannedTag)->countAllResults();
                if ($visitorCheck > 0) {
                    return redirect()->back()->with('error', 'This RFID Tag is currently assigned to a Visitor and cannot be used for items.');
                }
            }
        }

        // 5. Update the item to 'approved' and link the card
        $model->update($id, [
            'status'  => 'approved',
            'rfid'    => $scannedTag
        ]);

        // ==========================================
        // 📧 SEND AUTOMATED APPROVAL EMAIL
        // ==========================================
        $studentModel = new StudentModel();
        $student = $studentModel->find($item['student_id']);

        if ($student && !empty($student['email'])) {
            $itemName = $item['brand_model'] ?? $item['name'] ?? 'Item';
            $this->_sendItemNotification($student['email'], $student['first_name'], $itemName, 'approved');
        }

        return redirect()->to('/admin/items')->with('success', 'Item approved and linked to RFID Card.');
    }

    /**
     * Process other GET actions like reject, unregister, delete
     */
    public function process($action, $id)
    {
        $model = new StudentItemModel();

        $item = $model->find($id);
        if (!$item) {
            return redirect()->back()->with('error', 'Item not found.');
        }

        // Fetch student info for potential email notifications
        $studentModel = new StudentModel();
        $student = $studentModel->find($item['student_id']);
        $itemName = $item['brand_model'] ?? $item['name'] ?? 'Item';

        // 1. Unregistration Requests Flow
        if ($action === 'approve_unregister') {
            $model->update($id, ['status' => 'archived']);

            // 📧 Send Unregistered Email
            if ($student && !empty($student['email'])) {
                $this->_sendItemNotification($student['email'], $student['first_name'], $itemName, 'archived');
            }
            return redirect()->back()->with('success', 'Unregistration approved. Item moved to Archive.');
        }
        elseif ($action === 'deny_unregister') {
            $model->update($id, ['status' => 'approved']);
            return redirect()->back()->with('error', 'Unregistration denied. Item restored to active status.');
        }

        // 2. Standard Registration Rejections
        elseif ($action === 'reject' || $action === 'decline') {
            $model->update($id, ['status' => 'rejected']);

            // 📧 Send Rejected Email
            if ($student && !empty($student['email'])) {
                $this->_sendItemNotification($student['email'], $student['first_name'], $itemName, 'rejected');
            }
            return redirect()->back()->with('success', 'Item registration rejected.');
        }

        // 3. Force Delete
        elseif ($action === 'delete') {
            $model->delete($id);
            return redirect()->back()->with('success', 'Item permanently deleted from database.');
        }

        return redirect()->back()->with('error', 'Unknown action.');
    }

    /**
     * Helper method to send beautiful HTML status update emails to the student
     */
    private function _sendItemNotification($email, $name, $itemName, $status)
    {
        $emailService = \Config\Services::email();
        $emailService->setTo($email);

        $subject = '';
        $messageBody = '';

        if ($status === 'approved') {
            $subject = 'GATE: Item Approved!';
            $messageBody = "
                <h2 style='color: #39cb7f;'>Item Approved!</h2>
                <p style='font-size: 16px;'>Hi {$name},</p>
                <p style='font-size: 16px; color: #555;'>Great news! The registration for your <strong>{$itemName}</strong> has been approved by the administration.</p>
                <p style='font-size: 16px; color: #555;'>An RFID tag has been successfully assigned to your device. You may now tap your item in and out of the campus.</p>
            ";
        } elseif ($status === 'rejected') {
            $subject = 'GATE: Item Rejected';
            $messageBody = "
                <h2 style='color: #e46a76;'>Item Registration Rejected</h2>
                <p style='font-size: 16px;'>Hi {$name},</p>
                <p style='font-size: 16px; color: #555;'>Unfortunately, the registration for your <strong>{$itemName}</strong> has been rejected by the administration.</p>
                <p style='font-size: 16px; color: #555;'>This may be due to an unclear photo or incorrect serial number. Please visit the admin office for clarification.</p>
            ";
        } elseif ($status === 'archived') {
            $subject = 'GATE: Item Unregistered';
            $messageBody = "
        <h2 style='color: #2a3547;'>Item Unregistration Complete</h2>
        <p style='font-size: 16px;'>Hi {$name},</p>
        <p style='font-size: 16px; color: #555;'>Your request to unregister your <strong>{$itemName}</strong> has been approved.</p>
        <p style='font-size: 16px; color: #555;'>This item has been archived in our system and can no longer be used to enter the campus. If you wish to bring it back in the future, you will need to register it again.</p>
    ";
        }

        // Failsafe
        if (empty($subject)) return false;

        $message = "
            <div style='font-family: Arial, sans-serif; max-width: 600px; margin: 0 auto; padding: 20px; border: 1px solid #e0e0e0; border-radius: 10px;'>
                {$messageBody}
                <br>
                <p style='font-size: 14px; color: #999;'>Thank you,<br>GATE Administration</p>
            </div>
        ";

        $emailService->setSubject($subject);
        $emailService->setMessage($message);

        // Send the email (We don't return false if it fails, so it doesn't interrupt the Admin's UI workflow)
        $emailService->send();

        return true;
    }

    /**
     * AJAX endpoint polled by the Admin Dashboard to fetch the latest ESP32 scan
     */
    public function checkLatestScan()
    {
        $file = WRITEPATH . 'latest_scan.txt';

        if (file_exists($file)) {
            $content = file_get_contents($file);
            $epcs = array_values(array_filter(explode(",", trim($content))));

            if (!empty($epcs)) {
                // STRIP THE COMMA before sending it to the input box!
                $nextEpc = trim(array_shift($epcs), " ,\t\n\r");

                if (!empty($epcs)) {
                    file_put_contents($file, implode(",", $epcs) . ',', LOCK_EX);
                } else {
                    unlink($file);
                }

                return $this->response->setJSON(['status' => 'success', 'epc' => $nextEpc]);
            } else {
                unlink($file);
            }
        }

        return $this->response->setJSON(['status' => 'waiting']);
    }
}