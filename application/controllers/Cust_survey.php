<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cust_survey extends CI_Controller { 
	public function __construct(){
        parent::__construct();	
		
		$this->load->database();
        if(!$this->session->userdata('isSISCALlogin')){
			redirect('login');
		}
		$this->load->model('master_model');
		$controller				= ucfirst(strtolower($this->uri->segment(1)));
		$this->Arr_Akses		= getAcccesmenu($controller);
		
		$this->folder			= 'Survey';
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
			'title'			=> 'MASTER SURVEY',
			'action'		=> 'index',
			'akses_menu'	=> $Arr_Akses
		);
		history('View List Master Survey');
		$this->load->view($this->folder.'/v_master_survey',$data);
	}
	function get_data_display(){
		$Arr_Akses		= $this->Arr_Akses;
		
		$WHERE			= "1=1";
		
		$requestData	= $_REQUEST;
		
		$like_value     = $requestData['search']['value'];
        $column_order   = $requestData['order'][0]['column'];
        $column_dir     = $requestData['order'][0]['dir'];
        $limit_start    = $requestData['start'];
        $limit_length   = $requestData['length'];
		
		$columns_order_by = array(
			0 => 'title_survey',
			1 => 'descr',
			2 => 'valid_start',
			3 => 'valid_date'
		);
		
		
		
		if($like_value){
			if(!empty($WHERE))$WHERE	.=" AND ";
			$WHERE	.="(
						  title_survey LIKE '%".$this->db->escape_like_str($like_value)."%'
						  OR DATE_FORMAT(valid_start, '%d %b %Y') LIKE '%".$this->db->escape_like_str($like_value)."%'
						   OR DATE_FORMAT(valid_date, '%d %b %Y') LIKE '%".$this->db->escape_like_str($like_value)."%'
						  OR descr LIKE '%".$this->db->escape_like_str($like_value)."%'
						)";
		}
		
		
		
		$sql = "SELECT
					*,
					(@row:=@row+1) AS urut
				FROM
					crm_surveys,
				(SELECT @row:=0) r 
				WHERE ".$WHERE;
		//print_r($sql);exit();
		$fetch['totalData'] 	= $this->db->query($sql)->num_rows();
		$fetch['totalFiltered']	= $this->db->query($sql)->num_rows();

		

		$sql .= " ORDER BY ".$columns_order_by[$column_order]." ".$column_dir." ";
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
			
			$Code_Survey	= $row['code_survey'];
			$Title_Survey	= $row['title_survey'];
			$Valid_Start	= date('d-m-Y',strtotime($row['valid_start']));
			$Valid_End		= date('d-m-Y',strtotime($row['valid_end']));
			$Descr_Survey	= $row['descr'];			
			$Status_Survey	= $row['sts_survey'];
			
			$Lable_Status	= 'OPEN';
			$Color_Status	= 'bg-green-active';
			if($Status_Survey === 'CNC'){
				$Lable_Status	= 'CANCELED';
				$Color_Status	= 'bg-orange-active';
			}else if($Status_Survey === 'SCH'){
				$Lable_Status	= 'CLOSE';
				$Color_Status	= 'bg-maroon-active';
			}
			$Ket_Status		= '<span class="badge '.$Color_Status.'">'.$Lable_Status.'</span>';
			
			
			$Template		= '<a href="'.site_url().'/Cust_survey/detail_customer_survey?nomor_survey='.urlencode($Code_Survey).'" class="btn btn-sm btn-primary" title="DETAIL SURVEY"> <i class="fa fa-search"></i> </a>';
			if(($Arr_Akses['create'] == '1' || $Arr_Akses['update'] == '1') && $Status_Survey === 'OPN'){
				$Template		.= '&nbsp;&nbsp;<a href="'.site_url().'/Cust_survey/cancel_customer_survey?nomor_survey='.urlencode($Code_Survey).'" class="btn btn-sm btn-warning" title="CANCEL SURVEY"> <i class="fa fa-trash-o"></i> </a>';
			}
			
			
			
			
			$nestedData		= array();
			$nestedData[]	= $Title_Survey;
			$nestedData[]	= $Descr_Survey;
			$nestedData[]	= $Valid_Start;
			$nestedData[]	= $Valid_End;
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
	function create_customer_survey(){
		$Arr_Akses			= $this->Arr_Akses;
		if($Arr_Akses['create'] !='1'){
			$this->session->set_flashdata("alert_data", "<div class=\"alert alert-warning\" id=\"flash-message\">You Don't Have Right To Access This Page, Please Contact Your Administrator....</div>");
			redirect(site_url('Cust_survey'));
		}
		
		$data = array(
			'title'			=> 'CREATE CUSTOMER SURVEY',
			'action'		=> 'create_customer_survey',
			'akses_menu'	=> $Arr_Akses
		);
		
		$this->load->view($this->folder.'/v_master_survey_add',$data);
		
		
		
	}
	
	function save_create_customer_survey(){
		$rows_Return	= array(
			'status'		=> 2,
			'pesan'			=> 'No record was found...'
		);
		
		if($this->input->post()){
			//echo "<pre>";print_r($this->input->post());exit;
			
			
			$Created_By		= $this->session->userdata('siscal_username');
			$Created_Id		= $this->session->userdata('siscal_userid');
			$Created_Date	= date('Y-m-d H:i:s');
			
			$Survey_Title		= $this->input->post('survey_title');
			$Survey_Description	= $this->input->post('survey_title');
			$Valid_Start		= $this->input->post('survey_valid_start');
			$Valid_End			= $this->input->post('survey_valid_end');
			
			$detDetail			= $this->input->post('detDetail');
			
			
			## CEK JIKA ADA SURVEY YANG MASIH VALID ##
			$Query_Cek	= "SELECT
								*
							FROM
								crm_surveys
							WHERE
								sts_survey NOT IN ('CNC')
							AND (
								(
									'".$Valid_Start."' BETWEEN valid_start
									AND valid_end
								)
								OR (
									'".$Valid_End."' BETWEEN valid_start
									AND valid_end
								)
							)";
			$Num_Cek	= $this->db->query($Query_Cek)->num_rows();
			if($Num_Cek > 0){
				$rows_Return	= array(
					'status'		=> 2,
					'pesan'			=> 'Survey already in list between '.$Valid_Start.' AND '.$Valid_End
				);
			}else{
				$Pesan_Error	= '';
				$this->db->trans_begin();
				
				$Code_Survey	= 'SURVEY-'.date('mdYiHs');
				
				if($detDetail){
					$intLoop	= 0;
					foreach($detDetail as $keyDet=>$valDet){
						$intLoop++;
						$Ins_Detail		= array();
						$Code_Detail	= $Code_Survey.'-'.$intLoop;
						$Question		= ucwords(strtolower($valDet['question']));
						$Question_Type	= $valDet['question_type'];
						
						$Quest_Answer	= '';
						if(isset($valDet['choice_answer']) && !empty($valDet['choice_answer'])){
							$Quest_Answer	= ucwords(strtolower($valDet['choice_answer']));
						}
						
						$Ins_Detail		= array(
							'code_detail'		=> $Code_Detail,
							'code_survey'		=> $Code_Survey,
							'question_no'		=> $intLoop,
							'question'			=> $Question,
							'flag_image'		=> 'N',
							'question_type'		=> $Question_Type,							
							'choice_answer'		=> $Quest_Answer,
							'created_by'		=> $Created_By,
							'created_date'		=> $Created_Date
						);
						
						for($x=1;$x<=10;$x++){
							$Val_Choice	= '';
							if(isset($valDet['choice_'.$x]) && !empty($valDet['choice_'.$x])){
								$Val_Choice	= ucwords(strtolower($valDet['choice_'.$x]));
							}
							$Ins_Detail['choice_'.$x]	= $Val_Choice;
						}
						
						$Has_Ins_Detail = $this->db->insert('crm_survey_questions',$Ins_Detail);
						if($Has_Ins_Detail !== TRUE){
							$Pesan_Error	= 'Error Insert Survey Detail';
						}
						
					}
				}
				
				$Ins_Header	= array(
					'code_survey'	=> $Code_Survey,
					'title_survey'	=> $Survey_Title,
					'descr'			=> $Survey_Description,
					'valid_start'	=> $Valid_Start,
					'valid_end'		=> $Valid_End,
					'sts_survey'	=> 'OPN',
					'created_by'	=> $Created_By,
					'created_date'	=> $Created_Date
				);
				
				$Has_Ins_Head = $this->db->insert('crm_surveys',$Ins_Header);
				if($Has_Ins_Head !== TRUE){
					$Pesan_Error	= 'Error Insert Survey Header';
				}
			
				if ($this->db->trans_status() != TRUE || !empty($Pesan_Error)){
					$this->db->trans_rollback();
					$rows_Return		= array(
						'status'		=> 2,
						'pesan'			=> 'Create Customer Survey Process  Failed, '.$Pesan_Error
					);
					history('Create Customer Survey '.$Code_Survey.' - '.$Pesan_Error);
				}else{
					$this->db->trans_commit();
					$rows_Return		= array(
						'status'		=> 1,
						'pesan'			=> 'Create Customer Survey success. Thank you & have a nice day......'
					);
					history('Create Customer Survey '.$Code_Survey.' - Success ');
				}
			}

		}
		
		echo json_encode($rows_Return);
	}
	
	
	
	
	function cancel_customer_survey(){
		$OK_Proses	= 0;
		$rows_Header	= $rows_Detail =  array();
		
		if($this->input->get()){
			$Code_Process	= urldecode($this->input->get('nomor_survey'));
			
			$rows_Header	= $this->db->get_where('crm_surveys',array('code_survey'=>$Code_Process))->row();
			$rows_Detail	= $this->db->get_where('crm_survey_questions',array('code_survey'=>$Code_Process))->result();
			
		}
		
		
		$data = array(
			'title'			=> 'CUSTOMER SURVEY CANCELLATION',
			'action'		=> 'cancel_customer_survey',
			'akses_menu'	=> $this->Arr_Akses,
			'rows_header'	=> $rows_Header,
			'rows_detail'	=> $rows_Detail
		);
		
		$this->load->view($this->folder.'/v_master_survey_cancel',$data);
	}
	
	
	function save_cancel_customer_survey(){
		$rows_Return	= array(
			'status'		=> 2,
			'pesan'			=> 'No Record was found...'
		);
		if($this->input->post()){
			//echo"<pre>";print_r($this->input->post());exit;
			
			$Created_By		= $this->session->userdata('siscal_userid');
			$Created_Name	= $this->session->userdata('siscal_username');
			$Created_Date	= date('Y-m-d H:i:s');
			
			$Code_Order		= $this->input->post('code_survey');
			$Cancel_Reason	= strtoupper($this->input->post('cancel_reason'));
			
			$OK_Proses		= 1;
			$Pesan_Error	= '';
			
			$Find_Exist		= $this->db->get_where('crm_surveys',array('code_survey'=>$Code_Order))->row();
			if($Find_Exist){
				if($Find_Exist->sts_survey !== 'OPN'){
					$OK_Proses		= 0;
					$Pesan_Error	= 'Data has been modified by other process...';					
				}else{
					## CEK JIKA SURVEY SUDAH ADA YANG ISI ##
					$Find_Answer		= $this->db->get_where('crm_survey_answers',array('code_survey'=>$Code_Order))->num_rows();
					if($Find_Answer > 0){
						$OK_Proses		= 0;
						$Pesan_Error	= 'Survey has been answered by customer...';	
					}
				}
			}else{
				$OK_Proses		= 0;
				$Pesan_Error	= 'No record was found..';
			}
			
			if($OK_Proses === 1){
				$this->db->trans_begin();
				$Pesan_Error	= '';
				
				
				$Qry_Upd_Order	= "UPDATE crm_surveys SET sts_survey ='CNC', cancel_by = '".$Created_By."', cancel_date = '".$Created_Date."', cancel_reason = '".$Cancel_Reason."' WHERE code_survey = '".$Code_Order."'";
				$Has_Upd_Order 	= $this->db->query($Qry_Upd_Order);
				if($Has_Upd_Order !== TRUE){
					$Pesan_Error	= 'Error Update Survey Header';
				}
				
				
				
				if ($this->db->trans_status() != TRUE || !empty($Pesan_Error)){
					$this->db->trans_rollback();
					$rows_Return		= array(
						'status'		=> 2,
						'pesan'			=> 'Cancellation Process  Failed, '.$Pesan_Error
					);
					history('Cancellation Customer Survey '.$Code_Order.' - '.$Pesan_Error);
				}else{
					$this->db->trans_commit();
					$rows_Return		= array(
						'status'		=> 1,
						'pesan'			=> 'Cancellation process success. Thank you & have a nice day......'
					);
					history('Cancellation Customer Survey '.$Code_Order.' - '.$Cancel_Reason);
				}
				
			}else{			
				$rows_Return	= array(
					'status'		=> 2,
					'pesan'			=> $Pesan_Error
				);
			}
			

						
		}
		echo json_encode($rows_Return);
	}	
	function detail_customer_survey(){
		$rows_Header	= $rows_Detail =  $rows_Answer = array();
		
		if($this->input->get()){
			$Code_Process	= urldecode($this->input->get('nomor_survey'));
			
			$rows_Header	= $this->db->get_where('crm_surveys',array('code_survey'=>$Code_Process))->row();
			$rows_Detail	= $this->db->get_where('crm_survey_questions',array('code_survey'=>$Code_Process))->result();
			$rows_Answer	= $this->db->get_where('crm_survey_answers',array('code_survey'=>$Code_Process))->result();
			
		}
		
		
		$data = array(
			'title'			=> 'CUSTOMER SURVEY DETAIL',
			'action'		=> 'detail_customer_survey',
			'akses_menu'	=> $this->Arr_Akses,
			'rows_header'	=> $rows_Header,
			'rows_detail'	=> $rows_Detail,
			'rows_answer'	=> $rows_Answer
		);
		
		$this->load->view($this->folder.'/v_master_survey_preview',$data);
	}
	
	
	function preview_detail_question_answer(){
		$rows_Question	= $rows_Answer = $rows_Cust = array();
		
		if($this->input->post()){
			$Code_Process	= $this->input->post('code');
			$rows_Answer	= $this->db->get_where('crm_survey_answers',array('code_answer'=>$Code_Process))->row();
			
			$rows_Cust		= $this->db->get_where('customers',array('id'=>$rows_Answer->customer_id))->row();
			$rows_Header	= $this->db->get_where('crm_surveys',array('code_survey'=>$rows_Answer->code_survey))->row();
			$rows_Detail	= $this->db->get_where('crm_survey_questions',array('code_survey'=>$rows_Answer->code_survey))->result();
			
			
		}
		
		
		$data = array(
			'title'			=> 'ANSWER DETAIL',
			'action'		=> 'preview_detail_question_answer',
			'rows_header'	=> $rows_Header,
			'rows_detail'	=> $rows_Detail,
			'rows_answer'	=> $rows_Answer,
			'rows_cust'		=> $rows_Cust
		);
		
		$this->load->view($this->folder.'/v_master_survey_answer',$data);
	}
	
	function outs_letter_order_driver(){
		$Arr_Akses			= $this->Arr_Akses;
		if($Arr_Akses['create'] !='1'){
			$this->session->set_flashdata("alert_data", "<div class=\"alert alert-warning\" id=\"flash-message\">You Don't Have Right To Access This Page, Please Contact Your Administrator....</div>");
			redirect(site_url('Sales_order_receive'));
		}
		
		$data = array(
			'title'			=> 'CREATE SALES ORDER - DRIVER RECEIVE',
			'action'		=> 'outs_letter_order_driver',
			'akses_menu'	=> $Arr_Akses
		);
		
		$this->load->view($this->folder.'/v_sales_order_driver_outs',$data);
	}
	
	function display_out_sales_order_driver(){
		$Arr_Akses			= $this->Arr_Akses;
		$User_Groupid	= $this->session->userdata('siscal_group_id');
		$WHERE			= "header_rec.flag_sign = 'Y'
							AND header_rec.flag_warehouse = 'N'
							/*
							AND header_rec.flag_so = 'N'
							*/
							AND (
								det_rec.letter_order_id IS NULL
								OR det_rec.letter_order_id = ''
								OR det_rec.letter_order_id = '-'
							)";
		if($User_Groupid == '2'){
			$User_Member	= $this->session->userdata('siscal_member_id');
			if(!empty($WHERE))$WHERE	.=" AND ";
			$WHERE	.="header_quot.member_id = '".$User_Member."'";
		}
		
		$Month_Find		= $this->input->post('bulan');
		$Year_Find		= $this->input->post('tahun');
		$requestData	= $_REQUEST;
		
		$like_value     = $requestData['search']['value'];
        $column_order   = $requestData['order'][0]['column'];
        $column_dir     = $requestData['order'][0]['dir'];
        $limit_start    = $requestData['start'];
        $limit_length   = $requestData['length'];
		
		$columns_order_by = array(
			0 => 'header_rec.driver_name',
			1 => 'header_rec.datet',
			2 => 'header_quot.nomor',
			3 => 'header_rec.customer_name',
			4 => 'header_quot.pono',
			5 => 'header_quot.podate',
			6 => 'header_quot.member_name'
		);
		
		
		
		if($like_value){
			if(!empty($WHERE))$WHERE	.=" AND ";
			$WHERE	.="(
						  header_rec.driver_name LIKE '%".$this->db->escape_like_str($like_value)."%'
						  OR DATE_FORMAT(header_rec.datet, '%d %b %Y') LIKE '%".$this->db->escape_like_str($like_value)."%'
						  OR header_quot.nomor LIKE '%".$this->db->escape_like_str($like_value)."%'
						  OR header_rec.customer_name LIKE '%".$this->db->escape_like_str($like_value)."%'
						  OR header_quot.pono LIKE '%".$this->db->escape_like_str($like_value)."%'
						  OR DATE_FORMAT(header_quot.podate, '%d %b %Y') LIKE '%".$this->db->escape_like_str($like_value)."%'
						  OR header_quot.member_name LIKE '%".$this->db->escape_like_str($like_value)."%'
						)";
		}
		
		
		
		$sql = "SELECT
					header_rec.*,
					header_quot.id AS code_quotation,
					header_quot.nomor AS nomor_quotation,
					header_quot.podate,
					header_quot.pono,
					header_quot.member_id AS code_sales,
					header_quot.member_name AS name_sales,
					(@row:=@row+1) AS urut
				FROM
					quotation_driver_detail_receives det_rec
				INNER JOIN quotation_driver_receives header_rec ON det_rec.code_receive = header_rec.id
				INNER JOIN quotations header_quot ON det_rec.quotation_id = header_quot.id,
				(SELECT @row:=0) r 
				WHERE ".$WHERE."
				GROUP BY 
					det_rec.quotation_id,
					header_rec.id
				";
		//print_r($sql);exit();
		$fetch['totalData'] 	= $this->db->query($sql)->num_rows();
		$fetch['totalFiltered']	= $this->db->query($sql)->num_rows();

		

		$sql .= " ORDER BY header_rec.datet DESC,".$columns_order_by[$column_order]." ".$column_dir." ";
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
			$Custid			= $row['customer_id'];
			$Customer		= $row['customer_name'];
			$Marketing		= strtoupper($row['name_sales']);
			$Driver			= strtoupper($row['driver_name']);
			$Quot_PO		= $row['pono'];
			$Quot_PO_Date	= date('d-m-Y',strtotime($row['podate']));
			
			$Code_Quot		= $row['code_quotation'];
			$Nomor_Quot		= $row['nomor_quotation'];
			
			
			$Template		= '';			
			if($Arr_Akses['create'] == '1'){
				$Template		= '<a href="'.site_url().'/Sales_order_receive/create_sales_order_driver?nomor_rec='.urlencode($Code_Receive).'&nomor_quot='.urlencode($Code_Quot).'" class="btn btn-sm bg-navy-active"  title="CREATE SALES ORDER - DRIVER RECEIVE"> <i class="fa fa-plus"></i> </a>';
			}
			
			
			
			
			$nestedData		= array();
			$nestedData[]	= $Driver;
			$nestedData[]	= $Date_Receive;
			$nestedData[]	= $Nomor_Quot;
			$nestedData[]	= $Customer;
			$nestedData[]	= $Quot_PO;
			$nestedData[]	= $Quot_PO_Date;
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
	
	
	function create_sales_order_driver(){
		$rows_Header	= $rows_Customer = $rows_Detail = $rows_Receive = array();
		if($this->input->get()){
			$Code_Quot		= urldecode($this->input->get('nomor_quot'));
			$Code_Receive	= urldecode($this->input->get('nomor_rec'));
			$rows_Receive	= $this->db->get_where('quotation_driver_receives',array('id'=>$Code_Receive))->row();
			$rows_Header	= $this->db->get_where('quotations',array('id'=>$Code_Quot))->row();
			$rows_Customer	= $this->db->get_where('customers',array('id'=>$rows_Header->customer_id))->row();
			$Query_Detail	= "SELECT
									det_rec.tool_id,
									det_rec.tool_name,
									det_rec.quotation_detail_id,
									det_rec.quotation_id,
									COUNT(det_rec.id) AS total_outs,
									GROUP_CONCAT(det_rec.id) AS code_detail,
									GROUP_CONCAT(det_rec.descr) AS descr_receive,
									det_quot.cust_tool,
									det_quot.`range`,
									det_quot.piece_id,
									det_quot.supplier_id,
									det_quot.supplier_name,
									det_quot.descr AS cust_request
								FROM
									quotation_driver_detail_receives det_rec
								INNER JOIN quotation_details det_quot ON det_rec.quotation_detail_id = det_quot.id
								WHERE
									(
										det_rec.letter_order_id IS NULL
										OR det_rec.letter_order_id = '-'
										OR det_rec.letter_order_id = ''
									)
								AND det_rec.quotation_id = '".$Code_Quot."'
								AND code_receive = '".$Code_Receive."'
								GROUP BY
									det_rec.quotation_detail_id";
			$rows_Detail	= $this->db->query($Query_Detail)->result();
			
		}
		$rows_Supplier		= $this->master_model->getArray('suppliers',array('id !='=>'COMP-001'),'id','supplier');
		$data = array(
			'title'			=> 'CREATE SALES ORDER - DRIVER RECEIVE',
			'action'		=> 'create_sales_order_driver',
			'akses_menu'	=> $this->Arr_Akses,
			'rows_header'	=> $rows_Header,
			'rows_detail'	=> $rows_Detail,
			'rows_cust'		=> $rows_Customer,
			'rows_receive'	=> $rows_Receive,
			'rows_supplier'	=> $rows_Supplier
		);
		
		$this->load->view($this->folder.'/v_sales_order_driver_add',$data);
		
	}
	
	
	function save_create_letter_order_driver(){
		$rows_Return	= array(
			'status'		=> 2,
			'pesan'			=> 'No record was found...'
		);
		
		if($this->input->post()){
			//echo "<pre>";print_r($this->input->post());exit;
			
			
			$Created_By		= $this->session->userdata('siscal_username');
			$Created_Id		= $this->session->userdata('siscal_userid');
			$Created_Date	= date('Y-m-d H:i:s');
			
			$Code_Receive	= $this->input->post('code_receive');
			$Code_Quot		= $this->input->post('quotation_id');
			$Nomor_Quot		= $this->input->post('quotation_nomor');
			$Nocust			= $this->input->post('customer_id');
			$Customer		= $this->input->post('customer_name');
			$PIC_Name		= strtoupper($this->input->post('pic_name'));
			$PIC_Phone		= str_replace(array('+','-',' '),'',$this->input->post('pic_phone'));
			
			$Delv_Address	= strtoupper($this->input->post('address_send'));
			$Inv_Address	= strtoupper($this->input->post('address_inv'));
			$Cert_Address	= strtoupper($this->input->post('address_sertifikat'));
			$Cust_Address	= strtoupper($this->input->post('address'));
			
			$Delv_Notes		= strtoupper($this->input->post('send_notes'));
			$Inv_Notes		= strtoupper($this->input->post('inv_notes'));
			
			$detDetail		= $this->input->post('detDetail');
			$Pesan_Error	= '';
			$OK_Proses		= 1;
			$rows_Receive	= $this->db->get_where('quotation_driver_receives',array('id'=>$Code_Receive))->row();
			if($rows_Receive){
				if($rows_Receive->flag_warehouse === 'Y'){
					$Pesan_Error	= 'Data has been receive by warehouse. Please process SO Receive By Warehouse...';
					$OK_Proses		= 0;
				}
			}else{
				$Pesan_Error	= 'No receive record was found...';
				$OK_Proses		= 0;
			}
			
			if($OK_Proses === 0){
				$rows_Return	= array(
					'status'		=> 2,
					'pesan'			=> $Pesan_Error
				);
			}else{
			
				$this->db->trans_begin();
				
				$Date_Now		= date('Y-m-d');
				$Tahun_Now		= date('Y');
				$Month_Now		= date('m');
				$Bulan_Now		= date('n');
				$YearMonth		= date('Ym');
				
				$RomawiMonth	= getRomawi($Bulan_Now);
				
				## AMBIL NOMOR URUT ##
				$Urut_Code		= $Urut_Nomor	= 1;
				$Qry_Urut_Code	= "SELECT
										MAX(
											CAST(
												SUBSTRING_INDEX(id, '-' ,- 1) AS UNSIGNED
											)
										) AS nomor_urut
									FROM
										letter_orders
									WHERE
										YEAR (tgl_so) = '".$Tahun_Now."'";
				$rows_Urut_Code	= $this->db->query($Qry_Urut_Code)->row();
				if($rows_Urut_Code){
					$Urut_Code	= intval($rows_Urut_Code->nomor_urut) + 1;
				}
				$Pref_Urut		= sprintf('%05d',$Urut_Code);
				if($Urut_Code >=100000){
					$Pref_Urut	= $Urut_Code;
				}
				
				$Code_Letter		= 'SO-V3'.$YearMonth.'-'.$Pref_Urut;
				
				$Nomor_Project		= '';
				$Qry_Urut_Nomor		= "SELECT project_code, project_no FROM quotations WHERE id = '".$Code_Quot."'";
				$rows_Urut_Nomor	= $this->db->query($Qry_Urut_Nomor)->row();
				if($rows_Urut_Nomor){
					$Urut_Nomor		= intval($rows_Urut_Nomor->project_no) + 1;
					$Nomor_Project	= $rows_Urut_Nomor->project_code;
				}
				
				$Pref_Nomor			= getColsChar($Urut_Nomor);
				$Nomor_Letter		= $Pref_Nomor.'-'.$Nomor_Project;
				
				$Ins_Header			= array(
					'id'					=> $Code_Letter,
					'no_so'					=> $Nomor_Letter,
					'tgl_so'				=> $Date_Now,
					'quotation_id'			=> $Code_Quot,
					'customer_id'			=> $Nocust,
					'customer_name'			=> $Customer,
					'address'				=> $Cust_Address,
					'address_inv'			=> $Inv_Address,
					'address_sertifikat'	=> $Cert_Address,
					'address_send'			=> $Delv_Address,
					'pic'					=> $PIC_Name,
					'phone'					=> $PIC_Phone,
					'sts_so'				=> 'OPN',
					'created_date'			=> $Created_Date,
					'created_by'			=> $Created_Id,
					'get_tool'				=> '',
					'flag_so_insitu'		=> 'N',
					'notes_invoice'			=> $Inv_Notes,
					'notes_delivery'		=> $Delv_Notes
				);
				$Arr_Type		= array('I'=>'Insitu','L'=>'Labs','S'=>'Subcon');
				$Get_Tool		= '';
				if($detDetail){
					$intL	= 0;
					foreach($detDetail as $keyDet=>$valDet){
						$intL++;
						$Code_LetterDet	= $Code_Letter.'-'.$intL;
						$Code_Tool		= $valDet['tool_id'];
						$Name_Tool		= $valDet['tool_name'];
						$Supplier_Code	= $valDet['supplier'];
						$Supplier_Name	= '-';
						$Type			= $valDet['tipe'];
						$Code_Receive	= $valDet['code_detail'];
						$Qty			= $valDet['qty'];
						$Range			= $valDet['range'];
						$Satuan			= $valDet['piece_id'];
						$Get_Tool		= $valDet['get_tool'];
						$Description	= $valDet['descr'];
						$Code_QuotDet	= $valDet['quotation_detail_id'];
						
						$rows_Supplier	= $this->db->get_where('suppliers',array('id'=>$Supplier_Code))->row();
						if($rows_Supplier){
							$Supplier_Name	= $rows_Supplier->supplier;
						}
						
						
						$Ins_Detail		= array(
							'id'					=> $Code_LetterDet,
							'letter_order_id'		=> $Code_Letter,
							'quotation_detail_id'	=> $Code_QuotDet,
							'tool_id'				=> $Code_Tool,
							'tool_name'				=> $Name_Tool,
							'range'					=> $Range,
							'piece_id'				=> $Satuan,
							'qty'					=> $Qty,
							'supplier_id'			=> $Supplier_Code,
							'supplier_name'			=> $Supplier_Name,
							'descr'					=> $Description,
							'tipe'					=> $Arr_Type[$Type],
							'get_tool'				=> $Get_Tool,
							'detail_id'				=> $Code_Receive,
							'created_by'			=> $Created_Id,
							'created_date'			=> $Created_Date
						);
						
						$Has_Ins_Detail	= $this->db->insert('letter_order_details',$Ins_Detail);
						if($Has_Ins_Detail !== TRUE){
							$Pesan_Error	= 'Error Insert Letter Order Detail...';
						}
						
						$Qty_Proses		= $Qty;
						$Imp_Code_Rec	= str_replace(",","','",$Code_Receive);
						$Qry_Receive	= "SELECT * FROM quotation_driver_detail_receives WHERE id IN('".$Imp_Code_Rec."') AND (letter_order_id IS NULL OR letter_order_id = '' OR letter_order_id = '-')";
						$rows_Receive	= $this->db->query($Qry_Receive)->result();
						if($rows_Receive){
							foreach($rows_Receive as $keyRec=>$valRec){
								$Qty_Out	= 1;
								if($Qty_Out > 0 && $Qty_Proses > 0){
									$Qty_Update	= $Qty_Out;
									if($Qty_Proses < $Qty_Out){
										$Qty_Update	= $Qty_Proses;
									}
									
									$Upd_Rec_Detail	= "UPDATE quotation_driver_detail_receives SET qty_so = ".$Qty_Update.", letter_order_id = '".$Code_Letter."' WHERE id = '".$valRec->id."'";
									$Has_Upd_RecDet	= $this->db->query($Upd_Rec_Detail);
									if($Has_Upd_RecDet !== TRUE){
										$Pesan_Error	= 'Error Update Quotation Driver Receive Detail...';
									}
									
									$Qty_Proses	-=$Qty_Update;
									
								}
							}
						}
						
						if($Qty_Proses > 0){
							$Pesan_Error	= 'Qty SO Tool Should Less Then Leftover Qty...';	
						}
					}
				}
				
				$Ins_Header['get_tool']	= $Get_Tool;
				$Has_Ins_Header			= $this->db->insert('letter_orders',$Ins_Header);
				if($Has_Ins_Header !== TRUE){
					$Pesan_Error	= 'Error Insert Letter Order Header...';
				}
				
				$Upd_Quot	= array(
					'flag_so'		=> 'Y',
					'noso'			=> $Nomor_Letter,
					'tglso'			=> $Date_Now,
					'project_no'	=> $Urut_Nomor
				);
				
				$Has_Upd_Quot			= $this->db->update('quotations',$Upd_Quot,array('id'=>$Code_Quot));
				if($Has_Upd_Quot !== TRUE){
					$Pesan_Error	= 'Error Update Quotation...';
				}
				
				if ($this->db->trans_status() != TRUE || !empty($Pesan_Error)){
					$this->db->trans_rollback();
					$rows_Return		= array(
						'status'		=> 2,
						'pesan'			=> 'Create Sales Order Process  Failed, '.$Pesan_Error
					);
					history('Create Sales Order '.$Nomor_Letter.' - '.$Pesan_Error);
				}else{
					$this->db->trans_commit();
					$rows_Return		= array(
						'status'		=> 1,
						'pesan'			=> 'Create Sales Order success. Thank you & have a nice day......'
					);
					history('Create Sales Order '.$Nomor_Letter.' - Success ');
				}
			}

		}
		
		echo json_encode($rows_Return);
	}
	
	
	
}