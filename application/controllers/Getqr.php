<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Getqr extends CI_Controller {
	function __construct(){
		parent::__construct();
		
	}

	public function getQRcalresult($id)
	{
		$HashKey		= '173ALIDYhG93b0qyJfIxfsdgfh2guVoUubW46hjwvniR200881173Gacad0FgaC9mi2008811M4ru5L1mChaeMo0';
		$CodeHash		= enkripsi_url($id);
		$Link_URL		= 'https://sentral.dutastudy.com/Siscal_CRM/index.php/CertificateGenerate/CertificateAuthorized/'.$CodeHash;
		redirect($Link_URL);
	}

}
