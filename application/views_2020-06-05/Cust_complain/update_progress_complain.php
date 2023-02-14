
<form action="#" method="POST" id="form-proses-progress">
	<div class="box box-success">
		<div class="box-header">
			<h3 class="box-title">
				<i class="fa fa-envelope"></i> <?php echo('<span class="important">'.$title.'</span>'); ?>
			</h3>
			
		</div>
		<div class="box-body">
			<div class='form-group row'>			
				<label class='label-control col-sm-2'><b>No VoC <span class='text-red'>*</span></b></label> 
				<div class='col-sm-4'>
					<?php
						echo form_input(array('id'=>'nomor','name'=>'nomor','class'=>'form-control input-sm','readOnly'=>true),$rows_header[0]->nomor);
						echo form_input(array('id'=>'kode_voc','name'=>'kode_voc','type'=>'hidden'),$rows_header[0]->id);
					?>							
				</div>
				<label class='label-control col-sm-2'><b>Date VoC <span class='text-red'>*</span></b></label>
				<div class='col-sm-4'>
					<?php
						echo form_input(array('id'=>'datet','name'=>'datet','class'=>'form-control input-sm','readOnly'=>true),date('d F Y',strtotime($rows_header[0]->datet)));
					?>							
				</div>				
			</div>
			<div class='form-group row'>			
				<label class='label-control col-sm-2'><b>Receive By <span class='text-red'>*</span></b></label> 
				<div class='col-sm-4'>
					<?php
						echo form_input(array('id'=>'rec_by','name'=>'rec_by','class'=>'form-control input-sm','readOnly'=>true),$rows_header[0]->rec_by);
					?>							
				</div>
				<label class='label-control col-sm-2'><b>Customer <span class='text-red'>*</span></b></label>
				<div class='col-sm-4'>
					<?php
						echo form_input(array('id'=>'customer_id','name'=>'customer_id','class'=>'form-control input-sm','readOnly'=>true),$rows_header[0]->customer_name);
					?>							
				</div>				
			</div>
			<div class='form-group row'>			
				<label class='label-control col-sm-2'><b>No Order <span class='text-red'>*</span></b></label> 
				<div class='col-sm-4'>
					<?php
						echo form_input(array('id'=>'noso','name'=>'noso','class'=>'form-control input-sm','readOnly'=>true),$rows_header[0]->no_so);
					?>							
				</div>
				<label class='label-control col-sm-2'><b>PIC Name <span class='text-red'>*</span></b></label>
				<div class='col-sm-4'>
					<?php
						echo form_input(array('id'=>'pic_name','name'=>'pic_name','class'=>'form-control input-sm','readOnly'=>true),$rows_header[0]->pic_name);
					?>							
				</div>				
			</div>
			<div class='form-group row'>			
				<label class='label-control col-sm-2'><b>PIC Phone <span class='text-red'>*</span></b></label> 
				<div class='col-sm-4'>
					<?php
						echo form_input(array('id'=>'pic_phone','name'=>'pic_phone','class'=>'form-control input-sm','readOnly'=>true),$rows_header[0]->pic_phone);
					?>							
				</div>
				<label class='label-control col-sm-2'><b>PIC Email <span class='text-red'>*</span></b></label>
				<div class='col-sm-4'>
					<?php
						echo form_input(array('id'=>'pic_email','name'=>'pic_email','class'=>'form-control input-sm','readOnly'=>true),$rows_header[0]->pic_email);
					?>							
				</div>				
			</div>			
		</div>
		<div class="box-body">
			<div class="box box-warning">
				<div class="box-header">
					<h4 class="box-title">Action Plan >>> <span class="badge bg-maroon"><?php echo $rows_detail[0]->descr; ?></span></h4>					
				</div>
				<div class="box-body">	
					<div class='form-group row'>			
						<label class='label-control col-sm-2'><b>Action <span class='text-red'>*</span></b></label> 
						<div class='col-sm-4'>
							<?php
								echo form_textarea(array('id'=>'plan_descr','name'=>'plan_descr', 'cols'=>'75','rows'=>'1','class'=>'form-control input-sm','readOnly'=>true),$rows_datas[0]->plan_action);
								echo form_input(array('id'=>'kode_det','name'=>'kode_det','type'=>'hidden'),$rows_detail[0]->id);
							?>							
						</div>
						<label class='label-control col-sm-2'><b>Plan Close <span class='text-red'>*</span></b></label>
						<div class='col-sm-4'>
							<?php
								echo form_input(array('id'=>'plan_date','name'=>'plan_date','class'=>'form-control input-sm','readOnly'=>true),date('d F Y',strtotime($rows_datas[0]->plan_due_date)));
							?>							
						</div>				
					</div>
					<div class='form-group row'>			
						<label class='label-control col-sm-2'><b>PIC Incharge <span class='text-red'>*</span></b></label> 
						<div class='col-sm-4'>
							<?php
								echo form_input(array('id'=>'plan_incharge','name'=>'plan_incharge','class'=>'form-control input-sm','readOnly'=>true),$rows_datas[0]->plan_action_by_name);
								
							?>							
						</div>
						<label class='label-control col-sm-2'><b></b></label>
						<div class='col-sm-4'>
							<?php
								
							?>							
						</div>				
					</div>
				</div>
			</div>
		</div>
		<div class="box-body">
			<div class="box box-danger">
				<div class="box-header">
					<h4 class="box-title">Actual Action</h4>					
				</div>
				<div class="box-body">	
					<div class='form-group row'>			
						<label class='label-control col-sm-2'><b>Actual Action <span class='text-red'>*</span></b></label> 
						<div class='col-sm-4'>
							<?php
								echo form_textarea(array('id'=>'actual_descr','name'=>'actual_descr', 'cols'=>'75','rows'=>'1','class'=>'form-control input-sm'),$rows_datas[0]->plan_action);
								echo form_input(array('id'=>'kode_action','name'=>'kode_action','type'=>'hidden'),$rows_datas[0]->id);
							?>							
						</div>
						<label class='label-control col-sm-2'><b>Close Date<span class='text-red'>*</span></b></label>
						<div class='col-sm-4'>
							<?php
								echo form_input(array('id'=>'actual_date','name'=>'actual_date','class'=>'form-control input-sm','readOnly'=>true),date('Y-m-d'));
							?>							
						</div>				
					</div>
					<div class='form-group row'>			
						<label class='label-control col-sm-2'><b>Actual PIC Incharge <span class='text-red'>*</span></b></label> 
						<div class='col-sm-4'>
							<?php
								$rows_member['']	='Select An Option';
								echo form_dropdown('actual_incharge',$rows_member,$rows_datas[0]->plan_action_by_id, array('id'=>'actual_incharge', 'class'=>'form-control input-sm'));
								
							?>							
						</div>
						<label class='label-control col-sm-2'><b></b></label>
						<div class='col-sm-4'>
							<?php
								
							?>							
						</div>				
					</div>
				</div>
			</div>
		</div>
		<div class="box-body">
			<div class="box box-primary">
				<div class="box-header">
					<h4 class="box-title">Additional Plan Action</h4>
					<div class="box-tools pull-right">	
						<?php
							echo form_button(array('type'=>'button','class'=>'btn btn-md btn-danger','onClick'=>'tambahBaris();','content'=>'ADD PLAN ACTION'));
						?>
					</div>
				</div>
				<div class="box-body">
					 <table id="my-grid" class="table table-bordered table-striped">
						<thead>
							<tr class="bg-blue">
								<th class="text-center">Action</th>
								<th class="text-center">Plan Date Close</th>
								<th class="text-center">PIC Incharge</th>
								<th class="text-center">Action</th>
							</tr>
						</thead>

						<tbody id="list_add_action">
					   
						</tbody>
						
					</table>
					
				</div>
			</div>		
		</div>
		<div class="box-footer">
			<?php				
				echo"<button type='button' class='btn btn-md btn-success' id='btn-save'>SAVE PROCESS <i class='fa fa-save'></i>  </button>";
			?>
		</div>
	</div>
</form>
<style>
	.chosen-container{
		width : 100% !important;
	}
</style>
<script>
	var base_url			= '<?php echo base_url(); ?>';
	var active_controller	= '<?php echo($this->uri->segment(1)); ?>';
	var arr_member			= <?php echo json_encode($rows_member);?>;
	$(document).ready(function(){	
		$('#list_add_action').empty();
		$("#actual_date").datepicker({
			changeMonth		: true,
			changeYear		: true,
			showButtonPanel	: false,
			dateFormat		: 'yy-mm-dd',
			maxDate			: '+0d'
		});
	});
	$(document).on('click','#btn-save',function(e){
		e.preventDefault();
		$('#btn-save, #btn-close').prop('disabled',true);
		let pic_incharge	= $('#actual_incharge').val();
		let act_ket			= $('#actual_descr').val();
		let act_date		= $('#actual_date').val();
		let total_data		= $('#list_add_action').find('tr').length;
		let kode_voc		= $('#kode_voc').val();
		if(pic_incharge === '' || pic_incharge === null){
			swal({
			  title				: "Error Message !",
			  text				: 'Empty Actual PIC Incharge. Please choose actual pic incharge first ...',						
			  type				: "warning"
			});
			$('#btn-save, #btn-close').prop('disabled',false);
			return false;
		}
		
		if(act_ket === '' || act_ket === null || act_ket === '-'){
			swal({
			  title				: "Error Message !",
			  text				: 'Empty Actual action. Please input actual action first ...',						
			  type				: "warning"
			});
			$('#btn-save, #btn-close').prop('disabled',false);
			return false;
		}
		
		if(act_date === '' || act_date === null){
			swal({
			  title				: "Error Message !",
			  text				: 'Empty Actual close date. Please choose actual close date first ...',						
			  type				: "warning"
			});
			$('#btn-save, #btn-close').prop('disabled',false);
			return false;
		}
		
		if(parseInt(total_data) > 0){
			let intK	= 0;
			let intD	= 0;
			let intI	= 0;
			$('#list_add_action').find('tr').each(function(){
				let id_rows		= $(this).attr('id');
				const arr_row	= id_rows.split('_');
				let urut_kode	= arr_row[1];
				let add_ket		= $('#add_descr_'+urut_kode).val();
				let add_tgl		= $('#add_plan_'+urut_kode).val();
				let add_pic		= $('#add_incharge_'+urut_kode).val();
				if(add_ket==='' || add_ket === null  || add_ket==='-'){
					intK++;
				}
				if(add_tgl === '' || add_tgl === null){
					intD++;
				}
				if(add_pic === '' || add_pic === null){
					intI++;
				}
			});
			
			if(intK > 0){
				swal({
				  title				: "Error Message !",
				  text				: 'Empty additional action description. Please input additional action description first ...',						
				  type				: "warning"
				});
				$('#btn-save, #btn-close').prop('disabled',false);
				return false;
			}
			
			if(intD > 0){
				swal({
				  title				: "Error Message !",
				  text				: 'Empty additional action plan close date. Please input additional plan close date first ...',						
				  type				: "warning"
				});
				$('#btn-save, #btn-close').prop('disabled',false);
				return false;
			}
			
			if(intI > 0){
				swal({
				  title				: "Error Message !",
				  text				: 'Empty additional action PIC Incharge. Please choose additional action pic incharge first ...',						
				  type				: "warning"
				});
				$('#btn-save, #btn-close').prop('disabled',false);
				return false;
			}
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
					var formData 	= new FormData($('#form-proses-progress')[0]);
					var baseurl		= base_url +'index.php/'+active_controller+'/save_actual_progress';
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
								window.location.href = base_url +'index.php/'+ active_controller+'/view_detail/'+kode_voc;
							}else{
								
								if(data.status == 2){
									swal({
									  title	: "Save Failed!",
									  text	: data.pesan,
									  type	: "warning"
									});
									
								}else{
									swal({
									  title	: "Save Failed!",
									  text	: data.pesan,
									  type	: "warning"
									});
									
								}
								$('#btn-save, #btn-close').prop('disabled',false);
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
							$('#btn-save, #btn-close').prop('disabled',false);
							return false;
						}
					});
					
				} else {
					close_spinner_new();
					swal("Cancelled", "Data can be process again :)", "error");
					$('#btn-save, #btn-back').prop('disabled',false);
					return false;
				}
		});		
	});
	function tambahBaris(){
		
		let urut_baris		= 1;
		let total_baris		= $('#list_add_action').find('tr').length;
		if(parseInt(total_baris) > 0){
			let kode_rows		= $('#list_add_action tr:last').attr('id');
			const pecah_rows	= kode_rows.split('_'); 
			urut_baris			= parseInt(pecah_rows[1]) + 1;
		}
		let pilih_pic		= '';
		var Template2		='';
		Template2			+= '<tr id="tr_'+urut_baris+'">';
		Template2				+='<td>';
		Template2					+='<textarea cols="75" rows="1" class="form-control input-sm" id="add_descr_'+urut_baris+'" name="detDetail['+urut_baris+'][descr]"></textarea>';	
		Template2				+='</td>';
		Template2				+='<td>';
		Template2					+='<input type="text" class="form-control input-sm tanggal" id="add_plan_'+urut_baris+'" name="detDetail['+urut_baris+'][plan_date]" readOnly>';	
		Template2				+='</td>';
		Template2				+='<td>';
		Template2					+='<select name="detDetail['+urut_baris+'][pic_incharge]" id="add_incharge_'+urut_baris+'" class="form-control input-sm">';
										$.each(arr_member,function(key,nilai){
											let kepilih	= (key === pilih_pic)?'selected':'';
											Template2	+='<option value="'+key+'" '+kepilih+'>'+nilai+'</option>';
										});
		Template2					+='</select">';
		Template2				+='</td>';
		
		Template2				+='<td class="text-center">';
		Template2					+='<button type="button" class="btn btn-sm btn-danger" onClick="deleteROWS('+'\''+urut_baris+'\''+');"> <i class="fa fa-trash"></i> </button>';	
		Template2				+='</td>';
		Template2			+='</tr>';
		$('#list_add_action').append(Template2);
		
		$('#add_plan_'+urut_baris).datepicker({
			changeMonth		: true,
			changeYear		: true,
			showButtonPanel	: false,
			dateFormat		: 'yy-mm-dd',
			minDate			: '+0d'
		});
		$('#add_incharge_'+urut_baris).chosen();
	}
	
	function deleteROWS(no_rows){
		$('#list_add_action #tr_'+no_rows).remove();
	}
</script>
