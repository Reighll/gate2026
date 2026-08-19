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
            'subcategory'   => 'permit_empty',
            'brand_model'   => 'required',
            'serial_number' => 'required',
        ];
        $photo = $this->request->getFile('photo');
        if ($photo && $photo->isValid()) {
            $rules['photo'] = 'is_image[photo]|max_size[photo,51200]';
        }

        if (!$this->validate($rules)) {
            return redirect()->back()->with('error', implode('<br>', $this->validator->getErrors()));
        }

        if ($this->request->getPost('category') === 'Personal Computing Device'
            && empty($this->request->getPost('subcategory'))) {
            return redirect()->back()->with('error', 'Device Type is required for Personal Computing Devices.');
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
        $category = $this->request->getPost('category');

        $insertData = [
            'student_id'    => session()->get('student_id'),
            'category'      => $category,
            'subcategory'   => $this->request->getPost('subcategory') ?: null,
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

        if ($item['status'] !== 'pending') {
            return redirect()->to('student/items/registered')->with('error', 'This item can no longer be edited.');
        }

        $rules = [
            'category'      => 'required',
            'subcategory'   => 'permit_empty|required_if[category,Personal Computing Device]',
            'brand_model'   => 'required',
            'serial_number' => 'required',
        ];
        $photo = $this->request->getFile('photo');
        if ($photo && $photo->isValid()) {
            $rules['photo'] = 'is_image[photo]|max_size[photo,51200]';
        }

        if (!$this->validate($rules)) {
            return redirect()->back()->with('error', implode('<br>', $this->validator->getErrors()));
        }

        $updateData = [
            'category'      => $this->request->getPost('category'),
            'subcategory'   => $this->request->getPost('subcategory') ?: null,
            'brand_model'   => $this->request->getPost('brand_model'),
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

        return redirect()->to('student/items/registered')->with('success', 'Item updated successfully.');
    }

    public function requestUnregister($id)
    {
        if (!session()->get('student_logged_in')) return redirect()->to('student/login');

        $reason = $this->request->getPost('reason');

        $model = new StudentItemModel();

        $item = $model->where('id', $id)->where('student_id', session()->get('student_id'))->first();

        if (!$item) {
            return redirect()->to('student/dashboard')->with('error', 'Item not found.');
        }

        if (($item['brand_model'] ?? '') === 'Item Pass') {
            return redirect()->to('student/dashboard')->with('error', 'The Item Pass cannot be unregistered.');
        }

        $model->update($id, [
            'status' => 'staged',
            'reason' => $reason
        ]);
        return redirect()->to('student/dashboard')->with('success', 'Unregistration request submitted. Awaiting Admin approval.');
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

        if (($item['brand_model'] ?? '') === 'Item Pass') {
            return redirect()->to('student/items/registered')->with('error', 'The Item Pass tag itself cannot be toggled.');
        }

        if (($item['category'] ?? '') === 'Personal Computing Device') {
            return redirect()->to('student/items/registered')->with('error', 'Personal Computing Device items are always considered bringing and cannot be toggled.');
        }

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