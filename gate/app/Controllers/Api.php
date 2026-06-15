<?php
namespace App\Controllers;
use CodeIgniter\Controller;

class Api extends Controller
{
    public function scan()
    {
        // Catch the POST request from the ESP32
        $epc = $this->request->getPost('epc');

        if ($epc) {
            // NEW: Use FILE_APPEND and LOCK_EX to safely queue multiple scans in a row!
            // It will look like this inside the file: e28...,e29...,e30...,
            file_put_contents(WRITEPATH . 'latest_scan.txt', $epc . ',', FILE_APPEND | LOCK_EX);

            // Send success back to ESP32
            return $this->response->setBody("Success! EPC: " . $epc . " added to queue.");
        } else {
            return $this->response->setStatusCode(400)->setBody("Error: No EPC received.");
        }
    }
}