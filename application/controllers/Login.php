<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Login extends CI_Controller {
	function __construct() {
		parent::__construct();
		$this->load->model('master_model');	
        if($this->session->userdata('isSISCALlogin')){
				redirect('dashboard');
			}		
	}

	public function index(){
		if ($this->input->post()) {	
			$Login_user	= $this->input->post('username');
			$Pass_user	= $this->input->post('password');
			
			$Pass_Crypt	= security_hash($Pass_user);
			
			$WHR		= array(
				'username'		=> $Login_user,
				'password'		=> $Pass_Crypt,
				'flag_active'	=> '1'
			);
			//echo"<pre>";print_r($WHR);
			$Find_Login	= $this->db->get_where('users',$WHR);
			$Num_Find	= $Find_Login->num_rows();
			
			if($Num_Find > 0){
				$det_Find		= $Find_Login->result();
				$Group_id		= $det_Find[0]->group_id;
				$det_Group		= $this->db->get_where('groups',array('id'=>$Group_id))->result();
				$Member_Id		= $det_Find[0]->member_id;
				
				$data_Ses = array(
					'siscal_userid' 		=> $det_Find[0]->id,
					'siscal_username' 		=> $det_Find[0]->username,
					'isSISCALlogin' 		=> 1,
					'siscal_group_id'		=> $Group_id,
					'siscal_group_name'		=> $det_Group[0]->name
				); 
				
				if($Member_Id){
					$det_Member		= $this->db->get_where('members',array('id'=>$Member_Id))->result();
					if($det_Member){
						$data_Ses['siscal_member_id']	= $Member_Id;
						$data_Ses['siscal_member_name']	= $det_Member[0]->nama;
						
						unset($det_Member);
					}
				}
				
				unset($det_Group);
				unset($det_Find);

				//echo Enkripsi('Ada');exit;
				$this->session->set_userdata($data_Ses);
				// history('Login');
				if($Group_id == '8'){
					redirect(site_url('dashboard_selia'));
				}else{
					redirect(site_url('dashboard'));
				}
				
			}else{
				$this->session->set_flashdata("alert_data", "<text id=\"flash-message\">Username atau Password Anda Salah....</text>");
				redirect(site_url('/login'));
			}			
		} else {
			$this->load->view('login');
		}
	}
	
}
