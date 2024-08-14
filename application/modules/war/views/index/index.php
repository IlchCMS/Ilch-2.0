<?php

/** @var \Ilch\View $this */

/** @var \Ilch\Pagination $pagination */
$pagination = $this->get('pagination');

use Ilch\Date;

/** @var \Modules\War\Mappers\Games $gamesMapper */
$gamesMapper = $this->get('gamesMapper');


/** @var \Modules\War\Mappers\Enemy $enemyMapper */
$enemyMapper = $this->get('enemyMapper');
/** @var \Modules\War\Mappers\Group $groupMapper */
$groupMapper = $this->get('groupMapper');
?>

<link href="<?=$this->getBaseUrl('application/modules/war/static/css/style.css') ?>" rel="stylesheet">

<h1><?=$this->getTrans('menuGroups') ?></h1>
<h4><a class="btn btn-outline-secondary" href="<?=$this->getUrl(['controller' => 'group', 'action' => 'index']) ?>"><?=$this->getTrans('toGroups') ?></a></h4>

<h1><?=$this->getTrans('warsOverview') ?></h1>
<?php if ($this->get('war')) : ?>
    <?=$pagination->getHtml($this, []) ?>
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
                    <?php
                    $date = new Date($war->getWarTime())
                    ?>
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
                        $enemyPoints = 0;
                        $groupPoints = 0;
                        $class = '';
                        if ($games != '') {
                            foreach ($games as $game) {
                                $groupPoints += $game->getGroupPoints();
                                $enemyPoints += $game->getEnemyPoints();
                            }
                            if ($groupPoints > $enemyPoints) {
                                $class = ' class="war_win"';
                            }
                            if ($groupPoints < $enemyPoints) {
                                $class = ' class="war_lost"';
                            }
                            if ($groupPoints == $enemyPoints) {
                                $class = ' class="war_drawn"';
                            }
                        }
                        ?>
                        <td<?=$class ?>><?=$groupPoints ?>:<?=$enemyPoints ?></td>
                        <td><a href="<?=$this->getUrl(['action' => 'show', 'id' => $war->getId()]) ?>"><?=$this->getTrans('warReportShow') ?></a></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    <?=$pagination->getHtml($this, []) ?>
<?php else : ?>
    <?=$this->getTranslator()->trans('noWars') ?>
<?php endif; ?>
