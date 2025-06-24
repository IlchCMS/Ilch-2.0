<?php

/** @var \Ilch\View $this */
?>
<h1><?=$this->getTrans('cats') ?></h1>
<?php if (!empty($this->get('cats'))): ?>
<form method="POST">
    <?=$this->getTokenField() ?>
    <table class="table table-hover">
        <colgroup>
            <col class="icon_width">
            <col class="icon_width">
            <col class="icon_width">
            <col>
        </colgroup>
        <thead>
            <tr>
                <th><?=$this->getCheckAllCheckbox('check_cats') ?></th>
                <th></th>
                <th></th>
                <th><?=$this->getTrans('catTitle') ?></th>
            </tr>
        </thead>
        <tbody>
            <?php
            /** @var \Modules\Media\Models\Media $cat */
            foreach ($this->get('cats') as $cat): ?>
                <tr>
                    <td><?=$this->getDeleteCheckbox('check_cats', $cat->getId()) ?></td>
                    <td><?=$this->getEditIcon(['action' => 'treat', 'id' => $cat->getId()]) ?></td>
                    <td><?=$this->getDeleteIcon(['action' => 'delcat', 'id' => $cat->getId()]) ?></td>
                    <td><?=$this->escape($cat->getCatName()) ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    <?=$this->getListBar(['delete' => 'delete']) ?>
</form>
<?php else: ?>
    <?=$this->getTranslator()->trans('noCats') ?>
<?php endif; ?>
