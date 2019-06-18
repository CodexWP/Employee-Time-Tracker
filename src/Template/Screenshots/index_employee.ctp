<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Timesheet[]|\Cake\Collection\CollectionInterface $timesheets
 */
$this->start('css');
    echo $this->Html->css('daterangepicker/daterangepicker.css');
$this->end();

$screenshots = $ctpdata['screenshots'];

?>
<div class="row">
    <div class="col-sm-12">
        <div class="card m-0">
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
                                        <button class="btn btn-default prepend-button border-right-radius-0 input-sm"><i class="fa fa-clock-o"></i> Range</button>
                                        <input autocomplete="off" name="daterange" type="text" class="input-sm form-control text-center m-r-5" id="daterangepickerinput" value="<?=$this->request->getQuery('daterange')?>">
                                    </div>

                                    <div class="col-sm-2">
                                        <button type="submit" class="btn btn-primary input-sm"><i class="fa fa-filter"></i> Apply Filter</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
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
                                <div class="border rounded text-center">
                                    <img style="width:100%;height:auto;" src="'.$scr_url.'">
                                    <small>'.$scr_time.'</small><br>
                                    <small style="background: #f2f7f8;padding: 5px;border-radius: 5px;border:1px solid gainsboro;">'.$pro_name.'</small>
                                    <br>
                                    <i class="fa fa-keyboard-o text-themecolor d-inline-block" aria-hidden="true"></i>
                                    <div class="progress d-inline-block" style="width:30%;height: 5px">
                                        <div class="progress-bar bg-success" role="progressbar" style="width: '.$ks.'%" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"></div>
                                    </div>&nbsp;
                                    <i class="fa fa-mouse-pointer text-themecolor d-inline-block" aria-hidden="true"></i>
                                    <div class="progress d-inline-block" style="width:30%;height: 5px">
                                        <div class="progress-bar bg-success" role="progressbar" style="width: '.$mm.'%" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"></div>
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
                                <div class="border rounded text-center">
                                    <img style="width:100%;height:auto;" src="'.$scr_url.'">
                                    <small>'.$scr_time.'</small><br>
                                    <small style="background: #f2f7f8;padding: 5px;border-radius: 5px;border:1px solid gainsboro;">'.$pro_name.'</small>
                                    <br>
                                    <i class="fa fa-keyboard-o text-themecolor d-inline-block" aria-hidden="true"></i>
                                    <div class="progress d-inline-block" style="width:30%;height: 5px">
                                        <div class="progress-bar bg-success" role="progressbar" style="width: '.$ks.'%" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"></div>
                                    </div>&nbsp;
                                    <i class="fa fa-mouse-pointer text-themecolor d-inline-block" aria-hidden="true"></i>
                                    <div class="progress d-inline-block" style="width:30%;height: 5px">
                                        <div class="progress-bar bg-success" role="progressbar" style="width: '.$mm.'%" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"></div>
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


                <div class="row">
                    <div class="col-sm-12 m-t-40">

                        <?=$this->element('pagination')?>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>


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

    });
</script>
<?php
$this->end();
?>
