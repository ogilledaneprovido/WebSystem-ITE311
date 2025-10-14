<?php namespace App\Controllers;

use App\Models\EnrollmentModel;
use App\Models\CourseModel;
use App\Models\MaterialModel;
use CodeIgniter\Controller;

class Course extends Controller
{
    protected $courseModel;
    protected $enrollmentModel;

    public function __construct()
    {
        $this->courseModel = new CourseModel();
        $this->enrollmentModel = new EnrollmentModel();
    }

    public function index()
    {
        $data['courses'] = $this->courseModel->getAllCourses();
        return view('courses/index', $data);
    }

    public function view($id)
    {
        $data['course'] = $this->courseModel->getCourseById($id);
        return view('courses/view', $data);
    }

    public function enroll()
    {
        $session = session();
        if (!$session->has('user_id')) {
            return $this->response->setStatusCode(401)->setJSON(['status'=>'error','message'=>'Unauthorized']);
        }

        $user_id = (int)$session->get('user_id');
        $course_id = (int)$this->request->getPost('course_id');

        if ($course_id <= 0) {
            return $this->response->setStatusCode(400)->setJSON(['status'=>'error','message'=>'Invalid course id']);
        }

        // ensure course exists
        $course = $this->courseModel->find($course_id);
        if (!$course) {
            return $this->response->setStatusCode(404)->setJSON(['status'=>'error','message'=>'Course not found']);
        }

        if ($this->enrollmentModel->isAlreadyEnrolled($user_id, $course_id)) {
            return $this->response->setStatusCode(409)->setJSON(['status'=>'error','message'=>'Already enrolled']);
        }

        $this->enrollmentModel->enrollUser([
            'user_id' => $user_id,
            'course_id' => $course_id,
            'enrollment_date' => date('Y-m-d H:i:s')
        ]);

        return $this->response->setJSON(['status'=>'success','message'=>'Enrolled successfully','course_name'=>$course['title']]);
    }

    public function adminIndex()
    {
        if (!session()->get('isLoggedIn') || session()->get('role') !== 'admin') {
            return redirect()->to('/login');
        }

        $data['courses'] = $this->courseModel->findAll();
        
        return view('admin/courses', $data);
    }

    public function adminView($course_id)
    {
        if (!session()->get('isLoggedIn') || session()->get('role') !== 'admin') {
            return redirect()->to('/login');
        }

        $data = [
            'course' => $this->courseModel->find($course_id),
            'materials' => (new MaterialModel())->getMaterialsByCourse($course_id)
        ];
        
        return view('admin/course_view', $data);
    }
}
