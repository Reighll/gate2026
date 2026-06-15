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
        $student = $this->getCurrentStudent();
        if (!$student) return redirect()->to('student/login');

        // Note: The dashboard view file should now ONLY contain the dashboard_home code
        return view('Student/views/dashboard', ['student' => $student]);
    }

    public function itemRegistration()
    {
        if (!$this->getCurrentStudent()) return redirect()->to('student/login');

        return view('Student/views/dashboard_item_registration');
    }

    public function registeredItems()
    {
        $student = $this->getCurrentStudent();
        if (!$student) return redirect()->to('student/login');

        $itemModel = new StudentItemModel();
        $items = $itemModel->where('student_id', $student['id'])->findAll();

        return view('Student/views/dashboard_registered_items', ['items' => $items]);
    }

    public function removeItem()
    {
        $student = $this->getCurrentStudent();
        if (!$student) return redirect()->to('student/login');

        $itemModel = new StudentItemModel();
        $items = $itemModel->where('student_id', $student['id'])->findAll();

        return view('Student/views/dashboard_remove_item', ['items' => $items]);
    }

    public function reportItem()
    {
        $student = $this->getCurrentStudent();
        if (!$student) return redirect()->to('student/login');

        $itemModel = new StudentItemModel();
        $items = $itemModel->where('student_id', $student['id'])->findAll();

        return view('Student/views/dashboard_report_item', ['items' => $items]);
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

        return view('Student/views/dashboard_history', ['logs' => $logs]);
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
        if ($file && $file->isValid() && ! $file->hasMoved()) {
            $newName = $file->getRandomName();
            $file->move(FCPATH . 'uploads/profiles', $newName);
            $updateData['profile_pic'] = $newName;

            if (!empty($student['profile_pic']) && $student['profile_pic'] != 'default.png' && file_exists(FCPATH . 'uploads/profiles/' . $student['profile_pic'])) {
                unlink(FCPATH . 'uploads/profiles/' . $student['profile_pic']);
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
            session()->set('profile_pic', $updateData['profile_pic']);
        }

        return redirect()->to('student/profile')->with('success', 'Your profile details have been successfully updated.');
    }
}