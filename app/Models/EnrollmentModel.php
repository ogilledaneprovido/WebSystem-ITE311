<?php

namespace App\Models;

use CodeIgniter\Model;

class EnrollmentModel extends Model
{
    protected $table = 'enrollments';
    protected $primaryKey = 'id';
    protected $allowedFields = ['user_id', 'course_id', 'status', 'created_at'];
    protected $useTimestamps = false;

    public function enrollStudent($user_id, $course_id)
    {
        $data = [
            'user_id' => $user_id,
            'course_id' => $course_id,
            'status' => 'enrolled',
            'created_at' => date('Y-m-d H:i:s')
        ];
        return $this->insert($data);
    }

    public function getEnrollmentsByUser($user_id)
    {
        return $this->where('user_id', $user_id)->findAll();
    }

    public function isEnrolled($user_id, $course_id)
    {
        return $this->where(['user_id' => $user_id, 'course_id' => $course_id, 'status' => 'enrolled'])->first();
    }
}
