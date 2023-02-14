
<form action="#" method="POST" id="form_proses_cancel_receive" enctype="multipart/form-data">
	<div class="box box-warning">
		<?php
		if(empty($rows_header)){
			echo"
			<div class='box-body'>
				<div class='row'>
					<div class='col-sm-12'>
						<h4 class='text-red'><b>NO RECORD WAS FOUND.....</b></h4>
					</div>
				</div>
			</div>
				";
		}else{
			$Type_Cust		= $rows_header->rec_category;
		
			$Receive_By		= $rows_header->rec_by;
			if($Type_Cust === 'CUSTOMER'){
				$Ket_Comp	= '<span class="badge" style="background-color:#c2185b !important;color:#ffffff !important;">SEND BY CUSTOMER</span>';
			}else{
				$Ket_Comp	= '<span class="badge" style="background-color:#0277bd !important;color:#ffffff !important;">PICKUP BY DRIVER</span>';
				$Receive_By	= $rows_header->driver_name;
			}
		?>
		<div class="box-body">			
		
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
							echo form_input(array('id'=>'receive_no','name'=>'receive_no','class'=>'form-control input-sm','readOnly'=>true),$rows_header->nomor);
							echo form_input(array('id'=>'code_receive','name'=>'code_receive','type'=>'hidden'),$rows_header->id);
							
						?>
					</div>
				</div>
				<div class="col-sm-6">
					<div class="form-group">
						<label class="control-label">Receive Date</label>
						<?php
							echo form_input(array('id'=>'rec_date','name'=>'rec_date','class'=>'form-control input-sm','readOnly'=>true),date('d-m-Y',strtotime($rows_header->datet)));						
						?>
					</div>
				</div>				
			</div>
			<div class='row'>
				<div class="col-sm-6">
					<div class="form-group">
						<label class="control-label">Category</label>
						<div>
						<?php
							echo $Ket_Comp;
													
						?>
						</div>
					</div>
				</div>
				<div class="col-sm-6">
					<div class="form-group">
						<label class="control-label">Receive By</label>
						<?php
							echo form_input(array('id'=>'receive_by','name'=>'receive_by','class'=>'form-control input-sm','readOnly'=>true),$Receive_By);				
						?>
					</div>
				</div>				
			</div>
			<div class='row'>
				<div class="col-sm-6">
					<div class="form-group">
						<label class="control-label">Quotation</label>
						<?php
							echo form_input(array('id'=>'quotation_nomor','name'=>'quotation_nomor','class'=>'form-control input-sm','readOnly'=>true),$rows_quot->nomor);
													
						?>
					</div>
				</div>
				<div class="col-sm-6">
					<div class="form-group">
						<label class="control-label">Customer</label>
						<?php
							echo form_input(array('id'=>'customer','name'=>'customer','class'=>'form-control input-sm','readOnly'=>true),$rows_header->customer_name);				
						?>
					</div>
				</div>				
			</div>			
			<div class='row'>
				<div class="col-sm-6">
					<div class="form-group">
						<label class="control-label">Address</label>
						
						<?php
							echo form_textarea(array('id'=>'address', 'name'=>'address','cols'=>'75','rows'=>'2', 'class'=>'form-control input-sm','readOnly'=>true),$rows_quot->address);
							
													
						?>
						
					</div>
				</div>
				<div class="col-sm-6">
					<div class="form-group">
						<label class="control-label">Marketing</label>
						<?php
							echo form_input(array('id'=>'marketing','name'=>'marketing','class'=>'form-control input-sm','readOnly'=>true),$rows_quot->member_name);
													
						?>
						
					</div>
				</div>				
			</div>
			<div class='row'>
				<div class="col-sm-6">
					<div class="form-group">
						<label class="control-label">Cancel Reason <span class="text-red">*</span></label>
						
						<?php
							echo form_textarea(array('id'=>'cancel_reason', 'name'=>'cancel_reason','cols'=>'75','rows'=>'2', 'class'=>'form-control input-sm'));
							
													
						?>
						
					</div>
				</div>
				<div class="col-sm-6">
					&nbsp;
				</div>				
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
								<th class="text-center">Tool Code</th>
								<th class="text-center">Tool Name</th>
								<th class="text-center">Description</th>
								<th class="text-center">Qty Receive</th>
								<th class="text-center">Action</th>
							</tr>
						</thead>
						<tbody id="list_detail_cancel">
							<?php
							
							if($rows_detail){
								$intL	= 0;
								foreach($rows_detail as $ketD=>$valD){
									
									$Code_Detail	= $valD->id;
									$Code_Alat		= $valD->tool_id;
									$Cust_Alat		= $valD->tool_name;									
									$Qty_Ord		= $valD->qty_rec;
									$Qty_Pros		= $valD->qty_so;
									$Qty_Sisa		= $Qty_Ord - $Qty_Pros;
									$Description	= $valD->descr;
									if($Qty_Sisa > 0){
										$intL++;
										
										$ActionDel	= '<button type="button" onClick="DeleteRows(\''.$intL.'\')" class="btn btn-sm btn-danger" title="DELETE ROWS"> <i class="fa fa-trash-o"></i> </button>';
										
										echo'
										<tr id="tr_cancel_'.$intL.'">	
											<input type="hidden" name="detDelete['.$intL.'][code_detail]" id="code_detail_'.$intL.'" value = "'.$Code_Detail.'">
											<input type="hidden" name="detDelete['.$intL.'][tool_id]" id="tool_id_'.$intL.'" value = "'.$Code_Alat.'">
											<input type="hidden" name="detDelete['.$intL.'][tool_name]" id="tool_name_'.$intL.'" value = "'.$Code_Detail.'">
											<input type="hidden" name="detDelete['.$intL.'][quotation_header_receive_id]" id="code_header_'.$intL.'" value = "'.$rows_header->id.'">
											<input type="hidden" name="detDelete['.$intL.'][quotation_detail_id]" id="quot_detail_'.$intL.'" value = "'.$valD->quotation_detail_id.'">
											<input type="hidden" name="detDelete['.$intL.'][quotation_id]" id="quot_header_'.$intL.'" value = "'.$valD->quotation_id.'">
											<input type="hidden" name="detDelete['.$intL.'][qty_rec]" id="qty_rec_'.$intL.'" value = "'.$Qty_Ord.'">
											<input type="hidden" name="detDelete['.$intL.'][descr]" id="descr_'.$intL.'" value = "'.$valD->descr.'">
											<input type="hidden" name="detDelete['.$intL.'][rec_category]" id="rec_category_'.$intL.'" value = "'.$valD->rec_category.'">
											<input type="hidden" name="detDelete['.$intL.'][driver_id]" id="driver_id_'.$intL.'" value = "'.$valD->driver_id.'">
											<input type="hidden" name="detDelete['.$intL.'][driver_name]" id="driver_name_'.$intL.'" value = "'.$valD->driver_name.'">
											<input type="hidden" name="detDelete['.$intL.'][rec_date]" id="rec_date_'.$intL.'" value = "'.$valD->rec_date.'">
											<input type="hidden" name="detDelete['.$intL.'][receive_by]" id="receive_by_'.$intL.'" value = "'.$valD->receive_by.'">
											<input type="hidden" name="detDelete['.$intL.'][receive_proses]" id="receive_proses_'.$intL.'" value = "'.$valD->receive_proses.'">
											<input type="hidden" name="detDelete['.$intL.'][letter_order_id]" id="letter_order_id_'.$intL.'" value = "'.$valD->letter_order_id.'">
											<input type="hidden" name="detDelete['.$intL.'][qty_so]" id="qty_so_'.$intL.'" value = "'.$Qty_Pros.'">
											<input type="hidden" name="detDelete['.$intL.'][qty]" id="qty_'.$intL.'" value = "'.$Qty_Sisa.'">
											<input type="hidden" name="detDelete['.$intL.'][sentral_code_tool]" id="sentral_code_'.$intL.'" value = "'.$valD->sentral_code_tool.'">
											
											<td class="text-center">'.$Code_Alat.'</td>
											<td class="text-left">'.$Cust_Alat.'</td>
											<td class="text-left">'.$Description.'</td>
											<td class="text-center">'.$Qty_Sisa.'</td>
											<td class="text-center">'.$ActionDel.'</td>	
										</tr>
										';	
									}
								}
							}
							
							?>
						</tbody>
					</table>
				</div>
			</div>
		</div>	
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
		<div class="box-footer text-center">			
			<button type="button" id="btn_process_save_cancel" class="btn btn-md" style="background-color:#37474f; color:white;vertical-align:middle !important;" title="SAVE PROCESS"> PROCESS CANCELLATION <i class="fa fa-long-arrow-right" style="width:40px;"></i> </button>
		</div>
		<?php 
		}
		?>
	</div>
</form>

<style>
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
	.modal {
	  overflow-y:auto !important;
	}
</style>
<script>
	var site_url			= '<?php echo site_url(); ?>';
	var act_controller		= '<?php echo($this->uri->segment(1)); ?>';
	
	
	$(document).ready(function(){
		$('#loader_proses_save').hide();
	});
	
	function DeleteRows(Urut){
		$('#tr_cancel_'+Urut).remove();
	}
	
</script>