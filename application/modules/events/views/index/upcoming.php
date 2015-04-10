<?php
$date = new \Ilch\Date();
?>
<div class="table-responsive">
    <table class="table table-striped table-responsive">
        <tr align="center">
            <td width="20%"><a class="list-group-item" href="<?=$this->getUrl(array('action' => 'upcoming')); ?>"><i class="fa fa-history fa-flip-horizontal"></i>&nbsp; <?=$this->getTrans('naviEventsUpcoming') ?></a></td>
            <td width="20%"><a class="list-group-item" href="<?=$this->getUrl(array('action' => 'all')); ?>"><i class="fa fa-list-ul"></i>&nbsp; <?=$this->getTrans('naviEventsAll') ?></a></td>
            <td width="20%"><a class="list-group-item" href="<?=$this->getUrl(array('action' => 'past')); ?>"><i class="fa fa-history"></i>&nbsp; <?=$this->getTrans('naviEventsPast') ?></a></td>
            <td width="20%"><a class="list-group-item" href="<?=$this->getUrl(array('action' => 'treat')); ?>"><i class="fa fa-plus"></i>&nbsp; <?=$this->getTrans('naviEventsAdd') ?></a></td>
            <td width="20%"><a class="list-group-item" href="<?=$this->getUrl(array('action' => 'my')); ?>"><i class="fa fa-cogs"></i>&nbsp; <?=$this->getTrans('naviEventsMy') ?></a></td>
        </tr>
    </table>
    <table class="table table-striped table-responsive">
        <tr>
            <th colspan="2"><?=$this->getTrans('menuEventUpcoming') ?></th>
        </tr>
        <?php if ($this->get('eventListUpcoming') != ''): ?>
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
        <?php else: ?>
            <tr>
                <td colspan="2"><?=$this->getTrans('noEvent') ?></td>
            </tr>    
        <?php endif; ?>
    </table>
</div>
