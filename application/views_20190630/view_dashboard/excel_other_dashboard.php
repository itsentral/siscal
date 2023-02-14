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
$sheet 	= $objPHPExcel->getActiveSheet();
$Row=1;
$NewRow	=$Row+1;
if($kategori==1){
	$file_name	= 'Laporan_Incomplete_PO_';
	$title_name	= 'Incomplete PO';
	$Arr_Data	= array(
		'nomor'			=> 'Quotation',
		'datet'			=> 'Tanggal Quotation',
		'customer_name'	=> 'Customer',
		'pic_name'		=> 'PIC',
		'pono'			=> 'No PO',
		'podate'		=> 'Tgl PO',
		'leadtime'		=> 'Leadtime (Hari)',
		'member_name'	=> 'Marketing'
	);
	$sheet->setCellValue('A'.$Row, 'Laporan Incomplete PO Quotation');
	$sheet->getStyle('A'.$Row.':I'.$NewRow)->applyFromArray($style_header);
	$sheet->mergeCells('A'.$Row.':I'.$NewRow);
}else if($kategori==2){
	$file_name	= 'Laporan_Ivoice_Late_Send_';
	$title_name	= 'Incomplete PO';
	$Arr_Data	= array(
		'invoice_no'	=> 'Invoice',
		'datet'			=> 'Tanggal Invoice',
		'customer_name'	=> 'Customer',
		'address'		=> 'Alamat',
		'grand_tot'		=> 'Total',
		'date_create'	=> 'Tgl Input',
		'leadtime'		=> 'Leadtime (Hari)'
	);
	$sheet->setCellValue('A'.$Row, 'Laporan Invoice Late Send');
	$sheet->getStyle('A'.$Row.':H'.$NewRow)->applyFromArray($style_header);
	$sheet->mergeCells('A'.$Row.':H'.$NewRow);
}else if($kategori==3){
	$file_name	= 'Laporan_Ivoice_Late_Follow_Up_1_';
	$title_name	= 'Follow Up 1';
	$Arr_Data	= array(
		'invoice_no'	=> 'Invoice',
		'datet'			=> 'Tanggal Invoice',
		'customer_name'	=> 'Customer',
		'address'		=> 'Alamat',
		'grand_tot'		=> 'Total',
		'receive_date'	=> 'Tgl Receive',
		'leadtime'		=> 'Leadtime (Hari)'
	);
	$sheet->setCellValue('A'.$Row, 'Laporan Invoice Follow Up 1');
	$sheet->getStyle('A'.$Row.':H'.$NewRow)->applyFromArray($style_header);
	$sheet->mergeCells('A'.$Row.':H'.$NewRow);
} else if($kategori==4){
	$file_name	= 'Laporan_Ivoice_Late_Follow_Up_2_';
	$title_name	= 'Follow Up 2';
	$Arr_Data	= array(
		'invoice_no'	=> 'Invoice',
		'datet'			=> 'Tanggal Invoice',
		'customer_name'	=> 'Customer',
		'address'		=> 'Alamat',
		'grand_tot'		=> 'Total',
		'date_follow_up'	=> 'Tgl Follow Up 1',
		'leadtime'		=> 'Leadtime (Hari)'
	);
	$sheet->setCellValue('A'.$Row, 'Laporan Invoice Follow Up 2');
	$sheet->getStyle('A'.$Row.':H'.$NewRow)->applyFromArray($style_header);
	$sheet->mergeCells('A'.$Row.':H'.$NewRow);
} else if($kategori==5 || $kategori==6 || $kategori=='8' || $kategori=='9' || $kategori=='10' || $kategori=='11' || $kategori=='12'){
	
	$Arr_Data	= array(
		'invoice_no'	=> 'Invoice',
		'datet'			=> 'Tanggal Invoice',
		'customer_name'	=> 'Customer',
		'receive_date'	=> 'Date Receive',
		'grand_tot'		=> 'Total Invoice',
		'total_payment'	=> 'Total Bayar',
		'hutang'		=> 'Total AR',
		'leadtime'		=> 'Leadtime (Hari)'
	);
	$file_name	= 'Laporan_Potential_Bad_Debt_';
	$title_name	= 'Potential Bad Debt';
	$sheet->setCellValue('A'.$Row, 'Laporan Potential Bad Debt');
	if($kategori==6){
		$file_name	= 'Laporan_Bad_Debt_';
		$title_name	= 'Bad Debt';
		$sheet->setCellValue('A'.$Row, 'Laporan Bad Debt');
	}else if($kategori==8){
		$file_name	= 'Laporan_Potential_Bad_Debt_PPH23_';
		$title_name	= 'Potential Bad Debt PPH 23';
		$sheet->setCellValue('A'.$Row, 'Laporan Potential Bad Debt PPH 23');
	}else if($kategori==9){
		$file_name	= 'Laporan_Bad_Debt_PPH23_';
		$title_name	= 'Bad Debt PPH 23';
		$sheet->setCellValue('A'.$Row, 'Laporan Bad Debt PPH 23');
	}else if($kategori==10){
		$file_name	= 'Laporan_Potential_Bad_Debt_PPN_';
		$title_name	= 'Potential Bad Debt PPN';
		$sheet->setCellValue('A'.$Row, 'Laporan Potential Bad Debt PPN');
	}else if($kategori==11){
		$file_name	= 'Laporan_Bad_Debt_PPN_';
		$title_name	= 'Bad Debt PPN';
		$sheet->setCellValue('A'.$Row, 'Laporan Bad Debt PPN');
	}else if($kategori==12){
		$file_name	= 'Laporan_Piutang_Minus_';
		$title_name	= 'Piutang Minus';
		$sheet->setCellValue('A'.$Row, 'Laporan Piutang Minus');
	}
	
	
	$sheet->getStyle('A'.$Row.':I'.$NewRow)->applyFromArray($style_header);
	$sheet->mergeCells('A'.$Row.':I'.$NewRow);
}  else if($kategori==7){
	
	$Arr_Data	= array(
		'no_so'				=> 'No SO',
		'tgl_so'			=> 'Tgl SO',
		'quotation_nomor'	=> 'Quotation',
		'pono'				=> 'No PO',
		'customer_name'		=> 'Customer',		
		'pic'				=> 'PIC',
		'address_inv'		=> 'Alamat Invoice',
		'address_sertifikat'=> 'Alamat Sertifikat',
		'address_send'		=> 'Alamat Kirim',
		'leadtime'			=> 'Leadtime (Hari)'
	);
	$file_name	= 'Laporan_SO_Late_Schedule';
	$title_name	= 'Potential SO Late Schedule';
	$sheet->setCellValue('A'.$Row, 'Laporan SO Late Schedule');	
	$sheet->getStyle('A'.$Row.':K'.$NewRow)->applyFromArray($style_header);
	$sheet->mergeCells('A'.$Row.':K'.$NewRow);
}  
 
$NewRow++;
$Mulai		= 1;
$Cols		= getColsChar($Mulai);
$sheet->setCellValue($Cols.$NewRow, 'No');
$sheet->getStyle($Cols.$NewRow)->applyFromArray($style_header);

foreach($Arr_Data as $keyF=>$valF){
	$Mulai++;
	$Cols		= getColsChar($Mulai);
	$sheet->setCellValue($Cols.$NewRow, $valF);
	$sheet->getStyle($Cols.$NewRow)->applyFromArray($style_header);
}

if($records){
	$loop	=0;
	$sekarang	= date('Y-m-d');
	foreach($records as $key=>$val){
		$loop++;
		$NewRow++;
		$Mulai		= 1;
		$Cols		= getColsChar($Mulai);
		$sheet->setCellValue($Cols.$NewRow, $loop);
		$sheet->getStyle($Cols.$NewRow)->applyFromArray($styleArray1);
		
		foreach($Arr_Data as $keyF=>$valF){
			$Mulai++;
			if(isset($val[$keyF]) && $val[$keyF]){
				$Nil_Data	= $val[$keyF];
			}else{
				$Nil_Data	= $val[0][$keyF];
			}
			
			$Cols		= getColsChar($Mulai);
			$sheet->setCellValue($Cols.$NewRow, $Nil_Data);
			$sheet->getStyle($Cols.$NewRow)->applyFromArray($styleArray1);
		}
		
	}
}


$sheet->setTitle($title_name);       
$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
ob_end_clean();
//sesuaikan headernya 
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
header("Cache-Control: no-store, no-cache, must-revalidate");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
//ubah nama file saat diunduh
header('Content-Disposition: attachment;filename="'.$file_name.date('YmdHis').'.xls"');
//unduh file
$objWriter->save("php://output");
exit;

/*
function getColsChar($colums){
	if($colums>26){
		$modCols = floor($colums/26);
		$ExCols = $modCols*26;
		$totCols = $colums-$ExCols;
		
		if($totCols==0){
			$modCols=$modCols-1;
			$totCols+=26;
		}
		
		$lets1 = getLetColsLetter($modCols);
		$lets2 = getLetColsLetter($totCols);
		return $letsi = $lets1.$lets2;
	}else{
		$lets = getLetColsLetter($colums);
		return $letsi = $lets;
	}
}

function getLetColsLetter($numbs){
// Palleng by jester
	switch($numbs){
		case 1:
		$Chars = 'A';
		break;
		case 2:
		$Chars = 'B';
		break;
		case 3:
		$Chars = 'C';
		break;
		case 4:
		$Chars = 'D';
		break;
		case 5:
		$Chars = 'E';
		break;
		case 6:
		$Chars = 'F';
		break;
		case 7:
		$Chars = 'G';
		break;
		case 8:
		$Chars = 'H';
		break;
		case 9:
		$Chars = 'I';
		break;
		case 10:
		$Chars = 'J';
		break;
		case 11:
		$Chars = 'K';
		break;
		case 12:
		$Chars = 'L';
		break;
		case 13:
		$Chars = 'M';
		break;
		case 14:
		$Chars = 'N';
		break;
		case 15:
		$Chars = 'O';
		break;
		case 16:
		$Chars = 'P';
		break;
		case 17:
		$Chars = 'Q';
		break;
		case 18:
		$Chars = 'R';
		break;
		case 19:
		$Chars = 'S';
		break;
		case 20:
		$Chars = 'T';
		break;
		case 21:
		$Chars = 'U';
		break;
		case 22:
		$Chars = 'V';
		break;
		case 23:
		$Chars = 'W';
		break;
		case 24:
		$Chars = 'X';
		break;
		case 25:
		$Chars = 'Y';
		break;
		case 26:
		$Chars = 'Z';
		break;
	}

	return $Chars;
}
*/

?>