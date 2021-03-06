<?php


defined('BASEPATH') OR exit('No direct script access allowed');

	class  m_booking extends CI_Model {

		public function jadwal(){

			return $this->db->get('stasiun');

		}

		//==========booking coba======
		public function booking_coba($where){

			$this->db->select('*');
			$this->db->from('tb_booking');
			$this->db->where('status', $where);
			$return=$this->db->get()->result();
			return $return;
		}

		//=========Acc Booking==============
		public function acc_coba($value,$booking,$table){

			$this->db->where('id', $booking);
			$this->db->update($table, $value);
		}

		//===========jenis_jasa_cuci====
		public function jasa_cuci(){

			return $this->db->get('jasa');

		}

		public function jasa_cuci1($harga){

			return $this->db->get('jasa',$harga);

		}


		//=========cari tiket======
		public function cari_jadwal($data){

			// $this->db->select('jadwal2.*, Asal.nama_stasiun AS ASAL, Tujuan.nama_stasiun AS TUJUAN');

			$this->db->select('jadwal2.*, Asal.nama_stasiun AS ASAL');
			$this->db->where($data);
			$this->db->like('tgl_berangkat', $this->input->post('tanggal'));
			$this->db->from('jadwal2');
			$this->db->join('stasiun as Asal','jadwal2.asal = Asal.id','left');
			// $this->db->join('stasiun as Tujuan','jadwal2.tujuan = Tujuan.id','left');
			return $this->db->get();
			
		}

		//===========getdatabooking=======
		public function getdatabooking($id){
			// $this->db->select('jadwal2.*, Asal.nama_stasiun AS ASAL, Tujuan.nama_stasiun AS TUJUAN');
			$this->db->select('jadwal2.*, Asal.nama_stasiun AS ASAL');
			$this->db->where('jadwal2.id',$id);
			$this->db->join('stasiun as Asal','jadwal2.asal = Asal.id','left');
			// $this->db->join('stasiun as Tujuan','jadwal2.tujuan = Tujuan.id','left');
			return $this->db->get('jadwal2');
		}


		
		//=============insert pemesan========
		public function insert_penumpang($data){
			return $this->db->insert('penumpang', $data);		
		}

		//==============insert pemesanan=======
		public function insert_pemesan($data){
			return $this->db->insert('tiket', $data);
		}

		//=========gettiket=============
		public function get_tiket(){
			return $this->db->get('tiket');
		}

		//=========getpembayaran========
		public function get_pembayaran(){
			return $this->db->get('pembayaran');
		}

		//======insert pembayaran=======
		public function insert_pembayaran($data){
			return $this->db->insert('pembayaran', $data);
		}

		//=====get data booking========
		public function get_databooking($limit,$start){

			return $this->db->get('tb_booking',$limit,$start)->result_array();

		}

		//=======update jadwal==========
		public function acc_jadwal($value,$booking,$table){

			$this->db->where('id', $booking);
			$this->db->update($table, $value);
		}

		//=========get keyword===========
		public function search(){

			$keyword = $this->input->post('keyword', true);
			$this->db->like('keterangan', $keyword);
			$this->db->or_like('id_booking', $keyword);
			return $this->db->get('tb_booking')->result_array();
		}

		//==========Pagination===========
		public function pagination(){

			return $this->db->get('tb_booking')->num_rows();
		}
		
		
		//==========getkelolajadwalbyid==
		public function getkelolabooking($id){

			return $this->db->get_where('tb_booking', ['id' => $id])->row_array();
		}


		//==============antrean pencucian=======
		public function antrean_pencucian($where){

			$this->db->select('*');
			$this->db->from('tb_booking');
			$this->db->where('status', $where);
			$return=$this->db->get()->result();
			return $return;
		}


		//====================================
	}
?>