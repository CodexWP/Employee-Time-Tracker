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

    /**
     * View method
     *
     * @param string|null $id Setting id.
     * @return \Cake\Http\Response|void
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $setting = $this->Settings->get($id, ['contain' => []]);
        $this->set('setting', $setting);
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $setting = $this->Settings->newEntity();
        if ($this->request->is('post')) {
            $setting = $this->Settings->patchEntity($setting, $this->request->getData());
            if ($this->Settings->save($setting)) {
                $this->Flash->success(__('The setting has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The setting could not be saved. Please, try again.'));
        }
        $this->set(compact('setting'));
    }

    /**
     * Edit method
     *
     * @param string|null $id Setting id.
     * @return \Cake\Http\Response|null Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $setting = $this->Settings->get($id, [
            'contain' => []
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $setting = $this->Settings->patchEntity($setting, $this->request->getData());
            if ($this->Settings->save($setting)) {
                $this->Flash->success(__('The setting has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The setting could not be saved. Please, try again.'));
        }
        $this->set(compact('setting'));
    }

    /**
     * Delete method
     *
     * @param string|null $id Setting id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $setting = $this->Settings->get($id);
        if ($this->Settings->delete($setting)) {
            $this->Flash->success(__('The setting has been deleted.'));
        } else {
            $this->Flash->error(__('The setting could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }
}
