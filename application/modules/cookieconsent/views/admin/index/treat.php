<?php $cookieConsent = $this->get('cookieConsent'); ?>

<h1><?=$this->getTrans('treat') ?></h1>
<form class="form-horizontal" method="POST">
    <?=$this->getTokenField() ?>
    <div class="form-group">
        <label class="col-lg-2">
            <?=$this->getTrans('cookieConsentLocale') ?>
        </label>
        <div class="col-lg-10">
            <?=($cookieConsent) ? $cookieConsent->getLocale() : $this->getRequest()->getParam('locale') ?>
        </div>
    </div>
    <div class="form-group <?=$this->validation()->hasError('text') ? 'has-error' : '' ?>">
        <label for="text" class="col-lg-2 control-label">
            <?=$this->getTrans('cookieConsentText') ?>
        </label>
        <div class="col-lg-10">
            <textarea class="form-control ckeditor"
                      id="text"
                      name="text"
                      toolbar="ilch_html"
                      rows="5"><?=($cookieConsent) ? $cookieConsent->getText() : $this->originalInput('text') ?></textarea>
        </div>
    </div>
    <?=$this->getSaveBar() ?>
</form>

<?=$this->getDialog('mediaModal', $this->getTrans('media'), '<iframe frameborder="0"></iframe>'); ?>
