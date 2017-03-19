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
                </tr>
            </thead>
            <tbody>
                <?php foreach ($this->get('joins') as $join): ?>
                    <?php $team = $teamsMapper->getTeamById($join->getTeamId()); ?>
                    <tr>
                        <td><a href="<?=$this->getUrl(['action' => 'show', 'id' => $join->getId()]) ?>" title="<?=$this->getTrans('show') ?>"><?=$this->escape($join->getName()) ?></a></td>
                        <td><?=$this->escape($team->getName()) ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
<?php else: ?>
    <?=$this->getTrans('noApplications') ?>
<?php endif; ?>
