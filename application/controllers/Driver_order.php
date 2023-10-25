<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Driver_order extends CI_Controller
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

		$this->folder			= 'Driver_order';
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
			'title'			=> 'DRIVER ORDER',
			'action'		=> 'index',
			'akses_menu'	=> $Arr_Akses
		);
		history('View List Driver Order');
		$this->load->view($this->folder . '/v_driver_order', $data);
	}
	function get_data_display()
	{
		$Arr_Akses		= $this->Arr_Akses;

		$WHERE			= "1=1";

		$Month			= $this->input->post('bulan');
		$Year			= $this->input->post('tahun');
		$requestData	= $_REQUEST;

		$like_value     = $requestData['search']['value'];
		$column_order   = $requestData['order'][0]['column'];
		$column_dir     = $requestData['order'][0]['dir'];
		$limit_start    = $requestData['start'];
		$limit_length   = $requestData['length'];

		$columns_order_by = array(
			0 => 'order_no',
			1 => 'plan_date',
			2 => 'company',
			3 => 'type_comp',
			4 => 'category',
			5 => 'sts_order'
		);



		if ($like_value) {
			if (!empty($WHERE)) $WHERE	.= " AND ";
			$WHERE	.= "(
						  order_no LIKE '%" . $this->db->escape_like_str($like_value) . "%'
						  OR DATE_FORMAT(plan_date, '%d %b %Y') LIKE '%" . $this->db->escape_like_str($like_value) . "%'
						  OR plan_time LIKE '%" . $this->db->escape_like_str($like_value) . "%'
						  OR company LIKE '%" . $this->db->escape_like_str($like_value) . "%'
						  OR type_comp LIKE '%" . $this->db->escape_like_str($like_value) . "%'
						  OR category LIKE '%" . $this->db->escape_like_str($like_value) . "%'
						  OR sts_order LIKE '%" . $this->db->escape_like_str($like_value) . "%'
						)";
		}

		if ($Month) {
			if (!empty($WHERE)) $WHERE .= " AND ";
			$WHERE	.= "MONTH(plan_date) = '" . $Month . "'";
		}

		if ($Year) {
			if (!empty($WHERE)) $WHERE .= " AND ";
			$WHERE	.= "YEAR(plan_date) = '" . $Year . "'";
		}

		$sql = "SELECT
					*,
					(@row:=@row+1) AS urut
				FROM
					trans_driver_orders,
				(SELECT @row:=0) r 
				WHERE " . $WHERE;
		//print_r($sql);exit();
		$fetch['totalData'] 	= $this->db->query($sql)->num_rows();
		$fetch['totalFiltered']	= $this->db->query($sql)->num_rows();



		$sql .= " ORDER BY plan_date DESC," . $columns_order_by[$column_order] . " " . $column_dir . " ";
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
		$Date_Now	= date('Y-m-d');
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

			$Code_Order		= $row['order_code'];
			$Nomor_Order	= $row['order_no'];
			$Process_Date	= $row['plan_date'];
			$Date_Order		= date('d-m-Y', strtotime($row['plan_date']));
			$Time_Order		= $row['plan_time'];
			$Code_Cust		= $row['company_code'];
			$Name_Cust		= $row['company'];
			$Type_Cust		= $row['type_comp'];
			$Type_Process	= $row['category'];
			$Addr_Cust		= $row['address'];
			$PIC_Name_Cust	= $row['pic_name'];
			$PIC_Phone_Cust	= $row['pic_phone'];
			$Status_Order	= $row['sts_order'];

			$Code_SPK		= $row['spk_driver_code'];

			$Lable_Status	= 'OPEN';
			$Color_Status	= 'bg-green';
			if ($Status_Order === 'CNC') {
				$Lable_Status	= 'CANCELED';
				$Color_Status	= 'bg-orange';
			} else if ($Status_Order === 'PRO') {
				$Lable_Status	= 'ON PROCESS';
				$Color_Status	= 'bg-blue';
			} else if ($Status_Order === 'CLS') {
				$Lable_Status	= 'CLOSE';
				$Color_Status	= 'bg-navy-active';
			}
			$Ket_Status		= '<span class="badge ' . $Color_Status . '">' . $Lable_Status . '</span>';

			if ($Type_Process === 'REC') {
				$Ket_Category	= '<span class="badge" style="background-color:#16697A !important;color:#ffffff !important;">AMBIL ALAT</span>';
			} else if ($Type_Process === 'DEL') {
				$Ket_Category	= '<span class="badge" style="background-color:#DB6400 !important;color:#ffffff !important;">KIRIM ALAT</span>';
			} else if ($Type_Process === 'INS') {
				$Ket_Category	= '<span class="badge" style="background-color:#37474f !important;color:#ffffff !important;">ANTAR TEKNISI</span>';
			}

			if ($Type_Cust === 'CUST') {
				$Ket_Comp	= '<span class="badge" style="background-color:#c2185b !important;color:#ffffff !important;">CUSTOMER</span>';
			} else {
				$Ket_Comp	= '<span class="badge" style="background-color:#0277bd !important;color:#ffffff !important;">SUBCON</span>';
			}



			$Template		= '<button type="button" class="btn btn-sm btn-primary" onClick = "ActionPreview({code:\'' . $Code_Order . '\',action :\'detail_driver_order\',title:\'VIEW DRIVER ORDER\'});" title="VIEW DRIVER ORDER"> <i class="fa fa-search"></i> </button>';
			if (($Arr_Akses['create'] == '1' || $Arr_Akses['update'] == '1') && $Status_Order === 'OPN') {
				$Template		.= '&nbsp;&nbsp;<button type="button" class="btn btn-sm btn-warning" onClick = "ActionPreview({code:\'' . $Code_Order . '\',action :\'cancel_driver_order\',title:\'CANCEL DRIVER ORDER\'});" title="CANCEL DRIVER ORDER"> <i class="fa fa-trash-o"></i> </button>';
			}

			##  MODIFIED BY ALI ~ 2023-01-27  ##
			if ($Type_Process === 'INS') {
				$pros_Bast		= $this->db->get_where('insitu_letters', array('order_code' => $Code_Order));
			} else {
				$pros_Bast		= $this->db->get_where('bast_headers', array('order_code' => $Code_Order, 'status !=' => 'CNC'));
			}

			if ($pros_Bast->num_rows() > 0 && $Arr_Akses['download'] == '1' && ($Status_Order === 'OPN' || $Status_Order === 'PRO')) {
				$Template		.= '&nbsp;&nbsp;<button type="button" class="btn btn-sm bg-navy-active" onClick = "ActionPrint({code:\'' . $Code_Order . '\',action :\'print_bast_driver_order\',title:\'PRINT BAST DRIVER ORDER\'});" title="PRINT BAST DRIVER ORDER"> <i class="fa fa-print"></i> </button>';
			}

			$OK_Reschedule	= 0;
			if ($Status_Order == 'OPN' && $Process_Date < $Date_Now) {
				$OK_Reschedule = 1;
			} else if ($Status_Order !== 'CNC' && !empty($Code_SPK)) {
				$rows_Bast		= $pros_Bast->row();
				$rows_SPK		= $this->db->get_where('spk_drivers', array('id' => $Code_SPK))->row();
				if ($rows_SPK) {
					$Date_SPK	= $rows_SPK->datet;
					if ($Date_SPK < $Date_Now) {
						if ($Type_Process === 'INS') {
							$Query_Bast_Process	= "SELECT
													det_bast.*
												FROM
													insitu_letter_details det_bast
												INNER JOIN insitu_letters head_bast ON det_bast.insitu_letter_id = head_bast.id
												WHERE
													head_bast.order_code = '" . $Code_Order . "'
												AND det_bast.flag_tech_letter = 'Y'";
						} else {
							$Query_Bast_Process	= "SELECT
													det_bast.*
												FROM
													bast_details det_bast
												INNER JOIN bast_headers head_bast ON det_bast.bast_header_id = head_bast.id
												WHERE
													head_bast.`status` NOT IN ('CNC')
												AND head_bast.order_code = '" . $Code_Order . "'
												AND (
													det_bast.qty_io > 0
													OR det_bast.qty_image > 0
												)";
						}

						$num_Bast_Process	= $this->db->query($Query_Bast_Process)->num_rows();
						if ($num_Bast_Process <= 0) {
							$OK_Reschedule = 1;
						}
					}
				}
			}

			if ($OK_Reschedule === 1 && $Arr_Akses['update'] == '1') {
				$Template		.= "&nbsp;&nbsp;<a href='" . site_url('Driver_order/reschedule_driver_order?order=' . urlencode($Code_Order)) . "' class='btn btn-sm btn-danger' title='RESCHEDULE DRIVER ORDER'> <i class='fa fa-calendar'></i> </a>";
			}


			$nestedData		= array();
			$nestedData[]	= $Nomor_Order;
			$nestedData[]	= $Date_Order;
			$nestedData[]	= $Name_Cust;
			$nestedData[]	= $Ket_Comp;
			$nestedData[]	= $Ket_Category;
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

	function detail_driver_order()
	{
		$OK_Proses	= 0;
		$rows_Header	= $rows_Detail =  array();

		if ($this->input->post()) {
			$Code_Process	= urldecode($this->input->post('code'));

			$rows_Header	= $this->db->get_where('trans_driver_orders', array('order_code' => $Code_Process))->row();
			$rows_Detail	= $this->db->get_where('trans_driver_order_details', array('order_code' => $Code_Process))->result();
		}


		$data = array(
			'title'			=> 'DRIVER ORDER PREVIEW',
			'action'		=> 'detail_driver_order',
			'akses_menu'	=> $this->Arr_Akses,
			'rows_header'	=> $rows_Header,
			'rows_detail'	=> $rows_Detail,
			'category'		=> 'view'
		);

		$this->load->view($this->folder . '/v_driver_order_preview', $data);
	}

	function cancel_driver_order()
	{
		$rows_Header	= $rows_Detail =  array();

		if ($this->input->post()) {
			$Code_Process	= urldecode($this->input->post('code'));

			$rows_Header	= $this->db->get_where('trans_driver_orders', array('order_code' => $Code_Process))->row();
			$rows_Detail	= $this->db->get_where('trans_driver_order_details', array('order_code' => $Code_Process))->result();
		}


		$data = array(
			'title'			=> 'DRIVER ORDER CANCELLATION',
			'action'		=> 'cancel_driver_order',
			'akses_menu'	=> $this->Arr_Akses,
			'rows_header'	=> $rows_Header,
			'rows_detail'	=> $rows_Detail,
			'category'		=> 'cancel'
		);

		$this->load->view($this->folder . '/v_driver_order_preview', $data);
	}


	function save_cancel_driver_order()
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

			$Find_Exist		= $this->db->get_where('trans_driver_orders', array('order_code' => $Code_Order))->row();
			if ($Find_Exist) {
				if ($Find_Exist->sts_order !== 'OPN') {
					$rows_Return	= array(
						'status'		=> 2,
						'pesan'			=> 'Data has been modified by other process...'
					);
				} else {
					$this->db->trans_begin();
					$Pesan_Error	= '';

					$Type_Comp		= $Find_Exist->type_comp;
					$Cat_Process	= $Find_Exist->category;
					$Upd_Header		= array(
						'sts_order'		=> 'CNC',
						'cancel_reason'	=> $Cancel_Reason,
						'cancel_by'		=> $Created_By,
						'cancel_date'	=> $Created_Date
					);
					$Has_Upd_Header	= $this->db->update('trans_driver_orders', $Upd_Header, array('order_code' => $Code_Order));
					if ($Has_Upd_Header !== TRUE) {
						$Pesan_Error	= 'Error Update Driver Order..';
					}
					$Table_Update		= 'Trans Tool Detail';
					if ($Type_Comp === 'CUST' && $Cat_Process === 'REC') {
						$Table_Update		= 'Quotation Detail';
					}
					$rows_Detail		= $this->db->get_where('trans_driver_order_details', array('order_code' => $Code_Order))->result();
					if ($rows_Detail) {
						foreach ($rows_Detail as $keyDetail => $valDetail) {
							$Code_Detail	= $valDetail->code_process;
							$Qty_Process	= $valDetail->qty;
							$Query_Update	= "";
							if ($Cat_Process === 'INS') {
								$Query_Update	= "UPDATE trans_details SET flag_cust_pick ='N', flag_cust_send = 'N' WHERE id = '" . $Code_Detail . "'";
							} else if ($Cat_Process === 'REC') {
								if ($Type_Comp === 'CUST') {
									$Query_Update	= "UPDATE quotation_details SET qty_driver = qty_driver - " . $Qty_Process . " WHERE id = '" . $Code_Detail . "'";
								} else {
									$Query_Update	= "UPDATE trans_details SET qty_subcon_rec = qty_subcon_rec - " . $Qty_Process . " WHERE id = '" . $Code_Detail . "'";
								}
							} else if ($Cat_Process === 'DEL') {
								if ($Type_Comp === 'CUST') {
									$Query_Update	= "UPDATE trans_details SET qty_send = qty_send - " . $Qty_Process . " WHERE id = '" . $Code_Detail . "'";
								} else {
									$Query_Update	= "UPDATE trans_details SET qty_subcon_send = qty_subcon_send - " . $Qty_Process . " WHERE id = '" . $Code_Detail . "'";
								}
							}
							if ($Query_Update) {
								$Has_Upd_Query	= $this->db->query($Query_Update);
								if ($Has_Upd_Query !== TRUE) {
									$Pesan_Error	= 'Error Update ' . $Table_Update;
								}
							}
						}
					}

					##  MODIFIED BY ALI ~ 2022-12-11  ##
					if ($Cat_Process === 'INS') {
						$rows_Bast		= $this->db->get_where('insitu_letters', array('order_code' => $Code_Order))->result();
						if ($rows_Bast) {
							foreach ($rows_Bast as $keyBast => $valBast) {
								$Code_Bast		= $valBast->id;



								## DELETE BAST INSITU ##
								$Del_Bast_Head		= "DELETE FROM insitu_letters  WHERE id ='" . $Code_Bast . "'";
								$Has_Del_Bast_Head	= $this->db->query($Del_Bast_Head);
								if ($Has_Del_Bast_Head !== TRUE) {
									$Pesan_Error	= 'Error Delete Bast Insitu Header';
								}

								$Ok_SPK_Teknisi			= 'N';
								$rows_Bast_Detail		= $this->db->get_where('insitu_letter_details', array('insitu_letter_id' => $Code_Bast))->result();
								foreach ($rows_Bast_Detail as $keyBastDet => $valBastDet) {
									$Code_BastDet		= $valBastDet->quotation_detail_id;
									if ($valBastDet->flag_tech_letter == 'Y') {
										$Ok_SPK_Teknisi			= 'Y';
									}
									$Qry_Upd_Trans		= "UPDATE trans_details SET bast_rec_id = NULL, bast_rec_no = NULL, bast_rec_date = NULL, bast_rec_by = NULL WHERE id ='" . $Code_BastDet . "' AND bast_rec_id = '" . $Code_Bast . "'";


									$Has_Upd_Trans	= $this->db->query($Qry_Upd_Trans);
									if ($Has_Upd_Trans !== true) {
										$Pesan_Error	= 'Error Update Trans Detail - BAST...';
									}

									## CHECK TRANS DATA DETAILS ##
									$Qry_CheckTrans		= "SELECT * FROM trans_data_details WHERE trans_detail_id = '" . $Code_BastDet . "' AND flag_proses IN('N','Y')";
									$rows_CheckTrans	= $this->db->query($Qry_CheckTrans)->num_rows();
									if ($rows_CheckTrans > 0) {
										$Pesan_Error	= 'Trans Data Details has been modified by other process';
									} else {
										## DELETE BAST INSITU ##
										$Del_TransDet		= "DELETE FROM trans_data_details  WHERE trans_detail_id ='" . $Code_BastDet . "'";
										$Has_Del_TransDet	= $this->db->query($Del_TransDet);
										if ($Has_Del_TransDet !== TRUE) {
											$Pesan_Error	= 'Error Delete Trans Data Details';
										}
									}
								}

								if ($Ok_SPK_Teknisi == 'Y') {
									$Pesan_Error	= 'SPK Technician has been created...';
								} else {
									$Del_InsituDet		= "DELETE FROM insitu_letter_details  WHERE insitu_letter_id ='" . $Code_Bast . "'";
									$Has_Del_InsituDet	= $this->db->query($Del_InsituDet);
									if ($Has_Del_InsituDet !== TRUE) {
										$Pesan_Error	= 'Error Delete BAST Insitu Details';
									}
								}
							}
						}
					} else {

						$rows_Bast		= $this->db->get_where('bast_headers', array('order_code' => $Code_Order, 'status !=' => 'CNC'))->result();
						if ($rows_Bast) {
							foreach ($rows_Bast as $keyBast => $valBast) {
								$Code_Bast		= $valBast->id;
								$Category		= $valBast->flag_type;
								$Type_Process	= $valBast->type_bast;

								## UPD CANCEL BAST ##
								$Upd_Bast_Head		= "UPDATE bast_headers SET status ='CNC', cancel_by = '" . $Created_By . "', cancel_date = '" . $Created_Date . "', cancel_reason = '" . $Cancel_Reason . "' WHERE id ='" . $Code_Bast . "'";
								$Has_Upd_Bast_Head	= $this->db->query($Upd_Bast_Head);
								if ($Has_Upd_Bast_Head !== TRUE) {
									$Pesan_Error	= 'Error Update Bast Header';
								}

								$rows_Bast_Detail		= $this->db->get_where('bast_details', array('bast_header_id' => $Code_Bast))->result();
								foreach ($rows_Bast_Detail as $keyBastDet => $valBastDet) {
									$Code_BastDet		= $valBastDet->quotation_detail_id;

									if ($Category == 'CUST') {
										if ($Type_Process == 'REC') {
											$Qry_Upd_Trans	= "UPDATE trans_details SET bast_rec_id = NULL, bast_rec_no = NULL, bast_rec_date = NULL, bast_rec_by = NULL WHERE id ='" . $Code_BastDet . "' AND bast_rec_id = '" . $Code_Bast . "'";
										} else {
											$Qry_Upd_Trans	= "UPDATE trans_details SET bast_send_id = NULL, bast_send_no = NULL, bast_send_date = NULL, bast_send_by = NULL WHERE id ='" . $Code_BastDet . "' AND bast_send_id = '" . $Code_Bast . "'";
										}
									} else {
										if ($Type_Process == 'REC') {
											$Qry_Upd_Trans	= "UPDATE trans_details SET subcon_bast_rec_id = NULL, subcon_bast_rec_no = NULL, subcon_bast_rec_date = NULL, subcon_bast_rec_by = NULL WHERE id ='" . $Code_BastDet . "' AND subcon_bast_rec_id = '" . $Code_Bast . "'";
										} else {
											$Qry_Upd_Trans	= "UPDATE trans_details SET subcon_bast_send_id = NULL, subcon_bast_send_no = NULL, subcon_bast_send_date = NULL, subcon_bast_send_by = NULL WHERE id ='" . $Code_BastDet . "' AND subcon_bast_send_id = '" . $Code_Bast . "'";
										}
									}

									$Has_Upd_Trans	= $this->db->query($Qry_Upd_Trans);
									if ($Has_Upd_Trans !== true) {
										$Pesan_Error	= 'Error Update Trans Detail - BAST...';
									}
								}
							}
						}
					}


					if ($this->db->trans_status() != TRUE || !empty($Pesan_Error)) {
						$this->db->trans_rollback();
						$rows_Return		= array(
							'status'		=> 2,
							'pesan'			=> 'Save Process  Failed, ' . $Pesan_Error
						);
						history('Cancellation Driver Order ' . $Code_Order . ' - ' . $Pesan_Error);
					} else {
						$this->db->trans_commit();
						$rows_Return		= array(
							'status'		=> 1,
							'pesan'			=> 'Save process success. Thank you & have a nice day......'
						);
						history('Cancellation Driver Order ' . $Code_Order);
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

	##  MODIFIED BY ALI ~ 2022-12-11  ##
	function print_bast_driver_order()
	{
		$Code_Process	= '';
		$rows_Header	=  array();
		$Flag_Insitu	= 'N';
		if ($this->input->get()) {
			$Code_Process	= urldecode($this->input->get('code'));
			$Find_Exist		= $this->db->get_where('trans_driver_orders', array('order_code' => $Code_Process))->row();
			if ($Find_Exist->category == 'INS') {
				$Flag_Insitu	= 'Y';
				$rows_Header	= $this->db->get_where('insitu_letters', array('order_code' => $Code_Process))->result_array();
			} else {
				$rows_Header	= $this->db->get_where('bast_headers', array('order_code' => $Code_Process))->result_array();
			}
		}

		//echo $Code_Process;exit;
		if ($rows_Header) {
			$data = array(
				'title'			=> 'DRIVER ORDER BAST PRINT',
				'action'		=> 'print_bast_driver_order',
				'rows_header'	=> $rows_Header,
				'printby'		=> $this->session->userdata('siscal_username'),
				'today' 		=> date("Y-m-d H:i:s"),
				'code_process'	=> $Code_Process
			);
			if ($Flag_Insitu == 'Y') {
				$this->load->view($this->folder . '/v_driver_order_print_bast_insitu', $data);
			} else {
				$this->load->view($this->folder . '/v_driver_order_print_bast', $data);
			}
		} else {
			$this->session->set_flashdata("alert_data", "<div class=\"alert alert-warning\" id=\"flash-message\">No record was found....</div>");
			redirect(site_url('Driver_order'));
		}
	}

	##  MODIFIED BY ALI ~ 2022-12-14  ##
	function reschedule_driver_order()
	{
		$rows_Header	= $rows_Detail =  array();

		if ($this->input->get()) {
			$Code_Process	= urldecode($this->input->get('order'));

			$rows_Header	= $this->db->get_where('trans_driver_orders', array('order_code' => $Code_Process))->row();
			$rows_Detail	= $this->db->get_where('trans_driver_order_details', array('order_code' => $Code_Process))->result_array();
		}


		$data = array(
			'title'			=> 'DRIVER ORDER RESCHEDULE',
			'action'		=> 'reschedule_driver_order',
			'akses_menu'	=> $this->Arr_Akses,
			'rows_header'	=> $rows_Header,
			'rows_detail'	=> $rows_Detail
		);

		$this->load->view($this->folder . '/v_driver_order_reschedule', $data);
	}

	function save_reschedule_driver_order()
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

			$Plan_Date		= date('Y-m-d', strtotime($this->input->post('plan_date')));
			$Plan_Time		= $this->input->post('plan_time');
			$Type_Process	= $this->input->post('type_process');
			$Category		= $this->input->post('category');
			$Notes			= strtoupper($this->input->post('notes'));
			$Reason			= strtoupper($this->input->post('reason'));
			$Nocust			= $this->input->post('customer_id');
			$Customer		= $this->input->post('customer_name');
			$Address		= $this->input->post('address');
			$PIC_Name		= $this->input->post('pic_name');
			$PIC_Phone		= $this->input->post('pic_phone');
			$detDetail		= $this->input->post('detDetail');

			$Code_Order		= $this->input->post('code_order');

			$Pesan_Error	= '';

			$rows_Exist		= $this->db->get_where('trans_driver_orders', array('order_code' => $Code_Order))->row();
			$OK_Process		= 1;
			$OK_New			= 1;
			if ($rows_Exist) {
				$Status_Order	= $rows_Exist->sts_order;
				$Code_SPK		= $rows_Exist->spk_driver_code;
				if ($Status_Order == 'CNC') {
					$OK_Process		= 0;
					$Pesan_Error	= 'Data has been modified by other process...';
				} else if ($Status_Order !== 'OPN' && !empty($Code_SPK)) {
					if ($rows_Exist->category == 'INS') {
						$Query_Bast_Process	= "SELECT
													det_bast.*
												FROM
													insitu_letter_details det_bast
												INNER JOIN insitu_letters head_bast ON det_bast.insitu_letter_id = head_bast.id
												WHERE
													head_bast.order_code = '" . $Code_Order . "'
												AND det_bast.flag_tech_letter = 'Y'";
					} else {
						$Query_Bast_Process	= "SELECT
													det_bast.*
												FROM
													bast_details det_bast
												INNER JOIN bast_headers head_bast ON det_bast.bast_header_id = head_bast.id
												WHERE
													head_bast.`status` NOT IN ('CNC')
												AND head_bast.order_code = '" . $Code_Order . "'
												AND (
													det_bast.qty_io > 0
													OR det_bast.qty_image > 0
												)";
					}
					$num_Bast_Process	= $this->db->query($Query_Bast_Process)->num_rows();
					if ($num_Bast_Process > 0) {
						$OK_Process		= 0;
						$Pesan_Error	= 'Data has been modified by other process...';
					}
				} else if ($Status_Order == 'OPN') {
					$OK_New			= 0;
				}
			} else {
				$OK_Process		= 0;
				$Pesan_Error	= 'Driver order not found...';
			}


			if ($OK_Process ===  0) {
				$rows_Return	= array(
					'status'		=> 2,
					'pesan'			=> $Pesan_Error
				);
			} else {
				$this->db->trans_begin();
				$Pesan_Error	= '';
				$Code_Order_New	= '';
				if ($Type_Process == 'INS') {
					$rows_Bast		= $this->db->get_where('insitu_letters', array('order_code' => $Code_Order))->result();
				} else {
					$rows_Bast		= $this->db->get_where('bast_headers', array('order_code' => $Code_Order, 'status !=' => 'CNC'))->result();
				}

				## JIKA BELUM DIBUAT SPK DRIVER ~ MAKA HANYA UPDATE TANGGAL SAJA ##
				if ($OK_New === 0) {

					if ($rows_Bast) {
						foreach ($rows_Bast as $keyBast => $valBast) {
							$Code_Bast	= $valBast->id;

							## 1. UPDATE TRANS DETAIL ##

							if ($Type_Process == 'INS') {
								$rows_Bast_Detail		= $this->db->get_where('insitu_letter_details', array('insitu_letter_id' => $Code_Bast))->result();
								foreach ($rows_Bast_Detail as $keyBastDet => $valBastDet) {
									$Code_BastDet		= $valBastDet->quotation_detail_id;
									## DELETE BAST INSITU ##
									$Upd_TransDet		= "UPDATE trans_data_details  SET datet = '" . $Plan_Date . "' WHERE trans_detail_id ='" . $Code_BastDet . "' AND (flag_proses IS NULL OR flag_proses ='-')";
									$Has_Upd_TransDet	= $this->db->query($Upd_TransDet);
									if ($Has_Upd_TransDet !== TRUE) {
										$Pesan_Error	= 'Error Update Trans Data Details';
									}
								}

								$Qry_Upd_Trans	= "UPDATE trans_details SET bast_rec_date = '" . $Plan_Date . "' WHERE bast_rec_id = '" . $Code_Bast . "'";
							} else {
								if ($Category == 'CUST') {
									if ($Type_Process == 'REC') {
										$Qry_Upd_Trans	= "UPDATE trans_details SET bast_rec_date = '" . $Plan_Date . "' WHERE bast_rec_id = '" . $Code_Bast . "'";
									} else {
										$Qry_Upd_Trans	= "UPDATE trans_details SET bast_send_date = '" . $Plan_Date . "' WHERE bast_send_id = '" . $Code_Bast . "'";
									}
								} else {
									if ($Type_Process == 'REC') {
										$Qry_Upd_Trans	= "UPDATE trans_details SET subcon_bast_rec_date = '" . $Plan_Date . "' WHERE subcon_bast_rec_id = '" . $Code_Bast . "'";
									} else {
										$Qry_Upd_Trans	= "UPDATE trans_details SET subcon_bast_send_date = '" . $Plan_Date . "' WHERE subcon_bast_send_id = '" . $Code_Bast . "'";
									}
								}
							}

							$Has_Upd_Trans	= $this->db->query($Qry_Upd_Trans);
							if ($Has_Upd_Trans !== true) {
								$Pesan_Error	= 'Error Update Trans Detail - BAST...';
							}
						}

						## 2. UPDATE BAST HEADER ##
						if ($Type_Process == 'INS') {
							$Upd_Bast_Head		= "UPDATE insitu_letters SET datet = '" . $Plan_Date . "', modified_by = '" . $Created_By . "', modified_date = '" . $Created_Date . "' WHERE order_code = '" . $Code_Order . "'";
						} else {
							$Upd_Bast_Head		= "UPDATE bast_headers SET datet = '" . $Plan_Date . "', modified_by = '" . $Created_By . "', modified_date = '" . $Created_Date . "' WHERE order_code = '" . $Code_Order . "' AND status NOT IN('CNC')";
						}
						$Has_Upd_Bast_Head	= $this->db->query($Upd_Bast_Head);
						if ($Has_Upd_Bast_Head !== true) {
							$Pesan_Error	= 'Error Update Bast Header...';
						}
					}

					## 3. UPDATE DRIVER ORDER ##

					$Upd_Driver_Order	= "UPDATE trans_driver_orders SET plan_date = '" . $Plan_Date . "', modified_by = '" . $Created_By . "', modified_date = '" . $Created_Date . "', cancel_reason = '" . $Reason . "' WHERE order_code = '" . $Code_Order . "'";

					$Has_Upd_Driver_Order	= $this->db->query($Upd_Driver_Order);
					if ($Has_Upd_Driver_Order !== true) {
						$Pesan_Error	= 'Error Update Driver Order...';
					}
				} else {
					## 1. CANCEL DRIVER ORDER - OLD ##					
					$Upd_Driver_Order	= "UPDATE trans_driver_orders SET sts_order = 'CNC', cancel_by = '" . $Created_By . "', cancel_date = '" . $Created_Date . "', cancel_reason = '" . $Reason . "' WHERE order_code = '" . $Code_Order . "'";
					$Has_Upd_Driver_Order	= $this->db->query($Upd_Driver_Order);
					if ($Has_Upd_Driver_Order !== true) {
						$Pesan_Error	= 'Error Update Driver Order - OLD...';
					}

					if ($rows_Bast) {
						## 2. CANCEL BAST HEADER - OLD ##

						if ($Type_Process == 'INS') {
							$rows_Bast		= $this->db->get_where('insitu_letters', array('order_code' => $Code_Order))->result();
							if ($rows_Bast) {
								foreach ($rows_Bast as $keyBast => $valBast) {
									$Code_Bast		= $valBast->id;



									## DELETE BAST INSITU ##
									$Del_Bast_Head		= "DELETE FROM insitu_letters  WHERE id ='" . $Code_Bast . "'";
									$Has_Del_Bast_Head	= $this->db->query($Del_Bast_Head);
									if ($Has_Del_Bast_Head !== TRUE) {
										$Pesan_Error	= 'Error Delete Bast Insitu Header';
									}

									$Ok_SPK_Teknisi			= 'N';
									$rows_Bast_Detail		= $this->db->get_where('insitu_letter_details', array('insitu_letter_id' => $Code_Bast))->result();
									foreach ($rows_Bast_Detail as $keyBastDet => $valBastDet) {
										$Code_BastDet		= $valBastDet->quotation_detail_id;
										if ($valBastDet->flag_tech_letter == 'Y') {
											$Ok_SPK_Teknisi			= 'Y';
										}


										## CHECK TRANS DATA DETAILS ##
										$Qry_CheckTrans		= "SELECT * FROM trans_data_details WHERE trans_detail_id = '" . $Code_BastDet . "' AND flag_proses IN('N','Y')";
										$rows_CheckTrans	= $this->db->query($Qry_CheckTrans)->num_rows();
										if ($rows_CheckTrans > 0) {
											$Pesan_Error	= 'Trans Data Details has been modified by other process';
										} else {
											## DELETE BAST INSITU ##
											$Del_TransDet		= "DELETE FROM trans_data_details  WHERE trans_detail_id ='" . $Code_BastDet . "'";
											$Has_Del_TransDet	= $this->db->query($Del_TransDet);
											if ($Has_Del_TransDet !== TRUE) {
												$Pesan_Error	= 'Error Delete Trans Data Details';
											}
										}
									}

									if ($Ok_SPK_Teknisi == 'Y') {
										$Pesan_Error	= 'SPK Technician has been created...';
									} else {
										$Del_InsituDet		= "DELETE FROM insitu_letter_details  WHERE insitu_letter_id ='" . $Code_Bast . "'";
										$Has_Del_InsituDet	= $this->db->query($Del_InsituDet);
										if ($Has_Del_InsituDet !== TRUE) {
											$Pesan_Error	= 'Error Delete BAST Insitu Details';
										}
									}
								}
							}
						} else {
							$Upd_Bast_Head		= "UPDATE bast_headers SET status = 'CNC', cancel_by = '" . $Created_By . "', cancel_date = '" . $Created_Date . "', cancel_reason = '" . $Reason . "' WHERE order_code = '" . $Code_Order . "' AND status NOT IN('CNC')";
							$Has_Upd_Bast_Head	= $this->db->query($Upd_Bast_Head);
							if ($Has_Upd_Bast_Head !== true) {
								$Pesan_Error	= 'Error Update Bast Header - OLD...';
							}
						}
					}

					$Code_Order_New	= 'DRV-ORD-' . date('YmdHis');
					$Urut_Order		= 1;
					$Month_Process	= date('m', strtotime($Plan_Date));
					$Year_Process	= date('Y', strtotime($Plan_Date));
					$MonthYear_Proc	= date('Y-m', strtotime($Plan_Date));

					$Query_Urut		= "SELECT
											MAX(
												CAST(
													SUBSTRING_INDEX(order_no, '/', 1) AS UNSIGNED
												)
											) AS urut
										FROM
											trans_driver_orders
										WHERE
											plan_date LIKE '" . $MonthYear_Proc . "%'";
					$rows_Urut	= $this->db->query($Query_Urut)->row();
					if ($rows_Urut) {
						$Urut_Order	= intval($rows_Urut->urut) + 1;
					}
					$Lable_Urut		= sprintf('%05d', $Urut_Order);
					if ($Urut_Order > 99999) {
						$Lable_Urut		= $Urut_Order;
					}


					$Nomor_Order		= $Lable_Urut . '/DRV-ORD/' . $Month_Process . '/' . $Year_Process;

					## 3. INSERT DRIVER ORDER - NEW ##
					$Ins_Header		= array(
						'order_code'	=> $Code_Order_New,
						'order_no'		=> $Nomor_Order,
						'datet'			=> date('Y-m-d'),
						'plan_date'		=> $Plan_Date,
						'plan_time'		=> $Plan_Time,
						'company_code'	=> $Nocust,
						'company'		=> $Customer,
						'type_comp'		=> ($Category == 'CUST') ? 'CUST' : 'SUPP',
						'category'		=> $Type_Process,
						'address'		=> $Address,
						'pic_name'		=> $PIC_Name,
						'pic_phone'		=> $PIC_Phone,
						'sts_order'		=> 'OPN',
						'notes'			=> $Notes,
						'created_by'	=> $Created_By,
						'created_date'	=> $Created_Date
					);

					$Has_Ins_Header	= $this->db->insert('trans_driver_orders', $Ins_Header);
					if ($Has_Ins_Header !== TRUE) {
						$Pesan_Error	= 'Error Insert Driver Order..';
					}


					$arr_Bast_Header	= $arr_Bast_Det	= array();
					$OK_Bast			= 0;
					if ($Type_Process !== 'INS' && ($Category == 'SUPP' || ($Category == 'CUST' && $Type_Process == 'DEL'))) {
						$OK_Bast			= 1;
						$arr_Bast_Header	= array(
							'order_code'	=> $Code_Order_New,
							'kode'			=> $Nocust,
							'name'			=> $Customer,
							'flag_type'		=> ($Category == 'CUST') ? 'CUST' : 'SUPP',
							'type_bast'		=> $Type_Process,
							'address'		=> $Address,
							'pic'			=> $PIC_Name,
							'status'		=> 'OPN',
							'notes'			=> $Notes,
							'created_by'	=> $Created_By,
							'created_date'	=> $Created_Date
						);
					} else if ($Type_Process === 'INS') {
						$OK_Bast			= 1;
						$arr_Bast_Header	= array(
							'order_code'	=> $Code_Order_New,
							'customer_id'	=> $Nocust,
							'customer_name'	=> $Customer,
							'address'		=> $Address,
							'pic'			=> $PIC_Name,
							'status'		=> 'OPN',
							'created_by'	=> $Created_By,
							'created_date'	=> $Created_Date
						);
					}

					$Ins_Upd_Quot	= array();
					if ($detDetail) {
						$intL	= 0;
						foreach ($detDetail as $keyDetail => $valDetail) {
							$Qty_Pros	= $valDetail['qty'];
							if ($Qty_Pros > 0) {
								$intL++;
								$Code_Detail	= $Code_Order_New . '-' . $intL;
								$Code_Tool		= $valDetail['tool_id'];
								$Name_Tool		= $valDetail['tool_name'];
								$Code_QuotDet	= $valDetail['code_process'];

								$Ins_Detail		= array(
									'code_detail'	=> $Code_Detail,
									'order_code'	=> $Code_Order_New,
									'code_process'	=> $Code_QuotDet,
									'tool_id'		=> $Code_Tool,
									'tool_name'		=> $Name_Tool,
									'qty'			=> $Qty_Pros
								);

								## 4. INSERT DRIVER ORDER DETAIL ~ NEW ##
								$Has_Ins_Detail	= $this->db->insert('trans_driver_order_details', $Ins_Detail);
								if ($Has_Ins_Detail !== TRUE) {
									$Pesan_Error	= 'Error Insert Driver Order Detail - NEW..';
								}



								if ($OK_Bast === 1) {
									$rows_Trans		= $this->db->get_where('trans_details', array('id' => $Code_QuotDet))->row();
									if ($rows_Trans) {
										$Code_SO		= $rows_Trans->letter_order_id;
										if ($Type_Process === 'INS') {
											$arr_Bast_Det[$Code_SO][]	= array(
												'quotation_detail_id'	=> $Code_QuotDet,
												'tool_id'				=> $Code_Tool,
												'tool_name'				=> $Name_Tool,
												'qty'					=> $Qty_Pros,
												'flag_tech_letter'		=> 'N',
												'member_id'				=> $rows_Trans->teknisi_id,
												'member_name'			=> $rows_Trans->teknisi_name
											);
										} else {
											$arr_Bast_Det[$Code_SO][]	= array(
												'quotation_detail_id'	=> $Code_QuotDet,
												'tool_id'				=> $Code_Tool,
												'tool_name'				=> $Name_Tool,
												'qty'					=> $Qty_Pros,
												'qty_io'				=> 0,
												'qty_sisa'				=> $Qty_Pros
											);
										}
									}
								}
							}
						}
					}


					if ($OK_Bast === 1) {
						if ($Type_Process === 'INS') {
							$Urut_Code_Bast	= $Urut_Nomor_Bast = 1;
							$YearMonth	= date('Y-m', strtotime($Plan_Date));
							$YM_Short	= date('Ym', strtotime($Plan_Date));
							$Year		= date('Y', strtotime($Plan_Date));
							$Year_Short	= date('y', strtotime($Plan_Date));
							$Month		= date('n', strtotime($Plan_Date));

							$romawi		= getRomawi($Month);

							$Query_Urut	= "SELECT MAX(CAST(SUBSTRING_INDEX(id, '-', -1) AS UNSIGNED)) as urut FROM insitu_letters WHERE datet LIKE '" . $Year . "-%' LIMIT 1";
							$rows_Urut	= $this->db->query($Query_Urut)->row();
							if ($rows_Urut) {
								$Urut_Code_Bast	= intval($rows_Urut->urut) + 1;
							}




							$Urut_Nomor	= 1;
							$Query_Nomor	= "SELECT MAX(CAST(SUBSTRING_INDEX(nomor, '/', 1) AS UNSIGNED)) as urut FROM insitu_letters WHERE datet LIKE '" . $YearMonth . "-%' LIMIT 1";
							$rows_Nomor	= $this->db->query($Query_Nomor)->row();
							if ($rows_Nomor) {
								$Urut_Nomor_Bast	= intval($rows_Nomor->urut) + 1;
							}

							foreach ($arr_Bast_Det as $keyLet => $valLet) {
								$Nomor_Baru	= $Urut_Nomor_Bast;
								$Urut_Code	= $Urut_Code_Bast;
								if ($Urut_Code_Bast < 100000) {
									$Urut_Code	= sprintf('%05d', $Urut_Code_Bast);
								}

								if ($Urut_Nomor_Bast < 100000) {
									$Nomor_Baru	= sprintf('%05d', $Urut_Nomor_Bast);
								}
								$Code_Bast			= 'BAST-' . $YM_Short . '-I-' . $Urut_Code;
								$Nomor_Bast			= $Nomor_Baru . '/N.BAST-I/CAL/' . $romawi . '/' . $Year_Short;

								$Ins_Bast_Header					= $arr_Bast_Header;
								$Ins_Bast_Header['id']				= $Code_Bast;
								$Ins_Bast_Header['nomor']			= $Nomor_Bast;
								$Ins_Bast_Header['datet']			= $Plan_Date;
								$Ins_Bast_Header['letter_order_id']	= $keyLet;

								$Nomor_SO			= '-';
								$Code_Quotation		= '-';
								$rows_LetterOrder	= $this->db->get_where('letter_orders', array('id' => $keyLet))->row();
								if ($rows_LetterOrder) {
									$Nomor_SO		= $rows_LetterOrder->no_so;
									$Code_Quotation	= $rows_LetterOrder->quotation_id;
								}
								$Ins_Bast_Header['quotation_id']	= $Code_Quotation;
								$intBast	= 0;
								foreach ($valLet as $keyTool => $valTool) {
									$intBast++;
									$Code_BastDetail					= $Code_Bast . '-' . sprintf('%03d', $intBast);
									$Ins_Bast_Detail					= $valTool;
									$Ins_Bast_Detail['id']				= $Code_BastDetail;
									$Ins_Bast_Detail['insitu_letter_id']	= $Code_Bast;

									$Has_Ins_Bast_Detail	= $this->db->insert('insitu_letter_details', $Ins_Bast_Detail);
									if ($Has_Ins_Bast_Detail !== true) {
										$Pesan_Error	= 'Error Insert BAST Insitu Detail...';
									}
									$Qty_process		= $valTool['qty'];
									$Code_Trans			= $valTool['quotation_detail_id'];
									$Query_Trans		= "SELECT * FROM trans_details WHERE id = '" . $Code_Trans . "'";
									$Data_Trans			= $this->db->query($Query_Trans)->row();

									## UPDATE TRANS DETAILS ##
									$Qry_Upd_Trans	= "UPDATE trans_details SET bast_rec_id = '" . $Code_Bast . "', bast_rec_no = '" . $Nomor_Bast . "', bast_rec_date = '" . $Plan_Date . "', bast_rec_by = '" . $Created_By . "' WHERE id ='" . $Code_Trans . "'";
									$Has_Upd_Trans	= $this->db->query($Qry_Upd_Trans);
									if ($Has_Upd_Trans !== true) {
										$Pesan_Error	= 'Error Update Trans Detail - BAST...';
									}

									## INSERT TRANS DATA DETAILS ##
									for ($x = 1; $x <= $Qty_process; $x++) {
										$Code_TransDet	= $Code_Trans . '-' . $x;
										$Ins_TransDet	= array(
											'id'					=> $Code_TransDet,
											'trans_detail_id'		=> $Code_Trans,
											'tool_id'				=> $Data_Trans->tool_id,
											'tool_name'				=> $Data_Trans->tool_name,
											'actual_teknisi_id'		=> $Data_Trans->teknisi_id,
											'actual_teknisi_name'	=> $Data_Trans->teknisi_name,
											'datet'					=> $Plan_Date,
											'quotation_detail_id'	=> $Data_Trans->quotation_detail_id
										);

										$Has_Ins_Trans_Det	= $this->db->insert('trans_data_details', $Ins_TransDet);
										if ($Has_Ins_Trans_Det !== true) {
											$Pesan_Error	= 'Error Insert Trans Data Detail...';
										}
									}
								}

								$Has_Ins_Bast_Header	= $this->db->insert('insitu_letters', $Ins_Bast_Header);
								if ($Has_Ins_Bast_Header !== true) {
									$Pesan_Error	= 'Error Insert BAST Insitu Header...';
								}

								$Urut_Nomor_Bast++;
								$Urut_Code_Bast++;
							}
						} else {
							$Urut_Code_Bast	= $Urut_Nomor_Bast = 1;
							$YearMonth	= date('Y-m', strtotime($Plan_Date));
							$YM_Short	= date('Ym', strtotime($Plan_Date));
							$Year		= date('Y', strtotime($Plan_Date));
							$Month		= date('n', strtotime($Plan_Date));

							$CodePros	= ($Category == 'CUST') ? 'C' : 'S';
							$TypePros	= ($Type_Process == 'REC') ? 'R' : 'S';
							$romawi		= getRomawi($Month);


							$Query_Urut	= "SELECT MAX(CAST(SUBSTRING_INDEX(id, '-', -1) AS UNSIGNED)) as urut FROM bast_headers WHERE datet LIKE '" . $YearMonth . "-%' LIMIT 1";
							$rows_Urut	= $this->db->query($Query_Urut)->result();
							if ($rows_Urut) {
								$Urut_Code_Bast	= intval($rows_Urut[0]->urut) + 1;
							}




							$Urut_Nomor	= 1;
							$Query_Nomor	= "SELECT MAX(CAST(SUBSTRING_INDEX(nomor, '/', 1) AS UNSIGNED)) as urut FROM bast_headers WHERE datet LIKE '" . $Year . "-%' AND flag_type = '" . (($Category == 'CUST') ? 'CUST' : 'SUPP') . "' AND type_bast = '" . (($Type_Process == 'REC') ? 'REC' : 'DEL') . "' LIMIT 1";
							$rows_Nomor	= $this->db->query($Query_Nomor)->result();
							if ($rows_Nomor) {
								$Urut_Nomor_Bast	= intval($rows_Nomor[0]->urut) + 1;
							}


							foreach ($arr_Bast_Det as $keyLet => $valLet) {
								$Nomor_Baru	= $Urut_Nomor_Bast;
								$Urut_Code	= $Urut_Code_Bast;
								if ($Urut_Code_Bast < 10000) {
									$Urut_Code	= sprintf('%04d', $Urut_Code_Bast);
								}

								if ($Urut_Nomor_Bast < 1000) {
									$Nomor_Baru	= sprintf('%03d', $Urut_Nomor_Bast);
								}
								$Code_Bast			= 'BAST-' . $YM_Short . '-' . sprintf('%04d', $Urut_Code_Bast);
								$Nomor_Bast			= $Nomor_Baru . '/N-BAST.' . $TypePros . '/' . $CodePros . '-' . $romawi . '/' . $Year;

								$Ins_Bast_Header					= $arr_Bast_Header;
								$Ins_Bast_Header['id']				= $Code_Bast;
								$Ins_Bast_Header['nomor']			= $Nomor_Bast;
								$Ins_Bast_Header['datet']			= $Plan_Date;
								$Ins_Bast_Header['letter_order_id']	= $keyLet;

								$Nomor_SO			= '-';
								$rows_LetterOrder	= $this->db->get_where('letter_orders', array('id' => $keyLet))->row();
								if ($rows_LetterOrder) {
									$Nomor_SO		= $rows_LetterOrder->no_so;
								}
								$Ins_Bast_Header['no_so']	= $Nomor_SO;
								$intBast	= 0;
								foreach ($valLet as $keyTool => $valTool) {
									$intBast++;
									$Code_BastDetail					= $Code_Bast . '-' . $intBast;
									$Ins_Bast_Detail					= $valTool;
									$Ins_Bast_Detail['id']				= $Code_BastDetail;
									$Ins_Bast_Detail['bast_header_id']	= $Code_Bast;

									## 5. INSERT BAST DETAIL ~ NEW ##
									$Has_Ins_Bast_Detail	= $this->db->insert('bast_details', $Ins_Bast_Detail);
									if ($Has_Ins_Bast_Detail !== true) {
										$Pesan_Error	= 'Error Insert BAST Detail...';
									}
									$Qty_process		= $valTool['qty'];
									$Code_Trans			= $valTool['quotation_detail_id'];

									$Query_Trans	= "SELECT qty_send, qty_send_real, qty_subcon_send, qty_subcon_send_real, qty_subcon_rec, qty_subcon_rec_real FROM trans_details WHERE id = '" . $Code_Trans . "'";
									$Data_Trans		= $this->db->query($Query_Trans)->result_array();

									if ($Category == 'CUST') {
										if ($Type_Process == 'REC') {
											$Upd_Trans		= "bast_rec_id = '" . $Code_Bast . "', bast_rec_no = '" . $Nomor_Bast . "', bast_rec_date = '" . $Plan_Date . "', bast_rec_by = '" . $Created_By . "'";
										} else {
											$Upd_Trans		= "bast_send_id = '" . $Code_Bast . "', bast_send_no = '" . $Nomor_Bast . "', bast_send_date = '" . $Plan_Date . "', bast_send_by = '" . $Created_By . "', qty_send_real = qty_send_real - " . $Qty_process . ", location ='Fine Good'";
										}
									} else {
										if ($Type_Process == 'REC') {
											$Upd_Trans		= "subcon_bast_rec_id = '" . $Code_Bast . "', subcon_bast_rec_no = '" . $Nomor_Bast . "', subcon_bast_rec_date = '" . $Plan_Date . "', subcon_bast_rec_by = '" . $Created_By . "'";
										} else {
											$Upd_Trans		= "subcon_bast_send_id = '" . $Code_Bast . "', subcon_bast_send_no = '" . $Nomor_Bast . "', subcon_bast_send_date = '" . $Plan_Date . "', subcon_bast_send_by = '" . $Created_By . "', qty_subcon_send_real = qty_subcon_send_real - " . $Qty_process . ", location ='Warehouse'";
										}
									}

									## 6. UPDATE TRANS DETAIL ~ NEW ##
									$Qry_Upd_Trans	= "UPDATE trans_details SET " . $Upd_Trans . " WHERE id ='" . $Code_Trans . "'";
									$Has_Upd_Trans	= $this->db->query($Qry_Upd_Trans);
									if ($Has_Upd_Trans !== true) {
										$Pesan_Error	= 'Error Update Trans Detail - BAST...';
									}
								}

								## 7. INSERT BAST HEADER ~ NEW ##
								$Has_Ins_Bast_Header	= $this->db->insert('bast_headers', $Ins_Bast_Header);
								if ($Has_Ins_Bast_Header !== true) {
									$Pesan_Error	= 'Error Insert BAST Header...';
								}

								$Urut_Nomor_Bast++;
								$Urut_Code_Bast++;
							}
						}
					}
				}

				$Text_Add	= '';
				if ($Code_Order_New) {
					$Text_Add	= ' # New Code - ' . $Code_Order_New;
				}
				if ($this->db->trans_status() != TRUE || !empty($Pesan_Error)) {
					$this->db->trans_rollback();
					$rows_Return		= array(
						'status'		=> 2,
						'pesan'			=> 'Save Process  Failed, ' . $Pesan_Error
					);
					history('Reschedule Driver Order Process - ' . $Code_Order . $Text_Add . ' - ' . $Pesan_Error);
				} else {
					$this->db->trans_commit();
					$rows_Return		= array(
						'status'		=> 1,
						'pesan'			=> 'Save process success. Thank you & have a nice day......'
					);
					history('Reschedule Driver Order Process - ' . $Code_Order . $Text_Add);
				}
			}
		}
		echo json_encode($rows_Return);
	}
}
