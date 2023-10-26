<?php
$newsletter = $this->get('newsletter');
$userMapper = $this->get('userMapper');
$user = $userMapper->getUserById($newsletter->getUserId());
$date = new \Ilch\Date($newsletter->getDateCreated());
?>

<h1><?=$this->getTrans('show') ?></h1>
<form class="form-horizontal" method="POST" action="">
    <?=$this->getTokenField() ?>
    <div class="form-horizontal">
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
            <div class="col-xl-4"><a href="<?=$this->getUrl('user/profil/index/user/'.$user->getId()) ?>" target="_blank"><?=$this->escape($user->getName()) ?></a></div>
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
            <div class="col-xl-10"><?=$this->purify($newsletter->getText()) ?></div>
        </div>
    </div>
    <?=$this->getSaveBar('delete') ?>
</form>
