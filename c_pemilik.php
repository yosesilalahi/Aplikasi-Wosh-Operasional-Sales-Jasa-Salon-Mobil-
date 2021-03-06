<?php 

 defined('BASEPATH') OR exit('No direct script access allowed');
 
 class c_pemilik extends CI_Controller {

 	function __construct(){

		parent::__construct();
		$this->load->model('m_pemilik');
		$this->load->library('form_validation') ;

		//====agar orang lain tidak bisa akses URL======
		if ($this->session->has_userdata('role_id') && $this->session->userdata('role_id') == 3) {
		}
		else{
			redirect('c_halaman_utama');
		}
	}
 	

 	//=========Tampilan awal pemilik=======
 	public function index(){

 		$data['title'] = 'Dashboard';

		$data['user'] = $this->db->get_where('login', ['email' =>
		$this->session->userdata('email')])->row_array();

		$this->load->view('template/dashboard_header',$data);
		$this->load->view('template/dashboard_topbar',$data);
		$this->load->view('template/dashboard_sidebar',$data);
		$this->load->view('admin/v_pemilik',$data);
		$this->load->view('template/dashboard_footer',$data);
 	}

 	//===========role=====================

	public function role(){

		$data['title'] = 'Role';

		$data['user'] = $this->db->get_where('login', ['email' =>
		$this->session->userdata('email')])->row_array();

		$data['role'] = $this->m_pemilik->role()->result();

		$this->load->view('template/dashboard_header',$data);
		$this->load->view('template/dashboard_topbar',$data);
		$this->load->view('template/dashboard_sidebar',$data);
		$this->load->view('admin/v_role',$data);
		$this->load->view('template/dashboard_footer',$data);
	}

	//========roleacces=====================
	public function roleacces(){

		$data['title'] = 'Role Acces';

		$data['user'] = $this->db->get_where('login', ['email' =>
		$this->session->userdata('email')])->row_array();

		$data['role'] = $this->m_pemilik->roleacces()->result();

		$this->load->view('template/user_header', $data); 
		$this->load->view('template/user_sidebar', $data);
		$this->load->view('template/user_topbar', $data);
		$this->load->view('admin/v_role', $data);
		$this->load->view('template/user_footer', $data);
	}
 
 }
 
 /* End of file c_pemilik.php */
 /* Location: ./application/controllers/c_pemilik.php */ ?>