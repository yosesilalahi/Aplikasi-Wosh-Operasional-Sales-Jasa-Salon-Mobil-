<?php 

 defined('BASEPATH') OR exit('No direct script access allowed');
 
	 class c_invoice extends CI_Controller {

	 	function __construct(){

			parent::__construct();
			$this->load->model('m_invoice');
			$this->load->library('form_validation') ;
		}

		//===========Kelola Pajak===========
		public function index(){

	 		$data['title'] = 'Pendapatan';

			$data['user'] = $this->db->get_where('login', ['email' =>
			$this->session->userdata('email')])->row_array();

			$pendapatan = $this->m_invoice->index();

			if ($pendapatan)  {
				# code...
				

			}else{

				$this->cart->destroy();

				$this->session->set_flashdata('message', '<div class="alert alert-success" role="alert">
  						Data anda sudah tersinput l!!!
  					</div>');

				redirect('c_transaksi/pembayaran');
			}

 		}

 		//=========LIHAT DATA TRANSAKSI====
 		public function lihat_pendapatan(){

 		$data['title'] = 'Lihat Data Pendapatan' ;

		$data['user'] = $this->db->get_where('login', ['email' =>
		$this->session->userdata('email')])->row_array();

		/*$data['pendapatan'] = $this->m_invoice->tampil_pendapatan()->result();

			$this->load->view('template/dashboard_header',$data);
			$this->load->view('template/dashboard_topbar',$data);
			$this->load->view('template/dashboard_sidebar',$data);
			$this->load->view('transaksi/v_pendapatan',$data);
			$this->load->view('template/dashboard_footer',$data);*/

			$config['total_rows'] = $this->m_invoice->pagination();
			$config['per_page'] = 6 ;
			

			//===config=====
			// $config['base_url'] = 'http://localhost/wash/c_booking/kelola_data';

	    	$config['base_url'] = 'http://localhost/wash/c_invoice/lihat_pendapatan';

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
			$data['pendapatan'] = $this->m_invoice->get_pendapatan($config['per_page'], $data['start']);

			if ($this->input->post('keyword')) {
			# code...
			$data['pendapatan'] = $this->m_invoice->search_pendapatan();
		
			}
			// echo "<pre>";
			// print_r($data['pendapatan']);
			$this->load->view('template/dashboard_header',$data);
			$this->load->view('template/dashboard_topbar',$data);
			$this->load->view('template/dashboard_sidebar',$data);
			$this->load->view('transaksi/v_pendapatan',$data);
			$this->load->view('template/dashboard_footer',$data);
 		}

 		//=========Detail===================
 		public function detail($id_invoice){
			 $data['id_transaksi'] = $id_invoice;

 			$data['title'] = 'Detail Data Pendapatan' ;

			$data['user'] = $this->db->get_where('login', ['email' =>
			$this->session->userdata('email')])->row_array();

 			$data['invoice'] = $this->m_invoice->detail_pendapatan($id_invoice);
 			$data['makan'] = $this->m_invoice->detail_pesanan($id_invoice);

 			$this->load->view('template/dashboard_header',$data);
			$this->load->view('template/dashboard_topbar',$data);
			$this->load->view('template/dashboard_sidebar',$data);
			$this->load->view('transaksi/v_detail_pendapatan',$data);
			$this->load->view('template/dashboard_footer',$data);
 		}








	}
?>