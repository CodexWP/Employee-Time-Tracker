<?php
namespace App\Controller\Api;

use Cake\Event\Event;
use Cake\Http\Exception\UnauthorizedException;
use Cake\Utility\Security;
use Firebase\JWT\JWT;
use Cake\Http\ServerRequest;
use Cake\I18n\Time;

class ApiController extends AppController
{

    public function initialize()
    {
        parent::initialize();
        $this->loadModel('Users');
        $this->Auth->allow(['login','test']);
    }

    public function beforeFilter(Event $event) {
        parent::beforeFilter($event);
    }

    public function test()
    {
        $d = new \DateTime('now');
        $time = strtotime($d->format(("Y-m-d h:i:sa")));
        $day = $d->format("Y-m-d");
        $data = $this->request->getData();

        echo $d->format(("Y-m-d h:i:sa"));
        echo '<br>';
        echo date("Y-m-d h:i:sa", time());
        echo '<br>';
        echo $time;
        exit;
    }
    private function cakeerrortostring($errors=array())
    {
        $e = "";
        foreach ($errors as $key => $value) {
            foreach ($value as $k => $v) {
                $e = $e . '<li>' . $v . '</li>';
            }
        }
        return $e;
    }

    /**
     * Login User to generate token
     */
    public function login()
    {
        $user = $this->Auth->identify();
        if (!$user) {
            throw new UnauthorizedException("Invalid login details");
        }else{
            $tokenId  = base64_encode(32);
            $issuedAt = time();
            $key = Security::getSalt();
            $this->set([
                'msg' => 'Login successfully',
                'success' => true,
                'user' => $user,
                'data' => [
                    'token' => JWT::encode([
                        'alg' => 'HS256',
                        'email' => $user['email'] ,
                        'sub' => $user['email'],
                        'iat' => time(),
                        'exp' =>  time() + 86400,
                    ],
                        $key)
                ],
                '_serialize' => ['success', 'data', 'user', 'key']
            ]);
        }
    }

    public function gettime()
    {
        $d = new \DateTime('now');
        $time = strtotime($d->format(("Y-m-d h:i:sa")));
        $day = $d->format("Y-m-d");
        $data = $this->request->getData();
        $userid = $data['userid'];
        $tsmodel = $this->loadModel('Timesheets');
        $query = $tsmodel->find();
        $query = $query->select(['minutes' => $query->func()->sum('minutes')])
            ->where(['userid'=>$userid,'day'=>$day]);
        foreach ($query as $q)
            $min = $q->minutes;
        if(empty($min)){$min=0;}
        $resp = array("time"=>$time,"minutes"=>$min);
        $status = 1;
        $this->set(compact('resp', 'status'));
        $this->set('_serialize', ['resp', 'status']);
    }

    public function getprojects(){
        $data = $this->request->getData();
        if(!isset($data['userid']))
        {
            throw new UnauthorizedException("Invalid Request");
        }
        else {
            $userid = $data['userid'];
            $pmodel = $this->loadModel('Projects');
            $query = $pmodel->find("all");
            $projects = $query->select(['task_count' => $query->func()->count('c.id'), 'project_id', 'project_name', 'project_desc', 'status'])
                ->join([
                    'pm' => [
                        'table' => 'projectmembers',
                        'alias' => 'pm',
                        'type' => 'INNER',
                        'conditions' => 'Projects.project_id = pm.project_id',
                    ],
                    'c' => [
                        'table' => 'tasks',
                        'type' => 'LEFT',
                        'conditions' => 'c.project_id = Projects.project_id',
                    ]
                ])
                ->group('Projects.project_id')
                ->where(['pm.userid' => $userid])->limit(8);

            $status = 1;
            $this->set(compact('projects', 'status'));
            $this->set('_serialize', ['projects', 'status']);
        }
    }

    public function gettasks()
    {
        $data = $this->request->getData();
        if(!isset($data['userid']) && !isset($data['project_id']))
        {
            throw new UnauthorizedException("Invalid Request");
        }
        else {
            $userid = $data['userid'];
            $project_id = $data['project_id'];

            $pmmodel = $this->loadModel('Projectmembers');
            $pm = $pmmodel->find("all")->where(['userid' => $userid, 'project_id' => $project_id])->first();
            if ($pm) {

                $tmodel = $this->loadModel('Tasks');
                $tasks = $tmodel->find('all')->where(['project_id' => $project_id]);
                $status = 1;
                $this->set(compact('tasks', 'status'));
                $this->set('_serialize', ['tasks', 'status']);
            }
        }
    }

    public function storereport()
    {
        if($this->request->is('post')) {
            $data = $this->request->getData();
            $time_slot = $data['time_slot'];
            $arr = explode(" ", $time_slot);
            $day = $arr[0];
            $ssb64 = base64_decode($data['screenshot']);
            $path = WWW_ROOT . 'img/screenshots/';
            $file = uniqid() . ".png";
            file_put_contents($path . $file, $ssb64);
            $url = \Cake\Routing\Router::url('/', true) . "img/screenshots/" . $file;
            $pmodel = $this->loadModel("Projects");
            $projects = $pmodel->get($data['pid']);
            $tmp = array(
                'day' => $day,
                'project_id' => $data['pid'],
                'project_name' => $projects->project_name,
                'userid' => $data['userid'],
                'minutes' => $data['total_min'],
                'screenshot' => $url,
                'time_slot' => $time_slot,
                'screenshot_time' => $time_slot
            );
            $tsmodel = $this->loadModel('Timesheets');
            $ts = $tsmodel->newEntity();
            $timesheet = $tsmodel->patchEntity($ts, $tmp);
            if (count($timesheet->getErrors()) > 0) {
                $error = $this->cakeerrortostring($timesheet->getErrors());
                throw new UnauthorizedException($error);
            } else {
                if ($tsmodel->save($timesheet)) {
                    $resp = array("status" => "success");
                } else {
                    throw new UnauthorizedException("Something is wrong.");
                }
            }
            $status = 1;
            $this->set(compact('resp', 'status'));
            $this->set('_serialize', ['resp', 'status']);
        }
        else
        {
            throw new UnauthorizedException("Invalid request.");
        }
    }

}