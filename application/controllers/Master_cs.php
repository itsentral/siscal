<?php
defined('BASEPATH') OR exit('No direct script access allowed');
date_default_timezone_set('Asia/Jakarta');

class Master_cs extends CI_Controller {
	function __construct(){
		parent::__construct();
		if(!$this->session->userdata('isSISCALlogin')){
			redirect('login');
		}

		$this->load->model('Customers/M_customerlist', 'customer');
		$this->load->model('Customers/M_followuplist', 'followup');

		$this->folder	='Master/Customers';
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
			'title'			=> 'Master Customer',
			'action'		=> 'index',
			'row'			=> $comp_Data,
			'akses_menu'	=> $Arr_Akses
		);
		history('View Data Master Customer');
		$this->load->view($this->folder.'/vw_customerlist',$data);
	}

		
	function list_func_customer()
	{
			$list = $this->customer->get_datatables();
			$data = array();
			$no = $_POST['start'];
			foreach ($list as $item) {
				$no++; 
				$row = array();

				$controller			= ucfirst(strtolower($this->uri->segment(1)));
				$Arr_Akses			= getAcccesmenu($controller);

				if ($item->flag_active == '1') {
					$flag_active = "<td class='text-center'><span class='badge bg-green'>Active</span></td>";
				} else {
					$flag_active = "<td class='text-center'><span class='badge bg-maroon'>Inactive</span></td>";
				}
				
				$row[] = $no;
				$row[] = $item->name;
				$row[] = $item->address;
				$row[] = $item->phone;
				$row[] = $item->contact;
				$row[] = $item->nama;
				$row[] = $flag_active;

				if($Arr_Akses['create'] =='1'){
					$row[] = '
					<div class="btn-group">
						<button type="button" class="btn btn-warning btn-sm dropdown-toggle" data-toggle="dropdown" aria-expanded="true"><text style="font-weight: 600;letter-spacing: 0.3px;">Actions </text>
							<span class="fa fa-caret-down"></span>
						</button>

						<ul class="dropdown-menu pull-right">
							<li><a href="javascript:void(0)" onClick="onProgres();"><i class="fa fa-pencil"></i>&nbsp; Edit Data</a></li>
							<li><a href="javascript:void(0)" onClick="onProgres();"><i class="fa fa-eye"></i> View Data</a></li>
							<li><a href="'.base_url('master_cs').'/follow_up_sales/'.$item->id.'"><i class="fa fa-calendar-check-o"></i> Follow Up</a></li>
							<li><a href="javascript:void(0)" onClick="onProgres();"><i class="fa fa-trash"></i>&nbsp; Hapus Data</a></li>
						</ul>
				  	</div>';

				}else{
					$row[] = '<a class="btn btn-sm btn-success" href="javascript:void(0)" title="Sorry!" disabled><i class="fa fa-eye"></i></a>';
				}
				
				$data[] = $row;
			}

			$output = array(
				"draw" => $_POST['draw'],
				"recordsTotal" => $this->customer->count_all(),
				"recordsFiltered" => $this->customer->count_filtered(),
				"data" => $data,
			);
			
			echo json_encode($output);
		
	}

	function follow_up_sales($id = '')
	{
		$sessionGet 		= $this->session->userdata('siscal_username');
		$controller			= ucfirst(strtolower($this->uri->segment(1)));
		$Arr_Akses			= getAcccesmenu($controller);

		if($Arr_Akses['read'] !='1'){
			$this->session->set_flashdata("alert_data", "<div class=\"alert alert-warning\" id=\"flash-message\">You Don't Have Right To Access This Page, Please Contact Your Administrator....</div>");
			redirect(site_url('dashboard'));
		}
		
		$comp_Data			= $this->db->get('groups')->result_array();
		$rows_header		= $this->db->get_where('customers',array('id'=>$id))->row();
		$getSales			= $this->db->get_where('members',array('id'=>$rows_header->member_id))->row();
		$sqlStatus			= "SELECT status_activity FROM follow_up_sales AS a 
							WHERE date_activity = 
							(
								SELECT MAX(date_activity)
								FROM follow_up_sales AS b
								WHERE customer_id = '$id'
							)";   
   		$queryStatus 		= $this->db->query($sqlStatus)->row();
		$MonthlyActivity	= $this->db->get_where('follow_up_sales', array('customer_id'=>$id))->result_array();
		
		$data = array(
			'title'				=> 'Follow UP Customer',
			'action'			=> 'Sales',
			'row'				=> $comp_Data,
			'statusActivity'	=> $queryStatus,
			'MonthlyActivity'	=> $MonthlyActivity,
			'rows_header'		=> $rows_header,
			'getSales'			=> $getSales,
			'akses_menu'		=> $Arr_Akses,
			'id_cs'				=> $id
		);
		history($sessionGet.' View Data Follow Up Sales');
		$this->load->view($this->folder.'/vw_follow_up_sales',$data);
	}

	function list_func_activity()
	{
		$controller			= ucfirst(strtolower($this->uri->segment(1)));
		$Arr_Akses			= getAcccesmenu($controller);
		
		if ($Arr_Akses['read'] != '1') {
			$this->session->set_flashdata("alert_data", "<div class=\"alert alert-warning\" id=\"flash-message\">You Don't Have Right To Access This Page, Please Contact Your Administrator....</div>");
			redirect(site_url('Master_cs'));
		}

		$list = $this->followup->get_datatables();
		$data = array();
		$no = $_POST['start'];
		foreach ($list as $item) {
			$no++; 
			$row = array();

			$Status_ket		= $item->status_activity;
			$Lable_Status	= 'No Status';
			$Color_Status	= 'bg-default';

			if($Status_ket === 'New Data'){
				$Lable_Status	= 'New Data';
				$Color_Status	= 'bg-orange';
			}else if($Status_ket === 'No Program'){
				$Lable_Status	= 'No Program';
				$Color_Status	= 'bg-navy-active';
			}else if($Status_ket === 'Potensial'){
				$Lable_Status	= 'Potensial';
				$Color_Status	= 'bg-purple';
			}else if($Status_ket === 'Hot'){
				$Lable_Status	= 'Hot';
				$Color_Status	= 'bg-maroon';
			}else if($Status_ket === 'Deal'){
				$Lable_Status	= 'Deal';
				$Color_Status	= 'bg-olive';
			}else if($Status_ket === 'Lose'){
				$Lable_Status	= 'Lose';
				$Color_Status	= 'bg-default';
			}else{
				$Lable_Status	= 'No Status';
				$Color_Status	= 'bg-default';
			}

			$flag_status		= '<span class="badge '.$Color_Status.'">'.$Lable_Status.'</span>';
			
			$row[] = $item->nama_sales;
			$row[] = $item->date_activity;
			$row[] = $flag_status;
			$row[] = $item->ket_activity;

			if($Arr_Akses['create'] =='1'){
				$row[] = '<a class="btn btn-xs btn-warning" href="javascript:void(0)" title="Edit" onclick="edit_activity_sales(' . "'" . $item->id . "'" . ')"><i class="fa fa-pencil"></i></a>
						  <a class="btn btn-xs btn-danger" href="javascript:void(0)" title="Hapus" onclick="delete_activity_sales(' . "'" . $item->id . "'" . ')"><i class="fa fa-trash"></i></a>';

			}else{
				$row[] = '<a class="btn btn-xs btn-danger" href="javascript:void(0)" title="Anda tidak punya akses" disabled><i class="fa fa-close"></i></a>';
			}
			
			$data[] = $row;
		}

		$output = array(
			"draw" 				=> $_POST['draw'],
			"recordsTotal" 		=> $this->followup->count_all(),
			"recordsFiltered" 	=> $this->followup->count_filtered(),
			"data" 				=> $data,
		);
		
		echo json_encode($output);
	}

	function edit_func_activity($id)
    {
        $data = $this->followup->get_by_id($id);
        echo json_encode($data);
    }
	
	function add_func_activity(){
		$sessionGet = $this->session->userdata('siscal_username');
		$data = array(
			'id'	    		=> "",
			'customer_id' 		=> $this->input->post('customer_id'),
			'nama_sales' 		=> $this->input->post('nama_sales'),
			'date_activity' 	=> $this->input->post('date_activity'),
			'ket_activity' 		=> $this->input->post('ket_activity'),
			'status_activity' 	=> $this->input->post('status_activity'),
			'created_at' 		=> date("Y-m-d H:i:s"),
			'created_by' 		=> $sessionGet
		);

		$insert = $this->followup->save($data);

		if($insert){
			$result['status'] = true;
			$result['msg'] = 'Data berhasil tersimpan!';
		}else{
			$result['status'] = false;
			$result['msg'] = 'Data gagal tersimpan!';
		}
        history($sessionGet.' Add Data Follow Up Sales pada ID Customer '.$this->input->post('customer_id'));
		echo json_encode($result);
    }
	
	
	function update_func_activity(){
		$sessionGet = $this->session->userdata('siscal_username');
		$data = array(
			'nama_sales' 		=> $this->input->post('nama_sales'),
			'date_activity' 	=> $this->input->post('date_activity'),
			'ket_activity' 		=> $this->input->post('ket_activity'),
			'status_activity' 	=> $this->input->post('status_activity'),
			'update_at' 		=> date("Y-m-d H:i:s"),
			'update_by' 		=> $sessionGet
		);

		$update = $this->followup->update($data, array("id" => $this->input->post('id')));

		if($update){
			$result['status'] 	= true;
			$result['msg'] 		= 'Data berhasil diupdate!';
		}else{
			$result['status'] 	= false;
			$result['msg'] 		= 'Data gagal diupdate!';
		}
		history($sessionGet.' Edit Data Follow Up Sales pada ID Customer '.$this->input->post('customer_id'));
        echo json_encode($result);
    }

	function delete_func_activity($id, $idcs){
		$sessionGet = $this->session->userdata('siscal_username');
		$delete = $this->followup->delete_by_id($id);

		if($delete){
			$result['status'] = true;
			$result['msg']    = 'Follow Up berhasil dihapus!';
		}else{
			$result['status'] = false;
			$result['msg'] = 'Follow Up gagal dihapus!';
		}
		history($sessionGet.' Hapus Data Follow Up Sales pada ID Customer '.$idcs);
        echo json_encode($result);
    }
}
