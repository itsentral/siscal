<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Sales_order_receive extends CI_Controller
{
	public function __construct()
	{
		parent::__construct();

		$this->load->database();
		if (!$this->session->userdata('isSISCALlogin')) {
			redirect('login');
		}
		$this->load->model('master_model');
		$controller				= ucfirst(strtolower($this->uri->segment(1)));
		$this->Arr_Akses		= getAcccesmenu($controller);

		$this->folder			= 'Sales_order';
		$this->file_attachement	= $this->config->item('link_file');
		$this->file_location	= $this->config->item('location_file');
	}

	public function index()
	{
		$Arr_Akses			= $this->Arr_Akses;
		if ($Arr_Akses['read'] != '1') {
			$this->session->set_flashdata("alert_data", "<div class=\"alert alert-warning\" id=\"flash-message\">You Don't Have Right To Access This Page, Please Contact Your Administrator....</div>");
			redirect(site_url('dashboard'));
		}

		$data = array(
			'title'			=> 'SALES ORDER - RECEIVE',
			'action'		=> 'index',
			'akses_menu'	=> $Arr_Akses
		);
		history('View List Sales Order Receive');
		$this->load->view($this->folder . '/v_sales_order_receive', $data);
	}
	function get_data_display()
	{
		$Arr_Akses		= $this->Arr_Akses;

		$WHERE			= "head_so.flag_so_insitu = 'N'";

		$Month_Find		= $this->input->post('bulan');
		$Year_Find		= $this->input->post('tahun');
		$Status_Find	= $this->input->post('sts_so');
		$requestData	= $_REQUEST;

		$like_value     = $requestData['search']['value'];
		$column_order   = $requestData['order'][0]['column'];
		$column_dir     = $requestData['order'][0]['dir'];
		$limit_start    = $requestData['start'];
		$limit_length   = $requestData['length'];

		$columns_order_by = array(
			0 => 'head_so.no_so',
			1 => 'head_so.tgl_so',
			2 => 'head_so.customer_name',
			3 => 'head_quot.nomor',
			4 => 'head_quot.pono',
			// 6 => 'head_so.sts_so'
		);



		if ($like_value) {
			if (!empty($WHERE)) $WHERE	.= " AND ";
			$WHERE	.= "(
						  head_so.no_so LIKE '%" . $this->db->escape_like_str($like_value) . "%'
						  OR DATE_FORMAT(head_so.tgl_so, '%d %b %Y') LIKE '%" . $this->db->escape_like_str($like_value) . "%'
						  OR head_so.customer_name LIKE '%" . $this->db->escape_like_str($like_value) . "%'
						  OR head_quot.nomor LIKE '%" . $this->db->escape_like_str($like_value) . "%'
						  OR head_quot.pono LIKE '%" . $this->db->escape_like_str($like_value) . "%'
						)";
		}

		if ($Month_Find) {
			if (!empty($WHERE)) $WHERE .= " AND ";
			$WHERE	.= "MONTH(head_so.tgl_so) = '" . $Month_Find . "'";
		}

		if ($Year_Find) {
			if (!empty($WHERE)) $WHERE .= " AND ";
			$WHERE	.= "YEAR(head_so.tgl_so) = '" . $Year_Find . "'";
		}
		if ($Status_Find) {
			if (!empty($WHERE)) $WHERE .= " AND ";

			$WHERE	.= "head_so.sts_so = '" . $Status_Find . "'";
		}
		$sql = "SELECT
					head_so.*,
					head_quot.nomor AS quotation_nomor,
					head_quot.datet AS quotation_date,
					head_quot.pono,
					head_quot.podate,
					head_quot.member_id,
					head_quot.member_name,
					(@row:=@row+1) AS urut
				FROM
					letter_orders head_so
				INNER JOIN quotations head_quot ON head_so.quotation_id = head_quot.id,
				(SELECT @row:=0) r 
				WHERE " . $WHERE;
		//print_r($sql);exit();
		$fetch['totalData'] 	= $this->db->query($sql)->num_rows();
		$fetch['totalFiltered']	= $this->db->query($sql)->num_rows();



		$sql .= " ORDER BY head_so.id DESC," . $columns_order_by[$column_order] . " " . $column_dir . " ";
		$sql .= " LIMIT " . $limit_start . " ," . $limit_length . " ";

		$fetch['query'] = $this->db->query($sql);

		$totalData		= $fetch['totalData'];
		$totalFiltered	= $fetch['totalFiltered'];
		$query			= $fetch['query'];

		$data		= array();
		$urut1  	= 1;
		$urut2  	= 0;
		$Periode_Now = date('Y-m');
		$Tahun_Now	= date('Y');
		foreach ($query->result_array() as $row) {
			$total_data     = $totalData;
			$start_dari     = $requestData['start'];
			$asc_desc       = $requestData['order'][0]['dir'];
			if ($asc_desc == 'asc') {
				$nomor = $urut1 + $start_dari;
			}
			if ($asc_desc == 'desc') {
				$nomor = ($total_data - $start_dari) - $urut2;
			}

			$Code_SO		= $row['id'];
			$Nomor_SO		= $row['no_so'];
			$Date_SO		= date('d-m-Y', strtotime($row['tgl_so']));
			$Custid			= $row['customer_id'];
			$Customer		= $row['customer_name'];
			$Marketing		= strtoupper($row['member_name']);
			$Status_SO		= $row['sts_so'];
			$Quot_Nomor		= $row['quotation_nomor'];
			$Quot_Date		= date('d-m-Y', strtotime($row['quotation_date']));
			$Quot_PO		= $row['pono'];
			$Quot_PO_Date	= date('d-m-Y', strtotime($row['podate']));

			$Addr_Company	= $row['address'];
			$Addr_Invoice	= $row['address_inv'];
			$Addr_Sertifkat	= $row['address_sertifikat'];
			$Addr_Delivery	= $row['address_send'];


			$Lable_Status	= 'OPEN';
			$Color_Status	= 'bg-green-active';
			if ($Status_SO === 'CNC') {
				$Lable_Status	= 'CANCELED';
				$Color_Status	= 'bg-orange-active';
			} else if ($Status_SO === 'REV') {
				$Lable_Status	= 'REVISION';
				$Color_Status	= 'bg-navy-active';
			} else if ($Status_SO === 'SCH') {
				$Lable_Status	= 'SCHEDULED';
				$Color_Status	= 'bg-maroon-active';

				$Query_Outs		= "SELECT * FROM letter_order_details WHERE letter_order_id = '" . $Code_SO . "' AND (qty - qty_schedule) > 0";
				$Num_Outs		= $this->db->query($Query_Outs)->num_rows();
				// echo ($Num_Outs);
				// die();
				if ($Num_Outs > 0) {
					$Lable_Status	= 'PARTIAL SCHEDULED';
					$Color_Status	= 'bg-blue-active';
				}
			}
			$Ket_Status		= '<span class="badge ' . $Color_Status . '">' . $Lable_Status . '</span>';

			$Ket_Type		= '-';
			$Query_Type		= "SELECT
									SUM(
										CASE
										WHEN supplier_id = 'COMP-001' THEN
											1
										ELSE
											0
										END
									) AS total_lab,
									SUM(
										CASE
										WHEN supplier_id <> 'COMP-001' THEN
											1
										ELSE
											0
										END
									) AS total_subcon
								FROM
									letter_order_details
								WHERE
									letter_order_id = '" . $Code_SO . "'";
			$rows_Type		= $this->db->query($Query_Type)->row();
			if ($rows_Type) {
				$Jum_Labs		= $rows_Type->total_lab;
				$Jum_Subcon		= $rows_Type->total_subcon;
				if ($Jum_Labs > 0 && $Jum_Subcon > 0) {
					$Ket_Type		= '<span class="badge bg-navy-active">Labs & Subcon</span>';
				} else if ($Jum_Labs > 0 && $Jum_Subcon <= 0) {
					$Ket_Type		= '<span class="badge bg-maroon-active">Labs</span>';
				} else if ($Jum_Labs <= 0 && $Jum_Subcon > 0) {
					$Ket_Type		= '<span class="badge bg-orange-active">Subcon</span>';
				}
			}

			$Template		= '<button type="button" class="btn btn-sm btn-primary" onClick = "ActionPreview({code:\'' . $Code_SO . '\',action :\'detail_letter_order\',title:\'VIEW SALES ORDER - RECEIVE\'});" title="VIEW SALES ORDER - RECEIVE"> <i class="fa fa-search"></i> </button>';
			if (($Arr_Akses['create'] == '1' || $Arr_Akses['update'] == '1') && $Status_SO === 'OPN') {
				$Template		.= '&nbsp;&nbsp;<button type="button" class="btn btn-sm btn-danger" onClick = "ActionPreview({code:\'' . $Code_SO . '\',action :\'cancel_sales_order\',title:\'CANCEL SALES ORDER -RECEIVE\'});" title="CANCEL SALES ORDER -RECEIVE"> <i class="fa fa-trash-o"></i> </button>';
				$Template		.= '&nbsp;&nbsp;<a href="' . site_url() . '/Sales_order_receive/revisi_sales_order?nomor_order=' . urlencode($Code_SO) . '" class="btn btn-sm btn-success" title="SALES ORDER REVISION"> <i class="fa fa-edit"></i> </a>';
				$Template		.= '&nbsp;&nbsp;<a href="' . site_url() . '/Sales_order_receive/print_sales_order?nomor_order=' . urlencode($Code_SO) . '" class="btn btn-sm btn-warning" target = "_blank" title="PRINT SPK DRIVER ORDER"> <i class="fa fa-print"></i> </a>';
			}




			$nestedData		= array();
			$nestedData[]	= $Nomor_SO;
			$nestedData[]	= $Date_SO;
			$nestedData[]	= $Customer;
			$nestedData[]	= $Quot_Nomor;
			$nestedData[]	= $Quot_PO;
			$nestedData[]	= $Ket_Type;
			$nestedData[]	= $Ket_Status;
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
	function outs_letter_order_receive()
	{
		$Arr_Akses			= $this->Arr_Akses;
		if ($Arr_Akses['create'] != '1') {
			$this->session->set_flashdata("alert_data", "<div class=\"alert alert-warning\" id=\"flash-message\">You Don't Have Right To Access This Page, Please Contact Your Administrator....</div>");
			redirect(site_url('Sales_order_receive'));
		}

		$data = array(
			'title'			=> 'CREATE SALES ORDER - WAREHOUSE RECEIVE',
			'action'		=> 'outs_letter_order_receive',
			'akses_menu'	=> $Arr_Akses
		);

		$this->load->view($this->folder . '/v_sales_order_receive_outs', $data);
	}

	function display_out_sales_order()
	{
		$Arr_Akses			= $this->Arr_Akses;
		$User_Groupid	= $this->session->userdata('siscal_group_id');
		$WHERE			= "(
								detail.qty_rec - detail.qty_so
							) > 0";
		if ($User_Groupid == '2') {
			$User_Member	= $this->session->userdata('siscal_member_id');
			if (!empty($WHERE)) $WHERE	.= " AND ";
			$WHERE	.= "header.member_id = '" . $User_Member . "'";
		}

		$Month_Find		= $this->input->post('bulan');
		$Year_Find		= $this->input->post('tahun');
		$requestData	= $_REQUEST;

		$like_value     = $requestData['search']['value'];
		$column_order   = $requestData['order'][0]['column'];
		$column_dir     = $requestData['order'][0]['dir'];
		$limit_start    = $requestData['start'];
		$limit_length   = $requestData['length'];

		$columns_order_by = array(
			0 => 'header.nomor',
			1 => 'header.datet',
			2 => 'header.customer_name',
			3 => 'header.pono',
			4 => 'header.podate',
			5 => 'header.member_name'
		);



		if ($like_value) {
			if (!empty($WHERE)) $WHERE	.= " AND ";
			$WHERE	.= "(
						  header.nomor LIKE '%" . $this->db->escape_like_str($like_value) . "%'
						  OR DATE_FORMAT(header.datet, '%d %b %Y') LIKE '%" . $this->db->escape_like_str($like_value) . "%'
						  OR header.customer_name LIKE '%" . $this->db->escape_like_str($like_value) . "%'
						  OR header.member_name LIKE '%" . $this->db->escape_like_str($like_value) . "%'
						  OR header.pono LIKE '%" . $this->db->escape_like_str($like_value) . "%'
						  OR DATE_FORMAT(header.podate, '%d %b %Y') LIKE '%" . $this->db->escape_like_str($like_value) . "%'
						)";
		}



		$sql = "SELECT
					header.*,
					(@row:=@row+1) AS urut
				FROM
					quotation_detail_receives detail
				INNER JOIN quotations header ON detail.quotation_id = header.id,
				(SELECT @row:=0) r 
				WHERE " . $WHERE . "
				GROUP BY detail.quotation_id
				";
		//print_r($sql);exit();
		$fetch['totalData'] 	= $this->db->query($sql)->num_rows();
		$fetch['totalFiltered']	= $this->db->query($sql)->num_rows();



		$sql .= " ORDER BY header.datet DESC," . $columns_order_by[$column_order] . " " . $column_dir . " ";
		$sql .= " LIMIT " . $limit_start . " ," . $limit_length . " ";

		$fetch['query'] = $this->db->query($sql);

		$totalData		= $fetch['totalData'];
		$totalFiltered	= $fetch['totalFiltered'];
		$query			= $fetch['query'];

		$data		= array();
		$urut1  	= 1;
		$urut2  	= 0;
		$Periode_Now = date('Y-m');
		$Tahun_Now	= date('Y');
		foreach ($query->result_array() as $row) {
			$total_data     = $totalData;
			$start_dari     = $requestData['start'];
			$asc_desc       = $requestData['order'][0]['dir'];
			if ($asc_desc == 'asc') {
				$nomor = $urut1 + $start_dari;
			}
			if ($asc_desc == 'desc') {
				$nomor = ($total_data - $start_dari) - $urut2;
			}

			$Code_Quot		= $row['id'];
			$Nomor_Quot		= $row['nomor'];
			$Date_Quot		= date('d-m-Y', strtotime($row['datet']));
			$Custid			= $row['customer_id'];
			$Customer		= $row['customer_name'];
			$Marketing		= strtoupper($row['member_name']);
			$Quot_PO		= $row['pono'];
			$Quot_PO_Date	= date('d-m-Y', strtotime($row['podate']));



			$Template		= '';
			if ($Arr_Akses['create'] == '1') {
				$Template		= '<a href="' . site_url() . '/Sales_order_receive/create_sales_order?nomor_quot=' . urlencode($Code_Quot) . '" class="btn btn-sm bg-navy-active"  title="CREATE SALES ORDER -RECEIVE"> <i class="fa fa-plus"></i> </a>';
			}




			$nestedData		= array();
			$nestedData[]	= $Nomor_Quot;
			$nestedData[]	= $Date_Quot;
			$nestedData[]	= $Customer;
			$nestedData[]	= $Quot_PO;
			$nestedData[]	= $Quot_PO_Date;
			$nestedData[]	= $Marketing;
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



	function create_sales_order()
	{
		$rows_Header	= $rows_Customer = $rows_Detail = $rows_Plant = array();
		if ($this->input->get()) {
			$Code_Quot		= urldecode($this->input->get('nomor_quot'));
			$rows_Header	= $this->db->get_where('quotations', array('id' => $Code_Quot))->row();
			$rows_Customer	= $this->db->get_where('customers', array('id' => $rows_Header->customer_id))->row();
			$Query_Detail	= "SELECT
									det_rec.tool_id,
									det_rec.tool_name,
									det_rec.quotation_detail_id,
									det_rec.quotation_id,
									SUM(
										det_rec.qty_rec - det_rec.qty_so
									) AS total_outs,
									GROUP_CONCAT(det_rec.id) AS code_detail,
									GROUP_CONCAT(det_rec.descr) AS descr_receive,
									det_quot.cust_tool,
									det_quot.range,
									det_quot.piece_id,
									det_quot.supplier_id,
									det_quot.supplier_name,
									det_quot.descr AS cust_request
								FROM
									quotation_detail_receives det_rec
								INNER JOIN quotation_details det_quot ON det_rec.quotation_detail_id = det_quot.id
								WHERE
									(
										det_rec.qty_rec - det_rec.qty_so
									) > 0
								AND det_rec.quotation_id = '" . $Code_Quot . "'
								GROUP BY
									det_rec.quotation_detail_id";
			$rows_Detail	= $this->db->query($Query_Detail)->result();

			$Query_Plant	= "SELECT id,branch FROM plants WHERE customer_id = '" . $rows_Header->customer_id . "' AND NOT(branch IS NULL OR branch ='' OR branch='-')";
			$rows_Plant		= $this->db->query($Query_Plant)->result();
		}
		$rows_Supplier		= $this->master_model->getArray('suppliers', array('id !=' => 'COMP-001'), 'id', 'supplier');
		$data = array(
			'title'			=> 'CREATE SALES ORDER - RECEIVE',
			'action'		=> 'create_sales_order',
			'akses_menu'	=> $this->Arr_Akses,
			'rows_header'	=> $rows_Header,
			'rows_detail'	=> $rows_Detail,
			'rows_cust'		=> $rows_Customer,
			'rows_supplier'	=> $rows_Supplier,
			'rows_plant'	=> $rows_Plant
		);

		$this->load->view($this->folder . '/v_sales_order_receive_add', $data);
	}

	function save_create_letter_order()
	{
		$rows_Return	= array(
			'status'		=> 2,
			'pesan'			=> 'No record was found...'
		);

		if ($this->input->post()) {
			//echo "<pre>";print_r($this->input->post());exit;


			$Created_By		= $this->session->userdata('siscal_username');
			$Created_Id		= $this->session->userdata('siscal_userid');
			$Created_Date	= date('Y-m-d H:i:s');

			$Code_Quot		= $this->input->post('quotation_id');
			$Nomor_Quot		= $this->input->post('quotation_nomor');
			$Nocust			= $this->input->post('customer_id');
			$Customer		= $this->input->post('customer_name');
			$PIC_Name		= strtoupper($this->input->post('pic_name'));
			$PIC_Phone		= str_replace(array('+', '-', ' '), '', $this->input->post('pic_phone'));

			$Delv_Address	= $this->input->post('address_send');
			$Inv_Address	= $this->input->post('address_inv');
			$Cert_Address	= $this->input->post('address_sertifikat');
			$Cust_Address	= $this->input->post('address');



			$Delv_Notes		= strtoupper($this->input->post('send_notes'));
			$Inv_Notes		= strtoupper($this->input->post('inv_notes'));

			$detDetail		= $this->input->post('detDetail');

			$Pesan_Error	= '';
			$this->db->trans_begin();

			$Date_Now		= date('Y-m-d');
			$Tahun_Now		= date('Y');
			$Month_Now		= date('m');
			$Bulan_Now		= date('n');
			$YearMonth		= date('Ym');

			$RomawiMonth	= getRomawi($Bulan_Now);

			## AMBIL NOMOR URUT ##
			$Urut_Code		= $Urut_Nomor	= 1;
			$Qry_Urut_Code	= "SELECT
									MAX(
										CAST(
											SUBSTRING_INDEX(id, '-' ,- 1) AS UNSIGNED
										)
									) AS nomor_urut
								FROM
									letter_orders
								WHERE
									YEAR (created_date) = '" . $Tahun_Now . "'";
			$rows_Urut_Code	= $this->db->query($Qry_Urut_Code)->row();
			if ($rows_Urut_Code) {
				$Urut_Code	= intval($rows_Urut_Code->nomor_urut) + 1;
			}
			$Pref_Urut		= sprintf('%05d', $Urut_Code);
			if ($Urut_Code >= 100000) {
				$Pref_Urut	= $Urut_Code;
			}

			$Code_Letter		= 'SO-V3' . $YearMonth . '-' . $Pref_Urut;

			$Nomor_Project		= '';
			$Qry_Urut_Nomor		= "SELECT project_code, project_no FROM quotations WHERE id = '" . $Code_Quot . "'";
			$rows_Urut_Nomor	= $this->db->query($Qry_Urut_Nomor)->row();
			if ($rows_Urut_Nomor) {
				$Urut_Nomor		= intval($rows_Urut_Nomor->project_no) + 1;
				$Nomor_Project	= $rows_Urut_Nomor->project_code;
			}

			$Pref_Nomor			= getColsChar($Urut_Nomor);
			$Nomor_Letter		= $Pref_Nomor . '-' . $Nomor_Project;

			$Ins_Header			= array(
				'id'					=> $Code_Letter,
				'no_so'					=> $Nomor_Letter,
				'tgl_so'				=> $Date_Now,
				'quotation_id'			=> $Code_Quot,
				'customer_id'			=> $Nocust,
				'customer_name'			=> $Customer,
				'address'				=> $Cust_Address,
				'address_inv'			=> $Inv_Address,
				'address_sertifikat'	=> $Cert_Address,
				'address_send'			=> $Delv_Address,
				'pic'					=> $PIC_Name,
				'phone'					=> $PIC_Phone,
				'sts_so'				=> 'OPN',
				'created_date'			=> $Created_Date,
				'created_by'			=> $Created_Id,
				'get_tool'				=> '',
				'flag_so_insitu'		=> 'N',
				'notes_invoice'			=> $Inv_Notes,
				'notes_delivery'		=> $Delv_Notes
			);
			$Arr_Type		= array('I' => 'Insitu', 'L' => 'Labs', 'S' => 'Subcon');
			$Get_Tool		= '';
			if ($detDetail) {
				$intL	= 0;
				foreach ($detDetail as $keyDet => $valDet) {
					$intL++;
					$Code_LetterDet	= $Code_Letter . '-' . $intL;
					$Code_Tool		= $valDet['tool_id'];
					$Name_Tool		= $valDet['tool_name'];
					$Supplier_Code	= $valDet['supplier'];
					$Supplier_Name	= '-';
					$Type			= $valDet['tipe'];
					$Code_Receive	= $valDet['code_detail'];
					$Qty			= $valDet['qty'];
					$Range			= $valDet['range'];
					$Satuan			= $valDet['piece_id'];
					$Get_Tool		= $valDet['get_tool'];
					$Description	= $valDet['descr'];
					$Code_QuotDet	= $valDet['quotation_detail_id'];

					$rows_Supplier	= $this->db->get_where('suppliers', array('id' => $Supplier_Code))->row();
					if ($rows_Supplier) {
						$Supplier_Name	= $rows_Supplier->supplier;
					}


					$Ins_Detail		= array(
						'id'					=> $Code_LetterDet,
						'letter_order_id'		=> $Code_Letter,
						'quotation_detail_id'	=> $Code_QuotDet,
						'tool_id'				=> $Code_Tool,
						'tool_name'				=> $Name_Tool,
						'range'					=> $Range,
						'piece_id'				=> $Satuan,
						'qty'					=> $Qty,
						'supplier_id'			=> $Supplier_Code,
						'supplier_name'			=> $Supplier_Name,
						'descr'					=> $Description,
						'tipe'					=> $Arr_Type[$Type],
						'get_tool'				=> $Get_Tool,
						'detail_id'				=> $Code_Receive,
						'created_by'			=> $Created_Id,
						'created_date'			=> $Created_Date
					);

					$Has_Ins_Detail	= $this->db->insert('letter_order_details', $Ins_Detail);
					if ($Has_Ins_Detail !== TRUE) {
						$Pesan_Error	= 'Error Insert Letter Order Detail...';
					}

					$Qty_Proses		= $Qty;
					$Imp_Code_Rec	= str_replace(",", "','", $Code_Receive);
					$Qry_Receive	= "SELECT * FROM quotation_detail_receives WHERE id IN('" . $Imp_Code_Rec . "')";
					$rows_Receive	= $this->db->query($Qry_Receive)->result();
					if ($rows_Receive) {
						foreach ($rows_Receive as $keyRec => $valRec) {
							$Qty_Out	= $valRec->qty_rec - $valRec->qty_so;
							if ($Qty_Out > 0 && $Qty_Proses > 0) {
								$Qty_Update	= $Qty_Out;
								if ($Qty_Proses < $Qty_Out) {
									$Qty_Update	= $Qty_Proses;
								}

								$Upd_Rec_Detail	= "UPDATE quotation_detail_receives SET qty_so = qty_so + " . $Qty_Update . ", letter_order_id = '" . $Code_Letter . "' WHERE id = '" . $valRec->id . "'";
								$Has_Upd_RecDet	= $this->db->query($Upd_Rec_Detail);
								if ($Has_Upd_RecDet !== TRUE) {
									$Pesan_Error	= 'Error Insert Quotation Receive Detail...';
								}

								$Qty_Proses	-= $Qty_Update;
							}
						}
					}

					if ($Qty_Proses > 0) {
						$Pesan_Error	= 'Qty SO Tool Should Less Then Leftover Qty...';
					}
				}
			}

			$Ins_Header['get_tool']	= $Get_Tool;
			$Has_Ins_Header			= $this->db->insert('letter_orders', $Ins_Header);
			if ($Has_Ins_Header !== TRUE) {
				$Pesan_Error	= 'Error Insert Letter Order Header...';
			}

			$Upd_Quot	= array(
				'flag_so'		=> 'Y',
				'noso'			=> $Nomor_Letter,
				'tglso'			=> $Date_Now,
				'project_no'	=> $Urut_Nomor
			);

			$Has_Upd_Quot			= $this->db->update('quotations', $Upd_Quot, array('id' => $Code_Quot));
			if ($Has_Upd_Quot !== TRUE) {
				$Pesan_Error	= 'Error Update Quotation...';
			}

			if ($this->db->trans_status() != TRUE || !empty($Pesan_Error)) {
				$this->db->trans_rollback();
				$rows_Return		= array(
					'status'		=> 2,
					'pesan'			=> 'Create Sales Order Process  Failed, ' . $Pesan_Error
				);
				history('Create Sales Order ' . $Nomor_Letter . ' - ' . $Pesan_Error);
			} else {
				$this->db->trans_commit();
				$rows_Return		= array(
					'status'		=> 1,
					'pesan'			=> 'Create Sales Order success. Thank you & have a nice day......'
				);
				history('Create Sales Order ' . $Nomor_Letter . ' - Success ');
			}
		}

		echo json_encode($rows_Return);
	}

	function detail_letter_order()
	{
		$OK_Proses	= 0;
		$rows_Header	= $rows_Detail =  $rows_Quot = array();

		if ($this->input->post()) {
			$Code_Process	= urldecode($this->input->post('code'));

			$rows_Header	= $this->db->get_where('letter_orders', array('id' => $Code_Process))->row();
			$rows_Detail	= $this->db->get_where('letter_order_details', array('letter_order_id' => $Code_Process))->result();
			$rows_Quot		= $this->db->get_where('quotations', array('id' => $rows_Header->quotation_id))->row();
		}


		$data = array(
			'title'			=> 'SALES ORDER PREVIEW',
			'action'		=> 'detail_letter_order',
			'akses_menu'	=> $this->Arr_Akses,
			'rows_header'	=> $rows_Header,
			'rows_detail'	=> $rows_Detail,
			'rows_quot'		=> $rows_Quot,
			'category'		=> 'view'
		);

		$this->load->view($this->folder . '/v_sales_order_receive_preview', $data);
	}

	function print_sales_order()
	{
		$rows_Header	= $rows_Detail =  $rows_Quot = array();

		if ($this->input->get()) {
			$Code_Process	= urldecode($this->input->get('nomor_order'));

			$rows_Header	= $this->db->get_where('letter_orders', array('id' => $Code_Process))->row_array();
			$rows_Detail	= $this->db->get_where('letter_order_details', array('letter_order_id' => $Code_Process))->result_array();
			$rows_Quot		= $this->db->get_where('quotations', array('id' => $rows_Header['quotation_id']))->row_array();
		}


		$data = array(
			'title'			=> 'SALES ORDER PRINT',
			'action'		=> 'print_sales_order',
			'rows_header'	=> $rows_Header,
			'rows_detail'	=> $rows_Detail,
			'rows_quot'		=> $rows_Quot
		);

		$this->load->view($this->folder . '/v_sales_order_receive_print', $data);
	}


	function cancel_sales_order()
	{
		$OK_Proses	= 0;
		$rows_Header	= $rows_Detail =  $rows_Quot = array();

		if ($this->input->post()) {
			$Code_Process	= urldecode($this->input->post('code'));

			$rows_Header	= $this->db->get_where('letter_orders', array('id' => $Code_Process))->row();
			$rows_Detail	= $this->db->get_where('letter_order_details', array('letter_order_id' => $Code_Process))->result();
			$rows_Quot		= $this->db->get_where('quotations', array('id' => $rows_Header->quotation_id))->row();
		}


		$data = array(
			'title'			=> 'SALES ORDER CANCELLATION',
			'action'		=> 'cancel_sales_order',
			'akses_menu'	=> $this->Arr_Akses,
			'rows_header'	=> $rows_Header,
			'rows_detail'	=> $rows_Detail,
			'rows_quot'		=> $rows_Quot,
			'category'		=> 'cancel'
		);

		$this->load->view($this->folder . '/v_sales_order_receive_preview', $data);
	}


	function save_cancel_sales_order()
	{
		$rows_Return	= array(
			'status'		=> 2,
			'pesan'			=> 'No Record was found...'
		);
		if ($this->input->post()) {
			//echo"<pre>";print_r($this->input->post());exit;

			$Created_By		= $this->session->userdata('siscal_userid');
			$Created_Name	= $this->session->userdata('siscal_username');
			$Created_Date	= date('Y-m-d H:i:s');

			$Code_Order		= $this->input->post('code_order');
			$Cancel_Reason	= strtoupper($this->input->post('cancel_reason'));

			$Find_Exist		= $this->db->get_where('letter_orders', array('id' => $Code_Order))->row();
			if ($Find_Exist) {
				if ($Find_Exist->sts_so !== 'OPN') {
					$rows_Return	= array(
						'status'		=> 2,
						'pesan'			=> 'Data has been modified by other process...'
					);
				} else {
					$this->db->trans_begin();
					$Pesan_Error	= '';

					## AMBIL DATA SPK DETAIL TOOL ##
					$rows_Detail		= $this->db->get_where('letter_order_details', array('letter_order_id' => $Code_Order))->result();
					if ($rows_Detail) {
						$intL	= 0;
						foreach ($rows_Detail as $keyTool => $valTool) {
							$intL++;
							$Qty			= $valTool->qty;
							$Code_Receive	= $valTool->detail_id;

							$Qty_Proses		= $Qty;
							$Imp_Code_Rec	= str_replace(",", "','", $Code_Receive);
							$Qry_Receive	= "SELECT * FROM quotation_detail_receives WHERE id IN('" . $Imp_Code_Rec . "')";
							$rows_Receive	= $this->db->query($Qry_Receive)->result();
							if ($rows_Receive) {
								foreach ($rows_Receive as $keyRec => $valRec) {
									$Qty_Out	= $valRec->qty_so;
									if ($Qty_Out > 0 && $Qty_Proses > 0) {
										$Qty_Update	= $Qty_Out;
										if ($Qty_Proses < $Qty_Out) {
											$Qty_Update	= $Qty_Proses;
										}

										$Upd_Rec_Detail	= "UPDATE quotation_detail_receives SET qty_so = qty_so - " . $Qty_Update . ", letter_order_id = NULL WHERE id = '" . $valRec->id . "'";
										$Has_Upd_RecDet	= $this->db->query($Upd_Rec_Detail);
										if ($Has_Upd_RecDet !== TRUE) {
											$Pesan_Error	= 'Error Insert Quotation Receive Detail...';
										}

										$Qty_Proses	-= $Qty_Update;
									}
								}
							} else {

								## ANTICIPATE IF SO CREATED BEFORE WAREHOUSE RECEIVING ##
								$Qry_Receive	= "SELECT * FROM quotation_driver_detail_receives WHERE id IN('" . $Imp_Code_Rec . "')";
								$rows_Receive	= $this->db->query($Qry_Receive)->result();
								if ($rows_Receive) {
									foreach ($rows_Receive as $keyRec => $valRec) {
										$Qty_Out	= 1;
										if ($Qty_Out > 0 && $Qty_Proses > 0) {
											$Flag_Warehouse	= $valRec->flag_warehouse;
											$Qty_Update	= $Qty_Out;
											if ($Qty_Proses < $Qty_Out) {
												$Qty_Update	= $Qty_Proses;
											}
											if ($Flag_Warehouse === 'Y') {
												$Upd_Rec_Detail	= "UPDATE quotation_detail_receives SET qty_so = qty_so - " . $Qty_Update . ", letter_order_id = NULL WHERE id = '" . $valRec->code_rec_warehouse . "'";
												$Has_Upd_RecDet	= $this->db->query($Upd_Rec_Detail);
												if ($Has_Upd_RecDet !== TRUE) {
													$Pesan_Error	= 'Error Update Quotation Receive Detail...';
												}

												$Upd_Rec_Driver		= "UPDATE quotation_driver_detail_receives SET qty_so = qty_so - " . $Qty_Update . ", letter_order_id = NULL WHERE id = '" . $valRec->id . "'";
												$Has_Upd_RecDriver	= $this->db->query($Upd_Rec_Driver);
												if ($Has_Upd_RecDriver !== TRUE) {
													$Pesan_Error	= 'Error Update Quotation Driver Receive Detail...';
												}
											} else {
												$Upd_Rec_Detail	= "UPDATE quotation_driver_detail_receives SET qty_so = qty_so - " . $Qty_Update . ", letter_order_id = NULL WHERE id = '" . $valRec->id . "'";
												$Has_Upd_RecDet	= $this->db->query($Upd_Rec_Detail);
												if ($Has_Upd_RecDet !== TRUE) {
													$Pesan_Error	= 'Error Update Quotation Driver Receive Detail...';
												}
											}


											$Qty_Proses	-= $Qty_Update;
										}
									}
								}
							}
						}
					}

					$Qry_Upd_Order	= "UPDATE letter_orders SET sts_so ='CNC', cancel_by = '" . $Created_By . "', cancel_date = '" . $Created_Date . "', reason = '" . $Cancel_Reason . "' WHERE id = '" . $Code_Order . "'";
					$Has_Upd_Order 	= $this->db->query($Qry_Upd_Order);
					if ($Has_Upd_Order !== TRUE) {
						$Pesan_Error	= 'Error Update Letter Order Header';
					}



					if ($this->db->trans_status() != TRUE || !empty($Pesan_Error)) {
						$this->db->trans_rollback();
						$rows_Return		= array(
							'status'		=> 2,
							'pesan'			=> 'Cancellation Process  Failed, ' . $Pesan_Error
						);
						history('Cancellation Sales Order ' . $Code_Order . ' - ' . $Pesan_Error);
					} else {
						$this->db->trans_commit();
						$rows_Return		= array(
							'status'		=> 1,
							'pesan'			=> 'Cancellation process success. Thank you & have a nice day......'
						);
						history('Cancellation Sales Order ' . $Code_Order . ' - ' . $Cancel_Reason);
					}
				}
			} else {
				$rows_Return	= array(
					'status'		=> 2,
					'pesan'			=> 'No Record was found...'
				);
			}
		}
		echo json_encode($rows_Return);
	}

	function revisi_sales_order()
	{
		$rows_Header	= $rows_Quot = $rows_Detail = $rows_Outs = $rows_Plant = array();
		$Tgl_Old		= date('Y-m-d');
		$Noso_Rev		= '';
		$Urut_Rev		= '';
		$Code_Old		= '';
		if ($this->input->get()) {
			$Code_Process	= urldecode($this->input->get('nomor_order'));
			$rows_Header	= $this->db->get_where('letter_orders', array('id' => $Code_Process))->row();
			$rows_Detail	= $this->db->get_where('letter_order_details', array('letter_order_id' => $Code_Process))->result();
			$rows_Quot		= $this->db->get_where('quotations', array('id' => $rows_Header->quotation_id))->row();

			$Query_Plant	= "SELECT id,branch FROM plants WHERE customer_id = '" . $rows_Header->customer_id . "' AND NOT(branch IS NULL OR branch ='' OR branch='-')";
			$rows_Plant		= $this->db->query($Query_Plant)->result();

			$Nomor_SO		= $rows_Header->no_so;
			$Code_Old		= (isset($rows_Header->old_id) && !empty($rows_Header->old_id)) ? $rows_Header->old_id : $Code_Process;
			$rows_HeadOld	= $this->db->get_where('letter_orders', array('id' => $Code_Old))->row();
			if ($rows_HeadOld) {
				$Urut_Rev		= intval($rows_HeadOld->revisi) + 1;
				$Nomor_SO		= $rows_HeadOld->no_so;
				$Tgl_Old		= $rows_HeadOld->tgl_so;
			}

			$Noso_Rev			= $Nomor_SO . '/Rev-' . $Urut_Rev;

			$Query_Detail	= "SELECT
									det_rec.tool_id,
									det_rec.tool_name,
									det_rec.quotation_detail_id,
									det_rec.quotation_id,
									SUM(
										det_rec.qty_rec - det_rec.qty_so
									) AS total_outs,
									GROUP_CONCAT(det_rec.id) AS code_detail,
									GROUP_CONCAT(det_rec.descr) AS descr_receive,
									det_quot.cust_tool,
									det_quot.range,
									det_quot.piece_id,
									det_quot.supplier_id,
									det_quot.supplier_name,
									det_quot.descr AS cust_request
								FROM
									quotation_detail_receives det_rec
								INNER JOIN quotation_details det_quot ON det_rec.quotation_detail_id = det_quot.id
								WHERE
									(
										det_rec.qty_rec - det_rec.qty_so
									) > 0
								AND det_rec.quotation_id = '" . $rows_Header->quotation_id . "'
								GROUP BY
									det_rec.quotation_detail_id";
			$rows_Outs	= $this->db->query($Query_Detail)->result();
		}
		$rows_Supplier		= $this->master_model->getArray('suppliers', array('id !=' => 'COMP-001'), 'id', 'supplier');
		$data = array(
			'title'			=> 'REVISI SALES ORDER - RECEIVE',
			'action'		=> 'revisi_sales_order',
			'akses_menu'	=> $this->Arr_Akses,
			'rows_header'	=> $rows_Header,
			'rows_detail'	=> $rows_Detail,
			'rows_quot'		=> $rows_Quot,
			'rows_supplier'	=> $rows_Supplier,
			'tgl_old'		=> $Tgl_Old,
			'noso_rev'		=> $Noso_Rev,
			'urut_rev'		=> $Urut_Rev,
			'code_old'		=> $Code_Old,
			'rows_outs'		=> $rows_Outs,
			'rows_plant'	=> $rows_Plant
		);

		$this->load->view($this->folder . '/v_sales_order_receive_revisi', $data);
	}

	function save_revisi_letter_order()
	{
		$rows_Return	= array(
			'status'		=> 2,
			'pesan'			=> 'No record was found...'
		);

		if ($this->input->post()) {
			//echo "<pre>";print_r($this->input->post());exit;



			$Created_By		= $this->session->userdata('siscal_username');
			$Created_Id		= $this->session->userdata('siscal_userid');
			$Created_Date	= date('Y-m-d H:i:s');

			$Nomor_Letter	= $this->input->post('no_so');
			$Urut_Revisi	= $this->input->post('revisi');
			$Old_Code_Letter = $this->input->post('old_id');
			$Pre_Code_Letter = $this->input->post('prev_id');

			$Date_Letter	= $this->input->post('tgl_so');

			$Code_Quot		= $this->input->post('quotation_id');
			$Nomor_Quot		= $this->input->post('quotation_nomor');
			$Nocust			= $this->input->post('customer_id');
			$Customer		= $this->input->post('customer_name');
			$PIC_Name		= strtoupper($this->input->post('pic_name'));
			$PIC_Phone		= str_replace(array('+', '-', ' '), '', $this->input->post('pic_phone'));

			$Delv_Address	= $this->input->post('address_send');
			$Inv_Address	= $this->input->post('address_inv');
			$Cert_Address	= $this->input->post('address_sertifikat');
			$Cust_Address	= $this->input->post('address');



			$Delv_Notes		= strtoupper($this->input->post('send_notes'));
			$Inv_Notes		= strtoupper($this->input->post('inv_notes'));

			$detDetail		= $this->input->post('detDetail');

			$OK_Proses		= 1;
			$Pesan_Error	= '';
			$rows_Exists	= $this->db->get_where('letter_orders', array('id' => $Pre_Code_Letter))->row();
			if ($rows_Exists) {
				if ($rows_Exists->sts_so !== 'OPN') {
					$OK_Proses		= 0;
					$Pesan_Error	= 'Data has been modified by other process...';
				}
			} else {
				$OK_Proses		= 0;
				$Pesan_Error	= 'No record was found...';
			}

			if ($OK_Proses === 0) {
				$rows_Return		= array(
					'status'		=> 2,
					'pesan'			=> $Pesan_Error
				);
			} else {

				$this->db->trans_begin();

				## AMBIL DATA SPK DETAIL TOOL ##
				$rows_Detail		= $this->db->get_where('letter_order_details', array('letter_order_id' => $Pre_Code_Letter))->result();
				if ($rows_Detail) {
					$intL	= 0;
					foreach ($rows_Detail as $keyTool => $valTool) {
						$intL++;
						$Qty			= $valTool->qty;
						$Code_Receive	= $valTool->detail_id;

						$Qty_Proses		= $Qty;
						$Imp_Code_Rec	= str_replace(",", "','", $Code_Receive);
						$Qry_Receive	= "SELECT * FROM quotation_detail_receives WHERE id IN('" . $Imp_Code_Rec . "')";
						$rows_Receive	= $this->db->query($Qry_Receive)->result();
						if ($rows_Receive) {
							foreach ($rows_Receive as $keyRec => $valRec) {
								$Qty_Out	= $valRec->qty_so;
								if ($Qty_Out > 0 && $Qty_Proses > 0) {
									$Qty_Update	= $Qty_Out;
									if ($Qty_Proses < $Qty_Out) {
										$Qty_Update	= $Qty_Proses;
									}

									$Upd_Rec_Detail	= "UPDATE quotation_detail_receives SET qty_so = qty_so - " . $Qty_Update . ", letter_order_id = NULL WHERE id = '" . $valRec->id . "'";
									$Has_Upd_RecDet	= $this->db->query($Upd_Rec_Detail);
									if ($Has_Upd_RecDet !== TRUE) {
										$Pesan_Error	= 'Error Insert Quotation Receive Detail...';
									}

									$Qty_Proses	-= $Qty_Update;
								}
							}
						} else {

							## ANTICIPATE IF SO CREATED BEFORE WAREHOUSE RECEIVING ##
							$Qry_Receive	= "SELECT * FROM quotation_driver_detail_receives WHERE id IN('" . $Imp_Code_Rec . "')";
							$rows_Receive	= $this->db->query($Qry_Receive)->result();
							if ($rows_Receive) {
								foreach ($rows_Receive as $keyRec => $valRec) {
									$Qty_Out	= 1;
									if ($Qty_Out > 0 && $Qty_Proses > 0) {
										$Flag_Warehouse	= $valRec->flag_warehouse;
										$Qty_Update	= $Qty_Out;
										if ($Qty_Proses < $Qty_Out) {
											$Qty_Update	= $Qty_Proses;
										}
										if ($Flag_Warehouse === 'Y') {
											$Upd_Rec_Detail	= "UPDATE quotation_detail_receives SET qty_so = qty_so - " . $Qty_Update . ", letter_order_id = NULL WHERE id = '" . $valRec->code_rec_warehouse . "'";
											$Has_Upd_RecDet	= $this->db->query($Upd_Rec_Detail);
											if ($Has_Upd_RecDet !== TRUE) {
												$Pesan_Error	= 'Error Update Quotation Receive Detail - OLD...';
											}

											$Upd_Rec_Driver		= "UPDATE quotation_driver_detail_receives SET qty_so = qty_so - " . $Qty_Update . ", letter_order_id = NULL WHERE id = '" . $valRec->id . "'";
											$Has_Upd_RecDriver	= $this->db->query($Upd_Rec_Driver);
											if ($Has_Upd_RecDriver !== TRUE) {
												$Pesan_Error	= 'Error Update Quotation Driver Receive Detail - OLD ...';
											}
										} else {
											$Upd_Rec_Detail	= "UPDATE quotation_driver_detail_receives SET qty_so = qty_so - " . $Qty_Update . ", letter_order_id = NULL WHERE id = '" . $valRec->id . "'";
											$Has_Upd_RecDet	= $this->db->query($Upd_Rec_Detail);
											if ($Has_Upd_RecDet !== TRUE) {
												$Pesan_Error	= 'Error Update Quotation Driver Receive Detail - OLD...';
											}
										}


										$Qty_Proses	-= $Qty_Update;
									}
								}
							}
						}
					}
				}



				$Date_Now		= date('Y-m-d');
				$Tahun_Now		= date('Y');
				$Month_Now		= date('m');
				$Bulan_Now		= date('n');
				$YearMonth		= date('Ym');



				## AMBIL NOMOR URUT ##
				$Urut_Code		= $Urut_Nomor	= 1;
				$Qry_Urut_Code	= "SELECT
										MAX(
											CAST(
												SUBSTRING_INDEX(id, '-' ,- 1) AS UNSIGNED
											)
										) AS nomor_urut
									FROM
										letter_orders
									WHERE
										YEAR (created_date) = '" . $Tahun_Now . "'";
				$rows_Urut_Code	= $this->db->query($Qry_Urut_Code)->row();
				if ($rows_Urut_Code) {
					$Urut_Code	= intval($rows_Urut_Code->nomor_urut) + 1;
				}
				$Pref_Urut		= sprintf('%05d', $Urut_Code);
				if ($Urut_Code >= 100000) {
					$Pref_Urut	= $Urut_Code;
				}

				$Code_Letter		= 'SO-V3' . $YearMonth . '-' . $Pref_Urut;

				$Upd_Old			= array(
					'revisi'		=> $Urut_Revisi,
					'modified_date'	=> $Created_Date,
					'modified_by'	=> $Created_Id
				);

				$Update_Prev	= array(
					'sts_so'		=> 'REV',
					'modified_date'	=> $Created_Date,
					'modified_by'	=> $Created_Id
				);

				$Ins_Header			= array(
					'id'					=> $Code_Letter,
					'no_so'					=> $Nomor_Letter,
					'tgl_so'				=> $Date_Letter,
					'quotation_id'			=> $Code_Quot,
					'customer_id'			=> $Nocust,
					'customer_name'			=> $Customer,
					'address'				=> $Cust_Address,
					'address_inv'			=> $Inv_Address,
					'address_sertifikat'	=> $Cert_Address,
					'address_send'			=> $Delv_Address,
					'pic'					=> $PIC_Name,
					'phone'					=> $PIC_Phone,
					'sts_so'				=> 'OPN',
					'created_date'			=> $Created_Date,
					'created_by'			=> $Created_Id,
					'get_tool'				=> '',
					'flag_so_insitu'		=> 'N',
					'notes_invoice'			=> $Inv_Notes,
					'notes_delivery'		=> $Delv_Notes,
					'old_id'				=> $Old_Code_Letter
				);
				$Arr_Type		= array('I' => 'Insitu', 'L' => 'Labs', 'S' => 'Subcon');
				$Get_Tool		= '';
				if ($detDetail) {
					$intL	= 0;
					foreach ($detDetail as $keyDet => $valDet) {
						$intL++;
						$Code_LetterDet	= $Code_Letter . '-' . $intL;
						$Code_Tool		= $valDet['tool_id'];
						$Name_Tool		= $valDet['tool_name'];
						$Supplier_Code	= $valDet['supplier'];
						$Supplier_Name	= '-';
						$Type			= $valDet['tipe'];
						$Code_Receive	= $valDet['code_detail'];
						$Qty			= $valDet['qty'];
						$Range			= $valDet['range'];
						$Satuan			= $valDet['piece_id'];
						$Get_Tool		= $valDet['get_tool'];
						$Description	= $valDet['descr'];
						$Code_QuotDet	= $valDet['quotation_detail_id'];

						$rows_Supplier	= $this->db->get_where('suppliers', array('id' => $Supplier_Code))->row();
						if ($rows_Supplier) {
							$Supplier_Name	= $rows_Supplier->supplier;
						}


						$Ins_Detail		= array(
							'id'					=> $Code_LetterDet,
							'letter_order_id'		=> $Code_Letter,
							'quotation_detail_id'	=> $Code_QuotDet,
							'tool_id'				=> $Code_Tool,
							'tool_name'				=> $Name_Tool,
							'range'					=> $Range,
							'piece_id'				=> $Satuan,
							'qty'					=> $Qty,
							'supplier_id'			=> $Supplier_Code,
							'supplier_name'			=> $Supplier_Name,
							'descr'					=> $Description,
							'tipe'					=> $Arr_Type[$Type],
							'get_tool'				=> $Get_Tool,
							'detail_id'				=> $Code_Receive,
							'created_by'			=> $Created_Id,
							'created_date'			=> $Created_Date
						);

						$Has_Ins_Detail	= $this->db->insert('letter_order_details', $Ins_Detail);
						if ($Has_Ins_Detail !== TRUE) {
							$Pesan_Error	= 'Error Insert Letter Order Detail...';
						}

						$Qty_Proses		= $Qty;
						$Imp_Code_Rec	= str_replace(",", "','", $Code_Receive);
						$Qry_Receive	= "SELECT * FROM quotation_detail_receives WHERE id IN('" . $Imp_Code_Rec . "')";
						$rows_Receive	= $this->db->query($Qry_Receive)->result();
						if ($rows_Receive) {
							foreach ($rows_Receive as $keyRec => $valRec) {
								$Qty_Out	= $valRec->qty_rec - $valRec->qty_so;
								if ($Qty_Out > 0 && $Qty_Proses > 0) {
									$Qty_Update	= $Qty_Out;
									if ($Qty_Proses < $Qty_Out) {
										$Qty_Update	= $Qty_Proses;
									}

									$Upd_Rec_Detail	= "UPDATE quotation_detail_receives SET qty_so = qty_so + " . $Qty_Update . ", letter_order_id = '" . $Code_Letter . "' WHERE id = '" . $valRec->id . "'";
									$Has_Upd_RecDet	= $this->db->query($Upd_Rec_Detail);
									if ($Has_Upd_RecDet !== TRUE) {
										$Pesan_Error	= 'Error Update Quotation Receive Detail - NEW ...';
									}

									$Qty_Proses	-= $Qty_Update;
								}
							}
						} else {
							## ANTICIPATE IF SO CREATED BEFORE WAREHOUSE RECEIVING ##
							$Qry_Receive	= "SELECT * FROM quotation_driver_detail_receives WHERE id IN('" . $Imp_Code_Rec . "')";
							$rows_Receive	= $this->db->query($Qry_Receive)->result();
							if ($rows_Receive) {
								foreach ($rows_Receive as $keyRec => $valRec) {
									$Qty_Out	= 1;
									if ($Qty_Out > 0 && $Qty_Proses > 0) {
										$Flag_Warehouse	= $valRec->flag_warehouse;
										$Qty_Update	= $Qty_Out;
										if ($Qty_Proses < $Qty_Out) {
											$Qty_Update	= $Qty_Proses;
										}
										if ($Flag_Warehouse === 'Y') {
											$Upd_Rec_Detail	= "UPDATE quotation_detail_receives SET qty_so = qty_so + " . $Qty_Update . ", letter_order_id = '" . $Code_Letter . "' WHERE id = '" . $valRec->code_rec_warehouse . "'";
											$Has_Upd_RecDet	= $this->db->query($Upd_Rec_Detail);
											if ($Has_Upd_RecDet !== TRUE) {
												$Pesan_Error	= 'Error Update Quotation Receive Detail - NEW ...';
											}

											$Upd_Rec_Driver		= "UPDATE quotation_driver_detail_receives SET qty_so = qty_so + " . $Qty_Update . ", letter_order_id = '" . $Code_Letter . "' WHERE id = '" . $valRec->id . "'";
											$Has_Upd_RecDriver	= $this->db->query($Upd_Rec_Driver);
											if ($Has_Upd_RecDriver !== TRUE) {
												$Pesan_Error	= 'Error Update Quotation Driver Receive Detail - NEW ...';
											}
										} else {
											$Upd_Rec_Detail	= "UPDATE quotation_driver_detail_receives SET qty_so = qty_so + " . $Qty_Update . ", letter_order_id = '" . $Code_Letter . "' WHERE id = '" . $valRec->id . "'";
											$Has_Upd_RecDet	= $this->db->query($Upd_Rec_Detail);
											if ($Has_Upd_RecDet !== TRUE) {
												$Pesan_Error	= 'Error Update Quotation Driver Receive Detail - NEW...';
											}
										}


										$Qty_Proses	-= $Qty_Update;
									}
								}
							}
						}

						if ($Qty_Proses > 0) {
							$Pesan_Error	= 'Qty SO Tool Should Less Then Leftover Qty...';
						}
					}
				}

				$Ins_Header['get_tool']	= $Get_Tool;
				$Has_Ins_Header			= $this->db->insert('letter_orders', $Ins_Header);
				if ($Has_Ins_Header !== TRUE) {
					$Pesan_Error	= 'Error Insert Letter Order Header...';
				}



				$Has_Old_Order			= $this->db->update('letter_orders', $Upd_Old, array('id' => $Old_Code_Letter));
				if ($Has_Old_Order !== TRUE) {
					$Pesan_Error	= 'Error Update Letter Order - OLD';
				}

				$Has_Prev_Order			= $this->db->update('letter_orders', $Update_Prev, array('id' => $Pre_Code_Letter));
				if ($Has_Prev_Order !== TRUE) {
					$Pesan_Error	= 'Error Update Letter Order - PREV';
				}

				if ($this->db->trans_status() != TRUE || !empty($Pesan_Error)) {
					$this->db->trans_rollback();
					$rows_Return		= array(
						'status'		=> 2,
						'pesan'			=> 'Revision Sales Order Process  Failed, ' . $Pesan_Error
					);
					history('Revision Sales Order ' . $Nomor_Letter . ' - ' . $Pesan_Error);
				} else {
					$this->db->trans_commit();
					$rows_Return		= array(
						'status'		=> 1,
						'pesan'			=> 'Revision Sales Order success. Thank you & have a nice day......'
					);
					history('Revision Sales Order ' . $Nomor_Letter . ' - Success ');
				}
			}
		}

		echo json_encode($rows_Return);
	}


	function outs_letter_order_driver()
	{
		$Arr_Akses			= $this->Arr_Akses;
		if ($Arr_Akses['create'] != '1') {
			$this->session->set_flashdata("alert_data", "<div class=\"alert alert-warning\" id=\"flash-message\">You Don't Have Right To Access This Page, Please Contact Your Administrator....</div>");
			redirect(site_url('Sales_order_receive'));
		}

		$data = array(
			'title'			=> 'CREATE SALES ORDER - DRIVER RECEIVE',
			'action'		=> 'outs_letter_order_driver',
			'akses_menu'	=> $Arr_Akses
		);

		$this->load->view($this->folder . '/v_sales_order_driver_outs', $data);
	}

	function display_out_sales_order_driver()
	{
		$Arr_Akses			= $this->Arr_Akses;
		$User_Groupid	= $this->session->userdata('siscal_group_id');
		$WHERE			= "header_rec.flag_sign = 'Y'
							AND header_rec.flag_warehouse = 'N'
							/*
							AND header_rec.flag_so = 'N'
							*/
							AND (
								det_rec.letter_order_id IS NULL
								OR det_rec.letter_order_id = ''
								OR det_rec.letter_order_id = '-'
							)";
		if ($User_Groupid == '2') {
			$User_Member	= $this->session->userdata('siscal_member_id');
			if (!empty($WHERE)) $WHERE	.= " AND ";
			$WHERE	.= "header_quot.member_id = '" . $User_Member . "'";
		}

		$Month_Find		= $this->input->post('bulan');
		$Year_Find		= $this->input->post('tahun');
		$requestData	= $_REQUEST;

		$like_value     = $requestData['search']['value'];
		$column_order   = $requestData['order'][0]['column'];
		$column_dir     = $requestData['order'][0]['dir'];
		$limit_start    = $requestData['start'];
		$limit_length   = $requestData['length'];

		$columns_order_by = array(
			0 => 'header_rec.driver_name',
			1 => 'header_rec.datet',
			2 => 'header_quot.nomor',
			3 => 'header_rec.customer_name',
			4 => 'header_quot.pono',
			5 => 'header_quot.podate',
			6 => 'header_quot.member_name'
		);



		if ($like_value) {
			if (!empty($WHERE)) $WHERE	.= " AND ";
			$WHERE	.= "(
						  header_rec.driver_name LIKE '%" . $this->db->escape_like_str($like_value) . "%'
						  OR DATE_FORMAT(header_rec.datet, '%d %b %Y') LIKE '%" . $this->db->escape_like_str($like_value) . "%'
						  OR header_quot.nomor LIKE '%" . $this->db->escape_like_str($like_value) . "%'
						  OR header_rec.customer_name LIKE '%" . $this->db->escape_like_str($like_value) . "%'
						  OR header_quot.pono LIKE '%" . $this->db->escape_like_str($like_value) . "%'
						  OR DATE_FORMAT(header_quot.podate, '%d %b %Y') LIKE '%" . $this->db->escape_like_str($like_value) . "%'
						  OR header_quot.member_name LIKE '%" . $this->db->escape_like_str($like_value) . "%'
						)";
		}



		$sql = "SELECT
					header_rec.*,
					header_quot.id AS code_quotation,
					header_quot.nomor AS nomor_quotation,
					header_quot.podate,
					header_quot.pono,
					header_quot.member_id AS code_sales,
					header_quot.member_name AS name_sales,
					(@row:=@row+1) AS urut
				FROM
					quotation_driver_detail_receives det_rec
				INNER JOIN quotation_driver_receives header_rec ON det_rec.code_receive = header_rec.id
				INNER JOIN quotations header_quot ON det_rec.quotation_id = header_quot.id,
				(SELECT @row:=0) r 
				WHERE " . $WHERE . "
				GROUP BY 
					det_rec.quotation_id,
					header_rec.id
				";
		//print_r($sql);exit();
		$fetch['totalData'] 	= $this->db->query($sql)->num_rows();
		$fetch['totalFiltered']	= $this->db->query($sql)->num_rows();



		$sql .= " ORDER BY header_rec.datet DESC," . $columns_order_by[$column_order] . " " . $column_dir . " ";
		$sql .= " LIMIT " . $limit_start . " ," . $limit_length . " ";

		$fetch['query'] = $this->db->query($sql);

		$totalData		= $fetch['totalData'];
		$totalFiltered	= $fetch['totalFiltered'];
		$query			= $fetch['query'];

		$data		= array();
		$urut1  	= 1;
		$urut2  	= 0;
		$Periode_Now = date('Y-m');
		$Tahun_Now	= date('Y');
		foreach ($query->result_array() as $row) {
			$total_data     = $totalData;
			$start_dari     = $requestData['start'];
			$asc_desc       = $requestData['order'][0]['dir'];
			if ($asc_desc == 'asc') {
				$nomor = $urut1 + $start_dari;
			}
			if ($asc_desc == 'desc') {
				$nomor = ($total_data - $start_dari) - $urut2;
			}

			$Code_Receive	= $row['id'];
			$Nomor_Receive	= $row['nomor'];
			$Date_Receive	= date('d-m-Y', strtotime($row['datet']));
			$Custid			= $row['customer_id'];
			$Customer		= $row['customer_name'];
			$Marketing		= strtoupper($row['name_sales']);
			$Driver			= strtoupper($row['driver_name']);
			$Quot_PO		= $row['pono'];
			$Quot_PO_Date	= date('d-m-Y', strtotime($row['podate']));

			$Code_Quot		= $row['code_quotation'];
			$Nomor_Quot		= $row['nomor_quotation'];


			$Template		= '';
			if ($Arr_Akses['create'] == '1') {
				$Template		= '<a href="' . site_url() . '/Sales_order_receive/create_sales_order_driver?nomor_rec=' . urlencode($Code_Receive) . '&nomor_quot=' . urlencode($Code_Quot) . '" class="btn btn-sm bg-navy-active"  title="CREATE SALES ORDER - DRIVER RECEIVE"> <i class="fa fa-plus"></i> </a>';
			}




			$nestedData		= array();
			$nestedData[]	= $Driver;
			$nestedData[]	= $Date_Receive;
			$nestedData[]	= $Nomor_Quot;
			$nestedData[]	= $Customer;
			$nestedData[]	= $Quot_PO;
			$nestedData[]	= $Quot_PO_Date;
			$nestedData[]	= $Marketing;
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


	function create_sales_order_driver()
	{
		$rows_Header	= $rows_Customer = $rows_Detail = $rows_Receive = $rows_Plant = array();
		if ($this->input->get()) {
			$Code_Quot		= urldecode($this->input->get('nomor_quot'));
			$Code_Receive	= urldecode($this->input->get('nomor_rec'));
			$rows_Receive	= $this->db->get_where('quotation_driver_receives', array('id' => $Code_Receive))->row();
			$rows_Header	= $this->db->get_where('quotations', array('id' => $Code_Quot))->row();
			$rows_Customer	= $this->db->get_where('customers', array('id' => $rows_Header->customer_id))->row();

			$Query_Plant	= "SELECT id,branch FROM plants WHERE customer_id = '" . $rows_Header->customer_id . "' AND NOT(branch IS NULL OR branch ='' OR branch='-')";
			$rows_Plant		= $this->db->query($Query_Plant)->result();

			$Query_Detail	= "SELECT
									det_rec.tool_id,
									det_rec.tool_name,
									det_rec.quotation_detail_id,
									det_rec.quotation_id,
									COUNT(det_rec.id) AS total_outs,
									GROUP_CONCAT(det_rec.id) AS code_detail,
									GROUP_CONCAT(det_rec.descr) AS descr_receive,
									det_quot.cust_tool,
									det_quot.`range`,
									det_quot.piece_id,
									det_quot.supplier_id,
									det_quot.supplier_name,
									det_quot.descr AS cust_request
								FROM
									quotation_driver_detail_receives det_rec
								INNER JOIN quotation_details det_quot ON det_rec.quotation_detail_id = det_quot.id
								WHERE
									(
										det_rec.letter_order_id IS NULL
										OR det_rec.letter_order_id = '-'
										OR det_rec.letter_order_id = ''
									)
								AND det_rec.quotation_id = '" . $Code_Quot . "'
								AND code_receive = '" . $Code_Receive . "'
								GROUP BY
									det_rec.quotation_detail_id";
			$rows_Detail	= $this->db->query($Query_Detail)->result();
		}
		$rows_Supplier		= $this->master_model->getArray('suppliers', array('id !=' => 'COMP-001'), 'id', 'supplier');
		$data = array(
			'title'			=> 'CREATE SALES ORDER - DRIVER RECEIVE',
			'action'		=> 'create_sales_order_driver',
			'akses_menu'	=> $this->Arr_Akses,
			'rows_header'	=> $rows_Header,
			'rows_detail'	=> $rows_Detail,
			'rows_cust'		=> $rows_Customer,
			'rows_receive'	=> $rows_Receive,
			'rows_supplier'	=> $rows_Supplier,
			'rows_plant'	=> $rows_Plant
		);

		$this->load->view($this->folder . '/v_sales_order_driver_add', $data);
	}


	function save_create_letter_order_driver()
	{
		$rows_Return	= array(
			'status'		=> 2,
			'pesan'			=> 'No record was found...'
		);

		if ($this->input->post()) {
			//echo "<pre>";print_r($this->input->post());exit;


			$Created_By		= $this->session->userdata('siscal_username');
			$Created_Id		= $this->session->userdata('siscal_userid');
			$Created_Date	= date('Y-m-d H:i:s');

			$Code_Receive	= $this->input->post('code_receive');
			$Code_Quot		= $this->input->post('quotation_id');
			$Nomor_Quot		= $this->input->post('quotation_nomor');
			$Nocust			= $this->input->post('customer_id');
			$Customer		= $this->input->post('customer_name');
			$PIC_Name		= strtoupper($this->input->post('pic_name'));
			$PIC_Phone		= str_replace(array('+', '-', ' '), '', $this->input->post('pic_phone'));

			$Delv_Address	= $this->input->post('address_send');
			$Inv_Address	= $this->input->post('address_inv');
			$Cert_Address	= $this->input->post('address_sertifikat');
			$Cust_Address	= $this->input->post('address');



			$Delv_Notes		= strtoupper($this->input->post('send_notes'));
			$Inv_Notes		= strtoupper($this->input->post('inv_notes'));

			$detDetail		= $this->input->post('detDetail');
			$Pesan_Error	= '';
			$OK_Proses		= 1;
			$rows_Receive	= $this->db->get_where('quotation_driver_receives', array('id' => $Code_Receive))->row();
			if ($rows_Receive) {
				if ($rows_Receive->flag_warehouse === 'Y') {
					$Pesan_Error	= 'Data has been receive by warehouse. Please process SO Receive By Warehouse...';
					$OK_Proses		= 0;
				}
			} else {
				$Pesan_Error	= 'No receive record was found...';
				$OK_Proses		= 0;
			}

			if ($OK_Proses === 0) {
				$rows_Return	= array(
					'status'		=> 2,
					'pesan'			=> $Pesan_Error
				);
			} else {

				$this->db->trans_begin();

				$Date_Now		= date('Y-m-d');
				$Tahun_Now		= date('Y');
				$Month_Now		= date('m');
				$Bulan_Now		= date('n');
				$YearMonth		= date('Ym');

				$RomawiMonth	= getRomawi($Bulan_Now);

				## AMBIL NOMOR URUT ##
				$Urut_Code		= $Urut_Nomor	= 1;
				$Qry_Urut_Code	= "SELECT
										MAX(
											CAST(
												SUBSTRING_INDEX(id, '-' ,- 1) AS UNSIGNED
											)
										) AS nomor_urut
									FROM
										letter_orders
									WHERE
										YEAR (created_date) = '" . $Tahun_Now . "'";
				$rows_Urut_Code	= $this->db->query($Qry_Urut_Code)->row();
				if ($rows_Urut_Code) {
					$Urut_Code	= intval($rows_Urut_Code->nomor_urut) + 1;
				}
				$Pref_Urut		= sprintf('%05d', $Urut_Code);
				if ($Urut_Code >= 100000) {
					$Pref_Urut	= $Urut_Code;
				}

				$Code_Letter		= 'SO-V3' . $YearMonth . '-' . $Pref_Urut;

				$Nomor_Project		= '';
				$Qry_Urut_Nomor		= "SELECT project_code, project_no FROM quotations WHERE id = '" . $Code_Quot . "'";
				$rows_Urut_Nomor	= $this->db->query($Qry_Urut_Nomor)->row();
				if ($rows_Urut_Nomor) {
					$Urut_Nomor		= intval($rows_Urut_Nomor->project_no) + 1;
					$Nomor_Project	= $rows_Urut_Nomor->project_code;
				}

				$Pref_Nomor			= getColsChar($Urut_Nomor);
				$Nomor_Letter		= $Pref_Nomor . '-' . $Nomor_Project;

				$Ins_Header			= array(
					'id'					=> $Code_Letter,
					'no_so'					=> $Nomor_Letter,
					'tgl_so'				=> $Date_Now,
					'quotation_id'			=> $Code_Quot,
					'customer_id'			=> $Nocust,
					'customer_name'			=> $Customer,
					'address'				=> $Cust_Address,
					'address_inv'			=> $Inv_Address,
					'address_sertifikat'	=> $Cert_Address,
					'address_send'			=> $Delv_Address,
					'pic'					=> $PIC_Name,
					'phone'					=> $PIC_Phone,
					'sts_so'				=> 'OPN',
					'created_date'			=> $Created_Date,
					'created_by'			=> $Created_Id,
					'get_tool'				=> '',
					'flag_so_insitu'		=> 'N',
					'notes_invoice'			=> $Inv_Notes,
					'notes_delivery'		=> $Delv_Notes
				);
				$Arr_Type		= array('I' => 'Insitu', 'L' => 'Labs', 'S' => 'Subcon');
				$Get_Tool		= '';
				if ($detDetail) {
					$intL	= 0;
					foreach ($detDetail as $keyDet => $valDet) {
						$intL++;
						$Code_LetterDet	= $Code_Letter . '-' . $intL;
						$Code_Tool		= $valDet['tool_id'];
						$Name_Tool		= $valDet['tool_name'];
						$Supplier_Code	= $valDet['supplier'];
						$Supplier_Name	= '-';
						$Type			= $valDet['tipe'];
						$Code_Receive	= $valDet['code_detail'];
						$Qty			= $valDet['qty'];
						$Range			= $valDet['range'];
						$Satuan			= $valDet['piece_id'];
						$Get_Tool		= $valDet['get_tool'];
						$Description	= $valDet['descr'];
						$Code_QuotDet	= $valDet['quotation_detail_id'];

						$rows_Supplier	= $this->db->get_where('suppliers', array('id' => $Supplier_Code))->row();
						if ($rows_Supplier) {
							$Supplier_Name	= $rows_Supplier->supplier;
						}


						$Ins_Detail		= array(
							'id'					=> $Code_LetterDet,
							'letter_order_id'		=> $Code_Letter,
							'quotation_detail_id'	=> $Code_QuotDet,
							'tool_id'				=> $Code_Tool,
							'tool_name'				=> $Name_Tool,
							'range'					=> $Range,
							'piece_id'				=> $Satuan,
							'qty'					=> $Qty,
							'supplier_id'			=> $Supplier_Code,
							'supplier_name'			=> $Supplier_Name,
							'descr'					=> $Description,
							'tipe'					=> $Arr_Type[$Type],
							'get_tool'				=> $Get_Tool,
							'detail_id'				=> $Code_Receive,
							'created_by'			=> $Created_Id,
							'created_date'			=> $Created_Date
						);

						$Has_Ins_Detail	= $this->db->insert('letter_order_details', $Ins_Detail);
						if ($Has_Ins_Detail !== TRUE) {
							$Pesan_Error	= 'Error Insert Letter Order Detail...';
						}

						$Qty_Proses		= $Qty;
						$Imp_Code_Rec	= str_replace(",", "','", $Code_Receive);
						$Qry_Receive	= "SELECT * FROM quotation_driver_detail_receives WHERE id IN('" . $Imp_Code_Rec . "') AND (letter_order_id IS NULL OR letter_order_id = '' OR letter_order_id = '-')";
						$rows_Receive	= $this->db->query($Qry_Receive)->result();
						if ($rows_Receive) {
							foreach ($rows_Receive as $keyRec => $valRec) {
								$Qty_Out	= 1;
								if ($Qty_Out > 0 && $Qty_Proses > 0) {
									$Qty_Update	= $Qty_Out;
									if ($Qty_Proses < $Qty_Out) {
										$Qty_Update	= $Qty_Proses;
									}

									$Upd_Rec_Detail	= "UPDATE quotation_driver_detail_receives SET qty_so = " . $Qty_Update . ", letter_order_id = '" . $Code_Letter . "' WHERE id = '" . $valRec->id . "'";
									$Has_Upd_RecDet	= $this->db->query($Upd_Rec_Detail);
									if ($Has_Upd_RecDet !== TRUE) {
										$Pesan_Error	= 'Error Update Quotation Driver Receive Detail...';
									}

									$Qty_Proses	-= $Qty_Update;
								}
							}
						}

						if ($Qty_Proses > 0) {
							$Pesan_Error	= 'Qty SO Tool Should Less Then Leftover Qty...';
						}
					}
				}

				$Ins_Header['get_tool']	= $Get_Tool;
				$Has_Ins_Header			= $this->db->insert('letter_orders', $Ins_Header);
				if ($Has_Ins_Header !== TRUE) {
					$Pesan_Error	= 'Error Insert Letter Order Header...';
				}

				$Upd_Quot	= array(
					'flag_so'		=> 'Y',
					'noso'			=> $Nomor_Letter,
					'tglso'			=> $Date_Now,
					'project_no'	=> $Urut_Nomor
				);

				$Has_Upd_Quot			= $this->db->update('quotations', $Upd_Quot, array('id' => $Code_Quot));
				if ($Has_Upd_Quot !== TRUE) {
					$Pesan_Error	= 'Error Update Quotation...';
				}

				if ($this->db->trans_status() != TRUE || !empty($Pesan_Error)) {
					$this->db->trans_rollback();
					$rows_Return		= array(
						'status'		=> 2,
						'pesan'			=> 'Create Sales Order Process  Failed, ' . $Pesan_Error
					);
					history('Create Sales Order ' . $Nomor_Letter . ' - ' . $Pesan_Error);
				} else {
					$this->db->trans_commit();
					$rows_Return		= array(
						'status'		=> 1,
						'pesan'			=> 'Create Sales Order success. Thank you & have a nice day......'
					);
					history('Create Sales Order ' . $Nomor_Letter . ' - Success ');
				}
			}
		}

		echo json_encode($rows_Return);
	}

	function get_detail_comp_plant()
	{
		$response	= array(
			'alamat'	=> '',
			'nama'		=> '',
			'phone'		=> ''
		);

		if ($this->input->post()) {
			$Code_Cari	= $this->input->post('plant');
			$Code_Cust	= $this->input->post('nocust');
			$WHERE_Code	= array(
				'id'				=> $Code_Cari,
				'customer_id'		=> $Code_Cust
			);

			$rows_Find	= $this->db->get_where('plants', $WHERE_Code)->row();
			if ($rows_Find) {
				$response	= array(
					'alamat'	=> $rows_Find->address,
					'nama'		=> strtoupper($rows_Find->pic),
					'phone'		=> $rows_Find->phone
				);
			}
		}
		echo json_encode($response);
	}
}
