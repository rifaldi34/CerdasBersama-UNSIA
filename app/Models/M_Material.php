<?php

namespace App\Models;

use CodeIgniter\Model;

class M_Material extends Model
{
    protected $table = 'g_materials';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'title', 'article_content', 'type', 'file_path', 'thumbnail_path', 'author_id', 'status', 'created', 'updated'
    ];
    protected $useTimestamps = false;


    public function getMaterialsByStatus(string $status = 'approved', int $page = 1, int $limit = 10, string $user_id = null): array
    {
        $offset = ($page - 1) * $limit;

        // 1) Build the query off the V_G_MATERIALS view
        $builder = $this->db
                        ->table('V_G_MATERIALS')
                        ->where('status', $status);

        if (! empty($user_id)) {
            $builder->where('author_id', $user_id);
        }

        $builder->orderBy('created', 'DESC');

        // 2) Execute with limit + offset
        $query     = $builder->get($limit, $offset);
        $materials = $query->getResultArray();

        if (empty($materials)) {
            return [];
        }

        // 3) Attach files & ensure status field
        foreach ($materials as &$material) {
            $material = $this->_attachFiles($material);
            $material['status'] = $status;
        }

        return $materials;
    }


    public function getMaterialById(int $id): ?array
    {
        $material = $this->find($id);
        if (! $material) {
            return null;
        }

        $material = $this->_attachFiles($material);
        $material['status'] = $material['status'] ?? null; // ensure the key exists

        return $material;
    }


    private function _attachFiles(array $material): array
    {
        $db    = \Config\Database::connect();
        $files = $db->table('g_materials_files')
                    ->where('material_id', $material['id'])
                    ->get()
                    ->getResultArray();

        $material['files'] = $files;
        return $material;
    }



    // Add more helper methods as needed
} 