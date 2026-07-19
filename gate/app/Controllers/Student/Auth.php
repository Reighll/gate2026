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

        // --- RATE LIMIT: max 5 failed login attempts per 3-minute window, per IP ---
        $cache = \Config\Services::cache();
        $cacheKey = 'login_attempts_student_' . $this->request->getIPAddress();
        $record = $cache->get($cacheKey);
        $now = time();

        if (!$record || ($now - $record['first_attempt_at']) >= 180) {
            $record = ['count' => 0, 'first_attempt_at' => $now];
        }

        if ($record['count'] >= 5) {
            $secondsLeft = 180 - ($now - $record['first_attempt_at']);
            return redirect()->back()->withInput()->with('error', "Too many failed login attempts. Please try again in " . max(1, $secondsLeft) . " seconds.");
        }
        // --- END RATE LIMIT CHECK (failure is registered below) ---

        // CHANGED: Search the database by student_number
        $student = $model->where('student_number', $studentNumber)->first();

        if ($student && password_verify($password, $student['password'])) {

            // SECURITY CHECK: Is the email verified?
            if ($student['is_verified'] == 0) {
                $resendUrl = base_url('student/resendVerification/' . urlencode($student['email']));
                return redirect()->back()->withInput()->with('error', "Please verify your GSFE email address first.<br><br><a href='{$resendUrl}' class='btn btn-sm btn-danger fw-bold mt-2 shadow-sm'>Resend Verification Email</a>");
            }

            $sessionData = [
                'student_id'         => $student['id'],
                'student_name'       => $student['first_name'] . ' ' . $student['last_name'],
                'student_number'     => $student['student_number'],
                'profile_pic'      => $student['profile_pic'],
                'student_logged_in'  => true,
            ];
            session()->set($sessionData);
            $cache->delete($cacheKey); // clear the failed-attempt counter on success

            return redirect()->to('student/dashboard')->with('success', 'Welcome back!');
        }

        $record['count']++;
        $cache->save($cacheKey, $record, 190);
        $attemptsLeft = 5 - $record['count'];
        $msg = $attemptsLeft > 0
            ? "Invalid student number or password. You have {$attemptsLeft} attempt(s) left before you're locked out for 3 minutes."
            : "Invalid student number or password. Too many failed attempts — please try again in 3 minutes.";
        return redirect()->back()->withInput()->with('error', $msg);
    }

    public function save()
    {
        $model = new StudentModel();

        $studentNumber = $this->request->getPost('student_number');
        if (!preg_match('/^TUPT-\d{2}-\d{4}$/', $studentNumber)) {
            return redirect()->back()->withInput()->with('error', 'Please enter a valid Student Number in the format TUPT-XX-XXXX.');
        }

        $fullEmail = $this->request->getPost('email');
        if (!preg_match('/^[^@\s]+@tup\.edu\.ph$/i', $fullEmail)) {
            return redirect()->back()->withInput()->with('error', 'Please use a valid @tup.edu.ph email address.');
        }

        $password = $this->request->getPost('password');
        if (empty($password) || strlen($password) < 8) {
            return redirect()->back()->withInput()->with('error', 'Password must be at least 8 characters long.');
        }

        // CHANGED: Look up by BOTH student_number and email. student_number is the
        // real, stable identity (school-issued, can't be "mistyped into someone else's"
        // account the way a free-text email can), so it takes priority over email when
        // deciding whether this is a genuine duplicate or just a retry/typo-fix.
        $existingByNumber = $model->where('student_number', $studentNumber)->first();
        $existingByEmail  = $model->where('email', $fullEmail)->first();

        // 1. Generate a random secure token
        $verifyToken = bin2hex(random_bytes(24));

        $data = [
            'first_name'     => $this->request->getPost('first_name'),
            'last_name'      => $this->request->getPost('last_name'),
            'student_number' => $studentNumber,
            'email'          => $fullEmail,
            'password'       => password_hash($this->request->getPost('password'), PASSWORD_DEFAULT),
            'department'     => $this->request->getPost('department'),
            'year_level'     => $this->request->getPost('year_level'),
            'is_verified'    => 0,
            'verify_token'   => $verifyToken
        ];

        // Block only on a genuinely VERIFIED conflict — either identity.
        if ($existingByNumber && $existingByNumber['is_verified'] == 1) {
            return redirect()->back()->withInput()->with('error', 'This Student Number is already registered and verified. Please log in.');
        }
        if ($existingByEmail && $existingByEmail['is_verified'] == 1) {
            return redirect()->back()->withInput()->with('error', 'This email is already registered and verified. Please log in.');
        }

        // SMART REGISTRATION: Overwrite unverified accounts instead of blocking them!
        // Prefer the row matched by student_number (their real identity) as the one to
        // update, since that's what a typo'd email would have orphaned.
        $rowToOverwrite = $existingByNumber ?: $existingByEmail;

        if ($rowToOverwrite) {
            $model->update($rowToOverwrite['id'], $data);

            // If number and email matched two DIFFERENT unverified rows (e.g. they typo'd
            // the email once, then typo'd the number on a later attempt), we just merged
            // into one — delete the other leftover so it doesn't block future retries.
            if ($existingByNumber && $existingByEmail && $existingByNumber['id'] !== $existingByEmail['id']) {
                $model->delete($existingByEmail['id']);
            }
        } else {
            $model->insert($data);
        }

        // 2. Send the Verification Email
        $emailSent = $this->_sendVerificationEmail($fullEmail, $data['first_name'], $verifyToken);

        if ($emailSent) {
            return redirect()->to('student/login')->with('success', 'Registration successful! Please check your TUP email inbox (or spam folder) to verify your account before logging in. If you did not receive the email, the email address you registered may be incorrect. Please try registering again.');
        } else {
            return redirect()->to('student/login')->with('error', 'Registered, but the email failed to send.');
        }
    }

    // NEW: Private helper method so we don't repeat the long email code twice!
    private function _sendVerificationEmail($email, $name, $token)
    {
        $emailService = \Config\Services::email();
        $emailService->setTo($email);
        $emailService->setSubject('Verify your GATE Account');

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

    // ==========================================
    // FORGOT / RESET PASSWORD
    // ==========================================

    public function forgotPassword()
    {
        if (session()->get('student_logged_in')) return redirect()->to('student/dashboard');
        return view('Student/auth/forgot_password');
    }

    public function sendResetLink()
    {
        $model = new StudentModel();
        $email = trim((string) $this->request->getPost('email'));

        if (empty($email)) {
            return redirect()->back()->withInput()->with('error', 'Please enter your email address.');
        }

        $student = $model->where('email', $email)->first();

        // Always show the same generic message whether or not the account exists,
        // so we don't reveal which emails are registered.
        $genericMessage = 'If that email is registered, a password reset link has been sent. Please check your inbox (or spam folder).';

        if ($student) {
            $resetToken = bin2hex(random_bytes(32));
            $expiresAt = date('Y-m-d H:i:s', strtotime('+1 hour'));

            $model->update($student['id'], [
                'reset_token'         => $resetToken,
                'reset_token_expires' => $expiresAt,
            ]);

            $this->_sendResetEmail($email, $student['first_name'], $resetToken);
        }

        return redirect()->to('student/login')->with('success', $genericMessage);
    }

    public function resetPassword($token = null)
    {
        if (empty($token)) {
            return redirect()->to('student/forgot-password')->with('error', 'Invalid or missing reset link.');
        }

        $model = new StudentModel();
        $student = $model->where('reset_token', $token)->first();

        if (!$student || empty($student['reset_token_expires']) || strtotime($student['reset_token_expires']) < time()) {
            return redirect()->to('student/forgot-password')->with('error', 'This reset link is invalid or has expired. Please request a new one.');
        }

        return view('Student/auth/reset_password', ['token' => $token]);
    }

    public function updatePassword()
    {
        $model = new StudentModel();

        $token = $this->request->getPost('token');
        $newPassword = $this->request->getPost('new_password');
        $confirmPassword = $this->request->getPost('confirm_password');

        if (empty($token)) {
            return redirect()->to('student/forgot-password')->with('error', 'Invalid or missing reset link.');
        }

        $student = $model->where('reset_token', $token)->first();

        if (!$student || empty($student['reset_token_expires']) || strtotime($student['reset_token_expires']) < time()) {
            return redirect()->to('student/forgot-password')->with('error', 'This reset link is invalid or has expired. Please request a new one.');
        }

        if (empty($newPassword) || strlen($newPassword) < 8) {
            return redirect()->back()->withInput()->with('error', 'Password must be at least 8 characters long.');
        }

        if ($newPassword !== $confirmPassword) {
            return redirect()->back()->withInput()->with('error', 'Passwords do not match.');
        }

        $model->update($student['id'], [
            'password'            => password_hash($newPassword, PASSWORD_DEFAULT),
            'reset_token'         => null,
            'reset_token_expires' => null,
        ]);

        return redirect()->to('student/login')->with('success', 'Your password has been reset successfully. Please log in.');
    }

    private function _sendResetEmail($email, $name, $token)
    {
        $emailService = \Config\Services::email();
        $emailService->setTo($email);
        $emailService->setSubject('Reset your GATE Account Password');

        $resetUrl = base_url('student/reset-password/' . $token);

        $message = "
            <div style='font-family: Arial, sans-serif; max-width: 600px; margin: 0 auto; padding: 20px; border: 1px solid #e0e0e0; border-radius: 10px;'>
                <h2 style='color: #1e88e5;'>Password Reset Request</h2>
                <p style='font-size: 16px;'>Hi {$name},</p>
                <p style='font-size: 16px; color: #555;'>We received a request to reset your GATE account password. Click the button below to choose a new password. This link will expire in 1 hour.</p>
                <br>
                <a href='{$resetUrl}' style='background-color: #1e88e5; color: white; padding: 12px 25px; text-decoration: none; border-radius: 5px; font-weight: bold; display: inline-block;'>Reset Password</a>
                <br><br>
                <p style='font-size: 14px; color: #999;'>If you didn't request this, you can safely ignore this email — your password will remain unchanged.</p>
                <p style='font-size: 14px; color: #999;'>If the button doesn't work, copy and paste this link into your browser:<br> <a href='{$resetUrl}'>{$resetUrl}</a></p>
            </div>
        ";

        $emailService->setMessage($message);

        if ($emailService->send()) {
            return true;
        } else {
            log_message('error', 'Password reset email failed to send to: ' . $email . '. Error: ' . $emailService->printDebugger(['headers']));
            return false;
        }
    }

    public function logout()
    {
        session()->destroy();
        return redirect()->to('student/login');
    }
}