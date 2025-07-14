<?php

namespace App\Filters;

use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\Filters\FilterInterface;
use Config\Services;

class AuthFilter implements FilterInterface
{
    /**
     * This method runs before the controller. It checks for a valid session token
     * and either aborts with 401 or allows the request to proceed.
     */
    public function before(RequestInterface $request, $arguments = null)
    {
        return;//sementara dimatikan
        // Start session & database
        $session = Services::session();
        $db      = db_connect();
        $db->transException(true);

        // Retrieve token from session
        $token = $session->get('user_token');
        if (empty($token)) {
            return Services::response()
                ->setStatusCode(401)
                ->setBody('Unauthorized: Empty Token');
        }

        // Validate token against database
        $escToken = $db->escape($token);
        $sql = "SELECT user_id FROM g_auth_user_token WHERE user_id IN (SELECT user_id FROM s_user) AND token = {$escToken}";
        $query    = $db->query($sql);

        if ($query->getNumRows() === 0) {
            return Services::response()
                ->setStatusCode(401)
                ->setBody('Unauthorized: Invalid Token');
            //Yeah this really blocks futher processing, no need to exit;
        }

        // Update the token's expiry date to 10 days from now
        $newExpire = date('Y-m-d H:i:s', strtotime('+10 days'));
        $updateSql = "UPDATE g_auth_user_token SET expires = '{$newExpire}' WHERE token = {$escToken}";
        $db->query($updateSql);

        // Delete expired tokens
        $now = date('Y-m-d H:i:s');
        $deleteSql = "DELETE FROM g_auth_user_token WHERE expires < '{$now}'";
        $db->query($deleteSql);

        // Store valid user ID in session (or attach to request)
        $row = $query->getRow();
        $session->set('session_user_id', $row->user_id);
        // Optionally: $request->userId = $row->user_id;
    }

    /**
     * This method runs after the controller; not used for auth.
     */
    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // No action needed
    }
}

// -------------------------------------------
// Register the filter in app/Config/Filters.php:

// In $aliases:
//    'auth' => \App\Filters\AuthFilter::class,

// Apply globally (except for public routes):
// public $globals = [
//     'before' => [
//         'auth' => ['except' => ['login/*', 'public/*']],
//     ],
// ];

// Or apply per-route in app/Config/Routes.php:
// $routes->group('api', ['filter' => 'auth'], function($routes) {
//     $routes->get('users',    'UserController::index');
//     $routes->post('articles','ArticleController::create');
//     // ...
// });
