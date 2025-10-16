<?php

namespace App\Controllers;

class Announcement extends BaseController
{
    public function index()
    {
        // Check if user is logged in
        if (!session()->get('isLoggedIn')) {
            return redirect()->to('/login');
        }

        try {
            // For now, we'll use an empty array since the table will be created in Task 2
            // Later this will be replaced with: $model = new AnnouncementModel(); $announcements = $model->orderBy('created_at', 'DESC')->findAll();
            $announcements = [];

            $data = [
                'title' => 'Announcements',
                'announcements' => $announcements
            ];

            return view('announcements', $data);
        } catch (\Exception $e) {
            log_message('error', 'Announcements error: ' . $e->getMessage());
            
            $data = [
                'title' => 'Announcements',
                'announcements' => [],
                'error' => 'Unable to load announcements at this time.'
            ];
            
            return view('announcements', $data);
        }
    }
}
