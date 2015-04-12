<?php
$date = new \Ilch\Date();
?>
<div class="table-responsive">
    <?php include APPLICATION_PATH.'/modules/events/views/index/naviHead.php'; ?>
    <?php if ($this->get('eventListUpcoming') == '' AND $this->get('getEventList') == '' AND $this->get('getEventListOther') == '' AND $this->get('eventListPast') == ''): ?>
        <table class="table table-striped table-responsive">
            <tr>
                <th colspan="2"><?=$this->getTrans('menuEventList') ?></th>
            </tr>
            <tr>
                <td><?=$this->getTrans('noEvent') ?></td>
            </tr>
        </table>
    <?php endif; ?>

    <?php if ($this->get('eventListUpcoming') != ''): ?>
        <table class="table table-striped table-responsive">
            <tr>
                <th colspan="2"><?=$this->getTrans('menuEventUpcoming') ?></th>
            </tr>
            <?php foreach ($this->get('eventListUpcoming') as $eventlist): ?>
                <?php $date = new \Ilch\Date($eventlist->getDateCreated()); ?>
                <tr>
                    <td class="col-lg-3">
                        <?php if ($this->escape($eventlist->getImage()) != ''): ?>
                        <img src="<?=$this->escape($eventlist->getImage()) ?>">
                        <?php else: ?>
                            <img src="<?=$this->getModuleUrl('static/img/450x150.jpg') ?>">
                        <?php endif; ?>
                    </td>
                    <td class="col-lg-9">
                        <form class="form-horizontal" action="<?=$this->getUrl(array('action' => $this->getRequest()->getActionName())); ?>" method="post">
                            <?=$this->getTokenField(); ?>
                            <div  style="margin-top: -3px;">
                                <a href="<?=$this->getUrl('events/show/event/id/' . $eventlist->getId()) ?>"><b><?=$this->escape($eventlist->getTitle()) ?></a>
                                <div class="small">
                                    <?=$date->format("l, d. F Y", true) ?> <?=$this->getTrans('at') ?> <?=$date->format("H:i", true) ?><br />
                                    <?=$this->escape($eventlist->getPlace()) ?><br />
                                    <?php $entrantsMappers = new Modules\Events\Mappers\Entrants(); ?>
                                    <?=count($entrantsMappers->getEventEntrantsById($eventlist->getId()))+1 ?> <?= $this->getTrans('guest') ?>
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

    <?php if ($this->get('getEventListOther') != ''): ?>
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
            <?php foreach ($this->get('getEventListOther') as $eventlist): ?>
                <?php $date = new \Ilch\Date($eventlist->getDateCreated()); ?>
                <tr>
                    <td class="col-lg-3">
                        <?php if ($this->escape($eventlist->getImage()) != ''): ?>
                            <img src="<?=$this->escape($eventlist->getImage()) ?>">
                        <?php else: ?>
                            <img src="<?=$this->getModuleUrl('static/img/450x150.jpg') ?>">
                        <?php endif; ?>
                    </td>
                    <td class="col-lg-9">
                        <form class="form-horizontal" action="<?=$this->getUrl(array('action' => $this->getRequest()->getActionName())); ?>" method="post">
                            <?=$this->getTokenField(); ?>
                            <div  style="margin-top: -3px;">
                                <a href="<?=$this->getUrl('events/show/event/id/' . $eventlist->getId()) ?>"><b><?=$this->escape($eventlist->getTitle()) ?></a>
                                <div class="small">
                                    <?=$date->format("l, d. F Y", true) ?> <?=$this->getTrans('at') ?> <?=$date->format("H:i", true) ?><br />
                                    <?=$this->escape($eventlist->getPlace()) ?><br />
                                    <?php $entrantsMappers = new Modules\Events\Mappers\Entrants(); ?>
                                    <?=count($entrantsMappers->getEventEntrantsById($eventlist->getId()))+1 ?> <?= $this->getTrans('guest') ?>
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

    <?php if ($this->get('eventListPast') != ''): ?>
        <table class="table table-striped table-responsive">
            <tr>
                <th colspan="2"><?=$this->getTrans('menuEventPast') ?></th>
            </tr>
            <?php foreach ($this->get('eventListPast') as $eventlist): ?>
                <?php $date = new \Ilch\Date($eventlist->getDateCreated()); ?>
                <tr>
                    <td class="col-lg-3">
                        <?php if ($this->escape($eventlist->getImage()) != ''): ?>
                            <img src="<?=$this->escape($eventlist->getImage()) ?>">
                        <?php else: ?>
                            <img src="<?=$this->getModuleUrl('static/img/450x150.jpg') ?>">
                        <?php endif; ?>
                    </td>
                    <td class="col-lg-9">
                        <div  style="margin-top: -3px;">
                            <a href="<?=$this->getUrl('events/show/event/id/' . $eventlist->getId()) ?>"><b><?=$this->escape($eventlist->getTitle()) ?></a>
                            <div class="small">
                                <?=$date->format("l, d. F Y", true) ?> <?=$this->getTrans('at') ?> <?=$date->format("H:i", true) ?><br />
                                <?=$this->escape($eventlist->getPlace()) ?><br />
                                    <?php $entrantsMappers = new Modules\Events\Mappers\Entrants(); ?>
                                    <?=count($entrantsMappers->getEventEntrantsById($eventlist->getId()))+1 ?> <?= $this->getTrans('guest') ?>
                            </div>
                        </div>
                    </td>
                </tr>
            <?php endforeach; ?>
        </table>
    <?php endif; ?>
</div>
