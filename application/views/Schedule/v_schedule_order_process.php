
<form action="#" method="POST" id="form_proses_schedule" enctype="multipart/form-data">
	<div class="box box-warning">
		<?php
		if(empty($rows_detail)){
			echo"
			<div class='box-body'>
				<div class='row'>
					<div class='col-sm-12'>
						<h4 class='text-red'><b>NO RECORD WAS FOUND.....</b></h4>
					</div>
				</div>
			</div>
				";
		}else{
			
			
		?>
		<div class="box-body">			
			<div class="row">
				<div class="col-sm-12 col-xs-12 text-center sub-heading" style="color:white;">
					<h5>DETAIL SCHEDULE</h5>
				</div>				
			</div>
			<div class='row'>
				<div class="col-sm-6">
					<div class="form-group">
						<label class="control-label">Technician <span class="text-red"> *</span></label>
						
						<?php
							echo'<select name="jadwal_teknisi" id="jadwal_teknisi" class="form-control select2">
									<option value="">- Choose Technician -</option>';
								if($rows_option){
									 foreach($rows_option as $keyC=>$valC){
										echo'<option value="'.$keyC.'">'.$valC.'</option>';
									}
								}
							echo'</select>';
							echo form_input(array('id'=>'jadwal_leadtime','name'=>'jadwal_leadtime','type'=>'hidden'),$rows_tool->cycle_time);
							echo form_input(array('id'=>'jadwal_satuan','name'=>'jadwal_satuan','type'=>'hidden'),$rows_tool->time_tipe);
							echo form_input(array('id'=>'jadwal_alat','name'=>'jadwal_alat','type'=>'hidden'),$rows_tool->name);
							echo form_input(array('id'=>'code_urutdetail','name'=>'code_urutdetail','type'=>'hidden'),$code_urutdetail);
						?>
					</div>
				</div>
				<div class="col-sm-6">
					<div class="form-group">
						<label class="control-label">Calibration Date <span class="text-red"> *</span></label>
						<?php
							echo form_input(array('id'=>'jadwal_tanggal','name'=>'jadwal_tanggal','class'=>'form-control date_kalibrasi','readOnly'=>true));						
						?>
					</div>
				</div>				
			</div>
			
			<div class='row'>
				<div class="col-sm-3">
					<div class="form-group">
						<label class="control-label">Calibration Time <span class="text-red"> *</span></label>
						<?php
							echo'<select name="jadwal_time_awal" id="jadwal_time_awal" class="form-control select2">
									<option value="">- Empty List -</option>
								</select>';
													
						?>
					</div>
				</div>
				<div class="col-sm-3">
					<div class="form-group">
						<label class="control-label">&nbsp;</label>
						<?php
							echo form_input(array('id'=>'jadwal_time_akhir','name'=>'jadwal_time_akhir','class'=>'form-control','readOnly'=>true));
							echo form_input(array('id'=>'jadwal_cek_data','name'=>'jadwal_cek_data','type'=>'hidden'));	
						?>
					</div>
				</div>
				<div class="col-sm-6">
					<div class="form-group" id="tempuh_det">
						<label class="control-label">Trip Duration (Minutes) <span class="text-red"> *</span></label>
						<?php
							echo form_input(array('id'=>'jadwal_waktu_tempuh','name'=>'jadwal_waktu_tempuh','class'=>'form-control input-sm'),0);						
						?>
					</div>
				</div>				
			</div>
		</div>	
		<?php
			
				
		echo'
		<div class="box-body">
			<div class="row col-md-2 col-md-offset-5" id="loader_proses_save_calibration">
				<div class="loader">
					<span></span>
					<span></span>
					<span></span>
					<span></span>
				</div>
			</div>
		</div>
		<div class="box-footer text-center">			
			<button type="button" id="btn_simpan_schedule" class="btn btn-md text-center" style="background-color:#37474f; color:white;vertical-align:middle !important;"> SAVE CALIBRATION SCHEDULE <i class="fa fa-long-arrow-right" style="width:40px;"></i> </button>
		</div>';
			
		}
		?>
	</div>
</form>
<div class="box box-primary">
	<div class="box-header">
		<h3 class="box-title">
			<i class="fa fa-plus"></i> <?php echo('<span class="important">Set Jadwal</span>'); ?>
		</h3>
	</div>	
    <div id="calendar_jadwal" style="overflow-x:scroll !important;">
	
	</div>	
</div>
<link rel="stylesheet" href="<?php echo base_url('assets/plugins/fullcalendar/lib/fullcalendar.min.css') ?>">
<style>
	/* LOADER */
	.loader span{
	  display: inline-block;
	  width: 12px;
	  height: 12px;
	  border-radius: 100%;
	  background-color: #3498db;
	  margin: 35px 5px;
	  opacity: 0;
	}

	.loader span:nth-child(1){
		background: #4285F4;
	  	animation: opacitychange 1s ease-in-out infinite;
	}

	.loader span:nth-child(2){
  		background: #DB4437;
	 	animation: opacitychange 1s ease-in-out 0.11s infinite;
	}

	.loader span:nth-child(3){
  		background: #F4B400;
	  	animation: opacitychange 1s ease-in-out 0.22s infinite;
	}
	.loader span:nth-child(4){
  		background: #0F9D58;
	  	animation: opacitychange 1s ease-in-out 0.44s infinite;
	}
	@keyframes opacitychange{
	  0%, 100%{
		opacity: 0;
	  }

	  60%{
		opacity: 1;
	  }
	}
	
	.text-up{
		text-transform : uppercase !important;
	}
		
	.sub-heading{
		border-radius :5px;
		background-color :#03506F;
		color : white;
		margin : 20px 10px 15px 10px !important;
		width :98% !important;
	}
	
	.text-center{
		text-align : center !important;
		vertical-align	: middle !important;
	}
	#kalendar {
		max-width: 100%;
		margin: 50px auto;
	}
	
	.fc-sat, .fc-sun{
		color:red;
	}
	.fc-day-grid-event {
		cursor: pointer;
		text-align:left !important;
	}
	.fc-today {
		background:#EAEAEA !important;
		color: blue;	
		font-weight:bold;
	}
	.fc-more-cell{
		color:blue !important;
		font-weight:bold;
	}
	.popover-header{
		font-weight: bold;
	}
	.nav-item .active{
		font-weight:bold !important;
		color:#FFF !important;
		background:#035CA8 !important;
	}
	.v_cursor{
		cursor:pointer;
	}
	.moreBorder{
	  border: 3px solid #000000 !important;
	}
	.blink_text {
	  animation: blinker 1s linear infinite;
	}

	@keyframes blinker {
	  50% {
		opacity: 0;
	  }
	}
	
	.fc table{
		table-layout: auto !important;
	}
	.fc-view > table{  
		min-width: 0;
		width: auto;
	}
	.fc-axis{
		min-width:100px; /*the width of times column*/
		width:100px; /*the width of times column*/
	}
	.fc-day,.fc-resource-cell,.fc-content-col{
		min-width:300px;
		width:300px;
	}
	
</style>


<script src="<?php echo base_url('assets/plugins/fullcalendar/lib/moment.min.js');?>"></script>
<script src="<?php echo base_url('assets/plugins/fullcalendar/lib/fullcalendar.min.js');?>"></script>
<script src="<?php echo base_url('assets/plugins/fullcalendar/scheduler.min.js');?>"></script>
<script src="<?php echo base_url()?>/assets/plugins/moment/locale/id.js"></script>

<script>
	var base_url			= '<?php echo site_url(); ?>';
	var active_controller	= '<?php echo($this->uri->segment(1)); ?>';
	var kodes				='<?php echo $code_quotdetail;?>';
	var uruts				='<?php echo $code_urutdetail;?>';
	/*
	var rows_teknisi		= <?php echo preg_replace('/"([a-zA-Z]+[a-zA-Z0-9]*)":/','$1:',json_encode($rows_teknisi));?>;
	var rows_calendar		= <?php echo preg_replace('/"([a-zA-Z]+[a-zA-Z0-9]*)":/','$1:',json_encode($rows_calendar));?>;
	*/
	var kode_proses			= $('#kode_proses').val();
	var Insitu				= $('#insitu_'+uruts).val();
	var qty_kalibrasi		= $('#qty_'+uruts).val();
	var kode_split			= $('#sts_split_'+uruts).val();
	var urut_id				= $('#urut_id_'+uruts).val();
	var arrDay 				= ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
	var arrMonth 			= ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September' , 'Oktober', 'November', 'Desember'];
	var kategori			= <?php echo preg_replace('/"([a-zA-Z]+[a-zA-Z0-9]*)":/','$1:', json_encode($rows_teknisi));?>;
	
	const calendar_service =()=>{
		
		console.log(kategori);
		let tglServisNew	= '<?php echo date('Y-m-d');?>';
		
		let calendar = $('#calendar_jadwal').fullCalendar({
			defaultDate: tglServisNew,
			editable:false, 
			header: {
				left: 'prev,next today',
				center: 'title',
				right: 'month,agendaDay,listMonth'
			},
			height: 730,
			firstDay: 1,
			defaultView: 'agendaDay',
			eventLimit: true,
			events: {
				url : base_url+'/'+active_controller+'/display_data_calendar',
				type:'POST',
				data:{}
			},
			eventRender: function (event, element) {
				var sts 	= event.status;
				var desc 	= event.description;
				
				$(element).attr("data-original-title", sts);
				$(element).attr("data-content", desc);
				$(element).attr("id", "popoverData");
				$(element).attr("rel", "popover");
				$(element).attr("data-placement", "top");
				$(element).popover({ trigger: "hover", container : "body"});
			
			},
			
			slotLabelFormat:"HH:mm",
			timeFormat: 'H:mm',
			resources: kategori,
			resourceRender: function(resourceObj, labelTds, bodyTds) {
				labelTds.empty();
				labelTds.css('background', '#E9ECEF');
				/*if(resourceObj.id == '1'){
					labelTds.css('background', resourceObj.color);
				}*/
				labelTds.append(
					'<div class="text-center pt-1">' +
					'<img src="<?php echo base_url()?>/assets/img/avatar.png" width="80" height="50"><br>' +resourceObj.title+
					'</div>'
				);
			},
			dayNamesShort: arrDay,
			dayNames: arrDay,
			monthNames : arrMonth,
			schedulerLicenseKey: 'GPL-My-Project-Is-Open-Source'
		});
	}
	
	$(document).ready(function(){
		calendar_service();
		$('#loader_proses_save_calibration').hide();
		$('#tempuh_det').hide();
		$('#jadwal_teknisi').chosen();
		
		$('#waktu_tempuh_'+uruts).mask('?99');
		if(Insitu == 'Y'){
			$('#tempuh_det').show();
		}
		$('.date_kalibrasi').datepicker({			
			dateFormat: 'yy-mm-dd',
			changeMonth:true,
			changeYear:true,
			minDate:'+0d',
			onSelect: function(dateText, inst) {
				let date 	= $(this).val();
				let member	= $('#jadwal_teknisi').val();
				
				if(member !='' && date !=''){
					let revisi	= $('#revisi').val();
					$.post(base_url +'/'+ active_controller+'/GetTimeCalibrations',{'datet':date,'teknisi':member,'code':kodes,'revisi':revisi}, function(response) {
						const datas	= $.parseJSON(response);
						console.log(datas.mulai);
						let Template_New	= '<option value=""> - Select An Option - </option>';
						$.each(datas.mulai,function(kunci,nilai){
							Template_New	+='<option value="'+nilai+'">'+nilai+'</option>';
						});
						
						let beda = '';
						let awal = 0;
						$.each(datas.selesai,function(kunci,nilai){
							awal++;
							if(awal > 1){
								beda	+=',';
							}
							beda	+=nilai;
							
						});
						
						$('#jadwal_time_awal').html(Template_New).trigger('chosen:updated');
						$('#jadwal_cek_data').val(beda);
						$('#jadwal_time_awal').chosen();
					});
					
					
				}else{
					let Template_New	= '<option value=""> - Empty List - </option>';
					$('#jadwal_time_awal').html(Template_New).trigger('chosen:updated');
					$('#jadwal_cek_data').val('');
					$('#jadwal_time_awal').chosen();
				}
				
			}
		});
		
		
		
		
	});
	
	$(document).on('change','#jadwal_time_awal',()=>{
		let jam_awal	= $('#jadwal_time_awal').val();
			
		if(jam_awal !='' && jam_awal != null){
			//var qty		= $('#JadwalQty'+kodes).val();
			let cekData		= $('#jadwal_cek_data').val();
			let estimasi	= $('#jadwal_leadtime').val();
			let satuan		= $('#jadwal_satuan').val();
			let alat		= $('#jadwal_alat').val();
			//console.log(cekData);
			if(estimasi=='' || estimasi==null){				
				GeneralShowMessageError('error','Unsetup Cycle Time '+alat+'. Please Setup Cycle Time First');
				return false;
			}
			
			let pengali	=0;
			if(satuan=='minute'){
				pengali	=1;
			}else if(satuan=='second'){
				pengali	=0.02;
			}else if(satuan=='hour'){
				pengali	=60;
			}
			let total_waktu	= parseFloat(pengali) * parseFloat(qty_kalibrasi) * parseFloat(estimasi);
			let jam			= parseInt(total_waktu /60);
			let menit		= parseInt(total_waktu) - (jam * 60);
			 
			let Jam_Cek		= jam_awal.split(':');
			
			let minutes		= parseInt(Jam_Cek[1]) + parseInt(menit);
			let beda_menit	= parseInt(minutes).length;
			let cek_menit	= minutes.toString().slice(1,2);
			if(beda_menit ==1){
				cek_menit		= parseInt(minutes);
			}
			let tambah_menit	=0;
			if(parseInt(cek_menit) > 0 && parseInt(cek_menit) < 5){
				tambah_menit	= 5 - parseInt(cek_menit);
			}else if(parseInt(cek_menit) > 5){
				tambah_menit	= 10 - parseInt(cek_menit);
			}
			minutes				= parseInt(minutes) + parseInt(tambah_menit);
			let hours			= parseInt(Jam_Cek[0]) + parseInt(jam);
			if(minutes > 59){
				 hours			= parseInt(Jam_Cek[0]) + parseInt(jam) + 1;
				 minutes			= parseInt(minutes) - 60;
			 }
			 
			 if(hours >= 24){
				hours	=23;
			 }
			 let h				= (hours < 10 )?('0'+hours):hours;
			 let m				= (minutes < 10 )?('0'+minutes):minutes;
			
			 let jam_akhir		=h+':'+m;
			 let cek_ada		=0;
			 if(cekData.indexOf(',') > 0){
				var comp	= cekData.split(',');
				//console.log(comp);
				$.each(comp,function(kode,nilai){
					if(nilai==jam_akhir){
						cek_ada=1;
					}
				});
			 }else{
				if(cekData==jam_akhir){
					cek_ada=1;
				}
			 }
			 if(cek_ada==1){
				$('#jadwal_time_akhir').val(jam_akhir);
			 }else{
				
				GeneralShowMessageError('error','Calibration Time  At '+jam_awal+' - '+jam_akhir+' Already Used. Please Set Other Time.........');
				return false;
			 }
		}else{
			$('#jadwal_cek_data').val('');
		}
	});
	
	
	
</script>