<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Update_piutang extends CI_Controller
{
	public function __construct()
	{
		parent::__construct();
		$this->load->model('master_model');
		$this->load->database();
	}

	public function index()
	{
		$current_date 	= date('Y-m-d');
		$prev_date 		= date('Y-m-d', strtotime(date('Y-m-d') . ' -1 day'));

		$prev_month 	= date("m", strtotime($prev_date));
		$prev_year 		= date('Y', strtotime($prev_date));

		$current_month 	= date("m", strtotime($current_date));
		$current_year 	= date('Y', strtotime($current_date));

		$sqlDrop = "DROP TEMPORARY TABLE IF EXISTS temp_piutang";
		$sql	= "CREATE TEMPORARY TABLE temp_piutang SELECT
							invoice_no,
							customer_id,
							customer_name,
							$current_month AS bulan, -- bulan sekarang
							$current_year AS tahun, -- tahun sekarang
							saldo_akhir AS saldo_awal,
							0 AS debet,
							0 AS kredit,
							saldo_akhir
						FROM
							account_receivables
						WHERE
							bulan = $prev_month -- bulan sebelumnya
						AND tahun = $prev_year -- tahun sebeumnya
						AND saldo_akhir <> 0 ; 
						INSERT INTO account_receivables (
							invoice_no,
							customer_id,
							customer_name,
							bulan,
							tahun,
							saldo_awal,
							debet,
							kredit,
							saldo_akhir
						) SELECT
							invoice_no,
							customer_id,
							customer_name,
							bulan,
							tahun,
							saldo_awal,
							debet,
							kredit,
							saldo_akhir
						FROM
							temp_piutang";
		// https://sentral.dutastudy.com/Siscal_Dashboard/index.php/Update_piutang
		$this->db->query($sqlDrop);
		$this->db->query($sql);
	}
}
