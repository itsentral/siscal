<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Incentive_technician extends CI_Controller { 
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
		
		$rows_Teknisi		= $this->master_model->getArray('members',array('division_id'	=> 'DIV-002'),'id','nama');
		$data = array(
			'title'			=> 'MANAGE INCENTIVE TECHNICIAN',
			'action'		=> 'index',
			'akses_menu'	=> $Arr_Akses,
			'rows_teknisi'	=> $rows_Teknisi
		);
		history('View List Incentive Technician');
		$this->load->view($this->folder.'/v_incentive_technician',$data);
	}
	
	/*
	| -------------------------------- |
	|     DISPLAY LIST CPR INCENTIVE   |
	| -------------------------------- |
	*/
	function get_data_display(){
		$Arr_Akses		= $this->Arr_Akses;
		
		$Tech_Find		= $this->input->post('teknisi');
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
		
		if($Tech_Find){
			if(!empty($WHERE))$WHERE	.=" AND ";
			$WHERE	.="member_id = '".$Tech_Find."'";
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
					technician_incentives,
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
			
			
			$Template		= '<button type="button" class="btn btn-sm bg-navy-active" onClick = "ActionIncentive({code:\''.$CPR_No.'\',action :\'view_technician_cpr\',title:\'VIEW DETAIL\'});" title="VIEW DETAIL"> <i class="fa fa-search"></i> </button>';
			
			if(($Arr_Akses['delete'] == '1' || $Arr_Akses['update'] == '1') && $Status == 'OPN'){
				$Template		.= '&nbsp;&nbsp;<button type="button" class="btn btn-sm bg-red" onClick = "ActionIncentive({code:\''.$CPR_No.'\',action :\'cancel_technician_cpr\',title:\'CANCEL INCENTIVE\'});" title="CANCEL INCENTIVE"> <i class="fa fa-trash-o"></i> </button>';
				
				$Template		.= '&nbsp;&nbsp;<button type="button" class="btn btn-sm btn-primary" onClick = "ActionIncentive({code:\''.$CPR_No.'\',action :\'payment_technician_cpr\',title:\'UPDATE PAYMENT\'});" title="UPDATE PAYMENT"> <i class="fa fa-money"></i> </button>';
			}
			if($Arr_Akses['download'] == '1' && $Status == 'OPN'){				
				$Template		.= "&nbsp;&nbsp;<a href='".site_url()."/Incentive_technician/print_technician_cpr?cpr=".$CPR_No."' title='Print CPR' class='btn btn-sm btn-success' target='_blank'> <i class='fa fa-print'></i> </a>";
			
				
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
		$rows_Teknisi		= $this->master_model->getArray('members',array('division_id'	=> 'DIV-002'),'id','nama');
		$data = array(
			'title'			=> 'OUTSTANDING TECHNICIAN INCENTIVE',
			'action'		=> 'list_outstanding_incentive',
			'akses_menu'	=> $this->Arr_Akses,
			'rows_teknisi'	=> $rows_Teknisi
		);
		history('View Oustanding Technician Incentive');
		$this->load->view($this->folder.'/v_incentive_technician_outs',$data);
	}
	

	
	/*
	| -------------------------------- |
	|	 	DISPLAY OUTS INCENTIVE     |
	| -------------------------------- |
	*/
	
	function outstanding_incentive_cpr(){
		$Find_Tech		= $this->input->post('teknisi');
		
		$rows_Akses		= $this->Arr_Akses;
		
		
		$requestData	= $_REQUEST;
		
		
		$Like_Value		= $requestData['search']['value'];
		$column_order	= $requestData['order'][0]['column'];
		$column_dir		= $requestData['order'][0]['dir'];
		$limit_start	= $requestData['start'];
		$limit_length	= $requestData['length'];
		
		$WHERE_Find		= "1=1";
		if($Find_Tech){
			if(!empty($WHERE_Find))$WHERE_Find .=" AND ";
			$WHERE_Find .="code_teknisi = '".$Find_Tech."'";
		}
		
		$Query_Sub		= $this->QueryProcess();
		
		if($Like_Value){
			if(!empty($WHERE_Find))$WHERE_Find	.=" AND ";
			$WHERE_Find	.="(
						detail_invoice.quotation_nomor LIKE '%".$this->db->escape_like_str($Like_Value)."%'
						OR detail_invoice.no_so LIKE '%".$this->db->escape_like_str($Like_Value)."%'
						OR detail_invoice.customer_name LIKE '%".$this->db->escape_like_str($Like_Value)."%'
						OR detail_invoice.tool_id LIKE '%".$this->db->escape_like_str($Like_Value)."%'
						OR detail_invoice.name_teknisi LIKE '%".$this->db->escape_like_str($Like_Value)."%'
						)";
		}
		
		$sql = "SELECT
					detail_invoice.quotation_id,
					detail_invoice.quotation_nomor,
					detail_invoice.letter_order_id,
					detail_invoice.no_so,
					detail_invoice.schedule_detail_id,
					detail_invoice.schedule_nomor,
					detail_invoice.customer_id,
					detail_invoice.customer_name,
					detail_invoice.tool_id,
					detail_invoice.tool_name,
					SUM(detail_invoice.qty_result) AS qty,
					detail_invoice.hpp,
					detail_invoice.price,
					detail_invoice.diskon,
					detail_invoice.code_teknisi,
					detail_invoice.name_teknisi,
					detail_invoice.invoice_no,
					(@row:=@row+1) AS urut
					
				FROM
					(
					".$Query_Sub."
				) detail_invoice,
				(SELECT @row := 0) r 
				WHERE ".$WHERE_Find."
				GROUP BY
					detail_invoice.code_teknisi,
					detail_invoice.letter_order_id,
					detail_invoice.tool_id
				";
		//print_r($sql);exit();
		$fetch['totalData'] 		= $this->db->query($sql)->num_rows();
		$fetch['totalFiltered']	= $this->db->query($sql)->num_rows();

		$columns_order_by = array(
			1 => 'detail_invoice.quotation_nomor',
			2 => 'detail_invoice.no_so',
			3 => 'detail_invoice.customer_name',
			4 => 'detail_invoice.name_teknisi',
			5 => 'detail_invoice.tool_id'
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
			$Code_Letter	= $row['letter_order_id'];
			$Nomor_Letter	= $row['no_so'];
			$Code_Sched		= $row['schedule_detail_id'];
			$Nomor_Sched	= $row['schedule_nomor'];
			$Code_Cust		= $row['customer_id'];
			$Name_Cust		= $row['customer_name'];
			$Code_Tool		= $row['tool_id'];
			$Name_Tool		= $row['tool_name'];
			$Code_Tech		= $row['code_teknisi'];
			$Name_Tech		= $row['name_teknisi'];
			$Qty_Tool		= $row['qty'];
			$HPP_Tool		= $row['hpp'];
			$Price_Tool		= $row['price'];
			$Disc_Tool		= ($row['diskon'] > 0)?$row['diskon']:0;
			$Code_Invoice	= $row['invoice_no'];
			$Nett_Tool		= round($Qty_Tool * $Price_Tool * (100 - $Disc_Tool) / 100);
			
			$Lingkup 		= $Instrument = '- ';
			$Query_Tool	= "SELECT
								head_tool.id,
								head_tool.`name` AS tool_name,
								head_ins.`name` AS instrument,
								head_dim.`name` AS dimention
							FROM
								tools head_tool
							INNER JOIN instruments head_ins ON head_tool.instrument_id = head_ins.id
							INNER JOIN dimentions head_dim ON head_tool.dimention_id = head_dim.id
							WHERE
								head_tool.id = '".$Code_Tool."'";
			$rows_Tool	= $this->db->query($Query_Tool)->row();
			if($rows_Tool){
				$Lingkup	= $rows_Tool->dimention;
				$Instrument	= $rows_Tool->instrument;
			}
			
			$Code_Unik		= $Code_Tech.'^_^'.$Code_Tool.'^_^'.$Code_Letter;
			
			$nestedData 	= array(); 
			$nestedData[]	= '<input type="checkbox" id="det_pilih_'.$Code_Unik.'" name="detPilih[]" value="'.$Code_Unik.'">';
			$nestedData[]	= $Nomor_Quot; 
			$nestedData[]	= $Nomor_Letter;
			$nestedData[]	= $Name_Cust;
			$nestedData[]	= $Name_Tech;
			$nestedData[]	= $Code_Tool;
			$nestedData[]	= $Name_Tool;
			$nestedData[]	= number_format($Price_Tool);
			$nestedData[]	= number_format($Qty_Tool);
			$nestedData[]	= number_format($Disc_Tool);
			$nestedData[]	= number_format($Nett_Tool);
			$nestedData[]	= $Lingkup;
			
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
								`det_trans`.`id` AS `id`,
								`head_trans`.`quotation_id` AS `quotation_id`,
								`head_trans`.`quotation_detail_id` AS `quotation_detail_id`,
								`head_trans`.`quotation_nomor` AS `quotation_nomor`,
								`head_trans`.`quotation_date` AS `quotation_date`,
								`head_trans`.`letter_order_id` AS `letter_order_id`,
								`head_trans`.`letter_order_detail_id` AS `letter_order_detail_id`,
								`head_trans`.`no_so` AS `no_so`,
								`head_trans`.`tgl_so` AS `tgl_so`,
								`head_trans`.`schedule_detail_id` AS `schedule_detail_id`,
								`head_trans`.`schedule_id` AS `schedule_id`,
								`head_trans`.`schedule_nomor` AS `schedule_nomor`,
								`head_trans`.`schedule_date` AS `schedule_date`,
								`head_trans`.`customer_id` AS `customer_id`,
								`head_trans`.`customer_name` AS `customer_name`,
								`head_trans`.`tool_id` AS `tool_id`,
								`head_trans`.`tool_name` AS `tool_name`,
								`head_trans`.`supplier_id` AS `supplier_id`,
								`head_trans`.`supplier_name` AS `supplier_name`,
								1 AS `qty_result`,
								`head_trans`.`hpp` AS `hpp`,
								`head_trans`.`price` AS `price`,
								`head_trans`.`diskon` AS `diskon`,
								`head_trans`.`labs` AS `labs`,
								`head_trans`.`insitu` AS `insitu`,
								`head_trans`.`plan_process_date` AS `plan_process_date`,
								`head_trans`.`plan_time_start` AS `plan_time_start`,
								`head_trans`.`plan_time_end` AS `plan_time_end`,

							IF (
								(
									(
										`det_trans`.`actual_teknisi_id` IS NOT NULL
									)
									AND (
										`det_trans`.`actual_teknisi_id` <> ''
									)
									AND (
										`det_trans`.`actual_teknisi_id` <> '-'
									)
								),
								`det_trans`.`actual_teknisi_id`,
								`head_trans`.`teknisi_id`
							) AS `code_teknisi`,

							IF (
								(
									(
										`det_trans`.`actual_teknisi_name` IS NOT NULL
									)
									AND (
										`det_trans`.`actual_teknisi_name` <> ''
									)
									AND (
										`det_trans`.`actual_teknisi_name` <> '-'
									)
								),
								`det_trans`.`actual_teknisi_name`,
								`head_trans`.`teknisi_name`
							) AS `name_teknisi`,
							 `det_trans`.`datet` AS `actual_process_date`,
							 `det_trans`.`start_time` AS `actual_process_start`,
							 `det_trans`.`end_time` AS `actual_process_end`,
							 `det_trans`.`flag_insentif` AS `flag_insentif`,
							 x_order.invoice_no,
							 x_order.grand_tot,
							 x_order.pph23,
							 x_order.jumlah_bayar
							FROM
								(
									`trans_data_details` `det_trans`
									JOIN `trans_details` `head_trans` ON (
										(
											`det_trans`.`trans_detail_id` = `head_trans`.`id`
										)
									)
									JOIN (
										SELECT
											det_inv.letter_order_id,
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
											head_inv.grand_tot > 0
										GROUP BY
											det_inv.letter_order_id
									) x_order ON x_order.letter_order_id = head_trans.letter_order_id
								)
							WHERE
								(
									(
										`det_trans`.`flag_proses` = 'Y'
									)
									AND (
										`det_trans`.`flag_insentif` <> 'Y'
									)
									AND (
										`det_trans`.`flag_print` = 'Y'
									)
									AND (
										`det_trans`.`no_sertifikat` IS NOT NULL
									)
									AND (
										`det_trans`.`no_sertifikat` <> '-'
									)
									AND (
										`det_trans`.`no_sertifikat` <> ''
									)
									AND (
										(`head_trans`.`labs` = 'Y')
										OR (
											(`head_trans`.`insitu` = 'Y')
											AND (
												`head_trans`.`supplier_id` = 'COMP-001'
											)
										)
									)
									
									AND x_order.jumlah_bayar > 0
									AND (
										x_order.jumlah_bayar - (
											x_order.total_dpp - x_order.pph23
										)
									) >= 0
									
								)";
		
		return $Query_process;
	}
	
	
	
	/*
	| ------------------------------------ |
	|		TECHNICIAN CPR PROCESS		   |
	| ------------------------------------ |
	*/
	function technician_incentive_cpr_process(){
		$Ok_Proses		= 0;
		$rows_Teknisi	= $rows_Detail =  array();
		
		$Link_Back		= 'list_outstanding_incentive';
		if($this->input->post()){
			//echo"<pre>";print_r($this->input->post());exit;
			$Code_Selected	= $this->input->post('detPilih');
			$Imp_Code		= implode("','",$Code_Selected);
			$Code_Teknisi	= $this->input->post('teknisi');
			$rows_Teknisi	= $this->db->get_where('members',array('id'	=> $Code_Teknisi))->row();
			$WHERE			= "CONCAT_WS('^_^',detail_invoice.code_teknisi,detail_invoice.tool_id,detail_invoice.letter_order_id) IN('".$Imp_Code."')";
			
			$Query_Sub		= $this->QueryProcess();
		
		
		
			$Query_Find 	= "SELECT
									detail_invoice.quotation_id,
									detail_invoice.quotation_nomor,
									detail_invoice.letter_order_id,
									detail_invoice.no_so,
									detail_invoice.schedule_detail_id,
									detail_invoice.schedule_nomor,
									detail_invoice.customer_id,
									detail_invoice.customer_name,
									detail_invoice.tool_id,
									detail_invoice.tool_name,
									SUM(detail_invoice.qty_result) AS qty,
									detail_invoice.hpp,
									detail_invoice.price,
									detail_invoice.diskon,
									detail_invoice.code_teknisi,
									detail_invoice.name_teknisi,
									detail_invoice.invoice_no,
									detail_invoice.tgl_so,
									detail_invoice.actual_process_date,
									detail_invoice.insitu
									
								FROM
									(
									".$Query_Sub."
								) detail_invoice 
								WHERE ".$WHERE."
								GROUP BY
									detail_invoice.code_teknisi,
									detail_invoice.letter_order_id,
									detail_invoice.tool_id
								";
			
			//echo $Query_Find;exit;
			$rows_Detail	= $this->db->query($Query_Find)->result_array();
			if($rows_Detail){
				
				$Ok_Proses		= 1;
			}
		}
		
		if($Ok_Proses == 1){
			$data = array(
				'title'			=> 'TECHNICIAN INCENTIVE CPR PROCESS',
				'action'		=> 'technician_incentive_cpr_process',
				'rows_detail'	=> $rows_Detail,
				'rows_teknisi'	=> $rows_Teknisi
			);
			
			$this->load->view($this->folder.'/v_incentive_technician_process',$data);
		}else{			
			$this->session->set_flashdata("alert_data", "<div class=\"alert alert-warning\" id=\"flash-message\">No record was found....</div>");
			redirect(site_url('Incentive_technician/'.$Link_Back));
			
		}
	}
	
	
	function save_process_technician_incentive(){
		
		if($this->input->post()){
			//echo"<pre>";print_r($this->input->post());exit;
			$Created_By		= $this->session->userdata('siscal_userid');
			$Created_Date	= date('Y-m-d H:i:s');
			
			$CPR_Date		= date('Y-m-d');
			$CPR_Nomor		= 'CPR-T-'.date('Ym').'-'.date('dHis');
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
			
			$Has_Ins_CPR_Head	= $this->db->insert('technician_incentives',$Ins_CPR_Head);
			if($Has_Ins_CPR_Head !== TRUE){
				$Pesan_Error	= 'Error Insert CPR Header';
			}
			
			if($detDetail){
				$intD	= 0;
				foreach($detDetail as $keyDetail=>$valDetail){
					$intD++;
					$Code_Detail	= $CPR_Nomor.'-'.$intD;
					$Ins_CPR_Det	= array(
						'id'						=> $Code_Detail,
						'technician_incentive_id'	=> $CPR_Nomor,
						'invoice_no'				=> $valDetail['invoice_no'],
						'quotation_id'				=> $valDetail['quotation_id'],
						'quotation_nomor'			=> $valDetail['quotation_nomor'],
						'customer_id'				=> $valDetail['customer_id'],
						'customer_name'				=> $valDetail['customer_name'],
						'letter_order_id'			=> $valDetail['letter_order_id'],
						'no_so'						=> $valDetail['no_so'],
						'tool_id'					=> $valDetail['tool_id'],
						'tool_name'					=> $valDetail['tool_name'],
						'qty'						=> $valDetail['qty'],
						'price'						=> $valDetail['price'],
						'hpp'						=> $valDetail['hpp'],
						'diskon'					=> $valDetail['diskon'],
						'net_total'					=> $valDetail['net_total'],
						'nil_incentive'				=> str_replace(',','',$valDetail['nil_incentive']),
						'tot_incentive'				=> str_replace(',','',$valDetail['tot_incentive'])
					);
					
					$Has_Ins_CPR_Det	= $this->db->insert('technician_incentive_details',$Ins_CPR_Det);
					if($Has_Ins_CPR_Det !== TRUE){
						$Pesan_Error	= 'Error Insert CPR Detail';
					}
					$Arr_Imp_Code		= array();
					$Query_Tool			= "SELECT
												det_trans.id
											FROM
												trans_data_details det_trans
											INNER JOIN trans_details head_trans ON det_trans.trans_detail_id = head_trans.id
											WHERE
												det_trans.flag_proses = 'Y'
											AND det_trans.actual_teknisi_id = '".$Member_Code."'
											AND det_trans.flag_insentif <> 'Y'
											AND det_trans.tool_id = '".$valDetail['tool_id']."'
											AND head_trans.letter_order_id = '".$valDetail['letter_order_id']."'
											ORDER BY
												det_trans.id ASC
											LIMIT ".$valDetail['qty'];
					
					$rows_Tool		= $this->db->query($Query_Tool)->result();
					if($rows_Tool){
						foreach($rows_Tool as $keyTool=>$valTool){
							$Code_Trans	= $valTool->id;
							$Arr_Imp_Code[]	= $Code_Trans;
						}
						unset($rows_Tool);
					}
					
					if($Arr_Imp_Code){
						$Imp_Code		= implode("','",$Arr_Imp_Code);
						$Upd_Trans		= "UPDATE trans_data_details SET flag_insentif = 'Y' WHERE id IN('".$Imp_Code."')";
						
						$Has_Upd_Trans 	= $this->db->query($Upd_Trans);
						if($Has_Upd_Trans !== TRUE){
							$Pesan_Error	= 'Error Update Trans Data Detail';
						}
						
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
				history('Create Technician Incentive CPR '.$CPR_Nomor);
			}
			
			
		}else{
			$Arr_Return		= array(
				'status'		=> 2,
				'pesan'			=> 'No Record Was Found........'
			);
		}
		echo json_encode($Arr_Return);
	}
	
	
	
	
	function view_technician_cpr(){
		$rows_Header		= $rows_Detail	= array();
		if($this->input->post()){
			$Code_CPR			= $this->input->post('code');
			$rows_Header		= $this->db->get_where('technician_incentives',array('id'=>$Code_CPR))->result();			
			$rows_Detail		= $this->db->get_where('technician_incentive_details',array('technician_incentive_id'=>$Code_CPR))->result();
		}
		$data = array(
			'title'			=> 'TECHNICIAN CPR DETAIL',
			'rows_header'	=> $rows_Header,
			'rows_detail'	=> $rows_Detail,
			'action'		=> 'view_technician_cpr',
			'category'		=> 'view'
		);
		$this->load->view($this->folder.'/v_incentive_technician_preview',$data); 
	}
	
	
	
	function print_technician_cpr($Code_CPR = ''){
		$rows_Header		= $rows_Detail	= array();
		if($this->input->get()){
			$Code_CPR			= urldecode($this->input->get('cpr'));
			$rows_Header		= $this->db->get_where('technician_incentives',array('id'=>$Code_CPR))->row();			
			$rows_Detail		= $this->db->get_where('technician_incentive_details',array('technician_incentive_id'=>$Code_CPR))->result();
		}
		
		$data 			= array(
			'title'			=> 'PRINT INCENTIVE CPR',
			'action'		=> 'print_technician_cpr',
			'rows_header'	=> $rows_Header,
			'rows_detail'	=> $rows_Detail,
			'printby'		=> $this->session->userdata('siscal_username'),
			'today' 		=> date("Y-m-d H:i:s")			
		);	
		
		
		$this->load->view($this->folder.'/v_incentive_technician_print',$data); 
	}
	
	
	function payment_technician_cpr(){
		$rows_Header		= $rows_Detail	= array();
		if($this->input->post()){
			$Code_CPR			= $this->input->post('code');
			$rows_Header		= $this->db->get_where('technician_incentives',array('id'=>$Code_CPR))->result();			
			$rows_Detail		= $this->db->get_where('technician_incentive_details',array('technician_incentive_id'=>$Code_CPR))->result();
		}
		$data = array(
			'title'			=> 'TECHNICIAN CPR DETAIL',
			'rows_header'	=> $rows_Header,
			'rows_detail'	=> $rows_Detail,
			'action'		=> 'payment_technician_cpr',
			'category'		=> 'update'
		);
		$this->load->view($this->folder.'/v_incentive_technician_preview',$data); 
	}
	
	
	function save_payment_technician_cpr(){
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
			
			
			$rows_Check		= $this->db->get_where('technician_incentives',array('id'=>$Code_CPR))->row();
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
				$this->db->update('technician_incentives',$Upd_header,array('id'=>$Code_CPR));
				
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
					history('Update Technician Incentive CPR '.$Code_CPR);
				}
			}
			
		}
		echo json_encode($Arr_Return);
		
	}
	
	
	function cancel_technician_cpr(){
		$rows_Header		= $rows_Detail	= array();
		if($this->input->post()){
			$Code_CPR			= $this->input->post('code');
			$rows_Header		= $this->db->get_where('technician_incentives',array('id'=>$Code_CPR))->result();			
			$rows_Detail		= $this->db->get_where('technician_incentive_details',array('technician_incentive_id'=>$Code_CPR))->result();
		}
		$data = array(
			'title'			=> 'TECHNICIAN CPR CANCELLATION',
			'rows_header'	=> $rows_Header,
			'rows_detail'	=> $rows_Detail,
			'action'		=> 'cancel_technician_cpr',
			'category'		=> 'cancel'
		);
		$this->load->view($this->folder.'/v_incentive_technician_preview',$data); 
	}
	
	function save_cancel_technician_cpr(){
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
			
			$rows_Check		= $this->db->get_where('technician_incentives',array('id'=>$Code_CPR))->row();
			//echo"<pre>";print_r($rows_Check);exit;
			if($rows_Check->status !== 'OPN'){
				$Arr_Return		= array(
					'status'		=> 2,
					'pesan'			=> 'Data has been modified by other process..'
				);		
			}else{
				$this->db->trans_begin();
				
				$Pesan_Error	= '';				
				$rows_Detail	= $this->db->get_where('technician_incentive_details',array('technician_incentive_id'=>$Code_CPR))->result();
				
				$Upd_header	= array(
					'cancel_by'		=> $cancel_By,
					'cancel_date'	=> $cancel_Date,
					'cancel_reason'	=> $Reason,
					'status'		=> 'CNC',
					'modified_by'	=> $cancel_By,
					'modified_date'	=> $cancel_Date
				);				
				$Has_Upd_CPR = $this->db->update('technician_incentives',$Upd_header,array('id'=>$Code_CPR));
				if($Has_Upd_CPR !== TRUE){
					$Pesan_Error	= 'Error Update CPR Header';
				}
				
				if($rows_Detail){
					
					$Arr_Imp_Code		= array();
					foreach($rows_Detail as $keyDetail=>$valDetail){
						$Code_Tool		= $valDetail->tool_id;
						$Qty_Tool		= $valDetail->qty;
						$SO_Tool		= $valDetail->letter_order_id;
						
						$Query_Tool		= "SELECT
												det_trans.id
											FROM
												trans_data_details det_trans
											INNER JOIN trans_details head_trans ON det_trans.trans_detail_id = head_trans.id
											WHERE
												det_trans.flag_proses = 'Y'
											AND det_trans.actual_teknisi_id = '".$rows_Check->member_id."'
											AND det_trans.flag_insentif = 'Y'
											AND det_trans.tool_id = '".$Code_Tool."'
											AND head_trans.letter_order_id = '".$SO_Tool."'
											ORDER BY
												det_trans.id ASC
											LIMIT ".$Qty_Tool;
						$rows_Tool		= $this->db->query($Query_Tool)->result();
						if($rows_Tool){
							foreach($rows_Tool as $keyTool=>$valTool){
								$Code_Trans	= $valTool->id;
								$Arr_Imp_Code[]	= $Code_Trans;
							}
							unset($rows_Tool);
						}
						
					}
					
					if($Arr_Imp_Code){
						$Imp_Code		= implode("','",$Arr_Imp_Code);
						$Upd_Trans		= "UPDATE trans_data_details SET flag_insentif = 'N' WHERE id IN('".$Imp_Code."')";
						
						$Has_Upd_Trans 	= $this->db->query($Upd_Trans);
						if($Has_Upd_Trans !== TRUE){
							$Pesan_Error	= 'Error Update Trans Data Detail';
						}
						
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
					history('Cancel Technician CPR  '.$Code_CPR);
				}
				
			}
		}
		echo json_encode($Arr_Return);
		
	}
	
	function export_technician_incentive_outs(){
		$Code_Teknisi	= '';
		if($this->input->get()){
			$Code_Teknisi	= urldecode($this->input->get('teknisi'));
		}
		$Judul			='Technician Incentive Report';
		$rows_Teknisi	= $this->master_model->getArray('members',array('division_id'	=> 'DIV-002'),'id','nama');
		$WHERE_Find		= "1=1";
		if($Code_Teknisi){
			if(!empty($WHERE_Find))$WHERE_Find .=" AND ";
			$WHERE_Find .="code_teknisi = '".$Code_Teknisi."'";
			$Judul		.=' - '.strtoupper($rows_Teknisi[$Code_Teknisi]);
		}
		
		$Query_Sub		= $this->QueryProcess();
		
		
		
		$sql = "SELECT
					detail_invoice.quotation_id,
					detail_invoice.quotation_nomor,
					detail_invoice.letter_order_id,
					detail_invoice.no_so,
					detail_invoice.schedule_detail_id,
					detail_invoice.schedule_nomor,
					detail_invoice.customer_id,
					detail_invoice.customer_name,
					detail_invoice.tool_id,
					detail_invoice.tool_name,
					SUM(detail_invoice.qty_result) AS qty,
					detail_invoice.hpp,
					detail_invoice.price,
					detail_invoice.diskon,
					detail_invoice.code_teknisi,
					detail_invoice.name_teknisi,
					detail_invoice.invoice_no
					
				FROM
					(
					".$Query_Sub."
				) detail_invoice 
				WHERE ".$WHERE_Find."
				GROUP BY
					detail_invoice.code_teknisi,
					detail_invoice.letter_order_id,
					detail_invoice.tool_id
				";
		
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
		$sheet->getStyle('A'.$Row.':M'.$NewRow)->applyFromArray($style_header);
		$sheet->mergeCells('A'.$Row.':M'.$NewRow);



		// header
		$NewRow++;
		
		$sheet->setCellValue('A'.$NewRow, 'No');
		$sheet->getStyle('A'.$NewRow)->applyFromArray($style_header);

		$sheet->setCellValue('B'.$NewRow, 'Quotation No');
		$sheet->getStyle('B'.$NewRow)->applyFromArray($style_header);

		$sheet->setCellValue('C'.$NewRow, 'No SO');
		$sheet->getStyle('C'.$NewRow)->applyFromArray($style_header);

		$sheet->setCellValue('D'.$NewRow, 'Customer');
		$sheet->getStyle('D'.$NewRow)->applyFromArray($style_header);

		$sheet->setCellValue('E'.$NewRow, 'Teknisi');
		$sheet->getStyle('E'.$NewRow)->applyFromArray($style_header);

		$sheet->setCellValue('F'.$NewRow, 'Nama Alat');
		$sheet->getStyle('F'.$NewRow)->applyFromArray($style_header);

		$sheet->setCellValue('G'.$NewRow, 'Harga');
		$sheet->getStyle('G'.$NewRow)->applyFromArray($style_header);

		$sheet->setCellValue('H'.$NewRow, 'Qty');
		$sheet->getStyle('H'.$NewRow)->applyFromArray($style_header);

		$sheet->setCellValue('I'.$NewRow, 'Diskon (%)');
		$sheet->getStyle('I'.$NewRow)->applyFromArray($style_header);

		$sheet->setCellValue('J'.$NewRow, 'Nett');
		$sheet->getStyle('J'.$NewRow)->applyFromArray($style_header);

		$sheet->setCellValue('K'.$NewRow, 'Invoice No');
		$sheet->getStyle('K'.$NewRow)->applyFromArray($style_header);

		$sheet->setCellValue('L'.$NewRow, 'Incentive (1%)');
		$sheet->getStyle('L'.$NewRow)->applyFromArray($style_header);

		$sheet->setCellValue('M'.$NewRow, 'Ruang Lingkup');
		$sheet->getStyle('M'.$NewRow)->applyFromArray($style_header);
		
		$rows_data	= $this->db->query($sql)->result_array();
		if($rows_data){
			$loop	=0;
			$sekarang	= date('Y-m-d');
			foreach($rows_data as $key=>$row){
				$loop++;
				$NewRow++;
				
				$Code_Quot		= $row['quotation_id'];
				$Nomor_Quot		= $row['quotation_nomor'];
				$Code_Letter	= $row['letter_order_id'];
				$Nomor_Letter	= $row['no_so'];
				$Code_Sched		= $row['schedule_detail_id'];
				$Nomor_Sched	= $row['schedule_nomor'];
				$Code_Cust		= $row['customer_id'];
				$Name_Cust		= $row['customer_name'];
				$Code_Tool		= $row['tool_id'];
				$Name_Tool		= $row['tool_name'];
				$Code_Tech		= $row['code_teknisi'];
				$Name_Tech		= $row['name_teknisi'];
				$Qty_Tool		= $row['qty'];
				$HPP_Tool		= $row['hpp'];
				$Price_Tool		= $row['price'];
				$Disc_Tool		= ($row['diskon'] > 0)?$row['diskon']:0;
				$Code_Invoice	= $row['invoice_no'];
				$Nett_Tool		= round($Qty_Tool * $Price_Tool * (100 - $Disc_Tool) / 100);
				
				$insentif		= round($Nett_Tool * 0.01);
				
				$Lingkup 		= $Instrument = '- ';
				$Query_Tool	= "SELECT
									head_tool.id,
									head_tool.`name` AS tool_name,
									head_ins.`name` AS instrument,
									head_dim.`name` AS dimention
								FROM
									tools head_tool
								INNER JOIN instruments head_ins ON head_tool.instrument_id = head_ins.id
								INNER JOIN dimentions head_dim ON head_tool.dimention_id = head_dim.id
								WHERE
									head_tool.id = '".$Code_Tool."'";
				$rows_Tool	= $this->db->query($Query_Tool)->row();
				if($rows_Tool){
					$Lingkup	= $rows_Tool->dimention;
					$Instrument	= $rows_Tool->instrument;
				}
				
				
				$Mulai_Col	= 0;
				
				
				
				$Mulai_Col++;		
				$Cols	= getColsChar($Mulai_Col);
				$sheet->setCellValue($Cols.$NewRow, $loop);
				$sheet->getStyle($Cols.$NewRow)->applyFromArray($styleArray1);
				
				$Mulai_Col++;		
				$Cols	= getColsChar($Mulai_Col);
				$sheet->setCellValue($Cols.$NewRow, $Nomor_Quot);
				$sheet->getStyle($Cols.$NewRow)->applyFromArray($styleArray1);
				
				$Mulai_Col++;		
				$Cols	= getColsChar($Mulai_Col);
				$sheet->setCellValue($Cols.$NewRow, $Nomor_Letter);
				$sheet->getStyle($Cols.$NewRow)->applyFromArray($styleArray1);
				
				$Mulai_Col++;		
				$Cols	= getColsChar($Mulai_Col);
				$sheet->setCellValue($Cols.$NewRow, $Name_Cust);
				$sheet->getStyle($Cols.$NewRow)->applyFromArray($styleArray1);
				
				$Mulai_Col++;		
				$Cols	= getColsChar($Mulai_Col);
				$sheet->setCellValue($Cols.$NewRow, $Name_Tech);
				$sheet->getStyle($Cols.$NewRow)->applyFromArray($styleArray1);
				
				$Mulai_Col++;		
				$Cols	= getColsChar($Mulai_Col);
				$sheet->setCellValue($Cols.$NewRow, $Name_Tool);
				$sheet->getStyle($Cols.$NewRow)->applyFromArray($styleArray1);
				
				$Mulai_Col++;		
				$Cols	= getColsChar($Mulai_Col);
				$sheet->setCellValue($Cols.$NewRow, number_format($Price_Tool));
				$sheet->getStyle($Cols.$NewRow)->applyFromArray($styleArray1);
				
				
				$Mulai_Col++;		
				$Cols	= getColsChar($Mulai_Col);
				$sheet->setCellValue($Cols.$NewRow, number_format($Qty_Tool));
				$sheet->getStyle($Cols.$NewRow)->applyFromArray($styleArray1);
				
				$Mulai_Col++;		
				$Cols	= getColsChar($Mulai_Col);
				$sheet->setCellValue($Cols.$NewRow, number_format($Disc_Tool));
				$sheet->getStyle($Cols.$NewRow)->applyFromArray($styleArray1);
				
				$Mulai_Col++;		
				$Cols	= getColsChar($Mulai_Col);
				$sheet->setCellValue($Cols.$NewRow, number_format($Nett_Tool));
				$sheet->getStyle($Cols.$NewRow)->applyFromArray($styleArray1);
				
				$Mulai_Col++;		
				$Cols	= getColsChar($Mulai_Col);
				$sheet->setCellValue($Cols.$NewRow, $Code_Invoice);
				$sheet->getStyle($Cols.$NewRow)->applyFromArray($styleArray1);
				
				$Mulai_Col++;		
				$Cols	= getColsChar($Mulai_Col);
				$sheet->setCellValue($Cols.$NewRow, number_format($insentif));
				$sheet->getStyle($Cols.$NewRow)->applyFromArray($styleArray1);
				
				$Mulai_Col++;		
				$Cols	= getColsChar($Mulai_Col);
				$sheet->setCellValue($Cols.$NewRow, $Lingkup);
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
		header('Content-Disposition: attachment;filename="Laporan_Technician_Incentive_Outs_'.date('YmdHis').'.xls"');
		//unduh file
		$objWriter->save("php://output");
		exit;
	}
}