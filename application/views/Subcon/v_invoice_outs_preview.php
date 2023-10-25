
<form action="#" method="POST" id="form-proses" enctype="multipart/form-data">
	<div class="box box-warning">
		
		<div class="box-body">
			<div class="row">
				<div class="col-sm-12 col-xs-12 text-center sub-heading" style="color:white;">
					<h5><?php echo $title;?></h5>
				</div>
				
			</div>
			<?php
			if(empty($rows_detail)){
				echo"<div class='row'>
						<div class='col-sm-12'>
							<h4 class='text-red'><b>NO RECORD WAS FOUND.....</b></h4>
						</div>
					</div>";
			}else{
				if($flag_so == 'Y'){
			?>
				<div class='row'>
					<div class="col-sm-6">
						<div class="form-group">
							<label class="control-label">SO No</label>
							<?php
								echo form_input(array('id'=>'nomor_so','name'=>'nomor_so','class'=>'form-control input-sm','readOnly'=>true),$rows_detail[0]->no_so);	
								
							?>
						</div>
					</div>
					<div class="col-sm-6">
						<div class="form-group">
							<label class="control-label">SO Date</label>
							<?php
								echo form_input(array('id'=>'tgl_so','name'=>'tgl_so','class'=>'form-control input-sm','readOnly'=>true),date('d-m-Y',strtotime($rows_detail[0]->tgl_so)));						
							?>
						</div>
					</div>				
				</div>
				<?php
				}
				?>
				<div class='row'>
					<div class="col-sm-6">
						<div class="form-group">
							<label class="control-label">Quotation</label>
							<?php
								echo form_input(array('id'=>'quotation_nomor','name'=>'quotation_nomor','class'=>'form-control input-sm','readOnly'=>true),$rows_detail[0]->quotation_nomor);						
							?>
						</div>
					</div>
					<div class="col-sm-6">
						<div class="form-group">
							<label class="control-label">Quotation Date</label>
							<?php
								echo form_input(array('id'=>'tgl_quotation','name'=>'tgl_quotation','class'=>'form-control input-sm','readOnly'=>true),date('d-m-Y',strtotime($rows_detail[0]->quotation_date)));							
							?>
						</div>
					</div>				
				</div>
				<div class='row'>
					<div class="col-sm-6">
						<div class="form-group">
							<label class="control-label">Customer</label>
							<?php
								echo form_input(array('id'=>'customer_name','name'=>'customer_name','class'=>'form-control input-sm','readOnly'=>true),$rows_detail[0]->customer_name);				
							?>
						</div>
					</div>
					<div class="col-sm-6">
						<div class="form-group">
							<label class="control-label">PIC Name</label>
							<?php
								echo form_input(array('id'=>'pic_name','name'=>'pic_name','class'=>'form-control input-sm','readOnly'=>true),$rows_quot[0]->pic_name);						
							?>
						</div>
					</div>				
				</div>
				<div class='row'>
					<div class="col-sm-6">
						<div class="form-group">
							<label class="control-label">Marketing</label>
							<?php
								echo form_input(array('id'=>'marketing','name'=>'marketing','class'=>'form-control input-sm','readOnly'=>true),$rows_detail[0]->marketing_name);						
							?>
						</div>
					</div>
					<div class="col-sm-6">
						<div class="form-group">
							<label class="control-label">Exclude VAT</label>
							<?php
								$Exclude_PPN	= 'YES';
								if($rows_quot[0]->exc_ppn == 'N'){
									$Exclude_PPN	= 'NO';
								}
								echo form_input(array('id'=>'exc_ppn','name'=>'exc_ppn','class'=>'form-control input-sm','readOnly'=>true),$Exclude_PPN);						
							?>
						</div>
					</div>				
				</div>
				<div class="row">
					<div class="col-sm-12 col-xs-12 text-center sub-heading" style="color:white;">
						<h5>DETAIL PURCHASE ORDER</h5>
					</div>
					
				</div>
				<div class='row'>
					<div class="col-sm-6">
						<div class="form-group">
							<label class="control-label">PO No</label>
							<?php
								echo form_input(array('id'=>'pono','name'=>'pono','class'=>'form-control input-sm','readOnly'=>true),$rows_quot[0]->pono);	
								
							?>
						</div>
					</div>
					<div class="col-sm-6">
						<div class="form-group">
							<label class="control-label">PO Receive Date</label>
							<?php
								echo form_input(array('id'=>'tgl_po','name'=>'tgl_po','class'=>'form-control input-sm','readOnly'=>true),date('d-m-Y',strtotime($rows_quot[0]->po_receive)));						
							?>
						</div>
					</div>				
				</div>
				<div class='row'>
					<div class="col-sm-6">
						<div class="form-group">
							<label class="control-label">PO Input Date</label>
							<?php
								echo form_input(array('id'=>'tgl_input','name'=>'tgl_input','class'=>'form-control input-sm','readOnly'=>true),date('d-m-Y',strtotime($rows_quot[0]->podate)));						
							?>
						</div>
					</div>
					<div class="col-sm-6">&nbsp;</div>				
				</div>
				<div class="row">
					<div class="col-sm-12 col-xs-12 text-center sub-heading" style="color:white;">
						<h5>DETAIL TOOLS</h5>
					</div>
					
				</div>				
				
				
				<div class="row">
					<div class="col-sm-12" style="overflow-x:scroll !important;">
						<table class="table table-striped table-bordered" id="my-grid">
							<thead>
								<tr class="bg-navy-active">
									<th class="text-center">Tool Name</th>
									<th class="text-center">Vendor</th>
									<th class="text-center">Price</th>				
									<th class="text-center">Qty</th>
									<th class="text-center">Sub Total</th>
									<th class="text-center">Discount</th>
									<th class="text-center">Total</th>
									<?php 
									if($flag_so == 'N'){
										echo'<th class="text-center">No SO</th>';
									}
									?>
								</tr>
							</thead>
							<tbody id="list_detail">
								<?php
								$Total_Alat		= $Total_Insitu	= $Total_Akomodasi = 0;
								$Flag_Insitu	= 0;
								if($rows_detail){
									$intL	= 0;
									foreach($rows_detail as $ketD=>$valD){
										$intL++;
										
										if($valD->insitu == 'Y'){
											$Flag_Insitu	= 1;
										}
										
										$Qty_Proses		= ($valD->qty_proses > $valD->qty_real)?$valD->qty_real:$valD->qty_proses;
										$harga_barang	= $valD->price * $Qty_Proses;
										$harga_after	= (100 -  $valD->discount) * ($harga_barang / 100);
										$Total_Alat		+= $harga_after;
										
										echo"<tr>";										
											echo"<td align='left'>".$valD->tool_name."</td>";
											echo"<td align='left'>".$valD->supplier_name."</td>";
											echo"<td align='right'>".number_format($valD->price)."</td>";
											echo"<td align='center'>".number_format($Qty_Proses)."</td>";
											echo"<td align='right'>".number_format($harga_barang)."</td>";
											echo"<td align='right'>".number_format(floatval($valD->discount),2)."</td>";
											echo"<td align='right'>".number_format($harga_after)."</td>";
											if($flag_so == 'N'){
												echo"<td align='center'>".$valD->no_so."</td>";
											}
										echo"</tr>";
									}
								}
								?>
							</tbody>
							<tfoot class='bg-gray'>
								<tr>
									<td align='right' colspan='6'><b>Sub Total</b></td>
									<td align='right'><b><?php echo number_format(floatval($Total_Alat)); ?></b></td>
									<?php 
									if($flag_so == 'N'){
										echo'<td class="text-center">-</td>';
									}
									?>
								</tr>
								
							</tfoot>
						</table>
					</div>
				</div>				
			<?php
				
				if($Flag_Insitu == 1){
					$Query_Delivery	= "SELECT * FROM quotation_deliveries WHERE quotation_id  = '".$rows_quot[0]->id."' AND (day - IF(pros_invoice > 0, pros_invoice,0)) > 0";	
					$rows_Delivery	= $this->db->query($Query_Delivery)->result();
					if($rows_Delivery){
						echo'<div class="row">
								<div class="col-sm-12 col-xs-12 text-center sub-heading" style="color:white;">
									<h5>DETAIL INSITU COST</h5>
								</div>							
							</div>
							<div class="row">
								<div class="col-sm-12" style="overflow-x:scroll !important;">
									<table class="table table-bordered table-striped">
										<thead>
											<tr class="bg-navy-active">
												<th class="text-center">Area</th>
												<th class="text-center">Cost</th>
												<th class="text-center">Days</th>
												<th class="text-center">Sub Total</th>
												<th class="text-center">Discount</th>
												<th class="text-center">Total</th>
											</tr>
										</thead>
										<tbody>
							';
						foreach($rows_Delivery as $keyDel=>$valDel){
							$Ins_Pro			= (isset($valDel->pros_invoice) && $valDel->pros_invoice)?$valDel->pros_invoice:0;
							$Hari_Sisa			= $valDel->day - $Ins_Pro;
							$Diskon_Ins			= round($valDel->diskon / $valDel->day);
							
							$Harga_After		= round($valDel->fee * $Hari_Sisa);
							$Insitu_Nil			= ($Harga_After - ($Diskon_Ins * $Hari_Sisa));
							$Total_Insitu		+= $Insitu_Nil;
							
							echo"<tr>";
								echo"<td align='left'>";
									echo $valDel->delivery_name;
								echo"</td>";									
								echo"<td align='right'>";
									echo number_format(floatval($valDel->fee));
								echo"</td>";
								echo"<td align='center'>";
									echo number_format(floatval($Hari_Sisa));
								echo"</td>";
								echo"<td align='right'>";
									echo number_format(floatval($Harga_After));
								echo"</td>";
								echo"<td align='right'>";
									echo number_format(floatval($Diskon_Ins * $Hari_Sisa));
								echo"</td>";								
								echo"<td align='right'>";
									echo number_format(floatval($Insitu_Nil));
								echo"</td>";
							echo"</tr>";
						}
						
						echo'		</tbody>
									<tfoot class="bg-gray">
									<tr>
										<td align="right" colspan="5"><b>Sub Total</b></td>
										<td align="right"><b>'.number_format(floatval($Total_Insitu)).'</b></td>
									</tr>
									
								</tfoot>
								</div>
							</div>';
					}
					
					$Query_Accomodation	= "SELECT * FROM quotation_accommodations WHERE quotation_id  = '".$rows_quot[0]->id."' AND (pros_invoice IS NULL OR pros_invoice ='' OR pros_invoice ='N' OR pros_invoice ='-')";	
					$rows_Accomodation	= $this->db->query($Query_Accomodation)->result();
					if($rows_Accomodation){
						echo'<div class="row">
								<div class="col-sm-12 col-xs-12 text-center sub-heading" style="color:white;">
									<h5>DETAIL ACCOMMODATION COST</h5>
								</div>							
							</div>
							<div class="row">
								<div class="col-sm-12" style="overflow-x:scroll !important;">
									<table class="table table-bordered table-striped">
										<thead>
											<tr class="bg-navy-active">
												<th class="text-center">Description</th>
												<th class="text-center">Cost</th>
												<th class="text-center">Discount</th>
												<th class="text-center">Total</th>
											</tr>
										</thead>
										<tbody>
							';
						foreach($rows_Accomodation as $keyDel=>$valDel){
							$Total_Akomodasi		+= $valDel->total;
							
							echo"<tr>";
								echo"<td align='left'>";
									echo $valDel->accommodation_name;
								echo"</td>";									
								echo"<td align='right'>";
									echo number_format(floatval($valDel->nilai));
								echo"</td>";									
								echo"<td align='right'>";
									echo number_format(floatval($valDel->diskon));
								echo"</td>";								
								echo"<td align='right'>";
									echo number_format(floatval($valDel->total));
								echo"</td>";
							echo"</tr>";
						}
						
						echo'		</tbody>
									<tfoot class="bg-gray">
									<tr>
										<td align="right" colspan="3"><b>Sub Total</b></td>
										<td align="right"><b>'.number_format(floatval($Total_Akomodasi)).'</b></td>
									</tr>
									
								</tfoot>
								</div>
							</div>';
					}
				}
				
				$PPN  			= 0;
				
				if($rows_quot[0]->exc_ppn == 'N'){
					$Invoice_Date	= date('Y-m-d');
					$Prosen_PPN		= 10;
					$Query_Prosen	= "SELECT * FROM master_taxes WHERE valid_date <= '".$Invoice_Date."' ORDER BY valid_date DESC LIMIT 1";
					$rows_Prosen	= $this->db->query($Query_Prosen)->result();
					if($rows_Prosen){
						$Prosen_PPN	= $rows_Prosen[0]->ppn_value;
					}
					
					$PPN	= floor(($Total_Alat + $Total_Insitu + $Total_Akomodasi) * $Prosen_PPN / 100);
				}
				echo'<div class="row">
						<div class="col-sm-12 col-xs-12 text-center sub-heading" style="color:white;">
							<h5>SUMMARY</h5>
						</div>							
					</div>
					<div class="row">
						<div class="col-sm-6">
							<div class="form-group">
								<label class="control-label">Total Tool</label>
								'.form_input(array('id'=>'total_alat','name'=>'total_alat','class'=>'form-control input-sm','readOnly'=>true),number_format($Total_Alat)).'
							</div>
						</div>
						<div class="col-sm-6">
							<div class="form-group">
								<label class="control-label">Total Insitu</label>
								'.form_input(array('id'=>'total_insitu','name'=>'total_insitu','class'=>'form-control input-sm','readOnly'=>true),number_format($Total_Insitu)).'
							</div>
						</div>				
					</div>
					<div class="row">
						<div class="col-sm-6">
							<div class="form-group">
								<label class="control-label">Total Accommodation</label>
								'.form_input(array('id'=>'total_akomodasi','name'=>'total_akomodasi','class'=>'form-control input-sm','readOnly'=>true),number_format($Total_Akomodasi)).'
							</div>
						</div>
						<div class="col-sm-6">
							<div class="form-group">
								<label class="control-label">VAT ('.$Prosen_PPN.'%)</label>
								'.form_input(array('id'=>'total_ppn','name'=>'total_ppn','class'=>'form-control input-sm','readOnly'=>true),number_format($PPN)).'
							</div>
						</div>				
					</div>
					<div class="row">
						<div class="col-sm-6">
							<div class="form-group">
								<label class="control-label">Grand Total</label>
								'.form_input(array('id'=>'grand_total','name'=>'grand_total','class'=>'form-control input-sm','readOnly'=>true),number_format(($Total_Alat + $Total_Insitu + $Total_Akomodasi + $PPN))).'
							</div>
						</div>
						<div class="col-sm-6">&nbsp;</div>				
					</div>
					';
			}
		echo'</div>';
		
		?>
		
	</div>
</form>

<style>
	.sub-heading{
		border-radius :5px;
		background-color :#03506F;
		color : white;
		margin : 20px 10px 15px 10px !important;
		width :98% !important;
	}
	.ui-spinner-input{
		padding :10px 5px 10px 10px !important;
	}
</style>

