<?php
defined('BASEPATH') or exit('No direct script access allowed');
date_default_timezone_set('Asia/Jakarta');

class Error extends CI_Controller
{
	public function __construct()
	{
		parent::__construct();

		$this->load->database();
		if (!$this->session->userdata('isSISCALlogin')) {
			redirect('login');
		}
		$controller				= ucfirst(strtolower($this->uri->segment(1)));
		$this->Arr_Akses		= getAcccesmenu($controller);

		$this->load->helper('url');
	}

	public function index(){
		$controller			= ucfirst(strtolower($this->uri->segment(1)));
		$Arr_Akses			= getAcccesmenu($controller);

		$data = array(
			'title'			=> '404 Error Page',
			'action'		=> 'index',
			'akses_menu'	=> $Arr_Akses
		);
		
		history('View 404 Error Page');
		$this->load->view('error404', $data);
	}
}
