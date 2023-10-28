<?php

/** @var \Ilch\View $this */

/** @var \Modules\Teams\Mappers\Teams $teamsMapper */
$teamsMapper = $this->get('teamsMapper');

/** @var \Modules\Teams\Models\Joins[]|null $joins */
$joins = $this->get('joins');
?>
<h1><?=$this->getTrans('manage') ?></h1>
<?php if ($joins) : ?>
    <div class="table-responsive">
        <table class="table table-hover table-striped">
            <colgroup>
                <col class="col-xl-3" />
                <col />
            </colgroup>
            <thead>
                <tr>
                    <th><?=$this->getTrans('name') ?></th>
                    <th><?=$this->getTrans('team') ?></th>
                    <th><?=$this->getTrans('dateTime') ?></th>
                </tr>
            </thead>
            <tbody>
            <?php
            /** @var \Modules\Teams\Models\Joins $join */
            ?>
                <?php foreach ($joins as $join) : ?>
                    <?php $team = $teamsMapper->getTeamById($join->getTeamId()); ?>
                    <?php $date = new Ilch\Date($join->getDateCreated()); ?>
                    <tr>
                        <td><a href="<?=$this->getUrl(['action' => 'show', 'id' => $join->getId()]) ?>" title="<?=$this->getTrans('show') ?>"><?=$this->escape($join->getName()) ?></a></td>
                        <td><?=$this->escape($team->getName()) ?></td>
                        <td><?=$date->format('d.m.Y H:i', true) ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
<?php else : ?>
    <?=$this->getTrans('noApplications') ?>
<?php endif; ?>
