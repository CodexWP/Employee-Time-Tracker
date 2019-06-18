<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Timesheet $timesheet
 */
?>
<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Html->link(__('Edit Timesheet'), ['action' => 'edit', $timesheet->timeid]) ?> </li>
        <li><?= $this->Form->postLink(__('Delete Timesheet'), ['action' => 'delete', $timesheet->timeid], ['confirm' => __('Are you sure you want to delete # {0}?', $timesheet->timeid)]) ?> </li>
        <li><?= $this->Html->link(__('List Timesheets'), ['action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Timesheet'), ['action' => 'add']) ?> </li>
    </ul>
</nav>
<div class="timesheets view large-9 medium-8 columns content">
    <h3><?= h($timesheet->timeid) ?></h3>
    <table class="vertical-table">
        <tr>
            <th scope="row"><?= __('Screenshot') ?></th>
            <td><?= h($timesheet->screenshot) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Timeid') ?></th>
            <td><?= $this->Number->format($timesheet->timeid) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Company Id') ?></th>
            <td><?= $this->Number->format($timesheet->company_id) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Userid') ?></th>
            <td><?= $this->Number->format($timesheet->userid) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Minutes') ?></th>
            <td><?= $this->Number->format($timesheet->minutes) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Keystrokes Count') ?></th>
            <td><?= $this->Number->format($timesheet->keystrokes_count) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Mousemove Count') ?></th>
            <td><?= $this->Number->format($timesheet->mousemove_count) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Day') ?></th>
            <td><?= h($timesheet->day) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Time Start') ?></th>
            <td><?= h($timesheet->time_start) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Time End') ?></th>
            <td><?= h($timesheet->time_end) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Screenshot Time') ?></th>
            <td><?= h($timesheet->screenshot_time) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Created') ?></th>
            <td><?= h($timesheet->created) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Modified') ?></th>
            <td><?= h($timesheet->modified) ?></td>
        </tr>
    </table>
</div>
