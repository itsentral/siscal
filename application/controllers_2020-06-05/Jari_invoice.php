<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Jari_invoice extends CI_Controller {	
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
		$this->folder	='Jari_integrasi';
	}	
	public function index() {
		$Arr_Akses		= $this->Arr_Akses;
		if($Arr_Akses['read'] !='1'){
			$this->session->set_flashdata("alert_data", "<div class=\"alert alert-warning\" id=\"flash-message\">You Don't Have Right To Access This Page, Please Contact Your Administrator....</div>");
			redirect(site_url('dashboard'));
		}
		$rows_Driver		= $this->get_Driver();
		$data			= array(
			'action'		=> 'index',
			'title'			=> 'Invoice Delivery',
			'rows_driver'	=> $rows_Driver,
			'akses_menu'	=> $Arr_Akses
		);
		$this->load->view($this->folder.'/list_send_invoice',$data);
		
	}
	
	function get_Driver(){
		$rows_Driver	= array(''=>'Select An Option');
		$det_member		= $this->db->get_where('members',array('status'=>'1'))->result();
		if($det_member){
			foreach($det_member as $ketI=>$valI){
				$Nama_member	= $valI->nama;
				$rows_Driver[$Nama_member]	= $Nama_member;
			}
			unset($det_member);
		}
		return $rows_Driver;
	}
	
	public function list_detail() {
		
		$data			= array(
			'action'		=> 'list_detail',
			'title'			=> 'Detail Invoice'
		);
		$this->load->view($this->folder.'/list_outs_invoice',$data);
		
	}
	
	function get_data_display(){
		include "ssp.class.php";
		$WHERE		="grand_tot > 0";
		
		$table 		= 'invoices';
		$primaryKey = 'id';
		$columns 	= array(
			array( 'db' => 'id', 'dt' => 'id'),
			 array(
				'db' => 'id',
				'dt' => 'DT_RowId'
			),
			array( 'db' => 'nomor', 'dt' => 'nomor'),
			array( 'db' => 'invoice_no', 'dt' => 'invoice_no'),
			array( 'db' => 'customer_id', 'dt' => 'customer_id'),
			array( 'db' => 'customer_name', 'dt' => 'customer_name'),
			array( 'db' => 'address', 'dt' => 'address'),			
			array(
				'db' => 'datet',
				'dt'=> 'datet',
				'formatter' => function($d,$row){
					return date('d F Y',strtotime($d));
				}
			),
			array(
				'db' => 'dpp',
				'dt'=> 'dpp',
				'formatter' => function($d,$row){
					return number_format(floatval($d));
				}
			),
			array(
				'db' => 'diskon',
				'dt'=> 'diskon',
				'formatter' => function($d,$row){
					return number_format(floatval($d));
				}
			),
			array(
				'db' => 'total_dpp',
				'dt'=> 'total_dpp',
				'formatter' => function($d,$row){
					return number_format(floatval($d));
				}
			),
			array(
				'db' => 'ppn',
				'dt'=> 'ppn',
				'formatter' => function($d,$row){
					return number_format(floatval($d));
				}
			),
			array(
				'db' => 'grand_tot',
				'dt'=> 'grand_tot',
				'formatter' => function($d,$row){
					return number_format(floatval($d));
				}
			),
			array(
				'db' => 'id',
				'dt'=> 'action',
				'formatter' => function($d,$row){
					return '';
				}
			)

		);


		$sql_details = array(
			'user' => $this->db->username,
			'pass' => $this->db->password,
			'db'   => 'calibrations_new',
			'host' => $this->db->hostname
		);
		


		echo json_encode(
			SSP::complex ($_POST, $sql_details, $table, $primaryKey, $columns,null, $WHERE)
		);
	}
	
	function report_excel(){
		set_time_limit(0);
		//echo"<pre>";print_r($this->input->post());exit;
		$data_Pilih		= $this->input->post('data_pilihan');
		$rows_Pilih		= implode("','",$data_Pilih);
		$Tanggal_Inv	= $this->input->post('periode');
		$Driver_Name	= $this->input->post('driver');
		
		$this->load->library("PHPExcel");
		$objPHPExcel	= new PHPExcel();
		
		$Nama_File		= "INVOICE DELIVERY ".$Tanggal_Inv;
		// Set Creator & Title
		$style_header = array(
            'borders' => array(
                    'allborders' => array(
                    'style'      => PHPExcel_Style_Border::BORDER_THIN,
                    'color'      => array('rgb'=>'1006A3')
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
                    'style'  => PHPExcel_Style_Border::BORDER_THIN
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
                'vertical'   => PHPExcel_Style_Alignment::VERTICAL_CENTER
            )
        );
		
		$Columns_Detail	= array('Task Code','username driver','nama alat','nama perusahaan','tipe kegiatan','tanggal pelaksanaan','installment','collectfee','PIC Customer','NO BA','gender','No PO','birthdate','NO SO','Alamat Perusahaan','custphoneno','mobileno','mobileno2','negativestatus','Plat Mobil','relativename','relativetype','relativeaddress','relativephone','spousename','spousebirthdate','spousebirthplace','spouseaddress','spousemobileno','spouseoffice','spouseofficephone','spousejobname','companyname','companybusiness','companyaddress','companyphone','companyfax','jobname','merkname','modelname','typename','categoryname','caryear','color','chassisno','engineno','policeno','route','collbussname','ospokok','tenor','latitude','longitude','validuntil','custemail');
		
		$Columns_Header	= array('Task Code','Tanggal Pelaksanaan','Tanggal Pelaksanaan','period','dpd','angstung','denda');
		$objWorkSheet  	= $objPHPExcel->getActiveSheet();
		
		
		$sheet      = $objPHPExcel->createSheet(0);
		
		$Det_Rows_Detail	= $Det_Rows_Header =  array();
		
		$Next_Row	= 1;
		$Mulai_Col	= 0;
		foreach($Columns_Header as $keyJ=>$valJ){					
			$Mulai_Col++;
			$Cols_Name	= getColsChar($Mulai_Col);
			$sheet->setCellValue($Cols_Name.$Next_Row, $valJ);
			$sheet->getStyle($Cols_Name.$Next_Row)->applyFromArray($style_header);			
		}
		$intD			= 0;
		$Query_Header	= "SELECT * FROM invoices WHERE id IN ('".$rows_Pilih."')";
		$rows_Header	= $this->db->query($Query_Header)->result();
		if($rows_Header){
			$Page_Mulai	= 0;
			foreach($rows_Header as $keyH=>$valH){
				$intD++;
				$kode_Bast		= $valH->id;
				$nomor_Bast		= $valH->invoice_no;
				$tgl_Bast		= $valH->datet;
				$cust_Bast		= $valH->customer_name;
				$nocust_Bast	= $valH->customer_id;				
				$alamat_Bast	= $valH->address;
				$Total_Invoice	= $valH->grand_tot;
				$Nomor_PO		= $valH->invoice_no;
				$noso_Bast		='-';
				$Nomor_Quot		= $valH->nomor;
				$Longitude = $Latitude	= '';
				$tipe_kegiatan	= 'PENYERAHAN';
				
				$phone_Cust		= $PIC_Bast	='-';
				$Qry_Comp	= "SELECT phone,latitude,longitude,contact FROM customers WHERE id='".$nocust_Bast."'";
				
				$det_Comp		= $this->db->query($Qry_Comp)->result();
				if($det_Comp){
					$phone_Cust		= $det_Comp[0]->phone;
					$Latitude		= $det_Comp[0]->latitude;
					$Longitude		= $det_Comp[0]->longitude;
					$PIC_Bast		= $det_Comp[0]->contact;
				}
				
				$Det_Rows_Detail[$intD]		= array(
					$kode_Bast,
					$Driver_Name,
					$nomor_Bast,
					$cust_Bast,
					$tipe_kegiatan,
					$Tanggal_Inv,
					0,
					0,
					$PIC_Bast,
					$nomor_Bast,
					$kode_Bast,
					$Nomor_Quot,
					'',
					$noso_Bast,
					$alamat_Bast,
					$phone_Cust,
					$Latitude,
					$Longitude,
					$kode_Bast
				);
				
				
				
				$Next_Row++;
				$Mulai_Col		= 0;
				
				$Mulai_Col++;
				$Cols1		= getColsChar($Mulai_Col);
				$sheet->setCellValue($Cols1.$Next_Row, $kode_Bast);
				$sheet->getStyle($Cols1.$Next_Row)->applyFromArray($styleArray2);
				
				$Mulai_Col++;
				$Cols1		= getColsChar($Mulai_Col);
				$sheet->setCellValue($Cols1.$Next_Row, $Tanggal_Inv);
				$sheet->getStyle($Cols1.$Next_Row)->applyFromArray($styleArray2);
				
				$Mulai_Col++;
				$Cols1		= getColsChar($Mulai_Col);
				$sheet->setCellValue($Cols1.$Next_Row, $Tanggal_Inv);
				$sheet->getStyle($Cols1.$Next_Row)->applyFromArray($styleArray2);
				
				$Mulai_Col++;
				$Cols1		= getColsChar($Mulai_Col);
				$sheet->setCellValue($Cols1.$Next_Row, 0);
				$sheet->getStyle($Cols1.$Next_Row)->applyFromArray($styleArray2);
				
				$Mulai_Col++;
				$Cols1		= getColsChar($Mulai_Col);
				$sheet->setCellValue($Cols1.$Next_Row, 0);
				$sheet->getStyle($Cols1.$Next_Row)->applyFromArray($styleArray2);
				
				$Mulai_Col++;
				$Cols1		= getColsChar($Mulai_Col);
				$sheet->setCellValue($Cols1.$Next_Row, 0);
				$sheet->getStyle($Cols1.$Next_Row)->applyFromArray($styleArray2);
				
				$Mulai_Col++;
				$Cols1		= getColsChar($Mulai_Col);
				$sheet->setCellValue($Cols1.$Next_Row, 0);
				$sheet->getStyle($Cols1.$Next_Row)->applyFromArray($styleArray2);				
			}
			$sheet->setTitle('TASK DETAIL');
		}
		
		if($Det_Rows_Detail){
			$sheet      = $objPHPExcel->createSheet(1);
			$Next_Row	= 1;
			$Mulai_Col	= 0;
			foreach($Columns_Detail as $keyJ=>$valJ){					
				$Mulai_Col++;
				$Cols_Name	= getColsChar($Mulai_Col);
				$sheet->setCellValue($Cols_Name.$Next_Row, $valJ);
				$sheet->getStyle($Cols_Name.$Next_Row)->applyFromArray($style_header);			
			}
			
			foreach($Det_Rows_Detail as $keyI=>$valI){
				$Next_Row++;
				$Mulai_Col	= 0;
				$Lat_Cust	= $Long_Cust	= $Kode_Rest = '' ;
				for($y=0;$y<=54;$y++){					
					if($y <= 18){
						$Nilai_data	= $valI[$y];
						if($y === 16){
							$Nilai_data	= '';
							$Lat_Cust	= $valI[$y];
						}else if($y === 17){
							$Nilai_data	= '';
							$Long_Cust	= $valI[$y];
						}
						
					}else{
						$Nilai_data	= '';
						if($y === 51){
							$Nilai_data	= $Lat_Cust;
						}else if($y === 52){
							$Nilai_data	= $Long_Cust;
						}
					}
					
					$Mulai_Col++;
					$Cols1		= getColsChar($Mulai_Col);
					$sheet->setCellValue($Cols1.$Next_Row, $Nilai_data);
					$sheet->getStyle($Cols1.$Next_Row)->applyFromArray($styleArray2);
				}
			}
			$sheet->setTitle('TASK DETAIL');
		}
		

		$objWriter      = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
        //ob_end_clean();
        //sesuaikan headernya 
        header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
        header("Cache-Control: no-store, no-cache, must-revalidate");
        header("Cache-Control: post-check=0, pre-check=0", false);
        header("Pragma: no-cache");
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        //ubah nama file saat diunduh
        header('Content-Disposition: attachment;filename="'.$Nama_File.'.xls"');
        //unduh file
        $objWriter->save("php://output");
		
		redirect('index/'.$this->input->post('periode'));
		
	}
}
