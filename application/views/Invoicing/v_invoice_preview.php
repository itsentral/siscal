
<form action="#" method="POST" id="form-proses" enctype="multipart/form-data">
	<div class="box box-warning">
		
		<div class="box-body">
			<div class="row">
				<div class="col-sm-12 col-xs-12 text-center sub-heading" style="color:white;">
					<h5>DETAIL INVOICE</h5>
				</div>
				
			</div>
			<?php
			if(empty($rows_header)){
				echo"<div class='row'>
						<div class='col-sm-12'>
							<h4 class='text-red'><b>NO RECORD WAS FOUND.....</b></h4>
						</div>
					</div>";
			}else{
				
			?>
				<div class='row'>
					<div class="col-sm-6">
						<div class="form-group">
							<label class="control-label">Invoice No</label>
							<?php
								echo form_input(array('id'=>'nomor_invoice','name'=>'nomor_invoice','class'=>'form-control input-sm','readOnly'=>true),$rows_header[0]->invoice_no);	
								
							?>
						</div>
					</div>
					<div class="col-sm-6">
						<div class="form-group">
							<label class="control-label">Invoice Date</label>
							<?php
								echo form_input(array('id'=>'tgl_invoice','name'=>'tgl_invoice','class'=>'form-control input-sm','readOnly'=>true),$rows_header[0]->datet);						
							?>
						</div>
					</div>				
				</div>
				<div class='row'>
					<div class="col-sm-6">
						<div class="form-group">
							<label class="control-label">Customer</label>
							<?php
								echo form_input(array('id'=>'customer_name','name'=>'customer_name','class'=>'form-control input-sm','readOnly'=>true),$rows_header[0]->customer_name);						
							?>
						</div>
					</div>
					<div class="col-sm-6">
						<div class="form-group">
							<label class="control-label">Address</label>
							<?php
								echo form_textarea(array('id'=>'alamat','name'=>'alamat','class'=>'form-control input-sm','readOnly'=>true,'cols'=>75, 'rows'=>2),$rows_header[0]->address);						
							?>
						</div>
					</div>				
				</div>
				<div class='row'>
					<div class="col-sm-6">
						<div class="form-group">
							<label class="control-label">DPP</label>
							<?php
								echo form_input(array('id'=>'dpp','name'=>'dpp','class'=>'form-control input-sm','readOnly'=>true),number_format($rows_header[0]->dpp));						
							?>
						</div>
					</div>
					<div class="col-sm-6">
						<div class="form-group">
							<label class="control-label">Discount</label>
							<?php
								echo form_input(array('id'=>'diskon','name'=>'diskon','class'=>'form-control input-sm','readOnly'=>true),number_format($rows_header[0]->diskon));						
							?>
						</div>
					</div>				
				</div>
				<div class='row'>
					<div class="col-sm-6">
						<div class="form-group">
							<label class="control-label">VAT</label>
							<?php
								echo form_input(array('id'=>'ppn','name'=>'ppn','class'=>'form-control input-sm','readOnly'=>true),number_format($rows_header[0]->ppn));						
							?>
						</div>
					</div>
					<div class="col-sm-6">
						<div class="form-group">
							<label class="control-label">Invoice Total</label>
							<?php
								echo form_input(array('id'=>'grand_tot','name'=>'grand_tot','class'=>'form-control input-sm','readOnly'=>true),number_format($rows_header[0]->grand_tot));						
							?>
						</div>
					</div>				
				</div>
				<div class="row">
					<div class="col-sm-12 col-xs-12 text-center sub-heading" style="color:white;">
						<h5>SEND & RECEIVE INVOICE DETAIL</h5>
					</div>					
				</div>
				<div class='row'>
					<div class="col-sm-6">
						<div class="form-group">
							<label class="control-label">Send Date</label>
							<?php
								echo form_input(array('id'=>'send_date','name'=>'send_date','class'=>'form-control input-sm','readOnly'=>true),$rows_header[0]->send_date);	
								
							?>
						</div>
					</div>
					<div class="col-sm-6">
						<div class="form-group">
							<label class="control-label">Receive Date</label>
							<?php
								echo form_input(array('id'=>'receive_date','name'=>'receive_date','class'=>'form-control input-sm','readOnly'=>true),$rows_header[0]->receive_date);						
							?>
						</div>
					</div>				
				</div>
				<div class='row'>
					<div class="col-sm-6">
						<div class="form-group">
							<label class="control-label">Receive By</label>
							<?php
								echo form_input(array('id'=>'receive_by','name'=>'receive_by','class'=>'form-control input-sm','readOnly'=>true),$rows_header[0]->receive_by);	
								
							?>
						</div>
					</div>
					<div class="col-sm-6">&nbsp;</div>				
				</div>
				
				<div class="row">
					<div class="col-sm-12 col-xs-12 text-center sub-heading" style="color:white;">
						<h5>DETAIL ITEM </h5>
					</div>
					
				</div>
				<div class="row">
					<div class="col-sm-12" style="overflow-x:scroll !important;">
						<table class="table table-striped table-bordered" id="my-grid">
							<thead>
								<tr class="bg-navy-active">
									<th class="text-center">Tool</th>
									<th class="text-center">PO No</th>
									<th class="text-center">SO No</th>				
									<th class="text-center">Price</th>
									<th class="text-center">Qty</th>
									<th class="text-center">Sub Total</th>
									<th class="text-center">Discount</th>
									<th class="text-center">Total</th>
								</tr>
							</thead>
							<tbody id="list_detail">
								<?php
								if($rows_detail){
									$intL	= 0;
									foreach($rows_detail as $ketD=>$valD){
										$intL++;
										$Nomor_PO = $Nomor_SO = '- ';
										$Query_SO	= "SELECT no_so FROM letter_orders WHERE id = '".$valD->letter_order_id."'";
										$rows_SO	= $this->db->query($Query_SO)->result();
										if($rows_SO){
											$Nomor_SO	= $rows_SO[0]->no_so;
										}
										
										$Query_PO	= "SELECT pono FROM quotations WHERE id = '".$valD->quotation_id."'";
										
										$rows_PO	= $this->db->query($Query_PO)->result();
										if($rows_PO){
											$Nomor_PO	= $rows_PO[0]->pono;
										}
										
										echo"<tr>";										
											echo"<td align='left'>".$valD->tool_name."</td>";
											echo"<td align='center'>".$Nomor_PO."</td>";
											echo"<td align='center'>".$Nomor_SO."</td>";										
											echo"<td align='right'>".number_format($valD->price)."</td>";
											echo"<td align='center'>".number_format($valD->qty)."</td>";
											echo"<td align='right'>".number_format($valD->total_harga)."</td>";
											echo"<td align='right'>".number_format($valD->total_discount)."</td>";
											echo"<td align='right'>".number_format($valD->total_harga - $valD->total_discount)."</td>";
										echo"</tr>";
									}
								}
								?>
							</tbody>
						</table>
					</div>
				</div>
				
			<?php
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

