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
							<label class="control-label">BAST No</label>
							<div>
							<?php
								echo'<span class="badge bg-maroon">AUTOMATIC</span>';
								$Jenis_Bast	= 'DEL';
								if($jenis == 'REC'){
									$Jenis_Bast	= 'REC';
								}
								echo form_input(array('id'=>'datet','name'=>'datet','type'=>'hidden'),$rows_header[0]->datet);	
								echo form_input(array('id'=>'flag_type','name'=>'flag_type','type'=>'hidden'),$flag_type);
								echo form_input(array('id'=>'type_bast','name'=>'type_bast','type'=>'hidden'),$Jenis_Bast);
								echo form_input(array('id'=>'spk_driver_id','name'=>'spk_driver_id','type'=>'hidden'),$rows_header[0]->id);
								echo form_input(array('id'=>'letter_order_id','name'=>'letter_order_id','type'=>'hidden'),$rows_so[0]->id);
								
								if($jenis=='rec'){
									$Judul	='Qty Process';
									echo form_input(array('id'=>'receive_by','name'=>'receive_by','type'=>'hidden'),$rows_header[0]->member_name);
									
								}else{
									$Judul	='Qty Send';
									echo form_input(array('id'=>'sending_by','name'=>'sending_by','type'=>'hidden'),$rows_header[0]->member_name);
								}
								
							?>
							</div>
						</div>
					</div>
					<div class="col-sm-6">
						<div class="form-group">
							<label class="control-label">BAST Date</label>
							<?php
								echo form_input(array('id'=>'tgl_bast','name'=>'tgl_bast','class'=>'form-control input-sm','readOnly'=>true),date('d-m-Y',strtotime($rows_header[0]->datet)));						
							?>
						</div>
					</div>				
				</div>
				<div class='row'>
					<div class="col-sm-6">
						<div class="form-group">
							<label class="control-label">SO No</label>
							<?php
								echo form_input(array('id'=>'no_so','name'=>'no_so','class'=>'form-control input-sm','readOnly'=>true),$rows_so[0]->no_so);						
							?>
						</div>
					</div>
					<div class="col-sm-6">
						<div class="form-group">
							<label class="control-label">Customer</label>
							<?php
								echo form_input(array('id'=>'customer_so','name'=>'customer_so','class'=>'form-control input-sm','readOnly'=>true),$rows_so[0]->customer_name);					
							?>
						</div>
					</div>				
				</div>
				<div class='row'>
					<div class="col-sm-6">
						<div class="form-group">
							<label class="control-label">Company</label>
							<?php
								echo form_input(array('id'=>'name','name'=>'name','class'=>'form-control input-sm','readOnly'=>true),$rows_cust[0]->name);	
								echo form_input(array('id'=>'kode','name'=>'kode','type'=>'hidden'),$rows_cust[0]->id);
							?>
						</div>
					</div>
					<div class="col-sm-6">
						<div class="form-group">
							<label class="control-label">PIC Name</label>
							<?php
								echo form_input(array('id'=>'pic','name'=>'pic','class'=>'form-control input-sm'),$rows_cust[0]->contact);						
							?>
						</div>
					</div>				
				</div>
				<div class='row'>
					<div class="col-sm-6">
						<div class="form-group">
							<label class="control-label">Address</label>
							<?php
								if($flag_type=='CUST'){
									$Alamat		= $rows_so[0]->address_send;
								}else{
									$Alamat		= $rows_cust[0]->address;
									
								}
								echo form_textarea(array('id'=>'address','name'=>'address','class'=>'form-control input-sm','readOnly'=>true,'cols'=>75, 'rows'=>2),$Alamat);							
							?>
						</div>
					</div>
					<div class="col-sm-6">
						<div class="form-group">
							<label class="control-label">Notes</label>
							<?php
								echo form_textarea(array('id'=>'notes','name'=>'notes','class'=>'form-control input-sm','cols'=>75, 'rows'=>2),'');					
							?>
						</div>
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
									<th class="text-center"><?php echo $Judul;?></th>				
									<th class="text-center">Description</th>
								</tr>
							</thead>
							<tbody id="list_detail">
								<?php
								if($rows_detail){
									$intL	= 0;
									foreach($rows_detail as $ketD=>$valD){
										$intL++;
										
										
										echo"<tr id='tr_row_".$intL."'>";		
											echo form_input(array('id'=>'trans_id_'.$intL,'name'=>'detDetail['.$intL.'][trans_id]','type'=>'hidden'),$valD->id);
											echo form_input(array('id'=>'quotation_detail_id_'.$intL,'name'=>'detDetail['.$intL.'][quotation_detail_id]','type'=>'hidden'),$valD->schedule_detail_id);
											echo form_input(array('id'=>'tool_id_'.$intL,'name'=>'detDetail['.$intL.'][tool_id]','type'=>'hidden'),$valD->tool_id);
											echo form_input(array('id'=>'tool_name_'.$intL,'name'=>'detDetail['.$intL.'][tool_name]','type'=>'hidden'),$valD->tool_name);
											echo form_input(array('id'=>'qty_real_'.$intL,'name'=>'detDetail['.$intL.'][qty_real]','type'=>'hidden'),$valD->qty);
											
											
											echo"<td align='center'>".$intL."</td>";
											echo"<td align='left'>".$valD->tool_name."</td>";										
											echo"<td align='center'>";
												echo'<input type="text" name="detDetail['.$intL.'][qty]" id="qty_'.$intL.'" value="'.number_format($valD->qty).'" class="form-control input-sm" size = "5px" readOnly>';
											echo"</td>";
											echo"<td align='left'>";
												echo form_textarea(array('id'=>'descr_'.$intL,'name'=>'detDetail['.$intL.'][descr]','class'=>'form-control input-sm','cols'=>75, 'rows'=>2),'');
											echo"</td>";
											
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
				&nbsp;&nbsp;&nbsp;<button type="button" id="btn-process-approve" class="btn btn-md" style="background-color:#37474f; color:white;vertical-align:middle !important;" title="SAVE PROCESS"> SAVE PROCESS <i class="fa fa-long-arrow-right" style="width:40px;"></i> </button>';
			
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
	$(document).ready(function(){
		
		
		let jenis_proses	= $('#type_bast').val();
		if(jenis_proses=='DEL'){
			$('#list_detail').find('tr').each(function(){
				let id_tr	= $(this).attr('id');
				const kodes	= id_tr.split('_');
				let nil		= $('#qty_real_'+kodes[2]).val();
				
				$('#qty_'+kodes[2]).spinner({					
					min: 0,
					max: parseInt(nil)
				 });
			});	
		}
		
	});
	
	$(document).on('click','#btn-back',(e)=>{
		let Code_SPK	= $('#spk_driver_id').val();
		loading_spinner();
		window.location.href =  base_url+'/'+active_controller+'/spk_detail_preview?spk='+encodeURIComponent(Code_SPK);
	});
	
	$(document).on('click','#btn-process-approve',(e)=>{
		
		e.preventDefault();
		$('#btn-back, #btn-process-approve').prop('disabled',true);
		let Code_SPK	= $('#spk_driver_id').val();
		let PIC_Name	= $('#pic').val();			
		if(PIC_Name == null || PIC_Name == '' || PIC_Name == '-'){
			swal({
			  title				: "Error Message !",
			  text				: 'Empty PIC Name. Please input PIC Name first...',						
			  type				: "warning"
			});
			$('#btn-back, #btn-process-approve').prop('disabled',false);
			return false;
		}
		
		let IntL	= 0;
		$('#list_detail').find('tr').each(function(){
			let code_rows		= $(this).attr('id');
			const split_code	= code_rows.split('_');
			let urut_rows		= split_code[2];
			let qty_chosen		= $('#qty_'+urut_rows).val();
			if(parseInt(qty_chosen) > 0){
				IntL++;
			}
		});	
		
		if(IntL == 0){
			swal({
			  title				: "Error Message !",
			  text				: 'Empty quantity. Please input quantity first...',						
			  type				: "warning"
			});
			$('#btn-back, #btn-process-approve').prop('disabled',false);
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
					var baseurl		= base_url +'/'+ active_controller+'/save_bast_spk_driver';
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
								window.location.href = base_url+'/'+active_controller+'/spk_detail_preview?spk='+encodeURIComponent(Code_SPK);
							}else{								
								swal({
								  title	: "Save Failed!",
								  text	: data.pesan,
								  type	: "warning"
								});									
								//alert(data.pesan);
								$('#btn-back, #btn-process-approve').prop('disabled',false);
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
							$('#btn-back, #btn-process-approve').prop('disabled',false);
							return false;
						}
					});
					
				} else {
					close_spinner_new();
					swal("Cancelled", "Data can be process again :)", "error");
					$('#btn-back, #btn-process-approve').prop('disabled',false);
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
