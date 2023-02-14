<?php 
//App::import('Vendor','PHPExcel/Classes/PHPExcel.php');
//App::import('Vendor','PHPExcel/Classes/PHPExcel/Writer/Excel5.php');
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

$styleArray = array(					  
	  'alignment' => array(
		'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER
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
		'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
		'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER
	  )
  );

if($tipe_late =='1'){
	$judul			="Report Late Pick Up Customer Tool";
	$file_report	="Late_Pick_Cust_Tool";	
}else if($tipe_late =='2'){
	$judul			="Report Late Calibration Process";
	$file_report	="Late_Calibration_Process";	
}else if($tipe_late =='3'){
	$judul			="Report Late Send To Subcon";
	$file_report	="Late_Send_To_Subcon";	
}else if($tipe_late =='4'){
	$judul			="Report Late Pick From Subcon";
	$file_report	="Late_Pick_From_Subcon";	
}else if($tipe_late =='5'){
	$judul			="Report Late Send Tool To Cust";
	$file_report	="Late_Send_To_Cust";	
}
  
$Row=1;
$NewRow	=$Row+1;
$sheet 	= $objPHPExcel->getActiveSheet();

$sheet->setCellValue('A'.$Row, $judul);
$sheet->getStyle('A'.$Row.':I'.$NewRow)->applyFromArray($style_header);
$sheet->mergeCells('A'.$Row.':I'.$NewRow);

// header
$NewRow++;
$sheet->setCellValue('A'.$NewRow, 'No');
$sheet->getStyle('A'.$NewRow)->applyFromArray($style_header);

$sheet->setCellValue('B'.$NewRow, 'Tool Name');
$sheet->getStyle('B'.$NewRow)->applyFromArray($style_header);

$sheet->setCellValue('C'.$NewRow, 'Qty Late');
$sheet->getStyle('C'.$NewRow)->applyFromArray($style_header);

$sheet->setCellValue('D'.$NewRow, 'Customer');
$sheet->getStyle('D'.$NewRow)->applyFromArray($style_header);


$Lama	='J';
if($tipe_late=='1'){
	$sheet->setCellValue('E'.$NewRow, 'Quotation');
	$sheet->getStyle('E'.$NewRow)->applyFromArray($style_header);

	$sheet->setCellValue('F'.$NewRow, 'Schedule');
	$sheet->getStyle('F'.$NewRow)->applyFromArray($style_header);
	
	$sheet->setCellValue('G'.$NewRow, 'Plan Pickup Date');
	$sheet->getStyle('G'.$NewRow)->applyFromArray($style_header);

	$sheet->setCellValue('H'.$NewRow, 'Pick/Send By');
	$sheet->getStyle('H'.$NewRow)->applyFromArray($style_header);
	
	$sheet->setCellValue('I'.$NewRow, 'No SO');
	$sheet->getStyle('I'.$NewRow)->applyFromArray($style_header); 
}else if($tipe_late=='2'){
	$Lama			='L';
	$sheet->setCellValue('E'.$NewRow, 'Quotation');
	$sheet->getStyle('E'.$NewRow)->applyFromArray($style_header);

	$sheet->setCellValue('F'.$NewRow, 'Schedule');
	$sheet->getStyle('F'.$NewRow)->applyFromArray($style_header);
	
	$sheet->setCellValue('G'.$NewRow, 'Kategori');
	$sheet->getStyle('G'.$NewRow)->applyFromArray($style_header);
	
	$sheet->setCellValue('H'.$NewRow, 'Plan Process Date');
	$sheet->getStyle('H'.$NewRow)->applyFromArray($style_header);

	$sheet->setCellValue('I'.$NewRow, 'Technician');
	$sheet->getStyle('I'.$NewRow)->applyFromArray($style_header);
	
	$sheet->setCellValue('J'.$NewRow, 'No SO');
	$sheet->getStyle('J'.$NewRow)->applyFromArray($style_header);
	
	$sheet->setCellValue('K'.$NewRow, 'No SPK Teknisi');
	$sheet->getStyle('K'.$NewRow)->applyFromArray($style_header);
	
}else if($tipe_late=='3'){
	$sheet->setCellValue('E'.$NewRow, 'Quotation');
	$sheet->getStyle('E'.$NewRow)->applyFromArray($style_header);

	$sheet->setCellValue('F'.$NewRow, 'Schedule');
	$sheet->getStyle('F'.$NewRow)->applyFromArray($style_header);

	$sheet->setCellValue('G'.$NewRow, 'Plan Send To Subcon');
	$sheet->getStyle('G'.$NewRow)->applyFromArray($style_header);

	$sheet->setCellValue('H'.$NewRow, 'Subcon Name');
	$sheet->getStyle('H'.$NewRow)->applyFromArray($style_header);
	
	$sheet->setCellValue('I'.$NewRow, 'No SO');
	$sheet->getStyle('I'.$NewRow)->applyFromArray($style_header);
	
}else if($tipe_late=='4'){
	$sheet->setCellValue('E'.$NewRow, 'Quotation');
	$sheet->getStyle('E'.$NewRow)->applyFromArray($style_header);

	$sheet->setCellValue('F'.$NewRow, 'Schedule');
	$sheet->getStyle('F'.$NewRow)->applyFromArray($style_header);

	$sheet->setCellValue('G'.$NewRow, 'Plan Pick From Subcon');
	$sheet->getStyle('G'.$NewRow)->applyFromArray($style_header);

	$sheet->setCellValue('H'.$NewRow, 'Subcon Name');
	$sheet->getStyle('H'.$NewRow)->applyFromArray($style_header);
	
	$sheet->setCellValue('I'.$NewRow, 'No SO');
	$sheet->getStyle('I'.$NewRow)->applyFromArray($style_header);
}else if($tipe_late=='5'){
	$Lama	= 'H';
	$sheet->setCellValue('E'.$NewRow, 'Category');
	$sheet->getStyle('E'.$NewRow)->applyFromArray($style_header);
	
	$sheet->setCellValue('F'.$NewRow, 'Plan Date');
	$sheet->getStyle('F'.$NewRow)->applyFromArray($style_header);

	
	$sheet->setCellValue('G'.$NewRow, 'No SO');
	$sheet->getStyle('G'.$NewRow)->applyFromArray($style_header);
}

$sheet->setCellValue($Lama.$NewRow, 'Late');
$sheet->getStyle($Lama.$NewRow)->applyFromArray($style_header);

if($records){
	$loop		= 0;
	$sekarang	= date('Y-m-d');
	foreach($records as $key=>$val){
		$loop++;
		$NewRow++;
		$Kategori	= '-';
		if($tipe_late=='1'){
			$plan_ambil	= $val['plan_pick_date'];							
			$ambil_by	= ' - ';
			if($val['get_tool']=='Driver'){
				$ambil_by	= 'Pick By Driver';
			}else if($val['get_tool']=='Customer'){
				$ambil_by	= 'Send By Driver';
			}
		}else if($tipe_late=='2'){
			if($val['labs']=='Y'){
				$Kategori	= 'Labs';
			}else if($val['insitu']=='Y'){
				$Kategori	= 'Insitu';
			}
			$plan_ambil	= $val['plan_process_date'];							
			$ambil_by	= $val['teknisi_name'];							
		}else if($tipe_late=='3'){
			$plan_ambil	= $val['plan_subcon_send_date'];							
			$ambil_by	= $val['supplier_name'];							
		}else if($tipe_late=='4'){
			$plan_ambil	= $val['plan_subcon_pick_date'];							
			$ambil_by	= $val['supplier_name'];							
		}else if($tipe_late=='5'){
			$plan_ambil	= $val['plan_delivery_date'];							
			$ambil_by	= ' - ';
			if($val['get_tool']=='Driver'){
				$ambil_by	= 'Send By Driver';
			}else if($val['get_tool']=='Customer'){
				$ambil_by	= 'Pick By Customer';
			}
			if($val['labs']=='Y'){
				$Kategori	= 'Internal';
			}else if($val['subcon']=='Y'){
				$Kategori	= 'Subcon';
			}
		}
		
		$leadtime	= (strtotime($sekarang) - strtotime($plan_ambil)) / (60*60*24);
		
		$sheet->setCellValue('A'.$NewRow, $loop);
		$sheet->getStyle('A'.$NewRow)->applyFromArray($styleArray1);
		
		$sheet->setCellValue('B'.$NewRow, $val['tool_name']);
		$sheet->getStyle('B'.$NewRow)->applyFromArray($styleArray1);
		
		$sheet->setCellValue('C'.$NewRow, $val['qty_late']);
		$sheet->getStyle('C'.$NewRow)->applyFromArray($styleArray1);
		
		$sheet->setCellValue('D'.$NewRow, $val['customer_name']);
		$sheet->getStyle('D'.$NewRow)->applyFromArray($styleArray1);
		if($tipe_late != '5'){
			$sheet->setCellValue('E'.$NewRow, $val['quotation_nomor']);
			$sheet->getStyle('E'.$NewRow)->applyFromArray($styleArray1);
			
			$sheet->setCellValue('F'.$NewRow, $val['schedule_nomor']);
			$sheet->getStyle('F'.$NewRow)->applyFromArray($styleArray1);
		}
		if($tipe_late=='2'){
			$sheet->setCellValue('G'.$NewRow, $Kategori);
			$sheet->getStyle('G'.$NewRow)->applyFromArray($styleArray1);
			
			$sheet->setCellValue('H'.$NewRow, date('d M Y',strtotime($plan_ambil)));
			$sheet->getStyle('H'.$NewRow)->applyFromArray($styleArray1);
			
			$sheet->setCellValue('I'.$NewRow, $ambil_by);
			$sheet->getStyle('I'.$NewRow)->applyFromArray($styleArray1);
			
			$sheet->setCellValue('J'.$NewRow, $val['no_so']);
			$sheet->getStyle('J'.$NewRow)->applyFromArray($styleArray1);
			
			$Nomor_SPK		= $val[teknisi_id].'-'.date('Ymd',strtotime($val['plan_process_date']));
			$sheet->setCellValue('K'.$NewRow, $Nomor_SPK);
			$sheet->getStyle('K'.$NewRow)->applyFromArray($styleArray1);
		}else if($tipe_late=='5'){
			$sheet->setCellValue('E'.$NewRow, $Kategori);
			$sheet->getStyle('E'.$NewRow)->applyFromArray($styleArray1);
			
			$sheet->setCellValue('F'.$NewRow, date('d M Y',strtotime($plan_ambil)));
			$sheet->getStyle('F'.$NewRow)->applyFromArray($styleArray1);
			
			
			$sheet->setCellValue('G'.$NewRow, $val['no_so']);
			$sheet->getStyle('G'.$NewRow)->applyFromArray($styleArray1);
		}else{
			$sheet->setCellValue('G'.$NewRow, date('d M Y',strtotime($plan_ambil)));
			$sheet->getStyle('G'.$NewRow)->applyFromArray($styleArray1);
			
			$sheet->setCellValue('H'.$NewRow, $ambil_by);
			$sheet->getStyle('H'.$NewRow)->applyFromArray($styleArray1);
			
			$sheet->setCellValue('I'.$NewRow, $val['no_so']);
			$sheet->getStyle('I'.$NewRow)->applyFromArray($styleArray1);
		}		
		$sheet->setCellValue($Lama.$NewRow, $leadtime.' Day');
		$sheet->getStyle($Lama.$NewRow)->applyFromArray($styleArray1);
	}
}


$sheet->setTitle('Laporan Terlambat');       
$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
ob_end_clean();
//sesuaikan headernya 
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
header("Cache-Control: no-store, no-cache, must-revalidate");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
//ubah nama file saat diunduh
header('Content-Disposition: attachment;filename="'.$file_report.date('YmdHis').'.xls"');
//unduh file
$objWriter->save("php://output");
exit;
?>