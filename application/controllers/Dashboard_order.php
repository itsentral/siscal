<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Dashboard_order extends CI_Controller
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
			'title'		=> 'Dashboard',
			'rows_data'	=> $records_data
		);

		$this->load->view('view_dashboard/dashboard_so', $data);
	}


	## DASHBOARD SO ##
	function getdataorder($kategori)
	{
		if ($kategori == 1) {
			$Query_Order	= "SELECT
									det_temp.*,det_temp.first_so_cust_date AS first_so_date,det_temp.noso AS no_so,det_temp.total_so AS so_total
								FROM
									temp_sales_order_value det_temp
								INNER JOIN letter_orders det_so ON det_temp.id = det_so.id
								WHERE
									det_so.sts_so NOT IN ('CNC', 'REV')
								AND det_temp.tgl_so LIKE '" . date('Y-m') . "%'";
			$records		= $this->db->query($Query_Order)->result_array();
			$Judul			= 'Laporan Sales Order ' . date('M Y');
		} else if ($kategori == 2) {
			$Query_Order	= "SELECT
									det_temp.*,det_so.reason,det_temp.first_so_cust_date AS first_so_date,det_temp.noso AS no_so,det_temp.total_so AS so_total
								FROM
									temp_sales_order_value det_temp
								INNER JOIN letter_orders det_so ON det_temp.id = det_so.id
								WHERE
									det_so.sts_so IN ('CNC')
								AND det_so.cancel_date LIKE '" . date('Y-m') . "%'";
			$records		= $this->db->query($Query_Order)->result_array();

			$Judul			= 'Laporan Cancel Sales Order' . date('M Y');
		}

		$data			= array(
			'kategori'		=> $kategori,
			'records'		=> $records,
			'Judul'			=> $Judul
		);

		$this->load->view('view_dashboard/getdataorder', $data);
	}


	function get_excelorder($kategori)
	{
		if ($kategori == 1) {
			$Query_Order	= "SELECT
									det_temp.*,det_temp.first_so_cust_date AS first_so_date,det_temp.noso AS no_so,det_temp.total_so AS so_total
								FROM
									temp_sales_order_value det_temp
								INNER JOIN letter_orders det_so ON det_temp.id = det_so.id
								WHERE
									det_so.sts_so NOT IN ('CNC', 'REV')
								AND det_temp.tgl_so LIKE '" . date('Y-m') . "%'";
			$records		= $this->db->query($Query_Order)->result_array();
			$Judul			= 'Laporan Sales Order ' . date('M Y');
		} else if ($kategori == 2) {
			$Query_Order	= "SELECT
									det_temp.*,det_so.reason,det_temp.first_so_cust_date AS first_so_date,det_temp.noso AS no_so,det_temp.total_so AS so_total
								FROM
									temp_sales_order_value det_temp
								INNER JOIN letter_orders det_so ON det_temp.id = det_so.id
								WHERE
									det_so.sts_so IN ('CNC')
								AND det_so.cancel_date LIKE '" . date('Y-m') . "%'";
			$records		= $this->db->query($Query_Order)->result_array();

			$Judul			= 'Laporan Cancel Sales Order' . date('M Y');
		}



		$data			= array(
			'kategori'		=> $kategori,
			'records'		=> $records,
			'Judul'			=> $Judul
		);

		$this->load->view('view_dashboard/get_excelorder', $data);
	}

	## END DASHBOARD SO ##


	## JSON DATA DASHBOARD
	function json_dashboard($json = 'Y')
	{
		$Arr_Return		= array();

		$Proses_Hitung = Hitung_Nilai_SO();
		## SALES ORDER ##
		$Query_Order	= "SELECT
									SUM(det_temp.total_net) AS total
								FROM
									temp_sales_order_value det_temp
								INNER JOIN letter_orders det_so ON det_temp.id = det_so.id
								WHERE
									det_so.sts_so NOT IN ('CNC', 'REV')
								AND det_temp.tgl_so LIKE '" . date('Y-m') . "%'";
		$DataOrder					=  $this->db->query($Query_Order)->result_array();
		$Nilai_Order				= round($DataOrder[0]['total'] / 1000000);
		$Arr_Return['total_order']	= $Nilai_Order;

		## SO CANCEL ##
		$Query_Order	= "SELECT
									*
								FROM
									letter_orders
								WHERE
									sts_so IN ('CNC')
								AND cancel_date LIKE '" . date('Y-m') . "%'";
		$CancelOrder	=  $this->db->query($Query_Order)->num_rows();
		$Arr_Return['total_cancel_order']	= $CancelOrder;

		if ($json == 'Y') {
			echo json_encode($Arr_Return);
		} else {
			return $Arr_Return;
		}
	}
}
