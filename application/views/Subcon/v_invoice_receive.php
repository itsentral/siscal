<?php
$this->load->view('include/side_menu');
?> 
<form action="#" method="POST" id="form-proses-receive" enctype="multipart/form-data">
	<div class="box box-warning">
		
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
						<h5>DETAIL SEND & RECEIVE</h5>
					</div>
					
				</div>
				<div class='row'>
					<div class="col-sm-6">
						<label class="control-label">Send Date <span class="text-red">*</span></label>
						<?php
							echo form_input(array('id'=>'send_date','name'=>'send_date','class'=>'form-control input-sm tanggal','readOnly'=>true,'autocomplete'=>'off'));						
						?>
					</div>
					<div class="col-sm-6">
						<div class="form-group">
							<label class="control-label">Receive Date <span class="text-red">*</span></label>
							<?php
								echo form_input(array('id'=>'receive_date','name'=>'receive_date','class'=>'form-control input-sm tanggal','readOnly'=>true,'autocomplete'=>'off'));						
							?>
						</div>
					</div>
									
				</div>
				<div class='row'>
					<div class="col-sm-6">
						<label class="control-label">Receive By <span class="text-red">*</span></label>
						<?php
							echo form_input(array('id'=>'receive_by','name'=>'receive_by','class'=>'form-control input-sm','autocomplete'=>'off','style'=>'text-transform:uppercase;'));						
						?>
					</div>
					<div class="col-sm-6">&nbsp;</div>
									
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
									<th class="text-center">Tool Name</th>
									<th class="text-center">PO No</th>
									<th class="text-center">SO No</th>				
									<th class="text-center">Price</th>
									<th class="text-center">Qty</th>
									<th class="text-center">DPP</th>
									<th class="text-center">Discount</th>
									<th class="text-center">Total</th>
								</tr>
							</thead>
							<tbody id="list_detail">
								<?php
								if($rows_detail){
									$intL	= 0;
									foreach($rows_detail as $ketD=>$valD){
										$intL++;
										$Nomor_PO = $Nomor_SO = '- ';
										$Query_SO	= "SELECT no_so FROM letter_orders WHERE id = '".$valD->letter_order_id."'";
										$rows_SO	= $this->db->query($Query_SO)->result();
										if($rows_SO){
											$Nomor_SO	= $rows_SO[0]->no_so;
										}
										
										$Query_PO	= "SELECT pono FROM quotations WHERE id = '".$valD->quotation_id."'";
										$rows_PO	= $this->db->query($Query_PO)->result();
										if($rows_PO){
											$Nomor_PO	= $rows_PO[0]->pono;
										}
										
										echo"<tr>";										
											echo"<td align='left'>".$valD->tool_name."</td>";
											echo"<td align='center'>".$Nomor_PO."</td>";
											echo"<td align='center'>".$Nomor_SO."</td>";										
											echo"<td align='right'>".number_format($valD->price)."</td>";
											echo"<td align='center'>".number_format($valD->qty)."</td>";
											echo"<td align='right'>".number_format($valD->total_harga)."</td>";
											echo"<td align='right'>".number_format($valD->total_discount)."</td>";
											echo"<td align='right'>".number_format($valD->total_harga - $valD->total_discount)."</td>";
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
				&nbsp;&nbsp;&nbsp;<button type="button" id="btn-process-receive" class="btn btn-md" style="background-color:#37474f; color:white;vertical-align:middle !important;" title="SAVE PROCESS"> SAVE PROCESS <i class="fa fa-long-arrow-right" style="width:40px;"></i> </button>';
			
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
		
		$('#btn-back').click(function(){			
			loading_spinner();
			window.location.href =  base_url+'/'+active_controller;
		});
		$('.tanggal').datepicker({
			dateFormat  : 'yy-mm-dd',
			changeMonth : true,
			changeYear  : true,
			maxDate		: '+0d'
		});
	});
	
	
	
	$(document).on('click','#btn-process-receive',(e)=>{
		e.preventDefault();
		$('#btn-back, #btn-process-receive').prop('disabled',true);
		let Tgl_Kirim			= $('#send_date').val();
		let Tgl_Terima			= $('#receive_date').val();
		let User_Terima			= $('#receive_by').val();
		
		if(Tgl_Kirim == null || Tgl_Kirim == ''){
			swal({
			  title				: "Error Message !",
			  text				: 'Empty send date. Please input send date first...',						
			  type				: "warning"
			});
			$('#btn-back, #btn-process-receive').prop('disabled',false);
			return false;
		}
		
		if(Tgl_Terima == null || Tgl_Terima == ''){
			swal({
			  title				: "Error Message !",
			  text				: 'Empty receive date. Please input receive date first...',						
			  type				: "warning"
			});
			$('#btn-back, #btn-process-receive').prop('disabled',false);
			return false;
		}
		
		if(Tgl_Terima < Tgl_Kirim){
			swal({
			  title				: "Error Message !",
			  text				: 'Incorrect send date. send date should be less or same with receive date...',						
			  type				: "warning"
			});
			$('#btn-back, #btn-process-receive').prop('disabled',false);
			return false;
		}
		
		if(User_Terima == null || User_Terima == '' || User_Terima == '-'){
			swal({
			  title				: "Error Message !",
			  text				: 'Empty receiver. Please input receiver first...',						
			  type				: "warning"
			});
			$('#btn-back, #btn-process-receive').prop('disabled',false);
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
					var formData 	= new FormData($('#form-proses-receive')[0]);
					var baseurl		= base_url +'/'+ active_controller+'/update_receive';
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
								$('#btn-back, #btn-process-receive').prop('disabled',false);
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
							$('#btn-back, #btn-process-receive').prop('disabled',false);
							return false;
						}
					});
					
				} else {
					close_spinner_new();
					swal("Cancelled", "Data can be process again :)", "error");
					$('#btn-back, #btn-process-receive').prop('disabled',false);
					return false;
				}
		});
	});
	
	
</script>
