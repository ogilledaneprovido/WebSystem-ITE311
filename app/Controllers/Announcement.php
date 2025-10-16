<?php

namespace App\Controllers;

use App\Models\AnnouncementModel;

class Announcement extends BaseController
{
    public function index()
    {
        // Check if user is logged in
        if (!session()->get('isLoggedIn')) {
            return redirect()->to('/login');
        }

        try {
            $model = new AnnouncementModel();
            // Fetch all announcements ordered by created_at in descending order (newest first)
            $announcements = $model->orderBy('created_at', 'DESC')->findAll();

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
