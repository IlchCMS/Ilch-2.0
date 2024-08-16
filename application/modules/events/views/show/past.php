<?php

/** @var \Ilch\View $this */

/** @var \Modules\Events\Mappers\Entrants $entrantsMapper */
$entrantsMapper = $this->get('entrantsMapper');
?>

<?php include APPLICATION_PATH . '/modules/events/views/index/navi.php'; ?>

<h1><?=$this->getTrans('menuEventPast') ?></h1>
<div class="row">
    <div class="col-xl-12">
        <ul class="event-list">
            <?php if ($this->get('eventListPasts') != '') : ?>
                <?php
                /** @var \Modules\Events\Models\Events $event */
                foreach ($this->get('eventListPasts') as $event) : ?>
                    <?php $eventEntrants = $entrantsMapper->getEventEntrantsById($event->getId()) ?>
                    <?php $date = new \Ilch\Date($event->getStart()); ?>
                    <?php $agree = 0;
                    $maybe = 0; ?>
                    <?php if (($this->getUser() && $this->getUser()->hasAccess('module_events')) || is_in_array($this->get('readAccess'), explode(',', $event->getReadAccess()))) : ?>
                        <li>
                            <time>
                                <span class="day"><?=$date->format('j') ?></span>
                                <span class="month"><?=$this->getTrans($date->format('M')) ?></span>
                            </time>
                            <div class="info">
                                <h2 class="title"><a href="<?=$this->getUrl('events/show/event/id/' . $event->getId()) ?>"><?=$this->escape($event->getTitle()) ?></a></h2>
                                <p class="desc">
                                    <?php $place = explode(', ', $this->escape($event->getPlace()), 2); ?>
                                    <?=$place[0] ?>
                                    <?php if (!empty($place[1])) : ?>
                                        <br /><span class="text-muted"><?=$place[1] ?></span>
                                    <?php endif; ?>
                                    <?php if (!empty($event->getType())) : ?>
                                        <br><?=$this->escape($event->getType()) ?>
                                    <?php endif; ?>
                                </p>
                                <?php if ($eventEntrants != '') : ?>
                                    <?php foreach ($eventEntrants as $eventEntrantsUser) : ?>
                                        <?php if ($eventEntrantsUser->getStatus() == 1) : ?>
                                            <?php $agree++; ?>
                                        <?php elseif ($eventEntrantsUser->getStatus() == 2) : ?>
                                            <?php $maybe++; ?>
                                        <?php endif; ?>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                                <ul>
                                    <?php if ($event->getUserLimit() > 0) : ?>
                                        <li class="col-3"><?=$this->getTrans('guest') ?></li>
                                        <li class="col-3"><?=$agree ?> <i class="fa-solid fa-check"></i></li>
                                        <li class="col-3"><?=$maybe ?> <i class="fa-solid fa-question"></i></li>
                                        <li class="col-3"><?=$event->getUserLimit() ?> <i class="fa-solid fa-users"></i></li>
                                    <?php else : ?>
                                        <li class="col-4"><?=$this->getTrans('guest') ?></li>
                                        <li class="col-4"><?=$agree ?> <i class="fa-solid fa-check"></i></li>
                                        <li class="col-4"><?=$maybe ?> <i class="fa-solid fa-question"></i></li>
                                    <?php endif; ?>
                                </ul>
                            </div>
                        </li>
                    <?php endif; ?>
                <?php endforeach; ?>
            <?php else : ?>
                <?=$this->getTrans('noEvent') ?>
            <?php endif; ?>
        </ul>
    </div>
</div>
