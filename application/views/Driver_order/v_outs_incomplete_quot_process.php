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
							<label class="control-label">Plan Date <span class="text-red"> *</span></label>
							<div>
							<?php
								
								
								echo form_input(array('id'=>'plan_date','name'=>'plan_date','class'=>'form-control input-sm tanggal','readOnly'=>true),date('d-m-Y'));	
								echo form_input(array('id'=>'quotation_id','name'=>'quotation_id','type'=>'hidden'),$rows_header->id);
								echo form_input(array('id'=>'customer_id','name'=>'customer_id','type'=>'hidden'),$rows_header->customer_id);
								echo form_input(array('id'=>'alamat_quot','name'=>'alamat_quot','type'=>'hidden'),$rows_header->address);
								echo form_input(array('id'=>'pic_quot','name'=>'pic_quot','type'=>'hidden'),$rows_cust->contact);
								echo form_input(array('id'=>'phone_quot','name'=>'phone_quot','type'=>'hidden'),$rows_cust->hp);
								
								
							?>
							</div>
						</div>
					</div>
					<div class="col-sm-6">
						<div class="form-group">
							<label class="control-label">Plan Time <span class="text-red"> *</span></label>
							<?php
								echo form_input(array('id'=>'plan_time','name'=>'plan_time','class'=>'form-control input-sm'),date('H:i'));						
							?>
						</div>
					</div>				
				</div>
				
				<div class='row'>
					<div class="col-sm-6">
						<div class="form-group">
							<label class="control-label">Notes</label>
							<div>
							<?php
								
								echo form_textarea(array('id'=>'notes','name'=>'notes','class'=>'form-control input-sm text-up','cols'=>75,'rows'=>2));		
								
							?>
							</div>
						</div>
					</div>
					<div class="col-sm-6">
						&nbsp;
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
								echo form_input(array('id'=>'quotation_nomor','name'=>'quotation_nomor','class'=>'form-control input-sm','readOnly'=>true),$rows_header->nomor);	
								
							?>
						</div>
					</div>
					<div class="col-sm-6">
						<div class="form-group">
							<label class="control-label">Quotation Date</label>
							<?php
								echo form_input(array('id'=>'quotation_date','name'=>'quotation_date','class'=>'form-control input-sm','readOnly'=>true),date('d-m-Y',strtotime($rows_header->datet)));						
							?>
						</div>
					</div>				
				</div>
				<div class='row'>
					<div class="col-sm-6">
						<div class="form-group">
							<label class="control-label">Customer</label>
							<?php
								echo form_input(array('id'=>'customer_name','name'=>'customer_name','class'=>'form-control input-sm','readOnly'=>true),$rows_header->customer_name);
														
							?>
						</div>
					</div>
					<div class="col-sm-6">
						<div class="form-group">
							<label class="control-label">Marketing</label>
							<?php
								echo form_input(array('id'=>'member_name','name'=>'member_name','class'=>'form-control input-sm','readOnly'=>true),$rows_header->member_name);					
							?>
						</div>
					</div>				
				</div>
				<div class='row'>
					<div class="col-sm-6">
						<div class="form-group">
							<label class="control-label">PO No</label>
							<?php
								echo form_input(array('id'=>'pono','name'=>'pono','class'=>'form-control input-sm','readOnly'=>true),$rows_header->pono);	
								
							?>
						</div>
					</div>
					<div class="col-sm-6">
						<div class="form-group">
							<label class="control-label">PO Date</label>
							<?php
								echo form_input(array('id'=>'podate','name'=>'podate','class'=>'form-control input-sm','readOnly'=>true),date('d-m-Y',strtotime($rows_header->podate)));						
							?>
						</div>
					</div>				
				</div>
				<div class="row">
					<div class="col-sm-12 col-xs-12 text-center sub-heading" style="color:white;">
						<h5>PICKUP ADDRESS </h5>
					</div>					
				</div>
				<div class='row'>
					<div class="col-sm-6">
						<div class="form-group">
							<label class="control-label">Plant</label>
							<select name="comp_plant" id="comp_plant" class="form-control input-sm chosen-select">
								<option value=""> - </option>
							<?php
								if($rows_plant){
									foreach($rows_plant as $keyPlant=>$valPlant){
										echo'<option value="'.$valPlant->id.'">'.$valPlant->branch.'</option>';
									}
								}
								
								
							?>
							</select>
						</div>
					</div>
					<div class="col-sm-6">
						<div class="form-group">
							<label class="control-label">Address <span class="text-red"> *</span></label>
							<?php
								echo form_textarea(array('id'=>'address','name'=>'address','class'=>'form-control input-sm','cols'=>75,'rows'=>2,'readOnly'=>true),$rows_header->address);						
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
								echo form_input(array('id'=>'pic_name','name'=>'pic_name','class'=>'form-control input-sm','readOnly'=>true),$rows_cust->contact);	
								
							?>
							</div>
						</div>
					</div>
					<div class="col-sm-6">
						<div class="form-group">
							<label class="control-label">PIC Phone <span class="text-red"> *</span></label>
							<?php
								echo form_input(array('id'=>'pic_phone','name'=>'pic_phone','class'=>'form-control input-sm','readOnly'=>true),$rows_cust->hp);						
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
									<th class="text-center"><input type="checkbox" name="chk_all" id="chk_all"></th>
									<th class="text-center">Tool Code</th>
									<th class="text-center">Tool Name</th>
									<th class="text-center">Range</th>
									<th class="text-center">Qty</th>
									<th class="text-center">Qty Pickup</th>
								</tr>
							</thead>
							<tbody id="list_detail">
								<?php
								if($rows_detail){
									$intL	= 0;
									foreach($rows_detail as $ketD=>$valD){
										
										$Code_Detail	= $valD->id;
										$Code_Alat		= $valD->tool_id;
										$Nama_Alat		= $valD->tool_name;
										$Cust_Alat		= $valD->cust_tool;
										$Qty_Outs		= $valD->qty - $valD->qty_so - $valD->qty_driver;
										$Range_Alat		= $valD->range.' '.$valD->piece_id;
										$Keterangan		= $valD->descr;
										if($Qty_Outs > 0){											
											$intL++;
											echo'<tr id="tr_urut_'.$intL.'">';
												echo form_input(array('id'=>'tool_id_'.$intL,'name'=>'detDetail['.$intL.'][tool_id]','type'=>'hidden'),$Code_Alat);
												echo form_input(array('id'=>'tool_name_'.$intL,'name'=>'detDetail['.$intL.'][tool_name]','type'=>'hidden'),$Cust_Alat);
												echo form_input(array('id'=>'qty_sisa_'.$intL,'name'=>'detDetail['.$intL.'][qty_sisa]','type'=>'hidden'),$Qty_Outs);
												echo"
												<td align='center'><input type='checkbox' class='check_detail' name='detDetail[".$intL."][code_process]' id='code_process_".$intL."' value='".$Code_Detail."'></td>";
												echo'											
												<td class="text-center">'.$Code_Alat.'</td>
												<td class="text-left">'.$Cust_Alat.'</td>
												<td class="text-center">'.$Range_Alat.'</td>
												<td class="text-center">'.$Qty_Outs.'</td>
												<td class="text-center">'.form_input(array('id'=>'qty_detail_'.$intL,'name'=>'detDetail['.$intL.'][qty]','class'=>'form-control','readOnly'=>true),0).'</td>												
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
		<div class='box-footer'>";	
			echo'
				<button type="button" class="btn btn-md btn-danger" id="btn-back"> <i class="fa fa-long-arrow-left"></i> BACK </button>';
		if(!empty($rows_detail)){			
				echo'
				&nbsp;&nbsp;&nbsp;<button type="button" id="btn_process_order" class="btn btn-md" style="background-color:#37474f; color:white;vertical-align:middle !important;" title="SAVE PROCESS"> SAVE PROCESS <i class="fa fa-long-arrow-right" style="width:40px;"></i> </button>';
			
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
	.text-center{
		text-align : center !important;
		vertical-align	: middle !important;
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
	
</style>
<script>
	var base_url			= '<?php echo site_url(); ?>';
	var active_controller	= '<?php echo($this->uri->segment(1)); ?>';
	$(document).ready(function(){
		$('#loader_proses_save').hide();
		$('#list_detail').find('tr').each(function(){
			var id_tr	= $(this).attr('id').split('_');
			var kodes	= id_tr[2];
			var nil		= $('#qty_sisa_'+kodes).val();
			
			$('#qty_detail_'+kodes).spinner({					
				min: 0,
				max: parseInt(nil)
			 });
		});	
		
		$('.tanggal').datepicker({
			dateFormat	: 'dd-mm-yy',
			changeMonth	:true,
			changeYear	:true,
			minDate		:'+0d'
		});
		
		$('#plan_time').mask('?99:99');
		
	});
	$(document).on('change','#comp_plant',()=>{
		let ExistAddress		= $('#alamat_quot').val();
		let ExistContact		= $('#pic_quot').val();
		let ExistPhone			= $('#phone_quot').val();
		let ChosenPlant			= $('#comp_plant').val();
		if(ChosenPlant == '' || ChosenPlant == null){
			$('#address').val(ExistAddress);
			$('#pic_name').val(ExistContact);
			$('#pic_phone').val(ExistPhone);
		}else{
			let ChosenCust	= $('#customer_id').val();
			$('#loader_proses_save').show();
			$.post(base_url+'/'+active_controller+'/get_detail_comp_plant',{'plant':ChosenPlant,'nocust':ChosenCust},function(response){
				$('#loader_proses_save').hide();
				const datas	= $.parseJSON(response);
				$('#address').val(datas.alamat);
				$('#pic_name').val(datas.nama);
				$('#pic_phone').val(datas.phone);
			});
		}
	});
		
	$(document).on('click','#btn-back',(e)=>{
		loading_spinner();
		window.location.href =  base_url+'/'+active_controller;
	});
	
	$(document).on('click','#chk_all',()=>{
		if($('#chk_all').is(':checked')){
			$('#list_detail input[type="checkbox"].check_detail').prop('checked',true);
		}else{
			$('#list_detail input[type="checkbox"].check_detail').prop('checked',false);
		}
	});
	
	
	$(document).on('click','#btn_process_order', async(e)=>{
		e.preventDefault();
		$('#btn-back, #btn_process_order').prop('disabled',true);
		
		let AddressChosen = $('#address').val();
		let PICNameChosen = $('#pic_name').val();
		let PicPhoneChosen=	$('#pic_phone').val();
		
		const ValueCheck	= {
			'alamat':{'nilai':AddressChosen,'error':'Empty Pickup Address. Please input pickup address first..'},
			'contact_person':{'nilai':PICNameChosen,'error':'Empty PIC Name. Please input PIC name first..'},
			'contact_phone':{'nilai':PicPhoneChosen,'error':'Empty PIC Phone. Please input pic phone first..'}
		};
		
		let JumChecked	= $('.check_detail:checkbox:checked').length;
		if(parseInt(JumChecked) <= 0){
			let rowsChosen		= '';
			ValueCheck['rows_pilih']	={'nilai':rowsChosen,'error':'No record was selected. Please choose at least one record..'};
			
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
			let QtyChosen		= '';
			ValueCheck['rows_qty']	={'nilai':QtyChosen,'error':'Empty Quantity Pickup. Please input qty first...'};
			
		}
		
		
		try{			
			const ResultCheck		= await GeneralCheckEmptyValue(ValueCheck);
			const ResultConfirm		= await GeneralShowConfirmSave();
			const formData 			= new FormData($('#form_proses_order')[0]);
			const ParamProcess	= {
				'action'		: 'save_create_driver_order',
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
