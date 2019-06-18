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
                                <h3 class="text-themecolor col-sm-6"><i class="fa fa-briefcase" aria-hidden="true"></i> Client's Company</h3>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                  <div class="col-sm-12">
                    <div class="p-20 text-center">
                        <?php
                        if($company) {
                            ?>
                            <img src="<?= $company->company_logo ?>" class="img-thumbnail m-t-30" width="150">
                            <h4 class="card-title m-t-10"><?= $company->company_name ?></h4>
                            <h6 class="card-subtitle"><?= $company->company_about ?></h6>
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