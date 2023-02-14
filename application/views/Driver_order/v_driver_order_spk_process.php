<?php
$this->load->view('include/side_menu');
?> 
<form action="#" method="POST" id="form-proses" enctype="multipart/form-data">
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
							<label class="control-label">Letter No</label>
							<div>
							<?php
								echo'<span class="badge bg-blue">AUTOMATIC</span>';
							?>
							</div>
						</div>
					</div>
					<div class="col-sm-6">
						<div class="form-group">
							<label class="control-label">Date <span class="text-red">*</span></label>
							<?php
								echo form_input(array('id'=>'datet','name'=>'datet','class'=>'form-control input-sm','readOnly'=>true),date('d-m-Y',strtotime($plan_date)));						
							?>
						</div>
					</div>				
				</div>
				<div class='row'>
					<div class="col-sm-6">
						<div class="form-group">
							<label class="control-label">Driver Name <span class="text-red">*</span></label>
							<div>
								<select name="driver_id" id="driver_id" class="form-control chosen-select">
									<option value=""> - SELECT AN OPTION - </option>
									<?php
									if($rows_driver){
										foreach($rows_driver as $keyDriver=>$valDriver){
											$Code_Driver	= $valDriver->id;
											$Name_Driver	= strtoupper($valDriver->nama);
											$ChosenDriver	= $Code_Driver.'^'.$Name_Driver;
											echo'<option value="'.$ChosenDriver.'">'.$Name_Driver.'</option>';
										}
									}					
									?>
								</select>
							</div>
						</div>
					</div>
					<div class="col-sm-6">
						<div class="form-group">
							<label class="control-label">Notes</label>
							<?php
								echo form_textarea(array('id'=>'notes','name'=>'notes','class'=>'form-control input-sm','cols'=>100,'rows'=>2));					
							?>
						</div>
					</div>				
				</div>
				
				
				<div class="row">
					<div class="col-sm-12 col-xs-12 text-center sub-heading" style="color:white;">
						<h5>DRIVER ORDER </h5>
					</div>
					
				</div>
				<div class="row">
					<div class="col-sm-12" style="overflow-x:scroll !important;">
						<table class="table table-striped table-bordered" id="my-grid">
							<thead>
								<tr class="bg-navy-active">
									<th class="text-center">No</th>
									<th class="text-center">Driver Order</th>
									<th class="text-center">Plan Date</th>				
									<th class="text-center">Company</th>
									<th class="text-center">Type</th>
									<th class="text-center">Description</th>
									<th class="text-center">Address</th>
									<th class="text-center">Option</th>
								</tr>
							</thead>
							<tbody id="list_detail_order">
								<?php
								if($rows_header){
									$intL	= 0;
									foreach($rows_header as $ketD=>$row){
										$intL++;
										$Code_Order		= $row['order_code'];
										$Nomor_Order	= $row['order_no'];
										$Date_Order		= date('d-m-Y',strtotime($row['plan_date']));
										$Time_Order		= $row['plan_time'];
										$Code_Cust		= $row['company_code'];
										$Name_Cust		= $row['company'];
										$Type_Cust		= $row['type_comp'];
										$Type_Process	= $row['category'];
										$Addr_Cust		= $row['address'];
										$PIC_Name_Cust	= $row['pic_name'];
										$PIC_Phone_Cust	= $row['pic_phone'];
										$Status_Order	= $row['sts_order'];
										
												
										
										
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
										
										$Template		= '<button type="button" class="btn btn-sm btn-primary" onClick = "ActionPreview({code:\''.$Code_Order.'\',action :\'detail_driver_order\',title:\'VIEW DRIVER ORDER\'});" title="VIEW DRIVER ORDER"> <i class="fa fa-search"></i> </button>';
										
										echo'
										<tr>
											<input type="hidden" name="detDetail['.$intL.']" id="code_order_'.$intL.'" value="'.$Code_Order.'">
											<td class="text-center">'.$intL.'</td>
											<td class="text-center">'.$Nomor_Order.'</td>
											<td class="text-center">'.$Date_Order.'</td>
											<td class="text-left text-wrap">'.$Name_Cust.'</td>
											<td class="text-center">'.$Ket_Comp.'</td>
											<td class="text-center">'.$Ket_Category.'</td>
											<td class="text-left text-wrap">'.$Addr_Cust.'</td>
											<td class="text-center">'.$Template.'</td>											
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
		echo'
		
			<div class="row col-md-2 col-md-offset-5" id="loader_proses_save">
				<div class="loader">
					<span></span>
					<span></span>
					<span></span>
					<span></span>
				</div>
			</div>
		</div>
		';
		echo"<div class='box-footer'>";	
			echo'
				<button type="button" class="btn btn-md btn-danger" id="btn-back"> <i class="fa fa-long-arrow-left"></i> BACK </button>';
		if(!empty($rows_header)){			
				echo'
				&nbsp;&nbsp;&nbsp;<button type="button" id="btn-process-approve" class="btn btn-md" style="background-color:#37474f; color:white;vertical-align:middle !important;" title="SAVE PROCESS"> SAVE PROCESS <i class="fa fa-long-arrow-right" style="width:40px;"></i> </button>';
			
		}
		echo"</div>";
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
	.text-wrap{
		word-wrap : break-word !important;
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
</style>
<script>
	var base_url			= '<?php echo site_url(); ?>';
	var active_controller	= '<?php echo($this->uri->segment(1)); ?>';
	$(document).ready(function(){
		$('#datet').datepicker({
			dateFormat	: 'dd-mm-yy',
			changeMonth	:true,
			changeYear	:true,
			minDate		: '+0d'
		});
		
		$('#loader_proses_save').hide();
	});
	
	function ActionPreview(ObjectParam){
		let TitleAction	= ObjectParam.title;
		let CodeAction	= ObjectParam.code;
		let LinkAction 	= ObjectParam.action;
		
		loading_spinner_new();
		
		$('#MyModalTitle').text(TitleAction);		
        $.post(base_url +'/'+ active_controller+'/'+LinkAction,{'code':CodeAction}, function(response) {
			close_spinner_new();
            $("#MyModalDetail").html(response);
        });
		$("#MyModalView").modal('show');		
	}
	
	
	
	$(document).on('click','#btn-back',(e)=>{
		loading_spinner();
		window.location.href =  base_url+'/'+active_controller;
	});
	
	
	
	$(document).on('click','#btn-process-approve', async(e)=>{
		e.preventDefault();
		$('#btn-back, #btn-process-approve').prop('disabled',true);
		let DateChosen		= $('#datet').val();
		let DriverChosen	= $('#driver_id').val();
		
		
		const ValueCheck	= {
			'datet':{'nilai':DateChosen,'error':'Empty Letter Date. Please input letter date first..'},
			'driver':{'nilai':DriverChosen,'error':'Empty Driver. Please choose driver first..'}
		};
		
		
		
		try{			
			const ResultCheck		= await GeneralCheckEmptyValue(ValueCheck);
			const ResultConfirm		= await GeneralShowConfirmSave();
			const formData 			= new FormData($('#form-proses')[0]);
			const ParamProcess	= {
				'action'		: 'save_create_spk_driver_order',
				'parameter'		: formData,
				'loader'		: 'loader_proses_save'
			};			
			const Hasil_Bro			= await GeneralAjaxProcessData(ParamProcess);
			
			if(Hasil_Bro.status == '1'){
				GeneralShowMessageError('success',Hasil_Bro.pesan);
				window.location.href	= base_url+'/'+active_controller;
			}else{
				GeneralShowMessageError('error',Hasil_Bro.pesan);
				$('#btn-back, #btn-process-approve').prop('disabled',false);
				return false;
			}			
		}catch(error){
			GeneralShowMessageError('error',error.message);
			$('#btn-back, #btn-process-approve').prop('disabled',false);
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
