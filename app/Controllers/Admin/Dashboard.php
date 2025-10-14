<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\CourseModel;
use App\Models\MaterialModel;
use App\Models\UserModel;

class Dashboard extends BaseController
{
    public function index()
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

