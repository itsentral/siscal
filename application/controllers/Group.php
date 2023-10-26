<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Group extends CI_Controller {
	public function __construct(){
        parent::__construct();
		$this->load->database();
        if(!$this->session->userdata('isSISCALlogin')){
			redirect('login');
		}
		$this->folder	='Group';
    }

	public function index(){
		$controller			= ucfirst(strtolower($this->uri->segment(1)));
		$Arr_Akses			= getAcccesmenu($controller);
		if($Arr_Akses['read'] !='1'){
			$this->session->set_flashdata("alert_data", "<div class=\"alert alert-warning\" id=\"flash-message\">You Don't Have Right To Access This Page, Please Contact Your Administrator....</div>");
			redirect(site_url('dashboard'));
		}
		
		$comp_Data			= $this->db->get('groups')->result_array();
		
		$data = array(
			'title'			=> 'Indeks Of Access Group',
			'action'		=> 'index',
			'row'			=> $comp_Data,
			'akses_menu'	=> $Arr_Akses
		);
		history('View Data Group');
		$this->load->view($this->folder.'/index',$data);
	}
	
	public function add(){
		if($this->input->post()){
			$Group_Name			= $this->input->post('name');
			$Keterangan			= $this->input->post('descr');
			$Cek_Data			= $this->db->get_where('groups',array("LOWER(name)"=>strtolower($Group_Name)))->num_rows();
			if($Cek_Data > 0){
				$Arr_Kembali		= array(
					'status'		=> 2,
					'pesan'			=> 'Group Already Exist. Please Different Group Name.......'
				);
			}else{
				
				$det_Insert			= array(
					'name'				=> ucwords(strtolower($Group_Name)),
					'descr'				=> $Keterangan,
					'created'			=> date('Y-m-d H:i:s'),
					'created_by'		=> $this->session->userdata('siscal_username')
					
				);
				$this->db->trans_begin();
				$this->db->insert('groups',$det_Insert);
				
				if ($this->db->trans_status() !== true){
					$this->db->trans_rollback();
					$Arr_Kembali		= array(
						'status'		=> 2,
						'pesan'			=> 'Add Group failed. Please try again later......'
					);
				}else{
					$this->db->trans_commit();
					$Arr_Kembali		= array(
						'status'		=> 1,
						'pesan'			=> 'Add Group Success. Thank you & have a nice day.......'
					);
					history('Add Data Group'.$Group_Name);
				}
			}
			echo json_encode($Arr_Kembali);
		}else{
			$controller			= ucfirst(strtolower($this->uri->segment(1)));
			$Arr_Akses			= getAcccesmenu($controller);
			if($Arr_Akses['create'] !='1'){
				$this->session->set_flashdata("alert_data", "<div class=\"alert alert-warning\" id=\"flash-message\">You Don't Have Right To Access This Page, Please Contact Your Administrator....</div>");
				redirect(site_url('group'));
			}
			$data = array(
				'title'			=> 'ADD GROUP',
				'action'		=> 'add'
			);
			
			$this->load->view($this->folder.'/add_group',$data);
		}
	}
	
	public function edit_group($kode=''){
		if($this->input->post()){
			$Group_id			= $this->input->post('id');
			$Group_Name			= $this->input->post('name');
			$Keterangan			= $this->input->post('descr');
			$Query_Cek			= "SELECT * FROM `groups` WHERE LOWER(name)='".strtolower($Group_Name)."' AND id <> '".$Group_id."'";
			$Cek_Data			= $this->db->query($Query_Cek)->num_rows();
			if($Cek_Data > 0){
				$Arr_Kembali		= array(
					'status'		=> 2,
					'pesan'			=> 'Group Already Exist. Please Different Group Name.......'
				);
			}else{				
				$det_Insert			= array(
					'name'				=> ucwords(strtolower($Group_Name)),
					'descr'				=> $Keterangan,
					'modified'			=> date('Y-m-d H:i:s'),
					'modified_by'		=> $this->session->userdata('siscal_username')
					
				);
				$this->db->trans_begin();
				$this->db->update('groups',$det_Insert,array('id'=>$Group_id));
				
				if ($this->db->trans_status() !== true){
					$this->db->trans_rollback();
					$Arr_Kembali		= array(
						'status'		=> 2,
						'pesan'			=> 'Edit Group failed. Please try again later......'
					);
				}else{
					$this->db->trans_commit();
					$Arr_Kembali		= array(
						'status'		=> 1,
						'pesan'			=> 'Edit Group Success. Thank you & have a nice day.......'
					);
					history('Edit Data Group ID '.$Group_id);
				}
			}
			echo json_encode($Arr_Kembali);
		}else{
			$controller			= ucfirst(strtolower($this->uri->segment(1)));
			$Arr_Akses			= getAcccesmenu($controller);
			if($Arr_Akses['update'] !='1'){
				$this->session->set_flashdata("alert_data", "<div class=\"alert alert-warning\" id=\"flash-message\">You Don't Have Right To Access This Page, Please Contact Your Administrator....</div>");
				redirect(site_url('group'));
			}
			$int_data			= $this->db->get_where('groups',array('id'=>$kode))->result();
			$data = array(
				'title'			=> 'EDIT GROUP',
				'action'		=> 'edit',
				'rows_data'		=> $int_data
			);
			
			$this->load->view($this->folder.'/edit_group',$data);
		}
	}
	
	
	public function access_menu($id=''){
		if($this->input->post()){
			//echo"<pre>";print_r($this->input->post());exit;
			
			$Group_id				= $this->input->post('id');
			$Cek_Data				= $this->db->get_where('group_menus',array('group_id'=>$Group_id))->num_rows();
			
			$data_session			= $this->session->userdata;
			$Jam					= date('Y-m-d H:i:s');
			$Arr_Detail				= array();
			$Loop					= 0;
			$dataDetail				= $this->input->post('tree');
			foreach($dataDetail as $key=>$value){
				if(isset($value['read']) || isset($value['create']) || isset($value['update']) || isset($value['delete']) || isset($value['approve']) || isset($value['download'])){
					$Loop++;
					$a_read			= (isset($value['read']) && $value['read'])?$value['read']:0;
					$a_create		= (isset($value['create']) && $value['create'])?$value['create']:0;
					$a_update		= (isset($value['update']) && $value['update'])?$value['update']:0;
					$a_delete		= (isset($value['delete']) && $value['delete'])?$value['delete']:0;
					$a_download		= (isset($value['download']) && $value['download'])?$value['download']:0;
					$a_approve		= (isset($value['approve']) && $value['approve'])?$value['approve']:0;
					
					$det_Detail		= array(
						'menu_id'		=> $value['menu_id'],
						'group_id'		=> $Group_id,
						'read'			=> $a_read,
						'create'		=> $a_create,
						'update'		=> $a_update,
						'delete'		=> $a_delete,
						'approve'		=> $a_approve,
						'download'		=> $a_download,
						'created'		=> $Jam,
						'created_by'	=> $this->session->userdata('siscal_username')
					);
					$Arr_Detail[$Loop]	= $det_Detail;
					
				}
			}
			$this->db->trans_begin();
			if($Cek_Data > 0){
				$Q_Del				= "DELETE FROM `new_group_menus` WHERE `group_id`='".$Group_id."'";
				$this->db->query($Q_Del);
			}
			$this->db->insert_batch('new_group_menus',$Arr_Detail);
			
			
			if ($this->db->trans_status() !== true){
				$this->db->trans_rollback();
				$Arr_Kembali		= array(
					'status'		=> 2,
					'pesan'			=> 'Manage Access Group Failed. Please Try Again.......'
				);
			}else{
				$this->db->trans_commit();
				$Arr_Kembali		= array(
					'status'		=> 1,
					'pesan'			=> 'Manage Access Group Success. Thank you & have a nice day.......'
				);				
				history('Manage Access Group '.$this->input->post('name'));
				
			}			
			echo json_encode($Arr_Kembali);
		}else{		
			$controller			= ucfirst(strtolower($this->uri->segment(1)));
			$Arr_Akses			= getAcccesmenu($controller);
			if($Arr_Akses['update'] !='1' || $Arr_Akses['create'] !='1'){
				$this->session->set_flashdata("alert_data", "<div class=\"alert alert-warning\" id=\"flash-message\">You Don't Have Right To Access This Page, Please Contact Your Administrator....</div>");
				redirect(site_url('group'));
			}
			
			$get_Data			= $this->db->get_where('new_menus',array('active'=>'1','sts_siscal'=>'Y'))->result_array();
			$detail				= group_access($id);
			
			$int_data			= $this->db->get_where('groups',array('id'=>$id))->result();
			
			$data = array(
				'title'			=> 'Manage Access Group',
				'action'		=> 'access_menu',
				'data_menu'		=> $get_Data,
				'row_akses'		=> $detail,
				'rows'			=> $int_data
			);
			
			$this->load->view($this->folder.'/akses_menu',$data);
		}
	}
	
	
	

}