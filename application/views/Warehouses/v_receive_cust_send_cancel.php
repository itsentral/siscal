<?php
$this->load->view('include/side_menu');
?> 
<form action="#" method="POST" id="form_proses_cancel" enctype="multipart/form-data">
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
								echo form_input(array('id'=>'code_quot','name'=>'code_quot','type'=>'hidden'),$rows_header->id);
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
				<div class='row'>					
					<div class="col-sm-6">
						<div class="form-group">
							<label class="control-label">Cancel Reason <span class="text-red">*</span></label>
							<?php
								echo form_textarea(array('id'=>'cancel_reason', 'name'=>'cancel_reason','cols'=>'75','rows'=>'2', 'class'=>'form-control input-sm text-up'));								
							?>							
						</div>
					</div>
					<div class="col-sm-6">&nbsp;</div>				
				</div>
				<div class="row">
					<div class="col-sm-12 col-xs-12 text-center sub-heading" style="color:white;">
						<h5>OUTSTANDING RECEIVE ITEM </h5>
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
		echo"</div>
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
		";
		echo"<div class='box-footer'>";	
			echo'
				<button type="button" class="btn btn-md btn-danger" id="btn-back"> <i class="fa fa-long-arrow-left"></i> BACK </button>';
		if(!empty($rows_detail)){			
				echo'
				&nbsp;&nbsp;&nbsp;<button type="button" id="btn-process-cancel" class="btn btn-md" style="background-color:#37474f; color:white;vertical-align:middle !important;" title="CANCELLATION PROCESS"> CANCELLATION PROCESS <i class="fa fa-long-arrow-right" style="width:40px;"></i> </button>';
			
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
	.text-up{
		text-transform : uppercase !important;
	}
	.modal {
	  overflow-y:auto !important;
	}
</style>
<script>
	var base_url			= '<?php echo site_url(); ?>';
	var active_controller	= '<?php echo($this->uri->segment(1)); ?>';
	var _validFileExtensions = [".jpg", ".jpeg", ".png", ".gif", ".bmp", ".JPEG",".JPG"];
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
	});
	
	$(document).on('click','#chk_all',()=>{
		if($('#chk_all').is(':checked')){
			$('#list_detail input[type="checkbox"].check_detail').prop('checked',true);
		}else{
			$('#list_detail input[type="checkbox"].check_detail').prop('checked',false);
		}
	});
	
	
	$(document).on('click','#btn-back',(e)=>{
		loading_spinner();
		window.location.href =  base_url+'/'+active_controller;
	});
	
	$(document).on('click','#btn-process-cancel', async(e)=>{
		e.preventDefault();
		$('#btn-back, #btn-process-cancel').prop('disabled',true);
		
		let ReasonCancel = $('#cancel_reason').val();
		
		
		const ValueCheck	= {
			'alasan':{'nilai':ReasonCancel,'error':'Empty Cancel Reason. Please input reason first..'}
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
			const formData 			= new FormData($('#form_proses_cancel')[0]);
			const ParamProcess	= {
				'action'		: 'save_cancel_receive_process',
				'parameter'		: formData,
				'loader'		: 'loader_proses_save'
			};			
			const Hasil_Bro			= await GeneralAjaxProcessData(ParamProcess);
			
			if(Hasil_Bro.status == '1'){
				GeneralShowMessageError('success',Hasil_Bro.pesan);
				window.location.href	= base_url+'/'+active_controller;
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
	
	
	
</script>
