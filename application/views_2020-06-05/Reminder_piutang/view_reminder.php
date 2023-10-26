<?php
$this->load->view('include/side_menu'); 

?> 
<form action="#" method="POST" id="form-proses">
	<div class="box box-warning">
		<div class="box-header">
			<h3 class="box-title">
				<i class="fa fa-envelope"></i> <?php echo('<span class="important">'.$title.'</span>'); ?>
			</h3>
			
		</div>
		<div class="box-body">
			<div class='form-group row'>			
				<label class='label-control col-sm-2'><b>Letter No <span class='text-red'>*</span></b></label> 
				<div class='col-sm-4'>
					<?php
						echo form_input(array('id'=>'nomor_surat','name'=>'nomor_surat','class'=>'form-control input-sm','readOnly'=>true),$rows_header[0]->nomor_surat);
					?>							
				</div>
				<label class='label-control col-sm-2'><b>Letter Date <span class='text-red'>*</span></b></label>
				<div class='col-sm-4'>
					<?php
						echo form_input(array('id'=>'datet','name'=>'datet','class'=>'form-control input-sm','readOnly'=>true),date('d F Y',strtotime($rows_header[0]->datet)));
					?>							
				</div>				
			</div>
			<div class='form-group row'>			
				<label class='label-control col-sm-2'><b>Customer <span class='text-red'>*</span></b></label> 
				<div class='col-sm-4'>
					<?php
						echo form_input(array('id'=>'customer_name','name'=>'customer_name','class'=>'form-control input-sm','readOnly'=>true),$rows_header[0]->customer_name);
						
					?>							
				</div>
				<label class='label-control col-sm-2'><b>Address <span class='text-red'>*</span></b></label>
				<div class='col-sm-4'>
					<?php
						echo form_textarea(array('id'=>'address', 'name'=>'address','cols'=>'75','rows'=>'2', 'class'=>'form-control input-sm','disabled'=>true,'value'=>$rows_cust[0]->address));
					?>							
				</div>				
			</div>
			<div class='form-group row'>			
				<label class='label-control col-sm-2'><b>PIC Name <span class='text-red'>*</span></b></label> 
				<div class='col-sm-4'>
					<?php
						echo form_input(array('id'=>'pic_name','name'=>'pic_name','class'=>'form-control input-sm','readOnly'=>true),$rows_header[0]->pic_name);
					?>							
				</div>
				<label class='label-control col-sm-2'><b>PIC Email <span class='text-red'>*</span></b></label>
				<div class='col-sm-4'>
					<?php
						echo form_input(array('id'=>'pic_email','name'=>'pic_email','class'=>'form-control input-sm','readOnly'=>true),$rows_header[0]->pic_email);
					?>							
				</div>				
			</div>
			<div class='form-group row'>			
				<label class='label-control col-sm-2'><b>Status <span class='text-red'>*</span></b></label> 
				<div class='col-sm-4'>
					<?php
						$Canc_By	= $Canc_Date = $Canc_Reason ="-";
						$status  = $rows_header[0]->sts_letter;
						if($status=='OPN'){
							echo "<span class='badge bg-green'>OPEN</span>";
						}else if($status=='CNC'){
							echo "<span class='badge bg-orange'>CANCELED</span>";
							$Canc_By	= $rows_header[0]->cancel_by;
							$Canc_Date	= date('d M Y H:i',strtotime($rows_header[0]->cancel_date));
							$Canc_Reason= $rows_header[0]->cancel_reason;
						}else if($status=='CLS'){
							echo "<span class='badge bg-red'>CLOSE</span>";
						}
					?>							
				</div>
				<label class='label-control col-sm-2'><b>Cancel By </b></label>
				<div class='col-sm-4'>
					<?php
						echo form_input(array('id'=>'cancel_by','name'=>'cancel_by','class'=>'form-control input-sm','readOnly'=>true),$Canc_By);
					?>							
				</div>				
			</div>
			<div class='form-group row'>			
				<label class='label-control col-sm-2'><b>Cancel Date</b></label> 
				<div class='col-sm-4'>
					<?php
						echo form_input(array('id'=>'cancel_date','name'=>'cancel_date','class'=>'form-control input-sm','readOnly'=>true),$Canc_Date);
					?>							
				</div>
				<label class='label-control col-sm-2'><b>Cancel Reason </b></label>
				<div class='col-sm-4'>
					<?php
						echo form_textarea(array('id'=>'cancel_reason', 'name'=>'cancel_reason','cols'=>'75','rows'=>'2', 'class'=>'form-control input-sm','readOnly'=>true,'value'=>$Canc_Reason))
						
					?>							
				</div>				
			</div>
		</div>
		<div class="box-body">
			<table id="my-grid" class="table table-bordered table-striped">
				<thead>					
					<tr class="bg-blue">
						<th class="text-center">No</th>
						<th class="text-center">Invoice</th>
						<th class="text-center">Receive By</th>
						<th class="text-center">Receive Date</th>
						<th class="text-center">Total</th>
						<th class="text-center">Total Payment</th>
						<th class="text-center">Debt</th>
						<th class="text-center">Aging (Day)</th>
					</tr>
				</thead>

				<tbody id="list_detail">
				<?php
					if($rows_detail){
						$intI	= 0;
						foreach($rows_detail as $key=>$vals){
							$intI++;
							$no_Inv		= $vals->invoice_no;
							
							$tot_Inv	= $vals->total_invoice;
							$tot_Pay	= $vals->total_bayar;
							$tot_Debt	= $vals->total_piutang;
							$aging_Inv	= $vals->umur_piutang;
							$rec_By = $rec_Date ='-';
							if($vals->receive_date){
								$rec_Date	= date('d M Y',strtotime($vals->receive_date));
								$rec_By		= $vals->receive_by;
							}
							
							echo"<tr id='tr_".$intI."'>";
								
								echo"<td class='text-center'>".$intI."</td>";
								echo"<td class='text-center'>".$no_Inv."</td>";
								echo"<td class='text-center'>".$rec_By."</td>";
								echo"<td class='text-center'>".$rec_Date."</td>";
								echo"<td class='text-right'>".number_format($tot_Inv)."</td>";
								echo"<td class='text-right'>".number_format($tot_Pay)."</td>";
								echo"<td class='text-right'>".number_format($tot_Debt)."</td>";
								echo"<td class='text-center'>".$aging_Inv."</td>";
								
							echo"</tr>";
							
						}
					}				
				?>
				</tbody>
				
			</table>
					
		</div>
		<div class="box-footer">
			<?php
				echo"<button type='button' class='btn btn-md btn-danger' id='btn-back'> <i class='fa fa-angle-double-left'></i> Back </button>";	
			?>
		</div>
	</div>
</form>
<?php $this->load->view('include/footer'); ?>
<script>
	var base_url			= '<?php echo base_url(); ?>';
	var active_controller	= '<?php echo($this->uri->segment(1)); ?>';
	$(document).ready(function(){
		
		$('#btn-back').click(function(){			
			loading_spinner();
			window.location.href =  base_url+active_controller;
		});
		
	});
	
	
</script>
