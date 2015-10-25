<legend><?=$this->getTrans('menuCats') ?></legend>
<?php if ($this->get('cats') != ''): ?>
    <form class="form-horizontal" method="POST" action="">
        <?=$this->getTokenField() ?>
        <div class="table-responsive">
            <table class="table table-hover table-striped">
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
                        <th><?=$this->getTrans('title') ?></th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($this->get('cats') as $cat): ?>
                        <tr>
                            <td><input value="<?=$cat->getId() ?>" type="checkbox" name="check_cats[]" /></td>
                            <td><?=$this->getEditIcon(array('action' => 'treat', 'id' => $cat->getId())) ?></td>
                            <td><?=$this->getDeleteIcon(array('action' => 'delcat', 'id' => $cat->getId())) ?></td>
                            <td><?=$cat->getName() ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        <?=$this->getListBar(array('delete' => 'delete')) ?>
    </form>
<?php else: ?>
    <?=$this->getTranslator()->trans('noCats') ?>
<?php endif; ?>
