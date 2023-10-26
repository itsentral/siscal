<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Laporan_pph extends CI_Controller {	
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
		$WHERE_Inv		= "det_trans.tipe = 'CN'
							AND det_ar.accid = '1030-10-10'
							AND NOT (
								det_ar.no_reff IS NULL
								OR det_ar.no_reff = '-'
								OR det_ar.no_reff = ''
							)
							AND det_ar.kredit > 0
							AND det_ar.keterangan LIKE '%pph%'";
		if(!empty($WHERE_Inv))$WHERE_Inv	.=" AND ";
		$WHERE_Inv	.="(det_trans.tgl_reff BETWEEN '$periode_awal' AND '$periode_akhir')";
		
		$Query_Data		= "SELECT
								det_trans.jurnalid,
								det_trans.tgl_jurnal,
								det_trans.no_reff AS no_bukti_potong,
								det_trans.tgl_reff AS tgl_bukti_potong,
								det_trans.no_ntpn,
								det_ar.no_reff,
								det_ar.kredit
							FROM
								trans_jurnal_details det_ar
							INNER JOIN trans_jurnal_headers det_trans ON det_ar.jurnalid = det_trans.jurnalid
							WHERE ".$WHERE_Inv;
		$records		= $this->db->query($Query_Data)->result_array();
		if($records){
			foreach($records as $key=>$vals){
				$No_Inv		= $vals['no_reff'];
				
				$Inv_Date	= '-';
				$DPP		= $PPH = 0;
				$Customer	= '-';				
				
				$Query_INV	= "SELECT * FROM invoices WHERE invoice_no='".$No_Inv."'";
				$det_INV		= $this->db->query($Query_INV)->result();
				if($det_INV){
					$Inv_Date		= $det_INV[0]->datet;
					$Customer		= $det_INV[0]->customer_name;
					$DPP			= $det_INV[0]->total_dpp;
					$PPH			= $det_INV[0]->pph23;
				}
				$records[$key]['inv_date']	= $Inv_Date;
				$records[$key]['customer']	= $Customer;
				$records[$key]['dpp']		= $DPP;
				$records[$key]['pph']		= $PPH;
			}
		}
		$data			= array(
			'action'		=>'index',
			'title'			=>'Laporan PPH',
			'rows_data'		=> $records,
			'periode_akhir'	=> $periode_akhir,
			'periode_awal'	=> $periode_awal
		);
		
		$this->load->view($this->folder.'/laporan_pph',$data);
		
	}
	
	function excel_laporan_invoice($periode_awal='',$periode_akhir=''){
		set_time_limit(0);
		$WHERE_Inv		= "det_trans.tipe = 'CN'
							AND det_ar.accid = '1030-10-10'
							AND NOT (
								det_ar.no_reff IS NULL
								OR det_ar.no_reff = '-'
								OR det_ar.no_reff = ''
							)
							AND det_ar.kredit > 0
							AND det_ar.keterangan LIKE '%pph%'";
		if(!empty($WHERE_Inv))$WHERE_Inv	.=" AND ";
		$WHERE_Inv	.="(det_trans.tgl_reff BETWEEN '$periode_awal' AND '$periode_akhir')";
		
		$Query_Data		= "SELECT
								det_trans.jurnalid,
								det_trans.tgl_jurnal,
								det_trans.no_reff AS no_bukti_potong,
								det_trans.tgl_reff AS tgl_bukti_potong,
								det_trans.no_ntpn,
								det_ar.no_reff,
								det_ar.kredit
							FROM
								trans_jurnal_details det_ar
							INNER JOIN trans_jurnal_headers det_trans ON det_ar.jurnalid = det_trans.jurnalid
							WHERE ".$WHERE_Inv;
		$records		= $this->db->query($Query_Data)->result_array();
		if($records){
			foreach($records as $key=>$vals){
				$No_Inv		= $vals['no_reff'];
				
				$Inv_Date	= '-';
				$DPP		= $PPH = 0;
				$Customer	= '-';				
				
				$Query_INV	= "SELECT * FROM invoices WHERE invoice_no='".$No_Inv."'";
				$det_INV		= $this->db->query($Query_INV)->result();
				if($det_INV){
					$Inv_Date		= $det_INV[0]->datet;
					$Customer		= $det_INV[0]->customer_name;
					$DPP			= $det_INV[0]->total_dpp;
					$PPH			= $det_INV[0]->pph23;
				}
				$records[$key]['inv_date']	= $Inv_Date;
				$records[$key]['customer']	= $Customer;
				$records[$key]['dpp']		= $DPP;
				$records[$key]['pph']		= $PPH;
			}
		}
		
		$data			= array(
			'action'		=>'index',
			'title'			=>'Laporan PPH 23',
			'rows_data'		=> $records,
			'periode_akhir'	=> $periode_akhir,
			'periode_awal'	=> $periode_awal
		);
		
		$this->load->view($this->folder.'/laporan_pph_excel',$data);
	}
}
