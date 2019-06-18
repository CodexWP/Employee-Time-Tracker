<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;
use Cake\ORM\TableRegistry;

/**
 * Projects Model
 *
 * @method \App\Model\Entity\Project get($primaryKey, $options = [])
 * @method \App\Model\Entity\Project newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\Project[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\Project|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Project|bool saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Project patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\Project[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\Project findOrCreate($search, callable $callback = null, $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class ProjectsTable extends Table
{

    /**
     * Initialize method
     *
     * @param array $config The configuration for the Table.
     * @return void
     */
    public function initialize(array $config)
    {
        parent::initialize($config);

        $this->setTable('projects');
        $this->setDisplayField('project_id');
        $this->setPrimaryKey('project_id');

        $this->addBehavior('Timestamp');
    }

    /**
     * Default validation rules.
     *
     * @param \Cake\Validation\Validator $validator Validator instance.
     * @return \Cake\Validation\Validator
     */
    public function validationDefault(Validator $validator)
    {
        $validator
            ->integer('project_id')
            ->allowEmpty('project_id', 'create');

        $validator
            ->integer('created_userid')
            ->requirePresence('created_userid', 'create')
            ->notEmpty('created_userid');

        $validator
            ->scalar('project_name')
            ->maxLength('project_name', 255)
            ->requirePresence('project_name', 'create')
            ->notEmpty('project_name');

        return $validator;
    }

    public function editproject($post,$userid='')
    {
        if($userid)
            $count = $this->find('all')->where(['project_id' => $post['project_id'],'created_userid'=>$userid])->count();
        else
            $count = $this->find('all')->where(['project_id' => $post['project_id']])->count();

        if($count!=0)
        {
            $entity = $this->get($post['project_id']);
            $entity = $this->patchEntity($entity,$post);
            if ($this->save($entity)) {
                return true;
            }
            else {
                return false;
            }
        }
        else
        {
            return false;
        }
    }

    public function addnewtask($post,$userid='')
    {
        if($userid)
            $query = $this->find('all')->where(['project_id'=>$post['project_id'],'created_userid'=>$userid]);
        else
            $query = $this->find('all')->where(['project_id'=>$post['project_id']]);

        if($query->count()>0) {
            $taskmodel = TableRegistry::get('Tasks');
            $entity = $taskmodel->newEntity();
            $entity = $taskmodel->patchEntity($entity,$post);
            if ($taskmodel->save($entity)) {
                return $entity->id;
            }
            else {
                return false;
            }
        }
        else{
            return false;
        }
    }

    public function edittask($post,$userid='')
    {
        if($userid)
            $where = ['Projects.project_id' => $post['project_id'],'Projects.created_userid'=>$userid,'t.id'=>$post['id']];
        else
            $where=['Projects.project_id' => $post['project_id'],'t.id'=>$post['id']];

        $count = $this->find('all')
            ->join([
                't' => [
                    'table' => 'tasks',
                    'type' => 'INNER',
                    'conditions' => 't.project_id = Projects.project_id'
                ]
            ])
            ->where($where)->count();

        if($count!=0)
        {
            $taskmodel = TableRegistry::get('Tasks');
            $entity = $taskmodel->get($post['id']);
            $entity = $taskmodel->patchEntity($entity,$post);
            if ($taskmodel->save($entity)) {
                return true;
            }
            else {
                return false;
            }
        }
        else
        {
            return false;
        }
    }

    public function deletetask($userid,$post)
    {
        if($userid)
            $where = ['Projects.project_id' => $post['project_id'],'Projects.created_userid'=>$userid,'t.id'=>$post['id']];
        else
            $where=['Projects.project_id' => $post['project_id'],'t.id'=>$post['id']];
        $count = $this->find('all')
            ->join([
                't' => [
                    'table' => 'tasks',
                    'type' => 'INNER',
                    'conditions' => 't.project_id = Projects.project_id'
                ]
            ])
            ->where($where)->count();
        if($count!=0)
        {
            $taskmodel = TableRegistry::get('Tasks');
            $entity = $taskmodel->get($post['id']);
            if ($taskmodel->delete($entity)) {
                return true;
            }
            else {
                return false;
            }
        }
        else
        {
            return false;
        }
    }

}
