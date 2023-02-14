<?php
$this->load->view('include/side_menu');
?>
<div class="box box-info box-solid">
	<div class="box-header">
		<h3 class="box-title">
			<i class="fa fa-clock-o"></i> <?php echo ('<span class="important">Dashboard Late Report (<b>' . date('H:i') . '</b>)</span>'); ?>
		</h3>
	</div>
	<div class="box-body">
		<div class='form-group row'>

			<div class='col-md-4'>
				<div class="info-box bg-purple">
					<span class="info-box-icon">
						<i class="fa fa-refresh"></i>
					</span>
					<div class="info-box-content">
						<span class="info-box-text">Calibration Process (Inlab)</span>
						<span class="info-box-number" id='process_late'>
							<?php
							$process_late	= "<a href='#' onClick='return view_late(\"2\");' style='color:white !important' id='link_late_process'>" . number_format($rows_data['late_kalibrasi']) . "</a>";
							echo $process_late;

							?>
						</span>
					</div>
				</div>
			</div>

			<div class='col-md-4'>
				<div class="info-box bg-maroon">
					<span class="info-box-icon">
						<i class="fa fa-refresh"></i>
					</span>
					<div class="info-box-content">
						<span class="info-box-text">Calibration Process (Insitu)</span>
						<span class="info-box-number" id='process_late'>
							<?php
							$process_late	= "<a href='#' onClick='return view_late(\"8\");' style='color:white !important' id='link_late_process_insitu'>" . number_format($rows_data['late_kalibrasi_insitu']) . "</a>";
							echo $process_late;
							?>
						</span>
					</div>
				</div>
			</div>

			<div class='col-md-4'>
				<div class="info-box bg-blue">
					<span class="info-box-icon">
						<i class="fa fa-send"></i>
					</span>
					<div class="info-box-content">
						<span class="info-box-text">Send To Subcon</span>
						<span class="info-box-number" id='late_subcon_send'>
							<?php
							$link3	= "<a href='#' onClick='return view_late(\"3\");' style='color:white !important' id='link_late_subcon_send'>" . number_format($rows_data['late_kirim_subcon']) . "</a>";
							echo $link3;

							?>
						</span>
					</div>
				</div>
			</div>

			<div class='col-md-4'>
				<div class="info-box bg-green">
					<span class="info-box-icon">
						<i class="fa fa-reply-all"></i>
					</span>
					<div class="info-box-content">
						<span class="info-box-text">Pick From Subcon</span>
						<span class="info-box-number" id='late_subcon_pick'>
							<?php
							$link4	= "<a href='#' onClick='return view_late(\"4\");' style='color:white !important' id='link_late_subcon_pick'>" . number_format($rows_data['late_ambil_subcon']) . "</a>";
							echo $link4;

							?>
						</span>
					</div>
				</div>
			</div>

			<div class='col-md-4'>
				<div class="info-box bg-yellow">
					<span class="info-box-icon">
						<i class="fa fa-truck"></i>
					</span>
					<div class="info-box-content">
						<span class="info-box-text">Send To Cust</span>
						<span class="info-box-number" id='send_cust_late'>
							<?php
							$send_cust_late	= "<a href='#' onClick='return view_late(\"5\");' style='color:white !important' id='link_send_late'>" . number_format($rows_data['late_kirim_cust']) . "</a>";
							echo $send_cust_late;

							?>
						</span>
					</div>
				</div>
			</div>

			<div class='col-md-4'>
				<div class="info-box bg-red">
					<span class="info-box-icon">
						<i class="fa fa-refresh"></i>
					</span>
					<div class="info-box-content">
						<span class="info-box-text">Unschedule (More Than 1 Days) </span>
						<span class="info-box-number" id='late_schedule'>
							<?php
							$late_schedule	= "<a href='#' onClick='return view_other_dashboard(\"7\");' style='color:white !important' id='link_late_schedule'>" . number_format($rows_data['late_schedule']) . "</a>";
							echo $late_schedule;

							?>
						</span>
					</div>
				</div>
			</div>

		</div>
	</div>
</div>
<div class="modal fade" id="MyQuotation">
	<div class="modal-dialog" style="width:85% !important">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title" id="judul"></h4>
			</div>
			<div class="modal-body" id="isi">

			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
			</div>
		</div>
	</div>
</div>

<div class="modal fade" id="MyLate">
	<div class="modal-dialog" style="width:85% !important">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title" id="judul_late"></h4>
			</div>
			<div class="modal-body" id="isi_late">

			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
			</div>
		</div>
	</div>
</div>
<?php $this->load->view('include/footer'); ?>
<script>
	var base_url = '<?php echo base_url(); ?>';
	var active_controller = 'Dashboard_process';
	$(document).ready(function() {
		//ambil_data_dashboard();
		//setInterval(ambil_data_dashboard,500000);		 
	});

	function ambil_data_dashboard() {
		loading_spinner_new();
		var baseurl = base_url + active_controller + '/json_dashboard';
		$.ajax({
			'url': baseurl,
			'type': 'get',
			'success': function(data) {
				close_spinner_new();
				var datas = $.parseJSON(data);

				var late_kalibrasi = parseInt(datas.late_kalibrasi);
				var late_ambil_subcon = parseInt(datas.late_ambil_subcon);
				var late_kirim_subcon = parseInt(datas.late_kirim_subcon);
				var late_kirim_cust = parseInt(datas.late_kirim_cust);
				var late_schedule = parseInt(datas.late_schedule);


				$('#link_send_late').text(late_kirim_cust);
				$('#link_late_process').text(late_kalibrasi);
				$('#link_late_subcon_pick').text(late_ambil_subcon);
				$('#link_late_subcon_send').text(late_kirim_subcon);
				$('#link_late_schedule').text(late_schedule);

			},
			'error': function(data) {
				close_spinner_new();
			}

		});
	}


	function view_late(id) {
		loading_spinner_new();
		$('#isi_late').empty();
		$('#judul_late').text('');

		var baseurl = base_url + active_controller + '/getlatedata/' + id;
		$.ajax({
			'url': baseurl,
			'type': 'get',
			'success': function(data) {
				close_spinner_new();
				if (id == 2) {
					var ket = 'List Late Calibration Process';
				} else if (id == 3) {
					var ket = 'List Late Send Tool To Subcont';
				} else if (id == 4) {
					var ket = 'List Late Pick Tool From Subcont';
				} else if (id == 5) {
					var ket = 'List Late Send Tool To Cust';
				} else if (id == 8) {
					var ket = 'List Late Calibration Process Insitu';
				}

				$('#judul_late').text(ket);
				$('#isi_late').html(data);
				$('#MyLate').modal('show');
			},
			'error': function(data) {
				close_spinner_new();
				alert('An error occured, please try again.');
			}
		});
	}


	function view_other_dashboard(kode) {
		loading_spinner_new();
		$('#isi').empty();
		$('#judul').text('');
		if (kode == 7) {
			var ket = 'List Late Schedule Process';
		}


		var baseurl = base_url + active_controller + '/get_other_dashboard/' + kode;
		$.ajax({
			'url': baseurl,
			'type': 'get',
			'success': function(data) {
				close_spinner_new();
				$('#judul').text(ket);
				$('#isi').html(data);
				$('#MyQuotation').modal('show');
			},
			'error': function(data) {
				close_spinner_new();
				alert('An error occured, please try again.');
			}
		});
	}



	Number.prototype.format = function(n, x, s, c) {
		var re = '\\d(?=(\\d{' + (x || 3) + '})+' + (n > 0 ? '\\D' : '$') + ')',
			num = this.toFixed(Math.max(0, ~~n));

		return (c ? num.replace('.', c) : num).replace(new RegExp(re, 'g'), '$&' + (s || ','));
	};
</script>