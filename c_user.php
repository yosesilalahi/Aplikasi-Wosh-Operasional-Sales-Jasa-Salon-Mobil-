<?php

defined('BASEPATH') OR exit('No direct script access allowed');
	

class c_user extends CI_controller{

	function __construct(){

		parent::__construct();
		$this->load->model('m_user');
		$this->load->library('form_validation') ;

		//====agar orang lain tidak bisa akses URL====== itu copy di setiap controllernya admin line 15-20.
		
	}


	public function index(){
		if ($this->session->has_userdata('role_id') && $this->session->userdata('role_id') == 4 
		|| $this->session->has_userdata('role_id') && $this->session->userdata('role_id') == 3
		|| $this->session->has_userdata('role_id') && $this->session->userdata('role_id') == 2
		|| $this->session->has_userdata('role_id') && $this->session->userdata('role_id') == 1) {
	

		$data['title'] = 'My Profile';

		$data['user'] = $this->db->get_where('login', ['email' =>
		$this->session->userdata('email')])->row_array();


		// $this->load->view('template/user_header', $data); 
		// $this->load->view('template/user_sidebar', $data);
		// $this->load->view('template/user_topbar', $data);
		// $this->load->view('user/user_dashboard', $data);
		// $this->load->view('template/user_footer', $data);

		$this->load->view('template/dashboard_header',$data);
		$this->load->view('template/dashboard_topbar',$data);
		$this->load->view('template/dashboard_sidebar',$data);
		$this->load->view('user/user_dashboard',$data);
		$this->load->view('template/dashboard_footer',$data);
		// echo "<pre>";
		// print_r($this->session->userdata());
		}
		else {
			redirect('c_halaman_utama');
		}
	}

	public function edit(){
		if ($this->session->has_userdata('role_id') && $this->session->userdata('role_id') == 1
		|| $this->session->has_userdata('role_id') && $this->session->userdata('role_id') == 2) {

		$data['title'] = 'Edit Profile' ;

		$data['user'] = $this->db->get_where('login', ['email' => 
		$this->session->userdata('email')])->row_array();


		$this->form_validation->set_rules('name', 'Ful name', 'required|trim');

		if ($this->form_validation->run() == false) {
			$this->load->view('template/dashboard_header',$data);
			$this->load->view('template/dashboard_topbar',$data);
			$this->load->view('template/dashboard_sidebar',$data);
			$this->load->view('user/edit_user',$data);
			$this->load->view('template/dashboard_footer',$data);
		}else{

			$name = $this->input->post('name');
			$email = $this->input->post('email');

			//cek jika ada gambar yang akan di uploud
			$upload_image = $_FILES['image'];

			if ($upload_image) {
				# code...
				
				$config['allowed_types'] = 'gif|jpg|png';
				$config['max_size']  = '2048';
				$config['upload_path'] = './DASHBOARD sb-admin 2/Admin/img/';
				

				$this->load->library('upload', $config);

				if ($this->upload->do_upload('image')) {
					# code...
					$new_image = $this->upload->data('file_name');
					$this->db->set('image',$new_image);

				}else{
					echo $this->upload->display_errors();
				}
			}
			
			/*$data = array(

				'name' => $name,	
				'email' => $email
			);

			$where = array(

				'email' => $email
			);

			//model
			$this->m_user->edit($where,$data);*/

			$this->db->set('name',$name);
			$this->db->where('email',$email);
			$this->db->update('login');

			$this->session->set_flashdata('message', 
				'<div class="alert alert-success" role="alert">
		  			Your Profile has been update!!!  	 
		  		</div>');
			
			redirect('c_user');
		}
	}
	else {
		redirect('c_halaman_utama');
	}
	}

	

}