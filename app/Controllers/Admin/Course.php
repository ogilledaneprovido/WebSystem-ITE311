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

    public function create()
    {
        if (!session()->get('isLoggedIn') || session()->get('role') !== 'admin') {
            return redirect()->to('/login');
        }

        if ($this->request->getMethod() === 'post' || $this->request->getMethod() === 'POST' || $_SERVER['REQUEST_METHOD'] === 'POST') {
            log_message('info', 'Course creation POST request received');
            
            $courseModel = new CourseModel();
            
            $title = $this->request->getPost('title');
            $description = $this->request->getPost('description');
            
            log_message('info', 'Title: ' . $title . ', Description: ' . $description);
            
            $data = [
                'title' => $title,
                'description' => $description
            ];

            try {
                $result = $courseModel->insert($data);
                log_message('info', 'Insert result: ' . ($result ? 'success' : 'failed'));
                
                if ($result) {
                    session()->setFlashdata('success', 'Course created successfully!');
                    return redirect()->to('/admin/courses');
                } else {
                    $errors = $courseModel->errors();
                    log_message('error', 'Course creation errors: ' . json_encode($errors));
                    session()->setFlashdata('error', 'Failed to create course: ' . implode(', ', $errors));
                    return redirect()->back()->withInput();
                }
            } catch (\Exception $e) {
                log_message('error', 'Course creation exception: ' . $e->getMessage());
                session()->setFlashdata('error', 'Error: ' . $e->getMessage());
                return redirect()->back()->withInput();
            }
        }

        return view('admin/create_course');
    }

    public function delete($id)
    {
        if (!session()->get('isLoggedIn') || session()->get('role') !== 'admin') {
            return redirect()->to('/login');
        }

        $courseModel = new CourseModel();
        
        if ($courseModel->delete($id)) {
            return redirect()->to('/admin/courses')->with('success', 'Course deleted successfully!');
        } else {
            return redirect()->back()->with('error', 'Failed to delete course.');
        }
    }
}