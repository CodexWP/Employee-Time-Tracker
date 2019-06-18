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
                    $data = $this->addprojecttask();
                    break;
                case 'editprojecttask':
                    $data = $this->editprojecttask();
                    break;
                case 'editproject':
                    $data = $this->editproject();
                    break;
                case 'deleteprojecttask':
                    $data = $this->deleteprojecttask();
                    break;
                case 'getprojectdetails':
                    $data = $this->getprojectdetails();
                    break;
                case 'gettaskdetails':
                    $data = $this->gettaskdetails();
                    break;
                case 'getprojectmembers':
                    $data = $this->getprojectmembers();
                    break;
                case 'addprojectmember':
                    $data = $this->addprojectmember();
                    break;
                case 'deleteprojectmember':
                    $data = $this->deleteprojectmember();
                    break;
                default:
                    $data = array('status'=>'error', 'message'=>'Invalid operation');
                    break;
            }
            return $this->getjson($data);
        }

        throw new NotFoundException(__('Requested method is invalid.'));
    }

    private function addprojectmember(){
        $post = $this->request->getQuery();
        $post['project_id']=intval($post['project_id']);
        $post['member_id']=intval($post['member_id']);
        $userid = $this->session['user']['userid'];
        $mmodel = $this->loadModel('Members');
        $pmodel = $this->loadModel('Projects');
        $cmodel = $this->loadModel('Company');
        $company = $cmodel->getcompanybyuserid($userid);
        $mcount = $mmodel->find('all')->where(['company_id'=>$company->company_id])->count();
        $pcount = $pmodel->find('all')->where(['project_id'=>$post['project_id'],'created_userid'=>$userid])->count();
        if($mcount && $pcount)
        {
            $pmmodel = $this->loadModel('Projectmembers');
            $entity = $pmmodel->newEntity();
            $entity= $pmmodel->patchEntity($entity,$post);
            if($pmmodel->save($entity))
                return array('status'=>'success','message'=>'Successfully added.');
            else
                return array('status'=>'failed','message'=>'Failed/Member is already added');
        }
        else
        {
            return array('status'=>'failed','message'=>'Can not add now.');
        }
    }

    private function deleteprojectmember(){
        $post = $this->request->getQuery();
        $post['project_id']=intval($post['project_id']);
        $post['member_id']=intval($post['member_id']);
        $userid = $this->session['user']['userid'];
        $mmodel = $this->loadModel('Members');
        $pmodel = $this->loadModel('Projects');
        $cmodel = $this->loadModel('Company');
        $company = $cmodel->getcompanybyuserid($userid);
        $mcount = $mmodel->find('all')->where(['company_id'=>$company->company_id])->count();
        $pcount = $pmodel->find('all')->where(['project_id'=>$post['project_id'],'created_userid'=>$userid])->count();
        if($mcount && $pcount)
        {
            $pmmodel = $this->loadModel('Projectmembers');

            if($pmmodel->deleteAll(['project_id'=>$post['project_id'],'member_id'=>$post['member_id']]))
                return array('status'=>'success','message'=>'Successfully deleted.');
            else
                return array('status'=>'failed','message'=>'Invalid request.');
        }
        else
        {
            return array('status'=>'failed','message'=>'Can not delete now.');
        }
    }

    private function getprojectmembers(){
        $userid = $this->session['user']['userid'];
        $pid = $this->request->getQuery('project_id');
        $pmodel = $this->loadModel('Projects');
        $count = $pmodel->find("all")->where(['created_userid'=>$userid,'project_id'=>$pid])->count();
        if($count!=0)
        {
            $pmmodel = $this->loadModel('Projectmembers');
            $members = $pmmodel->find("all")
                ->select(['u.fname','u.lname','Projectmembers.member_id'])
                ->join([
                    'm'=>[
                        'table' => 'members',
                        'type' => 'LEFT',
                        'conditions' => 'm.member_id = Projectmembers.member_id',
                    ],
                    'u'=>[
                        'table' => 'users',
                        'type' => 'LEFT',
                        'conditions' => 'm.userid = u.userid',
                    ],
                ])
                ->where(['Projectmembers.project_id'=>$pid]);
            $projectmembers = array();
            foreach ($members as $member)
                $projectmembers[] = array('mid'=>$member->member_id,'name'=> $member->u['fname'].' '. $member->u['lname']);

            return array('status'=>'success','data'=>$projectmembers);
        }
        else
        {
            return array('status'=>'failed');
        }
    }

    private function getavailabletimesbydate()
    {
        $date = (new Time($this->request->getQuery('date')))->format('Y/m/d');
        $mid = $this->request->getQuery('mid');
        $tsmodel = $this->loadModel('Timesheets');
        $cmodel = $this->loadModel('Company');
        $userid = $this->session['user']['userid'];
        $companyid = $cmodel->getcompanybyuserid($userid)->company_id;
        if($date && $mid && $companyid)
        {
            $query = $tsmodel->find("all")
                ->where(['day'=>$date,'userid'=>$mid,'company_id'=>$companyid]);
            $slots = array();
            foreach ($query as $result)
            {
                $slots[] = $result->time_slot->format('h:ia');
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
        if($pmodel->editproject($userid,$post))
            return array('status'=>'success','message'=>'The project has been updated successfully.');
        else
            return array('status'=>'error','message'=>'The project could not be updated, try again.');
    }

    private function gettaskdetails()
    {
        $tid = $this->request->getQuery('tid');
        $tmodel = $this->loadModel('Tasks');
        $task = $tmodel->get($tid);
        $modified = (new Time($task->modified))->format('h:i a - d M, y');
        $task->modified = $modified;
        return array('status'=>'success','task'=>$task);
    }

    private function addprojecttask()
    {
        $userid = $this->session['user']['userid'];
        $post = $this->request->getData();
        $pmodel = $this->loadModel('Projects');
        if($id=$pmodel->addnewtask($userid,$post))
            return array('status'=>'success','id'=>$id,'message'=>'The task has been added successfully.');
        else
            return array('status'=>'error','message'=>'The task could not be added, try again.');
    }

    private function editprojecttask(){
        $userid = $this->session['user']['userid'];
        $post = $this->request->getData();
        $pmodel = $this->loadModel('Projects');
        if($pmodel->edittask($userid,$post))
            return array('status'=>'success','message'=>'The task has been updated successfully.');
        else
            return array('status'=>'error','message'=>'The task could not be updated, try again.');
    }

    private function deleteprojecttask(){
        $userid = $this->session['user']['userid'];
        $post = $this->request->getQuery();
        $pmodel = $this->loadModel('Projects');
        if($pmodel->deletetask($userid,$post))
            return array('status'=>'success','message'=>'The task has been deleted successfully.');
        else
            return array('status'=>'error','message'=>'The task could not be deleted, try again.');
    }


}
