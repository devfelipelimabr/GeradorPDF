<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateUsersTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type'           => 'INT',
                'constraint'     => 5,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'name' => [
                'type'       => 'VARCHAR',
                'constraint' => '128',
            ],
            'email' => [
                'type'       => 'VARCHAR',
                'constraint' => '256',
            ],
            'image' => [
                'type'       => 'VARCHAR',
                'constraint' => '256',
                'null' => true,
                'default' => null,
            ],
            'is_active' => [
                'type'       => 'BOOLEAN',
                'null' => false,
            ],
            'password_hash' => [
                'type'       => 'VARCHAR',
                'constraint' => '256',
            ],
            'reset_hash' => [
                'type'       => 'VARCHAR',
                'constraint' => '256',
                'null' => true,
                'default' => null,
            ],
            'reset_expires_in' => [
                'type'       => 'DATETIME',
                'null' => true,
                'default' => null,
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => false,
            ],
            'updated_at' => [
                'type' => 'DATETIME',
                'null' => false,
            ],
            'deleted_at' => [
                'type' => 'DATETIME',
                'null' => true,
                'default' => null,
            ],
        ]);
        
        $this->forge->addPrimaryKey('id');
        $this->forge->addUniqueKey('email');
        $this->forge->addKey('created_at');
        $this->forge->addKey('name');

        $this->forge->createTable('users');
    }

    public function down()
    {
        $this->forge->dropTable('users');
    }
}