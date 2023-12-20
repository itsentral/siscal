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
	.Btntable {
		padding: 8px !important;
		background-color: #34A388 !important;
		color: white !important;
		margin: 0px !important;
		border-radius: 10px !important;
	}
</style>


<?php $this->load->view('include/footer'); ?>

<link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.4.2/css/buttons.dataTables.min.css">
<link rel="stylesheet" href="<?php echo base_url('adminlte/plugins/datatables/extensions/FixedColumns/css/dataTables.fixedColumns.min.css'); ?>">
<script src="<?php echo base_url('adminlte/plugins/datatables/extensions/FixedColumns/js/dataTables.fixedColumns.min.js'); ?>"></script>
<script src="https://cdn.datatables.net/buttons/2.4.2/js/dataTables.buttons.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.html5.min.js"></script>
<script>
var table;

$(document).ready(function() {
	const today = new Date();
	const yyyy = today.getFullYear();
	let mm = today.getMonth() + 1; // Months start at 0!
	//let dd = today.getDate();
	//if (dd < 10) dd = '0' + dd;
	if (mm < 10) mm = '0' + mm;
	const formattedToday = mm + '-' + yyyy;

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
		dom				: 'Bfrtip', 
		buttons			: [
							{
								extend: 'pageLength',
								text:      '<i class="fa fa-list-ol"></i> <b>Show</b>',
								className: "Btntable"
							},
							
							{
								extend: 'copy',
								text:      '<i class="fa fa-copy"></i> <b>Copy</b>',
								titleAttr: 'Copy',
								className: "Btntable",
								exportOptions: {
								columns: [0,1,2,3,4,5]
								}
							},
							
							{
								extend: 'excelHtml5',
								text:      '<i class="fa fa-file-excel-o"></i> <b>Excel</b>',
								titleAttr: 'Excel',
								className: "Btntable",
								title: 'Master Customers - Data Bulan '+formattedToday,
								messageTop: 'SISCAL DASHBOARD',
								exportOptions: {
										columns: [0,1,2,3,4,5]
								}
							},
							
							{
								extend: 'pdfHtml5',
								text:      '<i class="fa fa-file-pdf-o"></i> <b>PDF</b>',
								titleAttr: 'PDF',
								className: "Btntable",
								title: 'Master Customers - Data Bulan '+formattedToday,
								messageTop: 'SISCAL DASHBOARD',
								orientation: 'landscape',
								pageSize: 'LEGAL',
								exportOptions: {
								columns: [0,1,2,3,4,5]
								}
							},

						],

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
