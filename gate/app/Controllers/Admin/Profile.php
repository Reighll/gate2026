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

        $updateData = [
            'first_name' => $this->request->getPost('first_name'),
            'last_name'  => $this->request->getPost('last_name'),
            'email'      => $this->request->getPost('email'),
        ];

        // Only hash and update the password if the user actually typed a new one
        $newPassword = $this->request->getPost('password');
        if (!empty($newPassword)) {
            $updateData['password'] = password_hash($newPassword, PASSWORD_DEFAULT);
        }

        $adminModel->update($adminId, $updateData);

        return redirect()->to('admin/profile')->with('success', 'Profile updated successfully.');
    }
}