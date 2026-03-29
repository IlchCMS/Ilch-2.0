<?php

/** @var \Ilch\View $this */
/** @var \Modules\War\Models\GameIcon[] $icons */
$icons = $this->get('icons');
?>
<h1><?=$this->getTrans('menuGameIcons') ?></h1>

<?php if ($icons) : ?>
    <form method="POST" action="">
        <?=$this->getTokenField() ?>
        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead class="table-light">
                    <tr>
                        <th style="width: 36px;"><?=$this->getCheckAllCheckbox('check_icons') ?></th>
                        <th style="width: 80px;"><?=$this->getTrans('gameIcon') ?></th>
                        <th><?=$this->getTrans('nextWarGame') ?></th>
                        <th style="width: 80px;"></th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    /** @var \Modules\War\Models\GameIcon $gameIcon */
                    foreach ($icons as $gameIcon) : ?>
                        <tr>
                            <td><?=$this->getDeleteCheckbox('check_icons', $gameIcon->getId()) ?></td>
                            <td>
                                <?php if (!empty($gameIcon->getIcon())) : ?>
                                    <img src="<?=$this->getBaseUrl('application/modules/war/static/img/' . $gameIcon->getIcon() . '.png') ?>"
                                         alt="<?=$this->escape($gameIcon->getTitle()) ?>"
                                         style="max-width: 48px; max-height: 48px; image-rendering: pixelated; image-rendering: crisp-edges;">
                                <?php else : ?>
                                    <div style="width: 48px; height: 48px; display: flex; align-items: center; justify-content: center; background: rgba(0,0,0,.05); border-radius: 6px;">
                                        <i class="fa-solid fa-gamepad text-muted"></i>
                                    </div>
                                <?php endif; ?>
                            </td>
                            <td class="fw-semibold"><?=$this->escape($gameIcon->getTitle()) ?></td>
                            <td>
                                <div class="d-flex gap-1">
                                    <?=$this->getEditIcon(['action' => 'treat', 'id' => $gameIcon->getId()]) ?>
                                    <?=$this->getDeleteIcon(['action' => 'del', 'id' => $gameIcon->getId()]) ?>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        <?=$this->getListBar(['delete' => 'delete']) ?>
    </form>
<?php else : ?>
    <p class="text-muted"><?=$this->getTranslator()->trans('noMaps') ?></p>
<?php endif; ?>
