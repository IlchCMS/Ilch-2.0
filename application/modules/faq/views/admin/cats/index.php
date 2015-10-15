<legend><?=$this->getTrans('menuCats') ?></legend>
<?php if ($this->get('cats') != ''): ?>
    <form class="form-horizontal" method="POST" action="">
        <?=$this->getTokenField() ?>
        <table class="table table-hover">
            <colgroup>
                <col class="icon_width" />
                <col class="icon_width" />
                <col class="icon_width" />
                <col class="icon_width" />
                <col class="col" />
            </colgroup>
            <tr>
                <th><?=$this->getCheckAllCheckbox('check_cats') ?></th>
                <th></th>
                <th></th>
                <th><?=$this->getTrans('entries') ?></th>
                <th><?=$this->getTrans('title') ?></th>
            </tr>
            <?php foreach ($this->get('cats') as $cat): ?>
                <?php $faqMapper = new Modules\Faq\Mappers\Faq(); ?>
                <?php $countFaqs = count($faqMapper->getFaqs(array('cat_id' => $cat->getId()))); ?>
                <tr>
                    <td><input value="<?=$cat->getId() ?>" type="checkbox" name="check_cats[]" /></td>
                    <td><?=$this->getEditIcon(array('action' => 'treat', 'id' => $cat->getId())) ?></td>
                    <td><?=$this->getDeleteIcon(array('action' => 'delcat', 'id' => $cat->getId())) ?></td>
                    <td align="center"><?=$countFaqs ?></td>
                    <td><?=$cat->getTitle() ?></td>
                </tr>
            <?php endforeach; ?>
        </table>
        <?=$this->getListBar(array('delete' => 'delete')) ?>
    </form>
<?php else: ?>
    <?=$this->getTrans('noCategory') ?>
<?php endif; ?>
