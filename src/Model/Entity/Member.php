<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * Member Entity
 *
 * @property int $member_id
 * @property int $userid
 * @property int $company_id
 * @property int $project_id
 * @property int $hourly_rate
 * @property int $monthly_limit
 * @property string $invite_token
 * @property string $invite_email
 * @property int $status
 * @property \Cake\I18n\FrozenTime $created
 * @property \Cake\I18n\FrozenTime $modified
 *
 * @property \App\Model\Entity\Company $company
 * @property \App\Model\Entity\Project $project
 */
class Member extends Entity
{

    /**
     * Fields that can be mass assigned using newEntity() or patchEntity().
     *
     * Note that when '*' is set to true, this allows all unspecified fields to
     * be mass assigned. For security purposes, it is advised to set '*' to false
     * (or remove it), and explicitly make individual fields accessible as needed.
     *
     * @var array
     */
    protected $_accessible = [
        'userid' => true,
        'company_id' => true,
        'project_id' => true,
        'hourly_rate' => true,
        'monthly_limit' => true,
        'invite_token' => true,
        'invite_email' => true,
        'status' => true,
        'created' => true,
        'modified' => true,
    ];
}
