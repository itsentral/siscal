<?php
$this->load->view('include/side_menu'); 

?> 
<form action="#" method="POST" id="form-proses">
	<div class="box box-warning">
		<div class="box-header">
			<h3 class="box-title">
				<i class="fa fa-envelope"></i> <?php echo('<span class="important">'.$title.'</span>'); ?>
			</h3>
			
		</div>
		<div class="box-body">
			<div class='form-group row'>			
				<label class='label-control col-sm-2'><b>Date VoC <span class='text-red'>*</span></b></label> 
				<div class='col-sm-4'>
					<?php
						echo date('d F Y');
					?>							
				</div>
				<label class='label-control col-sm-2'><b>Receive By <span class='text-red'>*</span></b></label>
				<div class='col-sm-4'>
					<?php
						$rows_rec['']	='Select An Option';
						echo form_dropdown('rec_by',$rows_rec,'', array('id'=>'rec_by', 'class'=>'form-control input-sm'));
					?>							
				</div>				
			</div>
			<div class='form-group row'>			
				<label class='label-control col-sm-2'><b>Customer <span class='text-red'>*</span></b></label> 
				<div class='col-sm-4'>
					<?php
						$rows_customer['']	='Select An Option';
						echo form_dropdown('customer_id',$rows_customer,'', array('id'=>'customer_id', 'class'=>'form-control input-sm'));
					?>							
				</div>
				<label class='label-control col-sm-2'><b>Sales Order <span class='text-red'>*</span></b></label>
				<div class='col-sm-4'>
					<?php
						$rows_noso	= array('Empty List');
						echo form_dropdown('letter_order_id',$rows_noso,'0', array('id'=>'letter_order_id', 'class'=>'form-control input-sm'));
					?>							
				</div>				
			</div>
			<div class='form-group row'>			
				<label class='label-control col-sm-2'><b>PIC Name <span class='text-red'>*</span></b></label> 
				<div class='col-sm-4'>
					<?php
						echo form_input(array('id'=>'pic_name','name'=>'pic_name','class'=>'form-control input-sm'));
					?>							
				</div>
				<label class='label-control col-sm-2'><b>PIC Phone <span class='text-red'>*</span></b></label>
				<div class='col-sm-4'>
					<?php
						echo form_input(array('id'=>'pic_phone','name'=>'pic_phone','class'=>'form-control input-sm'));
					?>							
				</div>				
			</div>
			<div class='form-group row'>			
				<label class='label-control col-sm-2'><b>PIC Email <span class='text-red'>*</span></b></label> 
				<div class='col-sm-4'>
					<?php
						echo form_input(array('id'=>'pic_email','name'=>'pic_email','class'=>'form-control input-sm'));
					?>							
				</div>
				<label class='label-control col-sm-2'><b>Plan Close Date <span class='text-red'>*</span></b></label> 
				<div class='col-sm-4'>
					<?php
						echo form_input(array('id'=>'plan_close','name'=>'plan_close','class'=>'form-control input-sm','readOnly'=>true),date('Y-m-d'));
					?>							
				</div>				
			</div>
		</div>
		<div class="box-body">
			<div class="box box-primary">
				<div class="box-header">
					<h4 class="box-title">Detail Complain</h4>
					<div class="box-tool pull-right">
						<button type="button" class="btn btn-sm bg-maroon" id="btn-rows"><i class="fa fa-plus"></i> ADD ROW</button>
					</div>
				</div>
				<div class="">
					<table id="my-grid" class="table table-bordered table-striped">
						<thead>					
							<tr class="bg-blue">
								<th class="text-center">Description VoC</th>
								<th class="text-center">Action</th>
							</tr>
						</thead>

						<tbody id="list_detail">
							<tr id="tr_1">
								<td>
									<?php
										echo form_textarea(array('id'=>'descr_1', 'name'=>'detDetail[1][descr]','cols'=>'75','rows'=>'2', 'class'=>'form-control input-sm'));
									?>
								</td>
								<td class="text-center">
									
								</td>
							</tr>
						</tbody>
						
					</table>
				</div>
			</div>		
		</div>
		<div class="box-footer">
			<?php
				echo"<button type='button' class='btn btn-md btn-danger' id='btn-back'> <i class='fa fa-angle-double-left'></i> Back </button>&nbsp;&nbsp;&nbsp;";	
				echo"<button type='button' class='btn btn-md btn-success' id='btn-save'>SAVE <i class='fa fa-save'></i>  </button>";
			?>
		</div>
	</div>
</form>
<?php $this->load->view('include/footer'); ?>
<script>
	var base_url			= '<?php echo base_url(); ?>';
	var active_controller	= '<?php echo($this->uri->segment(1)); ?>';
	$(document).ready(function(){
		$('input[type="select"]').chosen();
		$('#pic_phone').mask('?999999999999');
		$('#btn-back').click(function(){			
			loading_spinner();
			window.location.href =  base_url+'index.php/'+active_controller;
		});
		$("#plan_close").datepicker({
			changeMonth		: true,
			changeYear		: true,
			showButtonPanel	: false,
			dateFormat		: 'yy-mm-dd',
			minDate			: '+0d'
		});
	});
	$(document).on('click','#btn-rows',function(e){
		e.preventDefault();
		let last_baris	= $('#list_detail tr:last').attr('id');
		const row_pecah	= last_baris.split('_');
		let urut		= parseInt(row_pecah[1]) + 1;
		
		let Template	='<tr id="tr_'+urut+'">';
			Template		+='<td><textarea cols="75" rows="2" name="detDetail['+urut+'][descr]" id="descr_'+urut+'" class="form-control input-sm"></textarea></td>';
			Template		+='<td class="text-center"><button type="button" onClick="delRow('+'\''+urut+'\''+');" class="btn btn-sm btn-danger"> <i class="fa fa-trash"></i> </textarea></td>';
		Template		+='</tr>';
		$('#list_detail').append(Template);
	});
	
	$(document).on('change','#customer_id',function(e){
		e.preventDefault();
		let nocust		= $(this).val();
		let Template	= '';
		if(nocust=== '' || nocust === null){
			Template	='<option value="">Empty List</option>';
			$('#letter_order_id').html(Template).trigger('chosen:updated');
		}else{
			var baseurl		= base_url + 'index.php/'+active_controller+'/get_letter_order';
			$.ajax({
				url			: baseurl,
				type		: "POST",
				data		: {'custid':nocust},
				dataType	: 'json',
				success		: function(data){
					
					if($.isEmptyObject(data) === false){
						//let det_datas	= $.parseJSON(data);
						Template	='<option value="">Select An Option</option>';
						$.each(data,function(key,values){
							Template	+='<option value="'+key+'">'+values+'</option>';
						});
						
						$('#letter_order_id').html(Template).trigger('chosen:updated');
					}else{
						Template	='<option value="">Empty List</option>';
						$('#letter_order_id').html(Template).trigger('chosen:updated');
					}
					
				},
				error: function() {
					Template	='<option value="">Empty List</option>';
					$('#letter_order_id').html(Template).trigger('chosen:updated');
				}
			});
		}
	});
	
	$(document).on('click','#btn-save',function(e){
		e.preventDefault();
		$('#btn-save, #btn-back').prop('disabled',true);
		let custid		= $('#customer_id').val();
		let receive_by	= $('#rec_by').val();
		let sales_order	= $('#letter_order_id').val();
		let name_pic	= $('#pic_name').val();
		let phone_pic	= $('#pic_phone').val();
		let email_pic	= $('#pic_email').val();
		let tr_length	= $('#list_detail').find('tr').length;
		let intI		= 0;
		if(receive_by === null || receive_by ==='' || receive_by === '0'){
			swal({
			  title				: "Error Message !",
			  text				: 'Empty receiver by. Please choose receive by first ...',						
			  type				: "warning"
			});
			$('#btn-save, #btn-back').prop('disabled',false);
			return false;
		}
		if(custid === null || custid ==='' || custid === '0'){
			swal({
			  title				: "Error Message !",
			  text				: 'Empty customer. Please choose customer first ...',						
			  type				: "warning"
			});
			$('#btn-save, #btn-back').prop('disabled',false);
			return false;
		}
		if(sales_order === null || sales_order ==='' || sales_order === '0'){
			swal({
			  title				: "Error Message !",
			  text				: 'Empty sales order no. Please choose sales order no first ...',						
			  type				: "warning"
			});
			$('#btn-save, #btn-back').prop('disabled',false);
			return false;
		}
		
		if(name_pic === null || name_pic ==='' || name_pic === '-'){
			swal({
			  title				: "Error Message !",
			  text				: 'Empty PIC name. Please input PIC name first ...',						
			  type				: "warning"
			});
			$('#btn-save, #btn-back').prop('disabled',false);
			return false;
		}
		
		if(phone_pic === null || phone_pic ==='' || phone_pic.length < 5){
			swal({
			  title				: "Error Message !",
			  text				: 'Empty PIC phone. Please input PIC phone first ...',						
			  type				: "warning"
			});
			$('#btn-save, #btn-back').prop('disabled',false);
			return false;
		}
		
		if(email_pic === null || email_pic ==='' || email_pic === '-'){
			swal({
			  title				: "Error Message !",
			  text				: 'Empty PIC email. Please input PIC email first ...',						
			  type				: "warning"
			});
			$('#btn-save, #btn-back').prop('disabled',false);
			return false;
		}
		
		if(parseInt(tr_length) < 1){
			swal({
			  title				: "Error Message !",
			  text				: 'No Record was selected to process......',						
			  type				: "warning"
			});
			$('#btn-save, #btn-back').prop('disabled',false);
			return false;
		}else{
			$('#list_detail textarea').each(function(){
				let keterangan	= $(this).val();
				if(keterangan === '' || keterangan === null || keterangan === '-'){
					intI++;
				}
			});
			
			if(intI > 0){
				swal({
				  title				: "Error Message !",
				  text				: 'Empty description. Please input description first....',						
				  type				: "warning"
				});
				$('#btn-save, #btn-back').prop('disabled',false);
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
					var formData 	= new FormData($('#form-proses')[0]);
					var baseurl		= base_url +'index.php/'+ active_controller+'/create_complain';
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
								window.location.href = base_url +'index.php/'+ active_controller;
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
								$('#btn-save, #btn-back').prop('disabled',false);
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
							$('#btn-save, #btn-back').prop('disabled',false);
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
	function delRow(kode){
		$('#list_detail #tr_'+kode).remove();
	}
	
</script>
