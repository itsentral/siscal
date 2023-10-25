<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Receive_outs_sales_order extends CI_Controller { 
	public function __construct(){
        parent::__construct();	
		
		$this->load->database();
        if(!$this->session->userdata('isSISCALlogin')){
			redirect('login');
		}
		$this->load->model('master_model');
		$controller				= ucfirst(strtolower($this->uri->segment(1)));
		$this->Arr_Akses		= getAcccesmenu($controller);
		
		$this->folder			= 'Sales_order';
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
			'title'			=> 'RECEIVE OUTSTANDING SALES ORDER',
			'action'		=> 'index',
			'akses_menu'	=> $Arr_Akses
		);
		history('View List Receive Outstanding Sales Order');
		$this->load->view($this->folder.'/v_outs_sales_order',$data);
	}
	function get_data_display(){
		$Arr_Akses		= $this->Arr_Akses;
		
		$WHERE			= "1=1";
		
		$Month			= $this->input->post('bulan');
		$Year			= $this->input->post('tahun');
		$requestData	= $_REQUEST;
		
		$like_value     = $requestData['search']['value'];
        $column_order   = $requestData['order'][0]['column'];
        $column_dir     = $requestData['order'][0]['dir'];
        $limit_start    = $requestData['start'];
        $limit_length   = $requestData['length'];
		
		$columns_order_by = array(
			1 => 'head_rec.nomor',
			2 => 'head_rec.datet',
			3 => 'head_quot.nomor',
			4 => 'head_rec.customer_name',
			5 => 'head_quot.member_name'
		);
		
		
		
		if($like_value){
			if(!empty($WHERE))$WHERE	.=" AND ";
			$WHERE	.="(
						  head_rec.nomor LIKE '%".$this->db->escape_like_str($like_value)."%'
						  OR DATE_FORMAT(head_rec.datet, '%d %b %Y') LIKE '%".$this->db->escape_like_str($like_value)."%'
						  OR head_quot.nomor LIKE '%".$this->db->escape_like_str($like_value)."%'
						  OR head_rec.customer_name LIKE '%".$this->db->escape_like_str($like_value)."%'
						  OR head_quot.member_name LIKE '%".$this->db->escape_like_str($like_value)."%'
						)";
		}
		
		if($Month){
			if(!empty($WHERE))$WHERE .=" AND ";
			$WHERE	.="MONTH(head_rec.datet) = '".$Month."'";
		}
		
		if($Year){
			if(!empty($WHERE))$WHERE .=" AND ";
			$WHERE	.="YEAR(head_rec.datet) = '".$Year."'";
		}
		
		$sql = "SELECT
					head_rec.*,
					head_quot.nomor AS quotation_nomor,
					head_quot.pono,
					head_quot.podate,
					head_quot.member_name AS marketing,
					(@row:=@row+1) AS urut
				FROM
					quotation_header_receives head_rec
				INNER JOIN quotations head_quot ON head_quot.id = head_rec.quotation_id,
				(SELECT @row:=0) r 
				WHERE ".$WHERE;
		//print_r($sql);exit();
		$fetch['totalData'] 	= $this->db->query($sql)->num_rows();
		$fetch['totalFiltered']	= $this->db->query($sql)->num_rows();

		

		$sql .= " ORDER BY head_rec.datet DESC,".$columns_order_by[$column_order]." ".$column_dir." ";
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
			$Code_Cust		= $row['customer_id'];
			$Name_Cust		= $row['customer_name'];
			$Nomor_Quotation= $row['quotation_nomor'];
			$Code_Quotation	= $row['quotation_id'];
			$Nomor_PO		= $row['pono'];
			$Date_PO		= date('d-m-Y',strtotime($row['podate']));
			$Marketing		= $row['marketing'];
			
			$Ok_Cancel		= 0;
			$Qty_Exist_SO	= "SELECT * FROM quotation_detail_receives WHERE (qty_rec - qty_so) > 0 AND quotation_header_receive_id = '".$Code_Receive."'";
			$rows_Exist_SO	= $this->db->query($Qty_Exist_SO)->result();
			if($rows_Exist_SO){
				$Ok_Cancel		= 1;
			}
			
			$Template		= '<button type="button" class="btn btn-sm btn-primary" onClick = "ActionPreview({code:\''.$Code_Receive.'\',action :\'preview_warehouse_receive\',title:\'VIEW DETAIL RECEIVE\'});" title="VIEW DETAIL RECEIVE"> <i class="fa fa-search"></i> </button>';			
			if(($Arr_Akses['delete'] == '1' || $Arr_Akses['update'] == '1') && $Ok_Cancel == 1){
				$Template		.= '&nbsp;&nbsp;<button type="button" class="btn btn-sm btn-warning" onClick = "ActionPreview({code:\''.$Code_Receive.'\',action :\'cancel_warehouse_receive\',title:\'CANCEL RECEIVE\'});" title="CANCEL RECEIVE"> <i class="fa fa-trash-o"></i> </button>';
				
			}
			
			if($Arr_Akses['download'] == '1'){
				$Template		.= '&nbsp;&nbsp;<button type="button" class="btn btn-sm btn-danger" onClick = "printReceive(\''.$Code_Receive.'\');" title="PRINT RECEIVE"> <i class="fa fa-print"></i> </button>';
				
			}
			
			
			
			
			$nestedData		= array();
			$nestedData[]	= $nomor;
			$nestedData[]	= $Nomor_Receive;
			$nestedData[]	= $Date_Receive;
			$nestedData[]	= $Nomor_Quotation;
			$nestedData[]	= $Name_Cust;
			$nestedData[]	= $Marketing;
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
	
	function preview_warehouse_receive(){
		$OK_Proses	= 0;
		$rows_Header	= $rows_Detail = $rows_Quot =  array();
		
		if($this->input->post()){
			$Code_Process	= urldecode($this->input->post('code'));
			
			$rows_Header	= $this->db->get_where('quotation_header_receives',array('id'=>$Code_Process))->row();
			$rows_Quot		= $this->db->get_where('quotations',array('id'=>$rows_Header->quotation_id))->row();
			$rows_Detail	= $this->db->get_where('quotation_detail_receives',array('quotation_header_receive_id'=>$Code_Process))->result();
			
		}
		
		
		$data = array(
			'title'			=> 'WAREHOUSE RECEIVE PREVIEW',
			'action'		=> 'preview_warehouse_receive',
			'akses_menu'	=> $this->Arr_Akses,
			'rows_header'	=> $rows_Header,
			'rows_detail'	=> $rows_Detail,
			'rows_quot'		=> $rows_Quot,
			'category'		=> 'view'
		);
		
		$this->load->view($this->folder.'/v_outs_sales_order_preview',$data);
		
	}
	
	function preview_receive_tool_file(){
		$rows_Rec_Head	= $rows_Rec_Detail = $rows_Quot = $rows_Quot_Det = $rows_Sentral = $rows_Rec_Files = array();
		if($this->input->post()){
			$Code_Detail		= urldecode($this->input->post('code_rec_detail'));
			
			
			$rows_Rec_Detail	= $this->db->get_where('quotation_detail_receives',array('id'=>$Code_Detail))->row();
			$rows_Rec_Head		= $this->db->get_where('quotation_header_receives',array('id'=>$rows_Rec_Detail->quotation_header_receive_id))->row();
			$rows_Quot			= $this->db->get_where('quotations',array('id'=>$rows_Rec_Head->quotation_id))->row();
			$rows_Quot_Det		= $this->db->get_where('quotation_details',array('id'=>$rows_Rec_Detail->quotation_detail_id))->row();
			$rows_Sentral		= $this->db->get_where('sentral_customer_tools',array('sentral_tool_code'=>$rows_Rec_Detail->sentral_code_tool))->row();
			$rows_Rec_Files		= $this->db->get_where('quotation_driver_detail_receive_file',array('driver_detail_receive'=>$Code_Detail))->result();
			
		}
		
		$data = array(
			'title'			=> 'DETAIL RECEIVE TOOLS - PICKUP BY DRIVER',
			'action'		=> 'preview_receive_tool_pickup_driver',
			'rows_rec'		=> $rows_Rec_Head,
			'rows_rec_det'	=> $rows_Rec_Detail,
			'rows_quot'		=> $rows_Quot,
			'rows_quot_det'	=> $rows_Quot_Det,
			'rows_sentral'	=> $rows_Sentral,
			'rows_rec_file'	=> $rows_Rec_Files
		);
		
		$this->load->view($this->folder.'/v_outs_sales_order_rec_files',$data);
		
		
	}
	
	function cancel_warehouse_receive(){
		$rows_Header	= $rows_Detail = $rows_Quot =  array();
		
		if($this->input->post()){
			$Code_Process	= urldecode($this->input->post('code'));
			
			$rows_Header	= $this->db->get_where('quotation_header_receives',array('id'=>$Code_Process))->row();
			$rows_Quot		= $this->db->get_where('quotations',array('id'=>$rows_Header->quotation_id))->row();
			$rows_Detail	= $this->db->get_where('quotation_detail_receives',array('quotation_header_receive_id'=>$Code_Process,'(qty_rec - qty_so) >'=>0))->result();
			
		}
		
		
		$data = array(
			'title'			=> 'WAREHOUSE RECEIVE CANCELLATION',
			'action'		=> 'cancel_warehouse_receive',
			'akses_menu'	=> $this->Arr_Akses,
			'rows_header'	=> $rows_Header,
			'rows_detail'	=> $rows_Detail,
			'rows_quot'		=> $rows_Quot,
			'category'		=> 'view'
		);
		
		$this->load->view($this->folder.'/v_outs_sales_order_cancel',$data);
	}
	
	
	function save_cancel_recieve_tool(){
		$rows_Return	= array(
			'status'		=> 2,
			'pesan'			=> 'No Record was found...'
		);
		if($this->input->post()){
			//echo"<pre>";print_r($this->input->post());exit;
			
			$Created_By		= $this->session->userdata('siscal_userid');
			$Created_Name	= $this->session->userdata('siscal_username');
			$Created_Date	= date('Y-m-d H:i:s');
			
			$Code_Receive	= $this->input->post('code_receive');
			$Cancel_Reason	= strtoupper($this->input->post('cancel_reason'));
			$detReceive		= $this->input->post('detDelete');
			$Code_Cancel	= 'REC-CNC-'.date('HisdmY');
			
			$this->db->trans_begin();
			$Pesan_Error	= '';
			
			$Find_Exist		= $this->db->get_where('quotation_header_receives',array('id'=>$Code_Receive))->row();
			$Ins_Header		= array(
				'id'				=> $Code_Cancel,
				'code_rec'			=> $Code_Receive,
				'nomor'				=> $Find_Exist->nomor,
				'datet'				=> $Find_Exist->datet,
				'quotation_id'		=> $Find_Exist->quotation_id,
				'customer_id'		=> $Find_Exist->customer_id,
				'customer_name'		=> $Find_Exist->customer_name,
				'rec_category'		=> $Find_Exist->rec_category,
				'driver_id'			=> $Find_Exist->driver_id,
				'driver_name'		=> $Find_Exist->driver_name,
				'rec_by'			=> $Find_Exist->rec_by,
				'cancel_reason'		=> $Cancel_Reason,
				'cancel_by'			=> $Created_By,
				'cancel_date'		=> $Created_Date,
				'created_date'		=> $Find_Exist->created_date,
				'created_by'		=> $Find_Exist->created_by
			);
			
			$Has_Head_Detail		= $this->db->insert('quotation_header_receive_cancels',$Ins_Header);
			if($Has_Head_Detail !== TRUE){
				$Pesan_Error	= 'Error Insert Cancel Receive Header';
			}
			
			$Cat_Receive		= $Find_Exist->rec_category;
			
			if($detReceive){
				$intCancel	= 0;
				foreach($detReceive as $keyRec=>$valRec){
					$intCancel++;
					$Cancel_Detail	= $Code_Cancel.'-'.sprintf('%03d',$intCancel);
					$Code_Detail	= $valRec['code_detail'];
					$Qty_Rec		= $valRec['qty_rec'];
					$Qty_Cancel		= $valRec['qty'];
					$Quot_Detail	= $valRec['quotation_detail_id'];
					
					
					
					if($Qty_Rec == $Qty_Cancel){
						$Qry_Rec_Detail	= "DELETE FROM quotation_detail_receives WHERE id='".$Code_Detail."'";
					}else{
						$Qry_Rec_Detail	= "UPDATE quotation_detail_receives SET qty_so = qty_so + ".$Qty_Cancel." WHERE id='".$Code_Detail."'";
					}
					
					$Has_Pros_Rec_Detail	= $this->db->query($Qry_Rec_Detail);
					if($Has_Pros_Rec_Detail !== TRUE){
						$Pesan_Error	= 'Error Process Receive Detail';
					}
					
					
					$Qty_Rec_Driver		= '';
					 
					if(strtolower($Cat_Receive) == 'driver'){
						$Qry_Quot_Detail	= "UPDATE quotation_details SET qty_so = qty_so - ".$Qty_Cancel.", qty_driver = qty_driver + ".$Qty_Cancel." WHERE id='".$Quot_Detail."'";
						$Qty_Rec_Driver		= "UPDATE quotation_driver_detail_receives SET flag_warehouse = 'N', sentral_code_tool = NULL, code_rec_warehouse = NULL WHERE code_rec_warehouse='".$Code_Detail."'";
					}else{
						$Qry_Quot_Detail	= "UPDATE quotation_details SET qty_so = qty_so - ".$Qty_Cancel." WHERE id='".$Quot_Detail."'";
					}
					
					$Has_Pros_Quot_Detail	= $this->db->query($Qry_Quot_Detail);
					if($Has_Pros_Quot_Detail !== TRUE){
						$Pesan_Error	= 'Error Process Quotation Detail';
					}
					
					if($Qty_Rec_Driver){
						$Has_Pros_Driver_Detail	= $this->db->query($Qty_Rec_Driver);
						if($Has_Pros_Driver_Detail !== TRUE){
							$Pesan_Error	= 'Error Update Driver Receive Detail';
						}
					}
					
					if($valRec['sentral_code_tool']){
						$Qry_Del_Sentral_Code	= "DELETE FROM sentral_customer_tools WHERE sentral_tool_code = '".$valRec['sentral_code_tool']."'";
						/*
						$Has_Del_Sentral_Code	= $this->db->query($Qry_Del_Sentral_Code);
						if($Has_Del_Sentral_Code !== TRUE){
							$Pesan_Error	= 'Error Delete Sentral Customer Tool';
						}
						*/
					}
					
					$Ins_Detail												= $valRec;
					$Ins_Detail['id']										= $Cancel_Detail;
					$Ins_Detail['quotation_header_receive_cancel_id']		= $Code_Cancel;
					
					$Has_Ins_Detail		= $this->db->insert('quotation_detail_receive_cancels',$Ins_Detail);
					if($Has_Ins_Detail !== TRUE){
						$Pesan_Error	= 'Error Insert Cancel Receive Detail';
					}
					
				}
			}
			
			## CEK JIKA DETAIL WAREHOUSE RECEIVE ##
			$Jum_Rows	= $this->db->get_where('quotation_detail_receives',array('quotation_header_receive_id'=>$Code_Receive))->num_rows();
			if($Jum_Rows <= 0){
				$Qry_Del_Head_Receive	= "DELETE FROM quotation_header_receives WHERE id = '".$Code_Receive."'";
				
				$Has_Del_Head_Receive	= $this->db->query($Qry_Del_Head_Receive);
				if($Has_Del_Head_Receive !== TRUE){
					$Pesan_Error	= 'Error Delete Receive Header';
				}
				
			}
			
			## CEK JIKA DETAIL WAREHOUSE RECEIVE ##
			$Jum_Pros_SO	= $this->db->get_where('quotation_details',array('quotation_id'=>$Find_Exist->quotation_id,'qty_so >'=>0))->num_rows();
			if($Jum_Pros_SO <= 0){
				$Qry_Upd_Quot_Head	= "UPDATE quotations SET flag_so = 'N' WHERE id = '".$Find_Exist->quotation_id."'";
				
				$Has_Upd_Quot_Head	= $this->db->query($Qry_Upd_Quot_Head);
				if($Has_Upd_Quot_Head !== TRUE){
					$Pesan_Error	= 'Error Update Quotation Header';
				}
				
			}
			
			if ($this->db->trans_status() != TRUE || !empty($Pesan_Error)){
				$this->db->trans_rollback();
				$rows_Return		= array(
					'status'		=> 2,
					'pesan'			=> 'Save Process  Failed, '.$Pesan_Error
				);
				history('Cancellation Warehouse receive '.$Code_Receive.' - '.$Pesan_Error);
			}else{
				$this->db->trans_commit();
				$rows_Return		= array(
					'status'		=> 1,
					'pesan'			=> 'Save process success. Thank you & have a nice day......'
				);
				history('Cancellation Warehouse receive '.$Code_Receive);
			}	
		}
		echo json_encode($rows_Return);
	}	
	
	function list_cancel_receive(){
		$Arr_Akses			= $this->Arr_Akses;
		if($Arr_Akses['read'] !='1'){
			$this->session->set_flashdata("alert_data", "<div class=\"alert alert-warning\" id=\"flash-message\">You Don't Have Right To Access This Page, Please Contact Your Administrator....</div>");
			redirect(site_url('Receive_outs_sales_order'));
		}
		
		$data = array(
			'title'			=> 'WAREHOUSE RECEIVE CANCELLATION',
			'action'		=> 'list_cancel_receive',
			'akses_menu'	=> $Arr_Akses
		);
		history('View Warehouse Receive Cancellation');
		$this->load->view($this->folder.'/v_cancel_sales_order',$data);
	}
	
	function get_data_display_cancellation(){
		$Arr_Akses			= $this->Arr_Akses;
		
		$DateFr				= $this->input->post('datefr');
		$DateTl				= $this->input->post('datetl');
		
		$WHERE				= "1=1";
		if(!empty($DateFr) && !empty($DateTl)){
			if(!empty($WHERE))$WHERE	.=" AND ";
			$WHERE	.="(head_rec.datet BETWEEN '".$DateFr."' AND '".$DateTl."')";
		}
		
		$requestData	= $_REQUEST;
		
		$like_value     = $requestData['search']['value'];
        $column_order   = $requestData['order'][0]['column'];
        $column_dir     = $requestData['order'][0]['dir'];
        $limit_start    = $requestData['start'];
        $limit_length   = $requestData['length'];
		
		$columns_order_by = array(
			0 => 'head_rec.nomor',
			1 => 'head_rec.datet',
			2 => 'head_quot.nomor',
			3 => 'head_rec.customer_name',
			4 => 'head_quot.member_name',
			5 => 'head_rec.cancel_reason'
		);
		
		
		
		if($like_value){
			if(!empty($WHERE))$WHERE	.=" AND ";
			$WHERE	.="(
						  head_rec.nomor LIKE '%".$this->db->escape_like_str($like_value)."%'
						  head_quot.nomor LIKE '%".$this->db->escape_like_str($like_value)."%'
						  OR DATE_FORMAT(head_rec.datet, '%d %b %Y') LIKE '%".$this->db->escape_like_str($like_value)."%'
						  OR head_rec.customer_name LIKE '%".$this->db->escape_like_str($like_value)."%'
						  OR head_quot.member_name LIKE '%".$this->db->escape_like_str($like_value)."%'
						  OR head_rec.cancel_reason LIKE '%".$this->db->escape_like_str($like_value)."%'
						)";
		}
		
		
		$sql = "SELECT
					head_rec.*,
					head_quot.nomor AS quotation_nomor,
					head_quot.pono,
					head_quot.podate,
					head_quot.member_id,
					head_quot.member_name,
					(@row:=@row+1) AS urut
				FROM
					quotation_header_receive_cancels head_rec
					INNER JOIN quotations head_quot ON head_quot.id = head_rec.quotation_id,
				(SELECT @row:=0) r 
				WHERE ".$WHERE;
		//print_r($sql);exit();
		$fetch['totalData'] 	= $this->db->query($sql)->num_rows();
		$fetch['totalFiltered']	= $this->db->query($sql)->num_rows();

		

		$sql .= " ORDER BY head_rec.datet DESC,".$columns_order_by[$column_order]." ".$column_dir." ";
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
			$Code_Cancel		= $row['id'];
			$Code_Rec			= $row['code_rec'];
			$Nomor_Rec			= $row['nomor'];
			$Date_Rec			= date('d-m-Y',strtotime($row['datet']));
			$Customer			= $row['customer_name'];
			$Driver				= $row['driver_name'];
			$Nomor_Quot			= $row['quotation_nomor'];
			$Marketing			= $row['member_name'];
			$Cancel_Reason		= $row['cancel_reason'];
			$Nomor_PO			= $row['pono'];
			
			$Template		='<button type="button" onClick="return ActionPreview({code:\''.$Code_Cancel.'\',title:\'DETAIL CANCELLATION\',action:\'view_detail_cancellation\'});" class="btn btn-sm bg-navy-active" title="DETAIL CANCELLATION"> <i class="fa fa-search"></i> </button>';
			
			$nestedData			= array();
			$nestedData[] 		= $Nomor_Rec;
			$nestedData[] 		= $Date_Rec;
			$nestedData[] 		= $Nomor_Quot;
			$nestedData[] 		= $Customer;
			$nestedData[] 		= $Marketing;
			$nestedData[] 		= $Cancel_Reason;
			$nestedData[]		= $Template;
			
			
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
	
	function view_detail_cancellation(){
		$rows_Header = $rows_Detail = $rows_Quot = array();
		$Code_Back	= '';
		if($this->input->post()){
			$Code_Quot 		= urldecode($this->input->post('code'));
			$rows_Header	= $this->db->get_where('quotation_header_receive_cancels',array('id'=>$Code_Quot))->row();
			$rows_Detail	= $this->db->get_where('quotation_detail_receive_cancels',array('quotation_header_receive_cancel_id'=>$Code_Quot))->result();
			$rows_Quot		= $this->db->get_where('quotations',array('id'=>$rows_Header->quotation_id))->row();
			
		}
		$Arr_Akses			= $this->Arr_Akses;
		$data = array(
			'title'			=> 'DETAIL CANCELLATION',
			'action'		=> 'view_detail_cancellation',
			'akses_menu'	=> $Arr_Akses,
			'rows_detail'	=> $rows_Detail,
			'rows_header'	=> $rows_Header,
			'rows_quot'		=> $rows_Quot
		);
		
		$this->load->view($this->folder.'/v_cancel_sales_order_preview',$data);
		
	}
	
	function print_warehouse_receive($Code_Process = ''){
		$OK_Proses	= 0;
		$rows_Header	= $rows_Detail = $rows_Quot =  array();
		
		if($this->input->get()){
			$Code_Process	= urldecode($this->input->get('code'));
		}
		$rows_Header	= $this->db->get_where('quotation_header_receives',array('id'=>$Code_Process))->row();
		$rows_Quot		= $this->db->get_where('quotations',array('id'=>$rows_Header->quotation_id))->row();
		$rows_Detail	= $this->db->get_where('quotation_detail_receives',array('quotation_header_receive_id'=>$Code_Process))->result();
		
		
		$data = array(
			'title'			=> 'WAREHOUSE RECEIVE PREVIEW',
			'action'		=> 'preview_warehouse_receive',
			'akses_menu'	=> $this->Arr_Akses,
			'rows_header'	=> $rows_Header,
			'rows_detail'	=> $rows_Detail,
			'rows_quot'		=> $rows_Quot,
			'printby'		=> $this->session->userdata('siscal_username'),
			'today' 		=> date("Y-m-d H:i:s"),
		);
		
		$this->load->view($this->folder.'/v_outs_sales_order_print',$data);
		
	}
	
}