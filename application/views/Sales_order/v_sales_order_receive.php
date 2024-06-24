<?php
$this->load->view('include/side_menu');
date_default_timezone_set('Asia/Jakarta');
?>
<form action="#" method="POST" id="form-proses" enctype="multipart/form-data">
	<div class="box box-primary box-cs01">
		<div class="box-header">
			<h4 class="box-title"><i class="fa fa-check-square"></i> <?php echo $title; ?></h4>

		</div>
		<!-- /.box-header -->
		<div class="box-body">
			<div class="">
				<div class="col-sm-3">
					<div class="form-group">
						<label class="control-label">
							<strong>Month</strong>
						</label>
						<div>
							<select name="bulan" id="bulan" class="form-control select2" style="width:100%;">
								<option value="">- Choose Month -</option>
								<?php
								$array_bulan = array(
									'1'    => 'January', '2'   => 'February', '3'   => 'March', '4'   => 'April', '5'   => 'May', '6'   => 'June', '7'   => 'July', '8'   => 'August', '9'   => 'September', '10'   => 'October', '11'   => 'November', '12'   => 'December'
								);

								foreach ($array_bulan as $key => $value) {
									$selected = '';
									if ($key == date('n')) {
										$selected = 'selected';
									}
									echo '<option value="' . $key . '" ' . $selected . '>' . $value . '</option>';
								}
								?>
							</select>
						</div>

					</div>
				</div>
				<div class="col-sm-2">
					<div class="form-group">

						<label class="control-label">
							<strong>Year</strong>
						</label>
						<div>
							<select name="tahun" id="tahun" class="form-control select2" style="width:100%;">
								<option value="">- Choose Year -</option>
								<?php
								for ($i = date('Y'); $i >= 2018; $i--) {
									$selected = '';
									if (date('Y') == $i) {
										$selected = 'selected';
									}
									echo '<option value="' . $i . '" ' . $selected . '>' . $i . '</option>';
								}
								?>
							</select>
						</div>

					</div>
				</div>
				<div class="col-sm-3">
					<div class="form-group">
						<label class="control-label">
							<strong>Status</strong>
						</label>
						<div>
							<select name="sts_so" id="sts_so" class="form-control select2" style="width:100%;">
								<option value="">- Choose Status -</option>
								<?php
								$array_Status = array(
									'OPN'    => 'OPEN', 'CNC'   => 'CANCELED', 'REV'   => 'REVISION', 'SCH'   => 'SCHEDULED', 'PSCH'   => 'PARTIAL SCHEDULED', ''
								);

								foreach ($array_Status as $key => $value) {
									$selected = '';

									echo '<option value="' . $key . '" ' . $selected . '>' . $value . '</option>';
								}
								?>
							</select>
						</div>

					</div> <?php
							if ($akses_menu['create'] == '1') {
							?>
						<!-- <div class="col-sm-6">
							<div class="form-group">
								<label class="control-label">
									&nbsp;
								</label>
								<div>
									<button type="button" class="btn btn-sm bg-navy-active" id="btn_add_order" title="CREATE SO - WAREHOUSE RECEIVE"> CREATE SO - WAREHOUSE RECEIVE <i class="fa fa-plus"></i> </button>
									&nbsp;&nbsp;<button type="button" class="btn btn-sm bg-maroon-active" id="btn_add_driver" title="CREATE SO - DRIVER RECEIVE"> CREATE SO - DRIVER RECEIVE <i class="fa fa-plus"></i> </button>
								</div>
							</div>
						</div> -->
					<?php
							}
					?>
				</div>

				<div class="col-sm-12 table-responsive" style="margin-top:20px;">
				<div id="Loading_tes" class="overlay_load">
					<center>Please Wait . . . &nbsp;<img src="<?php echo base_url('assets/img/loading_small.gif') ?>"></center>
				</div>
				<table id="my-grid" class="table table-bordered table-striped">
					<thead style="background-color:#E9ECF9;color:#0A1A60;">
						<tr>
							<th class="text-center">SO No</th>
							<th class="text-center">SO Date</th>
							<th class="text-center">Customer</th>
							<th class="text-center">Quotation</th>
							<th class="text-center">PO No</th>
							<th class="text-center">Type</th>
							<th class="text-center">Status</th>
							<th class="text-center">Option</th>
						</tr>
					</thead>

					<tbody id="list_detail">

					</tbody>

				</table>
				</div>
			</div>

			<!-- /.box-body -->
		</div>
		<div class="modal fade" id="MyModalView" tabindex="-1" role="dialog" aria-labelledby="MyModal" data-backdrop="static">
			<div class="modal-dialog" role="document" style="min-width:70% !important;">
				<div class="modal-content">
					<div class="modal-header">
						<h5 class="modal-title" id="MyModalTitle"></h5>
						<button class="close" data-dismiss="modal" aria-label="close" id="btn-modal-close">
							<span aria-hidden="true"><i class="fa fa-close"></i></span>
						</button>
					</div>
					<div class="modal-body" id="MyModalDetail">

					</div>
				</div>
			</div>
		</div>
		<?php $this->load->view('include/footer'); ?>
		<!-- page script -->
		<style>
			.overlay_load {
				background: #eee;
				display: none;
				position: absolute;
				top: 0;
				right: 0;
				bottom: 0;
				left: 0;
				padding-top: 40%;
				opacity: 0.7;
				z-index: 2;
			}

			.text-center {
				text-align: center !important;
				vertical-align: middle !important;
			}

			.text-left {
				text-align: left !important;
				vertical-align: middle !important;
			}

			.text-right {
				text-align: right !important;
				vertical-align: middle !important;
			}

			.text-wrap {
				word-wrap: break-word !important;
			}

			table.table-bordered thead th,
			table.table-bordered thead td {
				border-left-width: thin !important;
				border-top-width: 0;
			}

			.chosen-container-single .chosen-single{
				height: 31px;
				line-height: 31px;
			}

			/* Start Css Box */
				.box-cs01{
					border-radius: 18px;
					box-shadow: 0 14px 28px rgba(0,0,0,0.25), 0 10px 10px rgba(0,0,0,0.22);
				}
			/* End Css Box */

			/* Start Css Table */
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
					width: auto;
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
				}

				.Btntable2 {
					background-color: #f39c12 !important;
					color: white !important;
				}

				.Btntable3 {
					background-color: #00a65a !important;
					color: white !important;
				}

				.highlight {
					color: #3c8dbc;
				}

			/* End Css Table */
		</style>

		<link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.4.2/css/buttons.dataTables.min.css">
		<script src="https://cdn.datatables.net/buttons/2.4.2/js/dataTables.buttons.min.js"></script>
		<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
		<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
		<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
		<script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.html5.min.js"></script>

		<script type="text/javascript">
			var base_url = '<?php echo site_url(); ?>';
			var active_controller = '<?php echo ($this->uri->segment(1)); ?>';
			$(function() {
				data_display();


			});
			$(document).on('change', '#bulan', data_display);
			$(document).on('change', '#tahun', data_display);
			$(document).on('change', '#sts_so', data_display);

			function ActionPreview(ObjectParam) {
				let TitleAction = ObjectParam.title;
				let CodeAction = ObjectParam.code;
				let LinkAction = ObjectParam.action;

				loading_spinner_new();

				$('#MyModalTitle').text(TitleAction);
				$.post(base_url + '/' + active_controller + '/' + LinkAction, {
					'code': CodeAction
				}, function(response) {
					close_spinner_new();
					$("#MyModalDetail").html(response);
				});
				$("#MyModalView").modal('show');
			}




			function data_display() {
				let MonthChosen = $('#bulan').val();
				let YearChosen = $('#tahun').val();
				let StatusChosen = $('#sts_so').val();
				let table_data = $('#my-grid').DataTable({
					"serverSide"	: true,
					"destroy"		: true,
					"processing"	: true,
					"stateSave"		: false,
					"bAutoWidth"	: false,
					"oLanguage"	: {
						"sSearch"			: "<b>Live Search : </b>",
						"sLengthMenu"		: "_MENU_ &nbsp;&nbsp;<b>Records Per Page</b>&nbsp;&nbsp;",
						"sInfo"				: "Showing _START_ to _END_ of _TOTAL_ entries",
						"sInfoFiltered"		: "(filtered from _MAX_ total entries)",
						"sZeroRecords"		: "No matching records found",
						"sEmptyTable"		: "No data available in table",
						"sLoadingRecords"	: "Please wait - loading...",
						"oPaginate": {
							"sPrevious"	: "Prev",
							"sNext"		: "Next"
						}
					},
					"aaSorting": [
						[1, "desc"]
					],
					"columnDefs": [{
							"targets": 0,
							"sClass": "text-center"
						},
						{
							"targets": 1,
							"sClass": "text-center"
						},
						{
							"targets": 2,
							"sClass": "text-left text-wrap"
						},
						{
							"targets": 3,
							"sClass": "text-center"
						},
						{
							"targets": 4,
							"sClass": "text-center"
						},
						{
							"targets": 5,
							"sClass": "text-center",
							"searchable": false,
							"orderable": false
						},
						{
							"targets": 6,
							"sClass": "text-center",
							"searchable": false,
							"orderable": false
						},
						{
							"targets": 7,
							"sClass": "text-center",
							"searchable": false,
							"orderable": false
						}
					],
					"sPaginationType"	: "simple_numbers",
					"iDisplayLength"	: 5,
					"aLengthMenu": [
						[5, 10, 20, 50, 100, 150],
						[5, 10, 20, 50, 100, 150]
					],
					dom				: 'Bfrtip', 
					buttons			: [
						{
							extend: 'pageLength',
							text:      '<i class="fa fa-list-ol"></i> <b>Show</b>',
							className: "Btntable"
						},
						<?php if ($akses_menu['download'] == '1') { ?>
						{
							extend: 'excelHtml5',
							text:      '<i class="fa fa-download fa-lg"></i> &nbsp;<b>Download Excel</b>',
							titleAttr: 'Excel',
							className: "Btntable",
							title: 'Sales Order - Receive - <?php echo date('d-m-Y h:i');?>',
							messageTop: 'SISCAL DASHBOARD',
							exportOptions: {
									columns: [0,1,2,3,4,5,6]
							}
						},
						<?php } ?>
						<?php if ($akses_menu['create'] == '1') { ?>
						{
							text:      '<i class="fa fa-plus fa-lg"></i> <b>Warehouse Receive</b>',
							className: "Btntable Btntable1",
							attr: {
								title: 'CREATE SO - WAREHOUSE RECEIVE',
								id: 'btn_add_order'
							}
						},
						{
							text:      '<i class="fa fa-truck fa-lg"></i> <b>Driver Receive</b>',
							className: "Btntable Btntable2",
							attr: {
								title: 'CREATE SO - DRIVER RECEIVE',
								id: 'btn_add_driver'
							}
						},
						<?php } ?>

					],
					"ajax": {
						url: base_url + '/' + active_controller + '/get_data_display',
						type: "post",
						cache: false,
						data: {
							'bulan': MonthChosen,
							'tahun': YearChosen,
							'sts_so': StatusChosen
						},
						error: function() {
							$(".my-grid-error").html("");
							$("#my-grid").append('<tbody class="my-grid-error"><tr><th colspan="8">No data found in the server</th></tr></tbody>');
							$("#my-grid_processing").css("display", "none");
						}
					},
					fnDrawCallback: function(nRow, aData, iDisplayIndex) {
						$('#my-grid tbody tr').hover(function() {
							$(this).addClass('highlight');
						}, function() {
							$(this).removeClass('highlight');
						});
						$('#my-grid tbody tr').each(function(){
							$(this).find('td:eq(7)').attr('nowrap', 'nowrap');
						});
					}
				});
			}

			$(document).on('click', '#btn_add_order', () => {
				loading_spinner_new();
				window.location.href = base_url + '/' + active_controller + '/outs_letter_order_receive';
			});

			$(document).on('click', '#btn_add_driver', () => {
				loading_spinner_new();
				window.location.href = base_url + '/' + active_controller + '/outs_letter_order_driver';
			});


			$(document).on('click', '#btn_cancel_order', async (e) => {
				e.preventDefault();
				$('#btn-modal-close, #btn_cancel_order').prop('disabled', true);

				let CancelReason = $('#cancel_reason').val();

				const ValueCheck = {
					'alasan': {
						'nilai': CancelReason,
						'error': 'Empty Cancel Reason. Please input reason first..'
					}
				};




				try {
					const ResultCheck = await GeneralCheckEmptyValue(ValueCheck);
					const ResultConfirm = await GeneralShowConfirmSave();
					const formData = new FormData($('#form_proses_driver_spk')[0]);
					const ParamProcess = {
						'action': 'save_cancel_sales_order',
						'parameter': formData,
						'loader': 'loader_proses_save'
					};
					const Hasil_Bro = await GeneralAjaxProcessData(ParamProcess);

					if (Hasil_Bro.status == '1') {
						GeneralShowMessageError('success', Hasil_Bro.pesan);
						window.location.href = base_url + '/' + active_controller;
					} else {
						GeneralShowMessageError('error', Hasil_Bro.pesan);
						$('#btn-modal-close, #btn_cancel_order').prop('disabled', false);
						return false;
					}
				} catch (error) {
					GeneralShowMessageError('error', error.message);
					$('#btn-modal-close, #btn_cancel_order').prop('disabled', false);
					return false;
				}
			});
		</script>
