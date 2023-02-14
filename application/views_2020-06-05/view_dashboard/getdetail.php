<?php
//echo "<pre>";print_r($records);exit;
?>
<div class="box box-primary">
    <div class="box-header">
        <h3 class="box-title">Data Quotation</h3>
    </div>
    <div class="box-body" style='overflow-x:scroll'>
        <table class="table table-bordered table-striped" id='table_late'>
            <thead>
                <tr class='bg-blue'>
                    <td align="center">No</td>
                    <td align="center">Quotation</td>
                    <td align="center">Date</td>
					<td align="center">Customer</td>
                    <td align="center">Total Alat</td>
                    <td align="center">Insitu</td>
					<td align="center">Akomodasi</td>
					<td align="center">Total Subcon</td>
					<td align="center">Success Fee</td>
					<td align="center">Net</td>					
					<td align="center">Sales</td>
					<?php
					if($tipe !='1'){
						if($tipe=='2'){
							echo"<td align='center'>Deal Date</td>";
						}
						
						
						if($tipe=='3'){							
							echo"<td align='center'>Fail Date</td>";
							echo"<td align='center'>Description</td>";
						}else if($tipe=='4'){
							echo"<td align='center'>Cancel Date</td>";
							echo"<td align='center'>Description</td>";
						}
						//echo"<td align='center'>$Judul</td>"; 
					}
					?>
					<!--<td align="center">Action</td>!-->
                </tr>
            </thead>
            <tbody>
            <?php
               if(isset($records) && $records){
				   $int=0;
					foreach($records as $key=>$val){
						$int++;
						$akomodasi		= $val['total_akomodasi'];
						$insitu			= $val['tot_insitu'];
						$subcon			= $val['total_subcon'];
						$success_fee	= $val['customer_fee'];
						$total_alat		= $val['total_dpp'] - $akomodasi - $insitu - $subcon - $success_fee;
						echo'<tr>';
                    		echo'<td align="center">'.$int.'</td>';
							echo'<td align="left">'.$val['nomor'].'</td>';
							echo'<td align="center">'.date('d M Y',strtotime($val['tgl_old'])).'</td>';
							echo'<td align="left">'.$val['customer_name'].'</td>';
							echo'<td align="right">'.number_format($val['total_dpp']).'</td>';
							echo'<td align="right">'.number_format($insitu).'</td>';
							echo'<td align="right">'.number_format($akomodasi).'</td>';
							echo'<td align="right">'.number_format($subcon).'</td>';
							echo'<td align="right">'.number_format($success_fee).'</td>';
							echo'<td align="right">'.number_format(floatval($total_alat)).'</td>';
							echo'<td align="left">'.$val['member_name'].'</td>';
							if($tipe !='1'){								
								if($tipe=='2'){
									$tgl	=date('d M Y',strtotime($val['podate']));
								}else{
									$tgl	=date('d M Y',strtotime($val['cancel_date']));
								}
								echo"<td align='center'>$tgl</td>";
								if($tipe=='3' || $tipe=='4'){
									echo'<td align="left">'.ucwords(strtolower($val['reason'])).'</td>';
								}
							}
							//echo'<td align="center"><a class="btn btn-sm btn-danger" href="/Calibrations_New/Quotations/view/'.$val['id'].'" target="_blank"><i class="fa fa-search"></i></a></td>';
							
                   		echo'</tr>';
					}
			   }
            ?>
            </tbody>
        </table>
     
    </div><!-- /.box-body -->
</div><!-- /.box -->
<script>
	$(document).ready(function(){
		$('#table_late').dataTable();
		
	})
</script>