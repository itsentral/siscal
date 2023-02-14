
<form action="#" method="POST" id="form-proses-revisi" enctype="multipart/form-data">
	<div class="box box-warning">
		
		<div class="box-body">
			<div class="row">
				<div class="col-sm-12 text-center sub-heading" style="color:white;">
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
							<label class="control-label">SO No</label>
							<?php
								echo form_input(array('id'=>'no_so','name'=>'no_so','class'=>'form-control input-sm','readOnly'=>true),$rows_header->no_so);	
								
								echo form_input(array('id'=>'code_back','name'=>'code_back','type'=>'hidden'),$Code_Back);
							?>
						</div>
					</div>
					<div class="col-sm-6">
						<div class="form-group">
							<label class="control-label">SO Date</label>
							<?php
								echo form_input(array('id'=>'tgl_so','name'=>'tgl_so','class'=>'form-control input-sm','readOnly'=>true),date('d-m-Y',strtotime($rows_header->tgl_so)));								
							?>
						</div>
					</div>				
				</div>
				<div class='row'>
					<div class="col-sm-6">
						<div class="form-group">
							<label class="control-label">Quotation</label>
							<?php
								echo form_input(array('id'=>'nomor_quotation','name'=>'nomor_quotation','class'=>'form-control input-sm','readOnly'=>true),$rows_header->quotation_nomor);						
							?>
						</div>
					</div>
					<div class="col-sm-6">
						<div class="form-group">
							<label class="control-label">PO No</label>
							<?php
								echo form_input(array('id'=>'pono','name'=>'pono','class'=>'form-control input-sm','readOnly'=>true),$rows_header->pono);						
							?>
						</div>
					</div>				
				</div>
				<div class='row'>
					<div class="col-sm-6">
						<div class="form-group">
							<label class="control-label">Customer</label>
							<?php
								echo form_input(array('id'=>'customer','name'=>'customer','class'=>'form-control input-sm','readOnly'=>true),$rows_header->customer_name);								
							?>
						</div>
					</div>
					<div class="col-sm-6">
						&nbsp;
					</div>				
				</div>
				<div class="row">
					<div class="col-sm-12 text-center sub-heading" style="color:white;">
						<h5>CALIBRATION FILE</h5>
					</div>
					
				</div>
				<div class='row'>
					<div class="col-sm-6">
						<div class="form-group">
							<label class="control-label">Code <span class="text-red"> *</span></label>
							<?php
							echo form_input(array('id'=>'code_detail','name'=>'code_detail','class'=>'form-control input-sm','readOnly'=>true),$rows_detail->id);
							?>
						</div>
					</div>
					<div class="col-sm-6">
						<div class="form-group">
							<label class="control-label">Tool Name</label>
							<?php
								echo form_input(array('id'=>'tool_name','name'=>'tool_name','class'=>'form-control input-sm','readOnly'=>true),$rows_header->tool_name);						
							?>
						</div>
					</div>				
				</div>
				<div class='row'>
					<div class="col-sm-6">
						<div class="form-group">
							<label class="control-label">Tool Identification No <span class="text-red"> *</span></label>
							<?php
								echo form_input(array('id'=>'no_identifikasi','name'=>'no_identifikasi','class'=>'form-control input-sm','readOnly'=>true),$rows_detail->no_identifikasi);								
							?>
							
						</div>
					</div>	
					<div class="col-sm-6">
						<div class="form-group">
							<label class="control-label">Tool Serial Number No <span class="text-red"> *</span></label>
							<?php
								echo form_input(array('id'=>'no_serial_number','name'=>'no_serial_number','class'=>'form-control input-sm','readOnly'=>true),$rows_detail->no_serial_number);								
							?>
							
						</div>
					</div>
								
				</div>
				
				<div class='row'>
					<div class="col-sm-4">
						<div class="form-group">
							<label class="control-label">Calibration File <span class="text-red"> *</span></label>
							<input type="file" id="lampiran_kalibrasi" name="lampiran_kalibrasi" class="form-control input-sm" onchange="ValidateSingleInput(this);">
						</div>
					</div>
					<div class="col-sm-2">
						<div class="form-group">
							<label class="control-label">&nbsp;</label>
							<div>
								<?php
								echo '<a href="'.$this->file_attachement.'hasil_kalibrasi/'.$rows_detail->file_kalibrasi.'" target="_blank" class="btn btn-sm btn-danger" title="OLD FILE"> OLD FILE <i class="fa fa-download"></i> </a>';
								?>			
							</div>
						</div>
					</div>
					<div class="col-sm-6">
						<div class="form-group">
							<label class="control-label">Reason <span class="text-red"> *</span></label>
							<?php
								echo form_textarea(array('id'=>'failed_reason', 'name'=>'failed_reason','cols'=>'75','rows'=>'2', 'class'=>'form-control input-sm'));									
							?>
							
						</div>
					</div>
								
				</div>
				
			<?php
			}
			?>
										
		</div>		
		<?php
		if(!empty($rows_detail)){
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
			</div>";
			echo"<div class='footer'>";
				echo'<button type="button" id="btn-process-reopen" class="btn btn-md" style="background-color:#37474f; color:white;vertical-align:middle !important;" title="SAVE PROCESS"> SAVE PROCESS <i class="fa fa-long-arrow-right" style="width:40px;"></i> </button>';
			echo"</div>";
		}
		?>
		
	</div>
</form>

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
<script type="text/javascript">
	$(function() {		
		$('#loader_proses_save').hide();	
	});
	
	
</script>