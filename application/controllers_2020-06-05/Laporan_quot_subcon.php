<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Laporan_quot_subcon extends CI_Controller {	
	public function __construct() {
		parent::__construct();
		$this->load->model('master_model');
		// Your own constructor code
		/*
		if(!$this->session->userdata('isSISCALlogin')){
			redirect('login');
		}
		*/
		$this->folder ='Report';
	}	
	public function index() {
		$periode_awal	= date('Y-m-01');
		$periode_akhir	= date('Y-m-d');
		if($this->input->post()){
			$periode_awal	= $this->input->post('periode_awal');
			$periode_akhir	= $this->input->post('periode_akhir');			
		}
		
		$WHERE			= "head_quot.`status` <> 'REV' AND det_quot.supplier_id <> 'COMP-001'";
		if(!empty($periode_awal) && !empty($periode_akhir)){
			if(!empty($WHERE))$WHERE	.=" AND ";
			$WHERE	.="(head_quot.datet BETWEEN '".$periode_awal."' AND '".$periode_akhir."')";
		}
		$Query_Data		= "SELECT
								det_quot.*, head_quot.nomor,
								head_quot.customer_id,
								head_quot.customer_name,
								head_quot.datet,
								head_quot.pono,
								head_quot.podate,
								head_quot.`status`,
								head_quot.reason,
								head_quot.old_id
							FROM
								quotation_details det_quot
							INNER JOIN quotations head_quot ON det_quot.quotation_id = head_quot.id
							WHERE
								".$WHERE."
							ORDER BY
								head_quot.datet ASC";
		$records		= $this->db->query($Query_Data)->result_array();
		
		$data			= array(
			'action'		=>'index',
			'title'			=>'Laporan Penjualan Subcon',
			'records'		=> $records,
			'periode_akhir'	=> $periode_akhir,
			'periode_awal'	=> $periode_awal
		);
		
		$this->load->view($this->folder.'/laporan_quot_subcon',$data);
		
	}
	
	function excel_laporan($periode_awal='',$periode_akhir=''){
		set_time_limit(0);
		$WHERE			= "head_quot.`status` <> 'REV' AND det_quot.supplier_id <> 'COMP-001'";
		if(!empty($periode_awal) && !empty($periode_akhir)){
			if(!empty($WHERE))$WHERE	.=" AND ";
			$WHERE	.="(head_quot.datet BETWEEN '".$periode_awal."' AND '".$periode_akhir."')";
		}
		$Query_Data		= "SELECT
								det_quot.*, head_quot.nomor,
								head_quot.customer_id,
								head_quot.customer_name,
								head_quot.datet,
								head_quot.pono,
								head_quot.podate,
								head_quot.`status`,
								head_quot.reason,
								head_quot.old_id
							FROM
								quotation_details det_quot
							INNER JOIN quotations head_quot ON det_quot.quotation_id = head_quot.id
							WHERE
								".$WHERE."
							ORDER BY
								head_quot.datet ASC";
		$records		= $this->db->query($Query_Data)->result_array();
		
		$data			= array(
			'action'		=>'index',
			'title'			=>'Laporan Penjualan Subcon',
			'records'		=> $records,
			'periode_akhir'	=> $periode_akhir,
			'periode_awal'	=> $periode_awal
		);
		
		$this->load->view($this->folder.'/laporan_quot_subcon_excel',$data);
	}
}
