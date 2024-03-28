<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Invoice_process extends CI_Controller
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

		$this->folder			= 'Invoicing';
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
			'title'			=> 'MANAGE INVOICE',
			'action'		=> 'index',
			'akses_menu'	=> $Arr_Akses,
			'rows_customer'	=> $rows_Customer
		);
		history('View List Invoice');
		$this->load->view($this->folder . '/main_invoice', $data);
	}

	/*
	| -------------------------------- |
	|	 	DISPLAY LIST INVOICE       |
	| -------------------------------- |
	*/
	function get_data_display()
	{
		$Arr_Akses		= $this->Arr_Akses;

		$Cust_Find		= $this->input->post('nocust');
		$Month_Find		= $this->input->post('bulan');
		$Year_Find		= $this->input->post('tahun');

		$requestData	= $_REQUEST;
		$fetch			= $this->qry_list_invoice(
			$Cust_Find,
			$Month_Find,
			$Year_Find,
			$requestData['search']['value'],
			$requestData['order'][0]['column'],
			$requestData['order'][0]['dir'],
			$requestData['start'],
			$requestData['length']
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
			//echo"<pre>";print_r($row);
			$Kode_Inv		= $row['id'];
			$Tgl_Inv		= date('d-m-Y', strtotime($row['datet']));
			$Nomor_Inv		= $row['invoice_no'];
			$Customer		= $row['customer_name'];
			$Custid			= $row['customer_id'];
			$Alamat			= $row['address'];
			$sts_Inv		= $row['status'];
			$Total_Inv		= number_format($row['grand_tot']);

			## AMBIL DATA NOSO& NO PO ##
			$Arr_Quot		= $Arr_SO = array();
			$Qry_Letter		= "SELECT
									det_inv.letter_order_id,
									det_so.no_so,
									head_quot.pono
								FROM
									invoice_details det_inv
								INNER JOIN letter_orders det_so ON det_inv.letter_order_id = det_so.id
								INNER JOIN quotations head_quot ON det_inv.quotation_id = head_quot.id
								WHERE
									det_inv.invoice_id = '" . $Kode_Inv . "'
								GROUP BY
									det_inv.letter_order_id";
			//echo $Qry_Letter;exit;
			$Det_Quot		= $this->db->query($Qry_Letter)->result();
			//echo"<pre>";print_r($Det_Quot);
			if ($Det_Quot) {
				foreach ($Det_Quot as $key => $values) {
					$id_SO		= $values->letter_order_id;
					$no_SO		= $values->no_so;
					$no_PO		= $values->pono;
					$Arr_Quot[$no_PO]	= $no_PO;
					$Arr_SO[$id_SO]		= $no_SO;
				}
			}

			$nomor_SO		= implode(",", $Arr_SO);
			$nomor_PO		= implode(",", $Arr_Quot);



			$nestedData 	= array();

			$nestedData[]	= $Nomor_Inv;
			$nestedData[]	= $Tgl_Inv;
			$nestedData[]	= $Customer;
			$nestedData[]	= $nomor_SO;
			$nestedData[]	= $nomor_PO;
			$nestedData[]	= $Total_Inv;

			$Template			= "";

			if ($Arr_Akses['read'] == 1) {
				$Template		.= "<button type='button' class='btn btn-sm bg-navy-active' onClick='view_invoice(\"" . $Kode_Inv . "\");'> <i class='fa fa-search'></i> </button>";
			}
			if ($Arr_Akses['download'] == 1 && $sts_Inv == 'APV') {
				$Template		.= "&nbsp;<a href='" . site_url('Invoice_process/print_invoice/' . $Kode_Inv) . "' class='btn btn-sm btn-info' title='Print Invoice' target='_blank'> <i class='fa fa-print'></i> </a>";

				//$Template		.="&nbsp;<a href='".site_url('Invoice_process/custome_invoice/'.$Kode_Inv)."' class='btn btn-sm bg-maroon-active' title='Custom Invoice'> <i class='fa fa-recycle'></i> </a>";
			}

			if ($Arr_Akses['update'] == 1) {
				if ($sts_Inv == 'APV') {
					$Template		.= "&nbsp;<a href='" . site_url('Invoice_process/update_receive/' . $Kode_Inv) . "' class='btn btn-sm btn-primary' title='UPDATE RECEIVE INVOICE'> <i class='fa fa-send'></i> </a>";
				}

				if ($sts_Inv == 'CLS') {
					$Template		.= "&nbsp;<a href='" . site_url('Invoice_process/follow_up/' . $Kode_Inv) . "' class='btn btn-sm btn-success' title='FOLLOW UP INVOICE'> <i class='fa fa-refresh'></i> </a>";
				}
			}

			if ($Arr_Akses['update'] == 1 || $Arr_Akses['delet'] == 1) {

				$Template		.= "&nbsp;<a href='" . site_url('Invoice_process/batal_invoice/' . $Kode_Inv) . "' class='btn btn-sm btn-danger' title='CANCEL INVOICE'> <i class='fa fa-trash'></i> </a>";
			}

			$nestedData[]	= $Template;

			$data[] = $nestedData;
			$urut1++;
			$urut2++;
		}

		$json_data = array(
			"draw"            => intval($requestData['draw']),
			"recordsTotal"    => intval(count($data)),
			"recordsFiltered" => intval($totalFiltered),
			"data"            => $data
		);

		echo json_encode($json_data);
	}
	public function qry_list_invoice($Custid = '', $Bulan = '', $Tahun = '', $like_value = NULL, $column_order = NULL, $column_dir = NULL, $limit_start = NULL, $limit_length = NULL)
	{
		$WHERE		= "grand_tot > 0";

		if ($Custid) {
			if (!empty($WHERE)) $WHERE	.= " AND ";
			$WHERE	.= "customer_id = '" . $Custid . "'";
		}

		if ($Bulan) {
			if (!empty($WHERE)) $WHERE	.= " AND ";
			$WHERE	.= "MONTH(datet) = '" . $Bulan . "'";
		}

		if ($Tahun) {
			if (!empty($WHERE)) $WHERE	.= " AND ";
			$WHERE	.= "YEAR(datet) = '" . $Tahun . "'";
		}

		if ($like_value) {
			if (!empty($WHERE)) $WHERE	.= " AND ";
			$WHERE	.= "(
						invoice_no LIKE '%" . $this->db->escape_like_str($like_value) . "%'
						OR datet LIKE '%" . $this->db->escape_like_str($like_value) . "%'
						OR customer_name LIKE '%" . $this->db->escape_like_str($like_value) . "%'
						OR `status` LIKE '%" . $this->db->escape_like_str($like_value) . "%'
						)";
		}



		$sql = "
			SELECT 
				(@row:=@row+1) AS nomor_urut,
				id,
				invoice_no,
				datet,
				customer_id,
				customer_name,
				address,
				receive_date,
				send_date,
				`status`,
				grand_tot
				FROM
					invoices,
				(SELECT @row:=0) r ";
		if ($WHERE) {
			$sql .= " WHERE " . $WHERE;
		}

		//print_r($sql);exit();


		$columns_order_by = array(
			0 => 'id',
			1 => 'datet',
			2 => 'customer_name'

		);

		$jum_Data	= $this->db->query($sql)->num_rows();

		$sql .= " ORDER BY " . $columns_order_by[$column_order] . " " . $column_dir . " ";
		$sql .= " LIMIT " . $limit_start . " ," . $limit_length . " ";

		$data['query'] 					= $this->db->query($sql);
		$data['totalData']				= $jum_Data;
		$data['totalFiltered']			= $jum_Data;

		return $data;
	}


	/*
	| -------------------------------- |
	|	   LIST OUTSTANDING PARTIAL    |
	| -------------------------------- |
	*/

	function list_outstanding_partial_po()
	{
		$rows_Customer		= $this->GetOutstandingCustomer('Y');
		$data = array(
			'title'			=> 'OUTSTANDING INVOICE (PARTIAL)',
			'action'		=> 'list_outstanding_partial_po',
			'akses_menu'	=> $this->Arr_Akses,
			'rows_customer'	=> $rows_Customer
		);
		history('View Oustanding Invoice (Partial)');
		$this->load->view($this->folder . '/main_outs_invoice_partial', $data);
	}

	/*
	| -------------------------------- |
	|	 	DISPLAY OUTS PARTIAL       |
	| -------------------------------- |
	*/

	function outstanding_partial_po()
	{
		$Find_Cust		= $this->input->post('nocust');

		$rows_Akses		= $this->Arr_Akses;


		$requestData	= $_REQUEST;


		$Like_Value		= $requestData['search']['value'];
		$column_order	= $requestData['order'][0]['column'];
		$column_dir		= $requestData['order'][0]['dir'];
		$limit_start	= $requestData['start'];
		$limit_length	= $requestData['length'];

		$WHERE_Find		= "tot_proses > 0
							AND qty_total <= (tot_proses + tot_fail)";
		if ($Find_Cust) {
			if (!empty($WHERE_Find)) $WHERE_Find .= " AND ";
			$WHERE_Find .= "customer_id = '" . $Find_Cust . "'";
		}

		$Query_Sub		= $this->QueryProcess('Y');

		if ($Like_Value) {
			if (!empty($WHERE_Find)) $WHERE_Find	.= " AND ";
			$WHERE_Find	.= "(
						no_so LIKE '%" . $this->db->escape_like_str($Like_Value) . "%'
						OR DATE_FORMAT(tgl_so,'%d-%m-%Y') LIKE '%" . $this->db->escape_like_str($Like_Value) . "%'
						OR customer_name LIKE '%" . $this->db->escape_like_str($Like_Value) . "%'
						OR pono LIKE '%" . $this->db->escape_like_str($Like_Value) . "%'
						OR quotation_nomor LIKE '%" . $this->db->escape_like_str($Like_Value) . "%'
						OR marketing_name LIKE '%" . $this->db->escape_like_str($Like_Value) . "%'
						)";
		}

		$sql = "SELECT
					*,
					(@row:=@row+1) AS nomor
					
				FROM
					(
					" . $Query_Sub . "
				) detail_invoice,
				(SELECT @row := 0) r 
				WHERE " . $WHERE_Find . "
				";
		// print_r($sql);
		// exit();
		$fetch['totalData'] 		= $this->db->query($sql)->num_rows();
		$fetch['totalFiltered']	= $this->db->query($sql)->num_rows();

		$columns_order_by = array(
			1 => 'no_so',
			2 => 'tgl_so',
			3 => 'customer_name',
			4 => 'quotation_nomor',
			5 => 'pono',
			6 => 'marketing_name'
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
			$Tgl_Order		= date('d-m-Y', strtotime($row['tgl_so']));
			$Code_Quot		= $row['quotation_id'];
			$No_Quot		= $row['quotation_nomor'];
			$Tgl_Quot		= date('d-m-Y', strtotime($row['quotation_date']));
			$Code_Sales		= $row['marketing_id'];
			$Name_Sales		= $row['marketing_name'];
			$Code_Customer	= $row['customer_id'];
			$Name_Customer	= $row['customer_name'];
			$No_PO			= $row['pono'];

			$Template		= "<button type='button' class='btn btn-sm bg-navy-active' onClick='view_order(\"" . $Code_Order . "\");'> <i class='fa fa-search'></i> </button>";
			if ($rows_Akses['create'] == '1' || $rows_Akses['update'] == '1') {
				$Template	.= "&nbsp;&nbsp;<button type='button' class='btn btn-sm bg-orange-active' id='proses_inv_" . $Code_Order . "' title='FOLLOW UP' onClick='return CreateInvoice(\"" . $Code_Order . "\");'> <i class='fa fa-money'></i> </button>";
			}





			$nestedData 	= array();
			$nestedData[]	= '<input type="checkbox" id="det_pilih_' . $Code_Order . '" name="detPilih[]" value="' . $Code_Order . '">';
			$nestedData[]	= $No_Order;
			$nestedData[]	= $Tgl_Order;
			$nestedData[]	= $Name_Customer;
			$nestedData[]	= $No_Quot;
			$nestedData[]	= $No_PO;
			$nestedData[]	= $Name_Sales;
			$origin = new DateTime($Tgl_Order);
			$target = new DateTime('now');
			$interval = $origin->diff($target);
			$nestedData[]   =  '<td align="center"><span class="badge bg-red">' . $interval->format('%a days') . '</span></td>';
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

	/*
	| -------------------------------- |
	|	   LIST OUTSTANDING FULL PO    |
	| -------------------------------- |
	*/

	function list_outstanding_full_po()
	{
		$rows_Customer		= $this->GetOutstandingCustomer('N');
		$data = array(
			'title'			=> 'OUTSTANDING INVOICE (FULL PO)',
			'action'		=> 'list_outstanding_full_po',
			'akses_menu'	=> $this->Arr_Akses,
			'rows_customer'	=> $rows_Customer
		);
		history('View Oustanding Invoice (Full PO)');
		$this->load->view($this->folder . '/main_outs_invoice_full', $data);
	}

	/*
	| -------------------------------- |
	|	 	DISPLAY OUTS FULL PO       |
	| -------------------------------- |
	*/

	function outstanding_full_po()
	{
		$Find_Cust		= $this->input->post('nocust');

		$rows_Akses		= $this->Arr_Akses;


		$requestData	= $_REQUEST;


		$Like_Value		= $requestData['search']['value'];
		$column_order	= $requestData['order'][0]['column'];
		$column_dir		= $requestData['order'][0]['dir'];
		$limit_start	= $requestData['start'];
		$limit_length	= $requestData['length'];

		$WHERE_Find		= "total_inv <= 0
							AND qty_sukses > 0
							AND total_quot <= (total_batal + qty_sukses + qty_gagal)";
		if ($Find_Cust) {
			if (!empty($WHERE_Find)) $WHERE_Find .= " AND ";
			$WHERE_Find .= "customer_id = '" . $Find_Cust . "'";
		}

		$Query_Sub		= $this->QueryProcess('N');

		if ($Like_Value) {
			if (!empty($WHERE_Find)) $WHERE_Find	.= " AND ";
			$WHERE_Find	.= "(
						nomor LIKE '%" . $this->db->escape_like_str($Like_Value) . "%'
						OR DATE_FORMAT(datet,'%d-%m-%Y') LIKE '%" . $this->db->escape_like_str($Like_Value) . "%'
						OR customer_name LIKE '%" . $this->db->escape_like_str($Like_Value) . "%'
						OR pono LIKE '%" . $this->db->escape_like_str($Like_Value) . "%'
						OR member_name LIKE '%" . $this->db->escape_like_str($Like_Value) . "%'
						)";
		}

		$sql = "SELECT
					*,
					(@row:=@row+1) AS urut
					
				FROM
					(
					" . $Query_Sub . "
				) detail_invoice,
				(SELECT @row := 0) r 
				WHERE " . $WHERE_Find . "
				";
		//print_r($sql);exit();
		$fetch['totalData'] 		= $this->db->query($sql)->num_rows();
		$fetch['totalFiltered']	= $this->db->query($sql)->num_rows();

		$columns_order_by = array(
			1 => 'nomor',
			2 => 'datet',
			3 => 'customer_name',
			4 => 'pono',
			5 => 'member_name'
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



			$No_Order		= '-';
			$Tgl_Order		= '-';
			$Code_Quot		= $row['id'];
			$No_Quot		= $row['nomor'];
			$Tgl_Quot		= date('d-m-Y', strtotime($row['datet']));
			$Code_Sales		= $row['member_id'];
			$Name_Sales		= $row['member_name'];
			$Code_Customer	= $row['customer_id'];
			$Name_Customer	= $row['customer_name'];
			$No_PO			= $row['pono'];

			// $first = new DateTime($Tgl_Order);
			// $data_tgl = new DateTime('now');
			// $tglakhir = $first->diff($data_tgl);

			$Template		= "<button type='button' class='btn btn-sm bg-navy-active' onClick='view_order(\"" . $Code_Quot . "\");'> <i class='fa fa-search'></i> </button>";
			if ($rows_Akses['create'] == '1' || $rows_Akses['update'] == '1') {
				$Template	.= "&nbsp;&nbsp;<button type='button' class='btn btn-sm bg-orange-active' id='proses_inv_" . $Code_Quot . "' title='CREATE INVOICE' onClick='return CreateInvoice(\"" . $Code_Quot . "\");'> <i class='fa fa-money'></i> </button>";
			}

			## AMBIL NOMOR SO ##
			$Arr_SO  	= $Arr_Tgl = array();
			$Query_SO	= $this->SubQueryFullPO($Code_Quot);
			$rows_SO	= $this->db->query($Query_SO)->result();
			if ($rows_SO) {
				foreach ($rows_SO as $keySO => $valSO) {
					$Code_SO			= $valSO->no_so;
					$Tgl_SO				= $valSO->tgl_so;
					$Arr_SO[$Code_SO]	= $Code_SO;
					$Arr_Tgl[$Tgl_SO]	= $Tgl_SO;
				}
				unset($rows_SO);
				$No_Order		= implode(',', $Arr_SO);
				$Tgl_Order		= implode(',', $Arr_Tgl);
			}


			$nestedData 	= array();
			$nestedData[]	= '<input type="checkbox" id="det_pilih_' . $Code_Quot . '" name="detPilih[]" value="' . $Code_Quot . '">';
			$nestedData[]	= $No_Quot;
			$nestedData[]	= $Tgl_Quot;
			$nestedData[]	= $Name_Customer;
			$nestedData[]	= $No_PO;
			$nestedData[]	= $Name_Sales;
			$nestedData[]	= $No_Order;
			$nestedData[]	= $Tgl_Order;
			// $nestedData[]   =  '<td align="center"><span class="badge bg-red">' . $tglakhir->format('%a days') . '</span></td>';
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

	function QueryProcess($Flag_Partial = 'N')
	{
		if ($Flag_Partial == 'Y') {
			$Query_process	= "SELECT
									trans_details.letter_order_id AS id,
									trans_details.letter_order_id,
									trans_details.tgl_so,
									trans_details.no_so,
									trans_details.quotation_id,
									trans_details.quotation_nomor,
									trans_details.quotation_date,
									trans_details.pono,
									trans_details.customer_id,
									trans_details.customer_name,
									trans_details.marketing_name,
									trans_details.marketing_id,
									sum(
										(
											CASE
											WHEN (
												trans_details.`re_schedule` <> 'Y'
											) THEN
												trans_details.`qty` - `trans_details`.`re_qty`
											ELSE
												0
											END
										)
									) AS qty_total,
									sum(
										(
											CASE
											WHEN (trans_details.`insitu` = 'N') THEN
												trans_details.`qty_rec`
											WHEN (
												trans_details.`insitu` = 'Y'
												AND NOT (
													trans_details.bast_rec_id IS NULL
													OR trans_details.bast_rec_id = ''
													OR trans_details.bast_rec_id = '-'
												)
											) THEN
												(
													trans_details.`qty` - trans_details.qty_reschedule
												)
											ELSE
												0
											END
										)
									) AS tot_real,
									SUM(trans_details.qty_proses) AS tot_proses,
									SUM(trans_details.qty_fail) AS tot_fail
								FROM
									trans_details
								INNER JOIN letter_orders ON letter_orders.id = trans_details.letter_order_id
								INNER JOIN customers ON letter_orders.customer_id = customers.id
								WHERE
									letter_orders.flag_invoice <> 'Y'
								-- AND trans_details.qty_proses > 0
								AND customers.flag_billing <> 'FULL'
								GROUP BY
									trans_details.letter_order_id";
		} else {
			$Query_process	= "SELECT
								det_header.id,
								det_header.nomor,
								det_header.datet,
								det_header.customer_id,
								det_header.customer_name,
								det_header.pono,
								det_header.member_id,
								det_header.member_name,
								det_header.pic_name,
								det_header.exc_ppn,
								det_header.po_receive,
								det_header.podate,
								det_inv.tot_real AS qty_real,
								det_inv.tot_proses AS qty_sukses,
								det_inv.tot_fail AS qty_gagal,
								x_quot.tot_quot AS total_quot,

							IF (
								x_cancel.tot_batal > 0,
								x_cancel.tot_batal,
								0
							) AS total_batal,

							IF (
								x_invoice.tot_inv > 0,
								x_invoice.tot_inv,
								0
							) AS total_inv
							FROM
								(
									SELECT
										trans_details.quotation_id,
										trans_details.quotation_nomor,
										trans_details.quotation_date,
										trans_details.pono,
										trans_details.customer_id,
										trans_details.customer_name,
										trans_details.marketing_name,
										trans_details.marketing_id,
										sum(
											(
												CASE
												WHEN (
													trans_details.`re_schedule` <> 'Y'
												) THEN
													trans_details.`qty` - `trans_details`.`re_qty`
												ELSE
													0
												END
											)
										) AS qty_total,
										sum(
											(
												CASE
												WHEN (trans_details.`insitu` = 'N') THEN
													trans_details.`qty_rec`
												WHEN (
													trans_details.`insitu` = 'Y'
													AND NOT (
														trans_details.bast_rec_id IS NULL
														OR trans_details.bast_rec_id = ''
														OR trans_details.bast_rec_id = '-'
													)
												) THEN
													(
														trans_details.`qty` - trans_details.qty_reschedule
													)
												ELSE
													0
												END
											)
										) AS tot_real,
										SUM(trans_details.qty_proses) AS tot_proses,
										SUM(trans_details.qty_fail) AS tot_fail
									FROM
										trans_details
									INNER JOIN letter_orders ON letter_orders.id = trans_details.letter_order_id
									INNER JOIN customers ON letter_orders.customer_id = customers.id
									WHERE
										letter_orders.flag_invoice <> 'Y'
									-- AND trans_details.qty_proses > 0
									AND customers.flag_billing = 'FULL'
									GROUP BY
										trans_details.quotation_id
								) AS det_inv
							INNER JOIN quotations det_header ON det_header.id = det_inv.quotation_id
							LEFT JOIN (
								SELECT
									SUM(qty) AS tot_quot,
									quotation_id
								FROM
									quotation_details
								GROUP BY
									quotation_id
							) x_quot ON x_quot.quotation_id = det_inv.quotation_id
							LEFT JOIN (
								SELECT
									SUM(qty_cancel) AS tot_batal,
									quotation_id
								FROM
									quotation_detail_cancels
								GROUP BY
									quotation_id
							) x_cancel ON x_cancel.quotation_id = det_inv.quotation_id
							LEFT JOIN (
								SELECT
									SUM(qty) AS tot_inv,
									quotation_id
								FROM
									invoice_details
								WHERE
									tipe = 'T'
								AND total_harga > 0
								GROUP BY
									quotation_id
							) x_invoice ON x_invoice.quotation_id = det_inv.quotation_id";
		}
		// var_dump($Query_process);
		// die();
		// print_r($Query_process);
		return $Query_process;
	}

	function SubQueryFullPO($Code_Quot = '')
	{
		$WHERE  = "letter_orders.flag_invoice <> 'Y'
				AND trans_details.qty_proses > 0
				AND customers.flag_billing = 'FULL'";
		if ($Code_Quot) {
			if (!empty($WHERE)) $WHERE	.= " AND ";
			$WHERE	.= "trans_details.quotation_id = '" . $Code_Quot . "'";
		}
		$Query_process	= "SELECT
								trans_details.letter_order_id AS id,
								trans_details.letter_order_id,
								trans_details.tgl_so,
								trans_details.no_so,
								trans_details.quotation_id,
								trans_details.quotation_nomor,
								trans_details.quotation_date,
								trans_details.pono,
								trans_details.customer_id,
								trans_details.customer_name,
								trans_details.marketing_name,
								trans_details.marketing_id,
								sum(
									(
										CASE
										WHEN (
											trans_details.`re_schedule` <> 'Y'
										) THEN
											trans_details.`qty` - `trans_details`.`re_qty`
										ELSE
											0
										END
									)
								) AS qty_total,
								sum(
									(
										CASE
										WHEN (trans_details.`insitu` = 'N') THEN
											trans_details.`qty_rec`
										WHEN (
											trans_details.`insitu` = 'Y'
											AND NOT (
												trans_details.bast_rec_id IS NULL
												OR trans_details.bast_rec_id = ''
												OR trans_details.bast_rec_id = '-'
											)
										) THEN
											(
												trans_details.`qty` - trans_details.qty_reschedule
											)
										ELSE
											0
										END
									)
								) AS tot_real,
								SUM(trans_details.qty_proses) AS tot_proses,
								SUM(trans_details.qty_fail) AS tot_fail
							FROM
								trans_details
							INNER JOIN letter_orders ON letter_orders.id = trans_details.letter_order_id
							INNER JOIN customers ON letter_orders.customer_id = customers.id
							WHERE
								" . $WHERE . "
							GROUP BY
								trans_details.letter_order_id";

		return $Query_process;
	}

	function GetOutstandingCustomer($Flag_Partial = 'N')
	{
		$ArrCustomer	= array();
		$Query_Sub		= $this->QueryProcess($Flag_Partial);
		if ($Flag_Partial == 'Y') {

			$Query_Customer	= "SELECT
									customer_id,
									customer_name
								FROM
									(
										" . $Query_Sub . "
									) detail_invoice
								WHERE
									tot_proses > 0
								AND qty_total <= (tot_proses + tot_fail)
								GROUP BY
									customer_id
								ORDER BY
									customer_name ASC";
		} else {


			$Query_Customer	= "SELECT
									customer_id,
									customer_name
								FROM
									(
										" . $Query_Sub . "
									) detail_invoice
								WHERE
									total_inv <= 0
								AND qty_sukses > 0
								AND total_quot <= (total_batal + qty_sukses + qty_gagal)
								GROUP BY
									customer_id
								ORDER BY
									customer_name ASC";


			// var_dump($Query_Customer);
			// die();
		}

		$rows_Customer	= $this->db->query($Query_Customer)->result();
		if ($rows_Customer) {
			foreach ($rows_Customer as $keyCust => $valCust) {
				$Code_Cust		= $valCust->customer_id;
				$Name_Cust		= $valCust->customer_name;
				$ArrCustomer[$Code_Cust]	= $Name_Cust;
			}
			unset($rows_Customer);
		}

		return $ArrCustomer;
	}

	function detail_outs_invoice()
	{
		$rows_Detail	= $rows_Quotation = array();
		$Flag_SO		= 'Y';
		if ($this->input->post()) {
			$Code_Process	= $this->input->post('kode_process');
			$Flag_SO		= $this->input->post('flag_so');

			$WHERE_Find		= "qty_proses > 0";
			if ($Code_Process) {
				if (!empty($WHERE_Find)) $WHERE_Find	.= " AND ";
				if ($Flag_SO == 'Y') {
					$WHERE_Find	.= "letter_order_id = '" . $Code_Process . "'";
				} else {
					$WHERE_Find	.= "quotation_id = '" . $Code_Process . "'";
				}
			}

			$Query_Find	= "SELECT
								id,
								letter_order_id,
								tgl_so,
								no_so,
								quotation_id,
								quotation_detail_id,
								quotation_nomor,
								quotation_date,
								pono,
								customer_id,
								customer_name,
								marketing_name,
								marketing_id,
								tool_id,
								tool_name,
								supplier_id,
								supplier_name,
								labs,
								insitu,
								subcon,
								price,
								hpp,
								COALESCE (diskon, 0) AS discount,
								qty AS qty_so,
								(
									CASE
									WHEN (`insitu` = 'N') THEN
										`qty_rec`
									WHEN (
										`insitu` = 'Y'
										AND NOT (
											bast_rec_id IS NULL
											OR bast_rec_id = ''
											OR bast_rec_id = '-'
										)
									) THEN
										(`qty` - qty_reschedule)
									ELSE
										0
									END
								) AS qty_real,
								qty_proses,
								qty_fail
							FROM
								trans_details
							WHERE
								" . $WHERE_Find;
			// print_r($Query_Find);
			$rows_Detail	= $this->db->query($Query_Find)->result();
			if ($rows_Detail) {
				$rows_Quotation	= $this->db->get_where('quotations', array('id' => $rows_Detail[0]->quotation_id))->result();
			}
		}

		$data = array(
			'title'			=> 'VIEW DETAIL OUTSTANDING INVOICE',
			'action'		=> 'detail_outs_invoice',
			'rows_detail'	=> $rows_Detail,
			'rows_quot'		=> $rows_Quotation,
			'flag_so'		=> $Flag_SO
		);

		$this->load->view($this->folder . '/v_invoice_outs_preview', $data);
	}

	/*
	| ------------------------------------ |
	|			INVOICE PROCESS			   |
	| ------------------------------------ |
	*/
	function invoicing_process()
	{
		$Ok_Proses		= 0;
		$rows_Customer	= $rows_Detail = $rows_Quotation = array();
		$Flag_Order		= 'Y';
		$Link_Back		= 'list_outstanding_partial_po';
		if ($this->input->get()) {
			$Code_Selected	= urldecode($this->input->get('code_inv'));
			$Imp_Code		= str_replace("^", "','", $Code_Selected);
			$Flag_Order		= urldecode($this->input->get('flag_order'));
			if ($Flag_Order == 'Y') {
				$WHERE			= "head_tool.qty_proses > 0
							AND (
								head_so.flag_invoice IS NULL
								OR head_so.flag_invoice = 'N'
								OR head_so.flag_invoice = ''
								OR head_so.flag_invoice = '-'
							)
							AND head_tool.letter_order_id IN('" . $Imp_Code . "')";
			} else {
				$Link_Back		= 'list_outstanding_full_po';
				$WHERE			= "head_tool.qty_proses > 0
							AND (
								head_so.flag_invoice IS NULL
								OR head_so.flag_invoice = 'N'
								OR head_so.flag_invoice = ''
								OR head_so.flag_invoice = '-'
							)
							AND head_tool.quotation_id IN('" . $Imp_Code . "')";
			}


			$Query_Find		= "SELECT
									head_tool.id,
									head_tool.letter_order_id,
									head_tool.tgl_so,
									head_tool.no_so,
									head_tool.quotation_id,
									head_tool.quotation_detail_id,
									head_tool.quotation_nomor,
									head_tool.quotation_date,
									head_tool.pono,
									head_tool.customer_id,
									head_tool.customer_name,
									head_tool.marketing_name,
									head_tool.marketing_id,
									head_tool.tool_id,
									head_tool.tool_name,
									head_tool.range,
									head_tool.piece_id,
									head_tool.supplier_id,
									head_tool.supplier_name,
									head_tool.labs,
									head_tool.insitu,
									head_tool.subcon,
									head_tool.price,
									head_tool.hpp,
									COALESCE (head_tool.diskon, 0) AS discount,
									head_tool.qty AS qty_so,
									(
										CASE
										WHEN (head_tool.`insitu` = 'N') THEN
											head_tool.`qty_rec`
										WHEN (
											head_tool.`insitu` = 'Y'
											AND NOT (
												head_tool.bast_rec_id IS NULL
												OR head_tool.bast_rec_id = ''
												OR head_tool.bast_rec_id = '-'
											)
										) THEN
											(
												head_tool.`qty` - head_tool.qty_reschedule
											)
										ELSE
											0
										END
									) AS qty_real,
									head_tool.qty_proses,
									head_tool.qty_fail
								FROM
									trans_details head_tool
								INNER JOIN letter_orders head_so ON head_tool.letter_order_id = head_so.id
								WHERE
								" . $WHERE;
			$rows_Detail	= $this->db->query($Query_Find)->result();
			if ($rows_Detail) {
				$rows_Customer	= $this->db->get_where('customers', array('id' => $rows_Detail[0]->customer_id))->result();
				$rows_Quotation	= $this->db->get_where('quotations', array('id' => $rows_Detail[0]->quotation_id))->result();
				$Ok_Proses		= 1;
			}
		}

		if ($Ok_Proses == 1) {
			$rows_Cetak			= array('Y' => 'Yes', 'N' => 'No');
			$rows_Faktur		= $this->GetMasterFaktur();
			$sisa_Faktur		= $this->GetLeftFaktur();
			$data = array(
				'title'			=> 'INVOICE PROCESS',
				'action'		=> 'invoicing_process',
				'rows_detail'	=> $rows_Detail,
				'rows_quot'		=> $rows_Quotation,
				'rows_cust'		=> $rows_Customer,
				'rows_cetak'	=> $rows_Cetak,
				'rows_faktur'	=> $rows_Faktur,
				'sisa_faktur'	=> $sisa_Faktur,
				'flag_so'		=> $Flag_Order
			);

			$this->load->view($this->folder . '/v_invoice_outs_process', $data);
		} else {
			$this->session->set_flashdata("alert_data", "<div class=\"alert alert-warning\" id=\"flash-message\">No record was found....</div>");
			redirect(site_url('Invoice_process/' . $Link_Back));
		}
	}

	function GetMasterFaktur()
	{
		$rows_Faktur	= array();
		$Qry_Faktur		= "SELECT * FROM faktur_headers ORDER BY kefak ASC";
		$find_Faktur	= $this->db->query($Qry_Faktur)->result();
		if ($find_Faktur) {
			foreach ($find_Faktur as $keyFaktur => $valFaktur) {
				$Code_Faktur	= $valFaktur->kefak;
				$Ket_Faktur		= $valFaktur->keterangan;

				$rows_Faktur[$Code_Faktur]	= $Code_Faktur . ' ' . $Ket_Faktur;
			}
			unset($find_Faktur);
		}

		return $rows_Faktur;
	}

	function GetLeftFaktur()
	{
		$Sisa_Faktur	= 0;
		$Qry_Faktur		= "SELECT * FROM faktur_no_masters WHERE status = '1' ORDER BY tgl_entry DESC LIMIT 1";
		$find_Faktur	= $this->db->query($Qry_Faktur)->result();
		if ($find_Faktur) {
			$Qry_Faktur_detail	= "SELECT * FROM faktur_no_details WHERE idgen = '" . $find_Faktur[0]->idgen . "' AND (sts IS NULL OR sts ='0' OR sts ='' OR sts = '-')";
			$Sisa_Faktur		= $this->db->query($Qry_Faktur_detail)->num_rows();
		}

		return $Sisa_Faktur;
	}

	function GetMasterPPN()
	{
		$Invoice_Date	= date('Y-m-d');
		if ($this->input->post()) {
			$Invoice_Date	= date('Y-m-d', strtotime($this->input->post('invoice_date')));
		}
		$Prosen_PPN		= 10;
		$Query_Prosen	= "SELECT * FROM master_taxes WHERE valid_date <= '" . $Invoice_Date . "' ORDER BY valid_date DESC LIMIT 1";
		$rows_Prosen	= $this->db->query($Query_Prosen)->result();
		if ($rows_Prosen) {
			$Prosen_PPN	= $rows_Prosen[0]->ppn_value;
		}

		$Arr_PPN	= array(
			'ppn'		=> $Prosen_PPN
		);

		echo json_encode($Arr_PPN);
	}

	function save_generate_invoice_process()
	{
		$Arr_Return		= array();
		if ($this->input->post()) {
			//echo"<pre>";print_r($this->input->post());exit;
			$Created_By		= $this->session->userdata('siscal_userid');
			$Created_Date	= date('Y-m-d H:i:s');

			$Code_Faktur	= $this->input->post('kefak');
			$Invoice_Date	= date('Y-m-d', strtotime($this->input->post('invoice_date')));
			$Nocust			= $this->input->post('nocust');
			$Customer		= strtoupper($this->input->post('customer_name'));
			$PIC_Name		= $this->input->post('pic_name');
			$Address		= $this->input->post('alamat');
			$Print_Faktur	= $this->input->post('cetak_faktur');

			$detDetail		= $this->input->post('detDetail');
			$Total_DPP		= str_replace(',', '', $this->input->post('dpp'));
			$PPN			= str_replace(',', '', $this->input->post('ppn'));
			$Total_Inv		= str_replace(',', '', $this->input->post('grand_total'));
			$Inc_PPN		= $this->input->post('inc_ppn');
			$Prosen_PPN		= $this->input->post('prosen_ppn');
			$Flag_SO		= $this->input->post('flag_so');
			$Quot_Nomor		= $this->input->post('quotation_nomor');
			if ($Flag_SO == 'Y') {
				$Quot_Nomor	= '';
			}



			$Year_Inv		= date('Y', strtotime($Invoice_Date));
			$Year_Month		= date('Ym', strtotime($Invoice_Date));
			$Month_Inv		= date('n', strtotime($Invoice_Date));
			$Day_Inv		= date('d', strtotime($Invoice_Date));
			$bulan			= date('m', strtotime($Invoice_Date));
			$romawi			= getRomawi($bulan);

			$Year_Now		= date('Y');
			$Month_Now		= date('n');
			$Selisih_Bulan			= (($Year_Now - $Year_Inv) * 12) + ($Month_Now - $Month_Inv);

			## CEK FATUR PAJAK ##
			$Query_Faktur_Gen	= "SELECT idgen,kode,tahun FROM faktur_no_masters WHERE status ='1' ORDER BY tanggal DESC LIMIT 1";
			$Pros_Faktur_Gen	= $this->db->query($Query_Faktur_Gen);
			$Num_Faktur_Gen		= $Pros_Faktur_Gen->num_rows();

			if ($Year_Inv < $Year_Now) {
				$Query_Faktur_Gen	= "SELECT idgen,kode,tahun FROM faktur_no_masters WHERE tahun ='" . $Year_Inv . "' ORDER BY tanggal DESC LIMIT 1";
				$Pros_Faktur_Gen	= $this->db->query($Query_Faktur_Gen);
				$Num_Faktur_Gen		= $Pros_Faktur_Gen->num_rows();
			}

			if ($Num_Faktur_Gen > 0) {
				## CEK SISA FAKTUR ##
				$rows_Faktur_Gen	= $Pros_Faktur_Gen->result();
				$ID_Faktur_Gen		= $rows_Faktur_Gen[0]->idgen;
				$Code_Faktur_Gen	= $rows_Faktur_Gen[0]->kode;

				$Query_Faktur_Det	= "SELECT * FROM faktur_no_details WHERE idgen = '" . $ID_Faktur_Gen . "' AND kode = '" . $Code_Faktur_Gen . "' AND (sts IS NULL OR sts = '0' OR sts = '' OR sts ='-')";
				$Pros_Faktur_Det	= $this->db->query($Query_Faktur_Det);
				$Num_Faktur_Det		= $Pros_Faktur_Det->num_rows();
				if ($Num_Faktur_Det > 0) {
					$Pesan_Error	= '';
					$this->db->trans_begin();

					$Urut_Inv		= 1;
					$Urut_Code		= 1;

					$Query_Urut_Code	= "SELECT MAX(CAST(SUBSTRING_INDEX(id, '-', -1) AS UNSIGNED)) as urut FROM invoices WHERE datet LIKE '" . $Year_Inv . "-%' LIMIT 1";
					$rows_Urut_Code		= $this->db->query($Query_Urut_Code)->result();
					if ($rows_Urut_Code) {
						$Urut_Code		= intval($rows_Urut_Code[0]->urut) + 1;
					}

					$Code_Invoice		= 'INV-V3' . $Year_Month . '-' . sprintf('%05d', $Urut_Code);

					if ($Year_Inv < $Year_Now) {
						$Query_Urut_Inv		= "SELECT MAX(CAST(SUBSTRING_INDEX(invoice_no, '/', -1) AS UNSIGNED)) as urut FROM invoices WHERE datet LIKE '" . $Year_Inv . "-%' AND flag_proses = 'Y' LIMIT 1";
						$rows_Urut_Inv		= $this->db->query($Query_Urut_Inv)->result();
						if ($rows_Urut_Inv) {
							$Urut_Inv		= intval($rows_Urut_Inv[0]->urut) + 1;
						}
					} else {
						$Query_Urut_Inv		= "SELECT * FROM banks WHERE id = '1' AND flag_active = '1'";
						$rows_Urut_Inv		= $this->db->query($Query_Urut_Inv)->result();
						if ($rows_Urut_Inv) {
							$Urut_Inv		= intval($rows_Urut_Inv[0]->noinv);
						}

						## UPDATE URUT INVOICE ##
						$Upd_Urut_Inv		= "UPDATE banks SET noinv = noinv + 1 WHERE id = '1'";
						$Has_Upd_Urut_Inv	= $this->db->query($Upd_Urut_Inv);
						if ($Has_Upd_Urut_Inv !== TRUE) {
							$Pesan_Error	= 'Error Update Invoice Iteration...';
						}
					}

					$Nomor_Invoice 	= $Year_Inv . '/STM-V3/' . sprintf('%05d', $Urut_Inv);

					$NPWP			= '';
					$TOP			= 30;
					$Query_Customer	= "SELECT top, npwp FROM customers WHERE id = '" . $Nocust . "'";
					$rows_Customer 	= $this->db->query($Query_Customer)->result();
					if ($rows_Customer) {
						$TOP		= $rows_Customer[0]->top;
						$NPWP		= $rows_Customer[0]->npwp;
					}
					$DueDate		= date('Y-m-d', strtotime('+' . $TOP . ' day', strtotime($Invoice_Date)));

					## AMBIL KODE FAKTUR ##
					$Nomor_Faktur		= '';
					if ($Print_Faktur == 'Y') {
						$Query_Faktur_Det	= "SELECT * FROM faktur_no_details WHERE idgen = '" . $ID_Faktur_Gen . "' AND kode = '" . $Code_Faktur_Gen . "' AND (sts IS NULL OR sts = '0' OR sts = '' OR sts ='-') ORDER BY idfaktur ASC LIMIT 1";
						$rows_Faktur_Det	= $this->db->query($Query_Faktur_Det)->result();
						$Nomor_Faktur		= $Code_Faktur . '.' . $rows_Faktur_Det[0]->fakturid;

						$Tgl_Generate		= date('Y-m-d H:i:s', mktime(date('H'), date('i'), date('s'), $Month_Inv, $Day_Inv, $Year_Inv));

						$Upd_Faktur_Det		= "UPDATE faktur_no_details SET nofaktur = '" . $Nomor_Faktur . "', tglfaktur = '" . $Invoice_Date . "', noinvoice = '" . $Nomor_Invoice . "', tglinvoice = '" . $Invoice_Date . "', sts ='1', tanggal_generate = '" . $Tgl_Generate . "' WHERE idgen = '" . $ID_Faktur_Gen . "' AND kode = '" . $Code_Faktur_Gen . "' AND idfaktur = '" . $rows_Faktur_Det[0]->idfaktur . "'";

						$Has_Upd_Faktur_Det	= $this->db->query($Upd_Faktur_Det);
						if ($Has_Upd_Faktur_Det !== TRUE) {
							$Pesan_Error	= 'Error Update Tax Invoice Code...';
						}
					}

					$PPH_23			= floor($Total_DPP * 0.02);

					$Ins_Header		= array(
						'id'			=> $Code_Invoice,
						'invoice_no'	=> $Nomor_Invoice,
						'nomor'			=> $Quot_Nomor,
						'datet'			=> $Invoice_Date,
						'customer_id'	=> $Nocust,
						'customer_name'	=> $Customer,
						'address'		=> $Address,
						'dpp'			=> 0,
						'diskon'		=> 0,
						'total_dpp'		=> $Total_DPP,
						'ppn'			=> $PPN,
						'pph23'			=> $PPH_23,
						'grand_tot'		=> $Total_Inv,
						'npwp'			=> $NPWP,
						'tipe_faktur'	=> $Code_Faktur,
						'no_faktur'		=> $Nomor_Faktur,
						'status'		=> 'OPN',
						'stat_efaktur'	=> '0',
						'flag_proses'	=> 'Y',
						'due_date'		=> $DueDate,
						'created_by'	=> $Created_By,
						'created_date'	=> $Created_Date
					);


					$DPP 					= 0;
					$Discount				= 0;
					$Arr_Quot	= $Arr_Letter	= array();
					if ($detDetail) {
						$intL	= 0;
						$intI	= $intA	= $intQ = $intL	= 0;
						foreach ($detDetail as $keyDetail => $valDetail) {
							$intL++;
							$Urut_Detail 			= $Code_Invoice . '-' . sprintf('%03d', $intL);
							$dpp 					= floatval(str_replace(',', '', $valDetail['total_harga']));
							$total_akhir 			= floatval(str_replace(',', '', $valDetail['total']));
							$diskon 				= $dpp - $total_akhir;
							$DPP 					+= $dpp;
							$Discount				+= $diskon;


							$Quot_Id				= $valDetail['quotation_id'];
							$Letter_ID				= $valDetail['letter_order_id'];

							if ($valDetail['tipe'] == 'I') {
								$tool_name 			= 'Insitu ' . ucwords(strtolower($valDetail['tool_name']));
								$Upd_Quot_Delivery	= "UPDATE quotation_deliveries SET pros_invoice = pros_invoice + " . $valDetail['qty'] . " WHERE id = '" . $valDetail['detail_id'] . "'";
								$Has_Upd_Quot_Delv	= $this->db->query($Upd_Quot_Delivery);
								if ($Has_Upd_Quot_Delv !== TRUE) {
									$Pesan_Error	= 'Error Update Quotation Delivery...';
								}
							} else if ($valDetail['tipe'] == 'A') {
								$tool_name 							= 'Biaya ' . ucwords(strtolower($valDetail['tool_name']));

								$Upd_Quot_Accomm	= "UPDATE quotation_accommodations SET pros_invoice = 'Y' WHERE id = '" . $valDetail['detail_id'] . "'";
								$Has_Upd_Quot_Accomm = $this->db->query($Upd_Quot_Accomm);
								if ($Has_Upd_Quot_Accomm !== TRUE) {
									$Pesan_Error	= 'Error Update Quotation Accommodation...';
								}
							} else {
								$tool_name 		= 'Jasa Kalibrasi ' . $valDetail['tool_name'];
							}


							$Ins_Detail	= array(
								'id'				=> $Urut_Detail,
								'invoice_id'		=> $Code_Invoice,
								'tool_id'			=> $valDetail['tool_id'],
								'tool_name'			=> $tool_name,
								'range'				=> $valDetail['range'],
								'piece_id'			=> $valDetail['piece_id'],
								'qty'				=> $valDetail['qty'],
								'price'				=> $valDetail['price'],
								'hpp'				=> $valDetail['hpp'],
								'discount'			=> $valDetail['discount'],
								'total_discount'	=> $diskon,
								'total_harga'		=> $dpp,
								'detail_id'			=> $valDetail['detail_id'],
								'quotation_id'		=> $Quot_Id,
								'letter_order_id'	=> $Letter_ID,
								'tipe'				=> $valDetail['tipe']
							);

							$Has_Ins_Inv_Detail	= $this->db->insert('invoice_details', $Ins_Detail);
							if ($Has_Ins_Inv_Detail !== TRUE) {
								$Pesan_Error	= 'Error Insert Invoice Detail...';
							}

							$OK_Q		= $OK_L	= 0;
							if (!isset($Arr_Quot) && empty($Arr_Quot)) {
								$OK_Q		= 1;
							} else {
								if (!in_array($Quot_Id, $Arr_Quot)) {
									$OK_Q	= 1;
								}
							}

							if ($OK_Q == 1) {
								$intQ++;
								$Arr_Quot[$intQ]		= $Quot_Id;
							}

							// Letter Order
							if (!isset($Arr_Letter) && empty($Arr_Letter)) {
								$OK_L		= 1;
							} else {
								if (!in_array($Letter_ID, $Arr_Letter)) {
									$OK_L	= 1;
								}
							}

							if ($OK_L == 1) {
								$intL++;
								$Arr_Letter[$intL]		= $Letter_ID;
							}
						}
					}

					$Ins_Header['dpp']		= $DPP;
					$Ins_Header['diskon']	= $Discount;

					$Has_Ins_Inv_Head	= $this->db->insert('invoices', $Ins_Header);
					if ($Has_Ins_Inv_Head !== TRUE) {
						$Pesan_Error	= 'Error Insert Invoice Header...';
					}

					## INVOICE LETTER ##
					$Letter_Code = 1;

					$Query_Urut_Letter	= "SELECT MAX(CAST(SUBSTRING_INDEX(id, '-', -1) AS UNSIGNED)) as urut FROM invoice_letters WHERE datet LIKE '" . $Year_Inv . "-%' LIMIT 1";
					$rows_Urut_Letter		= $this->db->query($Query_Urut_Letter)->result();
					if ($rows_Urut_Letter) {
						$Letter_Code		= intval($rows_Urut_Letter[0]->urut) + 1;
					}

					$Code_Letter			= 'SJV3-I' . $Year_Month . '-' . sprintf('%05d', $Letter_Code);
					$Nomor_Letter			= sprintf('%05d', $Letter_Code) . '/SJ/V3-INV/' . date('m/Y', strtotime($Invoice_Date));

					$Ins_Letter				= array(
						'id'				=> $Code_Letter,
						'nomor'				=> $Nomor_Letter,
						'datet'				=> $Invoice_Date,
						'invoice_id'		=> $Code_Invoice,
						'customer_id'		=> $Nocust,
						'customer_name'		=> $Customer,
						'created_date' 		=> $Created_Date,
						'created_by' 		=> $Created_By
					);
					$Has_Ins_Letter	= $this->db->insert('invoice_letters', $Ins_Letter);
					if ($Has_Ins_Letter !== TRUE) {
						$Pesan_Error	= 'Error Insert Invoice Letter...';
					}

					## PIUTANG ##

					$Arr_Piutang	= array(
						'invoice_no'		=> $Nomor_Invoice,
						'customer_id'		=> $Nocust,
						'customer_name'		=> $Customer,
						'bulan'				=> $Month_Inv,
						'tahun'				=> $Year_Inv,
						'saldo_awal'		=> 0,
						'debet'				=> $Total_Inv,
						'kredit'			=> 0,
						'saldo_akhir'		=> $Total_Inv
					);

					$Has_Ins_AR	= $this->db->insert('account_receivables', $Arr_Piutang);
					if ($Has_Ins_AR !== TRUE) {
						$Pesan_Error	= 'Error Insert Invoice AR...';
					}

					if ($Selisih_Bulan > 0) {
						for ($x = 1; $x <= $Selisih_Bulan; $x++) {
							$Tanggal_Proses	= date('Y-m-d', mktime(0, 0, 0, $Month_Inv + $x, 1, $Year_Inv));
							$Bulan_Proses	= date('n', strtotime($Tanggal_Proses));
							$Tahun_Proses	= date('Y', strtotime($Tanggal_Proses));

							$Arr_Piutang	= array(
								'invoice_no'		=> $Nomor_Invoice,
								'customer_id'		=> $Nocust,
								'customer_name'		=> $Customer,
								'bulan'				=> $Bulan_Proses,
								'tahun'				=> $Tahun_Proses,
								'saldo_awal'		=> $Total_Inv,
								'debet'				=> 0,
								'kredit'			=> 0,
								'saldo_akhir'		=> $Total_Inv
							);

							$Has_Ins_AR	= $this->db->insert('account_receivables', $Arr_Piutang);
							if ($Has_Ins_AR !== TRUE) {
								$Pesan_Error	= 'Error Insert Invoice AR...';
							}
						}
					}



					if ($Arr_Quot) {
						$Imp_Quot		= implode("','", $Arr_Quot);
						$Update_Quot	= "UPDATE quotations SET stat_gen = 'CLOSE' WHERE id IN('" . $Imp_Quot . "')";
						$Has_Upd_Quot	= $this->db->query($Update_Quot);
						if ($Has_Upd_Quot !== TRUE) {
							$Pesan_Error	= 'Error Update Quotation...';
						}
					}

					if ($Arr_Letter) {
						$Imp_Letter		= implode("','", $Arr_Letter);
						$Update_SO		= "UPDATE letter_orders SET flag_invoice = 'Y' WHERE id IN('" . $Imp_Letter . "')";
						$Has_Upd_SO		= $this->db->query($Update_SO);
						if ($Has_Upd_SO !== TRUE) {
							$Pesan_Error	= 'Error Update Service Order...';
						}
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
							'code'			=> $Code_Invoice
						);
						history('Add Invoice ' . $Nomor_Invoice);
					}
				} else {
					$Arr_Return		= array(
						'status'		=> 2,
						'pesan'			=> 'Empty Tax Invoice No. Please Upload New Tax Invoice No...'
					);
				}
			} else {
				$Arr_Return		= array(
					'status'		=> 2,
					'pesan'			=> 'Inactive Tax Invoice Master. Please Set Active Tax Invoice Master...'
				);
			}
		} else {
			$Arr_Return		= array(
				'status'		=> 2,
				'pesan'			=> 'No Record Was Found........'
			);
		}
		echo json_encode($Arr_Return);
	}

	function whatsapp_approval($Code_Invoice = null)
	{

		$Arr_Balik		= array(
			'status'		=> 2,
			'pesan'			=> 'No Records Was Found...'
		);
		if ($this->input->post()) {

			//echo"<pre>";print_r($this->params);exit;
			$Code_Invoice	= $this->input->post('code_inv');

			$Data_Invoice	= $this->db->get_where('invoices', array('id' => $Code_Invoice))->result_array();


			if (!$Data_Invoice) {
				$Arr_Balik		= array(
					'status'		=> 2,
					'pesan'			=> 'Invoice Not found......'
				);
			} else {

				$Receiver_Phone		= '6281398535011';
				//$Receiver_Phone		= '6285890969320';
				$Receiver_Inisial	= 'Ibu';
				$Receiver_Name		= 'Tati Rina';
				$Link_Approval		= 'https://sentral.dutastudy.com/Siscal_Dashboard';

				$Pesan_Whatsapp		= "# *Sentral Kalibrasi Sistem* # \n\nDear *" . $Receiver_Inisial . " " . $Receiver_Name . "*\n\nMohon Approval atas Invoice :\n";
				$Pesan_Whatsapp 	.= "*No. Invoice :* " . $Data_Invoice[0]['invoice_no'] . "\n";
				$Pesan_Whatsapp 	.= "*Tgl. Invoice :* " . date('d-m-Y', strtotime($Data_Invoice[0]['datet'])) . "\n";
				$Pesan_Whatsapp 	.= "*Customer :* " . strtoupper($Data_Invoice[0]['customer_name']) . "\n";
				$Pesan_Whatsapp 	.= "*Total :* " . number_format($Data_Invoice[0]['grand_tot']) . "\n\n\n";
				$Pesan_Whatsapp 	.= "Approval dapat dilakukan melalui aplikasi :\n*" . $Link_Approval . "*";
				$Pesan_Whatsapp	 	.= '\n\nTerima kasih.\n _This WA message automatically generated from System ' . base_url() . '_';

				$Arr_Balik			= Kirim_Whatsapp($Receiver_Phone, $Pesan_Whatsapp);
			}
		}
		echo json_encode($Arr_Balik);
	}


	function detail_invoice()
	{
		$rows_Header		= $rows_Detail	= array();
		if ($this->input->post()) {
			$Kode_Inv			= $this->input->post('kode_invoice');
			$rows_Header		= $this->db->get_where('invoices', array('id' => $Kode_Inv))->result();
			$rows_Detail		= $this->db->get_where('invoice_details', array('invoice_id' => $Kode_Inv))->result();
		}
		$data = array(
			'title'			=> 'INVOICE DETAIL',
			'rows_header'	=> $rows_Header,
			'rows_detail'	=> $rows_Detail,
			'action'		=> 'detail_invoice'
		);
		$this->load->view($this->folder . '/v_invoice_preview', $data);
	}



	function print_invoice($Kode_Inv = '')
	{
		$rows_header		= $this->master_model->getArray('invoices', array('id' => $Kode_Inv));
		$rows_detail		= $this->master_model->getArray('invoice_details', array('invoice_id' => $Kode_Inv));
		$rows_cust			= $this->master_model->getArray('customers', array('id' => $rows_header[0]['customer_id']));
		$rows_letter		= $this->master_model->getArray('letter_orders', array('id' => $rows_detail[0]['letter_order_id']));
		$rows_bank			= $this->master_model->getArray('banks', array('flag_active' => 1));

		$Query_Approve		= "SELECT head_member.* FROM members head_member INNER JOIN users head_user ON head_user.member_id = head_member.id WHERE head_user.id = '" . $rows_header[0]['approve_by'] . "'";
		//echo $Query_Approve;exit;
		$rows_Approve		= $this->db->query($Query_Approve)->result_array();
		$rows_Require		= $this->db->get_where('customer_inv_requirements', array('customer_id' => $rows_header[0]['customer_id']))->result_array();
		$rows_InvLetter		= $this->db->get_where('invoice_letters', array('invoice_id' => $Kode_Inv))->result_array();
		$data 			= array(
			'title'			=> 'PRINT INVOICE',
			'action'		=> 'print_invoice',
			'rows_header'	=> $rows_header[0],
			'rows_detail'	=> $rows_detail,
			'rows_cust'		=> $rows_cust[0],
			'printby'		=> $this->session->userdata('siscal_username'),
			'today' 		=> date("Y-m-d H:i:s"),
			'rows_approve'	=> $rows_Approve,
			'rows_bank'		=> $rows_bank,
			'rows_require'	=> $rows_Require,
			'rows_invletter' => $rows_InvLetter

		);


		$this->load->view($this->folder . '/v_invoice_print', $data);
	}

	function update_receive($Kode_Inv = '')
	{
		if ($this->input->post()) {
			//echo"<pre>";print_r($this->input->post());exit;
			$modified_By		= $this->session->userdata('siscal_userid');
			$modified_Date		= date('Y-m-d H:i:s');




			$Code_Invoice	= $this->input->post('code_invoice');
			$Send_Date		= $this->input->post('send_date');
			$Receive_By		= strtoupper($this->input->post('receive_by'));
			$Receive_Date	= $this->input->post('receive_date');

			$rows_Check		= $this->master_model->getArray('invoices', array('id' => $Code_Invoice));
			//echo"<pre>";print_r($rows_Check);exit;
			if ($rows_Check[0]['status'] !== 'APV' || $rows_Check[0]['grand_tot'] <= 0) {
				$Arr_Return		= array(
					'status'		=> 2,
					'pesan'			=> 'Data has been processed...'
				);
			} else {
				$Upd_header	= array(
					'send_date'		=> $Send_Date,
					'receive_by'	=> $Receive_By,
					'receive_date'	=> $Receive_Date,
					'status'		=> 'CLS',
					'modified_by'	=> $modified_By,
					'modified_date'	=> $modified_Date
				);

				$this->db->trans_begin();
				$this->db->update('invoices', $Upd_header, array('id' => $Code_Invoice));

				if ($this->db->trans_status() != TRUE) {
					$this->db->trans_rollback();
					$Arr_Return		= array(
						'status'		=> 2,
						'pesan'			=> 'Save Process  Failed, please try again...'
					);
				} else {
					$this->db->trans_commit();
					$Arr_Return		= array(
						'status'		=> 1,
						'pesan'			=> 'Save process success. Thank you & have a nice day......'
					);
					history('Update Invoice ' . $Code_Invoice);
				}
			}
			echo json_encode($Arr_Return);
		} else {

			$Arr_Akses			= $this->Arr_Akses;
			if ($Arr_Akses['update'] != '1') {
				$this->session->set_flashdata("alert_data", "<div class=\"alert alert-warning\" id=\"flash-message\">You Don't Have Right To Access This Page, Please Contact Your Administrator....</div>");
				redirect(site_url('Invoice_process'));
			}

			$rows_Header		= $this->db->get_where('invoices', array('id' => $Kode_Inv))->result();
			$rows_Detail		= $this->db->get_where('invoice_details', array('invoice_id' => $Kode_Inv))->result();


			if (empty($rows_Header)) {
				$this->session->set_flashdata("alert_data", "<div class=\"alert alert-warning\" id=\"flash-message\">Data has been updated....</div>");
				redirect(site_url('Invoice_process'));
			}

			$data = array(
				'title'			=> 'SEND & RECEIVE INVOICE',
				'rows_header'	=> $rows_Header,
				'rows_detail'	=> $rows_Detail,
				'action'		=> 'update_receive'
			);
			$this->load->view($this->folder . '/v_invoice_receive', $data);
		}
	}

	function follow_up($Kode_Inv = '')
	{
		$Arr_Akses			= $this->Arr_Akses;
		if ($Arr_Akses['update'] != '1') {
			$this->session->set_flashdata("alert_data", "<div class=\"alert alert-warning\" id=\"flash-message\">You Don't Have Right To Access This Page, Please Contact Your Administrator....</div>");
			redirect(site_url('Invoice_process'));
		}

		$rows_Header		= $this->db->get_where('invoices', array('id' => $Kode_Inv))->result();
		if (empty($rows_Header)) {
			$this->session->set_flashdata("alert_data", "<div class=\"alert alert-warning\" id=\"flash-message\">Data has been updated....</div>");
			redirect(site_url('Invoice_process'));
		}
		$Query_FollowUp		= "SELECT * FROM follow_up_invoices WHERE invoice_id = '" . $Kode_Inv . "' ORDER BY follow_up_date DESC";
		$rows_Detail		= $this->db->query($Query_FollowUp)->result();
		$data = array(
			'title'			=> 'FOLLOW UP PAYMENT INVOICE',
			'rows_header'	=> $rows_Header,
			'rows_detail'	=> $rows_Detail,
			'action'		=> 'follow_up'
		);
		$this->load->view($this->folder . '/v_invoice_followup', $data);
	}

	function add_invoice_follow_up()
	{
		$Arr_Return		= array(
			'status'		=> 2,
			'pesan'			=> 'No record was found to process...'
		);
		if ($this->input->post()) {

			$follow_By		= $this->session->userdata('siscal_username');
			$follow_Date	= date('Y-m-d H:i:s');

			$Code_Invoice	= $this->input->post('id_invoice');
			$Plan_Payment	= $this->input->post('plan_paid');
			$PIC_Payment	= strtoupper($this->input->post('contact_person'));
			$Flag_Trouble  	= 'N';
			$Status_Follow	= "OK";
			$Keterangan		= $this->input->post('keterangan');
			if ($this->input->post('trouble')) {
				$Flag_Trouble  	= 'Y';
				$Status_Follow	= "TROUBLE";
			}

			$rows_Check		= $this->master_model->getArray('invoices', array('id' => $Code_Invoice));
			//echo"<pre>";print_r($rows_Check);exit;
			if ($rows_Check[0]['status'] !== 'CLS' || $rows_Check[0]['grand_tot'] <= 0) {
				$Arr_Return		= array(
					'status'		=> 2,
					'pesan'			=> 'Data has been processed...'
				);
			} else {
				$Pesan_Error	= '';
				$this->db->trans_begin();
				$Upd_header	= array(
					'follow_status'		=> $Status_Follow
				);
				$Tgl_Bayar		= NULL;
				$Ins_Log		= array();
				if ($Plan_Payment) {
					$Upd_header['plan_payment']	= $Plan_Payment;
					$Tgl_Bayar					= $Plan_Payment;

					$Total_Saldo	= 0;
					$Code_Old		= '';

					## QUERY AR ##

					$Query_AR		= "SELECT * FROM account_receivables WHERE invoice_no = '" . $rows_Check[0]['invoice_no'] . "' AND bulan = '" . date('n') . "' AND tahun = '" . date('Y') . "'";

					$rows_Piutang	= $this->db->query($Query_AR)->result();
					if ($rows_Piutang) {
						$Total_Saldo	= $rows_Piutang[0]->saldo_awal;
					}

					$Tahun_Plan		= date('Y', strtotime($Plan_Payment));
					$Query_Old		= "SELECT * FROM invoice_plan_payments WHERE no_invoice = '" . $rows_Check[0]['invoice_no'] . "' AND periode = '" . $Tahun_Plan . "' ORDER BY id DESC LIMIT 1";
					$rows_Old		= $this->db->query($Query_Old)->result();
					if ($rows_Old) {
						$Code_Old		= $rows_Old[0]->id;
					}

					$Ins_Log		= array(
						'no_invoice'		=> $rows_Check[0]['invoice_no'],
						'periode'			=> $Tahun_Plan,
						'invoice_date'		=> $rows_Check[0]['datet'],
						'customer_id'		=> $rows_Check[0]['customer_id'],
						'customer_name'		=> $rows_Check[0]['customer_name'],
						'plan_payment'		=> $Plan_Payment,
						'total_inv'			=> $Total_Saldo
					);

					if ($Code_Old) {
						$Has_Ins_Plan	= $this->db->update('invoice_plan_payments', $Ins_Log, array('id' => $Code_Old));
					} else {
						$Has_Ins_Plan	= $this->db->insert('invoice_plan_payments', $Ins_Log);
					}

					if ($Has_Ins_Plan !== true) {
						$Pesan_Error	= 'Error Insert/Update Invoice Plan Payment';
					}
				}

				$Has_Upd_Invoice	= $this->db->update('invoices', $Upd_header, array('id' => $Code_Invoice));
				if ($Has_Upd_Invoice !== true) {
					$Pesan_Error	= 'Error Update Invoice';
				}

				$Ins_Follow		= array(
					'invoice_id'	=> $Code_Invoice,
					'plan_paid'		=> $Tgl_Bayar,
					'status'		=> $Status_Follow,
					'descr'			=> $Keterangan,
					'contact_person' => $PIC_Payment,
					'follow_up_by'	=> $follow_By,
					'follow_up_date' => $follow_Date
				);

				$Has_Ins_Follow	= $this->db->insert('follow_up_invoices', $Ins_Follow);
				if ($Has_Ins_Follow !== true) {
					$Pesan_Error	= 'Error Insert Follow Up Invoice';
				}



				if ($this->db->trans_status() !== TRUE || !empty($Pesan_Error)) {
					$this->db->trans_rollback();
					$Arr_Return		= array(
						'status'		=> 2,
						'pesan'			=> $Pesan_Error
					);
				} else {
					$this->db->trans_commit();
					$Arr_Return		= array(
						'status'		=> 1,
						'pesan'			=> 'Save process success. Thank you & have a nice day......'
					);
					history('Follow Up Invoice ' . $Code_Invoice);
				}
			}
		}
		echo json_encode($Arr_Return);
	}

	function delete_invoice_follow_up()
	{
		$Arr_Return		= array(
			'status'		=> 2,
			'pesan'			=> 'No record was found to process...'
		);
		if ($this->input->post()) {
			//echo"<pre>";print_r($this->input->post());exit;
			$Delete_By		= $this->session->userdata('siscal_userid');
			$Delete_Date	= date('Y-m-d H:i:s');



			$Code_Invoice	= $this->input->post('invoice');
			$Code_Follow	= $this->input->post('kode_follow');


			$rows_Check		= $this->master_model->getArray('invoices', array('id' => $Code_Invoice));
			//echo"<pre>";print_r($rows_Check);exit;
			if ($rows_Check[0]['status'] !== 'CLS' || $rows_Check[0]['grand_tot'] <= 0) {
				$Arr_Return		= array(
					'status'		=> 2,
					'pesan'			=> 'Data has been processed...'
				);
			} else {
				$Pesan_Error	= '';
				$this->db->trans_begin();

				## AMBIL DATA FOLLOW UP LAMA ##
				$Status_Follow 	= 'OK';
				$Query_FollowUp	= "SELECT * FROM follow_up_invoices WHERE invoice_id = '" . $Code_Invoice . "' AND id != '" . $Code_Follow . "' ORDER BY follow_up_date DESC LIMIT 1";
				$rows_FollowUp	= $this->db->query($Query_FollowUp)->result();
				if ($rows_FollowUp) {
					$Status_Follow 	= $rows_FollowUp[0]->status;
				}

				$Upd_header	= array(
					'follow_status'		=> $Status_Follow,
					'modified_By'		=> $Delete_By,
					'modified_date'		=> $Delete_Date
				);


				$Has_Upd_Invoice	= $this->db->update('invoices', $Upd_header, array('id' => $Code_Invoice));
				if ($Has_Upd_Invoice !== true) {
					$Pesan_Error	= 'Error Update Invoice';
				}

				$Query_Delete		= "DELETE FROM follow_up_invoices WHERE invoice_id = '" . $Code_Invoice . "' AND id = '" . $Code_Follow . "'";
				$Has_Delete_Follow	= $this->db->query($Query_Delete);
				if ($Has_Delete_Follow !== true) {
					$Pesan_Error	= 'Error Delete Invoice Follow Up';
				}


				if ($this->db->trans_status() !== TRUE || !empty($Pesan_Error)) {
					$this->db->trans_rollback();
					$Arr_Return		= array(
						'status'		=> 2,
						'pesan'			=> $Pesan_Error
					);
				} else {
					$this->db->trans_commit();
					$Arr_Return		= array(
						'status'		=> 1,
						'pesan'			=> 'Delete process success. Thank you & have a nice day......'
					);
					history('Delete Follow Up Invoice ' . $Code_Invoice . ' - ' . $Code_Follow);
				}
			}
		}
		echo json_encode($Arr_Return);
	}

	function batal_invoice($Kode_Inv = '')
	{
		if ($this->input->post()) {
			//echo"<pre>";print_r($this->input->post());exit;
			$cancel_By		= $this->session->userdata('siscal_userid');
			$cancel_Date	= date('Y-m-d H:i:s');

			$Code_Invoice	= $this->input->post('code_invoice');
			$Nomor_Invoice	= $this->input->post('nomor_invoice');
			$Status_Faktur	= $this->input->post('sts_tax');
			$Reason			= $this->input->post('cancel_reason');

			$rows_Check		= $this->master_model->getArray('invoices', array('id' => $Code_Invoice));

			$OK_Cek			= 1;
			$Error_Cek		= '';


			if ($rows_Check[0]['status'] == 'BTL' || $rows_Check[0]['status'] == 'REJ' || $rows_Check[0]['grand_tot'] <= 0) {
				$OK_Cek		= 0;
				$Error_Cek	= 'Data has been modified by other process..';
			}

			$Bulan_Now		= date('n');
			$Tahun_Now		= date('Y');

			## CEK JIKA SUDAH ADA PEMBAYARAN JURNAL ##
			$Qry_Piutang		= "SELECT * FROM account_receivables WHERE invoice_no = '" . $Nomor_Invoice . "' AND bulan = '" . $Bulan_Now . "' AND tahun = '" . $Tahun_Now . "'";
			$Pros_Piutang		= $this->db->query($Qry_Piutang);
			$Num_Piutang		= $Pros_Piutang->num_rows();
			if ($Num_Piutang <= 0) {
				$OK_Cek		= 0;
				$Error_Cek	= 'Journal has been created. Please cancel first..';
			} else {
				$rows_Jurnal	= $Pros_Piutang->result();
				$Saldo_Akhir	= $rows_Jurnal[0]->saldo_akhir;
				if ($Saldo_Akhir !== $rows_Check[0]['grand_tot']) {
					$OK_Cek		= 0;
					$Error_Cek	= 'Journal has been created. Please cancel first..';
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

				## DELETE AR ##
				$Query_AR	= "DELETE FROM account_receivables WHERE invoice_no = '" . $Nomor_Invoice . "'";
				$Has_DelAR	= $this->db->query($Query_AR);
				if ($Has_DelAR !== true) {
					$Pesan_Error	= 'Error Delete Account Receivable...';
				}

				$Upd_header	= array(
					'cancel_by'		=> $cancel_By,
					'cancel_date'	=> $cancel_Date,
					'reason'		=> $Reason,
					'status'		=> 'BTL',
					'dpp'			=> 0,
					'diskon'		=> 0,
					'total_dpp'		=> 0,
					'ppn'			=> 0,
					'pph23'			=> 0,
					'grand_tot'		=> 0,
					'flag_proses'	=> 'N'
				);

				if ($Status_Faktur == 'O') {
					$Upd_header['no_faktur']	= '';
				}

				## UPDATE INVOICE HEADER ##
				$Upd_InvHeader		= $this->db->update('invoices', $Upd_header, array('id' => $Code_Invoice));
				if ($Upd_InvHeader !== true) {
					$Pesan_Error	= 'Error Update Invoice....';
				}

				$rows_Detail		= $this->db->get_where('invoice_details', array('invoice_id' => $Code_Invoice))->result();
				$ArrLetter			= $ArrAkom = $ArrQuot = array();
				if ($rows_Detail) {

					foreach ($rows_Detail as $keyDetail => $valDetail) {
						$TipeDetail					= $valDetail->tipe;
						$LetterDetail				= $valDetail->letter_order_id;
						$QuotDetail					= $valDetail->quotation_id;
						$CodeQuotDetail 			= $valDetail->detail_id;
						$QtyDetail					= $valDetail->qty;
						$ArrLetter[$LetterDetail]	= $LetterDetail;
						$ArrQuot[$QuotDetail]		= $QuotDetail;
						if ($TipeDetail == 'A') {
							$ArrAkom[$CodeQuotDetail]	= $CodeQuotDetail;
						} else if ($TipeDetail == 'I') {
							$Qry_UpdDel		= "UPDATE quotation_deliveries SET pros_invoice = pros_invoice - " . $QtyDetail . " WHERE id = '" . $CodeQuotDetail . "'";
							$Has_UpdDelv	= $this->db->query($Qry_UpdDel);
							if ($Has_UpdDelv !== true) {
								$Pesan_Error	= 'Error Update Quotation Delivery...';
							}
						}
					}
				}

				## UPDATE INVOICE DETAIL ##
				$Upd_Detail			= "UPDATE invoice_details SET price = 0, hpp = 0, discount = 0, total_discount = 0, total_harga = 0 WHERE invoice_id = '" . $Code_Invoice . "'";
				$Has_UpdInvDetail	= $this->db->query($Upd_Detail);
				if ($Has_UpdInvDetail !== true) {
					$Pesan_Error	= 'Error Update Invoice Detail...';
				}

				if ($Status_Faktur == 'O') {
					$Upd_FakturDet		= "UPDATE faktur_no_details SET nofaktur = NULL, tglfaktur = NULL, noinvoice = NULL, tglinvoice = NULL, sts ='0', tanggal_generate = NULL WHERE noinvoice = '" . $Nomor_Invoice . "'";

					$Has_UpdFaktur		= $this->db->query($Upd_FakturDet);
					if ($Has_UpdFaktur !== TRUE) {
						$Pesan_Error	= 'Error Update Tax Invoice Code...';
					}
				}

				## UPDATE QUOTATION ACCOMMODATION ##
				if ($ArrAkom) {
					$Imp_Akom		= implode("','", $ArrAkom);
					$Qry_UpdAccomm	= "UPDATE quotation_accommodations SET pros_invoice = 'N' WHERE id IN('" . $Imp_Akom . "')";
					$Has_UpdAccomm	= $this->db->query($Qry_UpdAccomm);
					if ($Has_UpdAccomm !== true) {
						$Pesan_Error	= 'Error Update Quotation Accommodation...';
					}
				}

				## UPDATE SERVICE ORDER ##
				if ($ArrLetter) {
					$Imp_Letter		= implode("','", $ArrLetter);
					$Qry_UpdLetter	= "UPDATE letter_orders SET flag_invoice = 'N' WHERE id IN('" . $Imp_Letter . "')";
					$Has_UpdLetter	= $this->db->query($Qry_UpdLetter);
					if ($Has_UpdLetter !== true) {
						$Pesan_Error	= 'Error Update Service Order...';
					}
				}

				## UPDATE QUOTATION ##
				if ($ArrQuot) {
					$Imp_Quot		= implode("','", $ArrQuot);
					$Qry_UpdQuot	= "UPDATE quotations SET stat_gen = 'OPEN' WHERE id IN('" . $Imp_Quot . "')";
					$Has_UpdQuot	= $this->db->query($Qry_UpdQuot);
					if ($Has_UpdQuot !== true) {
						$Pesan_Error	= 'Error Update Quotation...';
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
					history('Cancel Invoice ' . $Nomor_Invoice);
				}
			}
			echo json_encode($Arr_Return);
		} else {

			$Arr_Akses			= $this->Arr_Akses;
			if ($Arr_Akses['delete'] != '1') {
				$this->session->set_flashdata("alert_data", "<div class=\"alert alert-warning\" id=\"flash-message\">You Don't Have Right To Access This Page, Please Contact Your Administrator....</div>");
				redirect(site_url('Invoice_process'));
			}

			$rows_Header		= $this->db->get_where('invoices', array('id' => $Kode_Inv))->result();

			if ($rows_Header[0]->grand_tot <= 0) {
				$this->session->set_flashdata("alert_data", "<div class=\"alert alert-warning\" id=\"flash-message\">Data has been updated....</div>");
				redirect(site_url('Invoice_process'));
			}


			$rows_Detail		= $this->db->get_where('invoice_details', array('invoice_id' => $Kode_Inv))->result();
			$data = array(
				'title'			=> 'CANCELLATION INVOICE',
				'rows_header'	=> $rows_Header,
				'rows_detail'	=> $rows_Detail,
				'action'		=> 'batal_invoice'
			);
			$this->load->view($this->folder . '/v_invoice_cancel', $data);
		}
	}
	
	function download_invoice(){ 
		set_time_limit(0);
		$Month_Find			= $this->input->get('bulan');
		$Year_Find			= $this->input->get('tahun');
		$Cust_Find			= $this->input->get('nocust');
		
		$Judul				= '';
		
		$WHERE				= "grand_tot > 0";
		$Judul_Cabang	= 'ALL CUSTOMER';
		if(!empty($Cust_Find) && strtolower($Cust_Find) !== 'all'){
			$rows_Customer		= $this->db->get_where('customers',array('id'=>$Cust_Find))->row();
			$Judul_Cabang		= strtoupper($rows_Customer->name);
			if(!empty($WHERE))$WHERE	.=" AND ";
			$WHERE	.="customer_id = '".$Cust_Find."'";
			
		}
		$Period_Find	= '';
		if($Month_Find){
			if(!empty($WHERE))$WHERE	.=" AND ";
			$WHERE	.="MONTH(datet) = '".$Month_Find."'";
			$Period_Find	.=' '.date('F',mktime(0,0,0,$Month_Find,1,date('Y')));
		}
		
		if($Year_Find){
			if(!empty($WHERE))$WHERE	.=" AND ";
			$WHERE	.="YEAR(datet) = '".$Year_Find."'";
			$Period_Find	.=' '.$Year_Find;
		}
		
		
		
		
		$this->load->library("PHPExcel");
        $objPHPExcel = new PHPExcel();
		        
		// Set Creator & Title
        $objPHPExcel->getProperties()->setCreator("SISCAL")->setTitle("SISCAL");
 
		$objset = $objPHPExcel->setActiveSheetIndex(0);
		$objget = $objPHPExcel->getActiveSheet(); 
		$objget->setTitle('AR');
             
        $objget->getStyle("A5:Q5")->applyFromArray(
            array(
				'fill' => array(
					'type' => PHPExcel_Style_Fill::FILL_SOLID,
					'color' => array('rgb' => 'E1E0F7')
				),
				'font' => array(
					'color' => array('rgb' => '000000'),
					'bold' => true
				)
            )
        );
		
		
		
		$objset->setCellValue("A".'1', 'SISCAL'); 
		$objset->setCellValue("A".'2', 'INVOICE PERIODE :'.$Period_Find);
		$objset->setCellValue("A".'3', 'CUSTOMER :  '.$Judul_Cabang);
			
		//table header
		$cols = array("A","B","C","D","E","F","G","H","I","J","K","L","M","N","O","P","Q");
		$val = array("No","Invoice No","Inv Date","Customer","Address","Tax No","DPP","PPN","Total","PPH 23","Plan Payment Date","Paid Date","Total Paid","Bank","Withholding Tax Slip","Date Tax","Total Tax");
		
		for ($a=0;$a<count($cols); $a++){
			$objset->setCellValue($cols[$a].'5', $val[$a]);
			 
			//Setting lebar cell
			$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(10);
			$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(15);
			$objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(20);
			$objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(45);
			$objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(20);
			$objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(20);
			$objPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(20);
			$objPHPExcel->getActiveSheet()->getColumnDimension('H')->setWidth(20);
			$objPHPExcel->getActiveSheet()->getColumnDimension('I')->setWidth(10);
			$objPHPExcel->getActiveSheet()->getColumnDimension('J')->setWidth(20);
			$objPHPExcel->getActiveSheet()->getColumnDimension('K')->setWidth(20);
			$objPHPExcel->getActiveSheet()->getColumnDimension('L')->setWidth(20);
			$objPHPExcel->getActiveSheet()->getColumnDimension('M')->setWidth(20);
			$objPHPExcel->getActiveSheet()->getColumnDimension('N')->setWidth(20);
			$objPHPExcel->getActiveSheet()->getColumnDimension('O')->setWidth(20);
			$objPHPExcel->getActiveSheet()->getColumnDimension('P')->setWidth(20);
			$objPHPExcel->getActiveSheet()->getColumnDimension('Q')->setWidth(20);
		 
			$style = array(
				'alignment' => array(
					'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
				)
			);
			$objPHPExcel->getActiveSheet()->getStyle($cols[$a].'0')->applyFromArray($style);
		}
		
		
		
		$sql = "SELECT
				*
			FROM
				invoices
			WHERE ".$WHERE;
		$rows_Detail = $this->db->query($sql)->result();
		
		//baris mulai
		$baris   		= 6;
		$Loop			= 0;
		$tot_DPP = $tot_PPN = $tot_PPH = $tot_ALL =0;
		if($rows_Detail){
			foreach($rows_Detail as $key=>$vals){
				$Loop++;
				
		
				$Nil_DPP	= $vals->total_dpp;
				$Nil_PPN	= $vals->ppn;
				$Nil_PPH	= $vals->pph23;
				$Code_Inv	= $vals->id;
				
				$Total_Inv	= $vals->grand_tot;
				
				$tot_DPP		+=$Nil_DPP;
				$tot_PPN		+=$Nil_PPN;
				$tot_PPH		+=$Nil_PPH;
				$tot_ALL		+=$Total_Inv;
				
				$Plan_Bayar		= $Tgl_Bayar = $Bank = $No_Bukpot = $Tgl_Bukpot = '-';
				$Total_Bayar	= $Total_bukpot = 0;
				
				## AMBIL DATA AR ##
				$Query_AR		= "SELECT * FROM account_receivables WHERE invoice_no = '".$vals->invoice_no."' ORDER BY tahun DESC, bulan DESC LIMIT 1";
				$rows_AR		= $this->db->query($Query_AR)->row();
				if($rows_AR){
					$Saldo_Akhir	= $rows_AR->saldo_akhir;
					if($Saldo_Akhir > 0){
						$Query_Plan	= "SELECT plan_paid FROM follow_up_invoices WHERE invoice_id = '".$Code_Inv."' AND NOT(plan_paid IS NULL OR plan_paid ='' OR plan_paid ='-') ORDER BY follow_up_date DESC LIMIT 1";
						$rows_Plan	= $this->db->query($Query_Plan)->row();
						if($rows_Plan){
							$Plan_Bayar		= date('d-m-Y',strtotime($rows_Plan->plan_paid));
						}
					}
				}
				
				## AMBIL DATA JURNAL BUM ##
				$Query_BUM		= "SELECT
									jur_ar.jurnalid,
									jur_ar.tgl_jurnal,
									jur_ar.debet,
									jur_ar.kredit,
									jur_head.accid,
									head_coa.acc_name
								FROM
									trans_ar_jurnals jur_ar
								INNER JOIN trans_jurnal_headers jur_head ON jur_ar.jurnalid = jur_head.jurnalid
								LEFT JOIN coa_masters head_coa ON head_coa.accid = jur_head.accid
								WHERE
									jur_ar.flag_batal = 'N'
								AND jur_ar.invoice_no = '".$vals->invoice_no."'
								AND jur_head.tipe = 'BUM'
								AND (
									jur_ar.debet > 0
									OR jur_ar.kredit > 0
								)";
				$rows_BUM	= $this->db->query($Query_BUM)->result();
				if($rows_BUM){
					$Temp_BUM = $Temp_BUM_Date = $Temp_BUM_Bank = array();
					foreach($rows_BUM as $keyBUM=>$valBUM){
						$Debet			= $valBUM->debet;
						$Kredit			= $valBUM->kredit;
						$BUM_No			= $valBUM->jurnalid;
						$BUM_Tgl		= date('d-m-Y',strtotime($valBUM->tgl_jurnal));
						$Nama_COA		= $valBUM->acc_name;
						
						$Selisih_BUM	= $Kredit - $Debet;
						if($Selisih_BUM > 0){
							$Total_Bayar	+=$Selisih_BUM;
							$Temp_BUM[$BUM_No]			= $BUM_No;
							$Temp_BUM_Date[$BUM_Tgl]	= $BUM_Tgl;
							$Temp_BUM_Bank[$Nama_COA]	= $Nama_COA;
						}
						
					}
					
					$Tgl_Bayar	= implode(",",$Temp_BUM_Date);
					$Bank		= implode(",",$Temp_BUM_Bank);
				}
				
				## QUERY BUK POT ##
				$Query_BukPot		= "SELECT
											jur_ar.jurnalid,
											jur_ar.tgl_jurnal,
											jur_ar.debet,
											jur_ar.kredit,
											jur_head.no_reff
										FROM
											trans_ar_jurnals jur_ar
										INNER JOIN trans_jurnal_headers jur_head ON jur_ar.jurnalid = jur_head.jurnalid
										WHERE
											jur_ar.flag_batal = 'N'
										AND jur_ar.invoice_no = '".$vals->invoice_no."'
										AND jur_head.tipe = 'CN'
										AND NOT(jur_head.no_reff IS NULL OR jur_head.no_reff = '' OR jur_head.no_reff = '-')
										AND (
											jur_ar.debet > 0
											OR jur_ar.kredit > 0
										)";
				$rows_Bukpot		= $this->db->query($Query_BukPot)->result();
				if($rows_Bukpot){
					$Temp_CN = $Temp_CN_Date = $Temp_CN_Reff = array();
					foreach($rows_Bukpot as $keyCN=>$valCN){
						$Debet			= $valCN->debet;
						$Kredit			= $valCN->kredit;
						$CN_No			= $valCN->jurnalid;
						$CN_Tgl			= date('d-m-Y',strtotime($valCN->tgl_jurnal));
						$Code_Reff		= $valCN->no_reff;
						
						$Selisih_CN	= $Kredit - $Debet;
						if($Selisih_CN > 0){
							$Total_bukpot	+=$Selisih_CN;
							$Temp_CN[$CN_No]			= $CN_No;
							$Temp_CN_Date[$CN_Tgl]		= $CN_Tgl;
							$Temp_CN_Reff[$Code_Reff]	= $Code_Reff;
						}
						
					}
					
					$Tgl_Bukpot	= implode(",",$Temp_CN_Date);
					$No_Bukpot	= implode(",",$Temp_CN_Reff);
				}
				
				
				$objset->setCellValue("A".$baris, $Loop); 
				$objset->setCellValue("B".$baris, $vals->invoice_no);
				$objset->setCellValue("C".$baris, date('d-m-Y', strtotime($vals->datet))); 
				$objset->setCellValue("D".$baris, $vals->customer_name);
				$objset->setCellValue("E".$baris, $vals->address);
				$objset->setCellValue("F".$baris, $vals->no_faktur);
				$objset->setCellValue("G".$baris, $Nil_DPP);
				$objset->setCellValue("H".$baris, $Nil_PPN);
				$objset->setCellValue("I".$baris, $Total_Inv);
				$objset->setCellValue("J".$baris, $Nil_PPH);
				$objset->setCellValue("K".$baris, $Plan_Bayar);
				$objset->setCellValue("L".$baris, $Tgl_Bayar);
				$objset->setCellValue("M".$baris, $Total_Bayar);
				$objset->setCellValue("N".$baris, $Bank);
				$objset->setCellValue("O".$baris, $No_Bukpot);
				$objset->setCellValue("P".$baris, $Tgl_Bukpot);
				$objset->setCellValue("Q".$baris, $Total_bukpot);
				
				$baris++;
				
				
			}
			
		}
		  
		
									
		$objPHPExcel->getActiveSheet()->getStyle('G1:J'.$baris)->getNumberFormat()->setFormatCode('#,##0');				
		$objPHPExcel->getActiveSheet()->getStyle('G6:J'.$baris)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
		
		     
		//nama sheet
		$objPHPExcel->getActiveSheet()->setTitle($Judul_Cabang); 
		$objPHPExcel->setActiveSheetIndex(0);  
		
		//nama file
		$filename	= urlencode("REPORT_INOICE_".str_replace(' ','-',$Period_Find).".xls");
		
		
		
		// kalau ingin ganti .xls, ganti Excel2007 menjadi Excel5 
		$objWriter	= PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5'); 
		
		//ob_end_clean();
		header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
		header("Cache-Control: no-store, no-cache, must-revalidate");
		header("Cache-Control: post-check=0, pre-check=0", false);
		header("Pragma: no-cache");
		header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
		header('Content-Disposition: attachment;filename="'.$filename.'"');
		$objWriter->save("php://output");
	}  
}
