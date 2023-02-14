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
							<label class="control-label">SO No</label>
							<?php
								echo form_input(array('id'=>'no_so','name'=>'no_so','class'=>'form-control input-sm','readOnly'=>true),$rows_order->no_so);	
								echo form_input(array('id'=>'code_order','name'=>'code_order','type'=>'hidden'),$rows_order->id);
								echo form_input(array('id'=>'quotation_id','name'=>'quotation_id','type'=>'hidden'),$rows_order->quotation_id);
								echo form_input(array('id'=>'customer_id','name'=>'customer_id','type'=>'hidden'),$rows_order->customer_id);
							?>
						</div>
					</div>
					<div class="col-sm-6">
						<div class="form-group">
							<label class="control-label">SO Date</label>
							<?php
								echo form_input(array('id'=>'so_date','name'=>'so_date','class'=>'form-control input-sm','readOnly'=>true),date('d-m-Y',strtotime($rows_order->tgl_so)));						
							?>
						</div>
					</div>				
				</div>
				
				<div class="row">
					<div class="col-sm-12 col-xs-12 text-center sub-heading" style="color:white;">
						<h5>DETAIL QUOTATION</h5>
					</div>
					
				</div>
				<div class='row'>
					<div class="col-sm-6">
						<div class="form-group">
							<label class="control-label">Quotation</label>
							<?php
								echo form_input(array('id'=>'quotation_nomor','name'=>'quotation_nomor','class'=>'form-control input-sm','readOnly'=>true),$rows_quot->nomor);	
								
							?>
						</div>
					</div>
					<div class="col-sm-6">
						<div class="form-group">
							<label class="control-label">Quotation Date</label>
							<?php
								echo form_input(array('id'=>'quotation_date','name'=>'quotation_date','class'=>'form-control input-sm','readOnly'=>true),date('d-m-Y',strtotime($rows_quot->datet)));						
							?>
						</div>
					</div>				
				</div>
				<div class='row'>
					<div class="col-sm-6">
						<div class="form-group">
							<label class="control-label">Customer</label>
							<?php
								echo form_input(array('id'=>'customer_name','name'=>'customer_name','class'=>'form-control input-sm','readOnly'=>true),$rows_quot->customer_name);
														
							?>
						</div>
					</div>
					<div class="col-sm-6">
						<div class="form-group">
							<label class="control-label">PO No</label>
							<?php
								echo form_input(array('id'=>'pono','name'=>'pono','class'=>'form-control input-sm','readOnly'=>true),$rows_quot->pono);	
								
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
								echo form_input(array('id'=>'pic_name','name'=>'pic_name','class'=>'form-control input-sm','readOnly'=>true),$rows_order->pic);	
								
							?>
							</div>
						</div>
					</div>
					<div class="col-sm-6">
						<div class="form-group">
							<label class="control-label">PIC Phone <span class="text-red"> *</span></label>
							<?php
								echo form_input(array('id'=>'pic_phone','name'=>'pic_phone','class'=>'form-control input-sm','readOnly'=>true),str_replace(array('+','-',' '),'',$rows_order->phone));						
							?>
						</div>
					</div>				
				</div>
				<div class="row">
					<div class="col-sm-12 col-xs-12 text-center sub-heading" style="color:white;">
						<h5>DETAIL REMINDER </h5>
					</div>					
				</div>
				
				<?php
				$Flag_Add	= 'Y';
				if($rows_user){
					$Flag_Add	= 'N';
					
					echo'
					<div class="row">
						<div class="col-sm-6">
							<div class="form-group">
								<label class="control-label">Account Name <span class="text-red"> *</span></label>
								<div>									
								'.form_input(array('id'=>'account_name','name'=>'account_name','class'=>'form-control input-sm','readOnly'=>true),$rows_user->name)
								.form_input(array('id'=>'account_username','name'=>'account_username','type'=>'hidden'),$rows_user->username).'
								</div>
							</div>
						</div>
						<div class="col-sm-6">
							<div class="form-group">
								<label class="control-label">Account Phone <span class="text-red"> *</span></label>
								'.form_input(array('id'=>'account_phone','name'=>'account_phone','class'=>'form-control input-sm','readOnly'=>true),str_replace(array('+','-',' '),'',$rows_user->phone)).'
							</div>
						</div>				
					</div>
					';
				}else{
					$rows_Phone		= $this->db->get('country_phones')->result();
					echo'
					<div class="row">
						<div class="col-sm-6">
							<div class="form-group">
								<label class="control-label">Account Username <span class="text-red"> *</span></label>
								<div>									
								'.form_input(array('id'=>'account_username','name'=>'account_username','class'=>'form-control input-sm','autocomplete'=>'off')).'								
								</div>
							</div>
						</div>
						<div class="col-sm-2">
							<div class="form-group">
								<label class="control-label">Account Phone <span class="text-red"> *</span></label>
								<div>
									<select name="account_phone_code" id="account_phone_code" class="form-control chosen-select">';
									$Default	= '62';
									if($rows_Phone){
										foreach($rows_Phone as $keyC=>$valsC){
											$Code_Phone	= $valsC->phone_code;
											$Yuup		= ($Default == $Code_Phone)?'selected':'';
											echo'<option value="'.$Code_Phone.'" '.$Yuup.'>+'.$Code_Phone.'</option>';
										}
									}
					echo'			</select>
								</div>
							</div>
						</div>
						<div class="col-sm-4">
							<div class="form-group">
								<label class="control-label">&nbsp;</label>
								'.form_input(array('id'=>'account_phone','name'=>'account_phone','class'=>'form-control input-sm','autocomplete'=>'off')).'
							</div>
						</div>				
					</div>
					<div class="row">
						<div class="col-sm-6">
							<div class="form-group">
								<label class="control-label">Account Name <span class="text-red"> *</span></label>
								<div>									
								'.form_input(array('id'=>'account_name','name'=>'account_name','class'=>'form-control input-sm text-up','autocomplete'=>'off')).'								
								</div>
							</div>
						</div>
						<div class="col-sm-6">
							&nbsp;
						</div>
					</div>	
					';
				}
				echo form_input(array('id'=>'flag_add','name'=>'flag_add','type'=>'hidden'),$Flag_Add);
				?>
				
				
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
									<td class="text-center">No</td>
									<th class="text-center">Tool Name</th>
									<th class="text-center">Merk</th>				
									<th class="text-center">Type</th>
									<th class="text-center">Identify No</th>
									<th class="text-center">Serial Number</th>
									<th class="text-center">Certificate No</th>
									<th class="text-center">Action</th>
								</tr>
							</thead>
							<tbody id="list_detail">
								<?php
								
								if($rows_detail){
									$intL	= 0;
									foreach($rows_detail as $ketD=>$valD){
										$intL++;
										$Code_Detail	= $valD->id;
																		
										echo"<tr>";								
											echo"<td class='text-center'>".$intL."</td>";
											echo"<td class='text-left'>".$valD->tool_name."</td>";
											echo"<td class='text-left'>".$valD->merk."</td>";
											echo"<td class='text-left'>".$valD->tool_type."</td>";
											echo"<td class='text-center'>".$valD->no_identifikasi."</td>";
											echo"<td class='text-center'>".$valD->no_serial_number."</td>";
											echo"<td class='text-center'>".$valD->no_sertifikat."</td>";
											echo"<td class='text-center'>
													<button type='button' class='btn btn-sm bg-navy-active' onClick = 'ActionPreview({code:\"".$Code_Detail."\",action :\"view_detail_sertifikat\",title:\"DETAIL CERTIFICATE\"});' title='DETAIL CERTIFICATE'> <i class='fa fa-search'></i> </button>
												</td>";											
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
		<div class='box-footer'>";	
			echo'
				<button type="button" class="btn btn-md btn-danger" id="btn-back"> <i class="fa fa-long-arrow-left"></i> BACK </button>';
		if(!empty($rows_detail)){			
				echo'
				&nbsp;&nbsp;&nbsp;<button type="button" id="btn_process_order" class="btn btn-md" style="background-color:#37474f; color:white;vertical-align:middle !important;" title="SEND REMINDER CERTIFICATE"> SEND REMINDER CERTIFICATE <i class="fa fa-send" style="width:40px;"></i> </button>';
			
		}
		echo"</div>";
		?>
		
	</div>
</form>
<div class="modal fade" id="MyModalView" tabindex="-1" role="dialog" aria-labelledby="MyModal" data-backdrop="static">
	<div class="modal-dialog" role="document" style="min-width:70% !important;">
		 <div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="MyModalTitle"></h5>
				<button class="close" data-dismiss="modal" aria-label="close" id="btn-modal-close">
					<span aria-hidden="true"><i class="fa fa-close"></i></span>
				</button>
			</div>
			<div class="modal-body" id="MyModalDetail">
			
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
	
</style>
<script>
	var base_url			= '<?php echo site_url(); ?>';
	var active_controller	= '<?php echo($this->uri->segment(1)); ?>';
	
	$(document).ready(function(){
		$('#loader_proses_save').hide();	
		$('#pic_phone, #account_phone').mask('?999 999 999 999 999');
		$('.chosen-select').chosen();
		
		$('#account_username').keypress(function(e) {
			if(e.which == 32) {
				return false;
			}				
		});
	});
	
	function ActionPreview(ObjectParam){
		let TitleAction	= ObjectParam.title;
		let CodeAction	= ObjectParam.code;
		let LinkAction 	= ObjectParam.action;
		
		loading_spinner_new();
		
		$('#MyModalTitle').text(TitleAction);		
        $.post(base_url +'/'+ active_controller+'/'+LinkAction,{'code':CodeAction}, function(response) {
			close_spinner_new();
            $("#MyModalDetail").html(response);
        });
		$("#MyModalView").modal('show');		
	}
	
	$(document).on('click','#btn-back',(e)=>{
		loading_spinner();
		window.location.href =  base_url+'/'+active_controller;
	});
	
	
	
	$(document).on('click','#btn_process_order', async(e)=>{
		e.preventDefault();
		$('#btn-back, #btn_process_order').prop('disabled',true);
		
		let Code_Order 		= $('#code_order').val();
		let Flag_Add 		= $('#flag_add').val();
		
		const ValueCheck	= {
			'code_order':{'nilai':Code_Order,'error':'Empty Sales Order No. Please input sales order no first..'}
		};
		if(Flag_Add == 'Y'){
			let Account_Username	= $('#account_username').val();
			let Account_Phone		= $('#account_phone').val();
			let Account_Name		= $('#account_name').val();
			
			ValueCheck['username']	={'nilai':Account_Username,'error':'Empty Account Username. Please input username first..'};
			ValueCheck['fullname']	={'nilai':Account_Name,'error':'Empty Account Name. Please input name first..'};
			
			if(Account_Phone == '' || Account_Phone == null || Account_Phone.length <= 10){
				ValueCheck['phone']	={'nilai':'','error':'Incorrect Or Empty Account Phone. Please input account phone first..'};
			}
		}
		
		
		
		try{			
			const ResultCheck		= await GeneralCheckEmptyValue(ValueCheck);
			const ResultConfirm		= await GeneralShowConfirmSave();
			const formData 			= new FormData($('#form_proses_order')[0]);
			const ParamProcess	= {
				'action'		: 'save_sales_order_reminder_certificate',
				'parameter'		: formData,
				'loader'		: 'loader_proses_save'
			};			
			const Hasil_Bro			= await GeneralAjaxProcessData(ParamProcess);
			
			if(Hasil_Bro.status == '1'){
				GeneralShowMessageError('success',Hasil_Bro.pesan);
				window.location.href	= base_url+'/'+active_controller;
			}else{
				GeneralShowMessageError('error',Hasil_Bro.pesan);
				$('#btn-back, #btn_process_order').prop('disabled',false);
				return false;
			}			
		}catch(error){
			GeneralShowMessageError('error',error.message);
			$('#btn-back, #btn_process_order').prop('disabled',false);
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
	
	
	
</script>
