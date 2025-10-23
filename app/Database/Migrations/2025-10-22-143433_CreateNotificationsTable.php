<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateNotificationsTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'user_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
            ],
            'message' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
            ],
            'is_read' => [
                'type'       => 'TINYINT',
                'constraint' => 1,
                'default'    => 0,
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => false,
            ],
        ]);

        $this->forge->addKey('id', true);
        $this->forge->addKey('user_id');
        $this->forge->createTable('notifications', true);

        // Only add FK if users table has id column
        // Comment out if you get FK constraint errors
        /*
        $this->db->query('ALTER TABLE `notifications` 
            ADD CONSTRAINT `fk_notifications_user` 
            FOREIGN KEY (`user_id`) 
            REFERENCES `users`(`id`) 
            ON DELETE CASCADE 
            ON UPDATE CASCADE');
        */
    }

    public function down()
    {
        // Comment out FK drop if you didn't create it
        // $this->db->query('ALTER TABLE `notifications` DROP FOREIGN KEY `fk_notifications_user`');
        
        $this->forge->dropTable('notifications', true);
    }
}
