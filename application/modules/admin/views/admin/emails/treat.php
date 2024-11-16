<?php
$email = $this->get('emailContent');
$module = $this->get('module');
$moduleName = ($module) ? $module->getName() : $this->escape($this->getRequest()->getParam('key'));
?>

<h1><?=$this->getTrans('edit') ?></h1>
<form method="POST">
    <?=$this->getTokenField() ?>
    <div class="row mb-3">
        <label class="col-xl-2">
            <?=$this->getTrans('emailModule') ?>
        </label>
        <div class="col-xl-10">
            <?=$moduleName ?>
        </div>
    </div>
    <div class="row mb-3">
        <label class="col-xl-2">
            <?=$this->getTrans('emailLocale') ?>
        </label>
        <div class="col-xl-10">
            <?=($email) ? $email->getLocale() : $this->getRequest()->getParam('locale') ?>
        </div>
    </div>
    <div class="row mb-3<?=$this->validation()->hasError('desc') ? ' has-error' : '' ?>">
        <label for="desc" class="col-xl-2 col-form-label">
            <?=$this->getTrans('emailDesc') ?>
        </label>
        <div class="col-xl-3">
            <input type="text"
                   class="form-control"
                   id="desc"
                   name="desc"
                   value="<?=($email) ? $email->getDesc() : $this->originalInput('desc') ?>" />
        </div>
    </div>
    <div class="row mb-3<?=$this->validation()->hasError('text') ? ' has-error' : '' ?>">
        <label for="text" class="col-xl-2 col-form-label">
            <?=$this->getTrans('emailText') ?>
        </label>
        <div class="col-xl-10">
            <textarea class="form-control ckeditor"
                      id="text"
                      name="text"
                      toolbar="ilch_html"
                      cols="60"
                      rows="5"><?=($email) ? $email->getText() : $this->originalInput('text') ?></textarea>
        </div>
    </div>
    <?=$this->getSaveBar() ?>
</form>
