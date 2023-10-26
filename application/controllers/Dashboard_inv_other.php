<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Dashboard_inv_other extends CI_Controller {	
	public function __construct() {
		parent::__construct();
		$this->load->model('master_model');
		// Your own constructor code
		
		if(!$this->session->userdata('isSISCALlogin')){
			redirect('login');
		}
		
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
		$WHERE				= "head_ar.bulan = DATE_FORMAT(CURRENT_DATE(), '%c')
							AND head_ar.tahun = DATE_FORMAT(CURRENT_DATE(), '%Y')
							AND head_ar.saldo_akhir > 0
							";
		if($kategori==2){
			## LATE INV SEND ##
			$Query_Data			= "SELECT 
										head_inv.invoice_no,
										head_inv.datet,
										head_inv.customer_name,
										head_inv.grand_tot,
										DATE_FORMAT(head_inv.created_date, '%Y-%m-%d') AS date_create,
										head_inv.id,
										head_inv.address,
										(head_inv.grand_tot - head_ar.saldo_akhir) AS total_payment,
										DATEDIFF(CURRENT_DATE (),DATE_FORMAT(head_inv.created_date, '%Y-%m-%d')) AS leadtime 
									FROM 
										invoices head_inv
										INNER JOIN account_receivables head_ar ON head_inv.invoice_no = head_ar.invoice_no
									WHERE
										".$WHERE."
										AND (
											head_inv.send_date IS NULL
											OR head_inv.send_date = ''
											OR head_inv.send_date = '-'
										)
										AND DATEDIFF(CURRENT_DATE (),DATE_FORMAT(head_inv.created_date, '%Y-%m-%d')) >= 3";
			
			$records		= $this->db->query($Query_Data)->result_array();
			
		}else if($kategori==3){
			## LATE INV FOLLOW UP 1 ##
			$Query_Data			= "SELECT 
										head_inv.invoice_no,
										head_inv.datet,
										head_inv.customer_name,
										head_inv.grand_tot,
										head_inv.receive_date,
										head_inv.id,
										head_inv.address,
										(head_inv.grand_tot - head_ar.saldo_akhir) AS total_payment,
										head_inv.plan_payment,
										DATEDIFF(CURRENT_DATE (),head_inv.receive_date) AS leadtime 
									FROM 
										invoices head_inv
										INNER JOIN account_receivables head_ar ON head_inv.invoice_no = head_ar.invoice_no
									WHERE
										".$WHERE."
										AND NOT(
											head_inv.send_date IS NULL
											OR head_inv.send_date = ''
											OR head_inv.send_date = '-'
										)
										AND (
											head_inv.follow_status IS NULL
											OR head_inv.follow_status = ''
										)
										AND head_ar.saldo_akhir =head_inv.grand_tot
										AND DATEDIFF(CURRENT_DATE (),head_inv.receive_date) >= 7";
			$records		= $this->db->query($Query_Data)->result_array();
			
			
		}else if($kategori==4){
			## LATE INV FOLLOW UP 2 ##
			$Query_Data			= "SELECT 
										head_inv.invoice_no,
										head_inv.datet,
										head_inv.customer_name,
										head_inv.grand_tot,
										head_inv.receive_date,
										head_inv.id,
										head_inv.address,
										(head_inv.grand_tot - head_ar.saldo_akhir) AS total_payment,
										(
											SELECT
												DATE_FORMAT(follow_up_date, '%Y-%m-%d') as tgl_follow 
											FROM
												follow_up_invoices
											WHERE
												follow_up_invoices.invoice_id = head_inv.id
											ORDER BY
												follow_up_invoices.follow_up_date DESC
											LIMIT 1
										) AS date_follow_up,
										head_inv.plan_payment,
										DATEDIFF(CURRENT_DATE (),head_inv.plan_payment) AS leadtime 
									FROM 
										invoices head_inv
										INNER JOIN account_receivables head_ar ON head_inv.invoice_no = head_ar.invoice_no
									WHERE
										".$WHERE."
										AND NOT(
											head_inv.send_date IS NULL
											OR head_inv.send_date = ''
											OR head_inv.send_date = '-'
										)
										AND NOT(
											head_inv.plan_payment IS NULL
											OR head_inv.plan_payment = ''
											OR head_inv.plan_payment = '-'
										)
										AND head_ar.saldo_akhir =head_inv.grand_tot
										AND DATEDIFF(CURRENT_DATE (),head_inv.plan_payment) > 0";
										
			
			$records		= $this->db->query($Query_Data)->result_array();
			
		}else if($kategori==12){
			## INVOICE MINUS ##
			$Query_Data		= "SELECT
								head_inv.invoice_no,
								head_inv.datet,
								head_inv.customer_name,
								head_inv.grand_tot,
								head_inv.receive_date,
								head_inv.id,
								head_inv.address,
								(head_inv.grand_tot - head_ar.saldo_akhir) AS total_payment,
								head_ar.saldo_akhir AS hutang,
								DATEDIFF(
									CURRENT_DATE (),
									IF (
										head_inv.receive_date IS NULL,
										head_inv.datet,
										head_inv.receive_date
									)
								) AS leadtime
								FROM 
									invoices head_inv
									INNER JOIN account_receivables head_ar ON head_inv.invoice_no = head_ar.invoice_no
								WHERE
									head_ar.bulan = DATE_FORMAT(CURRENT_DATE(), '%c')
									AND head_ar.tahun = DATE_FORMAT(CURRENT_DATE(), '%Y')
									AND head_ar.saldo_akhir < 0
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
		$WHERE				= "head_ar.bulan = DATE_FORMAT(CURRENT_DATE(), '%c')
							AND head_ar.tahun = DATE_FORMAT(CURRENT_DATE(), '%Y')
							AND head_ar.saldo_akhir > 0
							";
		if($kategori==2){
			## LATE INV SEND ##
			$Query_Data			= "SELECT 
										head_inv.invoice_no,
										head_inv.datet,
										head_inv.customer_name,
										head_inv.grand_tot,
										DATE_FORMAT(head_inv.created_date, '%Y-%m-%d') AS date_create,
										head_inv.id,
										head_inv.address,
										(head_inv.grand_tot - head_ar.saldo_akhir) AS total_payment,
										DATEDIFF(CURRENT_DATE (),DATE_FORMAT(head_inv.created_date, '%Y-%m-%d')) AS leadtime 
									FROM 
										invoices head_inv
										INNER JOIN account_receivables head_ar ON head_inv.invoice_no = head_ar.invoice_no
									WHERE
										".$WHERE."
										AND (
											head_inv.send_date IS NULL
											OR head_inv.send_date = ''
											OR head_inv.send_date = '-'
										)
										AND DATEDIFF(CURRENT_DATE (),DATE_FORMAT(head_inv.created_date, '%Y-%m-%d')) >= 3";
			
			$records		= $this->db->query($Query_Data)->result_array();
			
		}else if($kategori==3){
			## LATE INV FOLLOW UP 1 ##
			$Query_Data			= "SELECT 
										head_inv.invoice_no,
										head_inv.datet,
										head_inv.customer_name,
										head_inv.grand_tot,
										head_inv.receive_date,
										head_inv.id,
										head_inv.address,
										(head_inv.grand_tot - head_ar.saldo_akhir) AS total_payment,
										head_inv.plan_payment,
										DATEDIFF(CURRENT_DATE (),head_inv.receive_date) AS leadtime 
									FROM 
										invoices head_inv
										INNER JOIN account_receivables head_ar ON head_inv.invoice_no = head_ar.invoice_no
									WHERE
										".$WHERE."
										AND NOT(
											head_inv.send_date IS NULL
											OR head_inv.send_date = ''
											OR head_inv.send_date = '-'
										)
										AND (
											head_inv.follow_status IS NULL
											OR head_inv.follow_status = ''
										)
										AND head_ar.saldo_akhir =head_inv.grand_tot
										AND DATEDIFF(CURRENT_DATE (),head_inv.receive_date) >= 7";
			$records		= $this->db->query($Query_Data)->result_array();
			
			
		}else if($kategori==4){
			## LATE INV FOLLOW UP 2 ##
			$Query_Data			= "SELECT 
										head_inv.invoice_no,
										head_inv.datet,
										head_inv.customer_name,
										head_inv.grand_tot,
										head_inv.receive_date,
										head_inv.id,
										head_inv.address,
										(head_inv.grand_tot - head_ar.saldo_akhir) AS total_payment,
										(
											SELECT
												DATE_FORMAT(follow_up_date, '%Y-%m-%d') as tgl_follow 
											FROM
												follow_up_invoices
											WHERE
												follow_up_invoices.invoice_id = head_inv.id
											ORDER BY
												follow_up_invoices.follow_up_date DESC
											LIMIT 1
										) AS date_follow_up,
										head_inv.plan_payment,
										DATEDIFF(CURRENT_DATE (),head_inv.plan_payment) AS leadtime 
									FROM 
										invoices head_inv
										INNER JOIN account_receivables head_ar ON head_inv.invoice_no = head_ar.invoice_no
									WHERE
										".$WHERE."
										AND NOT(
											head_inv.send_date IS NULL
											OR head_inv.send_date = ''
											OR head_inv.send_date = '-'
										)
										AND NOT(
											head_inv.plan_payment IS NULL
											OR head_inv.plan_payment = ''
											OR head_inv.plan_payment = '-'
										)
										AND head_ar.saldo_akhir =head_inv.grand_tot
										AND DATEDIFF(CURRENT_DATE (),head_inv.plan_payment) > 0";
										
			
			$records		= $this->db->query($Query_Data)->result_array();
			
		}else if($kategori==12){
			## INVOICE MINUS ##
			$Query_Data		= "SELECT
								head_inv.invoice_no,
								head_inv.datet,
								head_inv.customer_name,
								head_inv.grand_tot,
								head_inv.receive_date,
								head_inv.id,
								head_inv.address,
								(head_inv.grand_tot - head_ar.saldo_akhir) AS total_payment,
								head_ar.saldo_akhir AS hutang,
								DATEDIFF(
									CURRENT_DATE (),
									IF (
										head_inv.receive_date IS NULL,
										head_inv.datet,
										head_inv.receive_date
									)
								) AS leadtime
								FROM 
									invoices head_inv
									INNER JOIN account_receivables head_ar ON head_inv.invoice_no = head_ar.invoice_no
								WHERE
									head_ar.bulan = DATE_FORMAT(CURRENT_DATE(), '%c')
									AND head_ar.tahun = DATE_FORMAT(CURRENT_DATE(), '%Y')
									AND head_ar.saldo_akhir < 0
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
		$WHERE				= "head_ar.bulan = DATE_FORMAT(CURRENT_DATE(), '%c')
							AND head_ar.tahun = DATE_FORMAT(CURRENT_DATE(), '%Y')
							AND head_ar.saldo_akhir > 0
							";
		## LATE SEND INVOICE ##
		$Query_Data							= "SELECT 
													head_inv.invoice_no									
												FROM 
													invoices head_inv
													INNER JOIN account_receivables head_ar ON head_inv.invoice_no = head_ar.invoice_no
												WHERE
													".$WHERE."
													AND (
														head_inv.send_date IS NULL
														OR head_inv.send_date = ''
														OR head_inv.send_date = '-'
													)
													AND DATEDIFF(CURRENT_DATE (),DATE_FORMAT(head_inv.created_date, '%Y-%m-%d')) >= 3";
		$Late_Inv_Send						= $this->db->query($Query_Data)->num_rows();
		
		$Arr_Return['total_late_inv_send']	= $Late_Inv_Send;
		
		// LATE FOLLOW UP
		$Query_Data								= "SELECT 
														head_inv.invoice_no
													FROM 
														invoices head_inv
														INNER JOIN account_receivables head_ar ON head_inv.invoice_no = head_ar.invoice_no
													WHERE 
														".$WHERE."
														AND NOT(
															head_inv.send_date IS NULL
															OR head_inv.send_date = ''
															OR head_inv.send_date = '-'
														)
														AND (
															head_inv.follow_status IS NULL
															OR head_inv.follow_status = ''
														)
														AND head_ar.saldo_akhir =head_inv.grand_tot
														AND DATEDIFF(CURRENT_DATE (),head_inv.receive_date) >= 7";
		$Late_FollowUp1							= $this->db->query($Query_Data)->num_rows();		
		$Arr_Return['total_late_inv_follow1']	= $Late_FollowUp1;
		
		## LATE FOLLOW UP 2 ##
		$Query_Data								= "SELECT 
														head_inv.invoice_no
													FROM 
														invoices head_inv
														INNER JOIN account_receivables head_ar ON head_inv.invoice_no = head_ar.invoice_no 
													WHERE 
														".$WHERE."
														AND NOT(
															head_inv.send_date IS NULL
															OR head_inv.send_date = ''
															OR head_inv.send_date = '-'
														)
														AND NOT(
															head_inv.plan_payment IS NULL
															OR head_inv.plan_payment = ''
															OR head_inv.plan_payment = '-'
														)
														AND head_ar.saldo_akhir =head_inv.grand_tot
														AND DATEDIFF(CURRENT_DATE (),head_inv.plan_payment) > 0";
		$Late_FollowUp2							= $this->db->query($Query_Data)->num_rows();
		$Arr_Return['total_late_inv_follow2']	= $Late_FollowUp2;
		
		
		
		## PIUTANG MINUS ##
		$Query_Data						= "SELECT 														
												SUM(head_ar.saldo_akhir) AS total_debt												
											FROM 
												invoices head_inv
												INNER JOIN account_receivables head_ar ON head_inv.invoice_no = head_ar.invoice_no
												WHERE
													head_ar.bulan = DATE_FORMAT(CURRENT_DATE(), '%c')
													AND head_ar.tahun = DATE_FORMAT(CURRENT_DATE(), '%Y')
													AND head_ar.saldo_akhir < 0";
		$Piutang_Minus					= $this->db->query($Query_Data)->result_array();
		
		$Arr_Return['piutang_minus']	= round(($Piutang_Minus[0]['total_debt'] * -1) / 1000000);
		
		if($json=='Y'){
			echo json_encode($Arr_Return);
		}else{
			return $Arr_Return;
		}
		
	}
	
	
	
	
}
