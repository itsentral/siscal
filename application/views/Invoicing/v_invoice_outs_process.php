<?php
$this->load->view('include/side_menu');
?>
<form action="#" method="POST" id="form-proses" enctype="multipart/form-data">
	<div class="box box-warning">

		<div class="box-body">
			<div class="row">
				<div class="col-sm-12 col-xs-12 text-center sub-heading" style="color:white;">
					<h5><?php echo $title; ?></h5>
				</div>

			</div>

			<div class='row'>
				<div class="col-sm-6 col-xs-12">
					<div class="form-group">
						<label class="control-label"><b>Tax Code <span class='text-red'>*</span></b></label>
						<select name="kefak" id="kefak" class="form-control input-sm chosen-select">
							<?php
							if ($rows_faktur) {
								foreach ($rows_faktur as $keyF => $valF) {
									echo "<option value='$keyF'>" . $valF . "</option>";
								}
							} else {
								echo "<option value=''>Empty List</option>";
							}
							?>
						</select>

					</div>
				</div>
				<div class="col-sm-6 col-xs-12">
					<div class="form-group">
						<label class="control-label"><b>Invoice Date <span class='text-red'>*</span></b></label>
						<?php
						echo form_input(array('id' => 'invoice_date', 'name' => 'invoice_date', 'class' => 'form-control input-sm', 'readOnly' => true), date('d-m-Y'));
						?>
					</div>
				</div>
			</div>
			<div class='row'>
				<div class="col-sm-6 col-xs-12">
					<div class="form-group">
						<label class="control-label"><b>Customer </b></label>
						<?php
						echo form_input(array('id' => 'customer_name', 'name' => 'customer_name', 'class' => 'form-control input-sm', 'readOnly' => true), $rows_cust[0]->name);
						echo form_input(array('id' => 'nocust', 'name' => 'nocust', 'type' => 'hidden'), $rows_cust[0]->id);
						echo form_input(array('id' => 'pic_name', 'name' => 'pic_name', 'type' => 'hidden'), $rows_quot[0]->pic_name);
						echo form_input(array('id' => 'quotation_nomor', 'name' => 'quotation_nomor', 'type' => 'hidden'), $rows_quot[0]->nomor);
						echo form_input(array('id' => 'flag_so', 'name' => 'flag_so', 'type' => 'hidden'), $flag_so);

						?>
					</div>
				</div>
				<div class="col-sm-6 col-xs-12">
					<div class="form-group">
						<label class="control-label"><b>Address</b></label>
						<?php
						echo form_textarea(array('id' => 'alamat', 'name' => 'alamat', 'class' => 'form-control input-sm', 'readOnly' => true, 'cols' => 75, 'rows' => 2), $rows_cust[0]->npwp_address);
						?>
					</div>
				</div>
			</div>
			<div class='row'>
				<div class="col-sm-6 col-xs-12">
					<div class="form-group">
						<label class="control-label"><b>Print Tax Invoice <span class='text-red'>*</span></b></label>
						<select name="cetak_faktur" id="cetak_faktur" class="form-control input-sm chosen-select">
							<?php
							if ($rows_cetak) {
								foreach ($rows_cetak as $keyF => $valF) {
									echo "<option value='$keyF'>" . $valF . "</option>";
								}
							} else {
								echo "<option value=''>Empty List</option>";
							}
							?>
						</select>
					</div>
				</div>
				<div class="col-sm-6">
					<div class="form-group">
						<label class="control-label">Remaining Tax Invoice</label>
						<?php
						echo form_input(array('id' => 'sisa_faktur', 'name' => 'sisa_faktur', 'class' => 'form-control input-sm', 'readOnly' => true), number_format($sisa_faktur));
						?>
					</div>
				</div>
			</div>

			<div class="row">
				<div class="col-sm-12 col-xs-12 text-center sub-heading" style="color:white;">
					<h5>DETAIL ITEM </h5>
				</div>

			</div>
			<div class="row">
				<div class="col-sm-12" style="overflow-x:scroll !important;">
					<table class="table table-striped table-bordered" id="my-grid">
						<thead>
							<tr class="bg-navy-active">
								<th class="text-center">Tool Name</th>
								<th class="text-center">PO No</th>
								<th class="text-center">SO No</th>
								<th class="text-center">Price</th>
								<th class="text-center">Qty</th>
								<th class="text-center">Sub Total</th>
								<th class="text-center">Discount</th>
								<th class="text-center">Total</th>
								<th class="text-center">Action</th>
							</tr>
						</thead>
						<tbody id="list_detail">
							<?php
							$Flag_Insitu	= 'N';
							$intL			= $Total_DPP = 0;
							$Arr_Quot		= $Arr_Insitu = array();

							$Flag_PPN		= 'N';
							if ($rows_quot[0]->exc_ppn == 'N') {
								$Flag_PPN	= 'Y';
							}
							if ($rows_detail) {

								foreach ($rows_detail as $ketD => $valD) {

									if ($valD->qty_proses > 0) {
										$intL++;
										$Quot_Id			= $valD->quotation_id;
										$Letter_Id			= $valD->letter_order_id;
										$Det_Id				= $valD->quotation_detail_id;
										$Arr_Quot[$Quot_Id]	= $Letter_Id;
										if ($valD->insitu == 'Y') {
											$Flag_Insitu	= 'Y';
											$Arr_Insitu[$Quot_Id]	= $Letter_Id;
										}

										$Qty_Proses		= ($valD->qty_proses > $valD->qty_real) ? $valD->qty_real : $valD->qty_proses;
										$harga_barang	= $valD->price * $Qty_Proses;
										$harga_discount	= round(($valD->discount * $harga_barang) / 100);
										$harga_after	= $harga_barang - $harga_discount;
										$Total_DPP		+= $harga_after;

										echo "<tr id='tr_row_" . $intL . "'>";
										echo '<input type="hidden" name="detDetail[' . $intL . '][tool_id]" id="tool_id_' . $intL . '" value="' . $valD->tool_id . '">';

										echo '<input type="hidden" name="detDetail[' . $intL . '][range]" id="range_' . $intL . '" value="' . $valD->range . '">';
										echo '<input type="hidden" name="detDetail[' . $intL . '][price]" id="price_' . $intL . '" value="' . $valD->price . '">';
										echo '<input type="hidden" name="detDetail[' . $intL . '][hpp]" id="hpp_' . $intL . '" value="' . $valD->hpp . '">';
										echo '<input type="hidden" name="detDetail[' . $intL . '][piece_id]" id="piece_id_' . $intL . '" value="' . $valD->piece_id . '">';
										echo '<input type="hidden" name="detDetail[' . $intL . '][tipe]" id="tipe_' . $intL . '" value="T">';
										echo '<input type="hidden" name="detDetail[' . $intL . '][detail_id]" id="detail_id_' . $intL . '" value="' . $Det_Id . '">';
										echo '<input type="hidden" name="detDetail[' . $intL . '][discount]" id="discount_' . $intL . '" value="' . $valD->discount . '">';
										echo '<input type="hidden" name="detDetail[' . $intL . '][quotation_id]" id="quotation_id_' . $intL . '" value="' . $Quot_Id . '">';
										echo '<input type="hidden" name="detDetail[' . $intL . '][letter_order_id]" id="letter_order_id_' . $intL . '" value="' . $Letter_Id . '">';



										echo "<td align='left'>";
										echo '<input type="text" name="detDetail[' . $intL . '][tool_name]" id="tool_name_' . $intL . '" value="' . $valD->tool_name . '" class="form-control input-sm" readOnly>';
										echo "</td>";
										echo "<td align='center'>" . $valD->pono . "</td>";
										echo "<td align='center'>" . $valD->no_so . "</td>";
										echo "<td align='right'>" . number_format($valD->price) . "</td>";
										echo "<td align='center'>";
										echo '<input type="text" name="detDetail[' . $intL . '][qty]" id="qty_' . $intL . '" value="' . number_format($Qty_Proses) . '" class="form-control input-sm" size = "5px" readOnly>';
										echo "</td>";
										echo "<td align='right'>";
										echo '<input type="text" name="detDetail[' . $intL . '][total_harga]" id="total_harga_' . $intL . '" value="' . number_format($harga_barang) . '" class="form-control input-sm" size = "25px" readOnly>';
										echo "</td>";
										echo "<td align='right'>" . number_format($valD->discount, 2) . "</td>";
										echo "<td align='right'>";
										echo '<input type="text" name="detDetail[' . $intL . '][total]" id="total_' . $intL . '" value="' . number_format($harga_after) . '" class="form-control input-sm" size = "25px" readOnly>';
										echo "</td>";
										echo "<td align='center'>-</td>";
										echo "</tr>";
									}
								}
							}

							if ($Flag_Insitu == 'Y') {
								foreach ($Arr_Insitu as $keyQuot => $valSO) {
									$Query_Delivery	= "SELECT * FROM quotation_deliveries WHERE quotation_id  = '" . $keyQuot . "' AND (day - IF(pros_invoice > 0, pros_invoice,0)) > 0";
									$rows_Delivery	= $this->db->query($Query_Delivery)->result();
									if ($rows_Delivery) {
										$Query_Quots	= "SELECT pono FROM quotations WHERE id = '" . $keyQuot . "'";
										$Result_Quot	= $this->db->query($Query_Quots)->result();
										foreach ($rows_Delivery as $keyDel => $valDel) {
											$Qty_Proses		= $valDel->day - $valDel->pros_invoice;
											$Diskon_Ins		= round($valDel->diskon / $valDel->day);
											if ($Qty_Proses > 0) {
												$intL++;

												$harga_barang	= $valDel->fee;
												$harga_after	= $harga_barang * $Qty_Proses;

												$Total_DPP		+= ($harga_after - ($Qty_Proses * $Diskon_Ins));

												echo "<tr id='tr_row_" . $intL . "'>";
												echo '<input type="hidden" name="detDetail[' . $intL . '][tool_id]" id="tool_id_' . $intL . '" value="' . $valDel->delivery_id . '">';
												echo '<input type="hidden" name="detDetail[' . $intL . '][range]" id="range_' . $intL . '" value="-">';
												echo '<input type="hidden" name="detDetail[' . $intL . '][price]" id="price_' . $intL . '" value="' . $valDel->fee . '">';
												echo '<input type="hidden" name="detDetail[' . $intL . '][hpp]" id="hpp_' . $intL . '" value="' . $valDel->fee . '">';
												echo '<input type="hidden" name="detDetail[' . $intL . '][piece_id]" id="piece_id_' . $intL . '" value="-">';
												echo '<input type="hidden" name="detDetail[' . $intL . '][tipe]" id="tipe_' . $intL . '" value="I">';
												echo '<input type="hidden" name="detDetail[' . $intL . '][detail_id]" id="detail_id_' . $intL . '" value="' . $valDel->id . '">';
												echo '<input type="hidden" name="detDetail[' . $intL . '][discount]" id="discount_' . $intL . '" value="' . $Diskon_Ins . '">';
												echo '<input type="hidden" name="detDetail[' . $intL . '][quotation_id]" id="quotation_id_' . $intL . '" value="' . $keyQuot . '">';
												echo '<input type="hidden" name="detDetail[' . $intL . '][letter_order_id]" id="letter_order_id_' . $intL . '" value="' . $valSO . '">';



												echo "<td align='left'>";
												echo '<input type="text" name="detDetail[' . $intL . '][tool_name]" id="tool_name_' . $intL . '" value="' . $valDel->delivery_name . '" class="form-control input-sm" readOnly>';
												echo "</td>";
												echo "<td align='center'>" . $Result_Quot[0]->pono . "</td>";
												echo "<td align='center'>-</td>";
												echo "<td align='right'>" . number_format($valDel->fee) . "</td>";
												echo "<td align='center'>";
												echo '<input type="text" name="detDetail[' . $intL . '][qty]" id="qty_' . $intL . '" value="' . number_format($Qty_Proses) . '" class="input-sm" size = "5px" readOnly>';
												echo "</td>";
												echo "<td align='right'>";
												echo '<input type="text" name="detDetail[' . $intL . '][total_harga]" id="total_harga_' . $intL . '" value="' . number_format($harga_after) . '" class="form-control input-sm" size = "25px" readOnly>';
												echo "</td>";
												echo "<td align='right'>" . number_format($Diskon_Ins) . "</td>";
												echo "<td align='right'>";
												echo '<input type="text" name="detDetail[' . $intL . '][total]" id="total_' . $intL . '" value="' . number_format($harga_after - ($Qty_Proses * $Diskon_Ins)) . '" class="form-control input-sm" size = "25px" readOnly>';
												echo "</td>";
												echo "<td align='center'>";
												echo "<button type='button' class='btn btn-sm btn-danger' title='DELETE ROWS' data-role='qtip' onClick='return DelItem(" . $intL . ");'><i class='fa fa-trash-o'></i></button>";
												echo "</td>";
												echo "</tr>";
											}
										}
									}
								}
							}

							if ($Arr_Quot) {
								foreach ($Arr_Quot as $keyQuot => $valSO) {
									$Query_Accomodation	= "SELECT * FROM quotation_accommodations WHERE quotation_id  = '" . $keyQuot . "' AND (pros_invoice IS NULL OR pros_invoice ='' OR pros_invoice ='N' OR pros_invoice ='-')";
									$rows_Accomodation	= $this->db->query($Query_Accomodation)->result();
									if ($rows_Accomodation) {
										$Query_Quots	= "SELECT pono FROM quotations WHERE id = '" . $keyQuot . "'";
										$Result_Quot	= $this->db->query($Query_Quots)->result();
										foreach ($rows_Accomodation as $keyAcc => $valAcc) {
											$Qty_Proses		= 1;
											$Diskon_Ins		= round($valAcc->diskon);
											if ($Qty_Proses > 0) {
												$intL++;

												$harga_barang	= $valAcc->nilai;
												$harga_after	= $harga_barang * $Qty_Proses;
												$Total_DPP		+= $valAcc->total;



												echo "<tr id='tr_row_" . $intL . "'>";
												echo '<input type="hidden" name="detDetail[' . $intL . '][tool_id]" id="tool_id_' . $intL . '" value="' . $valAcc->accommodation_id . '">';
												echo '<input type="hidden" name="detDetail[' . $intL . '][range]" id="range_' . $intL . '" value="-">';
												echo '<input type="hidden" name="detDetail[' . $intL . '][price]" id="price_' . $intL . '" value="' . $valAcc->nilai . '">';
												echo '<input type="hidden" name="detDetail[' . $intL . '][hpp]" id="hpp_' . $intL . '" value="' . $valAcc->nilai . '">';
												echo '<input type="hidden" name="detDetail[' . $intL . '][piece_id]" id="piece_id_' . $intL . '" value="-">';
												echo '<input type="hidden" name="detDetail[' . $intL . '][tipe]" id="tipe_' . $intL . '" value="A">';
												echo '<input type="hidden" name="detDetail[' . $intL . '][detail_id]" id="detail_id_' . $intL . '" value="' . $valAcc->id . '">';
												echo '<input type="hidden" name="detDetail[' . $intL . '][discount]" id="discount_' . $intL . '" value="' . $Diskon_Ins . '">';
												echo '<input type="hidden" name="detDetail[' . $intL . '][quotation_id]" id="quotation_id_' . $intL . '" value="' . $keyQuot . '">';
												echo '<input type="hidden" name="detDetail[' . $intL . '][letter_order_id]" id="letter_order_id_' . $intL . '" value="' . $valSO . '">';



												echo "<td align='left'>";
												echo '<input type="text" name="detDetail[' . $intL . '][tool_name]" id="tool_name_' . $intL . '" value="' . $valAcc->accommodation_name . '" class="form-control input-sm" readOnly>';
												echo "</td>";
												echo "<td align='center'>" . $Result_Quot[0]->pono . "</td>";
												echo "<td align='center'>-</td>";
												echo "<td align='right'>" . number_format($valAcc->nilai) . "</td>";
												echo "<td align='center'>";
												echo '<input type="text" name="detDetail[' . $intL . '][qty]" id="qty_' . $intL . '" value="' . number_format($Qty_Proses) . '" class="form-control input-sm" size = "5px" readOnly>';
												echo "</td>";
												echo "<td align='right'>";
												echo '<input type="text" name="detDetail[' . $intL . '][total_harga]" id="total_harga_' . $intL . '" value="' . number_format($harga_after) . '" class="form-control input-sm" size = "25px" readOnly>';
												echo "</td>";
												echo "<td align='right'>" . number_format($Diskon_Ins) . "</td>";
												echo "<td align='right'>";
												echo '<input type="text" name="detDetail[' . $intL . '][total]" id="total_' . $intL . '" value="' . number_format($valAcc->total) . '" class="form-control input-sm" size = "25px" readOnly>';
												echo "</td>";
												echo "<td align='center'>";
												echo "<button type='button' class='btn btn-sm btn-danger' title='DELETE ROWS' data-role='qtip' onClick='return DelItem(" . $intL . ");'><i class='fa fa-trash-o'></i></button>";
												echo "</td>";
												echo "</tr>";
											}
										}
									}
								}
							}

							?>
						</tbody>
						<tfoot class='bg-gray'>
							<tr>
								<td align='right' colspan='8'><b>Sub Total</b></td>
								<td align='right'>
									<?php
									echo '<input type="text" name="dpp" id="dpp" value="' . number_format($Total_DPP) . '" class="form-control input-sm" readOnly>';


									$PPN		= 0;
									if ($Flag_PPN == 'Y') {
										$PPN	= floor($Total_DPP * 0.1);
									}
									$Grand_Total	= $Total_DPP + $PPN;
									?>
								</td>
							</tr>
							<tr>
								<td align='right' colspan='8'><b>VAT</b></td>
								<td align='right'>
									<?php
									echo '<input type="text" name="ppn" id="ppn" value="' . number_format($PPN) . '" class="form-control input-sm" readOnly>';
									echo form_input(array('id' => 'inc_ppn', 'name' => 'inc_ppn', 'type' => 'hidden'), $Flag_PPN);
									echo form_input(array('id' => 'prosen_ppn', 'name' => 'prosen_ppn', 'type' => 'hidden'), 10);
									?>
								</td>
							</tr>
							<tr>
								<td align='right' colspan='8'><b>Grand Total</b></td>
								<td align='right'>
									<?php
									echo '<input type="text" name="grand_total" id="grand_total" value="' . number_format($Grand_Total) . '" class="form-control input-sm" readOnly>';

									?>
								</td>
							</tr>
						</tfoot>
					</table>
				</div>
			</div>

			<?php

			echo '</div>';
			echo "<div class='box-footer'>";
			echo '
				<button type="button" class="btn btn-md btn-danger" id="btn-back"> <i class="fa fa-long-arrow-left"></i> BACK </button>';
			if (!empty($rows_detail)) {
				echo '
				&nbsp;&nbsp;&nbsp;<button type="button" id="btn-process-approve" class="btn btn-md" style="background-color:#37474f; color:white;vertical-align:middle !important;" title="SAVE PROCESS"> SAVE PROCESS <i class="fa fa-long-arrow-right" style="width:40px;"></i> </button>';
			}
			echo "</div>";
			?>

		</div>
</form>

<?php $this->load->view('include/footer'); ?>
<style>
	.sub-heading {
		border-radius: 5px;
		background-color: #03506F;
		color: white;
		margin: 20px 10px 15px 10px !important;
		width: 98% !important;
	}

	.ui-spinner-input {
		padding: 10px 5px 10px 10px !important;
	}
</style>
<script>
	var base_url = '<?php echo site_url(); ?>';
	var active_controller = '<?php echo ($this->uri->segment(1)); ?>';

	const GetProsenPPN = () => {
		let InvoiceDate = $('#invoice_date').val();

		$.post(base_url + '/' + active_controller + '/GetMasterPPN', {
			'invoice_date': InvoiceDate
		}, function(response) {
			const result = $.parseJSON(response);
			$('#prosen_ppn').val(result.ppn);
			CalcALL();
		});
	}

	$(document).ready(function() {
		$('#list_detail').find('tr').each(function() {
			let code_rows = $(this).attr('id');
			const split_rows = code_rows.split('_');
			let tipe_detail = $('#tipe_' + split_rows[2]).val();
			let max_spin = parseInt($('#qty_' + split_rows[2]).val());

			if (tipe_detail == 'I') {
				$('#qty_' + split_rows[2]).spinner({
					min: 1,
					max: max_spin,
					spin: function(event, ui) {
						Calculation(split_rows[2]);
					}
				});
			}
		});

		$('#invoice_date').datepicker({
			changeMonth: true,
			changeYear: true,
			showButtonPanel: false,
			dateFormat: 'yy-mm-dd',
			maxDate: '+0d',
			minDate: '<?php echo date('Y-m-d', mktime(0, 0, 0, date('m') - 1, date('d'), date('Y'))); ?>',
			onClose: function() {
				GetProsenPPN();
			}
		});
		$('#btn-back').click(function() {
			loading_spinner();
			let Flag_SO = $('#flag_so').val();
			let Link_Back = base_url + '/' + active_controller + '/list_outstanding_full_po';
			if (Flag_SO == 'Y') {
				Link_Back = base_url + '/' + active_controller + '/list_outstanding_partial_po';
			} else {

			}
			window.location.href = Link_Back;
		});

		GetProsenPPN();
	});

	function DelItem(id) {
		$('#tr_row_' + id).remove();
		CalcALL();
	}

	function startCalculation(id) {
		intervalCalculation = setInterval('Calculation(' + id + ')', 1);
	}

	function Calculation(id) {
		let tipe_alat = $('#tipe_' + id).val();
		let harga = parseInt($('#price_' + id).val().replace(/\,/g, ''));
		let qty = parseInt($('#qty_' + id).val().replace(/\,/g, ''));
		let diskon = $('#discount_' + id).val().replace(/\,/g, '');
		if (diskon == 0 || diskon == null) {
			diskon = 0;
		}
		let net_pcs = harga;
		let tot_net = 0;
		let nil_diskon = 0;
		if (tipe_alat == 'T') {
			tot_net = harga * qty;
			nil_diskon = Math.round(tot_net * ((100 - parseFloat(diskon)) / 100));
		} else {
			tot_net = harga * qty;
			nil_diskon = qty * parseFloat(diskon);
		}

		let net_harga = parseFloat(tot_net) - parseFloat(nil_diskon);
		$('#total_harga_' + id).val(tot_net.format(0, 3, ','));
		$('#total_' + id).val(net_harga.format(0, 3, ','));

		CalcALL();
	}

	function stopCalculation() {
		clearInterval(intervalCalculation);
	}

	function CalcALL() {
		let sub_tot = 0;

		let grand_tot = 0;
		let ket_ppn = $('#inc_ppn').val();
		let Prosen_PPN = $('#prosen_ppn').val();

		$('#list_detail').find('tr').each(function() {
			let code_rows = $(this).attr('id');
			const split_rows = code_rows.split('_');
			let loop = split_rows[2];
			let awal = $('#total_' + loop).val().replace(/\,/g, '');
			sub_tot = parseFloat(sub_tot) + parseFloat(awal);

		});

		let ppn = 0;
		if (ket_ppn == 'Y') {
			ppn = Math.floor(parseFloat(sub_tot) * parseFloat(Prosen_PPN) / 100);
		}
		grand_tot = parseFloat(sub_tot) + parseFloat(ppn);
		$('#dpp').val(sub_tot.format(0, 3, ','));

		$('#ppn').val(ppn.format(0, 3, ','));
		$('#grand_total').val(grand_tot.format(0, 3, ','));
	}

	Number.prototype.format = function(n, x, s, c) {
		var re = '\\d(?=(\\d{' + (x || 3) + '})+' + (n > 0 ? '\\D' : '$') + ')',
			num = this.toFixed(Math.max(0, ~~n));

		return (c ? num.replace('.', c) : num).replace(new RegExp(re, 'g'), '$&' + (s || ','));
	};


	$(document).on('click', '#btn-process-approve', (e) => {
		e.preventDefault();
		$('#btn-back, #btn-process-approve').prop('disabled', true);
		let Code_Tax = $('#kefak').val();
		if (Code_Tax == '' || Code_Tax == null) {

			swal({
				title: "Error Message !",
				text: 'Empty Tax Code. Please choose tax code first...',
				type: "warning"
			});
			$('#btn-back, #btn-process-approve').prop('disabled', false);
			return false;

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
					var formData = new FormData($('#form-proses')[0]);
					var baseurl = base_url + '/' + active_controller + '/save_generate_invoice_process';
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
							console.log(data);
							if (data.status == 1) {
								$.post(base_url + '/' + active_controller + '/whatsapp_approval', {
									'code_inv': data.code
								}, function(hasil) {
									swal({
										title: "Save Success!",
										text: data.pesan,
										type: "success"
									});
									window.location.href = base_url + '/' + active_controller;
								});

							} else {
								swal({
									title: "Save Failed!",
									text: data.pesan,
									type: "warning"
								});

								alert(data.pesan);
								$('#btn-back, #btn-process-approve').prop('disabled', false);
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
							$('#btn-back, #btn-process-approve').prop('disabled', false);
							return false;
						}
					});

				} else {
					close_spinner_new();
					swal("Cancelled", "Data can be process again :)", "error");
					$('#btn-back, #btn-process-approve').prop('disabled', false);
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
						text: 'Hanya boleh pilih jenis file IMAGES atau PDF....',
						type: "warning"
					});

					oInput.value = "";
					return false;
				}
			}
		}
		return true;
	}
</script>