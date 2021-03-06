<?php


defined('BASEPATH') OR exit('No direct script access allowed');

class  m_produk_jual extends CI_Model {

	
	//========Tampil data Stok======
	
		//===========Tampil Jasa=========
		public function tampil_produk_jual(){

			$this->db->from('tb_produk');
			$this->db->join('tb_jasa', 'tb_produk.id_produk = tb_jasa.id_produk', 'LEFT');
			return $this->db->get();
   
	   }

	   public function update_produk_jual($where1,$data1){

		$this->db->set($data1);
		$this->db->where($where1);
		$this->db->update('tb_produk',$data1);

		}	

		//===========Delete Suplier===========
		public function delete_produk_jual($where){

				$this->db->where($where);
				$this->db->delete('tb_produk');
		}

		public function cekProduk($nama)
		{
			$this->db->from('tb_produk');
			$this->db->where("LOWER(nama_produk) = LOWER('".$nama."')");
			return $this->db->get();
		}
	
	
	
	}
	?>

    