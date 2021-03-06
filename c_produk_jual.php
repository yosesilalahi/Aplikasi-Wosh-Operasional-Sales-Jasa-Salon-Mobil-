<?php 

 defined('BASEPATH') OR exit('No direct script access allowed');
 
	 class c_produk_jual extends CI_Controller {

	 	function __construct(){

			parent::__construct();
			$this->load->model('m_stok');
			$this->load->model('m_jasa');
            $this->load->model('m_suplier');
            $this->load->model('m_produk_jual');
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

            $data['title'] = 'Produk';
    
            $data['user'] = $this->db->get_where('login', ['email' =>
            $this->session->userdata('email')])->row_array();
    
    
            
			$data['produk_jual'] = $this->m_produk_jual->tampil_produk_jual()->result();
    
            $this->load->view('template/dashboard_header',$data);
            $this->load->view('template/dashboard_topbar',$data);
            $this->load->view('template/dashboard_sidebar',$data);
            $this->load->view('stok/v_produk_jual',$data);
            $this->load->view('template/dashboard_footer',$data);
    
        }	
        
        
        public function tambah_produk_jual(){
			$kode_produk = $this->m_stok->gen_id('tb_produk', 'id_produk', 'PRG-');
			$data=[
                
				'kode_produk'		=>$kode_produk,
				'nama_produk'      =>$this->input->post('nama_produk'),
				'harga_jual'	    =>$this->input->post('harga_jual')
			]; 

			$produk = $this->m_produk_jual->cekProduk($this->input->post('nama_produk'))->num_rows();

			if($produk == 0){
				$this->db->insert('tb_produk',$data);
				$this->session->set_flashdata('message', '<div class="alert alert-success" role="alert">
							  Produk Telah Ditambahkan
						  </div>');
				redirect('c_produk_jual');
			}
			else{
				$this->session->set_flashdata('message', '<div class="alert alert-danger" role="alert">
				Produk Telah Tersedia
				</div>');
				redirect('c_produk_jual');
			}

			
        }
        


        public function update(){

			
            $id_produk = $this->input->post('id_produk');
            $kode_produk = $this->input->post('kode_produk');
			$nama_produk = $this->input->post('nama_produk');
			$harga_jual = $this->input->post('harga_jual');

			$data1 = array(
                'id_produk' => $id_produk,
                'kode_produk' => $kode_produk,
				'nama_produk' => $nama_produk,
				'harga_jual' => $harga_jual
			);

			$where1 = array(
				'id_produk' => $id_produk
			);

			//=========aksi edit suplier
			$this->m_produk_jual->update_produk_jual($where1,$data1);

			$this->session->set_flashdata('message', '<div class="alert alert-success" role="alert">
  					Your suplier has been update!  	 
  				</div>');
			redirect('c_produk_jual');
						
		}

		//=========aksi delete suplier=======
		public function delete_produk_jual($id){

			$where = array('id_produk' => $id);

			//===========Model=================
			$this->m_produk_jual->delete_produk_jual($where);

			$this->session->set_flashdata('message', '<div class="alert alert-danger" role="alert">
  						suplier after delete
  					</div>');

			redirect('c_produk_jual');
		}

     }
     ?>