<?php
defined('BASEPATH') OR exit('No direct script access allowed');
date_default_timezone_set('Asia/Jakarta');

class Toprint extends CI_Controller {
	function __construct(){
		parent::__construct();
		if(!$this->session->userdata('isSISCALlogin')){
			redirect('login');
		}

		$this->load->model('Sertifikat/M_toprint', 'toprint');

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
			'title'			=> 'ToPrint Sertifikat',
			'action'		=> 'Index',
			'row'			=> $comp_Data,
			'akses_menu'	=> $Arr_Akses
		);
		history($sessionGet.' View Data Selia Sertifikat');
		$this->load->view($this->folder.'/vw_toprint',$data);
	}

		
	function list_func_toprint()
	{
			$list = $this->toprint->get_datatables();
			$data = array();
			$no = $_POST['start'];
			foreach ($list as $item) {
				$no++; 
				$row = array();

				$controller			= ucfirst(strtolower($this->uri->segment(1)));
				$Arr_Akses			= getAcccesmenu($controller);

				$queryLate 	= "SELECT * FROM trans_data_detail_cals_file_log 
							where trans_data_detail_id = '".$item->id."' AND status_selia = 'SELESAI' 
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
				$row[] = $item->nama;
				$row[] = '<span class="badge bg-red">'.$lateday.'</span><p style="font-size:10px;">Tgl: '.$item->datet.'</p>';

				if($Arr_Akses['create'] =='1'){
					$row[] = '<a href="'.site_url('selia/downloadbyName?getFile='.$item->file_kalibrasi.'&'.'setName='.$renameFile).'" class="btn btn-sm btn-success" style="border-radius:25%;" target="_blank"><i class="fa fa-download"></i></a>
					<a href="javascript:void(0)" onClick="toprintData(' . "'" . $item->id . "'" . ');" class="btn btn-sm btn-warning" style="border-radius:25%;"><i class="fa fa-pencil"></i></a>';
				}else{
					$row[] = '<a class="btn btn-sm btn-success" href="javascript:void(0)" title="Sorry!" disabled><i class="fa fa-eye"></i></a>';
				}
				
				$data[] = $row;
			}

			$output = array(
				"draw" => $_POST['draw'],
				"recordsTotal" => $this->toprint->count_all(),
				"recordsFiltered" => $this->toprint->count_filtered(),
				"data" => $data,
			);
			
			echo json_encode($output);
		
	}

	function getById($id)
    {
        $data = $this->toprint->get_by_id($id);
        echo json_encode($data);
    }
	
	function update_func_toprint(){
		$sessionGet 	= $this->session->userdata('siscal_username');
		$id				= $this->input->post('id');
		$status_selia	= "PRINT";
		$catatan_mt		= $this->input->post('catatan_mt');
		$Created_By		= $this->session->userdata('siscal_userid');
		$Created_Date	= date('Y-m-d H:i:s');

		$this->db->trans_begin();

		$queryLog 	= "SELECT * FROM trans_data_detail_cals_file_log 
					where trans_data_detail_id = '".$id."' 
					ORDER BY id DESC LIMIT 1";
		$rowsLog	= $this->db->query($queryLog)->result_array();

		$Ins_Log		= array(
			'trans_data_detail_id'	=> $id,
			'file_kalibrasi'		=> $rowsLog[0]['file_kalibrasi'],
			'file_type'				=> $rowsLog[0]['file_type'],
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

		$UPD_Detail			= array(
			'modified_by'		=> $Created_By,
			'modified_date'		=> $Created_Date,
			'status_selia'		=> $status_selia
		);

		$Has_Upd_Trans		= $this->db->update('trans_data_details',$UPD_Detail,array('id'=>$id));
		if($Has_Upd_Trans !== TRUE){
			$Pesan_Error	= 'Error Update Trans Data Details';
		}

		if ($this->db->trans_status() != TRUE || !empty($Pesan_Error)){
			$this->db->trans_rollback();
			$result['status'] 	= false;
			$result['msg'] 		= 'Data gagal diupdate!';
			history($sessionGet.' Update ToPrint Process Code '.$id.' - '.$Pesan_Error);
		}else{
			$this->db->trans_commit();
			$result['status'] 	= true;
			$result['msg'] 		= 'Data berhasil diupdate!';
			history($sessionGet.' Update ToPrint Process Code '.$id);
		}
        echo json_encode($result);
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

}
