<?php
$userMapper = $this->get('userMapper');
$teamsMapper = $this->get('teamsMapper');
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
        <?php foreach ($this->get('awards') as $awards): ?>
            <div class="col-lg-6">
                <div class="award">
                    <div class="rank" align="center">
                        <?php if ($awards->getRank() == 1): ?>
                            <i class="fa fa-trophy img_gold" title="<?=$this->getTrans('rank') ?> <?=$awards->getRank() ?>"></i>
                        <?php elseif ($awards->getRank() == 2): ?>
                            <i class="fa fa-trophy img_silver" title="<?=$this->getTrans('rank') ?> <?=$awards->getRank() ?>"></i>
                        <?php elseif ($awards->getRank() == 3): ?>
                            <i class="fa fa-trophy img_bronze" title="<?=$this->getTrans('rank') ?> <?=$awards->getRank() ?>"></i>
                        <?php else: ?>
                            <span title="<?=$this->getTrans('rank') ?> <?=$awards->getRank() ?>"><?=$awards->getRank() ?></span>
                        <?php endif; ?>
                    </div>
                    <div class="rank_info">
                        <?php if ($awards->getTyp() == 2): ?>
                            <?php $team = $teamsMapper->getTeamById($awards->getUTId()); ?>
                            <?php if ($team) : ?>
                                <a href="<?=$this->getUrl('teams/index/index') ?>" target="_blank"><?=$this->escape($team->getName()) ?></a>
                            <?php else: ?>
                                <?=$this->getTrans('formerTeam') ?>
                            <?php endif; ?>
                        <?php else: ?>
                            <?php $user = $userMapper->getUserById($awards->getUTId()); ?>
                            <?php if ($user) : ?>
                                <a href="<?=$this->getUrl('user/profil/index/user/'.$user->getId()) ?>"><?=$this->escape($user->getName()) ?></a>
                            <?php else: ?>
                                <?=$this->getTrans('formerUser') ?>
                            <?php endif; ?>
                        <?php endif; ?>
                        <br />
                        <?=date('d.m.Y', strtotime($awards->getDate())) ?><br />

                        <?php if ($awards->getEvent() != ''): ?>
                            <?php if ($awards->getURL() != ''): ?>
                                <a href="<?=$this->escape($awards->getURL()) ?>" title="<?=$this->escape($awards->getEvent()) ?>" target="_blank" rel="noopener"><?=$this->escape($awards->getEvent()) ?></a>
                            <?php else: ?>
                                <?=$this->escape($awards->getEvent()) ?>
                            <?php endif; ?>
                        <?php else: ?>
                            <br />
                        <?php endif; ?>
                    </div>
                    <?php if ($awards->getImage() != ''): ?>
                        <div class="rank_image">
                            <img src="<?=(strncmp($awards->getImage(), 'application', 11) === 0) ? $this->getBaseUrl($awards->getImage()) : $this->escape($awards->getImage()) ?>" alt="<?=$this->getTrans('rank') ?> <?=$awards->getRank() ?>" title="<?=$this->getTrans('rank') ?> <?=$awards->getRank() ?>" />
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
<?php else: ?>
    <?=$this->getTrans('noAwards') ?>
<?php endif; ?>
