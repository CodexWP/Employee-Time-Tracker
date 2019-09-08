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

        $this->Auth->allow(['login',]);

    }

    public function beforeFilter(Event $event) {
        parent::beforeFilter($event);
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

    public function getprojects($userid=null){
        $pmodel = $this->loadModel('Projects');
        $query = $pmodel->find("all");
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
            ->where(['pm.userid'=>$userid])->limit(8);

        $status = 1;
        $this->set(compact('projects','status'));
        $this->set('_serialize', ['projects', 'status']);
    }



    public function add()
    {
        $user = $this->Users->newEntity();
        if ($this->request->is('post')) {
            $this->request->getData['group_id'] = 1;
            $user = $this->Users->patchEntity($user, $this->request->getData);
            if ($this->Users->save($user)) {
                $msg['msg'] = 'The user has been saved.';
                $msg['status'] = 1;
            } else {
                $msg['msg'] = 'The user could not be saved. Please, try again.';
                $msg['status'] = 0;
                $msg['error'] = $user->getErrors();
            }
            extract($msg);
            $this->set(compact('error', 'status', 'msg'));
            $this->set('_serialize', ['error', 'status', 'msg']);
        }

    }

}