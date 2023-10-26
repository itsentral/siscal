<?php
if($tipe =='1'){
	$judul	="Tool Certificate Unuploaded (INTERNAL)";
}else if($tipe =='2'){
	$judul	="Tool Certificate Unuploaded (SUBCON)";
}else if($tipe =='3'){
	$judul	="Late Send Certificate (INTERNAL)";
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
				<?php
				if($tipe==3){
					echo' <td align="center">No</td>
					<td align="center">No SO</td>
					<td align="center">Customer</td>
                    <td align="center">Tool Name</td>
					<td align="center">Vendor</td>
					<td align="center">Certificate No</td>
					<td align="center">Upload Date</td>					
					<td align="center">Late</td>';
				}else{
					echo' <td align="center">No</td>
					<td align="center">No SO</td>
					<td align="center">Customer</td>
                    <td align="center">Tool Name</td>
					<td align="center">Vendor</td>
					<td align="center">Process Date</td>
					<td align="center">Technician</td>
					<td align="center">Late</td>';
				}
				?>
                   					
					
                </tr>
            </thead>
            <tbody>
            <?php
               if(isset($records) && $records){
				   $int=0;
					$now		= date('Y-m-d');
					if($tipe==1 || $tipe==3){
						$sekarang	= date('Y-m-d',strtotime('-2 day',strtotime($now)));
					}else if($tipe==2){
						$sekarang	= date('Y-m-d',strtotime('-9 day',strtotime($now)));
					}
					
					foreach($records as $key=>$val){
						$int++;
						$Kategori	= '-';
						$ambil_by	= (isset($val['name_teknisi']) && $val['name_teknisi'])?$val['name_teknisi']:'-';
						$labs		= ($val['labs']=='N')?'-':$val['labs'];
						$insitu		= ($val['insitu']=='N')?'-':$val['insitu'];
						$subcon		= ($val['subcon']=='N')?'-':$val['subcon'];
						if($tipe=='1' || $tipe=='2'){
							$plan_ambil	= (isset($val['actual_process_date']) && $val['actual_process_date'])?$val['actual_process_date']:$val['plan_process_date'];						
							
						}else if($tipe==3){
							$plan_ambil	= $val['tgl_upload'];
						}
						$leadtime	= (strtotime($sekarang) - strtotime($plan_ambil)) / (60*60*24);
						echo'<tr>';
                    		echo'<td align="center">'.$int.'</td>';
							echo'<td align="center">'.$val['no_so'].'</td>';
							echo'<td align="left">'.$val['customer_name'].'</td>';
							echo'<td align="left">'.$val['tool_name'].'</td>';
							echo'<td align="left">'.$val['supplier_name'].'</td>';
							if($tipe=='1' || $tipe=='2'){
								echo'<td align="left">'.date('d M Y',strtotime($plan_ambil)).'</td>';
								echo'<td align="center"><span class="badge bg-maroon">'.$ambil_by.'</span></td>';
							}else if($tipe==3){
								echo'<td align="center"><span class="badge bg-maroon">'.$val['no_sertifikat'].'</span></td>';
								echo'<td align="left">'.date('d M Y',strtotime($plan_ambil)).'</td>';
								
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
			var Links		= base_url+active_controller+'/export_sertifikat/'+Kategori;
			//alert(Links);
			window.open(Links,'_blank');
		});
	})
</script>