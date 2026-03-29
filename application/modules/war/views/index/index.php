<?php

/** @var \Ilch\View $this */

use Ilch\Date;

/** @var \Ilch\Pagination $pagination */
$pagination = $this->get('pagination');
/** @var \Modules\War\Mappers\Games $gamesMapper */
$gamesMapper = $this->get('gamesMapper');
/** @var \Modules\War\Mappers\Enemy $enemyMapper */
$enemyMapper = $this->get('enemyMapper');
/** @var \Modules\War\Mappers\Group $groupMapper */
$groupMapper = $this->get('groupMapper');
/** @var array<string, string> $gameIconMap */
$gameIconMap = $this->get('gameIconMap') ?? [];
/** @var array<int, string> $mapNamesById */
$mapNamesById = $this->get('mapNamesById') ?? [];
?>

<link href="<?=$this->getBaseUrl('application/modules/war/static/css/style.css') ?>" rel="stylesheet">

<div class="d-flex justify-content-between align-items-center mb-3">
    <h1 class="mb-0"><?=$this->getTrans('warsOverview') ?></h1>
    <a class="btn btn-outline-secondary btn-sm" href="<?=$this->getUrl(['controller' => 'group', 'action' => 'index']) ?>">
        <?=$this->getTrans('toGroups') ?>
    </a>
</div>

<?php if ($this->get('wars')) : ?>
    <?=$pagination->getHtml($this, []) ?>
    <div class="table-responsive">
        <table class="table table-hover align-middle">
            <thead class="table-light">
                <tr>
                    <th style="width: 32px;"></th>
                    <th><?=$this->getTrans('enemyName') ?></th>
                    <th><?=$this->getTrans('groupName') ?></th>
                    <th><?=$this->getTrans('warMap') ?></th>
                    <th><?=$this->getTrans('nextWarTime') ?></th>
                    <th><?=$this->getTrans('warStatus') ?></th>
                    <th><?=$this->getTrans('warResult') ?></th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                <?php
                /** @var \Modules\War\Models\War $war */
                foreach ($this->get('wars') as $war) :
                    $date = new Date($war->getWarTime());
                    $enemy = $enemyMapper->getEnemyById($war->getWarEnemy());
                    $group = $groupMapper->getGroupById($war->getWarGroup());

                    $games = $gamesMapper->getGamesByWarId($war->getId());
                    $enemyPoints = 0;
                    $groupPoints = 0;
                    $resultBadgeClass = '';
                    $hasResult = false;
                    if ($games) {
                        foreach ($games as $game) {
                            $groupPoints += $game->getGroupPoints();
                            $enemyPoints += $game->getEnemyPoints();
                        }
                        if ($groupPoints > 0 || $enemyPoints > 0) {
                            $hasResult = true;
                            if ($groupPoints > $enemyPoints) {
                                $resultBadgeClass = 'bg-success';
                            } elseif ($groupPoints < $enemyPoints) {
                                $resultBadgeClass = 'bg-danger';
                            } else {
                                $resultBadgeClass = 'bg-primary';
                            }
                        }
                    }

                    $iconFilename = $gameIconMap[$war->getWarGame()] ?? null;

                    $mapNames = [];
                    if ($war->getWarMaps() !== '') {
                        foreach (explode(',', $war->getWarMaps()) as $mapId) {
                            $mapId = (int) $mapId;
                            if ($mapId > 0 && isset($mapNamesById[$mapId])) {
                                $mapNames[] = $mapNamesById[$mapId];
                            }
                        }
                    }
                ?>
                <tr>
                    <td>
                        <div class="war-box-icon">
                            <?php if ($iconFilename !== null) : ?>
                                <img src="<?=$this->getBaseUrl('application/modules/war/static/img/' . $iconFilename . '.png') ?>"
                                     width="14" height="14"
                                     alt="<?=$this->escape($war->getWarGame()) ?>"
                                     title="<?=$this->escape($war->getWarGame()) ?>">
                            <?php else : ?>
                                <i class="fa-solid fa-gamepad" title="<?=$this->escape($war->getWarGame()) ?>"></i>
                            <?php endif; ?>
                        </div>
                    </td>
                    <td class="fw-semibold"><?=$this->escape($enemy ? $enemy->getEnemyName() : '') ?></td>
                    <td><?=$this->escape($group ? $group->getGroupName() : '') ?></td>
                    <td>
                        <?php if (!empty($mapNames)) : ?>
                            <span class="text-muted small"><?=$this->escape(implode(', ', $mapNames)) ?></span>
                        <?php else : ?>
                            <span class="text-muted">–</span>
                        <?php endif; ?>
                    </td>
                    <td class="text-muted small"><?=$date->format('d.m.Y H:i', true) ?></td>
                    <td>
                        <?php if ($war->getWarStatus() == '1') : ?>
                            <span class="badge rounded-pill bg-warning text-dark"><?=$this->getTrans('warStatusOpen') ?></span>
                        <?php elseif ($war->getWarStatus() == '2') : ?>
                            <span class="badge rounded-pill bg-secondary"><?=$this->getTrans('warStatusClose') ?></span>
                        <?php endif; ?>
                    </td>
                    <td>
                        <?php if ($hasResult) : ?>
                            <span class="badge rounded-pill <?=$resultBadgeClass ?>"><?=$groupPoints ?>:<?=$enemyPoints ?></span>
                        <?php else : ?>
                            <span class="text-muted">–</span>
                        <?php endif; ?>
                    </td>
                    <td>
                        <a class="btn btn-outline-secondary btn-sm" href="<?=$this->getUrl(['action' => 'show', 'id' => $war->getId()]) ?>">
                            <?=$this->getTrans('warReportShow') ?>
                        </a>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    <?=$pagination->getHtml($this, []) ?>
<?php else : ?>
    <p class="text-muted"><?=$this->getTranslator()->trans('noWars') ?></p>
<?php endif; ?>
