<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class SUser extends Migration
{
    public function up()
    {
        helper('random_helper');
        // Create s_user table
        $this->forge->addField([
            'rec_id' => [
                'type'           => 'BIGINT',
                'auto_increment' => true,
            ],
            'user_id' => [ //ini buat internal ID (compare ke data)
                'type'           => 'VARCHAR',
                'constraint'     => 255,
                'comment'        => 'ini buat internal ID (compare ke data)',
            ],
            'irl_name' => [ //Ini nama dunia nyata
                'type'       => 'VARCHAR',
                'constraint' => 255,
                'comment'    => 'Ini nama dunia nyata',
            ],
            'username' => [ //Ini buat login
                'type'       => 'VARCHAR',
                'constraint' => 255,
                'comment'    => 'Ini Buat Login',
            ],
            'password' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
            ],
            'group_id' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
            ],
            'status' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
            ],
            'created' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'updated' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
        ]);
        $this->forge->addKey('rec_id', true);
        $this->forge->addKey('user_id');
        $this->forge->addKey('username');

        $this->forge->createTable('s_user', true);


        //============== START ADD User Login ==============
        $uuid = uuidv7();//01964e09eaca7ca4946b40adbf3a5e49
        $insert_arr = array(
            'user_id' => '01964e09eaca7ca4946b40adbf3a5e49',
            'irl_name' => 'Admin Admin',
            'username' => 'admin',
            'password' => password_hash('admin', PASSWORD_DEFAULT),
            'group_id' => 'admin',
            'status' => 'active',
            'created' => date('Y-m-d H:i:s'),
            'updated' => date('Y-m-d H:i:s'),
        );
        $this->db->table('s_user')->insert($insert_arr);

        $this->db->table('s_user')->insert([
            'user_id'   => '019802752c807d339987f61e54b588af',
            'irl_name'  => 'rifal',
            'username'  => 'rifaldi34',
            'password'  => '$2y$10$NqNfNFA172w4Iu4Yj5rxJesVM4jhXMr.iHTrUPkHj5goe0HQF4tVq', // pre-hashed
            'group_id'  => 'user',
            'status'    => 'active',
            'created'   => date('Y-m-d H:i:s'),
            'updated'   => date('Y-m-d H:i:s'),
        ]);

        //============== END ADD User Login ==============

        // Create g_auth_user_token table
        $this->forge->addField([
            'rec_id' => [
                'type'       => 'BIGINT',
                'auto_increment' => true,
            ],
            'token' => [//Token is in uuid
                'type'       => 'VARCHAR',
                'constraint' => 255,
            ],
            'user_id' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
            ],
            'created' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'expires' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
        ]);
        $this->forge->addKey('rec_id', true);
        $this->forge->addKey('token');
        $this->forge->addKey('user_id');
        $this->forge->createTable('g_auth_user_token', true);
        $default_db_name = $this->db->database;
        // $this->db->query("ALTER DATABASE " . $default_db_name . " SET READ_COMMITTED_SNAPSHOT ON;");
    }

    public function down()
    {
        // Drop tables in reverse order to avoid foreign key conflicts
        $this->forge->dropTable('g_auth_user_token', true);
        $this->forge->dropTable('s_user', true);
    }
}
