<div class="box">
    <div class="box-header">
        <h3 class="box-title"><i class="fa iniicon-leaveapplication"></i> <?= $this->lang->line('panel_title') ?></h3>
        <ol class="breadcrumb">
            <li><a href="<?= base_url("dashboard/index") ?>"><i class="fa fa-laptop"></i> <?= $this->lang->line('menu_dashboard') ?></a></li>
            <li class="active"><?= $this->lang->line('menu_leaveapplication') ?></li>
        </ol>
    </div><!-- /.box-header -->
    <!-- form start -->
    <div class="box-body">
        <div class="row">
            <div class="col-sm-12">
                <div id="hide-table">
                    <table id="example1" class="table table-striped table-bordered table-hover dataTable no-footer">
                        <thead>
                            <tr>
                                <th><?= $this->lang->line('slno') ?></th>
                                <th><?= $this->lang->line('leaveapplication_applicant') ?></th>
                                <th><?= $this->lang->line('leaveapplication_role') ?></th>
                                <th><?= $this->lang->line('leaveapplication_category') ?></th>
                                <th><?= $this->lang->line('leaveapplication_date') ?></th>
                                <th><?= $this->lang->line('leaveapplication_schedule') ?></th>
                                <th><?= $this->lang->line('leaveapplication_days') ?></th>
                                <th><?= $this->lang->line('leaveapplication_attachment') ?></th>
                                <th><?= $this->lang->line('leaveapplication_status') ?></th>
                                <?php if (permissionChecker('leaveapplication') || permissionChecker('leaveapplication')) { ?>
                                    <th><?= $this->lang->line('action') ?></th>
                                <?php } ?>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (customCompute($leaveapplications)) {
                                $i = 1;
                                foreach ($leaveapplications as $leaveapplication) { ?>
                                    <tr>
                                        <td data-title="<?= $this->lang->line('slno') ?>">
                                            <?php echo $i; ?>
                                        </td>
                                        <td data-title="<?= $this->lang->line('leaveapplication_applicant') ?>">
                                            <?php if (isset($allUser[$leaveapplication->create_usertypeID][$leaveapplication->create_userID])) {
                                    echo $allUser[$leaveapplication->create_usertypeID][$leaveapplication->create_userID]->name;
                                } ?>
                                        </td>
                                        <td data-title="<?= $this->lang->line('leaveapplication_role') ?>">
                                            <?= isset($allUserTypes[$leaveapplication->create_usertypeID]) ? $allUserTypes[$leaveapplication->create_usertypeID] : ""; ?>
                                        </td>
                                        <td data-title="<?= $this->lang->line('leaveapplication_category') ?>">
                                            <?= isset($leavecategorys[$leaveapplication->leavecategoryID]) ? $leavecategorys[$leaveapplication->leavecategoryID] : '' ?>
                                        </td>
                                        <td data-title="<?= $this->lang->line('leaveapplication_date') ?>">
                                            <?= date('d M Y', strtotime((string) $leaveapplication->apply_date)) ?>
                                        </td>
                                        <td data-title="<?= $this->lang->line('leaveapplication_schedule') ?>">
                                            <?= date('d M Y', strtotime((string) $leaveapplication->from_date)) ?> - <?= date('d M Y', strtotime((string) $leaveapplication->to_date)) ?>
                                        </td>
                                        <td data-title="<?= $this->lang->line('leaveapplication_days') ?>">
                                            <?php echo $leaveapplication->leave_days; ?>
                                        </td>

                                        <td data-title="<?= $this->lang->line('leaveapplication_attachment') ?>">
                                            <?php
                                            if ($leaveapplication->attachmentorginalname) {
                                                echo btn_download_file('leaveapplication/download/' . $leaveapplication->leaveapplicationID, namesorting($leaveapplication->attachmentorginalname, 12), $this->lang->line('download'));
                                            }
                                            ?>
                                        </td>

                                        <td data-title="<?= $this->lang->line('leaveapplication_status') ?>">
                                            <?php if ($leaveapplication->status == null) { ?>
                                                <button type="button" class="btn btn-warning btn-xs"><?= $this->lang->line('leaveapplication_status_pending') ?></button>
                                            <?php } elseif ($leaveapplication->status == 1) { ?>
                                                <button type="button" class="btn btn-success btn-xs"><?= $this->lang->line('leaveapplication_status_approve') ?></button>
                                            <?php } else { ?>
                                                <button type="button" class="btn btn-danger btn-xs"><?= $this->lang->line('leaveapplication_status_declined') ?></button>
                                            <?php } ?>
                                        </td>
                                        <?php if (permissionChecker('leaveapplication')) { ?>
                                            <td data-title="<?= $this->lang->line('action') ?>">
                                                <?php echo btn_sm_global('leaveapplication/view/' . $leaveapplication->leaveapplicationID, $this->lang->line('view'), 'fa fa-check-square-o', 'btn-info'); ?>
                                                <?php if (($siteinfos->school_year == $this->session->userdata('defaultschoolyearID')) || ($this->session->userdata('usertypeID') == 1)) { ?>
                                                    <?php if ($leaveapplication->status == null) { ?>
                                                        <button class="btn btn-primary btn-xs mrg leave-status" data-status="1" data-id="<?= $leaveapplication->leaveapplicationID ?>" data-placement="top" data-toggle="tooltip" data-original-title="Approve"><i class="fa fa-circle-o"></i></button>
                                                        <button class="btn btn-danger btn-xs mrg leave-status" data-status="0" data-id="<?= $leaveapplication->leaveapplicationID ?>" data-placement="top" data-toggle="tooltip" data-original-title="Decline"><i class="fa fa-dot-circle-o"></i></button>
                                                    <?php } ?>
                                                <?php } ?>
                                            </td>
                                        <?php } ?>
                                    </tr>
                            <?php $i++;
                                }
                            } ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    $('.leave-status').click(function() {

        if (confirm("This cannot be undone. are you sure?")) {
            var id = $(this).data("id");
            var status = $(this).data("status");

            if ((status != '' || status != null) && (id != '')) {
                $.ajax({
                    type: 'POST',
                    url: "<?= base_url('leaveapplication/status') ?>",
                    data: "id=" + id + "&status=" + status,
                    dataType: "html",
                    success: function(data) {
                        location.reload();
                        if (data == 'Success') {
                            toastr["success"]("Success")
                            toastr.options = {
                                "closeButton": true,
                                "debug": false,
                                "newestOnTop": false,
                                "progressBar": false,
                                "positionClass": "toast-top-right",
                                "preventDuplicates": false,
                                "onclick": null,
                                "showDuration": "500",
                                "hideDuration": "500",
                                "timeOut": "5000",
                                "extendedTimeOut": "1000",
                                "showEasing": "swing",
                                "hideEasing": "linear",
                                "showMethod": "fadeIn",
                                "hideMethod": "fadeOut"
                            }
                        } else {
                            toastr["error"]("Error")
                            toastr.options = {
                                "closeButton": true,
                                "debug": false,
                                "newestOnTop": false,
                                "progressBar": false,
                                "positionClass": "toast-top-right",
                                "preventDuplicates": false,
                                "onclick": null,
                                "showDuration": "500",
                                "hideDuration": "500",
                                "timeOut": "5000",
                                "extendedTimeOut": "1000",
                                "showEasing": "swing",
                                "hideEasing": "linear",
                                "showMethod": "fadeIn",
                                "hideMethod": "fadeOut"
                            }
                        }
                    }
                });
            }
        } else {
            return false;
        }

    });
</script>