<?php 
	
	defined('BASEPATH') OR exit('No direct script access allowed');
	
	class c_transaksi extends CI_Controller {

	function __construct(){

		parent::__construct();
		$this->load->model('m_transaksi');
		$this->load->library('form_validation');

		if ($this->session->has_userdata('role_id') && $this->session->userdata('role_id') == 4) {
		}
		else {
			redirect('c_halaman_utama');
		}

	}
		//===========Transaksi=================
		public function pembayaran(){
		
			$data['title'] = 'Pembayaran';

			$data['user'] = $this->db->get_where('login', ['email' =>
			$this->session->userdata('email')])->row_array();

			// $data['jasa_cuci'] = $this->m_transaksi->pembayaran_coba()->result();

			$this->load->view('template/dashboard_header',$data);
			$this->load->view('template/dashboard_topbar',$data);
			$this->load->view('template/dashboard_sidebar',$data);
			$this->load->view('transaksi/v_jasa',$data);
			$this->load->view('template/dashboard_footer',$data);

		}

		//========Tambah keranjang===============
		public function tambah_keranjang($id){

			$jasa = $this->m_transaksi->find($id);
			
			if($jasa->harga_jual == null){
				$harga = $jasa->harga;
			}
			elseif($jasa->harga_jual !== null){
				$harga = $jasa->harga_jual;
			}

			$data = array(
				'id'      => $jasa->id,
		        'qty'     => 1,
		        'price'   => $harga,
		        'name'    => $jasa->nama_jasa,
		       
			);

			$this->cart->insert($data);

			redirect('c_transaksi/pembayaran');
		}

		
		//========reset keranjang======
		public function reset_keranjang(){

			$this->cart->destroy();
			redirect('c_transaksi/pembayaran');
		}

		//========Menampilkan produk dan jasa=====
		public function tampil_produk_jasa(){

			$data['title'] = 'Jasa Dan Produk';

			$data['user'] = $this->db->get_where('login', ['email' =>
			$this->session->userdata('email')])->row_array();

			if(isset($_POST['submit'])){
				$data['jasa_cuci'] = $this->m_transaksi->cariProJas($this->input->post('cari'))->result();
			}
			else{
				$data['jasa_cuci'] = $this->m_transaksi->tampil_produkjasa()->result();
			}

			$this->load->view('template/dashboard_header',$data);
			$this->load->view('template/dashboard_topbar',$data);
			$this->load->view('template/dashboard_sidebar',$data);
			$this->load->view('transaksi/v_produk_jasa',$data);
			$this->load->view('template/dashboard_footer',$data);
		}


		//============Pembayaran =================
		public function bayar(){

			$data['title'] = 'Bayar' ;

			$data['user'] = $this->db->get_where('login', ['email' =>
			$this->session->userdata('email')])->row_array();

			if ($this->form_validation->run() == false) {
				# code...
				$this->load->view('template/dashboard_header',$data);
				$this->load->view('template/dashboard_topbar',$data);
				$this->load->view('template/dashboard_sidebar',$data);
				$this->load->view('transaksi/v_form_bayar',$data);
				$this->load->view('template/dashboard_footer',$data);
			}else{

				redirect('c_invoice');

			}
		}

		//===========Fitur Print Struk=============
		public function print(){
		
			
		
		}
	
	}
?>