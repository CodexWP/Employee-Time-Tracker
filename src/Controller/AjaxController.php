<?php
namespace App\Controller;

use App\Controller\AppController;
use Cake\Http\Exception\NotFoundException;
use Cake\I18n\Time;



/**
 * Ajax Controller
 *
 *
 * @method \App\Model\Entity\Ajax[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class AjaxController extends AppController
{

    /**
     * Index method
     *
     * @return \Cake\Http\Response|void
     */
    public function index()
    {
        if($this->request->is(['get','json'])) {
            $op = $this->request->getQuery('op');
            switch ($op) {
                case 'getavailabletimesbydate':
                    $data = $this->getavailabletimesbydate();
                    break;
                case 'sendtestmail':
                    $data = $this->sendtestmail();
                    break;
                case 'addprojecttask':
                    $data = $this->addprojecttask();/*Verified but need to restrict for different typr of users*/
                    break;
                case 'editprojecttask':
                    $data = $this->editprojecttask();/*Verified but need to restrict for different typr of users*/
                    break;
                case 'editproject':
                    $data = $this->editproject(); /*Verified but need to restrict for different typr of users*/
                    break;
                case 'deleteprojecttask':
                    $data = $this->deleteprojecttask();/*Verified but need to restrict for different typr of users*/
                    break;
                case 'getprojectdetails':
                    $data = $this->getprojectdetails();/*Verified but need to restrict for different typr of users*/
                    break;
                case 'gettaskdetails':
                    $data = $this->gettaskdetails();/*Verified but need to restrict for different typr of users*/
                    break;
                case 'getprojectmembers':
                    $data = $this->getprojectmembers();/*final verified*/
                    break;
                case 'getmemberprojects':
                    $data = $this->getmemberprojects();
                    break;
                case 'getprojectlist':
                    $data = $this->getprojectlist();/*final verified*/
                    break;
                case 'addprojectmember':
                    $data = $this->addprojectmember();/*final verified*/
                    break;
                case 'deleteprojectmember':
                    $data = $this->deleteprojectmember();/*final verified*/
                    break;
                default:
                    $data = array('status'=>'error', 'message'=>'Invalid operation');
                    break;
            }
            return $this->getjson($data);
        }

        throw new NotFoundException(__('Requested method is invalid.'));
    }

    private function getprojectlist(){
        $pmodel = $this->loadModel('Projects');
        if($this->isadmin())
        {
            $project = $pmodel->find('all');
        }
        else if($this->isclient())
        {
            $userid = $this->session['user']['userid'];
            $project = $pmodel->find('all')->where(['created_userid'=>$userid]);
        }
        else
        {
            return array('status'=>'failed','message'=>'Permission denied');
        }
        foreach ($project as $p)
            $projects[] = array('id'=>$p->project_id,'name'=>$p->project_name);

        return array('status'=>'success','data'=>$projects);
    }

    private function addprojectmember(){
        if(!$this->isadmin())
            return array('status'=>'failed','message'=>'Permission denied');
            
        $post = $this->request->getQuery();
        $pmmodel = $this->loadModel('Projectmembers');
        $entity = $pmmodel->newEntity();
        $entity= $pmmodel->patchEntity($entity,$post);
        if(count($entity->getErrors())>0)
        {
            $error = $this->cakeerrortostring($entity->getErrors());
            return array('status'=>'failed','message'=>$error);
        }
        if($pmmodel->save($entity))
            return array('status'=>'success','message'=>'Successfully added.');
        else
            return array('status'=>'failed','message'=>'Failed/Member is already added');
    }

    private function deleteprojectmember(){
        if(!$this->isadmin())
        {
            return array('status'=>'failed','message'=>'Permission denied');
        }

        $post = $this->request->getQuery();
        $pmmodel = $this->loadModel('Projectmembers');
        if($pmmodel->deleteAll(['project_id'=>$post['project_id'],'userid'=>$post['userid']]))
            return array('status'=>'success','message'=>'Successfully deleted.');
        else
            return array('status'=>'failed','message'=>'Invalid request.');

    }

    private function getprojectmembers(){
        if(!$this->isadmin())
        {
            return array('status'=>'failed','message'=>'Permission denied');
        }
        $pid = $this->request->getQuery('project_id');
        $pmmodel = $this->loadModel('Projectmembers');
        $members = $pmmodel->find("all")
            ->select(['u.fname','u.lname','Projectmembers.userid'])
            ->join([
                'u'=>[
                    'table' => 'users',
                    'type' => 'INNER',
                    'conditions' => 'Projectmembers.userid = u.userid',
                ],
            ])
            ->where(['Projectmembers.project_id'=>$pid]);
              
            $projectmembers = array();
            foreach ($members as $member)
                $projectmembers[] = array('uid'=>$member->userid,'name'=> $member->u['fname'].' '. $member->u['lname']);
            return array('status'=>'success','data'=>$projectmembers);
       
    }

    private function getmemberprojects(){
        if(!$this->isadmin())
        {
            return array('status'=>'failed','message'=>'Permission denied');
        }

        $uid = $this->request->getQuery('uid');
        $pmmodel = $this->loadModel('Projectmembers');
        $project = $pmmodel->find("all")
            ->select(['p.project_name','Projectmembers.project_id'])
            ->join([
                'p'=>[
                    'table' => 'projects',
                    'type' => 'INNER',
                    'conditions' => 'Projectmembers.project_id = p.project_id',
                ],
            ])
            ->where(['Projectmembers.userid'=>$uid]);

        $projects = array();
        foreach ($project as $pro)
            $projects[] = array('project_id'=>$pro->project_id,'project_name'=> $pro->p['project_name']);
        return array('status'=>'success','data'=>$projects);

    }

    private function getavailabletimesbydate()
    {
        $date = (new Time($this->request->getQuery('date')))->format('Y/m/d');
        $uid = $this->request->getQuery('uid');
        $tsmodel = $this->loadModel('Timesheets');
        if($date && $uid)
        {
            $query = $tsmodel->find("all")
                ->where(['day'=>$date,'userid'=>$uid]);
            $slots = array();
            foreach ($query as $result)
            {
                $slots[] = $result->time_slot->format('H:ia');
            }
            $data= array('status'=>'success','result'=>$slots);
        }
        else
        {
            $data= array('status'=>'error','message'=>'Invalid Date or Member ID.');
        }
        return $data;
    }

    private function sendtestmail()
    {
        $to_email = $this->session['user']['email'];
        $subject = 'A Test Email';

        if($this->sendmail('testmail', $to_email, $subject))
            return array('status'=>'success');
        else
            return array('status'=>'error');
    }


    private function getprojectdetails()
    {
        $pid = $this->request->getQuery('pid');
        $pmodel = $this->loadModel('Projects');
        $project = $pmodel->get($pid);
        $modified = (new Time($project->modified))->format('h:i a - d M, y');
        $project->modified = $modified;
        return array('status'=>'success','project'=>$project);
    }

    private function editproject(){
        $userid = $this->session['user']['userid'];
        $post = $this->request->getData();
        $pmodel = $this->loadModel('Projects');
        if($this->isadmin())
            $resp = $pmodel->editproject($post);
        else if($this->isclient())
            $resp = $pmodel->editproject($post,$userid);

        if($resp)
            return array('status'=>'success','message'=>'The project has been updated successfully.');
        else
            return array('status'=>'error','message'=>'The project could not be updated, try again.');
    }

    private function gettaskdetails()
    {
        $tid = $this->request->getQuery('tid');
        $tmodel = $this->loadModel('Tasks');
        try {
            $task = $tmodel->get($tid);
            $modified = (new Time($task->modified))->format('h:i a - d M, y');
            $task->modified = $modified;
            return array('status' => 'success', 'task' => $task);
        }
        catch (\Exception $e)
        {
            return array('status' => 'failed', 'message' => $e->getMessage());
        }
    }

    private function addprojecttask()
    {
        $userid = $this->session['user']['userid'];
        $post = $this->request->getData();
        $pmodel = $this->loadModel('Projects');
        if($this->isadmin())
            $id=$pmodel->addnewtask($post);
        else if($this->isclient())
            $id=$pmodel->addnewtask($post,$userid);
        if($id)
            return array('status'=>'success','id'=>$id,'message'=>'The task has been added successfully.');
        else
            return array('status'=>'error','id'=>$id,'message'=>'The task could not be added, try again.');
    }

    private function editprojecttask(){
        $userid = $this->session['user']['userid'];
        $post = $this->request->getData();
        $pmodel = $this->loadModel('Projects');
        if($this->isadmin())
            $resp = $pmodel->edittask($post);
        else if ($this->isclient())
            $resp = $pmodel->edittask($post,$userid);
        if($resp)
            return array('status'=>'success','message'=>'The task has been updated successfully.');
        else
            return array('status'=>'error','message'=>'The task could not be updated, try again.');
    }

    private function deleteprojecttask(){
        $userid = $this->session['user']['userid'];
        $post = $this->request->getQuery();
        $pmodel = $this->loadModel('Projects');
        if($this->isadmin())
            $resp = $pmodel->deletetask($post);
        else if($this->isclient())
            $resp = $pmodel->deletetask($post,$userid);
        if($resp)
            return array('status'=>'success','message'=>'The task has been deleted successfully.');
        else
            return array('status'=>'error','message'=>'The task could not be deleted, try again.');
    }


}
