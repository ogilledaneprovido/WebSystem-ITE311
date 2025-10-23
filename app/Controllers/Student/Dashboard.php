<?php

namespace App\Controllers\Student;

use App\Models\NotificationModel;
use App\Models\EnrollmentModel;
use App\Models\CourseModel;
use App\Models\AnnouncementModel;
use CodeIgniter\Controller;

class Dashboard extends Controller
{
    public function index()
    {
        // Check if user is logged in
        if (!session()->get('isLoggedIn')) {
            return redirect()->to('/login')->with('error', 'Please login first');
        }

        // Check if user is a student
        if (session()->get('role') !== 'student') {
            return redirect()->to('/dashboard')->with('error', 'Access denied');
        }

        $notificationModel = new NotificationModel();
        $enrollmentModel = new EnrollmentModel();
        $courseModel = new CourseModel();
        $announcementModel = new AnnouncementModel();
        $userId = session()->get('id');

        // Get total enrolled courses count
        $totalEnrolled = $enrollmentModel->where('user_id', $userId)->countAllResults();

        // Get recently enrolled courses (last 3)
        $recentEnrollments = $enrollmentModel
            ->where('user_id', $userId)
            ->orderBy('enrollment_date', 'DESC')
            ->limit(3)
            ->findAll();

        $recentCourses = [];
        foreach ($recentEnrollments as $enrollment) {
            $course = $courseModel->find($enrollment['course_id']);
            if ($course) {
                $course['enrolled_date'] = $enrollment['enrollment_date'];
                $recentCourses[] = $course;
            }
        }

        // Get total available courses (not enrolled)
        $allCoursesCount = $courseModel->countAllResults();
        $availableCoursesCount = $allCoursesCount - $totalEnrolled;

        // Get recent announcements (last 3)
        $recentAnnouncements = $announcementModel
            ->orderBy('created_at', 'DESC')
            ->limit(3)
            ->findAll();

        $data = [
            'total_enrolled' => $totalEnrolled,
            'recent_courses' => $recentCourses,
            'available_courses_count' => $availableCoursesCount,
            'recent_announcements' => $recentAnnouncements,
            'unread_count' => $notificationModel->getUnreadCount($userId),
            'pending_assignments' => 0, // Placeholder for future implementation
            'latest_grade' => null // Placeholder for future implementation
        ];

        return view('student_dashboard', $data);
    }
}
