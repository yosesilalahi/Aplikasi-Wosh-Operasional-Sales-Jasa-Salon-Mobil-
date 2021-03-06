<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class  m_pemilik extends CI_Model {

	

	//===========role=========
	public function role(){

		return $this->db->get('user_role');
		
	}

	//===========roleacces=========
	public function roleacces(){

		return $this->db->get_where('user_role', ['role_id' => $role_id]);
		
	}

		

}


 ?>