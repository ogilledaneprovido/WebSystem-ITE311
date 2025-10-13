<?php namespace App\Controllers;

use App\Models\EnrollmentModel;
use App\Models\CourseModel;

class Course extends BaseController
{
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
        $courseModel = new CourseModel();
        $course = $courseModel->find($course_id);
        if (!$course) {
            return $this->response->setStatusCode(404)->setJSON(['status'=>'error','message'=>'Course not found']);
        }

        $enrollmentModel = new EnrollmentModel();

        if ($enrollmentModel->isAlreadyEnrolled($user_id, $course_id)) {
            return $this->response->setStatusCode(409)->setJSON(['status'=>'error','message'=>'Already enrolled']);
        }

        $enrollmentModel->enrollUser([
            'user_id' => $user_id,
            'course_id' => $course_id,
            'enrollment_date' => date('Y-m-d H:i:s')
        ]);

        return $this->response->setJSON(['status'=>'success','message'=>'Enrolled successfully','course_name'=>$course['title']]);
    }
}
