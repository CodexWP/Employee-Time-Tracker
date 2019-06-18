<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * Timesheet Entity
 *
 * @property int $timeid
 * @property int $company_id
 * @property int $userid
 * @property \Cake\I18n\FrozenDate $day
 * @property \Cake\I18n\FrozenTime $time_slot
 * @property int|null $minutes
 * @property string|null $project_name
 * @property string|null $screenshot
 * @property \Cake\I18n\FrozenTime|null $screenshot_time
 * @property int|null $keystrokes_count
 * @property int|null $mousemove_count
 * @property \Cake\I18n\FrozenTime $created
 * @property \Cake\I18n\FrozenTime $modified
 */
class Timesheet extends Entity
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
        'company_id' => true,
        'userid' => true,
        'day' => true,
        'time_slot' => true,
        'minutes' => true,
        'project_id' => true,
        'project_name' => true,
        'screenshot' => true,
        'screenshot_time' => true,
        'keystrokes_count' => true,
        'mousemove_count' => true,
        'created' => true,
        'modified' => true
    ];
}
