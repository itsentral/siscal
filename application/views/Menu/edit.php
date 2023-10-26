<?php
$this->load->view('include/side_menu'); 
//echo"<pre>";print_r($data_menu);
?> 
<form action="#" method="POST" id="form_proses_bro">   
	<div class="box box-primary">
		<div class="box-header">
			<h3 class="box-title"><?=$title;?></h3>		
		</div>
		<!-- /.box-header -->
		<div class="box-body">
			<div class='form-group row'>			
				<label class='label-control col-sm-3'><b>Menu Name <span class='text-red'>*</span></b></label>
				<div class='col-sm-8'>
					<?php
						echo form_hidden('id',$row[0]->id);
						echo form_input(array('id'=>'name','name'=>'name','class'=>'form-control input-sm','placeholder'=>'Menu Name','value'=>$row[0]->name));
					?>
							
				</div>
			</div>
			<div class='form-group row'>			
				<label class='label-control col-sm-3'><b>Menu Path <span class='text-red'>*</span></b></label>
				<div class='col-sm-8'>
					<?php
						echo form_input(array('id'=>'path','name'=>'path','class'=>'form-control input-sm','placeholder'=>'Menu Path','value'=>$row[0]->path));
					?>			
				</div>
			</div>
			<div class='form-group row'>			
				<label class='label-control col-sm-3'><b>Menu Parent</b></label>
				<div class='col-sm-8'>
				<?php
					$data_menu[0]	= 'Select An Option';						
					echo form_dropdown('parent_id',$data_menu, $row[0]->parent_id, array('id'=>'parent_id','class'=>'form-control input-sm'));
					
				?>
					
				</div>
			</div>
			<div class='form-group row'>			
				<label class='label-control col-sm-3'><b>Ordering</b></label>
				<div class='col-sm-8'>
				<?php
					echo form_input(array('id'=>'weight','name'=>'weight','class'=>'form-control input-sm','value'=>'0','onKeyPress'=>'return NumberOnly(event);','value'=>$row[0]->weight));
				?>
					
				</div>
			</div>
			<div class='form-group row'>			
				<label class='label-control col-sm-3'><b>Icon</b></label>
				<div class='col-sm-8'>
				<?php
					echo form_input(array('id'=>'icon','name'=>'icon','class'=>'form-control input-sm','value'=>'','placeholder'=>'Menu Icon','value'=>$row[0]->icon));
				?>
					
				</div>
			</div>
			<div class='form-group row'>			
				<label class='label-control col-sm-3'><b>Status Aktif ?</b></label>
				<div class='col-sm-8'>
				<?php
					$active		= ($row[0]->active =='1')?TRUE:FALSE;
					$data = array(
							'name'          => 'flag_active',
							'id'            => 'flag_active',
							'value'         => '1',
							'checked'       => $active,
							'class'         => 'input-sm'
					);
	
					echo form_checkbox($data).'&nbsp;&nbsp;Yes';
					
				?>
					
				</div>
			</div>
		</div>
		<div class='box-footer'>
			<?php
			echo form_button(array('type'=>'button','class'=>'btn btn-md btn-primary','value'=>'save','content'=>'Save','id'=>'simpan-bro')).' ';
			echo form_button(array('type'=>'button','class'=>'btn btn-md btn-danger','value'=>'back','content'=>'Back','onClick'=>'javascript:back()'));
			?>
		</div>
		<!-- /.box-body -->
	 </div>
  <!-- /.box -->
</form>

<?php $this->load->view('include/footer'); ?>
<script>
	$(document).ready(function(){
		$('#simpan-bro').click(function(){
			var nama	= $('#name').val();
			var lokasi	= $('#path').val();
			if(nama=='' || nama==null){
				alert('Empty Menu Name, please input menu name first.....');
				return false;
			}
			
			if(lokasi=='' || lokasi==null){
				alert('Empty Menu Path, please input menu path first.....');
				return false;
			}
			var formData 	=new FormData($('#form_proses_bro')[0]);
			$('#spinner').modal('show');
			$.ajax({
				url: base_url +'index.php/'+ active_controller+'/edit',
				type: "POST",
				data: formData,
				cache: false,
				dataType: 'json',
				processData: false, 
				contentType: false,				
				success: function(data){
					if(data.status == 1){
						$('#spinner').modal('hide');						
						swal({
							  title: "Save Success!",
							  text: data.pesan,
							  type: "success",								  
							  timer: 7000,
							  showCancelButton: false,
							  showConfirmButton: false,
							  allowOutsideClick: false
							});
						window.location.href = base_url +'index.php/'+ active_controller;
					}else{
						$('#spinner').modal('hide');
						if(data.status=='2'){
							swal({
							  title: "Save Failed!",
							  text: data.pesan,
							  type: "danger",								  
							  timer: 7000,
							  showCancelButton: false,
							  showConfirmButton: false,
							  allowOutsideClick: false
							});
						}else{
							
							swal({
							  title: "Save Failed!",
							  text: data.pesan,
							  type: "warning",								  
							  timer: 7000,
							  showCancelButton: false,
							  showConfirmButton: false,
							  allowOutsideClick: false
							});
							
						}
						
					}

					
							
				},
				error: function() {
					$('#spinner').modal('hide');
					swal({
					  title: "Error Message !",
					  text: 'An Error Occured During Process. Please try again..',						
					  type: "warning",								  
					  timer: 7000,
					  showCancelButton: false,
					  showConfirmButton: false,
					  allowOutsideClick: false
					});
				}
			});
		});
	});
</script>
