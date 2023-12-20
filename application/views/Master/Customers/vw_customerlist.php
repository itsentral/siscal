<?php
$this->load->view('include/side_menu'); 
?> 
   
<div class="box box-primary">

	<div class="box-header">
		<h3 class="box-title"><i class="fa fa-users"></i> Customer</h3>
		<div class="box-tool pull-right">
		<button type="button" class="btn btn-default" onClick="reload_table()"><i class="glyphicon glyphicon-refresh"></i> Reload</button>	
		<button type="button" class="btn btn-success" onClick="onProgres()"><i class="glyphicon glyphicon-plus"></i> Add Customer</button>	
		</div>
	</div>

	<div class="box-body">

	<div class="col-sm-12" style="padding-bottom:12px;margin-top: -10px">
		<h4>Filter Data:</h4>
	</div>

	<div class="table-responsive col-sm-12" style="padding-bottom:65px;">
		<table id="table-cs" class="table table-bordered table-striped" width="100%">
			<thead style="background-color:#001F3F;color:white;">
				<tr style="font-size: 13px;height: 50px;">
					<th width="3%">#</th>
					<th width="20%">NAMA CUSTOMER</th>
					<th width="22%">ALAMAT</th>
					<th width="10%">TELEPON</th>
					<th width="10%">KONTAK PERSON</th>
					<th width="12%">SALES AKTIF</th>
					<th width="5%">STATUS</th>
					<th width="8%">ACTION</th>
				</tr>
			</thead>
			<tbody>
			</tbody>
		</table>
	</div>
	<br/>
	<br/>
		
	</div>

 </div>

<style>
	.shw_tbl{
		margin-top: 20px;
	}
	table.dataTable tbody td {
		vertical-align: middle;
	}
	table.dataTable thead th {
		text-align: center;
		vertical-align: middle;
	}
</style>


<?php $this->load->view('include/footer'); ?>

<link rel="stylesheet" href="<?php echo base_url('adminlte/plugins/datatables/extensions/FixedColumns/css/dataTables.fixedColumns.min.css'); ?>">
<script src="<?php echo base_url('adminlte/plugins/datatables/extensions/FixedColumns/js/dataTables.fixedColumns.min.js'); ?>"></script>

<script>
var table;

$(document).ready(function() {

	table = $('#table-cs').DataTable({
		// scrollY			: 350,
		// scrollX			: true,
		// fixedColumns	: true,
		// scrollCollapse	: true,     
		processing		: true,
		serverSide		: true,
		paging			: true, 
		order			: [],
		//autoWidth		: false,
		lengthMenu		: [[5, 10, 25, 50, 100, -1], [5, 10, 25, 50, 100, "All"]],
		iDisplayLength	: 5,
		ajax			: {
							"url"	: "<?php echo site_url('master_cs/list_func_customer') ?>",
							"type"	: "POST",
						},

		columnDefs	: [ 
							{
								"targets": [ 0,6,7 ],
								"className": 'text-center',
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

	// new $.fn.dataTable.FixedColumns( table, {
	// 	leftColumns: 1,
	// 	rightColumns: 0
	// } );
	
	$("input").change(function() {
		$(this).parent().parent().removeClass('has-error');
		$(this).next().empty();
	});
	$("select").change(function() {
		$(this).parent().parent().removeClass('has-error');
		$(this).next().empty();
	});

});


function reload_table() {
	table.columns.adjust().draw();
	table.ajax.reload(null, false);
}

function onProgres() {
	alert("Mohon Maaf, Action tersebut dalam proses pengerjaan!");
}

</script>
