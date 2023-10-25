<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Image_testing extends CI_Controller { 
	public function __construct(){
        parent::__construct();	
		
		$this->load->database();
        if(!$this->session->userdata('isSISCALlogin')){
			redirect('login');
		}
		$this->load->model('master_model');
		$controller			= ucfirst(strtolower($this->uri->segment(1)));
		
		
		$this->folder		= 'Testing';
		$this->file_attachement	= $this->config->item('link_file');
		$this->file_location	= $this->config->item('location_file');
    }

	public function index(){
		
		
		
		$data = array(
			'title'			=> 'TESTING CAMERA IMAGE',
			'action'		=> 'index'
		);
		history('Testing Camera');
		$this->load->view($this->folder.'/v_test_camera',$data);
	}
	
	
	function ajax_ambil_kamera(){
		$kategori		= '';
		if($this->input->get()){
			$kategori		= $this->input->get('kategori');
		}
		$data				= array(
			'kategori'	=> $kategori
		);
		$this->load->view($this->folder . '/v_ajax_ambil_kamera', $data);
	}
	
	
	
	
}