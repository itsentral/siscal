<?php
$this->load->view('include/side_menu');
?> 
<form action="#" method="POST" id="form_proses_order" enctype="multipart/form-data">
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
							<label class="control-label">Plan Date <span class="text-red"> *</span></label>
							<div>
							<?php
								
								
								echo form_input(array('id'=>'plan_date','name'=>'plan_date','class'=>'form-control input-sm tanggal','readOnly'=>true),date('d-m-Y'));	
								echo form_input(array('id'=>'customer_id','name'=>'customer_id','type'=>'hidden'),$code_company);
								echo form_input(array('id'=>'type_process','name'=>'type_process','type'=>'hidden'),$type_process);
								echo form_input(array('id'=>'category','name'=>'category','type'=>'hidden'),$category);
								
								$Label_Cust	= 'Customer';
								if(strtolower($category) !== 'cust'){
									$Label_Cust	= 'Subcon';
								}
								
								$Ket_Status	= '<span class="badge bg-green">AMBIL ALAT</span>';
								if(strtolower($type_process) == 'ins'){
									$Ket_Status	= '<span class="badge bg-orange">INSITU</span>';
								}else if(strtolower($type_process) == 'del'){
									$Ket_Status	= '<span class="badge bg-red">ANTAR ALAT</span>';
								}
								
							?>
							</div>
						</div>
					</div>
					<div class="col-sm-6">
						<div class="form-group">
							<label class="control-label">Plan Time <span class="text-red"> *</span></label>
							<?php
								echo form_input(array('id'=>'plan_time','name'=>'plan_time','class'=>'form-control input-sm'),date('H:i'));						
							?>
						</div>
					</div>			
				</div>
				<div class='row'>
					<div class="col-sm-6">
						<div class="form-group">
							<label class="control-label"><?php echo $Label_Cust;?> <span class="text-red"> *</span></label>
							<?php
								echo form_input(array('id'=>'customer_name','name'=>'customer_name','class'=>'form-control input-sm','readOnly'=>true),$company);
														
							?>
						</div>
					</div>
					<div class="col-sm-6">
						<div class="form-group">
							<label class="control-label">Address <span class="text-red"> *</span></label>
							<?php
								echo form_textarea(array('id'=>'address','name'=>'address','class'=>'form-control input-sm','cols'=>75,'rows'=>2,'readOnly'=>true),$alamat);						
							?>
						</div>
					</div>	
					
					
				</div>
				<div class='row'>	
					<div class="col-sm-6">
						<div class="form-group">
							<label class="control-label">PIC Name <span class="text-red"> *</span></label>
							<div>
							<?php								
								echo form_input(array('id'=>'pic_name','name'=>'pic_name','class'=>'form-control input-sm','readOnly'=>true),$pic_name);	
								
							?>
							</div>
						</div>
					</div>
					<div class="col-sm-6">
						<div class="form-group">
							<label class="control-label">PIC Phone <span class="text-red"> *</span></label>
							<?php
								echo form_input(array('id'=>'pic_phone','name'=>'pic_phone','class'=>'form-control input-sm','readOnly'=>true),$pic_phone);						
							?>
						</div>
					</div>	
					
					
				</div>
				<div class='row'>
					<div class="col-sm-6">
						<div class="form-group">
							<label class="control-label">Notes</label>
							<div>
							<?php
								
								echo form_textarea(array('id'=>'notes','name'=>'notes','class'=>'form-control input-sm text-up','cols'=>75,'rows'=>2));		
								
							?>
							</div>
						</div>
					</div>
					<div class="col-sm-6">
						<div class="form-group">
							<label class="control-label">Type</label>
							<div>
							<?php
								echo $Ket_Status;
								
							?>
							</div>
						</div>
					</div>	
					
				</div>
				
				
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
									<th class="text-center">Tool Name</th>
									<th class="text-center">Qty</th>				
									<th class="text-center">Type</th>
									<th class="text-center">SO No</th>
									<th class="text-center">Company</th>
									<th class="text-center">Description</th>
								</tr>
							</thead>
							<tbody id="list_detail">
								<?php
								if($rows_header){
									$intL	= 0;
									foreach($rows_header as $ketD=>$valD){
										$intL++;
											
										echo'<tr id="tr_urut_'.$intL.'">
											'.form_input(array('id'=>'code_process_'.$intL,'name'=>'detDetail['.$intL.'][code_process]','type'=>'hidden'),$valD['code_process']).'
											'.form_input(array('id'=>'tool_id_'.$intL,'name'=>'detDetail['.$intL.'][tool_id]','type'=>'hidden'),$valD['tool_id']).'
											'.form_input(array('id'=>'tool_name_'.$intL,'name'=>'detDetail['.$intL.'][tool_name]','type'=>'hidden'),$valD['tool_name']).'
											'.form_input(array('id'=>'qty_'.$intL,'name'=>'detDetail['.$intL.'][qty]','type'=>'hidden'),$valD['qty']).'
											<td class="text-center">'.$intL.'</td>
											<td class="text-center">'.$valD['tool_id'].'</td>
											<td class="text-left">'.$valD['tool_name'].'</td>
											<td class="text-center">'.$valD['qty'].'</td>
											<td class="text-center">'.$valD['tipe'].'</td>
											<td class="text-center">'.$valD['no_so'].'</td>
											<td class="text-left">'.$valD['description'].'</td>
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
		
		echo"
		<div class='box-body'>
			<div class='row col-md-2 col-md-offset-5' id='loader_proses_save'>
				<div class='loader'>
					<span></span>
					<span></span>
					<span></span>
					<span></span>
				</div>
			</div>
		</div>
		<div class='box-footer'>";	
			echo'
				<button type="button" class="btn btn-md btn-danger" id="btn-back"> <i class="fa fa-long-arrow-left"></i> BACK </button>';
		if(!empty($rows_header)){			
				echo'
				&nbsp;&nbsp;&nbsp;<button type="button" id="btn_process_order" class="btn btn-md" style="background-color:#37474f; color:white;vertical-align:middle !important;" title="SAVE PROCESS"> SAVE PROCESS <i class="fa fa-long-arrow-right" style="width:40px;"></i> </button>';
			
		}
		echo"</div>";
		?>
		
	</div>
</form>

<?php $this->load->view('include/footer'); ?>
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
	.text-center{
		text-align : center !important;
		vertical-align	: middle !important;
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
	
</style>
<script>
	var base_url			= '<?php echo site_url(); ?>';
	var active_controller	= '<?php echo($this->uri->segment(1)); ?>';
	$(document).ready(function(){
		$('#loader_proses_save').hide();
		
		$('.tanggal').datepicker({
			dateFormat	: 'dd-mm-yy',
			changeMonth	:true,
			changeYear	:true,
			minDate		:'+0d'
		});
		
		$('#plan_time').mask('?99:99');
		
	});
	
		
	$(document).on('click','#btn-back',(e)=>{
		loading_spinner();
		window.location.href =  base_url+'/'+active_controller;
	});
	
	
	
	$(document).on('click','#btn_process_order', async(e)=>{
		e.preventDefault();
		$('#btn-back, #btn_process_order').prop('disabled',true);
		
		let PlanDate 		= $('#plan_date').val();
		let PlanTime 		= $('#plan_time').val();
		
		const ValueCheck	= {
			'plan_date':{'nilai':PlanDate,'error':'Empty Plan Date. Please input plan date first..'},
			'plan_time':{'nilai':PlanTime,'error':'Empty Plan Time. Please input plan time first..'}
		};
		
		
		
		try{			
			const ResultCheck		= await GeneralCheckEmptyValue(ValueCheck);
			const ResultConfirm		= await GeneralShowConfirmSave();
			const formData 			= new FormData($('#form_proses_order')[0]);
			const ParamProcess	= {
				'action'		: 'save_create_driver_order',
				'parameter'		: formData,
				'loader'		: 'loader_proses_save'
			};			
			const Hasil_Bro			= await GeneralAjaxProcessData(ParamProcess);
			
			if(Hasil_Bro.status == '1'){
				GeneralShowMessageError('success',Hasil_Bro.pesan);
				window.location.href	= base_url+'/'+active_controller;
			}else{
				GeneralShowMessageError('error',Hasil_Bro.pesan);
				$('#btn-back, #btn_process_order').prop('disabled',false);
				return false;
			}			
		}catch(error){
			GeneralShowMessageError('error',error.message);
			$('#btn-back, #btn_process_order').prop('disabled',false);
            return false;
		}
	});
	
	
	
	function ValidateSingleInput(oInput) {
		if (oInput.type == "file") {
			var sFileName = oInput.value;
			 if (sFileName.length > 0) {
				var blnValid = false;
				for (var j = 0; j < _validFileExtensions.length; j++) {
					var sCurExtension = _validFileExtensions[j];
					if (sFileName.substr(sFileName.length - sCurExtension.length, sCurExtension.length).toLowerCase() == sCurExtension.toLowerCase()) {
						blnValid = true;
						break;
					}
				}
				 
				if (!blnValid) {
					swal({
					  title				: "Error Message !",
					  text				: 'Hanya boleh pilih jenis file IMAGES atau PDF....',						
					  type				: "warning"
					});
					
					oInput.value = "";
					return false;
				}
			}
		}
    	return true;
	}
	
	
	
</script>
