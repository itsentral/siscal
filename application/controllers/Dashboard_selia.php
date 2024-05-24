<?php
defined('BASEPATH') OR exit('No direct script access allowed');
date_default_timezone_set('Asia/Jakarta');

class Dashboard_selia extends CI_Controller {
	function __construct(){
		parent::__construct();
		if(!$this->session->userdata('isSISCALlogin')){
			redirect('login');
		}

		$this->load->model('Sertifikat/M_dashboard_selia', 'Dashboardselia');

		$this->folder	='view_dashboard/';
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
			'title'			=> 'Dashboard Selia (Proses DEV)',
			'action'		=> 'Index',
			'row'			=> $comp_Data,
			'countPending'	=> $this->Dashboardselia->count_all_pending(),
			'countRevisi'	=> $this->Dashboardselia->count_all_revisi(),
			'countPrint'	=> $this->Dashboardselia->count_all_print(),
			'countSelesai'	=> $this->Dashboardselia->count_all_selesai(),
			'akses_menu'	=> $Arr_Akses
		);
		history($sessionGet.' View Data Selia Sertifikat');
		$this->load->view($this->folder.'/dashboard_selia',$data);
	}

	function list_func_selia()
	{
			$list = $this->Dashboardselia->get_datatables();
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

				$row[] = $item->id;
				$row[] = $item->customer_name;
				//$row[] = $item->address_so;
				$row[] = $item->no_so;
				$row[] = $item->tool_name;
				$row[] = $item->no_identifikasi;
				$row[] = $item->no_serial_number;
				$row[] = $item->actual_teknisi_name;
				$row[] = '<span class="badge bg-red">'.$lateday.'</span><p style="font-size:10px;">Tgl: '.$item->datet.'</p>';
				
				if($Arr_Akses['download'] =='1'){
					//$row[] = '<a href="'.site_url('selia/downloadbyName?getFile='.$item->file_kalibrasi.'&'.'setName='.$renameFile).'" class="btn btn-sm btn-success" style="border-radius:25%;" target="_blank"><i class="fa fa-download fa-sm"></i></a>';
					$row[] = '<a href="'.$this->file_attachement.'hasil_kalibrasi/'.$item->file_kalibrasi.'" class="btn btn-sm btn-success" style="border-radius:25%;" target="_blank"><i class="fa fa-download"></i></a>';
				}else{
					$row[] = '<a class="btn btn-sm btn-success" href="javascript:void(0)" title="Sorry!" style="border-radius:25%;" disabled><i class="fa fa-download"></i></a>';
				}
				
				$data[] = $row;
			}

			$output = array(
				"draw" => $_POST['draw'],
				"recordsTotal" => $this->Dashboardselia->count_all(),
				"recordsFiltered" => $this->Dashboardselia->count_filtered(),
				"data" => $data,
			);
			
			echo json_encode($output);
		
	}

	function getById($id)
    {
        $data = $this->Dashboardselia->get_by_id($id);
        echo json_encode($data);
    }
	
}
