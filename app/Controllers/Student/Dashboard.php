<?php namespace App\Controllers\Student;

use App\Controllers\BaseController;
use App\Models\CourseModel;
use App\Models\EnrollmentModel;

class Dashboard extends BaseController
{
    public function index()
    {
        $session = session();
        $user_id = $session->get('user_id');

        $courseModel = new CourseModel();
        $enrollmentModel = new EnrollmentModel();

        // Fetch all courses
        $data['courses'] = $courseModel->findAll();

        // Fetch enrolled courses for current user
        $data['enrolled'] = $enrollmentModel->getUserEnrollments($user_id);

        return view('student/dashboard', $data);
    }
}
