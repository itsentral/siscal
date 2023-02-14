<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Incentive_sales extends CI_Controller { 
	public function __construct(){
        parent::__construct();	
		
		$this->load->database();
        if(!$this->session->userdata('isSISCALlogin')){
			redirect('login');
		}
		$this->load->model('master_model');
		$controller			= ucfirst(strtolower($this->uri->segment(1)));
		$this->Arr_Akses	= getAcccesmenu($controller);
		
		$this->folder			= 'Incentive';
		$this->file_location	= $this->config->item('location_file');
    }

	public function index(){
		$Arr_Akses			= $this->Arr_Akses;
		if($Arr_Akses['read'] !='1'){
			$this->session->set_flashdata("alert_data", "<div class=\"alert alert-warning\" id=\"flash-message\">You Don't Have Right To Access This Page, Please Contact Your Administrator....</div>");
			redirect(site_url('dashboard'));
		}
		
		$rows_Sales		= $this->master_model->getArray('members',array('division_id'	=> 'DIV-001'),'id','nama');
		$data = array(
			'title'			=> 'MANAGE INCENTIVE SALESMAN',
			'action'		=> 'index',
			'akses_menu'	=> $Arr_Akses,
			'rows_sales'	=> $rows_Sales
		);
		history('View List Incentive Salesman');
		$this->load->view($this->folder.'/v_incentive_sales',$data);
	}
	
	/*
	| -------------------------------- |
	|     DISPLAY LIST CPR INCENTIVE   |
	| -------------------------------- |
	*/
	function get_data_display(){
		$Arr_Akses		= $this->Arr_Akses;
		
		$Sales_Find		= $this->input->post('sales');
		$Month_Find		= $this->input->post('bulan');
		$Year_Find		= $this->input->post('tahun');
		
		$requestData	= $_REQUEST;
		$like_value     = $requestData['search']['value'];
        $column_order   = $requestData['order'][0]['column'];
        $column_dir     = $requestData['order'][0]['dir'];
        $limit_start    = $requestData['start'];
        $limit_length   = $requestData['length'];
		
		$columns_order_by = array(
			0 => 'id',
			1 => 'datet',
			2 => 'member_name',
			3 => 'descr',
			4 => 'total',
			5 => 'status'
		);
		
		$WHERE		= '1=1';
		
		if($Sales_Find){
			if(!empty($WHERE))$WHERE	.=" AND ";
			$WHERE	.="member_id = '".$Sales_Find."'";
		}
		
		
		
		if($Month_Find){
			if(!empty($WHERE))$WHERE	.=" AND ";
			$WHERE	.="MONTH(datet) = '".$Month_Find."'";
		}
		
		if($Year_Find){
			if(!empty($WHERE))$WHERE	.=" AND ";
			$WHERE	.="YEAR(datet) = '".$Year_Find."'";
		}
		
		if($like_value){
			if(!empty($WHERE))$WHERE	.=" AND ";
			$WHERE	.="(
						id LIKE '%".$this->db->escape_like_str($like_value)."%'
						OR DATE_FORMAT(datet,'%d-%m-%Y') LIKE '%".$this->db->escape_like_str($like_value)."%'
						OR member_name LIKE '%".$this->db->escape_like_str($like_value)."%'
						OR descr LIKE '%".$this->db->escape_like_str($like_value)."%'
						OR total LIKE '%".$this->db->escape_like_str($like_value)."%'
						OR status LIKE '%".$this->db->escape_like_str($like_value)."%'
						)";
		}
		
		
		
		
		$sql = "SELECT
					*,
					(@row:=@row+1) AS urut
				FROM
					sales_incentives,
				(SELECT @row:=0) r 
				WHERE ".$WHERE;
		//print_r($sql);exit();
		$fetch['totalData'] 	= $this->db->query($sql)->num_rows();
		$fetch['totalFiltered']	= $this->db->query($sql)->num_rows();

		

		$sql .= " ORDER BY ".$columns_order_by[$column_order]." ".$column_dir." ";
		$sql .= " LIMIT ".$limit_start." ,".$limit_length." ";
		//print_r($sql);exit();
		$fetch['query'] = $this->db->query($sql);
		
		$totalData		= $fetch['totalData'];
		$totalFiltered	= $fetch['totalFiltered'];
		$query			= $fetch['query'];
		
		$data		= array();
        $urut1  	= 1;
        $urut2  	= 0;
		$Periode_Now= date('Y-m');
		$Tahun_Now	= date('Y');
		$Pembagi	= 1000;
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
			
			$CPR_No				= $row['id'];
			$CPR_Date			= date('d-m-Y',strtotime($row['datet']));
			
			$Technician			= $row['member_name'];
			$Tech_Code			= $row['member_id'];
			$Keterangan			= $row['descr'];
			$Total				= $row['total'];
			$Status				= $row['status'];
			
			
			if($Status == 'OPN'){
				$Ket_Status		= '<span class="badge bg-green">WAITING PAYMENT</span>';
				
			}else if($Status == 'CNC'){
				$Ket_Status		= '<span class="badge bg-orange">CANCELED</span>';
				
			}else if($Status == 'BUK'){
				$Ket_Status		= '<span class="badge bg-red">ALREADY PAID</span>';
				
			}
			
			
			$Template		= '<button type="button" class="btn btn-sm bg-navy-active" onClick = "ActionIncentive({code:\''.$CPR_No.'\',action :\'view_sales_cpr\',title:\'VIEW DETAIL\'});" title="VIEW DETAIL"> <i class="fa fa-search"></i> </button>';
			
			if(($Arr_Akses['delete'] == '1' || $Arr_Akses['update'] == '1') && $Status == 'OPN'){
				$Template		.= '&nbsp;&nbsp;<button type="button" class="btn btn-sm bg-red" onClick = "ActionIncentive({code:\''.$CPR_No.'\',action :\'cancel_sales_cpr\',title:\'CANCEL INCENTIVE\'});" title="CANCEL INCENTIVE"> <i class="fa fa-trash-o"></i> </button>';
				
				$Template		.= '&nbsp;&nbsp;<button type="button" class="btn btn-sm btn-primary" onClick = "ActionIncentive({code:\''.$CPR_No.'\',action :\'payment_sales_cpr\',title:\'UPDATE PAYMENT\'});" title="UPDATE PAYMENT"> <i class="fa fa-money"></i> </button>';
			}
			if($Arr_Akses['download'] == '1' && $Status == 'OPN'){				
				$Template		.= "&nbsp;&nbsp;<a href='".site_url()."/Incentive_sales/print_sales_cpr?cpr=".$CPR_No."' title='Print CPR' class='btn btn-sm btn-success' target='_blank'> <i class='fa fa-print'></i> </a>";
			
				
			}
			
			
			
			
			
			$nestedData 	= array(); 
			//$nestedData[]	= $nomor;
			$nestedData[]	= $CPR_No;
			$nestedData[]	= $CPR_Date;
			$nestedData[]	= $Technician;
			$nestedData[]	= $Keterangan;
			$nestedData[]	= number_format($Total);
			$nestedData[]	= $Ket_Status;
			$nestedData[]	= $Template;
			
			$data[] 		= $nestedData;
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
	
	
	/*
	| -------------------------------- |
	|	   LIST OUTSTANDING CPR    	   |
	| -------------------------------- |
	*/
	
	function list_outstanding_incentive(){		
		$rows_Sales		= $this->master_model->getArray('members',array('division_id'	=> 'DIV-001'),'id','nama');
		$data = array(
			'title'			=> 'OUTSTANDING SALESMAN INCENTIVE',
			'action'		=> 'list_outstanding_incentive',
			'akses_menu'	=> $this->Arr_Akses,
			'rows_sales'	=> $rows_Sales
		);
		history('View Oustanding Salesman Incentive');
		$this->load->view($this->folder.'/v_incentive_sales_outs',$data);
	}
	

	
	/*
	| -------------------------------- |
	|	 	DISPLAY OUTS INCENTIVE     |
	| -------------------------------- |
	*/
	
	function outstanding_incentive_cpr(){
		$Find_Sales		= $this->input->post('sales');
		
		$rows_Akses		= $this->Arr_Akses;
		
		
		$requestData	= $_REQUEST;
		
		
		$Like_Value		= $requestData['search']['value'];
		$column_order	= $requestData['order'][0]['column'];
		$column_dir		= $requestData['order'][0]['dir'];
		$limit_start	= $requestData['start'];
		$limit_length	= $requestData['length'];
		
		$WHERE_Find		= "1=1";
		if($Find_Sales){
			if(!empty($WHERE_Find))$WHERE_Find .=" AND ";
			$WHERE_Find .="member_id = '".$Find_Sales."'";
		}
		
		$Query_Sub		= $this->QueryProcess();
		
		if($Like_Value){
			if(!empty($WHERE_Find))$WHERE_Find	.=" AND ";
			$WHERE_Find	.="(
						detail_invoice.quotation_nomor LIKE '%".$this->db->escape_like_str($Like_Value)."%'
						OR detail_invoice.no_so LIKE '%".$this->db->escape_like_str($Like_Value)."%'
						OR detail_invoice.customer_name LIKE '%".$this->db->escape_like_str($Like_Value)."%'
						OR detail_invoice.invoice_no LIKE '%".$this->db->escape_like_str($Like_Value)."%'
						OR detail_invoice.member_name LIKE '%".$this->db->escape_like_str($Like_Value)."%'
						)";
		}
		
		$sql = "SELECT
					detail_invoice.*,
					(@row:=@row+1) AS urut
					
				FROM
					(
					".$Query_Sub."
				) detail_invoice,
				(SELECT @row := 0) r 
				WHERE ".$WHERE_Find;
		//print_r($sql);exit();
		$fetch['totalData'] 		= $this->db->query($sql)->num_rows();
		$fetch['totalFiltered']	= $this->db->query($sql)->num_rows();

		$columns_order_by = array(
			1 => 'detail_invoice.no_so',
			2 => 'detail_invoice.invoice_no',
			3 => 'detail_invoice.quotation_nomor',
			4 => 'detail_invoice.customer_name',
			5 => 'detail_invoice.member_name'
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
            
			
			$Code_Quot		= $row['quotation_id'];
			$Nomor_Quot		= $row['quotation_nomor'];
			$Code_Letter	= $row['id'];
			$Nomor_Letter	= $row['no_so'];
			$Date_Letter	= $row['tgl_so'];
			$Code_Cust		= $row['customer_id'];
			$Name_Cust		= $row['customer_name'];			
			$Code_Sales		= $row['member_id'];
			$Name_Sales		= $row['member_name'];
			$Total_SO		= $row['tot_order'];
			$Insitu_SO		= $row['tot_insitu'];
			$Accomodation_SO= $row['tot_akomodasi'];
			$Subcon_SO		= $row['tot_subcon'];
			$Fee_Cust		= $row['success_fee'];
			$Code_Invoice	= $row['invoice_no'];
			$First_Date_SO	= $row['first_so_date'];
			$First_SO		= '';
			$PO_Date		= $row['podate'];
			
			$Query_First	= "SELECT
									no_so
								FROM
									letter_orders
								WHERE
									quotation_id = '".$Code_Quot."'
								AND tgl_so <= '".$Date_Letter."'
								AND sts_so NOT IN ('REV', 'CNC')
								ORDER BY
									tgl_so ASC
								LIMIT 1";
			$rows_First		= $this->db->query($Query_First)->row();
			if($rows_First){
				$First_SO	= $rows_First->no_so;
			}
			$Cust_Fee		= 0;
			$Nett_Tool		= $Total_SO - $Insitu_SO - $Accomodation_SO - $Subcon_SO;
			if($Nomor_Letter === $First_SO){
				$Nett_Tool	= $Nett_Tool - $Fee_Cust;
				$Cust_Fee	= $Fee_Cust;
			}
			
			$Jenis			= '-';
			if(!empty($First_Date_SO) && $First_Date_SO !== '0000-00-00' && $First_Date_SO !== '1970-01-01'){
				$Beda_Hari	= (strtotime($PO_Date) - strtotime($First_Date_SO)) / (60*60*24);
				if($Beda_Hari > 365){
					$Jenis	= 'Repeat';
				}else{
					$Jenis	= 'New';
				}
			}
			
			
			$Code_Unik		= $Code_Letter;
			
			$nestedData 	= array(); 
			$nestedData[]	= '<input type="checkbox" id="det_pilih_'.$Code_Unik.'" name="detPilih[]" value="'.$Code_Unik.'">';
			$nestedData[]	= $Nomor_Letter;
			$nestedData[]	= $Code_Invoice; 
			$nestedData[]	= $Nomor_Quot;
			$nestedData[]	= $Name_Cust;
			$nestedData[]	= $Name_Sales;
			$nestedData[]	= number_format($Total_SO);
			$nestedData[]	= number_format($Insitu_SO);
			$nestedData[]	= number_format($Subcon_SO);
			$nestedData[]	= number_format($Accomodation_SO);
			$nestedData[]	= number_format($Cust_Fee);
			$nestedData[]	= number_format($Nett_Tool);
			$nestedData[]	= $Jenis;
			
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
								head_so.*, head_quot.nomor AS quotation_nomor,
								head_quot.member_id,
								head_quot.member_name,
								head_quot.exc_ppn,
								head_quot.pono,
								head_quot.podate,
								head_quot.success_fee,
								head_cust.first_so_date,

							IF (
								x_det_inv.total_so > 0,
								x_det_inv.total_so,
								0
							) AS tot_order,

							IF (
								x_det_inv.total_insitu > 0,
								x_det_inv.total_insitu,
								0
							) AS tot_insitu,

							IF (
								x_det_inv.total_akomodasi > 0,
								x_det_inv.total_akomodasi,
								0
							) AS tot_akomodasi,

							IF (
								x_det_inv.total_subcon > 0,
								x_det_inv.total_subcon,
								0
							) AS tot_subcon,
							 x_det_inv.invoice_id,
							 x_det_inv.invoice_no,
							 x_det_inv.datet AS invoice_date,
							 x_det_inv.dpp,
							 x_det_inv.diskon,
							 x_det_inv.total_dpp,
							 x_det_inv.pph23,
							 x_det_inv.ppn,
							 x_det_inv.grand_tot,
							 x_det_inv.jumlah_bayar
							FROM
								letter_orders head_so
							INNER JOIN quotations head_quot ON head_so.quotation_id = head_quot.id
							INNER JOIN customers head_cust ON head_so.customer_id = head_cust.id
							INNER JOIN (
								SELECT
									det_inv.letter_order_id,
									SUM(
										det_inv.total_harga - det_inv.total_discount
									) AS total_so,
									SUM(
										CASE
										WHEN det_inv.tipe = 'I' THEN
											det_inv.total_harga - det_inv.total_discount
										ELSE
											0
										END
									) AS total_insitu,
									SUM(
										CASE
										WHEN det_inv.tipe = 'A' THEN
											det_inv.total_harga - det_inv.total_discount
										ELSE
											0
										END
									) AS total_akomodasi,
									SUM(
										CASE
										WHEN det_inv.tipe = 'T'
										AND det_quot.supplier_id <> 'COMP-001' THEN
											det_inv.hpp * det_inv.qty
										ELSE
											0
										END
									) AS total_subcon,
									head_inv.id AS invoice_id,
									head_inv.invoice_no,
									head_inv.datet,
									head_inv.dpp,
									head_inv.diskon,
									head_inv.total_dpp,
									head_inv.pph23,
									head_inv.ppn,
									head_inv.grand_tot,
									x_jurnal.total_bayar AS jumlah_bayar
								FROM
									invoice_details det_inv
								LEFT JOIN quotation_details det_quot ON det_inv.detail_id = det_quot.id
								AND det_inv.tipe = 'T'
								INNER JOIN invoices head_inv ON head_inv.id = det_inv.invoice_id
								LEFT JOIN (
									SELECT
										invoice_no,
										sum(
											(

												IF (isnull(kredit), 0, kredit) -
												IF (isnull(debet), 0, debet)
											)
										) AS total_bayar
									FROM
										trans_ar_jurnals
									WHERE
										flag_batal <> 'Y'
									AND NOT (
										invoice_no IS NULL
										OR invoice_no = ''
										OR invoice_no = '-'
									)
									GROUP BY
										invoice_no
								) AS x_jurnal ON x_jurnal.invoice_no = head_inv.invoice_no
								WHERE
									det_inv.total_harga > 0
								GROUP BY
									det_inv.letter_order_id
							) x_det_inv ON x_det_inv.letter_order_id = head_so.id
							WHERE
								head_so.flag_incentive = 'N'
							AND head_so.flag_invoice = 'Y'
							
							AND x_det_inv.jumlah_bayar > 0
							AND (
								x_det_inv.jumlah_bayar - (
									x_det_inv.total_dpp - x_det_inv.pph23
								)
							) >= 0
							
							";
		
		return $Query_process;
	}
	
	
	
	/*
	| ------------------------------------ |
	|		SALESMAN CPR PROCESS		   |
	| ------------------------------------ |
	*/
	function sales_incentive_cpr_process(){
		$Ok_Proses		= 0;
		$rows_Sales	= $rows_Detail =  array();
		
		$Link_Back		= 'list_outstanding_incentive';
		if($this->input->post()){
			//echo"<pre>";print_r($this->input->post());exit;
			$Code_Selected	= $this->input->post('detPilih');
			$Imp_Code		= implode("','",$Code_Selected);
			$Code_Sales		= $this->input->post('sales');
			$rows_Sales		= $this->db->get_where('members',array('id'	=> $Code_Sales))->row();
			$WHERE			= "detail_invoice.id IN('".$Imp_Code."')";
			
			$Query_Sub		= $this->QueryProcess();
		
		
		
			$Query_Find 	= "SELECT
									detail_invoice.*
									
								FROM
									(
									".$Query_Sub."
								) detail_invoice 
								WHERE ".$WHERE;
			
			
			$rows_Detail	= $this->db->query($Query_Find)->result_array();
			if($rows_Detail){				
				$Ok_Proses		= 1;
			}
		}
		
		if($Ok_Proses == 1){
			$data = array(
				'title'			=> 'SALESMAN INCENTIVE CPR PROCESS',
				'action'		=> 'sales_incentive_cpr_process',
				'rows_detail'	=> $rows_Detail,
				'rows_sales'	=> $rows_Sales
			);
			
			$this->load->view($this->folder.'/v_incentive_sales_process',$data);
		}else{			
			$this->session->set_flashdata("alert_data", "<div class=\"alert alert-warning\" id=\"flash-message\">No record was found....</div>");
			redirect(site_url('Incentive_sales/'.$Link_Back));
			
		}
	}
	
	
	function save_process_sales_incentive(){
		
		if($this->input->post()){
			//echo"<pre>";print_r($this->input->post());exit;
			$Created_By		= $this->session->userdata('siscal_userid');
			$Created_Date	= date('Y-m-d H:i:s');
			
			$CPR_Date		= date('Y-m-d');
			$CPR_Nomor		= 'CPR-M-'.date('Ym').'-'.date('dHis');
			$CPR_Total		= str_replace(',','',$this->input->post('total'));
			$Member_Code	= $this->input->post('member_id');
			$Member_Name	= $this->input->post('member_name');
			$CPR_Ket		= $this->input->post('descr');
			
			$detDetail		= $this->input->post('detDetail');
			
			

			
			$Pesan_Error	= '';
			$this->db->trans_begin();
			
			$Ins_CPR_Head	= array(
				'id'			=> $CPR_Nomor,
				'datet'			=> $CPR_Date,
				'member_id'		=> $Member_Code,
				'member_name'	=> $Member_Name,
				'descr'			=> $CPR_Ket,
				'total'			=> $CPR_Total,
				'status'		=> 'OPN',
				'created_by'	=> $Created_By,
				'created_date'	=> $Created_Date
			);
			
			$Has_Ins_CPR_Head	= $this->db->insert('sales_incentives',$Ins_CPR_Head);
			if($Has_Ins_CPR_Head !== TRUE){
				$Pesan_Error	= 'Error Insert CPR Header';
			}
			
			if($detDetail){
				$intD			= 0;
				$Arr_Imp_Code	= array();
				foreach($detDetail as $keyDetail=>$valDetail){
					$intD++;
					$Code_Detail	= $CPR_Nomor.'-'.$intD;					
					$Code_SO		= $valDetail['letter_order_id'];								
					$Ins_CPR_Det	= array(
						'id'						=> $Code_Detail,
						'sales_incentive_id'		=> $CPR_Nomor,
						'invoice_no'				=> $valDetail['invoice_no'],
						'quotation_id'				=> $valDetail['quotation_id'],
						'quotation_nomor'			=> $valDetail['quotation_nomor'],
						'customer_id'				=> $valDetail['customer_id'],
						'customer_name'				=> $valDetail['customer_name'],
						'letter_order_id'			=> $valDetail['letter_order_id'],						
						'net_total'					=> $valDetail['net_total'],
						'nil_incentive'				=> str_replace(',','',$valDetail['nil_incentive']),
						'tot_incentive'				=> str_replace(',','',$valDetail['tot_incentive'])
					);
					
					$Has_Ins_CPR_Det	= $this->db->insert('sales_incentive_details',$Ins_CPR_Det);
					if($Has_Ins_CPR_Det !== TRUE){
						$Pesan_Error	= 'Error Insert CPR Detail';
					}
					
					$Arr_Imp_Code[$Code_SO]	= $Code_SO;
				}
				
				if($Arr_Imp_Code){
					$Imp_Code		= implode("','",$Arr_Imp_Code);
					$Upd_Trans		= "UPDATE letter_orders SET flag_incentive = 'Y' WHERE id IN('".$Imp_Code."')";
					
					$Has_Upd_Trans 	= $this->db->query($Upd_Trans);
					if($Has_Upd_Trans !== TRUE){
						$Pesan_Error	= 'Error Update Letter Order';
					}
					
				}
			}
			
			if ($this->db->trans_status() !== TRUE || !empty($Pesan_Error)){
				$this->db->trans_rollback();
				$Arr_Return		= array(
					'status'		=> 2,
					'pesan'			=> 'Save Process  Failed, '.$Pesan_Error
				);
			}else{
				$this->db->trans_commit();
				$Arr_Return		= array(
					'status'		=> 1,
					'pesan'			=> 'Save process success. Thank you & have a nice day......',
					'code'			=> $CPR_Nomor
				);
				history('Create Sales Incentive CPR '.$CPR_Nomor);
			}
			
			
		}else{
			$Arr_Return		= array(
				'status'		=> 2,
				'pesan'			=> 'No Record Was Found........'
			);
		}
		echo json_encode($Arr_Return);
	}
	
	
	
	
	function view_sales_cpr(){
		$rows_Header		= $rows_Detail	= array();
		if($this->input->post()){
			$Code_CPR			= $this->input->post('code');
			$rows_Header		= $this->db->get_where('sales_incentives',array('id'=>$Code_CPR))->result();			
			$rows_Detail		= $this->db->get_where('sales_incentive_details',array('sales_incentive_id'=>$Code_CPR))->result();
		}
		$data = array(
			'title'			=> 'SALESMAN INCENTIVE CPR DETAIL',
			'rows_header'	=> $rows_Header,
			'rows_detail'	=> $rows_Detail,
			'action'		=> 'view_sales_cpr',
			'category'		=> 'view'
		);
		$this->load->view($this->folder.'/v_incentive_sales_preview',$data); 
	}
	
	
	
	function print_sales_cpr($Code_CPR = ''){
		$rows_Header		= $rows_Detail	= array();
		if($this->input->get()){
			$Code_CPR			= urldecode($this->input->get('cpr'));
			$rows_Header		= $this->db->get_where('sales_incentives',array('id'=>$Code_CPR))->row();			
			$rows_Detail		= $this->db->get_where('sales_incentive_details',array('sales_incentive_id'=>$Code_CPR))->result();
		}
		
		$data 			= array(
			'title'			=> 'PRINT INCENTIVE CPR',
			'action'		=> 'print_sales_cpr',
			'rows_header'	=> $rows_Header,
			'rows_detail'	=> $rows_Detail,
			'printby'		=> $this->session->userdata('siscal_username'),
			'today' 		=> date("Y-m-d H:i:s")			
		);	
		
		
		$this->load->view($this->folder.'/v_incentive_sales_print',$data); 
	}
	
	
	function payment_sales_cpr(){
		$rows_Header		= $rows_Detail	= array();
		if($this->input->post()){
			$Code_CPR			= $this->input->post('code');
			$rows_Header		= $this->db->get_where('sales_incentives',array('id'=>$Code_CPR))->result();			
			$rows_Detail		= $this->db->get_where('sales_incentive_details',array('sales_incentive_id'=>$Code_CPR))->result();
		}
		$data = array(
			'title'			=> 'SALESMAN INCENTIVE CPR DETAIL',
			'rows_header'	=> $rows_Header,
			'rows_detail'	=> $rows_Detail,
			'action'		=> 'payment_sales_cpr',
			'category'		=> 'update'
		);
		$this->load->view($this->folder.'/v_incentive_sales_preview',$data); 
	}
	
	
	function save_payment_sales_cpr(){
		$Arr_Return		= array(
			'status'		=> 2,
			'pesan'			=> 'No record was found....'
		);
		if($this->input->post()){
			//echo"<pre>";print_r($this->input->post());exit;
			$modified_By	= $this->session->userdata('siscal_userid');
			$modified_Date	= date('Y-m-d H:i:s');
			$Code_CPR		= $this->input->post('nomor_cpr');
			$Paid_Date		= $this->input->post('buk_tgl');
			$Paid_Reff		= strtoupper($this->input->post('buk_id'));
			
			
			$rows_Check		= $this->db->get_where('sales_incentives',array('id'=>$Code_CPR))->row();
			//echo"<pre>";print_r($rows_Check);exit;
			if($rows_Check->status !== 'OPN'){
				$Arr_Return		= array(
					'status'		=> 2,
					'pesan'			=> 'Data has been modified by other process...'
				);
			}else{
				$Upd_header	= array(
					'buk_tgl'		=> $Paid_Date,
					'buk_id'		=> $Paid_Reff,
					'status'		=> 'BUK',
					'modified_by'	=> $modified_By,
					'modified_date'	=> $modified_Date
				);
				
				$this->db->trans_begin();
				$this->db->update('sales_incentives',$Upd_header,array('id'=>$Code_CPR));
				
				if ($this->db->trans_status() != TRUE){
					$this->db->trans_rollback();
					$Arr_Return		= array(
						'status'		=> 2,
						'pesan'			=> 'Save Process  Failed, please try again...'
					);
				}else{
					$this->db->trans_commit();
					$Arr_Return		= array(
						'status'		=> 1,
						'pesan'			=> 'Save process success. Thank you & have a nice day......'
					);
					history('Update Salesman Incentive CPR '.$Code_CPR);
				}
			}
			
		}
		echo json_encode($Arr_Return);
		
	}
	
	
	function cancel_sales_cpr(){
		$rows_Header		= $rows_Detail	= array();
		if($this->input->post()){
			$Code_CPR			= $this->input->post('code');
			$rows_Header		= $this->db->get_where('sales_incentives',array('id'=>$Code_CPR))->result();			
			$rows_Detail		= $this->db->get_where('sales_incentive_details',array('sales_incentive_id'=>$Code_CPR))->result();
		}
		$data = array(
			'title'			=> 'SALESMAN INCENTIVE CPR CANCELLATION',
			'rows_header'	=> $rows_Header,
			'rows_detail'	=> $rows_Detail,
			'action'		=> 'cancel_sales_cpr',
			'category'		=> 'cancel'
		);
		$this->load->view($this->folder.'/v_incentive_sales_preview',$data); 
	}
	
	function save_cancel_sales_cpr(){
		$Arr_Return		= array(
			'status'		=> 2,
			'pesan'			=> 'No record was found....'
		);
		
		if($this->input->post()){
			//echo"<pre>";print_r($this->input->post());exit;
			$cancel_By		= $this->session->userdata('siscal_userid');
			$cancel_Date	= date('Y-m-d H:i:s');
			
			$Code_CPR		= $this->input->post('nomor_cpr');
			$Reason			= strtoupper($this->input->post('cancel_reason'));
			
			$rows_Check		= $this->db->get_where('sales_incentives',array('id'=>$Code_CPR))->row();
			//echo"<pre>";print_r($rows_Check);exit;
			if($rows_Check->status !== 'OPN'){
				$Arr_Return		= array(
					'status'		=> 2,
					'pesan'			=> 'Data has been modified by other process..'
				);		
			}else{
				$this->db->trans_begin();
				
				$Pesan_Error	= '';
				$rows_Order		= $this->master_model->getArray('sales_incentive_details',array('sales_incentive_id'=>$Code_CPR),'letter_order_id','letter_order_id');
				
				
				$Upd_header	= array(
					'cancel_by'		=> $cancel_By,
					'cancel_date'	=> $cancel_Date,
					'cancel_reason'	=> $Reason,
					'status'		=> 'CNC',
					'modified_by'	=> $cancel_By,
					'modified_date'	=> $cancel_Date
				);				
				$Has_Upd_CPR = $this->db->update('sales_incentives',$Upd_header,array('id'=>$Code_CPR));
				if($Has_Upd_CPR !== TRUE){
					$Pesan_Error	= 'Error Update CPR Header';
				}
				
				if($rows_Order){
					$Imp_Code		= implode("','",$rows_Order);
					$Upd_Trans		= "UPDATE letter_orders SET flag_incentive = 'N' WHERE id IN('".$Imp_Code."')";
					
					$Has_Upd_Trans 	= $this->db->query($Upd_Trans);
					if($Has_Upd_Trans !== TRUE){
						$Pesan_Error	= 'Error Update Letter Order';
					}
				}
				
				if ($this->db->trans_status() != TRUE || !empty($Pesan_Error)){
					$this->db->trans_rollback();
					$Arr_Return		= array(
						'status'		=> 2,
						'pesan'			=> 'Cancellation Process  Failed, please try again...'
					);
				}else{
					$this->db->trans_commit();
					$Arr_Return		= array(
						'status'		=> 1,
						'pesan'			=> 'Cancellation process success. Thank you & have a nice day......'
					);
					history('Cancel Salesman Incentive CPR  '.$Code_CPR);
				}
				
			}
		}
		echo json_encode($Arr_Return);
		
	}
	
	function export_sales_incentive_outs(){
		$Code_Sales	= '';
		if($this->input->get()){
			$Code_Sales	= urldecode($this->input->get('sales'));
		}
		$Judul			='Salesman Incentive Report';
		$rows_Teknisi	= $this->master_model->getArray('members',array('division_id'	=> 'DIV-001'),'id','nama');
		$WHERE_Find		= "1=1";
		if($Code_Sales){
			if(!empty($WHERE_Find))$WHERE_Find .=" AND ";
			$WHERE_Find .="detail_invoice.member_id = '".$Code_Sales."'";
			$Judul		.=' - '.strtoupper($rows_Teknisi[$Code_Sales]);
		}
		
		$Query_Sub		= $this->QueryProcess();
		
		
		
		$sql = "SELECT
					detail_invoice.*
					
				FROM
					(
					".$Query_Sub."
				) detail_invoice 
				WHERE ".$WHERE_Find;
		
		$this->load->library("PHPExcel");
		$objPHPExcel	= new PHPExcel();
		set_time_limit(0);
		ini_set('memory_limit','1024M');
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

		  
		  
		$Row	= 1;
		$NewRow	= $Row+1;
		$sheet 	= $objPHPExcel->getActiveSheet();

		$sheet->setCellValue('A'.$Row, $Judul);
		$sheet->getStyle('A'.$Row.':P'.$NewRow)->applyFromArray($style_header);
		$sheet->mergeCells('A'.$Row.':P'.$NewRow);



		// header
		$NewRow++;
		
		$sheet->setCellValue('A'.$NewRow, 'No');
		$sheet->getStyle('A'.$NewRow)->applyFromArray($style_header);

		$sheet->setCellValue('B'.$NewRow, 'Invoice No');
		$sheet->getStyle('B'.$NewRow)->applyFromArray($style_header);

		$sheet->setCellValue('C'.$NewRow, 'Quotation No');
		$sheet->getStyle('C'.$NewRow)->applyFromArray($style_header);

		$sheet->setCellValue('D'.$NewRow, 'Customer');
		$sheet->getStyle('D'.$NewRow)->applyFromArray($style_header);

		$sheet->setCellValue('E'.$NewRow, 'Marketing');
		$sheet->getStyle('E'.$NewRow)->applyFromArray($style_header);

		$sheet->setCellValue('F'.$NewRow, 'Total DPP');
		$sheet->getStyle('F'.$NewRow)->applyFromArray($style_header);

		$sheet->setCellValue('G'.$NewRow, 'Insitu');
		$sheet->getStyle('G'.$NewRow)->applyFromArray($style_header);

		$sheet->setCellValue('H'.$NewRow, 'Subcon');
		$sheet->getStyle('H'.$NewRow)->applyFromArray($style_header);

		$sheet->setCellValue('I'.$NewRow, 'Akomodasi');
		$sheet->getStyle('I'.$NewRow)->applyFromArray($style_header);

		$sheet->setCellValue('J'.$NewRow, 'Cust Fee');
		$sheet->getStyle('J'.$NewRow)->applyFromArray($style_header);

		$sheet->setCellValue('K'.$NewRow, 'Nett');
		$sheet->getStyle('K'.$NewRow)->applyFromArray($style_header);

		$sheet->setCellValue('L'.$NewRow, 'Incentive (%)');
		$sheet->getStyle('L'.$NewRow)->applyFromArray($style_header);

		$sheet->setCellValue('M'.$NewRow, 'No SO');
		$sheet->getStyle('M'.$NewRow)->applyFromArray($style_header);

		$sheet->setCellValue('N'.$NewRow, 'Tgl SO Pertama');
		$sheet->getStyle('N'.$NewRow)->applyFromArray($style_header);

		$sheet->setCellValue('O'.$NewRow, 'Tgl PO');
		$sheet->getStyle('O'.$NewRow)->applyFromArray($style_header);

		$sheet->setCellValue('P'.$NewRow, 'Jenis');
		$sheet->getStyle('P'.$NewRow)->applyFromArray($style_header);
		
		$rows_data	= $this->db->query($sql)->result_array();
		if($rows_data){
			$loop	=0;
			$sekarang	= date('Y-m-d');
			foreach($rows_data as $key=>$row){
				$loop++;
				$NewRow++;
				
				$Code_Quot		= $row['quotation_id'];
				$Nomor_Quot		= $row['quotation_nomor'];
				$Code_Letter	= $row['id'];
				$Nomor_Letter	= $row['no_so'];
				$Date_Letter	= $row['tgl_so'];
				$Code_Cust		= $row['customer_id'];
				$Name_Cust		= $row['customer_name'];			
				$Code_Sales		= $row['member_id'];
				$Name_Sales		= $row['member_name'];
				$Total_SO		= $row['tot_order'];
				$Insitu_SO		= $row['tot_insitu'];
				$Accomodation_SO= $row['tot_akomodasi'];
				$Subcon_SO		= $row['tot_subcon'];
				$Fee_Cust		= $row['success_fee'];
				$Code_Invoice	= $row['invoice_no'];
				$First_Date_SO	= $row['first_so_date'];
				$First_SO		= '';
				$PO_Date		= $row['podate'];
				
				$Query_First	= "SELECT
										no_so
									FROM
										letter_orders
									WHERE
										quotation_id = '".$Code_Quot."'
									AND tgl_so <= '".$Date_Letter."'
									AND sts_so NOT IN ('REV', 'CNC')
									ORDER BY
										tgl_so ASC
									LIMIT 1";
				$rows_First		= $this->db->query($Query_First)->row();
				if($rows_First){
					$First_SO	= $rows_First->no_so;
				}
				$Cust_Fee		= 0;
				$Nett_Tool		= $Total_SO - $Insitu_SO - $Accomodation_SO - $Subcon_SO;
				if($Nomor_Letter === $First_SO){
					$Nett_Tool	= $Nett_Tool - $Fee_Cust;
					$Cust_Fee	= $Fee_Cust;
				}
				$Persen			= 3;
				$Jenis			= '-';
				if(!empty($First_Date_SO) && $First_Date_SO !== '0000-00-00' && $First_Date_SO !== '1970-01-01'){
					$Beda_Hari	= (strtotime($PO_Date) - strtotime($First_Date_SO)) / (60*60*24);
					if($Beda_Hari > 365){
						$Jenis	= 'Repeat';
					}else{
						$Jenis	= 'New';
						$Persen	= 5;
					}
				}
				
				$insentif	= round(($Nett_Tool * $Persen)/100);
						
				
				$Mulai_Col	= 0;
				
				
				
				$Mulai_Col++;		
				$Cols	= getColsChar($Mulai_Col);
				$sheet->setCellValue($Cols.$NewRow, $loop);
				$sheet->getStyle($Cols.$NewRow)->applyFromArray($styleArray1);
				
				$Mulai_Col++;		
				$Cols	= getColsChar($Mulai_Col);
				$sheet->setCellValue($Cols.$NewRow, $Code_Invoice);
				$sheet->getStyle($Cols.$NewRow)->applyFromArray($styleArray1);
				
				$Mulai_Col++;		
				$Cols	= getColsChar($Mulai_Col);
				$sheet->setCellValue($Cols.$NewRow, $Nomor_Quot);
				$sheet->getStyle($Cols.$NewRow)->applyFromArray($styleArray1);
				
				$Mulai_Col++;		
				$Cols	= getColsChar($Mulai_Col);
				$sheet->setCellValue($Cols.$NewRow, $Name_Cust);
				$sheet->getStyle($Cols.$NewRow)->applyFromArray($styleArray1);
				
				$Mulai_Col++;		
				$Cols	= getColsChar($Mulai_Col);
				$sheet->setCellValue($Cols.$NewRow, $Name_Sales);
				$sheet->getStyle($Cols.$NewRow)->applyFromArray($styleArray1);
				
				
				
				$Mulai_Col++;		
				$Cols	= getColsChar($Mulai_Col);
				$sheet->setCellValue($Cols.$NewRow, number_format($Total_SO));
				$sheet->getStyle($Cols.$NewRow)->applyFromArray($styleArray1);
				
				
				$Mulai_Col++;		
				$Cols	= getColsChar($Mulai_Col);
				$sheet->setCellValue($Cols.$NewRow, number_format($Insitu_SO));
				$sheet->getStyle($Cols.$NewRow)->applyFromArray($styleArray1);
				
				$Mulai_Col++;		
				$Cols	= getColsChar($Mulai_Col);
				$sheet->setCellValue($Cols.$NewRow, number_format($Subcon_SO));
				$sheet->getStyle($Cols.$NewRow)->applyFromArray($styleArray1);
				
				$Mulai_Col++;		
				$Cols	= getColsChar($Mulai_Col);
				$sheet->setCellValue($Cols.$NewRow, number_format($Accomodation_SO));
				$sheet->getStyle($Cols.$NewRow)->applyFromArray($styleArray1);
				
				$Mulai_Col++;		
				$Cols	= getColsChar($Mulai_Col);
				$sheet->setCellValue($Cols.$NewRow, number_format($Cust_Fee));
				$sheet->getStyle($Cols.$NewRow)->applyFromArray($styleArray1);
				
				$Mulai_Col++;		
				$Cols	= getColsChar($Mulai_Col);
				$sheet->setCellValue($Cols.$NewRow, number_format($Nett_Tool));
				$sheet->getStyle($Cols.$NewRow)->applyFromArray($styleArray1);
				
				$Mulai_Col++;		
				$Cols	= getColsChar($Mulai_Col);
				$sheet->setCellValue($Cols.$NewRow, number_format($Persen));
				$sheet->getStyle($Cols.$NewRow)->applyFromArray($styleArray1);
				
				
				
				$Mulai_Col++;		
				$Cols	= getColsChar($Mulai_Col);
				$sheet->setCellValue($Cols.$NewRow, $Nomor_Letter);
				$sheet->getStyle($Cols.$NewRow)->applyFromArray($styleArray1);
				
				$Mulai_Col++;		
				$Cols	= getColsChar($Mulai_Col);
				$sheet->setCellValue($Cols.$NewRow, $First_Date_SO);
				$sheet->getStyle($Cols.$NewRow)->applyFromArray($styleArray1);
				
				$Mulai_Col++;		
				$Cols	= getColsChar($Mulai_Col);
				$sheet->setCellValue($Cols.$NewRow, $PO_Date);
				$sheet->getStyle($Cols.$NewRow)->applyFromArray($styleArray1);
				
				$Mulai_Col++;		
				$Cols	= getColsChar($Mulai_Col);
				$sheet->setCellValue($Cols.$NewRow, $Jenis);
				$sheet->getStyle($Cols.$NewRow)->applyFromArray($styleArray1);
				
				
			}
		}


		$sheet->setTitle('Insentif Report');       
		$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
		
		//sesuaikan headernya 
		header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
		header("Cache-Control: no-store, no-cache, must-revalidate");
		header("Cache-Control: post-check=0, pre-check=0", false);
		header("Pragma: no-cache");
		header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
		//ubah nama file saat diunduh
		header('Content-Disposition: attachment;filename="Laporan_Sales_Incentive_Outs_'.date('YmdHis').'.xls"');
		//unduh file
		$objWriter->save("php://output");
		exit;
	}
}