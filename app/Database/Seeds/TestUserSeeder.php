<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class TestUserSeeder extends Seeder
{
    public function run()
    {
        $data = [
            'name'     => 'Test User',
            'email'    => 'test@example.com',
            'password' => password_hash('123456', PASSWORD_DEFAULT),
            'role'     => 'student',
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ];

        // Using Query Builder
        $this->db->table('users')->insert($data);
    }
}
