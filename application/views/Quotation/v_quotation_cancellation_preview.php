
<div class="box box-warning">
	<div class="box-header">			
		<div class="box-tools pull-right">
			<?php 
				echo"";			
			?>
		</div>
	</div> 
	<div class="box-body">
		<div class="row">
			<div class="col-sm-12 col-xs-12 text-center sub-heading" style="color:white;">
				<h5><?php echo $title;?></h5>
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
						<label class="control-label">Nomor Quotation</label>
						<?php
							echo form_input(array('id'=>'nomor_quotation','name'=>'nomor_quotation','class'=>'form-control input-sm','readOnly'=>true),$rows_header[0]->nomor);	
							echo form_input(array('id'=>'code_quotation','name'=>'code_quotation','class'=>'form-control input-sm','type'=>'hidden'),$rows_header[0]->id);
						?>
					</div>
				</div>
				<div class="col-sm-6">
					<div class="form-group">
						<label class="control-label">Tanggal Quotation</label>
						<?php
							echo form_input(array('id'=>'tgl_quot','name'=>'tgl_quot','class'=>'form-control input-sm','readOnly'=>true),date('d-m-Y',strtotime($rows_header[0]->datet)));						
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
						<label class="control-label">Alamat</label>
						<?php
							echo form_textarea(array('id'=>'alamat','name'=>'alamat','class'=>'form-control input-sm','readOnly'=>true,'cols'=>75, 'rows'=>2),$rows_header[0]->address);						
						?>
					</div>
				</div>				
			</div>
			<div class='row'>
				<div class="col-sm-6">
					<div class="form-group">
						<label class="control-label">Marketing</label>
						<?php
							echo form_input(array('id'=>'marketing','name'=>'marketing','class'=>'form-control input-sm','readOnly'=>true),$rows_header[0]->member_name);						
						?>
					</div>
				</div>
				<div class="col-sm-6">
					<div class="form-group">
						<label class="control-label">Nomor PO</label>
						<?php
							echo form_input(array('id'=>'pono','name'=>'pono','class'=>'form-control input-sm','readOnly'=>true),$rows_header[0]->pono);						
						?>
					</div>
				</div>				
			</div>
			
			<div class="row">
				<div class="col-sm-12 col-xs-12 text-center sub-heading" style="color:white;">
					<h5>DETAIL ALAT </h5>
				</div>
				
			</div>
			<div class="row">
				<div class="col-sm-12" style="overflow-x:scroll !important;">
					<table class="table table-striped table-bordered" id="my-grid">
						<thead>
							<tr class="bg-navy-active">
								<th class="text-center">Kode</th>
								<th class="text-center">Kode Alat</th>
								<th class="text-center">Nama Alat</th>				
								<th class="text-center">Qty Batal</th>
								<th class="text-center">Alasan</th>
								<th class="text-center">Tgl Batal</th>
							</tr>
						</thead>
						<tbody id="list_detail_cancel">
							<?php
							if($rows_detail){
								$intL	= 0;
								foreach($rows_detail as $ketD=>$valD){
									$intL++;
									$Qty_Sisa	= $valD->qty_cancel;
									echo"<tr>";										
										echo"<td align='center'>".$valD->quotation_detail_id."</td>";
										echo"<td align='center'>".$valD->tool_id."</td>";
										echo"<td align='left'>".$valD->tool_name."</td>";										
										echo"<td align='center'>".$Qty_Sisa."</td>";
										echo"<td align='left'>".$valD->reason."</td>";
										echo"<td align='center'>".$valD->cancel_date."</td>";
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
</style>
