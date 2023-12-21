<?php
defined('BASEPATH') OR exit('No direct script access allowed');
require APPPATH.'third_party/endroid_qrcode/autoload.php';
use Endroid\QrCode\ErrorCorrectionLevel;
use Endroid\QrCode\QrCode;
use setasign\Fpdi\Fpdi;
class Calibration_result extends CI_Controller { 
	public function __construct(){
        parent::__construct();	
		
		$this->load->database();
        if(!$this->session->userdata('isSISCALlogin')){
			redirect('login');
		}
		$this->load->model('master_model');
		$controller			= ucfirst(strtolower($this->uri->segment(1)));
		$this->Arr_Akses	= getAcccesmenu($controller);
		
		$this->folder		= 'Hasil_kalibrasi';
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
			'title'			=> 'INDEX OF CALIBRATION RESULT',
			'action'		=> 'index',
			'akses_menu'	=> $Arr_Akses
		);
		history('View List Calibration Result');
		$this->load->view($this->folder.'/v_calibration_result',$data);
	}
	function get_data_display(){
		$Arr_Akses			= $this->Arr_Akses;
		
		$Month_Find			= $this->input->post('bulan');
		$Year_Find			= $this->input->post('tahun');		
		$WHERE				= "det_trans.flag_proses = 'Y'
							   AND det_trans.approve_certificate IN ('OPN', 'REJ')";
		
		if($Month_Find){
			if(!empty($WHERE))$WHERE .=" AND ";
			$WHERE	.="MONTH(head_trans.tgl_so) = '".$Month_Find."'";
		}
		
		if($Year_Find){
			if(!empty($WHERE))$WHERE .=" AND ";
			$WHERE	.="YEAR(head_trans.tgl_so) = '".$Year_Find."'";
		}
		
		$Group_By		= "GROUP BY
						head_trans.letter_order_id";
		
		$requestData	= $_REQUEST;
		
		$like_value     = $requestData['search']['value'];
        $column_order   = $requestData['order'][0]['column'];
        $column_dir     = $requestData['order'][0]['dir'];
        $limit_start    = $requestData['start'];
        $limit_length   = $requestData['length'];
		
		$columns_order_by = array(
			0 => 'head_trans.no_so',
			1 => 'head_trans.tgl_so',
			2 => 'head_trans.customer_name',
			3 => 'head_trans.quotation_nomor',
			4 => 'head_trans.pono'
		);
		
		
		
		if($like_value){
			if(!empty($WHERE))$WHERE	.=" AND ";
			$WHERE	.="(
						  head_trans.no_so LIKE '%".$this->db->escape_like_str($like_value)."%'
						  OR DATE_FORMAT(head_trans.tgl_so, '%d %b %Y') LIKE '%".$this->db->escape_like_str($like_value)."%'
						  OR head_trans.customer_name LIKE '%".$this->db->escape_like_str($like_value)."%'
						  OR head_trans.quotation_nomor LIKE '%".$this->db->escape_like_str($like_value)."%'
						  OR head_trans.pono LIKE '%".$this->db->escape_like_str($like_value)."%'
						)";
		}
		
		
		$sql = "SELECT
					head_trans.letter_order_id,
					head_trans.no_so,
					head_trans.tgl_so,
					head_trans.customer_id,
					head_trans.customer_name,
					head_trans.quotation_id,
					head_trans.quotation_nomor,
					head_trans.quotation_date,
					head_trans.pono,
					head_trans.podate,
					head_trans.marketing_id,
					head_trans.marketing_name,
					(@ROW :=@ROW + 1) AS urut
				FROM
					trans_details head_trans
					INNER JOIN trans_data_details det_trans ON head_trans.id = det_trans.trans_detail_id,
				 (SELECT @ROW := 0) r
				WHERE ".$WHERE."
				".$Group_By;
		//print_r($sql);exit();
		$fetch['totalData'] 	= $this->db->query($sql)->num_rows();
		$fetch['totalFiltered']	= $this->db->query($sql)->num_rows();

		

		$sql .= " ORDER BY head_trans.letter_order_id DESC,".$columns_order_by[$column_order]." ".$column_dir." ";
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
			
			$Code_SO		= $row['letter_order_id'];
			$Nomor_SO		= $row['no_so'];
			$Tgl_SO			= date('d-m-Y',strtotime($row['tgl_so']));
			$Customer		= $row['customer_name'];
			$Nocust			= $row['customer_id'];
			$Code_Quot		= $row['quotation_id'];
			$Nomor_Quot		= $row['quotation_nomor'];
			$Nomor_PO		= $row['pono'];
			$Date_PO		= date('d-m-Y',strtotime($row['podate']));
			$Date_Quot		= date('d-m-Y',strtotime($row['quotation_date']));
			
			
			$Template		='-';
			if($Arr_Akses['create'] == '1' || $Arr_Akses['update'] == '1'){
				$Template		="<a href='".site_url('Calibration_result/view_detail?kode='.urlencode($Code_SO))."' class='btn btn-sm bg-navy-active' title='DETAIL SERVICE ORDER'> <i class='fa fa-search'></i> </a>";
			}
			$nestedData		= array();
			$nestedData[]	= $Nomor_SO;
			$nestedData[]	= $Tgl_SO;
			$nestedData[]	= $Customer;
			$nestedData[]	= $Nomor_Quot;
			$nestedData[]	= $Nomor_PO;
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
	
	function view_detail(){
		$rows_Quot	= $rows_Letter = $rows_Trans = $rows_Cust = array();
		if($this->input->get()){
			$Code_SO		= urldecode($this->input->get('kode'));
			$rows_Letter	= $this->db->get_where('letter_orders',array('id'=>$Code_SO))->row();
			$rows_Quot		= $this->db->get_where('quotations',array('id'=>$rows_Letter->quotation_id))->row();
			$Query_Data		= "SELECT
									det_tool.*,
									head_tool.labs,
									head_tool.insitu,
									head_tool.subcon,
									head_tool.location,
									head_tool.so_descr,
									head_tool.range,
									head_tool.piece_id,
									head_tool.quotation_detail_id
							FROM
								trans_data_details det_tool
								INNER JOIN trans_details head_tool ON head_tool.id = det_tool.trans_detail_id
							WHERE head_tool.letter_order_id = '".$Code_SO."'";
			$rows_Trans	= $this->db->query($Query_Data)->result();
			if($rows_Trans){
				$OK_Proses      	= 1;
				
			}
			$rows_Cust		= $this->db->get_where('customers',array('id'=>$rows_Letter->customer_id))->row();
		}
		
		if($OK_Proses == 1){
			$Arr_Akses			= $this->Arr_Akses;
			$data = array(
				'title'			=> 'DETAIL SERVICE ORDER',
				'action'		=> 'view_detail',
				'akses_menu'	=> $Arr_Akses,
				'rows_quot'		=> $rows_Quot,
				'rows_letter'	=> $rows_Letter,
				'rows_trans'	=> $rows_Trans,
				'rows_cust'		=> $rows_Cust,
				'Code_SO'		=> $Code_SO
			);
			
			$this->load->view($this->folder.'/v_calibration_result_preview',$data);
		}else{			
			$this->session->set_flashdata("alert_data", "<div class=\"alert alert-warning\" id=\"flash-message\">No records wa found....</div>");
			redirect(site_url('Calibration_result'));
			
		}
		
	}
	
	
	
	function calibration_result_process(){
		$rows_Header = $rows_Detail = $rows_Teknisi = $rows_Supplier = array();
		$Code_Back	= $Code_Teknisi = '';
		if($this->input->post()){
			$Code_Alat 		= $this->input->post('code');			
			$rows_Detail	= $this->db->get_where('trans_data_details',array('id'=>$Code_Alat))->row();
			
			$rows_Header	= $this->db->get_where('trans_details',array('id'=>$rows_Detail->trans_detail_id))->row();
			$Code_Back		= $rows_Header->letter_order_id;
			
			
		}
		$Arr_Akses			= $this->Arr_Akses;
		$data = array(
			'title'			=> 'CALIBRATION RESULT PROCESS',
			'action'		=> 'calibration_result_process',
			'akses_menu'	=> $Arr_Akses,
			'rows_detail'	=> $rows_Detail,
			'rows_header'	=> $rows_Header,
			'Code_Back'		=> $Code_Back
		);
		
		$this->load->view($this->folder.'/v_calibration_result_process',$data);
	}
	
	
	
	function save_calibration_result_process(){
		$rows_Return	= array(
			'status'		=> 2,
			'pesan'			=> 'No Record was found...'
		);
		if($this->input->post()){
			//echo"<pre>";print_r($this->input->post());exit;
			$Created_By		= $this->session->userdata('siscal_userid');
			$Created_Date	= date('Y-m-d H:i:s');
			
			$Code_Detail	= $this->input->post('code_detail');
			$Reason_Fail	= strtoupper($this->input->post('failed_reason'));
			$rows_Find		= $this->db->get_where('trans_data_details',array('id'=>$Code_Detail))->row();
			
			$this->db->trans_begin();
			
			$Old_File		= $rows_Find->file_kalibrasi;
			$Old_File_Tipe	= $rows_Find->file_kalibrasi_tipe;
			
			$Rename_File	= $Code_Detail.'-'.date('YmdHis').'.'.$Old_File_Tipe;
			
			$Ins_Log		= array(
				'trans_data_detail_id'	=> $Code_Detail,
				'file_kalibrasi'		=> $Rename_File,
				'file_type'				=> $Old_File_Tipe,
				'reopen_reason'			=> $Reason_Fail,
				'reopen_by'				=> $Created_By,
				'reopen_date'			=> $Created_Date
			);
			$Has_Ins_Log	= $this->db->insert('trans_data_detail_cals_file_log',$Ins_Log);
			if($Has_Ins_Log !== TRUE){
				$Pesan_Error	= 'Error Insert Log Calibration File';
			}
			
			## RENAME FILE LAMA ##
			rename($this->file_location.'hasil_kalibrasi/'.$Old_File,$this->file_location.'hasil_kalibrasi/'.$Rename_File);
			
			$UPD_Detail			= array(
				'modified_by'		=> $Created_By,
				'modified_date'		=> $Created_Date
			);
				
			
			$Type_File			= '';
			$Data_File			= array();
			$Nama_File			= '';
			$Path_Source		= './assets/file/';
			$Path_Destination	= 'hasil_kalibrasi';
			
			//print_r($_SERVER['DOCUMENT_ROOT']);
			//exit;
			
			/* -------------------------------------------------------------
			|  UPLOAD FILE BASED ON PILIHAN FILE
			| ---------------------------------------------------------------
			*/
			
			$OK_Upload			= $OK_Selfie = 0;
			if($_FILES && isset($_FILES['lampiran_kalibrasi']['name']) && $_FILES['lampiran_kalibrasi']['name'] != ''){
				$nama_image 	= $_FILES['lampiran_kalibrasi']['name'];
				$type_iamge		= $_FILES['lampiran_kalibrasi']['type'];
				$tmp_image 		= $_FILES['lampiran_kalibrasi']['tmp_name'];
				$error_image	= $_FILES['lampiran_kalibrasi']['error'];
				$size_image 	= $_FILES['lampiran_kalibrasi']['size'];
				
				$cekExtensi 	= strtolower(getExtension($nama_image));
				$Nama_File		= $Code_Detail.'.'.$cekExtensi;
				$Type_File		= $cekExtensi;
				
				$Pesan_Error	= '';
				if($error_image == '1'){
					$OK_Proses		= 0;
					$Pesan_Error	= 'File Size Exceeds The Maximum Limit';
					
				}else{					
					$Data_File		= array(
						'name'			=> $nama_image,
						'type'			=> $type_iamge,
						'tmp_name'		=> $tmp_image,
						'error'			=> $error_image,
						'size'			=> $size_image
					);
					$OK_Upload		= 1;
					$Delete_FTP 	= delFile_Kalibrasi($Path_Destination.$Nama_File);
					$Has_Upload 	= PdfUpload_Kalibrasi($Data_File,$Path_Destination,$Code_Detail);
					
					$UPD_Detail['file_kalibrasi']		= $Nama_File;
					$UPD_Detail['file_kalibrasi_tipe']	= $Type_File;
					
				}					
			}
			$Has_Upd_Trans		= $this->db->update('trans_data_details',$UPD_Detail,array('id'=>$Code_Detail));
			if($Has_Upd_Trans !== TRUE){
				$Pesan_Error	= 'Error Update Trans Data Details';
			}
				
			if ($this->db->trans_status() != TRUE || !empty($Pesan_Error)){
				$this->db->trans_rollback();
				$rows_Return		= array(
					'status'		=> 2,
					'pesan'			=> 'Save Process  Failed, please try again...'
				);
				history('Update Calibration File Process Code '.$Code_Detail.' - '.$Pesan_Error);
			}else{
				$this->db->trans_commit();
				$rows_Return		= array(
					'status'		=> 1,
					'pesan'			=> 'Save process success. Thank you & have a nice day......'
				);
				history('Update Calibration File Process Code '.$Code_Detail);
			}	
			
		}
		echo json_encode($rows_Return);
	}
	
	
	function GenerateQRImage($Nama_File ='',$Location='',$Link_URL=''){
		$sroot = $_SERVER['DOCUMENT_ROOT'];
		include $sroot.'/Siscal_Dashboard/application/libraries/phpqrcode/qrlib.php';
		//$this->load->library('phpqrcode/qrlib');
		
		$File_Path	= $this->file_location.$Location.'/'.$Nama_File.'.png';
		if(file_exists($File_Path)) {
			unlink($File_Path);
		}
		$rows_Trans		= $this->db->get_where('trans_data_details',array('id'=>$Nama_File))->row();
		$rows_Tool		= $this->db->get_where('tools',array('id'=>$rows_Trans->tool_id))->row();
		
		if(strtolower($rows_Tool->certification_id) == 'kan'){
			$Logo_Path	= './assets/file/'.$Location.'/cals_kan.png';
		}else{
			$Logo_Path	= './assets/file/'.$Location.'/logo2.png';
		}
			
		$Label_Link	= $Link_URL;
		QRcode::png($Label_Link,$File_Path , QR_ECLEVEL_L, 11.45,0);
		
		$QR 		= imagecreatefrompng($File_Path);
		$logo 		= imagecreatefrompng($Logo_Path);
		
		$QR_width 		= imagesx($QR);
		$QR_height 		= imagesy($QR);

		$logo_width 	= imagesx($logo);
		$logo_height 	= imagesy($logo);

		// Scale logo to fit in the QR Code
		$logo_qr_width 	= $QR_width/3;
		$scale 			= $logo_width/$logo_qr_width;
		$logo_qr_height = $logo_height/$scale;
		
		list($newwidth, $newheight) = getimagesize($Logo_Path);
		$out 			= imagecreatetruecolor($QR_width, $QR_width);
		imagecopyresampled($out, $QR, 0, 0, 0, 0, $QR_width, $QR_height, $QR_width, $QR_height);
		
		if(strtolower($rows_Tool->certification_id) == 'kan'){
			imagecopyresampled($out, $logo, $QR_width/2.65, $QR_height/2.65, 0, 0, $QR_width/4, $QR_height/5, $newwidth, $newheight);
		}else{
			imagecopyresampled($out, $logo, $QR_width/2.65, $QR_height/2.65, 0, 0, $QR_width/4, $QR_height/4, $newwidth, $newheight);
		}
		
		
		
		imagepng($out,$File_Path);
		imagedestroy($out);

		
		## Change image color ##
		
		$im = imagecreatefrompng($File_Path); 
		$r = 44;$g = 62;$b = 80;
		for($x=0;$x<imagesx($im);++$x){
			for($y=0;$y<imagesy($im);++$y){
				$index 	= imagecolorat($im, $x, $y);
				$c   	= imagecolorsforindex($im, $index);
				if(($c['red'] < 100) && ($c['green'] < 100) && ($c['blue'] < 100)) { // dark colors
					// here we use the new color, but the original alpha channel
					$colorB = imagecolorallocatealpha($im, 0x12, 0x2E, 0x31, $c['alpha']);
					imagesetpixel($im, $x, $y, $colorB);
				}
			}
		}
		imagepng($im,$File_Path);
		imagedestroy($im);
		
	}
	
	
	function print_barcode_calibration_tool(){
		$rows_Sentral		= $rows_Tool = array();		
		if($this->input->post()){
			$Code_Sentral	= $this->input->post('code');
			
			$rows_Trans		= $this->db->get_where('trans_data_details',array('id'=>$Code_Sentral))->row();
			$rows_Sentral	= $this->db->get_where('sentral_customer_tools',array('sentral_tool_code'=>$rows_Trans->sentral_code_tool))->row();
			$rows_Tool		= $this->db->get_where('tools',array('id'=>$rows_Sentral->tool_id))->row();
			
			$File_QR		= $rows_Trans->qr_code;
			$Path_PDF		= $this->file_location.'QRCode/'.$Code_Sentral.'.pdf';
			$Name_File		= 'QR-'.$Code_Sentral.'.jpg';
			
						
			$HashKey		= '173ALIDYhG93b0qyJfIxfsdgfh2guVoUubW46hjwvniR200881173Gacad0FgaC9mi2008811M4ru5L1mChaeMo0';
			$CodeHash		= str_replace('=','',enkripsi_url($rows_Trans->id));
			$Link_URL		= 'https://sentral.dutastudy.com/Siscal_CRM/index.php/CertificateGenerate/CertificateAuthorized/'.$CodeHash;
			
			//echo $Path_PDF;exit;
			$GenerateQRCode	= $this->GenerateQRImage($rows_Trans->id,'QRCode',$Link_URL);
			//exit;
			if(file_exists($Path_PDF)){
				unset($Path_PDF);
			}
			
			## GENARATE PDF ##
			$File_PDF		= $this->GenerateQRFile($Code_Sentral);
			if(file_exists($Path_PDF)){
				chmod($Path_PDF, 0777);
			}
			//exit;
			
			$myurl 			= $Path_PDF.'[0]';
			$image 			= new Imagick();
			$image->setResolution( 300, 300 );
			$image->readImage($myurl);
			$image->setImageFormat( "jpeg" );
			$image->writeImage($this->file_location.'QRCode/'.$Name_File);
			$image->clear();
			$image->destroy();
			
			
			## HAPUS FILE PDF ##
			if(file_exists($Path_PDF)){
				unlink($Path_PDF);
			} 
			
			## HAPUS FILE QR ##
			
			$File_Barcode	= $this->file_location.'QRCode/'.$Code_Sentral.'.png';
			if(file_exists($File_Barcode)){
				chmod($File_Barcode, 0777);
				unlink($File_Barcode);
			}
			
			
			
			$UPD_Detail		= array(
				'qr_code'	=> $Name_File
			);
			
			$Has_Upd_Detail	= $this->db->update('trans_data_details',$UPD_Detail,array('id'=>$Code_Sentral));
			$rows_Trans		= $this->db->get_where('trans_data_details',array('id'=>$Code_Sentral))->row();
			
			
			$rows_Return	= array(
				'hasil'			=> 1,
				'pesan'			=> 'Berhasil',
				'path'			=> $this->file_attachement.'QRCode/'.$Name_File
			);
			
			echo json_encode($rows_Return);
			
		}		
	}
	
	function GenerateQRFile($Code=''){
		$rows_trans		= $this->db->get_where('trans_data_details',array('id'=>$Code))->row();
		$rows_header	= $this->db->get_where('sentral_customer_tools',array('sentral_tool_code'=>$rows_trans->sentral_code_tool))->row();
		$rows_tool		= $this->db->get_where('tools',array('id'=>$rows_header->tool_id))->row();
		
		$File_Path		= $this->file_location.'QRCode/'.$Code.'.pdf';
		
		$sroot = $_SERVER['DOCUMENT_ROOT'];
		include $sroot.'/Siscal_mobile/application/third_party/MPDF57/mpdf.php';
		$mpdf=new mPDF('utf-8', array(29,50));				// Create new mPDF Document
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
		$img_sentral	= $sroot.'/Siscal_Dashboard/assets/img/logo_flat.png';
		$img_kan		= $sroot.'/Siscal_Dashboard/assets/img/kan.png';
		//echo"<pre>";print_r($rows_header);exit;

		$HashKey		= '173ALIDYhG93b0qyJfIxfsdgfh2guVoUubW46hjwvniR200881173Gacad0FgaC9mi2008811M4ru5L1mChaeMo0';
		$CodeHash		= Enkripsi($rows_trans->id,$HashKey);
		$Link_URL		= 'https://sentral.dutastudy.com/Siscal_CRM/index.php/CertificateGenerate/CertificateAuthorized/'.$CodeHash;

		?>  

		<style type="text/css">
		@page {
			margin-top: 0.1cm;
			margin-left: 0.1cm;
			margin-right: 0.1cm;
			margin-bottom: 0.1cm;
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
			font-size:9px;
			padding: 2px;
			border-color: #666666;
		}

		table.noborder td {	
			padding: 1px;
			border-color: #666666;
			background-color: #ffffff;
			font-size:9px;
			font-family: verdana,arial,sans-serif;
		}

		table.gridtable {
			font-family: verdana,arial,sans-serif;
			font-size:10px;
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
			height: 14px; 
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
			margin: 1.5mm;
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
			left: 214px;
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
		.text-wrap{
			overflow-wrap: break-word !important;
			word-wrap: break-word !important;
			white-space: pre-wrap !important;
			word-break: break-word !important;
		}
		.text-center{
			text-align:center !important;
			vertical-align : middle !important;
		}
		.text-left{
			text-align:left !important;
			vertical-align : middle !important;
		}
		</style>
		<?php
		$Font_Footer	= $Font_Header = "8px";
		$Code_Trans		= $rows_trans->id;
		$Code_Serial	= $rows_trans->no_serial_number;
		$Code_Identify	= $rows_trans->no_identifikasi;
		$Text_Head		= $Code_Trans;
		if(!empty($Code_Identify) && $Code_Identify !== '-'){
			$Text_Head		= $Code_Identify;
		}

		if(!empty($Code_Serial) && $Code_Serial !== '-'){
			$Text_Head		= $Code_Serial;
		}

		if(strlen($Text_Head) > 20){
			//$Font_Header	= "6px";
		}
		$Text_Footer	= date('d-m-Y',strtotime($rows_trans->datet));
		if(!empty($rows_trans->valid_until) && $rows_trans->valid_until !== '0000-00-00' && $rows_trans->valid_until !== '1970-01-01'){
			$Text_Footer	.='<br>sd<br>'.date('d-m-Y',strtotime($rows_trans->valid_until));
			//$Font_Footer	= "6px";
		}

		$rows_Image	= "";
		if(strtolower($rows_tool->certification_id) == 'kan'){
			$rows_Image	= "
			<tr>
				<td width='100%' class='text-center'>
					<img src='".$img_kan."' width='30' height='25'>
				</td>
			</tr>
			";
		}



		$Header	="
		<div style='border-width: 1px;border-color: #666666;border-style: solid;'>
			<table class='noborder' width='100%' height='100%'>
				<tr>
					<td width='100%' class='text-center text-wrap' style='font-size:".$Font_Header." !important;'>".$Text_Head."</td>
				</tr>
				<tr>
					<td width='100%' class='text-center'>
						<img src='".$this->file_location.'QRCode/'.$Code_Trans.".png' width='85' height='80'>
					</td>
				</tr>
				<tr>
					<td width='100%' class='text-center text-wrap' style='font-size:".$Font_Footer." !important;'>".$Text_Footer."</td>
				</tr>
			</table>	
		</div>
		";

		echo $Header;
			
		$html = ob_get_contents();
		ob_end_clean();
		//echo $html;exit;
		$mpdf->WriteHTML($html);
		$mpdf->Output($File_Path ,'F');
	}
	
	
	/*
	function print_barcode_calibration_tool(){
		$rows_Sentral		= $rows_Tool = array();		
		if($this->input->post()){
			$Code_Sentral	= $this->input->post('code');
			$rows_Trans		= $this->db->get_where('trans_data_details',array('id'=>$Code_Sentral))->row();
			$rows_Sentral	= $this->db->get_where('sentral_customer_tools',array('sentral_tool_code'=>$rows_Trans->sentral_code_tool))->row();
			$rows_Tool		= $this->db->get_where('tools',array('id'=>$rows_Sentral->tool_id))->row();
			
			$File_QR		= $rows_Trans->qr_code;
			$Path_PDF		= $this->file_location.'QRCode/'.$Code_Sentral.'.pdf';
			$Name_File		= 'QR-'.$Code_Sentral.'.jpg';
			
			if(empty($File_QR) || $File_QR == '-'){				
				$HashKey		= '173ALIDYhG93b0qyJfIxfsdgfh2guVoUubW46hjwvniR200881173Gacad0FgaC9mi2008811M4ru5L1mChaeMo0';
				$CodeHash		= str_replace('=','',enkripsi_url($rows_Trans->id));
				$Link_URL		= 'https://sentral.dutastudy.com/Siscal_CRM/index.php/CertificateGenerate/CertificateAuthorized/'.$CodeHash;
				//echo $CodeHash.' '.dekripsi_url($CodeHash);exit;
				//$GenerateQRCode	= $this->GenerateQRImage($rows_Trans->id,'QRCode',$Link_URL);
				//echo"<br>https://sentral.dutastudy.com/T.php?q=".$rows_Trans->id;
				//echo"<br>".$CodeHash;exit;
				if(file_exists($Path_PDF)){
					unset($Path_PDF);
				}
				
				## GENARATE PDF ##
				$File_PDF		= $this->GenerateQRFile($Code_Sentral);
				if(file_exists($Path_PDF)){
					chmod($Path_PDF, 0777);
				}
				
				
				$myurl 			= $Path_PDF.'[0]';
				$image 			= new Imagick();
				$image->setResolution( 300, 300 );
				$image->readImage($myurl);
				$image->setImageFormat( "jpeg" );
				$image->writeImage($this->file_location.'QRCode/'.$Name_File);
				$image->clear();
				$image->destroy();
				
				
				## HAPUS FILE PDF ##
				if(file_exists($Path_PDF)){
					unlink($Path_PDF);
				}
				
				## HAPUS FILE QR ##
				
				$File_Barcode	= $this->file_location.'QRCode/'.$Code_Sentral.'.png';
				if(file_exists($File_Barcode)){
					chmod($File_Barcode, 0777);
					unlink($File_Barcode);
				}
				
				
				$UPD_Detail		= array(
					'qr_code'	=> $Name_File
				);
				
				$Has_Upd_Detail	= $this->db->update('trans_data_details',$UPD_Detail,array('id'=>$Code_Sentral));
				$rows_Trans		= $this->db->get_where('trans_data_details',array('id'=>$Code_Sentral))->row();
			}else{
				$Name_File		= $File_QR;
			}
			
			$rows_Return	= array(
				'hasil'			=> 1,
				'pesan'			=> 'Berhasil',
				'path'			=> $this->file_attachement.'QRCode/'.$Name_File
			);
			
			echo json_encode($rows_Return);
			
		}		
	}
	
	function GenerateQRFile($Code=''){
		$rows_trans		= $this->db->get_where('trans_data_details',array('id'=>$Code))->row();
		$rows_header	= $this->db->get_where('sentral_customer_tools',array('sentral_tool_code'=>$rows_trans->sentral_code_tool))->row();
		$rows_tool		= $this->db->get_where('tools',array('id'=>$rows_header->tool_id))->row();
		
		$File_Path		= $this->file_location.'QRCode/'.$Code.'.pdf';
		
		$sroot = $_SERVER['DOCUMENT_ROOT'];
		include $sroot.'/Siscal_mobile/application/third_party/MPDF57/mpdf.php';
		$mpdf=new mPDF('utf-8', array(29,25));				// Create new mPDF Document
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
		$img_sentral	= $sroot.'/Siscal_Dashboard/assets/img/logo_flat.png';
		$img_kan		= $sroot.'/Siscal_Dashboard/assets/img/kan.png';
		//echo"<pre>";print_r($rows_header);exit;

		$HashKey		= '173ALIDYhG93b0qyJfIxfsdgfh2guVoUubW46hjwvniR200881173Gacad0FgaC9mi2008811M4ru5L1mChaeMo0';
		$CodeHash		= Enkripsi($rows_trans->id,$HashKey);
		$Link_URL		= 'https://sentral.dutastudy.com/Siscal_CRM/index.php/CertificateGenerate/CertificateAuthorized/'.$CodeHash;
		
		?>  

		<style type="text/css">
		@page {
			margin-top: 0.1cm;
			margin-left: 0.1cm;
			margin-right: 0.1cm;
			margin-bottom: 0.1cm;
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
			font-size:9px;
			padding: 2px;
			border-color: #666666;
		}

		table.noborder td {	
			padding: 1px;
			border-color: #666666;
			background-color: #ffffff;
			font-size:9px;
			font-family: verdana,arial,sans-serif;
		}

		table.gridtable {
			font-family: verdana,arial,sans-serif;
			font-size:10px;
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
			height: 14px; 
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
			margin: 1.5mm;
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
			left: 214px;
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
		.text-wrap{
			overflow-wrap: break-word !important;
			word-wrap: break-word !important;
			white-space: pre-wrap !important;
			word-break: break-word !important;
		}
		.text-center{
			text-align:center !important;
			vertical-align : middle !important;
		}
		.text-left{
			text-align:left !important;
			vertical-align : middle !important;
		}
		</style>
		<?php

		$Code_Trans		= $rows_trans->id;
		$Code_Serial	= $rows_trans->no_serial_number;
		$Code_Identify	= $rows_trans->no_identifikasi;
		$Text_Head		= $Code_Trans;
		if(!empty($Code_Identify) && $Code_Identify !== '-'){
			$Text_Head		= $Code_Identify;
		}

		if(!empty($Code_Serial) && $Code_Serial !== '-'){
			$Text_Head		= $Code_Serial;
		}

		$Font_Footer	= "8px";
		$Text_Footer	= date('d-m-Y',strtotime($rows_trans->datet));
		if(!empty($rows_trans->valid_until) && $rows_trans->valid_until !== '0000-00-00' && $rows_trans->valid_until !== '1970-01-01'){
			$Text_Footer	.=' sd '.date('d-m-Y',strtotime($rows_trans->valid_until));
			$Font_Footer	= "7px";
		}

		$rows_Image	= "";
		if(strtolower($rows_tool->certification_id) == 'kan'){
			$rows_Image	= "
			<tr>
				<td width='100%' class='text-center'>
					<img src='".$img_kan."' width='30' height='25'>
				</td>
			</tr>
			";
		}



		$Header	="
		<div style='border-width: 1px;border-color: #666666;border-style: solid;'>
			<table class='noborder' width='100%' height='100%'>
				<tr>
					<td width='100%' class='text-center text-wrap'>".$Text_Head."</td>
				</tr>
				<tr>
					<td width='100%' class='text-center'>
						<barcode code='".$Link_URL."' type='QR' size='0.5' error='L'/>
						
					</td>
				</tr>
				<tr>
					<td width='100%' class='text-center text-wrap' style='font-size:".$Font_Footer." !important;'>".$Text_Footer."</td>
				</tr>
			</table>	
		</div>
		";

		echo $Header;
			
		$html = ob_get_contents();
		ob_end_clean();
		//echo $html;exit;
		$mpdf->WriteHTML($html);
		$mpdf->Output($File_Path ,'F');
	}
	
	*/
	
	function print_barcode_nonQR_tool(){
		$rows_Sentral		= $rows_Tool = array();		
		if($this->input->post()){
			$Code_Sentral	= $this->input->post('code');
			$rows_Trans		= $this->db->get_where('trans_data_details',array('id'=>$Code_Sentral))->row();
			$rows_Sentral	= $this->db->get_where('sentral_customer_tools',array('sentral_tool_code'=>$rows_Trans->sentral_code_tool))->row();
			$rows_Tool		= $this->db->get_where('tools',array('id'=>$rows_Sentral->tool_id))->row();
			
			$File_QR		= $rows_Trans->qr_code;
			$Path_PDF		= $this->file_location.'QRCode/'.$Code_Sentral.'.pdf';
			$Name_File		= 'QR-'.$Code_Sentral.'.jpg';
			
					
			$HashKey		= '173ALIDYhG93b0qyJfIxfsdgfh2guVoUubW46hjwvniR200881173Gacad0FgaC9mi2008811M4ru5L1mChaeMo0';
			$CodeHash		= str_replace('=','',enkripsi_url($rows_Trans->id));
			$Link_URL		= 'https://sentral.dutastudy.com/Siscal_CRM/index.php/CertificateGenerate/CertificateAuthorized/'.$CodeHash;
			//echo $CodeHash.' '.dekripsi_url($CodeHash);exit;
			//$GenerateQRCode	= $this->GenerateQRImage($rows_Trans->id,'QRCode',$Link_URL);
			//echo"<br>https://sentral.dutastudy.com/T.php?q=".$rows_Trans->id;
			//echo"<br>".$CodeHash;exit;
			if(file_exists($Path_PDF)){
				unset($Path_PDF);
			}
			
			## GENARATE PDF ##
			$File_PDF		= $this->GenerateNonQRFile($Code_Sentral);
			if(file_exists($Path_PDF)){
				chmod($Path_PDF, 0777);
			}
			
			
			$myurl 			= $Path_PDF.'[0]';
			$image 			= new Imagick();
			$image->setResolution( 300, 300 );
			$image->readImage($myurl);
			$image->setImageFormat( "jpeg" );
			$image->writeImage($this->file_location.'QRCode/'.$Name_File);
			$image->clear();
			$image->destroy();
			
			
			## HAPUS FILE PDF ##
			
			if(file_exists($Path_PDF)){
				unlink($Path_PDF);
			}
			
			## HAPUS FILE QR ##
			
			$File_Barcode	= $this->file_location.'QRCode/'.$Code_Sentral.'.png';
			if(file_exists($File_Barcode)){
				chmod($File_Barcode, 0777);
				unlink($File_Barcode);
			}
			
			
			$UPD_Detail		= array(
				'qr_code'	=> $Name_File
			);
			
			$Has_Upd_Detail	= $this->db->update('trans_data_details',$UPD_Detail,array('id'=>$Code_Sentral));
			$rows_Trans		= $this->db->get_where('trans_data_details',array('id'=>$Code_Sentral))->row();
		
			
			$rows_Return	= array(
				'hasil'			=> 1,
				'pesan'			=> 'Berhasil',
				'path'			=> $this->file_attachement.'QRCode/'.$Name_File
			);
			
			echo json_encode($rows_Return);
			
		}		
	}
	function GenerateNonQRFile($Code=''){
		$rows_trans		= $this->db->get_where('trans_data_details',array('id'=>$Code))->row();
		$rows_header	= $this->db->get_where('sentral_customer_tools',array('sentral_tool_code'=>$rows_trans->sentral_code_tool))->row();
		$rows_tool		= $this->db->get_where('tools',array('id'=>$rows_trans->tool_id))->row();
		//echo"<pre>";print_r($rows_trans);
		$File_Path		= $this->file_location.'QRCode/'.$Code.'.pdf';
		
		$sroot = $_SERVER['DOCUMENT_ROOT'];
		include $sroot.'/Siscal_mobile/application/third_party/MPDF57/mpdf.php';
		$mpdf=new mPDF('utf-8', array(53,27));				// Create new mPDF Document
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
		$img_sentral	= $sroot.'/Siscal_Dashboard/assets/img/logo_flat.png';
		$img_kan		= $sroot.'/Siscal_Dashboard/assets/img/kan.png';
		//echo"<pre>";print_r($rows_header);exit;

		$HashKey		= '173ALIDYhG93b0qyJfIxfsdgfh2guVoUubW46hjwvniR200881173Gacad0FgaC9mi2008811M4ru5L1mChaeMo0';
		$CodeHash		= Enkripsi($rows_trans->id,$HashKey);
		$Link_URL		= 'https://sentral.dutastudy.com/Siscal_CRM/index.php/CertificateGenerate/CertificateAuthorized/'.$CodeHash;
		
		?>  

		<style type="text/css">
		@page {
			margin-top: 0.1cm;
			margin-left: 0.1cm;
			margin-right: 0.1cm;
			margin-bottom: 0.1cm;
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
			font-size:9px;
			padding: 2px;
			border-color: #666666;
		}

		table.noborder td {	
			padding: 1px;
			border-color: #666666;
			background-color: #ffffff;
			font-size:9px;
			font-family: verdana,arial,sans-serif;
		}

		table.gridtable {
			font-family: verdana,arial,sans-serif;
			font-size:10px;
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
			height: 14px; 
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
			margin: 1.5mm;
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
			left: 214px;
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
		.text-wrap{
			overflow-wrap: break-word !important;
			word-wrap: break-word !important;
			white-space: pre-wrap !important;
			word-break: break-word !important;
		}
		.text-center{
			text-align:center !important;
			vertical-align : middle !important;
		}
		.text-left{
			text-align:left !important;
			vertical-align : middle !important;
		}
		</style>
		<?php
		
		$Code_Trans		= trim($rows_trans->id);
		$Code_Serial	= trim($rows_trans->no_serial_number);
		$Code_Identify	= trim($rows_trans->no_identifikasi);
		$Text_Head		= $Code_Trans;
		$Text_Label		= 'S/N';
		if(!empty($Code_Identify) && $Code_Identify != '-'){
			$Text_Head		= $Code_Identify;
			$Text_Label		= 'ID';
		}

		if(!empty($Code_Serial) && $Code_Serial != '-'){
			$Text_Head		= $Code_Serial;
			$Text_Label		= 'ID';
		}

		$Font_Size		= "10px";
		$Cals_Date		= date('d-m-Y',strtotime($rows_trans->datet));
		$Extra_Text		= "<tr>
								<td colspan='3' height='1'>&nbsp;</td>
							</tr>";
		if(!empty($rows_trans->valid_until) && $rows_trans->valid_until != '0000-00-00' && $rows_trans->valid_until != '1970-01-01'){
			$Font_Size	= "9px";
			$Extra_Text	= "<tr>
							<td width='25%' class='text-center text-wrap' style='font-size:".$Font_Size." !important;'><b>Exp Date<br><p>&nbsp;</p></b></td>
							<td width='5%' class='text-center' style='font-size:".$Font_Size." !important;'>:<br><p>&nbsp;</p></td>
							<td class='text-left text-wrap' style='font-size:".$Font_Size." !important;'><b>".date('d-m-Y',strtotime($rows_trans->valid_until))."<br><p>&nbsp;</p></b></td>
						</tr>
						";
			
			
		}

		$rows_Image	= "";
		
		if(strtolower($rows_tool->certification_id) == 'kan'){
			$rows_Image	= "
				<td width='70%' class='text-center'>
					<img src='".$img_sentral."' width='100' height='25'>
				</td>
				<td width='30%' class='text-center'>
					<img src='".$img_kan."' width='30' height='25'>
				</td>
			";
		}else{
			$rows_Image	= "
				<td width='100%' class='text-center' colspan='2'>
					<img src='".$img_sentral."' width='33' height='14'>
				</td>		
			";
		}
		

		
	$Header	="
		<div style='border-width: 1px;border-color: #666666;border-style: solid;'>
			<table class='noborder' width='100%' height='100%' style='border-collapse: collapse !important;'>
				<tr>
					".$rows_Image."
				</tr>
			</table>
			
			<table width='100%' height='100%' style='border-collapse: collapse !important;font-family: verdana,arial,sans-serif;' class='noborder'>
				<tr>
					<td width='25%' class='text-center text-wrap' style='font-size:".$Font_Size." !important;'><b>".$Text_Label."</b></td>
					<td width='5%' class='text-center' style='font-size:".$Font_Size." !important;'>:</td>
					<td class='text-left text-wrap' style='font-size:".$Font_Size." !important;'><b>".((strlen($Text_Head) > 43)?substr($Text_Head,0,43):$Text_Head)."</b></td>
				</tr>
				<tr>
					<td width='25%' class='text-center text-wrap' style='font-size:".$Font_Size." !important;'><b>Cal Date</b></td>
					<td width='5%' class='text-center' style='font-size:".$Font_Size." !important;'>:</td>
					<td class='text-left text-wrap' style='font-size:".$Font_Size." !important;'><b>".$Cals_Date."</b></td>
				</tr>
				".$Extra_Text."
				
			</table>
			
		</div>
		";
		

		echo $Header;
			
		$html = ob_get_contents();
		ob_end_clean();
		//echo $html;exit;
		$mpdf->WriteHTML($html);
		$mpdf->Output($File_Path ,'F');
		
	}

	function print_barcode_new(){
		$rows_Sentral		= $rows_Tool = array();		
		if($this->input->post()){
			$Code_Sentral	= $this->input->post('code');
			$Code_Pengenal	= $this->input->post('pengenal');
			
			$rows_Trans		= $this->db->get_where('trans_data_details',array('id'=>$Code_Sentral))->row();
			$rows_Sentral		= $this->db->get_where('sentral_customer_tools',array('sentral_tool_code'=>$rows_Trans->sentral_code_tool))->row();
			$rows_Tool		= $this->db->get_where('tools',array('id'=>$rows_Sentral->tool_id))->row();
			
			$File_QR		= $rows_Trans->qr_code;
			$Path_PDF		= $this->file_location.'QRCode/'.$Code_Sentral.'.pdf';
			$Name_File		= 'QR-'.$Code_Sentral.'.jpg';
			
						
			$HashKey		= '173ALIDYhG93b0qyJfIxfsdgfh2guVoUubW46hjwvniR200881173Gacad0FgaC9mi2008811M4ru5L1mChaeMo0';
			$CodeHash		= str_replace('=','',enkripsi_url($rows_Trans->id));
			$Link_URL		= 'https://sentral.dutastudy.com/Siscal_CRM/index.php/CertificateGenerate/CertificateAuthorized/'.$CodeHash;
			//$Link_URL		= base_url().'Getqrtest/'.$rows_Trans->id; //QR ini harus disesuaikan urlnya
			
			//echo $CodeHash.' '.dekripsi_url($CodeHash);exit;
			//$GenerateQRCode	= $this->GenerateQRImage($rows_Trans->id,'QRCode',$Link_URL);
			$GenerateQRCode	= $this->GenerateQRNew($rows_Trans->id,'QRCode',$Link_URL);


			
			if(file_exists($Path_PDF)){
				unset($Path_PDF);
			}
			
			## GENARATE PDF ##
			$File_PDF		= $this->GenerateQRFileNew($Code_Sentral, $Code_Pengenal);
			if(file_exists($Path_PDF)){
				chmod($Path_PDF, 0777);
			}
			
			$image = new Imagick();
			$image->setResolution( 300, 300 );
			$myurl = $Path_PDF.'[0]';
			$image->readImage($myurl);
			//$image->scaleImage(800,0);
			//$image->setImageResolution(300, 300);
			$image->setImageFormat( "jpeg" );
			$image->setImageCompression(imagick::COMPRESSION_JPEG); 
			$image->setImageCompressionQuality(100);
			$image= $image->flattenImages();
			$image->writeImage($this->file_location.'QRCode/'.$Name_File);
			$image->clear();
			$image->destroy();
			
			
			## HAPUS FILE PDF ##
			if(file_exists($Path_PDF)){
				unlink($Path_PDF);
			}
			
			## HAPUS FILE QR ##
			
			$File_Barcode	= $this->file_location.'QRCode/img-'.$Code_Sentral.'.png';
			if(file_exists($File_Barcode)){
				chmod($File_Barcode, 0777);
				unlink($File_Barcode);
			}
			
			
			
			$UPD_Detail		= array(
				'qr_code'	=> $Name_File
			);
			
			$Has_Upd_Detail	= $this->db->update('trans_data_details',$UPD_Detail,array('id'=>$Code_Sentral));
			$rows_Trans		= $this->db->get_where('trans_data_details',array('id'=>$Code_Sentral))->row();
			
			$sroot = $_SERVER['DOCUMENT_ROOT'];
			$rows_Return	= array(
				'hasil'			=> 1,
				'pesan'			=> 'Berhasil '.$sroot,
				'path'			=> $this->file_attachement.'QRCode/'.$Name_File
				//'path'			=> $this->file_attachement.'QRCode/'.$Code_Sentral.'.pdf'
			);
			
			echo json_encode($rows_Return);
			
		}		
	}
	
	function GenerateQRNew($Nama_File ='',$Location='',$Link_URL=''){
		
		$File_Path	= $this->file_location.$Location.'/img-'.$Nama_File.'.png';
		if(file_exists($File_Path)) {
			unlink($File_Path);
		}


		$rows_Trans		= $this->db->get_where('trans_data_details',array('id'=>$Nama_File))->row();
		$rows_Tool		= $this->db->get_where('tools',array('id'=>$rows_Trans->tool_id))->row();
		
		if(strtolower($rows_Tool->certification_id) == 'kan'){
			$Logo_Path	= './assets/file/'.$Location.'/cals_kan.png';
		}else{
			$Logo_Path	= './assets/file/'.$Location.'/logo2.png';
		}
		
		$Label_Link	= $Link_URL;
		
		$qrCode = new QrCode($Label_Link);
		$qrCode->setSize(250);

		// Set advanced options
		$qrCode->setWriterByName('png');
		$qrCode->setMargin(5);
		$qrCode->setEncoding('UTF-8');
		$qrCode->setErrorCorrectionLevel(ErrorCorrectionLevel::HIGH);
		$qrCode->setForegroundColor(['r' => 0, 'g' => 0, 'b' => 0, 'a' => 0]);
		$qrCode->setBackgroundColor(['r' => 255, 'g' => 255, 'b' => 255, 'a' => 0]);
		//$qrCode->setLogoPath('./assets/img/sc_logo.png');
		//$qrCode->setLogoWidth(80);
		$qrCode->setValidateResult(false);

		// Directly output the QR code
		//header('Content-Type: '.$qrCode->getContentType());
		//echo $qrCode->writeString();

		// Save it to a file
		//$filename = time().'.png';
		
		if(strtolower($rows_Tool->certification_id) == 'kan'){
			//$qrCode->setLogoPath('assets/img/sc_logo.png');
			//$qrCode->setLogoWidth(80);
		}else{
			//imagecopyresampled($out, $logo, $QR_width/2.65, $QR_height/2.65, 0, 0, $QR_width/4, $QR_height/4, $newwidth, $newheight);
		}
		
		$qrCode->writeFile($File_Path);
		return $File_Path;
		
		
	}

	
	function GenerateQRFileNew($Code='', $Code_Pengenal=''){
		$rows_trans		= $this->db->get_where('trans_data_details',array('id'=>$Code))->row();
		$rows_header		= $this->db->get_where('sentral_customer_tools',array('sentral_tool_code'=>$rows_trans->sentral_code_tool))->row();
		$rows_tool		= $this->db->get_where('tools',array('id'=>$rows_header->tool_id))->row();
		
		$File_Path		= $this->file_location.'QRCode/'.$Code.'.pdf';
		
		$sroot = $_SERVER['DOCUMENT_ROOT'];
		include $sroot.'/Siscal_mobile/application/third_party/MPDF57/mpdf.php';
		//$mpdf=new mPDF('utf-8', array(29,50));
		//$mpdf=new mPDF('utf-8', array(43,24));				
		$mpdf=new mPDF('utf-8', array(300,150));

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
		$img_sentral	= $sroot.'/Siscal_Dashboard/assets/img/logo_flat.png';
		$img_kan		= $sroot.'/Siscal_Dashboard/assets/img/kan.png';
		//echo"<pre>";print_r($rows_header);exit;

		$HashKey		= '173ALIDYhG93b0qyJfIxfsdgfh2guVoUubW46hjwvniR200881173Gacad0FgaC9mi2008811M4ru5L1mChaeMo0';
		$CodeHash		= Enkripsi($rows_trans->id,$HashKey);
		$Link_URL		= 'https://sentral.dutastudy.com/Siscal_CRM/index.php/CertificateGenerate/CertificateAuthorized/'.$CodeHash;

		?>  

		<style type="text/css">
		@page {
			margin-top: 0.05cm;
			margin-left: 0.05cm;
			margin-right: 0.05cm;
			margin-bottom: 0.05cm;
		}		
		</style>

		<?php
		$Font_Footer	= $Font_Header = "8px";
		$Code_Trans		= $rows_trans->id;
		$Code_Serial	= $rows_trans->no_serial_number;
		$Code_Identify	= $rows_trans->no_identifikasi;
		//$Text_Head		= $Code_Trans;
		$Text_Head		= '';

		if($Code_Pengenal == "I"){
			if(!empty($Code_Identify) && $Code_Identify !== '-'){
				$Text_Head		= 'ID: '.$Code_Identify;
			}else{
				$Text_Head		= '-';
			}
		}elseif($Code_Pengenal == "S"){
			if(!empty($Code_Serial) && $Code_Serial !== '-'){
				$Text_Head		= 'SN: '.$Code_Serial;
			}else{
				$Text_Head		= '-';
			}
		}else{
			if(!empty($Code_Identify) && $Code_Identify !== '-'){
				$Text_Head		= 'ID: '.$Code_Identify;
			}elseif(!empty($Code_Serial) && $Code_Serial !== '-'){
				$Text_Head		= 'SN: '.$Code_Serial;
			}else{
				$Text_Head		= '-';
			}
		}

		if(strlen($Text_Head) > 20){
			//$Font_Header	= "6px";
		}

		$Logo_Path	= "";
		
		if(strtolower($rows_Tool->certification_id) != 'kan'){
			$Logo_Path	= '	<div style="position: fixed; top: 0.1px; left: 48%;">
								<img src="./assets/img/logo-sc.jpg" style="width: 70%">
							</div>
							<div style="position: fixed; top: 2px; left: 73%;">
								<img src="./assets/img/kan.png" style="width: 80%">
							</div>';
		}else{
			$Logo_Path	= '	<div style="position: fixed; top: 1px; left: 48%;">
								<!--<img src="./assets/img/logo-new.png" style="width: 32px;">-->
								<!--<img src="./assets/img/logo-sc.jpg" style="width: 32px;">-->
								<img src="./assets/img/logo-sc.jpg" style="width: 70%">
							</div>';
		}

		$Text_Footer	= "";
		if(!empty($rows_trans->valid_until) && $rows_trans->valid_until !== '0000-00-00' && $rows_trans->valid_until !== '1970-01-01'){
			$Text_Footer	='<div style="font-size: 60px;position: fixed; top: 30%; left: 50%;font-family: verdana,arial,sans-serif;"><b>'.date('d-m-Y',strtotime($rows_trans->datet)).' Sd/</b></div>
			<div style="font-size: 60px;position: fixed; top: 42%; left: 50%;font-family: verdana,arial,sans-serif;"><b>'.date('d-m-Y',strtotime($rows_trans->valid_until)).'</b></div>';
		}else{
			$Text_Footer	='<div style="font-size: 60px;position: fixed; top: 43%; left: 50%;font-family: verdana,arial,sans-serif;"><b>'.date('d-m-Y',strtotime($rows_trans->datet)).'<b></div>';
		}

		
		$Header = '
					<div style="position: fixed;left: 1px;">
						<img src="'.$this->file_location.'QRCode/img-'.$Code_Trans.'.png" style="width: 94%">
					</div>
					
					<div style="font-size: 38px;position: fixed; bottom: -4px; left: 1%;font-family: verdana,arial,sans-serif;"><b>www.sentralkalibrasi.co.id</b></div>

					'.$Logo_Path.' '.$Text_Footer.'

					<div style="font-size: 50px;position: fixed; top: 53%; left: 50%;font-family: verdana,arial,sans-serif;"><hr style="height:6px;margin: 15px; width: 97%"/><b>'.$Text_Head.'</b></div>

				';

		echo $Header;
			
		$html = ob_get_contents();
		ob_end_clean();
		//echo $html;exit;
		$mpdf->WriteHTML($html);
		$mpdf->Output($File_Path ,'F');
	}
	
	function downloadQRBatch($Code_SO='', $flagPengenalBatch=''){

		$Query_Data		= "SELECT
								det_tool.*,
								head_tool.labs,
								head_tool.insitu,
								head_tool.subcon,
								head_tool.location,
								head_tool.so_descr,
								head_tool.range,
								head_tool.piece_id,
								head_tool.quotation_detail_id
						FROM
							trans_data_details det_tool
							INNER JOIN trans_details head_tool ON head_tool.id = det_tool.trans_detail_id
						WHERE det_tool.flag_proses = 'Y' AND head_tool.letter_order_id = '".$Code_SO."'";
		$rows_Trans	= $this->db->query($Query_Data)->result();
		
		$OK_Proses = 0;
		if($rows_Trans){
			$OK_Proses = 1;
		}

		$this->load->library("PHPExcel");
		$excel	= new PHPExcel();

		$excel->getProperties()->setCreator('SISCAL')
		->setLastModifiedBy('SISCAL')
		->setTitle("Data Kalibrasi")
		->setSubject("SISCAL")
		->setDescription("List Data Kalibrasi")
		->setKeywords("Data Kalibrasi");

		$style_col = array(
		'font' => array('bold' => true),
		'alignment' => array(
		'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
		'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER
		),
		'borders' => array(
		'top' => array('style'  => PHPExcel_Style_Border::BORDER_THIN),
		'right' => array('style'  => PHPExcel_Style_Border::BORDER_THIN),
		'bottom' => array('style'  => PHPExcel_Style_Border::BORDER_THIN),
		'left' => array('style'  => PHPExcel_Style_Border::BORDER_THIN)
		)
		);
		
		$style_row = array(
		'alignment' => array(
		'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER
		),
		
		'borders' => array(
		'top' => array('style'  => PHPExcel_Style_Border::BORDER_THIN),
		'right' => array('style'  => PHPExcel_Style_Border::BORDER_THIN),
		'bottom' => array('style'  => PHPExcel_Style_Border::BORDER_THIN),
		'left' => array('style'  => PHPExcel_Style_Border::BORDER_THIN)
		)
		);

		$excel->setActiveSheetIndex(0)->setCellValue('A1', "Tool Name");
		$excel->setActiveSheetIndex(0)->setCellValue('B1', "Valid Date");
		$excel->setActiveSheetIndex(0)->setCellValue('C1', "Id/Serial No");
		$excel->setActiveSheetIndex(0)->setCellValue('D1', "QRCode");

		$excel->getActiveSheet()->getStyle('A1')->applyFromArray($style_col);
		$excel->getActiveSheet()->getStyle('B1')->applyFromArray($style_col);
		$excel->getActiveSheet()->getStyle('C1')->applyFromArray($style_col);
		$excel->getActiveSheet()->getStyle('D1')->applyFromArray($style_col);

		$siswa = $rows_Trans;
		$no = 1;
		$numrow = 2; 
		foreach($siswa as $ketK=>$data){
		
		$CodeHash	= str_replace('=','',enkripsi_url($data->id));
		$QR			= 'https://sentral.dutastudy.com/Siscal_CRM/index.php/CertificateGenerate/CertificateAuthorized/'.$CodeHash;

		if(!empty($data->valid_until) && $data->valid_until !== '0000-00-00' && $data->valid_until !== '1970-01-01'){
			$date	= date('d-m-Y',strtotime($data->datet)).' Sd/ '.date('d-m-Y',strtotime($data->valid_until));
		}else{
			$date	= date('d-m-Y',strtotime($data->datet));
		}

		$codeToolsID		= "";

		if($flagPengenalBatch == "I"){
			if(!empty($data->no_identifikasi) && $data->no_identifikasi !== '-'){
				$codeToolsID		= 'ID: '.$data->no_identifikasi;
			}else{
				$codeToolsID		= "-";
			}
		}elseif($flagPengenalBatch == "S"){
			if(!empty($data->no_serial_number) && $data->no_serial_number !== '-'){
				$codeToolsID		= 'SN: '.$data->no_serial_number;
			}else{
				$codeToolsID		= "-";
			}
		}else{
			if(!empty($data->no_identifikasi) && $data->no_identifikasi !== '-'){
				$codeToolsID		= 'ID: '.$data->no_identifikasi;
			}elseif(!empty($data->no_serial_number) && $data->no_serial_number !== '-'){
				$codeToolsID		= 'SN: '.$data->no_serial_number;
			}else{
				$codeToolsID		= "-";
			}
		}

		$excel->setActiveSheetIndex(0)->setCellValue('A'.$numrow, $data->tool_name);
		$excel->setActiveSheetIndex(0)->setCellValue('B'.$numrow, $date);
		$excel->setActiveSheetIndex(0)->setCellValue('C'.$numrow, $codeToolsID, PHPExcel_Cell_DataType::TYPE_STRING);
		$excel->setActiveSheetIndex(0)->setCellValue('D'.$numrow, $QR);

		$excel->getActiveSheet()->getStyle('A'.$numrow)->applyFromArray($style_row);
		$excel->getActiveSheet()->getStyle('B'.$numrow)->applyFromArray($style_row);
		$excel->getActiveSheet()->getStyle('C'.$numrow)->applyFromArray($style_row);
		$excel->getActiveSheet()->getStyle('D'.$numrow)->applyFromArray($style_row);
		
		$no++;
		$numrow++;
		}

		$excel->getActiveSheet()->getColumnDimension('A')->setWidth(35);
		$excel->getActiveSheet()->getColumnDimension('B')->setWidth(25);
		$excel->getActiveSheet()->getColumnDimension('C')->setWidth(25);
		$excel->getActiveSheet()->getColumnDimension('D')->setWidth(155);

		$excel->getActiveSheet()->getDefaultRowDimension()->setRowHeight(-1);
		$excel->getActiveSheet()->getPageSetup()->setOrientation(PHPExcel_Worksheet_PageSetup::ORIENTATION_LANDSCAPE);
		$excel->getActiveSheet(0)->setTitle("Detail Tools");
		$excel->setActiveSheetIndex(0);

		$file_name = 'SISCAL QRcode Tools  - '.$Code_SO;   

		$objWriter = PHPExcel_IOFactory::createWriter($excel, 'Excel5');
		ob_end_clean();
		header("Last-Modified: " . gmdate("D, d M Y") . " GMT");
		header("Cache-Control: no-store, no-cache, must-revalidate");
		header("Cache-Control: post-check=0, pre-check=0", false);
		header("Pragma: no-cache");
		header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
		header('Content-Disposition: attachment;filename="'.$file_name.'.xls"');
		$objWriter->save("php://output");
	}
}
