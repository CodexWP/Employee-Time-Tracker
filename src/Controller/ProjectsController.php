<?php
namespace App\Controller;

use App\Controller\AppController;

/**
 * Projects Controller
 *
 *
 * @method \App\Model\Entity\Project[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class ProjectsController extends AppController
{

    public function index()
    {
        if($this->isadmin()) {
            $pmodel = $this->Projects;
            $userid = $this->session['user']['userid'];
            $query = $pmodel->find('all', ['order' => 'Projects.project_id desc']);
            if($this->request->getQuery('clientid'))
                $filter = array('Projects.created_userid'=>intval($this->request->getQuery('clientid')));
            else
                $filter = array('Projects.created_userid'=>$userid);

            $projects = $query->select(['task_count' => $query->func()->count('c.id'), 'project_name', 'project_desc', 'project_id', 'status'])
                ->join([
                    'c' => [
                        'table' => 'tasks',
                        'type' => 'LEFT',
                        'conditions' => 'c.project_id = Projects.project_id',
                    ]
                ])
                ->group('Projects.project_id')
                ->where($filter);

            if ($projects->count() == 0)
                $projects = false;

            $umodel = $this->loadModel('Users');
            $cl = $umodel->find('all')->where(['type'=>'client']);
            $clients = array();
            $clients[$userid]='My Projects';
            foreach ($cl as $c)
                $clients[$c->userid]=$c->fname.' '.$c->lname;

            $me = $umodel->find('all')->where(['type'=>'employee']);
            $members = array();
            foreach ($me as $m)
                $members[$m->userid]=$m->fname.' '.$m->lname;

            $this->set(compact('projects'));
            $this->set(compact('clients'));
            $this->set(compact('members'));
        }
        else if($this->isemployee())
        {
            $userid = $this->session['user']['userid'];
            $query = $this->Projects->find("all");
            $projects = $query->select(['task_count' => $query->func()->count('c.id'),'project_id','project_name','project_desc','status'])
                ->join([
                    'pm'=>[
                            'table' => 'projectmembers',
                            'alias' => 'pm',
                            'type' => 'INNER',
                            'conditions' => 'Projects.project_id = pm.project_id',
                        ],
                    'c'=> [
                            'table' => 'tasks',
                            'type' => 'LEFT',
                            'conditions' => 'c.project_id = Projects.project_id',
                        ]
                    ])
                ->group('Projects.project_id')
                ->where(['pm.userid'=>$userid]);

            $this->set(compact('projects'));
            $this->render('index_employee');
        }
        else if($this->isclient()) {
            $projectmodel = $this->Projects;
            $userid = $this->session['user']['userid'];
            $query = $projectmodel->find('all', ['order' => 'Projects.project_id desc']);
            $projects = $query->select(['task_count' => $query->func()->count('c.id'), 'project_name', 'project_desc', 'project_id', 'status'])
                ->join([
                    'c' => [
                        'table' => 'tasks',
                        'type' => 'LEFT',
                        'conditions' => 'c.project_id = Projects.project_id',
                    ]
                ])
                ->group('Projects.project_id')
                ->where(['Projects.created_userid' => $userid]);

            if ($projects->count() == 0)
                $projects = false;

            $this->set(compact('projects'));
            $this->render('index_client');
        }
        else
        {
            exit;
        }

    }

    public function create()
    {
        $project = $this->Projects->newEntity();
        if ($this->request->is('post')) {
            $post = $this->request->getData();
            $post['created_userid'] = $this->session['user']['userid'];
            $project = $this->Projects->patchEntity($project, $post);
            if ($this->Projects->save($project)) {
                $this->Flash->success(__('The project has been saved.'));
                return $this->redirect('/projects');
            }
            $this->Flash->error(__('The project could not be saved. Please, try again.'));
        }
        return $this->redirect(['action' => 'index']);
    }

    public function delete()
    {
        if ($this->request->is(['patch', 'post', 'put'])) {
            $userid = $this->session['user']['userid'];
            if($this->isadmin())
                $query = $this->Projects->find('all')->where(['project_id' => $this->request->getData('project_id')]);
            else if($this->isclient())
                $query = $this->Projects->find('all')->where(['project_id' => $this->request->getData('project_id'), 'created_userid' => $userid]);

            if ($query->count() > 0) {
                $this->request->allowMethod(['post', 'delete']);
                $project = $this->Projects->get($this->request->getData('project_id'));
                if ($this->Projects->delete($project)) {
                    $tmodel = $this->loadModel('Tasks');
                    $tmodel->deleteAll(['project_id' => $this->request->getData('project_id')]);
                    $this->Flash->success(__('The project has been deleted.'));
                } else {
                    $this->Flash->error(__('The project could not be deleted. Please, try again.'));
                }
            } else {
                $this->Flash->error(__('You don\'t have permission to delete this project.'));
            }
        }
        return $this->redirect(['action' => 'index']);
    }

    public function single($id)
    {
        if (!isset($id) || !is_numeric($id))
            return $this->redirect('/projects');

        $userid = $this->session['user']['userid'];
        if($this->isadmin()) {
            try {
                $project = $this->Projects->get($id);
            } catch (\Exception $e) {
                $this->Flash->error(__($e->getMessage()));
                return $this->redirect('/projects');
            }

            $taskmodel = $this->loadModel('Tasks');
            $tasks = $taskmodel->find('all')->where(['project_id' => $id]);
            $ctpdata = array(
                'project' => $project,
                'tasks' => $tasks
            );
            $this->set(compact('ctpdata'));
        }
        else if($this->isemployee())
        {
            $pmmodel = $this->loadModel('Projectmembers');
            $pm = $pmmodel->find("all")->where(['userid'=>$userid, 'project_id'=>$id])->first();
            if($pm){
                try {
                    $project = $this->Projects->get($id);
                } catch (\Exception $e) {
                    $this->Flash->error(__($e->getMessage()));
                    return $this->redirect('/projects');
                }

                $tmodel = $this->loadModel('Tasks');
                $tasks = $tmodel->find('all')->where(['project_id' => $id]);
                $ctpdata = array(
                    'project' => $project,
                    'tasks' => $tasks
                );
                $this->set(compact('ctpdata'));
                $this->render('single_employee');
            }
            else
            {
                $this->Flash->error("You don't have permission to see this project");
                return $this->redirect('/projects');
            }
        }
        else if($this->isclient()){
            $project = $this->Projects->find('all')->where(['project_id'=>$id,'created_userid'=>$userid])->first();
            if($project) {
                $taskmodel = $this->loadModel('Tasks');
                $tasks = $taskmodel->find('all')->where(['project_id' => $id]);
                $ctpdata = array(
                    'project' => $project,
                    'tasks' => $tasks
                );
                $this->set(compact('ctpdata'));
                $this->render('single_client');
            }
            else{
                $this->Flash->error("You dont have permission to see this project");
                return $this->redirect('/projects');
            }
        }
        else{
            exit;
        }
    }

}
