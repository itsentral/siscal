<script>
    var Akomodasi_data = {
        "AKOM-001": "OVER TIME",
        "AKOM-002": "TRANSPORT",
        "AKOM-003": "RUMAH AIRPORT",
        "AKOM-004": "TIKET PESAWAT\/KERETA",
        "AKOM-005": "AIRPORT\/ STATIUN KE LOKASI",
        "AKOM-006": "PENGINAPAN",
        "AKOM-007": "UANG SAKU",
        "AKOM-008": "PERCEPATAN",
        "AKOM-009": "PETUGAS KALIBRASI",
        "AKOM-010": "BIAYA ANTAR JEMPUT",
        "AKOM-011": "ASURANSI",
        "AKOM-012": "ASURANSI ALAT, PETUGAS KALIBRASI, AKOMODASI",
        "AKOM-013": "BIAYA PENGIRIMAN EKSPEDISI",
        "AKOM-014": "RAPID TEST",
        "AKOM-015": "DISCOUNT",
        "AKOM-016": "PCR TEST",
        "AKOM-017": "SEWA ANAK TIMBANGAN",
        "AKOM-018": "RAPID TEST ANTIGEN",
        "AKOM-019": "MEDICAL CECK UP",
        "AKOM-020": "INSITU ",
        "AKOM-021": "BIAYA ADJUST",
        "AKOM-022": "COMMISSIONING CHARGE",
        "AKOM-023": "AKOMODASI PERJALANAN",
        "AKOM-024": "SER-LCA-0137  BIAYA INSITU",
        "AKOM-025": "TAX WITHOLDING SANDI KHOO",
        "AKOM-026": "ALAT BANTU",
        "AKOM-027": "BAGASI PESAWAT",
        "AKOM-028": "BIAYA TRANSFER",
        "AKOM-029": "LAIN-LAIN",
        "AKOM-030": "BIAYA BUFFER TAMBAHAN ",
        "AKOM-031": "WRAPPING ALAT STANDAR DI BANDARA",
        "AKOM-032": "HELPER",
        "AKOM-033": "SERVICE - GANTI SENSOR",
        "AKOM-034": "BIAYA LEVEL MACHINE"
    };
    $(document).ready(function() {
        let grandTotal = 0;
        // let total = 0;
        // total = $('.totalCal').val();
        $('.totalCal').each(function() {
            grandTotal += parseInt($(this).val().replace(/,/g, '')); // Or this.innerHTML, this.innerText
            // console.log('ini total' + $(this).val())

        });

        let varo = new Intl.NumberFormat('id-ID', {
            minimumFractionDigits: 0,
        }).format(grandTotal)

        $('#grandTotal').val(varo);

        // alert(grandTotal)

        let grandTotal1 = 0;
        // let total = 0;
        // total = $('.totalCal').val().replace(/,/g, '');
        $('.totalCalAkomodasi').each(function() {
            grandTotal1 += parseInt($(this).val().replace(/,/g, '')); // Or this.innerHTML, this.innerText
            // console.log('ini total' + grandTotal)

        });
        let vari = new Intl.NumberFormat('id-ID', {
            minimumFractionDigits: 0,
        }).format(grandTotal1)

        $('#grandTotalAkomodasi').val(vari);

        $('#simpan-bro').click(function() {
            $('.txtCal').val();
            $('.totalCal').maskMoney('destroy')[0]
            // alert($('.txtCal').val());
            var formData = new FormData($('#form_proses_bro')[0]);
            // console.log(formData);
            $.ajax({
                type: "POST",
                url: base_url + 'index.php/' + active_controller + '/editApv',
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
                        // console.log('Success :' + data);
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


        let data_row_akomodasi = 0;
        $("#viewAkomodasi").click(function() {
            data_row_akomodasi++;
            inputRow(data_row_akomodasi);
        })

        inputRow = (i) => {
            let Template = '<tr id="tr_akomodasi_' + i + '">';
            Template += '<td align="center"><select name="akomodasi_' + i + '" id="akomodasi_' + i + '" class="form-control">';
            Template += '<option value="">Pilih Opsi</option>';
            $.each(Akomodasi_data, function(key, nilai) {
                Template += '<option value="' + key + '">' + nilai + '</option>';

            });
            Template += '</select>';
            Template += '<td align="center"><input name="nilai_' + i + '" type="text" id="nilai_' + i + '" class="form-control" onkeyup="countTotalAkomodasi(' + i + ')"></td>';
            Template += '<td align="center"><input name="diskon_' + i + '" type="text" id="diskon_' + i + '" class="form-control" onkeyup="countTotalAkomodasi(' + i + ')"></td>';
            Template += '<td align="center"><input name="total_' + i + '" type="text" class="totalCalAkomodasi form-control" id="totalAkomodasi_' + i + '" readonly onchange="countGrandTotalAkomodasi(' + i + ')"></td>';
            Template += '<td align="center"><button id="hapus_akomodasi" class="btn btn-danger float-end delete-record" data-id="' + i + '">Hapus</button></td>';
            Template += '</tr>';
            $('#list_akomodasi').append(Template)
        }

        $('#list_akomodasi').on('click', '.delete-record', function() {
            $(this).closest('tr').remove();
            return false;
        })


    })



    function countTotal(id) {


        let qty = $('#qty_' + id).text();
        let price = $('#price_' + id).val();
        let discount = $('#discount_' + id).val();
        let persen = parseInt(price) * discount / 100;
        let total = 0;
        // console.log('price =' + price);
        // console.log('discount =' + discount);
        // console.log('persen =' + persen);


        total = (price * qty) - persen;

        // console.log($('#total_' + id).val())
        $('#total_' + id).val(total).trigger('change');
        // console.log(total);
        countGrandTotal();
    }



    function countGrandTotal(id) {
        let grandTotal = 0;
        // let total = 0;
        // total = $('.totalCal').val();
        $('.totalCal').each(function() {
            grandTotal += parseInt($(this).val()); // Or this.innerHTML, this.innerText

        });
        // console.log('ini total' + grandTotal)

        $('#grandTotal').val(grandTotal);

        // $('#grandTotal').maskMoney({
        //     formatOnBlur: true,
        //     precision: 0,
        //     decimal: ',',
        // })

        // $('#grandTotal').val(grandTotal);
    }

    function countTotalAkomodasi(id) {


        let nilai = $('#nilai_' + id).val();
        let diskon = $('#diskon_' + id).val();
        let persen = parseInt(nilai) * diskon / 100;
        let total = 0;
        console.log('nilai =' + nilai);
        console.log('diskon =' + diskon);
        console.log('persen =' + persen);


        total = nilai - persen;

        // console.log($('#totalAkomodasi_' + id).val())
        $('#totalAkomodasi_' + id).val(total).trigger('change');
        console.log($('#totalAkomodasi_' + id).val(total).trigger('change'));
        countGrandTotalAkomodasi();
    }

    function countGrandTotalAkomodasi(id) {
        let grandTotal = 0;
        // let total = 0;
        // total = $('.totalCal').val();
        $('.totalCalAkomodasi').each(function() {
            grandTotal += parseInt($(this).val()); // Or this.innerHTML, this.innerText
            // console.log('ini total' + grandTotal)

        });
        $('#grandTotalAkomodasi').maskMoney({
            formatOnBlur: true,
            precision: 0,
            decimal: ',',
        });
        $('#grandTotalAkomodasi').val(grandTotal);

        // $('#grandTotal').maskMoney({
        //     formatOnBlur: true,
        //     precision: 0,
        //     decimal: ',',
        // })

        // $('#grandTotal').val(grandTotal);
    }
</script>
<div class="box box-primary">
    <div class="box-header">
        <h3 class="box-title"><?= $title; ?></h3>
    </div>
    <!-- /.box-header -->
    <?php foreach ($rows_header as $data) :
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
                            <option value="N"></option>
                            <option value="Y" <?php if ($data->exc_ppn == "Y") echo "selected" ?>>Ya</option>
                            <option value="N" <?php if ($data->exc_ppn == "N") echo "selected" ?>>Tidak</option>
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
                                            <input type="text" name="item[<?= $count; ?>][price]" id="price_<?= $count; ?>" class="txtCal form-control" value="<?= number_format($data1->price); ?>" readonly>
                                        </td>
                                        <td align="center">
                                            <input type="text" name="item[<?= $count; ?>][discount]" class="form-control" id="discount_<?= $count; ?>" onkeyup="countTotal(<?= $count; ?>);" value="<?= $data1->discount; ?>" readonly>
                                        </td>
                                        <td align="center">
                                            <input type="text" name="item[<?= $count; ?>][total]" class="totalCal form-control" id="total_<?= $count; ?>" value="<?= number_format($data1->total); ?>" readonly>
                                        </td>
                                        <td align="center">
                                            <textarea type="text" name="item[<?= $count; ?>][descr]" class="form-control" id="descr_<?= $count; ?>" readonly><?= $data1->descr; ?></textarea>
                                        </td>
                                        <td align="center">
                                            <textarea type="text" name="item[<?= $count; ?>][notes]" class="form-control" id="notes_<?= $count; ?>" readonly><?= $data1->notes; ?></textarea>
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
                                        <input type="text" name="grandTotal" class="form-control" id="grandTotal" readonly>
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
                                    <td align="center">
                                        Aksi
                                    </td>
                                </tr>
                            </thead>
                            <tbody id="list_akomodasi">
                                <?php
                                $hitung = 1;
                                foreach ($akomm as $akoms) :
                                ?>
                                    <tr id="tr_list_akomodasi_<?= $hitung; ?>">
                                        <td align="center">
                                            <?= $hitung; ?>
                                        </td>
                                        <td align="center">
                                            <select name="akomodasi[<?= $hitung; ?>][akomodasi]" id="akomodasi[<?= $hitung; ?>][akomodasi]" class="form-select" disabled>
                                                <option value="">Pilih Opsi</option>
                                                <?php
                                                foreach ($list_akomm as $listakom) :
                                                    if ($listakom->name === $akoms->accommodation_name) {
                                                        echo "<option selected value='$akoms->accommodation_name'>$akoms->accommodation_name</option>";
                                                    } else {
                                                        echo "<option value='$listakom->name'>$listakom->name</option>";
                                                    }
                                                endforeach;
                                                ?>
                                            </select>
                                        </td>
                                        <td align="center">
                                            <input type="text" id="akomodasi[<?= $hitung; ?>][nilai]" name="akomodasi[<?= $hitung; ?>][nilai]" class="form-control" value="<?= number_format($akoms->nilai); ?>" readonly>
                                        </td>
                                        <td align="center">
                                            <input type="text" id="akomodasi[<?= $hitung; ?>][diskon]" name="akomodasi[<?= $hitung; ?>][diskon]" class="form-control" value="<?= $akoms->diskon; ?>" readonly>
                                        </td>
                                        <td align="center">
                                            <input type="text" id="nilaiAkoms" name="akomodasi[<?= $hitung; ?>][total]" class="totalCalAkomodasi form-control" value="<?= number_format($akoms->total); ?>" readonly>
                                        </td>
                                    </tr>
                                <?php
                                endforeach;
                                ?>
                            </tbody>
                            <tfoot>
                                <tr class="bg-gray">
                                    <td class="text-right" colspan="4">
                                        Sub Total
                                    </td>
                                    <td>
                                        <input class="form-control" type="text" id="grandTotalAkomodasi" readonly>
                                    </td>
                                    <td>

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
                <button class="btn btn-primary" id="simpan-bro" value="save" content="Save" type="button">Approve</button>
                <!-- <button class="btn btn-danger" type="button" value="back" content="back" onclick="back()">Kembali</button> -->
            </div>
        </form>
</div>