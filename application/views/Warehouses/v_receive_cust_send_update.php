
<form action="#" method="POST" id="form_proses_update" enctype="multipart/form-data">
	<div class="box box-warning">
		
		<div class="box-body">
			
			<?php
			if(empty($rows_detail)){
				echo"<div class='row'>
						<div class='col-sm-12'>
							<h4 class='text-red'><b>NO RECORD WAS FOUND.....</b></h4>
						</div>
					</div>";
			}else{
				echo form_input(array('id'=>'code_trans','name'=>'code_trans','type'=>'hidden'),$code_trans);
				echo form_input(array('id'=>'code_new','name'=>'code_new','type'=>'hidden'),$code_new);
				echo form_input(array('id'=>'code_detail','name'=>'code_detail','type'=>'hidden'),$rows_detail->id);
				echo form_input(array('id'=>'cust_id_modal','name'=>'cust_id_modal','type'=>'hidden'),$rows_header->customer_id);
				echo form_input(array('id'=>'quotation_id','name'=>'quotation_id','type'=>'hidden'),$rows_header->id);
			?>
				<div class="row">
					<div class="col-sm-12 col-xs-12 text-center sub-heading" style="color:white;">
						<h5>DETAIL QUOTATION</h5>
					</div>
					
				</div>
				<div class='row'>
					<div class="col-sm-6">
						<div class="form-group">
							<label class="control-label">Quotation</label>
							<?php
								echo form_input(array('id'=>'quotation_modal','name'=>'quotation_modal','class'=>'form-control input-sm','readOnly'=>true),$rows_header->nomor);	
								
							?>
						</div>
					</div>
					<div class="col-sm-6">
						<div class="form-group">
							<label class="control-label">Quotation Date</label>
							<?php
								echo form_input(array('id'=>'quot_date_modal','name'=>'quot_date_modal','class'=>'form-control input-sm','readOnly'=>true),date('d-m-Y',strtotime($rows_header->datet)));						
							?>
						</div>
					</div>				
				</div>
				<div class='row'>
					<div class="col-sm-6">
						<div class="form-group">
							<label class="control-label">Customer</label>
							<?php
								echo form_input(array('id'=>'cust_modal','name'=>'cust_modal','class'=>'form-control input-sm','readOnly'=>true),$rows_header->customer_name);
														
							?>
						</div>
					</div>
					<div class="col-sm-6">
						<div class="form-group">
							<label class="control-label">Marketing</label>
							<?php
								echo form_input(array('id'=>'member_modal','name'=>'member_modal','class'=>'form-control input-sm','readOnly'=>true),$rows_header->member_name);					
							?>
						</div>
					</div>				
				</div>
				<div class='row'>
					<div class="col-sm-6">
						<div class="form-group">
							<label class="control-label">PO No</label>
							<?php
								echo form_input(array('id'=>'pono_modal','name'=>'pono_modal','class'=>'form-control input-sm','readOnly'=>true),$rows_header->pono);	
								
							?>
						</div>
					</div>
					<div class="col-sm-6">
						<div class="form-group">
							<label class="control-label">PO Date</label>
							<?php
								echo form_input(array('id'=>'podate_modal','name'=>'podate_modal','class'=>'form-control input-sm','readOnly'=>true),date('d-m-Y',strtotime($rows_header->podate)));						
							?>
						</div>
					</div>				
				</div>
				
				<div class="row">
					<div class="col-sm-12 col-xs-12 text-center sub-heading" style="color:white;">
						<h5>DETAIL TOOL </h5>
					</div>					
				</div>
				<div class='row'>
					<div class="col-sm-6">
						<div class="form-group">
							<label class="control-label">Sentral Cust Tool Code</label>
							<div class="row">
								<div class="col-sm-8 col-xs-12">
									<?php
									echo form_input(array('id'=>'code_cust_cari','name'=>'code_cust_cari','class'=>'form-control input-sm','autocomplete'=>"off"));
									echo form_input(array('id'=>'code_sentral_tool','name'=>'code_sentral_tool','type'=>'hidden'));
									?>
								</div>
								<div class="col-sm-4 col-xs-12">
									<button type="button" class="btn btn-sm btn-primary" id="btn_cari_detail"> <i class="fa fa-search"></i> SEARCH </button>
								</div>							
							</div>
						</div>
					</div>
					<div class="col-sm-6">
						<div class="form-group">
							<label class="control-label">Tool Name</label>
							<?php
								echo form_input(array('id'=>'tool_name','name'=>'tool_name','class'=>'form-control input-sm','autocomplete'=>"off",'readOnly'=>true),$rows_detail->cust_tool);						
							?>
						</div>
					</div>				
				</div>
				<div class='row'>
					<div class="col-sm-6">
						<div class="form-group">
							<label class="control-label">Code Tool</label>
							<?php
								echo form_input(array('id'=>'tool_id','name'=>'tool_id','class'=>'form-control input-sm','readOnly'=>true),$rows_detail->tool_id);						
							?>
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
							<label class="control-label">Tool Merk <span class="text-red"> *</span></label>
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
							<label class="control-label">Notes </label>
							<?php
								echo form_textarea(array('id'=>'descr', 'name'=>'descr','cols'=>'75','rows'=>'2', 'class'=>'form-control input-sm'));								
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
						<label>
							<strong>Take Picture Of Tool</strong>
						</label>
						<div>
							<button type="button" class="btn btn-sm bg-green-active"  id="btn_ambil_kamera" name="btn_ambil_kamera">
								<i class="fa fa-camera"></i> Take Picture
							</button>
						</div>
					</div>
					<div class="col-sm-6">
						<label>
							<strong>Upload Picture Of Tool</strong>
						</label>
					   <input class="form-control" type="file" name="files_depan[]" id="files_depan" onChange="ValidateSingleInput(this);" multiple>
					</div>
				</div>
				<div class='row'>
					<div class="col-sm-6" id="div_picture_depan" style="display: none;">
						<div class="form-group">
							<label>
								<strong>Preview Picture 1</strong>
							</label>
							<div>
								<button type="button" class="btn btn-sm btn-danger btn-hapus-foto_depan"  onclick="hapus_foto('depan')">
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
						
					</div>
					<div class="col-sm-6" id="div_picture_back" style="display: none;">
						<div class="form-group">
							<label>
								<strong>Preview Picture 2</strong>
							</label>
							<div>
								<button type="button" class="btn btn-sm btn-danger btn-hapus-foto_back"  onclick="hapus_foto('back')">
									<i class="fa fa-trash"></i> Delete Picture
								</button>
							</div>					
							 <input type="hidden" name="pic_webcam_back" id="pic_webcam_back" value="">							
						</div>
						<div class="form-group">
							<p class="text-center">
								<div id="result_camera_back"></div>
								<img src="" id="pic_upload_preview_back" style="display: none;max-width: 100%;">
							</p>
						</div>
					</div>
				</div>
				<div class='row'>
					<div class="col-sm-6" id="div_picture_kanan" style="display: none;">
						<div class="form-group">
							<label>
								<strong>Preview Picture 3</strong>
							</label>
							<div>
								<button type="button" class="btn btn-sm btn-danger btn-hapus-foto_kanan"  onclick="hapus_foto('kanan')">
									<i class="fa fa-trash"></i> Delete Picture
								</button>
							</div>					
							 <input type="hidden" name="pic_webcam_kanan" id="pic_webcam_kanan" value="">							
						</div>
						<div class="form-group">
							<p class="text-center">
								<div id="result_camera_kanan"></div>
								<img src="" id="pic_upload_preview_kanan" style="display: none;max-width: 100%;">
							</p>
						</div>
						
					</div>
					<div class="col-sm-6" id="div_picture_kiri" style="display: none;">
						<div class="form-group">
							<label>
								<strong>Preview Picture 4</strong>
							</label>
							<div>
								<button type="button" class="btn btn-sm btn-danger btn-hapus-foto_kiri"  onclick="hapus_foto('kiri')">
									<i class="fa fa-trash"></i> Delete Picture
								</button>
							</div>					
							 <input type="hidden" name="pic_webcam_kiri" id="pic_webcam_kiri" value="">							
						</div>
						<div class="form-group">
							<p class="text-center">
								<div id="result_camera_kiri"></div>
								<img src="" id="pic_upload_preview_kiri" style="display: none;max-width: 100%;">
							</p>
						</div>
					</div>
				</div>
				
				
			<?php
			}
		echo'</div>';
		
		if(!empty($rows_detail)){			
			echo'
			<div class="box-body">
				<div class="row col-md-2 col-md-offset-5" id="loader_proses_save">
					<div class="loader">
						<span></span>
						<span></span>
						<span></span>
						<span></span>
					</div>
				</div>
			</div>
			<div class="box-footer">			
				&nbsp;&nbsp;&nbsp;<button type="button" id="btn_process_update" class="btn btn-md" style="background-color:#37474f; color:white;vertical-align:middle !important;" title="SAVE PROCESS"> SAVE PROCESS <i class="fa fa-long-arrow-right" style="width:40px;"></i> </button>
			</div>';
			
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
	|	ALI ~ 2021-03-10	  |
	| ------------------------|
	*/
	function readURL(input,tipe){
        if (input.files && input.files[0]) {
            var reader = new FileReader();

            reader.onload = function(e) {
				if(tipe == 'depan'){
					$("#div_picture_depan").show();
					$("#pic_upload_preview_depan").show();
					$('#pic_upload_preview_depan').attr('src', e.target.result);
				}else if(tipe == 'kanan'){
					$("#div_picture_kanan").show();
					$("#pic_upload_preview_kanan").show();
					$('#pic_upload_preview_kanan').attr('src', e.target.result);
				}else if(tipe == 'kiri'){
					$("#div_picture_kiri").show();
					$("#pic_upload_preview_kiri").show();
					$('#pic_upload_preview_kiri').attr('src', e.target.result);
				}else{
					$("#div_picture_back").show();
					$("#pic_upload_preview_back").show();
					$('#pic_upload_preview_back').attr('src', e.target.result);
				}
                
            }

            reader.readAsDataURL(input.files[0]);
        }
    }
	
	$(document).on('click','#btn_ambil_kamera',()=>{
		let gambar_depan	= $('#pic_webcam_depan').val();
		let gambar_back		= $('#pic_webcam_back').val();
		let gambar_right	= $('#pic_webcam_kanan').val();
		let gambar_left		= $('#pic_webcam_kiri').val();
		let ket_tipe		= '';
		
		if(gambar_depan == '' || gambar_depan == null){
			ket_tipe		= 'depan';
		}else if(gambar_back == '' || gambar_back == null){
			ket_tipe		= 'back';
		}else if(gambar_right == '' || gambar_right == null){
			ket_tipe		= 'kanan';
		}else if(gambar_left == '' || gambar_left == null){
			ket_tipe		= 'kiri';
		}
		
		if(ket_tipe !='' && ket_tipe != null){
			ambil_kamera(ket_tipe);
		}else{
			GeneralShowMessageError('error','Maximum 4 picture. Please delete one picture before take picture again');
			return false;
		}
	});
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
			$("#div_picture_depan").hide();
			$("#pic_upload_preview_depan").hide();
			$('#pic_upload_preview_depan').attr('src', '');
		}else if(jenis =='kanan'){
			$("#result_camera_kanan").html("");
			$("#pic_webcam_kanan").attr('value', '');
			$("#div_picture_kanan").hide();
			$("#pic_upload_preview_kanan").hide();
			$('#pic_upload_preview_kanan').attr('src', '');
		}else if(jenis =='kiri'){
			$("#result_camera_kiri").html("");
			$("#pic_webcam_kiri").attr('value', '');
			$("#div_picture_kiri").hide();
			$("#pic_upload_preview_kiri").hide();
			$('#pic_upload_preview_kiri').attr('src', '');
		}else{
			$("#result_camera_back").html("");
			$("#pic_webcam_back").attr('value', '');
			$("#div_picture_back").hide();
			$("#pic_upload_preview_back").hide();
			$('#pic_upload_preview_back').attr('src', '');
		}
        
    }
	
	
	function view_image(jenis_img,file_image){
		$("#MyModal").modal();
        $("#detail_modal").html('');
        $("#public_title").html('VIEW PICTURE');
	
        $.post(base_url+'/'+active_controller+'/ambil_file_gambar' ,{'kategori':jenis_img,'file_image':file_image}, function(response){
            $("#detail_modal").html(response);
        });
	}
	
	$(document).on('click','#btn_close_contact',(e)=>{
		e.preventDefault();		
        $("#contact-body").html('');
        $(".contact-title").html('');
		$("#ContactModal").modal('hide');
	});
	
</script>