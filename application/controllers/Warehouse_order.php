<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Warehouse_order extends CI_Controller { 
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
			'title'			=> 'WAREHOUSE ORDER',
			'action'		=> 'index',
			'akses_menu'	=> $Arr_Akses
		);
		history('View List Warehouse Order');
		$this->load->view($this->folder.'/v_warehouse_order',$data);
	}
	function get_data_display(){
		$Arr_Akses			= $this->Arr_Akses;
		
		$WHERE				= "outs_bast.flag_update = 'N'
							  AND head_spk.`status` <> 'CNC'";
		
		$requestData	= $_REQUEST;
		
		$like_value     = $requestData['search']['value'];
        $column_order   = $requestData['order'][0]['column'];
        $column_dir     = $requestData['order'][0]['dir'];
        $limit_start    = $requestData['start'];
        $limit_length   = $requestData['length'];
		
		$columns_order_by = array(
			0 => 'head_spk.nomor',
			1 => 'head_spk.datet',
			2 => 'head_spk.member_name',
			3 => 'head_spk.descr'
		);
		
		
		
		if($like_value){
			if(!empty($WHERE))$WHERE	.=" AND ";
			$WHERE	.="(
						  head_spk.nomor LIKE '%".$this->db->escape_like_str($like_value)."%'
						  OR DATE_FORMAT(head_spk.datet, '%d %b %Y') LIKE '%".$this->db->escape_like_str($like_value)."%'
						  OR head_spk.member_name LIKE '%".$this->db->escape_like_str($like_value)."%'
						  OR head_spk.descr LIKE '%".$this->db->escape_like_str($like_value)."%'
						)";
		}
		
		
		$sql = "SELECT
					head_spk.id,
					head_spk.nomor,
					head_spk.datet,
					head_spk.member_id,
					head_spk.member_name,
					head_spk.descr,
					(@row:=@row+1) AS urut
				FROM
					bast_process_outstandings outs_bast
				INNER JOIN spk_drivers head_spk ON outs_bast.spk_driver_id = head_spk.id,
				(SELECT @row:=0) r 
				WHERE ".$WHERE."
				GROUP BY 
					outs_bast.spk_driver_id";
		//print_r($sql);exit();
		$fetch['totalData'] 	= $this->db->query($sql)->num_rows();
		$fetch['totalFiltered']	= $this->db->query($sql)->num_rows();

		

		$sql .= " ORDER BY head_spk.datet DESC,".$columns_order_by[$column_order]." ".$column_dir." ";
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
			
			$Code_Process	= $row['id'];
			$Nomor_SPK		= $row['nomor'];
			$Date_SPK		= date('d-m-Y',strtotime($row['datet']));
			$Code_Driver	= $row['member_id'];
			$Name_Driver	= $row['member_name'];
			$Keterangan		= $row['descr'];
			
			
			$Template		="<a href='".site_url('Warehouse_order/spk_detail_preview?spk='.urlencode($Code_Process))."' class='btn btn-sm bg-navy-active' title='VIEW DETAIL'> <i class='fa fa-long-arrow-right'></i> </a>";
			
			
			$nestedData		= array();
			$nestedData[]	= $Nomor_SPK;
			$nestedData[]	= $Date_SPK;
			$nestedData[]	= $Name_Driver;
			$nestedData[]	= $Keterangan;
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
	
	function spk_detail_preview(){
		$rows_header	= $rows_detail = array();
		if($this->input->get()){
			$Code_SPK		= urldecode($this->input->get('spk'));
			$rows_header	= $this->db->get_where('spk_drivers',array('id'=>$Code_SPK))->result();
			$Query_Detail	= "SELECT
								detail.spk_driver_id,
								detail.letter_order_id,
								letter.no_so,
								detail.kategori,
								detail.tipe,
								detail.comp_kode,
								detail.flag_proses,
								detail.flag_print,
								detail.flag_update,
								x_bast.id AS bast_id
							FROM
								bast_process_outstandings detail
							INNER JOIN letter_orders letter ON detail.letter_order_id = letter.id
							LEFT JOIN (
								SELECT
									id,
									spk_driver_id,
									letter_order_id,

								IF (
									flag_type = 'SUPP',
									'SUB',
									flag_type
								) AS flag_comp,

							IF (
								type_bast = 'DEL',
								'SEND',
								type_bast
							) AS jenis_bast
							FROM
								bast_headers
							GROUP BY
								spk_driver_id,
								letter_order_id,
								flag_type,
								type_bast
							) x_bast ON detail.spk_driver_id = x_bast.spk_driver_id
							AND detail.letter_order_id = x_bast.letter_order_id
							AND detail.kategori = x_bast.flag_comp
							AND detail.tipe = x_bast.jenis_bast
							WHERE
								detail.spk_driver_id = '".$Code_SPK."'";
			$rows_detail	= $this->db->query($Query_Detail)->result();
		}
		
		$Arr_Akses			= $this->Arr_Akses;
		$data = array(
			'title'			=> 'WAREHOUSE ORDER PREVIEW',
			'action'		=> 'spk_detail_preview',
			'akses_menu'	=> $Arr_Akses,
			'rows_header'	=> $rows_header,
			'rows_detail'	=> $rows_detail
		);
		
		$this->load->view($this->folder.'/v_warehouse_order_preview',$data);
	}
	
	
	function create_bast_spk_driver(){
		$OK_Proses	= 0;
		$rows_Header	= $rows_Tool = $rows_Cust = $rows_SO = array();
		if($this->input->get()){
			$Code_SPK		= urldecode($this->input->get('spk'));
			$Category		= urldecode($this->input->get('jenis'));
			$Code_Unik		= urldecode($this->input->get('code'));
			$Split_Code		= explode('_',$Code_Unik);
			$Cust_Type		= (isset($Split_Code[0]) && !empty($Split_Code[0]))?$Split_Code[0]:'';
			$Cust_Code		= (isset($Split_Code[1]) && !empty($Split_Code[1]))?$Split_Code[1]:'';
			$Code_SO		= (isset($Split_Code[2]) && !empty($Split_Code[2]))?$Split_Code[2]:'';
			
			$rows_Header 	= $this->db->get_where('spk_drivers',array('id'=>$Code_SPK))->result();
			
			$WHERE			= "kode  = '".$Cust_Code."' AND category = '".$Cust_Type."' AND type = '".$Category."' AND letter_order_id = '".$Code_SO."' AND spk_driver_id = '".$Code_SPK."'";
			$Query_Tool		= "SELECT * FROM spk_driver_tools WHERE ".$WHERE;		
			$rows_Tool		= $this->db->query($Query_Tool)->result();
			$rows_SO 		= $this->db->get_where('letter_orders',array('id'=>$Code_SO))->result();
			
			if($Cust_Code){
				if($Cust_Type == 'CUST'){
					$Query_Cust	 ="SELECT id,name,address, contact FROM customers WHERE id = '".$Cust_Code."'";
				}else{
					$Query_Cust	 ="SELECT id,supplier AS name,address, cp AS contact FROM suppliers WHERE id = '".$Cust_Code."'";
				}
				
				$rows_Cust	= $this->db->query($Query_Cust)->result();
			}
			
			if($rows_Tool){
				$OK_Proses	= 1;
			}
			
			
		}
		
		if($OK_Proses == 1){
			$data = array(
				'title'			=> 'WAREHOUSE ORDER PROCESS',
				'action'		=> 'create_bast_spk_driver',
				'akses_menu'	=> $this->Arr_Akses,
				'rows_header'	=> $rows_Header,
				'rows_detail'	=> $rows_Tool,
				'rows_so'		=> $rows_SO,
				'rows_cust'		=> $rows_Cust,
				'flag_type'		=> $Cust_Type,
				'jenis'			=> $Category
			);
			
			$this->load->view($this->folder.'/v_warehouse_order_process',$data);
		}else{
			$this->session->set_flashdata("alert_data", "<div class=\"alert alert-warning\" id=\"flash-message\">No record was found to process....</div>");
			redirect(site_url('Warehouse_order'));
		}
	}

	
	function save_bast_spk_driver(){
		$rows_Return	= array(
			'status'		=> 2,
			'pesan'			=> 'No Record was found...'
		);
		if($this->input->post()){
			//echo"<pre>";print_r($this->input->post());exit;
			
			$Created_By		= $this->session->userdata('siscal_userid');
			$Created_Date	= date('Y-m-d H:i:s');
			
			$Bast_Date		= $this->input->post('datet');
			$Cust_Type		= $this->input->post('flag_type');
			$Bast_Type		= $this->input->post('type_bast');
			$Code_SPK		= $this->input->post('spk_driver_id');
			$Code_SO		= $this->input->post('letter_order_id');
			$Receive_By		= $Send_By = '';
			$Nomor_SO		= $this->input->post('no_so');
			$Customer_SO	= $this->input->post('customer_so');
			$Cust_Name		= $this->input->post('name');
			$Cust_Code		= $this->input->post('kode');
			$PIC_Name		= $this->input->post('pic');
			$Address		= $this->input->post('address');
			$Notes			= $this->input->post('notes');
			$detDetail		= $this->input->post('detDetail');
			if($this->input->post('sending_by')){
				$Send_By	= $this->input->post('sending_by');
			}
			
			if($this->input->post('receive_by')){
				$Receive_By	= $this->input->post('receive_by');
			}
			
			
			$YearMonth	= date('Y-m',strtotime($Bast_Date));
			$YM_Short	= date('Ym',strtotime($Bast_Date));
			$Year		= date('Y',strtotime($Bast_Date));
			$Month		= date('n',strtotime($Bast_Date));
			
			$CodePros	= ($Cust_Type == 'CUST')?'C':'S';
			$TypePros	= ($Bast_Type == 'REC')?'R':'S';
			$romawi		= getRomawi($Month);
			
			$Urut_Code	= 1;
			$Query_Urut	= "SELECT MAX(CAST(SUBSTRING_INDEX(id, '-', -1) AS UNSIGNED)) as urut FROM bast_headers WHERE datet LIKE '".$YearMonth."-%' LIMIT 1";
			$rows_Urut	= $this->db->query($Query_Urut)->result();
			if($rows_Urut){
				$Urut_Code	= intval($rows_Urut[0]->urut) + 1;
			}
			
			$Code_Bast	= 'BAST-'.$YM_Short.'-'.sprintf('%04d',$Urut_Code);
			
			$Urut_Nomor	= 1;
			$Query_Nomor	= "SELECT MAX(CAST(SUBSTRING_INDEX(nomor, '/', 1) AS UNSIGNED)) as urut FROM bast_headers WHERE datet LIKE '".$Year."-%' AND flag_type = '".(($Cust_Type == 'CUST')?'CUST':'SUPP')."' AND type_bast = '".(($Bast_Type == 'REC')?'REC':'DEL')."' LIMIT 1";
			$rows_Nomor	= $this->db->query($Query_Nomor)->result();
			if($rows_Nomor){
				$Urut_Nomor	= intval($rows_Nomor[0]->urut) + 1;
			}
			$Nomor_Baru	= $Urut_Nomor;
			if($Urut_Nomor < 1000){
				$Nomor_Baru	= sprintf('%03d',$Urut_Nomor);
			}
			$Nomor_Bast		= $Nomor_Baru.'/N-BAST.'.$TypePros.'/'.$CodePros.'-'.$romawi.'/'.$Year;
			
			$this->db->trans_begin();
			$Pesan_Error	= '';
			
			$Ins_Header	= array(
				'id'					=> $Code_Bast,
				'nomor'					=> $Nomor_Bast,
				'datet'					=> $Bast_Date,
				'kode'					=> $Cust_Code,
				'name'					=> $Cust_Name,
				'address'				=> $Address,
				'pic'					=> $PIC_Name,
				'flag_type'				=> (($Cust_Type == 'CUST')?'CUST':'SUPP'),
				'type_bast'				=> (($Bast_Type == 'REC')?'REC':'DEL'),
				'notes'					=> $Notes,
				'status'				=> 'OPN',
				'spk_driver_id'			=> $Code_SPK,
				'created_date'			=> $Created_Date,
				'created_by'			=> $Created_By,
				'receive_by'			=> $Receive_By,
				'sending_by'			=> $Send_By,
				'letter_order_id'		=> $Code_SO,
				'no_so'					=> $Nomor_SO
			);
			
			$Has_Ins_Header		= $this->db->insert('bast_headers',$Ins_Header);
			if($Has_Ins_Header !== true){
				$Pesan_Error	= 'Error Insert BAST Header...';
			}
				
			## SPK DRIVER HEADER ##
			$Update_SPK_Head	= array(
				'status'		=>'CLS'
			);
			
			$Has_Upd_SPK_Head		= $this->db->update('spk_drivers',$Update_SPK_Head,array('id'=>$Code_SPK));
			if($Has_Upd_SPK_Head !== true){
				$Pesan_Error	= 'Error Update SPK Driver Header...';
			}
			
			
			## SPK DRIVER DETAIL ##
			
			$Update_SPK_Detail	= array();			
			$Query_SPK_Det		= "SELECT * FROM spk_driver_details WHERE spk_driver_id = '".$Code_SPK."' AND kode = '".$Cust_Code."' AND flag_type = '".(($Cust_Type == 'CUST')?'CUST':'SUPP')."'";
			$rows_SPK_Det		= $this->db->query($Query_SPK_Det)->result();
			if($rows_SPK_Det){
				$Code_SPK_detail	= $rows_SPK_Det[0]->id;
				unset($rows_SPK_Det);
				
				if($Bast_Type == 'REC'){
					$Update_SPK_Detail['flag_bast_pick']	= 'Y';
				}else{
					$Update_SPK_Detail['flag_bast_send']	= 'Y';
				}
				
				$Has_Upd_SPK_Detail	= $this->db->update('spk_driver_details',$Update_SPK_Detail,array('id'=>$Code_SPK_detail));
				if($Has_Upd_SPK_Detail !== true){
					$Pesan_Error	= 'Error Update SPK Driver Detail...';
				}
			}
			
			## SPK BAST OUSTANDING ##
			$Update_Bast_Outs	= array();			
			$Query_Bast_Outs	= "SELECT * FROM bast_process_outstandings WHERE spk_driver_id = '".$Code_SPK."' AND comp_kode = '".$Cust_Code."' AND kategori = '".(($Cust_Type == 'CUST')?'CUST':'SUB')."' AND tipe = '".(($Bast_Type == 'REC')?'REC':'SEND')."' AND letter_order_id = '".$Code_SO."'";
			$rows_Bast_Outs		= $this->db->query($Query_Bast_Outs)->result();
			if($rows_Bast_Outs){
				$Code_Bast_Outs						= $rows_Bast_Outs[0]->id;
				$Update_Bast_Outs['flag_proses']	= 'Y';
				unset($rows_Bast_Outs);
				
				$Has_Upd_Bast_Outs	= $this->db->update('bast_process_outstandings',$Update_Bast_Outs,array('id'=>$Code_Bast_Outs));
				if($Has_Upd_Bast_Outs !== true){
					$Pesan_Error	= 'Error Update Bast Driver outstanding...';
				}
			}
			
			
			
			$intL		= 0;
			$loop		= 0;
			$intH		= 0;
			
			if($detDetail){
				foreach($detDetail as $keyDetail=>$valDetail){
					$Arr_Det	= array();
					$intL++;
					$Arr_Det						= $valDetail;
					$Arr_Det['bast_header_id']		= $Code_Bast;
					$Arr_Det['qty_sisa']			= $valDetail['qty'];
					$Qty_Real						= $valDetail['qty_real'];
					$Qty_Proses						= $valDetail['qty'];
					$Arr_Det['id']					= $Code_Bast.'-'.$intL;
					
					unset($Arr_Det['trans_id']);
					unset($Arr_Det['qty_real']);
					
					$Arr_Tool							= array();
					$Arr_Tool['flag_proses']			= 'Y';
					$Arr_Tool['qty_proses']				= $Qty_Proses;
					
					$Qty_Beda							= $Qty_Real - $Qty_Proses;
					
					if($valDetail['quotation_detail_id'] !== ''){
						$Query_Trans	= "SELECT qty_send, qty_send_real, qty_subcon_send, qty_subcon_send_real, qty_subcon_rec, qty_subcon_rec_real FROM trans_details WHERE id = '".$valDetail['quotation_detail_id']."'";
						$Data_Trans		= $this->db->query($Query_Trans)->result_array();
						
						$Arr_Loc	= array();
						$Arr_Hist	= array();
						if($Cust_Type == 'CUST'){
							
							if($Bast_Type == 'REC'){
								$Arr_Loc['bast_rec_id']				= $Code_Bast;
								$Arr_Loc['bast_rec_no']				= $Nomor_Bast;
								$Arr_Loc['bast_rec_date']			= $Bast_Date;
								$Arr_Loc['bast_rec_by']				= $Created_By;
							}else{
								$Arr_Loc['bast_send_id']			= $Code_Bast;
								$Arr_Loc['bast_send_no']			= $Nomor_Bast;
								$Arr_Loc['bast_send_date']			= $Bast_Date;
								$Arr_Loc['bast_send_by']			= $Created_By;
								$Arr_Loc['qty_send_real']			= $Data_Trans[0]['qty_send_real'] + $Qty_Proses;
								if($Qty_Beda > 0){
									$Arr_Loc['qty_send']			= $Data_Trans[0]['qty_send'] - $Qty_Beda;
								}else{
									$Arr_Loc['location']			= 'Client';
								}
								
								$intH++;
								$Arr_Hist['quotation_detail_id']		= $valDetail['quotation_detail_id'];
								$Arr_Hist['bast_header_id']				= $Code_Bast;
								$Arr_Hist['flag_type']					= 'CUST';
								$Arr_Hist['qty']						= $Qty_Proses;
								$Arr_Hist['process_by']					= $Created_By;
								$Arr_Hist['process_date']				= $Created_Date;
								$Arr_Hist['trans']						= 'OUT';
								$Arr_Hist['loc_from']					= 'Fine Good';
								$Arr_Hist['loc_to']						= 'Client';
							}
						}else{
							if($Bast_Type == 'REC'){
								$Arr_Loc['subcon_bast_rec_id']		= $Code_Bast;
								$Arr_Loc['subcon_bast_rec_no']		= $Nomor_Bast;
								$Arr_Loc['subcon_bast_rec_date']	= $Bast_Date;
								$Arr_Loc['subcon_bast_rec_by']		= $Created_By;							
								if($Qty_Beda > 0){
									$Arr_Loc['qty_subcon_rec']		= $Data_Trans[0]['qty_subcon_rec'] - $Qty_Beda;
								}
							}else{
								$Arr_Loc['subcon_bast_send_id']		= $Code_Bast;
								$Arr_Loc['subcon_bast_send_no']		= $Nomor_Bast;
								$Arr_Loc['subcon_bast_send_date']	= $Bast_Date;
								$Arr_Loc['subcon_bast_send_by']		= $Created_By;
								$Arr_Loc['qty_subcon_send_real']		= $Data_Trans[0]['qty_subcon_send_real'] + $Qty_Proses;
								if($Qty_Beda > 0){
									$Arr_Loc['qty_subcon_send']		= $Data_Trans[0]['qty_subcon_send'] - $Qty_Beda;
								}else{
									$Arr_Loc['location']				= 'Subcon';
								}
								
								$intH++;
								$Arr_Hist['quotation_detail_id']			= $valDetail['quotation_detail_id'];
								$Arr_Hist['bast_header_id']				= $Code_Bast;
								$Arr_Hist['flag_type']					= 'SUPP';
								$Arr_Hist['qty']							= $Qty_Proses;
								$Arr_Hist['process_by']					= $Created_By;
								$Arr_Hist['process_date']				= $Created_Date;
								$Arr_Hist['trans']						= 'OUT';
								$Arr_Hist['loc_from']					= 'Warehouse';
								$Arr_Hist['loc_to']						= 'Subcon';
							}
						}
						
						## UPDATE TRANS DETAIL ##
						$Has_Upd_Trans_Det	= $this->db->update('trans_details',$Arr_Loc,array('id'=>$valDetail['quotation_detail_id']));
						if($Has_Upd_Trans_Det !== true){
							$Pesan_Error	= 'Error Update Trans Detail...';
						}
						
						## INSERT LOG IN OUT ##
						if($Arr_Hist){			
							$Has_Ins_Log		= $this->db->insert('log_io_trans',$Arr_Hist);
							if($Has_Ins_Log !== true){
								$Pesan_Error	= 'Error Insert Log In Out Trans...';
							}
						}
						
					}
					
					
					$Has_Upd_SPK_Tool	= $this->db->update('spk_driver_tools',$Arr_Tool,array('id'=>$valDetail['trans_id']));
					if($Has_Upd_SPK_Tool !== true){
						$Pesan_Error	= 'Error Update SPK Driver Tool...';
					}
					
					## INSERT BAST DETAIL ##
					if($Arr_Det){			
						$Has_Ins_Detail		= $this->db->insert('bast_details',$Arr_Det);
						if($Has_Ins_Detail !== true){
							$Pesan_Error	= 'Error Insert BAST Detail...';
						}
					}
				
				}
				unset($detDetail);
			}
			
			
			
			
			
			if ($this->db->trans_status() != TRUE || !empty($Pesan_Error)){
				$this->db->trans_rollback();
				$rows_Return		= array(
					'status'		=> 2,
					'pesan'			=> 'Save Process  Failed, '.$Pesan_Error
				);
				history('Add BAST Process '.$Code_Bast.' - '.$Nomor_Bast.' - '.$Pesan_Error);
			}else{
				$this->db->trans_commit();
				$rows_Return		= array(
					'status'		=> 1,
					'pesan'			=> 'Save process success. Thank you & have a nice day......'
				);
				history('Add BAST Process '.$Code_Bast.' - '.$Nomor_Bast);
			}
			
			
		}
		echo json_encode($rows_Return);
	}
	
	
	
	function print_bast($Kode_Bast=''){
		$rows_header		= $this->master_model->getArray('bast_headers',array('id'=>$Kode_Bast));		
		$rows_detail		= $this->master_model->getArray('bast_details',array('bast_header_id'=>$Kode_Bast));		
		$rows_SO			= $this->master_model->getArray('letter_orders',array('id'=>$rows_header[0]['letter_order_id']));
		$Cond_Umum			= "";
		if($rows_header[0]['flag_type'] == 'CUST'){
			$Query_Cust		= "SELECT contact,hp FROM customers WHERE id = '".$rows_header[0]['kode']."'";
			/*
			if($rows_header[0]['type_bast'] == 'REC'){
				$Cond_Umum			= "bast_rec_id = '".$Kode_Bast."' AND re_schedule = 'N'";
			}else{
				$Cond_Umum			= "bast_send_id = '".$Kode_Bast."'";
			}
			*/
		}else{
			$Query_Cust		= "SELECT cp AS contact,hp FROM suppliers WHERE id = '".$rows_header[0]['kode']."'";
			/*
			if($rows_header[0]['type_bast'] == 'REC'){
				$Cond_Umum			= "subcon_bast_rec_id = '".$Kode_Bast."'";
			}else{
				$Cond_Umum			= "subcon_bast_send_id = '".$Kode_Bast."'";
			}
			*/
		}
		
		$rows_cust			= $this->db->query($Query_Cust)->result_array();
		$rows_Quot			= $this->master_model->getArray('quotations',array('id'=>$rows_SO[0]['quotation_id']));
		
		
		
		$Arr_Detail			= array();
		if($rows_detail){
			$intL		= 0;
			foreach($rows_detail as $keyDetail=>$valDetail){
				$WHERE_New		= $Cond_Umum;
				if(!empty($WHERE_New))$WHERE_New	.=" AND ";
				$WHERE_New	.="id = '".$valDetail['quotation_detail_id']."'";
				
				$Qry_Trans		= "SELECT * FROM trans_details WHERE ".$WHERE_New." ORDER BY id DESC LIMIT 1";
				$rows_Trans		= $this->db->query($Qry_Trans)->result();
				if($rows_Trans){
					$quot_det_id	= $rows_Trans[0]->quotation_detail_id;
					if(isset($Arr_Detail[$quot_det_id]) && $Arr_Detail[$quot_det_id]){
						$Arr_Detail[$quot_det_id]['qty']				+= $valDetail['qty'];
						if(isset($Arr_Detail[$quot_det_id]['keterangan']) && $Arr_Detail[$quot_det_id]['keterangan']){
							$Arr_Detail[$quot_det_id]['keterangan']			.=', '.$valDetail['descr'];
						}else{
							$Arr_Detail[$quot_det_id]['keterangan']			= $valDetail['descr'];
						}
					}else{
						$Arr_Detail[$quot_det_id]['tool_id']				= $rows_Trans[0]->tool_id;
						$Arr_Detail[$quot_det_id]['tool_name']				= $rows_Trans[0]->tool_name;
						$Arr_Detail[$quot_det_id]['range']					= $rows_Trans[0]->range;
						$Arr_Detail[$quot_det_id]['piece_id']				= $rows_Trans[0]->piece_id;
						$Arr_Detail[$quot_det_id]['keterangan']				= $valDetail['descr'];
						$Arr_Detail[$quot_det_id]['plan_subcon_pick_date']	= $rows_Trans[0]->plan_subcon_pick_date;
						$Arr_Detail[$quot_det_id]['subcon_bast_send_no']	= $rows_Trans[0]->subcon_bast_send_no;
						$Arr_Detail[$quot_det_id]['pono']					= $rows_Trans[0]->pono;
						$Arr_Detail[$quot_det_id]['customer_id']			= $rows_Trans[0]->customer_id;
						$Arr_Detail[$quot_det_id]['customer_name']			= $rows_Trans[0]->customer_name;
						$Arr_Detail[$quot_det_id]['qty']					= $valDetail['qty'];
					}
					$Arr_Detail[$quot_det_id]['so_descr']					= $rows_Trans[0]->so_descr;
					unset($rows_Trans);
				}
			}
			
			unset($rows_detail);
		}
		
		$data 			= array(
			'title'			=> 'Print BAST',
			'action'		=> 'print_bast',
			'rows_header'	=> $rows_header[0],
			'rows_detail'	=> $Arr_Detail,
			'rows_cust'		=> $rows_cust[0],
			'rows_quot'		=> $rows_Quot[0],
			'rows_so'		=> $rows_SO[0],
			'printby'		=> $this->session->userdata('siscal_username'),
			'today' 		=> date("Y-m-d H:i:s"),
		);	
		
		
		$this->load->view($this->folder.'/print_bast',$data); 
	}
	
	
	
}