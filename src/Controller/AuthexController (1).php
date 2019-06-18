<?php
namespace App\Controller;
use Cake\Event\Event;
use App\Controller\AppController;
use App\View\Helper\CommonHelper;
use Cake\I18n\Time;
use Cake\Auth\DefaultPasswordHasher;


/**
 * Authex Controller
 *
 *
 * @method \App\Model\Entity\Authex[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class AuthexController extends AppController
{

    public function beforeFilter(Event $event)
    {
        parent::beforeFilter($event);
        $this->Auth->allow(['logout','register']);
        $this->viewBuilder()->setLayout('login');
/*
        if (!$this->Auth->loggedIn() && $this->Cookie->read('rememberme')) {
            $cookie = $this->Cookie->read('rememberme');
            debug($cookie);
            if (!$this->Auth->login($user['User'])) {
                $this->redirect('/logout'); // destroy session & cookie
            }
        }
        */
    }

    public function login()
    {
        if ($this->request->is('post')) {            
            $user = $this->Auth->identify($this->request->getData());
            if ($user) {
                if($user['status']==1)
                {
                    $this->Auth->setUser($user);
                    $this->Flash->success(__('Successfully logged in.'));

                    if ($this->request->getData(['rememberme']) == "on") {
                        $hasher = new DefaultPasswordHasher();
                        $cookie = array();
                        $cookie['username'] = $this->request->getData(['email']);
                        $cookie['password'] = $hasher->hash($this->request->getData(['password']));
                        $this->loadComponent('Cookie');
                        $this->Cookie->write('rememberme', $cookie);
                        $this->Cookie->configKey('rememberme', [
                            'expires' => '+10 days',
                            'httpOnly' => true
                        ]);
                    }

                    return $this->redirect($this->Auth->redirectUrl());
                }
                else
                {
                    $this->Flash->error(__('Account is inactive/suspended.'));
                }




            }
            else
            {
                $this->Flash->error(__('Invalid email or password, try again'));
            }
        }
    }

    public function register()
    {
        $this->checkinvitetoken();

        if ($this->request->is('post')) {
            $post = $this->request->getData();
            $usermodel = $this->loadModel('Users');
            $post['status'] = 1;
            $session = $this->request->getSession();
            $token = $session->read('Config.invite_token');
            if($token)
                $post['type'] = 'employee';
            else
                $post['type'] = 'client';

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
                if($token) {
                    $this->updatemembertable($user->userid, $token);
                    $session->delete('Config.invite_token');
                }
                $this->Flash->success(__('Successfully registered. You can login now.'));
                return $this->redirect('/login');
            }
            $this->Flash->error(__('User could not registered. Please try again.'));
        }
    }

    private function updatemembertable($userid,$token)
    {
         $membermodel = $this->loadModel('Members');
            $query = $membermodel->query()->update()->set(['userid'=>$userid,'status'=>1])
                ->where(['company_id'=>$token->company,'invite_email'=>$token->email,'status'=>-1])
                ->execute();
    }

    private function checkinvitetoken()
    {
        $invite_token = $this->request->getQuery('invite_token');
        $commonhelper = new CommonHelper(new \Cake\View\View());
        if($invite_token)
        {
            if($token=$commonhelper->getdatafrominvitetoken($invite_token))
            {
                $exp_time = $token->exp;
                $now_time = strtotime(Time::now());
                if($now_time<$exp_time)
                {
                    $membermodel = $this->loadModel('Members');
                    $query = $membermodel->find("all")->where(['company_id'=>$token->company,'invite_token'=>$invite_token,'invite_email'=>$token->email,'status'=>-1]);
                    if($query->count() > 0) {
                        $session = $this->request->getSession();
                        $session->write(['Config.invite_token' => $token]);
                        return true;
                    }
                    else
                    {
                        $this->Flash->error(__('Invite token does not exist, tell your client to resend invitation.'));
                    }
                }
                else
                {
                    $this->Flash->error(__('Invite token is expired, tell your client to resend invitation.'));
                }
            }
            else
            {
                $this->Flash->error(__('Invalid invitation token, tell your client to resend invitation.'));
            }
            return $this->redirect($this->request->referer());
        }
    }

    public function logout()
    {
        $this->loadComponent('Cookie');
        $this->Cookie->delete('rememberme');
        return $this->redirect($this->Auth->logout());
    }
}
