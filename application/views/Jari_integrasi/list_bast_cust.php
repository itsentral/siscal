<?php
$this->load->view('include/side_menu'); 

?> 
<form action="#" method="POST" id="form-proses">
	<div class="box box-warning">
		<div class="box-header">
			<h4 class="box-title"><i class="fa fa-check-square"></i> <?php echo $title;?></h4>
			<div class="box-tools pull-right">	
				<?php
					if($akses_menu['download'] == '1'){
						echo form_button(array('type'=>'button','class'=>'btn btn-md btn-success','id'=>'btn-create','content'=>'DOWNLOAD EXCEL'));
					}
					
				?>
			</div>		
		</div>
		<!-- /.box-header -->
		<div class="box-body">
			<div class="form-group row">
				<label class="control-label col-sm-2">Period</label>
				<div class="col-sm-4">
					<?php
					echo form_input(array('id'=>'periode','name'=>'periode','class'=>'form-control input-sm','readOnly'=>true),$periode);
					?>
				</div>
			</div>
			<table id="my-grid" class="table table-bordered table-striped">
				<thead>
					<tr class="bg-blue">
						<th class="text-center"><input type="checkbox" name="chk_all" id="chk_all" class="input-sm"></th>
						<th class="text-center">BAST No</th>
						<th class="text-center">Date</th>
						<th class="text-center">Company</th>
						<th class="text-center">PIC Name</th>
						<th class="text-center">Driver</th>
						<th class="text-center">Sales Order</th>
						<th class="text-center">Status</th>
						<th class="text-center">Action</th>
					</tr>
				</thead>
				<tbody id="list_detail">
					<?php
					if($rows_data){
						foreach($rows_data as $keyD=>$valD){
							echo"<tr>";
								echo"<td class='text-center'>";
									echo form_checkbox(array('id'=>'det_pilih_'.$keyD,'name'=>'det_pilih[]','class'=>'input-sm'),$valD->id);
								echo"</td>";
								echo"<td class='text-left'>".$valD->nomor."</td>";
								echo"<td class='text-center'>".date('d M Y',strtotime($valD->datet))."</td>";
								echo"<td class='text-left'>".$valD->customer."</td>";
								echo"<td class='text-left'>".$valD->pic."</td>";
								echo"<td class='text-left'>".$valD->driver."</td>";
								echo"<td class='text-left'>".$valD->no_so."</td>";
								echo"<td class='text-center'><span class='badge bg-green'>".$valD->category."</span></td>";
								echo"<td align='text-center'><a class='btn btn-sm btn-danger' href='#' onClick='view_detail(\"".$valD->id."\")'><i class='fa fa-search'></i></a></td>";
							echo"</tr>";
						}
					}
					?>
				</tbody>
				
			</table>
		</div>
		
		<!-- /.box-body -->
	</div>

<?php $this->load->view('include/footer'); ?>
<!-- page script -->
<script type="text/javascript">
	var base_url			= '<?php echo base_url(); ?>';
	var active_controller	= '<?php echo($this->uri->segment(1)); ?>';
	var arr_akses			= <?php echo json_encode($akses_menu);?>;
    $(function() {
		$('#my-grid').dataTable({
			order			: [[1,'desc']],
			columnDefs		:[{"targets" :0,"sortable":false}]
		});
		$('#btn-create').click(function(e){
			 e.preventDefault();
			var chk_pilih = [];
			$.each($("#list_detail input[type='checkbox']:checked"), function(){
				chk_pilih.push($(this).val());
			});
			if(chk_pilih.length < 1){
				swal({
					  title	: 'Error Message',
					  text	: 'No Record was selected, please choose at least one record....',
					  type	: 'warning'
				});
				return false;
			}
			$('#form-proses').attr('action',base_url+'index.php/'+active_controller+'/download_excel');
			$('#form-proses').submit();
		 });
		$('#periode').datepicker({
			dateFormat	: 'yy-mm-dd',
			changeMonth	: true,
			changeYear	: true,
			maxDate		: '+7d',
			onSelect	: function(dateText, inst) { 
				loading_spinner();
				$('#form-proses').attr('action',base_url+'index.php/'+active_controller+'/index');
				$('#form-proses').submit();
			}
		});
		
		$('#chk_all').click(function(e){
			//e.preventDefault();
			if($('#chk_all').is(':checked')){
				$('#list_detail input[type="checkbox"]:not(:checked)').trigger('click');
			}else{
				$('#list_detail input[type="checkbox"]:checked').trigger('click');
			}
			e.stopPropagation();
		});
	});
	
	function view_detail(kode_bast){
		loading_spinner();
		window.location.href =  base_url+'index.php/'+active_controller+'/view_bast_cust/'+kode_bast;
	}

</script>
