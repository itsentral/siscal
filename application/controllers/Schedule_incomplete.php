<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Schedule_incomplete extends CI_Controller { 
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
			'title'			=> 'OUTSTANDING RESCHEDULE',
			'action'		=> 'index',
			'akses_menu'	=> $Arr_Akses
		);
		history('View List Outstanding Reschedule');
		$this->load->view($this->folder.'/v_schedule_incomplete',$data);
	}
	function get_data_display(){
		$Arr_Akses		= $this->Arr_Akses;
		
		$WHERE			= "qty_reschedule > 0
							AND pro_reschedule <> 'Y' /* AND re_schedule<>'Y'  */";
		
		
		$requestData	= $_REQUEST;
		
		$like_value     = $requestData['search']['value'];
        $column_order   = $requestData['order'][0]['column'];
        $column_dir     = $requestData['order'][0]['dir'];
        $limit_start    = $requestData['start'];
        $limit_length   = $requestData['length'];
		
		$columns_order_by = array(
			0 => 'schedule_nomor',
			1 => 'schedule_date',
			2 => 'customer_name',
			3 => 'quotation_nomor',
			4 => 'no_so'
		);
		
		
		
		if($like_value){
			if(!empty($WHERE))$WHERE	.=" AND ";
			$WHERE	.="(
						  no_so LIKE '%".$this->db->escape_like_str($like_value)."%'
						  OR DATE_FORMAT(schedule_date, '%d %b %Y') LIKE '%".$this->db->escape_like_str($like_value)."%'
						  OR customer_name LIKE '%".$this->db->escape_like_str($like_value)."%'
						  OR schedule_nomor LIKE '%".$this->db->escape_like_str($like_value)."%'
						  OR quotation_nomor LIKE '%".$this->db->escape_like_str($like_value)."%'
						)";
		}
		
		
		
		$sql = "SELECT
					schedule_id,
					schedule_nomor,
					schedule_date,
					customer_id,
					customer_name,
					address_so AS address,
					pic_so AS pic,
					letter_order_id,
					no_so,
					quotation_id,
					quotation_nomor,
					pono,
					marketing_id AS member_id,
					marketing_name AS member_name,
					(@row:=@row+1) AS urut
				FROM
					trans_details,
				(SELECT @row:=0) r 
				WHERE ".$WHERE."
				GROUP BY
					schedule_id
				";
		//print_r($sql);exit();
		$fetch['totalData'] 	= $this->db->query($sql)->num_rows();
		$fetch['totalFiltered']	= $this->db->query($sql)->num_rows();

		

		$sql .= " ORDER BY schedule_date DESC,".$columns_order_by[$column_order]." ".$column_dir." ";
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
			
			$Code_Schedule		= $row['schedule_id'];
			$Nomor_Schedule		= $row['schedule_nomor'];
			$Date_Schedule		= date('d-m-Y',strtotime($row['schedule_date']));
			$Nomor_SO			= $row['no_so'];
			$Custid				= $row['customer_id'];
			$Customer			= $row['customer_name'];
			$Marketing			= strtoupper($row['member_name']);
			
			$Quot_Nomor			= $row['quotation_nomor'];
			
			$Template			='';
			if($Arr_Akses['create'] == '1' || $Arr_Akses['update'] == '1'){
				$Template		= '<a href="'.site_url().'/Schedule_incomplete/process_reschedule_order?nomor_order='.urlencode($Code_Schedule).'" class="btn btn-sm bg-navy-active" title="PROCESS RESCHEDULE"> <i class="fa fa-calendar"></i> </a>';
				
			}
			
			
			$nestedData		= array();
			$nestedData[]	= $Nomor_Schedule;
			$nestedData[]	= $Date_Schedule;
			$nestedData[]	= $Customer;
			$nestedData[]	= $Quot_Nomor;
			$nestedData[]	= $Nomor_SO;
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
	
	
	
	
	function process_reschedule_order(){
		$rows_Header	= $rows_Quot = $rows_Detail = $rows_Letter = array();
		$Tgl_Old		= date('Y-m-d');
		$Noso_Rev		= '';
		$Urut_Rev		= '';
		$Code_Old		= '';
		if($this->input->get()){
			$Code_Process	= urldecode($this->input->get('nomor_order'));
			$rows_Header	= $this->db->get_where('schedules',array('id'=>$Code_Process))->row();
			$rows_Detail	= $this->db->get_where('trans_details',array('schedule_id'=>$Code_Process,'qty_reschedule >'=>0,'pro_reschedule !='=>'Y'))->result();
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
		$Code_Process		= date('YmdHis');
		$data = array(
			'title'			=> 'RESCHEDULE PROCESS',
			'action'		=> 'process_reschedule_order',
			'akses_menu'	=> $this->Arr_Akses,
			'rows_header'	=> $rows_Header,
			'rows_detail'	=> $rows_Detail,
			'rows_quot'		=> $rows_Quot,
			'rows_letter'	=> $rows_Letter,
			'nomor_rev'		=> $Noso_Rev,
			'urut_rev'		=> $Urut_Rev,
			'code_old'		=> $Code_Old,
			'kode_proses'	=> $Code_Process
		);
		
		$this->load->view($this->folder.'/v_schedule_incomplete_process',$data);
	}
	
	function save_reschedule_order_process(){
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
			$Code_Process		= $this->input->post('kode_proses_new');
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
			if(empty($rows_Exists)){
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
				
				
				
				$Ins_Header			= array(
					'id'				=> $Code_Schedule,
					'nomor'				=> $Nomor_Schedule,
					'datet'				=> $Date_Now,
					'letter_order_id'	=> $Code_Sales,
					'quotation_id'		=> $Code_Quot,
					'customer_id'		=> $Nocust,
					'customer_name'		=> $Customer,
					'status'			=> 'APV',
					'notes'				=> $Notes,
					'revisi'			=> $Urut_Revisi,
					'created_by'		=> $Created_Id,
					'created_date'		=> $Created_Date,
					'kode_proses'		=> $Code_Process,
					'old_id'			=> $Old_Code_Schedule,
					'prev_id'			=> $Prev_Code_Schedule,
					'approve_by'		=> 'OTO-SISTEM',
					'approve_date'		=> $Created_Date
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
						
						
						$Upd_Trans_Detail		= "UPDATE trans_details SET pro_reschedule = 'Y' WHERE id = '".$Code_Detail."'";
						$Has_Upd_Trans_Detail	= $this->db->query($Upd_Trans_Detail);
						if($Has_Upd_Trans_Detail !== TRUE){
							$Pesan_Error	= 'Error Update Trans Detail - Old...';
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
						
						$rows_Trans	= $this->db->get_where('trans_details',array('id'=>$Code_Detail))->row();
						
						$Ins_Trans							= array();
						$Ins_Trans['id']					= $Code_SchedDet;
						$Ins_Trans['quotation_id']			= $rows_Trans->quotation_id;
						$Ins_Trans['quotation_nomor']		= $rows_Trans->quotation_nomor;
						$Ins_Trans['quotation_date']		= $rows_Trans->quotation_date;
						$Ins_Trans['quotation_address']		= $rows_Trans->quotation_address;
						$Ins_Trans['quotation_pic']			= $rows_Trans->quotation_pic;
						$Ins_Trans['customer_id']			= $rows_Trans->customer_id;
						$Ins_Trans['customer_name']			= $rows_Trans->customer_name;
						$Ins_Trans['delivery_id']			= $rows_Trans->delivery_id;
						$Ins_Trans['delivery_area']			= $rows_Trans->delivery_area;
						$Ins_Trans['delivery_day']			= $rows_Trans->delivery_day;
						$Ins_Trans['marketing_id']			= $rows_Trans->marketing_id;
						$Ins_Trans['marketing_name']		= $rows_Trans->marketing_name;
						$Ins_Trans['pono']					= $rows_Trans->pono;
						$Ins_Trans['podate']				= $rows_Trans->podate;
						$Ins_Trans['exc_ppn']				= $rows_Trans->exc_ppn;
						$Ins_Trans['total_dpp']				= $rows_Trans->total_dpp;
						$Ins_Trans['total_diskon']			= $rows_Trans->total_diskon;
						$Ins_Trans['total_after']			= $rows_Trans->total_after;
						$Ins_Trans['fee']					= $rows_Trans->fee;
						$Ins_Trans['ppn']					= $rows_Trans->ppn;
						$Ins_Trans['grand_total']			= $rows_Trans->grand_total;
						$Ins_Trans['quotation_detail_id']	= $rows_Trans->quotation_detail_id;
						$Ins_Trans['tool_id']				= $Code_Tool;
						$Ins_Trans['tool_name']				= $Cust_Tool;
						$Ins_Trans['range']					= $rows_Trans->range;
						$Ins_Trans['piece_id']				= $rows_Trans->piece_id;
						$Ins_Trans['qty']					= $Qty_Process;
						$Ins_Trans['qty_sisa']				= $Qty_Process;
						$Ins_Trans['price']					= $rows_Trans->price;
						$Ins_Trans['hpp']					= $rows_Trans->hpp;
						$Ins_Trans['diskon']				= $rows_Trans->diskon;						
						$Ins_Trans['total_harga']			= $rows_Trans->total_harga;
						$Ins_Trans['supplier_id']			= $rows_Trans->supplier_id;
						$Ins_Trans['supplier_name']			= $rows_Trans->supplier_name;
						$Ins_Trans['letter_order_id']		= $rows_Trans->letter_order_id;
						$Ins_Trans['letter_order_detail_id']= $rows_Trans->letter_order_detail_id;
						$Ins_Trans['no_so']					= $rows_Trans->no_so;
						$Ins_Trans['tgl_so']				= $rows_Trans->tgl_so;
						$Ins_Trans['address_so']			= $rows_Trans->address_so;
						$Ins_Trans['pic_so']				= $rows_Trans->pic_so;
						$Ins_Trans['phone_so']				= $rows_Trans->phone_so;
						$Ins_Trans['so_descr']				= $rows_Trans->so_descr;
						$Ins_Trans['get_tool']				= $rows_Trans->get_tool;
						$Ins_Trans['schedule_id']			= $Code_Schedule;
						$Ins_Trans['schedule_nomor']		= $Nomor_Schedule;
						$Ins_Trans['schedule_date']			= $Schedule_Date;
						$Ins_Trans['notes']					= $Notes;
						$Ins_Trans['schedule_detail_id']	= $Code_SchedDet;
						$Ins_Trans['labs']					= $Labs;
						$Ins_Trans['insitu']				= $Insitu;
						$Ins_Trans['subcon']				= $Subcon;
						$Ins_Trans['plan_pick_date']		= $Pickup_Date;
						$Ins_Trans['plan_process_date']		= $Cals_Date;
						$Ins_Trans['plan_delivery_date']	= $Send_Date;
						$Ins_Trans['plan_subcon_pick_date']	= $Subcon_Pickup;
						$Ins_Trans['plan_subcon_send_date']	= $Subcon_Send;
						$Ins_Trans['plan_time_start']		= $Time_Start;
						$Ins_Trans['plan_time_end']			= $Time_End;
						$Ins_Trans['teknisi_id']			= $Code_Teknisi;
						$Ins_Trans['teknisi_name']			= $Name_Teknisi;
						$Ins_Trans['sts_split']				= $Flag_Split;
						$Ins_Trans['re_schedule']			= 'Y';
						
						if($Insitu == 'N'){
							
							
							$Ins_Trans['spk_pick_driver_id']		= $rows_Trans->spk_pick_driver_id;
							$Ins_Trans['spk_pick_driver_detail_id']	= $rows_Trans->spk_pick_driver_detail_id;
							$Ins_Trans['spk_pick_driver_nomor']		= $rows_Trans->spk_pick_driver_nomor;
							$Ins_Trans['spk_pick_driver_date']		= $rows_Trans->spk_pick_driver_date;
							$Ins_Trans['pick_driver_id']			= $rows_Trans->pick_driver_id;
							$Ins_Trans['pick_driver_name']			= $rows_Trans->pick_driver_name;
							$Ins_Trans['flag_cust_pick']			= 'Y';
							$Ins_Trans['qty_rec']					= $Qty_Process;
							$Ins_Trans['location']					= 'Warehouse';
							$Ins_Trans['receiving']					= 'Y';
							$Ins_Trans['receiving_date']			= $rows_Trans->receiving_date;
							$Ins_Trans['receiving_by']				= $rows_Trans->receiving_by;
							$Ins_Trans['bast_rec_id']				= $rows_Trans->bast_rec_id;
							$Ins_Trans['bast_rec_no']				= $rows_Trans->bast_rec_no;
							$Ins_Trans['bast_rec_date']				= $rows_Trans->bast_rec_date;
							$Ins_Trans['bast_rec_by']				= $rows_Trans->bast_rec_by;							
						}						
						$Has_Ins_Trans	= $this->db->insert('trans_details',$Ins_Trans);
						if($Has_Ins_Trans !== TRUE){
							$Pesan_Error	= 'Error Insert Trans Detail';
						}
						
						$Arr_SentralTool	= array();
						## AMBIL TRANS DATA DETAIL ##
						$Qry_Find_SentralTool	= "SELECT
														*
													FROM
														trans_data_details
													WHERE
														trans_detail_id = '".$Code_Detail."'
													AND flag_proses <> 'Y'
													AND plan_reschedule = 'Y'
													LIMIT ".$Qty_Process;
						$rows_Find_SentralTool	= $this->db->query($Qry_Find_SentralTool)->result();
						if($rows_Find_SentralTool){
							$intSentral = 0;
							foreach($rows_Find_SentralTool as $keySentral=>$valSentral){
								$intSentral++;
								$Code_TransDet					= $valSentral->id;
								$Code_Sentral					= $valSentral->sentral_code_tool;
								$Arr_SentralTool[$intSentral]	= $Code_Sentral;
								
								$Upd_Trans_DataDet		= "UPDATE trans_data_details SET flag_proses = 'N', keterangan = 'Item belum selesai dikalibrasi sehingga dijadwalkan ulang' WHERE id = '".$Code_TransDet."'";
								$Has_Upd_Trans_DataDet	= $this->db->query($Upd_Trans_DataDet);
								if($Has_Upd_Trans_DataDet !== TRUE){
									$Pesan_Error	= 'Error Update Trans Data Detail - OLD';
								}
							}
							
							unset($rows_Find_SentralTool);
							
						}
						for($x=1;$x<=$Qty_Process;$x++){
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
							$Ins_Trans_Detail['quotation_detail_id']= $Code_QuotDet;
							$Ins_Trans_Detail['tool_id']			= $Code_Tool;
							$Ins_Trans_Detail['tool_name']			= $Name_Tool;
							$Ins_Trans_Detail['no_identifikasi']	= $No_Identik;
							$Ins_Trans_Detail['merk']				= $Merk;
							$Ins_Trans_Detail['tool_type']			= $Type_Tool;
							$Ins_Trans_Detail['no_serial_number']	= $Serial_Number;
							$Ins_Trans_Detail['sentral_code_tool']	= $Code_ToolSentral;
							
							$Has_Ins_TransDet	= $this->db->insert('trans_data_details',$Ins_Trans_Detail);
							if($Has_Ins_TransDet !== TRUE){
								$Pesan_Error	= 'Error Insert Trans Data Detail - NEW';
							}
						}
						
					}
				}
				
				
				
				
				if ($this->db->trans_status() != TRUE || !empty($Pesan_Error)){
					$this->db->trans_rollback();
					$rows_Return		= array(
						'status'		=> 2,
						'pesan'			=> 'Reschedule Incomplete Calibration Process  Failed, '.$Pesan_Error
					);
					history('Reschedule Incomplete Calibration '.$Nomor_Schedule.' - '.$Pesan_Error);
				}else{
					$this->db->trans_commit();
					$rows_Return		= array(
						'status'		=> 1,
						'pesan'			=> 'Reschedule Incomplete Calibration success. Thank you & have a nice day......'
					);
					history('Reschedule Incomplete Calibration '.$Nomor_Schedule.' - Success ');
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