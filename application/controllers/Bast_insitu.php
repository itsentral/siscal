<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Bast_insitu extends CI_Controller { 
	public function __construct(){
        parent::__construct();	
		
		$this->load->database();
        if(!$this->session->userdata('isSISCALlogin')){
			redirect('login');
		}
		$this->load->model('master_model');
		$controller			= ucfirst(strtolower($this->uri->segment(1)));
		$this->Arr_Akses	= getAcccesmenu($controller);
		
		$this->folder	= 'Insitu';
    }

	public function index(){
		$Arr_Akses			= $this->Arr_Akses;
		if($Arr_Akses['read'] !='1'){
			$this->session->set_flashdata("alert_data", "<div class=\"alert alert-warning\" id=\"flash-message\">You Don't Have Right To Access This Page, Please Contact Your Administrator....</div>");
			redirect(site_url('dashboard'));
		}
		
		$data = array(
			'title'			=> 'Manage BAST Insitu',
			'action'		=> 'index',
			'akses_menu'	=> $Arr_Akses
		);
		history('View List BAST Insitu');
		$this->load->view($this->folder.'/v_bast_insitu',$data);
	}
	function get_data_display(){
		$Arr_Akses			= $this->Arr_Akses;
		
		
		$requestData	= $_REQUEST;
		$fetch			= $this->qry_list_bast(
			$requestData['search']['value'], 
			$requestData['order'][0]['column'], 
			$requestData['order'][0]['dir'], 
			$requestData['start'], $requestData['length']
		);
		//echo"<pre>";print_r($fetch);exit;
		$totalData		= $fetch['totalData'];
		$totalFiltered	= $fetch['totalFiltered'];
		$query			= $fetch['query'];
		
		$data		= array();
        $urut1  	= 1;
        $urut2  	= 0;
		$Bulan_Now	= date('n');
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
			$Kode_BAST		= $row['id'];
			$Tgl_BAST		= date('d-m-Y',strtotime($row['datet']));
			$Nomor_Bast		= $row['nomor'];
			$Customer		= $row['customer_name'];
			$Custid			= $row['customer_id'];
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
			$nestedData[]	= $Nomor_Quot;
			$nestedData[]	= $Nomor_PO;
			$nestedData[]	= $Nomor_SO;
			$nestedData[]	= $Ket_Status;
			
			$Template			="";
			
			if($Arr_Akses['read'] == 1){
				$Template		.="<button type='button' class='btn btn-sm btn-info' onClick='view_bast(\"".$Kode_BAST."\");'> <i class='fa fa-search'></i> </button>";
				
			}
			if($Arr_Akses['download'] == 1 && $sts_BAST=='OPN'){
				$Template		.="&nbsp;<a href='".site_url('Bast_insitu/print_bast/'.$Kode_BAST)."' class='btn btn-sm btn-warning' title='Print BAST' target='_blank'> <i class='fa fa-print'></i> </a>";
			}
			
			
			
			$nestedData[]	= $Template;
			
			$data[] = $nestedData;
			$urut1++;
			$urut2++;
			
		}

		$json_data = array(
			"draw"            => intval( $requestData['draw'] ),  
			"recordsTotal"    => intval( count($data)),  
			"recordsFiltered" => intval( $totalFiltered ), 
			"data"            => $data
		);

		echo json_encode($json_data);
		
	}
	public function qry_list_bast($like_value = NULL, $column_order = NULL, $column_dir = NULL, $limit_start = NULL, $limit_length = NULL)
	{
		$WHERE		= "";
		
		
		if($like_value){
			if(!empty($WHERE))$WHERE	.=" AND ";
			$WHERE	.="(
						head_bast.nomor LIKE '%".$this->db->escape_like_str($like_value)."%'
						OR head_bast.datet LIKE '%".$this->db->escape_like_str($like_value)."%'
						OR head_bast.customer_name LIKE '%".$this->db->escape_like_str($like_value)."%'
						OR head_bast.`status` LIKE '%".$this->db->escape_like_str($like_value)."%'
						OR head_quot.nomor LIKE '%".$this->db->escape_like_str($like_value)."%'
						OR head_quot.pono LIKE '%".$this->db->escape_like_str($like_value)."%'
						OR head_so.no_so LIKE '%".$this->db->escape_like_str($like_value)."%'
						)";
		}
		
		

		$sql = "
			SELECT 
				(@row:=@row+1) AS nomor_urut,
				head_bast.*,
				head_quot.nomor AS quotation_nomor,
				head_quot.pono,
				head_so.no_so
				FROM
					insitu_letters head_bast
				INNER JOIN quotations head_quot ON head_bast.quotation_id=head_quot.id
				INNER JOIN letter_orders head_so ON head_so.id=head_bast.letter_order_id,
				(SELECT @row:=0) r ";
		if($WHERE){
			$sql.=" WHERE ".$WHERE;
		}
				
		//print_r($sql);exit();
		

		$columns_order_by = array( 
			0 => 'head_bast.nomor',
			1 => 'head_bast.datet',
			2 => 'head_bast.customer_name',
			3 => 'head_quot.nomor',
			4 => 'head_quot.pono',
			5 => 'head_so.no_so'
			
		);
		
		$jum_Data	= $this->db->query($sql)->num_rows();
		
		$sql .= " ORDER BY ".$columns_order_by[$column_order]." ".$column_dir." ";
		$sql .= " LIMIT ".$limit_start." ,".$limit_length." ";
		
		$data['query'] 					= $this->db->query($sql);
		$data['totalData']				= $jum_Data;
		$data['totalFiltered']			= $jum_Data;
		
		return $data;
	}
	
	
	function detail_bast(){
		$Kode_Bast			= $this->input->post('kode_bast');
		$rows_header		= $this->master_model->getArray('insitu_letters',array('id'=>$Kode_Bast));
		
		$rows_detail		= $this->master_model->getArray('insitu_letter_details',array('insitu_letter_id'=>$Kode_Bast));
		$data = array(
			'title'			=> 'Detail BAST',
			'rows_header'	=> $rows_header[0],
			'rows_detail'	=> $rows_detail,
			'action'		=> 'detail_bast'
		);
		$this->load->view($this->folder.'/detail_bast',$data); 
	}
	
	function print_bast($Kode_Bast=''){
		$rows_Header	= $this->db->get_where('insitu_letters',array('id'=>$Kode_Bast))->row_array();
		//echo"<pre>";print_r($rows_Header);exit;
		$data = array(
			'title'			=> 'BAST INSITU PRINT',
			'action'		=> 'print_bast',
			'rows_header'	=> $rows_Header,
			'printby'		=> $this->session->userdata('siscal_username'),
			'today' 		=> date("Y-m-d H:i:s")
		);
		$this->load->view($this->folder.'/print_bast',$data); 
	}
	
	
}