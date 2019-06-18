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
                                <h3 class="text-themecolor col-sm-6"><i class="fa fa-user" aria-hidden="true"></i> Clients</h3>
                                <div class="col-sm-6 text-right">
                                    <!--<a href="<?=$this->Url->build(['action'=>'invite'])?>"><button class="btn btn-success "><i class="fa fa-plus-circle" aria-hidden="true"></i> Invite member</button></a>-->
                                    <a href="<?=$this->Url->build(['action'=>'add'])?>"><button class="btn btn-primary"><i class="fa fa-user-plus" aria-hidden="true"></i> Add Client</button></a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-12">
                            <table class="table table-bordered companymembers">
                                <thead>
                                <tr>
                                    <td scope="col">ID</td>
                                    <td scope="col">Name</td>
                                    <td scope="col">Email</td>
                                    <td scope="col">Status</td>
                                    <td scope="col">Actions</td>
                                </tr>
                                </thead>
                                <tbody>
                                <?php

                                    $sn=0;
                                    foreach ($clients as $single) {

                                        $sn++;

                                        if($single->status==0) {
                                            $status = 'Inactive';
                                        }
                                        else if($single->status==1) {
                                            $status = 'Active';
                                        }

                                        echo '<tr>'.
                                            '<td class="clientid">'.$single->userid.'<data class="d-none">'.$single->userid.'</data></td>'.
                                            '<td class="clientname">'.$single->fname.' '.$single->lname.'</td>'.
                                            '<td>'.$single->email.'</td>'.
                                            '<td class="status">'.$status.'<data class="d-none">'.$single->status.'</data></td>'.
                                            '<td  class="text-center"><span class="deleteclient text-danger cursor-pointer"><i title="Delete" role="button" class=" fa fa-trash " aria-hidden="true"></i> Delete</span></td>'.
                                            '</tr>';
                                    }
                                if($sn==0)
                                    echo '<tr><td colspan="6">There are no clients.</td></tr>';
                                ?>
                                </tbody>
                            </table>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>

<!-- Start Deletion Confirm Modal -->
<div class="modal fade" id="memberdeletemodal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header topbar">
                <h5 class="modal-title text-white" id="exampleModalLabel"><i class="fa fa-exclamation-triangle" aria-hidden="true"></i> Warning</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                Are you sure to delete this member?
            </div>
            <div class="modal-footer">
                <?= $this->Form->create('deleteclient',['url'=>[ 'action'=>'delete'],'enctype' => 'multipart/form-data']) ?>
                <?= $this->Form->hidden('client_id',['required'=>'true']);?>
                <button type="submit" class="btn btn-primary">Yes</button>
                <?= $this->Form->end() ?>
                <button type="button" class="btn btn-secondary" data-dismiss="modal">No</button>
            </div>
        </div>
    </div>
</div>
<!-- End Deletion Confirm Modal -->

<!-- Start Edit Confirm Modal -->
<div class="modal fade" id="membereditmodal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header p-20 topbar">
                <h5 class="modal-title text-white" id="exampleModalLabel"><i class="fa fa-edit" aria-hidden="true"></i> Update member</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <?= $this->Form->create('editmember',['url'=>['action'=>'edit'],'enctype' => 'multipart/form-data']) ?>
            <div class="modal-body p-20">
                <?= $this->Form->hidden('member_id');?>

                <div class="form-group">
                    <label for="membername">Member name</label>
                    <?= $this->Form->text('member_name',['id'=>'membername','class'=>'form-control','readonly'=>'true']);?>
                </div>
                <div class="form-group">
                    <label for="memberemail">Assigned projects</label>
                    <?php  echo $this->Form->select('project_id',
                        $projectlist,
                        ['value' => '','class'=>'form-control','empty' => '(CHOOSE ONE)']
                    );
                    ?>
                </div>
                <div class="form-group">
                    <label for="memberstatus">Status</label>
                    <?php  echo $this->Form->select('status',
                        ["1"=>'Active','0'=>'Inactive'],
                        ['value' => '','class'=>'form-control','empty' => '(CHOOSE ONE)']
                    );
                    ?>
                </div>
            </div>
            <div class="modal-footer p-20">
                <button type="submit" class="btn btn-primary">Update</button>
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
        $btntrashicon = $("table.companymembers").find(".deleteclient");
        $btntrashicon.click(function(){
            var cid = $(this).parents("td").parents("tr").find("td.clientid").find("data").text();
            $('#memberdeletemodal').find("input[name='client_id']").val(cid);
            $('#memberdeletemodal').modal('show');
        });

        $btnediticon = $("table.companymembers").find(".editmember");
        $btnediticon.click(function() {
            var mid = $(this).parents("td").parents("tr").find("td.memberid").find("data").text();
            var mname = $(this).parents("td").parents("tr").find("td.membername").text();
            var proj_index = $(this).parents("td").parents("tr").find("td.project").find("data").text();
            var status_index = $(this).parents("td").parents("tr").find("td.status").find("data").text();
            $('#membereditmodal').find("input[name='member_id']").val(mid);
            $('#membereditmodal').find("input[name='member_name']").val(mname);
            $('#membereditmodal').find("select[name='project_id']").val(proj_index);
            $('#membereditmodal').find("select[name='status']").val(status_index);
            $('#membereditmodal').modal('show');
        });
    });
</script>
<?php $this->end();?>
