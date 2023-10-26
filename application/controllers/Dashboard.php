<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Dashboard extends CI_Controller {	
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
			'title'		=>'Dashboard',
			'rows_data'	=> $records_data
		);
		
		$this->load->view('dashboard',$data);
		
	}
	public function getdetail($tipe){
		
		$Tanggal		= date('Y-m-');
		$WHERE			= "";		
		if($tipe==1){
			if(!empty($WHERE))$WHERE.=" AND ";
			$WHERE		.= "tgl_old LIKE '".$Tanggal."%' AND status IN ('OPN','CNC','FAL','REC')";			
		}else if($tipe==2){
			if(!empty($WHERE))$WHERE.=" AND ";
			$WHERE		.= "podate LIKE '".$Tanggal."%' AND status IN ('REC')";
			
		}else if($tipe==3){
			if(!empty($WHERE))$WHERE.=" AND ";
			$WHERE		.= "cancel_date LIKE '".$Tanggal."%' AND status IN ('FAL')";
			
		}else if($tipe==4){
			if(!empty($WHERE))$WHERE.=" AND ";
			$WHERE		.= "cancel_date LIKE '".$Tanggal."%' AND status IN ('CNC')";
			
		}
		$Query_Data		= "SELECT * FROM view_quotation_totals WHERE ".$WHERE;		
		$Records		= $this->db->query($Query_Data)->result_array();
		
		$data			= array(
			'tipe'			=> $tipe,
			'records'		=> $Records
		);
		
		$this->load->view('view_dashboard/getdetail',$data);
		
		
	}
	
	
	
	## JSON DATA DASHBOARD
	function json_dashboard($json='Y'){
		$Tanggal		= date('Y-m-');
		$Arr_Return		= array();
		
		$Qry_All					="SELECT
											SUM(
												CASE
												WHEN tgl_old LIKE '".$Tanggal."%' THEN
													total_dpp - tot_insitu - total_akomodasi - total_subcon - customer_fee
												ELSE
													0
												END
											) AS total_all,

										SUM(
												CASE
												WHEN podate LIKE '".$Tanggal."%' THEN
													total_dpp - tot_insitu - total_akomodasi - total_subcon - customer_fee
												ELSE
													0
												END
											) AS total_po,
										
										SUM(
												CASE
												WHEN cancel_date LIKE '".$Tanggal."%' AND status='FAL'  THEN
													total_dpp - tot_insitu - total_akomodasi - total_subcon - customer_fee
												ELSE
													0
												END
											) AS total_fail,
										SUM(
												CASE
												WHEN cancel_date LIKE '".$Tanggal."%' AND status='CNC'  THEN
													total_dpp - tot_insitu - total_akomodasi - total_subcon - customer_fee
												ELSE
													0
												END
											) AS total_cancel
										FROM
											view_quotation_totals";
		$Data_All					= $this->db->query($Qry_All)->result_array();
		$Nilai_Total				= round($Data_All[0]['total_all'] / 1000000);
		$Nilai_Deal					= round($Data_All[0]['total_po'] / 1000000);
		$Nilai_Fail					= round($Data_All[0]['total_fail'] / 1000000);
		$Nilai_Cancel				= round($Data_All[0]['total_cancel'] / 1000000);
		
		$Arr_Return['total_quot']	= $Nilai_Total;
		$Arr_Return['deal_quot']	= $Nilai_Deal;
		$Arr_Return['fail_quot']	= $Nilai_Fail;
		$Arr_Return['cancel_quot']	= $Nilai_Cancel;
		
			
		if($json=='Y'){
			echo json_encode($Arr_Return);
		}else{
			return $Arr_Return;
		}
		
	}
	
	public function logout() {
		$this->session->sess_destroy();
		history('Logout');
		// if (!$this->session->userdata()) {
		redirect('login');
		// }
	}
	
	
}
