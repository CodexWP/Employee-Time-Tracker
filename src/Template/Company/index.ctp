<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Company[]|\Cake\Collection\CollectionInterface $company
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
                                <h3 class="text-themecolor col-sm-6"><i class="fa fa-briefcase" aria-hidden="true"></i> My Company</h3>
                                <?php
                                if($company)
                                {
                                    ?>
                                    <div class="col-sm-6 text-right">
                                        <a href="<?=$this->Url->build('/company/edit',true)?>"><button type="button" class="btn btn-warning"><i class="fa fa-edit"></i> Update</button></a>
                                        <button type="button" class="btn btn-danger" data-toggle="modal" data-target="#deletecompany_modal"><i class="fa fa-trash-o"></i> Delete</button>
                                    </div>
                                    <?php
                                }
                                ?>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                  <div class="col-sm-12">
                    <div class="p-20 text-center">
                        <?php
                        if($company)
                        {
                            ?>
                            <img src="<?=$company->company_logo?>" class="img-thumbnail m-t-30" width="150">
                            <h4 class="card-title m-t-10"><?=$company->company_name?></h4>
                            <h6 class="card-subtitle"><?=$company->company_about?></h6>
                            <div class="row text-center justify-content-md-center">
                                <div class="col-4"><a href="<?=$this->Url->build('/members',true)?>" class="link"><i class="icon-people"></i> <font class="font-medium"><?=$membercount?> company members</font></a></div>
                            </div>
                            <?php
                        }
                        else
                        {
                            ?>
                            <p>You do not have any company, create your own company by clicking on the following button.</p>
                            <a href="<?=$this->Url->build(['action'=>'create'])?>"><button class="btn btn-primary"><i class="fa fa-plus" aria-hidden="true"></i> Create your company</button></a>
                            <?php
                        }
                        ?>
                    </div>

                  </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php  if($company){$company_id=$company->company_id;?>
<!-- Start Edit Confirm Modal -->
<div class="modal fade" id="deletecompany_modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
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
                    <h3>Are you sure?</h3> All members, Time sheets and Screenshots will be deleted.
                </div>
            </div>
            <div class="modal-footer p-20">
                <?= $this->Form->create('companydelete_form',['action'=>'delete']) ?>
                <?=$this->Form->hidden('company_id',['value'=>$company_id])?>
                <button type="submit" class="btn btn-primary">Confirm Delete</button>
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                <?= $this->Form->end()?>
            </div>
        </div>
    </div>
</div>
<!-- End Edit Confirm Modal -->
<?php }?>