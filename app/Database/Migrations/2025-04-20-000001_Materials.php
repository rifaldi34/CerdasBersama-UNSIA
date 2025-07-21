<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class Materials extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type' => 'BIGINT',
                'auto_increment' => true,
            ],
            'title' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
            ],
            'article_content' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'thumbnail_path' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
                'null' => true,
            ],
            'author_id' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
            ],
            'status' => [
                'type' => 'ENUM',
                'constraint' => ['pending', 'approved', 'rejected'],
                'default' => 'pending',
            ],
            'created' => [
                'type' => 'DATETIME',
                'null' => false,
            ],
            'updated' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->createTable('g_materials', true);

    // Create g_materials_files table
    $this->forge->addField([
        'id' => [
            'type' => 'BIGINT',
            'auto_increment' => true,
        ],
        'material_id' => [
            'type' => 'BIGINT',
            'null' => false,
        ],
        'file_name' => [
            'type' => 'VARCHAR',
            'constraint' => 255,
        ],
        'file_path' => [
            'type' => 'VARCHAR',
            'constraint' => 255,
        ],
        'author_id' => [
            'type' => 'VARCHAR',
            'constraint' => 255,
        ],
        'content_type' => [
            'type' => 'VARCHAR',
            'constraint' => 50,
            'null' => true,
        ],
        'content_category' => [
            'type' => 'VARCHAR',
            'constraint' => 100,
            'null' => true,
        ],
        'link_url' => [
            'type' => 'TEXT',
            'null' => true,
        ],
        'file_size' => [
            'type' => 'INT',
            'null' => true,
        ],
        'mime_type' => [
            'type' => 'VARCHAR',
            'constraint' => 100,
            'null' => true,
        ],
        'created' => [
            'type' => 'DATETIME',
            'null' => false,
        ],
    ]);

        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('material_id', 'g_materials', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('g_materials_files', true);

        // 3. Insert dummy data langsung di migration
        $now = date('Y-m-d H:i:s');
        $materials = [
            [
                'title'           => 'Pengenalan CodeIgniter 4',
                'article_content' => 'Lorem ipsum dolor sit amet consectetur adipiscing elit. Quisque faucibus ex sapien vitae pellentesque sem placerat. In id cursus mi pretium tellus duis convallis. Tempus leo eu aenean sed diam urna tempor. Pulvinar vivamus fringilla lacus nec metus bibendum egestas. Iaculis massa nisl malesuada lacinia integer nunc posuere. Ut hendrerit semper vel class aptent taciti sociosqu. Ad litora torquent per conubia nostra inceptos himenaeos.

Lorem ipsum dolor sit amet consectetur adipiscing elit. Quisque faucibus ex sapien vitae pellentesque sem placerat. In id cursus mi pretium tellus duis convallis. Tempus leo eu aenean sed diam urna tempor. Pulvinar vivamus fringilla lacus nec metus bibendum egestas. Iaculis massa nisl malesuada lacinia integer nunc posuere. Ut hendrerit semper vel class aptent taciti sociosqu. Ad litora torquent per conubia nostra inceptos himenaeos.

Lorem ipsum dolor sit amet consectetur adipiscing elit. Quisque faucibus ex sapien vitae pellentesque sem placerat. In id cursus mi pretium tellus duis convallis. Tempus leo eu aenean sed diam urna tempor. Pulvinar vivamus fringilla lacus nec metus bibendum egestas. Iaculis massa nisl malesuada lacinia integer nunc posuere. Ut hendrerit semper vel class aptent taciti sociosqu. Ad litora torquent per conubia nostra inceptos himenaeos.

Lorem ipsum dolor sit amet consectetur adipiscing elit. Quisque faucibus ex sapien vitae pellentesque sem placerat. In id cursus mi pretium tellus duis convallis. Tempus leo eu aenean sed diam urna tempor. Pulvinar vivamus fringilla lacus nec metus bibendum egestas. Iaculis massa nisl malesuada lacinia integer nunc posuere. Ut hendrerit semper vel class aptent taciti sociosqu. Ad litora torquent per conubia nostra inceptos himenaeos.

Lorem ipsum dolor sit amet consectetur adipiscing elit. Quisque faucibus ex sapien vitae pellentesque sem placerat. In id cursus mi pretium tellus duis convallis. Tempus leo eu aenean sed diam urna tempor. Pulvinar vivamus fringilla lacus nec metus bibendum egestas. Iaculis massa nisl malesuada lacinia integer nunc posuere. Ut hendrerit semper vel class aptent taciti sociosqu. Ad litora torquent per conubia nostra inceptos himenaeos.',
                'thumbnail_path'  => 'uploads/thumbnails/ci_4_thumbnail.png',
                'author_id'       => '019802752c807d339987f61e54b588af',
                'status'          => 'approved',
                'created'         => $now,
                'updated'         => null,
            ],
            [
                'title'           => 'Teknik PHP Lanjutan',
                'article_content' => 'Pembahasan mendalam tentang OOP dan pola desain di PHP.',
                'thumbnail_path'  => 'uploads/thumbnails/php-oops.jpg',
                'author_id'       => '019802752c807d339987f61e54b588af',
                'status'          => 'pending',
                'created'         => date('Y-m-d H:i:s', strtotime('-2 days')),
                'updated'         => date('Y-m-d H:i:s', strtotime('-1 day')),
            ],
            [
                'title'           => 'Migrasi Database dengan CI4',
                'article_content' => 'Pelajari cara mengelola skema database menggunakan fitur migrasi.',
                'thumbnail_path'  => 'uploads/thumbnails/database-migration.png',
                'author_id'       => '019802752c807d339987f61e54b588af',
                'status'          => 'rejected',
                'created'         => date('Y-m-d H:i:s', strtotime('-5 days')),
                'updated'         => date('Y-m-d H:i:s', strtotime('-3 days')),
            ],
        ];

        // Insert batch
        $this->db->table('g_materials')->insertBatch($materials);

        // Ambil ID yang baru diinsert untuk dummy files
        $inserted = $this->db->table('g_materials')
                             ->select('id')
                             ->orderBy('created', 'DESC')
                             ->limit(count($materials))
                             ->get()
                             ->getResultArray();

        $files = [];
        foreach ($inserted as $idx => $row) {
            $files[] = [
                'material_id' => $row['id'],
                'file_name'   => "Pemrograman-Web-Menggunakan-Codeigniter-4-dan-Bootstrap_compressed.pdf",
                'file_path'   => "uploads/Pemrograman-Web-Menggunakan-Codeigniter-4-dan-Bootstrap_compressed.pdf",
                'author_id'   => '019802752c807d339987f61e54b588af',
                'created'     => $now,
            ];
        }

        $this->db->table('g_materials_files')->insertBatch($files);

        $this->db->query("
            CREATE OR REPLACE VIEW v_g_materials AS
            SELECT 
                a.*, 
                b.irl_name 
            FROM 
                g_materials AS a
            LEFT JOIN 
                s_user AS b 
            ON 
                a.author_id = b.user_id
        ");

    }

    public function down()
    {
        $this->forge->dropTable('g_materials', true);
        $this->forge->dropTable('g_materials_files', true);
        $this->db->query("DROP VIEW IF EXISTS v_g_materials");
    }
} 