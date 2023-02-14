
<form action="#" method="POST" id="form_proses_driver_spk" enctype="multipart/form-data">
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
			$Status_Order	= $rows_header->sts_so;
			
			$Lable_Status	= 'OPEN';
			$Color_Status	= 'bg-green';
			if($Status_Order === 'CNC'){
				$Lable_Status	= 'CANCELED';
				$Color_Status	= 'bg-orange';
			}else if($Status_Order === 'REV'){
				$Lable_Status	= 'REVISION';
				$Color_Status	= 'bg-navy-active';
			}else if($Status_Order === 'SCH'){
				$Lable_Status	= 'CLOSE';
				$Color_Status	= 'bg-maroon-active';
			}
			$Ket_Status		= '<span class="badge '.$Color_Status.'">'.$Lable_Status.'</span>';
			
			
		?>
		<div class="box-body">			
		
			<div class="row">
				<div class="col-sm-12 col-xs-12 text-center sub-heading" style="color:white;">
					<h5>DETAIL SALES ORDER</h5>
				</div>				
			</div>
			<div class='row'>
				<div class="col-sm-6">
					<div class="form-group">
						<label class="control-label">SO No</label>
						<?php
							echo form_input(array('id'=>'nomor_so','name'=>'nomor_so','class'=>'form-control input-sm','readOnly'=>true),$rows_header->no_so);
							echo form_input(array('id'=>'code_order','name'=>'code_order','type'=>'hidden'),$rows_header->id);
							echo form_input(array('id'=>'category','name'=>'category','type'=>'hidden'),$category);
						?>
					</div>
				</div>
				<div class="col-sm-6">
					<div class="form-group">
						<label class="control-label">SO Date</label>
						<?php
							echo form_input(array('id'=>'so_date','name'=>'so_date','class'=>'form-control input-sm','readOnly'=>true),date('d-m-Y',strtotime($rows_header->tgl_so)));						
						?>
					</div>
				</div>				
			</div>
			<div class='row'>
				<div class="col-sm-6">
					<div class="form-group">
						<label class="control-label">Quotation No</label>
						<?php
							echo form_input(array('id'=>'nomor_quot','name'=>'nomor_quot','class'=>'form-control input-sm','readOnly'=>true),$rows_quot->nomor);
							
						?>
					</div>
				</div>
				<div class="col-sm-6">
					<div class="form-group">
						<label class="control-label">Quotation Date</label>
						<?php
							echo form_input(array('id'=>'quot_date','name'=>'quot_date','class'=>'form-control input-sm','readOnly'=>true),date('d-m-Y',strtotime($rows_quot->datet)));						
						?>
					</div>
				</div>				
			</div>
			<div class='row'>
				<div class="col-sm-6">
					<div class="form-group">
						<label class="control-label">PO No</label>
						<?php
							echo form_input(array('id'=>'pono','name'=>'pono','class'=>'form-control input-sm','readOnly'=>true),strtoupper($rows_quot->pono));
													
						?>
					</div>
				</div>
				<div class="col-sm-6">
					<div class="form-group">
						<label class="control-label">Customer</label>
						<?php
							echo form_input(array('id'=>'customer_name','name'=>'customer_name','class'=>'form-control input-sm','readOnly'=>true),strtoupper($rows_quot->customer_name));
										
						?>
					</div>
				</div>				
			</div>
			<div class='row'>
				<div class="col-sm-6">
					<div class="form-group">
						<label class="control-label">PIC Name</label>
						<?php
							echo form_input(array('id'=>'pic_name','name'=>'pic_name','class'=>'form-control input-sm','readOnly'=>true),strtoupper($rows_header->pic));
													
						?>
					</div>
				</div>
				<div class="col-sm-6">
					<div class="form-group">
						<label class="control-label">PIC Phone</label>
						<?php
							echo form_input(array('id'=>'pic_phone','name'=>'pic_phone','class'=>'form-control input-sm','readOnly'=>true),$rows_header->phone);
										
						?>
					</div>
				</div>				
			</div>
			<div class="row">
				<div class="col-sm-12 col-xs-12 text-center sub-heading" style="color:white;">
					<h5>DETAIL ADDRESS</h5>
				</div>				
			</div>
			<div class='row'>
				<div class="col-sm-6">
					<div class="form-group">
						<label class="control-label">Delivery Address</label>
						<?php
							echo form_textarea(array('id'=>'send_address','name'=>'send_address','class'=>'form-control input-sm','readOnly'=>true,'cols'=>100,'rows'=>2),$rows_header->address_send);
													
						?>
					</div>
				</div>
				<div class="col-sm-6">
					<div class="form-group">
						<label class="control-label">Invoice Address</label>
						<?php
							echo form_textarea(array('id'=>'inv_address','name'=>'inv_address','class'=>'form-control input-sm','readOnly'=>true,'cols'=>100,'rows'=>2),$rows_header->address_inv);
										
						?>
					</div>
				</div>				
			</div>
			<div class='row'>
				<div class="col-sm-6">
					<div class="form-group">
						<label class="control-label">Certificate Address</label>
						<?php
							echo form_textarea(array('id'=>'cert_address','name'=>'cert_address','class'=>'form-control input-sm','readOnly'=>true,'cols'=>100,'rows'=>2),$rows_header->address_sertifikat);
													
						?>
					</div>
				</div>
				<div class="col-sm-6">
					<div class="form-group">
						<label class="control-label">Calibration Address</label>
						<?php
							echo form_textarea(array('id'=>'cal_address','name'=>'cal_address','class'=>'form-control input-sm','readOnly'=>true,'cols'=>100,'rows'=>2),$rows_header->address);
										
						?>
					</div>
				</div>				
			</div>
			<div class="row">
				<div class="col-sm-12 col-xs-12 text-center sub-heading" style="color:white;">
					<h5>DETAIL NOTES</h5>
				</div>				
			</div>
			<div class='row'>
				<div class="col-sm-6">
					<div class="form-group">
						<label class="control-label">Delivery Notes</label>
						<?php
							echo form_textarea(array('id'=>'send_notes','name'=>'send_notes','class'=>'form-control input-sm','readOnly'=>true,'cols'=>100,'rows'=>2),strtoupper($rows_header->notes_delivery));
													
						?>
					</div>
				</div>
				<div class="col-sm-6">
					<div class="form-group">
						<label class="control-label">Invoice Notes</label>
						<?php
							echo form_textarea(array('id'=>'inv_notes','name'=>'inv_notes','class'=>'form-control input-sm','readOnly'=>true,'cols'=>100,'rows'=>2),strtoupper($rows_header->notes_invoice));
										
						?>
					</div>
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
			
			<div class="row">
				<div class="col-sm-12 col-xs-12 text-center sub-heading" style="color:white;">
					<h5>DETAIL TOOL</h5>
				</div>				
			</div>
			
			<div class="row">
				<div class="col-sm-12" style="overflow-x:scroll !important;">
					<table class="table table-striped table-bordered">
						<thead>
							<tr class="bg-navy-active">								
								<th class="text-center" rowspan="2">Tool Name</th>
								<th class="text-center" rowspan="2">Qty</th>
								<th class="text-center" rowspan="2">Insitu</th>
								<th class="text-center" colspan="2">Cust Tool</th>
								<th class="text-center" rowspan="2">Subcon</th>
								<th class="text-center" rowspan="2">Labs</th>
								<th class="text-center" rowspan="2">Send By</th>
								<th class="text-center" rowspan="2">Insitu<br>Area</th>
								<th class="text-center" rowspan="2">Description</th>
								<th class="text-center" rowspan="2">Req Cust</th>
							</tr>
							<tr class="bg-navy-active" style="border-right-width: 1 !important;">	
								<th class="text-center">Pickup</th>
								<th class="text-center">Send</th>
							</tr>
						</thead>
						<tbody id="list_detail">
							<?php
							
							if($rows_detail){
								$intL	= 0;
								foreach($rows_detail as $ketD=>$valD){
									$intL++;
									
									$Ambil	='Y';
									$subcon	='-';
									$labs	='-';
									$insitu	='-';
									if($valD->tipe == 'Insitu'){
										$insitu	='Y';
										if($valD->supplier_id != 'COMP-001'){
											$subcon	='Y';
										}
										$Ambil	='-';
									}else if($valD->tipe == 'Labs'){
										$labs	='Y';
									}else if($valD->tipe == 'Subcon'){
										$subcon	='Y';
									}
									
									$Notes_Cust		= '-';
									$rows_QuotDet	= $this->db->get_where('quotation_details',array('id'=>$valD->quotation_detail_id))->row();
									if($rows_QuotDet){
										$Notes_Cust	= $rows_QuotDet->descr;
									}
									echo'
									<tr>											
										<td class="text-left text-wrap">'.$valD->tool_name.'</td>
										<td class="text-center">'.$valD->qty.'</td>										
										<td class="text-center">'.$insitu.'</td>
										<td class="text-center">'.$Ambil.'</td>
										<td class="text-center">'.$Ambil.'</td>
										<td class="text-center">'.$subcon.'</td>
										<td class="text-center">'.$labs.'</td>
										<td class="text-center">'.$valD->get_tool.'</td>	
										<td class="text-center"><span class="badge bg-green">'.$valD->delivery_name.'</span></td>
										<td class="text-left text-wrap">'.$valD->descr.'</td>
										<td class="text-left text-wrap">'.$Notes_Cust.'</td>								
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
				<div class="box-footer text-center">			
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