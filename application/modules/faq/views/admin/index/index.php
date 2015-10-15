<?php
    $faqs = $this->get('faqs');
    $categoryMapper = new \Modules\Faq\Mappers\Category();
?>

<legend><?=$this->getTrans('manage') ?></legend>
<?php if ($faqs != ''): ?>
    <form class="form-horizontal" method="POST" action="">
        <div class="table-responsive">
            <table class="table table-hover table-striped">
                <colgroup>
                    <col class="icon_width">
                    <col class="icon_width">
                    <col class="icon_width">
                    <col class="col-lg-2">
                    <col />
                </colgroup>
                <tr>
                    <th><?=$this->getCheckAllCheckbox('check_faqs')?></th>
                    <th></th>
                    <th></th>
                    <th><?=$this->getTrans('cat') ?></th>
                    <th><?=$this->getTrans('question') ?></th>
                </tr>
                <?php foreach ($faqs as $faq): ?>
                    <?php $faqsCats = $categoryMapper->getCategoryById($faq->getCatId()); ?>
                    <tr>
                        <td><input value="<?=$faq->getId()?>" type="checkbox" name="check_faqs[]" /></td>
                        <td><?=$this->getEditIcon(array('action' => 'treat', 'id' => $faq->getId()))?></td>
                        <td><?=$this->getDeleteIcon(array('action' => 'delfaq', 'id' => $faq->getId()))?></td>
                        <td><?=$faqsCats->getTitle() ?></td>
                        <td><?=$faq->getQuestion()?></td>
                    </tr>
                <?php endforeach; ?>
            </table>
        </div>
        <?=$this->getListBar(array('delete' => 'delete')) ?>
    </form>
<?php else: ?>
    <?=$this->getTrans('noFaqs') ?>
<?php endif; ?>
