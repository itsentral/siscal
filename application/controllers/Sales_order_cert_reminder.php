<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Sales_order_cert_reminder extends CI_Controller { 
	public function __construct(){
        parent::__construct();	
		
		$this->load->database();
        if(!$this->session->userdata('isSISCALlogin')){
			redirect('login');
		}
		$this->load->model('master_model');
		$controller			= ucfirst(strtolower($this->uri->segment(1)));
		$this->Arr_Akses	= getAcccesmenu($controller);
		
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
			'title'			=> 'SALES ORDER REMINDER CERTIFICATE',
			'action'		=> 'index',
			'akses_menu'	=> $Arr_Akses
		);
		history('View Sales Order Reminder Certificate');
		$this->load->view($this->folder.'/v_sales_order_cert_reminder',$data);
	}
	
	
	/*
	| -------------------------------- |
	|	 	DISPLAY OUTS REMINDER      |
	| -------------------------------- |
	*/
	
	function outstanding_partial_po(){
		$Find_Cust		= $this->input->post('nocust');
		
		$rows_Akses		= $this->Arr_Akses;
		
		
		$requestData	= $_REQUEST;
		
		
		$Like_Value		= $requestData['search']['value'];
		$column_order	= $requestData['order'][0]['column'];
		$column_dir		= $requestData['order'][0]['dir'];
		$limit_start	= $requestData['start'];
		$limit_length	= $requestData['length'];
		
		$Find_Cust		= $this->input->post('nocust');
		
		$WHERE_Find		= "tot_proses > 0
							AND qty_total <= (tot_proses + tot_fail)
							AND (tot_proses - total_certificate ) <= 0";
		
		$Query_Sub		= $this->QueryProcess();
		
		if($Like_Value){
			if(!empty($WHERE_Find))$WHERE_Find	.=" AND ";
			$WHERE_Find	.="(
						no_so LIKE '%".$this->db->escape_like_str($Like_Value)."%'
						OR DATE_FORMAT(tgl_so,'%d-%m-%Y') LIKE '%".$this->db->escape_like_str($Like_Value)."%'
						OR customer_name LIKE '%".$this->db->escape_like_str($Like_Value)."%'
						OR pono LIKE '%".$this->db->escape_like_str($Like_Value)."%'
						OR quotation_nomor LIKE '%".$this->db->escape_like_str($Like_Value)."%'
						OR marketing_name LIKE '%".$this->db->escape_like_str($Like_Value)."%'
						)";
		}
		
		$sql = "SELECT
					*,
					(@row:=@row+1) AS nomor
					
				FROM
					(
					".$Query_Sub."
				) detail_invoice,
				(SELECT @row := 0) r 
				WHERE ".$WHERE_Find."
				";
		//print_r($sql);exit();
		$fetch['totalData'] 		= $this->db->query($sql)->num_rows();
		$fetch['totalFiltered']	= $this->db->query($sql)->num_rows();

		$columns_order_by = array(
			0 => 'no_so',
			1 => 'tgl_so',
			2 => 'customer_name',
			3 => 'quotation_nomor',
			4 => 'pono',
			5 => 'marketing_name'
		);

		$sql .= " ORDER BY ".$columns_order_by[$column_order]." ".$column_dir;
		$sql .= " LIMIT ".$limit_start." ,".$limit_length." ";
		
		$fetch['query'] = $this->db->query($sql);
		
		$totalData		= $fetch['totalData'];
		$totalFiltered	= $fetch['totalFiltered'];
		$query			= $fetch['query'];
		
		$data		= array();
        $urut1  	= 1;
        $urut2  	= 0;
		$Tgl_Now	= date('Y-m-d');
		$Tahun_Now	= date('Y');
		
		foreach($query->result_array() as $row)
		{
			$total_data     = $totalData;
            $start_dari     = $requestData['start'];
            $asc_desc       = $requestData['order'][0]['dir'];
			$nomor 			= $urut1 + $start_dari;
            
			
			$Code_Order		= $row['letter_order_id'];
			$No_Order		= $row['no_so'];
			$Tgl_Order		= date('d-m-Y',strtotime($row['tgl_so']));
			$Code_Quot		= $row['quotation_id'];
			$No_Quot		= $row['quotation_nomor'];
			$Tgl_Quot		= date('d-m-Y',strtotime($row['quotation_date']));
			$Code_Sales		= $row['marketing_id'];
			$Name_Sales		= $row['marketing_name'];
			$Code_Customer	= $row['customer_id'];
			$Name_Customer	= $row['customer_name'];
			$No_PO			= $row['pono'];
			
			$Template		="-";
			if($rows_Akses['create'] == '1' || $rows_Akses['update'] == '1'){
				$Template	= "<button type='button' class='btn btn-sm bg-orange-active' id='proses_reminder_".$Code_Order."' title='REMINDER CERTIFICATE' onClick='return CreateReminder(\"".$Code_Order."\");'> <i class='fa fa-bullhorn'></i> </button>";
				
				
			}
			
			$Ket_Status	= "<span class='badge bg-red-active'>INACTIVE</span>";
			$Query_User	=  "SELECT
								*
							FROM
								crm_users
							WHERE
								flag_active = '1'
							AND category = 'EXT'
							AND FIND_IN_SET('".$row['customer_id']."', custid) > 0";
			$rows_User	= $this->db->query($Query_User)->row();
			if($rows_User){
				$Ket_Status	= "<span class='badge bg-green-active'>ACTIVE</span>";
			}
			
			
			
			$nestedData 	= array(); 
			$nestedData[]	= $No_Order; 
			$nestedData[]	= $Tgl_Order;
			$nestedData[]	= $Name_Customer;
			$nestedData[]	= $No_Quot;
			$nestedData[]	= $No_PO;
			$nestedData[]	= $Name_Sales;
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
	
	
	function QueryProcess(){	
		$Query_process	= "SELECT
								x_detail_trans.letter_order_id AS id,
								x_detail_trans.letter_order_id,
								x_detail_trans.tgl_so,
								x_detail_trans.no_so,
								x_detail_trans.quotation_id,
								x_detail_trans.quotation_nomor,
								x_detail_trans.quotation_date,
								x_detail_trans.pono,
								x_detail_trans.customer_id,
								x_detail_trans.customer_name,
								x_detail_trans.marketing_name,
								x_detail_trans.marketing_id,
								sum(
									(
										CASE
										WHEN (
											x_detail_trans.`re_schedule` <> 'Y'
										) THEN
											x_detail_trans.`qty` - `x_detail_trans`.`re_qty`
										ELSE
											0
										END
									)
								) AS qty_total,
								sum(
									(
										CASE
										WHEN (
											x_detail_trans.`insitu` = 'N'
										) THEN
											x_detail_trans.`qty_rec`
										WHEN (
											x_detail_trans.`insitu` = 'Y'
											AND NOT (
												x_detail_trans.bast_rec_id IS NULL
												OR x_detail_trans.bast_rec_id = ''
												OR x_detail_trans.bast_rec_id = '-'
											)
										) THEN
											(
												x_detail_trans.`qty` - x_detail_trans.qty_reschedule
											)
										ELSE
											0
										END
									)
								) AS tot_real,
								SUM(x_detail_trans.qty_proses) AS tot_proses,
								SUM(x_detail_trans.qty_fail) AS tot_fail,
								SUM(
									x_detail_trans.tot_certificate
								) AS total_certificate
							FROM
								(
									SELECT
										trans_details.*, SUM(
											CASE
											WHEN trans_data_details.flag_proses = 'Y'
											AND trans_data_details.approve_certificate = 'APV' THEN
												1
											ELSE
												0
											END
										) AS tot_certificate
									FROM
										trans_details
									INNER JOIN trans_data_details ON trans_data_details.trans_detail_id = trans_details.id
									INNER JOIN letter_orders ON letter_orders.id = trans_details.letter_order_id
									WHERE
										letter_orders.send_notif_wa <> 'Y'
									AND trans_details.qty_proses > 0
									GROUP BY
										trans_details.id
								) AS x_detail_trans
							WHERE
								x_detail_trans.tot_certificate > 0
							GROUP BY
								x_detail_trans.letter_order_id";
		
		return $Query_process;
	}
	
	
	
	
	/*
	| ------------------------------------ |
	|			REMINDER PROCESS		   |
	| ------------------------------------ |
	*/
	function sales_order_reminder_process(){
		
		$rows_User		= $rows_Detail = $rows_Order = $rows_Quot = array();
		
		if($this->input->get()){
			$Code_Order	= urldecode($this->input->get('code_order'));
			$rows_Order	= $this->db->get_where('letter_orders',array('id'=>$Code_Order))->row();
			$Query_User	=  "SELECT
								*
							FROM
								crm_users
							WHERE
								flag_active = '1'
							AND category = 'EXT'
							AND FIND_IN_SET('".$rows_Order->customer_id."', custid) > 0";
			$rows_User	= $this->db->query($Query_User)->row();
			$rows_Quot	= $this->db->get_where('quotations',array('id'=>$rows_Order->quotation_id))->row();
			
			
			$Query_Find		= "SELECT
									det_trans.*
								FROM
									trans_data_details det_trans
								INNER JOIN trans_details head_trans ON det_trans.trans_detail_id = head_trans.id
								WHERE
									det_trans.approve_certificate = 'APV'
								AND det_trans.flag_proses = 'Y'
								AND head_trans.letter_order_id = '".$Code_Order."'";
			$rows_Detail	= $this->db->query($Query_Find)->result();
		}
		$data = array(
			'title'			=> 'SALES ORDER CERTIFICATE REMINDER PROCESS',
			'action'		=> 'sales_order_reminder_process',
			'rows_detail'	=> $rows_Detail,
			'rows_user'		=> $rows_User,
			'rows_quot'		=> $rows_Quot,
			'rows_order'	=> $rows_Order
		);
		
		$this->load->view($this->folder.'/v_sales_order_cert_reminder_process',$data);
		
		
	}
	
	function view_detail_sertifikat(){
		$rows_Header = $rows_Detail = array();
		$Code_Back	= '';
		if($this->input->post()){
			$Code_Detail 	= urldecode($this->input->post('code'));
			$rows_Detail	= $this->db->get_where('trans_data_details',array('id'=>$Code_Detail))->result();
			$rows_Header	= $this->db->get_where('trans_details',array('id'=>$rows_Detail[0]->trans_detail_id))->result();
			$Code_Back		= $rows_Header[0]->letter_order_id;
		}
		$Arr_Akses			= $this->Arr_Akses;
		$data = array(
			'title'			=> 'DETAIL CERTIFICATE TOOL',
			'action'		=> 'view_detail_sertifikat',
			'akses_menu'	=> $Arr_Akses,
			'rows_detail'	=> $rows_Detail,
			'rows_header'	=> $rows_Header,
			'Code_Back'		=> $Code_Back
		);
		
		$this->load->view('Bast_Certificate/v_reopen_certificate_tool',$data);
		
	}
	
	function save_sales_order_reminder_certificate(){
		$Arr_Return		= array(
			'status'		=> 2,
			'pesan'			=> 'No record was found...'
		);
		if($this->input->post()){
			//echo"<pre>";print_r($this->input->post());exit;
			$Created_By		= $this->session->userdata('siscal_userid');
			$Created_Date	= date('Y-m-d H:i:s');
			
			$Code_Order		= $this->input->post('code_order');
			$Nomor_Order	= $this->input->post('no_so');
			$Nomor_PO		= $this->input->post('pono');
			$Customer_Name	= $this->input->post('customer_name');
			$Customer_Code	= $this->input->post('customer_id');
			
			$Flag_Add		= $this->input->post('flag_add');
			$Code_Phone		= '';
			if($Flag_Add == 'Y'){
				$Code_Phone	= $this->input->post('account_phone_code');
				$User_Name	= $this->input->post('account_username');
				$User_Full	= $this->input->post('account_name');
				$Nomor_Phone= str_replace(array('+','-',' ','  '),'',$this->input->post('account_phone'));
				$Nomor_WA	= $Code_Phone.$Nomor_Phone;
			}else{
				$User_Name	= $this->input->post('account_username');
				$User_Full	= $this->input->post('account_name');
				$Nomor_Phone= str_replace(array('+','-',' ','  '),'',$this->input->post('account_phone'));
				$Nomor_WA	= $Code_Phone.$Nomor_Phone;
			}
			
			$rows_Order		= $this->db->get_where('letter_orders',array('id'=>$Code_Order))->row();
			
			$Pesan_Error	= '';
			$Ok_Process		= 1;
			
			if($rows_Order){
				$Code_Status	= $rows_Order->sts_so;
				$Flag_Reminder	= $rows_Order->send_notif_wa;
				if($Code_Status === 'CNC' || $Code_Status === 'REV' || $Flag_Reminder === 'Y'){
					$Pesan_Error	= 'Data has been modified by other process...';
					$Ok_Process		= 0;
				}
			}else{
				$Pesan_Error	= 'Sales order record was not found...';
				$Ok_Process		= 0;
			}
			
			if($Ok_Process === 0){
				$Arr_Return		= array(
					'status'		=> 2,
					'pesan'			=> $Pesan_Error
				);
			}else{
				$Pesan_Error	= '';
				$this->db->trans_begin();
				
				$Upd_Order_Header		= array(
					'send_notif_wa'	=> 'Y',
					'modified_by'	=> $Created_By,
					'modified_date'	=> $Created_Date
				);
				
				$Has_Upd_Order_Head	= $this->db->update('letter_orders',$Upd_Order_Header,array('id'=>$Code_Order));
				if($Has_Upd_Order_Head !== TRUE){
					$Pesan_Error	= 'Error Update Sales Order...';
				}
				
				if($Flag_Add === 'Y'){
					$Cust_Type		= 'CUSTOMER';
					$Code_Group		= '3';
					$Type_User		= 'EXT';
					
					$Password		= security_hash_crm($User_Name);
					
					# AMBIL KODE URUT #
					$Qry_Urut		= "SELECT
											MAX(
												CAST(
													SUBSTRING_INDEX(userid, '-', - 1) AS UNSIGNED
												)
											) AS urut
										FROM
											crm_users
										WHERE userid LIKE 'CRM-USER%'
										LIMIT 1";
					$Urut_User		= 1;
					$det_Urut		= $this->db->query($Qry_Urut)->result();
					if($det_Urut){
						$Urut_User	= $det_Urut[0]->urut + 1;
					}
					$Code_User		= 'CRM-USER-'.sprintf('%05d',$Urut_User);
					if($Urut_User >= 100000){
						$Code_User		= 'CRM-USER-'.$Urut_User;
					}
					
					$Ins_User_CRM		= array(
						'userid'			=> $Code_User,
						'username'			=> $User_Name,
						'phone'				=> $Nomor_WA,
						'phone_code'		=> $Code_Phone,
						'password'			=> $Password,
						'group_id'			=> $Code_Group,
						'category'			=> $Type_User,
						'name'				=> strtoupper($User_Full),
						'position'			=> NULL,
						'cust_type'			=> $Cust_Type,
						'custid'			=> $Customer_Code,
						'flag_active'		=> '1',
						'created_date'		=> $Created_Date,
						'created_by'		=> $Created_By
					);
					
					$Has_Ins_User	= $this->db->insert('crm_users',$Ins_User_CRM);
					if($Has_Ins_User !== TRUE){
						$Pesan_Error	= 'Error Insert User CRM...';
					}
				}
				
				if ($this->db->trans_status() !== TRUE || !empty($Pesan_Error)){
					$this->db->trans_rollback();
					$Arr_Return		= array(
						'status'		=> 2,
						'pesan'			=> 'Save Process  Failed, '.$Pesan_Error
					);
					
					history('Send Reminder Certificate '.$Code_Order.' - FAILED');
				}else{
					$this->db->trans_commit();
					$Arr_Return		= array(
						'status'		=> 1,
						'pesan'			=> 'Save process success. Thank you & have a nice day......'
					);
					history('Send Reminder Certificate '.$Code_Order.' - SUCCESS');
					
					## SEND WHATSAPP ##
					
					$Link_Approval		= 'https://sentral.dutastudy.com/Siscal_CRM';
				
					$Pesan_Whatsapp		= "# *Sentral Sistem Calibration* # \n\nDear *".strtoupper($User_Full)."*\n\nSertifikat alat atas _*".$Customer_Name."*_ dengan nomor PO *".$Nomor_PO."* dan nomor SO *".$Nomor_Order."* telah dapat didonwload melalui sistem *".$Link_Approval."*\n\nUntuk akses ke sistem tersebut, Bapak/Ibu dapat login menggunakan akun _*".$User_Name."*_  atau Nomor HP *".$Nomor_WA."*.";
					$Pesan_Whatsapp	   .= '\n\nTerima kasih.\n _This WA message automatically generated from System '.base_url().'_';
					
					$Arr_Balik			= Kirim_Whatsapp($Nomor_WA,$Pesan_Whatsapp);
					
				}
			}
			
		}else{
			$Arr_Return		= array(
				'status'		=> 2,
				'pesan'			=> 'No Record Was Found........'
			);
		}
		echo json_encode($Arr_Return);
	}
	
	
}