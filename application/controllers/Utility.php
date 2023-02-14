<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Utility extends CI_Controller {
	public function __construct(){
        parent::__construct();
		$this->load->model('master_model');
		$this->load->database();
        if(!$this->session->userdata('isSISCALlogin')){
			redirect('login');
		}
    }

	public function schedule(){
		$controller			= ucfirst(strtolower($this->uri->segment(1)));
		$controller1		= ucfirst(strtolower($this->uri->segment(2)));
		$akses              = $controller.'/'.$controller1;
		
		// print_r($akses);
		// exit;
		
		$Arr_Akses			= getAcccesmenu($akses);
		if($Arr_Akses['read'] !='1'){
			$this->session->set_flashdata("alert_data", "<div class=\"alert alert-warning\" id=\"flash-message\">You Don't Have Right To Access This Page, Please Contact Your Administrator....</div>");
			redirect(site_url('dashboard'));
		}
		
		$get_Data			= $this->master_model->getData('letter_orders','id','01');
		$menu_akses			= $this->master_model->getMenu(array('sts_siscal'=>'Y'));
		
		$data = array(
			'title'			=> 'Indeks Of Utility',
			'deskripsi'	    => 'Tool ini untuk memunculkan data di schedule',
			'action'		=> 'index',
			'row'			=> $get_Data,
			'data_menu'		=> $menu_akses,
			'akses_menu'	=> $Arr_Akses
		);
		// history('View Data menu');
		$this->load->view('Utility/view_schedule',$data);
	}
	
	
	function tampilkan_schedule()
	{ 
		
		$controller			= ucfirst(strtolower($this->uri->segment(1)));
		$controller1		= 'schedule';
		$akses              = $controller.'/'.$controller1;
		$Arr_Akses			= getAcccesmenu($akses);
		if($Arr_Akses['read'] !='1'){
			$this->session->set_flashdata("alert_data", "<div class=\"alert alert-warning\" id=\"flash-message\">You Don't Have Right To Access This Page, Please Contact Your Administrator....</div>");
			redirect(site_url('dashboard'));
		}
		
		
		$no_so              = $this->input->post('no_so');		
		
		
		
		$get_Data			= $this->master_model->getDataLike('letter_orders','no_so',$no_so);
		// print_r($get_Data);
		// exit;
		
		
		$menu_akses			= $this->master_model->getMenu(array('sts_siscal'=>'Y'));
		
		$data = array(
			'title'			=> 'Indeks Of Utility',
			'deskripsi'	    => 'Tool ini untuk memunculkan data di schedule',
			'action'		=> 'index',
			'row'			=> $get_Data,
			'data_menu'		=> $menu_akses,
			'akses_menu'	=> $Arr_Akses
		);
		// history('View Data menu');
		$this->load->view('Utility/view_schedule',$data);
		
	}
	
	function edit_schedule(){
		
		$id = $this->uri->segment(3);
		
		// print_r($id);
		// exit;
		$user = $this->session->userdata('siscal_username');
		$tgl  = date('d-m-Y H:i:s');
		
		$this->db->trans_begin();
		
	    $update	 = "UPDATE letter_orders SET reserved='2', update_by='$user', update_on='$tgl' WHERE id='$id'";
        $this->db->query($update);
		
		
		if($this->db->trans_status() === FALSE){
			 $this->db->trans_rollback();
			 $Arr_Return		= array(
					'status'		=> 2,
					'pesan'			=> 'Save Process Failed. Please Try Again...'
			   );
		}else{
			 $this->db->trans_commit();
			 $Arr_Return		= array(
				'status'		=> 1,
				'pesan'			=> 'Save Process Success. Thank You & Have A Nice Day...'
		   );
		}
		echo json_encode($Arr_Return);
		
	}
	
	
	public function latesend(){
		$controller			= ucfirst(strtolower($this->uri->segment(1)));
		$controller1		= ucfirst(strtolower($this->uri->segment(2)));
		$akses              = $controller.'/'.$controller1;
		$Arr_Akses			= getAcccesmenu($akses);
		if($Arr_Akses['read'] !='1'){
			$this->session->set_flashdata("alert_data", "<div class=\"alert alert-warning\" id=\"flash-message\">You Don't Have Right To Access This Page, Please Contact Your Administrator....</div>");
			redirect(site_url('dashboard'));
		}
		
		$get_Data			= $this->master_model->getData('trans_details','id','01');
		
		$menu_akses			= $this->master_model->getMenu(array('sts_siscal'=>'Y'));
		
		$data = array(
			'title'			=> 'Indeks Of Utility',
			'deskripsi'	    => 'Tool ini untuk tidak menampilkan data di dashboard late send',
			'action'		=> 'index',
			'row'			=> $get_Data,
			'data_menu'		=> $menu_akses,
			'akses_menu'	=> $Arr_Akses
		);
		// history('View Data menu');
		$this->load->view('Utility/view_latesend',$data);
	}
	
	
	function tampilkan_latesend()
	{ 
		
		$controller			= ucfirst(strtolower($this->uri->segment(1)));
		$controller1		= 'latesend';
		$akses              = $controller.'/'.$controller1;
		$Arr_Akses			= getAcccesmenu($akses);
		
		if($Arr_Akses['read'] !='1'){
			$this->session->set_flashdata("alert_data", "<div class=\"alert alert-warning\" id=\"flash-message\">You Don't Have Right To Access This Page, Please Contact Your Administrator....</div>");
			redirect(site_url('dashboard'));
		}
		
		
		$no_so              = $this->input->post('no_so');		
		
		
		
		$get_Data			= $this->master_model->getDataLike('trans_details','no_so',$no_so);
		// print_r($get_Data);
		// exit;
		
		
		$menu_akses			= $this->master_model->getMenu(array('sts_siscal'=>'Y'));
		
		$data = array(
			'title'			=> 'Indeks Of Utility',
			'deskripsi'	    => 'Tool ini untuk tidak menampilkan data di dashboard late send',
			'action'		=> 'index',
			'row'			=> $get_Data,
			'data_menu'		=> $menu_akses,
			'akses_menu'	=> $Arr_Akses
		);
		// history('View Data menu');
		$this->load->view('Utility/view_latesend',$data);
		
	}
	
	function edit_latesend(){
		
		$id = $this->uri->segment(3);
		
		// print_r($id);
		// exit;
		
		$user = $this->session->userdata('siscal_username');
		$tgl  = date('d-m-Y H:i:s');
		
		$this->db->trans_begin();
		
	    $update	 = "UPDATE trans_details SET qty_send=qty, qty_send_real=qty,location='Client', update_by='$user', update_on='$tgl'  WHERE id='$id'";
        $this->db->query($update);
		
		
		if($this->db->trans_status() === FALSE){
			 $this->db->trans_rollback();
			 $Arr_Return		= array(
					'status'		=> 2,
					'pesan'			=> 'Save Process Failed. Please Try Again...'
			   );
		}else{
			 $this->db->trans_commit();
			 $Arr_Return		= array(
				'status'		=> 1,
				'pesan'			=> 'Save Process Success. Thank You & Have A Nice Day...'
		   );
		}
		echo json_encode($Arr_Return);
		
	}
	
	
	public function detail_so(){
		$controller			= ucfirst(strtolower($this->uri->segment(1)));
		$controller1		= 'detail_so';
		$akses              = $controller.'/'.$controller1;
		$Arr_Akses			= getAcccesmenu($akses);
		
		if($Arr_Akses['read'] !='1'){
			$this->session->set_flashdata("alert_data", "<div class=\"alert alert-warning\" id=\"flash-message\">You Don't Have Right To Access This Page, Please Contact Your Administrator....</div>");
			redirect(site_url('dashboard'));
		}
		
		
		$no_so              = 'A-007/SO/CAL-V3/XII/18';		

        $so			    = $this->db->query("SELECT * FROM letter_orders WHERE no_so ='$no_so'")->row();
		
		$id_so          =  $so->id;
		
		
		
		$get_Data			= $this->master_model->getDataLike('letter_order_details','letter_order_id',$id_so);
		// print_r($get_Data);
		// exit;
		
		
		$menu_akses			= $this->master_model->getMenu(array('sts_siscal'=>'Y'));
		
		$data = array(
			'title'			=> 'Indeks Of Utility',
			'deskripsi'	    => 'Tool ini untuk menghapus detail so yang double',
			'action'		=> 'index',
			'no_so'		    => $no_so,
			'row'			=> $get_Data,
			'data_menu'		=> $menu_akses,
			'akses_menu'	=> $Arr_Akses
		);
		// history('View Data menu');
		$this->load->view('Utility/view_detail_so',$data);
		
	}
	
	function tampilkan_detail_so()
	{ 
		
		$controller			= ucfirst(strtolower($this->uri->segment(1)));
		$controller1		= 'detail_so';
		$akses              = $controller.'/'.$controller1;
		$Arr_Akses			= getAcccesmenu($akses);
		
		if($Arr_Akses['read'] !='1'){
			$this->session->set_flashdata("alert_data", "<div class=\"alert alert-warning\" id=\"flash-message\">You Don't Have Right To Access This Page, Please Contact Your Administrator....</div>");
			redirect(site_url('dashboard'));
		}
        $no_so              = $this->input->post('no_so');		

        $so			= $this->db->query("SELECT * FROM letter_orders WHERE no_so ='$no_so'")->row();
		
		$id_so          = $so->id;
		
		
		
		$get_Data			= $this->master_model->getDataLike('letter_order_details','letter_order_id',$id_so);
		// print_r($get_Data);
		// exit;
		
		
		$menu_akses			= $this->master_model->getMenu(array('sts_siscal'=>'Y'));
		
		$data = array(
			'title'			=> 'Indeks Of Utility',
			'deskripsi'	    => 'Tool ini untuk menghapus detail so yang double',
			'action'		=> 'index',
			'no_so'		    => $no_so,
			'row'			=> $get_Data,
			'data_menu'		=> $menu_akses,
			'akses_menu'	=> $Arr_Akses
		);
		// history('View Data menu');
		$this->load->view('Utility/view_detail_so',$data);
		
	}
	
	function hapus_detail(){
		
		$id = $this->uri->segment(3);
		
		// print_r($id);
		// exit;
		
		$user = $this->session->userdata('siscal_username');
		$tgl  = date('Y-m-d H:i:s');
		
		$this->db->trans_begin();
		
		$update1	 = "UPDATE letter_order_details SET modified_by='$user', modified_date='$tgl'  WHERE id='$id'";
        $this->db->query($update1);
		
		
	    $update2	 = "INSERT INTO letter_order_details_delete (
					id,
					letter_order_id,
					quotation_detail_id,
					tool_id,
					tool_name,
					piece_id,
					qty,
					supplier_id,
					supplier_name,
					delivery_id,
					delivery_name,
					descr,
					tipe,
					get_tool,
					detail_id,
					created_by,
					created_date,
					modified_by,
					modified_date,
					flag_subcon_process
				) SELECT
					id,
					letter_order_id,
					quotation_detail_id,
					tool_id,
					tool_name,
					piece_id,
					qty,
					supplier_id,
					supplier_name,
					delivery_id,
					delivery_name,
					descr,
					tipe,
					get_tool,
					detail_id,
					created_by,
					created_date,
					modified_by,
					modified_date,
					flag_subcon_process
					
				FROM
					letter_order_details 
				WHERE id='$id'";
				
        $this->db->query($update2);
		
		$update3	 = "DELETE FROM letter_order_details WHERE id='$id'";
        $this->db->query($update3);
		
		if($this->db->trans_status() === FALSE){
			 $this->db->trans_rollback();
			 $Arr_Return		= array(
					'status'		=> 2,
					'pesan'			=> 'Save Process Failed. Please Try Again...'
			   );
		}else{
			 $this->db->trans_commit();
			 $Arr_Return		= array(
				'status'		=> 1,
				'pesan'			=> 'Save Process Success. Thank You & Have A Nice Day...'
		   );
		}
		echo json_encode($Arr_Return);
		
	}
}

