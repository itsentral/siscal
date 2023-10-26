<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Customer_voice_cancel extends CI_Controller {	
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
			'title'			=>'List Of Cancel VoC',
			'akses_menu'	=> $Arr_Akses
		);
		
		$this->load->view('Cust_complain/list_cancel',$data);
		
	}
	
	function get_data_display(){
		include "ssp.class.php";
		$WHERE		="sts_voc='CNC'";
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
				'db' => 'cancel_date',
				'dt'=> 'cancel_date',
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
	
	
}
