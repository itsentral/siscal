
<form action="#" method="POST" id="form-proses-cancel" enctype="multipart/form-data">
	<div class="box box-warning">
		
		<div class="box-body">
			<div class="row">
				<div class="col-sm-12 col-xs-12 text-center sub-heading" style="color:white;">
					<h5>DETAIL SUBCON PURCHASE ORDER</h5>
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
							<label class="control-label">PO No</label>
							<?php
								echo form_input(array('id'=>'nomor_po','name'=>'nomor_po','class'=>'form-control input-sm','readOnly'=>true),$rows_header->subcon_pono);	
								echo form_input(array('id'=>'code_process','name'=>'code_process','type'=>'hidden'),$rows_header->id);
							?>
						</div>
					</div>
					<div class="col-sm-6">
						<div class="form-group">
							<label class="control-label">PO Date</label>
							<?php
								echo form_input(array('id'=>'tgl_po','name'=>'tgl_po','class'=>'form-control input-sm','readOnly'=>true),$rows_header->datet);						
							?>
						</div>
					</div>				
				</div>
				<div class='row'>
					<div class="col-sm-6">
						<div class="form-group">
							<label class="control-label">Subcon</label>
							<?php
								echo form_input(array('id'=>'supplier','name'=>'supplier','class'=>'form-control input-sm','readOnly'=>true),$rows_header->supplier_name);						
							?>
						</div>
					</div>
					<div class="col-sm-6">
						<div class="form-group">
							<label class="control-label">PIC Name</label>
							<?php
								echo form_input(array('id'=>'pic_name','name'=>'pic_name','class'=>'form-control input-sm','readOnly'=>true),$rows_header->pic_name);							
							?>
						</div>
					</div>				
				</div>
				<div class='row'>
					<div class="col-sm-6">
						<div class="form-group">
							<label class="control-label">Address</label>
							<?php
								echo form_textarea(array('id'=>'alamat','name'=>'alamat','class'=>'form-control input-sm','readOnly'=>true,'cols'=>75, 'rows'=>2),$rows_header->address);						
							?>
						</div>
					</div>
					<div class="col-sm-6">
						<div class="form-group">
							<label class="control-label">Exclude VAT (PPN)</label>
							<div>
							<?php
								$Lable_VAT	= '<span class="badge bg-orange-active">NO</span>';
								if($rows_header->exc_ppn == 'Y'){
									$Lable_VAT	= '<span class="badge bg-orange-active">YES</span>';
								}
								echo $Lable_VAT;					
							?>
							</div>
						</div>
					</div>				
				</div>
				
				<?php
				$Sub_Total_Insitu	= $Sub_Total_Akom = $Sub_Total_Alat	=0;
				if($rows_detail){
					echo'
					<div class="row">
						<div class="col-sm-12 col-xs-12 text-center sub-heading" style="color:white;">
							<h5>DETAIL ITEM</h5>
						</div>					
					</div>
					<div class="row">
						<div class="col-sm-12" style="overflow-x:scroll !important;">
							<table class="table table-striped table-bordered" id="my-grid">
								<thead>
									<tr class="bg-navy-active">
										<th class="text-center">Tool</th>
										<th class="text-center">Insitu</th>
										<th class="text-center">Customer</th>				
										<th class="text-center">Price</th>
										<th class="text-center">Qty</th>
										<th class="text-center">Discount</th>
										<th class="text-center">Total</th>
										<th class="text-center">SO No</th>
										<th class="text-center">Description</th>
										<th class="text-center">Notes</th>
									</tr>
								</thead>
								<tbody id="list_detail">';
								$intL	= 0;
								foreach($rows_detail as $ketD=>$valD){
									$intL++;
									$Harga_After	= round($valD->price * ((100 - $valD->discount)/100));
									$Sub_Total_Alat		+=($Harga_After * $valD->qty);
									$Nomor_PO = $Nomor_SO = '- ';
									$Query_SO	= "SELECT no_so FROM letter_orders WHERE id = '".$valD->letter_order_id."'";
									$rows_SO	= $this->db->query($Query_SO)->row();
									if($rows_SO){
										$Nomor_SO	= $rows_SO->no_so;
									}
									
									
									
									echo"<tr>";										
										echo"<td class='text-left text-wrap'>".$valD->tool_name."</td>";
										echo"<td class='text-center'>".$valD->flag_insitu."</td>";
										echo"<td class='text-left text-wrap'>".$valD->customer_name."</td>";										
										echo"<td class='text-right'>".number_format($valD->price)."</td>";
										echo"<td class='text-center'>".number_format($valD->qty)."</td>";
										echo"<td class='text-right'>".number_format($valD->discount,2)."</td>";
										echo"<td class='text-right'>".number_format($Harga_After * $valD->qty)."</td>";
										echo"<td class='text-center'>".$Nomor_SO."</td>";
										echo"<td class='text-left text-wrap'>".$valD->descr."</td>";
										echo"<td class='text-left text-wrap'>".$valD->notes."</td>";
									echo"</tr>";
								}
					echo'
								</tbody>
								<tfoot>
									<tr class="bg-gray">
										<th class="text-right" colspan="6">Sub Total</th>
										<th class="text-right">'.number_format($Sub_Total_Alat).'</th>
										<th class="text-center" colspan="3">-</th>
									</tr>
								</tfoot>
							</table>
						</div>
					</div>
					';
				}
				if($rows_delivery){
					echo'
					<div class="row">
						<div class="col-sm-12 col-xs-12 text-center sub-heading" style="color:white;">
							<h5>DETAIL INSITU </h5>
						</div>						
					</div>
					<div class="row">
						<div class="col-sm-12" style="overflow-x:scroll !important;">
							<table class="table table-striped table-bordered" id="my-grid">
								<thead>
									<tr class="bg-navy-active">
										<th class="text-center">Area</th>
										<th class="text-center">Fee</th>
										<th class="text-center">Duration (Days)</th>				
										<th class="text-center">Sub Total</th>
										<th class="text-center">Discount</th>
										<th class="text-center">Total</th>
									</tr>
								</thead>
								<tbody id="list_detail">';
								$intD	= 0;
								foreach($rows_delivery as $ketDel=>$valDel){
									$intD++;
									$Harga_After			= round($valDel->fee * $valDel->day);
									$Sub_Total_Insitu		+= $valDel->total;
								
									
									echo"<tr>";										
										echo"<td class='text-left text-wrap'>".$valDel->delivery_name."</td>";
										echo"<td class='text-right'>".number_format($valDel->fee)."</td>";
										echo"<td class='text-center'>".number_format($valDel->day)."</td>";
										echo"<td class='text-right'>".number_format($Harga_After)."</td>";
										echo"<td class='text-right'>".number_format($valDel->diskon)."</td>";
										echo"<td class='text-right'>".number_format($valDel->total)."</td>";
									echo"</tr>";
								}
					echo'
								</tbody>
								<tfoot>
									<tr class="bg-gray">
										<th class="text-right" colspan="5">Sub Total</th>
										<th class="text-right">'.number_format($Sub_Total_Insitu).'</th>
									</tr>
								</tfoot>
							</table>
						</div>
					</div>
					';
				}
				
				if($rows_akomodasi){
					echo'
					<div class="row">
						<div class="col-sm-12 col-xs-12 text-center sub-heading" style="color:white;">
							<h5>DETAIL ACCOMMODATION </h5>
						</div>						
					</div>
					<div class="row">
						<div class="col-sm-12" style="overflow-x:scroll !important;">
							<table class="table table-striped table-bordered" id="my-grid">
								<thead>
									<tr class="bg-navy-active">
										<th class="text-center">Description</th>
										<th class="text-center">Fee</th>
										<th class="text-center">Discount</th>
										<th class="text-center">Total</th>
									</tr>
								</thead>
								<tbody id="list_detail">';
								$intD	= 0;
								foreach($rows_akomodasi as $ketDel=>$valDel){
									$intD++;
									$Sub_Total_Akom		+= $valDel->total;
								
									
									echo"<tr>";										
										echo"<td class='text-left text-wrap'>".$valDel->accommodation_name."</td>";
										echo"<td class='text-right'>".number_format($valDel->nilai)."</td>";
										echo"<td class='text-right'>".number_format($valDel->diskon)."</td>";
										echo"<td class='text-right'>".number_format($valDel->total)."</td>";
									echo"</tr>";
								}
					echo'
								</tbody>
								<tfoot>
									<tr class="bg-gray">
										<th class="text-right" colspan="3">Sub Total</th>
										<th class="text-right">'.number_format($Sub_Total_Akom).'</th>
									</tr>
								</tfoot>
							</table>
						</div>
					</div>
					';
				}
				
				?>
				
				<div class="row">
					<div class="col-sm-12 col-xs-12 text-center sub-heading" style="color:white;">
						<h5>SUMMARY </h5>
					</div>						
				</div>
				<div class='row'>
					<div class="col-sm-6">
						<div class="form-group">
							<label class="control-label">Total Tools</label>
							<?php
								echo form_input(array('id'=>'total_alat','name'=>'total_alat','class'=>'form-control input-sm','readOnly'=>true),number_format($Sub_Total_Alat));						
							?>
						</div>
					</div>
					<div class="col-sm-6">
						<div class="form-group">
							<label class="control-label">VAT (PPN)</label>
							<?php
								echo form_input(array('id'=>'ppn','name'=>'ppn','class'=>'form-control input-sm','readOnly'=>true),number_format($rows_header->ppn));						
							?>
						</div>
					</div>				
				</div>
				<div class='row'>
					<div class="col-sm-6">
						<div class="form-group">
							<label class="control-label">Total Insitu</label>
							<?php
								echo form_input(array('id'=>'total_insitu','name'=>'total_insitu','class'=>'form-control input-sm','readOnly'=>true),number_format($Sub_Total_Insitu));						
							?>
						</div>
					</div>
					<div class="col-sm-6">
						<div class="form-group">
							<label class="control-label">Total Accommodation</label>
							<?php
								echo form_input(array('id'=>'total_akomodasi','name'=>'total_akomodasi','class'=>'form-control input-sm','readOnly'=>true),number_format($Sub_Total_Akom));						
							?>
						</div>
					</div>				
				</div>
				<div class='row'>
					<div class="col-sm-6">
						<div class="form-group">
							<label class="control-label">Grand Total</label>
							<?php
								echo form_input(array('id'=>'grand_total','name'=>'grand_total','class'=>'form-control input-sm','readOnly'=>true),number_format($rows_header->grand_tot));						
							?>
						</div>
					</div>
					<div class="col-sm-6">
						&nbsp;
					</div>				
				</div>
				<?php
				if($category === 'cancel'){
					echo'
					<div class="row">
						<div class="col-sm-12 col-xs-12 text-center sub-heading" style="color:white;">
							<h5>DETAIL CANCELLATION</h5>
						</div>				
					</div>
					<div class="row">
						<div class="col-sm-6">
							<div class="form-group">
								<label class="control-label">Cancel Reason <span class="text-red"> *</span></label>							
								'.form_textarea(array('id'=>'cancel_reason', 'name'=>'cancel_reason','cols'=>'75','rows'=>'2', 'class'=>'form-control input-sm text-up')).'									
														
							</div>
						</div>
						<div class="col-sm-6">&nbsp;</div>
					</div>
					';
				}
				?>
			<?php
			}
		echo'</div>';
		
		if($category !== 'view'){
			echo'
			<div class="box-body">
				<div class="row col-md-2 col-md-offset-5" id="loader_proses_save">
					<div class="loader">
						<span></span>
						<span></span>
						<span></span>
						<span></span>
					</div>
				</div>
			</div>
			<div class="box-footer">			
				<button type="button" id="btn_cancel_order" class="btn btn-md text-center" style="background-color:#37474f; color:white;vertical-align:middle !important;" title="CANCELLATION PROCESS"> CANCELLATION PROCESS <i class="fa fa-long-arrow-right" style="width:40px;"></i> </button>
			</div>';
		}
		
		?>
	</div>
</form>


<style>
	
	.ui-spinner-input{
		padding :10px 5px 10px 10px !important;
	}
	.text-wrap{
		word-wrap : break-word !important;
	}
	/* LOADER */
	.loader span{
	  display: inline-block;
	  width: 12px;
	  height: 12px;
	  border-radius: 100%;
	  background-color: #3498db;
	  margin: 35px 5px;
	  opacity: 0;
	}

	.loader span:nth-child(1){
		background: #4285F4;
	  	animation: opacitychange 1s ease-in-out infinite;
	}

	.loader span:nth-child(2){
  		background: #DB4437;
	 	animation: opacitychange 1s ease-in-out 0.11s infinite;
	}

	.loader span:nth-child(3){
  		background: #F4B400;
	  	animation: opacitychange 1s ease-in-out 0.22s infinite;
	}
	.loader span:nth-child(4){
  		background: #0F9D58;
	  	animation: opacitychange 1s ease-in-out 0.44s infinite;
	}
	@keyframes opacitychange{
	  0%, 100%{
		opacity: 0;
	  }

	  60%{
		opacity: 1;
	  }
	}
	
	.text-up{
		text-transform : uppercase !important;
	}
		
	.sub-heading{
		border-radius :5px;
		background-color :#03506F;
		color : white;
		margin : 20px 10px 15px 10px !important;
		width :98% !important;
	}
	
	.text-center{
		text-align : center !important;
		vertical-align	: middle !important;
	}
	
	.text-left{
		text-align : left !important;
		vertical-align	: middle !important;
	}
	
	.text-right{
		text-align : right !important;
		vertical-align	: middle !important;
	}
</style>
<script>
	$(document).ready(function(){
		$('#loader_proses_save').hide();
	});
</script>
