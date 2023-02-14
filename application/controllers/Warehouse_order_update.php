<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Warehouse_order_update extends CI_Controller { 
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
			'title'			=> 'UPDATE WAREHOUSE ORDER',
			'action'		=> 'index',
			'akses_menu'	=> $Arr_Akses
		);
		history('View List Warehouse Order');
		$this->load->view($this->folder.'/v_warehouse_order_update',$data);
	}
	function get_data_display(){
		$Arr_Akses			= $this->Arr_Akses;
		
		$WHERE				= "outs_bast.flag_update = 'N'
							  AND outs_bast.flag_proses = 'Y'
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
			
			
			$Template		="<a href='".site_url('Warehouse_order_update/spk_update_preview?spk='.urlencode($Code_Process))."' class='btn btn-sm bg-navy-active' title='VIEW DETAIL'> <i class='fa fa-long-arrow-right'></i> </a>";
			
			
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
	
	function spk_update_preview(){
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
		
		$this->load->view($this->folder.'/v_warehouse_order_update_preview',$data);
	}
	
	
	function update_bast_spk_driver(){
		$OK_Proses	= 0;
		$rows_header	= $rows_detail = $rows_SO = array();
		if($this->input->get()){
			$Code_BAST		= urldecode($this->input->get('code'));
			$rows_header	= $this->db->get_where('bast_headers',array('id'=>$Code_BAST))->result();
			
			$rows_detail	= $this->db->get_where('bast_details',array('bast_header_id'=>$Code_BAST))->result();
			$rows_SO 		= $this->db->get_where('letter_orders',array('id'=>$rows_header[0]->letter_order_id))->result();
		}
		
		
		
		$Arr_Akses			= $this->Arr_Akses;
		$data = array(
			'title'			=> 'WAREHOUSE ORDER UPDATE PROCESS',
			'action'		=> 'update_bast_spk_driver',
			'akses_menu'	=> $Arr_Akses,
			'rows_header'	=> $rows_header,
			'rows_detail'	=> $rows_detail,
			'rows_so'		=> $rows_SO
		);
		
		$this->load->view($this->folder.'/v_warehouse_order_update_process',$data);
		
		
		
	}

	
	function save_update_bast_spk_driver(){
		$rows_Return	= array(
			'status'		=> 2,
			'pesan'			=> 'No Record was found...'
		);
		if($this->input->post()){
			//echo"<pre>";print_r($this->input->post());exit;
			
			$Created_By		= $this->session->userdata('siscal_userid');
			$Created_Date	= date('Y-m-d H:i:s');
			
			$Code_BAST		= $this->input->post('code_bast');
			$Cust_Type		= $this->input->post('flag_type');
			$Bast_Type		= $this->input->post('type_bast');
			$Code_SPK		= $this->input->post('spk_driver_id');
			$Code_SO		= $this->input->post('letter_order_id');
			$Receive_By		= $Send_By = '';
			$Nomor_SO		= $this->input->post('no_so');
			$Customer_SO	= $this->input->post('customer_so');
			$Cust_Name		= $this->input->post('name');
			$Cust_Code		= $this->input->post('kode');
			$Receive_Cust	= $this->input->post('cust_receive_by');
			$detDetail		= $this->input->post('detDetail');
			
			
			
			$rows_Exist		= $this->db->get_where('bast_headers',array('id'=>$Code_BAST))->result();
			$OK_Proses		= 1;
			$Pesan_Error	= '';
			if($rows_Exist){
				if($rows_Exist[0]->status !== 'OPN'){
					$OK_Proses		= 0;
					$Pesan_Error	= 'Data has been modified by other process.....';
				}
			}else{
				$OK_Proses		= 0;
				$Pesan_Error	= 'BAST not found.....';
			}
			
			if($OK_Proses === 0){
				$rows_Return	= array(
					'status'		=> 2,
					'pesan'			=> $Pesan_Error
				);
			}else{
				
				$this->db->trans_begin();
				
				$Upd_Header		= array(
					'cust_receive_by'	=> $Receive_Cust,
					'status'			=> 'CLS',
					'cust_receive_date'	=> $Created_Date,
					'modified_by'		=> $Created_By,
					'modified_date'		=> $Created_Date
				);
				
				$Has_Upd_Header		= $this->db->update('bast_headers',$Upd_Header,array('id'=>$Code_BAST));
				if($Has_Upd_Header !== true){
					$Pesan_Error	= 'Error Update BAST Header...';
				}
				
				## SPK BAST OUSTANDING ##
				$Update_Bast_Outs	= array();			
				$Query_Bast_Outs	= "SELECT * FROM bast_process_outstandings WHERE spk_driver_id = '".$Code_SPK."' AND comp_kode = '".$Cust_Code."' AND kategori = '".(($Cust_Type == 'CUST')?'CUST':'SUB')."' AND tipe = '".(($Bast_Type == 'REC')?'REC':'SEND')."' AND letter_order_id = '".$Code_SO."'";
				$rows_Bast_Outs		= $this->db->query($Query_Bast_Outs)->result();
				if($rows_Bast_Outs){
					$Code_Bast_Outs						= $rows_Bast_Outs[0]->id;
					$Update_Bast_Outs['flag_update']	= 'Y';
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
						
						$Code_Detail	= $valDetail['id'];
						$Code_Schedule	= $valDetail['quotation_detail_id'];
						$Qty_Bast		= $valDetail['qty'];
						$Qty_Proses		= $valDetail['qty_process'];
						$Descr_Detail	= strtoupper($valDetail['descr']);
						
						$Qty_Sisa		= $Qty_Bast - $Qty_Proses;
						$Upd_Detail		= array(
							'qty_io'		=> $Qty_Proses,
							'qty_sisa'		=> $Qty_Sisa,
							'descr'			=> $Descr_Detail
						);
						
						
						$Has_Upd_Detail		= $this->db->update('bast_details',$Upd_Detail,array('id'=>$Code_Detail));
						if($Has_Upd_Detail !== true){
							$Pesan_Error	= 'Error Update BAST Detail...';
						}
					
						
						$Arr_Tool							= array();
						$Arr_Tool['flag_proses']			= 'Y';
						$Arr_Tool['qty_proses']				= $Qty_Proses;
						
						
						
						if($Code_Schedule !== ''){
							$Query_Trans	= "SELECT qty_send, qty_send_real, qty_subcon_send, qty_subcon_send_real, qty_subcon_rec, qty_subcon_rec_real FROM trans_details WHERE id = '".$Code_Schedule."'";
							$Data_Trans		= $this->db->query($Query_Trans)->result_array();
							
							$Query_Loc	= array();
							$Arr_Hist	= array();
							if($Cust_Type == 'CUST'){
								
								if($Bast_Type == 'REC'){
									$Query_Loc	= "UPDATE trans_details SET receiving = 'Y', qty_rec = qty_rec + ".$Qty_Proses.", location = 'Warehouse' WHERE id ='".$Code_Schedule."'";
									
									$Arr_Hist['quotation_detail_id']		= $Code_Schedule;
									$Arr_Hist['bast_header_id']				= $Code_BAST;
									$Arr_Hist['flag_type']					= 'CUST';
									$Arr_Hist['qty']						= $Qty_Proses;
									$Arr_Hist['process_by']					= $Created_By;
									$Arr_Hist['process_date']				= $Created_Date;
									$Arr_Hist['trans']						= 'IN';
									$Arr_Hist['loc_from']					= 'Client';
									$Arr_Hist['loc_to']						= 'Warehouse';
									
								}else{
									$Query_Loc	= "UPDATE trans_details SET sending = 'Y' WHERE id ='".$Code_Schedule."'";
									if($Qty_Sisa > 0){
										$Query_Loc	= "UPDATE trans_details SET sending = 'Y', qty_send = qty_send - ".$Qty_Sisa.", qty_send_real = qty_send_real + ".$Qty_Proses.", location = 'Fine Good' WHERE id ='".$Code_Schedule."'";
									}									
								}
							}else{
								if($Bast_Type == 'REC'){
									$Query_Loc	= "UPDATE trans_details SET subcon_receiving = 'Y', qty_subcon_rec_real = qty_subcon_rec_real + ".$Qty_Proses.", location = 'Fine Good' WHERE id ='".$Code_Schedule."'";
									
									if($Qty_Sisa > 0){
										$Query_Loc	= "UPDATE trans_details SET subcon_receiving = 'Y', qty_subcon_rec_real = qty_subcon_rec_real + ".$Qty_Proses.", location = 'Fine Good',qty_subcon_rec = qty_subcon_rec - ".$Qty_Sisa." WHERE id ='".$Code_Schedule."'";
									}
									
									
									$Arr_Hist['quotation_detail_id']		= $Code_Schedule;
									$Arr_Hist['bast_header_id']				= $Code_BAST;
									$Arr_Hist['flag_type']					= 'SUPP';
									$Arr_Hist['qty']						= $Qty_Proses;
									$Arr_Hist['process_by']					= $Created_By;
									$Arr_Hist['process_date']				= $Created_Date;
									$Arr_Hist['trans']						= 'IN';
									$Arr_Hist['loc_from']					= 'Subcon';
									$Arr_Hist['loc_to']						= 'Fine Good';
									
								}else{
									$Query_Loc	= "UPDATE trans_details SET subcon_sending = 'Y' WHERE id ='".$Code_Schedule."'";
									if($Qty_Sisa > 0){
										$Query_Loc	= "UPDATE trans_details SET subcon_sending = 'Y', qty_subcon_send = qty_subcon_send - ".$Qty_Sisa.", qty_subcon_send_real = qty_subcon_send_real - ".$Qty_Sisa.", location = 'Subcon' WHERE id ='".$Code_Schedule."'";
									}
								}
							}
							
							## UPDATE TRANS DETAIL ##
							$Has_Upd_Trans_Det	= $this->db->query($Query_Loc);
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
					
					}
					unset($detDetail);
				}
				
				if ($this->db->trans_status() != TRUE || !empty($Pesan_Error)){
					$this->db->trans_rollback();
					$rows_Return		= array(
						'status'		=> 2,
						'pesan'			=> 'Save Process  Failed, '.$Pesan_Error
					);
					history('Update BAST Process '.$Code_BAST.' - '.$Pesan_Error);
				}else{
					$this->db->trans_commit();
					$rows_Return		= array(
						'status'		=> 1,
						'pesan'			=> 'Save process success. Thank you & have a nice day......'
					);
					history('Update BAST Process '.$Code_BAST);
				}
					
				
			}			
		}
		echo json_encode($rows_Return);
	}
	
	
	
	
	
	
}