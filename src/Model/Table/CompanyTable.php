<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Company Model
 *
 * @method \App\Model\Entity\Company get($primaryKey, $options = [])
 * @method \App\Model\Entity\Company newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\Company[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\Company|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Company|bool saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Company patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\Company[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\Company findOrCreate($search, callable $callback = null, $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class CompanyTable extends Table
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

        $this->setTable('company');
        $this->setDisplayField('company_id');
        $this->setPrimaryKey('company_id');

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
            ->integer('company_id')
            ->allowEmpty('company_id', 'create');

        $validator
            ->integer('created_userid')
            ->requirePresence('created_userid', 'create')
            ->notEmpty('created_userid');

        $validator
            ->scalar('company_name')
            ->maxLength('company_name', 255)
            ->requirePresence('company_name', 'create')
            ->notEmpty('company_name');

        $validator
            ->scalar('company_about')
            ->maxLength('company_about', 500)
            ->allowEmpty('company_about');

        $validator
            ->scalar('company_logo')
            ->maxLength('company_logo', 100)
            ->requirePresence('company_logo', 'create')
            ->notEmpty('company_logo');

        $validator
            ->integer('company_status')
            ->requirePresence('company_status', 'create')
            ->notEmpty('company_status');

        return $validator;
    }
    
    public function getcompanybyuserid($userid){
        $query = $this->find("all")->where(['created_userid'=>$userid]);
        foreach ($query as $value) {
            return $value;
        }
        return false;
    }
    public function getcompanybycompanyid($companyid){
        $query = $this->find("all")->where(['company_id'=>$companyid]);
        foreach ($query as $value) {
            return $value;
        }
        return false;
    }

}
