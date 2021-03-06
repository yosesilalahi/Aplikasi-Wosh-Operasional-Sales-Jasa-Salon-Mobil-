<?php


defined('BASEPATH') OR exit('No direct script access allowed');

class  m_suplier extends CI_Model {

	
	//========Tampil data Suplier======
	public function tampil_suplier(){

	 	return $this->db->get('tb_suplier');

	}

	//========Tampil data Suplier ID======
	public function tampil_suplier_id($id){
		$this->db->where("LOWER(nama_suplier) = LOWER('".$id."')");
		return $this->db->get('tb_suplier');

   }

	//========Tampil data Stok Op name======
	public function tampil_stok_opname(){

	 	return $this->db->get('tb_stok_opname');

	}

	//=========Update Suplier=================
	public function update_suplier($where1,$data1){

		$this->db->set($data1);
		$this->db->where($where1);
		$this->db->update('tb_suplier',$data1);

	}	

	//===========Delete Suplier===========
	public function delete_suplier($where){

			$this->db->where($where);
			$this->db->delete('tb_suplier');
	}
}

/* End of file model */
/* Location: ./application/models/model */


?>