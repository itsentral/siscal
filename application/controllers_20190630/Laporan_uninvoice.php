<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Laporan_uninvoice extends CI_Controller {	
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
		
		$data			= array(
			'action'	=>'index',
			'title'		=>'SO Outs Invoice'
		);
		
		$this->load->view('Report/laporan_outs_invoice',$data);
		
	}
	function get_data_display(){
		$data_server 	= akses_server_side();
		$WHERE			="(flag_invoice  IS NULL OR flag_invoice='' OR flag_invoice='N')";
		
		
		$table 		= 'view_letter_order_reports';
		$primaryKey = 'id';
		$columns 	= array(
			array( 'db' => 'id', 'dt' => 'id'),
			 array(
				'db' => 'id',
				'dt' => 'DT_RowId'
			),
			array( 'db' => 'no_so', 'dt' => 'no_so'),
			array( 'db' => 'customer_id', 'dt' => 'customer_id'),
			array( 'db' => 'customer_name', 'dt' => 'customer_name'),
			array( 'db' => 'address', 'dt' => 'address'),
			array( 'db' => 'pic', 'dt' => 'pic'),
			array( 'db' => 'quotation_nomor', 'dt' => 'quotation_nomor'),
			array( 'db' => 'pono', 'dt' => 'pono'),
			array( 'db' => 'flag_so_insitu', 'dt' => 'flag_so_insitu'),
			array( 'db' => 'first_so', 'dt' => 'first_so'),
			array( 'db' => 'member_name', 'dt' => 'member_name'),
			array( 
				'db' => 'tgl_so', 
				'dt'=> 'tgl_so',
				'formatter' => function($d,$row){
					return date('d M Y',strtotime($d));
				}
			),
			array( 
				'db' => 'podate', 
				'dt'=> 'podate',
				'formatter' => function($d,$row){
					return date('d M Y',strtotime($d));
				}
			),
			array( 
				'db' => 'quotation_date', 
				'dt'=> 'quotation_date',
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
				'db' => 'total_akomodasi', 
				'dt'=> 'total_akomodasi',
				'formatter' => function($d,$row){
					return number_format($d);
				}
			),
			array( 
				'db' => 'total_subcon', 
				'dt'=> 'total_subcon',
				'formatter' => function($d,$row){
					return number_format($d);
				}
			),
			array( 
				'db' => 'tot_insitu', 
				'dt'=> 'tot_insitu',
				'formatter' => function($d,$row){
					return number_format($d);
				}
			),
			array( 
				'db' => 'quot_net', 
				'dt'=> 'quot_net',
				'formatter' => function($d,$row){
					return number_format($d);
				}
			),
			array( 
				'db' => 'customer_fee', 
				'dt'=> 'customer_fee',
				'formatter' => function($d,$row){
					return number_format($d);
				}
			),
			array( 
				'db' => 'total_so', 
				'dt'=> 'total_so',
				'formatter' => function($d,$row){
					return number_format($d);
				}
			),
			array( 
				'db' => 'subcon_so', 
				'dt'=> 'subcon_so',
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
	
	function excel_laporan_uninvoice(){
		set_time_limit(0);
		//ini_set('memory_limit','524MB');
		$Qry_Records	= "SELECT
							no_so,
							tgl_so,
							customer_name,
							flag_so_insitu,
							quotation_nomor,
							quotation_date,
							pono,
							podate,
							grand_tot,
							ppn,
							total_akomodasi,
							total_subcon,
							tot_insitu,
							member_name,
							first_so,
							total_so,
							subcon_so
						FROM
							view_letter_order_reports
						WHERE
							(
								flag_invoice IS NULL
								OR flag_invoice = ''
								OR flag_invoice = 'N'
							)";
		$records		= $this->db->query($Qry_Records)->result_array();
		$data			= array(
			'action'	=>'index',
			'title'		=>'SO Outs Invoice',
			'rows_data'	=> $records
		);
		
		$this->load->view('Report/laporan_outs_invoice_excel',$data);
	}
}
