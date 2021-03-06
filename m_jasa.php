<?php


defined('BASEPATH') OR exit('No direct script access allowed');

class  m_jasa extends CI_Model {

	
		//===========Tampil Jasa=========
		public function tampil_jasa($where){

			$this->db->select('*');
			$this->db->from('tb_jasa');
			$this->db->where('status', $where);
			$return=$this->db->get()->result();
			return $return;
		}

		//===========Tampil Jasa=========
		public function tampil_jasa_id($where){

			$this->db->from('tb_jasa');
			$this->db->where("LOWER(nama_jasa) = LOWER('".$where."')");
			$this->db->where('status', 1);
			$return = $this->db->get()->num_rows();
			return $return;
		}

		//===========Delete Jasa===========
		public function delete_jasa($where){

			$this->db->where($where);
			$this->db->delete('tb_jasa');
		}


		//=========Update jasa=============
		public function update_jasa($where1,$data1){

		$this->db->set($data1);
		$this->db->where($where1);
		$this->db->update('tb_jasa',$data1);

		}

		

	
	
	

}

/* End of file model */
/* Location: ./application/models/model */


?>