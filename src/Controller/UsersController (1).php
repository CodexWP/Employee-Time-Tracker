<?php
namespace App\Controller;
use Cake\Event\Event;
use App\Controller\AppController;
use Cake\I18n\Time;

/**
 * Users Controller
 *
 * @property \App\Model\Table\UsersTable $Users
 *
 * @method \App\Model\Entity\User[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class UsersController extends AppController
{


    /**
     * Index method
     *
     * @return \Cake\Http\Response|void
     */
    public function dashboard()
    {
        $userid = $this->session['user']['userid'];

        if($this->isadmin()) {
            $tsmodel = $this->loadModel('Timesheets');
            $companymodel = $this->loadModel('Company');
            $membermodel = $this->loadModel('Members');

            $company = $companymodel->getcompanybyuserid($userid);
            if(!$company)
            {
                $this->Flash->error(__('Please create your own company first.'));
                return $this->redirect('/company');
            }
            $company_id = $company->company_id;

            $first = (new Time('6 days ago'))->format('Y/m/d');
            $last = (new Time())->format('Y/m/d');

            $query = $tsmodel->find();
            $query = $query->select(['minutes' => $query->func()->sum('minutes')])
                ->where(['company_id' => $company_id, 'day' => (new Time())->format('Y/m/d')]);
            $today_min = 0;
            foreach ($query as $result) {
                $today_min = $result->minutes;
            }

            $query = $tsmodel->find();
            $query = $query->select(['minutes' => $query->func()->sum('minutes')])
                ->where(['company_id' => $company_id, 'day >=' => (new Time('last saturday'))->format('Y/m/d'), 'day <=' => (new Time())->format('Y/m/d')]);
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
                ->where(['company_id' => $company_id, 'day >=' => $l_week_start, 'day <=' => $l_week_end]);

            $lastweek_min = 0;
            foreach ($query as $result) {
                $lastweek_min = $result->minutes;
            }

            $query = $tsmodel->find();
            $query = $query->select(['minutes' => $query->func()->sum('minutes')])
                ->where(['company_id' => $company_id, 'day >=' => (new Time('first day of this month'))->format('Y/m/d'), 'day <=' => (new Time('last day of this month'))->format('Y/m/d')]);

            $thismonth_min = 0;
            foreach ($query as $result) {
                $thismonth_min = $result->minutes;
            }

            $timebox = array('today' => $today_min, 'thisweek' => $thisweek_min, 'lastweek' => $lastweek_min, 'thismonth' => $thismonth_min);

            $query = $tsmodel->find('all', ['order' => 'day desc']);
            $query = $query->select(['minutes' => $query->func()->sum('minutes'), 'day'])
                ->where(['company_id' => $company_id, 'day >=' => $first, 'day <=' => $last])
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
                    'type' => 'LEFT',
                    'conditions' => 'u.userid = Timesheets.userid',
                ])
                ->where(['Timesheets.company_id' => $company_id, 'day >=' => $first, 'day <=' => $last])
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
                        'type' => 'LEFT',
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
                        'type' => 'LEFT',
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
                        'type' => 'LEFT',
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
                        'type' => 'LEFT',
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
                        'type' => 'LEFT',
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


    public function view($id = null)
    {
        $user = $this->Users->get($id, [ 'contain' => []]);
        $this->set('user', $user);
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $user = $this->Users->newEntity();
        if ($this->request->is('post')) {
            $user = $this->Users->patchEntity($user, $this->request->getData());
            if ($this->Users->save($user)) {
                $this->Flash->success(__('The user has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The user could not be saved. Please, try again.'));
        }
        $this->set(compact('user'));
    }

    /**
     * Edit method
     *
     * @param string|null $id User id.
     * @return \Cake\Http\Response|null Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $user = $this->Users->get($id, [
            'contain' => []
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $user = $this->Users->patchEntity($user, $this->request->getData());
            if ($this->Users->save($user)) {
                $this->Flash->success(__('The user has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The user could not be saved. Please, try again.'));
        }
        $this->set(compact('user'));
    }

    /**
     * Delete method
     *
     * @param string|null $id User id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $user = $this->Users->get($id);
        if ($this->Users->delete($user)) {
            $this->Flash->success(__('The user has been deleted.'));
        } else {
            $this->Flash->error(__('The user could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }

    public function login()
    {
        if ($this->request->is('post')) {
            $user = $this->Auth->identify();
            if ($user) {
                $this->Auth->setUser($user);
                return $this->redirect($this->Auth->redirectUrl());
            }
            $this->Flash->error('Your username or password is incorrect.');
        }
    }
}
