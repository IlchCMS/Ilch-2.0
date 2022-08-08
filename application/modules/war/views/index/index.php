<?php
$gamesMapper = $this->get('gamesMapper');
$gamesMapper = $this->get('gamesMapper');
$gamesMapper = $this->get('gamesMapper');
?>

<link href="<?=$this->getBaseUrl('application/modules/war/static/css/style.css') ?>" rel="stylesheet">

<h1><?=$this->getTrans('menuGroups') ?></h1>
<h4><a class="btn btn-default" href="<?=$this->getUrl(['controller' => 'group', 'action' => 'index']) ?>"><?=$this->getTrans('toGroups') ?></a></h4>

<h1><?=$this->getTrans('warsOverview') ?></h1>
<?php if ($this->get('war')): ?>
    <?=$this->get('pagination')->getHtml($this, []) ?>
    <div class="table-responsive">
        <table class="table table-striped table-hover">
            <colgroup>
                <col class="col-lg-2" />
                <col class="col-lg-2" />
                <col class="col-lg-2" />
                <col class="col-lg-2" />
                <col class="col-lg-2" />
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
                <?php foreach ($this->get('war') as $war): ?>
                    <?php
                    $date = new \Ilch\Date($war->getWarTime())
                    ?>
                    <tr>
                        <td><?php
                        $enemy = $this->get('enemyMapper')->getEnemyById($war->getWarEnemy());
                        echo $this->escape($enemy ? $enemy->getEnemyName() : '');
                        ?></td>
                        <td><?php
                        $group = $this->get('groupMapper')->getGroupById($war->getWarGroup());
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
    <?=$this->get('pagination')->getHtml($this, []) ?>
<?php else: ?>
    <?=$this->getTranslator()->trans('noWars') ?>
<?php endif; ?>
