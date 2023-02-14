
<form action="#" method="POST" id="form_proses_preview" enctype="multipart/form-data">
	<div class="box box-warning">
		
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
							<label class="control-label">CPR No</label>
							<?php
								echo form_input(array('id'=>'nomor_cpr','name'=>'nomor_cpr','class'=>'form-control input-sm','readOnly'=>true),$rows_header[0]->id);	
								
							?>
						</div>
					</div>
					<div class="col-sm-6">
						<div class="form-group">
							<label class="control-label">CPR Date</label>
							<?php
								echo form_input(array('id'=>'tgl_cpr','name'=>'tgl_cpr','class'=>'form-control input-sm','readOnly'=>true),$rows_header[0]->datet);						
							?>
						</div>
					</div>				
				</div>
				<div class='row'>
					<div class="col-sm-6">
						<div class="form-group">
							<label class="control-label">Technician</label>
							<?php
								echo form_input(array('id'=>'technician_name','name'=>'technician_name','class'=>'form-control input-sm','readOnly'=>true),$rows_header[0]->member_name);						
							?>
						</div>
					</div>
					<div class="col-sm-6">
						<div class="form-group">
							<label class="control-label">Description</label>
							<?php
								echo form_textarea(array('id'=>'descr','name'=>'descr','class'=>'form-control input-sm','readOnly'=>true,'cols'=>75, 'rows'=>2),$rows_header[0]->descr);						
							?>
						</div>
					</div>				
				</div>
				<?php
				$Label_Button	= '';
				$Code_Button	= '';
				if($category == 'update'){
					$Label_Button	= 'UPDATE PAYMENT PROCESS';
					$Code_Button	= 'btn_upd_payment';
				?>
				<div class="row">
					<div class="col-sm-12 col-xs-12 text-center sub-heading" style="color:white;">
						<h5>DETAIL PAYMENT</h5>
					</div>
					
				</div>
				<div class='row'>
					<div class="col-sm-6">
						<label class="control-label">Payment Date <span class="text-red">*</span></label>
						<?php
							echo form_input(array('id'=>'buk_tgl','name'=>'buk_tgl','class'=>'form-control input-sm tanggal','readOnly'=>true,'autocomplete'=>'off'));						
						?>
					</div>
					
					<div class="col-sm-6">
						<label class="control-label">Payment Reff No <span class="text-red">*</span></label>
						<?php
							echo form_input(array('id'=>'buk_id','name'=>'buk_id','class'=>'form-control input-sm','autocomplete'=>'off','style'=>'text-transform:uppercase;'));						
						?>
					</div>		
				</div>
				<?php
				}else if($category == 'cancel'){
					$Label_Button	= 'CANCELLATION PROCESS';
					$Code_Button	= 'btn_cancel_cpr';
				?>
				<div class="row">
					<div class="col-sm-12 col-xs-12 text-center sub-heading" style="color:white;">
						<h5>DETAIL CANCELLATION</h5>
					</div>
					
				</div>
				<div class='row'>
					<div class="col-sm-6">
						<div class="form-group">
							<label class="control-label">Cancel Reason <span class="text-red">*</span></label>
							<?php
								echo form_textarea(array('id'=>'cancel_reason','name'=>'cancel_reason','class'=>'form-control input-sm','cols'=>75, 'rows'=>2));						
							?>
						</div>
					</div>
					
					<div class="col-sm-6">&nbsp;</div>		
				</div>
				<?php
				}
				?>
				<div class="row">
					<div class="col-sm-12 col-xs-12 text-center sub-heading" style="color:white;">
						<h5>DETAIL SO </h5>
					</div>
					
				</div>
				
				<div class="row">
					<div class="col-sm-12" style="overflow-x:scroll !important;">
						<table class="table table-striped table-bordered" id="my-grid">
							<thead>
								<tr class="bg-navy-active">
									<th class="text-center">Customer</th>
									<th class="text-center">SO No</th>
									<th class="text-center">Invoice No</th>
									<th class="text-center">Quotation</th>
									<th class="text-center">Total Nett</th>
									<th class="text-center">Incentive<br>(%)</th>
									<th class="text-center">Total<br>Incentive</th>
								</tr>
							</thead>
							<tbody id="list_detail">
								<?php
								if($rows_detail){
									$intL	= 0;
									foreach($rows_detail as $ketD=>$valD){
										$intL++;										
										$rows_SO	= $this->db->get_where('letter_orders',array('id'=>$valD->letter_order_id))->row();
										
										echo"<tr>";			
											echo"<td class='text-left text-wrap'>".$valD->customer_name."</td>";
											echo"<td class='text-center'>".$rows_SO->no_so."</td>";											
											echo"<td class='text-center'>".$valD->invoice_no."</td>";											
											echo"<td class='text-center'>".$valD->quotation_nomor."</td>";	
											echo"<td class='text-right'>".number_format($valD->net_total)."</td>";
											echo"<td class='text-center'>".number_format($valD->nil_incentive)."</td>";
											echo"<td class='text-right'>".number_format($valD->tot_incentive)."</td>";
										echo"</tr>";
									}
								}
								?>
							</tbody>
							<tfoot>
								<tr class='bg-gray'>
									<th class='text-right' colspan='6'><b>Grand Total</b></th>
									<th class='text-right'>
										<?php
										echo number_format($rows_header[0]->total);
										?>
									</th>
									
								</tr>
							</tfoot>
						</table>
					</div>
				</div>
				
			<?php
			}
		echo'</div>';
		if(!empty($rows_header) && ($category == 'update' || $category == 'cancel')){
			echo '
			<div class="box-footer">
				<button type="button" class="btn btn-md bg-orange-active" id="'.$Code_Button.'" title="'.$Label_Button.'"> '.$Label_Button.' <i clas="fa fa-arrow-right"></i> </button>
			</div>
			';
		}
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
	.text-wrap{
		word-wrap:break-word !important;
	}
	.text-center{
		text-align:center !important;
		vertical-align:middle !important;
	}
	.text-left{
		text-align:left !important;
		vertical-align:middle !important;
	}
	.text-right{
		text-align:right !important;
		vertical-align:middle !important;
	}
</style>

<script>
	
	$(document).ready(function(){
		$('.tanggal').datepicker({
			dateFormat: 'yy-mm-dd',
			changeMonth:true,
			changeYear:true,
			maxDate:'+0d'
		});
	});
	
	
	
</script>