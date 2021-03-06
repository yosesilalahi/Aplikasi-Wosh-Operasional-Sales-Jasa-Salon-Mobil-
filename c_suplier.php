<?php 

 defined('BASEPATH') OR exit('No direct script access allowed');
 
	 class c_suplier extends CI_Controller {

	 	function __construct(){

			parent::__construct();
			$this->load->model('m_suplier');
			$this->load->library('form_validation') ;
			$this->load->model('m_admin');

			//====agar orang lain tidak bisa akses URL====== itu copy di setiap controllernya admin line 15-20.
			if ($this->session->has_userdata('role_id') && $this->session->userdata('role_id') == 1) {
			}
			else {
				redirect('c_halaman_utama');
			}
		}

		//===========Suplier===========

		public function index(){

	 		$data['title'] = 'Data Suplier';

			$data['user'] = $this->db->get_where('login', ['email' =>
			$this->session->userdata('email')])->row_array();

			$data['suplier'] = $this->m_suplier->tampil_suplier()->result();

			$this->load->view('template/dashboard_header',$data);
			$this->load->view('template/dashboard_topbar',$data);
			$this->load->view('template/dashboard_sidebar',$data);
			$this->load->view('suplier/v_suplier',$data);
			$this->load->view('template/dashboard_footer',$data);

 		}

 		//=============Stok=====================
 		// public function index(){

			// $data['title'] = 'Stok Produk';

			// $data['user'] = $this->db->get_where('login', ['email' =>
			// $this->session->userdata('email')])->row_array();

			// //=================Model=============
			// $data['menu'] = $this->m_sidebar->menu_management()->result();

	
			// $data['stok'] = $this->m_stok->tampil_stok()->result();



			// if ($this->form_validation->run() == false) {

			// 	$this->load->view('template/dashboard_header',$data);
			// 	$this->load->view('template/dashboard_topbar',$data);
			// 	$this->load->view('template/dashboard_sidebar',$data);
			// 	$this->load->view('stok/v_stok',$data);
			// 	$this->load->view('template/dashboard_footer',$data);

			// }else{

			// 	$data = array(

			// 		'menu' => $this->input->post('menu')
			// 	); 

			// 	$this->m_sidebar->insert_menumanagement($data);

			// 	$this->session->set_flashdata('message', '<div class="alert alert-success" role="alert">
  	// 					New menu added
  	// 				</div>');
			// 	redirect('c_menubar');
			// }


		//===============Stok Opname========
		public function stok_opname(){

			$data['title'] = 'Stok Opname';

			$data['user'] = $this->db->get_where('login', ['email' =>
			$this->session->userdata('email')])->row_array();

			$data['stok_opname'] = $this->m_stok->tampil_stok_opname()->result();

			$this->load->view('template/dashboard_header',$data);
			$this->load->view('template/dashboard_topbar',$data);
			$this->load->view('template/dashboard_sidebar',$data);
			$this->load->view('stok/v_stok_opname',$data);
			$this->load->view('template/dashboard_footer',$data);

		}


		//===============Tambah Data Suplier=========
		public function tambah_suplier(){

			$data=[

				'nama_suplier' =>$this->input->post('nama_suplier'),
				'no_telp'		=>$this->input->post('no_telp'),
				'alamat'		=>$this->input->post('alamat')
			]; 
			$sup = $this->m_suplier->tampil_suplier_id($this->input->post('nama_suplier'))->num_rows();
			if($sup == 0){
				$this->db->insert('tb_suplier',$data);
				$this->session->set_flashdata('message', '<div class="alert alert-success" role="alert">
							  Suplier Telah Ditambahkan
						  </div>');
						  
						  redirect('c_suplier');
						}
						else{
							$this->session->set_flashdata('message', '<div class="alert alert-danger" role="alert">
							Supplier Telah Tersedia
							</div>');
							
							redirect('c_suplier');
						}

			
		}

		//===============Tambah Suplier=========
		public function tambah_stok_opname(){

			$data=[

				'id_jasa'  =>$this->input->post('id_jasa'),
				'nama_jasa' =>$this->input->post('nama_jasa'),
				'stok'		=>$this->input->post('stok'),
				'harga'		=>$this->input->post('harga')
			]; 

			$this->db->insert('tb_stok_opname',$data);

			$this->session->set_flashdata('message', '<div class="alert alert-success" role="alert">
  						Stok Telah Ditambahkan
  					</div>');

			redirect('c_stok/stok_opname');
		}

		//==============Edit Suplier=========
		public function update(){

			
			$id_suplier = $this->input->post('id_suplier');
			$nama_suplier = $this->input->post('nama_suplier');
			$no_telp = $this->input->post('no_telp');
			$alamat = $this->input->post('alamat');

			$data1 = array(
				'id_suplier' => $id_suplier,
				'nama_suplier' => $nama_suplier,
				'no_telp' => $no_telp,
				'alamat' => $alamat
			);

			$where1 = array(
				'id_suplier' => $id_suplier
			);

			//=========aksi edit suplier
			$this->m_suplier->update_suplier($where1,$data1);

			$this->session->set_flashdata('message', '<div class="alert alert-success" role="alert">
  					suplier telah diubah	 
  				</div>');
			redirect('c_suplier');
						
		}

		//=========aksi delete suplier=======
		public function delete_suplier($id){

			$where = array('id_suplier' => $id);

			//===========Model=================
			$this->m_suplier->delete_suplier($where);

			$this->session->set_flashdata('message', '<div class="alert alert-danger" role="alert">
  						suplier telah dihapus
  					</div>');

			redirect('c_suplier');
		}




		

	}
?>