<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Customer_voice extends CI_Controller {	
	public function __construct() {
		parent::__construct();
		$this->load->model('master_model');
		// Your own constructor code
		/*
		if(!$this->session->userdata('isSISCALlogin')){
			redirect('login');
		}
		*/
		$controller			= ucfirst(strtolower($this->uri->segment(1)));
		/*
		$this->Arr_Akses			= getAcccesmenu($controller);		
		*/
		$this->Arr_Akses			= array(
			'read'		=> 1,
			'create'	=> 1,
			'update'	=> 1,
			'delete'	=> 1,
			'download'	=> 1,
			'approve'	=> 1,
		);
		
	}	
	public function index() {
		
		$Arr_Akses		= $this->Arr_Akses;
		if($Arr_Akses['read'] !='1'){
			$this->session->set_flashdata("alert_data", "<div class=\"alert alert-warning\" id=\"flash-message\">You Don't Have Right To Access This Page, Please Contact Your Administrator....</div>");
			redirect(site_url('dashboard'));
		}
		
		$data			= array(
			'action'		=>'index',
			'title'			=>'Voice Of Customer',
			'akses_menu'	=> $Arr_Akses
		);
		
		$this->load->view('Cust_complain/list',$data);
		
	}
	
	function get_data_display(){
		include "ssp.class.php";
		$WHERE		="sts_voc='OPN'";
		$Arr_Bulan	= array(1=>'January','February','March','April','May','June','July','August','September','October','November','December');
		/*
		if($this->input->post('periode')){
			$Pecah_Data		= explode(' ',$this->input->post('periode'));
			$Bulan			= array_search($Pecah_Data[0],$Arr_Bulan);
			$Periode		= date('Y-m',mktime(0,0,0,$Bulan,1,$Pecah_Data[1]));
			if(!empty($WHERE))$WHERE	.=" AND ";
			$WHERE	.="datet LIKE '".$Periode."%'";
		}
		*/
		$table 		= 'complain_customers';
		$primaryKey = 'id';
		$columns 	= array(
			array( 'db' => 'id', 'dt' => 'id'),
			 array(
				'db' => 'id',
				'dt' => 'DT_RowId'
			),
			array( 'db' => 'nomor', 'dt' => 'nomor'),
			array( 'db' => 'customer_id', 'dt' => 'customer_id'),
			array( 'db' => 'customer_name', 'dt' => 'customer_name'),
			array( 'db' => 'letter_order_id', 'dt' => 'letter_order_id'),
			array( 'db' => 'pic_name', 'dt' => 'pic_name'),
			array( 'db' => 'pic_email', 'dt' => 'pic_email'),
			array( 'db' => 'pic_phone', 'dt' => 'pic_phone'),
			array( 'db' => 'cancel_by', 'dt' => 'cancel_by'),
			array( 'db' => 'cancel_reason', 'dt' => 'cancel_reason'),
			array( 'db' => 'no_so', 'dt' => 'no_so'),
			array( 'db' => 'sts_voc', 'dt' => 'sts_voc'),
			array( 'db' => 'rec_by', 'dt' => 'rec_by'),
			array(
				'db' => 'datet',
				'dt'=> 'datet',
				'formatter' => function($d,$row){
					return date('d F Y',strtotime($d));
				}
			),
			array(
				'db' => 'plan_close',
				'dt'=> 'plan_close',
				'formatter' => function($d,$row){
					return date('d F Y',strtotime($d));
				}
			),
			
			array(
				'db' => 'id',
				'dt'=> 'action',
				'formatter' => function($d,$row){
					return '';
				}
			)

		);


		$sql_details = array(
			'user' => $this->db->username,
			'pass' => $this->db->password,
			'db'   => $this->db->database,
			'host' => $this->db->hostname
		);
		


		echo json_encode(
			SSP::complex ($_POST, $sql_details, $table, $primaryKey, $columns,null, $WHERE)
		);
	}
	
	function create_complain(){
		if($this->input->post()){
			//echo"<pre>";print_r($this->input->post());exit;
			## PARAMETER POST ##
			$Rec_By			= $this->input->post('rec_by');
			$Customer_ID	= $this->input->post('customer_id');
			$Order_ID		= $this->input->post('letter_order_id');
			$PIC_Name		= $this->input->post('pic_name');
			$PIC_Phone		= $this->input->post('pic_phone');
			$PIC_Email		= $this->input->post('pic_email');
			$Plan_Date		= $this->input->post('plan_close');
			$det_Detail		= $this->input->post('detDetail');
			
			$Created_By		= "OTO-SISTEM";
			$Created_Date	= date('Y-m-d H:i:s');
			$Process_Date	= date('Y-m-d');
			$Month_Process	= date('n');
			$Year_Process	= date('Y');
			$Period_Process	= date('Y-m');
			$Kode_Romawi	= getRomawi($Month_Process);
			
			$det_Customer	= $this->master_model->getArray('customers',array('id'=>$Customer_ID),'id','name');
			$det_Member		= $this->master_model->getArray('members',array('id'=>$Rec_By),'id','nama');
			$det_Order		= $this->master_model->getArray('letter_orders',array('id'=>$Order_ID),'id','no_so');
			
			## FORMAT NOMOR & ID ##
			$Format_Nomor	= '/SSPM/MKT-P/'.$Kode_Romawi.'/'.$Year_Process;
			$Format_ID		= 'VOC-'.date('Ym').'-V3-';
			## GET URUT ##
			$Urut_ID			= $Urut_No = 1;
			$Query_VOC		= "SELECT * FROM complain_customers WHERE datet LIKE '".$Period_Process."%' ORDER BY id DESC LIMIT 1";			
			$det_Last		= $this->db->query($Query_VOC)->result();
			if($det_Last){
				$id_Last	= explode('-',$det_Last[0]->id);
				$no_Last	= explode('/',$det_Last[0]->nomor);
				$Urut_ID	= intval($id_Last[3]) + 1;
				$Urut_No	= intval($no_Last[0]) + 1;
			}
			$Kode_VOC		= $Format_ID.sprintf('%05d',$Urut_ID);
			$Nomor_VOC		= sprintf('%05d',$Urut_No).$Format_Nomor;
			
			## PROSES INSERT ##
			$Insert_Log		= array(
				'complain_customer_id'		=> $Kode_VOC,
				'sts_voc'					=> 'OPN',
				'descr'						=> 'CREATE VOC '.$Nomor_VOC,
				'update_by'					=> $Created_By,
				'update_date'				=> $Created_Date
			);
			
			$Insert_Header	= array(
				'id'				=> $Kode_VOC,
				'nomor'				=> $Nomor_VOC,
				'datet'				=> $Process_Date,
				'customer_id'		=> $Customer_ID,
				'customer_name'		=> $det_Customer[$Customer_ID],
				'letter_order_id'	=> $Order_ID,
				'no_so'				=> $det_Order[$Order_ID],
				'pic_name'			=> strtoupper($PIC_Name),
				'pic_phone'			=> $PIC_Phone,
				'pic_email'			=> strtolower($PIC_Email),
				'sts_voc'			=> 'OPN',
				'rec_by'			=> $det_Member[$Rec_By],
				'rec_date'			=> $Process_Date,
				'plan_close'		=> $Plan_Date,
				'created_by'		=> $Created_By,
				'created_date'		=> $Created_Date
			);
			
			$Insert_Detail	= array();
			if($det_Detail){
				$intL		= 0;
				foreach($det_Detail as $key=>$vals){
					$intL++;
					$kode_Detail	= $Kode_VOC.'-'.$intL;
					$det_Arr		= array(
						'id'					=> $kode_Detail,
						'complain_customer_id'	=> $Kode_VOC,
						'descr'					=> strtoupper($vals['descr']),
						'sts_process'			=> 'OPN'
					);
					$Insert_Detail[$intL]	= $det_Arr;
				}
			}
			$this->db->trans_start();		
			$this->db->insert('complain_customers',$Insert_Header);
			$this->db->insert_batch('complain_customer_details',$Insert_Detail);
			$this->db->insert('complain_customer_logs',$Insert_Log);
			$this->db->trans_complete();
			if($this->db->trans_status() === FALSE){
				$this->db->trans_rollback();
				$Arr_Data	= array(
					'pesan'		=>'Failed Process. Please try again later ...',
					'status'		=> 2
				);			
			}else{
				$this->db->trans_commit();
				$Arr_Data	= array(
					'pesan'		=>'Success Process. Thanks ...',
					'status'	=> 1
					
				);
				
				//history('Create VoC : '.$Nomor_VOC);
				
			}
			echo json_encode($Arr_Data);
		}else{
			$Arr_Akses		= $this->Arr_Akses;
			if($Arr_Akses['create'] !='1'){
				$this->session->set_flashdata("alert_data", "<div class=\"alert alert-warning\" id=\"flash-message\">You Don't Have Right To Access This Page, Please Contact Your Administrator....</div>");
				redirect(site_url('customer_voice'));
			}
			## GET CUSTOMER DATA ##
			$row_Cust		= $this->master_model->getArray('customers',array('flag_active'=>'1'),'id','name');
			$row_User		= $this->master_model->getArray('members',array('status'=>'1'),'id','nama');
			$data			= array(
				'action'		=>'create_complain',
				'title'			=>'Create VoC',
				'rows_customer'	=> $row_Cust,
				'rows_rec'		=> $row_User
			);
			
			$this->load->view('Cust_complain/add_complain',$data);
		}
	}
	
	function get_letter_order(){
		$det_Return	= array();
		if($this->input->post()){
			$Nocust				= $this->input->post('custid');
			$det_Return			= $this->master_model->getArray('letter_orders',array('sts_so'=>'SCH','customer_id'=>$Nocust),'id','no_so');
		}
		echo json_encode($det_Return);
	}
	
	function view_voc($kode_voc=''){
		
		$row_Head		= $this->master_model->getArray('complain_customers',array('id'=>$kode_voc));
		$row_Det		= $this->master_model->getArray('complain_customer_details',array('complain_customer_id'=>$kode_voc));
		$data			= array(
			'action'		=>'view_voc',
			'title'			=>'View VoC',
			'rows_header'	=> $row_Head,
			'rows_detail'	=> $row_Det
		);
		
		$this->load->view('Cust_complain/view_complain',$data);
	}
	
	function cancel_voc($kode_voc=''){
		if($this->input->post()){
			//echo"<pre>";print_r($this->input->post());exit;
			## PARAMETER POST ##
			$Kode_VOC		= $this->input->post('kode_voc');
			$Cancel_Reason	= strtoupper($this->input->post('cancel_reason'));
			$Cancel_By		= "OTO-SISTEM";
			$Cancel_Date	= date('Y-m-d H:i:s');
			
			## CEK STATUS ##
			$det_Status		= $this->db->get_where('complain_customers',array('id'=>$Kode_VOC))->result();
			if($det_Status[0]->sts_voc !== 'OPN'){
				$Arr_Data	= array(
					'pesan'		=>'Data cannot be canceled. Its had been processed...',
					'status'	=> 2
					
				);
			}else{
				$Upd_Header	= array(
					'sts_voc'			=> 'CNC',
					'cancel_reason'		=> $Cancel_Reason,
					'cancel_by'			=> $Cancel_By,
					'cancel_date'		=> $Cancel_Date
				);
				$this->db->trans_start();		
				$this->db->update('complain_customers',$Upd_Header,array('id'=>$Kode_VOC));
				$this->db->trans_complete();
				if($this->db->trans_status() === FALSE){
					$this->db->trans_rollback();
					$Arr_Data	= array(
						'pesan'		=>'Cancel process failed. Please try again later ...',
						'status'		=> 2
					);			
				}else{
					$this->db->trans_commit();
					$Arr_Data	= array(
						'pesan'		=>'Cancel process success. Thanks ...',
						'status'	=> 1
						
					);					
					//history('Cancel Voc  : '.$Kode_VOC);
					
				}
			}
			echo json_encode($Arr_Data);
		}else{
			$Arr_Akses		= $this->Arr_Akses;
			if($Arr_Akses['delete'] !='1'){
				$this->session->set_flashdata("alert_data", "<div class=\"alert alert-warning\" id=\"flash-message\">You Don't Have Right To Access This Page, Please Contact Your Administrator....</div>");
				redirect(site_url('customer_voice'));
			}
			
			$row_Head		= $this->master_model->getArray('complain_customers',array('id'=>$kode_voc));
			$row_Det		= $this->master_model->getArray('complain_customer_details',array('complain_customer_id'=>$kode_voc));
			$data			= array(
				'action'		=>'cancel_voc',
				'title'			=>'Cancel VoC',
				'rows_header'	=> $row_Head,
				'rows_detail'	=> $row_Det
			);
			
			$this->load->view('Cust_complain/cancel_complain',$data);
		}
	}
	
	function update_voc($kode_voc=''){
		if($this->input->post()){
			//echo"<pre>";print_r($this->input->post());exit;
			## PARAMETER POST ##
			$Kode_VOC		= $this->input->post('kode_voc');
			$PIC_Incharge	= $this->input->post('pic_incharge');
			$det_Incharge	= $this->master_model->getArray('members',array('id'=>$PIC_Incharge),'id','nama');
			$Follow_By		= "OTO-SISTEM";
			$Follow_Date	= date('Y-m-d H:i:s');
			$data_Detail	= $this->input->post('detDetail');
			## CEK STATUS ##
			$det_Status		= $this->db->get_where('complain_customers',array('id'=>$Kode_VOC))->result();
			if($det_Status[0]->sts_voc !== 'OPN'){
				$Arr_Data	= array(
					'pesan'		=>'Data cannot be process. Its had been processed...',
					'status'	=> 2
					
				);
			}else{
				$Upd_Header	= array(
					'sts_voc'			=> 'FOL',
					'modified_by'		=> $Follow_By,
					'modified_date'		=> $Follow_Date,
					'pic_incharge'		=> $PIC_Incharge,
					'pic_incharge_name'	=> $det_Incharge[$PIC_Incharge]
				);
				$Insert_Log	= array(
					'complain_customer_id'	=> $Kode_VOC,
					'sts_voc'				=> 'FOL',
					'descr'					=> 'FOLLOW UP VoC '.$det_Status[0]->nomor,
					'update_by'				=> $Follow_By,
					'update_date'			=> $Follow_Date
				);
				$Insert_Action		= array();
				if($data_Detail){
					$Juml			= 0;
					foreach($data_Detail as $key=>$valD){
						$Kode_Detail		= $valD['header'];
						$det_Loop			= $valD['detail'];
						$Loop				= 0;
						foreach($det_Loop as $keyL => $valL){
							$Juml++;
							$Loop++;
							$det_comp		= $Kode_Detail.'-'.$Loop;
							$Insert_Action[$Juml]	= array(
								'id'							=> $det_comp,
								'complain_customer_detail_id'	=> $Kode_Detail,
								'plan_action'					=> strtoupper($valL['descr']),
								'plan_action_by_id'				=> $PIC_Incharge,
								'plan_action_by_name'			=> $det_Incharge[$PIC_Incharge],
								'plan_due_date'					=> $valL['plan_date'],	
								'sts_action'					=> 'OPN'
							);
						}
					}
				}
				$this->db->trans_start();		
				$this->db->update('complain_customers',$Upd_Header,array('id'=>$Kode_VOC));
				$this->db->insert('complain_customer_logs',$Insert_Log);
				$this->db->insert_batch('complain_customer_actions',$Insert_Action);
				$this->db->trans_complete();
				if($this->db->trans_status() === FALSE){
					$this->db->trans_rollback();
					$Arr_Data	= array(
						'pesan'		=>'Follow up process failed. Please try again later ...',
						'status'		=> 2
					);			
				}else{
					$this->db->trans_commit();
					$Arr_Data	= array(
						'pesan'		=>'Follow up process success. Thanks ...',
						'status'	=> 1
						
					);					
					//history('Follow Up Voc  : '.$Kode_VOC);
					
				}
			}
			echo json_encode($Arr_Data);
		}else{
			$Arr_Akses		= $this->Arr_Akses;
			if($Arr_Akses['update'] !='1'){
				$this->session->set_flashdata("alert_data", "<div class=\"alert alert-warning\" id=\"flash-message\">You Don't Have Right To Access This Page, Please Contact Your Administrator....</div>");
				redirect(site_url('customer_voice'));
			}
			
			$row_Head		= $this->master_model->getArray('complain_customers',array('id'=>$kode_voc));
			$row_Det		= $this->master_model->getArray('complain_customer_details',array('complain_customer_id'=>$kode_voc));
			$row_User		= $this->master_model->getArray('members',array('status'=>'1'),'id','nama');
			$data			= array(
				'action'		=>'update_voc',
				'title'			=>'Input Action Plan',
				'rows_header'	=> $row_Head,
				'rows_detail'	=> $row_Det,
				'rows_incharge'	=> $row_User
			);
			
			$this->load->view('Cust_complain/Update_complain',$data);
		}
	}
}
