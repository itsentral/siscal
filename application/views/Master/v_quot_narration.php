<?php
$this->load->view('include/side_menu'); 
?>  

<div class="box box-primary">
	<div class="box-header">
		<h3 class="box-title"><?=$title;?></h3>
		<div class="box-tool pull-right">
		<?php
			if($akses_menu['create']=='1'){
				echo '&nbsp;&nbsp;<button type="button" class="btn btn-sm" onClick = "ActionPreview({code:\'\',action :\'add_quot_narasi\',title:\'ADD NARRATION\'});" title="ADD NARRATION" style="background-color:#0A043C !important;color:white !important;"> ADD NARRATION <i class="fa fa-arrow-right fa-lg"></i> </button>';
			}
		  ?>
		</div>
	</div>
	<!-- /.box-header -->
	<div class="box-body">
			
		<table id="example1" class="table table-bordered table-striped">
			<thead>
				<tr class='bg-blue'>
					<th class="text-center">No.</th>
					<th class="text-center">Code</th>
					<th class="text-center">Name</th>
					<th class="text-center">Description</th>
					<th class="text-center">Status</th>
					<th class="text-center">Option</th>
				</tr>
			</thead>
			<tbody>
			  <?php 
			  if($row){
					$int	=0;
					foreach($row as $datas){
						$int++;
						$path	= (isset($datas->path) && $datas->path)?$datas->path:'#';
						$class	= 'bg-green';
						$status	= 'Active';
						if($datas->flag_active == 'N'){
							$class	= 'bg-red';
							$status	= 'Not Active';
						}
						
						echo"<tr>";							
							echo"<td align='center'>$int</td>";
							echo"<td align='center'>".$datas->code."</td>";
							echo"<td align='left'>".$datas->name."</td>";
							echo"<td align='left'>".$datas->descr."</td>";
							echo"<td align='center'><span class='badge $class'>$status</span></td>";
							echo"<td align='center'>";
								$Template = '<button type="button" class="btn btn-sm bg-navy-active" onClick = "ActionPreview({code:\''.$datas->code.'\',action :\'view_detail_narasi\',title:\'VIEW DETAIL\'});" title="VIEW DETAIL"> <i class="fa fa-search"></i> </button>';
								if($akses_menu['update']=='1'){
									$Template .= '&nbsp;&nbsp;<button type="button" class="btn btn-sm " onClick = "ActionPreview({code:\''.$datas->code.'\',action :\'update_quot_narasi\',title:\'UPDATE NARRATION\'});" title="UPDATE NARRATION" style="background-color:#DB6400 !important;color:white !important;"> <i class="fa fa-edit"></i> </button>';
									
								}
								echo $Template;
																
								
							echo"</td>";
						echo"</tr>";
					}
			  }
			  ?>
			</tbody>
		</table>
	</div>
	<!-- /.box-body -->
 </div>
  <!-- /.box -->
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
</style>


<script>
	var base_url			= '<?php echo site_url(); ?>';
	var active_controller	= '<?php echo($this->uri->segment(1)); ?>';
	
	$(document).ready(function(){
		$('#btn-add').click(function(){
			$('#spinner').modal('show');
			window.location.href = base_url +'index.php/'+ active_controller+'/add';
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
	
	$(document).on('click','#btn-process-add-narasi',(e)=>{
		e.preventDefault();
		$('#btn-modal-close, #btn-process-add-narasi').prop('disabled',true);
		let NameNarration		= $('#nama_narasi').val();
		
		if(NameNarration == null || NameNarration == '' || NameNarration == '-'){
			swal({
			  title				: "Error Message !",
			  text				: 'Empty name narration. Please input name narration first...',						
			  type				: "warning"
			});
			$('#btn-modal-close, #btn-process-add-narasi').prop('disabled',false);
			return false;
		}
		
		let intL = 0; 
		tinymce.triggerSave();	
		$('#list_item_narasi tr').each(function(){
			
			const kode_unik_urut	= $(this).attr('id').split('_');
			let kode_text_urut		= kode_unik_urut[2];
			//console.log(kode_unik_urut);
			
			let ket_inv			= $('#narration_'+kode_text_urut).val();
			//let ket_inv		= tinymce.get('#narration_'+kode_text_urut).getContent();
			if(ket_inv == '' || ket_inv == null || ket_inv =='-'){
				intL++;
			}
		});
		if(intL > 0){
			swal({
			  title				: "Error Message !",
			  text				: 'Empty narration. Please input detail narration first...',						
			  type				: "warning"
			});
			$('#btn-modal-close, #btn-process-add-narasi').prop('disabled',false);
			return false;
		}
		
		swal({
			  title: "Are you sure?",
			  text: "You will not be able to process again this data!",
			  type: "warning",
			  showCancelButton: true,
			  confirmButtonClass: "btn-danger",
			  confirmButtonText: "Yes, Process it!",
			  cancelButtonText: "No, cancel process!",
			  closeOnConfirm: true,
			  closeOnCancel: false
			},
			function(isConfirm) {					
				if (isConfirm) {
					loading_spinner_new();
					var formData 	= new FormData($('#form-proses-narasi')[0]);
					var baseurl		= base_url +'/'+ active_controller+'/save_add_quot_narasi';
					$.ajax({
						url			: baseurl,
						type		: "POST",
						data		: formData,
						cache		: false,
						dataType	: 'json',
						processData	: false, 
						contentType	: false,				
						success		: function(data){
							close_spinner_new();
							if(data.status == 1){											
								swal({
									  title	: "Save Success!",
									  text	: data.pesan,
									  type	: "success"
									});
								window.location.href = base_url +'/'+ active_controller;
							}else{								
								swal({
								  title	: "Save Failed!",
								  text	: data.pesan,
								  type	: "warning"
								});									
								//alert(data.pesan);
								$('#btn-modal-close, #btn-process-add-narasi').prop('disabled',false);
								return false;
								
							}
						},
						error: function() {
							close_spinner_new();
							swal({
							  title				: "Error Message !",
							  text				: 'An Error Occured During Process. Please try again..',						
							  type				: "warning"
							});
							$('#btn-modal-close, #btn-process-add-narasi').prop('disabled',false);
							return false;
						}
					});
					
				} else {
					close_spinner_new();
					swal("Cancelled", "Data can be process again :)", "error");
					$('#btn-modal-close, #btn-process-add-narasi').prop('disabled',false);
					return false;
				}
		});
	});
	
	$(document).on('click','#btn-process-edit-narasi',(e)=>{
		e.preventDefault();
		$('#btn-modal-close, #btn-process-edit-narasi').prop('disabled',true);
		let NameNarration		= $('#nama_narasi').val();
		
		if(NameNarration == null || NameNarration == '' || NameNarration == '-'){
			swal({
			  title				: "Error Message !",
			  text				: 'Empty name narration. Please input name narration first...',						
			  type				: "warning"
			});
			$('#btn-modal-close, #btn-process-edit-narasi').prop('disabled',false);
			return false;
		}
		
		let intL = 0; 
		tinymce.triggerSave();
		let juml_baris		= $('#list_item_narasi').find('tr').length;
		if(parseInt(juml_baris) == 0){
			swal({
			  title				: "Error Message !",
			  text				: 'Empty narration. Please input at least one narration first...',						
			  type				: "warning"
			});
			$('#btn-modal-close, #btn-process-edit-narasi').prop('disabled',false);
			return false;
		}
			
		$('#list_item_narasi tr').each(function(){
			
			const kode_unik_urut	= $(this).attr('id').split('_');
			let kode_text_urut		= kode_unik_urut[2];
			//console.log(kode_unik_urut);
			let ket_inv			= $('#narration_'+kode_text_urut).val();
			if(ket_inv == '' || ket_inv == null || ket_inv =='-'){
				intL++;
			}
		});
		if(intL > 0){
			swal({
			  title				: "Error Message !",
			  text				: 'Empty narration. Please input detail narration first...',						
			  type				: "warning"
			});
			$('#btn-modal-close, #btn-process-edit-narasi').prop('disabled',false);
			return false;
		}
		
		swal({
			  title: "Are you sure?",
			  text: "You will not be able to process again this data!",
			  type: "warning",
			  showCancelButton: true,
			  confirmButtonClass: "btn-danger",
			  confirmButtonText: "Yes, Process it!",
			  cancelButtonText: "No, cancel process!",
			  closeOnConfirm: true,
			  closeOnCancel: false
			},
			function(isConfirm) {					
				if (isConfirm) {
					loading_spinner_new();
					var formData 	= new FormData($('#form-edit-narasi')[0]);
					var baseurl		= base_url +'/'+ active_controller+'/save_edit_quot_narasi';
					$.ajax({
						url			: baseurl,
						type		: "POST",
						data		: formData,
						cache		: false,
						dataType	: 'json',
						processData	: false, 
						contentType	: false,				
						success		: function(data){
							close_spinner_new();
							if(data.status == 1){											
								swal({
									  title	: "Save Success!",
									  text	: data.pesan,
									  type	: "success"
									});
								window.location.href = base_url +'/'+ active_controller;
							}else{								
								swal({
								  title	: "Save Failed!",
								  text	: data.pesan,
								  type	: "warning"
								});									
								//alert(data.pesan);
								$('#btn-modal-close, #btn-process-edit-narasi').prop('disabled',false);
								return false;
								
							}
						},
						error: function() {
							close_spinner_new();
							swal({
							  title				: "Error Message !",
							  text				: 'An Error Occured During Process. Please try again..',						
							  type				: "warning"
							});
							$('#btn-modal-close, #btn-process-edit-narasi').prop('disabled',false);
							return false;
						}
					});
					
				} else {
					close_spinner_new();
					swal("Cancelled", "Data can be process again :)", "error");
					$('#btn-modal-close, #btn-process-edit-narasi').prop('disabled',false);
					return false;
				}
		});
	});
</script>
