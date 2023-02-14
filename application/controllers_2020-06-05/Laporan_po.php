<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Laporan_po extends CI_Controller {	
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
		
		$WHERE			= "det_quot.`status` = 'REC'";
		if(!empty($periode_awal) && !empty($periode_akhir)){
			if(!empty($WHERE))$WHERE	.=" AND ";
			$WHERE	.="(det_quot.datet BETWEEN '".$periode_awal."' AND '".$periode_akhir."')";
		}
		$Query_Data		= "SELECT
								det_quot.*, det_cust.reference_by,
								det_cust.reference_name,
								det_cust.reference_phone,
								det_cust.first_so_date
							FROM
								quotations det_quot
							INNER JOIN customers det_cust ON det_quot.customer_id = det_cust.id
							WHERE
								".$WHERE."
							GROUP BY
								det_quot.old_id
							ORDER BY
								det_quot.datet DESC";
		$records		= $this->db->query($Query_Data)->result_array();
		if($records){
			foreach($records as $key=>$vals){
				$No_Quot		= $vals['id'];
				$Nil_Subcon		= $Cust_Fee = 0;
				if($vals['success_fee'] > 0){
					$Cust_Fee	= $vals['success_fee'];
				}
				$No_SO			= '';
				$nilai_total	= $vals['grand_tot'] - $vals['ppn'];
				$nilai_insitu	= ($vals['total_insitu'] > 0)?$vals['total_insitu']:0;
				$nilai_akom		= ($vals['total_akomodasi'] > 0)?$vals['total_akomodasi']:0;
				$Quot_ID		= $vals['id'];
				
				$Query_Subcon	= "SELECT
									SUM(
										CASE
										WHEN supplier_id <> 'COMP-001' THEN
											qty * hpp
										ELSE
											0
										END
									) as jum_subcon
								FROM
									quotation_details
								WHERE
									quotation_details.quotation_id='".$No_Quot."'";
				//echo "<br>".$Query_SO;
				$det_Subcon		= $this->db->query($Query_Subcon)->result();
				if($det_Subcon){
					$Nil_Subcon	= $det_Subcon[0]->jum_subcon;			
				}
				$records[$key]['total_subcon']	= $Nil_Subcon;
				$records[$key]['customer_fee']	= $Cust_Fee;
				
				
				$Status			= '-';
				if(!empty($vals['first_so_date']) && $vals['first_so_date'] !='0000-00-00' && $vals['first_so_date'] !='1970-01-01'){
					$Beda_Hari	= (strtotime($vals['podate']) - strtotime($vals['first_so_date'])) / (60*60*24);
					if($Beda_Hari > 365){
						$Status	= 'Repeat';
					}else{
						$Status	= 'New';
					}
				}
					
				
				$records[$key]['keterangan']	= $Status;
				
				$No_SO			= '';
				$Query_Letter	= "SELECT * FROM letter_orders WHERE quotation_id='".$Quot_ID."' AND sts_so NOT IN ('CNC','REV')";
				$det_SO			= $this->db->query($Query_Letter)->result();
				if($det_SO){
					foreach($det_SO as $keySO=>$valSO){
						if(!empty($No_SO))$No_SO	.=", ";
						$No_SO	.= $valSO->no_so;
					}
				}
				
				$nilai_akhir				= $nilai_total - $nilai_insitu - $Nil_Subcon - $nilai_akom - $Cust_Fee;
				$records[$key]['nil_akhir']	= $nilai_akhir;
				$records[$key]['grand_tot']	= $nilai_total;
				
				$records[$key]['no_so']		= $No_SO;
				
			}
		}
		$data			= array(
			'action'		=>'index',
			'title'			=>'Laporan PO',
			'records'		=> $records,
			'periode_akhir'	=> $periode_akhir,
			'periode_awal'	=> $periode_awal
		);
		
		$this->load->view($this->folder.'/laporan_po',$data);
		
	}
	
	function excel_laporan($periode_awal='',$periode_akhir=''){
		set_time_limit(0);
		$WHERE			= "det_quot.`status` = 'REC'";
		if(!empty($periode_awal) && !empty($periode_akhir)){
			if(!empty($WHERE))$WHERE	.=" AND ";
			$WHERE	.="(det_quot.datet BETWEEN '".$periode_awal."' AND '".$periode_akhir."')";
		}
		$Query_Data		= "SELECT
								det_quot.*, det_cust.reference_by,
								det_cust.reference_name,
								det_cust.reference_phone,
								det_cust.first_so_date
							FROM
								quotations det_quot
							INNER JOIN customers det_cust ON det_quot.customer_id = det_cust.id
							WHERE
								".$WHERE."
							GROUP BY
								det_quot.old_id
							ORDER BY
								det_quot.datet DESC";
		$records		= $this->db->query($Query_Data)->result_array();
		if($records){
			foreach($records as $key=>$vals){
				$No_Quot		= $vals['id'];
				$Nil_Subcon		= $Cust_Fee = 0;
				if($vals['success_fee'] > 0){
					$Cust_Fee	= $vals['success_fee'];
				}
				$No_SO			= '';
				$nilai_total	= $vals['grand_tot'] - $vals['ppn'];
				$nilai_insitu	= ($vals['total_insitu'] > 0)?$vals['total_insitu']:0;
				$nilai_akom		= ($vals['total_akomodasi'] > 0)?$vals['total_akomodasi']:0;
				$Quot_ID		= $vals['id'];
				
				$Query_Subcon	= "SELECT
									SUM(
										CASE
										WHEN supplier_id <> 'COMP-001' THEN
											qty * hpp
										ELSE
											0
										END
									) as jum_subcon
								FROM
									quotation_details
								WHERE
									quotation_details.quotation_id='".$No_Quot."'";
				//echo "<br>".$Query_SO;
				$det_Subcon		= $this->db->query($Query_Subcon)->result();
				if($det_Subcon){
					$Nil_Subcon	= $det_Subcon[0]->jum_subcon;			
				}
				$records[$key]['total_subcon']	= $Nil_Subcon;
				$records[$key]['customer_fee']	= $Cust_Fee;
				
				
				$Status			= '-';
				if(!empty($vals['first_so_date']) && $vals['first_so_date'] !='0000-00-00' && $vals['first_so_date'] !='1970-01-01'){
					$Beda_Hari	= (strtotime($vals['podate']) - strtotime($vals['first_so_date'])) / (60*60*24);
					if($Beda_Hari > 365){
						$Status	= 'Repeat';
					}else{
						$Status	= 'New';
					}
				}
				$records[$key]['keterangan']	= $Status;
				
				$No_SO			= '';
				$Query_Letter	= "SELECT * FROM letter_orders WHERE quotation_id='".$Quot_ID."' AND sts_so NOT IN ('CNC','REV')";
				$det_SO			= $this->db->query($Query_Letter)->result();
				if($det_SO){
					foreach($det_SO as $keySO=>$valSO){
						if(!empty($No_SO))$No_SO	.=", ";
						$No_SO	.= $valSO->no_so;
					}
				}
				
				$nilai_akhir				= $nilai_total - $nilai_insitu - $Nil_Subcon - $nilai_akom - $Cust_Fee;
				$records[$key]['nil_akhir']	= $nilai_akhir;
				$records[$key]['grand_tot']	= $nilai_total;
				
				$records[$key]['no_so']		= $No_SO;
				
			}
		}
		
		$data			= array(
			'action'		=>'index',
			'title'			=>'Laporan PO',
			'records'		=> $records,
			'periode_akhir'	=> $periode_akhir,
			'periode_awal'	=> $periode_awal
		);
		
		$this->load->view($this->folder.'/laporan_po_excel',$data);
	}
}
