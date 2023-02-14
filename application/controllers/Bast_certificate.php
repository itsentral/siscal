<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Bast_certificate extends CI_Controller
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

		$this->folder	= 'Bast_Certificate';
	}

	public function index()
	{
		$Arr_Akses			= $this->Arr_Akses;
		if ($Arr_Akses['read'] != '1') {
			$this->session->set_flashdata("alert_data", "<div class=\"alert alert-warning\" id=\"flash-message\">You Don't Have Right To Access This Page, Please Contact Your Administrator....</div>");
			redirect(site_url('dashboard'));
		}

		$data = array(
			'title'			=> 'Manage BAST Certificates',
			'action'		=> 'index',
			'akses_menu'	=> $Arr_Akses
		);
		history('View List BAST Certificates');
		$this->load->view($this->folder . '/main_bast', $data);
	}
	function get_data_display()
	{
		$Arr_Akses			= $this->Arr_Akses;


		$requestData	= $_REQUEST;
		$fetch			= $this->qry_list_bast(
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
			$Kode_BAST		= $row['id'];
			$Tgl_BAST		= date('d-m-Y', strtotime($row['datet']));
			$Nomor_Bast		= $row['nomor'];
			$Customer		= $row['customer_name'];
			$Custid			= $row['customer_id'];
			$Alamat			= $row['address'];
			$sts_BAST		= $row['status'];

			if ($sts_BAST == 'OPN') {
				$Ket_Status	= "<span class='badge bg-maroon'>OPEN</span>";
			} else if ($sts_BAST == 'CLS') {
				$Ket_Status	= "<span class='badge bg-orange'>CLOSE</span>";
			} else {
				$Ket_Status	= "<span class='badge bg-red'>CANCEL</span>";
			}
			$nestedData 	= array();

			$nestedData[]	= $Nomor_Bast;
			$nestedData[]	= $Tgl_BAST;
			$nestedData[]	= $Customer;
			$nestedData[]	= $Ket_Status;

			$Template			= "";

			if ($Arr_Akses['read'] == 1) {
				$Template		.= "<button type='button' class='btn btn-sm btn-info' onClick='view_bast(\"" . $Kode_BAST . "\");'> <i class='fa fa-search'></i> </button>";
			}
			if ($Arr_Akses['download'] == 1 && $sts_BAST == 'OPN') {
				$Template		.= "&nbsp;<a href='" . site_url('Bast_certificate/print_bast/' . $Kode_BAST) . "' class='btn btn-sm btn-warning' title='Print BAST' target='_blank'> <i class='fa fa-print'></i> </a>";
			}

			if ($Arr_Akses['update'] == 1 && $sts_BAST == 'OPN') {
				$Template		.= "&nbsp;<a href='" . site_url('Bast_certificate/update_bast/' . $Kode_BAST) . "' class='btn btn-sm btn-success' title='Update BAST'> <i class='fa fa-edit'></i> </a>";
			}

			if ($Arr_Akses['delete'] == 1 && $sts_BAST == 'OPN') {
				$Template		.= "&nbsp;<a href='" . site_url('Bast_certificate/cancel_bast/' . $Kode_BAST) . "' class='btn btn-sm btn-danger' title='Cancel BAST'> <i class='fa fa-trash'></i> </a>";
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
	public function qry_list_bast($like_value = NULL, $column_order = NULL, $column_dir = NULL, $limit_start = NULL, $limit_length = NULL)
	{
		$WHERE		= "";


		if ($like_value) {
			if (!empty($WHERE)) $WHERE	.= " AND ";
			$WHERE	.= "(
						nomor LIKE '%" . $this->db->escape_like_str($like_value) . "%'
						OR datet LIKE '%" . $this->db->escape_like_str($like_value) . "%'
						OR customer_name LIKE '%" . $this->db->escape_like_str($like_value) . "%'
						OR `status` LIKE '%" . $this->db->escape_like_str($like_value) . "%'
						)";
		}



		$sql = "
			SELECT 
				(@row:=@row+1) AS nomor_urut,
				id,
				nomor,
				datet,
				customer_id,
				customer_name,
				address,
				receive_date,
				`status`
				FROM
					bast_certificates,
				(SELECT @row:=0) r ";
		if ($WHERE) {
			$sql .= " WHERE " . $WHERE;
		}




		$columns_order_by = array(
			0 => 'nomor',
			1 => 'datet',
			2 => 'customer_name',
			3 => 'status'


		);

		$jum_Data	= $this->db->query($sql)->num_rows();

		$sql .= " ORDER BY " . $columns_order_by[$column_order] . " " . $column_dir . " ";
		$sql .= " LIMIT " . $limit_start . " ," . $limit_length . " ";

		$data['query'] 					= $this->db->query($sql);
		$data['totalData']				= $jum_Data;
		$data['totalFiltered']			= $jum_Data;

		return $data;
	}

	function outstanding_bast()
	{

		$Arr_Akses			= $this->Arr_Akses;
		//echo "<pre>";print_r($Arr_Akses);exit;
		if ($Arr_Akses['create'] != '1') {
			$this->session->set_flashdata("alert_data", "<div class=\"alert alert-warning\" id=\"flash-message\">You Don't Have Right To Access This Page, Please Contact Your Administrator....</div>");
			redirect(site_url('Bast_certificate'));
		}

		## AMBIL DATA CUSTOMER ##
		$Qry_Customer		= "SELECT
									`header`.`customer_id` AS `customer_id`,
									`header`.`customer_name` AS `customer_name`
								FROM
									trans_data_details detail
								INNER JOIN trans_details header ON detail.trans_detail_id = header.id
								INNER JOIN letter_orders letter ON letter.id = header.letter_order_id
								WHERE
									detail.flag_proses = 'Y'
								AND NOT (
									detail.no_sertifikat IS NULL
									OR detail.no_sertifikat = ''
									OR detail.no_sertifikat = '-'
								)
								AND (
									detail.flag_send IS NULL
									OR detail.flag_send = ''
									OR detail.flag_send = 'N'
								)
								AND detail.approve_certificate = 'APV'
								GROUP BY header.customer_id
								ORDER BY header.customer_name ASC";
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
			'title'			=> 'List Outstanding BAST Certificate',
			'action'		=> 'outstanding_bast',
			'rows_cust'		=> $Arr_Customer
		);
		$this->load->view($this->folder . '/main_outs_bast', $data);
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
			$Total_Cert		= $row['total_certitfate'];


			$nestedData 	= array();

			$nestedData[]	= "<input type='checkbox' name='detDetail[]' value='" . $Kode_SO . "'>";
			$nestedData[]	= $No_SO;
			$nestedData[]	= $Tgl_SO;
			$nestedData[]	= $Customer;
			$nestedData[]	= $Quotation;
			$nestedData[]	= $Total_Cert;


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
		$WHERE		= "detail.flag_proses = 'Y'
						AND NOT (
							detail.no_sertifikat IS NULL
							OR detail.no_sertifikat = ''
							OR detail.no_sertifikat = '-'
						)
						AND (
							detail.flag_send IS NULL
							OR detail.flag_send = ''
							OR detail.flag_send = 'N'
						)
						AND detail.approve_certificate = 'APV'";
		if ($custid) {
			if (!empty($WHERE)) $WHERE	.= " AND ";
			$WHERE			.= "header.customer_id ='" . $custid . "'";
		}



		if ($like_value) {
			if (!empty($WHERE)) $WHERE	.= " AND ";
			$WHERE	.= "(
						header.no_so LIKE '%" . $this->rental->escape_like_str($like_value) . "%'
						OR header.tgl_so LIKE '%" . $this->rental->escape_like_str($like_value) . "%'
						OR header.customer_name LIKE '%" . $this->rental->escape_like_str($like_value) . "%'
						OR header.quotation_nomor LIKE '%" . $this->rental->escape_like_str($like_value) . "%'
						)";
		}



		$sql = "
			SELECT 
				(@row:=@row+1) AS nomor,
				header.letter_order_id,
				header.tgl_so,
				header.no_so,
				header.quotation_id,
				header.quotation_nomor,
				header.customer_id,
				header.customer_name,
				header.pono,
				header.podate,
				COUNT(detail.id) AS total_certitfate
			FROM
				trans_data_details detail
			INNER JOIN trans_details header ON detail.trans_detail_id = header.id
			INNER JOIN letter_orders letter ON letter.id = header.letter_order_id,
			(SELECT @row:=0) r 
			WHERE " . $WHERE . "
			GROUP BY header.letter_order_id
			";
		//print_r($sql);exit();


		$columns_order_by = array(
			1 => 'no_so',
			2 => 'tgl_so',
			3 => 'customer_name',
			4 => 'quotation_nomor'

		);

		$jum_Data	= $this->db->query($sql)->num_rows();

		$sql .= " ORDER BY " . $columns_order_by[$column_order] . " " . $column_dir . " ";
		$sql .= " LIMIT " . $limit_start . " ," . $limit_length . " ";

		$data['query'] 					= $this->db->query($sql);

		$data['totalData']				= $jum_Data;
		$data['totalFiltered']			= $jum_Data;

		return $data;
	}

	function detail_bast()
	{
		$Kode_Bast			= $this->input->post('kode_bast');
		$rows_header		= $this->master_model->getArray('bast_certificates', array('id' => $Kode_Bast));

		$rows_detail		= $this->master_model->getArray('bast_certificate_details', array('bast_certificate_id' => $Kode_Bast));
		$data = array(
			'title'			=> 'Detail BAST',
			'rows_header'	=> $rows_header[0],
			'rows_detail'	=> $rows_detail,
			'action'		=> 'detail_bast'
		);
		$this->load->view($this->folder . '/detail_bast', $data);
	}

	function print_bast($Kode_Bast = '')
	{
		$rows_header		= $this->master_model->getArray('bast_certificates', array('id' => $Kode_Bast));
		$rows_detail		= $this->master_model->getArray('bast_certificate_details', array('bast_certificate_id' => $Kode_Bast));
		$rows_cust			= $this->master_model->getArray('customers', array('id' => $rows_header[0]['customer_id']));
		$data 			= array(
			'title'			=> 'Print BAST',
			'action'		=> 'print_bast',
			'rows_header'	=> $rows_header[0],
			'rows_detail'	=> $rows_detail,
			'rows_cust'		=> $rows_cust[0],
			'printby'		=> $this->session->userdata('siscal_username'),
			'today' 		=> date("Y-m-d H:i:s"),
		);


		$this->load->view($this->folder . '/print_bast', $data);
	}

	function proses_bast()
	{
		if ($this->input->post()) {
			$Nocust		= $this->input->post('custid');
			$det_Pilih	= $this->input->post('detDetail');
			$Imp_data	= implode("','", $det_Pilih);
			$WHR_Noso	= array(
				'id IN'	=> "('" . $Imp_data . "')"
			);

			//$Rows_NoSO	= $this->master_model->getArray('letter_orders',$WHR_Noso,'no_so','no_so');
			$Rows_Cust	= $this->db->get_where('customers', array('id' => $Nocust))->result();
			$Qry_Detail	= "SELECT
								detail.id AS id,
								header.quotation_id AS quotation_id,
								header.quotation_detail_id AS quotation_detail_id,
								header.quotation_nomor AS quotation_nomor,
								header.quotation_date AS quotation_date,
								header.letter_order_id AS letter_order_id,
								header.letter_order_detail_id AS letter_order_detail_id,
								header.no_so AS no_so,
								header.tgl_so AS tgl_so,
								header.schedule_detail_id AS schedule_detail_id,
								header.schedule_id AS schedule_id,
								header.schedule_nomor AS schedule_nomor,
								header.schedule_date AS schedule_date,
								header.customer_id AS customer_id,
								header.customer_name AS customer_name,
								header.tool_id AS tool_id,
								header.tool_name AS tool_name,
								header.supplier_id AS supplier_id,
								header.supplier_name AS supplier_name,
								1 AS qty_result,
								header.hpp AS hpp,
								header.price AS price,
								header.diskon AS diskon,
								header.labs AS labs,
								header.insitu AS insitu,
								header.subcon AS subcon,
								header.plan_process_date AS plan_process_date,
								header.plan_time_start AS plan_time_start,
								header.plan_time_end AS plan_time_end,
								COALESCE (
									detail.actual_teknisi_id,
									header.teknisi_id
								) AS code_teknisi,
								COALESCE (
									detail.actual_teknisi_name,
									header.teknisi_name
								) AS name_teknisi,
								detail.datet AS actual_process_date,
								detail.start_time AS actual_process_start,
								detail.end_time AS actual_process_end,
								detail.merk AS merk,
								detail.tool_type AS tool_type,
								detail.no_identifikasi AS no_identifikasi,
								detail.no_sertifikat AS no_sertifikat,
								detail.valid_until AS valid_until,
								detail.file_name AS file_name,
								detail.prosedur_kalibrasi AS prosedur_kalibrasi,
								detail.standar_kalibrasi AS standar_kalibrasi,
								detail.suhu AS suhu,
								detail.kelembaban AS kelembaban,
								detail.flag_print AS flag_print,
								detail.flag_send AS flag_send,
								header.pono AS pono,
								header.podate AS podate,
								letter.address_sertifikat
							FROM
								trans_data_details detail
							INNER JOIN trans_details header ON detail.trans_detail_id = header.id
							INNER JOIN letter_orders letter ON letter.id = header.letter_order_id
							WHERE
								detail.flag_proses = 'Y'
							AND NOT (
								detail.no_sertifikat IS NULL
								OR detail.no_sertifikat = ''
								OR detail.no_sertifikat = '-'
							)
							AND (
								detail.flag_send IS NULL
								OR detail.flag_send = ''
								OR detail.flag_send = 'N'
							)
							AND detail.approve_certificate = 'APV'
							AND header.letter_order_id IN ('" . $Imp_data . "')
							AND header.customer_id='" . $Nocust . "'";



			$Pros_Det	= $this->db->query($Qry_Detail);
			if ($Pros_Det->num_rows() > 0) {
				$rows_Detail		= $Pros_Det->result();

				$data = array(
					'title'			=> 'BAST Certificate Process',
					'action'		=> 'proses_bast',
					'rows_detail'	=> $rows_Detail,
					'rows_cust'		=> $Rows_Cust
				);
				$this->load->view($this->folder . '/create_bast', $data);
			} else {
				$this->session->set_flashdata("alert_data", "<div class=\"alert alert-warning\" id=\"flash-message\">No records was found. Data has been modified...</div>");
				redirect(site_url('Bast_certificate/outstanding_bast'));
			}
		} else {
			$this->session->set_flashdata("alert_data", "<div class=\"alert alert-warning\" id=\"flash-message\">No records was found to process....</div>");
			redirect(site_url('Bast_certificate/outstanding_bast'));
		}
	}

	function bast_save_process()
	{
		$Arr_Return		= array();
		if ($this->input->post()) {
			//echo"<pre>";print_r($this->input->post());exit;
			$Created_By		= $this->session->userdata('siscal_userid');
			$Created_Date	= date('Y-m-d H:i:s');

			$Datet			= $this->input->post('tgl_bast');
			$Nocust			= $this->input->post('customer_id');
			$Customer		= $this->input->post('customer_name');
			$Address		= $this->input->post('address');
			$Notes			= $this->input->post('notes');
			$Periode_Bast	= date('Y-m', strtotime($Datet));

			$det_Detail		= $this->input->post('det_Detail');
			$det_Pilih		= $this->input->post('det_Pilih');

			$Imp_data		= implode_data($det_Pilih, 'value');
			$bulan			= date('m', strtotime($Datet));
			$romawi			= getRomawi($bulan);

			## CEK APAKAH SUDAH DIPROSES ATAU BELUM ##
			$Qry_Cek		=	"SELECT 
									* 
								FROM 
									trans_data_details 
								WHERE 
									id IN ('" . $Imp_data . "') 
								AND flag_proses = 'Y'
								AND NOT (
									no_sertifikat IS NULL
									OR no_sertifikat = ''
									OR no_sertifikat = '-'
								)
								AND (
									flag_send IS NULL
									OR flag_send = ''
									OR flag_send = 'N'
								)
								AND approve_certificate = 'APV'";

			$num_Cek		= $this->db->query($Qry_Cek)->num_rows();
			if ($num_Cek > 0) {
				## AMBIL URUT ##
				$Urut_Id		= 1;
				$Qry_Urut		= "SELECT * FROM bast_certificates WHERE datet LIKE '" . $Periode_Bast . "%' ORDER BY SUBSTRING(id,-4) DESC LIMIT 1";
				$det_Urut		= $this->db->query($Qry_Urut)->result();
				if ($det_Urut) {
					$Urut_Id	= intval(substr($det_Urut[0]->id, -4)) + 1;
				}

				$Kode_Bast		= 'BAST-CERT-N' . date('ym', strtotime($Datet)) . '-' . sprintf('%04d', $Urut_Id);

				## AMBIL NOMOR ##
				$Urut_Nomor		= 1;
				$Qry_Nomor		= "SELECT MAX(CAST(SUBSTRING_INDEX(nomor, '/', 1) AS UNSIGNED)) as urut FROM bast_certificates WHERE datet LIKE '" . $Periode_Bast . "%' LIMIT 1";
				$det_Nomor		= $this->db->query($Qry_Nomor)->result();
				if ($det_Nomor) {
					$Urut_Nomor	= intval($det_Nomor[0]->urut) + 1;
				}
				$Nomor_Bast		=  sprintf('%04d', $Urut_Nomor) . '/N-BAST.CRTF/' . $romawi . '/' . date('Y', strtotime($Datet));

				$Ins_Header		= array(
					'id'			=> $Kode_Bast,
					'nomor'			=> $Nomor_Bast,
					'customer_id'	=> $Nocust,
					'customer_name'	=> $Customer,
					'address'		=> $Address,
					'descr'			=> $Notes,
					'status'		=> 'OPN',
					'created_by'	=> $Created_By,
					'created_date'	=> $Created_Date,
					'datet'			=> $Datet
				);

				$Upd_Letter		= $Upd_Trans  = $Ins_Detail		= $Arr_SO = array();
				if ($det_Pilih) {
					$intL	= $intS	= 0;
					foreach ($det_Pilih as $keyI => $valI) {
						$intL++;
						$Kode_Detail								= $Kode_Bast . '-' . $intL;
						$de_Find									= $det_Detail[$keyI];
						$no_SO										= $de_Find['letter_order_id'];

						$Ins_Detail[$intL]							= $de_Find;
						$Ins_Detail[$intL]['id']					= $Kode_Detail;
						$Ins_Detail[$intL]['bast_certificate_id']	= $Kode_Bast;
						$Ins_Detail[$intL]['trans_data_detail_id']	= $valI;

						$Upd_Trans[$intL]		= array(
							'id'			=> $valI,
							'flag_send'		=> 'Y'
						);

						if (!isset($Arr_SO[$no_SO]) && empty($Arr_SO[$no_SO])) {
							$intS++;
							$Upd_Letter[$intS]		= array(
								'id'					=> $no_SO,
								'flag_certificate'		=> 'Y'
							);
						}
					}
				}
				unset($det_Pilih);
				unset($det_Detail);

				$this->db->trans_begin();
				$this->db->insert('bast_certificates', $Ins_Header);
				$this->db->insert_batch('bast_certificate_details', $Ins_Detail);
				$this->db->update_batch('trans_data_details', $Upd_Trans, 'id');
				$this->db->update_batch('letter_orders', $Upd_Letter, 'id');

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
					history('Add Bast Certificate ' . $Nomor_Bast);
				}
			} else {
				$Arr_Return		= array(
					'status'		=> 2,
					'pesan'			=> 'Data has been processed...'
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

	function update_bast($kode_bast = '')
	{
		if ($this->input->post()) {
			$modified_By		= $this->session->userdata('siscal_userid');
			$modified_Date		= date('Y-m-d H:i:s');

			$Nobast			= $this->input->post('id');
			$Send_By		= $this->input->post('send_by');
			$Receive_By		= $this->input->post('receive_by');
			$Receive_Date	= $this->input->post('receive_date');

			$rows_Check		= $this->master_model->getArray('bast_certificates', array('id' => $Nobast));
			//echo"<pre>";print_r($rows_Check);exit;
			if ($rows_Check[0]['status'] != 'OPN') {
				$Arr_Return		= array(
					'status'		=> 2,
					'pesan'			=> 'Data has been processed...'
				);
			} else {
				$Upd_header	= array(
					'send_by'		=> $Send_By,
					'receive_by'	=> $Receive_By,
					'receive_date'	=> $Receive_Date,
					'status'		=> 'CLS'
				);

				$this->db->trans_begin();
				$this->db->update('bast_certificates', $Upd_header, array('id' => $Nobast));

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
					history('Update Bast Certificate ' . $Nobast);
				}
			}
			echo json_encode($Arr_Return);
		} else {

			$Arr_Akses			= $this->Arr_Akses;
			if ($Arr_Akses['update'] != '1') {
				$this->session->set_flashdata("alert_data", "<div class=\"alert alert-warning\" id=\"flash-message\">You Don't Have Right To Access This Page, Please Contact Your Administrator....</div>");
				redirect(site_url('Bast_certificate'));
			}


			$rows_header		= $this->master_model->getArray('bast_certificates', array('id' => $kode_bast));
			if ($rows_header[0]['status'] != 'OPN') {
				$this->session->set_flashdata("alert_data", "<div class=\"alert alert-warning\" id=\"flash-message\">Data has been updated....</div>");
				redirect(site_url('Bast_certificate'));
			}
			$rows_detail		= $this->master_model->getArray('bast_certificate_details', array('bast_certificate_id' => $kode_bast));
			$data = array(
				'title'			=> 'Update BAST',
				'rows_header'	=> $rows_header[0],
				'rows_detail'	=> $rows_detail,
				'action'		=> 'update_bast'
			);
			$this->load->view($this->folder . '/receive_bast', $data);
		}
	}

	function cancel_bast($kode_bast = '')
	{
		if ($this->input->post()) {
			$cancel_By		= $this->session->userdata('siscal_userid');
			$cancel_Date		= date('Y-m-d H:i:s');

			$Nobast			= $this->input->post('id');
			$Reason			= $this->input->post('cancel_reason');

			$rows_Check		= $this->master_model->getArray('bast_certificates', array('id' => $Nobast));

			if ($rows_Check[0]['status'] != 'OPN') {
				$Arr_Return		= array(
					'status'		=> 2,
					'pesan'			=> 'Data has been processed...'
				);
			} else {
				$Upd_header	= array(
					'cancel_by'		=> $cancel_By,
					'cancel_date'	=> $cancel_Date,
					'cancel_reason'	=> $Reason,
					'status'		=> 'CNC'
				);

				$rows_SO			= $this->master_model->getArray('bast_certificate_details', array('bast_certificate_id' => $Nobast), 'letter_order_id', 'letter_order_id');
				$rows_Trans			= $this->master_model->getArray('bast_certificate_details', array('bast_certificate_id' => $Nobast), 'trans_data_detail_id', 'trans_data_detail_id');

				$Imp_SO				= implode_data($rows_SO);
				$Imp_Trans			= implode_data($rows_Trans);

				$Upd_SO				= "UPDATE letter_orders SET flag_certificate='N' WHERE id IN ('" . $Imp_SO . "')";
				$Upd_Trans			= "UPDATE trans_data_details SET flag_send='N' WHERE id IN ('" . $Imp_Trans . "')";

				$this->db->trans_begin();
				$this->db->update('bast_certificates', $Upd_header, array('id' => $Nobast));
				$this->db->query($Upd_SO);
				$this->db->query($Upd_Trans);

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
					history('Cancel Bast Certificate ' . $Nobast);
				}
			}
			echo json_encode($Arr_Return);
		} else {

			$Arr_Akses			= $this->Arr_Akses;
			if ($Arr_Akses['delete'] != '1') {
				$this->session->set_flashdata("alert_data", "<div class=\"alert alert-warning\" id=\"flash-message\">You Don't Have Right To Access This Page, Please Contact Your Administrator....</div>");
				redirect(site_url('Bast_certificate'));
			}


			$rows_header		= $this->master_model->getArray('bast_certificates', array('id' => $kode_bast));
			if ($rows_header[0]['status'] != 'OPN') {
				$this->session->set_flashdata("alert_data", "<div class=\"alert alert-warning\" id=\"flash-message\">Data has been updated....</div>");
				redirect(site_url('Bast_certificate'));
			}
			$rows_detail		= $this->master_model->getArray('bast_certificate_details', array('bast_certificate_id' => $kode_bast));
			$data = array(
				'title'			=> 'Cancel Process BAST',
				'rows_header'	=> $rows_header[0],
				'rows_detail'	=> $rows_detail,
				'action'		=> 'cancel_bast'
			);
			$this->load->view($this->folder . '/delete_bast', $data);
		}
	}
}
