<div class="box box-primary">
    <div class="box-header">
        <h3 class="box-title">Outstanding Reminder Sertifikat</h3>
		
    </div>
    <div class="box-body" style='overflow-x:scroll'>
        <table class="table table-bordered table-striped" id='table_late'>
            <thead>
                <tr class='bg-blue'>
					<th class="text-center">No</th>
					<td align="center"><b>No Quotation</b></td>
					<td align="center"><b>Tgl Quotation</b></td>
					<td align="center"><b>Perusahaan</b></td>
					<td align="center"><b>No PO</b></td>
					<td align="center"><b>Marketing</b></td>			
                </tr>
            </thead>
            <tbody>
            <?php
               if($results){
					$intL	=0;
					foreach($results as $key=>$vals){
						$intL++;
						echo"<tr>";
							echo"<td class='text-center'>".$intL."</td>";
							echo"<td align='left'>".$vals['nomor']."</td>";
							echo"<td align='center'>".date('d M Y',strtotime($vals['datet']))."</td>";
							echo"<td align='left'>".$vals['customer_name']."</td>";	
							echo"<td align='center'>".$vals['pono']."</td>";
							echo"<td align='left'>".$vals['member_name']."</td>";
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