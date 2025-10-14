<?php

namespace App\Controllers;

use App\Models\MaterialModel;
use App\Models\CourseModel;
use App\Models\UserModel;
use CodeIgniter\Controller;

class Admin extends Controller
{
    public function dashboard()
    {
        if (!session()->get('isLoggedIn') || session()->get('role') !== 'admin') {
            return redirect()->to('/login');
        }

        $courseModel = new CourseModel();
        $materialModel = new MaterialModel();
        $userModel = new UserModel();

        $data = [
            'total_courses' => $courseModel->countAll(),
            'total_materials' => $materialModel->countAll(),
            'total_students' => $userModel->where('role', 'student')->countAllResults(),
            'recent_courses' => $courseModel->orderBy('created_at', 'DESC')->findAll(5)
        ];

        return view('admin/dashboard', $data);
    }
}