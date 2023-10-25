<?php
$this->load->view('include/side_menu');
?>
<div class="box box-primary">
    <div class="box-header">
        <h3 class="box-title"><?= $title; ?></h3>
        <div class="box-body">
            <table id="example1" class="table table-bordered table-striped">
                <thead>
                    <tr class='bg-blue'>
                        <th class="text-center">No.</th>
                        <th class="text-center">Subcon PO</th>
                        <th class="text-center">Tanggal Order</th>
                        <th class="text-center">Subcon</th>
                        <th class="text-center">Alamat</th>
                        <th class="text-center">Incentive</th>
                        <th class="text-center">Status</th>
                        <th class="text-center">Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $i = 1;
                    foreach ($row as $data) :
                    ?>

                        <tr>
                            <td align="center">
                                <?= $i++; ?>
                            </td>
                            <td align="center">
                                <?= $data->subcon_pono; ?>
                            </td>
                            <td align="center">
                                <?= $data->datet; ?>
                            </td>
                            <td align="center">
                                <?= $data->supplier_name; ?>
                            </td>
                            <td align="center">
                                <?= $data->address; ?>
                            </td>
                            <td align="center">
                                <?= number_format($data->grand_tot); ?>
                            </td>
                            <td align="center">
                                <span class="badge bg-green"><?php if ($data->sts_subcon === 'APV') {
                                                                    echo
                                                                    'APPROVED';
                                                                } ?></span>
                            </td>
                            <td align="center">
                                <?php
                                if ($data->sts_subcon == 'APV') {
                                ?>
                                    <a href="<?= site_url('Subcon_approve/edit/' . $data->id); ?>" class="btn btn-sm btn-primary" title="Re-open Data" data-role="qtip"><i class="fa fa-edit"></i></a>
                                <?php
                                }
                                ?>
                            </td>
                        </tr>

                    <?php endforeach; ?>

                </tbody>
            </table>
        </div>
    </div>
</div>

<?php $this->load->view('include/footer'); ?>