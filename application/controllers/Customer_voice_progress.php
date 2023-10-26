<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Customer_voice_progress extends CI_Controller {	
	public function __construct() {
		parent::__construct();
		$this->load->model('master_model');
		// Your own constructor code
		
		if(!$this->session->userdata('isSISCALlogin')){
			redirect('login');
		}
		
		$controller			= ucfirst(strtolower($this->uri->segment(1)));
		
		$this->Arr_Akses			= getAcccesmenu($controller);		
		
		
		
	}	
	public function index() {		
		$Arr_Akses		= $this->Arr_Akses;
		if($Arr_Akses['read'] !='1'){
			$this->session->set_flashdata("alert_data", "<div class=\"alert alert-warning\" id=\"flash-message\">You Don't Have Right To Access This Page, Please Contact Your Administrator....</div>");
			redirect(site_url('dashboard'));
		}
		$Qry_Data		= "SELECT * FROM complain_customers WHERE sts_voc IN ('FOL','PRG','CLS')";
		$rows_data		= $this->db->query($Qry_Data)->result();
		$data			= array(
			'action'		=>'index',
			'title'			=>'List Of VoC Process',
			'akses_menu'	=> $Arr_Akses,
			'rows_data'		=> $rows_data
		);
		
		$this->load->view('Cust_complain/list_on_progress',$data);
		
	}
	
	
	
	function view_detail($kode_voc=''){
		$Arr_Akses		= $this->Arr_Akses;
		$row_Head		= $this->master_model->getArray('complain_customers',array('id'=>$kode_voc));
		$row_Det		= $this->master_model->getArray('complain_customer_details',array('complain_customer_id'=>$kode_voc));
		$data			= array(
			'action'		=>'view_detail',
			'title'			=>'View Detail VoC',
			'rows_header'	=> $row_Head,
			'rows_detail'	=> $row_Det,
			'akses_menu'	=> $Arr_Akses
		);
		
		$this->load->view('Cust_complain/detail_progress_complain',$data);
	}
	
	function update_actual_progress(){
		$kode_det		= $this->input->post('kode_detail');
		$rows_datas		= $this->db->get_where('complain_customer_actions',array('id'=>$kode_det))->result();
		$rows_detail	= $this->db->get_where('complain_customer_details',array('id'=>$rows_datas[0]->complain_customer_detail_id))->result();
		$rows_header	= $this->db->get_where('complain_customers',array('id'=>$rows_detail[0]->complain_customer_id))->result();
		$det_Member		= $this->master_model->getArray('members',array(),'id','nama');
		$data			= array(
			'action'		=>'update_actual_progress',
			'title'			=>'Update Progress Detail VoC',
			'rows_header'	=> $rows_header,
			'rows_detail'	=> $rows_detail,
			'rows_datas'	=> $rows_datas,
			'rows_member'	=> $det_Member
		);
		
		$this->load->view('Cust_complain/update_progress_complain',$data);
	}
	
	function save_actual_progress(){
		$Arr_Return		= array();		
		if($this->input->post()){
			//echo"<pre>";print_r($this->input->post());exit;
			$Proses_By		= "OTO-SISTEM";
			$Proses_Date	= date('Y-m-d H:i:s');
			$det_Member		= $this->master_model->getArray('members',array(),'id','nama');
			$Kode_VOC		= $this->input->post('kode_voc');
			$Kode_Det		= $this->input->post('kode_det');
			$Kode_Act		= $this->input->post('kode_action');
			$Actual_Act		= strtoupper($this->input->post('actual_descr'));
			$Actual_Date	= $this->input->post('actual_date');
			$Actual_ID		= $this->input->post('actual_incharge');
			$Actual_Name	= $det_Member[$Actual_ID];
			$sts_Act		= $sts_Head = $sts_Detail = "";
			$Insert_Detail	= array();
			$Upd_Action		= array(
				'descr'					=> $Actual_Act,
				'actual_action_by_id'	=> $Actual_ID,
				'actual_action_by_name'	=> strtoupper($Actual_Name),
				'actual_finish_date'	=> $Actual_Date,
				'sts_action'			=> 'CLS'
			);
			$WHR_Action		= array('id'=>$Kode_Act);
			if($this->input->post('detDetail')){
				$sts_Detail	= 'OPN';
				$sts_Head	= 'PRG';
				$det_Detail		= $this->input->post('detDetail');
				
				## GET URUT DETAIL ACTION ##
				$Next_Urut	= 0;
				$Qry_Urut	= "SELECT id FROM complain_customer_actions WHERE complain_customer_detail_id='".$Kode_Det."' ORDER BY id DESC LIMIT 1";
				$det_Urut	= $this->db->query($Qry_Urut)->result();
				if($det_Urut){
					$Pecah_Kode	= explode("-",$det_Urut[0]->id);
					$Tot_Kode	= count($Pecah_Kode) - 1;
					$Next_Urut	= $Pecah_Kode[$Tot_Kode];
				}
				
				foreach($det_Detail as $keyD=>$valD){
					$Next_Urut++;
					$det_comp		= $Kode_Det.'-'.$Next_Urut;
					$Insert_Detail[$keyD]	= array(
						'id'							=> $det_comp,
						'complain_customer_detail_id'	=> $Kode_Det,
						'plan_action'					=> strtoupper($valD['descr']),
						'plan_action_by_id'				=> $valD['pic_incharge'],
						'plan_action_by_name'			=> $det_Member[$valD['pic_incharge']],
						'plan_due_date'					=> $valD['plan_date'],	
						'sts_action'					=> 'OPN'
					);
				}
			}else{
				## CEK CLOSE DETAIL ACTION ##
				$WHR_Det		= array(
					'complain_customer_detail_id'	=> $Kode_Det,
					'id !='							=> $Kode_Act,
					'sts_action'					=> 'OPN'
				);
				$sts_Detail	='OPN';
				$num_Det_Open	= $this->db->get_where('complain_customer_actions',$WHR_Det)->num_rows();
				if($num_Det_Open < 1){
					$sts_Detail	='CLS';
				}
				
				## CEK CLOSE DETAIL ##
				$sts_Head	='PRG';
				if($sts_Detail === 'CLS'){
					$WHR_Head	= array(
						'complain_customer_id'			=> $Kode_VOC,
						'id !='							=> $Kode_Det,
						'sts_process'					=> 'OPN'
					);
					$num_Open	= $this->db->get_where('complain_customer_details',$WHR_Head)->num_rows();
					if($num_Open < 1){
						$sts_Head	='CLS';
					}
				}
			}
			$Insert_Log	= array(
				'complain_customer_id'	=> $Kode_VOC,
				'sts_voc'				=> $sts_Head,
				'descr'					=> 'CLOSE ACTION VoC KODE : '.$Kode_Act,
				'update_by'				=> $Proses_By,
				'update_date'			=> $Proses_Date
			);
			
			$Upd_Head		= array(
				'sts_voc'			=> $sts_Head,
				'modified_by'		=> $Proses_By,
				'modified_date'		=> $Proses_Date
			);
			
			$Upd_Detail		= array(
				'sts_process'	=> $sts_Detail
			);
			
			$this->db->trans_start();
			$this->db->update('complain_customer_actions',$Upd_Action,$WHR_Action);
			$this->db->update('complain_customer_details',$Upd_Detail,array('id'=>$Kode_Det));
			$this->db->update('complain_customers',$Upd_Head,array('id'=>$Kode_VOC));
			$this->db->insert('complain_customer_logs',$Insert_Log);
			if($Insert_Detail){
				$this->db->insert_batch('complain_customer_actions',$Insert_Detail);
			}
			$this->db->trans_complete();
			if($this->db->trans_status() === FALSE){
				$this->db->trans_rollback();
				$Arr_Return	= array(
					'pesan'		=>'Update actual process failed. Please try again later ...',
					'status'		=> 2
				);			
			}else{
				$this->db->trans_commit();
				$Arr_Return	= array(
					'pesan'		=>'Update actual process success. Thanks ...',
					'status'	=> 1
					
				);					
				//history('Update Actual Action  : '.$Kode_VOC);
				
			}
		}else{
			$Arr_Return	= array(
				'hasil'		=> 2,
				'pesan'		=> 'No Record was found to process.....'
			);
		}
		
		echo json_encode($Arr_Return);
	}
	
	function close_complain($kode_voc=''){
		if($this->input->post()){
			//echo"<pre>";print_r($this->input->post());exit;
			$Close_By		= "OTO-SISTEM";
			$Close_Date		= date('Y-m-d H:i:s');
			$Kode_VOC		= $this->input->post('kode_voc');
			$FeedBack		= $this->input->post('cust_feedback');
			$RateFeed		= $this->input->post('rating_val');
			$head_VOC		= $this->db->get_where('complain_customers',array('id'=>$Kode_VOC))->result();
			if($head_VOC[0]->sts_voc !== 'CLS'){
				if($head_VOC[0]->sts_voc === 'CLA'){
					$Pesan		= 'VoC has been closed....';
				}else{
					$Pesan		= 'VoC cannot be closed cause still on job process...'; 
				}
				$Arr_Return	= array(
					'pesan'			=> $Pesan,
					'status'		=> 2
				);
			}else{
				$Insert_Log	= array(
					'complain_customer_id'	=> $Kode_VOC,
					'sts_voc'				=> 'CLA',
					'descr'					=> 'CLOSE VoC  : '.$Kode_VOC,
					'update_by'				=> $Close_By,
					'update_date'			=> $Close_Date
				);
				
				$Upd_Head		= array(
					'sts_voc'			=> 'CLA',
					'modified_by'		=> $Close_By,
					'modified_date'		=> $Close_Date,
					'rate'				=> $RateFeed,
					'feedback'			=> $FeedBack,
					'feedback_date'		=> $Close_Date
				);
				
				$this->db->trans_start();
				$this->db->update('complain_customers',$Upd_Head,array('id'=>$Kode_VOC));
				$this->db->insert('complain_customer_logs',$Insert_Log);
				
				$this->db->trans_complete();
				if($this->db->trans_status() === FALSE){
					$this->db->trans_rollback();
					$Arr_Return	= array(
						'pesan'		=>'Close VoC process failed. Please try again later ...',
						'status'		=> 2
					);			
				}else{
					$this->db->trans_commit();
					$Arr_Return	= array(
						'pesan'		=>'Close VoC process success. Thanks ...',
						'status'	=> 1
						
					);					
					//history('Close VoC  : '.$Kode_VOC);					
				}
			}
			echo json_encode($Arr_Return);
		}else{
			$Arr_Akses		= $this->Arr_Akses;
			if($Arr_Akses['update'] !='1'){
				$this->session->set_flashdata("alert_data", "<div class=\"alert alert-warning\" id=\"flash-message\">You Don't Have Right To Access This Page, Please Contact Your Administrator....</div>");
				redirect(site_url('customer_voice_progress'));
			}
			$row_Head		= $this->master_model->getArray('complain_customers',array('id'=>$kode_voc));
			$row_Det		= $this->master_model->getArray('complain_customer_details',array('complain_customer_id'=>$kode_voc));
			$data			= array(
				'action'		=>'close_complain',
				'title'			=>'Close VoC',
				'rows_header'	=> $row_Head,
				'rows_detail'	=> $row_Det
			);
			
			$this->load->view('Cust_complain/close_voc',$data);
		}
	}
}
