<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * Company Entity
 *
 * @property int $company_id
 * @property int $created_userid
 * @property string $company_name
 * @property string $company_about
 * @property string $company_logo
 * @property int $company_status
 * @property \Cake\I18n\FrozenTime $created
 * @property \Cake\I18n\FrozenTime $modified
 */
class Company extends Entity
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
        'created_userid' => true,
        'company_name' => true,
        'company_about' => true,
        'company_logo' => true,
        'company_status' => true,
        'created' => true,
        'modified' => true
    ];
}
