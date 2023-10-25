
<form action="#" method="POST" id="form-prewview-driver-pickup" enctype="multipart/form-data">
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
							<label class="control-label">Pickup No</label>
							<?php
								echo form_input(array('id'=>'pickup_nomor','name'=>'pickup_nomor','class'=>'form-control input-sm','readOnly'=>true),$rows_header->nomor);	
								echo form_input(array('id'=>'code_receive','name'=>'code_receive','type'=>'hidden'),$rows_header->id);
							?>
						</div>
					</div>
					<div class="col-sm-6">
						<div class="form-group">
							<label class="control-label">Pickup Date</label>
							<?php
								echo form_input(array('id'=>'pickup_date','name'=>'pickup_date','class'=>'form-control input-sm','readOnly'=>true),date('d-m-Y',strtotime($rows_header->datet)));						
							?>
						</div>
					</div>				
				</div>
				<div class='row'>
					<div class="col-sm-6">
						<div class="form-group">
							<label class="control-label">Customer</label>
							<?php
								echo form_input(array('id'=>'customer_name','name'=>'customer_name','class'=>'form-control input-sm','readOnly'=>true),$rows_header->customer_name);
														
							?>
						</div>
					</div>
					<div class="col-sm-6">
						<div class="form-group">
							<label class="control-label">Driver</label>
							<?php
								echo form_input(array('id'=>'driver_name','name'=>'driver_name','class'=>'form-control input-sm','readOnly'=>true),$rows_header->driver_name);					
							?>
						</div>
					</div>				
				</div>
				<div class='row'>
					<div class="col-sm-6">
						<div class="form-group">
							<label class="control-label">Quotation No</label>
							<?php
								echo form_input(array('id'=>'quotation_nomor','name'=>'quotation_nomor','class'=>'form-control input-sm','readOnly'=>true),$rows_quot->nomor);	
								
							?>
						</div>
					</div>
					<div class="col-sm-6">
						<div class="form-group">
							<label class="control-label">Quotation Date</label>
							<?php
								echo form_input(array('id'=>'quotation_date','name'=>'quotation_date','class'=>'form-control input-sm','readOnly'=>true),date('d-m-Y',strtotime($rows_quot->datet)));						
							?>
						</div>
					</div>				
				</div>
				<div class='row'>
					<div class="col-sm-6">
						<div class="form-group">
							<label class="control-label">PO No</label>
							<?php
								echo form_input(array('id'=>'pono','name'=>'pono','class'=>'form-control input-sm','readOnly'=>true),$rows_quot->pono);	
								
							?>
						</div>
					</div>
					<div class="col-sm-6">
						<div class="form-group">
							<label class="control-label">PO Date</label>
							<?php
								echo form_input(array('id'=>'podate','name'=>'podate','class'=>'form-control input-sm','readOnly'=>true),date('d-m-Y',strtotime($rows_quot->podate)));						
							?>
						</div>
					</div>				
				</div>
				
				<?php
				if($rows_header->flag_sign == 'Y' && file_exists($this->file_attachement.'quotation_receive_tool/'.$rows_header->id.'.pdf')){
					echo'
						<div class="row">
							<div class="col-sm-6">
								<div class="form-group">
									<label class="control-label">BAST File</label>
									<div>
										<a href="'.$this->file_attachement.'quotation_receive_tool/'.$rows_header->id.'.pdf" class="btn btn-sm bg-orange-active" target ="_blank"> PREVIEW FILE </a>
									</div>
								</div>
							</div>
							<div class="col-sm-6">&nbsp;</div>				
						</div>
					';
				}
				if($category === 'close'){
				echo'
					<div class="row">
						<div class="col-sm-12 col-xs-12 text-center sub-heading" style="color:white;">
							<h5>DETAIL CLOSE</h5>
						</div>				
					</div>
					<div class="row">
						<div class="col-sm-6">
							<div class="form-group">
								<label class="control-label">Close Reason <span class="text-red"> *</span></label>							
								'.form_textarea(array('id'=>'close_reason', 'name'=>'close_reason','cols'=>'75','rows'=>'2', 'class'=>'form-control input-sm text-up')).'									
														
							</div>
						</div>
						<div class="col-sm-6">&nbsp;</div>
					</div>
					';
				}
				?>
				<div class="row">
					<div class="col-sm-12 col-xs-12 text-center sub-heading" style="color:white;">
						<h5>DETAIL TOOL </h5>
					</div>
					
				</div>
				<div class="row">
					<div class="col-sm-12" style="overflow-x:scroll !important;">
						<table class="table table-striped table-bordered" id="my-grid">
							<thead>
								<tr class="bg-navy-active">
									<th class="text-center">No</th>
									<th class="text-center">Tool Code</th>
									<th class="text-center">Tool Name</th>
									<th class="text-center">Range</th>				
									<th class="text-center">Description</th>
									<th class="text-center">Quotation<br>Notes</th>
									
								</tr>
							</thead>
							<tbody id="list_detail">
								<?php
								if($rows_detail){
									$intL	= 0;
									foreach($rows_detail as $ketD=>$valD){
										
										$intL++;
										$Code_Detail	= $valD->id;
										$Code_Alat		= $valD->tool_id;
										$Cust_Alat		= $valD->tool_name;
										$Range_Alat		= '-';
										$Keterangan		= $valD->descr;
										$Notes_Quot		= '-';
										$rows_QuotDet	= $this->db->get_where('quotation_details',array('id'=>$valD->quotation_detail_id))->row();
										if($rows_QuotDet){
											$Range_Alat		= $rows_QuotDet->range.' '.$rows_QuotDet->piece_id;
											$Notes_Quot		= $rows_QuotDet->descr;
										}
										
												
										echo'
										<tr>
											<td class="text-center">'.$intL.'</td>
											<td class="text-center">'.$Code_Alat.'</td>
											<td class="text-left">'.$Cust_Alat.'</td>
											<td class="text-center">'.$Range_Alat.'</td>
											<td class="text-left">'.$Keterangan.'</td>
											<td class="text-left text-wrap">'.$Notes_Quot.'</td>
										</tr>
										';
											
										
										
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
				<button type="button" id="btn_close_receive" class="btn btn-md text-center" style="background-color:#37474f; color:white;vertical-align:middle !important;" title="CLOSE PROCESS"> CLOSE PROCESS <i class="fa fa-long-arrow-right" style="width:40px;"></i> </button>
			</div>';
		}
		?>
		
	</div>
</form>
<div class="modal fade" id="MyModalView" tabindex="-1" role="dialog" aria-labelledby="MyModal" data-backdrop="static">
	<div class="modal-dialog" role="document" style="min-width:70% !important;">
		 <div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="MyModalTitle"></h5>
				<button class="close" data-dismiss="modal" aria-label="close" id="btn-modal-close">
					<span aria-hidden="true"><i class="fa fa-close"></i></span>
				</button>
			</div>
			<div class="modal-body" id="MyModalDetail">
			
			</div>
		</div>
	</div>
</div>

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
	.ui-spinner-input{
		padding :10px 5px 10px 10px !important;
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
