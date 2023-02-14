
<form action="#" method="POST" id="form-proses-reopen" enctype="multipart/form-data">
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
						<h5>CALIBRATION RESULT</h5>
					</div>
					
				</div>
				
				<div class='row'>
					<div class="col-sm-6">
						<div class="form-group">
							<label class="control-label">Calibration Process <span class="text-red"> *</span></label>
							<div>
								<select name="flag_proses" id="flag_proses" class="form-control chosen-select">
									<option value=""> - Select An Option - </option>
									<?php
									$Arr_Option	= array('Y'=>'YES','N'=>'NO');
									foreach($Arr_Option as $keyOpt=>$valOpt){
										echo'<option value="'.$keyOpt.'">'.$valOpt.'</option>';
									}
														
									?>
								</select>
							</div>
						</div>
					</div>
					<div class="col-sm-6">
						<div class="form-group">
							<label class="control-label">Failed Reason / Notes <span class="text-red"> *</span></label>
							<?php
								echo form_textarea(array('id'=>'failed_reason', 'name'=>'failed_reason','cols'=>'75','rows'=>'2', 'class'=>'form-control input-sm'));									
							?>
							
						</div>
					</div>	
				</div>
				<div class="row">
					<div class="col-sm-12 col-xs-12 text-center sub-heading" style="color:white;">
						<h5>DETAIL TOOL PICTURE </h5>
					</div>					
				</div>
				<div class='row'>
					<div class="col-sm-6">
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
							 <input type="hidden" name="pic_webcam_back" id="pic_webcam_back" value="">
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
						<hr></hr>
					</div>
					<div class="col-sm-6">
						<div class="form-group">
							<label>
								<strong>After Calibration</strong>
							</label>
							<div>
								<button type="button" class="btn btn-sm bg-blue-active" onclick="ambil_kamera('back')">
									<i class="fa fa-camera"></i> Take Picture
								</button>
								<button type="button" class="btn btn-sm btn-danger btn-hapus-foto_back" style="display: none;" onclick="hapus_foto('back')">
									<i class="fa fa-trash"></i> Delete Picture
								</button>
							</div>
							
						</div>
						<div class="form-group">
							<p class="text-center">
								<div id="result_camera_back"></div>
								<img src="" id="pic_upload_preview_back" style="display: none;max-width: 100%;">
							</p>
						</div>
						<div class="form-group">
							<label>
								<strong>Upload After Cals Image</strong>
							</label>
						   <input class="form-control" type="file" name="files_back" id="files_back" onChange="ValidateSingleInput2(this);">					   
						</div>						
						<hr></hr>
					</div>
					
				</div>
				<div id="div_gagal" style="display:none !important;">
					<div class="row">
						<div class="col-sm-12 text-center sub-heading" style="color:white;">
							<h5>FAILED RESULT</h5>
						</div>						
					</div>
					<div class='row'>
						
						<div class="col-sm-6">
							<div class="form-group">
								<label class="control-label">Reschedule</label>
								<div>
									<select name="plan_reschedule" id="plan_reschedule" class="form-control chosen-select">
										
										<?php
										$Arr_Option	= array('N'=>'NO','Y'=>'YES');
										foreach($Arr_Option as $keyOpt=>$valOpt){
											echo'<option value="'.$keyOpt.'">'.$valOpt.'</option>';
										}
															
										?>
									</select>
								</div>
								
							</div>
						</div>
						<div class="col-sm-6">&nbsp;</div>	
					</div>
				</div>
				<div id="div_berhasil" style="display:none !important;">
					<div class="row">
						<div class="col-sm-12 text-center sub-heading" style="color:white;">
							<h5>SUCCESS RESULT</h5>
						</div>						
					</div>
					<div class='row'>
						<?php 
						if($rows_header[0]->subcon == 'Y'){
						?>
							<div class="col-sm-6">
								<div class="form-group">
									<label class="control-label">Actual Subcon <span class="text-red"> *</span></label>
									<div>
										<select name="actual_subcon" id="actual_subcon" class="form-control chosen-select">
											<option value=""> - Select An Option - </option>
											<?php
											if($rows_supplier){
												foreach($rows_supplier as $keySupp=>$valSupp){
													$Yuup = ($keySupp == $rows_header[0]->supplier_id)?'selected':'';
													echo'<option value="'.$keySupp.'" '.$Yuup.'>'.$valSupp.'</option>';
												}
											}
											
																
											?>
										</select>
									</div>
								</div>
							</div>
						<?php
						}else{						
						?>
							<div class="col-sm-6">
								<div class="form-group">
									<label class="control-label">Actual Technician <span class="text-red"> *</span></label>
									<div>
										<select name="actual_teknisi" id="actual_teknisi" class="form-control chosen-select">
											<option value=""> - Select An Option - </option>
											<?php
											if($rows_teknisi){
												foreach($rows_teknisi as $keyTech=>$valTech){
													$Yuup = ($keyTech == $code_teknisi)?'selected':'';
													echo'<option value="'.$keyTech.'" '.$Yuup.'>'.$valTech.'</option>';
												}
											}
											
																
											?>
										</select>
									</div>
								</div>
							</div>
						<?php
						}
						?>
						<div class="col-sm-6">
							<div class="form-group">
								<label class="control-label">Calibration Date <span class="text-red"> *</span></label>
								
								<?php
									echo form_input(array('id'=>'tgl_proses','name'=>'tgl_proses','class'=>'form-control input-sm','readOnly'=>true),date('d-m-Y'));								
								?>
								
							</div>
						</div>				
					</div>
					<div class='row'>
						<div class="col-sm-6">
							<div class="form-group">
								<label class="control-label">Calibration Time <span class="text-red"> *</span></label>
								<div class="rows">
									<div class="col-sm-6 col-xs-6">
										<?php
											echo form_input(array('id'=>'jam_awal','name'=>'jam_awal','class'=>'form-control input-sm jam_picker','autocompete'=>'off', 'placeholder'=>'Start Time'));						
										?>
									</div>
									<div class="col-sm-6 col-xs-6">
										<?php
											echo form_input(array('id'=>'jam_akhir','name'=>'jam_akhir','class'=>'form-control input-sm jam_picker','autocompete'=>'off', 'placeholder'=>'End Time'));						
										?>
									</div>
								</div>
							</div>
						</div>
						<div class="col-sm-6">
							<div class="form-group">
								<label class="control-label">Tool Identification No <span class="text-red"> *</span></label>
								<?php
									echo form_input(array('id'=>'no_identifikasi','name'=>'no_identifikasi','class'=>'form-control input-sm','autocompete'=>'off'));								
								?>
								
							</div>
						</div>				
					</div>
					<div class='row'>
						<div class="col-sm-6">
							<div class="form-group">
								<label class="control-label">Tool Serial Number No <span class="text-red"> *</span></label>
								<?php
									echo form_input(array('id'=>'no_serial_number','name'=>'no_serial_number','class'=>'form-control input-sm','autocompete'=>'off'));								
								?>
								
							</div>
						</div>
						<div class="col-sm-6">
							<div class="form-group">
								<label class="control-label">Tool Merk </label>
								<?php
									echo form_input(array('id'=>'merk_alat','name'=>'merk_alat','class'=>'form-control input-sm','autocompete'=>'off'));							
								?>
								
							</div>
						</div>
										
					</div>
					<div class='row'>
						<div class="col-sm-6">
							<div class="form-group">
								<label class="control-label">Tool Type</label>
								<?php
									echo form_input(array('id'=>'tipe_alat','name'=>'tipe_alat','class'=>'form-control input-sm','autocompete'=>'off'));						
								?>
								
							</div>
							</div>
						<div class="col-sm-6">
							<div class="form-group">
								<label class="control-label">Temperature </label>
								<?php
									echo form_input(array('id'=>'suhu','name'=>'suhu','class'=>'form-control input-sm','autocompete'=>'off'));							
								?>
								
							</div>
						</div>
										
					</div>
					<div class='row'>
						<div class="col-sm-6">
							<div class="form-group">
								<label class="control-label">Humadity</label>
								<?php
									echo form_input(array('id'=>'kelembaban','name'=>'kelembaban','class'=>'form-control input-sm','autocompete'=>'off'));						
								?>
								
							</div>
							</div>
						<div class="col-sm-6">
							<div class="form-group">
								<label class="control-label">Calibration Procedure</label>
								<?php
									echo form_input(array('id'=>'prosedur_kalibrasi','name'=>'prosedur_kalibrasi','class'=>'form-control input-sm','autocompete'=>'off'));						
								?>
								
							</div>
						</div>
									
					</div>
					<div class='row'>
						<div class="col-sm-6">
							<div class="form-group">
								<label class="control-label">Calibration Standard </label>
								<?php
									echo form_input(array('id'=>'standar_kalibrasi','name'=>'standar_kalibrasi','class'=>'form-control input-sm','autocompete'=>'off'));							
								?>
								
							</div>
						</div>	
						<div class="col-sm-6">
							<div class="form-group">
								<label class="control-label">Calibration File <span class="text-red"> *</span></label>
								<input type="file" id="lampiran_kalibrasi" name="lampiran_kalibrasi" class="form-control input-sm" onchange="ValidateSingleInput(this);">
							</div>
						</div>
								
					</div>
					
				</div>
				
			<?php
			}
			?>
										
		</div>		
		<?php
		if(!empty($rows_detail)){
			echo"<div class='footer'>";
				echo'<button type="button" id="btn-process-reopen" class="btn btn-md" style="background-color:#37474f; color:white;vertical-align:middle !important;" title="SAVE PROCESS"> SAVE PROCESS <i class="fa fa-long-arrow-right" style="width:40px;"></i> </button>';
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
</style>
<script type="text/javascript">
	$(function() {		
		$('.jam_picker').mask('?99:99');
		
		$('#tgl_proses').datepicker({
			dateFormat: 'dd-mm-yy',
			changeMonth:true,
			changeYear:true,
			maxDate:'+0d'
		});		
		
		$(".custom-file-input").on("change", function() {
			var fileName = $(this).val().split("\\").pop();
			hapus_foto();
			$(this).siblings(".custom-file-label").addClass("selected").html(fileName);
			readURL(this);
			$(".btn-hapus-foto").show();
		});
	});
	
	$(document).on('change','#flag_proses',()=>{
		let Hasil_Cal = $('#flag_proses').val();
		if(Hasil_Cal == 'Y'){
			$('#div_berhasil').show();
			$('#div_gagal').hide();
		}else if(Hasil_Cal == 'N'){
			$('#div_berhasil').hide();
			$('#div_gagal').show();
		}else{
			$('#div_berhasil').hide();
			$('#div_gagal').hide();
		}
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