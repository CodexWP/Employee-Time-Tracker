<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Timesheet $timesheet
 */
?>
<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Form->postLink(
                __('Delete'),
                ['action' => 'delete', $timesheet->timeid],
                ['confirm' => __('Are you sure you want to delete # {0}?', $timesheet->timeid)]
            )
        ?></li>
        <li><?= $this->Html->link(__('List Timesheets'), ['action' => 'index']) ?></li>
    </ul>
</nav>
<div class="timesheets form large-9 medium-8 columns content">
    <?= $this->Form->create($timesheet) ?>
    <fieldset>
        <legend><?= __('Edit Timesheet') ?></legend>
        <?php
            echo $this->Form->control('company_id');
            echo $this->Form->control('userid');
            echo $this->Form->control('day');
            echo $this->Form->control('time_start');
            echo $this->Form->control('time_end');
            echo $this->Form->control('minutes');
            echo $this->Form->control('screenshot');
            echo $this->Form->control('screenshot_time');
            echo $this->Form->control('keystrokes_count');
            echo $this->Form->control('mousemove_count');
        ?>
    </fieldset>
    <?= $this->Form->button(__('Submit')) ?>
    <?= $this->Form->end() ?>
</div>
