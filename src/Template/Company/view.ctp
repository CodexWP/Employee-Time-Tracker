<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Company $company
 */
?>
<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Html->link(__('Edit Company'), ['action' => 'edit', $company->company_id]) ?> </li>
        <li><?= $this->Form->postLink(__('Delete Company'), ['action' => 'delete', $company->company_id], ['confirm' => __('Are you sure you want to delete # {0}?', $company->company_id)]) ?> </li>
        <li><?= $this->Html->link(__('List Company'), ['action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Company'), ['action' => 'add']) ?> </li>
    </ul>
</nav>
<div class="company view large-9 medium-8 columns content">
    <h3><?= h($company->company_id) ?></h3>
    <table class="vertical-table">
        <tr>
            <th scope="row"><?= __('Company Name') ?></th>
            <td><?= h($company->company_name) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Company Logo') ?></th>
            <td><?= h($company->company_logo) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Company Id') ?></th>
            <td><?= $this->Number->format($company->company_id) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Created Userid') ?></th>
            <td><?= $this->Number->format($company->created_userid) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Company Status') ?></th>
            <td><?= $this->Number->format($company->company_status) ?></td>
        </tr>
    </table>
</div>
