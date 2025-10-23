<?php

namespace App\Models;

use CodeIgniter\Model;

class AnnouncementModel extends Model
{
    protected $table = 'announcements';
    protected $primaryKey = 'id';
    protected $allowedFields = ['title', 'content', 'created_at'];
    protected $useTimestamps = false;
    protected $dateFormat = 'datetime';
    protected $createdField = 'created_at';

    // Validation
    protected $validationRules = [
        'title' => 'required|max_length[255]',
        'content' => 'required'
    ];

    protected $validationMessages = [
        'title' => [
            'required' => 'Announcement title is required',
            'max_length' => 'Title cannot exceed 255 characters'
        ],
        'content' => [
            'required' => 'Announcement content is required'
        ]
    ];

    protected $skipValidation = false;
    protected $cleanValidationRules = true;

    // Callbacks
    protected $allowCallbacks = true;
    protected $beforeInsert = [];
    protected $afterInsert = [];
    protected $beforeUpdate = [];
    protected $afterUpdate = [];
    protected $beforeFind = [];
    protected $afterFind = [];
    protected $beforeDelete = [];
    protected $afterDelete = [];
}
