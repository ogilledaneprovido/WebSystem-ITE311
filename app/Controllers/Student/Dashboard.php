<?php namespace App\Controllers\Student;

use App\Controllers\BaseController;
use App\Models\CourseModel;
use App\Models\EnrollmentModel;
use App\Models\MaterialModel;

class Dashboard extends BaseController
{
    public function index()
    {
        if (!session()->get('isLoggedIn') || session()->get('role') !== 'student') {
            return redirect()->to('/login');
        }

        $courseModel = new CourseModel();
        $enrollmentModel = new EnrollmentModel();
        $materialModel = new MaterialModel();
        
        $user_id = session()->get('user_id');
        
        // Get student's enrolled courses
        $enrollments = $enrollmentModel->where('user_id', $user_id)->findAll();
        $enrolledCourses = [];
        
        foreach ($enrollments as $enrollment) {
            $course = $courseModel->find($enrollment['course_id']);
            if ($course) {
                $course['materials_count'] = count($materialModel->getMaterialsByCourse($course['id']));
                $enrolledCourses[] = $course;
            }
        }

        $data = [
            'enrolled_courses' => $enrolledCourses,
            'total_enrollments' => count($enrollments)
        ];

        return view('student/dashboard', $data);
    }
}
