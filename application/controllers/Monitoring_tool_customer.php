<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Monitoring_tool_customer extends CI_Controller { 
	public function __construct(){
        parent::__construct();	
		
		$this->load->database();
        if(!$this->session->userdata('isSISCALlogin')){
			redirect('login');
		}
		$this->load->model('master_model');
		$controller			= ucfirst(strtolower($this->uri->segment(1)));
		$this->Arr_Akses	= getAcccesmenu($controller);
		
		$this->folder	= 'Tool_monitoring';
    }

	public function index(){
		$Arr_Akses			= $this->Arr_Akses;
		if($Arr_Akses['read'] !='1'){
			$this->session->set_flashdata("alert_data", "<div class=\"alert alert-warning\" id=\"flash-message\">You Don't Have Right To Access This Page, Please Contact Your Administrator....</div>");
			redirect(site_url('dashboard'));
		}
		
		$data = array(
			'title'			=> 'MONITORING CUSTOMER TOOLS',
			'action'		=> 'index',
			'akses_menu'	=> $Arr_Akses
		);
		history('View Monitoring Close Tools');
		$this->load->view($this->folder.'/v_monitor_close',$data);
	}
	function get_data_display(){
		$Arr_Akses		= $this->Arr_Akses;
		$User_Groupid	= $this->session->userdata('siscal_group_id');
		$Month_Find		= $this->input->post('bulan');
		$Year_Find		= $this->input->post('tahun');
		
		
		$WHERE			= "1=1";
		if($Month_Find){
			if(!empty($WHERE))$WHERE	.=" AND ";
			$WHERE	.="MONTH(tgl_so) = '".$Month_Find."'";
		}
		
		
		if($Year_Find){
			if(!empty($WHERE))$WHERE	.=" AND ";
			$WHERE	.="YEAR(tgl_so) = '".$Year_Find."'";
		}
		
		
		
		$requestData	= $_REQUEST;
		
		$like_value     = $requestData['search']['value'];
        $column_order   = $requestData['order'][0]['column'];
        $column_dir     = $requestData['order'][0]['dir'];
        $limit_start    = $requestData['start'];
        $limit_length   = $requestData['length'];
		
		$columns_order_by = array(
			0 => 'quotation_nomor',
			1 => 'pono',
			2 => 'customer_name',
			3 => 'no_so',
			4 => 'tgl_so',
			5 => 'schedule_nomor',
			6 => 'tool_id',
			7 => 'tool_name',
			8 => 'qty_tool',
			9 => 'category',
			10 => 'supplier_name',
			11 => 'location',
			12 => 'pick_date',
			13 => 'spk_pick_driver_nomor',
			14 => 'bast_rec_no',
			16 => 'plan_subcon_send_date',
			17 => 'subcon_send_spk_nomor',
			18 => 'subcon_bast_send_no',
			19 => 'qty_subcon_send_real',
			20 => 'plan_process_date',
			21 => 'teknisi_name',
			22 => 'qty_labs_real',
			23 => 'qty_proses',
			24 => 'qty_fail',
			25 => 'plan_subcon_pick_date',
			26 => 'subcon_pick_spk_nomor',
			27 => 'subcon_bast_rec_no',
			28 => 'qty_subcon_rec_real',
			29 => 'plan_delivery_date',
			30 => 'spk_send_driver_nomor',
			31 => 'bast_send_no',
			31 => 'qty_send_real'
			
		);
		
		
		
		if($like_value){
			if(!empty($WHERE))$WHERE	.=" AND ";
			$WHERE	.="(
						  quotation_nomor LIKE '%".$this->db->escape_like_str($like_value)."%'
						  OR DATE_FORMAT(tgl_so, '%d-%m-%Y') LIKE '%".$this->db->escape_like_str($like_value)."%'
						  OR pono LIKE '%".$this->db->escape_like_str($like_value)."%'
						  OR customer_name LIKE '%".$this->db->escape_like_str($like_value)."%'
						  OR no_so LIKE '%".$this->db->escape_like_str($like_value)."%'
						  OR tool_id LIKE '%".$this->db->escape_like_str($like_value)."%'
						  OR schedule_nomor LIKE '%".$this->db->escape_like_str($like_value)."%'
						  OR tool_name LIKE '%".$this->db->escape_like_str($like_value)."%'
						  OR qty_tool LIKE '%".$this->db->escape_like_str($like_value)."%'
						  OR category LIKE '%".$this->db->escape_like_str($like_value)."%'
						  OR supplier_name LIKE '%".$this->db->escape_like_str($like_value)."%'
						  OR location LIKE '%".$this->db->escape_like_str($like_value)."%'
						  OR pick_date LIKE '%".$this->db->escape_like_str($like_value)."%'
						  OR spk_pick_driver_nomor LIKE '%".$this->db->escape_like_str($like_value)."%'
						  OR bast_rec_no LIKE '%".$this->db->escape_like_str($like_value)."%'
						  OR plan_subcon_send_date LIKE '%".$this->db->escape_like_str($like_value)."%'
						  OR subcon_send_spk_nomor LIKE '%".$this->db->escape_like_str($like_value)."%'
						  OR subcon_bast_send_no LIKE '%".$this->db->escape_like_str($like_value)."%'
						  OR qty_subcon_send_real LIKE '%".$this->db->escape_like_str($like_value)."%'
						  OR plan_process_date LIKE '%".$this->db->escape_like_str($like_value)."%'
						  OR teknisi_name LIKE '%".$this->db->escape_like_str($like_value)."%'
						  OR qty_labs_real LIKE '%".$this->db->escape_like_str($like_value)."%'
						  OR qty_proses LIKE '%".$this->db->escape_like_str($like_value)."%'
						  OR qty_fail LIKE '%".$this->db->escape_like_str($like_value)."%'
						  OR plan_subcon_pick_date LIKE '%".$this->db->escape_like_str($like_value)."%'
						  OR subcon_pick_spk_nomor LIKE '%".$this->db->escape_like_str($like_value)."%'
						  OR subcon_bast_rec_no LIKE '%".$this->db->escape_like_str($like_value)."%'
						  OR qty_subcon_rec_real LIKE '%".$this->db->escape_like_str($like_value)."%'
						  OR plan_delivery_date LIKE '%".$this->db->escape_like_str($like_value)."%'
						  OR spk_send_driver_nomor LIKE '%".$this->db->escape_like_str($like_value)."%'
						  OR bast_send_no LIKE '%".$this->db->escape_like_str($like_value)."%'
						  OR qty_send_real LIKE '%".$this->db->escape_like_str($like_value)."%'
						)";
		}
		
		
		
		$sql = "SELECT
					*,
					(@row:=@row+1) AS urut
				FROM
					view_monitoring_tool_schedules,
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
			
			$Code_Process			= $row['id'];
			$Nomor_Quot				= $row['quotation_nomor'];
			$Nomor_PO				= $row['pono'];
			$Nomor_SO				= $row['no_so'];
			$Date_SO				= date('d-m-Y',strtotime($row['tgl_so']));
			$Nomor_Schedule			= $row['schedule_nomor'];
			$Customer				= $row['customer_name'];
			$Teknisi				= strtoupper($row['teknisi_name']);
			
			$Code_Tool				= $row['tool_id'];
			$Name_Tool				= $row['tool_name'];
			$Qty_Tool				= $row['qty_tool'];
			$Cat_Tool				= $row['category'];
			
			$Supplier				= $row['supplier_name'];
			$Location				= $row['location'];
			$Pick_Date				= $row['pick_date'];
			$Pick_Nomor				= $row['spk_pick_driver_nomor'];
			$Bast_Rec_Nomor			= $row['bast_rec_no'];
			$Subcon_Send_Date		= $row['plan_subcon_send_date'];
			$Subcon_Send_Nomor		= $row['subcon_send_spk_nomor'];
			$Subcon_Bast_Send_Nomor	= $row['subcon_bast_send_no'];
			$Qty_Sub_Send			= $row['qty_subcon_send_real'];
			$Cals_Date				= $row['plan_process_date'];
			$Qty_Labs				= $row['qty_labs_real'];
			$Qty_Process			= $row['qty_proses'];
			$Qty_Fail				= $row['qty_fail'];
			$Subcon_Pick_Date		= $row['plan_subcon_pick_date'];
			$Subcon_Pick_Nomor		= $row['subcon_pick_spk_nomor'];
			$Subcon_Bast_Rec_Nomor	= $row['subcon_bast_rec_no'];
			$Qty_Sub_Rec			= $row['qty_subcon_rec_real'];
			$Delivery_Date			= $row['plan_delivery_date'];
			$Send_Nomor				= $row['spk_send_driver_nomor'];
			$Bast_Send_Nomor		= $row['bast_send_no'];
			$Qty_Send				= $row['qty_send_real'];
			
			$Qty_Terima				= $Qty_Tool;
			if(strtolower($Cat_Tool) == 'insitu'){
				$Qty_Terima			= 0;
			}
			
			$Ket_Tipe				= '<span class="badge bg-green-active">Labs</span>';
			if(strtolower($Cat_Tool) == 'insitu'){
				$Ket_Tipe			= '<span class="badge bg-maroon-active">Insitu</span>';
			}else if(strtolower($Cat_Tool) == 'subcon'){
				$Ket_Tipe			= '<span class="badge bg-blue-active">Subcon</span>';
			}
			
			$Qty_Reschedule			= $row['qty_reschedule'];
			$Keterangan				= '-';
			if($Qty_Reschedule > 0 && $row['pro_reschedule'] == 'N'){
				$Keterangan				= '<span class="badge bg-purple-active">Plan Reschedule : '.$Qty_Reschedule.'</span>';
				
			}
			
			$Qty_Kirim_Subcon		= $Qty_Sub_Send;
			if($Qty_Sub_Send > $Qty_Tool){
				$Qty_Kirim_Subcon	= $Qty_Sub_Send - $Qty_Reschedule;
			}
			
			$Qty_Ambil_Subcon		= $Qty_Sub_Rec;
			if($Qty_Sub_Rec > $Qty_Tool){
				$Qty_Ambil_Subcon	= $Qty_Sub_Rec - $Qty_Reschedule;
			}
			
			$Qty_Kirim_Cust			= $Qty_Send;
			if($Qty_Send > $Qty_Tool){
				$Qty_Kirim_Cust			= $Qty_Send - $Qty_Reschedule;
			}
			
			$Qty_Inlab				= $Qty_Labs;
			if($Qty_Labs > $Qty_Tool){
				$Qty_Inlab			= $Qty_Labs - $Qty_Reschedule;
			}
			
			$Total_Process			= $Qty_Process + $Qty_Fail;
			if($Total_Process > $Qty_Tool){
				if($Qty_Process > $Qty_Tool){
					$Qty_Process	= $Qty_Tool;
				}else{
					$Qty_Fail		= $Qty_Tool - $Qty_Process;
				}
			}
			
			
			$Ket_Cals		= '-';
			
			$Query_Notest	= "SELECT
									GROUP_CONCAT(DISTINCT(UPPER(keterangan)) SEPARATOR ', ') AS notes
								FROM
									trans_data_details
								WHERE
									trans_detail_id = '".$Code_Process."'
								AND NOT (
									keterangan IS NULL
									OR keterangan = ''
									OR keterangan = '-'
								)";
			$rows_Notes		= $this->db->query($Query_Notest)->row();
			if($rows_Notes){
				$Ket_Cals	= $rows_Notes->notes;
			}
			
			
			$nestedData		= array();
			$nestedData[]	= $Nomor_Quot;
			$nestedData[]	= $Nomor_PO;
			$nestedData[]	= $Customer;
			$nestedData[]	= $Nomor_SO;
			$nestedData[]	= $Date_SO;
			$nestedData[]	= $Nomor_Schedule;
			$nestedData[]	= $Code_Tool;
			$nestedData[]	= $Name_Tool;
			$nestedData[]	= $Qty_Tool;
			$nestedData[]	= $Ket_Tipe;
			$nestedData[]	= $Supplier;
			$nestedData[]	= $Location;
			$nestedData[]	= $Pick_Date;
			$nestedData[]	= $Pick_Nomor;
			$nestedData[]	= $Bast_Rec_Nomor;
			$nestedData[]	= $Qty_Terima;
			$nestedData[]	= $Subcon_Send_Date;
			$nestedData[]	= $Subcon_Send_Nomor;
			$nestedData[]	= $Subcon_Bast_Send_Nomor;
			$nestedData[]	= $Qty_Kirim_Subcon;
			$nestedData[]	= $Cals_Date;
			$nestedData[]	= $Teknisi;
			$nestedData[]	= $Qty_Inlab;
			$nestedData[]	= $Qty_Process;			
			$nestedData[]	= $Qty_Fail;
			$nestedData[]	= $Subcon_Pick_Date;
			$nestedData[]	= $Subcon_Pick_Nomor;
			$nestedData[]	= $Subcon_Bast_Rec_Nomor;
			$nestedData[]	= $Qty_Ambil_Subcon;
			$nestedData[]	= $Delivery_Date;
			$nestedData[]	= $Send_Nomor;
			$nestedData[]	= $Bast_Send_Nomor;
			$nestedData[]	= $Qty_Kirim_Cust;
			$nestedData[]	= $Keterangan;
			$nestedData[]	= $Ket_Cals;
			
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
	
	function download_excel_tool(){
		$Month_Find		= urldecode($this->input->get('bulan'));
		$Year_Find		= urldecode($this->input->get('tahun'));
		$Arr_Bulan		= array(1=>'Jan','Feb','Mar','Apr','Mei','Jun','Jul','Aug','Sep','Oct','Nov','Dec'); 
		$Judul			="Report Monitoring Proses Kalibrasi (OPEN)";
		$Title			="Monitor";	
		set_time_limit(0);
		ini_set('memory_limit','1024M');
		
		$WHERE			= "1=1";
		if($Month_Find){
			if(!empty($WHERE))$WHERE	.=" AND ";
			$WHERE	.="MONTH(tgl_so) = '".$Month_Find."'";
			
			$Judul	.=" ".$Arr_Bulan[$Month_Find];
		}
		
		
		if($Year_Find){
			if(!empty($WHERE))$WHERE	.=" AND ";
			$WHERE	.="YEAR(tgl_so) = '".$Year_Find."'";
			$Judul	.=" ".$Year_Find;
		}
		
		
		
		
		$this->load->library("PHPExcel");		
		$objPHPExcel	= new PHPExcel();
		
		$style_header = array(
			'borders' => array(
				'allborders' => array(
					  'style' => PHPExcel_Style_Border::BORDER_THIN,
					  'color' => array('rgb'=>'1006A3')
				  )
			),
			'fill' => array(
				'type' => PHPExcel_Style_Fill::FILL_SOLID,
				'color' => array('rgb'=>'E1E0F7'),
			),
			'font' => array(
				'bold' => true,
			),
			'alignment' => array(
				'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
				'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER
			)
		);

		$style_header2 = array(	
			'fill' => array(
				'type' => PHPExcel_Style_Fill::FILL_SOLID,
				'color' => array('rgb'=>'E1E0F7'),
			),
			'font' => array(
				'bold' => true,
			),
			'alignment' => array(
				'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
				'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER
			)
		);

		$styleArray = array(					  
			  'alignment' => array(
				'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER
			  )
		  );
		$styleArray3 = array(					  
			  'alignment' => array(
				'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_LEFT
			  )
		  );  
	    $styleArray1 = array(
			  'borders' => array(
				  'allborders' => array(
					  'style' => PHPExcel_Style_Border::BORDER_THIN
				  )
			  ),
			  'alignment' => array(
				'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_LEFT,
				'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER
			  )
		  );
		$styleArray2 = array(
			  'borders' => array(
				  'allborders' => array(
					  'style' => PHPExcel_Style_Border::BORDER_THIN
				  )
			  ),
			  'alignment' => array(
				'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_LEFT,
				'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER
			  )
		  );
		 
		$sheet 		= $objPHPExcel->getActiveSheet();
		
		
		
		
		$Row	= 1;
		$NewRow	= $Row+1;
		$sheet 	= $objPHPExcel->getActiveSheet();

		$sheet->setCellValue('A'.$Row, $Judul);
		$sheet->getStyle('A'.$Row.':AH'.$NewRow)->applyFromArray($style_header);
		$sheet->mergeCells('A'.$Row.':AH'.$NewRow);
		
		$Mulai_Col	= 0;
		// header
		$NewRow++;
		$Row_Baru	= $NewRow + 1;
		
		$Mulai_Col++;
		$Cols		= getColsChar($Mulai_Col);
		$sheet->setCellValue($Cols.$NewRow, 'Quotation');
		$sheet->getStyle($Cols.$NewRow.':'.$Cols.$Row_Baru)->applyFromArray($style_header);
		$sheet->mergeCells($Cols.$NewRow.':'.$Cols.$Row_Baru);
		
		$Mulai_Col++;
		$Cols		= getColsChar($Mulai_Col);
		$sheet->setCellValue($Cols.$NewRow, 'PO No');
		$sheet->getStyle($Cols.$NewRow.':'.$Cols.$Row_Baru)->applyFromArray($style_header);
		$sheet->mergeCells($Cols.$NewRow.':'.$Cols.$Row_Baru);
		
		$Mulai_Col++;
		$Cols		= getColsChar($Mulai_Col);
		$sheet->setCellValue($Cols.$NewRow, 'Customer');
		$sheet->getStyle($Cols.$NewRow.':'.$Cols.$Row_Baru)->applyFromArray($style_header);
		$sheet->mergeCells($Cols.$NewRow.':'.$Cols.$Row_Baru);
		
		$Mulai_Col++;
		$Cols		= getColsChar($Mulai_Col);
		
		$Mulai_Col2	= $Mulai_Col + 1;
		$Cols2		= getColsChar($Mulai_Col2);
		$sheet->setCellValue($Cols.$NewRow, 'Sales Order');
		$sheet->getStyle($Cols.$NewRow.':'.$Cols2.$NewRow)->applyFromArray($style_header);
		$sheet->mergeCells($Cols.$NewRow.':'.$Cols2.$NewRow);
		
		$sheet->setCellValue($Cols.$Row_Baru, 'No SO');
		$sheet->getStyle($Cols.$Row_Baru)->applyFromArray($style_header);
		
		$Mulai_Col++;
		$Cols		= getColsChar($Mulai_Col);
		$sheet->setCellValue($Cols.$Row_Baru, 'Tgl SO');
		$sheet->getStyle($Cols.$Row_Baru)->applyFromArray($style_header);
		
		$Mulai_Col++;
		$Cols		= getColsChar($Mulai_Col);
		$sheet->setCellValue($Cols.$NewRow, 'Schedule');
		$sheet->getStyle($Cols.$NewRow.':'.$Cols.$Row_Baru)->applyFromArray($style_header);
		$sheet->mergeCells($Cols.$NewRow.':'.$Cols.$Row_Baru);

		$Mulai_Col++;
		$Cols		= getColsChar($Mulai_Col);
		$sheet->setCellValue($Cols.$NewRow, 'Tool ID');
		$sheet->getStyle($Cols.$NewRow.':'.$Cols.$Row_Baru)->applyFromArray($style_header);
		$sheet->mergeCells($Cols.$NewRow.':'.$Cols.$Row_Baru);
		
		$Mulai_Col++;
		$Cols		= getColsChar($Mulai_Col);
		$sheet->setCellValue($Cols.$NewRow, 'Tool Name');
		$sheet->getStyle($Cols.$NewRow.':'.$Cols.$Row_Baru)->applyFromArray($style_header);
		$sheet->mergeCells($Cols.$NewRow.':'.$Cols.$Row_Baru);
		
		$Mulai_Col++;
		$Cols		= getColsChar($Mulai_Col);
		$sheet->setCellValue($Cols.$NewRow, 'Qty');
		$sheet->getStyle($Cols.$NewRow.':'.$Cols.$Row_Baru)->applyFromArray($style_header);
		$sheet->mergeCells($Cols.$NewRow.':'.$Cols.$Row_Baru);
		
		$Mulai_Col++;
		$Cols		= getColsChar($Mulai_Col);
		$sheet->setCellValue($Cols.$NewRow, 'Status');
		$sheet->getStyle($Cols.$NewRow.':'.$Cols.$Row_Baru)->applyFromArray($style_header);
		$sheet->mergeCells($Cols.$NewRow.':'.$Cols.$Row_Baru);
		
		$Mulai_Col++;
		$Cols		= getColsChar($Mulai_Col);
		$sheet->setCellValue($Cols.$NewRow, 'Vendor');
		$sheet->getStyle($Cols.$NewRow.':'.$Cols.$Row_Baru)->applyFromArray($style_header);
		$sheet->mergeCells($Cols.$NewRow.':'.$Cols.$Row_Baru);

		$Mulai_Col++;
		$Cols		= getColsChar($Mulai_Col);
		$sheet->setCellValue($Cols.$NewRow, 'Lokasi');
		$sheet->getStyle($Cols.$NewRow.':'.$Cols.$Row_Baru)->applyFromArray($style_header);
		$sheet->mergeCells($Cols.$NewRow.':'.$Cols.$Row_Baru);
		
		$Mulai_Col++;
		$Cols		= getColsChar($Mulai_Col);
		$Mulai_Col2	= $Mulai_Col + 3;
		$Cols2		= getColsChar($Mulai_Col2);
		$sheet->setCellValue($Cols.$NewRow, 'Pick Date');
		$sheet->getStyle($Cols.$NewRow.':'.$Cols2.$NewRow)->applyFromArray($style_header);
		$sheet->mergeCells($Cols.$NewRow.':'.$Cols2.$NewRow);
		
		$sheet->setCellValue($Cols.$Row_Baru, 'Pick Date');
		$sheet->getStyle($Cols.$Row_Baru)->applyFromArray($style_header);
		
		$Mulai_Col++;
		$Cols		= getColsChar($Mulai_Col);
		$sheet->setCellValue($Cols.$Row_Baru, 'SPK Driver No');
		$sheet->getStyle($Cols.$Row_Baru)->applyFromArray($style_header);
		
		$Mulai_Col++;
		$Cols		= getColsChar($Mulai_Col);
		$sheet->setCellValue($Cols.$Row_Baru, 'BAST No');
		$sheet->getStyle($Cols.$Row_Baru)->applyFromArray($style_header);
		
		$Mulai_Col++;
		$Cols		= getColsChar($Mulai_Col);
		$sheet->setCellValue($Cols.$Row_Baru, 'Qty Receive');
		$sheet->getStyle($Cols.$Row_Baru)->applyFromArray($style_header);
		
		
		$Mulai_Col++;
		$Cols		= getColsChar($Mulai_Col);
		$Mulai_Col2	= $Mulai_Col + 3;
		$Cols2		= getColsChar($Mulai_Col2);
		$sheet->setCellValue($Cols.$NewRow, 'Send To Subcon');
		$sheet->getStyle($Cols.$NewRow.':'.$Cols2.$NewRow)->applyFromArray($style_header);
		$sheet->mergeCells($Cols.$NewRow.':'.$Cols2.$NewRow);
		
		$sheet->setCellValue($Cols.$Row_Baru, 'Plan Date');
		$sheet->getStyle($Cols.$Row_Baru)->applyFromArray($style_header);
		
		$Mulai_Col++;
		$Cols		= getColsChar($Mulai_Col);
		$sheet->setCellValue($Cols.$Row_Baru, 'SPK Driver No');
		$sheet->getStyle($Cols.$Row_Baru)->applyFromArray($style_header);
		
		$Mulai_Col++;
		$Cols		= getColsChar($Mulai_Col);
		$sheet->setCellValue($Cols.$Row_Baru, 'BAST No');
		$sheet->getStyle($Cols.$Row_Baru)->applyFromArray($style_header);
		
		$Mulai_Col++;
		$Cols		= getColsChar($Mulai_Col);
		$sheet->setCellValue($Cols.$Row_Baru, 'Qty Send');
		$sheet->getStyle($Cols.$Row_Baru)->applyFromArray($style_header);
		
		
		$Mulai_Col++;
		$Cols		= getColsChar($Mulai_Col);
		$Mulai_Col2	= $Mulai_Col + 4;
		$Cols2		= getColsChar($Mulai_Col2);
		$sheet->setCellValue($Cols.$NewRow, 'Calibration Process');
		$sheet->getStyle($Cols.$NewRow.':'.$Cols2.$NewRow)->applyFromArray($style_header);
		$sheet->mergeCells($Cols.$NewRow.':'.$Cols2.$NewRow);
		
		$sheet->setCellValue($Cols.$Row_Baru, 'Plan Date');
		$sheet->getStyle($Cols.$Row_Baru)->applyFromArray($style_header);
		
		$Mulai_Col++;
		$Cols		= getColsChar($Mulai_Col);
		$sheet->setCellValue($Cols.$Row_Baru, 'Technician');
		$sheet->getStyle($Cols.$Row_Baru)->applyFromArray($style_header);
		
		$Mulai_Col++;
		$Cols		= getColsChar($Mulai_Col);
		$sheet->setCellValue($Cols.$Row_Baru, 'Qty Inlab');
		$sheet->getStyle($Cols.$Row_Baru)->applyFromArray($style_header);
		
		$Mulai_Col++;
		$Cols		= getColsChar($Mulai_Col);
		$sheet->setCellValue($Cols.$Row_Baru, 'Qty Success');
		$sheet->getStyle($Cols.$Row_Baru)->applyFromArray($style_header);
		
		$Mulai_Col++;
		$Cols		= getColsChar($Mulai_Col);
		$sheet->setCellValue($Cols.$Row_Baru, 'Qty Fail');
		$sheet->getStyle($Cols.$Row_Baru)->applyFromArray($style_header);

		$Mulai_Col++;
		$Cols		= getColsChar($Mulai_Col);
		$Mulai_Col2	= $Mulai_Col + 3;
		$Cols2		= getColsChar($Mulai_Col2);
		$sheet->setCellValue($Cols.$NewRow, 'Pick From Subcon');
		$sheet->getStyle($Cols.$NewRow.':'.$Cols2.$NewRow)->applyFromArray($style_header);
		$sheet->mergeCells($Cols.$NewRow.':'.$Cols2.$NewRow);
		
		$sheet->setCellValue($Cols.$Row_Baru, 'Plan Date');
		$sheet->getStyle($Cols.$Row_Baru)->applyFromArray($style_header);
		
		$Mulai_Col++;
		$Cols		= getColsChar($Mulai_Col);
		$sheet->setCellValue($Cols.$Row_Baru, 'SPK Driver No');
		$sheet->getStyle($Cols.$Row_Baru)->applyFromArray($style_header);
		
		$Mulai_Col++;
		$Cols		= getColsChar($Mulai_Col);
		$sheet->setCellValue($Cols.$Row_Baru, 'BAST No');
		$sheet->getStyle($Cols.$Row_Baru)->applyFromArray($style_header);
		
		$Mulai_Col++;
		$Cols		= getColsChar($Mulai_Col);
		$sheet->setCellValue($Cols.$Row_Baru, 'Qty Receive');
		$sheet->getStyle($Cols.$Row_Baru)->applyFromArray($style_header);
		
		
		$Mulai_Col++;
		$Cols		= getColsChar($Mulai_Col);
		$Mulai_Col2	= $Mulai_Col + 3;
		$Cols2		= getColsChar($Mulai_Col2);
		$sheet->setCellValue($Cols.$NewRow, 'Send To Customer');
		$sheet->getStyle($Cols.$NewRow.':'.$Cols2.$NewRow)->applyFromArray($style_header);
		$sheet->mergeCells($Cols.$NewRow.':'.$Cols2.$NewRow);
		
		$sheet->setCellValue($Cols.$Row_Baru, 'Plan Date');
		$sheet->getStyle($Cols.$Row_Baru)->applyFromArray($style_header);
		
		$Mulai_Col++;
		$Cols		= getColsChar($Mulai_Col);
		$sheet->setCellValue($Cols.$Row_Baru, 'SPK Driver No');
		$sheet->getStyle($Cols.$Row_Baru)->applyFromArray($style_header);
		
		$Mulai_Col++;
		$Cols		= getColsChar($Mulai_Col);
		$sheet->setCellValue($Cols.$Row_Baru, 'BAST No');
		$sheet->getStyle($Cols.$Row_Baru)->applyFromArray($style_header);
		
		$Mulai_Col++;
		$Cols		= getColsChar($Mulai_Col);
		$sheet->setCellValue($Cols.$Row_Baru, 'Qty Send');
		$sheet->getStyle($Cols.$Row_Baru)->applyFromArray($style_header);
		
		$Mulai_Col++;
		$Cols		= getColsChar($Mulai_Col);
		$sheet->setCellValue($Cols.$NewRow, 'Keterangan');
		$sheet->getStyle($Cols.$NewRow.':'.$Cols.$Row_Baru)->applyFromArray($style_header);
		$sheet->mergeCells($Cols.$NewRow.':'.$Cols.$Row_Baru);
		
		$Mulai_Col++;
		$Cols		= getColsChar($Mulai_Col);
		$sheet->setCellValue($Cols.$NewRow, 'Cals Notes');
		$sheet->getStyle($Cols.$NewRow.':'.$Cols.$Row_Baru)->applyFromArray($style_header);
		$sheet->mergeCells($Cols.$NewRow.':'.$Cols.$Row_Baru);
		
		$sql = "SELECT
					*,
					(@row:=@row+1) AS urut
				FROM
					view_monitoring_tool_schedules,
				(SELECT @row:=0) r 
				WHERE ".$WHERE."
				ORDER BY 
					tgl_so DESC
				";
		$records	= $this->db->query($sql)->result_array();
		
					
		if($records){
			$awal_row		= $Row_Baru;
			$loop			= 0;
			foreach($records as $keys=>$values){
				$awal_row++;
				$loop++;
				
				$kategori	= '-';
				$Qty_Alat	= $values['qty_tool'];
				$kategori	= $values['category'];
				$Qty_Rec	= 0;
				if($kategori !='Insitu'){
					$Qty_Rec	= $values['qty_tool'];
				}
				$Qty_Kirim_Subcon	= $values['qty_subcon_send_real'];
				$Qty_Ambil_Subcon	= $values['qty_subcon_rec_real'];
				$Qty_Kirim_Cust		= $values['qty_send_real'];
				$Qty_Inlab			= $values['qty_labs_real'];
				if($Qty_Kirim_Subcon > $Qty_Rec){
					$Qty_Kirim_Subcon	= $Qty_Kirim_Subcon - $values['qty_reschedule'];
				}
				
				if($Qty_Ambil_Subcon > $Qty_Rec){
					$Qty_Ambil_Subcon	= $Qty_Ambil_Subcon - $values['qty_reschedule'];
				}
				
				if($Qty_Kirim_Cust > $Qty_Rec){
					$Qty_Kirim_Cust		= $Qty_Kirim_Cust - $values['qty_reschedule'];
				}
				
				if($Qty_Inlab > $Qty_Rec){
					$Qty_Inlab			= $Qty_Inlab - $values['qty_reschedule'];
				}
				$Keterangan		= '-';
				if($values['qty_reschedule'] > 0 && $values['pro_reschedule']=='N'){
					$Keterangan	= 'Plan Reschedule : '.$values['qty_reschedule'];
				}
				
				$Qty_Proses		= $values['qty_proses'];
				$Qty_Fail		= $values['qty_fail'];
				if(($Qty_Proses + $Qty_Fail) > $Qty_Alat){
					if($Qty_Proses > $Qty_Alat){
						$Qty_Proses	= $Qty_Alat;
					}else{
						$Qty_Fail	= $Qty_Alat - $Qty_Proses;
					}
				}
				
				$Ket_Cals		= '-';
			
				$Query_Notest	= "SELECT
										GROUP_CONCAT(DISTINCT(UPPER(keterangan)) SEPARATOR ', ') AS notes
									FROM
										trans_data_details
									WHERE
										trans_detail_id = '".$values['id']."'
									AND NOT (
										keterangan IS NULL
										OR keterangan = ''
										OR keterangan = '-'
									)";
				$rows_Notes		= $this->db->query($Query_Notest)->row();
				if($rows_Notes){
					$Ket_Cals	= $rows_Notes->notes;
				}
				
				$awal_col	= 0;
				$awal_col++;				
				$Cols		= getColsChar($awal_col);
				$sheet->setCellValue($Cols.$awal_row, $values['quotation_nomor']);
				$sheet->getStyle($Cols.$awal_row)->applyFromArray($styleArray2);
				
				$awal_col++;				
				$Cols		= getColsChar($awal_col);
				$sheet->setCellValue($Cols.$awal_row, $values['pono']);
				$sheet->getStyle($Cols.$awal_row)->applyFromArray($styleArray2);
				
				$awal_col++;				
				$Cols		= getColsChar($awal_col);
				$sheet->setCellValue($Cols.$awal_row, $values['customer_name']);
				$sheet->getStyle($Cols.$awal_row)->applyFromArray($styleArray2);
				
				$awal_col++;				
				$Cols		= getColsChar($awal_col);
				$sheet->setCellValue($Cols.$awal_row, $values['no_so']);
				$sheet->getStyle($Cols.$awal_row)->applyFromArray($styleArray2);
				
				$awal_col++;				
				$Cols		= getColsChar($awal_col);
				$sheet->setCellValue($Cols.$awal_row, date('d-m-Y',strtotime($values['tgl_so'])));
				$sheet->getStyle($Cols.$awal_row)->applyFromArray($styleArray2);
				
				$awal_col++;				
				$Cols		= getColsChar($awal_col);
				$sheet->setCellValue($Cols.$awal_row, $values['schedule_nomor']);
				$sheet->getStyle($Cols.$awal_row)->applyFromArray($styleArray2);
				
				$awal_col++;				
				$Cols		= getColsChar($awal_col);
				$sheet->setCellValue($Cols.$awal_row, $values['tool_id']);
				$sheet->getStyle($Cols.$awal_row)->applyFromArray($styleArray2);
				
				$awal_col++;				
				$Cols		= getColsChar($awal_col);
				$sheet->setCellValue($Cols.$awal_row, $values['tool_name']);
				$sheet->getStyle($Cols.$awal_row)->applyFromArray($styleArray2);
				
				$awal_col++;				
				$Cols		= getColsChar($awal_col);
				$sheet->setCellValue($Cols.$awal_row, $values['qty_tool']);
				$sheet->getStyle($Cols.$awal_row)->applyFromArray($styleArray2);
				
				$awal_col++;				
				$Cols		= getColsChar($awal_col);
				$sheet->setCellValue($Cols.$awal_row, $kategori);
				$sheet->getStyle($Cols.$awal_row)->applyFromArray($styleArray2);
					
				$awal_col++;				
				$Cols		= getColsChar($awal_col);
				$sheet->setCellValue($Cols.$awal_row, $values['supplier_name']);
				$sheet->getStyle($Cols.$awal_row)->applyFromArray($styleArray2);
					
				$awal_col++;				
				$Cols		= getColsChar($awal_col);
				$sheet->setCellValue($Cols.$awal_row, $values['location']);
				$sheet->getStyle($Cols.$awal_row)->applyFromArray($styleArray2);
				
				$awal_col++;				
				$Cols		= getColsChar($awal_col);
				$sheet->setCellValue($Cols.$awal_row, date('d-m-Y',strtotime($values['pick_date'])));
				$sheet->getStyle($Cols.$awal_row)->applyFromArray($styleArray2);
				
				
				$awal_col++;				
				$Cols		= getColsChar($awal_col);
				$sheet->setCellValue($Cols.$awal_row, $values['spk_pick_driver_nomor']);
				$sheet->getStyle($Cols.$awal_row)->applyFromArray($styleArray2);
				
				$awal_col++;				
				$Cols		= getColsChar($awal_col);
				$sheet->setCellValue($Cols.$awal_row, $values['bast_rec_no']);
				$sheet->getStyle($Cols.$awal_row)->applyFromArray($styleArray2);
				
				$awal_col++;				
				$Cols		= getColsChar($awal_col);
				$sheet->setCellValue($Cols.$awal_row, $Qty_Rec);
				$sheet->getStyle($Cols.$awal_row)->applyFromArray($styleArray2);
				
				
				
				$awal_col++;				
				$Cols		= getColsChar($awal_col);
				$sheet->setCellValue($Cols.$awal_row, date('d-m-Y',strtotime($values['plan_subcon_send_date'])));
				$sheet->getStyle($Cols.$awal_row)->applyFromArray($styleArray2);
				
				
				$awal_col++;				
				$Cols		= getColsChar($awal_col);
				$sheet->setCellValue($Cols.$awal_row, $values['subcon_send_spk_nomor']);
				$sheet->getStyle($Cols.$awal_row)->applyFromArray($styleArray2);
				
				
				$awal_col++;				
				$Cols		= getColsChar($awal_col);
				$sheet->setCellValue($Cols.$awal_row, $values['subcon_bast_send_no']);
				$sheet->getStyle($Cols.$awal_row)->applyFromArray($styleArray2);
				
				$awal_col++;				
				$Cols		= getColsChar($awal_col);
				$sheet->setCellValue($Cols.$awal_row, $Qty_Kirim_Subcon);
				$sheet->getStyle($Cols.$awal_row)->applyFromArray($styleArray2);
				
				$awal_col++;				
				$Cols		= getColsChar($awal_col);
				$sheet->setCellValue($Cols.$awal_row, date('d-m-Y',strtotime($values['plan_process_date'])));
				$sheet->getStyle($Cols.$awal_row)->applyFromArray($styleArray2);
				
				$awal_col++;				
				$Cols		= getColsChar($awal_col);
				$sheet->setCellValue($Cols.$awal_row, $values['teknisi_name']);
				$sheet->getStyle($Cols.$awal_row)->applyFromArray($styleArray2);
				
				$awal_col++;				
				$Cols		= getColsChar($awal_col);
				$sheet->setCellValue($Cols.$awal_row, $Qty_Inlab);
				$sheet->getStyle($Cols.$awal_row)->applyFromArray($styleArray2);
				
				$awal_col++;				
				$Cols		= getColsChar($awal_col);
				$sheet->setCellValue($Cols.$awal_row, $Qty_Proses);
				$sheet->getStyle($Cols.$awal_row)->applyFromArray($styleArray2);
				
				$awal_col++;				
				$Cols		= getColsChar($awal_col);
				$sheet->setCellValue($Cols.$awal_row, $Qty_Fail);
				$sheet->getStyle($Cols.$awal_row)->applyFromArray($styleArray2);
				
				$awal_col++;				
				$Cols		= getColsChar($awal_col);
				$sheet->setCellValue($Cols.$awal_row, date('d-m-Y',strtotime($values['plan_subcon_pick_date'])));
				$sheet->getStyle($Cols.$awal_row)->applyFromArray($styleArray2);
				
				$awal_col++;				
				$Cols		= getColsChar($awal_col);
				$sheet->setCellValue($Cols.$awal_row, $values['subcon_pick_spk_nomor']);
				$sheet->getStyle($Cols.$awal_row)->applyFromArray($styleArray2);
				
				$awal_col++;				
				$Cols		= getColsChar($awal_col);
				$sheet->setCellValue($Cols.$awal_row, $values['subcon_bast_rec_no']);
				$sheet->getStyle($Cols.$awal_row)->applyFromArray($styleArray2);
				
				$awal_col++;				
				$Cols		= getColsChar($awal_col);
				$sheet->setCellValue($Cols.$awal_row, $Qty_Ambil_Subcon);
				$sheet->getStyle($Cols.$awal_row)->applyFromArray($styleArray2);
				
				
				$awal_col++;				
				$Cols		= getColsChar($awal_col);
				$sheet->setCellValue($Cols.$awal_row, date('d-m-Y',strtotime($values['plan_delivery_date'])));
				$sheet->getStyle($Cols.$awal_row)->applyFromArray($styleArray2);
				
				
				$awal_col++;				
				$Cols		= getColsChar($awal_col);
				$sheet->setCellValue($Cols.$awal_row, $values['spk_send_driver_nomor']);
				$sheet->getStyle($Cols.$awal_row)->applyFromArray($styleArray2);
				
				$awal_col++;				
				$Cols		= getColsChar($awal_col);
				$sheet->setCellValue($Cols.$awal_row, $values['bast_send_no']);
				$sheet->getStyle($Cols.$awal_row)->applyFromArray($styleArray2);
				
				$awal_col++;				
				$Cols		= getColsChar($awal_col);
				$sheet->setCellValue($Cols.$awal_row, $Qty_Kirim_Cust);
				$sheet->getStyle($Cols.$awal_row)->applyFromArray($styleArray2);
				
				$awal_col++;				
				$Cols		= getColsChar($awal_col);
				$sheet->setCellValue($Cols.$awal_row, $Keterangan);
				$sheet->getStyle($Cols.$awal_row)->applyFromArray($styleArray2);
				
				$awal_col++;				
				$Cols		= getColsChar($awal_col);
				$sheet->setCellValue($Cols.$awal_row, $Ket_Cals);
				$sheet->getStyle($Cols.$awal_row)->applyFromArray($styleArray2);
				
				
			}
			
		}
		
		
		
		$sheet->setTitle($Title);
		//mulai menyimpan excel format xlsx, kalau ingin xls ganti Excel2007 menjadi Excel5          
		$objWriter		= PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
		//ob_end_clean();
		//sesuaikan headernya 
		header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
		header("Cache-Control: no-store, no-cache, must-revalidate");
		header("Cache-Control: post-check=0, pre-check=0", false);
		header("Pragma: no-cache");
		header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
		//ubah nama file saat diunduh
		header('Content-Disposition: attachment;filename="Report_Monitoring_Tool_Open_'.date('YmdHis').'.xls"');
		//unduh file
		$objWriter->save("php://output");
		
	}
	
	
}