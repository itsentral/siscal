<?php
$this->load->view('include/side_menu'); 
?> 
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
						<label class="control-label">Notes</label>
						<?php
							echo form_textarea(array('id'=>'notes', 'name'=>'notes','cols'=>'75','rows'=>'2', 'class'=>'form-control input-sm','readOnly'=>true),$rows_header->notes);
													
						?>						
					</div>
				</div>	
				<div class="col-sm-6">
					&nbsp;
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
								<th class="text-center">Quotation</th>
								<th class="text-center">Tool Code</th>
								<th class="text-center">Tool Name</th>
								<th class="text-center">Range</th>
								<th class="text-center">Qty</th>
								<th class="text-center">Qty<br>Process</th>
								<th class="text-center">Qty<br>Receive</th>
								<th class="text-center">Qty<br>Reschedule</th>
								<th class="text-center">Qty<br>Outs</th>
								<th class="text-center"><input type="checkbox" id="chk_all" id="chk_all" value="Y"></th>
							</tr>
						</thead>
						<tbody id="list_detail_tool">
							<?php
							
							$Flag_Outs		= 'N';
							$Temp_SPK		= array();
							if($rows_detail){
								$intL	= 0;
								foreach($rows_detail as $ketD=>$valD){
									$intL++;
									$Code_Detail	= $valD->code_process;
									$Code_Alat		= $valD->tool_id;
									$Cust_Alat		= $valD->tool_name;									
									$Qty_Ord		= $valD->qty;
									$Qty_Pros		= $valD->qty_pros;
									$Range_Alat		= '-';
									$Quot_Nomor		= '-';
									$Query_Tool		= "SELECT det_quot.`range`, det_quot.piece_id, head_quot.nomor  FROM quotation_details det_quot INNER JOIN quotations head_quot ON head_quot.id=det_quot.quotation_id WHERE det_quot.id = '".$Code_Detail."'";
									$rows_Tool		= $this->db->query($Query_Tool)->row();
									if($rows_Tool){
										$Range_Alat		= $rows_Tool->range.' '.$rows_Tool->piece_id;
										$Quot_Nomor		= $rows_Tool->nomor;
									}
									
									$Action_Del		= '-';
									$Qty_Schedule	= $Qty_Receive	= 0;
									$Code_SPK_Detail= $valD->spk_driver_tool_id;
										
									if($Status_Order !== 'OPN'){
										## CEK CODE SPK ##
										
										$Qry_SPK_Tool		= "SELECT * FROM spk_driver_tools WHERE id = '".$Code_SPK_Detail."'";
										$det_SPK_Tool		= $this->db->query($Qry_SPK_Tool)->row();
										if($det_SPK_Tool){
											$Qty_Pros		= $det_SPK_Tool->qty;
											$Qty_Receive	= ($det_SPK_Tool->qty_proses > 0)?$det_SPK_Tool->qty_proses:0;
											$Qty_Schedule	= ($det_SPK_Tool->qty_reschedule > 0)?$det_SPK_Tool->qty_reschedule:0;
											
											if($Qty_Receive > 0){
												$Temp_SPK[]	= $Code_SPK_Detail;
											}
										}
										
									}
									
									$Qty_Outs		= $Qty_Ord - $Qty_Receive - $Qty_Schedule;
									if($Status_Order !== 'OPN' && $Qty_Outs > 0){
										$Flag_Outs		= 'Y';
										$Code_Sched		= $Code_SPK_Detail.'^_^'.$Qty_Outs;
										$Action_Del	= '<input type="checkbox" name="detReschedule[]" id="det_sched_'.$intL.'" value="'.$Code_Sched.'" class="chk_pilih">';			
			
									}
									
									
									
									echo'
									<tr>					
										<td class="text-center">'.$Quot_Nomor.'</td>
										<td class="text-center">'.$Code_Alat.'</td>
										<td class="text-left">'.$Cust_Alat.'</td>
										<td class="text-center">'.$Range_Alat.'</td>
										<td class="text-center">'.$Qty_Ord.'</td>
										<td class="text-center">'.$Qty_Pros.'</td>
										<td class="text-center">'.$Qty_Receive.'</td>
										<td class="text-center">'.$Qty_Schedule.'</td>
										<td class="text-center">'.$Qty_Outs.'</td>
										<td class="text-center">'.$Action_Del.'</td>
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
			if($Temp_SPK){
				$Impl_SPK		= implode("','",$Temp_SPK);
				$Query_Rec		= "SELECT
										head_rec.*,
										rec_det.quotation_id
									FROM
										quotation_driver_receives head_rec
										
									INNER JOIN quotation_driver_detail_receives rec_det ON rec_det.code_receive = head_rec.id
									WHERE
										rec_det.spk_driver_tool_id IN('".$Impl_SPK."')
									GROUP BY
										head_rec.id";
				echo'
				<div class="row">
					<div class="col-sm-12" style="overflow-x:scroll !important;">
						<table class="table table-striped table-bordered" id="my-grid2">
							<thead>
								<tr class="bg-blue-active">	
									<th class="text-center">Receive No</th>
									<th class="text-center">Date</th>
									<th class="text-center">Customer</th>
									<th class="text-center">Driver</th>
									<th class="text-center">SPK No</th>
									<th class="text-center">Action</th>
								</tr>
							</thead>
							<tbody id="list_receive">
				';
				$Pros_Rec	= $this->db->query($Query_Rec);
				$rows_Rec	= $Pros_Rec->result();
				if($rows_Rec){
					foreach($rows_Rec as $keyRec=>$valRec){
						$Code_Rec		= $valRec->id;
						$Nomor_Rec		= $valRec->nomor;
						$Date_Rec		= date('d-m-Y',strtotime($valRec->datet));
						$Customer_Rec	= $valRec->customer_name;
						$Driver_Rec		= $valRec->driver_name;
						$Flag_Sign		= $valRec->flag_sign;
						$Code_SPK		= $valRec->spk_driver_id;
						$Code_Quot		= $valRec->quotation_id;
						
						$Code_Unik		= $Code_Rec.'^'.$Code_Quot;
						
						$SPK_Driver		= '-';
						$rows_SPK		= $this->db->get_where('spk_drivers',array('id'=>$Code_SPK))->row();
						if($rows_SPK){
							$SPK_Driver	= $rows_SPK->nomor;
						}
						
						$Template		= '<button type="button" class="btn btn-sm btn-success" onClick = "PreviewReceive({code:\''.$Code_Unik.'\',action :\'preview_driver_recieve\',title:\'VIEW DRIVER RECEIVE\'});" title="VIEW DRIVER RECEIVE"> <i class="fa fa-search"></i> </button>';			
						if($Flag_Sign === 'N'){
							$Template		.= '&nbsp;&nbsp;<button type="button" class="btn btn-sm bg-navy-active" onClick = "PreviewReceive({code:\''.$Code_Unik.'\',action :\'cloce_driver_receive\',title:\'CLOSE DRIVER RECEIVE\'});" title="CLOSE DRIVER RECEIVE"> <i class="fa fa-edit"></i> </button>';
							
						}
						
						echo'
							<tr>					
								<td class="text-center">'.$Nomor_Rec.'</td>
								<td class="text-center">'.$Date_Rec.'</td>
								<td class="text-left">'.$Customer_Rec.'</td>
								<td class="text-left">'.$Driver_Rec.'</td>
								<td class="text-center">'.$SPK_Driver.'</td>
								<td class="text-center">'.$Template.'</td>
							</tr>
							';		
					}
				}
				echo'
							</tbody>
						</table>
					</div>
				</div>
				';
			}
			?>
		</div>	
		<?php
			
		}
		echo"
		<div class='box-body'>
			<div class='row col-md-2 col-md-offset-5' id='loader_proses_reschedule'>
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
			<button type="button" class="btn btn-md btn-danger" id="btn_kembali"> <i class="fa fa-long-arrow-left"></i> BACK </button>';
			if($Flag_Outs === 'Y'){		
				echo'
				&nbsp;&nbsp;&nbsp;<button type="button" id="btn_reschedule" class="btn btn-md" style="background-color:#37474f; color:white;vertical-align:middle !important;" title="RESCHEDULE PROCESS"> RESCHEDULE PROCESS <i class="fa fa-long-arrow-right" style="width:40px;"></i> </button>';
				
			}
		echo"
		</div>";
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
	.overlay_load {
		background: #eee; 
		display: none;       
		position: absolute;  
		top: 0;              
		right: 0;            
		bottom: 0;
		left: 0;
		padding-top:40%;
		opacity: 0.7;
		z-index:2;
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
	var base_url			= '<?php echo site_url(); ?>';
	var active_controller	= '<?php echo($this->uri->segment(1)); ?>';
	$(document).ready(function(){
		$('#loader_proses_reschedule').hide();
	});
	$(document).on('click','#chk_all',()=>{
		if($('#chk_all').is(':checked')){		
			$('#list_detail_tool input[type="checkbox"].chk_pilih').prop('checked',true);
		}else{
			$('#list_detail_tool input[type="checkbox"].chk_pilih').prop('checked',false);
		}

	});
	function PreviewReceive(ObjectParam){
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
	
	$(document).on('click','#btn_kembali', ()=>{
		window.location.href	= base_url+'/'+active_controller;
	});
	
	$(document).on('click','#btn_reschedule', async(e)=>{
		e.preventDefault();
		$('#btn_kembali, #btn_reschedule').prop('disabled',true);
		let JumChecked	= '';
		let Jum_Chosen 	= $('#list_detail_tool input[type="checkbox"].chk_pilih:checked').length;
		if(parseInt(Jum_Chosen) > 0){
			JumChecked	= Jum_Chosen;
		}
		let CodeOrder	 = $('#code_order').val();
		const ValueCheck	= {
			'alasan':{'nilai':JumChecked,'error':'No record was selected to process..'}
		};
		
		
		
		
		try{			
			const ResultCheck		= await GeneralCheckEmptyValue(ValueCheck);
			const ResultConfirm		= await GeneralShowConfirmSave();
			const formData 			= new FormData($('#form_proses_driver_order')[0]);
			const ParamProcess	= {
				'action'		: 'save_reschedule_driver_order',
				'parameter'		: formData,
				'loader'		: 'loader_proses_reschedule'
			};			
			const Hasil_Bro			= await GeneralAjaxProcessData(ParamProcess);
			
			if(Hasil_Bro.status == '1'){
				GeneralShowMessageError('success',Hasil_Bro.pesan);
				window.location.href	= base_url+'/'+active_controller+'/detail_driver_order?code='+encodeURIComponent(CodeOrder);
			}else{
				GeneralShowMessageError('error',Hasil_Bro.pesan);
				$('#btn_kembali, #btn_reschedule').prop('disabled',false);
				return false;
			}			
		}catch(error){
			GeneralShowMessageError('error',error.message);
			$('#btn_kembali, #btn_reschedule').prop('disabled',false);
            return false;
		}
	});
	
	$(document).on('click','#btn_close_receive', async(e)=>{
		e.preventDefault();
		$('#btn-modal-close, #btn_close_receive').prop('disabled',true);
		
		let CancelReason = $('#close_reason').val();
		let CodeOrder	 = $('#code_order').val();
		const ValueCheck	= {
			'alasan':{'nilai':CancelReason,'error':'Empty Close Reason. Please input reason first..'}
		};
		
		
		
		
		try{			
			const ResultCheck		= await GeneralCheckEmptyValue(ValueCheck);
			const ResultConfirm		= await GeneralShowConfirmSave();
			const formData 			= new FormData($('#form-prewview-driver-pickup')[0]);
			const ParamProcess	= {
				'action'		: 'save_close_driver_receive',
				'parameter'		: formData,
				'loader'		: 'loader_proses_save'
			};			
			const Hasil_Bro			= await GeneralAjaxProcessData(ParamProcess);
			
			if(Hasil_Bro.status == '1'){
				GeneralShowMessageError('success',Hasil_Bro.pesan);
				window.location.href	= base_url+'/'+active_controller+'/detail_driver_order?code='+encodeURIComponent(CodeOrder);
			}else{
				GeneralShowMessageError('error',Hasil_Bro.pesan);
				$('#btn-modal-close, #btn_close_receive').prop('disabled',false);
				return false;
			}			
		}catch(error){
			GeneralShowMessageError('error',error.message);
			$('#btn-modal-close, #btn_close_receive').prop('disabled',false);
            return false;
		}
	});
</script>