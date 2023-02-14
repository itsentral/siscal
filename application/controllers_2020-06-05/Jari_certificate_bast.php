<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Jari_certificate_bast extends CI_Controller {	
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
	public function index($Periode='') {
		$Arr_Akses		= $this->Arr_Akses;
		if($Arr_Akses['read'] !='1'){
			$this->session->set_flashdata("alert_data", "<div class=\"alert alert-warning\" id=\"flash-message\">You Don't Have Right To Access This Page, Please Contact Your Administrator....</div>");
			redirect(site_url('dashboard'));
		}
		if(empty($Periode)){
			$Periode	= date('Y-m-d');
		}
		
		if($this->input->post()){
			$Periode	= $this->input->post('periode');
		}
		
	
		$WHERE			= "datet = '$Periode'
						AND `status` != 'CNC'";
		$Query			= "SELECT
							id,
							nomor,
							datet,
							customer_id AS nocust,
							customer_name AS customer,
							address							
						FROM
							bast_certificates						
						WHERE ".$WHERE;
		//echo $Query;
		
		$records		= $this->db->query($Query)->result();
		$data			= array(
			'action'		=> 'index',
			'title'			=> 'BAST Certificate',
			'rows_data'		=> $records,
			'periode'		=> $Periode,
			'akses_menu'	=> $Arr_Akses
		);
		$this->load->view($this->folder.'/list_bast_certificate',$data);
		
	}
	
	function view_bast_certificate($kode_bast=''){
		$Arr_Akses		= $this->Arr_Akses;
		if($Arr_Akses['read'] !='1'){
			$this->session->set_flashdata("alert_data", "<div class=\"alert alert-warning\" id=\"flash-message\">You Don't Have Right To Access This Page, Please Contact Your Administrator....</div>");
			redirect(site_url('Jari_cust_bast'));
		}
		$rows_detail	= $this->db->get_where('bast_certificate_details',array('bast_certificate_id'=>$kode_bast))->result();
		$rows_header	= $this->db->get_where('bast_certificates',array('id'=>$kode_bast))->result();
		$data			= array(
			'action'		=> 'view_bast_certificate',
			'title'			=> 'BAST Certificate Detail',
			'rows_header'	=> $rows_header,
			'rows_detail'	=> $rows_detail
		);
		$this->load->view($this->folder.'/detail_bast_certificate',$data);
	}
	
	
	function report_excel(){
		set_time_limit(0);
		//echo"<pre>";print_r($this->input->post());exit;
		$data_Pilih		= $this->input->post('det_pilih');
		$rows_Pilih		= implode("','",$data_Pilih);
		
		$this->load->library("PHPExcel");
		$objPHPExcel	= new PHPExcel();
		
		$Nama_File		= "BAST CERTIFICATE ".$this->input->post('periode');
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
		$Query_Header	= "SELECT * FROM bast_certificates WHERE id IN ('".$rows_Pilih."')";
		$rows_Header	= $this->db->query($Query_Header)->result();
		if($rows_Header){
			$Page_Mulai	= 0;
			foreach($rows_Header as $keyH=>$valH){
				
				$kode_Bast		= $valH->id;
				$nomor_Bast		= $valH->nomor;
				$tgl_Bast		= $valH->datet;
				$cust_Bast		= $valH->customer_name;
				$nocust_Bast	= $valH->customer_id;				
				$alamat_Bast	= $valH->address;
				
				
				
				$Longitude = $Latitude	= '';
				$tipe_kegiatan	= 'PENYERAHAN';
				
				$rows_detail	= $this->db->get_where('bast_certificate_details',array('bast_certificate_id'=>$kode_Bast))->result();
				
				
				$Driver_Name	= '';
				
				$Qry_Comp	= "SELECT phone,latitude,longitude,contact FROM customers WHERE id='".$nocust_Bast."'";
				
				$det_Comp		= $this->db->query($Qry_Comp)->result();
				$phone_Cust		= $det_Comp[0]->phone;
				$Latitude		= $det_Comp[0]->latitude;
				$Longitude		= $det_Comp[0]->longitude;
				$PIC_Bast		= $det_Comp[0]->contact;
				
				if($rows_detail){
					foreach($rows_detail as $keyD=>$valD){
						$intD++;
						$Name_Item	= $valD->tool_name;
						$Kode_Item	= $valD->tool_id;
						$Trans_Item	= $valD->trans_data_detail_id;
						$Bast_Item	= $valD->id;
						
						$Qry_Trans		= "SELECT nomor,pono FROM quotations WHERE id='".$valD->quotation_id."'";
						$det_Trans		= $this->db->query($Qry_Trans)->result();
						$Nomor_PO		= $det_Trans[0]->pono;
						$Nomor_Quot		= $det_Trans[0]->nomor;
						
						$Qry_SO			= "SELECT no_so FROM letter_orders WHERE id='".$valD->letter_order_id."'";
						$det_SO			= $this->db->query($Qry_SO)->result();
						$noso_Bast		= $det_SO[0]->no_so;
						
						$Det_Rows_Detail[$intD]		= array(
							$Bast_Item,
							$Driver_Name,
							$Name_Item,
							$cust_Bast,
							$tipe_kegiatan,
							$tgl_Bast,
							0,
							0,
							$PIC_Bast,
							$nomor_Bast,
							$Trans_Item,
							$valD->certificate_no,
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
						$sheet->setCellValue($Cols1.$Next_Row, $Bast_Item);
						$sheet->getStyle($Cols1.$Next_Row)->applyFromArray($styleArray2);
						
						$Mulai_Col++;
						$Cols1		= getColsChar($Mulai_Col);
						$sheet->setCellValue($Cols1.$Next_Row, $tgl_Bast);
						$sheet->getStyle($Cols1.$Next_Row)->applyFromArray($styleArray2);
						
						$Mulai_Col++;
						$Cols1		= getColsChar($Mulai_Col);
						$sheet->setCellValue($Cols1.$Next_Row, $tgl_Bast);
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
				}
				
								
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
				$Lat_Cust	= $Long_Cust	='';
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
			$sheet->setTitle('TASK');
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
