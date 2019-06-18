<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Company $company
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
                                <h3 class="text-themecolor col-sm-6"><i class="fa fa-plus-square" aria-hidden="true"></i> Invite a member</h3>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-6 offset-sm-3">
                        <div class="p-20">
                            <?= $this->Form->create('invitemember',['enctype' => 'multipart/form-data']) ?>
                            <div class="form-group">
                                <label for="useremail">Member email</label>
                                <?= $this->Form->email('invite_email',['id'=>'useremail','class'=>'form-control','placeholder'=>'Enter member email','required'=>'true']);?>
                            </div>
                            <div class="text-center">
                                <?= $this->Form->button('<i class="fa fa-check" aria-hidden="true"></i> '.__('Send invitation'),['class'=>'btn btn-primary']) ?>
                            </div>
                            <?= $this->Form->end() ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
