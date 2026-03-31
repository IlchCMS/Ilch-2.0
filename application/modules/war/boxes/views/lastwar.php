<?php
/** @var \Ilch\View $this */

/** @var \Modules\War\Mappers\Games $gamesMapper */
$gamesMapper = $this->get('gamesMapper');
/** @var array $gameIconMap */
$gameIconMap = $this->get('gameIconMap') ?? [];

$displayed = 0;
?>
<link href="<?=$this->getBoxUrl('static/css/style.css') ?>" rel="stylesheet">

<?php if ($this->get('wars') != '') :
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
            } elseif ($groupPoints < $enemyPoints) {
                $matchStatus = 'war_lost';
            } elseif ($groupPoints === $enemyPoints && ($groupPoints > 0 || $enemyPoints > 0)) {
                $matchStatus = 'war_drawn';
            }
        }

        $iconFilename = null;
        foreach ($gameIconMap as $game) {
            if ($game['title'] == $war->getWarGame()) {
                $iconFilename = $game['icon'];
                break;
            }
        }
        ?>
        <a class="war-box-entry text-decoration-none" href="<?=$this->getUrl('war/index/show/id/' . $war->getId()) ?>">
            <span class="war-box-icon">
                <?php if ($iconFilename !== null) : ?>
                    <img src="<?=$this->getBoxUrl('static/img/' . $iconFilename . '.png') ?>" title="<?=$this->escape($war->getWarGame()) ?>" width="16" height="16" alt="<?=$this->escape($war->getWarGame()) ?>">
                <?php else : ?>
                    <i class="fa-solid fa-gamepad text-muted" title="<?=$this->escape($war->getWarGame()) ?>"></i>
                <?php endif; ?>
            </span>
            <span class="war-box-teams">
                <span class="war-box-tag"><?=$this->escape($war->getWarGroupTag()) ?></span>
                <span class="war-box-vs"><?=$this->getTrans('vs') ?></span>
                <span class="war-box-tag"><?=$this->escape($war->getWarEnemyTag()) ?></span>
            </span>
            <span class="war-box-meta <?=$matchStatus ?>">
                <?=$groupPoints ?>:<?=$enemyPoints ?>
            </span>
        </a>
    <?php endforeach; ?>
<?php endif; ?>

<?php if (!$displayed) : ?>
    <?=$this->getTrans('noWars') ?>
<?php endif; ?>
