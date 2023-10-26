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
	$judul			="Report Certificate Unuploaded";
	$file_report	="Report_Certificate_Unuploaded";	
}
  
$Row=1;
$NewRow	=$Row+1;
$sheet 	= $objPHPExcel->getActiveSheet();

$sheet->setCellValue('A'.$Row, $judul);
$sheet->getStyle('A'.$Row.':H'.$NewRow)->applyFromArray($style_header);
$sheet->mergeCells('A'.$Row.':H'.$NewRow);

// header
$NewRow++;
$sheet->setCellValue('A'.$NewRow, 'No');
$sheet->getStyle('A'.$NewRow)->applyFromArray($style_header);

$sheet->setCellValue('B'.$NewRow, 'No SO');
$sheet->getStyle('B'.$NewRow)->applyFromArray($style_header);

$sheet->setCellValue('C'.$NewRow, 'Customer');
$sheet->getStyle('C'.$NewRow)->applyFromArray($style_header);

$sheet->setCellValue('D'.$NewRow, 'Tool Name');
$sheet->getStyle('D'.$NewRow)->applyFromArray($style_header);

$sheet->setCellValue('E'.$NewRow, 'Vendor');
$sheet->getStyle('E'.$NewRow)->applyFromArray($style_header);

$sheet->setCellValue('F'.$NewRow, 'Process Date');
$sheet->getStyle('F'.$NewRow)->applyFromArray($style_header);

$sheet->setCellValue('G'.$NewRow, 'Technician');
$sheet->getStyle('G'.$NewRow)->applyFromArray($style_header);

$sheet->setCellValue('H'.$NewRow, 'Late');
$sheet->getStyle('H'.$NewRow)->applyFromArray($style_header);



if($records){
	$loop	=0;
	$now		= date('Y-m-d');
	$sekarang	= date('Y-m-d',strtotime('-2 day',strtotime($now)));
	foreach($records as $key=>$val){
		$loop++;
		$NewRow++;
		$ambil_by	= (isset($val['name_teknisi']) && $val['name_teknisi'])?$val['name_teknisi']:'-';
		$labs		= ($val['labs']=='N')?'-':$val['labs'];
		$insitu		= ($val['insitu']=='N')?'-':$val['insitu'];
		$subcon		= ($val['subcon']=='N')?'-':$val['subcon'];
		if($tipe_late=='1'){
			$plan_ambil	= (isset($val['actual_process_date']) && $val['actual_process_date'])?$val['actual_process_date']:$val['plan_process_date'];						
			
		}
		$leadtime	= (strtotime($sekarang) - strtotime($plan_ambil)) / (60*60*24);
		
		$sheet->setCellValue('A'.$NewRow, $loop);
		$sheet->getStyle('A'.$NewRow)->applyFromArray($styleArray1);
		
		$sheet->setCellValue('B'.$NewRow, $val['no_so']);
		$sheet->getStyle('B'.$NewRow)->applyFromArray($styleArray1);
		
		$sheet->setCellValue('C'.$NewRow, $val['customer_name']);
		$sheet->getStyle('C'.$NewRow)->applyFromArray($styleArray1);
		
		$sheet->setCellValue('D'.$NewRow, $val['tool_name']);
		$sheet->getStyle('D'.$NewRow)->applyFromArray($styleArray1);
		
		$sheet->setCellValue('E'.$NewRow, $val['supplier_name']);
		$sheet->getStyle('E'.$NewRow)->applyFromArray($styleArray1);
		
		$sheet->setCellValue('F'.$NewRow, date('d M Y',strtotime($plan_ambil)));
		$sheet->getStyle('F'.$NewRow)->applyFromArray($styleArray1);
		
		$sheet->setCellValue('G'.$NewRow, $ambil_by);
		$sheet->getStyle('G'.$NewRow)->applyFromArray($styleArray1);
		
		$sheet->setCellValue('H'.$NewRow, $leadtime.' day');
		$sheet->getStyle('H'.$NewRow)->applyFromArray($styleArray1);
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