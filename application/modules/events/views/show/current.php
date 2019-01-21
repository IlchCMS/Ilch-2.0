<?php
$entrantsMapper = $this->get('entrantsMapper');
?>

<?php include APPLICATION_PATH.'/modules/events/views/index/navi.php'; ?>

<h1><?=$this->getTrans('menuEventCurrent') ?></h1>
<div class="row">
    <div class="col-lg-12">
        <ul class="event-list">
            <?php if ($this->get('eventListCurrent') != ''): ?>
                <?php foreach ($this->get('eventListCurrent') as $eventlist): ?>
                    <?php $eventEntrants = $entrantsMapper->getEventEntrantsById($eventlist->getId()) ?>
                    <?php $date = new \Ilch\Date($eventlist->getStart()); ?>
                    <?php $agree = 0; $maybe = 0; ?>
                    <?php if (is_in_array($this->get('readAccess'), explode(',', $eventlist->getReadAccess())) OR $this->getUser() AND $this->getUser()->hasAccess('module_events')): ?>
                        <li>
                            <time>
                                <span class="day"><?=$date->format("j") ?></span>
                                <span class="month"><?=$this->getTrans($date->format('M')) ?></span>
                            </time>
                            <div class="info">
                                <h2 class="title"><a href="<?=$this->getUrl('events/show/event/id/' . $eventlist->getId()) ?>"><?=$this->escape($eventlist->getTitle()) ?></a></h2>
                                <p class="desc">
                                    <?php $place = explode(', ', $this->escape($eventlist->getPlace()), 2); ?>
                                    <?=$place[0] ?>
                                    <?php if (!empty($place[1])): ?>
                                        <br /><span class="text-muted"><?=$place[1] ?></span>
                                    <?php endif; ?>
                                </p>
                                <?php if ($eventEntrants != ''): ?>
                                    <?php foreach ($eventEntrants as $eventEntrantsUser): ?>
                                        <?php if ($eventEntrantsUser->getStatus() == 1): ?>
                                            <?php $agree++; ?>
                                        <?php elseif ($eventEntrantsUser->getStatus() == 2): ?>
                                            <?php $maybe++; ?>
                                        <?php endif; ?>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                                <ul>
                                    <?php if ($eventlist->getUserLimit() > 0): ?>
                                        <li style="width:25%;"><?=$this->getTrans('guest') ?></li>
                                        <li style="width:25%;"><?=$agree ?> <i class="fa fa-check"></i></li>
                                        <li style="width:25%;"><?=$maybe ?> <i class="fa fa-question"></i></li>
                                        <li style="width:25%;"><?=$eventlist->getUserLimit() ?> <i class="fa fa-users"></i></li>
                                    <?php else: ?>
                                        <li style="width:33%;"><?=$this->getTrans('guest') ?></li>
                                        <li style="width:33%;"><?=$agree ?> <i class="fa fa-check"></i></li>
                                        <li style="width:33%;"><?=$maybe ?> <i class="fa fa-question"></i></li>
                                    <?php endif; ?>
                                </ul>
                            </div>
                        </li>
                    <?php endif; ?>
                <?php endforeach; ?>
            <?php else: ?>
                <?=$this->getTrans('noEvent') ?>
            <?php endif; ?>
        </ul>
    </div>
</div>
