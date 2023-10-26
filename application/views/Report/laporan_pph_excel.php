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

$Cols	= getColsChar(1);
$Cols2	= getColsChar(11);

$sheet->setCellValue($Cols.$Row, 'LAPORAN PPH 23 '.$periode_awal.' sd '.$periode_akhir);
$sheet->getStyle($Cols.$Row.':'.$Cols2.$NewRow)->applyFromArray($style_header);
$sheet->mergeCells($Cols.$Row.':'.$Cols2.$NewRow);


$Arr_Judul		= array(
	'no_reff'			=> 'No Invoice',
	'inv_date'			=> 'Tgl Invoice',
	'customer'			=> 'Customer',
	'dpp'				=> 'DPP',
	'pph'				=> 'PPH 23',
	'no_bukti_potong'	=> 'Bukti Potong',
	'tgl_bukti_potong'	=> 'Tgl Bukti Potong',
	'jurnalid'			=> 'Jurnal',
	'tgl_jurnal'		=> 'Tgl Jurnal',
	'kredit'			=> 'Nil Jurnal'
);

// header
$NewRow++;
$NextRow	= $NewRow + 1;
$sheet->setCellValue('A'.$NewRow, 'No');
$sheet->getStyle('A'.$NewRow)->applyFromArray($style_header);

$Mulai_Col		= 1;
foreach($Arr_Judul as $keyJ=>$valJ){
	$Mulai_Col++;
	$Cols	= getColsChar($Mulai_Col);
	$sheet->setCellValue($Cols.$NewRow, $valJ);
	$sheet->getStyle($Cols.$NewRow)->applyFromArray($style_header);
}




if($rows_data){
	$loop	=0;
	$sekarang	= date('Y-m-d');
	foreach($rows_data as $key=>$val){
		$loop++;
		$NewRow++;
		$Mulai_Col	= 0;
		
		$Mulai_Col++;
		$Cols	= getColsChar($Mulai_Col);
		$sheet->setCellValue($Cols.$NewRow, $loop);
		$sheet->getStyle($Cols.$NewRow)->applyFromArray($styleArray1);
		
		foreach($Arr_Judul as $keyJ=>$valJ){
			$Mulai_Col++;
			$Cols	= getColsChar($Mulai_Col);
			$sheet->setCellValue($Cols.$NewRow, $val[$keyJ]);
			$sheet->getStyle($Cols.$NewRow)->applyFromArray($styleArray1);
		}
		
		
	}
}


$sheet->setTitle('Laporan PPPH 23');       
$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
ob_end_clean();
//sesuaikan headernya 
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
header("Cache-Control: no-store, no-cache, must-revalidate");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
//ubah nama file saat diunduh
header('Content-Disposition: attachment;filename="Laporan_PPH23_'.date('YmdHis').'.xls"');
//unduh file
$objWriter->save("php://output");
exit;

?>