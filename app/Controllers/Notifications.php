<?php

namespace App\Controllers;

use App\Models\NotificationModel;
use CodeIgniter\Controller;

class Notifications extends Controller
{
    /**
     * Get notifications for the current user
     * Returns JSON response with unread count and notification list
     */
    public function get()
    {
        // Check if user is logged in
        if (!session()->get('isLoggedIn')) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'User not logged in'
            ])->setStatusCode(401);
        }

        $notificationModel = new NotificationModel();
        $userId = session()->get('id');

        // Get unread count
        $unreadCount = $notificationModel->getUnreadCount($userId);

        // Get latest notifications (limit 10)
        $notifications = $notificationModel->getNotificationsForUser($userId, 10);

        return $this->response->setJSON([
            'success' => true,
            'unread_count' => $unreadCount,
            'notifications' => $notifications
        ]);
    }

    /**
     * Mark a notification as read
     * 
     * @param int $id Notification ID
     */
    public function mark_as_read($id)
    {
        // Check if user is logged in
        if (!session()->get('isLoggedIn')) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'User not logged in'
            ])->setStatusCode(401);
        }

        $notificationModel = new NotificationModel();
        $userId = session()->get('id');

        // Verify the notification belongs to the current user
        $notification = $notificationModel->find($id);

        if (!$notification) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Notification not found'
            ])->setStatusCode(404);
        }

        if ($notification['user_id'] != $userId) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Unauthorized access'
            ])->setStatusCode(403);
        }

        // Mark as read
        if ($notificationModel->markAsRead($id)) {
            return $this->response->setJSON([
                'success' => true,
                'message' => 'Notification marked as read'
            ]);
        } else {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Failed to update notification'
            ])->setStatusCode(500);
        }
    }

    /**
     * Mark all notifications as read for current user
     */
    public function mark_all_as_read()
    {
        // Check if user is logged in
        if (!session()->get('isLoggedIn')) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'User not logged in'
            ])->setStatusCode(401);
        }

        $notificationModel = new NotificationModel();
        $userId = session()->get('id');

        if ($notificationModel->markAllAsRead($userId)) {
            return $this->response->setJSON([
                'success' => true,
                'message' => 'All notifications marked as read'
            ]);
        } else {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Failed to update notifications'
            ])->setStatusCode(500);
        }
    }

    /**
     * Display notifications page for the current user
     */
    public function index()
    {
        // Check if user is logged in
        if (!session()->get('isLoggedIn')) {
            return redirect()->to('/login')->with('error', 'Please login first');
        }

        $notificationModel = new NotificationModel();
        $userId = session()->get('id');
        $role = session()->get('role');

        $data = [
            'notifications' => $notificationModel->getNotificationsForUser($userId, 50),
            'unread_count' => $notificationModel->getUnreadCount($userId)
        ];

        // Return appropriate view based on role
        if ($role === 'admin') {
            return view('admin/notifications', $data);
        } elseif ($role === 'teacher') {
            return view('teacher/notifications', $data);
        } elseif ($role === 'student') {
            return view('student/notifications', $data);
        } else {
            return view('notifications', $data);
        }
    }
}
