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

    /**
     * Index method
     *
     * @return \Cake\Http\Response|void
     */
    public function index()
    {
        if($this->isadmin()) {
            $projectmodel = $this->Projects;
            $userid = $this->session['user']['userid'];
            $query = $projectmodel->find('all', ['order' => 'Projects.project_id desc']);
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
            $clients[$userid]='My Self';

            foreach ($cl as $c)
                $clients[$c->userid]=$c->fname.' '.$c->lname;

            $this->set(compact('projects'));
            $this->set(compact('clients'));
        }
        else if($this->isemployee())
        {
            $userid = $this->session['user']['userid'];
            $mmodel = $this->loadModel('Members');
            $member = $mmodel->find('all')->where(['userid'=>$userid])->first();
            $query = $this->Projects->find("all");
            $projects = $query->select(['project_id','project_name','project_desc','status'])
                ->join([
                        'table' => 'projectmembers',
                        'alias' => 'pm',
                        'type' => 'LEFT',
                        'conditions' => 'Projects.project_id = pm.project_id',
                    ])
                ->where(['pm.member_id'=>$member->member_id]);

            $this->set(compact('projects'));
            $this->render('index_employee');
            /*
            $membermodel = $this->loadModel('Members');

            $userid = $this->session['user']['userid'];
            $member = $membermodel->find("all")->where(['userid'=>$userid])->first();

            if($member->project_id) {
                $project_id = $member->project_id;
                $query = $projectmodel->find('all', ['order' => 'Projects.project_id desc']);

                $projects = $query->select(['task_count' => $query->func()->count('c.id'), 'project_name', 'project_desc', 'project_id', 'status'])
                    ->join([
                        'c' => [
                            'table' => 'tasks',
                            'conditions' => 'c.project_id = Projects.project_id',
                        ]
                    ])
                    ->where(['Projects.project_id' => $project_id]);

                if ($projects->count() == 0)
                    $projects = false;
            }
            else{
                $projects = false;
            }
            $this->set(compact('projects'));
            $this->render('index_employee');
            */
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

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null Redirects on successful add, renders view otherwise.
     */
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

    /**
     * Edit method
     *
     * @param string|null $id Project id.
     * @return \Cake\Http\Response|null Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function edit()
    {
        if ($this->request->is(['patch', 'post', 'put'])) {
            $userid = $this->session['user']['userid'];
            $query = $this->Projects->find('all')->where(['project_id'=>$this->request->getData('project_id'),'created_userid'=>$userid]);
            if($query->count()>0) {
                $project = $this->Projects->get($this->request->getData('project_id'));
                $project = $this->Projects->patchEntity($project, $this->request->getData());
                if ($this->Projects->save($project)) {
                    $this->Flash->success(__('The project has been successfully updated.'));
                }
                else {
                    $this->Flash->error(__('The project could not be updated. Please, try again.'));
                }
            }
            else{
                $this->Flash->error(__('You dont have permission to update this project.'));
            }
            return $this->redirect('/projects/single/'.$this->request->getData('project_id'));
        }
        return $this->redirect(['action' => 'index']);
    }

    /**
     * Delete method
     *
     * @param string|null $id Project id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete()
    {
        if ($this->request->is(['patch', 'post', 'put'])) {
            $userid = $this->session['user']['userid'];
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
            $mmodel = $this->loadModel('Members');
            $member = $mmodel->find('all')->where(['userid'=>$userid])->first();

            $pmmodel = $this->loadModel('Projectmembers');
            $pm = $pmmodel->find("all")->where(['member_id'=>$member->member_id, 'project_id'=>$id])->first();
            if($pm){
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

    public function createtask()
    {
        if ($this->request->is(['patch', 'post', 'put'])) {
            $userid = $this->session['user']['userid'];
            $query = $this->Projects->find('all')->where(['project_id'=>$this->request->getData('project_id'),'created_userid'=>$userid]);
            if($query->count()>0) {
                $taskmodel = $this->loadModel('Tasks');
                $entity = $taskmodel->newEntity();
                $post = $this->request->getData();
                $entity = $taskmodel->patchEntity($entity,$post);
                if ($taskmodel->save($entity)) {
                    $this->Flash->success(__('The task has been created successfully.'));
                }
                else {
                    $this->Flash->error(__('The task could not be created. Please, try again.'));
                }
            }
            else{
                $this->Flash->error(__('You dont have permission to create task in this project.'));
            }
            return $this->redirect('/projects/single/'.$this->request->getData('project_id'));
        }
        return $this->redirect(['action' => 'index']);
    }

    public function edittask()
    {
        if ($this->request->is(['patch', 'post', 'put'])) {
            $userid = $this->session['user']['userid'];
            $query = $this->Projects->find('all')->where(['project_id'=>$this->request->getData('project_id'),'created_userid'=>$userid]);
            if($query->count()>0) {
                $taskmodel = $this->loadModel('Tasks');
                $entity = $taskmodel->get($this->request->getData('task_id'));
                $post = $this->request->getData();
                $entity = $taskmodel->patchEntity($entity,$post);
                if ($taskmodel->save($entity)) {
                    $this->Flash->success(__('The task has been updated successfully.'));
                }
                else {
                    $this->Flash->error(__('The task could not be updated. Please, try again.'));
                }
            }
            else{
                $this->Flash->error(__('You dont have permission to update task in this project.'));
            }
            return $this->redirect('/projects/single/'.$this->request->getData('project_id'));
        }
        return $this->redirect(['action' => 'index']);
    }

    public function deletetask()
    {
        if ($this->request->is(['patch', 'post', 'put'])) {
            $userid = $this->session['user']['userid'];
            $query = $this->Projects->find('all')->where(['project_id'=>$this->request->getData('project_id'),'created_userid'=>$userid]);
            if($query->count()>0) {
                $taskmodel = $this->loadModel('Tasks');
                $entity = $taskmodel->get($this->request->getData('task_id'));
                if ($taskmodel->delete($entity)) {
                    $this->Flash->success(__('The task has been deleted successfully.'));
                }
                else {
                    $this->Flash->error(__('The task could not be deleted. Please, try again.'));
                }
            }
            else{
                $this->Flash->error(__('You dont have permission to delete task in this project.'));
            }
            return $this->redirect('/projects/single/'.$this->request->getData('project_id'));
        }
        return $this->redirect(['action' => 'index']);
    }
}
