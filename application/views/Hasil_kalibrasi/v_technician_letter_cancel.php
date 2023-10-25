<?php
$this->load->view('include/side_menu');
?> 
<form action="#" method="POST" id="form-proses-cancel" enctype="multipart/form-data">
	<div class="box box-warning">
		
		<div class="box-body">
			<div class="row">
				<div class="col-sm-12 col-xs-12 text-center sub-heading" style="color:white;">
					<h5>DETAIL SPK TECHNICIAN</h5>
				</div>
				
			</div>
			<?php
			if(empty($rows_header)){
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
							<label class="control-label">SPK No</label>
							<?php
								echo form_input(array('id'=>'nomor_spk','name'=>'nomor_spk','class'=>'form-control input-sm','readOnly'=>true),$rows_header->id);	
								
							?>
						</div>
					</div>
					<div class="col-sm-6">
						<div class="form-group">
							<label class="control-label">SPK Date</label>
							<?php
								echo form_input(array('id'=>'tgl_spk','name'=>'tgl_spk','class'=>'form-control input-sm','readOnly'=>true),$rows_header->datet);						
							?>
						</div>
					</div>				
				</div>
				<div class='row'>
					<div class="col-sm-6">
						<div class="form-group">
							<label class="control-label">Technician</label>
							<?php
								echo form_input(array('id'=>'member_name','name'=>'member_name','class'=>'form-control input-sm','readOnly'=>true),$rows_header->member_name);						
							?>
						</div>
					</div>
					<div class="col-sm-6">
						&nbsp;
					</div>				
				</div>
				<div class="row">
					<div class="col-sm-12 col-xs-12 text-center sub-heading" style="color:white;">
						<h5>DETAIL CANCELLATION</h5>
					</div>
					
				</div>
				<div class='row'>
					
					<div class="col-sm-6">
						<div class="form-group">
							<label class="control-label">Cancel Reason <span class="text-red">*</span></label>
							<?php
								echo form_textarea(array('id'=>'cancel_reason','name'=>'cancel_reason','class'=>'form-control input-sm text-up','cols'=>75, 'rows'=>2));						
							?>
						</div>
					</div>
					<div class="col-sm-6">
						&nbsp;
					</div>				
				</div>
				<div class="row">
					<div class="col-sm-12 col-xs-12 text-center sub-heading" style="color:white;">
						<h5>DETAIL ITEM </h5>
					</div>
					
				</div>
				<div class="row">
					<div class="col-sm-12" style="overflow-x:scroll !important;">
						<table class="table table-striped table-bordered" id="my-grid">
							<thead>
								<tr class="bg-navy-active">
									<th class="text-center">No</th>
									<th class="text-center">Tool Name</th>
									<th class="text-center">Company</th>				
									<th class="text-center">SO No</th>
									<th class="text-center">Qty</th>
									<th class="text-center">Type</th>
									<th class="text-center">Plan Date</th>
								</tr>
							</thead>
							<tbody id="list_detail">
								<?php
								if($rows_detail){
									$intL	= 0;
									foreach($rows_detail as $ketD=>$valD){
										$intL++;
										$Nomor_SO = $Company = $Plan_Date = '-';
										$Query_SO	= "SELECT no_so,customer_name,plan_process_date,plan_time_start FROM trans_details WHERE id = '".$valD->detail_id."'";
										$rows_SO	= $this->db->query($Query_SO)->row();
										if($rows_SO){
											$Nomor_SO	= $rows_SO->no_so;
											$Company	= $rows_SO->customer_name;
											$Plan_Date	= date('d M Y H:i',strtotime($rows_SO->plan_process_date.' '.$rows_SO->plan_time_start));
										}
										
										if($valD->category == 'INSITU'){
											$jenis		='<span class="badge bg-maroon">Insitu</span>';
										}else{
											$jenis		='<span class="badge bg-green">Labs</span>';
										}
										
										echo"<tr>";	
											echo"<td align='center'>".$intL."</td>";
											echo"<td align='left'>".$valD->tool_name."</td>";
											echo"<td align='left'>".$Company."</td>";
											echo"<td align='center'>".$Nomor_SO."</td>";										
											echo"<td align='center'>".number_format($valD->qty)."</td>";
											echo"<td align='center'>".$jenis."</td>";
											echo"<td align='center'>".$Plan_Date."</td>";
										echo"</tr>";
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
				<button type="button" class="btn btn-md btn-danger" id="btn-back"> <i class="fa fa-long-arrow-left"></i> BACK </button>';
		if(!empty($rows_detail)){			
				echo'
				&nbsp;&nbsp;&nbsp;<button type="button" id="btn-process-cancel" class="btn btn-md" style="background-color:#37474f; color:white;vertical-align:middle !important;" title="SAVE PROCESS"> SAVE PROCESS <i class="fa fa-long-arrow-right" style="width:40px;"></i> </button>';
			
		}
		echo"</div>";
		?>
		
	</div>
</form>

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
		$('#btn-back').click(function(){			
			loading_spinner();
			window.location.href =  base_url+'/'+active_controller;
		});
		
	});
	
	$(document).on('click','#btn-process-cancel', async(e)=>{
		e.preventDefault();
		$('#btn-back, #btn-process-cancel').prop('disabled',true);
	
		let reason			= $('#cancel_reason').val();
		
		const ValueCheck	= {
			'alasan':{'nilai':reason,'error':'Empty Reason. Please input reason first..'}
		};
		
		
		try{			
			const ResultCheck		= await GeneralCheckEmptyValue(ValueCheck);
			const ResultConfirm		= await GeneralShowConfirmSave();
			const formData 			= new FormData($('#form-proses-cancel')[0]);
			const ParamProcess	= {
				'action'		: 'save_batal_spk_teknisi',
				'parameter'		: formData,
				'loader'		: 'loader_proses_save'
			};			
			const Hasil_Bro			= await GeneralAjaxProcessData(ParamProcess);
			
			if(Hasil_Bro.status == '1'){
				GeneralShowMessageError('success',Hasil_Bro.pesan);
				window.location.href = base_url +'/'+ active_controller;
			}else{
				GeneralShowMessageError('error',Hasil_Bro.pesan);
				$('#btn-back, #btn-process-cancel').prop('disabled',false);
				return false;
			}			
		}catch(error){
			GeneralShowMessageError('error',error.message);
			$('#btn-back, #btn-process-cancel').prop('disabled',false);
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
	
	$(document).on('click','#chk_all',()=>{
		if($('#chk_all').is(':checked')){
			$('#list_detail input[type="checkbox"].check_detail').prop('checked',true);
		}else{
			$('#list_detail input[type="checkbox"].check_detail').prop('checked',false);
		}
	});
	
</script>
