<?php $this->load->view('include/side_menu'); ?>

<div class="row">
        <div class="col-md-3">
          <a href="#" onClick="window.location.reload();" class="btn Btn-cs Btn-cs1 btn-block margin-bottom"><i class="fa fa-refresh"></i> Reload Page</a>
		  <br/>
          <div class="box box-solid">
            <div class="box-header with-border">
              <h3 class="box-title">Selia</h3>

              <div class="box-tools">
                <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                </button>
              </div>
            </div>
            <div class="box-body no-padding">
              <ul class="nav nav-pills nav-stacked">
                <li class="active"><a href="#"><i class="fa fa-inbox"></i> Pending <span class="label label-danger pull-right">102</span></a></li>
                <li><a href="#"><i class="fa fa-envelope-o"></i> Ready To Print <span class="label label-warning pull-right">65</span></a></li>
                <li><a href="#"><i class="fa fa-file-text-o"></i> Selesai <span class="label label-success pull-right">105</span></a></li>
              </ul>
            </div>
          </div>
          <div class="box box-solid">
            <div class="box-header with-border">
              <h3 class="box-title">Chart Selia</h3>

              <div class="box-tools">
                <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                </button>
              </div>
            </div>
            <div class="box-body no-padding chart-responsive">
				<div class="chart" id="selia-chart" style="height: 300px; position: relative;"></div>
            </div>
            <!-- /.box-body -->
          </div>
          <!-- /.box -->
        </div>
        <!-- /.col -->
        <div class="col-md-9">
          <div class="box box-primary">
            <div class="box-header with-border">
              <h3 class="box-title">List Selia Pending</h3>
            </div>
            <!-- /.box-header -->
            <div class="box-body no-padding">
             
            </div>
            <!-- /.box-body -->
            <div class="box-footer no-padding">
				<div class="table-responsive col-sm-12" style="padding-bottom:65px;">
					<table id="table-pending" class="table table-bordered table-striped" width="100%">
						<thead class="thead-cs" style="background-color:#E9ECF9;color:#0A1A60;">
							<tr style="font-size: 13px;height: 50px;">
								<th width="19%">NO SO</th>
								<th width="32%">NAMA ALAT</th>
								<th width="14%">ID NUMBER</th>
								<th width="14%">S/N NUMBER</th>
								<th width="10%">DETAIL</th>
								<th width="9%">LATE</th>
							</tr>
						</thead>
						<tbody id="list_selia">
						</tbody>
					</table>
            	</div>
            </div>
          </div>
          <!-- /. box -->
        </div>
        <!-- /.col -->
      </div>

<?php $this->load->view('include/footer'); ?>

<style>
	table.dataTable tbody td {
		vertical-align: middle;
	}
	table.dataTable thead th {
		text-align: center;
		vertical-align: middle;
	}
	.dataTables_filter {
		padding-top: 10px;
	}
	.Btntable {
		font-size: 13.3px !important;
		padding: 6px !important;
		margin: 4px !important;
		margin-bottom: 10px !important;
		border-radius: 4px !important;
		width: 85px;
		border: none !important;
		box-shadow: 0 1px 2px rgba(0,0,0,0.07), 
                0 2px 4px rgba(0,0,0,0.07), 
                0 4px 8px rgba(0,0,0,0.07), 
                0 8px 16px rgba(0,0,0,0.07),
                0 16px 32px rgba(0,0,0,0.07), 
                0 32px 64px rgba(0,0,0,0.07);
	}
	.Btntable1 {
		background-color: #2F92E4 !important;
		color: white !important;
		width: 105px;
	}
	.Btntable2 {
		background-color: #FFAC05 !important;
		color: white !important;
		width: 95px;
	}

	.Btn-cs {
		font-size: 14px;
		padding: 7px;
		margin: 4px;
		margin-bottom: 0px !important;
		border-radius: 8px;
		width: 85px;
		border: none;
		box-shadow: 0 1px 2px rgba(0,0,0,0.07), 
                0 2px 4px rgba(0,0,0,0.07), 
                0 4px 8px rgba(0,0,0,0.07), 
                0 8px 16px rgba(0,0,0,0.07),
                0 16px 32px rgba(0,0,0,0.07), 
                0 32px 64px rgba(0,0,0,0.07);
	}
	.Btn-cs:hover {
		color: white;
		transition: all 150ms linear;
		opacity: .88;
	}
	.Btn-cs1 {
		width: auto;
		background-color: #f39c12;
		color: white;
	}

	.btn-copy {
		font-size: 12px;
		padding: 5.5px;
		border-radius: 8px;
		width: 75px;
		border: none;
		background-color: #D4D8DF;
		color: black;
		box-shadow: 0 1px 2px rgba(0,0,0,0.07), 
                0 2px 4px rgba(0,0,0,0.07), 
                0 4px 8px rgba(0,0,0,0.07), 
                0 8px 16px rgba(0,0,0,0.07),
                0 16px 32px rgba(0,0,0,0.07), 
                0 32px 64px rgba(0,0,0,0.07);
	}
	.btn-copy:hover {
		color: blue;
		transition: all 150ms linear;
		opacity: .88;
	}
</style>

<link rel="stylesheet" href="<?php echo base_url();?>assets/plugins/morris/morris.css">
<link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.4.2/css/buttons.dataTables.min.css">
<script src="https://cdn.datatables.net/buttons/2.4.2/js/dataTables.buttons.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.html5.min.js"></script>
<script src="<?php echo base_url();?>assets/plugins/raphael/raphael.min.js"></script>
<script src="<?php echo base_url();?>assets/plugins/morris/morris.min.js"></script>
<script>
	var donut;
	var tablePending;

	donut = new Morris.Donut({
      element: 'selia-chart',
      resize: true,
      colors: ["#dd4b39", "#f39c12", "#00a65a"],
      data: [
        {label: "Pending", value: 102},
        {label: "Ready To Print", value: 65},
        {label: "Selesai", value: 105}
      ],
      hideHover: 'auto'
    });

	tablePending = $('#table-pending').DataTable({
		// processing		: true,
		// serverSide		: true,
		paging			: true, 
		order			: [],
		//autoWidth		: false,
		lengthMenu		: [[5, 10, 25, 50, 100, -1], [5, 10, 25, 50, 100, "All"]],
		iDisplayLength	: 5,
		oLanguage: {
			oPaginate: {
				sNext: '<i class="fa fa-chevron-circle-right fa-lg"></i>',
				sPrevious: '<i class="fa fa-chevron-circle-left fa-lg"></i>'
			}
		},

		// ajax			: {
		// 					"url"	: "<?php echo site_url('selia/list_func_selia') ?>",
		// 					"type"	: "POST",
		// 				},
		dom				: 'Bfrtip', 
		buttons			: [
							{
								extend: 'pageLength',
								text:      '<i class="fa fa-list-ol"></i> <b>Show</b>',
								className: "Btntable Btn2"
							},

							{
								text:      '<i class="fa fa-refresh fa-lg"></i> &nbsp;<b>Reload</b>',
								className: "Btntable reload-table",
							},
							

						],

		// columnDefs	: [ 
		// 					{
		// 						"targets": [ 1,3,4,5,6,7 ],
		// 						"className": 'text-center',
		// 					}, 
		// 					{
		// 						"targets": [ 0,5,7 ],
		// 						"orderable": false,
		// 					}, 
		// 				],
		
		fnDrawCallback: function(nRow, aData, iDisplayIndex) {
			$('#table-pending tbody tr').hover(function() {
				$(this).addClass('highlight');
			}, function() {
				$(this).removeClass('highlight');
			});
		}

	});


</script>
