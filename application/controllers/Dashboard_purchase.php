<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Dashboard_purchase extends CI_Controller {	
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
			'title'		=>'Dashboard Incomplete',
			'rows_data'	=> $records_data
		);
		
		$this->load->view('view_dashboard/dashboard_po',$data);
		
	}
	
	## GET OTHER  DASHBOARD ## 
	function get_other_dashboard($kategori){
		
		if($kategori=='1'){			
			$records		= $this->db->get('view_quotation_po_incompletes')->result_array();
		}
		$data			= array(
			'kategori'		=> $kategori,
			'records'		=> $records
		);
		
		$this->load->view('view_dashboard/get_other_dashboard',$data);	
		
	}
	
	
	
	function excel_other_dashboard($kategori){
		if($kategori=='1'){			
			$records		= $this->db->get('view_quotation_po_incompletes')->result_array();
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
		
		## INCOMPLETE PO ##		
		$totRecords								= $this->db->get('view_quotation_po_incompletes')->num_rows();
		$Arr_Return['total_incomplete_quot']	= $totRecords;
		
		## OUTSTANDING SO RECEIVE ##
		
		$Out_Sales_Order						= $this->db->get('view_outstanding_receive_tools')->num_rows();
		$Arr_Return['total_incomplete_receive']	= $Out_Sales_Order;
		
		## OUTSTANDING BAST REC/SEND ##		
		$Count_Delivery							= $this->db->get('view_incomplete_delivery_reminders')->num_rows();
		$Arr_Return['total_incomplete_delivery']= $Count_Delivery;
		
		
		if($json=='Y'){
			echo json_encode($Arr_Return);
		}else{
			return $Arr_Return;
		}
		
	}
	
	
	
	public function get_incomplete_bast(){		
		$results		= $this->db->get('view_incomplete_delivery_reminders')->result_array();		
		$data			= array(
			'results'		=> $results
		);
		$this->load->view('view_dashboard/incomplete_bast',$data);
	}
	
	public function get_incomplete_receive(){
		$results		= $this->db->get('view_outstanding_receive_tools')->result_array();		
		$data			= array(
			'results'		=> $results
		);
		$this->load->view('view_dashboard/incomplete_receive',$data);
	}
}
