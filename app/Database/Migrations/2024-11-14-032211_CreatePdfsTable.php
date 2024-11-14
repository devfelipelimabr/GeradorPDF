<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreatePdfsTable extends Migration
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
            'pdf_path' => [
                'type'       => 'VARCHAR',
                'constraint' => '255',
            ],
            'data' => [
                'type'       => 'JSON',
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => false,
            ],
        ]);

        $this->forge->addPrimaryKey('id');
        $this->forge->addForeignKey('user_id', 'users', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addKey('created_at');

        $this->forge->createTable('pdfs');
    }

    public function down()
    {
        $this->forge->dropTable('pdfs');
    }
}
