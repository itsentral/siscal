<?php
if($tipe =='1'){
	$judul	="Late Pick Up Customer Tool";
}else if($tipe =='2'){
	$judul	="Late Calibration Process";
}else if($tipe =='3'){
	$judul	="Late Send To Subcon";
}else if($tipe =='4'){
	$judul	="Late Pick From Subcon";
}else if($tipe =='5'){
	$judul	="Late Send To Customer";
}
echo"<input type='hidden' id='late_type' value='".$tipe."'>";
?>
<div class="box box-primary">
    <div class="box-header">
        <!--<h3 class="box-title"><?php echo $judul;?></h3>!-->
		<div class='box-tool pull-right'>
			<button type='button' class='btn btn-md btn-danger' id='btn_export'><i class="fa fa-cloud-download"> Download Excel</i></button>
		</div>
    </div>
    <div class="box-body" style='overflow-x:scroll'>
        <table class="table table-bordered table-striped" id='table_late'>
            <thead>
                <tr class='bg-blue'>
                    <td align="center">No</td>
                    <td align="center">Tool Name</td>
                    <td align="center">Qty Late</td>
					<td align="center">Customer</td>
					<?php
					if($tipe != 5){
					?>
						<td align="center">Quotation</td>
						<td align="center">Schedule</td>
					<?php
					}else{
					?>
						<td align="center">Category</td>
					<?php
					}
					?>
                   
					<td align="center">No SO</td>
					<?php
						if($tipe=='1'){
							echo"<td align='center'>Plan Pick Up</td>";
					        echo"<td align='center'>Pick / Send By</td>";
						}else if($tipe=='2'){
							echo"<td align='center'>Category</td>";
							echo"<td align='center'>Plan Process Calibration</td>";
					        echo"<td align='center'>Technician</td>";
							 echo"<td align='center'>No SPK</td>";
						}else if($tipe=='3'){
							echo"<td align='center'>Plan Send To Subcon</td>";
					        echo"<td align='center'>Subcon Name</td>";
						}else if($tipe=='4'){
							echo"<td align='center'>Plan Pick From Subcon</td>";
					        echo"<td align='center'>Subcon Name</td>";
						}else if($tipe=='5'){
							echo"<td align='center'>Plan Date</td>";
						}
					?>
					
                    
					<td align="center">Late</td>					
					
                </tr>
            </thead>
            <tbody>
            <?php
               if(isset($records) && $records){
				   $int=0;
				   $sekarang	= date('Y-m-d');
					foreach($records as $key=>$val){
						$int++;
						$Kategori	= '-';
						if($tipe=='1'){
							$plan_ambil	= $val['plan_pick_date'];							
							$ambil_by	= ' - ';
							if($val['get_tool']=='Driver'){
								$ambil_by	= 'Pick By Driver';
							}else if($val['get_tool']=='Customer'){
								$ambil_by	= 'Send By Driver';
							}
						}else if($tipe=='2'){
							
							if($val['labs']=='Y'){
								$Kategori	= 'Labs';
							}else if($val['insitu']=='Y'){
								$Kategori	= 'Insitu';
							}
							$plan_ambil	= $val['plan_process_date'];							
							$ambil_by	= $val['teknisi_name'];							
						}else if($tipe=='3'){
							$plan_ambil	= $val['plan_subcon_send_date'];							
							$ambil_by	= $val['supplier_name'];							
						}else if($tipe=='4'){
							$plan_ambil	= $val['plan_subcon_pick_date'];							
							$ambil_by	= $val['supplier_name'];							
						}else if($tipe=='5'){
							$plan_ambil	= $val['plan_delivery_date'];							
							$ambil_by	= ' - ';
							if($val['get_tool']=='Driver'){
								$ambil_by	= 'Send By Driver';
							}else if($val['get_tool']=='Customer'){
								$ambil_by	= 'Pick By Customer';
							}
							if($val['labs']=='Y'){
								$Kategori	= 'Internal';
							}else if($val['subcon']=='Y'){
								$Kategori	= 'Subcon';
							}
						}
						$leadtime	= (strtotime($sekarang) - strtotime($plan_ambil)) / (60*60*24);
						echo'<tr>';
                    		echo'<td align="center">'.$int.'</td>';
							echo'<td align="left">'.$val['tool_name'].'</td>';
							echo'<td align="center">'.$val['qty_late'].'</td>';
							echo'<td align="left">'.$val['customer_name'].'</td>';
							if($tipe != 5){
								echo'<td align="left">'.$val['quotation_nomor'].'</td>';
								echo'<td align="left">'.$val['schedule_nomor'].'</td>';
								echo'<td align="left">'.$val['no_so'].'</td>';
								if($Kategori !='-'){
									echo'<td align="center">'.$Kategori.'</td>';
								}
							
							}else{
								echo'<td align="center">'.$Kategori.'</td>';
								echo'<td align="left">'.$val['no_so'].'</td>';
							}
							
							
							echo'<td align="left">'.date('d M Y',strtotime($plan_ambil)).'</td>';
							if($tipe != 5){
								echo'<td align="center"><span class="badge bg-maroon">'.$ambil_by.'</span></td>';
								if($tipe=='2'){
									$Nomor_SPK		= $val['teknisi_id'].'-'.date('Ymd',strtotime($val['plan_process_date']));
									echo'<td align="left">'.$Nomor_SPK.'</td>';
								}
							}
							
							echo'<td align="center"><span class="badge bg-green">'.$leadtime.' Day</span></td>';							
							
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
		$('#table_late').dataTable();
		$('#btn_export').click(function(){
			var Kategori	= $('#late_type').val();
			var Links		= base_url+active_controller+'/export_excel/'+Kategori;
			//alert(Links);
			window.open(Links,'_blank');
		});
	})
</script>