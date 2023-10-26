<div class="box box-primary">
    <div class="box-header">
        <h3 class="box-title">Outstanding Reminder Sertifikat</h3>
		
    </div>
    <div class="box-body" style='overflow-x:scroll'>
        <table class="table table-bordered table-striped" id='table_late'>
            <thead>
                <tr class='bg-blue'>
					<th class='text-center'>No</th>
					<th class='text-center'>Nama Alat</th>
					<th class='text-center'>Customer</th>
					<th class='text-center'>No Sertifikat</th>
					<th class='text-center'>Valid Date</th>					
                </tr>
            </thead>
            <tbody>
            <?php
               if($results){
					$intL	=0;
					foreach($results as $key=>$val){
						$intL++;						
						echo"<tr>";
							echo"<td align='center'>".$intL."</td>";
							echo"<td align='left'>".$val['tool_name']."</td>";
							echo"<td align='left'>".$val['customer_name']."</td>";	
							echo"<td align='left'>".$val['no_sertifikat']."</td>";
							echo"<td align='center'>".date('d M Y',strtotime($val['valid_until']))."</td>";							
						echo"</tr>";
					}
				}	
            ?>
            </tbody>
        </table>
     
    </div><!-- /.box-body -->
</div><!-- /.box -->
<script>
	var base_url			= '<?php echo base_url(); ?>';
	//var active_controller	= 'Dashboard';
	$(document).ready(function(){
		$('#table_late').dataTable();
		
		
	})
</script>