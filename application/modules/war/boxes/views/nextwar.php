<?php

/** @var \Ilch\View $this */

/** @var \Modules\War\Mappers\War $warMapper */
$warMapper = $this->get('warMapper');
/** @var array $gameIconMap */
$gameIconMap = $this->get('gameIconMap') ?? [];

$displayed = 0;
?>

<link href="<?=$this->getBoxUrl('static/css/style.css') ?>" rel="stylesheet">

<?php if ($this->get('wars') != '') :
    /** @var \Modules\War\Models\War $war */
    foreach ($this->get('wars') as $war) :
        $displayed++;

        $iconFilename = null;
        foreach ($gameIconMap as $game) {
            if ($game['title'] == $war->getWarGame()) {
                $iconFilename = $game['icon'];
                break;
            }
        }
        ?>
        <div class="nextwar-box">
            <div class="row">
                <a href="<?=$this->getUrl('war/index/show/id/' . $war->getId()) ?>" title="<?=$this->escape($war->getWarGroupTag()) . ' ' . $this->getTrans('vs') . ' ' . $this->escape($war->getWarEnemyTag()) ?>">
                    <div class="col-4 ellipsis">
                        <?php if ($iconFilename !== null) : ?>
                            <img src="<?=$this->getBoxUrl('static/img/' . $iconFilename . '.png') ?>" title="<?=$this->escape($war->getWarGame()) ?>" width="16" height="16" alt="<?=$this->escape($war->getWarGame()) ?>">
                        <?php else : ?>
                            <i class="fa-solid fa-gamepad text-muted" title="<?=$this->escape($war->getWarGame()) ?>"></i>
                        <?php endif; ?>
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
                    <div class="ellipsis-item text-end">
                        <i><?=$warMapper->countdown($war->getWarTime()) ?></i>
                    </div>
                </div>
            </div>
        </div>
    <?php endforeach; ?>
<?php endif; ?>

<?php if (!$displayed) : ?>
    <?=$this->getTrans('noWars') ?>
<?php endif; ?>
