<?php
namespace App\Controller;
use Cake\Event\Event;
use App\Controller\AppController;
use Cake\I18n\Time;


class UsersController extends AppController
{

    /* Show dashboard data for admin/client/employee*/
    public function dashboard()
    {
        $userid = $this->session['user']['userid'];

        if($this->isadmin()) {
            $tsmodel = $this->loadModel('Timesheets');

            $first = (new Time('6 days ago'))->format('Y/m/d');
            $last = (new Time())->format('Y/m/d');

            $query = $tsmodel->find();
            $query = $query->select(['minutes' => $query->func()->sum('minutes')])
                ->where(['day' => (new Time())->format('Y/m/d')]);
            $today_min = 0;
            foreach ($query as $result) {
                $today_min = $result->minutes;
            }

            $query = $tsmodel->find();
            $query = $query->select(['minutes' => $query->func()->sum('minutes')])
                ->where(['day >=' => (new Time('last saturday'))->format('Y/m/d'), 'day <=' => (new Time())->format('Y/m/d')]);
            $thisweek_min = 0;
            foreach ($query as $result) {
                $thisweek_min = $result->minutes;
            }

            $t = new Time('last saturday');
            $l_week_start = $t->modify('7 days ago')->format('Y/m/d');
            $t = new Time('last saturday');
            $l_week_end = $t->modify('1 days ago')->format('Y/m/d');
            $query = $tsmodel->find();
            $query = $query->select(['minutes' => $query->func()->sum('minutes')])
                ->where(['day >=' => $l_week_start, 'day <=' => $l_week_end]);

            $lastweek_min = 0;
            foreach ($query as $result) {
                $lastweek_min = $result->minutes;
            }

            $query = $tsmodel->find();
            $query = $query->select(['minutes' => $query->func()->sum('minutes')])
                ->where(['day >=' => (new Time('first day of this month'))->format('Y/m/d'), 'day <=' => (new Time('last day of this month'))->format('Y/m/d')]);

            $thismonth_min = 0;
            foreach ($query as $result) {
                $thismonth_min = $result->minutes;
            }

            $timebox = array('today' => $today_min, 'thisweek' => $thisweek_min, 'lastweek' => $lastweek_min, 'thismonth' => $thismonth_min);

            $query = $tsmodel->find('all', ['order' => 'day desc']);
            $query = $query->select(['minutes' => $query->func()->sum('minutes'), 'day'])
                ->where(['day >=' => $first, 'day <=' => $last])
                ->group('day');

            $timebar_min = array();
            $timebar_day = array();

            foreach ($query as $result) {
                $timebar_min[] = sprintf('%0.2f', $result->minutes / 60);
                $timebar_day[] = $result->day->format('M d');
            }

            $timebar = array('min' => $timebar_min, 'day' => $timebar_day);
            $query = $tsmodel->find("all", ['order' => 'minutes desc']);
            $employees = $query->select(['userid', 'project_name', 'minutes' => $query->func()->sum('minutes'), 'u.fname', 'u.lname', 'u.thumb'])
                ->join([
                    'table' => 'users',
                    'alias' => 'u',
                    'type' => 'INNER',
                    'conditions' => 'u.userid = Timesheets.userid',
                ])
                ->where(['day >=' => $first, 'day <=' => $last])
                ->group('Timesheets.userid')
                ->limit(5);

            $ctpdata = array(
                'timebox' => $timebox,
                'timebar' => $timebar,
                'employees' => $employees
            );
            $this->set(compact('ctpdata'));
        }

        else if($this->isemployee())
        {
            $tsmodel = $this->loadModel('Timesheets');
            $first = (new Time('6 days ago'))->format('Y/m/d');
            $last = (new Time())->format('Y/m/d');
            $query = $tsmodel->find();
            $query = $query->select(['minutes' => $query->func()->sum('minutes')])
                ->where(['userid' => $userid, 'day' => (new Time())->format('Y/m/d')])->first();
            $today_min = $query->minutes;
            $query = $tsmodel->find();
            $query = $query->select(['minutes' => $query->func()->sum('minutes')])
                ->where(['userid' => $userid, 'day >=' => (new Time('last saturday'))->format('Y/m/d'), 'day <=' => (new Time())->format('Y/m/d')])->first();
            $thisweek_min = $query->minutes;
            $t = new Time('last saturday');
            $l_week_start = $t->modify('7 days ago')->format('Y/m/d');
            $t = new Time('last saturday');
            $l_week_end = $t->modify('1 days ago')->format('Y/m/d');
            $query = $tsmodel->find();
            $query = $query->select(['minutes' => $query->func()->sum('minutes')])
                ->where(['userid' => $userid, 'day >=' => $l_week_start, 'day <=' => $l_week_end])->first();
            $lastweek_min = $query->minutes;
            $query = $tsmodel->find();
            $query = $query->select(['minutes' => $query->func()->sum('minutes')])
                ->where(['userid' => $userid, 'day >=' => (new Time('first day of this month'))->format('Y/m/d'), 'day <=' => (new Time('last day of this month'))->format('Y/m/d')])->first();
            $thismonth_min = $query->minutes;
            $timebox = array('today' => $today_min, 'thisweek' => $thisweek_min, 'lastweek' => $lastweek_min, 'thismonth' => $thismonth_min);
            $query = $tsmodel->find('all', ['order' => 'day desc']);
            $query = $query->select(['minutes' => $query->func()->sum('minutes'), 'day'])
                ->where(['userid' => $userid, 'day >=' => $first, 'day <=' => $last])
                ->group('day');
            $timebar_min = array();
            $timebar_day = array();
            foreach ($query as $result) {
                $timebar_min[] = sprintf('%0.2f', $result->minutes / 60);
                $timebar_day[] = $result->day->format('M d');
            }
            $timebar = array('min' => $timebar_min, 'day' => $timebar_day);
            $ctpdata = array(
                'timebox' => $timebox,
                'timebar' => $timebar
            );
            $this->set(compact('ctpdata'));
            $this->render('dashboard_employee');
        }
        else if($this->isclient())
        {
            $tsmodel = $this->loadModel('Timesheets');
            $first = (new Time('6 days ago'))->format('Y/m/d');
            $last = (new Time())->format('Y/m/d');
            $query = $tsmodel->find();
            $query = $query->select(['minutes' => $query->func()->sum('minutes')])
                ->join([
                    'p'=>[
                        'table' => 'projects',
                        'type' => 'INNER',
                        'conditions' => 'p.project_id = Timesheets.project_id',
                    ]
                ])
                ->where(['p.created_userid' => $userid, 'day' => (new Time())->format('Y/m/d')])->first();

            $today_min = $query->minutes;
            $query = $tsmodel->find();
            $query = $query->select(['minutes' => $query->func()->sum('minutes')])
                ->join([
                    'p'=>[
                        'table' => 'projects',
                        'type' => 'INNER',
                        'conditions' => 'p.project_id = Timesheets.project_id',
                    ]
                ])
                ->where(['p.created_userid' => $userid, 'day >=' => (new Time('last saturday'))->format('Y/m/d'), 'day <=' => (new Time())->format('Y/m/d')])->first();
            $thisweek_min = $query->minutes;

            $t = new Time('last saturday');
            $l_week_start = $t->modify('7 days ago')->format('Y/m/d');
            $t = new Time('last saturday');
            $l_week_end = $t->modify('1 days ago')->format('Y/m/d');
            $query = $tsmodel->find();
            $query = $query->select(['minutes' => $query->func()->sum('minutes')])
                ->join([
                    'p'=>[
                        'table' => 'projects',
                        'type' => 'INNER',
                        'conditions' => 'p.project_id = Timesheets.project_id',
                    ]
                ])
                ->where(['p.created_userid' => $userid, 'day >=' => $l_week_start, 'day <=' => $l_week_end])->first();
            $lastweek_min = $query->minutes;

            $query = $tsmodel->find();
            $query = $query->select(['minutes' => $query->func()->sum('minutes')])
                ->join([
                    'p'=>[
                        'table' => 'projects',
                        'type' => 'INNER',
                        'conditions' => 'p.project_id = Timesheets.project_id',
                    ]
                ])
                ->where(['p.created_userid' => $userid, 'day >=' => (new Time('first day of this month'))->format('Y/m/d'), 'day <=' => (new Time('last day of this month'))->format('Y/m/d')])->first();
            $thismonth_min = $query->minutes;

            $timebox = array('today' => $today_min, 'thisweek' => $thisweek_min, 'lastweek' => $lastweek_min, 'thismonth' => $thismonth_min);
            $query = $tsmodel->find('all', ['order' => 'day desc']);
            $query = $query->select(['minutes' => $query->func()->sum('minutes'), 'day'])
                ->join([
                    'p'=>[
                        'table' => 'projects',
                        'type' => 'INNER',
                        'conditions' => 'p.project_id = Timesheets.project_id',
                    ]
                ])
                ->where(['p.created_userid' => $userid, 'day >=' => $first, 'day <=' => $last])
                ->group('day');
            $timebar_min = array();
            $timebar_day = array();
            foreach ($query as $result) {
                $timebar_min[] = sprintf('%0.2f', $result->minutes / 60);
                $timebar_day[] = $result->day->format('M d');
            }

            $timebar = array('min' => $timebar_min, 'day' => $timebar_day);

            $ctpdata = array(
                'timebox' => $timebox,
                'timebar' => $timebar
            );
            $this->set(compact('ctpdata'));
            $this->render('dashboard_client');
        }
        else
        {
            exit;
        }
    }

    /*show all members - verified*/
    public function members()
    {
        if(!$this->isadmin())
        {
            $this->Flash->error(__("You don't have permission to access this area"));
            return $this->redirect('/');
        }
        $members = $this->Users->find('all')->where(['type'=>'employee']);
        $this->set(compact('members'));
    }

    /*add new member - verified*/
    public function addmember()
    {
        if(!$this->isadmin())
        {
            $this->Flash->error(__("You don't have permission to access this area"));
            return $this->redirect('/');
        }

        if ($this->request->is('post'))
        {
            $post = $this->request->getData();
            $umodel = $this->Users;
            $post['status'] = 1;
            $post['type'] = 'employee';
            $entity = $umodel->newEntity();
            $entity = $umodel->patchEntity($entity,$post);

            if(count($entity->getErrors())>0)
            {
                $error = $this->cakeerrortostring($entity->getErrors());
                $this->Flash->error(__($error));
                return;
            }
            if($umodel->save($entity))
            {
                $this->Flash->success(__('Member is successfully registered.'));
                return $this->redirect('/members');
            }

            $this->Flash->error(__('Member could not registered. Please try again.'));
        }
    }

    /*show all members - verified*/
    public function clients()
    {
        if(!$this->isadmin())
        {
            $this->Flash->error(__("You don't have permission to access this area"));
            return $this->redirect('/');
        }
        $clients = $this->Users->find('all')->where(['type'=>'client']);
        $this->set(compact('clients'));
    }

    /*add new member - verified*/
    public function addclient()
    {
        if(!$this->isadmin())
        {
            $this->Flash->error(__("You don't have permission to access this area"));
            return $this->redirect('/');
        }

        if ($this->request->is('post'))
        {
            $post = $this->request->getData();
            $umodel = $this->Users;
            $post['status'] = 1;
            $post['type'] = 'client';
            $entity = $umodel->newEntity();
            $entity = $umodel->patchEntity($entity,$post);

            if(count($entity->getErrors())>0)
            {
                $error = $this->cakeerrortostring($entity->getErrors());
                $this->Flash->error(__($error));
                return;
            }
            if($umodel->save($entity))
            {
                $this->Flash->success(__('Client is successfully registered.'));
                return $this->redirect('/clients');
            }

            $this->Flash->error(__('Client could not registered. Please try again.'));
        }
    }

    /*Delete method for employee/members - verified*/
    public function delete()
    {
        if(!$this->isadmin())
        {
            $this->Flash->error(__("You don't have permission to access this area"));
            return $this->redirect('/');
        }
        if($this->request->is('post')) {
            $this->request->allowMethod(['post', 'delete']);
            $post = $this->request->getData();
            try {
                $entity = $this->Users->get($post['userid']);
                if ($this->Users->delete($entity)) {
                    $pmmodel = $this->loadModel('Projectmembers');
                    $pmmodel->deleteAll(['userid'=>$post['userid']]);
                    $this->Flash->success(__('Successfully deleted.'));
                }
                else
                    $this->Flash->error(__('Could not be deleted. Please, try again.'));
            }
            catch (\Exception $e) {
                $this->Flash->error(__($e->getMessage()));
            }
            if($post['type']=='employee')
                return $this->redirect('/members');
            if($post['type']=='client')
                return $this->redirect('/clients');
        }
    }

}
