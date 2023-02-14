
<form action="#" method="POST" id="form-proses-narasi" enctype="multipart/form-data">
	<div class="box box-warning">
		
		
			<?php
			if(empty($rows_header)){
				echo '
				<div class="box-body">
					<div class="row">
						<div class="col-sm-12">
							<div class="alert alert-warning">						 
								<h5><i class="fa fa-info"></i> Alert!</h5>
								DATA NOT FOUND...
							</div>
						</div>					
					</div>
				</div>
				';
			}else{
			
			
			?>
			<div class="box-body">
				<div class="row">
					<div class="col-sm-12 text-center sub-heading" style="color:white;">
						<h5><?php echo $title;?></h5>
					</div>
					
				</div>
				<div class='row'>
					<div class="col-sm-6">
						<div class="form-group">
							<label class="control-label">Code Narration <span class="text-red">*</span></label>
							<?php
								echo form_input(array('id'=>'code_narasi','name'=>'code_narasi','class'=>'form-control input-sm text-up','readOnly'=>true),$rows_header[0]->code);	
									
							?>
						</div>
					</div>
					<div class="col-sm-6">
						<div class="form-group">
							<label class="control-label">Name Narration <span class="text-red">*</span></label>
							<?php
								echo form_input(array('id'=>'nama_narasi','name'=>'nama_narasi','class'=>'form-control input-sm text-up','readOnly'=>true),$rows_header[0]->name);	
									
							?>
						</div>
					</div>				
				</div>
				<div class='row'>
					<div class="col-sm-6">
						<div class="form-group">
							<div class="form-group">
							<label class="control-label">Description</label>
							<?php
								echo form_input(array('id'=>'ket_narasi','name'=>'ket_narasi','class'=>'form-control input-sm text-up','readOnly'=>true),$rows_header[0]->descr);							
							?>
						</div>
						</div>
					</div>
					<div class="col-sm-6">
						<div class="form-group">
							<label class="control-label">Status</label>
							<div>
							<?php
								$Status	= 'ACTIVE';
								$Color	= 'bg-green';
								if($rows_header[0]->flag_active == 'N'){
									$Status	= 'NOT ACTIVE';
									$Color	= 'bg-red';
								}
								echo '<span class="badge '.$Color.'">'.$Status.'</span>';							
							?>
							</div>
						</div>
					</div>				
				</div>
				
				<div class="row">
					<div class="col-sm-12 col-xs-12 text-center sub-heading" style="color:white;">
						<h5>DETAIL NARRATION</h5>
					</div>				
				</div>
				<div class='row'>					
					<div class="col-sm-12">
						<div class="box box-danger">
							<div class="box-body">
								<table class="table table-striped table-bordered" width="100%">
									<thead>
										<tr class="white-text" style="background-color:#0A81AB !important;color:white !important;">
											<th class="text-center">No</th>
											<th class="text-center">Narration</th>											
										</tr>
										
									</thead>
									<tbody id="list_item_narasi">
									<?php
									
									if($rows_detail){
										$intL	= 0;
										foreach($rows_detail as $keyD=>$valD){
											$intL++;
											echo'<tr>
													<td class="text-center">'.$intL.'</td>
													<td class="text-left">'.$valD->narration.'</td>							
												</tr>';
										}
									}
										
									
									?>
									</tbody>
								</table>
							</div>
						</div>
					</div>
									
				</div>					
			</div>		
			
		<?php
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
	.text-up{
		text-transform :uppercase !important;
	}
</style>
<script>
	$(document).ready(function(){	
	
	});
	
</script>