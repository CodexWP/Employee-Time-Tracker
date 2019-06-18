<?php
/**
 * CakePHP(tm) : Rapid Development Framework (https://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 * @link          https://cakephp.org CakePHP(tm) Project
 * @since         0.10.0
 * @license       https://opensource.org/licenses/mit-license.php MIT License
 */
if(isset($settings['system_title']))
    $cakeDescription = $settings['system_title'];
else
    $cakeDescription = 'Time Tracker';
?>
<!DOCTYPE html>
<html>
<head>
    <?= $this->Html->charset() ?>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>
        <?= $this->fetch('title') ?> -
        <?= $cakeDescription ?>
    </title>
    <meta name="ajax" url="<?=$this->Url->build('/ajax',true)?>">
    <?= $this->Html->meta('icon') ?>
    <?= $this->Html->css('bootstrap/bootstrap.min.css') ?>
    <?= $this->Html->css('style.css') ?>
    <?= $this->Html->css('custom-style.css') ?>
    <?= $this->Html->css('colors/blue.css') ?>
    <?= $this->Html->script('jquery/jquery.min.js') ?>
    <?= $this->Html->script('bootstrap/bootstrap-notify.min.js') ?>

    <!--For Quill Editor task creation-->
    <?= $this->Html->css('jquery-ui.css') ?>
    <?= $this->Html->css('quill.snow.css') ?>
    <?= $this->Html->script('jquery/jquery-ui.js') ?>
    <?= $this->Html->script('quill.js') ?>
    <!--For Quill Editor task creation-->


    <?= $this->fetch('meta') ?>
    <?= $this->fetch('css') ?>
    <?= $this->fetch('script') ?>
</head>
<body class="fix-header card-no-border">
    <div class="preloader">
        <svg class="circular" viewBox="25 25 50 50">
            <circle class="path" cx="50" cy="50" r="20" fill="none" stroke-width="2" stroke-miterlimit="10" /> </svg>
    </div>
    <!-- ============================================================== -->
    <!-- Main wrapper - style you can find in pages.scss -->
    <!-- ============================================================== -->
    <div id="main-wrapper">


        <?=$this->element('header');?>

        <?=$this->element('sidebar');?>

        <?= $this->Flash->render() ?>

        <!-- ============================================================== -->
        <!-- Page wrapper  -->
        <!-- ============================================================== -->
        <div class="page-wrapper">
            <!-- ============================================================== -->
            <!-- Container fluid  -->
            <!-- ============================================================== -->
            <div class="container-fluid">

                <?php /*$this->element('breadcrumb');*/ ?>

                <!-- ============================================================== -->
                <!-- Start Page Content -->
                <!-- ============================================================== -->


                <?= $this->fetch('content') ?>
                

                <!-- ============================================================== -->
                <!-- End PAge Content -->
                <!-- ============================================================== -->
            </div>
            <!-- ============================================================== -->
            <!-- End Container fluid  -->
            <!-- ============================================================== -->

            <?=$this->element('footer');?>

        </div>
        <!-- ============================================================== -->
        <!-- End Page wrapper  -->
        <!-- ============================================================== -->
    </div>


    <?= $this->Html->script('bootstrap/tether.min.js') ?>
    <?= $this->Html->script('bootstrap/bootstrap.min.js') ?>
    <?= $this->Html->script('jquery.slimscroll.js') ?>
    <?= $this->Html->script('waves.js') ?>
    <?= $this->Html->script('sidebarmenu.js') ?>
    <?= $this->Html->script('sticky-kit-master/dist/sticky-kit.min.js') ?>
    <?= $this->Html->script('custom.js') ?>

</body>
</html>
