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

        // --- RATE LIMIT: max 5 failed login attempts per 3-minute window, per IP ---
        $cache = \Config\Services::cache();
        // md5() so IPv6 addresses (which contain ':', a reserved cache-key
        // character) don't throw InvalidArgumentException on every attempt.
        $cacheKey = 'login_attempts_guard_' . md5($this->request->getIPAddress());
        $record = $cache->get($cacheKey);
        $now = time();

        if (!$record || ($now - $record['first_attempt_at']) >= 180) {
            $record = ['count' => 0, 'first_attempt_at' => $now];
        }

        if ($record['count'] >= 5) {
            $secondsLeft = 180 - ($now - $record['first_attempt_at']);
            return redirect()->back()->with('error', "Too many failed login attempts. Please try again in " . max(1, $secondsLeft) . " seconds.");
        }
        // --- END RATE LIMIT CHECK (failure is registered below) ---

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
                    'guard_profile_pic'      => $guard['profile_pic'],
                    'guard_logged_in' => true,
                ];
                $session->set($ses_data);
                $cache->delete($cacheKey); // clear the failed-attempt counter on success

                // Update Last Login Time
                $model->update($guard['id'], ['last_login' => date('Y-m-d H:i:s')]);

                return redirect()->to('guard/dashboard');
            } else {
                $record['count']++;
                $cache->save($cacheKey, $record, 190);
                $attemptsLeft = 5 - $record['count'];
                $msg = $attemptsLeft > 0
                    ? "Incorrect password. You have {$attemptsLeft} attempt(s) left before you're locked out for 3 minutes."
                    : "Incorrect password. Too many failed attempts — please try again in 3 minutes.";
                return redirect()->back()->with('error', $msg);
            }
        } else {
            $record['count']++;
            $cache->save($cacheKey, $record, 190);
            $attemptsLeft = 5 - $record['count'];
            $msg = $attemptsLeft > 0
                ? "Username not found. You have {$attemptsLeft} attempt(s) left before you're locked out for 3 minutes."
                : "Username not found. Too many failed attempts — please try again in 3 minutes.";
            return redirect()->back()->with('error', $msg);
        }
    }

    public function logout()
    {
        session()->destroy();
        return redirect()->to('guard/login');
    }
}