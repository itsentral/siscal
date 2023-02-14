<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Reminder_piutang extends CI_Controller {	
	public function __construct() {
		parent::__construct();
		$this->load->model('master_model');
		// Your own constructor code
		/*
		if(!$this->session->userdata('isSISCALlogin')){
			redirect('login');
		}
		*/
		$controller			= ucfirst(strtolower($this->uri->segment(1)));
		/*
		$this->Arr_Akses			= getAcccesmenu($controller);		
		*/
		$this->Arr_Akses			= array(
			'read'		=> 1,
			'create'	=> 1,
			'update'	=> 1,
			'delete'	=> 1,
			'download'	=> 1,
			'approve'	=> 1,
		);
		
	}	
	public function index() {
		
		$Arr_Akses		= $this->Arr_Akses;
		if($Arr_Akses['read'] !='1'){
			$this->session->set_flashdata("alert_data", "<div class=\"alert alert-warning\" id=\"flash-message\">You Don't Have Right To Access This Page, Please Contact Your Administrator....</div>");
			redirect(site_url('dashboard'));
		}
		
		$data			= array(
			'action'		=>'index',
			'title'			=>'Debt Reminder Letter',
			'akses_menu'	=> $Arr_Akses
		);
		
		$this->load->view('Reminder_piutang/list',$data);
		
	}
	
	function get_data_display(){
		include "ssp.class.php";
		$WHERE		="";
		$table 		= 'ar_reminders';
		$primaryKey = 'id';
		$columns 	= array(
			array( 'db' => 'id', 'dt' => 'id'),
			 array(
				'db' => 'id',
				'dt' => 'DT_RowId'
			),
			array( 'db' => 'nomor_surat', 'dt' => 'nomor_surat'),
			array( 'db' => 'customer_id', 'dt' => 'customer_id'),
			array( 'db' => 'customer_name', 'dt' => 'customer_name'),
			array( 'db' => 'sts_letter', 'dt' => 'sts_letter'),
			array( 'db' => 'pic_name', 'dt' => 'pic_name'),
			array( 'db' => 'pic_email', 'dt' => 'pic_email'),
			array( 'db' => 'cancel_by', 'dt' => 'cancel_by'),
			array( 'db' => 'cancel_reason', 'dt' => 'cancel_reason'),
			array( 'db' => 'flag_email', 'dt' => 'flag_email'),
			array(
				'db' => 'datet',
				'dt'=> 'datet',
				'formatter' => function($d,$row){
					return date('d F Y',strtotime($d));
				}
			),
			array(
				'db' => 'cancel_date',
				'dt'=> 'cancel_date',
				'formatter' => function($d,$row){
					return date('d F Y',strtotime($d));
				}
			),
			
			array(
				'db' => 'id',
				'dt'=> 'action',
				'formatter' => function($d,$row){
					return '';
				}
			)

		);


		$sql_details = array(
			'user' => $this->db->username,
			'pass' => $this->db->password,
			'db'   => $this->db->database,
			'host' => $this->db->hostname
		);
		


		echo json_encode(
			SSP::complex ($_POST, $sql_details, $table, $primaryKey, $columns,null, $WHERE)
		);
	}
	
	function create_letter(){
		set_time_limit(0);
		$Arr_Akses		= $this->Arr_Akses;
		if($Arr_Akses['create'] !='1'){
			$this->session->set_flashdata("alert_data", "<div class=\"alert alert-warning\" id=\"flash-message\">You Don't Have Right To Access This Page, Please Contact Your Administrator....</div>");
			redirect(site_url('reminder_piutang'));
		}
		
		## GET DATA ##
		$Query_Del		= "TRUNCATE TABLE temp_piutang_payment";
		$Pros_Del		= $this->db->query($Query_Del);
		
		## INSERT PROSES ##
		$Query_Temp1	= "INSERT INTO temp_piutang_payment (
								id,
								invoice_no,
								datet,
								customer_id,
								customer_name,
								address,
								dpp,
								ppn,
								pph23,
								grand_tot,
								total_payment,
								send_date,
								receive_date,
								plan_payment,
								leadtime,
								ver_data,
								receive_by
							) SELECT
								id,
								invoice_no,
								datet,
								customer_id,
								customer_name,
								address,
								dpp,
								ppn,

							IF (
								pph23 IS NULL
								OR pph23 = 0
								OR pph23 = '',
								ROUND(dpp * 0.02),
								pph23
							) AS pph23,
							 grand_tot,
							 total_payment,
							 send_date,
							 receive_date,
							 plan_payment,
							 DATEDIFF(
								CURDATE(),

							IF (
								receive_date IS NULL
								OR receive_date = ''
								OR receive_date = '-',
								datet,
								receive_date
							)
							) AS leadtime,
							2 AS ver_data,
							receive_by
							FROM
								calibrationsnew_db.view_invoice_payment_reports
							WHERE
								(grand_tot - total_payment) > 0
							AND (
								DATEDIFF(
									CURDATE(),

								IF (
									receive_date IS NULL
									OR receive_date = ''
									OR receive_date = '-',
									datet,
									receive_date
								)
								)
							) >= 60";
		$Proses_Temp	= $this->db->query($Query_Temp1);
							
		$Query_Temp2	= "INSERT INTO temp_piutang_payment (
								id,
								invoice_no,
								datet,
								customer_id,
								customer_name,
								address,
								dpp,
								ppn,
								pph23,
								grand_tot,
								total_payment,
								send_date,
								receive_date,
								plan_payment,
								leadtime,
								ver_data,
								receive_by
							) SELECT
								id,
								invoice_no,
								datet,
								customer_id,
								customer_name,
								address,
								dpp,
								ppn,

							IF (
								pph23 IS NULL
								OR pph23 = 0
								OR pph23 = '',
								ROUND(dpp * 0.02),
								pph23
							) AS pph23,
							 grand_tot,
							 total_payment,
							 send_date,
							 receive_date,
							 plan_payment,
							 DATEDIFF(
								CURDATE(),

							IF (
								receive_date IS NULL
								OR receive_date = ''
								OR receive_date = '-',
								datet,
								receive_date
							)
							) AS leadtime,
							3 AS ver_data,
							receive_by
							FROM
								view_invoice_payment_reports
							WHERE
								(grand_tot - total_payment) > 0
							AND (
								DATEDIFF(
									CURDATE(),

								IF (
									receive_date IS NULL
									OR receive_date = ''
									OR receive_date = '-',
									datet,
									receive_date
								)
								)
							) >= 60";
		$Proses_Temp2	= $this->db->query($Query_Temp2);
		
		## PROSES SELECT ##		
		$Query_Data	= "SELECT
							customer_id,
							customer_name,
							address,
							COUNT(invoice_no) AS jum_invoice,
							SUM(grand_tot - total_payment) AS total_piutang
						FROM
							temp_piutang_payment						
						GROUP BY
							customer_id
						ORDER BY customer_name ASC";
		$Results	= $this->db->query($Query_Data)->result();
		
		$data			= array(
			'action'		=>'create_letter',
			'title'			=>'Debt Reminder Letter',
			'rows_data'		=> $Results
		);
		
		$this->load->view('Reminder_piutang/list_outstanding',$data);
	}
	function proses_letter($kode_cust=''){
		if($this->input->post()){
			$Nocust			= $this->input->post('customer_id');
			$Customer		= $this->input->post('customer_name');
			$Customer		= $this->input->post('customer_name');
			$Contact_Name	= $this->input->post('pic_name');
			$Contact_Email	= $this->input->post('pic_email');
			$det_Detail		= $this->input->post('detRows');
			
			## DECLARE ##
			$datet			= date('Y-m-d');
			$period			= date('Y-m');
			$month			= date('n');
			$year			= date('Y');
			$Kode_Romawi	= getRomawi($month);
			
			## CEK DATA REMINDER ##
			
			$Urut			= 1;
			$Nomor			= 1;
			$Qry_Urut		= "SELECT * FROM ar_reminders WHERE datet LIKE '".$period."%' ORDER BY id DESC LIMIT 1";
			$Pros_Urut		= $this->db->query($Qry_Urut);
			$num_Urut		= $Pros_Urut->num_rows();
			if($num_Urut > 0){
				$det_Urut	= $Pros_Urut->result();
				$id_Urut	= explode('-',$det_Urut[0]->id);
				$nomor_Urut	= explode('/',$det_Urut[0]->nomor_surat);
				$Urut		= intval($id_Urut[3]) + 1;
				$Nomor		= intval($nomor_Urut[0]) + 1;
			}
			
			## FORMAT ID	:  AR-201907-V3-0001 ##
			$Kode_Letter	= 'AR-'.$period.'-'.sprintf('%04d',$Urut);
			$Nomor_Letter	= sprintf('%04d',$Urut).'/SSC-FNC/SK/'.$Kode_Romawi.'/'.$year;
			
			$Head_Insert	= array(
				'id'			=> $Kode_Letter,
				'nomor_surat'	=> $Nomor_Letter,
				'datet'			=> $datet,
				'customer_id'	=> $Nocust,
				'customer_name'	=> $Customer,
				'sts_letter'	=> 'OPN',
				'pic_name'		=> $Contact_Name,
				'pic_email'		=> $Contact_Email,
				'flag_email'	=> 'N',
				'created_date'	=> date('Y-m-d H:i:s'),
				'created_by'	=> 'OTO-SISTEM'
			);
			
			$Detail_Insert	= array();
			if($det_Detail){
				$loop		= 0;
				foreach($det_Detail as $key=>$vals){
					$loop++;
					$Kode_Detail	= $Kode_Letter.'-'.sprintf('%03d',$loop);
					$Detail_Insert[$loop]					=  $vals;
					$Detail_Insert[$loop]['id']				=  $Kode_Detail;
					$Detail_Insert[$loop]['ar_reminder_id']	=  $Kode_Letter;
					
				}
			}
			
			$this->db->trans_start();		
			$this->db->insert('ar_reminders',$Head_Insert);
			$this->db->insert_batch('ar_reminder_details',$Detail_Insert);
			$this->db->trans_complete();
			if($this->db->trans_status() === FALSE){
				$this->db->trans_rollback();
				$Arr_Data	= array(
					'pesan'		=>'Failed Process. Please try again later ...',
					'status'		=> 2
				);			
			}else{
				$this->db->trans_commit();
				$Arr_Data	= array(
					'pesan'		=>'Success Process. Thanks ...',
					'status'	=> 1
					
				);
				
				//history('Create AR Reminder Letter : '.$Nomor_Letter);
				
			}
			echo json_encode($Arr_Data);
			
		}else{
			$Arr_Akses		= $this->Arr_Akses;
			if($Arr_Akses['create'] !='1'){
				$this->session->set_flashdata("alert_data", "<div class=\"alert alert-warning\" id=\"flash-message\">You Don't Have Right To Access This Page, Please Contact Your Administrator....</div>");
				redirect(site_url('reminder_piutang/create_letter'));
			}
			
			## GET DATA CUSTOMER ##
			$pros_Cust	= $this->db->get_where('customers',array('id'=>$kode_cust));
			$num_Cust	= $pros_Cust->num_rows();
			if($num_Cust > 0){
				$det_Cust	= $pros_Cust->result();
			}else{
				## AMBIL DARI DATABASE CALIBRATIONS ##
				$Query_Cust	= "SELECT * FROM calibrations.customers WHERE id='".$kode_cust."'";
				$det_Cust	= $this->db->query($Query_Cust)->result();
			}
			
			## AMBIL DATA PIUTANG ##
			$det_Piutang	= $this->db->get_where('temp_piutang_payment',array('customer_id'=>$kode_cust))->result();
			$data			= array(
				'action'		=>'proses_letter',
				'title'			=>'Create Debt Reminder Letter',
				'rows_detail'	=> $det_Piutang,
				'rows_header'	=> $det_Cust
			);
			
			$this->load->view('Reminder_piutang/create_reminder',$data);
		}
	}
	function view_letter($kode_letter=''){
		$Arr_Akses		= $this->Arr_Akses;
		if($Arr_Akses['read'] !='1'){
			$this->session->set_flashdata("alert_data", "<div class=\"alert alert-warning\" id=\"flash-message\">You Don't Have Right To Access This Page, Please Contact Your Administrator....</div>");
			redirect(site_url('reminder_piutang'));
		}
		
		$rows_Head	= $this->db->get_where('ar_reminders',array('id'=>$kode_letter))->result();
		$kode_cust	= $rows_Head[0]->customer_id;
		
		## GET DATA CUSTOMER ##
		$pros_Cust	= $this->db->get_where('customers',array('id'=>$kode_cust));
		$num_Cust	= $pros_Cust->num_rows();
		if($num_Cust > 0){
			$det_Cust	= $pros_Cust->result();
		}else{
			## AMBIL DARI DATABASE CALIBRATIONS ##
			$Query_Cust	= "SELECT * FROM calibrations.customers WHERE id='".$kode_cust."'";
			$det_Cust	= $this->db->query($Query_Cust)->result();
		}
		
		## AMBIL DATA PIUTANG ##
		$det_Piutang	= $this->db->get_where('ar_reminder_details',array('ar_reminder_id'=>$kode_letter))->result();
		$data			= array(
			'action'		=>'view_letter',
			'title'			=>'View Debt Reminder Letter',
			'rows_detail'	=> $det_Piutang,
			'rows_cust'		=> $det_Cust,
			'rows_header'	=> $rows_Head
		);
		
		$this->load->view('Reminder_piutang/view_reminder',$data);
	}
	
	function cancel_letter($kode_letter=''){
		if($this->input->post()){
			$Kode_Letter	= $this->input->post('cancel_id');
			$Cancel_Date	= date('Y-m-d H:i:s');
			$Cancel_By		= 'OTO-SISTEM';
			$Cancel_Reason	= $this->input->post('cancel_reason');
			
			## AMBIL DATA ##
			$det_Letter		= $this->db->get_where('ar_reminders',array('id'=>$Kode_Letter))->result();
			if($det_Letter[0]->sts_letter !='OPN'){
				if($det_Letter[0]->sts_letter =='CNC'){
					$Pesan		='Data has been canceled......';
				}else{
					$Pesan		='Data has been sent to customer......';
				}
				$Arr_Data		= array(
					'status'		=> 2,
					'pesan'			=> $Pesan
				);
			}else{
				$Upd_Header	= array(
					'cancel_by'		=> $Cancel_By,
					'cancel_date'	=> $Cancel_Date,
					'cancel_reason'	=> $Cancel_Reason,
					'sts_letter'	=> 'CNC'
				);
				$this->db->trans_start();		
				$this->db->update('ar_reminders',$Upd_Header,array('id'=>$Kode_Letter));
				$this->db->trans_complete();
				if($this->db->trans_status() === FALSE){
					$this->db->trans_rollback();
					$Arr_Data	= array(
						'pesan'		=>'Cancel process failed. Please try again later ...',
						'status'		=> 2
					);			
				}else{
					$this->db->trans_commit();
					$Arr_Data	= array(
						'pesan'		=>'Cancel process success. Thank you & have a nice day ...',
						'status'	=> 1
						
					);					
					//history('Cancel AR Reminder Letter : '.$det_Letter[0]->nomor_surat);					
				}
			}
			echo json_encode($Arr_Data);
		}else{
			$Arr_Akses		= $this->Arr_Akses;
			if($Arr_Akses['delete'] !='1'){
				$this->session->set_flashdata("alert_data", "<div class=\"alert alert-warning\" id=\"flash-message\">You Don't Have Right To Access This Page, Please Contact Your Administrator....</div>");
				redirect(site_url('reminder_piutang'));
			}
			
			$rows_Head	= $this->db->get_where('ar_reminders',array('id'=>$kode_letter))->result();
			$kode_cust	= $rows_Head[0]->customer_id;
			
			## GET DATA CUSTOMER ##
			$pros_Cust	= $this->db->get_where('customers',array('id'=>$kode_cust));
			$num_Cust	= $pros_Cust->num_rows();
			if($num_Cust > 0){
				$det_Cust	= $pros_Cust->result();
			}else{
				## AMBIL DARI DATABASE CALIBRATIONS ##
				$Query_Cust	= "SELECT * FROM calibrations.customers WHERE id='".$kode_cust."'";
				$det_Cust	= $this->db->query($Query_Cust)->result();
			}
			
			## AMBIL DATA PIUTANG ##
			$det_Piutang	= $this->db->get_where('ar_reminder_details',array('ar_reminder_id'=>$kode_letter))->result();
			$data			= array(
				'action'		=>'cancel_letter',
				'title'			=>'Cancel Debt Reminder Letter',
				'rows_detail'	=> $det_Piutang,
				'rows_cust'		=> $det_Cust,
				'rows_header'	=> $rows_Head
			);
			
			$this->load->view('Reminder_piutang/cancel_reminder',$data);
		}
	}
	
	function print_letter($kode_letter='',$jenis='D'){
		$rows_Head	= $this->db->get_where('ar_reminders',array('id'=>$kode_letter))->result();
		$kode_cust	= $rows_Head[0]->customer_id;
		
		## GET DATA CUSTOMER ##
		$pros_Cust	= $this->db->get_where('customers',array('id'=>$kode_cust));
		$num_Cust	= $pros_Cust->num_rows();
		if($num_Cust > 0){
			$det_Cust	= $pros_Cust->result();
		}else{
			## AMBIL DARI DATABASE CALIBRATIONS ##
			$Query_Cust	= "SELECT * FROM calibrations.customers WHERE id='".$kode_cust."'";
			$det_Cust	= $this->db->query($Query_Cust)->result();
		}
		
		## AMBIL DATA PIUTANG ##
		$det_Piutang	= $this->db->get_where('ar_reminder_details',array('ar_reminder_id'=>$kode_letter))->result();
		$data			= array(
			'type_file'			=> $jenis,
			'rows_detail'	=> $det_Piutang,
			'rows_cust'		=> $det_Cust,
			'rows_header'	=> $rows_Head
		);
		
		$this->load->view('Reminder_piutang/print_reminder',$data);
	}
	
	function email_letter($kode_letter=''){
		if($this->input->post()){
			$Kode_Letter	= $this->input->post('email_kode');
			$Update_Date	= date('Y-m-d H:i:s');
			$Update_By		= 'OTO-SISTEM';
			$Initial_Name	= $this->input->post('inisial_name');
			$Email_Name		= $this->input->post('pic_name');
			$Email_To		= $this->input->post('pic_email');
			
			## AMBIL DATA ##
			$det_Letter		= $this->db->get_where('ar_reminders',array('id'=>$Kode_Letter))->result();
			if($det_Letter[0]->sts_letter !='OPN'){
				if($det_Letter[0]->sts_letter =='CNC'){
					$Pesan		='Data has been canceled......';
				}else{
					$Pesan		='Data has been sent to customer......';
				}
				$Arr_Data		= array(
					'status'		=> 2,
					'pesan'			=> $Pesan
				);
			}else{
				$Email_Sender	= $this->db->get_where('email_senders',array('flag_active'=>'Y','category'=>'Schedule'))->result();
				
				if(!$Email_Sender){
					$Arr_Data		= array(
						'status'		=> 2,
						'pesan'			=> 'Empty Email Sender. Please Set Email Sender First......'
					);				
				}else{
					
					$Email_Setting	= $this->db->get('setting_emails')->result();
					
					$sroot 			= $_SERVER['DOCUMENT_ROOT'];
					$data_url		= base_url();
					$Split_Beda		= explode('/',$data_url);
					$Jum_Beda		= count($Split_Beda);
					$Nama_APP		= $Split_Beda[$Jum_Beda - 2];
					//echo"<pre>";print_r($Split_Beda);exit;
					$directory_file	= $sroot.'/assets/file/';
					if(file_exists($sroot."/application/libraries/PHPMailer/PHPMailerAutoload.php")){
						include $sroot."/application/libraries/PHPMailer/PHPMailerAutoload.php";
						$img_file	= $sroot.'/assets/img/logo.jpg';
						
					}else{
						include $sroot."/".$Nama_APP."/application/libraries/PHPMailer/PHPMailerAutoload.php";
						$directory_file	= $sroot."/".$Nama_APP.'/assets/file/';
						$img_file		= $sroot."/".$Nama_APP.'/assets/img/logo.jpg';
						
					}
					
					
					
					$Body= "<html xmlns=\"http://www.w3.org/1999/xhtml\">
							<head>
								<meta http-equiv=\"Content-Type\" content=\"text/html; charset=UTF-8\" />
								<title>Agungrent</title>
								<meta name=\"viewport\" content=\"width=device-width, initial-scale=1.0\"/>
							</head>
							<body style=\"margin: 0; padding: 0; background-color:#c0c0c0;\">
								<table border=\"0\" cellpadding=\"0\" cellspacing=\"0\" width=\"100%\">	
									<tr>
										<td style=\"padding: 10px 0 30px 0;\">
											<table align=\"center\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\" width=\"600\" style=\"border: 1px solid #cccccc; border-collapse: collapse;\">
												<tr>
													<td bgcolor=\"#ffffff\" style=\"padding: 30px 30px 30px 30px;\">
														<table border=\"0\" cellpadding=\"0\" cellspacing=\"0\" width=\"100%\">
															<tr>
																<td style=\"color: #ffffff; font-family: Arial, sans-serif; font-size: 14px;\" width=\"75%\">
																	<img src='cid:1001' alt=\"Sentral\" style=\"display: block;\" />
																</td>
																<td align=\"right\" width=\"25%\">
																	
																</td>
															</tr>
														</table>
													</td>
												</tr>
												<tr>
													<td bgcolor=\"#ffffff\" style=\"padding: 40px 30px 40px 30px;\">
														<table border=\"0\" cellpadding=\"0\" cellspacing=\"0\" width=\"100%\">
															<tr>
																<td style=\"color: #153643;font-family: Arial, sans-serif; font-size: 18px;\">
																	Dear <b>".$Initial_Name." ".ucwords(strtolower($Email_Name))."</b>
																</td>
															</tr>
															<tr>
																<td style=\"padding: 20px 0 30px 0; color: #808080; font-family: Arial, sans-serif; font-size: 14px; line-height: 20px;\">
																	Berikut terlampir surat konfirmasi status tagihan atas ".$det_Letter[0]->customer_name.", mohon konfirmasi atas tagihan tersebut.
																	
																	
																</td>
															</tr>
														</table>
														<br>Terima kasih atas perhatian dan kerjasamanya.<br><br><br><font style=\"font-family: Arial, sans-serif; font-size: 14px; line-height: 20px;text-align:center;\">Sentral Kalibrasi Sistem<br>Cikarang Square Blok B No. 11, Jl. Cibarusah Cikarang Selatan - Jawa Barat 17530<br>Telp. 021-21381247-6,29067201-3, Fax 29067204 - <b><i>cs@sentralkalibrasi.co.id</i></b></font><br>
													</td>
												</tr>
												<tr>
													<td bgcolor=\"#EFEFEF\" style=\"padding: 30px 30px 30px 30px;\">
														<table border=\"0\" cellpadding=\"0\" cellspacing=\"0\" width=\"100%\">
															<tr>
																<td style=\"color: #555; font-family: Arial, sans-serif; font-size: 14px;\" width=\"75%\">&reg; Sentral Kalibrasi " . date("Y") . "<br/>
																</td>
															</tr>
														</table>
													</td>
												</tr>
											</table>
										</td>
									</tr>
								</table>
							</body>
							</html>";
					//echo $Body;exit;
					$mail					= new PHPMailer();
					
					$mail->IsSMTP();
					//$mail->SMTPDebug 		= 2;
					$mail->Mailer			= "smtp";
					$mail->Host				= $Email_Setting[0]->email;
					$mail->Port				= $Email_Setting[0]->port;
					$mail->SMTPAuth			= true;
					//$mail->SMTPSecure 		= 'tls';
					$mail->Username			= $Email_Sender[0]->email_from;
					$mail->Password			= Dekripsi($Email_Sender[0]->pass_from);
					
					// - - - - - - - - - - - - - - - - - - - From - To - - - - - - - - - - - - - - - - - - - //
					
					
					$mail->From				= $Email_Sender[0]->email_from; // sender email
					$mail->FromName			= $Email_Sender[0]->name_from; // name sender
					
					
					$mail->AddAddress($Email_To, ucwords(strtolower($Initial_Name.' '.$Email_Name)));
					if($Email_Sender[0]->cc_email){
						$Arr_Nama	= array();
						$Arr_Email	= array();
						$fnd		= strstr($Email_Sender[0]->cc_email,',');
						if($fnd){
							$Arr_Nama	= explode(',',$Email_Sender[0]->cc_name);
							$Arr_Email	= explode(',',$Email_Sender[0]->cc_email);
						}else{
							$Arr_Nama[1]	= $Email_Sender[0]->cc_name;
							$Arr_Email[1]	= $Email_Sender[0]->cc_email;
						}
						
						foreach($Arr_Email as $key=>$vals){
							$mail->addCC($vals,$Arr_Nama[$key]);
						}
					}
					
					
					$mail->addCC('mahrus86ali@gmail.com','Mahrus Ali');
					
					// - - - - - - - - - - - - - - - - - - - Message Here - - - - - - - - - - - - - - - - - - - //
					$subject	="Konfirmasi Status Tagihan";
					
					$mail->IsHTML(true);
					$mail->Subject	= $subject;
					
					$mail->Body		= $Body;
					
					
					$Data_File = $this->print_letter($Kode_Letter,'F');
					
					//echo"masuk bro";exit;
					$file_pdf	= $directory_file.$det_Letter[0]->id.'.pdf';
					$mail->AddAttachment($file_pdf);  // attach pdf
					$mail->AddEmbeddedImage($img_file, 1001);
					if(!$mail->Send()) {
						//$mail->ErrorInfo
						unlink($file_pdf);
						$Arr_Data		= array(
							'status'		=> 2,
							'pesan'			=> 'Send Email Failed. Please Try Again...'
						);
						
					}else{
						$Upd_Header	= array(
							'modified_by'		=> $Update_By,
							'modified_date'		=> $Update_Date,
							'pic_email'			=> $Email_To,
							'pic_name'			=> ucwords(strtolower($Initial_Name.' '.$Email_Name)),
							'sts_letter'		=> 'CLS',
							'flag_email'		=> 'Y'
						);
						$this->db->update('ar_reminders',$Upd_Header,array('id'=>$Kode_Letter));
						unlink($file_pdf);
						$Arr_Data		= array(
							'status'		=> 1,
							'pesan'			=> 'Email Has Been Send. Thank You & Have A Nice Day......'
						);
						//history('Send Email AR Reminder Letter : '.$det_Letter[0]->nomor_surat);	
					}
				}
			}
			echo json_encode($Arr_Data);
		}else{
			$Arr_Akses		= $this->Arr_Akses;
			if($Arr_Akses['update'] !='1'){
				$this->session->set_flashdata("alert_data", "<div class=\"alert alert-warning\" id=\"flash-message\">You Don't Have Right To Access This Page, Please Contact Your Administrator....</div>");
				redirect(site_url('reminder_piutang'));
			}
			
			$rows_Head	= $this->db->get_where('ar_reminders',array('id'=>$kode_letter))->result();
			$kode_cust	= $rows_Head[0]->customer_id;
			
			## GET DATA CUSTOMER ##
			$pros_Cust	= $this->db->get_where('customers',array('id'=>$kode_cust));
			$num_Cust	= $pros_Cust->num_rows();
			if($num_Cust > 0){
				$det_Cust	= $pros_Cust->result();
			}else{
				## AMBIL DARI DATABASE CALIBRATIONS ##
				$Query_Cust	= "SELECT * FROM calibrations.customers WHERE id='".$kode_cust."'";
				$det_Cust	= $this->db->query($Query_Cust)->result();
			}
			
			## AMBIL DATA PIUTANG ##
			$det_Piutang	= $this->db->get_where('ar_reminder_details',array('ar_reminder_id'=>$kode_letter))->result();
			$data			= array(
				'action'		=>'email_letter',
				'title'			=>'Send Email Reminder Letter',
				'rows_detail'	=> $det_Piutang,
				'rows_cust'		=> $det_Cust,
				'rows_header'	=> $rows_Head
			);
			
			$this->load->view('Reminder_piutang/email_reminder',$data);
		}
	}
}
