<?php

/** @var \Ilch\View $this */

use Ilch\Date;

/** @var \Modules\War\Models\Group $group */
$group = $this->get('group');
/** @var \Modules\War\Mappers\War[]|null $wars */
$wars = $this->get('wars');
/** @var \Modules\War\Mappers\Games $gamesMapper */
$gamesMapper = $this->get('gamesMapper');


/** @var \Modules\War\Mappers\Enemy $enemyMapper */
$enemyMapper = $this->get('enemyMapper');
/** @var \Modules\War\Mappers\Group $groupMapper */
$groupMapper = $this->get('groupMapper');

$win = 0;
$lost = 0;
$drawn = 0;
$winCount = 0;
$lostCont = 0;
$drawnCount = 0;

foreach ($wars ?? [] as $war) {
    $enemyPoints = 0;
    $groupPoints = 0;
    $games = $gamesMapper->getGamesByWhere(['war_id' => $war->getId()]);

    if ($games) {
        foreach ($games as $game) {
            $groupPoints += $game->getGroupPoints();
            $enemyPoints += $game->getEnemyPoints();
        }
    }
    if ($groupPoints > $enemyPoints) {
        $win++;
    }
    if ($groupPoints < $enemyPoints) {
        $lost++;
    }
    if ($groupPoints == $enemyPoints) {
        $drawn++;
    }
    $winCount = $win;
    $lostCont = $lost;
    $drawnCount = $drawn;
}
?>

<link href="<?=$this->getBaseUrl('application/modules/war/static/css/style.css') ?>" rel="stylesheet">

<div id="war_index" class="row">
    <div class="col-xl-12 no_padding">
        <div class="row">
            <div class="col-12 col-xl-6 text-center">
                <img src="<?=$this->getBaseUrl($group->getGroupImage()) ?>" alt="<?=$group->getGroupName() ?>" class="thumbnail img-fluid" />
            </div>
            <div class="col-12 col-xl-6 section-box">
                <h3>
                    <?=$this->escape($group->getGroupName()) ?>
                </h3>
                <strong><?=$this->getTrans('groupDesc') ?></strong>
                <p><?=$this->escape($group->getGroupDesc()) ?></p>
                <hr />
                <div class="row rating-desc">
                    <div class="col-xl-12">
                        <strong><?=$this->getTrans('games') ?></strong><br />
                        <span><?=$this->getTrans('warWin') ?></span>(<?=$winCount ?>)<span class="separator">|</span>
                        <span><?=$this->getTrans('warLost') ?></span>(<?=$lostCont ?>)<span class="separator">|</span>
                        <span><?=$this->getTrans('warDrawn') ?></span>(<?=$drawnCount ?>)
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<h1><?=$this->getTrans('warsOverview') ?></h1>
<?php if ($this->get('war')) : ?>
    <div class="table-responsive">
        <table class="table table-striped table-hover">
            <colgroup>
                <col class="col-xl-2" />
                <col class="col-xl-2" />
                <col class="col-xl-2" />
                <col class="col-xl-2" />
                <col class="col-xl-2" />
                <col />
            </colgroup>
            <thead>
                <tr>
                    <th><?=$this->getTrans('enemyName') ?></th>
                    <th><?=$this->getTrans('groupName') ?></th>
                    <th><?=$this->getTrans('nextWarTime') ?></th>
                    <th><?=$this->getTrans('warStatus') ?></th>
                    <th><?=$this->getTrans('warResult') ?></th>
                    <th><?=$this->getTrans('warReport') ?></th>
                </tr>
            </thead>
            <tbody>
                <?php
                /** @var \Modules\War\Models\War $war */
                foreach ($this->get('war') as $war) : ?>
                    <?php $date = new Date($war->getWarTime()) ?>
                    <tr>
                        <td><?php
                        $enemy = $enemyMapper->getEnemyById($war->getWarEnemy());
                        echo $this->escape($enemy ? $enemy->getEnemyName() : '');
                        ?></td>
                        <td><?php
                        $group = $groupMapper->getGroupById($war->getWarGroup());
                        echo $this->escape($group ? $group->getGroupName() : '');
                        ?></td>
                        <td><?=$date->format("d.m.Y H:i", true) ?></td>
                        <td>
                            <?php
                            if ($war->getWarStatus() == '1') {
                                echo $this->getTrans('warStatusOpen');
                            } elseif ($war->getWarStatus() == '2') {
                                echo $this->getTrans('warStatusClose');
                            }
                            ?>
                        </td>
                        <?php
                        $games = $gamesMapper->getGamesByWarId($war->getId());
                        $class = '';
                        $enemyPoints = 0;
                        $groupPoints = 0;
                        if ($games != '') {
                            foreach ($games as $game) {
                                $groupPoints += $game->getGroupPoints();
                                $enemyPoints += $game->getEnemyPoints();
                            }
                            if ($groupPoints > $enemyPoints) {
                                $class = 'class="war_win"';
                                $ergebniss = $this->getTrans('warWin');
                            }
                            if ($groupPoints < $enemyPoints) {
                                $class = 'class="war_lost"';
                                $ergebniss = $this->getTrans('warLost');
                            }
                            if ($groupPoints == $enemyPoints) {
                                $class = 'class="war_drawn"';
                                $ergebniss = $this->getTrans('warDrawn');
                            }
                        }
                        ?>
                        <td <?=$class ?>><?=$groupPoints ?>:<?=$enemyPoints ?></td>
                        <td>
                            <?php if ($games) : ?>
                                <a href="<?=$this->getUrl(['controller' => 'index', 'action' => 'show', 'id' => $war->getId()]) ?>"><?=$this->getTrans('warReportShow') ?></a>
                            <?php else : ?>
                                <a href="<?=$this->getUrl(['controller' => 'index', 'action' => 'show', 'id' => $war->getId()]) ?>"><?=$this->getTrans('warPlay') ?></a>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
<?php else : ?>
    <?=$this->getTranslator()->trans('noWars') ?>
<?php endif; ?>
