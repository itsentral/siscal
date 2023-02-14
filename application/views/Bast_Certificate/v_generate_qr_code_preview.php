<?php
$this->load->view('include/side_menu');
?> 
<form action="#" method="POST" id="form-proses" enctype="multipart/form-data">
	<div class="box box-warning">
		<div class="box-header">			
			<div class="box-tools pull-right">
				<?php 
					echo"<button type='button' class='btn btn-md btn-danger' id='btn-back'> <i class='fa fa-angle-double-left'></i> BACK </button>&nbsp;&nbsp;&nbsp;";			
				?>
			</div>
		</div> 
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
				$rows_Quotation	= $this->db->get_where('quotations',array('id'=>$rows_header[0]->quotation_id))->result();
			?>
				<div class='row'>
					<div class="col-sm-6">
						<div class="form-group">
							<label class="control-label">SO No</label>
							<?php
								echo form_input(array('id'=>'nomor_so','name'=>'nomor_so','class'=>'form-control input-sm','readOnly'=>true),$rows_so[0]->no_so);						
							?>
						</div>
					</div>
					<div class="col-sm-6">
						<div class="form-group">
							<label class="control-label">SO Date</label>
							<?php
								echo form_input(array('id'=>'tgl_so','name'=>'tgl_so','class'=>'form-control input-sm','readOnly'=>true),date('d-m-Y',strtotime($rows_so[0]->tgl_so)));						
							?>
						</div>
					</div>				
				</div>
				<div class='row'>
					<div class="col-sm-6">
						<div class="form-group">
							<label class="control-label">Customer</label>
							<?php
								echo form_input(array('id'=>'customer_name','name'=>'customer_name','class'=>'form-control input-sm','readOnly'=>true),$rows_so[0]->customer_name);						
							?>
						</div>
					</div>
					<div class="col-sm-6">
						<div class="form-group">
							<label class="control-label">Address</label>
							<?php
								echo form_textarea(array('id'=>'alamat','name'=>'alamat','class'=>'form-control input-sm','readOnly'=>true,'cols'=>75, 'rows'=>2),$rows_so[0]->address);						
							?>
						</div>
					</div>				
				</div>
				<div class='row'>
					<div class="col-sm-6">
						<div class="form-group">
							<label class="control-label">Quotation</label>
							<?php
								echo form_input(array('id'=>'nomor_quotation','name'=>'nomor_quotation','class'=>'form-control input-sm','readOnly'=>true),$rows_Quotation[0]->nomor);						
							?>
						</div>
					</div>
					<div class="col-sm-6">
						<div class="form-group">
							<label class="control-label">PO No</label>
							<?php
								echo form_input(array('id'=>'pono','name'=>'pono','class'=>'form-control input-sm','readOnly'=>true),$rows_Quotation[0]->pono);						
							?>
						</div>
					</div>				
				</div>
				<div class="row">
					<div class="col-sm-12 col-xs-12 text-center sub-heading" style="color:white;">
						<h5>DETAIL SO</h5>
					</div>
					
				</div>
				<div class="row">
					<div class="col-sm-12" style="overflow-x:scroll !important;">
						<table class="table table-striped table-bordered" id="my-grid">
							<thead>
								<tr class="bg-navy-active">
									<th class="text-center">Tool Name</th>
									<th class="text-center">Qty</th>				
									<th class="text-center">Lab</th>
									<th class="text-center">Insitu</th>
									<th class="text-center">Subcon</th>
									<th class="text-center">Description</th>
									<th class="text-center">Area Insitu</th>
								</tr>
							</thead>
							<tbody id="list_detail">
								<?php
								$rows_det_SO	= $this->db->get_where('letter_order_details',array('letter_order_id'=>$rows_so[0]->id))->result();
								if($rows_det_SO){
									foreach($rows_det_SO as $ketD=>$valD){
										$subcon	='-';
										$labs	='-';
										$insitu	='-';
										if($valD->tipe == 'Insitu'){
											$insitu	='Y';
										}else if($valD->tipe == 'Labs'){
											$labs	='Y';
										}else if($valD->tipe == 'Subcon'){
											$subcon	='Y';
										}
										echo"<tr>";										
											echo"<td align='left'>".$valD->tool_name."</td>";
											echo"<td align='center'>".$valD->qty."</td>";
											echo"<td align='center'>".$labs."</td>";										
											echo"<td align='center'>".$insitu."</td>";
											echo"<td align='center'>".$subcon."</td>";
											echo"<td align='left'>".$valD->descr."</td>";
											echo"<td align='center'>".$valD->delivery_name."</td>";
										echo"</tr>";
									}
								}
								?>
							</tbody>
						</table>
					</div>
				</div>
				<div class="row">
					<div class="col-sm-12 col-xs-12 text-center sub-heading" style="color:white;">
						<h5>DETAIL CALIBRATION</h5>
					</div>
					<div class="col-sm-12 col-xs-12">&nbsp;</div>
				</div>
				<div class="row">
					<div class="col-sm-12" style="overflow-x:scroll !important;">
						<table class="table table-striped table-bordered" id="my-grid">
							<thead>
								<tr style="background-color:#16697A !important;color:white !important;">
									<th class="text-center">Code</th>
									<th class="text-center">Tool Code</th>				
									<th class="text-center">Name Tool</th>
									<th class="text-center">Cal Result</th>
									<th class="text-center">Certificate No</th>
									<th class="text-center">Valid Until</th>
									<th class="text-center">No Identify</th>
									<th class="text-center">Action</th>
								</tr>
							</thead>
							<tbody id="list_sertifikat">
								<?php
								//echo"<pre>";print_r($rows_detail);exit;
								if($rows_detail){
									foreach($rows_detail as $ketK=>$valK){
										$Template	= '-';
										if($valK->flag_proses=='Y' && ($valK->no_sertifikat == '' || $valK->no_sertifikat == '-')){
											$Template = '<button type="button" class="btn btn-sm bg-navy-active" onClick = "ActionPreview({code:\''.$valK->id.'\',action :\'generate_qrcode_tool\',title:\'GENERATE QR CODE\'});" title="GENERATE QR CODE"> <i class="fa fa-gear"></i> </button>';
											
											
										}
										echo"<tr>";										
											echo"<td class='text-center'>".$valK->id."</td>";
											echo"<td class='text-center'>".$valK->tool_id."</td>";
											echo"<td class='text-left'>".$valK->tool_name."</td>";
											echo"<td class='text-center'>";
												if($valK->flag_proses == 'Y'){
													echo "<span class='badge bg-green'>SUCCESS</span>";
												}else if ($valK->flag_proses == 'N'){
													echo "<span class='badge bg-red'>FAIL / CANCEL</span>";
												}else{
													echo "<span class='badge bg-purple'>UNPROCESSED</span>";
												}
											echo"</td>";
											echo"<td class='text-center'>";
												$sertifikat			= '-';
												if($valK->no_sertifikat !='' && $valK->no_sertifikat !='-'){
													$sertifikat		= $valK->no_sertifikat;
												}
												echo $sertifikat;
											echo"</td>";
											echo"<td class='text-center'>";
												$valid			= '-';
												if($valK->valid_until !='' && $valK->valid_until !='0000-00-00' && $valK->valid_until !='1970-01-01'){
													$valid		= date('d-m-Y',strtotime($valK->valid_until));
												}
												echo $valid;
											echo"</td>";
											echo"<td class='text-left'>".$valK->no_identifikasi."</td>";
											echo"<td class='text-center'>".$Template."</td>";	
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
			?>
										
		</div>		
	
		
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
</style>
<script>
	var base_url			= '<?php echo site_url(); ?>';
	var active_controller	= '<?php echo($this->uri->segment(1)); ?>';
	var _validFileExtensions = [".pdf",".PDF"];
	$(document).ready(function(){
		
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
	
	$(document).on('click','#btn-process-reopen',(e)=>{
		e.preventDefault();
		$('#btn-modal-close, #btn-process-reopen').prop('disabled',true);
		let Lampiran_File	= $('#lampiran_reopen').val();
		let CodeDetail		= $('#code_detail').val();
		let CodeOrder		= $('#code_so').val();
		
		
		if(Lampiran_File == null || Lampiran_File == ''){
			swal({
			  title				: "Error Message !",
			  text				: 'Empty certificate file. Please upload certificate file first...',						
			  type				: "warning"
			});
			$('#btn-modal-close, #btn-process-reopen').prop('disabled',false);
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
					var formData 	= new FormData($('#form-proses-reopen')[0]);
					var baseurl		= base_url +'/'+ active_controller+'/save_generate_qrcode_sertifikat';
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
								//window.location.href = base_url +'/'+ active_controller+'/view_detail_tool?noso='+encodeURIComponent(CodeOrder);
							}else{								
								swal({
								  title	: "Save Failed!",
								  text	: data.pesan,
								  type	: "warning"
								});									
								//alert(data.pesan);
								$('#btn-modal-close, #btn-process-reopen').prop('disabled',false);
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
							$('#btn-modal-close, #btn-process-reopen').prop('disabled',false);
							return false;
						}
					});
					
				} else {
					close_spinner_new();
					swal("Cancelled", "Data can be process again :)", "error");
					$('#btn-modal-close, #btn-process-reopen').prop('disabled',false);
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
	
	
</script>
