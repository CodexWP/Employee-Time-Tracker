<?php
namespace App\Controller;

use App\Controller\AppController;
use App\View\Helper\CommonHelper;
use Cake\Event\Event;

/**
 * Company Controller
 *
 *
 * @method \App\Model\Entity\Company[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class CompanyController extends AppController
{

    public function index()
    {
        $userid = $this->session['user']['userid'];

        if($this->isadmin()) {
            $company = $this->Company->getcompanybyuserid($userid);
            $this->set(compact('company'));
            if ($company) {
                $membermodel = $this->loadModel('Members');
                $membercount = $membermodel->find()->where(['company_id' => $company->company_id])->count();
                $this->set(compact('membercount'));
            }
        }

        if($this->isemployee()){
            $membermodel = $this->loadModel('Members');
            $member = $membermodel->find()->where(['userid'=>$userid])->first();
            $company = $this->Company->getcompanybycompanyid($member->company_id);
            $this->set(compact('company'));
            $this->render('index_employee');
        }
    }

    public function create()
    {
        if(!$this->isclient())
        {
            $this->Flash->error(__('You dont have permission to access this area.'));
            return $this->redirect('/dashboard');
        }

        if ($this->request->is('post')) {
            $company = $this->Company->newEntity();
            $tmpfile = $_FILES['company_logo'];
            $commonhelper = new CommonHelper(new \Cake\View\View());
            $result = $commonhelper->uploadimage($tmpfile);
            if($result['status']=='failed') {
                $this->Flash->error(__($result['result']));
                return;
            }
            $post = $this->request->getData();
            $post['company_logo'] = $result['result']['url'];
            $post['created_userid'] = $this->session['user']['userid'];
            $post['company_status'] = 1;
            $company_exists = $this->Company->getcompanybyuserid($post['created_userid']);
            if($company_exists)
            {
                $this->Flash->error(__('You already have a company.'));
                return;
            }
            $company = $this->Company->patchEntity($company, $post);

            if ($this->Company->save($company)) {
                $this->Flash->success(__('The company has been created.'));
                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The company could not be created. Please, try again.'));
        }
    }


    public function edit()
    {
        if(!$this->isclient())
        {
            $this->Flash->error(__('You dont have permission to access this area.'));
            return $this->redirect('/company');
        }

        $userid = $this->session['user']['userid'];
        $company = $this->Company->getcompanybyuserid($userid);

        if($company) {
            $company = $this->Company->get($company->company_id, ['contain' => []]);
            if ($this->request->is(['patch', 'post', 'put'])) {
                $post = $this->request->getData();
                $tmpfile = $_FILES['company_logo'];
                $post['company_logo']=$company->company_logo;
                if($tmpfile) {
                    $commonhelper = new CommonHelper(new \Cake\View\View());
                    $result = $commonhelper->uploadimage($tmpfile);
                    if ($result['status'] == 'success') {
                        $post['company_logo']=$result['result']['url'];
                    }
                }
                $company = $this->Company->patchEntity($company, $post);
                 if ($this->Company->save($company)) {
                    $this->Flash->success(__('The company has been updated successfully.'));
                    return $this->redirect(['action' => 'index']);
                }
                $this->Flash->error(__('The company could not be saved. Please, try again.'));
            }
            $this->set(compact('company'));
        }
        else
        {
            $this->Flash->error(__('You don\'t have any company, please create first.'));
            return $this->redirect(['action' => 'index']);
        }
       
    }


    public function delete()
    {
        if(!$this->isclient())
        {
            $this->Flash->error(__('You dont have permission to access this area.'));
            return $this->redirect('/dashboard');
        }
        $userid = $this->session['user']['userid'];
        $company = $this->Company->getcompanybyuserid($userid);
        if(!$company) {
            $this->Flash->error(__('You dont have permission to delete this company.'));
            return $this->redirect('/company');
        }
        
        if ($this->request->is('post')) {
            $this->request->allowMethod(['post', 'delete']);
            $tsmodel = $this->loadModel('Timesheets');
            $tsmodel->deleteAll(['company_id'=>$company->company_id]);

            $membermodel = $this->loadModel('Members');
            $membermodel->deleteAll([]);
            $this->Company->deleteAll([]);
            $this->Flash->success(__('Your company is successfully deleted.'));
            return $this->redirect(['action' => 'index']);
        }
        $this->Flash->error(__('Unknown error occured.'));
        return $this->redirect('/');
    }

    public function beforeFilter(Event $event)
    {
        parent::beforeFilter($event);

    }
}
