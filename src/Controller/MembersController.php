<?php
namespace App\Controller;

use App\Controller\AppController;
use App\View\Helper\CommonHelper;
use Cake\Event\Event;

/**
 * Members Controller
 *
 * @property \App\Model\Table\MembersTable $Members
 *
 * @method \App\Model\Entity\Member[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class MembersController extends AppController
{

    /**
     * Index method
     *
     * @return \Cake\Http\Response|void
     */
    public function index()
    {
        $userid = $this->session['user']['userid'];
        $companymodel = $this->loadModel('Company');
        $mycompany = $companymodel->getcompanybyuserid($userid);
        $this->set(compact('mycompany'));

        if($mycompany)
        {

            $members = $this->Members->getmycompanymembers($mycompany->company_id);
            $this->set(compact('members'));
            $projectmodel = $this->loadModel('Projects');
            $projects = $projectmodel->find("all")
                    ->select(['project_id','project_name'])
                    ->where(['created_userid'=>$userid]);
            $projectlist = array();
            foreach ($projects as $project)
                $projectlist[$project->project_id] = $project->project_name;

            $this->set(compact('projectlist'));
        }
    }

    public function add()
    {

        if ($this->request->is('post'))
        {
            $post = $this->request->getData();
            $usermodel = $this->loadModel('Users');
            $post['status'] = 1;
            $post['type'] = 'employee';
            $user = $usermodel->newEntity();
            $user = $usermodel->patchEntity($user,$post);

            if(count($user->getErrors())>0)
            {
                $error = $this->cakeerrortostring($user->getErrors());
                $this->Flash->error(__($error));
                return;
            }

            if($usermodel->save($user))
            {
                $userid = $this->session['user']['userid'];
                $companymodel = $this->loadModel('Company');
                $company = $companymodel->getcompanybyuserid($userid);
                $this->updatemembertable($user->userid,$company->company_id);
                $this->Flash->success(__('Member is successfully registered.'));
                return $this->redirect('/members');
            }
            $this->Flash->error(__('Member could not registered. Please try again.'));
        }
    }

    private function updatemembertable($userid,$company_id)
    {
        $member = $this->Members->newEntity();
        $data = array('company_id'=>$company_id,'userid'=>$userid,'status'=>1);
        $member = $this->Members->patchEntity($member, $data);
        $this->Members->save($member);
    }

    public function invite()
    {
        if ($this->request->is('post')) {
            $userid = $this->session['user']['userid'];
            $companymodel = $this->loadModel('Company');
            $mycompany = $companymodel->getcompanybyuserid($userid);
            $post = $this->request->getData();

            if($mycompany)
            {
                $companyid = $mycompany->company_id;
                $commonhelper = new CommonHelper(new \Cake\View\View());
                $invite_token = $commonhelper->generateinvitetoken($post['invite_email'],$companyid);
                $post['invite_token'] = $invite_token;
                $post['company_id'] = $companyid;
                $post['status'] = -1;
                try {
                    $membermodel = $this->Members;
                    $member = $membermodel->newEntity();
                    $member = $membermodel->patchEntity($member, $post);
                    if ($membermodel->save($member)) {
                        $this->sendmail('invitemail', $post['invite_email'], 'You have received an invitation.',$post);
                        $this->Flash->success(__('Invitation is sent successfully.'));
                        return $this->redirect(['action' => 'index']);
                    }
                    $this->Flash->error(__('Then invitation could not be sent. Please, try again.'));
                }
                catch (\PDOException $e)
                {
                    echo $e->getMessage();exit;
                }
            }
            else
            {
                $this->Flash->error(__('Please create your own company first.'));
                return $this->redirect(['controller'=>'company','action' => 'create']);
            }
        }
    }

    /**
     * Edit method
     *
     * @param string|null $id Member id.
     * @return \Cake\Http\Response|null Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function edit()
    {
        if ($this->request->is(['patch', 'post', 'put']))
        {
            $userid = $this->session['user']['userid'];
            $companymodel = $this->loadModel('Company');
            $company = $companymodel->getcompanybyuserid($userid);
            $query = $this->Members->find('all')->where(['member_id' => $this->request->getData('member_id'), 'company_id' => $company->company_id]);
            if($query->count() > 0) {
                $member = $this->Members->get($this->request->getData('member_id'));
                $member = $this->Members->patchEntity($member, $this->request->getData());
                if ($this->Members->save($member)) {
                    $this->Flash->success(__('The member has been successfully updated.'));
                }
                else {
                    $this->Flash->error(__('The member could not be saved. Please, try again.'));
                }
            }
            else{
                $this->Flash->error(__('You don\'t have permission to update this member.'));
            }
        }
        return $this->redirect(['action' => 'index']);
    }

    /**
     * Delete method
     *
     * @param string|null $id Member id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete()
    {
        if ($this->request->is(['patch', 'post', 'put'])) {
            $userid = $this->session['user']['userid'];
            $companymodel = $this->loadModel('Company');
            $company = $companymodel->getcompanybyuserid($userid);
            $member = $this->Members->find('all')->where(['member_id' => $this->request->getData('member_id'), 'company_id' => $company->company_id])->first();

            if($member) {
                $this->request->allowMethod(['post', 'delete']);
                
                if ($this->Members->delete($member)) {
                    $usermodel = $this->loadModel('Users');
                    if($usermodel->delete($member))
                    {
                        $this->Flash->success(__('The member has been successfully deleted.'));
                    }
                    else
                    {
                        $this->Flash->error(__('The member could not be deleted. Please, try again.'));
                    }
                } else {
                    $this->Flash->error(__('The member could not be deleted. Please, try again.'));
                }
            }
            else
            {
                $this->Flash->error(__('You don\'t have permission to delete this member.'));
            }
        }
        return $this->redirect(['action' => 'index']);
    }

    public function beforeFilter(Event $event)
    {
        parent::beforeFilter($event);
        if(!$this->isadmin())
        {
            $this->Flash->error(__('You don\'t have permission to access this area.'));
            return $this->redirect('/');
        }

        $userid = $this->session['user']['userid'];
        $companymodel = $this->loadModel('Company');
        $company = $companymodel->getcompanybyuserid($userid);
        if(!$company)
        {
            $this->Flash->error(__('Please create your company first.'));
            return $this->redirect('/company');
        }
    }
}
