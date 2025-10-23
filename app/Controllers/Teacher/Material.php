<?php

namespace App\Controllers\Teacher;

use CodeIgniter\Controller;

class Material extends Controller
{
    public function index()
    {
        if (!session()->get('isLoggedIn') || session()->get('role') !== 'teacher') {
            return redirect()->to('/login');
        }

        // For demo, just show an empty list
        $materials = session()->get('materials') ?? [];
        return view('teacher/materials', ['materials' => $materials]);
    }

    public function upload()
    {
        if (!session()->get('isLoggedIn') || session()->get('role') !== 'teacher') {
            return redirect()->to('/login');
        }

        if ($this->request->getMethod() === 'post') {
            $title = $this->request->getPost('title');
            $file = $this->request->getFile('file');
            if ($file && $file->isValid() && !$file->hasMoved()) {
                $newName = $file->getRandomName();
                $file->move(ROOTPATH . 'public/uploads/materials', $newName);
                $materials = session()->get('materials') ?? [];
                $materials[] = [
                    'title' => $title,
                    'filename' => $newName
                ];
                session()->set('materials', $materials);
                return redirect()->to(base_url('teacher/materials'));
            }
        }
        return view('teacher/upload_material');
    }
}
