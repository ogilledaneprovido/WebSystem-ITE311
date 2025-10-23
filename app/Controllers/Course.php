<?php namespace App\Controllers;

use App\Models\EnrollmentModel;
use App\Models\CourseModel;
use App\Models\MaterialModel;
use App\Models\NotificationModel;
use CodeIgniter\Controller;

class Course extends Controller
{
    protected $courseModel;
    protected $enrollmentModel;

    public function __construct()
    {
        $this->courseModel = new CourseModel();
        $this->enrollmentModel = new EnrollmentModel();
    }

    public function index()
    {
        // Check if user is logged in
        if (!session()->get('isLoggedIn')) {
            return redirect()->to('/login')->with('error', 'Please login first');
        }

        $userId = session()->get('id');
        $userRole = session()->get('role');

        $data['courses'] = [];

        // For students, show only enrolled courses
        if ($userRole === 'student') {
            try {
                $enrollmentModel = new EnrollmentModel();
                $courseModel = new CourseModel();
                
                // Get enrollment records for this user
                $enrollments = $enrollmentModel
                    ->where('user_id', $userId)
                    ->findAll();
                
                // Get course details for each enrollment
                $enrolledCourses = [];
                foreach ($enrollments as $enrollment) {
                    $course = $courseModel->find($enrollment['course_id']);
                    if ($course) {
                        // Handle both column names for enrollment date
                        $course['enrolled_at'] = $enrollment['enrollment_date'] ?? $enrollment['enrolled_at'] ?? null;
                        $enrolledCourses[] = $course;
                    }
                }
                
                $data['courses'] = $enrolledCourses;
            } catch (\Exception $e) {
                log_message('error', 'Error fetching enrolled courses: ' . $e->getMessage());
                session()->setFlashdata('error', 'Error loading courses. Please try again.');
                $data['courses'] = [];
            }
        } else {
            // For admin/teacher, show all courses
            try {
                $data['courses'] = $this->courseModel->findAll() ?? [];
            } catch (\Exception $e) {
                log_message('error', 'Error fetching all courses: ' . $e->getMessage());
                $data['courses'] = [];
            }
        }

        return view('courses/index', $data);
    }

    public function view($id)
    {
        $data['course'] = $this->courseModel->getCourseById($id);
        return view('courses/view', $data);
    }

    public function enroll()
    {
        // Check if user is logged in
        if (!session()->get('isLoggedIn')) {
            return redirect()->to('/login')->with('error', 'Please login first');
        }

        // Check if user is a student
        if (session()->get('role') !== 'student') {
            return redirect()->back()->with('error', 'Only students can enroll in courses');
        }

        $courseId = $this->request->getPost('course_id');
        $userId = session()->get('id');

        if (!$courseId) {
            return redirect()->back()->with('error', 'Course ID is required');
        }

        $enrollmentModel = new EnrollmentModel();
        $courseModel = new CourseModel();
        $notificationModel = new NotificationModel();

        // Check if already enrolled
        $existingEnrollment = $enrollmentModel
            ->where('user_id', $userId)
            ->where('course_id', $courseId)
            ->first();

        if ($existingEnrollment) {
            return redirect()->back()->with('error', 'You are already enrolled in this course');
        }

        // Get course details
        $course = $courseModel->find($courseId);

        if (!$course) {
            return redirect()->back()->with('error', 'Course not found');
        }

        // Enroll the student
        $enrollmentData = [
            'user_id' => $userId,
            'course_id' => $courseId,
            'enrollment_date' => date('Y-m-d H:i:s')
        ];

        if ($enrollmentModel->insert($enrollmentData)) {
            // Create notification for the student
            $notificationMessage = "You have been successfully enrolled in " . $course['title'];
            $notificationModel->createNotification($userId, $notificationMessage);

            return redirect()->back()->with('success', 'Successfully enrolled in ' . $course['title']);
        } else {
            return redirect()->back()->with('error', 'Failed to enroll in course');
        }
    }

    public function adminIndex()
    {
        if (!session()->get('isLoggedIn') || session()->get('role') !== 'admin') {
            return redirect()->to('/login');
        }

        $data['courses'] = $this->courseModel->findAll();
        
        return view('admin/courses', $data);
    }

    public function adminView($course_id)
    {
        if (!session()->get('isLoggedIn') || session()->get('role') !== 'admin') {
            return redirect()->to('/login');
        }

        $data = [
            'course' => $this->courseModel->find($course_id),
            'materials' => (new MaterialModel())->getMaterialsByCourse($course_id)
        ];
        
        return view('admin/course_view', $data);
    }
}
