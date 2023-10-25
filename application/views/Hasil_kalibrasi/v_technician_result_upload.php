
<form action="#" method="POST" id="form-proses-upload" enctype="multipart/form-data">
	<div class="box box-warning">
		
		<div class="box-body">
			<div class="row">
				<div class="col-sm-12 text-center sub-heading" style="color:white;">
					<h5><?php echo $title;?></h5>
				</div>
				
			</div>
			<?php
			if(empty($rows_detail)){
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
								echo form_input(array('id'=>'no_so','name'=>'no_so','class'=>'form-control input-sm','readOnly'=>true),$rows_header[0]->no_so);	
								echo form_input(array('id'=>'code_trans','name'=>'code_trans','type'=>'hidden'),$rows_header[0]->id);
								echo form_input(array('id'=>'code_detail','name'=>'code_detail','type'=>'hidden'),$rows_detail[0]->id);
								echo form_input(array('id'=>'code_so','name'=>'code_so','type'=>'hidden'),$rows_header[0]->letter_order_id);
								echo form_input(array('id'=>'subcon','name'=>'subcon','type'=>'hidden'),$rows_header[0]->subcon);
								echo form_input(array('id'=>'code_back','name'=>'code_back','type'=>'hidden'),$Code_Back);
							?>
						</div>
					</div>
					<div class="col-sm-6">
						<div class="form-group">
							<label class="control-label">SO Date</label>
							<?php
								echo form_input(array('id'=>'tgl_so','name'=>'tgl_so','class'=>'form-control input-sm','readOnly'=>true),date('d-m-Y',strtotime($rows_header[0]->tgl_so)));								
							?>
						</div>
					</div>				
				</div>
				<div class='row'>
					<div class="col-sm-6">
						<div class="form-group">
							<label class="control-label">Quotation</label>
							<?php
								echo form_input(array('id'=>'nomor_quotation','name'=>'nomor_quotation','class'=>'form-control input-sm','readOnly'=>true),$rows_header[0]->quotation_nomor);						
							?>
						</div>
					</div>
					<div class="col-sm-6">
						<div class="form-group">
							<label class="control-label">PO No</label>
							<?php
								echo form_input(array('id'=>'pono','name'=>'pono','class'=>'form-control input-sm','readOnly'=>true),$rows_header[0]->pono);						
							?>
						</div>
					</div>				
				</div>
				<div class='row'>
					<div class="col-sm-6">
						<div class="form-group">
							<label class="control-label">Customer</label>
							<?php
								echo form_input(array('id'=>'customer','name'=>'customer','class'=>'form-control input-sm','readOnly'=>true),$rows_header[0]->customer_name);								
							?>
						</div>
					</div>
					<div class="col-sm-6">
						<div class="form-group">
							<label class="control-label">Tool Name</label>
							<?php
								echo form_input(array('id'=>'tool_name','name'=>'tool_name','class'=>'form-control input-sm','readOnly'=>true),$rows_header[0]->tool_name);						
							?>
						</div>
					</div>				
				</div>
				<div class="row">
					<div class="col-sm-12 text-center sub-heading" style="color:white;">
						<h5>UPLOAD IMAGE BEFORE CALIBRATION</h5>
					</div>
					
				</div>
				
				<div class='row'>
					<div class="col-sm-6">
						<div class="form-group">
							<label class="control-label">Allow Take Picture <span class="text-red"> *</span></label>
							<div>
								<select name="take_image" id="take_image" class="form-control chosen-select">
									<option value=""> - Select An Option - </option>
									<?php
									$Default_Val	= 'Y';
									$Arr_Option	= array('Y'=>'YES','N'=>'NO');
									foreach($Arr_Option as $keyOpt=>$valOpt){
										$Yuup	= ($keyOpt == $Default_Val)?'selected':'';
										echo'<option value="'.$keyOpt.'" '.$Yuup.'>'.$valOpt.'</option>';
									}
														
									?>
								</select>
							</div>
						</div>
					</div>
					<div class="col-sm-6" id="div_allow">
						<div class="form-group">
							<label>
								<strong>Before Calibration</strong>
							</label>
							<div>
								<button type="button" class="btn btn-sm bg-green-active" onclick="ambil_kamera('depan')">
									<i class="fa fa-camera"></i> Take Picture
								</button>
								<button type="button" class="btn btn-sm btn-danger btn-hapus-foto_depan" style="display: none;" onclick="hapus_foto('depan')">
									<i class="fa fa-trash"></i> Delete Picture
								</button>
							</div>					
							 <input type="hidden" name="pic_webcam_depan" id="pic_webcam_depan" value="">
						</div>
						<div class="form-group">
							<p class="text-center">
								<div id="result_camera_depan"></div>
								<img src="" id="pic_upload_preview_depan" style="display: none;max-width: 100%;">
							</p>
						</div>
						<div class="form-group">
							<label>
								<strong>Upload Before Cals Image</strong>
							</label>
						   <input class="form-control" type="file" name="files_depan" id="files_depan" onChange="ValidateSingleInput2(this);">					   
						</div>	
					</div>	
				</div>
				
				
			<?php
			}
			?>
										
		</div>		
		<?php
		if(!empty($rows_detail)){
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
			</div>";
			echo"<div class='footer'>";
				echo'<button type="button" id="btn-process-upload" class="btn btn-md" style="background-color:#37474f; color:white;vertical-align:middle !important;" title="SAVE PROCESS"> SAVE PROCESS <i class="fa fa-long-arrow-right" style="width:40px;"></i> </button>';
			echo"</div>";
		}
		?>
		
	</div>
</form>
<div class="modal fade" id="ContactModal" tabindex="-1" role="dialog" aria-labelledby="ContactModal">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header green accent-4">
                <h5 class="modal-title contact-title text-white" style="text-transform: uppercase;">
                    <div class="content-center mx-auto " style="text-transform: uppercase;"><h4 class="text-white"><?php echo $title;?></h4></div>
                </h5>
                <button class="close" aria-label="close" type="button" id="btn_close_contact">
                    <span aria-hidden="true"><i class="fa fa-close"></i></span>
                </button>
            </div>
            <div class="modal-body" id="contact-body"></div>
            
        </div>
    </div>
</div>
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
<script type="text/javascript">
	$(function() {		
		$('#loader_proses_save').hide();	
		
		$(".custom-file-input").on("change", function() {
			var fileName = $(this).val().split("\\").pop();
			hapus_foto();
			$(this).siblings(".custom-file-label").addClass("selected").html(fileName);
			readURL(this);
			$(".btn-hapus-foto").show();
		});
	});
	
	
	/* 
	| ------------------------|
	|	ALI ~ 2023-01-23	  |
	| ------------------------|
	*/
	function readURL(input,tipe){
        if (input.files && input.files[0]) {
            var reader = new FileReader();

            reader.onload = function(e) {
				if(tipe == 'depan'){
					$("#pic_upload_preview_depan").show();
					$('#pic_upload_preview_depan').attr('src', e.target.result);
				}else{
					$("#pic_upload_preview_back").show();
					$('#pic_upload_preview_back').attr('src', e.target.result);
				}
                
            }

            reader.readAsDataURL(input.files[0]);
        }
    }

    function ambil_kamera(jenis){
        $("#ContactModal").modal();
        $("#contact-body").html('');
        $(".contact-title").html('TAKE PICTURE');
	
        $.get(base_url+'/'+active_controller+'/ajax_ambil_kamera' ,{'kategori':jenis}, function(response){
            $("#contact-body").html(response);
        });
    }

    function hapus_foto(jenis){
		if(jenis =='depan'){
			$("#result_camera_depan").html("");
			$("#pic_webcam_depan").attr('value', '');
			$(".btn-hapus-foto_depan").hide();
			$("#pic_upload_preview_depan").hide();
			$('#pic_upload_preview_depan').attr('src', '');
		}else{
			$("#result_camera_back").html("");
			$("#pic_webcam_back").attr('value', '');
			$(".btn-hapus-foto_back").hide();
			$("#pic_upload_preview_back").hide();
			$('#pic_upload_preview_back').attr('src', '');
		}
        
    }
	
	$(document).on('click','#btn_close_contact',(e)=>{
		e.preventDefault();		
        $("#contact-body").html('');
        $(".contact-title").html('');
		$("#ContactModal").modal('hide');
	});
	
</script>