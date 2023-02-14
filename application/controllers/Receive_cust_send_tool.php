<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Receive_cust_send_tool extends CI_Controller { 
	public function __construct(){
        parent::__construct();	
		
		$this->load->database();
        if(!$this->session->userdata('isSISCALlogin')){
			redirect('login');
		}
		$this->load->model('master_model');
		$controller			= ucfirst(strtolower($this->uri->segment(1)));
		$this->Arr_Akses	= getAcccesmenu($controller);
		
		$this->folder		= 'Warehouses';
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
			'title'			=> 'RECEIVE TOOLS - SEND BY CUSTOMER',
			'action'		=> 'index',
			'akses_menu'	=> $Arr_Akses
		);
		history('View List Receive Tools - Send By Customer');
		$this->load->view($this->folder.'/v_receive_cust_send',$data);
	}
	function get_data_display(){
		$Arr_Akses			= $this->Arr_Akses;
		
		$WHERE				= "header.`status` = 'REC'
							AND (detail.qty - detail.qty_so - detail.qty_driver) > 0
							AND detail.flag_insitu = 'N'";
		
		$requestData	= $_REQUEST;
		
		$like_value     = $requestData['search']['value'];
        $column_order   = $requestData['order'][0]['column'];
        $column_dir     = $requestData['order'][0]['dir'];
        $limit_start    = $requestData['start'];
        $limit_length   = $requestData['length'];
		
		$columns_order_by = array(
			0 => 'header.nomor',
			1 => 'header.datet',
			2 => 'header.customer_name',
			3 => 'header.pono',
			4 => 'header.podate',
			5 => 'header.member_name'
		);
		
		
		
		if($like_value){
			if(!empty($WHERE))$WHERE	.=" AND ";
			$WHERE	.="(
						  header.nomor LIKE '%".$this->db->escape_like_str($like_value)."%'
						  OR DATE_FORMAT(header.datet, '%d %b %Y') LIKE '%".$this->db->escape_like_str($like_value)."%'
						  OR header.member_name LIKE '%".$this->db->escape_like_str($like_value)."%'
						  OR header.customer_name LIKE '%".$this->db->escape_like_str($like_value)."%'
						  OR header.pono LIKE '%".$this->db->escape_like_str($like_value)."%'
						   OR DATE_FORMAT(header.podate, '%d %b %Y') LIKE '%".$this->db->escape_like_str($like_value)."%'
						)";
		}
		
		
		$sql = "SELECT
					header.id,
					header.nomor,
					header.datet,
					header.customer_id,
					header.customer_name, 
					header.podate,
					header.pono,
					header.grand_tot,
					header.member_id,
					header.member_name,
					header.project_no,
					header.address,
					(@row:=@row+1) AS urut
				FROM
					quotations header
				INNER JOIN quotation_details detail ON header.id = detail.quotation_id,
				(SELECT @row:=0) r 
				WHERE ".$WHERE."
				GROUP BY 
					header.id";
		//print_r($sql);exit();
		$fetch['totalData'] 	= $this->db->query($sql)->num_rows();
		$fetch['totalFiltered']	= $this->db->query($sql)->num_rows();

		

		$sql .= " ORDER BY header.datet DESC,".$columns_order_by[$column_order]." ".$column_dir." ";
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
			$Nomor_Quot		= $row['nomor'];
			$Date_Quot		= date('d-m-Y',strtotime($row['datet']));
			$Code_Sales		= $row['member_id'];
			$Name_Sales		= $row['member_name'];
			$Code_Customer	= $row['customer_id'];
			$Name_Customer	= $row['customer_name'];
			$PO_No			= $row['pono'];
			$PO_Date		= date('d-m-Y',strtotime($row['podate']));
			
			$Template		='';
			if($Arr_Akses['create'] == '1' || $Arr_Akses['update'] == '1'){
				$Template		="<a href='".site_url('Receive_cust_send_tool/receive_process?quotation='.urlencode($Code_Quot))."&code_process=' class='btn btn-sm bg-navy-active' title='RECEIVE TOOL - SEND BY CUSTOMER'> <i class='fa fa-long-arrow-right'></i> </a>";
			}
			
			if($Arr_Akses['delete'] == '1'){
				$Template		.="&nbsp;&nbsp;<a href='".site_url('Receive_cust_send_tool/cancel_receive_process?quotation='.urlencode($Code_Quot))."' class='btn btn-sm bg-orange-active' title='CANCEL RECEIVE TOOL - SEND BY CUSTOMER'> <i class='fa fa-trash-o'></i> </a>";
			}
			
			
			
			$nestedData		= array();
			$nestedData[]	= $Nomor_Quot;
			$nestedData[]	= $Date_Quot;
			$nestedData[]	= $Name_Customer;
			$nestedData[]	= $PO_No;
			$nestedData[]	= $PO_Date;
			$nestedData[]	= $Name_Sales;
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
	
	function receive_process(){
		$OK_Proses	= 0;
		$rows_Header	= $rows_Detail = $rows_Receive = $rows_Rec_Detail =  array();
		$Code_Process	= '';
		if($this->input->get()){
			$Code_Quot		= urldecode($this->input->get('quotation'));
			$Code_Process	= urldecode($this->input->get('code_process'));
			
			$rows_Header	= $this->db->get_where('quotations',array('id'=>$Code_Quot))->row();
			$rows_Detail	= $this->db->get_where('quotation_details',array('quotation_id'=>$Code_Quot,'(qty - qty_so - qty_driver) >'=>0,'flag_insitu'=>'N'))->result();
			
			if($Code_Process){
				$rows_Receive	 = $this->db->get_where('quotation_header_receives',array('id'=>$Code_Process))->row();
				$rows_Rec_Detail = $this->db->get_where('quotation_detail_receives',array('quotation_header_receive_id'=>$Code_Process))->result();
			}
			
			if(!empty($rows_Detail) || !empty($Code_Process)){
				$OK_Proses	= 1;
			}
		}
		
		if($OK_Proses == 1){
			$data = array(
				'title'			=> 'RECEIVE TOOL PROCESS - SEND BY CUSTOMER',
				'action'		=> 'receive_process',
				'akses_menu'	=> $this->Arr_Akses,
				'rows_header'	=> $rows_Header,
				'rows_detail'	=> $rows_Detail,
				'rows_rec'		=> $rows_Receive,
				'rows_rec_det'	=> $rows_Rec_Detail,
				'code_process'	=> $Code_Process
			);
			
			$this->load->view($this->folder.'/v_receive_cust_send_process',$data);
		}else{
			$this->session->set_flashdata("alert_data", "<div class=\"alert alert-warning\" id=\"flash-message\">No record was found to process....</div>");
			redirect(site_url('Receive_cust_send_tool'));
		}
	}
	
	function cancel_receive_process(){
		$OK_Proses	= 0;
		$rows_Header	= $rows_Detail =  array();
		if($this->input->get()){
			$Code_Quot		= urldecode($this->input->get('quotation'));
			
			$rows_Header	= $this->db->get_where('quotations',array('id'=>$Code_Quot))->row();
			$rows_Detail	= $this->db->get_where('quotation_details',array('quotation_id'=>$Code_Quot,'(qty - qty_so - qty_driver) >'=>0,'flag_insitu'=>'N'))->result();
			
			
			
			if($rows_Detail){
				$OK_Proses	= 1;
			}
		}
		
		if($OK_Proses == 1){
			$data = array(
				'title'			=> 'CANCELLATION PO OUTSTANDING RECEIVE',
				'action'		=> 'cancel_receive_process',
				'akses_menu'	=> $this->Arr_Akses,
				'rows_header'	=> $rows_Header,
				'rows_detail'	=> $rows_Detail
			);
			
			$this->load->view($this->folder.'/v_receive_cust_send_cancel',$data);
		}else{
			$this->session->set_flashdata("alert_data", "<div class=\"alert alert-warning\" id=\"flash-message\">No record was found to process....</div>");
			redirect(site_url('Receive_cust_send_tool'));
		}
		
	}
	
	function save_cancel_receive_process(){
		$rows_Return	= array(
			'status'		=> 2,
			'pesan'			=> 'No Record was found...'
		);
		if($this->input->post()){
			//echo"<pre>";print_r($this->input->post());exit;
			
			$Created_By		= $this->session->userdata('siscal_userid');
			$Created_Name	= $this->session->userdata('siscal_username');
			$Created_Date	= date('Y-m-d H:i:s');
			
			$Code_Quot		= $this->input->post('code_quot');
			$Cancel_Reason	= strtoupper($this->input->post('cancel_reason'));
			$detDetail		= $this->input->post('detDetail');
			

			
			
			
			
			$this->db->trans_begin();
			$Pesan_Error	= '';
			
			if($detDetail){
				foreach($detDetail as $keyDet=>$valDetail){
					if(isset($valDetail['code_process']) && !empty($valDetail['code_process'])){
						$Code_Detail	= $valDetail['code_process'];
						$Qty_Cancel		= $valDetail['qty'];
						$Code_Cancel	= $Code_Detail.'-'.date('YmdHis');
						$rows_Detail	= $this->db->get_where('quotation_details',array('id'=>$Code_Detail))->row();
						
						$Ins_Cancel		= array(
							'id'					=> $Code_Cancel,
							'quotation_detail_id'	=> $Code_Detail,
							'quotation_id'			=> $Code_Quot,
							'tool_id'				=> $rows_Detail->tool_id,
							'tool_name'				=> $rows_Detail->cust_tool,
							'qty_cancel'			=> $Qty_Cancel,
							'reason'				=> $Cancel_Reason,
							'cancel_by'				=> $Created_By,
							'cancel_date'			=> $Created_Date,
							'flag_insitu'			=> 'N'
						);
						
						$Has_Ins_Quot_Cancel		= $this->db->insert('quotation_detail_cancels',$Ins_Cancel);
						if($Has_Ins_Quot_Cancel !== TRUE){
							$Pesan_Error	= 'Error Insert Quotation Cancel';
						}
						
						$Upd_Quot_Detail	= "UPDATE quotation_details SET qty_so = qty_so +  ".$Qty_Cancel." WHERE id ='".$Code_Detail."'";
						$Has_Upd_Quot_Detail= $this->db->query($Upd_Quot_Detail);
						if($Has_Upd_Quot_Detail !== TRUE){
							$Pesan_Error	= 'Error Update Quotation Detail';
						}
						
					}
				}
			}
			
			
			
			
			if ($this->db->trans_status() != TRUE || !empty($Pesan_Error)){
				$this->db->trans_rollback();
				$rows_Return		= array(
					'status'		=> 2,
					'pesan'			=> 'Save Process  Failed, '.$Pesan_Error
				);
				history('Cancel PO Outstanding Receive -  '.$Code_Quot.' - '.$Pesan_Error);
			}else{
				$this->db->trans_commit();
				$rows_Return		= array(
					'status'		=> 1,
					'pesan'			=> 'Save process success. Thank you & have a nice day......'
				);
				history('Cancel PO Outstanding Receive -  '.$Code_Quot);
			}
			
		}
		echo json_encode($rows_Return);
	}
	
	function process_receive_tool_cust_send(){
		$rows_header	= $rows_detail = array();
		$Code_Process	= '';
		$Flag_New		= 'N';
		if($this->input->post()){
			$Code_Quot_Detail	= urldecode($this->input->post('code_quot_detail'));
			$Code_Process		= urldecode($this->input->post('code_process'));
			$Flag_New			= urldecode($this->input->post('flag_new'));
			
			$rows_Detail	= $this->db->get_where('quotation_details',array('id'=>$Code_Quot_Detail,'(qty - qty_so - qty_driver) >'=>0))->row();
			$rows_Header	= $this->db->get_where('quotations',array('id'=>$rows_Detail->quotation_id))->row();
			
		}
		
		$Arr_Akses			= $this->Arr_Akses;
		$data = array(
			'title'			=> 'TOOL RECEIVE PROCESS',
			'action'		=> 'process_receive_tool_cust_send',
			'akses_menu'	=> $Arr_Akses,
			'rows_header'	=> $rows_Header,
			'rows_detail'	=> $rows_Detail,
			'code_trans'	=> $Code_Process,
			'code_new'		=> $Flag_New
		);
		
		$this->load->view($this->folder.'/v_receive_cust_send_update',$data);
	}
	
	function ajax_ambil_kamera(){
		$kategori		= '';
		if($this->input->get()){
			$kategori		= $this->input->get('kategori');
		}
		$data				= array(
			'kategori'	=> $kategori
		);
		$this->load->view($this->folder . '/v_ajax_ambil_kamera', $data);
	}
	
	/* ------------------------------------------------------------------
	|	PREVIEW FILE
	|  ------------------------------------------------------------------
	*/
	function ambil_file_gambar(){
		(!$this->input->is_ajax_request() ? show_404() : '');
		
		$Category		= $this->input->post('kategori');
		$File_Image		= '';
		if($this->input->post('file_image')){
			$File_Image	= $this->input->post('file_image');
		}
		$Ext_File		= getExtension($File_Image);
		//echo"<pre>";print_r($Ext_File);exit;
		$Judul			= '';
		$Path_Image		= '';
		
		if(strtolower($Category) == 'certificate'){
			$Judul		= 'Certificate';
			$Path_Image	= $this->file_attachement.'Entries/preview_file/sertifikat/'.$File_Image;
			
		}
		
		$this->data = array(
			'title'			=> $Judul,
			'path_image'	=> $Path_Image,
			'file_type'		=> $Ext_File,
			'file_name'		=> $File_Image
		);
		//echo "<pre>";print_r($this->data);exit;
        $this->load->view($this->ajax_contents . 'v_preview_file', $this->data);
	}
	
	function GetDetailSentralCode(){
		$response	= array(
			'code'			=> '',
			'no_identify'	=> '',
			'no_serial'		=> '',
			'merk'			=> '',
			'tipe'			=> ''	
		);
		
		if($this->input->post()){
			$Code_Cari	= $this->input->post('code_find');
			$Code_Cust	= $this->input->post('customer');
			$WHERE_Code	= array(
				'sentral_tool_code'	=> $Code_Cari,
				'customer_id'		=> $Code_Cust
			);
			
			$rows_Find	= $this->db->get_where('sentral_customer_tools',$WHERE_Code)->row();
			if($rows_Find){
				$response	= array(
					'code'			=> $rows_Find->sentral_tool_code,
					'no_identify'	=> $rows_Find->no_identifikasi,
					'no_serial'		=> $rows_Find->no_serial_number,
					'merk'			=> $rows_Find->merk,
					'tipe'			=> $rows_Find->tool_type
				);
			}
		}
		echo json_encode($response);
	}
	
	
	function save_receive_cust_send_process(){
		$rows_Return	= array(
			'status'		=> 2,
			'pesan'			=> 'No Record was found...'
		);
		if($this->input->post()){
			//echo"<pre>";print_r($this->input->post());exit;
			
			$Created_By		= $this->session->userdata('siscal_userid');
			$Created_Name	= $this->session->userdata('siscal_username');
			$Created_Date	= date('Y-m-d H:i:s');
			
			
			$Code_Receive	= $this->input->post('code_trans');
			$Code_QuotDet	= $this->input->post('code_detail');
			$Code_Quot		= $this->input->post('quotation_id');
			$Code_Cust		= $this->input->post('cust_id_modal');
			$Name_Cust		= $this->input->post('cust_modal');
			
			$Code_Sentral	= $this->input->post('code_sentral_tool');
			$Code_Tool		= $this->input->post('tool_id');
			$Name_Tool		= $this->input->post('tool_name');
			$Code_Identify	= $this->input->post('no_identifikasi');
			$Code_Serial	= $this->input->post('no_serial_number');
			$Code_Merk		= strtoupper($this->input->post('merk_alat'));
			$Code_Type		= strtoupper($this->input->post('tipe_alat'));
			$Notes			= strtoupper($this->input->post('descr'));
			
			$Notes_Front		= strtoupper($this->input->post('notes_depan'));
			$Notes_Back			= strtoupper($this->input->post('notes_back'));
			$Notes_Right		= strtoupper($this->input->post('notes_kanan'));
			$Notes_Left			= strtoupper($this->input->post('notes_kiri'));
			
			$this->db->trans_begin();
			$Pesan_Error	= '';
			
			## PROSES CHECK EXISTING ##
			$Find_Exist		= $this->db->get_where('quotation_header_receives',array('id'=>$Code_Receive))->num_rows();
			if($Find_Exist <= 0){
				## INSERT RECEIVE HEADER ##
				$Nomor_Urut	= 1;
				$Query_Urut	= "SELECT
									MAX(
										CAST(
											SUBSTRING_INDEX(nomor, '/', 1) AS UNSIGNED
										)
									) AS urut
								FROM
									quotation_header_receives
								WHERE
									datet LIKE '".date('Y-m-')."%'";
				$rows_Urut	= $this->db->query($Query_Urut)->row();
				if($rows_Urut){
					$Nomor_Urut	= intval($rows_Urut->urut) + 1;
				}
				$Lable_Urut		= sprintf('%05d',$Nomor_Urut);
				if($Nomor_Urut > 99999){
					$Lable_Urut		= $Nomor_Urut;
				}
				
				$Nomor_Receive	= $Lable_Urut.'/REC-V3/'.date('m').'/'.date('Y');
				$Ins_Receive	= array(
						'id'				=> $Code_Receive,
						'nomor'				=> $Nomor_Receive,
						'datet'				=> date('Y-m-d'),
						'quotation_id'		=> $Code_Quot,
						'customer_id'		=> $Code_Cust,
						'customer_name'		=> $Name_Cust,
						'rec_category'		=> 'CUSTOMER',
						'rec_by'			=> $Created_Name,
						'created_date'		=> $Created_Date,
						'created_by'		=> $Created_By
				);
				$Has_Ins_REc_Head	= $this->db->insert('quotation_header_receives',$Ins_Receive);
				if($Has_Ins_REc_Head !== TRUE){
					$Pesan_Error	= 'Error Insert Receive Header';
				}				
			}
			
			if(empty($Code_Sentral)){
				$Urut_Sentral	= 1;
				$Query_Sentral	= "SELECT
										MAX(
											CAST(
												SUBSTRING_INDEX(sentral_tool_code, '-', - 1) AS UNSIGNED
											)
										) AS urut
									FROM
										sentral_customer_tools
									WHERE
										customer_id = '".$Code_Cust."'";
				$rows_Sentral	= $this->db->query($Query_Sentral)->row();
				if($rows_Sentral){
					$Urut_Sentral	= intval($rows_Sentral->urut) + 1;
				}
				
				$Lable_Sentral	= sprintf('%04d',$Urut_Sentral);
				if($Urut_Sentral > 9999){
					$Lable_Sentral	= $Urut_Sentral;
				}
				
				$Code_Sentral	= $Code_Cust.'-CAL-'.$Lable_Sentral;
				
				$Ins_Sentral	= array(
					'sentral_tool_code'		=> $Code_Sentral,
					'customer_id'			=> $Code_Cust,
					'customer_name'			=> $Name_Cust,
					'tool_id'				=> $Code_Tool,
					'tool_name'				=> $Name_Tool,
					'merk'					=> $Code_Merk,
					'tool_type'				=> $Code_Type,
					'no_identifikasi'		=> $Code_Identify,
					'no_serial_number'		=> $Code_Serial,
					'descr'					=> $Notes,
					'created_by'			=> $Created_By,
					'created_date'			=> $Created_Date
				);
				$Has_Ins_Sentral	= $this->db->insert('sentral_customer_tools',$Ins_Sentral);
				if($Has_Ins_Sentral !== TRUE){
					$Pesan_Error	= 'Error Insert Sentral Customer Tool';
				}
			}
			
			$Urut_Detail	= 1;
			$Query_Urut_Det	= "SELECT
								MAX(
									CAST(
										SUBSTRING_INDEX(id, '-', -1) AS UNSIGNED
									)
								) AS urut
							FROM
								quotation_detail_receives
							WHERE
								quotation_header_receive_id = '".$Code_Receive."'";
			$rows_Urut_Det	= $this->db->query($Query_Urut_Det)->row();
			if($rows_Urut_Det){
				$Urut_Detail	= intval($rows_Urut_Det->urut) + 1;
			}
			$Lable_Urut_Det		= sprintf('%04d',$Urut_Detail);
			if($Urut_Detail > 9999){
				$Lable_Urut_Det		= $Urut_Detail;
			}
			
			$Qty_Receive	= 1;
			$Code_Detail	= $Code_Receive.'-'.$Lable_Urut_Det;
			$Ins_Detail		= array(
				'id'							=> $Code_Detail,
				'quotation_header_receive_id'	=> $Code_Receive,
				'quotation_detail_id'			=> $Code_QuotDet,
				'quotation_id'					=> $Code_Quot,
				'tool_id'						=> $Code_Tool,
				'tool_name'						=> $Name_Tool,
				'qty_rec'						=> $Qty_Receive,
				'descr'							=> $Notes,
				'rec_category'					=> 'CUSTOMER',
				'rec_date'						=> date('Y-m-d'),
				'receive_by'					=> $Created_By,
				'receive_proses'				=> $Created_Date,
				'sentral_code_tool'				=> $Code_Sentral
			);
			
			$Has_Ins_Detail	= $this->db->insert('quotation_detail_receives',$Ins_Detail);
			if($Has_Ins_Detail !== TRUE){
				$Pesan_Error	= 'Error Insert Receive Detail';
			}
			
			$Upd_Quot_Detail	= "UPDATE quotation_details SET qty_so = qty_so + ".$Qty_Receive." WHERE id = '".$Code_QuotDet."'";
			$Has_Upd_Quot_Detail= $this->db->query($Upd_Quot_Detail);
			if($Has_Upd_Quot_Detail !== TRUE){
				$Pesan_Error	= 'Error Update Quotation Detail';
			}
			
			$Upd_Quot			= "UPDATE quotations SET flag_so = 'Y', modified_date = '".$Created_Date."', modified_by = '".$Created_By."' WHERE id ='".$Code_Quot."'";
			$Has_Upd_Quot		= $this->db->query($Upd_Quot);
			if($Has_Upd_Quot !== TRUE){
				$Pesan_Error	= 'Error Update Quotation Header';
			}
			
			## UPLOAD FILE ##
			$Code_Rec_Tools	= $Code_Detail;
			$Code_File		= $Code_Rec_Tools;
			
			$Path_Loc       = $this->config->item('location_file').'receive_tool/';
			$Pict_Inc_Front	= $Pic_Inc_Back ='';
			
			## IMAGE FRONT ##
			if($this->input->post('pic_webcam_depan')){
				
				$img_depan			= $this->input->post('pic_webcam_depan');
				$image_parts    	= explode(";base64,", $img_depan);
				$image_type_aux 	= explode("image/", $image_parts[0]);
				$image_type     	= $image_type_aux[1];              
				$image_base64   	= base64_decode($image_parts[1]);
				$Pict_Inc_Front   	= "FR-".$Code_File.".".$image_type;
				
				if (file_exists($Path_Loc.$Pict_Inc_Front)) {
					chmod($Path_Loc.$Pict_Inc_Front, 0777);
					unlink($Path_Loc.$Pict_Inc_Front);
				}
				file_put_contents($Path_Loc.$Pict_Inc_Front, $image_base64);
				
				$Ins_Image_Front	= array(
					'driver_detail_receive'	=> $Code_Rec_Tools,
					'file_name'				=> $Pict_Inc_Front,
					'notes'					=> $Notes_Front,
					'file_type'				=> $image_type
				);
				$Has_Ins_Image_FR	= $this->db->insert('quotation_driver_detail_receive_file',$Ins_Image_Front);
				if($Has_Ins_Image_FR !== TRUE){
					$Pesan_Error	= 'Error Insert Driver Receive Image - Front';
				}
			}
			
			if($_FILES && !empty($_FILES['files_depan']['name']) && $_FILES['files_depan']['name'] != ''){
				$nama_image 	= $_FILES['files_depan']['name'];
				$type_iamge		= $_FILES['files_depan']['type'];
				$tmp_image 		= $_FILES['files_depan']['tmp_name'];
				$error_image	= $_FILES['files_depan']['error'];
				$size_image 	= $_FILES['files_depan']['size'];
				
				$cekExtensi 	= strtolower(getExtension($nama_image));
				$Pict_Inc_Front = "FR-".$Code_File.".".$cekExtensi;
				$Type_File		= $cekExtensi;
				
				
				$Pesan_Error	= '';
				if($error_image == '1'){
					
					$Pesan_Error	= 'File Size Exceeds The Maximum Limit';
					
				}else{					
					$Data_File		= array(
						'name'			=> $nama_image,
						'type'			=> $type_iamge,
						'tmp_name'		=> $tmp_image,
						'error'			=> $error_image,
						'size'			=> $size_image
					);
					$Del_Upload			= delFile_Kalibrasi('receive_tool',$Pict_Inc_Front);
					$Has_Upload 		= ImageResizes_Kalibrasi($Data_File,'receive_tool',"FR-".$Code_File);
					
					$Ins_Image_Front	= array(
						'driver_detail_receive'	=> $Code_Rec_Tools,
						'file_name'				=> $Pict_Inc_Front,
						'notes'					=> $Notes_Front,
						'file_type'				=> $Type_File
					);
					$Has_Ins_Image_FR	= $this->db->insert('quotation_driver_detail_receive_file',$Ins_Image_Front);
					if($Has_Ins_Image_FR !== TRUE){
						$Pesan_Error	= 'Error Insert Driver Receive Image - Front';
					}
					
				}					
			}
			
			## END FRONT IMAGE ##
			
			## IMAGE BACK ##
			if($this->input->post('pic_webcam_back')){
				
				$img_back			= $this->input->post('pic_webcam_back');
				$image_parts    	= explode(";base64,", $img_back);
				$image_type_aux 	= explode("image/", $image_parts[0]);
				$image_type     	= $image_type_aux[1];              
				$image_base64   	= base64_decode($image_parts[1]);
				$Pict_Inc_Back   	= "BC-".$Code_File.".".$image_type;
				
				if (file_exists($Path_Loc.$Pict_Inc_Back)) {
					chmod($Path_Loc.$Pict_Inc_Back, 0777);
					unlink($Path_Loc.$Pict_Inc_Back);
				}
				file_put_contents($Path_Loc.$Pict_Inc_Back, $image_base64);
				
				$Ins_Image_Back	= array(
					'driver_detail_receive'	=> $Code_Rec_Tools,
					'file_name'				=> $Pict_Inc_Back,
					'notes'					=> $Notes_Back,
					'file_type'				=> $image_type
				);
				$Has_Ins_Image_BC	= $this->db->insert('quotation_driver_detail_receive_file',$Ins_Image_Back);
				if($Has_Ins_Image_BC !== TRUE){
					$Pesan_Error	= 'Error Insert Driver Receive Image - Back';
				}
			}
			
			if($_FILES && !empty($_FILES['files_back']['name']) && $_FILES['files_back']['name'] != ''){
				$nama_image 	= $_FILES['files_back']['name'];
				$type_iamge		= $_FILES['files_back']['type'];
				$tmp_image 		= $_FILES['files_back']['tmp_name'];
				$error_image	= $_FILES['files_back']['error'];
				$size_image 	= $_FILES['files_back']['size'];
				
				$cekExtensi 	= strtolower(getExtension($nama_image));
				$Pict_Inc_Back  = "BC-".$Code_File.".".$cekExtensi;
				$Type_File		= $cekExtensi;
				
				
				$Pesan_Error	= '';
				if($error_image == '1'){
					
					$Pesan_Error	= 'File Size Exceeds The Maximum Limit';
					
				}else{					
					$Data_File		= array(
						'name'			=> $nama_image,
						'type'			=> $type_iamge,
						'tmp_name'		=> $tmp_image,
						'error'			=> $error_image,
						'size'			=> $size_image
					);
					$Del_Upload			= delFile_Kalibrasi('receive_tool',$Pict_Inc_Back);
					$Has_Upload 		= ImageResizes_Kalibrasi($Data_File,'receive_tool',"BC-".$Code_File);
					
					$Ins_Image_Back	= array(
						'driver_detail_receive'	=> $Code_Rec_Tools,
						'file_name'				=> $Pict_Inc_Back,
						'notes'					=> $Notes_Back,
						'file_type'				=> $Type_File
					);
					$Has_Ins_Image_BC	= $this->db->insert('quotation_driver_detail_receive_file',$Ins_Image_Back);
					if($Has_Ins_Image_BC !== TRUE){
						$Pesan_Error	= 'Error Insert Driver Receive Image - Back';
					}
					
				}					
			}				
			## END BACK IMAGE ##
			
			## IMAGE RIGHT SIDE ##				
			if($this->input->post('pic_webcam_kanan')){
				
				$img_Kanan			= $this->input->post('pic_webcam_kanan');
				$image_parts    	= explode(";base64,", $img_Kanan);
				$image_type_aux 	= explode("image/", $image_parts[0]);
				$image_type     	= $image_type_aux[1];              
				$image_base64   	= base64_decode($image_parts[1]);
				$Pict_Inc_Right   	= "RS-".$Code_File.".".$image_type;
				
				if (file_exists($Path_Loc.$Pict_Inc_Right)) {
					chmod($Path_Loc.$Pict_Inc_Right, 0777);
					unlink($Path_Loc.$Pict_Inc_Right);
				}
				file_put_contents($Path_Loc.$Pict_Inc_Right, $image_base64);
				
				$Ins_Image_Right	= array(
					'driver_detail_receive'	=> $Code_Rec_Tools,
					'file_name'				=> $Pict_Inc_Right,
					'notes'					=> $Notes_Right,
					'file_type'				=> $image_type
				);
				$Has_Ins_Image_RS	= $this->db->insert('quotation_driver_detail_receive_file',$Ins_Image_Right);
				if($Has_Ins_Image_RS !== TRUE){
					$Pesan_Error	= 'Error Insert Driver Receive Image - Right Side';
				}
			}
			
			if($_FILES && !empty($_FILES['files_kanan']['name']) && $_FILES['files_kanan']['name'] != ''){
				$nama_image 	= $_FILES['files_kanan']['name'];
				$type_iamge		= $_FILES['files_kanan']['type'];
				$tmp_image 		= $_FILES['files_kanan']['tmp_name'];
				$error_image	= $_FILES['files_kanan']['error'];
				$size_image 	= $_FILES['files_kanan']['size'];
				
				$cekExtensi 	= strtolower(getExtension($nama_image));
				$Pict_Inc_Right = "RS-".$Code_File.".".$cekExtensi;
				$Type_File		= $cekExtensi;
				
				
				$Pesan_Error	= '';
				if($error_image == '1'){
					
					$Pesan_Error	= 'File Size Exceeds The Maximum Limit';
					
				}else{					
					$Data_File		= array(
						'name'			=> $nama_image,
						'type'			=> $type_iamge,
						'tmp_name'		=> $tmp_image,
						'error'			=> $error_image,
						'size'			=> $size_image
					);
					$Del_Upload			= delFile_Kalibrasi('receive_tool',$Pict_Inc_Right);
					$Has_Upload 		= ImageResizes_Kalibrasi($Data_File,'receive_tool',"RS-".$Code_File);
					
					$Ins_Image_Right	= array(
						'driver_detail_receive'	=> $Code_Rec_Tools,
						'file_name'				=> $Pict_Inc_Right,
						'notes'					=> $Notes_Right,
						'file_type'				=> $Type_File
					);
					$Has_Ins_Image_RS	= $this->db->insert('quotation_driver_detail_receive_file',$Ins_Image_Right);
					if($Has_Ins_Image_RS !== TRUE){
						$Pesan_Error	= 'Error Insert Driver Receive Image - Right Side';
					}
					
				}					
			}				
			## END RIGHT IMAGE ##
			
			## IMAGE LEFT SIDE ##				
			if($this->input->post('pic_webcam_kiri')){
				
				$img_Left			= $this->input->post('pic_webcam_kiri');
				$image_parts    	= explode(";base64,", $img_Left);
				$image_type_aux 	= explode("image/", $image_parts[0]);
				$image_type     	= $image_type_aux[1];              
				$image_base64   	= base64_decode($image_parts[1]);
				$Pict_Inc_Left   	= "LS-".$Code_File.".".$image_type;
				
				if (file_exists($Path_Loc.$Pict_Inc_Left)) {
					chmod($Path_Loc.$Pict_Inc_Left, 0777);
					unlink($Path_Loc.$Pict_Inc_Left);
				}
				file_put_contents($Path_Loc.$Pict_Inc_Left, $image_base64);
				
				$Ins_Image_Left	= array(
					'driver_detail_receive'	=> $Code_Rec_Tools,
					'file_name'				=> $Pict_Inc_Left,
					'notes'					=> $Notes_Left,
					'file_type'				=> $image_type
				);
				$Has_Ins_Image_LS	= $this->db->insert('quotation_driver_detail_receive_file',$Ins_Image_Left);
				if($Has_Ins_Image_LS !== TRUE){
					$Pesan_Error	= 'Error Insert Driver Receive Image - Left Side';
				}
			}
			if($_FILES && !empty($_FILES['files_kiri']['name']) && $_FILES['files_kiri']['name'] != ''){
				$nama_image 	= $_FILES['files_kiri']['name'];
				$type_iamge		= $_FILES['files_kiri']['type'];
				$tmp_image 		= $_FILES['files_kiri']['tmp_name'];
				$error_image	= $_FILES['files_kiri']['error'];
				$size_image 	= $_FILES['files_kiri']['size'];
				
				$cekExtensi 	= strtolower(getExtension($nama_image));
				$Pict_Inc_Left 	= "LS-".$Code_File.".".$cekExtensi;
				$Type_File		= $cekExtensi;
				
				
				$Pesan_Error	= '';
				if($error_image == '1'){
					
					$Pesan_Error	= 'File Size Exceeds The Maximum Limit';
					
				}else{					
					$Data_File		= array(
						'name'			=> $nama_image,
						'type'			=> $type_iamge,
						'tmp_name'		=> $tmp_image,
						'error'			=> $error_image,
						'size'			=> $size_image
					);
					$Del_Upload			= delFile_Kalibrasi('receive_tool',$Pict_Inc_Left);
					$Has_Upload 		= ImageResizes_Kalibrasi($Data_File,'receive_tool',"LS-".$Code_File);
					
					$Ins_Image_Left	= array(
						'driver_detail_receive'	=> $Code_Rec_Tools,
						'file_name'				=> $Pict_Inc_Left,
						'notes'					=> $Notes_Left,
						'file_type'				=> $Type_File
					);
					$Has_Ins_Image_LS	= $this->db->insert('quotation_driver_detail_receive_file',$Ins_Image_Left);
					if($Has_Ins_Image_LS !== TRUE){
						$Pesan_Error	= 'Error Insert Driver Receive Image - Left Side';
					}
					
				}					
			}				
			## END LEFT IMAGE ##
			
			
			
			if ($this->db->trans_status() != TRUE || !empty($Pesan_Error)){
				$this->db->trans_rollback();
				$rows_Return		= array(
					'status'		=> 2,
					'pesan'			=> 'Save Process  Failed, '.$Pesan_Error
				);
				history('Receive Tool Process - Send By Customer '.$Code_Receive.' - '.$Code_Detail.' - '.$Pesan_Error);
			}else{
				$this->db->trans_commit();
				$rows_Return		= array(
					'status'		=> 1,
					'pesan'			=> 'Save process success. Thank you & have a nice day......'
				);
				history('Receive Tool Process - Send By Customer '.$Code_Receive.' - '.$Code_Detail);
			}
			
		}
		echo json_encode($rows_Return);
	}
	
	function preview_receive_tool_cust_send(){
		$rows_Rec_Head	= $rows_Rec_Detail = $rows_Quot = $rows_Quot_Det	= $rows_Sentral = array();
		if($this->input->post()){
			$Code_Detail	= urldecode($this->input->post('code_rec_detail'));
			$Code_Receive	= urldecode($this->input->post('code_process'));
			$rows_Rec_Head	= $this->db->get_where('quotation_header_receives',array('id'=>$Code_Receive))->row();
			$rows_Rec_Detail= $this->db->get_where('quotation_detail_receives',array('id'=>$Code_Detail))->row();
			$rows_Quot		= $this->db->get_where('quotations',array('id'=>$rows_Rec_Head->quotation_id))->row();
			$rows_Quot_Det	= $this->db->get_where('quotation_details',array('id'=>$rows_Rec_Detail->quotation_detail_id))->row();
			$rows_Sentral	= $this->db->get_where('sentral_customer_tools',array('sentral_tool_code'=>$rows_Rec_Detail->sentral_code_tool))->row();
		}
		
		$data = array(
			'title'			=> 'DETAIL RECEIVE TOOLS - SEND BY CUSTOMER',
			'action'		=> 'preview_receive_tool_cust_send',
			'rows_rec'		=> $rows_Rec_Head,
			'rows_rec_det'	=> $rows_Rec_Detail,
			'rows_quot'		=> $rows_Quot,
			'rows_quot_det'	=> $rows_Quot_Det,
			'rows_sentral'	=> $rows_Sentral
		);
		
		$this->load->view($this->folder.'/v_receive_cust_send_preview',$data);
		
		
	}
	
	
	function print_barcode_receive_tool($Kode_Sentral=''){
		$rows_Sentral		= array();		
		if($this->input->get()){
			$Code_Sentral	= urldecode($this->input->get('code_tool'));
			$rows_Sentral	= $this->db->get_where('sentral_customer_tools',array('sentral_tool_code'=>$Code_Sentral))->row();
		}
		
		
		$data 			= array(
			'title'			=> 'Print Barcode',
			'action'		=> 'print_barcode_receive_tool',
			'rows_header'	=> $rows_Sentral,
			'printby'		=> $this->session->userdata('siscal_username'),
			'today' 		=> date("Y-m-d H:i:s"),
		);	
		
		
		$this->load->view($this->folder.'/v_sentral_tool_barcode',$data); 
	}
	
	function print_barcode_receive(){
		$rows_Receive		= array();		
		if($this->input->get()){
			$Code_Receive	= urldecode($this->input->get('receive'));
			$Query_Receive	= "
								SELECT
									head_rec.datet,
									head_rec.customer_name,
									det_rec.tool_name,
									det_rec.id
								FROM
									quotation_header_receives head_rec
								INNER JOIN quotation_detail_receives det_rec ON head_rec.id = det_rec.quotation_header_receive_id
								WHERE
									det_rec.id = '".$Code_Receive."'
											";
			$rows_Receive	= $this->db->query($Query_Receive)->row();
		}
		
		
		$data 			= array(
			'title'			=> 'Print Barcode Receive',
			'action'		=> 'print_barcode_receive',
			'rows_header'	=> $rows_Receive,
			'printby'		=> $this->session->userdata('siscal_username'),
			'today' 		=> date("Y-m-d H:i:s"),
		);	
		
		
		$this->load->view($this->folder.'/v_sentral_receive_barcode',$data); 
	}
	
	
}