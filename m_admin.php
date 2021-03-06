<?php


defined('BASEPATH') OR exit('No direct script access allowed');

class  m_admin extends CI_Model {

	
	//========Tampil data jumlah======
	public function jumlah_kendaraan(){

	 $this->db->select_sum('nama_kendaraan');
	 $query = $this->db->get('tb_kendaraan');
	 
		 if ($query->num_rows()>0) {
		 	# code...
		 	return $query->row()->nama_kendaraan ;
		 }
		 else{

		 	return 0 ;
		 }
	}


	function grafik_stok()
    {
        $this->db->group_by('nama_jasa');
        $this->db->select('nama_jasa, stok');
        return $this->db->from('tb_jasa')
          ->get()
          ->result();
    }

	public function cekLog($email)
	{
		$this->db->from("login");
		$this->db->where("email", $email);
		$this->db->where("role_id", 1);
		return $this->db->get();
	}
}

/* End of file model */
/* Location: ./application/models/model */


?>