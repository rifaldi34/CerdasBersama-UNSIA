<?php 

namespace App\Models;

use CodeIgniter\Model;

class M_Auth extends Model
{

	public function generateAuthTokenAdmin($admin_id){
		helper('random_helper');
		$uuidv7 = uuidv7();
		$this->db->table('g_auth_user_token')
		->insert([
			'token' => $uuidv7,
			'user_id' => $admin_id,
			'created' => date('Y-m-d H:i:s'),
		]);
		return $uuidv7;
	}

    public function get_user_permissions($user_id) {
        //select user then grab the group then select the group permission
        $user_data = $this->db->table('s_user')
        ->where('user_id', $user_id)
        ->get();

        if ($user_data->getNumRows() == 0) {
            return [];
        }
        $user_data = $user_data->getRow();

        $group_data = $this->db->table('s_group')
        ->where('group_id', $user_data->group_id)
        ->get();
        if ($group_data->getNumRows() == 0) {
            return [];
        }

        $permis_data = $group_data->getRow()->permiss_json;
        return json_decode($permis_data, true);
    }

}


?>