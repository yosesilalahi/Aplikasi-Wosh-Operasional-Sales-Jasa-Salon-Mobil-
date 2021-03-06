<?php


defined('BASEPATH') OR exit('No direct script access allowed');

class  M_sidebar extends CI_Model {

		
		//=========insert data management======
		public function insert_menumanagement($data){

			return $this->db->insert('user_menu', $data);
		}

		//=========menu management=============
		public function menu_management(){

			return $this->db->get_where('user_menu');
		}

		//=============sub menu================
		public function submenu(){
			
			$query = "SELECT `user_sub_menu`.*, `user_menu`.`menu`
						FROM `user_sub_menu` JOIN `user_menu`
						  ON `user_sub_menu`.`menu_id` = `user_menu`.`id`
			";
			return $this->db->query($query);
		}

		//=========option submenu=============
		public function optionsubmenu(){
			
			return $this->db->get('user_menu');
		}

		//=========delete menu management======
		public function delete($where){

			$this->db->where($where);
			$this->db->delete('user_menu');
		}

		//=========edit menu management======
		public function menu_edit($where){

			return $this->db->get_where('user_menu',$where);
		}

		//=========Menu Bar=================
		public function update_menu($where1,$data1){

			$this->db->set($data1);
			$this->db->where($where1);
			$this->db->update('user_menu',$data1);

		}	

}

/* End of file model */
/* Location: ./application/models/model */


?>