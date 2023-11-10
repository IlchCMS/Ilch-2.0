<link href="<?=$this->getModuleUrl('static/css/birthday.css') ?>" rel="stylesheet">

<?php
$date = new \Ilch\Date();
?>

<h1><?=$this->getTrans('menuBirthdayList') ?></h1>
<?php if ($this->get('birthdayListNOW') != ''): ?>
    <div class="card card-primary">
        <div class="card-header">
            <strong><?=$this->getTrans('birthdayToday') ?></strong>
            <span class="float-end">
                <strong><?=date('d.m.Y', strtotime($date->format('Y-m-d'))) ?></strong>
            </span>
        </div>
        <?php foreach ($this->get('birthdayListNOW') as $birthdaylist): ?>
            <div class="card-body row">
                <div class="col-xl-12">
                    <div class="row">
                        <div class="col-xl-2 confetti">
                            <a href="<?=$this->getUrl('user/profil/index/user/' . $birthdaylist->getId()) ?>">
                                <img class="img-thumbnail center-block" style="margin-bottom: 0px;" src="<?=$this->getStaticUrl() . '../' . $this->escape($birthdaylist->getAvatar()) ?>" title="<?=$this->escape($birthdaylist->getName()) ?>" width="69" height="69">
                            </a>
                        </div>
                        <div class="col-xl-10">
                            <a href="<?=$this->getUrl('user/profil/index/user/' . $birthdaylist->getId()) ?>"><strong><?=$this->escape($birthdaylist->getName()) ?></strong></a><br />
                            <?=$this->getTrans('will') ?> <?=floor(($date->format('Ymd') - str_replace("-", "", $this->escape($birthdaylist->getBirthday()))) / 10000) ?> <?=$this->getTrans('yearsOld') ?><br />
                            <?php if ($this->getUser() && $this->getUser()->getId() != $this->escape($birthdaylist->getID())): ?>
                                <a href="<?=$this->getUrl('user/panel/dialognew/id/' . $birthdaylist->getId()) ?>"><?=$this->getTrans('writeCongratulations') ?></a>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
    <br />
<?php endif; ?>

<?php if ($this->get('birthdayList') != ''): ?>
    <?php $monthsUserCount = 0; ?>
    <?php for ($x = 1; $x < 13; $x++): ?>
        <?php foreach ($this->get('birthdayList') as $birthdaylist): ?>
            <?php if ($birthdaylist->getBirthday() != '' && $this->escape(date('n', strtotime($birthdaylist->getBirthday()))) == $x): ?>
                <?php $monthsUserCount++; ?>
            <?php endif; ?>
        <?php endforeach; ?>

        <div class="card card-default">
            <div class="card-header">
                <?php $month = date('F', mktime(0, 0, 0, +$x, 1, 1)); ?>
                <strong><?=$this->getTrans($month) ?></strong>
                <span class="float-end">
                <strong><?=$monthsUserCount ?> <?=$this->getTrans('people') ?></strong>
            </span>
            </div>
            <div class="card-body">
                <?php if ($monthsUserCount != ''): ?>
                    <?php foreach ($this->get('birthdayList') as $birthdaylist): ?>
                        <?php if ($birthdaylist->getBirthday() != '' && $this->escape(date('n', strtotime($birthdaylist->getBirthday()))) == $x): ?>
                            <div style="padding: 0 2px 2px 0; float: left;">
                                <a href="<?=$this->getUrl('user/profil/index/user/' . $birthdaylist->getId()) ?>">
                                    <img class="thumbnail" style="margin-bottom: 0px;" src="<?=$this->getStaticUrl() . '../' . $this->escape($birthdaylist->getAvatar()) ?>" title="<?=$this->escape($birthdaylist->getName()) ?> (<?=date("d.m", strtotime($this->escape($birthdaylist->getBirthday()))) ?>)" width="69" height="69">
                                </a>
                            </div>
                        <?php endif; ?>
                    <?php endforeach; ?>
                <?php else: ?>
                    <?=$this->getTrans('noBirthdayMonth') ?>
                <?php endif; ?>
            </div>
        </div>
        <?php $monthsUserCount = 0; ?>
    <?php endfor; ?>
<?php endif; ?>
