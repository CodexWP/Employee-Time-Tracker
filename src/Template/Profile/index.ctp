<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Timesheet[]|\Cake\Collection\CollectionInterface $timesheets
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
                                <h3 class="text-themecolor col-sm-6"><i class="fa fa-user m-r-10" aria-hidden="true"></i> My Profile</h3>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-8 offset-sm-2">
                        <?=$this->Form->create('profileform',['url'   => ['action' => 'editprofile'],'method'=>'post','enctype' => 'multipart/form-data']) ?>

                        <div class="form-group row">
                            <div class="col-sm-12 text-center">
                                <?php
                                if($user->thumb)
                                    echo '<img src="'.$user->thumb.'" class="img-thumbnail m-b-20" style="height:150px;">';
                                else
                                    echo $this->Html->image('no-image.png',['class'=>'img-thumbnail m-b-20','style'=>'height:150px;']);
                                ?>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="staticEmail" class="col-sm-4 col-form-label">Profile Photo </label>
                            <div class="col-sm-8">
                                <input name="thumb" type="file" class="form-control">
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="staticEmail" class="col-sm-4 col-form-label">First Name</label>
                            <div class="col-sm-8">
                                <input name="fname" type="text" class="form-control" value="<?=$user->fname?$user->fname:''?>" placeholder="Your first name">
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="staticEmail" class="col-sm-4 col-form-label">Last Name</label>
                            <div class="col-sm-8">
                                <input name="lname" type="text" class="form-control" value="<?=$user->lname?$user->lname:''?>" placeholder="Your last name">
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="staticEmail" class="col-sm-4 col-form-label">Your Email</label>
                            <div class="col-sm-8">
                                <input name="email" type="text" class="form-control" value="<?=$user->email?$user->email:''?>" placeholder="Enter your email">
                            </div>
                        </div>

                        <div class="form-group row m-t-40">
                            <div class="col-sm-12 text-center">
                                <button class="btn btn-primary"><i class="fa fa-check"></i> Update Profile</button>
                            </div>
                        </div>
                        <?= $this->Form->end() ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-sm-12 m-t-10">
        <div class="card">
            <div class="card-block">
                <div class="row m-b-20">
                    <div class="col-sm-12">
                        <div class="border-bottom p-b-10">
                            <div class="row">
                                <h3 class="text-themecolor col-sm-6"><i class="fa fa-key m-r-10" aria-hidden="true"></i> Change Password</h3>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-8 offset-sm-2">
                        <?= $this->Form->create('passwordform',['url'=>['action'=>'changepassword'],'method'=>'post','enctype' => 'multipart/form-data']) ?>

                        <div class="form-group row">
                            <label for="staticEmail" class="col-sm-4 col-form-label">Current Password</label>
                            <div class="col-sm-8">
                                <input name="current_password" type="password" class="form-control" value="" placeholder="Enter current password">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="staticEmail" class="col-sm-4 col-form-label">New Password</label>
                            <div class="col-sm-8">
                                <input name="new_password" type="password" class="form-control" value="" placeholder="Enter new password">
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="staticEmail" class="col-sm-4 col-form-label">Confirm Password</label>
                            <div class="col-sm-8">
                                <input name="confirm_password" type="password" class="form-control" value="" placeholder="Enter confirm password">
                            </div>
                        </div>

                        <div class="form-group row m-t-40">
                            <div class="col-sm-12 text-center">
                                <button class="btn btn-primary"><i class="fa fa-check"></i> Update Password</button>
                            </div>
                        </div>
                        <?= $this->Form->end() ?>
                    </div>
                </div>
            </div>
        </div>
</div>
</div>