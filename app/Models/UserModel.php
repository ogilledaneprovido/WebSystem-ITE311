<?php

namespace App\Models;

use CodeIgniter\Model;

class UserModel extends Model
{
    protected $table = 'users';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    protected $protectFields = true;
    protected $allowedFields = [
        'name',
        'email',
        'password',
        'role',
        'created_at',
        'updated_at'
    ];

    // Dates
    protected $useTimestamps = false;
    protected $dateFormat = 'datetime';
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';
    protected $deletedField = 'deleted_at';

    // Validation
    protected $validationRules = [
        'name' => 'required|min_length[3]|max_length[50]',
        'email' => 'required|valid_email|is_unique[users.email,id,{id}]',
        'password' => 'required|min_length[6]',
        'role' => 'required|in_list[student,instructor,admin]'
    ];

    protected $validationMessages = [
        'name' => [
            'required' => 'Name is required',
            'min_length' => 'Name must be at least 3 characters',
            'max_length' => 'Name cannot exceed 50 characters'
        ],
        'email' => [
            'required' => 'Email is required',
            'valid_email' => 'Please enter a valid email',
            'is_unique' => 'Email already exists'
        ],
        'password' => [
            'required' => 'Password is required',
            'min_length' => 'Password must be at least 6 characters'
        ],
        'role' => [
            'required' => 'Role is required',
            'in_list' => 'Role must be either student, instructor, or admin'
        ]
    ];

    protected $skipValidation = false;
    protected $cleanValidationRules = true;

    // Callbacks
    protected $allowCallbacks = true;
    protected $beforeInsert = ['hashPassword'];
    protected $beforeUpdate = ['hashPassword'];

    /**
     * Hash password before saving to database
     */
    protected function hashPassword(array $data)
    {
        if (isset($data['data']['password'])) {
            $data['data']['password'] = password_hash($data['data']['password'], PASSWORD_DEFAULT);
        }

        return $data;
    }

    /**
     * Find user by username or email
     */
    public function findUserByUsernameOrEmail($identifier)
    {
        return $this->where('username', $identifier)
                    ->orWhere('email', $identifier)
                    ->first();
    }

    /**
     * Verify user credentials
     */
    public function verifyCredentials($identifier, $password)
    {
        $user = $this->findUserByUsernameOrEmail($identifier);
        
        if ($user && password_verify($password, $user['password'])) {
            return $user;
        }
        
        return false;
    }

    /**
     * Get user profile data (without password)
     */
    public function getUserProfile($userId)
    {
        return $this->select('id, username, email, created_at, updated_at')
                    ->where('id', $userId)
                    ->first();
    }

    /**
     * Update user profile
     */
    public function updateProfile($userId, $data)
    {
        $allowedFields = ['username', 'email'];
        $updateData = [];

        foreach ($allowedFields as $field) {
            if (isset($data[$field])) {
                $updateData[$field] = $data[$field];
            }
        }

        if (!empty($updateData)) {
            $updateData['updated_at'] = date('Y-m-d H:i:s');
            return $this->update($userId, $updateData);
        }

        return false;
    }

    /**
     * Change user password
     */
    public function changePassword($userId, $newPassword)
    {
        $data = [
            'password' => password_hash($newPassword, PASSWORD_DEFAULT),
            'updated_at' => date('Y-m-d H:i:s')
        ];

        return $this->update($userId, $data);
    }
}
