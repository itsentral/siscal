<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Subcon_purchase_order extends CI_Controller
{
	public function __construct()
	{
		parent::__construct();

		$this->load->database();
		if (!$this->session->userdata('isSISCALlogin')) {
			redirect('login');
		}
		$this->load->model('master_model');
		$controller			= ucfirst(strtolower($this->uri->segment(1)));
		$this->Arr_Akses	= getAcccesmenu($controller);

		$this->folder			= 'Subcon';
		$this->file_location	= $this->config->item('location_file');
	}

	public function index()
	{
		$Arr_Akses			= $this->Arr_Akses;
		if ($Arr_Akses['read'] != '1') {
			$this->session->set_flashdata("alert_data", "<div class=\"alert alert-warning\" id=\"flash-message\">You Don't Have Right To Access This Page, Please Contact Your Administrator....</div>");
			redirect(site_url('dashboard'));
		}

		$rows_Supplier		= $this->master_model->getArray('suppliers', array('id !='=>'COMP-001'), 'id', 'supplier');
		$data = array(
			'title'			=> 'MANAGE SUBCON PURCHASE ORDER',
			'action'		=> 'index',
			'akses_menu'	=> $Arr_Akses,
			'rows_supplier'	=> $rows_Supplier
		);
		history('View List Subcon Purchase Order');
		$this->load->view($this->folder . '/v_subcon_purchase_order', $data);
	}

	/*
	| -------------------------------- |
	|	 	DISPLAY LIST INVOICE       |
	| -------------------------------- |
	*/
	function get_data_display()
	{
		$Arr_Akses		= $this->Arr_Akses;

		$WHERE			= "1=1";
		
		$Supp_Find		= $this->input->post('supplier');
		$Month_Find		= $this->input->post('bulan');
		$Year_Find		= $this->input->post('tahun');
		$Status_Find	= $this->input->post('status');
		$requestData	= $_REQUEST;
		
		$like_value     = $requestData['search']['value'];
        $column_order   = $requestData['order'][0]['column'];
        $column_dir     = $requestData['order'][0]['dir'];
        $limit_start    = $requestData['start'];
        $limit_length   = $requestData['length'];
		
		$columns_order_by = array(
			0 => 'subcon_pono',
			1 => 'datet',
			2 => 'supplier_name',
			3 => 'address',
			4 => 'grand_tot',
			5 => 'sts_subcon'
		);
		
		
		
		if($this->db->escape_like_str($like_value)){
			if(!empty($WHERE))$WHERE	.=" AND ";
			$WHERE	.="(
						  subcon_pono LIKE '%".$this->db->escape_like_str($this->db->escape_like_str($like_value))."%'
						  OR DATE_FORMAT(datet, '%d %b %Y') LIKE '%".$this->db->escape_like_str($this->db->escape_like_str($like_value))."%'
						  OR supplier_name LIKE '%".$this->db->escape_like_str($this->db->escape_like_str($like_value))."%'
						  OR address LIKE '%".$this->db->escape_like_str($this->db->escape_like_str($like_value))."%'
						  OR grand_tot LIKE '%".$this->db->escape_like_str($this->db->escape_like_str($like_value))."%'
						  OR sts_subcon LIKE '%".$this->db->escape_like_str($this->db->escape_like_str($like_value))."%'
						)";
		}
		
		if($Supp_Find){
			if(!empty($WHERE))$WHERE .=" AND ";
			$WHERE	.="supplier_id = '".$Supp_Find."'";
		}
		
		if($Month_Find){
			if(!empty($WHERE))$WHERE .=" AND ";
			$WHERE	.="MONTH(datet) = '".$Month_Find."'";
		}
		
		if($Year_Find){
			if(!empty($WHERE))$WHERE .=" AND ";
			$WHERE	.="YEAR(datet) = '".$Year_Find."'";
		}
		
		if($Status_Find){
			if(!empty($WHERE))$WHERE .=" AND ";
			$WHERE	.="sts_subcon = '".$Status_Find."'";
		}
		
		$sql = "SELECT
					*,
					(@row:=@row+1) AS urut
				FROM
					subcon_purchase_orders,
				(SELECT @row:=0) r 
				WHERE ".$WHERE;
		//print_r($sql);exit();
		$fetch['totalData'] 	= $this->db->query($sql)->num_rows();
		$fetch['totalFiltered']	= $this->db->query($sql)->num_rows();

		

		$sql .= " ORDER BY datet DESC,".$columns_order_by[$column_order]." ".$column_dir." ";
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
			
			$Code_Subcon	= $row['id'];
			$Nomor_Subcon	= $row['subcon_pono'];
			$Date_Subcon	= date('d-m-Y',strtotime($row['datet']));
			$Code_Vendor	= $row['supplier_id'];
			$Name_Vendor	= $row['supplier_name'];
			$Addr_Vendor	= strtoupper($row['address']);
			$Total_Subcon	= number_format($row['grand_tot']);
			$Status_SPK		= $row['sts_subcon'];
			
			$Lable_Status	= 'WAITING APPROVAL';
			$Color_Status	= 'bg-green';
			if($Status_SPK === 'CNC'){
				$Lable_Status	= 'CANCELED';
				$Color_Status	= 'bg-orange';
			}else if($Status_SPK === 'CLS'){
				$Lable_Status	= 'CLOSE';
				$Color_Status	= 'bg-navy-active';
			}else if($Status_SPK === 'APV'){
				$Lable_Status	= 'WAITING INVOICE';
				$Color_Status	= 'bg-maroon-active';
			}else if($Status_SPK === 'REJ'){
				$Lable_Status	= 'REJECTED';
				$Color_Status	= 'bg-red-active';
			}
			
			$Ket_Status		= '<span class="badge '.$Color_Status.'">'.$Lable_Status.'</span>';
			
			
			
			$Template		= '<button type="button" class="btn btn-sm btn-primary" onClick = "ActionPreview({code:\''.$Code_Subcon.'\',action :\'preview_subcon_purchase_order\',title:\'VIEW SUBCON PURCHASE ORDER\'});" title="VIEW SUBCON PURCHASE ORDER"> <i class="fa fa-search"></i> </button>';			
			if(($Arr_Akses['delete'] == '1' || $Arr_Akses['update'] == '1') && $Status_SPK === 'OPN'){
				$Template		.= '&nbsp;&nbsp;<button type="button" class="btn btn-sm btn-danger" onClick = "ActionPreview({code:\''.$Code_Subcon.'\',action :\'cancel_subcon_purchase_order\',title:\'CANCEL SUBCON PURCHASE ORDER\'});" title="CANCEL SUBCON PURCHASE ORDER"> <i class="fa fa-trash-o"></i> </button>';
			}
			if($Arr_Akses['download'] == '1' && $Status_SPK === 'APV'){
				$Template		.= '&nbsp;&nbsp;<a href="'.site_url().'/Subcon_purchase_order/print_subcon_purchase_order?nomor_po='.urlencode($Code_Subcon).'" class="btn btn-sm bg-navy-active" target = "_blank" title="PRINT SUBCON PURCHASE ORDER"> <i class="fa fa-print"></i> </button>';
			}
			
			
			
			$nestedData		= array();
			$nestedData[]	= $Nomor_Subcon;
			$nestedData[]	= $Date_Subcon;
			$nestedData[]	= $Name_Vendor;
			$nestedData[]	= $Addr_Vendor;
			$nestedData[]	= $Total_Subcon;
			$nestedData[]	= $Ket_Status;
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
	
	function preview_subcon_purchase_order(){
		$rows_Header	= $rows_Detail =  $rows_Delivery = $rows_Accomodation = array();
		
		if($this->input->post()){
			$Code_Process	= urldecode($this->input->post('code'));
			
			$rows_Header		= $this->db->get_where('subcon_purchase_orders',array('id'=>$Code_Process))->row();
			$rows_Detail		= $this->db->get_where('subcon_purchase_order_details',array('subcon_purchase_order_id'=>$Code_Process))->result();
			$rows_Delivery		= $this->db->get_where('subcon_purchase_order_deliveries',array('subcon_purchase_order_id'=>$Code_Process))->result();
			$rows_Accomodation	= $this->db->get_where('subcon_accommodations',array('subcon_purchase_order_id'=>$Code_Process))->result();
			
		}
		
		
		$data = array(
			'title'			=> 'SUBCON PURCHASE ORDER PREVIEW',
			'action'		=> 'preview_subcon_purchase_order',
			'akses_menu'	=> $this->Arr_Akses,
			'rows_header'	=> $rows_Header,
			'rows_detail'	=> $rows_Detail,
			'rows_delivery'	=> $rows_Delivery,
			'rows_akomodasi'=> $rows_Accomodation,
			'category'		=> 'view'
		);
		
		$this->load->view($this->folder.'/v_subcon_purchase_order_preview',$data);
	}
	
	function cancel_subcon_purchase_order(){
		$rows_Header	= $rows_Detail =  $rows_Delivery = $rows_Accomodation = array();
		
		if($this->input->post()){
			$Code_Process	= urldecode($this->input->post('code'));
			
			$rows_Header		= $this->db->get_where('subcon_purchase_orders',array('id'=>$Code_Process))->row();
			$rows_Detail		= $this->db->get_where('subcon_purchase_order_details',array('subcon_purchase_order_id'=>$Code_Process))->result();
			$rows_Delivery		= $this->db->get_where('subcon_purchase_order_deliveries',array('subcon_purchase_order_id'=>$Code_Process))->result();
			$rows_Accomodation	= $this->db->get_where('subcon_accommodations',array('subcon_purchase_order_id'=>$Code_Process))->result();
			
		}
		
		
		$data = array(
			'title'			=> 'SUBCON PURCHASE ORDER CANCELLATION',
			'action'		=> 'cancel_subcon_purchase_order',
			'akses_menu'	=> $this->Arr_Akses,
			'rows_header'	=> $rows_Header,
			'rows_detail'	=> $rows_Detail,
			'rows_delivery'	=> $rows_Delivery,
			'rows_akomodasi'=> $rows_Accomodation,
			'category'		=> 'cancel'
		);
		
		$this->load->view($this->folder.'/v_subcon_purchase_order_preview',$data);
	}
	
	function save_cancel_subcon_purchase_order(){
		$rows_Return	= array(
			'status'		=> '2',
			'pesan'			=> 'No record was found...'
		);
		if($this->input->post()){
			//echo"<pre>";print_r($this->input->post());exit;
			$Code_Process		= $this->input->post('code_process');
			$Cancel_By			= $this->session->userdata('siscal_userid');
			$Cancel_Date		= date('Y-m-d H:i:s');
			$Cancel_Reason		= strtoupper($this->input->post('cancel_reason'));
			
			$OK_Cancel			= 1;
			$Pesan_Error		= '';
			$rows_Header		= $this->db->get_where('subcon_purchase_orders',array('id'=>$Code_Process))->row();
			if($rows_Header){
				if($rows_Header->sts_subcon !== 'OPN'){
					$OK_Cancel			= 0;
					$Pesan_Error		= 'Data Has Been Modified By Other Process...';
				}
			}else{
				$OK_Cancel			= 0;
				$Pesan_Error		= 'Subcon Purchase Order Not Found...';
			}
			
			if($OK_Cancel === 1){
				$this->db->trans_begin();
				$rows_Letter		= $this->master_model->getArray('subcon_purchase_order_details', array('subcon_purchase_order_id'=>$Code_Process), 'letter_order_detail_id', 'letter_order_detail_id');
				$Upd_Header			= array(
					'sts_subcon'		=> 'CNC',
					'cancel_reason'		=> $Cancel_Reason,
					'cancel_by'			=> $Cancel_By,
					'cancel_date'		=> $Cancel_Date
				);
				$Has_Upd_Head = $this->db->update('subcon_purchase_orders',$Upd_Header,array('id'=>$Code_Process));
				if($Has_Upd_Head !== true){
					$Pesan_Error	= 'Error Update Subcon Purchase Order...';
				}
				
				if($rows_Letter){
					$Impl_Letter	= implode("','",$rows_Letter);
					$Upd_Letter		= "UPDATE letter_order_details SET flag_subcon_process = 'N' WHERE id IN('".$Impl_Letter."')";
					$Has_Upd_Letter = $this->db->query($Upd_Letter);
					if($Has_Upd_Letter !== true){
						$Pesan_Error	= 'Error Update Letter Order Detail...';
					}
				}
				
				if ($this->db->trans_status() !== TRUE || !empty($Pesan_Error)) {
					$this->db->trans_rollback();
					$rows_Return		= array(
						'status'		=> 2,
						'pesan'			=> $Pesan_Error
					);
				} else {
					$this->db->trans_commit();
					$rows_Return		= array(
						'status'		=> 1,
						'pesan'			=> 'Save process success. Thank you & have a nice day......'
					);
					history('Cancellation Subcon Purchase Order ' . $Code_Process);
				}
				
			}else{
				$rows_Return	= array(
					'status'		=> '2',
					'pesan'			=> $Pesan_Error
				);
			}
		}
		
		echo json_encode($rows_Return);
	}
	
	function print_subcon_purchase_order(){
		$rows_Header	= $rows_Detail = $rows_Delivery = $rows_Accomodation = $rows_User = $rows_Supplier = array();
		if($this->input->get()){
			$Code_Process	= urldecode($this->input->get('nomor_po'));
			if($Code_Process){
				$rows_Header		= $this->db->get_where('subcon_purchase_orders',array('id'=>$Code_Process))->row_array();
				$rows_Supplier		= $this->db->get_where('suppliers',array('id'=>$rows_Header['supplier_id']))->row_array();
				$rows_Detail		= $this->db->get_where('subcon_purchase_order_details',array('subcon_purchase_order_id'=>$Code_Process))->result_array();
				$rows_Delivery		= $this->db->get_where('subcon_purchase_order_deliveries',array('subcon_purchase_order_id'=>$Code_Process))->result_array();
				$rows_Accomodation	= $this->db->get_where('subcon_accommodations',array('subcon_purchase_order_id'=>$Code_Process))->result_array();
				
				$User_Approve		= 'USR.058';
				if (isset($rows_Header['approve_by']) && !empty($rows_Header['approve_by'])) {
					$User_Approve	= $rows_Header['approve_by'];
				}
				
				$rows_User	= array(
					'nama'			=> '',
					'ttd_file'		=> ''
				);
				
				$det_User			= $this->db->get_where('users',array('id'=>$User_Approve))->row();
				if(!empty($det_User) && !empty($det_User->member_id)){
					$rows_Member	= $this->db->get_where('members',array('id'=>$det_User->member_id))->row();
					if($rows_Member){
						$rows_User	= array(
							'nama'			=> $rows_Member->nama,
							'ttd_file'		=> $rows_Member->ttd_file
						);
					}
				}
			}
		}
		
		if($rows_Header){
			$data = array(
				'title'			=> 'PRINT SUBCON PURCHASE ORDER',
				'action'		=> 'print_subcon_purchase_order',
				'akses_menu'	=> $this->Arr_Akses,
				'rows_header'	=> $rows_Header,
				'rows_detail'	=> $rows_Detail,
				'rows_delivery'	=> $rows_Delivery,
				'rows_akomodasi'=> $rows_Accomodation,
				'rows_user'		=> $rows_User,
				'printby'		=> $this->session->userdata('siscal_username'),
				'today' 		=> date("Y-m-d H:i:s"),
				'rows_supplier' => $rows_Supplier
			);
			
			$this->load->view($this->folder.'/v_subcon_purchase_order_print',$data);
		}else{
			$this->session->set_flashdata("alert_data", "<div class=\"alert alert-warning\" id=\"flash-message\">No record was found....</div>");
			redirect(site_url('Subcon_purchase_order'));
		}
	}
	

	/*
	| -------------------------------- |
	|	   LIST OUTSTANDING SUBCON PO  |
	| -------------------------------- |
	*/

	function outs_subcon_purchase_order()
	{
		$rows_Supplier		= $this->master_model->getArray('suppliers', array('id !='=>'COMP-001'), 'id', 'supplier');
		$data = array(
			'title'			=> 'OUTSTANDING SUBCON PURCHASE ORDER',
			'action'		=> 'outs_subcon_purchase_order',
			'akses_menu'	=> $this->Arr_Akses,
			'rows_supplier'	=> $rows_Supplier
		);
		history('View Oustanding Subcon Purchase Order');
		$this->load->view($this->folder . '/v_subcon_purchase_order_outs', $data);
	}

	/*
	| -------------------------------- |
	|	 	DISPLAY OUTS PARTIAL       |
	| -------------------------------- |
	*/

	function display_outstanding_subcon_po()
	{
		$Find_Supplier	= $this->input->post('supplier_id');
		$rows_Akses		= $this->Arr_Akses;
		$WHERE			= "1=1";	
		if ($Find_Supplier) {
			if(!empty($WHERE))$WHERE	.=" AND ";
			$WHERE		.= "supplier_id='" . $Find_Supplier . "'";
		}		
		$requestData	= $_REQUEST;
		
		$like_value     = $requestData['search']['value'];
		$column_order   = $requestData['order'][0]['column'];
		$column_dir     = $requestData['order'][0]['dir'];
		$limit_start    = $requestData['start'];
		$limit_length   = $requestData['length'];
		
		$columns_order_by = array(
			1 => 'tool_id',
			2 => 'tool_name',
			3 => 'qty',
			4 => 'hpp',
			5 => 'tipe',
			6 => 'supplier_name',
			7 => 'no_so',
			8 => 'customer_name',
			9 => 'quotation_nomor',
			10 => 'pono',
			11 => 'member_name'
		);	
		
		
		if($this->db->escape_like_str($like_value)){
			if(!empty($WHERE))$WHERE	.=" AND ";
			$WHERE	.="(
						 tool_id LIKE '%".$this->db->escape_like_str($this->db->escape_like_str($like_value))."%'
						 OR tool_name LIKE '%".$this->db->escape_like_str($like_value)."%'
						 OR qty LIKE '%".$this->db->escape_like_str($like_value)."%'
						 OR hpp LIKE '%".$this->db->escape_like_str($like_value)."%'
						 OR tipe LIKE '%".$this->db->escape_like_str($like_value)."%'
						 OR supplier_name LIKE '%".$this->db->escape_like_str($like_value)."%'
						 OR no_so LIKE '%".$this->db->escape_like_str($like_value)."%'
						 OR customer_name LIKE '%".$this->db->escape_like_str($like_value)."%'
						 OR quotation_nomor LIKE '%".$this->db->escape_like_str($like_value)."%'
						 OR pono LIKE '%".$this->db->escape_like_str($like_value)."%'
						 OR member_name LIKE '%".$this->db->escape_like_str($like_value)."%'
						)";
		}
		
		
		$sql = "SELECT
					*,
					(@row:=@row+1) AS urut
				FROM
					view_subcon_orders,
				(SELECT @row:=0) r 
				WHERE ".$WHERE;
		//print_r($sql);exit();
		$fetch['totalData'] 	= $this->db->query($sql)->num_rows();
		$fetch['totalFiltered']	= $this->db->query($sql)->num_rows();

		$sql .= " ORDER BY ".$columns_order_by[$column_order]." ".$column_dir;
		$sql .= " LIMIT ".$limit_start." ,".$limit_length." ";

		$fetch['query'] = $this->db->query($sql);
		
		$totalData		= $fetch['totalData'];
		$totalFiltered	= $fetch['totalFiltered'];
		$query			= $fetch['query'];

		$data			= array();
		$urut1  		= 1;
		$urut2  		= 0;
		$Date_Compare	= date('Y-m-d H:i');
		foreach($query->result_array() as $row)
		{
			$total_data     = $totalData;
			$start_dari     = $requestData['start'];
			$asc_desc       = $requestData['order'][0]['dir'];
			if($asc_desc == 'asc')
			{
				$nomor = ($total_data - $start_dari) - $urut2;
			}
			if($asc_desc == 'desc')
			{
				$nomor = $urut1 + $start_dari;
			}
			
			$Code_Order			= $row['id'];
			$Code_Tool			= $row['tool_id'];
			$Name_Tool			= $row['tool_name'];
			$Qty_Tool			= $row['qty'];
			$HPP_Tool			= number_format($row['hpp']);
			$Vendor_Name		= strtoupper($row['supplier_name']);
			$Customer			= strtoupper($row['customer_name']);			
			$Type_Order			= $row['tipe'];
			$No_SO				= $row['no_so'];
			$Name_Marketing		= strtoupper($row['member_name']);
			$No_Quot			= strtoupper($row['quotation_nomor']);
			$No_PO				= $row['pono'];
			
			
			$Ket_Status			= '<span class="badge bg-aqua">SUBCON</span>';
			if(strtolower($Type_Order) == 'insitu'){
				$Ket_Status			= '<span class="badge bg-maroon">INSITU</span>';
			}
			
			$nestedData		= array();
			$nestedData[] 	= '<input type="checkbox" name="detPilih[]" class="chk_pilih" value="'.$Code_Order.'">';
			$nestedData[] 	= $Code_Tool;
			$nestedData[] 	= $Name_Tool;
			$nestedData[] 	= $Qty_Tool;
			$nestedData[] 	= $HPP_Tool;
			$nestedData[] 	= $Ket_Status;
			$nestedData[] 	= $Vendor_Name;
			$nestedData[]	= $No_SO;
			$nestedData[] 	= $Customer;
			$nestedData[] 	= $No_Quot;
			$nestedData[] 	= $No_PO;
			$nestedData[] 	= $Name_Marketing;
			$data[] 		= $nestedData;
			$urut1++;
			$urut2++;
		}

		$response = array(
			"draw"            => intval( $requestData['draw'] ),  
			"recordsTotal"    => intval( $totalData ),  
			"recordsFiltered" => intval( $totalFiltered ), 
			"data"            => $data
		);
	

		echo json_encode($response);  
	}

	
	

	/*
	| ------------------------------------ |
	|			SUBCON PROCESS			   |
	| ------------------------------------ |
	*/
	function subcon_purchase_order_process()
	{
		$Ok_Proses		= 0;
		$rows_Supplier	= $rows_Detail = array();
		
		$Link_Back		= 'outs_subcon_purchase_order';
		if ($this->input->get()) {
			$Code_Selected	= urldecode($this->input->get('code_order'));
			$Code_Supplier	= urldecode($this->input->get('supplier'));
			$Imp_Code		= str_replace("^", "','", $Code_Selected);
			
			$WHERE			= "supplier_id = '".$Code_Supplier."'
							   AND id IN('" . $Imp_Code . "')";
			
			


			$Query_Find		= "SELECT
									*
								FROM
									view_subcon_orders
								WHERE
								" . $WHERE;
			$rows_Detail	= $this->db->query($Query_Find)->result();
			if ($rows_Detail) {
				$rows_Supplier	= $this->db->get_where('suppliers', array('id' => $Code_Supplier))->row();				
				$Ok_Proses		= 1;
			}
		}

		if ($Ok_Proses == 1) { 
			$PPN_Val			= 10;
			$Query_Tax			= "SELECT * FROM master_taxes WHERE valid_date <= '".date('Y-m-d')."' ORDER BY valid_date DESC LIMIT 1";
			$rows_Tax			= $this->db->query($Query_Tax)->row();
			if($rows_Tax){
				$PPN_Val		= $rows_Tax->ppn_value;
			}
			$rows_Accomodation	= $this->master_model->getArray('accommodations', array('flag_active' => 'Y'), 'id', 'name');
			$data = array(
				'title'			=> 'SUBCON PURCHASE ORDER PROCESS',
				'action'		=> 'subcon_purchase_order_process',
				'rows_detail'	=> $rows_Detail,
				'rows_supplier'	=> $rows_Supplier,
				'rows_akomodasi'=> $rows_Accomodation,
				'prosen_ppn'	=> $PPN_Val
			);

			$this->load->view($this->folder . '/v_subcon_purchase_order_process', $data);
		} else {
			$this->session->set_flashdata("alert_data", "<div class=\"alert alert-warning\" id=\"flash-message\">No record was found....</div>");
			redirect(site_url('Subcon_purchase_order/' . $Link_Back));
		}
	}

	function list_master_insitu()
	{
		$rows_MasterDelivery	= $this->db->get_where('deliveries',array('flag_active'=>'Y'))->result_array();
		$data = array(
			'title'			=> 'MASTER AREA DELIVERIES',
			'action'		=> 'list_master_insitu',
			'rows_master'	=> $rows_MasterDelivery
		);

		$this->load->view($this->folder . '/v_area_delivery', $data);
	}

	

	function save_subcon_purchase_order_process()
	{
		$Arr_Return		= array();
		if ($this->input->post()) {
			//echo"<pre>";print_r($this->input->post());exit;
			$Pesan_Error	= '';
			$this->db->trans_begin();
			
			$rows_Accomod	= $this->master_model->getArray('accommodations', array('flag_active' => 'Y'), 'id', 'name');
			$Created_By		= $this->session->userdata('siscal_userid');
			$Created_Date	= date('Y-m-d H:i:s');
			
			$PO_Date		= date('Y-m-d');
			$PO_Year		= date('Y');
			$PO_Month		= date('m');
			$Code_Supplier	= $this->input->post('supplier_id');
			$Name_Supplier	= strtoupper($this->input->post('supplier_name'));
			$Addr_Supplier	= $this->input->post('address');
			$Name_PIC		= strtoupper($this->input->post('pic_name'));
			$Exc_PPN		= $this->input->post('exc_ppn');
			$detDetail		= $this->input->post('detDetail');
			$detInsitu		= $detAkmodasi = array();
			if($this->input->post('detInsitu')){
				$detInsitu		= $this->input->post('detInsitu');
			}
			
			if($this->input->post('detAkomodasi')){
				$detAkmodasi		= $this->input->post('detAkomodasi');
			}
			
			$Total_DPP		= str_replace(',','',$this->input->post('total_dpp'));
			$PPN			= str_replace(',','',$this->input->post('ppn'));
			$Grand_Total	= str_replace(',','',$this->input->post('grand_total'));
			$Total_Akomodasi = $Total_Insitu = 0;
			if($this->input->post('total_insitu')){
				$Total_Insitu	= str_replace(',','',$this->input->post('total_insitu'));
			}
			
			if($this->input->post('total_akomodasi')){
				$Total_Akomodasi	= str_replace(',','',$this->input->post('total_akomodasi'));
			}
			
			
			
			$Urut_Code	= $Urut_Nomor	= 1;
			$Qry_Urut			= "SELECT
										MAX(
											CAST(
												SUBSTRING_INDEX(id, '-' ,- 1) AS UNSIGNED
											)
										) AS code_urut
									FROM
										subcon_purchase_orders
									WHERE
										datet LIKE '".$PO_Year."-%'";
			$rows_Urut_Code		= $this->db->query($Qry_Urut)->row();
			if($rows_Urut_Code){
				$Urut_Code		= intval($rows_Urut_Code->code_urut) + 1;
			}
			
			$Code_Order			= 'SUB' . date('m') . '-V3' . date('y') . '-' . sprintf('%05d', $Urut_Code);
			
			$Qry_Urut_Nomor		= "SELECT
										MAX(
											CAST(
												SUBSTRING_INDEX(subcon_pono, '/' ,1) AS UNSIGNED
											)
										) AS nomor_urut
									FROM
										subcon_purchase_orders
									WHERE
										datet LIKE '".date('Y-m-')."%'";
			$rows_Urut_Nomor		= $this->db->query($Qry_Urut_Nomor)->row();
			if($rows_Urut_Nomor){
				$Urut_Nomor		= intval($rows_Urut_Nomor->nomor_urut) + 1;
			}
			$romawi				= getRomawi($PO_Month);
			$Nomor_Order		= sprintf('%05d', $Urut_Nomor) . '/SUB-V3/CAL-PO/' . $romawi . '/' . date('y');
			
			
				
			$Flag_Approve				= 'N';
			$total_dpp					= 0;
			$total_disc					= 0;
			
			
			
			if($detDetail){
				$int_Tool	= 0;
				$Arr_Update	= array();
				foreach($detDetail as $keyDet=>$valDet){
					$int_Tool++;
					
					$dpp									= floatval(str_replace(',', '', $valDet['total']));
					$diskon									= round($valDet['discount'] * $dpp / 100);
					$hpp_sub								= str_replace(',', '', $valDet['hpp']);
					$harga_barang_sub						= str_replace(',', '', $valDet['price']);
					$harga_net_sub							= round((100 - $valDet['discount']) * $harga_barang_sub / 100);
					
					
					
					$Ins_Detail								= array();
					$Ins_Detail['id']						= $Code_Order . '-' . $int_Tool;
					$Ins_Detail['subcon_purchase_order_id']	= $Code_Order;
					$Ins_Detail['tool_id']					= $valDet['tool_id'];
					$Ins_Detail['tool_name']				= $valDet['tool_name'];
					$Ins_Detail['qty']						= $valDet['qty'];
					$Ins_Detail['hpp']						= $valDet['hpp'];
					$Ins_Detail['price']					= str_replace(',', '', $valDet['price']);
					$Ins_Detail['discount']					= $valDet['discount'];
					$Ins_Detail['total']					= $dpp;
					$Ins_Detail['quotation_detail_id']		= $valDet['quotation_detail_id'];
					$Ins_Detail['quotation']				= $valDet['quotation'];
					$Ins_Detail['letter_order_detail_id']	= $valDet['letter_order_detail_id'];
					$Ins_Detail['letter_order_id']			= $valDet['letter_order_id'];
					$Ins_Detail['customer_id']				= $valDet['customer_id'];
					$Ins_Detail['customer_name']			= $valDet['customer_name'];
					$Ins_Detail['descr']					= $valDet['descr'];
					$Ins_Detail['notes']					= $valDet['notes'];
					$Ins_Detail['flag_insitu']				= $valDet['flag_Insitu'];

					$Has_Ins_Detail		= $this->db->insert('subcon_purchase_order_details', $Ins_Detail);
					if($Has_Ins_Detail !== TRUE) {
						$Pesan_Error	= 'Error Insert Subcon Purchase Detail...';
					}
					
					$Arr_Update[$int_Tool]								= $valDet['letter_order_detail_id'];
					
					$rows_QuotDet	= $this->db->get_where('quotation_details',array('id'=>$valDet['quotation_detail_id']))->row_array();
					if($rows_QuotDet){
						$harga_quot									= $rows_QuotDet['price'];
						$disc_quot									= (isset($rows_QuotDet['discount']) && $rows_QuotDet['discount']) ? $rows_QuotDet['discount'] : 0;
						$harga_net_quot								= round((100 - $disc_quot) * $harga_quot /  100);
					}
					
					if ($harga_net_sub > ($harga_net_quot * 0.5)) {
						$Flag_Approve		= 'Y';
					}
					$total_dpp										+= $dpp;
					$total_disc										+= $diskon;
				}
				unset($detDetail);
				
				if($Arr_Update){
					$Impl_LetterDet		= implode("','",$Arr_Update);
					$Upd_LetterDet		= "UPDATE letter_order_details SET flag_subcon_process = 'Y' WHERE id IN('".$Impl_LetterDet."')";
					$Has_Upd_LetterDet	= $this->db->query($Upd_LetterDet);
					if($Has_Ins_Detail !== TRUE) {
						$Pesan_Error	= 'Error Insert Subcon Purchase Detail...';
					}
					
				}
			}
			
			if($detInsitu){
				$Int_Insitu	= 0;
				foreach($detInsitu as $keyIns=>$valIns){
					$Int_Insitu++;
					$Fee			= $valIns['fee'];
					$Hari			= $valIns['day'];
					$DiskI			= (isset($valIns['diskon']) && $valIns['diskon']) ? str_replace(',', '', $valIns['diskon']) : 0;
					$TotalI			= $Fee * $Hari;
					$NettI			= (isset($valIns['total']) && $valIns['total']) ? str_replace(',', '', $valIns['total']) : 0;
					
					$Ins_Insitu		= array(
						'id'						=> $Code_Order.'-'.$Int_Insitu,
						'subcon_purchase_order_id'	=> $Code_Order,
						'delivery_id'				=> $valIns['delivery_id'],
						'delivery_name'				=> $valIns['delivery_name'],
						'fee'						=> $valIns['fee'],
						'day'						=> str_replace(',','',$valIns['day']),
						'diskon'					=> $DiskI,
						'total'						=> $NettI
					);
					
					$Has_Ins_Insitu		= $this->db->insert('subcon_purchase_order_deliveries', $Ins_Insitu);
					if($Has_Ins_Insitu !== TRUE) {
						$Pesan_Error	= 'Error Insert Subcon Purchase Delivery...';
					}
					
					$total_dpp										+= $TotalI;
					$total_disc										+= $DiskI;
				}
			}
			
			if($detAkmodasi){
				$intAkom	= 0;
				foreach($detAkmodasi as $keyAkom=>$valAkom){
					$intAkom++;
					
					$Code_Akomodasi		= $valAkom['accommodation_id'];
					$Name_Akomodasi		= $rows_Accomod[$Code_Akomodasi];
					$Nilai_Akomodasi	= (isset($valAkom['nilai']) && $valAkom['nilai']) ? str_replace(',', '', $valAkom['nilai']) : 0;
					$Disk_Akomodasi		= (isset($valAkom['diskon']) && $valAkom['diskon']) ? str_replace(',', '', $valAkom['diskon']) : 0;
					$Total_Akomodasi	= (isset($valAkom['total']) && $valAkom['total']) ? str_replace(',', '', $valAkom['total']) : 0;
					
					$Ins_Akomodasi		= array(
						'id'						=> $Code_Order.'-'.$intAkom,
						'subcon_purchase_order_id'	=> $Code_Order,
						'accommodation_id'			=> $Code_Akomodasi,
						'accommodation_name'		=> $Name_Akomodasi,
						'nilai'						=> $Nilai_Akomodasi,
						'diskon'					=> $Disk_Akomodasi,
						'total'						=> $Total_Akomodasi
					);
					
					$Has_Ins_Akomodasi	= $this->db->insert('subcon_accommodations', $Ins_Akomodasi);
					if($Has_Ins_Akomodasi !== TRUE) {
						$Pesan_Error	= 'Error Insert Subcon Purchase Accommodation...';
					}
					
					$total_dpp			+= $Nilai_Akomodasi;
					$total_disc			+= $Disk_Akomodasi;
					
					
					
				}
			}
			
			$Ins_Header		= array(
				'id'					=> $Code_Order,
				'old_id'				=> $Code_Order,
				'subcon_pono'			=> $Nomor_Order,
				'datet'					=> $PO_Date,
				'supplier_id'			=> $Code_Supplier,
				'supplier_name'			=> $Name_Supplier,
				'address'				=> $Addr_Supplier,
				'pic_name'				=> $Name_PIC,
				'dpp_after_discount'	=> $Total_DPP,
				'insitu'				=> $Total_Insitu,
				'akomodasi'				=> $Total_Akomodasi,
				'ppn'					=> $PPN,
				'grand_tot'				=> $Grand_Total,
				'exc_ppn'				=> $Exc_PPN,
				'dpp'					=> $total_dpp,
				'discount'				=> $total_disc,
				'sts_subcon'			=> 'OPN',
				'created_date'			=> $Created_Date,
				'created_by'			=> $Created_By
			);
			
			/*
			if($Flag_Approve=='N'){
				$Ins_Header['sts_subcon']		= 'APV';
			}
			*/
			
			$Has_Ins_Header	= $this->db->insert('subcon_purchase_orders', $Ins_Header);
			if($Has_Ins_Header !== TRUE) {
				$Pesan_Error	= 'Error Insert Subcon Purchase Header...';
			}
			if ($this->db->trans_status() !== TRUE || !empty($Pesan_Error)) {
				$this->db->trans_rollback();
				$Arr_Return		= array(
					'status'		=> 2,
					'pesan'			=> 'Save Process  Failed, ' . $Pesan_Error
				);
			} else {
				$this->db->trans_commit();
				$Arr_Return		= array(
					'status'		=> 1,
					'pesan'			=> 'Save process success. Thank you & have a nice day......',
					'code'			=> $Code_Order
				);
				history('Add PO Subcon ' . $Nomor_Order);
			}			
		} else {
			$Arr_Return		= array(
				'status'		=> 2,
				'pesan'			=> 'No Record Was Found........'
			);
		}
		echo json_encode($Arr_Return);
	}
	function download_outs_list(){
		set_time_limit(0);
		$this->load->library("PHPExcel");
		$objPHPExcel	= new PHPExcel();
		$Judul			= "List Outstanding Subcon Order";
		$WHERE			= "1=1";
		if($this->input->get()){
			$Code_Supplier	= urldecode($this->input->get('supplier'));
			if($Code_Supplier){
				if(!empty($WHERE))$WHERE	.=" AND ";
				$WHERE	.="supplier_id = '".$Code_Supplier."'";
				
				$rows_Supplier	= $this->db->get_where('suppliers', array('id' => $Code_Supplier))->row();
				$Judul			.=" ".$rows_Supplier->supplier;
			}
			
			
		}
		
		$Query_Outs		= "SELECT * FROM view_subcon_orders WHERE ".$WHERE;
		$rows_Header	= $this->db->query($Query_Outs)->result_array();
		
		
		$style_header = array(
			'borders' => array(
				'allborders' => array(
					  'style' => PHPExcel_Style_Border::BORDER_THIN,
					  'color' => array('rgb'=>'1006A3')
				  )
			),
			'fill' => array(
				'type' => PHPExcel_Style_Fill::FILL_SOLID,
				'color' => array('rgb'=>'E1E0F7'),
			),
			'font' => array(
				'bold' => true,
			),
			'alignment' => array(
				'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
				'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER
			)
		);

		$styleArray = array(					  
			  'alignment' => array(
				'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER
			  )
		  );				
		  $styleArray1 = array(
			  'borders' => array(
				  'allborders' => array(
					  'style' => PHPExcel_Style_Border::BORDER_THIN
				  )
			  ),
			  'alignment' => array(
				'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_LEFT,
				'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER
			  )
		  );
		  $styleArray2 = array(
			  'borders' => array(
				  'allborders' => array(
					  'style' => PHPExcel_Style_Border::BORDER_THIN
				  )
			  ),
			  'alignment' => array(
				'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
				'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER
			  )
		  );

		  
		  
		$Row	=1;
		$NewRow	=$Row+1;
		$sheet 	= $objPHPExcel->getActiveSheet();

		$sheet->setCellValue('A'.$Row, $Judul);
		$sheet->getStyle('A'.$Row.':L'.$NewRow)->applyFromArray($style_header);
		$sheet->mergeCells('A'.$Row.':L'.$NewRow);

		$NewRow++;
		$NewRow++;
		$sheet->setCellValue('A'.$NewRow, 'No');	
		$sheet->getStyle('A'.$NewRow)->applyFromArray($style_header);

		$sheet->setCellValue('B'.$NewRow, 'Kode Alat');	
		$sheet->getStyle('B'.$NewRow)->applyFromArray($style_header);

		$sheet->setCellValue('C'.$NewRow, 'Nama Alat');	
		$sheet->getStyle('C'.$NewRow)->applyFromArray($style_header);

		$sheet->setCellValue('D'.$NewRow, 'Qty');	
		$sheet->getStyle('D'.$NewRow)->applyFromArray($style_header);

		$sheet->setCellValue('E'.$NewRow, 'HPP');	
		$sheet->getStyle('E'.$NewRow)->applyFromArray($style_header);

		$sheet->setCellValue('F'.$NewRow, 'Tipe');	
		$sheet->getStyle('F'.$NewRow)->applyFromArray($style_header);

		$sheet->setCellValue('G'.$NewRow, 'Subcon');	
		$sheet->getStyle('G'.$NewRow)->applyFromArray($style_header);

		$sheet->setCellValue('H'.$NewRow, 'No SO');	
		$sheet->getStyle('H'.$NewRow)->applyFromArray($style_header);

		$sheet->setCellValue('I'.$NewRow, 'Customer');	
		$sheet->getStyle('I'.$NewRow)->applyFromArray($style_header);

		$sheet->setCellValue('J'.$NewRow, 'No Quotation');	
		$sheet->getStyle('J'.$NewRow)->applyFromArray($style_header);

		$sheet->setCellValue('K'.$NewRow, 'No PO');	
		$sheet->getStyle('K'.$NewRow)->applyFromArray($style_header);

		$sheet->setCellValue('L'.$NewRow, 'Marketing');	
		$sheet->getStyle('L'.$NewRow)->applyFromArray($style_header);

		if($rows_Header){
			$loop	=0;
			foreach($rows_Header as $keyI=>$valI){
				$loop++;
				$NewRow++;
				$sheet->setCellValue('A'.$NewRow, $loop);				
				$sheet->getStyle('A'.$NewRow)->applyFromArray($styleArray);
				
				$sheet->setCellValue('B'.$NewRow, $valI['tool_id']);				
				$sheet->getStyle('B'.$NewRow)->applyFromArray($styleArray);
				
				$sheet->setCellValue('C'.$NewRow, $valI['tool_name']);				
				$sheet->getStyle('C'.$NewRow)->applyFromArray($styleArray);
				
				$sheet->setCellValue('D'.$NewRow, number_format($valI['qty']));				
				$sheet->getStyle('D'.$NewRow)->applyFromArray($styleArray);
				
				$sheet->setCellValue('E'.$NewRow, number_format($valI['hpp']));				
				$sheet->getStyle('E'.$NewRow)->applyFromArray($styleArray);
				
				$sheet->setCellValue('F'.$NewRow, $valI['tipe']);				
				$sheet->getStyle('F'.$NewRow)->applyFromArray($styleArray);
				
				$sheet->setCellValue('G'.$NewRow, $valI['supplier_name']);				
				$sheet->getStyle('G'.$NewRow)->applyFromArray($styleArray);
				
				$sheet->setCellValue('H'.$NewRow, $valI['no_so']);				
				$sheet->getStyle('H'.$NewRow)->applyFromArray($styleArray);
				
				$sheet->setCellValue('I'.$NewRow, $valI['customer_name']);				
				$sheet->getStyle('I'.$NewRow)->applyFromArray($styleArray);
				
				$sheet->setCellValue('J'.$NewRow, $valI['quotation_nomor']);				
				$sheet->getStyle('J'.$NewRow)->applyFromArray($styleArray);
				
				$sheet->setCellValue('K'.$NewRow, $valI['pono']);				
				$sheet->getStyle('K'.$NewRow)->applyFromArray($styleArray);
				
				$sheet->setCellValue('L'.$NewRow, $valI['member_name']);				
				$sheet->getStyle('L'.$NewRow)->applyFromArray($styleArray);
					
			}
		}


		$sheet->setTitle('Outs Subcon PO');       
		$objWriter		= PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
		//ob_end_clean();
		//sesuaikan headernya 
		header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
		header("Cache-Control: no-store, no-cache, must-revalidate");
		header("Cache-Control: post-check=0, pre-check=0", false);
		header("Pragma: no-cache");
		header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
		//ubah nama file saat diunduh
		header('Content-Disposition: attachment;filename="Outstanding_Subcon_Purchase_Order.xls"');
		//unduh file
		$objWriter->save("php://output");
		exit;
	}

	
}
