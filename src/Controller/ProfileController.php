<?php
namespace App\Controller;

use App\Controller\AppController;
use App\View\Helper\CommonHelper;
use Cake\Auth\DefaultPasswordHasher;


/**
 * Profile Controller
 *
 *
 * @method \App\Model\Entity\Profile[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class ProfileController extends AppController
{
    /**
     * Index method
     *
     * @return \Cake\Http\Response|void
     */

    public function index()
    {
        $userid = $this->session['user']['userid'];
        $usermodel = $this->loadModel('Users');
        $user = $usermodel->get($userid);
        $this->set(compact('user'));
    }

    /**
     * View method
     *
     * @param string|null $id Profile id.
     * @return \Cake\Http\Response|void
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $profile = $this->Profile->get($id, [
            'contain' => []
        ]);

        $this->set('profile', $profile);
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $profile = $this->Profile->newEntity();
        if ($this->request->is('post')) {
            $profile = $this->Profile->patchEntity($profile, $this->request->getData());
            if ($this->Profile->save($profile)) {
                $this->Flash->success(__('The profile has been saved.'));

                return $this->redirect(['action' => 'index']);
            $this->Flash->error(__('The profile could not be saved. Please, try again.'));
        }
    }
        $this->set(compact('profile'));
    }

    /**
     * Edit method
     *
     * @param string|null $id Profile id.
     * @return \Cake\Http\Response|null Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function editprofile()
    {
        if($this->request->is('post'))
        {
            $userid = $this->session['user']['userid'];
            $usermodel = $this->loadModel('Users');

            $post = $this->request->getData();
            if(isset($post['thumb'])){unset($post['thumb']);}

            $commonhelper = new CommonHelper(new \Cake\View\View());
            $tmpfile = $_FILES['thumb'];
            $result = $commonhelper->uploadimage($tmpfile);
            if ($result['status'] == 'success') {
                $post['thumb'] = $result['result']['url'];
            }

            $post['userid'] = $userid;
            $entity = $usermodel->get($userid);
            $entity = $usermodel->patchEntity($entity,$post);
            if($usermodel->save($entity)) {
                $this->Flash->success(__('The profile has been updated.'));
                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The profile could not be saved. Please, try again.'));
            return $this->redirect(['action'=> 'index']);
        }
        return $this->redirect(['action'=> 'index']);
    }

    public function changepassword()
    {
        if($this->request->is('post')) {
            $userid = $this->session['user']['userid'];
            $usermodel = $this->loadModel('Users');
            $post = $this->request->getData();
            if($post['current_password']=='' || $post['new_password']=='' || $post['confirm_password']=='')
            {
                $this->Flash->error(__('Password field is empty.'));
                return $this->redirect(['action'=> 'index']);
            }
            if($post['new_password']!=$post['confirm_password'])
            {
                $this->Flash->error(__('New password/confirm password is not match.'));
                return $this->redirect(['action'=> 'index']);
            }
            $entity = $usermodel->get($this->session['user']['userid']);
            $dbpass = $entity->password;
            $hasher = new DefaultPasswordHasher();
            if(!$hasher->check($post['current_password'],$dbpass))
            {
                $this->Flash->error('Current password is wrong.');
                return $this->redirect(['action'=> 'index']);
            }
            $post['password'] = $post['new_password'];

            $entity = $usermodel->patchEntity($entity,$post);

            if($usermodel->save($entity))
            {
                $this->Flash->success(__('Password has been changed successfully.'));
                return $this->redirect(['action' => 'index']);
            }

            $this->Flash->success(__('Password could not be changed. try again.'));
            return $this->redirect(['action' => 'index']);

        }
        return $this->redirect(['action'=> 'index']);
    }

    /**
     * Delete method
     *
     * @param string|null $id Profile id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function testmail()
    {

        $to_email = $this->session['user']['email'];
        $subject = 'A Test Email';

        if($this->sendmail('testmail', $to_email, $subject))
            debug(array('status'=>'success'));
        else
            debug(array('status'=>'error'));

        exit;
    }
}
