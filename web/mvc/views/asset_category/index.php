<div class="box">
    <div class="box-header">
        <h3 class="box-title"><i class="fa fa-life-ring"></i> <?=$this->lang->line('panel_title')?></h3>


        <ol class="breadcrumb">
            <li><a href="<?=base_url("dashboard/index")?>"><i class="fa fa-laptop"></i> <?=$this->lang->line('menu_dashboard')?></a></li>
            <li class="active"><?=$this->lang->line('panel_title')?></li>
        </ol>
    </div><!-- /.box-header -->
    <!-- form start -->
    <div class="box-body">
        <div class="row">
            <div class="col-sm-12">

                <?php
                   if(permissionChecker('asset_category_add')) {
                ?>
                    <h5 class="page-header">
                        <a href="<?php echo base_url('asset_category/add') ?>">
                            <i class="fa fa-plus"></i>
                            <?=$this->lang->line('add_title')?>
                        </a>
                    </h5>
                <?php } ?>
                <div id="hide-table">
                    <table id="example1" class="table table-striped table-bordered table-hover dataTable no-footer">
                        <thead>
                            <tr>
                                <th class=""><?=$this->lang->line('slno')?></th>
                                <th class=""><?=$this->lang->line('asset_category')?></th>
                                <?php if(permissionChecker('asset_category_edit') || permissionChecker('asset_category_delete')) { ?>
                                    <th class="col-md-2"><?=$this->lang->line('action')?></th>
                                <?php } ?>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if(customCompute($asset_categorys)) {$i = 1; foreach($asset_categorys as $asset_category) { ?>
                                <tr>
                                    <td data-title="<?=$this->lang->line('slno')?>">
                                        <?php echo $i; ?>
                                    </td>
                                    <td data-title="<?=$this->lang->line('asset_category')?>">
                                        <?php
                                            if (strlen((string) $asset_category->category) > 25) {
                    echo strip_tags(substr((string) $asset_category->category, 0, 25)."...");
                } else {
                    echo strip_tags(substr((string) $asset_category->category, 0, 25));
                }
                                        ?>
                                    </td>
                                    <?php if(permissionChecker('asset_category_edit') || permissionChecker('asset_category_delete')) { ?>
                                        <td data-title="<?=$this->lang->line('action')?>">
                                            <?php echo btn_edit('asset_category/edit/'.$asset_category->asset_categoryID, $this->lang->line('edit')) ?>
                                            <?php echo btn_delete('asset_category/delete/'.$asset_category->asset_categoryID, $this->lang->line('delete')) ?>
                                        </td>
                                    <?php } ?>
                                </tr>
                            <?php $i++; }} ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>