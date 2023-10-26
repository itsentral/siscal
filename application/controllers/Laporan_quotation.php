<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Laporan_quotation extends CI_Controller {	
	public function __construct() {
		parent::__construct();
		$this->load->model('master_model');
		// Your own constructor code
		
		if(!$this->session->userdata('isSISCALlogin')){
			redirect('login');
		}
		$controller			= ucfirst(strtolower($this->uri->segment(1)));
		$this->Arr_Akses	= getAcccesmenu($controller);
		$this->folder ='Report';
	}	
	public function index() {
		$Arr_Akses		= $this->Arr_Akses;
		if($Arr_Akses['read'] !='1'){
			$this->session->set_flashdata("alert_data", "<div class=\"alert alert-warning\" id=\"flash-message\">You Don't Have Right To Access This Page, Please Contact Your Administrator....</div>");
			redirect(site_url('dashboard'));
		}
		
		$periode_awal	= date('Y-m-01');
		$periode_akhir	= date('Y-m-d');
		if($this->input->post()){
			$periode_awal	= $this->input->post('periode_awal');
			$periode_akhir	= $this->input->post('periode_akhir');			
		}
		
		$WHERE			= "det_quot.`status` <> 'REV'";
		if(!empty($periode_awal) && !empty($periode_akhir)){
			if(!empty($WHERE))$WHERE	.=" AND ";
			$WHERE	.="(det_quot.datet BETWEEN '".$periode_awal."' AND '".$periode_akhir."')";
		}
		$Query_Data		= "SELECT
								det_quot.*, det_cust.reference_by,
								det_cust.reference_name,
								det_cust.reference_phone
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
					
				$Quot_Old		= $this->db->get_where('quotations',array('id'=>$vals['old_id']))->result();
				$Tgl_Old		= '-';
				if($Quot_Old){
					$Tgl_Old	= $Quot_Old[0]->datet;
					unset($Quot_Old);
				}
				
				$records[$key]['tgl_old']	= $Tgl_Old;
			}
		}
		$data			= array(
			'action'		=>'index',
			'title'			=>'Laporan Penawaran',
			'records'		=> $records,
			'periode_akhir'	=> $periode_akhir,
			'periode_awal'	=> $periode_awal,
			'akses_menu'	=> $Arr_Akses
		);
		
		$this->load->view($this->folder.'/laporan_quotation',$data);
		
	}
	
	function excel_laporan($periode_awal='',$periode_akhir=''){
		set_time_limit(0);
		$WHERE			= "det_quot.`status` <> 'REV'";
		if(!empty($periode_awal) && !empty($periode_akhir)){
			if(!empty($WHERE))$WHERE	.=" AND ";
			$WHERE	.="(det_quot.datet BETWEEN '".$periode_awal."' AND '".$periode_akhir."')";
		}
		$Query_Data		= "SELECT
								det_quot.*, det_cust.reference_by,
								det_cust.reference_name,
								det_cust.reference_phone
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
					
				$Quot_Old		= $this->db->get_where('quotations',array('id'=>$vals['old_id']))->result();
				$Tgl_Old		= '-';
				if($Quot_Old){
					$Tgl_Old	= $Quot_Old[0]->datet;
					unset($Quot_Old);
				}
				
				$records[$key]['tgl_old']	= $Tgl_Old;
			}
		}
		
		$data			= array(
			'action'		=>'index',
			'title'			=>'Laporan Quotation',
			'records'		=> $records,
			'periode_akhir'	=> $periode_akhir,
			'periode_awal'	=> $periode_awal
		);
		
		$this->load->view($this->folder.'/laporan_quotation_excel',$data);
	}
}
