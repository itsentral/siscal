<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Technician_productivities extends CI_Controller { 
	public function __construct(){
        parent::__construct();	
		
		$this->load->database();
        if(!$this->session->userdata('isSISCALlogin')){
			redirect('login');
		}
		$this->load->model('master_model');
		$controller			= ucfirst(strtolower($this->uri->segment(1)));
		$this->Arr_Akses	= getAcccesmenu($controller);
		
		$this->folder	= 'Productivity';
    }

	public function index(){
		$Arr_Akses			= $this->Arr_Akses;
		if($Arr_Akses['read'] !='1'){
			$this->session->set_flashdata("alert_data", "<div class=\"alert alert-warning\" id=\"flash-message\">You Don't Have Right To Access This Page, Please Contact Your Administrator....</div>");
			redirect(site_url('dashboard'));
		}
		
		$data = array(
			'title'			=> 'TECHNICIAN PRODUCTIVITY',
			'action'		=> 'index',
			'akses_menu'	=> $Arr_Akses,
			'rows_teknisi'	=> $this->GetTechnician()
		);
		history('View Technician Productivity');
		$this->load->view($this->folder.'/v_prod_technician',$data);
	}
	
	function GetTechnician($Flag_Active = ''){
		$WHERE_Tech			= "head_member.division_id = 'DIV-002' AND head_skill.flag_active = 'Y'";
		
		if($Flag_Active == 'Y'){
			if(!empty($WHERE_Tech))$WHERE_Tech	.=" AND ";
			$WHERE_Tech	.="head_skill.flag_active = 'Y'";
		}else if($Flag_Active == 'N'){
			if(!empty($WHERE_Tech))$WHERE_Tech	.=" AND ";
			$WHERE_Tech	.="head_skill.flag_active = 'N'";
		}
		
		if(!empty($WHERE_Tech))$WHERE_Tech	.=" AND ";
		$WHERE_Tech	.="`status` = '1'";
		$Query_Teknisi	= "SELECT
										head_member.id,
										head_member.nama
									FROM
										members head_member
										INNER JOIN tech_skills head_skill ON head_skill.member_id=head_member.id
									WHERE
										".$WHERE_Tech."
									ORDER BY
										head_member.nama ASC";
		$Temp_Teknisi	= array();
		$rows_Teknisi	= $this->db->query($Query_Teknisi)->result();
		if($rows_Teknisi){
			foreach($rows_Teknisi as $KeyTech=>$ValTech){
				$Code_Tech		= $ValTech->id;
				$Name_Tech		= $ValTech->nama;
				$Temp_Teknisi[$Code_Tech]	= strtoupper($Name_Tech);
			}
			unset($rows_Teknisi);
		}
		
		return $Temp_Teknisi;
	}
	
	function GetDayOffDate($Month = '', $Tahun = ''){
		$Temp_Day	= array();
		if(empty($Month))$Month	= date('n');
		if(empty($Tahun))$Tahun	= date('Y');
		$Last_Day	= date('t',mktime(0,0,0,$Month,1,$Tahun));
		for($x=1;$x<=$Last_Day;$x++){
			$Day			= sprintf('%02d',$x);
			$Temp_Day[$x]	= $Day;
		}
		
		return $Temp_Day;
	}
	
	function get_data_display(){
		$Arr_Akses		= $this->Arr_Akses;
		$User_Groupid	= $this->session->userdata('siscal_group_id');
		$Month_Find		= $this->input->post('bulan');
		$Year_Find		= $this->input->post('tahun');
		$Teknisi_Find	= $this->input->post('teknisi');
		$rows_Technician	= $this->GetTechnician();
		$Plan_Date		= "IF (
							NOT (
								det_trans.datet IS NULL
								OR det_trans.datet = ''
								OR det_trans.datet = '-'
							),
							det_trans.datet,
							head_trans.plan_process_date
						)";
		$Plan_Tech		= "IF (
							NOT (
								det_trans.actual_teknisi_id IS NULL
								OR det_trans.actual_teknisi_id = ''
								OR det_trans.actual_teknisi_id = '-'
							),
							det_trans.actual_teknisi_id,
							head_trans.teknisi_id
						)";
		$WHERE			= "det_trans.flag_proses = 'Y'
							AND (
								head_trans.labs = 'Y'
								OR head_trans.insitu = 'Y'
							)";
		if($Month_Find){
			if(!empty($WHERE))$WHERE	.=" AND ";
			$WHERE	.="MONTH(".$Plan_Date.") = '".$Month_Find."'";
		}
		
		
		if($Year_Find){
			if(!empty($WHERE))$WHERE	.=" AND ";
			$WHERE	.="YEAR(".$Plan_Date.") = '".$Year_Find."'";
		}
		
		if($Teknisi_Find){
			if(!empty($WHERE))$WHERE	.=" AND ";
			$WHERE	.=$Plan_Tech." = '".$Teknisi_Find."'";
			
			$rows_Technician	= $this->master_model->getArray('members',array('id'=>$Teknisi_Find),'id','nama');
		}
		
		$Query_Productivity		= "SELECT
									".$Plan_Tech." AS code_technician,
									IF (
										NOT (
											det_trans.actual_teknisi_name IS NULL
											OR det_trans.actual_teknisi_name = ''
											OR det_trans.actual_teknisi_name = '-'
										),
										det_trans.actual_teknisi_name,
										head_trans.teknisi_name
									) AS name_technician,
									 DAY (
										".$Plan_Date."
									) AS date_cals,
									 SUM(
										 ROUND(
											(
												TIME_TO_SEC(head_trans.plan_time_end) - TIME_TO_SEC(head_trans.plan_time_start)
											) / (60 * head_trans.qty )
										)
										
									) AS jumlah_menit
									FROM
										trans_data_details det_trans
									INNER JOIN trans_details head_trans ON det_trans.trans_detail_id = head_trans.id
									WHERE
										".$WHERE."
									GROUP BY
										".$Plan_Tech.",
										".$Plan_Date;
		$rows_Productivity	= $this->db->query($Query_Productivity)->result();
		$rows_Days			= $this->GetDayOffDate($Month_Find,$Year_Find);
		
		
		$data = array(
			'title'			=> 'LIST TECHNICIAN PRODUCTIVITY',
			'action'		=> 'get_data_display',
			'akses_menu'	=> $Arr_Akses,
			'rows_teknisi'	=> $rows_Technician,
			'rows_detail'	=> $rows_Productivity,
			'rows_day'		=> $rows_Days,
			'bulan_cari'	=> $Month_Find,
			'tahun_cari'	=> $Year_Find
		);
		$this->load->view($this->folder.'/v_prod_technician_list',$data);
		
	}
	
	function preview_detail_productivity(){
		$rows_Detail	= $rows_Teknisi = array();
		$Periode_Find	= '';
		if($this->input->post()){
			$Code_Tech		= $this->input->post('teknisi');
			$Month_Find		= $this->input->post('bulan');
			$Year_Find		= $this->input->post('tahun');
			$Day_Find		= $this->input->post('hari');
			$Periode_Find	= date('F Y',mktime(0,0,0,$Month_Find,1,$Year_Find));
			if($Day_Find){
				$Periode_Find	= date('F Y',mktime(0,0,0,$Month_Find,$Day_Find,$Year_Find));
			}
			
			$Plan_Date		= "IF (
								NOT (
									det_trans.datet IS NULL
									OR det_trans.datet = ''
									OR det_trans.datet = '-'
								),
								det_trans.datet,
								head_trans.plan_process_date
							)";
			$Plan_Tech		= "IF (
								NOT (
									det_trans.actual_teknisi_id IS NULL
									OR det_trans.actual_teknisi_id = ''
									OR det_trans.actual_teknisi_id = '-'
								),
								det_trans.actual_teknisi_id,
								head_trans.teknisi_id
							)";
			$WHERE			= "det_trans.flag_proses = 'Y'
								AND (
									head_trans.labs = 'Y'
									OR head_trans.insitu = 'Y'
								)";
			if($Month_Find){
				if(!empty($WHERE))$WHERE	.=" AND ";
				$WHERE	.="MONTH(".$Plan_Date.") = '".$Month_Find."'";
			}
			
			
			if($Year_Find){
				if(!empty($WHERE))$WHERE	.=" AND ";
				$WHERE	.="YEAR(".$Plan_Date.") = '".$Year_Find."'";
			}
			
			if($Day_Find){
				if(!empty($WHERE))$WHERE	.=" AND ";
				$WHERE	.="DAY(".$Plan_Date.") = '".$Day_Find."'";
			}
			
			if($Code_Tech){
				if(!empty($WHERE))$WHERE	.=" AND ";
				$WHERE	.=$Plan_Tech." = '".$Code_Tech."'";
				$rows_Teknisi	= $this->db->get_where('members',array('id'=>$Code_Tech))->row();
			}
			
			$Query_Productivity		= "SELECT
										det_trans.id AS code_trans,
										head_trans.id AS code_head,
										det_trans.tool_id,
										det_trans.tool_name,
										det_trans.no_identifikasi,
										det_trans.no_serial_number,
										det_trans.no_sertifikat,
										det_trans.valid_until,
										".$Plan_Tech." AS code_technician,
										IF (
											NOT (
												det_trans.actual_teknisi_name IS NULL
												OR det_trans.actual_teknisi_name = ''
												OR det_trans.actual_teknisi_name = '-'
											),
											det_trans.actual_teknisi_name,
											head_trans.teknisi_name
										) AS name_technician,
										 ".$Plan_Date." AS date_cals,
										 ROUND(
											(
												TIME_TO_SEC(head_trans.plan_time_end) - TIME_TO_SEC(head_trans.plan_time_start)
											) / (60 * head_trans.qty )
										) AS jumlah_menit,
										head_trans.quotation_id,
										 head_trans.quotation_nomor,
										 head_trans.quotation_nomor,
										 head_trans.customer_id,
										 head_trans.customer_name,
										 head_trans.letter_order_id,
										 head_trans.no_so,
										 head_trans.tgl_so,
										 head_trans.pono,
										 head_trans.podate,
										 head_trans.marketing_id,
										 head_trans.marketing_name,
										 head_trans.plan_time_start,
										 head_trans.plan_time_end,
										 head_trans.labs,
										 head_trans.insitu,
										 head_trans.subcon,
										 head_trans.qty,
										 head_trans.supplier_id,
										 head_trans.supplier_name
										FROM
											trans_data_details det_trans
										INNER JOIN trans_details head_trans ON det_trans.trans_detail_id = head_trans.id
										WHERE
											".$WHERE."
										ORDER BY
											".$Plan_Tech." ASC,
											".$Plan_Date." ASC";
			$rows_Detail	= $this->db->query($Query_Productivity)->result();
			
			
		}
		$data = array(
			'title'			=> 'PREVIEW TECHNICIAN PRODUCTIVITY',
			'action'		=> 'preview_detail_productivity',
			'rows_teknisi'	=> $rows_Teknisi,
			'rows_detail'	=> $rows_Detail,
			'period_find'	=> $Periode_Find
		);
		$this->load->view($this->folder.'/v_prod_technician_preview',$data);
	}
	
	function download_summary_productivity(){
		$Month_Find		= urldecode($this->input->get('bulan'));
		$Year_Find		= urldecode($this->input->get('tahun'));
		$Teknisi_Find	= urldecode($this->input->get('teknisi'));
		$Arr_Bulan		= array(1=>'Jan','Feb','Mar','Apr','Mei','Jun','Jul','Aug','Sep','Oct','Nov','Dec'); 
		$Judul			="TECHNICIAN SUMMARY PRODUCTIVITY";
		$Title			="Productivity";	
		$rows_Technician= $this->GetTechnician();
		set_time_limit(0);
		ini_set('memory_limit','1024M');
		
		$Plan_Date		= "IF (
							NOT (
								det_trans.datet IS NULL
								OR det_trans.datet = ''
								OR det_trans.datet = '-'
							),
							det_trans.datet,
							head_trans.plan_process_date
						)";
		$Plan_Tech		= "IF (
							NOT (
								det_trans.actual_teknisi_id IS NULL
								OR det_trans.actual_teknisi_id = ''
								OR det_trans.actual_teknisi_id = '-'
							),
							det_trans.actual_teknisi_id,
							head_trans.teknisi_id
						)";
		$WHERE			= "det_trans.flag_proses = 'Y'
							AND (
								head_trans.labs = 'Y'
								OR head_trans.insitu = 'Y'
							)";
		if($Month_Find){
			if(!empty($WHERE))$WHERE	.=" AND ";
			$WHERE	.="MONTH(".$Plan_Date.") = '".$Month_Find."'";
			$Judul	.=" ".strtoupper($Arr_Bulan[$Month_Find]);
		}
		
		
		if($Year_Find){
			if(!empty($WHERE))$WHERE	.=" AND ";
			$WHERE	.="YEAR(".$Plan_Date.") = '".$Year_Find."'";
			$Judul	.=" ".$Year_Find;
		}
		
		if($Teknisi_Find){
			if(!empty($WHERE))$WHERE	.=" AND ";
			$WHERE	.=$Plan_Tech." = '".$Teknisi_Find."'";
			$rows_Technician	= $this->master_model->getArray('members',array('id'=>$Teknisi_Find),'id','nama');
		}
		
		$Query_Productivity		= "SELECT
									".$Plan_Tech." AS code_technician,
									IF (
										NOT (
											det_trans.actual_teknisi_name IS NULL
											OR det_trans.actual_teknisi_name = ''
											OR det_trans.actual_teknisi_name = '-'
										),
										det_trans.actual_teknisi_name,
										head_trans.teknisi_name
									) AS name_technician,
									 DAY (
										".$Plan_Date."
									) AS date_cals,
									 SUM(
										 ROUND(
											(
												TIME_TO_SEC(head_trans.plan_time_end) - TIME_TO_SEC(head_trans.plan_time_start)
											) / (60 * head_trans.qty )
										)
										
									) AS jumlah_menit
									FROM
										trans_data_details det_trans
									INNER JOIN trans_details head_trans ON det_trans.trans_detail_id = head_trans.id
									WHERE
										".$WHERE."
									GROUP BY
										".$Plan_Tech.",
										".$Plan_Date;
		$rows_Productivity	= $this->db->query($Query_Productivity)->result();
		$rows_Days			= $this->GetDayOffDate($Month_Find,$Year_Find);
		
		
		
		
		
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
		$Jumlah_Baris	= count($rows_Days) + 3;
		$Cols	= getColsChar($Jumlah_Baris);
		$sheet->setCellValue('A'.$Row, $Judul);
		$sheet->getStyle('A'.$Row.':'.$Cols.$NewRow)->applyFromArray($style_header);
		$sheet->mergeCells('A'.$Row.':'.$Cols.$NewRow);
		
		$Mulai_Col	= 0;
		// header
		$NewRow++;
		$Row_Baru	= $NewRow + 1;
		
		$Mulai_Col++;
		$Cols		= getColsChar($Mulai_Col);
		$sheet->setCellValue($Cols.$NewRow, 'No');
		$sheet->getStyle($Cols.$NewRow.':'.$Cols.$Row_Baru)->applyFromArray($style_header);
		$sheet->mergeCells($Cols.$NewRow.':'.$Cols.$Row_Baru);
		
		$Mulai_Col++;
		$Cols		= getColsChar($Mulai_Col);
		$sheet->setCellValue($Cols.$NewRow, 'Technician');
		$sheet->getStyle($Cols.$NewRow.':'.$Cols.$Row_Baru)->applyFromArray($style_header);
		$sheet->mergeCells($Cols.$NewRow.':'.$Cols.$Row_Baru);
		
		
		
		$Mulai_Col++;
		$Cols		= getColsChar($Mulai_Col);
		
		$Mulai_Col2	= $Mulai_Col + count($rows_Days) + 2;
		$Cols2		= getColsChar($Mulai_Col2);
		$sheet->setCellValue($Cols.$NewRow, 'Productivity'.date('F Y',mktime(0,0,0,$Month_Find,1,$Year_Find)));
		$sheet->getStyle($Cols.$NewRow.':'.$Cols2.$NewRow)->applyFromArray($style_header);
		$sheet->mergeCells($Cols.$NewRow.':'.$Cols2.$NewRow);
		
		if($rows_Days){
			foreach($rows_Days as $keyDay=>$valDay){
				$Cols		= getColsChar($Mulai_Col);
				$sheet->setCellValue($Cols.$Row_Baru, $valDay);
				$sheet->getStyle($Cols.$Row_Baru)->applyFromArray($style_header);
				$Mulai_Col++;
			}
		}
		$Cols		= getColsChar($Mulai_Col);
		$sheet->setCellValue($Cols.$Row_Baru, 'Total');
		$sheet->getStyle($Cols.$Row_Baru)->applyFromArray($style_header);
		
		$Temp_Find	= array();
		if($rows_Productivity){
			foreach($rows_Productivity as $keyDet=>$valDet){
				$code_Tech		= $valDet->code_technician;
				$date_Cals		= $valDet->date_cals;
				$Jum_Minute		= $valDet->jumlah_menit;
				$Temp_Find[$code_Tech][$date_Cals]	= $Jum_Minute;
			}
			unset($rows_Productivity);
		}
		if($rows_Technician){
			
			$Temp_Summary	= array();
			$intL			= 0;
			$Total_All		= 0;
			$awal_row		= $Row_Baru;
			//echo"<pre>";print_r($detDetail);
			foreach($rows_Technician as $keyTech=>$valTech){
				$intL++;
				$awal_row++;
				
				$awal_col	= 0;
				$awal_col++;				
				$Cols		= getColsChar($awal_col);
				$sheet->setCellValue($Cols.$awal_row, $intL);
				$sheet->getStyle($Cols.$awal_row)->applyFromArray($styleArray2);
				
				$awal_col++;				
				$Cols		= getColsChar($awal_col);
				$sheet->setCellValue($Cols.$awal_row, $valTech);
				$sheet->getStyle($Cols.$awal_row)->applyFromArray($styleArray2);
				
				
				$Sub_Total		= 0;
				foreach($rows_Days as $keyDate=>$valDate){
					$Tanggal_Cari		= date('Y-m-d',mktime(0,0,0,$Month_Find,$keyDate,$Year_Find));
					$Nil_Tanggal		= 0;
					if(isset($Temp_Find[$keyTech][$keyDate]) && !empty($Temp_Find[$keyTech][$keyDate])){
						$Nil_Tanggal	= round($Temp_Find[$keyTech][$keyDate] / 60,1);
					}
					
					$Text_Tanggal		= $Nil_Tanggal;
					if(!isset($Temp_Summary[$keyDate]) || empty($Temp_Summary[$keyDate])){
						$Temp_Summary[$keyDate]	= 0;
					}
					$Temp_Summary[$keyDate]	+=$Nil_Tanggal;
					$Sub_Total				+=$Nil_Tanggal;
					$Total_All				+=$Nil_Tanggal;
					
					$awal_col++;				
					$Cols		= getColsChar($awal_col);
					$sheet->setCellValue($Cols.$awal_row, $Nil_Tanggal);
					$sheet->getStyle($Cols.$awal_row)->applyFromArray($styleArray2);
					
				}
				$awal_col++;				
				$Cols		= getColsChar($awal_col);
				$sheet->setCellValue($Cols.$awal_row, $Sub_Total);
				$sheet->getStyle($Cols.$awal_row)->applyFromArray($styleArray2);
				
			}
			
			$Curr_Row	= $awal_row + 1;
			$Next_Row	= $awal_row + 2;
			
			$Mulai_Col	= 0;
			
			$Mulai_Col++;			
			$Cols		= getColsChar($Mulai_Col);
			$Mulai_Col++;			
			$Cols2		= getColsChar($Mulai_Col);
			$sheet->setCellValue($Cols.$Curr_Row, 'TOTAL');
			$sheet->getStyle($Cols.$Curr_Row.':'.$Cols2.$Curr_Row)->applyFromArray($style_header);
			$sheet->mergeCells($Cols.$Curr_Row.':'.$Cols2.$Curr_Row);
			
			$sheet->setCellValue($Cols.$Next_Row, 'AVERAGE');
			$sheet->getStyle($Cols.$Next_Row.':'.$Cols2.$Next_Row)->applyFromArray($style_header);
			$sheet->mergeCells($Cols.$Next_Row.':'.$Cols2.$Next_Row);
		
			
			foreach($rows_Days as $keyFoot=>$valFoot){
				$Nil_Total	= $Temp_Summary[$keyFoot];
				
				$Nil_Rata	= 0;
				if($Nil_Total > 0){
					$Nil_Rata	= round($Nil_Total / count($rows_Technician),1);
				}
				$Mulai_Col++;
				$Cols		= getColsChar($Mulai_Col);
				$sheet->setCellValue($Cols.$Curr_Row, round($Nil_Total,1));
				$sheet->getStyle($Cols.$Curr_Row)->applyFromArray($style_header);
				
			
				$sheet->setCellValue($Cols.$Next_Row, round($Nil_Rata,1));
				$sheet->getStyle($Cols.$Next_Row)->applyFromArray($style_header);
				
				
			}
			$Mulai_Col++;
			$Cols		= getColsChar($Mulai_Col);
			$sheet->setCellValue($Cols.$Curr_Row, number_format($Total_All,1));
			$sheet->getStyle($Cols.$Curr_Row)->applyFromArray($style_header);
			
		
			$sheet->setCellValue($Cols.$Next_Row, round($Total_All / count($rows_Technician),1));
			$sheet->getStyle($Cols.$Next_Row)->applyFromArray($style_header);
			
		
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
		header('Content-Disposition: attachment;filename="Summary_Productivity_'.date('YmdHis').'.xls"');
		//unduh file
		$objWriter->save("php://output");
		
	}
	
	function download_detail_productivity(){
		$Month_Find		= urldecode($this->input->get('bulan'));
		$Year_Find		= urldecode($this->input->get('tahun'));
		$Teknisi_Find	= urldecode($this->input->get('teknisi'));
		$Arr_Bulan		= array(1=>'Jan','Feb','Mar','Apr','Mei','Jun','Jul','Aug','Sep','Oct','Nov','Dec'); 
		$Judul			="TECHNICIAN DETAIL PRODUCTIVITY";
		$Title			="Productivity";	
		$rows_Technician= $this->GetTechnician();
		set_time_limit(0);
		ini_set('memory_limit','1024M');
		
		$Plan_Date		= "IF (
							NOT (
								det_trans.datet IS NULL
								OR det_trans.datet = ''
								OR det_trans.datet = '-'
							),
							det_trans.datet,
							head_trans.plan_process_date
						)";
		$Plan_Tech		= "IF (
							NOT (
								det_trans.actual_teknisi_id IS NULL
								OR det_trans.actual_teknisi_id = ''
								OR det_trans.actual_teknisi_id = '-'
							),
							det_trans.actual_teknisi_id,
							head_trans.teknisi_id
						)";
		$WHERE			= "det_trans.flag_proses = 'Y'
							AND (
								head_trans.labs = 'Y'
								OR head_trans.insitu = 'Y'
							)";
		if($Month_Find){
			if(!empty($WHERE))$WHERE	.=" AND ";
			$WHERE	.="MONTH(".$Plan_Date.") = '".$Month_Find."'";
			$Judul	.=" ".strtoupper($Arr_Bulan[$Month_Find]);
		}
		
		
		if($Year_Find){
			if(!empty($WHERE))$WHERE	.=" AND ";
			$WHERE	.="YEAR(".$Plan_Date.") = '".$Year_Find."'";
			$Judul	.=" ".$Year_Find;
		}
		
		if($Teknisi_Find){
			if(!empty($WHERE))$WHERE	.=" AND ";
			$WHERE	.=$Plan_Tech." = '".$Teknisi_Find."'";
			$rows_Technician	= $this->master_model->getArray('members',array('id'=>$Teknisi_Find),'id','nama');
		}
		
		$Query_Productivity		= "SELECT
									det_trans.id AS code_trans,
									head_trans.id AS code_head,
									det_trans.tool_id,
									det_trans.tool_name,
									det_trans.no_identifikasi,
									det_trans.no_serial_number,
									det_trans.no_sertifikat,
									det_trans.valid_until,
									".$Plan_Tech." AS code_technician,
									IF (
										NOT (
											det_trans.actual_teknisi_name IS NULL
											OR det_trans.actual_teknisi_name = ''
											OR det_trans.actual_teknisi_name = '-'
										),
										det_trans.actual_teknisi_name,
										head_trans.teknisi_name
									) AS name_technician,
									 ".$Plan_Date." AS date_cals,
									 ROUND(
										(
											TIME_TO_SEC(head_trans.plan_time_end) - TIME_TO_SEC(head_trans.plan_time_start)
										) / (60 * head_trans.qty )
									) AS jumlah_menit,
									head_trans.quotation_id,
									 head_trans.quotation_nomor,
									 head_trans.quotation_nomor,
									 head_trans.customer_id,
									 head_trans.customer_name,
									 head_trans.letter_order_id,
									 head_trans.no_so,
									 head_trans.tgl_so,
									 head_trans.pono,
									 head_trans.podate,
									 head_trans.marketing_id,
									 head_trans.marketing_name,
									 head_trans.plan_time_start,
									 head_trans.plan_time_end,
									 head_trans.labs,
									 head_trans.insitu,
									 head_trans.subcon,
									 head_trans.qty,
									 head_trans.supplier_id,
									 head_trans.supplier_name,
									 IF(head_trans.labs = 'Y','LABS',IF(head_trans.insitu = 'Y','INSITU','SUBCON')) AS type_cals
									FROM
										trans_data_details det_trans
									INNER JOIN trans_details head_trans ON det_trans.trans_detail_id = head_trans.id
									WHERE
										".$WHERE."
									ORDER BY
										".$Plan_Tech." ASC,
										".$Plan_Date." ASC";
		$rows_Detail	= $this->db->query($Query_Productivity)->result();
		
		
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
		
		$rows_Judul	= array(
			'tool_id'			=> 'Code Tool',
			'tool_name'			=> 'Name Tool',
			'no_identifikasi'	=> 'Identify No',
			'no_serial_number'	=> 'Serial Number No',
			'customer_name'		=> 'Customer',
			'no_so'				=> 'SO No',
			'tgl_so'			=> 'SO Date',
			'quotation_nomor'	=> 'Quotation',
			'pono'				=> 'PO No',
			'type_cals'			=> 'Type Process',
			'name_technician'	=> 'Technician',
			'date_cals'			=> 'Calibration Date',
			'jumlah_menit'		=> 'Duration (Minutes)'			
		);
		
		
		$Row	= 1;
		$NewRow	= $Row+1;
		$sheet 	= $objPHPExcel->getActiveSheet();
		$Jumlah_Baris	= count($rows_Judul) + 1;
		$Cols	= getColsChar($Jumlah_Baris);
		$sheet->setCellValue('A'.$Row, $Judul);
		$sheet->getStyle('A'.$Row.':'.$Cols.$NewRow)->applyFromArray($style_header);
		$sheet->mergeCells('A'.$Row.':'.$Cols.$NewRow);
		
		$Mulai_Col	= 0;
		// header
		$NewRow	= $NewRow + 2;
		
		
		$Mulai_Col++;
		$Cols		= getColsChar($Mulai_Col);
		$sheet->setCellValue($Cols.$NewRow, 'No');
		$sheet->getStyle($Cols.$NewRow)->applyFromArray($style_header);
		
		foreach($rows_Judul as $KeyHead=>$valHead){
			$Mulai_Col++;
			$Cols		= getColsChar($Mulai_Col);
			$sheet->setCellValue($Cols.$NewRow, $valHead);
			$sheet->getStyle($Cols.$NewRow)->applyFromArray($style_header);
		}
		
		
		if($rows_Detail){
			
			$intL			= 0;			
			$awal_row		= $NewRow;
			$Sum_Total		= 0;
			//echo"<pre>";print_r($detDetail);
			foreach($rows_Detail as $keyDet=>$valDet){
				$intL++;
				$awal_row++;
				
				$awal_col	= 0;
				$awal_col++;				
				$Cols		= getColsChar($awal_col);
				$sheet->setCellValue($Cols.$awal_row, $intL);
				$sheet->getStyle($Cols.$awal_row)->applyFromArray($styleArray2);
				
				$intLoop	= 0;
				foreach($rows_Judul as $keyDate=>$valDate){
					$intLoop++;
					$Val_Column			= $valDet->$keyDate;
					$awal_col++;				
					$Cols		= getColsChar($awal_col);
					$sheet->setCellValue($Cols.$awal_row, $Val_Column);
					$sheet->getStyle($Cols.$awal_row)->applyFromArray($styleArray2);
					
					if($intLoop == 13){
						$Sum_Total	+=intval($Val_Column);
					}
				}
				
				
			}
			
			$Curr_Row	= $awal_row + 1;
			$Next_Row	= $awal_row + 2;
			
			$Mulai_Col	= 0;
			
			$Mulai_Col++;			
			$Cols		= getColsChar($Mulai_Col);
			$Mulai_Col	= 13;			
			$Cols2		= getColsChar($Mulai_Col);
			$sheet->setCellValue($Cols.$Curr_Row, 'TOTAL');
			$sheet->getStyle($Cols.$Curr_Row.':'.$Cols2.$Next_Row)->applyFromArray($style_header);
			$sheet->mergeCells($Cols.$Curr_Row.':'.$Cols2.$Next_Row);
			
			$Mulai_Col++;
			$Cols		= getColsChar($Mulai_Col);
			$sheet->setCellValue($Cols.$Curr_Row, number_format($Sum_Total).' Minutes');
			$sheet->getStyle($Cols.$Curr_Row)->applyFromArray($style_header);
			
		
			$sheet->setCellValue($Cols.$Next_Row, round($Sum_Total / 60,1).' Hour');
			$sheet->getStyle($Cols.$Next_Row)->applyFromArray($style_header);
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
		header('Content-Disposition: attachment;filename="Detail_Productivity_'.date('YmdHis').'.xls"');
		//unduh file
		$objWriter->save("php://output");
	}
	
}