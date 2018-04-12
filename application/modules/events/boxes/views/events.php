<?php if ($this->get('eventList') != ''): ?>
    <ul class="list-unstyled">
        <?php foreach ($this->get('eventList') as $eventlist): ?>
            <?php $date = new \Ilch\Date($eventlist->getStart()); ?>
            <?php if (is_in_array($this->get('readAccess'), explode(',', $eventlist->getReadAccess())) OR $this->getUser() AND $this->getUser()->hasAccess('module_events')): ?>
                <li>
                    <i class="fa fa-calendar"></i>
                    <a href="<?=$this->getUrl('events/show/event/id/' . $eventlist->getId()) ?>">
                        <?=((strlen($this->escape($eventlist->getTitle()))<15) ? $this->escape($eventlist->getTitle()) : substr($this->escape($eventlist->getTitle()),0,15).'...') ?>
                    </a>
                    <span class="small pull-right"><?=$date->format("d.m.y", true) ?></span>
                </li>
            <?php endif; ?>
        <?php endforeach; ?>
    </ul>
<?php else: ?>
    <?=$this->getTrans('noEvent') ?>
<?php endif; ?>
<div align="center"><a href="<?=$this->getUrl('events/show/upcoming/') ?>"><?=$this->getTrans('otherEvents') ?></a></div>
