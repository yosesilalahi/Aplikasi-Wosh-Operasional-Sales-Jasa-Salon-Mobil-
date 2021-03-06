<?php 

 defined('BASEPATH') OR exit('No direct script access allowed');
 
	 class c_pajak extends CI_Controller {

	 	function __construct(){

			parent::__construct();
			$this->load->model('m_pajak');
			$this->load->library('form_validation') ;

			//====agar orang lain tidak bisa akses URL======
			if (!$this->session->userdata('email')) {
				redirect('c_halaman_utama');
			}

		}

		//===========Kelola Pajak===========
		public function index(){

	 		$data['title'] = 'Kelola Pajak';

			$data['user'] = $this->db->get_where('login', ['email' =>
			$this->session->userdata('email')])->row_array();

			$this->load->view('template/dashboard_header',$data);
			$this->load->view('template/dashboard_topbar',$data);
			$this->load->view('template/dashboard_sidebar',$data);
			$this->load->view('pajak/v_pajak',$data);
			$this->load->view('template/dashboard_footer',$data);
 		}








	}
?>