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

  
  
$Row	=1;
$NewRow	=$Row+1;
$sheet 	= $objPHPExcel->getActiveSheet();

$sheet->setCellValue('A'.$Row, 'LAPORAN OUTSTANDING BAST SERTIFIKAT');
$sheet->getStyle('A'.$Row.':G'.$NewRow)->applyFromArray($style_header);
$sheet->mergeCells('A'.$Row.':G'.$NewRow);



// header
$NewRow++;
$NextRow	= $NewRow + 1;
$sheet->setCellValue('A'.$NewRow, 'No');
$sheet->getStyle('A'.$NewRow.':A'.$NextRow)->applyFromArray($style_header);
$sheet->mergeCells('A'.$NewRow.':A'.$NextRow);

$sheet->setCellValue('B'.$NewRow, 'No SO');
$sheet->getStyle('B'.$NewRow.':B'.$NextRow)->applyFromArray($style_header);
$sheet->mergeCells('B'.$NewRow.':B'.$NextRow);

$sheet->setCellValue('C'.$NewRow, 'Tgl SO');
$sheet->getStyle('C'.$NewRow.':C'.$NextRow)->applyFromArray($style_header);
$sheet->mergeCells('C'.$NewRow.':C'.$NextRow);

$sheet->setCellValue('D'.$NewRow, 'Customer');
$sheet->getStyle('D'.$NewRow.':D'.$NextRow)->applyFromArray($style_header);
$sheet->mergeCells('D'.$NewRow.':D'.$NextRow);

$sheet->setCellValue('E'.$NewRow, 'No Quotation');
$sheet->getStyle('E'.$NewRow.':E'.$NextRow)->applyFromArray($style_header);
$sheet->mergeCells('E'.$NewRow.':E'.$NextRow);

$sheet->setCellValue('F'.$NewRow, 'No PO');
$sheet->getStyle('F'.$NewRow.':F'.$NextRow)->applyFromArray($style_header);
$sheet->mergeCells('F'.$NewRow.':F'.$NextRow);

$sheet->setCellValue('G'.$NewRow, 'Total Sertifikat');
$sheet->getStyle('G'.$NewRow.':G'.$NextRow)->applyFromArray($style_header);
$sheet->mergeCells('G'.$NewRow.':G'.$NextRow);

$NewRow	= $NextRow;


if($rows_data){
	$loop	=0;
	
	foreach($rows_data as $key=>$val){
		$loop++;
		$NewRow++;
		
		$sheet->setCellValue('A'.$NewRow, $loop);
		$sheet->getStyle('A'.$NewRow)->applyFromArray($styleArray1);
		
		$sheet->setCellValue('B'.$NewRow, $val['no_so']);
		$sheet->getStyle('B'.$NewRow)->applyFromArray($styleArray1);
		
		$sheet->setCellValue('C'.$NewRow, date('d M Y',strtotime($val['tgl_so'])));
		$sheet->getStyle('C'.$NewRow)->applyFromArray($styleArray1);
		
		$sheet->setCellValue('D'.$NewRow, $val['customer_name']);
		$sheet->getStyle('D'.$NewRow)->applyFromArray($styleArray1);
		
		$sheet->setCellValue('E'.$NewRow, $val['quotation_nomor']);
		$sheet->getStyle('E'.$NewRow)->applyFromArray($styleArray1);
		
		$sheet->setCellValue('F'.$NewRow, $val['pono']);
		$sheet->getStyle('F'.$NewRow)->applyFromArray($styleArray1);
		
		
		$sheet->setCellValue('G'.$NewRow, number_format($val['tot_qty']));
		$sheet->getStyle('G'.$NewRow)->applyFromArray($styleArray1);
		
	}
}


$sheet->setTitle('Out BAST Sertifikat');       
$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
ob_end_clean();
//sesuaikan headernya 
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
header("Cache-Control: no-store, no-cache, must-revalidate");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
//ubah nama file saat diunduh
header('Content-Disposition: attachment;filename="Laporan_Outs_BAST_Sertifikat_'.date('YmdHis').'.xls"');
//unduh file
$objWriter->save("php://output");
exit;

?>