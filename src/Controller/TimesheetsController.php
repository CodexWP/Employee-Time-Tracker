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
            $umodel = $this->loadModel('Users');
            $members = $umodel->find('all')->where(['type'=>'employee']);
            foreach ($members as $member)
                $membersarr[$member->userid] = $member->fname . ' ' . $member->lname;

            $pmodel = $this->loadModel('Projects');
            $p = $pmodel->find('all');
            foreach ($p as $k)
                $projects[$k->project_id] = $k->project_name;

            $filter = $this->filterby();

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
                'members' => $membersarr,
                'projects' => $projects
            );

            $this->set(compact('ctpdata'));
        }
        else if($this->isemployee())
        {
            $filter = $this->filterby();
            $query = $this->Timesheets->find();
            $filter['where']['userid'] = $userid;
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


            $filter = $this->filterby('Timesheets.');
            $filter['where']['p.created_userid'] = $userid;
            $query = $this->Timesheets->find();
            $query = $query->select(['minutes' => $query->func()->sum('minutes'), 'day'])
                ->join([
                    'p'=>[
                        'table' => 'Projects',
                        'type' => 'INNER',
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

    private function filterby($alias = '')
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
            $where[$alias.'project_id'] = $this->request->getQuery('project_id');
        }

        if($this->request->getQuery('userid')) {
            $where['userid'] = $this->request->getQuery('userid');
        }

        $where['day >='] = (new Time($range[0]))->setTimezone('UTC');
        $where['day <='] = (new Time($range[1]))->setTimezone('UTC');

        return array('range'=>$range,'where'=>$where);
    }
}
