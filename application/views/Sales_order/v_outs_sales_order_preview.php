
<form action="#" method="POST" id="form_proses_driver_order" enctype="multipart/form-data">
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
								<th class="text-center">Qty SO</th>
								<th class="text-center">Action</th>
							</tr>
						</thead>
						<tbody id="list_detail">
							<?php
							
							if($rows_detail){
								$intL	= 0;
								foreach($rows_detail as $ketD=>$valD){
									
									$Code_Detail	= $valD->id;
									$Code_Alat		= $valD->tool_id;
									$Cust_Alat		= $valD->tool_name;									
									$Qty_Ord		= $valD->qty_rec;
									$Qty_Pros		= $valD->qty_so;
									$Description	= $valD->descr;
									$Preview		= '-';
									$rows_File		= $this->db->get_where('quotation_driver_detail_receive_file',array('driver_detail_receive'=>$Code_Detail))->result();
									if($rows_File){
										$Preview	= '<button type="button" onClick="ViewReceiveTool(\''.$Code_Detail.'\')" class="btn btn-sm btn-danger" title="VIEW RECEIVE FILE"> <i class="fa fa-search"></i> </button>';
									}
									echo'
									<tr>											
										<td class="text-center">'.$Code_Alat.'</td>
										<td class="text-left">'.$Cust_Alat.'</td>
										<td class="text-left">'.$Description.'</td>
										<td class="text-center">'.$Qty_Ord.'</td>
										<td class="text-center">'.$Qty_Pros.'</td>
										<td class="text-center">'.$Preview.'</td>	
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
		}
		?>
	</div>
</form>
<div class="modal fade" id="MyModalPreview" tabindex="-1" role="dialog" aria-labelledby="MyModalPreview" data-backdrop="static">
	<div class="modal-dialog" role="document" style="min-width:70% !important;">
		 <div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="MyModalPreviewTitle"></h5>
				<button type ="button" class="close" aria-label="close" id="btn-modal-close-preview">
					<span aria-hidden="true"><i class="fa fa-close"></i></span>
				</button>
			</div>
			<div class="modal-body" id="MyModalPreviewDetail">
			
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
		
	});
	$(document).on('click','#btn-modal-close-preview',()=>{
		$("#MyModalPreviewDetail").html('');
		$('#MyModalPreviewTitle').text('');
		$("#MyModalPreview").modal('hide');
	});
	function ViewReceiveTool(Code_DetReceive){
		let Code_Receive	= $('#code_process').val();
		
		loading_spinner_new();
		$.post(site_url+'/'+act_controller+'/preview_receive_tool_file',{'code_rec_detail':Code_DetReceive},function(response){
			close_spinner_new();
			$('#MyModalPreviewTitle').html('PREVIEW RECEIVE TOOL FILES');
			$('#MyModalPreviewDetail').html(response);
			$('#MyModalPreview').modal('show');
			
		});
	}
	
</script>