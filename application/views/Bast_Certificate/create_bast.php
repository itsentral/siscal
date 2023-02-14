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
							echo form_input(array('id'=>'nomor_bast','name'=>'nomor_bast','class'=>'form-control input-sm','disabled'=>true),'AUTOMATIC');						
						?>
					</div>
				</div>
				<div class="col-sm-6">
					<div class="form-group">
						<label class="control-label">Tanggal BAST</label>
						<?php
							echo form_input(array('id'=>'tgl_bast','name'=>'tgl_bast','class'=>'form-control input-sm','readOnly'=>true),date('Y-m-d'));						
						?>
					</div>
				</div>				
			</div>
			<div class='row'>
				<div class="col-sm-6">
					<div class="form-group">
						<label class="control-label">Customer</label>
						<?php
							echo form_input(array('id'=>'customer_name','name'=>'customer_name','class'=>'form-control input-sm','readOnly'=>true),$rows_cust[0]->name);
							echo form_input(array('id'=>'customer_id','name'=>'customer_id','type'=>'hidden'),$rows_cust[0]->id);
						?>
					</div>
				</div>
				<div class="col-sm-6">
					<div class="form-group">
						<label class="control-label">Alamat</label>
						<?php
							echo form_textarea(array('id'=>'address', 'name'=>'address','cols'=>'75','rows'=>'2', 'class'=>'form-control input-sm'),$rows_cust[0]->address);						
						?>
					</div>
				</div>				
			</div>
			
			<div class='row'>
				<div class="col-sm-6">
					<div class="form-group">
						<label class="control-label">Notes</label>
						<?php
							echo form_textarea(array('id'=>'notes', 'name'=>'notes','cols'=>'75','rows'=>'2', 'class'=>'form-control input-sm'));
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
						<td class="text-center"><input type="checkbox" id='chk_all'></td>
						<th class="text-center">Nama Alat</th>
						<th class="text-center">Tgl Kalibrasi</th>				
						<th class="text-center">Teknisi</th>
						<th class="text-center">No Sertifikat</th>
						<th class="text-center">Kategori</th>
						<th class="text-center">No PO</th>
						<th class="text-center">No SO</th>
						<th class="text-center">Keterangan</th>
						<th class="text-center">Inv Status</th>
					</tr>
				</thead>
				<tbody id="list_detail">
					<?php
					if($rows_detail){
						$intI		= 0;
						
						foreach($rows_detail as $ketD=>$valD){
							$intI++;
							$Tgl_Kalibrasi	= $valD->actual_process_date;
							if(empty($Tgl_Kalibrasi)){
								$Tgl_Kalibrasi	= $valD->plan_process_date;
							}
							$Labs			= $valD->labs;
							$Insitu			= $valD->insitu;
							$Subcon			= $valD->subcon;
							if($Labs === 'Y'){
								$warna	= '<span class="badge bg-green">Labs</span>';
							}else if($Insitu === 'Y'){
								$warna	= '<span class="badge bg-maroon">Insitu</span>';
							}else{
								$warna	= '<span class="badge bg-aqua">Subcon</span>';
							}
							
							$Status_Bayar	= '<span class="badge bg-red-active">UNPAID</span>';
							
							$Query_Invoice	= "SELECT
													head_inv.invoice_no
												FROM
													invoices head_inv
												INNER JOIN invoice_details det_inv ON head_inv.id = det_inv.invoice_id
												WHERE
													head_inv.grand_tot > 0
												AND det_inv.letter_order_id = '".$valD->letter_order_id."'
												AND det_inv.tipe = 'T'
												ORDER BY
													head_inv.invoice_no DESC
												LIMIT 1";
							$rows_Invoice	= $this->db->query($Query_Invoice)->result();
							if($rows_Invoice){
								$Nomor_Invoice	= $rows_Invoice[0]->invoice_no;
								
								## CEK JURNAL ##
								$Query_Jurnal	= "SELECT
														jurnalid
													FROM
														trans_jurnal_histories
													WHERE
														no_reff = '".$Nomor_Invoice."'
													AND tipe_jurnal = 'BUM'
													AND kredit > 0
													ORDER BY
														tgl_jurnal DESC
													LIMIT 1";
								$rows_Jurnal	= $this->db->query($Query_Jurnal)->result();
								if($rows_Jurnal){
									$Status_Bayar	= '<span class="badge bg-navy-active">PAID<br>'.$rows_Jurnal[0]->jurnalid.'</span>';
								}
							}
							
							echo"<tr id='tr_".$intI."'>";
								echo"<input type='hidden' name='det_Detail[".$intI."][tool_type]' value='".$valD->tool_type."'>";
								echo"<input type='hidden' name='det_Detail[".$intI."][tool_id]' value='".$valD->tool_id."'>";
								echo"<input type='hidden' name='det_Detail[".$intI."][tool_name]' value='".$valD->tool_name."'>";
								echo"<input type='hidden' name='det_Detail[".$intI."][merk]' value='".$valD->merk."'>";
								echo"<input type='hidden' name='det_Detail[".$intI."][certificate_no]' value='".$valD->no_sertifikat."'>";
								echo"<input type='hidden' name='det_Detail[".$intI."][no_identifikasi]' value='".$valD->no_identifikasi."'>";
								echo"<input type='hidden' name='det_Detail[".$intI."][letter_order_id]' value='".$valD->letter_order_id."'>";
								echo"<input type='hidden' name='det_Detail[".$intI."][quotation_id]' value='".$valD->quotation_id."'>";
								
								echo"<td class='text-center'>";
									echo form_checkbox(array('name'=>'det_Pilih['.$intI.']','id'=>'detail_id_'.$intI,'value'=>$valD->id,'checked'=>false));
								echo"</td>";
								echo"<td class='text-left'>".$valD->tool_name."</td>";
								echo"<td class='text-center'>".date('d M Y',strtotime($Tgl_Kalibrasi))."</td>";
								echo"<td class='text-center'>".$valD->name_teknisi."</td>";
								echo"<td class='text-left'>".$valD->no_sertifikat."</td>";
								echo"<td class='text-center'>".$warna."</td>";
								echo"<td class='text-center'>".$valD->pono."</td>";
								echo"<td class='text-center'>".$valD->no_so."</td>";
								echo"<td class='text-right'>";
									echo form_textarea(array('id'=>'descr_'.$intI, 'name'=>'det_Detail['.$intI.'][descr]','cols'=>'75','rows'=>'2', 'class'=>'form-control input-sm','readOnly'=>true),$valD->address_sertifikat);
								echo"</td>";
								echo"<td class='text-center'>".$Status_Bayar."</td>";
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
		$('.harga').maskMoney();
		$("#tgl_bast").datepicker({
			dateFormat	: 'yy-mm-dd',
			changeMonth	: true,
			changeYear	: true,
			maxDate		: "+7d",
			minDate		: "-1m"
		});
		$('#btn-back').click(function(){			
			loading_spinner();
			window.location.href =  base_url+'index.php/'+active_controller;
		});
		
	});
	$(document).on('click','#chk_all',function(e){		
		if($(this).is(':checked')){
			$('#list_detail input[type="checkbox"]:not(:checked)').trigger('click');
		}else{
			$('#list_detail input[type="checkbox"]:checked').trigger('click');
		}
		e.stopPropagation();
	});
	
	$(document).on('click','#btn-save',function(e){
		e.preventDefault();
		$('#btn-save, #btn-back').prop('disabled',true);
		var total	= $('#list_detail input[type="checkbox"]').filter(':checked').length;
		if(parseInt(total) <= 0){
			swal({
			  title				: "Error Message !",
			  text				: 'No Record was selected....',						
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
					var baseurl		= base_url +'index.php/'+ active_controller+'/bast_save_process';
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
