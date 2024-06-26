<?php
$this->load->view('include/side_menu');
?> 
<form action="#" method="POST" id="form_proses_order" enctype="multipart/form-data">
	<div class="box box-warning">
		
		<div class="box-body">
			<div class="row">
				<div class="col-sm-12 col-xs-12 text-center sub-heading" style="color:white;">
					<h5><?php echo $title;?></h5>
				</div>
				
			</div>
			<?php
			if(empty($rows_detail)){
				echo"<div class='row'>
						<div class='col-sm-12'>
							<h4 class='text-red'><b>NO RECORD WAS FOUND.....</b></h4>
						</div>
					</div>";
			}else{
				
				
			?>
				
				
				<div class='row'>
					<div class="col-sm-6">
						<div class="form-group">
							<label class="control-label">Schedule No</label>
							<div>
								
								<?php
									echo form_input(array('id'=>'nomor','name'=>'nomor','class'=>'form-control input-sm','readOnly'=>true),$nomor_rev);
									echo form_input(array('id'=>'letter_order_id','name'=>'letter_order_id','type'=>'hidden'),$rows_header->letter_order_id);
									echo form_input(array('id'=>'kode_proses_new','name'=>'kode_proses_new','type'=>'hidden'),$kode_proses);
									echo form_input(array('id'=>'kode_proses','name'=>'kode_proses','type'=>'hidden'),$rows_header->kode_proses);
									echo form_input(array('id'=>'revisi','name'=>'revisi','type'=>'hidden'),$urut_rev);
									echo form_input(array('id'=>'customer_id','name'=>'customer_id','type'=>'hidden'),$rows_header->customer_id);
									echo form_input(array('id'=>'quotation_id','name'=>'quotation_id','type'=>'hidden'),$rows_header->quotation_id);
									echo form_input(array('id'=>'old_id','name'=>'old_id','type'=>'hidden'),$code_old);
									echo form_input(array('id'=>'prev_id','name'=>'prev_id','type'=>'hidden'),$rows_header->id);
								?>
							</div>
						</div>
					</div>
					<div class="col-sm-6">
						<div class="form-group">
							<label class="control-label">Schedule Date</label>
							<?php
								echo form_input(array('id'=>'datet','name'=>'datet','class'=>'form-control input-sm','readOnly'=>true),date('d-m-Y'));						
							?>
						</div>
					</div>				
				</div>
				
				<div class='row'>
					<div class="col-sm-6">
						<div class="form-group">
							<label class="control-label">SO No <span class="text-red"> *</span></label>
							<div>
							<?php								
								echo form_input(array('id'=>'no_so','name'=>'no_so','class'=>'form-control input-sm','readOnly'=>true),$rows_letter->no_so);	
								
							?>
							</div>
						</div>
					</div>
					<div class="col-sm-6">
						<div class="form-group">
							<label class="control-label">Quotation <span class="text-red"> *</span></label>
							<?php
								echo form_input(array('id'=>'quotation_nomor','name'=>'quotation_nomor','class'=>'form-control input-sm','readOnly'=>true),$rows_quot->nomor);						
							?>
						</div>
					</div>				
				</div>
				
				<div class='row'>
					<div class="col-sm-6">
						<div class="form-group">
							<label class="control-label">Customer <span class="text-red"> *</span></label>
							<?php
								echo form_input(array('id'=>'customer_name','name'=>'customer_name','class'=>'form-control input-sm','readOnly'=>true),$rows_header->customer_name);
														
							?>
						</div>
					</div>
					<div class="col-sm-6">
						<div class="form-group">
							<label class="control-label">Address <span class="text-red"> *</span></label>
							<?php
								echo form_textarea(array('id'=>'address','name'=>'address','class'=>'form-control input-sm text-up','cols'=>75,'rows'=>2,'readOnly'=>true),$rows_quot->address);						
							?>
						</div>
					</div>				
				</div>
				<div class='row'>
					<div class="col-sm-6">
						<div class="form-group">
							<label class="control-label">PIC Name <span class="text-red"> *</span></label>
							<div>
							<?php								
								echo form_input(array('id'=>'pic_name','name'=>'pic_name','class'=>'form-control input-sm','readOnly'=>true),$rows_letter->pic);	
								
							?>
							</div>
						</div>
					</div>
					<div class="col-sm-6">
						<div class="form-group">
							<label class="control-label">Notes</label>
							<?php
								echo form_textarea(array('id'=>'notes','name'=>'notes','class'=>'form-control input-sm text-up','cols'=>75,'rows'=>2),$rows_header->notes);					
							?>
						</div>
					</div>				
				</div>
				
				<div class="row">
					<div class="col-sm-12 col-xs-12 text-center sub-heading" style="color:white;">
						<h5>DETAIL TOOL </h5>
					</div>
					
				</div>
				<div class="row">
					<div class="col-sm-12" style="overflow-x:scroll !important;">
						<table class="table table-striped table-bordered" id="my-grid">
							<thead>
								<tr class="bg-navy-active">
									<th class="text-center" rowspan='2'>Cust Tool</th>
									<th class="text-center" rowspan='2'>Sentral Tool</th>
									<th class="text-center" rowspan='2'>Qty</th>
									<th class="text-center" rowspan='2'>Insitu</th>
									<th class="text-center" rowspan='2'>Subcon</th>
									<th class="text-center" rowspan='2'>Labs</th>
									<th class="text-center" colspan='3'>Labs/Insitu Process</th>
									<th class="text-center" colspan='3'>Subcon Process</th>
									<th class="text-center" rowspan='2'>Description</th>	
									<th class="text-center" rowspan='2'>Action</th>									
								</tr>
								<tr class="bg-navy-active">
									<th class="text-center">Send<br>To<br>Customer</th>
									<th class="text-center">Process</th>
									<th class="text-center">Pickup<br>From<br>Customer</th>
									<th class="text-center">Pickup<br>From<br>Subcon</th>
									<th class="text-center">Process</th>
									<th class="text-center" style="border-right-width: thin !important;">Send<br>To<br>Subcon</th>								
								</tr>
							</thead>
							<tbody id="list_detail">
								<?php
								if($rows_detail){
									$intL	= 0;
									foreach($rows_detail as $ketD=>$valD){
									
										$Code_Detail	= $valD->id;
										$Code_DetQuot	= $valD->quotation_detail_id;
										$Code_Alat		= $valD->tool_id;
										$Nama_Alat		= $valD->tool_name;
										$Qty			= $valD->qty_reschedule;
										$subcon			= $valD->subcon;
										$labs			= $valD->labs;
										$insitu			= $valD->insitu;
										
										
										
										$Def_CodeSupp	= $valD->supplier_id;
										$Def_NameSupp	= $valD->supplier_name;
										
										$Def_CodeSupp	= '';
										$Def_NameSupp	= '';
										
										$Tool_Name		= $Cust_Alat = $Nama_Alat;
										$rows_QuotDet	= $this->db->get_where('quotation_details',array('id'=>$Code_DetQuot))->row();
										if($rows_QuotDet){
											$Tool_Name	= $rows_QuotDet->tool_name;
											$Cust_Alat	= $rows_QuotDet->cust_tool;
										}
										
										
										$kode_subcon	= $subcon;
										if(strtolower($insitu)=='y'){
											if($Def_CodeSupp !='COMP-001'){
												$kode_subcon	='Y';
											}																		
										}
										
										$Flag_Split		= 'N';
										$Waktu_Tempuh	= 0;
										$Urut_ID		= 0;
										
										$Pickup_Date	= $Cals_Date	= $Send_Date = $Subcon_Pickup = $Subcon_Send = $Time_Start = $Time_End = '-';
										$Code_Teknisi 	= $Name_Teknisi = '';
										
										if(isset($valD->plan_pick_date) && !empty($valD->plan_pick_date)){
											$Pickup_Date	= $valD->plan_pick_date;
										}
										
										if(isset($valD->plan_process_date) && !empty($valD->plan_process_date)){
											$Cals_Date	= $valD->plan_process_date;
										}
										
										if(isset($valD->plan_delivery_date) && !empty($valD->plan_delivery_date)){
											$Send_Date	= $valD->plan_delivery_date;
										}
										
										if(isset($valD->plan_subcon_pick_date) && !empty($valD->plan_subcon_pick_date)){
											$Subcon_Pickup	= $valD->plan_subcon_pick_date;
										}
										
										if(isset($valD->plan_subcon_send_date) && !empty($valD->plan_subcon_send_date)){
											$Subcon_Send	= $valD->plan_subcon_send_date;
										}
										
										$Time_Start			= substr($valD->plan_time_start,0,5);
										$Time_End			= substr($valD->plan_time_end,0,5);
										$Code_Teknisi		= $valD->teknisi_id;
										$Name_Teknisi		= $valD->teknisi_name;
										
										$Keterangan			= '';
										$Query_Keterangan	= "
																SELECT
																	GROUP_CONCAT(DISTINCT keterangan) AS ket_reschedule
																FROM
																	trans_data_details
																WHERE
																	trans_detail_id = '".$Code_Detail."'
																AND plan_reschedule ='Y'
																AND flag_proses = 'N'
																AND NOT (
																	keterangan IS NULL
																	OR keterangan = ''
																	OR keterangan = '-'
																)";
										$rows_Keterangan	= $this->db->query($Query_Keterangan)->row();
										if($rows_Keterangan){
											$Keterangan		= $rows_Keterangan->ket_reschedule;
										}
										
										$Qty_Outs		= $Qty;
										if($Qty_Outs > 0){											
											$intL++;
											echo'<tr id="tr_urut_'.$intL.'">';
												echo form_input(array('id'=>'quotation_detail_id_'.$intL,'name'=>'detDetail['.$intL.'][quotation_detail_id]','type'=>'hidden'),$Code_DetQuot);
												echo form_input(array('id'=>'code_detail_'.$intL,'name'=>'detDetail['.$intL.'][code_detail]','type'=>'hidden'),$Code_Detail);
												echo form_input(array('id'=>'tool_id_'.$intL,'name'=>'detDetail['.$intL.'][tool_id]','type'=>'hidden'),$Code_Alat);
												echo form_input(array('id'=>'tool_name_'.$intL,'name'=>'detDetail['.$intL.'][tool_name]','type'=>'hidden'),$Nama_Alat);
												echo form_input(array('id'=>'tool_cust_'.$intL,'name'=>'detDetail['.$intL.'][tool_cust]','type'=>'hidden'),$Cust_Alat);
												echo form_input(array('id'=>'labs_'.$intL,'name'=>'detDetail['.$intL.'][labs]','type'=>'hidden'),$labs);
												echo form_input(array('id'=>'insitu_'.$intL,'name'=>'detDetail['.$intL.'][insitu]','type'=>'hidden'),$insitu);
												echo form_input(array('id'=>'subcon_'.$intL,'name'=>'detDetail['.$intL.'][subcon]','type'=>'hidden'),$subcon);
												echo form_input(array('id'=>'qty_process_'.$intL,'name'=>'detDetail['.$intL.'][qty_process]','type'=>'hidden'),$Qty_Outs);
												echo form_input(array('id'=>'sts_split_'.$intL,'name'=>'detDetail['.$intL.'][sts_split]','type'=>'hidden'),$Flag_Split);
												echo form_input(array('id'=>'waktu_tempuh_'.$intL,'name'=>'detDetail['.$intL.'][waktu_tempuh]','type'=>'hidden'),$Waktu_Tempuh);
												echo form_input(array('id'=>'urut_id_'.$intL,'name'=>'detDetail['.$intL.'][urut_id]','type'=>'hidden', 'class'=>$Code_DetQuot),$Urut_ID);
												echo form_input(array('id'=>'supplier_id_'.$intL,'name'=>'detDetail['.$intL.'][supplier_id]','type'=>'hidden'),$Def_CodeSupp);
												echo form_input(array('id'=>'keterangan_'.$intL,'name'=>'detDetail['.$intL.'][keterangan]','type'=>'hidden'),$Keterangan);
												
												
												echo'											
												<td class="text-left text-wrap">'.$Cust_Alat.'</td>
												<td class="text-left text-wrap">'.$Tool_Name.'</td>
												<td class="text-center">'.form_input(array('id'=>'qty_'.$intL,'name'=>'detDetail['.$intL.'][qty]','class'=>'form-control','readOnly'=>true),$Qty_Outs).'</td>
												<td class="text-center">'.$insitu.'</td>
												<td class="text-center">'.$kode_subcon.'</td>
												<td class="text-center">'.$labs.'</td>
												<td class="text-center">';
													if($labs=='Y' || $subcon=='Y'){
														echo form_input(array('id'=>'delivery_date_'.$intL,'name'=>'detDetail['.$intL.'][delivery_date]','class'=>'form-control tanggal','readOnly'=>true),$Send_Date);
													}else{
														echo $Send_Date;
													}
												echo'
												</td>
												<td class="text-center" id="proses_'.$intL.'">';
													if($subcon == 'N'){
														echo $Cals_Date.' '.$Time_Start.' - '.$Time_End.' ('.$Name_Teknisi.')';
														echo form_input(array('id'=>'process_date_'.$intL,'name'=>'detDetail['.$intL.'][process_date]','type'=>'hidden'),$Cals_Date);
														echo form_input(array('id'=>'member_id_'.$intL,'name'=>'detDetail['.$intL.'][member_id]','type'=>'hidden'),$Code_Teknisi);
														echo form_input(array('id'=>'member_name_'.$intL,'name'=>'detDetail['.$intL.'][member_name]','type'=>'hidden'),$Name_Teknisi);
														echo form_input(array('id'=>'jam_awal_'.$intL,'name'=>'detDetail['.$intL.'][jam_awal]','type'=>'hidden'),$Time_Start);
														echo form_input(array('id'=>'jam_akhir'.$intL,'name'=>'detDetail['.$intL.'][jam_akhir]','type'=>'hidden'),$Time_End);
													}
												echo'
												</td>
												<td class="text-center">';
													if($labs=='Y' || $subcon=='Y'){
														
														echo form_input(array('id'=>'pick_date_'.$intL,'name'=>'detDetail['.$intL.'][pick_date]','class'=>'form-control tanggal','readOnly'=>true),$Pickup_Date);
													}else{
														echo $Pickup_Date;
													}
												echo'
												</td>
												<td class="text-center">';
													if($subcon=='Y' && $insitu=='N'){
														echo form_input(array('id'=>'subcon_pick_date_'.$intL,'name'=>'detDetail['.$intL.'][subcon_pick_date]','class'=>'form-control tanggal','readOnly'=>true),$Subcon_Pickup);
													}else{
														echo $Subcon_Pickup;
													}
												echo'
												</td>
												<td class="text-center">';
													if($subcon=='Y'){
														echo form_input(array('id'=>'process_date_'.$intL,'name'=>'detDetail['.$intL.'][process_date]','class'=>'form-control tanggal','readOnly'=>true),$Cals_Date);
													}else{
														echo '-';
													}
												echo'
												</td>
												<td class="text-center">';
													if($subcon=='Y' && $insitu=='N'){
														echo form_input(array('id'=>'subcon_send_date_'.$intL,'name'=>'detDetail['.$intL.'][subcon_send_date]','class'=>'form-control tanggal','readOnly'=>true),$Subcon_Send);
													}else{
														echo $Subcon_Send;
													}
												echo'
												</td>
												<td class="text-left text-wrap">'.$Keterangan.'</td>
												<td class="text-center">';
													if($subcon == 'N'){
														echo"<div class='btn-group' id='btn_jadwal_".$intL."'>";
															echo"<button type='button' id='btn-jadwal' class='btn btn-sm btn-success' onClick='return viewJadwal(".$intL.");' data-role='qtip' title='SET DATE CALIBRATION'><i class='fa fa-calendar'></i></button>";
														echo"</div>";
														
														
														if($Qty_Outs > 1){
															echo"<div class='btn-group' id='btn_split_".$intL."'>";
																echo"<button type='button' id='btn-split' class='btn btn-sm btn-warning' onClick='return splitData(".$intL.");' data-role='qtip' title='SLIT DATA'><i class='fa fa-recycle'></i></button>";
															echo"</div>";
														}
														
														
													}
													/*
													if($Flag_Split == 'N'){
														echo"<div class='btn-group' id='btn_delete_".$intL."'>";
															echo"<button type='button' id='btn-delete' class='btn btn-sm btn-danger' onClick='return DeleteData(".$intL.");' data-role='qtip' title='DELETE DATA'><i class='fa fa-trash-o'></i></button>";
														echo"</div>";
													}
													*/
													
												echo'
												</td>
																																				
											</tr>
											';
											
										}
											
										
									}
								}
								?>
							</tbody>
						</table>
					</div>
				</div>
				
			<?php
				
			}
		echo'</div>';
		
		echo"
		<div class='box-body'>
			<div class='row col-md-2 col-md-offset-5' id='loader_proses_save'>
				<div class='loader'>
					<span></span>
					<span></span>
					<span></span>
					<span></span>
				</div>
			</div>
		</div>
		<div class='box-footer text-center'>";	
			echo'
				<button type="button" class="btn btn-md btn-danger" id="btn_batal_schedule"> <i class="fa fa-long-arrow-left"></i> CANCEL SCHEDULE </button>';
		if(!empty($rows_detail)){			
				echo'
				&nbsp;&nbsp;&nbsp;<button type="button" id="btn_process_order" class="btn btn-md" style="background-color:#37474f; color:white;vertical-align:middle !important;" title="SAVE SCHEDULE"> SAVE SCHEDULE <i class="fa fa-long-arrow-right" style="width:40px;"></i> </button>';
			
		}
		echo"</div>";
		?>
		
	</div>
</form>
<div class="modal fade" id="MyModalView" tabindex="-1" role="dialog" aria-labelledby="MyModalView" data-backdrop="static">
	<div class="modal-dialog" role="document" style="min-width:70% !important;">
		 <div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title">SET CALIBRATION DATE</h5>
				<button class="close" data-dismiss="modal" aria-label="close" id="btn-modal-close">
					<span aria-hidden="true"><i class="fa fa-close"></i></span>
				</button>
			</div>
			<div class="modal-body" id="MyModalDetail">
			
			</div>
		</div>
	</div>
</div>
<div class="modal fade" id="MyModalSplit" tabindex="-1" role="dialog" aria-labelledby="MyModalSplit" data-backdrop="static">
	<div class="modal-dialog" role="document" style="min-width:70% !important;">
		 <div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title">SPLIT DATA</h5>
				<button class="close" data-dismiss="modal" aria-label="close" id="btn-modal-close2">
					<span aria-hidden="true"><i class="fa fa-close"></i></span>
				</button>
			</div>
			<div class="modal-body" id="MyModalSplitDetail">
			
			</div>
			<div class="modal-footer">
				<button type='button' id='btn_proses_split' class='btn btn-sm bg-navy-active'>SPLIT DATA</button>
			</div>
		</div>
	</div>
</div>
<?php $this->load->view('include/footer'); ?>

<style>
	.sub-heading{
		border-radius :5px;
		background-color :#03506F;
		color : white;
		margin : 20px 10px 15px 10px !important;
		width :98% !important;
	}
	.ui-spinner-input{
		padding :10px 5px 10px 10px !important;
	}
	
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
	.text-center{
		text-align : center !important;
		vertical-align	: middle !important;
	}
	.text-left{
		text-align : left !important;
		vertical-align	: middle !important;
	}
	.text-wrap{
		word-wrap : break-word !important;
	}
		.popover{
		max-width:500px;			
	}
</style>
<script>
	var base_url			= '<?php echo site_url(); ?>';
	var active_controller	= '<?php echo($this->uri->segment(1)); ?>';
	$(document).ready(function(){
		$('#loader_proses_save').hide();
		
		
		$('.tanggal').datepicker({
			dateFormat	: 'yy-mm-dd',
			changeMonth	:true,
			changeYear	:true,
			minDate		:'+0d'
		});
		
		
		$('.chosen-select').chosen();
		
	});
	
		
	
	
	const DeleteData =(Urut)=>{
		$('#list_detail #tr_urut_'+Urut).remove();
	}
	
	$(document).on('click','#btn_batal_schedule', (e)=>{
		e.preventDefault();
		$('#loader_proses_save').show();
		$('#btn_batal_schedule, #btn_process_order').prop('disabled',true);
		
		let ScheduleOrder	= $('#prev_id').val();
		let CodeProcess	= $('#kode_proses_new').val();
		
		 $.post(base_url +'/'+ active_controller+'/CancelRescheduleProcess',{'code':ScheduleOrder,'kode_proses':CodeProcess}, function(response) {
			$('#loader_proses_save').hide();
			const datas	= $.parseJSON(response);
			if(datas.status == '1'){
				GeneralShowMessageError('success',datas.pesan);
				window.location.href	= base_url +'/'+ active_controller;
			}else{
				GeneralShowMessageError('error',datas.pesan);
				$('#btn_batal_schedule, #btn_process_order').prop('disabled',false);
				return false;
			}
           
			
        });
		
		
		
	});
	
	$(document).on('click','#btn_process_order', async(e)=>{
		e.preventDefault();
		$('#btn_batal_schedule, #btn_process_order').prop('disabled',true);	
		
		let OrderCode 		= $('#letter_order_id').val();
		let ProcessCode		= $('#kode_proses_new').val();
		
		const ValueCheck	= {
			'kode_so':{'nilai':OrderCode,'error':'Empty SO No. Please choose SO No first..'}
		};
		
		let JumChecked	= $('#list_detail').find('tr').length;
		if(parseInt(JumChecked) <= 0){
			let rowsChosen		= '';
			ValueCheck['rows_pilih']	={'nilai':rowsChosen,'error':'No record was selected. Please choose at least one record..'};
			
		}
		
		let intA	= intK	= intP	= intF	=  intS	= intB1	= intB2	= 0;
		$('#list_detail').find('tr').each(function(){
			const SplitCode	= $(this).attr('id').split('_');
			let CodeUrut	= SplitCode[2];
			
			let Insitu		= $('#insitu_'+CodeUrut).val();
			let Labs		= $('#labs_'+CodeUrut).val();
			let Subcon		= $('#subcon_'+CodeUrut).val();
			
			let PickupDate	= $('#pick_date_'+CodeUrut).val();
			let SendDate	= $('#delivery_date_'+CodeUrut).val();
			let ProcessDate	= $('#process_date_'+CodeUrut).val();
			
			if(ProcessDate == '' || ProcessDate == null){
				intP++;
			}
			
			if(Insitu == 'N'){
				if(PickupDate == '' || PickupDate == null){
					intA++;
				}
				
				if(SendDate == '' || SendDate == null){
					intK++;
				}
				if(PickupDate > ProcessDate || ProcessDate > SendDate){
					intF++;
				}
			}
			
			if(Insitu == 'N' && Subcon == 'Y' ){
				let SubconSend	= $('#subcon_send_date_'+CodeUrut).val();
				var SubconPickup= $('#subcon_pick_date_'+CodeUrut).val();
				if(SubconSend == '' || SubconPickup==''){
					intS++;
				}
				if(SubconSend > SubconPickup || SubconSend > ProcessDate){
					intB1++;
				}
				
				if(SubconPickup < SubconSend || SubconPickup < ProcessDate){
					intB2++;
				}
			}			
		});
		
		if(intA > 0){
			ValueCheck['pick_date']	={'nilai':'','error':'Empty Pick Tool Date. Please Input Pick Tool Date First...'};			
		}
		
		if(intP > 0){
			ValueCheck['cal_date']	={'nilai':'','error':'Empty Calibration Date. Please Input Calibration Date First...'};	
		}
		
		if(intK > 0){
			ValueCheck['send_date']	={'nilai':'','error':'Empty Delivery Date. Please Input Delivery Date First...'};
		}
		
		if(intF >0){
			ValueCheck['schedule_date']	={'nilai':'','error':'Incorrect Schedule Date. Please Input Correct Pick Tool / Delivery / Calibration Date...'};
		}
		
		if(intS > 0){
			ValueCheck['schedule_date']	={'nilai':'','error':'Empty Subcon Schedule Pick / Send Date . Please Input Subcon Schedule Pick / Send Date First...'};
		}
		
		if(intB1 > 0){
			ValueCheck['schedule_date']	={'nilai':'','error':'Incorrect Subcon Schedule Send Date . Please Input Correct Subcon Schedule Send Date First...'};
		}
		
		if(intB2 > 0){
			ValueCheck['schedule_date']	={'nilai':'','error':'Incorrect Subcon Schedule Pick Date . Please Input Correct Subcon Schedule Pick Date First..'};
		}
		
		
		try{			
			const ResultCheck		= await GeneralCheckEmptyValue(ValueCheck);
			const ResultConfirm		= await GeneralShowConfirmSave();
			const formData 			= new FormData($('#form_proses_order')[0]);
			const ParamProcess	= {
				'action'		: 'save_reschedule_order_process',
				'parameter'		: formData,
				'loader'		: 'loader_proses_save'
			};			
			const Hasil_Bro			= await GeneralAjaxProcessData(ParamProcess);
			
			if(Hasil_Bro.status == '1'){
				GeneralShowMessageError('success',Hasil_Bro.pesan);
				window.location.href	= base_url+'/'+active_controller;
			}else{
				GeneralShowMessageError('error',Hasil_Bro.pesan);
				$('#btn_batal_schedule, #btn_process_order').prop('disabled',false);
				return false;
			}			
		}catch(error){
			GeneralShowMessageError('error',error.message);
			$('#btn_batal_schedule, #btn_process_order').prop('disabled',false);
            return false;
		}
	});
	
	
	
	function ValidateSingleInput(oInput) {
		if (oInput.type == "file") {
			var sFileName = oInput.value;
			 if (sFileName.length > 0) {
				var blnValid = false;
				for (var j = 0; j < _validFileExtensions.length; j++) {
					var sCurExtension = _validFileExtensions[j];
					if (sFileName.substr(sFileName.length - sCurExtension.length, sCurExtension.length).toLowerCase() == sCurExtension.toLowerCase()) {
						blnValid = true;
						break;
					}
				}
				 
				if (!blnValid) {
					swal({
					  title				: "Error Message !",
					  text				: 'Hanya boleh pilih jenis file IMAGES atau PDF....',						
					  type				: "warning"
					});
					
					oInput.value = "";
					return false;
				}
			}
		}
    	return true;
	}
	
	function viewJadwal(UrutJadwal){
		loading_spinner_new();		
		$("#MyModalDetail").html('');
		let Code_Quot	= $('#quotation_detail_id_'+UrutJadwal).val();
        $.post(base_url +'/'+ active_controller+'/GetScheduleCalibrations',{'code':Code_Quot,'urut':UrutJadwal}, function(response) {
			close_spinner_new();
            $("#MyModalDetail").html(response);
			
        });
		$("#MyModalView").modal('show');	
		
	}
	
	function splitData(UrutJadwal){
				
		$("#MyModalSplitDetail").html('');
		let Code_Alat	= $('#tool_id_'+UrutJadwal).val();
		let Nama_Alat	= $('#tool_name_'+UrutJadwal).val();
		let Qty_Alat	= $('#qty_'+UrutJadwal).val();
		//console.log(Code_Alat);
		if(parseInt(Qty_Alat) <= 1 || Qty_Alat == '' || Qty_Alat == null){
			
			GeneralShowMessageError('error','Incorrect Quantity. Quantity should be greater than 1');
			return false;
		}else{
			loading_spinner_new();
			let Template	= '<div class="row">'+
								'<input type="hidden" id="urut_pecah" value="'+UrutJadwal+'">'+
								'<input type="hidden" id="qty_pecah" value="'+Qty_Alat+'">'+
								'<div class="col-sm-6 col-xs-12">'+
									'<div class="form-group">'+
										'<label class="control-label">Tool Code</label>'+
										'<input type="text" class="form-control" name="code_alat_pecah" id="code_alat_pecah" value="'+Code_Alat+'" readOnly>'+
									'</div>'+
								'</div>'+
								'<div class="col-sm-6 col-xs-12">'+
									'<div class="form-group">'+
										'<label class="control-label">Tool Name</label>'+
										'<input type="text" class="form-control" name="nama_alat_pecah" id="nama_alat_pecah" value="'+Nama_Alat+'" readOnly>'+
									'</div>'+
								'</div>'+
							 '</div>'+
							 '<div class="row">'+
								'<div class="col-sm-6 col-xs-12">'+
									'<div class="form-group">'+
										'<label class="control-label">Qty Split</label>'+
										'<div><input type="text" class="form-control" name="qty_ambil" id="qty_ambil" value="'+Qty_Alat+'"></div>'+
									'</div>'+
								'</div>'+
								'<div class="col-sm-6 col-xs-12">&nbsp;</div>'+
							 '</div>';
			close_spinner_new();
            $("#MyModalSplitDetail").html(Template);
			$("#MyModalSplit").modal('show');	
			
			let nil_old		= $('#qty_pecah').val();
			
			$('#qty_ambil').spinner({					
				min: 1,
				max: parseInt(nil_old)
			 });
		}			
	}
	
	$(document).on('click','#btn_proses_split',(e)=>{
		e.preventDefault();
		$('#btn-modal-close2, #btn_proses_split').prop('disabled',true);
		loading_spinner_new();
		
		let kode_lama		= $('#urut_pecah').val();
		let qty_awal		= $('#qty_pecah').val();
		let qty_ambil		= $('#qty_ambil').val();
		let tr_urut			= $('#list_detail tr:last').attr('id');
		const kode_urut		= tr_urut.split('_');
		let next_urut		= parseInt(kode_urut[2]) + 1;
		let sisa			= parseInt(qty_awal) - parseInt(qty_ambil);
		
		
		if(qty_ambil=='' || qty_ambil==null){
			GeneralShowMessageError('error','Empty Split Qty. Please Input Split Qty First');
			close_spinner_new();
			$('#btn-modal-close2, #btn_proses_split').prop('disabled',false);
			return false;
		}
		if(parseInt(qty_ambil)==parseInt(qty_awal)){
			GeneralShowMessageError('error','Qty Should  Be Less Than Qty Item. Please Input Correct Qty');
			close_spinner_new();
			$('#btn-modal-close2, #btn_proses_split').prop('disabled',false);
			return false;
		}
		
		let kode		= $('#quotation_detail_id_'+kode_lama).val();
		let kode_detail	= $('#code_detail_'+kode_lama).val();
		let kode_alat	= $('#tool_id_'+kode_lama).val();
		let nama_alat	= $('#tool_name_'+kode_lama).val();
		let cust_alat	= $('#tool_cust_'+kode_lama).val();
		let labs		= $('#labs_'+kode_lama).val();
		let insitu		= $('#insitu_'+kode_lama).val();
		let subcon		= $('#subcon_'+kode_lama).val();
		let sts_split	= $('#sts_split_'+kode_lama).val();
		let waktu_tempuh= $('#waktu_tempuh_'+kode_lama).val();
		let supplier_id	= $('#supplier_id_'+kode_lama).val();
		let ket_detail	= $('#keterangan_'+kode_lama).val();
		let kode_subcon	= subcon;
		if(insitu=='Y' && supplier_id !='COMP-001'){
			kode_subcon	= 'Y';
		}
		
		let d_last		= $('#list_detail .'+kode+':last').attr('id');
		let nil_urut	= parseInt($('#'+d_last).val()) + 1;
			
		let Template	='<tr id="tr_urut_'+next_urut+'">'+
							'<input type="hidden" name="detDetail['+next_urut+'][quotation_detail_id]" id="quotation_detail_id_'+next_urut+'" value="'+kode+'">'+
							'<input type="hidden" name="detDetail['+next_urut+'][code_detail]" id="code_detail_'+next_urut+'" value="'+kode_detail+'">'+
							'<input type="hidden" name="detDetail['+next_urut+'][tool_id]" id="tool_id_'+next_urut+'" value="'+kode_alat+'">'+
							'<input type="hidden" name="detDetail['+next_urut+'][tool_name]" id="tool_name_'+next_urut+'" value="'+nama_alat+'">'+
							'<input type="hidden" name="detDetail['+next_urut+'][tool_cust]" id="tool_cust_'+next_urut+'" value="'+cust_alat+'">'+
							'<input type="hidden" name="detDetail['+next_urut+'][labs]" id="labs_'+next_urut+'" value="'+labs+'">'+
							'<input type="hidden" name="detDetail['+next_urut+'][insitu]" id="insitu_'+next_urut+'" value="'+insitu+'">'+
							'<input type="hidden" name="detDetail['+next_urut+'][subcon]" id="subcon_'+next_urut+'" value="'+subcon+'">'+
							'<input type="hidden" name="detDetail['+next_urut+'][qty_process]" id="qty_process_'+next_urut+'" value="'+qty_ambil+'">'+
							'<input type="hidden" name="detDetail['+next_urut+'][sts_split]" id="sts_split_'+next_urut+'" value="Y">'+
							'<input type="hidden" name="detDetail['+next_urut+'][waktu_tempuh]" id="waktu_tempuh_'+next_urut+'" value="0">'+
							'<input type="hidden" name="detDetail['+next_urut+'][supplier_id]" id="supplier_id_'+next_urut+'" value="'+supplier_id+'">'+
							'<input type="hidden" name="detDetail['+next_urut+'][keterangan]" id="keterangan_'+next_urut+'" value="'+ket_detail+'">'+
							'<input type="hidden" name="detDetail['+next_urut+'][urut_id]" id="urut_id_'+next_urut+'" value="'+nil_urut+'" class="'+kode+'">'+
							'<td class="text-left text-wrap">'+cust_alat+'</td>'+
							'<td class="text-left text-wrap">'+nama_alat+'</td>'+
							'<td class="text-center"><input type="text" class="form-control" name="detDetail['+next_urut+'][qty]" id="qty_'+next_urut+'" value="'+qty_ambil+'" readOnly></td>'+
							'<td class="text-center">'+insitu+'</td>'+
							'<td class="text-center">'+kode_subcon+'</td>'+
							'<td class="text-center">'+labs+'</td>'+
							'<td class="text-center">';
							if(labs=='Y' || subcon=='Y'){
								Template +='<input type="text" name="detDetail['+next_urut+'][delivery_date]" id="delivery_date_'+next_urut+'" class="form-control tanggal" readOnly>';
							}else{
								Template +='-';
							}
		    Template	 +='</td>'+
							'<td class="text-center" id="proses_'+next_urut+'"></td>'+
							'<td class="text-center">';
							if(labs=='Y' || subcon=='Y'){
								let tgl_ambil	= $('#pick_date_'+kode_lama).val();
								Template		+='<input type="text" name="detDetail['+next_urut+'][pick_date]" id="pick_date_'+next_urut+'" class="form-control" value="'+tgl_ambil+'" readOnly>';
							}else{
								Template +='-';
							}
			  Template	 +='</td>'+
							'<td class="text-center">';
							if(insitu=='N' && subcon=='Y'){
								
								Template		+='<input type="text" name="detDetail['+next_urut+'][subcon_pick_date]" id="subcon_pick_date_'+next_urut+'" class="form-control tanggal" value="" readOnly>';
							}else{
								Template +='-';
							}
			   Template	 +='</td>'+
							'<td class="text-center">';							
							if(subcon=='Y'){								
								Template		+='<input type="text" name="detDetail['+next_urut+'][process_date]" id="process_date_'+next_urut+'" class="form-control tanggal" value="" readOnly>';
							}else{
								Template +='-';
							}
			 Template	 +='</td>'+
							'<td class="text-center">';		
							if(insitu=='N' && subcon=='Y'){
								
								Template		+='<input type="text" name="detDetail['+next_urut+'][subcon_send_date]" id="subcon_send_date_'+next_urut+'" class="form-control tanggal" value="" readOnly>';
							}else{
								Template +='-';
							}
			 Template	 +='<td class="text-left text-wrap">'+ket_detail+'</td>';				
			 Template	 +='</td>'+
							'<td class="text-center">';
							if(subcon=='N'){
								Template		+='<button type="button" id="btn-jadwal" class="btn btn-sm btn-success" onClick="return viewJadwal('+next_urut+');" data-role="qtip" title="SET CALIBRATION DATE"><i class="fa fa-calendar"></i></button>';
							}
			 Template	 +='</td>'+
						'</tr>';
		
		
			
			
			$('#list_detail').append(Template);
			
			/*
			$('#qty_'+kode_lama).spinner({					
				min: 1,
				max: parseInt(sisa)
			 });
			 */
			 
			 $('#qty_'+kode_lama).val(sisa);
			 $('#btn_delete_'+kode_lama).hide(); 
			 if(parseInt(sisa) <= 1){
				 $('#btn_split_'+kode_lama).hide();
			  }
			
			$('.tanggal').datepicker({
				dateFormat: 'yy-mm-dd',
				changeMonth:true,
				changeYear:true,
				minDate:'+0d'
			});
			close_spinner_new();
			$('#MyModalSplitDetail').empty();	
			$('#MyModalSplit').modal('hide');
			$('#btn-modal-close2, #btn_proses_split').prop('disabled',false);
	});
	
	
	$(document).on('click','#btn_simpan_schedule',async(e)=>{
		e.preventDefault();
		$('#btn-modal-close, #btn_simpan_schedule').prop('disabled',true);
		
		let UrutKalibrasi 		= $('#code_urutdetail').val();		
		let TeknisiKalibrasi 	= $('#jadwal_teknisi').val();
		let TglKalibrasi 		= $('#jadwal_tanggal').val();
		let JamAwalKalibrasi	= $('#jadwal_time_awal').val();
		let JamAkhirKalibrasi	= $('#jadwal_time_akhir').val();
		let TempuhKalibrasi		= $('#jadwal_waktu_tempuh').val();
		let KodeProses			= $('#kode_proses_new').val();
		let FlagSplit			= $('#sts_split_'+UrutKalibrasi).val();
		let CodeQuotDetail		= $('#quotation_detail_id_'+UrutKalibrasi).val();
		let CodeUrutDetail		= $('#urut_id_'+UrutKalibrasi).val();
		let NewKodeProses		= KodeProses;
		if(CodeUrutDetail !='' && CodeUrutDetail != null && parseInt(CodeUrutDetail) > 0){
			NewKodeProses	= KodeProses+'-'+CodeUrutDetail; 
		}
		
		const ValueCheck	= {
			'teknisi':{'nilai':TeknisiKalibrasi,'error':'Empty Technician. Please choose Technician first..'},
			'tanggal':{'nilai':TglKalibrasi,'error':'Empty Calibration Date. Please input calibration date first..'},
			'jamawal':{'nilai':JamAwalKalibrasi,'error':'Empty Start Calibration Time. Please input start calibration time first..'},
			'jamakhir':{'nilai':JamAkhirKalibrasi,'error':'Empty End Calibration Time. Please input end calibration time first..'}
		};
		
		if(JamAkhirKalibrasi <= JamAwalKalibrasi){
			ValueCheck['validjam']	= {'nilai':'','error':'Incorrect Calibration Time. Please input correct calibration time first..'};
		}
		
		
		
		try{			
			const ResultCheck		= await GeneralCheckEmptyValue(ValueCheck);
			const ResultConfirm		= await GeneralShowConfirmSave();
			const formData 			= new FormData($('#form_proses_schedule')[0]);
			formData.append('kode',CodeQuotDetail);
			formData.append('kode_proses',NewKodeProses);
			formData.append('sts_split',FlagSplit);
			const ParamProcess	= {
				'action'		: 'save_insert_schedule_tools',
				'parameter'		: formData,
				'loader'		: 'loader_proses_save_calibration'
			};			
			const Hasil_Bro			= await GeneralAjaxProcessData(ParamProcess);
			
			if(Hasil_Bro.status == '1'){
				GeneralShowMessageError('success',Hasil_Bro.pesan);
				
				let TeknisiNama		= Hasil_Bro.member_name;
				let KetKalibrasi	= TglKalibrasi+' '+JamAwalKalibrasi+' - '+JamAkhirKalibrasi+' ('+TeknisiNama+')';
				let TemplateHasil	= '<input type="hidden" name="detDetail['+UrutKalibrasi+'][process_date]" id="process_date_'+UrutKalibrasi+'" value="'+TglKalibrasi+'">'+
									  '<input type="hidden" name="detDetail['+UrutKalibrasi+'][member_id]" id="member_id_'+UrutKalibrasi+'" value="'+TeknisiKalibrasi+'">'+
									  '<input type="hidden" name="detDetail['+UrutKalibrasi+'][member_name]" id="member_name_'+UrutKalibrasi+'" value="'+TeknisiNama+'">'+
									  '<input type="hidden" name="detDetail['+UrutKalibrasi+'][jam_awal]" id="jam_awal_'+UrutKalibrasi+'" value="'+JamAwalKalibrasi+'">'+
									  '<input type="hidden" name="detDetail['+UrutKalibrasi+'][jam_akhir]" id="jam_akhir_'+UrutKalibrasi+'" value="'+JamAkhirKalibrasi+'">'+KetKalibrasi;
				$('#proses_'+UrutKalibrasi).html(TemplateHasil);
				$('#waktu_tempuh_'+UrutKalibrasi).val(TempuhKalibrasi);
				$("#MyModalDetail").html('');
				$("#MyModalView").modal('hide');
				$('#btn-modal-close').prop('disabled',false);
			}else{
				GeneralShowMessageError('error',Hasil_Bro.pesan);
				$('#btn-modal-close, #btn_simpan_schedule').prop('disabled',false);
				return false;
			}			
		}catch(error){
			GeneralShowMessageError('error',error.message);
			$('#btn-modal-close, #btn_simpan_schedule').prop('disabled',false);
            return false;
		}
	});
	
</script>
