<?php namespace App\Models;
use CodeIgniter\Model;

class CourseModel extends Model
{
    protected $table = 'courses';
    protected $primaryKey = 'id';
    protected $allowedFields = ['title', 'description', 'created_at'];
    protected $useTimestamps = false;

    public function getAllCourses()
    {
        return $this->findAll();
    }

    public function getCourseById($id)
    {
        return $this->find($id);
    }
}
