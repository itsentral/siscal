<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Reopen_certificate extends CI_Controller { 
	public function __construct(){
        parent::__construct();	
		
		$this->load->database();
        if(!$this->session->userdata('isSISCALlogin')){
			redirect('login');
		}
		$this->load->model('master_model');
		$controller			= ucfirst(strtolower($this->uri->segment(1)));
		$this->Arr_Akses	= getAcccesmenu($controller);
		
		$this->folder	= 'Bast_Certificate';
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
			'title'			=> 'Manage Certificates',
			'action'		=> 'index',
			'akses_menu'	=> $Arr_Akses
		);
		history('View List Certificates');
		$this->load->view($this->folder.'/v_reopen_certificate',$data);
	}
	function get_data_display(){
		$Arr_Akses			= $this->Arr_Akses;
		
		$DateFr				= $this->input->post('datefr');
		$DateTl				= $this->input->post('datetl');
		
		$WHERE				= "detail_trans.approve_certificate = 'APV'";
		if(!empty($DateFr) && !empty($DateTl)){
			if(!empty($WHERE))$WHERE	.=" AND ";
			$WHERE	.="(head_trans.tgl_so BETWEEN '".$DateFr."' AND '".$DateTl."')";
		}
		
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
			3 => 'head_trans.marketing_name',
			4 => 'head_trans.quotation_nomor',
			5 => 'head_trans.pono'
		);
		
		
		
		if($like_value){
			if(!empty($WHERE))$WHERE	.=" AND ";
			$WHERE	.="(
						  head_trans.no_so LIKE '%".$this->db->escape_like_str($like_value)."%'
						  OR DATE_FORMAT(head_trans.tgl_so, '%d %b %Y') LIKE '%".$this->db->escape_like_str($like_value)."%'
						  OR head_trans.customer_name LIKE '%".$this->db->escape_like_str($like_value)."%'
						  OR head_trans.marketing_name LIKE '%".$this->db->escape_like_str($like_value)."%'
						  OR head_trans.quotation_nomor LIKE '%".$this->db->escape_like_str($like_value)."%'
						  OR head_trans.pono LIKE '%".$this->db->escape_like_str($like_value)."%'
						)";
		}
		
		
		$sql = "SELECT
					head_trans.letter_order_id,
					head_trans.no_so,
					head_trans.tgl_so,
					head_trans.quotation_id,
					head_trans.quotation_date,
					head_trans.quotation_nomor,
					head_trans.pono,
					head_trans.podate,
					head_trans.customer_id,
					head_trans.customer_name,
					head_trans.marketing_id,
					head_trans.marketing_name,
					head_trans.schedule_id,
					head_trans.schedule_nomor,
					head_trans.schedule_date,
					(@row:=@row+1) AS urut
				FROM
					trans_details head_trans
					INNER JOIN trans_data_details detail_trans ON head_trans.id = detail_trans.trans_detail_id,
				(SELECT @row:=0) r 
				WHERE ".$WHERE."
				GROUP BY
					head_trans.letter_order_id
				";
		//print_r($sql);exit();
		$fetch['totalData'] 	= $this->db->query($sql)->num_rows();
		$fetch['totalFiltered']	= $this->db->query($sql)->num_rows();

		

		$sql .= " ORDER BY head_trans.tgl_so DESC,".$columns_order_by[$column_order]." ".$column_dir." ";
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
			$nestedData		=array();
			$intL			= 0;
            foreach($columns_order_by as $keyI=>$valI){
				$intL++;
				$Pecah_Kode		= explode('.',$valI);
				$Field_Cari		= $Pecah_Kode[1];
				$Nilai_Data		= $row[$Field_Cari];
				
				
				if($intL === 2){
					if(!empty($Nilai_Data) && $Nilai_Data !== '-'){
						$Nilai_Data	= date('d-m-Y',strtotime($Nilai_Data));
					}					
				}
				
				$nestedData[] = $Nilai_Data;
			}  
			$Template		="<a href='".site_url('Reopen_certificate/view_detail?noso='.urlencode($Code_SO))."' class='btn btn-sm bg-navy-active' title='DETAIL SERVICE ORDER'> <i class='fa fa-search'></i> </a>";
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
		$rows_header	= array();
		if($this->input->get()){
			$Code_SO	= urldecode($this->input->get('noso'));
			$rows_header	= $this->db->get_where('letter_orders',array('id'=>$Code_SO))->result();
		}
		$Arr_Akses			= $this->Arr_Akses;
		$data = array(
			'title'			=> 'DETAIL SERVICE ORDER',
			'action'		=> 'view_detail',
			'akses_menu'	=> $Arr_Akses,
			'rows_header'	=> $rows_header
		);
		
		$this->load->view($this->folder.'/v_reopen_certificate_detail',$data);
	}
	
	function view_detail_sertifikat(){
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
			'title'			=> 'DETAIL CERTIFICATE TOOL',
			'action'		=> 'view_detail_sertifikat',
			'akses_menu'	=> $Arr_Akses,
			'rows_detail'	=> $rows_Detail,
			'rows_header'	=> $rows_Header,
			'Code_Back'	=> $Code_Back
		);
		
		$this->load->view($this->folder.'/v_reopen_certificate_tool',$data);
		
	}
	
	function view_open_sertifikat(){
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
			'title'			=> 'REOPEN TOOL CERTIFICATE',
			'action'		=> 'view_open_sertifikat',
			'akses_menu'	=> $Arr_Akses,
			'rows_detail'	=> $rows_Detail,
			'rows_header'	=> $rows_Header,
			'Code_Back'		=> $Code_Back
		);
		
		$this->load->view($this->folder.'/v_reopen_certificate_process',$data);
	}
	
	function save_reopen_sertifikat(){
		$rows_Return	= array(
			'status'		=> 2,
			'pesan'			=> 'No Record was found...'
		);
		if($this->input->post()){
			//echo"<pre>";print_r($this->input->post());exit;
			$Created_By		= $this->session->userdata('siscal_userid');
			$Created_Date	= date('Y-m-d H:i:s');
			
			$Code_Trans		= $this->input->post('code_detail');
			$Code_SO		= $this->input->post('code_so');
			$Reason_Open	= $this->input->post('reopen_reason');
			
			$rows_Find		= $this->db->get_where('trans_data_details',array('id'=>$Code_Trans))->result();
			if($rows_Find[0]->approve_certificate !== 'APV'){
				$rows_Return	= array(
					'status'		=> 2,
					'pesan'			=> 'Data has been modified by other process...'
				);
			}else{
				
				$Type_File			= '';
				$Data_File			= array();
				$Nama_File			= '';
				$Path_Source		= './assets/file/';
				$Path_Destination	= 'sertifikat';
				
				//print_r($_SERVER['DOCUMENT_ROOT']);
				//exit;
				
				/* -------------------------------------------------------------
				|  UPLOAD FILE BASED ON PILIHAN FILE
				| ---------------------------------------------------------------
				*/
				$File_Old			= $Code_Trans.'-'.date('YmdHis').'.'.$rows_Find[0]->file_type;
				$Rename_File		= rename($this->file_location.$Path_Destination.'/'.$rows_Find[0]->file_name, $this->file_location.$Path_Destination.'/'.$File_Old);
				$OK_Upload			= $OK_Selfie = 0;
				if($_FILES && isset($_FILES['lampiran_reopen']['name']) && $_FILES['lampiran_reopen']['name'] != ''){
					$nama_image 	= $_FILES['lampiran_reopen']['name'];
					$type_iamge		= $_FILES['lampiran_reopen']['type'];
					$tmp_image 		= $_FILES['lampiran_reopen']['tmp_name'];
					$error_image	= $_FILES['lampiran_reopen']['error'];
					$size_image 	= $_FILES['lampiran_reopen']['size'];
					
					$cekExtensi 	= strtolower(getExtension($nama_image));
					$Nama_File		= $Code_Trans.'.'.$cekExtensi;
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
						if(strtolower($Type_File) == 'pdf'){
							$Has_Upload = PdfUpload_Kalibrasi($Data_File,$Path_Destination,$Code_Trans);
						}else{
							$Has_Upload = ImageResizes_Kalibrasi($Data_File,$Path_Destination, $Code_Trans);
						}
						
					}					
				}
				
				$Ins_Log			= array(
					'trans_data_detail_id'		=> $Code_Trans,
					'no_sertifikat'				=> $rows_Find[0]->no_sertifikat,
					'valid_until'				=> $rows_Find[0]->valid_until,
					'file_name'					=> $File_Old,
					'file_type'					=> $rows_Find[0]->file_type,
					'approve_by'				=> $rows_Find[0]->approve_by,
					'approve_date'				=> $rows_Find[0]->approve_date,
					'reopen_reason'				=> $Reason_Open,
					'reopen_by'					=> $Created_By,
					'reopen_date'				=> $Created_Date
				);
				
				$Upd_Header			= array(
					'approve_certificate'	=> 'OPN',
					'flag_send'				=> 'N',
					'file_name'				=> $Nama_File,
					'file_type'				=> $Type_File
				);
				
				$this->db->trans_begin();
				
				$Has_Upd_Trans		= $this->db->update('trans_data_details',$Upd_Header,array('id'=>$Code_Trans));
				if($Has_Upd_Trans !== TRUE){
					$Pesan_Error	= 'Error Update Trans Data Details';
				}
				
				$Has_Ins_Log		= $this->db->insert('trans_data_detail_certificate_reopen',$Ins_Log);
				if($Has_Ins_Log !== TRUE){
					$Pesan_Error	= 'Error Insert Trans Data Detail Certificate Reopen';
				}
				
				if ($this->db->trans_status() != TRUE || !empty($Pesan_Error)){
					$this->db->trans_rollback();
					$rows_Return		= array(
						'status'		=> 2,
						'pesan'			=> 'Save Process  Failed, please try again...'
					);
					history('Reopen Certificate Tools Code '.$Code_Trans.' - '.$Pesan_Error);
				}else{
					$this->db->trans_commit();
					$rows_Return		= array(
						'status'		=> 1,
						'pesan'			=> 'Save process success. Thank you & have a nice day......'
					);
					history('Reaopen Certificate Tools Code '.$Code_Trans);
				}
				
			}
		}
		echo json_encode($rows_Return);
	}
	
	
	
}