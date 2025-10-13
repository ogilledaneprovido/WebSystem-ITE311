<?php namespace App\Models;
use CodeIgniter\Model;

class EnrollmentModel extends Model
{
    protected $table = 'enrollments';
    protected $primaryKey = 'id';
    protected $allowedFields = ['user_id','course_id','enrollment_date'];

    public function enrollUser($data)
    {
        return $this->insert($data);
    }

    public function getUserEnrollments($user_id)
    {
        // returns courses.* for courses the user is enrolled in
        return $this->select('courses.*')
                    ->join('courses', 'courses.id = enrollments.course_id')
                    ->where('enrollments.user_id', $user_id)
                    ->findAll();
    }

    public function isAlreadyEnrolled($user_id, $course_id)
    {
        return $this->where(['user_id' => $user_id, 'course_id' => $course_id])->first() !== null;
    }
}
