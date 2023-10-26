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
		$Arr_Akses		= $this->Arr_Akses;
		
		$WHERE			= "flag_insitu='N'";
		
		
		$requestData	= $_REQUEST;
		
		$like_value     = $requestData['search']['value'];
        $column_order   = $requestData['order'][0]['column'];
        $column_dir     = $requestData['order'][0]['dir'];
        $limit_start    = $requestData['start'];
        $limit_length   = $requestData['length'];
		
		$columns_order_by = array(
			0 => 'tool_id',
			1 => 'tool_name',
			2 => 'cust_tool',
			3 => 'qty',
			4 => 'sisa_so',
			5 => 'nomor',
			6 => 'datet',
			7 => 'customer_name',
			8 => 'pono',
			9 => 'podate',
			10 => 'supplier_name'
		);
		
		
		
		if($like_value){
			if(!empty($WHERE))$WHERE	.=" AND ";
			$WHERE	.="(
						  nomor LIKE '%".$this->db->escape_like_str($like_value)."%'
						  OR DATE_FORMAT(datet, '%d-%m-%Y') LIKE '%".$this->db->escape_like_str($like_value)."%'
						   OR DATE_FORMAT(podate, '%d-%m-%Y') LIKE '%".$this->db->escape_like_str($like_value)."%'
						  OR tool_id LIKE '%".$this->db->escape_like_str($like_value)."%'
						  OR tool_name LIKE '%".$this->db->escape_like_str($like_value)."%'
						  OR cust_tool LIKE '%".$this->db->escape_like_str($like_value)."%'
						  OR qty LIKE '%".$this->db->escape_like_str($like_value)."%'
						  OR sisa_so LIKE '%".$this->db->escape_like_str($like_value)."%'
						  OR supplier_name LIKE '%".$this->db->escape_like_str($like_value)."%'
						  OR customer_name LIKE '%".$this->db->escape_like_str($like_value)."%'
						  OR pono LIKE '%".$this->db->escape_like_str($like_value)."%'
						  OR cust_tool LIKE '%".$this->db->escape_like_str($like_value)."%'
						)";
		}
		
		
		
		$sql = "SELECT
					*,
					(@row:=@row+1) AS urut
				FROM
					view_outstanding_order_details,
				(SELECT @row:=0) r 
				WHERE ".$WHERE;
		//print_r($sql);exit();
		$fetch['totalData'] 	= $this->db->query($sql)->num_rows();
		$fetch['totalFiltered']	= $this->db->query($sql)->num_rows();

		

		$sql .= " ORDER BY datet DESC,".$columns_order_by[$column_order]." ".$column_dir." ";
		$sql .= " LIMIT ".$limit_start." ,".$limit_length." ";

		$fetch['query'] = $this->db->query($sql);
		
		$totalData		= $fetch['totalData'];
		$totalFiltered	= $fetch['totalFiltered'];
		$query			= $fetch['query'];
		
		$data		= array();
        $urut1  	= 1;
        $urut2  	= 0;
		$Periode_Now= date('Y-m');
		$Tahun_Now	= date('Y');
		foreach($query->result_array() as $row)
		{
			$total_data     = $totalData;
            $start_dari     = $requestData['start'];
            $asc_desc       = $requestData['order'][0]['dir'];
            if($asc_desc == 'asc')
            {
                $nomor = $urut1 + $start_dari;
            }
            if($asc_desc == 'desc')
            {
                $nomor = ($total_data - $start_dari) - $urut2;
            }
			
			$Code_Tool		= $row['tool_id'];
			$Name_Tool		= $row['tool_name'];
			$Cust_Tool		= $row['cust_tool'];
			$Qty_Tool		= $row['qty'];
			$Qty_SO			= '<span class="badge bg-green">'.$row['sisa_so'].'</span>';
			$Quot_Nomor		= $row['nomor'];
			$Quot_Date		= date('d-m-Y',strtotime($row['datet']));
			$Nomor_PO		= $row['pono'];
			$Date_PO		= date('d-m-Y',strtotime($row['podate']));
			$Name_Cust		= $row['customer_name'];
			$Name_Supp		= $row['supplier_name'];
			
			
			
			
			
			
			$nestedData		= array();
			$nestedData[]	= $Code_Tool;
			$nestedData[]	= $Name_Tool;
			$nestedData[]	= $Cust_Tool;
			$nestedData[]	= $Qty_Tool;
			$nestedData[]	= $Qty_SO;
			$nestedData[]	= $Quot_Nomor;
			$nestedData[]	= $Quot_Date;
			$nestedData[]	= $Name_Cust;
			$nestedData[]	= $Nomor_PO;
			$nestedData[]	= $Date_PO;
			$nestedData[]	= $Name_Supp;
			$data[] = $nestedData;
            $urut1++;
            $urut2++;
		}

		$json_data = array(
			"draw"            => intval( $requestData['draw'] ),  
			"recordsTotal"    => intval( $totalData ),  
			"recordsFiltered" => intval( $totalFiltered ), 
			"data"            => $data
		);

		echo json_encode($json_data);
		
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
		$Col_Akhir	= getColsChar(12);
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
			'cust_tool'		=> 'Cust Alat',
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
