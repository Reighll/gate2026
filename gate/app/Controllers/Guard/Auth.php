<?php

namespace App\Controllers\Guard;

use App\Controllers\BaseController;
use App\Models\GuardModel;

class Auth extends BaseController
{
    public function index()
    {
        // If already logged in, go to dashboard (we will build this next)
        if (session()->get('guard_logged_in')) {
            return redirect()->to('guard/dashboard');
        }
        return view('Guard/auth/login');
    }

    public function login()
    {
        $session = session();
        $model   = new GuardModel();

        $username = $this->request->getPost('username');
        $password = $this->request->getPost('password');

        // 1. Find Guard by Username
        $guard = $model->where('username', $username)->first();

        if ($guard) {
            // 2. Verify Password & Status
            if (password_verify($password, $guard['password'])) {
                if ($guard['status'] !== 'active') {
                    return redirect()->back()->with('error', 'Your account is suspended.');
                }

                // 3. Set Session
                $ses_data = [
                    'guard_id'        => $guard['id'],
                    'guard_name'      => $guard['first_name'] . ' ' . $guard['last_name'],
                    'guard_username'  => $guard['username'],
                    'profile_pic'      => $guard['profile_pic'],
                    'guard_logged_in' => true,
                ];
                $session->set($ses_data);

                // Update Last Login Time
                $model->update($guard['id'], ['last_login' => date('Y-m-d H:i:s')]);

                return redirect()->to('guard/dashboard');
            } else {
                return redirect()->back()->with('error', 'Incorrect password.');
            }
        } else {
            return redirect()->back()->with('error', 'Username not found.');
        }
    }

    public function logout()
    {
        session()->destroy();
        return redirect()->to('guard/login');
    }
}