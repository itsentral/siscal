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
				<label class='label-control col-sm-2'><b>No VoC <span class='text-red'>*</span></b></label> 
				<div class='col-sm-4'>
					<?php
						echo form_input(array('id'=>'nomor','name'=>'nomor','class'=>'form-control input-sm','readOnly'=>true),$rows_header[0]['nomor']);
						echo form_input(array('id'=>'kode_voc','name'=>'kode_voc','type'=>'hidden'),$rows_header[0]['id']);
					?>							
				</div>
				<label class='label-control col-sm-2'><b>Date VoC <span class='text-red'>*</span></b></label>
				<div class='col-sm-4'>
					<?php
						echo form_input(array('id'=>'datet','name'=>'datet','class'=>'form-control input-sm','readOnly'=>true),date('d F Y',strtotime($rows_header[0]['datet'])));
					?>							
				</div>				
			</div>
			<div class='form-group row'>			
				<label class='label-control col-sm-2'><b>Receive By <span class='text-red'>*</span></b></label> 
				<div class='col-sm-4'>
					<?php
						echo form_input(array('id'=>'rec_by','name'=>'rec_by','class'=>'form-control input-sm','readOnly'=>true),$rows_header[0]['rec_by']);
					?>							
				</div>
				<label class='label-control col-sm-2'><b>Customer <span class='text-red'>*</span></b></label>
				<div class='col-sm-4'>
					<?php
						echo form_input(array('id'=>'customer_id','name'=>'customer_id','class'=>'form-control input-sm','readOnly'=>true),$rows_header[0]['customer_name']);
					?>							
				</div>				
			</div>
			<div class='form-group row'>			
				<label class='label-control col-sm-2'><b>No Order <span class='text-red'>*</span></b></label> 
				<div class='col-sm-4'>
					<?php
						echo form_input(array('id'=>'noso','name'=>'noso','class'=>'form-control input-sm','readOnly'=>true),$rows_header[0]['no_so']);
					?>							
				</div>
				<label class='label-control col-sm-2'><b>PIC Name <span class='text-red'>*</span></b></label>
				<div class='col-sm-4'>
					<?php
						echo form_input(array('id'=>'pic_name','name'=>'pic_name','class'=>'form-control input-sm','readOnly'=>true),$rows_header[0]['pic_name']);
					?>							
				</div>				
			</div>
			<div class='form-group row'>			
				<label class='label-control col-sm-2'><b>PIC Phone <span class='text-red'>*</span></b></label> 
				<div class='col-sm-4'>
					<?php
						echo form_input(array('id'=>'pic_phone','name'=>'pic_phone','class'=>'form-control input-sm','readOnly'=>true),$rows_header[0]['pic_phone']);
					?>							
				</div>
				<label class='label-control col-sm-2'><b>PIC Email <span class='text-red'>*</span></b></label>
				<div class='col-sm-4'>
					<?php
						echo form_input(array('id'=>'pic_email','name'=>'pic_email','class'=>'form-control input-sm','readOnly'=>true),$rows_header[0]['pic_email']);
					?>							
				</div>				
			</div>
			<div class='form-group row'>			
				<label class='label-control col-sm-2'><b>Plan Close <span class='text-red'>*</span></b></label> 
				<div class='col-sm-4'>
					<?php
						echo form_input(array('id'=>'plan_close','name'=>'plan_close','class'=>'form-control input-sm','readOnly'=>true),date('d F Y',strtotime($rows_header[0]['plan_close'])));
					?>							
				</div>
				<label class='label-control col-sm-2'><b>User Incharge <span class='text-red'>*</span></b></label>
				<div class='col-sm-4'>
					<?php
						$rows_incharge['']	='Select An Option';
						echo form_dropdown('pic_incharge',$rows_incharge,'', array('id'=>'pic_incharge', 'class'=>'form-control input-sm'));
					?>							
				</div>				
			</div>
		</div>
		<div class="box-body">
			<div class="box box-primary">
				<div class="box-header">
					<h4 class="box-title">Detail Complain</h4>
					
				</div>
				<div class="box-body">					
					<?php
					if($rows_detail){
						$id_Accord	= "MyAccordion";
						echo"<div class='panel-group' id='".$id_Accord."'>";
							$Loop	= 0;
							foreach($rows_detail as $key=>$vals){
								$Loop++;
								$Coll_Name	= "tutup_".$Loop;
								echo form_input(array('id'=>'kode_det_'.$Loop,'name'=>'detDetail['.$vals['id'].'][header]','type'=>'hidden'),$vals['id']);
								echo"<div class='panel panel-info'>";
									echo"<div class='panel-heading'>";
										echo"<h4 class='panel-title'>";
											echo"<a data-toggle='collapse' href='#".$Coll_Name."' data-parent='#".$id_Accord."'>".$Loop.". ".$vals['descr']."</a>";
											
										echo"</h4>";
									echo"</div>";
									echo"<div id='".$Coll_Name."' class='panel-collapse collapse in'>";
										echo"<div class='panel-body'>";
											echo"<div class='form-group row'>";
												echo"<div class='col-sm-12 col-xs-12'>";
													echo form_button(array('type'=>'button','class'=>'btn btn-md btn-success pull-right','id'=>'btn-create','content'=>'ADD ROW','onClick'=>'create_rows(\''.$vals['id'].'\');'));
												echo"</div>";
											echo"</div>";
											echo"<table id='my-grid' class='table table-bordered table-striped'>
												<thead>					
													<tr class='bg-blue'>
														<th class='text-center'>Action Description</th>
														<th class='text-center'>Plan Close</th>
														<th class='text-center'>Option</th>
													</tr>
												</thead>
												<tbody id='list_detail_".$vals['id']."'>
													<tr id='tr_1'>
														<td>
														".form_textarea(array('id'=>'action_descr_'.$vals['id'].'_1','name'=>'detDetail['.$vals['id'].'][detail][1][descr]', 'cols'=>'75','rows'=>'1','class'=>'form-control input-sm'))."
														</td>
														<td>
														".form_input(array('id'=>'action_plan_'.$vals['id'].'_1','name'=>'detDetail['.$vals['id'].'][detail][1][plan_date]','class'=>'form-control input-sm tanggal','readOnly'=>true))."
														</td>
														<td></td>
													</tr>
												</tbody>
												
											</table>";
										echo"</div>";
									echo"</div>";
								echo"</div>";
							}
							echo form_input(array('id'=>'jumlah_data','name'=>'jumlah_data','type'=>'hidden'),$Loop);
						echo"</div>";
					}					
					?>
				</div>
			</div>		
		</div>
		<div class="box-footer">
			<?php
				echo"<button type='button' class='btn btn-md btn-danger' id='btn-back'> <i class='fa fa-angle-double-left'></i> Back </button>&nbsp;&nbsp;&nbsp;";	
				echo"<button type='button' class='btn btn-md btn-success' id='btn-save'>FOLLOW UP PROCESS <i class='fa fa-save'></i>  </button>";
			?>
		</div>
	</div>
</form>
<?php $this->load->view('include/footer'); ?>
<script>
	var base_url			= '<?php echo base_url(); ?>';
	var active_controller	= '<?php echo($this->uri->segment(1)); ?>';
	$(document).ready(function(){		
		$('#btn-back').click(function(){			
			loading_spinner();
			window.location.href =  base_url+'index.php/'+active_controller;
		});
		$(".tanggal").datepicker({
			changeMonth		: true,
			changeYear		: true,
			showButtonPanel	: false,
			dateFormat		: 'yy-mm-dd',
			minDate			: '+0d'
		});
	});
	$(document).on('click','#btn-save',function(e){
		e.preventDefault();
		$('#btn-save, #btn-back').prop('disabled',true);
		let pic_incharge	= $('#pic_incharge').val();
		let jumlah_datas	= parseInt($('#jumlah_data').val());
		let intK			= 0;
		let intY			= 0;
		
		if(pic_incharge === '' || pic_incharge === null){
			swal({
			  title				: "Error Message !",
			  text				: 'Empty PIC Incharge. Please choose pic incharge first ...',						
			  type				: "warning"
			});
			$('#btn-save, #btn-back').prop('disabled',false);
			return false;
		}
		
		for(x=1;x<=jumlah_datas;x++){
			let kode_detail	= $('#kode_det_'+x).val();
			$('#list_detail_'+kode_detail).find('tr').each(function(){
				let id_rows		= $(this).attr('id');
				const arr_row	= id_rows.split('_');
				let urut_kode	= arr_row[1];
				let keterangan	= $('#action_descr_'+kode_detail+'_'+urut_kode).val();
				let tgl_plan	=  $('#action_plan_'+kode_detail+'_'+urut_kode).val();
				
				if(keterangan==='' || keterangan === null  || keterangan==='-'){
					intK++;
				}
				if(tgl_plan === '' || tgl_plan === null){
					intY++;
				}
			});
		}
		
		if(intK > 0){
			swal({
			  title				: "Error Message !",
			  text				: 'Empty action description. Please input action description first ...',						
			  type				: "warning"
			});
			$('#btn-save, #btn-back').prop('disabled',false);
			return false;
		}
		
		if(intY > 0){
			swal({
			  title				: "Error Message !",
			  text				: 'Empty action description. Please input action description first ...',						
			  type				: "warning"
			});
			$('#btn-save, #btn-back').prop('disabled',false);
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
					var formData 	= new FormData($('#form-proses')[0]);
					var baseurl		= base_url +'index.php/'+active_controller+'/update_voc';
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
	function create_rows(kode_det){
		let kode_rows		= $('#list_detail_'+kode_det+' tr:last').attr('id');
		const pecah_rows	= kode_rows.split('_'); 
		let urut_rows		= parseInt(pecah_rows[1]) + 1;
		
		let Template		= '<tr id="tr_'+urut_rows+'">';
		Template				+='<td>';
		Template					+='<textarea cols="75" rows="1" class="form-control input-sm" id="action_descr_'+kode_det+'_'+urut_rows+'" name="detDetail['+kode_det+'][detail]['+urut_rows+'][descr]"></textarea>';	
		Template				+='</td>';
		Template				+='<td>';
		Template					+='<input type="text" class="form-control input-sm tanggal" id="action_plan_'+kode_det+'_'+urut_rows+'" name="detDetail['+kode_det+'][detail]['+urut_rows+'][plan_date]" readOnly>';	
		Template				+='</td>';
		Template				+='<td class="text-center">';
		Template					+='<button type="button" class="btn btn-sm btn-danger" onClick="deleteROWS('+'\''+kode_det+'\''+','+'\''+urut_rows+'\''+');"> <i class="fa fa-trash"></i> </button>';	
		Template				+='</td>';
		Template			+='</tr>';
		$('#list_detail_'+kode_det).append(Template);
		
		$('#action_plan_'+kode_det+'_'+urut_rows).datepicker({
			changeMonth		: true,
			changeYear		: true,
			showButtonPanel	: false,
			dateFormat		: 'yy-mm-dd',
			minDate			: '+0d'
		});
		
	}
	
	function deleteROWS(kode_det,no_rows){
		$('#list_detail_'+kode_det+' #tr_'+no_rows).remove();
	}
</script>
