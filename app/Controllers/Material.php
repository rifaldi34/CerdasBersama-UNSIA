<?php

namespace App\Controllers;

use App\Models\M_Material;
use App\Models\M_Comment;
use App\Models\M_MaterialFiles;
use CodeIgniter\Controller;

class Material extends BaseController
{
    protected $materialModel;
    protected $materialFilesModel;
    protected $commentModel;
    protected $session;
    protected $lib_auth;

    public function __construct()
    {
        $this->materialModel = new M_Material();
        $this->materialFilesModel = new M_MaterialFiles();
        $this->commentModel = new M_Comment();
        $this->session = \Config\Services::session();
        $this->lib_auth = new \App\Libraries\Lib_Auth();
        $this->mauth = new \App\Models\M_Auth();

        $user_permissions = $this->mauth->get_user_permissions($this->session->get('session_user_id'));
        $this->session->set('user_permissions', $user_permissions);

    }

    public function getIndex()
    {
        //========== CHECK LOGIN TOKEN ====================
        $userToken = $this->session->get('user_token');
        if (!$this->lib_auth->validate_token($userToken) && !empty($userToken)) {
            // Token is invalid, redirect to logout
            return redirect()
            ->to(site_url('Auth/logout?redirecthome=1'))
            ->withHeaders()   // ensure headers are flushed
            ->send();
        }
        //========== CHECK LOGIN TOKEN ====================
        $isLoggedIn = $this->session->get('user_token') ? true : false;

        $type = $this->request->getGet('type');
        $page = $this->request->getGet('page') ?? 1;
        $limit = 1000;

        $materials = $this->materialModel->getMaterialsByStatus('approved', $page, $limit);
        $materials_pending = [];
        $materials_rejected = [];
        if ($isLoggedIn) {
            if ($this->lib_auth->check_permiss('material', 'changeStatus', true) ) {
                $materials_pending = $this->materialModel->getMaterialsByStatus('pending', $page, $limit);
                $materials_rejected = $this->materialModel->getMaterialsByStatus('rejected', $page, $limit);
            }else{
                $materials_pending = $this->materialModel->getMaterialsByStatus('pending', $page, $limit, $this->session->get('session_user_id'));
                $materials_rejected = $this->materialModel->getMaterialsByStatus('rejected', $page, $limit, $this->session->get('session_user_id'));
            }
        }

        $session_user_id = $this->session->get('session_user_id');
        $canChangeStatus = $this->lib_auth->check_permiss('material', 'changeStatus', true);
        return view('materials/home', compact('materials', 'materials_pending', 'materials_rejected', 'isLoggedIn', 'canChangeStatus', 'session_user_id'));
    }

    public function getView()
    {
        $id = $this->request->getGet('id');
        $isLoggedIn = $this->session->get('user_token') ? true : false;

        $material = $this->materialModel->getMaterialById($id);

        if (!$material || $material['status'] !== 'approved') {
            //if admin or the owner of it do nothing
            if ($this->lib_auth->check_permiss('material', 'changeStatus', true) || $material['author_id'] == $this->session->get('session_user_id')) {
                
            }else{
                throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound('Material not found');
            }
        }
        $comments = $this->commentModel->getCommentsForMaterial($id);

        return view('materials/view', compact('material', 'isLoggedIn', 'comments'));
    }

    public function getAdd()
    {
        $this->lib_auth->check_permiss('material', 'add');
        if (!$this->session->get('user_token')) {
            return redirect()->to(base_url('Auth/login'));
        }
        return view('materials/add');
    }

    public function postCreate()
    {
        $this->lib_auth->check_permiss('material', 'add');
        if (! $this->session->get('user_token')) {
            return redirect()->to(base_url('Auth/login'));
        }

        $title       = $this->request->getPost('title');
        $description = $this->request->getPost('description');
        $author_id   = $this->session->get('session_user_id');
        $files       = $this->request->getPost('files'); // Optional array of content items

        $errors = [];

        // Basic validation for title
        if (empty($title)) {
            $errors[] = 'Title is required.';
        }

        // Handle thumbnail upload (optional)
        $thumbnail_path = null;
        $thumbnail = $this->request->getFile('thumbnail');
        if ($thumbnail && $thumbnail->isValid() && ! $thumbnail->hasMoved()) {
            if ($thumbnail->getSize() > 2_048_000) { // 2MB limit
                $errors[] = 'Thumbnail file size must be less than 2MB.';
            } else {
                // Make public/uploads/thumbnails if not exists
                $thumbDir = FCPATH . 'uploads/thumbnails';
                if (! is_dir($thumbDir)) {
                    mkdir($thumbDir, 0755, true);
                }

                // Move into public/uploads/thumbnails
                $newName = $thumbnail->getRandomName();
                $thumbnail->move($thumbDir, $newName);

                // Save relative path for DB
                $thumbnail_path = 'uploads/thumbnails/' . $newName;
            }
        }

        // Validate and process content items if provided
        $content_items = [];
        if (is_array($files) && ! empty($files)) {
            foreach ($files as $index => $item) {
                $content_type     = $item['content_type']     ?? '';
                $content_category = $item['content_category'] ?? '';

                // File vs link inputs
                $uploadedFile = $this->request->getFile("files.{$index}.file");
                $linkValue    = $item['link'] ?? '';

                // Skip empty
                if (empty($content_type)
                    && empty($content_category)
                    && (! $uploadedFile || ! $uploadedFile->isValid())
                    && empty($linkValue)
                ) {
                    continue;
                }

                // Require type & category
                if (empty($content_type) || empty($content_category)) {
                    $errors[] = 'Content type and category are required for each content item.';
                    continue;
                }

                $ci = [
                    'content_type'     => $content_type,
                    'content_category' => $content_category,
                    'file_path'        => null,
                    'link_url'         => null,
                    'file_name'        => null,
                    'file_size'        => null,
                    'mime_type'        => null,
                ];

                if ($content_type === 'file') {
                    if ($uploadedFile && $uploadedFile->isValid() && ! $uploadedFile->hasMoved()) {
                        $mimeType      = $uploadedFile->getMimeType();
                        $allowed_types = $this->getAllowedFileTypes($content_category);

                        if (! in_array($mimeType, $allowed_types)) {
                            $errors[] = "Invalid file type for {$content_category}.";
                            continue;
                        }
                        if ($uploadedFile->getSize() > 100_000_000) { // 100MB
                            $errors[] = "File size must be less than 100MB for {$content_category}.";
                            continue;
                        }

                        // Ensure directory public/uploads/materials exists
                        $matDir = FCPATH . 'uploads/materials';
                        if (! is_dir($matDir)) {
                            mkdir($matDir, 0755, true);
                        }

                        // Move and record info
                        $newFileName = $uploadedFile->getRandomName();
                        $uploadedFile->move($matDir, $newFileName);

                        $ci['file_path'] = 'uploads/materials/' . $newFileName;
                        $ci['file_name'] = $uploadedFile->getClientName();
                        $ci['file_size'] = $uploadedFile->getSize();
                        $ci['mime_type'] = $mimeType;
                    } else {
                        $errors[] = 'A valid file upload is required for content type "file".';
                        continue;
                    }
                }
                elseif ($content_type === 'link') {
                    if (empty($linkValue) || ! filter_var($linkValue, FILTER_VALIDATE_URL)) {
                        $errors[] = "Invalid or missing URL for content type \"link\".";
                        continue;
                    }
                    $ci['link_url'] = $linkValue;
                }

                $content_items[] = $ci;
            }
        }

        // If validation errors exist, display and stop
        if (! empty($errors)) {
            foreach ($errors as $error) {
                echo esc($error) . '<br/>';
            }
            return;
        }

        // Insert main material record
        $material_data = [
            'title'           => $title,
            'article_content' => $description,
            'thumbnail_path'  => $thumbnail_path,
            'author_id'       => $author_id,
            'status'          => 'pending',
            'created'         => date('Y-m-d H:i:s'),
            'updated'         => date('Y-m-d H:i:s'),
        ];
        $material_id = $this->materialModel->insert($material_data);

        // Insert any valid content items
        foreach ($content_items as $ci) {
            $ci['material_id'] = $material_id;
            $ci['author_id']   = $author_id;
            $ci['created']     = date('Y-m-d H:i:s');
            $ci['updated']     = date('Y-m-d H:i:s');
            $this->materialFilesModel->insert($ci);
        }

        return redirect()->to(base_url('Material'))
                        ->with('success', 'Material submitted for review.');
    }

    public function getEdit()
    {
        $id = $this->request->getGet('id');
        $isLoggedIn = $this->session->get('user_token') ? true : false;
        $material = $this->materialModel->getMaterialById($id);
        if (!$material) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound('Material not found');
        }
        // Only owner can edit
        if ($material['author_id'] != $this->session->get('session_user_id')) {
            return redirect()->to(base_url('Material/view?id=' . $id))
                ->with('error', 'You are not allowed to edit this material.');
        }
        // Pass old values for form
        $old = [
            'title' => $material['title'],
            'description' => $material['article_content'],
            'thumbnail_path' => $material['thumbnail_path'],
            'files' => $material['files'] ?? [],
        ];
        return view('materials/add', compact('old', 'isLoggedIn', 'material'));
    }

    public function postUpdate()
    {
        $id = $this->request->getPost('id');
        $material = $this->materialModel->getMaterialById($id);
        if (!$material) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound('Material not found');
        }
        // Only owner can update
        if ($material['author_id'] != $this->session->get('session_user_id')) {
            return redirect()->to(base_url('Material/view?id=' . $id))
                ->with('error', 'You are not allowed to edit this material.');
        }
        $title = $this->request->getPost('title');
        $description = $this->request->getPost('description');
        $files = $this->request->getPost('files');
        $errors = [];
        if (empty($title)) {
            $errors[] = 'Title is required.';
        }
        // Handle thumbnail upload (optional)
        $thumbnail_path = $material['thumbnail_path'];
        $thumbnail = $this->request->getFile('thumbnail');
        if ($thumbnail && $thumbnail->isValid() && !$thumbnail->hasMoved()) {
            if ($thumbnail->getSize() > 2_048_000) {
                $errors[] = 'Thumbnail file size must be less than 2MB.';
            } else {
                $thumbDir = FCPATH . 'uploads/thumbnails';
                if (!is_dir($thumbDir)) {
                    mkdir($thumbDir, 0755, true);
                }
                $newName = $thumbnail->getRandomName();
                $thumbnail->move($thumbDir, $newName);
                $thumbnail_path = 'uploads/thumbnails/' . $newName;
            }
        }
        // Validate and process content items if provided
        $content_items = [];
        if (is_array($files) && !empty($files)) {
            foreach ($files as $index => $item) {
                $content_type = $item['content_type'] ?? '';
                $content_category = $item['content_category'] ?? '';
                $uploadedFile = $this->request->getFile("files.{$index}.file");
                $linkValue = $item['link'] ?? '';
                if (empty($content_type)
                    && empty($content_category)
                    && (!$uploadedFile || !$uploadedFile->isValid())
                    && empty($linkValue)
                ) {
                    continue;
                }
                if (empty($content_type) || empty($content_category)) {
                    $errors[] = 'Content type and category are required for each content item.';
                    continue;
                }
                $ci = [
                    'content_type' => $content_type,
                    'content_category' => $content_category,
                    'file_path' => null,
                    'link_url' => null,
                    'file_name' => null,
                    'file_size' => null,
                    'mime_type' => null,
                ];
                if ($content_type === 'file') {
                    if ($uploadedFile && $uploadedFile->isValid() && !$uploadedFile->hasMoved()) {
                        $mimeType = $uploadedFile->getMimeType();
                        $allowed_types = $this->getAllowedFileTypes($content_category);
                        if (!in_array($mimeType, $allowed_types)) {
                            $errors[] = "Invalid file type for {$content_category}.";
                            continue;
                        }
                        if ($uploadedFile->getSize() > 100_000_000) {
                            $errors[] = "File size must be less than 100MB for {$content_category}.";
                            continue;
                        }
                        $matDir = FCPATH . 'uploads/materials';
                        if (!is_dir($matDir)) {
                            mkdir($matDir, 0755, true);
                        }
                        $newFileName = $uploadedFile->getRandomName();
                        $uploadedFile->move($matDir, $newFileName);
                        $ci['file_path'] = 'uploads/materials/' . $newFileName;
                        $ci['file_name'] = $uploadedFile->getClientName();
                        $ci['file_size'] = $uploadedFile->getSize();
                        $ci['mime_type'] = $mimeType;
                    } else {
                        $errors[] = 'A valid file upload is required for content type "file".';
                        continue;
                    }
                } elseif ($content_type === 'link') {
                    if (empty($linkValue) || !filter_var($linkValue, FILTER_VALIDATE_URL)) {
                        $errors[] = "Invalid or missing URL for content type \"link\".";
                        continue;
                    }
                    $ci['link_url'] = $linkValue;
                }
                $content_items[] = $ci;
            }
        }
        if (!empty($errors)) {
            foreach ($errors as $error) {
                echo esc($error) . '<br/>';
            }
            return;
        }
        // Update main material record
        $material_data = [
            'title' => $title,
            'article_content' => $description,
            'thumbnail_path' => $thumbnail_path,
            'status' => 'pending',
            'updated' => date('Y-m-d H:i:s'),
        ];
        $this->materialModel->update($id, $material_data);
        // Optionally, update files (delete old, insert new)
        $this->materialFilesModel->where('material_id', $id)->delete();
        foreach ($content_items as $ci) {
            $ci['material_id'] = $id;
            $ci['author_id'] = $material['author_id'];
            $ci['created'] = date('Y-m-d H:i:s');
            $ci['updated'] = date('Y-m-d H:i:s');
            $this->materialFilesModel->insert($ci);
        }
        // return redirect()->to(base_url('Material/view?id=' . $id))
        //     ->with('success', 'Material updated successfully.');
        return redirect()->to(base_url('Material'))->with('success', 'Material updated successfully.');
    }

    public function postDelete()
    {
        $id = $this->request->getPost('id');
        if (!$id) {
            return redirect()->to(base_url('Material'))->with('error', 'No material ID provided.');
        }
        $material = $this->materialModel->getMaterialById($id);
        if (!$material) {
            return redirect()->to(base_url('Material'))->with('error', 'Material not found.');
        }
        // Only owner or admin with permission can delete
        $isOwner = $material['author_id'] == $this->session->get('session_user_id');
        $isAdmin = $this->lib_auth->check_permiss('material', 'changeStatus', true);
        if (!($isOwner || $isAdmin)) {
            return redirect()->to(base_url('Material/view?id=' . $id))
                ->with('error', 'You are not allowed to delete this material.');
        }
        // Delete material files
        $this->materialFilesModel->where('material_id', $id)->delete();
        // Delete material
        $this->materialModel->delete($id);
        return redirect()->to(base_url('Material'))->with('success', 'Material deleted successfully.');
    }

    public function postChangeStatus()
    {
        // Only admin with changeStatus permission
        if (! $this->lib_auth->check_permiss('material', 'changeStatus', true)) {
            return redirect()->to(base_url('Material'))->with('error', 'You are not allowed to change material status.');
        }
        $id = $this->request->getPost('id');
        $status = $this->request->getPost('status');
        if (!in_array($status, ['approved', 'rejected', 'pending'])) {
            return redirect()->to(base_url('Material'))->with('error', 'Invalid status.');
        }
        $material = $this->materialModel->getMaterialById($id);
        if (!$material) {
            return redirect()->to(base_url('Material'))->with('error', 'Material not found.');
        }
        $this->materialModel->update($id, [
            'status' => $status,
            'updated' => date('Y-m-d H:i:s'),
        ]);
        return redirect()->to(base_url('Material'))->with('success', 'Material status updated to ' . $status . '.');
    }


    private function getAllowedFileTypes($category)
    {
        $allowed_types = [
            'article' => ['text/plain', 'text/html', 'application/pdf', 'application/msword', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document'],
            'image' => ['image/jpeg', 'image/png', 'image/gif', 'image/webp', 'image/svg+xml'],
            'ebook' => ['application/pdf'],
            'audio' => ['audio/mpeg', 'audio/mp4', 'audio/wav', 'audio/ogg', 'audio/webm'],
            'video' => ['video/mp4', 'video/avi', 'video/quicktime', 'video/x-msvideo', 'video/webm']
        ];
        
        return $allowed_types[$category] ?? [];
    }
} 