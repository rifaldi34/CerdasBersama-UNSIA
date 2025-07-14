<?php

namespace App\Controllers;

use Kreait\Firebase\Factory;
use Kreait\Firebase\Messaging\CloudMessage;
use Kreait\Firebase\Messaging\Notification;
use Kreait\Firebase\Messaging\AndroidConfig;
use Kreait\Firebase\Messaging\AndroidNotification;
use CodeIgniter\Files\File;

class Cms extends BaseController
{

    protected $db;
	protected $mauth;
    protected $session;
    protected $session_user_id;

    public function initController(\CodeIgniter\HTTP\RequestInterface $request, \CodeIgniter\HTTP\ResponseInterface $response, \Psr\Log\LoggerInterface $logger)
    {
        // Call parent initController
        parent::initController($request, $response, $logger);

		$this->db = db_connect();
        $this->db->transException(true);  // Enable transaction exceptions (error when sql fails)
        $this->mauth = new \App\Models\M_Auth();
        $this->session = \Config\Services::session();
    }

    public function getHome(){
        echo view('cms/template/header');
        echo view('cms/template/footer');
    }
}
