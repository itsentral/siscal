<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Bast_tool extends CI_Controller { 
	public function __construct(){
        parent::__construct();	
		
		$this->load->database();
        if(!$this->session->userdata('isSISCALlogin')){
			redirect('login');
		}
		$this->load->model('master_model');
		$controller			= ucfirst(strtolower($this->uri->segment(1)));
		$this->Arr_Akses	= getAcccesmenu($controller);
		
		$this->folder	= 'Warehouses';
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
			'title'			=> 'Manage BAST Tools',
			'action'		=> 'index',
			'akses_menu'	=> $Arr_Akses
		);
		history('View List BAST Tool');
		$this->load->view($this->folder.'/v_bast_tool',$data);
	}
	
	function get_display_list(){
		$Arr_Akses		= $this->Arr_Akses;
		$Month_Find		= $this->input->post('month');
		$Year_Find		= $this->input->post('tahun');
		$WHERE			= "1=1";
		
		
		if($Month_Find){
			if(!empty($WHERE))$WHERE	.=" AND ";
			$WHERE	.="MONTH(head_bast.datet) = '".$Month_Find."'";
		}
		
		if($Year_Find){
			if(!empty($WHERE))$WHERE	.=" AND ";
			$WHERE	.="YEAR(head_bast.datet) = '".$Year_Find."'";
		}
		
		
		$requestData	= $_REQUEST;
		
		$like_value     = $requestData['search']['value'];
        $column_order   = $requestData['order'][0]['column'];
        $column_dir     = $requestData['order'][0]['dir'];
        $limit_start    = $requestData['start'];
        $limit_length   = $requestData['length'];
		
		$columns_order_by = array(
			0 => 'head_bast.nomor',
			1 => 'head_bast.datet',
			2 => 'head_bast.name',
			3 => 'head_bast.no_so',
			4 => 'head_quot.nomor',
			5 => 'head_quot.pono',
			6 => 'head_bast.`status`'
		);
		
		
		
		if($like_value){
			if(!empty($WHERE))$WHERE	.=" AND ";
			$WHERE	.="(
						 head_bast.nomor LIKE '%".$this->db->escape_like_str($like_value)."%'
						  OR DATE_FORMAT(head_bast.datet, '%d-%m-%Y') LIKE '%".$this->db->escape_like_str($like_value)."%'
						  OR head_bast.name LIKE '%".$this->db->escape_like_str($like_value)."%'
						  OR head_bast.no_so LIKE '%".$this->db->escape_like_str($like_value)."%'
						  OR head_quot.nomor LIKE '%".$this->db->escape_like_str($like_value)."%'
						  OR head_quot.pono LIKE '%".$this->db->escape_like_str($like_value)."%'
						  OR head_quot.`status` LIKE '%".$this->db->escape_like_str($like_value)."%'
						)";
		}
		
		
		$sql = "SELECT
					 head_bast.*,
					 head_quot.id AS quotation_id,
					 head_quot.nomor AS quotation_nomor,
					 head_quot.datet AS quotation_date,
					 head_quot.pono,
					 head_quot.podate,
					(@row:=@row+1) AS urut
				FROM
					bast_headers head_bast
				INNER JOIN letter_orders head_so ON head_so.id=head_bast.letter_order_id
				INNER JOIN quotations head_quot ON head_so.quotation_id = head_quot.id,
				(SELECT @row:=0) r 
				WHERE ".$WHERE;
		//print_r($sql);exit();
		$fetch['totalData'] 	= $this->db->query($sql)->num_rows();
		$fetch['totalFiltered']	= $this->db->query($sql)->num_rows();

		

		$sql .= " ORDER BY ".$columns_order_by[$column_order]." ".$column_dir." ";
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
			
			
			$intL			= 0;
            
			$Kode_BAST		= $row['id'];
			$Tgl_BAST		= date('d-m-Y',strtotime($row['datet']));
			$Nomor_Bast		= $row['nomor'];
			$Customer		= $row['name'];
			$Custid			= $row['kode'];
			$Alamat			= $row['address'];
			$sts_BAST		= $row['status'];
			$Code_Quot		= $row['quotation_id'];
			$Code_SO		= $row['letter_order_id'];
			$Nomor_Quot		= $row['quotation_nomor'];
			$Nomor_SO		= $row['no_so'];
			$Nomor_PO		= $row['pono'];
			
			if($sts_BAST == 'OPN'){
				$Ket_Status	= "<span class='badge bg-maroon'>OPEN</span>";
			}else if($sts_BAST == 'CLS'){
				$Ket_Status	= "<span class='badge bg-orange'>CLOSE</span>";
			}
			$nestedData 	= array(); 
			
			$nestedData[]	= $Nomor_Bast;
			$nestedData[]	= $Tgl_BAST;
			$nestedData[]	= $Customer;
			$nestedData[]	= $Nomor_SO;
			$nestedData[]	= $Nomor_Quot;
			$nestedData[]	= $Nomor_PO;			
			$nestedData[]	= $Ket_Status;
			
			$Template			="";
			
			if($Arr_Akses['read'] == 1){
				$Template		.="<button type='button' class='btn btn-sm btn-info' onClick='view_bast(\"".$Kode_BAST."\");'> <i class='fa fa-search'></i> </button>";
				
			}
			if($Arr_Akses['download'] == 1 && $sts_BAST=='OPN'){
				$Template		.="&nbsp;<a href='".site_url('Bast_tool/print_bast/'.$Kode_BAST)."' class='btn btn-sm btn-warning' title='Print BAST' target='_blank'> <i class='fa fa-print'></i> </a>";
			}
			
			
			
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
	
	
	
	function view_detail_bast(){
		$Kode_Bast			= urldecode($this->input->get('nobast'));
		$rows_header		= $this->db->get_where('bast_headers',array('id'=>$Kode_Bast))->row();
		$rows_Order			= $this->db->get_where('letter_orders',array('id'=>$rows_header->letter_order_id))->row();
		$rows_Quot			= $this->db->get_where('quotations',array('id'=>$rows_Order->quotation_id))->row();
		
		$rows_detail		= $this->db->get_where('bast_details',array('bast_header_id'=>$Kode_Bast))->result();
		$data = array(
			'title'			=> 'DETAIL BAST',
			'rows_header'	=> $rows_header,
			'rows_detail'	=> $rows_detail,
			'rows_quot'		=> $rows_Quot,
			'rows_order'	=> $rows_Order,
			'action'		=> 'view_detail_bast'
		);
		$this->load->view($this->folder.'/v_bast_tool_preview',$data); 
	}
	
	function print_bast($Kode_Bast=''){
		$rows_Header	= $this->db->get_where('bast_headers',array('id'=>$Kode_Bast))->row_array();
		$data = array(
			'title'			=> 'BAST PRINT',
			'action'		=> 'print_bast',
			'rows_header'	=> $rows_Header,
			'printby'		=> $this->session->userdata('siscal_username'),
			'today' 		=> date("Y-m-d H:i:s"),
			'code_process'	=> $Kode_Bast
		);
		
		$this->load->view($this->folder.'/v_bast_tool_print',$data); 
	}
	
	function preview_receive_tool_bast_driver(){
		$rows_Header	= $rows_Tool	= array();
		if($this->input->post()){
			$Code_Detail	= $this->input->post('code_rec_detail');
			$rows_Header	= $this->db->get_where('bast_details',array('id'=>$Code_Detail))->row();
			$rows_Tool	= $this->db->get_where('bast_detail_tools',array('bast_detail_id'=>$Code_Detail))->result();
		}
		
		$data = array(
			'title'			=> 'DETAIL BAST RECEIVE',
			'rows_header'	=> $rows_Header,
			'rows_detail'	=> $rows_Tool,
			'action'		=> 'preview_receive_tool_bast_driver'
		);
		$this->load->view($this->folder.'/v_bast_tool_receive',$data); 
	}
	
}