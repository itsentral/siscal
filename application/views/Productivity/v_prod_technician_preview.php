<div class="box box-warning">
	<div class="box-header">
		<h3 class="box-title">
			<i class="fa fa-check"></i> <?php echo('<span class="important">'.$title.'</span>'); ?>
		</h3>
		
		
	</div>
	<div class="box-body">
		<div class='row'>
			<div class="col-sm-6">
				<div class="form-group">
					<label class="control-label">Technician</label>
					<?php
						echo form_input(array('id'=>'teknisi','name'=>'teknisi','class'=>'form-control input-sm','readOnly'=>true),(!empty($rows_teknisi)?$rows_teknisi->nama:'ALL TECHNICIAN'));						
					?>
				</div>
			</div>
			<div class="col-sm-6">
				<div class="form-group">
					<label class="control-label">Period</label>
					<?php
						echo form_input(array('id'=>'periode','name'=>'periode','class'=>'form-control input-sm','readOnly'=>true),$period_find);						
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
	<div class="box-body" style="overflow-x:scroll !important;">
		<table class="table table-striped table-bordered" id="my-list">
			<thead>
				<tr class="bg-navy-blue">
					<th class="text-center">No</th>
					<th class="text-center">Code Tool</th>
					<th class="text-center">Tool Name</th>				
					<th class="text-center">Identify No</th>
					<th class="text-center">Serial No</th>
					<th class="text-center">Technician</th>
					<th class="text-center">Cals Date</th>
					<th class="text-center">Duration<br>(Minute)</th>
					<th class="text-center">Type</th>
					<th class="text-center">Customer</th>
					<th class="text-center">SO No</th>
					<th class="text-center">SO Date</th>
					<th class="text-center">Quotation No</th>
					<th class="text-center">Cals Notes</th>
				</tr>
			</thead>
			<tbody id="list_detail_tool">
				<?php
				if($rows_detail){
					$intI		= 0;
					
					foreach($rows_detail as $ketD=>$valD){
						$intI++;
						$Type_Tool	= "<span class='badge bg-green-active'>LABS</span>";
						if($valD->insitu == 'Y'){
							$Type_Tool	= "<span class='badge bg-navy-active'>INSITU</span>";
						}else if($valD->subcon == 'Y'){
							$Type_Tool	= "<span class='badge bg-maroon-active'>SUBCON</span>";
						}
						
						
						echo"<tr>";
							echo"<td class='text-center'>".$intI."</td>";
							echo"<td class='text-left'>".$valD->tool_id."</td>";
							echo"<td class='text-left'>".$valD->tool_name."</td>";
							echo"<td class='text-center'>".$valD->no_identifikasi."</td>";
							echo"<td class='text-center'>".$valD->no_serial_number."</td>";
							echo"<td class='text-center'>".$valD->name_technician."</td>";
							echo"<td class='text-center'>".$valD->date_cals."</td>";
							echo"<td class='text-center'>".number_format($valD->jumlah_menit)."</td>";
							echo"<td class='text-center'>".$Type_Tool."</td>";
							echo"<td class='text-left'>".$valD->customer_name."</td>";
							echo"<td class='text-center'>".$valD->no_so."</td>";
							echo"<td class='text-center'>".$valD->tgl_so."</td>";
							echo"<td class='text-center'>".$valD->quotation_nomor."</td>";	
							echo"<td class='text-left'>".$valD->keterangan."</td>";
						echo"</tr>";
					}
				}
				
			echo"</tbody>";
			
			?>
		</table>
	</div>
	
</div>
<script type="text/javascript">
	$(function() {	
		$('#my-list').dataTable();		
	});
	
	
	

</script>