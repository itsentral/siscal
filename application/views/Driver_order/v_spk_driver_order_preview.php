
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
			}else if($Status_Order === 'CLS'){
				$Lable_Status	= 'CLOSE';
				$Color_Status	= 'bg-navy-active';
			}
			$Ket_Status		= '<span class="badge '.$Color_Status.'">'.$Lable_Status.'</span>';
			
			
		?>
		<div class="box-body">			
		
			<div class="row">
				<div class="col-sm-12 col-xs-12 text-center sub-heading" style="color:white;">
					<h5>DETAIL SPK DRIVER ORDER</h5>
				</div>				
			</div>
			<div class='row'>
				<div class="col-sm-6">
					<div class="form-group">
						<label class="control-label">SPK No</label>
						<?php
							echo form_input(array('id'=>'nomor_spk','name'=>'nomor_spk','class'=>'form-control input-sm','readOnly'=>true),$rows_header->nomor);
							echo form_input(array('id'=>'code_order','name'=>'code_order','type'=>'hidden'),$rows_header->id);
							echo form_input(array('id'=>'category','name'=>'category','type'=>'hidden'),$category);
						?>
					</div>
				</div>
				<div class="col-sm-6">
					<div class="form-group">
						<label class="control-label">SPK Date</label>
						<?php
							echo form_input(array('id'=>'spk_date','name'=>'spk_date','class'=>'form-control input-sm','readOnly'=>true),date('d-m-Y',strtotime($rows_header->datet)));						
						?>
					</div>
				</div>				
			</div>
			<div class='row'>
				<div class="col-sm-6">
					<div class="form-group">
						<label class="control-label">Driver</label>
						<?php
							echo form_input(array('id'=>'nama_driver','name'=>'nama_driver','class'=>'form-control input-sm','readOnly'=>true),strtoupper($rows_header->member_name));
													
						?>
					</div>
				</div>
				<div class="col-sm-6">
					<div class="form-group">
						<label class="control-label">Notes</label>
						<?php
							echo form_textarea(array('id'=>'notes', 'name'=>'notes','cols'=>'75','rows'=>'2', 'class'=>'form-control input-sm','readOnly'=>true),$rows_header->descr);				
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
					<h5>DETAIL COMPANY</h5>
				</div>				
			</div>
			
			<div class="row">
				<div class="col-sm-12" style="overflow-x:scroll !important;">
					<table class="table table-striped table-bordered" id="my-grid">
						<thead>
							<tr class="bg-navy-active">								
								<th class="text-center">No</th>
								<th class="text-center">Company</th>
								<th class="text-center">Address</th>
								<th class="text-center">Description</th>
							</tr>
						</thead>
						<tbody id="list_detail">
							<?php
							
							if($rows_detail){
								$intL	= 0;
								foreach($rows_detail as $ketD=>$valD){
									$intL++;
									$Company_Name	= strtoupper($valD->name);
									$Address		= strtoupper($valD->address);
									$Keterangan		= strtoupper($valD->keterangan);
									echo'
									<tr>											
										<td class="text-center">'.$intL.'</td>
										<td class="text-left">'.$Company_Name.'</td>
										<td class="text-left text-wrap">'.$Address.'</td>
										<td class="text-left text-wrap">'.$Keterangan.'</td>								
									</tr>
									';									
								}
							}
							?>
						</tbody>
					</table>
				</div>
			</div>
			
			<div class="row">
				<div class="col-sm-12 col-xs-12 text-center sub-heading" style="color:white;">
					<h5>DETAIL Tool</h5>
				</div>				
			</div>
			
			<div class="row">
				<div class="col-sm-12" style="overflow-x:scroll !important;">
					<table class="table table-striped table-bordered" id="my-grid">
						<thead>
							<tr class="bg-navy-active">								
								<th class="text-center">No</th>
								<th class="text-center">Tool Name</th>
								<th class="text-center">Qty</th>
								<th class="text-center">Type</th>
								<th class="text-center">Company</th>
								<th class="text-center">Description</th>
							</tr>
						</thead>
						<tbody id="list_detail">
							<?php
							
							if($rows_tool){
								$intL	= 0;
								foreach($rows_tool as $ketD=>$valD){
									$intL++;
									
									if($valD->category == 'CUST'){
										$tipe		= 'Labs';
										$Keterangan	= 'Antar Alat Ke Cust';
										if($valD->type =='INS'){
											$tipe		= 'Insitu';
											$Keterangan	= 'Antar '.$valD->teknisi;
										}else if($valD->type == 'REC'){
											$Keterangan	= 'Ambil Alat Ke Customer';
										}
									}else{
										$tipe		= 'Subcon';
										$Keterangan	= 'Antar Alat Ke Subcon';
										if($valD->type == 'REC'){
											$Keterangan	= 'Ambil Alat Dari Subcon';
										}
									}
									$Company_Name	= strtoupper($valD->name);
									
									echo'
									<tr>											
										<td class="text-center">'.$intL.'</td>
										<td class="text-left">'.$valD->tool_name.'</td>
										<td class="text-center">'.$valD->qty.'</td>
										<td class="text-center"><span class="badge bg-green">'.$tipe.'</span></td>
										<td class="text-left text-wrap">'.$Company_Name.'</td>
										<td class="text-left text-wrap">'.$Keterangan.'</td>								
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