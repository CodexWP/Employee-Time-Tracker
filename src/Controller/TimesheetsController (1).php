<?php
namespace App\Controller;

use App\Controller\AppController;
use Cake\I18n\Time;

/**
 * Timesheets Controller
 *
 * @property \App\Model\Table\TimesheetsTable $Timesheets
 *
 * @method \App\Model\Entity\Timesheet[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class TimesheetsController extends AppController
{

    /**
     * Index method
     *
     * @return \Cake\Http\Response|void
     */

    public function index()
    {

        $userid = $this->session['user']['userid'];
        if($this->isadmin()) {
            $companymodel = $this->loadModel('Company');
            $membermodel = $this->loadModel('Members');
            $pmodel = $this->loadModel('Projects');
            $p = $pmodel->find('all');
            $projects = array();
            foreach ($p as $k)
                $projects[$k->project_id] = $k->project_name;

            $membersarr = array();
            $company_id = null;
            $company = $companymodel->getcompanybyuserid($userid);
            if ($company) {
                $company_id = $company->company_id;
                $companymembers = $membermodel->getmycompanymembers($company_id);
                
                foreach ($companymembers as $member)
                {
                    $membersarr[$member->userid] = $member->u['fname'] . ' ' . $member->u['lname'];
                }
            }

            $filter = $this->filterby($company_id);
            $query = $this->Timesheets->find();
            $query = $query->select(['minutes' => $query->func()->sum('minutes'), 'day'])
                ->where($filter['where'])
                ->group('day');

            $range = $filter['range'];
            $period = new \DatePeriod(
                new \DateTime($range[0]),
                new \DateInterval('P1D'),
                (new \DateTime($range[1]))->modify('+1 day')
            );

            if (strtotime($range[0]) == strtotime($range[1]))
                $period = array('DateTime' => new \DateTime($range[0]));

            $dailymincount = array();
            foreach ($query as $result)
                $dailymincount[$result->day->format('Y/m/d')] = $result->minutes;

            $ctpdata = array(
                'period' => $period,
                'dailymincount' => $dailymincount,
                'companymembers' => $membersarr,
                'projects' => $projects
            );

            $this->set(compact('ctpdata'));
        }
        else if($this->isemployee())
        {
            $filter = $this->filterby($userid,'u');
            $query = $this->Timesheets->find();
            $query = $query->select(['minutes' => $query->func()->sum('minutes'), 'day'])
                ->where($filter['where'])
                ->group('day');

            $range = $filter['range'];
            $period = new \DatePeriod(
                new \DateTime($range[0]),
                new \DateInterval('P1D'),
                (new \DateTime($range[1]))->modify('+1 day')
            );

            if (strtotime($range[0]) == strtotime($range[1]))
                $period = array('DateTime' => new \DateTime($range[0]));

            $dailymincount = array();
            foreach ($query as $result)
                $dailymincount[$result->day->format('Y/m/d')] = $result->minutes;

            $ctpdata = array(
                'period' => $period,
                'dailymincount' => $dailymincount
            );
            $this->set(compact('ctpdata'));
            $this->render('index_employee');
        }
        else if($this->isclient())
        {
            $pmodel = $this->loadModel('Projects');
            $p = $pmodel->find('all')->where(['created_userid'=>$userid]);
            $projects = array();
            foreach ($p as $k)
                $projects[$k->project_id] = $k->project_name;


            $filter = $this->filterby_client();
            $filter['where']['p.created_userid'] = $userid;
            $query = $this->Timesheets->find();
            $query = $query->select(['minutes' => $query->func()->sum('minutes'), 'day'])
                ->join([
                    'p'=>[
                        'table' => 'Projects',
                        'type' => 'LEFT',
                        'conditions' => 'p.project_id = Timesheets.project_id',
                    ]
                ])
                ->where($filter['where'])
                ->group('day');

            $range = $filter['range'];
            $period = new \DatePeriod(
                new \DateTime($range[0]),
                new \DateInterval('P1D'),
                (new \DateTime($range[1]))->modify('+1 day')
            );

            if (strtotime($range[0]) == strtotime($range[1]))
                $period = array('DateTime' => new \DateTime($range[0]));

            $dailymincount = array();
            foreach ($query as $result)
                $dailymincount[$result->day->format('Y/m/d')] = $result->minutes;

            $ctpdata = array(
                'period' => $period,
                'dailymincount' => $dailymincount,
                'projects' => $projects
            );

            $this->set(compact('ctpdata'));
            $this->render('index_client');
        }

    }

    private function filterby($id, $cu='c')
    {
        $tz = $this->session['user']['timezone'];

        if($this->request->getQuery('daterange')) {
            $range = explode("-", $this->request->getQuery('daterange'));
        }
        else
            $range = array(
                (new Time('first day of this month'))
                    ->format('Y/m/d'),
                (new Time('last day of this month'))
                    ->format('Y/m/d')
            );

        $where = array();

        if($cu=='c')
        {
            $where['company_id'] = $id;
            if($this->request->getQuery('member'))
                $where['userid'] = $this->request->getQuery('member');
        }

        if($cu=='u')
        {
            $where['userid'] = $id;
        }

        if($this->request->getQuery('project_id')) {
            $where['project_id'] = $this->request->getQuery('project_id');
        }

        $where['day >='] = (new Time($range[0]))->setTimezone('UTC');
        $where['day <='] = (new Time($range[1]))->setTimezone('UTC');

        return array('range'=>$range,'where'=>$where);
    }

    private function filterby_client()
    {
        if($this->request->getQuery('daterange')) {
            $range = explode("-", $this->request->getQuery('daterange'));
        }
        else
            $range = array(
                (new Time('first day of this month'))
                    ->format('Y/m/d'),
                (new Time('last day of this month'))
                    ->format('Y/m/d')
            );

        $where = array();
        if($this->request->getQuery('project_id')) {
            $where['p.project_id'] = $this->request->getQuery('project_id');
        }
        $where['day >='] = (new Time($range[0]))->setTimezone('UTC');
        $where['day <='] = (new Time($range[1]))->setTimezone('UTC');

        return array('range'=>$range,'where'=>$where);
    }

    /**
     * View method
     *
     * @param string|null $id Timesheet id.
     * @return \Cake\Http\Response|void
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $timesheet = $this->Timesheets->get($id, [
            'contain' => ['Companies']
        ]);
        $this->set('timesheet', $timesheet);
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $timesheet = $this->Timesheets->newEntity();
        if ($this->request->is('post')) {
            $timesheet = $this->Timesheets->patchEntity($timesheet, $this->request->getData());
            if ($this->Timesheets->save($timesheet)) {
                $this->Flash->success(__('The timesheet has been saved.'));
                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The timesheet could not be saved. Please, try again.'));
        }
        $companies = $this->Timesheets->Companies->find('list', ['limit' => 200]);
        $this->set(compact('timesheet', 'companies'));
    }

    /**
     * Edit method
     *
     * @param string|null $id Timesheet id.
     * @return \Cake\Http\Response|null Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $timesheet = $this->Timesheets->get($id, [
            'contain' => []
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $timesheet = $this->Timesheets->patchEntity($timesheet, $this->request->getData());
            if ($this->Timesheets->save($timesheet)) {
                $this->Flash->success(__('The timesheet has been saved.'));
                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The timesheet could not be saved. Please, try again.'));
        }
        $companies = $this->Timesheets->Companies->find('list', ['limit' => 200]);
        $this->set(compact('timesheet', 'companies'));
    }

    /**
     * Delete method
     *
     * @param string|null $id Timesheet id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $timesheet = $this->Timesheets->get($id);
        if ($this->Timesheets->delete($timesheet)) {
            $this->Flash->success(__('The timesheet has been deleted.'));
        } else {
            $this->Flash->error(__('The timesheet could not be deleted. Please, try again.'));
        }
        return $this->redirect(['action' => 'index']);
    }
}
