
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
						<label class="control-label">BAST</label>
						<?php
							echo form_input(array('id'=>'nomor_bast','name'=>'nomor_bast','class'=>'form-control input-sm','readOnly'=>true),$rows_header['nomor']);						
						?>
					</div>
				</div>
				<div class="col-sm-6">
					<div class="form-group">
						<label class="control-label">Date</label>
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
						<label class="control-label">Address</label>
						<?php
							echo form_textarea(array('id'=>'address', 'name'=>'address','cols'=>'75','rows'=>'2', 'class'=>'form-control input-sm','readOnly'=>true),$rows_header['address']);						
						?>
					</div>
				</div>				
			</div>
			
			<div class='row'>
				<div class="col-sm-6">
					<div class="form-group">
						<label class="control-label">PIC Name</label>
						<?php
							echo form_input(array('id'=>'pic_name','name'=>'pic_name','class'=>'form-control input-sm','readOnly'=>true),$rows_header['pic']);
							
						?>
					</div>
				</div>
				<div class="col-sm-6">
					&nbsp;
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
						<th class="text-center">Code Tool</th>
						<th class="text-center">Name Tool</th>				
						<th class="text-center">Qty</th>
						<th class="text-center">Description</th>
						<th class="text-center">Technician</th>
					</tr>
				</thead>
				<tbody id="list_detail">
					<?php
					if($rows_detail){
						$intI		= 0;
						
						foreach($rows_detail as $ketD=>$valD){
							$intI++;
							
							
							echo"<tr>";
								
								echo"<td class='text-center'>".$intI."</td>";
								echo"<td class='text-center'>".$valD['tool_id']."</td>";
								echo"<td class='text-left'>".$valD['tool_name']."</td>";
								echo"<td class='text-center'>".$valD['qty']."</td>";
								echo"<td class='text-left'>".$valD['descr']."</td>";
								echo"<td class='text-center'>".$valD['member_name']."</td>";
							echo"</tr>";
						}
					}
					
				echo"</tbody>";
				
				?>
			</table>
		</div>
		
	</div>
</form>


<style>
	
</style>

