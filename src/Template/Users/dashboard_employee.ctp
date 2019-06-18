<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\User[]|\Cake\Collection\CollectionInterface $users
 */
$timebox = $ctpdata['timebox'];
$timebar = $ctpdata['timebar'];
?>
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.7.3/Chart.min.js"></script>

<div class="row">
    <div class="col-sm-3">
        <div class="card">
            <div class="card-block">
                <h4 class="card-title border-bottom p-b-10">Today Time</h4>
                <div class="text-right">
                    <h2 class="font-light m-b-0"><i class="fa fa-clock-o text-success"></i> <?=intval($timebox['today']/60)?><small>h</small> <?=$timebox['today']%60?><small>m</small></h2>
                </div>

            </div>
        </div>
    </div>

    <div class="col-sm-3">
        <div class="card">
            <div class="card-block">
                <h4 class="card-title border-bottom p-b-10">This Week</h4>
                <div class="text-right">
                    <h2 class="font-light m-b-0"><i class="fa fa-clock-o text-success"></i> <?=intval($timebox['thisweek']/60)?><small>h</small> <?=$timebox['thisweek']%60?><small>m</small></h2>
                </div>

            </div>
        </div>
    </div>

    <div class="col-sm-3">
        <div class="card">
            <div class="card-block">
                <h4 class="card-title border-bottom p-b-10">Last Week</h4>
                <div class="text-right">
                    <h2 class="font-light m-b-0"><i class="fa fa-clock-o text-success"></i> <?=intval($timebox['lastweek']/60)?><small>h</small> <?=$timebox['lastweek']%60?><small>m</small></h2>
                </div>

            </div>
        </div>
    </div>

    <div class="col-sm-3">
        <div class="card">
            <div class="card-block">
                <h4 class="card-title border-bottom p-b-10">This Month</h4>
                <div class="text-right">
                    <h2 class="font-light m-b-0"><i class="fa fa-clock-o text-success"></i> <?=intval($timebox['thismonth']/60)?><small>h</small> <?=$timebox['thismonth']%60?><small>m</small></h2>
                </div>

            </div>
        </div>
    </div>

</div>

<div class="row">
    <div class="col-sm-12">
        <div class="card">
            <div class="card-block">

                <div class="row m-b-20">
                    <div class="col-sm-12">
                        <div class="border-bottom p-b-10">
                            <div class="row">
                                <h3 class="text-themecolor col-sm-6"><i class="fa fa-bar-chart" aria-hidden="true"></i> Daily Hour Reports - <small>Last 7 days</small></h3>
                            </div>
                        </div>
                        <canvas id="timebarchart" height="100"></canvas>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>

<script type="application/javascript">
    var ctx = document.getElementById("timebarchart").getContext('2d');
    var min = <?=json_encode($timebar['min'])?>;
    var day = <?=json_encode($timebar['day'])?>;

    var timebarchart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: day,
            datasets: [{
                label: 'Time Hour',
                data: min,
                backgroundColor: '#82CDFF',
                borderColor: '#1c99ed',
                borderWidth: 1
            }]
        },
        options: { scales: {yAxes: [{ticks: {beginAtZero:true}}]}}
    });
</script>