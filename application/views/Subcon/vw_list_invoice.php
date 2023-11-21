<?php
$this->load->view('include/side_menu'); 
?> 
   
<div class="box box-primary">

	<div class="box-header">
		<h3 class="box-title"><i class="fa fa-folder-o"></i> <?=$title;?></h3>
		<div class="box-tool pull-right">
		</div>
	</div>

	<div class="box-body">
	<form>
		<div class='form-group row'>			
			<div class='col-sm-3'>
				<input type="text" class="form-control" name="noInvoice" id="noInvoice" placeholder="Cari No Invoice...">							
			</div>
			<div class='col-sm-3'>
				<input type="text" class="form-control" name="noPCR" id="noPCR" placeholder="Cari No PCR...">							
			</div>
			<div class='col-sm-2'>
				<select class="form-control chosen-large" name="noRef" id="noRef">
					<option value="">== Pilih Status ==</option>
					<option value="P">PAID</option>
					<option value="U">UNPAID</option>
				</select>							
			</div>
			<div class='col-sm-4'>
            	<button type="button" class="btn btn-primary" onClick="reload_table()"><i class="glyphicon glyphicon-search"></i> Search</button>			
				<button type="button" class="btn btn-warning" onClick="reload_table()"><i class="glyphicon glyphicon-refresh"></i> Reload Table</button>				
			</div>
		</div>
	</form>
	<br/>

	<div class="shw_tbl">
		<table id="table-invoice" class="table table-bordered table-striped" cellspacing="0" width="100%">
			<thead style="background-color:#102850;color:white;">
                <tr >
                    <th class="text-center" width="25%">Vendor</th>
                    <th class="text-center" width="10%">No Invoice</th>
                    <th class="text-center" width="10%">No CPR</th>
                    <th class="text-center" width="15%">Total After PPN</th>
                    <th class="text-center" width="15%">Desc/Plan Bayar</th>
                    <th class="text-center" width="10%">Tgl Pembayaran</th>
                    <th class="text-center" width="10%">No REFF</th>
                    <th class="text-center" width="5%">Status</th>
                </tr>
            </thead>
            <tbody>
            </tbody>
			<tfoot>
				<tr>
					<th></th>
					<th></th>
					<th style="text-align:right">Total:</th>
					<th colspan=""></th>
					<th></th>
					<th></th>
					<th></th>
					<th></th>
				</tr>
			</tfoot>
		</table>
	</div>
	<br/>
	<br/>
		
	</div>

 </div>


<style>
	.Btntable {
		padding: 8px !important;
		background-color: #34A388 !important;
		color: white !important;
		margin: 0px !important;
		border-radius: 10px !important;
	}

	.chosen-container-single .chosen-single{
		height: 35px;
  		line-height: 35px;
	}
</style>


<?php $this->load->view('include/footer'); ?>


<link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.4.2/css/buttons.dataTables.min.css">
<script src="https://cdn.datatables.net/buttons/2.4.2/js/dataTables.buttons.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.html5.min.js"></script>

<script>
var save_method;
var table;

$(document).ready(function() {
	var numberRenderer = $.fn.dataTable.render.number( ',').display;
	table = $('#table-invoice').DataTable({
		scrollY			: 377,
		scrollX			: true,
		scrollCollapse	: true,   
		processing		: true,
		serverSide		: true, 
		order			: [],
		lengthMenu		: [[10, 25, 50, 100, -1], [10, 25, 50, 100, "All"]],
		iDisplayLength	: 50,
		ajax			: {
							"url"	: "<?php echo site_url('list_invoice_subcon/list_func') ?>",
							"type"	: "POST",
							"data"	: function(data) {
										data.noInvoice 	= $('#noInvoice').val();
										data.noPCR 		= $('#noPCR').val();
										data.noRef 		= $('#noRef').val();
							},
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
								columns: [0,1,2,3,4,5,6,7]
								}
							},
							
							{
								extend: 'excelHtml5',
								text:      '<i class="fa fa-file-excel-o"></i> <b>Excel</b>',
								titleAttr: 'Excel',
								className: "Btntable",
								exportOptions: {
								columns: [0,1,2,3,4,5,6,7]
								}
							},
							
							{
								extend: 'pdfHtml5',
								text:      '<i class="fa fa-file-pdf-o"></i> <b>PDF</b>',
								titleAttr: 'PDF',
								className: "Btntable",
								orientation: 'landscape',
								pageSize: 'LEGAL',
								exportOptions: {
								columns: [0,1,2,3,4,5,6,7]
								}
							},

						],
		columnDefs		: [
							{
								"targets": [ 2, 5, 6, 7 ],
								"className": 'text-center',
							},  
							{
								"targets": [ 3 ],
								"className": 'text-right',
							},  
							{
								"targets": [ 7 ],
								"orderable": false,
							},  
						],
		
		// fnDrawCallback	: function(nRow, aData, iDisplayIndex) {
		// 	$('#table tbody tr').hover(function() {
		// 		$(this).addClass('highlight');
		// 	}, function() {
		// 		$(this).removeClass('highlight');
		// 	});
		// },

		footerCallback: function (row, data, start, end, display) {
			var api = this.api();
	
			// Remove the formatting to get integer data for summation
			var intVal = function (i) {
				return typeof i === 'string'
					? i.replace(/[\$,]/g, '') * 1
					: typeof i === 'number'
					? i
					: 0;
			};
	
			// Total over all pages
			total = api
				.column(3)
				.data()
				.reduce(function (a, b) {
					return intVal(a) + intVal(b);
				}, 0);
	
			// Total over this page
			pageTotal = api
				.column(3, { page: 'current' })
				.data()
				.reduce(function (a, b) {
					return intVal(a) + intVal(b);
				}, 0);
	
			// Update footer
			$(api.column(3).footer()).html(
				'Rp' + numberRenderer(pageTotal)
			);
		}

	});

});


function reload_table() {
	table.ajax.reload(null, false);
}

</script>
