<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Spk_driver_order extends CI_Controller { 
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
		$Query_Driver	= "SELECT id, nama FROM members WHERE  division_id = 'DIV-004' ORDER BY nama ASC";
		$rows_Driver	= $this->db->query($Query_Driver)->result();
		$data = array(
			'title'			=> 'SPK DRIVER ORDER',
			'action'		=> 'index',
			'akses_menu'	=> $Arr_Akses,
			'rows_driver'	=> $rows_Driver
		);
		history('View List SPK Driver Order');
		$this->load->view($this->folder.'/v_spk_driver_order',$data);
	}
	function get_data_display(){
		$Arr_Akses		= $this->Arr_Akses;
		
		$WHERE			= "1=1";
		
		$Datet_Find		= $this->input->post('tanggal');
		$Driver_Find	= $this->input->post('driver');
		$requestData	= $_REQUEST;
		
		$like_value     = $requestData['search']['value'];
        $column_order   = $requestData['order'][0]['column'];
        $column_dir     = $requestData['order'][0]['dir'];
        $limit_start    = $requestData['start'];
        $limit_length   = $requestData['length'];
		
		$columns_order_by = array(
			0 => 'nomor',
			1 => 'datet',
			2 => 'member_name',
			3 => 'descr',
			4 => 'status'
		);
		
		
		
		if($like_value){
			if(!empty($WHERE))$WHERE	.=" AND ";
			$WHERE	.="(
						  nomor LIKE '%".$this->db->escape_like_str($like_value)."%'
						  OR DATE_FORMAT(datet, '%d %b %Y') LIKE '%".$this->db->escape_like_str($like_value)."%'
						  OR member_name LIKE '%".$this->db->escape_like_str($like_value)."%'
						  OR descr LIKE '%".$this->db->escape_like_str($like_value)."%'
						  OR status LIKE '%".$this->db->escape_like_str($like_value)."%'
						)";
		}
		
		if($Datet_Find){
			if(!empty($WHERE))$WHERE .=" AND ";
			$WHERE	.="datet = '".$Datet_Find."'";
		}
		
		if($Driver_Find){
			if(!empty($WHERE))$WHERE .=" AND ";
			$WHERE	.="member_id = '".$Driver_Find."'";
		}
		
		$sql = "SELECT
					*,
					(@row:=@row+1) AS urut
				FROM
					spk_drivers,
				(SELECT @row:=0) r 
				WHERE ".$WHERE;
		//print_r($sql);exit();
		$fetch['totalData'] 	= $this->db->query($sql)->num_rows();
		$fetch['totalFiltered']	= $this->db->query($sql)->num_rows();

		

		$sql .= " ORDER BY datet DESC,".$columns_order_by[$column_order]." ".$column_dir." ";
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
			
			$Code_SPK		= $row['id'];
			$Nomor_SPK		= $row['nomor'];
			$Date_SPK		= date('d-m-Y',strtotime($row['datet']));
			$Code_Driver	= $row['member_id'];
			$Name_Driver	= $row['member_name'];
			$Notes			= strtoupper($row['descr']);
			$Status_SPK		= $row['status'];
			
			$Lable_Status	= 'OPEN';
			$Color_Status	= 'bg-green';
			if($Status_SPK === 'CNC'){
				$Lable_Status	= 'CANCELED';
				$Color_Status	= 'bg-orange';
			}else if($Status_SPK === 'CLS'){
				$Lable_Status	= 'CLOSE';
				$Color_Status	= 'bg-navy-active';
			}
			$Ket_Status		= '<span class="badge '.$Color_Status.'">'.$Lable_Status.'</span>';
			
			
			
			$Template		= '<button type="button" class="btn btn-sm btn-primary" onClick = "ActionPreview({code:\''.$Code_SPK.'\',action :\'detail_spk_driver_order\',title:\'VIEW SPK DRIVER ORDER\'});" title="VIEW SPK DRIVER ORDER"> <i class="fa fa-search"></i> </button>';			
			if(($Arr_Akses['create'] == '1' || $Arr_Akses['update'] == '1') && $Status_SPK === 'OPN'){
				$Template		.= '&nbsp;&nbsp;<button type="button" class="btn btn-sm btn-danger" onClick = "ActionPreview({code:\''.$Code_SPK.'\',action :\'cancel_spk_driver_order\',title:\'CANCEL SPK DRIVER ORDER\'});" title="CANCEL DRIVER ORDER"> <i class="fa fa-trash-o"></i> </button>';
				$Template		.= '&nbsp;&nbsp;<a href="'.site_url().'/Spk_driver_order/print_spk_driver?nomor_spk='.urlencode($Code_SPK).'" class="btn btn-sm btn-warning" target = "_blank" title="PRINT SPK DRIVER ORDER"> <i class="fa fa-print"></i> </button>';
			}
			
			
			
			
			$nestedData		= array();
			$nestedData[]	= $Nomor_SPK;
			$nestedData[]	= $Date_SPK;
			$nestedData[]	= $Name_Driver;
			$nestedData[]	= $Notes;
			$nestedData[]	= $Ket_Status;
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
	
	function detail_spk_driver_order(){
		$OK_Proses	= 0;
		$rows_Header	= $rows_Detail =  $rows_Tool = array();
		
		if($this->input->post()){
			$Code_Process	= urldecode($this->input->post('code'));
			
			$rows_Header	= $this->db->get_where('spk_drivers',array('id'=>$Code_Process))->row();
			$rows_Detail	= $this->db->get_where('spk_driver_details',array('spk_driver_id'=>$Code_Process))->result();
			$rows_Tool		= $this->db->get_where('spk_driver_tools',array('spk_driver_id'=>$Code_Process))->result();
			
		}
		
		
		$data = array(
			'title'			=> 'SPK DRIVER ORDER PREVIEW',
			'action'		=> 'detail_spk_driver_order',
			'akses_menu'	=> $this->Arr_Akses,
			'rows_header'	=> $rows_Header,
			'rows_detail'	=> $rows_Detail,
			'rows_tool'		=> $rows_Tool,
			'category'		=> 'view'
		);
		
		$this->load->view($this->folder.'/v_spk_driver_order_preview',$data);
		
	}
	
	function print_spk_driver(){
		$rows_Header	= $rows_Detail =  $rows_Tool = array();
		
		if($this->input->get()){
			$Code_Process	= urldecode($this->input->get('nomor_spk'));
			
			$rows_Header	= $this->db->get_where('spk_drivers',array('id'=>$Code_Process))->row_array();
			$rows_Detail	= $this->db->get_where('spk_driver_details',array('spk_driver_id'=>$Code_Process))->result_array();
			$rows_Tool		= $this->db->get_where('spk_driver_tools',array('spk_driver_id'=>$Code_Process))->result_array();
			
		}
		
		
		$data = array(
			'title'			=> 'SPK DRIVER ORDER PRINT',
			'action'		=> 'print_spk_driver',
			'rows_header'	=> $rows_Header,
			'rows_detail'	=> $rows_Detail,
			'rows_tool'		=> $rows_Tool
		);
		
		$this->load->view($this->folder.'/v_spk_driver_order_print',$data);
	}
	
	function cancel_spk_driver_order(){
		
		$rows_Header	= $rows_Detail =  $rows_Tool = array();
		
		if($this->input->post()){
			$Code_Process	= urldecode($this->input->post('code'));
			
			$rows_Header	= $this->db->get_where('spk_drivers',array('id'=>$Code_Process))->row();
			$rows_Detail	= $this->db->get_where('spk_driver_details',array('spk_driver_id'=>$Code_Process))->result();
			$rows_Tool		= $this->db->get_where('spk_driver_tools',array('spk_driver_id'=>$Code_Process))->result();
			
		}
		
		
		$data = array(
			'title'			=> 'SPK DRIVER ORDER CANCELLATION',
			'action'		=> 'cancel_spk_driver_order',
			'akses_menu'	=> $this->Arr_Akses,
			'rows_header'	=> $rows_Header,
			'rows_detail'	=> $rows_Detail,
			'rows_tool'		=> $rows_Tool,
			'category'		=> 'cancel'
		);
		
		$this->load->view($this->folder.'/v_spk_driver_order_preview',$data);
	}
	
	
	function save_cancel_spk_driver_order(){
		$rows_Return	= array(
			'status'		=> 2,
			'pesan'			=> 'No Record was found...'
		);
		if($this->input->post()){
			//echo"<pre>";print_r($this->input->post());exit;
			
			$Created_By		= $this->session->userdata('siscal_userid');
			$Created_Name	= $this->session->userdata('siscal_username');
			$Created_Date	= date('Y-m-d H:i:s');
			
			$Code_Order		= $this->input->post('code_order');
			$Cancel_Reason	= strtoupper($this->input->post('cancel_reason'));
			
			$Find_Exist		= $this->db->get_where('spk_drivers',array('id'=>$Code_Order))->row();
			if($Find_Exist){
				if($Find_Exist->status !== 'OPN'){
					$rows_Return	= array(
						'status'		=> 2,
						'pesan'			=> 'Data has been modified by other process...'
					);
				}else{
					$this->db->trans_begin();
					$Pesan_Error	= '';
					
					##  MODIFIED BY ALI ~ 2022-12-12  ##
					$Flag_Process	= 'N';		
					$Query_Bast		= "SELECT * FROM bast_headers WHERE spk_driver_id = '".$Code_Order."' AND status NOT IN('CNC') AND NOT(order_code IS NULL OR order_code = '' OR order_code ='-')";
					$num_Bast		= $this->db->query($Query_Bast)->num_rows();
					if($num_Bast > 0){
						$Flag_Process	= 'Y';
					}
					
					## AMBIL DATA SPK DETAIL TOOL ##
					$rows_SPK_Tool		= $this->db->get_where('spk_driver_tools',array('spk_driver_id'=>$Code_Order))->result();
					if($rows_SPK_Tool){
						$intL	= 0;
						foreach($rows_SPK_Tool as $keyTool=>$valTool){
							$intL++;
							$Code_Cust		= $valTool->kode;
							$Code_Detail	= $valTool->id;
							$Code_Trans		= $valTool->schedule_detail_id;
							$Qty_Trans		= $valTool->qty;
							$Category_Trans	= $valTool->category;
							$Type_Trans		= $valTool->type;
							
							$Query_SO	= "SELECT letter_order_id, teknisi_name, qty_send, qty_send_real, qty_subcon_send, qty_subcon_send_real, qty_subcon_rec, qty_subcon_rec_real FROM trans_details WHERE id = '".$Code_Trans."'";
							//echo $Query_SO.'<br>';
							$rows_SO	= $this->db->query($Query_SO)->row();
							
							## UPDATE TRANS ORDER DETAIL ##
							$Upd_Order_Detail		= "UPDATE trans_driver_order_details SET qty_pros = qty_pros - ".$Qty_Trans.", spk_driver_tool_id = NULL WHERE spk_driver_tool_id = '".$Code_Detail."'";
							$Has_Upd_Order_Detail	= $this->db->query($Upd_Order_Detail);
							if($Has_Upd_Order_Detail !== TRUE){
								$Pesan_Error	= 'Error Update Trans Order Detail';
							}
							
							$Arr_Upd_Trans	= array();
							if($Type_Trans == 'INS'){
								$Arr_Upd_Trans['spk_pick_driver_id']		= NULL;
								$Arr_Upd_Trans['spk_pick_driver_detail_id']	= NULL;
								$Arr_Upd_Trans['spk_pick_driver_nomor']		= NULL;
								$Arr_Upd_Trans['spk_pick_driver_date']		= NULL;
								$Arr_Upd_Trans['pick_driver_id']			= NULL;
								$Arr_Upd_Trans['pick_driver_name']			= NULL;
								$Arr_Upd_Trans['flag_cust_pick']			= 'N';									
								$Arr_Upd_Trans['spk_send_driver_id']		= NULL;
								$Arr_Upd_Trans['spk_send_driver_detail_id']	= NULL;
								$Arr_Upd_Trans['spk_send_driver_nomor']		= NULL;
								$Arr_Upd_Trans['spk_send_driver_date']		= NULL;
								$Arr_Upd_Trans['send_driver_id']			= NULL;
								$Arr_Upd_Trans['send_driver_name']			= NULL;
								$Arr_Upd_Trans['flag_cust_send']			= 'N';
							}else if($Type_Trans == 'REC'){
								if($Category_Trans !== 'CUST'){
									## UNTUK SEMENTARA TIDAK TERMASUK AMBIL ALAT KE CUSTOMER ##
									$Arr_Upd_Trans['subcon_pick_spk_id']		= NULL;
									$Arr_Upd_Trans['subcon_pick_spk_detail_id']	= NULL;
									$Arr_Upd_Trans['subcon_pick_spk_nomor']		= NULL;
									$Arr_Upd_Trans['subcon_pick_spk_date']		= NULL;
									$Arr_Upd_Trans['subcon_pick_driver_id']		= NULL;
									$Arr_Upd_Trans['subcon_pick_driver_name']	= NULL;
									$Arr_Upd_Trans['flag_subcon_pick']			= 'N';
									
									
								}
								/*
								else{
									$Arr_Upd_Trans['spk_pick_driver_id']		= NULL;
									$Arr_Upd_Trans['spk_pick_driver_detail_id']	= NULL;
									$Arr_Upd_Trans['spk_pick_driver_nomor']		= NULL;
									$Arr_Upd_Trans['spk_pick_driver_date']		= NULL;
									$Arr_Upd_Trans['pick_driver_id']			= NULL;
									$Arr_Upd_Trans['pick_driver_name']			= NULL;
									$Arr_Upd_Trans['flag_cust_pick']			= 'N';	
								}
								*/
							}else {
								if($Category_Trans=='CUST'){
									$Arr_Upd_Trans['spk_send_driver_id']		= NULL;
									$Arr_Upd_Trans['spk_send_driver_detail_id']	= NULL;
									$Arr_Upd_Trans['spk_send_driver_nomor']		= NULL;
									$Arr_Upd_Trans['spk_send_driver_date']		= NULL;
									$Arr_Upd_Trans['send_driver_id']			= NULL;
									$Arr_Upd_Trans['send_driver_name']			= NULL;
									$Arr_Upd_Trans['flag_cust_send']			= 'N';	
									
									##  MODIFIED BY ALI ~ 2022-12-12  ##
									if($Flag_Process === 'Y'){
										$Arr_Upd_Trans['qty_send_real']				= $rows_SO->qty_send_real - $Qty_Trans;
										$Arr_Upd_Trans['location']					= 'Fine Good';
									}
									
								}else{
									$Arr_Upd_Trans['subcon_send_spk_id']		= NULL;
									$Arr_Upd_Trans['subcon_send_spk_detail_id']	= NULL;
									$Arr_Upd_Trans['subcon_send_spk_nomor']		= NULL;
									$Arr_Upd_Trans['subcon_send_spk_date']		= NULL;
									$Arr_Upd_Trans['subcon_send_driver_id']		= NULL;
									$Arr_Upd_Trans['subcon_send_driver_name']	= NULL;
									$Arr_Upd_Trans['flag_subcon_send']			= 'N';
									
									##  MODIFIED BY ALI ~ 2022-12-12  ##
									if($Flag_Process === 'Y'){
										$Arr_Upd_Trans['qty_subcon_send_real']		= $rows_SO->qty_subcon_send_real - $Qty_Trans;
										$Arr_Upd_Trans['location']					= 'Warehouse';
									}
									
								}
							}
							
							if($Arr_Upd_Trans){
								$Has_Upd_Trans_Detail	= $this->db->update('trans_details',$Arr_Upd_Trans,array('id'=>$Code_Trans));
								if($Has_Upd_Trans_Detail !== TRUE){
									$Pesan_Error	= 'Error Update Trans Detail';
								}
							}
							
						}
					}
					
					##  MODIFIED BY ALI ~ 2023-01-28  ##
					if($Flag_Process === 'Y'){
						if($Type_Trans == 'REC'){
							$Field_Upd_Bast	= "spk_driver_id = NULL, status = 'OPN'";					
							$Upd_Bast_Head		= "UPDATE insitu_letters SET ".$Field_Upd_Bast." WHERE spk_driver_id = '".$Code_Order."'";
							$Has_Upd_Bast_Head 	= $this->db->query($Upd_Bast_Head);
							if($Has_Upd_Bast_Head !== TRUE){
								$Pesan_Error	= 'Error Update Bast Insitu Header';
							}
						}else{
							$Field_Upd_Bast	= "spk_driver_id = NULL, receive_by = NULL, sending_by = NULL";					
							$Upd_Bast_Head		= "UPDATE bast_headers SET ".$Field_Upd_Bast." WHERE spk_driver_id = '".$Code_Order."' AND status <> 'CNC'";
							$Has_Upd_Bast_Head 	= $this->db->query($Upd_Bast_Head);
							if($Has_Upd_Bast_Head !== TRUE){
								$Pesan_Error	= 'Error Update Bast Header Header';
							}
						}
						
					}
					
					$Qry_Upd_Order	= "UPDATE trans_driver_orders SET sts_order ='OPN', driver_id = NULL, driver_name = NULL, spk_driver_code = NULL, modified_by = '".$Created_By."', modified_date = '".$Created_Date."' WHERE spk_driver_code = '".$Code_Order."'";
					$Has_Upd_Order 	= $this->db->query($Qry_Upd_Order);
					if($Has_Upd_Order !== TRUE){
						$Pesan_Error	= 'Error Update Trans Order Header';
					}
					
					$Del_Header		= "DELETE FROM spk_drivers WHERE id = '".$Code_Order."'";
					$Has_Del_Header	= $this->db->query($Del_Header);
					if($Has_Del_Header !== TRUE){
						$Pesan_Error	= 'Error Delete SPK Driver';
					}
					
					$Del_Detail		= "DELETE FROM spk_driver_details WHERE spk_driver_id = '".$Code_Order."'";
					$Has_Del_Detail	= $this->db->query($Del_Detail);
					if($Has_Del_Detail !== TRUE){
						$Pesan_Error	= 'Error Delete SPK Driver Detail';
					}
					
					$Del_Detail_Tool		= "DELETE FROM spk_driver_tools WHERE spk_driver_id = '".$Code_Order."'";
					$Has_Del_Detail_Tool	= $this->db->query($Del_Detail_Tool);
					if($Has_Del_Detail_Tool !== TRUE){
						$Pesan_Error	= 'Error Delete SPK Driver Tool';
					}
					
					
					$Del_Outs_Bast	= "DELETE FROM bast_process_outstandings WHERE spk_driver_id = '".$Code_Order."'";
					$Has_Del_Bast	= $this->db->query($Del_Outs_Bast);
					if($Has_Del_Bast !== TRUE){
						$Pesan_Error	= 'Error Delete Bast Outstanding';
					}
					
					
					if ($this->db->trans_status() != TRUE || !empty($Pesan_Error)){
						$this->db->trans_rollback();
						$rows_Return		= array(
							'status'		=> 2,
							'pesan'			=> 'Cancellation Process  Failed, '.$Pesan_Error
						);
						history('Cancellation SPK Driver Order '.$Code_Order.' - '.$Pesan_Error);
					}else{
						$this->db->trans_commit();
						$rows_Return		= array(
							'status'		=> 1,
							'pesan'			=> 'Cancellation process success. Thank you & have a nice day......'
						);
						history('Cancellation SPK Driver Order '.$Code_Order.' - '.$Cancel_Reason);
					}
					
				}
			}else{
				$rows_Return	= array(
					'status'		=> 2,
					'pesan'			=> 'No Record was found...'
				);
			}
			

						
		}
		echo json_encode($rows_Return);
	}	
}