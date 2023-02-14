
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
			$Status_Order	= $rows_header->status;
			
			$Lable_Status	= 'OPEN';
			$Color_Status	= 'bg-green';
			if($Status_Order === 'CNC'){
				$Lable_Status	= 'CANCELED';
				$Color_Status	= 'bg-orange';
			}else if($Status_Order === 'REV'){
				$Lable_Status	= 'REVISION';
				$Color_Status	= 'bg-navy-active';
			}else if($Status_Order === 'APV'){
				$Lable_Status	= 'APPROVE BY CUSTOMER';
				$Color_Status	= 'bg-maroon-active';
			}
			$Ket_Status		= '<span class="badge '.$Color_Status.'">'.$Lable_Status.'</span>';
			
			
		?>
		<div class="box-body">			
			<div class="row">
				<div class="col-sm-12 col-xs-12 text-center sub-heading" style="color:white;">
					<h5>DETAIL SCHEDULE</h5>
				</div>				
			</div>
			<div class='row'>
				<div class="col-sm-6">
					<div class="form-group">
						<label class="control-label">Schedule No</label>
						<?php
							echo form_input(array('id'=>'nomor_schedule','name'=>'nomor_schedule','class'=>'form-control input-sm','readOnly'=>true),$rows_header->nomor);
							echo form_input(array('id'=>'code_order','name'=>'code_order','type'=>'hidden'),$rows_header->id);
							echo form_input(array('id'=>'sales_order','name'=>'sales_order','type'=>'hidden'),$rows_header->letter_order_id);
							echo form_input(array('id'=>'quot_order','name'=>'quot_order','type'=>'hidden'),$rows_header->quotation_id);
							echo form_input(array('id'=>'category','name'=>'category','type'=>'hidden'),$category);
						?>
					</div>
				</div>
				<div class="col-sm-6">
					<div class="form-group">
						<label class="control-label">Schedule Date</label>
						<?php
							echo form_input(array('id'=>'sched_date','name'=>'sched_date','class'=>'form-control input-sm','readOnly'=>true),date('d-m-Y',strtotime($rows_header->datet)));						
						?>
					</div>
				</div>				
			</div>
			
			<div class='row'>
				<div class="col-sm-6">
					<div class="form-group">
						<label class="control-label">SO No</label>
						<?php
							echo form_input(array('id'=>'nomor_so','name'=>'nomor_so','class'=>'form-control input-sm','readOnly'=>true),$rows_letter->no_so);
							
						?>
					</div>
				</div>
				<div class="col-sm-6">
					<div class="form-group">
						<label class="control-label">SO Date</label>
						<?php
							echo form_input(array('id'=>'so_date','name'=>'so_date','class'=>'form-control input-sm','readOnly'=>true),date('d-m-Y',strtotime($rows_letter->tgl_so)));						
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
						<label class="control-label">Customer</label>
						<?php
							echo form_input(array('id'=>'customer_name','name'=>'customer_name','class'=>'form-control input-sm','readOnly'=>true),strtoupper($rows_quot->customer_name));
										
						?>
					</div>
				</div>	
				<div class="col-sm-6">
					<div class="form-group">
						<label class="control-label">PIC Name</label>
						<?php
							echo form_input(array('id'=>'pic_name','name'=>'pic_name','class'=>'form-control input-sm','readOnly'=>true),strtoupper($rows_letter->pic));
													
						?>
					</div>
				</div>
			</div>
			<div class='row'>
				<div class="col-sm-6">
					<div class="form-group">
						<label class="control-label">Address</label>
						<?php
							echo form_textarea(array('id'=>'address','name'=>'address','class'=>'form-control input-sm','readOnly'=>true,'cols'=>100,'rows'=>2),strtoupper($rows_cust->address));
													
						?>
					</div>
				</div>
				<div class="col-sm-6">
					<div class="form-group">
						<label class="control-label">Description</label>
						<?php
							echo form_textarea(array('id'=>'notes','name'=>'notes','class'=>'form-control input-sm','readOnly'=>true,'cols'=>100,'rows'=>2),strtoupper($rows_header->notes));
													
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
		   }else if($category === 'email'){
				echo'
				<div class="row">
					<div class="col-sm-12 col-xs-12 text-center sub-heading" style="color:white;">
						<h5>DETAIL EMAIL</h5>
					</div>				
				</div>
				<div class="row">
					<div class="col-sm-2">
						<div class="form-group">
							<label class="control-label">PIC Name <span class="text-red"> *</span></label>							
							<select name="inisial" id="inisial" class="form-control chosen-select">
								<option value="Bapak">Bapak</option>
								<option value="Ibu">Ibu</option>
							</select>							
						</div>
					</div>
					<div class="col-sm-4">
						<div class="form-group">
							<label class="control-label">&nbsp;</label>							
							'.form_input(array('id'=>'email_name','name'=>'email_name','class'=>'form-control input-sm text-up','autocomplete'=>'off'),strtoupper($rows_letter->pic)).'									
													
						</div>
					</div>
					<div class="col-sm-6">
						<div class="form-group">
							<label class="control-label">Email <span class="text-red"> *</span></label>
							'.form_input(array('id'=>'email_to','name'=>'email_to','class'=>'form-control input-sm','autocomplete'=>'off'),$rows_cust->email).'
						</div>
					</div>
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
								<th class="text-center" rowspan="2">Subcon</th>
								<th class="text-center" rowspan="2">Labs</th>
								<th class="text-center" colspan="3">Labs/Insitu Process</th>
								<th class="text-center" colspan="3">Subcon Process</th>
							</tr>
							<tr class="bg-navy-active" style="border-right-width: 1 !important;">	
								<th class="text-center">Pickup</th>
								<th class="text-center">Process</th>
								<th class="text-center">Send</th>
								<th class="text-center">Pickup</th>
								<th class="text-center">Process</th>
								<th class="text-center">Send</th>
							</tr>
						</thead>
						<tbody id="list_detail">
							<?php
							
							if($rows_detail){
								$intL	= 0;
								foreach($rows_detail as $ketD=>$valD){
									$intL++;
									
									$subcon			= $valD['subcon'];
									$insitu			= $valD['insitu'];
									$labs			= $valD['labs'];
									$tgl_proses		= date('d M Y',strtotime($valD['process_date']));
									$alat			= $valD['tool_id'];
									$tgl_ambil		= '-';
									$tgl_balik		= '-';
									$kode_subcon	= $subcon;
									$Supplier_Cust	= '-';
									$rows_QuotDet	= $this->db->get_where('quotation_details',array('id'=>$valD['quotation_detail_id']))->row();
									if($rows_QuotDet){
										$Supplier_Cust	= $rows_QuotDet->supplier_id;
									}
									if($insitu=='N'){
										$tgl_ambil	=date('d M Y',strtotime($valD['pick_date']));
										$tgl_balik	=date('d M Y',strtotime($valD['delivery_date']));
									}else{
										if($Supplier_Cust !='COMP-001'){
											$kode_subcon	='Y';
										}
									}
									if(isset($rows_aloc[$alat]) && $rows_aloc[$alat]){
										$tgl_proses	.=' '.$rows_aloc[$alat]['start_time'].' - '.$rows_aloc[$alat]['end_time'].' ('.$rows_aloc[$alat]['member_name'].')';
										
									}
									$tgl_kirim_Subcon	= (isset($valD['subcon_send_date']) && $valD['subcon_send_date'])?date('d M Y',strtotime($valD['subcon_send_date'])):'-';
									$tgl_ambil_Subcon	= (isset($valD['subcon_pick_date']) && $valD['subcon_pick_date'])?date('d M Y',strtotime($valD['subcon_pick_date'])):'-';
										
									$Tgl_Pros_Subcon 	= '-';	
									if($subcon == 'Y'){
										$Tgl_Pros_Subcon = $tgl_proses;
									}
									echo'
									<tr id="rows_tr_'.$intL.'">											
										<td class="text-left text-wrap">'.$valD['tool_name'].'</td>
										<td class="text-center">'.$valD['qty'].'</td>										
										<td class="text-center">'.$insitu.'</td>
										<td class="text-center">'.$kode_subcon.'</td>
										<td class="text-center">'.$labs.'</td>
										<td class="text-center">'.$tgl_ambil.'</td>
										<td class="text-center">'.$tgl_proses.'</td>
										<td class="text-center">'.$tgl_balik.'</td>	
										<td class="text-center">'.$tgl_kirim_Subcon.'</td>
										<td class="text-center">'.$Tgl_Pros_Subcon.'</td>
										<td class="text-center">'.$tgl_ambil_Subcon.'</td>								
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
				if(strtolower($category) === 'cancel'){
					$Lable_Button	= 'SAVE CANCELLATION PROCESS';
					$Code_Button	= 'btn_cancel_schedule';
				}else if(strtolower($category) === 'approve'){
					$Lable_Button	= 'SAVE APPROVAL PROCESS';
					$Code_Button	= 'btn_approve_schedule';
				}else if(strtolower($category) === 'email'){
					$Lable_Button	= 'SEND EMAIL PROCESS';
					$Code_Button	= 'btn_email_schedule';
				}
				
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
					<button type="button" id="'.$Code_Button.'" class="btn btn-md text-center" style="background-color:#37474f; color:white;vertical-align:middle !important;" title="'.$Lable_Button.'"> '.$Lable_Button.' <i class="fa fa-long-arrow-right" style="width:40px;"></i> </button>
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