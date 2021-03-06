<?php 
defined('BASEPATH') OR exit('No direct script access allowed');

	class c_menubar extends CI_controller{


		function __construct(){

			parent::__construct();
			$this->load->model('m_sidebar');
			$this->load->library('form_validation') ;

		}

		//=========Menu Management==============
		public function index(){

			$data['title'] = 'Menu Management';

			$data['user'] = $this->db->get_where('login', ['email' =>
			$this->session->userdata('email')])->row_array();

			//=================Model=============
			$data['menu'] = $this->m_sidebar->menu_management()->result();

	
			$this->form_validation->set_rules('menu', 'Menu', 'required');



			if ($this->form_validation->run() == false) {
				$this->load->view('template/dashboard_header',$data);
				$this->load->view('template/dashboard_topbar',$data);
				$this->load->view('template/dashboard_sidebar',$data);
				$this->load->view('menu/v_menu',$data);
				$this->load->view('template/dashboard_footer',$data);
			}else{

				$data = array(

					'menu' => $this->input->post('menu')
				); 

				$this->m_sidebar->insert_menumanagement($data);

				$this->session->set_flashdata('message', '<div class="alert alert-success" role="alert">
  						New menu added
  					</div>');
				redirect('c_menubar');
			}

		}
		//==========side bar=============
		public function side_bar(){

			 $role_id = $this->session->userdata('role_id');

			 $this->load->model('side_bar');

		}

		//=============Sub menu==============
		public function submenu(){

			$data['title'] = 'Submenu Management';

			$data['user'] = $this->db->get_where('login', ['email' =>
			$this->session->userdata('email')])->row_array();

			$data['submenu'] = $this->m_sidebar->submenu()->result();	

			$data['menu'] = $this->m_sidebar->optionsubmenu()->result();

			$this->form_validation->set_rules('title', 'Title', 'required');
			$this->form_validation->set_rules('menu_id', 'Menu', 'required');
			$this->form_validation->set_rules('url', 'URL', 'required');
			$this->form_validation->set_rules('icon', 'Icon', 'required');


			if ($this->form_validation->run() == false ) {
				# code...
				$this->load->view('template/dashboard_header',$data);
				$this->load->view('template/dashboard_topbar',$data);
				$this->load->view('template/dashboard_sidebar',$data);
				$this->load->view('menu/v_submenuu',$data);
				$this->load->view('template/dashboard_footer',$data);
			}else{

			$data = [

				'title'     =>$this->input->post('title'),
				'menu_id'   =>$this->input->post('menu_id'),
				'url' 	    =>$this->input->post('url'),
				'icon'      =>$this->input->post('icon'),
				'is_active' =>$this->input->post('is_active')	
			];

			$this->db->insert('user_sub_menu', $data);

			$this->session->set_flashdata('message', '<div class="alert alert-success" role="alert">
  						New sub menu added
  					</div>');
				redirect('c_menubar/submenu');

			}
		}
		


		//===========delete menu management========
		public function delete($id){

			$where = array('id' => $id);

			//===========Model=================
			$this->m_sidebar->delete($where);


			$this->session->set_flashdata('message', '<div class="alert alert-danger" role="alert">
  						Menu management after delete
  					</div>');

			redirect('c_menubar');
		}

		//==============Edit Menu Management=========
		public function update(){

			$id = $this->input->post('id');
			$menu = $this->input->post('menu');

			$data1 = array(
				'menu' => $menu
			);

			$where1 = array(
				'id' => $id
			);

			//=========aksi edit menu
			$this->m_sidebar->update_menu($where1,$data1);

			$this->session->set_flashdata('message', '<div class="alert alert-success" role="alert">
  					Your menu has been update!  	 
  				</div>');
			redirect('c_menubar');
						
		}





	}


/* End of file c_menubar.php */
/* Location: ./application/controllers/c_menubar.php */
?>