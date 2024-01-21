<?php

/** @var \Ilch\View $this */

/** @var \Modules\Training\Mappers\Entrants $entrantsMapper */
$entrantsMapper = $this->get('entrantsMapper');
/** @var \Modules\Training\Models\Training[]|null $training */
$training = $this->get('training');
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
            <?php if ($training) : ?>
                <?php foreach ($training as $model) : ?>
                    <tr>
                        <td><?=date('d.m.Y', strtotime($model->getDate())) ?> <?=$this->getTrans('at') ?> <?=date('H:i', strtotime($model->getDate())) ?> <?=$this->getTrans('clock') ?></td>
                        <td><a href="<?=$this->getUrl('training/index/show/id/' . $model->getId()) ?>"><?=$this->escape($model->getTitle()) ?></a></td>
                        <td><?=$this->escape($model->getPlace()) ?></td>
                        <td class="text-center"><?=count($entrantsMapper->getEntrantsById($model->getId())) ?></td>
                    </tr>
                <?php endforeach; ?>
            <?php else : ?>
                <tr>
                    <td colspan="4"><?=$this->getTrans('noTraining') ?></td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>
