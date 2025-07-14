<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class TTestingCrud extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'rec_id' => [
                'type'           => 'BIGINT',
                'auto_increment' => true,
            ],
            'testing_name' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
            ],
            'testing_description' => [
                'type'       => 'TEXT',
            ],
        ]);
        $this->forge->addKey('rec_id', true);
        $this->forge->addKey('testing_name');

        $this->forge->createTable('t_testing_crud', true);

        for ($i=0; $i < 1000 ; $i++) { 
            $arr_insert = [
                'testing_name' => 'Testing Name '.$i,
                'testing_description' => 'Testing Description '.$i
            ];
            $this->db->table('t_testing_crud')->insert($arr_insert);
        }
    }

    public function down()
    {
        $this->forge->dropTable('t_testing_crud', true);
    }
}
