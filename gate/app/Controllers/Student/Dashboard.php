<?php

namespace App\Controllers\Student;

use App\Controllers\BaseController;
use App\Models\StudentModel;
use App\Models\StudentItemModel;

class Dashboard extends BaseController
{
    /**
     * Helper method to ensure auth and get the current student.
     * Keeps the code DRY (Don't Repeat Yourself).
     */
    private function getCurrentStudent()
    {
        if (!session()->get('student_logged_in')) {
            return false;
        }
        $studentModel = new StudentModel();
        return $studentModel->find(session()->get('student_id'));
    }

    // ==========================================
    // SIDEBAR PAGE VIEWS
    // ==========================================

    public function index()
    {
        $session = session();
        log_message('error', 'DASHBOARD HIT - studentId=' . ($session->get('student_id') ?? 'NULL') . ' time=' . date('H:i:s'));
        $studentModel = new StudentModel();

        // 1. Get the logged-in student's ID
        // Checking common session keys you might have used in your Auth controller
        $studentId = $session->get('student_id') ?? $session->get('user_id') ?? $session->get('id');

        // Safety Check: If no session ID is found, redirect to login
        if (!$studentId) {
            return redirect()->to('/student/login')->with('error', 'Please log in to access the dashboard.');
        }

        // 2. Fetch the student details from the database
        $student = $studentModel->find($studentId);

        // Safety Check: If the student doesn't exist in the database, redirect
        if (!$student) {
            $session->destroy();
            return redirect()->to('/student/login')->with('error', 'Student record not found. Please log in again.');
        }

        // 3. Check Campus Status logic
        $db = \Config\Database::connect();

        // Find the most recent log for any item owned by this student
        $latestLog = $db->table('item_logs')
            ->join('student_items', 'student_items.id = item_logs.item_id')
            ->where('student_items.student_id', $studentId)
            ->orderBy('item_logs.created_at', 'DESC')
            ->get()
            ->getRowArray();

        // Default properties for Outside Campus
        $campusStatus = 'Outside Campus';
        $badgeClass   = 'bg-light-danger text-danger';

        if ($latestLog) {
            // Check the action/status of the latest log.
            $action = strtolower($latestLog['action'] ?? $latestLog['status'] ?? '');

            // Adjust 'time_in' to match whatever string you save in the database when they enter
            if (in_array($action, ['time_in', 'in', 'time in', 'entered', '1'])) {
                $campusStatus = 'Inside Campus';
                $badgeClass   = 'bg-light-success text-success';
            }
        }

        // 4. Pass everything to the view
        $data = [
            'title'          => 'Student Dashboard',
            'student'        => $student,     // This passes first_name, last_name, etc. safely
            'campusStatus'   => $campusStatus,
            'badgeClass'     => $badgeClass,
            'showTermsModal' => empty($student['terms_accepted'])
        ];
        /**
         * This page's content depends on session/account state (terms acceptance,
         * campus status), so it must never be served from browser cache or bfcache —
         * otherwise a different account can see a stale snapshot of someone else's state.
         */

        $this->response->setHeader('Cache-Control', 'no-store, no-cache, must-revalidate, max-age=0');
        $this->response->setHeader('Pragma', 'no-cache');

        return view('Student/views/dashboard', $data);
    }

    public function acceptTerms()
    {
        if (!session()->get('student_logged_in')) {
            return $this->response->setStatusCode(403);
        }

        $studentModel = new StudentModel();
        $studentModel->update(session()->get('student_id'), ['terms_accepted' => 1]);

        return $this->response->setJSON(['status' => 'success']);
    }

    public function itemRegistration()
    {
        if (!$this->getCurrentStudent()) return redirect()->to('student/login');

        return view('Student/views/item_registration');
    }

    public function registeredItems()
    {
        $student = $this->getCurrentStudent();
        if (!$student) return redirect()->to('student/login');

        $itemModel = new StudentItemModel();
        $items = $itemModel->where('student_id', $student['id'])->findAll();

        return view('Student/views/registered_items', ['items' => $items]);
    }

    public function removeItem()
    {
        $student = $this->getCurrentStudent();
        if (!$student) return redirect()->to('student/login');

        $itemModel = new StudentItemModel();
        $items = $itemModel->where('student_id', $student['id'])->findAll();

        return view('Student/views/remove_item', ['items' => $items]);
    }

    public function reportItem()
    {
        $student = $this->getCurrentStudent();
        if (!$student) return redirect()->to('student/login');

        $itemModel = new StudentItemModel();
        $items = $itemModel->where('student_id', $student['id'])->findAll();

        return view('Student/views/report_item', ['items' => $items]);
    }

    public function history()
    {
        $student = $this->getCurrentStudent();
        if (!$student) return redirect()->to('student/login');

        $db = \Config\Database::connect();
        $logs = [];

        if ($db->tableExists('item_logs')) {
            $builder = $db->table('item_logs');
            $builder->select('item_logs.*, student_items.brand_model, student_items.serial_number');
            $builder->join('student_items', 'student_items.id = item_logs.item_id');
            $builder->where('student_items.student_id', $student['id']);
            $builder->orderBy('item_logs.created_at', 'DESC');
            $logs = $builder->get()->getResultArray();
        }

        return view('Student/views/history', ['logs' => $logs]);
    }

    // ==========================================
    // PROFILE MANAGEMENT
    // ==========================================

    public function profile()
    {
        $student = $this->getCurrentStudent();
        if (!$student) return redirect()->to('student/login');

        return view('Student/views/profile', ['student' => $student]);
    }

    public function updateProfile()
    {
        if (!session()->get('student_logged_in')) {
            return redirect()->to('student/login');
        }

        $studentModel = new \App\Models\StudentModel();
        $studentId = session()->get('student_id');
        $student = $studentModel->find($studentId);

        $updateData = [
            'first_name' => $this->request->getPost('first_name'),
            'last_name'  => $this->request->getPost('last_name'),
            'email'      => $this->request->getPost('email'),
            'department' => $this->request->getPost('department'),
            'year_level' => $this->request->getPost('year_level'),
        ];

        // 1. Handle Profile Picture Upload
        $file = $this->request->getFile('profile_pic');

        if ($file && $file->isValid() && !$file->hasMoved()) {

            // --- RATE LIMIT: max 3 picture changes per 2-minute window ---
            $cache = \Config\Services::cache();
            $cacheKey = 'profile_pic_attempts_student_' . $studentId;
            $record = $cache->get($cacheKey);
            $now = time();

            // Reset the window if it doesn't exist yet or the 2 minutes have passed
            if (!$record || ($now - $record['first_attempt_at']) >= 120) {
                $record = ['count' => 0, 'first_attempt_at' => $now];
            }

            if ($record['count'] >= 3) {
                $secondsLeft = 120 - ($now - $record['first_attempt_at']);
                return redirect()->back()->withInput()->with('error', "You've reached the limit of 3 profile picture changes. Please try again in " . max(1, $secondsLeft) . " seconds.");
            }

            $record['count']++;
            $cache->save($cacheKey, $record, 130); // stored slightly longer than the window itself
            // --- END RATE LIMIT ---

            $newName = $file->getRandomName();
            $uploadPath = FCPATH . 'uploads/profiles/';

            $file->move($uploadPath, $newName);

            // Resize + compress profile image
            try {
                \Config\Services::image()
                    ->withFile($filepath)
                    ->resize(300, 300, true, 'auto') // good for avatars
                    ->save($filepath, 75);           // balanced quality

                $updateData['profile_pic'] = $newName;

            } catch (\Exception $e) {
                log_message('error', 'Profile Image Compression Failed: ' . $e->getMessage());

                // still save original if compression fails
                $updateData['profile_pic'] = $newName;
            }

            // delete old profile pic
            if (
                !empty($student['profile_pic']) &&
                $student['profile_pic'] != 'default.png' &&
                file_exists($uploadPath . $student['profile_pic'])
            ) {
                unlink($uploadPath . $student['profile_pic']);
            }
        }

        // 2. Handle Password Change
        $currentPassword = $this->request->getPost('current_password');
        $newPassword = $this->request->getPost('new_password');
        $confirmPassword = $this->request->getPost('confirm_password');

        if (!empty($newPassword)) {
            if (empty($currentPassword) || !password_verify($currentPassword, $student['password'])) {
                return redirect()->back()->withInput()->with('error', 'Incorrect current password. Profile was not updated.');
            }
            if ($newPassword !== $confirmPassword) {
                return redirect()->back()->withInput()->with('error', 'New passwords do not match. Please try again.');
            }
            $updateData['password'] = password_hash($newPassword, PASSWORD_DEFAULT);
        }

        $studentModel->update($studentId, $updateData);

        session()->set('student_name', $updateData['first_name'] . ' ' . $updateData['last_name']);
        if (isset($updateData['profile_pic'])) {
            session()->set('student_profile_pic', $updateData['profile_pic']);
        }

        return redirect()->to('student/profile')->with('success', 'Your profile details have been successfully updated.');
    }
}