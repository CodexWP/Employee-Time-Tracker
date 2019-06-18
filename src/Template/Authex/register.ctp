<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\User[]|\Cake\Collection\CollectionInterface $users
 */
$session = $this->request->getSession();
$token = $session->read('Config.invite_token');
if($token){$email=$token->email;$disabled="true";}else{$email='';$disabled="false";}
?>
<div class="row">
    <div class="col-sm-6 offset-sm-3">
        <div class="p-30 m-t-40 rounded shadow-lg bg-white" style="box-shadow: 0px 0px 7px 1px #d3dadc;">
            <div class="text-center">
                <?=$this->Html->image('logo.png', ['alt' => 'CakePHP','style'=>'width:200px;','class'=>'m-b-20 m-t-20']);?><hr>
            </div>
            <?= $this->Form->create('register',['class'=>'m-t-40 m-b-40']) ?>
                <div class="form-group">
                    <label for="fname">First name</label>
                    <?= $this->Form->text('fname',['id'=>'fname','class'=>'form-control','placeholder'=>'Enter first name']);?>
                </div>
                <div class="form-group">
                    <label for="lname">Last name</label>
                    <?= $this->Form->text('lname',['id'=>'lname','class'=>'form-control','placeholder'=>'Enter last name']);?>
                </div>
                  <div class="form-group">
                    <label for="useremail">Email address</label>
                    <?= $this->Form->email('email',['id'=>'useremail','class'=>'form-control','placeholder'=>'Enter email address','value'=>$email,'readonly'=>$disabled]);?>
                  </div>
                <div class="form-group">
                    <label for="userpass">Password</label>
                    <?= $this->Form->password('password',['id'=>'userpass','class'=>'form-control','placeholder'=>'Enter password']);?>
                </div>
              <div class="text-center">
                <?= $this->Form->button(__('Register'),['class'=>'btn btn-primary btn-lg']) ?>
               </div>
            <?= $this->Form->end() ?>

        </div>
    </div>
</div>
