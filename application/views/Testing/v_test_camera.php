<?php
$this->load->view('include/side_menu'); 
?>
<form action="#" method="POST" id="form_proses_update" enctype="multipart/form-data">
	<div class="box box-warning">
		
		<div class="box-body">
			
			
				
				<div class="row">
					<div class="col-sm-12 col-xs-12 text-center sub-heading" style="color:white;">
						<h5>TEST IMAGE </h5>
					</div>					
				</div>
				<div class='row'>
					<div class="col-sm-12">
						<div class="form-group">
							<label>
								<strong>Testing Picture</strong>
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
								
						<hr></hr>
					</div>
					
				</div>
				
			</div>
		
		
		
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
<?php $this->load->view('include/footer'); ?>
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
					$("#pic_upload_preview_depan").show();
					$('#pic_upload_preview_depan').attr('src', e.target.result);
				}else if(tipe == 'kanan'){
					$("#pic_upload_preview_kanan").show();
					$('#pic_upload_preview_kanan').attr('src', e.target.result);
				}else if(tipe == 'kiri'){
					$("#pic_upload_preview_kiri").show();
					$('#pic_upload_preview_kiri').attr('src', e.target.result);
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
		}else if(jenis =='kanan'){
			$("#result_camera_kanan").html("");
			$("#pic_webcam_kanan").attr('value', '');
			$(".btn-hapus-foto_kanan").hide();
			$("#pic_upload_preview_kanan").hide();
			$('#pic_upload_preview_kanan').attr('src', '');
		}else if(jenis =='kiri'){
			$("#result_camera_kiri").html("");
			$("#pic_webcam_kiri").attr('value', '');
			$(".btn-hapus-foto_kiri").hide();
			$("#pic_upload_preview_kiri").hide();
			$('#pic_upload_preview_kiri').attr('src', '');
		}else{
			$("#result_camera_back").html("");
			$("#pic_webcam_back").attr('value', '');
			$(".btn-hapus-foto_back").hide();
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