<?php
$date = new \Ilch\Date();
?>
<div class="table-responsive">
    <?php include APPLICATION_PATH.'/modules/events/views/index/navi.php'; ?>
    <table class="table table-striped table-responsive">
        <tr>
            <th colspan="2"><?=$this->getTrans('menuEventParticipation') ?></th>
        </tr>
        <?php if ($this->get('eventList') != ''): ?>
            <?php foreach ($this->get('eventList') as $eventlist): ?>
                <?php $date = new \Ilch\Date($eventlist->getDateCreated()); ?>
                <tr>
                    <td class="col-lg-3">
                        <?php if ($this->escape($eventlist->getImage()) != ''): ?>
                            <img src="<?=$this->getBaseUrl().$this->escape($eventlist->getImage()) ?>">
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
