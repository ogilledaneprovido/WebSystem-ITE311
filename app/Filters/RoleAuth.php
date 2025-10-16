<?php

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

class RoleAuth implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        $session = session();
        
        // Check if user is logged in
        if (!$session->get('isLoggedIn')) {
            return redirect()->to('/login')->with('error', 'Please log in to access this page.');
        }
        
        $userRole = $session->get('role');
        $currentPath = $request->getUri()->getPath();
        
        // Define role-based access rules
        $accessRules = [
            'admin' => [
                'allowed_patterns' => ['/admin'],
                'can_access_all' => true // Admin can access any route
            ],
            'teacher' => [
                'allowed_patterns' => ['/teacher'],
                'can_access_all' => false
            ],
            'student' => [
                'allowed_patterns' => ['/student', '/announcements'],
                'can_access_all' => false
            ]
        ];
        
        // Check if user role exists in our rules
        if (!isset($accessRules[$userRole])) {
            return redirect()->to('/announcements')->with('error', 'Access Denied: Invalid user role.');
        }
        
        $userRules = $accessRules[$userRole];
        
        // Admin can access everything
        if ($userRules['can_access_all']) {
            return;
        }
        
        // Check if current path matches allowed patterns for the user's role
        $hasAccess = false;
        foreach ($userRules['allowed_patterns'] as $pattern) {
            if (strpos($currentPath, $pattern) === 0) {
                $hasAccess = true;
                break;
            }
        }
        
        // If access is denied, redirect with error message
        if (!$hasAccess) {
            return redirect()->to('/announcements')->with('error', 'Access Denied: Insufficient Permissions');
        }
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // Do nothing
    }
}
