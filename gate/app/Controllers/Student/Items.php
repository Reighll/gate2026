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
        $model->insert([
            'student_id'    => session()->get('student_id'),
            'category'      => $this->request->getPost('category'),
            'brand_model'   => $this->request->getPost('brand_model'),
            'serial_number' => $this->request->getPost('serial_number'),
            'photo'         => $photoName,
            'status'        => 'pending'
        ]);

        // THE FIX: Set the flashdata and send a JSON success signal!
        if ($this->request->isAJAX()) {
            session()->setFlashdata('success', 'Item registered successfully! Please proceed to the Administration Office for item verification to claim your RFID sticker.');
            return $this->response->setJSON(['status' => 'success']);
        }

        return redirect()->to('student/dashboard')->with('success', 'Item registered successfully! Please proceed to the Administration Office for item verification to claim your RFID sticker.');
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

        return redirect()->to('student/items/registered')->with('success', 'Item updated successfully.');
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
}