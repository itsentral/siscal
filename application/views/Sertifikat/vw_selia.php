<?php
$this->load->view('include/side_menu'); 
?> 
   
<div class="box box-primary box-cs01">

	<div class="box-body">
		<div class="col-sm-12" style="padding-bottom:12px;margin-top: 15px">
			<!-- <h4>Filter Data:</h4> -->
		</div>
		<div class="table-responsive col-sm-12" style="padding-bottom:65px;">
		<form action="#" id="selectFile" method="POST">
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
						<th>LATE</th>
						<th>ACTION</th>
					</tr>
				</thead>
				<tbody id="list_selia" style="font-size: 12px;">
				</tbody>
			</table>
		</form>
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
				<h4 class="modal-title">Detail Customer</h4>
			</div>
			<div class="modal-body">
			<form action="#" id="formAddress" method="POST">
				<div class="form-group">
					<label>Code SCH</label>
					<input class="form-control input" name="code_sch" id ="code_sch">
					<span class="help-block"></span>
				</div>
				<div class="form-group">
					<label>Nama Teknisi</label>
					<input class="form-control input" name="tk_name" id ="tk_name">
					<span class="help-block"></span>
				</div>
				<div class="form-group">
					<label>Nama Customer</label>
					<textarea class="form-control input" name="cs_name" id ="cs_name" rows=2>
					</textarea>
					<span class="help-block"></span>
				</div>
				<div class="form-group">
					<label>Alamat SO</label>
					<textarea class="form-control input" name="alamat_so" id ="alamat_so" rows=4>
					</textarea>
					<span class="help-block"></span>
				</div>

			</div>
			<div class="modal-footer">
				<button type="button" class="btn Btn-cs Btn-cs2" data-dismiss="modal"><i class="glyphicon glyphicon-remove"></i> <text id="closeModal"></text></button>
				<button type="button" class="btn Btn-cs Btn-cs1" id="btnCopy" onClick="BtnCopy();"><i class="fa fa-copy"></i> Copy Alamat</button>
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
				<h4 class="modal-title">FORM SELIA</h4>
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
					<label class="control-label">STATUS<text style="color:red;">*</text></label>
					<select class="form-control" name="status_selia" id="status_selia" style="width:100%">
						<option value="">==PILIH==</option>
						<option value="REVISI">Revisi</option>
						<option value="SELESAI">Selesai</option>
					</select>
				</div>

				<div class="form-group fileSelia">
					<label class="control-label">File Selia<text style="color:red;">*</text></label>
					<input type="file" class="form-control input sm" name="file_selia_1" id ="file_selia_1">
					<span class="help-block"></span>
				</div>

				<div class="form-group">
					<label class="control-label">CATATAN MT</label>
					<textarea class="form-control input" name="catatan_mt" id ="catatan_mt" rows=4></textarea>
					<span class="help-block"></span>
				</div>

			</div>
			<div class="modal-footer">
				<button type="button" class="btn Btn-cs Btn-cs2" data-dismiss="modal"><i class="glyphicon glyphicon-remove"></i> <text id="closeModalSelia"></text></button>
				<button type="submit" class="btn Btn-cs Btn-cs1" id="btnSave"><i class="fa fa-save"></i> Update</button>
			</div>	
			</form>
		</div>
	</div>
</div>

<div class="modal fade" id="FormModalUpload" tabindex="-1" role="dialog" aria-labelledby="FormModal" data-backdrop="static">
	<div class="modal-dialog modal-dialog-centered" style="min-width:60% !important;">
		<div class="modal-content modal-cs">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
				<span aria-hidden="true"><i class="fa fa-close"></i></span></button>
				<h4 class="modal-title">Upload File Selia</h4>
			</div>
			<form action="#" id="formUpload" method="POST">
			<div class="modal-body">

				<div id="fileUpload"></div>
				<br/>
				<text style="color:red;">*<i>data yang akan diupload akan hilang dari list table dan dianggap telah diselia</i></text>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn Btn-cs Btn-cs2" data-dismiss="modal"><i class="glyphicon glyphicon-remove"></i> Close</button>
				<button type="submit" class="btn Btn-cs Btn-cs1" id="btnUpload"><i class="fa fa-upload"></i> Upload</button>
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
		width: auto;
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
	.inputfileUpload{
		display: none;
	}
</style>


<?php $this->load->view('include/footer'); ?>
<link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.4.2/css/buttons.dataTables.min.css">
<link rel="stylesheet" href="<?php echo base_url("assets/fileUpload/fileUpload.css")?>">
<script src="https://cdn.datatables.net/buttons/2.4.2/js/dataTables.buttons.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.html5.min.js"></script>
<!-- <script src="<?php echo base_url("assets/fileUpload/fileUpload.js")?>"></script> -->
<script>
var table;
var save_method;

$(function() {
    $('.fileSelia').hide(); 
    $('#status_selia').change(function(){
        if($('#status_selia').val() == 'SELESAI') {
            $('.fileSelia').show(); 
        } else {
            $('.fileSelia').hide(); 
        } 
    });
});

$(document).ready(function() {
	const today = new Date();
	const yyyy = today.getFullYear();
	let mm = today.getMonth() + 1; // Months start at 0!
	let dd = today.getDate();
	if (dd < 10) dd = '0' + dd;
	if (mm < 10) mm = '0' + mm;
	const formattedToday = dd + '-' + mm + '-' + yyyy;

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
		oLanguage: {
			oPaginate: {
				sNext: '<i class="fa fa-chevron-circle-right fa-lg"></i>',
				sPrevious: '<i class="fa fa-chevron-circle-left fa-lg"></i>'
			}
		},
		//pagingType: "simple", //"simple, simple_numbers, full"

		ajax			: {
							"url"	: "<?php echo site_url('selia/list_func_selia') ?>",
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
							
							// {
							// 	extend: 'excelHtml5',
							// 	text:      '<i class="fa fa-download fa-lg"></i> &nbsp;<b>Excel</b>',
							// 	titleAttr: 'Excel',
							// 	className: "Btntable",
							// 	title: 'Master Alat Kalibrasi - Data Per '+formattedToday,
							// 	messageTop: 'SISCAL DASHBOARD',
							// 	exportOptions: {
							// 			columns: [0,1,2,3,4,5]
							// 	}
							// },

							{
								text:      '<i class="fa fa-download fa-lg"></i> &nbsp;<b>Download</b>',
								className: "Btntable Btntable1 saveFile",
							},

							{
								text:      '<i class="fa fa-upload fa-lg"></i> &nbsp;<b>Upload</b>',
								className: "Btntable Btntable2 uploadFile",
							},

						],

		columnDefs	: [ 
							{
								"targets": [ 1,4,6,7,9,10 ],
								"className": 'text-center',
							}, 
							{
								"targets": [ 0,10 ],
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
				$(this).find('td:eq(9)').attr('nowrap', 'nowrap');
				$(this).find('td:eq(10)').attr('nowrap', 'nowrap');
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
	
	
	// else{
	// 	$('.saveFile').html('<i class="fa fa-download fa-lg"></i> &nbsp;<b>Loading...</b>');
	// 	$('.saveFile').attr('disabled', true);

	// 	var formData = new FormData($('#selectFile')[0]);
	// 	$.ajax({
	// 		url: "<?php echo site_url('selia/downloadFile') ?>",
	// 		type: "POST",
	// 		data: formData,
	// 		processData: false,
    //    	 	contentType: false,
	// 		success: function(data) {
				
	// 			alert(data.msg);
	// 			$('.saveFile').html('<i class="fa fa-download fa-lg"></i> &nbsp;<b>Download</b>');
	// 			$('.saveFile').attr('disabled', false);
	// 			table.ajax.reload(null, false);


	// 		},
	// 		error: function(jqXHR, textStatus, errorThrown) {
	// 			alert('Download gagal, hubungin Administrator!');
	// 			$('.saveFile').html('<i class="fa fa-download fa-lg"></i> &nbsp;<b>Download</b>');
	// 			$('.saveFile').attr('disabled', false);
	// 			table.ajax.reload(null, false);

	// 		}
	// 	});
	// }
});

$(document).on("click", ".uploadFile", function (e) {
	e.preventDefault();
	
	var userGroup = '<?php echo $this->session->userdata('siscal_group_id');?>';
	if(userGroup=='1' || userGroup=='10'){
		const file = document.querySelector('.fileUploadBatch');
            		 file.value = '';

		$(".fileList > tbody").empty();
		
		if ($(".fileList > tbody").find("tr").length === 0) {
			$(".fileList > tbody").append(
				'<tr><td colspan="6" class="no-file">No files selected!</td></tr>'
			);
		}

		$('#formUpload')[0].reset();
		$('#FormModalUpload').modal('show')
	}else{
		alert('Mohon Maaf fitur ini hanya bisa digunakan oleh MT!');
	}
	

});

function viewDetail(id) {
	$('#formAddress')[0].reset();
	$('#code_sch').attr('readonly', true);
	$('#tk_name').attr('readonly', true);
	$('#cs_name').attr('readonly', true);
	$('#alamat_so').attr('readonly', true);

	$.ajax({
		url: "<?php echo site_url('selia/getById') ?>/" + id,
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

function seliaData(id) {
	$('#formSelia')[0].reset();
	$('#kode').attr('readonly', true);
	$('.fileSelia').hide();

	$.ajax({
		url: "<?php echo site_url('selia/getById') ?>/" + id,
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
	let file_selia		= $('#file_selia_1').val();

	var ValueCheck;
	
	if(status_selia == 'SELESAI'){
		ValueCheck	= {
			'status_selia':{'nilai':status_selia,'error':'Empty Status. Please input reason first..'},
			'file_selia_1':{'nilai':file_selia,'error':'Empty File Selia. Please input reason first..'}
		};
	}else{
		ValueCheck	= {
			'status_selia':{'nilai':status_selia,'error':'Empty Status. Please input reason first..'}
		};
	}

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
		url: "<?php echo site_url('selia/update_func_selia') ?>",
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
			$('#btnSave').html('<i class="fa fa-save"></i> Update');
			$('#btnSave').attr('disabled', false);


		},
		error: function(jqXHR, textStatus, errorThrown) {
			alert('Error adding / update data');
			$('#btnSave').html('<i class="fa fa-save"></i> Update');
			$('#btnSave').attr('disabled', false);

		}
	});
});

$("#formUpload").submit(async(e)=> {
	e.preventDefault();

	$('#btnUpload').html('<i class="fa fa-spinner"></i> Uploading...');
	$('#btnUpload').attr('disabled', true);

	let file_seliaBatch		= $('#fileUpload-1').val();

	var ValueCheck;

	ValueCheck	= {
		'file_selia_batch':{'nilai':file_seliaBatch,'error':'File Selia Kosong, Mohon upload File untuk Update data Selia Anda...'}
	};

	try{
		const ResultCheck	= await GeneralCheckEmptyValue(ValueCheck);
	}catch(error){
		GeneralShowMessageError('error',error.message);
		$('#btnUpload').html('<i class="fa fa-upload"></i> Upload');
		$('#btnUpload').attr('disabled', false);
		return false;
	}
	var formDataBatch = new FormData($('#formUpload')[0]);
	$.ajax({
		url: "<?php echo site_url('selia/upload_seliaBatch') ?>",
		type: "POST",
		data: formDataBatch,
		contentType: false,
		processData: false,
		dataType: "JSON",
		success: function(data) {

			if (data.status)
			{
				$('#FormModalUpload').modal('hide');
				alert(data.msg);
				reload_table();
			} else {
				alert(data.msg);
			}
			$('#btnUpload').html('<i class="fa fa-upload"></i> Upload');
			$('#btnUpload').attr('disabled', false);


		},
		error: function(jqXHR, textStatus, errorThrown) {
			alert('Error adding / upload data');
			$('#btnUpload').html('<i class="fa fa-upload"></i> Upload');
			$('#btnUpload').attr('disabled', false);

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


//FUNGSI DRAG & DROP FILE
(function ($) {
	var fileUploadCount = 0;

	$.fn.fileUpload = function () {
		return this.each(function () {
			var fileUploadDiv = $(this);
			var fileUploadId = `fileUpload-${++fileUploadCount}`;

			var fileDivContent = `
                <label for="${fileUploadId}" class="file-upload">
                    <div>
                        <i class="fa fa-cloud-upload"></i> 
						<text style="font-size:26px;" class="ya">Upload File Selia</text>
                        <!--<p>Drag & Drop Files Here</p>-->
                        <!--<span>OR</span>-->
                        <div>Browse Files</div>
						<div class="inputfileUpload">
							<input type="file" id="${fileUploadId}" class="fileUploadBatch" name=file_selia_batch[] multiple="multiple" hidden />
						</div>
                    	
					</div>
                </label>
            `;

			fileUploadDiv.html(fileDivContent).addClass("file-container");

			var table = null;
			var tableBody = null;

			function createTable() {
				table = $(`
                    <table class="fileList">
                        <thead>
                            <tr>
                                <th></th>
                                <th style="width: 60%;">File Name</th>
                                <!--<th>Preview</th>-->
                                <th style="width: 30%;">Size</th>
                                <!--<th>Type</th>-->
                                <!--<th></th>-->
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                `);

				tableBody = table.find("tbody");
				fileUploadDiv.append(table);
			}

			function handleFiles(files) {
				if (!table) {
					createTable();
				}

				tableBody.empty();
				if (files.length > 0) {
					$.each(files, function (index, file) {
						var fileName = file.name;
						var fileSize = (file.size / 1024).toFixed(2) + " KB";
						var fileType = file.type;
						var preview = fileType.startsWith("image")
							? `<img src="${URL.createObjectURL(
									file
							  )}" alt="${fileName}" height="30">`
							: `<i class="fa fa-eye-slash">None</i>`;

						tableBody.append(`
                            <tr>
                                <td>${index + 1}</td>
                                <td>${fileName}</td>
                                <!--<td>${preview}</td>-->
                                <td>${fileSize}</td>
                                <!--<td>${fileType}</td>-->
                                <!--<td><button type="button" class="deleteBtn"><i class="fa fa-trash"></i></button></td>-->
                            </tr>
                        `);
					});

					tableBody.find(".deleteBtn").click(function () {
						$(this).closest("tr").remove();

						if (tableBody.find("tr").length === 0) {
							tableBody.append(
								'<tr><td colspan="6" class="no-file">No files selected!</td></tr>'
							);
						}
					});
				}
			}

			// Events triggered after dragging files.
			fileUploadDiv.on({
				dragover: function (e) {
					e.preventDefault();
					fileUploadDiv.toggleClass("dragover", e.type === "dragover");
				},
				drop: function (e) {
					e.preventDefault();
					fileUploadDiv.removeClass("dragover");
					handleFiles(e.originalEvent.dataTransfer.files);
				},
			});

			// Event triggered when file is selected.
			fileUploadDiv.find(`#${fileUploadId}`).change(function () {
				handleFiles(this.files);
			});
		});
	};
})(jQuery);

$(document).ready(function () {
	$("#fileUpload").fileUpload();
});
</script>
