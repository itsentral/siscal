<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Laporan_uninvoice extends CI_Controller {	
	public function __construct() {
		parent::__construct();
		$this->load->model('master_model');
		// Your own constructor code
		
		if(!$this->session->userdata('isSISCALlogin')){
			redirect('login');
		}
		$controller			= ucfirst(strtolower($this->uri->segment(1)));
		$this->Arr_Akses	= getAcccesmenu($controller);
	}	
	public function index() {
		$Arr_Akses		= $this->Arr_Akses;
		if($Arr_Akses['read'] !='1'){
			$this->session->set_flashdata("alert_data", "<div class=\"alert alert-warning\" id=\"flash-message\">You Don't Have Right To Access This Page, Please Contact Your Administrator....</div>");
			redirect(site_url('dashboard'));
		}
		
		$data			= array(
			'action'	=>'index',
			'title'		=>'SO Outs Invoice',
			'akses_menu'=> $Arr_Akses
		);
		
		$this->load->view('Report/laporan_outs_invoice',$data);
		
	}
	
	function get_data_display(){
		
		$requestData	= $_REQUEST;
		$fetch			= $this->qry_letter(
			$requestData['search']['value'], 
			$requestData['order'][0]['column'], 
			$requestData['order'][0]['dir'], 
			$requestData['start'], $requestData['length']
		);
		$totalData		= $fetch['totalData'];
		$totalFiltered	= $fetch['totalFiltered'];
		$query			= $fetch['query'];
		
		$data		= array();
        $urut1  	= 1;
        $urut2  	= 0;
		$Bulan_Now	= date('n');
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
			$No_SO			= $row['no_so'];
			$Tgl_SO			= $row['tgl_so'];
			$Quot_ID		= $row['quotation_id'];
			$Nocust			= $row['customer_id'];
			$Customer		= $row['customer_name'];
			$Cust_fee		= ($row['success_fee'] > 0)?$row['success_fee']:0;
			$No_PO			= $row['pono'];
			$Marketing		= $row['member_name'];
			$Total_Quot		= $row['grand_tot'];
			$Net_Quot		= $row['quot_net'];
			$PPN			= $row['ppn'];
			$Insitu			= $row['total_insitu'];
			$Akomodasi		= $row['total_akomodasi'];
			$Total_After	= $row['total_dpp'];
			$Total_Alat		= $row['total_alat'];
			
			## AMBIL TGL PERTAMA SO ##
			$First_SO		='';
			$Qry_First		= "SELECT
									no_so
								FROM
									letter_orders
								WHERE
									quotation_id = '".$Quot_ID."'
								AND tgl_so <= '".$Tgl_SO."'
								AND sts_so NOT IN ('REV', 'CNC')
								ORDER BY
									tgl_so ASC
								LIMIT 1";
			$det_First		= $this->db->query($Qry_First)->result();
			if($det_First){
				$First_SO	= $det_First[0]->no_so;
			}
			
			## AMBIL JUMLAH SO ##			
			$Subcon_SO		= $row['total_subcon'];
			$Total_SO		= $row['total_so'];
			
			if($Total_Alat <= 0 || $Total_Alat == '' || $Total_Alat ==  null){
				$Qry_SUM_SO		= "SELECT
										SUM(
											ROUND(
												(
													100 -
													IF (
														quot_det.discount > 0,
														quot_det.discount,
														0
													)
												) * (det_so.qty * quot_det.price) / 100
											)
										) AS total_so,
										SUM(
											IF (
												det_so.supplier_id <> 'COMP-001',
												det_so.qty * quot_det.hpp,
												0
											)
										) AS subcon_so
									FROM
										letter_order_details det_so
									INNER JOIN quotation_details quot_det ON det_so.quotation_detail_id = quot_det.id
									WHERE
										det_so.letter_order_id = '".$row['id']."'";
				$det_SUM		= $this->db->query($Qry_SUM_SO)->result();
				if($det_SUM){
					$Subcon_SO	= $det_SUM[0]->subcon_so;
					$Total_SO	= $det_SUM[0]->total_so;
				}
			}
			
			$Insitu_SO		= $Akom_SO	=0;
			if($First_SO === $No_SO){
				if($row['flag_so_insitu'] === 'Y'){
					$Insitu_SO	= $Insitu;
				}
				
				$Akom_SO		= $Akomodasi;
			}
			
			$Total_Real_SO	= $Total_SO + $Akom_SO + $Insitu_SO;
			$PPN_SO			= 0;
			if($PPN > 0){
				$PPN_SO		= round($Total_Real_SO  * 0.1);
			}
			
			$nestedData 	= array(); 
			//$nestedData[]	= $nomor;
			$nestedData[]	= $No_SO;
			$nestedData[]	= date('d F Y',strtotime($Tgl_SO));
			$nestedData[]	= $Customer;
			$nestedData[]	= $row['quotation_nomor'];
			$nestedData[]	= $No_PO;
			$nestedData[]	= number_format($Total_SO);
			$nestedData[]	= number_format($Insitu_SO);
			$nestedData[]	= number_format($Akom_SO);
			$nestedData[]	= number_format($Total_Real_SO);
			$nestedData[]	= number_format($PPN_SO);
			$nestedData[]	= number_format($Total_Real_SO + $PPN_SO);
			$nestedData[]	= $Marketing;
			
			$data[] = $nestedData;
			$urut1++;
			$urut2++;
			
		}

		$json_data = array(
			"draw"            => intval( $requestData['draw'] ),  
			"recordsTotal"    => intval( count($data)),  
			"recordsFiltered" => intval( $totalFiltered ), 
			"data"            => $data
		);

		echo json_encode($json_data);
		
	}
	public function qry_letter($like_value = NULL, $column_order = NULL, $column_dir = NULL, $limit_start = NULL, $limit_length = NULL)
	{
		$WHERE		= "(
							detail_so.total_alat IS NULL
							OR detail_so.total_alat = ''
							OR (
								detail_so.total_alat - detail_so.total_fail
							) > 0
						)";
		if($like_value){
			if(!empty($WHERE))$WHERE	.=" AND ";
			$WHERE	.="(
						detail_so.no_so LIKE '%".$this->db->escape_like_str($like_value)."%'
						OR detail_so.tgl_so LIKE '%".$this->db->escape_like_str($like_value)."%'
						OR detail_so.customer_name LIKE '%".$this->db->escape_like_str($like_value)."%'
						OR detail_so.quotation_nomor LIKE '%".$this->db->escape_like_str($like_value)."%'
						OR detail_so.pono LIKE '%".$this->db->escape_like_str($like_value)."%'
						OR detail_so.member_name LIKE '%".$this->db->escape_like_str($like_value)."%'
						)";
		}
		
		

		$sql = "
			SELECT
				(@ROW :=@ROW + 1) AS nomor,
				detail_so.*
			FROM
				(
					SELECT
						det_so.*, det_quot.nomor AS quotation_nomor,
						det_quot.datet AS quotation_date,
						det_quot.pono,
						det_quot.podate,
						det_quot.total_dpp,
						det_quot.grand_tot,
						det_quot.ppn,
						det_quot.total_akomodasi,
						det_quot.total_insitu,
						det_quot.member_id,
						det_quot.member_name,
						(
							det_quot.grand_tot - det_quot.ppn - det_quot.total_akomodasi - det_quot.total_insitu
						) AS quot_net,
						det_quot.success_fee,
						xb.total_alat,
						xb.total_fail,
						xb.total_so,
						xb.total_subcon
					FROM
						letter_orders det_so
					LEFT JOIN quotations det_quot ON det_so.quotation_id = det_quot.id
					LEFT JOIN (
						SELECT
							trans_details.letter_order_id AS letter_order_id,
							SUM(
								(
									(
										CASE
										WHEN trans_details.re_schedule <> 'Y' THEN
											trans_details.qty - trans_details.re_qty
										ELSE
											0
										END
									) - trans_details.qty_fail
								) * trans_details.price * (
									(
										100 -
										IF (
											trans_details.diskon > 0,
											trans_details.diskon,
											0
										)
									) / 100
								)
							) AS total_so,
							(
								CASE
								WHEN trans_details.supplier_id <> 'COMP-001' THEN
									(
										(
											CASE
											WHEN trans_details.re_schedule <> 'Y' THEN
												trans_details.qty - trans_details.re_qty
											ELSE
												0
											END
										) - trans_details.qty_fail
									) * trans_details.price * (
										(
											100 -
											IF (
												trans_details.diskon > 0,
												trans_details.diskon,
												0
											)
										) / 100
									)
								ELSE
									0
								END
							) AS total_subcon,
							SUM(trans_details.qty_fail) AS total_fail,
							SUM(
								(
									CASE
									WHEN trans_details.re_schedule <> 'Y' THEN
										trans_details.qty - trans_details.re_qty
									ELSE
										0
									END
								)
							) AS total_alat
						FROM
							trans_details
						INNER JOIN letter_orders ON trans_details.letter_order_id = letter_orders.id
						WHERE
							letter_orders.flag_invoice = 'N'
						GROUP BY
							trans_details.letter_order_id
					) AS xb ON xb.letter_order_id = det_so.id
					WHERE
						(
							det_so.flag_invoice IS NULL
							OR det_so.flag_invoice = ''
							OR det_so.flag_invoice = 'N'
						)
					AND det_so.sts_so NOT IN ('CNC', 'REV')
				) detail_so,
				(SELECT @ROW := 0) r
				WHERE ".$WHERE;
		//print_r($sql);exit();
		$data['totalData'] 		= $this->db->query($sql)->num_rows();
		$data['totalFiltered']	= $this->db->query($sql)->num_rows();

		$columns_order_by = array( 
			0 => 'detail_so.no_so',
			1 => 'detail_so.tgl_so' 
		);

		$sql .= " ORDER BY ".$columns_order_by[$column_order]." ".$column_dir." ";
		$sql .= " LIMIT ".$limit_start." ,".$limit_length." ";

		$data['query'] = $this->db->query($sql);
		return $data;
	}
	
	
	
	function excel_laporan_uninvoice(){
		set_time_limit(0);
		//ini_set('memory_limit','524MB');
		$Qry_Data		= "SELECT
								detail_so.*
							FROM
								(
									SELECT
										det_so.*, det_quot.nomor AS quotation_nomor,
										det_quot.datet AS quotation_date,
										det_quot.pono,
										det_quot.podate,
										det_quot.total_dpp,
										det_quot.grand_tot,
										det_quot.ppn,
										det_quot.total_akomodasi,
										det_quot.total_insitu,
										det_quot.member_id,
										det_quot.member_name,
										(
											det_quot.grand_tot - det_quot.ppn - det_quot.total_akomodasi - det_quot.total_insitu
										) AS quot_net,
										det_quot.success_fee,
										xb.total_alat,
										xb.total_fail,
										xb.total_so,
										xb.total_subcon
									FROM
										letter_orders det_so
									LEFT JOIN quotations det_quot ON det_so.quotation_id = det_quot.id
									LEFT JOIN (
										SELECT
											trans_details.letter_order_id AS letter_order_id,
											SUM(
												(
													(
														CASE
														WHEN trans_details.re_schedule <> 'Y' THEN
															trans_details.qty - trans_details.re_qty
														ELSE
															0
														END
													) - trans_details.qty_fail
												) * trans_details.price * (
													(
														100 -
														IF (
															trans_details.diskon > 0,
															trans_details.diskon,
															0
														)
													) / 100
												)
											) AS total_so,
											(
												CASE
												WHEN trans_details.supplier_id <> 'COMP-001' THEN
													(
														(
															CASE
															WHEN trans_details.re_schedule <> 'Y' THEN
																trans_details.qty - trans_details.re_qty
															ELSE
																0
															END
														) - trans_details.qty_fail
													) * trans_details.price * (
														(
															100 -
															IF (
																trans_details.diskon > 0,
																trans_details.diskon,
																0
															)
														) / 100
													)
												ELSE
													0
												END
											) AS total_subcon,
											SUM(trans_details.qty_fail) AS total_fail,
											SUM(
												(
													CASE
													WHEN trans_details.re_schedule <> 'Y' THEN
														trans_details.qty - trans_details.re_qty
													ELSE
														0
													END
												)
											) AS total_alat
										FROM
											trans_details
										INNER JOIN letter_orders ON trans_details.letter_order_id = letter_orders.id
										WHERE
											letter_orders.flag_invoice = 'N'
										GROUP BY
											trans_details.letter_order_id
									) AS xb ON xb.letter_order_id = det_so.id
									WHERE
										(
											det_so.flag_invoice IS NULL
											OR det_so.flag_invoice = ''
											OR det_so.flag_invoice = 'N'
										)
									AND det_so.sts_so NOT IN ('CNC', 'REV')
								) detail_so
								WHERE 
									(
										detail_so.total_alat IS NULL
										OR detail_so.total_alat = ''
										OR (
											detail_so.total_alat - detail_so.total_fail
										) > 0
									)";
		//echo"<pre>";print_r($Qry_Data);exit;
		$rows_Data		= $this->db->query($Qry_Data)->result_array();
		
		$data			= array(
			'action'	=>'index',
			'title'		=>'SO Outs Invoice',
			'rows_data'	=> $rows_Data
		);
		
		$this->load->view('Report/laporan_outs_invoice_excel',$data);
	}
}
