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
			if(empty($rows_detail) && empty($code_process)){
				echo"<div class='row'>
						<div class='col-sm-12'>
							<h4 class='text-red'><b>NO RECORD WAS FOUND.....</b></h4>
						</div>
					</div>";
			}else{
				$Flag_New	= 'Y';
				$Receive_Date	= date('Y-m-d');
				if(!empty($code_process)){
					$Flag_New		= 'N';
					$Code_Receive	= $code_process;
					$Receive_Date	= $rows_rec->datet;
				}else{
					$Code_Receive	= 'BPB-'.date('YmdHis');
				}
				$Code_Quot     	= '';
				if(!empty($rows_detail)){
					$Code_Quot     	= $rows_detail[0]->quotation_id;
				}else if(!empty($rows_rec)){
					$Code_Quot     	= $rows_rec->quotation_id;
				}
				
				$rows_Quot		= $this->db->get_where('quotations',array('id'=>$Code_Quot))->row();
				
			?>
				<div class='row'>
					<div class="col-sm-6">
						<div class="form-group">
							<label class="control-label">Receive No</label>
							<div>
							<?php
								
								
								echo form_input(array('id'=>'code_process','name'=>'code_process','class'=>'form-control input-sm','readOnly'=>true),$Code_Receive);	
								echo form_input(array('id'=>'flag_new','name'=>'flag_new','type'=>'hidden'),$Flag_New);
								echo form_input(array('id'=>'code_rec_driver','name'=>'code_rec_driver','type'=>'hidden'),$rows_header->id);
								echo form_input(array('id'=>'driver_id','name'=>'driver_id','type'=>'hidden'),$rows_header->driver_id);
								echo form_input(array('id'=>'code_quot','name'=>'code_quot','type'=>'hidden'),$Code_Quot);
								echo form_input(array('id'=>'rec_category','name'=>'rec_category','type'=>'hidden'),'CUSTOMER');
								
								
								
								
							?>
							</div>
						</div>
					</div>
					<div class="col-sm-6">
						<div class="form-group">
							<label class="control-label">Receive Date</label>
							<?php
								echo form_input(array('id'=>'datet','name'=>'datet','class'=>'form-control input-sm','readOnly'=>true),date('d-m-Y',strtotime($Receive_Date)));						
							?>
						</div>
					</div>				
				</div>
				<div class='row'>
					<div class="col-sm-6">
						<div class="form-group">
							<label class="control-label">Receive Type</label>
							<div>
								<?php
									echo'<span class="badge bg-blue">PICKU BY DRIVER</span>';					
								?>
							</div>
						</div>
					</div>
					<div class="col-sm-6">&nbsp;</div>				
				</div>
				<div class="row">
					<div class="col-sm-12 col-xs-12 text-center sub-heading" style="color:white;">
						<h5>DETAIL DRIVER RECEIVE</h5>
					</div>
					
				</div>
				<div class='row'>
					<div class="col-sm-6">
						<div class="form-group">
							<label class="control-label">Pickup No</label>
							<?php
								echo form_input(array('id'=>'pickup_nomor','name'=>'pickup_nomor','class'=>'form-control input-sm','readOnly'=>true),$rows_header->nomor);	
								
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
								echo form_input(array('id'=>'quotation_nomor','name'=>'quotation_nomor','class'=>'form-control input-sm','readOnly'=>true),$rows_Quot->nomor);	
								
							?>
						</div>
					</div>
					<div class="col-sm-6">
						<div class="form-group">
							<label class="control-label">Quotation Date</label>
							<?php
								echo form_input(array('id'=>'quotation_date','name'=>'quotation_date','class'=>'form-control input-sm','readOnly'=>true),date('d-m-Y',strtotime($rows_Quot->datet)));						
							?>
						</div>
					</div>				
				</div>
				<div class='row'>
					<div class="col-sm-6">
						<div class="form-group">
							<label class="control-label">PO No</label>
							<?php
								echo form_input(array('id'=>'pono','name'=>'pono','class'=>'form-control input-sm','readOnly'=>true),$rows_Quot->pono);	
								
							?>
						</div>
					</div>
					<div class="col-sm-6">
						<div class="form-group">
							<label class="control-label">PO Date</label>
							<?php
								echo form_input(array('id'=>'podate','name'=>'podate','class'=>'form-control input-sm','readOnly'=>true),date('d-m-Y',strtotime($rows_Quot->podate)));						
							?>
						</div>
					</div>				
				</div>
				<div class='row'>
					<div class="col-sm-6">
						<div class="form-group">
							<label class="control-label">BAST File</label>
							<div>
							<?php
								echo '<a href="'.$this->file_attachement.'quotation_receive_tool/'.$rows_header->id.'.pdf" class="btn btn-sm bg-orange-active" target ="_blank"> PREVIEW FILE </a>';	
								
							?>
							</div>
						</div>
					</div>
					<div class="col-sm-6">&nbsp;</div>				
				</div>
				
				<div class="row">
					<div class="col-sm-12 col-xs-12 text-center sub-heading" style="color:white;">
						<h5>OUTSTANDING RECEIVE ITEM </h5>
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
									<th class="text-center">Action</th>
								</tr>
							</thead>
							<tbody id="list_detail">
								<?php
								if($rows_detail){
									$intL	= 0;
									foreach($rows_detail as $ketD=>$valD){
										if($valD->flag_warehouse == 'N'){
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
												<td class="text-left">'.$Notes_Quot.'</td>
												<td class="text-center"><button type="button" onClick="ReceiveTool(\''.$Code_Detail.'\',\''.$valD->code_receive.'\')" class="btn btn-sm btn-danger" title="RECEIVE TOOLS"> <i class="fa fa-long-arrow-right"></i> </button></td>
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
				
			<?php
				## CEK JIKA ADA SUDAH DITERIMA ~ BASED ON CODE RECEIVE ##
				if($rows_rec_det){
					echo'
					<div class="row">
						<div class="col-sm-12 col-xs-12 text-center sub-heading" style="color:white;">
							<h5>DETAIL RECEIVE ITEM </h5>
						</div>						
					</div>
					<div class="row">
						<div class="col-sm-12" style="overflow-x:scroll !important;">
							<table class="table table-striped table-bordered" id="my_table)receive">
								<thead>
									<tr class="bg-navy-active">
										<th class="text-center">No</th>
										<th class="text-center">Tool Code</th>
										<th class="text-center">Tool Name</th>
										<th class="text-center">Range</th>				
										<th class="text-center">Description</th>
										<th class="text-center">Serial<br>Number</th>
										<th class="text-center">Sentral<br>Tool Code</th>
										<th class="text-center">Action</th>
									</tr>
								</thead>
								<tbody id="list_receive">
					';
							$intR	= 0;
							foreach($rows_rec_det as $keyRec=>$valRec){
								$intR++;
								$Nomor_Seri		= '-';
								$Range_Receive	= '-';
								$rows_Sentral	= $this->db->get_where('sentral_customer_tools',array('sentral_tool_code'=>$valRec->sentral_code_tool))->row();
								if($rows_Sentral){
									$Nomor_Seri	= $rows_Sentral->no_serial_number;
								}
								$rows_QuotDet	= $this->db->get_where('quotation_details',array('id'=>$valRec->quotation_detail_id))->row();
								if($rows_QuotDet){
									$Range_Receive		= $rows_QuotDet->range.' '.$rows_QuotDet->piece_id;
								}
								
								echo'
								<tr>
									<td class="text-center">'.$intR.'</td>
									<td class="text-center">'.$valRec->tool_id.'</td>
									<td class="text-left">'.$valRec->tool_name.'</td>
									<td class="text-center">'.$Range_Receive.'</td>
									<td class="text-left">'.$valRec->descr.'</td>
									<td class="text-center">'.$Nomor_Seri.'</td>
									<td class="text-center">'.$valRec->sentral_code_tool.'</td>
									<td class="text-center">
									<button type="button" onClick="ViewReceiveTool(\''.$valRec->id.'\')" class="btn btn-sm btn-danger" title="VIEW RECEIVE TOOLS"> <i class="fa fa-search"></i> </button>
									&nbsp;&nbsp;<button type="button" onClick="PrintBarcode(\''.$valRec->sentral_code_tool.'\')" class="btn btn-sm btn-warning" title="PRINT TOOL BARCODE"> <i class="fa fa-print"></i> </button>
									&nbsp;&nbsp;<button type="button" onClick="PrintBarcodeReceive(\''.$valRec->id.'\')" class="btn btn-sm btn-primary" title="PRINT RECEIVE BARCODE"> <i class="fa fa-print"></i> </button>
									</td>
								</tr>
								';
							}
					
					echo'
								</tbody>
							</table>
						</div>
					</div>
					';
				}
			}
		echo'</div>';
		echo"<div class='box-footer text-center'>";	
			echo'
				<button type="button" class="btn btn-md bg-navy-active" id="btn-back"> <i class="fa fa-long-arrow-left"></i> &nbsp;&nbsp;BACK </button>';
		
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
</style>
<script>
	var base_url			= '<?php echo site_url(); ?>';
	var active_controller	= '<?php echo($this->uri->segment(1)); ?>';
	$(document).ready(function(){
		
	});
	
	const ReceiveTool =(nomor_detail,nomor_header)=>{
		let Code_Receive	= $('#code_process').val();
		let Flag_New		= $('#flag_new').val();
		loading_spinner_new();
		$.post(base_url+'/'+active_controller+'/process_receive_tool_pickup_driver',{'code_rec_detail':nomor_detail,'code_process':Code_Receive,'flag_new':Flag_New,'code_rec':nomor_header},function(response){
			close_spinner_new();
			$('#MyModalTitle').html('RECEIVE TOOLS - PICKUP BY DRIVER');
			$('#MyModalDetail').html(response);
			$('#MyModalView').modal('show');
		});
	};
	
	const ViewReceiveTool = (Code_DetReceive)=>{
		let Code_Receive	= $('#code_process').val();
		
		loading_spinner_new();
		$.post(base_url+'/'+active_controller+'/preview_receive_tool_pickup_driver',{'code_rec_detail':Code_DetReceive,'code_process':Code_Receive},function(response){
			close_spinner_new();
			$('#MyModalTitle').html('PREVIEW RECEIVE TOOLS - SEND BY CUSTOMER');
			$('#MyModalDetail').html(response);
			$('#MyModalView').modal('show');
			
		});
	}
	
	const PrintBarcode = (Code_Print)=>{
		let Link_Print	= base_url+'/'+active_controller+'/print_barcode_receive_tool?code_tool='+encodeURIComponent(Code_Print);
		window.open(Link_Print,'_blank');
	};
	
	const PrintBarcodeReceive = (Code_Print)=>{
		let Link_Print	= base_url+'/'+active_controller+'/print_barcode_receive?receive='+encodeURIComponent(Code_Print);
		window.open(Link_Print,'_blank');
	};
	
	
	$(document).on('click','#btn-back',(e)=>{
		loading_spinner();
		window.location.href =  base_url+'/'+active_controller;
	});
	
	/*
	| ----------------------- |
	| 	 CEK EXISTING CODE 	  |
	| ----------------------- |
	*/
	$(document).on('click','#btn_cari_detail',()=>{
		let Code_Cari	= $('#code_cust_cari').val();
		let Code_Cust	= $('#cust_id_modal').val();
		if(Code_Cari == '' || Code_Cari == null || Code_Cari == '-'){
			swal({
			  title				: "Error Message !",
			  text				: 'Incorrect sentral customer code tool. Please input correct code...',						
			  type				: "warning"
			});			
			return false;
		}
		$('#loader_proses_save').show();
		
		$.post(base_url+'/'+active_controller+'/GetDetailSentralCode',{'code_find':Code_Cari,'customer':Code_Cust},function(response){
			$('#loader_proses_save').hide();
			const data_res	= $.parseJSON(response);
			//console.log(data_res.tipe);
			let code_sentral	= data_res.code;
			let code_identify	= data_res.no_identify;
			let code_serial		= data_res.no_serial;
			let code_merk		= data_res.merk;
			let code_type		= data_res.tipe
			
			$('#code_sentral_tool').val(code_sentral);
			$('#no_identifikasi').val(code_identify);
			$('#no_serial_number').val(code_serial);
			$('#merk_alat').val(code_merk);
			$('#tipe_alat').val(code_type);
		});
		
	});
	
	
	$(document).on('click','#btn_process_update', async(e)=>{
		e.preventDefault();
		$('#btn-modal-close, #btn_process_update').prop('disabled',true);
		let Code_Trans	= $('#code_trans').val();
		let Code_Rec	= $('#code_rec_driver').val();
		let Code_Quot	= $('#quotation_update').val();
		let Identfy_No	= $('#no_identifikasi').val();
		let Serial_No	= $('#no_serial_number').val();
		let Merk		= $('#merk_alat').val();
		let Tipe_Alat	= $('#tipe_alat').val();
		
		let Code_Unik	= Code_Rec+'^'+Code_Quot;
		
		const ValueCheck	= {
			'identify':{'nilai':Identfy_No,'error':'Empty Identfy No. Please input identfy no first..'},
			'serial_number':{'nilai':Serial_No,'error':'Empty Serial Number No. Please input serial number no first..'},
			'merk':{'nilai':Merk,'error':'Empty Tool Merk. Please input tool merk first..'}
		};
		
		
		
		try{			
			const ResultCheck		= await GeneralCheckEmptyValue(ValueCheck);
			const ResultConfirm		= await GeneralShowConfirmSave();
			const formData 			= new FormData($('#form_proses_update')[0]);
			const ParamProcess	= {
				'action'		: 'save_receive_pickup_driver_process',
				'parameter'		: formData,
				'loader'		: 'loader_proses_save'
			};			
			const Hasil_Bro			= await GeneralAjaxProcessData(ParamProcess);
			
			if(Hasil_Bro.status == '1'){
				GeneralShowMessageError('success',Hasil_Bro.pesan);
				window.location.href	= base_url+'/'+active_controller+'/receive_process?receive='+encodeURIComponent(Code_Unik)+'&code_process='+encodeURIComponent(Code_Trans);
			}else{
				GeneralShowMessageError('error',Hasil_Bro.pesan);
				$('#btn-modal-close, #btn_process_update').prop('disabled',false);
				return false;
			}			
		}catch(error){
			GeneralShowMessageError('error',error.message);
			$('#btn-modal-close, #btn_process_update').prop('disabled',false);
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
