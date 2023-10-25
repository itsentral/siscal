<?php
//App::import('Vendor','PHPExcel/Classes/PHPExcel.php');
//App::import('Vendor','PHPExcel/Classes/PHPExcel/Writer/Excel5.php');
$this->load->library("PHPExcel");
$objPHPExcel    = new PHPExcel();

$style_header = array(
    'borders' => array(
        'allborders' => array(
            'style' => PHPExcel_Style_Border::BORDER_THIN,
            'color' => array('rgb' => '1006A3')
        )
    ),
    'fill' => array(
        'type' => PHPExcel_Style_Fill::FILL_SOLID,
        'color' => array('rgb' => 'E1E0F7'),
    ),
    'font' => array(
        'bold' => true,
    ),
    'alignment' => array(
        'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
        'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER
    )
);

$style_footer = array(
    'borders' => array(
        'allborders' => array(
            'style' => PHPExcel_Style_Border::BORDER_THIN,
            'color' => array('rgb' => '1006A3')
        )
    ),
    'fill' => array(
        'type' => PHPExcel_Style_Fill::FILL_SOLID,
        'color' => array('rgb' => 'E1E0F7'),
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
    ),
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

$Row = 1;
$NewRow    = $Row + 1;
foreach (range('A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O') as $columnID) {
    $objPHPExcel->getActiveSheet()->getColumnDimension($columnID)->setAutoSize(true);
}
$sheet     = $objPHPExcel->getActiveSheet();

$judul = "Schedule Incomplete";
$file_report = "Export_Schedule_Incomplete_";

$sheet->setCellValue('A' . $Row, $judul);
$sheet->getStyle('A' . $Row . ':P' . $NewRow)->applyFromArray($style_header);
$sheet->mergeCells('A' . $Row . ':P' . $NewRow);

$NewRow++;
$sheet->setCellValue('A' . $NewRow, 'No');
$sheet->getStyle('A' . $NewRow)->applyFromArray($style_header);

$sheet->setCellValue('B' . $NewRow, 'ID Detail');
$sheet->getStyle('B' . $NewRow)->applyFromArray($style_header);

$sheet->setCellValue('C' . $NewRow, 'Schedule Nomor');
$sheet->getStyle('C' . $NewRow)->applyFromArray($style_header);

$sheet->setCellValue('D' . $NewRow, 'Schedule Date');
$sheet->getStyle('D' . $NewRow)->applyFromArray($style_header);

$sheet->setCellValue('E' . $NewRow, 'Customer Name');
$sheet->getStyle('E' . $NewRow)->applyFromArray($style_header);

$sheet->setCellValue('F' . $NewRow, 'Address');
$sheet->getStyle('F' . $NewRow)->applyFromArray($style_header);

$sheet->setCellValue('G' . $NewRow, 'PIC');
$sheet->getStyle('G' . $NewRow)->applyFromArray($style_header);

$sheet->setCellValue('H' . $NewRow, 'Letter Order ID');
$sheet->getStyle('H' . $NewRow)->applyFromArray($style_header);

$sheet->setCellValue('I' . $NewRow, 'Nomor SO');
$sheet->getStyle('I' . $NewRow)->applyFromArray($style_header);

$sheet->setCellValue('J' . $NewRow, 'Quotation ID');
$sheet->getStyle('J' . $NewRow)->applyFromArray($style_header);

$sheet->setCellValue('K' . $NewRow, 'Nomor Quotation');
$sheet->getStyle('K' . $NewRow)->applyFromArray($style_header);

$sheet->setCellValue('L' . $NewRow, 'Nomor PO');
$sheet->getStyle('L' . $NewRow)->applyFromArray($style_header);

$sheet->setCellValue('M' . $NewRow, 'Sales Name');
$sheet->getStyle('M' . $NewRow)->applyFromArray($style_header);

$sheet->setCellValue('N' . $NewRow, 'Tool Name');
$sheet->getStyle('N' . $NewRow)->applyFromArray($style_header);

$sheet->setCellValue('O' . $NewRow, 'Teknisi Name');
$sheet->getStyle('O' . $NewRow)->applyFromArray($style_header);

$sheet->setCellValue('P' . $NewRow, 'Keterangan');
$sheet->getStyle('P' . $NewRow)->applyFromArray($style_header);

if ($records) {
    $nomor = 0;
    foreach ($records as $val) {
        $loop++;
        $NewRow++;
        $sheet->setCellValue('A' . $NewRow, $loop);
        $sheet->getStyle('A' . $NewRow)->applyFromArray($styleArray1);

        $sheet->setCellValue('B' . $NewRow, $val['id_details']);
        $sheet->getStyle('B' . $NewRow)->applyFromArray($styleArray1);

        $sheet->setCellValue('C' . $NewRow, $val['schedule_nomor']);
        $sheet->getStyle('C' . $NewRow)->applyFromArray($styleArray1);

        $sheet->setCellValue('D' . $NewRow, $val['schedule_date']);
        $sheet->getStyle('D' . $NewRow)->applyFromArray($styleArray1);

        $sheet->setCellValue('E' . $NewRow, $val['customer_name']);
        $sheet->getStyle('E' . $NewRow)->applyFromArray($styleArray1);

        $sheet->setCellValue('F' . $NewRow, $val['address_so']);
        $sheet->getStyle('F' . $NewRow)->applyFromArray($styleArray1);

        $sheet->setCellValue('G' . $NewRow, $val['pic_so']);
        $sheet->getStyle('G' . $NewRow)->applyFromArray($styleArray1);

        $sheet->setCellValue('H' . $NewRow, $val['letter_order_id']);
        $sheet->getStyle('H' . $NewRow)->applyFromArray($styleArray1);

        $sheet->setCellValue('I' . $NewRow, $val['no_so']);
        $sheet->getStyle('I' . $NewRow)->applyFromArray($styleArray1);

        $sheet->setCellValue('J' . $NewRow, $val['quotation_id']);
        $sheet->getStyle('J' . $NewRow)->applyFromArray($styleArray1);

        $sheet->setCellValue('K' . $NewRow, $val['quotation_nomor']);
        $sheet->getStyle('K' . $NewRow)->applyFromArray($styleArray1);

        $sheet->setCellValue('L' . $NewRow, $val['pono']);
        $sheet->getStyle('L' . $NewRow)->applyFromArray($styleArray1);

        $sheet->setCellValue('M' . $NewRow, $val['marketing_name']);
        $sheet->getStyle('M' . $NewRow)->applyFromArray($styleArray1);

        $sheet->setCellValue('N' . $NewRow, $val['tool_name']);
        $sheet->getStyle('N' . $NewRow)->applyFromArray($styleArray1);

        $sheet->setCellValue('O' . $NewRow, $val['actual_teknisi_name']);
        $sheet->getStyle('O' . $NewRow)->applyFromArray($styleArray1);

        $sheet->setCellValue('P' . $NewRow, $val['keterangan']);
        $sheet->getStyle('P' . $NewRow)->applyFromArray($styleArray1);
    }
}



$sheet->setTitle('Schedule Incomplete');
$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
ob_end_clean();
//sesuaikan headernya 
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
header("Cache-Control: no-store, no-cache, must-revalidate");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
//ubah nama file saat diunduh
header('Content-Disposition: attachment;filename="' . $file_report . date('YmdHis') . '.xls"');
//unduh file
$objWriter->save("php://output");
exit;
