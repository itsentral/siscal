<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class List_invoice_subcon extends CI_Controller {
	function __construct(){
		parent::__construct();
		if(!$this->session->userdata('isSISCALlogin')){
			redirect('login');
		}
		$this->load->model('Subcon/M_invoice_subcon', 'invoice');
		$this->folder	='Subcon';
	}

	public function index()
	{
		$controller			= ucfirst(strtolower($this->uri->segment(1)));
		$Arr_Akses			= getAcccesmenu($controller);
		if($Arr_Akses['read'] !='1'){
			$this->session->set_flashdata("alert_data", "<div class=\"alert alert-warning\" id=\"flash-message\">You Don't Have Right To Access This Page, Please Contact Your Administrator....</div>");
			redirect(site_url('dashboard'));
		}
		
		$comp_Data			= $this->db->get('groups')->result_array();
		
		$data = array(
			'title'			=> 'List Invoice Subcon',
			'action'		=> 'index',
			'row'			=> $comp_Data,
			'akses_menu'	=> $Arr_Akses
		);
		history('View Data Group');
		$this->load->view($this->folder.'/vw_list_invoice',$data);
	}

		
	function list_func()
	{
			$list = $this->invoice->get_datatables();
			$data = array();
			$no = $_POST['start'];
			foreach ($list as $item) {
				$no++; 

				$tglPayment = strtotime($item->payment_date);

				if($item->payment_reff != ""){
					$status = '<button class="btn btn-xs btn-success">PAID</button>';
				}else{
					$status = '<button class="btn btn-xs btn-danger">UNPAID</button>';
				}

				$row = array();
				$row[] = $item->supplier_name;
				$row[] = $item->cust_invoice_no;
				$row[] = $item->subcon_cpr_header_id;
				$row[] = number_format($item->total);
				$row[] = $item->descr;
				$row[] = ($item->payment_date != null) ? date("Y/m/d", $tglPayment) : "";
				$row[] = $item->payment_reff;
				$row[] = $status;
				
				$data[] = $row;
			}

			$output = array(
				"draw" => $_POST['draw'],
				"recordsTotal" => $this->invoice->count_all(),
				"recordsFiltered" => $this->invoice->count_filtered(),
				"data" => $data,
			);
			
			echo json_encode($output);
		
	}

}
