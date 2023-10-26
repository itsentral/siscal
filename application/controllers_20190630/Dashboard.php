<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Dashboard extends CI_Controller {	
	public function __construct() {
		parent::__construct();
		$this->load->model('master_model');
		// Your own constructor code
		/*
		if(!$this->session->userdata('isSISCALlogin')){
			redirect('login');
		}
		*/
	}	
	public function index() {
		$records_data	= $this->json_dashboard('N');
		$data			= array(
			'action'	=>'index',
			'title'		=>'Dashboard',
			'rows_data'	=> $records_data
		);
		
		$this->load->view('dashboard',$data);
		
	}
	public function getdetail($tipe){
		
		$Tanggal		= date('Y-m-');
		$WHERE			= "";		
		if($tipe==1){
			if(!empty($WHERE))$WHERE.=" AND ";
			$WHERE		.= "tgl_old LIKE '".$Tanggal."%' AND status IN ('OPN','CNC','FAL','REC')";			
		}else if($tipe==2){
			if(!empty($WHERE))$WHERE.=" AND ";
			$WHERE		.= "podate LIKE '".$Tanggal."%' AND status IN ('REC')";
			
		}else if($tipe==3){
			if(!empty($WHERE))$WHERE.=" AND ";
			$WHERE		.= "cancel_date LIKE '".$Tanggal."%' AND status IN ('FAL')";
			
		}else if($tipe==4){
			if(!empty($WHERE))$WHERE.=" AND ";
			$WHERE		.= "cancel_date LIKE '".$Tanggal."%' AND status IN ('CNC')";
			
		}
		$Query_Data		= "SELECT * FROM view_quotation_totals WHERE ".$WHERE;		
		$Records		= $this->db->query($Query_Data)->result_array();
		
		$data			= array(
			'tipe'			=> $tipe,
			'records'		=> $Records
		);
		
		$this->load->view('view_dashboard/getdetail',$data);
		
		
	}
	
	## DASHBOARD SO ##
	function getdataorder($kategori){
		
		if($kategori==1){
			$Table_Name		= 'view_letter_order_total_currents';
			$Judul			= 'Laporan Sales Order '.date('M Y');
		}else if($kategori==2){
			$Judul			= 'Laporan Cancel Sales Order'.date('M Y');
			$Table_Name		= 'view_letter_order_cancels';
		}
		
		
		$records		= $this->db->get($Table_Name)->result_array();
		$data			= array(
			'kategori'		=> $kategori,
			'records'		=> $records,
			'Judul'			=> $Judul
		);
		
		$this->load->view('view_dashboard/getdataorder',$data);
		
	}
	
	
	function get_excelorder($kategori){
		if($kategori==1){
			$Table_Name		= 'view_letter_order_total_currents';
			$Judul			= 'Laporan Sales Order '.date('M Y');
		}else if($kategori==2){
			$Judul			= 'Laporan Cancel Sales Order'.date('M Y');
			$Table_Name		= 'view_letter_order_cancels';
		}
		
		
		$records		= $this->db->get($Table_Name)->result_array();
		$data			= array(
			'kategori'		=> $kategori,
			'records'		=> $records,
			'Judul'			=> $Judul
		);
		
		$this->load->view('view_dashboard/get_excelorder',$data);
	}
	
	## END DASHBOARD SO ##
	
	## GET OTHER  DASHBOARD ## 
	function get_other_dashboard($kategori){
		
		if($kategori=='1'){			
			$records		= $this->db->get('view_quotation_po_incompletes')->result_array();
		}else if($kategori==2){
			## LATE INV SEND ##
			$Query_Data		= "SELECT 
									invoice_no,
									datet,
									customer_name,
									grand_tot,
									date_create,
									id,
									address,
									total_payment,
									DATEDIFF(CURRENT_DATE (),date_create) AS leadtime 
								FROM view_invoice_payment_reports 
								WHERE 
									(
										send_date IS NULL
										OR send_date = ''
										OR send_date = '-'
									)
									AND DATEDIFF(CURRENT_DATE (),date_create) >= 3";
			$records		= $this->db->query($Query_Data)->result_array();
			
		}else if($kategori==3){
			## LATE INV FOLLOW UP 1 ##
			$Query_Data		= "SELECT 
									invoice_no,
									datet,
									customer_name,
									grand_tot,
									receive_date,
									id,
									address,
									total_payment,
									DATEDIFF(CURRENT_DATE (),receive_date) AS leadtime 
								FROM view_invoice_payment_reports 
								WHERE 
									NOT (
										send_date IS NULL
										OR send_date = ''
										OR send_date = '-'
									)
									AND (
										date_follow_up IS NULL
										OR date_follow_up = ''
										OR date_follow_up = '-'
									)
									AND total_payment <=0
									AND DATEDIFF(CURRENT_DATE (),receive_date) >= 7";
			$records		= $this->db->query($Query_Data)->result_array();
			
			
		}else if($kategori==4){
			## LATE INV FOLLOW UP 2 ##
			$Query_Data		= "SELECT 
									invoice_no,
									datet,
									customer_name,
									grand_tot,
									receive_date,
									id,
									address,
									total_payment,
									date_follow_up,
									DATEDIFF(CURRENT_DATE (),receive_date) AS leadtime 
								FROM view_invoice_payment_reports 
								WHERE 
									NOT (
										send_date IS NULL
										OR send_date = ''
										OR send_date = '-'
									)
									AND NOT(
										date_follow_up IS NULL
										OR date_follow_up = ''
										OR date_follow_up = '-'
									)
									AND total_payment <=0
									AND total_follow_up =1
									AND DATEDIFF(CURRENT_DATE (),receive_date) >= 7";
			$records		= $this->db->query($Query_Data)->result_array();
			
		}else if($kategori==5){
			## POTENTIAL BAD DEBT ##
			$Query_Data		= "SELECT 
									invoice_no,
									datet,
									customer_name,
									grand_tot,
									receive_date,
									id,
									address,
									total_payment,
									(grand_tot - total_payment) AS hutang,
									DATEDIFF(CURRENT_DATE (),IF(receive_date IS NULL,datet,receive_date)) AS leadtime
								FROM view_invoice_payment_reports 
								WHERE 
									(grand_tot - total_payment) > 0
									AND (grand_tot - total_payment) <> pph23
									AND (grand_tot - total_payment) <> ppn
									AND (DATEDIFF(CURRENT_DATE (),IF(receive_date IS NULL,datet,receive_date)) BETWEEN '30' AND '60')";
			$records		= $this->db->query($Query_Data)->result_array();						
			
		}else if($kategori==6){
			## BAD DEBT ##
			$Query_Data		= "SELECT 
									invoice_no,
									datet,
									customer_name,
									grand_tot,
									receive_date,
									id,
									address,
									total_payment,
									(grand_tot - total_payment) AS hutang,
									DATEDIFF(CURRENT_DATE (),IF(receive_date IS NULL,datet,receive_date)) AS leadtime
								FROM view_invoice_payment_reports 
								WHERE 
									(grand_tot - total_payment) > 0
									AND (grand_tot - total_payment) <> pph23
									AND (grand_tot - total_payment) <> ppn
									AND DATEDIFF(CURRENT_DATE (),IF(receive_date IS NULL,datet,receive_date)) > 60";
			$records		= $this->db->query($Query_Data)->result_array();	
			
			
		}else if($kategori==7){
			## LATE SCHEDULE ##
			$Query_Data		= "SELECT 
									det_so.*,
									DATEDIFF(CURRENT_DATE (),det_so.tgl_so) AS leadtime,
									det_quot.nomor AS quotation_nomor,
									det_quot.pono,
									det_quot.podate
								FROM letter_orders det_so INNER JOIN quotations det_quot ON det_so.quotation_id=det_quot.id
								WHERE 
									det_so.sts_so='OPN'
									AND DATEDIFF(CURRENT_DATE (),det_so.tgl_so) > 2";
			$records		= $this->db->query($Query_Data)->result_array();	
			
			
		}else if($kategori==8){
			##POTENTIAL BAD DEBT PPH ##
			$Query_Data		= "SELECT
								invoice_no,
								datet,
								customer_name,
								grand_tot,
								receive_date,
								id,
								address,
								total_payment,
								(grand_tot - total_payment) AS hutang,
								DATEDIFF(
									CURRENT_DATE (),

								IF (
									receive_date IS NULL,
									datet,
									receive_date
								)
								) AS leadtime
							FROM
								view_invoice_payment_reports
							WHERE
								(grand_tot - total_payment) = pph23
							AND (
								DATEDIFF(
									CURRENT_DATE (),

								IF (
									receive_date IS NULL,
									datet,
									receive_date
								)
								) BETWEEN '30'
								AND '60'
							)";
			$records		= $this->db->query($Query_Data)->result_array();
		}else if($kategori==9){
			## BAD DEBT PPH ##
			$Query_Data		= "SELECT
								invoice_no,
								datet,
								customer_name,
								grand_tot,
								receive_date,
								id,
								address,
								total_payment,
								(grand_tot - total_payment) AS hutang,
								DATEDIFF(
									CURRENT_DATE (),

								IF (
									receive_date IS NULL,
									datet,
									receive_date
								)
								) AS leadtime
							FROM
								view_invoice_payment_reports
							WHERE
								(grand_tot - total_payment) = pph23
							AND DATEDIFF(CURRENT_DATE (),IF(receive_date IS NULL,datet,receive_date)) > 60";
			$records		= $this->db->query($Query_Data)->result_array();
		}else if($kategori==10){
			##POTENTIAL BAD DEBT PPN ##
			$Query_Data		= "SELECT
								invoice_no,
								datet,
								customer_name,
								grand_tot,
								receive_date,
								id,
								address,
								total_payment,
								(grand_tot - total_payment) AS hutang,
								DATEDIFF(
									CURRENT_DATE (),

								IF (
									receive_date IS NULL,
									datet,
									receive_date
								)
								) AS leadtime
							FROM
								view_invoice_payment_reports
							WHERE
								(grand_tot - total_payment) = ppn
							AND (
								DATEDIFF(
									CURRENT_DATE (),

								IF (
									receive_date IS NULL,
									datet,
									receive_date
								)
								) BETWEEN '30'
								AND '60'
							)";
			$records		= $this->db->query($Query_Data)->result_array();
		}else if($kategori==11){
			## BAD DEBT PPN ##
			$Query_Data		= "SELECT
								invoice_no,
								datet,
								customer_name,
								grand_tot,
								receive_date,
								id,
								address,
								total_payment,
								(grand_tot - total_payment) AS hutang,
								DATEDIFF(
									CURRENT_DATE (),

								IF (
									receive_date IS NULL,
									datet,
									receive_date
								)
								) AS leadtime
							FROM
								view_invoice_payment_reports
							WHERE
								(grand_tot - total_payment) = ppn
							AND DATEDIFF(CURRENT_DATE (),IF(receive_date IS NULL,datet,receive_date)) > 60";
			$records		= $this->db->query($Query_Data)->result_array();
		}else if($kategori==12){
			## INVOICE MINUS ##
			$Query_Data		= "SELECT
								invoice_no,
								datet,
								customer_name,
								grand_tot,
								receive_date,
								id,
								address,
								total_payment,
								(grand_tot - total_payment) AS hutang,
								DATEDIFF(
									CURRENT_DATE (),

								IF (
									receive_date IS NULL,
									datet,
									receive_date
								)
								) AS leadtime
							FROM
								view_invoice_payment_reports
							WHERE
								(grand_tot - total_payment) < 0
							";
			$records		= $this->db->query($Query_Data)->result_array();
		}
		
		$data			= array(
			'kategori'		=> $kategori,
			'records'		=> $records
		);
		
		$this->load->view('view_dashboard/get_other_dashboard',$data);	
		
	}
	
	
	
	function excel_other_dashboard($kategori){
		if($kategori=='1'){			
			$records		= $this->db->get('view_quotation_po_incompletes')->result_array();
		}else if($kategori==2){
			## LATE INV SEND ##
			$Query_Data		= "SELECT 
									invoice_no,
									datet,
									customer_name,
									grand_tot,
									date_create,
									id,
									address,
									total_payment,
									DATEDIFF(CURRENT_DATE (),date_create) AS leadtime 
								FROM view_invoice_payment_reports 
								WHERE 
									(
										send_date IS NULL
										OR send_date = ''
										OR send_date = '-'
									)
									AND DATEDIFF(CURRENT_DATE (),date_create) >= 3";
			$records		= $this->db->query($Query_Data)->result_array();
			
		}else if($kategori==3){
			## LATE INV FOLLOW UP 1 ##
			$Query_Data		= "SELECT 
									invoice_no,
									datet,
									customer_name,
									grand_tot,
									receive_date,
									id,
									address,
									total_payment,
									DATEDIFF(CURRENT_DATE (),receive_date) AS leadtime 
								FROM view_invoice_payment_reports 
								WHERE 
									NOT (
										send_date IS NULL
										OR send_date = ''
										OR send_date = '-'
									)
									AND (
										date_follow_up IS NULL
										OR date_follow_up = ''
										OR date_follow_up = '-'
									)
									AND total_payment <=0
									AND DATEDIFF(CURRENT_DATE (),receive_date) >= 7";
			$records		= $this->db->query($Query_Data)->result_array();
			
			
		}else if($kategori==4){
			## LATE INV FOLLOW UP 2 ##
			$Query_Data		= "SELECT 
									invoice_no,
									datet,
									customer_name,
									grand_tot,
									receive_date,
									id,
									address,
									total_payment,
									date_follow_up,
									DATEDIFF(CURRENT_DATE (),receive_date) AS leadtime 
								FROM view_invoice_payment_reports 
								WHERE 
									NOT (
										send_date IS NULL
										OR send_date = ''
										OR send_date = '-'
									)
									AND NOT(
										date_follow_up IS NULL
										OR date_follow_up = ''
										OR date_follow_up = '-'
									)
									AND total_payment <=0
									AND total_follow_up =1
									AND DATEDIFF(CURRENT_DATE (),receive_date) >= 14";
			$records		= $this->db->query($Query_Data)->result_array();
			
		}else if($kategori==5){
			## POTENTIAL BAD DEBT ##
			$Query_Data		= "SELECT 
									invoice_no,
									datet,
									customer_name,
									grand_tot,
									receive_date,
									id,
									address,
									total_payment,
									(grand_tot - total_payment) AS hutang,
									DATEDIFF(CURRENT_DATE (),IF(receive_date IS NULL,datet,receive_date)) AS leadtime
								FROM view_invoice_payment_reports 
								WHERE 
									(grand_tot - total_payment) > 0
									AND (grand_tot - total_payment) <> pph23
									AND (grand_tot - total_payment) <> ppn
									AND (DATEDIFF(CURRENT_DATE (),IF(receive_date IS NULL,datet,receive_date)) BETWEEN '30' AND '60')";
			$records		= $this->db->query($Query_Data)->result_array();						
			
		}else if($kategori==6){
			## BAD DEBT ##
			$Query_Data		= "SELECT 
									invoice_no,
									datet,
									customer_name,
									grand_tot,
									receive_date,
									id,
									address,
									total_payment,
									(grand_tot - total_payment) AS hutang,
									DATEDIFF(CURRENT_DATE (),IF(receive_date IS NULL,datet,receive_date)) AS leadtime
								FROM view_invoice_payment_reports 
								WHERE 
									(grand_tot - total_payment) > 0
									AND (grand_tot - total_payment) <> pph23
									AND (grand_tot - total_payment) <> ppn
									AND DATEDIFF(CURRENT_DATE (),IF(receive_date IS NULL,datet,receive_date)) > 60";
			$records		= $this->db->query($Query_Data)->result_array();	
			
			
		}else if($kategori==7){
			## LATE SCHEDULE ##
			$Query_Data		= "SELECT 
									det_so.*,
									DATEDIFF(CURRENT_DATE (),det_so.tgl_so) AS leadtime,
									det_quot.nomor AS quotation_nomor,
									det_quot.pono,
									det_quot.podate
								FROM letter_orders det_so INNER JOIN quotations det_quot ON det_so.quotation_id=det_quot.id
								WHERE 
									det_so.sts_so='OPN'
									AND DATEDIFF(CURRENT_DATE (),det_so.tgl_so) > 2";
			$records		= $this->db->query($Query_Data)->result_array();	
			
			
		}else if($kategori==8){
			##POTENTIAL BAD DEBT PPH ##
			$Query_Data		= "SELECT
								invoice_no,
								datet,
								customer_name,
								grand_tot,
								receive_date,
								id,
								address,
								total_payment,
								(grand_tot - total_payment) AS hutang,
								DATEDIFF(
									CURRENT_DATE (),

								IF (
									receive_date IS NULL,
									datet,
									receive_date
								)
								) AS leadtime
							FROM
								view_invoice_payment_reports
							WHERE
								(grand_tot - total_payment) = pph23
							AND (
								DATEDIFF(
									CURRENT_DATE (),

								IF (
									receive_date IS NULL,
									datet,
									receive_date
								)
								) BETWEEN '30'
								AND '60'
							)";
			$records		= $this->db->query($Query_Data)->result_array();
		}else if($kategori==9){
			## BAD DEBT PPH ##
			$Query_Data		= "SELECT
								invoice_no,
								datet,
								customer_name,
								grand_tot,
								receive_date,
								id,
								address,
								total_payment,
								(grand_tot - total_payment) AS hutang,
								DATEDIFF(
									CURRENT_DATE (),

								IF (
									receive_date IS NULL,
									datet,
									receive_date
								)
								) AS leadtime
							FROM
								view_invoice_payment_reports
							WHERE
								(grand_tot - total_payment) = pph23
							AND DATEDIFF(CURRENT_DATE (),IF(receive_date IS NULL,datet,receive_date)) > 60";
			$records		= $this->db->query($Query_Data)->result_array();
		}else if($kategori==10){
			##POTENTIAL BAD DEBT PPN ##
			$Query_Data		= "SELECT
								invoice_no,
								datet,
								customer_name,
								grand_tot,
								receive_date,
								id,
								address,
								total_payment,
								(grand_tot - total_payment) AS hutang,
								DATEDIFF(
									CURRENT_DATE (),

								IF (
									receive_date IS NULL,
									datet,
									receive_date
								)
								) AS leadtime
							FROM
								view_invoice_payment_reports
							WHERE
								(grand_tot - total_payment) = ppn
							AND (
								DATEDIFF(
									CURRENT_DATE (),

								IF (
									receive_date IS NULL,
									datet,
									receive_date
								)
								) BETWEEN '30'
								AND '60'
							)";
			$records		= $this->db->query($Query_Data)->result_array();
		}else if($kategori==11){
			## BAD DEBT PPN ##
			$Query_Data		= "SELECT
								invoice_no,
								datet,
								customer_name,
								grand_tot,
								receive_date,
								id,
								address,
								total_payment,
								(grand_tot - total_payment) AS hutang,
								DATEDIFF(
									CURRENT_DATE (),

								IF (
									receive_date IS NULL,
									datet,
									receive_date
								)
								) AS leadtime
							FROM
								view_invoice_payment_reports
							WHERE
								(grand_tot - total_payment) = ppn
							AND DATEDIFF(CURRENT_DATE (),IF(receive_date IS NULL,datet,receive_date)) > 60";
			$records		= $this->db->query($Query_Data)->result_array();
		}else if($kategori==12){
			## INVOICE MINUS ##
			$Query_Data		= "SELECT
								invoice_no,
								datet,
								customer_name,
								grand_tot,
								receive_date,
								id,
								address,
								total_payment,
								(grand_tot - total_payment) AS hutang,
								DATEDIFF(
									CURRENT_DATE (),

								IF (
									receive_date IS NULL,
									datet,
									receive_date
								)
								) AS leadtime
							FROM
								view_invoice_payment_reports
							WHERE
								(grand_tot - total_payment) < 0
							";
			$records		= $this->db->query($Query_Data)->result_array();
		}
		$data			= array(
			'kategori'		=> $kategori,
			'records'		=> $records
		);
		
		$this->load->view('view_dashboard/excel_other_dashboard',$data);
	}
	## END OTHER DASHBOARD ##
	
	## JSON DATA DASHBOARD
	function json_dashboard($json='Y'){
		$Arr_Return		= array();
		
		## QUOTATION TOTAL ##
		
		$Tanggal					= date('Y-m-');
		$Query_Quot					= "SELECT SUM(total_dpp - tot_insitu - total_akomodasi - total_subcon - customer_fee) AS total FROM view_quotation_totals WHERE tgl_old LIKE '".$Tanggal."%'";
			
		$DataTotal					= $this->db->query($Query_Quot)->result_array();
		$Nilai_Total				= round($DataTotal[0]['total'] / 1000000);		
		$Arr_Return['total_quot']	= $Nilai_Total;
		
		## QUOTATION DEAL ##
		$Query_Deal					= "SELECT SUM(total_dpp - tot_insitu - total_akomodasi - total_subcon - customer_fee) AS total FROM view_quotation_totals WHERE podate LIKE '".$Tanggal."%'";
		$DataDeal					= $this->db->query($Query_Deal)->result_array();
		$Nilai_Deal					= round($DataDeal[0]['total'] / 1000000);
		$Arr_Return['deal_quot']	= $Nilai_Deal;
		
		## QUOTATION FAIL ##
		$Query_Fail					= "SELECT SUM(total_dpp - tot_insitu - total_akomodasi - total_subcon - customer_fee) AS total FROM view_quotation_totals WHERE cancel_date LIKE '".$Tanggal."%' AND status='FAL'";
		$DataFail					= $this->db->query($Query_Fail)->result_array();
		
		$Nilai_Fail					= round($DataFail[0]['total'] / 1000000);
		$Arr_Return['fail_quot']	= $Nilai_Fail;
		
		## QUOTATION CANCEL ##
		$Query_Canc					= "SELECT SUM(total_dpp - tot_insitu - total_akomodasi - total_subcon - customer_fee) AS total FROM view_quotation_totals WHERE cancel_date LIKE '".$Tanggal."%' AND status='CNC'";
		$DataCancel					= $this->db->query($Query_Canc)->result_array();		
		$Nilai_Cancel				= round($DataCancel[0]['total'] / 1000000);
		$Arr_Return['cancel_quot']	= $Nilai_Cancel;
		
		## SALES ORDER ##
		$Query_Order				= "SELECT SUM(total_net) AS total FROM view_letter_order_total_currents";
		$DataOrder					=  $this->db->query($Query_Order)->result_array();
		$Nilai_Order				= round($DataOrder[0]['total'] / 1000000);
		$Arr_Return['total_order']	= $Nilai_Order;
		
		## CERTIFICATE UNUPLOADED ##
		
		
		$Tgl_Beda						= date('Y-m-d',strtotime('-2 days'));
		$Query_Sertf					= "SELECT * FROM view_tool_certificates WHERE flag_sertifikat<>'Y' AND tgl_kalibrasi < '".$Tgl_Beda."'";
		$Count_Certificate				= $this->db->query($Query_Sertf)->num_rows();
		$Arr_Return['upload_sertifikat']= $Count_Certificate;
		
		## OUTSTANDING REMINDER ##
		$Tgl_Balik						= date('Y-m-d',strtotime('+1 month',strtotime(date('Y-m-d'))));
		$Query_Reminder					= "SELECT * FROM view_outstanding_reminders WHERE valid_until < '".$Tgl_Balik."'";		
		$Count_Reminder					= $this->db->query($Query_Reminder)->num_rows();
		$Arr_Return['total_reminder']	= $Count_Reminder;
		
		## INCOMPLETE PO ##		
		$totRecords								= $this->db->get('view_quotation_po_incompletes')->num_rows();
		$Arr_Return['total_incomplete_quot']	= $totRecords;
		
		## OUTSTANDING SO RECEIVE ##
		
		$Out_Sales_Order						= $this->db->get('view_outstanding_receive_tools')->num_rows();
		$Arr_Return['total_incomplete_receive']	= $Out_Sales_Order;
		
		## OUTSTANDING BAST REC/SEND ##		
		$Count_Delivery							= $this->db->get('view_incomplete_delivery_reminders')->num_rows();
		$Arr_Return['total_incomplete_delivery']= $Count_Delivery;
		
		## LATE SEND INVOICE ##
		$Query_Data							= "SELECT 
													invoice_no									
												FROM view_invoice_payment_reports 
												WHERE 
													(
														send_date IS NULL
														OR send_date = ''
														OR send_date = '-'
													)
													AND DATEDIFF(CURRENT_DATE (),date_create) >= 3";
		$Late_Inv_Send						= $this->db->query($Query_Data)->num_rows();
		
		$Arr_Return['total_late_inv_send']	= $Late_Inv_Send;
		
		// LATE FOLLOW UP
		$Query_Data								= "SELECT 
														invoice_no
													FROM view_invoice_payment_reports 
													WHERE 
														NOT (
															send_date IS NULL
															OR send_date = ''
															OR send_date = '-'
														)
														AND (
															date_follow_up IS NULL
															OR date_follow_up = ''
															OR date_follow_up = '-'
														)
														AND total_payment <=0
														AND DATEDIFF(CURRENT_DATE (),receive_date) >= 7";
		$Late_FollowUp1							= $this->db->query($Query_Data)->num_rows();		
		$Arr_Return['total_late_inv_follow1']	= $Late_FollowUp1;
		
		## LATE FOLLOW UP 2 ##
		$Query_Data								= "SELECT 
															invoice_no
													FROM view_invoice_payment_reports 
													WHERE 
														NOT (
															send_date IS NULL
															OR send_date = ''
															OR send_date = '-'
														)
														AND NOT(
															date_follow_up IS NULL
															OR date_follow_up = ''
															OR date_follow_up = '-'
														)
														AND total_payment <=0
														AND total_follow_up =1
														AND DATEDIFF(CURRENT_DATE (),receive_date) >= 14";
		$Late_FollowUp2							= $this->db->query($Query_Data)->num_rows();
		$Arr_Return['total_late_inv_follow2']	= $Late_FollowUp2;
		
		## POTENTIAL BAD DEBT ##
		$Query_Data							= "SELECT 
														
													SUM(grand_tot - total_payment) AS total_debt
												FROM view_invoice_payment_reports 
												WHERE 
													(grand_tot - total_payment) > 0
													AND (grand_tot - total_payment) <> pph23
													AND (grand_tot - total_payment) <> ppn
													AND (DATEDIFF(CURRENT_DATE (),IF(receive_date IS NULL,datet,receive_date)) BETWEEN '30' AND '60')";
		$Potential_Debt						= $this->db->query($Query_Data)->result_array();
		
		$Arr_Return['total_potential_bad']	= round($Potential_Debt[0]['total_debt'] / 1000000);
		
		## BAD DEBT ##
		$Query_Data						= "SELECT 
														
													SUM(grand_tot - total_payment) AS total_debt
												FROM view_invoice_payment_reports 
												WHERE 
													(grand_tot - total_payment) > 0
													AND (grand_tot - total_payment) <> pph23
													AND (grand_tot - total_payment) <> ppn
													AND DATEDIFF(CURRENT_DATE (),IF(receive_date IS NULL,datet,receive_date)) > 60";
		$Bad_Debt						= $this->db->query($Query_Data)->result_array();
		
		$Arr_Return['total_bad_debt']	= round($Bad_Debt[0]['total_debt'] / 1000000);
		
		## SO CANCEL ##
		$CancelOrder						= $this->db->get('view_letter_order_cancels')->num_rows();
		$Arr_Return['total_cancel_order']	= $CancelOrder;
		
		## LATE KALIBRASI ##
		$sekarang						= date('Y-m-d');
		$Query_Cal						= "SELECT * FROM view_late_calibration_process_tools WHERE plan_process_date < '".$sekarang."'";
		$Late_Kalibrasi					= $this->db->query($Query_Cal)->num_rows();
		
		$Arr_Return['late_kalibrasi']	= $Late_Kalibrasi;
		
		## LATE KIRIM SUBCON ##
		$Query_Sub						= "SELECT * FROM view_late_subcont_send_tools WHERE plan_subcon_send_date < '".$sekarang."'";
		$Late_Kirim_Subcon				= $this->db->query($Query_Sub)->num_rows();
		
		$Arr_Return['late_kirim_subcon']= $Late_Kirim_Subcon;
		
		## LATE AMBIL SUBCON ##
		$Query_Sub						= "SELECT * FROM view_late_subcont_pick_tools WHERE plan_subcon_pick_date < '".$sekarang."'";
		$Late_Ambil_Subcon				= $this->db->query($Query_Sub)->num_rows();		
		$Arr_Return['late_ambil_subcon']= $Late_Ambil_Subcon;
		
		## LATE KIRIM CUST ##
		$Query_Sub						= "SELECT * FROM view_late_send_customer_tools WHERE plan_delivery_date < '".$sekarang."'";
		$Late_Kirim_Cust				= $this->db->query($Query_Sub)->num_rows();	
		$Arr_Return['late_kirim_cust']	= $Late_Kirim_Cust;
		
		## LATE SCHEDULE ##		
		$Query_Sub						= "SELECT 
												*
											FROM letter_orders 
											WHERE 
												sts_so='OPN'
												AND DATEDIFF(CURRENT_DATE (),tgl_so) > 2";
		$Late_Schedule					= $this->db->query($Query_Sub)->num_rows();
		$Arr_Return['late_schedule']	= $Late_Schedule;
	
		## POTENTIAL BAD DEBT PPH ##
		$Query_Data							= "SELECT 
														
													SUM(grand_tot - total_payment) AS total_debt
												FROM view_invoice_payment_reports 
												WHERE 
													(grand_tot - total_payment) =pph23
													AND (DATEDIFF(CURRENT_DATE (),IF(receive_date IS NULL,datet,receive_date)) BETWEEN '30' AND '60')";
		$Potential_Debt_PPH						= $this->db->query($Query_Data)->result_array();
		
		$Arr_Return['total_potential_bad_pph']	= round($Potential_Debt_PPH[0]['total_debt'] / 1000000);
		
		## BAD DEBT PPH ##
		$Query_Data						= "SELECT 
														
													SUM(grand_tot - total_payment) AS total_debt
												FROM view_invoice_payment_reports 
												WHERE 
													(grand_tot - total_payment) =pph23
													AND DATEDIFF(CURRENT_DATE (),IF(receive_date IS NULL,datet,receive_date)) > 60";
		$Bad_Debt_PPH						= $this->db->query($Query_Data)->result_array();
		
		$Arr_Return['total_bad_debt_pph']	= round($Bad_Debt_PPH[0]['total_debt'] / 1000000);
			
		## POTENTIAL BAD DEBT PPN ##
		$Query_Data							= "SELECT 
														
													SUM(grand_tot - total_payment) AS total_debt
												FROM view_invoice_payment_reports 
												WHERE 
													(grand_tot - total_payment) =ppn
													AND (DATEDIFF(CURRENT_DATE (),IF(receive_date IS NULL,datet,receive_date)) BETWEEN '30' AND '60')";
		$Potential_Debt_PPN						= $this->db->query($Query_Data)->result_array();
		
		$Arr_Return['total_potential_bad_ppn']	= round($Potential_Debt_PPN[0]['total_debt'] / 1000000);
		
		## BAD DEBT PPN ##
		$Query_Data						= "SELECT 
														
													SUM(grand_tot - total_payment) AS total_debt
												FROM view_invoice_payment_reports 
												WHERE 
													(grand_tot - total_payment) =ppn
													AND DATEDIFF(CURRENT_DATE (),IF(receive_date IS NULL,datet,receive_date)) > 60";
		$Bad_Debt_PPN						= $this->db->query($Query_Data)->result_array();
		
		$Arr_Return['total_bad_debt_ppn']	= round($Bad_Debt_PPN[0]['total_debt'] / 1000000);
		
		## PIUTANG MINUS ##
		$Query_Data						= "SELECT 
														
													SUM(grand_tot - total_payment) AS total_debt
												FROM view_invoice_payment_reports 
												WHERE 
													(grand_tot - total_payment) < 0";
		$Piutang_Minus					= $this->db->query($Query_Data)->result_array();
		
		$Arr_Return['piutang_minus']	= round(($Piutang_Minus[0]['total_debt'] * -1) / 1000000);
		
		if($json=='Y'){
			echo json_encode($Arr_Return);
		}else{
			return $Arr_Return;
		}
		
	}
	
	
	
	public function getlatedata($tipe){		
		$Tgl_Telat			= date('Y-m-d');		
		$Cond				= array();		
		if($tipe==2){
			$Table_Name		= 'view_late_calibration_process_tools';
			$Cond			= array(
				"plan_process_date <" => $Tgl_Telat
			);
			
		}else if($tipe==3){
			$Table_Name		= 'view_late_subcont_send_tools';
			$Cond			= array(
				"plan_subcon_send_date <" => $Tgl_Telat
			);
			
			
		}else if($tipe==4){
			$Table_Name		= 'view_late_subcont_pick_tools';
			$Cond			= array(
				"plan_subcon_pick_date <" => $Tgl_Telat
			);
			
			
		}else if($tipe==5){
			$Table_Name		= 'view_late_send_customer_tools';
			$Cond			= array(
				"plan_delivery_date <" => $Tgl_Telat
			);
			
			
		}
		$records		= $this->db->get_where($Table_Name,$Cond)->result_array();	
		
		$data			= array(
			'tipe'			=> $tipe,
			'records'		=> $records
		);
		
		$this->load->view('view_dashboard/getlatedata',$data);
		
			
	}
	public function export_excel($tipe_late){		
		$Tgl_Telat			= date('Y-m-d');
		if($tipe_late==2){
			$Table_Name		= 'view_late_calibration_process_tools';
			$Cond			= array(
				"plan_process_date <" => $Tgl_Telat
			);
			
		}else if($tipe_late==3){
			$Table_Name		= 'view_late_subcont_send_tools';
			$Cond			= array(
				"plan_subcon_send_date <" => $Tgl_Telat
			);
			
			
		}else if($tipe_late==4){
			$Table_Name		= 'view_late_subcont_pick_tools';
			$Cond			= array(
				"plan_subcon_pick_date <" => $Tgl_Telat
			);
			
			
		}else if($tipe_late==5){
			$Table_Name		= 'view_late_send_customer_tools';
			$Cond			= array(
				"plan_delivery_date <" => $Tgl_Telat
			);
			
			
		}
		$records		= $this->db->get_where($Table_Name,$Cond)->result_array();	
		
		$data			= array(
			'tipe_late'			=> $tipe_late,
			'records'		=> $records
		);
		
		$this->load->view('view_dashboard/export_excel',$data);
	}
	
	public function getcertificatedata($tipe){		
		$Cond		= array();				
		if($tipe==1){
			$Tgl_Beda		= date('Y-m-d',strtotime('-2 days'));
			$Query_Sertf	= "SELECT * FROM view_tool_certificates WHERE flag_sertifikat<>'Y' AND tgl_kalibrasi < '".$Tgl_Beda."'";
			$records		= $this->db->query($Query_Sertf)->result_array();
			
		}
		$data			= array(
			'tipe'			=> $tipe,
			'records'		=> $records
		);
		
		$this->load->view('view_dashboard/getcertificatedata',$data);
		
			
	}
	
	public function export_sertifikat($tipe_late){		
		$Tgl_Telat			= date('Y-m-d');		
		$Cond				= array();		
		if($tipe_late==1){
			$Tgl_Beda		= date('Y-m-d',strtotime('-2 days'));
			$Query_Sertf	= "SELECT * FROM view_tool_certificates WHERE flag_sertifikat<>'Y' AND tgl_kalibrasi < '".$Tgl_Beda."'";
			$records		= $this->db->query($Query_Sertf)->result_array();
			
		}
		$data			= array(
			'tipe_late'			=> $tipe_late,
			'records'		=> $records
		);
		
		$this->load->view('view_dashboard/export_sertifikat',$data);
		
	}
	
	public function get_sertifikat_exp(){		
		$Tgl_Balik		= date('Y-m-d',strtotime('+1 month',strtotime(date('Y-m-d'))));	
		$WHERE			= array(
			"valid_until <"	=> $Tgl_Balik
		);
		$results		= $this->db->get_where('view_outstanding_reminders',$WHERE)->result_array();
			
		
		$data			= array(
			'results'		=> $results
		);
		$this->load->view('view_dashboard/reminder_sertifikat',$data);
	}
	
	public function get_incomplete_bast(){		
		$results		= $this->db->get('view_incomplete_delivery_reminders')->result_array();		
		$data			= array(
			'results'		=> $results
		);
		$this->load->view('view_dashboard/incomplete_bast',$data);
	}
	
	public function get_incomplete_receive(){
		$results		= $this->db->get('view_outstanding_receive_tools')->result_array();		
		$data			= array(
			'results'		=> $results
		);
		$this->load->view('view_dashboard/incomplete_receive',$data);
	}
}
