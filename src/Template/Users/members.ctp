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
                                <h3 class="text-themecolor col-sm-6"><i class="fa fa-user" aria-hidden="true"></i> All Members</h3>
                                <div class="col-sm-6 text-right">
                                    <a href="<?=$this->Url->build('/members/add')?>"><button class="btn btn-primary"><i class="fa fa-user-plus" aria-hidden="true"></i> Add Member</button></a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-12">
                            <table class="table table-bordered users">
                                <thead>
                                <tr>
                                    <td scope="col">ID</td>
                                    <td scope="col">Name</td>
                                    <td scope="col">Email</td>
                                    <td scope="col">Status</td>
                                    <td scope="col" class="text-center">Actions</td>
                                </tr>
                                </thead>
                                <tbody>
                                <?php
                                    $sn=0;
                                    foreach ($members as $single) {

                                        $sn++;
                                        $email = $single->email;
                                        if($single->status==-1)
                                        {
                                            $status="Invited";
                                            $email = $single->invite_email;
                                            $edit = '';
                                        }
                                        else if($single->status==0) {
                                            $status = 'Inactive';
                                        }
                                        else if($single->status==1) {
                                            $status = 'Active';
                                        }

                                        echo '<tr>'.
                                            '<td class="userid">'.$single->userid.'<data class="d-none">'.$single->userid.'</data></td>'.
                                            '<td class="username">'.$single->fname.' '.$single->lname.'</td>'.
                                            '<td>'.$email.'</td>'.
                                            '<td class="status">'.$status.'<data class="d-none">'.$single->status.'</data></td>'.
                                            '<td  class="text-center"><span class="deleteuser text-danger cursor-pointer"><i title="Delete" role="button" class=" fa fa-trash " aria-hidden="true"></i> Delete</span></td>'.
                                            '</tr>';
                                    }
                                if($sn==0)
                                    echo '<tr><td colspan="6" class="text-center">There are no company members.</td></tr>';
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
<div class="modal fade" id="user-delete-modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
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
                <?= $this->Form->create('deleteuser',['url'=>[ 'action'=>'delete'],'enctype' => 'multipart/form-data']) ?>
                <?= $this->Form->hidden('userid',['required'=>'true']);?>
                <?= $this->Form->hidden('type',['required'=>'true','value'=>'employee']);?>
                <button type="submit" class="btn btn-primary">Yes</button>
                <?= $this->Form->end() ?>
                <button type="button" class="btn btn-secondary" data-dismiss="modal">No</button>
            </div>
        </div>
    </div>
</div>
<!-- End Deletion Confirm Modal -->

<?php $this->start('script');?>
<script type="application/javascript">
    $("document").ready(function(){
        $del_btn = $("table.users").find(".deleteuser");
        $del_btn.click(function(){
            var mid = $(this).parents("td").parents("tr").find("td.userid").find("data").text();
            $('#user-delete-modal').find("input[name='userid']").val(mid);
            $('#user-delete-modal').modal('show');
        });
    });
</script>
<?php $this->end();?>
