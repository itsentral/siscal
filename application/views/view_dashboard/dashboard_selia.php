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
			  <input type="hidden" id="set_status" value="PENDING">
              <ul class="nav nav-pills nav-stacked" id="filterDashboard">
                <li class="group-li active" id="PENDING"><a href="javascript:void(0)"><i class="fa fa-inbox"></i> Pending <span class="label label-danger pull-right"><?php echo $countPending;?></span></a></li>
                <li class="group-li" id="REVISI"><a href="javascript:void(0)"><i class="fa fa-file-text-o"></i> Revisi Teknisi <span class="label label-warning pull-right"><?php echo $countRevisi;?></span></a></li>
                <li class="group-li" id="SELESAI"><a href="javascript:void(0)"><i class="fa fa-file-text-o"></i> Ready To Print <span class="label label-success pull-right"><?php echo $countSelesai;?></span></a></li>
                <li class="group-li" id="PRINT"><a href="javascript:void(0)"><i class="fa fa-envelope-o"></i> Print (pdf) <span class="label label-primary pull-right"><?php echo $countPrint;?></span></a></li>
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

			<div class="box box-danger box-color-d">
				<div class="box-header with-border">
				<h3 class="box-title">LIST FILE <text id="titleList">PENDING</text></h3>
				</div>

				<div class="box-body no-padding">
					<div class="table-responsive col-sm-12" style="padding-bottom:60px;padding-top:25px;">
						<table id="table-pending" class="table table-bordered table-striped" width="100%">
							<thead class="thead-cs" style="background-color:#E9ECF9;color:#0A1A60;">
								<tr style="font-size: 11px;height: 40px;">
									<th>KODE</th>
									<th>CUSTOMER</th>
									<!-- <th>ALAMAT SO</th> -->
									<th>NO SO</th>
									<th>NAMA ALAT</th>
									<th>ID NUMBER</th>
									<th>S/N NUMBER</th>
									<th>TEKNISI</th>
									<th>MT</th>
									<th>LATE</th>
									<th>FILE</th>
								</tr>
							</thead>
							<tbody id="list_selia" style="font-size: 10.8px;">
							</tbody>
						</table>
					</div>
				</div>
			</div>

        </div>
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

	.highlight {
		color: #3c8dbc;
		/* cursor: pointer; */
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

	.Btn-cs2 {
		background-color: #d9534f;
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
	var tableDashboard;

	donut = new Morris.Donut({
      element: 'selia-chart',
      resize: true,
      colors: ["#dd4b39", "#f39c12", "#00a65a", "#3c8dbc"],
      data: [
        {label: "Pending", value: <?php echo $countPending;?>},
        {label: "Revisi", value: <?php echo $countRevisi;?>},
        {label: "Ready To Print", value: <?php echo $countSelesai;?>},
        {label: "Print (pdf)", value: <?php echo $countPrint;?>}
      ],
      hideHover: 'auto'
    });

	tableDashboard = $('#table-pending').DataTable({
		processing		: true,
		serverSide		: true,
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

		ajax			: {
							"url"	: "<?php echo site_url('dashboard_selia/list_func_selia') ?>",
							"type"	: "POST",
							"data"	: function (data) {
										var set_status = $('#set_status').val();
										data.status_dashboard = set_status;
									}
						},
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

		columnDefs	: [ 
							{
								// "targets": [ 0,3,5,6,7,8,9 ],
								"targets": [ 0,2,4,5,6,8,9 ],
								"className": 'text-center',
							}, 
							{
								// "targets": [ 9 ],
								"targets": [ 9 ],
								"orderable": false,
							},  
						],
		
		fnDrawCallback: function(nRow, aData, iDisplayIndex) {
			$('#table-pending tbody tr').hover(function() {
				$(this).addClass('highlight');
			}, function() {
				$(this).removeClass('highlight');
			});
			$('#table-pending tbody tr').each(function(){
				$(this).find('td:eq(8)').attr('nowrap', 'nowrap');
			});
		}

	});

	function viewDetail(id) {
		$('#formAddress')[0].reset();
		$('#code_sch').attr('readonly', true);
		$('#tk_name').attr('readonly', true);
		$('#cs_name').attr('readonly', true);
		$('#alamat_so').attr('readonly', true);

		$.ajax({
			url: "<?php echo site_url('dashboard_selia/getById') ?>/" + id,
			type: "GET",
			dataType: "JSON",
			success: function(data) {
				$('[name="code_sch"]').val(data.id);
				$('[name="tk_name"]').val(data.actual_teknisi_name);
				$('[name="cs_name"]').val(data.customer_name);
				$('[name="alamat_so"]').val(data.address_so);
				$('#closeModal').text('Close');
				$('#FormModal').modal('show');

			},
			error: function(jqXHR, textStatus, errorThrown) {
				alert('Error get data from ajax');
			}
		});
	}

	$('.reload-table').click(function(){
		tableDashboard.ajax.reload();
	});

	$("#filterDashboard li").click(function() {
		var idDashboard = this.id;

		$('li.group-li.active').removeClass("active");  
		$(this).addClass("active");
		
		$('.box-color-d').removeClass("box-danger");
		$('.box-color-d').removeClass("box-warning");
		$('.box-color-d').removeClass("box-success");
		$('.box-color-d').removeClass("box-primary");

		if(idDashboard == "PENDING"){
			$('.box-color-d').addClass("box-danger");
		}
		if(idDashboard == "REVISI"){
			$('.box-color-d').addClass("box-warning");
		}
		if(idDashboard == "SELESAI"){
			$('.box-color-d').addClass("box-success");
		}
		if(idDashboard == "PRINT"){
			$('.box-color-d').addClass("box-primary");
		}
		

		$('#titleList').html(idDashboard);

		var status = $('#set_status').val(idDashboard);
		tableDashboard.draw();
	});


</script>
