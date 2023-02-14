<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Dashboard_process extends CI_Controller {	
	public function __construct() {
		parent::__construct();
		$this->load->model('master_model');
		// Your own constructor code
		/*
		if(!$this->session->userdata('isSISCALlogin')){
			redirect('login');
		}
		*/
	}	
	public function index() {
		$records_data	= $this->json_dashboard('N');
		$data			= array(
			'action'	=>'index',
			'title'		=>'Dashboard Calibration Process',
			'rows_data'	=> $records_data
		);
		
		$this->load->view('view_dashboard/dashboard_process',$data);
		
	}
	
	## GET OTHER  DASHBOARD ## 
	function get_other_dashboard($kategori){
		if($kategori==7){
			## LATE SCHEDULE ##
			$Query_Data		= "SELECT 
									det_so.*,
									DATEDIFF(CURRENT_DATE (),det_so.tgl_so) AS leadtime,
									det_quot.nomor AS quotation_nomor,
									det_quot.pono,
									det_quot.podate
								FROM letter_orders det_so INNER JOIN quotations det_quot ON det_so.quotation_id=det_quot.id
								WHERE 
									det_so.sts_so='OPN'
									AND DATEDIFF(CURRENT_DATE (),det_so.tgl_so) > 2";
			$records		= $this->db->query($Query_Data)->result_array();	
			
			
		}
		
		$data			= array(
			'kategori'		=> $kategori,
			'records'		=> $records
		);
		
		$this->load->view('view_dashboard/get_other_dashboard',$data);	
		
	}
	
	
	
	function excel_other_dashboard($kategori){
		if($kategori==7){
			## LATE SCHEDULE ##
			$Query_Data		= "SELECT 
									det_so.*,
									DATEDIFF(CURRENT_DATE (),det_so.tgl_so) AS leadtime,
									det_quot.nomor AS quotation_nomor,
									det_quot.pono,
									det_quot.podate
								FROM letter_orders det_so INNER JOIN quotations det_quot ON det_so.quotation_id=det_quot.id
								WHERE 
									det_so.sts_so='OPN'
									AND DATEDIFF(CURRENT_DATE (),det_so.tgl_so) > 2";
			$records		= $this->db->query($Query_Data)->result_array();	
			
			
		}
		
		$data			= array(
			'kategori'		=> $kategori,
			'records'		=> $records
		);
		
		$this->load->view('view_dashboard/excel_other_dashboard',$data);
	}
	## END OTHER DASHBOARD ##
	
	## JSON DATA DASHBOARD
	function json_dashboard($json='Y'){
		$Arr_Return		= array();
		
		## LATE KALIBRASI ##
		$sekarang						= date('Y-m-d');
		$Query_Cal						= "SELECT * FROM view_late_calibration_process_tools WHERE plan_process_date < '".$sekarang."'";
		$Late_Kalibrasi					= $this->db->query($Query_Cal)->num_rows();
		
		$Arr_Return['late_kalibrasi']	= $Late_Kalibrasi;
		
		## LATE KIRIM SUBCON ##
		$Query_Sub						= "SELECT * FROM view_late_subcont_send_tools WHERE plan_subcon_send_date < '".$sekarang."'";
		$Late_Kirim_Subcon				= $this->db->query($Query_Sub)->num_rows();
		
		$Arr_Return['late_kirim_subcon']= $Late_Kirim_Subcon;
		
		## LATE AMBIL SUBCON ##
		$Query_Sub						= "SELECT * FROM view_late_subcont_pick_tools WHERE plan_subcon_pick_date < '".$sekarang."'";
		$Late_Ambil_Subcon				= $this->db->query($Query_Sub)->num_rows();		
		$Arr_Return['late_ambil_subcon']= $Late_Ambil_Subcon;
		
		## LATE KIRIM CUST ##
		$Query_Sub						= "SELECT * FROM view_late_send_customer_tools WHERE plan_delivery_date < '".$sekarang."'";
		$Late_Kirim_Cust				= $this->db->query($Query_Sub)->num_rows();	
		$Arr_Return['late_kirim_cust']	= $Late_Kirim_Cust;
		
		## LATE SCHEDULE ##		
		$Query_Sub						= "SELECT 
												*
											FROM letter_orders 
											WHERE 
												sts_so='OPN'
												AND DATEDIFF(CURRENT_DATE (),tgl_so) > 2";
		$Late_Schedule					= $this->db->query($Query_Sub)->num_rows();
		$Arr_Return['late_schedule']	= $Late_Schedule;
		
		
		if($json=='Y'){
			echo json_encode($Arr_Return);
		}else{
			return $Arr_Return;
		}
		
	}
	
	
	
	public function getlatedata($tipe){		
		$Tgl_Telat			= date('Y-m-d');		
		$Cond				= array();		
		if($tipe==2){
			$Table_Name		= 'view_late_calibration_process_tools';
			$Cond			= array(
				"plan_process_date <" => $Tgl_Telat
			);
			
		}else if($tipe==3){
			$Table_Name		= 'view_late_subcont_send_tools';
			$Cond			= array(
				"plan_subcon_send_date <" => $Tgl_Telat
			);
			
			
		}else if($tipe==4){
			$Table_Name		= 'view_late_subcont_pick_tools';
			$Cond			= array(
				"plan_subcon_pick_date <" => $Tgl_Telat
			);
			
			
		}else if($tipe==5){
			$Table_Name		= 'view_late_send_customer_tools';
			$Cond			= array(
				"plan_delivery_date <" => $Tgl_Telat
			);
			
			
		}
		$records		= $this->db->get_where($Table_Name,$Cond)->result_array();	
		
		$data			= array(
			'tipe'			=> $tipe,
			'records'		=> $records
		);
		
		$this->load->view('view_dashboard/getlatedata',$data);
		
			
	}
	public function export_excel($tipe_late){		
		$Tgl_Telat			= date('Y-m-d');
		if($tipe_late==2){
			$Table_Name		= 'view_late_calibration_process_tools';
			$Cond			= array(
				"plan_process_date <" => $Tgl_Telat
			);
			
		}else if($tipe_late==3){
			$Table_Name		= 'view_late_subcont_send_tools';
			$Cond			= array(
				"plan_subcon_send_date <" => $Tgl_Telat
			);
			
			
		}else if($tipe_late==4){
			$Table_Name		= 'view_late_subcont_pick_tools';
			$Cond			= array(
				"plan_subcon_pick_date <" => $Tgl_Telat
			);
			
			
		}else if($tipe_late==5){
			$Table_Name		= 'view_late_send_customer_tools';
			$Cond			= array(
				"plan_delivery_date <" => $Tgl_Telat
			);
			
			
		}
		$records		= $this->db->get_where($Table_Name,$Cond)->result_array();	
		
		$data			= array(
			'tipe_late'			=> $tipe_late,
			'records'		=> $records
		);
		
		$this->load->view('view_dashboard/export_excel',$data);
	}
	
	
}
