<?php
defined('BASEPATH') or exit('No direct script access allowed');

use setasign\Fpdi\Fpdi;

class Technician_result extends CI_Controller
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

		$this->folder	= 'Hasil_kalibrasi';
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
			'title'			=> 'INDEX OF CALIBRATION RESULT',
			'action'		=> 'index',
			'akses_menu'	=> $Arr_Akses
		);
		history('View List Calibration Result');
		$this->load->view($this->folder . '/v_technician_result', $data);
	}
	function get_data_display()
	{
		$Arr_Akses			= $this->Arr_Akses;

		$DateFr				= $this->input->post('datefr');
		$DateTl				= $this->input->post('datetl');

		$WHERE				= "head_teknisi.`status` NOT IN ('CNC')";
		$Group_User			= $this->session->userdata('siscal_group_id');
		$Arr_Group			= array(1 => '6', '10', '8');
		$Member_ID			= '';
		if (@$this->session->userdata('siscal_member_id')) {
			$Member_ID	= $this->session->userdata('siscal_member_id');
		}

		if (in_array($Group_User, $Arr_Group) && !empty($Member_ID)) {
			if (!empty($WHERE)) $WHERE	.= " AND ";

			$WHERE		.= "head_teknisi.member_id = '" . $Member_ID . "'";
		}

		if (!empty($DateFr) && !empty($DateTl)) {
			if (!empty($WHERE)) $WHERE	.= " AND ";
			$WHERE	.= "(x_tool.tgl_so BETWEEN '" . $DateFr . "' AND '" . $DateTl . "')";
		}


		$Group_By		= "GROUP BY
						x_tool.letter_order_id,
						head_teknisi.member_id,
						head_teknisi.datet";

		$requestData	= $_REQUEST;

		$like_value     = $requestData['search']['value'];
		$column_order   = $requestData['order'][0]['column'];
		$column_dir     = $requestData['order'][0]['dir'];
		$limit_start    = $requestData['start'];
		$limit_length   = $requestData['length'];

		$columns_order_by = array(
			0 => 'x_tool.no_so',
			1 => 'head_teknisi.id',
			2 => 'head_teknisi.datet',
			3 => 'x_tool.customer_name',
			4 => 'head_teknisi.member_name'
		);



		if ($like_value) {
			if (!empty($WHERE)) $WHERE	.= " AND ";
			$WHERE	.= "(
						  x_tool.no_so LIKE '%" . $this->db->escape_like_str($like_value) . "%'
						  OR DATE_FORMAT(head_teknisi.datet, '%d %b %Y') LIKE '%" . $this->db->escape_like_str($like_value) . "%'
						   OR head_teknisi.id LIKE '%" . $this->db->escape_like_str($like_value) . "%'
						  OR x_tool.customer_name LIKE '%" . $this->db->escape_like_str($like_value) . "%'
						  OR head_teknisi.member_name LIKE '%" . $this->db->escape_like_str($like_value) . "%'
						 
						)";
		}


		$sql = "SELECT
					x_tool.letter_order_id,
					x_tool.no_so,
					x_tool.customer_id,
					x_tool.customer_name,
					head_teknisi.member_id,
					head_teknisi.member_name,
					head_teknisi.datet,
					head_teknisi.id,
					x_tool.tgl_so,
					x_tool.pono,
					x_tool.podate,
					x_tool.quotation_id,
					x_tool.quotation_nomor,
					x_tool.quotation_date,
					GROUP_CONCAT(x_tool.id) AS code_tool,
					(@ROW :=@ROW + 1) AS urut
				FROM
					tech_orders head_teknisi
				INNER JOIN tech_order_details det_teknisi ON head_teknisi.id = det_teknisi.tech_order_id
				INNER JOIN (
					SELECT
						head_tool.id,
						head_tool.letter_order_id,
						head_tool.no_so,
						head_tool.customer_id,
						head_tool.customer_name,
						head_tool.tgl_so,
						head_tool.pono,
						head_tool.podate,
						head_tool.quotation_id,
						head_tool.quotation_nomor,
						head_tool.quotation_date
					FROM
						trans_details head_tool
					INNER JOIN trans_data_details det_tool ON head_tool.id = det_tool.trans_detail_id
					WHERE
						(
							det_tool.flag_proses IS NULL
							OR det_tool.flag_proses = ''
							OR det_tool.flag_proses = '-'
						)
					GROUP BY
						head_tool.id
				) AS x_tool ON x_tool.id = det_teknisi.detail_id,
				 (SELECT @ROW := 0) r
				WHERE " . $WHERE . "
				" . $Group_By;
		// print_r($sql);exit();
		$fetch['totalData'] 	= $this->db->query($sql)->num_rows();
		$fetch['totalFiltered']	= $this->db->query($sql)->num_rows();



		$sql .= " ORDER BY head_teknisi.datet DESC," . $columns_order_by[$column_order] . " " . $column_dir . " ";
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
			$Code_SO		= $row['letter_order_id'];
			$Code_Teknisi	= $row['member_id'];
			$Date_Teknisi	= $row['datet'];

			$Code_Alat		= str_replace(',', '^', $row['code_tool']);
			//$Nomor_SPK		= $row['id'];

			$Code_Unik		= $Code_SO . '^' . $Code_Teknisi . '^' . $Date_Teknisi;
			$nestedData		= array();
			$intL			= 0;
			foreach ($columns_order_by as $keyI => $valI) {
				$intL++;
				$Pecah_Kode		= explode('.', $valI);
				$Field_Cari		= $Pecah_Kode[1];
				$Nilai_Data		= $row[$Field_Cari];


				if ($intL === 3) {
					if (!empty($Nilai_Data) && $Nilai_Data !== '-') {
						$Nilai_Data	= date('d-m-Y', strtotime($Nilai_Data));
					}
				}

				$nestedData[] = $Nilai_Data;
			}

			$rows_SO		= $this->db->get_where('letter_orders', array('id' => $Code_SO))->row();
			$Flag_Insitu	= $rows_SO->flag_so_insitu;
			if ($Flag_Insitu == 'Y') {
				$Ket_Status	= '<span class="badge bg-green">ONSITE</span>';
			} else {
				$Ket_Status	= '<span class="badge bg-maroon">INLAB</span>';
			}
			$nestedData[]	= $Ket_Status;
			$Template		= '-';
			if ($Arr_Akses['create'] == '1' || $Arr_Akses['update'] == '1') {
				$Template		= "<a href='" . site_url('Technician_result/view_detail?kode=' . urlencode($Code_Unik)) . "' data-alat='" . $Code_Alat . "' class='btn btn-sm bg-navy-active' title='DETAIL SERVICE ORDER'> <i class='fa fa-search'></i> </a>";
			}
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

		// var_dump($sql);
		// die();
		// print_r($this->db->last_query());

		echo json_encode($json_data);
	}

	function view_detail()
	{
		$rows_header	= $rows_Member = $rows_Cust = array();
		$Code_Process	= '';
		$Code_Alat		= '';
		$OK_Proses      = 0;
		if ($this->input->get()) {
			$Code_Process	= urldecode($this->input->get('kode'));
			//$Code_Alat		= urldecode($this->input->get('alat'));
			$Split_Code		= explode('^', $Code_Process);
			$Code_SO		= $Split_Code[0];
			$Code_Teknisi	= $Split_Code[1];
			$Date_Teknisi	= $Split_Code[2];
			$WHERE			= "head_teknisi.`status` NOT IN ('CNC')";
			if ($Code_SO) {
				if (!empty($WHERE)) $WHERE .= " AND ";
				$WHERE .= "head_tool.letter_order_id = '" . $Code_SO . "'";
			}
			if ($Code_Teknisi) {
				if (!empty($WHERE)) $WHERE .= " AND ";
				$WHERE .= "head_teknisi.member_id = '" . $Code_Teknisi . "'";

				$rows_Member	= $this->db->get_where('members', array('id' => $Code_Teknisi))->result();
			}
			if ($Date_Teknisi) {
				if (!empty($WHERE)) $WHERE .= " AND ";
				$WHERE .= "head_teknisi.datet = '" . $Date_Teknisi . "'";
			}
			/*
			if($Code_Alat){
				if(!empty($WHERE))$WHERE .=" AND ";
				$WHERE .="head_tool.id IN('".str_replace("^","','",$Code_Alat)."')";
			}
			*/
			$Query_Data		= "SELECT
									det_tool.*,
									head_tool.letter_order_id,
									head_tool.no_so,
									head_tool.customer_id,
									head_tool.customer_name,
									head_teknisi.member_id,
									head_teknisi.member_name,
									head_teknisi.datet,
									head_tool.tgl_so,
									head_tool.pono,
									head_tool.podate,
									head_tool.quotation_id,
									head_tool.quotation_nomor,
									head_tool.quotation_date,
									head_tool.labs,
									head_tool.insitu,
									head_tool.subcon,
									head_tool.location,
									head_tool.so_descr,
									head_tool.range,
									head_tool.piece_id,
									head_tool.quotation_detail_id
							FROM
								trans_data_details det_tool
								INNER JOIN trans_details head_tool ON head_tool.id = det_tool.trans_detail_id
								INNER JOIN tech_order_details det_teknisi ON det_teknisi.detail_id = head_tool.id
								INNER JOIN tech_orders head_teknisi ON head_teknisi.id = det_teknisi.tech_order_id
							WHERE " . $WHERE;
			$rows_header	= $this->db->query($Query_Data)->result();
			// print_r($Query_Data);
			if ($rows_header) {
				$OK_Proses      	= 1;
			}

			$rows_Cust		= $this->db->get_where('customers', array('id' => $rows_header[0]->customer_id))->row();
		}

		if ($OK_Proses == 1) {
			$Arr_Akses			= $this->Arr_Akses;
			$data = array(
				'title'			=> 'DETAIL SERVICE ORDER',
				'action'		=> 'view_detail',
				'akses_menu'	=> $Arr_Akses,
				'rows_header'	=> $rows_header,
				'code_process'	=> $Code_Process,
				'rows_member'	=> $rows_Member,
				'rows_cust'		=> $rows_Cust
			);

			$this->load->view($this->folder . '/v_technician_result_detail', $data);
		} else {
			$this->session->set_flashdata("alert_data", "<div class=\"alert alert-warning\" id=\"flash-message\">No records wa found....</div>");
			redirect(site_url('Technician_result'));
		}
	}



	function calibration_result_process()
	{
		$rows_Header = $rows_Detail = $rows_Teknisi = $rows_Supplier = array();
		$Code_Back	= $Code_Teknisi = '';
		if ($this->input->post()) {
			$Code_Unik 		= urldecode($this->input->post('code'));
			$Split_Code		= explode('^', $Code_Unik);

			$Code_SO		= $Split_Code[0];
			$Code_Teknisi 	= $Split_Code[1];
			$Code_Alat		= $Split_Code[2];
			$Date_Teknisi	= $Split_Code[3];
			$rows_Detail	= $this->db->get_where('trans_data_details', array('id' => $Code_Alat))->result();
			$rows_Header	= $this->db->get_where('trans_details', array('id' => $rows_Detail[0]->trans_detail_id))->result();
			$Code_Back		= $Code_SO . '^' . $Code_Teknisi . '^' . $Date_Teknisi;

			## AMBIL DIMENTION ALAT ##
			$Query_Dimensi	= "SELECT dimention_id FROM tools WHERE id = '" . $rows_Header[0]->tool_id . "'";
			$rows_Dimensi	= $this->db->query($Query_Dimensi)->result();
			if ($rows_Dimensi) {
				$Code_Dimensi	= $rows_Dimensi[0]->dimention_id;

				## AMBIL TEKNISI SKILL ##
				$WHERE_Teknisi	= "head_member.division_id = 'DIV-002' AND head_member.status = '1' AND find_in_set('" . $Code_Dimensi . "',head_skill.dimention_id)";
				$Query_Teknisi	= "SELECT head_skill.member_id,head_skill.member_name FROM tech_skills head_skill INNER JOIN members head_member ON head_skill.member_id=head_member.id WHERE " . $WHERE_Teknisi . " GROUP BY head_member.id ORDER BY head_member.nama ASC";
				$Has_Teknisi	= $this->db->query($Query_Teknisi)->result();
				if ($Has_Teknisi) {
					foreach ($Has_Teknisi as $keyTeknisi => $valTeknisi) {
						$ID_Teknisi		= $valTeknisi->member_id;
						$Name_Teknisi	= $valTeknisi->member_name;
						$rows_Teknisi[$ID_Teknisi]	= $Name_Teknisi;
					}
					unset($Has_Teknisi);
				}
			}

			## AMBIL DATA SUPPLIER JIKA SUBCON ##
			if ($rows_Header[0]->subcon == 'Y') {
				$Query_Suppplier 	= "SELECT id, supplier FROM suppliers WHERE id <> 'COMP-001' ORDER BY supplier ASC";
				$Has_Supplier		= $this->db->query($Query_Suppplier)->result();
				if ($Has_Supplier) {
					foreach ($Has_Supplier as $keySupp => $valSupp) {
						$Code_Supp		= $valSupp->id;
						$Name_Supp		= $valSupp->supplier;
						$rows_Supplier[$Code_Supp]	= $Name_Supp;
					}
					unset($Has_Supplier);
				}
			}
		}
		$Arr_Akses			= $this->Arr_Akses;
		$data = array(
			'title'			=> 'CALIBRATION RESULT PROCESS',
			'action'		=> 'calibration_result_process',
			'akses_menu'	=> $Arr_Akses,
			'rows_detail'	=> $rows_Detail,
			'rows_header'	=> $rows_Header,
			'Code_Back'		=> $Code_Back,
			'code_teknisi'	=> $Code_Teknisi,
			'rows_teknisi'	=> $rows_Teknisi,
			'rows_supplier'	=> $rows_Supplier
		);

		$this->load->view($this->folder . '/v_technician_result_process', $data);
	}



	function save_calibration_result_process()
	{
		$rows_Return	= array(
			'status'		=> 2,
			'pesan'			=> 'No Record was found...'
		);
		if ($this->input->post()) {
			//echo"<pre>";print_r($this->input->post());exit;
			$Created_By		= $this->session->userdata('siscal_userid');
			$Created_Date	= date('Y-m-d H:i:s');
			$Reason_Fail	= '';
			$Code_Trans		= $this->input->post('code_trans');
			$Code_Detail	= $this->input->post('code_detail');
			$Code_SO		= $this->input->post('code_so');
			$Subcon			= $this->input->post('subcon');
			$Flag_Proses	= $this->input->post('flag_proses');
			$Reason_Fail	= strtoupper($this->input->post('failed_reason'));
			$Take_Image		= $this->input->post('take_image');
			$Reschedule		= 'N';

			if ($Flag_Proses == 'N') {
				$Reschedule		= $this->input->post('plan_reschedule');
			} else {
				if ($Subcon == 'Y') {
					$Code_Actual_Subcon		= $this->input->post('actual_subcon');
					$Name_Actual_Subcon		= '-';
					$rows_Supplier			= $this->db->get_where('suppliers', array('id' => $Code_Actual_Subcon))->result();
					if ($rows_Supplier) {
						$Name_Actual_Subcon	= $rows_Supplier[0]->supplier;
					}
				} else {
					$Code_Actual_Teknisi 	= $this->input->post('actual_teknisi');
					$Name_Actual_Teknisi	= '-';
					$rows_Member			= $this->db->get_where('members', array('id' => $Code_Actual_Teknisi))->result();
					if ($rows_Member) {
						$Name_Actual_Teknisi	= $rows_Member[0]->nama;
					}
				}

				$Cal_Date		= date('Y-m-d', strtotime($this->input->post('tgl_proses')));
				$Time_Start		= $this->input->post('jam_awal');
				$Time_End		= $this->input->post('jam_akhir');

				$Tool_Identify	= $this->input->post('no_identifikasi');
				$Tool_Serial	= $this->input->post('no_serial_number');
				$Tool_Merk		= strtoupper($this->input->post('merk_alat'));
				$Tool_Type		= strtoupper($this->input->post('tipe_alat'));
				$Tool_Temp		= $this->input->post('suhu');
				$Tool_Humadity	= $this->input->post('kelembaban');
				$Tool_Procedure	= $this->input->post('prosedur_kalibrasi');
				$Tool_Standard	= $this->input->post('standar_kalibrasi');
			}



			$rows_Find		= $this->db->get_where('trans_data_details', array('id' => $Code_Detail))->result();
			if ($rows_Find[0]->flag_proses === 'N' || $rows_Find[0]->flag_proses === 'Y') {
				$rows_Return	= array(
					'status'		=> 2,
					'pesan'			=> 'Data has been modified by other process...'
				);
			} else {
				$Field_Header		= "flag_process = 'Y'";

				$UPD_Detail			= array(
					'flag_proses'		=> $Flag_Proses,
					'modified_by'		=> $Created_By,
					'modified_date'		=> $Created_Date,
					'keterangan'		=> $Reason_Fail,
					'take_image'		=> $Take_Image
				);

				if ($Flag_Proses == 'N') {
					$UPD_Detail['plan_reschedule']	= $Reschedule;

					if ($Reschedule == 'Y') {
						$Field_Header	.= ", qty_reschedule = qty_reschedule + 1";
					} else {
						$Field_Header	.= ", qty_fail = qty_fail + 1, qty_sisa = qty_sisa - 1";
					}
				} else {
					$Field_Header	.= ", qty_proses = qty_proses + 1, qty_sisa = qty_sisa - 1";

					$UPD_Detail['merk']					= $Tool_Merk;
					$UPD_Detail['tool_type']			= $Tool_Type;
					$UPD_Detail['no_identifikasi']		= $Tool_Identify;
					$UPD_Detail['no_serial_number']		= $Tool_Serial;
					$UPD_Detail['datet']				= $Cal_Date;
					$UPD_Detail['start_time']			= $Time_Start;
					$UPD_Detail['end_time']				= $Time_End;
					$UPD_Detail['suhu']					= $Tool_Temp;
					$UPD_Detail['kelembaban']			= $Tool_Humadity;
					$UPD_Detail['standar_kalibrasi']	= $Tool_Standard;
					$UPD_Detail['prosedur_kalibrasi']	= $Tool_Procedure;

					if ($Subcon == 'Y') {
						$UPD_Detail['actual_subcon_id']		= $Code_Actual_Subcon;
						$UPD_Detail['actual_subcon_name']	= $Name_Actual_Subcon;
					} else {
						$UPD_Detail['actual_teknisi_id']	= $Code_Actual_Teknisi;
						$UPD_Detail['actual_teknisi_name']	= $Name_Actual_Teknisi;
					}
				}

				$Type_File			= '';
				$Data_File			= array();
				$Nama_File			= '';
				$Path_Source		= './assets/file/';
				$Path_Destination	= 'hasil_kalibrasi';

				//print_r($_SERVER['DOCUMENT_ROOT']);
				//exit;

				/* -------------------------------------------------------------
				|  UPLOAD FILE BASED ON PILIHAN FILE
				| ---------------------------------------------------------------
				*/
				$Pesan_Error		= '';
				$OK_Upload			= $OK_Selfie = 0;
				if ($_FILES && isset($_FILES['lampiran_kalibrasi']['name']) && $_FILES['lampiran_kalibrasi']['name'] != '') {
					$nama_image 	= $_FILES['lampiran_kalibrasi']['name'];
					$type_iamge		= $_FILES['lampiran_kalibrasi']['type'];
					$tmp_image 		= $_FILES['lampiran_kalibrasi']['tmp_name'];
					$error_image	= $_FILES['lampiran_kalibrasi']['error'];
					$size_image 	= $_FILES['lampiran_kalibrasi']['size'];

					$cekExtensi 	= strtolower(getExtension($nama_image));
					$Nama_File		= $Code_Detail . '.' . $cekExtensi;
					$Type_File		= $cekExtensi;


					if ($error_image == '1') {
						$OK_Proses		= 0;
						$Pesan_Error	= 'File Size Exceeds The Maximum Limit';
					} else {
						$Data_File		= array(
							'name'			=> $nama_image,
							'type'			=> $type_iamge,
							'tmp_name'		=> $tmp_image,
							'error'			=> $error_image,
							'size'			=> $size_image
						);
						$OK_Upload		= 1;
						$Delete_FTP 	= delFile_Kalibrasi($Path_Destination . $Nama_File);
						$Has_Upload 	= PdfUpload_Kalibrasi($Data_File, $Path_Destination, $Code_Detail);

						$UPD_Detail['file_kalibrasi']		= $Nama_File;
						$UPD_Detail['file_kalibrasi_tipe']	= $Type_File;
					}
				}

				# UPLOAD FILE ##
				$Code_File		= $Code_Detail;

				$Path_Loc       = $this->config->item('location_file') . 'hasil_kalibrasi/';
				$Pict_Inc_Front	= $Pic_Inc_Back = '';

				## IMAGE FRONT ##
				if ($this->input->post('pic_webcam_depan')) {

					$img_depan			= $this->input->post('pic_webcam_depan');
					$image_parts    	= explode(";base64,", $img_depan);
					$image_type_aux 	= explode("image/", $image_parts[0]);
					$image_type     	= $image_type_aux[1];
					$image_base64   	= base64_decode($image_parts[1]);
					$Pict_Inc_Front   	= "BEFORE-" . $Code_File . "." . $image_type;

					if (file_exists($Path_Loc . $Pict_Inc_Front)) {
						chmod($Path_Loc . $Pict_Inc_Front, 0777);
						unlink($Path_Loc . $Pict_Inc_Front);
					}
					file_put_contents($Path_Loc . $Pict_Inc_Front, $image_base64);

					$UPD_Detail['before_cals_image']	= $Pict_Inc_Front;
					$UPD_Detail['before_image_type']	= $image_type;
				}

				if ($_FILES && !empty($_FILES['files_depan']['name']) && $_FILES['files_depan']['name'] != '') {
					$nama_image 	= $_FILES['files_depan']['name'];
					$type_iamge		= $_FILES['files_depan']['type'];
					$tmp_image 		= $_FILES['files_depan']['tmp_name'];
					$error_image	= $_FILES['files_depan']['error'];
					$size_image 	= $_FILES['files_depan']['size'];

					$cekExtensi 	= strtolower(getExtension($nama_image));
					$Pict_Inc_Front = "BEFORE-" . $Code_File . "." . $cekExtensi;
					$Type_File		= $cekExtensi;



					if ($error_image == '1') {
						$Pesan_Error	= 'File Size Exceeds The Maximum Limit';
					} else {
						$Data_File		= array(
							'name'			=> $nama_image,
							'type'			=> $type_iamge,
							'tmp_name'		=> $tmp_image,
							'error'			=> $error_image,
							'size'			=> $size_image
						);
						$Del_Upload			= delFile_Kalibrasi('hasil_kalibrasi', $Pict_Inc_Front);
						$Has_Upload 		= ImageResizes_Kalibrasi($Data_File, 'hasil_kalibrasi', "BEFORE-" . $Code_File);

						$UPD_Detail['before_cals_image']	= $Pict_Inc_Front;
						$UPD_Detail['before_image_type']	= $Type_File;
					}
				}

				## END FRONT IMAGE ##

				## IMAGE BACK ##
				if ($this->input->post('pic_webcam_back')) {

					$img_back			= $this->input->post('pic_webcam_back');
					$image_parts    	= explode(";base64,", $img_back);
					$image_type_aux 	= explode("image/", $image_parts[0]);
					$image_type     	= $image_type_aux[1];
					$image_base64   	= base64_decode($image_parts[1]);
					$Pict_Inc_Back   	= "AFTER-" . $Code_File . "." . $image_type;

					if (file_exists($Path_Loc . $Pict_Inc_Back)) {
						chmod($Path_Loc . $Pict_Inc_Back, 0777);
						unlink($Path_Loc . $Pict_Inc_Back);
					}
					file_put_contents($Path_Loc . $Pict_Inc_Back, $image_base64);

					$UPD_Detail['after_cals_image']		= $Pict_Inc_Back;
					$UPD_Detail['after_image_type']		= $image_type;
				}

				if ($_FILES && !empty($_FILES['files_back']['name']) && $_FILES['files_back']['name'] != '') {
					$nama_image 	= $_FILES['files_back']['name'];
					$type_iamge		= $_FILES['files_back']['type'];
					$tmp_image 		= $_FILES['files_back']['tmp_name'];
					$error_image	= $_FILES['files_back']['error'];
					$size_image 	= $_FILES['files_back']['size'];

					$cekExtensi 	= strtolower(getExtension($nama_image));
					$Pict_Inc_Back  = "AFTER-" . $Code_File . "." . $cekExtensi;
					$Type_File		= $cekExtensi;



					if ($error_image == '1') {
						$Pesan_Error	= 'File Size Exceeds The Maximum Limit';
					} else {
						$Data_File		= array(
							'name'			=> $nama_image,
							'type'			=> $type_iamge,
							'tmp_name'		=> $tmp_image,
							'error'			=> $error_image,
							'size'			=> $size_image
						);
						$Del_Upload			= delFile_Kalibrasi('hasil_kalibrasi', $Pict_Inc_Back);
						$Has_Upload 		= ImageResizes_Kalibrasi($Data_File, 'hasil_kalibrasi', "AFTER-" . $Code_File);

						$UPD_Detail['after_cals_image']		= $Pict_Inc_Back;
						$UPD_Detail['after_image_type']		= $Type_File;
					}
				}
				## END BACK IMAGE ##


				$this->db->trans_begin();

				## MODIFIED BY ALI ~ 2023-01-04 ##
				$rows_Trans		= $this->db->get_where('trans_details', array('id' => $Code_Trans))->row();
				if ($Flag_Proses == 'Y') {

					if (empty($rows_Find[0]->sentral_code_tool) || $rows_Find[0]->sentral_code_tool == '-') {
						$Urut_Sentral	= 1;
						$Query_Sentral	= "SELECT
												MAX(
													CAST(
														SUBSTRING_INDEX(sentral_tool_code, '-', - 1) AS UNSIGNED
													)
												) AS urut
											FROM
												sentral_customer_tools
											WHERE
												customer_id = '" . $rows_Trans->customer_id . "'";
						$rows_Sentral	= $this->db->query($Query_Sentral)->row();
						if ($rows_Sentral) {
							$Urut_Sentral	= intval($rows_Sentral->urut) + 1;
						}

						$Lable_Sentral	= sprintf('%04d', $Urut_Sentral);
						if ($Urut_Sentral > 9999) {
							$Lable_Sentral	= $Urut_Sentral;
						}

						$Code_Sentral	= $rows_Trans->customer_id . '-CAL-' . $Lable_Sentral;

						$Ins_Sentral	= array(
							'sentral_tool_code'		=> $Code_Sentral,
							'customer_id'			=> $rows_Trans->customer_id,
							'customer_name'			=> $rows_Trans->customer_name,
							'tool_id'				=> $rows_Trans->tool_id,
							'tool_name'				=> $rows_Trans->tool_name,
							'merk'					=> $Tool_Merk,
							'tool_type'				=> $Tool_Type,
							'no_identifikasi'		=> $Tool_Identify,
							'no_serial_number'		=> $Tool_Serial,
							'descr'					=> '',
							'created_by'			=> $Created_By,
							'created_date'			=> $Created_Date
						);
						$Has_Ins_Sentral	= $this->db->insert('sentral_customer_tools', $Ins_Sentral);
						if ($Has_Ins_Sentral !== TRUE) {
							$Pesan_Error	= 'Error Insert Sentral Customer Tool';
						}

						$UPD_Detail['sentral_code_tool']	= $Code_Sentral;
					}
				}



				$Has_Upd_Trans		= $this->db->update('trans_data_details', $UPD_Detail, array('id' => $Code_Detail));
				if ($Has_Upd_Trans !== TRUE) {
					$Pesan_Error	= 'Error Update Trans Data Details';
				}

				$Qry_Upd_Header		= "UPDATE trans_details SET " . $Field_Header . " WHERE id = '" . $Code_Trans . "'";
				$Has_Ins_Log		= $this->db->query($Qry_Upd_Header);
				if ($Has_Ins_Log !== TRUE) {
					$Pesan_Error	= 'Error Update Trans Detail';
				}

				## MODIFIED BY ALI ~ 2023-02-18 ##
				if ($rows_Trans->insitu === 'Y') {
					$Code_Order			= $Code_Driver = '';
					$Query_SPK			= "SELECT
												head_insitu.order_code,
												head_insitu.spk_driver_id
											FROM
												insitu_letters head_insitu
											INNER JOIN insitu_letter_details det_insitu ON head_insitu.id=det_insitu.insitu_letter_id
											WHERE
												det_insitu.id = '" . $Code_Trans . "'										
											GROUP BY
												head_insitu.id
											ORDER BY head_insitu.datet DESC LIMIT 1";
					$rows_SPK			= $this->db->query($Query_SPK)->row();
					if ($rows_SPK) {
						$Code_Order		= $rows_SPK->order_code;
						$Code_Driver 	= $rows_SPK->spk_driver_id;

						## UPDATE SPK DRIVER TOOL ##
						$Upd_SPK_Driver_Tools	= "UPDATE spk_driver_tools SET qty_proses = qty, flag_proses = 'Y' WHERE spk_driver_id = '" . $Code_Driver . "' AND schedule_detail_id = '" . $Code_Trans . "'";
						$Has_Upd_SPK_Tool		= $this->db->query($Upd_SPK_Driver_Tools);
						if ($Has_Upd_SPK_Tool !== TRUE) {
							$Pesan_Error	= 'Error Update SPK Driver Detail Tools';
						}

						## UPDATE SPK DRIVER ##
						$Upd_SPK_Driver		= "UPDATE spk_drivers SET status = 'CLS' WHERE id = '" . $Code_Driver . "'";
						$Has_Upd_Driver		= $this->db->query($Upd_SPK_Driver);
						if ($Has_Upd_Driver !== TRUE) {
							$Pesan_Error	= 'Error Update SPK Driver Header';
						}
					}
				}

				if ($this->db->trans_status() != TRUE || !empty($Pesan_Error)) {
					$this->db->trans_rollback();
					$rows_Return		= array(
						'status'		=> 2,
						'pesan'			=> 'Save Process  Failed, please try again...'
					);
					history('Calibration Result Process Code ' . $Code_Detail . ' - ' . $Pesan_Error);
				} else {
					$this->db->trans_commit();
					$rows_Return		= array(
						'status'		=> 1,
						'pesan'			=> 'Save process success. Thank you & have a nice day......'
					);
					history('Calibration Result Process Code ' . $Code_Detail);
				}
			}
		}
		echo json_encode($rows_Return);
	}

	function take_image_before_calibration()
	{
		$rows_Header = $rows_Detail = array();
		$Code_Back	= $Code_Teknisi = '';
		if ($this->input->post()) {
			$Code_Unik 		= urldecode($this->input->post('code'));
			$Split_Code		= explode('^', $Code_Unik);

			$Code_SO		= $Split_Code[0];
			$Code_Teknisi 	= $Split_Code[1];
			$Code_Alat		= $Split_Code[2];
			$Date_Teknisi	= $Split_Code[3];
			$rows_Detail	= $this->db->get_where('trans_data_details', array('id' => $Code_Alat))->result();
			$rows_Header	= $this->db->get_where('trans_details', array('id' => $rows_Detail[0]->trans_detail_id))->result();
			$Code_Back		= $Code_SO . '^' . $Code_Teknisi . '^' . $Date_Teknisi;
		}
		$Arr_Akses			= $this->Arr_Akses;
		$data = array(
			'title'			=> 'UPLOAD IMAGE TOOL BEFORE CALIBRATION',
			'action'		=> 'take_image_before_calibration',
			'akses_menu'	=> $Arr_Akses,
			'rows_detail'	=> $rows_Detail,
			'rows_header'	=> $rows_Header,
			'Code_Back'		=> $Code_Back,
			'code_teknisi'	=> $Code_Teknisi
		);

		$this->load->view($this->folder . '/v_technician_result_upload', $data);
	}

	function save_take_image_before_calibration()
	{
		$rows_Return	= array(
			'status'		=> 2,
			'pesan'			=> 'No Record was found...'
		);
		if ($this->input->post()) {
			//echo"<pre>";print_r($this->input->post());exit;
			$Created_By		= $this->session->userdata('siscal_userid');
			$Created_Date	= date('Y-m-d H:i:s');

			$Code_Trans		= $this->input->post('code_trans');
			$Code_Detail	= $this->input->post('code_detail');
			$Code_SO		= $this->input->post('code_so');
			$Flag_Proses	= $this->input->post('take_image');



			$rows_Find		= $this->db->get_where('trans_data_details', array('id' => $Code_Detail))->result();
			if ($rows_Find[0]->flag_proses === 'N' || $rows_Find[0]->flag_proses === 'Y') {
				$rows_Return	= array(
					'status'		=> 2,
					'pesan'			=> 'Data has been modified by other process...'
				);
			} else {
				$Field_Header		= "flag_process = 'Y'";

				$UPD_Detail			= array(
					'take_image'		=> $Flag_Proses,
					'modified_by'		=> $Created_By,
					'modified_date'		=> $Created_Date
				);



				$Type_File			= '';
				$Data_File			= array();
				$Nama_File			= '';
				$Path_Source		= './assets/file/';
				$Path_Destination	= 'hasil_kalibrasi';

				//print_r($_SERVER['DOCUMENT_ROOT']);
				//exit;

				/* -------------------------------------------------------------
				|  UPLOAD FILE BASED ON PILIHAN FILE
				| ---------------------------------------------------------------
				*/
				$Pesan_Error		= '';
				$OK_Upload			= $OK_Selfie = 0;

				# UPLOAD FILE ##
				$Code_File		= $Code_Detail;

				$Path_Loc       = $this->config->item('location_file') . 'hasil_kalibrasi/';
				$Pict_Inc_Front	= $Pic_Inc_Back = '';

				## IMAGE FRONT ##
				if ($this->input->post('pic_webcam_depan')) {

					$img_depan			= $this->input->post('pic_webcam_depan');
					$image_parts    	= explode(";base64,", $img_depan);
					$image_type_aux 	= explode("image/", $image_parts[0]);
					$image_type     	= $image_type_aux[1];
					$image_base64   	= base64_decode($image_parts[1]);
					$Pict_Inc_Front   	= "BEFORE-" . $Code_File . "." . $image_type;

					if (file_exists($Path_Loc . $Pict_Inc_Front)) {
						chmod($Path_Loc . $Pict_Inc_Front, 0777);
						unlink($Path_Loc . $Pict_Inc_Front);
					}
					file_put_contents($Path_Loc . $Pict_Inc_Front, $image_base64);

					$UPD_Detail['before_cals_image']	= $Pict_Inc_Front;
					$UPD_Detail['before_image_type']	= $image_type;
				}

				if ($_FILES && !empty($_FILES['files_depan']['name']) && $_FILES['files_depan']['name'] != '') {
					$nama_image 	= $_FILES['files_depan']['name'];
					$type_iamge		= $_FILES['files_depan']['type'];
					$tmp_image 		= $_FILES['files_depan']['tmp_name'];
					$error_image	= $_FILES['files_depan']['error'];
					$size_image 	= $_FILES['files_depan']['size'];

					$cekExtensi 	= strtolower(getExtension($nama_image));
					$Pict_Inc_Front = "BEFORE-" . $Code_File . "." . $cekExtensi;
					$Type_File		= $cekExtensi;



					if ($error_image == '1') {
						$Pesan_Error	= 'File Size Exceeds The Maximum Limit';
					} else {
						$Data_File		= array(
							'name'			=> $nama_image,
							'type'			=> $type_iamge,
							'tmp_name'		=> $tmp_image,
							'error'			=> $error_image,
							'size'			=> $size_image
						);
						$Del_Upload			= delFile_Kalibrasi('hasil_kalibrasi', $Pict_Inc_Front);
						$Has_Upload 		= ImageResizes_Kalibrasi($Data_File, 'hasil_kalibrasi', "BEFORE-" . $Code_File);

						$UPD_Detail['before_cals_image']	= $Pict_Inc_Front;
						$UPD_Detail['before_image_type']	= $Type_File;
					}
				}

				## END FRONT IMAGE ##


				$this->db->trans_begin();
				## MODIFIED BY ALI ~ 2023-02-18 ##
				$rows_Trans		= $this->db->get_where('trans_details', array('id' => $Code_Trans))->row();



				$Has_Upd_Trans		= $this->db->update('trans_data_details', $UPD_Detail, array('id' => $Code_Detail));
				if ($Has_Upd_Trans !== TRUE) {
					$Pesan_Error	= 'Error Update Trans Data Details';
				}



				if ($this->db->trans_status() != TRUE || !empty($Pesan_Error)) {
					$this->db->trans_rollback();
					$rows_Return		= array(
						'status'		=> 2,
						'pesan'			=> 'Save Process  Failed, please try again...'
					);
					history('Upload Image Before Calibration Code ' . $Code_Detail . ' - ' . $Pesan_Error);
				} else {
					$this->db->trans_commit();
					$rows_Return		= array(
						'status'		=> 1,
						'pesan'			=> 'Save process success. Thank you & have a nice day......'
					);
					history('Upload Image Before Calibration Code ' . $Code_Detail);
				}
			}
		}
		echo json_encode($rows_Return);
	}

	function print_barcode_calibration_tool()
	{
		$rows_Sentral		= $rows_Tool = array();
		if ($this->input->post()) {
			$Code_Sentral	= $this->input->post('code');
			$rows_Trans		= $this->db->get_where('trans_data_details', array('id' => $Code_Sentral))->row();
			$rows_Sentral	= $this->db->get_where('sentral_customer_tools', array('sentral_tool_code' => $rows_Trans->sentral_code_tool))->row();
			$rows_Tool		= $this->db->get_where('tools', array('id' => $rows_Sentral->tool_id))->row();

			$File_QR		= $rows_Trans->qr_code;
			$Path_PDF		= $this->file_location . 'QRCode/' . $Code_Sentral . '.pdf';
			$Name_File		= 'QR-' . $Code_Sentral . '.jpg';

			if (empty($File_QR) || $File_QR == '-') {
				$HashKey		= '173ALIDYhG93b0qyJfIxfsdgfh2guVoUubW46hjwvniR200881173Gacad0FgaC9mi2008811M4ru5L1mChaeMo0';
				$CodeHash		= str_replace('=', '', enkripsi_url($rows_Trans->id));
				$Link_URL		= 'https://sentral.dutastudy.com/Siscal_CRM/index.php/CertificateGenerate/CertificateAuthorized/' . $CodeHash;
				//echo $CodeHash.' '.dekripsi_url($CodeHash);exit;
				$GenerateQRCode	= $this->GenerateQRImage($rows_Trans->id, 'QRCode', $Link_URL);

				if (file_exists($Path_PDF)) {
					unset($Path_PDF);
				}

				## GENARATE PDF ##
				$File_PDF		= $this->GenerateQRFile($Code_Sentral);
				if (file_exists($Path_PDF)) {
					chmod($Path_PDF, 0777);
				}


				$myurl 			= $Path_PDF . '[0]';
				$image 			= new Imagick();
				$image->setResolution(300, 300);
				$image->readImage($myurl);
				$image->setImageFormat("jpeg");
				$image->writeImage($this->file_location . 'QRCode/' . $Name_File);
				$image->clear();
				$image->destroy();


				## HAPUS FILE PDF ##
				if (file_exists($Path_PDF)) {
					unlink($Path_PDF);
				}

				## HAPUS FILE QR ##

				$File_Barcode	= $this->file_location . 'QRCode/' . $Code_Sentral . '.png';
				if (file_exists($File_Barcode)) {
					chmod($File_Barcode, 0777);
					unlink($File_Barcode);
				}


				$UPD_Detail		= array(
					'qr_code'	=> $Name_File
				);

				$Has_Upd_Detail	= $this->db->update('trans_data_details', $UPD_Detail, array('id' => $Code_Sentral));
				$rows_Trans		= $this->db->get_where('trans_data_details', array('id' => $Code_Sentral))->row();
			} else {
				$Name_File		= $File_QR;
			}

			$rows_Return	= array(
				'hasil'			=> 1,
				'pesan'			=> 'Berhasil',
				'path'			=> $this->file_attachement . 'QRCode/' . $Name_File
			);

			echo json_encode($rows_Return);
		}
	}

	function GenerateQRFile($Code = '')
	{
		$rows_trans		= $this->db->get_where('trans_data_details', array('id' => $Code))->row();
		$rows_header	= $this->db->get_where('sentral_customer_tools', array('sentral_tool_code' => $rows_trans->sentral_code_tool))->row();
		$rows_tool		= $this->db->get_where('tools', array('id' => $rows_header->tool_id))->row();

		$File_Path		= $this->file_location . 'QRCode/' . $Code . '.pdf';

		$sroot = $_SERVER['DOCUMENT_ROOT'];
		include $sroot . '/Siscal_mobile/application/third_party/MPDF57/mpdf.php';
		$mpdf = new mPDF('utf-8', array(29, 25));				// Create new mPDF Document
		$ArrBulan	= array(1 => 'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'Nopember', 'Desember');
		$ArrHari	= array(
			'Sun'	=> 'Minggu',
			'Mon'	=> 'Senin',
			'Tue'	=> 'Selasa',
			'Wed'	=> 'Rabu',
			'Thu'	=> 'Kamis',
			'Fri'	=> 'Jumat',
			'Sat'	=> 'Sabtu'
		);
		//Beginning Buffer to save PHP variables and HTML tags
		ob_start();
		$img_sentral	= $sroot . '/Siscal_Dashboard/assets/img/logo_flat.png';
		$img_kan		= $sroot . '/Siscal_Dashboard/assets/img/kan.png';
		//echo"<pre>";print_r($rows_header);exit;

		$HashKey		= '173ALIDYhG93b0qyJfIxfsdgfh2guVoUubW46hjwvniR200881173Gacad0FgaC9mi2008811M4ru5L1mChaeMo0';
		$CodeHash		= Enkripsi($rows_trans->id, $HashKey);
		$Link_URL		= 'https://sentral.dutastudy.com/Siscal_CRM/index.php/CertificateGenerate/CertificateAuthorized/' . $CodeHash;

?>

		<style type="text/css">
			@page {
				margin-top: 0.1cm;
				margin-left: 0.1cm;
				margin-right: 0.1cm;
				margin-bottom: 0.1cm;
			}

			.font {
				font-family: verdana, arial, sans-serif;
				font-size: 14px;
			}

			.fontheader {
				font-family: verdana, arial, sans-serif;
				font-size: 13px;
				color: #333333;
				border-width: 1px;
				border-color: #666666;
				border-collapse: collapse;
			}

			table.noborder2 th {
				font-size: 11px;
				padding: 1px;
				border-color: #666666;
			}

			table.noborder2 td {
				padding: 1px;
				border-color: #666666;
				background-color: #ffffff;
				font-size: 10px;
				font-family: verdana, arial, sans-serif;
			}

			table.noborder3 td {
				padding: 1px;
				border-color: #666666;
				background-color: #ffffff;
				font-size: 12px;
				font-family: verdana, arial, sans-serif;
			}

			table.noborder,
			.noborder2,
			noborder3 {
				font-family: verdana, arial, sans-serif;
			}

			table.noborder th {
				font-size: 9px;
				padding: 2px;
				border-color: #666666;
			}

			table.noborder td {
				padding: 1px;
				border-color: #666666;
				background-color: #ffffff;
				font-size: 9px;
				font-family: verdana, arial, sans-serif;
			}

			table.gridtable {
				font-family: verdana, arial, sans-serif;
				font-size: 10px;
				color: #333333;
				border-width: 1px;
				border-color: #666666;
				border-collapse: collapse;
			}

			table.gridtable th {
				border-width: 1px;
				padding: 5px;
				border-style: solid;
				border-color: #666666;
				background-color: #f2f2f2;

			}

			table.gridtable th.head {
				border-width: 1px;
				padding: 8px;
				border-style: solid;
				border-color: #666666;
				background-color: #7f7f7f;
				color: #ffffff;
			}

			table.gridtable td {
				border-width: 1px;
				padding: 5px;
				border-style: solid;
				border-color: #666666;
				background-color: #ffffff;
			}

			table.gridtable td zero {
				border-width: 1px;
				padding: 5px;
				border-color: #666666;
				background-color: #ffffff;

			}

			table.gridtable td.cols {
				border-width: 1px;
				padding: 5px;
				border-style: solid;
				border-color: #666666;
				background-color: #ffffff;
			}

			table.cooltabs {
				font-size: 12px;
				font-family: verdana, arial, sans-serif;
				border-width: 1px;
				border-style: solid;
			}

			table.cooltabs th.reg {
				font-family: verdana, arial, sans-serif;
				border-radius: 5px 5px 5px 5px;
				background: #e3e0e4;
				padding: 5px;
			}

			table.cooltabs td.reg {
				font-family: verdana, arial, sans-serif;
				border-radius: 5px 5px 5px 5px;
				padding: 5px;
				border-width: 1px;
			}

			#cooltabs {
				font-family: verdana, arial, sans-serif;
				border-width: 1px;
				border-style: solid;
				border-radius: 5px 5px 5px 5px;
				background: #e3e0e4;
				padding: 5px;
				width: 800px;
				height: 14px;
			}

			#cooltabs2 {
				font-family: verdana, arial, sans-serif;
				border-width: 1px;
				border-style: solid;
				border-radius: 5px 5px 5px 5px;
				background: #e3e0e4;
				padding: 5px;
				width: 180px;
				height: 10px;
			}

			#space {
				padding: 3px;
				width: 180px;
				height: 1px;
			}

			#cooltabshead {
				font-size: 12px;
				font-family: verdana, arial, sans-serif;
				border-width: 1px;
				border-style: solid;
				border-radius: 5px 5px 0 0;
				background: #dfdfdf;
				padding: 5px;
				width: 162px;
				height: 10px;
				float: left;
			}

			#cooltabschild {
				font-size: 10px;
				font-family: verdana, arial, sans-serif;
				border-width: 1px;
				border-style: solid;
				border-radius: 0 0 5px 5px;
				padding: 5px;
				width: 162px;
				height: 10px;
				float: left;
			}

			p {
				margin: 0 0 0 0;
			}

			p.pos_fixed {
				font-family: verdana, arial, sans-serif;
				position: fixed;
				top: 50px;
				left: 230px;
			}

			p.pos_fixed2 {
				font-family: verdana, arial, sans-serif;
				position: fixed;
				top: 589px;
				left: 230px;
			}

			p.notesmall {
				font-size: 9px;
			}


			.barcode {
				padding: 1.5mm;
				margin: 1.5mm;
				vertical-align: top;
				color: #000044;
			}

			.barcodecell {
				text-align: center;
				vertical-align: middle;
				position: fixed;
				top: 14px;
				right: 10px;
			}

			p.pt {
				font-family: verdana, arial, sans-serif;
				font-size: 7px;
				position: fixed;
				top: 62px;
				left: 5px;
			}

			h3.pt {
				font-family: calibri, arial, sans-serif;
				position: fixed;
				top: 175px;
				left: 250px;
			}

			h3 {
				font-family: calibri, arial, sans-serif;
				position: fixed;
				top: 65px;
				left: 200px;
			}

			h2 {
				font-family: calibri, arial, sans-serif;
				position: fixed;
				top: 95px;
				left: 280px;
			}

			p.reg {
				font-family: verdana, arial, sans-serif;
				font-size: 11px;
			}

			p.sub {
				font-family: verdana, arial, sans-serif;
				font-size: 13px;
				position: fixed;
				top: 55px;
				left: 214px;
				color: #6b6b6b;
			}

			p.header {
				font-family: verdana, arial, sans-serif;
				font-size: 11px;
				color: #330000;
			}

			p.barcs {
				font-family: verdana, arial, sans-serif;
				font-size: 11px;
				position: fixed;
				top: 13px;
				right: 1px;
			}

			p.alamat {
				font-family: verdana, arial, sans-serif;
				font-size: 7px;
				position: fixed;
				top: 71px;
				left: 5px;
			}

			p.tlp {
				font-family: verdana, arial, sans-serif;
				font-size: 7px;
				position: fixed;
				top: 80px;
				left: 5px;
			}

			p.date {
				font-family: verdana, arial, sans-serif;
				font-size: 12px;
				text-align: right;
			}

			p.foot {
				font-family: verdana, arial, sans-serif;
				font-size: 7px;
				position: fixed;
				top: 750px;
				left: 5px;
			}

			p.footer {
				font-family: verdana, arial, sans-serif;
				font-size: 10px;
				position: fixed;
				bottom: 7px;
			}

			p.ln {
				font-family: verdana, arial, sans-serif;
				font-size: 9px;
				position: fixed;
				bottom: 1px;
				left: 2px;
			}

			#hrnew {
				border: 0;
				border-bottom: 1px solid #ccc;
				background: #999;
			}

			.text-wrap {
				overflow-wrap: break-word !important;
				word-wrap: break-word !important;
				white-space: pre-wrap !important;
				word-break: break-word !important;
			}

			.text-center {
				text-align: center !important;
				vertical-align: middle !important;
			}

			.text-left {
				text-align: left !important;
				vertical-align: middle !important;
			}
		</style>
		<?php

		$Code_Trans		= $rows_trans->id;
		$Code_Serial	= $rows_trans->no_serial_number;
		$Code_Identify	= $rows_trans->no_identifikasi;
		$Text_Head		= $Code_Trans;
		if (!empty($Code_Identify) && $Code_Identify !== '-') {
			$Text_Head		= $Code_Identify;
		}

		if (!empty($Code_Serial) && $Code_Serial !== '-') {
			$Text_Head		= $Code_Serial;
		}

		$Font_Footer	= "8px";
		$Text_Footer	= date('d-m-Y', strtotime($rows_trans->datet));
		if (!empty($rows_trans->valid_until) && $rows_trans->valid_until !== '0000-00-00' && $rows_trans->valid_until !== '1970-01-01') {
			$Text_Footer	.= ' sd ' . date('d-m-Y', strtotime($rows_trans->valid_until));
			$Font_Footer	= "7px";
		}

		$rows_Image	= "";
		if (strtolower($rows_tool->certification_id) == 'kan') {
			$rows_Image	= "
			<tr>
				<td width='100%' class='text-center'>
					<img src='" . $img_kan . "' width='30' height='25'>
				</td>
			</tr>
			";
		}



		$Header	= "
		<div style='border-width: 1px;border-color: #666666;border-style: solid;'>
			<table class='noborder' width='100%' height='100%'>
				<tr>
					<td width='100%' class='text-center text-wrap'>" . $Text_Head . "</td>
				</tr>
				<tr>
					<td width='100%' class='text-center'>
						<img src='" . $this->file_location . 'QRCode/' . $Code_Trans . ".png' width='50' height='45'>
					</td>
				</tr>
				<tr>
					<td width='100%' class='text-center text-wrap' style='font-size:" . $Font_Footer . " !important;'>" . $Text_Footer . "</td>
				</tr>
			</table>	
		</div>
		";

		echo $Header;

		$html = ob_get_contents();
		ob_end_clean();
		//echo $html;exit;
		$mpdf->WriteHTML($html);
		$mpdf->Output($File_Path, 'F');
	}

	function ajax_ambil_kamera()
	{
		$kategori		= '';
		if ($this->input->get()) {
			$kategori		= $this->input->get('kategori');
		}
		$data				= array(
			'kategori'	=> $kategori
		);
		$this->load->view($this->folder . '/v_ajax_ambil_kamera', $data);
	}

	function GenerateQRImage($Nama_File = '', $Location = '', $Link_URL = '')
	{
		$sroot = $_SERVER['DOCUMENT_ROOT'];
		include $sroot . '/Siscal_Dashboard/application/libraries/phpqrcode/qrlib.php';
		//$this->load->library('phpqrcode/qrlib');

		$File_Path	= $this->file_location . $Location . '/' . $Nama_File . '.png';
		if (file_exists($File_Path)) {
			unlink($File_Path);
		}
		$rows_Trans		= $this->db->get_where('trans_data_details', array('id' => $Nama_File))->row();
		$rows_Tool		= $this->db->get_where('tools', array('id' => $rows_Trans->tool_id))->row();

		if (strtolower($rows_Tool->certification_id) == 'kan') {
			$Logo_Path	= './assets/file/' . $Location . '/cals_kan.png';
		} else {
			$Logo_Path	= './assets/file/' . $Location . '/logo2.png';
		}

		$Label_Link	= $Link_URL;
		QRcode::png($Label_Link, $File_Path, QR_ECLEVEL_L, 11.45, 0);

		$QR 		= imagecreatefrompng($File_Path);
		$logo 		= imagecreatefrompng($Logo_Path);

		$QR_width 		= imagesx($QR);
		$QR_height 		= imagesy($QR);

		$logo_width 	= imagesx($logo);
		$logo_height 	= imagesy($logo);

		// Scale logo to fit in the QR Code
		$logo_qr_width 	= $QR_width / 3;
		$scale 			= $logo_width / $logo_qr_width;
		$logo_qr_height = $logo_height / $scale;

		list($newwidth, $newheight) = getimagesize($Logo_Path);
		$out 			= imagecreatetruecolor($QR_width, $QR_width);
		imagecopyresampled($out, $QR, 0, 0, 0, 0, $QR_width, $QR_height, $QR_width, $QR_height);

		if (strtolower($rows_Tool->certification_id) == 'kan') {
			imagecopyresampled($out, $logo, $QR_width / 2.65, $QR_height / 2.65, 0, 0, $QR_width / 4, $QR_height / 5, $newwidth, $newheight);
		} else {
			imagecopyresampled($out, $logo, $QR_width / 2.65, $QR_height / 2.65, 0, 0, $QR_width / 4, $QR_height / 4, $newwidth, $newheight);
		}



		imagepng($out, $File_Path);
		imagedestroy($out);


		## Change image color ##

		$im = imagecreatefrompng($File_Path);
		$r = 44;
		$g = 62;
		$b = 80;
		for ($x = 0; $x < imagesx($im); ++$x) {
			for ($y = 0; $y < imagesy($im); ++$y) {
				$index 	= imagecolorat($im, $x, $y);
				$c   	= imagecolorsforindex($im, $index);
				if (($c['red'] < 100) && ($c['green'] < 100) && ($c['blue'] < 100)) { // dark colors
					// here we use the new color, but the original alpha channel
					$colorB = imagecolorallocatealpha($im, 0x12, 0x2E, 0x31, $c['alpha']);
					imagesetpixel($im, $x, $y, $colorB);
				}
			}
		}
		imagepng($im, $File_Path);
		imagedestroy($im);
	}


	function print_barcode_nonQR_tool()
	{
		$rows_Sentral		= $rows_Tool = array();
		if ($this->input->post()) {
			$Code_Sentral	= $this->input->post('code');
			$rows_Trans		= $this->db->get_where('trans_data_details', array('id' => $Code_Sentral))->row();
			$rows_Sentral	= $this->db->get_where('sentral_customer_tools', array('sentral_tool_code' => $rows_Trans->sentral_code_tool))->row();
			$rows_Tool		= $this->db->get_where('tools', array('id' => $rows_Sentral->tool_id))->row();

			$File_QR		= $rows_Trans->qr_code;
			$Path_PDF		= $this->file_location . 'QRCode/' . $Code_Sentral . '.pdf';
			$Name_File		= 'QR-' . $Code_Sentral . '.jpg';

			if (empty($File_QR) || $File_QR == '-') {
				$HashKey		= '173ALIDYhG93b0qyJfIxfsdgfh2guVoUubW46hjwvniR200881173Gacad0FgaC9mi2008811M4ru5L1mChaeMo0';
				$CodeHash		= str_replace('=', '', enkripsi_url($rows_Trans->id));
				$Link_URL		= 'https://sentral.dutastudy.com/Siscal_CRM/index.php/CertificateGenerate/CertificateAuthorized/' . $CodeHash;
				//echo $CodeHash.' '.dekripsi_url($CodeHash);exit;
				//$GenerateQRCode	= $this->GenerateQRImage($rows_Trans->id,'QRCode',$Link_URL);
				//echo"<br>https://sentral.dutastudy.com/T.php?q=".$rows_Trans->id;
				//echo"<br>".$CodeHash;exit;
				if (file_exists($Path_PDF)) {
					unset($Path_PDF);
				}

				## GENARATE PDF ##
				$File_PDF		= $this->GenerateNonQRFile($Code_Sentral);
				if (file_exists($Path_PDF)) {
					chmod($Path_PDF, 0777);
				}


				$myurl 			= $Path_PDF . '[0]';
				$image 			= new Imagick();
				$image->setResolution(300, 300);
				$image->readImage($myurl);
				$image->setImageFormat("jpeg");
				$image->writeImage($this->file_location . 'QRCode/' . $Name_File);
				$image->clear();
				$image->destroy();


				## HAPUS FILE PDF ##

				if (file_exists($Path_PDF)) {
					unlink($Path_PDF);
				}

				## HAPUS FILE QR ##

				$File_Barcode	= $this->file_location . 'QRCode/' . $Code_Sentral . '.png';
				if (file_exists($File_Barcode)) {
					chmod($File_Barcode, 0777);
					unlink($File_Barcode);
				}


				$UPD_Detail		= array(
					'qr_code'	=> $Name_File
				);

				$Has_Upd_Detail	= $this->db->update('trans_data_details', $UPD_Detail, array('id' => $Code_Sentral));
				$rows_Trans		= $this->db->get_where('trans_data_details', array('id' => $Code_Sentral))->row();
			} else {
				$Name_File		= $File_QR;
			}

			$rows_Return	= array(
				'hasil'			=> 1,
				'pesan'			=> 'Berhasil',
				'path'			=> $this->file_attachement . 'QRCode/' . $Name_File
			);

			echo json_encode($rows_Return);
		}
	}
	function GenerateNonQRFile($Code = '')
	{
		$rows_trans		= $this->db->get_where('trans_data_details', array('id' => $Code))->row();
		$rows_header	= $this->db->get_where('sentral_customer_tools', array('sentral_tool_code' => $rows_trans->sentral_code_tool))->row();
		$rows_tool		= $this->db->get_where('tools', array('id' => $rows_trans->tool_id))->row();
		//echo"<pre>";print_r($rows_trans);
		$File_Path		= $this->file_location . 'QRCode/' . $Code . '.pdf';

		$sroot = $_SERVER['DOCUMENT_ROOT'];
		include $sroot . '/Siscal_mobile/application/third_party/MPDF57/mpdf.php';
		$mpdf = new mPDF('utf-8', array(53, 27));				// Create new mPDF Document
		$ArrBulan	= array(1 => 'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'Nopember', 'Desember');
		$ArrHari	= array(
			'Sun'	=> 'Minggu',
			'Mon'	=> 'Senin',
			'Tue'	=> 'Selasa',
			'Wed'	=> 'Rabu',
			'Thu'	=> 'Kamis',
			'Fri'	=> 'Jumat',
			'Sat'	=> 'Sabtu'
		);
		//Beginning Buffer to save PHP variables and HTML tags
		ob_start();
		$img_sentral	= $sroot . '/Siscal_Dashboard/assets/img/logo_flat.png';
		$img_kan		= $sroot . '/Siscal_Dashboard/assets/img/kan.png';
		//echo"<pre>";print_r($rows_header);exit;

		$HashKey		= '173ALIDYhG93b0qyJfIxfsdgfh2guVoUubW46hjwvniR200881173Gacad0FgaC9mi2008811M4ru5L1mChaeMo0';
		$CodeHash		= Enkripsi($rows_trans->id, $HashKey);
		//$Link_URL		= 'https://sentral.dutastudy.com/Siscal_CRM/index.php/CertificateGenerate/CertificateAuthorized/'.$CodeHash;
		$Link_URL		= 'https://sentral-sistem.com/tool.php?q=' . $rows_trans->id;

		?>

		<style type="text/css">
			@page {
				margin-top: 0.1cm;
				margin-left: 0.1cm;
				margin-right: 0.1cm;
				margin-bottom: 0.1cm;
			}

			.font {
				font-family: verdana, arial, sans-serif;
				font-size: 14px;
			}

			.fontheader {
				font-family: verdana, arial, sans-serif;
				font-size: 13px;
				color: #333333;
				border-width: 1px;
				border-color: #666666;
				border-collapse: collapse;
			}

			table.noborder2 th {
				font-size: 11px;
				padding: 1px;
				border-color: #666666;
			}

			table.noborder2 td {
				padding: 1px;
				border-color: #666666;
				background-color: #ffffff;
				font-size: 10px;
				font-family: verdana, arial, sans-serif;
			}

			table.noborder3 td {
				padding: 1px;
				border-color: #666666;
				background-color: #ffffff;
				font-size: 12px;
				font-family: verdana, arial, sans-serif;
			}

			table.noborder,
			.noborder2,
			noborder3 {
				font-family: verdana, arial, sans-serif;
			}

			table.noborder th {
				font-size: 9px;
				padding: 2px;
				border-color: #666666;
			}

			table.noborder td {
				padding: 1px;
				border-color: #666666;
				background-color: #ffffff;
				font-size: 9px;
				font-family: verdana, arial, sans-serif;
			}

			table.gridtable {
				font-family: verdana, arial, sans-serif;
				font-size: 10px;
				color: #333333;
				border-width: 1px;
				border-color: #666666;
				border-collapse: collapse;
			}

			table.gridtable th {
				border-width: 1px;
				padding: 5px;
				border-style: solid;
				border-color: #666666;
				background-color: #f2f2f2;

			}

			table.gridtable th.head {
				border-width: 1px;
				padding: 8px;
				border-style: solid;
				border-color: #666666;
				background-color: #7f7f7f;
				color: #ffffff;
			}

			table.gridtable td {
				border-width: 1px;
				padding: 5px;
				border-style: solid;
				border-color: #666666;
				background-color: #ffffff;
			}

			table.gridtable td zero {
				border-width: 1px;
				padding: 5px;
				border-color: #666666;
				background-color: #ffffff;

			}

			table.gridtable td.cols {
				border-width: 1px;
				padding: 5px;
				border-style: solid;
				border-color: #666666;
				background-color: #ffffff;
			}

			table.cooltabs {
				font-size: 12px;
				font-family: verdana, arial, sans-serif;
				border-width: 1px;
				border-style: solid;
			}

			table.cooltabs th.reg {
				font-family: verdana, arial, sans-serif;
				border-radius: 5px 5px 5px 5px;
				background: #e3e0e4;
				padding: 5px;
			}

			table.cooltabs td.reg {
				font-family: verdana, arial, sans-serif;
				border-radius: 5px 5px 5px 5px;
				padding: 5px;
				border-width: 1px;
			}

			#cooltabs {
				font-family: verdana, arial, sans-serif;
				border-width: 1px;
				border-style: solid;
				border-radius: 5px 5px 5px 5px;
				background: #e3e0e4;
				padding: 5px;
				width: 800px;
				height: 14px;
			}

			#cooltabs2 {
				font-family: verdana, arial, sans-serif;
				border-width: 1px;
				border-style: solid;
				border-radius: 5px 5px 5px 5px;
				background: #e3e0e4;
				padding: 5px;
				width: 180px;
				height: 10px;
			}

			#space {
				padding: 3px;
				width: 180px;
				height: 1px;
			}

			#cooltabshead {
				font-size: 12px;
				font-family: verdana, arial, sans-serif;
				border-width: 1px;
				border-style: solid;
				border-radius: 5px 5px 0 0;
				background: #dfdfdf;
				padding: 5px;
				width: 162px;
				height: 10px;
				float: left;
			}

			#cooltabschild {
				font-size: 10px;
				font-family: verdana, arial, sans-serif;
				border-width: 1px;
				border-style: solid;
				border-radius: 0 0 5px 5px;
				padding: 5px;
				width: 162px;
				height: 10px;
				float: left;
			}

			p {
				margin: 0 0 0 0;
			}

			p.pos_fixed {
				font-family: verdana, arial, sans-serif;
				position: fixed;
				top: 50px;
				left: 230px;
			}

			p.pos_fixed2 {
				font-family: verdana, arial, sans-serif;
				position: fixed;
				top: 589px;
				left: 230px;
			}

			p.notesmall {
				font-size: 9px;
			}


			.barcode {
				padding: 1.5mm;
				margin: 1.5mm;
				vertical-align: top;
				color: #000044;
			}

			.barcodecell {
				text-align: center;
				vertical-align: middle;
				position: fixed;
				top: 14px;
				right: 10px;
			}

			p.pt {
				font-family: verdana, arial, sans-serif;
				font-size: 7px;
				position: fixed;
				top: 62px;
				left: 5px;
			}

			h3.pt {
				font-family: calibri, arial, sans-serif;
				position: fixed;
				top: 175px;
				left: 250px;
			}

			h3 {
				font-family: calibri, arial, sans-serif;
				position: fixed;
				top: 65px;
				left: 200px;
			}

			h2 {
				font-family: calibri, arial, sans-serif;
				position: fixed;
				top: 95px;
				left: 280px;
			}

			p.reg {
				font-family: verdana, arial, sans-serif;
				font-size: 11px;
			}

			p.sub {
				font-family: verdana, arial, sans-serif;
				font-size: 13px;
				position: fixed;
				top: 55px;
				left: 214px;
				color: #6b6b6b;
			}

			p.header {
				font-family: verdana, arial, sans-serif;
				font-size: 11px;
				color: #330000;
			}

			p.barcs {
				font-family: verdana, arial, sans-serif;
				font-size: 11px;
				position: fixed;
				top: 13px;
				right: 1px;
			}

			p.alamat {
				font-family: verdana, arial, sans-serif;
				font-size: 7px;
				position: fixed;
				top: 71px;
				left: 5px;
			}

			p.tlp {
				font-family: verdana, arial, sans-serif;
				font-size: 7px;
				position: fixed;
				top: 80px;
				left: 5px;
			}

			p.date {
				font-family: verdana, arial, sans-serif;
				font-size: 12px;
				text-align: right;
			}

			p.foot {
				font-family: verdana, arial, sans-serif;
				font-size: 7px;
				position: fixed;
				top: 750px;
				left: 5px;
			}

			p.footer {
				font-family: verdana, arial, sans-serif;
				font-size: 10px;
				position: fixed;
				bottom: 7px;
			}

			p.ln {
				font-family: verdana, arial, sans-serif;
				font-size: 9px;
				position: fixed;
				bottom: 1px;
				left: 2px;
			}

			#hrnew {
				border: 0;
				border-bottom: 1px solid #ccc;
				background: #999;
			}

			.text-wrap {
				overflow-wrap: break-word !important;
				word-wrap: break-word !important;
				white-space: pre-wrap !important;
				word-break: break-word !important;
			}

			.text-center {
				text-align: center !important;
				vertical-align: middle !important;
			}

			.text-left {
				text-align: left !important;
				vertical-align: middle !important;
			}
		</style>
<?php

		$Code_Trans		= trim($rows_trans->id);
		$Code_Serial	= trim($rows_trans->no_serial_number);
		$Code_Identify	= trim($rows_trans->no_identifikasi);
		$Text_Head		= $Code_Trans;
		$Text_Label		= 'S/N';
		if (!empty($Code_Identify) && $Code_Identify != '-') {
			$Text_Head		= $Code_Identify;
			$Text_Label		= 'ID';
		}

		if (!empty($Code_Serial) && $Code_Serial != '-') {
			$Text_Head		= $Code_Serial;
			$Text_Label		= 'ID';
		}

		$Font_Size		= "10px";
		$Cals_Date		= date('d-m-Y', strtotime($rows_trans->datet));
		$Extra_Text		= "<tr>
								<td colspan='3' height='1'>&nbsp;</td>
							</tr>";
		if (!empty($rows_trans->valid_until) && $rows_trans->valid_until != '0000-00-00' && $rows_trans->valid_until != '1970-01-01') {
			$Font_Size	= "9px";
			$Extra_Text	= "<tr>
							<td width='25%' class='text-center text-wrap' style='font-size:" . $Font_Size . " !important;'><b>Exp Date<br><p>&nbsp;</p></b></td>
							<td width='5%' class='text-center' style='font-size:" . $Font_Size . " !important;'>:<br><p>&nbsp;</p></td>
							<td class='text-left text-wrap' style='font-size:" . $Font_Size . " !important;'><b>" . date('d-m-Y', strtotime($rows_trans->valid_until)) . "<br><p>&nbsp;</p></b></td>
						</tr>
						";
		}

		$rows_Image	= "";

		if (strtolower($rows_tool->certification_id) == 'kan') {
			$rows_Image	= "
				<td width='70%' class='text-center'>
					<img src='" . $img_sentral . "' width='100' height='25'>
				</td>
				<td width='30%' class='text-center'>
					<img src='" . $img_kan . "' width='30' height='25'>
				</td>
			";
		} else {
			$rows_Image	= "
				<td width='100%' class='text-center' colspan='2'>
					<img src='" . $img_sentral . "' width='33' height='14'>
				</td>		
			";
		}



		$Header	= "
		<div style='border-width: 1px;border-color: #666666;border-style: solid;'>
			<table class='noborder' width='100%' height='100%' style='border-collapse: collapse !important;'>
				<tr>
					" . $rows_Image . "
				</tr>
			</table>
			
			<table width='100%' height='100%' style='border-collapse: collapse !important;font-family: verdana,arial,sans-serif;' class='noborder'>
				<tr>
					<td width='25%' class='text-center text-wrap' style='font-size:" . $Font_Size . " !important;'><b>" . $Text_Label . "</b></td>
					<td width='5%' class='text-center' style='font-size:" . $Font_Size . " !important;'>:</td>
					<td class='text-left text-wrap' style='font-size:" . $Font_Size . " !important;'><b>" . ((strlen($Text_Head) > 43) ? substr($Text_Head, 0, 43) : $Text_Head) . "</b></td>
				</tr>
				<tr>
					<td width='25%' class='text-center text-wrap' style='font-size:" . $Font_Size . " !important;'><b>Cal Date</b></td>
					<td width='5%' class='text-center' style='font-size:" . $Font_Size . " !important;'>:</td>
					<td class='text-left text-wrap' style='font-size:" . $Font_Size . " !important;'><b>" . $Cals_Date . "</b></td>
				</tr>
				" . $Extra_Text . "
				
			</table>
			
		</div>
		";


		echo $Header;

		$html = ob_get_contents();
		ob_end_clean();
		//echo $html;exit;
		$mpdf->WriteHTML($html);
		$mpdf->Output($File_Path, 'F');
	}
}
