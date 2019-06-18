<?php
namespace App\Controller;

use App\Controller\AppController;
use Cake\I18n\Time;

/*
 * Screenshots Controller
 *
 *
 * @method \App\Model\Entity\Screenshot[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class ScreenshotsController extends AppController
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
            $pmodel = $this->loadModel('Projects');
            $p = $pmodel->find('all');
            $projects = array();
            foreach ($p as $k)
                $projects[$k->project_id] = $k->project_name;

            $umodel = $this->loadModel('Users');
            $members = $umodel->find('all')->where(['type'=>'employee']);
            foreach ($members as $member)
                $membersarr[$member->userid] = $member->fname . ' ' . $member->lname;

            $screenshots = [];

            $where = $this->filterby();

            if ($this->request->getQuery('daterange') && $this->request->getQuery('userid')) {
                $timemodel = $this->loadModel('Timesheets');
                $screenshots = $timemodel->find('all', ['order' => 'time_slot desc'])
                    ->where($where);
            }
            $ctpdata = array(
                'screenshots' => $screenshots,
                'members' => $membersarr,
                'projects' => $projects
            );
            $this->set(compact('ctpdata'));
        }
        else if($this->isemployee())
        {
            $screenshots = [];
            $where = $this->filterby($userid,'u');
            $timemodel = $this->loadModel('Timesheets');
            $screenshots = $timemodel->find('all', ['order' => 'time_slot desc'])
                ->where($where);

            $screenshots = $this->paginate($screenshots);
            $ctpdata = array(
                'screenshots' => $screenshots,
            );
            $this->set(compact('ctpdata'));
            $this->render('index_employee');
        }
        else if($this->isclient())
        {
            $companymodel = $this->loadModel('Company');
            $membermodel = $this->loadModel('Members');
            $pmodel = $this->loadModel('Projects');
            $p = $pmodel->find('all')->where(['created_userid'=>$userid]);
            $projects = array();
            foreach ($p as $k)
                $projects[$k->project_id] = $k->project_name;


            $screenshots = [];
            $where = $this->filterby_client();
            $where['p.created_userid'] = $userid;
            if ($this->request->getQuery('daterange') && $this->request->getQuery('project_id') ) {
                $timemodel = $this->loadModel('Timesheets');
                $screenshots = $timemodel->find('all', ['order' => 'time_slot desc'])
                    ->join([
                        'p'=>[
                            'table' => 'Projects',
                            'type' => 'INNER',
                            'conditions' => 'p.project_id = Timesheets.project_id',
                        ]
                    ])
                    ->where($where);
            }

            $ctpdata = array(
                'screenshots' => $screenshots,
                'projects' => $projects
            );
            $this->set(compact('ctpdata'));
            $this->render('index_client');
        }
        else
        {
            exit;
        }
    }

    private function filterby()
    {
        if($this->request->getQuery('daterange'))
            $range = explode("-", $this->request->getQuery('daterange'));
        else
            $range = array(
                (new Time('first day of this month'))
                    ->format('Y/m/d'),
                (new Time('last day of this month'))
                    ->format('Y/m/d')
            );

        $where = array();

        if($this->request->getQuery('project_id'))
        {
            $where['project_id'] = $this->request->getQuery('project_id');
        }

        if($this->request->getQuery('userid')) {
            $where['userid'] = $this->request->getQuery('userid');
        }

        $where['day >='] = (new Time($range[0]))->setTimezone('UTC');
        $where['day <='] = (new Time($range[1]))->setTimezone('UTC');

        return $where;
    }

    private function filterby_client()
    {
        if($this->request->getQuery('daterange'))
            $range = explode("-", $this->request->getQuery('daterange'));
        else
            $range = array(
                (new Time('first day of this month'))
                    ->format('Y/m/d'),
                (new Time('last day of this month'))
                    ->format('Y/m/d')
            );

        $where = array();

        if($this->request->getQuery('project_id'))
        {
            $where['p.project_id'] = $this->request->getQuery('project_id');
        }
        $where['day >='] = (new Time($range[0]))->setTimezone('UTC');
        $where['day <='] = (new Time($range[1]))->setTimezone('UTC');

        return $where;
    }


    public function addmanualtime()
    {
        if($this->isadmin()) {
            if ($this->request->is('post')) {
                $post = $this->request->getData();
                $uid = $post['userid'];
                $date = $post['date'];
                $time_from = $post['_timefrom'];
                $time_to = $post['_timeto'];
                $data = array();

                $a = substr($time_from, -2);
                $m = intval(substr($time_from, -4, 2));
                $h = intval(substr($time_from, 0, 2));

                $a1 = substr($time_to, -2);
                $m1 = intval(substr($time_to, -4, 2));
                $h1 = intval(substr($time_to, 0, 2));
                $tmp = array(
                    'day' => $date,
                    'project_id' => $post['project_id'],
                    'project_name' => $post['project_name'],
                    'userid' => $uid,
                    'minutes' => 10,
                    'screenshot' => \Cake\Routing\Router::url('/', true) . 'img/manual-screenshot.png'
                );

                if ($h == 12 && $a == 'am') {
                    $h = 0;
                }
                if ($h1 == 12 && $a1 == 'am') {
                    $h1 = 0;
                }
                for ($i = $h; $i <= $h1; $i++) {
                    for ($j = 0; $j < 60; $j = $j + 10) {
                        if ($i == $h1 && $j == $m1)
                            break;
                        if ($i == $h) {
                            if ($j >= $m) {
                                $ts = (new Time($date . ' ' . $i . ':' . $j));
                                $tmp['time_slot'] = $ts;
                                $tmp['screenshot_time'] = $ts;
                                $data[] = $tmp;
                            }
                        } else {
                            $ts = (new Time($date . ' ' . $i . ':' . $j));
                            $tmp['time_slot'] = $ts;
                            $tmp['screenshot_time'] = $ts;
                            $data[] = $tmp;
                        }

                    }
                }

                $tsmodel = $this->loadModel('Timesheets');
                $entities = $tsmodel->newEntities($data);
                $entities = $tsmodel->patchEntities($entities, $data);
                //$errors = $entities->getErrors();

                $min = 0;
                foreach ($entities as $entity) {
                    if ($tsmodel->save($entity)) {
                        $min = $min + 10;
                    }
                }
                $h = intval($min / 60);
                $m = intval($min % 60);

                $this->Flash->success($h . ' hour and ' . $m . ' minutes are added.');
            }
        }

        return $this->redirect('/screenshots');
    }

    public function delete()
    {
        if($this->isadmin() && $this->request->is('post'))
        {
            $this->request->allowMethod(['post', 'delete']);
            $post = $this->request->getData();
            $sids = array_map('intval', explode(',', $post['sids']));
            $tsmodel = $this->loadModel('Timesheets');
            $condition = array('timeid in' => $sids);
            $tsmodel->deleteAll($condition,false);
            $this->Flash->success(__('Screenshots are deleted successfully.'));

            return $this->redirect($this->request->referer());
        }
        return $this->redirect('/');
    }


}
