<?php

namespace App\Controllers;

use App\Models\MaterialModel;
use App\Models\CourseModel;
use App\Models\UserModel;
use App\Models\AnnouncementModel;
use App\Models\NotificationModel;
use CodeIgniter\Controller;

class Admin extends Controller
{
    public function dashboard()
    {
        // Check if user is logged in and is an admin
        if (!session()->get('isLoggedIn') || session()->get('role') !== 'admin') {
            return redirect()->to('/login');
        }

        $courseModel = new CourseModel();
        $materialModel = new MaterialModel();
        $userModel = new UserModel();
        $notificationModel = new NotificationModel();
        $announcementModel = new AnnouncementModel();

        $userId = session()->get('id');

        // Get statistics
        $totalCourses = $courseModel->countAll();
        $totalMaterials = $materialModel->countAll();
        $totalStudents = $userModel->where('role', 'student')->countAllResults();
        $totalTeachers = $userModel->where('role', 'teacher')->countAllResults();

        // Get recent courses
        $recentCourses = $courseModel->orderBy('created_at', 'DESC')->findAll(3);

        // Get recent announcements
        $recentAnnouncements = $announcementModel->orderBy('created_at', 'DESC')->findAll(3);

        // Get recent students
        $recentStudents = $userModel->where('role', 'student')
                                    ->orderBy('created_at', 'DESC')
                                    ->findAll(3);

        $data = [
            'total_courses' => $totalCourses,
            'total_materials' => $totalMaterials,
            'total_students' => $totalStudents,
            'total_teachers' => $totalTeachers,
            'recent_courses' => $recentCourses,
            'recent_announcements' => $recentAnnouncements,
            'recent_students' => $recentStudents,
            'unread_count' => $notificationModel->getUnreadCount($userId)
        ];

        return view('admin_dashboard', $data);
    }

    public function announcements()
    {
        // Check if user is logged in and is an admin
        if (!session()->get('isLoggedIn') || session()->get('role') !== 'admin') {
            return redirect()->to('/login');
        }

        $announcementModel = new AnnouncementModel();
        $data['announcements'] = $announcementModel->orderBy('created_at', 'DESC')->findAll();

        return view('admin/announcements', $data);
    }

    public function createAnnouncement()
    {
        // Check if user is logged in and is an admin
        if (!session()->get('isLoggedIn') || session()->get('role') !== 'admin') {
            return redirect()->to('/login');
        }

        if ($this->request->getMethod() === 'post' || $this->request->getMethod() === 'POST' || $_SERVER['REQUEST_METHOD'] === 'POST') {
            log_message('info', 'Announcement creation POST request received');
            
            $announcementModel = new AnnouncementModel();
            
            $title = $this->request->getPost('title');
            $content = $this->request->getPost('content');
            
            log_message('info', 'Title: ' . $title . ', Content: ' . substr($content, 0, 50));
            
            $data = [
                'title' => $title,
                'content' => $content
            ];

            try {
                $result = $announcementModel->insert($data);
                log_message('info', 'Insert result: ' . ($result ? 'success' : 'failed'));
                
                if ($result) {
                    session()->setFlashdata('success', 'Announcement created successfully!');
                    return redirect()->to('/admin/announcements');
                } else {
                    $errors = $announcementModel->errors();
                    log_message('error', 'Announcement creation errors: ' . json_encode($errors));
                    session()->setFlashdata('error', 'Failed to create announcement: ' . implode(', ', $errors));
                    return redirect()->back()->withInput();
                }
            } catch (\Exception $e) {
                log_message('error', 'Announcement creation exception: ' . $e->getMessage());
                session()->setFlashdata('error', 'Error: ' . $e->getMessage());
                return redirect()->back()->withInput();
            }
        }

        return view('admin/create_announcement');
    }

    public function deleteAnnouncement($id)
    {
        // Check if user is logged in and is an admin
        if (!session()->get('isLoggedIn') || session()->get('role') !== 'admin') {
            return redirect()->to('/login');
        }

        $announcementModel = new AnnouncementModel();
        
        if ($announcementModel->delete($id)) {
            return redirect()->to('/admin/announcements')->with('success', 'Announcement deleted successfully!');
        } else {
            return redirect()->back()->with('error', 'Failed to delete announcement.');
        }
    }

    public function users()
    {
        // Check if user is logged in and is an admin
        if (!session()->get('isLoggedIn') || session()->get('role') !== 'admin') {
            return redirect()->to('/login');
        }

        $userModel = new UserModel();
        
        // Get all users with role filter if provided
        $role = $this->request->getGet('role');
        
        if ($role && in_array($role, ['student', 'teacher', 'admin'])) {
            $data['users'] = $userModel->where('role', $role)->orderBy('created_at', 'DESC')->findAll();
            $data['filter_role'] = $role;
        } else {
            $data['users'] = $userModel->orderBy('created_at', 'DESC')->findAll();
            $data['filter_role'] = 'all';
        }

        // Get user counts by role
        $data['total_students'] = $userModel->where('role', 'student')->countAllResults();
        $data['total_teachers'] = $userModel->where('role', 'teacher')->countAllResults();
        $data['total_admins'] = $userModel->where('role', 'admin')->countAllResults();
        $data['total_users'] = $userModel->countAll();

        return view('admin/users', $data);
    }

    public function deleteUser($id)
    {
        // Check if user is logged in and is an admin
        if (!session()->get('isLoggedIn') || session()->get('role') !== 'admin') {
            return redirect()->to('/login');
        }

        // Prevent deleting own account
        if ($id == session()->get('id')) {
            return redirect()->back()->with('error', 'You cannot delete your own account!');
        }

        $userModel = new UserModel();
        
        if ($userModel->delete($id)) {
            return redirect()->to('/admin/users')->with('success', 'User deleted successfully!');
        } else {
            return redirect()->back()->with('error', 'Failed to delete user.');
        }
    }

    public function updateUserRole($id)
    {
        // Check if user is logged in and is an admin
        if (!session()->get('isLoggedIn') || session()->get('role') !== 'admin') {
            return redirect()->to('/login');
        }

        // Prevent changing own role
        if ($id == session()->get('id')) {
            return redirect()->back()->with('error', 'You cannot change your own role!');
        }

        if ($this->request->getMethod() === 'post') {
            $userModel = new UserModel();
            $newRole = $this->request->getPost('role');

            if (in_array($newRole, ['student', 'teacher', 'admin'])) {
                $userModel->update($id, ['role' => $newRole]);
                return redirect()->to('/admin/users')->with('success', 'User role updated successfully!');
            } else {
                return redirect()->back()->with('error', 'Invalid role specified.');
            }
        }

        return redirect()->back()->with('error', 'Invalid request.');
    }
}