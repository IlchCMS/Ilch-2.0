<link href="<?=$this->getBaseUrl('application/modules/war/static/css/style.css') ?>" rel="stylesheet">

<legend><?=$this->getTrans('menuGroups') ?></legend>
<?php if ($this->get('groups') != ''): ?>
    <?=$this->get('pagination')->getHtml($this, []) ?>
    <div id="war_index">
        <?php foreach ($this->get('groups') as $group): ?>
            <?php
            $warMapper = $this->get('warMapper');
            $gamesMapper = $this->get('gamesMapper');

            $win = 0;
            $lost = 0;
            $drawn = 0;
            $winCount = 0;
            $lostCont = 0;
            $drawnCount = 0;

            $wars = $warMapper->getWars(['group' => $group->getId()]);

            foreach ($wars as $war) {
                $enemyPoints = '';
                $groupPoints = '';
                $games = $gamesMapper->getGamesByWhere(['war_id' => $war->getId()]);

                if($games) {
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
            <div class="col-lg-12">
                <div class="row">
                    <div class="col-xs-12 col-md-3 text-center">
                        <a href="<?=$this->getUrl(['controller' => 'group', 'action' => 'show', 'id' => $group->getId()]) ?>">
                            <img src="<?=$this->getBaseUrl($group->getGroupImage()) ?>" alt="<?=$group->getGroupName() ?>" class="thumbnail img-responsive" />
                        </a>
                    </div>
                    <div class="col-xs-12 col-md-9 section-box">
                        <h4>
                            <a href="<?=$this->getUrl(['controller' => 'group', 'action' => 'show', 'id' => $group->getId()]) ?>"><?=$this->escape($group->getGroupName()) ?></a>
                        </h4>
                        <strong><?=$this->getTrans('groupDesc') ?></strong>
                        <p><?=$this->escape($group->getGroupDesc()) ?></p>
                        <hr />
                        <div class="row rating-desc">
                            <div class="col-md-12">
                                <strong><?=$this->getTrans('games') ?></strong></br>
                                <span><?=$this->getTrans('warWin') ?></span>(<?=$winCount ?>)<span class="separator">|</span>
                                <span><?=$this->getTrans('warLost') ?></span>(<?=$lostCont ?>)<span class="separator">|</span>
                                <span><?=$this->getTrans('warDrawn') ?></span>(<?=$drawnCount ?>)
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
    <?=$this->get('pagination')->getHtml($this, []) ?>
<?php else: ?>
    <?=$this->getTranslator()->trans('noGroup') ?>
<?php endif; ?>
