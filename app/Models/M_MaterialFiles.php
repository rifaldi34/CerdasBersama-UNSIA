<?php

namespace App\Models;

use CodeIgniter\Model;

class M_MaterialFiles extends Model
{
    protected $table = 'g_materials_files';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'material_id', 'file_name', 'file_path', 'author_id', 'created'
    ];
    protected $useTimestamps = false;

    // You can add helper methods here as needed, for example:
    public function getFilesByMaterialId($material_id)
    {
        return $this->where('material_id', $material_id)
                    ->orderBy('created', 'DESC')
                    ->findAll();
    }
} 