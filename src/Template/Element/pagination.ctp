<?php
$config = [
    'number' => '<li class="page-item"><a class="page-link" href="{{url}}">{{text}}</a></li>',
    'current' => '<li class="active page-item"><a class="page-link" href="#">{{text}}</a></li>',
    'nextActive' => '<li class="page-item"><a class="page-link" aria-label="Next" href="{{url}}">{{text}}</a></li>',
    'nextDisabled' => '<li class="next disabled page-item"><a class="page-link" aria-label="Next"><span aria-hidden="true">»</span></a></li>',
    'prevActive' => '<li class="page-item"><a class="page-link" aria-label="Previous" href="{{url}}">{{text}}</a></li>',
    'prevDisabled' => '<li class="page-item prev disabled "><a class="page-link" aria-label="Previous"><span aria-hidden="true">«</span></a></li>',
    'first' => '<li class="page-item"><a class="page-link" aria-label="First" href="{{url}}">{{text}}</a></li>',
    'last' => '<li class="page-item"><a class="page-link" aria-label="Last" href="{{url}}">{{text}}</a></li>',

];
$this->Paginator->setTemplates($config);
?>

    <ul class="pagination justify-content-center">
        <?= $this->Paginator->first('<<') ?>
        <?= $this->Paginator->prev('<') ?>
        <?= $this->Paginator->numbers() ?>
        <?= $this->Paginator->next('>') ?>
        <?= $this->Paginator->last('>>') ?>
    </ul>

<p class="text-center"><?= $this->Paginator->counter(['format' => __('Page {{page}} of {{pages}}, showing {{current}} record(s) out of {{count}} total')]) ?></p>
