<?php $entrantsMapper = $this->get('entrantsMapper');
$adminAccess = null;
if ($this->getUser()) {
    $adminAccess = $this->getUser()->isAdmin();
}
$displayedTrainings = 0;
?>

<h1><?=$this->getTrans('menuTraining') ?></h1>
<div class="table-responsive">
    <table class="table table-hover table-striped">
        <colgroup>
            <col class="col-xl-3">
            <col>
            <col class="col-xl-3">
            <col class="col-xl-1">
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
                    <?php
                    if (!is_in_array($this->get('readAccess'), explode(',', $training->getReadAccess())) && $adminAccess == false) {
                        continue;
                    }
                    $displayedTrainings++;
                    ?>
                    <tr>
                        <td><?=date('d.m.Y', strtotime($training->getDate())) ?> <?=$this->getTrans('at') ?> <?=date('H:i', strtotime($training->getDate())) ?> <?=$this->getTrans('clock') ?></td>
                        <td><a href="<?=$this->getUrl('training/index/show/id/' . $training->getId()) ?>"><?=$this->escape($training->getTitle()) ?></a></td>
                        <td><?=$this->escape($training->getPlace()) ?></td>
                        <td align="center"><?=count($entrantsMapper->getEntrantsById($training->getId())) ?></td>
                    </tr>
                <?php endforeach; ?>
                <?php if ($displayedTrainings == 0) : ?>
                    <tr>
                        <td colspan="4"><?=$this->getTrans('noTraining') ?></td>
                    </tr>
                <?php endif; ?>
            <?php else: ?>
                <tr>
                    <td colspan="4"><?=$this->getTrans('noTraining') ?></td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>
