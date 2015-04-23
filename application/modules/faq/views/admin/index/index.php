<?php
    $categories = $this->get('categorys');
    $faqs = $this->get('faqs');
?>

<legend><?=$this->getTrans('manage') ?></legend>
<form class="form-horizontal" method="POST" action="">
    <?php if ($categories != ''): ?>
        <?=$this->getTokenField() ?>
            <div class="table-responsive">
                <table class="table table-hover table-striped">
                    <colgroup>
                        <col class="icon_width">
                        <col class="icon_width">
                        <col class="icon_width">
                        <col class="col-lg-11">
                        <col />
                    </colgroup>
                    <tr>
                        <th><?=$this->getCheckAllCheckbox('check_cats') ?></th>
                        <th></th>
                        <th></th>
                        <th><?=$this->getTrans('cat') ?></th>
                        <th style="text-align:center"><?=$this->getTrans('count') ?></th>
                    </tr>
                    <?php foreach ($this->get('categorys') as $category): ?>
                        <?php $faqMappers = new Modules\Faq\Mappers\Faq(); ?>
                        <tr>
                            <td><input value="<?=$category->getId() ?>" type="checkbox" name="check_cats[]" /></td>
                            <td><?=$this->getEditIcon(array('action' => 'treatCat', 'id' => $category->getId())) ?></td>
                            <td><?=$this->getDeleteIcon(array('action' => 'delCat', 'id' => $category->getId())) ?></td>
                            <td><a href="<?=$this->getUrl(array('action' => 'index', 'catId' => $category->getId())) ?>" title="<?=$this->escape($category->getTitle()) ?>"><?=$this->escape($category->getTitle()) ?></a></td>
                            <td align="center"><?=count($faqMappers->getFaqsByCatId($category->getId())) ?></td>
                        </tr>
                    <?php endforeach; ?>
                </table>
                <br />
            </div>
    <?php endif; ?>
    <?php if ($faqs != ''): ?>
            <div class="table-responsive">
                <table class="table table-hover table-striped">
                    <colgroup>
                        <col class="icon_width">
                        <col class="icon_width">
                        <col class="icon_width">
                        <col class="col-lg-12">
                    </colgroup>
                    <tr>
                        <th><?=$this->getCheckAllCheckbox('check_faqs')?></th>
                        <th></th>
                        <th></th>
                        <th><?php echo $this->getTrans('title'); ?></th>
                    </tr>
                    <?php foreach ($faqs as $faq): ?>
                        <tr>
                            <td><input value="<?=$faq->getId()?>" type="checkbox" name="check_faqs[]" /></td>
                            <td><?=$this->getEditIcon(array('action' => 'treat', 'id' => $faq->getId()))?></td>
                            <td><?=$this->getDeleteIcon(array('action' => 'delfaq', 'id' => $faq->getId()))?></td>
                            <td><?=$faq->getTitle()?></td>
                        </tr>
                    <?php endforeach; ?>
                </table>
            </div>
    <?php else: ?>
        <?=$this->getTrans('noFaqs') ?>
    <?php endif; ?>
    <?=$this->getListBar(array('delete' => 'delete')) ?>
</form>
