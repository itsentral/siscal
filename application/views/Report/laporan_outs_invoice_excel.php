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
	foreach($rows_data as $key=>$row){
		$loop++;
		$NewRow++;
		
		
		$No_SO			= $row['no_so'];
		$Tgl_SO			= $row['tgl_so'];
		$Quot_ID		= $row['quotation_id'];
		$Nocust			= $row['customer_id'];
		$Customer		= $row['customer_name'];
		$Cust_fee		= ($row['success_fee'] > 0)?$row['success_fee']:0;
		$No_PO			= $row['pono'];
		$Marketing		= $row['member_name'];
		$Total_Quot		= $row['grand_tot'];
		$Net_Quot		= $row['quot_net'];
		$PPN			= $row['ppn'];
		$Insitu			= $row['total_insitu'];
		$Akomodasi		= $row['total_akomodasi'];
		$Total_After	= $row['total_dpp'];
		$Total_Alat		= $row['total_alat'];
		
		## AMBIL TGL PERTAMA SO ##
		$First_SO		='';
		$Qry_First		= "SELECT
								no_so
							FROM
								letter_orders
							WHERE
								quotation_id = '".$Quot_ID."'
							AND tgl_so <= '".$Tgl_SO."'
							AND sts_so NOT IN ('REV', 'CNC')
							ORDER BY
								tgl_so ASC
							LIMIT 1";
		$det_First		= $this->db->query($Qry_First)->result();
		if($det_First){
			$First_SO	= $det_First[0]->no_so;
		}
		
		## AMBIL JUMLAH SO ##			
		$Subcon_SO		= $row['total_subcon'];
		$Total_SO		= $row['total_so'];
		
		if($Total_Alat <= 0 || $Total_Alat == '' || $Total_Alat ==  null){
			$Qry_SUM_SO		= "SELECT
									SUM(
										ROUND(
											(
												100 -
												IF (
													quot_det.discount > 0,
													quot_det.discount,
													0
												)
											) * (det_so.qty * quot_det.price) / 100
										)
									) AS total_so,
									SUM(
										IF (
											det_so.supplier_id <> 'COMP-001',
											det_so.qty * quot_det.hpp,
											0
										)
									) AS subcon_so
								FROM
									letter_order_details det_so
								INNER JOIN quotation_details quot_det ON det_so.quotation_detail_id = quot_det.id
								WHERE
									det_so.letter_order_id = '".$row['id']."'";
			$det_SUM		= $this->db->query($Qry_SUM_SO)->result();
			if($det_SUM){
				$Subcon_SO	= $det_SUM[0]->subcon_so;
				$Total_SO	= $det_SUM[0]->total_so;
			}
		}
		
		$Insitu_SO		= $Akom_SO	=0;
		if($First_SO === $No_SO){
			if($row['flag_so_insitu'] === 'Y'){
				$Insitu_SO	= $Insitu;
			}
			
			$Akom_SO		= $Akomodasi;
		}
		
		$Total_Real_SO	= $Total_SO + $Akom_SO + $Insitu_SO;
		$PPN_SO			= 0;
		if($PPN > 0){
			$PPN_SO		= round($Total_Real_SO  * 0.1);
		}
		
		$Mulai_Col	= 0;
		
		
		
		$Mulai_Col++;		
		$Cols	= getColsChar($Mulai_Col);
		$sheet->setCellValue($Cols.$NewRow, $loop);
		$sheet->getStyle($Cols.$NewRow)->applyFromArray($styleArray1);
		
		$Mulai_Col++;		
		$Cols	= getColsChar($Mulai_Col);
		$sheet->setCellValue($Cols.$NewRow, $No_SO);
		$sheet->getStyle($Cols.$NewRow)->applyFromArray($styleArray1);
		
		$Mulai_Col++;		
		$Cols	= getColsChar($Mulai_Col);
		$sheet->setCellValue($Cols.$NewRow, date('d M Y',strtotime($Tgl_SO)));
		$sheet->getStyle($Cols.$NewRow)->applyFromArray($styleArray1);
		
		$Mulai_Col++;		
		$Cols	= getColsChar($Mulai_Col);
		$sheet->setCellValue($Cols.$NewRow, $Customer);
		$sheet->getStyle($Cols.$NewRow)->applyFromArray($styleArray1);
		
		$Mulai_Col++;		
		$Cols	= getColsChar($Mulai_Col);
		$sheet->setCellValue($Cols.$NewRow, $row['quotation_nomor']);
		$sheet->getStyle($Cols.$NewRow)->applyFromArray($styleArray1);
		
		$Mulai_Col++;		
		$Cols	= getColsChar($Mulai_Col);
		$sheet->setCellValue($Cols.$NewRow, $No_PO);
		$sheet->getStyle($Cols.$NewRow)->applyFromArray($styleArray1);
		
		$Mulai_Col++;		
		$Cols	= getColsChar($Mulai_Col);
		$sheet->setCellValue($Cols.$NewRow, number_format($Total_SO));
		$sheet->getStyle($Cols.$NewRow)->applyFromArray($styleArray1);
		
		
		$Mulai_Col++;		
		$Cols	= getColsChar($Mulai_Col);
		$sheet->setCellValue($Cols.$NewRow, number_format($Insitu_SO));
		$sheet->getStyle($Cols.$NewRow)->applyFromArray($styleArray1);
		
		$Mulai_Col++;		
		$Cols	= getColsChar($Mulai_Col);
		$sheet->setCellValue($Cols.$NewRow, number_format($Akom_SO));
		$sheet->getStyle($Cols.$NewRow)->applyFromArray($styleArray1);
		
		$Mulai_Col++;		
		$Cols	= getColsChar($Mulai_Col);
		$sheet->setCellValue($Cols.$NewRow, number_format($Total_Real_SO));
		$sheet->getStyle($Cols.$NewRow)->applyFromArray($styleArray1);
		
		$Mulai_Col++;		
		$Cols	= getColsChar($Mulai_Col);
		$sheet->setCellValue($Cols.$NewRow, number_format($PPN_SO));
		$sheet->getStyle($Cols.$NewRow)->applyFromArray($styleArray1);
		
		$Mulai_Col++;		
		$Cols	= getColsChar($Mulai_Col);
		$sheet->setCellValue($Cols.$NewRow, number_format($Total_Real_SO + $PPN_SO));
		$sheet->getStyle($Cols.$NewRow)->applyFromArray($styleArray1);
		
		$Mulai_Col++;		
		$Cols	= getColsChar($Mulai_Col);
		$sheet->setCellValue($Cols.$NewRow, $Marketing);
		$sheet->getStyle($Cols.$NewRow)->applyFromArray($styleArray1);
		
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