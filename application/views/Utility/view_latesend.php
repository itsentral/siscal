<?php
$this->load->view('include/side_menu');
?>
<div class="box box-primary">
	<div class="box-header">
		<h3 class="box-title"><?= $deskripsi; ?></h3>
	</div>

	<section class="content-header">
		<div class="col-xs-12">
			<div class="box">
				<div class="box-header">
					<form method="post" action="<?= base_url() ?>index.php/utility/tampilkan_latesend" autocomplete="off">

						<b>No SO : </b> <input type="text" id="no_so" name="no_so" value="" /> <input type="submit" name="tampilkan" value="Tampilkan" onclick="return check()" class="btn btn-success pull-center">

					</form>

				</div>
			</div>
		</div>
	</section>

	<!-- /.box-header -->
	<div class="box-body">
		<table id="example1" class="table table-bordered table-striped">
			<thead>
				<tr class='bg-blue'>
					<th class="text-center">No.</th>
					<th class="text-center">Id</th>
					<th class="text-center">No SO</th>
					<!-- <th class="text-center">Teknisi</th> -->
					<th class="text-center">Tool Name</th>
					<th class="text-center">Lokasi</th>
					<th class="text-center">Qty</th>
					<th class="text-center">Qty Proses</th>
					<th class="text-center">Qty Sisa</th>
					<th class="text-center">Qty Rec</th>
					<th class="text-center">Qty Send</th>
					<th class="text-center">Qty Send Real</th>
					<th class="text-center">Re Schedule</th>
					<th class="text-center">Option</th>
				</tr>
			</thead>
			<tbody>
				<?php
				if ($row) {
					$int	= 0;
					foreach ($row as $datas) {
						$int++;

						echo "<tr>";
						echo "<td align='center'>$int</td>";
						echo "<td align='left'>" . $datas->id . "</td>";
						echo "<td align='left'>" . $datas->no_so . "</td>";
						// echo "<td align='left'>" . $datas->teknisi_name . "</td>";
						echo "<td align='left'>" . $datas->tool_name . "</td>";
						echo "<td align='left'>" . $datas->location . "</td>";
						echo "<td align='left'>" . $datas->qty . "</td>";
						echo "<td align='left'>" . $datas->qty_proses . "</td>";
						echo "<td align='left'>" . $datas->qty_sisa . "</td>";
						echo "<td align='left'>" . $datas->qty_rec . "</td>";
						echo "<td align='left'>" . $datas->qty_send . "</td>";
						echo "<td align='left'>" . $datas->qty_send_real . "</td>";
						echo "<td align='left'>" . $datas->re_schedule . "</td>";
						echo "<td align='center'>";
						if ($akses_menu['update'] == '1') {
							echo "<button class='btn btn-sm btn-warning edit' title='Hilangkan di tampilan dashboard late send' data-id='" . $datas->id . "'><i class='fa fa-edit'></i>";
						}
						echo "</td>";
						echo "</tr>";
					}
				}
				?>
			</tbody>
		</table>
	</div>
	<!-- /.box-body -->
</div>
<!-- /.box -->

<?php $this->load->view('include/footer'); ?>
<script>
	$(document).on('click', '.edit', function() {
		var id = $(this).data('id');

		swal({
				title: "Apakah anda yakin ?",
				text: "Data tidak akan ditampilkan di dashboard !!!",
				type: "warning",
				showCancelButton: true,
				confirmButtonClass: "btn-danger",
				confirmButtonText: "Ya, Lanjutkan !",
				cancelButtonText: "Tidak, Batalkan !",
				closeOnConfirm: false,
				closeOnCancel: false
			},
			function(isConfirm) {
				if (isConfirm) {
					loading_spinner();
					$.ajax({
						url: base_url + active_controller + '/edit_latesend/' + id,
						type: "POST",
						data: "id=" + id,
						cache: false,
						dataType: 'json',
						processData: false,
						contentType: false,
						success: function(data) {
							if (data.status == 1) {
								swal({
									title: "Save Success!",
									text: data.pesan,
									type: "success",
									timer: 7000,
									showCancelButton: false,
									showConfirmButton: false,
									allowOutsideClick: false
								});
								window.location.href = base_url + active_controller + '/latesend';
							} else if (data.status == 0) {
								swal({
									title: "Save Failed!",
									text: data.pesan,
									type: "warning",
									timer: 7000,
									showCancelButton: false,
									showConfirmButton: false,
									allowOutsideClick: false
								});
							}
						},
						error: function() {
							swal({
								title: "Error Message !",
								text: 'An Error Occured During Process. Please try again..',
								type: "warning",
								timer: 7000,
								showCancelButton: false,
								showConfirmButton: false,
								allowOutsideClick: false
							});
						}
					});
				} else {
					swal("Dibatalkan", "Data dapat di hapus kembali ...", "error");
					return false;
				}
			});
	});


	$('#example1').dataTable({
		aLengthMenu: [
			[25, 50, 100, 200, -1],
			[25, 50, 100, 200, "All"]
		],
		iDisplayLength: 100
	});
</script>