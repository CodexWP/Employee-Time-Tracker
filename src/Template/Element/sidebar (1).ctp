<?php
?>
<!-- ============================================================== -->
<!-- Left Sidebar - style you can find in sidebar.scss  -->
<!-- ============================================================== -->
<aside class="left-sidebar">
    <!-- Sidebar scroll-->
    <div class="scroll-sidebar">
        <!-- Sidebar navigation-->
        <nav class="sidebar-nav">
            <?php
            if($session['user']['type']=='admin')
            {
            ?>
            <ul id="sidebarnav">
                <li>
                    <a href="<?= $this->Url->build('/dashboard') ?>" class="waves-effect"><i
                                class="fa fa-tachometer m-r-10" aria-hidden="true"></i>Dashboard</a>
                </li>
                <!--<li>
                    <a href="<?= $this->Url->build('/company') ?>" class="waves-effect"><i
                                class="fa fa-briefcase m-r-10" aria-hidden="true"></i>Company</a>
                </li>-->
                <li>
                    <a href="<?= $this->Url->build('/members') ?>" class="waves-effect"><i class="fa fa-user m-r-10"
                                                                                           aria-hidden="true"></i>Members</a>
                </li>
                <li>
                    <a href="<?= $this->Url->build('/clients') ?>" class="waves-effect"><i class="fa fa-user m-r-10"
                                                                                           aria-hidden="true"></i>Clients</a>
                </li>
                <li>
                    <a href="<?= $this->Url->build('/projects') ?>" class="waves-effect"><i class="fa fa-tasks m-r-10"
                                                                                            aria-hidden="true"></i>Projects</a>
                </li>
                <li>
                    <a href="<?= $this->Url->build('/timesheets') ?>" class="waves-effect"><i
                                class="fa fa-calendar m-r-10" aria-hidden="true"></i>Time Sheets</a>
                </li>
                <li>
                    <a href="<?= $this->Url->build('/screenshots') ?>" class="waves-effect"><i
                                class="fa fa-picture-o m-r-10" aria-hidden="true"></i>Screenshots</a>
                </li>
                <li>
                    <a href="<?= $this->Url->build('/settings') ?>" class="waves-effect"><i class="fa fa-gear m-r-10"
                                                                                            aria-hidden="true"></i>Settings</a>
                </li>
                <li>
                    <a href="<?= $this->Url->build('/profile') ?>" class="waves-effect"><i class="fa fa-at m-r-10"
                                                                                           aria-hidden="true"></i>Profile</a>
                </li>
            </ul>
            <?php
            }
            else if($session['user']['type']=='employee' || $session['user']['type']=='client')
            {
            ?>
                <ul id="sidebarnav">
                    <li>
                        <a href="<?= $this->Url->build('/dashboard') ?>" class="waves-effect"><i
                                    class="fa fa-tachometer m-r-10" aria-hidden="true"></i>Dashboard</a>
                    </li>
                    <li>
                        <a href="<?= $this->Url->build('/projects') ?>" class="waves-effect"><i class="fa fa-tasks m-r-10"
                                                                                                aria-hidden="true"></i>Projects</a>
                    </li>
                    <li>
                        <a href="<?= $this->Url->build('/timesheets') ?>" class="waves-effect"><i
                                    class="fa fa-calendar m-r-10" aria-hidden="true"></i>Time Sheets</a>
                    </li>
                    <li>
                        <a href="<?= $this->Url->build('/screenshots') ?>" class="waves-effect"><i
                                    class="fa fa-picture-o m-r-10" aria-hidden="true"></i>Screenshots</a>
                    </li>
                    <li>
                        <a href="<?= $this->Url->build('/profile') ?>" class="waves-effect"><i class="fa fa-at m-r-10"
                                                                                               aria-hidden="true"></i>Profile</a>
                    </li>
                </ul>
            <?php
            }
            ?>

            <div class="text-center m-t-30">
                <a href="<?= $this->Url->build('/logout', true)?>" class="btn btn-danger"><i class="fa fa-sign-out"></i> Logout</a>
            </div>
        </nav>
        <!-- End Sidebar navigation -->
    </div>
    <!-- End Sidebar scroll-->
</aside>
<!-- ============================================================== -->
<!-- End Left Sidebar - style you can find in sidebar.scss  -->
<!-- ============================================================== -->