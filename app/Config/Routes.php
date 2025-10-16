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
$routes->get('student/dashboard', 'Student\Dashboard::index');
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

// Protected Student Routes (with RoleAuth filter)
$routes->group('student', ['filter' => 'roleauth'], function($routes) {
    $routes->get('dashboard', 'Student\Dashboard::index');
    $routes->get('available-courses', 'Student\Course::availableCourses');
    $routes->post('enroll-ajax', 'Student\Course::enrollAjax');
    $routes->post('unenroll-ajax', 'Student\Course::unenrollAjax');
});

// Protected Teacher Routes (with RoleAuth filter)
$routes->group('teacher', ['filter' => 'roleauth'], function($routes) {
    $routes->get('dashboard', 'Teacher::dashboard');
});

// Protected Admin Routes (with RoleAuth filter)
$routes->group('admin', ['filter' => 'roleauth'], function($routes) {
    $routes->get('dashboard', 'Admin::dashboard');
    $routes->get('courses', 'Admin\Course::index');
    $routes->get('course/(:num)', 'Admin\Course::view/$1');
    $routes->get('course/(:num)/upload', 'Materials::upload/$1');
    $routes->post('course/(:num)/upload', 'Materials::upload/$1');
    $routes->get('users', 'Admin\Users::index');
    $routes->get('settings', 'Admin\Settings::index');
});
