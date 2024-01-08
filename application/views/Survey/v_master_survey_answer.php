
<form action="#" method="POST" id="form_proses_driver_spk" enctype="multipart/form-data">
	<div class="box box-warning">
		<br/>
		<div class="pull-right">
			<a class="word-export btn btn-primary" href="javascript:void(0)" onclick="exportHTML();"><i class="fa fa-file-word-o"></i> Export to Word </a> 
			<a class="word-excel btn btn-success" href="javascript:void(0)" onclick="exportExcel();"><i class="fa fa-file-excel-o"></i> Export to Excel </a> 
		</div>
		<div class="box-body">			
			
			<?php
			echo form_input(array('id'=>'code_answer','name'=>'code_answer','type'=>'hidden'),$rows_answer->code_answer);
			if($rows_detail){
				$Arr_Lable	= array(1=>'a','b','c','d','e','f','g','h','i','j');
				foreach($rows_detail as $KeyDetail=>$valDetail){
					$Code_Pertanyaan	= $valDetail->code_detail;
					$Urut_Pertanyaan	= $valDetail->question_no;
					$Text_Pertanyaan	= $valDetail->question;
					$Type_Pertanyaan 	= $valDetail->question_type;
					$Jawab_Original 	= $valDetail->choice_answer;
					
					
					$Jawab_Pertanyaan	= '';
					$Jawab_Descr		= '';
					$rows_Jawaban		= $this->db->get_where('crm_survey_answer_details',array('code_answer'=>$rows_answer->code_answer,'code_question'=>$Code_Pertanyaan))->row();
					if($rows_Jawaban){
						$Jawab_Pertanyaan	= $rows_Jawaban->answer;
						$Jawab_Descr		= $rows_Jawaban->descr;
					}
					echo '
					<div class="row">
						<div class="col-sm-12">
							<label class="control-label text-left">'.$Urut_Pertanyaan.'. <b><i>'.$Text_Pertanyaan.'</i></b></label>
							'.form_input(array('name'=>'detAnswer['.$Urut_Pertanyaan.'][question]','id'=>'pertanyaan_'.$Urut_Pertanyaan,'type'=>'hidden'),$Text_Pertanyaan).'
							'.form_input(array('name'=>'detAnswer['.$Urut_Pertanyaan.'][code_question]','id'=>'kode_pertanyaan_'.$Urut_Pertanyaan,'type'=>'hidden'),$Code_Pertanyaan).'
							'.form_input(array('name'=>'detAnswer['.$Urut_Pertanyaan.'][type_question]','id'=>'tipe_pertanyaan_'.$Urut_Pertanyaan,'type'=>'hidden'),$Type_Pertanyaan).'
						</div>
					</div>
					';
					
					if(strtolower($Type_Pertanyaan) == 'essay'){
						echo'
						<div class="row">
							<div class="col-sm-12">
								<div class="form-group">
									<label class="control-label text-left"><b><i>Answer : </i></b></label>
									'.form_textarea(array('id'=>'jawab_pertanyaan_'.$Urut_Pertanyaan,'name'=>'detAnswer['.$Urut_Pertanyaan.'][answer_question]','class'=>'form-control','readOnly'=>true,'cols'=>100,'rows'=>2),$Jawab_Pertanyaan).'
								</div>
							</div>
						</div>
						';
					}else{
						$Loop_Pertanyaan	= $Urut_Choice = 0;
						for($z=1;$z<=10;$z++){
							$Lable_Tanya	= 'choice_'.$z;
							if(!empty($valDetail->$Lable_Tanya) && $valDetail->$Lable_Tanya !=='-'){
								$Loop_Pertanyaan++;
								$Urut_Choice++;
								if($Loop_Pertanyaan == 1){
									echo'
									<div class="row">
									';
								}
								$Lable_Urut	= $Urut_Choice;
								
								if(strtolower($Type_Pertanyaan)== 'multiple choice'){
									$Lable_Urut	= $Arr_Lable[$Urut_Choice];
								}
								$Val_Pertanyaan	= $valDetail->$Lable_Tanya;
								$Status_Checked	= '';
								$Class_Answer	= '';
								if($Jawab_Pertanyaan){
									if(strtolower($Jawab_Pertanyaan) == strtolower($Val_Pertanyaan) && strtolower($Jawab_Pertanyaan) == strtolower($Jawab_Original)){
										$Class_Answer		= 'text-green';
									}else if(strtolower($Jawab_Pertanyaan) == strtolower($Val_Pertanyaan) && strtolower($Val_Pertanyaan) !== strtolower($Jawab_Original)){
										$Class_Answer		= 'text-red';
									}else if(strtolower($Val_Pertanyaan) == strtolower($Jawab_Original)){
										$Class_Answer		= 'text-green';
									}
									
									if(strtolower($Jawab_Pertanyaan) == strtolower($Val_Pertanyaan)){
										$Status_Checked		= 'checked';
									}
								}else{
									if(strtolower($Val_Pertanyaan) == strtolower($Jawab_Original)){
										$Class_Answer		= 'text-green';
									}
								}
								
								
								echo'
								<div class="col-sm-3">
									<label class="d-inline">
										<div class="iradio_minimal-red" >
											<input type="radio" name="detAnswer['.$Urut_Pertanyaan.'][answer_question]" id="radio_'.$Urut_Pertanyaan.'_'.$Urut_Choice.'" class="minimal" value ="'.$Val_Pertanyaan.'" '.$Status_Checked.'>&nbsp;'.$Lable_Urut.'. <span class="'.$Class_Answer.'">'.$Val_Pertanyaan.'</span>
										</div>
										
									</label>
								</div>
								';
								
								if($Loop_Pertanyaan >= 4){
									echo'
									</div>
									';
									$Loop_Pertanyaan = 0;
								}
							}
						}
						if($Loop_Pertanyaan > 0 && $Loop_Pertanyaan < 4){
							$Selisih	= 4 - $Loop_Pertanyaan;
							for($xy = 1;$xy<=$Selisih;$xy++){
								echo'
								<div class="col-sm-3">&nbsp;</div>
								';
							}
							echo'
							</div>
							';
						}
						
						if(strtolower($Type_Pertanyaan)== 'likert scale' && !empty($Jawab_Descr)){
							echo'
							<div class="row">
								<div class="col-sm-12">
									<div class="form-group">
										<label class="control-label text-left"><b><i>Description : </i><b></label>
										'.form_textarea(array('id'=>'descr_pertanyaan_'.$Urut_Pertanyaan,'name'=>'detAnswer['.$Urut_Pertanyaan.'][descr_question]','class'=>'form-control','readOnly'=>true,'cols'=>100,'rows'=>2),$Jawab_Descr).'
									</div>
								</div>
							</div>
							';
						}
					}
					echo'
					<div class="row">
						<div class="col-sm-12">&nbsp;</div>
					</div>
					';
				}
			}
			?>
			
			
		</div>	

		<div class="" id="source-html" style="display:none;">
			<?php
			echo form_input(array('id'=>'code_answer2','name'=>'code_answer2','type'=>'hidden'),$rows_answer->code_answer);
			if($rows_detail){
				$Arr_Lable	= array(1=>'a','b','c','d','e','f','g','h','i','j');
				foreach($rows_detail as $KeyDetail=>$valDetail){
					$Code_Pertanyaan	= $valDetail->code_detail;
					$Urut_Pertanyaan	= $valDetail->question_no;
					$Text_Pertanyaan	= $valDetail->question;
					$Type_Pertanyaan 	= $valDetail->question_type;
					$Jawab_Original 	= $valDetail->choice_answer;
					
					
					$Jawab_Pertanyaan	= '';
					$Jawab_Descr		= '';
					$rows_Jawaban		= $this->db->get_where('crm_survey_answer_details',array('code_answer'=>$rows_answer->code_answer,'code_question'=>$Code_Pertanyaan))->row();
					if($rows_Jawaban){
						$Jawab_Pertanyaan	= $rows_Jawaban->answer;
						$Jawab_Descr		= $rows_Jawaban->descr;
					}
					echo '
					<div class="row">
						<div class="col-sm-12">
							<label class="control-label text-left">'.$Urut_Pertanyaan.'. <b><i>'.$Text_Pertanyaan.'</i></b></label>
							'.form_input(array('name'=>'detAnswer2['.$Urut_Pertanyaan.'][question]','id'=>'pertanyaan2_'.$Urut_Pertanyaan,'type'=>'hidden'),$Text_Pertanyaan).'
							'.form_input(array('name'=>'detAnswer2['.$Urut_Pertanyaan.'][code_question]','id'=>'kode_pertanyaan2_'.$Urut_Pertanyaan,'type'=>'hidden'),$Code_Pertanyaan).'
							'.form_input(array('name'=>'detAnswer2['.$Urut_Pertanyaan.'][type_question]','id'=>'tipe_pertanyaan2_'.$Urut_Pertanyaan,'type'=>'hidden'),$Type_Pertanyaan).'
						</div>
					</div>
					';
					
					if(strtolower($Type_Pertanyaan) == 'essay'){
						echo'
						<div class="row">
							<div class="col-sm-12">
								<div class="form-group">
									<label class="control-label text-left"><b><i>Answer : </i></b></label>
									'.form_textarea(array('id'=>'jawab_pertanyaan2_'.$Urut_Pertanyaan,'name'=>'detAnswer2['.$Urut_Pertanyaan.'][answer_question]','class'=>'form-control','readOnly'=>true,'cols'=>100,'rows'=>2),$Jawab_Pertanyaan).'
								</div>
							</div>
						</div>
						';
					}else{
						$Loop_Pertanyaan	= $Urut_Choice = 0;
						for($z=1;$z<=10;$z++){
							$Lable_Tanya	= 'choice_'.$z;
							if(!empty($valDetail->$Lable_Tanya) && $valDetail->$Lable_Tanya !=='-'){
								$Loop_Pertanyaan++;
								$Urut_Choice++;
								if($Loop_Pertanyaan == 1){
									echo'
									<div class="row">
									';
								}
								$Lable_Urut	= $Urut_Choice;
								
								if(strtolower($Type_Pertanyaan)== 'multiple choice'){
									$Lable_Urut	= $Arr_Lable[$Urut_Choice];
								}
								$Val_Pertanyaan	= $valDetail->$Lable_Tanya;
								$Status_Checked	= '';
								$Class_Answer	= '';
								if($Jawab_Pertanyaan){
									if(strtolower($Jawab_Pertanyaan) == strtolower($Val_Pertanyaan) && strtolower($Jawab_Pertanyaan) == strtolower($Jawab_Original)){
										$Class_Answer		= 'text-green';
									}else if(strtolower($Jawab_Pertanyaan) == strtolower($Val_Pertanyaan) && strtolower($Val_Pertanyaan) !== strtolower($Jawab_Original)){
										$Class_Answer		= 'text-red';
									}else if(strtolower($Val_Pertanyaan) == strtolower($Jawab_Original)){
										$Class_Answer		= 'text-green';
									}
									
									if(strtolower($Jawab_Pertanyaan) == strtolower($Val_Pertanyaan)){
										$Status_Checked		= 'checked';
									}
								}else{
									if(strtolower($Val_Pertanyaan) == strtolower($Jawab_Original)){
										$Class_Answer		= 'text-green';
									}
								}
								
								
								echo'
								<div class="col-sm-3">
									<label class="d-inline">
										<div class="iradio_minimal-red" >
										<b><i>Answer : </i></b>'.$Jawab_Pertanyaan.'
										</div>
										
									</label>
								</div>
								';
								
								if($Loop_Pertanyaan >= 4){
									echo'
									</div>
									';
									$Loop_Pertanyaan = 0;
								}
							}
							break;
						}
						if($Loop_Pertanyaan > 0 && $Loop_Pertanyaan < 4){
							$Selisih	= 4 - $Loop_Pertanyaan;
							for($xy = 1;$xy<=$Selisih;$xy++){
								echo'
								<div class="col-sm-3">&nbsp;</div>
								';
							}
							echo'
							</div>
							';
						}
						
						if(strtolower($Type_Pertanyaan)== 'likert scale' && !empty($Jawab_Descr)){
							echo'
							<div class="row">
								<div class="col-sm-12">
									<div class="form-group">
										<label class="control-label text-left"><b><i>Description : </i><b></label>
										'.form_textarea(array('id'=>'descr_pertanyaan2_'.$Urut_Pertanyaan,'name'=>'detAnswer2['.$Urut_Pertanyaan.'][descr_question]','class'=>'form-control','readOnly'=>true,'cols'=>100,'rows'=>2),$Jawab_Descr).'
									</div>
								</div>
							</div>
							';
						}
					}
					echo'
					<div class="row">
						<div class="col-sm-12">&nbsp;</div>
					</div>
					';
				}
			}
			?>
			
			
		</div>
		
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
	.d-inline{
		display : inline !important;
	}
	
</style>
<script>
	$(document).ready(function(){
		$('#loader_proses_save').hide();
	});
</script>

<script type="text/javascript">
	var base_url = '<?php echo base_url(); ?>';

	function exportHTML(){ 
		var header = "<html xmlns:o='urn:schemas-microsoft-com:office:office' "+
				"xmlns:w='urn:schemas-microsoft-com:office:word' "+
				"xmlns='http://www.w3.org/TR/REC-html40'>"+
				"<head><meta charset='utf-8'><title>Data Survey Customer</title></head><body>";
		var footer = "</body></html>";
		var sourceHTML = header+document.getElementById("source-html").innerHTML+footer;
		
		var source = 'data:application/vnd.ms-word;charset=utf-8,' + encodeURIComponent(sourceHTML);
		var fileDownload = document.createElement("a");
		document.body.appendChild(fileDownload);
		fileDownload.href = source;
		fileDownload.download = 'Data Survey Customer.doc';
		fileDownload.click();
		document.body.removeChild(fileDownload);
	}

	function exportExcel(){
		var CodeAnswer = $('#code_answer').val();
		var Links = base_url + active_controller + '/exportExcelAnswer/' + CodeAnswer;
		//alert(CodeAnswer);
		window.open(Links, '_blank');
	}
</script> 
