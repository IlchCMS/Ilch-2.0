<?php

/** @var \Ilch\View $this */

/** @var Modules\Faq\Mappers\Category $categoryMapper */
$categoryMapper = $this->get('categoryMapper');

/** @var Modules\Faq\Models\Faq $faqs */
$faqs = $this->get('faqs');
?>
<h1><?=$this->getTrans('manage') ?></h1>
<?php if ($faqs) : ?>
    <form class="form-horizontal" method="POST" action="">
        <?=$this->getTokenField() ?>
        <div class="table-responsive">
            <table class="table table-hover table-striped">
                <colgroup>
                    <col class="icon_width">
                    <col class="icon_width">
                    <col class="icon_width">
                    <col class="col-xl-2">
                    <col>
                </colgroup>
                <thead>
                    <tr>
                        <th><?=$this->getCheckAllCheckbox('check_faqs') ?></th>
                        <th></th>
                        <th></th>
                        <th><?=$this->getTrans('cat') ?></th>
                        <th><?=$this->getTrans('question') ?></th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($faqs as $faq) : ?>
                        <?php $faqsCats = $categoryMapper->getCategoryById($faq->getCatId(), null); ?>
                        <tr>
                            <td><?=$this->getDeleteCheckbox('check_faqs', $faq->getId()) ?></td>
                            <td><?=$this->getEditIcon(['action' => 'treat', 'id' => $faq->getId()]) ?></td>
                            <td><?=$this->getDeleteIcon(['action' => 'delfaq', 'id' => $faq->getId()]) ?></td>
                            <td><?=$this->escape($faqsCats->getTitle()) ?></td>
                            <td><?=$this->escape($faq->getQuestion()) ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        <?=$this->getListBar(['delete' => 'delete']) ?>
    </form>
<?php else : ?>
    <?=$this->getTrans('noFaqs') ?>
<?php endif; ?>
