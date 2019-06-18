<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Timesheet[]|\Cake\Collection\CollectionInterface $timesheets
 */
$this->start('css');
echo $this->Html->css('daterangepicker/daterangepicker.css');
$this->end();
$screenshots = $ctpdata['screenshots'];
$projects = $ctpdata['projects'];
?>
<div class="row">
    <div class="col-sm-12">
        <div class="card">
            <div class="card-block">
                <form method="get">
                    <div class="row m-b-20">
                        <div class="col-sm-12">
                            <div class="border-bottom p-b-10">
                                <div class="row">
                                    <h3 class="text-themecolor col-sm-6"><i class="fa fa-picture-o"></i> Screen Shots</h3>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="border-bottom p-b-10">
                                <div class="row">
                                    <div class="input-group col-sm-5 m-b-5">
                                        <button class="btn btn-default prepend-button input-sm border-right-radius-0"><i class="fa fa-clock-o"></i> Range</button>
                                        <input autocomplete="off" name="daterange" type="text" class="form-control text-center m-r-5 input-sm" id="daterangepickerinput" value="<?=$this->request->getQuery('daterange')?>">
                                    </div>

                                    <div class="input-group col-sm-5 m-b-5">
                                        <button class="btn btn-default prepend-button input-sm border-right-radius-0"><i class="fa fa-tasks " aria-hidden="true"></i> Project</button>
                                        <?php  echo $this->Form->select('project_id',
                                            $projects,
                                            ['value' => $this->request->getQuery('project_id'),'class'=>'form-control input-sm','empty' => 'CHOOSE ONE','required'=>'true']
                                        );
                                        ?>
                                    </div>
                                    <div class="col-sm-2">
                                        <button type="submit" class="btn btn-primary input-sm"><i class="fa fa-filter"></i> Apply Filter</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
                <div class="row m-t-10">
                    <div class="col-sm-12">
                        <button style="display:none;" id="btn-delete-screenshot" class="btn btn-danger btn-sm" disabled="disabled"><i class="fa fa-trash"></i> Delete Selected</button>
                    </div>
                </div>
                <?php
                $tmp_time_slot ='';
                $count=0;
                foreach ($screenshots as $scr)
                {
                    $count++;
                    $day = $scr->day->format('M d, Y');
                    $pro_name = $scr->project_name?$scr->project_name:'No Project Name';
                    $scr_time = $scr->screenshot_time->format('h:i a');
                    $time_slot  =$scr->time_slot->format('Y/m/d h a');
                    $time_hour = $scr->time_slot->format('h a');
                    $scr_url = $scr->screenshot?$scr->screenshot:$this->Url->build('/img/no-screenshot.jpg', true);

                    $ks = $scr->keystrokes_count?$scr->keystrokes_count:0;
                    $mm = $scr->mousemove_count?$scr->mousemove_count:0;
                    $ks = ($ks*100)/250;

                    if($tmp_time_slot=='') {
                        $tmp_time_slot = $time_slot;
                        echo '<div class="row m-t-30">
                                <div class="col-sm-12">
                                    <div class="border rounded p-10">
                                        <h4 class="border-bottom p-b-10 m-b-10 text-primary"><i class="fa fa-clock-o"></i> '.$time_hour.' - <small>'.$day.'</small></h4>
                                        <div class="row m-0">';
                    }

                    if($tmp_time_slot==$time_slot) {
                        echo '<div class="col-sm-2 p-l-5 p-r-5">
                                <div class="border rounded text-center single-screenshot">
                                    <img style="width:100%;height:auto;" src="'.$scr_url.'">
                                    <small id="time">'.$scr_time.'</small><br>
                                    <small id="project" style="background: #f2f7f8;padding: 5px;border-radius: 5px;border:1px solid gainsboro;">'.$pro_name.'</small>
                                    <br>
                                    <i class="fa fa-keyboard-o text-themecolor d-inline-block" aria-hidden="true"></i>
                                    <div class="progress d-inline-block" style="width:30%;height: 5px">
                                        <div class="progress-bar bg-success keystroke" role="progressbar" style="width: '.$ks.'%" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"></div>
                                    </div>&nbsp;
                                    <i class="fa fa-mouse-pointer text-themecolor d-inline-block" aria-hidden="true"></i>
                                    <div class="progress d-inline-block" style="width:30%;height: 5px">
                                        <div class=" progress-bar bg-success mousemove" role="progressbar" style="width: '.$mm.'%" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"></div>
                                    </div>
                                </div>
                            </div>';
                    }
                    else{
                        echo '</div></div></div></div>';
                        echo '<div class="row m-t-30">
                                <div class="col-sm-12">
                                    <div class="border rounded p-10">
                                        <h4 class="border-bottom p-b-10 m-b-10 text-primary"><i class="fa fa-clock-o"></i> '.$time_hour.' - <small>'.$day.'</small></h4>
                                        <div class="row m-0">';
                        echo '<div class="col-sm-2 p-l-5 p-r-5">
                                <div class="border rounded text-center single-screenshot">
                                    <img style="width:100%;height:auto;" src="'.$scr_url.'">
                                    <small class="time">'.$scr_time.'</small><br>
                                    <small class="project" style="background: #f2f7f8;padding: 5px;border-radius: 5px;border:1px solid gainsboro;">'.$pro_name.'</small>
                                    <br>
                                    <i class="fa fa-keyboard-o text-themecolor d-inline-block" aria-hidden="true"></i>
                                    <div class="progress d-inline-block" style="width:30%;height: 5px">
                                        <div class="progress-bar bg-success keystroke" role="progressbar" style="width: '.$ks.'%" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"></div>
                                    </div>&nbsp;
                                    <i class="fa fa-mouse-pointer text-themecolor d-inline-block" aria-hidden="true"></i>
                                    <div class="progress d-inline-block" style="width:30%;height: 5px">
                                        <div class="progress-bar bg-success mousemove" role="progressbar" style="width: '.$mm.'%" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"></div>
                                    </div>
                                </div>
                            </div>';

                        $tmp_time_slot = $time_slot;
                    }
                }

                if($count>0) {
                    echo '</div></div></div></div>';
                }
                else
                {
                    echo '<p class="text-center m-t-30">No screenshots are available.</p>';
                }
                ?>

            </div>
        </div>
    </div>
</div>


<!-- Start add manual time Confirm Modal -->
<div class="modal fade" id="addmanualtime_modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header p-20 topbar position-relative">
                <h5 class="modal-title text-white" id="exampleModalLabel"><i class="fa fa-clock-o" aria-hidden="true"></i> Add Manual Time</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <?= $this->Form->create('amt_form',['url'=>['action'=>'addmanualtime'],'enctype' => 'multipart/form-data']) ?>

            <div class="modal-body p-30">
                <div class="form-group">
                    <label for="projectname">Project Name *</label>
                    <?php  echo $this->Form->select('project_id',
                        $projects,
                        ['value' => $this->request->getQuery('project_id'),'class'=>'form-control','empty' => 'CHOOSE ONE','required'=>'true']
                    );
                    ?>
                    <input type="hidden" name="project_name" value="">
                    <script>
                        $("#addmanualtime_modal").find("select[name='project_id']").change(function(){
                            if($(this).val()!='') {
                                $text = $("#addmanualtime_modal").find("select[name='project_id'] option:selected").text();
                                $("#addmanualtime_modal").find("input[name='project_name']").val($text);
                            }
                        })
                    </script>
                </div>
                <div class="form-group">
                    <label for="selectdate">Select a Member *</label>
                    <?php  echo $this->Form->select('mid',
                        $companymembers,
                        ['class'=>'form-control','empty' => 'CHOOSE ONE','id'=>'amt_mid_input','required'=>'true']
                    );
                    ?>
                </div>
                <div class="form-group">
                    <label for="selectdate">Select Date *</label>
                    <input name="date" class="form-control" type="text" autocomplete="off" id="amt_date_input" placeholder="Click and select a date from picker." required>
                </div>
                <div class="form-group">
                    <div class="row">
                        <div class="col-sm-6">
                            <select class="form-control" name="_timefrom" id="_timefrom" required="true"></select>
                        </div>
                        <div class="col-sm-6">
                            <select class="form-control" name="_timeto" id="_timeto" required="true"></select>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer p-20">
                <button type="submit" class="btn btn-primary">Add Time</button>
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
            </div>
            <?= $this->Form->end() ?>
        </div>
    </div>
</div>
<!-- End Edit Confirm Modal -->

<!-- Start Screenshot Delete Confirm Modal -->
<div class="modal fade" id="deletescr_modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header p-20 topbar position-relative">
                <h5 class="modal-title text-white" id="exampleModalLabel"><i class="fa fa-exclamation-triangle" aria-hidden="true"></i> Delete Confirmation</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body p-20">
                <div class="text-center p-b-30">
                    <h1 class="text-danger"><i class="fa fa-exclamation-triangle" aria-hidden="true"></i></h1>
                    <h3>Are you sure?</h3> <data id="total_scrs_del"></data> screenshots will be deleted.
                </div>
            </div>
            <div class="modal-footer p-20">
                <?= $this->Form->create('screenshotdelete_form',['url'=>['action'=>'delete']]) ?>
                <?=$this->Form->hidden('sids',['id'=>'total_sids_del'])?>
                <button type="submit" class="btn btn-primary">Confirm Delete</button>
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                <?= $this->Form->end()?>
            </div>
        </div>
    </div>
</div>
<!-- End Edit Confirm Modal -->


<!-- Start Screenshot Open Confirm Modal -->
<div class="modal fade" id="openscr_modal" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="fa fa-picture-o"></i> ScreenShot | Time - <data id="openscr_time"></data></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <img id="openscr_img" style="width:100%" src="">
            </div>
            <div class="modal-footer">
                <div class="col-sm-12 text-left">
                    <div class="row">
                        <div class="col-sm-12 p-b-10 m-b-10 border-bottom">
                            <strong> Project : </strong>  <data id="openscr_project">A website like corvials</data>
                        </div>

                        <div class="col-sm-4">
                            <i class="fa fa-keyboard-o text-themecolor d-inline-block" aria-hidden="true"></i> Key Strokes Count : <data id="openscr_key"></data>
                        </div>
                        <div class="col-sm-4">
                            <i class="fa fa-mouse-pointer text-themecolor d-inline-block" aria-hidden="true"></i> Mouse Moves Count : <data id="openscr_mouse"></data>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- End Modal -->

<script type="application/javascript">
    $(".single-screenshot").find("img").click(function () {
        $sscr  = $(this).parents(".single-screenshot");
        $oscr = $("#openscr_modal");
        $oscr.find("#openscr_img").attr("src",$(this).attr("src"));
        $oscr.find("#openscr_time").text($sscr.find("#time").text());
        $oscr.find("#openscr_project").text($sscr.find("#project").text());
        $oscr.find("#openscr_key").text($sscr.find(".keystroke").width());
        $oscr.find("#openscr_mouse").text($sscr.find(".mousemove").width());
        $oscr.modal("show");
    })
</script>


<?php
$this->start('script');
echo $this->Html->script('moment/moment.min.js');
echo $this->Html->script('moment/moment-timezone.js');
echo $this->Html->script('daterangepicker/daterangepicker.min.js');
?>
<script type="application/javascript">
    var ajaxurl = $("meta[name='ajax']").attr("url");
    var slots = [];
    $(function() {
        var start,end;
        $daterange = $('#daterangepickerinput').val();
        if($daterange)
        {
            $datearr = $daterange.split("-");
            start = new moment($datearr[0]);
            end = new moment($datearr[1]);
        }
        else
        {
            start = moment().startOf('month');
            end = moment().endOf('month');
        }
        function cb(start, end) {
            $('#daterangepickerinput').val(start.format('YYYY/M/D') + ' - ' + end.format('YYYY/M/D'));
        }
        $('#daterangepickerinput').daterangepicker({
            autoUpdateInput: false,
            startDate: start,
            endDate: end,
            ranges: {
                'Today': [moment(), moment()],
                'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
                'Last 7 Days': [moment().subtract(6, 'days'), moment()],
                'Last 30 Days': [moment().subtract(29, 'days'), moment()],
                'This Month': [moment().startOf('month'), moment().endOf('month')],
                'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
            }
        }, cb);
        cb(start, end);
        var max = 2100;

        $('#amt_date_input').daterangepicker({
            autoUpdateInput: false,
            singleDatePicker: true,
            showDropdowns: true,
            minYear: 2010,
            maxYear: max
        }, onlydate);

        function onlydate(start,end)
        {
            var d = start.format('YYYY/M/D');
            $('#amt_date_input').val(d);
            var mid = $('#amt_mid_input').val();
            if(mid!=undefined && mid!='' && d!=undefined && d!='')
                getavailabletimes(d,mid);
        }
        $('#amt_mid_input').change(function(){
            $('#amt_date_input').val('');
            $("#_timefrom").html('');
            $("#_timeto").html('');
        });

        $("#_timefrom").change(function () {
            var slot = $(this).val();
            console.log(slot);
            $e = $("#_timeto");
            appendtooptions ($e, slots, slot);
        });
    });

    function getavailabletimes(date,mid)
    {
        $.ajax({
            type: "GET",
            url: ajaxurl+"?op=getavailabletimesbydate&date="+date+"&mid="+mid,
            dataType: "json",
            success: function (resp) {
                if(resp.status=='success') {
                    $e = $("#_timefrom");
                    slots = resp.result;
                    appendfromoptions($e,slots);
                }
            }
        })
    }


    function appendfromoptions($e,setslot) {
        var s = '', h = '', m = '', a = '',o='';
        o += '<option value="" selected>FROM TIME</option>';
        for (i = 0; i < 24; i++) {
            h = ("0" + i).slice(-2);
            if (i < 12)
                a = 'am';
            else
                a = 'pm';
            if(i==0){h = 12;}else{h = ("0" + i).slice(-2);}

            for (j = 0; j < 60; j = j + 10) {
                m = ("0" + j).slice(-2);
                s = String(h) + ':' + String(m) + a;
                if(setslot.indexOf(s)!=-1)
                    o += '<option value="'+s+'" style="color:red" disabled>'+s+'</option>';
                else
                    o += '<option value="'+s+'" >'+s+'</option>';
            }
        }
        $e.html(o);
    }
    function appendtooptions($e,setslot,after) {
        var s = '', h = '', m = '', a = '',o='';
        o += '<option value="" selected>TO TIME</option>';
        var start =0;
        for (i = 0; i < 24; i++) {
            h = ("0" + i).slice(-2);
            if (i < 12)
                a = 'am';
            else
                a = 'pm';

            if(i==0){h = 12;}else{h = ("0" + i).slice(-2);}

            for (j = 0; j < 60; j = j + 10) {
                m = ("0" + j).slice(-2);
                s = String(h) + ':' + String(m) + a;
                if(after==s){start = 1;}
                if(start>1) {
                    if (setslot.indexOf(s) != -1)
                        o += '<option value="' + s + '" style="color:red" disabled>' + s + '</option>';
                    else
                        o += '<option value="' + s + '" >' + s + '</option>';
                }
                if(start>0){start++;}
            }
        }
        $e.html(o);
    }

</script>
<?php
$this->end();
?>
