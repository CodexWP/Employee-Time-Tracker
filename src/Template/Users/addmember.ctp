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
                                <h3 class="text-themecolor col-sm-6"><i class="fa fa-plus-square" aria-hidden="true"></i> Add new member</h3>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-6 offset-sm-3">
                        <div class="p-30 rounded shadow-lg bg-white">
                            <?= $this->Form->create('addmember',['class'=>'']) ?>
                            <div class="form-group">
                                <label for="fname">First name *</label>
                                <?= $this->Form->text('fname',['id'=>'fname','class'=>'form-control','placeholder'=>'Enter first name']);?>
                            </div>
                            <div class="form-group">
                                <label for="lname">Last name *</label>
                                <?= $this->Form->text('lname',['id'=>'lname','class'=>'form-control','placeholder'=>'Enter last name']);?>
                            </div>
                            <div class="form-group">
                                <label for="useremail">Email address *</label>
                                <?= $this->Form->email('email',['id'=>'useremail','class'=>'form-control','placeholder'=>'Enter email address','required'=>'true']);?>
                            </div>
                            <div class="form-group">
                                <label for="userpass">Password *</label>
                                <?= $this->Form->password('password',['id'=>'userpass','class'=>'form-control','placeholder'=>'Enter password']);?>
                            </div>
                            <div class="text-center">
                                <?= $this->Form->button(__('<i class="fa fa-check"></i> Register member'),['class'=>'btn btn-primary']) ?>
                            </div>
                            <?= $this->Form->end() ?>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
