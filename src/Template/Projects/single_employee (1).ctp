<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Member[]|\Cake\Collection\CollectionInterface $members
 */

$project = $ctpdata['project'];
$tasks = $ctpdata['tasks'];

?>
<?= $this->Html->css('jquery-ui.css') ?>
<?= $this->Html->script('jquery/jquery-ui.js') ?>
<?= $this->Html->css('quill.snow.css') ?>

<div class="row">
    <div class="col-sm-12">
        <div class="card">
            <div class="card-block">

                <div class="row m-b-20">
                    <div class="col-sm-12">
                        <div class="border-bottom p-b-10">
                            <div class="row">
                                <h3 id="project-header" class="text-themecolor col-sm-6" style="cursor: pointer"><i class="fa fa-sliders" aria-hidden="true"></i> TASKS : <?=$project->project_name?></h3>
                                <input type="hidden" id="single-project-id" value="<?=$project->project_id?>">
                                <div class="col-sm-6 text-right">
                                    <a href="<?=$this->Url->build('/projects')?>">
                                        <button class="btn btn-default"><i class="fa fa-chevron-left" aria-hidden="true"></i> Back Projects</button>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-6">
                        <div class="border">
                            <div class="card border m-0">
                                <div class="card-block p-15">
                                    <ul id="tasks-sortable">
                                        <?php
                                        $exists = false;
                                        foreach ($tasks as $task){
                                            $exists = true;
                                            echo '<li data="'.$task->id.'" class="ui-state-default"><i class="fa fa-hand-o-right" aria-hidden="true"></i> '.$task->task_title.' <i class="fa fa-angle-right pull-right" aria-hidden="true"></i></li>';
                                        }
                                        if(!$exists)
                                        {
                                            echo '<span id="no-task-status">No tasks are available.</span>';
                                        }
                                        ?>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-sm-6">
                        <div class="border">
                            <div class="card border m-0">


                                <div style="display:none;" class="card-block p-15 text-center" id="loading">
                                    <i class="fa fa-circle-o-notch fa-spin fa-3x fa-fw"></i>
                                    <span class="sr-only">Loading...</span>
                                    <h2>LOADING...</h2>
                                </div>

                                <div style="display:none;" class="card-block p-15 text-center" id="loading-failed">
                                    <i class="fa fa-hand-paper-o fa-3x" aria-hidden="true"></i>
                                    <span class="sr-only">Loading...</span>
                                    <h2 class="text-danger">LOADING FAILED!</h2>
                                </div>

                                <div style="display:none;" class="card-block p-15" id="edit-project">
                                    <?=$this->Form->create('edit-project-form',['method'=>'post'])?>
                                    <input type="text" class="form-control m-b-20" name="project_title" placeholder="Enter project title" value=" " readonly>
                                    <h6 class="border-bottom p-b-5"><i class="fa fa-snowflake-o" aria-hidden="true"></i> <data id="texteditor-header">Project Description</data></h6>
                                    <div  id="project_editor">

                                    </div><br>
                                    <small class="font-size-10"><i class="fa fa-clock-o" aria-hidden="true"></i> Updated : <data id="project-update"></data></small>

                                    <?=$this->Form->end()?>
                                </div>

                                <div style="display:none;" class="card-block p-15" id="single-edit-task">
                                    <?=$this->Form->create('single-edit-task-form',['method'=>'post'])?>
                                    <input type="hidden" name="tid" value="">
                                    <input type="text" class="form-control m-b-20" name="edit_task_title" placeholder="Edit task title" readonly>
                                    <h6 class="border-bottom p-b-5"><i class="fa fa-snowflake-o" aria-hidden="true"></i> Task Description</h6>
                                    <div  id="edittask_editor">

                                    </div>
                                    <br>
                                    <small class="font-size-10"><i class="fa fa-clock-o" aria-hidden="true"></i> Updated : <data id="task-update"></data></small>

                                    <?=$this->Form->end()?>
                                </div>


                            </div>
                        </div>
                    </div>

                </div>

            </div>
        </div>
    </div>
</div>


<?= $this->Html->script('quill.js') ?>


<script type="application/javascript">
    $( function() {
        $( "#tasks-sortable" ).sortable({
            placeholder: "ui-state-highlight",
            start: function(e,ui) {
                ui.item.addClass('draggingli');
            },
            stop: function(e,ui) {
                ui.item.removeClass('draggingli');
            }
        });
        $( "#tasks-sortable" ).disableSelection();

        $( "#tasks-sortable" ).find("li").click(function(){
            $( "#tasks-sortable" ).find("li").removeClass("text-primary");
            $(this).addClass("text-primary");
        })

        $("#project-header").click();
    } );

    var ajaxurl = $("meta[name='ajax']").attr("url");

    $("#project-header").click(function(){

        var pid = $("#single-project-id").val();
        $.ajax({
            url: ajaxurl + "?op=getprojectdetails&pid="+pid,
            type: 'get',
            processData: false,
            contentType: false,
            dataType:'json',
            beforeSend:function(){
                hidedivision();
            },
            success:function(resp){
                if(resp.status=='success') {
                    var project = resp.project;
                    if(project.project_desc!='')
                        quill_project.setContents(JSON.parse(project.project_desc));
                    $("input[name='project_title']").val(project.project_name);
                    $("#project-update").text(project.modified);
                    showdivision('edit-project');
                    quill_project.enable(false);
                }
                else
                {
                    showdivision('loading-failed');
                }
            },
            error:function(){
                showdivision('loading-failed');
            }
        })
    });

    var quill_project = new Quill('#project_editor', {
        theme: 'snow',
        placeholder: 'No details here...',
        "modules": {
            "toolbar": false
        }
    });

    var quill_edittask = new Quill('#edittask_editor', {
        theme: 'snow',
        placeholder: 'No details here...',
        "modules": {
            "toolbar": false
        }
    });



    /* Start show task details Ajax Functions*/
    $( "#tasks-sortable" ).on("click", "li", function() {
        var tid = $(this).attr('data');
        $.ajax({
            url: ajaxurl + "?op=gettaskdetails&tid="+tid,
            type: 'get',
            processData: false,
            contentType: false,
            dataType:'json',
            beforeSend:function(){
                hidedivision();
            },
            success:function(resp){
                if(resp.status=='success') {
                    var task = resp.task;
                    quill_edittask.setContents(JSON.parse(task.task_content));
                    $("input[name='edit_task_title']").val(task.task_title);
                    $("input[name='tid']").val(tid);
                    $("#task-update").text(task.modified);
                    showdivision('single-edit-task');
                    quill_edittask.enable(false);
                }
                else
                {
                    showdivision('loading-failed');
                }
            },
            error:function(){
                showdivision('loading-failed');
            }
        })
    });
    /*End show task functions*/


    function hidedivision(c=0)
    {

        $("#single-edit-task").hide();
        $("#single-add-task").hide();
        $("#edit-project").hide();
        $("#loading-failed").hide();
        if(c==0)
            $("#loading").show();
    }
    function showdivision(id){
        $("#loading").hide();
        $("#"+id).fadeIn();
    }

</script>
