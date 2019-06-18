<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Member[]|\Cake\Collection\CollectionInterface $members
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
                                <h3 class="text-themecolor col-sm-6"><i class="fa fa-briefcase m-r-10" aria-hidden="true"></i>All Projects</h3>
                                <div class="col-sm-6 text-right">
                                    <button class="btn btn-themecolor pull-right " data-toggle="modal" data-target="#projectcreatemodal"><i class="fa fa-plus m-r-10" aria-hidden="true"></i> Create project</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <?php
                $status=array(
                    '0'=>'Active',
                    '1'=>'Completed',
                    '2'=>'Pause',
                    '3'=>'Cancelled'
                );
                ?>
                <div class="row">
                    <div class="col-sm-12">
                        <div class="border-bottom p-b-10">
                            <form method="get">
                            <div class="row">
                                <div class="input-group col-sm-5 m-b-5">
                                    <button class="btn btn-default prepend-button border-right-radius-0 input-sm"><i class="fa fa-clock-o"></i> Clients</button>
                                    <?php
                                    echo $this->Form->select('clientid',
                                        $clients,
                                        ['value' => $this->request->getQuery('clientid'),'class'=>'form-control input-sm','empty' => 'CHOOSE ONE','required'=>'true']
                                    );
                                    ?>
                                </div>
                                <div class="col-sm-2">
                                    <button type="submit" class="btn btn-primary input-sm"><i class="fa fa-filter"></i> Apply Filter</button>
                                </div>
                            </div>
                            </form>
                        </div>
                    </div>
                </div>


                <div class="row m-t-20">
                    <div class="col-sm-12 table-responsive">
                        <table class="table table-bordered myprojects">
                            <thead>
                            <tr>
                                <td class="text-center" scope="col">ID</td>
                                <td class="text-center" scope="col">Project name</td>
                                <td class="text-center" scope="col">Members</td>
                                <td class="text-center" scope="col">Tasks</td>
                                <td class="text-center" scope="col">Status</td>
                                <td class="text-center" scope="col">Action</td>
                            </tr>
                            </thead>
                            <tbody>
                            <?php
                            if($projects)
                            {
                                foreach ($projects as $project)
                                {
                                    
                                    echo '<tr>
                                            <td class="projectid text-center">'.$project->project_id.'</td>
                                            <td class="projectname">'.$project->project_name.'</td>
                                            <td class="projectmembers text-center text-themecolor"><span class="btn-project-members" pid="'.$project->project_id.'" pname="'.$project->project_name.'"><i class="fa fa-user-plus cursor-pointer" aria-hidden="true"></i></span></td>
                                            <td class="text-center">'.$project->task_count.'</td>
                                            <td class="text-center">'.$status[$project->status].'</td>
                                            <td  class="text-center"><a target="_blank" href="'.$this->Url->build("/projects/single/").$project->project_id.'"><i title="Edit" role="button" class="fa fa-edit text-themecolor" aria-hidden="true"></i></a> <i title="Delete" role="button" class="deleteproject fa fa-trash text-danger m-l-10" aria-hidden="true"></i></td>
                                          </tr>';
                                }
                            }
                            else
                            {
                                echo '<tr>
                                        <td class="text-center" colspan="4">You don\'t have any projects.</td>
                                     </tr>';
                            }
                            ?>
                            </tbody>
                        </table>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>


<!-- Start Update project members -->
<div class="modal fade" id="project-members-modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header p-20 topbar">
                <h5 class="modal-title text-white" id="exampleModalLabel"><i class="fa fa-users" aria-hidden="true"></i> Project Members</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body p-20">
                <div class="row">

                    <div class="col-sm-6">
                        <div class="card">
                            <div class="card-block table-responsive">
                                <div style="display:none;" class="p-15 text-center" id="loading">
                                    <i class="fa fa-circle-o-notch fa-spin fa-3x fa-fw"></i>
                                    <span class="sr-only">Loading...</span>
                                    <h2>LOADING...</h2>
                                </div>

                                <table id="members-table" class="table table-bordered">

                                </table>
                            </div>
                        </div>
                    </div>

                    <div class="col-sm-6">
                        <div class="card">
                            <div class="card-block">
                                <div class="form-group">
                                    <label for="projectid">Project Name</label>
                                    <input name="project_id" class="form-control" type="text" pid="" value="" readonly>
                                </div>
                                <div class="form-group">
                                    <label for="projectid">Add Member</label>
                                    <select class="form-control" name="members-list">
                                        <option value="" selected>Choose One</option>
                                        <?php
                                        foreach ($members as $member)
                                        {
                                            echo '<option value="'.$member->member_id.'">'.$member->u['fname'].' '.$member->u['lname'].'</option>';
                                        }
                                        ?>
                                    </select>
                                </div>
                                <div class="form-group text-center">
                                    <button id="btn-add-project-member" type="button" class="btn btn-primary">Add Member</button>
                                    <span class="processing-spin text-themecolor" style="display:none;"><i class="fa fa-refresh fa-spin fa-fw"></i> Processing...</span>

                                </div>
                            </div>
                        </div>
                    </div>


                </div>
            </div>

        </div>
    </div>
</div>
<!-- End project members Modal -->



<!-- Start Deletion Confirm Modal -->
<div class="modal fade" id="projectdeletemodal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header topbar">
                <h5 class="modal-title text-white" id="exampleModalLabel"><i class="fa fa-exclamation-triangle" aria-hidden="true"></i> Warning</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                Are you sure to delete this project?
            </div>
            <div class="modal-footer">
                <?= $this->Form->create('deleteproject',['url'=>[ 'action'=>'delete'],'enctype' => 'multipart/form-data']) ?>
                <?= $this->Form->hidden('project_id',['required'=>'true']);?>
                <button type="submit" class="btn btn-primary">Yes</button>
                <?= $this->Form->end() ?>
                <button type="button" class="btn btn-secondary" data-dismiss="modal">No</button>
            </div>
        </div>
    </div>
</div>
<!-- End Deletion Confirm Modal -->

<!-- Start create Confirm Modal -->
<div class="modal fade" id="projectcreatemodal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header p-20 topbar">
                <h5 class="modal-title text-white" id="exampleModalLabel"><i class="fa fa-plus" aria-hidden="true"></i> Create a Project</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <?= $this->Form->create('createproject',['url'=>[ 'action'=>'create'],'enctype' => 'multipart/form-data']) ?>
            <div class="modal-body p-20">
                <div class="form-group">
                    <label for="projectname">Project name</label>
                    <?= $this->Form->text('project_name',['id'=>'projectname','class'=>'form-control','placeholder'=>'Enter project name','required'=>'true']);?>
                </div>
            </div>
            <div class="modal-footer p-20">
                <button type="submit" class="btn btn-primary">Create</button>
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
            </div>
            <?= $this->Form->end() ?>
        </div>
    </div>
</div>
<!-- End Edit Confirm Modal -->


<?php $this->start('script');?>
<script type="application/javascript">
    $("document").ready(function(){
        $btntrashicon = $("table.myprojects").find(".deleteproject");
        $btntrashicon.click(function(){
            var pid = $(this).parents("td").parents("tr").find("td.projectid").text();
            $('#projectdeletemodal').find("input[name='project_id']").val(pid);
            $('#projectdeletemodal').modal('show');
        });

        $btnediticon = $("table.myprojects").find(".editproject");
        $btnediticon.click(function(){
            var pid = $(this).parents("td").parents("tr").find("td.projectid").text();
            var pname = $(this).parents("td").parents("tr").find("td.projectname").text();
            var pdesc = $(this).parents("td").parents("tr").find("td.projectdesc").find("data").text();

            $('#projecteditmodal').find("input[name='project_id']").val(pid);
            $('#projecteditmodal').find("input[name='project_name']").val(pname);
            $('#projecteditmodal').find("textarea[name='project_desc']").val(pdesc);
            $('#projecteditmodal').modal('show');
        });

        var ajaxurl = $("meta[name='ajax']").attr("url");

        $("#members-table").on("click",'.btn-project-member-delete', function(){
            var mid = $(this).attr('mid');
            var pid = $("input[name='project_id']").attr('pid');
            $tr = $(this).parents('tr');
            $.ajax({
                url: ajaxurl + "?op=deleteprojectmember&project_id="+pid+"&member_id="+mid,
                type: 'get',
                processData: false,
                contentType: false,
                dataType:'json',
                beforeSend:function(){
                    $(".processing-spin").show();
                },
                success:function(resp){

                    if(resp.status=='success')
                    {
                        $tr.remove();
                    }
                    else
                    {
                        alert(resp.message);
                    }

                    $(".processing-spin").hide();
                },
                error: function(xhr, status, error){
                    var errorMessage = xhr.status + ': ' + xhr.statusText;
                    alert('Error - ' + errorMessage);
                    $(".processing-spin").hide();
                }
            })
        });

        $("#btn-add-project-member").click(function(){
            var pid = $("input[name='project_id']").attr('pid');
            var mid = $("select[name='members-list']").val();
            var mname = $("select[name='members-list'] option:selected").text();
            if(mid==''){alert('Please select a member first.');return;}
            $.ajax({
                url: ajaxurl + "?op=addprojectmember&project_id="+pid+"&member_id="+mid,
                type: 'get',
                processData: false,
                contentType: false,
                dataType:'json',
                beforeSend:function(){
                    $(".processing-spin").show();
                },
                success:function(resp){

                    if(resp.status=='success')
                    {
                        $html =  '<tr>' +
                                '<td><i class="fa fa-user" aria-hidden="true"></i> ' + mname + '</td><td><h3 class="text-danger cursor-pointer "><i mid="' + mid + '" class="fa fa-trash-o btn-project-member-delete" aria-hidden="true"></i></h3></td>' +
                                '</tr>';
                        $("#members-table").append($html);
                    }
                    else
                    {
                        alert(resp.message);
                    }

                    $(".processing-spin").hide();
                },
                error: function(xhr, status, error){
                    var errorMessage = xhr.status + ': ' + xhr.statusText;
                    alert('Error - ' + errorMessage);
                    $(".processing-spin").hide();
                }
            })
        });

        $(".btn-project-members").click(function(){
            var pid = $(this).attr('pid');
            $("input[name='project_id']").attr('pid',pid);
            $("input[name='project_id']").val($(this).attr('pname'));
            $("#project-members-modal").modal('show');

            $.ajax({
                url: ajaxurl + "?op=getprojectmembers&project_id="+pid,
                type: 'get',
                processData: false,
                contentType: false,
                dataType:'json',
                beforeSend:function(){
                    $("#loading").show();
                    $("#members-table").hide();
                    $("#members-table").html('');
                },
                success:function(resp){
                    if(resp.status=='success') {
                        var members = resp.data;
                        $html = '';
                        for (i = 0; i < members.length; i++) {
                            $html = $html +
                                '<tr>' +
                                '<td><i class="fa fa-user" aria-hidden="true"></i> ' + members[i].name + '</td><td><h3 class="text-danger cursor-pointer"><i mid="' + members[i].mid + '" class="fa fa-trash-o btn-project-member-delete" aria-hidden="true"></i></h3></td>' +
                                '</tr>';
                            $("#members-table").html($html);
                        }
                        if ($html == '') {
                            $html = '<tr><td>No members are found.</td></tr>';
                            $("#members-table").html($html);
                        }
                    }
                    else{
                        alert('Loading failed. contact with support.');
                    }

                    $("#loading").hide();
                    $("#members-table").show();
                },
                error: function(xhr, status, error){
                    var errorMessage = xhr.status + ': ' + xhr.statusText;
                    alert('Error - ' + errorMessage);
                    $("#loading").hide();
                    $("#members-table").show();
                }
            })
        });



    });
</script>
<?php $this->end();?>
