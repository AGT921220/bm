<div class="box">
    <div class="box-header">
        <h3 class="box-title"><i class="fa iniicon-sociallink"></i> <?=$this->lang->line('panel_title')?></h3>
        <ol class="breadcrumb">
            <li><a href="<?=base_url("dashboard/index")?>"><i class="fa fa-laptop"></i> <?=$this->lang->line('menu_dashboard')?></a></li>
            <li><a href="<?=base_url("sociallink/index")?>"><?=$this->lang->line('menu_sociallink')?></a></li>
            <li class="active"><?=$this->lang->line('menu_add')?> <?=$this->lang->line('menu_sociallink')?></li>
        </ol>
    </div><!-- /.box-header -->
    <!-- form start -->
    <div class="box-body">
        <div class="row">
            <div class="col-sm-10">
                <form class="form-horizontal" role="form" method="post">
                    
                    <?php
                    if (form_error('userroleID')) {
    echo "<div class='form-group has-error' >";
} else {
    echo "<div class='form-group' >";
}
                    ?>
                        <label for="userroleID" class="col-sm-2 control-label">
                            <?=$this->lang->line("sociallink_role")?> <span class="text-red">*</span>
                        </label>
                        <div class="col-sm-6">
                            <?php
                                $usertypeArray = array('0' => $this->lang->line('sociallink_role_select'));
                                if(customCompute($usertypes)) {
                                    foreach($usertypes as $usertype) {
                                        $usertypeArray[$usertype->usertypeID] = $usertype->usertype;
                                    }
                                }
                                echo form_dropdown("userroleID", $usertypeArray, set_value("userroleID"), "id='userroleID' class='form-control'");
                            ?>
                        </div>
                        <span class="col-sm-4 control-label">
                            <?php echo form_error('userroleID'); ?>
                        </span>
                    </div>

                    <?php
                    if (form_error('userID')) {
                                echo "<div class='form-group has-error' >";
                            } else {
                                echo "<div class='form-group' >";
                            }
                    ?>
                        <label for="userID" class="col-sm-2 control-label">
                            <?=$this->lang->line("sociallink_user")?> <span class="text-red">*</span>
                        </label>
                        <div class="col-sm-6">
                            <?php
                                if(!isset($userArray)) {
                                    $userArray = array('0' => $this->lang->line('sociallink_user_select'));
                                } else {
                                    $userArray;
                                }

                                echo form_dropdown("userID", $userArray, set_value("userID"), "id='userID' class='form-control'");
                            ?>
                        </div>
                        <span class="col-sm-4 control-label">
                            <?php echo form_error('userID'); ?>
                        </span>
                    </div>

                    <?php
                    if (form_error('facebook')) {
                                echo "<div class='form-group has-error' >";
                            } else {
                                echo "<div class='form-group' >";
                            }
                    ?>
                        <label for="facebook" class="col-sm-2 control-label">
                            <?=$this->lang->line("sociallink_facebook")?>
                        </label>
                        <div class="col-sm-6">
                            <input type="text" class="form-control" id="facebook" name="facebook" value="<?=set_value('facebook')?>">
                        </div>
                        <span class="col-sm-4 control-label">
                            <?php echo form_error('facebook'); ?>
                        </span>
                    </div>

                    <?php
                    if (form_error('twitter')) {
                        echo "<div class='form-group has-error' >";
                    } else {
                        echo "<div class='form-group' >";
                    }
                    ?>
                        <label for="twitter" class="col-sm-2 control-label">
                            <?=$this->lang->line("sociallink_twitter")?>
                        </label>
                        <div class="col-sm-6">
                            <input type="text" class="form-control" id="twitter" name="twitter" value="<?=set_value('twitter')?>">
                        </div>
                        <span class="col-sm-4 control-label">
                            <?php echo form_error('twitter'); ?>
                        </span>
                    </div>

                    <?php
                    if (form_error('linkedin')) {
                        echo "<div class='form-group has-error' >";
                    } else {
                        echo "<div class='form-group' >";
                    }
                    ?>
                        <label for="linkedin" class="col-sm-2 control-label">
                            <?=$this->lang->line("sociallink_linkedin")?>
                        </label>
                        <div class="col-sm-6">
                            <input type="text" class="form-control" id="linkedin" name="linkedin" value="<?=set_value('linkedin')?>" >
                        </div>
                        <span class="col-sm-4 control-label">
                            <?php echo form_error('linkedin'); ?>
                        </span>
                    </div>

                    <?php
                        if (form_error('googleplus')) {
                        echo "<div class='form-group has-error' >";
                    } else {
                        echo "<div class='form-group' >";
                    }
                    ?>
                        <label for="googleplus" class="col-sm-2 control-label">
                            <?=$this->lang->line("sociallink_googleplus")?>
                        </label>
                        <div class="col-sm-6">
                            <input type="text" class="form-control" id="googleplus" name="googleplus" value="<?=set_value('googleplus')?>" >
                        </div>
                        <span class="col-sm-4 control-label">
                            <?php echo form_error('googleplus'); ?>
                        </span>
                    </div>

                    <div class="form-group">
                        <div class="col-sm-offset-2 col-sm-8">
                            <input type="submit" class="btn btn-success" value="<?=$this->lang->line("add_sociallink")?>" >
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    $('#userroleID').select2();
    $('#userID').select2();
    $("#userroleID").change(function() {
        var userroleID = $(this).val();
        $.ajax({
            type: 'POST',
            url: "<?=base_url('sociallink/gerUser')?>",
            data: {"userroleID":userroleID},
            dataType: "html",
            success: function(data) {
               $('#userID').html(data);
            }
        });
    });
</script>
