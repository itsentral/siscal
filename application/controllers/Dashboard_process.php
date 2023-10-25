<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Dashboard_process extends CI_Controller
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
			'title'		=> 'Dashboard Calibration Process',
			'rows_data'	=> $records_data
		);

		$this->load->view('view_dashboard/dashboard_process', $data);
	}

	## GET OTHER  DASHBOARD ## 
	function get_other_dashboard($kategori)
	{
		if ($kategori == 7) {
			## LATE SCHEDULE ##
			/*
			$Query_Data		= "SELECT
									head_quot.*,
									head_rec.id AS receive_id,
									head_rec.nomor AS receive_nomor,
									head_rec.datet AS receive_date,
									head_so.id AS code_so,
									head_so.no_so,
									DATEDIFF(
										CURRENT_DATE (),
										head_rec.datet
									) AS leadtime
								FROM
									quotation_header_receives head_rec
								INNER JOIN quotation_detail_receives det_rec ON head_rec.id = det_rec.quotation_header_receive_id
								INNER JOIN quotations head_quot ON head_rec.quotation_id = head_quot.id
								LEFT JOIN letter_orders head_so ON head_so.id = det_rec.letter_order_id
								WHERE
									DATEDIFF(
										CURRENT_DATE (),
										head_rec.datet
									) > 1
								AND (
									(det_rec.qty_so <= 0)
									OR (
										head_so.sts_so = 'OPN'
										AND det_rec.qty_so > 0
									)
								)
								GROUP BY
									head_rec.quotation_id
								ORDER BY
									head_rec.datet ASC";
			*/
			$Query_Data		= "SELECT
									det_so.*, det_quot.nomor AS quotation_nomor,
									det_quot.datet AS quotation_date,
									det_quot.podate,
									det_quot.pono,
									det_quot.po_receive,
									det_quot.pic_name,
									DATEDIFF(
										CURRENT_DATE (),
										det_so.tgl_so
									) AS leadtime
								FROM
									letter_orders det_so
								INNER JOIN quotations det_quot ON det_so.quotation_id = det_quot.id
								WHERE
									det_so.sts_so = 'OPN'
								AND DATEDIFF(
									CURRENT_DATE (),
									det_so.tgl_so
								) > 1";
			$records		= $this->db->query($Query_Data)->result_array();
		} else if ($kategori == 99) {
			$Query_Sub						= "SELECT * FROM view_schedule_incomplete";
			$records						= $this->db->query($Query_Sub)->result_array();
			// var_dump($records);
			// die();
		}


		$data			= array(
			'kategori'		=> $kategori,
			'records'		=> $records
		);

		$this->load->view('view_dashboard/get_other_dashboard', $data);
	}



	function excel_other_dashboard($kategori)
	{
		if ($kategori == 7) {
			## LATE SCHEDULE ##
			$Query_Data		= "SELECT
									det_so.*, det_quot.nomor AS quotation_nomor,
									det_quot.datet AS quotation_date,
									det_quot.podate,
									det_quot.pono,
									det_quot.po_receive,
									det_quot.pic_name,
									DATEDIFF(
										CURRENT_DATE (),
										det_so.tgl_so
									) AS leadtime
								FROM
									letter_orders det_so
								INNER JOIN quotations det_quot ON det_so.quotation_id = det_quot.id
								WHERE
									det_so.sts_so = 'OPN'
								AND DATEDIFF(
									CURRENT_DATE (),
									det_so.tgl_so
								) > 1";
			$records		= $this->db->query($Query_Data)->result_array();

			$data			= array(
				'kategori'		=> $kategori,
				'records'		=> $records
			);

			$this->load->view('view_dashboard/excel_other_dashboard', $data);
		} else if ($kategori == 99) {
			$sql = "SELECT 
		trans.id,
		trans.schedule_id,
		trans.schedule_nomor,
		trans.schedule_date,
		trans.customer_id,
		trans.customer_name,
		trans.address_so,
		trans.pic_so,
		trans.letter_order_id,
		trans.no_so,
		trans.quotation_id,
		trans.quotation_nomor,
		trans.pono,
		trans.marketing_id,
		trans.marketing_name,
		details.id as id_details,
		details.tool_name,
		details.keterangan,
		details.actual_teknisi_name
		FROM
		trans_details trans
		INNER JOIN 
		trans_data_details details
		ON 
		trans.id = details.trans_detail_id
		WHERE 
		qty_reschedule > 0
		AND 
		pro_reschedule 
		<> 
		'Y'
		AND
		plan_reschedule ='Y'
		AND 
		flag_proses = 'N' 
		AND NOT 
		( details.keterangan IS NULL OR details.keterangan = '' OR details.keterangan = '-')
		AND NOT 
		( details.actual_teknisi_name IS NULL OR details.actual_teknisi_name = '' OR details.actual_teknisi_name = '-')
		GROUP BY
		id_details
		ORDER BY
		schedule_date DESC";
			$records = $this->db->query($sql)->result_array();

			$data			= array(
				// 'kategori'		=> $kategori,
				'records'		=> $records
			);

			$this->load->view('view_dashboard/v_export_excel', $data);
		}
	}
	## END OTHER DASHBOARD ##

	## JSON DATA DASHBOARD
	function json_dashboard($json = 'Y')
	{
		$Arr_Return						= array();
		$sekarang						= date('Y-m-d');
		$Telat							= date('Y-m-d', strtotime($sekarang . ' - 2 days'));
		$Query_Cal						= "SELECT * FROM view_late_calibration_process_tools WHERE receiving_date < '" . $Telat . "'";
		$Late_Kalibrasi					= $this->db->query($Query_Cal)->num_rows();
		$Arr_Return['late_kalibrasi']	= $Late_Kalibrasi;

		/* insitu */
		$Query_Cal_insitu						= "SELECT * FROM view_late_calibration_process_tools WHERE insitu = 'Y' AND plan_process_date < '" . $Telat . "'";
		$Late_Kalibrasi_insitu					= $this->db->query($Query_Cal_insitu)->num_rows();
		$Arr_Return['late_kalibrasi_insitu']	= $Late_Kalibrasi_insitu;

		## LATE KIRIM SUBCON ##
		$Query_Sub						= "SELECT * FROM view_late_subcont_send_tools WHERE plan_subcon_send_date < '" . $sekarang . "'";
		$Late_Kirim_Subcon				= $this->db->query($Query_Sub)->num_rows();
		$Arr_Return['late_kirim_subcon'] = $Late_Kirim_Subcon;

		## LATE AMBIL SUBCON ##
		$Query_Sub						= "SELECT * FROM view_late_subcont_pick_tools WHERE plan_subcon_pick_date < '" . $sekarang . "'";
		$Late_Ambil_Subcon				= $this->db->query($Query_Sub)->num_rows();
		$Arr_Return['late_ambil_subcon'] = $Late_Ambil_Subcon;

		## LATE KIRIM CUST ##
		$Query_Sub						= "SELECT * FROM view_late_send_customer_tools WHERE plan_delivery_date < '" . $sekarang . "'";
		$Late_Kirim_Cust				= $this->db->query($Query_Sub)->num_rows();
		$Arr_Return['late_kirim_cust']	= $Late_Kirim_Cust;

		// VIEW SCHEDULE INCOMPLETE
		$Query_Sub						= "SELECT * FROM view_schedule_incomplete";
		$view_schedule				= $this->db->query($Query_Sub)->num_rows();
		$Arr_Return['view_schedule_incomplete']	= $view_schedule;

		## LATE SCHEDULE ##		
		$Query_Sub						= "SELECT
												det_so.*, det_quot.nomor AS quotation_nomor,
												det_quot.datet AS quotation_date,
												det_quot.podate,
												det_quot.pono,
												det_quot.po_receive,
												det_quot.pic_name,
												DATEDIFF(
													CURRENT_DATE (),
													det_so.tgl_so
												) AS leadtime
											FROM
												letter_orders det_so
											INNER JOIN quotations det_quot ON det_so.quotation_id = det_quot.id
											WHERE
												det_so.sts_so = 'OPN'
											AND DATEDIFF(
												CURRENT_DATE (),
												det_so.tgl_so
											) > 1";
		$Late_Schedule					= $this->db->query($Query_Sub)->num_rows();
		$Arr_Return['late_schedule']	= $Late_Schedule;

		if ($json == 'Y') {
			echo json_encode($Arr_Return);
		} else {
			return $Arr_Return;
		}
	}

	public function getlatedata($tipe)
	{
		$Tgl_Telat			= date('Y-m-d');
		$Cond				= array();
		if ($tipe == 2) {
			$Telat			= date('Y-m-d', strtotime($Tgl_Telat . ' - 2 days'));
			$Table_Name		= 'view_late_calibration_process_tools';
			$Cond			= array(
				//"plan_process_date <" => $Tgl_Telat
				// "labs" => 'Y',
				"receiving_date <" => $Telat
			);
		} else if ($tipe == 3) {
			$Table_Name		= 'view_late_subcont_send_tools';
			$Cond			= array(
				"plan_subcon_send_date <" => $Tgl_Telat
			);
		} else if ($tipe == 4) {
			$Table_Name		= 'view_late_subcont_pick_tools';
			$Cond			= array(
				"plan_subcon_pick_date <" => $Tgl_Telat
			);
		} else if ($tipe == 5) {
			$Table_Name		= 'view_late_send_customer_tools';
			$Cond			= array(
				"plan_delivery_date <" => $Tgl_Telat
			);
		} else if ($tipe == 8) {
			$Table_Name		= 'view_late_calibration_process_tools';
			$Telat			= date('Y-m-d', strtotime($Tgl_Telat . ' - 2 days'));
			$Cond			= array(
				"insitu" => 'Y',
				"plan_process_date <" => $Telat
			);
		}

		$records			= $this->db->get_where($Table_Name, $Cond)->result_array();
		$data				= array(
			'tipe'			=> $tipe,
			'records'		=> $records
		);

		$this->load->view('view_dashboard/getlatedata', $data);
	}

	public function export_excel($tipe_late)
	{
		$Tgl_Telat			= date('Y-m-d');
		if ($tipe_late == 2) {
			$Table_Name		= 'view_late_calibration_process_tools';
			$Telat			= date('Y-m-d', strtotime($Tgl_Telat . ' - 2 days'));
			$Cond			= array(
				"labs" => 'Y',
				"plan_process_date <" => $Tgl_Telat
			);
		} else if ($tipe_late == 3) {
			$Table_Name		= 'view_late_subcont_send_tools';
			$Cond			= array(
				"plan_subcon_send_date <" => $Tgl_Telat
			);
		} else if ($tipe_late == 4) {
			$Table_Name		= 'view_late_subcont_pick_tools';
			$Cond			= array(
				"plan_subcon_pick_date <" => $Tgl_Telat
			);
		} else if ($tipe_late == 5) {
			$Table_Name		= 'view_late_send_customer_tools';
			$Cond			= array(
				"plan_delivery_date <" => $Tgl_Telat
			);
		} else if ($tipe_late == 8) {
			$Table_Name		= 'view_late_calibration_process_tools';
			$Telat			= date('Y-m-d', strtotime($Tgl_Telat . ' - 2 days'));
			$Cond			= array(
				"insitu" => 'Y',
				"plan_process_date <" => $Telat
			);
		}
		$records		= $this->db->get_where($Table_Name, $Cond)->result_array();

		$data			= array(
			'tipe_late'			=> $tipe_late,
			'records'		=> $records
		);

		$this->load->view('view_dashboard/export_excel', $data);
	}
}
