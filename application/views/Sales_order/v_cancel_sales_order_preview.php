
<div class="box box-warning">
	<div class="box-header">			
		<div class="box-tools pull-right">
			<?php 
				echo"";			
			?>
		</div>
	</div> 
	<div class="box-body">
		
		<?php
		if(empty($rows_header)){
			echo"<div class='row'>
					<div class='col-sm-12'>
						<h4 class='text-red'><b>NO RECORD WAS FOUND.....</b></h4>
					</div>
				</div>";
		}else{
			
		?>
			<div class="row">
				<div class="col-sm-12 col-xs-12 text-center sub-heading" style="color:white;">
					<h5>DETAIL RECEIVE</h5>
				</div>					
			</div>
			<div class='row'>
				<div class="col-sm-6">
					<div class="form-group">
						<label class="control-label">Receive No</label>
						<?php
							echo form_input(array('id'=>'receive_nomor','name'=>'receive_nomor','class'=>'form-control input-sm','readOnly'=>true),$rows_header->nomor);	
							echo form_input(array('id'=>'code_cancel','name'=>'code_cancel','class'=>'form-control input-sm','type'=>'hidden'),$rows_header->id);
						?>
					</div>
				</div>
				<div class="col-sm-6">
					<div class="form-group">
						<label class="control-label">Receive Date</label>
						<?php
							echo form_input(array('id'=>'tgl_rec','name'=>'tgl_rec','class'=>'form-control input-sm','readOnly'=>true),date('d-m-Y',strtotime($rows_header->datet)));						
						?>
					</div>
				</div>				
			</div>
			<div class="row">
				<div class="col-sm-12 col-xs-12 text-center sub-heading" style="color:white;">
					<h5>DETAIL QUOTATION</h5>
				</div>
				
			</div>
			<div class='row'>
				<div class="col-sm-6">
					<div class="form-group">
						<label class="control-label">Quotation No</label>
						<?php
							echo form_input(array('id'=>'nomor_quotation','name'=>'nomor_quotation','class'=>'form-control input-sm','readOnly'=>true),$rows_quot->nomor);	
							
						?>
					</div>
				</div>
				<div class="col-sm-6">
					<div class="form-group">
						<label class="control-label">Quotation Date</label>
						<?php
							echo form_input(array('id'=>'tgl_quot','name'=>'tgl_quot','class'=>'form-control input-sm','readOnly'=>true),date('d-m-Y',strtotime($rows_quot->datet)));						
						?>
					</div>
				</div>				
			</div>
			<div class='row'>
				<div class="col-sm-6">
					<div class="form-group">
						<label class="control-label">Customer</label>
						<?php
							echo form_input(array('id'=>'customer_name','name'=>'customer_name','class'=>'form-control input-sm','readOnly'=>true),$rows_quot->customer_name);						
						?>
					</div>
				</div>
				<div class="col-sm-6">
					<div class="form-group">
						<label class="control-label">Address</label>
						<?php
							echo form_textarea(array('id'=>'alamat','name'=>'alamat','class'=>'form-control input-sm','readOnly'=>true,'cols'=>75, 'rows'=>2),$rows_quot->address);						
						?>
					</div>
				</div>				
			</div>
			<div class='row'>
				<div class="col-sm-6">
					<div class="form-group">
						<label class="control-label">Marketing</label>
						<?php
							echo form_input(array('id'=>'marketing','name'=>'marketing','class'=>'form-control input-sm','readOnly'=>true),$rows_quot->member_name);						
						?>
					</div>
				</div>
				<div class="col-sm-6">
					<div class="form-group">
						<label class="control-label">PO No</label>
						<?php
							echo form_input(array('id'=>'pono','name'=>'pono','class'=>'form-control input-sm','readOnly'=>true),$rows_quot->pono);						
						?>
					</div>
				</div>				
			</div>
			<div class="row">
				<div class="col-sm-12 col-xs-12 text-center sub-heading" style="color:white;">
					<h5>DETAIL CANCELLATION</h5>
				</div>
				
			</div>
			<div class='row'>
				<div class="col-sm-6">
					<div class="form-group">
						<label class="control-label">Cancel Date</label>
						<?php
							echo form_input(array('id'=>'tgl_rec','name'=>'tgl_rec','class'=>'form-control input-sm','readOnly'=>true),date('d-m-Y',strtotime($rows_header->cancel_date)));						
						?>
					</div>
				</div>
				<div class="col-sm-6">
					<div class="form-group">
						<label class="control-label">Reason</label>
						<?php
							echo form_textarea(array('id'=>'cancel_reason','name'=>'cancel_reason','class'=>'form-control input-sm','readOnly'=>true,'cols'=>75, 'rows'=>2),$rows_header->cancel_reason);						
						?>
					</div>
				</div>				
			</div>
			
			<div class="row">
				<div class="col-sm-12 col-xs-12 text-center sub-heading" style="color:white;">
					<h5>DETAIL TOOLS </h5>
				</div>
				
			</div>
			<div class="row">
				<div class="col-sm-12" style="overflow-x:scroll !important;">
					<table class="table table-striped table-bordered" id="my-grid">
						<thead>
							<tr class="bg-navy-active">
								<th class="text-center">Code</th>
								<th class="text-center">Tool Code</th>
								<th class="text-center">Tool Name</th>				
								<th class="text-center">Qty Cancel</th>
								<th class="text-center">Description</th>
							</tr>
						</thead>
						<tbody id="list_detail_cancel">
							<?php
							if($rows_detail){
								$intL	= 0;
								foreach($rows_detail as $ketD=>$valD){
									$intL++;
									$Qty_Sisa	= $valD->qty;
									echo"<tr>";										
										echo"<td align='center'>".$valD->quotation_detail_id."</td>";
										echo"<td align='center'>".$valD->tool_id."</td>";
										echo"<td align='left'>".$valD->tool_name."</td>";										
										echo"<td align='center'>".$Qty_Sisa."</td>";
										echo"<td align='left'>".$valD->descr."</td>";
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
			?>
		</div>
	</div>

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
	.text-center{
		text-align : center !important;
		vertical-align	: middle !important;
	}
</style>
