<?php
defined('BASEPATH') OR exit('No direct script access allowed');
date_default_timezone_set('Asia/Jakarta');

class Selia extends CI_Controller {
	function __construct(){
		parent::__construct();
		if(!$this->session->userdata('isSISCALlogin')){
			redirect('login');
		}

		$this->load->model('Sertifikat/M_selia', 'selia');

		$this->folder	='Sertifikat/';
		$this->file_attachement	= $this->config->item('link_file');
		$this->file_loc			= $this->config->item('location_file');
	}

	public function index()
	{
		$sessionGet 		= $this->session->userdata('siscal_username');
		$controller			= ucfirst(strtolower($this->uri->segment(1)));
		$Arr_Akses			= getAcccesmenu($controller);

		if($Arr_Akses['read'] !='1'){
			$this->session->set_flashdata("alert_data", "<div class=\"alert alert-warning\" id=\"flash-message\">You Don't Have Right To Access This Page, Please Contact Your Administrator....</div>");
			redirect(site_url('dashboard'));
		}
		
		$comp_Data			= $this->db->get('groups')->result_array();
		
		$data = array(
			'title'			=> 'Selia Sertifikat',
			'action'		=> 'Index',
			'row'			=> $comp_Data,
			'akses_menu'	=> $Arr_Akses
		);
		history($sessionGet.' View Data Selia Sertifikat');
		$this->load->view($this->folder.'/vw_selia',$data);
	}

		
	function list_func_selia()
	{
			$list = $this->selia->get_datatables();
			$data = array();
			$no = $_POST['start'];
			foreach ($list as $item) {
				$no++; 
				$row = array();

				$controller			= ucfirst(strtolower($this->uri->segment(1)));
				$Arr_Akses			= getAcccesmenu($controller);

				$queryLate 	= "SELECT * FROM trans_data_detail_cals_file_log 
							where trans_data_detail_id = '".$item->id."' AND status_selia = 'PENDING' 
							ORDER BY id DESC LIMIT 1";
				$rowsLate	= $this->db->query($queryLate)->result_array();

				$t = date_create($rowsLate[0]['created_date']);
				$n = date_create();
				$terlambat = date_diff($t, $n);
				$lateday = $terlambat->d.' hari';

				$id_sn = '';
				if($item->no_identifikasi == '' || $item->no_identifikasi == '0'){
					$id_sn = 'SN('.$item->no_serial_number.')';
				}elseif($item->no_serial_number == '' || $item->no_serial_number == '0'){
					$id_sn = 'ID('.$item->no_identifikasi.')';
				}else{
					$id_sn = 'SN('.$item->no_serial_number.')__ID('.$item->no_identifikasi.')';
				}

				$renameFile = strtoupper($item->actual_teknisi_name).'__'.$item->customer_name.'__'.$item->tool_name.'__'.$id_sn;

				$row[] = '<input type="checkbox" id="checkID_' . $item->id . '" name="checkID[]" value="' . $item->file_kalibrasi . '">';
				$row[] = $item->id;
				$row[] = $item->customer_name;
				$row[] = $item->address_so;
				$row[] = $item->no_so;
				$row[] = $item->tool_name;
				$row[] = $item->no_identifikasi;
				$row[] = $item->no_serial_number;
				$row[] = $item->actual_teknisi_name;
				//$row[] = '<a href="#" href="javascript:void(0)" onClick="viewDetail(' . "'" . $item->id . "'" . ');" class="btn"><i class="fa fa-eye"></i> <b>View Detail</b></a>';
				$row[] = '<span class="badge bg-red">'.$lateday.'</span><p style="font-size:10px;">Tgl: '.$item->datet.'</p>';
				
				if($Arr_Akses['create'] =='1'){
					// $row[] = '<a href="'.$this->file_attachement.'hasil_kalibrasi/'.$item->file_kalibrasi.'" class="btn btn-sm btn-success" style="border-radius:25%;" target="_blank"><i class="fa fa-download"></i></a>
					// <a href="javascript:void(0)" onClick="seliaData(' . "'" . $item->id . "'" . ');" class="btn btn-sm btn-warning" style="border-radius:25%;"><i class="fa fa-pencil"></i></a>';

					$row[] = '<a href="'.site_url('selia/downloadbyName?getFile='.$item->file_kalibrasi.'&'.'setName='.$renameFile).'" class="btn btn-sm btn-success" style="border-radius:25%;" target="_blank"><i class="fa fa-download"></i></a>
					<a href="javascript:void(0)" onClick="seliaData(' . "'" . $item->id . "'" . ');" class="btn btn-sm btn-warning" style="border-radius:25%;"><i class="fa fa-pencil"></i></a>';
				}else{
					$row[] = '<a class="btn btn-sm btn-success" href="javascript:void(0)" title="Sorry!" disabled><i class="fa fa-eye"></i></a>';
				}
				
				$data[] = $row;
			}

			$output = array(
				"draw" => $_POST['draw'],
				"recordsTotal" => $this->selia->count_all(),
				"recordsFiltered" => $this->selia->count_filtered(),
				"data" => $data,
			);
			
			echo json_encode($output);
		
	}

	function getById($id)
    {
        $data = $this->selia->get_by_id($id);
        echo json_encode($data);
    }
	
	function update_func_selia(){
		$sessionGet 	= $this->session->userdata('siscal_username');
		$id				= $this->input->post('id');
		$status_selia	= $this->input->post('status_selia');
		$catatan_mt		= $this->input->post('catatan_mt');
		$Created_By		= $this->session->userdata('siscal_userid');
		$Created_Date	= date('Y-m-d H:i:s');
		$rows_Find		= $this->db->get_where('trans_data_details',array('id'=>$id))->row();

		$this->db->trans_begin();

		$queryLog 	= "SELECT * FROM trans_data_detail_cals_file_log 
					where trans_data_detail_id = '".$id."' 
					ORDER BY id DESC LIMIT 1";
		$rowsLog	= $this->db->query($queryLog)->result_array();

		$Old_File		= $rows_Find->file_kalibrasi;
		$Old_File_Tipe	= $rows_Find->file_kalibrasi_tipe;

		$Rename_File	= $id.'-'.date('YmdHis').'.'.$Old_File_Tipe;

		$Ins_Log		= array(
			'trans_data_detail_id'	=> $id,
			'file_kalibrasi'		=> $Rename_File,
			'file_type'				=> $Old_File_Tipe,
			'reopen_reason'			=> $rowsLog[0]['reopen_reason'],
			'reopen_by'				=> $rowsLog[0]['reopen_by'],
			'reopen_date'			=> $rowsLog[0]['reopen_date'],
			'id_selia'				=> $Created_By,
			'status_selia'			=> $status_selia,
			'catatan_mt'			=> $catatan_mt,
			'created_date'			=> $Created_Date
		);

		$Has_Ins_Log	= $this->db->insert('trans_data_detail_cals_file_log',$Ins_Log);
		if($Has_Ins_Log !== TRUE){
			$Pesan_Error	= 'Error Insert Log Calibration File';
		}

		## RENAME FILE LAMA ##
		rename($this->file_loc.'hasil_kalibrasi/'.$Old_File,$this->file_loc.'hasil_kalibrasi/'.$Rename_File);

		$UPD_Detail			= array(
			'modified_by'		=> $Created_By,
			'modified_date'		=> $Created_Date,
			'status_selia'		=> $status_selia
		);

		$Type_File			= '';
		$Data_File			= array();
		$Nama_File			= '';
		$Path_Source		= './assets/file/';
		$Path_Destination	= 'hasil_kalibrasi';

		/* -------------------------------------------------------------
		|  UPLOAD FILE BASED ON PILIHAN FILE
		| ---------------------------------------------------------------
		*/
		
		$OK_Upload			= $OK_Selfie = 0;
		if($_FILES && isset($_FILES['file_selia_1']['name']) && $_FILES['file_selia_1']['name'] != ''){
			$nama_image 	= $_FILES['file_selia_1']['name'];
			$type_iamge		= $_FILES['file_selia_1']['type'];
			$tmp_image 		= $_FILES['file_selia_1']['tmp_name'];
			$error_image	= $_FILES['file_selia_1']['error'];
			$size_image 	= $_FILES['file_selia_1']['size'];
			
			$cekExtensi 	= strtolower(getExtension($nama_image));
			$Nama_File		= $id.'.'.$cekExtensi;
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
				$Has_Upload 	= PdfUpload_Kalibrasi($Data_File,$Path_Destination,$id);
				
				$UPD_Detail['file_kalibrasi']		= $Nama_File;
				$UPD_Detail['file_kalibrasi_tipe']	= $Type_File;
				
			}					
		}

		$Has_Upd_Trans		= $this->db->update('trans_data_details',$UPD_Detail,array('id'=>$id));
		if($Has_Upd_Trans !== TRUE){
			$Pesan_Error	= 'Error Update Trans Data Details';
		}

		if ($this->db->trans_status() != TRUE || !empty($Pesan_Error)){
			$this->db->trans_rollback();
			$result['status'] 	= false;
			$result['msg'] 		= 'Data gagal diupdate!';
			history($sessionGet.' Update Selia Process Code '.$id.' - '.$Pesan_Error);
		}else{
			$this->db->trans_commit();
			$result['status'] 	= true;
			$result['msg'] 		= 'Data berhasil diupdate!';

			//FUNGSI NOTIF TO TEKNISI
			if($status_selia == "REVISI"){

			}else{

			}

			history($sessionGet.' Update Selia Process Code '.$id);
		}
        echo json_encode($result);
    }

	function upload_seliaBatch(){
		$sessionGet 	= $this->session->userdata('siscal_username');
		$status_selia	= "SELESAI";
		$catatan_mt		= "UPDATE FILE CALIBRATIONS BATCH";
		$Created_By		= $this->session->userdata('siscal_userid');
		$Created_Date	= date('Y-m-d H:i:s');
		$jumlahFile 	= count($_FILES['file_selia_batch']['name']);

		//$this->load->library('upload');

		$rows_Find 		= [];
		$filenameSelia 	= [];
		$cekExtensi 	= [];
		$cekFile 		= '';
		$Pesan_Error	= '';

		$this->db->trans_begin();

		for($i=0;$i<$jumlahFile;$i++){
			if(!empty($_FILES['file_selia_batch']['name'][$i])){

				$nama_image 	= $_FILES['file_selia_batch']['name'][$i];
				$type_iamge		= $_FILES['file_selia_batch']['type'][$i];
				$tmp_image 		= $_FILES['file_selia_batch']['tmp_name'][$i];
				$error_image 	= $_FILES['file_selia_batch']['error'][$i];
				$size_image 	= $_FILES['file_selia_batch']['size'][$i];
				$cekExtensi[] 	= strtolower(getExtension($nama_image));
				$filenameSelia[]= pathinfo($nama_image, PATHINFO_FILENAME);
				
				//var_dump($filenameSelia);
				//die();

				$update_trans 	= [];
				$insert_log 	= [];
				$Rename_File	= [];

				//Sebelum foreach disini dikasih if dulu yang ada idnya di table trans data detail
				//fungsi if cek id yang dibawah ntar di cek dulu bener ga
				// $rows_Find	= $this->db->get_where('trans_data_details',array('id'=>$filenameSelia,'status_selia'=>'PENDING'))->row();
				// $cekID[]	= $rows_Find['id'][$i]; 

				// var_dump($cekID);
				// die();
				// exit();

				// if($cekID  == $filenameSelia){

				// }

				foreach($filenameSelia as $keycals => $val){
					$update_trans[] = [
						'id' 					=> $val,
						'modified_by'			=> $Created_By,
						'modified_date'			=> $Created_Date,
						'status_selia'			=> $status_selia,
						'file_kalibrasi'		=> $filenameSelia[$keycals].'.'.$cekExtensi[$keycals],
						'file_kalibrasi_tipe'	=> $cekExtensi[$keycals]
					];

					$Rename_File	= $filenameSelia[$keycals].'-'.date('YmdHis').'.'.$cekExtensi[$keycals];

					
					$insert_log[] 	= [
						'trans_data_detail_id'	=> $filenameSelia[$keycals],
						'file_kalibrasi'		=> $Rename_File,
						'file_type'				=> $cekExtensi[$keycals],
						'reopen_reason'			=> null,
						'reopen_by'				=> null,
						'reopen_date'			=> null,
						'id_selia'				=> $Created_By,
						'status_selia'			=> $status_selia,
						'catatan_mt'			=> $catatan_mt,
						'created_date'			=> $Created_Date
					];

				}
				
			}

			if (file_exists($this->file_loc.'hasil_kalibrasi/'.$nama_image)) {
				unlink($this->file_loc.'hasil_kalibrasi/'.$nama_image);
			}

			if(move_uploaded_file($tmp_image, $this->file_loc.'hasil_kalibrasi/'.$nama_image)){
				$cekFile = 'YES';
			}else{
				$cekFile = 'NOT';
			}

		}

		//$uploadSelia = $this->func_upload_seliaBatch('file_selia_batch');

		$Has_Upd_Trans = $this->db->update_batch('trans_data_details',$update_trans,'id');
		$Has_Ins_Log	= $this->db->insert_batch('trans_data_detail_cals_file_log',$insert_log);

		//if($uploadSelia != "NOT"){
		if($cekFile == 'YES'){
			if($Has_Upd_Trans == TRUE && $Has_Ins_Log == TRUE){
				$Pesan_Error	= '';
			}else{
				$Pesan_Error	= 'Error Update Trans Details OR Insert Log Calibration File ';
			}
		}else{
			$Pesan_Error	= "Upload File Gagal!";
		}
		
		
		if ($this->db->trans_status() == TRUE && empty($Pesan_Error)){
			$this->db->trans_commit();
			$result['status'] 	= true;
			$result['msg'] 		= 'Data Berhasil diupdate!';
			history($sessionGet.' Update Selia Process Batch');
			//echo 'bisa-'.$Pesan_Error;
		}else{
			$this->db->trans_rollback();
			$result['status'] 	= false;
			$result['msg'] 		= $Pesan_Error;
			history($sessionGet.' Update Selia Process Batch - '.$Pesan_Error);
			//echo 'gabisa-'.$Pesan_Error;
		}
		
		echo json_encode($result);

	}

	public function func_upload_seliaBatch($file)
	{
		$strInputFileName = $file;
		$arrFiles = $_FILES;
		//$new_file = '';
		$dir_path	= $this->file_loc.'hasil_kalibrasi/';

		if (!is_dir($dir_path)) {
            mkdir($dir_path, 0777, TRUE);
        }
		
		$result	= 'NOT';

		if (is_array($_FILES[$strInputFileName]['name']))
		{
			$countFiles = count($_FILES[$strInputFileName]['name']);
			for($i=0;$i<$countFiles; $i++)
			{
				//overwrite _FILES array
				$_FILES[$strInputFileName]['name'] = $arrFiles[$strInputFileName]['name'][$i];
				$_FILES[$strInputFileName]['type'] = $arrFiles[$strInputFileName]['type'][$i];
				$_FILES[$strInputFileName]['tmp_name'] = $arrFiles[$strInputFileName]['tmp_name'][$i];
				$_FILES[$strInputFileName]['error'] = $arrFiles[$strInputFileName]['error'][$i];
				$_FILES[$strInputFileName]['size'] = $arrFiles[$strInputFileName]['size'][$i];
				
				$tmpFilePath = $arrFiles[$strInputFileName]['tmp_name'][$i];

				$newFilePath = $dir_path . $arrFiles[$strInputFileName]['name'][$i];

				if (file_exists($newFilePath)) {
					unlink($newFilePath);
				}

				if(move_uploaded_file($tmpFilePath, $newFilePath)) 
				{
					$result = 'YES';
				} 
				else 
				{
					$result = 'NOT';
					break; 
				}
			}
		}
		else
		{
			$result = 'NOT';
		}
		return $result;
	}

	function downloadFile(){
		$sessionGet 	= $this->session->userdata('siscal_username');

		if($this->input->get()){  
			$Code_Selected	= urldecode($this->input->get('checkID'));
			$CodeSlct		= str_replace("^", ",", $Code_Selected);
			$List 			= explode(',', $CodeSlct);
			
			if($List){
				$uploaddir 		= $this->file_loc.'hasil_kalibrasi/';
		
				$Nama_Folder	= 'File-Selia_'.$sessionGet.'_'.date('d-m-Y');
				$Folder_ZIP		= './hasil_kalibrasi/'.$Nama_Folder;
				
				$Nama_ZIP		= $Nama_Folder.'.zip';
				$Dir_ZIP		= './hasil_kalibrasi/'.$Nama_Folder.'.zip';
				$bad 			= array_merge(array_map('chr', range(0,31)), array("<", ">", ":", '"', "/", "\\", "|", "?", "*"));
				
				@unlink($Dir_ZIP);
				
				mkdir($Folder_ZIP, 0777, true);
				chmod($Folder_ZIP,0777);
				
				
				
				$DeleteFile	= array();
				foreach($List as $filename){
					$File_Detail	= $filename;
					
					copy($uploaddir.$File_Detail,$Folder_ZIP.'/'.$File_Detail);
					chmod($Folder_ZIP.'/'.$File_Detail,0777);
					$DeleteFile[] = $File_Detail;
					
				}
				$rootPath = realpath($Folder_ZIP);
				
				$zip = new \ZipArchive();
				$zip->open($Dir_ZIP, ZipArchive::CREATE | ZipArchive::OVERWRITE);
				
				/** @var SplFileInfo[] $files */
				$files = new RecursiveIteratorIterator(
					new RecursiveDirectoryIterator($rootPath),
					RecursiveIteratorIterator::LEAVES_ONLY
				);
				
				
				foreach ($files as $name => $file)
				{
					// if (!$file->isDir())
					// {
					// 	$filePath 		= $file->getRealPath();
					// 	$relativePath 	= substr($filePath, strlen($rootPath) + 1);
					// 	$zip->addFile($filePath, $relativePath);
						
					// }

					if (!$file->isDir())
					{
						$filePath 		= $file->getRealPath();
						$relativePath 	= substr($filePath, strlen($rootPath) + 1);
						$GetIDFile		= substr($relativePath , 0, (strlen($relativePath ))-(strlen(strrchr($relativePath, '.'))));
						$rows_File		= $this->db->select('trans_data_details.actual_teknisi_name, trans_details.customer_name,
												trans_data_details.tool_name, trans_data_details.no_identifikasi,
												trans_data_details.no_serial_number')
										->from('trans_data_details')
										->join('trans_details', 'trans_data_details.trans_detail_id = trans_details.id')
										->where('trans_data_details.id', $GetIDFile)->get()->row();

						$id_sn = '';
						if($rows_File->no_identifikasi == '' || $rows_File->no_identifikasi == '0'){
							$id_sn = 'SN('.$rows_File->no_serial_number.')';
						}elseif($rows_File->no_serial_number == '' || $rows_File->no_serial_number == '0'){
							$id_sn = 'ID('.$rows_File->no_identifikasi.')';
						}else{
							$id_sn = 'SN('.$rows_File->no_serial_number.')_ID('.$rows_File->no_identifikasi.')';
						}

						$renameFilecs		= str_replace($bad, '_', strtoupper($rows_File->actual_teknisi_name).'_'.$rows_File->customer_name.'_'.$rows_File->tool_name.'_'.$id_sn);
						$zip->addFile($filePath, $renameFilecs.'_'.$relativePath);
						
					}

				}

				$zip->close();

				header('Content-Description: File Transfer');
				header('Content-Type: application/octet-stream');
				header('Content-Disposition: attachment; filename='.basename($Dir_ZIP));
				header('Content-Transfer-Encoding: binary');
				header('Expires: 0');
				header('Cache-Control: must-revalidate');
				header('Pragma: public');
				header('Content-Length: ' . filesize($Dir_ZIP));
				readfile($Dir_ZIP);
				
				if($DeleteFile){
					foreach($DeleteFile as $keyDel=>$valDel){
						unlink($Folder_ZIP.'/'.$valDel);
					}
				}
				
				rmdir($Folder_ZIP);
				@unlink($Dir_ZIP);	
					
				
			}else{
				$this->session->set_userdata('notif_gagal', 'No record was found...');
				redirect('Selia');
			}
		}else{
			$this->session->set_userdata('notif_gagal', 'Incorrect link....');
            redirect('Selia');
		}
		
		
	}

	function downloadbyName(){
		$path			= $this->file_loc.'hasil_kalibrasi/';
		$file_name 		= $path.$_GET['getFile'];
		$new_filename 	= $_GET['setName'].'__'.$_GET['getFile'];

		//chmod($file_name,0777);

		$mime = 'application/force-download';
		header('Pragma: public');    
		header('Expires: 0');        
		header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
		header('Cache-Control: private',false);
		header('Content-Type: '.$mime);
		header('Content-Disposition: filename="'.$new_filename.'"');
		header('Content-Transfer-Encoding: binary');
		header('Connection: close');
		readfile($file_name);    
		exit();

	}
}
