<?php
$this->load->view('include/side_menu');
?>

<script>
    function ActionPreview(ObjectParam) {
        let TitleAction = ObjectParam.title;
        let CodeAction = ObjectParam.code;
        let LinkAction = ObjectParam.action;
        let Id = ObjectParam.id;

        loading_spinner_new();

        $('#MyModalTitle').text(TitleAction);
        $.post(base_url + active_controller + '/' + LinkAction, {
            'code': CodeAction,
            'id': Id
        }, function(response) {
            close_spinner_new();
            $("#MyModalDetail").html(response);
        });
        $("#MyModalView").modal('show');
    }
</script>
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
                                <span class="badge bg-yellow"><?php if ($data->sts_subcon == "OPN") {
                                                                    echo "OPEN";
                                                                } ?></span>
                            </td>
                            <td align="center">
                                <?php
                                if ($data->sts_subcon == 'OPN') {
                                ?>
                                    <a href="<?= site_url('Subcon_open/view/' . $data->id); ?>" class="btn btn-sm btn-primary" title="VIEW DATA" data-role="qtip"><i class="fa fa-eye"></i></a>
                                    <?php
                                    if ($akses_menu['update'] == '1') {
                                    ?>
                                        <a href="<?= site_url('Subcon_open/edit/' . $data->id); ?>" class="btn btn-sm btn-warning" title="EDIT DATA" data-role="qtip"><i class="fa fa-edit"></i></a>
                                    <?php
                                    }
                                    ?>
                                    <?php
                                    if ($akses_menu['approve'] == '1') {
                                    ?>
                                        <button type="button" href="" onclick="ActionPreview({code:'<?= $data->subcon_pono; ?>', id:'<?= $data->id; ?>', action:'modalEdit', title:'APPROVE'})" class="btn btn-sm btn-danger" title="APPROVE" data-role="qtip"><i class="fa fa-save"></i></button>
                                <?php
                                    }
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

<div class="modal fade" id="MyModalView" tabindex="-1" role="dialog" aria-labelledby="MyModal" data-backdrop="static">
    <div class="modal-dialog" role="document" style="min-width:70% !important;">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="MyModalTitle"></h5>
                <button class="close" data-dismiss="modal" aria-label="close" id="btn-modal-close">
                    <span aria-hidden="true"><i class="fa fa-close"></i></span>
                </button>
            </div>
            <div class="modal-body" id="MyModalDetail">

            </div>
        </div>
    </div>
</div>

<?php $this->load->view('include/footer'); ?>