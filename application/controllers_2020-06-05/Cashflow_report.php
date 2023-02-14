<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cashflow_report extends CI_Controller {	
	public function __construct() {
		parent::__construct();
		$this->load->model('master_model');
		// Your own constructor code
		/*
		if(!$this->session->userdata('isSISCALlogin')){
			redirect('login');
		}
		*/
		$this->Folder	='Report';
	}	
	public function index() {
		$Arr_Bulan		= array('Jan','Feb','Mar','Apr','Mei','Jun','Jul','Aug','Sep','Oct','Nov','Dec');
		$Tahun_Pilih	= date('Y');
		if($this->input->post()){
			$Tahun_Pilih	= $this->input->post('periode');
		}
		$records_data	= $this->get_CashFlow($Tahun_Pilih,'N');
		$data			= array(
			'action'		=>'index',
			'title'			=>'Report Plan Payment',
			'rows_data'		=> $records_data,
			'rows_month'	=> $Arr_Bulan,
			'tahun_pilih'	=> $Tahun_Pilih
		);
		
		$this->load->view($this->Folder.'/laporan_cashflow',$data);
		
	}
	
	
	## DASHBOARD SO ##
	function get_CashFlow($Year_Data,$Flag_Json='N'){
		$det_Detail			= array();
		$arr_Plan			= $arr_Paid_Plan	= $arr_Paid_All	= array();
		$Arr_Bulan			= array('1'=>'Jan','Feb','Mar','Apr','Mei','Jun','Jul','Aug','Sep','Oct','Nov','Dec');
		$Pembagi			= 1000000;
		for($x=1;$x<=12;$x++){
			$Plan_Total		= 0;
			$Plan_Paid		= 0;
			$Total_Paid		= 0;
			$Non_Plan		= 0;
			$Month_Name		= $Arr_Bulan[$x];
			
			$Periode_Bulan	= date('Ym',mktime(0,0,0,$x,1,$Year_Data));
			$Periode_Month	= date('Y-m',mktime(0,0,0,$x,1,$Year_Data));
			
			## AMBIL DATA PLAN  ##
			$Qry_Plan		= "SELECT SUM(total_inv) as total_plan FROM invoice_plan_payments WHERE plan_payment LIKE '".$Periode_Month."%' AND total_inv > 0";
			$det_Plan		= $this->db->query($Qry_Plan)->result();
			if($det_Plan){
				$Plan_Total	= ($det_Plan[0]->total_plan > 0)?$det_Plan[0]->total_plan:0;
			}
			
			## AMBIL TOTAL PAYMENT ATAS PLAN ##
			$Qry_Plan_Paid	= "SELECT
								SUM(
									IF (
										det_bayar.kredit > 0,
										det_bayar.kredit,
										0
									) -
									IF (
										det_bayar.debet > 0,
										det_bayar.debet,
										0
									)
								) AS paid_total
							FROM
								trans_ar_jurnals det_bayar
							INNER JOIN invoice_plan_payments det_plan ON det_bayar.invoice_no = det_plan.no_invoice
							WHERE
								det_bayar.bln_proses = '".$Periode_Bulan."'
							AND (
								det_bayar.flag_batal IS NULL
								OR det_bayar.flag_batal = 'N'
								OR det_bayar.flag_batal = ''
							)
							AND det_plan.plan_payment LIKE '".$Periode_Month."%'";
			$det_Plan_Paid	= $this->db->query($Qry_Plan_Paid)->result();
			if($det_Plan_Paid){
				$Plan_Paid	= ($det_Plan_Paid[0]->paid_total > 0)?$det_Plan_Paid[0]->paid_total:0;
			}
			
			## TOTAL JURNAL ##
			$Qry_Payment	= "SELECT
								SUM(
									IF (
										det_bayar.kredit > 0,
										det_bayar.kredit,
										0
									) -
									IF (
										det_bayar.debet > 0,
										det_bayar.debet,
										0
									)
								) AS payment_total
							FROM
								trans_ar_jurnals det_bayar
							WHERE
								det_bayar.bln_proses = '".$Periode_Bulan."'
							AND (
								det_bayar.flag_batal IS NULL
								OR det_bayar.flag_batal = 'N'
								OR det_bayar.flag_batal = ''
							)";
			$det_Payment	= $this->db->query($Qry_Payment)->result();
			if($det_Payment){
				$Total_Paid 	= ($det_Payment[0]->payment_total > 0)?$det_Payment[0]->payment_total:0;
			}
			
			$Non_Plan		= $Total_Paid - $Plan_Paid;
			
			$det_Detail[$Month_Name]	= array(
				'total_plan'	=> $Plan_Total,
				'plan_paid'		=> $Plan_Paid,
				'non_plan'		=> $Non_Plan,
				'total_paid'	=> $Total_Paid
			);
			array_push($arr_Plan,round($Plan_Total/$Pembagi));
			array_push($arr_Paid_Plan,round($Plan_Paid/$Pembagi));
			array_push($arr_Paid_All,round($Non_Plan/$Pembagi));
		}
		$Arr_Chart		= array(
			array('name'=>'Plan','data'=>$arr_Plan),
			array('name'=>'Payment Of Plan','data'=>$arr_Paid_Plan),
			array('name'=>'Payment Unplanned','data'=>$arr_Paid_All)
		);
		$Arr_Balikan	= array('rows_table'=>$det_Detail,'rows_chart'=>$Arr_Chart);
		if($Flag_Json === 'Y'){
			echo json_encode($Arr_Balikan);
		}else{
			return $Arr_Balikan;
		}
		
		
	}
	
	function view_detail(){
		$Tahun_Pilih	= $this->input->post('periode');
		$Bulan_Pilih	= $this->input->post('bulan');
		$Tipe_Pilih		= $this->input->post('tipe');
		$Arr_Bulan			= array('1'=>'Jan','Feb','Mar','Apr','Mei','Jun','Jul','Aug','Sep','Oct','Nov','Dec');
		
		$Month_Det		= array_search($Bulan_Pilih,$Arr_Bulan);
		
		$Periode_Pilih	= date('Y-m',mktime(0,0,0,$Month_Det,1,$Tahun_Pilih));
		$Periode_AR		= date('Ym',mktime(0,0,0,$Month_Det,1,$Tahun_Pilih));
		$Periode_Name	= date('F Y',mktime(0,0,0,$Month_Det,1,$Tahun_Pilih));
		
		$JUDUL			= "View Detail ";
		
		if(strtolower($Tipe_Pilih)==='total_plan'){
			$JUDUL			.="Plan Pembayaran";
			$Query_Data		= "SELECT det_inv.*,det_plan.total_inv AS total_find, 0 AS paid_total FROM invoice_plan_payments det_plan INNER JOIN invoices det_inv ON det_plan.no_invoice=det_inv.invoice_no WHERE det_plan.plan_payment LIKE '".$Periode_Pilih."%'";
		}else  if(strtolower($Tipe_Pilih)==='plan_paid'){
			$JUDUL			.="Pembayaran Atas Plan";
			$Query_Data		= "SELECT
								det_inv.*,
								det_Plan.total_inv AS total_find,
								SUM(
									IF (
										det_bayar.kredit > 0,
										det_bayar.kredit,
										0
									) -
									IF (
										det_bayar.debet > 0,
										det_bayar.debet,
										0
									)
								) AS paid_total
							FROM
								trans_ar_jurnals det_bayar
							INNER JOIN invoice_plan_payments det_plan ON det_bayar.invoice_no = det_plan.no_invoice
							INNER JOIN invoices det_inv ON det_inv.invoice_no = det_plan.no_invoice
							WHERE
								det_bayar.bln_proses = '".$Periode_AR."'
							AND (
								det_bayar.flag_batal IS NULL
								OR det_bayar.flag_batal = 'N'
								OR det_bayar.flag_batal = ''
							)
							AND det_plan.plan_payment LIKE '".$Periode_Pilih."%' GROUP BY det_plan.no_invoice";
		}else  if(strtolower($Tipe_Pilih)==='non_plan'){
			$JUDUL			.="Pembayaran Non Plan";
			$Query_Data		= "SELECT
								det_inv.*,
								0 AS total_find,
								SUM(
									IF (
										det_bayar.kredit > 0,
										det_bayar.kredit,
										0
									) -
									IF (
										det_bayar.debet > 0,
										det_bayar.debet,
										0
									)
								) AS paid_total
							FROM
								trans_ar_jurnals det_bayar
							INNER JOIN invoices det_inv ON det_inv.invoice_no = det_bayar.invoice_no
							WHERE
								det_bayar.bln_proses = '".$Periode_AR."'
							AND (
								det_bayar.flag_batal IS NULL
								OR det_bayar.flag_batal = 'N'
								OR det_bayar.flag_batal = ''
							)
							AND det_inv.invoice_no NOT IN (SELECT no_invoice FROM invoice_plan_payments WHERE plan_payment LIKE '".$Periode_Pilih."%') GROUP BY det_inv.invoice_no";
		}
		$JUDUL			.=" ".$Periode_Name;
		$records		= $this->db->query($Query_Data)->result();
		$data			= array(
			'action'		=>'index',
			'rows_judul'	=>$JUDUL,
			'rows_data'		=> $records,
		);
		
		$this->load->view($this->Folder.'/detail_cashflow',$data);
	}
	
	
	
	
	
}
