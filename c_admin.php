 <?php

defined('BASEPATH') OR exit('No direct script access allowed');
	

class c_admin extends CI_controller{

	function __construct(){

		parent::__construct();
		$this->load->model('m_admin');
		$this->load->library('form_validation') ;
		
		//====agar orang lain tidak bisa akses URL======
		//Ini di copy ke [c_kendaraan, c_booking, c_stok, c_jasa, c_supplier] //dah pak
		//LINE 17-21
		if ($this->session->has_userdata('role_id') && $this->session->userdata('role_id') == 1) {
		}
		else {
			redirect('c_halaman_utama');
		}
	}


	public function index(){

		$data['title'] = 'Dashboard';

		$data['user'] = $this->db->get_where('login', ['email' =>
		$this->session->userdata('email')])->row_array();

		$data['tampil_data'] = $this->db->from('tb_kendaraan')->get()->num_rows();


		$data['jumlah_user'] = $this->db->from('login')->get()->num_rows();


		$data['jumlah_data_booking'] = $this->db->from('tb_booking')->get()->num_rows();


		$data['hasil']=$this->m_admin->grafik_stok();


		$this->load->view('template/dashboard_header',$data);
		$this->load->view('template/dashboard_topbar',$data);
		$this->load->view('template/dashboard_sidebar',$data);
		$this->load->view('admin/v_admin',$data);
		$this->load->view('template/dashboard_footer',$data);

		// echo "<pre>";
		// print_r($this->session->userdata());
	}



	

}