<link href="<?=$this->getBaseUrl('application/modules/war/static/css/style.css') ?>" rel="stylesheet">

<legend><?=$this->getTrans('menuGroups') ?></legend>
<h4><a class="btn btn-default" href="<?=$this->getUrl(['controller' => 'group', 'action' => 'index']) ?>"><?=$this->getTrans('toGroups') ?></a></h4>

<legend><?=$this->getTrans('warsOverview') ?></legend>
<?php if ($this->get('war') != ''): ?>
    <?=$this->get('pagination')->getHtml($this, []) ?>
    <div class="table-responsive">
        <table class="table table-striped table-hover">
            <colgroup>
                <col class="col-lg-2">
                <col class="col-lg-2">
                <col class="col-lg-2">
                <col class="col-lg-2">
                <col class="col-lg-2">
                <col>
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
                    <?php $date = new \Ilch\Date($war->getWarTime()) ?>
                    <tr>
                        <td><?=$this->escape($war->getWarEnemy()) ?></td>
                        <td><?=$this->escape($war->getWarGroup()) ?></td>
                        <td><?=$date->format("d.m.Y H:i", true) ?></td>
                        <td>
                            <?php if ($war->getWarStatus() == '1'): ?>
                                <?=$this->getTrans('warStatusOpen') ?>
                            <?php elseif ($war->getWarStatus() == '2'): ?>
                                <?=$this->getTrans('warStatusClose') ?>
                            <?php endif; ?>
                        </td>
                        <?php
                        $gameMapper = new \Modules\War\Mappers\Games();
                        $games = $gameMapper->getGamesByWarId($war->getId());
                        $enemyPoints = '';
                        $groupPoints = '';
                        $class = '';
                        if ($games != '') {
                            foreach ($games as $game) {
                                $groupPoints += $game->getGroupPoints();
                                $enemyPoints += $game->getEnemyPoints();
                            }
                            if ($groupPoints > $enemyPoints) {
                                $class = 'class="war_win"';
                                $ergebniss = 'gewonnen';
                            }
                            if ($groupPoints < $enemyPoints) {
                                $class = 'class="war_lost"';
                                $ergebniss = 'verloren';
                            }
                            if ($groupPoints == $enemyPoints) {
                                $class = 'class="war_drawn"';
                                $ergebniss = 'unentschieden';
                            }
                        }
                        ?>
                        <td <?=$class ?>><?=$groupPoints ?>:<?=$enemyPoints ?></td>
                        <td>
                            <a href="<?=$this->getUrl(['action' => 'show', 'id' => $war->getId()]) ?>"><?=$this->getTrans('warReportShow') ?></a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    <?=$this->get('pagination')->getHtml($this, []) ?>
<?php else: ?>
    <?=$this->getTranslator()->trans('noWars') ?>
<?php endif; ?>
