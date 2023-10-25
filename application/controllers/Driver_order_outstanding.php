<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Driver_order_outstanding extends CI_Controller { 
	public function __construct(){
        parent::__construct();	
		
		$this->load->database();
        if(!$this->session->userdata('isSISCALlogin')){
			redirect('login');
		}
		$this->load->model('master_model');
		$controller				= ucfirst(strtolower($this->uri->segment(1)));
		$this->Arr_Akses		= getAcccesmenu($controller);
		
		$this->folder			= 'Driver_order';
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
			'title'			=> 'OUTSTANDING DRIVER ORDER',
			'action'		=> 'index',
			'akses_menu'	=> $Arr_Akses
		);
		history('View List Driver Order');
		$this->load->view($this->folder.'/v_driver_order_outstanding',$data);
	}
	function get_data_display(){
		$Arr_Akses		= $this->Arr_Akses;
		
		$WHERE			= "sts_order ='OPN'";
		
		$Datet			= $this->input->post('tanggal');
		$requestData	= $_REQUEST;
		
		$like_value     = $requestData['search']['value'];
        $column_order   = $requestData['order'][0]['column'];
        $column_dir     = $requestData['order'][0]['dir'];
        $limit_start    = $requestData['start'];
        $limit_length   = $requestData['length'];
		
		$columns_order_by = array(
			1 => 'order_no',
			2 => 'plan_date',
			3 => 'company',
			4 => 'type_comp',
			5 => 'category',
			6 => 'address'
		);
		
		
		
		if($like_value){
			if(!empty($WHERE))$WHERE	.=" AND ";
			$WHERE	.="(
						  order_no LIKE '%".$this->db->escape_like_str($like_value)."%'
						  OR DATE_FORMAT(plan_date, '%d %b %Y') LIKE '%".$this->db->escape_like_str($like_value)."%'
						  OR plan_time LIKE '%".$this->db->escape_like_str($like_value)."%'
						  OR company LIKE '%".$this->db->escape_like_str($like_value)."%'
						  OR type_comp LIKE '%".$this->db->escape_like_str($like_value)."%'
						  OR category LIKE '%".$this->db->escape_like_str($like_value)."%'
						  OR address LIKE '%".$this->db->escape_like_str($like_value)."%'
						)";
		}
		
		if($Datet){
			if(!empty($WHERE))$WHERE .=" AND ";
			$WHERE	.="plan_date = '".date('Y-m-d',strtotime($Datet))."'";
		}
		
		
		$sql = "SELECT
					*,
					(@row:=@row+1) AS urut
				FROM
					trans_driver_orders,
				(SELECT @row:=0) r 
				WHERE ".$WHERE;
		//print_r($sql);exit();
		$fetch['totalData'] 	= $this->db->query($sql)->num_rows();
		$fetch['totalFiltered']	= $this->db->query($sql)->num_rows();

		

		$sql .= " ORDER BY plan_date DESC,".$columns_order_by[$column_order]." ".$column_dir." ";
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
			
			$Code_Order		= $row['order_code'];
			$Nomor_Order	= $row['order_no'];
			$Date_Order		= date('d-m-Y',strtotime($row['plan_date']));
			$Time_Order		= $row['plan_time'];
			$Code_Cust		= $row['company_code'];
			$Name_Cust		= $row['company'];
			$Type_Cust		= $row['type_comp'];
			$Type_Process	= $row['category'];
			$Addr_Cust		= $row['address'];
			$PIC_Name_Cust	= $row['pic_name'];
			$PIC_Phone_Cust	= $row['pic_phone'];
			$Status_Order	= $row['sts_order'];
			
			
			
			if($Type_Process === 'REC'){
				$Ket_Category	= '<span class="badge" style="background-color:#16697A !important;color:#ffffff !important;">AMBIL ALAT</span>';
			}else if($Type_Process === 'DEL'){
				$Ket_Category	= '<span class="badge" style="background-color:#DB6400 !important;color:#ffffff !important;">KIRIM ALAT</span>';
			}else if($Type_Process === 'INS'){
				$Ket_Category	= '<span class="badge" style="background-color:#37474f !important;color:#ffffff !important;">ANTAR TEKNISI</span>';
			}
			
			if($Type_Cust === 'CUST'){
				$Ket_Comp	= '<span class="badge" style="background-color:#c2185b !important;color:#ffffff !important;">CUSTOMER</span>';
			}else{
				$Ket_Comp	= '<span class="badge" style="background-color:#0277bd !important;color:#ffffff !important;">SUBCON</span>';
			}
			
			
			
			$Template		= '<button type="button" class="btn btn-sm btn-primary" onClick = "ActionPreview({code:\''.$Code_Order.'\',action :\'detail_driver_order\',title:\'VIEW DRIVER ORDER\'});" title="VIEW DRIVER ORDER"> <i class="fa fa-search"></i> </button>';			
			
			
			
			
			
			$nestedData		= array();
			$nestedData[]	= '<input type="checkbox" name="detPilih[]" id="det_pilih_'.$Code_Order.'" class="check_detail" value = "'.$Code_Order.'">';
			$nestedData[]	= $Nomor_Order;
			$nestedData[]	= $Date_Order;
			$nestedData[]	= $Name_Cust;
			$nestedData[]	= $Ket_Comp;
			$nestedData[]	= $Ket_Category;
			$nestedData[]	= $Addr_Cust;
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
	
	function detail_driver_order(){
		$OK_Proses	= 0;
		$rows_Header	= $rows_Detail =  array();
		
		if($this->input->post()){
			$Code_Process	= urldecode($this->input->post('code'));			
			$rows_Header	= $this->db->get_where('trans_driver_orders',array('order_code'=>$Code_Process))->row();
			$rows_Detail	= $this->db->get_where('trans_driver_order_details',array('order_code'=>$Code_Process))->result();
			
		}
		
		
		$data = array(
			'title'			=> 'DRIVER ORDER PREVIEW',
			'action'		=> 'detail_driver_order',
			'akses_menu'	=> $this->Arr_Akses,
			'rows_header'	=> $rows_Header,
			'rows_detail'	=> $rows_Detail,
			'category'		=> 'view'
		);
		
		$this->load->view($this->folder.'/v_driver_order_preview',$data);
		
	}
	
	function create_spk_driver_order(){
		$rows_Header	= $rows_Driver =  array();
		$Plan_Date		= date('Y-m-d');
		if($this->input->get()){
			$Code_Pilih		= urldecode($this->input->get('code_driver'));
			$Datet			= urldecode($this->input->get('plan_date'));
			$Plan_Date		= date('Y-m-d',strtotime($Datet));
			$Impl_Code		= str_replace("^_^","','",$Code_Pilih);
			$Query_Header	= "SELECT * FROM trans_driver_orders WHERE order_code IN('".$Impl_Code."') AND sts_order IN('OPN')";
			$rows_Header	= $this->db->query($Query_Header)->result_array();
			
			$Query_Driver	= "SELECT id, nama FROM members WHERE status ='1' AND division_id = 'DIV-004' ORDER BY nama ASC";
			$rows_Driver	= $this->db->query($Query_Driver)->result();
		}
		
		
		$data = array(
			'title'			=> 'CREATE SPK DRIVER',
			'action'		=> 'create_spk_driver_order',
			'akses_menu'	=> $this->Arr_Akses,
			'rows_header'	=> $rows_Header,
			'rows_driver'	=> $rows_Driver,
			'plan_date'		=> $Plan_Date,
			'category'		=> 'add'
		);
		$this->load->view($this->folder.'/v_driver_order_spk_process',$data);
	}
	
	
	function save_create_spk_driver_order(){
		$rows_Return	= array(
			'status'		=> 2,
			'pesan'			=> 'No Record was found...'
		);
		if($this->input->post()){
			//echo"<pre>";print_r($this->input->post());exit;
			
			$Created_By		= $this->session->userdata('siscal_userid');
			$Created_Name	= $this->session->userdata('siscal_username');
			$Created_Date	= date('Y-m-d H:i:s');
			
			$Date_SPK		= date('Y-m-d',strtotime($this->input->post('datet')));
			$ChosenDriver	= explode('^',$this->input->post('driver_id'));
			$Code_Driver	= $ChosenDriver[0];
			$Name_Driver	= $ChosenDriver[1];
			$Notes			= strtoupper($this->input->post('notes'));
			$detDetail		= $this->input->post('detDetail');
			
			$OK_Proses		= 1;
			$Pesan_Error	= '';
			$ImpDetail		= implode("','",$detDetail);
			$Query_Find		= "SELECT * FROM trans_driver_orders WHERE order_code IN('".$ImpDetail."') AND sts_order IN('OPN')";
			$Pros_Find		= $this->db->query($Query_Find);
			$Num_Find		= $Pros_Find->num_rows();
			if($Num_Find <= 0){
				$OK_Proses	= 0;
				$Pesan_Error	= 'Data has been modified by other process...';
			}
			
			if($OK_Proses === 0){
				$rows_Return	= array(
					'status'		=> 2,
					'pesan'			=> $Pesan_Error
				);
			}else{
				$this->db->trans_begin();
				$Pesan_Error	= '';
				
				$rows_Find		= $Pros_Find->result();
				$MonthYear		= date('Y-m',strtotime($Date_SPK));
				$Year			= date('Y',strtotime($Date_SPK));
				$Month			= date('n',strtotime($Date_SPK));
				$Month_Romawi	= getRomawi($Month);
				
				
				$Nomor_Urut		= 1;
				$Query_Urut		= "SELECT
										MAX(CAST(SUBSTRING_INDEX(id,'-',-1) AS UNSIGNED)) AS nomor_urut
									FROM
										spk_drivers
									WHERE
										datet LIKE '".$Year."-%'";
				$rows_Urut		= $this->db->query($Query_Urut)->row();
				if($rows_Urut){
					$Nomor_Urut	= intval($rows_Urut->nomor_urut) + 1;
				}
				
				$Code_SPK		= 'SPK-D-'.str_replace('-','',$MonthYear).'-'.sprintf('%04d',$Nomor_Urut);
				
				## AMBIL NOMOR SPK DRIVER ##
				$Awal_Nomor	= 1;
				$Query_Nomor	= "SELECT
										MAX(CAST(SUBSTRING_INDEX(nomor,'/',1) AS UNSIGNED)) AS nomor_awal
									FROM
										spk_drivers
									WHERE
										datet LIKE '".$MonthYear."-%'";
				$rows_Nomor		= $this->db->query($Query_Nomor)->row();
				if($rows_Nomor){
					$Awal_Nomor	= intval($rows_Nomor->nomor_awal) + 1;
				}
				
				$Nomor_SPK		= sprintf('%03d',$Awal_Nomor).'/N-SPK.D/'.$Month_Romawi.'/'.date('y',strtotime($Date_SPK));
				
				$intL		= $intP	= $intB	= $intK	= $intS	= $intT	= 0;
				$Arr_Hist	= $Arr_Det	=  $Arr_BAST	= $Arr_Pick	= $Arr_Send	= $Arr_Teknisi = $Arr_QuotDet = $Upd_Order = array();
				if($rows_Find){
					foreach($rows_Find as $keyFind=>$valFind){
						$Code_Order		= $valFind->order_code;
						$Nocust			= $valFind->company_code;
						$Customer		= $valFind->company;
						$Cust_Type		= $valFind->type_comp;
						$Order_Type		= $valFind->category;
						$Cust_Address	= $valFind->address;
						$rows_Detail	= $this->db->get_where('trans_driver_order_details',array('order_code'=>$Code_Order))->result();
						
						$Upd_Order[]	= $Code_Order;
						
						##  MODIFIED BY ALI ~ 2023-01-28  ##
						$Flag_Process	= 'N';	
						if($Order_Type === 'INS'){
							$num_Bast		= $this->db->get_where('insitu_letters',array('order_code'=>$Code_Order))->num_rows();
						}else{
							$num_Bast		= $this->db->get_where('bast_headers',array('order_code'=>$Code_Order,'status !='=>'CNC'))->num_rows();						
						}
						if($num_Bast > 0){
							$Flag_Process	= 'Y';
						}
						
						
						if(!isset($Arr_Det[$Nocust]) && empty($Arr_Det[$Nocust])){
							$intB++;
							$Arr_Det[$Nocust]['spk_driver_id']	= $Code_SPK;
							$Arr_Det[$Nocust]['kode']			= $Nocust;
							$Arr_Det[$Nocust]['name']			= $Customer;
							$Arr_Det[$Nocust]['flag_type']		= $Cust_Type;
							$Arr_Det[$Nocust]['address']		= $Cust_Address;
							$Arr_Det[$Nocust]['urut']			= $intB;
							
							
						}
						
						
						if($rows_Detail){
							foreach($rows_Detail as $keyDetail=>$valDetail){
								$intP++;
								$intL++;
								$Code_DetOrder		= $valDetail->code_detail;
								$Code_Reff			= $valDetail->code_process;
								$Qty_DetOrder		= $valDetail->qty;
								$Code_Tool			= $valDetail->tool_id;
								$Name_Tool			= $valDetail->tool_name;
								
								$Code_SPK_Tool		= $Code_SPK.'-'.$intL;
								
								## UPDATE TRANS ORDER DETAIL ##
								$Upd_Order_Detail		= "UPDATE trans_driver_order_details SET qty_pros = qty_pros + ".$Qty_DetOrder.", spk_driver_tool_id = '".$Code_SPK_Tool."' WHERE code_detail = '".$Code_DetOrder."'";
								$Has_Upd_Order_Detail	= $this->db->query($Upd_Order_Detail);
								if($Has_Upd_Order_Detail !== TRUE){
									$Pesan_Error	= 'Error Update Trans Order Detail';
								}
								
								
								
								
								##  MODIFIED BY ALI ~ 2023-01-28  ##
								$Code_Bast			= '';
								$OK_Trans			= 0;
								if($Cust_Type === 'CUST' && $Order_Type === 'REC'){
									$Letter_Order	= '';
									$Teknisi		= '';
								}else{
									$OK_Trans		= 1;
									$Query_SO	= "SELECT letter_order_id, teknisi_name, qty_send, qty_send_real, qty_subcon_send, qty_subcon_send_real, qty_subcon_rec, qty_subcon_rec_real FROM trans_details WHERE id = '".$Code_Reff."'";
									//echo $Query_SO.'<br>';
									$rows_SO	= $this->db->query($Query_SO)->row();
									if($rows_SO){
										$Letter_Order	= $rows_SO->letter_order_id;
										$Teknisi		= $rows_SO->teknisi_name;
										
										if($Order_Type === 'INS'){
											$rows_Bast		= $this->db->get_where('insitu_letters',array('order_code'=>$Code_Order,'letter_order_id'=>$Letter_Order))->row();
										}else{
											$rows_Bast		= $this->db->get_where('bast_headers',array('order_code'=>$Code_Order,'status !='=>'CNC','letter_order_id'=>$Letter_Order))->row();
										}
										if($rows_Bast){
											$Code_Bast	= $rows_Bast->id;
											unset($rows_Bast);
										}
									}
								}
								//echo"Category : ".$Teknisi." Order Type : ".$Letter_Order."<br>";
								
								
								$OK_Pick	= $OK_Send	= 0;
								$Arr_Loc	= $Arr_Loc_IO = array();
								if($Order_Type === 'INS'){
									$Arr_Det[$Nocust]['flag_insitu']		= 'Y';
									$OK_Pick	= $OK_Send	= 1;
									
									if(!isset($Arr_Teknisi[$Nocust][$Teknisi]) && empty($Arr_Teknisi[$Nocust][$Teknisi])){
										$Arr_Teknisi[$Nocust][$Teknisi]	=  $Teknisi;
									}
									
									$Arr_Loc['spk_pick_driver_id']			= $Code_SPK;
									$Arr_Loc['spk_pick_driver_detail_id']	= $Code_SPK.'-'.$Arr_Det[$Nocust]['urut'];
									$Arr_Loc['spk_pick_driver_nomor']		= $Nomor_SPK;
									$Arr_Loc['spk_pick_driver_date']		= $Date_SPK;
									$Arr_Loc['pick_driver_id']				= $Code_Driver;
									$Arr_Loc['pick_driver_name']			= $Name_Driver;
									$Arr_Loc['flag_cust_pick']				= 'Y';									
									$Arr_Loc['spk_send_driver_id']			= $Code_SPK;
									$Arr_Loc['spk_send_driver_detail_id']	= $Code_SPK.'-'.$Arr_Det[$Nocust]['urut'];
									$Arr_Loc['spk_send_driver_nomor']		= $Nomor_SPK;
									$Arr_Loc['spk_send_driver_date']		= $Date_SPK;
									$Arr_Loc['send_driver_id']				= $Code_Driver;
									$Arr_Loc['send_driver_name']			= $Name_Driver;
									$Arr_Loc['flag_cust_send']				= 'Y';
									
									
									
									
									
									$Arr_Hist[$intL]['spk_driver_id']		= $Code_SPK;
									$Arr_Hist[$intL]['id']					= $Code_SPK_Tool;
									$Arr_Hist[$intL]['tool_id']				= $Code_Tool;
									$Arr_Hist[$intL]['tool_name']			= $Name_Tool;
									$Arr_Hist[$intL]['letter_order_id']		= $Letter_Order;
									$Arr_Hist[$intL]['schedule_detail_id']	= $Code_Reff;
									$Arr_Hist[$intL]['kode']				= $Nocust;
									$Arr_Hist[$intL]['name']				= $Customer;
									$Arr_Hist[$intL]['type']				= 'INS';
									$Arr_Hist[$intL]['category']			= ($Cust_Type=='CUST')?$Cust_Type:'SUB';
									$Arr_Hist[$intL]['qty']					= $Qty_DetOrder;
									$Arr_Hist[$intL]['qty_proses']			= 0;
									$Arr_Hist[$intL]['flag_proses']			= 'N';
									$Arr_Hist[$intL]['teknisi']				= $Teknisi;
									
								}else if($Order_Type === 'DEL'){
									$Arr_Det[$Nocust]['flag_send']		= 'Y';
									$Kode_Unik							= $Letter_Order.'-'.$Cust_Type.'-SEND-'.$Nocust;
									$OK_Send							= 1;
									if(!isset($Arr_BAST[$Kode_Unik]) && empty($Arr_BAST[$Kode_Unik])){
										
										##  MODIFIED BY ALI ~ 2022-12-10  ##
										$Arr_BAST[$Kode_Unik]['letter_order_id']	= $Letter_Order;
										$Arr_BAST[$Kode_Unik]['spk_driver_id']		= $Code_SPK;
										$Arr_BAST[$Kode_Unik]['kategori']			= ($Cust_Type=='CUST')?$Cust_Type:'SUB';
										$Arr_BAST[$Kode_Unik]['tipe']				= 'SEND';
										$Arr_BAST[$Kode_Unik]['comp_kode']			= $Nocust;
										$Arr_BAST[$Kode_Unik]['flag_proses']		= $Flag_Process;
									}
									
									
									$Arr_Hist[$intL]['spk_driver_id']		= $Code_SPK;
									$Arr_Hist[$intL]['id']					= $Code_SPK_Tool;
									$Arr_Hist[$intL]['tool_id']				= $Code_Tool;
									$Arr_Hist[$intL]['tool_name']			= $Name_Tool;
									$Arr_Hist[$intL]['letter_order_id']		= $Letter_Order;
									$Arr_Hist[$intL]['schedule_detail_id']	= $Code_Reff;
									$Arr_Hist[$intL]['kode']				= $Nocust;
									$Arr_Hist[$intL]['name']				= $Customer;
									$Arr_Hist[$intL]['type']				= 'SEND';
									$Arr_Hist[$intL]['category']			= ($Cust_Type=='CUST')?$Cust_Type:'SUB';
									$Arr_Hist[$intL]['qty']					= $Qty_DetOrder;
									$Arr_Hist[$intL]['qty_proses']			= 0;
									$Arr_Hist[$intL]['flag_proses']			= 'N';
									
									
									
									if($Cust_Type=='CUST'){
										$Arr_Loc['spk_send_driver_id']			= $Code_SPK;
										$Arr_Loc['spk_send_driver_detail_id']	= $Code_SPK.'-'.$Arr_Det[$Nocust]['urut'];
										$Arr_Loc['spk_send_driver_nomor']		= $Nomor_SPK;
										$Arr_Loc['spk_send_driver_date']		= $Date_SPK;
										$Arr_Loc['send_driver_id']				= $Code_Driver;
										$Arr_Loc['send_driver_name']			= $Name_Driver;
										$Arr_Loc['flag_cust_send']				= 'Y';		
										
										##  MODIFIED BY ALI ~ 2022-12-10  ##
										if($OK_Trans === 1 && $Flag_Process === 'Y'){
											$Arr_Loc['qty_send_real']				= $rows_SO->qty_send_real + $Qty_DetOrder;
											$Arr_Loc['location']					= 'Client';
										}
										
										$Arr_Loc_IO['quotation_detail_id']		= $Code_Reff;
										$Arr_Loc_IO['bast_header_id']			= $Code_Bast;
										$Arr_Loc_IO['flag_type']				= 'CUST';
										$Arr_Loc_IO['qty']						= $Qty_DetOrder;
										$Arr_Loc_IO['process_by']				= $Created_By;
										$Arr_Loc_IO['process_date']				= $Created_Date;
										$Arr_Loc_IO['trans']					= 'OUT';
										$Arr_Loc_IO['loc_from']					= 'Fine Good';
										$Arr_Loc_IO['loc_to']					= 'Client';
										
										
									}else{
										$Arr_Loc['subcon_send_spk_id']			= $Code_SPK;
										$Arr_Loc['subcon_send_spk_detail_id']	= $Code_SPK.'-'.$Arr_Det[$Nocust]['urut'];
										$Arr_Loc['subcon_send_spk_nomor']		= $Nomor_SPK;
										$Arr_Loc['subcon_send_spk_date']		= $Date_SPK;
										$Arr_Loc['subcon_send_driver_id']		= $Code_Driver;
										$Arr_Loc['subcon_send_driver_name']		= $Name_Driver;
										$Arr_Loc['flag_subcon_send']			= 'Y';
										
										##  MODIFIED BY ALI ~ 2022-12-10  ##
										if($OK_Trans === 1 && $Flag_Process === 'Y'){
											$Arr_Loc['qty_subcon_send_real']		= $rows_SO->qty_subcon_send_real + $Qty_DetOrder;
											$Arr_Loc['location']					= 'Subcon';
										}
										
										$Arr_Loc_IO['quotation_detail_id']		= $Code_Reff;
										$Arr_Loc_IO['bast_header_id']			= $Code_Bast;
										$Arr_Loc_IO['flag_type']				= 'SUPP';
										$Arr_Loc_IO['qty']						= $Qty_DetOrder;
										$Arr_Loc_IO['process_by']				= $Created_By;
										$Arr_Loc_IO['process_date']				= $Created_Date;
										$Arr_Loc_IO['trans']					= 'OUT';
										$Arr_Loc_IO['loc_from']					= 'Warehouse';
										$Arr_Loc_IO['loc_to']					= 'Subcon';
										
									}
									
								}else if($Order_Type === 'REC'){
									$OK_Pick	= 1;
									
									$Arr_Hist[$intL]['spk_driver_id']		= $Code_SPK;
									$Arr_Hist[$intL]['id']					= $Code_SPK_Tool;
									$Arr_Hist[$intL]['tool_id']				= $Code_Tool;
									$Arr_Hist[$intL]['tool_name']			= $Name_Tool;
									$Arr_Hist[$intL]['letter_order_id']		= $Letter_Order;
									$Arr_Hist[$intL]['schedule_detail_id']	= $Code_Reff;
									$Arr_Hist[$intL]['kode']				= $Nocust;
									$Arr_Hist[$intL]['name']				= $Customer;
									$Arr_Hist[$intL]['type']				= 'REC';
									$Arr_Hist[$intL]['category']			= ($Cust_Type=='CUST')?$Cust_Type:'SUB';
									$Arr_Hist[$intL]['qty']					= $Qty_DetOrder;
									$Arr_Hist[$intL]['qty_proses']			= 0;
									$Arr_Hist[$intL]['flag_proses']			= 'N';
									$Arr_Det[$Nocust]['flag_pick']			= 'Y';
									
									if($Cust_Type !== 'CUST'){
										
										$Kode_Unik							= $Letter_Order.'-'.$Cust_Type.'-REC-'.$Nocust;
										
										
										## UNTUK SEMENTARA TIDAK TERMASUK AMBIL ALAT KE CUSTOMER ##
										
										if(!isset($Arr_BAST[$Kode_Unik]) && empty($Arr_BAST[$Kode_Unik])){
										
											##  MODIFIED BY ALI ~ 2022-12-10  ##
											$Arr_BAST[$Kode_Unik]['letter_order_id']	= $Letter_Order;
											$Arr_BAST[$Kode_Unik]['spk_driver_id']		= $Code_SPK;
											$Arr_BAST[$Kode_Unik]['kategori']			= ($Cust_Type=='CUST')?$Cust_Type:'SUB';
											$Arr_BAST[$Kode_Unik]['tipe']				= 'REC';
											$Arr_BAST[$Kode_Unik]['comp_kode']			= $Nocust;
											$Arr_BAST[$Kode_Unik]['flag_proses']		= $Flag_Process;
										}
										
										
										
																			
										$Arr_Loc['subcon_pick_spk_id']			= $Code_SPK;
										$Arr_Loc['subcon_pick_spk_detail_id']	= $Code_SPK.'-'.$Arr_Det[$Nocust]['urut'];
										$Arr_Loc['subcon_pick_spk_nomor']		= $Nomor_SPK;
										$Arr_Loc['subcon_pick_spk_date']		= $Date_SPK;
										$Arr_Loc['subcon_pick_driver_id']		= $Code_Driver;
										$Arr_Loc['subcon_pick_driver_name']		= $Name_Driver;
										$Arr_Loc['flag_subcon_pick']			= 'Y';
										
										
										
									}
									/*
									else{
										$Arr_Loc['spk_pick_driver_id']			= $Code_SPK;
										$Arr_Loc['spk_pick_driver_detail_id']	= $Code_SPK.'-'.$Arr_Det[$Nocust]['urut'];
										$Arr_Loc['spk_pick_driver_nomor']		= $Nomor_SPK;
										$Arr_Loc['spk_pick_driver_date']		= $Date_SPK;
										$Arr_Loc['pick_driver_id']				= $Code_Driver;
										$Arr_Loc['pick_driver_name']			= $Name_Driver;
										$Arr_Loc['flag_cust_pick']				= 'Y';		
									}
									
									*/
									
								}
								if($Arr_Loc){
									$Has_Upd_Trans_Detail	= $this->db->update('trans_details',$Arr_Loc,array('id'=>$Code_Reff));
									if($Has_Upd_Trans_Detail !== TRUE){
										$Pesan_Error	= 'Error Update Trans Detail';
									}
								}
								
								##  MODIFIED BY ALI ~ 2022-12-10  ##
								if($Arr_Loc_IO){
									$Has_Ins_Log		= $this->db->insert('log_io_trans',$Arr_Loc_IO);
									if($Has_Ins_Log !== true){
										$Pesan_Error	= 'Error Insert Log In Out Trans...';
									}
									
								}
								
								if($OK_Pick==1){
									$intK++;
									$Arr_Pick[$Nocust][$intK]	= $Code_Reff;	
								}
								if($OK_Send==1){
									$intS++;
									$Arr_Send[$Nocust][$intS]	= $Code_Reff;
									
								}
								
								
							}
						}	
						
						##  MODIFIED BY ALI ~ 2023-01-28  ##
						if($Flag_Process === 'Y'){
							if($Order_Type === 'INS'){
								$Field_Upd_Bast		= "spk_driver_id = '".$Code_SPK."', status = 'CLS'";
								$Upd_Bast_Head		= "UPDATE insitu_letters SET ".$Field_Upd_Bast." WHERE order_code = '".$Code_Order."'";
								$Has_Upd_Bast_Head 	= $this->db->query($Upd_Bast_Head);
								if($Has_Upd_Bast_Head !== TRUE){
									$Pesan_Error	= 'Error Update Bast Insitu Header';
								}
							}else{
								$Field_Upd_Bast	= "spk_driver_id = '".$Code_SPK."'";
								if($Order_Type === 'REC'){
									$Field_Upd_Bast	.= ", receive_by = '".$Name_Driver."'";
								}else{
									$Field_Upd_Bast	.= ", sending_by = '".$Name_Driver."'";
								}
								
								$Upd_Bast_Head		= "UPDATE bast_headers SET ".$Field_Upd_Bast." WHERE order_code = '".$Code_Order."' AND status <> 'CNC'";
								$Has_Upd_Bast_Head 	= $this->db->query($Upd_Bast_Head);
								if($Has_Upd_Bast_Head !== TRUE){
									$Pesan_Error	= 'Error Update Bast Header Header';
								}
							}
						}
						
						
					}
				}
				
				if($Upd_Order){
					$Imp_Upd_Order	= implode("','",$Upd_Order);
					$Qry_Upd_Order	= "UPDATE trans_driver_orders SET sts_order ='PRO', driver_id = '".$Code_Driver."', driver_name = '".$Name_Driver."', spk_driver_code = '".$Code_SPK."', modified_by = '".$Created_By."', modified_date = '".$Created_Date."' WHERE order_code IN('".$Imp_Upd_Order."')";
					$Has_Upd_Order 	= $this->db->query($Qry_Upd_Order);
					if($Has_Upd_Order !== TRUE){
						$Pesan_Error	= 'Error Update Trans Order Header';
					}
				}
				
				$Ins_Header	= array(
					'id'			=> $Code_SPK,
					'nomor'			=> $Nomor_SPK,
					'datet'			=> $Date_SPK,
					'member_id'		=> $Code_Driver,
					'member_name'	=> $Name_Driver,
					'status'		=> 'OPN',
					'descr'			=> $Notes,
					'created_by'	=> $Created_By,
					'created_date'	=> $Created_Date
				);
				
				$Has_Ins_SPK	= $this->db->insert('spk_drivers',$Ins_Header);
				if($Has_Ins_SPK !== TRUE){
					$Pesan_Error	= 'Error Insert SPK Driver';
				}
				
				if($Arr_Hist){
					foreach($Arr_Hist as $keyHis=>$valHis){
						$Has_Ins_SPK_Tool	= $this->db->insert('spk_driver_tools',$valHis);
						if($Has_Ins_SPK_Tool !== TRUE){
							$Pesan_Error	= 'Error Insert SPK Driver Tool';
						}
					}
					
					
				}
				
				if($Arr_BAST){
					foreach($Arr_BAST as $keyBast=>$valBast){
						$Has_Ins_Outs_Bast	= $this->db->insert('bast_process_outstandings',$valBast);
						if($Has_Ins_Outs_Bast !== TRUE){
							$Pesan_Error	= 'Error Insert SPK Driver Bast Outstanding';
						}
					}
					
				}
				
				if($Arr_Det){
					$Urut_Det	= 0;
					foreach($Arr_Det as $keyDet=>$valDet){
						$Urut_Det++;
						$Ins_Detail					= array();
						$Ins_Detail					= $valDet;
						unset($Ins_Detail['urut']);
						$Code_Urut					= $valDet['kode'].'-'.$valDet['urut'];
						$Custid						= $valDet['kode'];
						$Ins_Detail['id']			= $Code_SPK.'-'.$Urut_Det;
						$Ins_Detail['flag_pick']	= (isset($valDet['flag_pick']) && $valDet['flag_pick'])?$valDet['flag_pick']:'N';
						$Ins_Detail['flag_insitu']	= (isset($valDet['flag_insitu']) && $valDet['flag_insitu'])?$valDet['flag_insitu']:'N';
						$Ins_Detail['flag_send']	= (isset($valDet['flag_send']) && $valDet['flag_send'])?$valDet['flag_send']:'N';
						$Pick = $Send				= '';
						$Keterangan					= '';
						
						if(isset($Arr_Pick[$Custid]) && !empty($Arr_Pick[$Custid])){
							$Pick						= implode(',',$Arr_Pick[$Custid]);
							
						}
						if(isset($Arr_Send[$Custid]) && !empty($Arr_Send[$Custid])){
							$Send						= implode(',',$Arr_Send[$Custid]);
							
						}
						if($Ins_Detail['flag_send'] == 'Y'){
							if(!empty($Keterangan))$Keterangan	.=',';
							$Keterangan	.=' Kirim Alat';
						}
						if($Ins_Detail['flag_pick']=='Y'){
							if(!empty($Keterangan))$Keterangan	.=',';
							$Keterangan	.=' Ambil Alat';
						}
						if($Ins_Detail['flag_insitu']=='Y'){
							$Tech_Name	= implode(',',$Arr_Teknisi[$Custid]);
							if(!empty($Keterangan))$Keterangan	.=',';
							$Keterangan	.=' Antar '.$Tech_Name;
						}
						
						
						$Ins_Detail['pick_id']		= $Pick;
						$Ins_Detail['send_id']		= $Send;
						$Ins_Detail['keterangan']	= $Keterangan;
						
						$Has_Ins_SPK_Det	= $this->db->insert('spk_driver_details',$Ins_Detail);
						if($Has_Ins_SPK_Det !== TRUE){
							$Pesan_Error	= 'Error Insert SPK Driver Detail';
						}						
					}
					unset($Arr_Det);
				}
				
				if ($this->db->trans_status() != TRUE || !empty($Pesan_Error)){
					$this->db->trans_rollback();
					$rows_Return		= array(
						'status'		=> 2,
						'pesan'			=> 'Save Process  Failed, '.$Pesan_Error
					);
					history('SPK Driver Process '.$Code_SPK.' - '.$Pesan_Error);
				}else{
					$this->db->trans_commit();
					$rows_Return		= array(
						'status'		=> 1,
						'pesan'			=> 'Save process success. Thank you & have a nice day......'
					);
					history('SPK Driver Process '.$Code_SPK);
				}
				
			}
						
		}
		echo json_encode($rows_Return);
	}	
}