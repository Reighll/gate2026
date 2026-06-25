<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\AdminModel;

class Profile extends BaseController
{
    public function index()
    {
        $adminModel = new AdminModel();

        // Correctly fetching the admin's ID from the session using 'admin_id'
        $adminId = session()->get('admin_id');

        $data['admin'] = $adminModel->find($adminId);

        // SAFETY NET: If the database finds nothing (e.g., session expired), redirect gracefully.
        if (!$data['admin']) {
            return redirect()->to('admin/login')->with('error', 'Session expired or profile not found. Please log in again.');
        }

        return view('Admin/views/profile', $data);
    }

    public function update()
    {
        $adminModel = new AdminModel();

        // Fetching the correct session key here as well
        $adminId = session()->get('admin_id');

        // Safety net to ensure we don't try to update a null ID
        if (!$adminId) {
            return redirect()->to('admin/login')->with('error', 'Session expired. Please log in again.');
        }

        // Fetch current admin data so we can verify the old password if needed
        $admin = $adminModel->find($adminId);

        $updateData = [
            'first_name'  => $this->request->getPost('first_name'),
            'last_name'   => $this->request->getPost('last_name'),
            'email'       => $this->request->getPost('email'),
            'profile_pic' => $this->request->getPost('profile_pic') // <-- Captures the selected avatar!
        ];

        // Handle the new 3-step Password Change
        $currentPassword = $this->request->getPost('current_password');
        $newPassword     = $this->request->getPost('new_password');
        $confirmPassword = $this->request->getPost('confirm_password');

        if (!empty($newPassword)) {
            // Verify current password first
            if (empty($currentPassword) || !password_verify($currentPassword, $admin['password'])) {
                return redirect()->back()->withInput()->with('error', 'Incorrect current password. Profile was not updated.');
            }

            // Ensure new password and confirm password match
            if ($newPassword !== $confirmPassword) {
                return redirect()->back()->withInput()->with('error', 'New passwords do not match. Please try again.');
            }

            // Hash and set the new password
            $updateData['password'] = password_hash($newPassword, PASSWORD_DEFAULT);
        }

        // Update the database
        $adminModel->update($adminId, $updateData);

        // UPDATE THE SESSION: This forces the navbar to instantly show the new name and avatar
        session()->set([
            'admin_name'  => $updateData['first_name'] . ' ' . $updateData['last_name'],
            'profile_pic' => $updateData['profile_pic']
        ]);

        return redirect()->to('admin/profile')->with('success', 'Profile updated successfully.');
    }
}