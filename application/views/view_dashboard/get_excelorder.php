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

 
$Row=1;
$NewRow	=$Row+1;
$sheet 	= $objPHPExcel->getActiveSheet();
if($kategori=='1'){
	$Kolom		= 'O';
	$Title		= 'Laporan SO';
	$FileName	= 'Laporan_SO_';
}else if($kategori=='2'){
	$Kolom		= 'I';
	$Title		= 'Cancel SO';
	$FileName	= 'Laporan_Cancel_SO_';
}
$sheet->setCellValue('A'.$Row, $Judul);
$sheet->getStyle('A'.$Row.':'.$Kolom.$NewRow)->applyFromArray($style_header);
$sheet->mergeCells('A'.$Row.':'.$Kolom.$NewRow);

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

if($kategori==1){
	$sheet->setCellValue('G'.$NewRow, 'Total SO');
	$sheet->getStyle('G'.$NewRow)->applyFromArray($style_header);

	$sheet->setCellValue('H'.$NewRow, 'Subcon');
	$sheet->getStyle('H'.$NewRow)->applyFromArray($style_header);

	$sheet->setCellValue('I'.$NewRow, 'Insitu');
	$sheet->getStyle('I'.$NewRow)->applyFromArray($style_header);

	$sheet->setCellValue('J'.$NewRow, 'Akomodasi');
	$sheet->getStyle('J'.$NewRow)->applyFromArray($style_header);

	$sheet->setCellValue('K'.$NewRow, 'Cust Fee');
	$sheet->getStyle('K'.$NewRow)->applyFromArray($style_header);

	$sheet->setCellValue('L'.$NewRow, 'Net SO');
	$sheet->getStyle('L'.$NewRow)->applyFromArray($style_header);

	$sheet->setCellValue('M'.$NewRow, 'Marketing');
	$sheet->getStyle('M'.$NewRow)->applyFromArray($style_header);

	$sheet->setCellValue('N'.$NewRow, 'Tipe');
	$sheet->getStyle('N'.$NewRow)->applyFromArray($style_header);

	$sheet->setCellValue('O'.$NewRow, 'Referensi');
	$sheet->getStyle('O'.$NewRow)->applyFromArray($style_header);
}else if($kategori=='2'){
	$sheet->setCellValue('G'.$NewRow, 'Cancel Date');
	$sheet->getStyle('G'.$NewRow)->applyFromArray($style_header);

	$sheet->setCellValue('H'.$NewRow, 'Alasan');
	$sheet->getStyle('H'.$NewRow)->applyFromArray($style_header);

	$sheet->setCellValue('I'.$NewRow, 'Marketing');
	$sheet->getStyle('I'.$NewRow)->applyFromArray($style_header);
}


if($records){
	$loop	=0;
	$sekarang	= date('Y-m-d');
	foreach($records as $key=>$val){
		$loop++;
		$NewRow++;
		if($kategori==1){
			$Tgl_PO			= $val['podate'];
			$Tgl_Compare	= $val['first_so_date'];
			$Jenis_Ref		= '-';						
			if($Tgl_Compare !='' && $Tgl_Compare !='0000-00-00' && $Tgl_Compare !='1970-01-01'){
				$Beda		=(strtotime($Tgl_PO) - strtotime($Tgl_Compare)) / (60*60*24);
				if($Beda > 365){
					$Jenis_Ref		= 'Repeat';
				}else{
					$Jenis_Ref		= 'New';
				}
			}
		}
		$sheet->setCellValue('A'.$NewRow, $loop);
		$sheet->getStyle('A'.$NewRow)->applyFromArray($styleArray1);
		
		$sheet->setCellValue('B'.$NewRow, $val['no_so']);
		$sheet->getStyle('B'.$NewRow)->applyFromArray($styleArray1);
		
		$sheet->setCellValue('C'.$NewRow, date('d M Y',strtotime($val[tgl_so])));
		$sheet->getStyle('C'.$NewRow)->applyFromArray($styleArray1);
		
		$sheet->setCellValue('D'.$NewRow, $val['customer_name']);
		$sheet->getStyle('D'.$NewRow)->applyFromArray($styleArray1);
		
		$sheet->setCellValue('E'.$NewRow, $val['quotation_nomor']);
		$sheet->getStyle('E'.$NewRow)->applyFromArray($styleArray1);
		
		$sheet->setCellValue('F'.$NewRow, $val[pono]);
		$sheet->getStyle('F'.$NewRow)->applyFromArray($styleArray1);
		
		if($kategori==1){
			$sheet->setCellValue('G'.$NewRow, number_format($val['so_total']));
			$sheet->getStyle('G'.$NewRow)->applyFromArray($styleArray1);
			
			$sheet->setCellValue('H'.$NewRow, number_format($val['subcon_so']));
			$sheet->getStyle('H'.$NewRow)->applyFromArray($styleArray1);
			
			$sheet->setCellValue('I'.$NewRow, number_format($val['insitu']));
			$sheet->getStyle('I'.$NewRow)->applyFromArray($styleArray1);
			
			$sheet->setCellValue('J'.$NewRow, number_format($val['akomodasi']));
			$sheet->getStyle('J'.$NewRow)->applyFromArray($styleArray1);
			
			$sheet->setCellValue('K'.$NewRow, number_format($val['cust_fee']));
			$sheet->getStyle('K'.$NewRow)->applyFromArray($styleArray1);
			
			$sheet->setCellValue('L'.$NewRow, number_format($val['total_net']));
			$sheet->getStyle('L'.$NewRow)->applyFromArray($styleArray1);
				
			$sheet->setCellValue('M'.$NewRow, $val['member_name']);
			$sheet->getStyle('M'.$NewRow)->applyFromArray($styleArray1);
			
			$sheet->setCellValue('N'.$NewRow, $Jenis_Ref);
			$sheet->getStyle('N'.$NewRow)->applyFromArray($styleArray1);
			
			$sheet->setCellValue('O'.$NewRow, $val['reference_by']);
			$sheet->getStyle('O'.$NewRow)->applyFromArray($styleArray1);
		}else if($kategori==2){
			$sheet->setCellValue('G'.$NewRow, date('d M Y H:i',strtotime($val['cancel_date'])));
			$sheet->getStyle('G'.$NewRow)->applyFromArray($styleArray1);
			
			$sheet->setCellValue('H'.$NewRow, $val['reason']);
			$sheet->getStyle('H'.$NewRow)->applyFromArray($styleArray1);
			
			$sheet->setCellValue('I'.$NewRow, $val['member_name']);
			$sheet->getStyle('I'.$NewRow)->applyFromArray($styleArray1);
		}
	}
}


$sheet->setTitle($Title);       
$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
ob_end_clean();
//sesuaikan headernya 
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
header("Cache-Control: no-store, no-cache, must-revalidate");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
//ubah nama file saat diunduh
header('Content-Disposition: attachment;filename="'.$FileName.date('YmdHis').'.xls"');
//unduh file
$objWriter->save("php://output");
exit;
?>