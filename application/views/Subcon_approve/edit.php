<?php
$this->load->view('include/side_menu');
//echo"<pre>";print_r($data_menu);
?>
<script>
    function countTotal(id) {
        let qty = $('#qty_' + id).text();
        let price = $('#price_' + id).val().replace(/,/g, '');
        let discount = $('#discount_' + id).val();
        let persen = parseInt(price) * discount / 100;
        let total = 0;

        total = (price * qty) - persen;
        $('#total_' + id).val(total).trigger('change')
        // console.log(qty)
        // console.log(price)
        // console.log(discount)
        // console.log(persen)

    }

    $(document).ready(function() {
        // function countGrandTotal(id) {
        let grandTotal = 0;
        $('.totalCal').each(function() {
            grandTotal += parseFloat($(this).val().replace(/,/g, '')); // Or this.innerHTML, this.innerText
        });

        let vari = new Intl.NumberFormat('id-ID', {
            minimumFractionDigits: 0,
        }).format(grandTotal)
        // alert(vari);

        $('#grandTotal').val(vari);

        // Currency Format


    })

    function countGrandTotal(id) {
        let grandTotal = 0;
        $('.totalCal').each(function() {
            grandTotal += parseFloat($(this).val()); // Or this.innerHTML, this.innerText
        });
        let vari = new Intl.NumberFormat('id-ID', {
            minimumFractionDigits: 0,
        }).format(grandTotal)
        // alert(vari);

        $('#grandTotal').val(vari);
    }
</script>
<div class="box box-primary">
    <div class="box-header">
        <h3 class="box-title"><?= $title; ?></h3>
    </div>
    <!-- /.box-header -->
    <?php foreach ($row as $data) :
    ?>
        <form action="#" method="POST" id="form_proses_bro">
            <div class="box-body">
                <div class='form-group row'>
                    <input type="text" hidden value="<?= $data->id; ?>" name="id" id="id">
                    <label class='label-control col-md-2'><b>Nomor<span class='text-red'>*</span></b></label>
                    <div class='col-md-4'>
                        <input type="text" class="form-control" name="subcon_pono" id="subcon_pono" value="<?= $data->subcon_pono; ?>" readonly>
                    </div>

                    <label class="label-control col-md-2"><b>Tanggal</b></label>
                    <div class="col-md-4">
                        <input type="text" name="datet" id="datet" value="<?= $data->datet; ?>" class="form-control" readonly>
                    </div>
                </div>

                <div class="form-group row">
                    <label for="" class="label-control col-md-2"><b>Subcon<span class="text-red">*</span></b></label>
                    <div class="col-md-4">
                        <input type="text" name="supplier_name" id="supplier_name" value="<?= $data->supplier_name; ?>" class="form-control" readonly>
                    </div>

                    <label for="" class="label-control col-md-2"><b>Alamat<span class="text-red">*</span></b></label>
                    <div class="col-md-4">
                        <textarea type="text" class="form-control" readonly id="address"><?= $data->address; ?></textarea>
                    </div>
                </div>

                <div class="form-group row">
                    <label for="" class="label-control col-md-2"><b>PIC<span class="text-red">*</span></b></label>
                    <div class="col-md-4">
                        <input type="text" class="form-control" id="pic_name" name="pic_name" value="<?= $data->pic_name; ?>" readonly>
                    </div>

                    <label for="exc_ppn" class="label-control col-md-2"><b>Exclude PPN</b></label>
                    <div class="col-md-4">
                        <select class="form-control" id="exc_ppn" name="exc_ppn" disabled>
                            <option value="<?= $data->exc_ppn; ?>"><?php echo ($data->exc_ppn == "N") ? 'No' : 'Yes'; ?></option>
                        </select>
                    </div>
                </div>
            </div>

            <!-- /.box-body -->

            <div class="box box-danger">
                <div class="box-header">
                    <h3 class="box-title">Detail Data</h3>
                    <div class="box-body">
                        <table id="table_list_alat" class="table table-bordered table-striped">
                            <thead>
                                <tr class='bg-blue'>
                                    <th class="text-center">
                                        No
                                    </th>
                                    <th class="text-center">Kode Alat</th>
                                    <th class="text-center">Nama Alat</th>
                                    <th class="text-center">Tipe</th>
                                    <th class="text-center">Customer</th>
                                    <th class="text-center">Qty</th>
                                    <th class="text-center">Harga</th>
                                    <th class="text-center">Disc</th>
                                    <th class="text-center">Total</th>
                                    <th class="text-center">Keterangan</th>
                                    <th class="text-center">Notes</th>
                                </tr>
                            </thead>
                            <tbody id="list_alat">
                                <?php
                                // $i = 0;
                                $count = 1;
                                foreach ($detail as $data1) :
                                ?>
                                    <tr id="tr_<?= $count; ?>">
                                        <td align="center">
                                            <?= $count; ?>
                                        </td>
                                        <td align="center" name="item[<?= $count; ?>][tool_id]">
                                            <?= $data1->tool_id; ?>
                                        </td>
                                        <td align="center" name="item[<?= $count; ?>][tool_name]">
                                            <?= $data1->tool_name; ?>
                                        </td>
                                        <td align="center">
                                        </td>
                                        <td align="center" name="item[<?= $count; ?>][customer_name]"">
                                            <?= $data1->customer_name; ?>
                                        </td>
                                        <td align=" center" class="qtyCal" name="item[<?= $count; ?>][qty]" id="qty_<?= $count; ?>">
                                            <?= $data1->qty; ?>
                                        </td>
                                        <input type="text" hidden id="id" name="item[<?= $count; ?>][id]" value="<?= $data1->id; ?>">
                                        <td align="center">
                                            <input type="text" name="item[<?= $count; ?>][price]" readonly id="price_<?= $count; ?>" onkeyup="countTotal(<?= $count; ?>);" class="txtCal form-control" value="<?= number_format($data1->price); ?>">
                                        </td>
                                        <td align="center">
                                            <input type="text" name="item[<?= $count; ?>][discount]" class="form-control" readonly id="discount_<?= $count; ?>" onkeyup="countTotal(<?= $count; ?>);" value="<?= $data1->discount; ?>">
                                        </td>
                                        <td align="center">
                                            <input type="text" name="item[<?= $count; ?>][total]" class="totalCal form-control" id="total_<?= $count; ?>" value="<?= number_format($data1->total); ?>" readonly onchange="countGrandTotal(<?= $count; ?>);">
                                        </td>
                                        <td align="center">
                                            <textarea type="text" name="item[<?= $count; ?>][descr]" class="form-control" readonly id="descr_<?= $count; ?>"><?= $data1->descr; ?></textarea>
                                        </td>
                                        <td align="center">
                                            <textarea type="text" name="item[<?= $count; ?>][notes]" class="form-control" readonly id="notes_<?= $count; ?>"><?= $data1->notes; ?></textarea>
                                        </td>
                                    </tr>
                                <?php
                                    ++$count;
                                endforeach; ?>
                            </tbody>

                            <tfoot>
                                <tr class="text-right bg-gray">
                                    <td colspan="8">Total</td>
                                    <td>
                                        <input type="text" name="grandTotal" class="form-control" id="grandTotal" value="" readonly>
                                    </td>
                                    <td></td>
                                    <td></td>
                                </tr>
                            </tfoot>

                        </table>
                    </div>
                </div>
            </div>

            <div class="box box-danger">
                <div class="box-header">
                    <h3 class="box-title">Detail Akomodasi</h3>
                    <div class="box-body">
                        <div class="box-tool pull-right">

                        </div>
                        <table id="akomodasi" class="table table-bordered table-striped">
                            <thead>
                                <tr class='bg-blue'>
                                    <td align="center">
                                        No
                                    </td>
                                    <td align="center">
                                        Akomodasi
                                    </td>
                                    <td align="center">
                                        Nilai
                                    </td>
                                    <td align="center">
                                        Diskon
                                    </td>
                                    <td align="center">
                                        Total
                                    </td>
                                </tr>
                            </thead>
                            <tbody id="list_akomodasi">
                                <?php
                                $akomlist = 1;
                                foreach ($akomodasi as $akom) :
                                ?>
                                    <tr id="akomodasilist[<?= $akomlist; ?>]">
                                        <td align="center">
                                            <?= $akomlist; ?>
                                        </td>
                                        <td align="center">
                                            <input type="text" name="" id="" class="form-control" disabled value="<?= $akom->accommodation_name; ?>">
                                        </td>
                                        <td align="center">
                                            <input type="text" value="<?= number_format($akom->nilai); ?>" class="form-control" disabled>
                                        </td>
                                        <td align="center">
                                            <input type="text" value="<?= $akom->diskon; ?>" class="form-control" disabled>
                                        </td>
                                        <td align="center">
                                            <input type="text" value="<?= number_format($akom->total); ?>" class="totalCalAkomodasi form-control" disabled>
                                        </td>
                                    </tr>
                                <?php
                                    ++$akomlist;
                                endforeach;
                                ?>
                            </tbody>
                            <tfoot>
                                <tr class="bg-gray">
                                    <td class="text-right" colspan="4">
                                        Sub Total
                                    </td>
                                    <td>
                                        <input class="form-control" type="text" id="grandTotalAkomodasi" disabled>
                                    </td>
                                </tr>
                            </tfoot>
                        </table>


                    </div>
                </div>
            </div>

            <div class="box box-success">
                <div class="box-header">
                    <div class="box-body">
                        <div class="row form-group">
                            <label for="" class="label-control col-md-2">Total DPP</label>
                            <div class="col-md-4">
                                <input type="text" class="form-control" name="dpp" id="dpp" value="<?= number_format($data->dpp); ?>" readonly>
                            </div>

                            <label for="" class="label-control col-md-2">PPN</label>
                            <div class="col-md-4">
                                <input type="text" class="form-control" name="ppn" id="ppn" value="<?= number_format($data->ppn); ?>" readonly>
                            </div>
                        </div>
                        <div class="row form-group">
                            <label for="" class="label-control col-md-2">Grand Total</label>
                            <div class="col-md-4">
                                <input type="text" class="form-control" name="grand_tot" id="grand_tot" value="<?= number_format($data->grand_tot); ?>" readonly>
                            </div>

                            <label for="" class="label-control col-md-2">Total Insitu</label>
                            <div class="col-md-4">
                                <input type="text" class="form-control" name="Insitu" id="Insitu" value="<?= number_format($data->insitu); ?>" disabled>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
                <!-- /.box -->
                </div>
            </div>
            <div class="box-footer">
                <button class="btn btn-primary" id="simpan-bro" value="save" content="Save" type="button">Ubah Menjadi Open</button>
                <button class="btn btn-danger" type="button" value="back" content="back" onclick="back()">Kembali</button>
            </div>
        </form>
</div>

<?php $this->load->view('include/footer'); ?>

<script type="text/javascript">
    // var Akomodasi_data = {
    //     "AKOM-001": "OVER TIME",
    //     "AKOM-002": "TRANSPORT",
    //     "AKOM-003": "RUMAH AIRPORT",
    //     "AKOM-004": "TIKET PESAWAT\/KERETA",
    //     "AKOM-005": "AIRPORT\/ STATIUN KE LOKASI",
    //     "AKOM-006": "PENGINAPAN",
    //     "AKOM-007": "UANG SAKU",
    //     "AKOM-008": "PERCEPATAN",
    //     "AKOM-009": "PETUGAS KALIBRASI",
    //     "AKOM-010": "BIAYA ANTAR JEMPUT",
    //     "AKOM-011": "ASURANSI",
    //     "AKOM-012": "ASURANSI ALAT, PETUGAS KALIBRASI, AKOMODASI",
    //     "AKOM-013": "BIAYA PENGIRIMAN EKSPEDISI",
    //     "AKOM-014": "RAPID TEST",
    //     "AKOM-015": "DISCOUNT",
    //     "AKOM-016": "PCR TEST",
    //     "AKOM-017": "SEWA ANAK TIMBANGAN",
    //     "AKOM-018": "RAPID TEST ANTIGEN",
    //     "AKOM-019": "MEDICAL CECK UP",
    //     "AKOM-020": "INSITU ",
    //     "AKOM-021": "BIAYA ADJUST",
    //     "AKOM-022": "COMMISSIONING CHARGE",
    //     "AKOM-023": "AKOMODASI PERJALANAN",
    //     "AKOM-024": "SER-LCA-0137  BIAYA INSITU",
    //     "AKOM-025": "TAX WITHOLDING SANDI KHOO",
    //     "AKOM-026": "ALAT BANTU",
    //     "AKOM-027": "BAGASI PESAWAT",
    //     "AKOM-028": "BIAYA TRANSFER",
    //     "AKOM-029": "LAIN-LAIN",
    //     "AKOM-030": "BIAYA BUFFER TAMBAHAN ",
    //     "AKOM-031": "WRAPPING ALAT STANDAR DI BANDARA",
    //     "AKOM-032": "HELPER",
    //     "AKOM-033": "SERVICE - GANTI SENSOR",
    //     "AKOM-034": "BIAYA LEVEL MACHINE"
    // };
    $(document).ready(function() {

        $('#simpan-bro').click(function() {
            var formData = new FormData($('#form_proses_bro')[0]);
            // console.log(formData);
            $.ajax({
                type: "POST",
                url: base_url + 'index.php/' + active_controller + '/edit',
                data: formData,
                dataType: 'JSON',
                processData: false,
                contentType: false,
                cache: false,
                success: function(data) {
                    // console.log("SUCCESS : ", data);
                    if (data.status == '1') {
                        $('#spinner').modal('hide');
                        swal({
                            title: "Save Success!",
                            text: data.pesan,
                            type: "success",
                            timer: 7000,
                            showCancelButton: false,
                            showConfirmButton: false,
                            allowOutsideClick: false
                        });
                        window.location.href = base_url + 'index.php/' + active_controller;
                    } else {
                        $('#spinner').modal('hide');
                        if (data.status == '2') {
                            swal({
                                title: "Save Failed!",
                                text: data.pesan,
                                type: "danger",
                                timer: 7000,
                                showCancelButton: false,
                                showConfirmButton: false,
                                allowOutsideClick: false
                            });
                        } else {

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

                    }
                },
                error: function(e) {
                    $('#spinner').modal('hide');
                    // console.log("SUCCESS : ", data);
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

        })


        // let data_row_akomodasi = 0;
        // $("#viewAkomodasi").click(function() {
        //     data_row_akomodasi++;
        //     inputRow(data_row_akomodasi);
        // })

        // inputRow = (i) => {
        //     let Template = '<tr id="tr_akomodasi_' + i + '">';
        //     Template += '<td align="center"><select name="akomodasi" class="form-control">';
        //     Template += '<option value="">Pilih Opsi</option>';
        //     $.each(Akomodasi_data, function(key, nilai) {
        //         Template += '<option value="' + key + '">' + nilai + '</option>';

        //     });
        //     Template += '</select>';
        //     Template += '<td align="center"><input name="nilai" type="nilai" class="form-control"></td>';
        //     Template += '<td align="center"><input name="diskon" type="nilai" class="form-control"></td>';
        //     Template += '<td align="center"><input name="total" type="nilai" class="form-control" readonly></td>';
        //     Template += '<td align="center"><button id="hapus_akomodasi" class="btn btn-danger float-end delete-record" data-id="' + i + '">Hapus</button></td>';
        //     Template += '</tr>';
        //     $('#list_akomodasi').append(Template)
        // }

        // $('#list_akomodasi').on('click', '.delete-record', function() {
        //     $(this).closest('tr').remove();
        //     return false;
        // })



        // $('#table_list_alat').on('input', '.txtCal', function() {
        //     var calculated_total_sum = 0;
        // console.log($(this))
        // let price_1 = 0;
        // let qty_1 = 0;
        // let subtotal = 0;
        // $(".txtCal").keyup(function() {
        //     var qty = $(this).val();
        //     var price = $(this).closest('tr').find('td[class=qtyCal]').html();

        //     var total = parseInt(qty) * parseFloat(price);

        //     $(this).closest('tr').find('td[class^=totalCal]').html(total)
        //     // var asli = $("#total_1").val(total);
        //     var grandTotal = 0;
        //     $("td[class^=totalCal]").each(function() {
        //         grandTotal += parseFloat($(this).html());
        //         console.log(($(this).html()));
        //     });

        //     // console.log(grandTotal)

        // });

        // var get_textbox_value = $(this).val();

        // if ($.isNumeric(get_textbox_value)) {
        //     calculated_total_sum += parseFloat(get_textbox_value);
        // }
        // })
    });

    //     $("#total").val(calculated_total_sum);
    // });
</script>