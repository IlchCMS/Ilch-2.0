<?php
$userMapper = $this->get('userMapper');
$teamsMapper = $this->get('teamsMapper');
?>

<h1><?=$this->getTrans('manage') ?></h1>
<?php if (!empty($this->get('awards'))): ?>
    <form class="form-horizontal" method="POST" action="">
        <?=$this->getTokenField() ?>
        <div class="table-responsive">
            <table class="table table-hover table-striped">
                <colgroup>
                    <col class="icon_width">
                    <col class="icon_width">
                    <col class="icon_width">
                    <col class="col-lg-1">
                    <col class="col-lg-1">
                    <col class="col-lg-2">
                    <col>
                </colgroup>
                <thead>
                    <tr>
                        <th><?=$this->getCheckAllCheckbox('check_entries') ?></th>
                        <th></th>
                        <th></th>
                        <th><?=$this->getTrans('date') ?></th>
                        <th><?=$this->getTrans('rank') ?></th>
                        <th><?=$this->getTrans('userTeam') ?></th>
                        <th><?=$this->getTrans('event') ?></th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($this->get('awards') as $awards) : ?>
                    <?php $getDate = new \Ilch\Date($awards->getDate()); ?>
                        <tr>
                            <td><?=$this->getDeleteCheckbox('check_entries', $awards->getId()) ?></td>
                            <td><?=$this->getEditIcon(['action' => 'treat', 'id' => $awards->getId()]) ?></td>
                            <td><?=$this->getDeleteIcon(['action' => 'del', 'id' => $awards->getId()]) ?></td>
                            <td><?=$getDate->format('d.m.Y', true) ?></td>
                            <td><?=$this->escape($awards->getRank()) ?></td>
                            <?php if ($awards->getTyp() == 2): ?>
                                <?php $team = $teamsMapper->getTeamById($awards->getUTId()); ?>
                                <?php if ($team) : ?>
                                    <td><a href="<?=$this->getUrl('teams/index/index') ?>" target="_blank"><?=$this->escape($team->getName()) ?></a></td>
                                <?php else: ?>
                                    <td><?=$this->getTrans('formerTeam') ?></td>
                                <?php endif; ?>
                            <?php else: ?>
                                <?php $user = $userMapper->getUserById($awards->getUTId()); ?>
                                <?php if ($user) : ?>
                                    <td><a href="<?=$this->getUrl('user/profil/index/user/'.$user->getId()) ?>" target="_blank"><?=$this->escape($user->getName()) ?></a></td>
                                <?php else: ?>
                                    <td><?=$this->getTrans('formerUser') ?></td>
                                <?php endif; ?>
                            <?php endif; ?>

                            <?php if ($awards->getEvent() != ''): ?>
                                <?php if ($awards->getURL() != ''): ?>
                                    <td><a href="<?=$this->escape($awards->getURL()) ?>" title="<?=$this->escape($awards->getEvent()) ?>" target="_blank" rel="noopener"><?=$this->escape($awards->getEvent()) ?></a></td>
                                <?php else: ?>
                                    <td><?=$this->escape($awards->getEvent()) ?></td>
                                <?php endif; ?>
                            <?php endif; ?>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        <?=$this->getListBar(['delete' => 'delete']) ?>
    </form>
<?php else: ?>
    <?=$this->getTrans('noAwards') ?>
<?php endif; ?>