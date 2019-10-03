<?php
namespace App\Controller;

use App\Controller\AppController;
use Cake\Auth\DefaultPasswordHasher;
use App\View\Helper\CommonHelper;


/**
 * Settings Controller
 *
 *
 * @method \App\Model\Entity\Setting[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class SettingsController extends AppController
{

    /**
     * Index method
     *
     * @return \Cake\Http\Response|void
     */
    public function index()
    {
        if($this->request->is('post'))
        {
            $post = $this->request->getData();
            if(isset($post['mobile_logo'])){unset($post['mobile_logo']);}
            if(isset($post['system_logo'])){unset($post['system_logo']);}
            $usermodel = $this->loadModel('Users');
            $dbpass = $usermodel->get($this->session['user']['userid'])->password;
            $hasher = new DefaultPasswordHasher();
            if(!$hasher->check($post['admin_pass'],$dbpass))
            {
                $this->Flash->error('Admin password is not match.');
            }
            else {
                unset($post['admin_pass']);

                $commonhelper = new CommonHelper(new \Cake\View\View());
                $tmpfile = $_FILES['system_logo'];
                $result = $commonhelper->uploadimage($tmpfile);
                if ($result['status'] == 'success') {
                    $post['system_logo'] = $result['result']['url'];
                }
                $tmpfile = $_FILES['mobile_logo'];
                $result = $commonhelper->uploadimage($tmpfile);
                if ($result['status'] == 'success') {
                    $post['mobile_logo'] = $result['result']['url'];
                }

                $data = array();
                foreach ($post as $key => $value) {
                    $data['name'] = $key;
                    $data['value'] = $value;
                    $entity = $this->Settings->newEntity();
                    $entity = $this->Settings->patchEntity($entity, $data);
                    $this->Settings->save($entity);
                }
                $this->compact();
                $this->Flash->success('Settings is successfully updated.');
            }
        }
    }

    public function test()
    {

    }



    
    public function systemstatus(){}
}
