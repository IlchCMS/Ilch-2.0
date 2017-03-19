<h1><?=$this->getTrans('manage') ?></h1>
<form class="form-horizontal" method="POST" action="">
    <?=$this->getTokenField() ?>
    <div class="form-group <?=$this->validation()->hasError('cookieConsentText') ? 'has-error' : '' ?>">
        <label for="cookieConsentText" class="col-lg-2 control-label">
            <?=$this->getTrans('cookieConsentText') ?>:
        </label>
        <div class="col-lg-10">
            <textarea class="form-control ckeditor"
                      id="ck_1"
                      name="cookieConsentText"
                      toolbar="ilch_html"
                      rows="5"><?=($this->originalInput('cookieConsentText') != '') ? $this->escape($this->originalInput('cookieConsentText')) : $this->escape($this->get('cookieConsentText')) ?></textarea>
        </div>
    </div>
    <?=$this->getSaveBar() ?>
</form>

<?=$this->getDialog('mediaModal', $this->getTrans('media'), '<iframe frameborder="0"></iframe>'); ?>
