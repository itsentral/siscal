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

$sheet->setCellValue('A'.$Row, 'LAPORAN SO OUTSTANDING INVOICE');
$sheet->getStyle('A'.$Row.':M'.$NewRow)->applyFromArray($style_header);
$sheet->mergeCells('A'.$Row.':M'.$NewRow);



// header
$NewRow++;
$sheet->setCellValue('A'.$NewRow, 'No');
$sheet->getStyle('A'.$NewRow)->applyFromArray($style_header);

$sheet->setCellValue('B'.$NewRow, 'No SO');
$sheet->getStyle('B'.$NewRow)->applyFromArray($style_header);

$sheet->setCellValue('C'.$NewRow, 'Tgl SO');
$sheet->getStyle('C'.$NewRow)->applyFromArray($style_header);

$sheet->setCellValue('D'.$NewRow, 'Customer');
$sheet->getStyle('D'.$NewRow)->applyFromArray($style_header);

$sheet->setCellValue('E'.$NewRow, 'Quotation');
$sheet->getStyle('E'.$NewRow)->applyFromArray($style_header);

$sheet->setCellValue('F'.$NewRow, 'No PO');
$sheet->getStyle('F'.$NewRow)->applyFromArray($style_header);


$sheet->setCellValue('G'.$NewRow, 'DPP SO');
$sheet->getStyle('G'.$NewRow)->applyFromArray($style_header);

$sheet->setCellValue('H'.$NewRow, 'Insitu');
$sheet->getStyle('H'.$NewRow)->applyFromArray($style_header);

$sheet->setCellValue('I'.$NewRow, 'Akomodasi');
$sheet->getStyle('I'.$NewRow)->applyFromArray($style_header);

$sheet->setCellValue('J'.$NewRow, 'Total DPP');
$sheet->getStyle('J'.$NewRow)->applyFromArray($style_header);

$sheet->setCellValue('K'.$NewRow, 'PPN');
$sheet->getStyle('K'.$NewRow)->applyFromArray($style_header);

$sheet->setCellValue('L'.$NewRow, 'Total SO');
$sheet->getStyle('L'.$NewRow)->applyFromArray($style_header);

$sheet->setCellValue('M'.$NewRow, 'Marketing');
$sheet->getStyle('M'.$NewRow)->applyFromArray($style_header);

if($rows_data){
	$loop	=0;
	$sekarang	= date('Y-m-d');
	foreach($rows_data as $key=>$val){
		$loop++;
		$NewRow++;
		
		
		$Nilai_Dpp		= $val['total_so'];
		$Quot_DPP		= $val['grand_tot'] - $val['ppn'] - $val['total_akomodasi'] - $val['tot_insitu'];
		$Nil_Insitu		= $Nil_Akom 	= 0;
		$Flag_Insitu	= $val['flag_so_insitu'];
		$No_SO			= $val['no_so'];
		$First_SO		= $val['first_so'];
		if($Nilai_Dpp == $Quot_DPP || $No_SO==$First_SO){
			$Nil_Akom	= $val['total_akomodasi'];
			if($Flag_Insitu=='Y'){
				$Nil_Insitu	= $val['tot_insitu'];
			}
		}
		
		$Total			= $Nilai_Dpp + $Nil_Akom + $Nil_Insitu;
		$PPN			= 0;
		if($val['ppn'] > 0){
			$PPN		= round($Total * 0.1);
		}
		
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
		
		
		$sheet->setCellValue('G'.$NewRow, number_format($Nilai_Dpp));
		$sheet->getStyle('G'.$NewRow)->applyFromArray($styleArray1);
		
		$sheet->setCellValue('H'.$NewRow, number_format($Nil_Insitu));
		$sheet->getStyle('H'.$NewRow)->applyFromArray($styleArray1);
		
		$sheet->setCellValue('I'.$NewRow, number_format($Nil_Akom));
		$sheet->getStyle('I'.$NewRow)->applyFromArray($styleArray1);
		
		$sheet->setCellValue('J'.$NewRow, number_format($Total));
		$sheet->getStyle('J'.$NewRow)->applyFromArray($styleArray1);
		
		$sheet->setCellValue('K'.$NewRow, number_format($PPN));
		$sheet->getStyle('K'.$NewRow)->applyFromArray($styleArray1);
		
		$sheet->setCellValue('L'.$NewRow, number_format($PPN + $Total));
		$sheet->getStyle('L'.$NewRow)->applyFromArray($styleArray1);
			
		$sheet->setCellValue('M'.$NewRow, $val['member_name']);
		$sheet->getStyle('M'.$NewRow)->applyFromArray($styleArray1);
		
	}
}


$sheet->setTitle('SO Outs Invoice');       
$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
ob_end_clean();
//sesuaikan headernya 
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
header("Cache-Control: no-store, no-cache, must-revalidate");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
//ubah nama file saat diunduh
header('Content-Disposition: attachment;filename="Laporan_SO_Outs_Invoice'.date('YmdHis').'.xls"');
//unduh file
$objWriter->save("php://output");
exit;

?>