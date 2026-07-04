<?php
namespace App\Controllers;

use CodeIgniter\Controller;

class Api extends Controller
{
    public function scan()
    {
        $epc = $this->request->getPost('epc');

        if (!$epc) {
            return $this->response
                ->setStatusCode(400)
                ->setBody("Error: No EPC received.");
        }

        // CLEAN EPC STRING (VERY IMPORTANT)
        $epc = trim($epc);

        // Convert batch "A,B,C" into clean array first
        $epcArray = array_values(array_unique(array_filter(array_map('trim', explode(',', $epc)))));

        // OVERWRITE FILE (NOT APPEND)
        // We only want the latest batch, not history accumulation
        file_put_contents(
            WRITEPATH . 'latest_scan.txt',
            implode(',', $epcArray),
            LOCK_EX
        );

        return $this->response->setBody(
            "Success! EPC batch stored: " . implode(',', $epcArray)
        );
    }
}