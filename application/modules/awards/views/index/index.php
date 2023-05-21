<?php
$teams = $this->get('teams');
$users = $this->get('users');
$recipientToDisplayLimit = 6;
?>

<link href="<?=$this->getModuleUrl('static/css/awards.css') ?>" rel="stylesheet">

<h1><?=$this->getTrans('menuAwards') ?></h1>
<?php if ($this->get('awards')): ?>
    <div class="row">
        <div class="col-lg-12">
            <div class="award">
                <div class="awards_info">
                    <?=$this->getTrans('currentlyThereAre') ?> <b><?=$this->get('awardsCount') ?></b> <?=$this->getTrans('awards') ?>.
                </div>
            </div>
        </div>
        <?php foreach ($this->get('awards') as $award): ?>
            <div class="col-lg-6">
                <div class="award">
                    <div class="rank" align="center">
                        <a href="<?=$this->getUrl(['action' => 'show', 'id' => $award->getId()]) ?>">
                        <?php if ($award->getRank() == 1): ?>
                            <i class="fa-solid fa-trophy img_gold" title="<?=$this->getTrans('rank') ?> <?=$award->getRank() ?>"></i>
                        <?php elseif ($award->getRank() == 2): ?>
                            <i class="fa-solid fa-trophy img_silver" title="<?=$this->getTrans('rank') ?> <?=$award->getRank() ?>"></i>
                        <?php elseif ($award->getRank() == 3): ?>
                            <i class="fa-solid fa-trophy img_bronze" title="<?=$this->getTrans('rank') ?> <?=$award->getRank() ?>"></i>
                        <?php else: ?>
                            <span title="<?=$this->getTrans('rank') ?> <?=$award->getRank() ?>"><?=$award->getRank() ?></span>
                        <?php endif; ?>
                        </a>
                    </div>
                    <div class="rank_info">
                        <?php $recipientsDisplayed = 0 ?>
                        <?php foreach($award->getRecipients() as $recipient) : ?>
                            <?php if ($recipientsDisplayed >= $recipientToDisplayLimit): ?>
                                <a href="<?=$this->getUrl(['action' => 'show', 'id' => $award->getId()]) ?>">...</a>
                                <?php break; ?>
                            <?php endif; ?>
                            <?php if ($recipient->getTyp() == 2): ?>
                                <?php foreach($teams[$award->getId()] as $team) : ?>
                                    <?php if ($team->getId() === $recipient->getUtId()) : ?>
                                        <a href="<?=$this->getUrl('teams/index/index') ?>" target="_blank"><?=$this->escape($team->getName()) ?></a>
                                        <?php $recipientsDisplayed++ ?>
                                        <?php break; ?>
                                    <?php endif; ?>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <?php foreach($users[$award->getId()] as $user) : ?>
                                    <?php if ($user->getId() === $recipient->getUtId()) : ?>
                                        <a href="<?=$this->getUrl('user/profil/index/user/'.$user->getId()) ?>"><?=$this->escape($user->getName()) ?></a>
                                        <?php $recipientsDisplayed++ ?>
                                        <?php break; ?>
                                    <?php endif; ?>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        <?php endforeach; ?>
                        <?php if ($recipientsDisplayed === 0) : ?>
                            <?=$this->getTrans('formerUsersOrTeams') ?>
                        <?php endif; ?>
                        <br />
                        <?=date('d.m.Y', strtotime($award->getDate())) ?><br />

                        <?php if ($award->getEvent() != ''): ?>
                            <?php if ($award->getURL() != ''): ?>
                                <a href="<?=$this->escape($award->getURL()) ?>" title="<?=$this->escape($award->getEvent()) ?>" target="_blank" rel="noopener"><?=$this->escape($award->getEvent()) ?></a>
                            <?php else: ?>
                                <?=$this->escape($award->getEvent()) ?>
                            <?php endif; ?>
                        <?php else: ?>
                            <br />
                        <?php endif; ?>
                    </div>
                    <?php if ($award->getImage() != ''): ?>
                        <div class="rank_image">
                            <img src="<?=(strncmp($award->getImage(), 'application', 11) === 0) ? $this->getBaseUrl($award->getImage()) : $this->escape($award->getImage()) ?>" alt="<?=$this->getTrans('rank') ?> <?=$award->getRank() ?>" title="<?=$this->getTrans('rank') ?> <?=$award->getRank() ?>" />
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
<?php else: ?>
    <?=$this->getTrans('noAwards') ?>
<?php endif; ?>
