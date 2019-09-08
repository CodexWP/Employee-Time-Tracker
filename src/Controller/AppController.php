<?php
/**
 * CakePHP(tm) : Rapid Development Framework (https://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 * @link      https://cakephp.org CakePHP(tm) Project
 * @since     0.2.9
 * @license   https://opensource.org/licenses/mit-license.php MIT License
 */
namespace App\Controller;

use Cake\Controller\Controller;
use Cake\Event\Event;
use Cake\Controller\Component\AuthComponent;
use Cake\Mailer\MailerAwareTrait;

/**
 * Application Controller
 *
 * Add your application-wide methods in the class below, your controllers
 * will inherit them.
 *
 * @link https://book.cakephp.org/3.0/en/controllers.html#the-app-controller
 */
class AppController extends Controller
{
    use MailerAwareTrait;
    public $session = array();
    public $settings = array();

    /**
     * Initialization hook method.
     *
     * Use this method to add common initialization code like loading components.
     *
     * e.g. `$this->loadComponent('Security');`
     *
     * @return void
     */
    public function initialize()
    {
        parent::initialize();
        $this->loadComponent('Csrf');
        $this->loadComponent('RequestHandler', [
            'enableBeforeRedirect' => false,
        ]);
        $this->loadComponent('Flash');

        $this->loadComponent('Auth', [
            'authenticate' => [
                'Form' => [
                    'fields' => ['username' => 'email', 'password' => 'password']
                ]
            ],

            'loginAction' => ['controller' => 'Authex', 'action' => 'login'],
            'loginRedirect' => ['controller' => 'Users','action' => 'dashboard'],
            'logoutRedirect' => ['controller' => 'Authex','action' => 'login'],
        ]);

        $this->Auth->getConfig('authenticate',['Form' =>['userModel' => 'Users']]);

        $this->compact();

        /*
         * Enable the following component for recommended CakePHP security settings.
         * see https://book.cakephp.org/3.0/en/controllers/components/security.html
         */
        //$this->loadComponent('Security');
    }

    public function compact(){
        $session = $this->request->getSession();        
        $this->session['user'] = $session->read('Auth.User');
        $this->set('session', $this->session);

        $setmodel = $this->loadModel('Settings');
        $query = $setmodel->find('all');
        foreach ($query as $result)
        {
            $this->settings[$result->name]=$result->value;
        }
        $this->set('settings',$this->settings);
    }

    public function cakeerrortostring($errors=array())
    {
        $e = "";
        foreach ($errors as $key => $value) {
            foreach ($value as $k => $v) {
                $e = $e . '<li>' . $v . '</li>';
            }
        }
        return $e;
    }

    public function getjson($data){
        $result = json_encode($data);
        $this->response->withType('applicaton/json');
        $body = $this->response->getBody();
		$body->write($result);
        $this->response->withbody($body);
        return $this->response;
    }

    public function isadmin(){
        if($this->session['user']['type']=='admin')
            return true;
        else
            return false;
    }

    public function isemployee(){
        if($this->session['user']['type']=='employee')
            return true;
        else
            return false;
    }

    public function isclient(){
        if($this->session['user']['type']=='client')
            return true;
        else
            return false;
    }

    public function sendmail($action, $to_email, $subject, $data=array())
    {
        $config = array();
        $config['from_email'] = $this->settings['system_email'];
        $config['from_name'] = $this->settings['system_title'];
        $config['to_email'] = $to_email;
        $config['subject'] = $subject;
        $config['data'] = $data;
        try{
            $result = $this->getMailer('System')->send($action, [$config]);
            
            if(is_array($result) && isset($result['message']))
                return true;
            else
                return false;
        }
        catch (\Exception $e)
        {
            $this->log($e->getMessage());
            return false;
        }
    }
}
