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

				$row[] = $item->id;
				$row[] = $item->no_so;
				$row[] = $item->tool_name;
				$row[] = $item->no_identifikasi;
				$row[] = $item->no_serial_number;
				$row[] = '<a href="#" href="javascript:void(0)" onClick="viewAddress(' . "'" . $item->id . "'" . ');" class="btn"><i class="fa fa-eye"></i> <b>Lihat Alamat</b></a>';
				//$row[] = '<span class="badge bg-red">'.$item->status_selia.'</span>';
				$row[] = '<span class="badge bg-red">'.$lateday.'</span>';
				
				if($Arr_Akses['create'] =='1'){
					$row[] = '
					<div class="btn-group">
						<button type="button" class="btn btn-warning btn-sm dropdown-toggle" data-toggle="dropdown" aria-expanded="true"><text style="font-weight: 600;letter-spacing: 0.3px;">Actions </text>
							<span class="fa fa-caret-down"></span>
						</button>

						<ul class="dropdown-menu pull-right">
						<li><a href="'.$this->file_attachement.'hasil_kalibrasi/'.$item->file_kalibrasi.'" target="_blank"><i class="fa fa-download"></i>&nbsp; Unduh File</a></li>
						<li><a href="javascript:void(0)" onClick="seliaData(' . "'" . $item->id . "'" . ');"><i class="fa fa-pencil"></i>&nbsp; Update Data</a></li>
						</ul>
				  	</div>';

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
}
