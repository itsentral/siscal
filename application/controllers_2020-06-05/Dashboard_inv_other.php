<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Dashboard_inv_other extends CI_Controller {	
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
			'title'		=>'Dashboard Invoice',
			'rows_data'	=> $records_data
		);
		
		$this->load->view('view_dashboard/dashboard_inv_other',$data);
		
	}
	
	## GET OTHER  DASHBOARD ## 
	function get_other_dashboard($kategori){
		
		if($kategori==2){
			## LATE INV SEND ##
			$Query_Data		= "SELECT 
									invoice_no,
									datet,
									customer_name,
									grand_tot,
									date_create,
									id,
									address,
									total_payment,
									DATEDIFF(CURRENT_DATE (),date_create) AS leadtime 
								FROM view_invoice_payment_reports 
								WHERE 
									(
										send_date IS NULL
										OR send_date = ''
										OR send_date = '-'
									)
									AND DATEDIFF(CURRENT_DATE (),date_create) >= 3";
			$records		= $this->db->query($Query_Data)->result_array();
			
		}else if($kategori==3){
			## LATE INV FOLLOW UP 1 ##
			$Query_Data		= "SELECT 
									invoice_no,
									datet,
									customer_name,
									grand_tot,
									receive_date,
									id,
									address,
									total_payment,
									DATEDIFF(CURRENT_DATE (),receive_date) AS leadtime 
								FROM view_invoice_payment_reports 
								WHERE 
									NOT (
										send_date IS NULL
										OR send_date = ''
										OR send_date = '-'
									)
									AND (
										date_follow_up IS NULL
										OR date_follow_up = ''
										OR date_follow_up = '-'
									)
									AND total_payment <=0
									AND DATEDIFF(CURRENT_DATE (),receive_date) >= 7";
			$records		= $this->db->query($Query_Data)->result_array();
			
			
		}else if($kategori==4){
			## LATE INV FOLLOW UP 2 ##
			$Query_Data		= "SELECT 
									invoice_no,
									datet,
									customer_name,
									grand_tot,
									receive_date,
									id,
									address,
									total_payment,
									date_follow_up,
									plan_payment,
									DATEDIFF(CURRENT_DATE (),plan_payment) AS leadtime 
								FROM view_invoice_payment_reports 
								WHERE 
									NOT (
										send_date IS NULL
										OR send_date = ''
										OR send_date = '-'
									)
									AND NOT(
										plan_payment IS NULL
										OR plan_payment = ''
										OR plan_payment = '-'
									)
									AND total_payment <=0
									AND total_follow_up > 0 
									AND DATEDIFF(CURRENT_DATE (),plan_payment) > 0";
			$records		= $this->db->query($Query_Data)->result_array();
			
		}else if($kategori==12){
			## INVOICE MINUS ##
			$Query_Data		= "SELECT
								invoice_no,
								datet,
								customer_name,
								grand_tot,
								receive_date,
								id,
								address,
								total_payment,
								(grand_tot - total_payment) AS hutang,
								DATEDIFF(
									CURRENT_DATE (),

								IF (
									receive_date IS NULL,
									datet,
									receive_date
								)
								) AS leadtime
							FROM
								view_invoice_payment_reports
							WHERE
								(grand_tot - total_payment) < 0
							";
			$records		= $this->db->query($Query_Data)->result_array();
		}
		
		$data			= array(
			'kategori'		=> $kategori,
			'records'		=> $records
		);
		
		$this->load->view('view_dashboard/get_other_dashboard',$data);	
		
	}
	
	
	
	function excel_other_dashboard($kategori){
		if($kategori==2){
			## LATE INV SEND ##
			$Query_Data		= "SELECT 
									invoice_no,
									datet,
									customer_name,
									grand_tot,
									date_create,
									id,
									address,
									total_payment,
									DATEDIFF(CURRENT_DATE (),date_create) AS leadtime 
								FROM view_invoice_payment_reports 
								WHERE 
									(
										send_date IS NULL
										OR send_date = ''
										OR send_date = '-'
									)
									AND DATEDIFF(CURRENT_DATE (),date_create) >= 3";
			$records		= $this->db->query($Query_Data)->result_array();
			
		}else if($kategori==3){
			## LATE INV FOLLOW UP 1 ##
			$Query_Data		= "SELECT 
									invoice_no,
									datet,
									customer_name,
									grand_tot,
									receive_date,
									id,
									address,
									total_payment,
									DATEDIFF(CURRENT_DATE (),receive_date) AS leadtime 
								FROM view_invoice_payment_reports 
								WHERE 
									NOT (
										send_date IS NULL
										OR send_date = ''
										OR send_date = '-'
									)
									AND (
										date_follow_up IS NULL
										OR date_follow_up = ''
										OR date_follow_up = '-'
									)
									AND total_payment <=0
									AND DATEDIFF(CURRENT_DATE (),receive_date) >= 7";
			$records		= $this->db->query($Query_Data)->result_array();
			
			
		}else if($kategori==4){
			## LATE INV FOLLOW UP 2 ##
			$Query_Data		= "SELECT 
									invoice_no,
									datet,
									customer_name,
									grand_tot,
									receive_date,
									id,
									address,
									total_payment,
									date_follow_up,
									plan_payment,
									DATEDIFF(CURRENT_DATE (),plan_payment) AS leadtime 
								FROM view_invoice_payment_reports 
								WHERE 
									NOT (
										send_date IS NULL
										OR send_date = ''
										OR send_date = '-'
									)
									AND NOT(
										plan_payment IS NULL
										OR plan_payment = ''
										OR plan_payment = '-'
									)
									AND total_payment <=0
									AND total_follow_up > 0
									AND DATEDIFF(CURRENT_DATE (),plan_payment) > 0";
			$records		= $this->db->query($Query_Data)->result_array();
			
		}else if($kategori==12){
			## INVOICE MINUS ##
			$Query_Data		= "SELECT
								invoice_no,
								datet,
								customer_name,
								grand_tot,
								receive_date,
								id,
								address,
								total_payment,
								(grand_tot - total_payment) AS hutang,
								DATEDIFF(
									CURRENT_DATE (),

								IF (
									receive_date IS NULL,
									datet,
									receive_date
								)
								) AS leadtime
							FROM
								view_invoice_payment_reports
							WHERE
								(grand_tot - total_payment) < 0
							";
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
		## LATE SEND INVOICE ##
		$Query_Data							= "SELECT 
													invoice_no									
												FROM view_invoice_payment_reports 
												WHERE 
													(
														send_date IS NULL
														OR send_date = ''
														OR send_date = '-'
													)
													AND DATEDIFF(CURRENT_DATE (),date_create) >= 3";
		$Late_Inv_Send						= $this->db->query($Query_Data)->num_rows();
		
		$Arr_Return['total_late_inv_send']	= $Late_Inv_Send;
		
		// LATE FOLLOW UP
		$Query_Data								= "SELECT 
														invoice_no
													FROM view_invoice_payment_reports 
													WHERE 
														NOT (
															send_date IS NULL
															OR send_date = ''
															OR send_date = '-'
														)
														AND (
															date_follow_up IS NULL
															OR date_follow_up = ''
															OR date_follow_up = '-'
														)
														AND total_payment <=0
														AND DATEDIFF(CURRENT_DATE (),receive_date) >= 7";
		$Late_FollowUp1							= $this->db->query($Query_Data)->num_rows();		
		$Arr_Return['total_late_inv_follow1']	= $Late_FollowUp1;
		
		## LATE FOLLOW UP 2 ##
		$Query_Data								= "SELECT 
															invoice_no
													FROM view_invoice_payment_reports 
													WHERE 
														NOT (
															send_date IS NULL
															OR send_date = ''
															OR send_date = '-'
														)
														AND NOT(
															plan_payment IS NULL
															OR plan_payment = ''
															OR plan_payment = '-'
														)
														AND total_payment <=0
														AND total_follow_up > 0
														AND DATEDIFF(CURRENT_DATE (),plan_payment) > 0";
		$Late_FollowUp2							= $this->db->query($Query_Data)->num_rows();
		$Arr_Return['total_late_inv_follow2']	= $Late_FollowUp2;
		
		
		
		## PIUTANG MINUS ##
		$Query_Data						= "SELECT 
														
													SUM(grand_tot - total_payment) AS total_debt
												FROM view_invoice_payment_reports 
												WHERE 
													(grand_tot - total_payment) < 0";
		$Piutang_Minus					= $this->db->query($Query_Data)->result_array();
		
		$Arr_Return['piutang_minus']	= round(($Piutang_Minus[0]['total_debt'] * -1) / 1000000);
		
		if($json=='Y'){
			echo json_encode($Arr_Return);
		}else{
			return $Arr_Return;
		}
		
	}
	
	
	
	
}
