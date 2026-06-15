<?php

namespace App\Filters;

use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\Filters\FilterInterface;

class AuthGuard implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        // Get the first part of the URL (e.g., 'admin', 'guard', or 'student')
        $segment = $request->getUri()->getSegment(1);

        // 1. Admin Security Check
        if ($segment === 'admin') {
            if (!session()->get('is_admin_logged_in')) {
                return redirect()->to('/admin/login')->with('error', 'Please log in first.');
            }
        }

        // 2. Guard Security Check
        elseif ($segment === 'guard') {
            if (!session()->get('is_guard_logged_in')) {
                return redirect()->to('/guard/login')->with('error', 'Please log in first.');
            }
        }

        // 3. Student Security Check
        elseif ($segment === 'student') {
            if (!session()->get('is_student_logged_in')) {
                return redirect()->to('/student/login')->with('error', 'Please log in first.');
            }
        }

        // Fallback for any other protected routes
        else {
            if (!session()->get('is_admin_logged_in')) {
                return redirect()->to('/admin/login');
            }
        }
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // Do nothing here
    }
}