<?php

namespace App\Controllers\Student;

use App\Controllers\BaseController;
use App\Models\StudentModel;

class Auth extends BaseController
{
    public function index()
    {
        if (session()->get('student_logged_in')) return redirect()->to('student/dashboard');
        return view('Student/auth/login');
    }

    public function register()
    {
        if (session()->get('student_logged_in')) return redirect()->to('student/dashboard');
        return view('Student/auth/login');
    }

    public function login()
    {
        $model = new StudentModel();

        // CHANGED: We now get 'student_number' from your new login form instead of 'email'
        $studentNumber = $this->request->getPost('student_number');
        $password = $this->request->getPost('password');

        // CHANGED: Search the database by student_number
        $student = $model->where('student_number', $studentNumber)->first();

        if ($student && password_verify($password, $student['password'])) {

            // SECURITY CHECK: Is the email verified?
            if ($student['is_verified'] == 0) {
                // Generate a resend link and display it INSIDE the error alert!
                $resendUrl = base_url('student/resendVerification/' . urlencode($student['email']));
                return redirect()->back()->withInput()->with('error', "Please verify your GSFE email address first.<br><br><a href='{$resendUrl}' class='btn btn-sm btn-danger fw-bold mt-2 shadow-sm'>Resend Verification Email</a>");
            }

            // Set session data
            $sessionData = [
                'student_id'         => $student['id'],
                'student_name'       => $student['first_name'] . ' ' . $student['last_name'],
                'student_number'     => $student['student_number'],
                'profile_pic'      => $student['profile_pic'],
                'student_logged_in'  => true,
            ];
            session()->set($sessionData);

            return redirect()->to('student/dashboard')->with('success', 'Welcome back!');
        }

        return redirect()->back()->withInput()->with('error', 'Invalid email or password.');
    }

    public function save()
    {
        $model = new StudentModel();

        $fullEmail = $this->request->getPost('email');
        $existingStudent = $model->where('email', $fullEmail)->first();

        // 1. Generate a random secure token
        $verifyToken = bin2hex(random_bytes(24));

        $data = [
            'first_name'     => $this->request->getPost('first_name'),
            'last_name'      => $this->request->getPost('last_name'),
            'student_number' => $this->request->getPost('student_number'),
            'email'          => $fullEmail,
            'password'       => password_hash($this->request->getPost('password'), PASSWORD_DEFAULT),
            'department'     => $this->request->getPost('department'),
            'year_level'     => $this->request->getPost('year_level'),
            'is_verified'    => 0,
            'verify_token'   => $verifyToken
        ];

        // SMART REGISTRATION: Overwrite unverified accounts instead of blocking them!
        if ($existingStudent) {
            if ($existingStudent['is_verified'] == 1) {
                return redirect()->back()->withInput()->with('error', 'This email is already registered and verified. Please log in.');
            } else {
                // Update their existing pending record with the fresh token
                $model->update($existingStudent['id'], $data);
            }
        } else {
            // Ensure the student number isn't taken by another account
            if ($model->where('student_number', $data['student_number'])->first()) {
                return redirect()->back()->withInput()->with('error', 'This Student Number is already registered.');
            }
            $model->insert($data);
        }

        // 2. Send the Verification Email
        $emailSent = $this->_sendVerificationEmail($fullEmail, $data['first_name'], $verifyToken);

        if ($emailSent) {
            return redirect()->to('student/login')->with('success', 'Registration successful! Please check your GSFE email inbox (or spam) to verify your account before logging in.');
        } else {
            return redirect()->to('student/login')->with('error', 'Registered, but the email failed to send. Check your SMTP settings.');
        }
    }

    // NEW: Private helper method so we don't repeat the long email code twice!
    private function _sendVerificationEmail($email, $name, $token)
    {
        $emailService = \Config\Services::email();
        $emailService->setTo($email);
        $emailService->setSubject('Verify your Gatepass Account');

        $verifyUrl = base_url('student/verifyEmail/' . $token);

        $message = "
            <div style='font-family: Arial, sans-serif; max-width: 600px; margin: 0 auto; padding: 20px; border: 1px solid #e0e0e0; border-radius: 10px;'>
                <h2 style='color: #1e88e5;'>Welcome to GATE!</h2>
                <p style='font-size: 16px;'>Hi {$name},</p>
                <p style='font-size: 16px; color: #555;'>Please click the button below to verify your student email address and activate your account:</p>
                <br>
                <a href='{$verifyUrl}' style='background-color: #1e88e5; color: white; padding: 12px 25px; text-decoration: none; border-radius: 5px; font-weight: bold; display: inline-block;'>Verify Email Address</a>
                <br><br>
                <p style='font-size: 14px; color: #999;'>If the button doesn't work, copy and paste this link into your browser:<br> <a href='{$verifyUrl}'>{$verifyUrl}</a></p>
            </div>
        ";

        $emailService->setMessage($message);

        if ($emailService->send()) {
            return true;
        } else {
            log_message('error', 'Verification email failed to send to: ' . $email . '. Error: ' . $emailService->printDebugger(['headers']));
            return false;
        }
    }

    public function verifyEmail($token = null)
    {
        if (empty($token)) {
            return redirect()->to('student/login')->with('error', 'Invalid verification link.');
        }

        $model = new StudentModel();
        $student = $model->where('verify_token', $token)->first();

        if ($student) {
            $model->update($student['id'], [
                'is_verified'  => 1,
                'verify_token' => null
            ]);

            return redirect()->to('student/login')->with('success', 'Your email has been successfully verified! You may now log in.');
        }

        return redirect()->to('student/login')->with('error', 'This verification link is invalid or has already been used.');
    }

    // NEW: Dedicated Resend Method
    public function resendVerification($email = null)
    {
        if (empty($email)) return redirect()->to('student/login');

        $email = urldecode($email);
        $model = new StudentModel();
        $student = $model->where('email', $email)->first();

        if ($student && $student['is_verified'] == 0) {
            $verifyToken = bin2hex(random_bytes(24));
            $model->update($student['id'], ['verify_token' => $verifyToken]);

            $emailSent = $this->_sendVerificationEmail($email, $student['first_name'], $verifyToken);

            if ($emailSent) {
                return redirect()->to('student/login')->with('success', 'A new verification link has been sent! Please check your inbox/spam folder.');
            } else {
                return redirect()->to('student/login')->with('error', 'Failed to resend the verification email. Check SMTP settings.');
            }
        }

        return redirect()->to('student/login')->with('error', 'This account is already verified or does not exist.');
    }

    public function logout()
    {
        session()->destroy();
        return redirect()->to('student/login');
    }
}