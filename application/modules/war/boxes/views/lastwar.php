<?php
/** @var \Ilch\View $this */

/** @var \Modules\War\Mappers\Games $gamesMapper */
$gamesMapper = $this->get('gamesMapper');
/** @var \Modules\War\Mappers\War $warMapper */
$warMapper = $this->get('warMapper');
?>
<link href="<?=$this->getBoxUrl('static/css/style.css') ?>" rel="stylesheet">

<?php if ($this->get('wars') != '') :
    $displayed = 0;
    $adminAccess = null;
    if ($this->getUser()) {
        $adminAccess = $this->getUser()->isAdmin();
    }

    /** @var \Modules\War\Models\War $war */
    foreach ($this->get('wars') as $war) :
        $displayed++;

        $games = $gamesMapper->getGamesByWarId($war->getId());
        $enemyPoints = 0;
        $groupPoints = 0;
        $matchStatus = '';
        if ($games != '') {
            foreach ($games as $game) {
                $groupPoints += $game->getGroupPoints();
                $enemyPoints += $game->getEnemyPoints();
            }
            if ($groupPoints > $enemyPoints) {
                $matchStatus = 'war_win';
            }
            if ($groupPoints < $enemyPoints) {
                $matchStatus = 'war_lost';
            }
            if ($groupPoints == $enemyPoints) {
                $matchStatus = 'war_drawn';
            }
        }

        $gameImg = $this->getBoxUrl('static/img/' . $war->getWarGame() . '.png');
        if (file_exists(APPLICATION_PATH . '/modules/war/static/img/' . $war->getWarGame() . '.png')) {
            $gameImg = '<img src="' . $this->getBoxUrl('static/img/' . urlencode($war->getWarGame()) . '.png') . '" title="' . $this->escape($war->getWarGame()) . '" width="16" height="16">';
        } else {
            $gameImg = '<i class="fa-solid fa-question-circle text-muted" title="' . $this->escape($war->getWarGame()) . '"></i>';
        }
        ?>
        <div class="lastwar-box">
            <div class="row">
                <a href="<?=$this->getUrl('war/index/show/id/' . $war->getId()) ?>" title="<?=$this->escape($war->getWarGroupTag()) . ' ' . $this->getTrans('vs') . ' ' . $this->escape($war->getWarEnemyTag()) ?>">
                    <div class="col-4 ellipsis">
                        <?=$gameImg ?>
                        <div class="ellipsis-item">
                            <?=$this->escape($war->getWarGroupTag()) ?>
                        </div>
                    </div>
                    <div class="col-2 small float-start nextwar-vs"><?=$this->getTrans('vs') ?></div>
                    <div class="col-3 ellipsis">
                        <div class="ellipsis-item">
                            <?=$this->escape($war->getWarEnemyTag()) ?>
                        </div>
                    </div>
                </a>
                <div class="col-3 small nextwar-date">
                    <div class="ellipsis-item text-end <?=$matchStatus ?>" title="<?=$groupPoints ?>:<?=$enemyPoints ?>">
                        <?=$groupPoints ?>:<?=$enemyPoints ?>
                    </div>
                </div>
            </div>
        </div>
    <?php endforeach; ?>
    <?php if (!$displayed) : ?>
        <?=$this->getTrans('noWars') ?>
    <?php endif; ?>
<?php else : ?>
    <?=$this->getTrans('noWars') ?>
<?php endif; ?>
