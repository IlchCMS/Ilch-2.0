<legend><?=$this->getTrans('cats') ?></legend>
<?php if ($this->get('cats') != ''): ?>
<form class="form-horizontal" method="POST" action="">
    <?=$this->getTokenField() ?>
    <table class="table table-hover">
        <colgroup>
            <col class="icon_width" />
            <col class="icon_width" />
            <col class="icon_width" />
            <col class="col" />
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
            <?php foreach ($this->get('cats') as $cat): ?>
                <tr>
                    <td><input type="checkbox" name="check_cats[]" value="<?=$cat->getId() ?>" /></td>
                    <td><?=$this->getEditIcon(['action' => 'treat', 'id' => $cat->getId()]) ?></td>
                    <td><?=$this->getDeleteIcon(['action' => 'delcat', 'id' => $cat->getId()]) ?></td>
                    <td><?=$cat->getCatName() ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    <?=$this->getListBar(['delete' => 'delete']) ?>
</form>
<?php else: ?>
    <?=$this->getTranslator()->trans('noCats') ?>
<?php endif; ?>
