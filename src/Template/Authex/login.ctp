<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\User[]|\Cake\Collection\CollectionInterface $users
 */
?>
<div class="row">
    <div class="col-sm-6 offset-sm-3">
        <div class="p-30 m-t-40 rounded shadow-lg bg-white" style="box-shadow: 0px 0px 7px 1px #d3dadc;">
            <div class="text-center">
                <?=$this->Html->image('logo.png', ['alt' => 'CakePHP','style'=>'width:200px;','class'=>'m-b-20 m-t-20']);?><hr>
            </div>
            <?= $this->Form->create('login',['class'=>'m-t-40 m-b-40']) ?>
 
              <div class="form-group">
                <label for="useremail">Email address</label>

                <?= $this->Form->text('email',['id'=>'useremail','class'=>'form-control','placeholder'=>'Enter email address']);?>

                <small id="emailHelp" class="form-text text-muted">We'll never share your email with anyone else.</small>
              </div>
              <div class="form-group">
                <label for="userpass">Password</label>
                <?= $this->Form->password('password',['id'=>'userpass','class'=>'form-control','placeholder'=>'Enter password']);?>
              </div>
              <div class="form-check text-center">
                <input type="checkbox" id="rememberme" name="rememberme" value="on">
                <label for="rememberme" >Remember me</label>
              </div>
              <div class="text-center">
                <?= $this->Form->button(__('Login'),['class'=>'btn btn-primary btn-lg']) ?>
               </div>
            <?= $this->Form->end() ?>

        </div>
    </div>
</div>
