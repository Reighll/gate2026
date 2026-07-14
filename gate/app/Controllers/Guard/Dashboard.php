<?php

namespace App\Controllers\Guard;

use App\Controllers\BaseController;
use App\Models\StudentItemModel;
use App\Models\StudentModel;
use App\Models\ItemLogModel;
use App\Models\GuardModel;

class Dashboard extends BaseController
{
    public function index()
    {
        if (!session()->get('guard_logged_in')) {
            return redirect()->to('guard/login');
        }

        $db = \Config\Database::connect();

        $slotsAvailable = 0;
        if ($db->tableExists('visitor_tags')) {
            $slotsAvailable = $db->table('visitor_tags')->where('status', 'available')->countAllResults();
        }

        $data = [
            'slotsAvailable' => $slotsAvailable
        ];

        return view('Guard/views/dashboard', $data);
    }

    public function checkIn()
    {
        if (!session()->get('guard_logged_in')) return redirect()->to('guard/login');

        $rfidRaw = trim((string) $this->request->getPost('rfid'));

        if (empty($rfidRaw)) {
            return redirect()->back()->with('error', 'SCAN FAILED: No card data received.');
        }

        $db = \Config\Database::connect();

        // 1. EXPLODE THE BATCH
        $rfidArray = array_unique(array_map('trim', array_filter(explode(',', $rfidRaw))));

        $successCount    = 0;
        $errorMessages   = [];
        $warningMessages = [];
        $lastItem        = null;
        $lastStudent     = null;
        $lastAction      = '';
        $scannedItemsList = [];

        // NEW: Track visitor states for the UI messages
        $lastVisitorName  = null;
        $lastVisitorPhoto = null;
        $isVisitorHandled = false;

        // 2. LOOP THROUGH EVERY TAG IN THE BATCH
        foreach ($rfidArray as $rfid) {
            if (empty($rfid)) continue;

            $isVisitorHandled = false;

            // --- VISITOR CHECK ---
            // --- VISITOR CHECK ---
            if ($db->tableExists('visitor_tags')) {
                $fields = $db->getFieldNames('visitor_tags');
                $visitorColumn = in_array('rfid_uid', $fields) ? 'rfid_uid' : (in_array('rfid', $fields) ? 'rfid' : null);

                if ($visitorColumn) {
                    $visitorTag = $db->table('visitor_tags')->where($visitorColumn, $rfid)->limit(1)->get()->getRowArray();

                    if ($visitorTag) {
                        if ($db->tableExists('visitor_logs')) {
                            $logFields = $db->getFieldNames('visitor_logs');

                            // FIX 1: Ensure we check both possible column names so the query doesn't fail!
                            $logVisitorColumn = in_array('rfid_uid', $logFields) ? 'rfid_uid' : (in_array('rfid', $logFields) ? 'rfid' : null);

                            if ($logVisitorColumn) {
                                $activeVisit = $db->table('visitor_logs')
                                    ->where($logVisitorColumn, $rfid)
                                    ->orderBy('time_in', 'DESC')
                                    ->limit(1)
                                    ->get()
                                    ->getRowArray();

                                if ($activeVisit) {
                                    // (Inside your checkIn function)

                                    // FIX 2: Make the "inside" check much more accurate by checking the status
                                    $isInside = false;

                                    if (in_array('status', $logFields) && isset($activeVisit['status'])) {
                                        // Change 'inside' to 'active'
                                        $isInside = ($activeVisit['status'] === 'active');
                                    } else {
                                        // Fallback to checking time_out if status column doesn't exist
                                        $timeOutValue = $activeVisit['time_out'] ?? null;
                                        $isInside = (empty($timeOutValue) || strpos((string)$timeOutValue, '0000-00-00') !== false);
                                    }

                                    if ($isInside) {
                                        // LOGGING THE VISITOR OUT
                                        $updateData = ['time_out' => date('Y-m-d H:i:s')];
                                        if (in_array('status', $logFields)) {
                                            // Change 'outside' to 'completed'
                                            $updateData['status'] = 'completed';
                                        }

                                        $db->table('visitor_logs')->where('id', $activeVisit['id'])->update($updateData);
                                        $successCount++;
                                        $isVisitorHandled = true;

                                        // Capture the name AND id photo for the success banner
                                        $lastVisitorName  = $activeVisit['name'] ?? 'Visitor';
                                        $lastVisitorPhoto = $activeVisit['id_photo'] ?? null;
                                    }
                                }
                            }
                        }

                        // If it's a NEW visitor (or they are already checked out), show the details form
                        if (!$isVisitorHandled) {
                            session()->setFlashdata('visitor_rfid', $rfid);
                            return redirect()->to('guard/dashboard')->with('info', 'VISITOR PASS DETECTED. Please enter details.');
                        }
                    }
                }
            }

            if ($isVisitorHandled) continue;

            // --- STUDENT ITEM CHECK ---
            $itemModel = new StudentItemModel();
            if ($db->fieldExists('rfid', 'student_items')) {
                $item = $itemModel->where('rfid', $rfid)->first();

                if ($item) {
                    $studentModel = new StudentModel();
                    $student = $studentModel->find($item['student_id']);

                    $itemName = $item['brand_model'] ?? $item['name'] ?? 'Item';
                    $studentName = $student ? ($student['first_name'] . ' ' . $student['last_name']) : 'Unknown Student';

                    $lastStudent = $student;

                    if ($item['status'] === 'missing') {
                        $warningMessages[] = "🚨 MISSING DETECTED: {$itemName} ({$studentName}). Please hold item and verify!";
                        continue;
                    }

                    if ($item['status'] !== 'approved') {
                        $errorMessages[] = "DENIED: {$itemName} status is '{$item['status']}'.";
                        continue;
                    }

                    $logModel = new ItemLogModel();
                    $timestamp = date('Y-m-d H:i:s');

                    if (isset($item['in_campus']) && $item['in_campus'] == 1) {
                        $itemModel->update($item['id'], ['in_campus' => 0]);
                        if ($db->tableExists('item_logs')) {
                            $logModel->insert(['item_id' => $item['id'], 'action' => 'time_out', 'created_at' => $timestamp]);
                        }
                        $successCount++;
                        $lastAction = 'TIME-OUT';
                        $item['action_taken'] = 'TIME-OUT';
                    } else {
                        $itemModel->update($item['id'], ['in_campus' => 1]);
                        if ($db->tableExists('item_logs')) {
                            $logModel->insert(['item_id' => $item['id'], 'action' => 'time_in', 'created_at' => $timestamp]);
                        }
                        $successCount++;
                        $lastAction = 'TIME-IN';
                        $item['action_taken'] = 'TIME-IN';
                    }
                    $scannedItemsList[] = $item;
                    $lastItem = $item;
                } else {
                    $errorMessages[] = "Unrecognized Card ({$rfid})";
                }
            }
        }

        // 3. BATCH SUMMARY RESULTS
        if (count($rfidArray) == 1) {

            if (!empty($warningMessages)) {
                session()->setFlashdata('scanned_item', $lastItem);
                session()->setFlashdata('scanned_student', $lastStudent);
                return redirect()->to('guard/dashboard')->with('warning', implode('<br>', $warningMessages));

            } elseif ($isVisitorHandled && $successCount > 0) {
                // NEW: Trigger the success banner specifically for Visitors!
                // Flash the visitor's name + ID photo so the dashboard can display it, just like item scans.
                session()->setFlashdata('departed_visitor', [
                    'name'  => $lastVisitorName,
                    'photo' => $lastVisitorPhoto,
                ]);
                return redirect()->to('guard/dashboard')->with('success', "VISITOR DEPARTED: " . esc($lastVisitorName));

            } elseif ($lastItem && $lastStudent && $successCount > 0) {
                session()->setFlashdata('scanned_item', $lastItem);
                session()->setFlashdata('scanned_student', $lastStudent);
                return redirect()->to('guard/dashboard')->with('success', "{$lastAction} LOGGED: " . esc($lastItem['brand_model'] ?? 'Item'));

            } elseif (!empty($errorMessages)) {
                return redirect()->to('guard/dashboard')->with('error', implode('<br>', $errorMessages));
            }

        } else {
            // Multiple Items Scanned (Batch)
            if ($successCount > 0) {
                session()->setFlashdata('success', "BATCH COMPLETE: {$successCount} scans logged successfully!");
            }

            if (!empty($warningMessages)) {
                session()->setFlashdata('warning', "🚨 SECURITY ALERT:<br>" . implode('<br>', $warningMessages));
            }

            if (!empty($errorMessages)) {
                session()->setFlashdata('error', "Some scans failed:<br>" . implode('<br>', $errorMessages));
            }

            if (!empty($scannedItemsList) && $lastStudent) {
                session()->setFlashdata('scanned_items', $scannedItemsList); // <-- CHANGED TO ARRAY
                session()->setFlashdata('scanned_student', $lastStudent);
            }
            return redirect()->to('guard/dashboard');
        }

        return redirect()->to('guard/dashboard');
    }

    public function logVisitor()
    {
        if (!session()->get('guard_logged_in')) return redirect()->to('guard/login');

        $db = \Config\Database::connect();

        // 1. Get the basic inputs
        $rfid    = $this->request->getPost('rfid');
        $name    = $this->request->getPost('visitor_name');
        $purpose = $this->request->getPost('purpose');

        // 2. VALIDATION: Check if RFID is empty
        if (empty($rfid)) {
            return redirect()->to('guard/dashboard')->with('error', 'No RFID tag detected. Please scan a valid visitor card first.');
        }

        // 3. FETCH THE ACTUAL TAG DETAILS
        $tagFields = $db->getFieldNames('visitor_tags');
        $tagColumn = in_array('rfid_uid', $tagFields) ? 'rfid_uid' : 'rfid';

        $visitorTag = $db->table('visitor_tags')->where($tagColumn, $rfid)->get()->getRowArray();

        if (!$visitorTag) {
            return redirect()->to('guard/dashboard')->with('error', 'Unregistered Card: The RFID tag (' . esc($rfid) . ') is not registered as a Visitor Tag in the system.');
        }

        $passName = $visitorTag['pass_number'] ?? 'Unknown Pass';

        // --- NEW: MANDATORY PHOTO VALIDATION ---
        $manualPhoto = $this->request->getFile('manual_photo');
        $webcamPhoto = $this->request->getPost('webcam_photo');

        $hasManualPhoto = ($manualPhoto && $manualPhoto->isValid() && !$manualPhoto->hasMoved());
        $hasWebcamPhoto = !empty($webcamPhoto);

        if (!$hasManualPhoto && !$hasWebcamPhoto) {
            // Kick them back to the dashboard with an error if both are missing
            return redirect()->to('guard/dashboard')->withInput()->with('error', 'SECURITY ALERT: An ID Photo is mandatory. Please snap a picture or upload an image.');
        }
        // ---------------------------------------

        // 4. Proceed with Image Uploads & Compression
        $photoName = null;
        $uploadDir = FCPATH . 'uploads/visitor_ids/';

        if (!is_dir($uploadDir)) mkdir($uploadDir, 0777, true);

        if ($hasManualPhoto) {
            $photoName = $manualPhoto->getRandomName();
            $manualPhoto->move($uploadDir, $photoName);
            $filepath = $uploadDir . $photoName;

            try {
                \Config\Services::image()->withFile($filepath)->resize(800, 800, true, 'auto')->save($filepath, 70);
            } catch (\Exception $e) {
                log_message('error', 'Visitor image compression failed: ' . $e->getMessage());
            }

        } else if ($hasWebcamPhoto) {
            $imageParts = explode(";base64,", $webcamPhoto);
            if (count($imageParts) == 2) {
                $imageTypeAux = explode("image/", $imageParts[0]);
                $imageType = $imageTypeAux[1] ?? 'png';
                $imageBase64 = base64_decode($imageParts[1]);

                $photoName = 'visitor_webcam_' . time() . '_' . uniqid() . '.' . $imageType;
                $filepath = $uploadDir . $photoName;

                file_put_contents($filepath, $imageBase64);

                try {
                    \Config\Services::image()->withFile($filepath)->resize(800, 800, true, 'auto')->save($filepath, 70);
                } catch (\Exception $e) {
                    log_message('error', 'Visitor webcam compression failed: ' . $e->getMessage());
                }
            }
        }

        // 5. Insert into Database
        if ($db->tableExists('visitor_logs')) {
            $logFields = $db->getFieldNames('visitor_logs');
            $logVisitorColumn = in_array('rfid', $logFields) ? 'rfid' : 'rfid_uid';

            $insertData = [
                $logVisitorColumn => $rfid,
                'name'            => $name,
                'purpose'         => $purpose,
                'time_in'         => date('Y-m-d H:i:s'),
            ];

            if (in_array('tag_id', $logFields)) $insertData['tag_id'] = $passName;
            if ($photoName && in_array('id_photo', $logFields)) $insertData['id_photo'] = $photoName;
            if (in_array('status', $logFields)) $insertData['status'] = 'active';

            $db->table('visitor_logs')->insert($insertData);
        }

        return redirect()->to('guard/dashboard')->with('success', 'VISITOR LOGGED IN: ' . esc($name));
    }

    public function checkLatestScan()
    {
        $file = WRITEPATH . 'latest_scan.txt';

        if (file_exists($file)) {
            $content = file_get_contents($file);
            $epcsRaw = explode(",", trim($content));
            $cleanedEpcs = [];

            foreach ($epcsRaw as $epc) {
                $clean = trim($epc, " ,\t\n\r");
                if (!empty($clean)) {
                    $cleanedEpcs[] = $clean;
                }
            }

            if (!empty($cleanedEpcs)) {
                // Wipe the file immediately so we grab ALL of them at once!
                unlink($file);

                // Return them as a single comma-separated string back to the browser
                return $this->response->setJSON(['status' => 'success', 'epc' => implode(',', $cleanedEpcs)]);
            } else {
                unlink($file);
            }
        }

        return $this->response->setJSON(['status' => 'waiting']);
    }

    // ==========================================
    // GUARD PROFILE SETTINGS
    // ==========================================

    public function profile()
    {
        if (!session()->get('guard_logged_in')) return redirect()->to('guard/login');

        $guardModel = new \App\Models\GuardModel();
        $data = [
            'guard' => $guardModel->find(session()->get('guard_id'))
        ];

        return view('Guard/views/profile', $data);
    }

    public function updateProfile()
    {
        if (!session()->get('guard_logged_in')) return redirect()->to('guard/login');

        $guardModel = new \App\Models\GuardModel();
        $guardId = session()->get('guard_id');
        $guard = $guardModel->find($guardId);

        $updateData = [
            'first_name' => $this->request->getPost('first_name'),
            'last_name'  => $this->request->getPost('last_name'),
        ];

        // 1. Handle Profile Picture Upload
        $file = $this->request->getFile('profile_pic');
        if ($file && $file->isValid() && !$file->hasMoved()) {
            $newName = $file->getRandomName();
            $file->move(FCPATH . 'uploads/profiles', $newName);
            $updateData['profile_pic'] = $newName;

            // Delete the old picture so your server doesn't get full
            if (!empty($guard['profile_pic']) && $guard['profile_pic'] != 'default.png' && file_exists(FCPATH . 'uploads/profiles/' . $guard['profile_pic'])) {
                unlink(FCPATH . 'uploads/profiles/' . $guard['profile_pic']);
            }
        }

        // 2. Handle Password Change
        $currentPassword = $this->request->getPost('current_password');
        $newPassword = $this->request->getPost('new_password');
        $confirmPassword = $this->request->getPost('confirm_password');

        if (!empty($newPassword)) {
            if (empty($currentPassword) || !password_verify($currentPassword, $guard['password'])) {
                return redirect()->back()->withInput()->with('error', 'Incorrect current password. Profile was not updated.');
            }
            if ($newPassword !== $confirmPassword) {
                return redirect()->back()->withInput()->with('error', 'New passwords do not match. Please try again.');
            }
            $updateData['password'] = password_hash($newPassword, PASSWORD_DEFAULT);
        }

        // Update Database
        $guardModel->update($guardId, $updateData);

        // Update Session for Navbar
        session()->set('guard_name', $updateData['first_name'] . ' ' . $updateData['last_name']);
        if (isset($updateData['profile_pic'])) {
            session()->set('profile_pic', $updateData['profile_pic']);
        }

        return redirect()->to('guard/profile')->with('success', 'Your profile details have been successfully updated.');
    }
}