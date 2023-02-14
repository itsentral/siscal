
<form action="#" method="POST" id="form_proses_driver_order" enctype="multipart/form-data">
	<div class="box box-warning">
		<?php
		if(empty($rows_detail)){
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
			$Type_Cust		= $rows_header->type_comp;
			$Type_Process	= $rows_header->category;
			$Status_Order	= $rows_header->sts_order;
			
			$Lable_Status	= 'OPEN';
			$Color_Status	= 'bg-green';
			if($Status_Order === 'CNC'){
				$Lable_Status	= 'CANCELED';
				$Color_Status	= 'bg-orange';
			}else if($Status_Order === 'PRO'){
				$Lable_Status	= 'ON PROCESS';
				$Color_Status	= 'bg-blue';
			}else if($Status_Order === 'CLS'){
				$Lable_Status	= 'CLOSE';
				$Color_Status	= 'bg-navy-active';
			}
			$Ket_Status		= '<span class="badge '.$Color_Status.'">'.$Lable_Status.'</span>';
			
			if($Type_Process === 'REC'){
				$Ket_Category	= '<span class="badge" style="background-color:#16697A !important;color:#ffffff !important;">AMBIL ALAT</span>';
			}else if($Type_Process === 'DEL'){
				$Ket_Category	= '<span class="badge" style="background-color:#DB6400 !important;color:#ffffff !important;">KIRIM ALAT</span>';
			}else if($Type_Process === 'INS'){
				$Ket_Category	= '<span class="badge" style="background-color:#37474f !important;color:#ffffff !important;">ANTAR TEKNISI</span>';
			}
			
			if($Type_Cust === 'CUST'){
				$Ket_Comp	= '<span class="badge" style="background-color:#c2185b !important;color:#ffffff !important;">CUSTOMER</span>';
			}else{
				$Ket_Comp	= '<span class="badge" style="background-color:#0277bd !important;color:#ffffff !important;">SUBCON</span>';
			}
		?>
		<div class="box-body">			
		
			<div class="row">
				<div class="col-sm-12 col-xs-12 text-center sub-heading" style="color:white;">
					<h5>DETAIL DRIVER ORDER</h5>
				</div>				
			</div>
			<div class='row'>
				<div class="col-sm-6">
					<div class="form-group">
						<label class="control-label">Order No</label>
						<?php
							echo form_input(array('id'=>'order_no','name'=>'order_no','class'=>'form-control input-sm','readOnly'=>true),$rows_header->order_no);
							echo form_input(array('id'=>'code_order','name'=>'code_order','type'=>'hidden'),$rows_header->order_code);
							echo form_input(array('id'=>'category','name'=>'category','type'=>'hidden'),$category);
						?>
					</div>
				</div>
				<div class="col-sm-6">
					<div class="form-group">
						<label class="control-label">Plan Date</label>
						<?php
							echo form_input(array('id'=>'plan_date','name'=>'plan_date','class'=>'form-control input-sm','readOnly'=>true),date('d-m-Y',strtotime($rows_header->plan_date)));						
						?>
					</div>
				</div>				
			</div>
			<div class='row'>
				<div class="col-sm-6">
					<div class="form-group">
						<label class="control-label">Company</label>
						<?php
							echo form_input(array('id'=>'company','name'=>'company','class'=>'form-control input-sm','readOnly'=>true),$rows_header->company);
													
						?>
					</div>
				</div>
				<div class="col-sm-6">
					<div class="form-group">
						<label class="control-label">Address</label>
						<?php
							echo form_textarea(array('id'=>'address', 'name'=>'address','cols'=>'75','rows'=>'2', 'class'=>'form-control input-sm','readOnly'=>true),$rows_header->address);				
						?>
					</div>
				</div>				
			</div>
			<div class='row'>
				<div class="col-sm-6">
					<div class="form-group">
						<label class="control-label">PIC Name</label>
						<?php
							echo form_input(array('id'=>'pic_name','name'=>'pic_name','class'=>'form-control input-sm','readOnly'=>true),$rows_header->pic_name);
													
						?>
					</div>
				</div>
				<div class="col-sm-6">
					<div class="form-group">
						<label class="control-label">PIC Phone</label>
						<?php
							echo form_input(array('id'=>'pic_phone','name'=>'pic_phone','class'=>'form-control input-sm','readOnly'=>true),$rows_header->pic_phone);				
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
						<label class="control-label">Type Process</label>
						<div>
						<?php
							echo $Ket_Category;
													
						?>
						</div>
					</div>
				</div>				
			</div>
			<div class='row'>
				<div class="col-sm-6">
					<div class="form-group">
						<label class="control-label">Status_Order</label>
						<div>
						<?php
							echo $Ket_Status;
													
						?>
						</div>
					</div>
				</div>
				<div class="col-sm-6">
					<div class="form-group">
						<label class="control-label">Notes</label>
						<?php
							echo form_textarea(array('id'=>'notes', 'name'=>'notes','cols'=>'75','rows'=>'2', 'class'=>'form-control input-sm','readOnly'=>true),$rows_header->notes);
													
						?>						
					</div>
				</div>				
			</div>
			<?php
			if($Status_Order === 'CNC'){
				echo'
				<div class="row">
					<div class="col-sm-6">
						<div class="form-group">
							<label class="control-label">Cancel Reason</label>							
							'.form_textarea(array('id'=>'alasan', 'name'=>'alasan','cols'=>'75','rows'=>'2', 'class'=>'form-control input-sm','readOnly'=>true),$rows_header->cancel_reason).'									
													
						</div>
					</div>
					<div class="col-sm-6">&nbsp;</div>
				</div>
				';
			}else if($Status_Order === 'PRO' || $Status_Order === 'CLS'){
				$rows_SPK	= $this->db->get_where('spk_drivers',array('id'=>$rows_header->spk_driver_code))->row();
				$Nomor_SPK	='-';
				if($rows_SPK){
					$Nomor_SPK	= $rows_SPK->nomor;
				}
				
				echo'
				<div class="row">
					<div class="col-sm-6">
						<div class="form-group">
							<label class="control-label">Driver</label>							
							'.form_input(array('id'=>'driver_name','name'=>'driver_name','class'=>'form-control input-sm','readOnly'=>true),$rows_header->driver_name).'									
													
						</div>
					</div>
					<div class="col-sm-6">
						<div class="form-group">
							<label class="control-label">SPK Driver</label>							
							'.form_input(array('id'=>'spk_driver_code','name'=>'spk_driver_code','class'=>'form-control input-sm','readOnly'=>true),$Nomor_SPK).'									
													
						</div>
					</div>
				</div>
				';
			}
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
								<th class="text-center">Range</th>
								<th class="text-center">Qty</th>
								<th class="text-center">Qty Process</th>
								<th class="text-center">No SO</th>
							</tr>
						</thead>
						<tbody id="list_detail">
							<?php
							$Ok_SO		= 1;
							$Add_Field	= ',no_so';
							
							if($Type_Cust == 'CUST' && $Type_Process === 'REC'){
								$Table_Detail	= "quotation_details";
								$Ok_SO			= 0;
								$Add_Field		= '';
							}else{
								$Table_Detail	= "trans_details";
							}
							if($rows_detail){
								$intL	= 0;
								foreach($rows_detail as $ketD=>$valD){
									
									$Code_Detail	= $valD->code_process;
									$Code_Alat		= $valD->tool_id;
									$Cust_Alat		= $valD->tool_name;									
									$Qty_Ord		= $valD->qty;
									$Qty_Pros		= $valD->qty_pros;
									$Range_Alat		= '-';
									$No_SO			= '-';
									$Query_Tool		= "SELECT `range`, piece_id ".$Add_Field." FROM ".$Table_Detail." WHERE id = '".$Code_Detail."'";
									$rows_Tool		= $this->db->query($Query_Tool)->row();
									if($rows_Tool){
										$Range_Alat	= $rows_Tool->range.' '.$rows_Tool->piece_id;
										if($Ok_SO === 1){
											$No_SO	= $rows_Tool->no_so;
										}
									}
									echo'
									<tr>											
										<td class="text-center">'.$Code_Alat.'</td>
										<td class="text-left">'.$Cust_Alat.'</td>
										<td class="text-center">'.$Range_Alat.'</td>
										<td class="text-center">'.$Qty_Ord.'</td>
										<td class="text-center">'.$Qty_Pros.'</td>	
										<td class="text-center">'.$No_SO.'</td>
									</tr>
									';									
								}
							}
							?>
						</tbody>
					</table>
				</div>
			</div>
		</div>	
		<?php
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
	
</style>
<script>
	$(document).ready(function(){
		$('#loader_proses_save').hide();
	});
</script>