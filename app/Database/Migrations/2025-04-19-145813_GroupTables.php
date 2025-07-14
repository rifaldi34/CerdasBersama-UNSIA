<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class GroupTables extends Migration
{
    public function up()
    {
        // Define fields for s_group
        $this->forge->addField([
            'group_id'      => [
                'type'           => 'VARCHAR',
                'constraint'     => 255,
            ],
            'group_name'    => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
                'null'       => false,
            ],
            'description'   => [
                'type'       => 'TEXT',
                'null'       => true,
            ],
            'permiss_json'  => [
                'type'       => 'TEXT',
                'null'       => true,
            ],
            'is_active'     => [
                'type'       => 'TINYINT',
                'constraint' => 1,
                'default'    => 1,
            ],
            'created'    => [
                'type'    => 'DATETIME',
                'null'    => false,
            ],
            'updated'    => [
                'type'    => 'DATETIME',
                'null'    => true,
            ],
        ]);

        // Primary key
        $this->forge->addKey('group_id', true);
        // Create the table
        $this->forge->createTable('s_group');


        // Permissions for Admin:
        // - can change status of others (approve/reject course material)
        // - cannot edit the content itself
        $adminPerms = [
            'material.changeStatus',   // e.g. approve/reject others' materials
            'comment.add'              // (unchanged)
        ];
        $this->db->table('s_group')->insert([
            'group_id'     => 'admin',
            'group_name'   => 'Admin',
            'description'  => 'Admin – can change status of others’ materials',
            'permiss_json' => json_encode($adminPerms),
            'is_active'    => 1,
            'created'      => date('Y-m-d H:i:s'),
            'updated'      => null,
        ]);

        // Permissions for User:
        // - can add, edit, delete *their own* materials
        // - cannot change status of any material
        $userPerms = [
            'material.add',
            'material.edit_own',
            'material.delete_own',
            'comment.add'
        ];
        $this->db->table('s_group')->insert([
            'group_id'     => 'user',
            'group_name'   => 'User',
            'description'  => 'Regular user – manage own materials only',
            'permiss_json' => json_encode($userPerms),
            'is_active'    => 1,
            'created'      => date('Y-m-d H:i:s'),
            'updated'      => null,
        ]);


    }

    public function down()
    {
        $this->forge->dropTable('s_group');
    }
}
