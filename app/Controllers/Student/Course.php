<?php

namespace App\Controllers\Student;

use App\Models\CourseModel;
use App\Models\EnrollmentModel;
use App\Models\NotificationModel;
use CodeIgniter\Controller;

class Course extends Controller
{
    public function availableCourses()
    {
        // Check if user is logged in and is a student
        if (!session()->get('isLoggedIn') || session()->get('role') !== 'student') {
            return redirect()->to('/login');
        }

        $courseModel = new CourseModel();
        $enrollmentModel = new EnrollmentModel();
        $userId = session()->get('id');

        // Get all courses
        $allCourses = $courseModel->findAll();

        // Get enrolled course IDs
        $enrolledCourses = $enrollmentModel->where('user_id', $userId)->findAll();
        $enrolledIds = array_column($enrolledCourses, 'course_id');

        // Filter available courses
        $availableCourses = array_filter($allCourses, function($course) use ($enrolledIds) {
            return !in_array($course['id'], $enrolledIds);
        });

        $data = [
            'available_courses' => $availableCourses,
            'enrolled_ids' => $enrolledIds
        ];

        return view('student/available_courses', $data);
    }

    public function enrollAjax()
    {
        // Check if user is logged in and is a student
        if (!session()->get('isLoggedIn') || session()->get('role') !== 'student') {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Unauthorized access'
            ])->setStatusCode(401);
        }

        $courseId = $this->request->getPost('course_id');
        $userId = session()->get('id');

        if (!$courseId) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Course ID is required'
            ]);
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
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Already enrolled in this course'
            ]);
        }

        // Get course details
        $course = $courseModel->find($courseId);

        if (!$course) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Course not found'
            ]);
        }

        // Enroll the student
        $enrollmentData = [
            'user_id' => $userId,
            'course_id' => $courseId,
            'enrollment_date' => date('Y-m-d H:i:s')
        ];

        if ($enrollmentModel->insert($enrollmentData)) {
            // Create notification for successful enrollment
            $notificationMessage = "You have been successfully enrolled in " . $course['title'];
            $notificationModel->createNotification($userId, $notificationMessage);

            return $this->response->setJSON([
                'success' => true,
                'message' => 'Successfully enrolled in ' . $course['title']
            ]);
        } else {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Failed to enroll in course'
            ]);
        }
    }

    public function unenrollAjax()
    {
        // Check if user is logged in and is a student
        if (!session()->get('isLoggedIn') || session()->get('role') !== 'student') {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Unauthorized access'
            ])->setStatusCode(401);
        }

        $courseId = $this->request->getPost('course_id');
        $userId = session()->get('id');

        if (!$courseId) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Course ID is required'
            ]);
        }

        $enrollmentModel = new EnrollmentModel();
        $courseModel = new CourseModel();
        $notificationModel = new NotificationModel();

        // Get course details
        $course = $courseModel->find($courseId);

        // Delete enrollment
        $deleted = $enrollmentModel
            ->where('user_id', $userId)
            ->where('course_id', $courseId)
            ->delete();

        if ($deleted) {
            // Create notification for unenrollment
            if ($course) {
                $notificationMessage = "You have been unenrolled from " . $course['title'];
                $notificationModel->createNotification($userId, $notificationMessage);
            }

            return $this->response->setJSON([
                'success' => true,
                'message' => 'Successfully unenrolled from course'
            ]);
        } else {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Failed to unenroll from course'
            ]);
        }
    }
}
