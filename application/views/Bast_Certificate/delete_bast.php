<?php
$this->load->view('include/side_menu');
?> 
<form action="#" method="POST" id="form-proses" enctype="multipart/form-data">
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
						<label class="control-label">Nomor</label>
						<?php
							echo form_input(array('id'=>'nomor_bast','name'=>'nomor_bast','class'=>'form-control input-sm','readOnly'=>true),$rows_header['nomor']);
							echo form_input(array('id'=>'id','name'=>'id','type'=>'hidden'),$rows_header['id']);
						?>
					</div>
				</div>
				<div class="col-sm-6">
					<div class="form-group">
						<label class="control-label">Tanggal BAST</label>
						<?php
							echo form_input(array('id'=>'tgl_bast','name'=>'tgl_bast','class'=>'form-control input-sm','readOnly'=>true),date('d F Y',strtotime($rows_header['datet'])));						
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
						<label class="control-label">Pengirim</label>
						<?php
							echo form_input(array('id'=>'send_by','name'=>'send_by','class'=>'form-control input-sm'),$rows_header['send_by']);
							
						?>
					</div>
				</div>
				<div class="col-sm-6">
					<div class="form-group">
						<label class="control-label">Notes</label>
						<?php
							echo form_textarea(array('id'=>'notes', 'name'=>'notes','cols'=>'75','rows'=>'2', 'class'=>'form-control input-sm','readOnly'=>true),$rows_header['descr']);
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
						<td class="text-center">No</td>
						<th class="text-center">Nama Alat</th>
						<th class="text-center">Merk</th>				
						<th class="text-center">Tipe</th>
						<th class="text-center">No Identifikasi</th>
						<th class="text-center">No Sertifikat</th>
						<th class="text-center">No PO</th>
						<th class="text-center">No SO</th>
					</tr>
				</thead>
				<tbody id="list_detail">
					<?php
					if($rows_detail){
						$intI		= 0;
						
						foreach($rows_detail as $ketD=>$valD){
							$intI++;
							$Qry_SO		= "SELECT no_so FROM letter_orders WHERE id='".$valD['letter_order_id']."'";
							$det_SO		= $this->db->query($Qry_SO)->result();
							
							$Qry_Quot	= "SELECT pono FROM quotations WHERE id='".$valD['quotation_id']."'";
							$det_Quot	= $this->db->query($Qry_Quot)->result();
							
							echo"<tr>";
								
								echo"<td class='text-center'>".$intI."</td>";
								echo"<td class='text-left'>".$valD['tool_name']."</td>";
								echo"<td class='text-left'>".$valD['merk']."</td>";
								echo"<td class='text-left'>".$valD['tool_type']."</td>";
								echo"<td class='text-center'>".$valD['no_identifikasi']."</td>";
								echo"<td class='text-left'>".$valD['certificate_no']."</td>";
								echo"<td class='text-center'>".$det_Quot[0]->pono."</td>";
								echo"<td class='text-center'>".$det_SO[0]->no_so."</td>";
								
							echo"</tr>";
						}
					}
					
				echo"</tbody>";
				
				?>
			</table>
		</div>
		<div class="box-footer">
			<?php
				echo"<button type='button' class='btn btn-md btn-danger' id='btn-back'> <i class='fa fa-angle-double-left'></i> BACK </button>&nbsp;&nbsp;&nbsp;";	
				echo"<button type='button' class='btn btn-md btn-success' id='btn-save'>SAVE PROCESS <i class='fa fa-refresh'></i>  </button>";	
				
			?>
		</div>
	</div>
</form>
<?php $this->load->view('include/footer'); ?>
<style>
	
</style>
<script>
	var base_url			= '<?php echo base_url(); ?>';
	var active_controller	= '<?php echo($this->uri->segment(1)); ?>';
	
	$(document).ready(function(){
		
		$('#btn-back').click(function(){			
			loading_spinner();
			window.location.href =  base_url+'index.php/'+active_controller;
		});
		
	});
	
	
	$(document).on('click','#btn-save',function(e){
		$('#btn-save, #btn-back').prop('disabled',true);
		e.preventDefault();
		
		var alasan	= $('#cancel_reason').val();
		
		if(alasan == '' || alasan ==null || alasan =='-'){
			swal({
			  title				: "Error Message !",
			  text				: 'Empty cancel reason, please input cancel reason...',						
			  type				: "warning"
			});
			$('#btn-save, #btn-back').prop('disabled',false);
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
					var baseurl		= base_url +'index.php/'+ active_controller+'/cancel_bast';
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
								window.location.href = base_url +'index.php/'+ active_controller;
							}else{								
								swal({
								  title	: "Save Failed!",
								  text	: data.pesan,
								  type	: "warning"
								});									
								//alert(data.pesan);
								$('#btn-save, #btn-back').prop('disabled',false);
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
							$('#btn-save, #btn-back').prop('disabled',false);
							return false;
						}
					});
					
				} else {
					close_spinner_new();
					swal("Cancelled", "Data can be process again :)", "error");
					$('#btn-save, #btn-back').prop('disabled',false);
					return false;
				}
		});		
	});
	
	
</script>

