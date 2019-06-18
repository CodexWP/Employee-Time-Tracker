<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Member $member
 */
?>
<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Html->link(__('Edit Member'), ['action' => 'edit', $member->company_id]) ?> </li>
        <li><?= $this->Form->postLink(__('Delete Member'), ['action' => 'delete', $member->company_id], ['confirm' => __('Are you sure you want to delete # {0}?', $member->company_id)]) ?> </li>
        <li><?= $this->Html->link(__('List Members'), ['action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Member'), ['action' => 'add']) ?> </li>
    </ul>
</nav>
<div class="members view large-9 medium-8 columns content">
    <h3><?= h($member->company_id) ?></h3>
    <table class="vertical-table">
        <tr>
            <th scope="row"><?= __('Company Id') ?></th>
            <td><?= $this->Number->format($member->company_id) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Userid') ?></th>
            <td><?= $this->Number->format($member->userid) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Project Id') ?></th>
            <td><?= $this->Number->format($member->project_id) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Hourly Rate') ?></th>
            <td><?= $this->Number->format($member->hourly_rate) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Monthly Limit') ?></th>
            <td><?= $this->Number->format($member->monthly_limit) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Status') ?></th>
            <td><?= $this->Number->format($member->status) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Created') ?></th>
            <td><?= h($member->created) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Modified') ?></th>
            <td><?= h($member->modified) ?></td>
        </tr>
    </table>
</div>
