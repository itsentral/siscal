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
$Cols2	= getColsChar(12);

$sheet->setCellValue($Cols.$Row, 'LAPORAN PENJUALAN SUBCON '.$periode_awal.' sd '.$periode_akhir);
$sheet->getStyle($Cols.$Row.':'.$Cols2.$NewRow)->applyFromArray($style_header);
$sheet->mergeCells($Cols.$Row.':'.$Cols2.$NewRow);


$Arr_Judul		= array(
	'nomor'				=> 'Quotation',
	'datet'				=> 'Datet',
	'customer_name'		=> 'Customer',
	'no_so'				=> 'No SO',
	'pono'				=> 'No PO',
	'cust_tool'			=> 'Tool',
	'range'				=> 'Range',
	'Qty'				=> 'Qty',
	'hpp'				=> 'Subcon Price',
	'price'				=> 'Cust Price',
	'discount'			=> 'Cust Disc (%)',
	'status'			=> 'Status'
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




if($records){
	$loop	=0;
	$sekarang	= date('Y-m-d');
	foreach($records as $key=>$val){
		$loop++;
		$NewRow++;
		
		$No_SO		='-';
		$Query_SO	="SELECT 
							head_so.no_so
						FROM
							letter_orders head_so
						INNER JOIN letter_order_details det_so ON head_so.id = det_so.letter_order_id
						WHERE
							head_so.sts_so NOT IN ('CNC', 'REV')
						AND det_so.quotation_detail_id = '".$val['id']."'
						GROUP BY
							head_so.id";
		$det_SO		= $this->db->query($Query_SO)->result();
		if($det_SO){
			$No_SO	='';
			foreach($det_SO as $keyS=>$valSO){
				if(!empty($No_SO))$No_SO	.=', ';
				$No_SO	.=$valSO->no_so;
			}
		}
		
		if($val['status'] === 'OPN'){
			$Kets		= 'OPEN';
		}else if($val['status'] === 'CNC'){
			$Kets		= 'CANCEL';
		}else if($val['status'] === 'FAL'){
			$Kets		= 'FAIL';
		}else if($val['status'] === 'REC'){
			$Kets		= 'DEAL';
		}else if($val['status'] === 'CLS'){
			$Kets		= 'CLOSE';
		}
		
		$Mulai_Col	= 0;
		
		$Mulai_Col++;
		$Cols	= getColsChar($Mulai_Col);
		$sheet->setCellValue($Cols.$NewRow, $loop);
		$sheet->getStyle($Cols.$NewRow)->applyFromArray($styleArray1);
		
		foreach($Arr_Judul as $keyJ=>$valJ){
			$Mulai_Col++;
			if($Mulai_Col === 5){
				$Nil_Cols	= $No_SO;
			}else if($Mulai_Col === 12){
				$Nil_Cols	= $Kets;
			}else{
				$Nil_Cols	= $val[$keyJ];
			}
			
			$Cols	= getColsChar($Mulai_Col);
			$sheet->setCellValue($Cols.$NewRow, $Nil_Cols);
			$sheet->getStyle($Cols.$NewRow)->applyFromArray($styleArray1);
		}
		
		
	}
}


$sheet->setTitle('Laporan Subcon');       
$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
ob_end_clean();
//sesuaikan headernya 
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
header("Cache-Control: no-store, no-cache, must-revalidate");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
//ubah nama file saat diunduh
header('Content-Disposition: attachment;filename="LAP_PENJ_SUBCON_'.date('YmdHis').'.xls"');
//unduh file
$objWriter->save("php://output");
exit;

?>