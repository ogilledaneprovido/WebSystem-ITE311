<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\CourseModel;
use App\Models\MaterialModel;

class Course extends BaseController
{
    public function index()
    {
        if (!session()->get('isLoggedIn') || session()->get('role') !== 'admin') {
            return redirect()->to('/login');
        }

        $courseModel = new CourseModel();
        $data['courses'] = $courseModel->findAll();
        
        return view('admin/courses', $data);
    }

    public function view($course_id)
    {
        if (!session()->get('isLoggedIn') || session()->get('role') !== 'admin') {
            return redirect()->to('/login');
        }

        $courseModel = new CourseModel();
        $materialModel = new MaterialModel();
        
        $data = [
            'course' => $courseModel->find($course_id),
            'materials' => $materialModel->getMaterialsByCourse($course_id)
        ];
        
        if (!$data['course']) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Course not found');
        }
        
        return view('admin/course_view', $data);
    }
}