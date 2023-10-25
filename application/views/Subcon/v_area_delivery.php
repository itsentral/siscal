
<div class="box box-warning">
	<div class="box-header">
		<h4 class="box-title"><i class="fa fa-check-square"></i> <?php echo $title;?></h4>
		
	</div>
	<!-- /.box-header -->
	<div class="box-body">
		<table id="my-delivery" class="table table-bordered table-striped">
			<thead>
				<tr class="bg-navy-active">
					<th class="text-center">Code</th>
					<th class="text-center">Area Name</th>				
					<th class='text-center'>Fee</th>
					<th class='text-center'>Action</th>		
				</tr>
			</thead>
			<tbody id="list_master_delivery">
			<?php
			if($rows_master){
				foreach($rows_master as $keyMaster=>$valMaster){
					echo"
					<tr>
						<input type='hidden' name='master_delv_id' id='master_delv_id_".$valMaster['id']."' value='".$valMaster['id']."'>
						<input type='hidden' name='master_delv_area' id='master_delv_area_".$valMaster['id']."' value='".$valMaster['area']."'>
						<input type='hidden' name='master_delv_id' id='master_delv_fee_".$valMaster['id']."' value='".$valMaster['fee']."'>
						
						
						<td class='text-center'>".$valMaster['id']."</td>
						<td class='text-left'>".$valMaster['area']."</td>
						<td class='text-right'>".number_format($valMaster['fee'])."</td>
						<td class='text-center'><button type='button' class='btn btn-md btn-danger' onClick='return selectArea(\"".$valMaster['id']."\")'> <i class='fa fa-check'></i></button></td>
					</tr>";
				}
			}	
			?>
			</tbody>
			
		</table>
	</div>
	
	<!-- /.box-body -->
</div>


<script type="text/javascript">
	
	$(function() {		
		$('#my-delivery').DataTable();		
	});

</script>
