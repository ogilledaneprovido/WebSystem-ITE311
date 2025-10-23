<?php

namespace App\Controllers\Student;

use CodeIgniter\Controller;

class Assignment extends Controller
{
    public function index()
    {
        // Check if user is logged in and is a student
        if (!session()->get('isLoggedIn') || session()->get('role') !== 'student') {
            return redirect()->to('/login')->with('error', 'Please login as a student');
        }

        $data = [
            'assignments' => [] // Placeholder for future assignment implementation
        ];

        return view('student/assignments', $data);
    }
}
