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

  
$judul			="PO Report ".$periode_awal." sd ".$periode_akhir;
$file_report	="PO_Report";	

  
$Row=1;
$NewRow	=$Row+1;
$sheet 	= $objPHPExcel->getActiveSheet();

$sheet->setCellValue('A'.$Row, $judul);
$sheet->getStyle('A'.$Row.':P'.$NewRow)->applyFromArray($style_header);
$sheet->mergeCells('A'.$Row.':P'.$NewRow);

$NewRow++;
$sheet->setCellValue('A'.$NewRow, 'No');
$sheet->getStyle('A'.$NewRow)->applyFromArray($style_header);

$sheet->setCellValue('B'.$NewRow, 'Nama Perusahaan');
$sheet->getStyle('B'.$NewRow)->applyFromArray($style_header);

$sheet->setCellValue('C'.$NewRow, 'Nomor Quotation');
$sheet->getStyle('C'.$NewRow)->applyFromArray($style_header);

$sheet->setCellValue('D'.$NewRow, 'Nilai PO');
$sheet->getStyle('D'.$NewRow)->applyFromArray($style_header);

$sheet->setCellValue('E'.$NewRow, 'Nilai Insitu');
$sheet->getStyle('E'.$NewRow)->applyFromArray($style_header);

$sheet->setCellValue('F'.$NewRow, 'Nilai Akomodasi');
$sheet->getStyle('F'.$NewRow)->applyFromArray($style_header);

$sheet->setCellValue('G'.$NewRow, 'Nilai Subcon');
$sheet->getStyle('G'.$NewRow)->applyFromArray($style_header);

$sheet->setCellValue('H'.$NewRow, 'Cust Fee');
$sheet->getStyle('H'.$NewRow)->applyFromArray($style_header);

$sheet->setCellValue('I'.$NewRow, 'Nilai Bersih');
$sheet->getStyle('I'.$NewRow)->applyFromArray($style_header);

$sheet->setCellValue('J'.$NewRow, 'Sales');
$sheet->getStyle('J'.$NewRow)->applyFromArray($style_header);

$sheet->setCellValue('K'.$NewRow, 'Status');
$sheet->getStyle('K'.$NewRow)->applyFromArray($style_header);

$sheet->setCellValue('L'.$NewRow, 'No SO');
$sheet->getStyle('L'.$NewRow)->applyFromArray($style_header);

$sheet->setCellValue('M'.$NewRow, 'Reff By');
$sheet->getStyle('M'.$NewRow)->applyFromArray($style_header);

$sheet->setCellValue('N'.$NewRow, 'Reff Name');
$sheet->getStyle('N'.$NewRow)->applyFromArray($style_header);

$sheet->setCellValue('O'.$NewRow, 'Reff Phone');
$sheet->getStyle('O'.$NewRow)->applyFromArray($style_header);

$sheet->setCellValue('P'.$NewRow, 'Tgl PO');
$sheet->getStyle('P'.$NewRow)->applyFromArray($style_header);



if($records){
	
	foreach($records as $key=>$vals){
		$loop++;
		$NewRow++;
		
		
		
		$sheet->setCellValue('A'.$NewRow, $loop);
		$sheet->getStyle('A'.$NewRow)->applyFromArray($styleArray1);
		
		$sheet->setCellValue('B'.$NewRow, $vals[customer_name]);
		$sheet->getStyle('B'.$NewRow)->applyFromArray($styleArray1);
		
		$sheet->setCellValue('C'.$NewRow, $vals['nomor']);
		$sheet->getStyle('C'.$NewRow)->applyFromArray($styleArray1);
		
		
		$sheet->setCellValue('D'.$NewRow, number_format($vals['grand_tot']));
		$sheet->getStyle('D'.$NewRow)->applyFromArray($styleArray1);
		
		$sheet->setCellValue('E'.$NewRow, number_format($vals[total_insitu]));
		$sheet->getStyle('E'.$NewRow)->applyFromArray($styleArray1);
		
		$sheet->setCellValue('F'.$NewRow, number_format($vals[total_akomodasi]));
		$sheet->getStyle('F'.$NewRow)->applyFromArray($styleArray1);
		
		$sheet->setCellValue('G'.$NewRow, number_format($vals['total_subcon']));
		$sheet->getStyle('G'.$NewRow)->applyFromArray($styleArray1);
		
		$sheet->setCellValue('H'.$NewRow, number_format($vals['customer_fee']));
		$sheet->getStyle('H'.$NewRow)->applyFromArray($styleArray1);
			
		$sheet->setCellValue('I'.$NewRow, number_format($vals['nilai_akhir']));
		$sheet->getStyle('I'.$NewRow)->applyFromArray($styleArray1);
		
		$sheet->setCellValue('J'.$NewRow, $vals['member_name']);
		$sheet->getStyle('J'.$NewRow)->applyFromArray($styleArray1);
			
		$sheet->setCellValue('K'.$NewRow, $vals['keterangan']);
		$sheet->getStyle('K'.$NewRow)->applyFromArray($styleArray1);
		
		$sheet->setCellValue('L'.$NewRow, $vals['no_so']);
		$sheet->getStyle('L'.$NewRow)->applyFromArray($styleArray1);
		
		$sheet->setCellValue('M'.$NewRow, $vals['reference_by']);
		$sheet->getStyle('M'.$NewRow)->applyFromArray($styleArray1);
		
		$sheet->setCellValue('N'.$NewRow, $vals['reference_name']);
		$sheet->getStyle('N'.$NewRow)->applyFromArray($styleArray1);
		
		$sheet->setCellValue('O'.$NewRow, $vals['reference_phone']);
		$sheet->getStyle('O'.$NewRow)->applyFromArray($styleArray1);
		
		$sheet->setCellValue('P'.$NewRow, date('d-m-Y',strtotime($vals['podate'])));
		$sheet->getStyle('P'.$NewRow)->applyFromArray($styleArray1);
		
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