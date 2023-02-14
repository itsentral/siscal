<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Laporan_invoice extends CI_Controller {	
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
		$WHERE_Inv		= "grand_tot > 0";
		if(!empty($WHERE_Inv))$WHERE_Inv	.=" AND ";
		$WHERE_Inv	.="(datet BETWEEN '$periode_awal' AND '$periode_akhir')";
		
		$Query_Data		= "SELECT * FROM invoices WHERE ".$WHERE_Inv;
		$records		= $this->db->query($Query_Data)->result_array();
		if($records){
			foreach($records as $key=>$vals){
				$No_Inv		= $vals['invoice_no'];
				$No_SO		= '';
				$Query_SO	= "SELECT det_so.no_so FROM letter_orders det_so INNER JOIN invoice_details det_inv ON det_so.id=det_inv.letter_order_id WHERE det_inv.invoice_id='".$vals['id']."' GROUP BY det_so.id";
				//echo "<br>".$Query_SO;
				$det_SO		= $this->db->query($Query_SO)->result();
				if($det_SO){
					foreach($det_SO as $keys=>$values){
						if(!empty($No_SO))$No_SO	.=",";
						$No_SO	.=$values->no_so;
					}
				}
				$records[$key]['no_so']	= $No_SO;
				
				$rows_Quot		= '';
				## AMBIL DATA PO ##
				$Query_PO		= "SELECT quot_det.pono FROM quotations quot_det INNER JOIN invoice_details det_inv ON quot_det.id=det_inv.quotation_id  WHERE det_inv.invoice_id='".$vals['id']."' GROUP BY quot_det.pono";
				$det_PO			= $this->db->query($Query_PO)->result();

				if($det_PO){
					foreach($det_PO as $keyP=>$valP){
						if(!empty($rows_Quot))$rows_Quot	.=", ";
						$rows_Quot	.=$valP->pono;
					}
				}
				
				$records[$key]['pono']	= $rows_Quot;
				
				
			}
		}
		$data			= array(
			'action'		=>'index',
			'title'			=>'Laporan Invoice',
			'rows_data'		=> $records,
			'periode_akhir'	=> $periode_akhir,
			'periode_awal'	=> $periode_awal
		);
		
		$this->load->view($this->folder.'/laporan_invoice',$data);
		
	}
	
	function excel_laporan_invoice($periode_awal='',$periode_akhir=''){
		set_time_limit(0);
		
		$WHERE_Inv		= "grand_tot > 0";
		if(!empty($WHERE_Inv))$WHERE_Inv	.=" AND ";
		$WHERE_Inv	.="(datet BETWEEN '$periode_awal' AND '$periode_akhir')";
		
		$Query_Data		= "SELECT * FROM invoices WHERE ".$WHERE_Inv;
		$records		= $this->db->query($Query_Data)->result_array();
		if($records){
			foreach($records as $key=>$vals){
				$No_Inv		= $vals['invoice_no'];
				$No_SO		= '';
				$Query_SO	= "SELECT det_so.no_so FROM letter_orders det_so INNER JOIN invoice_details det_inv ON det_so.id=det_inv.letter_order_id WHERE det_inv.invoice_id='".$vals['id']."' GROUP BY det_so.id";
				//echo "<br>".$Query_SO;
				$det_SO		= $this->db->query($Query_SO)->result();
				if($det_SO){
					foreach($det_SO as $keys=>$values){
						if(!empty($No_SO))$No_SO	.=",";
						$No_SO	.=$values->no_so;
					}
				}
				$records[$key]['no_so']	= $No_SO;
				
				$rows_Quot		= '';
				## AMBIL DATA PO ##
				$Query_PO		= "SELECT quot_det.pono FROM quotations quot_det INNER JOIN invoice_details det_inv ON quot_det.id=det_inv.quotation_id  WHERE det_inv.invoice_id='".$vals['id']."' GROUP BY quot_det.pono";
				$det_PO			= $this->db->query($Query_PO)->result();

				if($det_PO){
					foreach($det_PO as $keyP=>$valP){
						if(!empty($rows_Quot))$rows_Quot	.=", ";
						$rows_Quot	.=$valP->pono;
					}
				}
				
				$records[$key]['pono']	= $rows_Quot;
				
				
			}
		}
		
		$data			= array(
			'action'		=>'index',
			'title'			=>'Laporan Invoice',
			'rows_data'		=> $records,
			'periode_akhir'	=> $periode_akhir,
			'periode_awal'	=> $periode_awal
		);
		
		$this->load->view($this->folder.'/laporan_invoice_excel',$data);
	}
}
