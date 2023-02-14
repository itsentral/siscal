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

$sheet->setCellValue('A'.$Row, 'LAPORAN INVOICE PLAN PAYMENT');
$sheet->getStyle('A'.$Row.':L'.$NewRow)->applyFromArray($style_header);
$sheet->mergeCells('A'.$Row.':L'.$NewRow);



// header
$NewRow++;
$sheet->setCellValue('A'.$NewRow, 'No');
$sheet->getStyle('A'.$NewRow)->applyFromArray($style_header);

$sheet->setCellValue('B'.$NewRow, 'No Invoice');
$sheet->getStyle('B'.$NewRow)->applyFromArray($style_header);

$sheet->setCellValue('C'.$NewRow, 'Tgl Invoice');
$sheet->getStyle('C'.$NewRow)->applyFromArray($style_header);

$sheet->setCellValue('D'.$NewRow, 'Customer');
$sheet->getStyle('D'.$NewRow)->applyFromArray($style_header);

$sheet->setCellValue('E'.$NewRow, 'Alamat');
$sheet->getStyle('E'.$NewRow)->applyFromArray($style_header);

$sheet->setCellValue('F'.$NewRow, 'DPP');
$sheet->getStyle('F'.$NewRow)->applyFromArray($style_header);


$sheet->setCellValue('G'.$NewRow, 'PPN');
$sheet->getStyle('G'.$NewRow)->applyFromArray($style_header);

$sheet->setCellValue('H'.$NewRow, 'PPH 23');
$sheet->getStyle('H'.$NewRow)->applyFromArray($style_header);

$sheet->setCellValue('I'.$NewRow, 'Total Invoice');
$sheet->getStyle('I'.$NewRow)->applyFromArray($style_header);

$sheet->setCellValue('J'.$NewRow, 'Total Bayar');
$sheet->getStyle('J'.$NewRow)->applyFromArray($style_header);

$sheet->setCellValue('K'.$NewRow, 'Piutang');
$sheet->getStyle('K'.$NewRow)->applyFromArray($style_header);

$sheet->setCellValue('L'.$NewRow, 'Tgl Plan Bayar');
$sheet->getStyle('L'.$NewRow)->applyFromArray($style_header);

if($rows_data){
	$loop	=0;
	$sekarang	= date('Y-m-d');
	foreach($rows_data as $key=>$val){
		$loop++;
		$NewRow++;
		
		
		$Total_Inv		= $val['grand_tot'];
		$Total_Bayar	= $val['total_payment'];
		$Piutang		= $Total_Inv - $Total_Bayar;
		
		$sheet->setCellValue('A'.$NewRow, $loop);
		$sheet->getStyle('A'.$NewRow)->applyFromArray($styleArray1);
		
		$sheet->setCellValue('B'.$NewRow, $val['invoice_no']);
		$sheet->getStyle('B'.$NewRow)->applyFromArray($styleArray1);
		
		$sheet->setCellValue('C'.$NewRow, date('d M Y',strtotime($val['datet'])));
		$sheet->getStyle('C'.$NewRow)->applyFromArray($styleArray1);
		
		$sheet->setCellValue('D'.$NewRow, $val['customer_name']);
		$sheet->getStyle('D'.$NewRow)->applyFromArray($styleArray1);
		
		$sheet->setCellValue('E'.$NewRow, $val['address']);
		$sheet->getStyle('E'.$NewRow)->applyFromArray($styleArray1);
		
		$sheet->setCellValue('F'.$NewRow, number_format($val['total_dpp']));
		$sheet->getStyle('F'.$NewRow)->applyFromArray($styleArray1);
		
		
		$sheet->setCellValue('G'.$NewRow, number_format($val['ppn']));
		$sheet->getStyle('G'.$NewRow)->applyFromArray($styleArray1);
		
		$sheet->setCellValue('H'.$NewRow, number_format($val['pph23']));
		$sheet->getStyle('H'.$NewRow)->applyFromArray($styleArray1);
		
		$sheet->setCellValue('I'.$NewRow, number_format($Total_Inv));
		$sheet->getStyle('I'.$NewRow)->applyFromArray($styleArray1);
		
		$sheet->setCellValue('J'.$NewRow, number_format($Total_Bayar));
		$sheet->getStyle('J'.$NewRow)->applyFromArray($styleArray1);
		
		$sheet->setCellValue('K'.$NewRow, number_format($Piutang));
		$sheet->getStyle('K'.$NewRow)->applyFromArray($styleArray1);
		
		$sheet->setCellValue('L'.$NewRow, date('d F Y',strtotime($val['plan_payment'])));
		$sheet->getStyle('L'.$NewRow)->applyFromArray($styleArray1);
		
	}
}


$sheet->setTitle('Plan Payment Inv');       
$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
ob_end_clean();
//sesuaikan headernya 
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
header("Cache-Control: no-store, no-cache, must-revalidate");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
//ubah nama file saat diunduh
header('Content-Disposition: attachment;filename="Laporan_Plan_Paymnet_Invoice_'.date('YmdHis').'.xls"');
//unduh file
$objWriter->save("php://output");
exit;

?>