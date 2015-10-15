<?php
$newsletter = $this->get('newsletter');
$userMapper = new \Modules\User\Mappers\User();
$user = $userMapper->getUserById($newsletter->getUserId());
$date = new \Ilch\Date($newsletter->getDateCreated());
?>

<legend><?=$this->getTrans('show') ?></legend>
<form class="form-horizontal" method="POST" action="">
    <?=$this->getTokenField() ?>
    <div class="form-horizontal">
        <div class="form-group">
            <label for="date" class="col-lg-2">
                <?=$this->getTrans('date') ?>:
            </label>
            <div class="col-lg-4"><?=$date->format("d.m.Y H:i", true) ?></div>
        </div>
        <div class="form-group">
            <label for="from" class="col-lg-2">
                <?=$this->getTrans('from') ?>:
            </label>
            <div class="col-lg-4"><a href="<?=$this->getUrl('user/profil/index/user/'.$user->getId()) ?>" target="_blank"><?=$this->escape($user->getName()) ?></a></div>
        </div>
        <div class="form-group">
            <label for="subject" class="col-lg-2">
                <?=$this->getTrans('subject') ?>:
            </label>
            <div class="col-lg-10"><?=$this->escape($newsletter->getSubject()) ?></div>
        </div>
        <div class="form-group">
            <label for="text" class="col-lg-2">
                <?=$this->getTrans('text') ?>:
            </label>
            <div class="col-lg-10"><?=$newsletter->getText() ?></div>
        </div>
    </div>
    <?=$this->getSaveBar('delete') ?>
</form>
