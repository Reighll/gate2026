<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\AdminModel;
use App\Models\AdminKeyModel;

class Auth extends BaseController
{
    public function index()
    {
        // EXACT ORIGINAL: If already logged in, redirect to dashboard
        if (session()->get('is_admin_logged_in')) {
            return redirect()->to('admin/dashboard');
        }

        // NEW: Pass flag for the sliding animation
        return view('Admin/auth/login', ['show_register' => false]);
    }

    // NEW: The Register entry point
    public function register()
    {
        if (session()->get('is_admin_logged_in')) {
            return redirect()->to('admin/dashboard');
        }

        // Pass flag to slide to the register form
        return view('Admin/auth/login', ['show_register' => true]);
    }

    public function attemptLogin()
    {
        // EXACT ORIGINAL LOGIC RESTORED
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

                // 4. Success! Set Session Data (RESTORED is_admin_logged_in)
                $ses_data = [
                    'admin_id'           => $admin['id'],
                    'admin_name'         => $admin['first_name'] . ' ' . $admin['last_name'],
                    'admin_username'     => $admin['username'],
                    'admin_email'        => $admin['email'],
                    'profile_pic'    => $admin['profile_pic'],
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

    // NEW: The logic to handle the invite key registration
    public function attemptRegister()
    {
        $session    = session();
        $adminModel = new AdminModel();
        $keyModel   = new AdminKeyModel();

        // 1. Basic Validation
        $rules = [
            'invite_key' => 'required',
            'first_name' => 'required',
            'last_name'  => 'required',
            'email'      => 'required|valid_email',
            'username'   => 'required|min_length[4]|is_unique[admins.username]',
            'password'   => 'required|min_length[6]'
        ];

        if (!$this->validate($rules)) {
            $session->setFlashdata('error', 'Invalid input or username is already taken.');
            return redirect()->back()->withInput();
        }

        $inviteKey = $this->request->getPost('invite_key');

        // 2. Validate the Invite Key!
        $validKey = $keyModel->where('key_code', $inviteKey)
            ->where('status', 'unused') // Must be unused!
            ->first();

        if (!$validKey) {
            $session->setFlashdata('error', 'Invalid or already used Invite Key.');
            return redirect()->back()->withInput();
        }

        // 3. Create the Admin Account
        $adminData = [
            'first_name' => $this->request->getPost('first_name'),
            'last_name'  => $this->request->getPost('last_name'),
            'email'      => $this->request->getPost('email'),
            'username'   => $this->request->getPost('username'),
            'password'   => password_hash($this->request->getPost('password'), PASSWORD_DEFAULT),
            'status'     => 'active'
        ];

        $adminModel->insert($adminData);

        // 4. Burn the Key (Mark as used)
        $keyModel->update($validKey['id'], [
            'status'  => 'used',
            'used_at' => date('Y-m-d H:i:s')
        ]);

        $session->setFlashdata('success', 'Admin account successfully created! You may now log in.');
        return redirect()->to('admin/login');
    }

    public function logout()
    {
        // EXACT ORIGINAL LOGIC
        session()->destroy();
        return redirect()->to('admin/login');
    }
}