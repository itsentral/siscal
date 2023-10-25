<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Technician_letters extends CI_Controller
{
	public function __construct()
	{
		parent::__construct();

		$this->load->database();
		if (!$this->session->userdata('isSISCALlogin')) {
			redirect('login');
		}
		$this->load->model('master_model');
		$controller			= ucfirst(strtolower($this->uri->segment(1)));
		$this->Arr_Akses	= getAcccesmenu($controller);

		$this->folder			= 'Hasil_kalibrasi';
		$this->file_location	= $this->config->item('location_file');
	}

	public function index()
	{
		$Arr_Akses			= $this->Arr_Akses;
		if ($Arr_Akses['read'] != '1') {
			$this->session->set_flashdata("alert_data", "<div class=\"alert alert-warning\" id=\"flash-message\">You Don't Have Right To Access This Page, Please Contact Your Administrator....</div>");
			redirect(site_url('dashboard'));
		}

		$rows_Customer		= $this->master_model->getArray('customers', array(), 'id', 'name');
		$data = array(
			'title'			=> 'MANAGE SPK TECHNICIAN',
			'action'		=> 'index',
			'akses_menu'	=> $Arr_Akses,
			'rows_customer'	=> $rows_Customer
		);
		history('View List SPK Technician');
		$this->load->view($this->folder . '/v_technician_letter', $data);
	}

	/*
	| -------------------------------- |
	|	 	DISPLAY LIST INVOICE       |
	| -------------------------------- |
	*/
	function get_data_display()
	{
		$Arr_Akses		= $this->Arr_Akses;

		$Month_Find		= $this->input->post('bulan');
		$Year_Find		= $this->input->post('tahun');
		
		$WHERE			= "1=1";
		$requestData	= $_REQUEST;
		
		$like_value     = $requestData['search']['value'];
        $column_order   = $requestData['order'][0]['column'];
        $column_dir     = $requestData['order'][0]['dir'];
        $limit_start    = $requestData['start'];
        $limit_length   = $requestData['length'];
		
		$columns_order_by = array(
			0 => 'id',
			1 => 'datet',
			2 => 'member_name'
		);
		
		if($Month_Find){
			if(!empty($WHERE))$WHERE	.=" AND ";
			$WHERE	.="MONTH(datet) = '".$Month_Find."'";
		}
		
		if($Year_Find){
			if(!empty($WHERE))$WHERE	.=" AND ";
			$WHERE	.="YEAR(datet) = '".$Year_Find."'";
		}
		
		if($like_value){
			if(!empty($WHERE))$WHERE	.=" AND ";
			$WHERE	.="(
						  id LIKE '%".$this->db->escape_like_str($like_value)."%'
						  OR DATE_FORMAT(datet, '%d-%m-%Y') LIKE '%".$this->db->escape_like_str($like_value)."%'
						  OR member_name LIKE '%".$this->db->escape_like_str($like_value)."%'
						)";
		}
		
		
		$sql = "SELECT
					*,
					(@row:=@row+1) AS urut
				FROM
					tech_orders,
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
			$Teknisi		= $row['member_name'];
			$Date_SPK		= date('d-m-Y',strtotime($row['datet']));
			$Code_Teknisi	= $row['member_id'];
			$Status_SPK		= $row['status'];
			
			if($Status_SPK == 'OPN'){
				$Ket_Status	= "<span class='badge bg-green'>OPEN</span>";
			}else if($Status_SPK == 'CLS'){
				$Ket_Status	= "<span class='badge bg-maroon'>CLOSE</span>";
			}else{
				$Ket_Status	= "<span class='badge bg-orange'>CANCEL</span>";
			}
			
			$Template			= "";
			
			if ($Arr_Akses['read'] == 1) {
				$Template		.= "<button type='button' class='btn btn-sm bg-navy-active' onClick='PreviewSPK(\"" . $Code_SPK . "\");'> <i class='fa fa-search'></i> </button>";
			}
			if ($Arr_Akses['download'] == 1 && $Status_SPK !== 'CNC') {
				$Template		.= "&nbsp;<a href='" . site_url('Technician_letters/print_spk/' . $Code_SPK) . "' class='btn btn-sm btn-info' title='Print SPK' target='_blank'> <i class='fa fa-print'></i> </a>";

			}

			if (($Arr_Akses['update'] == 1 || $Arr_Akses['delete'] == 1) && $Status_SPK !== 'CNC') {
				$Template		.= "&nbsp;<a href='" . site_url('Technician_letters/additional_data/' . $Code_SPK) . "' class='btn btn-sm bg-orange-active' title='ADDITIONAL DATA'> <i class='fa fa-plus'></i> </a>";
				
			}

			if (($Arr_Akses['update'] == 1 || $Arr_Akses['delete'] == 1) && $Status_SPK == 'OPN') {

				$Template		.= "&nbsp;<a href='" . site_url('Technician_letters/batal_spk_teknisi/' . $Code_SPK) . "' class='btn btn-sm btn-danger' title='CANCEL SPK'> <i class='fa fa-trash'></i> </a>";
			}
			
			$nestedData		= array();
			$nestedData[]	= $Code_SPK;
			$nestedData[]	= $Date_SPK;
			$nestedData[]	= $Teknisi;
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
	
	function getTechnician(){
		$Arr_Technician		= array();
		$Query_Technician	= "SELECT id, nama FROM members WHERE division_id = 'DIV-002' AND `status` = '1' ORDER BY nama ASC";
		$rows_Technican		= $this->db->query($Query_Technician)->result();
		if($rows_Technican){
			foreach($rows_Technican as $keyTech=>$valTech){
				$Code_Tech	= $valTech->id;
				$Name_Tech	= strtoupper($valTech->nama);
				$Arr_Technician[$Code_Tech]	= $Name_Tech;
			}
		}
		
		return $Arr_Technician;
	}


	/*
	| -------------------------------- |
	|	   LIST OUTSTANDING PARTIAL    |
	| -------------------------------- |
	*/

	function create_spk_technician()
	{
		$rows_Technician		= $this->getTechnician();
		$data = array(
			'title'			=> 'CREATE TECHNICIAN LETTER',
			'action'		=> 'create_spk_technician',
			'akses_menu'	=> $this->Arr_Akses,
			'rows_teknisi'	=> $rows_Technician
		);
		history('Create Technician Letter');
		$this->load->view($this->folder . '/v_technician_letter_add', $data);
	}
	
	

	/*
	| -------------------------------- |
	|	 	DISPLAY OUTS PARTIAL       |
	| -------------------------------- |
	*/

	function outstanding_technician_letter()
	{
		$rows_Akses		= $this->Arr_Akses;
		$requestData	= $_REQUEST;


		$Like_Value		= $requestData['search']['value'];
		$column_order	= $requestData['order'][0]['column'];
		$column_dir		= $requestData['order'][0]['dir'];
		$limit_start	= $requestData['start'];
		$limit_length	= $requestData['length'];

		$WHERE_Find		= "1=1";
		

		if ($Like_Value) {
			if (!empty($WHERE_Find)) $WHERE_Find	.= " AND ";
			$WHERE_Find	.= "(
						tool_id LIKE '%" . $this->db->escape_like_str($Like_Value) . "%'
						OR DATE_FORMAT(plan_date,'%d-%m-%Y') LIKE '%" . $this->db->escape_like_str($Like_Value) . "%'
						OR cust_name LIKE '%" . $this->db->escape_like_str($Like_Value) . "%'
						OR tool_name LIKE '%" . $this->db->escape_like_str($Like_Value) . "%'
						OR no_so LIKE '%" . $this->db->escape_like_str($Like_Value) . "%'
						OR teknisi LIKE '%" . $this->db->escape_like_str($Like_Value) . "%'
						OR jenis LIKE '%" . $this->db->escape_like_str($Like_Value) . "%'
						OR qty LIKE '%" . $this->db->escape_like_str($Like_Value) . "%'
						)";
		}

		$sql = "SELECT
					detail_id,
					tool_id,
					tool_name,
					qty,
					plan_date,
					cust_name,
					teknisi,
					no_so,
					letter_order_id,
					trans_id,
					jenis,
					(@row:=@row+1) AS nomor
				FROM
					(
						(
							SELECT
								det_ins.quotation_detail_id AS detail_id,
								det_ins.tool_id,
								det_ins.tool_name,
								det_ins.qty,
								head_ins.datet AS plan_date,
								head_ins.customer_name AS cust_name,
								det_ins.member_name AS teknisi,
								det_so.no_so,
								head_ins.letter_order_id,
								det_ins.id AS trans_id,
								'Insitu' AS jenis
							FROM
								insitu_letter_details det_ins
							INNER JOIN insitu_letters head_ins ON det_ins.insitu_letter_id = head_ins.id
							INNER JOIN letter_orders det_so ON det_so.id = head_ins.letter_order_id
							WHERE
								NOT (
									head_ins.order_code IS NULL
									OR head_ins.order_code = ''
									OR head_ins.order_code = '-'
								)
							AND det_ins.flag_tech_letter = 'N'
						)
						UNION
							(
								SELECT
									id AS detail_id,
									tool_id,
									tool_name,
									(
										qty_rec - qty_labs - qty_reschedule
									) AS qty,
									plan_process_date AS plan_date,
									customer_name AS cust_name,
									teknisi_name AS teknisi,
									no_so,
									letter_order_id,
									id AS trans_id,
									'Labs' AS jenis
								FROM
									trans_details
								WHERE
									labs = 'Y'
								AND location = 'Warehouse'
								AND (
									qty_rec - qty_labs - qty_reschedule
								) > 0
							)
					) detail_alat,
					(SELECT @row := 0) r 
				WHERE " . $WHERE_Find . "
				";
		//print_r($sql);exit();
		$fetch['totalData'] 		= $this->db->query($sql)->num_rows();
		$fetch['totalFiltered']	= $this->db->query($sql)->num_rows();

		$columns_order_by = array(
			1 => 'tool_id',
			2 => 'tool_name',
			3 => 'qty',
			4 => 'no_so',
			5 => 'cust_name',
			6 => 'plan_date',
			7 => 'teknisi'
		);

		$sql .= " ORDER BY " . $columns_order_by[$column_order] . " " . $column_dir;
		$sql .= " LIMIT " . $limit_start . " ," . $limit_length . " ";

		$fetch['query'] = $this->db->query($sql);

		$totalData		= $fetch['totalData'];
		$totalFiltered	= $fetch['totalFiltered'];
		$query			= $fetch['query'];

		$data		= array();
		$urut1  	= 1;
		$urut2  	= 0;
		$Tgl_Now	= date('Y-m-d');
		$Tahun_Now	= date('Y');

		foreach ($query->result_array() as $row) {
			$total_data     = $totalData;
			$start_dari     = $requestData['start'];
			$asc_desc       = $requestData['order'][0]['dir'];
			$nomor 			= $urut1 + $start_dari;


			$Code_Order		= $row['letter_order_id'];
			$No_Order		= $row['no_so'];
			$Plan_Date		= date('d-m-Y', strtotime($row['plan_date']));
			$Code_Detail	= $row['detail_id'];
			$Trans_Code		= $row['trans_id'];
			$Name_Teknisi	= $row['teknisi'];
			$Name_Customer	= $row['cust_name'];
			$Qty			= $row['qty'];
			$Tool_Code		= $row['tool_id'];
			$Tool_Name		= str_replace("'",' ',$row['tool_name']);
			$Tool_Type		= $row['jenis'];
			
			$Ket_Type		= '<span class="badge bg-maroon">Labs</span>';
			if(strtolower($Tool_Type) == 'insitu'){
				$Ket_Type		= '<span class="badge bg-purple">Insitu</span>';
			}
			

			$Template		= "-";
			if ($rows_Akses['create'] == '1') {
				$Template	= "<button type='button' data-trans-so='".$No_Order."' data-trans-tool ='".$Tool_Code."' data-trans-name='".$Tool_Name."' data-trans-detail='".$Code_Detail."' data-trans-cust='".$Name_Customer."' data-trans-teknisi='".$Name_Teknisi."' data-trans-date='".$row['plan_date']."' data-trans-qty ='".$Qty."' data-trans-jenis='".$Tool_Type."' data-trans-letter='".$Code_Order."' class='btn btn-sm bg-orange-active' id='trans_tool_" . $Trans_Code . "' title='CHOOSE TOOLS' onClick='return ChosenTool(\"" . $Trans_Code . "\");'> <i class='fa fa-plus'></i> </button>";
			}





			$nestedData 	= array();
			$nestedData[]	= $Tool_Code;
			$nestedData[]	= $Tool_Name;
			$nestedData[]	= $Qty;
			$nestedData[]	= $No_Order;
			$nestedData[]	= $Name_Customer;
			$nestedData[]	= $Plan_Date;
			$nestedData[]	= $Name_Teknisi;
			$nestedData[]	= $Ket_Type;
			$nestedData[]	= $Template;

			$data[] = $nestedData;
			$urut1++;
			$urut2++;
		}

		$json_data = array(
			"draw"            => intval($requestData['draw']),
			"recordsTotal"    => intval($totalData),
			"recordsFiltered" => intval($totalFiltered),
			"data"            => $data
		);

		echo json_encode($json_data);
	}
	
	function save_create_technician_process()
	{
		$Arr_Return		= array(
			'status'		=> 2,
			'pesan'			=> 'No Record Was Found........'
		);
		if ($this->input->post()) {
			//echo"<pre>";print_r($this->input->post());exit;
			$Created_By		= $this->session->userdata('siscal_userid');
			$Created_Date	= date('Y-m-d H:i:s');
			
			$Code_Tech		= $this->input->post('code_teknisi');
			$Date_Tech		= $this->input->post('spk_date');
			$detDetail		= $this->input->post('TechOrderDetail');
			
			$Code_SPK		= $Code_Tech.'-'.date('Ymd',strtotime($Date_Tech));
			
			## CEK SPK EXISTING ##
			$Found_Exist	= $this->db->get_where('tech_orders',array('id'=>$Code_SPK))->num_rows();
			if($Found_Exist > 0){
				$Arr_Return		= array(
					'status'		=> 2,
					'pesan'			=> 'SPK Technician Already Exist. Please Try Again'
				);
			}else{
				$Pesan_Error	= '';
				$this->db->trans_begin();
				$rows_Member	= $this->db->get_where('members',array('id'=>$Code_Tech))->row();
				$Name_Tech		= '-';
				if($rows_Member){
					$Name_Tech	= strtoupper($rows_Member->nama);
				}
				
				$intL			= 0;
				$labs			= 'N';
				$insitu			= 'N';
				if($detDetail){
					foreach($detDetail as $KeyDet=>$valDet){
						$intL++;
						$Code_Proses				= $valDet['kode_proses'];
						$Category					= $valDet['category'];
						$Ins_Detail					= $valDet;
						$Code_Detail				= $Code_SPK.'-'.$intL;
						$Ins_Detail['id']			= $Code_Detail;
						$Ins_Detail['tech_order_id']= $Code_SPK;
						$Ins_Detail['flag_proses']	= 'N';
						
						
						$Has_Ins_Detail				= $this->db->insert('tech_order_details',$Ins_Detail);
						if($Has_Ins_Detail !== TRUE){
							$Pesan_Error	= 'Error Insert Tech Order Detail';
						}
						
						if(strtolower($Category) == 'labs'){
							$labs			= 'Y';
							$Upd_Trans		= "UPDATE trans_details SET qty_labs = qty_labs + ".$valDet['qty']." WHERE id ='".$valDet['detail_id']."'";
							$Has_Upd_Trans	= $this->db->query($Upd_Trans);
							if($Has_Upd_Trans !== TRUE){
								$Pesan_Error	= 'Error Update Trans Detail';
							}
						}else{
							$insitu				= 'Y';
							$Code_Order			= $Code_Driver = '';
							$Query_SPK			= "SELECT
														head_insitu.order_code,
														head_insitu.spk_driver_id
													FROM
														insitu_letters head_insitu
													INNER JOIN insitu_letter_details det_insitu ON head_insitu.id=det_insitu.insitu_letter_id
													WHERE
														det_insitu.id = '".$Code_Proses."'
													GROUP BY
														head_insitu.id";
							$rows_SPK			= $this->db->query($Query_SPK)->row();
							
							if ($rows_SPK) {
								$Code_Order		= $rows_SPK->order_code;
								$Code_Driver 	= $rows_SPK->spk_driver_id;
							}
							
							
							
							if($Code_Order){
								$Upd_Bast_Insitu	= "UPDATE insitu_letter_details SET tech_order_id = '".$Code_SPK."', member_id = '".$Code_Tech."', member_name = '".$Name_Tech."', flag_tech_letter = 'Y' WHERE id = '".$Code_Proses."'";
								
								$Has_Upd_InsituDet	= $this->db->query($Upd_Bast_Insitu);
								if($Has_Upd_InsituDet !== TRUE){
									$Pesan_Error	= 'Error Update Insitu Letter Detail';
								}
								
								if($Code_Driver){
									$Upd_SPK_Driver		= "UPDATE spk_driver_tools SET teknisi = '".$Name_Tech."' WHERE schedule_detail_id = '".$valDet['detail_id']."' AND spk_driver_id = '".$Code_Driver."'";
									
									$Has_Upd_SPK_Driver	= $this->db->query($Upd_SPK_Driver);
									if($Has_Upd_SPK_Driver !== TRUE){
										$Pesan_Error	= 'Error Update Driver Letter Tools';
									}
								}
								
								
							}else{
								$Upd_SPK_Driver		= "UPDATE spk_driver_tools SET flag_proses = 'Y',  qty_proses = '".$valDet['qty']."' WHERE id = '".$Code_Proses."'";
								$Has_Upd_SPK_Driver	= $this->db->query($Upd_SPK_Driver);
								if($Has_Upd_SPK_Driver !== TRUE){
									$Pesan_Error	= 'Error Update Driver Letter Tools';
								}
							}
						}
					}
				}
				
				$Ins_Header	= array(
					'id'				=> $Code_SPK,
					'member_id'			=> $Code_Tech,
					'member_name'		=> $Name_Tech,
					'datet'				=> $Date_Tech,
					'insitu'			=> $insitu,
					'labs'				=> $labs,
					'status'			=> 'OPN',
					'created_date'		=> $Created_Date,
					'created_by'		=> $Created_By
				);
				
				$Has_Ins_Head	= $this->db->insert('tech_orders',$Ins_Header);
				if($Has_Ins_Head !== TRUE){
					$Pesan_Error	= 'Error Insert Tech Order';
				}
				
				if ($this->db->trans_status() !== TRUE || !empty($Pesan_Error)) {
					$this->db->trans_rollback();
					$Arr_Return		= array(
						'status'		=> 2,
						'pesan'			=> 'Save Process  Failed, ' . $Pesan_Error
					);
				} else {
					$this->db->trans_commit();
					$Arr_Return		= array(
						'status'		=> 1,
						'pesan'			=> 'Save process success. Thank you & have a nice day......',
						'code'			=> $Code_SPK
					);
					history('Add Technician Letter ' . $Code_SPK);
				}
			} 
		} 
		echo json_encode($Arr_Return);
	}

	
	function detail_spk_letter()
	{
		$rows_Header		= $rows_Detail	= array();
		if ($this->input->post()) {
			$Kode_Inv			= $this->input->post('kode_spk');
			$rows_Header		= $this->db->get_where('tech_orders', array('id' => $Kode_Inv))->row();
			$rows_Detail		= $this->db->get_where('tech_order_details', array('tech_order_id' => $Kode_Inv))->result();
		}
		$data = array(
			'title'			=> 'SPK TECHNICIAN DETAIL',
			'rows_header'	=> $rows_Header,
			'rows_detail'	=> $rows_Detail,
			'action'		=> 'detail_spk_letter'
		);
		$this->load->view($this->folder . '/v_technician_letter_preview', $data);
	}
	
	function additional_data($Kode_SPK =''){
		$Arr_Akses			= $this->Arr_Akses;
		if ($Arr_Akses['create'] != '1' || $Arr_Akses['update'] != '1') {
			$this->session->set_flashdata("alert_data", "<div class=\"alert alert-warning\" id=\"flash-message\">You Don't Have Right To Access This Page, Please Contact Your Administrator....</div>");
			redirect(site_url('Technician_letters'));
		}

		$rows_Header		= $this->db->get_where('tech_orders', array('id' => $Kode_SPK))->row();
		$rows_Detail		= $this->db->get_where('tech_order_details', array('tech_order_id' => $Kode_SPK))->result();
		$data = array(
			'title'			=> 'ADDITIONAL SPK TECHNICIAN',
			'rows_header'	=> $rows_Header,
			'rows_detail'	=> $rows_Detail,
			'action'		=> 'additional_data'
		);
		$this->load->view($this->folder . '/v_technician_letter_additional', $data);
	}
	
	function save_additional_technician_process(){
		$Arr_Return		= array(
			'status'		=> 2,
			'pesan'			=> 'No Record Was Found........'
		);
		if ($this->input->post()) {
			//echo"<pre>";print_r($this->input->post());exit;
			$Created_By		= $this->session->userdata('siscal_userid');
			$Created_Date	= date('Y-m-d H:i:s');
			
			$Code_SPK		= $this->input->post('code_spk');
			$Date_Tech		= $this->input->post('spk_date');
			$detDetail		= $this->input->post('TechOrderDetail');
			
			
			## CEK SPK EXISTING ##
			$Found_Exist	= $this->db->get_where('tech_orders',array('id'=>$Code_SPK))->row();
			
			$Pesan_Error	= '';
			$this->db->trans_begin();
			
			$Name_Tech		= $Found_Exist->member_name;
			$Code_Tech		= $Found_Exist->member_id;
			
			$intL			= 0;
			$Query_Urut		= "SELECT
									MAX(
										CAST(
											SUBSTRING_INDEX(id, '-' ,- 1) AS UNSIGNED
										)
									) AS urut
								FROM
									tech_order_details
								WHERE
									tech_order_id = '".$Code_SPK."'";
			$rows_Urut		= $this->db->query($Query_Urut)->row();
			
			//echo"<pre>";print_r($rows_Urut);exit;
			if($rows_Urut){
				$intL		= intval($rows_Urut->urut);
			}
			
			
			$labs			= 'N';
			$insitu			= 'N';
			if($detDetail){
				foreach($detDetail as $KeyDet=>$valDet){
					$intL++;
					$Code_Proses				= $valDet['kode_proses'];
					$Category					= $valDet['category'];
					$Ins_Detail					= $valDet;
					$Code_Detail				= $Code_SPK.'-'.$intL;
					$Ins_Detail['id']			= $Code_Detail;
					$Ins_Detail['tech_order_id']= $Code_SPK;
					$Ins_Detail['flag_proses']	= 'N';
					
					
					$Has_Ins_Detail				= $this->db->insert('tech_order_details',$Ins_Detail);
					if($Has_Ins_Detail !== TRUE){
						$Pesan_Error	= 'Error Insert Tech Order Detail';
					}
					
					if(strtolower($Category) == 'labs'){
						$labs			= 'Y';
						$Upd_Trans		= "UPDATE trans_details SET qty_labs = qty_labs + ".$valDet['qty']." WHERE id ='".$valDet['detail_id']."'";
						$Has_Upd_Trans	= $this->db->query($Upd_Trans);
						if($Has_Upd_Trans !== TRUE){
							$Pesan_Error	= 'Error Update Trans Detail';
						}
					}else{
						$insitu				= 'Y';
						$Code_Order			= $Code_Driver = '';
						$Query_SPK			= "SELECT
													head_insitu.order_code,
													head_insitu.spk_driver_id
												FROM
													insitu_letters head_insitu
												INNER JOIN insitu_letter_details det_insitu ON head_insitu.id=det_insitu.insitu_letter_id
												WHERE
													det_insitu.id = '".$Code_Proses."'
												GROUP BY
													head_insitu.id";
						$rows_SPK			= $this->db->query($Query_SPK)->row();
						
						if ($rows_SPK) {
							$Code_Order		= $rows_SPK->order_code;
							$Code_Driver 	= $rows_SPK->spk_driver_id;
						}
						
						
						
						if($Code_Order){
							$Upd_Bast_Insitu	= "UPDATE insitu_letter_details SET tech_order_id = '".$Code_SPK."', member_id = '".$Code_Tech."', member_name = '".$Name_Tech."', flag_tech_letter = 'Y' WHERE id = '".$Code_Proses."'";
							
							$Has_Upd_InsituDet	= $this->db->query($Upd_Bast_Insitu);
							if($Has_Upd_InsituDet !== TRUE){
								$Pesan_Error	= 'Error Update Insitu Letter Detail';
							}
							
							if($Code_Driver){
								$Upd_SPK_Driver		= "UPDATE spk_driver_tools SET teknisi = '".$Name_Tech."' WHERE schedule_detail_id = '".$valDet['detail_id']."' AND spk_driver_id = '".$Code_Driver."'";
								
								$Has_Upd_SPK_Driver	= $this->db->query($Upd_SPK_Driver);
								if($Has_Upd_SPK_Driver !== TRUE){
									$Pesan_Error	= 'Error Update Driver Letter Tools';
								}
							}
							
							
						}else{
							$Upd_SPK_Driver		= "UPDATE spk_driver_tools SET flag_proses = 'Y',  qty_proses = '".$valDet['qty']."' WHERE id = '".$Code_Proses."'";
							$Has_Upd_SPK_Driver	= $this->db->query($Upd_SPK_Driver);
							if($Has_Upd_SPK_Driver !== TRUE){
								$Pesan_Error	= 'Error Update Driver Letter Tools';
							}
						}
					}
				}
			}
			
			$Ins_Header	= array();
			
			if($insitu == 'Y'){
				$Ins_Header['insitu']			= 'Y';
				$Ins_Header['process_insitu']	= 'N';
			}
			
			if($labs == 'Y'){
				$Ins_Header['labs']			= 'Y';
				$Ins_Header['process_labs']	= 'N';
			}
			
			$Has_Ins_Head	= $this->db->update('tech_orders',$Ins_Header,array('id'=>$Code_SPK));
			if($Has_Ins_Head !== TRUE){
				$Pesan_Error	= 'Error Update Tech Order';
			}
			
			if ($this->db->trans_status() !== TRUE || !empty($Pesan_Error)) {
				$this->db->trans_rollback();
				$Arr_Return		= array(
					'status'		=> 2,
					'pesan'			=> 'Save Process  Failed, ' . $Pesan_Error
				);
			} else {
				$this->db->trans_commit();
				$Arr_Return		= array(
					'status'		=> 1,
					'pesan'			=> 'Save process success. Thank you & have a nice day......',
					'code'			=> $Code_SPK
				);
				history('Additional Tool Technician Letter ' . $Code_SPK);
			}
			
		} 
		echo json_encode($Arr_Return);
	}
	
	function batal_spk_teknisi($Kode_Inv = '')
	{
		

			$Arr_Akses			= $this->Arr_Akses;
			if ($Arr_Akses['delete'] != '1') {
				$this->session->set_flashdata("alert_data", "<div class=\"alert alert-warning\" id=\"flash-message\">You Don't Have Right To Access This Page, Please Contact Your Administrator....</div>");
				redirect(site_url('Technician_letters'));
			}

			$rows_Header		= $this->db->get_where('tech_orders', array('id' => $Kode_Inv))->row();
			$rows_Detail		= $this->db->get_where('tech_order_details', array('tech_order_id' => $Kode_Inv))->result();
			$data = array(
				'title'			=> 'CANCELLATION SPK TECHNICIAN',
				'rows_header'	=> $rows_Header,
				'rows_detail'	=> $rows_Detail,
				'action'		=> 'batal_spk_teknisi'
			);
			$this->load->view($this->folder . '/v_technician_letter_cancel', $data);
		
	}
	
	function save_batal_spk_teknisi()
	{
		if ($this->input->post()) {
			//echo"<pre>";print_r($this->input->post());exit;
			$cancel_By		= $this->session->userdata('siscal_userid');
			$cancel_Date	= date('Y-m-d H:i:s');

			$Code_SPK		= $this->input->post('nomor_spk');
			$Reason			= $this->input->post('cancel_reason');

			$rows_Check		= $this->db->get_where('tech_orders', array('id' => $Code_SPK))->row();

			$OK_Cek			= 1;
			$Error_Cek		= '';


			if ($rows_Check->status !== 'OPN') {
				$OK_Cek		= 0;
				$Error_Cek	= 'Data has been modified by other process..';
			}

			

			## CEK JIKA SUDAH DI SPK ##
			$Code_Order			= $Code_Driver = '';
			$Query_SPK			= "SELECT
										head_insitu.order_code,
										head_insitu.spk_driver_id
									FROM
										insitu_letters head_insitu
									INNER JOIN insitu_letter_details det_insitu ON head_insitu.id=det_insitu.insitu_letter_id
									WHERE
										det_insitu.flag_tech_letter = 'Y'
									AND det_insitu.tech_order_id = '".$Code_SPK."'
									GROUP BY
										head_insitu.id";
			$rows_SPK			= $this->db->query($Query_SPK)->row();
			if ($rows_SPK) {
				$Code_Order		= $rows_SPK->order_code;
				$Code_Driver 	= $rows_SPK->spk_driver_id;
				
				if($Code_Driver){
					$OK_Cek		= 0;
					$Error_Cek	= 'SPK Driver has been processed..';
				}
			}

			if ($OK_Cek == 0) {
				$Arr_Return		= array(
					'status'		=> 2,
					'pesan'			=> $Error_Cek
				);
			} else {
				$Pesan_Error	= '';
				$this->db->trans_begin();

				

				$Upd_header	= array(
					'cancel_by'			=> $cancel_By,
					'cancel_date'		=> $cancel_Date,
					'cancel_reason'		=> $Reason,
					'status'			=> 'CNC'
				);

				

				## UPDATE SPK TEKNISI ##
				$Upd_Header		= $this->db->update('tech_orders', $Upd_header, array('id' => $Code_SPK));
				if ($Upd_Header !== true) {
					$Pesan_Error	= 'Error Update SPK Header....';
				}

				$rows_Detail		= $this->db->get_where('tech_order_details', array('tech_order_id' => $Code_SPK))->result();
			
				if ($rows_Detail) {

					foreach ($rows_Detail as $keyDetail => $valDetail) {
						$Qty_Awal					= $valDetail->qty;
						$Kode_Proses				= $valDetail->kode_proses;
						$Kategori					= $valDetail->category;
						
						if(strtolower($Kategori) == 'labs'){
							$Upd_Trans		= "UPDATE trans_details SET qty_labs = qty_labs - ".$Qty_Awal." WHERE id ='".$Kode_Proses."'";
							$Has_Upd_Trans	= $this->db->query($Upd_Trans);
							if ($Has_Upd_Trans !== true) {
								$Pesan_Error	= 'Error Update Trans Detail....';
							}
						}else{		
							if(empty($Code_Order) || $Code_Order == '-'){
								$Upd_SPK_Driver	= "UPDATE spk_driver_tools SET flag_proses = 'N', qty_proses = 0 WHERE id ='".$Kode_Proses."'";
								$Has_Upd_Driver	= $this->db->query($Upd_SPK_Driver);
								if ($Has_Upd_Driver !== true) {
									$Pesan_Error	= 'Error Update SPK Tool Detail....';
								}
							}
						}
						
						if($Code_Order){
							$Upd_Ins_Detail		= "UPDATE insitu_letter_details SET tech_order_id = NULL, flag_tech_letter = 'N' WHERE quotation_detail_id ='".$valDetail->detail_id."' AND tech_order_id = '".$Code_SPK."'";
							$Has_Upd_Ins_Detail	= $this->db->query($Upd_Ins_Detail);
							if ($Has_Upd_Ins_Detail !== true) {
								$Pesan_Error	= 'Error Update Insitu Letter Detail....';
							}
						}
						
						
					}
				}

				
				if ($this->db->trans_status() != TRUE || !empty($Pesan_Error)) {
					$this->db->trans_rollback();
					$Arr_Return		= array(
						'status'		=> 2,
						'pesan'			=> 'Cancellation Process  Failed, please try again...'
					);
				} else {
					$this->db->trans_commit();
					$Arr_Return		= array(
						'status'		=> 1,
						'pesan'			=> 'Cancellation process success. Thank you & have a nice day......'
					);
					history('Cancel SPK Technician ' . $Code_SPK);
				}
			}
			
		} else {
			$Arr_Return		= array(
				'status'		=> 2,
				'pesan'			=> 'No Record Was Found........'
			);
		}
		echo json_encode($Arr_Return);
	}
	
	function print_spk($Code_SPK=''){
		$rows_Header	= $this->db->get_where('tech_orders',array('id'=>$Code_SPK))->row_array();
		$rows_Detail	= $this->db->get_where('tech_order_details',array('tech_order_id'=>$Code_SPK))->result_array();
		$rows_Member	= $this->db->get_where('members',array('id'=>$rows_Header['member_id']))->row_array();
		//echo"<pre>";print_r($rows_Header);exit;
		$data = array(
			'title'			=> 'TECHNICIAN LETTER PRINT',
			'action'		=> 'print_spk',
			'rows_header'	=> $rows_Header,
			'rows_detail'	=> $rows_Detail,
			'rows_member'	=> $rows_Member,
			'printby'		=> $this->session->userdata('siscal_username'),
			'today' 		=> date("Y-m-d H:i:s")
		);
		$this->load->view($this->folder.'/v_technician_letter_print',$data); 
	}
	
}
