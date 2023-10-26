
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
						echo"<th class='text-center'>Tipe Billing</th>";
						//echo"<th class='text-center'>Action</th>";
					}else if($kategori==2){
						echo"<th class='text-center'>Invoice</th>";
						echo"<th class='text-center'>Datet</th>";
						echo"<th class='text-center'>Customer</th>";
						echo"<th class='text-center'>Alamat</th>";
						echo"<th class='text-center'>Nilai Total</th>";
						echo"<th class='text-center'>Tgl Input</th>";
						echo"<th class='text-center'>No SO</th>";
						echo"<th class='text-center'>Leadtime</th>";
					}else if($kategori==3){
						echo"<th class='text-center'>Invoice</th>";
						echo"<th class='text-center'>Datet</th>";
						echo"<th class='text-center'>Customer</th>";
						echo"<th class='text-center'>Alamat</th>";
						echo"<th class='text-center'>Nilai Total</th>";
						echo"<th class='text-center'>Tgl Receive</th>";
						echo"<th class='text-center'>No SO</th>";
						echo"<th class='text-center'>Leadtime</th>";
					}else if($kategori==4){
						echo"<th class='text-center'>Invoice</th>";
						echo"<th class='text-center'>Datet</th>";
						echo"<th class='text-center'>Customer</th>";
						echo"<th class='text-center'>Alamat</th>";
						echo"<th class='text-center'>Nilai Total</th>";
						echo"<th class='text-center'>Plan Bayar</th>";
						echo"<th class='text-center'>No SO</th>";
						echo"<th class='text-center'>Leadtime</th>";
					}else if($kategori==5 || $kategori==6 || $kategori=='8' || $kategori=='9' || $kategori=='10' || $kategori=='11' || $kategori=='12'){
						echo"<th class='text-center'>Invoice</th>";
						echo"<th class='text-center'>Datet</th>";
						echo"<th class='text-center'>Customer</th>";
						echo"<th class='text-center'>Tgl Receive Inv</th>";
						echo"<th class='text-center'>Total Inv</th>";
						echo"<th class='text-center'>Total Bayar</th>";
						echo"<th class='text-center'>Total AR</th>";
						echo"<th class='text-center'>No SO</th>";
						echo"<th class='text-center'>Leadtime</th>";
					}else if($kategori==7){						
						echo"<th class='text-center'>Quotation</th>";
						echo"<th class='text-center'>Tgl Quotation</th>";
						echo"<th class='text-center'>No PO</th>";
						echo"<th class='text-center'>Customer</th>";
						echo"<th class='text-center'>PIC</th>";
						echo"<th class='text-center'>Alamat</th>";
						echo"<th class='text-center'>No SO</th>";
						echo"<th class='text-center'>Tgl SO</th>";
						echo"<th class='text-center'>Leadtime</th>";
					}
					?>                
                    
					
                </tr>
            </thead>
            <tbody>
            <?php
			   $Arr_Invoice	= array(1=>'2','3','4','5','6','8','9','10','11','12');
               if(isset($records) && $records){
				   $int=0;
					foreach($records as $key=>$val){
						$int++;
						$No_SO		= '';
						if(in_array($kategori,$Arr_Invoice)){
							$Query_SO	= "SELECT
												det_so.no_so
											FROM
												letter_orders det_so
											INNER JOIN invoice_details det_inv ON det_inv.letter_order_id = det_so.id
											INNER JOIN invoices head_inv ON head_inv.id = det_inv.invoice_id
											WHERE
												head_inv.invoice_no = '".$val['invoice_no']."'
											GROUP BY
												det_so.id";
							$det_ORDER	= $this->db->query($Query_SO)->result_array();
							if($det_ORDER){
								foreach($det_ORDER as $keyS=>$valS){
									if(!empty($No_SO))$No_SO	.=', ';
									$No_SO	.=$valS['no_so'];
								}
							}
						}
						echo'<tr>';
                    		echo'<td align="center">'.$int.'</td>';
						if($kategori==1){
							## ALI 2019-12-22 ##
							$Qry_Cust	= "SELECT flag_billing FROM customers WHERE id='".$val['customer_id']."'";
							$det_Cust	= $this->db->query($Qry_Cust)->result_array();
							$Tipe_Bill	= '-';
							if($det_Cust[0]['flag_billing'] =='FULL'){
								$Tipe_Bill	= 'Full PO';
							}else if($det_Cust[0]['flag_billing'] =='PARTIAL'){
								$Tipe_Bill	= 'Partial';
							}
							
							echo'<td align="left">'.$val['nomor'].'</td>';
							echo'<td align="center">'.date('d M Y',strtotime($val['datet'])).'</td>';
							echo'<td align="left">'.$val['customer_name'].'</td>';
							echo'<td align="left">'.$val['pic_name'].'</td>';
							echo'<td align="left">'.$val['pono'].'</td>';
							echo'<td align="center">'.date('d M Y',strtotime($val['podate'])).'</td>';
							echo'<td align="center">'.$val['leadtime'].'</td>';
							echo'<td align="left">'.$val['member_name'].'</td>';
							echo'<td align="center">'.$Tipe_Bill.'</td>';
							//echo'<td align="center"><a href="/Calibrations_New/QuotationDeals/view/'.$val['id'].'" class="btn btn-md btn-primary" title="View Detail" data-role="qtip"><i class="fa fa-search"></a></td>';
						}else if($kategori==2){
							echo'<td align="left">'.$val['invoice_no'].'</td>';
							echo'<td align="center">'.date('d M Y',strtotime($val['datet'])).'</td>';
							echo'<td align="left">'.$val['customer_name'].'</td>';
							echo'<td align="left">'.$val['address'].'</td>';
							echo'<td align="left">'.number_format($val['grand_tot']).'</td>';
							echo'<td align="center">'.date('d M Y',strtotime($val['date_create'])).'</td>';
							echo'<td align="left">'.$No_SO.'</td>';
							echo'<td align="center">'.$val['leadtime'].'</td>';
							
						}else if($kategori==3){
							echo'<td align="left">'.$val['invoice_no'].'</td>';
							echo'<td align="center">'.date('d M Y',strtotime($val['datet'])).'</td>';
							echo'<td align="left">'.$val['customer_name'].'</td>';
							echo'<td align="left">'.$val['address'].'</td>';
							echo'<td align="left">'.number_format($val['grand_tot']).'</td>';
							echo'<td align="center">'.date('d M Y',strtotime($val['receive_date'])).'</td>';
							echo'<td align="left">'.$No_SO.'</td>';
							echo'<td align="center">'.$val['leadtime'].'</td>';
							
						}else if($kategori==4){
							echo'<td align="left">'.$val['invoice_no'].'</td>';
							echo'<td align="center">'.date('d M Y',strtotime($val['datet'])).'</td>';
							echo'<td align="left">'.$val['customer_name'].'</td>';
							echo'<td align="left">'.$val['address'].'</td>';
							echo'<td align="left">'.number_format($val['grand_tot']).'</td>';
							echo'<td align="center">'.date('d M Y',strtotime($val['plan_payment'])).'</td>';
							echo'<td align="left">'.$No_SO.'</td>';
							echo'<td align="center">'.$val['leadtime'].'</td>';
							
						}else if($kategori==5 || $kategori==6 || $kategori=='8' || $kategori=='9' || $kategori=='10' || $kategori=='11' || $kategori=='12'){
							echo'<td align="left">'.$val['invoice_no'].'</td>';
							echo'<td align="center">'.date('d M Y',strtotime($val['datet'])).'</td>';
							echo'<td align="left">'.$val['customer_name'].'</td>';
							echo'<td align="center">'.date('d M Y',strtotime($val['receive_date'])).'</td>';
							echo'<td align="left">'.number_format($val['grand_tot']).'</td>';
							echo'<td align="left">'.number_format($val['total_payment']).'</td>';
							echo'<td align="left">'.number_format($val['hutang']).'</td>';
							echo'<td align="left">'.$No_SO.'</td>';
							echo'<td align="center">'.$val['leadtime'].'</td>';
							
						}else if($kategori==7){							
							echo'<td align="center">'.$val['quotation_nomor'].'</td>';
							echo'<td align="center">'.date('d M Y',strtotime($val['quotation_date'])).'</td>';
							echo'<td align="center">'.$val['pono'].'</td>';
							echo'<td align="left">'.$val['customer_name'].'</td>';
							echo'<td align="center">'.$val['pic_name'].'</td>';
							echo'<td align="left">'.$val['address'].'</td>';
							echo'<td align="left">'.$val['no_so'].'</td>';
							echo'<td align="center">'.date('d M Y',strtotime($val['tgl_so'])).'</td>';						
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
	//var active_controller	= 'Dashboard';
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