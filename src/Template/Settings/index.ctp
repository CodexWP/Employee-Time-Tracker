<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Timesheet[]|\Cake\Collection\CollectionInterface $timesheets
 */
?>
<div class="row">
    <div class="col-sm-12">
        <div class="card">
            <div class="card-block">
                <div class="row m-b-20">
                    <div class="col-sm-12">
                        <div class="border-bottom p-b-10">
                            <div class="row">
                                <h3 class="text-themecolor col-sm-6"><i class="fa fa-gear m-r-10" aria-hidden="true"></i> System settings</h3>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-8 offset-sm-2">
                        <?= $this->Form->create('settingsform',['method'=>'post','enctype' => 'multipart/form-data']) ?>

                            <div class="form-group row">
                                <label for="staticEmail" class="col-sm-4 col-form-label">System Logo<br><small>(150 X 35px)</small></small></label>
                                <div class="col-sm-8">

                                    <?php
                                    if(isset($settings['system_logo']))
                                        echo '<img src="'.$settings['system_logo'].'" class="img-thumbnail m-b-20">';
                                    else
                                        echo $this->Html->image('logo.png',['class'=>'img-thumbnail m-b-20']);
                                    ?>
                                    <input name="system_logo" type="file" class="form-control">
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="staticEmail" class="col-sm-4 col-form-label">Responsive Logo<br><small>(35 X 35px)</small></small></label>
                                <div class="col-sm-8">
                                    <?php
                                    if(isset($settings['mobile_logo']))
                                        echo '<img src="'.$settings['mobile_logo'].'" class="img-thumbnail m-b-20">';
                                    else
                                        echo $this->Html->image('mobile_logo.png',['class'=>'img-thumbnail m-b-20']);
                                    ?>
                                    <input name="mobile_logo" type="file" class="form-control">
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="staticEmail" class="col-sm-4 col-form-label">System Title</label>
                                <div class="col-sm-8">
                                    <input name="system_title" type="text" class="form-control" value="<?=isset($settings['system_title'])?$settings['system_title']:''?>" placeholder="Enter system title">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="staticEmail" class="col-sm-4 col-form-label">Copyright</label>
                                <div class="col-sm-8">
                                    <input autocomplete="off" name="footer_text" type="text" class="form-control" value="<?=isset($settings['footer_text'])?$settings['footer_text']:''?>" placeholder="Enter footer copyright text">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="staticEmail" class="col-sm-4 col-form-label">System Email</label>
                                <div class="col-sm-8">
                                    <input autocomplete="off" name="system_email" type="text" class="form-control" value="<?=isset($settings['system_email'])?$settings['system_email']:''?>" placeholder="Enter system email">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="staticEmail" class="col-sm-4 col-form-label">Email Configuration</small></label>
                                <div class="col-sm-8">
                                    <?=$this->Form->radio('email_config', ['phpmail'=>'PHP Mail','smtp'=>'SMTP'],['class'=>'m-l-20 m-r-5','value'=>isset($settings['email_config'])?$settings['email_config']:'phpmail'])?>
                                    <input type="hidden" name="smtp_config" value="<?=isset($settings['smtp_config'])?$settings['smtp_config']:',,,,'?>">

                                    <small id="send-test-mail" class="m-l-10 text-primary cursor-pointer"><i class="fa fa-send-o"></i> Send Test Mail</small>
                                    <small id="send-test-mail-loading" class="m-l-10 text-themecolor" style="display: none;"><i class="m-l-10 fa fa-circle-o-notch fa-spin fa-fw"></i> Sending...</small>
                                </div>
                            </div>
                            <div class="form-group row m-t-40">
                                <label for="staticEmail" class="col-sm-4 col-form-label">Your Password</label>
                                <div class="col-sm-8">
                                    <input  name="admin_pass" type="password" class="form-control" autocomplete="off" placeholder="Enter admin password to change.">
                                </div>
                            </div>
                            <div class="form-group row m-t-40">
                                <div class="col-sm-12 text-center">
                                    <button class="btn btn-primary"><i class="fa fa-check"></i> Update Settings</button>
                                </div>
                            </div>
                        <?= $this->Form->end() ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Start Edit Confirm Modal -->
<div class="modal fade" id="smtp-modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header p-20 topbar position-relative">
                <h5 class="modal-title text-white" id="exampleModalLabel"><i class="fa fa-clock-o" aria-hidden="true"></i> SMTP Configuration</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <div class="modal-body p-20">
                <div class="form-group">
                    <label for="">Host Name</label>
                    <input type="text" name="smtp_host" class="form-control">
                </div>
                <div class="form-group">
                    <label for="">User Name</label>
                    <input type="text" name="smtp_user" class="form-control">
                </div>
                <div class="form-group">
                    <label for="">Password</label>
                    <input type="text" name="smtp_pass" class="form-control">
                </div>
                <div class="row">
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label for="">SMTP Port</label>
                            <input type="text" name="smtp_port" class="form-control">
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label for="">Protocol</label>
                            <select name="smtp_protocol" class="form-control">
                                <option value="none">None</option>
                                <option value="ssl">SSL</option>
                                <option value="tls">TLS</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer p-20">
                <button id="btn_save_smtp" type="button" class="btn btn-primary">Save Configure</button>
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
<!-- End Edit Confirm Modal -->
<script type="application/javascript">
    var ajaxurl = $("meta[name='ajax']").attr("url");
    $("input#email-config-smtp").click(function(){
        $smtp = $("#smtp-modal");
        var smtp_config = $("input[name='smtp_config']").val();
        var smtp_array = smtp_config.split(',');
        $smtp.find("input[name='smtp_host']").val(smtp_array[0]);
        $smtp.find("input[name='smtp_user']").val(smtp_array[1]);
        $smtp.find("input[name='smtp_pass']").val(smtp_array[2]);
        $smtp.find("input[name='smtp_port']").val(smtp_array[3]);
        $smtp.find("select[name='smtp_protocol']").val(smtp_array[4]);
        $("#smtp-modal").modal('show');
    });
    $("#smtp-modal").find("#btn_save_smtp").click(function(){
        var host =  $smtp.find("input[name='smtp_host']").val();
        var user =  $smtp.find("input[name='smtp_user']").val();
        var pass =  $smtp.find("input[name='smtp_pass']").val();
        var port =  $smtp.find("input[name='smtp_port']").val();
        var protocol =  $smtp.find("select[name='smtp_protocol']").val();
        var smtp_config = host+','+user+','+pass+','+port+','+protocol;
        $("input[name='smtp_config']").val(smtp_config);
        $("#smtp-modal").modal('hide');
    });

    $("#send-test-mail").click(function(){
        $(this).hide();
        $("#send-test-mail-loading").show();
        $.ajax({
            type: "GET",
            url: ajaxurl+"?op=sendtestmail",
            dataType: "json",
            success: function (resp) {
                if(resp.status=='success')
                    alert("Test message has been sent. Check your email.")
                else
                    alert("Test message could not be sent. Try again.");

                $("#send-test-mail-loading").hide();
                $("#send-test-mail").show();
            }
        })
    })

</script>