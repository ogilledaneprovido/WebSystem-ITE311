<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class AnnouncementSeeder extends Seeder
{
    public function run()
    {
        $data = [
            [
                'title' => 'Welcome to ITE311 Portal',
                'content' => 'Welcome to the new ITE311 Course Management Portal! This system will help you manage your courses, assignments, and materials efficiently. Please explore the features available based on your role and don\'t hesitate to reach out if you need any assistance.',
                'created_at' => date('Y-m-d H:i:s', strtotime('-2 days'))
            ],
            [
                'title' => 'System Maintenance Notice',
                'content' => 'Please be informed that scheduled system maintenance will be conducted this weekend from 2:00 AM to 6:00 AM on Saturday. The system may be temporarily unavailable during this period. We apologize for any inconvenience this may cause and appreciate your understanding.',
                'created_at' => date('Y-m-d H:i:s', strtotime('-1 day'))
            ],
            [
                'title' => 'New Features Available',
                'content' => 'We are excited to announce new features that have been added to enhance your learning experience: Course material downloads, improved enrollment management, enhanced dashboard navigation, and real-time notifications. Check out these new features today!',
                'created_at' => date('Y-m-d H:i:s')
            ]
        ];

        // Insert data using the table method
        $this->db->table('announcements')->insertBatch($data);
    }
}
