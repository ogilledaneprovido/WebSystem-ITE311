<?php

namespace App\Controllers\Teacher;

use App\Models\CourseModel;
use CodeIgniter\Controller;

class Course extends Controller
{
    public function index()
    {
        // Check if user is logged in and is a teacher
        if (!session()->get('isLoggedIn') || session()->get('role') !== 'teacher') {
            return redirect()->to('/login');
        }

        $courseModel = new CourseModel();
        $courses = $courseModel->findAll();

        return view('teacher/courses', ['courses' => $courses]);
    }
}
