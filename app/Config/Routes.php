<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Home::index');
$routes->get('about', 'Home::about');
$routes->get('contact', 'Home::contact');
$routes->get('register', 'Auth::register');
$routes->post('register', 'Auth::register');
$routes->get('login', 'Auth::login');
$routes->post('login', 'Auth::login');
$routes->get('logout', 'Auth::logout');
$routes->get('/dashboard', 'Auth::dashboard');
$routes->post('course/enroll', 'Course::enroll');

$routes->get('/admin/course/(:num)/upload', 'Materials::upload/$1');
$routes->post('/admin/course/(:num)/upload', 'Materials::upload/$1');
$routes->get('/materials/delete/(:num)', 'Materials::delete/$1');
$routes->get('/materials/download/(:num)', 'Materials::download/$1');

$routes->get('/courses', 'Course::index');
$routes->get('/course/(:num)', 'Course::view/$1');
$routes->get('/course/(:num)/materials', 'Materials::index/$1');
$routes->get('/admin/dashboard', 'Admin::dashboard');
$routes->get('/admin/courses', 'Admin\Course::index');
$routes->get('/admin/course/(:num)', 'Admin\Course::view/$1');
$routes->get('/materials/(:num)/edit', 'Materials::edit/$1');

// Announcements route - accessible to all logged-in users
$routes->get('/announcements', 'Announcement::index');

// Notification routes - accessible to all logged-in users
$routes->get('/notifications', 'Notifications::get');
$routes->post('/notifications/mark_read/(:num)', 'Notifications::mark_as_read/$1');
$routes->post('/notifications/mark_all_read', 'Notifications::mark_all_as_read');

// Protected Student Routes
$routes->group('student', function($routes) {
    $routes->get('dashboard', 'Student\Dashboard::index');
    $routes->get('available-courses', 'Student\Course::availableCourses');
    $routes->post('enroll-ajax', 'Student\Course::enrollAjax');
    $routes->post('unenroll-ajax', 'Student\Course::unenrollAjax');
    $routes->get('assignments', 'Student\Assignment::index');
    $routes->get('grades', 'Student\Grade::index');
    $routes->get('notifications', 'Notifications::index');
});

// Protected Teacher Routes
$routes->group('teacher', function($routes) {
    $routes->get('dashboard', 'Teacher::dashboard');
    $routes->get('courses', 'Teacher\Course::index');
    $routes->get('materials', 'Teacher\Material::index');
    $routes->get('materials/upload', 'Teacher\Material::upload');
    $routes->post('materials/upload', 'Teacher\Material::upload');
    $routes->get('students', 'Teacher\Student::index');
    $routes->get('assignments', 'Teacher\Assignment::index');
    $routes->get('assignments/create', 'Teacher\Assignment::create');
    $routes->post('assignments/create', 'Teacher\Assignment::create');
    $routes->get('grades', 'Teacher\Grade::index');
    $routes->get('announcements', 'Teacher\Announcement::index');
    $routes->get('notifications', 'Notifications::index');
});

// Protected Admin Routes
$routes->group('admin', function($routes) {
    $routes->get('dashboard', 'Admin::dashboard');
    $routes->get('courses', 'Admin\Course::index');
    $routes->get('courses/create', 'Admin\Course::create');
    $routes->post('courses/create', 'Admin\Course::create');
    $routes->get('course/(:num)', 'Admin\Course::view/$1');
    $routes->get('course/(:num)/delete', 'Admin\Course::delete/$1');
    $routes->get('course/(:num)/upload', 'Materials::upload/$1');
    $routes->post('course/(:num)/upload', 'Materials::upload/$1');
    $routes->get('users', 'Admin::users');
    $routes->get('user/(:num)/delete', 'Admin::deleteUser/$1');
    $routes->post('user/(:num)/update-role', 'Admin::updateUserRole/$1');
    $routes->get('settings', 'Admin\Settings::index');
    $routes->get('announcements', 'Admin::announcements');
    $routes->get('announcements/create', 'Admin::createAnnouncement');
    $routes->post('announcements/create', 'Admin::createAnnouncement');
    $routes->get('announcements/delete/(:num)', 'Admin::deleteAnnouncement/$1');
    $routes->get('notifications', 'Notifications::index');
});
