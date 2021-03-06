	<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class c_kendaraan extends CI_Controller {

	function __construct(){

		parent::__construct();
		$this->load->model('m_kendaraan');
		$this->load->model('m_admin');
		$this->load->library('form_validation');

		//====agar orang lain tidak bisa akses URL====== itu copy di setiap controllernya admin line 15-20.
		if ($this->session->has_userdata('role_id') && $this->session->userdata('role_id') == 1 
		|| $this->session->has_userdata('role_id') && $this->session->userdata('role_id') == 3
		|| $this->session->has_userdata('role_id') && $this->session->userdata('role_id') == 2) {
		}
		else {
			redirect('c_halaman_utama');
		}

	}

	public function index(){

		$data['title'] = 'Form kendaraan';

		$data['user'] = $this->db->get_where('login', ['email' =>
		$this->session->userdata('email')])->row_array();

		$this->form_validation->set_rules('nama_kendaraan', 'Nama_kendaraan', 'required');

	

			if ($this->form_validation->run() == FALSE) {
				$this->load->view('template/dashboard_header',$data);
				$this->load->view('template/dashboard_topbar',$data);
				$this->load->view('template/dashboard_sidebar',$data);
				$this->load->view('kendaraan/form_kendaraan',$data);
				$this->load->view('template/dashboard_footer',$data);
			} else {
				
				//============Inpu data jasa==========
				$data = array(

					'id_kendaraan' =>$this->input->post('id_kendaraan'),
					'nama_kendaraan' =>$this->input->post('nama_kendaraan'),
					'warna' => $this->input->post('warna')
							
				);

				$this->m_kendaraan->insert_kendaraan($data);

				$this->session->set_flashdata('message', '<div class="alert alert-success" role="alert">
  						Terimakasih sudah melakukan booking silahkan tunggu Confirm dari admin!!!
  					</div>');

				redirect('c_booking');
			}
		}


		//================Kelola data kendaraaan=============
		public function kelola_kendaraan(){


		$data['title'] = 'Kelola kendaraan' ;

		$data['user'] = $this->db->get_where('login', ['email' =>
		$this->session->userdata('email')])->row_array();

		//===config=====
		$config['total_rows'] = $this->m_kendaraan->pagination();
		$config['per_page'] = 6 ;
		

		//===config=====
		// $config['base_url'] = 'http://localhost/wash/c_booking/kelola_data';

    	$config['base_url'] = 'http://localhost/wash/c_kendaraan/kelola_kendaraan';

		// $config['num_links'] = 2 ;

		//styling 
		$config['full_tag_open'] = '<nav><ul class="pagination justify-content-center">';
  		$config['full_tag_close'] = '</ul></nav>';

							
  		$config['first_link'] = 'First';
  		$config['first_tag_open'] = ' <li class="page-item">';
  		$config['first_tag_close'] = '</li>';


  		$config['last_link'] = 'Last';
  		$config['last_tag_open'] = ' <li class="page-item">';
  		$config['last_tag_close'] = '</li>';


  		$config['next_link'] = '&raquo';
  		$config['next_tag_open'] = ' <li class="page-item">';
  		$config['next_tag_close'] = ' </li>';

  		$config['prev_link'] = '&laquo';
  		$config['prev_tag_open'] = ' <li class="page-item">';
  		$config['prev_tag_close'] = '</li>';

  		$config['cur_tag_open'] = ' <li class="page-item active"><a class="page-link" href="#">';
  		$config['cur_tag_close'] = ' </a>';

  		$config['num_tag_open'] = ' <li class="page-item">';
  		$config['num_tag_close'] = '</li>';

  		$config['attributes'] = array('class'  =>'page-link');
		//initilize
		// $this->pagination->initialize($config);

		$this->pagination->initialize($config);


		$data['start'] = $this->uri->segment(3);
		$data['kelola_kendaraan'] = $this->m_kendaraan->get_kendaraan($config['per_page'], $data['start']);

		if ($this->input->post('keyword')) {
			# code...
			$data['kelola_kendaraan'] = $this->m_kendaraan->search_kendaraan();
		
		}
		
				$this->load->view('template/dashboard_header',$data);
				$this->load->view('template/dashboard_topbar',$data);
				$this->load->view('template/dashboard_sidebar',$data);
				$this->load->view('kendaraan/v_kelola_kendaraan',$data);
				$this->load->view('template/dashboard_footer',$data);
		}

		//==============Hapus data kendaraan=============
		public function delete_kendaraan($id){

			$where = array('id' => $id);

			//===========Model=================
		$this->db->delete('tb_kendaraan', $where);


			$this->session->set_flashdata('message', '<div class="alert alert-danger" role="alert">
  						kendaraan after delete
  					</div>');

			redirect('c_kendaraan/kelola_kendaraan');

		}

		//==============edit data kendaraan========
		public function edit_data_kendaraan($id){

		$data['title'] = 'Edit Data kendaraan' ;

		$data['kelola_kendaraan'] = $this->m_kendaraan->getkelolakendaraan($id);

		$this->form_validation->set_rules('id_kendaraan', 'Id_kendaraan', 'required');
		$this->form_validation->set_rules('nama_kendaraan', 'Nama_kendaraan', 'required');
		$this->form_validation->set_rules('warna', 'Warna', 'required');
	
		if ($this->form_validation->run() == false) {
			# code...
			$this->load->view('template/dashboard_header',$data);
			$this->load->view('template/dashboard_topbar',$data);
			$this->load->view('template/dashboard_(khusus)');
			$this->load->view('kendaraan/v_edit_kendaraan',$data);
			$this->load->view('template/dashboard_footer',$data);

		} else {
			
			//=============edit data=========

			$data = [

				"id_kendaraan" 	    => $this->input->post('id_kendaraan', true),
				"nama_kendaraan" 		=> $this->input->post('nama_kendaraan', true),
				"warna"   => $this->input->post('warna',true)
				
			] ; 


			$this->db->where('id', $this->input->post('id'));
			
			$this->db->update('tb_kendaraan', $data);

			$this->session->set_flashdata('message', '<div class="alert alert-success" role="alert">
  						Succes your edit kendaraan!!!
  					</div>');

			redirect('c_kendaraan/kelola_kendaraan');
			
			}	
		}


		//==============Tambah data kendaraan======

		public function tambah_data_kendaraan(){

			$data=[

				'id_kendaraan' 		=>$this->input->post('id_kendaraan'),
				'nama_kendaraan' 		=>$this->input->post('nama_kendaraan'),
				'warna'	=>$this->input->post('warna')
				
			]; 

			$this->db->insert('tb_kendaraan',$data);

			$this->session->set_flashdata('message', '<div class="alert alert-success" role="alert">
  						vehicle new added
  					</div>');

			redirect('c_kendaraan/kelola_kendaraan');
		}



		//============Lihat data kendaraan========
		public function lihat_data_kendaraan(){

		$data['title'] = 'Data Kendaraan' ; 

		$data['user'] = $this->db->get_where('login', ['email' =>
		$this->session->userdata('email')])->row_array();

/*
		$data['data_kendaraan'] = $this->m_kendaraan->lihat_kendaraan()->result();

			$this->load->view('template/dashboard_header',$data);
			$this->load->view('template/dashboard_topbar',$data);
			$this->load->view('template/dashboard_sidebar',$data);
			$this->load->view('kendaraan/v_lihat_kendaraan',$data);
			$this->load->view('template/dashboard_footer',$data);	*/

			//===config=====
		$config['total_rows'] = $this->m_kendaraan->pagination();
		$config['per_page'] = 6 ;
		

		//===config=====
		// $config['base_url'] = 'http://localhost/wash/c_booking/kelola_data';

    	$config['base_url'] = 'http://localhost/wash/c_kendaraan/lihat_data_kendaraan';

		// $config['num_links'] = 2 ;

		//styling 
		$config['full_tag_open'] = '<nav><ul class="pagination justify-content-center">';
  		$config['full_tag_close'] = '</ul></nav>';

							
  		$config['first_link'] = 'First';
  		$config['first_tag_open'] = ' <li class="page-item">';
  		$config['first_tag_close'] = '</li>';


  		$config['last_link'] = 'Last';
  		$config['last_tag_open'] = ' <li class="page-item">';
  		$config['last_tag_close'] = '</li>';


  		$config['next_link'] = '&raquo';
  		$config['next_tag_open'] = ' <li class="page-item">';
  		$config['next_tag_close'] = ' </li>';

  		$config['prev_link'] = '&laquo';
  		$config['prev_tag_open'] = ' <li class="page-item">';
  		$config['prev_tag_close'] = '</li>';

  		$config['cur_tag_open'] = ' <li class="page-item active"><a class="page-link" href="#">';
  		$config['cur_tag_close'] = ' </a>';

  		$config['num_tag_open'] = ' <li class="page-item">';
  		$config['num_tag_close'] = '</li>';

  		$config['attributes'] = array('class'  =>'page-link');
		//initilize
		// $this->pagination->initialize($config);

		$this->pagination->initialize($config);


		$data['start'] = $this->uri->segment(3);
		$data['data_kendaraan'] = $this->m_kendaraan->get_kendaraan($config['per_page'], $data['start']);

		if ($this->input->post('keyword')) {
			# code...
			$data['data_kendaraan'] = $this->m_kendaraan->search_kendaraan();
		
		}	
				$this->load->view('template/dashboard_header',$data);
				$this->load->view('template/dashboard_topbar',$data);
				$this->load->view('template/dashboard_sidebar',$data);
				$this->load->view('kendaraan/v_lihat_kendaraan',$data);
				$this->load->view('template/dashboard_footer',$data);
		}
		

		
	

	}

?>