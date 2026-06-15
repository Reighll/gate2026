<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\AdminModel;

class Auth extends BaseController
{
    public function index()
    {
        // If already logged in, redirect to dashboard
        if (session()->get('is_admin_logged_in')) {
            return redirect()->to('admin/dashboard');
        }

        return view('Admin/auth/login');
    }

    public function attemptLogin()
    {
        $session = session();
        $model   = new AdminModel();

        // 1. Get Form Input
        $username = $this->request->getPost('username');
        $password = $this->request->getPost('password');

        // 2. Find the user by Username
        $admin = $model->where('username', $username)->first();

        // 3. Verify User Exists + Password is Correct
        if ($admin) {

            // Check Password Hash
            if (password_verify($password, $admin['password'])) {

                // Check if Account is Suspended
                if ($admin['status'] !== 'active') {
                    $session->setFlashdata('error', 'This account has been suspended.');
                    return redirect()->back()->withInput();
                }

                // 4. Success! Set Session Data
                $ses_data = [
                    'admin_id'           => $admin['id'],
                    'admin_name'         => $admin['first_name'] . ' ' . $admin['last_name'],
                    'admin_username'     => $admin['username'],
                    'is_admin_logged_in' => true,
                ];
                $session->set($ses_data);

                // Update "Last Login" timestamp
                $model->update($admin['id'], ['last_login' => date('Y-m-d H:i:s')]);

                // Redirect to Dashboard
                return redirect()->to('admin/dashboard');
            }
        }

        // 5. Failure
        $session->setFlashdata('error', 'Invalid username or password.');
        return redirect()->back()->withInput();
    }

    public function logout()
    {
        session()->destroy();
        return redirect()->to('admin/login');
    }
}