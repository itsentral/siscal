<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Dashboard_certificates extends CI_Controller
{
	public function __construct()
	{
		parent::__construct();
		$this->load->model('master_model');
		// Your own constructor code

		if (!$this->session->userdata('isSISCALlogin')) {
			redirect('login');
		}
	}
	public function index()
	{
		$records_data	= $this->json_dashboard('N');
		$data			= array(
			'action'	=> 'index',
			'title'		=> 'Dashboard Cerificate',
			'rows_data'	=> $records_data
		);

		$this->load->view('view_dashboard/dashboard_certificate', $data);
	}



	## JSON DATA DASHBOARD
	function json_dashboard($json = 'Y')
	{
		$Arr_Return		= array();


		## CERTIFICATE UNUPLOADED INTERNAL ##


		$Tgl_Beda						= date('Y-m-d', strtotime('-2 days'));
		//$Query_Sertf					= "SELECT * FROM view_tool_certificates WHERE flag_sertifikat<>'Y' AND tgl_kalibrasi < '".$Tgl_Beda."'";
		$Query_Sertf					= "SELECT
												detail.id,
												header.quotation_id,
												header.quotation_detail_id,
												header.quotation_nomor,
												header.quotation_date,
												header.letter_order_id,
												header.letter_order_detail_id,
												header.no_so,
												header.tgl_so,
												header.schedule_detail_id,
												header.schedule_id,
												header.schedule_nomor,
												header.schedule_date,
												header.customer_id,
												header.customer_name,
												header.tool_id,
												header.tool_name,
												header.supplier_id,
												header.supplier_name,
												1 AS qty_result,
												header.hpp,
												header.price,
												header.diskon,
												header.labs,
												header.insitu,
												header.subcon,
												header.plan_process_date,
												header.plan_time_start,
												header.plan_time_end,
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
												detail.merk,
												detail.tool_type,
												detail.no_identifikasi,
												detail.no_sertifikat,
												detail.valid_until,
												detail.file_name,
												detail.prosedur_kalibrasi,
												detail.standar_kalibrasi,
												detail.suhu,
												detail.kelembaban,
												detail.flag_print,
												detail.flag_send,
												detail.supervisor_name,
												(
													CASE
													WHEN detail.no_sertifikat = ''
													OR detail.no_sertifikat IS NULL
													OR detail.no_sertifikat = '-' THEN
														'N'
													ELSE
														'Y'
													END
												) AS flag_sertifikat,
												(
													CASE
													WHEN detail.datet = ''
													OR detail.datet IS NULL
													OR detail.datet = '0000-00-00'
													OR detail.datet = '1970-01-01' THEN
														header.plan_process_date
													ELSE
														detail.datet
													END
												) AS tgl_kalibrasi
											FROM
												trans_data_details detail
											INNER JOIN trans_details header ON header.id = detail.trans_detail_id
											WHERE
												detail.flag_proses = 'Y'
											AND (
												detail.no_sertifikat = ''
												OR detail.no_sertifikat IS NULL
												OR detail.no_sertifikat = '-'
											)
											AND LOWER(header.supplier_name) = 'internal'
											AND (
												CASE
												WHEN detail.datet = ''
												OR detail.datet IS NULL
												OR detail.datet = '0000-00-00'
												OR detail.datet = '1970-01-01' THEN
													header.plan_process_date
												ELSE
													detail.datet
												END
											) < '" . $Tgl_Beda . "'";
		$Count_Certificate				= $this->db->query($Query_Sertf)->num_rows();
		$Arr_Return['upload_sertifikat'] = $Count_Certificate;


		## CERTIFICATE UNUPLOADED SUBCON ##

		$Tgl_Beda						= date('Y-m-d', strtotime('-9 days'));
		//$Query_Sertf					= "SELECT * FROM view_tool_certificates WHERE flag_sertifikat<>'Y' AND tgl_kalibrasi < '".$Tgl_Beda."'";
		$Query_Sertf					= "SELECT
												detail.id,
												header.quotation_id,
												header.quotation_detail_id,
												header.quotation_nomor,
												header.quotation_date,
												header.letter_order_id,
												header.letter_order_detail_id,
												header.no_so,
												header.tgl_so,
												header.schedule_detail_id,
												header.schedule_id,
												header.schedule_nomor,
												header.schedule_date,
												header.customer_id,
												header.customer_name,
												header.tool_id,
												header.tool_name,
												header.supplier_id,
												header.supplier_name,
												1 AS qty_result,
												header.hpp,
												header.price,
												header.diskon,
												header.labs,
												header.insitu,
												header.subcon,
												header.plan_process_date,
												header.plan_time_start,
												header.plan_time_end,
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
												detail.merk,
												detail.tool_type,
												detail.no_identifikasi,
												detail.no_sertifikat,
												detail.valid_until,
												detail.file_name,
												detail.prosedur_kalibrasi,
												detail.standar_kalibrasi,
												detail.suhu,
												detail.kelembaban,
												detail.flag_print,
												detail.flag_send,
												(
													CASE
													WHEN detail.no_sertifikat = ''
													OR detail.no_sertifikat IS NULL
													OR detail.no_sertifikat = '-' THEN
														'N'
													ELSE
														'Y'
													END
												) AS flag_sertifikat,
												(
													CASE
													WHEN detail.datet = ''
													OR detail.datet IS NULL
													OR detail.datet = '0000-00-00'
													OR detail.datet = '1970-01-01' THEN
														header.plan_process_date
													ELSE
														detail.datet
													END
												) AS tgl_kalibrasi
											FROM
												trans_data_details detail
											INNER JOIN trans_details header ON header.id = detail.trans_detail_id
											WHERE
												detail.flag_proses = 'Y'
											AND (
												detail.no_sertifikat = ''
												OR detail.no_sertifikat IS NULL
												OR detail.no_sertifikat = '-'
											)
											AND LOWER(header.supplier_name) != 'internal'
											AND (
												CASE
												WHEN detail.datet = ''
												OR detail.datet IS NULL
												OR detail.datet = '0000-00-00'
												OR detail.datet = '1970-01-01' THEN
													header.plan_process_date
												ELSE
													detail.datet
												END
											) < '" . $Tgl_Beda . "'";
		$Count_Certificate				= $this->db->query($Query_Sertf)->num_rows();
		$Arr_Return['subcon_sertifikat'] = $Count_Certificate;

		## LATE KIRIM SERTIFIKAT ##

		$Tgl_Beda						= date('Y-m-d', strtotime('-2 days'));
		$Query_Sertf					= "SELECT
												detail.id,
												header.quotation_id,
												header.quotation_detail_id,
												header.quotation_nomor,
												header.quotation_date,
												header.letter_order_id,
												header.letter_order_detail_id,
												header.no_so,
												header.tgl_so,
												header.schedule_detail_id,
												header.schedule_id,
												header.schedule_nomor,
												header.schedule_date,
												header.customer_id,
												header.customer_name,
												header.tool_id,
												header.tool_name,
												header.supplier_id,
												header.supplier_name,
												1 AS qty_result,
												header.hpp,
												header.price,
												header.diskon,
												header.labs,
												header.insitu,
												header.subcon,
												header.plan_process_date,
												header.plan_time_start,
												header.plan_time_end,
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
												detail.merk,
												detail.tool_type,
												detail.no_identifikasi,
												detail.no_sertifikat,
												detail.valid_until,
												detail.file_name,
												detail.prosedur_kalibrasi,
												detail.standar_kalibrasi,
												detail.suhu,
												detail.kelembaban,
												detail.flag_print,
												detail.flag_send,
												DATE_FORMAT(
													detail.modified_date,
													'%Y-%m-%d'
												) AS tgl_upload
											FROM
												trans_data_details detail
											INNER JOIN trans_details header ON header.id = detail.trans_detail_id
											WHERE
												detail.flag_proses = 'Y'
											AND NOT (
												detail.no_sertifikat = ''
												OR detail.no_sertifikat IS NULL
												OR detail.no_sertifikat = '-'
											)
											AND LOWER(header.supplier_name) = 'internal'
											AND DATE_FORMAT(
												detail.modified_date,
												'%Y-%m-%d'
											) < '" . $Tgl_Beda . "'
											AND (
												detail.flag_send = 'N'
												OR detail.flag_send = ''
												OR detail.flag_send IS NULL
											)";
		$Count_Certificate				= $this->db->query($Query_Sertf)->num_rows();
		$Arr_Return['kirim_sertifikat'] = $Count_Certificate;


		## OUTSTANDING REMINDER ##
		$Tgl_Balik						= date('Y-m-d', strtotime('+1 month', strtotime(date('Y-m-d'))));
		$Query_Reminder					= "SELECT * FROM view_outstanding_reminders WHERE valid_until < '" . $Tgl_Balik . "'";
		$Count_Reminder					= $this->db->query($Query_Reminder)->num_rows();
		$Arr_Return['total_reminder']	= $Count_Reminder;


		if ($json == 'Y') {
			echo json_encode($Arr_Return);
		} else {
			return $Arr_Return;
		}
	}



	public function getcertificatedata($tipe)
	{
		$Cond		= array();
		if ($tipe == 1) {
			$Tgl_Beda		= date('Y-m-d', strtotime('-2 days'));
			$Query_Sertf					= "SELECT
												detail.id,
												header.quotation_id,
												header.quotation_detail_id,
												header.quotation_nomor,
												header.quotation_date,
												header.letter_order_id,
												header.letter_order_detail_id,
												header.no_so,
												header.tgl_so,
												header.schedule_detail_id,
												header.schedule_id,
												header.schedule_nomor,
												header.schedule_date,
												header.customer_id,
												header.customer_name,
												header.tool_id,
												header.tool_name,
												header.supplier_id,
												header.supplier_name,
												1 AS qty_result,
												header.hpp,
												header.price,
												header.diskon,
												header.labs,
												header.insitu,
												header.subcon,
												header.plan_process_date,
												header.plan_time_start,
												header.plan_time_end,
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
												detail.merk,
												detail.tool_type,
												detail.no_identifikasi,
												detail.no_sertifikat,
												detail.valid_until,
												detail.file_name,
												detail.prosedur_kalibrasi,
												detail.standar_kalibrasi,
												detail.suhu,
												detail.kelembaban,
												detail.flag_print,
												detail.flag_send,
												detail.supervisor_name,
												(
													CASE
													WHEN detail.no_sertifikat = ''
													OR detail.no_sertifikat IS NULL
													OR detail.no_sertifikat = '-' THEN
														'N'
													ELSE
														'Y'
													END
												) AS flag_sertifikat,
												(
													CASE
													WHEN detail.datet = ''
													OR detail.datet IS NULL
													OR detail.datet = '0000-00-00'
													OR detail.datet = '1970-01-01' THEN
														header.plan_process_date
													ELSE
														detail.datet
													END
												) AS tgl_kalibrasi
											FROM
												trans_data_details detail
											INNER JOIN trans_details header ON header.id = detail.trans_detail_id
											WHERE
												detail.flag_proses = 'Y'
											AND (
												detail.no_sertifikat = ''
												OR detail.no_sertifikat IS NULL
												OR detail.no_sertifikat = '-'
											)
											AND LOWER(header.supplier_name) = 'internal'
											AND (
												CASE
												WHEN detail.datet = ''
												OR detail.datet IS NULL
												OR detail.datet = '0000-00-00'
												OR detail.datet = '1970-01-01' THEN
													header.plan_process_date
												ELSE
													detail.datet
												END
											) < '" . $Tgl_Beda . "'";
			$records		= $this->db->query($Query_Sertf)->result_array();
		} else if ($tipe == 2) {
			$Tgl_Beda		= date('Y-m-d', strtotime('-9 days'));
			$Query_Sertf					= "SELECT
												detail.id,
												header.quotation_id,
												header.quotation_detail_id,
												header.quotation_nomor,
												header.quotation_date,
												header.letter_order_id,
												header.letter_order_detail_id,
												header.no_so,
												header.tgl_so,
												header.schedule_detail_id,
												header.schedule_id,
												header.schedule_nomor,
												header.schedule_date,
												header.customer_id,
												header.customer_name,
												header.tool_id,
												header.tool_name,
												header.supplier_id,
												header.supplier_name,
												1 AS qty_result,
												header.hpp,
												header.price,
												header.diskon,
												header.labs,
												header.insitu,
												header.subcon,
												header.plan_process_date,
												header.plan_time_start,
												header.plan_time_end,
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
												detail.merk,
												detail.tool_type,
												detail.no_identifikasi,
												detail.no_sertifikat,
												detail.valid_until,
												detail.file_name,
												detail.prosedur_kalibrasi,
												detail.standar_kalibrasi,
												detail.suhu,
												detail.kelembaban,
												detail.flag_print,
												detail.flag_send,
												(
													CASE
													WHEN detail.no_sertifikat = ''
													OR detail.no_sertifikat IS NULL
													OR detail.no_sertifikat = '-' THEN
														'N'
													ELSE
														'Y'
													END
												) AS flag_sertifikat,
												(
													CASE
													WHEN detail.datet = ''
													OR detail.datet IS NULL
													OR detail.datet = '0000-00-00'
													OR detail.datet = '1970-01-01' THEN
														header.plan_process_date
													ELSE
														detail.datet
													END
												) AS tgl_kalibrasi
											FROM
												trans_data_details detail
											INNER JOIN trans_details header ON header.id = detail.trans_detail_id
											WHERE
												detail.flag_proses = 'Y'
											AND (
												detail.no_sertifikat = ''
												OR detail.no_sertifikat IS NULL
												OR detail.no_sertifikat = '-'
											)
											AND LOWER(header.supplier_name) != 'internal'
											AND (
												CASE
												WHEN detail.datet = ''
												OR detail.datet IS NULL
												OR detail.datet = '0000-00-00'
												OR detail.datet = '1970-01-01' THEN
													header.plan_process_date
												ELSE
													detail.datet
												END
											) < '" . $Tgl_Beda . "'";
			$records		= $this->db->query($Query_Sertf)->result_array();
		} else if ($tipe === '3') {
			## LATE KIRIM SERTIFIKAT ##

			$Tgl_Beda						= date('Y-m-d', strtotime('-2 days'));
			$Query_Sertf					= "SELECT
													detail.id,
													header.quotation_id,
													header.quotation_detail_id,
													header.quotation_nomor,
													header.quotation_date,
													header.letter_order_id,
													header.letter_order_detail_id,
													header.no_so,
													header.tgl_so,
													header.schedule_detail_id,
													header.schedule_id,
													header.schedule_nomor,
													header.schedule_date,
													header.customer_id,
													header.customer_name,
													header.tool_id,
													header.tool_name,
													header.supplier_id,
													header.supplier_name,
													1 AS qty_result,
													header.hpp,
													header.price,
													header.diskon,
													header.labs,
													header.insitu,
													header.subcon,
													header.plan_process_date,
													header.plan_time_start,
													header.plan_time_end,
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
													detail.merk,
													detail.tool_type,
													detail.no_identifikasi,
													detail.no_sertifikat,
													detail.valid_until,
													detail.file_name,
													detail.prosedur_kalibrasi,
													detail.standar_kalibrasi,
													detail.suhu,
													detail.kelembaban,
													detail.flag_print,
													detail.flag_send,
													DATE_FORMAT(
														detail.modified_date,
														'%Y-%m-%d'
													) AS tgl_upload
												FROM
													trans_data_details detail
												INNER JOIN trans_details header ON header.id = detail.trans_detail_id
												WHERE
													detail.flag_proses = 'Y'
												AND NOT (
													detail.no_sertifikat = ''
													OR detail.no_sertifikat IS NULL
													OR detail.no_sertifikat = '-'
												)
												AND LOWER(header.supplier_name) = 'internal'
												AND DATE_FORMAT(
													detail.modified_date,
													'%Y-%m-%d'
												) < '" . $Tgl_Beda . "'
												AND (
													detail.flag_send = 'N'
													OR detail.flag_send = ''
													OR detail.flag_send IS NULL
												)";
			$records				= $this->db->query($Query_Sertf)->result_array();
		}

		$data			= array(
			'tipe'			=> $tipe,
			'records'		=> $records
		);

		$this->load->view('view_dashboard/getcertificatedata', $data);
	}

	public function export_sertifikat($tipe_late)
	{
		$Tgl_Telat			= date('Y-m-d');
		$Cond				= array();
		if ($tipe_late == 1) {
			$Tgl_Beda		= date('Y-m-d', strtotime('-2 days'));
			$Query_Sertf					= "SELECT
												detail.id,
												header.quotation_id,
												header.quotation_detail_id,
												header.quotation_nomor,
												header.quotation_date,
												header.letter_order_id,
												header.letter_order_detail_id,
												header.no_so,
												header.tgl_so,
												header.schedule_detail_id,
												header.schedule_id,
												header.schedule_nomor,
												header.schedule_date,
												header.customer_id,
												header.customer_name,
												header.tool_id,
												header.tool_name,
												header.supplier_id,
												header.supplier_name,
												1 AS qty_result,
												header.hpp,
												header.price,
												header.diskon,
												header.labs,
												header.insitu,
												header.subcon,
												header.plan_process_date,
												header.plan_time_start,
												header.plan_time_end,
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
												detail.merk,
												detail.tool_type,
												detail.no_identifikasi,
												detail.no_sertifikat,
												detail.valid_until,
												detail.file_name,
												detail.prosedur_kalibrasi,
												detail.standar_kalibrasi,
												detail.suhu,
												detail.kelembaban,
												detail.flag_print,
												detail.flag_send,
												detail.supervisor_name,
												(
													CASE
													WHEN detail.no_sertifikat = ''
													OR detail.no_sertifikat IS NULL
													OR detail.no_sertifikat = '-' THEN
														'N'
													ELSE
														'Y'
													END
												) AS flag_sertifikat,
												(
													CASE
													WHEN detail.datet = ''
													OR detail.datet IS NULL
													OR detail.datet = '0000-00-00'
													OR detail.datet = '1970-01-01' THEN
														header.plan_process_date
													ELSE
														detail.datet
													END
												) AS tgl_kalibrasi
											FROM
												trans_data_details detail
											INNER JOIN trans_details header ON header.id = detail.trans_detail_id
											WHERE
												detail.flag_proses = 'Y'
											AND (
												detail.no_sertifikat = ''
												OR detail.no_sertifikat IS NULL
												OR detail.no_sertifikat = '-'
											)
											AND LOWER(header.supplier_name) = 'internal'
											AND (
												CASE
												WHEN detail.datet = ''
												OR detail.datet IS NULL
												OR detail.datet = '0000-00-00'
												OR detail.datet = '1970-01-01' THEN
													header.plan_process_date
												ELSE
													detail.datet
												END
											) < '" . $Tgl_Beda . "'";
			$records		= $this->db->query($Query_Sertf)->result_array();
		} else if ($tipe_late == 2) {
			$Tgl_Beda		= date('Y-m-d', strtotime('-9 days'));
			$Query_Sertf					= "SELECT
												detail.id,
												header.quotation_id,
												header.quotation_detail_id,
												header.quotation_nomor,
												header.quotation_date,
												header.letter_order_id,
												header.letter_order_detail_id,
												header.no_so,
												header.tgl_so,
												header.schedule_detail_id,
												header.schedule_id,
												header.schedule_nomor,
												header.schedule_date,
												header.customer_id,
												header.customer_name,
												header.tool_id,
												header.tool_name,
												header.supplier_id,
												header.supplier_name,
												1 AS qty_result,
												header.hpp,
												header.price,
												header.diskon,
												header.labs,
												header.insitu,
												header.subcon,
												header.plan_process_date,
												header.plan_time_start,
												header.plan_time_end,
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
												detail.merk,
												detail.tool_type,
												detail.no_identifikasi,
												detail.no_sertifikat,
												detail.valid_until,
												detail.file_name,
												detail.prosedur_kalibrasi,
												detail.standar_kalibrasi,
												detail.suhu,
												detail.kelembaban,
												detail.flag_print,
												detail.flag_send,
												(
													CASE
													WHEN detail.no_sertifikat = ''
													OR detail.no_sertifikat IS NULL
													OR detail.no_sertifikat = '-' THEN
														'N'
													ELSE
														'Y'
													END
												) AS flag_sertifikat,
												(
													CASE
													WHEN detail.datet = ''
													OR detail.datet IS NULL
													OR detail.datet = '0000-00-00'
													OR detail.datet = '1970-01-01' THEN
														header.plan_process_date
													ELSE
														detail.datet
													END
												) AS tgl_kalibrasi
											FROM
												trans_data_details detail
											INNER JOIN trans_details header ON header.id = detail.trans_detail_id
											WHERE
												detail.flag_proses = 'Y'
											AND (
												detail.no_sertifikat = ''
												OR detail.no_sertifikat IS NULL
												OR detail.no_sertifikat = '-'
											)
											AND LOWER(header.supplier_name) != 'internal'
											AND (
												CASE
												WHEN detail.datet = ''
												OR detail.datet IS NULL
												OR detail.datet = '0000-00-00'
												OR detail.datet = '1970-01-01' THEN
													header.plan_process_date
												ELSE
													detail.datet
												END
											) < '" . $Tgl_Beda . "'";
			$records		= $this->db->query($Query_Sertf)->result_array();
		} else if ($tipe_late === '3') {
			## LATE KIRIM SERTIFIKAT ##

			$Tgl_Beda						= date('Y-m-d', strtotime('-2 days'));
			$Query_Sertf					= "SELECT
													detail.id,
													header.quotation_id,
													header.quotation_detail_id,
													header.quotation_nomor,
													header.quotation_date,
													header.letter_order_id,
													header.letter_order_detail_id,
													header.no_so,
													header.tgl_so,
													header.schedule_detail_id,
													header.schedule_id,
													header.schedule_nomor,
													header.schedule_date,
													header.customer_id,
													header.customer_name,
													header.tool_id,
													header.tool_name,
													header.supplier_id,
													header.supplier_name,
													1 AS qty_result,
													header.hpp,
													header.price,
													header.diskon,
													header.labs,
													header.insitu,
													header.subcon,
													header.plan_process_date,
													header.plan_time_start,
													header.plan_time_end,
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
													detail.merk,
													detail.tool_type,
													detail.no_identifikasi,
													detail.no_sertifikat,
													detail.valid_until,
													detail.file_name,
													detail.prosedur_kalibrasi,
													detail.standar_kalibrasi,
													detail.suhu,
													detail.kelembaban,
													detail.flag_print,
													detail.flag_send,
													DATE_FORMAT(
														detail.modified_date,
														'%Y-%m-%d'
													) AS tgl_upload
												FROM
													trans_data_details detail
												INNER JOIN trans_details header ON header.id = detail.trans_detail_id
												WHERE
													detail.flag_proses = 'Y'
												AND NOT (
													detail.no_sertifikat = ''
													OR detail.no_sertifikat IS NULL
													OR detail.no_sertifikat = '-'
												)
												AND LOWER(header.supplier_name) = 'internal'
												AND DATE_FORMAT(
													detail.modified_date,
													'%Y-%m-%d'
												) < '" . $Tgl_Beda . "'
												AND (
													detail.flag_send = 'N'
													OR detail.flag_send = ''
													OR detail.flag_send IS NULL
												)";
			$records				= $this->db->query($Query_Sertf)->result_array();
		}

		$data			= array(
			'tipe_late'			=> $tipe_late,
			'records'		=> $records
		);

		$this->load->view('view_dashboard/export_sertifikat', $data);
	}

	public function get_sertifikat_exp()
	{
		$Tgl_Balik		= date('Y-m-d', strtotime('+1 month', strtotime(date('Y-m-d'))));
		// print_r($Tgl_Balik);
		// exit;

		$WHERE			= array(
			"valid_until <"	=> $Tgl_Balik
		);
		$results		= $this->db->get_where('view_outstanding_reminders', $WHERE)->result_array();


		$data			= array(
			'results'		=> $results
		);
		$this->load->view('view_dashboard/reminder_sertifikat', $data);
	}
}
