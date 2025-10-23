<?php

namespace App\Controllers;

use App\Models\NotificationModel;
use App\Models\CourseModel;
use App\Models\UserModel;
use App\Models\AnnouncementModel;
use CodeIgniter\Controller;

class Teacher extends Controller
{
    public function dashboard()
    {
        // Check if user is logged in and is a teacher
        if (!session()->get('isLoggedIn') || session()->get('role') !== 'teacher') {
            return redirect()->to('/login');
        }

        $notificationModel = new NotificationModel();
        $courseModel = new CourseModel();
        $userModel = new UserModel();
        $announcementModel = new AnnouncementModel();
        $userId = session()->get('id');

        // Get statistics
        $totalCourses = $courseModel->countAll();
        $totalStudents = $userModel->where('role', 'student')->countAllResults();

        // Get recent courses
        $recentCourses = $courseModel->orderBy('created_at', 'DESC')->findAll(3);

        // Get recent announcements
        $recentAnnouncements = $announcementModel->orderBy('created_at', 'DESC')->findAll(3);

        $data = [
            'total_courses' => $totalCourses,
            'total_students' => $totalStudents,
            'recent_courses' => $recentCourses,
            'recent_announcements' => $recentAnnouncements,
            'unread_count' => $notificationModel->getUnreadCount($userId),
            'pending_assignments' => 0, // Placeholder for future
            'latest_submissions' => 0 // Placeholder for future
        ];

        return view('teacher_dashboard', $data);
    }
}
