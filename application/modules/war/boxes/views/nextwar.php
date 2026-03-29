<?php
/** @var \Ilch\View $this */

/** @var \Modules\War\Mappers\War $warMapper */
$warMapper = $this->get('warMapper');
/** @var array<string, string> $gameIconMap */
$gameIconMap = $this->get('gameIconMap') ?? [];
?>
<link href="<?=$this->getBoxUrl('static/css/style.css') ?>" rel="stylesheet">

<?php if ($this->get('wars') != '') :
    $displayed = 0;

    /** @var \Modules\War\Models\War $war */
    foreach ($this->get('wars') as $war) :
        $displayed++;
        $iconFilename = $gameIconMap[$war->getWarGame()] ?? null;
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
            <span class="war-box-meta">
                <?=$this->escape($warMapper->countdown($war->getWarTime())) ?>
            </span>
        </a>
    <?php endforeach; ?>
    <?php if (!$displayed) : ?>
        <p class="text-muted small mb-0"><?=$this->getTrans('noWars') ?></p>
    <?php endif; ?>
<?php else : ?>
    <p class="text-muted small mb-0"><?=$this->getTrans('noWars') ?></p>
<?php endif; ?>
