<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Quot_insitu_cancel extends CI_Controller { 
	public function __construct(){
        parent::__construct();	
		
		$this->load->database();
        if(!$this->session->userdata('isSISCALlogin')){
			redirect('login');
		}
		$this->load->model('master_model');
		$controller			= ucfirst(strtolower($this->uri->segment(1)));
		$this->Arr_Akses	= getAcccesmenu($controller);
		
		$this->folder	= 'Quotation';
		$this->file_attachement	= $this->config->item('link_file');
		$this->file_location	= $this->config->item('location_file');
    }

	public function index(){
		$Arr_Akses			= $this->Arr_Akses;
		if($Arr_Akses['read'] !='1'){
			$this->session->set_flashdata("alert_data", "<div class=\"alert alert-warning\" id=\"flash-message\">You Don't Have Right To Access This Page, Please Contact Your Administrator....</div>");
			redirect(site_url('dashboard'));
		}
		
		$data = array(
			'title'			=> 'INSITU QUOTATION CANCELLATION',
			'action'		=> 'index',
			'akses_menu'	=> $Arr_Akses
		);
		history('View List Quotation');
		$this->load->view($this->folder.'/v_quot_insitu_outstanding',$data);
	}
	function get_data_display(){
		$Arr_Akses			= $this->Arr_Akses;
		
		$DateFr				= $this->input->post('datefr');
		$DateTl				= $this->input->post('datetl');
		
		$WHERE				= "head_quot. STATUS = 'REC'
								AND detail_quot.flag_insitu = 'Y'
								AND (
									detail_quot.qty - detail_quot.qty_so
								) > 0";
		if(!empty($DateFr) && !empty($DateTl)){
			if(!empty($WHERE))$WHERE	.=" AND ";
			$WHERE	.="(head_quot.datet BETWEEN '".$DateFr."' AND '".$DateTl."')";
		}
		
		$requestData	= $_REQUEST;
		
		$like_value     = $requestData['search']['value'];
        $column_order   = $requestData['order'][0]['column'];
        $column_dir     = $requestData['order'][0]['dir'];
        $limit_start    = $requestData['start'];
        $limit_length   = $requestData['length'];
		
		$columns_order_by = array(
			0 => 'head_quot.nomor',
			1 => 'head_quot.datet',
			2 => 'head_quot.customer_name',
			3 => 'head_quot.member_name',
			4 => 'head_quot.pono',
			5 => 'head_quot.podate'
		);
		
		
		
		if($like_value){
			if(!empty($WHERE))$WHERE	.=" AND ";
			$WHERE	.="(
						  head_quot.nomor LIKE '%".$this->db->escape_like_str($like_value)."%'
						  OR DATE_FORMAT(head_quot.datet, '%d %b %Y') LIKE '%".$this->db->escape_like_str($like_value)."%'
						  OR head_quot.customer_name LIKE '%".$this->db->escape_like_str($like_value)."%'
						  OR head_quot.member_name LIKE '%".$this->db->escape_like_str($like_value)."%'
						  OR head_quot.pono LIKE '%".$this->db->escape_like_str($like_value)."%'
						  OR DATE_FORMAT(head_quot.podate, '%d %b %Y') LIKE '%".$this->db->escape_like_str($like_value)."%'
						)";
		}
		
		
		$sql = "SELECT
					head_quot.id,
					head_quot.datet,
					head_quot.nomor,
					head_quot.pono,
					head_quot.podate,
					head_quot.customer_id,
					head_quot.customer_name,
					head_quot.member_id,
					head_quot.member_name,
					(@row:=@row+1) AS urut
				FROM
					quotation_details detail_quot
					INNER JOIN quotations head_quot ON head_quot.id = detail_quot.quotation_id,
				(SELECT @row:=0) r 
				WHERE ".$WHERE."
				GROUP BY
					head_quot.id
				";
		//print_r($sql);exit();
		$fetch['totalData'] 	= $this->db->query($sql)->num_rows();
		$fetch['totalFiltered']	= $this->db->query($sql)->num_rows();

		

		$sql .= " ORDER BY head_quot.datet DESC,".$columns_order_by[$column_order]." ".$column_dir." ";
		$sql .= " LIMIT ".$limit_start." ,".$limit_length." ";

		$fetch['query'] = $this->db->query($sql);
		
		$totalData		= $fetch['totalData'];
		$totalFiltered	= $fetch['totalFiltered'];
		$query			= $fetch['query'];
		
		$data		= array();
        $urut1  	= 1;
        $urut2  	= 0;
		$Periode_Now= date('Y-m');
		$Tahun_Now	= date('Y');
		foreach($query->result_array() as $row)
		{
			$total_data     = $totalData;
            $start_dari     = $requestData['start'];
            $asc_desc       = $requestData['order'][0]['dir'];
            if($asc_desc == 'asc')
            {
                $nomor = $urut1 + $start_dari;
            }
            if($asc_desc == 'desc')
            {
                $nomor = ($total_data - $start_dari) - $urut2;
            }
			$Code_Quot		= $row['id'];
			$nestedData		=array();
			$intL			= 0;
            foreach($columns_order_by as $keyI=>$valI){
				$intL++;
				$Pecah_Kode		= explode('.',$valI);
				$Field_Cari		= $Pecah_Kode[1];
				$Nilai_Data		= $row[$Field_Cari];
				
				
				if($intL === 2 || $intL === 6){
					if(!empty($Nilai_Data) && $Nilai_Data !== '-'){
						$Nilai_Data	= date('d-m-Y',strtotime($Nilai_Data));
					}					
				}
				
				$nestedData[] = $Nilai_Data;
			}  
			$Template		="<a href='".site_url('Quot_insitu_cancel/Cancel_insitu?quotation='.urlencode($Code_Quot))."' class='btn btn-sm bg-navy-active' title='DETAIL QUOTATION'> <i class='fa fa-search'></i> </a>";
			$nestedData[]	= $Template;
			
			
			$data[] = $nestedData;
            $urut1++;
            $urut2++;
		}

		$json_data = array(
			"draw"            => intval( $requestData['draw'] ),  
			"recordsTotal"    => intval( $totalData ),  
			"recordsFiltered" => intval( $totalFiltered ), 
			"data"            => $data
		);

		echo json_encode($json_data);
		
	}
	
	function Cancel_insitu(){
		$rows_header	= $rows_detail = array();
		if($this->input->get()){
			$Code_Quot		= urldecode($this->input->get('quotation'));
			$rows_header	= $this->db->get_where('quotations',array('id'=>$Code_Quot))->result();
			$Qry_Detail		= "SELECT * FROM quotation_details WHERE quotation_id = '".$Code_Quot."' AND (qty - qty_so) > 0 AND flag_insitu = 'Y'";
			$rows_detail	= $this->db->query($Qry_Detail)->result();
		}
		$Arr_Akses			= $this->Arr_Akses;
		$data = array(
			'title'			=> 'INSITU QUOTATION CANCELLATION',
			'action'		=> 'Cancel_insitu',
			'akses_menu'	=> $Arr_Akses,
			'rows_header'	=> $rows_header,
			'rows_detail'	=> $rows_detail
		);
		
		$this->load->view($this->folder.'/v_quot_insitu_cancel_process',$data);
	}
	
	function view_detail_cancellation(){
		$rows_Header = $rows_Detail = array();
		$Code_Back	= '';
		if($this->input->post()){
			$Code_Quot 		= urldecode($this->input->post('code'));
			$rows_Detail	= $this->db->get_where('quotation_detail_cancels',array('quotation_id'=>$Code_Quot))->result();
			$rows_Header	= $this->db->get_where('quotations',array('id'=>$Code_Quot))->result();
			
		}
		$Arr_Akses			= $this->Arr_Akses;
		$data = array(
			'title'			=> 'DETAIL CANCELLATION',
			'action'		=> 'view_detail_cancellation',
			'akses_menu'	=> $Arr_Akses,
			'rows_detail'	=> $rows_Detail,
			'rows_header'	=> $rows_Header
		);
		
		$this->load->view($this->folder.'/v_quotation_cancellation_preview',$data);
		
	}
	

	
	function save_quotation_cancel_proses(){
		$rows_Return	= array(
			'status'		=> 2,
			'pesan'			=> 'No Record was found...'
		);
		if($this->input->post()){
			//echo"<pre>";print_r($this->input->post());exit;
			
			$Created_By		= $this->session->userdata('siscal_userid');
			$Created_Date	= date('Y-m-d H:i:s');
			
			
			$Code_Quot		= $this->input->post('code_quotation');
			$detChosen		= $this->input->post('detPilih');
			$Reason_Open	= $this->input->post('cancel_reason');			
			$CodeTrans		= date('YmdHis');
			
			$this->db->trans_begin();
			$Pesan_Error	= '';
			
			if($detChosen){
				$intL	= 0;
				foreach($detChosen as $ketChosen=>$valChosen){
					$intL++;
					if(isset($valChosen['code_detail']) && !empty($valChosen['code_detail'])){
						$Code_Quot_Detail	= $valChosen['code_detail'];
						$Qty_Cancel			= $valChosen['qty'];
						
						$Code_Proc_Detail	= $Code_Quot_Detail.'-'.$CodeTrans;
						
						$rows_Quot_Detail	= $this->db->get_where('quotation_details',array('id'=>$Code_Quot_Detail,'quotation_id'=>$Code_Quot))->result();
						
						## UPDATE QUOTATION DETAIL ##
						$Upd_Quot_Detail		= "UPDATE quotation_details SET qty_so = qty_so + ".$Qty_Cancel." WHERE id = '".$Code_Quot_Detail."' AND quotation_id = '".$Code_Quot."'";
						$Has_Upd_Quot_Detail	= $this->db->query($Upd_Quot_Detail);
						if($Has_Upd_Quot_Detail !== TRUE){
							$Pesan_Error	= 'Error Update Quotation Detail';
						}
						
						## INSERT KE LOG ##
						$Ins_Log	= array(
							'id'					=> $Code_Proc_Detail,
							'quotation_detail_id'	=> $Code_Quot_Detail,
							'quotation_id'			=> $Code_Quot,
							'tool_id'				=> $rows_Quot_Detail[0]->tool_id,
							'tool_name'				=> $rows_Quot_Detail[0]->tool_name,
							'qty_cancel'			=> $Qty_Cancel,
							'reason'				=> $Reason_Open,
							'cancel_by'				=> $Created_By,
							'cancel_date'			=> $Created_Date,
							'flag_insitu'			=> 'Y'
						);
						
						$Has_Ins_Log		= $this->db->insert('quotation_detail_cancels',$Ins_Log);
						if($Has_Ins_Log !== TRUE){
							$Pesan_Error	= 'Error insert Quotation Detail Cancel';
						}
					}
				}
			}
			
			
			if ($this->db->trans_status() != TRUE || !empty($Pesan_Error)){
				$this->db->trans_rollback();
				$rows_Return		= array(
					'status'		=> 2,
					'pesan'			=> 'Save Process  Failed, please try again...'
				);
				history('Cancellation Quotation Detail Code '.$Code_Quot.' - '.$Pesan_Error);
			}else{
				$this->db->trans_commit();
				$rows_Return		= array(
					'status'		=> 1,
					'pesan'			=> 'Save process success. Thank you & have a nice day......'
				);
				history('Cancellation Quotation Detail Code '.$Code_Quot);
			}
				
			
		}
		echo json_encode($rows_Return);
	}
	
	
	function list_quotation_cancellation(){
		$Arr_Akses			= $this->Arr_Akses;
		if($Arr_Akses['read'] !='1'){
			$this->session->set_flashdata("alert_data", "<div class=\"alert alert-warning\" id=\"flash-message\">You Don't Have Right To Access This Page, Please Contact Your Administrator....</div>");
			redirect(site_url('Quot_insitu_cancel'));
		}
		
		$data = array(
			'title'			=> 'QUOTATION CANCELLATION',
			'action'		=> 'index',
			'akses_menu'	=> $Arr_Akses
		);
		history('View Quotation Cancellation');
		$this->load->view($this->folder.'/v_quotation_cancellation',$data);
	}
	
	function get_data_display_cancellation(){
		$Arr_Akses			= $this->Arr_Akses;
		
		$DateFr				= $this->input->post('datefr');
		$DateTl				= $this->input->post('datetl');
		
		$WHERE				= "1=1";
		if(!empty($DateFr) && !empty($DateTl)){
			if(!empty($WHERE))$WHERE	.=" AND ";
			$WHERE	.="(head_quot.datet BETWEEN '".$DateFr."' AND '".$DateTl."')";
		}
		
		$requestData	= $_REQUEST;
		
		$like_value     = $requestData['search']['value'];
        $column_order   = $requestData['order'][0]['column'];
        $column_dir     = $requestData['order'][0]['dir'];
        $limit_start    = $requestData['start'];
        $limit_length   = $requestData['length'];
		
		$columns_order_by = array(
			0 => 'head_quot.nomor',
			1 => 'head_quot.datet',
			2 => 'head_quot.customer_name',
			3 => 'head_quot.member_name',
			4 => 'head_quot.pono',
			5 => 'head_quot.podate'
		);
		
		
		
		if($like_value){
			if(!empty($WHERE))$WHERE	.=" AND ";
			$WHERE	.="(
						  head_quot.nomor LIKE '%".$this->db->escape_like_str($like_value)."%'
						  OR DATE_FORMAT(head_quot.datet, '%d %b %Y') LIKE '%".$this->db->escape_like_str($like_value)."%'
						  OR head_quot.customer_name LIKE '%".$this->db->escape_like_str($like_value)."%'
						  OR head_quot.member_name LIKE '%".$this->db->escape_like_str($like_value)."%'
						  OR head_quot.pono LIKE '%".$this->db->escape_like_str($like_value)."%'
						  OR DATE_FORMAT(head_quot.podate, '%d %b %Y') LIKE '%".$this->db->escape_like_str($like_value)."%'
						)";
		}
		
		
		$sql = "SELECT
					head_quot.id,
					head_quot.datet,
					head_quot.nomor,
					head_quot.pono,
					head_quot.podate,
					head_quot.customer_id,
					head_quot.customer_name,
					head_quot.member_id,
					head_quot.member_name,
					(@row:=@row+1) AS urut
				FROM
					quotation_detail_cancels detail_quot
					INNER JOIN quotations head_quot ON head_quot.id = detail_quot.quotation_id,
				(SELECT @row:=0) r 
				WHERE ".$WHERE."
				GROUP BY
					head_quot.id
				";
		//print_r($sql);exit();
		$fetch['totalData'] 	= $this->db->query($sql)->num_rows();
		$fetch['totalFiltered']	= $this->db->query($sql)->num_rows();

		

		$sql .= " ORDER BY head_quot.datet DESC,".$columns_order_by[$column_order]." ".$column_dir." ";
		$sql .= " LIMIT ".$limit_start." ,".$limit_length." ";

		$fetch['query'] = $this->db->query($sql);
		
		$totalData		= $fetch['totalData'];
		$totalFiltered	= $fetch['totalFiltered'];
		$query			= $fetch['query'];
		
		$data		= array();
        $urut1  	= 1;
        $urut2  	= 0;
		$Periode_Now= date('Y-m');
		$Tahun_Now	= date('Y');
		foreach($query->result_array() as $row)
		{
			$total_data     = $totalData;
            $start_dari     = $requestData['start'];
            $asc_desc       = $requestData['order'][0]['dir'];
            if($asc_desc == 'asc')
            {
                $nomor = $urut1 + $start_dari;
            }
            if($asc_desc == 'desc')
            {
                $nomor = ($total_data - $start_dari) - $urut2;
            }
			$Code_Quot		= $row['id'];
			$nestedData		=array();
			$intL			= 0;
            foreach($columns_order_by as $keyI=>$valI){
				$intL++;
				$Pecah_Kode		= explode('.',$valI);
				$Field_Cari		= $Pecah_Kode[1];
				$Nilai_Data		= $row[$Field_Cari];
				
				
				if($intL === 2 || $intL === 6){
					if(!empty($Nilai_Data) && $Nilai_Data !== '-'){
						$Nilai_Data	= date('d-m-Y',strtotime($Nilai_Data));
					}					
				}
				
				$nestedData[] = $Nilai_Data;
			}  
			$Template		='<button type="button" onClick="return ActionPreview({code:\''.$Code_Quot.'\',title:\'DETAIL CANCELLATION\',action:\'view_detail_cancellation\'});" class="btn btn-sm bg-navy-active" title="DETAIL CANCELLATION"> <i class="fa fa-search"></i> </button>';
			
			$nestedData[]	= $Template;
			
			
			$data[] = $nestedData;
            $urut1++;
            $urut2++;
		}

		$json_data = array(
			"draw"            => intval( $requestData['draw'] ),  
			"recordsTotal"    => intval( $totalData ),  
			"recordsFiltered" => intval( $totalFiltered ), 
			"data"            => $data
		);

		echo json_encode($json_data);
	}
	
}