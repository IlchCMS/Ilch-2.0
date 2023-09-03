<?php

use Ilch\Date;

$war = $this->get('war');
$group = $this->get('group');
$enemy = $this->get('enemy');
$userGroupIds = $this->get('userGroupIds');
$userMapper = $this->get('userMapper');
$acceptArray = $this->get('accept');
$acceptCheckArray = $this->get('acceptCheck');
$games = $this->get('games');
$commentsClass = new Ilch\Comments();
?>

<link href="<?=$this->getModuleUrl('static/css/style.css') ?>" rel="stylesheet">
<link href="<?=$this->getModuleUrl('../comment/static/css/comment.css') ?>" rel="stylesheet">

<h1><?=$this->getTrans('warPlay') ?></h1>
<div class="row">
    <div class="center-text row no_margin">
        <div class="col-md-5">
            <img class="thumbnail img-responsive" src="<?=$this->getBaseUrl($group && $group->getGroupImage() != '' ? $group->getGroupImage() : 'application/modules/media/static/img/nomedia.png') ?>" alt="<?=$group && $group->getGroupName() != '' ? $group->getGroupName() : '' ?>">
            <h4><span><?=$this->escape($group ? $group->getGroupName() : '') ?></span></h4>
        </div>
        <div class="col-md-2 plays-vs">
            <h4>
                <span class="fa-solid fa-circle-arrow-left"></span>
                <span>VS</span>
                <span class="fa-solid fa-circle-arrow-right"></span>
            </h4>
        </div>
        <div class="col-md-5">
            <img class="thumbnail img-responsive" src="<?=$this->getBaseUrl($enemy && $enemy->getEnemyImage() != '' ? $enemy->getEnemyImage() : 'application/modules/media/static/img/nomedia.png') ?>" alt="<?=$enemy && $enemy->getEnemyName() != '' ? $enemy->getEnemyName() : '' ?>">
            <h4><span><?=$this->escape($enemy ? $enemy->getEnemyName() : '') ?></span></h4>
        </div>
    </div>
    <div class="col-lg-6">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h3 class="panel-title"><?=$this->getTrans('warStatusFrom') ?> <?=$war->getWarTime() ?></h3>
            </div>
            <div class="panel-body">
                <?php
                if ($war->getWarStatus() == '1') {
                    echo $this->getTrans('warStatusOpen');
                } elseif ($war->getWarStatus() == '2') {
                    echo $this->getTrans('warStatusClose');
                }
                ?>
            </div>
        </div>
    </div>
    <div class="col-lg-6">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h3 class="panel-title"><?=$this->getTrans('warResult') ?></h3>
            </div>
            <div class="panel-body">
                <?php
                $enemyPoints = 0;
                $groupPoints = 0;
                $class = '';
                $result = '';
                foreach ($games ?? [] as $game) {
                    $groupPoints += $game->getGroupPoints();
                    $enemyPoints += $game->getEnemyPoints();
                }
                if ($groupPoints > $enemyPoints) {
                    $class = ' class="war_win"';
                    $result = $this->getTrans('warWin');
                }
                if ($groupPoints < $enemyPoints) {
                    $class = ' class="war_lost"';
                    $result = $this->getTrans('warLost');
                }
                if ($groupPoints == $enemyPoints) {
                    $class = ' class="war_drawn"';
                    $result = $this->getTrans('warDrawn');
                }
                ?>
                <span<?=$class ?>><?=$groupPoints ?>:<?=$enemyPoints ?> <?=$result ?></span>
            </div>
        </div>
    </div>
    <div class="col-lg-6">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h3 class="panel-title"><?=$this->getTrans('warMap') ?></h3>
            </div>
            <div class="panel-body">
            <?php foreach ($games ?? [] as $game): ?>
                <?php
                $mapModel = $this->get('mapsMapper')->getEntryById($game->getMap());
                ?>
                <ul class="list-group">
                    <li class="list-group-item">
                        <span class=""><?=$this->escape($mapModel ? $mapModel->getName() : '') ?></span>
                        <span class="pull-right"><?=$game->getGroupPoints() ?> : <?=$game->getEnemyPoints() ?></span>
                    </li>
                </ul>
            <?php endforeach; ?>
            </div>
        </div>
    </div>
    <div class="clearfix"></div>
    <div class="col-lg-6">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h3 class="panel-title"><?=$this->getTrans('warMember') ?></h3>
            </div>
            <div class="panel-body">
                <ul class="list-group">
                <?php foreach ($userGroupIds ?? [] as $userGroupId): ?>
                    <?php
                    $user = $userMapper->getUserById($userGroupId);
                    if (!$user) {
                        $user = $userMapper->getDummyUser();
                    }
                    ?>
                    <li class="list-group-item"><a href="<?=$this->getUrl(['module' => 'user', 'controller' => 'profil', 'action' => 'index', 'user' => $user->getId()]) ?>"><?=$user->getName() ?></a></li>
                <?php endforeach; ?>
                </ul>
            </div>
        </div>
    </div>
    <div class="col-lg-6">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h3 class="panel-title"><?=$this->getTrans('warAccept') ?></h3>
            </div>
            <div class="panel-body">
            <?php if ($userGroupIds): ?>
                <form id="accept_form" class="form-horizontal" method="POST" action="">
                    <?=$this->getTokenField() ?>
                    <ul class="list-group">
                    <?php foreach ($acceptCheckArray ?? [] as $acceptCheck): ?>
                        <?php
                        $user = $userMapper->getUserById($acceptCheck->getUserId());
                        if (!$user) {
                            $user = $userMapper->getDummyUser();
                        }
                        $text = '';
                        $class = '';
                        if ($acceptCheck->getAccept() == '1') {
                            $class = ' war_win';
                            $text = $this->getTrans('has').' '.$this->getTrans('accepted');
                        }
                        if ($acceptCheck->getAccept() == '2') {
                            $class = ' war_lost';
                            $text = $this->getTrans('has').' '.$this->getTrans('declined');
                        }
                        if ($acceptCheck->getAccept() == '3') {
                            $class = ' war_drawn';
                            $text = $this->getTrans('is').' '.$this->getTrans('undecided');
                        }
                        $comment = '';
                        if ($acceptCheck->getComment()) {
                            $comment = ' -> '.$this->escape($acceptCheck->getComment());
                        }
                        ?>
                        <li class="list-group-item<?=$class ?>">
                            <a href="<?=$this->getUrl(['module' => 'user', 'controller' => 'profil', 'action' => 'index', 'user' => $user->getId()]) ?>"><?=$user->getName() ?></a>: 
                            <?=$text ?><?=$comment ?> <i class="fa-solid fa-circle-info" data-toggle="popover" data-trigger="hover" data-content="<?=(new Date($acceptCheck->getDateCreated()))->format('d.m.Y H:i') ?>"></i>
                        </li>
                    <?php endforeach; ?>
                    </ul>
                    <?php
                    $datenow = $this->get('datenow')->getTimestamp();
                    $wartime = (new Date($war->getWarTime()))->getTimestamp();
                    ?>
                    <?php if (($war->getLastAcceptTime() == 0 || ((($wartime - $datenow) / 60) >= $war->getLastAcceptTime())) && $war->getWarStatus() == 1):?>
                        <?php foreach ($userGroupIds as $userGroupId): ?>
                            <?php
                            $user = $userMapper->getUserById($userGroupId);
                            if (!$user) {
                                $user = $userMapper->getDummyUser();
                            }
                            ?>
                            <?php if ($this->getUser()): ?>
                                <?php if ($user->getId() == $this->getUser()->getId()): ?>
                                    <select class="form-control col-lg-3 <?=$this->validation()->hasError('warAccept') ? ' has-error' : '' ?>" id="warAccept" name="warAccept">
                                        <optgroup label="<?=$this->getTrans('choose') ?>">
                                            <option value="1" <?=($acceptArray ? $acceptArray->getAccept() : '') == 1 ? 'selected=""' : '' ?>><?=$this->getTrans('accept') ?></option>
                                            <option value="2" <?=($acceptArray ? $acceptArray->getAccept() : '') == 2 ? 'selected=""' : '' ?>><?=$this->getTrans('decline') ?></option>
                                            <option value="3" <?=($acceptArray ? $acceptArray->getAccept() : '') == 3 ? 'selected=""' : '' ?>><?=$this->getTrans('undecided') ?></option>
                                        </optgroup>
                                    </select>
                                    <textarea class="form-control col-lg-3 <?=$this->validation()->hasError('warComment') ? ' has-error' : '' ?>"
                                              style="resize: vertical"
                                              name="warComment"
                                              id="warComment" ></textarea>
                                    <button type="submit" class="btn" name="save" value="save">
                                        <?=$this->getTrans('save') ?>
                                    </button>
                                <?php endif; ?>
                            <?php endif; ?>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </form>
            <?php endif; ?>
            </div>
        </div>
    </div>
    <div class="col-lg-12">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h3 class="panel-title"><?=$this->getTrans('warReport') ?></h3>
            </div>
            <div class="panel-body">
                <?=$this->purify($war->getWarReport()) ?>
            </div>
        </div>
    </div>
</div>

<?= $commentsClass->getComments($this->get('commentsKey'), $war, $this) ?>
<script>
    $('[data-toggle="popover"]').popover();  
</script>
