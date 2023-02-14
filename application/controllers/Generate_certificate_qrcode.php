<?php
defined('BASEPATH') OR exit('No direct script access allowed');
use setasign\Fpdi\Fpdi;
class Generate_certificate_qrcode extends CI_Controller { 
	public function __construct(){
        parent::__construct();	
		
		$this->load->database();
        if(!$this->session->userdata('isSISCALlogin')){
			redirect('login');
		}
		$this->load->model('master_model');
		$controller			= ucfirst(strtolower($this->uri->segment(1)));
		$this->Arr_Akses	= getAcccesmenu($controller);
		
		$this->folder		= 'Bast_Certificate';
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
			'title'			=> 'GENERATE QRCODE TOOL',
			'action'		=> 'index',
			'akses_menu'	=> $Arr_Akses
		);
		history('View List Generate QRCODE Certificate');
		$this->load->view($this->folder.'/v_generate_qr_code',$data);
	}
	function get_data_display(){
		$Arr_Akses			= $this->Arr_Akses;
		
		$WHERE				= "detail.flag_proses = 'Y'
								AND (
									detail.no_sertifikat IS NULL
									OR detail.no_sertifikat = ''
									OR detail.no_sertifikat = '-'
								)";
		
		$requestData	= $_REQUEST;
		
		$like_value     = $requestData['search']['value'];
        $column_order   = $requestData['order'][0]['column'];
        $column_dir     = $requestData['order'][0]['dir'];
        $limit_start    = $requestData['start'];
        $limit_length   = $requestData['length'];
		
		$columns_order_by = array(
			0 => 'header.no_so',
			1 => 'header.tgl_so',
			2 => 'header.customer_name',
			3 => 'header.quotation_nomor',
			4 => 'header.pono'
		);
		
		
		
		if($like_value){
			if(!empty($WHERE))$WHERE	.=" AND ";
			$WHERE	.="(
						  header.no_so LIKE '%".$this->db->escape_like_str($like_value)."%'
						  OR DATE_FORMAT(header.tgl_so, '%d %b %Y') LIKE '%".$this->db->escape_like_str($like_value)."%'
						  OR header.customer_name LIKE '%".$this->db->escape_like_str($like_value)."%'
						  OR header.quotation_nomor LIKE '%".$this->db->escape_like_str($like_value)."%'
						  OR header.pono LIKE '%".$this->db->escape_like_str($like_value)."%'
						)";
		}
		
		
		$sql = "SELECT
					header.letter_order_id,
					header.tgl_so,
					header.no_so,
					header.quotation_id,
					header.quotation_nomor,
					header.quotation_date,
					header.pono,
					header.customer_id,
					header.customer_name,
					SUM(
						CASE
						WHEN detail.no_sertifikat IS NULL
						OR detail.no_sertifikat = ''
						OR detail.no_sertifikat = '-' THEN
							1
						ELSE
							0
						END
					) AS tot_sertifikat,
					(@row:=@row+1) AS urut
				FROM
					trans_data_details detail
				INNER JOIN trans_details header ON detail.trans_detail_id = header.id,
				(SELECT @row:=0) r 
				WHERE ".$WHERE."
				GROUP BY
					header.letter_order_id";
		//print_r($sql);exit();
		$fetch['totalData'] 	= $this->db->query($sql)->num_rows();
		$fetch['totalFiltered']	= $this->db->query($sql)->num_rows();

		

		$sql .= " ORDER BY header.tgl_so DESC,".$columns_order_by[$column_order]." ".$column_dir." ";
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
			$Date_SO		= date('d-m-Y',strtotime($row['tgl_so']));
			$Customer		= $row['customer_name'];
			$Code_Cust		= $row['customer_id'];
			$Code_Quot		= $row['quotation_id'];
			$Nomor_Quot		= $row['quotation_nomor'];
			$Nomor_PO		= $row['pono'];
			$Date_Quot		= date('d-m-Y',strtotime($row['quotation_date']));
			$Total			= number_format($row['tot_sertifikat']);
			
			
			
			$Template		="<a href='".site_url('Generate_certificate_qrcode/view_detail_tool?noso='.urlencode($Code_SO))."' class='btn btn-sm bg-navy-active' title='VIEW DETAIL'> <i class='fa fa-long-arrow-right'></i> </a>";
			
			
			$nestedData		= array();
			$nestedData[]	= $Nomor_SO;
			$nestedData[]	= $Date_SO;
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
	
	function view_detail_tool(){
		$rows_header	= $rows_detail = $rows_SO = array();
		if($this->input->get()){
			$Code_SO		= urldecode($this->input->get('noso'));
			$rows_SO		= $this->db->get_where('letter_orders',array('id'=>$Code_SO))->result();
			$rows_header	= $this->db->get_where('trans_details',array('letter_order_id'=>$Code_SO))->result();
			$Qry_Detail		= "SELECT
									det_tool.*
								FROM
									trans_data_details det_tool
								INNER JOIN trans_details head_tool ON det_tool.trans_detail_id = head_tool.id
								WHERE
									head_tool.letter_order_id = '".$Code_SO."'
								/* AND det_tool.flag_proses = 'Y' */";
			$rows_detail	= $this->db->query($Qry_Detail)->result();
		}
		$Arr_Akses			= $this->Arr_Akses;
		$data = array(
			'title'			=> 'DETAIL TOOLS',
			'action'		=> 'view_detail_tool',
			'akses_menu'	=> $Arr_Akses,
			'rows_header'	=> $rows_header,
			'rows_detail'	=> $rows_detail,
			'rows_so'		=> $rows_SO
		);
		
		$this->load->view($this->folder.'/v_generate_qr_code_preview',$data);
	}
	
	function generate_qrcode_tool(){
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
			'title'			=> 'GENERATE QR CODE CERTIFICATE',
			'action'		=> 'generate_qrcode_tool',
			'akses_menu'	=> $Arr_Akses,
			'rows_detail'	=> $rows_Detail,
			'rows_header'	=> $rows_Header,
			'Code_Back'		=> $Code_Back
		);
		
		$this->load->view($this->folder.'/v_generate_qr_code_process',$data);
	}
	
	
	

	
	function save_generate_qrcode_sertifikat(){
		$rows_Return	= array(
			'status'		=> 2,
			'pesan'			=> 'No Record was found...'
		);
		if($this->input->post()){
			//echo"<pre>";print_r($this->input->post());exit;
			
			//echo"<pre>";print_r($this->input->post());exit;
			$Created_By		= $this->session->userdata('siscal_userid');
			$Created_Date	= date('Y-m-d H:i:s');
			
			$Code_Trans		= $this->input->post('code_detail');
			$Code_SO		= $this->input->post('code_so');
			$Reason_Open	= $this->input->post('reopen_reason');
			
			$rows_Find		= $this->db->get_where('trans_data_details',array('id'=>$Code_Trans))->result();
			if($rows_Find[0]->no_sertifikat !== '' && $rows_Find[0]->no_sertifikat !== '-' && !empty($rows_Find[0]->no_sertifikat)){
				$rows_Return	= array(
					'status'		=> 2,
					'pesan'			=> 'Data has been modified by other process...'
				);
			}else{
				
				$Type_File			= '';
				$Data_File			= array();
				$Nama_File			= '';
				$Path_Source		= './assets/file/sertifikat/';
				$Path_Destination	= 'sertifikat';
				
				//print_r($_SERVER['DOCUMENT_ROOT']);
				//exit;
				
				/* -------------------------------------------------------------
				|  UPLOAD FILE BASED ON PILIHAN FILE
				| ---------------------------------------------------------------
				*/
				
				$OK_Upload			= $OK_Selfie = 0;
				$Nama_File			= $Code_Trans.'.pdf';
				$New_File			= $Code_Trans.'_new.pdf';
				if($_FILES && isset($_FILES['lampiran_reopen']['name']) && $_FILES['lampiran_reopen']['name'] != ''){
					$nama_image 	= $_FILES['lampiran_reopen']['name'];
					$type_iamge		= $_FILES['lampiran_reopen']['type'];
					$tmp_image 		= $_FILES['lampiran_reopen']['tmp_name'];
					$error_image	= $_FILES['lampiran_reopen']['error'];
					$size_image 	= $_FILES['lampiran_reopen']['size'];
					
					$cekExtensi 	= strtolower(getExtension($nama_image));
					$Nama_File		= $Code_Trans.'.'.$cekExtensi;
					$New_File		= $Code_Trans.'_new.'.$cekExtensi;
					$Type_File		= $cekExtensi;
					
					$Pesan_Error	= '';
					if($error_image == '1'){
						$OK_Proses		= 0;
						$Pesan_Error	= 'File Size Exceeds The Maximum Limit';
						
					}else{
						if (file_exists($Path_Source.$Nama_File)) {
							unlink($Path_Source.$Nama_File);
						}
						
						move_uploaded_file($tmp_image, $Path_Source.$Nama_File);
					}					
				}
				
				## GENERATE QR CODE ##
				$HashKey		= '173ALIDYhG93b0qyJfIxfsdgfh2guVoUubW46hjwvniR200881173Gacad0FgaC9mi2008811M4ru5L1mChaeMo0';
				$CodeHash		= $Code_Trans;
				
				
				$Link_URL		= 'https://sentral.dutastudy.com/Siscal_CRM/index.php/CertificateGenerate/CertificateAuthorized/'.$CodeHash;
				$GenerateQRCode	= $this->GenerateQRImage($CodeHash,'QRCode',$Link_URL);
				
				$sroot 				= $_SERVER['DOCUMENT_ROOT'];
				$Location_QrCode	= './assets/file/QRCode/'.$CodeHash.'.png';
				
				$PathFileLama		= $Path_Source.$Nama_File;
				$PathFileNew		= $Path_Source.$Code_Trans.'.png';
				
				
				
				## TEXT IMAGICK DULU ##
				
				## PROSES INSERT GAMBAR ##
				require_once('vendor/autoload.php');
				
				
				// initiate FPDI
				$pdf = new Fpdi();
				// add a page
				
				// set the source file
				$Jumlah_Pagi = $pdf->setSourceFile($Path_Source.$Nama_File);
				$IntL        = 0;
				for($x=1;$x<=$Jumlah_Pagi;$x++){
					$pdf->AddPage('P','A4');
					$tplId 	= $pdf->importPage($x);
					$size 	= $pdf->getTemplateSize($tplId);
					$pdf->useImportedPage($tplId, null, null, $size['width'], $size['height'],FALSE);
					if($x==1){
						$Text_Ind	= 'Harap periksa keaslian sertfikat anda di';
						$Link_Ind	= 'https://sentral.dutastudy.com/Siscal_CRM';
						$Text_Qr	= 'Pindai QR Code untuk tautan unduh sertifikat';
						$Text_End	= 'Please Check your certificate autenticity on';
						$Text_Qr_En	= 'Scan the QR Code for certificate download link';
						
						
						$pdf->SetFont('Times','',8);
						//$pdf->SetTextColor(255, 0, 0);
						$pdf->SetXY(19, 160);
						$pdf->Write(0, $Text_Ind);
						
						$pdf->SetXY(19, 164);
						$pdf->Write(0, $Link_Ind);
						
						$pdf->SetXY(19, 168);
						$pdf->Write(0, $Text_Qr);	
						
						$pdf->SetFont('Times','I',8);
						$pdf->SetXY(19, 172);						
						$pdf->Write(0, $Text_End);
						
						$pdf->SetXY(19, 176);
						$pdf->Write(0, $Link_Ind);
						
						$pdf->SetXY(19, 180);
						$pdf->Write(0, $Text_Qr_En);
						
						$pdf->SetFont('Times','',10);
						//$pdf->SetTextColor(255, 0, 0);
						$pdf->SetXY(19, 185);
						$pdf->Write(0, $Code_Trans);
						
						//$pdf->Image($Location_QrCode,20,187,30,30);
					}
				}
				// import page 1
				
				// use the imported page and place it at point 10,10 with a width of 100 mm
				
				//$pdf->useTemplate($tplId, 10, 10, 100);
				
				
				
				$pdf->Output($Path_Source.$New_File, "F");
				unlink($Path_Source.$Nama_File);
				unlink($Location_QrCode);
				echo"<pre>";print_r($Jumlah_Pagi);
				
				$rows_Return		= array(
					'status'		=> 1,
					'pesan'			=> 'Save process success. Thank you & have a nice day......',
					'file'			=> $New_File
				);				
			}
			
			
			
		}
		echo json_encode($rows_Return);
	}
	
	function GenerateQRImage($Nama_File ='',$Location='',$Link_URL=''){
		$sroot = $_SERVER['DOCUMENT_ROOT'];
		include $sroot.'/Siscal_Dashboard/application/libraries/phpqrcode/qrlib.php';
		//$this->load->library('phpqrcode/qrlib');
		
		$File_Path	= './assets/file/'.$Location.'/'.$Nama_File.'.png';
		if (file_exists($File_Path)) {
			unlink($File_Path);
		}
		
		$Logo_Path	= './assets/file/'.$Location.'/logo.png';		
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
		imagecopyresampled($out, $logo, $QR_width/2.65, $QR_height/2.65, 0, 0, $QR_width/4, $QR_height/4, $newwidth, $newheight);
		
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
	
	
	
}