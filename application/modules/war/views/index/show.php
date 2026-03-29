<?php

/** @var \Ilch\View $this */

use Ilch\Date;

$commentsClass = new Ilch\Comments();

/** @var \Modules\War\Models\War $war */
$war = $this->get('war');
/** @var \Modules\War\Models\Group $group */
$group = $this->get('group');
/** @var \Modules\War\Models\Enemy $enemy */
$enemy = $this->get('enemy');
/** @var int[]|null $userGroupIds */
$userGroupIds = $this->get('userGroupIds');
/** @var \Modules\War\Models\Accept|null $acceptArray */
$acceptArray = $this->get('accept');
/** @var \Modules\War\Models\Accept[]|null $acceptCheckArray */
$acceptCheckArray = $this->get('acceptCheck');
/** @var \Modules\War\Models\Games[]|null $games */
$games = $this->get('games');
/** @var \Modules\User\Mappers\User $userMapper */
$userMapper = $this->get('userMapper');
/** @var \Modules\War\Mappers\Maps $mapsMapper */
$mapsMapper = $this->get('mapsMapper');
/** @var \Ilch\Date $datenow */
$datenow = $this->get('datenow');
/** @var array<string, string> $gameIconMap */
$gameIconMap = $this->get('gameIconMap') ?? [];

// Geplante Maps aus war.maps (kommagetrennte IDs) auflösen
$plannedMapIds = [];
if ($war->getWarMaps() !== '') {
    foreach (explode(',', $war->getWarMaps()) as $rawId) {
        $id = (int) $rawId;
        if ($id > 0) {
            $plannedMapIds[] = $id;
        }
    }
}

// Gespielte Runden nach Map-ID indizieren
$gamesByMapId = [];
foreach ($games ?? [] as $game) {
    $gamesByMapId[$game->getMap()] = $game;
}

// Gesamtergebnis berechnen
$groupPoints = 0;
$enemyPoints = 0;
foreach ($games ?? [] as $game) {
    $groupPoints += $game->getGroupPoints();
    $enemyPoints += $game->getEnemyPoints();
}
$resultLabel = '';
$resultBadgeClass = '';
if ($groupPoints > 0 || $enemyPoints > 0) {
    if ($groupPoints > $enemyPoints) {
        $resultBadgeClass = 'bg-success';
        $resultLabel = $this->getTrans('warWin');
    } elseif ($groupPoints < $enemyPoints) {
        $resultBadgeClass = 'bg-danger';
        $resultLabel = $this->getTrans('warLost');
    } else {
        $resultBadgeClass = 'bg-primary';
        $resultLabel = $this->getTrans('warDrawn');
    }
}

$iconFilename = $gameIconMap[$war->getWarGame()] ?? null;
?>

<link href="<?=$this->getModuleUrl('static/css/style.css') ?>" rel="stylesheet">
<link href="<?=$this->getModuleUrl('../comment/static/css/comment.css') ?>" rel="stylesheet">

<!-- ═══════════════════════════════════════════════════
     Section 1 – VS-Hero
     ═══════════════════════════════════════════════════ -->
<div class="war-hero mb-4">
    <div class="row align-items-center text-center g-0">
        <div class="col-5">
            <img class="war-hero-img img-fluid"
                 src="<?=$this->getBaseUrl($group && $group->getGroupImage() !== '' ? $group->getGroupImage() : 'application/modules/media/static/img/nomedia.png') ?>"
                 alt="<?=$this->escape($group ? $group->getGroupName() : '') ?>">
            <h5 class="mt-2 fw-semibold"><?=$this->escape($group ? $group->getGroupName() : '') ?></h5>
        </div>
        <div class="col-2 d-flex flex-column align-items-center gap-2">
            <div class="war-hero-vs">VS</div>
            <?php if ($iconFilename !== null) : ?>
                <div class="war-hero-game">
                    <img class="war-hero-game-icon"
                         src="<?=$this->getModuleUrl('static/img/' . $iconFilename . '.png') ?>"
                         alt="<?=$this->escape($war->getWarGame()) ?>">
                    <div class="war-hero-game-name"><?=$this->escape($war->getWarGame()) ?></div>
                </div>
            <?php elseif ($war->getWarGame() !== '') : ?>
                <div class="war-hero-game">
                    <div class="war-hero-game-fallback">
                        <i class="fa-solid fa-gamepad"></i>
                    </div>
                    <div class="war-hero-game-name"><?=$this->escape($war->getWarGame()) ?></div>
                </div>
            <?php endif; ?>
        </div>
        <div class="col-5">
            <img class="war-hero-img img-fluid"
                 src="<?=$this->getBaseUrl($enemy && $enemy->getEnemyImage() !== '' ? $enemy->getEnemyImage() : 'application/modules/media/static/img/nomedia.png') ?>"
                 alt="<?=$this->escape($enemy ? $enemy->getEnemyName() : '') ?>">
            <h5 class="mt-2 fw-semibold"><?=$this->escape($enemy ? $enemy->getEnemyName() : '') ?></h5>
        </div>
    </div>

    <div class="d-flex justify-content-center flex-wrap gap-2 mt-3">
        <?php if ($war->getWarStatus() == '1') : ?>
            <span class="badge rounded-pill bg-warning text-dark">
                <i class="fa-solid fa-circle-dot me-1"></i><?=$this->getTrans('warStatusOpen') ?>
            </span>
        <?php elseif ($war->getWarStatus() == '2') : ?>
            <span class="badge rounded-pill bg-secondary">
                <i class="fa-solid fa-circle-check me-1"></i><?=$this->getTrans('warStatusClose') ?>
            </span>
        <?php endif; ?>
        <?php if ($war->getWarGame() !== '') : ?>
            <span class="badge rounded-pill bg-dark">
                <i class="fa-solid fa-gamepad me-1"></i><?=$this->escape($war->getWarGame()) ?>
            </span>
        <?php endif; ?>
        <?php if ($war->getWarXonx() !== '') : ?>
            <span class="badge rounded-pill bg-dark">
                <i class="fa-solid fa-users me-1"></i><?=$this->escape($war->getWarXonx()) ?>
            </span>
        <?php endif; ?>
        <?php if ($war->getWarMatchtype() !== '') : ?>
            <span class="badge rounded-pill bg-dark">
                <i class="fa-solid fa-trophy me-1"></i><?=$this->escape($war->getWarMatchtype()) ?>
            </span>
        <?php endif; ?>
    </div>
</div>

<!-- ═══════════════════════════════════════════════════
     Section 2 – War-Infos
     ═══════════════════════════════════════════════════ -->
<div class="row g-3 mb-3">
    <div class="col-sm-6 col-xl-3">
        <div class="war-info-tile">
            <div class="war-info-label"><?=$this->getTrans('nextWarTime') ?></div>
            <div class="war-info-value">
                <?php
                $dateObj = new Date($war->getWarTime());
                echo $dateObj->format('d.m.Y', true);
                ?>
            </div>
            <div class="war-info-sub"><?=$dateObj->format('H:i', true) ?> Uhr</div>
        </div>
    </div>
    <?php if ($resultLabel !== '') : ?>
    <div class="col-sm-6 col-xl-3">
        <div class="war-info-tile">
            <div class="war-info-label"><?=$this->getTrans('warResult') ?></div>
            <div class="war-info-value"><?=$groupPoints ?>:<?=$enemyPoints ?></div>
            <div class="war-info-sub">
                <span class="badge rounded-pill <?=$resultBadgeClass ?>"><?=$resultLabel ?></span>
            </div>
        </div>
    </div>
    <?php endif; ?>
    <?php if ($war->getWarServer() !== '') : ?>
    <div class="col-sm-6 col-xl-3">
        <div class="war-info-tile">
            <div class="war-info-label"><?=$this->getTrans('warServer') ?></div>
            <div class="war-info-value text-truncate"><?=$this->escape($war->getWarServer()) ?></div>
            <?php if ($war->getWarPassword() !== '') : ?>
                <div class="war-info-sub"><?=$this->getTrans('warPassword') ?>: <?=$this->escape($war->getWarPassword()) ?></div>
            <?php endif; ?>
        </div>
    </div>
    <?php endif; ?>
</div>

<!-- ═══════════════════════════════════════════════════
     Section 3 – Maps & Ergebnis
     ═══════════════════════════════════════════════════ -->
<div class="card mb-3">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h6 class="mb-0">
            <i class="fa-solid fa-map me-2"></i><?=$this->getTrans('warMap') ?>
        </h6>
        <?php if ($resultLabel !== '') : ?>
            <span class="fw-bold">
                <?=$groupPoints ?>:<?=$enemyPoints ?>
                <span class="badge rounded-pill <?=$resultBadgeClass ?> ms-1"><?=$resultLabel ?></span>
            </span>
        <?php endif; ?>
    </div>
    <div class="card-body p-0">
        <?php if (!empty($plannedMapIds)) : ?>
            <ul class="list-group list-group-flush">
                <?php foreach ($plannedMapIds as $mapId) :
                    $mapModel = $mapsMapper->getEntryById($mapId);
                    $playedGame = $gamesByMapId[$mapId] ?? null;
                ?>
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        <span><?=$this->escape($mapModel ? $mapModel->getName() : '#' . $mapId) ?></span>
                        <?php if ($playedGame !== null) : ?>
                            <?php
                            $gp = $playedGame->getGroupPoints();
                            $ep = $playedGame->getEnemyPoints();
                            if ($gp > $ep) {
                                $mapBadge = 'bg-success';
                            } elseif ($gp < $ep) {
                                $mapBadge = 'bg-danger';
                            } else {
                                $mapBadge = 'bg-secondary';
                            }
                            ?>
                            <span class="badge rounded-pill <?=$mapBadge ?>"><?=$gp ?> : <?=$ep ?></span>
                        <?php else : ?>
                            <span class="text-muted small">–</span>
                        <?php endif; ?>
                    </li>
                <?php endforeach; ?>
            </ul>
        <?php else : ?>
            <p class="text-muted small m-3"><?=$this->getTrans('noMaps') ?></p>
        <?php endif; ?>
    </div>
</div>

<!-- ═══════════════════════════════════════════════════
     Section 4 – Teilnahme
     ═══════════════════════════════════════════════════ -->
<?php if ($userGroupIds) : ?>
<div class="card mb-3">
    <div class="card-header">
        <h6 class="mb-0">
            <i class="fa-solid fa-users me-2"></i><?=$this->getTrans('warAccept') ?>
        </h6>
    </div>
    <div class="card-body">
        <form id="accept_form" method="POST" action="">
            <?=$this->getTokenField() ?>
            <ul class="list-group list-group-flush mb-3">
                <?php foreach ($acceptCheckArray ?? [] as $acceptCheck) :
                    $user = $userMapper->getUserById($acceptCheck->getUserId()) ?? $userMapper->getDummyUser();

                    if ($acceptCheck->getAccept() == '1') {
                        $badgeClass = 'bg-success';
                        $badgeText  = $this->getTrans('accepted');
                    } elseif ($acceptCheck->getAccept() == '2') {
                        $badgeClass = 'bg-danger';
                        $badgeText  = $this->getTrans('declined');
                    } elseif ($acceptCheck->getAccept() == '3') {
                        $badgeClass = 'bg-warning text-dark';
                        $badgeText  = $this->getTrans('undecided');
                    } else {
                        $badgeClass = 'bg-light text-dark border';
                        $badgeText  = '';
                    }
                    $comment = $acceptCheck->getComment() ? $this->escape($acceptCheck->getComment()) : '';
                ?>
                    <li class="list-group-item">
                        <div class="d-flex justify-content-between align-items-center">
                            <a href="<?=$this->getUrl(['module' => 'user', 'controller' => 'profil', 'action' => 'index', 'user' => $user->getId()]) ?>">
                                <?=$user->getName() ?>
                            </a>
                            <div class="d-flex align-items-center gap-2">
                                <?php if ($badgeText !== '') : ?>
                                    <span class="badge rounded-pill <?=$badgeClass ?>"><?=$badgeText ?></span>
                                <?php endif; ?>
                                <i class="fa-solid fa-circle-info text-muted"
                                   style="cursor: default;"
                                   data-bs-toggle="tooltip"
                                   data-bs-title="<?=(new Date($acceptCheck->getDateCreated()))->format('d.m.Y H:i') ?>"></i>
                            </div>
                        </div>
                        <?php if ($comment !== '') : ?>
                            <small class="text-muted"><?=$comment ?></small>
                        <?php endif; ?>
                    </li>
                <?php endforeach; ?>
            </ul>

            <?php
            $datenow = $datenow->getTimestamp();
            $wartime = (new Date($war->getWarTime()))->getTimestamp();
            ?>
            <?php if (($war->getLastAcceptTime() == 0 || ((($wartime - $datenow) / 60) >= $war->getLastAcceptTime())) && $war->getWarStatus() == 1) : ?>
                <?php foreach ($userGroupIds as $userGroupId) :
                    $user = $userMapper->getUserById($userGroupId) ?? $userMapper->getDummyUser();
                ?>
                    <?php if ($this->getUser() && $user->getId() == $this->getUser()->getId()) : ?>
                        <div class="row g-2 align-items-start">
                            <div class="col-sm-4">
                                <select class="form-select<?=$this->validation()->hasError('warAccept') ? ' is-invalid' : '' ?>"
                                        id="warAccept" name="warAccept">
                                    <optgroup label="<?=$this->getTrans('choose') ?>">
                                        <option value="1" <?=($acceptArray ? $acceptArray->getAccept() : '') == 1 ? 'selected=""' : '' ?>><?=$this->getTrans('accept') ?></option>
                                        <option value="2" <?=($acceptArray ? $acceptArray->getAccept() : '') == 2 ? 'selected=""' : '' ?>><?=$this->getTrans('decline') ?></option>
                                        <option value="3" <?=($acceptArray ? $acceptArray->getAccept() : '') == 3 ? 'selected=""' : '' ?>><?=$this->getTrans('undecided') ?></option>
                                    </optgroup>
                                </select>
                            </div>
                            <div class="col-sm-6">
                                <textarea class="form-control<?=$this->validation()->hasError('warComment') ? ' is-invalid' : '' ?>"
                                          style="resize: vertical;"
                                          name="warComment"
                                          id="warComment"
                                          rows="2"></textarea>
                            </div>
                            <div class="col-sm-2">
                                <button type="submit" class="btn btn-outline-secondary w-100" name="save" value="save">
                                    <?=$this->getTrans('save') ?>
                                </button>
                            </div>
                        </div>
                    <?php endif; ?>
                <?php endforeach; ?>
            <?php endif; ?>
        </form>
    </div>
</div>
<?php endif; ?>

<!-- ═══════════════════════════════════════════════════
     Section 5 – Bericht
     ═══════════════════════════════════════════════════ -->
<?php if ($war->getWarReport() !== '') : ?>
<div class="card mb-3">
    <div class="card-header">
        <h6 class="mb-0">
            <i class="fa-solid fa-file-lines me-2"></i><?=$this->getTrans('warReport') ?>
        </h6>
    </div>
    <div class="card-body ck-content">
        <?=$this->purify($war->getWarReport()) ?>
    </div>
</div>
<?php endif; ?>

<!-- ═══════════════════════════════════════════════════
     Section 6 – Kommentare
     ═══════════════════════════════════════════════════ -->
<?= $commentsClass->getComments($this->get('commentsKey'), $war, $this) ?>
