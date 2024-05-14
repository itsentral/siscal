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
			'akses_menu'	=> $Arr_Akses
		);
		history($sessionGet.' View Data Selia Sertifikat');
		$this->load->view($this->folder.'/dashboard_selia',$data);
	}

	function getById($id)
    {
        $data = $this->selia->get_by_id($id);
        echo json_encode($data);
    }
	
}
