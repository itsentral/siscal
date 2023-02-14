<?php
$this->load->view('include/side_menu'); 

?> 
<form action="#" method="POST" id="form-proses">
	<div class="box box-warning">
		<div class="box-header">
			<h3 class="box-title">
				<i class="fa fa-envelope"></i> <?php echo('<span class="important">'.$title.'</span>'); ?>
			</h3>
			<div class="box-tool pull-right">
				<?php
					echo"<button type='button' class='btn btn-md btn-danger' id='btn-back'> <i class='fa fa-angle-double-left'></i> BACK </button>";	
				?>
			</div>
		</div>
		<div class="box-body">
			<div class='form-group row'>			
				<label class='label-control col-sm-2'><b>No VoC <span class='text-red'>*</span></b></label> 
				<div class='col-sm-4'>
					<?php
						echo form_input(array('id'=>'nomor','name'=>'nomor','class'=>'form-control input-sm','readOnly'=>true),$rows_header[0]['nomor']);
					?>							
				</div>
				<label class='label-control col-sm-2'><b>Date VoC <span class='text-red'>*</span></b></label>
				<div class='col-sm-4'>
					<?php
						echo form_input(array('id'=>'datet','name'=>'datet','class'=>'form-control input-sm','readOnly'=>true),date('d F Y',strtotime($rows_header[0]['datet'])));
					?>							
				</div>				
			</div>
			<div class='form-group row'>			
				<label class='label-control col-sm-2'><b>Receive By <span class='text-red'>*</span></b></label> 
				<div class='col-sm-4'>
					<?php
						echo form_input(array('id'=>'rec_by','name'=>'rec_by','class'=>'form-control input-sm','readOnly'=>true),$rows_header[0]['rec_by']);
					?>							
				</div>
				<label class='label-control col-sm-2'><b>Customer <span class='text-red'>*</span></b></label>
				<div class='col-sm-4'>
					<?php
						echo form_input(array('id'=>'customer_id','name'=>'customer_id','class'=>'form-control input-sm','readOnly'=>true),$rows_header[0]['customer_name']);
					?>							
				</div>				
			</div>
			<div class='form-group row'>			
				<label class='label-control col-sm-2'><b>No Order <span class='text-red'>*</span></b></label> 
				<div class='col-sm-4'>
					<?php
						echo form_input(array('id'=>'noso','name'=>'noso','class'=>'form-control input-sm','readOnly'=>true),$rows_header[0]['no_so']);
					?>							
				</div>
				<label class='label-control col-sm-2'><b>PIC Name <span class='text-red'>*</span></b></label>
				<div class='col-sm-4'>
					<?php
						echo form_input(array('id'=>'pic_name','name'=>'pic_name','class'=>'form-control input-sm','readOnly'=>true),$rows_header[0]['pic_name']);
					?>							
				</div>				
			</div>
			<div class='form-group row'>			
				<label class='label-control col-sm-2'><b>PIC Phone <span class='text-red'>*</span></b></label> 
				<div class='col-sm-4'>
					<?php
						echo form_input(array('id'=>'pic_phone','name'=>'pic_phone','class'=>'form-control input-sm','readOnly'=>true),$rows_header[0]['pic_phone']);
					?>							
				</div>
				<label class='label-control col-sm-2'><b>PIC Email <span class='text-red'>*</span></b></label>
				<div class='col-sm-4'>
					<?php
						echo form_input(array('id'=>'pic_email','name'=>'pic_email','class'=>'form-control input-sm','readOnly'=>true),$rows_header[0]['pic_email']);
					?>							
				</div>				
			</div>
			<div class='form-group row'>			
				<label class='label-control col-sm-2'><b>Plan Close <span class='text-red'>*</span></b></label> 
				<div class='col-sm-4'>
					<?php
						echo form_input(array('id'=>'plan_close','name'=>'plan_close','class'=>'form-control input-sm','readOnly'=>true),date('d F Y',strtotime($rows_header[0]['plan_close'])));
					?>							
				</div>
				<label class='label-control col-sm-2'><b>Status <span class='text-red'>*</span></b></label> 
				<div class='col-sm-4'>
					<?php
						$sts_VOC	= $rows_header[0]['sts_voc'];
						if($sts_VOC === 'OPN'){
							$Class_sts	= 'bg-green';
							$Ket_sts	= 'OPEN';
						}else if($sts_VOC === 'CNC'){
							$Class_sts	= 'bg-red';
							$Ket_sts	= 'CANCELED';
						}else if($sts_VOC === 'PRG'){
							$Class_sts	= 'bg-blue';
							$Ket_sts	= 'ON PROGRESS';
						}else if($sts_VOC === 'CLS'){
							$Class_sts	= 'bg-orange';
							$Ket_sts	= 'CLOSE JOB';
						}else if($sts_VOC === 'CLA'){
							$Class_sts	= 'bg-purple';
							$Ket_sts	= 'CLOSE VoC';
						}else if($sts_VOC === 'FOL'){
							$Class_sts	= 'bg-maroon';
							$Ket_sts	= 'FOLLOW UP';
						}
						echo"<span class='badge ".$Class_sts."'>".$Ket_sts."</span>";
					?>							
				</div>				
			</div>
		</div>
		<div class="box-body">
			<div class="box box-primary">
				<div class="box-header">
					<h4 class="box-title">Detail Complain</h4>
					
				</div>
				<div class="">
					<table id="my-grid" class="table table-bordered table-striped">
						<thead>					
							<tr class="bg-blue">
								<th class="text-center">Description VoC</th>
								<th class="text-center">Plan Close</th>
								<th class="text-center">Status</th>
							</tr>
						</thead>

						<tbody id="list_detail">
							<?php
							if($rows_detail){
								foreach($rows_detail as $key=>$vals){
									$Plan_Close ='-';
									if($vals['sts_process'] === 'OPN'){
										$ket_detail	="<span class='badge bg-green'>OPEN</span>";
									}else{
										$ket_detail	="<span class='badge bg-maroon'>CLOSE</span>";
									}
									
									if($vals['plan_due_date']){
										$Plan_Close		= date('d F Y',strtotime($vals['plan_due_date']));
									}
									
									echo"<tr>";
										echo"<td class='text-left'>".$vals['descr']."</td>";
										echo"<td class='text-center'>".$Plan_Close."</td>";
										echo"<td class='text-center'>".$ket_detail."</td>";
									echo"</tr>";
								}
							}
							?>
						</tbody>
						
					</table>
				</div>
			</div>		
		</div>
		
	</div>
</form>
<?php $this->load->view('include/footer'); ?>
<script>
	var base_url			= '<?php echo base_url(); ?>';
	var active_controller	= '<?php echo($this->uri->segment(1)); ?>';
	$(document).ready(function(){
		
		$('#btn-back').click(function(){			
			loading_spinner();
			window.location.href =  base_url+active_controller;
		});
		
	});
	
	
</script>
