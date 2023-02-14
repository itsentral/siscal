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
			<?php
			if($sts_VOC === 'CLA'){
				echo"<div class='form-group row'>";
					echo"<label class='label-control col-sm-2'><b>Rate</b></label>";
					echo"<div class='col-sm-4'>";
						$Rating		= $rows_header[0]['rate'];
						$text_rate	= "";
						for($x=1;$x<=5;$x++){
							if($x <=$Rating){
								$text_rate	.="<span class='glyphicon glyphicon-star'></span>";
							}else{
								$text_rate	.="<span class='glyphicon glyphicon-star-empty'></span>";
							}
						}
						echo $text_rate;
					echo"</div>";
					echo"<label class='label-control col-sm-2'><b>Feedback</b></label>";
					echo"<div class='col-sm-4'>";
						echo form_textarea(array('id'=>'feedback','name'=>'feedback', 'cols'=>'75','rows'=>'2','class'=>'form-control input-sm','readOnly'=>true),$rows_header[0]['feedback']);
					echo"</div>";
				echo"</div>";
			}			
			?>
		</div>
		<div class="box-body">
			<div class="box box-primary">
				<div class="box-header">
					<h4 class="box-title">Detail Complain</h4>					
				</div>
				<div class="box-body">
					<?php
						if($rows_detail){
							$id_Accord	= "MyAccordion";
							echo"<div class='panel-group' id='".$id_Accord."'>";
								$Loop	= 0;
								foreach($rows_detail as $key=>$vals){
									$Loop++;
									$Coll_Name	= "tutup_".$Loop;
									echo form_input(array('id'=>'kode_det_'.$Loop,'name'=>'detDetail['.$vals['id'].'][header]','type'=>'hidden'),$vals['id']);
									echo"<div class='panel panel-info'>";
										echo"<div class='panel-heading'>";
											echo"<h4 class='panel-title'>";
												echo"<a data-toggle='collapse' href='#".$Coll_Name."' data-parent='#".$id_Accord."'>".$Loop.". ".$vals['descr']."</a>";
												
											echo"</h4>";
										echo"</div>";
										echo"<div id='".$Coll_Name."' class='panel-collapse collapse in'>";
											echo"<div class='panel-body'>";												
												echo"<table id='my-grid' class='table table-bordered table-striped'>
													<thead>					
														<tr class='bg-blue'>
															<th class='text-center' colspan='3'>Plan Action</th>
															<th class='text-center' colspan='3'>Actual</th>
															<th class='text-center' rowspan='2'>Option</th>
														</tr>
														<tr class='bg-blue'>
															<th class='text-center'>Description</th>
															<th class='text-center'>Plan Date</th>
															<th class='text-center'>PIC Incharge</th>
															<th class='text-center'>Description</th>
															<th class='text-center'>Actual Date</th>
															<th class='text-center'>PIC Incharge</th>
														</tr>
													</thead>
													<tbody id='list_detail_".$vals['id']."'>";
														$data_Detail	= $this->db->get_where('complain_customer_actions',array('complain_customer_detail_id'=>$vals['id']))->result();
														if($data_Detail){
															foreach($data_Detail as $keyD=>$valD){
																echo"<tr>";
																	echo"<td class='text-left'>".$valD->plan_action."</td>";
																	echo"<td class='text-center'>".date('d M Y',strtotime($valD->plan_due_date))."</td>";
																	echo"<td class='text-center'>".$valD->plan_action_by_name."</td>";
																	echo"<td class='text-left'>".$valD->descr."</td>";
																	echo"<td class='text-center'>";
																		$actual_date	='-';
																		if($valD->actual_finish_date){
																			$actual_date	= date('d M Y',strtotime($valD->actual_finish_date));
																		}
																		echo $actual_date;
																	echo"</td>";
																	echo"<td class='text-center'>".$valD->actual_action_by_name."</td>";
																	echo"<td class='text-center'>";
																		$actual_action	='-';
																		if($valD->sts_action=== 'OPN' && ($akses_menu['update']=== 1 || $akses_menu['create'] === 1)){
																			$actual_action	= "<a href='#' onClick='updateActual(\"".$valD->id."\");' class='btn btn-sm bg-orange' title='Update Actual Action'> <i class='fa fa-check-square'></i>";
																		}
																		echo $actual_action;
																	echo"</td>";
																	
																echo"</tr>";
															}
														}
													echo"</tbody>
													
												</table>";
											echo"</div>";
										echo"</div>";
									echo"</div><br>";
								}
								
							echo"</div>";
						}
					?>
				</div>
			</div>		
		</div>
		
	</div>
</form>
<div class="modal fade" id="MymodalActual" >
	<div class="modal-dialog" style="width:80%">
		<div class="modal-content">
			<div class="modal-header">
				<h4 class="modal-title">Actual Action VoC</h4>
			</div>
			<div class="modal-body" id="Actual-list">
			
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal" id="btn-close">Close</button>
			</div>
		</div><!-- /.modal-content -->
	</div><!-- /.modal-dialog -->
</div><!-- /.modal -->
<style type="text/css">   
    .glyphicon {
		color		: #f6a821;
		font-size 	: 35px;
	}
</style>
<?php $this->load->view('include/footer'); ?>
<script>
	var base_url			= '<?php echo base_url(); ?>';
	var active_controller	= '<?php echo($this->uri->segment(1)); ?>';
	$(document).ready(function(){
		
		$('#btn-back').click(function(){			
			loading_spinner();
			window.location.href =  base_url+'index.php/'+active_controller;
		});
		
	});
	
	function updateActual(kode_det){
		$('#Actual-list').empty();
		let baseurl2		= base_url +'index.php/'+active_controller+'/update_actual_progress';
		$.ajax({
			url			: baseurl2,
			type		: "POST",
			data		: {'kode_detail':kode_det},
			success		: function(data){
				$('#Actual-list').html(data);
				$('#MymodalActual').modal('show');
				
			}
		});
	}
</script>
