<?php
namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateSnippets extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id'         => ['type' => 'CHAR', 'constraint' => 36], // UUID
            'user_id'    => ['type' => 'INT', 'unsigned' => true],

            'title'      => ['type' => 'VARCHAR', 'constraint' => 140, 'default' => ''],
            'language'   => ['type' => 'VARCHAR', 'constraint' => 50, 'default' => 'plaintext'],
            'tags'       => ['type' => 'TEXT', 'null' => true],
            'pinned'     => ['type' => 'TINYINT', 'constraint' => 1, 'default' => 0],
            'deleted'    => ['type' => 'TINYINT', 'constraint' => 1, 'default' => 0],
            'content'    => ['type' => 'LONGTEXT', 'null' => true],

            'created_at' => ['type' => 'DATETIME', 'null' => true],
            'updated_at' => ['type' => 'DATETIME', 'null' => true],
        ]);

        $this->forge->addKey('id', true);
        $this->forge->addKey(['user_id', 'updated_at']);
        $this->forge->addKey(['user_id', 'deleted']);
        $this->forge->addKey(['user_id', 'pinned']);
        $this->forge->addForeignKey('user_id', 'users', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('snippets', true);
    }

    public function down()
    {
        $this->forge->dropTable('snippets', true);
    }
}
