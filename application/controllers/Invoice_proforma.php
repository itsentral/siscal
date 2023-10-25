<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Invoice_proforma extends CI_Controller
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

		$this->folder	= 'Proforma_invoices';
	}

	public function index()
	{
		$Arr_Akses			= $this->Arr_Akses;
		if ($Arr_Akses['read'] != '1') {
			$this->session->set_flashdata("alert_data", "<div class=\"alert alert-warning\" id=\"flash-message\">You Don't Have Right To Access This Page, Please Contact Your Administrator....</div>");
			redirect(site_url('dashboard'));
		}

		$data = array(
			'title'			=> 'LIST INVOICE PROFORMA',
			'action'		=> 'index',
			'akses_menu'	=> $Arr_Akses
		);
		history('View List Invoice Proforma');
		$this->load->view($this->folder . '/v_invoice_proforma', $data);
	}
	function get_data_display()
	{
		$Arr_Akses			= $this->Arr_Akses;


		$requestData	= $_REQUEST;
		$fetch			= $this->qry_list_invoice(
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
			$Invoice_Code	= $row['id'];
			$Invoice_Date	= date('d-m-Y', strtotime($row['datet']));
			$Invoice_No		= $row['invoice_no'];
			$Customer		= $row['customer_name'];
			$Custid			= $row['customer_id'];
			$Alamat			= $row['address'];
			$Total			= $row['grand_tot'];
			$sts_Invoice	= $row['status'];

			$Text_SO		= $Text_PO = '';
			$Nomor_SO		= "SELECT
									GROUP_CONCAT(DISTINCT head_so.no_so) AS nomor_so
								FROM
									letter_orders head_so
								INNER JOIN proforma_invoice_details det_inv ON head_so.id = det_inv.letter_order_id
								WHERE
									det_inv.proforma_invoice_id = '".$Invoice_Code."'
								";
			$rows_SO		= $this->db->query($Nomor_SO)->row();
			$rows_Quot		= array();
			
			if($rows_SO){
				$Text_SO	= $rows_SO->nomor_so;				
			}
			
			$Nomor_Quot		= "SELECT
									GROUP_CONCAT(DISTINCT head_quot.pono) AS nomor_po
								FROM
									quotations head_quot
								INNER JOIN proforma_invoice_details det_inv ON head_quot.id = det_inv.quotation_id
								WHERE
									det_inv.proforma_invoice_id = '".$Invoice_Code."'
								";
			$rows_PO		= $this->db->query($Nomor_Quot)->row();
			if($rows_PO){
				$Text_PO	= $rows_PO->nomor_po;				
			}


			if ($sts_Invoice == 'OPN') {
				$Ket_Status	= "<span class='badge bg-maroon'>OPEN</span>";
			} else if ($sts_Invoice == 'CLS') {
				$Ket_Status	= "<span class='badge bg-orange'>CLOSE</span>";
			} else {
				$Ket_Status	= "<span class='badge bg-red'>CANCEL</span>";
			}
			$nestedData 	= array();

			$nestedData[]	= $Invoice_No;
			$nestedData[]	= $Invoice_Date;
			$nestedData[]	= $Customer;
			$nestedData[]	= $Text_SO;
			$nestedData[]	= $Text_PO;
			$nestedData[]	= $Ket_Status;

			$Template			= "";

			if ($Arr_Akses['read'] == 1) {
				$Template		.= "<button type='button' class='btn btn-sm' onClick='ActionInvoice({code:\"" . $Invoice_Code . "\",action :\"view_inv_proforma\",title:\"VIEW DETAIL INVOICE\"});' title='Detail invoice Proforma' style='background-color:#37474f;color:white;'> <i class='fa fa-search'></i> </button>";
			}
			if ($Arr_Akses['download'] == 1 && $sts_Invoice == 'OPN') {
				$Template		.= "&nbsp;&nbsp;<button type='button' class='btn btn-sm' onClick='PrintInvoice(\"" . $Invoice_Code . "\");' title='Print invoice Proforma' style='background-color:#006064;color:white;'> <i class='fa fa-print'></i> </button>";
			}

			if (($Arr_Akses['delete'] == 1 || $Arr_Akses['update'] == 1) && $sts_Invoice == 'OPN') {
				$Template		.= "&nbsp;&nbsp;<button type='button' class='btn btn-sm' onClick='ActionInvoice({code:\"" . $Invoice_Code . "\",action :\"cancel_inv_proforma\",title:\"CANCEL INVOICE\"});' title='Cancel invoice Proforma' style='background-color:#ff6f00;color:white;'> <i class='fa fa-trash-o'></i> </button>";
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
	public function qry_list_invoice($like_value = NULL, $column_order = NULL, $column_dir = NULL, $limit_start = NULL, $limit_length = NULL)
	{
		$WHERE		= "";


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
				grand_tot,
				`status`
				FROM
					proforma_invoices,
				(SELECT @row:=0) r ";
		if ($WHERE) {
			$sql .= " WHERE " . $WHERE;
		}

		//print_r($sql);exit();


		$columns_order_by = array(
			0 => 'invoice_no',
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

	function outstanding_invoice()
	{

		$Arr_Akses			= $this->Arr_Akses;
		//echo "<pre>";print_r($Arr_Akses);exit;
		if ($Arr_Akses['create'] != '1') {
			$this->session->set_flashdata("alert_data", "<div class=\"alert alert-warning\" id=\"flash-message\">You Don't Have Right To Access This Page, Please Contact Your Administrator....</div>");
			redirect(site_url('Invoice_proforma'));
		}

		$WHR_Find		= "head_quot.grand_tot <= 1100000
							AND head_so.flag_invoice = 'N'
							AND head_so.flag_proforma = 'N'
							AND head_quot.flag_proforma = 'N'
							AND head_so.sts_so NOT IN('CNC','REV')";
		## AMBIL DATA CUSTOMER ##
		$Qry_Customer		= "SELECT
									head_quot.customer_id,
									head_quot.customer_name
								FROM
									letter_orders head_so
								INNER JOIN quotations head_quot ON head_so.quotation_id = head_quot.id
								WHERE
									" . $WHR_Find . "
								GROUP BY head_quot.customer_id
								ORDER BY head_quot.customer_name ASC";
		$Pros_Cust			= $this->db->query($Qry_Customer)->result();
		$Arr_Customer		= array('' => 'Empty List');
		if ($Pros_Cust) {
			$Arr_Customer	= array('' => 'Select An Option');
			foreach ($Pros_Cust as $keyC => $valC) {
				$Kode_Cust					= $valC->customer_id;
				$Name_Cust					= $valC->customer_name;
				$Arr_Customer[$Kode_Cust]	= $Name_Cust;
			}
			unset($Pros_Cust);
		}

		$data = array(
			'title'			=> 'OUTSTANDING INVOICE',
			'action'		=> 'outstanding_invoice',
			'rows_cust'		=> $Arr_Customer
		);
		$this->load->view($this->folder . '/v_invoice_proforma_outs', $data);
	}


	function get_list_outstanding()
	{

		$Nocust			= $this->input->post('nocust');

		$requestData	= $_REQUEST;
		$fetch			= $this->qry_list_outstanding(
			$Nocust,
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
			$Kode_SO		= $row['letter_order_id'];
			$Tgl_SO			= date('d-m-Y', strtotime($row['tgl_so']));
			$Customer		= $row['customer_name'];
			$Custid			= $row['customer_id'];
			$Quotation		= $row['quotation_nomor'];
			$No_PO			= $row['pono'];
			$No_SO			= $row['no_so'];
			$Quot_Id		= $row['quotation_id'];
			$Total			= $row['grand_tot'];
			$Marketing		= $row['member_name'];


			$nestedData 	= array();

			$nestedData[]	= "<input type='checkbox' name='detDetail[]' value='" . $Kode_SO . "'>";
			$nestedData[]	= $No_SO;
			$nestedData[]	= $Tgl_SO;
			$nestedData[]	= $Customer;
			$nestedData[]	= $Quotation;
			$nestedData[]	= $No_PO;
			$nestedData[]	= $Marketing;

			$Template		= '-';
			if ($this->Arr_Akses['delete'] == 1 || $this->Arr_Akses['update'] == 1) {
				$Template		= "&nbsp;&nbsp;<button type='button' class='btn btn-sm' onClick='CloseOrder(\"" . $Kode_SO . "\");' title='Close SO' style='background-color:#c2185b;color:white;' id='btn_cancel_" . $Kode_SO . "' data-noso='" . $No_SO . "'> <i class='fa fa-trash-o'></i> </button>";
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
	public function qry_list_outstanding($custid, $like_value = NULL, $column_order = NULL, $column_dir = NULL, $limit_start = NULL, $limit_length = NULL)
	{

		$WHERE		= "head_quot.grand_tot <= 1100000
						AND head_so.flag_invoice = 'N'
						AND head_so.flag_proforma = 'N'
						AND head_so.sts_so NOT IN('CNC','REV')
						AND head_quot.flag_proforma = 'N'";



		if ($custid) {
			if (!empty($WHERE)) $WHERE	.= " AND ";
			$WHERE			.= "head_quot.customer_id ='" . $custid . "'";
		}



		if ($like_value) {
			if (!empty($WHERE)) $WHERE	.= " AND ";
			$WHERE	.= "(
						head_so.no_so LIKE '%" . $this->rental->escape_like_str($like_value) . "%'
						OR head_so.tgl_so LIKE '%" . $this->rental->escape_like_str($like_value) . "%'
						OR head_quot.customer_name LIKE '%" . $this->rental->escape_like_str($like_value) . "%'
						OR head_quot.nomor LIKE '%" . $this->rental->escape_like_str($like_value) . "%'
						OR head_quot.pono LIKE '%" . $this->rental->escape_like_str($like_value) . "%'
						OR head_quot.member_name LIKE '%" . $this->rental->escape_like_str($like_value) . "%'
						)";
		}



		$sql = "
			SELECT 
				(@row:=@row+1) AS nomor,
				head_so.id AS letter_order_id,
				head_so.no_so,
				head_so.tgl_so,
				head_quot.nomor AS quotation_nomor,
				head_so.quotation_id,
				head_quot.datet AS quotation_date,
				head_quot.customer_id,
				head_quot.customer_name,
				head_quot.pono,
				head_quot.podate,
				head_quot.po_receive,
				head_quot.grand_tot,
				head_quot.member_id,
				head_quot.member_name
			FROM
				letter_orders head_so
			INNER JOIN quotations head_quot ON head_so.quotation_id = head_quot.id,
			(SELECT @row:=0) r 
			WHERE " . $WHERE . "
			GROUP BY head_so.id
			";
		//print_r($sql);exit();


		$columns_order_by = array(
			1 => 'head_so.no_so',
			2 => 'head_so.tgl_so',
			3 => 'head_quot.customer_name',
			4 => 'head_quot.nomor',
			5 => 'head_quot.pono',
			6 => 'head_quot.member_name'

		);

		$jum_Data	= $this->db->query($sql)->num_rows();

		$sql .= " ORDER BY " . $columns_order_by[$column_order] . " " . $column_dir . " ";
		$sql .= " LIMIT " . $limit_start . " ," . $limit_length . " ";

		$data['query'] 					= $this->db->query($sql);
		$data['totalData']				= $jum_Data;
		$data['totalFiltered']			= $jum_Data;

		return $data;
	}

	function view_inv_proforma()
	{
		$rows_Header	= $rows_Detail	= $rows_Cust = array();
		if ($this->input->post()) {
			$Kode_Inv			= $this->input->post('code');
			$rows_Header		= $this->master_model->getArray('proforma_invoices', array('id' => $Kode_Inv));
			$rows_Detail		= $this->master_model->getArray('proforma_invoice_details', array('proforma_invoice_id' => $Kode_Inv));
			$rows_Cust			= $this->master_model->getArray('customers', array('id' => $rows_Header[0]['customer_id']));
		}

		$data = array(
			'title'			=> 'DETAIL INVOICE PROFORMA',
			'rows_header'	=> $rows_Header[0],
			'rows_detail'	=> $rows_Detail,
			'rows_cust'		=> $rows_Cust,
			'action'		=> 'view_inv_proforma'
		);
		$this->load->view($this->folder . '/v_invoice_proforma_preview', $data);
	}

	function print_invoice_proforma($Kode_Inv = '')
	{
		$rows_Header		= $this->master_model->getArray('proforma_invoices', array('id' => $Kode_Inv));
		$rows_Detail		= $this->master_model->getArray('proforma_invoice_details', array('proforma_invoice_id' => $Kode_Inv));
		$rows_Cust			= $this->master_model->getArray('customers', array('id' => $rows_Header[0]['customer_id']));
		$data 			= array(
			'title'			=> 'Print Invoice Proforma',
			'action'		=> 'print_invoice_proforma',
			'rows_header'	=> $rows_Header[0],
			'rows_detail'	=> $rows_Detail,
			'rows_cust'		=> $rows_Cust[0],
			'printby'		=> $this->session->userdata('siscal_username'),
			'today' 		=> date("Y-m-d H:i:s"),
		);


		$this->load->view($this->folder . '/v_invoice_proforma_print', $data);
	}

	function proses_close_order()
	{
		$Arr_Return		= array(
			'status'		=> 2,
			'pesan'			=> 'No records was found to process..'
		);
		if ($this->input->post()) {
			$CodeOrder		= $this->input->post('kode_so');
			$Reason			= strtoupper($this->input->post('alasan'));

			$rows_Find		= $this->db->get_where('letter_orders', array('id' => $CodeOrder))->result();
			if ($rows_Find[0]->sts_so == 'CNC' || $rows_Find[0]->sts_so == 'REV' || $rows_Find[0]->flag_proforma == 'Y') {
				$Arr_Return		= array(
					'status'		=> 2,
					'pesan'			=> 'Data has been modified by other process..'
				);
			} else {
				$UpdOrder	= array(
					'flag_proforma'				=> 'Y',
					'cancel_proforma_by'		=> $this->session->userdata('siscal_username'),
					'cancel_proforma_date'		=> date('Y-m-d H:i:s'),
					'cancel_proforma_reason'	=> $Reason
				);

				$this->db->trans_begin();

				$this->db->update('letter_orders', $UpdOrder, array('id' => $CodeOrder));

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
					history('Close Service Order Invoice Proforma ' . $rows_Find[0]->no_so);
				}
			}
		}

		echo json_encode($Arr_Return);
	}

	function generate_invoice()
	{
		$Arr_Return		= array();
		if ($this->input->post()) {
			//echo"<pre>";print_r($this->input->post());exit;
			$Customer		= $this->input->post('custid');
			$detPilih		= $this->input->post('detDetail');
			$Imp_Data		= implode("','", $detPilih);

			$Query_SO		= "SELECT
									det_order.*, head_order.no_so,
									head_order.tgl_so,
									head_order.quotation_id,
									head_order.customer_id,
									head_order.customer_name,
									head_order.flag_so_insitu,
									head_order.address_inv,
									head_quot.nomor AS quotation_nomor,
									head_quot.datet AS quotation_date,
									head_quot.podate,
									head_quot.pono,
									head_quot.po_receive,
									head_quot.exc_ppn,
									det_quot.price,
									det_quot.discount,
									det_quot.hpp
								FROM
									letter_order_details det_order
								INNER JOIN quotation_details det_quot ON det_quot.id = det_order.quotation_detail_id
								INNER JOIN letter_orders head_order ON det_order.letter_order_id = head_order.id
								INNER JOIN quotations head_quot ON head_quot.id = head_order.quotation_id
								WHERE
									head_order.id IN ('" . $Imp_Data . "')
								AND head_order.flag_proforma = 'N'
								AND head_order.flag_invoice = 'N'
								AND head_quot.flag_proforma = 'N'
								AND head_order.sts_so NOT IN ('CNC', 'REV')";
			$rows_Order		= $this->db->query($Query_SO)->result();
			if ($rows_Order) {
				$rows_Cust	= $this->db->get_where('customers', array('id' => $Customer))->result();
				$data = array(
					'title'			=> 'GENERATE INVOICE',
					'action'		=> 'generate_invoice',
					'rows_cust'		=> $rows_Cust,
					'rows_detail'	=> $rows_Order
				);
				$this->load->view($this->folder . '/v_invoice_proforma_generate', $data);
			} else {
				$this->session->set_flashdata("alert_data", "<div class=\"alert alert-warning\" id=\"flash-message\">Data has been modified by other process...</div>");
				redirect(site_url('Invoice_proforma'));
			}
		} else {
			$this->session->set_flashdata("alert_data", "<div class=\"alert alert-warning\" id=\"flash-message\">No record was found to process....</div>");
			redirect(site_url('Invoice_proforma'));
		}
	}

	function save_generate_invoice()
	{
		$rows_Balik	= array(
			'status'	=> 2,
			'pesan'		=> 'No record was found to process....'
		);
		if ($this->input->post()) {
			//echo"<pre>";print_r($this->input->post());exit;
			$Invoice_Date	= $this->input->post('tgl_invoice');
			$Customer		= strtoupper($this->input->post('customer_name'));
			$Nocust			= $this->input->post('customer_id');
			$Address		= $this->input->post('address');
			$Inv_DPP		= str_replace(',', '', $this->input->post('dpp'));
			$Inv_PPN		= str_replace(',', '', $this->input->post('ppn'));
			$Inv_Total		= str_replace(',', '', $this->input->post('grand_tot'));
			$Exec_PPN		= $this->input->post('exc_ppn');
			$detailInvoice	= $this->input->post('InvoiceDetail');
			$Created_Date	= date('Y-m-d H:i:s');
			$Created_By		= $this->session->userdata('siscal_userid');

			$Urut_Nomor		= $Urut_Code = 1;
			## AMBIL NOMOR URUT ##
			$Qry_Urut		= "SELECT
								MAX(
									CAST(
										SUBSTRING_INDEX(id, '-', - 1) AS UNSIGNED
									)
								) AS urut
							FROM
								proforma_invoices
							WHERE
								datet LIKE '" . date('Y-m', strtotime($Invoice_Date)) . "%'";
			$Find_Urut		= $this->db->query($Qry_Urut)->result();

			if ($Find_Urut) {
				$Urut_Code			= $Find_Urut[0]->urut + 1;
			}

			$Code_Invoice	= 'INV-PROF-' . date('Ym', strtotime($Invoice_Date)) . '-' . sprintf('%04d', $Urut_Code);

			$Qry_Nomor		= "SELECT
								MAX(
									CAST(
										SUBSTRING_INDEX(invoice_no, '/', - 1) AS UNSIGNED
									)
								) AS urut
							FROM
								proforma_invoices
							WHERE
								datet LIKE '" . date('Y', strtotime($Invoice_Date)) . "%'";
			$Find_Nomor		= $this->db->query($Qry_Nomor)->result();

			if ($Find_Nomor) {
				$Urut_Nomor		= $Find_Nomor[0]->urut + 1;
			}

			$Nomor_Invoice 	= substr($Invoice_Date, 0, 4) . '/STM-PROFORMA/' . sprintf('%05d', $Urut_Nomor);
			$rows_Cust		= $this->db->get_where('customers', array('id' => $Nocust))->result();

			$Ins_Header		= array(
				'id'				=> $Code_Invoice,
				'invoice_no'		=> $Nomor_Invoice,
				'datet'				=> $Invoice_Date,
				'customer_id'		=> $Nocust,
				'customer_name'		=> $Customer,
				'address'			=> $Address,
				'dpp'				=> $Inv_DPP,
				'diskon'			=> 0,
				'total_dpp'			=> $Inv_DPP,
				'ppn'				=> $Inv_PPN,
				'pph23'				=> round($Inv_DPP * 0.02),
				'grand_tot'			=> $Inv_Total,
				'npwp'				=> $rows_Cust[0]->npwp,
				'status'			=> 'OPN',
				'created_by'		=> $Created_By,
				'created_date'		=> $Created_Date
			);

			$Ins_Letter		= $Ins_Detail = $Ins_Delivery = $Ins_Accomodation  = array();
			if ($detailInvoice) {
				$intD		= 0;
				foreach ($detailInvoice as $keyD => $valD) {
					$intD++;
					$Tool_Code 		= $valD['tool_id'];
					$Range			= $valD['range'];
					$Price			= $valD['price'];
					$HPP			= $valD['hpp'];
					$Pieces			= $valD['piece_id'];
					$Tipe			= $valD['tipe'];
					$Detail_ID		= $valD['detail_id'];
					$Discount		= $valD['discount'];
					$Quot_Code		= $valD['quotation_id'];
					$SO_Code		= $valD['letter_order_id'];
					$Tool_Name		= $valD['tool_name'];
					$Qty			= $valD['qty'];
					$Total_Harga	= str_replace(',', '', $valD['total_harga']);
					$Total			= str_replace(',', '', $valD['total']);
					$Total_Disc		= $Total_Harga - $Total;
					$Ket_Inv		= '';

					if ($Tipe == 'A') {
						$Ins_Accomodation[$intD]	= array(
							'id'			=> $Detail_ID,
							'pros_proforma'	=> 'Y'
						);
						$Ket_Inv		= 'Insitu ' . ucwords(strtolower($Tool_Name));
					} else if ($Tipe == 'I') {
						$Ins_Delivery[$Detail_ID]	= $Qty;
						$Ket_Inv		= 'Biaya ' . ucwords(strtolower($Tool_Name));
					} else {
						$Ket_Inv		= 'Jasa Kalibrasi ' . $Tool_Name;
					}

					if (!empty($SO_Code) && $SO_Code !== '-') {
						$Ins_Letter[$intD]	= $SO_Code;
					}

					$Ins_Detail[$intD]		= array(
						'id'					=> $Code_Invoice . '-' . $intD,
						'proforma_invoice_id'	=> $Code_Invoice,
						'tool_id'				=> $Tool_Code,
						'tool_name'				=> $Ket_Inv,
						'range'					=> $Range,
						'piece_id'				=> $Pieces,
						'qty'					=> $Qty,
						'price'					=> $Price,
						'hpp'					=> $HPP,
						'discount'				=> $Discount,
						'total_discount'		=> $Total_Disc,
						'total_harga'			=> $Total_Harga,
						'detail_id'				=> $Detail_ID,
						'quotation_id'			=> $Quot_Code,
						'letter_order_id'		=> $SO_Code,
						'tipe'					=> $Tipe
					);
				}
			}

			$this->db->trans_begin();
			$this->db->insert('proforma_invoices', $Ins_Header);
			$this->db->insert_batch('proforma_invoice_details', $Ins_Detail);
			if ($Ins_Letter) {
				$SO_Imp		= implode("','", $Ins_Letter);
				$Upd_Letter	= "UPDATE letter_orders SET flag_proforma = 'Y' WHERE id IN('" . $SO_Imp . "')";
				$this->db->query($Upd_Letter);
			}

			if ($Ins_Accomodation) {
				$this->db->update_batch('quotation_accommodations', $Ins_Accomodation, 'id');
			}

			if ($Ins_Delivery) {
				foreach ($Ins_Delivery as $keyU => $valU) {
					$Upd_Delivery	= "UPDATE quotation_deliveries SET pros_proforma = pros_proforma + " . $valU . " WHERE id = '" . $keyU . "'";
					$this->db->query($Upd_Delivery);
				}
			}

			if ($this->db->trans_status() != TRUE) {
				$this->db->trans_rollback();
				$rows_Balik		= array(
					'status'		=> 2,
					'pesan'			=> 'Invoice Proforma Process  Failed, please try again...'
				);
			} else {
				$this->db->trans_commit();
				$rows_Balik		= array(
					'status'		=> 1,
					'pesan'			=> 'Invoice Proforma process success. Thank you & have a nice day......'
				);
				history('Create Invoice Proforma ' . $Nomor_Invoice);
			}
		}
		echo json_encode($rows_Balik);
	}


	function cancel_inv_proforma()
	{
		if ($this->input->post()) {
			$Code_Cancel		= $this->input->post('code');
			$rows_Header		= $this->master_model->getArray('proforma_invoices', array('id' => $Code_Cancel));
			$rows_Detail		= $this->master_model->getArray('proforma_invoice_details', array('proforma_invoice_id' => $Code_Cancel));
			$rows_Cust			= $this->master_model->getArray('customers', array('id' => $rows_Header[0]['customer_id']));


			$data = array(
				'title'			=> 'CANCEL INVOICE PROFORMA',
				'rows_header'	=> $rows_Header[0],
				'rows_cust'		=> $rows_Cust[0],
				'rows_detail'	=> $rows_Detail,
				'action'		=> 'cancel_inv_proforma'
			);
			$this->load->view($this->folder . '/v_invoice_proforma_cancel', $data);
		}
	}

	function save_cancel_inv_proforma()
	{

		$Arr_Return		= array(
			'status'		=> 2,
			'pesan'			=> 'No records was found.....'
		);

		if ($this->input->post()) {
			$cancel_By		= $this->session->userdata('siscal_userid');
			$cancel_Date		= date('Y-m-d H:i:s');

			$Inv_Cancel		= $this->input->post('code_inv_cancel');
			$Reason			= $this->input->post('cancel_reason');

			$rows_Check		= $this->db->get_where('proforma_invoices', array('id' => $Inv_Cancel))->result();
			//echo"<pre>";print_r($this->input->post());exit;
			if ($rows_Check[0]->status != 'OPN') {
				$Arr_Return		= array(
					'status'		=> 2,
					'pesan'			=> 'Data has been processed...'
				);
			} else {
				$Upd_header	= array(
					'cancel_by'		=> $cancel_By,
					'cancel_date'	=> $cancel_Date,
					'reason'		=> $Reason,
					'status'		=> 'BTL'
				);



				$rows_Quot			= $this->master_model->getArray('proforma_invoice_details',array('id'=>$Inv_Cancel,'tipe'=>'T'),'quotation_id','quotation_id');
				$rows_SO			= $this->master_model->getArray('proforma_invoice_details',array('id'=>$Inv_Cancel,'tipe'=>'T'),'letter_order_id','letter_order_id');
				$rows_Quot_Accom	= $this->master_model->getArray('proforma_invoice_details',array('id'=>$Inv_Cancel,'tipe'=>'A'),'detail_id','detail_id');
				$rows_Quot_Del		= $this->master_model->getArray('proforma_invoice_details',array('id'=>$Inv_Cancel,'tipe'=>'I'),'detail_id','qty');
				

				$this->db->trans_begin();
				$this->db->update('proforma_invoices', $Upd_header, array('id' => $Inv_Cancel));
				
				if($rows_Quot){
					$Imp_Quot	= implode_data($rows_Quot);
					$Upd_Quot	= "UPDATE quotations SET flag_proforma='N' WHERE id IN ('".$Imp_Quot."') AND flag_proforma = 'Y'";
					$this->db->query($Upd_Quot);
				}
				
				if ($rows_SO) {
					$Imp_SO				= implode_data($rows_SO);
					$Upd_SO				= "UPDATE letter_orders SET flag_proforma='N' WHERE id IN ('" . $Imp_SO . "')";
					$this->db->query($Upd_SO);
				}

				if ($rows_Quot_Accom) {
					$Imp_Accomodation	= implode_data($rows_Quot_Accom);
					$Upd_Trans			= "UPDATE quotation_accommodations SET pros_proforma='N' WHERE id IN ('" . $Imp_Trans . "')";
					$this->db->query($Upd_Trans);
				}

				if ($rows_Quot_Del) {
					foreach ($rows_Quot_Del as $keyU => $valU) {
						$Upd_Delivery	= "UPDATE quotation_deliveries SET pros_proforma = pros_proforma - " . $valU . " WHERE id = '" . $keyU . "'";
						$this->db->query($Upd_Delivery);
					}
				}

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
					history('Cancel Invoice Proforma ' . $Inv_Cancel);
				}
			}
		}
		echo json_encode($Arr_Return);
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
	
	function GetCustomerQuotation(){
		## AMBIL DATA CUSTOMER ##
		$Qry_Customer		= "SELECT
									customer_id,
									customer_name
								FROM
									quotations
								WHERE
									STATUS IN ('REC')
								AND YEAR (datet) >= '2023'
								AND flag_proforma = 'N'
								AND id NOT IN (
									SELECT
										quotation_id
									FROM
										letter_orders
									WHERE
										(
											flag_invoice = 'Y'
											OR flag_proforma = 'Y'
										)
									AND YEAR (tgl_so) >= '2023'
									GROUP BY
										quotation_id
								)
								GROUP BY customer_id
								ORDER BY customer_name ASC";
		$Pros_Cust			= $this->db->query($Qry_Customer)->result();
		$rows_Customer		= array(''=>'Empty List');
		if($Pros_Cust){
			$rows_Customer	= array(''=>'Select An Option');
			foreach($Pros_Cust as $keyC=>$valC){
				$Kode_Cust					= $valC->customer_id;
				$Name_Cust					= $valC->customer_name;
				$rows_Customer[$Kode_Cust]	= $Name_Cust;
			}
			unset($Pros_Cust);
		}
		
		return $rows_Customer;
	}
	
	function outs_invoice_quotation(){
		
		$Arr_Akses			= $this->Arr_Akses;
		//echo "<pre>";print_r($Arr_Akses);exit;
		if($Arr_Akses['create'] !='1'){
			$this->session->set_flashdata("alert_data", "<div class=\"alert alert-warning\" id=\"flash-message\">You Don't Have Right To Access This Page, Please Contact Your Administrator....</div>");
			redirect(site_url('Invoice_proforma'));
		}
		
		$rows_Customer		= $this->GetCustomerQuotation();
		
		$data = array(
			'title'			=> 'OUTSTANDING INVOICE PROFORMA - QUOTATION',
			'action'		=> 'outs_invoice_quotation',
			'rows_cust'		=> $rows_Customer
		);
		$this->load->view($this->folder.'/v_invoice_proforma_outs_quot',$data); 
	}
	
	function display_out_quotation(){
		$Arr_Akses			= $this->Arr_Akses;
		$User_Groupid	= $this->session->userdata('siscal_group_id');
		$WHERE			= "`status` IN ('REC')
							AND YEAR (datet) >= '2021'
							AND flag_proforma = 'N'
							AND id NOT IN (
								SELECT
									quotation_id
								FROM
									letter_orders
								WHERE
									(
										flag_invoice = 'Y'
										OR flag_proforma = 'Y'
									)
								AND YEAR (tgl_so) >= '2021'
								GROUP BY
									quotation_id
							)";
		if($User_Groupid == '2'){
			$User_Member	= $this->session->userdata('siscal_member_id');
			if(!empty($WHERE))$WHERE	.=" AND ";
			$WHERE	.="member_id = '".$User_Member."'";
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
			0 => 'nomor',
			1 => 'datet',
			2 => 'customer_name',
			3 => 'pono',
			4 => 'podate',
			5 => 'member_name',
			6 => 'grand_tot'
		);
		
		
		
		if($like_value){
			if(!empty($WHERE))$WHERE	.=" AND ";
			$WHERE	.="(
						  nomor LIKE '%".$this->db->escape_like_str($like_value)."%'
						  OR datet, '%d %b %Y') LIKE '%".$this->db->escape_like_str($like_value)."%'
						  OR grand_tot LIKE '%".$this->db->escape_like_str($like_value)."%'
						  OR customer_name LIKE '%".$this->db->escape_like_str($like_value)."%'
						  OR pono LIKE '%".$this->db->escape_like_str($like_value)."%'
						  OR DATE_FORMAT(podate, '%d %b %Y') LIKE '%".$this->db->escape_like_str($like_value)."%'
						  OR member_name LIKE '%".$this->db->escape_like_str($like_value)."%'
						)";
		}
		
		
		
		$sql = "SELECT
					*,
					(@row:=@row+1) AS urut
				FROM
					quotations,
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
			
			$Code_Quot		= $row['id'];
			$Nomor_Quot		= $row['nomor'];
			$Date_Quot		= date('d-m-Y',strtotime($row['datet']));
			$Custid			= $row['customer_id'];
			$Customer		= $row['customer_name'];
			$Marketing		= strtoupper($row['member_name']);
			$Quot_PO		= $row['pono'];
			$Quot_PO_Date	= date('d-m-Y',strtotime($row['podate']));
			
			$Total_Quot		= number_format($row['grand_tot']);
			
			
			$Template		= '';			
			if($Arr_Akses['create'] == '1'){
				$Template		= '<a href="'.site_url().'/Invoice_proforma/create_quotation_proforma_inv?quot='.urlencode($Code_Quot).'" class="btn btn-sm bg-navy-active"  title="CREATE INVOICE PROFORMA"> <i class="fa fa-money"></i> </a>';
			}
			
			
			
			
			$nestedData		= array();
			$nestedData[]	= $Nomor_Quot;
			$nestedData[]	= $Date_Quot;
			$nestedData[]	= $Customer;
			$nestedData[]	= $Quot_PO;
			$nestedData[]	= $Quot_PO_Date;
			$nestedData[]	= $Marketing;
			$nestedData[]	= $Total_Quot;
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
	
	function create_quotation_proforma_inv(){
		$Arr_Return		= array();
		if($this->input->get()){
			//echo"<pre>";print_r($this->input->post());exit;
			$Code_Quotation		= urldecode($this->input->get('quot'));
			$rows_Quotation		= $this->db->get_where('quotations',array('id'=>$Code_Quotation,'flag_proforma'=>'N'))->row();
			if($rows_Quotation){
				$rows_Detail	= $this->db->get_where('quotation_details',array('quotation_id'=>$Code_Quotation))->result();
				$rows_Delivery	= $this->db->get_where('quotation_deliveries',array('quotation_id'=>$Code_Quotation))->result();
				$rows_Akomodasi	= $this->db->get_where('quotation_accommodations',array('quotation_id'=>$Code_Quotation))->result();
				$rows_Cust		= $this->db->get_where('customers',array('id'=>$rows_Quotation->customer_id))->row();
				$data = array(
					'title'			=> 'GENERATE INVOICE PROFORMA - QUOTATION',
					'action'		=> 'create_quotation_proforma_inv',
					'rows_cust'		=> $rows_Cust,
					'rows_header'	=> $rows_Quotation,
					'rows_detail'	=> $rows_Detail,
					'rows_delivery'	=> $rows_Delivery,
					'rows_akomdasi'	=> $rows_Akomodasi
				);
				$this->load->view($this->folder.'/v_invoice_proforma_generate_quot',$data); 
			}else{
				$this->session->set_flashdata("alert_data", "<div class=\"alert alert-warning\" id=\"flash-message\">Data has been modified by other process...</div>");
				redirect(site_url('Invoice_proforma/outs_invoice_quotation'));
			}
			
		}else{
			$this->session->set_flashdata("alert_data", "<div class=\"alert alert-warning\" id=\"flash-message\">No record was found to process....</div>");
			redirect(site_url('Invoice_proforma/outs_invoice_quotation'));
		}
		
		
	}
	
	function save_generate_invoice_quotation(){
		$rows_Balik	= array(
			'status'	=> 2,
			'pesan'		=> 'No record was found to process....'
		);
		if($this->input->post()){
			//echo"<pre>";print_r($this->input->post());exit;
			$Invoice_Date	= $this->input->post('tgl_invoice');
			$Customer		= strtoupper($this->input->post('customer_name'));
			$Nocust			= $this->input->post('customer_id');
			$Address		= $this->input->post('address');
			$Code_Quot		= $this->input->post('quotation_id');
			$Inv_DPP		= str_replace(',','',$this->input->post('dpp'));
			$Inv_PPN		= str_replace(',','',$this->input->post('ppn'));
			$Inv_Total		= str_replace(',','',$this->input->post('grand_tot'));
			$Exec_PPN		= $this->input->post('exc_ppn');
			$detailInvoice	= $this->input->post('InvoiceDetail');
			$Created_Date	= date('Y-m-d H:i:s');
			$Created_By		= $this->session->userdata('siscal_userid');
			
			$Urut_Nomor		= $Urut_Code = 1;
			## AMBIL NOMOR URUT ##
			$Qry_Urut		= "SELECT
								MAX(
									CAST(
										SUBSTRING_INDEX(id, '-', - 1) AS UNSIGNED
									)
								) AS urut
							FROM
								proforma_invoices
							WHERE
								datet LIKE '".date('Y-m',strtotime($Invoice_Date))."%'";
			$Find_Urut		= $this->db->query($Qry_Urut)->result();
			
			if($Find_Urut){
				$Urut_Code			= $Find_Urut[0]->urut + 1;
			}
			
			$Code_Invoice	= 'INV-PROF-'.date('Ym',strtotime($Invoice_Date)).'-'.sprintf('%04d',$Urut_Code);
			
			$Qry_Nomor		= "SELECT
								MAX(
									CAST(
										SUBSTRING_INDEX(invoice_no, '/', - 1) AS UNSIGNED
									)
								) AS urut
							FROM
								proforma_invoices
							WHERE
								datet LIKE '".date('Y',strtotime($Invoice_Date))."%'";
			$Find_Nomor		= $this->db->query($Qry_Nomor)->result();
			
			if($Find_Nomor){
				$Urut_Nomor		= $Find_Nomor[0]->urut + 1;
			}
			
			$Nomor_Invoice 	= substr($Invoice_Date,0,4).'/STM-PROFORMA/'.sprintf('%05d',$Urut_Nomor);
			$rows_Cust		= $this->db->get_where('customers',array('id'=>$Nocust))->result();
			
			$Ins_Header		= array(
				'id'				=> $Code_Invoice,
				'invoice_no'		=> $Nomor_Invoice,
				'datet'				=> $Invoice_Date,
				'customer_id'		=> $Nocust,
				'customer_name'		=> $Customer,
				'address'			=> $Address,
				'dpp'				=> $Inv_DPP,
				'diskon'			=> 0,
				'total_dpp'			=> $Inv_DPP,
				'ppn'				=> $Inv_PPN,
				'pph23'				=> round($Inv_DPP * 0.02),
				'grand_tot'			=> $Inv_Total,
				'npwp'				=> $rows_Cust[0]->npwp,
				'status'			=> 'OPN',
				'created_by'		=> $Created_By,
				'created_date'		=> $Created_Date
			);
			
			$Ins_Detail = $Ins_Delivery = $Ins_Accomodation  = array();
			if($detailInvoice){
				$intD		= 0;
				foreach($detailInvoice as $keyD=>$valD){
					$intD++;
					$Tool_Code 		= $valD['tool_id'];
					$Range			= $valD['range'];
					$Price			= $valD['price'];
					$HPP			= $valD['hpp'];
					$Pieces			= $valD['piece_id'];
					$Tipe			= $valD['tipe'];
					$Detail_ID		= $valD['detail_id'];
					$Discount		= $valD['discount'];
					$Quot_Code		= $valD['quotation_id'];
					$SO_Code		= $valD['letter_order_id'];
					$Tool_Name		= $valD['tool_name'];
					$Qty			= $valD['qty'];
					$Total_Harga	= str_replace(',','',$valD['total_harga']);
					$Total			= str_replace(',','',$valD['total']);
					$Total_Disc		= $Total_Harga - $Total;
					$Ket_Inv		= '';
					
					if($Tipe == 'A'){
						$Ins_Accomodation[$intD]	= array(
							'id'			=> $Detail_ID,
							'pros_proforma'	=> 'Y'
						);
						$Ket_Inv		= 'Insitu '.ucwords(strtolower($Tool_Name));
					}else if($Tipe == 'I'){
						$Ins_Delivery[$Detail_ID]	= $Qty;
						$Ket_Inv		= 'Biaya '.ucwords(strtolower($Tool_Name));
					}else{
						$Ket_Inv		= 'Jasa Kalibrasi '.$Tool_Name;
					}
					
					
					
					$Ins_Detail[$intD]		= array(
						'id'					=> $Code_Invoice.'-'.$intD,
						'proforma_invoice_id'	=> $Code_Invoice,
						'tool_id'				=> $Tool_Code,
						'tool_name'				=> $Ket_Inv,
						'range'					=> $Range,
						'piece_id'				=> $Pieces,
						'qty'					=> $Qty,
						'price'					=> $Price,
						'hpp'					=> $HPP,
						'discount'				=> $Discount,
						'total_discount'		=> $Total_Disc,
						'total_harga'			=> $Total_Harga,
						'detail_id'				=> $Detail_ID,
						'quotation_id'			=> $Quot_Code,
						'letter_order_id'		=> $SO_Code,
						'tipe'					=> $Tipe
					);
					
				}
			}
			
			$this->db->trans_begin();
			$this->db->insert('proforma_invoices',$Ins_Header);
			$this->db->insert_batch('proforma_invoice_details',$Ins_Detail);
			
			$Upd_Quotation	= "UPDATE quotations SET flag_proforma = 'Y' WHERE id ='".$Code_Quot."'";
			$this->db->query($Upd_Quotation);
			
			
			if($Ins_Accomodation){
				$this->db->update_batch('quotation_accommodations',$Ins_Accomodation,'id');
			}
			
			if($Ins_Delivery){
				foreach($Ins_Delivery as $keyU=>$valU){
					$Upd_Delivery	= "UPDATE quotation_deliveries SET pros_proforma = pros_proforma + ".$valU." WHERE id = '".$keyU."'";
					$this->db->query($Upd_Delivery);
				}
				
			}
			
			if ($this->db->trans_status() != TRUE){
				$this->db->trans_rollback();
				$rows_Balik		= array(
					'status'		=> 2,
					'pesan'			=> 'Invoice Proforma Process  Failed, please try again...'
				);
			}else{
				$this->db->trans_commit();
				$rows_Balik		= array(
					'status'		=> 1,
					'pesan'			=> 'Invoice Proforma process success. Thank you & have a nice day......'
				);
				history('Create Invoice Proforma '.$Nomor_Invoice);
			}

		}
		echo json_encode($rows_Balik);
	}
}
