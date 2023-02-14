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

$sheet->setCellValue('A'.$Row, 'LAPORAN PENJUALAN '.$periode_awal.' sd '.$periode_akhir);
$sheet->getStyle('A'.$Row.':Q'.$NewRow)->applyFromArray($style_header);
$sheet->mergeCells('A'.$Row.':Q'.$NewRow);



// header
$NewRow++;
$NextRow	= $NewRow + 1;
$sheet->setCellValue('A'.$NewRow, 'No');
$sheet->getStyle('A'.$NewRow.':A'.$NextRow)->applyFromArray($style_header);
$sheet->mergeCells('A'.$NewRow.':A'.$NextRow);

$sheet->setCellValue('B'.$NewRow, 'No Invoice');
$sheet->getStyle('B'.$NewRow.':B'.$NextRow)->applyFromArray($style_header);
$sheet->mergeCells('B'.$NewRow.':B'.$NextRow);

$sheet->setCellValue('C'.$NewRow, 'Tgl Invoice');
$sheet->getStyle('C'.$NewRow.':C'.$NextRow)->applyFromArray($style_header);
$sheet->mergeCells('C'.$NewRow.':C'.$NextRow);

$sheet->setCellValue('D'.$NewRow, 'Customer');
$sheet->getStyle('D'.$NewRow.':D'.$NextRow)->applyFromArray($style_header);
$sheet->mergeCells('D'.$NewRow.':D'.$NextRow);

$sheet->setCellValue('E'.$NewRow, 'Alamat');
$sheet->getStyle('E'.$NewRow.':E'.$NextRow)->applyFromArray($style_header);
$sheet->mergeCells('E'.$NewRow.':E'.$NextRow);

$sheet->setCellValue('F'.$NewRow, 'DPP');
$sheet->getStyle('F'.$NewRow.':F'.$NextRow)->applyFromArray($style_header);
$sheet->mergeCells('F'.$NewRow.':F'.$NextRow);

$sheet->setCellValue('G'.$NewRow, 'PPN');
$sheet->getStyle('G'.$NewRow.':G'.$NextRow)->applyFromArray($style_header);
$sheet->mergeCells('G'.$NewRow.':G'.$NextRow);

$sheet->setCellValue('H'.$NewRow, 'PPH 23');
$sheet->getStyle('H'.$NewRow.':H'.$NextRow)->applyFromArray($style_header);
$sheet->mergeCells('H'.$NewRow.':H'.$NextRow);

$sheet->setCellValue('I'.$NewRow, 'Total Invoice');
$sheet->getStyle('I'.$NewRow.':I'.$NextRow)->applyFromArray($style_header);
$sheet->mergeCells('I'.$NewRow.':I'.$NextRow);


$sheet->setCellValue('J'.$NewRow, 'No SO');
$sheet->getStyle('J'.$NewRow.':J'.$NextRow)->applyFromArray($style_header);
$sheet->mergeCells('J'.$NewRow.':J'.$NextRow);

$sheet->setCellValue('K'.$NewRow, 'No Faktur');
$sheet->getStyle('K'.$NewRow.':K'.$NextRow)->applyFromArray($style_header);
$sheet->mergeCells('K'.$NewRow.':K'.$NextRow);

$sheet->setCellValue('L'.$NewRow, 'Ket Follow Up');
$sheet->getStyle('L'.$NewRow.':L'.$NextRow)->applyFromArray($style_header);
$sheet->mergeCells('L'.$NewRow.':L'.$NextRow);

$sheet->setCellValue('M'.$NewRow, 'BUM');
$sheet->getStyle('M'.$NewRow.':O'.$NewRow)->applyFromArray($style_header);
$sheet->mergeCells('M'.$NewRow.':O'.$NewRow);

$sheet->setCellValue('P'.$NewRow, 'PPH 23 / PPN');
$sheet->getStyle('P'.$NewRow.':Q'.$NewRow)->applyFromArray($style_header);
$sheet->mergeCells('P'.$NewRow.':Q'.$NewRow);

$sheet->setCellValue('M'.$NextRow, 'Tgl Bayar');
$sheet->getStyle('M'.$NextRow)->applyFromArray($style_header);

$sheet->setCellValue('N'.$NextRow, 'Total Bayar');
$sheet->getStyle('N'.$NextRow)->applyFromArray($style_header);

$sheet->setCellValue('O'.$NextRow, 'Bank');
$sheet->getStyle('O'.$NextRow)->applyFromArray($style_header);

$sheet->setCellValue('P'.$NextRow, 'Reff 1');
$sheet->getStyle('P'.$NextRow)->applyFromArray($style_header);

$sheet->setCellValue('Q'.$NextRow, 'Reff 2');
$sheet->getStyle('Q'.$NextRow)->applyFromArray($style_header);
$NewRow	= $NextRow;


if($rows_data){
	$loop	=0;
	$sekarang	= date('Y-m-d');
	foreach($rows_data as $key=>$val){
		$loop++;
		$NewRow++;
		$No_Inv		= $val['invoice_no'];
		$Tgl_Bayar	= $Bank	= $Reff1 = $Reff2 = '-';
		$Jum_Bayar	= 0;
		## BUM ##
		$Query_Bum	= "SELECT (det_ar.kredit - det_ar.debet) as total_bayar,det_ar.tgl_jurnal, det_trans.accid FROM trans_ar_jurnals det_ar INNER JOIN trans_jurnal_headers det_trans ON det_ar.jurnalid=det_trans.jurnalid WHERE det_trans.tipe='BUM' AND det_trans.sts_batal='N' AND det_ar.invoice_no='$No_Inv'";
		$det_Bum	= $this->db->query($Query_Bum)->result();
		//echo $Query_Bum;
		//echo"<pre>";print_r($det_Bum);exit;
		if($det_Bum){
			foreach($det_Bum as $ky=>$values){
				$Tot_Bayar	= $values->total_bayar;
				$Tgl_Bayar	= date('d M Y',strtotime($values->tgl_jurnal));
				$Coa_Bayar	= $values->accid;
				
				$Jum_Bayar	+=$Tot_Bayar;
				
				## COA BANK ##
				if($Coa_Bayar !='-' && $Coa_Bayar !=''){
					$Query_Bank	= "SELECT CONCAT(bank,' ',norek) as nama_bank FROM coa_masters WHERE accid='$Coa_Bayar'";
					$det_Bank	= $this->db->query($Query_Bank)->result();
					if($det_Bank){
						$Bank	= $det_Bank[0]->nama_bank;
					}
				}
			}
		}
		
		## CN ##
		$Query_CN	= "SELECT det_trans.no_reff FROM trans_ar_jurnals det_ar INNER JOIN trans_jurnal_headers det_trans ON det_ar.jurnalid=det_trans.jurnalid WHERE det_trans.tipe='CN' AND det_trans.sts_batal='N' AND det_ar.invoice_no='$No_Inv' ORDER BY det_trans.tgl_jurnal DESC LIMIT 2";
		$det_CN		= $this->db->query($Query_CN)->result();
		if($det_CN){
			$intI	= 0;
			foreach($det_CN as $ks=>$values){
				$intI++;
				if($intI==1){
					$Reff1 	= $values->no_reff;
				}else{
					$Reff2 	= $values->no_reff;
				}
			}
		}
		
		$Total_Inv		= $val['grand_tot'];
		
		
		$sheet->setCellValue('A'.$NewRow, $loop);
		$sheet->getStyle('A'.$NewRow)->applyFromArray($styleArray1);
		
		$sheet->setCellValue('B'.$NewRow, $val['invoice_no']);
		$sheet->getStyle('B'.$NewRow)->applyFromArray($styleArray1);
		
		$sheet->setCellValue('C'.$NewRow, date('d M Y',strtotime($val['datet'])));
		$sheet->getStyle('C'.$NewRow)->applyFromArray($styleArray1);
		
		$sheet->setCellValue('D'.$NewRow, $val['customer_name']);
		$sheet->getStyle('D'.$NewRow)->applyFromArray($styleArray1);
		
		$sheet->setCellValue('E'.$NewRow, $val['address']);
		$sheet->getStyle('E'.$NewRow)->applyFromArray($styleArray1);
		
		$sheet->setCellValue('F'.$NewRow, number_format($val['total_dpp']));
		$sheet->getStyle('F'.$NewRow)->applyFromArray($styleArray1);
		
		
		$sheet->setCellValue('G'.$NewRow, number_format($val['ppn']));
		$sheet->getStyle('G'.$NewRow)->applyFromArray($styleArray1);
		
		$sheet->setCellValue('H'.$NewRow, number_format($val['pph23']));
		$sheet->getStyle('H'.$NewRow)->applyFromArray($styleArray1);
		
		$sheet->setCellValue('I'.$NewRow, number_format($Total_Inv));
		$sheet->getStyle('I'.$NewRow)->applyFromArray($styleArray1);
		
		$sheet->setCellValue('J'.$NewRow, $val['no_so']);
		$sheet->getStyle('J'.$NewRow)->applyFromArray($styleArray1);
		
		$sheet->setCellValue('K'.$NewRow, $val['no_faktur']);
		$sheet->getStyle('K'.$NewRow)->applyFromArray($styleArray1);
		
		$sheet->setCellValue('L'.$NewRow, $val['descr_follow_up']);
		$sheet->getStyle('L'.$NewRow)->applyFromArray($styleArray1);
		
		$sheet->setCellValue('M'.$NewRow, $Tgl_Bayar);
		$sheet->getStyle('M'.$NewRow)->applyFromArray($styleArray1);
		
		$sheet->setCellValue('N'.$NewRow, number_format($Jum_Bayar));
		$sheet->getStyle('N'.$NewRow)->applyFromArray($styleArray1);
		
		$sheet->setCellValue('O'.$NewRow, $Bank);
		$sheet->getStyle('O'.$NewRow)->applyFromArray($styleArray1);
		
		$sheet->setCellValue('P'.$NewRow, $Reff1);
		$sheet->getStyle('P'.$NewRow)->applyFromArray($styleArray1);
		
		$sheet->setCellValue('Q'.$NewRow, $Reff2);
		$sheet->getStyle('Q'.$NewRow)->applyFromArray($styleArray1);
		
	}
}


$sheet->setTitle('Laporan Invoice');       
$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
ob_end_clean();
//sesuaikan headernya 
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
header("Cache-Control: no-store, no-cache, must-revalidate");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
//ubah nama file saat diunduh
header('Content-Disposition: attachment;filename="Laporan_Invoice_'.date('YmdHis').'.xls"');
//unduh file
$objWriter->save("php://output");
exit;

?>