<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Laporan_out_bast_certificate extends CI_Controller {	
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
		$Query_data		= "SELECT
							letter_order_id,
							quotation_nomor,
							quotation_id,
							no_so,
							tgl_so,
							pono,
							customer_name,
							COUNT(tool_id) AS tot_qty
						FROM
							view_send_certificate_outstandings
						GROUP BY
							letter_order_id
						ORDER BY
							letter_order_id ASC";
		$rows_data		= $this->db->query($Query_data)->result();
		
		$data			= array(
			'action'		=>'index',
			'title'			=>'Outstanding BAST Certificate',
			'rows_header'	=> $rows_data
		);
		
		$this->load->view('Report/laporan_bast_certificate',$data);
		
	}
	
	
	function excel_letter(){
		set_time_limit(0);
		//ini_set('memory_limit','524MB');
		$Query_data		= "SELECT
							letter_order_id,
							quotation_nomor,
							quotation_id,
							no_so,
							tgl_so,
							pono,
							customer_name,
							COUNT(tool_id) AS tot_qty
						FROM
							view_send_certificate_outstandings
						GROUP BY
							letter_order_id
						ORDER BY
							letter_order_id ASC";
		$rows_data		= $this->db->query($Query_data)->result_array();
		$data			= array(
			'action'	=>'index',
			'title'		=>'Outstanding BAST Certificate',
			'rows_data'	=> $rows_data
		);
		
		$this->load->view('Report/exc_outs_bast_certificate',$data);
	}
	
	function excel_detail(){
		$rows_data		= $this->db->get('view_send_certificate_outstandings')->result_array();
		$data			= array(
			'action'	=>'index',
			'title'		=>'Outstanding BAST Certificate Detail',
			'rows_data'	=> $rows_data
		);
		
		$this->load->view('Report/exc_outs_bast_certificate_detail',$data);
	}
}
