<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Member[]|\Cake\Collection\CollectionInterface $members
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
                                <h3 class="text-themecolor col-sm-6"><i class="fa fa-briefcase m-r-10" aria-hidden="true"></i>All Projects</h3>
                                <div class="col-sm-6 text-right">

                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row m-t-20">
                    <div class="col-sm-12 table-responsive">
                        <table class="table table-bordered myprojects">
                            <thead>
                            <tr>
                                <td scope="col">ID</td>
                                <td scope="col">Project name</td>
                                <td class="text-center" scope="col">Tasks</td>
                                <td scope="col" class="text-center">Action</td>
                            </tr>
                            </thead>
                            <tbody>
                            <?php
                            if($projects)
                            {
                                foreach ($projects as $project)
                                {
                                    echo '<tr>
                                            <td class="projectid">'.$project->project_id.'</td>
                                            <td class="projectname">'.$project->project_name.'</td>  
                                            <td class="text-center">'.$project->task_count.'</td>                                                                                       
                                            <td  class="text-center"><a target="_blank" href="'.$this->Url->build("/projects/single/").$project->project_id.'"><i title="Edit" role="button" class="fa fa-edit text-themecolor" aria-hidden="true"></i> View</a></td>
                                          </tr>';
                                }
                            }
                            else
                            {
                                echo '<tr>
                                        <td class="text-center" colspan="4">No projects are assigned yet.</td>
                                     </tr>';
                            }
                            ?>
                            </tbody>
                        </table>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>
