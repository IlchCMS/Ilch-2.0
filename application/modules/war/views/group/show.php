<link href="<?=$this->getBaseUrl('application/modules/war/static/css/style.css') ?>" rel="stylesheet">

<?php
$group = $this->get('group')
?>

<div id="war_index">
    <div class="col-lg-12">
        <div class="row">
            <div class="col-xs-12 col-md-6 text-center">
                <img src="<?=$this->getBaseUrl($group->getGroupImage()) ?>" alt="<?=$group->getGroupName() ?>" class="thumbnail img-responsive" />
            </div>
            <div class="col-xs-12 col-md-6 section-box">
                <h3>
                    <?=$group->getGroupName() ?>
                </h3>
                <p>...</p>
                <hr />
                <div class="row rating-desc">
                    <div class="col-md-12">
                        <span>WIN</span>(36)<span class="separator">|</span>
                        <span>LOOS</span>(100)
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<legend><?=$this->getTrans('warsOverview') ?></legend>
<?php if ($this->get('war') != ''): ?>
    <table class="table table-striped table-hover table-responsive">
        <colgroup>
            <col class="col-lg-2">
            <col class="col-lg-2">
            <col class="col-lg-2">
            <col class="col-lg-2">
            <col class="col-lg-2">
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
                <?php $date = new \Ilch\Date($war->getWarTime()) ?>
                <tr>
                    <td><?=$war->getWarEnemy() ?></td>
                    <td><?=$war->getWarGroup() ?></td>
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
                    if ($games != ''){
                        $enemyPoints = '';
                        $groupPoints = 0;
                        $class = '';
                        foreach ($games as $game){
                            $groupPoints += $game->getGroupPoints();
                            $enemyPoints += $game->getEnemyPoints();
                        }
                        if ($groupPoints > $enemyPoints){
                            $class = 'class="war_win"';
                            $ergebniss = $this->getTrans('warWin');
                        }
                        if ($groupPoints < $enemyPoints){
                            $class = 'class="war_lost"';
                            $ergebniss = $this->getTrans('warLost');
                        }
                        if ($groupPoints == $enemyPoints){
                            $class = 'class="war_drawn"';
                            $ergebniss = $this->getTrans('warDrawn');
                        }
                    }
                    ?>
                    <td <?=$class ?>><?=$groupPoints ?>:<?=$enemyPoints ?></td>
                    <td>
                        <?php if ($games): ?>
                            <a href="<?=$this->getUrl(array('controller' => 'index', 'action' => 'show', 'id' => $war->getId())) ?>"><?=$this->getTrans('warReportShow') ?></a>
                        <?php else: ?>
                            <?=$this->getTrans('warReportNo') ?>
                        <?php endif; ?>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
<?php else: ?>
    <?=$this->getTranslator()->trans('noWars') ?>
<?php endif; ?>
