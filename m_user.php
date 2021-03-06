<?php


defined('BASEPATH') OR exit('No direct script access allowed');

class  m_user extends CI_Model {

	
		//===========edit profile user=========
		public function edit($where,$data){

			$this->db->set($data);
			$this->db->where($where);
			$this->db->update('login',$data);

		}	
	
	

}

/* End of file model */
/* Location: ./application/models/model */


?>