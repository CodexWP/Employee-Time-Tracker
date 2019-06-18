<?php
namespace App\Controller;

use App\Controller\AppController;

/**
 * Clients Controller
 *
 *
 * @method \App\Model\Entity\Client[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class ClientsController extends AppController
{

    /**
     * Index method
     *
     * @return \Cake\Http\Response|void
     */
    public function index()
    {
        $userid = $this->session['user']['userid'];
        $umodel = $this->loadModel('Users');
        $clients = $umodel->find('all')->where(['type'=>'client']);
        $this->set(compact('clients'));
    }

    /**
     * View method
     *
     * @param string|null $id Client id.
     * @return \Cake\Http\Response|void
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $client = $this->Clients->get($id, [
            'contain' => []
        ]);

        $this->set('client', $client);
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        if ($this->request->is('post')) {
            $post = $this->request->getData();
            $umodel = $this->loadModel('Users');
            $post['status'] = 1;
            $post['type'] = 'client';
            $entity = $umodel->newEntity();
            $entity = $umodel->patchEntity($entity, $post);
            if (count($entity->getErrors()) > 0) {
                $error = $this->cakeerrortostring($entity->getErrors());
                $this->Flash->error(__($error));
                return;
            }
            if ($umodel->save($entity)) {
                $this->Flash->success(__('Client is successfully registered.'));
                return $this->redirect('/clients');
            } else {
                $this->Flash->error(__('Member could not registered. Please try again.'));
            }
        }

    }

    /**
     * Edit method
     *
     * @param string|null $id Client id.
     * @return \Cake\Http\Response|null Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $client = $this->Clients->get($id, [
            'contain' => []
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $client = $this->Clients->patchEntity($client, $this->request->getData());
            if ($this->Clients->save($client)) {
                $this->Flash->success(__('The client has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The client could not be saved. Please, try again.'));
        }
        $this->set(compact('client'));
    }

    /**
     * Delete method
     *
     * @param string|null $id Client id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete()
    {
        if ($this->request->is(['patch', 'post', 'put'])) {
            $clientid = $this->request->getData('client_id');
            $this->request->allowMethod(['post', 'delete']);
            $umodel = $this->loadModel('Users');
            $entity = $umodel->get($clientid);

            if($umodel->delete($entity)) {
                $this->Flash->success(__('The member has been successfully deleted.'));
            }
            else
            {
                $this->Flash->error(__('The member could not be deleted. Please, try again.'));
            }
        }

        return $this->redirect(['action' => 'index']);
    }

}
