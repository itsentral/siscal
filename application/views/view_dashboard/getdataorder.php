<div class="box box-primary">
    <div class="box-header">
        <h3 class="box-title"><?php echo $Judul;?></h3>
		<div class='box-tool pull-right'>
			<button type='button' class='btn btn-md btn-danger' id='btn_export'><i class="fa fa-cloud-download"> Download Excel</i></button>
		</div>
    </div>
    <div class="box-body" style='overflow-x:scroll'>
        <table class="table table-bordered table-striped" id='table_late'>
            <thead>
                <tr class='bg-blue'>
					<td align="center">No</td>
                    <td align="center">No So</td>
                    <td align="center">Date</td>
					<td align="center">Customer</td>
                    <td align="center">Quotation</td>
                    <td align="center">No PO</td>
					<?php 
					if($kategori==1){
					?>
                    
					<td align="center">Nilai SO</td>
					<td align="center">Subcon</td>
					<td align="center">Insitu</td>
					<td align="center">Akomodasi</td>
					<td align="center">Cust Fee</td>
					<td align="center">Net SO</td>
					<td align="center">Marketing</td>
					<td align="center">Tipe</td>
					<td align="center">Referensi</td>
					<?php
					}else if($kategori==2){
					?>
					<td align="center">Tgl Cancel</td>
					<td align="center">Alasan Batal</td>
					<td align="center">Marketing</td>
					<?php
					}
					?>
                </tr>
            </thead>
            <tbody>
            <?php
               if(isset($records) && $records){
				   $int=0;
					foreach($records as $key=>$val){
						$int++;
						if($kategori==1){
							$Tgl_PO			= $val['podate'];
							$Tgl_Compare	= $val['first_so_date'];
							$Jenis_Ref		= '-';						
							if($Tgl_Compare !='' && $Tgl_Compare !='0000-00-00' && $Tgl_Compare !='1970-01-01'){
								$Beda		=(strtotime($Tgl_PO) - strtotime($Tgl_Compare)) / (60*60*24);
								if($Beda > 365){
									$Jenis_Ref		= 'Repeat';
								}else{
									$Jenis_Ref		= 'New';
								}
							}
						}
						echo'<tr>';
                    		echo'<td align="center">'.$int.'</td>';
							echo'<td align="left">'.$val['no_so'].'</td>';
							echo'<td align="center">'.date('d M Y',strtotime($val['tgl_so'])).'</td>';
							echo'<td align="left">'.$val['customer_name'].'</td>';
							echo'<td align="left">'.$val['quotation_nomor'].'</td>';
							echo'<td align="left">'.$val['pono'].'</td>';
							if($kategori==1){
								echo'<td align="right">'.number_format($val['so_total']).'</td>';
								echo'<td align="right">'.number_format($val['subcon_so']).'</td>';
								echo'<td align="right">'.number_format($val['insitu']).'</td>';
								echo'<td align="right">'.number_format($val['akomodasi']).'</td>';
								echo'<td align="right">'.number_format($val['cust_fee']).'</td>';
								echo'<td align="right">'.number_format($val['total_net']).'</td>';
								echo'<td align="left">'.$val['member_name'].'</td>';
								echo'<td align="left">'.$Jenis_Ref.'</td>';
								echo'<td align="left">'.$val['reference_by'].'</td>';
							}else if($kategori==2){
								echo'<td align="center">'.date('d M Y H:i',strtotime($val['cancel_date'])).'</td>';
								echo'<td align="left">'.$val['reason'].'</td>';
								echo'<td align="left">'.$val['member_name'].'</td>';
							}
							
                   		echo'</tr>';
					}
			   }
            ?>
            </tbody>
        </table>
     
    </div><!-- /.box-body -->
</div><!-- /.box -->
<script>
	var kategori			='<?php echo $kategori;?>';
	var base_url			= '<?php echo base_url(); ?>';
	//var active_controller	= 'Dashboard';
	$(document).ready(function(){
		$('#table_late').dataTable();
		$('#btn_export').click(function(e){
			e.preventDefault();
			var Links		= base_url+active_controller+'/get_excelorder/'+kategori;
			//alert(Links);
			window.open(Links,'_blank');
		});
		
	})
</script>