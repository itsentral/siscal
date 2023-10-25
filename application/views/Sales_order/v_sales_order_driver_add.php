<?php
$this->load->view('include/side_menu');
?> 
<form action="#" method="POST" id="form_proses_order" enctype="multipart/form-data" accept-charset="UTF-8">
	<div class="box box-warning">
		
		<div class="box-body">
			
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
						<h5>DETAIL DRIVER</h5>
					</div>					
				</div>
				<div class='row'>
					<div class="col-sm-6">
						<div class="form-group">
							<label class="control-label">Receive No</label>
							<?php
								echo form_input(array('id'=>'receive_nomor','name'=>'receive_nomor','class'=>'form-control input-sm','readOnly'=>true),$rows_receive->nomor);	
								echo form_input(array('id'=>'quotation_id','name'=>'quotation_id','type'=>'hidden'),$rows_header->id);
								echo form_input(array('id'=>'code_receive','name'=>'code_receive','type'=>'hidden'),$rows_receive->id);
								echo form_input(array('id'=>'customer_id','name'=>'customer_id','type'=>'hidden'),$rows_header->customer_id);
							?>
						</div>
					</div>
					<div class="col-sm-6">
						<div class="form-group">
							<label class="control-label">Receive Date</label>
							<?php
								echo form_input(array('id'=>'receive_date','name'=>'receive_date','class'=>'form-control input-sm','readOnly'=>true),date('d-m-Y',strtotime($rows_receive->datet)));						
							?>
						</div>
					</div>				
				</div>
				<div class='row'>
					<div class="col-sm-6">
						<div class="form-group">
							<label class="control-label">Driver</label>
							<?php
								echo form_input(array('id'=>'driver_name','name'=>'driver_name','class'=>'form-control input-sm','readOnly'=>true),$rows_receive->driver_name);
							?>
						</div>
					</div>
					<div class="col-sm-6">
						<div class="form-group">
							<label class="control-label">PIC Name</label>
							<?php
								echo form_input(array('id'=>'receive_by','name'=>'receive_by','class'=>'form-control input-sm','readOnly'=>true),strtoupper($rows_receive->cust_pic_name));						
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
							<label class="control-label">PO No</label>
							<?php
								echo form_input(array('id'=>'pono','name'=>'pono','class'=>'form-control input-sm','readOnly'=>true),$rows_header->pono);	
								
							?>
						</div>
					</div>	
					<div class="col-sm-6">
						&nbsp;
					</div>
								
				</div>
				<div class="row">
					<div class="col-sm-12 col-xs-12 text-center sub-heading" style="color:white;">
						<h5>DETAIL CUSTOMER</h5>
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
				</div>
				<div class='row'>
					<div class="col-sm-6">
						<div class="form-group">
							<label class="control-label">PIC Name <span class="text-red"> *</span></label>
							<div>
							<?php								
								echo form_input(array('id'=>'pic_name','name'=>'pic_name','class'=>'form-control input-sm'),$rows_cust->contact);	
								
							?>
							</div>
						</div>
					</div>
					<div class="col-sm-6">
						<div class="form-group">
							<label class="control-label">PIC Phone <span class="text-red"> *</span></label>
							<?php
								echo form_input(array('id'=>'pic_phone','name'=>'pic_phone','class'=>'form-control input-sm'),str_replace(array('+','-',' '),'',$rows_cust->hp));						
							?>
						</div>
					</div>				
				</div>
				<div class="row">
					<div class="col-sm-12 col-xs-12 text-center sub-heading" style="color:white;">
						<h5>ADDRESS </h5>
					</div>					
				</div>
				<div class='row'>
					<div class="col-sm-6">
						<div class="form-group">
							<label class="control-label">Delivery Address <span class="text-red"> *</span></label>
							<?php
								echo form_textarea(array('id'=>'address_send','name'=>'address_send','class'=>'form-control input-sm','cols'=>75,'rows'=>2),$rows_cust->address);						
							?>
						</div>
					</div>
					<div class="col-sm-6">
						<div class="form-group">
							<label class="control-label">Invoice Address <span class="text-red"> *</span></label>
							<?php
								echo form_textarea(array('id'=>'address_inv','name'=>'address_inv','class'=>'form-control input-sm','cols'=>75,'rows'=>2),$rows_cust->npwp_address);						
							?>
						</div>
					</div>				
				</div>
				<div class='row'>
					<div class="col-sm-6">
						<div class="form-group">
							<label class="control-label">Certificate Address <span class="text-red"> *</span></label>
							<?php
								echo form_textarea(array('id'=>'address_sertifikat','name'=>'address_sertifikat','class'=>'form-control input-sm','cols'=>75,'rows'=>2),$rows_cust->address);						
							?>
						</div>
					</div>
					<div class="col-sm-6">
						<div class="form-group">
							<label class="control-label">Calibration Address <span class="text-red"> *</span></label>
							<?php
								echo form_textarea(array('id'=>'address','name'=>'address','class'=>'form-control input-sm','cols'=>75,'rows'=>2),$rows_cust->address);						
							?>
						</div>
					</div>				
				</div>
				<div class="row">
					<div class="col-sm-12 col-xs-12 text-center sub-heading" style="color:white;">
						<h5>DETAIL NOTES</h5>
					</div>				
				</div>
				<div class='row'>
					<div class="col-sm-6">
						<div class="form-group">
							<label class="control-label">Delivery Notes</label>
							<?php
								echo form_textarea(array('id'=>'send_notes','name'=>'send_notes','class'=>'form-control input-sm text-up','cols'=>100,'rows'=>2));
														
							?>
						</div>
					</div>
					<div class="col-sm-6">
						<div class="form-group">
							<label class="control-label">Invoice Notes</label>
							<?php
								echo form_textarea(array('id'=>'inv_notes','name'=>'inv_notes','class'=>'form-control input-sm text-up','cols'=>100,'rows'=>2));
											
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
									<th class="text-center">Tool Name</th>
									<th class="text-center">Vendor</th>
									<th class="text-center">Qty</th>
									<th class="text-center">Category</th>
									<th class="text-center">Req Cust</th>
									<th class="text-center">Description</th>									
									<th class="text-center">Action</th>
									
								</tr>
							</thead>
							<tbody id="list_detail">
								<?php
								if($rows_detail){
									$intL	= 0;
									foreach($rows_detail as $ketD=>$valD){
										
										$Code_Detail	= $valD->code_detail;
										$Code_DetQuot	= $valD->quotation_detail_id;
										$Code_Alat		= $valD->tool_id;
										$Nama_Alat		= $valD->tool_name;
										$Cust_Alat		= $valD->cust_tool;
										$Qty_Outs		= $valD->total_outs;
										$Range_Alat		= $valD->range.' '.$valD->piece_id;
										$Keterangan		= $valD->descr_receive;
										$Ket_Cust		= $valD->cust_request;
										$Def_CodeSupp	= $valD->supplier_id;
										$Def_NameSupp	= $valD->supplier_name;
										
										$Tool_Name		= $Nama_Alat;
										if(!empty($Cust_Alat) && $Cust_Alat !== '-'){
											$Tool_Name		= $Cust_Alat;
										}
										if($Qty_Outs > 0){											
											$intL++;
											echo'<tr id="tr_urut_'.$intL.'">';
												echo form_input(array('id'=>'quotation_detail_id_'.$intL,'name'=>'detDetail['.$intL.'][quotation_detail_id]','type'=>'hidden'),$Code_DetQuot);
												echo form_input(array('id'=>'code_detail_'.$intL,'name'=>'detDetail['.$intL.'][code_detail]','type'=>'hidden'),$Code_Detail);
												echo form_input(array('id'=>'tool_id_'.$intL,'name'=>'detDetail['.$intL.'][tool_id]','type'=>'hidden'),$Code_Alat);
												echo form_input(array('id'=>'tool_name_'.$intL,'name'=>'detDetail['.$intL.'][tool_name]','type'=>'hidden'),$Tool_Name);
												echo form_input(array('id'=>'range_'.$intL,'name'=>'detDetail['.$intL.'][range]','type'=>'hidden'),$valD->range);
												echo form_input(array('id'=>'piece_id_'.$intL,'name'=>'detDetail['.$intL.'][piece_id]','type'=>'hidden'),$valD->piece_id);
												echo form_input(array('id'=>'qty_sisa_'.$intL,'name'=>'detDetail['.$intL.'][qty_sisa]','type'=>'hidden'),$Qty_Outs);
												echo form_input(array('id'=>'get_tool_'.$intL,'name'=>'detDetail['.$intL.'][get_tool]','type'=>'hidden'),'Driver');
												echo'											
												<td class="text-left text-wrap">'.$Tool_Name.'</td>
												<td class="text-left">';
													$Tipe		= 'S';
													$Ket_Tipe	= '<span class="badge bg-maroon-active">SUBCON</span>';
													if(strtolower($Def_CodeSupp) == 'comp-001'){
														$Tipe		= 'L';
														$Ket_Tipe	= '<span class="badge bg-green-active">LABS</span>';
														echo $Def_NameSupp;
														echo form_input(array('id'=>'supplier_'.$intL,'name'=>'detDetail['.$intL.'][supplier]','type'=>'hidden'),$Def_CodeSupp);
													}else{
														echo'
														<select name="detDetail['.$intL.'][supplier]" id="supplier_'.$intL.'" class="form-control chosen-select">
															
														';
														if($rows_supplier){
															foreach($rows_supplier as $keySupp=>$valSupp){
																$Yuup	= ($keySupp == $Def_CodeSupp)?'selected':'';
																echo '
																<option value="'.$keySupp.'" '.$Yuup.'>'.$valSupp.'</option>
																';
															}
														}
														echo'
														</select>
														';
													}
													echo form_input(array('id'=>'tipe_'.$intL,'name'=>'detDetail['.$intL.'][tipe]','type'=>'hidden'),$Tipe);
												echo'
												</td>
												<td class="text-center">'.form_input(array('id'=>'qty_detail_'.$intL,'name'=>'detDetail['.$intL.'][qty]','class'=>'form-control','readOnly'=>true),$Qty_Outs).'</td>
												<td class="text-center">'.$Ket_Tipe.'</td>
												<td class="text-left text-wrap">'.$Ket_Cust.'</td>
												<td class="text-center">'.form_textarea(array('id'=>'desc_'.$intL,'name'=>'detDetail['.$intL.'][descr]','class'=>'form-control input-sm text-up text-wrap','cols'=>50,'rows'=>2),$Keterangan).'</td>
												
												<td class="text-center">
													<button type="button" class="btn btn-sm btn-danger" title="Hapus Data" data-role="qtip" onClick="return DelItem('.$intL.');"><i class="fa fa-trash-o"></i></button>
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
	
	var _Address_Delv		= $('#address_send').val();
	var _Address_Inv		= $('#address_inv').val();
	var _Address_Cert		= $('#address_sertifikat').val();
	var _Address_Cust		= $('#address').val();
	var _PIC_Cust			= $('#pic_name').val();
	var _PIC_Phone			= $('#pic_phone').val();
	
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
		
		//$('#pic_phone').mask('?999 999 999 999 999');
		$('.chosen-select').chosen();
		
	});
	
		
	$(document).on('click','#btn-back',(e)=>{
		loading_spinner();
		window.location.href =  base_url+'/'+active_controller+'/outs_letter_order_driver';
	});
	
	const DelItem =(Urut)=>{
		$('#tr_urut_'+Urut).remove();
	}
	
	
	$(document).on('click','#btn_process_order', async(e)=>{
		e.preventDefault();
		$('#btn-back, #btn_process_order').prop('disabled',true);
		
		let AddressChosen = $('#address').val();
		let AddressDeliver = $('#address_send').val();
		let AddressInvoice = $('#address_inv').val();
		let AddressCert 	= $('#address_sertifikat').val();
		let PICNameChosen = $('#pic_name').val();
		let PicPhoneChosen=	$('#pic_phone').val();
		
		const ValueCheck	= {
			'alamat':{'nilai':AddressChosen,'error':'Empty Customer Address. Please input customer address first..'},
			'kirim':{'nilai':AddressDeliver,'error':'Empty Delivery Address. Please input delivery address first..'},
			'invoice':{'nilai':AddressInvoice,'error':'Empty Invoice Address. Please input invoice address first..'},
			'dokumen':{'nilai':AddressCert,'error':'Empty Certificate Address. Please input certificate address first..'},
			'contact_person':{'nilai':PICNameChosen,'error':'Empty PIC Name. Please input PIC name first..'},
			'contact_phone':{'nilai':PicPhoneChosen,'error':'Empty PIC Phone. Please input pic phone first..'}
		};
		
		let JumChecked	= $('#list_detail').find('tr').length;
		if(parseInt(JumChecked) <= 0){
			let rowsChosen		= '';
			ValueCheck['rows_pilih']	={'nilai':rowsChosen,'error':'No record was selected. Please choose at least one record..'};
			
		}
		let intL	= 0;
		$('#list_detail').find('tr').each(function(){
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
				'action'		: 'save_create_letter_order_driver',
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
	
	$(document).on('change','#comp_plant',()=>{
		
		let ChosenPlant			= $('#comp_plant').val();
		if(ChosenPlant == '' || ChosenPlant == null){
			$('#address_send').val(_Address_Delv);
			$('#address_inv').val(_Address_Inv);
			$('#address_sertifikat').val(_Address_Cert);
			$('#address').val(_Address_Cust);
			$('#pic_name').val(_PIC_Cust);
			$('#pic_phone').val(_PIC_Phone);
		}else{
			let ChosenCust	= $('#customer_id').val();
			$('#loader_proses_save').show();
			$.post(base_url+'/'+active_controller+'/get_detail_comp_plant',{'plant':ChosenPlant,'nocust':ChosenCust},function(response){
				$('#loader_proses_save').hide();
				const datas	= $.parseJSON(response);
				$('#address').val(datas.alamat);
				$('#address_send').val(datas.alamat);
				$('#address_sertifikat').val(datas.alamat);
				$('#pic_name').val(datas.nama);
				$('#pic_phone').val(datas.phone);
			});
		}
	});
	
	
</script>
