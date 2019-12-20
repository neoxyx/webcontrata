<?php
class Login_model extends CI_Model {

        public function get_login($user,$pass)
        {
				
                $query = $this->db->get_where('users', array('user'=> $user,
				'pass'=>sha1($pass)));
				if ($query->num_rows() > 0) {					
					return $query->row();
				} else {
					return false;
				}
        }

}
?>
