<?php if ($this->get('eventList') != ''): ?>
    <style>
    .eventbox-title-ellipsis {
        text-overflow:ellipsis;
        overflow:hidden;
        white-space:nowrap;
        width:60%;
        display:inline-block;
    }
    </style>
    <ul class="list-unstyled">
        <?php foreach ($this->get('eventList') as $eventlist): ?>
            <?php $date = new \Ilch\Date($eventlist->getStart()); ?>
            <?php if (($this->getUser() && $this->getUser()->hasAccess('module_events')) || is_in_array($this->get('readAccess'), explode(',', $eventlist->getReadAccess()))): ?>
                <li>
                    <span class="eventbox-title-ellipsis">
                        <i class="fa-regular fa-calendar"></i>
                        <a href="<?=$this->getUrl('events/show/event/id/' . $eventlist->getId()) ?>">
                            <?=$this->escape($eventlist->getTitle()) ?>
                        </a>
                    </span>
                    <span class="small float-end"><?=$date->format('d.m.y', true) ?></span>
                </li>
            <?php endif; ?>
        <?php endforeach; ?>
    </ul>
<?php else: ?>
    <?=$this->getTrans('noEvent') ?>
<?php endif; ?>
<div align="center"><a href="<?=$this->getUrl('events/show/upcoming/') ?>"><?=$this->getTrans('otherEvents') ?></a></div>
