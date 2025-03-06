<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateUsersTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'user_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'auto_increment' => true,
            ],
            'username' => [
                'type' => 'VARCHAR',
                'constraint' => 100,
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'updated_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
        ]);
        $this->forge->addKey('user_id', true);
        $this->forge->createTable('users');

        $this->forge->addField([
            'locker_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'auto_increment' => true,
            ],
            'locker_no' => [
                'type' => 'INT',
                'constraint' => 5,
                'unique' => true,
            ],
            'status' => [
                'type' => 'ENUM',
                'constraint' => ['free', 'occupied'],
                'default' => 'free',
            ],
            'user_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'null' => true,
            ],
            'next_locker_no' => [
                'type'       => 'INT',
                'constraint' => 11,
                'default'    => 0,
                'null' => false,
            ],
        ]);
        $this->forge->addKey('locker_id', true);
        $this->forge->createTable('lockers');
    }

    public function down()
    {
        $this->forge->dropTable('users');
        $this->forge->dropTable('lockers');
    }
}
