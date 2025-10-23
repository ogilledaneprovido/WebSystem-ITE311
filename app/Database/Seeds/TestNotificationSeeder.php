<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class TestNotificationSeeder extends Seeder
{
    public function run()
    {
        // Get a sample student user (adjust the ID as needed)
        $userModel = new \App\Models\UserModel();
        $students = $userModel->where('role', 'student')->findAll(3);

        if (empty($students)) {
            echo "No students found. Please create student users first.\n";
            return;
        }

        $notificationData = [];

        // Create test notifications for each student
        foreach ($students as $student) {
            $notificationData[] = [
                'user_id' => $student['id'],
                'message' => 'Welcome to ITE311! You have been added to the system.',
                'is_read' => 0,
                'created_at' => date('Y-m-d H:i:s', strtotime('-2 hours'))
            ];

            $notificationData[] = [
                'user_id' => $student['id'],
                'message' => 'New course material has been uploaded to your enrolled courses.',
                'is_read' => 0,
                'created_at' => date('Y-m-d H:i:s', strtotime('-1 hour'))
            ];

            $notificationData[] = [
                'user_id' => $student['id'],
                'message' => 'Reminder: Complete your pending assignments before the deadline.',
                'is_read' => 0,
                'created_at' => date('Y-m-d H:i:s', strtotime('-30 minutes'))
            ];
        }

        // Insert notifications
        $this->db->table('notifications')->insertBatch($notificationData);

        echo "Test notifications created successfully!\n";
    }
}
