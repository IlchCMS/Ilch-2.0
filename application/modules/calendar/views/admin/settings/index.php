<?php

/** @var \Modules\Calendar\Models\Events[] $events */
$events = $this->get('events');

/** @var \Ilch\View $this */
?>
<h1><?=$this->getTrans('settings') ?></h1>
<div class="alert alert-info" role="alert">
    <?=$this->getTrans('calendarSourceInfo') ?>
</div>
<div class="table-responsive">
    <table class="table table-hover table-striped">
        <colgroup>
            <col class="icon_width" />
            <col />
        </colgroup>
        <thead>
            <tr>
                <th></th>
                <th><?=$this->getTrans('calendarSource') ?></th>
            </tr>
        </thead>
        <tbody>
        <?php foreach ($events as $event): ?>
            <tr>
                <?php $icon = ($event->isEnabled()) ? 'fa-regular fa-square-check' : 'fa-regular fa-square' ?>
                <td><a href="<?=$this->getUrl(['action' => 'enable', 'id' => $event->getId(), 'enabled' => !$event->isEnabled() ? 'true' : 'false'], 'admin', true) ?>"><i class="<?=$icon ?>"></i></a></td>
                <td><?=$this->escape($event->getUrl()) ?></td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
</div>
