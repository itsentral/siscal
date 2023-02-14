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
							echo form_input(array('id'=>'survey_title','name'=>'survey_title','class'=>'form-control input-sm text-up','autocomplete'=>'off'));	
						?>
					</div>
				</div>
				<div class="col-sm-6">
					<div class="form-group">
						<label class="control-label">Description Survey <span class="text-red"> *</span></label>
						<?php
							echo form_textarea(array('id'=>'survey_descr','name'=>'survey_descr','class'=>'form-control input-sm text-up','cols'=>75,'rows'=>2,'autocomplete'=>'off'));								
						?>
					</div>
				</div>				
			</div>
			<div class='row'>
				<div class="col-sm-6">
					<div class="form-group">
						<label class="control-label">Valid Start <span class="text-red"> *</span></label>
						<?php
							echo form_input(array('id'=>'survey_valid_start','name'=>'survey_valid_start','class'=>'form-control input-sm','autocomplete'=>'off','readOnly'=>true));	
						?>
					</div>
				</div>
				<div class="col-sm-6">
					<div class="form-group">
						<label class="control-label">Valid End <span class="text-red"> *</span></label>
						<?php
							echo form_input(array('id'=>'survey_valid_end','name'=>'survey_valid_end','class'=>'form-control input-sm','autocomplete'=>'off','readOnly'=>true));						
						?>
					</div>
				</div>				
			</div>						
		</div>
		<div class="box-body">
			<div class="box box-danger">
				<div class="box-header">
					<h4 class="box-title"><i class="fa fa-list-alt"></i> DETAIL QUESTION</h4>
					<div class="box-tool pull-right">
						<button type="button" class="btn btn-sm btn-primary" id="btn_add_question" title="ADD QUESTION"> ADD QUESTION <i class="fa fa-plus"></i> </button>
					</div>
				</div>
				<div class='box-body'>
					<table class="table table-striped table-bordered" id="my-grid" style="overflow-x:scroll !important;">
						<thead>
							<tr class="bg-navy-active">
								<th class="text-center">Question</th>
								<th class="text-center">Type</th>
								<th class="text-center">Answer</th>
								<th class="text-center">Action</th>								
							</tr>
						</thead>
						<tbody id="list_detail_question">
							
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
			&nbsp;&nbsp;&nbsp;<button type="button" id="btn_save_add_survey" class="btn btn-md" style="background-color:#37474f; color:white;vertical-align:middle !important;" title="SAVE SURVEY"> SAVE SURVEY <i class="fa fa-long-arrow-right" style="width:40px;"></i> </button>';
			
		
		</div>
		
	</div>
</form>
<div class="modal fade" id="MyModalView" tabindex="-1" role="dialog" aria-labelledby="MyModal" data-backdrop="static">
	<div class="modal-dialog" role="document" style="min-width:30% !important;">
		 <div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="MyModalTitle">ADD QUESTION</h5>
				<button class="close" data-dismiss="modal" aria-label="close" id="btn-modal-close">
					<span aria-hidden="true"><i class="fa fa-close"></i></span>
				</button>
			</div>
			<div class="modal-body" id="MyModalDetail">
				<div class='row'>
					<div class="col-sm-12">
						<div class="form-group">
							<label class="control-label">Question <span class="text-red"> *</span></label>
							<?php
								echo form_textarea(array('id'=>'add_question','name'=>'add_question','class'=>'form-control input-sm text-up','cols'=>75,'rows'=>2,'autocomplete'=>'off'));
							?>
						</div>
					</div>
				</div>
				<div class='row'>
					<div class="col-sm-12">
						<div class="form-group">
							<label class="control-label">Question Type <span class="text-red"> *</span></label>
							
							<select name="type_question" id="type_question" class="form-control select2">
								<option value="">- OPTION LIST -</option>
								<option value="MULTIPLE CHOICE"> MULTIPLE CHOICE </option>
								<option value="ESSAY"> ESSAY </option>
								<option value="LIKERT SCALE"> LIKERT SCALE </option>								
							</select>
						
						</div>
					</div>				
				</div>
				<div id='div_quest_det'>
				
				</div>
				
			</div>
			<div class="modal-footer text-center">
				<button type="button" class="btn btn-md" style="color :#fff !important; background-color :#03506F !important;" id="btn_save_add_question" title="SAVE QUESTION"> SAVE QUESTION <i class="fa fa-save"></i> </button>
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
		$('.chosen-select').chosen();
		$("#survey_valid_start, #survey_valid_end").datepicker({
			changeMonth: true,
			changeYear: true,
			dateFormat: 'yy-mm-dd',
			autoclose: true,
			orientation: 'bottom',
			todayHighlight: true,
			minDate : '+0d'
		});
	});
	
		
	$(document).on('click','#btn-kembali',(e)=>{
		loading_spinner();
		window.location.href =  base_url+'/'+active_controller;
	});
	
	$(document).on('click','#btn_add_question',()=>{
		$('#add_question').val('');
		$('#type_question').val('').trigger('chosen:updated');
		$('#div_quest_det').html('');
		$("#MyModalView").modal('show');
		
	});
	
	$(document).on('change','#type_question',()=>{
		$('#div_quest_det').html('');
		let Tipe_Question	= $('#type_question').val();
		let Template		='';
		if(Tipe_Question == 'MULTIPLE CHOICE'){
			for(x=1;x<=4;x++){
				Template	+='<div class="row">'+
								'<div class="col-sm-12">'+
									'<div class="form-group">'+
										'<label class="control-label">Choice '+x+' <span class="text-red"> *</span></label>'+
										'<textarea id="choice_question_'+x+'" name="choice_question_'+x+'" class="form-control input-sm text-up" cols="100" rows="2" autocomplete="off"></textarea>'+
									'</div>'+
								'</div>'+
							 '</div>';
									
			}
			Template	+='<div class="row">'+
								'<div class="col-sm-12">'+
									'<div class="form-group">'+
										'<label class="control-label">Answer <span class="text-red"> *</span></label>'+
										'<textarea id="answer_question" name="answer_question" class="form-control input-sm text-up" cols="100" rows="2" autocomplete="off"></textarea>'+
									'</div>'+
								'</div>'+
							 '</div>';
		}else if(Tipe_Question == 'LIKERT SCALE'){
			for(x=1;x<=5;x++){
				if(x <= 3){
					Template	+='<div class="row scale_likert" id="div_scale_'+x+'">'+
									'<div class="col-sm-12">'+
										'<div class="form-group">'+
											'<label class="control-label">Scale '+x+' <span class="text-red"> *</span></label>'+	
											 '<input type="text" id="scale_question_'+x+'" name="scale_question_'+x+'" class="form-control input-sm text-up" autocomplete="off">'+
										'</div>'+
									'</div>'+								
								 '</div>';
				}else{
					Template	+='<div class="row scale_likert" id="div_scale_'+x+'">'+
									'<div class="col-sm-12">'+
										'<div class="form-group">'+
											'<label class="control-label">Scale '+x+' <span class="text-red"> *</span></label>'+	
											'<div class="input-group">'+
												 '<div class="input-group-btn">'+
													  '<button type="button" id="btn_delete_question" class="btn btn-sm btn-danger" title="DELETE LIKERT SCALE" onClick="DeleteQuestionScale('+x+');"> <i class="fa fa-trash-o"></i> </button>'+
												 '</div>'+
												 '<input type="text" id="scale_question_'+x+'" name="scale_question_'+x+'" class="form-control input-sm text-up" autocomplete="off">'+
											'</div>'+
										'</div>'+
									'</div>'+								
								 '</div>';

				}
									
			}
		}
		
		$('#div_quest_det').html(Template);
	});
	
	const DeleteQuestionScale =(Urut)=>{
		$('#div_scale_'+Urut).remove();
	}
	
	const DeleteQuestionChosen =(Urut)=>{
		$('#list_detail_question #tr_urut_'+Urut).remove();
	}
	
	$(document).on('click','#btn_save_add_question',(e)=>{
		e.preventDefault();
		$('#btn-modal-close, #btn_save_add_question').prop('disabled',true);
		let Temp_Hidden	= '';
		let Temp_Answer = '';
		let Urut_row	= 1;
		let Jum_Row		= $('#list_detail_question').find('tr').length;
		if(parseInt(Jum_Row) > 0){
			let Code_Last_Row	= $('#list_detail_question tr:last').attr('id');
			const Split_Last	= Code_Last_Row.split('_');
			Urut_row			= parseInt(Split_Last[2]) + 1;
		}
		
		let Chosen_Question		= $('#add_question').val();
		let Chosen_Type_Quest	= $('#type_question').val();
		if(Chosen_Question == '' || Chosen_Question == null || Chosen_Question == '-'){
			GeneralShowMessageError('error','Empty Question. Please input question first..');
			$('#btn-modal-close, #btn_save_add_question').prop('disabled',false);
			return false;
		}
		
		let Temp_Question = Chosen_Question;
		
		if(Chosen_Type_Quest == '' || Chosen_Type_Quest == null){
			GeneralShowMessageError('error','Empty Type Question. Please choose type question first..');
			$('#btn-modal-close, #btn_save_add_question').prop('disabled',false);
			return false;
		}
		
		Temp_Hidden	+='<input type="hidden" name="detDetail['+Urut_row+'][question]" id="x_question_'+Urut_row+'" value="'+Chosen_Question.toUpperCase()+'">'+
					  '<input type="hidden" name="detDetail['+Urut_row+'][question_type]" id="x_question_type_'+Urut_row+'" value="'+Chosen_Type_Quest+'">';
		
		if(Chosen_Type_Quest.toLowerCase() == 'multiple choice'){
			let Multiple_1	= $('#choice_question_1').val();
			let Multiple_2	= $('#choice_question_2').val();
			let Multiple_3	= $('#choice_question_3').val();
			let Multiple_4	= $('#choice_question_4').val();
			let Multi_Answer= $('#answer_question').val();
			
			if(Multiple_1 == '' || Multiple_1 == null || Multiple_1 == '-'){
				GeneralShowMessageError('error','Empty Multiple Choice 1. Please input multiple choice 1 first..');
				$('#btn-modal-close, #btn_save_add_question').prop('disabled',false);
				return false;
			}
			
			if(Multiple_2 == '' || Multiple_2 == null || Multiple_2 == '-'){
				GeneralShowMessageError('error','Empty Multiple Choice 2. Please input multiple choice 2 first..');
				$('#btn-modal-close, #btn_save_add_question').prop('disabled',false);
				return false;
			}
			
			if(Multiple_3 == '' || Multiple_3 == null || Multiple_3 == '-'){
				GeneralShowMessageError('error','Empty Multiple Choice 3. Please input multiple choice 3 first..');
				$('#btn-modal-close, #btn_save_add_question').prop('disabled',false);
				return false;
			}
			
			if(Multiple_4 == '' || Multiple_4 == null || Multiple_4 == '-'){
				GeneralShowMessageError('error','Empty Multiple Choice 4. Please input multiple choice 4 first..');
				$('#btn-modal-close, #btn_save_add_question').prop('disabled',false);
				return false;
			}
			
			if(Multi_Answer == '' || Multi_Answer == null || Multi_Answer == '-'){
				GeneralShowMessageError('error','Empty Multiple Choice Answer. Please input multiple choice answer first..');
				$('#btn-modal-close, #btn_save_add_question').prop('disabled',false);
				return false;
			}
			
			let OK_Correct	= 0;
			if(Multiple_1.toLowerCase() == Multi_Answer.toLowerCase() || Multiple_2.toLowerCase() == Multi_Answer.toLowerCase() || Multiple_3.toLowerCase() == Multi_Answer.toLowerCase() || Multiple_4.toLowerCase() == Multi_Answer.toLowerCase()){
				OK_Correct	= 1;
			}
			
			if(OK_Correct == 0){
				GeneralShowMessageError('error','Incorrect Multiple Choice Answer. Please input correct answer first..');
				$('#btn-modal-close, #btn_save_add_question').prop('disabled',false);
				return false;
			}
			Temp_Answer		= Multi_Answer.toUpperCase();
			Temp_Question +='<br>a. '+Multiple_1.toUpperCase()+'<br>b. '+Multiple_2.toUpperCase()+'<br>c. '+Multiple_3.toUpperCase()+'<br>d. '+Multiple_4.toUpperCase();
			Temp_Hidden	+='<input type="hidden" name="detDetail['+Urut_row+'][choice_1]" id="x_choice1_'+Urut_row+'" value="'+Multiple_1.toUpperCase()+'">'+
						  '<input type="hidden" name="detDetail['+Urut_row+'][choice_2]" id="x_choice2_'+Urut_row+'" value="'+Multiple_2.toUpperCase()+'">'+
						  '<input type="hidden" name="detDetail['+Urut_row+'][choice_3]" id="x_choice3_'+Urut_row+'" value="'+Multiple_3.toUpperCase()+'">'+
						  '<input type="hidden" name="detDetail['+Urut_row+'][choice_4]" id="x_choice4_'+Urut_row+'" value="'+Multiple_4.toUpperCase()+'">'+
						  '<input type="hidden" name="detDetail['+Urut_row+'][choice_answer]" id="x_choice_answer_'+Urut_row+'" value="'+Multi_Answer.toUpperCase()+'">';
			
		}else if(Chosen_Type_Quest.toLowerCase() == 'likert scale'){
			let intS	= 0;
			let intC	= 0;
			$('#div_quest_det div.scale_likert').each(function(){
				intS++;
				let Code_Scale		= $(this).attr('id');
				const Split_Scale	= Code_Scale.split('_');
				let Urut_Scale		= Split_Scale[2];
				
				let Scale_Question	= $('#scale_question_'+Urut_Scale).val();
				if(Scale_Question == '' || Scale_Question == null || Scale_Question == '-'){
					intC++;
				}
				Temp_Question +='<br>'+intS+'. '+Scale_Question.toUpperCase();
				Temp_Hidden	+='<input type="hidden" name="detDetail['+Urut_row+'][choice_'+intS+']" id="x_choice'+intS+'_'+Urut_row+'" value="'+Scale_Question.toUpperCase()+'">';
			});
			
			if(intC > 0){
				GeneralShowMessageError('error','Empty Likert Scale Description. Please input scale description first..');
				$('#btn-modal-close, #btn_save_add_question').prop('disabled',false);
				return false;
			}
		}
		
		let Template_Add = '<tr id="tr_urut_'+Urut_row+'">'+Temp_Hidden+
								'<td class="text-left">'+Temp_Question+'</td>'+
								'<td class="text-center">'+Chosen_Type_Quest+'</td>'+
								'<td class="text-left">'+Temp_Answer+'</td>'+
								'<td class="text-center"><button type="button" class="btn btn-sm btn-danger" title="DELETE QUESTION" onClick="DeleteQuestionChosen('+Urut_row+');"> <i class="fa fa-trash-o"></i> </button></td>'+
						   '</tr>';
		$('#list_detail_question').append(Template_Add);
		$('#btn-modal-close, #btn_save_add_question').prop('disabled',false);
		$('#add_question').val('');
		$('#type_question').val('').trigger('chosen:updated');
		$('#div_quest_det').html('');
		$("#MyModalView").modal('hide');
	});
	
	
	
	$(document).on('click','#btn_save_add_survey', async(e)=>{
		e.preventDefault();
		$('#btn-kembali, #btn_save_add_survey').prop('disabled',true);
		
		let Title_Survey 	= $('#survey_title').val();
		let Descr_Survey	= $('#survey_descr').val();
		let Start_Survey 	= $('#survey_valid_start').val();
		let End_Survey 		= $('#survey_valid_end').val();
		let Num_Quest		= $('#list_detail_question').find('tr').length;
	
		
		const ValueCheck	= {
			'judul':{'nilai':Title_Survey,'error':'Empty Survey Title. Please input title first..'},
			'keterangan':{'nilai':Descr_Survey,'error':'Empty Survey Description. Please input description first..'},
			'valstart':{'nilai':Start_Survey,'error':'Empty Valid Start Date Survey. Please input start date first..'},
			'valend':{'nilai':End_Survey,'error':'Empty Valid End Date Survey. Please input end date first..'}
		};
		
		
		if(parseInt(Num_Quest) <= 0){
			let rowsChosen		= '';
			ValueCheck['rows_quest']	={'nilai':rowsChosen,'error':'No question was created. Please add question at least one record..'};
			
		}
		
		if(End_Survey <= Start_Survey){
			ValueCheck['valid_date']	={'nilai':'','error':'Incorrect valid date survey....'};
		}
		
		try{			
			const ResultCheck		= await GeneralCheckEmptyValue(ValueCheck);
			const ResultConfirm		= await GeneralShowConfirmSave();
			const formData 			= new FormData($('#form_proses_survey')[0]);
			const ParamProcess	= {
				'action'		: 'save_create_customer_survey',
				'parameter'		: formData,
				'loader'		: 'loader_proses_save'
			};			
			const Hasil_Bro			= await GeneralAjaxProcessData(ParamProcess);
			
			if(Hasil_Bro.status == '1'){
				GeneralShowMessageError('success',Hasil_Bro.pesan);
				window.location.href	= base_url+'/'+active_controller;
			}else{
				GeneralShowMessageError('error',Hasil_Bro.pesan);
				$('#btn-kembali, #btn_save_add_survey').prop('disabled',false);
				return false;
			}			
		}catch(error){
			GeneralShowMessageError('error',error.message);
			$('#btn-kembali, #btn_save_add_survey').prop('disabled',false);
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
