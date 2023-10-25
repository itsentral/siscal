<?php
$this->load->view('include/side_menu');
?>
<form action="#" method="POST" id="form-proses" enctype="multipart/form-data">
	<div class="box box-warning">
		<div class="box-header">
			<h4 class="box-title"><i class="fa fa-check-square"></i> GENERATE INVOICE FULL PO</h4>

		</div>
		<!-- /.box-header -->
		<div class="box-body">
			<div class="row">
				<div class="col-sm-4">
					<div class="form-group">
						<label class="control-label">
							<strong>Customer</strong>
						</label>
						<div>
							<select name="customer" id="customer" class="form-control chosen-select">
								<option value=""> - SELECT AN OPTION - </option>
								<?php

								foreach ($rows_customer as $keyC => $valC) {
									echo '<option value="' . $keyC . '">' . $valC . '</option>';
								}
								?>
							</select>
						</div>

					</div>
				</div>
				<div class="col-sm-4">
					<div class="form-group">
						<label class="control-label">&nbsp;</label>
						<div>
							<button type='button' class='btn btn-md bg-red-active' id='btn_kembali'> <i class='fa fa-arrow-left'></i> BACK TO LIST </button>
							&nbsp;&nbsp;
							<button type='button' class='btn btn-md bg-green-active' id='btn_process_inv'> PROCESS INVOICE <i class='fa fa-arrow-right'></i> </button>
						</div>
					</div>
				</div>

			</div>

			<div id="Loading_tes" class="overlay_load">
				<center>Please Wait . . . &nbsp;<img src="<?php echo base_url('assets/img/loading_small.gif') ?>"></center>
			</div>
			<table id="my-grid" class="table table-bordered table-striped">
				<thead>
					<tr class="bg-navy-blue">
						<th class="text-center"><input type="checkbox" id="chk_all" name="chk_all"></th>
						<th class="text-center">Quotation No</th>
						<th class="text-center">Quotation Date</th>
						<th class="text-center">Customer</th>
						<th class="text-center">PO No</th>
						<th class="text-center">Marketing</th>
						<th class="text-center">SO No</th>
						<th class="text-center">SO Date</th>
						<!-- <th class="text-center">Late Days</th> -->
						<th class="text-center">Action</th>
					</tr>
				</thead>

				<tbody id="list_outs_inv">

				</tbody>

			</table>
		</div>

		<!-- /.box-body -->
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
			vertical-align: midle !important;
		}

		.ui-datepicker-calendar {
			display: none;
		}

		.bg-navy-blue {
			background-color: #16697A !important;
			color: #ffffff !important;
		}
	</style>
	<script type="text/javascript">
		var base_url = '<?php echo site_url(); ?>';
		var active_controller = '<?php echo ($this->uri->segment(1)); ?>';

		$(function() {
			data_display();
		});
		$(document).on('click', '#btn_kembali', () => {
			loading_spinner();
			window.location.href = base_url + '/' + active_controller;
		});

		$(document).on('click', '#chk_all', () => {
			if ($('#chk_all').is(':checked')) {
				$('#list_outs_inv input[type="checkbox"]').prop('checked', true);
			} else {
				$('#list_outs_inv input[type="checkbox"]').prop('checked', false);
			}
		});


		$(document).on('change', '#customer', data_display);

		function data_display() {
			let CustChosen = $('#customer').val();
			let table_data = $('#my-grid').DataTable({
				"serverSide": true,
				"destroy": true,
				"stateSave": false,
				"bAutoWidth": false,
				"oLanguage": {
					"sSearch": "<b>Live Search : </b>",
					"sLengthMenu": "_MENU_ &nbsp;&nbsp;<b>Records Per Page</b>&nbsp;&nbsp;",
					"sInfo": "Showing _START_ to _END_ of _TOTAL_ entries",
					"sInfoFiltered": "(filtered from _MAX_ total entries)",
					"sZeroRecords": "No matching records found",
					"sEmptyTable": "No data available in table",
					"sLoadingRecords": "Please wait - loading...",
					"oPaginate": {
						"sPrevious": "Prev",
						"sNext": "Next"
					}
				},
				"aaSorting": [
					[2, "desc"]
				],
				"columnDefs": [{
						"targets": 0,
						"sClass": "text-center",
						"searchable": false,
						"orderable": false
					},
					{
						"targets": 1,
						"sClass": "text-center"
					},
					{
						"targets": 2,
						"sClass": "text-center"
					},
					{
						"targets": 3,
						"sClass": "text-left"
					},
					{
						"targets": 4,
						"sClass": "text-center"
					},
					{
						"targets": 5,
						"sClass": "text-center"
					},
					{
						"targets": 6,
						"sClass": "text-center",
						"searchable": false,
						"orderable": false,
					},
					{
						"targets": 7,
						"sClass": "text-center",
						"searchable": false,
						"orderable": false
					},
					{
						"targets": 8,
						"sClass": "text-center",
						"searchable": false,
						"orderable": false,
					},
					// {
					// 	"targets": 9,
					// 	"sClass": "text-center",
					// 	"searchable": false,
					// 	"orderable": false
					// }
				],
				"sPaginationType": "simple_numbers",
				"iDisplayLength": 10,
				"aLengthMenu": [
					[5, 10, 20, 50, 100, 150],
					[5, 10, 20, 50, 100, 150]
				],
				"ajax": {
					url: base_url + '/' + active_controller + '/outstanding_full_po',
					type: "post",
					data: {
						'nocust': CustChosen
					},
					cache: false,
					beforeSend: function() {
						$('#Loading_tes').show();
					},
					complete: function() {
						$('#Loading_tes').hide();
					},
					error: function() {
						$(".my-grid-error").html("");
						$("#my-grid").append('<tbody class="my-grid-error"><tr><th colspan="9">No data found in the server</th></tr></tbody>');
						$("#my-grid_processing").css("display", "none");
					}
				}
			});
		}

		function view_order(kode) {
			$('#Mymodal-detail').empty();

			var baseurl = base_url + '/' + active_controller + '/detail_outs_invoice';
			$.ajax({
				url: baseurl,
				type: "POST",
				data: {
					'kode_process': kode,
					'flag_so': 'N'
				},
				beforeSend: function() {
					$('#Loading_tes').show();
				},
				success: function(data) {
					$('#Mymodal-title').html('Detail Outstanding Invoice');
					$('#Mymodal-list').html(data);
					$('#Mymodal').modal('show');
				},
				complete: function() {
					setTimeout(function() {
						$('#Loading_tes').hide();
					}, 1500);
				},
				error: function() {
					swal({
						title: "Error Message !",
						text: 'An Error Occured During Process. Please try again..',
						type: "warning"
					});
					return false;
				}

			});


		}

		function CreateInvoice(CodeQuot) {
			loading_spinner();
			let Link_Process = base_url + '/' + active_controller + '/invoicing_process?code_inv=' + encodeURIComponent(CodeQuot) + '&flag_order=N';
			window.location.href = Link_Process;
		}



		$(document).on('click', '#btn_process_inv', (e) => {
			e.preventDefault();
			let ChosenCustomer = $('#customer').val();
			if (ChosenCustomer == '' || ChosenCustomer == null) {
				swal({
					title: "Warning !",
					text: 'Empty Customer. Please choose customer first',
					type: "warning"
				});
				return false;
			}


			let JumChosen = $('#list_outs_inv').find('input[type="checkbox"]:checked').length;

			if (parseInt(JumChosen) <= 0 || JumChosen == '') {
				swal({
					title: "Warning !",
					text: 'No record was selected. Please choose at least one record....',
					type: "warning"
				});
				return false;
			}

			const ChosenOrder = [];
			$('#list_outs_inv').find('input[type="checkbox"]:checked').each(function() {
				ChosenOrder.push($(this).val());
			});

			let CodeTerpilih = ChosenOrder.join('^');

			loading_spinner();
			let Link_Process = base_url + '/' + active_controller + '/invoicing_process?code_inv=' + encodeURIComponent(CodeTerpilih) + '&flag_order=N';
			window.location.href = Link_Process;
		});
	</script>