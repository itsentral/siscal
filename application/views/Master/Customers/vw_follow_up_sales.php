<?php
$this->load->view('include/side_menu'); 
?> 
   
<div class="box">

	<div class="box-header">
		<!-- <h3 class="box-title"><i class="fa fa-users"></i> PT. SANKEI GOHSYU INDUSTRIES</h3> -->
		<div class="box-tool pull-left">
			<a href="<?php echo base_url();?>index.php/master_cs" class="btn btn-danger" style="margin-left:10px;" onClick="reload_table()"><i class=" fa fa-arrow-circle-left"></i> &nbsp;Kembali</a>	
		</div>
		
	</div>

	<div class="box-body">
	<div class="">

	<div class="row" style="margin-bottom:20px; margin-top: -10px;">
        <div class="col-xs-12">
          <div class="box box-default">
            <div class="box-header text-center">
              <h3 class="box-title"><i class="fa fa-calendar"></i> <b>Follow Up Monthly</b></h3>
            </div>
            <div class="box-body table-responsive">
              <table class="table table-hover table-month">
                <tr>
                  <th class="text-center">Status Follow Up</th>
                  <th class="text-center">Jan</th>
                  <th class="text-center">Feb</th>
                  <th class="text-center">Mar</th>
                  <th class="text-center">Apr</th>
                  <th class="text-center">Mei</th>
                  <th class="text-center">Jun</th>
                  <th class="text-center">Jul</th>
                  <th class="text-center">Agt</th>
                  <th class="text-center">Sep</th>
                  <th class="text-center">Okt</th>
                  <th class="text-center">Nov</th>
                  <th class="text-center">Des</th>
                </tr>
                <tr class="text-center">
                  <td>
					<?php
					if(!empty($statusActivity->status_activity)){
						if($statusActivity->status_activity == "New Data"){
							echo '<text style="font-size:16px;font-weight:bold;color:#ff851b;">NEW DATA</text>';
						}elseif($statusActivity->status_activity == "No Program"){
							echo '<text style="font-size:16px;font-weight:bold;color:#001a35;">No Program</text>';
						}elseif($statusActivity->status_activity == "Potensial"){
							echo '<text style="font-size:16px;font-weight:bold;color:#605ca8;">POTENSIAL</text>';
						}elseif($statusActivity->status_activity == "Hot"){
							echo '<text style="font-size:16px;font-weight:bold;color:#d81b60;">HOT</text>';
						}elseif($statusActivity->status_activity == "Deal"){
							echo '<text style="font-size:16px;font-weight:bold;color:#3d9970;">DEAL</text>';
						}elseif($statusActivity->status_activity == "Lose"){
							echo '<text style="font-size:16px;font-weight:bold;color:#777;">LOSE</text>';
						}else{
							echo '<text style="font-size:16px;font-weight:bold;color:#777;">No Status</text>';
						}
					}else{
						echo '<text style="font-size:16px;font-weight:bold;color:#777;">No Status</text>';
					}
					
					?>
				  </td>
                  <td>
				  	<?php 
				  	foreach($MonthlyActivity as $val){
						$monthval = date("m",strtotime($val['date_activity']));
						if($monthval == "01"){
							echo '<i class="fa fa-check-square-o"></i>';
							break;
						}
					
					}?>
				  </td>
                  <td>
				  	<?php 
				  	foreach($MonthlyActivity as $val){
						$monthval = date("m",strtotime($val['date_activity']));
						if($monthval == "02"){
							echo '<i class="fa fa-check-square-o"></i>';
							break;
						}
					
					}?>
				  </td>
                  <td>
					<?php 
				  	foreach($MonthlyActivity as $val){
						$monthval = date("m",strtotime($val['date_activity']));
						if($monthval == "03"){
							echo '<i class="fa fa-check-square-o"></i>';
							break;
						}
					
					}?>
				  </td>
                  <td>
				  	<?php 
				  	foreach($MonthlyActivity as $val){
						$monthval = date("m",strtotime($val['date_activity']));
						if($monthval == "04"){
							echo '<i class="fa fa-check-square-o"></i>';
							break;
						}
					
					}?>
				  </td>
                  <td>
				  	<?php 
				  	foreach($MonthlyActivity as $val){
						$monthval = date("m",strtotime($val['date_activity']));
						if($monthval == "05"){
							echo '<i class="fa fa-check-square-o"></i>';
							break;
						}
					
					}?>
				  </td>
                  <td>
				  <?php 
				  	foreach($MonthlyActivity as $val){
						$monthval = date("m",strtotime($val['date_activity']));
						if($monthval == "06"){
							echo '<i class="fa fa-check-square-o"></i>';
							break;
						}
					
					}?>
				  </td>
                  <td>
				  	<?php 
				  	foreach($MonthlyActivity as $val){
						$monthval = date("m",strtotime($val['date_activity']));
						if($monthval == "07"){
							echo '<i class="fa fa-check-square-o"></i>';
							break;
						}
					
					}?>
				  </td>
                  <td>
				  <?php 
				  	foreach($MonthlyActivity as $val){
						$monthval = date("m",strtotime($val['date_activity']));
						if($monthval == "08"){
							echo '<i class="fa fa-check-square-o"></i>';
							break;
						}
					
					}?>
				  </td>
                  <td>
				  <?php 
				  	foreach($MonthlyActivity as $val){
						$monthval = date("m",strtotime($val['date_activity']));
						if($monthval == "09"){
							echo '<i class="fa fa-check-square-o"></i>';
							break;
						}
					
					}?>
				  </td>
                  <td>
					<?php 
				  	foreach($MonthlyActivity as $val){
						$monthval = date("m",strtotime($val['date_activity']));
						if($monthval == "10"){
							echo '<i class="fa fa-check-square-o"></i>';
							break;
						}
					
					}?>
				  </td>
                  <td>
					<?php 
				  	foreach($MonthlyActivity as $val){
						$monthval = date("m",strtotime($val['date_activity']));
						if($monthval == "11"){
							echo '<i class="fa fa-check-square-o"></i>';
							break;
						}
					
					}?>
				  </td>
                  <td>
					<?php 
				  	foreach($MonthlyActivity as $val){
						$monthval = date("m",strtotime($val['date_activity']));
						if($monthval == "12"){
							echo '<i class="fa fa-check-square-o"></i>';
							break;
						}
					
					}?>
				  </td>
                </tr>
                
              </table>
            </div>
            <!-- /.box-body -->
          </div>
          <!-- /.box -->
        </div>
      </div>
	</div>
	
	<div class="row">
		<div class="col-sm-5">
		<div class="row">
			<div class="col-xs-12">
			<div class="box">
				<div class="box-header">
					<h3 class="box-title">
						<i class="fa fa-user"></i> <b>Data Customer</b>
					</h3>
				</div>
				<br/>
				<div class="box-body table-responsive no-padding" style="height: 400px;">
					<table class="table table-hover" width="100%">
						<tr>
							<td width="30%"><i class="fa fa-users"></i> <b>Customer <span class="pull-right">:</span></b></td>
							<td width="70%"><?php echo $rows_header->name;?></td>
						</tr>
						<tr>
							<td><i class="fa fa-map-marker"></i> <b>Alamat <span class="pull-right">:</span></b></td>
							<td><?php echo $rows_header->address;?></td>
						</tr>
						<tr>
							<td><i class="fa fa-phone"></i> <b>Phone <span class="pull-right">:</span></b></td>
							<td><?php echo (!empty($rows_header->phone)) ? $rows_header->phone : '-';?></td>
						</tr>
						<tr>
							<td><i class="fa fa-fax"></i> <b>Fax <span class="pull-right">:</span></b></td>
							<td><?php echo (!empty($rows_header->phone2)) ? $rows_header->phone2 : '-';?></td>
						</tr>
						<tr>
							<td><i class="fa fa-user"></i> <b>Contact <span class="pull-right">:</span></b></td>
							<td><?php echo $rows_header->contact;?></td>
						</tr>
						<tr>
							<td><i class="fa fa-envelope"></i> <b>Email <span class="pull-right">:</span></b></td>
							<td><?php echo $rows_header->email;?></td>
						</tr>
						<tr>
							<td><i class="fa fa-check-circle"></i> <b>Sales <span class="pull-right">:</span></b></td>
							<td><span class="label label-primary" style="font-size:12px;"><?php echo $getSales->nama;?></span></td>
						</tr>
						<!-- <tr>
							<td><i class="fa fa-paper-plane"></i> <b>Tele <span class="pull-right">:</span></b></td>
							<td>Aminah</td>
						</tr> -->
					</table>
				</div>
			</div>
			</div>
		</div>

		</div>
		
		<div class="col-sm-7">
		<div class="row">
			<div class="col-xs-12">
			<div class="box">
				<div class="box-header">
					<h3 class="box-title"><i class="fa fa-calendar-check-o"></i> <b>Activity Sales</b></h3>

					<div class="box-tools">
					<button type="button" class="btn btn-default btn-md" style="" onClick="reload_table()"><i class="fa fa-refresh"></i> Reload</button>
						<button type="button" class="btn btn-success btn-md" style="margin-right: -10px;" onClick="add_activity_sales()"><i class="fa fa-calendar-plus-o"></i> Add Activity</button>	
					</div>
				</div>
				<br/>
				<div class="box-body no-padding" style="height: 400px;">
					<table class="table table-hover nowrap" id="table-activity">
						<thead style="background-color:#698a9e;color:white;" width="100%">
							<tr>
								<th width="20%">Sales</th>
								<th width="10%">Tanggal</th>
								<th width="10%">Status</th>
								<th width="50%">Keterangan</th>
								<th width="10%">Action</th>
							</tr>
						</thead>
						<tbody>
						</tbody>
					</table>
				</div>
			</div>
			</div>
		</div>
		</div>
	</div>
	<br/>
		
	</div>

</div>


<div class="modal fade" id="addActivity" tabindex="-1" role="dialog" aria-labelledby="addActivity" data-backdrop="static">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
				<span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title title-activity">Activity</h4>
			</div>
			
			<div class="modal-body">
			<form class="form-horizontal" action="#" id="formActivity" method="POST">
				<input type="hidden" name="id" id="id">
				<input type="hidden" name="customer_id" id="customer_id" value="<?php echo $rows_header->id;?>">
				<div class="form-group">
					<label for="nama_sales" class="col-sm-3 control-label">Nama Sales</label>

					<div class="col-sm-9">
						<input type="text" class="form-control" name="nama_sales" id="nama_sales" placeholder="Nama Sales">
					</div>
				</div>
				<div class="form-group">
					<label for="date_activity" class="col-sm-3 control-label">Tgl Follow Up</label>

					<div class="col-sm-9">
						<input type="text" class="form-control" name="date_activity" id="date_activity" placeholder="yyyy-mm-dd" readonly>
					</div>
				</div>
				<div class="form-group">
					<label for="ket_activity" class="col-sm-3 control-label">Keterangan</label>

					<div class="col-sm-9">
						<textarea class="form-control" name="ket_activity" id="ket_activity" placeholder="Keterangan Activity..." rows=3></textarea>
					</div>
				</div>
				<div class="form-group">
					<label for="status_activity" class="col-sm-3 control-label">Status</label>
					<div class="col-sm-9">
						<select class="form-control chosen-select1" name="status_activity" id="status_activity">
							<option value="">==Pilih Status==</option>
							<option value="New Data">New Data</option>
							<option value="No Program">No Program</option>
							<option value="Potensial">Potensial</option>
							<option value="Hot">Hot</option>
							<option value="Deal">Deal</option>
							<option value="Lose">Lose</option>
						</select>
						
					</div>
					
				</div>
				
			</div>

			<div class="modal-footer">
				<button type="button" class="btn btn-danger" data-dismiss="modal"><i class="fa fa-close"></i> Close</button>
				<button type="submit" class="btn btn-primary" id="btnSave"><i class="fa fa-save"></i> Simpan</button>
			</div>
			
			</form>
		</div>
	</div>
</div>

<style>
	table.dataTable tbody td {
		vertical-align: middle;
		padding:7px;
		font-size: 14px;
		color: #1A89B9;
	}
	table.dataTable thead th {
		text-align: center;
		vertical-align: middle;
		padding-left: 25px;
	}
	table.dataTable thead th:first-child {
		border-radius: 0px 0px 0px 0px;
	}
	table.dataTable thead th:last-child {
		border-radius: 0px 0px 0px 0px;
	}

	table.table-month tbody td {
		vertical-align: middle;
		padding:7px;
		font-size: 16px;
		color: #23456D;
	}
	.chosen-container-single .chosen-single{
		height: 30px;
  		line-height: 30px;
	}
</style>


<?php $this->load->view('include/footer'); ?>
<link rel="stylesheet" href="<?php echo base_url('adminlte/plugins/iCheck/all.css');?>">
<script src="<?php echo base_url('adminlte/plugins/iCheck/icheck.min.js'); ?>"></script>
<script>
var table;
var save_method;
$(function() {	
	$('#date_activity').datepicker({
		dateFormat	: 'yy-mm-dd',
		changeMonth	:true,
		changeYear	:true,
	});
	
});
$(document).ready(function() {
	table = $('#table-activity').DataTable({   
		scrollY			: 255,
		scrollX			: true,
		scrollCollapse	: false,  
		processing		: true,
		serverSide		: true,
		paging			: true,
		searching		: false,
		autoWidth		: false,
		order			: [],
		ajax			: {
							"url"	: "<?php echo site_url('master_cs/list_func_activity') ?>",
							"type"	: "POST",
							"data"	: function(data) {
										data.cus = '<?php echo $rows_header->id;?>';
									},
						},

		columnDefs	: [
							{
								"targets": [ 1,2,4 ],
								"className": 'text-center',
							},  
							{
								"targets": [ 0,1,2,3,4 ],
								"orderable": false,
							}, 
						],
		
		fnDrawCallback: function(nRow, aData, iDisplayIndex) {
			$('#table tbody tr').hover(function() {
				$(this).addClass('highlight');
			}, function() {
				$(this).removeClass('highlight');
			});
		}

	});
	
	$("input").change(function() {
		$(this).parent().parent().removeClass('has-error');
		$(this).next().empty();
	});
	// $("select").change(function() {
	// 	$(this).parent().parent().removeClass('has-error');
	// 	$(this).next().empty();
	// });



});

function reload_table() {
	table.columns.adjust().draw();
	table.ajax.reload(null, false);
	location.reload();
}

function add_activity_sales() {
	save_method = 'add';
	$('#formActivity')[0].reset()
	$('.form-group').removeClass('has-error');
	$('.help-block').empty();
	$('#id').attr('readonly', true);
	$('#customer_id').attr('readonly', true);
	$(".chosen-select1").val('').trigger("chosen:updated");
	$(".title-activity").text("Add Activity Sales"); 
	$('#addActivity').modal('show');
	
}

function edit_activity_sales(id) {
	save_method = 'update';
	$('#formActivity')[0].reset();
	$('.form-group').removeClass('has-error');
	$('.help-block').empty();
	$('#id').attr('readonly', true);
	$('#customer_id').attr('readonly', true);

	$.ajax({
		url: "<?php echo site_url('master_cs/edit_func_activity') ?>/" + id,
		type: "GET",
		dataType: "JSON",
		success: function(data) {
			$('[name="id"]').val(data.id);
			$('[name="customer_id"]').val(data.customer_id);
			$('[name="nama_sales"]').val(data.nama_sales);
			$('[name="date_activity"]').val(data.date_activity);
			$('[name="ket_activity"]').val(data.ket_activity);
			$(".chosen-select1").val(data.status_activity).trigger("chosen:updated");
			$(".title-activity").text("Edit Activity Sales"); 
			$('#addActivity').modal('show');

		},
		error: function(jqXHR, textStatus, errorThrown) {
			alert('Error get data from ajax');
		}
	});
}

$("#formActivity").submit(function(e) {
	e.preventDefault();

	$('#btnSave').text('saving...');
	$('#btnSave').attr('disabled', true);
	var url;

	if (save_method == 'add') {
		url = "<?php echo site_url('master_cs/add_func_activity') ?>";
	} else {
		url = "<?php echo site_url('master_cs/update_func_activity') ?>";
	}

	var formData = new FormData($('#formActivity')[0]);
	$.ajax({
		url: url,
		type: "POST",
		data: formData,
		contentType: false,
		processData: false,
		dataType: "JSON",
		success: function(data) {

			if (data.status)
			{
				$('#addActivity').modal('hide');
				alert(data.msg);
				reload_table();
			} else {
				alert(data.msg);
			}
			$('#btnSave').text('simpan');
			$('#btnSave').attr('disabled', false);


		},
		error: function(jqXHR, textStatus, errorThrown) {
			alert('Error adding / update data');
			$('#btnSave').text('simpan');
			$('#btnSave').attr('disabled', false);

		}
	});
});

function delete_activity_sales(id) {
	var idcs = '<?php echo $rows_header->id;?>';
	if (confirm('Yakin ingin menghapus data activity tersebut?')) {
		$.ajax({
			url: "<?php echo site_url('master_cs/delete_func_activity') ?>/" + id + "/" + idcs,
			type: "POST",
			dataType: "JSON",
			success: function(data) {
				alert('Data Berhasil dihapus.');
				reload_table();
			},
			error: function(jqXHR, textStatus, errorThrown) {
				alert('Error deleting data');
			}
		});

	}
}

</script>
