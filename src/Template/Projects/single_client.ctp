<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Member[]|\Cake\Collection\CollectionInterface $members
 */

$project = $ctpdata['project'];
$tasks = $ctpdata['tasks'];

?>

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
                                    <button class="btn btn-themecolor" id="btn-add-task"><i class="fa fa-plus" aria-hidden="true"></i> Add Task</button>
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
                                    <input type="text" class="form-control m-b-20" name="project_title" placeholder="Enter project title" value=" ">
                                    <h6 class="border-bottom p-b-5"><i class="fa fa-snowflake-o" aria-hidden="true"></i> <data id="texteditor-header">Project Description</data></h6>
                                    <div  id="project_editor">

                                    </div><br>
                                    <small class="font-size-10"><i class="fa fa-clock-o" aria-hidden="true"></i> Updated : <data id="project-update"></data></small>
                                    <br><br>
                                    <div for="button">
                                        <button class="btn btn-sm btn-warning" id="btn-edit-project">Update</button>
                                        <span class="processing-spin text-themecolor" style="display:none;"><i class="fa fa-refresh fa-spin fa-fw"></i> Processing...</span>

                                    </div>
                                    <?=$this->Form->end()?>
                                </div>

                                <div style="display:none;" class="card-block p-15" id="single-add-task">
                                    <?=$this->Form->create('single-add-task-form',['method'=>'post'])?>
                                    <input type="text" class="form-control m-b-20" name="add_task_title" placeholder="Enter task title">
                                    <h6 class="border-bottom p-b-5"><i class="fa fa-snowflake-o" aria-hidden="true"></i> Task Description</h6>
                                    <div  id="newtask_editor">

                                    </div>
                                    <br>
                                    <div for="button">
                                        <button class="btn btn-sm btn-primary" id="single-add-task-btn">Add New</button>
                                        <span class="processing-spin text-themecolor" style="display:none;"><i class="fa fa-refresh fa-spin fa-fw"></i> Processing...</span>
                                    </div>
                                    <?=$this->Form->end()?>
                                </div>



                                <div style="display:none;" class="card-block p-15" id="single-edit-task">
                                    <?=$this->Form->create('single-edit-task-form',['method'=>'post'])?>
                                    <input type="hidden" name="tid" value="">
                                    <input type="text" class="form-control m-b-20" name="edit_task_title" placeholder="Edit task title">
                                    <h6 class="border-bottom p-b-5"><i class="fa fa-snowflake-o" aria-hidden="true"></i> Task Description</h6>
                                    <div  id="edittask_editor">

                                    </div>
                                    <br>
                                    <small class="font-size-10"><i class="fa fa-clock-o" aria-hidden="true"></i> Updated : <data id="task-update"></data></small>
                                    <br><br>
                                    <div for="button">
                                        <button class="btn btn-sm btn-warning" id="btn-single-edit-task">Update</button>
                                        <button class="btn btn-sm btn-danger" id="btn-single-delete-task">Delete</button>
                                        <span class="processing-spin text-themecolor" style="display:none;"><i class="fa fa-refresh fa-spin fa-fw"></i> Processing...</span>
                                    </div>
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

    $("#btn-add-task").click(function(){
        hidedivision();
        showdivision('single-add-task');
    });


    var quill_project = new Quill('#project_editor', {
        theme: 'snow',
        placeholder: 'Enter details...'
    });
    var quill_newtask = new Quill('#newtask_editor', {
        theme: 'snow',
        placeholder: 'Enter details...'
    });
    var quill_edittask = new Quill('#edittask_editor', {
        theme: 'snow',
        placeholder: 'Enter details...'
    });

    /* Start Edit Project Ajax Functions*/
    $ep = $("#edit-project");
    $ep.find("form").submit(function(e){
        e.preventDefault();
        var desc = JSON.stringify(quill_project.getContents());
        var title = $("input[name='project_title']").val();
        if(title==''){alert('Task title field is empty.');return;}
        var pid = $("#single-project-id").val();

        var fd = new FormData();
        fd.append('project_desc', desc);
        fd.append('project_name',title);
        fd.append('project_id', pid);

        $.ajax({
            url: ajaxurl + "?op=editproject",
            data: fd,
            type: 'post',
            processData: false,
            contentType: false,
            dataType:'json',
            headers : {'X-CSRF-Token': $ep.find("input[name='_csrfToken']").val()},
            beforeSend:function(){
                $("#btn-edit-project").attr('disabled','disabled');
                $ep.find(".processing-spin").show();
            },
            success: function ( resp ) {
                showstatus(resp.status,resp.message);
                $("#btn-edit-project").removeAttr('disabled');
                $ep.find(".processing-spin").hide();
            },
            error: function(xhr, status, error){
                var errorMessage = xhr.status + ': ' + xhr.statusText;
                alert('Error - ' + errorMessage);
                $("#btn-edit-project").removeAttr('disabled');
                $ep.find(".processing-spin").hide();
            }
        });
    })
    /* Start End Project Ajax Functions*/

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


    /* Start Add New Task Ajax Functions*/
    $sat = $("#single-add-task");
    $sat.find("form").submit(function(e){
        e.preventDefault();

        var desc = JSON.stringify(quill_newtask.getContents());
        var title = $("input[name='add_task_title']").val();
        if(title==''){alert('Task title field is empty.');return;}
        var pid = $("#single-project-id").val();
        var fd = new FormData();
        fd.append('task_content', desc);
        fd.append('task_title',title);
        fd.append('project_id', pid);

        $.ajax({
            url: ajaxurl + "?op=addprojecttask",
            data: fd,
            type: 'post',
            processData: false,
            contentType: false,
            dataType:'json',
            headers : {'X-CSRF-Token': $sat.find("input[name='_csrfToken']").val()},
            beforeSend:function(){
                $("#single-add-task-btn").attr('disabled','disabled');
                $sat.find(".processing-spin").show();
            },
            success: function ( resp ) {
                showstatus(resp.status,resp.message);
                if(resp.status=='success')
                {
                    $html = '<li data="'+resp.id+'" class="ui-state-default ui-sortable-handle"><i class="fa fa-hand-o-right" aria-hidden="true"></i> '+title+' <i class="fa fa-angle-right pull-right" aria-hidden="true"></i></li>';
                    $("#tasks-sortable").append($html);
                    $sat.find("input[name='add_task_title']").val('');
                    $("#newtask_editor").find(".ql-editor").html("");
                    $("#no-task-status").hide();
                }
                $("#single-add-task-btn").removeAttr('disabled');
                $sat.find(".processing-spin").hide();
            },
            error: function(xhr, status, error){
                var errorMessage = xhr.status + ': ' + xhr.statusText;
                alert('Error - ' + errorMessage);
                $("#single-add-task-btn").removeAttr('disabled');
                $sat.find(".processing-spin").hide();
            }
        });
    })
    /*End add new task functions*/


    /* Start Edit Task Ajax Functions*/
    $set = $("#single-edit-task");
    $set.find("form").submit(function(e){
        e.preventDefault();
        var desc = JSON.stringify(quill_edittask.getContents());
        var title = $("input[name='edit_task_title']").val();
        if(title==''){alert('Task title field is empty.');return;}
        var pid = $("#single-project-id").val();
        var tid = $("input[name='tid']").val();
        var fd = new FormData();
        fd.append('task_content', desc);
        fd.append('task_title',title);
        fd.append('id', tid);
        fd.append('project_id', pid);

        $.ajax({
            url: ajaxurl + "?op=editprojecttask",
            data: fd,
            type: 'post',
            processData: false,
            contentType: false,
            dataType:'json',
            headers : {'X-CSRF-Token': $set.find("input[name='_csrfToken']").val()},
            beforeSend:function(){
                $("#btn-single-edit-task").attr('disabled','disabled');
                $set.find(".processing-spin").show();
            },
            success: function ( resp ) {
                showstatus(resp.status,resp.message);
                if(resp.status=='success')
                {
                    $html = '<i class="fa fa-hand-o-right" aria-hidden="true"></i> '+title+' <i class="fa fa-angle-right pull-right" aria-hidden="true"></i>';
                    $( "#tasks-sortable" ).find("li[data='"+tid+"']").html($html);
                }
                $("#btn-single-edit-task").removeAttr('disabled');
                $set.find(".processing-spin").hide();
            },
            error: function(xhr, status, error){
                var errorMessage = xhr.status + ': ' + xhr.statusText;
                alert('Error - ' + errorMessage);
                $("#btn-single-edit-task").removeAttr('disabled');
                $set.find(".processing-spin").hide();
            }
        });
    })
    /*End edit task functions*/


    /* Start Delete Task Ajax Functions*/
    $("#btn-single-delete-task").click(function(){

        var pid = $("#single-project-id").val();
        var tid = $("input[name='tid']").val();

        $.ajax({
            url: ajaxurl + "?op=deleteprojecttask&id="+tid+"&project_id="+pid,
            type: 'get',
            processData: false,
            contentType: false,
            dataType:'json',
            beforeSend:function(){
                $("#btn-single-delete-task").attr('disabled','disabled');
                $set.find(".processing-spin").show();
            },
            success: function ( resp ) {
                showstatus(resp.status,resp.message);
                if(resp.status=='success')
                {
                    $( "#tasks-sortable" ).find("li[data='"+tid+"']").remove();
                    hidedivision(1);
                }
                $("#btn-single-delete-task").removeAttr('disabled');
                $set.find(".processing-spin").hide();

            },
            error: function(xhr, status, error){
                var errorMessage = xhr.status + ': ' + xhr.statusText;
                alert('Error - ' + errorMessage);
                $("#btn-single-delete-task").removeAttr('disabled');
                $set.find(".processing-spin").hide();
            }
        });
    })
    /*End Delete task functions*/


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
