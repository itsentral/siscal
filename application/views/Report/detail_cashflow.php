
<div class="box box-primary">
    <div class="box-header">
        <h3 class="box-title"><?php echo $rows_judul;?></h3>
		
    </div>
    <div class="box-body" style='overflow-x:scroll'>
        <table class="table table-bordered table-striped" id='table_other'>
            <thead>
                <tr class='bg-blue'>
					
					<?php
						echo"<th class='text-center'>No</th>";
						echo"<th class='text-center'>No Invoice</th>";
						echo"<th class='text-center'>Datet</th>";
						echo"<th class='text-center'>Customer</th>";
						echo"<th class='text-center'>DPP</th>";
						echo"<th class='text-center'>PPN</th>";
						echo"<th class='text-center'>Total</th>";
						echo"<th class='text-center'>PPH 23</th>";
						echo"<th class='text-center'>Total Plan</th>";
						echo"<th class='text-center'>Total Paid</th>";
						
					?>                
                    
					
                </tr>
            </thead>
            <tbody>
            <?php
			  
               if(isset($rows_data) && $rows_data){
				   $int=0;
					foreach($rows_data as $key=>$val){
						$int++;
						
						echo'<tr>';
                    		echo'<td align="center">'.$int.'</td>';					
							echo'<td align="left">'.$val->invoice_no.'</td>';
							echo'<td align="center">'.date('d M Y',strtotime($val->datet)).'</td>';
							echo'<td align="left">'.$val->customer_name.'</td>';
							echo'<td align="right">'.number_format($val->total_dpp).'</td>';
							echo'<td align="right">'.number_format($val->ppn).'</td>';
							echo'<td align="right">'.number_format($val->grand_tot).'</td>';
							echo'<td align="right">'.number_format($val->pph23).'</td>';
							echo'<td align="right">'.number_format($val->total_find).'</td>';
							echo'<td align="right">'.number_format($val->paid_total).'</td>';
                   		echo'</tr>';
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
		$('#table_other').dataTable();
		
		
	})
</script>