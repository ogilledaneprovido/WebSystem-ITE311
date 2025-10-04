<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class UserSeeder extends Seeder
{
    public function run()
    {
        $data = [
            [
                'name'     => 'Dane',
                'email'    => 'jesse@example.com',
                'role'     => 'admin',
                'password' => password_hash('admin123', PASSWORD_DEFAULT),
            ],
            [
                'name'     => 'Jim',
                'email'    => 'jim@example.com',
                'role'     => 'instructor',
                'password' => password_hash('teach123', PASSWORD_DEFAULT),
            ],
            [
                'name'     => 'Ogille',
                'email'    => 'ogille@example.com',
                'role'     => 'student',
                'password' => password_hash('stud123', PASSWORD_DEFAULT),
            ],
        ];

        // Insert multiple users
        $this->db->table('users')->insertBatch($data);
    }
}
