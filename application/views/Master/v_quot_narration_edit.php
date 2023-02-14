
<script>
	

</script>
<form action="#" method="POST" id="form-edit-narasi" enctype="multipart/form-data">
	<div class="box box-warning">
		
		
			<?php
			if(empty($rows_header)){
				echo '
				<div class="box-body">
					<div class="row">
						<div class="col-sm-12">
							<div class="alert alert-warning">						 
								<h5><i class="fa fa-info"></i> Alert!</h5>
								DATA NOT FOUND...
							</div>
						</div>					
					</div>
				</div>
				';
			}else{
			
			
			?>
			<div class="box-body">
				<div class="row">
					<div class="col-sm-12 text-center sub-heading" style="color:white;">
						<h5><?php echo $title;?></h5>
					</div>
					
				</div>
				<div class='row'>
					<div class="col-sm-6">
						<div class="form-group">
							<label class="control-label">Code Narration <span class="text-red">*</span></label>
							<?php
								echo form_input(array('id'=>'code_narasi','name'=>'code_narasi','class'=>'form-control input-sm text-up','readOnly'=>true),$rows_header[0]->code);	
									
							?>
						</div>
					</div>
					<div class="col-sm-6">
						<div class="form-group">
							<label class="control-label">Name Narration <span class="text-red">*</span></label>
							<?php
								echo form_input(array('id'=>'nama_narasi','name'=>'nama_narasi','class'=>'form-control text-up'),$rows_header[0]->name);	
									
							?>
						</div>
					</div>				
				</div>
				<div class='row'>
					<div class="col-sm-6">
						<div class="form-group">
							
							<label class="control-label">Description</label>
							<?php
								echo form_textarea(array('id'=>'ket_narasi','name'=>'ket_narasi','class'=>'form-control text-up','cols'=>75,'rows'=>2),$rows_header[0]->descr);							
							?>
						</div>
					</div>
					<div class="col-sm-6">
						<div class="form-group">
							<label class="control-label">Status</label>
							<div>
							<?php
								$active		= ($rows_header[0]->flag_active =='Y')?TRUE:FALSE;
								$data = array(
										'name'          => 'flag_active',
										'id'            => 'flag_active',
										'value'         => 'Y',
										'checked'       => $active,
										'class'         => 'input-sm'
								);
				
								echo form_checkbox($data).'&nbsp;&nbsp;ACTIVE ?';				
							?>
							</div>
						</div>
					</div>				
				</div>
				
				<div class="row">
					<div class="col-sm-12 col-xs-12 text-center sub-heading" style="color:white;">
						<h5>DETAIL NARRATION</h5>
					</div>				
				</div>
				<div class='row'>
					<div class="col-sm-12">
						<div class="callout callout-warning">
							<h4><i class="icon fa fa-warning"></i> Alert!</h4>

							<p>Narration must not contain <b>"</b> , <b>'</b> , <b> & </b> , and <b>- (with spaces)</b>.</p>
						 </div>
					</div>
					<div class="col-sm-12">&nbsp;</div>
					<div class="col-sm-12">
						<div class="box box-danger">
							<div class="box-header">
								<div class="box-tool pull-right">
									<button type="button" class="btn btn-sm " id = "btn-add-rows" title="ADD ROWS" style="background-color:#11324D !important;color:white !important;"> <i class="fa fa-plus fa-lg"></i> ADD ROWS</button>
								</div>
							</div>
							<div class="box-body">
								<table class="table table-striped table-bordered" width="100%">
									<thead>
										<tr class="white-text" style="background-color:#0A81AB !important;color:white !important;">
											<th class="text-center">Narration</th>	
											<th class="text-center">Action</th>
										</tr>
										
									</thead>
									<tbody id="list_item_narasi">
									<?php
									
									if($rows_detail){
										$intL	= 0;
										foreach($rows_detail as $keyD=>$valD){
											$intL++;
											echo"<tr id='tr_narasi_".$intL."' class='narasi'>";
												echo"<td class='text-left'>";
													echo"<textarea cols='50' rows='1' class='editor_narasi form-control text-up' name='detDetail[".$intL."][narration]' id='narration_".$intL."'>".$valD->narration."</textarea>";
												echo"</td>";
												echo"<td class='text-center'><button type='button' class='btn btn-sm btn-danger' title='Delete Row' onClick='return hapus_Rows(".$intL.");'> <i class='fa fa-trash'></i> </button></td>";											
											echo"</tr>";
										}
									}
										
									
									?>
									</tbody>
								</table>
							</div>
							</div>
					</div>
									
				</div>					
			</div>		
			<div class='footer'>
				<button type="button" id="btn-process-edit-narasi" class="btn btn-md" style="background-color:#BB2205; color:white;vertical-align:middle !important;" title="SAVE PROCESS"> SAVE PROCESS <i class="fa fa-long-arrow-right" style="width:40px;"></i> </button>
			</div>
		<?php
		}
		?>
	</div>
</form>

<style>
	.sub-heading{
		border-radius :5px;
		background-color :#03506F;
		color : white;
		margin : 20px 10px 15px 10px !important;
		width :98% !important;
	}
	.text-up{
		text-transform :uppercase !important;
	}
</style>
<script src="<?php echo base_url('assets/plugins/tinymce/tinymce.min.js');?>"></script>
<script>
	$(document).ready(function(){	
		tinymce.init({
		  selector: '.editor_narasi',  // change this value according to your HTML
		  plugin: 'a_tinymce_plugin',
		  a_plugin_option: true,
		  a_configuration_option: 400,
		  menubar: 'edit view format',
		  fontsize_formats: "8pt 9pt 10pt 12pt 14pt 18pt 24pt 36pt",
		  content_style: "body {font-size: 9pt;}"
		});
	});
	$(document).on('click','#btn-add-rows',()=>{
		let nomor_urut 	= 1;
		let text_urut	= 1;
		let jum_baris	= $('#list_item_narasi').find('tr').length;
		if(parseInt(jum_baris) > 0){
			const RowsCodeExp	= $('#list_item_narasi tr:last').attr('id').split('_');
			text_urut			= parseInt($('#tr_narasi_'+RowsCodeExp[2]).text()) + 1;
			nomor_urut			= parseInt(RowsCodeExp[2]) + 1;
		}
		
		Template	='<tr id="tr_narasi_'+nomor_urut+'" >'+
						'<td class="text-left"><textarea cols="50" rows="1" class="editor_narasi form-control input-sm text-up" name="detDetail['+nomor_urut+'][narration]" id="narration_'+nomor_urut+'"></textarea></td>'+
						'<td class="text-center"><button type="button" class="btn btn-sm btn-danger" title="Delete Row" onClick="return hapus_Rows('+nomor_urut+');"> <i class="fa fa-trash"></i> </button></td>'+
					'</tr>';
		$('#list_item_narasi').append(Template);
		tinymce.init({
		  selector: '#narration_'+nomor_urut,  // change this value according to your HTML
		  plugin: 'a_tinymce_plugin',
		  a_plugin_option: true,
		  a_configuration_option: 400,
		  menubar: 'edit view format',
		  fontsize_formats: "8pt 9pt 10pt 12pt 14pt 18pt 24pt 36pt",
		  content_style: "body {font-size: 9pt;}"
		});	
	});
	
	function hapus_Rows(Urut){
		$('#tr_narasi_'+Urut).remove();
	}
</script>