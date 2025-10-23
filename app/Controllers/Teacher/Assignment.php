<?php

namespace App\Controllers\Teacher;

use CodeIgniter\Controller;

class Assignment extends Controller
{
    public function index()
    {
        if (!session()->get('isLoggedIn') || session()->get('role') !== 'teacher') {
            return redirect()->to('/login');
        }

        // Load assignments from session for demo (replace with DB in production)
        $assignments = session()->get('assignments') ?? [];
        return view('teacher/assignments', ['assignments' => $assignments]);
    }

    public function create()
    {
        if (!session()->get('isLoggedIn') || session()->get('role') !== 'teacher') {
            return redirect()->to('/login');
        }

        if ($this->request->getMethod() === 'post') {
            $title = $this->request->getPost('title');
            $description = $this->request->getPost('description');
            $due_date = $this->request->getPost('due_date');

            $assignments = session()->get('assignments') ?? [];
            $assignments[] = [
                'title' => $title,
                'description' => $description,
                'due_date' => $due_date
            ];
            session()->set('assignments', $assignments);
            return redirect()->to(base_url('teacher/assignments'));
        }
        return view('teacher/create_assignment');
    }
}
