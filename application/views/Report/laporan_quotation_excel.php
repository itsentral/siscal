<?php 

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

  
$judul			="Quotation Report ".$periode_awal." sd ".$periode_akhir;
$file_report	="Quotation_Report";	

  
$Row=1;
$NewRow	=$Row+1;
$sheet 	= $objPHPExcel->getActiveSheet();

$sheet->setCellValue('A'.$Row, $judul);
$sheet->getStyle('A'.$Row.':O'.$NewRow)->applyFromArray($style_header);
$sheet->mergeCells('A'.$Row.':O'.$NewRow);

// header
$NewRow++;
$sheet->setCellValue('A'.$NewRow, 'Quotation No');
$sheet->getStyle('A'.$NewRow)->applyFromArray($style_header);

$sheet->setCellValue('B'.$NewRow, 'Quotation Date');
$sheet->getStyle('B'.$NewRow)->applyFromArray($style_header);

$sheet->setCellValue('C'.$NewRow, 'Customer');
$sheet->getStyle('C'.$NewRow)->applyFromArray($style_header);

$sheet->setCellValue('D'.$NewRow, 'Quotation Value');
$sheet->getStyle('D'.$NewRow)->applyFromArray($style_header);

$sheet->setCellValue('E'.$NewRow, 'Insitu');
$sheet->getStyle('E'.$NewRow)->applyFromArray($style_header);

$sheet->setCellValue('F'.$NewRow, 'Akomodasi');
$sheet->getStyle('F'.$NewRow)->applyFromArray($style_header);

$sheet->setCellValue('G'.$NewRow, 'Subcon');
$sheet->getStyle('G'.$NewRow)->applyFromArray($style_header);

$sheet->setCellValue('H'.$NewRow, 'Customer Fee');
$sheet->getStyle('H'.$NewRow)->applyFromArray($style_header);

$sheet->setCellValue('I'.$NewRow, 'Netto');
$sheet->getStyle('I'.$NewRow)->applyFromArray($style_header);

$sheet->setCellValue('J'.$NewRow, 'Marketing');
$sheet->getStyle('J'.$NewRow)->applyFromArray($style_header);

$sheet->setCellValue('K'.$NewRow, 'Status');
$sheet->getStyle('K'.$NewRow)->applyFromArray($style_header);

$sheet->setCellValue('L'.$NewRow, 'Description');
$sheet->getStyle('L'.$NewRow)->applyFromArray($style_header);

$sheet->setCellValue('M'.$NewRow, 'Reff By');
$sheet->getStyle('M'.$NewRow)->applyFromArray($style_header);

$sheet->setCellValue('N'.$NewRow, 'Reff Name');
$sheet->getStyle('N'.$NewRow)->applyFromArray($style_header);

$sheet->setCellValue('O'.$NewRow, 'Reff Phone');
$sheet->getStyle('O'.$NewRow)->applyFromArray($style_header);

if($records){
	
	foreach($records as $key=>$vals){
		$loop++;
		$NewRow++;
		$nilai_dpp		= $vals['grand_tot'] - $vals['ppn'];
		$nilai_Bersih	= $nilai_dpp - $vals['total_subcon'] - $vals['total_insitu'] - $vals['total_akomodasi'] - $vals['customer_fee'];
		$sts_quot		= $vals['status'];
		if($sts_quot=='OPN'){
			$status="OPEN";
		}else if($sts_quot=='CNC'){
			$status="CANCEL";
		}else if($sts_quot=='FAL'){
			$status="FAILED";
		}if($sts_quot=='CLS'){
			$status="CLOSE";
		}else if($sts_quot=='REC'){
			$status="RECEIVE PO";
		}
		
		
		
		$sheet->setCellValue('A'.$NewRow, $vals['nomor']);
		$sheet->getStyle('A'.$NewRow)->applyFromArray($styleArray1);
		
		$sheet->setCellValue('B'.$NewRow, date('d M Y',strtotime($vals[datet])));
		$sheet->getStyle('B'.$NewRow)->applyFromArray($styleArray1);
		
		$sheet->setCellValue('C'.$NewRow, $vals[customer_name]);
		$sheet->getStyle('C'.$NewRow)->applyFromArray($styleArray1);
		
		$sheet->setCellValue('D'.$NewRow, number_format($nilai_dpp));
		$sheet->getStyle('D'.$NewRow)->applyFromArray($styleArray1);
		
		$sheet->setCellValue('E'.$NewRow, number_format($vals[total_insitu]));
		$sheet->getStyle('E'.$NewRow)->applyFromArray($styleArray1);
		
		$sheet->setCellValue('F'.$NewRow, number_format($vals[total_akomodasi]));
		$sheet->getStyle('F'.$NewRow)->applyFromArray($styleArray1);
		
		$sheet->setCellValue('G'.$NewRow, number_format($vals['total_subcon']));
		$sheet->getStyle('G'.$NewRow)->applyFromArray($styleArray1);
		
		$sheet->setCellValue('H'.$NewRow, number_format($vals['customer_fee']));
		$sheet->getStyle('H'.$NewRow)->applyFromArray($styleArray1);
			
		$sheet->setCellValue('I'.$NewRow, number_format($nilai_Bersih));
		$sheet->getStyle('I'.$NewRow)->applyFromArray($styleArray1);
		
		$sheet->setCellValue('J'.$NewRow, $vals['member_name']);
		$sheet->getStyle('J'.$NewRow)->applyFromArray($styleArray1);
			
		$sheet->setCellValue('K'.$NewRow, $status);
		$sheet->getStyle('K'.$NewRow)->applyFromArray($styleArray1);
		
		$sheet->setCellValue('L'.$NewRow, $vals['reason']);
		$sheet->getStyle('L'.$NewRow)->applyFromArray($styleArray1);
		
		$sheet->setCellValue('M'.$NewRow, $vals['reference_by']);
		$sheet->getStyle('M'.$NewRow)->applyFromArray($styleArray1);
		
		$sheet->setCellValue('N'.$NewRow, $vals['reference_name']);
		$sheet->getStyle('N'.$NewRow)->applyFromArray($styleArray1);
		
		$sheet->setCellValue('O'.$NewRow, $vals['reference_phone']);
		$sheet->getStyle('O'.$NewRow)->applyFromArray($styleArray1);
		
	}
}


$sheet->setTitle('Report');       
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