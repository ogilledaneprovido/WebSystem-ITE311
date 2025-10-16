<?php

namespace App\Controllers;

use App\Models\UserModel;

class Auth extends BaseController
{
    public function register()
    {
        helper(['form']);
        $session = session();
        $model = new UserModel();

        if ($this->request->getMethod() === 'post' || $this->request->getMethod() === 'POST' || $_SERVER['REQUEST_METHOD'] === 'POST') {
            $rules = [
                'name' => 'required|min_length[3]',
                'email' => 'required|valid_email|is_unique[users.email]',
                'password' => 'required|min_length[6]',
                'password_confirm' => 'matches[password]',
            ];

            if ($this->validate($rules)) {
                log_message('debug', 'Validation passed');
                $data = [
                    'name'     => $this->request->getVar('name'),
                    'email'    => $this->request->getVar('email'),
                    'password' => $this->request->getVar('password'), // Don't hash here, let UserModel handle it
                    'role'     => 'student', // Use valid role from database enum
                ];
                
                log_message('debug', 'Attempting to save user data: ' . json_encode($data));
                
                try {
                    if ($model->save($data)) {
                        log_message('debug', 'User saved successfully');
                        $session->setFlashdata('success', 'Registration successful. Please login.');
                        return redirect()->to(base_url('login'));
                    } else {
                        log_message('error', 'Model save failed. Errors: ' . json_encode($model->errors()));
                        $session->setFlashdata('error', 'Registration failed: ' . implode(', ', $model->errors()));
                    }
                } catch (\Exception $e) {
                    log_message('error', 'Exception during save: ' . $e->getMessage());
                    $session->setFlashdata('error', 'Database error: ' . $e->getMessage());
                }
            } else {
                log_message('debug', 'Validation failed: ' . json_encode($this->validator->getErrors()));
                $session->setFlashdata('errors', $this->validator->getErrors());
            }
        }

        echo view('auth/register');
    }

    public function login()
    {
        helper(['form']);
        $session = session();
        
        if ($this->request->getMethod() === 'post' || $this->request->getMethod() === 'POST' || $_SERVER['REQUEST_METHOD'] === 'POST') {
            $model = new \App\Models\UserModel();
            $email = $this->request->getVar('email');
            $password = $this->request->getVar('password');
            
            if (empty($email) || empty($password)) {
                $session->setFlashdata('error', 'Please enter both email and password.');
                return view('auth/login');
            }
            
            $user = $model->where('email', $email)->first();

            if ($user && password_verify($password, $user['password'])) {
                $session->set([
                    'isLoggedIn' => true,
                    'user_id'    => $user['id'],
                    'username'   => $user['name'],
                    'email'      => $user['email'],
                    'role'       => $user['role']
                ]);

                // Enhanced role-based redirection
                if ($user['role'] === 'student') {
                    return redirect()->to(base_url('announcements'));
                } elseif ($user['role'] === 'teacher') {
                    return redirect()->to(base_url('teacher/dashboard'));
                } elseif ($user['role'] === 'admin') {
                    return redirect()->to(base_url('admin/dashboard'));
                }
                
                // Fallback
                return redirect()->to(base_url('dashboard'));
            } else {
                $session->setFlashdata('error', 'Invalid login credentials.');
                return view('auth/login');
            }
        }

        return view('auth/login');
    }

    public function logout()
    {
        session()->destroy();
        return redirect()->to(base_url('login'));
    }

    public function dashboard()
    {
        if (!session()->get('isLoggedIn')) {
            return redirect()->to(base_url('login'));
        }

        $name = session()->get('name');
        $role = session()->get('role');

        // Redirect admins to proper admin dashboard
        if ($role === 'admin') {
            return redirect()->to(base_url('/admin/dashboard'));
        } elseif ($role === 'teacher') {
            // Redirect to teacher dashboard if you have one
            return redirect()->to(base_url('/teacher/dashboard'));
        } else {
            // Redirect students to student dashboard
            return redirect()->to(base_url('/student/dashboard'));
        }

        // Fallback - this should not be reached
        $userModel = new UserModel();
        $data = [
            'name' => $name,
            'role' => $role,
            'totalUsers' => 0,
            'extraInfo' => ''
        ];

        if ($role == 'admin') {
            $data['totalUsers'] = count($userModel->findAll());
            $data['extraInfo'] = 'You have access to manage all users.';
        } elseif ($role == 'teacher') {
            $data['extraInfo'] = 'You can manage your assigned classes and view grades.';
        } else {
            $data['extraInfo'] = 'You can view your subjects, assignments, and grades.';
        }

        echo view('auth/dashboard', $data);
    }

}
