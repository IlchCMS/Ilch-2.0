<?php

/** @var \Ilch\View $this */

/** @var Modules\Faq\Models\Faq|null $faq */
$faq = $this->get('faq');

/** @var Modules\Faq\Models\Category[]|null $cats */
$cats = $this->get('cats');
?>

<h1>
    <?=($faq) ? $this->getTrans('edit') : $this->getTrans('add') ?>
</h1>
<?php if ($cats) : ?>
    <form class="form-horizontal" method="POST" action="">
        <?=$this->getTokenField() ?>
        <div class="row form-group ilch-margin-b <?=$this->validation()->hasError('catId') ? 'has-error' : '' ?>">
            <label for="catId" class="col-lg-2 control-label">
                <?=$this->getTrans('cat') ?>:
            </label>
            <div class="col-lg-2">
                <select class="form-control" id="catId" name="catId">
                    <?php foreach ($cats as $model) : ?>
                        <option value="<?=$model->getId() ?>" <?=($this->originalInput('catId', ($faq->getId() ? $faq->getCatId() : 0))) == $model->getId() ? 'selected=""' : '' ?>><?=$this->escape($model->getTitle()) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
        </div>
        <div class="row form-group ilch-margin-b <?=$this->validation()->hasError('question') ? 'has-error' : '' ?>">
            <label for="question" class="col-lg-2 control-label">
                <?=$this->getTrans('question') ?>:
            </label>
            <div class="col-lg-4">
                <input type="text"
                       class="form-control"
                       id="question"
                       name="question"
                       value="<?=$this->escape($this->originalInput('question', $faq->getQuestion())) ?>" />
            </div>
        </div>
        <div class="row form-group ilch-margin-b <?=$this->validation()->hasError('answer') ? 'has-error' : '' ?>">
            <label for="ck_1" class="col-lg-2 control-label">
                <?=$this->getTrans('answer') ?>:
            </label>
            <div class="col-lg-10">
                <textarea class="form-control ckeditor"
                          id="ck_1"
                          name="answer"
                          cols="45"
                          rows="3"
                          toolbar="ilch_html"><?=$this->originalInput('answer', $faq->getAnswer()) ?></textarea>
            </div>
        </div>
        <?=($faq->getId()) ? $this->getSaveBar('updateButton') : $this->getSaveBar('addButton') ?>
    </form>
    <?=$this->getDialog('mediaModal', $this->getTrans('media'), '<iframe frameborder="0"></iframe>') ?>
<?php else : ?>
    <?=$this->getTrans('noCategory') ?>
<?php endif; ?>
