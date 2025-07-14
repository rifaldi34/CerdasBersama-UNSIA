<?php

namespace App\Controllers;

use App\Models\M_Comment;
use CodeIgniter\Controller;

class Comment extends BaseController
{
    protected $commentModel;
    protected $mauth;
    protected $session;

    public function __construct()
    {
        $this->commentModel = new M_Comment();
        $this->mauth = new \App\Models\M_Auth();
        $this->session = \Config\Services::session();

        $user_permissions = $this->mauth->get_user_permissions($this->session->get('session_user_id'));
        $this->session->set('user_permissions', $user_permissions);

    }

    public function postAdd()
    {

        $material_id = $this->request->getGet('id');
        $lib_auth = new \App\Libraries\Lib_Auth();
        $lib_auth->check_permiss('comment', 'add');
        if (!$this->session->get('user_token')) {
            return redirect()->to(base_url('Auth/login'));
        }
        $user_id = $this->session->get('session_user_id');
        $content = $this->request->getPost('content');
        $parent_id = $this->request->getPost('parent_id') ?: null;
        if (empty($content)) {
            return redirect()->to(base_url('Material/view/' . $material_id));
        }

        $data_insert = [
            'material_id' => $material_id,
            'user_id' => $user_id,
            'parent_id' => $parent_id,
            'content' => $content,
            'created' => date('Y-m-d H:i:s'),
            'updated' => date('Y-m-d H:i:s'),
        ];
        $this->commentModel->insert($data_insert);

        return redirect()->to(base_url('Material/view?id=' . $material_id));
    }
} 