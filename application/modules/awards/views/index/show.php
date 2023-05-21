<?php
$award = $this->get('award');
$teams = $this->get('teams');
$users = $this->get('users');
?>
<link href="<?=$this->getModuleUrl('static/css/awards.css') ?>" rel="stylesheet">

<h1><?=$this->getTrans('menuAwards') ?></h1>
<?php if ($this->get('award')): ?>
    <div class="row">
            <div class="rank_show">
                <?php if ($award->getRank() == 1): ?>
                    <i class="fa-solid fa-trophy img_gold fa-4x" title="<?=$this->getTrans('rank') ?> <?=$award->getRank() ?>"></i>
                <?php elseif ($award->getRank() == 2): ?>
                    <i class="fa-solid fa-trophy img_silver fa-4x" title="<?=$this->getTrans('rank') ?> <?=$award->getRank() ?>"></i>
                <?php elseif ($award->getRank() == 3): ?>
                    <i class="fa-solid fa-trophy img_bronze fa-4x" title="<?=$this->getTrans('rank') ?> <?=$award->getRank() ?>"></i>
                <?php else: ?>
                    <span title="<?=$this->getTrans('rank') ?> <?=$award->getRank() ?>"><?=$award->getRank() ?></span>
                <?php endif; ?>
            </div>
            <div class="rank_info_show">
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
    <br>
    <p><?=$this->getTrans('recipientsOfAward') ?>:</p>
    <div class="award_recipients_show">
    <?php foreach($award->getRecipients() as $recipient) : ?>
        <?php if ($recipient->getTyp() == 2): ?>
            <?php foreach($teams as $team) : ?>
                <?php if ($team->getId() === $recipient->getUtId()) : ?>
                    <a href="<?=$this->getUrl('teams/index/index') ?>" target="_blank"><?=$this->escape($team->getName()) ?></a>
                    <?php break; ?>
                <?php endif; ?>
            <?php endforeach; ?>
        <?php else: ?>
            <?php foreach($users as $user) : ?>
                <?php if ($user->getId() === $recipient->getUtId()) : ?>
                    <a href="<?=$this->getUrl('user/profil/index/user/'.$user->getId()) ?>"><?=$this->escape($user->getName()) ?></a>
                    <?php break; ?>
                <?php endif; ?>
            <?php endforeach; ?>
        <?php endif; ?>
    <?php endforeach; ?>
    </div>
<?php else: ?>
    <?=$this->getTrans('awardNotFound') ?>
<?php endif; ?>
