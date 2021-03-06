<?php 
defined('BASEPATH') OR exit('No direct script access allowed');

class c_booking extends CI_Controller {

	function __construct(){
		parent::__construct();
		$this->load->model('m_booking');
		$this->load->model('m_admin');
		$this->load->library('form_validation');
		$this->load->library('pagination');

		//====agar orang lain tidak bisa akses URL====== itu copy di setiap controllernya admin line 15-20.
		if ($this->session->has_userdata('role_id') && $this->session->userdata('role_id') == 1
		|| $this->session->has_userdata('role_id') && $this->session->userdata('role_id') == 2) {
		}
		else {
			redirect('c_halaman_utama');
		}

	}

		//===========Booking===============
		public function index(){

			$data['title'] = 'Booking';
			
			$data['user'] = $this->db->get_where('login', ['email' =>
			$this->session->userdata('email')])->row_array();

			
			// $data['jadwal'] = $this->m_booking->jadwal()->result();

			// $data['jasa'] = $this->m_booking->jasa_cuci()->result();
 			
 			
 				$this->load->view('template/dashboard_header',$data);
				$this->load->view('template/dashboard_topbar',$data);
				$this->load->view('template/dashboard_sidebar',$data);
				$this->load->view('booking/v_booking',$data);
				$this->load->view('template/dashboard_footer',$data);	
 				
		}


		public function waiting_list(){

			$data['title'] = 'jadwal Tersedia';

			$data['user'] = $this->db->get_where('login', ['email' =>
			$this->session->userdata('email')])->row_array();

			$where=0 ;
			$data['booking_coba'] = $this->m_booking->booking_coba($where);

			$this->load->view('template/dashboard_header',$data);
			$this->load->view('template/dashboard_topbar',$data);
			$this->load->view('template/dashboard_sidebar',$data);
			$this->load->view('booking/booking_coba',$data);
			$this->load->view('template/dashboard_footer',$data);
		}

		//===================accep==========
		public function accept($value){

			$status = 1 ;

			$booking = array(

				'status' => $status
			);

			$this->m_booking->acc_coba($booking,$value,'tb_booking');


			

			redirect('c_kendaraan');

		}

		public function cari_jadwal(){

			$data['title'] = 'carijadwal'; 

			$asal = $this->input->post('asal');
			// $tujuan = $this->input->post('tujuan');
			
				$data = array(
					'asal' => $asal,
					// 'tujuan' => $tujuan
				);

			$data['cek'] = $this->m_booking->cari_jadwal($data)->result();

			$data['penumpang'] = $this->input->post('jumlah');

			$this->load->view('template/dashboard_header',$data);
			$this->load->view('template/dashboard_topbar',$data);
			$this->load->view('template/dashboard_(khusus)');
			$this->load->view('booking/coba',$data);
			$this->load->view('template/dashboard_footer',$data);
		}


		//=======================pesan==================
		public function pesan($id){


			$data['pesan'] = $this->m_booking->getdatabooking($id)->row();

			$data['id_jadwal'] = $id;

			$data['jasa'] = $this->m_booking->jasa_cuci1($id)->row();
			
			$this->load->view('template/dashboard_header',$data);
			$this->load->view('template/dashboard_topbar',$data);
			$this->load->view('template/dashboard_(khusus)');
			$this->load->view('booking/pesan',$data);
			$this->load->view('template/dashboard_footer',$data);
			
		}

		//===============================================
		public function pesan_tiket(){
				
			$penumpang = $this->input->post('penumpang');

			//generate no pembayaran
			$cek = $this->m_booking->get_pembayaran()->num_rows()+1;

			$no_pembayaran = 'ACPE'.$cek;

			$harga = $this->input->post('harga');

			$ppn = 10 ;
			
			// $harga = $this->input->post('jasa_cuci');

			$total_pembayaran = $penumpang*$harga;

			$no_tiket = 'TTR'.$cek;

				//=====input pembayaran=====
				$data = array(

					'no_pembayaran' =>	$no_pembayaran,
					'total_pembayaran' => $total_pembayaran,
					'no_tiket' => $no_tiket,
					'status' => 0
				);

				//=============Model insert pembayaran=========
				$this->m_booking->insert_pembayaran($data);


			//======generate Nomor tiket
			$cek = $this->m_booking->get_tiket()->num_rows()+1;

		
			//===input data penumpang
			for($i=1; $i<=$penumpang; $i++ ){

				$data = array(
					
							'nomor_tiket' => $no_tiket,
							'nama' => $this->input->post('nama'.$i),
							'no_identitas' => $this->input->post('identitas'.$i),
				);

				$this->m_booking->insert_penumpang($data);
			}

				//===input data pemesan
				$data = array(
					'nomor_tiket' => $no_tiket,
					'id_jadwal' => $this->input->post('id_jadwal'),
					'nama_pemesan' => $this->input->post('nama_pemesan'),
					'email' => $this->input->post('email'),
					'no_telepon' => $this->input->post('no_telp'),
					'alamat' => $this->input->post('alamat')
				);	

			//=============Model insert pemesan=========
			$this->m_booking->insert_pemesan($data);

			$this->session->set_flashdata('nomor', $no_pembayaran);
			$this->session->set_flashdata('total', $total_pembayaran);

			$this->load->view('template/dashboard_header');
			$this->load->view('template/dashboard_topbar');
			$this->load->view('template/dashboard_(khusus)');
			$this->load->view('transaksi/pembayaran', $total_pembayaran);
			$this->load->view('template/dashboard_footer');
		}


		//==========Kelola jadwal========
		public function kelola_data(){

		$data['title'] = 'Kelola Jadwal' ;

		$data['user'] = $this->db->get_where('login', ['email' =>
		$this->session->userdata('email')])->row_array();

		//===config=====
		$config['total_rows'] = $this->m_booking->pagination();
		$config['per_page'] = 5 ;
		

		//initilize
		$this->pagination->initialize($config);


		$data['start'] = $this->uri->segment(3);
		$data['kelola_booking'] = $this->m_booking->get_databooking($config['per_page'], $data['start']);

		if ($this->input->post('keyword')) {
			# code...
			$data['kelola_booking'] = $this->m_booking->search();
		
		}
		
				$this->load->view('template/dashboard_header',$data);
				$this->load->view('template/dashboard_topbar',$data);
				$this->load->view('template/dashboard_sidebar',$data);
				$this->load->view('booking/v_kelola_jadwal',$data);
				$this->load->view('template/dashboard_footer',$data);
						
		}


		//==============edit data jadwal========
		public function edit_data_jadwal($id){

		$data['title'] = 'Edit Data jadwal' ;

		$data['kelola_booking'] = $this->m_booking->getkelolabooking($id);

		$this->form_validation->set_rules('id_booking', 'Id_booking', 'required');
		$this->form_validation->set_rules('jam_booking', 'Jam_Booking', 'required');
		$this->form_validation->set_rules('tanggal_booking', 'Tanggal_booking', 'required');
		$this->form_validation->set_rules('keterangan', 'Keterangan', 'required');

		if ($this->form_validation->run() == false) {
			# code...
			$this->load->view('template/dashboard_header',$data);
			$this->load->view('template/dashboard_topbar',$data);
			$this->load->view('template/dashboard_(khusus)',$data);
			$this->load->view('booking/v_edit_jadwal',$data);
			$this->load->view('template/dashboard_footer',$data);

		} else {
			
			//=============edit data=========

			$data = [

				"id_booking" 	    => $this->input->post('id_booking', true),
				"jam_booking" 		=> $this->input->post('jam_booking', true),
				"tanggal_booking"   => $this->input->post('tanggal_booking',true),
				"keterangan" 		=> $this->input->post('keterangan', true)
			] ; 


			$this->db->where('id', $this->input->post('id'));
			$this->db->update('tb_booking', $data);

			$this->session->set_flashdata('message', '<div class="alert alert-success" role="alert">
  						Succes your edit jadwal!!!
  					</div>');

			redirect('c_booking/kelola_data');
			
			}	
		}




		//==============Tambah data jadwal======

		public function tambah(){

			$data=[

				'id_booking' 		=>$this->input->post('id_booking'),
				'jam_booking' 		=>$this->input->post('jam_booking'),
				'tanggal_booking'	=>$this->input->post('tanggal_booking'),
				'keterangan'		=>$this->input->post('keterangan')
			]; 

			$this->db->insert('tb_booking',$data);

			$this->session->set_flashdata('message', '<div class="alert alert-success" role="alert">
  						New menu added
  					</div>');

			redirect('c_booking/kelola_data');
		}

		//==============Hapus jadwal=============
		public function delete_jadwal($id){

			$where = array('id' => $id);

			//===========Model=================
		$this->db->delete('tb_booking', $where);


			$this->session->set_flashdata('message', '<div class="alert alert-danger" role="alert">
  						Jadwal after delete
  					</div>');
			redirect('c_booking/kelola_data');

		}

		//=============Update status jadwal======
		public function update_jadwal($value){

			$status = 0 ;

			$booking = array(

				'status' => $status
			);

			$this->m_booking->acc_jadwal($booking,$value,'tb_booking');

			$this->session->set_flashdata('message', '<div class="alert 
			alert-success"role="alert">
  				Succes your update jadwal!!!
  			</div>');

			redirect('c_booking/kelola_data');

		}

		//==============menampilkan data antrea=====
		public function antrean_pencucian(){

			$data['title'] = 'Antrean Pencucian';

			$data['user'] = $this->db->get_where('login', ['email' =>
			$this->session->userdata('email')])->row_array();

			$where=3 ;

			$data['antrean_pencucian'] = $this->m_booking->antrean_pencucian($where);

			$this->load->view('template/dashboard_header',$data);
			$this->load->view('template/dashboard_topbar',$data);
			$this->load->view('template/dashboard_sidebar',$data);
			$this->load->view('booking/antrean_pencucian',$data);
			$this->load->view('template/dashboard_footer',$data);

		}

		//===================accep==========
		public function upproval($value){

			$status = 3 ;

			$booking = array(

				'status' => $status
			);

			$this->m_booking->acc_coba($booking,$value,'tb_booking');

				$this->session->set_flashdata('message', '<div class="alert 
				alert-success"role="alert">
	  				Succes your Upproval !!!
	  			</div>');

			redirect('c_booking/kelola_data');

		}

		
	}
?>