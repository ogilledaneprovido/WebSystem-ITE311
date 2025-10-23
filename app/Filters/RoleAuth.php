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
        
        // Normalize path - remove trailing slash and ensure it starts with /
        $currentPath = '/' . trim($currentPath, '/');
        
        // Check access based on role and path
        // Admin can access everything except student/teacher dashboards
        if ($userRole === 'admin') {
            // Admin should only access admin routes
            if (strpos($currentPath, '/student') === 0) {
                return redirect()->to('/admin/dashboard')->with('error', 'Access Denied: Admins cannot access student routes.');
            }
            if (strpos($currentPath, '/teacher') === 0) {
                return redirect()->to('/admin/dashboard')->with('error', 'Access Denied: Admins cannot access teacher routes.');
            }
            return; // Admin can access admin routes
        }
        
        // Teacher can only access teacher routes
        if ($userRole === 'teacher') {
            if (strpos($currentPath, '/teacher') === 0) {
                return; // Allow access to teacher routes
            }
            // Deny access to admin and student routes
            if (strpos($currentPath, '/admin') === 0) {
                return redirect()->to('/teacher/dashboard')->with('error', 'Access Denied: Teachers cannot access admin routes.');
            }
            if (strpos($currentPath, '/student') === 0) {
                return redirect()->to('/teacher/dashboard')->with('error', 'Access Denied: Teachers cannot access student routes.');
            }
            // If not a protected route, allow access (e.g., announcements, notifications)
            return;
        }
        
        // Student can only access student routes
        if ($userRole === 'student') {
            if (strpos($currentPath, '/student') === 0) {
                return; // Allow access to student routes
            }
            // Deny access to admin and teacher routes
            if (strpos($currentPath, '/admin') === 0) {
                return redirect()->to('/student/dashboard')->with('error', 'Access Denied: Students cannot access admin routes.');
            }
            if (strpos($currentPath, '/teacher') === 0) {
                return redirect()->to('/student/dashboard')->with('error', 'Access Denied: Students cannot access teacher routes.');
            }
            // If not a protected route, allow access (e.g., announcements, notifications)
            return;
        }
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // Do nothing
    }
}
