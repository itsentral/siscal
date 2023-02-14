<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Outstanding_receive extends CI_Controller {	
	public function __construct() {
		parent::__construct();
		$this->load->model('master_model');
		// Your own constructor code
		/*
		if(!$this->session->userdata('isSISCALlogin')){
			redirect('login');
		}
		*/
		$controller			= ucfirst(strtolower($this->uri->segment(1)));
		/*
		$this->Arr_Akses			= getAcccesmenu($controller);		
		*/
		$this->Arr_Akses			= array(
			'read'		=> 1,
			'create'	=> 1,
			'update'	=> 1,
			'delete'	=> 1,
			'download'	=> 1,
			'approve'	=> 1,
		);
		$this->folder	= 'Warehouses';
	}	
	public function index() {		
		$data			= array(
			'action'	=>'index',
			'title'		=>'Detail Outstanding Penerimaan Barang'
		);		
		$this->load->view($this->folder.'/list_out_receive_item',$data);		
	}
	
	function get_data_display(){
		include "ssp.class.php";
		$WHERE		= "flag_insitu='N'";
		$table 		= 'view_outstanding_order_details';
		$primaryKey = 'detail_id';
		$columns 	= array(
			array( 'db' => 'detail_id', 'dt' => 'detail_id'),
			 array(
				'db' => 'detail_id',
				'dt' => 'DT_RowId'
			),
			array( 'db' => 'id', 'dt' => 'id'),
			array( 'db' => 'nomor', 'dt' => 'nomor'),
			array( 'db' => 'customer_name', 'dt' => 'customer_name'),
			array( 'db' => 'customer_id', 'dt' => 'customer_id'),
			array( 'db' => 'address', 'dt' => 'address'),
			array( 'db' => 'flag_insitu', 'dt' => 'flag_insitu'),
			array( 'db' => 'pono', 'dt' => 'pono'),
			array( 'db' => 'member_id', 'dt' => 'member_id'),
			array( 'db' => 'member_name', 'dt' => 'member_name'),
			array( 'db' => 'project_no', 'dt' => 'project_no'),
			array( 'db' => 'tool_id', 'dt' => 'tool_id'),
			array( 'db' => 'tool_name', 'dt' => 'tool_name'),
			array( 'db' => 'cust_tool', 'dt' => 'cust_tool'),
			array( 'db' => 'range', 'dt' => 'range'),
			array( 'db' => 'piece_id', 'dt' => 'piece_id'),
			array( 'db' => 'supplier_id', 'dt' => 'supplier_id'),
			array( 'db' => 'supplier_name', 'dt' => 'supplier_name'),
			array( 'db' => 'descr', 'dt' => 'descr'),
			array( 'db' => 'qty', 'dt' => 'qty'),
			array( 'db' => 'sisa_so', 'dt' => 'sisa_so'),
			array(
				'db' => 'datet',
				'dt'=> 'datet',
				'formatter' => function($d,$row){
					return date('d F Y',strtotime($d));
				}
			),
			array(
				'db' => 'podate',
				'dt'=> 'podate',
				'formatter' => function($d,$row){
					return date('d F Y',strtotime($d));
				}
			),
			array(
				'db' => 'grand_tot',
				'dt'=> 'grand_tot',
				'formatter' => function($d,$row){
					return number_format($d);
				}
			),
			array(
				'db' => 'hpp',
				'dt'=> 'hpp',
				'formatter' => function($d,$row){
					return number_format($d);
				}
			),
			array(
				'db' => 'price',
				'dt'=> 'price',
				'formatter' => function($d,$row){
					return number_format($d);
				}
			),
			array(
				'db' => 'detail_id',
				'dt'=> 'action',
				'formatter' => function($d,$row){
					return '';
				}
			)

		);


		$sql_details = array(
			'user' => $this->db->username,
			'pass' => $this->db->password,
			'db'   => $this->db->database,
			'host' => $this->db->hostname
		);
		


		echo json_encode(
			SSP::complex ($_POST, $sql_details, $table, $primaryKey, $columns,null, $WHERE)
		);
	}
	
	
	
	public function get_excel_outs_receive(){
		$records		= $this->db->get_where('view_outstanding_order_details',array('flag_insitu'=>'N'))->result();
		
		
		$Judul				= 'DETAIL OUTSTANDING PENERIMAAN ALAT';
		$Title				= 'Detail Alat';
		
		
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

		$style_header2 = array(	
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
		$styleArray3 = array(					  
			  'alignment' => array(
				'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_LEFT
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
				'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_LEFT,
				'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER
			  )
		  );
		 
		$Arr_Bulan	= array(1=>'Jan','Feb','Mar','Apr','Mei','Jun','Jul','Aug','Sep','Oct','Nov','Dec'); 
		$sheet 		= $objPHPExcel->getActiveSheet();
		
		$Row		= 1;
		$NewRow		= $Row+1;
		$Col_Akhir	= getColsChar(11);
		$sheet->setCellValue('A'.$Row, $Judul);
		$sheet->getStyle('A'.$Row.':'.$Col_Akhir.$NewRow)->applyFromArray($style_header2);
		$sheet->mergeCells('A'.$Row.':'.$Col_Akhir.$NewRow);
		
		
		$NextRow= $NewRow +2;
		$Nama_Col	= getColsChar(1);
		$sheet->setCellValue($Nama_Col.$NextRow,'No');
		$sheet->getStyle($Nama_Col.$NextRow)->applyFromArray($style_header);
		
		
		$Arr_Judul	= array(
			'tool_id'		=> 'Kode Alat',
			'tool_name'		=> 'Nama Alat',
			'qty'			=> 'Qty',
			'sisa_so'		=> 'Qty Outstanding',
			'nomor'			=> 'Quotation',
			'datet'			=> 'Tgl Quotation',
			'customer_name'	=> 'Customer',
			'pono'			=> 'No PO',
			'podate'		=> 'PO Date',
			'supplier_name'	=> 'Supplier'
		);
		
		
		$Mulai_Col	=1;
		foreach($Arr_Judul as $key=>$vals){
			$Mulai_Col++;
			$Nama_Col	= getColsChar($Mulai_Col);
			$sheet->setCellValue($Nama_Col.$NextRow,$vals);
			$sheet->getStyle($Nama_Col.$NextRow)->applyFromArray($style_header);
		}
					
		if($records){
			$awal_row		= $NextRow;
			$loop			= 0;
			foreach($records as $keys=>$values){
				$awal_row++;
				$loop++;
				
				
				$awal_col	= 0;
				$awal_col++;				
				$Cols		= getColsChar($awal_col);
				$sheet->setCellValue($Cols.$awal_row, $loop);
				$sheet->getStyle($Cols.$awal_row)->applyFromArray($styleArray2);
				
				foreach($Arr_Judul as $keyD=>$valD){
					$awal_col++;				
					$Cols		= getColsChar($awal_col);
					$det_Name	= '';
					if($awal_col === 7 || $awal_col===10){
						$det_Name = date('d F Y',strtotime($values->$keyD));
					}else{
						$det_Name = $values->$keyD;
					}
					$sheet->setCellValue($Cols.$awal_row, $det_Name);
					$sheet->getStyle($Cols.$awal_row)->applyFromArray($styleArray2);
				}
				
			}
			
		}
		
		
		
		$sheet->setTitle($Title);
		//mulai menyimpan excel format xlsx, kalau ingin xls ganti Excel2007 menjadi Excel5          
		$objWriter		= PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
		//ob_end_clean();
		//sesuaikan headernya 
		header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
		header("Cache-Control: no-store, no-cache, must-revalidate");
		header("Cache-Control: post-check=0, pre-check=0", false);
		header("Pragma: no-cache");
		header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
		//ubah nama file saat diunduh
		header('Content-Disposition: attachment;filename="Report_Outs_Rec_Tool_'.date('YmdHis').'.xls"');
		//unduh file
		$objWriter->save("php://output");
		
			
		
	}
	
}
