<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Timesheet[]|\Cake\Collection\CollectionInterface $timesheets
 */
$this->start('css');
    echo $this->Html->css('daterangepicker/daterangepicker.css');
$this->end();

$period = $ctpdata['period'];
$dailymincount = $ctpdata['dailymincount'];
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
                                    <h3 class="text-themecolor col-sm-6"><i class="fa fa-calendar m-r-10" aria-hidden="true"></i> Time Sheets</h3>
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
                                        <input autocomplete="off" name="daterange" type="text" class="form-control text-center m-r-5 input-sm" id="daterangepickerinput" value="<?=$this->request->getQuery('daterange')?>">
                                    </div>
                                    <div class="col-sm-2">
                                        <button type="submit" class="btn btn-primary input-sm"><i class="fa fa-filter"></i> Apply filter</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
                <div class="row m-t-30">
                    <div class="col-sm-12">
                        <div class="text-center">
                            <?php
                            $sum =0;
                            foreach ($period as $day){
                                if(isset($dailymincount[$day->format('Y/m/d')])) {
                                    $sum +=intval($dailymincount[$day->format('Y/m/d')]);
                                }
                            }
                            $h = intval($sum/60);
                            $m = intval($sum%60);
                            ?>
                            <h1 class="text-primary"><?=$h?>h <?=$m?>m</h1>
                            <small>Total Time Worked</small>
                        </div>
                        <style>
                            .table td{min-width:150px;}
                        </style>
                        <table class="table table-bordered m-t-20 table-responsive">
                            <thead>
                                <tr>
                                <?php
                                foreach ($period as $day){
                                    echo '<td class="text-center"><strong>'.$day->format('d M, D').'</strong></td>';
                                }
                                ?>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                <?php
                                foreach ($period as $day){
                                    if(isset($dailymincount[$day->format('Y/m/d')]))
                                    {

                                        $h = intval(intval($dailymincount[$day->format('Y/m/d')])/60);
                                        $m = intval($dailymincount[$day->format('Y/m/d')])%60;
                                        $spend = $h.'h '.$m.'m';
                                        $w = (intval($dailymincount[$day->format('Y/m/d')])/(12*60))*100;
                                    }
                                    else
                                    {
                                        $spend = '0h 0m';
                                        $w=0;
                                    }
                                    echo '<td class="text-center">    
                                            <div class="progress">
                                              <div class="progress-bar bg-success" role="progressbar" style="width: '.$w.'%" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"></div>
                                            </div>'.$spend.
                                        '</td>';
                                }
                                ?>
                                </tr>
                            </tbody>
                        </table>
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
    $(function() {
        var start,end;
        var tz = "America/Los_Angeles";
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
                'Today': [moment().tz(tz), moment().tz(tz)],
                'Yesterday': [moment().tz(tz).subtract(1, 'days'), moment().tz(tz).subtract(1, 'days')],
                'Last 7 Days': [moment().tz(tz).subtract(6, 'days'), moment().tz(tz)],
                'Last 30 Days': [moment().tz(tz).subtract(29, 'days'), moment().tz(tz)],
                'This Month': [moment().tz(tz).startOf('month'), moment().endOf('month')],
                'Last Month': [moment().tz(tz).subtract(1, 'month').startOf('month'), moment().tz(tz).subtract(1, 'month').endOf('month')]
            }
        }, cb);

        cb(start, end);

    });

</script>
<?php
$this->end();
?>
