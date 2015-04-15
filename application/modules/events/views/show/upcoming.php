<?php
$date = new \Ilch\Date();
?>
<div class="table-responsive">
    <?php include APPLICATION_PATH.'/modules/events/views/index/navi.php'; ?>
    <table class="table table-striped table-responsive">
        <tr>
            <th colspan="2"><?=$this->getTrans('menuEventUpcoming') ?></th>
        </tr>
        <?php if ($this->get('eventListUpcoming') != ''): ?>
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
        <?php else: ?>
            <tr>
                <td colspan="2"><?=$this->getTrans('noEvent') ?></td>
            </tr>    
        <?php endif; ?>
    </table>
</div>
