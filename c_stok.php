<?php 

 defined('BASEPATH') OR exit('No direct script access allowed');
 
	 class c_stok extends CI_Controller {

	 	function __construct(){

			parent::__construct();
			$this->load->model('m_stok');
			$this->load->model('m_jasa');
			$this->load->model('m_suplier');
			$this->load->model('m_admin');
			$this->load->library('form_validation') ;

			if ($this->session->has_userdata('role_id') && $this->session->userdata('role_id') == 1) {
			}
			else {
				redirect('c_halaman_utama');
			}
		}

		//===========Kelola Stok===========

		public function index(){

	 		$data['title'] = 'Stok Produk';

			$data['user'] = $this->db->get_where('login', ['email' =>
			$this->session->userdata('email')])->row_array();

			$where = 0 ;
			
			$data['stok'] = $this->m_stok->tampil_stok($where);
			$data['suplier'] = $this->m_suplier->tampil_suplier()->result();
			$data['produk_jual'] = $this->m_stok->produk_jual()->result();
			

			// print_r($data['jasa']);

			if(isset($_POST['tgl_cari'])){
				$tgl = $this->input->post();
				$data['stok'] = $this->m_stok->tampil_stok_tgl($tgl['dari'], $tgl['sampai']);
			}

			$this->load->view('template/dashboard_header',$data);
			$this->load->view('template/dashboard_topbar',$data);
			$this->load->view('template/dashboard_sidebar',$data);
			$this->load->view('stok/v_stok',$data);
			$this->load->view('template/dashboard_footer',$data);

		 }		 

		 public function index2()
		 {
			$data['jasa'] = $this->m_jasa->tampil_jasa('1');
			$data['suplier'] = $this->m_suplier->tampil_suplier()->result();
			$this->load->view('contoh',$data);
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


		//===============Stok In========
		public function stok_opname(){

			$data['title'] = 'Stok Opname';

			$data['user'] = $this->db->get_where('login', ['email' =>
			$this->session->userdata('email')])->row_array();

			$data['stok_opname'] = $this->m_stok->tampil_stok_opname()->result();
			$data['stok_opname_dd'] = $this->m_stok->tampil_stok_opname_dd()->result();

			$this->load->view('template/dashboard_header',$data);
			$this->load->view('template/dashboard_topbar',$data);
			$this->load->view('template/dashboard_sidebar',$data);
			$this->load->view('stok/v_stok_opname',$data);
			$this->load->view('template/dashboard_footer',$data);

		}


		//===============Tambah Stok===============
		public function tambah(){
			$produk = $this->m_stok->produk_jual_id($this->input->post('id_produk'))->result();
			$nama_jasa_db = $this->m_stok->where_produk_name($this->input->post('id_produk'))->num_rows();
			
			if($nama_jasa_db == 0){
				$data=[

					'id_jasa'		=> $produk[0]->kode_produk,
					'id_produk'		=> $this->input->post('id_produk'),
					'nama_jasa'		=> $produk[0]->nama_produk,
					'id_suplier'	=> $this->input->post('id_suplier'),
					'stok'			=> $this->input->post('stok'),
					'stok_masuk'	=> $this->input->post('stok'),
					'harga'			=> $this->input->post('harga')
				];
				$this->db->insert('tb_jasa',$data);
				$this->session->set_flashdata('message', '<div class="alert alert-success" role="alert">
  						New menu added
  					</div>');

				redirect('c_stok');
			}
			else{
				$produk = $this->m_stok->where_produk_name($this->input->post('id_produk'))->result();
				$sisaStokTotal = $produk[0]->stok + $this->input->post('stok');
				$masukStokTotal = $produk[0]->stok_masuk + $this->input->post('stok');
				$data=[
					'stok'			=> $sisaStokTotal,
					'stok_masuk'	=> $masukStokTotal,
					'id_suplier' 	=> $this->input->post('id_suplier'),
					'harga'			=> $this->input->post('harga'),
					'created'		=> date("Y-m-d H:i:s")
				];
				
				$this->m_stok->update_stok_masuk($this->input->post('id_produk'), $data);

				$this->session->set_flashdata('message', '<div class="alert alert-success" role="alert">
  						Data Stok Berhasil Ditambah
  					</div>');
				redirect('c_stok');
			}

			
		}

		//===============Tambah Stok Opname=========
		public function tambah_stok_opname(){
			$form = $this->input->post();
			
			$produk = $this->m_stok->where_produk($form['produk'])->result();

			$data = array(
				'id_jasa' 	=> $form['produk'],
				'nama_jasa' => $produk[0]->nama_jasa,
				'stok'		=> $form['stok']
			);

			$this->db->insert('tb_stok_opname',$data);

			$update = array('stok' => $form['stok']);
			$this->m_stok->update_stok($form['produk'],$update);
			
			$this->session->set_flashdata('message', '<div class="alert alert-success" role="alert">
  						stok telah ditambahkan
  					</div>');

			redirect('c_stok/stok_opname');

			// $data=[

			// 	'id_jasa'  =>$this->input->post('id_jasa'),
			// 	'nama_jasa' =>$this->input->post('nama_jasa'),
			// 	'stok'		=>$this->input->post('stok'),
			// 	'harga'		=>$this->input->post('harga')
			// ]; 

			// echo "<pre>";
			// print_r($form);
			// echo "</pre>";
		}

		//===============delete produk==========
		public function delete_produk($id){

			$where = array('id' => $id);

			//===========Model=================
			$this->m_stok->delete_stok($where);


			$this->session->set_flashdata('message', '<div class="alert alert-danger" role="alert">
  						Data stok telah dihapus
  					</div>');

			redirect('c_stok');
		}

		//==============Edit Stok=========
		public function update(){

			$id = $this->input->post('id');
			$id_jasa = $this->input->post('id_jasa');
			$nama_jasa = $this->input->post('nama_jasa');
			$id_suplier = $this->input->post('id_suplier');
			$stok = $this->input->post('stok');
			$harga = $this->input->post('harga');

			$data1 = array(
				'id_jasa' => $id_jasa,
				'nama_jasa' => $nama_jasa,
				'id_suplier' =>$id_suplier,
				'stok' => $stok,
				'harga' => $harga
			);

			$where1 = array(
				'id' => $id
			);

			//=========aksi edit menu
			$this->m_stok->update_stok($id_jasa,$data1);

			$this->session->set_flashdata('message', '<div class="alert alert-success" role="alert">
  					Data stok telah diubah  	 
  				</div>');
			redirect('c_stok');
		}




		

	}
?>