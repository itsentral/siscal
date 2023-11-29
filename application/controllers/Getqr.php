<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH.'third_party/endroid_qrcode/autoload.php';
use Endroid\QrCode\ErrorCorrectionLevel;
//use Endroid\QrCode\LabelAlignment;
use Endroid\QrCode\QrCode;
//use Endroid\QrCode\Response\QrCodeResponse;

use setasign\Fpdi\Fpdi;

class Getqr extends CI_Controller {
	function __construct(){
		parent::__construct();

		$this->load->model('master_model');
		//$this->file_attachement	= $this->config->item('link_file');
		//$this->file_location	= $this->config->item('location_file');
		
	}

	public function index(){
		$data['title'] = "TEST QR";
		$data['action'] = "TEST";
		$this->load->view('testqr', $data);
	}

	public function getQRcalresult($id)
	{
		$HashKey		= '173ALIDYhG93b0qyJfIxfsdgfh2guVoUubW46hjwvniR200881173Gacad0FgaC9mi2008811M4ru5L1mChaeMo0';
		$CodeHash		= enkripsi_url($id);
		$Link_URL		= 'https://sentral.dutastudy.com/Siscal_CRM/index.php/CertificateGenerate/CertificateAuthorized/'.$CodeHash;
		redirect($Link_URL);
	}

	function print_barcode_calibration_new(){
		$CLR 				= $this->uri->segment(1);
		$rows_Sentral		= $rows_Tool = array();		

		if($this->input->post()){
			$Code_Sentral	= $this->input->post('code');
			
			$rows_Trans		= $this->db->get_where('trans_data_details',array('id'=>$Code_Sentral))->row();
			$rows_Sentral	= $this->db->get_where('sentral_customer_tools',array('sentral_tool_code'=>$rows_Trans->sentral_code_tool))->row();
			$rows_Tool		= $this->db->get_where('tools',array('id'=>$rows_Sentral->tool_id))->row();
			
			$File_QR		= $rows_Trans->qr_code;
			$datet			= $rows_Trans->datet;
			$Path_PDF		= $this->file_location.'QRCode/'.$Code_Sentral.'.pdf';
			$Name_File		= 'QR-'.$Code_Sentral.'.png';

			// $Text_date	= date('d-m-Y',strtotime($rows_trans->datet));
			// if(!empty($rows_trans->valid_until) && $rows_trans->valid_until !== '0000-00-00' && $rows_trans->valid_until !== '1970-01-01'){
			// 	$Text_date	.= .' sd'.date('d-m-Y',strtotime($rows_trans->valid_until));
			// 	//$Font_Footer	= "6px";
			// }
	
			$Link_URL		= base_url().'Getqr/'.$rows_Trans->id;
			

			$GenerateQRCode	= $this->GenerateQR($rows_Trans->id,'QRCode',$Link_URL);
			
			
			$UPD_Detail		= array(
				'qr_code'	=> $Name_File
			);
			
			//$Has_Upd_Detail	= $this->db->update('trans_data_details',$UPD_Detail,array('id'=>$Code_Sentral));
			$rows_Trans		= $this->db->get_where('trans_data_details',array('id'=>$Code_Sentral))->row();
			
			
			$sroot = $_SERVER['DOCUMENT_ROOT'];
			include $sroot.'/Siscal_mobile/application/third_party/MPDF57/mpdf.php';
			// include $sroot.'/codeigniter3/sentral/Siscal_mobile/application/third_party/MPDF57/mpdf.php';

			//ini ukuran kertas 24mm
			// $mpdf=new mPDF('utf-8', array(70,24));		
			$mpdf=new mPDF('utf-8', array(43,24));		
			
			
			$File_Path		= 'assets/img/file/QRCode/QR-'.$Code_Sentral.'.pdf';
			ob_start();

			?>

				<style type="text/css">
					@page {
						margin-top: 0.1cm;
						margin-left: 0.2cm;
						margin-right: 0.2cm;
						margin-bottom: 0.1cm;
					}
				</style>
				
			<?php

			if(strtolower($rows_Tool->certification_id) == 'kan'){
				$Logo_Path	= '	<div style="position: fixed; top: 2px; left: 80px;">
									<img src="'.base_url().'assets/img/logo-sc.jpg'.'" style="width: 38px;">
								</div>
								<div style="position: fixed; top: 2px; left: 115px;">
									<img src="'.base_url().'assets/img/kan.png'.'" style="width: 28px;">
								</div>';
			}else{
				$Logo_Path	= '	<div style="position: fixed; top: 2px; left: 80px;">
									<img src="'.base_url().'assets/img/logo-sc.jpg'.'" style="width: 38px;">
								</div>';
			}

			$header = '
						<div style="position: fixed;left: 1px;">
							<img src="'.base_url().'assets/img/QRCode/'.$Code_Sentral.'.png" style="width: 77px;">
						</div>
						
						<div style="font-size: 8px;position: fixed; top: 75px; left: 6.2px;font-family: verdana,arial,sans-serif;">'.$Code_Sentral.'</div>

						'.$Logo_Path.'

						<div style="font-size: 10px;position: fixed; top: 37px; left: 84px;font-family: verdana,arial,sans-serif;">'.$datet.'</div>

						<!--<div style="font-size: 8px;position: fixed; top: 47px; left: 84px;font-family: verdana,arial,sans-serif;">'.$datet.' Sd/</div>
						<div style="font-size: 8px;position: fixed; top: 57px; left: 84px;font-family: verdana,arial,sans-serif;">'.$datet.'</div> INI KALAU ADA SAMPAI DENGAN VALIDNYA-->

						<div style="font-size: 5px;position: fixed; top: 68px; left: 84px;font-family: verdana,arial,sans-serif;">www.sentralkalibrasi.co.id</div>

					';
			
			echo $header;

			$html = ob_get_contents();
			ob_end_clean();
			$mpdf->WriteHTML($html);
			$mpdf->Output($File_Path ,'F');

			$myurl 			= $Path_PDF.'[0]';
			$image 			= new Imagick();
			$image->setResolution( 300, 300 );
			$image->readImage($myurl);
			$image->setImageFormat( "jpeg" );
			$image->writeImage($this->file_location.'QRCode/'.$Name_File);
			$image->clear();
			$image->destroy();
			
			$rows_Return	= array(
				'hasil'			=> 1,
				'pesan'			=> 'Berhasil',
				'path'			=> $this->file_attachement.'QRCode/'.$Name_File
			);
			
			echo json_encode($rows_Return);
			
		}		
	}

	function GenerateQR($Nama_File ='',$Location='',$Link_URL=''){
		
		$File_Path	= $this->file_location.$Location.'/'.$Nama_File.'.png';

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
		$qrCode->setMargin(10);
		$qrCode->setEncoding('UTF-8');
		$qrCode->setErrorCorrectionLevel(ErrorCorrectionLevel::HIGH);
		$qrCode->setForegroundColor(['r' => 0, 'g' => 0, 'b' => 0, 'a' => 0]);
		$qrCode->setBackgroundColor(['r' => 255, 'g' => 255, 'b' => 255, 'a' => 0]);
		//$qrCode->setLogoPath('assets/img/sc_logo.png');
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

}
