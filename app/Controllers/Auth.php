<?php

namespace App\Controllers;

class Auth extends BaseController
{
    protected $db;
	protected $mauth;

    public function initController(\CodeIgniter\HTTP\RequestInterface $request, \CodeIgniter\HTTP\ResponseInterface $response, \Psr\Log\LoggerInterface $logger)
    {
        // Call parent initController
        parent::initController($request, $response, $logger);

		$this->db = db_connect();
        $this->db->transException(true);  // Enable transaction exceptions (error when sql fails)
        $this->mauth = new \App\Models\M_Auth();

    }
    
    public function getLogin()
    {
        $session = session();

        $admin_token = $session->get('user_token');
        if (empty($admin_token)) {
        }else{
            $admin_token_q = $this->db->escape($admin_token);//escaped;
            $sql = "SELECT * 
                FROM g_auth_user_token 
                WHERE user_id IN (SELECT user_id FROM s_user) AND token = $admin_token_q";
            $query = $this->db->query($sql);

            $result = $query->getResult();

            if ($query->getNumRows() == 0) {
            }else{
                return redirect()->to(base_url('Cms/home'));
            }

        }

        return view('home/login_page');
        // return view('welcome_message');
    }

    public function getHello()
    {
        // echo 'Hello World!';

        // echo password_hash('QuickBrown123-123', PASSWORD_DEFAULT);
    }

    public function postLoginP(){
        $username = $this->request->getPost('username');
        $password = $this->request->getPost('password');

        // echo $username . $password;

        $data_g_admin = $this->db->table('s_user')
        ->select('*')
        ->where('username', $username)
        ->get();

        if ($data_g_admin->getNumRows() > 0) {
            $admin_id = $data_g_admin->getRow()->user_id;
            $password_real = $data_g_admin->getRow()->password;
            if (password_verify($password, $password_real)) {
                $token = $this->mauth->generateAuthTokenAdmin($admin_id);

                $session = \Config\Services::session();
                $session->set('user_token', $token);
                $session->set('session_user_id', $admin_id);
                return redirect()->to(base_url('Material'));
            } else {
                session()->setFlashdata('login_error', 'Password salah!');
                return redirect()->back()->withInput();
            }
        }else{
            session()->setFlashdata('login_error', 'User tidak ditemukan!');
            return redirect()->back()->withInput();
        }

    }

    public function getLogout()
    {
        $redirectHome = $this->request->getGet('redirecthome');

        $session = \Config\Services::session();
        $session->destroy();

        $redirectHome = true;//true
        if ($redirectHome)
        {
            return redirect()->to(base_url('Material'));
        }else{
            return redirect()->to(base_url('Auth/login'));
        }

        
    }


    public function getRegister()
    {
        return view('home/register_page');
    }

    public function postRegisterP()
    {
        helper('random_helper');
        $username = $this->request->getPost('username');
        $password = $this->request->getPost('password');
        $irl_name = $this->request->getPost('irl_name');
        $errors = [];

        // Basic validation
        if (empty($username) || empty($password) || empty($irl_name)) {
            $errors[] = 'All fields are required.';
        }
        if (strlen($username) < 4) {
            $errors[] = 'Username must be at least 4 characters.';
        }
        if (strlen($password) < 6) {
            $errors[] = 'Password must be at least 6 characters.';
        }
        // Check if username exists
        $exists = $this->db->table('s_user')->where('username', $username)->countAllResults();
        if ($exists > 0) {
            $errors[] = 'Username already taken.';
        }
        if ($errors) {
            return view('home/register_page', ['errors' => $errors, 'old' => compact('username', 'irl_name')]);
        }
        // Create user
        $user_id = uuidv7();
        $this->db->table('s_user')->insert([
            'user_id' => $user_id,
            'irl_name' => $irl_name,
            'username' => $username,
            'password' => password_hash($password, PASSWORD_DEFAULT),
            'group_id' => 'user',
            'status' => 'active',
            'created' => date('Y-m-d H:i:s'),
            'updated' => date('Y-m-d H:i:s'),
        ]);
        // Optionally, auto-login after registration
        $token = $this->mauth->generateAuthTokenAdmin($user_id);
        $session = \Config\Services::session();
        $session->set('user_token', $token);
        return redirect()->to(base_url('Material'));
    }
}
