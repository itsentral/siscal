<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Dashboard_inv_bad extends CI_Controller {	
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
		
		$this->load->view('view_dashboard/dashboard_inv_bad',$data);
		
	}
	
	## GET OTHER  DASHBOARD ## 
	function get_other_dashboard($kategori){
		
		$WHERE				= "head_ar.bulan = DATE_FORMAT(CURRENT_DATE(), '%c')
							AND head_ar.tahun = DATE_FORMAT(CURRENT_DATE(), '%Y')
							AND head_ar.saldo_akhir > 0
							AND (
								DATEDIFF(
									CURRENT_DATE (),

								IF (
									head_inv.receive_date IS NULL,
									head_inv.datet,
									head_inv.receive_date
								)
								) > '60'
							)";
		
		if($kategori==6){
			## POTENTIAL BAD DEBT ##
			$WHERE			.=" AND head_ar.saldo_akhir != head_inv.pph23
								AND head_ar.saldo_akhir != head_inv.ppn";
			
									
			
		}else if($kategori==9){
			$WHERE			.=" AND head_ar.saldo_akhir = head_inv.pph23";
		}else if($kategori==11){
			##POTENTIAL BAD DEBT PPN ##
			$WHERE			.=" AND head_ar.saldo_akhir = head_inv.ppn";
		}
		$Query_Data			= "SELECT
								head_inv.invoice_no,
								head_inv.datet,
								head_inv.customer_name,
								head_inv.grand_tot,
								head_inv.receive_date,
								head_inv.id,
								head_inv.address,
								head_ar.saldo_akhir AS hutang,
								(head_inv.grand_tot - head_ar.saldo_akhir) AS total_payment,
								DATEDIFF(CURRENT_DATE (),IF(head_inv.receive_date IS NULL,head_inv.datet,head_inv.receive_date)) AS leadtime
							FROM
								invoices head_inv
							INNER JOIN account_receivables head_ar ON head_inv.invoice_no = head_ar.invoice_no
							WHERE
								".$WHERE;
		$records		= $this->db->query($Query_Data)->result_array();
		
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
							AND (
								DATEDIFF(
									CURRENT_DATE (),

								IF (
									head_inv.receive_date IS NULL,
									head_inv.datet,
									head_inv.receive_date
								)
								) > '60'
							)";
		
		if($kategori==6){
			## POTENTIAL BAD DEBT ##
			$WHERE			.=" AND head_ar.saldo_akhir != head_inv.pph23
								AND head_ar.saldo_akhir != head_inv.ppn";
			
									
			
		}else if($kategori==9){
			$WHERE			.=" AND head_ar.saldo_akhir = head_inv.pph23";
		}else if($kategori==11){
			##POTENTIAL BAD DEBT PPN ##
			$WHERE			.=" AND head_ar.saldo_akhir = head_inv.ppn";
		}
		$Query_Data			= "SELECT
								head_inv.invoice_no,
								head_inv.datet,
								head_inv.customer_name,
								head_inv.grand_tot,
								head_inv.receive_date,
								head_inv.id,
								head_inv.address,
								head_ar.saldo_akhir AS hutang,
								(head_inv.grand_tot - head_ar.saldo_akhir) AS total_payment,
								DATEDIFF(CURRENT_DATE (),IF(head_inv.receive_date IS NULL,head_inv.datet,head_inv.receive_date)) AS leadtime
							FROM
								invoices head_inv
							INNER JOIN account_receivables head_ar ON head_inv.invoice_no = head_ar.invoice_no
							WHERE
								".$WHERE;
		$records		= $this->db->query($Query_Data)->result_array();
		
		
		
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
		$Qry_ALL		= "SELECT
								SUM(
									CASE
									WHEN head_ar.saldo_akhir != head_inv.pph23
									AND head_ar.saldo_akhir != head_inv.ppn THEN
										head_ar.saldo_akhir
									ELSE
										0
									END
								) AS debt_all,
							SUM(
									CASE
									WHEN head_ar.saldo_akhir = head_inv.pph23 THEN
										head_ar.saldo_akhir
									ELSE
										0
									END
								) AS debt_pph,
							SUM(
									CASE
									WHEN head_ar.saldo_akhir = head_inv.ppn THEN
										head_ar.saldo_akhir
									ELSE
										0
									END
								) AS debt_ppn
							FROM
								invoices head_inv
							INNER JOIN account_receivables head_ar ON head_inv.invoice_no = head_ar.invoice_no
							WHERE
								head_ar.bulan = DATE_FORMAT(CURRENT_DATE(), '%c')
							AND head_ar.tahun = DATE_FORMAT(CURRENT_DATE(), '%Y')
							AND head_ar.saldo_akhir > 0
							AND (
								DATEDIFF(
									CURRENT_DATE (),

								IF (
									head_inv.receive_date IS NULL,
									head_inv.datet,
									head_inv.receive_date
								)
								) > '60'
							)";
		
		$det_ALL							= $this->db->query($Qry_ALL)->result_array();
		$Arr_Return['total_bad_debt']		= round($det_ALL[0]['debt_all'] / 1000000);
		$Arr_Return['total_bad_debt_pph']	= round($det_ALL[0]['debt_pph'] / 1000000);
		$Arr_Return['total_bad_debt_ppn']	= round($det_ALL[0]['debt_ppn'] / 1000000);
		
		/*
		## BAD DEBT ##
		$Query_Data						= "SELECT 
														
													SUM(grand_tot - total_payment) AS total_debt
												FROM view_invoice_payment_reports 
												WHERE 
													(grand_tot - total_payment) > 0
													AND (grand_tot - total_payment) <> pph23
													AND (grand_tot - total_payment) <> ppn
													AND DATEDIFF(CURRENT_DATE (),IF(receive_date IS NULL,datet,receive_date)) > 60";
		$Bad_Debt						= $this->db->query($Query_Data)->result_array();
		
		$Arr_Return['total_bad_debt']	= round($Bad_Debt[0]['total_debt'] / 1000000);
		
		
		## BAD DEBT PPH ##
		$Query_Data						= "SELECT 
														
													SUM(grand_tot - total_payment) AS total_debt
												FROM view_invoice_payment_reports 
												WHERE 
													(grand_tot - total_payment) =pph23
													AND DATEDIFF(CURRENT_DATE (),IF(receive_date IS NULL,datet,receive_date)) > 60";
		$Bad_Debt_PPH						= $this->db->query($Query_Data)->result_array();
		
		$Arr_Return['total_bad_debt_pph']	= round($Bad_Debt_PPH[0]['total_debt'] / 1000000);
			
		
		## BAD DEBT PPN ##
		$Query_Data						= "SELECT 
														
													SUM(grand_tot - total_payment) AS total_debt
												FROM view_invoice_payment_reports 
												WHERE 
													(grand_tot - total_payment) =ppn
													AND DATEDIFF(CURRENT_DATE (),IF(receive_date IS NULL,datet,receive_date)) > 60";
		$Bad_Debt_PPN						= $this->db->query($Query_Data)->result_array();
		
		$Arr_Return['total_bad_debt_ppn']	= round($Bad_Debt_PPN[0]['total_debt'] / 1000000);
		
		*/
		
		if($json=='Y'){
			echo json_encode($Arr_Return);
		}else{
			return $Arr_Return;
		}
		
	}
	
	
	
	
}
