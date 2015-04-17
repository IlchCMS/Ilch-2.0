<?php
$training = $this->get('training');

$userMapper = new \Modules\User\Mappers\User();
?>
<legend><?=$this->getTrans('trainDetails') ?></legend>
<div class="form-horizontal">
    <div class="form-group">
        <label for="receiver" class="col-lg-2">
            <?=$this->getTrans('title') ?>:
        </label>
        <div class="col-lg-10"><?=$this->escape($training->getTitle()) ?></div>
    </div>
    <div class="form-group">
        <label for="date" class="col-lg-2">
            <?=$this->getTrans('date') ?>:
        </label>
        <div class="col-lg-10"><?=date('d.m.Y', strtotime($training->getDate())) ?> <?=$this->getTrans('at') ?> <?=date('H:i', strtotime($training->getDate())) ?> <?=$this->getTrans('clock') ?></div>
    </div>
    <div class="form-group">
        <label for="place" class="col-lg-2">
            <?=$this->getTrans('place') ?>:
        </label>
        <div class="col-lg-10"><?=$this->escape($training->getPlace()) ?></div>
    </div>
    <div class="form-group">
        <label for="entrant" class="col-lg-2">
            <?=$this->getTrans('entrant') ?>:
        </label>
        <div class="col-lg-10">
            <?=$this->getTrans('entrys') ?> <?=$this->get('trainEntrantsUserCount') ?>
            <?php if ($this->get('trainEntrantsUserCount') != 0): ?>
                <br />
                <?php foreach ($this->get('trainEntrantsUser') as $trainEntrantsUser): ?>
                <?php $entrantsUser = $userMapper->getUserById($trainEntrantsUser->getUserId()); ?>
                    <a href="<?=$this->getUrl('user/profil/index/user/'.$entrantsUser->getId()) ?>" target="_blank"><?=$this->escape($entrantsUser->getName()) ?></a><br />
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>
    <div class="form-group">
        <label for="otherInfo" class="col-lg-2">
            <?=$this->getTrans('otherInfo') ?>:
        </label>
        <div class="col-lg-12">
            <?php if ($training->getText()!= ''): ?>
                <?=$training->getText() ?>
            <?php else: ?>
                <?=$this->getTrans('noOtherInfo') ?>
            <?php endif; ?>
        </div>
    </div>
    <?php  if ($this->getUser()): ?>
        <form class="form-horizontal" method="POST" action="">
        <?=$this->getTokenField() ?>
            <?php if ($this->get('trainEntrantUser') != ''): ?>
                <button type="submit" value="del" name="del" class="btn btn-sm btn-danger">
                    <?=$this->getTrans('decline') ?>
                </button>
            <?php else: ?>
                <button type="submit" value="save" name="save" class="btn btn-sm btn-success">
                    <?=$this->getTrans('join') ?>
                </button>
            <?php endif; ?>
        </form>
    <?php endif; ?>
</div>
