<?php

use Ilch\Date;

$newsletter = $this->get('newsletter');
$userMapper = $this->get('userMapper');
$user = $userMapper->getUserById($newsletter->getUserId());
$date = new Date($newsletter->getDateCreated());
?>

<h1><?=$this->getTrans('show') ?></h1>
<div class="row mb-3">
    <div class="col-xl-2">
        <?=$this->getTrans('date') ?>:
    </div>
    <div class="col-xl-4"><?=$date->format('d.m.Y H:i', true) ?></div>
</div>
<div class="row mb-3">
    <div class="col-xl-2">
        <?=$this->getTrans('from') ?>:
    </div>
    <div class="col-xl-4"><a href="<?=$this->getUrl('user/profil/index/user/' . $user->getId()) ?>" target="_blank"><?=$this->escape($user->getName()) ?></a></div>
</div>
<div class="row mb-3">
    <div class="col-xl-2">
        <?=$this->getTrans('subject') ?>:
    </div>
    <div class="col-xl-10"><?=$this->escape($newsletter->getSubject()) ?></div>
</div>
<div class="row mb-3">
    <div class="col-xl-2">
        <?=$this->getTrans('text') ?>:
    </div>
    <div class="col-xl-10 ck-content">
        <?=$this->purify($newsletter->getText()) ?>
    </div>
</div>

<form method="POST" action="">
    <?=$this->getTokenField() ?>
    <?=$this->getSaveBar('delete') ?>
</form>
