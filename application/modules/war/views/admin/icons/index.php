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
            <table class="table table-striped table-hover">
                <colgroup>
                    <col class="icon_width" />
                    <col class="icon_width" />
                    <col class="icon_width" />
                    <col class="icon_width" />
                    <col />
                </colgroup>
                <thead>
                    <tr>
                        <th><?=$this->getCheckAllCheckbox('check_icons') ?></th>
                        <th></th>
                        <th></th>
                        <th></th>
                        <th><?=$this->getTrans('nextWarGame') ?></th>
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
                                    <img src="<?=$this->getBaseUrl('application/modules/war/static/img/' . $gameIcon->getIcon() . '.png') ?>" alt="<?=$this->escape($gameIcon->getTitle()) ?>" width="16" height="16">
                                <?php endif; ?>
                            </td>
                            <td><?=$this->getEditIcon(['action' => 'treat', 'id' => $gameIcon->getId()]) ?></td>
                            <td><?=$this->getDeleteIcon(['action' => 'del', 'id' => $gameIcon->getId()]) ?></td>
                            <td><?=$this->escape($gameIcon->getTitle()) ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        <?=$this->getListBar(['delete' => 'delete']) ?>
    </form>
<?php else : ?>
    <?=$this->getTranslator()->trans('noMaps') ?>
<?php endif; ?>
