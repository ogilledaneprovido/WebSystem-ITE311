<?php

namespace App\Controllers;

use App\Models\MaterialModel;
use CodeIgniter\Controller;

class Materials extends Controller
{
    // Add missing index method for displaying materials by course
    public function index($course_id)
    {
        $materialModel = new MaterialModel();
        $materials = $materialModel->getMaterialsByCourse($course_id);
        
        return view('materials/index', [
            'materials' => $materials,
            'course_id' => $course_id
        ]);
    }

    // ✅ Upload material
    public function upload($course_id)
    {
        helper(['form', 'url']);

        // Add admin check for security
        if (!session()->get('isLoggedIn') || session()->get('role') !== 'admin') {
            return redirect()->to('/login');
        }

        // Check if this is a POST request (form submission)
        if ($this->request->getMethod() === 'POST' || $_SERVER['REQUEST_METHOD'] === 'POST') {
            
            // Get the uploaded file
            $file = $this->request->getFile('material_file');
            
            // Debug: Force show an error message to test if this code is reached
            if (!$file || $file->getName() === '') {
                session()->setFlashdata('error', 'Please select a file to upload.');
                return redirect()->back();
            }

            // Check for upload errors
            if ($file->getError() !== UPLOAD_ERR_OK) {
                session()->setFlashdata('error', 'File upload failed: ' . $file->getErrorString());
                return redirect()->back();
            }

            // Check if file is valid and not moved
            if ($file->isValid() && !$file->hasMoved()) {
                
                // Create upload directory
                $uploadPath = FCPATH . 'uploads/materials/';
                if (!is_dir($uploadPath)) {
                    mkdir($uploadPath, 0755, true);
                }

                // Generate new filename and move file
                $newName = $file->getRandomName();
                
                if ($file->move($uploadPath, $newName)) {
                    
                    // Save to database
                    $materialModel = new MaterialModel();
                    $data = [
                        'course_id' => (int)$course_id,
                        'file_name' => $file->getClientName(),
                        'file_path' => 'uploads/materials/' . $newName,
                        'created_at' => date('Y-m-d H:i:s')
                    ];
                    
                    if ($materialModel->insertMaterial($data)) {
                        session()->setFlashdata('success', 'File uploaded successfully: ' . $file->getClientName());
                        return redirect()->to('/admin/course/' . $course_id);
                    } else {
                        // Delete uploaded file if database save fails
                        unlink($uploadPath . $newName);
                        session()->setFlashdata('error', 'Failed to save file information to database.');
                        return redirect()->back();
                    }
                    
                } else {
                    session()->setFlashdata('error', 'Failed to save uploaded file.');
                    return redirect()->back();
                }
            } else {
                session()->setFlashdata('error', 'Invalid file or file already processed.');
                return redirect()->back();
            }
        }

        // Show upload form
        return view('materials/upload', ['course_id' => $course_id]);
    }

    // ✅ Delete material
    public function delete($material_id)
    {
        // Add admin check for security
        if (!session()->get('isLoggedIn') || session()->get('role') !== 'admin') {
            return redirect()->to('/login');
        }

        $model = new MaterialModel();
        $material = $model->find($material_id);

        if ($material) {
            if (file_exists(FCPATH . $material['file_path'])) {
                unlink(FCPATH . $material['file_path']);
            }
            $model->delete($material_id);
        }

        return redirect()->back()->with('success', 'Material deleted successfully!');
    }

    // ✅ Download material
    public function download($material_id)
    {
        // Add login check and enrollment verification as per lab requirements
        if (!session()->get('isLoggedIn')) {
            return redirect()->to('/login');
        }

        $model = new MaterialModel();
        $material = $model->find($material_id);

        if ($material && file_exists(FCPATH . $material['file_path'])) {
            return $this->response->download(FCPATH . $material['file_path'], null)
                                  ->setFileName($material['file_name']);
        }

        return redirect()->back()->with('error', 'File not found.');
    }
}
