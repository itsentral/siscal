<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Driver_order_incomplete extends CI_Controller { 
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
		$this->file_root		= $_SERVER['DOCUMENT_ROOT'].'/Siscal_Dashboard/';
    }

	public function index(){
		$Arr_Akses			= $this->Arr_Akses;
		if($Arr_Akses['read'] !='1'){
			$this->session->set_flashdata("alert_data", "<div class=\"alert alert-warning\" id=\"flash-message\">You Don't Have Right To Access This Page, Please Contact Your Administrator....</div>");
			redirect(site_url('dashboard'));
		}
		
		$data = array(
			'title'			=> 'INCOMPLETE DRIVER ORDER - RECEIVE TOOL',
			'action'		=> 'index',
			'akses_menu'	=> $Arr_Akses
		);
		history('View List Driver Order  Incomplete');
		$this->load->view($this->folder.'/v_driver_order_incomplete',$data);
	}
	function get_data_display(){
		$Arr_Akses		= $this->Arr_Akses;
		$Current_Date	= date('Y-m-d');
		$WHERE			= "x_outs_order.plan_date < '".$Current_Date."'";
		
		$Month			= $this->input->post('bulan');
		$Year			= $this->input->post('tahun');
		$requestData	= $_REQUEST;
		
		$like_value     = $requestData['search']['value'];
        $column_order   = $requestData['order'][0]['column'];
        $column_dir     = $requestData['order'][0]['dir'];
        $limit_start    = $requestData['start'];
        $limit_length   = $requestData['length'];
		
		$columns_order_by = array(
			0 => 'x_outs_order.order_no',
			1 => 'x_outs_order.plan_date',
			2 => 'x_outs_order.driver_name',
			3 => 'x_outs_order.customer',
			4 => 'x_outs_order.quotation_nomor',
			5 => 'x_outs_order.sts_order'
		);
		
		
		
		if($like_value){
			if(!empty($WHERE))$WHERE	.=" AND ";
			$WHERE	.="(
						  x_outs_order.order_no LIKE '%".$this->db->escape_like_str($like_value)."%'
						  OR DATE_FORMAT(x_outs_order.plan_date, '%d %b %Y') LIKE '%".$this->db->escape_like_str($like_value)."%'
						  OR x_outs_order.customer LIKE '%".$this->db->escape_like_str($like_value)."%'
						  OR x_outs_order.quotation_nomor LIKE '%".$this->db->escape_like_str($like_value)."%'
						  OR x_outs_order.driver_name LIKE '%".$this->db->escape_like_str($like_value)."%'
						  OR x_outs_order.sts_order LIKE '%".$this->db->escape_like_str($like_value)."%'
						)";
		}
		
		if($Month){
			if(!empty($WHERE))$WHERE .=" AND ";
			$WHERE	.="MONTH(x_outs_order.plan_date) = '".$Month."'";
		}
		
		if($Year){
			if(!empty($WHERE))$WHERE .=" AND ";
			$WHERE	.="YEAR(x_outs_order.plan_date) = '".$Year."'";
		}
		
		$sql = "SELECT
					x_outs_order.order_code,
					x_outs_order.order_no,
					x_outs_order.datet,
					x_outs_order.plan_date,
					x_outs_order.driver_name,
					x_outs_order.sts_order,
					x_outs_order.nocust,
					x_outs_order.customer,
					x_outs_order.quotation_id,
					x_outs_order.quotation_nomor,
					(@ROW :=@ROW + 1) AS urut
				FROM
					(
						(
							SELECT
								head_ord.order_code,
								head_ord.order_no,
								head_ord.datet,
								head_ord.plan_date,
								head_ord.driver_name,
								head_ord.sts_order,
								head_ord.company_code AS nocust,
								head_ord.company AS customer,
								head_quot.id AS quotation_id,
								head_quot.nomor AS quotation_nomor
							FROM
								trans_driver_orders head_ord
							INNER JOIN trans_driver_order_details det_ord ON head_ord.order_code = det_ord.order_code
							INNER JOIN quotation_details det_quot ON det_ord.code_process = det_quot.id
							INNER JOIN quotations head_quot ON head_quot.id = det_quot.quotation_id
							INNER JOIN (
								SELECT
									rec_det.spk_driver_tool_id
								FROM
									quotation_driver_detail_receives rec_det
								INNER JOIN quotation_driver_receives head_rec ON rec_det.code_receive = head_rec.id
								WHERE
									head_rec.flag_sign = 'N'
								GROUP BY
									rec_det.spk_driver_tool_id
								UNION
									SELECT
										id AS spk_driver_tool_id
									FROM
										spk_driver_tools
									WHERE
										(
											qty - qty_proses - qty_reschedule
										) > 0
							) x_det_spk ON x_det_spk.spk_driver_tool_id = det_ord.spk_driver_tool_id
							WHERE
								head_ord.sts_order IN ('PRO', 'CLS')
								AND head_ord.type_comp IN ('CUST')
								AND head_ord.category IN ('REC')
							GROUP BY
								head_ord.order_code
						)
						UNION
							(
								SELECT
									head_ord.order_code,
									head_ord.order_no,
									head_ord.datet,
									head_ord.plan_date,
									head_ord.driver_name,
									head_ord.sts_order,
									head_ord.company_code AS nocust,
									head_ord.company AS customer,
									head_quot.id AS quotation_id,
									head_quot.nomor AS quotation_nomor
								FROM
									trans_driver_orders head_ord
								INNER JOIN trans_driver_order_details det_ord ON head_ord.order_code = det_ord.order_code
								INNER JOIN quotation_details det_quot ON det_ord.code_process = det_quot.id
								INNER JOIN quotations head_quot ON head_quot.id = det_quot.quotation_id
								WHERE
									head_ord.sts_order IN ('OPN')
									AND head_ord.type_comp IN ('CUST')
									AND head_ord.category IN ('REC')
								GROUP BY
									head_ord.order_code
							)
					) x_outs_order,
					(SELECT @ROW := 0) r
				WHERE
					".$WHERE."
				GROUP BY
					x_outs_order.order_code,
					x_outs_order.quotation_id";
		//print_r($sql);exit();
		$fetch['totalData'] 	= $this->db->query($sql)->num_rows();
		$fetch['totalFiltered']	= $this->db->query($sql)->num_rows();

		

		$sql .= " ORDER BY x_outs_order.plan_date DESC,".$columns_order_by[$column_order]." ".$column_dir." ";
		$sql .= " LIMIT ".$limit_start." ,".$limit_length." ";
		
		$fetch['query'] = $this->db->query($sql);
		
		$totalData		= $fetch['totalData'];
		$totalFiltered	= $fetch['totalFiltered'];
		$query			= $fetch['query'];
		
		//echo"<pre>";print_r($sql);exit();
		
		$data		= array();
        $urut1  	= 1;
        $urut2  	= 0;
		$Periode_Now= date('Y-m');
		$Tahun_Now	= date('Y');
		$Date_Now	= date('Y-m-d');
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
			
			$Code_Order		= $row['order_code'];
			$Nomor_Order	= $row['order_no'];
			$Process_Date	= $row['plan_date'];
			$Date_Order		= date('d-m-Y',strtotime($row['plan_date']));
			$Code_Cust		= $row['nocust'];
			$Name_Cust		= $row['customer'];
			$Code_Quot		= $row['quotation_id'];
			$Nomor_Quot		= $row['quotation_nomor'];
			$Driver_Name	= $row['driver_name'];
			$Status_Order	= $row['sts_order'];
			
			
			
			$Lable_Status	= 'OPEN';
			$Color_Status	= 'bg-green';
			if($Status_Order === 'CNC'){
				$Lable_Status	= 'CANCELED';
				$Color_Status	= 'bg-orange';
			}else if($Status_Order === 'PRO'){
				$Lable_Status	= 'ON PROCESS';
				$Color_Status	= 'bg-blue';
			}else if($Status_Order === 'CLS'){
				$Lable_Status	= 'CLOSE';
				$Color_Status	= 'bg-navy-active';
			}
			$Ket_Status		= '<span class="badge '.$Color_Status.'">'.$Lable_Status.'</span>';
			
			
			
			$Template		= '<button type="button" class="btn btn-sm btn-primary" onClick = "ActionDetail({code:\''.$Code_Order.'\',action :\'detail_driver_order\',title:\'VIEW DRIVER ORDER\'});" title="VIEW DRIVER ORDER"> <i class="fa fa-search"></i> </button>';			
			if(($Arr_Akses['create'] == '1' || $Arr_Akses['update'] == '1') && $Status_Order === 'OPN'){
				$Template		.= '&nbsp;&nbsp;<button type="button" class="btn btn-sm btn-warning" onClick = "ActionPreview({code:\''.$Code_Order.'\',action :\'cancel_driver_order\',title:\'CANCEL DRIVER ORDER\'});" title="CANCEL DRIVER ORDER"> <i class="fa fa-trash-o"></i> </button>';
				
			}
			
			
			
			
			$nestedData		= array();
			$nestedData[]	= $Nomor_Order;
			$nestedData[]	= $Date_Order;
			$nestedData[]	= $Driver_Name;
			$nestedData[]	= $Name_Cust;
			$nestedData[]	= $Nomor_Quot;
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
	
	function detail_driver_order(){
		$OK_Proses	= 0;
		$rows_Header	= $rows_Detail =  array();
		
		if($this->input->get()){
			$Code_Process	= urldecode($this->input->get('code'));			
			$rows_Header	= $this->db->get_where('trans_driver_orders',array('order_code'=>$Code_Process))->row();
			$rows_Detail	= $this->db->get_where('trans_driver_order_details',array('order_code'=>$Code_Process))->result();			
		}
		
		
		$data = array(
			'title'			=> 'DETAIL INCOMPLETE DRIVER ORDER - RECEIVE TOOL',
			'action'		=> 'detail_driver_order',
			'akses_menu'	=> $this->Arr_Akses,
			'rows_header'	=> $rows_Header,
			'rows_detail'	=> $rows_Detail
		);
		
		$this->load->view($this->folder.'/v_driver_order_incomplete_preview',$data);
		
	}
	
	function preview_driver_recieve(){
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
			'title'			=> 'PREVIEW DRIVER RECEIVE',
			'action'		=> 'preview_driver_recieve',
			'akses_menu'	=> $this->Arr_Akses,
			'rows_header'	=> $rows_Header,
			'rows_detail'	=> $rows_Detail,
			'rows_quot'		=> $rows_Quot,
			'category'		=> 'view'
		);
		
		$this->load->view($this->folder.'/v_driver_order_incomplete_receive',$data);
	}
	
	
	function cloce_driver_receive(){
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
			'title'			=> 'CLOSE DRIVER RECEIVE',
			'action'		=> 'cloce_driver_receive',
			'akses_menu'	=> $this->Arr_Akses,
			'rows_header'	=> $rows_Header,
			'rows_detail'	=> $rows_Detail,
			'rows_quot'		=> $rows_Quot,
			'category'		=> 'close'
		);
		
		$this->load->view($this->folder.'/v_driver_order_incomplete_receive',$data);
	}
	
	function save_close_driver_receive(){
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
			$Close_Reason	= strtoupper($this->input->post('close_reason'));
			
			$Find_Exist		= $this->db->get_where('quotation_driver_receives',array('id'=>$Code_Receive))->row();
			if($Find_Exist){
				if($Find_Exist->flag_sign !== 'N'){
					$rows_Return	= array(
						'status'		=> 2,
						'pesan'			=> 'Data has been modified by other process...'
					);
				}else{
					$this->db->trans_begin();
					$Pesan_Error	= '';
					
					$Path_Loc       = $this->file_location.'signature_receive_tool/';
					$Upd_Header		= array(
						'flag_sign'		=> 'Y',
						'close_reason'	=> $Close_Reason,
						'close_by'		=> $Created_By,
						'close_date'	=> $Created_Date
					);
					$Has_Upd_Header	= $this->db->update('quotation_driver_receives',$Upd_Header,array('id'=>$Code_Receive));
					if($Has_Upd_Header !== TRUE){
						$Pesan_Error	= 'Error Update Driver Receive..';
					}
					
					
					
					if ($this->db->trans_status() != TRUE || !empty($Pesan_Error)){
						$this->db->trans_rollback();
						$rows_Return		= array(
							'status'		=> 2,
							'pesan'			=> 'Save Process  Failed, '.$Pesan_Error
						);
						history('Close Driver Order Receive'.$Code_Receive.' - '.$Pesan_Error);
					}else{
						$this->db->trans_commit();
						
						$Hasil_Generate	= $this->GenerateReceivePDF($Code_Receive);
						
						$rows_Return		= array(
							'status'		=> 1,
							'pesan'			=> 'Save process success. Thank you & have a nice day......'
						);
						history('Close Driver Order Receive'.$Code_Receive);
					}
					
				}
			}else{
				$rows_Return	= array(
					'status'		=> 2,
					'pesan'			=> 'No Record was found...'
				);
			}
			

						
		}
		echo json_encode($rows_Return);
	}	
	
	function GenerateReceivePDF($Code_Receive = ''){
		
		$Nama_File		= $this->file_location.'quotation_receive_tool/'.$Code_Receive.'.pdf';
		if(file_exists($Nama_File)){
			unlink($Nama_File);
		}
		
		$Path_Loc       = $this->file_location.'signature_receive_tool/';
		$sroot 			= $_SERVER['DOCUMENT_ROOT'];
		include $this->file_root.'application/libraries/MPDF57/mpdf.php';
		$mpdf			= new mPDF('utf-8', 'A4');				// Create new mPDF Document
		$ArrBulan	=array(1=>'Januari','Februari','Maret','April','Mei','Juni','Juli','Agustus','September','Oktober','Nopember','Desember');
		$ArrHari	= array(
			'Sun'	=> 'Minggu',
			'Mon'	=> 'Senin',
			'Tue'	=> 'Selasa',
			'Wed'	=> 'Rabu',
			'Thu'	=> 'Kamis',
			'Fri'	=> 'Jumat',
			'Sat'	=> 'Sabtu'
			);
		//Beginning Buffer to save PHP variables and HTML tags
		ob_start();
		$img_file	= $this->file_root.'assets/img/logo.jpg';
		$img_file2	= $this->file_root.'assets/img/kan.png';
		$img_file3 	= $this->file_root.'assets/img/line.jpg';

		//echo"<pre>";print_r($records);exit;

		?>  

		<style type="text/css">
		@page {
			margin-top: 0.8cm;
			margin-left: 1cm;
			margin-right: 1cm;
			margin-bottom: 0.8cm;
		}
		.font{
			font-family: verdana,arial,sans-serif;
			font-size:14px;
		}
		.fontheader{
			font-family: verdana,arial,sans-serif;
			font-size:13px;
			color:#333333;
			border-width: 1px;
			border-color: #666666;
			border-collapse: collapse;
		}

		table.noborder2 th {
			font-size:11px;
			padding: 1px;
			border-color: #666666;
		}

		table.noborder2 td {	
			padding: 1px;
			border-color: #666666;
			background-color: #ffffff;
			font-size:10px;
			font-family: verdana,arial,sans-serif;
		}
		table.noborder3 td {	
			padding: 1px;
			border-color: #666666;
			background-color: #ffffff;
			font-size:12px;
			font-family: verdana,arial,sans-serif;
		}
		table.noborder, .noborder2,noborder3 {
			font-family: verdana,arial,sans-serif;
		}

		table.noborder th {
			font-size:12px;
			padding: 1px;
			border-color: #666666;
		}

		table.noborder td {	
			padding: 1px;
			border-color: #666666;
			background-color: #ffffff;
			font-size:13px;
			font-family: verdana,arial,sans-serif;
		}

		table.gridtable {
			font-family: verdana,arial,sans-serif;
			font-size:11px;
			color:#333333;
			border-width: 1px;
			border-color: #666666;
			border-collapse: collapse;
		}

		table.gridtable th {
			border-width: 1px;
			padding: 5px;
			border-style: solid;
			border-color: #666666;
			background-color: #f2f2f2;
			
		}

		table.gridtable th.head {
			border-width: 1px;
			padding: 8px;
			border-style: solid;
			border-color: #666666;
			background-color: #7f7f7f;
			color: #ffffff;
		}
		table.gridtable td {
			border-width: 1px;
			padding: 5px;
			border-style: solid;
			border-color: #666666;
			background-color: #ffffff;
		}

		table.gridtable td zero {
			border-width: 1px;
			padding: 5px;
			border-color: #666666;
			background-color: #ffffff;
			
		}

		table.gridtable td.cols {
			border-width: 1px;
			padding: 5px;
			border-style: solid;
			border-color: #666666;
			background-color: #ffffff;
		}

		table.cooltabs {
			font-size:12px;
			font-family: verdana,arial,sans-serif;
			border-width: 1px;
			border-style: solid;
		}

		table.cooltabs th.reg {
			font-family: verdana,arial,sans-serif;
			border-radius: 5px 5px 5px 5px;
			background: #e3e0e4;
			padding: 5px;
		}

		table.cooltabs td.reg {
			font-family: verdana,arial,sans-serif;
			border-radius: 5px 5px 5px 5px;
			padding: 5px;
			border-width: 1px;
		}

		#cooltabs {
			font-family: verdana,arial,sans-serif;
			border-width: 1px;
			border-style: solid;
			border-radius: 5px 5px 5px 5px;
			background: #e3e0e4;
			padding: 5px; 
			width: 800px;
			height: 20px; 
		}

		#cooltabs2{
			font-family: verdana,arial,sans-serif;
			border-width: 1px;
			border-style: solid;
			border-radius: 5px 5px 5px 5px;
			background: #e3e0e4;
			padding: 5px; 
			width: 180px;
			height: 10px;
		}

		#space{
			padding: 3px; 
			width: 180px;
			height: 1px;
		}

		#cooltabshead{
			font-size:12px;
			font-family: verdana,arial,sans-serif;
			border-width: 1px;
			border-style: solid;
			border-radius: 5px 5px 0 0;
			background: #dfdfdf;
			padding: 5px; 
			width: 162px;
			height: 10px;
			float:left;
		}

		#cooltabschild{
			font-size:10px;
			font-family: verdana,arial,sans-serif;
			border-width: 1px;
			border-style: solid;
			border-radius: 0 0 5px 5px;
			padding: 5px; 
			width: 162px;
			height: 10px;
			float:left;
		}

		p {
		  margin: 0 0 0 0;
		}

		p.pos_fixed {
			font-family: verdana,arial,sans-serif;
			position: fixed;
			top: 50px;
			left: 230px;
		}

		p.pos_fixed2 {
			font-family: verdana,arial,sans-serif;
			position: fixed;
			top: 589px;
			left: 230px;
		}

		p.notesmall {
			font-size: 9px;
		}

		.barcode {
			padding: 1.5mm;
			margin: 0;
			vertical-align: top;
			color: #000044;
		}

		.barcodecell {
			text-align: center;
			vertical-align: middle;
			position: fixed;
			top: 14px;
			right: 10px;
		}
		p.pt {
			font-family: verdana,arial,sans-serif;
			font-size:7px;
			position: fixed;
			top: 62px;
			left: 5px;
		}
		h3.pt {
			font-family: calibri,arial,sans-serif;
			position: fixed;	
			top: 175px;
			left: 250px;
			}

		h3 {
			font-family: calibri,arial,sans-serif;
			position: fixed;	
			top: 65px;
			left: 200px;
			}

		h2 {
			font-family: calibri,arial,sans-serif;
			position: fixed;
			top: 95px;
			left: 280px;
			}
			
		p.reg {
			font-family: verdana,arial,sans-serif;
			font-size:11px;
		}

		p.sub {
			font-family: verdana,arial,sans-serif;
			font-size:13px;
			position: fixed;
			top: 55px;
			left: 220px;
			color: #6b6b6b;
		}

		p.header {
			font-family: verdana,arial,sans-serif;
			font-size:11px;
			color: #330000;
		}

		p.barcs {
			font-family: verdana,arial,sans-serif;
			font-size:11px;
			position: fixed;
			top: 13px;
			right: 1px;
		}

		p.alamat {
			font-family: verdana,arial,sans-serif;
			font-size:7px;
			position: fixed;
			top: 71px;
			left: 5px;
		}

		p.tlp {
			font-family: verdana,arial,sans-serif;
			font-size:7px;
			position: fixed;
			top: 80px;
			left: 5px;
		}

		p.date {
			font-family: verdana,arial,sans-serif;
			font-size:12px;
			text-align: right;
		}

		p.foot {
			font-family: verdana,arial,sans-serif;
			font-size:7px;
			position: fixed;
			top: 750px;
			left: 5px;
		}

		p.footer {
			font-family: verdana,arial,sans-serif;
			font-size:10px;
			position: fixed;
			bottom: 7px;    
		}

		p.ln {
			font-family: verdana,arial,sans-serif;
			font-size:9px;
			position: fixed;
			bottom: 1px;
			left: 2px;
		}

		#hrnew {
			border: 0;
			border-bottom: 1px solid #ccc;
			background: #999;
		}
		.text-wrap {
			word-wrap : break-word !important;
		}
		.text-center {
			text-align : center !important;
			vertical-align : middle !important;
		}
		.text-left {
			text-align : left !important;
			vertical-align : middle !important;
		}
		</style>
		<?php
		
		$Header	="
			<div id='space'></div>
			<div id='space'></div>
			<table class='noborder2' width='100%'>
				<tr>
					<td width='25%' align='left'>
						<img src='".$img_file."' width='90' height='70'/>
					</td>
					<td width='50%' align='center'>
						<div style='font-size:17px;font-weight: bold;'>PT. SENTRAL TEHNOLOGI MANAGEMEN</div>
					</td>
					<td width='25%' align='right'>
						<img src='".$img_file2."' width='90' height='70'/>
					</td>
				</tr>
				<tr>
					<td colspan='3'><img src='".$img_file3."'/></td>
				</tr>
			</table>
			<div id='space'></div>
			<div id='space'></div>
			
		
		";

		$Footer	="<p style='font-family: verdana,arial,sans-serif;font-size:10px;text-align:center;position:fixed;bottom:5px;width:100%;' class='footer'>
				<b>www.sentralkalibrasi.co.id</b><br>Cikarang Square Blok B No. 11, Jl. Cibarusah Cikarang Selatan - Jawa Barat 17530<br>Telp. 021-89321314-15,89321323-24 - <b><br>E-mail</b> : <i>cs@sentralkalibrasi.co.id</i>
			</p>";
		echo $Header;
		
		$rows_Receive		= $this->db->get_where('quotation_driver_receives',array('id'=>$Code_Receive))->row();
		$Query_Tools		= "SELECT
									quotation_detail_id,
									tool_id,
									tool_name,
									COUNT(quotation_detail_id) AS jum_receive,
									GROUP_CONCAT(
										DISTINCT (descr) SEPARATOR ', '
									) AS ket_terima
								FROM
									quotation_driver_detail_receives
								WHERE
									code_receive = '".$Code_Receive."'
								GROUP BY
									quotation_detail_id";
		$rows_Tool			= $this->db->query($Query_Tools)->result();
		$rows_Header 		= $this->db->get_where('spk_drivers',array('id'=>$rows_Receive->spk_driver_id))->row();
		$rows_Customer 		= $this->db->get_where('customers',array('id'=>$rows_Receive->customer_id))->row();
		
		$inf_serah		= $rows_Receive->customer_name;
		$Judul			= 'SPK PENGAMBILAN ALAT ';
		$inf_terima		= 'PT. Sentral Tehnologi Managemen';	
		$tanggal		= date('d F Y',strtotime($rows_Receive->datet));
		
		$penerima		= strtoupper($rows_Receive->driver_name);
		$pemberi		= '-----------------------------------';
		$pemberi_phone	= '';
		
		$Addr_Cust		=  '-';
		
		## AMBIL ORDER ##
		$Query_Trans_Order		= "SELECT
										address
									FROM
										trans_driver_orders
									WHERE
										spk_driver_code = '".$rows_Receive->spk_driver_id."'
									AND company_code = '".$rows_Receive->customer_id."'
									AND type_comp = 'CUST'
									AND category = 'REC'
									LIMIT 1";
		$rows_Trans_Order		= $this->db->query($Query_Trans_Order)->row();
		if($rows_Trans_Order){
			$Addr_Cust		= $rows_Trans_Order->address;
		}
			
			
		?>
		<table class="noborder3" width='100%'>	
			<tr>
				<td align='center' valign='top' colspan='3'  width='100%' style="font-size:13px;font-family:calibri,arial,sans-serif;"><b><?php echo $Judul;?></b></td>
			</tr>
			<tr>
				<td align='left' valign='top' colspan='3' height='6' width='100%'></td>
			</tr>
			<tr>
				<td align='left' valign='top' width='24%'>No SPK</td>
				<td align='center' valign='top' width='4%'>:</td>
				<td align='left' valign='top' width='72%'><b><?php echo $rows_Header->nomor; ?></b></td>		
			</tr>
			<tr>
				<td align='left' valign='top' width='24%'>Nama Perusahaan</td>
				<td align='center' valign='top' width='4%'>:</td>
				<td align='left' valign='top' width='72%'><b><?php echo $rows_Header->customer_name; ?></b></td>		
			</tr>
			<tr>
				<td align='left' valign='top' width='24%'>Alamat</td>
				<td align='center' valign='top' width='4%'>:</td>
				<td align='left' valign='top' width='72%'><b><?php echo $Addr_Cust; ?></b></td>		
			</tr>
			<tr>
				<td align='left' valign='top' width='24%'>PIC</td>
				<td align='center' valign='top' width='4%'>:</td>
				<td align='left' valign='top' width='72%'><b><?php echo $rows_Customer->contact; ?></b></td>		
			</tr>
			<tr>
				<td align='left' valign='top' width='24%'>Phone PIC</td>
				<td align='center' valign='top' width='4%'>:</td>
				<td align='left' valign='top' width='72%'><b><?php echo $rows_Customer->hp; ?></b></td>		
			</tr>
			<tr>
				<td align='left' valign='top' colspan='3' height='3' width='100%'></td>
			</tr>
		</table>
		<div id='space'></div>
		<table class="gridtable" width='100%'>
			<tr>
				<th width='5%' class="text-center">NO.</th>
				<th width='35%' class="text-center">NAMA ALAT</th>
				<th width='13%' class="text-center">QUOTATION</th>
				<th width='12%' class="text-center">NO PO</th>
				<th width='10%' class="text-center">QTY<br>AMBIL</th>
				<th width='10%' class="text-center">QTY<br>TERIMA</th>
				<th width='25%' class="text-center">KETERANGAN</th>					
			</tr>
			
			<?php
				$loop		= 0;
				$Batas		= 20;
				if($rows_Tool){
					$Page		= 0;
					foreach($rows_Tool as $key=>$val){
						$Sisa_SO		= $val->jum_receive;
						$Nomor_PO		= $Nomor_Quot	= '-';
						$Range			= $Pieces		= '-';
						$Query_QuotDet	= "SELECT
												head_quot.nomor AS quotation_nomor,
												head_quot.pono,
												det_quot.range,
												det_quot.piece_id,
												det_quot.qty,
												det_quot.qty_so,
												det_quot.qty_driver
											FROM
												quotations head_quot
											INNER JOIN quotation_details det_quot ON head_quot.id = det_quot.quotation_id
											WHERE
												det_quot.id = '".$val->quotation_detail_id."'";
						$rows_QuotDet		= $this->db->query($Query_QuotDet)->row();
						if($rows_QuotDet){
							$Nomor_PO		= $rows_QuotDet->pono;
							$Nomor_Quot		= $rows_QuotDet->quotation_nomor;
							$Range			= $rows_QuotDet->range;
							$Pieces			= $rows_QuotDet->piece_id;
							$Sisa_SO		= $rows_QuotDet->qty - $rows_QuotDet->qty_so - $rows_QuotDet->qty_driver + $val->jum_receive;
						}
						
						$loop++;
						echo"<tr>";
							echo "<td width='5%' class='text-center'>$loop</td>";
							echo "<td width='35%' class='text-left text-wrap'>".$val->tool_name."</td>";
							echo "<td width='13%' class='text-center'>".$Nomor_Quot."</td>";
							echo "<td width='13%' class='text-center'>".$Nomor_PO."</td>";
							echo "<td width='10%' class='text-center'>".number_format($Sisa_SO)."</td>";
							echo "<td width='10%' class='text-center'>".number_format($val->jum_receive)."</td>";
							echo "<td width='25%' class='text-left text-wrap'>".$val->ket_terima."</td>";								
						echo"</tr>";
						if($loop >=$Batas){
							$Page++;
							$Batas	= 22;
							echo"</table>";
							echo $Footer;
							echo "<pagebreak>";
							echo $Header;
							echo"<table class='gridtable' width='100%'>
								<tr>
									<th width='5%' class='text-center'>NO.</th>
								<th width='35%' class='text-center'>NAMA ALAT</th>
								<th width='13%' class='text-center'>QUOTATION</th>
								<th width='12%' class='text-center'>NO PO</th>
								<th width='10%' class='text-center'>QTY<br>AMBIL</th>
								<th width='10%' class='text-center'>QTY<br>TERIMA</th>
								<th width='25%' class='text-center'>KETERANGAN</th>					
								</tr>";
							$loop=0;
						}
					}
				}
			?>
			
			
		</table>
		<?php
			$Next		= $loop + 3;
			if($Next >= $Batas){		
				echo $Footer;
				echo "<pagebreak>";
				echo $Header;
				
			}
			
			$Query_Approve		= "SELECT head_member.* FROM members head_member WHERE head_member.id = '".$rows_Receive->driver_id."'";
			//echo $Query_Approve;exit;
			$rows_Approve		= $this->db->query($Query_Approve)->row();
			//echo"<pre>";print_r($rows_Approve);exit;
			$TandaTanganPIC		='<br><br><br><br><br><br><br><br>';
			$TandaTanganDriver	='<br><br><br><br><br><br><br><br>';
			
			if($rows_Approve){
				if(!empty($rows_Approve->ttd_file)){
					$TandaTanganDriver	= '<img src="'.$this->file_location.'signature/'.$rows_Approve->ttd_file.'" width="240px" height="80px"/>';
				}	
			}
		?>
		<div id='space'></div>
		<div id='space'></div>
		<table class="noborder3" width='100%'>
			<tr>
				<td colspan='3' align='left'>Tanggal : <?php echo $tanggal;?></td>
			</tr>
			<tr>
				<td align='left' valign='top' width='20%'></td>
				<td align='center' valign='top' width='40%'>Diserahkan oleh<br><?php echo $inf_serah.$TandaTanganPIC.$pemberi.'<br>'.$pemberi_phone;?></td>
				<td align='center' valign='top' width='40%'>Diterima oleh<br><?php echo $inf_terima.$TandaTanganDriver.$penerima;?></td>
			</tr>	
		</table>
		<div id='space'></div>
		<div id='space'></div>
		<div id='space'></div>
		<div id='space'></div>

			<p style="font-family: verdana,arial,sans-serif;font-size:10px;text-align:left;position:fixed;bottom:45px;width:100%;">
				<b><i><?php echo $rows_Receive->nomor;?>.</i></b>
			</p>

		<?php
		echo $Footer;
		$html = ob_get_contents();
		ob_end_clean();

		$mpdf->WriteHTML($html);
		//$mpdf->Output($rows_Receive->id.".pdf" ,'I');
		$mpdf->Output($Nama_File ,'F');
		//exit;
		
		
	}
	
	
	##  MODIFIED BY ALI ~ 2022-12-14  ##
	function reschedule_driver_order(){
		$rows_Header	= $rows_Detail =  array();
		
		if($this->input->get()){
			$Code_Process	= urldecode($this->input->get('order'));
			
			$rows_Header	= $this->db->get_where('trans_driver_orders',array('order_code'=>$Code_Process))->row();
			$rows_Detail	= $this->db->get_where('trans_driver_order_details',array('order_code'=>$Code_Process))->result_array();
			
		}
		
		
		$data = array(
			'title'			=> 'DRIVER ORDER RESCHEDULE',
			'action'		=> 'reschedule_driver_order',
			'akses_menu'	=> $this->Arr_Akses,
			'rows_header'	=> $rows_Header,
			'rows_detail'	=> $rows_Detail
		);
		
		$this->load->view($this->folder.'/v_driver_order_reschedule',$data);
	}
	
	function save_reschedule_driver_order(){
		$rows_Return	= array(
			'status'		=> 2,
			'pesan'			=> 'No Record was found...'
		);
		if($this->input->post()){
			//echo"<pre>";print_r($this->input->post());exit;
			$Code_Order		= $this->input->post('code_order');
			$Created_By		= $this->session->userdata('siscal_userid');
			$Created_Name	= $this->session->userdata('siscal_username');
			$Created_Date	= date('Y-m-d H:i:s');
			
			$detDetail		= $this->input->post('detReschedule');
			$Code_Order		= $this->input->post('code_order');
			
			$this->db->trans_begin();
			$Pesan_Error	= '';
			
			if($detDetail){
				$Temp_SPK		= array();
				foreach($detDetail as $KeyDet=>$valDet){
					$Code_Split		= explode('^_^',$valDet);
					$Code_SPK_Det	= $Code_Split[0];
					$Qty_Schedule	= $Code_Split[1];
					
					$rows_SPK_Det	= $this->db->get_where('spk_driver_tools',array('id'=>$Code_SPK_Det))->row();
					if($rows_SPK_Det){
						$Code_SPK	= $rows_SPK_Det->spk_driver_id;
						$Code_Quot	= $rows_SPK_Det->schedule_detail_id;
						
						$Temp_SPK[$Code_SPK]	= $Code_SPK;
						
						## UPDATE SPK TOOL ##
						$Upd_Driver_Tool	= "UPDATE spk_driver_tools SET qty_reschedule = qty_reschedule + ".$Qty_Schedule.", flag_proses = 'Y' WHERE spk_driver_id = '".$Code_SPK."' AND id = '".$Code_SPK_Det."'";
						$Has_Upd_DriverTool	= $this->db->query($Upd_Driver_Tool);
						if($Has_Upd_DriverTool !== TRUE){
							$Pesan_Error	= 'Error Update SPK Driver Tool';
						}
						
						## UPDATE QUOTATION DETAIL ##
						$Upd_Quot_Detail	= "UPDATE quotation_details SET qty_driver = qty_driver - ".$Qty_Schedule." WHERE id = '".$Code_Quot."'";
						$Has_Upd_Quot_Det	= $this->db->query($Upd_Quot_Detail);
						if($Has_Upd_Quot_Det !== TRUE){
							$Pesan_Error	= 'Error Update Quotation Detail';
						}
						
					}
				}
				
				if($Temp_SPK){
					$Imp_SPK			= implode("','",$Temp_SPK);
					$Upd_SPK_Head		= "UPDATE spk_drivers SET `status` = 'CLS' WHERE id IN('".$Imp_SPK."')";
					$Has_Upd_SPK_Head	= $this->db->query($Upd_SPK_Head);
					if($Has_Upd_SPK_Head !== TRUE){
						$Pesan_Error	= 'Error Update SPK Header';
					}
				}
			}
			
			if ($this->db->trans_status() != TRUE || !empty($Pesan_Error)){
				$this->db->trans_rollback();
				$rows_Return		= array(
					'status'		=> 2,
					'pesan'			=> 'Save Process  Failed, '.$Pesan_Error
				);
				history('Reschedule Driver Order Process - '.$Code_Order.' - '.$Pesan_Error);
			}else{
				$this->db->trans_commit();
				$rows_Return		= array(
					'status'		=> 1,
					'pesan'			=> 'Save process success. Thank you & have a nice day......'
				);
				history('Reschedule Driver Order Process - '.$Code_Order);
			}		
		}
		echo json_encode($rows_Return);
	}
	
	function cancel_driver_order(){
		$rows_Header	= $rows_Detail =  array();
		
		if($this->input->post()){
			$Code_Process	= urldecode($this->input->post('code'));
			
			$rows_Header	= $this->db->get_where('trans_driver_orders',array('order_code'=>$Code_Process))->row();
			$rows_Detail	= $this->db->get_where('trans_driver_order_details',array('order_code'=>$Code_Process))->result();
			
		}
		
		
		$data = array(
			'title'			=> 'DRIVER ORDER CANCELLATION',
			'action'		=> 'cancel_driver_order',
			'akses_menu'	=> $this->Arr_Akses,
			'rows_header'	=> $rows_Header,
			'rows_detail'	=> $rows_Detail,
			'category'		=> 'cancel'
		);
		
		$this->load->view($this->folder.'/v_driver_order_preview',$data);
	}
	
	
	function save_cancel_driver_order(){
		$rows_Return	= array(
			'status'		=> 2,
			'pesan'			=> 'No Record was found...'
		);
		if($this->input->post()){
			//echo"<pre>";print_r($this->input->post());exit;
			
			$Created_By		= $this->session->userdata('siscal_userid');
			$Created_Name	= $this->session->userdata('siscal_username');
			$Created_Date	= date('Y-m-d H:i:s');
			
			$Code_Order		= $this->input->post('code_order');
			$Cancel_Reason	= strtoupper($this->input->post('cancel_reason'));
			
			$Find_Exist		= $this->db->get_where('trans_driver_orders',array('order_code'=>$Code_Order))->row();
			if($Find_Exist){
				if($Find_Exist->sts_order !== 'OPN'){
					$rows_Return	= array(
						'status'		=> 2,
						'pesan'			=> 'Data has been modified by other process...'
					);
				}else{
					$this->db->trans_begin();
					$Pesan_Error	= '';
					
					$Type_Comp		= $Find_Exist->type_comp;
					$Cat_Process	= $Find_Exist->category;
					$Upd_Header		= array(
						'sts_order'		=> 'CNC',
						'cancel_reason'	=> $Cancel_Reason,
						'cancel_by'		=> $Created_By,
						'cancel_date'	=> $Created_Date
					);
					$Has_Upd_Header	= $this->db->update('trans_driver_orders',$Upd_Header,array('order_code'=>$Code_Order));
					if($Has_Upd_Header !== TRUE){
						$Pesan_Error	= 'Error Update Driver Order..';
					}
					$Table_Update		= 'Trans Tool Detail';
					if($Type_Comp === 'CUST' && $Cat_Process === 'REC'){
						$Table_Update		= 'Quotation Detail';
					}
					$rows_Detail		= $this->db->get_where('trans_driver_order_details',array('order_code'=>$Code_Order))->result();
					if($rows_Detail){
						foreach($rows_Detail as $keyDetail=>$valDetail){
							$Code_Detail	= $valDetail->code_process;
							$Qty_Process	= $valDetail->qty;
							$Query_Update	= "";
							if($Cat_Process === 'INS'){
								$Query_Update	= "UPDATE trans_details SET flag_cust_pick ='N', flag_cust_send = 'N' WHERE id = '".$Code_Detail."'";
							}else if($Cat_Process === 'REC'){
								if($Type_Comp === 'CUST'){
									$Query_Update	= "UPDATE quotation_details SET qty_driver = qty_driver - ".$Qty_Process." WHERE id = '".$Code_Detail."'";
								}else{
									$Query_Update	= "UPDATE trans_details SET qty_subcon_rec = qty_subcon_rec - ".$Qty_Process." WHERE id = '".$Code_Detail."'";
								}
							}else if($Cat_Process === 'DEL'){
								if($Type_Comp === 'CUST'){
									$Query_Update	= "UPDATE trans_details SET qty_send = qty_send - ".$Qty_Process." WHERE id = '".$Code_Detail."'";
								}else{
									$Query_Update	= "UPDATE trans_details SET qty_subcon_send = qty_subcon_send - ".$Qty_Process." WHERE id = '".$Code_Detail."'";
								}
							}
							if($Query_Update){
								$Has_Upd_Query	= $this->db->query($Query_Update);
								if($Has_Upd_Query !== TRUE){
									$Pesan_Error	= 'Error Update '.$Table_Update;
								}
							}
							
						}
					}
					
					##  MODIFIED BY ALI ~ 2022-12-11  ##
					if($Cat_Process === 'INS'){
						$rows_Bast		= $this->db->get_where('insitu_letters',array('order_code'=>$Code_Order))->result();
						if($rows_Bast){
							foreach($rows_Bast as $keyBast=>$valBast){
								$Code_Bast		= $valBast->id;
								
								
								
								## DELETE BAST INSITU ##
								$Del_Bast_Head		= "DELETE FROM insitu_letters  WHERE id ='".$Code_Bast."'";
								$Has_Del_Bast_Head	= $this->db->query($Del_Bast_Head);
								if($Has_Del_Bast_Head !== TRUE){
									$Pesan_Error	= 'Error Delete Bast Insitu Header';
								}
								
								$Ok_SPK_Teknisi			= 'N';
								$rows_Bast_Detail		= $this->db->get_where('insitu_letter_details',array('insitu_letter_id'=>$Code_Bast))->result();
								foreach($rows_Bast_Detail as $keyBastDet=>$valBastDet){
									$Code_BastDet		= $valBastDet->quotation_detail_id;
									if($valBastDet->flag_tech_letter == 'Y'){
										$Ok_SPK_Teknisi			= 'Y';
									}
									$Qry_Upd_Trans		= "UPDATE trans_details SET bast_rec_id = NULL, bast_rec_no = NULL, bast_rec_date = NULL, bast_rec_by = NULL WHERE id ='".$Code_BastDet."' AND bast_rec_id = '".$Code_Bast."'";
											
									
									$Has_Upd_Trans	= $this->db->query($Qry_Upd_Trans);
									if($Has_Upd_Trans !== true){
										$Pesan_Error	= 'Error Update Trans Detail - BAST...';
									}		
									
									## CHECK TRANS DATA DETAILS ##
									$Qry_CheckTrans		= "SELECT * FROM trans_data_details WHERE trans_detail_id = '".$Code_BastDet."' AND flag_proses IN('N','Y')";
									$rows_CheckTrans	= $this->db->query($Qry_CheckTrans)->num_rows();
									if($rows_CheckTrans > 0){
										$Pesan_Error	= 'Trans Data Details has been modified by other process';
									}else{
										## DELETE BAST INSITU ##
										$Del_TransDet		= "DELETE FROM trans_data_details  WHERE trans_detail_id ='".$Code_BastDet."'";
										$Has_Del_TransDet	= $this->db->query($Del_TransDet);
										if($Has_Del_TransDet !== TRUE){
											$Pesan_Error	= 'Error Delete Trans Data Details';
										}
									}
								}
								
								if($Ok_SPK_Teknisi == 'Y'){
									$Pesan_Error	= 'SPK Technician has been created...';
								}else{
									$Del_InsituDet		= "DELETE FROM insitu_letter_details  WHERE insitu_letter_id ='".$Code_Bast."'";
									$Has_Del_InsituDet	= $this->db->query($Del_InsituDet);
									if($Has_Del_InsituDet !== TRUE){
										$Pesan_Error	= 'Error Delete BAST Insitu Details';
									}
								}
							}
						}
					}else{
					
						$rows_Bast		= $this->db->get_where('bast_headers',array('order_code'=>$Code_Order,'status !='=>'CNC'))->result();
						if($rows_Bast){
							foreach($rows_Bast as $keyBast=>$valBast){
								$Code_Bast		= $valBast->id;
								$Category		= $valBast->flag_type;
								$Type_Process	= $valBast->type_bast;
								
								## UPD CANCEL BAST ##
								$Upd_Bast_Head		= "UPDATE bast_headers SET status ='CNC', cancel_by = '".$Created_By."', cancel_date = '".$Created_Date."', cancel_reason = '".$Cancel_Reason."' WHERE id ='".$Code_Bast."'";
								$Has_Upd_Bast_Head	= $this->db->query($Upd_Bast_Head);
								if($Has_Upd_Bast_Head !== TRUE){
									$Pesan_Error	= 'Error Update Bast Header';
								}
								
								$rows_Bast_Detail		= $this->db->get_where('bast_details',array('bast_header_id'=>$Code_Bast))->result();
								foreach($rows_Bast_Detail as $keyBastDet=>$valBastDet){
									$Code_BastDet		= $valBastDet->quotation_detail_id;
									
									if($Category == 'CUST'){
										if($Type_Process == 'REC'){
											$Qry_Upd_Trans	= "UPDATE trans_details SET bast_rec_id = NULL, bast_rec_no = NULL, bast_rec_date = NULL, bast_rec_by = NULL WHERE id ='".$Code_BastDet."' AND bast_rec_id = '".$Code_Bast."'";
											
										}else{
											$Qry_Upd_Trans	= "UPDATE trans_details SET bast_send_id = NULL, bast_send_no = NULL, bast_send_date = NULL, bast_send_by = NULL WHERE id ='".$Code_BastDet."' AND bast_send_id = '".$Code_Bast."'";
										}
									}else{
										if($Type_Process == 'REC'){
											$Qry_Upd_Trans	= "UPDATE trans_details SET subcon_bast_rec_id = NULL, subcon_bast_rec_no = NULL, subcon_bast_rec_date = NULL, subcon_bast_rec_by = NULL WHERE id ='".$Code_BastDet."' AND subcon_bast_rec_id = '".$Code_Bast."'";
											
										}else{
											$Qry_Upd_Trans	= "UPDATE trans_details SET subcon_bast_send_id = NULL, subcon_bast_send_no = NULL, subcon_bast_send_date = NULL, subcon_bast_send_by = NULL WHERE id ='".$Code_BastDet."' AND subcon_bast_send_id = '".$Code_Bast."'";
											
										}
									}
									
									$Has_Upd_Trans	= $this->db->query($Qry_Upd_Trans);
									if($Has_Upd_Trans !== true){
										$Pesan_Error	= 'Error Update Trans Detail - BAST...';
									}								
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
						history('Cancellation Driver Order '.$Code_Order.' - '.$Pesan_Error);
					}else{
						$this->db->trans_commit();
						$rows_Return		= array(
							'status'		=> 1,
							'pesan'			=> 'Save process success. Thank you & have a nice day......'
						);
						history('Cancellation Driver Order '.$Code_Order);
					}
					
				}
			}else{
				$rows_Return	= array(
					'status'		=> 2,
					'pesan'			=> 'No Record was found...'
				);
			}
			

						
		}
		echo json_encode($rows_Return);
	}	
	
}