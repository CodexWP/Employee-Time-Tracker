<?php

?>
        <!-- ============================================================== -->
        <!-- Topbar header - style you can find in pages.scss -->
        <!-- ============================================================== -->
        <header class="topbar">
            <nav class="navbar top-navbar navbar-toggleable-sm navbar-light ">
                <!-- ============================================================== -->
                <!-- Logo -->
                <!-- ============================================================== -->
                <div class="navbar-header" style="margin:2px 0px;">
                    <a class="navbar-brand" href="<?=$this->Url->build('/',true)?>">
                        <!-- Logo icon -->
                        <b class="mobile_logo">
                            <!-- Dark Logo icon -->
                            <?php
                            if(isset($settings['mobile_logo']))
                                echo '<img src="'.$settings['mobile_logo'].'">';
                            else
                                echo $this->Html->image('mobile_logo.png');
                            ?>
                        </b>
                        <span class="logo">
                            <!--You can put here icon as well // <i class="wi wi-sunset"></i> //-->
                            <!-- Dark Logo icon -->
                            <?php
                            if(isset($settings['system_logo']))
                                echo '<img src="'.$settings['system_logo'].'">';
                            else
                                echo $this->Html->image('logo.png');
                            ?>
                        </span>
                        <!--End Logo icon -->
                     </a>
                </div>
                <!-- ============================================================== -->
                <!-- End Logo -->
                <!-- ============================================================== -->
                <div class="navbar-collapse">
                    <!-- ============================================================== -->
                    <!-- toggle and nav items -->
                    <!-- ============================================================== -->
                    <ul class="navbar-nav mr-auto mt-md-0 ">
                        <!-- This is  -->
                        <li class="nav-item"> <a class="nav-link nav-toggler hidden-md-up text-muted waves-effect waves-dark" href="javascript:void(0)"><i class="ti-menu"></i></a> </li>
                        <li class="nav-item hidden-sm-down">
                            <form class="app-search p-l-20">
                                <input type="text" class="form-control" placeholder="Search for..."> <a class="srh-btn"><i class="ti-search"></i></a>
                            </form>
                        </li>

                    </ul>
                    <!-- ============================================================== -->
                    <!-- User profile and search -->
                    <!-- ============================================================== -->
                    <?php
                        if($session['user']['thumb'])
                            $thumb = $session['user']['thumb'];
                        else
                            $thumb = $this->Url->build('/img/no-image.png', true);
                    ?>
                    <ul class="navbar-nav my-lg-0">

                        <li class="nav-item dropdown m-r-10">
                            <h3 class="text-warning bg-light top-icon-alert" style=""><i class="fa fa-bell-o" aria-hidden="true"></i></h3>
                        </li>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle text-muted waves-effect waves-dark" href="" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">   <img src="<?=$thumb?>" class="profile-pic m-r-5">
                               <?=$session['user']['fname'].' '.$session['user']['lname']?></a>
                        </li>
                    </ul>                    
                </div>
            </nav>
        </header>
        <!-- ============================================================== -->
        <!-- End Topbar header -->
        <!-- ============================================================== -->

<!-- Start create Confirm Modal -->
<div class="modal fade" id="sidebar-add-task-modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <?=$this->Form->create('sidebar-add-task-form',['method'=>'post', 'id'=>'sidebar-add-task-form'])?>
            <div class="modal-header p-20 topbar">
                <h5 class="modal-title text-white" id="exampleModalLabel"><i class="fa fa-plus" aria-hidden="true"></i> Add New Task</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body p-20">
                <div class="card-block p-15">
                    <select name="sidebar-project-list" class="form-control" required>
                        <option value="" selected>Choose Project</option>
                    </select><br><br>
                    <input type="text" class="form-control m-b-20" name="sidebar-add-task-title" placeholder="Enter task title" required>
                    <br>
                    <div  id="sidebar_newtask_editor">
                    </div>
                    <br>
                    <span class="processing-spin text-themecolor" style="display:none;"><i class="fa fa-refresh fa-spin fa-fw"></i> Processing...</span>
                    <span class="processing-complete text-success" style="display:none;"><i class="fa fa-check"></i> Task has ben added.</span>

                </div>
            </div>
            <div class="modal-footer p-20">
                <button type="submit" class="btn btn-primary"> Add Task</button>
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
            </div>
            <?= $this->Form->end() ?>
        </div>
    </div>
</div>
<!-- End Edit Confirm Modal -->

