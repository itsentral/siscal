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

$sheet->setCellValue('A'.$Row, 'LAPORAN DETAIL OUTSTANDING BAST SERTIFIKAT');
$sheet->getStyle('A'.$Row.':L'.$NewRow)->applyFromArray($style_header);
$sheet->mergeCells('A'.$Row.':L'.$NewRow);



// header
$NewRow++;
$NextRow	= $NewRow + 1;
$sheet->setCellValue('A'.$NewRow, 'No');
$sheet->getStyle('A'.$NewRow.':A'.$NextRow)->applyFromArray($style_header);
$sheet->mergeCells('A'.$NewRow.':A'.$NextRow);

$sheet->setCellValue('B'.$NewRow, 'Kode Alat');
$sheet->getStyle('B'.$NewRow.':B'.$NextRow)->applyFromArray($style_header);
$sheet->mergeCells('B'.$NewRow.':B'.$NextRow);

$sheet->setCellValue('C'.$NewRow, 'Nama Alat');
$sheet->getStyle('C'.$NewRow.':C'.$NextRow)->applyFromArray($style_header);
$sheet->mergeCells('C'.$NewRow.':C'.$NextRow);

$sheet->setCellValue('D'.$NewRow, 'No SO');
$sheet->getStyle('D'.$NewRow.':D'.$NextRow)->applyFromArray($style_header);
$sheet->mergeCells('D'.$NewRow.':D'.$NextRow);

$sheet->setCellValue('E'.$NewRow, 'No Quotation');
$sheet->getStyle('E'.$NewRow.':E'.$NextRow)->applyFromArray($style_header);
$sheet->mergeCells('E'.$NewRow.':E'.$NextRow);

$sheet->setCellValue('F'.$NewRow, 'Customer');
$sheet->getStyle('F'.$NewRow.':F'.$NextRow)->applyFromArray($style_header);
$sheet->mergeCells('F'.$NewRow.':F'.$NextRow);

$sheet->setCellValue('G'.$NewRow, 'Kategori');
$sheet->getStyle('G'.$NewRow.':G'.$NextRow)->applyFromArray($style_header);
$sheet->mergeCells('G'.$NewRow.':G'.$NextRow);

$sheet->setCellValue('H'.$NewRow, 'Tgl Kalibrasi');
$sheet->getStyle('H'.$NewRow.':H'.$NextRow)->applyFromArray($style_header);
$sheet->mergeCells('H'.$NewRow.':H'.$NextRow);

$sheet->setCellValue('I'.$NewRow, 'Teknisi');
$sheet->getStyle('I'.$NewRow.':I'.$NextRow)->applyFromArray($style_header);
$sheet->mergeCells('I'.$NewRow.':I'.$NextRow);


$sheet->setCellValue('J'.$NewRow, 'No Sertifikat');
$sheet->getStyle('J'.$NewRow.':J'.$NextRow)->applyFromArray($style_header);
$sheet->mergeCells('J'.$NewRow.':J'.$NextRow);

$sheet->setCellValue('K'.$NewRow, 'Valid Until');
$sheet->getStyle('K'.$NewRow.':K'.$NextRow)->applyFromArray($style_header);
$sheet->mergeCells('K'.$NewRow.':K'.$NextRow);

$sheet->setCellValue('L'.$NewRow, 'No Invoice');
$sheet->getStyle('L'.$NewRow.':L'.$NextRow)->applyFromArray($style_header);
$sheet->mergeCells('L'.$NewRow.':L'.$NextRow);

$NewRow	= $NextRow;


if($rows_data){
	$loop	=0;
	
	foreach($rows_data as $key=>$val){
		$loop++;
		$NewRow++;
		if($val['labs']=='Y'){
			$Kategori	= 'Labs';
		}else if($val['subcon']=='Y'){
			$Kategori	= 'Subcon';
		}else{
			if($val['supplier_id']=='COMP-001'){
				$Kategori	= 'Insitu';
			}else{
				$Kategori	= 'Insitu Subcon';
			}
		}
		$Tgl_Kal	= '-';
		if($val['actual_process_date']){
			$Tgl_Kal	= date('d M Y',strtotime($val['actual_process_date']));
		}
		$sheet->setCellValue('A'.$NewRow, $loop);
		$sheet->getStyle('A'.$NewRow)->applyFromArray($styleArray1);
		
		$sheet->setCellValue('B'.$NewRow, $val['tool_id']);
		$sheet->getStyle('B'.$NewRow)->applyFromArray($styleArray1);
		
		$sheet->setCellValue('C'.$NewRow, $val['tool_name']);
		$sheet->getStyle('C'.$NewRow)->applyFromArray($styleArray1);
		
		$sheet->setCellValue('D'.$NewRow, $val['no_so']);
		$sheet->getStyle('D'.$NewRow)->applyFromArray($styleArray1);
		
		$sheet->setCellValue('E'.$NewRow, $val['quotation_nomor']);
		$sheet->getStyle('E'.$NewRow)->applyFromArray($styleArray1);
		
		$sheet->setCellValue('F'.$NewRow, $val['customer_name']);
		$sheet->getStyle('F'.$NewRow)->applyFromArray($styleArray1);
		
		
		$sheet->setCellValue('G'.$NewRow, $Kategori);
		$sheet->getStyle('G'.$NewRow)->applyFromArray($styleArray1);
		
		$sheet->setCellValue('H'.$NewRow, $Tgl_Kal);
		$sheet->getStyle('H'.$NewRow)->applyFromArray($styleArray1);
		
		$sheet->setCellValue('I'.$NewRow, $val['name_teknisi']);
		$sheet->getStyle('I'.$NewRow)->applyFromArray($styleArray1);
		
		$sheet->setCellValue('J'.$NewRow, $val['no_sertifikat']);
		$sheet->getStyle('J'.$NewRow)->applyFromArray($styleArray1);
		
		$sheet->setCellValue('K'.$NewRow, date('d F Y',strtotime($val['valid_until'])));
		$sheet->getStyle('K'.$NewRow)->applyFromArray($styleArray1);
		
		$sheet->setCellValue('L'.$NewRow, $val['invoice_no']);
		$sheet->getStyle('L'.$NewRow)->applyFromArray($styleArray1);
		
	}
}


$sheet->setTitle('Detail Out BAST');       
$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
ob_end_clean();
//sesuaikan headernya 
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
header("Cache-Control: no-store, no-cache, must-revalidate");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
//ubah nama file saat diunduh
header('Content-Disposition: attachment;filename="Laporan_Detail_Outs_BAST_Sertifikat_'.date('YmdHis').'.xls"');
//unduh file
$objWriter->save("php://output");
exit;

?>