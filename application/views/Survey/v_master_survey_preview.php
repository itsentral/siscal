<?php
$this->load->view('include/side_menu');
?> 
<form action="#" method="POST" id="form_proses_survey" enctype="multipart/form-data">
	<div class="box box-warning">		
		<div class="box-body">
		
			<div class="row">
				<div class="col-sm-12 col-xs-12 text-center sub-heading" style="color:white;">
					<h5>DETAIL SURVEY</h5>
				</div>					
			</div>
			<div class='row'>
				<div class="col-sm-6">
					<div class="form-group">
						<label class="control-label">Title Survey <span class="text-red"> *</span></label>
						<?php
							echo form_input(array('id'=>'survey_title','name'=>'survey_title','class'=>'form-control input-sm text-up','autocomplete'=>'off','readOnly'=>true),$rows_header->title_survey);
							echo form_input(array('id'=>'code_survey','name'=>'code_survey','type'=>'hidden'),$rows_header->code_survey);	
						?>
					</div>
				</div>
				<div class="col-sm-6">
					<div class="form-group">
						<label class="control-label">Description Survey <span class="text-red"> *</span></label>
						<?php
							echo form_textarea(array('id'=>'survey_descr','name'=>'survey_descr','class'=>'form-control input-sm text-up','cols'=>75,'rows'=>2,'autocomplete'=>'off','readOnly'=>true),$rows_header->descr);								
						?>
					</div>
				</div>				
			</div>
			<div class='row'>
				<div class="col-sm-6">
					<div class="form-group">
						<label class="control-label">Valid Start <span class="text-red"> *</span></label>
						<?php
							echo form_input(array('id'=>'survey_valid_start','name'=>'survey_valid_start','class'=>'form-control input-sm','autocomplete'=>'off','readOnly'=>true),$rows_header->valid_start);	
						?>
					</div>
				</div>
				<div class="col-sm-6">
					<div class="form-group">
						<label class="control-label">Valid End <span class="text-red"> *</span></label>
						<?php
							echo form_input(array('id'=>'survey_valid_end','name'=>'survey_valid_end','class'=>'form-control input-sm','autocomplete'=>'off','readOnly'=>true),$rows_header->valid_end);						
						?>
					</div>
				</div>				
			</div>				
		</div>
		<div class="box-body">
			<div class="box box-danger">
				<div class="box-header">
					<h4 class="box-title"><i class="fa fa-list-alt"></i> DETAIL QUESTION</h4>
					
				</div>
				<div class='box-body'>
					<table class="table table-striped table-bordered" id="my-grid" style="overflow-x:scroll !important;">
						<thead>
							<tr class="bg-navy-active">
								<th class="text-center">No</th>
								<th class="text-center">Question</th>
								<th class="text-center">Type</th>
								<th class="text-center">Answer</th>														
							</tr>
						</thead>
						<tbody id="list_detail_question">
							<?php
							if($rows_detail){
								$Arr_Letter	= array(1=>'a','b','c','d','e','f','g','h','i','j');
								foreach($rows_detail as $keyDet=>$valDet){
									$Quest_No		= $valDet->question_no;
									$Question		= $valDet->question;
									$Quest_Type		= $valDet->question_type;
									$Quest_Answer	= $valDet->choice_answer;
									
									$Text_Question	= $Question;
									if(strtolower($Quest_Type)== 'multiple choice' || strtolower($Quest_Type)== 'likert scale'){
										$Urut_Loop	= 0;
										for($x=1;$x<=10;$x++){
											$Lable_Check	= 'choice_'.$x;
											if(!empty($valDet->$Lable_Check) && $valDet->$Lable_Check !=='-'){
												$Urut_Loop++;
												
												$Lable_Urut	= $Urut_Loop;
												
												if(strtolower($Quest_Type)== 'multiple choice'){
													$Lable_Urut	= $Arr_Letter[$Urut_Loop];
												}
												$Val_Choice	= $valDet->$Lable_Check;
												
												$Text_Question	.='<br>'.$Lable_Urut.'. '.$Val_Choice;
											}
										}
									}
									
									echo'
									<tr>
										<td class="text-center">'.$Quest_No.'</td>
										<td class="text-left text-wrap">'.$Text_Question.'</td>
										<td class="text-center">'.$Quest_Type.'</td>
										<td class="text-left text-wrap">'.$Quest_Answer.'</td>
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
		
		<div class="box-body">
			<div class="box box-primary">
				<div class="box-header">
					<h4 class="box-title"><i class="fa fa-list-alt"></i> DETAIL RESPONDENT</h4>
					
				</div>
				<div class='box-body'>
					<table class="table table-striped table-bordered" id="my-grid2" style="overflow-x:scroll !important;">
						<thead>
							<tr class="bg-navy-active">
								<th class="text-center">No</th>
								<th class="text-center">Respondent</th>
								<th class="text-center">Date Input</th>
								<th class="text-center">Customer</th>
								<th class="text-center">Result</th>
							</tr>
						</thead>
						<tbody id="list_detail_answer">
							<?php
							if($rows_answer){
								$Urut = 0;
								foreach($rows_answer as $keyDet=>$valDet){
									$Urut++;
									$Code_Answer	= $valDet->code_answer;
									$Respondent		= $valDet->user_answer;
									$Date_Answer	= date('d-m-Y',strtotime($valDet->date_answer));
									$Code_Customer	= $valDet->customer_id;
									$Name_Customer	= '';
									$rows_Customer	= $this->db->get_where('customers',array('id'=>$Code_Customer))->row();
									if($rows_Customer){
										$Name_Customer	= $rows_Customer->name;
									}
									
									
									echo'
									<tr>
										<td class="text-center">'.$Urut.'</td>
										<td class="text-center">'.$Respondent.'</td>
										<td class="text-center">'.$Date_Answer.'</td>
										<td class="text-left text-wrap">'.$Name_Customer.'</td>
										<td class="text-center">
											<button type="button" class="btn btn-sm" style="background-color:#03506F !important; color:#fff !important;" onClick = "ActionPreview({code:\''.$Code_Answer.'\',action :\'preview_detail_question_answer\',title:\'DETAIL ANSWER\'});" title="DETAIL BAST ANSWER"> <i class="fa fa-search"></i> </button>
										</td>
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
		<div class='box-footer text-center'>
			<button type="button" class="btn btn-md btn-danger" id="btn-kembali"> <i class="fa fa-long-arrow-left"></i> BACK </button>';
			
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
	.text-center{
		text-align : center !important;
		vertical-align	: middle !important;
	}
	.text-left{
		text-align : left !important;
		vertical-align	: middle !important;
	}
	.text-wrap{
		word-wrap : break-word !important;
	}
	
</style>
<script>
	var base_url			= '<?php echo site_url(); ?>';
	var active_controller	= '<?php echo($this->uri->segment(1)); ?>';
	$(document).ready(function(){
		$('#loader_proses_save').hide();		
		$('#my-grid2').DataTable();
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
	$(document).on('click','#btn-kembali',(e)=>{
		loading_spinner();
		window.location.href =  base_url+'/'+active_controller;
	});
	
	
	
	$(document).on('click','#btn_save_cancel_survey', async(e)=>{
		e.preventDefault();
		$('#btn-kembali, #btn_save_cancel_survey').prop('disabled',true);
		
		let Cancel_Reason	= $('#cancel_reason').val();
	
		
		const ValueCheck	= {
			'alasan':{'nilai':Cancel_Reason,'error':'Empty Cancel Reason. Please input reason first..'}
		};
		
	
		
		try{			
			const ResultCheck		= await GeneralCheckEmptyValue(ValueCheck);
			const ResultConfirm		= await GeneralShowConfirmSave();
			const formData 			= new FormData($('#form_proses_survey')[0]);
			const ParamProcess	= {
				'action'		: 'save_cancel_customer_survey',
				'parameter'		: formData,
				'loader'		: 'loader_proses_save'
			};			
			const Hasil_Bro			= await GeneralAjaxProcessData(ParamProcess);
			
			if(Hasil_Bro.status == '1'){
				GeneralShowMessageError('success',Hasil_Bro.pesan);
				window.location.href	= base_url+'/'+active_controller;
			}else{
				GeneralShowMessageError('error',Hasil_Bro.pesan);
				$('#btn-kembali, #btn_save_cancel_survey').prop('disabled',false);
				return false;
			}			
		}catch(error){
			GeneralShowMessageError('error',error.message);
			$('#btn-kembali, #btn_save_cancel_survey').prop('disabled',false);
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
