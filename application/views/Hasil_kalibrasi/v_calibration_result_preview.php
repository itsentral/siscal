<?php
$this->load->view('include/side_menu');
?> 
<form action="#" method="POST" id="form-proses" enctype="multipart/form-data">
	<div class="box box-warning">
		<div class="box-header">			
			<div class="box-tools pull-right">
				<?php 
					echo"<button type='button' class='btn btn-md btn-danger' id='btn-back'> <i class='fa fa-angle-double-left'></i> BACK </button>&nbsp;&nbsp;&nbsp;";			
				?>
			</div>
		</div> 
		<div class="box-body">
			<div class="row">
				<div class="col-sm-12 col-xs-12 text-center sub-heading" style="color:white;">
					<h5><?php echo $title;?></h5>
				</div>
				
			</div>
			<?php
			if(empty($rows_trans)){
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
							<label class="control-label">SO No</label>
							<?php
								echo form_input(array('id'=>'nomor_so','name'=>'nomor_so','class'=>'form-control input-sm','readOnly'=>true),$rows_letter->no_so);
								echo form_input(array('id'=>'code_so','name'=>'code_so','type'=>'hidden'),$rows_letter->id);
							?>
						</div>
					</div>
					<div class="col-sm-6">
						<div class="form-group">
							<label class="control-label">SO Date</label>
							<?php
								echo form_input(array('id'=>'tgl_so','name'=>'tgl_so','class'=>'form-control input-sm','readOnly'=>true),date('d-m-Y',strtotime($rows_letter->tgl_so)));						
							?>
						</div>
					</div>				
				</div>
				<div class='row'>
					<div class="col-sm-6">
						<div class="form-group">
							<label class="control-label">Customer</label>
							<?php
								echo form_input(array('id'=>'customer_name','name'=>'customer_name','class'=>'form-control input-sm','readOnly'=>true),$rows_letter->customer_name);						
							?>
						</div>
					</div>
					<div class="col-sm-6">
						<div class="form-group">
							<label class="control-label">Quotation</label>
							<?php
								echo form_input(array('id'=>'nomor_quotation','name'=>'nomor_quotation','class'=>'form-control input-sm','readOnly'=>true),$rows_quot->nomor);						
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
							<label class="control-label">Marketing</label>
							<?php
								echo form_input(array('id'=>'marketing','name'=>'teknisi','class'=>'form-control input-sm','readOnly'=>true),strtoupper($rows_quot->member_name));						
							?>
						</div>
					</div>
				</div>
				
				<div class="row">
					<div class="col-sm-12 col-xs-12 text-center sub-heading" style="color:white;">
						<h5>PIC LAB CUSTOMER</h5>
					</div>
					<div class="col-sm-12 col-xs-12">&nbsp;</div>
				</div>
				<div class='row'>					
					<div class="col-sm-6">
						<div class="form-group">
							<label class="control-label">Name</label>
							<?php
								echo form_input(array('id'=>'pic_lab','name'=>'pic_lab','class'=>'form-control input-sm','readOnly'=>true),$rows_cust->lab);						
							?>
						</div>
					</div>	
					<div class="col-sm-6">
						<div class="form-group">
							<label class="control-label">Phone</label>
							<?php
								echo form_input(array('id'=>'pic_lab_hp','name'=>'pic_lab_hp','class'=>'form-control input-sm','readOnly'=>true),strtoupper($rows_cust->lab_hp));						
							?>
						</div>
					</div>
				</div>
				<div class='row'>					
					<div class="col-sm-6">
						<div class="form-group">
							<label class="control-label">Email</label>
							<?php
								echo form_input(array('id'=>'lab_email','name'=>'lab_email','class'=>'form-control input-sm','readOnly'=>true),$rows_cust->lab_email);						
							?>
						</div>
					</div>	
					<div class="col-sm-6">
						&nbsp;
					</div>
				</div>
				
				<div class="row">
					<div class="col-sm-12 col-xs-12 text-center sub-heading" style="color:white;">
						<h5>TOOL DETAIL</h5>
					</div>
					<div class="col-sm-12 col-xs-12">&nbsp;</div>
				</div>
				<div class="row">
				<div class="col-sm-12 text-right">
					<button type="button" class="btn bg-orange" onclick="PrintBarcodeBatch();"><i class="fa fa-print"></i> QRCode Batch</button>
				</div>
					<div class="col-sm-12" style="overflow-x:scroll !important;">
						<table class="table table-striped table-bordered" id="my-grid">
							<thead>
								<tr style="background-color:#16697A !important;color:white !important;">
									<th class="text-center">Code</th>
									<th class="text-center">Tool Name</th>
									<th class="text-center">Range</th>
									<th class="text-center">Result</th>
									<th class="text-center">Technician</th>
									<th class="text-center">Identify No</th>
									<th class="text-center">Serial<br>Number</th>
									<th class="text-center">Action</th>
								</tr>
							</thead>
							<tbody id="list_sertifikat">
								<?php
								
								if($rows_trans){
									$Arr_Location	= array(1=>'Client','Labs','Fine Good','Cawang');
									foreach($rows_trans as $ketK=>$valK){
										$Code_Alat		= $valK->tool_id;
										$Name_Alat		= $valK->tool_name;
										$ID_Alat		= $valK->id;
										$Schedule_Code	= $valK->trans_detail_id;
										$Labs			= $valK->labs;
										$Insitu			= $valK->insitu;
										$Subcon			= $valK->subcon;
										$Location		= $valK->location;
										$Cal_Result		= $valK->flag_proses;
										$Cal_Reschedule	= $valK->plan_reschedule;
										$Tool_Descr		= $valK->keterangan;
										$Status_Approve	= $valK->approve_certificate;
										$File_Cals		= $valK->file_kalibrasi;
										
										$File_Before	= $valK->before_cals_image;
										$File_After		= $valK->after_cals_image;
										
										$Tool_Range		= $valK->range.' '.$valK->piece_id;
										$Tool_QuotDet	= $valK->quotation_detail_id;
										$Real_Tech 		= $valK->actual_teknisi_name;
										
										$Identify_No	= $valK->no_identifikasi;
										$Serial_No		= $valK->no_serial_number;
										
										$Template		= '';
										
										if(!empty($File_Before) && $File_Before !=='-'){
											if(!empty($Template))$Template	.='&nbsp;&nbsp;';
											$Template	.='<a href="'.$this->file_attachement.'hasil_kalibrasi/'.$File_Before.'" target="_blank" class="btn btn-sm blue_grey" title="DOWNLOAD BEFORE CALIBRATION IMAGE"> <i class="fa fa-download"></i> </a>';											
											
										}
										
										if(!empty($File_After) && $File_After !=='-'){
											if(!empty($Template))$Template	.='&nbsp;&nbsp;';
											$Template	.='<a href="'.$this->file_attachement.'hasil_kalibrasi/'.$File_After.'" target="_blank" class="btn btn-sm brown" title="DOWNLOAD AFTER CALIBRATION IMAGE"> <i class="fa fa-download"></i> </a>';											
											
										}
										
										if($Cal_Result === 'Y'){
											$Status		= "<span class='badge bg-green'>SUCCESS</span>";
											if(!empty($File_Cals) && $File_Cals !=='-'){
												if(!empty($Template))$Template	.='&nbsp;&nbsp;';
												$Template	.='<a href="'.$this->file_attachement.'hasil_kalibrasi/'.$File_Cals.'" target="_blank" class="btn btn-sm btn-danger" title="DOWNLOAD CALIBRATION FILE"> <i class="fa fa-download"></i> </a>';
												
												if($Status_Approve !== 'APV'){
													if(!empty($Template))$Template	.='&nbsp;&nbsp;';
													$Template .= '<button type="button" class="btn btn-sm bg-navy-active" onClick = "ActionPreview({code:\''.$ID_Alat.'\',action :\'calibration_result_process\',title:\'UPDATE CALIBRATION FILE\'});" title="UPDATE CALIBRATION FILE"> <i class="fa fa-arrow-right fa-lg"></i> </button>';
												}
											}
											if(!empty($valK->sentral_code_tool) && $valK->sentral_code_tool !== '-'){
												if(!empty($Template))$Template	.='&nbsp;&nbsp;';
												$Template  .= '<button type="button" onClick="PrintBarcode(\''.$ID_Alat.'\',\'Y\')" class="btn btn-sm btn-warning" title="PRINT CALIBRATIONS BARCODE QR"> <i class="fa fa-print"></i> </button>';
												
												if(!empty($Template))$Template	.='&nbsp;&nbsp;';
												$Template  .= '<button type="button" onClick="PrintBarcode(\''.$ID_Alat.'\',\'N\')" class="btn btn-sm bg-orange-active" title="PRINT CALIBRATIONS BARCODE NON QR"> <i class="fa fa-print"></i> </button>';
											}
										}else if($Cal_Result === 'N'){
											if($Cal_Reschedule === 'Y'){
												$Status		= "<span class='badge bg-light-blue'>RESCHEDULE</span>";
											}else{
												$Status		= "<span class='badge bg-red'>FAIL / CANCEL</span>";
											}
										}else{
											if($Cal_Reschedule === 'Y'){
												$Status		= "<span class='badge bg-blue'>PLAN RESCHEDULE</span>";
											}else{
												$Status		= "<span class='badge bg-purple'>UNPROCESSED</span>";
											}
										}
										
										
										echo"<tr>";	
											echo"<td class='text-center'>".$ID_Alat."</td>";
											echo"<td class='text-left'>".$Name_Alat."</td>";
											echo"<td class='text-center'>".$Tool_Range."</td>";
											echo"<td class='text-center'>";
												echo $Status;
											echo"</td>";
											echo"<td class='text-center'>".$Real_Tech."</td>";
											echo"<td class='text-center'>".$Identify_No."</td>";	
											echo"<td class='text-center'>".$Serial_No."</td>";											
											echo"<td class='text-center'>".$Template."</td>";	
										echo"</tr>";
									}
								}
								?>
							</tbody>
						</table>
					</div>
				</div>
			<?php
			}
			?>
										
		</div>		
	
		
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

<div class="modal fade" id="FormModalQR">
	<div class="modal-dialog modal-sm" style="margin-top:250px;">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
				<span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title">Printer Tools</h4>
			</div>
			<form action="#" method="POST" id="form" enctype="multipart/form-data">
			<div class="modal-body">
				<input type="hidden" name="qr_code" readonly>
				<input type="hidden" name="qr_flag" readonly>

				<div class="row">
					<div class="form-group col-sm-12">
						<label class="control-label">Pengenal Alat</label>
						<select name="flaq_pengenal" class="form-control" style="width:100%">
							<option value="">By Sistem</option>
							<option value="I">Identify No</option>
							<option value="S">Serial Number</option>
						</select>						
					</div>

					<div class="form-group col-sm-12">
						<label class="control-label">Pilih Template </label>
						<select name="flaq_print" class="form-control" style="width:100%">
							<option value="Y">Landscape</option>
							<option value="N">Potrait</option>
						</select>						
					</div>
				</div>

			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-danger" data-dismiss="modal"><i class="glyphicon glyphicon-remove"></i> Batal</button>
				<button type="button" class="btn btn-primary" id="btnSave"><i class="glyphicon glyphicon-print"></i> Print</button>
			</div>	
		</form>
		</div>
	</div>
</div>

<div class="modal fade" id="FormModalQRBatch">
	<div class="modal-dialog modal-sm" style="margin-top:250px;">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
				<span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title">Download Data QRCode Batch</h4>
			</div>
			<form action="#" method="POST" id="form2" enctype="multipart/form-data">
			<div class="modal-body">
				<input type="hidden" name="codeso" value="<?php echo $Code_SO;?>" readonly>

				<div class="row">
					<div class="form-group col-sm-12">
						<label class="control-label">Pengenal Alat</label>
						<select name="flaq_pengenal_batch" class="form-control" style="width:100%">
							<option value="">By Sistem</option>
							<option value="I">Identify No</option>
							<option value="S">Serial Number</option>
						</select>						
					</div>
				</div>

			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-danger" data-dismiss="modal"><i class="glyphicon glyphicon-remove"></i> Batal</button>
				<button type="button" class="btn btn-primary" id="btnSaveBatch"><i class="glyphicon glyphicon-print"></i> Download</button>
			</div>	
		</form>
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
	.blue_grey{
		background-color : #37474f !important;
		color : #fff !important;
	}
	
	.brown{
		background-color : #5d4037 !important;
		color : #fff !important;
	}
	
	.amber{
		background-color : #ff6f00 !important;
		color : #fff !important;
	}
</style>
<script>
	var base_url			= '<?php echo site_url(); ?>';
	var active_controller	= '<?php echo($this->uri->segment(1)); ?>';
	var _validFileExtensions = [".xls", ".xlsx", ".xlsm", ".xlsxm"];
	$(document).ready(function(){
		
		$('#btn-back').click(function(){			
			loading_spinner();
			window.location.href =  base_url+'/'+active_controller;
		});
		
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
	
	$(document).on('click','#btn-process-reopen', async(e)=>{
		e.preventDefault();
		$('#btn-modal-close, #btn-process-reopen').prop('disabled',true);
	
		let Code_Back		= $('#code_back').val();		
		let CodeDetail		= $('#code_detail').val();
		let Lampiran_File	= $('#lampiran_kalibrasi').val();
		let reason			= $('#failed_reason').val();
		
		const ValueCheck	= {
			'file':{'nilai':Lampiran_File,'error':'Empty Calibration result file. Please upload calibration result file first..'},
			'alasan':{'nilai':reason,'error':'Empty Reason. Please input reason first..'}
		};
		
		
		try{			
			const ResultCheck		= await GeneralCheckEmptyValue(ValueCheck);
			const ResultConfirm		= await GeneralShowConfirmSave();
			const formData 			= new FormData($('#form-proses-revisi')[0]);
			const ParamProcess	= {
				'action'		: 'save_calibration_result_process',
				'parameter'		: formData,
				'loader'		: 'loader_proses_save'
			};			
			const Hasil_Bro			= await GeneralAjaxProcessData(ParamProcess);
			
			if(Hasil_Bro.status == '1'){
				GeneralShowMessageError('success',Hasil_Bro.pesan);
				window.location.href = base_url +'/'+ active_controller+'/view_detail?kode='+encodeURIComponent(Code_Back);
			}else{
				GeneralShowMessageError('error',Hasil_Bro.pesan);
				$('#btn-modal-close, #btn-process-reopen').prop('disabled',false);
				return false;
			}			
		}catch(error){
			GeneralShowMessageError('error',error.message);
			$('#btn-modal-close, #btn-process-reopen').prop('disabled',false);
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
					  text				: 'Hanya boleh pilih jenis file EXCEL....',						
					  type				: "warning"
					});
					
					oInput.value = "";
					return false;
				}
			}
		}
    	return true;
	}
	
	const PrintBarcode = (Code_Print, Flag_QR)=>{
		
		
		let Barcode_Action	= 'print_barcode_nonQR_tool';
		if(Flag_QR == 'Y'){
			Barcode_Action	= 'print_barcode_calibration_tool';
			$('[name="qr_code"]').val(Code_Print);
			$('[name="qr_flag"]').val(Flag_QR);
			//$('[name="qr_action"]').val(Barcode_Action);
			$('#FormModalQR').modal('show');
		}else{
			loading_spinner_new();
			$.post(base_url +'/'+ active_controller+'/'+Barcode_Action,{'code':Code_Print}, function(response) {
				close_spinner_new();
				const datas	= $.parseJSON(response);
				window.open(datas.path,'_blank');
        		});
			
		}

		

	};

	$('#btnSave').on('click',function(e) {
		e.preventDefault();
		//loading_spinner_new();

		$('#btnSave').html('<i class="glyphicon glyphicon-ok"></i> Proses...');
		$('#btnSave').attr('disabled', true);
		
		var flagPrint 		= $('[name="flaq_print"]').val();
		var flagPengenal 	= $('[name="flaq_pengenal"]').val();
		var Code_Print		= $('[name="qr_code"]').val();

		//alert(flagPrint);
		
		let url	= 'print_barcode_calibration_tool';

		if(flagPrint == 'Y'){
			url	= 'print_barcode_new';
		}
		
		//pakek ajax aja ntar gais ya

		$.post(base_url +'/'+ active_controller+'/'+url,{'code':Code_Print,'pengenal':flagPengenal}, function(response) {
			//close_spinner_new();
			
           		//console.log(response);
			const datas	= $.parseJSON(response);
			
			if(datas.hasil == 1){
				$('#FormModalQR').modal('hide');
				window.open(datas.path,'_blank');
				$('#btnSave').html('<i class="glyphicon glyphicon-print"></i> Print');
				$('#btnSave').attr('disabled', false);
			}else{
				alert("Maaf Print Data QRCode tidak dapat diproses!");
				$('#btnSave').html('<i class="glyphicon glyphicon-print"></i> Print');
				$('#btnSave').attr('disabled', false);

			}
			
        	});

	});

	function PrintBarcodeBatch(){
		$('#FormModalQRBatch').modal('show');
	}

	$('#btnSaveBatch').on('click',function(e) {
		e.preventDefault();
		//loading_spinner_new();

		$('#btnSaveBatch').html('<i class="glyphicon glyphicon-ok"></i> Proses...');
		$('#btnSaveBatch').attr('disabled', true);
		
		var Code_SO	 		  = $('[name="codeso"]').val();
		var flagPengenalBatch = $('[name="flaq_pengenal_batch"]').val();

		var Links = base_url + active_controller + '/downloadQRBatch/' + Code_SO + '/' + flagPengenalBatch;
			window.open(Links, '_blank');
			$('#FormModalQRBatch').modal('hide');
			$('#btnSaveBatch').html('<i class="glyphicon glyphicon-print"></i> Download');
			$('#btnSaveBatch').attr('disabled', false);
	});
	
</script>
