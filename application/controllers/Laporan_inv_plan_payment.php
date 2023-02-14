<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Laporan_inv_plan_payment extends CI_Controller {	
	public function __construct() {
		parent::__construct();
		$this->load->model('master_model');
		// Your own constructor code
		
		if(!$this->session->userdata('isSISCALlogin')){
			redirect('login');
		}
		$controller			= ucfirst(strtolower($this->uri->segment(1)));
		$this->Arr_Akses	= getAcccesmenu($controller);
	}	
	public function index() {
		$Arr_Akses		= $this->Arr_Akses;
		if($Arr_Akses['read'] !='1'){
			$this->session->set_flashdata("alert_data", "<div class=\"alert alert-warning\" id=\"flash-message\">You Don't Have Right To Access This Page, Please Contact Your Administrator....</div>");
			redirect(site_url('dashboard'));
		}
		
		
		$data			= array(
			'action'	=>'index',
			'title'		=>'Invoice Plan Payment',
			'akses_menu'	=> $Arr_Akses
		);
		
		$this->load->view('Report/laporan_inv_plan_payment',$data);
		
	}
	function get_data_display(){
		$data_server 	= akses_server_side();
		$Tgl_Now		= date('Y-m-d');
		$Tgl_Next		= date('Y-m-d',strtotime('+3 month',strtotime($Tgl_Now)));
		$WHERE			="((grand_tot - total_payment) >= (total_dpp - pph23)) AND (plan_payment BETWEEN '$Tgl_Now' AND '$Tgl_Next')";
		
		
		$table 		= 'view_invoice_payment_reports';
		$primaryKey = 'id';
		$columns 	= array(
			array( 'db' => 'id', 'dt' => 'id'),
			 array(
				'db' => 'id',
				'dt' => 'DT_RowId'
			),
			array( 'db' => 'invoice_no', 'dt' => 'invoice_no'),
			array( 'db' => 'customer_id', 'dt' => 'customer_id'),
			array( 'db' => 'customer_name', 'dt' => 'customer_name'),
			array( 'db' => 'address', 'dt' => 'address'),
			array( 'db' => 'no_faktur', 'dt' => 'no_faktur'),
			
			array( 
				'db' 	=> 'datet', 
				'dt'	=> 'datet',
				'formatter' => function($d,$row){
					return date('d M Y',strtotime($d));
				}
			),
			array( 
				'db' => 'plan_payment', 
				'dt'=> 'plan_payment',
				'formatter' => function($d,$row){
					return date('d M Y',strtotime($d));
				}
			),
			
			array( 
				'db' => 'grand_tot', 
				'dt'=> 'grand_tot',
				'formatter' => function($d,$row){
					return number_format($d);
				}
			),
			array( 
				'db' => 'ppn', 
				'dt'=> 'ppn',
				'formatter' => function($d,$row){
					return number_format($d);
				}
			),
			array( 
				'db' => 'total_dpp', 
				'dt'=> 'total_dpp',
				'formatter' => function($d,$row){
					return number_format($d);
				}
			),
			array( 
				'db' => 'pph23', 
				'dt'=> 'pph23',
				'formatter' => function($d,$row){
					return number_format($d);
				}
			),
			array( 
				'db' => 'total_payment', 
				'dt'=> 'total_payment',
				'formatter' => function($d,$row){
					return number_format($d);
				}
			)
		);
	
	
		$sql_details = array(
			'user' => $data_server['hostuser'],
			'pass' => $data_server['hostpass'],
			'db'   => $data_server['hostdb'],
			'host' => $data_server['hostname']
		);
		include( 'ssp.class.php' );
		
		
		echo json_encode(
			SSP::complex ($_POST, $sql_details, $table, $primaryKey, $columns,null, $WHERE)
		);
	}
	
	function excel_laporan_inv_payment(){
		set_time_limit(0);
		$Tgl_Now		= date('Y-m-d');
		$Tgl_Next		= date('Y-m-d',strtotime('+3 month',strtotime($Tgl_Now)));
		$WHERE			="((grand_tot - total_payment) >= (total_dpp - pph23)) AND (plan_payment BETWEEN '$Tgl_Now' AND '$Tgl_Next')";
		$Qry_Records	= "SELECT
							invoice_no,
							datet,
							customer_id,
							customer_name,
							address,
							total_dpp,
							ppn,
							pph23,
							grand_tot,
							no_faktur,
							due_date,
							send_date,
							receive_date,
							receive_by,
							total_payment,
							plan_payment
						FROM
							view_invoice_payment_reports
						WHERE ".$WHERE;
		$records		= $this->db->query($Qry_Records)->result_array();
		$data			= array(
			'action'	=>'index',
			'title'		=>'Invoice Plan Payment',
			'rows_data'	=> $records
		);
		
		$this->load->view('Report/laporan_plan_payment_inv_excel',$data);
	}
}
