<?php
$this->load->view('include/side_menu');
?> 
<form action="#" method="POST" id="form-proses-follow" enctype="multipart/form-data">
	<div class="box box-warning">
		<div class="box-header">
			<div class="box-tools">
				<button type="button" class="btn btn-md btn-danger" id="btn-back"> <i class="fa fa-long-arrow-left"></i> BACK </button>
			</div>
		</div>
		<div class="box-body">
			<div class="row">
				<div class="col-sm-12 col-xs-12 text-center sub-heading" style="color:white;">
					<h5>DETAIL INVOICE</h5>
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
							<label class="control-label">Invoice No</label>
							<?php
								echo form_input(array('id'=>'nomor_invoice','name'=>'nomor_invoice','class'=>'form-control input-sm','readOnly'=>true),$rows_header[0]->invoice_no);	
								echo form_input(array('id'=>'code_invoice','name'=>'code_invoice','class'=>'form-control input-sm','type'=>'hidden'),$rows_header[0]->id);
							?>
						</div>
					</div>
					<div class="col-sm-6">
						<div class="form-group">
							<label class="control-label">Invoice Date</label>
							<?php
								echo form_input(array('id'=>'tgl_invoice','name'=>'tgl_invoice','class'=>'form-control input-sm','readOnly'=>true),date('d-m-Y',strtotime($rows_header[0]->datet)));						
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
							<label class="control-label">Address</label>
							<?php
								echo form_textarea(array('id'=>'alamat','name'=>'alamat','class'=>'form-control input-sm','readOnly'=>true,'cols'=>75, 'rows'=>2),$rows_header[0]->address);						
							?>
						</div>
					</div>				
				</div>
				<div class='row'>
					<div class="col-sm-6">
						<div class="form-group">
							<label class="control-label">DPP</label>
							<?php
								echo form_input(array('id'=>'dpp','name'=>'dpp','class'=>'form-control input-sm','readOnly'=>true),number_format($rows_header[0]->dpp));						
							?>
						</div>
					</div>
					<div class="col-sm-6">
						<div class="form-group">
							<label class="control-label">Discount</label>
							<?php
								echo form_input(array('id'=>'diskon','name'=>'diskon','class'=>'form-control input-sm','readOnly'=>true),number_format($rows_header[0]->diskon));						
							?>
						</div>
					</div>				
				</div>
				<div class='row'>
					<div class="col-sm-6">
						<div class="form-group">
							<label class="control-label">VAT (PPN)</label>
							<?php
								echo form_input(array('id'=>'ppn','name'=>'ppn','class'=>'form-control input-sm','readOnly'=>true),number_format($rows_header[0]->ppn));						
							?>
						</div>
					</div>
					<div class="col-sm-6">
						<div class="form-group">
							<label class="control-label">Total</label>
							<?php
								echo form_input(array('id'=>'grand_tot','name'=>'grand_tot','class'=>'form-control input-sm','readOnly'=>true),number_format($rows_header[0]->grand_tot));						
							?>
						</div>
					</div>				
				</div>
				
				<div class="row">
					<div class="col-sm-12 col-xs-12 text-center sub-heading" style="color:white;">
						<h5>DETAIL FOLLOW UP PAYMENT </h5>
					</div>					
				</div>
				<div class='row'>
					<div class="col-sm-12 text-right">
						<button type="button" id="btn-add-follow" class="btn btn-md" style="background-color:#F66B0E; color:white;vertical-align:middle !important;" title="ADD FOLLOW UP"> ADD FOLLOW UP <i class="fa fa-plus" style="width:40px;"></i> </button>
						
					</div>
					<div class="col-sm-12">&nbsp;</div>				
				</div>
				<div class="row">
					<div class="col-sm-12" style="overflow-x:scroll !important;">
						<table class="table table-striped table-bordered" id="my-grid">
							<thead>
								<tr class="bg-navy-active">
									<th class="text-center">Date</th>
									<th class="text-center">Follow Up<br>By</th>
									<th class="text-center">Status</th>				
									<th class="text-center">Description</th>
									<th class="text-center">plan Date<br>Payment</th>
									<th class="text-center">Action</th>
								</tr>
							</thead>
							<tbody id="list_detail">
								<?php
								if($rows_detail){
									$intL	= 0;
									foreach($rows_detail as $ketD=>$valD){
										$intL++;
										$FollowUp_Status	= $valD->status;
										$Ket_Status		='-';
										if($FollowUp_Status == 'OK'){
											$Ket_Status		='<span class="badge bg-green">OK</span>';
										}else if($FollowUp_Status == 'TROUBLE'){
											$Ket_Status		='<span class="badge bg-maroon">BERMASALAH</span>';
										}
										
										$Plan_Payment = '-';
										if($valD->plan_paid){
											$Plan_Payment = date('d M Y',strtotime($valD->plan_paid));
										}	
										
										$Action = '-';
										if($intL == 1){
											$Action = "<button class='btn btn-sm btn-danger' id='btn_delete' onClick='deleteDet(\"".$rows_header[0]->id."\",\"".$valD->id."\");' data-role='qtip' title='Delete Follow Up'><i class='fa fa-trash-o'></i></button>";
										}
										echo"<tr>";										
											echo"<td align='center'>".$valD->follow_up_date."</td>";
											echo"<td align='center'>".$valD->follow_up_by."</td>";
											echo"<td align='center'>".$Ket_Status."</td>";										
											echo"<td align='left' class='text-wrap'>".$valD->descr."</td>";
											echo"<td align='center'>".$Plan_Payment."</td>";
											echo"<td align='center'>".$Action."</td>";
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
		
		?>
		
	</div>
</form>
<div class="modal fade" id="MyFollow" >
	<div class="modal-dialog" style="width:45%">
		<div class="modal-content">
			<form action="#" method="POST" id="form-add-follow" enctype="multipart/form-data">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-label="Close" id="btn_close"><span aria-hidden="true">&times;</span></button>
					<h4 class="modal-title">ADD FOLLOW UP</h4>
				</div>
				<div class="modal-body">
					<div id='alert_pesan'>

					</div>
					<div class='box box-success'>
						<div class='box-body'>
							<div class="row">
								<div class="col-sm-12">
									<div class="form-group">
										<label class="control-label">Invoice No</label>
										<?php
											echo form_input(array('id'=>'inv_no','name'=>'inv_no','class'=>'form-control input-sm','readOnly'=>true),$rows_header[0]->invoice_no);	
											echo form_input(array('id'=>'id_invoice','name'=>'id_invoice','class'=>'form-control input-sm','type'=>'hidden'),$rows_header[0]->id);						
										?>
									</div>
								</div>
							</div>
							
							<div class="row">
								<div class="col-sm-12">
									<div class="form-group">
										<label class="control-label">Invoice Date</label>
										<?php
											echo form_input(array('id'=>'inv_tgl','name'=>'inv_tgl','class'=>'form-control input-sm','readOnly'=>true),date('d M Y',strtotime($rows_header[0]->datet)));						
										?>
									</div>
								</div>
							</div>
							
							<div class="row">
								<div class="col-sm-12">
									<div class="form-group">
										<label class="control-label">Contact Person <span class="text-red">*</span></label>
										<?php
											echo form_input(array('id'=>'contact_person','name'=>'contact_person','class'=>'form-control input-sm','autocomplete'=>'off','style'=>'text-transform:uppercase;'));						
										?>
									</div>
								</div>
							</div>
							
							<div class="row">
								<div class="col-sm-12">
									<div class="form-group">
										<label class="control-label">Plan Payment Date <span class="text-red">*</span></label>
										<?php
											echo form_input(array('id'=>'plan_paid','name'=>'plan_paid','class'=>'form-control input-sm','autocomplete'=>'off','readOnly'=>true));						
										?>
									</div>
								</div>
							</div>
							<div class="row">
								<div class="col-sm-12">
									<div class="form-group">
										<label class="control-label">Trouble ? <span class="text-red">*</span></label>
										<div class="form-check-inline">							
											<label class="form-check-label">
												<input type="checkbox" class="form-check-input" name="trouble" id="trouble" value="Y"> <span class="text-green"><b>YES</b></span>
											</label>
										</div>
									</div>
								</div>
							</div>
							<div class="row">
								<div class="col-sm-12">
									<div class="form-group">
										<label class="control-label">Description <span class="text-red">*</span></label>
										<?php
											echo form_textarea(array('id'=>'keterangan','name'=>'keterangan','class'=>'form-control input-sm','cols'=>75, 'rows'=>2));						
										?>
									</div>
								</div>
							</div>							
						</div>
					</div>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-primary" id="save_follow">PROCESS FOLLOW UP</button>
					<button type="button" class="btn btn-default" id="btn_kembali" data-dismiss="modal" id="btn-back">CLOSE</button>
				</div>
			</form>
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
	.text-wrap{
		word-wrap: break-word !important;
	}
</style>
<script>
	var base_url			= '<?php echo site_url(); ?>';
	var active_controller	= '<?php echo($this->uri->segment(1)); ?>';
	
	const deleteDet =(CodeInvoice,CodeDelete)=>{
		$('#btn_delete, #btn-back').prop('disabled',true);
		swal({
			  title: "Are you sure?",
			  text: "You want to delete this data!",
			  type: "warning",
			  showCancelButton: true,
			  confirmButtonClass: "btn-danger",
			  confirmButtonText: "Yes, Processasr it!",
			  cancelButtonText: "No, cancel process!",
			  closeOnConfirm: true,
			  closeOnCancel: false
			},
			function(isConfirm) {					
				if (isConfirm) {
					loading_spinner_new();
					
					let baseurl		= base_url +'/'+ active_controller+'/delete_invoice_follow_up';
					$.ajax({
						url			: baseurl,
						type		: "POST",
						data		: {'kode_follow':CodeDelete,'invoice':CodeInvoice},
						cache		: false,
						dataType	: 'json',				
						success		: function(data){
							close_spinner_new();
							if(data.status == 1){											
								swal({
									  title	: "Save Success!",
									  text	: data.pesan,
									  type	: "success"
									});
								window.location.href = base_url +'/'+ active_controller+'/follow_up/'+CodeInvoice;
							}else{								
								swal({
								  title	: "Save Failed!",
								  text	: data.pesan,
								  type	: "warning"
								});									
								//alert(data.pesan);
								$('#btn_delete, #btn-back').prop('disabled',false);
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
							$('#btn_delete, #btn-back').prop('disabled',false);
							return false;
						}
					});
					
				} else {
					close_spinner_new();
					swal("Cancelled", "Data can be process again :)", "error");
					$('#btn_delete, #btn-back').prop('disabled',false);
					return false;
				}
		});
	}
	$(document).ready(function(){
		
		$('#btn-back').click(function(){			
			loading_spinner();
			window.location.href =  base_url+'/'+active_controller;
		});
		$('#plan_paid').datepicker({
			dateFormat  : 'yy-mm-dd',
			changeMonth : true,
			changeYear  : true,
			minDate		: '+0d'
		});
	});
	
	$(document).on('click','#btn-add-follow',()=>{
		$('#contact_person').val('');
		$('#plan_paid').val('');
		$('#keterangan').val('');
		$('#MyFollow').modal('show');
	});
	
	$(document).on('click','#save_follow',(e)=>{
		e.preventDefault();
		$('#btn_kembali, #save_follow').prop('disabled',true);
		let Tgl_Bayar			= $('#plan_paid').val();
		let User_PIC			= $('#contact_person').val();
		let Code_Inv			= $('#id_invoice').val();
		if(User_PIC == null || User_PIC == '' || User_PIC == '-'){
			swal({
			  title				: "Error Message !",
			  text				: 'Empty contact person. Please input contact person first...',						
			  type				: "warning"
			});
			$('#btn_kembali, #save_follow').prop('disabled',false);
			return false;
		}
		
		if(Tgl_Bayar == null || Tgl_Bayar == ''){
			swal({
			  title				: "Error Message !",
			  text				: 'Empty plan payment date. Please input plan payment date first...',						
			  type				: "warning"
			});
			$('#btn_kembali, #save_follow').prop('disabled',false);
			return false;
		}
		
		if($('#trouble').is(':checked')){
			let Description = $('#keterangan').val();
			if(Description == null || Description == '' || Description == '-'){
				swal({
				  title				: "Error Message !",
				  text				: 'Empty desciption. Please input description first...',						
				  type				: "warning"
				});
				$('#btn_kembali, #save_follow').prop('disabled',false);
				return false;
			}
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
					var formData 	= new FormData($('#form-add-follow')[0]);
					var baseurl		= base_url +'/'+ active_controller+'/add_invoice_follow_up';
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
								window.location.href = base_url +'/'+ active_controller+'/follow_up/'+Code_Inv;
							}else{								
								swal({
								  title	: "Save Failed!",
								  text	: data.pesan,
								  type	: "warning"
								});									
								//alert(data.pesan);
								$('#btn_kembali, #save_follow').prop('disabled',false);
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
							$('#btn_kembali, #save_follow').prop('disabled',false);
							return false;
						}
					});
					
				} else {
					close_spinner_new();
					swal("Cancelled", "Data can be process again :)", "error");
					$('#btn_kembali, #save_follow').prop('disabled',false);
					return false;
				}
		});
	});
	
	
</script>
