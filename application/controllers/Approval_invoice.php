<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Approval_invoice extends CI_Controller { 
	public function __construct(){
        parent::__construct();	
		
		$this->load->database();
        if(!$this->session->userdata('isSISCALlogin')){
			redirect('login');
		}
		$this->load->model('master_model');
		$controller			= ucfirst(strtolower($this->uri->segment(1)));
		$this->Arr_Akses	= getAcccesmenu($controller);
		
		$this->folder	= 'Invoicing';
		$this->file_attachement	= $this->config->item('link_file');
		$this->file_location	= $this->config->item('location_file');
    }

	public function index(){
		$Arr_Akses			= $this->Arr_Akses;
		if($Arr_Akses['read'] !='1'){
			$this->session->set_flashdata("alert_data", "<div class=\"alert alert-warning\" id=\"flash-message\">You Don't Have Right To Access This Page, Please Contact Your Administrator....</div>");
			redirect(site_url('dashboard'));
		}
// TEST
		$data = array(
			'title'			=> 'INVOICE APPROVAL',
			'action'		=> 'index',
			'akses_menu'	=> $Arr_Akses
		);
		history('View List Invoice Approval');
		$this->load->view($this->folder.'/v_invoice_approval',$data);
	}
	function get_data_display(){
		$Arr_Akses			= $this->Arr_Akses;
		
		$WHERE				= "grand_tot > 0
								AND status = 'OPN'";
		
		$requestData	= $_REQUEST;
		
		$like_value     = $requestData['search']['value'];
        $column_order   = $requestData['order'][0]['column'];
        $column_dir     = $requestData['order'][0]['dir'];
        $limit_start    = $requestData['start'];
        $limit_length   = $requestData['length'];
		
		$columns_order_by = array(
			1 => 'invoice_no',
			2 => 'datet',
			3 => 'customer_name',
			4 => 'grand_tot'
		);
		
		
		
		if($like_value){
			if(!empty($WHERE))$WHERE	.=" AND ";
			$WHERE	.="(
						  invoice_no LIKE '%".$this->db->escape_like_str($like_value)."%'
						  OR DATE_FORMAT(datet, '%d %b %Y') LIKE '%".$this->db->escape_like_str($like_value)."%'
						  OR customer_name LIKE '%".$this->db->escape_like_str($like_value)."%'
						  OR grand_tot LIKE '%".$this->db->escape_like_str($like_value)."%'
						)";
		}
		
		
		$sql = "SELECT
					id,
					datet,
					invoice_no,
					grand_tot,
					customer_id,
					customer_name,
					(@row:=@row+1) AS urut
				FROM
					invoices,
				(SELECT @row:=0) r 
				WHERE ".$WHERE;
		//print_r($sql);exit();
		$fetch['totalData'] 	= $this->db->query($sql)->num_rows();
		$fetch['totalFiltered']	= $this->db->query($sql)->num_rows();

		

		$sql .= " ORDER BY datet ASC,".$columns_order_by[$column_order]." ".$column_dir." ";
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
			
			$Code_Inv		= $row['id'];
			$Customer		= $row['customer_name'];
			$Total			= number_format($row['grand_tot']);
			
			$Nomor_SO		= $Nomor_PO	= '-';
			$Query_SO		= "SELECT
									GROUP_CONCAT(DISTINCT head_so.no_so) AS nomor_so
								FROM
									letter_orders head_so
								INNER JOIN invoice_details det_inv ON head_so.id = det_inv.letter_order_id
								WHERE
									det_inv.invoice_id = '".$Code_Inv."'";
			$rows_SO		= $this->db->query($Query_SO)->result();
			if($rows_SO){
				$Nomor_SO	= $rows_SO[0]->nomor_so;
			}
			
			
			$Query_PO		= "SELECT
									GROUP_CONCAT(DISTINCT head_quot.pono) AS nomor_po
								FROM
									quotations head_quot
								INNER JOIN invoice_details det_inv ON head_quot.id = det_inv.quotation_id
								WHERE
									det_inv.invoice_id = '".$Code_Inv."'";
			$rows_PO		= $this->db->query($Query_PO)->result();
			if($rows_PO){
				$Nomor_PO	= $rows_PO[0]->nomor_po;
			}
			
			$Template		="<a href='".site_url('Approval_invoice/Approval_invoice_process?invoice='.urlencode($Code_Inv))."' class='btn btn-sm bg-navy-active' title='APPROVAL INVOICE'> <i class='fa fa-long-arrow-right'></i> </a>";
			
			
			$nestedData		= array();
			$nestedData[]	= $Template;
			$nestedData[]	= $row['invoice_no'];
			$nestedData[]	= date('d F Y',strtotime($row['datet']));
			$nestedData[]	= $Customer;
			$nestedData[]	= $Total;
			$nestedData[]	= $Nomor_SO;
			$nestedData[]	= $Nomor_PO;
			
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
	
	function Approval_invoice_process(){
		$rows_header	= $rows_detail = array();
		if($this->input->get()){
			$Code_Inv		= urldecode($this->input->get('invoice'));
			$rows_header	= $this->db->get_where('invoices',array('id'=>$Code_Inv))->result();
			$rows_detail	= $this->db->get_where('invoice_details',array('invoice_id'=>$Code_Inv))->result();
		}
		$Arr_Akses			= $this->Arr_Akses;
		$data = array(
			'title'			=> 'APPROVAL INVOICE',
			'action'		=> 'Approval_invoice_process',
			'akses_menu'	=> $Arr_Akses,
			'rows_header'	=> $rows_header,
			'rows_detail'	=> $rows_detail
		);
		
		$this->load->view($this->folder.'/v_invoice_approval_proses',$data);
	}
	
	

	
	function save_approval_invoice_process(){
		$rows_Return	= array(
			'status'		=> 2,
			'pesan'			=> 'No Record was found...'
		);
		if($this->input->post()){
			//echo"<pre>";print_r($this->input->post());exit;
			
			$Created_By		= $this->session->userdata('siscal_userid');
			$Created_Date	= date('Y-m-d H:i:s');
			
			
			$Code_Invoice	= $this->input->post('code_invoice');
			$Status_Approval= $this->input->post('sts_approve');
			$Reject_Reason	= $this->input->post('reject_reason');			
			$CodeTrans		= date('YmdHis');
			
			$rows_Invoice	= $this->db->get_where('invoices',array('id'=>$Code_Invoice))->result();
			if($rows_Invoice[0]->status !== 'OPN'){
				$rows_Return		= array(
					'status'		=> 2,
					'pesan'			=> 'Data has been modified by other process...'
				);
			}else{
				$this->load->library('user_agent');
		
				$HTTP_USER_AGENT = $_SERVER['HTTP_USER_AGENT'];
				
				$iPad       = stripos($HTTP_USER_AGENT, "iPad");
				$Tablets    = stripos($HTTP_USER_AGENT, "Tablet");
				
				
				$ip         = cek_ip_client();
				$browser    = $this->agent->browser().' '.$this->agent->version();
				$platform   = $this->agent->platform();
				
				// CEK, MENGGUNAKAN DEVICE APA?
				if ($this->agent->is_robot()){					
					$device = 'robot';				
				} else if ($iPad OR $Tablets){					
					$device = 'tablet';				
				} else if ($this->agent->is_mobile()){					
					$device = 'mobile';				
				} else if($this->agent->is_browser()){					
					$device = 'desktop';					
				} else {					
					$device = 'others';
				}

			      
				$this->db->trans_begin();
				$Pesan_Error	= '';
				
				$RandCode		= $Code_Invoice.rand(0,1000000000000);
				$HashKey		= GenerateHashKey($RandCode);
				$CodeHash		= GenerateHashText($rows_Invoice[0]->invoice_no,$HashKey);
				
				$Ins_Log		= array(
					'code_invoice'	=> $CodeHash,
					'key_hash'		=> $HashKey,
					'invoice'		=> $Code_Invoice,
					'sts_approve'	=> $Status_Approval,
					'approve_by'	=> $Created_By,
					'approve_date'	=> $Created_Date,
					'reason'		=> $Reject_Reason,
					'ip_address'	=> $ip,
					'agent'			=> $HTTP_USER_AGENT,
					'device'		=> $platform . ' | ' . $browser . ' | ' . $device
				);
				
				$Has_Ins_Log	= $this->db->insert('log_approval_invoices',$Ins_Log);
				if($Has_Ins_Log !== TRUE){
					$Pesan_Error	= 'Error Insert Log Approval Invoice';
				}
				
				$Upd_invoice	= array(
					'status'		=> $Status_Approval,
					'approve_by'	=> $Created_By,
					'approve_date'	=> $Created_Date,
					'reject_reason'	=> $Reject_Reason,
					'code_hash'		=> $CodeHash
				);
				
				
				
				if($Status_Approval == 'APV'){
					$Upd_invoice['flag_qr']		= 'Y';
					$Link_URL		= 'https://sentral.dutastudy.com/Siscal_CRM/index.php/InvoiceGenerate/InvoiceAuthorized/'.$CodeHash;
					$GenerateQRCode	= $this->GenerateQRImage($CodeHash,'QRCode',$Link_URL);
				}else{
					$Upd_invoice['dpp']			= 0;
					$Upd_invoice['diskon']		= 0;
					$Upd_invoice['total_dpp']	= 0;
					$Upd_invoice['ppn']			= 0;
					$Upd_invoice['grand_tot']	= 0;
					$Upd_invoice['pph23']		= 0;
					$Upd_invoice['no_faktur']	= null;
					$Upd_invoice['flag_proses']	= 'N';
					
					$rows_Detail	= $this->db->get_where('invoice_details',array('invoice_id'=>$Code_Invoice))->result();
					$ArrayQuot		= $ArrayLetter	= array();
					if($rows_Detail){
						foreach($rows_Detail as $keyDetail=>$valDetail){
							$Tipe_Alat	= $valDetail->tipe;
							$Qty_ALat	= $valDetail->qty;
							$Code_Quot	= $valDetail->quotation_id;
							$Code_SO	= $valDetail->letter_order_id;
							$Code_Detail= $valDetail->detail_id;
							
							if($Tipe_Alat == 'I'){
								if($Qty_ALat > 0){
									$Upd_Delivery	= "UPDATE quotation_deliveries SET pros_invoice = pros_invoice - ".$Qty_ALat." WHERE id = '".$Code_Detail."'";
									$Has_Upd_Delievry	= $this->db->query($Upd_Delivery);
									if($Has_Upd_Delievry !== TRUE){
										$Pesan_Error	= 'Error Update Quotation Delivery';
									}
								}
							}else if($Tipe_Alat == 'A'){
								$Upd_Accomodation		= "UPDATE quotation_accommodations SET pros_invoice = 'N' WHERE id = '".$Code_Detail."'";
								$Has_Upd_Accomodation	= $this->db->query($Upd_Accomodation);
								if($Has_Upd_Accomodation !== TRUE){
									$Pesan_Error	= 'Error Update Quotation Accommodation';
								}
							}
							
							$ArrayLetter[$Code_SO]	= $Code_SO;
							$ArrayQuot[$Code_Quot]	= $Code_Quot;
						}
						
					}
					
					# UPDATE INVOICE DETAIL ##
					$Upd_Inv_Detail	= "UPDATE invoice_details SET price = 0, hpp = 0, discount = 0, total_discount = 0, total_harga = 0 WHERE invoice_id = '".$Code_Invoice."'";
					$Has_Upd_Inv_Detail	= $this->db->query($Upd_Inv_Detail);
					if($Has_Upd_Inv_Detail !== TRUE){
						$Pesan_Error	= 'Error Update Invoice Detail';
					}
					
					# UPDATE QUOTATION ##
					if($ArrayQuot){
						$Imp_Quot			= implode("','",$ArrayQuot);
						$Upd_Quotation		= "UPDATE quotations SET stat_gen = 'OPEN' WHERE id IN('".$Imp_Quot."')";
						$Has_Upd_Quotation	= $this->db->query($Upd_Quotation);
						if($Has_Upd_Quotation !== TRUE){
							$Pesan_Error	= 'Error Update Quotation';
						}
					}
					
					# UPDATE SERVICE ORDER ##
					if($ArrayLetter){
						$Imp_Letter			= implode("','",$ArrayLetter);
						$Upd_Order			= "UPDATE letter_orders SET flag_invoice = 'N' WHERE id IN('".$Imp_Letter."')";
						$Has_Upd_Order		= $this->db->query($Upd_Order);
						if($Has_Upd_Order !== TRUE){
							$Pesan_Error	= 'Error Update Service Order';
						}
					}
					
					# UPDATE FAKTUR DETAIL ##
					$Upd_Faktur	= "UPDATE faktur_no_details SET nofaktur = NULL,tglfaktur =NULL,noinvoice = NULL,tglinvoice = NULL,sts = 0 WHERE noinvoice = '".$rows_Invoice[0]->invoice_no."'";					
					$Has_Upd_Faktur		= $this->db->query($Upd_Faktur);
					if($Has_Upd_Faktur !== TRUE){
						$Pesan_Error	= 'Error Update Faktur';
					}
					
					# DELETE AR ##
					$Del_AR			= "DELETE FROM account_receivables WHERE invoice_no = '".$rows_Invoice[0]->invoice_no."'";
					$Has_Del_AR		= $this->db->query($Del_AR);
					if($Has_Del_AR !== TRUE){
						$Pesan_Error	= 'Error Delete Piutang';
					}
				}
				
				$Has_Upd_Invoice	= $this->db->update('invoices',$Upd_invoice,array('id'=>$Code_Invoice));
				if($Has_Upd_Invoice !== TRUE){
					$Pesan_Error	= 'Error Update Invoice';
				}
				
				
				if ($this->db->trans_status() != TRUE || !empty($Pesan_Error)){
					$this->db->trans_rollback();
					$rows_Return		= array(
						'status'		=> 2,
						'pesan'			=> 'Save Process  Failed, please try again...'
					);
					history('Approval Invoice '.$Code_Invoice.' - '.$rows_Invoice[0]->invoice_no.' - '.$Pesan_Error);
				}else{
					$this->db->trans_commit();
					$rows_Return		= array(
						'status'		=> 1,
						'pesan'			=> 'Save process success. Thank you & have a nice day......'
					);
					history('Approval Invoice '.$Code_Invoice.' - '.$rows_Invoice[0]->invoice_no);
				}
				
			}
		}
		echo json_encode($rows_Return);
	}
	
	function GenerateQRImage($Nama_File ='',$Location='',$Link_URL=''){
		$sroot = $_SERVER['DOCUMENT_ROOT'];
		include $sroot.'/Siscal_Dashboard/application/libraries/phpqrcode/qrlib.php';
		//$this->load->library('phpqrcode/qrlib');
		
		//$File_Path	= './assets/file/'.$Location.'/'.$Nama_File.'.png';
		$File_Path	= $this->file_location.$Location.'/'.$Nama_File.'.png';
		$Logo_Path	= $this->file_location.$Location.'/logo.png';
		
		$Label_Link	= $Link_URL;
		QRcode::png($Label_Link,$File_Path , QR_ECLEVEL_L, 11.45,0);
		
		$QR 		= imagecreatefrompng($File_Path);
		$logo 		= imagecreatefrompng($Logo_Path);
		
		$QR_width 		= imagesx($QR);
		$QR_height 		= imagesy($QR);

		$logo_width 	= imagesx($logo);
		$logo_height 	= imagesy($logo);

		// Scale logo to fit in the QR Code
		$logo_qr_width 	= $QR_width/3;
		$scale 			= $logo_width/$logo_qr_width;
		$logo_qr_height = $logo_height/$scale;
		
		list($newwidth, $newheight) = getimagesize($Logo_Path);
		$out 			= imagecreatetruecolor($QR_width, $QR_width);
		imagecopyresampled($out, $QR, 0, 0, 0, 0, $QR_width, $QR_height, $QR_width, $QR_height);
		imagecopyresampled($out, $logo, $QR_width/2.65, $QR_height/2.65, 0, 0, $QR_width/4, $QR_height/4, $newwidth, $newheight);
		
		imagepng($out,$File_Path);
		imagedestroy($out);

		
		## Change image color ##
		
		$im = imagecreatefrompng($File_Path);
		$r = 44;$g = 62;$b = 80;
		for($x=0;$x<imagesx($im);++$x){
			for($y=0;$y<imagesy($im);++$y){
				$index 	= imagecolorat($im, $x, $y);
				$c   	= imagecolorsforindex($im, $index);
				if(($c['red'] < 100) && ($c['green'] < 100) && ($c['blue'] < 100)) { // dark colors
					// here we use the new color, but the original alpha channel
					$colorB = imagecolorallocatealpha($im, 0x12, 0x2E, 0x31, $c['alpha']);
					imagesetpixel($im, $x, $y, $colorB);
				}
			}
		}
		imagepng($im,$File_Path);
		imagedestroy($im);
		
	}
	
	
	
}