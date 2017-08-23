<?php if ($this->get('eventList') != ''): ?>
    <ul class="list-unstyled">
        <?php foreach ($this->get('eventList') as $eventlist): ?>
            <?php $date = new \Ilch\Date($eventlist->getStart()); ?>
            <li>
                <i class="fa fa-calendar"></i>
                <a href="<?=$this->getUrl('events/show/event/id/' . $eventlist->getId()) ?>">
                    <?=((strlen($this->escape($eventlist->getTitle()))<15) ? $this->escape($eventlist->getTitle()) : substr($this->escape($eventlist->getTitle()),0,15).'...') ?>
                </a>
                <span class="small pull-right"><?=$date->format("d.m.y", true) ?></span>
            </li>
        <?php endforeach; ?>
    </ul>
<?php else: ?>
    <?=$this->getTrans('noEvent') ?>
<?php endif; ?>
<div align="center"><a href="<?=$this->getUrl('events/show/upcoming/') ?>"><?=$this->getTrans('otherEvents') ?></a></div>
