<?php

namespace App\Controllers\Generated;
use App\Controllers\BaseController;

class CrudSample extends BaseController
{

    protected $db;
	protected $mauth;
    protected $lib_auth;
    protected $mapi;
    protected $session;
    protected $session_user_id;

    public function initController(\CodeIgniter\HTTP\RequestInterface $request, \CodeIgniter\HTTP\ResponseInterface $response, \Psr\Log\LoggerInterface $logger)
    {
        // Call parent initController
        parent::initController($request, $response, $logger);

		$this->db = db_connect();
        $this->db->transException(true);  // Enable transaction exceptions (error when sql fails)
        $this->mauth = new \App\Models\M_Auth();
        $this->lib_auth = new \App\Libraries\Lib_Auth();
        $this->mapi = new \App\Models\M_Api();
        $this->session = \Config\Services::session();
        $this->session_user_id = $this->session->get('session_user_id');


        //Get the user permissions
        $user_permissions = $this->mauth->get_user_permissions($this->session_user_id);
        //set user permissions to session
        $this->session->set('user_permissions', $user_permissions);
    }

    public function getIndex(){
        $this->lib_auth->check_permiss('crudsample', 'view');

        $pager = service('pager');
        $limit = 10;
        
        // Define the desired meta keys
        $baseBuilder = $this->db->table('t_testing_crud')->select('*');

        //START GET FOR PAGINATION ==========================
        $currentPage = $this->request->getGet('page');
        if (empty($currentPage)) {
            $currentPage = 1;
        }
        $sort_field = $this->request->getGet('sort_field');
        $sort_type = $this->request->getGet('sort_type');
        $filter_type = $this->request->getGet('filter_type');
		$filter_val = $this->request->getGet('filter_val');
        $order_by_arr = array(
            'rec_id' => 'asc'
        );

        $offset = $limit * ($currentPage - 1);

        if (!empty($sort_field) && !empty($sort_type)) {
            $order_by_arr = array(
                $sort_field => $sort_type,
            );
        }
        $filter_arr_and = null;
        if (!empty($filter_type) && !empty($filter_val)) {
            $filter_arr_and = array(
                $filter_type => $filter_val
            );
        }

        $compiled_to_pagination = $this->mapi->builderToPagination($baseBuilder, $limit, $offset, $filter_arr_and, null, $order_by_arr);
        $page_links = $pager->makeLinks($currentPage, $limit, $compiled_to_pagination['count_all'], 'custom_pagination');

        //====END GET FOR PAGINATION ==========================

        $lib_auth = $this->lib_auth;
        $data = array(
            'base_url_table' => 'Generated/CrudSample',
            'table_compiled_1' => $compiled_to_pagination,
            'pager_links' => $page_links,
            'lib_auth' => $lib_auth
        );
        
        echo view('cms/template/header');
        echo view('cms/Generated/crud_sample', $data);
        echo view('cms/template/footer');
    }

    public function getAdd(){
        $this->lib_auth->check_permiss('crudsample', 'add');

        $data = [
            'role' => 'add'
        ];

        echo view('cms/template/header');
        echo view('cms/Generated/crud_sample_add_edit', $data);
        echo view('cms/template/footer');
    }

    public function getEdit(){
        $this->lib_auth->check_permiss('crudsample', 'edit');

        $id = $this->request->getGet('id');
        $data_obj_table = $this->db->table('t_testing_crud')->where('rec_id', $id)->get()->getRow();
        $data = array(
            'data_obj_table' => $data_obj_table,
            'role' => 'edit'
        );
        echo view('cms/template/header');
        echo view('cms/Generated/crud_sample_add_edit', $data);
        echo view('cms/template/footer');
    }

    private function validation(array $dataPosted, string $action)
    {
        $validator = \Config\Services::validation();
        $action = strtolower($action);

        if ($action == 'add' || $action == 'edit') {
            // Define rules
            $rules = [
                'testing_name'        => 'required|alpha_numeric',
                'testing_description' => 'required|alpha_numeric',
            ];
            // (Optional) Custom error messages
            $messages = [
                'testing_name' => [
                    'required'      => 'Anda harus memberikan Nama Testing.',
                    'alpha_numeric' => 'Nama Testing hanya boleh mengandung huruf dan angka.',
                ],
                'testing_description' => [
                    'required'      => 'Anda harus memberikan Deskripsi Testing.',
                    'alpha_numeric' => 'Deskripsi Testing hanya boleh mengandung huruf dan angka.',
                ],
            ];

            $validator->setRules($rules, $messages);

            $final['accepted'] = false;
            if (! $validator->run($dataPosted))
            {
                $final['accepted'] = false;
                $final['errors'] = implode(" && ", $validator->getErrors());
                return $final;
            }
            $final['accepted'] = true;
            $final['errors'] = null;
            return $final;
        } elseif($action == 'delete') {
            $final['accepted'] = true;
            $final['errors'] = 'Cant Delete Testing';
            return $final;
        }
    }


    public function postProcess(){
        $role = $this->request->getGet('ROLE');
        $validate_only = $this->request->getGet('validate_only');

        if (strtolower($role) == 'add' || strtolower($role) == 'edit') {

            $validation = $this->validation($this->request->getPost(), $role);

            if ($validate_only == 'yes') {
                $validation['csrf_name'] = csrf_token();
                $validation['csrf_hash'] = csrf_hash();
                return $this->response->setJSON($validation);
                return;
            }else{
                if (! $validation['accepted']) {
                    echo '<h1>GAGAL !, Harap screenshot dan kirim Error ke admin</h1>';
                    echo json_encode($this->request->getPost());
                    echo '<br>';
                    echo json_encode($validation);
                    return;
                }
            }

            $rec_id = $this->request->getPost('rec_id');
            $testing_name = $this->request->getPost('testing_name');
            $testing_description = $this->request->getPost('testing_description');

            if (empty($rec_id)) {
                $this->lib_auth->check_permiss('crudsample', 'add');
                $data_insert = array(
                    'testing_name' => $testing_name,
                    'testing_description' => $testing_description,
                );

                $this->db->table('t_testing_crud')->insert($data_insert);
                $this->session->set('custom_success', 'Sample has been inserted');
            }elseif (!empty($rec_id)) {
                $this->lib_auth->check_permiss('crudsample', 'edit');
                $data_update = array(
                    'testing_name' => $testing_name,
                    'testing_description' => $testing_description,
                );

                $this->db->table('t_testing_crud')->where('rec_id', $rec_id)->update($data_update);
                $this->session->set('custom_success', 'Sample has been updated');
            }

            // $this->db->table('g_banner')->where('rec_id', $rec_id)->update($to_update);
            
            return redirect()->to(base_url('Generated/CrudSample'));

        }elseif (strtolower($role) == 'delete') {
            $validation = $this->validation($this->request->getPost(), $role);

            if ($validate_only == 'yes') {
                $validation['csrf_name'] = csrf_token();
                $validation['csrf_hash'] = csrf_hash();
                return $this->response->setJSON($validation);
                return;
            }else{
                if (! $validation['accepted']) {
                    $validation['csrf_name'] = csrf_token();
                    $validation['csrf_hash'] = csrf_hash();
                    return $this->response->setJSON($validation);
                    return;
                }
            }

            // Allow delete if user has either 'delete' or 'changeStatus' permission
            $canDelete = $this->lib_auth->check_permiss('crudsample', 'delete', true) || $this->lib_auth->check_permiss('crudsample', 'changeStatus', true);
            if (! $canDelete) {
                $validation_success['accepted'] = false;
                $validation_success['errors'] = 'You are not allowed to delete this item.';
                return $this->response->setJSON($validation_success);
            }

            $rec_id = $this->request->getPost('txt_id');
            $this->db->table('t_testing_crud')->where('rec_id', $rec_id)->delete();
            // $this->session->set('custom_success', 'Sample has been deleted');

            $validation_success['accepted'] = true;
            $validation_success['errors'] = 'OK';
            
            return $this->response->setJSON($validation_success);
        }
    }

    // public function getTestTextLimit(){
    //     $arr_permiss_test = [];
    //     for ($i=0; $i < 50000 ; $i++) { 
    //         $arr_permiss_test[] = 'testing.add_edit_delete_skibidi_lmao'.$i;
    //     }
    //     $json = json_encode($arr_permiss_test);

    //     // $this->db->table('t_testing_crud')->truncate();
    //     // return;

    //     $this->db->transBegin();

    //     //insert to [s_group]
    //     $this->db->table('s_group')->insert([
    //         'group_id' => 'testing',
    //         'group_name' => 'Testing',
    //         'description' => 'Testing',
    //         'permiss_json' => $json,
    //         'is_active' => 1,
    //         'created' => date('Y-m-d H:i:s')
    //     ]);
        
    //     if ($this->db->transStatus() === false) {
    //         $this->db->transRollback();
    //     } else {
    //         $this->db->transCommit();
    //     }

    //     //read from [s_group]
    //     $group = $this->db->table('s_group')
    //         ->where('group_id', 'testing')
    //         ->get()
    //         ->getRow();

    //     $permiss = json_decode($group->permiss_json);
    //     echo count($permiss);

    //     //delete
    //     $this->db->table('s_group')->where('group_id', 'testing')->delete();
    // }

    // public function getTestPerformance(){
    //     // return false;
    //     // $this->db->table('t_testing_crud')->truncate();
    //     // return;

    //     $this->db->transBegin();

    //     $last_number = $this->db->table('t_testing_crud')->countAllResults();
    //     $last_number = $last_number + 1;
        
    //     for ($z=0; $z <= 1 ; $z++) {     
    //         $data_insert = [];
    //         for ($i=$last_number; $i < $last_number + 10000 ; $i++) { 
    //             $arr_insert = [
    //                 'testing_name' => 'Testing Name '.$i,
    //                 'testing_description' => 'Testing Description '.$i
    //             ];
    //             $data_insert[] = $arr_insert;
    //         }
    //         $this->db->table('t_testing_crud')->insertBatch($data_insert);
    //     }
        
    //     if ($this->db->transStatus() === false) {
    //         $this->db->transRollback();
    //     } else {
    //         $this->db->transCommit();
    //     }
    // }
}
