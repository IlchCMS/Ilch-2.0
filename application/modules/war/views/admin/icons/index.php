<?php

/** @var \Ilch\View $this */
?>
<h1><?=$this->getTrans('menuGameIcons') ?></h1>
<?php if ($this->get('icons')) : ?>
    <form method="POST" action="">
        <?=$this->getTokenField() ?>
        <div class="table-responsive">
            <table class="table table-striped table-hover">
                <colgroup>
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
                        <th><?=$this->getTrans('nextWarGame') ?></th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    /** @var \Modules\War\Models\GameIcon $game */
                    foreach ($this->get('icons') as $game) : ?>
                        <tr>
                            <td><?=$this->getDeleteCheckbox('check_icons', $game->getId()) ?></td>
                            <td><?=$this->getEditIcon(['action' => 'treat', 'id' => $game->getId()]) ?></td>
                            <td><?=$this->getDeleteIcon(['action' => 'del', 'id' => $game->getId()]) ?></td>
                            <td><?=$this->escape($game->getTitle()) ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        <?=$this->getListBar(['delete' => 'delete']) ?>
    </form>
<?php else : ?>
    <?=$this->getTranslator()->trans('noGameIcons') ?>
<?php endif; ?>
