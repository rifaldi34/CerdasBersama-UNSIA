<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class Comments extends Migration
{
    public function up()
    {
        // 1) Define table fields
        $this->forge->addField([
            'id'          => [ 'type' => 'BIGINT', 'auto_increment' => true ],
            'material_id' => [ 'type' => 'BIGINT' ],
            'user_id'     => [ 'type' => 'VARCHAR', 'constraint' => 255 ],
            'parent_id'   => [ 'type' => 'BIGINT', 'null' => true ],
            'content'     => [ 'type' => 'TEXT' ],
            'created'     => [ 'type' => 'DATETIME', 'null' => false ],
            'updated'     => [ 'type' => 'DATETIME', 'null' => true ],
        ]);

        // 2) Set primary and secondary keys
        $this->forge->addKey('id', true);
        $this->forge->addKey('material_id');
        $this->forge->addKey('user_id');
        $this->forge->addKey('parent_id');

        // 3) Create the comments table
        $this->forge->createTable('g_comments', true);

        // 4) Insert sample nested comment data (similar to Reddit)
        $now = date('Y-m-d H:i:s');
        $sampleComments = [
            // Top-level comments
            [
                'material_id' => 1,
                'user_id'     => '019802752c807d339987f61e54b588af',
                'parent_id'   => null,
                'content'     => 'This is the first top-level comment on material 1.',
                'created'     => $now,
                'updated'     => null,
            ],
            [
                'material_id' => 1,
                'user_id'     => '019802752c807d339987f61e54b588af',
                'parent_id'   => null,
                'content'     => 'Here is another top-level discussion point.',
                'created'     => $now,
                'updated'     => null,
            ],
            // Replies to the first comment
            [
                'material_id' => 1,
                'user_id'     => '019802752c807d339987f61e54b588af',
                'parent_id'   => 1,
                'content'     => 'Replying to the first comment.',
                'created'     => $now,
                'updated'     => null,
            ],
            [
                'material_id' => 1,
                'user_id'     => '019802752c807d339987f61e54b588af',
                'parent_id'   => 1,
                'content'     => 'Another reply at the same level.',
                'created'     => $now,
                'updated'     => null,
            ],
            // Nested reply (reply to a reply)
            [
                'material_id' => 1,
                'user_id'     => 'user5',
                'parent_id'   => 3,
                'content'     => 'Deeply nested reply to user3s comment.',
                'created'     => $now,
                'updated'     => null,
            ],
        ];

        // Batch insert sample data
        $this->db->table('g_comments')->insertBatch($sampleComments);

        $this->db->query("
            CREATE OR REPLACE VIEW v_g_comments AS
            SELECT 
                a.*, 
                b.irl_name 
            FROM 
                g_comments AS a
            LEFT JOIN 
                s_user AS b 
            ON 
                a.user_id = b.user_id
        ");
    }

    public function down()
    {
        $this->forge->dropTable('g_comments', true);
        $this->db->query("DROP VIEW IF EXISTS v_g_comments");
    }
}
