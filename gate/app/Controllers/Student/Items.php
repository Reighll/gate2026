<?php

namespace App\Controllers\Student;

use App\Controllers\BaseController;
use App\Models\StudentItemModel;

class Items extends BaseController
{
    public function store()
    {
        // 1. Security check
        if (!session()->get('student_logged_in')) {
            return redirect()->to('student/login');
        }

        $model = new StudentItemModel();

        // 2. Validate user input
        $rules = [
            'category'      => 'required',
            'brand_model'   => 'required',
            'serial_number' => 'required',
            'photo'         => 'uploaded[photo]|is_image[photo]|max_size[photo,51200]'
        ];

        if (!$this->validate($rules)) {
            $errorString = implode('<br>', $this->validator->getErrors());

            // THE FIX: Respond with clean JSON if the background script is listening
            if ($this->request->isAJAX()) {
                return $this->response->setJSON([
                    'status' => 'error',
                    'message' => $errorString
                ]);
            }

            return redirect()->to('student/dashboard')->withInput()->with('error', $errorString);
        }

        // 3. Handle the Image Upload & Compression
        $photo = $this->request->getFile('photo');
        $photoName = '';

        if ($photo->isValid() && !$photo->hasMoved()) {
            $photoName = $photo->getRandomName();
            $uploadPath = FCPATH . 'uploads/items/';
            $photo->move($uploadPath, $photoName);

            $filepath = $uploadPath . $photoName;
            try {
                \Config\Services::image()
                    ->withFile($filepath)
                    ->resize(800, 800, true, 'auto')
                    ->save($filepath, 60);
            } catch (\Exception $e) {
                log_message('error', 'Image Compression Failed: ' . $e->getMessage());
            }
        }

        // 4. Save to Database
        //
        // "Personal Computing Device" items always go through the normal pending
        // -> admin scan flow, unchanged. "Others" items can skip straight to
        // approved if this student already has an established shared tag (from
        // their Item Pass or any other previously-approved Others item) — they
        // just inherit that same tag. If no shared tag exists yet, it stays
        // pending exactly like before; there's nothing to auto-attach.
        $category = $this->request->getPost('category');

        $insertData = [
            'student_id'    => session()->get('student_id'),
            'category'      => $category,
            'brand_model'   => $this->request->getPost('brand_model'),
            'serial_number' => $this->request->getPost('serial_number'),
            'photo'         => $photoName,
            'status'        => 'pending',
        ];

        $autoApproved = false;

        if ($category === 'Others') {
            $sharedTagItem = $model
                ->where('student_id', session()->get('student_id'))
                ->where('category', 'Others')
                ->where('status', 'approved')
                ->where('rfid IS NOT NULL')
                ->orderBy('updated_at', 'DESC')
                ->first();

            if ($sharedTagItem && !empty($sharedTagItem['rfid'])) {
                $insertData['status']      = 'approved';
                $insertData['rfid']        = $sharedTagItem['rfid'];
                $insertData['in_campus']   = 0;
                $insertData['is_bringing'] = 1;
                $autoApproved = true;
            }
        }

        $model->insert($insertData);

        $successMessage = $autoApproved
            ? 'Item registered and automatically verified using your existing shared tag!'
            : 'Item registered successfully! Please proceed to the Administration Office for item verification to claim your RFID sticker.';

        // THE FIX: Set the flashdata and send a JSON success signal!
        if ($this->request->isAJAX()) {
            session()->setFlashdata('success', $successMessage);
            return $this->response->setJSON(['status' => 'success']);
        }

        return redirect()->to('student/dashboard')->with('success', $successMessage);
    }

    public function update($id)
    {
        if (!session()->get('student_logged_in')) return redirect()->to('student/login');

        $model = new StudentItemModel();
        $item = $model->where('id', $id)->where('student_id', session()->get('student_id'))->first();

        if (!$item) {
            return redirect()->to('student/dashboard')->with('error', 'Item not found.');
        }

        $rules = ['serial_number' => 'required'];
        $photo = $this->request->getFile('photo');
        if ($photo && $photo->isValid()) {
            $rules['photo'] = 'is_image[photo]|max_size[photo,51200]';
        }

        if (!$this->validate($rules)) {
            return redirect()->back()->with('error', implode('<br>', $this->validator->getErrors()));
        }

        $updateData = [
            'serial_number' => $this->request->getPost('serial_number'),
        ];

        if ($photo && $photo->isValid() && !$photo->hasMoved()) {
            if (!empty($item['photo']) && file_exists(FCPATH . 'uploads/items/' . $item['photo'])) {
                unlink(FCPATH . 'uploads/items/' . $item['photo']);
            }

            $photoName = $photo->getRandomName();
            $uploadPath = FCPATH . 'uploads/items/';
            $photo->move($uploadPath, $photoName);

            try {
                \Config\Services::image()
                    ->withFile($uploadPath . $photoName)
                    ->resize(800, 800, true, 'auto')
                    ->save($uploadPath . $photoName, 60);
            } catch (\Exception $e) {
                log_message('error', 'Image Compression Failed: ' . $e->getMessage());
            }

            $updateData['photo'] = $photoName;
        }

        $model->update($id, $updateData);

        return redirect()->to('student/registered-items')->with('success', 'Item updated successfully.');
    }

    public function requestUnregister($id)
    {
        if (!session()->get('student_logged_in')) return redirect()->to('student/login');

        $reason = $this->request->getPost('reason');

        $model = new StudentItemModel();

        $item = $model->where('id', $id)->where('student_id', session()->get('student_id'))->first();

        if ($item) {
            $model->update($id, [
                'status' => 'staged',
                'reason' => $reason
            ]);
            return redirect()->to('student/dashboard')->with('success', 'Unregistration request submitted. Awaiting Admin approval.');
        }

        return redirect()->to('student/dashboard')->with('error', 'Item not found.');
    }

    public function report()
    {
        if (!session()->get('student_logged_in')) return redirect()->to('student/login');

        $itemId = $this->request->getPost('item_id');
        $notes  = $this->request->getPost('notes');

        if (empty($itemId)) {
            return redirect()->back()->with('error', 'Please select an item to report.');
        }

        $model = new StudentItemModel();

        $item = $model->where('id', $itemId)->where('student_id', session()->get('student_id'))->first();

        if ($item) {
            $model->update($itemId, [
                'status' => 'missing',
                'notes'  => $notes
            ]);
            return redirect()->to('student/dashboard')->with('success', 'Item reported missing! Guards have been alerted.');
        }

        return redirect()->to('student/dashboard')->with('error', 'Invalid item selected.');
    }

    public function markFound($id)
    {
        if (!session()->get('student_logged_in')) return redirect()->to('student/login');

        $model = new StudentItemModel();

        $item = $model->where('id', $id)->where('student_id', session()->get('student_id'))->first();

        if ($item && $item['status'] === 'missing') {
            $model->update($id, ['status' => 'approved']);
            return redirect()->to('student/dashboard')->with('success', 'Glad you found it! The missing alert has been cleared.');
        }

        return redirect()->to('student/dashboard')->with('error', 'Unable to update item status.');
    }

    /**
     * True only while the student's most recent scan was a time_in with no
     * time_out after it. Mirrors Student\Dashboard::getCampusStatus() — kept
     * as its own copy here rather than shared, since these are two separate
     * controllers and this is a small, self-contained check.
     */
    private function isStudentInsideCampus($studentId)
    {
        $model = new StudentItemModel();

        $pass = $model->where('student_id', $studentId)
            ->where('brand_model', 'Item Pass')
            ->where('status', 'approved')
            ->first();

        if (!$pass) return false;

        return (int) ($pass['in_campus'] ?? 0) === 1;
    }

    /**
     * Flip whether the student is bringing this item onto campus.
     * Only meaningful for approved items — this is what Guard\Dashboard::checkIn()
     * checks when multiple items share one RFID tag, so it knows which of them
     * to actually log for that scan.
     *
     * Locked to only be changeable while the student is currently Inside
     * Campus. Without this: leave an item at school (toggle it off before
     * exiting, so the exit scan correctly skips it — its in_campus flag
     * stays "1", correctly still reflecting it's still there), then later
     * flip it back on from home before actually walking back in. The next
     * entry scan would then read that item's stale in_campus=1 as a reason
     * to log a time_out for it — the exact opposite of what's happening.
     * Restricting changes to while-inside makes that sequence impossible.
     */
    public function toggleBringing($id)
    {
        if (!session()->get('student_logged_in')) return redirect()->to('student/login');

        $model = new StudentItemModel();
        $item = $model->where('id', $id)->where('student_id', session()->get('student_id'))->first();

        if (!$item) {
            return redirect()->to('student/items/registered')->with('error', 'Item not found.');
        }

        if ($item['status'] !== 'approved') {
            return redirect()->to('student/items/registered')->with('error', 'Only approved items can be toggled.');
        }

        // The Item Pass IS the shared physical tag — it doesn't make sense to
        // mark "not bringing" the card itself, so it's always treated as
        // bringing. Blocked here too, not just hidden in the view, in case
        // someone posts to this route directly.
        if (($item['brand_model'] ?? '') === 'Item Pass') {
            return redirect()->to('student/items/registered')->with('error', 'The Item Pass tag itself cannot be toggled.');
        }

        // Locked only when THIS item is currently recorded as still at school
        // (in_campus=1) while the student is Outside — that's the one
        // combination that would create a scan-direction mismatch if the
        // toggle got flipped back on remotely. An item that's already
        // in_campus=0 (i.e. it left with the student) stays freely
        // toggleable no matter where the student currently is, and
        // everything is freely toggleable while the student is inside.
        $itemStillAtSchool = (int) ($item['in_campus'] ?? 0) === 1;
        if ($itemStillAtSchool && !$this->isStudentInsideCampus(session()->get('student_id'))) {
            return redirect()->to('student/items/registered')->with('error', 'You can only change this once you\'re back inside campus — this item is currently marked as left at school.');
        }

        $newValue = ((int) $item['is_bringing'] === 1) ? 0 : 1;
        $model->update($id, ['is_bringing' => $newValue]);

        $itemName = $item['brand_model'] ?? $item['name'] ?? 'Item';
        $message = $newValue === 1
            ? $itemName . ' marked as bringing.'
            : $itemName . ' marked as left behind — it won\'t be logged if the tag is scanned.';

        return redirect()->to('student/items/registered')->with('success', $message);
    }
}