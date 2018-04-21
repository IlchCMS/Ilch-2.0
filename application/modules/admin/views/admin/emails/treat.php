<?php
$email = $this->get('emailContent');
$moduleMapper = $this->get('moduleMapper');
$module = $moduleMapper->getModulesByKey($this->getRequest()->getParam('key'), $this->getTranslator()->getLocale());
?>

<h1><?=$this->getTrans('edit') ?></h1>
<form class="form-horizontal" method="POST">
    <?=$this->getTokenField() ?>
    <div class="form-group">
        <label class="col-lg-2">
            <?=$this->getTrans('emailModule') ?>
        </label>
        <div class="col-lg-10">
            <?=$module->getName() ?>
        </div>
    </div>
    <div class="form-group">
        <label class="col-lg-2">
            <?=$this->getTrans('emailLocale') ?>
        </label>
        <div class="col-lg-10">
            <?=($email) ? $email->getLocale() : $this->getRequest()->getParam('locale') ?>
        </div>
    </div>
    <div class="form-group <?=$this->validation()->hasError('desc') ? 'has-error' : '' ?>">
        <label for="desc" class="col-lg-2 control-label">
            <?=$this->getTrans('emailDesc') ?>
        </label>
        <div class="col-lg-3">
            <input type="text"
                   class="form-control"
                   id="desc"
                   name="desc"
                   value="<?=($email) ? $email->getDesc() : $this->originalInput('desc') ?>" />
        </div>
    </div>
    <div class="form-group <?=$this->validation()->hasError('text') ? 'has-error' : '' ?>">
        <label for="text" class="col-lg-2 control-label">
            <?=$this->getTrans('emailText') ?>
        </label>
        <div class="col-lg-10">
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
