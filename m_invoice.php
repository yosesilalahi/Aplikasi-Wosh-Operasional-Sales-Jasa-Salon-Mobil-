<?php


defined('BASEPATH') OR exit('No direct script access allowed');

class  m_invoice extends CI_Model {

	
		//===========edit profile user=========
		public function index(){

			date_default_timezone_set('Asia/jakarta');
			$id_kendaraan = $this->input->post('id_kendaraan');
			$total = $this->cart->total();

			$data = array(

				'id_kendaraan' => $id_kendaraan,
				'tgl_pesan' => date('Y-m-d H:i:s'),
				'harga' => $total


			);
			
			$this->db->insert('tb_invoice', $data);
			$id_invoice = $this->db->insert_id();
			
			foreach ($this->cart->contents() as $item) {
				# code...
				$data =  array(

					'id_invoice'   => $id_invoice,
					'id_jasa'	   => $item['id'],
					'nama_jasa'    => $item['name'],
					'jumlah'	   => $item['qty'],
					'harga' 	   => $item['price'],
				);

				$this->db->insert('tb_detail', $data);
			}

			// return TRUE; bingung disini pak.. strukturnya susah takut error soalnya pake cart
		}

		//==========Pembayaran=======
		public function tampil_pendapatan(){

			return $this->db->get('tb_invoice');
			if ($result->num_rows() > 0 ) {
				# code...
				return $result->result();
			}else{

				return false;
			}

		}

		//======Detail Pendapatan===
		public function detail_pendapatan($id_invoice){

			$result = $this->db->where('id', $id_invoice)->limit(1)->get('tb_invoice');
			if ($result->num_rows() > 0) {
				# code...
				return $result->row();
			}else{
				return false ;
			}
		}

		//======Detail Pendapatan======
		public function detail_pesanan($id_invoice){

			// return $this->db->get('tb_detail');
		$result = $this->db->where('id_invoice', $id_invoice)->get('tb_detail');
			if ($result->num_rows() > 0) {
				# code...
				return $result->result();
			}else{
				return false ;
			}
		}

		//==============Pagination=======
		public function pagination(){

			return $this->db->get('tb_invoice')->num_rows();
		}

		//=====get data pendapatan========
		public function get_pendapatan($limit,$start){
			$this->db->select('*, tb_invoice.id AS id_transaksi');
			$this->db->from('tb_invoice',$limit,$start);
			$this->db->join('login', 'tb_invoice.id_kasir = login.id', 'LEFT');
			return $this->db->get()->result_array();

		}

		//=========get keyword===========
		public function search_pendapatan(){

			$keyword = $this->input->post('keyword', true);
			$this->db->like('tgl_pesan', $keyword);
			$this->db->or_like('harga', $keyword);
			$this->db->or_like('id_kendaraan', $keyword);
			return $this->db->get('tb_invoice')->result_array();
		}

	
	

}

/* End of file model */
/* Location: ./application/models/model */


?>