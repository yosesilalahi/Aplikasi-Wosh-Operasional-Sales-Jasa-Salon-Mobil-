<?php


defined('BASEPATH') OR exit('No direct script access allowed');

class  m_transaksi extends CI_Model {

		


		//==========get pembayaran==========
		public function get_pembayaran_where($kode){
			
			$this->db->where('no_pembayaran', $kode);
			return $this->db->get('pembayaran');
		}

		//===========Cek pembayaran=========
		public function cek_konfirmasi_pembayaran($nomor){

			$this->db->where('nomor_tiket', $nomor);
			return $this->db->get('penumpang');
		}
		
		//==========Tampil Produk dan Jasa=======
		public function tampil_produkjasa(){
			$this->db->from('tb_jasa');
			$this->db->join('tb_produk', 'tb_jasa.id_produk = tb_produk.id_produk', 'LEFT');
			return $this->db->get();
		}

		//search
		public function cariProJas($cari)
		{
			$this->db->from('tb_jasa');
			$this->db->join('tb_produk', 'tb_jasa.id_produk = tb_produk.id_produk', 'LEFT');
			$this->db->like('nama_jasa', $cari);
			return $this->db->get();
		}

		//==============find=============
		public function find($id){
			$this->db->from('tb_jasa');
			$this->db->join('tb_produk', 'tb_jasa.id_produk = tb_produk.id_produk', 'LEFT');
			$result = $this->db->where('id', $id)->limit(1)->get();

			if ($result->num_rows() > 0 ) {

				return $result->row();

			}else{

				return array();
			}
								
		}

		//==========Jasa ==================
		public function jasa(){
			
		}
		
	

}

/* End of file model */
/* Location: ./application/models/model */


?>