<?php
$this->load->view('include/side_menu');
?>
<form action="#" method="POST" id="form-proses" enctype="multipart/form-data">
	<div class="box box-primary box-cs01">
		<div class="box-header">
			<div class="box-tools pull-left">
				<?php
				echo "<button type='button' class='btn Btn-cs bg-red' id='btn-back'> <i class='fa fa-angle-double-left'></i>&nbsp; BACK &nbsp;&nbsp;</button>&nbsp;&nbsp;&nbsp;";
				?>
			</div>
		</div>
		<div class="box-body">
			<div class="col-sm-12">
				<h4 class="title-cs"><i class="fa fa-list fa-md"></i> <?php echo $title;?></h4>
				<hr/>
			</div>

			<?php
			if (empty($rows_header)) {
				echo "<div class='row'>
						<div class='col-sm-12'>
							<h4 class='text-red'><b>NO RECORD WAS FOUND.....</b></h4>
						</div>
					</div>";
			} else {

			?>
				<div class=''>
					<div class="col-sm-6">
						<div class="form-group">
							<label class="control-label">SO No</label>
							<?php
							echo form_input(array('id' => 'nomor_so', 'name' => 'nomor_so', 'class' => 'form-control input-sm', 'readOnly' => true), $rows_header[0]->no_so);
							echo form_input(array('id' => 'code_process', 'name' => 'code_process', 'type' => 'hidden'), $code_process);
							?>
						</div>
					</div>
					<div class="col-sm-6">
						<div class="form-group">
							<label class="control-label">SO Date</label>
							<?php
							echo form_input(array('id' => 'tgl_so', 'name' => 'tgl_so', 'class' => 'form-control input-sm', 'readOnly' => true), date('d-m-Y', strtotime($rows_header[0]->tgl_so)));
							?>
						</div>
					</div>
				</div>
				<div class=''>
					<div class="col-sm-6">
						<div class="form-group">
							<label class="control-label">Customer</label>
							<?php
							echo form_input(array('id' => 'customer_name', 'name' => 'customer_name', 'class' => 'form-control input-sm', 'readOnly' => true), $rows_header[0]->customer_name);
							?>
						</div>
					</div>
					<div class="col-sm-6">
						<div class="form-group">
							<label class="control-label">Quotation</label>
							<?php
							echo form_input(array('id' => 'nomor_quotation', 'name' => 'nomor_quotation', 'class' => 'form-control input-sm', 'readOnly' => true), $rows_header[0]->quotation_nomor);
							?>
						</div>
					</div>

				</div>
				<div class=''>
					<div class="col-sm-6">
						<div class="form-group">
							<label class="control-label">PO No</label>
							<?php
							echo form_input(array('id' => 'pono', 'name' => 'pono', 'class' => 'form-control input-sm', 'readOnly' => true), $rows_header[0]->pono);
							?>
						</div>
					</div>
					<div class="col-sm-6">
						<div class="form-group">
							<label class="control-label">Technician</label>
							<?php
							echo form_input(array('id' => 'teknisi', 'name' => 'teknisi', 'class' => 'form-control input-sm', 'readOnly' => true), strtoupper($rows_member[0]->nama));
							?>
						</div>
					</div>
				</div>
				<div class=''>
					<div class="col-sm-6">
						<div class="form-group">
							<label class="control-label">Tgl SPK</label>
							<?php
							echo form_input(array('id' => 'spk_date', 'name' => 'spk_date', 'class' => 'form-control input-sm', 'readOnly' => true), $rows_header[0]->datet);
							?>
						</div>
					</div>
					<div class="col-sm-6">
						<div class="form-group">
							<label class="control-label">Alamat Sertifikat</label>
							<textarea class="form-control" rows=4 readonly><?php if(!empty($row_letter)) echo $row_letter->address_sertifikat; ?></textarea>
						</div>
					</div>

				</div>

				<div class="col-sm-12">
					<h4 class="title-cs"><i class="fa fa-user fa-md"></i> PIC LAB CUSTOMER</h4>
					<hr/>
				</div>

				<div class=''>
					<div class="col-sm-6">
						<div class="form-group">
							<label class="control-label">Name</label>
							<?php
							echo form_input(array('id' => 'pic_lab', 'name' => 'pic_lab', 'class' => 'form-control input-sm', 'readOnly' => true), $rows_cust->lab);
							?>
						</div>
					</div>
					<div class="col-sm-6">
						<div class="form-group">
							<label class="control-label">Phone</label>
							<?php
							echo form_input(array('id' => 'pic_lab_hp', 'name' => 'pic_lab_hp', 'class' => 'form-control input-sm', 'readOnly' => true), strtoupper($rows_cust->lab_hp));
							?>
						</div>
					</div>
				</div>
				<div class=''>
					<div class="col-sm-6">
						<div class="form-group">
							<label class="control-label">Email</label>
							<?php
							echo form_input(array('id' => 'lab_email', 'name' => 'lab_email', 'class' => 'form-control input-sm', 'readOnly' => true), $rows_cust->lab_email);
							?>
						</div>
					</div>
					<div class="col-sm-6">
						&nbsp;
					</div>
				</div>

				<div class="col-sm-12 text-left" style="margin-bottom:-10px;">
					<h4 class="title-cs"><i class="fa fa-check-square-o fa-md"></i> <u>TOOL DETAIL</u></h4>
					<hr/>
				</div>

				<div class="">
				<div class="table-responsive col-sm-12" style="padding-bottom:65px;padding-top:10px;">
						<table class="table table-striped table-bordered" id="my-grid" width="100%">
							<thead style="background-color:#E9ECF9;color:#0A1A60;">
								<tr>
									<th class="text-center">Code</th>
									<th class="text-center">Tool Name</th>
									<th class="text-center">Range</th>
									<th class="text-center">Result</th>
									<th class="text-center">Real Technician</th>
									<th class="text-center">Request Cust</th>
									<th class="text-center">Description</th>
									<th class="text-center">Action</th>
								</tr>
							</thead>
							<tbody id="list_sertifikat">
								<?php

								if ($rows_header) {
									$Arr_Location	= array(1 => 'Client', 'Labs', 'Fine Good', 'Cawang');
									foreach ($rows_header as $ketK => $valK) {
										$Code_SO		= $valK->letter_order_id;
										$Code_Teknisi	= $valK->member_id;
										$Date_Teknisi	= $valK->datet;
										$Code_Alat		= $valK->tool_id;
										$Name_Alat		= $valK->tool_name;
										$ID_Alat		= $valK->id;
										$Schedule_Code	= $valK->trans_detail_id;
										$Labs			= $valK->labs;
										$Insitu			= $valK->insitu;
										$Subcon			= $valK->subcon;
										$Location		= $valK->location;
										$Cal_Result		= $valK->flag_proses;
										$Cal_Reschedule	= $valK->plan_reschedule;
										$Tool_Descr		= $valK->keterangan;

										$File_Before		= $valK->before_cals_image;
										$File_After			= $valK->after_cals_image;
										$Allow_Take			= $valK->take_image;

										$Tool_Range			= $valK->range . ' ' . $valK->piece_id;
										$Tool_Quot_Detail	= $valK->quotation_detail_id;
										$Tool_Descr			= $valK->so_descr;

										$rows_Quot_Detail	= $this->db->get_where('quotation_details', array('id' => $Tool_Quot_Detail))->row();

										$Real_Technician = $valK->actual_teknisi_name;

										$Code_Unik		= $Code_SO . '^' . $Code_Teknisi . '^' . $ID_Alat . '^' . $Date_Teknisi;

										$OK_Update		= 0;
										if ($Labs == 'N') {
											$OK_Update	= 1;
										} else {
											if (in_array($Location, $Arr_Location)) {
												$OK_Update	= 1;
											}
										}
										$Add_Print	= '';
										if ($Cal_Result === 'Y') {
											$Status		= "<span class='badge bg-green'>SUCCESS</span>";
											if (!empty($valK->sentral_code_tool) && $valK->sentral_code_tool !== '-') {
												$Add_Print  = '&nbsp;&nbsp;<button type="button" onClick="PrintBarcode(\'' . $ID_Alat . '\')" class="btn btn-sm btn-warning" title="PRINT CALIBRATIONS BARCODE"> <i class="fa fa-print"></i> </button>';
											}
										} else if ($Cal_Result === 'N') {
											if ($Cal_Reschedule === 'Y') {
												$Status		= "<span class='badge bg-light-blue'>RESCHEDULE</span>";
											} else {
												$Status		= "<span class='badge bg-red'>FAIL / CANCEL</span>";
											}
										} else {
											if ($Cal_Reschedule === 'Y') {
												$Status		= "<span class='badge bg-blue'>PLAN RESCHEDULE</span>";
											} else {
												$Status		= "<span class='badge bg-purple'>UNPROCESSED</span>";
											}
										}

										if ($Cal_Result == '') {
											if ($Cal_Reschedule == 'N' && $OK_Update == 1) {
												$Template = '<button type="button" class="btn btn-sm bg-navy-active" onClick = "ActionPreview({code:\'' . $Code_Unik . '\',action :\'calibration_result_process\',title:\'UPDATE CALIBRATION RESULT\'});" title="UPDATE CALIBRATION RESULT"> <i class="fa fa-arrow-right fa-lg"></i> </button>';
												if ((empty($Allow_Take) || $Allow_Take == 'Y') && (empty($File_Before) || $File_Before == '-')) {
													$Template .= '&nbsp;&nbsp;<button type="button" class="btn btn-sm bg-blue-active" onClick = "ActionPreview({code:\'' . $Code_Unik . '\',action :\'take_image_before_calibration\',title:\'UPLOAD IMAGE TOOL BEFORE CALIBRATION\'});" title="UPDATE IMAGE TOOL BEFORE CALIBRATION"> <i class="fa fa-upload fa-lg"></i> </button>';
												}
											} else {
												$Template	= "<span class='badge bg-purple'>UNPROCESSED RESCHEDULE</span>";
											}
										} else {
											$Template	= "<span class='badge bg-maroon'>DONE</span>";
										}

										if (!empty($File_Before) && $File_Before !== '-') {
											if (!empty($Template)) $Template	.= '&nbsp;&nbsp;';
											$Template	.= '<a href="' . $this->file_attachement . 'hasil_kalibrasi/' . $File_Before . '" target="_blank" class="btn btn-sm blue_grey" title="DOWNLOAD BEFORE CALIBRATION IMAGE"> <i class="fa fa-download"></i> </a>';
										}

										if (!empty($File_After) && $File_After !== '-') {
											if (!empty($Template)) $Template	.= '&nbsp;&nbsp;';
											$Template	.= '<a href="' . $this->file_attachement . 'hasil_kalibrasi/' . $File_After . '" target="_blank" class="btn btn-sm brown" title="DOWNLOAD AFTER CALIBRATION IMAGE"> <i class="fa fa-download"></i> </a>';
										}

										echo "<tr>";
										echo "<td class='text-center'>" . $ID_Alat . "</td>";
										echo "<td class='text-left'>" . $Name_Alat . "</td>";
										echo "<td class='text-center'>" . $Tool_Range . "</td>";
										echo "<td class='text-center'>";
										echo $Status;
										echo "</td>";
										echo "<td class='text-center'>" . $Real_Technician . "</td>";
										echo "<td class='text-left'>" . $rows_Quot_Detail->descr . "</td>";
										echo "<td class='text-left'>" . $Tool_Descr . "</td>";
										echo "<td class='wide text-center'>" . $Template . $Add_Print . "</td>";
										echo "</tr>";
									}
								}
								?>
							</tbody>
						</table>
					</div>
				</div>
			<?php
			}
			?>

		</div>


	</div>
</form>
<div class="modal fade" id="MyModalView" tabindex="-1" role="dialog" aria-labelledby="MyModal" data-backdrop="static">
	<div class="modal-dialog" role="document" style="min-width:70% !important;">
		<div class="modal-content modal-cs">
			<div class="modal-header">
				<button class="close" data-dismiss="modal" aria-label="close" id="btn-modal-close">
					<span aria-hidden="true"><i class="fa fa-close"></i></span>
				</button>
				<h4 class="modal-title" id="MyModalTitle">UPDATE CALIBRATION</h4>
			</div>
			<div class="modal-body" id="MyModalDetail">

			</div>
		</div>
	</div>
</div>
<?php $this->load->view('include/footer'); ?>
<style>
/* Start Css Button */
	.Btn-cs {
		font-size: 14px;
		padding: 7px;
		margin: 4px;
		margin-bottom: 0px !important;
		border-radius: 8px;
		width: auto;
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
/* End Css Button */
	.title-cs{
		padding-top:20px;
		font-weight: bold;
	}
	.box-cs01{
		border-radius: 18px;
		box-shadow: 0 14px 28px rgba(0,0,0,0.25), 0 10px 10px rgba(0,0,0,0.22);
	}
	.modal-cs{
		-webkit-border-radius: 20px !important;
		-moz-border-radius: 20px !important;
		border-radius: 20px !important; 
	}

	table.dataTable tbody td {
		vertical-align: middle;
	}
	table.dataTable thead th {
		text-align: center;
		vertical-align: middle;
	}

	.dataTables_filter {
		float: right !important;
	}

	.highlight {
		color: #3c8dbc;
		/* cursor: pointer; */
	}

	td.wide {
		white-space: nowrap;;
	}
	.sub-heading {
		border-radius: 5px;
		background-color: #03506F;
		color: white;
		margin: 20px 10px 15px 10px !important;
		width: 98% !important;
	}

	.modal {
		overflow: auto !important;
	}

	.blue_grey {
		background-color: #37474f !important;
		color: #fff !important;
	}

	.brown {
		background-color: #5d4037 !important;
		color: #fff !important;
	}

	.amber {
		background-color: #ff6f00 !important;
		color: #fff !important;
	}
</style>
<script>
	var base_url = '<?php echo site_url(); ?>';
	var active_controller = '<?php echo ($this->uri->segment(1)); ?>';
	var _validFileExtensions = [".xls", ".xlsx", ".xlsm", ".xlsxm"];
	var _validFileExtensions2 = [".jpg", ".jpeg", ".png", ".gif", ".bmp", ".JPEG", ".JPG"];
	var table;

	$(document).ready(function() {

		$('#btn-back').click(function() {
			loading_spinner();
			window.location.href = base_url + '/' + active_controller;
		});

		table = $('#my-grid').DataTable({
			paging		: false,
			ordering	: false,
			//dom			: '<"top"f>rt<"bottom"lp><"clear">',
			language	: {
							// "search": "_INPUT_",
							"searchPlaceholder": "Cari Tool Detail..."
						},

			fnDrawCallback: function(nRow, aData, iDisplayIndex) {
				$('#my-grid tbody tr').hover(function() {
					$(this).addClass('highlight');
				}, function() {
					$(this).removeClass('highlight');
				});
			}
		});

	});

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

	$(document).on('click', '#btn-process-reopen', (e) => {
		e.preventDefault();
		$('#btn-modal-close, #btn-process-reopen').prop('disabled', true);
		let Code_Back = $('#code_back').val();
		let Cal_Result = $('#flag_proses').val();
		let Subcon = $('#subcon').val();
		let CodeDetail = $('#code_detail').val();
		let CodeOrder = $('#code_so').val();
		let ImageFront = $('#pic_webcam_depan').val();
		let ImageBack = $('#pic_webcam_back').val();

		let UploadFront = $('#files_depan').val();
		let UploadBack = $('#files_back').val();

		let Plan_Reschedule = $('#plan_reschedule').val();
		let Allow_Take = $('#take_image').val();
		let ExistFront = $('#exist_depan').val();

		let OK_Check_File = 'Y';
		if (Cal_Result == 'N' || Allow_Take == 'N') {
			OK_Check_File = 'N';
		}

		if (Cal_Result == null || Cal_Result == '') {
			swal({
				title: "Error Message !",
				text: 'Empty Calibration Process. Please choose calibration process first...',
				type: "warning"
			});
			$('#btn-modal-close, #btn-process-reopen').prop('disabled', false);
			return false;
		}

		if (Allow_Take == null || Allow_Take == '') {
			swal({
				title: "Error Message !",
				text: 'Empty Take Picture Permission. Please Choose Take Picture Permission First..',
				type: "warning"
			});
			$('#btn-modal-close, #btn-process-reopen').prop('disabled', false);
			return false;
		}

		if (OK_Check_File == 'Y') {

			if ((ImageFront == '' || ImageFront == null) && (UploadFront == '' || UploadFront == null) && ExistFront == 'N') {
				swal({
					title: "Error Message !",
					text: 'Empty Before Cals Picture. Please Tak Picture or Upload Image first..',
					type: "warning"
				});
				$('#btn-modal-close, #btn-process-reopen').prop('disabled', false);
				return false;

			}

			if ((ImageBack == '' || ImageBack == null) && (UploadBack == '' || UploadBack == null)) {
				swal({
					title: "Error Message !",
					text: 'Empty After Cals Picture. Please Tak Picture or Upload Image first..',
					type: "warning"
				});
				$('#btn-modal-close, #btn-process-reopen').prop('disabled', false);
				return false;

			}
		}

		if (Cal_Result == 'Y') {
			if (Subcon == 'Y') {
				let Actual_Subcon = $('#actual_subcon').val();
				if (Actual_Subcon == null || Actual_Subcon == '') {
					swal({
						title: "Error Message !",
						text: 'Empty Actual Subcon. Please choose actual subcon first...',
						type: "warning"
					});
					$('#btn-modal-close, #btn-process-reopen').prop('disabled', false);
					return false;
				}
			} else {
				let Actual_Teknisi = $('#actual_teknisi').val();
				if (Actual_Teknisi == null || Actual_Teknisi == '') {
					swal({
						title: "Error Message !",
						text: 'Empty Actual Technician. Please choose actual technician first...',
						type: "warning"
					});
					$('#btn-modal-close, #btn-process-reopen').prop('disabled', false);
					return false;
				}
			}

			let Datet 			= $('#tgl_proses').val();
			let TimeStart 		= $('#jam_awal').val();
			let TimeEnd 		= $('#jam_akhir').val();
			let ToolIdentify 	= $('#no_identifikasi').val();
			let SerialNumber 	= $('#no_serial_number').val();
			let Lampiran_File 	= $('#lampiran_kalibrasi').val();
			let Selia			= $('#id_selia').val();

			if (Datet == null || Datet == '' || Datet == '-') {
				swal({
					title: "Error Message !",
					text: 'Empty Calibration Date. Please input calibration date first...',
					type: "warning"
				});
				$('#btn-modal-close, #btn-process-reopen').prop('disabled', false);
				return false;
			}

			if (TimeStart == null || TimeStart == '' || TimeStart == '00:00') {
				swal({
					title: "Error Message !",
					text: 'Empty Calibration Start Time. Please input calibration start time first...',
					type: "warning"
				});
				$('#btn-modal-close, #btn-process-reopen').prop('disabled', false);
				return false;
			}

			if (TimeEnd == null || TimeEnd == '' || TimeEnd == '00:00') {
				swal({
					title: "Error Message !",
					text: 'Empty Calibration End Time. Please input calibration end time first...',
					type: "warning"
				});
				$('#btn-modal-close, #btn-process-reopen').prop('disabled', false);
				return false;
			}

			if (TimeEnd < TimeStart) {
				swal({
					title: "Error Message !",
					text: 'Incorrect Calibration Time...',
					type: "warning"
				});
				$('#btn-modal-close, #btn-process-reopen').prop('disabled', false);
				return false;
			}

			if (ToolIdentify == null || ToolIdentify == '' || ToolIdentify == '-') {
				swal({
					title: "Error Message !",
					text: 'Empty Tool Identification No. Please input tool identification no first...',
					type: "warning"
				});
				$('#btn-modal-close, #btn-process-reopen').prop('disabled', false);
				return false;
			}

			if (SerialNumber == null || SerialNumber == '' || SerialNumber == '-') {
				swal({
					title: "Error Message !",
					text: 'Empty Tool Serial Number No. Please input tool serial number no first...',
					type: "warning"
				});
				$('#btn-modal-close, #btn-process-reopen').prop('disabled', false);
				return false;
			}

			if (Lampiran_File == null || Lampiran_File == '') {
				swal({
					title: "Error Message !",
					text: 'Empty Calibration result file. Please upload calibration result file first...',
					type: "warning"
				});
				$('#btn-modal-close, #btn-process-reopen').prop('disabled', false);
				return false;
			}

			if (Selia == null || Selia == '') {
				swal({
					title: "Error Message !",
					text: 'Empty Penyelia. Please input penyelia first...',
					type: "warning"
				});
				$('#btn-modal-close, #btn-process-reopen').prop('disabled', false);
				return false;
			}

		} else {
			let Fail_Reason = $('#failed_reason').val();
			if (Fail_Reason == null || Fail_Reason == '' || Fail_Reason == '-') {
				swal({
					title: "Error Message !",
					text: 'Empty Failed Reason. Please input failed reason first...',
					type: "warning"
				});
				$('#btn-modal-close, #btn-process-reopen').prop('disabled', false);
				return false;
			}
		}



		swal({
				title: "Are you sure?",
				text: "You will not be able to process again this data!",
				type: "warning",
				showCancelButton: true,
				confirmButtonClass: "btn-danger",
				confirmButtonText: "Yes, Process it!",
				cancelButtonText: "No, cancel process!",
				closeOnConfirm: true,
				closeOnCancel: false
			},
			function(isConfirm) {
				if (isConfirm) {
					loading_spinner_new();
					var formData = new FormData($('#form-proses-reopen')[0]);
					var baseurl = base_url + '/' + active_controller + '/save_calibration_result_process';
					$.ajax({
						url: baseurl,
						type: "POST",
						data: formData,
						cache: false,
						dataType: 'json',
						processData: false,
						contentType: false,
						success: function(data) {
							close_spinner_new();
							if (data.status == 1) {
								swal({
									title: "Save Success!",
									text: data.pesan,
									type: "success"
								});
								window.location.href = base_url + '/' + active_controller + '/view_detail?kode=' + encodeURIComponent(Code_Back);
							} else {
								swal({
									title: "Save Failed!",
									text: data.pesan,
									type: "warning"
								});
								//alert(data.pesan);
								$('#btn-modal-close, #btn-process-reopen').prop('disabled', false);
								return false;

							}
						},
						error: function() {
							close_spinner_new();
							swal({
								title: "Error Message !",
								text: 'An Error Occured During Process. Please try again..',
								type: "warning"
							});
							$('#btn-modal-close, #btn-process-reopen').prop('disabled', false);
							return false;
						}
					});

				} else {
					close_spinner_new();
					swal("Cancelled", "Data can be process again :)", "error");
					$('#btn-modal-close, #btn-process-reopen').prop('disabled', false);
					return false;
				}
			});
	});

	function ValidateSingleInput(oInput) {
		if (oInput.type == "file") {
			var sFileName = oInput.value;
			if (sFileName.length > 0) {
				var blnValid = false;
				for (var j = 0; j < _validFileExtensions.length; j++) {
					var sCurExtension = _validFileExtensions[j];
					if (sFileName.substr(sFileName.length - sCurExtension.length, sCurExtension.length).toLowerCase() == sCurExtension.toLowerCase()) {
						blnValid = true;
						break;
					}
				}

				if (!blnValid) {
					swal({
						title: "Error Message !",
						text: 'Hanya boleh pilih jenis file EXCEL....',
						type: "warning"
					});

					oInput.value = "";
					return false;
				}
			}
		}
		return true;
	}

	function ValidateSingleInput2(oInput) {
		if (oInput.type == "file") {
			var sFileName = oInput.value;
			if (sFileName.length > 0) {
				var blnValid = false;
				for (var j = 0; j < _validFileExtensions2.length; j++) {
					var sCurExtension = _validFileExtensions[j];
					if (sFileName.substr(sFileName.length - sCurExtension.length, sCurExtension.length).toLowerCase() == sCurExtension.toLowerCase()) {
						blnValid = true;
						break;
					}
				}

				if (!blnValid) {
					swal({
						title: "Error Message !",
						text: 'Hanya boleh pilih jenis file Image....',
						type: "warning"
					});

					oInput.value = "";
					return false;
				}
			}
		}
		return true;
	}

	const PrintBarcode = (Code_Print) => {
		loading_spinner_new();
		$.post(base_url + '/' + active_controller + '/print_barcode_nonQR_tool', {
			'code': Code_Print
		}, function(response) {
			close_spinner_new();
			//console.log(response);
			const datas = $.parseJSON(response);
			window.open(datas.path, '_blank');
		});
	};

	$(document).on('click', '#btn-process-upload', async (e) => {
		e.preventDefault();
		$('#btn-modal-close, #btn-process-upload').prop('disabled', true);

		let Code_Back = $('#code_back').val();
		let Take_Picture = $('#take_image').val();
		let CodeDetail = $('#code_detail').val();
		let ImageFront = $('#pic_webcam_depan').val();
		let ImageBack = $('#pic_webcam_back').val();

		let UploadFront = $('#files_depan').val();

		let OK_Check_File = 'Y';
		if (Take_Picture == 'N') {
			OK_Check_File = 'N';
		}

		const ValueCheck = {
			'flag_allow': {
				'nilai': Take_Picture,
				'error': 'Empty Take Picture Permission. Please Choose Take Picture Permission First..'
			}
		};




		if (OK_Check_File == 'Y') {

			if ((ImageFront == '' || ImageFront == null) && (UploadFront == '' || UploadFront == null)) {
				ValueCheck['file'] = {
					'nilai': '',
					'error': 'Empty Before Cals Picture. Please Tak Picture or Upload Image first..'
				};
			}

		}




		try {
			const ResultCheck = await GeneralCheckEmptyValue(ValueCheck);
			const ResultConfirm = await GeneralShowConfirmSave();
			const formData = new FormData($('#form-proses-upload')[0]);
			const ParamProcess = {
				'action': 'save_take_image_before_calibration',
				'parameter': formData,
				'loader': 'loader_proses_save'
			};
			const Hasil_Bro = await GeneralAjaxProcessData(ParamProcess);

			if (Hasil_Bro.status == '1') {
				GeneralShowMessageError('success', Hasil_Bro.pesan);
				window.location.href = base_url + '/' + active_controller + '/view_detail?kode=' + encodeURIComponent(Code_Back);
			} else {
				GeneralShowMessageError('error', Hasil_Bro.pesan);
				$('#btn-modal-close, #btn-process-upload').prop('disabled', false);
				return false;
			}
		} catch (error) {
			GeneralShowMessageError('error', error.message);
			$('#btn-modal-close, #btn-process-upload').prop('disabled', false);
			return false;
		}


	});
</script>
