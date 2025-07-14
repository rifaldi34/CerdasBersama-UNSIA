<?php

namespace App\Models;

use CodeIgniter\Model;

class M_Comment extends Model
{
    protected $table = 'g_comments'; // this is for insert/update only
    protected $allowedFields = ['material_id', 'user_id', 'parent_id', 'content', 'created', 'updated'];


    public function getCommentsForMaterial($material_id)
    {
        $builder = $this->db->table('v_g_comments');
        $comments = $builder
            ->where('material_id', $material_id)
            ->orderBy('created', 'ASC')
            ->get()
            ->getResultArray();

        return $this->buildNested($comments);
    }

    private function buildNested(array $comments, $parent_id = null): array
    {
        $branch = [];
        foreach ($comments as $comment) {
            if ($comment['parent_id'] == $parent_id) {
                $children = $this->buildNested($comments, $comment['id']);
                if ($children) {
                    $comment['replies'] = $children;
                }
                $branch[] = $comment;
            }
        }
        return $branch;
    }

    public function getCommentById($id)
    {
        return $this->db->table('v_g_comments')
            ->where('id', $id)
            ->get()
            ->getRowArray();
    }

    public function insertComment(array $data)
    {
        return $this->db->table('g_comments')->insert($data);
    }

    public function updateComment($id, array $data)
    {
        return $this->db->table('g_comments')
            ->where('id', $id)
            ->update($data);
    }
    
    public function deleteComment($id)
    {
        return $this->db->table('g_comments')
            ->where('id', $id)
            ->delete();
    }
}
