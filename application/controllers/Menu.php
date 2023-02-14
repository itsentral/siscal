<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Menu extends CI_Controller {
	public function __construct(){
        parent::__construct();
		$this->load->model('master_model');
		$this->load->database();
        if(!$this->session->userdata('isSISCALlogin')){
			redirect('login');
		}
    }

	public function index(){
		$controller			= ucfirst(strtolower($this->uri->segment(1)));
		$Arr_Akses			= getAcccesmenu($controller);
		if($Arr_Akses['read'] !='1'){
			$this->session->set_flashdata("alert_data", "<div class=\"alert alert-warning\" id=\"flash-message\">You Don't Have Right To Access This Page, Please Contact Your Administrator....</div>");
			redirect(site_url('dashboard'));
		}
		
		$get_Data			= $this->master_model->getData('new_menus','sts_siscal','Y');
		$menu_akses			= $this->master_model->getMenu(array('sts_siscal'=>'Y'));
		
		$data = array(
			'title'			=> 'Indeks Of Menus',
			'action'		=> 'index',
			'row'			=> $get_Data,
			'data_menu'		=> $menu_akses,
			'akses_menu'	=> $Arr_Akses
		);
		// history('View Data menu');
		$this->load->view('Menu/index',$data);
	}
	public function add(){		
		if($this->input->post()){
			$Arr_Kembali			= array();
			$data					= $this->input->post();
			$data['created_by']		= $this->session->userdata('siscal_username'); 
			$data['created_date']	= date('Y-m-d H:i:s');
			$data['sts_siscal']		= 'Y';
			$data['active']			= '1';
			if($this->master_model->simpan('new_menus',$data)){
				$Arr_Kembali		= array(
					'status'		=> 1,
					'pesan'			=> 'Add Menu Success. Thank you & have a nice day.......'
				);
				history('Add Data Menu'.$data['name']);
			}else{
				$Arr_Kembali		= array(
					'status'		=> 2,
					'pesan'			=> 'Add Menu failed. Please try again later......'
				);
				
			}
			echo json_encode($Arr_Kembali);
		}else{		
			$controller			= ucfirst(strtolower($this->uri->segment(1)));
			$Arr_Akses			= getAcccesmenu($controller);
			if($Arr_Akses['create'] !='1'){
				$this->session->set_flashdata("alert_data", "<div class=\"alert alert-warning\" id=\"flash-message\">You Don't Have Right To Access This Page, Please Contact Your Administrator....</div>");
				redirect(site_url('menu'));
			}
			$arr_Where			= array('active'=>'1','sts_siscal'=>'Y');
			$get_Data			= $this->master_model->getMenu($arr_Where);
			$data = array(
				'title'			=> 'Add Menus',
				'action'		=> 'add',
				'data_menu'		=> $get_Data
			);
			$this->load->view('Menu/add',$data);
		}
	}
	public function edit($id=''){
		if($this->input->post()){
			//echo"<pre>";print_r($this->input->post());exit;
			$data					= $this->input->post();
			$Arr_Kembali			= array();
			$data['active']			= $this->input->post('flag_active');
			unset($data['id']);
			unset($data['flag_active']);
			$data['modified_by']	= $this->session->userdata('siscal_username'); 
			$data['modified_date']	= date('Y-m-d H:i:s');
			$data['sts_siscal']		= 'Y';
			//echo"<pre>";print_r($data);exit;
			if($this->master_model->getUpdate('new_menus',$data,'id',$this->input->post('id'))){
				$Arr_Kembali		= array(
					'status'		=> 1,
					'pesan'			=> 'Edit Menu Success. Thank you & have a nice day.......'
				);
				history('Edit Data Menu'.$data['name']);
				
			}else{
				$Arr_Kembali		= array(
					'status'		=> 2,
					'pesan'			=> 'Edit Menu failed. Please try again later......'
				);
			}
			echo json_encode($Arr_Kembali);
		}else{		
			$controller			= ucfirst(strtolower($this->uri->segment(1)));
			$Arr_Akses			= getAcccesmenu($controller);
			if($Arr_Akses['update'] !='1'){
				$this->session->set_flashdata("alert_data", "<div class=\"alert alert-warning\" id=\"flash-message\">You Don't Have Right To Access This Page, Please Contact Your Administrator....</div>");
				redirect(site_url('menu'));
			}
			$arr_Where			= array('active'=>'1','sts_siscal'=>'Y');
			$get_Data			= $this->master_model->getMenu($arr_Where);
			
			$detail				= $this->master_model->getData('new_menus','id',$id); 
			$data = array(
				'title'			=> 'Edit Menus',
				'action'		=> 'edit',
				'data_menu'		=> $get_Data,
				'row'			=> $detail
			);
			
			$this->load->view('Menu/edit',$data);
		}
	}

	function delete($id_lokasi){
		$controller			= ucfirst(strtolower($this->uri->segment(1)));
		$Arr_Akses			= getAcccesmenu($controller);
		if($Arr_Akses['delete'] !='1'){
			$this->session->set_flashdata("alert_data", "<div class=\"alert alert-warning\" id=\"flash-message\">You Don't Have Right To Access This Page, Please Contact Your Administrator....</div>");
			redirect(site_url('menu'));
		}
		
		$this->db->where('id', $id_lokasi);
		$this->db->delete("new_menus");
		if($this->db->affected_rows()>0){
			$this->session->set_flashdata("alert_data", "<div class=\"alert alert-success\" id=\"flash-message\">Data has been successfully deleted...........!!</div>");
			history('Delete Data Menu id'.$id_lokasi);
			redirect(site_url('menu'));
		}
	}
}