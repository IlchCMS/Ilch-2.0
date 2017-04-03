<?php $teamsMapper = $this->get('teamsMapper'); ?>

<h1><?=$this->getTrans('manage') ?></h1>
<?php if ($this->get('joins')): ?>
    <div class="table-responsive">
        <table class="table table-hover table-striped">
            <colgroup>
                <col class="col-lg-3" />
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
                <?php foreach ($this->get('joins') as $join): ?>
                    <?php $team = $teamsMapper->getTeamByGroupId($join->getTeamId()); ?>
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
<?php else: ?>
    <?=$this->getTrans('noApplications') ?>
<?php endif; ?>
