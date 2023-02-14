<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Narration_quotation extends CI_Controller {
	public function __construct(){
        parent::__construct();
		$this->load->model('master_model');
		$this->load->database();
        if(!$this->session->userdata('isSISCALlogin')){
			redirect('login');
		}
		
		$this->folder		= 'Master/';
    }

	public function index(){
		$controller			= ucfirst(strtolower($this->uri->segment(1)));
		$Arr_Akses			= getAcccesmenu($controller);
		if($Arr_Akses['read'] !='1'){
			$this->session->set_flashdata("alert_data", "<div class=\"alert alert-warning\" id=\"flash-message\">You Don't Have Right To Access This Page, Please Contact Your Administrator....</div>");
			redirect(site_url('dashboard'));
		}
		
		$get_Data			= $this->master_model->getData('quot_narrations');
		
		$data = array(
			'title'			=> 'Narration Of Quotation',
			'action'		=> 'index',
			'row'			=> $get_Data,
			'akses_menu'	=> $Arr_Akses
		);
		// history('View Data menu');
		$this->load->view($this->folder.'v_quot_narration',$data);
	}
	
	/*
	| ------------------------ |
	|	  ADD NARRATIONS	   |
	| ------------------------ |
	*/
	public function add_quot_narasi(){	
		$data = array(
			'title'			=> 'ADD NARRATION',
			'action'		=> 'add_quot_narasi'
		);
		$this->load->view($this->folder.'v_quot_narration_add',$data);
		
	}
	public function save_add_quot_narasi(){	
		$rows_Return		= array(
			'status'		=> 2,
			'pesan'			=> 'No records was found'
		);
		if($this->input->post()){
			//echo"<pre>";print_r($this->input->post());exit;
			$Name_Narasi	= strtoupper($this->input->post('nama_narasi'));
			$Descr_Narasi	= strtoupper($this->input->post('ket_narasi'));
			$detDetail		= $this->input->post('detDetail');
			$Created_By		= $this->session->userdata('siscal_username'); 
			$Created_Date	= date('Y-m-d H:i:s');
			$Flag_Active	= 'Y';
			
			## CEK EXISTING ##
			$Qry_Exist	= "SELECT * FROM quot_narrations WHERE LOWER(name) = '".strtolower($Name_Narasi)."'";
			$Num_Find	= $this->db->query($Qry_Exist)->num_rows();
			if($Num_Find > 0){
				$rows_Return		= array(
					'status'		=> 2,
					'pesan'			=> 'Narration name already exist in list...'
				);
			}else{
				$Pesan_Error	= '';
				$this->db->trans_begin();
				$Urut		= 1;
				$Query_Urut	= "SELECT MAX(CAST(SUBSTRING_INDEX(code, '-', -1) AS UNSIGNED)) as urut FROM quot_narrations";
				$rows_Urut	= $this->db->query($Query_Urut)->result();
				if($rows_Urut){
					$Urut	= intval($rows_Urut[0]->urut) + 1;
				}
				
				$Code_Head	= 'TEXT-QUOT-'.sprintf('%04d',$Urut);
				$Ins_Header	= array(
					'code'			=> $Code_Head,
					'name'			=> $Name_Narasi,
					'descr'			=> $Descr_Narasi,
					'flag_active'	=> $Flag_Active,
					'created_by'	=> $Created_By,
					'created_date'	=> $Created_Date
				);
				
				$Has_Ins_Head	= $this->db->insert('quot_narrations',$Ins_Header);
				if($Has_Ins_Head !== TRUE){
					$Pesan_Error	= 'Error Insert Quotation Narration';
				}
				
				$intL	= 0;
				if($detDetail){
					foreach($detDetail as $keyD=>$valD){
						$intL++;
						$Code_Detail	= $Code_Head.'-'.$intL;
						$Ins_Detail		= array(
							'code_detail'	=> $Code_Detail,
							'code'			=> $Code_Head,
							'narration'		=> $valD['narration'],
							'weight'		=> $intL
						);
						
						$Has_Ins_Detail	= $this->db->insert('quot_narration_details',$Ins_Detail);
						if($Has_Ins_Detail !== TRUE){
							$Pesan_Error	= 'Error Insert Quotation Narration Detail';
						}
					}
				}
				
				if($this->db->trans_status() !== TRUE || !empty($Pesan_Error)){
					$this->db->trans_rollback();
					$rows_Return		= array(
						'status'		=> 2,
						'pesan'			=> 'Add Narration process failed...'
					);
				}else{
					$this->db->trans_commit();
					$rows_Return		= array(
						'status'		=> 1,
						'pesan'			=> 'Add Narration process success...'
					);
					
					history('Add Narration Quotation'.$Name_Narasi);
				}
			}
		}
		echo json_encode($rows_Return);
	}
	
	function view_detail_narasi(){
		$rows_Header	= $rows_Detail	= array();
		if($this->input->post('code')){
			$Code_Narasi	= $this->input->post('code');
			$rows_Header	= $this->db->get_where('quot_narrations',array('code'=>$Code_Narasi))->result();
			$rows_Detail	= $this->db->get_where('quot_narration_details',array('code'=>$Code_Narasi))->result();
		}
		
		$data = array(
			'title'			=> 'VIEW NARRATION',
			'action'		=> 'view_detail_narasi',
			'rows_header'	=> $rows_Header,
			'rows_detail'	=> $rows_Detail
		);
		$this->load->view($this->folder.'v_quot_narration_preview',$data);
	}
	
	function update_quot_narasi(){
		$rows_Header	= $rows_Detail	= array();
		if($this->input->post('code')){
			$Code_Narasi	= $this->input->post('code');
			$rows_Header	= $this->db->get_where('quot_narrations',array('code'=>$Code_Narasi))->result();
			$rows_Detail	= $this->db->get_where('quot_narration_details',array('code'=>$Code_Narasi))->result();
		}
		
		$data = array(
			'title'			=> 'EDIT NARRATION',
			'action'		=> 'update_quot_narasi',
			'rows_header'	=> $rows_Header,
			'rows_detail'	=> $rows_Detail
		);
		$this->load->view($this->folder.'v_quot_narration_edit',$data);
	}
	function save_edit_quot_narasi(){
		$rows_Return		= array(
			'status'		=> 2,
			'pesan'			=> 'No records was found'
		);
		if($this->input->post()){
			//echo"<pre>";print_r($this->input->post());exit;
			$Code_Narasi	= $this->input->post('code_narasi');
			$Name_Narasi	= strtoupper($this->input->post('nama_narasi'));
			$Descr_Narasi	= strtoupper($this->input->post('ket_narasi'));
			$detDetail		= $this->input->post('detDetail');
			$Created_By		= $this->session->userdata('siscal_username'); 
			$Created_Date	= date('Y-m-d H:i:s');
			$Flag_Active	= 'N';
			if($this->input->post('flag_active')){
				$Flag_Active	= $this->input->post('flag_active');
			}
			
			## CEK EXISTING ##
			$Qry_Exist	= "SELECT * FROM quot_narrations WHERE LOWER(name) = '".strtolower($Name_Narasi)."' AND code != '".$Code_Narasi."'";
			$Num_Find	= $this->db->query($Qry_Exist)->num_rows();
			if($Num_Find > 0){
				$rows_Return		= array(
					'status'		=> 2,
					'pesan'			=> 'Narration name already exist in list...'
				);
			}else{
				$Pesan_Error	= '';
				$this->db->trans_begin();
				
				$Ins_Log		= "INSERT INTO quot_narration_logs (
									code,
									name,
									descr,
									flag_active,
									process_by,
									process_date
								) SELECT
									code,
									name,
									descr,
									flag_active,
									'".$Created_By."' AS process_by,
									'".$Created_Date."' AS process_date
								FROM
									quot_narrations
								WHERE
									code = '".$Code_Narasi."'";
				$Has_Ins_Log	= $this->db->query($Ins_Log);
				if($Has_Ins_Log !== TRUE){
					$Pesan_Error	= 'Error Insert Quotation Narration Log';
				}
				
				$Ins_Log_Detail	= "INSERT INTO quot_narration_detail_logs (
										code_detail,
										code,
										narration,
										weight,
										process_by,
										process_date
									) SELECT
										code_detail,
										code,
										narration,
										weight,
										'".$Created_By."' AS process_by,
										'".$Created_Date."' AS process_date
									FROM
										quot_narration_details
									WHERE
										code = '".$Code_Narasi."'";
										
				$Has_Ins_Log_Det	= $this->db->query($Ins_Log_Detail);
				if($Has_Ins_Log_Det !== TRUE){
					$Pesan_Error	= 'Error Insert Quotation Narration Detail Log ';
				}
				
				$Upd_Header	= array(
					'name'			=> $Name_Narasi,
					'descr'			=> $Descr_Narasi,
					'flag_active'	=> $Flag_Active,
					'modified_by'	=> $Created_By,
					'modified_date'	=> $Created_Date
				);
				
				$Has_Upd_Head	= $this->db->update('quot_narrations',$Upd_Header,array('code'=>$Code_Narasi));
				if($Has_Upd_Head !== TRUE){
					$Pesan_Error	= 'Error Update Quotation Narration';
				}
				
				$Del_Detail		= "DELETE FROM quot_narration_details WHERE code = '".$Code_Narasi."'";
				$Has_Del_Det	= $this->db->query($Del_Detail);
				if($Has_Del_Det !== TRUE){
					$Pesan_Error	= 'Error Delete Quotation Narration Detail ';
				}
				$intL	= 0;
				if($detDetail){
					foreach($detDetail as $keyD=>$valD){
						$intL++;
						$Code_Detail	= $Code_Narasi.'-'.$intL;
						$Ins_Detail		= array(
							'code_detail'	=> $Code_Detail,
							'code'			=> $Code_Narasi,
							'narration'		=> $valD['narration'],
							'weight'		=> $intL
						);
						
						$Has_Ins_Detail	= $this->db->insert('quot_narration_details',$Ins_Detail);
						if($Has_Ins_Detail !== TRUE){
							$Pesan_Error	= 'Error Insert Quotation Narration Detail';
						}
					}
				}
				
				if($this->db->trans_status() !== TRUE || !empty($Pesan_Error)){
					$this->db->trans_rollback();
					$rows_Return		= array(
						'status'		=> 2,
						'pesan'			=> 'Edit Narration process failed...'
					);
				}else{
					$this->db->trans_commit();
					$rows_Return		= array(
						'status'		=> 1,
						'pesan'			=> 'Edit Narration process success...'
					);
					
					history('Edit Narration Quotation'.$Name_Narasi.' - '.$Code_Narasi);
				}
			}
		}
		echo json_encode($rows_Return);
	}
	
}