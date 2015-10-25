<?php 
$entrantsMapper = new Modules\Training\Mappers\Entrants();
?>

<legend><?=$this->getTrans('menuTraining') ?></legend>
<div class="table-responsive">
    <table class="table table-hover table-striped">
        <colgroup>
            <col class="col-lg-3">
            <col class="col-lg-5">
            <col class="col-lg-3">
            <col class="col-lg-1">
        </colgroup>
        <thead>
            <tr>
                <th><?=$this->getTrans('dateTime') ?></th>
                <th><?=$this->getTrans('title') ?></th>
                <th><?=$this->getTrans('place') ?></th>
                <th><?=$this->getTrans('entrant') ?></th>
            </tr>
        </thead>
        <tbody>
            <?php if ($this->get('training') != ''): ?>
                <?php foreach ($this->get('training') as $training): ?>
                    <tr>
                        <td><?=date('d.m.Y', strtotime($training->getDate())) ?> <?=$this->getTrans('at') ?> <?=date('H:i', strtotime($training->getDate())) ?> <?=$this->getTrans('clock') ?></td>    
                        <td><a href="<?=$this->getUrl('training/index/show/id/' . $training->getId()) ?>"><?=$training->getTitle() ?></a></td>
                        <td><?=$training->getPlace() ?></td>
                        <td align="center"><?=count($entrantsMapper->getEntrantsById($training->getId())) ?></td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="4"><?=$this->getTrans('noTraining') ?></td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>
