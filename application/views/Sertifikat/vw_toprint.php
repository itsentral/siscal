<?php
$this->load->view('include/side_menu'); 
?> 
   
<div class="box box-primary box-cs01">

	<div class="box-body">
		<div class="col-sm-12" style="padding-bottom:12px;margin-top: 15px">
			<!-- <h4>Filter Data:</h4> -->
		</div>
		<div class="table-responsive col-sm-12" style="padding-bottom:65px;">
			<table id="table-cs" class="table table-bordered table-striped" width="100%">
				<thead class="thead-cs" style="background-color:#E9ECF9;color:#0A1A60;">
					<tr style="font-size: 13px;height: 50px;">
						<th><input type="checkbox" id="chk_all" name="chk_all"></th>
						<th>KODE</th>
						<th>CUSTOMER</th>
						<th>ALAMAT SO</th>
						<th>NO SO</th>
						<th>NAMA ALAT</th>
						<th>ID NUMBER</th>
						<th>S/N NUMBER</th>
						<th>TEKNISI</th>
						<th>MT</th>
						<th>LATE</th>
						<th>ACTION</th>
					</tr>
				</thead>
				<tbody id="list_selia" style="font-size: 12px;">
				</tbody>
			</table>
		</div>
		<br/>
		<br/>
	</div>
</div>

<div class="modal fade" id="FormModal" tabindex="-1" role="dialog" aria-labelledby="FormModal" data-backdrop="static">
	<div class="modal-dialog modal-dialog-centered" style="min-width:30% !important;">
		<div class="modal-content modal-cs">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
				<span aria-hidden="true"><i class="fa fa-close"></i></span></button>
				<h4 class="modal-title">Alamat SO</h4>
			</div>
			<div class="modal-body">
			<form action="#" id="formAddress" method="POST">
				<div class="form-group">
					<textarea class="form-control input" name="alamat_so" id ="alamat_so" rows=4>
					</textarea>
					<span class="help-block"></span>
				</div>

			</div>
			<div class="modal-footer">
				<button type="button" class="btn Btn-cs Btn-cs2" data-dismiss="modal"><i class="glyphicon glyphicon-remove"></i> <text id="closeModal"></text></button>
				<button type="button" class="btn Btn-cs Btn-cs1" id="btnCopy" onClick="BtnCopy();"><i class="fa fa-copy"></i> Copy</button>
			</div>	
			</form>
		</div>
	</div>
</div>

<div class="modal fade" id="FormModalSelia" tabindex="-1" role="dialog" aria-labelledby="FormModal" data-backdrop="static">
	<div class="modal-dialog modal-dialog-centered" style="min-width:30% !important;">
		<div class="modal-content modal-cs">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
				<span aria-hidden="true"><i class="fa fa-close"></i></span></button>
				<h4 class="modal-title">CONFIRM UPDATE TO PRINT</h4>
			</div>
			<form action="#" id="formSelia" method="POST">
			<div class="modal-body">
				<input type="hidden" name="id" readonly>
				
				<div class="form-group">
					<label class="control-label">KODE</label>
					<input type="text" class="form-control input sm" name="kode" id ="kode">
					<span class="help-block"></span>
				</div>
				<div class="form-group">
					<label class="control-label">STATUS</label>
					<input type="text" class="form-control input sm" value="PRINT" name="status_selia" id ="status_selia" readonly>
					<span class="help-block"></span>
				</div>
				<text style="color:red;">*<i>data yang sudah diupdate akan hilang dari list table</i></text>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn Btn-cs Btn-cs2" data-dismiss="modal"><i class="glyphicon glyphicon-remove"></i> <text id="closeModalSelia"></text></button>
				<button type="submit" class="btn Btn-cs Btn-cs1" id="btnSave"><i class="fa fa-save"></i> Update</button>
			</div>	
			</form>
		</div>
	</div>
</div>

<style>
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
		width: auto;
	}

	.highlight {
		color: #3c8dbc;
		/* cursor: pointer; */
	}

/* End Css Table */

/* Start Css Button */
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
		background-color: #2F92E4;
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
/* End Css Button */

/* Start Css Modal */
	.modal-cs{
		-webkit-border-radius: 20px !important;
		-moz-border-radius: 20px !important;
		border-radius: 20px !important; 
	}
/* End Css Modal */
</style>


<?php $this->load->view('include/footer'); ?>

<link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.4.2/css/buttons.dataTables.min.css">
<script src="https://cdn.datatables.net/buttons/2.4.2/js/dataTables.buttons.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.html5.min.js"></script>
<script>
var table;
var save_method;

$(document).ready(function() {
	const today = new Date();
	const yyyy = today.getFullYear();
	let mm = today.getMonth() + 1; // Months start at 0!
	let dd = today.getDate();
	if (dd < 10) dd = '0' + dd;
	if (mm < 10) mm = '0' + mm;
	const formattedToday = dd + '-' + mm + '-' + yyyy;

	table = $('#table-cs').DataTable({
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
		//pagingType: "simple", //"simple, simple_numbers, full"

		ajax			: {
							"url"	: "<?php echo site_url('toprint/list_func_toprint') ?>",
							"type"	: "POST",
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

							{
								text:      '<i class="fa fa-download fa-lg"></i> &nbsp;<b>Download</b>',
								className: "Btntable Btntable1 saveFile",
							},

						],

		columnDefs	: [ 
						{
							"targets": [ 0,4,6,7,8,11 ],
							"className": 'text-center',
						}, 
						{
							"targets": [ 0, 11 ],
							"orderable": false,
						}, 
					],

		fnDrawCallback: function(nRow, aData, iDisplayIndex) {
			$('#table-cs tbody tr').hover(function() {
				$(this).addClass('highlight');
			}, function() {
				$(this).removeClass('highlight');
			});
			$('#table-cs tbody tr').each(function(){
				$(this).find('td:eq(4)').attr('nowrap', 'nowrap');
				$(this).find('td:eq(10)').attr('nowrap', 'nowrap');
				$(this).find('td:eq(11)').attr('nowrap', 'nowrap');
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


	$('.reload-table').click(function(){
		table.columns.adjust().draw();
		table.ajax.reload(null, false);
	});

});

$(document).on('click', '#chk_all', () => {
	if ($('#chk_all').is(':checked')) {
		$('#list_selia input[type="checkbox"]').prop('checked', true);
	} else {
		$('#list_selia input[type="checkbox"]').prop('checked', false);
	}
});

$(document).on("click", ".saveFile", function (e) {
	e.preventDefault();

	let slct = $('#list_selia').find('input[type="checkbox"]:checked').length;

	if (parseInt(slct) <= 0 || slct == '') {
		swal({
			title: "Warning !",
			text: 'No record was selected. Please choose at least one record....',
			type: "warning"
		});
		return false;
	}
	
	const ChosenOrder = [];
	
	$('#list_selia').find('input[type="checkbox"]:checked').each(function() {
		ChosenOrder.push($(this).val());
	});

	let CodeTerpilih = ChosenOrder.join('^');

	let Link_Process = base_url + '/' + active_controller + '/downloadFile?checkID=' + encodeURIComponent(CodeTerpilih);
	window.location.href = Link_Process;
	
});

function viewAddress(id) {
	$('#formAddress')[0].reset();
	$('#alamat_so').attr('readonly', true);

	$.ajax({
		url: "<?php echo site_url('toprint/getById') ?>/" + id,
		type: "GET",
		dataType: "JSON",
		success: function(data) {
			$('[name="alamat_so"]').val(data.address_so);
			$('#closeModal').text('Close');
			$('#FormModal').modal('show');

		},
		error: function(jqXHR, textStatus, errorThrown) {
			alert('Error get data from ajax');
		}
	});
}

function toprintData(id) {
	$('#formSelia')[0].reset();
	$('#kode').attr('readonly', true);

	$.ajax({
		url: "<?php echo site_url('toprint/getById') ?>/" + id,
		type: "GET",
		dataType: "JSON",
		success: function(data) {
			$('[name="id"]').val(data.id);
			$('[name="kode"]').val(data.id);
			$('#closeModalSelia').text('Close');
			$('#FormModalSelia').modal('show');
		},
		error: function(jqXHR, textStatus, errorThrown) {
			alert('Error get data from ajax');
		}
	});
}

$("#formSelia").submit(async(e)=> {
	e.preventDefault();

	$('#btnSave').text('saving...');
	$('#btnSave').attr('disabled', true);

	let status_selia	= $('#status_selia').val();
	const ValueCheck	= {
		'status_selia':{'nilai':status_selia,'error':'Empty Status. Please input reason first..'}
	};

	try{
		const ResultCheck	= await GeneralCheckEmptyValue(ValueCheck);
	}catch(error){
		GeneralShowMessageError('error',error.message);
		$('#btnSave').html('<i class="fa fa-save"></i> Update');
		$('#btnSave').attr('disabled', false);
		return false;
	}
	var formData = new FormData($('#formSelia')[0]);
	$.ajax({
		url: "<?php echo site_url('toprint/update_func_toprint') ?>",
		type: "POST",
		data: formData,
		contentType: false,
		processData: false,
		dataType: "JSON",
		success: function(data) {

			if (data.status)
			{
				$('#FormModalSelia').modal('hide');
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

function onProgres() {
	alert("Mohon Maaf, Action tersebut dalam proses pengerjaan!");
}

function isNumber(evt) {
	evt = (evt) ? evt : window.event;
	var charCode = (evt.which) ? evt.which : evt.keyCode;
	if (charCode > 31 && (charCode < 48 || charCode > 57)) {
		return false;
	}
	return true;
}

function BtnCopy() {
  var copyText = document.getElementById("alamat_so");

  copyText.select();
  copyText.setSelectionRange(0, 99999);
  navigator.clipboard.writeText(copyText.value);
  
  alert("Alamat SO Berhasil di Salin!");
  $('#FormModal').modal('hide');
}

function reload_table() {
	table.ajax.reload(null, false);
}
</script>
