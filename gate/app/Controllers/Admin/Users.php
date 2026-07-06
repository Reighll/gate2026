<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\StudentModel;
use App\Models\GuardModel;
use App\Models\AdminModel;

class Users extends BaseController
{
    public function index()
    {
        $studentModel = new StudentModel();
        $guardModel   = new GuardModel();
        $adminModel   = new AdminModel();

        $data = [
            'students' => $studentModel->orderBy('created_at', 'DESC')->findAll(),
            'guards'   => $guardModel->orderBy('created_at', 'DESC')->findAll(),
            'admins'   => $adminModel->findAll()
        ];

        return view('Admin/views/users', $data);
    }

    public function createStudent()
    {
        $studentModel = new \App\Models\StudentModel();

        // 1. Validate Input (Ensures no duplicate student numbers)
        $rules = [
            'student_number' => 'required|is_unique[students.student_number]',
            'first_name'     => 'required',
            'last_name'      => 'required',
            'password'       => 'required|min_length[6]'
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->with('error', 'Error: Student Number is already registered or password is too short.');
        }

        // 2. Prepare Data
        $data = [
            'student_number' => $this->request->getPost('student_number'),
            'first_name'     => $this->request->getPost('first_name'),
            'last_name'      => $this->request->getPost('last_name'),
            'email'          => $this->request->getPost('email'),
            // Securely hash the password!
            'password'       => password_hash($this->request->getPost('password'), PASSWORD_DEFAULT),
            'status'         => 'active',
            'created_at'     => date('Y-m-d H:i:s')
        ];

        // 3. Save to Database
        $studentModel->insert($data);

        return redirect()->to('admin/users')->with('success', 'New Student account created successfully.');
    }

    public function deleteStudent($id = null)
    {
        $studentModel = new StudentModel();

        // Verify the student actually exists before trying to delete
        if ($studentModel->find($id)) {
            $studentModel->delete($id);
            // Redirect back with a success message
            return redirect()->to('/admin/users')->with('success', 'Student successfully deleted.');
        }

        // If they don't exist, redirect with an error
        return redirect()->to('/admin/users')->with('error', 'Student not found.');
    }

    /**
     * Handle the Change Password modal submission
     * Redirects to the updateStudent handler to keep updates consolidated
     */
    public function editStudent($id = null)
    {
        return $this->updateStudent($id);
    }

    /**
     * Process the modal form submission and update the student details & optional password
     */
    public function updateStudent($id = null)
    {
        $studentModel = new StudentModel();

        // Ensure the student exists
        if (!$studentModel->find($id)) {
            return redirect()->to('/admin/users')->with('error', 'Student not found.');
        }

        // Grab data from the modal form POST request (Including student_number!)
        $updateData = [
            'first_name'     => $this->request->getPost('first_name'),
            'last_name'      => $this->request->getPost('last_name'),
            'student_number' => $this->request->getPost('student_number'),
            'email'          => $this->request->getPost('email'),
        ];

        // Safe check: Only save Department & Year Level if they are present in the submitted request
        if ($this->request->getPost('department') !== null) {
            $updateData['department'] = $this->request->getPost('department');
        }
        if ($this->request->getPost('year_level') !== null) {
            $updateData['year_level'] = $this->request->getPost('year_level');
        }

        // Check if a password was provided. If YES, securely hash it. If NO, leave current password as-is.
        $newPassword = $this->request->getPost('password') ?? $this->request->getPost('reset_password');
        if (!empty($newPassword)) {
            $updateData['password'] = password_hash($newPassword, PASSWORD_DEFAULT);
        }

        // Perform the update
        $studentModel->update($id, $updateData);

        // Redirect back to the users management page
        return redirect()->to('/admin/users')->with('success', 'Student updated successfully.');
    }

    // -------------------------------------------------------------------------
    // Create Guard Logic
    // -------------------------------------------------------------------------
    public function createGuard()
    {
        $guardModel = new GuardModel();

        // 1. Validate Input
        $rules = [
            'username' => 'required|min_length[4]|is_unique[guards.username]',
            'password' => 'required|min_length[6]',
            'first_name' => 'required',
            'last_name'  => 'required'
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->with('error', 'Error: Username taken or password too short.');
        }

        // 2. Prepare Data
        $data = [
            'first_name' => $this->request->getPost('first_name'),
            'last_name'  => $this->request->getPost('last_name'),
            'username'   => $this->request->getPost('username'),
            // Securely hash the password
            'password'   => password_hash($this->request->getPost('password'), PASSWORD_DEFAULT),
            'status'     => 'active',
            'created_at' => date('Y-m-d H:i:s')
        ];

        // 3. Save to Database
        $guardModel->insert($data);

        return redirect()->to('admin/users')->with('success', 'New Guard account created successfully.');
    }

    // Optional: Delete Guard
    public function deleteGuard($id = null)
    {
        $guardModel = new GuardModel();

        if ($guardModel->find($id)) {
            $guardModel->delete($id);
            return redirect()->to('/admin/users')->with('success', 'Guard successfully deleted.');
        }

        return redirect()->to('/admin/users')->with('error', 'Guard not found.');
    }
    // -------------------------------------------------------------------------
    // Guard Update Logic
    // -------------------------------------------------------------------------
    public function editGuard($id = null)
    {
        $guardModel = new GuardModel();

        if (!$guardModel->find($id)) {
            return redirect()->to('/admin/users')->with('error', 'Guard not found.');
        }

        $updateData = [
            'first_name' => $this->request->getPost('first_name'),
            'last_name'  => $this->request->getPost('last_name'),
            'username'   => $this->request->getPost('username'),
        ];

        // Only update password if provided
        $newPassword = $this->request->getPost('password');
        if (!empty($newPassword)) {
            $updateData['password'] = password_hash($newPassword, PASSWORD_DEFAULT);
        }

        $guardModel->update($id, $updateData);
        return redirect()->to('/admin/users')->with('success', 'Guard updated successfully.');
    }

    // -------------------------------------------------------------------------
    // Admin Management Logic
    // -------------------------------------------------------------------------

    // NEW: Generate Admin Invite Key Logic
    public function generateAdminKey()
    {
        // Security check
        $adminId = session()->get('admin_id');
        if (!$adminId) {
            return $this->response->setJSON(['success' => false, 'message' => 'Unauthorized']);
        }

        // Use your specific Model
        $keyModel = new \App\Models\AdminKeyModel();

        // Generate a random 6-character hex string
        $randomCode = strtoupper(substr(bin2hex(random_bytes(4)), 0, 6));
        $finalKey = 'GATE-ADM-' . $randomCode;

        // Save to your database mapping to your exact columns
        $keyModel->insert([
            'key_code'     => $finalKey,
            'generated_by' => $adminId,
            'status'       => 'unused' // Note: Change to 0 if your database uses a boolean
        ]);

        return $this->response->setJSON([
            'success' => true,
            'key'     => $finalKey,
            'message' => 'Key generated successfully!'
        ]);
    }

    public function createAdmin()
    {
        $adminModel = new AdminModel();

        $rules = [
            'username' => 'required|is_unique[admins.username]',
            'password' => 'required|min_length[6]',
            'first_name' => 'required',
            'last_name'  => 'required'
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->with('error', 'Invalid input or username taken.');
        }

        $data = [
            'first_name' => $this->request->getPost('first_name'),
            'last_name'  => $this->request->getPost('last_name'),
            'username'   => $this->request->getPost('username'),
            'email'      => $this->request->getPost('email'),
            'password'   => password_hash($this->request->getPost('password'), PASSWORD_DEFAULT),
        ];

        $adminModel->insert($data);
        return redirect()->to('admin/users')->with('success', 'Admin created successfully.');
    }

    public function editAdmin($id = null)
    {
        $adminModel = new AdminModel();

        if (!$adminModel->find($id)) {
            return redirect()->to('/admin/users')->with('error', 'Admin not found.');
        }

        $updateData = [
            'first_name' => $this->request->getPost('first_name'),
            'last_name'  => $this->request->getPost('last_name'),
            'username'   => $this->request->getPost('username'),
            'email'      => $this->request->getPost('email'),
        ];

        $newPassword = $this->request->getPost('password');
        if (!empty($newPassword)) {
            $updateData['password'] = password_hash($newPassword, PASSWORD_DEFAULT);
        }

        $adminModel->update($id, $updateData);
        return redirect()->to('/admin/users')->with('success', 'Admin updated successfully.');
    }

    public function deleteAdmin($id = null)
    {
        $adminModel = new AdminModel();

        // Prevent deleting the main admin account if necessary
        $admin = $adminModel->find($id);
        if ($admin && $admin['username'] !== 'admin') {
            $adminModel->delete($id);
            return redirect()->to('/admin/users')->with('success', 'Admin deleted.');
        }

        return redirect()->to('/admin/users')->with('error', 'Cannot delete this admin.');
    }
}