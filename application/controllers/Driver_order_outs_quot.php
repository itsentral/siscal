<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Driver_order_outs_quot extends CI_Controller { 
	public function __construct(){
        parent::__construct();	
		
		$this->load->database();
        if(!$this->session->userdata('isSISCALlogin')){
			redirect('login');
		}
		$this->load->model('master_model');
		$controller				= ucfirst(strtolower($this->uri->segment(1)));
		$this->Arr_Akses		= getAcccesmenu($controller);
		
		$this->folder			= 'Driver_order';
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
			'title'			=> 'DRIVER ORDER - CUSTOMER PICKUP TOOLS',
			'action'		=> 'index',
			'akses_menu'	=> $Arr_Akses
		);
		history('View List Incomplete PO - Driver Order');
		$this->load->view($this->folder.'/v_outs_incomplete_quotation',$data);
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
				$Template		="<a href='".site_url('Driver_order_outs_quot/create_driver_order?quotation='.urlencode($Code_Quot))."' class='btn btn-sm bg-navy-active' title='ADD DRIVER ORDER'> <i class='fa fa-long-arrow-right'></i> </a>";
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
	
	function create_driver_order(){
		$OK_Proses	= 0;
		$rows_Header	= $rows_Detail = $rows_Plant = $rows_Cust = array();
		
		if($this->input->get()){
			$Code_Quot		= urldecode($this->input->get('quotation'));
			$Code_Process	= urldecode($this->input->get('code_process'));
			
			$rows_Header	= $this->db->get_where('quotations',array('id'=>$Code_Quot))->row();
			$rows_Detail	= $this->db->get_where('quotation_details',array('quotation_id'=>$Code_Quot,'(qty - qty_so - qty_driver) >'=>0))->result();
			$Query_Plant	= "SELECT id,branch FROM plants WHERE customer_id = '".$rows_Header->customer_id."' AND NOT(branch IS NULL OR branch ='' OR branch='-')";
			$rows_Plant		= $this->db->query($Query_Plant)->result();
			$rows_Cust		= $this->db->get_where('customers',array('id'=>$rows_Header->customer_id))->row();
			if($rows_Detail){
				$OK_Proses	= 1;
			}
		}
		
		if($OK_Proses == 1){
			$data = array(
				'title'			=> 'DRIVER ORDER - CUSTOMER PICKUP TOOLS',
				'action'		=> 'create_driver_order',
				'akses_menu'	=> $this->Arr_Akses,
				'rows_header'	=> $rows_Header,
				'rows_detail'	=> $rows_Detail,
				'rows_plant'	=> $rows_Plant,
				'rows_cust'		=> $rows_Cust
			);
			
			$this->load->view($this->folder.'/v_outs_incomplete_quot_process',$data);
		}else{
			$this->session->set_flashdata("alert_data", "<div class=\"alert alert-warning\" id=\"flash-message\">No record was found to process....</div>");
			redirect(site_url('Driver_order_outs_quot'));
		}
	}
	
	
	
	function get_detail_comp_plant(){
		$response	= array(
			'alamat'	=> '',
			'nama'		=> '',
			'phone'		=> ''	
		);
		
		if($this->input->post()){
			$Code_Cari	= $this->input->post('plant');
			$Code_Cust	= $this->input->post('nocust');
			$WHERE_Code	= array(
				'id'				=> $Code_Cari,
				'customer_id'		=> $Code_Cust
			);
			
			$rows_Find	= $this->db->get_where('plants',$WHERE_Code)->row();
			if($rows_Find){
				$response	= array(
					'alamat'	=> $rows_Find->address,
					'nama'		=> strtoupper($rows_Find->pic),
					'phone'		=> $rows_Find->phone	
				);
				
			}
		}
		echo json_encode($response);
	}
	
	
	function save_create_driver_order(){
		$rows_Return	= array(
			'status'		=> 2,
			'pesan'			=> 'No Record was found...'
		);
		if($this->input->post()){
			//echo"<pre>";print_r($this->input->post());exit;
			
			$Created_By		= $this->session->userdata('siscal_userid');
			$Created_Name	= $this->session->userdata('siscal_username');
			$Created_Date	= date('Y-m-d H:i:s');
			
			$Plan_Date		= date('Y-m-d',strtotime($this->input->post('plan_date')));
			$Plan_Time		= $this->input->post('plan_time');
			$Notes			= strtoupper($this->input->post('notes'));
			$Nocust			= $this->input->post('customer_id');
			$Customer		= $this->input->post('customer_name');
			$Address		= $this->input->post('address');
			$PIC_Name		= $this->input->post('pic_name');
			$PIC_Phone		= $this->input->post('pic_phone');
			$detDetail		= $this->input->post('detDetail');
			$Code_Quot		= $this->input->post('quotation_id');
			
			## CEK APAKAH DATA MASIH OUTSTANDING ##
			$num_Find	= $this->db->get_where('quotation_details',array('quotation_id'=>$Code_Quot,'(qty - qty_so - qty_driver) >'=>0))->num_rows();
			if($num_Find <= 0){
				$rows_Return	= array(
					'status'		=> 2,
					'pesan'			=> 'No outstanding record was found...'
				);
			}else{
				$this->db->trans_begin();
				$Pesan_Error	= '';
				
				$Code_Order		= 'DRV-ORD-'.date('YmdHis');				
				$Urut_Order		= 1;
				$Month_Process	= date('m',strtotime($Plan_Date));
				$Year_Process	= date('Y',strtotime($Plan_Date));
				$MonthYear_Proc	= date('Y-m',strtotime($Plan_Date));
				
				$Query_Urut		= "SELECT
										MAX(
											CAST(
												SUBSTRING_INDEX(order_no, '/', 1) AS UNSIGNED
											)
										) AS urut
									FROM
										trans_driver_orders
									WHERE
										plan_date LIKE '".$MonthYear_Proc."%'";
				$rows_Urut	= $this->db->query($Query_Urut)->row();
				if($rows_Urut){
					$Urut_Order	= intval($rows_Urut->urut) + 1;
				}
				$Lable_Urut		= sprintf('%05d',$Urut_Order);
				if($Urut_Order > 99999){
					$Lable_Urut		= $Urut_Order;
				}
				
				$Nomor_Order	= $Lable_Urut.'/DRV-ORD/'.$Month_Process.'/'.$Year_Process;
				
				$Ins_Header		= array(
					'order_code'	=> $Code_Order,
					'order_no'		=> $Nomor_Order,
					'datet'			=> date('Y-m-d'),
					'plan_date'		=> $Plan_Date,
					'plan_time'		=> $Plan_Time,
					'company_code'	=> $Nocust,
					'company'		=> $Customer,
					'type_comp'		=> 'CUST',
					'category'		=> 'REC',
					'address'		=> $Address,
					'pic_name'		=> $PIC_Name,
					'pic_phone'		=> $PIC_Phone,
					'sts_order'		=> 'OPN',
					'notes'			=> $Notes,
					'created_by'	=> $Created_By,
					'created_date'	=> $Created_Date
				);
				
				$Has_Ins_Header	= $this->db->insert('trans_driver_orders',$Ins_Header);
				if($Has_Ins_Header !== TRUE){
					$Pesan_Error	= 'Error Insert Driver Order..';
				}
				$Ins_Upd_Quot	= array();
				if($detDetail){
					$intL	= 0;
					foreach($detDetail as $keyDetail=>$valDetail){
						$Qty_Pros	= $valDetail['qty'];
						if($Qty_Pros > 0){
							$intL++;
							$Code_Detail	= $Code_Order.'-'.$intL;
							$Code_Tool		= $valDetail['tool_id'];
							$Name_Tool		= $valDetail['tool_name'];
							$Code_QuotDet	= $valDetail['code_process'];
							
							$Ins_Detail		= array(
								'code_detail'	=> $Code_Detail,
								'order_code'	=> $Code_Order,
								'code_process'	=> $Code_QuotDet,
								'tool_id'		=> $Code_Tool,
								'tool_name'		=> $Name_Tool,
								'qty'			=> $Qty_Pros
							);
							
							## PROSES INSERT DRIVER ORDER DETAIL ##
							$Has_Ins_Detail	= $this->db->insert('trans_driver_order_details',$Ins_Detail);
							if($Has_Ins_Detail !== TRUE){
								$Pesan_Error	= 'Error Insert Driver Order Detail..';
							}
							
							## PROSES UPDATE QUOTATION DETAIL ##
							$Upd_Quot_Detail	= "UPDATE quotation_details SET qty_driver = qty_driver + ".$Qty_Pros." WHERE id = '".$Code_QuotDet."'";
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
					history('Driver Order Process - Pickup By Driver '.$Code_Order.' - '.$Pesan_Error);
				}else{
					$this->db->trans_commit();
					$rows_Return		= array(
						'status'		=> 1,
						'pesan'			=> 'Save process success. Thank you & have a nice day......'
					);
					history('Driver Order Process - Pickup By Driver '.$Code_Order);
				}				
			}			
		}
		echo json_encode($rows_Return);
	}
	
	
	
	
	
}