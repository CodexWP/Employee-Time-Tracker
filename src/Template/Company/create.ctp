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
                <div class="p-b-20">
                    <h3 class="text-themecolor">Create Company</h3><hr>
                </div>
                <div class="col-sm-6 offset-sm-3">
                    <div class="p-20">                        
                        <?= $this->Form->create('companycreate',['enctype' => 'multipart/form-data']) ?>
                        <div class="form-group">
                            <label for="useremail">Company Logo</label>
                            <?= $this->Form->file('company_logo',['class'=>'form-control']);?> 
                        </div>
                          <div class="form-group">
                            <label for="useremail">Company Name</label>

                            <?= $this->Form->text('company_name',['id'=>'useremail','class'=>'form-control','placeholder'=>'Enter company name']);?>                            
                          </div>
                          <div class="form-group">
                            <label for="userpass">About Company</label>
                            <?= $this->Form->textarea('company_about',['class'=>'form-control','placeholder'=>'Write about your company (optional)']);?>
                          </div>
                          <div class="text-center">
                            <?= $this->Form->button('<i class="fa fa-check" aria-hidden="true"></i> '.__('Create'),['class'=>'btn btn-primary btn-lg']) ?>
                           </div>
                        <?= $this->Form->end() ?>

                    </div>
                </div>


            </div>
        </div>
    </div>
</div>
