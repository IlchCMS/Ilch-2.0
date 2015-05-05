<?php if ($this->get('eventList') != ''): ?>
    <?php foreach ($this->get('eventList') as $eventlist): ?>
        <?php $date = new \Ilch\Date($eventlist->getDateCreated()); ?>
        <i class="fa fa-calendar"></i> 
        <a href="<?=$this->getUrl('events/index/show/id/' . $eventlist->getId()) ?>"><?=((strlen($this->escape($eventlist->getTitle()))<15) ? $this->escape($eventlist->getTitle()) : substr($this->escape($eventlist->getTitle()),0,15).'...') ?></a> <span class="small" style="float: right;">(<?=$date->format("d.m", true) ?>)</span><br />
    <?php endforeach; ?>
<?php else: ?>
    <?=$this->getTrans('noEvent') ?>
<?php endif; ?>
<hr />
<div align="center"><a href="<?=$this->getUrl('events/index/index/') ?>"><?=$this->getTrans('otherEvents') ?></a></div>
