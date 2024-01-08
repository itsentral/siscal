<?php
$this->load->library("PHPExcel");
$objPHPExcel	= new PHPExcel();

$style_header = array(
	'borders' => array(
		'allborders' => array(
			'style' => PHPExcel_Style_Border::BORDER_THIN
		)
	),
	'fill' => array(
		'type' => PHPExcel_Style_Fill::FILL_SOLID,
		'color' => array('rgb' => '133680'),
	),
	'font' => array(
		'bold' => true,
		'color' => array('rgb'=> 'FFFFFF')
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

$nameCus = '';
if($rows_cust){
	$nameCus = $rows_cust->name;
}

$judul			= "DATA SURVEY CUSTOMER ".$nameCus;
$file_report	= "SURVEY CUSTOMER ".$nameCus;

$Row 	= 1;
$NewRow	= $Row + 1;
$sheet 	= $objPHPExcel->getActiveSheet();

$sheet->getRowDimension('3')->setRowHeight(25);
$sheet->getColumnDimension('A')->setWidth(82);
$sheet->getColumnDimension('B')->setWidth(60);
$sheet->getColumnDimension('C')->setWidth(60);
$sheet->getStyle('A')->getAlignment()->setWrapText(true);
$sheet->getStyle('B')->getAlignment()->setWrapText(true);
$sheet->getStyle('C')->getAlignment()->setWrapText(true);

$sheet->setCellValue('A' . $Row, $judul);
$sheet->getStyle('A' . $Row . ':C' . $NewRow)->applyFromArray($style_header);
$sheet->mergeCells('A' . $Row . ':C' . $NewRow);

// header
$NewRow++;
$sheet->setCellValue('A' . $NewRow, 'QUESTIONS');
$sheet->getStyle('A' . $NewRow)->applyFromArray($style_header);

$sheet->setCellValue('B' . $NewRow, 'ANSWERS');
$sheet->getStyle('B' . $NewRow)->applyFromArray($style_header);

$sheet->setCellValue('C' . $NewRow, 'DESCRIPTIONS');
$sheet->getStyle('C' . $NewRow)->applyFromArray($style_header);

if ($records) {
	$loop		= 0;
	$sekarang	= date('Y-m-d');
	foreach ($records as $key => $val) {
		$loop++;
		$NewRow++;
		$Kategori	= '-';

		$sheet->setCellValue('A' . $NewRow, $loop);
		$sheet->getStyle('A' . $NewRow)->applyFromArray($styleArray1);

		$sheet->setCellValue('B' . $NewRow, $val['tool_name']);
		$sheet->getStyle('B' . $NewRow)->applyFromArray($styleArray1);

		$sheet->setCellValue('C' . $NewRow, $val['qty_late']);
		$sheet->getStyle('C' . $NewRow)->applyFromArray($styleArray1);

	}
}

if($rows_detail){
	$Arr_Lable	= array(1=>'a','b','c','d','e','f','g','h','i','j');
	$loop		= 0;
	foreach($rows_detail as $KeyDetail=>$valDetail){
		$loop++;
		$NewRow++;
		$Code_Pertanyaan	= $valDetail->code_detail;
		$Urut_Pertanyaan	= $valDetail->question_no;
		$Text_Pertanyaan	= $valDetail->question;
		$Type_Pertanyaan 	= $valDetail->question_type;
		$Jawab_Original 	= $valDetail->choice_answer;
		
		
		$Jawab_Pertanyaan	= '';
		$Jawab_Descr		= '';
		$rows_Jawaban		= $this->db->get_where('crm_survey_answer_details',array('code_answer'=>$rows_answer->code_answer,'code_question'=>$Code_Pertanyaan))->row();

		if($rows_Jawaban){
			$sheet->setCellValue('A' . $NewRow, $rows_Jawaban->question);
			$sheet->getStyle('A' . $NewRow)->applyFromArray($styleArray1);

			$sheet->setCellValue('B' . $NewRow, $rows_Jawaban->answer);
			$sheet->getStyle('B' . $NewRow)->applyFromArray($styleArray1);

			$sheet->setCellValue('C' . $NewRow, $rows_Jawaban->descr);
			$sheet->getStyle('C' . $NewRow)->applyFromArray($styleArray1);
		}
		
	}
}


$sheet->setTitle('Survey Answer Customer');
$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
ob_end_clean();
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
header("Cache-Control: no-store, no-cache, must-revalidate");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="' . $file_report . '.xls"');
$objWriter->save("php://output");
exit;
