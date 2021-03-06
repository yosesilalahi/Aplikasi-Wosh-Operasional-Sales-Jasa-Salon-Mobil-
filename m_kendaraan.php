<?php

defined('BASEPATH') OR exit('No direct script access allowed');

	class  m_kendaraan extends CI_Model {

		
		//==========insert data kendaraan==========
		public function insert_kendaraan($data){

			return $this->db->insert('tb_kendaraan', $data);
		}

		public function get_jasa(){

			return $this->db->get('tb_jasa');
		}

		//=====pagination========
		public function pagination(){

			return $this->db->get('tb_kendaraan')->num_rows();
		}
		
		//=====get data booking========
		public function get_kendaraan($limit,$start){

			return $this->db->get('tb_kendaraan',$limit,$start)->result_array();

		}

		//=========get keyword===========
		public function search_kendaraan(){

			$keyword = $this->input->post('keyword', true);
			$this->db->like('warna', $keyword);
			$this->db->or_like('id_kendaraan', $keyword);
			return $this->db->get('tb_kendaraan')->result_array();
		}

		//==========getkelolajadwalbyid==
		public function getkelolakendaraan($id){

			return $this->db->get_where('tb_kendaraan', ['id' => $id])->row_array();
		}

		//=========Lihat Data Kendaraan=======
		public function lihat_kendaraan(){

			return $this->db->get('tb_kendaraan');
		}

	}	


 ?>		
