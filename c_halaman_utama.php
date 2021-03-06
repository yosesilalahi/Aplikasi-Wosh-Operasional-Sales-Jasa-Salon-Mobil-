<?php

defined('BASEPATH') OR exit('No direct script access allowed');
	

class c_halaman_utama extends CI_controller{
		
		function __construct(){

		parent::__construct();
		//$this->load->model('model');
		$this->load->library('form_validation') ;

		}

		
		//======Halaman Utama Login=====
		public function index(){

			$this->form_validation->set_rules('email', 'Email', 'trim|required|valid_email');
			$this->form_validation->set_rules('password', 'Password', 'trim|required');

			if($this->form_validation->run() == false){
				$data['title'] = 'login page'; 
				// $this->load->view('TEMPLATE_login/v_login',$data);
				$this->load->view('template/login_header', $data);
				$this->load->view('login/v_login');
				$this->load->view('template/login_footer');
			}else{
				//validasinya succes 
				$this->_login();

				//diakses di kelas private
			}
		}

		//=======kelas yg hanya diakses oleh login OR Index======
		private function _login(){

			$email = $this->input->post('email');
			$password = $this->input->post('password');

			$user = $this->db->get_where('login', ['email' => $email])->row_array();
			
			//jika usernya ada
			if ($user) {
				//jika usernya aktif
				if ($user['is_active'] == 1) {
					//cek password
					if (password_verify($password, $user['password'])) {
						
						$data = [

							'email' => $user['email'],
							'role_id' => $user['role_id']
						];

						//jika role_id = 1 maka dia adalah admin
						$this->session->set_userdata($data);
							if ($user['role_id'] == 1 ) {
								# code...
									redirect('c_admin'); // admin

								}elseif  ($user['role_id'] == 2 ){

									redirect('c_booking');//pelanggan

								}else{

									redirect('c_user');	//pemilik
							}
						
					}else{
						$this->session->set_flashdata('message', 
						'<div class="alert alert-danger" role="alert">
	  						Salah Kata sandi !!!
	  					</div>');
						redirect('c_halaman_utama');	
					}
					
				}else{
					$this->session->set_flashdata('message', 
					'<div class="alert alert-danger" role="alert">
  						Email Belum Di aktivasi!!!
  					</div>');
					redirect('c_halaman_utama');
				}
				
			}else{
				$this->session->set_flashdata('message', 
					'<div class="alert alert-danger" role="alert">
  						Email belum Terdaftar !!!
  					</div>');
				redirect('c_halaman_utama');		
			}


		}

		//======Halaman Utama Registrasion=====
		public function registrasion(){



			$this->form_validation->set_rules('name', 'Name', 'required|trim');
			$this->form_validation->set_rules('email', 'Email', 'required|trim|valid_email|is_unique[login.email]', [
				'is_unique' => 'Email sudah terdaftar'
			]);
			$this->form_validation->set_rules('password1', 'Password', 'required|trim|min_length[3]|matches[password2]',[
				'matches' => 'Password dont match!',
				'min_length' => 'Password too short!'
			]);
			$this->form_validation->set_rules('password2', 'Password', 'required|trim|min_length[3]|matches[password1]');
			


			if ($this->form_validation->run() == false) {
					# code...
				$data['title'] = 'registrasion' ;
				$this->load->view('template/registrasion_header',$data);
				$this->load->view('registrasion/registrasion');
				$this->load->view('template/registrasion_footer');
			}else{

				$data = [

					'name' => htmlspecialchars($this->input->post('name', true)),
					'email' => htmlspecialchars($this->input->post('email', true)),
					'image' => 'default.jpg',
					'password' => password_hash($this->input->post('password1'), PASSWORD_DEFAULT),
					'role_id' => 2,
					'is_active' => 1,
					'date_created' => time()

				];

				$this->db->insert('login', $data);

				$this->session->set_flashdata('message', 
					'<div class="alert alert-success" role="alert">
  						Congratulation! your account has been created. Please Login
  					</div>');
				redirect('c_halaman_utama');
			}
		}

		//====logout=====
		public function logout(){

			$this->session->unset_userdata('email');
			$this->session->unset_userdata('role_id');

			$this->session->set_flashdata('message', 
					'<div class="alert alert-success" role="alert">
  						Anda Sudah Keluar Sistem!  	 
  					</div>');
				redirect('c_halaman_utama');
		}


		//=======Halaman Utama=====
		public function home_page(){

			$this->load->view('TEMPLATE_home_page/home_page_wash');
		}


		//=======Halaman Utama=====
		public function coba_login(){

			$this->load->view('TEMPLATE_login/v_login');
		}
}
	
	
	/* End of file c_halaman_utama.php */
	/* Location: ./application/controllers/c_halaman_utama.php */

?>