<?php

/** @var \Ilch\View $this */

/** @var \Modules\Awards\Models\Awards[] $awards */
$awards = $this->get('awards');

/** @var \Modules\Teams\Models\Teams[] $teams */
$teams = $this->get('teams');
/** @var \Modules\Users\Models\User[] $users */
$users = $this->get('users');
$recipientToDisplayLimit = 6;
?>

<h1><?=$this->getTrans('manage') ?></h1>
<?php if (!empty($awards)) : ?>
    <form method="POST" action="">
        <?=$this->getTokenField() ?>
        <div class="table-responsive">
            <table class="table table-hover table-striped">
                <colgroup>
                    <col class="icon_width">
                    <col class="icon_width">
                    <col class="icon_width">
                    <col class="col-xl-1">
                    <col class="col-xl-1">
                    <col class="col-xl-3">
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
                    <?php foreach ($awards as $award) :
                        $getDate = new \Ilch\Date($award->getDate()); ?>
                        <tr>
                            <td><?=$this->getDeleteCheckbox('check_entries', $award->getId()) ?></td>
                            <td><?=$this->getEditIcon(['action' => 'treat', 'id' => $award->getId()]) ?></td>
                            <td><?=$this->getDeleteIcon(['action' => 'del', 'id' => $award->getId()]) ?></td>
                            <td><?=$getDate->format('d.m.Y', true) ?></td>
                            <td><?=$this->escape($award->getRank()) ?></td>
                            <td>
                            <?php $recipientsDisplayed = 0 ?>
                            <?php foreach ($award->getRecipients() as $recipient) : ?>
                                <?php if ($recipientsDisplayed >= $recipientToDisplayLimit) : ?>
                                    <a href="<?=$this->getUrl(['action' => 'show', 'id' => $award->getId()]) ?>">...</a>
                                    <?php break; ?>
                                <?php endif; ?>
                                <?php if ($recipient->getTyp() == 2) : ?>
                                    <?php foreach ($teams[$award->getId()] as $team) : ?>
                                        <?php if ($team->getId() === $recipient->getUtId()) : ?>
                                            <i class="fa-solid fa-users"></i> <a href="<?=$this->getUrl('teams/index/index') ?>" target="_blank"><?=$this->escape($team->getName()) ?></a>
                                            <?php $recipientsDisplayed++ ?>
                                            <?php break; ?>
                                        <?php endif; ?>
                                    <?php endforeach; ?>
                                <?php else : ?>
                                    <?php foreach ($users[$award->getId()] as $user) : ?>
                                        <?php if ($user->getId() === $recipient->getUtId()) : ?>
                                            <i class="fa-solid fa-user"></i> <a href="<?=$this->getUrl('user/profil/index/user/' . $user->getId()) ?>" target="_blank"><?=$this->escape($user->getName()) ?></a>
                                            <?php $recipientsDisplayed++ ?>
                                            <?php break; ?>
                                        <?php endif; ?>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            <?php endforeach; ?>
                            <?php if ($recipientsDisplayed === 0) : ?>
                                <?=$this->getTrans('formerUsersOrTeams') ?>
                            <?php endif; ?>
                            </td>
                            <?php if ($award->getEvent() != '') : ?>
                                <?php if ($award->getURL() != '') : ?>
                                    <td><a href="<?=$this->escape($award->getURL()) ?>" title="<?=$this->escape($award->getEvent()) ?>" target="_blank" rel="noopener"><?=$this->escape($award->getEvent()) ?></a></td>
                                <?php else : ?>
                                    <td><?=$this->escape($award->getEvent()) ?></td>
                                <?php endif; ?>
                            <?php endif; ?>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        <?=$this->getListBar(['delete' => 'delete']) ?>
    </form>
<?php else : ?>
    <?=$this->getTrans('noAwards') ?>
<?php endif; ?>
