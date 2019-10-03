<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Timesheets Model
 *
 * @property |\Cake\ORM\Association\BelongsTo $Companies
 *
 * @method \App\Model\Entity\Timesheet get($primaryKey, $options = [])
 * @method \App\Model\Entity\Timesheet newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\Timesheet[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\Timesheet|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Timesheet|bool saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Timesheet patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\Timesheet[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\Timesheet findOrCreate($search, callable $callback = null, $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class TimesheetsTable extends Table
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

        $this->setTable('timesheets');
        $this->setDisplayField(['userid','time_slot']);
        $this->setPrimaryKey(['userid','time_slot']);
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
            ->integer('userid')
            ->requirePresence('userid', 'create','User ID can not be empty.')
            ->notEmpty('userid');

        $validator
            ->date('day')
            ->requirePresence('day', 'create', 'Day can not be empty.')
            ->notEmpty('day');

        $validator
            ->dateTime('time_slot')
            ->requirePresence('time_slot', 'create', 'Time Slot can not be empty.')
            ->notEmpty('time_slot');

        $validator
            ->integer('minutes')
            ->allowEmpty('minutes');

        $validator
            ->integer('project_id')
            ->requirePresence('project_id', 'create', 'Project ID can not be empty.')
            ->notEmpty('project_id');

        $validator
            ->scalar('project_name')
            ->maxLength('project_name', 255)
            ->allowEmpty('project_name');

        $validator
            ->scalar('screenshot')
            ->maxLength('screenshot', 255)
            ->allowEmpty('screenshot');

        $validator
            ->dateTime('screenshot_time')
            ->allowEmpty('screenshot_time');

        $validator
            ->integer('keystrokes_count')
            ->allowEmpty('keystrokes_count');

        $validator
            ->integer('mousemove_count')
            ->allowEmpty('mousemove_count');

        return $validator;
    }

    /**
     * Returns a rules checker object that will be used for validating
     * application integrity.
     *
     * @param \Cake\ORM\RulesChecker $rules The rules object to be modified.
     * @return \Cake\ORM\RulesChecker
     */
    /*
    public function buildRules(RulesChecker $rules)
    {
        $rules->add($rules->isUnique(['time_slot']));
        return $rules;
    }
    */
}
