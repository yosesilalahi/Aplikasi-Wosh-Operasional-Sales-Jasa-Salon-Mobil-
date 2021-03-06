<?php 

 defined('BASEPATH') OR exit('No direct script access allowed');
 
 class c_jasa extends CI_Controller {

 	function __construct(){

		parent::__construct();
		$this->load->model('m_jasa');
		$this->load->model('m_stok');
		$this->load->model('m_admin');
		$this->load->library('form_validation') ;


		//====agar orang lain tidak bisa akses URL====== itu copy di setiap controllernya admin line 15-20.
		if ($this->session->has_userdata('role_id') && $this->session->userdata('role_id') == 1) {
		}
		else {
			redirect('c_halaman_utama');
		}

	}
 	

 	//=========Tampilan awal pemilik=======
 	public function index(){

		$data['title'] = 'Jasa';

		$data['user'] = $this->db->get_where('login', ['email' =>
		$this->session->userdata('email')])->row_array();

		$where = 1 ;

		$data['jasa_cadangan'] = $this->m_jasa->tampil_jasa($where);

		$this->load->view('template/dashboard_header',$data);
		$this->load->view('template/dashboard_topbar',$data);
		$this->load->view('template/dashboard_sidebar',$data);
		$this->load->view('jasa/v_jasa(cadangan)',$data);
		$this->load->view('template/dashboard_footer',$data);

	}

	//===============delete produk==========
	public function delete_jasa($id){

		$where = array('id' => $id);

		//===========Model=================
		$this->m_jasa->delete_jasa($where);


		$this->session->set_flashdata('message', '<div class="alert alert-danger" role="alert">
  				Jasa Telah Dihapus
  			</div>');

		redirect('c_jasa');
	}

	//==============Edit Stok=========
		public function update(){

			$id = $this->input->post('id');
			$nama_jasa = $this->input->post('nama_jasa');
			
			$harga = $this->input->post('harga');



			$data1 = array(
				'nama_jasa' => $nama_jasa,
				
				'harga' => $harga
			);

			$where1 = array(
				'id' => $id
			);

			//=========aksi edit menu
			$this->m_jasa->update_jasa($where1,$data1);

			$this->session->set_flashdata('message', '<div class="alert alert-success" role="alert">
  					Jasa Telah Di Ubah	 
  				</div>');
			redirect('c_jasa');
						
		}


		//=======Tambah Jasa=============
		public function tambah_jasa(){

		$id_jasa = $this->m_stok->gen_id('tb_jasa', 'id', 'SRV-');
		$nama_jasa = $this->input->post('nama_jasa');
		$harga = $this->input->post('harga');
		$status = $this->input->post('status');

			$data= array(

				'id_jasa'   => $id_jasa,
				'nama_jasa' => $nama_jasa,
				'harga'		=> $harga,
				'status' 	=> 1
			);
			
			$cek = $this->m_jasa->tampil_jasa_id($nama_jasa);
			
			if($cek == 0){
			$this->db->insert('tb_jasa',$data);

			

			$this->session->set_flashdata('message', '<div class="alert alert-success" role="alert">
  						Jasa Telah Di inputkan
  					</div>');

			redirect('c_jasa');
			}
			else{
				$this->session->set_flashdata('message', '<div class="alert alert-danger" role="alert">
  						Jasa Telah Tersedia
  					</div>');

			redirect('c_jasa');
			}
		}


 	
 
 }
 
 /* End of file c_pemilik.php */
 /* Location: ./application/controllers/c_pemilik.php */ ?>