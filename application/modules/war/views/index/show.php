<?php
$gamesMapper = $this->get('gamesMapper');
$war = $this->get('war');
$group = $this->get('group');
$enemy = $this->get('enemy');
$userMapper = $this->get('userMapper');
$userGroupMapper = $this->get('userGroupMapper');
$acceptArray = $this->get('accept');
$acceptCheckArray = $this->get('acceptCheck');
$commentsClass = new Ilch\Comments();
?>

<link href="<?=$this->getModuleUrl('static/css/style.css') ?>" rel="stylesheet">
<link href="<?=$this->getModuleUrl('../comment/static/css/comment.css') ?>" rel="stylesheet">

<h1><?=$this->getTrans('warPlay') ?></h1>
<div class="row">
    <div class="center-text row no_margin">
        <div class="col-md-5">
            <img class="thumbnail img-responsive" src="<?=$this->getBaseUrl($group->getGroupImage() != '' ? $group->getGroupImage() : 'application/modules/media/static/img/nomedia.png') ?>" alt="<?=$group->getGroupName() != '' ? $group->getGroupName() : '' ?>">
            <h4><span><?=$this->escape($group != '' ? $group->getGroupName() : '') ?></span></h4>
        </div>
        <div class="col-md-2 plays-vs">
            <h4>
                <span class="fa fa-arrow-circle-left "></span>
                <span>VS</span>
                <span class="fa fa-arrow-circle-right"></span>
            </h4>
        </div>
        <div class="col-md-5">
            <img class="thumbnail img-responsive" src="<?=$this->getBaseUrl($enemy->getEnemyImage() != '' ? $enemy->getEnemyImage() : 'application/modules/media/static/img/nomedia.png') ?>" alt="<?=$enemy->getEnemyName() != '' ? $enemy->getEnemyName() : '' ?>">
            <h4><span><?=$this->escape($enemy->getEnemyName()) ?></span></h4>
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
                $games = $gamesMapper->getGamesByWarId($war->getId());
                $enemyPoints = 0;
                $groupPoints = 0;
                $class = '';
                $result = '';
                if ($games != '') {
                    foreach ($games as $game) {
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
                <?php if ($this->get('games') != ''): ?>
                    <?php foreach ($this->get('games') as $game): ?>
                        <ul class="list-group">
                            <li class="list-group-item">
                                <span class=""><?=$this->escape($game->getMap()) ?></span>
                                <span class="pull-right"><?=$game->getGroupPoints() ?> : <?=$game->getEnemyPoints() ?></span>
                            </li>
                        </ul>
                    <?php endforeach; ?>
                <?php endif; ?>
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
                <?php $userGroupIds = $userGroupMapper->getUsersForGroup($group != '' ? $group->getGroupMember() : ''); ?>
                <ul class="list-group">
                    <?php if ($userGroupIds): ?>
                        <?php foreach ($userGroupIds as $userGroupId): ?>
                            <?php $user = $userMapper->getUserById($userGroupId); ?>
                            <li class="list-group-item"><a href="<?=$this->getUrl(['module' => 'user', 'controller' => 'profil', 'action' => 'index', 'user' => $user->getId()]) ?>"><?=$user->getName() ?></a></li>
                        <?php endforeach; ?>
                    <?php endif; ?>
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
                            <?php if ($acceptCheckArray): ?>
                                <?php foreach ($acceptCheckArray as $acceptCheck): ?>
                                    <?php $user = $userMapper->getUserById($acceptCheck->getUserId()) ?>
                                    <?php if ($user): ?>
                                        <?php
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
                                        if ($acceptCheck->getComment() != '') {
                                            $comment = ' -> '.$this->escape($acceptCheck->getComment());
                                        } else {
                                            $comment = '';
                                        }
                                        ?>
                                        <li class="list-group-item<?=$class ?>"><a href="<?=$this->getUrl(['module' => 'user', 'controller' => 'profil', 'action' => 'index', 'user' => $user->getId()]) ?>"><?=$user->getName() ?></a>: <?=$text ?><?=$comment ?></li>
                                    <?php endif; ?>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </ul>
                        <?php $userGroupIds = $userGroupMapper->getUsersForGroup($group->getGroupMember()); ?>
                        <?php foreach ($userGroupIds as $userGroupId): ?>
                            <?php $user = $userMapper->getUserById($userGroupId); ?>
                            <?php if ($this->getUser()): ?>
                                <?php if ($user->getId() == $this->getUser()->getId()): ?>
                                    <select class="form-control col-lg-3" id="warAccept" name="warAccept">
                                        <optgroup label="<?=$this->getTrans('choose') ?>">
                                            <option value="1"><?=$this->getTrans('accept') ?></option>
                                            <option value="2"><?=$this->getTrans('decline') ?></option>
                                            <option value="3"><?=$this->getTrans('undecided') ?></option>
                                        </optgroup>
                                    </select>
                                    <textarea class="form-control col-lg-3"
                                              style="resize: vertical"
                                              name="warComment"
                                              id="warComment" ></textarea>
                                    <button type="submit" class="btn" name="save" value="save">
                                        <?=$this->getTrans('save') ?>
                                    </button>
                                <?php endif; ?>
                            <?php endif; ?>
                        <?php endforeach; ?>
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
