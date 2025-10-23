<?php

namespace App\Controllers\Teacher;

use App\Models\UserModel;
use CodeIgniter\Controller;

class Student extends Controller
{
    public function index()
    {
        // Check if user is logged in and is a teacher
        if (!session()->get('isLoggedIn') || session()->get('role') !== 'teacher') {
            return redirect()->to('/login');
        }

        $userModel = new UserModel();
        $students = $userModel->where('role', 'student')->findAll();

        return view('teacher/students', ['students' => $students]);
    }
}
