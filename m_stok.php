<?php


defined('BASEPATH') OR exit('No direct script access allowed');

class  m_stok extends CI_Model {

	
	//========Tampil data Stok======
	public function tampil_stok($where){

		$this->db->select('*');
		$this->db->from('tb_jasa a');
		$this->db->join('tb_suplier b', 'a.id_suplier = b.id_suplier');
		$this->db->where('status', $where);
		$this->db->order_by('a.id', 'DESC');
		$return=$this->db->get()->result();
		return $return;
	}

	//========Tampil data Stok filter tgl======
	public function tampil_stok_tgl($dari, $sampai)
	{
		$this->db->select('*');
		$this->db->from('tb_jasa a');
		$this->db->join('tb_suplier b', 'a.id_suplier = b.id_suplier');
		$this->db->where("date(a.created) BETWEEN '".$dari."' AND '".$sampai."'");
		$this->db->order_by('a.id', 'DESC');
		$return=$this->db->get()->result();
		return $return;
	}

	//========Tampil data Produk Jual======
	public function produk_jual()
	{
		return $this->db->get('tb_produk');
	}

	//========Tampil data Produk Jual Where ID======
	public function produk_jual_id($id)
	{
		$this->db->where('id_produk', $id);
		return $this->db->get('tb_produk');
	}

	//========Tampil data Stok opname Dropdown======
	public function tampil_stok_opname_dd(){
		$this->db->from('tb_jasa');
		$this->db->where('status', 0);
	 	return $this->db->get();

	}

	//========Update Data Stok Masuk======
	public function update_stok_masuk($id, $data){
		$this->db->where('id_produk', $id);
		$this->db->update('tb_jasa', $data);
	}

	//========Tampil data Stok opname Tabel======
	public function tampil_stok_opname(){
		$this->db->from('tb_stok_opname');
		$this->db->order_by('created','DESC');
	 	return $this->db->get();

	}

	//===========Delete stok===========
	public function delete_stok($where){

		$this->db->where($where);
		$this->db->delete('tb_jasa');
	}


	//=========Update Stok=================
	public function update_stok($where1,$data1)
	{
		$this->db->where('id_jasa', $where1);
		$this->db->update('tb_jasa',$data1);
	}
	
	//Where product id
	public function where_produk($id_jasa)
	{
		$this->db->from('tb_jasa');
		$this->db->where('id_jasa', $id_jasa);
		return $this->db->get();
	}

	//Where product name
	public function where_produk_name($nama_jasa)
	{
		$this->db->from('tb_jasa');
		$this->db->where('id_produk', $nama_jasa);
		return $this->db->get();
	}

	//Generate Kode
	public function gen_id($table, $primaryKey, $kodeDepan)   {
		$this->db->select('RIGHT('.$table.'.'.$primaryKey.',4) as kode', FALSE);
		$this->db->order_by($primaryKey,'DESC');    
		$this->db->limit(1);    
		$query = $this->db->get($table);      //cek dulu apakah ada sudah ada kode di tabel.    
		if($query->num_rows() <> 0){      
		 //jika kode ternyata sudah ada.      
		 $data = $query->row();      
		 $kode = intval($data->kode) + 1;    
		}
		else {      
		 //jika kode belum ada      
		 $kode = 1;    
		}
		$kodemax = str_pad($kode, 4, "0", STR_PAD_LEFT); // angka 4 menunjukkan jumlah digit angka 0
		$kodejadi = $kodeDepan.$kodemax;    // hasilnya ODJ-9921-0001 dst."EMPL-1018-"
		return $kodejadi;  
  }
	
}

/* End of file model */
/* Location: ./application/models/model */


?>