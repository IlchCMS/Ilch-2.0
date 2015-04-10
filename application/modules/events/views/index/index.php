<?php
$date = new \Ilch\Date();
?>
<div class="table-responsive">
    <table class="table table-striped table-responsive">
        <tr align="center">
            <td width="20%"><a class="list-group-item" href="#"><i class="fa fa-history fa-flip-horizontal"></i>&nbsp; Bevorstehende</a></td>
            <td width="20%"><a class="list-group-item" href="#"><i class="fa fa-list-ul"></i>&nbsp; Alle</a></td>
            <td width="20%"><a class="list-group-item" href="#"><i class="fa fa-history"></i>&nbsp; Vergangene</a></td>
            <td width="20%"><a class="list-group-item" href="<?=$this->getUrl(array('action' => 'treat')); ?>"><i class="fa fa-plus"></i>&nbsp; Erstellen</a></td>
            <td width="20%"><a class="list-group-item" href="#"><i class="fa fa-cogs"></i>&nbsp; Meine</a></td>
        </tr>
    </table>
    <?php if ($this->get('eventListUpcoming') != ''): ?>
        <table class="table table-striped table-responsive">
            <tr>
                <th colspan="2"><?=$this->getTrans('menuEventUpcoming') ?></th>
            </tr>
            <?php foreach ($this->get('eventListUpcoming') as $eventlist): ?>
                <?php $date = new \Ilch\Date($eventlist->getDateCreated()); ?>
                <tr>
                    <td width="167">
                        <?php if ($this->escape($eventlist->getImage()) != ''): ?>
                        <img src="<?=$this->escape($eventlist->getImage()) ?>">
                        <?php else: ?>
                            <img src="<?=$this->getModuleUrl('static/img/450x150.jpg') ?>">
                        <?php endif; ?>
                    </td>
                    <td>
                        <form class="form-horizontal" action="<?=$this->getUrl(array('action' => $this->getRequest()->getActionName())); ?>" method="post">
                            <?=$this->getTokenField(); ?>
                            <div  style="margin-top: -3px;">
                                <a href="<?=$this->getUrl('events/index/show/id/' . $eventlist->getId()) ?>"><b><?=$this->escape($eventlist->getTitle()) ?></a>
                                <div class="small">
                                    <?=$date->format("l, d. F Y", true) ?> <?=$this->getTrans('at') ?> <?=$date->format("H:i", true) ?><br />
                                    <?=$this->escape($eventlist->getPlace()) ?><br />
                                    <?php $eventsMappers = new Modules\Events\Mappers\Events(); ?>
                                    <?=count($eventsMappers->getEventEntrants($eventlist->getId()))+1 ?> <?= $this->getTrans('guest') ?>
                                </div>
                                <!--
                                <?php if ($this->getUser()): ?>
                                    <input type="hidden" name="id" value="<?= $this->escape($eventlist->getId()) ?>">
                                    <button type="submit" value="1" name="save" class="btn btn-sm btn-success">
                                        <?=$this->getTrans('join') ?>
                                    </button>
                                    <button type="submit" value="2" name="save" class="btn btn-sm btn-warning">
                                        <?=$this->getTrans('maybe') ?>
                                    </button>
                                    <button type="submit" value="delete" name="delete" class="btn btn-sm btn-danger">
                                        <?=$this->getTrans('decline') ?>
                                    </button>
                                <?php endif; ?>
                                -->
                            </div>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
        </table>
    <?php endif; ?>

    <!--
    <?php $MyEventsCount = 0; ?>
    <?php if ($this->get('eventList') != ''): ?>
        <?php foreach ($this->get('eventList') as $eventlist): ?>
            <?php if ($eventlist->getUserId() == $this->getUser()->getId()): ?>
                <?php $MyEventsCount++; ?>
            <?php endif; ?>
        <?php endforeach; ?>
    <?php endif; ?>
    <?php if ($MyEventsCount != ''): ?>
        <table class="table table-striped table-responsive">
            <tr>
                <th colspan="2"><?=$this->getTrans('menuEventHost') ?></th>
            </tr>
            <?php foreach ($this->get('eventList') as $eventlist): ?>
                <?php if ($eventlist->getUserId() == $this->getUser()->getId()): ?>
                    <?php $date = new \Ilch\Date($eventlist->getDateCreated()); ?>
                    <tr>
                        <td width="167"><img src="<?=$this->escape($eventlist->getImage()) ?>"></td>
                        <td>
                            <form class="form-horizontal" action="<?=$this->getUrl(array('action' => $this->getRequest()->getActionName())); ?>" method="post">
                                <?=$this->getTokenField(); ?>
                                <div  style="margin-top: -3px;">
                                    <a href="<?=$this->getUrl('events/index/show/id/' . $eventlist->getId()) ?>"><b><?=$this->escape($eventlist->getTitle()) ?></a>
                                    <div class="small">
                                        <?=$date->format("l, d. F Y", true) ?> <?=$this->getTrans('at') ?> <?=$date->format("H:i", true) ?><br />
                                        <?=$this->escape($eventlist->getPlace()) ?><br />
                                        <?php $eventsMappers = new Modules\Events\Mappers\Events(); ?>
                                        <?=count($eventsMappers->getEventEntrants($eventlist->getId()))+1 ?> <?= $this->getTrans('guest') ?>
                                    </div>
                                </div>
                            </form>
                        </td>
                    </tr>
                <?php endif; ?>
            <?php endforeach; ?>
        </table>
    <?php endif; ?>
    -->

    <table class="table table-striped table-responsive">
        <tr>
            <th colspan="2">
                <?php if ($this->get('eventListUpcoming') != ''): ?>
                    <?=$this->getTrans('menuEventOtherList') ?>
                <?php else: ?>
                    <?=$this->getTrans('menuEventList') ?>
                <?php endif; ?>
            </th>
        </tr>
        <?php if ($this->get('eventList') != ''): ?>
        <?php $x = 2; ?>
            <?php foreach ($this->get('eventList') as $eventlist): ?>
                <?php if ($this->escape(date('n', strtotime("+$x months", strtotime($date->format('Y-m-d'))))) == $this->escape(date('n', strtotime($eventlist->getDateCreated()))) AND $this->escape(date('n-d', strtotime($eventlist->getDateCreated()))) > $this->escape(date('n-d', strtotime($date->format('Y-m-d'))))): ?>
                    <?php $date = new \Ilch\Date($eventlist->getDateCreated()); ?>
                    <tr>
                        <td width="167">
                            <?php if ($this->escape($eventlist->getImage()) != ''): ?>
                            <img src="<?=$this->escape($eventlist->getImage()) ?>">
                            <?php else: ?>
                                <img src="<?=$this->getModuleUrl('static/img/450x150.jpg') ?>">
                            <?php endif; ?>
                        </td>
                        <td>
                            <form class="form-horizontal" action="<?=$this->getUrl(array('action' => $this->getRequest()->getActionName())); ?>" method="post">
                                <?=$this->getTokenField(); ?>
                                <div  style="margin-top: -3px;">
                                    <a href="<?=$this->getUrl('events/index/show/id/' . $eventlist->getId()) ?>"><b><?=$this->escape($eventlist->getTitle()) ?></a>
                                    <div class="small">
                                        <?=$date->format("l, d. F Y", true) ?> <?=$this->getTrans('at') ?> <?=$date->format("H:i", true) ?><br />
                                        <?=$this->escape($eventlist->getPlace()) ?><br />
                                        <?php $eventsMappers = new Modules\Events\Mappers\Events(); ?>
                                        <?=count($eventsMappers->getEventEntrants($eventlist->getId()))+1 ?> <?= $this->getTrans('guest') ?>
                                    </div>
                                    <!--
                                    <?php if ($this->getUser()): ?>
                                        <input type="hidden" name="id" value="<?= $this->escape($eventlist->getId()) ?>">
                                        <button type="submit" value="1" name="save" class="btn btn-sm btn-success">
                                            <?=$this->getTrans('join') ?>
                                        </button>
                                        <button type="submit" value="2" name="save" class="btn btn-sm btn-warning">
                                            <?=$this->getTrans('maybe') ?>
                                        </button>
                                        <button type="submit" value="delete" name="delete" class="btn btn-sm btn-danger">
                                            <?=$this->getTrans('decline') ?>
                                        </button>
                                    <?php endif; ?>
                                    -->
                                </div>
                            </form>
                        </td>
                    </tr>
                <?php else: ?>            
                    <tr>
                        <td>
                            <?=$this->getTrans('noEvent') ?>
                        </td>
                    </tr>
                <?php endif; ?>
            <?php endforeach; ?>
        <?php else: ?>            
            <tr>
                <td>
                    <?=$this->getTrans('noEvent') ?>
                </td>
            </tr>
        <?php endif; ?>
    </table>

    <?php if ($this->get('eventListPast') != ''): ?>
        <table class="table table-striped table-responsive">
            <tr>
                <th colspan="2"><?=$this->getTrans('menuEventPast') ?></th>
            </tr>
            <?php foreach ($this->get('eventListPast') as $eventlist): ?>
                <?php $date = new \Ilch\Date($eventlist->getDateCreated()); ?>
                <tr>
                    <td width="167">
                        <?php if ($this->escape($eventlist->getImage()) != ''): ?>
                        <img src="<?=$this->escape($eventlist->getImage()) ?>">
                        <?php else: ?>
                            <img src="<?=$this->getModuleUrl('static/img/450x150.jpg') ?>">
                        <?php endif; ?>
                    </td>
                    <td>
                        <div  style="margin-top: -3px;">
                            <a href="<?=$this->getUrl('events/index/show/id/' . $eventlist->getId()) ?>"><b><?=$this->escape($eventlist->getTitle()) ?></a>
                            <div class="small">
                                <?=$date->format("l, d. F Y", true) ?> <?=$this->getTrans('at') ?> <?=$date->format("H:i", true) ?><br />
                                <?=$this->escape($eventlist->getPlace()) ?><br />
                                <?php $eventsMappers = new Modules\Events\Mappers\Events(); ?>
                                <?=count($eventsMappers->getEventEntrants($eventlist->getId()))+1 ?> <?= $this->getTrans('guest') ?>
                            </div>
                        </div>
                    </td>
                </tr>
            <?php endforeach; ?>
        </table>
    <?php endif; ?>
</div>
