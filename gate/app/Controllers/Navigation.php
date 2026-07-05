<?php

namespace App\Controllers;

class Navigation extends BaseController
{
    public function loadStudentPage($page = 'dashboard')
    {
        // 1. Gather any data needed for the view
        $data = [
            'title' => ucfirst($page) . ' | GATE System'
        ];

        // 2. Check if the request was made quietly by HTMX
        if ($this->request->hasHeader('HX-Request')) {

            // Return ONLY the inner content view (no navbars, no headers)
            return view("Student/pages/{$page}_content_only", $data);

        }

        // 3. If it's a normal browser reload (user pressed F5)
        // Return the full standard page with the layout and navbars
        return view("Student/pages/{$page}", $data);
    }
}