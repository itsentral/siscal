<?php
$this->load->view('include/side_menu');
?> 
<form action="#" method="POST" id="form-proses" enctype="multipart/form-data">
	<div class="box box-warning">		
		<div class="box-body">
			<div class="row">
				<div class="col-sm-12 col-xs-12 text-center sub-heading" style="color:white;">
					<h5><?php echo $title;?></h5>
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
							<label class="control-label">Nomor Quotation</label>
							<?php
								echo form_input(array('id'=>'nomor_quotation','name'=>'nomor_quotation','class'=>'form-control input-sm','readOnly'=>true),$rows_header[0]->nomor);	
								echo form_input(array('id'=>'code_quotation','name'=>'code_quotation','class'=>'form-control input-sm','type'=>'hidden'),$rows_header[0]->id);
							?>
						</div>
					</div>
					<div class="col-sm-6">
						<div class="form-group">
							<label class="control-label">Tanggal Quotation</label>
							<?php
								echo form_input(array('id'=>'tgl_quot','name'=>'tgl_quot','class'=>'form-control input-sm','readOnly'=>true),date('d-m-Y',strtotime($rows_header[0]->datet)));						
							?>
						</div>
					</div>				
				</div>
				<div class='row'>
					<div class="col-sm-6">
						<div class="form-group">
							<label class="control-label">Customer</label>
							<?php
								echo form_input(array('id'=>'customer_name','name'=>'customer_name','class'=>'form-control input-sm','readOnly'=>true),$rows_header[0]->customer_name);						
							?>
						</div>
					</div>
					<div class="col-sm-6">
						<div class="form-group">
							<label class="control-label">Alamat</label>
							<?php
								echo form_textarea(array('id'=>'alamat','name'=>'alamat','class'=>'form-control input-sm','readOnly'=>true,'cols'=>75, 'rows'=>2),$rows_header[0]->address);						
							?>
						</div>
					</div>				
				</div>
				<div class='row'>
					<div class="col-sm-6">
						<div class="form-group">
							<label class="control-label">Marketing</label>
							<?php
								echo form_input(array('id'=>'marketing','name'=>'marketing','class'=>'form-control input-sm','readOnly'=>true),$rows_header[0]->member_name);						
							?>
						</div>
					</div>
					<div class="col-sm-6">
						<div class="form-group">
							<label class="control-label">Nomor PO</label>
							<?php
								echo form_input(array('id'=>'pono','name'=>'pono','class'=>'form-control input-sm','readOnly'=>true),$rows_header[0]->pono);						
							?>
						</div>
					</div>				
				</div>
				<div class='row'>
					<div class="col-sm-6">
						<div class="form-group">
							<label class="control-label">Cancel Reason <span class="text-red">*</span></label>
							<?php
								echo form_textarea(array('id'=>'cancel_reason','name'=>'cancel_reason','class'=>'form-control input-sm','cols'=>75, 'rows'=>2));						
							?>
						</div>
					</div>
					<div class="col-sm-6">&nbsp;</div>				
				</div>
				<div class="row">
					<div class="col-sm-12 col-xs-12 text-center sub-heading" style="color:white;">
						<h5>DETAIL ALAT </h5>
					</div>
					
				</div>
				<div class="row">
					<div class="col-sm-12" style="overflow-x:scroll !important;">
						<table class="table table-striped table-bordered" id="my-grid">
							<thead>
								<tr class="bg-navy-active">
									<th class="text-center"><input type="checkbox" name="chk_all" id="chk_all"></th>
									<th class="text-center">Kode Alat</th>
									<th class="text-center">Nama Alat</th>				
									<th class="text-center">Qty</th>
									<th class="text-center">Qty Cancel</th>
								</tr>
							</thead>
							<tbody id="list_detail">
								<?php
								if($rows_detail){
									$intL	= 0;
									foreach($rows_detail as $ketD=>$valD){
										$intL++;
										$Qty_Sisa	= $valD->qty - $valD->qty_so;
										echo"<tr id='tr_item_".$intL."'>";										
											echo"<td align='center'><input type='checkbox' class='check_detail' name='detPilih[".$intL."][code_detail]' id='code_detail_".$intL."' value='".$valD->id."'></td>";
											echo"<td align='center'>".$valD->tool_id."</td>";
											echo"<td align='left'>".$valD->cust_tool."</td>";										
											echo"<td align='center'>".$Qty_Sisa."</td>";
											echo"<td align='center'>"
												.form_input(array('id'=>'qty_detail_'.$intL,'name'=>'detPilih['.$intL.'][qty]','class'=>'form-control','readOnly'=>true),0)
												.form_input(array('id'=>'qty_sisa_'.$intL,'name'=>'detPilih['.$intL.'][qty_sisa]','type'=>'hidden'),$Qty_Sisa).
											"</td>";
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
			
		echo"<div class='box-footer'>";	
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
</style>
<script>
	var base_url			= '<?php echo site_url(); ?>';
	var active_controller	= '<?php echo($this->uri->segment(1)); ?>';
	var _validFileExtensions = [".jpg", ".jpeg", ".png", ".gif", ".bmp", ".pdf",".PDF",".JPEG",".JPG"];
	$(document).ready(function(){
		$('#list_detail').find('tr').each(function(){
			var id_tr	= $(this).attr('id').split('_');
			var kodes	= id_tr[2];
			var nil		= $('#qty_sisa_'+kodes).val();
			
			$('#qty_detail_'+kodes).spinner({					
				min: 0,
				max: parseInt(nil)
			 });
		});	

		$('#btn-back').click(function(){			
			loading_spinner();
			window.location.href =  base_url+'/'+active_controller;
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
	
	$(document).on('click','#btn-process-cancel',(e)=>{
		e.preventDefault();
		$('#btn-back, #btn-process-cancel').prop('disabled',true);
		let Reason			= $('#cancel_reason').val();
		
		if(Reason == null || Reason == '' || Reason == '-'){
			swal({
			  title				: "Error Message !",
			  text				: 'Empty cancel reason. Please input reason first...',						
			  type				: "warning"
			});
			$('#btn-back, #btn-process-cancel').prop('disabled',false);
			return false;
		}
		let JumChecked	= $('.check_detail:checkbox:checked').length;
		if(parseInt(JumChecked) <= 0){
			swal({
			  title				: "Error Message !",
			  text				: 'No record was found to process...',						
			  type				: "warning"
			});
			$('#btn-back, #btn-process-cancel').prop('disabled',false);
			return false;
		}
		let intL	= 0;
		$('#list_detail .check_detail:checkbox:checked').each(function(){
			const SplitCode	= $(this).attr('id').split('_');
			let CodeUrut	= SplitCode[2];
			
			let QtyChosen	= $('#qty_detail_'+CodeUrut).val();
			if(parseInt(QtyChosen) <= 0 || QtyChosen == null || QtyChosen == ''){
				intL++;
			}
		});
		
		if(intL > 0){
			swal({
			  title				: "Error Message !",
			  text				: 'Empty Quantity Cancel. Please input qty first...',						
			  type				: "warning"
			});
			$('#btn-back, #btn-process-cancel').prop('disabled',false);
			return false;
		}
		
		swal({
			  title: "Are you sure?",
			  text: "You will not be able to process again this data!",
			  type: "warning",
			  showCancelButton: true,
			  confirmButtonClass: "btn-danger",
			  confirmButtonText: "Yes, Process it!",
			  cancelButtonText: "No, cancel process!",
			  closeOnConfirm: true,
			  closeOnCancel: false
			},
			function(isConfirm) {					
				if (isConfirm) {
					loading_spinner_new();
					var formData 	= new FormData($('#form-proses')[0]);
					var baseurl		= base_url +'/'+ active_controller+'/save_quotation_cancel_proses';
					$.ajax({
						url			: baseurl,
						type		: "POST",
						data		: formData,
						cache		: false,
						dataType	: 'json',
						processData	: false, 
						contentType	: false,				
						success		: function(data){
							close_spinner_new();
							if(data.status == 1){											
								swal({
									  title	: "Save Success!",
									  text	: data.pesan,
									  type	: "success"
									});
								window.location.href = base_url +'/'+ active_controller;
							}else{								
								swal({
								  title	: "Save Failed!",
								  text	: data.pesan,
								  type	: "warning"
								});									
								//alert(data.pesan);
								$('#btn-back, #btn-process-cancel').prop('disabled',false);
								return false;
								
							}
						},
						error: function() {
							close_spinner_new();
							swal({
							  title				: "Error Message !",
							  text				: 'An Error Occured During Process. Please try again..',						
							  type				: "warning"
							});
							$('#btn-back, #btn-process-cancel').prop('disabled',false);
							return false;
						}
					});
					
				} else {
					close_spinner_new();
					swal("Cancelled", "Data can be process again :)", "error");
					$('#btn-back, #btn-process-cancel').prop('disabled',false);
					return false;
				}
		});
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
