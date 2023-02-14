
<div class="box box-primary">
    <div class="box-header">
        <h3 class="box-title">Data Detail</h3>
		<div class='box-tool pull-right'>
			<button type='button' class='btn btn-md btn-danger' id='btn_export'><i class="fa fa-cloud-download"> Download Excel</i></button>
			<input type='hidden' id='category_type' value='<?php echo $kategori;?>'>;
		</div>
    </div>
    <div class="box-body" style='overflow-x:scroll'>
        <table class="table table-bordered table-striped" id='table_other'>
            <thead>
                <tr class='bg-blue'>
					 <th class="text-center">No</th>
					<?php
					if($kategori==1){
						echo"<th class='text-center'>Quotation</th>";
						echo"<th class='text-center'>Datet</th>";
						echo"<th class='text-center'>Customer</th>";
						echo"<th class='text-center'>PIC</th>";
						echo"<th class='text-center'>PO No</th>";
						echo"<th class='text-center'>PO Date</th>";
						echo"<th class='text-center'>Leadtime</th>";
						echo"<th class='text-center'>Marketing</th>";
						//echo"<th class='text-center'>Action</th>";
					}else if($kategori==2){
						echo"<th class='text-center'>Invoice</th>";
						echo"<th class='text-center'>Datet</th>";
						echo"<th class='text-center'>Customer</th>";
						echo"<th class='text-center'>Alamat</th>";
						echo"<th class='text-center'>Nilai Total</th>";
						echo"<th class='text-center'>Tgl Input</th>";
						echo"<th class='text-center'>Leadtime</th>";
					}else if($kategori==3){
						echo"<th class='text-center'>Invoice</th>";
						echo"<th class='text-center'>Datet</th>";
						echo"<th class='text-center'>Customer</th>";
						echo"<th class='text-center'>Alamat</th>";
						echo"<th class='text-center'>Nilai Total</th>";
						echo"<th class='text-center'>Tgl Receive</th>";
						echo"<th class='text-center'>Leadtime</th>";
					}else if($kategori==4){
						echo"<th class='text-center'>Invoice</th>";
						echo"<th class='text-center'>Datet</th>";
						echo"<th class='text-center'>Customer</th>";
						echo"<th class='text-center'>Alamat</th>";
						echo"<th class='text-center'>Nilai Total</th>";
						echo"<th class='text-center'>Tgl Follow Up 1</th>";
						echo"<th class='text-center'>Leadtime</th>";
					}else if($kategori==5 || $kategori==6 || $kategori=='8' || $kategori=='9' || $kategori=='10' || $kategori=='11' || $kategori=='12'){
						echo"<th class='text-center'>Invoice</th>";
						echo"<th class='text-center'>Datet</th>";
						echo"<th class='text-center'>Customer</th>";
						echo"<th class='text-center'>Tgl Receive Inv</th>";
						echo"<th class='text-center'>Total Inv</th>";
						echo"<th class='text-center'>Total Bayar</th>";
						echo"<th class='text-center'>Total AR</th>";
						echo"<th class='text-center'>Leadtime</th>";
					}else if($kategori==7){
						echo"<th class='text-center'>No SO</th>";
						echo"<th class='text-center'>Tgl SO</th>";
						echo"<th class='text-center'>Quotation</th>";
						echo"<th class='text-center'>No PO</th>";
						echo"<th class='text-center'>Customer</th>";
						echo"<th class='text-center'>PIC</th>";
						echo"<th class='text-center'>Alamat Inv</th>";
						echo"<th class='text-center'>Alamat Sertifikat</th>";
						echo"<th class='text-center'>Alamat Kirim</th>";
						echo"<th class='text-center'>Leadtime</th>";
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
						echo'<tr>';
                    		echo'<td align="center">'.$int.'</td>';
						if($kategori==1){
							echo'<td align="left">'.$val['nomor'].'</td>';
							echo'<td align="center">'.date('d M Y',strtotime($val['datet'])).'</td>';
							echo'<td align="left">'.$val['customer_name'].'</td>';
							echo'<td align="left">'.$val['pic_name'].'</td>';
							echo'<td align="left">'.$val['pono'].'</td>';
							echo'<td align="center">'.date('d M Y',strtotime($val['podate'])).'</td>';
							echo'<td align="center">'.$val['leadtime'].'</td>';
							echo'<td align="left">'.$val['member_name'].'</td>';
							//echo'<td align="center"><a href="/Calibrations_New/QuotationDeals/view/'.$val['id'].'" class="btn btn-md btn-primary" title="View Detail" data-role="qtip"><i class="fa fa-search"></a></td>';
						}else if($kategori==2){
							echo'<td align="left">'.$val['invoice_no'].'</td>';
							echo'<td align="center">'.date('d M Y',strtotime($val['datet'])).'</td>';
							echo'<td align="left">'.$val['customer_name'].'</td>';
							echo'<td align="left">'.$val['address'].'</td>';
							echo'<td align="left">'.number_format($val['grand_tot']).'</td>';
							echo'<td align="center">'.date('d M Y',strtotime($val['date_create'])).'</td>';
							echo'<td align="center">'.$val['leadtime'].'</td>';
							
						}else if($kategori==3){
							echo'<td align="left">'.$val['invoice_no'].'</td>';
							echo'<td align="center">'.date('d M Y',strtotime($val['datet'])).'</td>';
							echo'<td align="left">'.$val['customer_name'].'</td>';
							echo'<td align="left">'.$val['address'].'</td>';
							echo'<td align="left">'.number_format($val['grand_tot']).'</td>';
							echo'<td align="center">'.date('d M Y',strtotime($val['receive_date'])).'</td>';
							echo'<td align="center">'.$val['leadtime'].'</td>';
							
						}else if($kategori==4){
							echo'<td align="left">'.$val['invoice_no'].'</td>';
							echo'<td align="center">'.date('d M Y',strtotime($val['datet'])).'</td>';
							echo'<td align="left">'.$val['customer_name'].'</td>';
							echo'<td align="left">'.$val['address'].'</td>';
							echo'<td align="left">'.number_format($val['grand_tot']).'</td>';
							echo'<td align="center">'.date('d M Y',strtotime($val['date_follow_up'])).'</td>';
							echo'<td align="center">'.$val['leadtime'].'</td>';
							
						}else if($kategori==5 || $kategori==6 || $kategori=='8' || $kategori=='9' || $kategori=='10' || $kategori=='11' || $kategori=='12'){
							echo'<td align="left">'.$val['invoice_no'].'</td>';
							echo'<td align="center">'.date('d M Y',strtotime($val['datet'])).'</td>';
							echo'<td align="left">'.$val['customer_name'].'</td>';
							echo'<td align="center">'.date('d M Y',strtotime($val['receive_date'])).'</td>';
							echo'<td align="left">'.number_format($val['grand_tot']).'</td>';
							echo'<td align="left">'.number_format($val['total_payment']).'</td>';
							echo'<td align="left">'.number_format($val['hutang']).'</td>';
							echo'<td align="center">'.$val['leadtime'].'</td>';
							
						}else if($kategori==7){
							echo'<td align="left">'.$val['no_so'].'</td>';
							echo'<td align="center">'.date('d M Y',strtotime($val['tgl_so'])).'</td>';
							echo'<td align="center">'.$val['quotation_nomor'].'</td>';
							echo'<td align="center">'.$val['pono'].'</td>';
							echo'<td align="left">'.$val['customer_name'].'</td>';
							echo'<td align="center">'.$val['pic'].'</td>';
							echo'<td align="left">'.$val['address_inv'].'</td>';
							echo'<td align="left">'.$val['address_sertifikat'].'</td>';
							echo'<td align="left">'.$val['address_send'].'</td>';
							echo'<td align="center">'.$val['leadtime'].'</td>';
							
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
	var base_url			= '<?php echo base_url(); ?>';
	var active_controller	= 'Dashboard';
	$(document).ready(function(){
		$('#table_other').dataTable();
		$('#btn_export').click(function(){
			var Kategori	= $('#category_type').val();
			var Links		= base_url+active_controller+'/excel_other_dashboard/'+Kategori;
			//alert(Links);
			window.open(Links,'_blank');
		});
		
	})
</script>