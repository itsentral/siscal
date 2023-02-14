
<form action="#" method="POST" id="form-proses-cancel" enctype="multipart/form-data">
	<div class="box box-warning">
		<div class="box-header">
			<h3 class="box-title">
				<i class="fa fa-envelope"></i> <?php echo('<span class="important">'.$title.'</span>'); ?>
			</h3>
			
			
		</div>
		<div class="box-body">
			<div class='row'>
				<div class="col-sm-6">
					<div class="form-group">
						<label class="control-label">Nomor Invoice</label>
						<?php
							echo form_input(array('id'=>'invoice_no','name'=>'invoice_no','class'=>'form-control input-sm','readOnly'=>true),$rows_header['invoice_no']);
							echo form_input(array('id'=>'code_inv_cancel','name'=>'code_inv_cancel','type'=>'hidden'),$rows_header['id']);
						?>
					</div>
				</div>
				<div class="col-sm-6">
					<div class="form-group">
						<label class="control-label">Tanggal Invoice</label>
						<?php
							echo form_input(array('id'=>'tgl_invoice','name'=>'tgl_bast','class'=>'form-control input-sm','readOnly'=>true),date('d F Y',strtotime($rows_header['datet'])));						
						?>
					</div>
				</div>				
			</div>
			<div class='row'>
				<div class="col-sm-6">
					<div class="form-group">
						<label class="control-label">Customer</label>
						<?php
							echo form_input(array('id'=>'customer_name','name'=>'customer_name','class'=>'form-control input-sm','readOnly'=>true),$rows_header['customer_name']);
							
						?>
					</div>
				</div>
				<div class="col-sm-6">
					<div class="form-group">
						<label class="control-label">Alamat</label>
						<?php
							echo form_textarea(array('id'=>'address', 'name'=>'address','cols'=>'75','rows'=>'2', 'class'=>'form-control input-sm','readOnly'=>true),$rows_header['address']);						
						?>
					</div>
				</div>				
			</div>
			
			<div class='row'>
				<div class="col-sm-6">
					<div class="form-group">
						<label class="control-label">DPP</label>
						<?php
							echo form_input(array('id'=>'inv_dpp','name'=>'inv_dpp','class'=>'form-control input-sm','readOnly'=>true),number_format($rows_header['dpp']));
							
						?>
					</div>
				</div>
				<div class="col-sm-6">
					<div class="form-group">
						<label class="control-label">Diskon</label>
						<?php
							echo form_input(array('id'=>'diskon','name'=>'diskon','class'=>'form-control input-sm','readOnly'=>true),number_format($rows_header['diskon']));
						?>
					</div>
				</div>
								
			</div>
			<div class='row'>
				<div class="col-sm-6">
					<div class="form-group">
						<label class="control-label">PPN</label>
						<?php
							echo form_input(array('id'=>'inv_ppn','name'=>'inv_ppn','class'=>'form-control input-sm','readOnly'=>true),number_format($rows_header['ppn']));
							
						?>
					</div>
				</div>
				<div class="col-sm-6">
					<div class="form-group">
						<label class="control-label">Grand Total</label>
						<?php
							
							echo form_input(array('id'=>'grand_tot','name'=>'grand_tot','class'=>'form-control input-sm','readOnly'=>true),number_format($rows_header['grand_tot']));
						?>
					</div>
				</div>
								
			</div>
			<div class='row'>
				<div class="col-sm-6">
					<div class="form-group">
						<label class="control-label">Cancel Reason</label>
						<?php
							echo form_textarea(array('id'=>'cancel_reason', 'name'=>'cancel_reason','cols'=>'75','rows'=>'2', 'class'=>'form-control input-sm'));
						?>
					</div>
				</div>
				<div class="col-sm-6">
					<div class="form-group">
						<label class="control-label"></label>
						<?php
							
						?>
					</div>
				</div>
								
			</div>
		</div>		
	</div>
	<div class="box box-primary">
		<div class="box-header">
			<h3 class="box-title">
				<i class="fa fa-refresh"></i> <?php echo('<span class="important">Data Detail</span>'); ?>
			</h3>			
		</div>
		<div class="box-body">
			<table class="table table-striped table-bordered" id="my-grid">
				<thead>
					<tr class="bg-blue">
						<th class="text-center">Nama Alat</th>
						<th class="text-center">No PO</th>				
						<th class="text-center">No SO</th>
						<th class="text-center">Harga</th>
						<th class="text-center">Qty</th>
						<th class="text-center">DPP</th>
						<th class="text-center">Diskon</th>
						<th class="text-center">Total</th>
					</tr>
				</thead>
				<tbody id="list_detail">
					<?php
					if($rows_detail){
						$intI		= 0;
						
						foreach($rows_detail as $ketD=>$valD){
							$intI++;
							$No_SO		= $No_PO	= '-';
							if(!empty($valD['letter_order_id']) && $valD['letter_order_id'] !== '-'){
								$Qry_SO		= "SELECT no_so FROM letter_orders WHERE id='".$valD['letter_order_id']."'";
								$det_SO		= $this->db->query($Qry_SO)->result();
								if($det_SO){
									$No_SO	= $det_SO[0]->no_so;
								}
							}
							
							if(!empty($valD['quotation_id']) && $valD['quotation_id'] !== '-'){
								$Qry_Quot	= "SELECT pono FROM quotations WHERE id='".$valD['quotation_id']."'";
								$det_Quot	= $this->db->query($Qry_Quot)->result();
								if($det_Quot){
									$No_PO	= $det_Quot[0]->pono;
								}
							}
							
							
							
							echo"<tr>";
								echo"<td class='text-left'>".$valD['tool_name']."</td>";
								echo"<td class='text-center'>".$No_PO."</td>";
								echo"<td class='text-center'>".$No_SO."</td>";
								echo"<td class='text-right'>".number_format($valD['price'])."</td>";
								echo"<td class='text-center'>".$valD['qty']."</td>";
								echo"<td class='text-right'>".number_format($valD['total_harga'])."</td>";
								echo"<td class='text-right'>".number_format($valD['total_discount'])."</td>";
								echo"<td class='text-right'>".number_format($valD['total_harga'] - $valD['total_discount'])."</td>";								
							echo"</tr>";
						}
					}
					
				echo"</tbody>";
				
				?>
			</table>
		</div>
		<div class="box-footer">
			<?php
				echo"<button type='button' class='btn btn-md' style='background-color:#37474f;color:white;' id='btn-save-cancel'>SAVE CANCEL INVOICE PROFORMA <i class='fa fa-save fa-lg' style='width:45px;'></i>  </button>";
				
			?>
		</div>
	</div>
</form>


<style>
	
</style>

<script>
	var base_url			= '<?php echo site_url(); ?>';
	var active_controller	= '<?php echo($this->uri->segment(1)); ?>';
	
	$(document).ready(function(){
		
	});
	
	
	$(document).on('click','#btn-save-cancel',function(e){
		$('#btn-save-cancel, #btn-close').prop('disabled',true);
		e.preventDefault();
		
		var alasan	= $('#cancel_reason').val();
		
		if(alasan == '' || alasan ==null || alasan =='-'){
			swal({
			  title				: "Error Message !",
			  text				: 'Empty cancel reason, please input cancel reason...',						
			  type				: "warning"
			});
			$('#btn-save-cancel, #btn-close').prop('disabled',false);
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
					var formData 	= new FormData($('#form-proses-cancel')[0]);
					var baseurl		= base_url +'/'+ active_controller+'/save_cancel_inv_proforma';
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
								$('#btn-save-cancel, #btn-close').prop('disabled',false);
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
							$('#btn-save-cancel, #btn-close').prop('disabled',false);
							return false;
						}
					});
					
				} else {
					close_spinner_new();
					swal("Cancelled", "Data can be process again :)", "error");
					$('#btn-save-cancel, #btn-close').prop('disabled',false);
					return false;
				}
		});		
	});
	
	
</script>