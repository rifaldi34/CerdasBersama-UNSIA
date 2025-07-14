<?php
namespace App\Libraries;

class Lib_Auth {

    public function btn_checkpermis($appl_id, $role) {
        
        if ($this->check_permiss($appl_id, $role, true)) {
            return '';
        } else {
            return 'disabled onclick="return false;"';
        }

        return '';
    }

    public function validate_token($token= null): bool
    {
        if (empty($token)) {
            return false;
        }

        $db = db_connect();
        $db->transException(true);

        // Validate token against database
        $escToken = $db->escape($token);
        $sql = "SELECT user_id FROM g_auth_user_token WHERE user_id IN (SELECT user_id FROM s_user) AND token = {$escToken}";
        $query = $db->query($sql);

        if ($query->getNumRows() == 0) {
            return false;
        }

        // Update the token's expiry date to 10 days from now
        $newExpire = date('Y-m-d H:i:s', strtotime('+10 days'));
        $updateSql = "UPDATE g_auth_user_token SET expires = '{$newExpire}' WHERE token = {$escToken}";
        $db->query($updateSql);

        // Delete expired tokens
        $now = date('Y-m-d H:i:s');
        $deleteSql = "DELETE FROM g_auth_user_token WHERE expires < '{$now}'";
        $db->query($deleteSql);

        return true;
    }

    /**
     * Check if the current user has the given permission.
     *
     * @param  string  $appl_id   e.g. 'crudsample', 'material', 'comment'
     * @param  string  $role      e.g. 'view', 'add', 'edit', 'delete', 'approve'
     * @param  bool    $returnBool  If true, just return true/false instead of aborting.
     * @return bool
     * @throws \CodeIgniter\Exceptions\PageForbiddenException
     *
     * Example permissions:
     *   - material.add
     *   - material.approve
     *   - comment.add
     */
    public function check_permiss(string $appl_id, string $role, bool $returnBool = false): bool
    {
        // $appl_id = strtolower($appl_id);
        // $role = strtolower($role);
        // fetch the permissions array from your Registry (or session, etc.)
        $session = \Config\Services::session();
        $userPermissions = $session->get('user_permissions');
        
        // build the key
        $key = "{$appl_id}.{$role}";

        // simple in_array check
        $allowed = in_array($key, $userPermissions, true);
        
        if ($returnBool) {
            // just let the caller handle the result
            return $allowed;
        }
        
        if (! $allowed) {
            $response = \Config\Services::response();
            $response->setStatusCode(403)
                     ->setBody('403 Forbidden: you lack permission to ' . $key)
                     ->send();
            exit;
            // alternatively, redirect to a 'no-permission' page:
            // return redirect()->to('/unauthorized');
        }
        
        return false;
    }
}