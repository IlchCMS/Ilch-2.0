<?php

/** @var \Ilch\View $this */

use Ilch\Date;
$commentsClass = new Ilch\Comments();

/** @var \Modules\War\Models\War $war */
$war = $this->get('war');
/** @var \Modules\War\Models\Group $group */
$group = $this->get('group');
/** @var \Modules\War\Models\Enemy $enemy */
$enemy = $this->get('enemy');
/** @var int[]|null $userGroupIds */
$userGroupIds = $this->get('userGroupIds');
/** @var \Modules\War\Models\Accept $acceptArray */
$acceptArray = $this->get('accept');
/** @var \Modules\War\Models\Accept[]|null $acceptCheckArray */
$acceptCheckArray = $this->get('acceptCheck');
/** @var \Modules\War\Models\Games[]|null $games */
$games = $this->get('games');

/** @var \Modules\User\Mappers\User $userMapper */
$userMapper = $this->get('userMapper');
/** @var \Modules\War\Mappers\Maps $mapsMapper */
$mapsMapper = $this->get('mapsMapper');

/** @var \Ilch\Date $datenow */
$datenow = $this->get('datenow');
?>

<link href="<?=$this->getModuleUrl('static/css/style.css') ?>" rel="stylesheet">
<link href="<?=$this->getModuleUrl('../comment/static/css/comment.css') ?>" rel="stylesheet">

<h1><?=$this->getTrans('warPlay') ?></h1>
<div class="row">
    <div class="center-text row no_margin">
        <div class="col-xl-5">
            <img class="img-thumbnail img-fluid" src="<?=$this->getBaseUrl($group && $group->getGroupImage() != '' ? $group->getGroupImage() : 'application/modules/media/static/img/nomedia.png') ?>" alt="<?=$group && $group->getGroupName() != '' ? $group->getGroupName() : '' ?>">
            <h4><span><?=$this->escape($group ? $group->getGroupName() : '') ?></span></h4>
        </div>
        <div class="col-xl-2 plays-vs">
            <h4>
                <span class="fa-solid fa-circle-arrow-left"></span>
                <span>VS</span>
                <span class="fa-solid fa-circle-arrow-right"></span>
            </h4>
        </div>
        <div class="col-xl-5">
            <img class="img-thumbnail img-fluid" src="<?=$this->getBaseUrl($enemy && $enemy->getEnemyImage() != '' ? $enemy->getEnemyImage() : 'application/modules/media/static/img/nomedia.png') ?>" alt="<?=$enemy && $enemy->getEnemyName() != '' ? $enemy->getEnemyName() : '' ?>">
            <h4><span><?=$this->escape($enemy ? $enemy->getEnemyName() : '') ?></span></h4>
        </div>
    </div>
    <div class="col-xl-6">
        <div class="card cartd-default">
            <div class="card-header">
                <h6 class="card-title"><?=$this->getTrans('warStatusFrom') ?> <?=$war->getWarTime() ?></h6>
            </div>
            <div class="card-body">
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
    <div class="col-xl-6">
        <div class="card card-default">
            <div class="card-header">
                <h6 class="card-title"><?=$this->getTrans('warResult') ?></h6>
            </div>
            <div class="card-body">
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
    <div class="col-xl-6">
        <div class="card card-default">
            <div class="card-header">
                <h6 class="card-title"><?=$this->getTrans('warMap') ?></h6>
            </div>
            <div class="card-body">
            <?php foreach ($games ?? [] as $game) : ?>
                <?php
                $mapModel = $mapsMapper->getEntryById($game->getMap());
                ?>
                <ul class="list-group">
                    <li class="list-group-item">
                        <span class=""><?=$this->escape($mapModel ? $mapModel->getName() : '') ?></span>
                        <span class="float-end"><?=$game->getGroupPoints() ?> : <?=$game->getEnemyPoints() ?></span>
                    </li>
                </ul>
            <?php endforeach; ?>
            </div>
        </div>
    </div>
    <div class="clearfix"></div>
    <div class="col-xl-6">
        <div class="card card-default">
            <div class="card-header">
                <h6 class="card-title"><?=$this->getTrans('warMember') ?></h6>
            </div>
            <div class="card-body">
                <ul class="list-group">
                <?php foreach ($userGroupIds ?? [] as $userGroupId) : ?>
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
    <div class="col-xl-6">
        <div class="card card-default">
            <div class="card-header">
                <h6 class="card-title"><?=$this->getTrans('warAccept') ?></h6>
            </div>
            <div class="card-body">
            <?php if ($userGroupIds) : ?>
                <form id="accept_form" method="POST" action="">
                    <?=$this->getTokenField() ?>
                    <ul class="list-group">
                    <?php foreach ($acceptCheckArray ?? [] as $acceptCheck) : ?>
                        <?php
                        $user = $userMapper->getUserById($acceptCheck->getUserId());
                        if (!$user) {
                            $user = $userMapper->getDummyUser();
                        }
                        $text = '';
                        $class = '';
                        if ($acceptCheck->getAccept() == '1') {
                            $class = ' war_win';
                            $text = $this->getTrans('has') . ' ' . $this->getTrans('accepted');
                        }
                        if ($acceptCheck->getAccept() == '2') {
                            $class = ' war_lost';
                            $text = $this->getTrans('has') . ' ' . $this->getTrans('declined');
                        }
                        if ($acceptCheck->getAccept() == '3') {
                            $class = ' war_drawn';
                            $text = $this->getTrans('is') . ' ' . $this->getTrans('undecided');
                        }
                        $comment = '';
                        if ($acceptCheck->getComment()) {
                            $comment = ' -> ' . $this->escape($acceptCheck->getComment());
                        }
                        ?>
                        <li class="list-group-item<?=$class ?>">
                            <a href="<?=$this->getUrl(['module' => 'user', 'controller' => 'profil', 'action' => 'index', 'user' => $user->getId()]) ?>"><?=$user->getName() ?></a>:
                            <?=$text ?><?=$comment ?> <span class="fa-solid fa-circle-info" data-bs-toggle="tooltip" data-bs-custom-class="custom-tooltip" data-bs-title="<?=(new Date($acceptCheck->getDateCreated()))->format('d.m.Y H:i') ?>"></span>
                        </li>
                    <?php endforeach; ?>
                    </ul>
                    <?php
                    $datenow = $datenow->getTimestamp();
                    $wartime = (new Date($war->getWarTime()))->getTimestamp();
                    ?>
                    <?php if (($war->getLastAcceptTime() == 0 || ((($wartime - $datenow) / 60) >= $war->getLastAcceptTime())) && $war->getWarStatus() == 1) :?>
                        <?php foreach ($userGroupIds as $userGroupId) : ?>
                            <?php
                            $user = $userMapper->getUserById($userGroupId);
                            if (!$user) {
                                $user = $userMapper->getDummyUser();
                            }
                            ?>
                            <?php if ($this->getUser()) : ?>
                                <?php if ($user->getId() == $this->getUser()->getId()) : ?>
                                    <select class="form-select col-lg-3 mb-3<?=$this->validation()->hasError('warAccept') ? ' has-error' : '' ?>" id="warAccept" name="warAccept">
                                        <optgroup label="<?=$this->getTrans('choose') ?>">
                                            <option value="1" <?=($acceptArray ? $acceptArray->getAccept() : '') == 1 ? 'selected=""' : '' ?>><?=$this->getTrans('accept') ?></option>
                                            <option value="2" <?=($acceptArray ? $acceptArray->getAccept() : '') == 2 ? 'selected=""' : '' ?>><?=$this->getTrans('decline') ?></option>
                                            <option value="3" <?=($acceptArray ? $acceptArray->getAccept() : '') == 3 ? 'selected=""' : '' ?>><?=$this->getTrans('undecided') ?></option>
                                        </optgroup>
                                    </select>
                                    <textarea class="form-control col-lg-3 mb-3<?=$this->validation()->hasError('warComment') ? ' has-error' : '' ?>"
                                              style="resize: vertical"
                                              name="warComment"
                                              id="warComment" ></textarea>
                                    <button type="submit" class="btn btn-outline-secondary" name="save" value="save">
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
    <div class="col-xl-12">
        <div class="card card-default">
            <div class="card-header">
                <h6 class="card-title"><?=$this->getTrans('warReport') ?></h6>
            </div>
            <div class="card-body ck-content">
                <?=$this->purify($war->getWarReport()) ?>
            </div>
        </div>
    </div>
</div>

<?= $commentsClass->getComments($this->get('commentsKey'), $war, $this) ?>
