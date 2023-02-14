<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Schedule_order extends CI_Controller { 
	public function __construct(){
        parent::__construct();	
		
		$this->load->database();
        if(!$this->session->userdata('isSISCALlogin')){
			redirect('login');
		}
		$this->load->model('master_model');
		$controller				= ucfirst(strtolower($this->uri->segment(1)));
		$this->Arr_Akses		= getAcccesmenu($controller);
		
		$this->folder			= 'Schedule';
		$this->file_attachement	= $this->config->item('link_file');
		$this->file_location	= $this->config->item('location_file');
    }

	public function index(){
		$Arr_Akses			= $this->Arr_Akses;
		if($Arr_Akses['read'] !='1'){
			$this->session->set_flashdata("alert_data", "<div class=\"alert alert-warning\" id=\"flash-message\">You Don't Have Right To Access This Page, Please Contact Your Administrator....</div>");
			redirect(site_url('dashboard'));
		}
		
		$data = array(
			'title'			=> 'SCHEDULE',
			'action'		=> 'index',
			'akses_menu'	=> $Arr_Akses
		);
		history('View List Schedule');
		$this->load->view($this->folder.'/v_schedule_order',$data);
	}
	function get_data_display(){
		$Arr_Akses		= $this->Arr_Akses;
		
		$WHERE			= "1=1";
		
		$Month_Find		= $this->input->post('bulan');
		$Year_Find		= $this->input->post('tahun');
		$requestData	= $_REQUEST;
		
		$like_value     = $requestData['search']['value'];
        $column_order   = $requestData['order'][0]['column'];
        $column_dir     = $requestData['order'][0]['dir'];
        $limit_start    = $requestData['start'];
        $limit_length   = $requestData['length'];
		
		$columns_order_by = array(
			0 => 'head_sched.nomor',
			1 => 'head_sched.datet',
			2 => 'head_sched.customer_name',
			3 => 'head_quot.nomor',
			4 => 'head_so.no_so'
		);
		
		
		
		if($like_value){
			if(!empty($WHERE))$WHERE	.=" AND ";
			$WHERE	.="(
						  head_so.no_so LIKE '%".$this->db->escape_like_str($like_value)."%'
						  OR DATE_FORMAT(head_sched.datet, '%d %b %Y') LIKE '%".$this->db->escape_like_str($like_value)."%'
						  OR head_sched.customer_name LIKE '%".$this->db->escape_like_str($like_value)."%'
						  OR head_quot.nomor LIKE '%".$this->db->escape_like_str($like_value)."%'
						  OR head_sched.nomor LIKE '%".$this->db->escape_like_str($like_value)."%'
						)";
		}
		
		if($Month_Find){
			if(!empty($WHERE))$WHERE .=" AND ";
			$WHERE	.="MONTH(head_sched.datet) = '".$Month_Find."'";
		}
		
		if($Year_Find){
			if(!empty($WHERE))$WHERE .=" AND ";
			$WHERE	.="YEAR(head_sched.datet) = '".$Year_Find."'";
		}
		
		$sql = "SELECT
					head_sched.*,
					head_quot.nomor AS quotation_nomor,
					head_quot.datet AS quotation_date,
					head_quot.pono,
					head_quot.podate,
					head_quot.member_id,
					head_quot.member_name,
					head_so.no_so,
					head_so.tgl_so,
					(@row:=@row+1) AS urut
				FROM
					schedules head_sched
				INNER JOIN letter_orders head_so ON head_sched.letter_order_id=head_so.id
				INNER JOIN quotations head_quot ON head_sched.quotation_id = head_quot.id,
				(SELECT @row:=0) r 
				WHERE ".$WHERE;
		//print_r($sql);exit();
		$fetch['totalData'] 	= $this->db->query($sql)->num_rows();
		$fetch['totalFiltered']	= $this->db->query($sql)->num_rows();

		

		$sql .= " ORDER BY head_sched.datet DESC,".$columns_order_by[$column_order]." ".$column_dir." ";
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
			
			$Code_Schedule		= $row['id'];
			$Nomor_Schedule		= $row['nomor'];
			$Date_Schedule		= date('d-m-Y',strtotime($row['datet']));
			$Status_Schedule	= $row['status'];
			$Nomor_SO			= $row['no_so'];
			$Date_SO			= date('d-m-Y',strtotime($row['tgl_so']));
			$Custid				= $row['customer_id'];
			$Customer			= $row['customer_name'];
			$Marketing			= strtoupper($row['member_name']);
			
			$Quot_Nomor			= $row['quotation_nomor'];
			$Quot_Date			= date('d-m-Y',strtotime($row['quotation_date']));
			$Quot_PO			= $row['pono'];
			$Quot_PO_Date		= date('d-m-Y',strtotime($row['podate']));
			
			
			
			
			$Lable_Status	= 'OPEN';
			$Color_Status	= 'bg-green-active';
			if($Status_Schedule === 'CNC'){
				$Lable_Status	= 'CANCELED';
				$Color_Status	= 'bg-orange-active';
			}else if($Status_Schedule === 'REV'){
				$Lable_Status	= 'REVISION';
				$Color_Status	= 'bg-navy-active';
			}else if($Status_Schedule === 'APV'){
				$Lable_Status	= 'APPROVE BY CUSTOMER';
				$Color_Status	= 'bg-maroon-active';
			}
			$Ket_Status		= '<span class="badge '.$Color_Status.'">'.$Lable_Status.'</span>';
			
			
			
			$Template		= '<button type="button" class="btn btn-sm btn-primary" onClick = "ActionPreview({code:\''.$Code_Schedule.'\',action :\'detail_schedule_order\',title:\'VIEW SCHEDULE\'});" title="VIEW SCHEDULE"> <i class="fa fa-search"></i> </button>';			
			if(($Arr_Akses['create'] == '1' || $Arr_Akses['update'] == '1') && $Status_Schedule === 'OPN'){
				$Template		.= '&nbsp;&nbsp;<button type="button" class="btn btn-sm btn-danger" onClick = "ActionPreview({code:\''.$Code_Schedule.'\',action :\'cancel_schedule_order\',title:\'CANCEL SCHEDULE\'});" title="CANCEL SCHEDULE"> <i class="fa fa-trash-o"></i> </button>';
				$Template		.= '&nbsp;&nbsp;<a href="'.site_url().'/Schedule_order/revisi_schedule_order?nomor_order='.urlencode($Code_Schedule).'" class="btn btn-sm btn-success" title="REVISION SCHEDULE"> <i class="fa fa-edit"></i> </a>';
				if($row['sts_email'] == 'N'){
					$Template		.= '&nbsp;&nbsp;<button type="button" class="btn btn-sm bg-maroon" onClick = "ActionPreview({code:\''.$Code_Schedule.'\',action :\'email_schedule_order\',title:\'SEND EMAIL SCHEDULE\'});" title="SEND EMAIL"> <i class="fa fa-send"></i> </button>';
				}
				
				$Template		.= '&nbsp;&nbsp;<button type="button" class="btn btn-sm bg-navy-active" onClick = "ActionPreview({code:\''.$Code_Schedule.'\',action :\'approve_schedule_order\',title:\'APPROVE SCHEDULE\'});" title="APPROVE SCHEDULE"> <i class="fa fa-check-square"></i> </button>';
			}
			
			if(($Arr_Akses['create'] == '1' || $Arr_Akses['update'] == '1') && $Status_Schedule === 'APV'){
				$Template		.= '&nbsp;&nbsp;<a href="'.site_url().'/Schedule_order/approve_reschedule_order?nomor_order='.urlencode($Code_Schedule).'" class="btn btn-sm bg-purple-active" title="APPROVE RESCHEDULE"> <i class="fa fa-recycle"></i> </a>';
				
			}
			
			if($Arr_Akses['download'] == '1'  && $Status_Schedule === 'OPN'){
				$Template		.= '&nbsp;&nbsp;<a href="'.site_url().'/Schedule_order/print_schedule?nomor_order='.urlencode($Code_Schedule).'" class="btn btn-sm btn-warning" target = "_blank" title="PRINT SCHEDULE"> <i class="fa fa-print"></i> </a>';
			}
			
			
			$nestedData		= array();
			$nestedData[]	= $Nomor_Schedule;
			$nestedData[]	= $Date_Schedule;
			$nestedData[]	= $Customer;
			$nestedData[]	= $Quot_Nomor;
			$nestedData[]	= $Nomor_SO;
			$nestedData[]	= $Ket_Status;
			$nestedData[]	= $Template;
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
	function outs_schedule_order(){
		$Arr_Akses			= $this->Arr_Akses;
		if($Arr_Akses['create'] !='1'){
			$this->session->set_flashdata("alert_data", "<div class=\"alert alert-warning\" id=\"flash-message\">You Don't Have Right To Access This Page, Please Contact Your Administrator....</div>");
			redirect(site_url('Schedule_order'));
		}
		
		$data = array(
			'title'			=> 'CREATE SCHEDULE',
			'action'		=> 'outs_schedule_order',
			'akses_menu'	=> $Arr_Akses
		);
		
		$this->load->view($this->folder.'/v_schedule_order_outs',$data);
		
		
		
	}
	
	function display_out_schedule_order(){
		$Arr_Akses			= $this->Arr_Akses;
		$User_Groupid	= $this->session->userdata('siscal_group_id');
		$WHERE			= "(
								detail.qty - detail.qty_schedule
							) > 0
							AND header.sts_so NOT IN('CNC','REV')";
							
		$requestData	= $_REQUEST;
		
		$like_value     = $requestData['search']['value'];
        $column_order   = $requestData['order'][0]['column'];
        $column_dir     = $requestData['order'][0]['dir'];
        $limit_start    = $requestData['start'];
        $limit_length   = $requestData['length'];
		
		$columns_order_by = array(
			0 => 'header.no_so',
			1 => 'header.tgl_so',
			2 => 'header.customer_name',
			3 => 'head_quot.nomor',
			4 => 'head_quot.datet'
		);
		
		
		
		if($like_value){
			if(!empty($WHERE))$WHERE	.=" AND ";
			$WHERE	.="(
						  header.no_so LIKE '%".$this->db->escape_like_str($like_value)."%'
						  OR DATE_FORMAT(header.tgl_so, '%d %b %Y') LIKE '%".$this->db->escape_like_str($like_value)."%'
						  OR header.customer_name LIKE '%".$this->db->escape_like_str($like_value)."%'
						   OR head_quot.nomor LIKE '%".$this->db->escape_like_str($like_value)."%'
						  OR DATE_FORMAT(head_quot.datet, '%d %b %Y') LIKE '%".$this->db->escape_like_str($like_value)."%'
						)";
		}
		
		
		
		$sql = "SELECT
					header.*,
					head_quot.nomor AS quot_nomor,
					head_quot.datet AS quot_date,
					head_quot.pono,
					head_quot.podate,
					(@row:=@row+1) AS urut
				FROM
					letter_order_details detail
				INNER JOIN letter_orders header ON detail.letter_order_id = header.id
				INNER JOIN quotations head_quot ON head_quot.id=header.quotation_id,
				(SELECT @row:=0) r 
				WHERE ".$WHERE."
				GROUP BY header.id
				";
		//print_r($sql);exit();
		$fetch['totalData'] 	= $this->db->query($sql)->num_rows();
		$fetch['totalFiltered']	= $this->db->query($sql)->num_rows();

		

		$sql .= " ORDER BY header.tgl_so DESC,".$columns_order_by[$column_order]." ".$column_dir." ";
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
			
			$Code_SO		= $row['id'];
			$Nomor_SO		= $row['no_so'];
			$Date_SO		= date('d M Y',strtotime($row['tgl_so']));
			$Custid			= $row['customer_id'];
			$Customer		= $row['customer_name'];
			$Quot_PO		= $row['pono'];
			$Quot_PO_Date	= date('d-m-Y',strtotime($row['podate']));
			$Nomor_Quot		= $row['quot_nomor'];
			$Date_Quot		= date('d M Y',strtotime($row['quot_date']));
			
			
			
			$Template		= '';			
			if($Arr_Akses['create'] == '1'){
				$Template		= '<a href="'.site_url().'/Schedule_order/create_schedule_order?nomor_order='.urlencode($Code_SO).'" class="btn btn-sm bg-navy-active"  title="CREATE SCHEDULE"> <i class="fa fa-calendar"></i> </a>';
			}
			
			
			
			
			$nestedData		= array();
			$nestedData[]	= $Nomor_SO;
			$nestedData[]	= $Date_SO;
			$nestedData[]	= $Customer;
			$nestedData[]	= $Nomor_Quot;
			$nestedData[]	= $Date_Quot;
			$nestedData[]	= $Template;
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
	
	function CancelScheduleProcess(){
		$rows_Return	= array(
			'status'		=> 2,
			'pesan'			=> 'No record was found...'
		);
		if($this->input->post()){
			$SalesOrder		= $this->input->post('code');
			$CodeProcess	= $this->input->post('kode_proses');
			$rows_Header	= $this->db->get_where('letter_orders',array('id'=>$SalesOrder))->row();
			$this->db->trans_begin();
			$Pesan_Error	= '';
			
			$Upd_Letter	= array(
				'reserved'	=> '1'
			);
			$Has_Upd_Order	= $this->db->update('letter_orders',$Upd_Letter,array('id'=>$SalesOrder));
			if($Has_Upd_Order !== true){
				$Pesan_Error	= 'Error Update Letter Order';
			}
			
			$Del_Temp_Schedule	= "DELETE FROM temp_schedule_orders WHERE id = '".$SalesOrder."' AND kode_proses = '".$CodeProcess."'";
			$Has_Del_Temp_Sched	= $this->db->query($Del_Temp_Schedule);
			if($Has_Upd_Order !== true){
				$Pesan_Error	= 'Error Delete Temp Schedule Order';
			}
			
			$Del_Temp_Alocate	= "DELETE FROM temp_allocations WHERE quotation_detail_id LIKE '".$rows_Header->quotation_id."%' AND kode_proses LIKE '".$CodeProcess."%'";
			$Has_Del_Temp_Aloc	= $this->db->query($Del_Temp_Alocate);
			if($Has_Del_Temp_Aloc !== TRUE){
				$Pesan_Error	= 'Error Delete Schedule Temp Allocation';
			}
			

			if ($this->db->trans_status() != TRUE || !empty($Pesan_Error)){
				$this->db->trans_rollback();
				$rows_Return		= array(
					'status'		=> 2,
					'pesan'			=> 'Cancellation Schedule Process  Failed, '.$Pesan_Error
				);
				history('Cancellation Schedule Process '.$SalesOrder.' - '.$CodeProcess.' - '.$Pesan_Error);
			}else{
				$this->db->trans_commit();
				$rows_Return		= array(
					'status'		=> 1,
					'pesan'			=> 'Cancellation Schedule Process success. Thank you & have a nice day......'
				);
				history('Cancellation Schedule Process '.$SalesOrder.' - '.$CodeProcess.' - Success');
			}
			
			
		}
		
		echo json_encode($rows_Return);
	}
	
	
	function create_schedule_order(){
		$rows_Header	= $rows_Customer = $rows_Detail = $rows_Quot =  array();
		$Kode_Proses	= date('YmdHis');
		if($this->input->get()){
			$Code_Sales		= urldecode($this->input->get('nomor_order'));
			$rows_Header	= $this->db->get_where('letter_orders',array('id'=>$Code_Sales))->row();
			$rows_Customer	= $this->db->get_where('customers',array('id'=>$rows_Header->customer_id))->row();
			$rows_Quot		= $this->db->get_where('quotations',array('id'=>$rows_Header->quotation_id))->row();
			$rows_Detail	= $this->db->get_where('letter_order_details',array('letter_order_id'=>$Code_Sales,'(qty - qty_schedule) >'=>0))->result();
			
		}
		if($rows_Detail){
			$data = array(
				'title'			=> 'CREATE SCHEDULE ORDER',
				'action'		=> 'create_schedule_order',
				'akses_menu'	=> $this->Arr_Akses,
				'rows_header'	=> $rows_Header,
				'rows_detail'	=> $rows_Detail,
				'rows_cust'		=> $rows_Customer,
				'rows_quot'		=> $rows_Quot,
				'kode_proses'	=> $Kode_Proses
			);
			
			## UPDATE LETTER ORDER JADI RESERVED ##
			$Upd_Letter	= array(
				'reserved'	=> '1'
			);
			$Has_Upd_Order	= $this->db->update('letter_orders',$Upd_Letter,array('id'=>$Code_Sales));
			
			$Ins_Temp_Schedule	= array(
				'id'			=> $Code_Sales,
				'kode_proses'	=> $Kode_Proses
			);
			$Has_Ins_Temp_Schedule	= $this->db->insert('temp_schedule_orders',$Ins_Temp_Schedule);
			
			$this->load->view($this->folder.'/v_schedule_order_add',$data);
		}else{
			$this->session->set_flashdata("alert_data", "<div class=\"alert alert-warning\" id=\"flash-message\">Sales Order Not Found. Please Try Again....</div>");
			redirect(site_url('Schedule_order'));
		}
		
		
	}
	
	function GetTeknisi(){
		$Arr_Color		= array(1=>'green','red','orange','blue','grey');
		$periode_prev	= date('Y-m-d');
		$periode_next	= date('Y-m-t',mktime(0,0,0,date('m') + 1 ,1,date('Y')));
		$Query_Teknisi	= "SELECT
								head_aloc.member_id
							FROM
								temp_allocations head_aloc
							INNER JOIN schedules head_sched ON head_sched.kode_proses = SUBSTRING(head_aloc.kode_proses,1,LENGTH(head_sched.kode_proses))
							INNER JOIN schedule_details det_sched ON head_sched.id = det_sched.schedule_id
							AND head_aloc.quotation_detail_id = det_sched.quotation_detail_id
							WHERE
								head_sched.`status` IN ('OPN', 'APV')
							AND (
								head_aloc.plan_date_start BETWEEN '".$periode_prev."'
															AND '".$periode_next."'
							)
							GROUP BY
								head_aloc.member_id";
		$rows_Find		= $this->db->query($Query_Teknisi)->result();
		$rows_Teknisi	= array();
		if($rows_Find){
			$loop	= 0;
			$warna	= 0;
			foreach($rows_Find as $KeyFind=>$valFind){
				$Code_Member	= $valFind->member_id;
				$rows_Member	= $this->db->get_where('members',array('id'=>$Code_Member))->row();
				if($rows_Member){
					$warna++;
					$Nama_Member	= $rows_Member->nama;
					
					$rows_Teknisi[$loop]['id']				= $Code_Member;
					$rows_Teknisi[$loop]['title']			= $Nama_Member;
					$rows_Teknisi[$loop]['eventColor']		= $Arr_Color[$warna];
					
					$loop++;
					if($warna==5)$warna=0;
				}
			}
			unset($rows_Find);
		}
		
		return $rows_Teknisi;
	}
	
	function GetScheduleCalibrations(){
		$rows_Teknisi	= $rows_Tool = $rows_QuotDet = $rows_Opt_Teknisi = $rows_Calendar = array();
		$Code_Urut		= $Code_QuotDet = '';
		if($this->input->post()){
			$Code_Urut		= $this->input->post('urut');
			$Code_QuotDet	= $this->input->post('code');
			
			$rows_QuotDet	= $this->db->get_where('quotation_details',array('id'=>$Code_QuotDet))->row();
			$rows_Tool		= $this->db->get_where('tools',array('id'=>$rows_QuotDet->tool_id))->row();
			
			$Query_Opt_Teknisi	= "SELECT
										head_mem.id,
										head_mem.nama
									FROM
										members head_mem
									INNER JOIN tech_skills head_skill ON head_mem.id = head_skill.member_id
									WHERE
										head_mem.division_id = 'DIV-002'
									AND head_mem.`status` = '1'
									AND find_in_set('".$rows_Tool->dimention_id."', head_skill.dimention_id)
									GROUP BY
										head_mem.id
									ORDER BY
										head_mem.nama ASC";
			$det_Teknisi		= $this->db->query($Query_Opt_Teknisi)->result();
			if($det_Teknisi){
				foreach($det_Teknisi as $keyTek=>$valTek){
					$code_Tek	= $valTek->id;
					$name_Tek	= $valTek->nama;
					$Pecah_Nama	= explode(' ',$name_Tek);
					if(is_array($Pecah_Nama)){
						$New_Nama	= $Pecah_Nama[0];
					}else{
						$New_Nama	= $name_Tek;
					}
					$rows_Opt_Teknisi[$code_Tek]	= $name_Tek;
					$rows_Teknisi[]	= array(
						'id'			=> $code_Tek,
						'title'			=> strtoupper($New_Nama),
						'color'			=> '#EC536C'
					);
					
				}
				unset($det_Teknisi);
			}
			
			
		}
		$data = array(
			'title'				=> 'CALIBRATION SCHEDULE',
			'action'			=> 'GetScheduleCalibrations',
			'akses_menu'		=> $this->Arr_Akses,
			'rows_tool'			=> $rows_Tool,
			'rows_detail'		=> $rows_QuotDet,
			'rows_option'		=> $rows_Opt_Teknisi,
			'rows_teknisi'		=> $rows_Teknisi,
			'code_urutdetail'	=> $Code_Urut,
			'code_quotdetail'	=> $Code_QuotDet
		);
		
		
		$this->load->view($this->folder.'/v_schedule_order_process',$data);
		
	}
	
	function GetTimeCalibrations(){
		$Arr_Waktu	= $Arr_Balik = array();
		if($this->input->post()){
			$Tanggal		= $this->input->post('datet');
			$Teknisi		= $this->input->post('teknisi');
			$Code_QuotDet	= $this->input->post('code');
			$Revisi			= $this->input->post('revisi');
			
			$rows_Time		= $this->db->get_where('master_times',array('sts_active'=>'Y'))->result();
			$dataWaktu		= array();
			if($rows_Time){
				foreach($rows_Time as $keyTime=>$valTime){
					$Code_Time	= $valTime->id;
					$Name_Time	= substr($valTime->time,0,5);
					$dataWaktu[$Code_Time]	= $Name_Time;
				}
				unset($rows_Time);
			}
			$Arr_Waktu	= $dataWaktu;
			$Arr_Balik	= $dataWaktu;
				
			$Query_Alocate	= "SELECT * FROM temp_allocations WHERE member_id = '".$Teknisi."' AND plan_date_start = '".$Tanggal."' ORDER BY plan_time_start ASC";
			$rows_Alocate	= $this->db->query($Query_Alocate)->result();
			if($rows_Alocate){
				$loop		=0;
				foreach($dataWaktu as $keys=>$vals){
					foreach($rows_Alocate as $key=>$value){
						$tgl_awal		= $value->plan_date_start;
						$tgl_akhir		= $value->plan_date_end;
						$quot_id		= $value->quotation_detail_id;
						$beda			= (strtotime($tgl_akhir) - strtotime($tgl_awal)) / (60*60*24);
						$waktu_awal		= substr($value->plan_time_start,0,5);
						$waktu_akhir	= substr($value->plan_time_end,0,5);
						if($beda > 0){
							$waktu_akhir	= ($beda * 24) + intval(substr($waktu_akhir,0,2)).':'.substr($waktu_akhir,3,2);
						}
						$OK	=1;
						if($Revisi !=''){
							if($Code_QuotDet==$quot_id){
								$OK	=0;
							}
						}
						
						if($vals >= $waktu_awal && $vals <=$waktu_akhir && $OK==1){
							
							if($vals==$waktu_awal){
								$fndA	= array_search($vals,$Arr_Waktu);
								if($fndA){
									unset($Arr_Waktu[$fndA]);
								}
							}
							
							if($vals==$waktu_akhir){
								$fndK	= array_search($vals,$Arr_Balik);
								if($fndK){
									unset($Arr_Balik[$fndK]);
								}
							}
							
							if($vals > $waktu_awal && $vals < $waktu_akhir){
								$fnd1	= array_search($vals,$Arr_Waktu);
								$fnd2	= array_search($vals,$Arr_Balik);
								if($fnd1){
									unset($Arr_Waktu[$fnd1]);
								}
								if($fnd2){
									unset($Arr_Balik[$fnd2]);
								}
							}
							//echo "</br>".$waktu_awal." - ".$waktu_akhir." val ".$vals." ok ".$OK." Quotation : ".$quot_id." = ".$kode_det."</br>";
							//echo"<pre>";print_r($Arr_Waktu);
							
						}
						
					}
				}
				
				unset($rows_Alocate);
				unset($dataWaktu);
			}
			
		}
		
		$a_data	= array(
			'mulai'	=> $Arr_Waktu,
			'selesai'=>$Arr_Balik
		);
		echo json_encode($a_data);
	}
	
	function display_data_calendar(){
		$Tgl_Awal 		= date('Y-m-d', strtotime('-10 days'.$this->input->get('start')));
		$Tgl_Akhir 		= date('Y-m-d', strtotime('+10 days'.$this->input->get('end')));
		$arrData		= array();
		
		$Query_Detail	= "SELECT
								head_aloc.member_id,
								head_aloc.quotation_detail_id,
								head_aloc.plan_date_start,
								head_aloc.plan_time_start,
								head_aloc.plan_date_end,
								head_aloc.plan_time_end,
								head_sched.customer_name,
								det_sched.tool_name,
								det_sched.qty AS qty_schedule,
								head_tran.id AS code_tran,
								head_tran.plan_process_date,
								head_tran.qty AS qty_tran,
								head_tran.re_qty AS reqty_tran								
							FROM
								temp_allocations head_aloc
							INNER JOIN schedules head_sched ON head_sched.kode_proses = SUBSTRING(head_aloc.kode_proses,1,LENGTH(head_sched.kode_proses))
							INNER JOIN schedule_details det_sched ON head_sched.id = det_sched.schedule_id
							AND head_aloc.quotation_detail_id = det_sched.quotation_detail_id
							AND head_aloc.sts_split = det_sched.sts_split
							LEFT JOIN trans_details head_tran ON head_tran.id=det_sched.id
							WHERE
								head_sched.`status` IN ('OPN', 'APV')
							AND (
								head_aloc.plan_date_start BETWEEN '".$Tgl_Awal."'
															AND '".$Tgl_Akhir."'
							)
							ORDER BY
							head_aloc.plan_date_start ASC, head_aloc.plan_time_start ASC";
		$rows_FindDet	= $this->db->query($Query_Detail)->result();
		if($rows_FindDet){
			$loop	= 0;
			foreach($rows_FindDet as $KeyDet=>$valDet){
				$loop++;
				
				$Quot_Detail		= $valDet->quotation_detail_id;
				$Code_Mmeber		= $valDet->member_id;
				$Code_Unik			= $Quot_Detail.'-'.$Code_Mmeber.'-'.$loop;
				$Plan_Start_Date	= $valDet->plan_date_start;
				$Plan_Start_Time	= $valDet->plan_time_start;
				$Plan_End_Date		= $valDet->plan_date_end;
				$Plan_End_Time		= $valDet->plan_time_end;
				
				$Name_Tool			= $valDet->tool_name;
				$Qty_Schedule		= $valDet->qty_schedule;
				$Code_Trans			= $valDet->code_tran;
				$Process_Trans		= $valDet->plan_process_date;
				$Qty_Trans			= $valDet->qty_tran;
				$ReQty_Trans		= $valDet->reqty_tran;
				
				$Jam_Mulai			= substr($Plan_Start_Time,0,5);
				
				$bgcolor 			= '#6C757D';
				$waktuSrvs 			= '';
				if($Jam_Mulai < '12:00'){
					$bgcolor 	= '#58DB83';
				}else if($Jam_Mulai >= '12:00' && $Jam_Mulai <= '15:00'){
					$bgcolor 	= '#F5B225';
				}else if($Jam_Mulai > '15:00' && $Jam_Mulai <= '18:00'){
					$bgcolor 	= '#035CA8';
				}else if($Jam_Mulai > '18:00'){
					$bgcolor 	= '#EC536C';
				}
				
				$bgBordercolor 		= '#FFFFFF';
				$borderWidth 		= '';
				$title 				= $Name_Tool.' - '.$valDet->customer_name;
				if(isset($Code_Trans) && $Code_Trans){
					if($Process_Trans == $Plan_Start_Date){
						$Qty_Proses = $Qty_Trans;
					}else{
						$Qty_Proses = $ReQty_Trans;
					}
				}else{
					$Qty_Proses = $Qty_Schedule;
				}
				
				
				$Descr 				= 'Alat : '.$Name_Tool.', Qty : '.$Qty_Proses.', Kalibrasi Jam '.date('H:i', strtotime($Plan_Start_Time)).' - '.date('H:i', strtotime($Plan_End_Time)).'. Customer : '.$valDet->customer_name;
				
				$arrData[] 	= array("id"					=> $Code_Unik,
								"title"				=> strtoupper($title),
								"description"		=> strtoupper($Descr),
								"status"			=> 'Kalibrasi - '.$Name_Tool,
								"start"				=> $Plan_Start_Date.'T'.$Plan_Start_Time,
								"end"				=> $Plan_End_Date.'T'.$Plan_End_Time,
								"backgroundColor"	=> $bgcolor,
								"resourceId"		=> $Code_Mmeber,
								"borderColor"		=> $bgBordercolor,
								"className"			=> $borderWidth
							);
			}
			unset($rows_FindDet);
		}
		echo json_encode($arrData);
	}
	
	function save_insert_schedule_tools(){
		$rows_Return	= array(
			'status'		=> 2,
			'pesan'			=> 'No record was found...'
		);
		
		if($this->input->post()){
			//echo "<pre>";print_r($this->input->post());exit;
			
			$Code_Teknisi		= $this->input->post('jadwal_teknisi');
			$Date_Calibration	= $this->input->post('jadwal_tanggal');
			$Start_Time_Cal		= $this->input->post('jadwal_time_awal');
			$End_Time_Cal		= $this->input->post('jadwal_time_akhir');
			$Trip_Time			= $this->input->post('jadwal_waktu_tempuh');
			$Code_QuotDet		= $this->input->post('kode');
			$Code_Process		= $this->input->post('kode_proses');
			$Flag_Split			= $this->input->post('sts_split');
			
			$Name_Teknisi		= '-';
			$rows_Member		= $this->db->get_where('members',array('id'=>$Code_Teknisi))->row();
			if($rows_Member){
				$Name_Teknisi	= strtoupper($rows_Member->nama);
			}
			
			if($Trip_Time > 0){
				$jam_bagi1	=explode(':',$Start_Time_Cal);
				$jam_bagi2	=explode(':',$End_Time_Cal);
				$Start_Time_Cal	=date('H:i',mktime(intval($jam_bagi1[0]),intval($jam_bagi1[1]) - $Trip_Time,0,date('m'),date('d'),date('Y')));
				$End_Time_Cal	=date('H:i',mktime(intval($jam_bagi2[0]),intval($jam_bagi2[1]) + $Trip_Time,0,date('m'),date('d'),date('Y')));			
			}
			$Start_Time_Cal	= $Start_Time_Cal.':00';
			$End_Time_Cal	= $End_Time_Cal.':00';
			
			
			$dataIns		= array(
				'quotation_detail_id'	=> $Code_QuotDet,
				'member_id'				=> $Code_Teknisi,
				'plan_date_start'		=> $Date_Calibration,
				'plan_time_start'		=> $Start_Time_Cal,
				'plan_date_end'			=> $Date_Calibration,
				'plan_time_end'			=> $End_Time_Cal,
				'status'				=> 'RES',
				'kode_proses'			=> $Code_Process,
				'sts_split'				=> $Flag_Split
			);
			
			$Query_Find	= "SELECT
								*
							FROM
								temp_allocations
							WHERE
								plan_date_start = '".$Date_Calibration."'
							AND member_id = '".$Code_Teknisi."'
							AND (
								(
									plan_time_start > '".$Start_Time_Cal."'
									AND plan_time_start < '".$End_Time_Cal."'
								)
								OR (
									plan_time_end > '".$Start_Time_Cal."'
									AND plan_time_end < '".$End_Time_Cal."'
								)
								OR (
									plan_time_start <= '".$Start_Time_Cal."'
									AND plan_time_end >= '".$End_Time_Cal."'
								)
							)
							AND quotation_detail_id <> '".$Code_QuotDet."'
							AND kode_proses <> '".$Code_Process."'";
			$rows_Find	= $this->db->query($Query_Find)->num_rows();
			if($rows_Find > 0){
				$rows_Return	= array(
					'status'		=> 2,
					'pesan'			=> 'Schedule already exist in list...',
					'member_name'	=> $Name_Teknisi
				);
			}else{
				$Pesan_Error	= '';
				$this->db->trans_begin();
				$Query_Same	= "SELECT
								*
							FROM
								temp_allocations
							WHERE
								quotation_detail_id = '".$Code_QuotDet."'
							AND kode_proses = '".$Code_Process."'";
				$rows_Same	= $this->db->query($Query_Same)->num_rows();
				
				if($rows_Same > 0){
					$Delete_Same	= "DELETE FROM
											temp_allocations
										WHERE
											quotation_detail_id = '".$Code_QuotDet."'
										AND kode_proses = '".$Code_Process."'";
					$Has_Del_Same	= $this->db->query($Delete_Same);
					if($Has_Del_Same !== true){
						$Pesan_Error	= 'Error Delete Temp Alocate - Same Data';
					}
				}
				
				$Has_Ins_Alocate	= $this->db->insert('temp_allocations',$dataIns);
				if($Has_Ins_Alocate !== true){
					$Pesan_Error	= 'Error Insert Temp Alocate - New Data';
				}
				
				if ($this->db->trans_status() != TRUE || !empty($Pesan_Error)){
					$this->db->trans_rollback();
					$rows_Return		= array(
						'status'		=> 2,
						'pesan'			=> 'Create Sales Order Process  Failed, '.$Pesan_Error,
						'member_name'	=> $Name_Teknisi
					);
					history('Insert Tool Schedule Date '.$Code_QuotDet.' - '.$Code_Process.' - '.$Pesan_Error);
				}else{
					$this->db->trans_commit();
					$rows_Return		= array(
						'status'		=> 1,
						'pesan'			=> 'Create Sales Order success. Thank you & have a nice day......',
						'member_name'	=> $Name_Teknisi
					);
					history('Insert Tool Schedule Date '.$Code_QuotDet.' - '.$Code_Process.' - Success');
				}
				
			}

		}
		echo json_encode($rows_Return);
	}
	
	function save_create_schedule_order(){
		$rows_Return	= array(
			'status'		=> 2,
			'pesan'			=> 'No record was found...'
		);
		
		if($this->input->post()){
			//echo "<pre>";print_r($this->input->post());exit;
			
			
			$Created_By		= $this->session->userdata('siscal_username');
			$Created_Id		= $this->session->userdata('siscal_userid');
			$Created_Date	= date('Y-m-d H:i:s');
			
			
			$Code_Sales		= $this->input->post('letter_order_id');
			$Code_Process	= $this->input->post('kode_proses');
			$Nocust			= $this->input->post('customer_id');
			$Code_Quot		= $this->input->post('quotation_id');
			$Customer		= strtoupper($this->input->post('customer_name'));
			$PIC_Name		= strtoupper($this->input->post('pic_name'));
			$Address		= strtoupper($this->input->post('address'));
			$Notes			= strtoupper($this->input->post('notes'));
			
			$detDetail		= $this->input->post('detDetail');
			
			$Schedule_Date	= date('Y-m-d');
			
			
			$Pesan_Error	= '';
			$this->db->trans_begin();
			
			$Date_Now		= date('Y-m-d');
			$Tahun_Now		= date('Y');
			$Month_Now		= date('m');
			$Bulan_Now		= date('n');
			$YearMonth		= date('Ym');
			
			$RomawiMonth	= getRomawi($Bulan_Now);
			
			
			## AMBIL NOMOR URUT ##
			$Urut_Code		= $Urut_Nomor	= 1;
			$Qry_Urut_Code	= "SELECT
									MAX(
										CAST(
											SUBSTRING_INDEX(id, '-' ,-1) AS UNSIGNED
										)
									) AS nomor_urut
								FROM
									schedules
								WHERE
									YEAR(datet) = '".$Tahun_Now."'";
			$rows_Urut_Code	= $this->db->query($Qry_Urut_Code)->row();
			if($rows_Urut_Code){
				$Urut_Code	= intval($rows_Urut_Code->nomor_urut) + 1;
			}
			$Pref_Urut		= sprintf('%05d',$Urut_Code);
			if($Urut_Code >=100000){
				$Pref_Urut	= $Urut_Code;
			}
			
			$Code_Schedule		= 'SCH-'.$YearMonth.'-'.$Pref_Urut;
			
			$Qry_Urut_Nomor		= "SELECT
									MAX(
										CAST(
											SUBSTRING_INDEX(nomor, '/' ,1) AS UNSIGNED
										)
									) AS nomor_urut
								FROM
									schedules
								WHERE
									datet LIKE '".date('Y-m')."-%'";
			$rows_Urut_Nomor	= $this->db->query($Qry_Urut_Nomor)->row();
			if($rows_Urut_Nomor){
				$Urut_Nomor	= intval($rows_Urut_Nomor->nomor_urut) + 1;
			}
			$Pref_Nomor		= sprintf('%03d',$Urut_Nomor);
			if($Urut_Nomor >=1000){
				$Pref_Nomor	= $Urut_Nomor;
			}
			
			$Nomor_Schedule	= $Pref_Nomor.'/SCH-N/'.$RomawiMonth.'/'.date('y');
			
			
			$Ins_Header			= array(
				'id'				=> $Code_Schedule,
				'nomor'				=> $Nomor_Schedule,
				'datet'				=> $Date_Now,
				'letter_order_id'	=> $Code_Sales,
				'quotation_id'		=> $Code_Quot,
				'customer_id'		=> $Nocust,
				'customer_name'		=> $Customer,
				'status'			=> 'OPN',
				'notes'				=> $Notes,
				'revisi'			=> 0,
				'created_by'		=> $Created_Id,
				'created_date'		=> $Created_Date,
				'kode_proses'		=> $Code_Process
			);
			$Has_Ins_Header			= $this->db->insert('schedules',$Ins_Header);
			if($Has_Ins_Header !== TRUE){
				$Pesan_Error	= 'Error Insert Schedule Header...';
			}
			
			if($detDetail){
				$intL	= 0;
				foreach($detDetail as $keyDet=>$valDet){
					$intL++;
					$Code_SchedDet	= $Code_Schedule.'-'.$intL;
					
					$Code_Detail	= $valDet['code_detail'];
					$Code_QuotDet	= $valDet['quotation_detail_id'];
					
					$Code_Tool		= $valDet['tool_id'];
					$Name_Tool		= $valDet['tool_name'];
					$Cust_Tool		= $valDet['tool_cust'];
					$Labs			= $valDet['labs'];
					$Insitu			= $valDet['insitu'];
					$Subcon			= $valDet['subcon'];
					$Qty_SO			= $valDet['qty_process'];
					$Flag_Split		= $valDet['sts_split'];
					$Waktu_Tempuh	= $valDet['waktu_tempuh'];
					$Urut_ID		= $valDet['urut_id'];
					$Supplier_Code	= $valDet['supplier_id'];
					$Qty_Process	= $valDet['qty'];
					
					$Pickup_Date	= $Cals_Date	= $Send_Date = $Subcon_Pickup = $Subcon_Send = $Time_Start = $Time_End = NULL;
					$Code_Teknisi 	= $Name_Teknisi = '';
					
					if(isset($valDet['pick_date']) && !empty($valDet['pick_date'])){
						$Pickup_Date	= $valDet['pick_date'];
					}
					
					if(isset($valDet['process_date']) && !empty($valDet['process_date'])){
						$Cals_Date	= $valDet['process_date'];
					}
					
					if(isset($valDet['delivery_date']) && !empty($valDet['delivery_date'])){
						$Send_Date	= $valDet['delivery_date'];
					}
					
					if(isset($valDet['subcon_pick_date']) && !empty($valDet['subcon_pick_date'])){
						$Subcon_Pickup	= $valDet['subcon_pick_date'];
					}
					
					if(isset($valDet['subcon_send_date']) && !empty($valDet['subcon_send_date'])){
						$Subcon_Send	= $valDet['subcon_send_date'];
					}
					
					if(isset($valDet['jam_awal']) && !empty($valDet['jam_awal'])){
						$Time_Start	= $valDet['jam_awal'].':00';
					}
					
					if(isset($valDet['jam_akhir']) && !empty($valDet['jam_akhir'])){
						$Time_End	= $valDet['jam_akhir'].':00';
					}
					
					if(isset($valDet['member_id']) && !empty($valDet['member_id'])){
						$Code_Teknisi	= $valDet['member_id'];
					}
					
					if(isset($valDet['member_name']) && !empty($valDet['member_name'])){
						$Name_Teknisi	= $valDet['member_name'];
					}
					
					
					$Upd_Letter_Detail	= "UPDATE letter_order_details SET qty_schedule = qty_schedule + ".$Qty_Process." WHERE id = '".$Code_Detail."'";
					$Has_Upd_SODet		= $this->db->query($Upd_Letter_Detail);
					if($Has_Upd_SODet !== TRUE){
						$Pesan_Error	= 'Error Update Sales Order Detail...';
					}
					
					
					
					$Ins_Detail		= array(
						'id'					=> $Code_SchedDet,
						'schedule_id'			=> $Code_Schedule,
						'quotation_detail_id'	=> $Code_QuotDet,
						'tool_id'				=> $Code_Tool,
						'tool_name'				=> $Cust_Tool,
						'qty'					=> $Qty_Process,
						'pick_date'				=> $Pickup_Date,
						'process_date'			=> $Cals_Date,
						'delivery_date'			=> $Send_Date,
						'selected'				=> 0,
						'insitu'				=> $Insitu,
						'labs'					=> $Labs,
						'subcon'				=> $Subcon,
						'subcon_send_date'		=> $Subcon_Send,
						'subcon_pick_date'		=> $Subcon_Pickup,
						'waktu_tempuh'			=> $Waktu_Tempuh,
						'sts_split'				=> $Flag_Split,
						'urut_id'				=> $Urut_ID
					);
					
					$Has_Ins_Detail	= $this->db->insert('schedule_details',$Ins_Detail);
					if($Has_Ins_Detail !== TRUE){
						$Pesan_Error	= 'Error Insert Schedule Detail...';
					}
					
					if(!empty($Time_Start) && $Time_Start !== NULL && !empty($Time_End) && $Time_End !== NULL){
						$Ins_Detail_Aloc		= array(
							'schedule_detail_id'	=> $Code_SchedDet,
							'schedule_id'			=> $Code_Schedule,
							'quotation_detail_id'	=> $Code_QuotDet,
							'tool_id'				=> $Code_Tool,
							'tool_name'				=> $Cust_Tool,
							'qty'					=> $Qty_Process,
							'plan_date_start'		=> $Cals_Date,
							'plan_time_start'		=> $Time_Start,
							'plan_date_end'			=> $Cals_Date,
							'plan_time_end'			=> $Time_End,
							'member_id'				=> $Code_Teknisi,
							'member_name'			=> $Name_Teknisi,
							'waktu_tempuh'			=> $Waktu_Tempuh
						);
						
						$Has_Ins_Detail_Aloc	= $this->db->insert('schedule_allocations',$Ins_Detail_Aloc);
						if($Has_Ins_Detail_Aloc !== TRUE){
							$Pesan_Error	= 'Error Insert Schedule Allocation...';
						}
					}
				}
			}
			
			
			
			$Upd_Letter	= array(
				'sts_so'		=> 'SCH'
			);
			
			$Has_Upd_Letter			= $this->db->update('letter_orders',$Upd_Letter,array('id'=>$Code_Sales));
			if($Has_Upd_Letter !== TRUE){
				$Pesan_Error	= 'Error Update Sales Order...';
			}
			
			if ($this->db->trans_status() != TRUE || !empty($Pesan_Error)){
				$this->db->trans_rollback();
				$rows_Return		= array(
					'status'		=> 2,
					'pesan'			=> 'Create Schedule Order Process  Failed, '.$Pesan_Error
				);
				history('Create Schedule Order '.$Nomor_Schedule.' - '.$Pesan_Error);
			}else{
				$this->db->trans_commit();
				$rows_Return		= array(
					'status'		=> 1,
					'pesan'			=> 'Create Schedule Order success. Thank you & have a nice day......'
				);
				history('Create Schedule Order '.$Nomor_Schedule.' - Success ');
			}

		}
		
		echo json_encode($rows_Return);
	}
	
	
	function print_schedule(){
		$rows_Header	= $rows_Detail =  $rows_Quot = $rows_Cust = $rows_Alocate = array();
		
		if($this->input->get()){
			$Code_Process	= urldecode($this->input->get('nomor_order'));
			
			$rows_Header	= $this->db->get_where('schedules',array('id'=>$Code_Process))->row_array();
			$rows_Detail	= $this->db->get_where('schedule_details',array('schedule_id'=>$Code_Process))->result_array();
			$rows_Alocate	= $this->db->get_where('schedule_allocations',array('schedule_id'=>$Code_Process))->result_array();
			$rows_Quot		= $this->db->get_where('quotations',array('id'=>$rows_Header['quotation_id']))->row_array();
			$rows_Cust		= $this->db->get_where('customers',array('id'=>$rows_Header['customer_id']))->row_array();
		}
		
		
		$data = array(
			'title'			=> 'SCHEDULE PRINT',
			'action'		=> 'print_schedule',
			'rows_header'	=> $rows_Header,
			'rows_detail'	=> $rows_Detail,
			'rows_quot'		=> $rows_Quot,
			'rows_cust'		=> $rows_Cust,
			'rows_alocate'	=> $rows_Alocate
		);	
		
		$this->load->view($this->folder.'/v_schedule_order_print',$data);
	}
	
	/*
	| ----------------------------- |
	|  		DETAIL SCHEDULE 		|
	| ----------------------------- |
	*/
	function detail_schedule_order(){
		$OK_Proses	= 0;
		$rows_Header	= $rows_Detail =  $rows_Quot = $rows_Customer = $rows_Letter = $rows_Allocation  = array();
		
		if($this->input->post()){
			$Code_Process	= urldecode($this->input->post('code'));
			
			$rows_Header	= $this->db->get_where('schedules',array('id'=>$Code_Process))->row();
			$rows_Detail	= $this->db->get_where('schedule_details',array('schedule_id'=>$Code_Process))->result_array();
			$rows_Quot		= $this->db->get_where('quotations',array('id'=>$rows_Header->quotation_id))->row();
			$rows_Letter	= $this->db->get_where('letter_orders',array('id'=>$rows_Header->letter_order_id))->row();
			$rows_Customer	= $this->db->get_where('customers',array('id'=>$rows_Header->customer_id))->row();
			
			$rows_Jadwal	= $this->db->get_where('schedule_allocations',array('schedule_id'=>$Code_Process))->result_array();
			if($rows_Jadwal){
				foreach($rows_Jadwal as $keyJadwal=>$valJadwal){
					$Alat									= $valJadwal['schedule_detail_id'];
					$rows_Allocation[$Alat]['member_id']	= $valJadwal['member_id'];
					$rows_Allocation[$Alat]['member_name']	= $valJadwal['member_name'];
					$rows_Allocation[$Alat]['start_date']	= $valJadwal['plan_date_start'];
					$rows_Allocation[$Alat]['start_time']	= substr($valJadwal['plan_time_start'],0,5);
					$rows_Allocation[$Alat]['end_date']		= $valJadwal['plan_date_end'];
					$rows_Allocation[$Alat]['end_time']		= substr($valJadwal['plan_time_end'],0,5);
				}
				unset($rows_Jadwal);
			}
			
		}
		
		
		$data = array(
			'title'			=> 'SCHEDULE PREVIEW',
			'action'		=> 'detail_schedule_order',
			'akses_menu'	=> $this->Arr_Akses,
			'rows_header'	=> $rows_Header,
			'rows_detail'	=> $rows_Detail,
			'rows_quot'		=> $rows_Quot,
			'rows_cust'		=> $rows_Customer,
			'rows_letter'	=> $rows_Letter,
			'rows_aloc'		=> $rows_Allocation,
			'category'		=> 'view'
		);
		
		$this->load->view($this->folder.'/v_schedule_order_preview',$data);
		
	}
	
	/*
	| ----------------------------- |
	|  	   SEND EMAIL SCHEDULE 		|
	| ----------------------------- |
	*/
	
	function email_schedule_order(){
		$OK_Proses	= 0;
		$rows_Header	= $rows_Detail =  $rows_Quot = $rows_Customer = $rows_Letter = $rows_Allocation  = array();
		
		if($this->input->post()){
			$Code_Process	= urldecode($this->input->post('code'));
			
			$rows_Header	= $this->db->get_where('schedules',array('id'=>$Code_Process))->row();
			$rows_Detail	= $this->db->get_where('schedule_details',array('schedule_id'=>$Code_Process))->result_array();
			$rows_Quot		= $this->db->get_where('quotations',array('id'=>$rows_Header->quotation_id))->row();
			$rows_Letter	= $this->db->get_where('letter_orders',array('id'=>$rows_Header->letter_order_id))->row();
			$rows_Customer	= $this->db->get_where('customers',array('id'=>$rows_Header->customer_id))->row();
			
			$rows_Jadwal	= $this->db->get_where('schedule_allocations',array('schedule_id'=>$Code_Process))->result_array();
			if($rows_Jadwal){
				foreach($rows_Jadwal as $keyJadwal=>$valJadwal){
					$Alat									= $valJadwal['schedule_detail_id'];
					$rows_Allocation[$Alat]['member_id']	= $valJadwal['member_id'];
					$rows_Allocation[$Alat]['member_name']	= $valJadwal['member_name'];
					$rows_Allocation[$Alat]['start_date']	= $valJadwal['plan_date_start'];
					$rows_Allocation[$Alat]['start_time']	= substr($valJadwal['plan_time_start'],0,5);
					$rows_Allocation[$Alat]['end_date']		= $valJadwal['plan_date_end'];
					$rows_Allocation[$Alat]['end_time']		= substr($valJadwal['plan_time_end'],0,5);
				}
				unset($rows_Jadwal);
			}
			
		}
		
		
		$data = array(
			'title'			=> 'SEND EMAIL SCHEDULE',
			'action'		=> 'email_schedule_order',
			'akses_menu'	=> $this->Arr_Akses,
			'rows_header'	=> $rows_Header,
			'rows_detail'	=> $rows_Detail,
			'rows_quot'		=> $rows_Quot,
			'rows_cust'		=> $rows_Customer,
			'rows_letter'	=> $rows_Letter,
			'rows_aloc'		=> $rows_Allocation,
			'category'		=> 'email'
		);
		
		$this->load->view($this->folder.'/v_schedule_order_preview',$data);
	}
	
	function save_email_schedule_order(){
		$rows_Return	= array(
			'status'		=> 2,
			'pesan'			=> 'No Record was found...'
		);
		if($this->input->post()){
			//echo"<pre>";print_r($this->input->post());exit;
			
			$Created_By		= $this->session->userdata('siscal_userid');
			$Created_Name	= $this->session->userdata('siscal_username');
			$Created_Date	= date('Y-m-d H:i:s');
			
			$Code_Order		= $this->input->post('code_order');
			$Sales_Order	= $this->input->post('sales_order');
			$Quot_Order		= $this->input->post('quot_order');
			$Inisial		= $this->input->post('inisial');
			$Email_Address	= $this->input->post('email_to');
			$Email_Name		= ucwords(strtolower($this->input->post('email_name')));
			
			
			$OK_Proses		= 1;
			
			$rows_Sender	= $this->db->get_where('email_senders',array('flag_active'=>'Y'))->row();
			$rows_Setting	= $this->db->get('setting_emails')->row();
			
			$Find_Exist		= $this->db->get_where('schedules',array('id'=>$Code_Order))->row();
			if($Find_Exist){
				if($Find_Exist->status !== 'OPN'){
					$OK_Proses		= 0;
					$Pesan_Error	= 'Data has been modified by other process';
					$rows_Return	= array(
						'status'		=> 2,
						'pesan'			=> 'Data has been modified by other process...'
					);
				}
			}else{
				$OK_Proses		= 0;
				$Pesan_Error	= 'No record was found...';
			}
			
			if(empty($rows_Sender)){
				$OK_Proses		= 0;
				$Pesan_Error	= 'Empty Email Sender. Please Set Email Sender First..';
			}
			if($OK_Proses ===  1){
			
				$this->db->trans_begin();
				$Pesan_Error	= '';
				
				$sroot 	= $_SERVER['DOCUMENT_ROOT'];
				include $sroot.'/Siscal_Dashboard/application/libraries/PHPMailer/PHPMailerAutoload.php';
				
				
				$Body= "<html xmlns=\"http://www.w3.org/1999/xhtml\">
						<head>
							<meta http-equiv=\"Content-Type\" content=\"text/html; charset=UTF-8\" />
							<title>Agungrent</title>
							<meta name=\"viewport\" content=\"width=device-width, initial-scale=1.0\"/>
						</head>
						<body style=\"margin: 0; padding: 0; background-color:#c0c0c0;\">
							<table border=\"0\" cellpadding=\"0\" cellspacing=\"0\" width=\"100%\">	
								<tr>
									<td style=\"padding: 10px 0 30px 0;\">
										<table align=\"center\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\" width=\"600\" style=\"border: 1px solid #cccccc; border-collapse: collapse;\">
											<tr>
												<td bgcolor=\"#ffffff\" style=\"padding: 30px 30px 30px 30px;\">
													<table border=\"0\" cellpadding=\"0\" cellspacing=\"0\" width=\"100%\">
														<tr>
															<td style=\"color: #ffffff; font-family: Arial, sans-serif; font-size: 14px;\" width=\"75%\">
																<img src='cid:1001' alt=\"Sentral\" style=\"display: block;\" />
															</td>
															<td align=\"right\" width=\"25%\">
																
															</td>
														</tr>
													</table>
												</td>
											</tr>
											<tr>
												<td bgcolor=\"#ffffff\" style=\"padding: 40px 30px 40px 30px;\">
													<table border=\"0\" cellpadding=\"0\" cellspacing=\"0\" width=\"100%\">
														<tr>
															<td style=\"color: #153643;font-family: Arial, sans-serif; font-size: 18px;\">
																Dear <b>".$Inisial." ".$Email_Name."</b>
															</td>
														</tr>
														<tr>
															<td style=\"padding: 20px 0 30px 0; color: #808080; font-family: Arial, sans-serif; font-size: 14px; line-height: 20px;\">
																Berikut terlampir form persetujuan schedule kalibrasi untuk ".$Find_Exist->customer_name.", mohon untuk diisi pula kondisi alat semula untuk persiapan kami dalam proses kalibrasi, contoh : penunjukan angka tidak dari nol, baterai habis,atau error. Dan jika ".$Inisial." setuju dengan schedule yang kami berikan mohon <b>ditandatangani</b> dan <b>dikirimkan kembali</b> agar pelaksanaan kalibrasi segera bisa dilakukan.
																
																
															</td>
														</tr>
													</table>
													<br>Terima kasih atas perhatian dan kepercayaannya.<br><br><br><font style=\"font-family: Arial, sans-serif; font-size: 14px; line-height: 20px;text-align:center;\">Sentral Kalibrasi Sistem<br>Cikarang Square Blok B No. 11, Jl. Cibarusah Cikarang Selatan - Jawa Barat 17530<br>Telp. 021-21381247-6,29067201-3, Fax 29067204 - <b><i>cs@sentralkalibrasi.co.id</i></b></font><br>
												</td>
											</tr>
											<tr>
												<td bgcolor=\"#EFEFEF\" style=\"padding: 30px 30px 30px 30px;\">
													<table border=\"0\" cellpadding=\"0\" cellspacing=\"0\" width=\"100%\">
														<tr>
															<td style=\"color: #555; font-family: Arial, sans-serif; font-size: 14px;\" width=\"75%\">&reg; Sentral Kalibrasi " . date("Y") . "<br/>
															</td>
														</tr>
													</table>
												</td>
											</tr>
										</table>
									</td>
								</tr>
							</table>
						</body>
						</html>";
				//echo $Body;exit;
				$mail					= new PHPMailer();
				
				$mail->IsSMTP();
				//$mail->SMTPDebug 		= 2;
				$mail->Mailer			= "smtp";
				$mail->Host				= $rows_Setting->email;
				$mail->Port				= $rows_Setting->port;
				$mail->SMTPAuth			= true;
				//$mail->SMTPSecure 		= 'tls';
				$mail->Username			= $rows_Sender->email_from;
				$mail->Password			= Dekripsi($rows_Sender->pass_from);
				
				// - - - - - - - - - - - - - - - - - - - From - To - - - - - - - - - - - - - - - - - - - //
				
				
				$mail->From				= $rows_Sender->email_from; // sender email
				$mail->FromName			= $rows_Sender->name_from; // name sender
				
				$Email_To				= $Inisial;
				$Email_Name				= $Inisial.' '.$Email_Name;
				
				$mail->AddAddress($Email_To, ucwords(strtolower($Email_Name)));
				if($rows_Sender->cc_email){
					$Arr_Nama	= array();
					$Arr_Email	= array();
					$fnd		= strstr($rows_Sender->cc_email,',');
					if($fnd){
						$Arr_Nama	= explode(',',$rows_Sender->cc_name);
						$Arr_Email	= explode(',',$rows_Sender->cc_email);
					}else{
						$Arr_Nama[1]	= $rows_Sender->cc_name;
						$Arr_Email[1]	= $rows_Sender->cc_email;
					}
					
					foreach($Arr_Email as $key=>$vals){
						$mail->addCC($vals,$Arr_Nama[$key]);
					}
				}
				
				$rows_Quot		= $this->db->get_where('quotations',array('id'=>$Find_Exist->quotation_id))->row();
				$rows_Member	= $this->db->get_where('members',array('id'=>$rows_Quot->member_id))->row();
				if(isset($rows_Member->email) && $rows_Member->email){
					$mail->addCC($rows_Member->email,$rows_Member->nama);
				}
				
				//$mail->addCC('mahrus.ali@agungrent.co.id','Mahrus Ali');
				
				// - - - - - - - - - - - - - - - - - - - Message Here - - - - - - - - - - - - - - - - - - - //
				$subject	="Schedule Kalibrasi";
				if($Find_Exist->revisi > 0){
					$subject	="Revisi Schedule Kalibrasi";
				}
				$mail->IsHTML(true);
				$mail->Subject	= $subject;
				
				$mail->Body		= $Body;
				
				$img_file	= $sroot.'/Siscal_Dashboard/assets/img/logo.jpg';
				$directory	= $sroot.'/Siscal_Dashboard/assets/file/';
				
				
				$this->download_pdf($Code_Order);
				
				//echo"masuk bro";exit;
				$file_pdf	= $directory.$Code_Order.'.pdf';
				$mail->AddAttachment($file_pdf);  // attach pdf
				$mail->AddEmbeddedImage($img_file, 1001);
				if(!$mail->Send()) {
					$Pesan_Error	= 'Send Email Failed. Please Try Again...'.$mail->ErrorInfo;
					unlink($file_pdf);
									
				}else{
				
					$Upd_Header		= "UPDATE schedules SET sts_email = 'Y' WHERE id = '".$Code_Order."'";
					$Has_Upd_Header	= $this->db->query($Upd_Header);
					if($Has_Upd_Header !== TRUE){
						$Pesan_Error	= 'Error Update Schedule Header';
					}					
					unlink($file_pdf);					
				}
				
				
				
				if ($this->db->trans_status() != TRUE || !empty($Pesan_Error)){
					$this->db->trans_rollback();
					$rows_Return		= array(
						'status'		=> 2,
						'pesan'			=> 'Send Email Process  Failed, '.$Pesan_Error
					);
					history('Send Email Schedule Order '.$Code_Order.' - '.$Pesan_Error);
				}else{
					$this->db->trans_commit();
					$rows_Return		= array(
						'status'		=> 1,
						'pesan'			=> 'Send Email process success. Thank you & have a nice day......'
					);
					history('Send Email Schedule Order '.$Code_Order.' - '.$Cancel_Reason);
				}
					
				
			}else{
				$rows_Return	= array(
					'status'		=> 2,
					'pesan'			=> $Pesan_Error
				);
			}
			

						
		}
		echo json_encode($rows_Return);
	}
	
	function download_pdf($kode){
		$sroot 	= $_SERVER['DOCUMENT_ROOT'];
		include $sroot.'/Siscal_Dashboard/application/libraries/MPDF57/mpdf.php';
		$mpdf	= new mPDF('utf-8', 'A4-P');
		
		$ArrBulan	=array(1=>'Januari','Februari','Maret','April','Mei','Juni','Juli','Agustus','September','Oktober','Nopember','Desember');
		$ArrHari	= array(
			'Sun'	=> 'Minggu',
			'Mon'	=> 'Senin',
			'Tue'	=> 'Selasa',
			'Wed'	=> 'Rabu',
			'Thu'	=> 'Kamis',
			'Fri'	=> 'Jumat',
			'Sat'	=> 'Sabtu'
			);
		//Beginning Buffer to save PHP variables and HTML tags
		
		$img_file	= $sroot.'/Siscal_Dashboard/assets/img/logo.jpg';
		$img_file2	= $sroot.'/Siscal_Dashboard/assets/img/kan.png';
		$directory	= $sroot.'/Siscal_Dashboard/assets/file/';
		$ArrBulan	=array(1=>'Jan','Feb','Mar','Apr','Mei','Jun','Jul','Aug','Sep','Oct','Nov','Dec');
		//Beginning Buffer to save PHP variables and HTML tags
		set_time_limit(0);
		ini_set('memory_limit','1024M');
		ob_start();
		
		
		$rows_Header	= $this->db->get_where('schedules',array('id'=>$kode))->row();
		$rows_Detail	= $this->db->get_where('schedule_details',array('schedule_id'=>$kode))->result_array();
		$rows_Quot		= $this->db->get_where('quotations',array('id'=>$rows_Header->quotation_id))->row();
		$rows_Letter	= $this->db->get_where('letter_orders',array('id'=>$rows_Header->letter_order_id))->row();
		$rows_Customer	= $this->db->get_where('customers',array('id'=>$rows_Header->customer_id))->row();
		
		$rows_Jadwal	= $this->db->get_where('schedule_allocations',array('schedule_id'=>$kode))->result_array();
		if($rows_Jadwal){
			foreach($rows_Jadwal as $keyJadwal=>$valJadwal){
				$Alat									= $valJadwal['schedule_detail_id'];
				$rows_Allocation[$Alat]['member_id']	= $valJadwal['member_id'];
				$rows_Allocation[$Alat]['member_name']	= $valJadwal['member_name'];
				$rows_Allocation[$Alat]['start_date']	= $valJadwal['plan_date_start'];
				$rows_Allocation[$Alat]['start_time']	= substr($valJadwal['plan_time_start'],0,5);
				$rows_Allocation[$Alat]['end_date']		= $valJadwal['plan_date_end'];
				$rows_Allocation[$Alat]['end_time']		= substr($valJadwal['plan_time_end'],0,5);
			}
			unset($rows_Jadwal);
		}
		?>  
				
		<style type="text/css">
		@page {
			margin-top: 0.8cm;
			margin-left: 1cm;
			margin-right: 1cm;
			margin-bottom: 0.8cm;
		}
		.font{
			font-family: verdana,arial,sans-serif;
			font-size:14px;
		}
		.fontheader{
			font-family: verdana,arial,sans-serif;
			font-size:13px;
			color:#333333;
			border-width: 1px;
			border-color: #666666;
			border-collapse: collapse;
		}

		table.noborder2 th {
			font-size:11px;
			padding: 1px;
			border-color: #666666;
		}

		table.noborder2 td {	
			padding: 1px;
			border-color: #666666;
			background-color: #ffffff;
			font-size:10px;
			font-family: verdana,arial,sans-serif;
		}
		table.noborder3 td {	
			padding: 1px;
			border-color: #666666;
			background-color: #ffffff;
			font-size:12px;
			font-family: verdana,arial,sans-serif;
		}
		table.noborder, .noborder2,noborder3 {
			font-family: verdana,arial,sans-serif;
		}

		table.noborder th {
			font-size:12px;
			padding: 1px;
			border-color: #666666;
		}

		table.noborder td {	
			padding: 1px;
			border-color: #666666;
			background-color: #ffffff;
			font-size:13px;
			font-family: verdana,arial,sans-serif;
		}

		table.gridtable {
			font-family: verdana,arial,sans-serif;
			font-size:11px;
			color:#333333;
			border-width: 1px;
			border-color: #666666;
			border-collapse: collapse;
		}

		table.gridtable th {
			border-width: 1px;
			padding: 5px;
			border-style: solid;
			border-color: #666666;
			background-color: #f2f2f2;
			
		}

		table.gridtable th.head {
			border-width: 1px;
			padding: 8px;
			border-style: solid;
			border-color: #666666;
			background-color: #7f7f7f;
			color: #ffffff;
		}
		table.gridtable td {
			border-width: 1px;
			padding: 5px;
			border-style: solid;
			border-color: #666666;
			background-color: #ffffff;
		}

		table.gridtable td zero {
			border-width: 1px;
			padding: 5px;
			border-color: #666666;
			background-color: #ffffff;
			
		}

		table.gridtable td.cols {
			border-width: 1px;
			padding: 5px;
			border-style: solid;
			border-color: #666666;
			background-color: #ffffff;
		}

		table.cooltabs {
			font-size:12px;
			font-family: verdana,arial,sans-serif;
			border-width: 1px;
			border-style: solid;
		}

		table.cooltabs th.reg {
			font-family: verdana,arial,sans-serif;
			border-radius: 5px 5px 5px 5px;
			background: #e3e0e4;
			padding: 5px;
		}

		table.cooltabs td.reg {
			font-family: verdana,arial,sans-serif;
			border-radius: 5px 5px 5px 5px;
			padding: 5px;
			border-width: 1px;
		}

		#cooltabs {
			font-family: verdana,arial,sans-serif;
			border-width: 1px;
			border-style: solid;
			border-radius: 5px 5px 5px 5px;
			background: #e3e0e4;
			padding: 5px; 
			width: 800px;
			height: 20px; 
		}

		#cooltabs2{
			font-family: verdana,arial,sans-serif;
			border-width: 1px;
			border-style: solid;
			border-radius: 5px 5px 5px 5px;
			background: #e3e0e4;
			padding: 5px; 
			width: 180px;
			height: 10px;
		}

		#space{
			padding: 3px; 
			width: 180px;
			height: 1px;
		}

		#cooltabshead{
			font-size:12px;
			font-family: verdana,arial,sans-serif;
			border-width: 1px;
			border-style: solid;
			border-radius: 5px 5px 0 0;
			background: #dfdfdf;
			padding: 5px; 
			width: 162px;
			height: 10px;
			float:left;
		}

		#cooltabschild{
			font-size:10px;
			font-family: verdana,arial,sans-serif;
			border-width: 1px;
			border-style: solid;
			border-radius: 0 0 5px 5px;
			padding: 5px; 
			width: 162px;
			height: 10px;
			float:left;
		}

		p {
		  margin: 0 0 0 0;
		}

		p.pos_fixed {
			font-family: verdana,arial,sans-serif;
			position: fixed;
			top: 50px;
			left: 230px;
		}

		p.pos_fixed2 {
			font-family: verdana,arial,sans-serif;
			position: fixed;
			top: 589px;
			left: 230px;
		}

		p.notesmall {
			font-size: 9px;
		}

		.barcode {
			padding: 1.5mm;
			margin: 0;
			vertical-align: top;
			color: #000044;
		}

		.barcodecell {
			text-align: center;
			vertical-align: middle;
			position: fixed;
			top: 14px;
			right: 10px;
		}
		p.pt {
			font-family: verdana,arial,sans-serif;
			font-size:7px;
			position: fixed;
			top: 62px;
			left: 5px;
		}
		h3.pt {
			font-family: calibri,arial,sans-serif;
			position: fixed;	
			top: 175px;
			left: 250px;
			}

		h3 {
			font-family: calibri,arial,sans-serif;
			position: fixed;	
			top: 65px;
			left: 200px;
			}

		h2 {
			font-family: calibri,arial,sans-serif;
			position: fixed;
			top: 50px;
			left: 95px;
			}
			
		p.reg {
			font-family: verdana,arial,sans-serif;
			font-size:11px;
		}

		p.sub {
			font-family: verdana,arial,sans-serif;
			font-size:13px;
			position: fixed;
			top: 55px;
			left: 220px;
			color: #6b6b6b;
		}

		p.header {
			font-family: verdana,arial,sans-serif;
			font-size:11px;
			color: #330000;
		}

		p.barcs {
			font-family: verdana,arial,sans-serif;
			font-size:11px;
			position: fixed;
			top: 13px;
			right: 1px;
		}

		p.alamat {
			font-family: verdana,arial,sans-serif;
			font-size:7px;
			position: fixed;
			top: 71px;
			left: 5px;
		}

		p.tlp {
			font-family: verdana,arial,sans-serif;
			font-size:7px;
			position: fixed;
			top: 80px;
			left: 5px;
		}

		p.date {
			font-family: verdana,arial,sans-serif;
			font-size:12px;
			text-align: right;
		}

		p.foot {
			font-family: verdana,arial,sans-serif;
			font-size:7px;
			position: fixed;
			top: 750px;
			left: 5px;
		}

		p.footer {
			font-family: verdana,arial,sans-serif;
			font-size:10px;
			position: fixed;
			bottom: 7px;    
		}

		p.ln {
			font-family: verdana,arial,sans-serif;
			font-size:9px;
			position: fixed;
			bottom: 1px;
			left: 2px;
		}

		#hrnew {
			border: 0;
			border-bottom: 1px solid #ccc;
			background: #999;
		}
		</style>
		<div id='space'></div>
		<div id='space'></div>
		<table class="noborder2" width='100%'>
			<tr>
				<td width='50%' align='left'>
					<img src="<?php echo $img_file;?>" width="90" height="70"/>
				</td>
				<td width='50%' align='right'>
					<img src="<?php echo $img_file2;?>" width="90" height="70"/>
				</td>
			</tr>
		</table>

		<div id='space'></div>


		<div id='space'></div>
		<div id='space'></div>
		<h2 align='center'><u>FORM PERSETUJUAN SCHEDULE KALIBRASI<u></h2>
		<div id='space'></div>
		<?php
			$Keterangan	= 'Mohon konfirmasinya apabila sudah setuju dengan schedule pengambilan dan pengembalian alat';
			$Ambil	= 'Pengambilan';
			if($rows_Letter->get_tool == 'CUSTOMER'){
				$Ambil	= 'Diantar Customer';
				$Keterangan	= 'Mohon konfirmasinya apabila sudah setuju dengan schedule pengantaran (oleh customer) dan pengembalian alat';
			}
		?>
		<table class="noborder3" width='100%'>	
			
			<tr>
				<td align='left' valign='top' width='24%'>Nama Perusahaan</td>
				<td align='center' valign='top' width='4%'>:</td>
				<td align='left' valign='top' width='72%'><b><?php echo $rows_Header->customer_name; ?></b></td>		
			</tr>
			<tr>
				<td align='left' valign='top' width='24%'>Alamat</td>
				<td align='center' valign='top' width='4%'>:</td>
				<td align='left' valign='top' width='72%'><?php echo $rows_Letter->address; ?></td>		
			</tr>
			<tr>
				<td align='left' valign='top' width='24%'>No PO / Penawaran</td>
				<td align='center' valign='top' width='4%'>:</td>
				<td align='left' valign='top' width='72%'><?php echo $rows_Quot->pono ?></td>		
			</tr>	
			<tr>
				<td align='left' valign='top' colspan='3' height='3' width='100%'></td>
			</tr>
			
			<tr>
				<td colspan='3' width='100%'>
					<table class="gridtable" width='100%'>
						<tr>
							<th width='5%' align='center' valign='middle' rowspan='2'>No.</th>
							<th width='30%' align='center' valign='middle' rowspan='2'>Nama Alat</th>
							<th width='5%' align='center' valign='middle' rowspan='2'>Qty</th>
							<th width='25%' align='center'  colspan='3'>Tanggal</th>
							<th width='22%' align='center' valign='middle' rowspan='2'>Keterangan Kond. Alat</th>					
						</tr>
						<tr>					
							<th width='12%' align='center'><?php echo $Ambil;?></th>
							<th width='13%' align='center'>Pengembalian</th>
							<th width='13%' align='center'>Insitu</th>
						</tr>
						<?php
							
							if($rows_Detail){
								$loop	=0;
								foreach($rows_Detail as $key=>$val){
									$loop++;
									$bulan_pick	= date('n',strtotime($val['pick_date']));
									$tgl_pick	= date('d',strtotime($val['pick_date'])).' '.$ArrBulan[$bulan_pick].' '.date('Y',strtotime($val['pick_date']));
									
									$bulan_delv	= date('n',strtotime($val['delivery_date']));
									$tgl_delv	= date('d',strtotime($val['delivery_date'])).' '.$ArrBulan[$bulan_delv].' '.date('Y',strtotime($val['delivery_date']));
									$Tgl_Proses	= '-';
									$insitu		= 'N';
									if($val['labs']=='Y'){
										$lokasi	='Labs';
									}else if($val['insitu']=='Y'){
										$lokasi			 = 'Lokasi Client';
										$bulan_test	 	= date('n',strtotime($val['process_date']));
										$tgl_pick	 	= '-';
										$tgl_delv	 	= '-';								
										$Tgl_Proses		= date('d',strtotime($val['process_date'])).' '.$ArrBulan[$bulan_test].' '.date('Y',strtotime($val['process_date']));
									}else{
										$lokasi	='Subcon';
									}
									
									
										echo"<tr>";
											echo "<td width='5%' align='center'>$loop</td>";
											echo "<td width='30%' align='left'>$val[tool_name]</td>";	
											echo "<td width='5%' align='center'>".$val['qty']."</td>";
											echo "<td width='12%' align='center'>".$tgl_pick."</td>";
											echo "<td width='13%' align='center'>".$tgl_delv."</td>";
											echo "<td width='13%' align='center'>".$Tgl_Proses."</td>";
											echo "<td width='22%' align='center'></td>";
										echo"</tr>";
								}
							}
						?>
						
						
					</table>
				</td>
			</tr>
		</table>
		<div id='space'></div>
		<table class="noborder3" width='100%'>
			<tr>
				<td colspan='3' align='left'><?php echo $Keterangan;?></td>
			</tr>
			<tr>
				<td width='10%' valign='top' align='left'>Note :</td>
				<td width='3%' valign='top' align='left'>1.</td>
				<td width='87%' valign='top' align='left'>Sertifikat asli akan diberikan setelah pembayaran dilakukan, setelah proses kalibrasi selesai kami akan mengirimkan sertifikat dalam bentuk scan terlebih dahulu.</td>
			</tr>
			<tr>
				<td width='10%' valign='top' align='left'></td>
				<td width='3%' valign='top' align='left'>2.</td>
				<td width='87%' valign='top' align='left'>Sebagai identitas unik alat ukur Bapak/Ibu, kami akan menempelkan sticker kalibrasi pada alat ukur namun sticker kalibrasi sebelumnya akan kami dokumentasikan untuk telusur dikemudian hari dan akan dicopot supaya rapi dan bersih.</td>
			</tr>
			<tr>
				<td width='10%' valign='top' align='left'></td>
				<td width='3%' valign='top' align='left'>3.</td>
				<td width='87%' valign='top' align='left'>Apabila pada saat pengambilan alat tidak ada atau belum disiapkan maka kami tidak menjadwalkan ulang untuk pengambilan alat tesebut sehingga alat harus diantar sendiri oleh client ke lab kami.</td>
			</tr>
			<tr>
				<td width='10%' valign='top' align='left'></td>
				<td width='3%' valign='top' align='left'>*</td>
				<td width='87%' valign='top' align='left'>
					<p class='reg'><font color='#6b6b6b'><b>
						Kondisi alat wajib diisi untuk persiapan kami dalam proses kalibrasi (contoh : Penunjukan angka tidak dari nol,batrai habis, atau error)</b></font>
						<div id='space'></div>
					</p>
				</td>
			</tr>
			<tr>
				<td colspan='3' align='left' height='6px'></td>
			</tr>
				
			<tr>
				<td width='10%' valign='top' align='left'></td>
				<td width='3%' valign='top' align='left'></td>
				<td width='87%' valign='top' align='left'>
					<table class='noborder' width='100%'>
						<tr>
							<td align='left' width='50%'></td>					
							<td align='center' width='50%'>
								Mengetahui dan Menyetujui,<br><font color='#6b6b6b'><b><?php echo $rows_Header->customer_name;?></b></font><br><br><br><br><br><br><br><br><br><b><font color='#6b6b6b'>---------------------------------------<br>
							</td>
							
						</tr>
						
					</table>
				</td>
			</tr>	
		</table>

		<div id='space'></div>
		<div id='space'></div>
		<div id='space'></div>
		<div id='space'></div>

			<p style="font-family: verdana,arial,sans-serif;font-size:10px;text-align:left;position:fixed;bottom:45px;width:100%;">
				<b><i><?php echo $rows_Header->nomor;?>.</i></b>
			</p>
			
			<p style="font-family: verdana,arial,sans-serif;font-size:10px;text-align:center;position:fixed;bottom:5px;width:100%;">
				<b><i>www.sentralkalibrasi.co.id</i></b><br>Cikarang Square Blok B No. 11, Jl. Cibarusah Cikarang Selatan - Jawa Barat 17530<br>Telp. 021-21381247-6,29067201-3, Fax 29067204 - <b><i>cs@sentralkalibrasi.co.id</i></b>
			</p>


		<?php
		$html = ob_get_contents();
		ob_end_clean();

		
		$mpdf->SetWatermarkText('Sentral Kalibrasi');
		$mpdf->showWatermarkText = true;	
		$mpdf->WriteHTML($html);		
		$mpdf->Output($directory.$rows_Header->id.'.pdf' ,'F');
	}
	
	
	
	/*
	| ----------------------------- |
	|  		CANCEL SCHEDULE 		|
	| ----------------------------- |
	*/
	
	function cancel_schedule_order(){
		$OK_Proses	= 0;
		$rows_Header	= $rows_Detail =  $rows_Quot = $rows_Customer = $rows_Letter = $rows_Allocation  = array();
		
		if($this->input->post()){
			$Code_Process	= urldecode($this->input->post('code'));
			
			$rows_Header	= $this->db->get_where('schedules',array('id'=>$Code_Process))->row();
			$rows_Detail	= $this->db->get_where('schedule_details',array('schedule_id'=>$Code_Process))->result_array();
			$rows_Quot		= $this->db->get_where('quotations',array('id'=>$rows_Header->quotation_id))->row();
			$rows_Letter	= $this->db->get_where('letter_orders',array('id'=>$rows_Header->letter_order_id))->row();
			$rows_Customer	= $this->db->get_where('customers',array('id'=>$rows_Header->customer_id))->row();
			
			$rows_Jadwal	= $this->db->get_where('schedule_allocations',array('schedule_id'=>$Code_Process))->result_array();
			if($rows_Jadwal){
				foreach($rows_Jadwal as $keyJadwal=>$valJadwal){
					$Alat									= $valJadwal['schedule_detail_id'];
					$rows_Allocation[$Alat]['member_id']	= $valJadwal['member_id'];
					$rows_Allocation[$Alat]['member_name']	= $valJadwal['member_name'];
					$rows_Allocation[$Alat]['start_date']	= $valJadwal['plan_date_start'];
					$rows_Allocation[$Alat]['start_time']	= substr($valJadwal['plan_time_start'],0,5);
					$rows_Allocation[$Alat]['end_date']		= $valJadwal['plan_date_end'];
					$rows_Allocation[$Alat]['end_time']		= substr($valJadwal['plan_time_end'],0,5);
				}
				unset($rows_Jadwal);
			}
			
		}
		
		
		$data = array(
			'title'			=> 'SCHEDULE CANCELLATION',
			'action'		=> 'cancel_schedule_order',
			'akses_menu'	=> $this->Arr_Akses,
			'rows_header'	=> $rows_Header,
			'rows_detail'	=> $rows_Detail,
			'rows_quot'		=> $rows_Quot,
			'rows_cust'		=> $rows_Customer,
			'rows_letter'	=> $rows_Letter,
			'rows_aloc'		=> $rows_Allocation,
			'category'		=> 'cancel'
		);
		
		$this->load->view($this->folder.'/v_schedule_order_preview',$data);
		
	}
	
	
	function save_cancel_schedule_order(){
		$rows_Return	= array(
			'status'		=> 2,
			'pesan'			=> 'No Record was found...'
		);
		if($this->input->post()){
			//echo"<pre>";print_r($this->input->post());exit;
			
			$Created_By		= $this->session->userdata('siscal_userid');
			$Created_Name	= $this->session->userdata('siscal_username');
			$Created_Date	= date('Y-m-d H:i:s');
			
			$Code_Order		= $this->input->post('code_order');
			$Sales_Order	= $this->input->post('sales_order');
			$Quot_Order		= $this->input->post('quot_order');
			$Cancel_Reason	= strtoupper($this->input->post('cancel_reason'));
			
			$Find_Exist		= $this->db->get_where('schedules',array('id'=>$Code_Order))->row();
			if($Find_Exist){
				if($Find_Exist->status !== 'OPN'){
					$rows_Return	= array(
						'status'		=> 2,
						'pesan'			=> 'Data has been modified by other process...'
					);
				}else{
					$this->db->trans_begin();
					$Pesan_Error	= '';
					
					$Query_SO		= "SELECT * FROM schedules WHERE letter_order_id = '".$Sales_Order."' AND status NOT IN('CNC','REV')";
					$Jum_SO_Schedule= $this->db->query($Query_SO)->num_rows();
					
					## INSERT KE SCHEDULE LOG ##
					$Ins_Head_Log	= array(
						'schedule_id'		=> $Find_Exist->id,
						'nomor'				=> $Find_Exist->nomor,
						'datet'				=> $Find_Exist->datet,
						'letter_order_id'	=> $Find_Exist->letter_order_id,
						'quotation_id'		=> $Find_Exist->quotation_id,
						'customer_id'		=> $Find_Exist->customer_id,
						'customer_name'		=> $Find_Exist->customer_name,
						'created_by'		=> $Find_Exist->created_by,
						'created_date'		=> $Find_Exist->created_date,
						'cancel_by'			=> $Created_By,
						'cancel_date'		=> $Created_Date,
						'kode_proses'		=> $Find_Exist->kode_proses,
						'cancel_reason'		=> $Cancel_Reason
					);
					
					$Has_Ins_Head_Log		= $this->db->insert('schedule_logs',$Ins_Head_Log);
					if($Has_Ins_Head_Log !== TRUE){
						$Pesan_Error	= 'Error Insert Schedule Log';
					}
					
					## AMBIL DATA SCHEDULE DETAIL ##
					$rows_Detail		= $this->db->get_where('schedule_details',array('schedule_id'=>$Code_Order))->result();
					if($rows_Detail){
						foreach($rows_Detail as $keyDet=>$valDet){
							$Code_QuotDet	= $valDet->quotation_detail_id;
							$Qty_QuotDet	= $valDet->qty;
							
							## UPDATE SALES ORDER DETAIL ##
							$Upd_Order_Detail		= "UPDATE letter_order_details SET qty_schedule = qty_schedule - ".$Qty_QuotDet." WHERE letter_order_id = '".$Sales_Order."' AND quotation_detail_id = '".$Code_QuotDet."'";
							$Has_Upd_Order_Detail	= $this->db->query($Upd_Order_Detail);
							if($Has_Upd_Order_Detail !== TRUE){
								$Pesan_Error	= 'Error Update Sales Order Detail';
							}
							
							## INSERT KE SCHEDULE DETAIL LOG ##
							$Ins_Detail_Log		= array(
								'schedule_detail_id'	=> $valDet->id,
								'schedule_id'			=> $valDet->schedule_id,
								'quotation_detail_id'	=> $valDet->quotation_detail_id,
								'tool_id'				=> $valDet->tool_id,
								'tool_name'				=> $valDet->tool_name,
								'qty'					=> $valDet->qty,
								'pick_date'				=> $valDet->pick_date,
								'process_date'			=> $valDet->process_date,
								'delivery_date'			=> $valDet->delivery_date,
								'selected'				=> $valDet->selected,
								'insitu'				=> $valDet->insitu,
								'labs'					=> $valDet->labs,
								'subcon'				=> $valDet->subcon,
								'subcon_send_date'		=> $valDet->subcon_send_date,
								'subcon_pick_date'		=> $valDet->subcon_pick_date,
								'waktu_tempuh'			=> $valDet->waktu_tempuh,
								'sts_split'				=> $valDet->sts_split,
								'urut_id'				=> $valDet->urut_id
							);
							
							$Has_Ins_Detail_Log		= $this->db->insert('schedule_detail_logs',$Ins_Detail_Log);
							if($Has_Ins_Detail_Log !== TRUE){
								$Pesan_Error	= 'Error Insert Schedule Detail Log';
							}
						}
					}
					
					## GET DETAIL SCHEDULE ALLOCATION ##
					$rows_Alocate		= $this->db->get_where('schedule_allocations',array('schedule_id'=>$Code_Order))->result();
					if($rows_Alocate){
						foreach($rows_Alocate as $keyLoc=>$valLoc){
							$Ins_Alocate_Log		= array(
								'schedule_id'			=> $valLoc->schedule_id,
								'schedule_detail_id'	=> $valLoc->schedule_detail_id,
								'quotation_detail_id'	=> $valLoc->quotation_detail_id,
								'tool_id'				=> $valLoc->tool_id,
								'tool_name'				=> $valLoc->tool_name,
								'qty'					=> $valLoc->qty,
								'plan_date_start'		=> $valLoc->plan_date_start,
								'plan_time_start'		=> $valLoc->plan_time_start,
								'plan_date_end'			=> $valLoc->plan_date_end,
								'plan_time_end'			=> $valLoc->plan_time_end,
								'actual_date_start'		=> $valLoc->actual_date_start,
								'actual_date_end'		=> $valLoc->actual_date_end,
								'member_id'				=> $valLoc->member_id,
								'member_name'			=> $valLoc->member_name,
								'waktu_tempuh'			=> $valLoc->waktu_tempuh
							);
							
							$Has_Ins_Alocate_Log		= $this->db->insert('schedule_allocation_logs',$Ins_Alocate_Log);
							if($Has_Ins_Alocate_Log !== TRUE){
								$Pesan_Error	= 'Error Insert Schedule Allocation Log';
							}
							
						}
					}
					
					## DELETE SCHEDULE HEADER ##
					$Del_Head			= "DELETE FROM schedules WHERE id = '".$Code_Order."'";
					$Has_Del_Head		= $this->db->query($Del_Head);
					if($Has_Del_Head !== TRUE){
						$Pesan_Error	= 'Error Delete Schedule';
					}
					
					## DELETE SCHEDULE DETAIL ##
					$Del_Detail			= "DELETE FROM schedule_details WHERE schedule_id = '".$Code_Order."'";
					$Has_Del_Detail		= $this->db->query($Del_Detail);
					if($Has_Del_Detail !== TRUE){
						$Pesan_Error	= 'Error Delete Schedule Detail';
					}
					
					## DELETE SCHEDULE ALLOCATION ##
					$Del_Alocate		= "DELETE FROM schedule_allocations WHERE schedule_id = '".$Code_Order."'";
					$Has_Del_Alocate	= $this->db->query($Del_Alocate);
					if($Has_Del_Alocate !== TRUE){
						$Pesan_Error	= 'Error Delete Schedule Alocate';
					}
					
					## DELETE TEMP SCHEDULE ORDER ##
					$Del_Temp_Schedule	= "DELETE FROM temp_schedule_orders WHERE id= '".$Sales_Order."' AND kode_proses = '".$Find_Exist->kode_proses."'";
					$Has_Del_Temp_Sched	= $this->db->query($Del_Temp_Schedule);
					if($Has_Del_Temp_Sched !== TRUE){
						$Pesan_Error	= 'Error Delete Schedule Temp Order';
					}
					
					$Del_Temp_Alocate	= "DELETE FROM temp_allocations WHERE quotation_detail_id LIKE '".$Quot_Order."%' AND kode_proses LIKE '".$Find_Exist->kode_proses."%'";
					$Has_Del_Temp_Aloc	= $this->db->query($Del_Temp_Alocate);
					if($Has_Del_Temp_Aloc !== TRUE){
						$Pesan_Error	= 'Error Delete Schedule Temp Allocation';
					}
					
					if($Jum_SO_Schedule <= 1){
						$Qry_Upd_Order	= "UPDATE letter_orders SET sts_so ='OPN', modified_by = '".$Created_By."', modified_date = '".$Created_Date."' WHERE id = '".$Sales_Order."'";
						$Has_Upd_Order 	= $this->db->query($Qry_Upd_Order);
						if($Has_Upd_Order !== TRUE){
							$Pesan_Error	= 'Error Update Letter Order Header';
						}
					}
					
					if ($this->db->trans_status() != TRUE || !empty($Pesan_Error)){
						$this->db->trans_rollback();
						$rows_Return		= array(
							'status'		=> 2,
							'pesan'			=> 'Cancellation Process  Failed, '.$Pesan_Error
						);
						history('Cancellation Schedule Order '.$Code_Order.' - '.$Pesan_Error);
					}else{
						$this->db->trans_commit();
						$rows_Return		= array(
							'status'		=> 1,
							'pesan'			=> 'Cancellation process success. Thank you & have a nice day......'
						);
						history('Cancellation Schedule Order '.$Code_Order.' - '.$Cancel_Reason);
					}
					
				}
			}else{
				$rows_Return	= array(
					'status'		=> 2,
					'pesan'			=> 'No Record was found...'
				);
			}
			

						
		}
		echo json_encode($rows_Return);
	}	
	
	/*
	| ----------------------------- |
	|  		APPROVE SCHEDULE 		|
	| ----------------------------- |
	*/
	
	function approve_schedule_order(){
		$OK_Proses	= 0;
		$rows_Header	= $rows_Detail =  $rows_Quot = $rows_Customer = $rows_Letter = $rows_Allocation  = array();
		
		if($this->input->post()){
			$Code_Process	= urldecode($this->input->post('code'));
			
			$rows_Header	= $this->db->get_where('schedules',array('id'=>$Code_Process))->row();
			$rows_Detail	= $this->db->get_where('schedule_details',array('schedule_id'=>$Code_Process))->result_array();
			$rows_Quot		= $this->db->get_where('quotations',array('id'=>$rows_Header->quotation_id))->row();
			$rows_Letter	= $this->db->get_where('letter_orders',array('id'=>$rows_Header->letter_order_id))->row();
			$rows_Customer	= $this->db->get_where('customers',array('id'=>$rows_Header->customer_id))->row();
			
			$rows_Jadwal	= $this->db->get_where('schedule_allocations',array('schedule_id'=>$Code_Process))->result_array();
			if($rows_Jadwal){
				foreach($rows_Jadwal as $keyJadwal=>$valJadwal){
					$Alat									= $valJadwal['schedule_detail_id'];
					$rows_Allocation[$Alat]['member_id']	= $valJadwal['member_id'];
					$rows_Allocation[$Alat]['member_name']	= $valJadwal['member_name'];
					$rows_Allocation[$Alat]['start_date']	= $valJadwal['plan_date_start'];
					$rows_Allocation[$Alat]['start_time']	= substr($valJadwal['plan_time_start'],0,5);
					$rows_Allocation[$Alat]['end_date']		= $valJadwal['plan_date_end'];
					$rows_Allocation[$Alat]['end_time']		= substr($valJadwal['plan_time_end'],0,5);
				}
				unset($rows_Jadwal);
			}
			
		}
		
		
		$data = array(
			'title'			=> 'SCHEDULE APPROVAL',
			'action'		=> 'approve_schedule_order',
			'akses_menu'	=> $this->Arr_Akses,
			'rows_header'	=> $rows_Header,
			'rows_detail'	=> $rows_Detail,
			'rows_quot'		=> $rows_Quot,
			'rows_cust'		=> $rows_Customer,
			'rows_letter'	=> $rows_Letter,
			'rows_aloc'		=> $rows_Allocation,
			'category'		=> 'approve'
		);
		
		$this->load->view($this->folder.'/v_schedule_order_preview',$data);
	}
	
	function save_approval_schedule_order(){
		$rows_Return	= array(
			'status'		=> 2,
			'pesan'			=> 'No Record was found...'
		);
		if($this->input->post()){
			//echo"<pre>";print_r($this->input->post());exit;
			
			$Created_By		= $this->session->userdata('siscal_userid');
			$Created_Name	= $this->session->userdata('siscal_username');
			$Created_Date	= date('Y-m-d H:i:s');
			
			$Code_Order		= $this->input->post('code_order');
			$Sales_Order	= $this->input->post('sales_order');
			$Quot_Order		= $this->input->post('quot_order');
			$Cancel_Reason	= strtoupper($this->input->post('cancel_reason'));
			
			$Find_Exist		= $this->db->get_where('schedules',array('id'=>$Code_Order))->row();
			if($Find_Exist){
				if($Find_Exist->status !== 'OPN'){
					$rows_Return	= array(
						'status'		=> 2,
						'pesan'			=> 'Data has been modified by other process...'
					);
				}else{
					$this->db->trans_begin();
					$Pesan_Error	= '';
					
					$rows_Quotation	= $this->db->get_where('quotations',array('id'=>$Quot_Order))->row();
					$rows_Letter	= $this->db->get_where('letter_orders',array('id'=>$Sales_Order))->row();
					## UPDATE KE SCHEDULE ##
					$Ins_Head_Log	= array(
						'status'			=> 'APV',
						'approve_by'		=> $Created_By,
						'approve_date'		=> $Created_Date
					);
					
					
					
					$Has_Upd_Head_Log	= $this->db->update('schedules',$Ins_Head_Log,array('id'=>$Code_Order));
					if($Has_Upd_Head_Log !== TRUE){
						$Pesan_Error	= 'Error Update Schedule';
					}
					
					## AMBIL DATA SCHEDULE DETAIL ##
					$rows_Detail		= $this->db->get_where('schedule_details',array('schedule_id'=>$Code_Order))->result();
					if($rows_Detail){
						$Kode_Gen		= 'T-'.date('YmdHis');
						$Urut_Ident		= 0;
						foreach($rows_Detail as $keyDet=>$valDet){
							$Code_QuotDet	= $valDet->quotation_detail_id;
							$Qty_QuotDet	= $valDet->qty;
							$Code_SchedDet	= $valDet->id;
							$Code_Tool		= $valDet->tool_id;
							$Name_Tool		= $valDet->tool_name;
							$Labs			= $valDet->labs;
							$Insitu			= $valDet->insitu;
							$Subcon			= $valDet->subcon;
							$Pickup_Date	= $valDet->pick_date;
							$Process_Date	= $valDet->process_date;
							$Send_Date		= $valDet->delivery_date;
							$Pickup_Subcon	= $valDet->subcon_pick_date;
							$Send_Subcon	= $valDet->subcon_send_date;
							$Flag_Split		= $valDet->sts_split;
							
							$rows_LetterDet	= $this->db->get_where('letter_order_details',array('letter_order_id'=>$Sales_Order,'quotation_detail_id'=>$Code_QuotDet))->row();
							$rows_QuotDet	= $this->db->get_where('quotation_details',array('id'=>$Code_QuotDet))->row();
							$rows_SchedAloc	= $this->db->get_where('schedule_allocations',array('schedule_detail_id'=>$Code_SchedDet,'schedule_id'=>$Code_Order))->row();
							
							$Arr_SentralTool	= array();
							
							if($Insitu === 'N'){
								$Query_SentralTool	= "SELECT
															sentral_code_tool
														FROM
															quotation_detail_receives
														WHERE
															letter_order_id = '".$Sales_Order."'
														AND quotation_detail_id = '".$Code_QuotDet."'
														AND NOT (
															sentral_code_tool IS NULL
															OR sentral_code_tool = ''
															OR sentral_code_tool = '-'
														)";
								$rows_SentralTool	= $this->db->query($Query_SentralTool)->result();
								$intSentral			= 0;
								if($rows_SentralTool){
									foreach($rows_SentralTool as $keySentral=>$valSentral){
										$Code_Sentral		= $valSentral->sentral_code_tool;
										
										## CEK APAKAH SUDAH DI PROSES DI TRANS DETAIL ##
										$Qry_Find_SentralTool	= "SELECT
																		det_tool.id
																	FROM
																		trans_data_details det_tool
																	INNER JOIN trans_details head_tool ON det_tool.trans_detail_id = head_tool.id
																	WHERE
																		det_tool.sentral_code_tool = '".$Code_Sentral."'
																	AND head_tool.quotation_detail_id = '".$Code_QuotDet."'
																	AND head_tool.letter_order_id = '".$Sales_Order."'";
										$Num_Find_SentralTool	= $this->db->query($Qry_Find_SentralTool)->num_rows();
										if($Num_Find_SentralTool <= 0){
											$intSentral++;
											$Arr_SentralTool[$intSentral]	= $Code_Sentral;
											
										}
									}
									
									unset($rows_SentralTool);
								}
							}
							
							$Ins_Trans							= array();
							$Ins_Trans['id']					= $Code_SchedDet;
							$Ins_Trans['quotation_id']			= $Quot_Order;
							$Ins_Trans['quotation_nomor']		= $rows_Quotation->nomor;;
							$Ins_Trans['quotation_date']		= $rows_Quotation->datet;
							$Ins_Trans['quotation_address']		= $rows_Quotation->address;
							$Ins_Trans['quotation_pic']			= $rows_Quotation->pic_name;
							$Ins_Trans['customer_id']			= $rows_Quotation->customer_id;
							$Ins_Trans['customer_name']			= $rows_Quotation->customer_name;
							$Ins_Trans['delivery_id']			= $rows_LetterDet->delivery_id;
							$Ins_Trans['delivery_area']			= $rows_LetterDet->delivery_name;
							//$Ins_Trans['delivery_day']		= $rows_Quotation->day;
							$Ins_Trans['marketing_id']			= $rows_Quotation->member_id;
							$Ins_Trans['marketing_name']		= $rows_Quotation->member_name;
							$Ins_Trans['pono']					= $rows_Quotation->pono;
							$Ins_Trans['podate']				= $rows_Quotation->podate;
							$Ins_Trans['exc_ppn']				= $rows_Quotation->exc_ppn;
							$Ins_Trans['total_dpp']				= $rows_Quotation->dpp;
							$Ins_Trans['total_diskon']			= $rows_Quotation->diskon;
							$Ins_Trans['total_after']			= $rows_Quotation->total_dpp;
							$Ins_Trans['fee']					= $rows_Quotation->success_fee;
							$Ins_Trans['ppn']					= $rows_Quotation->ppn;
							$Ins_Trans['grand_total']			= $rows_Quotation->grand_tot;
							$Ins_Trans['quotation_detail_id']	= $rows_QuotDet->id;
							$Ins_Trans['tool_id']				= $Code_Tool;
							$Ins_Trans['tool_name']				= $Name_Tool;
							$Ins_Trans['range']					= $rows_QuotDet->range;
							$Ins_Trans['piece_id']				= $rows_QuotDet->piece_id;
							$Ins_Trans['qty']					= $Qty_QuotDet;
							$Ins_Trans['qty_sisa']				= $Qty_QuotDet;
							$Ins_Trans['price']					= $rows_QuotDet->price;
							$Ins_Trans['hpp']					= $rows_QuotDet->hpp;
							$Ins_Trans['diskon']				= $rows_QuotDet->discount;
							
							$Ins_Trans['total_harga']			= round(($Qty_QuotDet * $rows_QuotDet->price) * (100 - $rows_QuotDet->discount) / 100);
							$Ins_Trans['supplier_id']			= $rows_LetterDet->supplier_id;
							$Ins_Trans['supplier_name']			= $rows_LetterDet->supplier_name;
							$Ins_Trans['letter_order_id']		= $Sales_Order;
							$Ins_Trans['letter_order_detail_id']= $rows_LetterDet->id;
							$Ins_Trans['no_so']					= $rows_Letter->no_so;
							$Ins_Trans['tgl_so']				= $rows_Letter->tgl_so;
							$Ins_Trans['address_so']			= $rows_Letter->address;
							$Ins_Trans['pic_so']				= $rows_Letter->pic;
							$Ins_Trans['phone_so']				= $rows_Letter->phone;
							$Ins_Trans['so_descr']				= $rows_LetterDet->descr;
							$Ins_Trans['get_tool']				= $rows_LetterDet->get_tool;
							$Ins_Trans['schedule_id']			= $Find_Exist->id;
							$Ins_Trans['schedule_nomor']		= $Find_Exist->nomor;
							$Ins_Trans['schedule_date']			= $Find_Exist->datet;
							$Ins_Trans['notes']					= $Find_Exist->notes;
							$Ins_Trans['schedule_detail_id']	= $Code_SchedDet;
							$Ins_Trans['labs']					= $Labs;
							$Ins_Trans['insitu']				= $Insitu;
							$Ins_Trans['subcon']				= $Subcon;
							$Ins_Trans['plan_pick_date']		= $Pickup_Date;
							$Ins_Trans['plan_process_date']		= $Process_Date;
							$Ins_Trans['plan_delivery_date']	= $Send_Date;
							$Ins_Trans['plan_subcon_pick_date']	= $Pickup_Subcon;
							$Ins_Trans['plan_subcon_send_date']	= $Send_Subcon;
							$Ins_Trans['plan_time_start']		= (isset($rows_SchedAloc->plan_time_start))?$rows_SchedAloc->plan_time_start:'00:00';
							$Ins_Trans['plan_time_end']			= (isset($rows_SchedAloc->plan_time_end))?$rows_SchedAloc->plan_time_end:'00:00';
							$Ins_Trans['teknisi_id']			= (isset($rows_SchedAloc->member_id))?$rows_SchedAloc->member_id:'';
							$Ins_Trans['teknisi_name']			= (isset($rows_SchedAloc->member_name))?$rows_SchedAloc->member_name:'';
							$Ins_Trans['sts_split']				= $Flag_Split;
							
							if($Insitu == 'N'){
								$Urut_Ident++;
								
								$Ins_Trans['spk_pick_driver_id']		= '-';
								$Ins_Trans['spk_pick_driver_detail_id']	= '-';
								$Ins_Trans['spk_pick_driver_nomor']		= '-';
								$Ins_Trans['spk_pick_driver_date']		= $Pickup_Date;
								$Ins_Trans['pick_driver_id']			= '-';
								$Ins_Trans['pick_driver_name']			= '-';
								$Ins_Trans['flag_cust_pick']			= 'Y';
								$Ins_Trans['qty_rec']					= $Qty_QuotDet;
								$Ins_Trans['location']					= 'Warehouse';
								$Ins_Trans['receiving']					= 'Y';
								$Ins_Trans['receiving_date']			= $Pickup_Date;
								$Ins_Trans['receiving_by']				= '-';
								$Ins_Trans['bast_rec_id']				= '-';
								$Ins_Trans['bast_rec_no']				= '-';
								$Ins_Trans['bast_rec_date']				= $Pickup_Date;
								$Ins_Trans['bast_rec_by']				= $Created_By;
								
								$Arr_Log								= array();
								$Arr_Log['loc_from']					= 'Client';
								$Arr_Log['loc_to']						= 'Warehouse';
								$Arr_Log['quotation_detail_id']			= $Code_SchedDet;
								$Arr_Log['bast_header_id']				= '-';
								$Arr_Log['flag_type']					= 'CUST';
								$Arr_Log['trans']						= 'IN';
								$Arr_Log['qty']							= $Qty_QuotDet;
								$Arr_Log['process_date']				= $Pickup_Date;
								$Arr_Log['process_by']					= $Created_By;
								
								$Has_Ins_TransLog	= $this->db->insert('log_io_trans',$Arr_Log);
								if($Has_Ins_TransLog !== TRUE){
									$Pesan_Error	= 'Error Insert Log In Out Trans';
								}
								
								for($x=1;$x<=$Qty_QuotDet;$x++){
									$Ins_Trans_Detail					= array();
									$Code_ToolSentral					= '';
									$Merk = $Type_Tool = $No_Identik	= $Serial_Number = '';
									if(!empty($Arr_SentralTool) && isset($Arr_SentralTool[$x])){
										$Code_ToolSentral	= $Arr_SentralTool[$x];
										$rows_CustTool		= $this->db->get_where('sentral_customer_tools',array('sentral_tool_code'=>$Code_ToolSentral))->row();
										if($rows_CustTool){
											$Merk 			= $rows_CustTool->merk;
											$Type_Tool 		= $rows_CustTool->tool_type;
											$No_Identik		= $rows_CustTool->no_identifikasi;
											$Serial_Number 	= $rows_CustTool->no_serial_number;
										}
										unset($Arr_SentralTool[$x]);
									}
									
									$k_Detail								= $Code_SchedDet.'-'.$x;
									$Ins_Trans_Detail['id']					= $k_Detail;
									$Ins_Trans_Detail['trans_detail_id']	= $Code_SchedDet;
									$Ins_Trans_Detail['quotation_detail_id']= $rows_QuotDet->id;
									$Ins_Trans_Detail['tool_id']			= $Code_Tool;
									$Ins_Trans_Detail['tool_name']			= $Name_Tool;
									$Ins_Trans_Detail['no_identifikasi']	= $No_Identik;
									$Ins_Trans_Detail['merk']				= $Merk;
									$Ins_Trans_Detail['tool_type']			= $Type_Tool;
									$Ins_Trans_Detail['no_serial_number']	= $Serial_Number;
									$Ins_Trans_Detail['sentral_code_tool']	= $Code_ToolSentral;
									
									$Has_Ins_TransDet	= $this->db->insert('trans_data_details',$Ins_Trans_Detail);
									if($Has_Ins_TransDet !== TRUE){
										$Pesan_Error	= 'Error Insert Trans Data Detail';
									}
								}
								
							}
							
							
							$Has_Ins_Trans	= $this->db->insert('trans_details',$Ins_Trans);
							if($Has_Ins_Trans !== TRUE){
								$Pesan_Error	= 'Error Insert Trans Detail';
							}
							
						}
					}
					
					
					
					
					
					$Upd_Temp_Alocate	= "UPDATE temp_allocations SET status = 'APV' WHERE quotation_detail_id LIKE '".$Quot_Order."%' AND kode_proses = '".$Find_Exist->kode_proses."'";
					$Has_Upd_Temp_Aloc	= $this->db->query($Upd_Temp_Alocate);
					if($Has_Upd_Temp_Aloc !== TRUE){
						$Pesan_Error	= 'Error Update Schedule Temp Allocation';
					}
					
					
					
					if ($this->db->trans_status() != TRUE || !empty($Pesan_Error)){
						$this->db->trans_rollback();
						$rows_Return		= array(
							'status'		=> 2,
							'pesan'			=> 'Approval Process  Failed, '.$Pesan_Error
						);
						history('Approval Schedule Order '.$Code_Order.' - '.$Pesan_Error);
					}else{
						$this->db->trans_commit();
						$rows_Return		= array(
							'status'		=> 1,
							'pesan'			=> 'Approval process success. Thank you & have a nice day......'
						);
						history('Approval Schedule Order '.$Code_Order.' - '.$Cancel_Reason);
					}
					
				}
			}else{
				$rows_Return	= array(
					'status'		=> 2,
					'pesan'			=> 'No Record was found...'
				);
			}
			

						
		}
		echo json_encode($rows_Return);
	}
	
	function revisi_schedule_order(){
		$rows_Header	= $rows_Quot = $rows_Detail = $rows_Letter = array();
		$Tgl_Old		= date('Y-m-d');
		$Noso_Rev		= '';
		$Urut_Rev		= '';
		$Code_Old		= '';
		if($this->input->get()){
			$Code_Process	= urldecode($this->input->get('nomor_order'));
			$rows_Header	= $this->db->get_where('schedules',array('id'=>$Code_Process))->row();
			$rows_Detail	= $this->db->get_where('schedule_details',array('schedule_id'=>$Code_Process))->result();
			$rows_Quot		= $this->db->get_where('quotations',array('id'=>$rows_Header->quotation_id))->row();
			$rows_Letter	= $this->db->get_where('letter_orders',array('id'=>$rows_Header->letter_order_id))->row();
			
			$Nomor_SO		= $rows_Header->nomor;
			$Code_Old		= (isset($rows_Header->old_id) && !empty($rows_Header->old_id))?$rows_Header->old_id:$Code_Process;
			$rows_HeadOld	= $this->db->get_where('schedules',array('id'=>$Code_Old))->row();
			if($rows_HeadOld){
				$Urut_Rev		= intval($rows_HeadOld->revisi) + 1;
				$Nomor_SO		= $rows_HeadOld->nomor;
				
			}
			
			$Noso_Rev			= $Nomor_SO.'/Rev-'.$Urut_Rev;
			
			
			
		}
		$data = array(
			'title'			=> 'REVISION SCHEDULE ORDER',
			'action'		=> 'revisi_schedule_order',
			'akses_menu'	=> $this->Arr_Akses,
			'rows_header'	=> $rows_Header,
			'rows_detail'	=> $rows_Detail,
			'rows_quot'		=> $rows_Quot,
			'rows_letter'	=> $rows_Letter,
			'nomor_rev'		=> $Noso_Rev,
			'urut_rev'		=> $Urut_Rev,
			'code_old'		=> $Code_Old
		);
		
		$this->load->view($this->folder.'/v_schedule_order_revisi',$data);
	}
	
	function save_revisi_schedule_order(){
		$rows_Return	= array(
			'status'		=> 2,
			'pesan'			=> 'No record was found...'
		);
		
		if($this->input->post()){
			//echo "<pre>";print_r($this->input->post());exit;
			
			
			
			$Created_By			= $this->session->userdata('siscal_username');
			$Created_Id			= $this->session->userdata('siscal_userid');
			$Created_Date		= date('Y-m-d H:i:s');
			
			$Nomor_Schedule		= $this->input->post('nomor');
			$Urut_Revisi		= $this->input->post('revisi');
			$Old_Code_Schedule	= $this->input->post('old_id');
			$Prev_Code_Schedule	= $this->input->post('prev_id');
			
			$Code_Sales			= $this->input->post('letter_order_id');
			$Code_Process		= $this->input->post('kode_proses');
			$Nocust				= $this->input->post('customer_id');
			$Code_Quot			= $this->input->post('quotation_id');
			$Customer			= strtoupper($this->input->post('customer_name'));
			$PIC_Name			= strtoupper($this->input->post('pic_name'));
			$Address			= strtoupper($this->input->post('address'));
			$Notes				= strtoupper($this->input->post('notes'));
			
			$detDetail			= $this->input->post('detDetail');
			
			$Schedule_Date		= date('Y-m-d');
			
			$Date_Now			= date('Y-m-d');
			$Tahun_Now			= date('Y');
			$Month_Now			= date('m');
			$Bulan_Now			= date('n');
			$YearMonth			= date('Ym');
			
			
			
			
			
			$OK_Proses		= 1;
			$Pesan_Error	= '';
			$rows_Exists	= $this->db->get_where('schedules',array('id'=>$Prev_Code_Schedule))->row();
			if($rows_Exists){
				if($rows_Exists->status !== 'OPN'){
					$OK_Proses		= 0;
					$Pesan_Error	= 'Data has been modified by other process...';
				}
			}else{
				$OK_Proses		= 0;
				$Pesan_Error	= 'No record was found...';
			}
			
			if($OK_Proses === 0){
				$rows_Return		= array(
					'status'		=> 2,
					'pesan'			=> $Pesan_Error
				);
			}else{
				
				$this->db->trans_begin();
				
				## AMBIL NOMOR URUT ##
				$Urut_Code		= $Urut_Nomor	= 1;
				$Qry_Urut_Code	= "SELECT
										MAX(
											CAST(
												SUBSTRING_INDEX(id, '-' ,-1) AS UNSIGNED
											)
										) AS nomor_urut
									FROM
										schedules
									WHERE
										YEAR(datet) = '".$Tahun_Now."'";
				$rows_Urut_Code	= $this->db->query($Qry_Urut_Code)->row();
				if($rows_Urut_Code){
					$Urut_Code	= intval($rows_Urut_Code->nomor_urut) + 1;
				}
				$Pref_Urut		= sprintf('%05d',$Urut_Code);
				if($Urut_Code >=100000){
					$Pref_Urut	= $Urut_Code;
				}
				
				$Code_Schedule		= 'SCH-'.$YearMonth.'-'.$Pref_Urut;
				
				// Update OLD
				$Update_Old	= array(
					'revisi'		=> $Urut_Revisi,
					'modified_date'	=> $Created_Date,
					'modified_by'	=> $Created_Id
				);
				
				$Has_Old_Schedule	= $this->db->update('schedules',$Update_Old,array('id'=>$Old_Code_Schedule));
				if($Has_Old_Schedule !== TRUE){
					$Pesan_Error	= 'Error Update Schedule - Old...';
				}
				
				// update Prev
				$Update_Prev	= array(
					'status'		=>'REV',
					'modified_date'	=> $Created_Date,
					'modified_by'	=> $Created_Id
				);
				
				$Has_Prev_Schedule	= $this->db->update('schedules',$Update_Prev,array('id'=>$Prev_Code_Schedule));
				if($Has_Prev_Schedule !== TRUE){
					$Pesan_Error	= 'Error Update Schedule - Previous...';
				}
				
				
				
				## AMBIL DATA SPK DETAIL TOOL ##
				$rows_Detail		= $this->db->get_where('schedule_details',array('schedule_id'=>$Prev_Code_Schedule))->result();
				if($rows_Detail){
					$intL	= 0;
					foreach($rows_Detail as $keyTool=>$valTool){
						$intL++;
						$Qty			= $valTool->qty;
						$Code_Receive	= $valTool->quotation_detail_id;
						
						$Upd_SO_Detail	= "UPDATE letter_order_details SET qty_schedule = qty_schedule - ".$Qty." WHERE quotation_detail_id = '".$Code_Receive."' AND letter_order_id = '".$Code_Sales."'";
						$Has_Upd_SODet	= $this->db->query($Upd_SO_Detail);
						if($Has_Upd_SODet !== TRUE){
							$Pesan_Error	= 'Error Insert Sales Order Detail...';
						}
						
					}
				}
				
				
				$Ins_Header			= array(
					'id'				=> $Code_Schedule,
					'nomor'				=> $Nomor_Schedule,
					'datet'				=> $Date_Now,
					'letter_order_id'	=> $Code_Sales,
					'quotation_id'		=> $Code_Quot,
					'customer_id'		=> $Nocust,
					'customer_name'		=> $Customer,
					'status'			=> 'OPN',
					'notes'				=> $Notes,
					'revisi'			=> $Urut_Revisi,
					'created_by'		=> $Created_Id,
					'created_date'		=> $Created_Date,
					'kode_proses'		=> $Code_Process,
					'old_id'			=> $Old_Code_Schedule,
					'prev_id'			=> $Prev_Code_Schedule
				);
				$Has_Ins_Header			= $this->db->insert('schedules',$Ins_Header);
				if($Has_Ins_Header !== TRUE){
					$Pesan_Error	= 'Error Insert Schedule Header...';
				}
				
				if($detDetail){
					$intL	= 0;
					foreach($detDetail as $keyDet=>$valDet){
						$intL++;
						$Code_SchedDet	= $Code_Schedule.'-'.$intL;
						
						$Code_Detail	= $valDet['code_detail'];
						$Code_QuotDet	= $valDet['quotation_detail_id'];
						
						$Code_Tool		= $valDet['tool_id'];
						$Name_Tool		= $valDet['tool_name'];
						$Cust_Tool		= $valDet['tool_cust'];
						$Labs			= $valDet['labs'];
						$Insitu			= $valDet['insitu'];
						$Subcon			= $valDet['subcon'];
						$Qty_SO			= $valDet['qty_process'];
						$Flag_Split		= $valDet['sts_split'];
						$Waktu_Tempuh	= $valDet['waktu_tempuh'];
						$Urut_ID		= $valDet['urut_id'];
						$Supplier_Code	= $valDet['supplier_id'];
						$Qty_Process	= $valDet['qty'];
						
						$Pickup_Date	= $Cals_Date	= $Send_Date = $Subcon_Pickup = $Subcon_Send = $Time_Start = $Time_End = NULL;
						$Code_Teknisi 	= $Name_Teknisi = '';
						
						if(isset($valDet['pick_date']) && !empty($valDet['pick_date'])){
							$Pickup_Date	= $valDet['pick_date'];
						}
						
						if(isset($valDet['process_date']) && !empty($valDet['process_date'])){
							$Cals_Date	= $valDet['process_date'];
						}
						
						if(isset($valDet['delivery_date']) && !empty($valDet['delivery_date'])){
							$Send_Date	= $valDet['delivery_date'];
						}
						
						if(isset($valDet['subcon_pick_date']) && !empty($valDet['subcon_pick_date'])){
							$Subcon_Pickup	= $valDet['subcon_pick_date'];
						}
						
						if(isset($valDet['subcon_send_date']) && !empty($valDet['subcon_send_date'])){
							$Subcon_Send	= $valDet['subcon_send_date'];
						}
						
						if(isset($valDet['jam_awal']) && !empty($valDet['jam_awal'])){
							$Time_Start	= $valDet['jam_awal'].':00';
						}
						
						if(isset($valDet['jam_akhir']) && !empty($valDet['jam_akhir'])){
							$Time_End	= $valDet['jam_akhir'].':00';
						}
						
						if(isset($valDet['member_id']) && !empty($valDet['member_id'])){
							$Code_Teknisi	= $valDet['member_id'];
						}
						
						if(isset($valDet['member_name']) && !empty($valDet['member_name'])){
							$Name_Teknisi	= $valDet['member_name'];
						}
						
						
						$Upd_Letter_Detail	= "UPDATE letter_order_details SET qty_schedule = qty_schedule + ".$Qty_Process." WHERE id = '".$Code_Detail."'";
						$Has_Upd_SODet		= $this->db->query($Upd_Letter_Detail);
						if($Has_Upd_SODet !== TRUE){
							$Pesan_Error	= 'Error Update Sales Order Detail...';
						}
						
						
						
						$Ins_Detail		= array(
							'id'					=> $Code_SchedDet,
							'schedule_id'			=> $Code_Schedule,
							'quotation_detail_id'	=> $Code_QuotDet,
							'tool_id'				=> $Code_Tool,
							'tool_name'				=> $Cust_Tool,
							'qty'					=> $Qty_Process,
							'pick_date'				=> $Pickup_Date,
							'process_date'			=> $Cals_Date,
							'delivery_date'			=> $Send_Date,
							'selected'				=> 0,
							'insitu'				=> $Insitu,
							'labs'					=> $Labs,
							'subcon'				=> $Subcon,
							'subcon_send_date'		=> $Subcon_Send,
							'subcon_pick_date'		=> $Subcon_Pickup,
							'waktu_tempuh'			=> $Waktu_Tempuh,
							'sts_split'				=> $Flag_Split,
							'urut_id'				=> $Urut_ID
						);
						
						$Has_Ins_Detail	= $this->db->insert('schedule_details',$Ins_Detail);
						if($Has_Ins_Detail !== TRUE){
							$Pesan_Error	= 'Error Insert Schedule Detail...';
						}
						
						if(!empty($Time_Start) && $Time_Start !== NULL && !empty($Time_End) && $Time_End !== NULL){
							$Ins_Detail_Aloc		= array(
								'schedule_detail_id'	=> $Code_SchedDet,
								'schedule_id'			=> $Code_Schedule,
								'quotation_detail_id'	=> $Code_QuotDet,
								'tool_id'				=> $Code_Tool,
								'tool_name'				=> $Cust_Tool,
								'qty'					=> $Qty_Process,
								'plan_date_start'		=> $Cals_Date,
								'plan_time_start'		=> $Time_Start,
								'plan_date_end'			=> $Cals_Date,
								'plan_time_end'			=> $Time_End,
								'member_id'				=> $Code_Teknisi,
								'member_name'			=> $Name_Teknisi,
								'waktu_tempuh'			=> $Waktu_Tempuh
							);
							
							$Has_Ins_Detail_Aloc	= $this->db->insert('schedule_allocations',$Ins_Detail_Aloc);
							if($Has_Ins_Detail_Aloc !== TRUE){
								$Pesan_Error	= 'Error Insert Schedule Allocation...';
							}
						}
					}
				}
				
				
				
				$Upd_Letter	= array(
					'sts_so'		=> 'SCH'
				);
				
				$Has_Upd_Letter			= $this->db->update('letter_orders',$Upd_Letter,array('id'=>$Code_Sales));
				if($Has_Upd_Letter !== TRUE){
					$Pesan_Error	= 'Error Update Sales Order...';
				}
				
				if ($this->db->trans_status() != TRUE || !empty($Pesan_Error)){
					$this->db->trans_rollback();
					$rows_Return		= array(
						'status'		=> 2,
						'pesan'			=> 'Revision Schedule Order Process  Failed, '.$Pesan_Error
					);
					history('Revision Schedule Order '.$Nomor_Schedule.' - '.$Pesan_Error);
				}else{
					$this->db->trans_commit();
					$rows_Return		= array(
						'status'		=> 1,
						'pesan'			=> 'Revision Schedule Order success. Thank you & have a nice day......'
					);
					history('Revision Schedule Order '.$Nomor_Schedule.' - Success ');
				}
			}

		}
		
		echo json_encode($rows_Return);
	}
	
	
	function approve_reschedule_order(){
		$rows_Header	= $rows_Quot = $rows_Detail = $rows_Letter = array();
		
		if($this->input->get()){
			$Code_Process	= urldecode($this->input->get('nomor_order'));
			$rows_Header	= $this->db->get_where('schedules',array('id'=>$Code_Process))->row();
			$rows_Detail	= $this->db->get_where('schedule_details',array('schedule_id'=>$Code_Process))->result();
			$rows_Quot		= $this->db->get_where('quotations',array('id'=>$rows_Header->quotation_id))->row();
			$rows_Letter	= $this->db->get_where('letter_orders',array('id'=>$rows_Header->letter_order_id))->row();			
		}
		$data = array(
			'title'			=> 'APPROVE RESCHEDULE',
			'action'		=> 'approve_reschedule_order',
			'akses_menu'	=> $this->Arr_Akses,
			'rows_header'	=> $rows_Header,
			'rows_detail'	=> $rows_Detail,
			'rows_quot'		=> $rows_Quot,
			'rows_letter'	=> $rows_Letter
		);
		
		$this->load->view($this->folder.'/v_schedule_order_recycle',$data);
	}
	
	function save_process_reschedule_order(){
		$rows_Return	= array(
			'status'		=> 2,
			'pesan'			=> 'No record was found...'
		);
		
		if($this->input->post()){
			//echo "<pre>";print_r($this->input->post());exit;
			
			
			
			$Created_By			= $this->session->userdata('siscal_username');
			$Created_Id			= $this->session->userdata('siscal_userid');
			$Created_Date		= date('Y-m-d H:i:s');
			
			$Nomor_Schedule		= $this->input->post('nomor');
			$Code_Schedule		= $this->input->post('code_schedule');
			
			$Code_Sales			= $this->input->post('letter_order_id');
			$Code_Process		= $this->input->post('kode_proses');
			$Nocust				= $this->input->post('customer_id');
			$Code_Quot			= $this->input->post('quotation_id');
			$Customer			= strtoupper($this->input->post('customer_name'));
			$PIC_Name			= strtoupper($this->input->post('pic_name'));
			$Address			= strtoupper($this->input->post('address'));
			$Notes				= strtoupper($this->input->post('notes'));
			
			$detDetail			= $this->input->post('detDetail');
			
			$Schedule_Date		= date('Y-m-d');
			
			$Date_Now			= date('Y-m-d');
			$Tahun_Now			= date('Y');
			$Month_Now			= date('m');
			$Bulan_Now			= date('n');
			$YearMonth			= date('Ym');
			
			
			
			
			
			$OK_Proses		= 1;
			$Pesan_Error	= '';
			$rows_Exists	= $this->db->get_where('schedules',array('id'=>$Code_Schedule))->row();
			if($rows_Exists){
				if($rows_Exists->status !== 'APV'){
					$OK_Proses		= 0;
					$Pesan_Error	= 'Data has been modified by other process...';
				}
			}else{
				$OK_Proses		= 0;
				$Pesan_Error	= 'No record was found...';
			}
			
			if($OK_Proses === 0){
				$rows_Return		= array(
					'status'		=> 2,
					'pesan'			=> $Pesan_Error
				);
			}else{
				
				$this->db->trans_begin();
				
				
				## DELETE SCHEDULE DETAIL ##
				$Qry_Del_SchedDetail	= "DELETE FROM schedule_details WHERE schedule_id = '".$Code_Schedule."'";
				$Has_Del_SchedDetail	= $this->db->query($Qry_Del_SchedDetail);
				if($Has_Del_SchedDetail !== TRUE){
					$Pesan_Error	= 'Error Delete Schedule Detail...';
				}
				
				## DELETE SCHEDULE ALLOCATION ##
				$Qry_Del_SchedAlocate	= "DELETE FROM schedule_allocations WHERE schedule_id = '".$Code_Schedule."'";
				$Has_Del_SchedAlocate	= $this->db->query($Qry_Del_SchedAlocate);
				if($Has_Del_SchedAlocate !== TRUE){
					$Pesan_Error	= 'Error Delete Schedule Alocate...';
				}
				
				
				
				if($detDetail){
					$intL	= 0;
					foreach($detDetail as $keyDet=>$valDet){
						$intL++;
						$Code_SchedDet	= $Code_Schedule.'-'.$intL;
						
						$Code_Detail	= $valDet['code_detail'];
						$Code_QuotDet	= $valDet['quotation_detail_id'];
						
						$Code_Tool		= $valDet['tool_id'];
						$Name_Tool		= $valDet['tool_name'];
						$Cust_Tool		= $valDet['tool_cust'];
						$Labs			= $valDet['labs'];
						$Insitu			= $valDet['insitu'];
						$Subcon			= $valDet['subcon'];
						$Qty_SO			= $valDet['qty_process'];
						$Flag_Split		= $valDet['sts_split'];
						$Waktu_Tempuh	= $valDet['waktu_tempuh'];
						$Urut_ID		= $valDet['urut_id'];
						$Supplier_Code	= $valDet['supplier_id'];
						$Qty_Process	= $valDet['qty'];
						
						$Pickup_Date	= $Cals_Date	= $Send_Date = $Subcon_Pickup = $Subcon_Send = $Time_Start = $Time_End = NULL;
						$Code_Teknisi 	= $Name_Teknisi = '';
						
						if(isset($valDet['pick_date']) && !empty($valDet['pick_date'])){
							$Pickup_Date	= $valDet['pick_date'];
						}
						
						if(isset($valDet['process_date']) && !empty($valDet['process_date'])){
							$Cals_Date	= $valDet['process_date'];
						}
						
						if(isset($valDet['delivery_date']) && !empty($valDet['delivery_date'])){
							$Send_Date	= $valDet['delivery_date'];
						}
						
						if(isset($valDet['subcon_pick_date']) && !empty($valDet['subcon_pick_date'])){
							$Subcon_Pickup	= $valDet['subcon_pick_date'];
						}
						
						if(isset($valDet['subcon_send_date']) && !empty($valDet['subcon_send_date'])){
							$Subcon_Send	= $valDet['subcon_send_date'];
						}
						
						if(isset($valDet['jam_awal']) && !empty($valDet['jam_awal'])){
							$Time_Start	= $valDet['jam_awal'].':00';
						}
						
						if(isset($valDet['jam_akhir']) && !empty($valDet['jam_akhir'])){
							$Time_End	= $valDet['jam_akhir'].':00';
						}
						
						if(isset($valDet['member_id']) && !empty($valDet['member_id'])){
							$Code_Teknisi	= $valDet['member_id'];
						}
						
						if(isset($valDet['member_name']) && !empty($valDet['member_name'])){
							$Name_Teknisi	= $valDet['member_name'];
						}
						
						$Upd_Trans		= array(
							'plan_pick_date'		=> $Pickup_Date,
							'plan_process_date'		=> $Cals_Date,
							'plan_delivery_date'	=> $Send_Date,
							'insitu'				=> $Insitu,
							'labs'					=> $Labs,
							'subcon'				=> $Subcon,
							'plan_subcon_send_date'	=> $Subcon_Send,
							'plan_subcon_pick_date'	=> $Subcon_Pickup,
							'plan_time_start'		=> $Time_Start,
							'plan_time_end'			=> $Time_End,
							'teknisi_id'			=> $Code_Teknisi,
							'teknisi_name'			=> $Name_Teknisi
						);
						
						$Has_Upd_Trans		= $this->db->update('trans_details',$Upd_Trans,array('id'=>$Code_Detail));
						if($Has_Upd_Trans !== TRUE){
							$Pesan_Error	= 'Error Update Trans Detail...';
						}
				
						
						
						
						$Ins_Detail		= array(
							'id'					=> $Code_Detail,
							'schedule_id'			=> $Code_Schedule,
							'quotation_detail_id'	=> $Code_QuotDet,
							'tool_id'				=> $Code_Tool,
							'tool_name'				=> $Cust_Tool,
							'qty'					=> $Qty_Process,
							'pick_date'				=> $Pickup_Date,
							'process_date'			=> $Cals_Date,
							'delivery_date'			=> $Send_Date,
							'selected'				=> 0,
							'insitu'				=> $Insitu,
							'labs'					=> $Labs,
							'subcon'				=> $Subcon,
							'subcon_send_date'		=> $Subcon_Send,
							'subcon_pick_date'		=> $Subcon_Pickup,
							'waktu_tempuh'			=> $Waktu_Tempuh,
							'sts_split'				=> $Flag_Split,
							'urut_id'				=> $Urut_ID
						);
						
						$Has_Ins_Detail	= $this->db->insert('schedule_details',$Ins_Detail);
						if($Has_Ins_Detail !== TRUE){
							$Pesan_Error	= 'Error Insert Schedule Detail...';
						}
						
						if(!empty($Time_Start) && $Time_Start !== NULL && !empty($Time_End) && $Time_End !== NULL){
							$Ins_Detail_Aloc		= array(
								'schedule_detail_id'	=> $Code_Detail,
								'schedule_id'			=> $Code_Schedule,
								'quotation_detail_id'	=> $Code_QuotDet,
								'tool_id'				=> $Code_Tool,
								'tool_name'				=> $Cust_Tool,
								'qty'					=> $Qty_Process,
								'plan_date_start'		=> $Cals_Date,
								'plan_time_start'		=> $Time_Start,
								'plan_date_end'			=> $Cals_Date,
								'plan_time_end'			=> $Time_End,
								'member_id'				=> $Code_Teknisi,
								'member_name'			=> $Name_Teknisi,
								'waktu_tempuh'			=> $Waktu_Tempuh
							);
							
							$Has_Ins_Detail_Aloc	= $this->db->insert('schedule_allocations',$Ins_Detail_Aloc);
							if($Has_Ins_Detail_Aloc !== TRUE){
								$Pesan_Error	= 'Error Insert Schedule Allocation...';
							}
						}
					}
				}
				
				
				
				
				if ($this->db->trans_status() != TRUE || !empty($Pesan_Error)){
					$this->db->trans_rollback();
					$rows_Return		= array(
						'status'		=> 2,
						'pesan'			=> 'Arrange Schedule Order Process  Failed, '.$Pesan_Error
					);
					history('Arrange Schedule Order '.$Nomor_Schedule.' - '.$Pesan_Error);
				}else{
					$this->db->trans_commit();
					$rows_Return		= array(
						'status'		=> 1,
						'pesan'			=> 'Arrange Schedule Order success. Thank you & have a nice day......'
					);
					history('Arrange Schedule Order '.$Nomor_Schedule.' - Success ');
				}
			}

		}
		
		echo json_encode($rows_Return);
	}
	
	
	function CancelRescheduleProcess(){
		$rows_Return	= array(
			'status'		=> 2,
			'pesan'			=> 'No record was found...'
		);
		if($this->input->post()){
			$SalesOrder		= $this->input->post('code');
			$CodeProcess	= $this->input->post('kode_proses');
			$rows_Header	= $this->db->get_where('schedules',array('id'=>$SalesOrder))->row();
			$this->db->trans_begin();
			$Pesan_Error	= '';
			
			$rows_Alocate	= $this->db->get_where('schedule_allocations',array('schedule_id'=>$SalesOrder))->result();
			$Text_Insert	= "";
			if($rows_Alocate){
				foreach($rows_Alocate as $keyAl=>$valAl){
					$Code_Detail	= $valAl->schedule_detail_id;
					
					$Flag_Split		= 'N';
					$Urut_ID		= 0;
					$rows_Detail	= $this->db->get_where('schedule_details',array('id'=>$Code_Detail))->row();
					if($rows_Detail){
						$Flag_Split	= $rows_Detail->sts_split;
						$Urut_ID	= $rows_Detail->urut_id;
					}
					
					$New_KodeProses	= $CodeProcess;
					if($Urut_ID > 0){
						$New_KodeProses	= $CodeProcess.'-'.$Urut_ID;
					}
					
					if(!empty($Text_Insert))$Text_Insert .=",";
					$Text_Insert	.="(
										'".$valAl->quotation_detail_id."',
										'".$valAl->member_id."',
										'".$valAl->plan_date_start."',
										'".$valAl->plan_time_start."',
										'".$valAl->plan_date_end."',
										'".$valAl->plan_time_end."',
										'RES',
										'".$New_KodeProses."',
										'".$Flag_Split."'
									)";
					
				}
			}
			
			
			$Query_Insert		= "INSERT INTO temp_allocations (
										quotation_detail_id,
										member_id,
										plan_date_start,
										plan_time_start,
										plan_date_end,
										plan_time_end,
										`status`,
										kode_proses,
										sts_split
									)
									VALUES
									".$Text_Insert."
									;";
			
			
			$Del_Temp_Alocate	= "DELETE FROM temp_allocations WHERE quotation_detail_id LIKE '".$rows_Header->quotation_id."%' AND kode_proses LIKE '".$CodeProcess."%'";
			$Has_Del_Temp_Aloc	= $this->db->query($Del_Temp_Alocate);
			if($Has_Del_Temp_Aloc !== TRUE){
				$Pesan_Error	= 'Error Delete Schedule Temp Allocation';
			}
			
			$Has_Ins_Real		= $this->db->query($Query_Insert);
			if($Has_Ins_Real !== TRUE){
				$Pesan_Error	= 'Error Insert Schedule Temp Allocation - Real';
			}

			if ($this->db->trans_status() != TRUE || !empty($Pesan_Error)){
				$this->db->trans_rollback();
				$rows_Return		= array(
					'status'		=> 2,
					'pesan'			=> 'Cancellation Schedule Process  Failed, '.$Pesan_Error
				);
				history('Cancellation Schedule Process '.$SalesOrder.' - '.$CodeProcess.' - '.$Pesan_Error);
			}else{
				$this->db->trans_commit();
				$rows_Return		= array(
					'status'		=> 1,
					'pesan'			=> 'Cancellation Schedule Process success. Thank you & have a nice day......'
				);
				history('Cancellation Schedule Process '.$SalesOrder.' - '.$CodeProcess.' - Success');
			}
			
			
		}
		
		echo json_encode($rows_Return);
	}
	
}