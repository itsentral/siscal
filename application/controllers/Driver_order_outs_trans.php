<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Driver_order_outs_trans extends CI_Controller { 
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
		$Arr_CustType		= array(
			'CUST'	=> 'Customer',
			'SUB'	=> 'Subcon'
		);
		$Arr_ProsType		= array(
			'REC'	=> 'PICKUP TOOL',
			'DEL'	=> 'SEND TOOL',
			'INS'	=> 'INSITU'
		);
		$data = array(
			'title'			=> 'OUTSTANDING DRIVER ORDER - TRANSACTION DATA',
			'action'		=> 'index',
			'akses_menu'	=> $Arr_Akses,
			'rows_category'	=> $Arr_CustType,
			'rows_type'		=> $Arr_ProsType
		);
		history('View Outstanding Driver Order - Transaction Data');
		$this->load->view($this->folder.'/v_outs_trans_driver_order',$data);
	}
	
	function get_company_data(){
		$Template	= '<option value=""> - Empty List - </option>';
		if($this->input->post()){
			$Category 	= $this->input->post('category');
			$Selected	= $this->input->post('selected');
			if($Category){
				if(strtolower($Category) == 'cust'){
					$WHERE		= "
							(
								insitu = 'Y'
								AND (
									flag_cust_pick = 'N'
									OR flag_cust_send = 'N'
								)
							)
						OR (
							qty_rec > 0
							AND flag_process = 'Y'
							AND (

								IF (
									(qty_proses + qty_fail) > qty_rec,
									qty_rec,
									(qty_proses + qty_fail)
								) - qty_send
							) > 0
							AND location = 'Fine Good' /*
							AND (
								(
									(
										spk_send_driver_id = ''
										OR spk_send_driver_id IS NULL
									)
									AND qty_send = 0
								)
								OR (
									qty_send > 0
									AND NOT (
										spk_send_driver_id = ''
										OR spk_send_driver_id IS NULL
									)
								)
							)
							*/
						)
						
					";
					$Group_By	= "customer_id";
					$Field_Find	= "customer_id AS code_comp, customer_name AS name_comp";
				}else{
					$Field_Find	= "supplier_id AS code_comp, supplier_name AS name_comp";
					$Group_By	= "supplier_id";
					$WHERE	= "(
									location = 'Subcon'
									AND subcon = 'Y'
									AND qty_rec > 0
									AND (
										qty_subcon_send - qty_subcon_rec
									) > 0
									AND qty_subcon_send > 0 /*
									AND (
										(
											(
												subcon_pick_driver_id = ''
												OR subcon_pick_driver_id IS NULL
											)
											AND qty_subcon_rec = 0
										)
										OR (
											NOT (
												subcon_pick_driver_id = ''
												OR subcon_pick_driver_id IS NULL
											)
											AND qty_subcon_rec > 0
										)
									)
								*/
								)
							OR (
								location = 'Warehouse'
								AND subcon = 'Y'
								AND (
									qty_rec - qty_subcon_send - qty_reschedule
								) > 0
								AND qty_rec > 0 
								/*
								AND (
									(
										(
											subcon_send_driver_id = ''
											OR subcon_send_driver_id IS NULL
										)
										AND qty_subcon_send = 0
									)
									OR (
										NOT (
											subcon_send_driver_id = ''
											OR subcon_send_driver_id IS NULL
										)
										AND qty_subcon_send > 0
									)
								)
								*/
							)";
				}
				
				$Query_Trans	= "SELECT ".$Field_Find." FROM trans_details WHERE ".$WHERE." GROUP BY ".$Group_By;
				$rows_Comp		= $this->db->query($Query_Trans)->result();
				if($rows_Comp){
					$Template	= '<option value=""> - Select An Option - </option>';
					foreach($rows_Comp as $keyComp=>$valComp){
						$Code_Comp	= $valComp->code_comp;
						$Name_Comp	= strtoupper($valComp->name_comp);
						$Template	.= '<option value="'.$Code_Comp.'">'.$Name_Comp.'</option>';
					}
				}
			}
			
		}
		echo $Template;
	}
	
	function get_data_display(){
		$Arr_Akses			= $this->Arr_Akses;
		$Cat_Find			= $this->input->post('category');
		$Cust_Find			= $this->input->post('nocust');
		$Type_Find			= $this->input->post('tipe');
		$WHERE				= '';
		
		if($Cust_Find){
			if(!empty($WHERE))$WHERE	.=" AND ";
			if($Cat_Find == 'SUB'){
				$WHERE	.="supplier_id = '".$Cust_Find."'";
			}else{				
				$WHERE	.="customer_id = '".$Cust_Find."'";
			}
		}
		if($Type_Find){
			if(!empty($WHERE))$WHERE	.=" AND ";
			if(strtolower($Type_Find) == 'ins'){
				$WHERE	.="(
								insitu = 'Y'
								AND (
									flag_cust_pick = 'N'
									OR flag_cust_send = 'N'
								)
							)";
			}else if(strtolower($Type_Find) == 'del'){
				if($Cat_Find == 'SUB'){
					$WHERE	.="
							(
								location = 'Warehouse'
								AND subcon = 'Y'
								AND (
									qty_rec - qty_subcon_send - qty_reschedule
								) > 0
								AND qty_rec > 0 
								/*
								AND (
									(
										(
											subcon_send_driver_id = ''
											OR subcon_send_driver_id IS NULL
										)
										AND qty_subcon_send = 0
									)
									OR (
										NOT (
											subcon_send_driver_id = ''
											OR subcon_send_driver_id IS NULL
										)
										AND qty_subcon_send > 0
									)
								)
								*/
							)
							";
				}else{				
					$WHERE	.="
							(
								qty_rec > 0
								AND flag_process = 'Y'
								AND (

									IF (
										(qty_proses + qty_fail) > qty_rec,
										qty_rec,
										(qty_proses + qty_fail)
									) - qty_send
								) > 0
								AND location = 'Fine Good' /*
								AND (
									(
										(
											spk_send_driver_id = ''
											OR spk_send_driver_id IS NULL
										)
										AND qty_send = 0
									)
									OR (
										qty_send > 0
										AND NOT (
											spk_send_driver_id = ''
											OR spk_send_driver_id IS NULL
										)
									)
								)
								*/
							)
							";
				}
			}else{
				if($Cat_Find == 'SUB'){
					$WHERE	.="
								(
									location = 'Subcon'
									AND subcon = 'Y'
									AND qty_rec > 0
									AND (
										qty_subcon_send - qty_subcon_rec
									) > 0
									AND qty_subcon_send > 0 /*
									AND (
										(
											(
												subcon_pick_driver_id = ''
												OR subcon_pick_driver_id IS NULL
											)
											AND qty_subcon_rec = 0
										)
										OR (
											NOT (
												subcon_pick_driver_id = ''
												OR subcon_pick_driver_id IS NULL
											)
											AND qty_subcon_rec > 0
										)
									)
								*/
								)
					";
				}
				
			}
		}
		
		$requestData	= $_REQUEST;
		
		$like_value     = $requestData['search']['value'];
        $column_order   = $requestData['order'][0]['column'];
        $column_dir     = $requestData['order'][0]['dir'];
        $limit_start    = $requestData['start'];
        $limit_length   = $requestData['length'];
		
		$columns_order_by = array(
			1 => 'tool_name',
			4 => 'no_so'
		);
		
		
		
		if($like_value){
			if(!empty($WHERE))$WHERE	.=" AND ";
			$WHERE	.="(
						  tool_name LIKE '%".$this->db->escape_like_str($like_value)."%'
						  OR no_so LIKE '%".$this->db->escape_like_str($like_value)."%'
						)";
		}
		
		
		$sql = "SELECT
					*,
					(@row:=@row+1) AS urut
				FROM
					trans_details,
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
			
			$Code_Detail		= $row['id'];
			$insitu				= $row['insitu'];
			$subcon				= $row['subcon'];
			$labs				= $row['labs'];
			$Lokasi				= $row['location'];
			$Qty				= $row['qty'];
			$Qty_Rec			= $row['qty_rec'];
			$Qty_Sch			= $row['qty_reschedule'];
			$Qty_Subcon_Send	= $row['qty_subcon_send'];
			$Qty_Subcon_Rec		= $row['qty_subcon_rec'];
			$Qty_Send			= $row['qty_send'];
			$Qty_Gagal			= $row['qty_fail'];
			$Qty_Sukses			= $row['qty_proses'];
			
			$OK_Process			= 1;
			if($insitu === 'Y' && $subcon === 'Y'){
				$OK_Process	= 0;
			}
			if($OK_Process === 1){
				$Customer	= strtoupper($row['customer_name']);
				$Supplier	= strtoupper($row['supplier_name']);
				$nestedData	= array();
				if($insitu === 'Y'){
					$nestedData[]	= '<input type="checkbox" id="chk_pilih_'.$Code_Detail.'" name="detPilih[]" value="'.$Code_Detail.'" class="chk_pilih">';
					$nestedData[]	= $row['tool_name'];
					$nestedData[]	= $Qty;
					$nestedData[]	= '<span class="badge bg-navy-active">INSITU</span>';
					$nestedData[]	= $row['no_so'];
					$nestedData[]	= $Customer;
					$nestedData[]	= 'ANTAR '.strtoupper($row['teknisi_name']);
					$nestedData[]	= $row['plan_process_date'];
				}else if($labs === 'Y' || $subcon === 'Y'){
					if(strtolower($Lokasi)=='fine good'){
						$Qty_Akan		= $Qty_Gagal + $Qty_Sukses;
						
						if($Qty_Akan > $Qty_Rec){
							$Qty_Akan	= $Qty_Rec;
						}
						
						$Qty_Kirim		= $Qty_Akan - $Qty_Send;
						
						if($Qty_Kirim > 0){
							$nestedData[]	= '<input type="checkbox" id="chk_pilih_'.$Code_Detail.'" name="detPilih[]" value="'.$Code_Detail.'" class="chk_pilih">';
							$nestedData[]	= $row['tool_name'];
							$nestedData[]	= $Qty_Kirim;
							$nestedData[]	= ($labs == 'Y')?'<span class="badge bg-green-active">LABS</span>':'<span class="badge bg-red-active">SUBCON</span>';
							$nestedData[]	= $row['no_so'];
							$nestedData[]	= $Customer;
							$nestedData[]	= 'ANTAR ALAT KE CUSTOMER';
							$nestedData[]	= $row['plan_delivery_date'];
						}
					}
					
					if($subcon === 'Y'){
						if(strtolower($Lokasi)=='warehouse'){
							$Qty_Kirim_Sub	= $Qty_Rec - $Qty_Subcon_Send - $Qty_Sch;
							$nestedData[]	= '<input type="checkbox" id="chk_pilih_'.$Code_Detail.'" name="detPilih[]" value="'.$Code_Detail.'" class="chk_pilih">';
							$nestedData[]	= $row['tool_name'];
							$nestedData[]	= $Qty_Kirim_Sub;
							$nestedData[]	= '<span class="badge bg-red-active">SUBCON</span>';
							$nestedData[]	= $row['no_so'];
							$nestedData[]	= $Supplier;
							$nestedData[]	= 'ANTAR ALAT KE SUBCON';
							$nestedData[]	= $row['plan_subcon_send_date'];
								
						}
						if(strtolower($Lokasi) == 'subcon'){
							$Qty_Ambil		= $Qty_Subcon_Send - $Qty_Subcon_Rec;
							$nestedData[]	= '<input type="checkbox" id="chk_pilih_'.$Code_Detail.'" name="detPilih[]" value="'.$Code_Detail.'" class="chk_pilih">';
							$nestedData[]	= $row['tool_name'];
							$nestedData[]	= $Qty_Ambil;
							$nestedData[]	= '<span class="badge bg-red-active">SUBCON</span>';
							$nestedData[]	= $row['no_so'];
							$nestedData[]	= $Supplier;
							$nestedData[]	= 'AMBIL ALAT DARI SUBCON';
							$nestedData[]	= $row['plan_subcon_pick_date'];

						}
					}
				}
				
				$data[] = $nestedData;
				$urut1++;
				$urut2++;
			}
			
			
		}

		$json_data = array(
			"draw"            => intval( $requestData['draw'] ),  
			"recordsTotal"    => intval( $totalData ),  
			"recordsFiltered" => intval( $totalFiltered ), 
			"data"            => $data
		);

		echo json_encode($json_data);
		
	}
	
	function create_spk_driver_order(){
		$OK_Proses		= 0;
		$rows_Header	= array();
		$Category		= $Code_Cust = $Type = '';
		$Company = $Address		= $PIC_Name  = $PIC_Phone ='-';
		
		if($this->input->get()){
			$Category		= urldecode($this->input->get('category'));
			$Code_Cust		= urldecode($this->input->get('nocust'));
			$Type			= urldecode($this->input->get('tipe'));
			$Code_Process	= urldecode($this->input->get('tool'));
			
			$WHERE				= "id IN('".str_replace("^_^","','",$Code_Process)."')";
		
			if($Code_Cust){
				if(!empty($WHERE))$WHERE	.=" AND ";
				if($Category == 'SUB'){
					$WHERE	.="supplier_id = '".$Code_Cust."'";
				}else{				
					$WHERE	.="customer_id = '".$Code_Cust."'";
				}
			}
			if($Type){
				if(!empty($WHERE))$WHERE	.=" AND ";
				if(strtolower($Type) == 'ins'){
					$WHERE	.="(
									insitu = 'Y'
									AND (
										flag_cust_pick = 'N'
										OR flag_cust_send = 'N'
									)
								)";
				}else if(strtolower($Type) == 'del'){
					if($Category == 'SUB'){
						$WHERE	.="
								(
									location = 'Warehouse'
									AND subcon = 'Y'
									AND (
										qty_rec - qty_subcon_send - qty_reschedule
									) > 0
									AND qty_rec > 0 
									/*
									AND (
										(
											(
												subcon_send_driver_id = ''
												OR subcon_send_driver_id IS NULL
											)
											AND qty_subcon_send = 0
										)
										OR (
											NOT (
												subcon_send_driver_id = ''
												OR subcon_send_driver_id IS NULL
											)
											AND qty_subcon_send > 0
										)
									)
									*/
								)
								";
					}else{				
						$WHERE	.="
								(
									qty_rec > 0
									AND flag_process = 'Y'
									AND (

										IF (
											(qty_proses + qty_fail) > qty_rec,
											qty_rec,
											(qty_proses + qty_fail)
										) - qty_send
									) > 0
									AND location = 'Fine Good' /*
									AND (
										(
											(
												spk_send_driver_id = ''
												OR spk_send_driver_id IS NULL
											)
											AND qty_send = 0
										)
										OR (
											qty_send > 0
											AND NOT (
												spk_send_driver_id = ''
												OR spk_send_driver_id IS NULL
											)
										)
									)
									*/
								)
								";
					}
				}else{
					if($Category == 'SUB'){
						$WHERE	.="
									(
										location = 'Subcon'
										AND subcon = 'Y'
										AND qty_rec > 0
										AND (
											qty_subcon_send - qty_subcon_rec
										) > 0
										AND qty_subcon_send > 0 /*
										AND (
											(
												(
													subcon_pick_driver_id = ''
													OR subcon_pick_driver_id IS NULL
												)
												AND qty_subcon_rec = 0
											)
											OR (
												NOT (
													subcon_pick_driver_id = ''
													OR subcon_pick_driver_id IS NULL
												)
												AND qty_subcon_rec > 0
											)
										)
									*/
									)
						";
					}
					
				}
			}
			
			if($Category == 'CUST'){
				$Query_Cust	= "SELECT name AS company, address, contact AS pic_name, hp AS pic_phone FROM customers WHERE id= '".$Code_Cust."'";
			}else{
				$Query_Cust	= "SELECT supplier AS company, address, cp AS pic_name, hp AS pic_phone FROM suppliers WHERE id= '".$Code_Cust."'";
			}
			$rows_Cust		= $this->db->query($Query_Cust)->row();
			if($rows_Cust){
				$Company	= strtoupper($rows_Cust->company);
				$Address	= $rows_Cust->address;
				$PIC_Name	= strtoupper($rows_Cust->pic_name);
				$PIC_Phone	= $rows_Cust->pic_phone;
			}
			$Query_Detail	= "SELECT * FROM trans_details WHERE ".$WHERE;
			$rows_Detail	= $this->db->query($Query_Detail)->result_array();
			//echo"<pre>";print_r($Query_Detail);exit;
			
			if($rows_Detail){
				$OK_Proses	= 1;
				$intLoop	= 0;
				foreach($rows_Detail as $key=>$row){
					$Code_Detail		= $row['id'];
					$insitu				= $row['insitu'];
					$subcon				= $row['subcon'];
					$labs				= $row['labs'];
					$Lokasi				= $row['location'];
					$Qty				= $row['qty'];
					$Qty_Rec			= $row['qty_rec'];
					$Qty_Sch			= $row['qty_reschedule'];
					$Qty_Subcon_Send	= $row['qty_subcon_send'];
					$Qty_Subcon_Rec		= $row['qty_subcon_rec'];
					$Qty_Send			= $row['qty_send'];
					$Qty_Gagal			= $row['qty_fail'];
					$Qty_Sukses			= $row['qty_proses'];
					
					$OK_Process			= 1;
					if($insitu === 'Y' && $subcon === 'Y'){
						$OK_Process	= 0;
					}
					$rows_Letter		= $this->db->get_where('letter_orders',array('id'=>$row['letter_order_id']))->row();
					$Alamat_Ambil		= $rows_Letter->address;
					$Alamat_Kirim		= $rows_Letter->address_send;
					if($OK_Process === 1){
						$intLoop++;
						$Customer	= strtoupper($row['customer_name']);
						$Supplier	= strtoupper($row['supplier_name']);
						
						$nestedData	= array();
						if($insitu === 'Y'){
							$rows_Header[$intLoop]	= array(
								'code_process'		=> $Code_Detail,
								'tool_id'			=> $row['tool_id'],
								'tool_name'			=> $row['tool_name'],
								'qty'				=> $Qty,
								'tipe'				=> '<span class="badge bg-navy-active">INSITU</span>',
								'no_so'				=> $row['no_so'],
								'letter_order_id'	=> $row['letter_order_id'],
								'description'		=> 'ANTAR '.strtoupper($row['teknisi_name']),
								'code_teknisi'		=> $row['teknisi_id'],
								'nama_teknisi'		=> $row['teknisi_name']
							);
							if(!empty($Alamat_Ambil) && $Alamat_Ambil != '-'){
								$Address	= $Alamat_Ambil;
							}
							
							
						}else if($labs === 'Y' || $subcon === 'Y'){
							if(strtolower($Lokasi)=='fine good'){
								$Qty_Akan		= $Qty_Gagal + $Qty_Sukses;
								
								if($Qty_Akan > $Qty_Rec){
									$Qty_Akan	= $Qty_Rec;
								}
								
								$Qty_Kirim		= $Qty_Akan - $Qty_Send;
								
								if($Qty_Kirim > 0){
									$rows_Header[$intLoop]	= array(
										'code_process'		=> $Code_Detail,
										'tool_id'			=> $row['tool_id'],
										'tool_name'			=> $row['tool_name'],
										'qty'				=> $Qty_Kirim,
										'tipe'				=> ($labs == 'Y')?'<span class="badge bg-green-active">LABS</span>':'<span class="badge bg-red-active">SUBCON</span>',
										'no_so'				=> $row['no_so'],
										'letter_order_id'	=> $row['letter_order_id'],
										'description'		=> 'ANTAR ALAT KE CUSTOMER',
										'code_teknisi'		=> $row['teknisi_id'],
										'nama_teknisi'		=> $row['teknisi_name']
									);
									
									if(!empty($Alamat_Kirim) && $Alamat_Kirim != '-'){
										$Address	= $Alamat_Kirim;
									}
									
								}
							}
							
							if($subcon === 'Y'){
								if(strtolower($Lokasi)=='warehouse'){
									$Qty_Kirim_Sub	= $Qty_Rec - $Qty_Subcon_Send - $Qty_Sch;
									$rows_Header[$intLoop]	= array(
										'code_process'		=> $Code_Detail,
										'tool_id'			=> $row['tool_id'],
										'tool_name'			=> $row['tool_name'],
										'qty'				=> $Qty_Kirim_Sub,
										'tipe'				=> '<span class="badge bg-red-active">SUBCON</span>',
										'no_so'				=> $row['no_so'],
										'letter_order_id'	=> $row['letter_order_id'],
										'description'		=> 'ANTAR ALAT KE SUBCON',
										'code_teknisi'		=> $row['teknisi_id'],
										'nama_teknisi'		=> $row['teknisi_name']
									);
									
									
										
								}
								if(strtolower($Lokasi) == 'subcon'){
									$Qty_Ambil		= $Qty_Subcon_Send - $Qty_Subcon_Rec;
									$rows_Header[$intLoop]	= array(
										'code_process'		=> $Code_Detail,
										'tool_id'			=> $row['tool_id'],
										'tool_name'			=> $row['tool_name'],
										'qty'				=> $Qty_Ambil,
										'tipe'				=> '<span class="badge bg-red-active">SUBCON</span>',
										'no_so'				=> $row['no_so'],
										'letter_order_id'	=> $row['letter_order_id'],
										'description'		=> 'AMBIL ALAT DARI SUBCON',
										'code_teknisi'		=> $row['teknisi_id'],
										'nama_teknisi'		=> $row['teknisi_name']
									);
									
									

								}
							}
						}
						
						
					}
				}
				
			}
		}
		
		if($OK_Proses == 1){
			
			$data = array(
				'title'			=> 'DRIVER ORDER - OUTSTANDING TRANSACTION DATA',
				'action'		=> 'create_driver_order',
				'akses_menu'	=> $this->Arr_Akses,
				'rows_header'	=> $rows_Header,
				'category'		=> $Category,
				'type_process'	=> $Type,
				'code_company'	=> $Code_Cust,
				'company'		=> $Company,
				'alamat'		=> $Address,
				'pic_name'		=> $PIC_Name,
				'pic_phone'		=> $PIC_Phone
			);
			
			$this->load->view($this->folder.'/v_outs_trans_driver_order_process',$data);
		}else{
			$this->session->set_flashdata("alert_data", "<div class=\"alert alert-warning\" id=\"flash-message\">No record was found to process....</div>");
			redirect(site_url('Driver_order_outs_trans'));
		}
	}
	
	
	function save_create_driver_order(){
		$rows_Return	= array(
			'status'		=> 2,
			'pesan'			=> 'No Record was found...'
		);
		if($this->input->post()){
			//echo"<pre>";print_r($this->input->post());exit;
			
			$Created_By		= $this->session->userdata('siscal_userid');
			$Created_Name	= $this->session->userdata('siscal_username');
			$Created_Date	= date('Y-m-d H:i:s');
			
			$Plan_Date		= date('Y-m-d',strtotime($this->input->post('plan_date')));
			$Plan_Time		= $this->input->post('plan_time');
			$Type_Process	= $this->input->post('type_process');
			$Category		= $this->input->post('category');
			$Notes			= strtoupper($this->input->post('notes'));
			$Nocust			= $this->input->post('customer_id');
			$Customer		= $this->input->post('customer_name');
			$Address		= $this->input->post('address');
			$PIC_Name		= $this->input->post('pic_name');
			$PIC_Phone		= $this->input->post('pic_phone');
			$detDetail		= $this->input->post('detDetail');
			
			


			$this->db->trans_begin();
			$Pesan_Error	= '';
			
			$Code_Order		= 'DRV-ORD-'.date('YmdHis');				
			$Urut_Order		= 1;
			$Month_Process	= date('m',strtotime($Plan_Date));
			$Year_Process	= date('Y',strtotime($Plan_Date));
			$MonthYear_Proc	= date('Y-m',strtotime($Plan_Date));
			
			$Query_Urut		= "SELECT
									MAX(
										CAST(
											SUBSTRING_INDEX(order_no, '/', 1) AS UNSIGNED
										)
									) AS urut
								FROM
									trans_driver_orders
								WHERE
									plan_date LIKE '".$MonthYear_Proc."%'";
			$rows_Urut	= $this->db->query($Query_Urut)->row();
			if($rows_Urut){
				$Urut_Order	= intval($rows_Urut->urut) + 1;
			}
			$Lable_Urut		= sprintf('%05d',$Urut_Order);
			if($Urut_Order > 99999){
				$Lable_Urut		= $Urut_Order;
			}
			
			
			$Nomor_Order		= $Lable_Urut.'/DRV-ORD/'.$Month_Process.'/'.$Year_Process;
			
			$Ins_Header		= array(
				'order_code'	=> $Code_Order,
				'order_no'		=> $Nomor_Order,
				'datet'			=> date('Y-m-d'),
				'plan_date'		=> $Plan_Date,
				'plan_time'		=> $Plan_Time,
				'company_code'	=> $Nocust,
				'company'		=> $Customer,
				'type_comp'		=> ($Category == 'CUST')?'CUST':'SUPP',
				'category'		=> $Type_Process,
				'address'		=> $Address,
				'pic_name'		=> $PIC_Name,
				'pic_phone'		=> $PIC_Phone,
				'sts_order'		=> 'OPN',
				'notes'			=> $Notes,
				'created_by'	=> $Created_By,
				'created_date'	=> $Created_Date
			);
			
			$Has_Ins_Header	= $this->db->insert('trans_driver_orders',$Ins_Header);
			if($Has_Ins_Header !== TRUE){
				$Pesan_Error	= 'Error Insert Driver Order..';
			}
			
			##  MODIFIED BY ALI ~ 2022-12-10  ##
			$arr_Bast_Header	= $arr_Bast_Det	= array();
			$OK_Bast			= 0;
			if($Type_Process !== 'INS' && ($Category == 'SUB' || ($Category == 'CUST' && $Type_Process == 'DEL'))){
				$OK_Bast			= 1;
				$arr_Bast_Header	= array(
					'order_code'	=> $Code_Order,
					'kode'			=> $Nocust,
					'name'			=> $Customer,
					'flag_type'		=> ($Category == 'CUST')?'CUST':'SUPP',
					'type_bast'		=> $Type_Process,
					'address'		=> $Address,
					'pic'			=> $PIC_Name,
					'status'		=> 'OPN',
					'notes'			=> $Notes,
					'created_by'	=> $Created_By,
					'created_date'	=> $Created_Date
				);
			}
			
			$Ins_Upd_Quot	= array();
			if($detDetail){
				$intL	= 0;
				foreach($detDetail as $keyDetail=>$valDetail){
					$Qty_Pros	= $valDetail['qty'];
					if($Qty_Pros > 0){
						$intL++;
						$Code_Detail	= $Code_Order.'-'.$intL;
						$Code_Tool		= $valDetail['tool_id'];
						$Name_Tool		= $valDetail['tool_name'];
						$Code_QuotDet	= $valDetail['code_process'];
						
						$Ins_Detail		= array(
							'code_detail'	=> $Code_Detail,
							'order_code'	=> $Code_Order,
							'code_process'	=> $Code_QuotDet,
							'tool_id'		=> $Code_Tool,
							'tool_name'		=> $Name_Tool,
							'qty'			=> $Qty_Pros
						);
						
						## PROSES INSERT DRIVER ORDER DETAIL ##
						$Has_Ins_Detail	= $this->db->insert('trans_driver_order_details',$Ins_Detail);
						if($Has_Ins_Detail !== TRUE){
							$Pesan_Error	= 'Error Insert Driver Order Detail..';
						}
						$Table_Update		= 'Tran Detail';
						$Query_Update		= '';
						if($Type_Process === 'INS'){
							$Query_Update	= "UPDATE trans_details SET flag_cust_pick ='Y', flag_cust_send = 'Y' WHERE id = '".$Code_QuotDet."'";
						}else if($Type_Process === 'REC'){
							if($Category === 'CUST'){
								$Table_Update	= 'Quotation Detail';
								$Query_Update	= "UPDATE quotation_details SET qty_driver = qty_driver + ".$Qty_Pros." WHERE id = '".$Code_QuotDet."'";
							}else{
								$Query_Update	= "UPDATE trans_details SET qty_subcon_rec = qty_subcon_rec + ".$Qty_Pros." WHERE id = '".$Code_QuotDet."'";
							}
						}else if($Type_Process === 'DEL'){
							if($Category === 'CUST'){
								$Query_Update	= "UPDATE trans_details SET qty_send = qty_send + ".$Qty_Pros." WHERE id = '".$Code_QuotDet."'";
							}else{
								$Query_Update	= "UPDATE trans_details SET qty_subcon_send = qty_subcon_send + ".$Qty_Pros." WHERE id = '".$Code_QuotDet."'";
							}
						}
						if($Query_Update){
							$Has_Upd_Query	= $this->db->query($Query_Update);
							if($Has_Upd_Query !== TRUE){
								$Pesan_Error	= 'Error Update '.$Table_Update;
							}
						}
						
						##  MODIFIED BY ALI ~ 2022-12-10  ##
						if($OK_Bast === 1){
							$rows_Trans		= $this->db->get_where('trans_details',array('id'=>$Code_QuotDet))->row();
							if($rows_Trans){
								$Code_SO		= $rows_Trans->letter_order_id;
								$arr_Bast_Det[$Code_SO][]	= array(
									'quotation_detail_id'	=> $Code_QuotDet,
									'tool_id'				=> $Code_Tool,
									'tool_name'				=> $Name_Tool,
									'qty'					=> $Qty_Pros,
									'qty_io'				=> 0,
									'qty_sisa'				=> $Qty_Pros
								);
							}
						}
												
					}						
				}
			}
			
			##  MODIFIED BY ALI ~ 2022-12-10  ##
			if($OK_Bast === 1){
				$Urut_Code_Bast	= $Urut_Nomor_Bast = 1;
				$YearMonth	= date('Y-m',strtotime($Plan_Date));
				$YM_Short	= date('Ym',strtotime($Plan_Date));
				$Year		= date('Y',strtotime($Plan_Date));
				$Month		= date('n',strtotime($Plan_Date));
				
				$CodePros	= ($Category == 'CUST')?'C':'S';
				$TypePros	= ($Type_Process == 'REC')?'R':'S';
				$romawi		= getRomawi($Month);
				
				
				$Query_Urut	= "SELECT MAX(CAST(SUBSTRING_INDEX(id, '-', -1) AS UNSIGNED)) as urut FROM bast_headers WHERE datet LIKE '".$YearMonth."-%' LIMIT 1";
				$rows_Urut	= $this->db->query($Query_Urut)->result();
				if($rows_Urut){
					$Urut_Code_Bast	= intval($rows_Urut[0]->urut) + 1;
				}
				
				
				
				
				$Urut_Nomor	= 1;
				$Query_Nomor	= "SELECT MAX(CAST(SUBSTRING_INDEX(nomor, '/', 1) AS UNSIGNED)) as urut FROM bast_headers WHERE datet LIKE '".$Year."-%' AND flag_type = '".(($Category == 'CUST')?'CUST':'SUPP')."' AND type_bast = '".(($Type_Process == 'REC')?'REC':'DEL')."' LIMIT 1";
				$rows_Nomor	= $this->db->query($Query_Nomor)->result();
				if($rows_Nomor){
					$Urut_Nomor_Bast	= intval($rows_Nomor[0]->urut) + 1;
				}
				
				
				foreach($arr_Bast_Det as $keyLet=>$valLet){
					$Nomor_Baru	= $Urut_Nomor_Bast;
					$Urut_Code	= $Urut_Code_Bast;
					if($Urut_Code_Bast < 10000){
						$Urut_Code	= sprintf('%04d',$Urut_Code_Bast);
					}
					
					if($Urut_Nomor_Bast < 1000){
						$Nomor_Baru	= sprintf('%03d',$Urut_Nomor_Bast);
					}
					$Code_Bast			= 'BAST-'.$YM_Short.'-'.sprintf('%04d',$Urut_Code_Bast);
					$Nomor_Bast			= $Nomor_Baru.'/N-BAST.'.$TypePros.'/'.$CodePros.'-'.$romawi.'/'.$Year;
					
					$Ins_Bast_Header					= $arr_Bast_Header;
					$Ins_Bast_Header['id']				= $Code_Bast;
					$Ins_Bast_Header['nomor']			= $Nomor_Bast;
					$Ins_Bast_Header['datet']			= $Plan_Date;
					$Ins_Bast_Header['letter_order_id']	= $keyLet;
					
					$Nomor_SO			= '-';
					$rows_LetterOrder	= $this->db->get_where('letter_orders',array('id'=>$keyLet))->row();
					if($rows_LetterOrder){
						$Nomor_SO		= $rows_LetterOrder->no_so;
					}
					$Ins_Bast_Header['no_so']	= $Nomor_SO;
					$intBast	= 0;
					foreach($valLet as $keyTool=>$valTool){
						$intBast++;
						$Code_BastDetail					= $Code_Bast.'-'.$intBast;
						$Ins_Bast_Detail					= $valTool;
						$Ins_Bast_Detail['id']				= $Code_BastDetail;
						$Ins_Bast_Detail['bast_header_id']	= $Code_Bast;
						
						$Has_Ins_Bast_Detail	= $this->db->insert('bast_details',$Ins_Bast_Detail);
						if($Has_Ins_Bast_Detail !== true){
							$Pesan_Error	= 'Error Insert BAST Detail...';
						}
						$Qty_process		= $valTool['qty'];
						$Code_Trans			= $valTool['quotation_detail_id'];
						
						$Query_Trans	= "SELECT qty_send, qty_send_real, qty_subcon_send, qty_subcon_send_real, qty_subcon_rec, qty_subcon_rec_real FROM trans_details WHERE id = '".$Code_Trans."'";
						$Data_Trans		= $this->db->query($Query_Trans)->result_array();
						
						if($Category == 'CUST'){
							if($Type_Process == 'REC'){
								$Upd_Trans		= "bast_rec_id = '".$Code_Bast."', bast_rec_no = '".$Nomor_Bast."', bast_rec_date = '".$Plan_Date."', bast_rec_by = '".$Created_By."'";
							}else{
								$Upd_Trans		= "bast_send_id = '".$Code_Bast."', bast_send_no = '".$Nomor_Bast."', bast_send_date = '".$Plan_Date."', bast_send_by = '".$Created_By."'";
							}
						}else{
							if($Type_Process == 'REC'){
								$Upd_Trans		= "subcon_bast_rec_id = '".$Code_Bast."', subcon_bast_rec_no = '".$Nomor_Bast."', subcon_bast_rec_date = '".$Plan_Date."', subcon_bast_rec_by = '".$Created_By."'";
							}else{
								$Upd_Trans		= "subcon_bast_send_id = '".$Code_Bast."', subcon_bast_send_no = '".$Nomor_Bast."', subcon_bast_send_date = '".$Plan_Date."', subcon_bast_send_by = '".$Created_By."'";
							}
						}
						
						$Qry_Upd_Trans	= "UPDATE trans_details SET ".$Upd_Trans." WHERE id ='".$Code_Trans."'";
						
						$Has_Upd_Trans	= $this->db->query($Qry_Upd_Trans);
						if($Has_Upd_Trans !== true){
							$Pesan_Error	= 'Error Update Trans Detail - BAST...';
						}
						
					}
					
					$Has_Ins_Bast_Header	= $this->db->insert('bast_headers',$Ins_Bast_Header);
					if($Has_Ins_Bast_Header !== true){
						$Pesan_Error	= 'Error Insert BAST Header...';
					}
					
					$Urut_Nomor_Bast++;
					$Urut_Code_Bast++;
				}
			}
			
			if ($this->db->trans_status() != TRUE || !empty($Pesan_Error)){
				$this->db->trans_rollback();
				$rows_Return		= array(
					'status'		=> 2,
					'pesan'			=> 'Save Process  Failed, '.$Pesan_Error
				);
				history('Create Driver Order Process - Outstanding Transaction Data - '.$Code_Order.' - '.$Pesan_Error);
			}else{
				$this->db->trans_commit();
				$rows_Return		= array(
					'status'		=> 1,
					'pesan'			=> 'Save process success. Thank you & have a nice day......'
				);
				history('Create Driver Order Process - Outstanding Transaction Data - '.$Code_Order);
			}				
						
		}
		echo json_encode($rows_Return);
	}
	
	
	
	
	
}