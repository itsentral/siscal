<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Receive_driver_order_tool extends CI_Controller { 
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
			'title'			=> 'RECEIVE TOOLS - PICKUP BY DRIVER',
			'action'		=> 'index',
			'akses_menu'	=> $Arr_Akses
		);
		history('View List Receive Tools - Pickup By Driver');
		$this->load->view($this->folder.'/v_receive_driver_pickup',$data);
	}
	function get_data_display(){
		$Arr_Akses			= $this->Arr_Akses;
		
		$WHERE				= "header.`flag_sign` = 'Y'
							AND detail.flag_warehouse = 'N'";
		
		$requestData	= $_REQUEST;
		
		$like_value     = $requestData['search']['value'];
        $column_order   = $requestData['order'][0]['column'];
        $column_dir     = $requestData['order'][0]['dir'];
        $limit_start    = $requestData['start'];
        $limit_length   = $requestData['length'];
		
		$columns_order_by = array(
			0 => 'header.nomor',
			1 => 'header.datet',
			2 => 'header.driver_name',
			3 => 'header.customer_name',
			4 => 'quot.nomor',
			5 => 'quot.pono'
		);
		
		
		
		if($like_value){
			if(!empty($WHERE))$WHERE	.=" AND ";
			$WHERE	.="(
						  header.nomor LIKE '%".$this->db->escape_like_str($like_value)."%'
						  OR DATE_FORMAT(header.datet, '%d %b %Y') LIKE '%".$this->db->escape_like_str($like_value)."%'
						  OR header.driver_name LIKE '%".$this->db->escape_like_str($like_value)."%'
						  OR header.customer_name LIKE '%".$this->db->escape_like_str($like_value)."%'
						  OR quot.pono LIKE '%".$this->db->escape_like_str($like_value)."%'
						  OR quot.nomor LIKE '%".$this->db->escape_like_str($like_value)."%'
						)";
		}
		
		
		$sql = "SELECT
					header.id,
					header.nomor,
					header.datet,
					header.customer_id,
					header.customer_name, 
					header.driver_id,
					header.driver_name, 
					header.flag_sign,
					quot.id AS quotation_id,
					quot.podate,
					quot.pono,
					quot.nomor AS quotation_nomor,
					quot.member_id,
					quot.member_name,
					quot.address,
					(@row:=@row+1) AS urut
				FROM
					quotation_driver_receives header
				INNER JOIN quotation_driver_detail_receives detail ON header.id = detail.code_receive
				INNER JOIN quotations quot ON quot.id = detail.quotation_id,
				(SELECT @row:=0) r 
				WHERE ".$WHERE."
				GROUP BY 
					header.id,
					detail.quotation_id";
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
			
			$Code_Receive	= $row['id'];
			$Nomor_Receive	= $row['nomor'];
			$Date_Receive	= date('d-m-Y',strtotime($row['datet']));
			$Code_Sales		= $row['member_id'];
			$Name_Sales		= $row['member_name'];
			$Code_Driver	= $row['driver_id'];
			$Name_Driver	= $row['driver_name'];
			$Code_Customer	= $row['customer_id'];
			$Code_Quotation	= $row['quotation_id'];
			$Name_Customer	= $row['customer_name'];
			$Nomor_Quot		= $row['quotation_nomor'];
			$PO_No			= $row['pono'];
			$PO_Date		= date('d-m-Y',strtotime($row['podate']));
			
			$Code_Unik		= $Code_Receive.'^'.$Code_Quotation;
			
			$Template		= '<button type="button" class="btn btn-sm btn-primary" onClick = "ActionPreview({code:\''.$Code_Unik.'\',action :\'detail_driver_pickup_tool\',title:\'VIEW DRIVER RECEIVES\'});" title="VIEW DRIVER RECEIVES"> <i class="fa fa-search"></i> </button>';
			
			if($Arr_Akses['create'] == '1' || $Arr_Akses['update'] == '1'){
				$Template		.="&nbsp;&nbsp;<a href='".site_url('Receive_driver_order_tool/receive_process?receive='.urlencode($Code_Unik))."&code_process=' class='btn btn-sm bg-navy-active' title='RECEIVE TOOL - PICKUP BY DRIVER'> <i class='fa fa-long-arrow-right'></i> </a>";
			}
			
			
			
			
			
			$nestedData		= array();
			$nestedData[]	= $Nomor_Receive;
			$nestedData[]	= $Date_Receive;
			$nestedData[]	= $Name_Driver;
			$nestedData[]	= $Name_Customer;
			$nestedData[]	= $Nomor_Quot;
			$nestedData[]	= $PO_No;
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
	
	function detail_driver_pickup_tool(){
		$rows_Header	= $rows_Detail	= $rows_Quot = array();
		if($this->input->post()){
			$Code_Driv_Head	= urldecode($this->input->post('code'));
			$Split_Code		= explode('^',$Code_Driv_Head);
			$Code_Receive	= $Split_Code[0];
			$Code_Quot		= $Split_Code[1];
			$rows_Header	= $this->db->get_where('quotation_driver_receives',array('id'=>$Code_Receive))->row();
			$rows_Detail	= $this->db->get_where('quotation_driver_detail_receives',array('code_receive'=>$Code_Receive,'quotation_id'=>$Code_Quot))->result();
			$rows_Quot		= $this->db->get_where('quotations',array('id'=>$Code_Quot))->row();
		}
		$data = array(
			'title'			=> 'DETAIL DRIVER PICKUP TOOLS',
			'action'		=> 'detail_driver_pickup_tool',
			'akses_menu'	=> $this->Arr_Akses,
			'rows_header'	=> $rows_Header,
			'rows_detail'	=> $rows_Detail,
			'rows_quot'		=> $rows_Quot
		);
		
		$this->load->view($this->folder.'/v_driver_pickup_preview',$data);
	}
	
	
	function receive_process(){
		$OK_Proses	= 0;
		$rows_Header	= $rows_Detail = $rows_Receive = $rows_Rec_Detail =  array();
		$Code_Process	= '';
		if($this->input->get()){
			$Code_Driv_Head	= urldecode($this->input->get('receive'));
			$Split_Code		= explode('^',$Code_Driv_Head);
			$Code_Receive	= $Split_Code[0];
			$Code_Quot		= $Split_Code[1];
			$Code_Process	= urldecode($this->input->get('code_process'));
			
			$rows_Header	= $this->db->get_where('quotation_driver_receives',array('id'=>$Code_Receive))->row();
			$rows_Detail	= $this->db->get_where('quotation_driver_detail_receives',array('code_receive'=>$Code_Receive,'quotation_id'=>$Code_Quot,'flag_warehouse'=>'N'))->result();
			
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
				'title'			=> 'RECEIVE TOOL PROCESS - PICKUP BY DRIVER',
				'action'		=> 'receive_process',
				'akses_menu'	=> $this->Arr_Akses,
				'rows_header'	=> $rows_Header,
				'rows_detail'	=> $rows_Detail,
				'rows_rec'		=> $rows_Receive,
				'rows_rec_det'	=> $rows_Rec_Detail,
				'code_process'	=> $Code_Process
			);
			
			$this->load->view($this->folder.'/v_receive_driver_pickup_process',$data);
		}else{
			$this->session->set_flashdata("alert_data", "<div class=\"alert alert-warning\" id=\"flash-message\">No record was found to process....</div>");
			redirect(site_url('Receive_driver_order_tool'));
		}
	}
	
	function process_receive_tool_pickup_driver(){
		$rows_header	= $rows_detail = array();
		$Code_Process	= '';
		$Flag_New		= 'N';
		if($this->input->post()){
			$Code_Rec_Detail	= urldecode($this->input->post('code_rec_detail'));
			$Code_Process		= urldecode($this->input->post('code_process'));
			$Flag_New			= urldecode($this->input->post('flag_new'));
			$Code_Receive		= urldecode($this->input->post('code_rec'));
			
			$rows_Detail	= $this->db->get_where('quotation_driver_detail_receives',array('id'=>$Code_Rec_Detail,'code_receive'=>$Code_Receive))->row();
			$rows_Header	= $this->db->get_where('quotation_driver_receives',array('id'=>$Code_Receive))->row();
			
		}
		
		$Arr_Akses			= $this->Arr_Akses;
		$data = array(
			'title'			=> 'TOOL RECEIVE PROCESS',
			'action'		=> 'process_receive_tool_pickup_driver',
			'akses_menu'	=> $Arr_Akses,
			'rows_header'	=> $rows_Header,
			'rows_detail'	=> $rows_Detail,
			'code_trans'	=> $Code_Process,
			'code_new'		=> $Flag_New
		);
		
		$this->load->view($this->folder.'/v_receive_driver_pickup_update',$data);
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
	
	
	function save_receive_pickup_driver_process(){
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
			$Code_Driv_Head	= $this->input->post('code_driver_header');
			$Code_Driv_Det	= $this->input->post('code_driver_detail');
			$Code_Quot		= $this->input->post('quotation_update');
			$Code_Cust		= $this->input->post('cust_id_modal');
			$Name_Cust		= $this->input->post('cust_modal');
			
			$Code_Driver	= $this->input->post('driver_id_modal');
			$Name_Driver	= $this->input->post('driver_modal');
			
			$Code_Sentral	= $this->input->post('code_sentral_tool');
			$Code_Tool		= $this->input->post('tool_id');
			$Name_Tool		= $this->input->post('tool_name');
			$Code_Identify	= $this->input->post('no_identifikasi');
			$Code_Serial	= $this->input->post('no_serial_number');
			$Code_Merk		= strtoupper($this->input->post('merk_alat'));
			$Code_Type		= strtoupper($this->input->post('tipe_alat'));
			$Notes			= strtoupper($this->input->post('descr'));
			
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
						'rec_category'		=> 'DRIVER',
						'driver_id'			=> $Code_Driver,
						'driver_name'		=> $Name_Driver,
						'rec_by'			=> $Created_Name,
						'created_date'		=> $Created_Date,
						'created_by'		=> $Created_By
				);
				$Has_Ins_REc_Head	= $this->db->insert('quotation_header_receives',$Ins_Receive);
				if($Has_Ins_REc_Head !== TRUE){
					$Pesan_Error	= 'Error Insert Receive Header';
				}				
			}
			
			## MODIFIED BY ALI ~ 2022-11-19 ##
			$rows_RecDrv_Detail	= $this->db->get_where('quotation_driver_detail_receives',array('id'=>$Code_Driv_Det))->row();
			$Qty_SO				= 0;
			$Letter_Order_Code	= '';
			$Code_QuotDet		= '';
			if($rows_RecDrv_Detail){
				$Code_QuotDet		= $rows_RecDrv_Detail->quotation_detail_id;
				$Letter_Order_Code	= $rows_RecDrv_Detail->letter_order_id;
				$Qty_SO				= $rows_RecDrv_Detail->qty_so;
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
			
			## MODIFIED BY ALI ~ 2022-11-19 ##
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
				'rec_category'					=> 'DRIVER',
				'driver_id'						=> $Code_Driver,
				'driver_name'					=> $Name_Driver,
				'rec_date'						=> date('Y-m-d'),
				'receive_by'					=> $Created_By,
				'receive_proses'				=> $Created_Date,
				'sentral_code_tool'				=> $Code_Sentral,
				'letter_order_id'				=> $Letter_Order_Code,
				'qty_so'						=> $Qty_SO
			);
			
			$Has_Ins_Detail	= $this->db->insert('quotation_detail_receives',$Ins_Detail);
			if($Has_Ins_Detail !== TRUE){
				$Pesan_Error	= 'Error Insert Receive Detail';
			}
			
			$Upd_Quot_Detail	= "UPDATE quotation_details SET qty_so = qty_so + ".$Qty_Receive.", qty_driver = qty_driver - ".$Qty_Receive." WHERE id = '".$Code_QuotDet."'";
			$Has_Upd_Quot_Detail= $this->db->query($Upd_Quot_Detail);
			if($Has_Upd_Quot_Detail !== TRUE){
				$Pesan_Error	= 'Error Update Quotation Detail';
			}
			
			$Upd_Quot			= "UPDATE quotations SET flag_so = 'Y', modified_date = '".$Created_Date."', modified_by = '".$Created_By."' WHERE id ='".$Code_Quot."'";
			$Has_Upd_Quot		= $this->db->query($Upd_Quot);
			if($Has_Upd_Quot !== TRUE){
				$Pesan_Error	= 'Error Update Quotation Header';
			}
			
			## UPDATE DRIVER RECEIVE ##
			$Upd_Driver_RecDet		= "UPDATE quotation_driver_detail_receives SET flag_warehouse = 'Y', sentral_code_tool = '".$Code_Sentral."', code_rec_warehouse = '".$Code_Detail."' WHERE id = '".$Code_Driv_Det."' AND code_receive = '".$Code_Driv_Head."'";
			$Has_Upd_Driver_RecDet	= $this->db->query($Upd_Driver_RecDet);
			if($Has_Upd_Driver_RecDet !== TRUE){
				$Pesan_Error	= 'Error Update Driver Receive Detail';
			}
			
			
			$Upd_Driver_RecHead		= "UPDATE quotation_driver_receives SET flag_warehouse = 'Y', rec_by = '".$Created_Name."', rec_date = '".$Created_Date."' WHERE id = '".$Code_Driv_Head."'";
			$Has_Upd_Driver_RecHead	= $this->db->query($Upd_Driver_RecHead);
			if($Has_Upd_Driver_RecHead !== TRUE){
				$Pesan_Error	= 'Error Update Driver Receive Header';
			}
			
			if ($this->db->trans_status() != TRUE || !empty($Pesan_Error)){
				$this->db->trans_rollback();
				$rows_Return		= array(
					'status'		=> 2,
					'pesan'			=> 'Save Process  Failed, '.$Pesan_Error
				);
				history('Receive Tool Process - Pickup By Driver '.$Code_Receive.' - '.$Code_Driv_Det.' - '.$Pesan_Error);
			}else{
				$this->db->trans_commit();
				$rows_Return		= array(
					'status'		=> 1,
					'pesan'			=> 'Save process success. Thank you & have a nice day......'
				);
				history('Receive Tool Process - Pickup By Driver '.$Code_Receive.' - '.$Code_Driv_Det);
			}
			
		}
		echo json_encode($rows_Return);
	}
	
	
	
	function preview_receive_tool_pickup_driver(){
		$rows_Rec_Head	= $rows_Rec_Detail = $rows_Quot = $rows_Quot_Det	= $rows_Sentral = $rows_Driver_Head = $rows_Driver_Detail = array();
		if($this->input->post()){
			$Code_Detail		= urldecode($this->input->post('code_rec_detail'));
			$Code_Receive		= urldecode($this->input->post('code_process'));
			$rows_Rec_Head		= $this->db->get_where('quotation_header_receives',array('id'=>$Code_Receive))->row();
			$rows_Rec_Detail	= $this->db->get_where('quotation_detail_receives',array('id'=>$Code_Detail))->row();
			$rows_Quot			= $this->db->get_where('quotations',array('id'=>$rows_Rec_Head->quotation_id))->row();
			$rows_Quot_Det		= $this->db->get_where('quotation_details',array('id'=>$rows_Rec_Detail->quotation_detail_id))->row();
			$rows_Sentral		= $this->db->get_where('sentral_customer_tools',array('sentral_tool_code'=>$rows_Rec_Detail->sentral_code_tool))->row();
			$rows_Driver_Detail	= $this->db->get_where('quotation_driver_detail_receives',array('code_rec_warehouse'=>$Code_Detail))->row();
			if($rows_Driver_Detail){
				$rows_Driver_Head	= $this->db->get_where('quotation_driver_receives',array('id'=>$rows_Driver_Detail->code_receive))->row();
			}
		}
		
		$data = array(
			'title'			=> 'DETAIL RECEIVE TOOLS - PICKUP BY DRIVER',
			'action'		=> 'preview_receive_tool_pickup_driver',
			'rows_rec'		=> $rows_Rec_Head,
			'rows_rec_det'	=> $rows_Rec_Detail,
			'rows_quot'		=> $rows_Quot,
			'rows_quot_det'	=> $rows_Quot_Det,
			'rows_sentral'	=> $rows_Sentral,
			'rows_drvhead'	=> $rows_Driver_Head,
			'rows_drvdetail'=> $rows_Driver_Detail
		);
		
		$this->load->view($this->folder.'/v_receive_driver_pickup_preview',$data);
		
		
	}
	
	
	function print_barcode_receive_tool($Kode_Sentral=''){
		$rows_Sentral		= $rows_Tool = array();		
		if($this->input->get()){
			$Code_Sentral	= urldecode($this->input->get('code_tool'));
			$rows_Sentral	= $this->db->get_where('sentral_customer_tools',array('sentral_tool_code'=>$Code_Sentral))->row();
			$rows_Tool		= $this->db->get_where('tools',array('id'=>$rows_Sentral->tool_id))->row();
		}
		
		
		$data 			= array(
			'title'			=> 'Print Barcode',
			'action'		=> 'print_barcode_receive_tool',
			'rows_header'	=> $rows_Sentral,
			'rows_tool'		=> $rows_Tool,
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