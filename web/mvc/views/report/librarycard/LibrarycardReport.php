<div class="row">
    <div class="col-sm-12" style="margin:10px 0px">
        <?php
            $pdf_preview_uri = base_url('librarycardreport/pdf/'.$classesID.'/'.$sectionID.'/'.$studentID.'/'.$type.'/'.$background);
            echo btn_printReport('librarycardreport', $this->lang->line('report_print'), 'printablediv');
            echo btn_pdfPreviewReport('librarycardreport',$pdf_preview_uri, $this->lang->line('report_pdf_preview'));
            echo btn_sentToMailReport('librarycardreport', $this->lang->line('report_send_pdf_to_mail'));
        ?>
    </div>
</div>
<div class="box">
    <div class="box-header bg-gray">
        <h3 class="box-title text-navy"><i class="fa fa-clipboard"></i> 
            <?=$this->lang->line('librarycardreport_report_for')?> -
            <?=isset($classes[$classesID]) ? $classes[$classesID]: '';?>
        </h3>
    </div><!-- /.box-header -->
    <div id="printablediv">
        <style>
            .librarycardreport {
                font-family: arial;    
                max-width:794px;
                max-height: 1123px;
                margin-left: auto;
                margin-right: auto;
                -webkit-print-color-adjust: exact;
            }
            /*IDcard Front Part Css Code*/
            .librarycardreport-frontend{
                margin: 3px;
                float: left;
                border: 1px solid #000;
                padding: 10px;
                width: 257px;
                text-align: center;
                height:290px;
                <?php if($background == 1) { ?>
                background:url("<?=base_url('uploads/default/idcard-border.png')?>")!important;
                background-size: 100% 100% !important;
                <?php } ?>
            }
            
            .librarycardreport-frontend h3{
                font-size: 20px;
                color: #1A2229;
            }
            
            .librarycardreport-frontend img{
                width: 50px;
                height: 50px;
                border: 1px solid #ddd;
                margin-bottom: 5px;
            }

            .librarycardreport-frontend p{
                text-align: left;
                font-size: 12px;
                margin-bottom: 0px;
                color: #1A2229;
            }

            /*ID Card Back Part Css Code*/
            .librarycardreport-backend{
                margin: 3px;
                /*float: left;*/
                float: right;
                border: 1px solid #1A2229;
                padding: 10px;
                width: 257px;
                text-align: center;
                height:290px;
                <?php if($background == 1) { ?>
                background:url("<?=base_url('uploads/default/idcard-border.png')?>")!important;
                background-size: 100% 100% !important;
                <?php } ?>
            }

            .librarycardreport-backend h3{
                background-color: #1A2229;
                color: #fff;
                font-size: 13px;
                padding: 5px 0px;
                margin:5px;
                margin-top: 13px;
            }

            .librarycardreport-backend h4{
                font-size: 11px;
                color: #1A2229;
                font-weight: bold;
                padding: 5px 0px;
            }

            .librarycardreport-backend p{
                font-size: 17px;
                color: #1A2229;
                font-weight: 500;
                line-height: 17px;
            }

            .librarycardreport-schooladdress {
                color: #1A2229 !important;
                font-weight: 500;
            }

            .librarycardreport-bottom {
                text-align: center;
                padding-top: 5px
            }

            .librarycardreport-qrcode{
                float: left;
                width: 50%;
            }

            .librarycardreport-qrcode img{
                width: 80px;
                height: 80px;
            }

            .librarycardreport-session{
                float: right;
                width: 50%;
            }
            
            .librarycardreport-session span{
                color: #1A2229;
                font-weight: bold;
                margin-top: 35px;
                overflow: hidden;
                float: left;
            }

            @media print {
                .librarycardreport {
                    max-width:794px;
                    max-height: 1123px;
                    margin-left: auto;
                    margin-right: auto;
                    -webkit-print-color-adjust: exact;
                    margin:0px auto;    
                }

                /*ID Card Front Part Css Code*/
                .librarycardreport-frontend{
                    margin: 1px;
                    float: left;
                    border: 1px solid #000;
                    padding: 10px;
                    width: 250px;
                }

                h3{
                    color: #1A2229 !important;
                }

                .librarycardreport-frontend .profile-view-dis .profile-view-tab {
                    width: 100%;
                    float: left;
                    margin-bottom: 0px;
                    padding: 0 15px;
                    font-size: 14px;
                    margin-top: 5px;
                }

                /*ID Card Back Part Css Code*/
                .librarycardreport-backend {
                    margin: 1px;
                    float: right;
                    border: 1px solid #1A2229;
                    padding: 10px;
                    width: 250px;
                }

                .librarycardreport-backend h3{
                    background-color: #1A2229 !important;
                    font-size: 12px;
                    color: #fff !important;
                    overflow: hidden;
                    display: block;
                }
            }

            .librarycardreport-frontend .profile-view-dis .profile-view-tab {
                width: 100%;
                float: left;
                margin-bottom: 0px;
                padding: 0 15px;
                font-size: 14px;
                margin-top: 5px;
            }
        </style>

        <div class="box-body" style="margin-bottom: 50px;">
            <div class="row">
                <div class="col-sm-12">
                    <?php if (customCompute($librarycards)) { ?>
                    <table class="librarycardreport">
                        <tr>
                            <?php $j= 0; $i=0; $c = customCompute($librarycards); foreach($librarycards as $librarycard) {
                            //TYPE 1 == Front Part
                            //TYPE 2 == Back Part
                            if($type == 1) { ?>
                                <td class="librarycardreport-frontend">
                                    <h3><?=$siteinfos->sname?></h3> 
                                    <img src="<?=imagelink($librarycard->photo)?>" alt="">
                                    <div class="profile-view-dis">
                                        <div class="profile-view-tab">
                                            <p><span><b><?=$this->lang->line('librarycardreport_name')?></b> </span>: <?=$librarycard->srname?></p>
                                        </div>
                                        <div class="profile-view-tab">
                                            <p><span><b><?=$this->lang->line('librarycardreport_libraryID')?></b> </span>: <?=$librarycard->lID?></p>
                                        </div>
                                        <div class="profile-view-tab">
                                            <p><span><b><?=$this->lang->line('librarycardreport_class')?></b> </span>: <?=isset($classes[$librarycard->srclassesID]) ? $classes[$librarycard->srclassesID] : ''?></p>
                                        </div>
                                        <div class="profile-view-tab">
                                            <p><span><b><?=$this->lang->line('librarycardreport_section')?></b> </span>: <?=isset($sections[$librarycard->srsectionID]) ? $sections[$librarycard->srsectionID] : ''?></p>
                                        </div>
                                        <div class="profile-view-tab">
                                            <p><span><b><?=$this->lang->line('librarycardreport_roll')?></b> </span>: <?=$librarycard->srroll?></p>
                                        </div>
                                        <div class="profile-view-tab">
                                            <p><span><b><?=$this->lang->line('librarycardreport_joining_date')?></b> </span>: <?=date('d M Y',strtotime((string) $librarycard->ljoindate))?></p>
                                        </div>
                                    </div>
                                </td>
                                <?php 
                                $i++; 
                                if($i==3) {
                                    $j++;
                                    $k = $c/3;
                                    $k = ceil($k);
                                    if($k == $j) {
                                        echo "";
                                    } else {
                                        echo "</tr><tr>";
                                    }
                                    $i=0;
                                }
                            } else { $i++;
                                    $filename = $librarycard->lID;
                                    $text = $this->lang->line('librarycardreport_id')." : ".$librarycard->lID;

                                    $filepath = FCPATH.'uploads/libraryQRcode/'.$filename.'.png';
                                    if(!file_exists($filepath)) {
                                        generate_qrcode($text,$filename,'libraryQRcode');
                                    }
                                ?>
                                <td class="librarycardreport-backend">
                                    <h3><?=$this->lang->line('librarycardreport_valid_up')?> <?=date('F Y',strtotime((string) $schoolyear->endingdate))?></h3>
                                    <h4><?=$this->lang->line('librarycardreport_please_return')?> : </h4>
                                    <p><?=$siteinfos->sname?></p>
                                    <div class="librarycardreport-schooladdress">
                                        <?=$siteinfos->address?>
                                    </div>
                                    <div class="librarycardreport-bottom">
                                        <div class="librarycardreport-qrcode">
                                            <img src="<?=base_url('uploads/libraryQRcode/'.$filename.'.png')?>" alt="">
                                        </div>
                                        <div class="librarycardreport-session">
                                            <span><?=$this->lang->line('librarycardreport_session')?> : <?=$schoolyear->schoolyear?></span>
                                        </div>
                                    </div>
                                </td>
                            <?php } } ?>
                        </tr>
                    </table>
                    <?php } else { ?>   
                        <div class="callout callout-danger">
                            <p><b class="text-info"><?=$this->lang->line('librarycardreport_data_not_found')?></b></p>
                        </div>
                    <?php } ?>
                </div>
            </div><!-- row -->
        </div><!-- Body -->
    </div>
</div>


<!-- email modal starts here -->
<form class="form-horizontal" role="form" action="<?=base_url('librarycardreport/send_pdf_to_mail');?>" method="post">
    <div class="modal fade" id="mail">
      <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only"><?=$this->lang->line('librarycardreport_close')?></span></button>
                <h4 class="modal-title"><?=$this->lang->line('librarycardreport_mail')?></h4>
            </div>
            <div class="modal-body">

                <?php
                    if (form_error('to')) {
                                    echo "<div class='form-group has-error' >";
                                } else {
                                    echo "<div class='form-group' >";
                                }
                ?>
                    <label for="to" class="col-sm-2 control-label">
                        <?=$this->lang->line("librarycardreport_to")?> <span class="text-red">*</span>
                    </label>
                    <div class="col-sm-6">
                        <input type="email" class="form-control" id="to" name="to" value="<?=set_value('to')?>" >
                    </div>
                    <span class="col-sm-4 control-label" id="to_error">
                    </span>
                </div>

                <?php
                    if (form_error('subject')) {
                    echo "<div class='form-group has-error' >";
                } else {
                    echo "<div class='form-group' >";
                }
                ?>
                    <label for="subject" class="col-sm-2 control-label">
                        <?=$this->lang->line("librarycardreport_subject")?> <span class="text-red">*</span>
                    </label>
                    <div class="col-sm-6">
                        <input type="text" class="form-control" id="subject" name="subject" value="<?=set_value('subject')?>" >
                    </div>
                    <span class="col-sm-4 control-label" id="subject_error">
                    </span>

                </div>

                <?php
                    if (form_error('message')) {
                    echo "<div class='form-group has-error' >";
                } else {
                    echo "<div class='form-group' >";
                }
                ?>
                    <label for="message" class="col-sm-2 control-label">
                        <?=$this->lang->line("librarycardreport_message")?>
                    </label>
                    <div class="col-sm-6">
                        <textarea class="form-control" id="message" style="resize: vertical;" name="message" value="<?=set_value('message')?>" ></textarea>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" style="margin-bottom:0px;" data-dismiss="modal"><?=$this->lang->line('close')?></button>
                <input type="button" id="send_pdf" class="btn btn-success" value="<?=$this->lang->line("librarycardreport_send")?>" />
            </div>
        </div>
      </div>
    </div>
</form>
<!-- email end here -->

<script type="text/javascript">
    
    function check_email(email) {
        var status = false;
        var emailRegEx = /^[A-Z0-9._%+-]+@[A-Z0-9.-]+\.[A-Z]{2,4}$/i;
        if (email.search(emailRegEx) == -1) {
            $("#to_error").html('');
            $("#to_error").html("<?=$this->lang->line('librarycardreport_mail_valid')?>").css("text-align", "left").css("color", 'red');
        } else {
            status = true;
        }
        return status;
    }


    $('#send_pdf').click(function() {
        var field = {
            'to'         : $('#to').val(), 
            'subject'    : $('#subject').val(), 
            'message'    : $('#message').val(),
            'classesID'  : '<?=$classesID?>',
            'sectionID'  : '<?=$sectionID?>',
            'studentID'     : '<?=$studentID?>',
            'type'       : '<?=$type?>',
            'background' : '<?=$background?>',
        };

        var to = $('#to').val();
        var subject = $('#subject').val();
        var error = 0;

        $("#to_error").html("");
        $("#subject_error").html("");

        if(to == "" || to == null) {
            error++;
            $("#to_error").html("<?=$this->lang->line('librarycardreport_mail_to')?>").css("text-align", "left").css("color", 'red');
        } else {
            if(check_email(to) == false) {
                error++
            }
        }

        if(subject == "" || subject == null) {
            error++;
            $("#subject_error").html("<?=$this->lang->line('librarycardreport_mail_subject')?>").css("text-align", "left").css("color", 'red');
        } else {
            $("#subject_error").html("");
        }

        if(error == 0) {
            $('#send_pdf').attr('disabled','disabled');
            $.ajax({
                type: 'POST',
                url: "<?=base_url('librarycardreport/send_pdf_to_mail')?>",
                data: field,
                dataType: "html",
                success: function(data) {
                    var response = JSON.parse(data);
                    if (response.status == false) {
                        $('#send_pdf').removeAttr('disabled');
                        if( response.to) {
                            $("#to_error").html("<?=$this->lang->line('librarycardreport_mail_to')?>").css("text-align", "left").css("color", 'red');
                        } 
                        if( response.subject) {
                            $("#subject_error").html("<?=$this->lang->line('librarycardreport_mail_subject')?>").css("text-align", "left").css("color", 'red');
                        }
                        if(response.message) {
                            toastr["error"](response.message)
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
                    } else {
                        location.reload();
                    }
                }
            });
        }
    });
</script>