<legend><?=$this->getTrans('settings') ?></legend>
<?php if ($this->validation()->hasErrors()): ?>
    <div class="alert alert-danger" role="alert">
        <strong> <?=$this->getTrans('errorsOccured') ?>:</strong>
        <ul>
            <?php foreach ($this->validation()->getErrorMessages() as $error): ?>
                <li><?= $error; ?></li>
            <?php endforeach; ?>
        </ul>
    </div>
<?php endif; ?>
<form class="form-horizontal" method="POST" action="">
    <?=$this->getTokenField() ?>
    <div class="form-group <?=$this->validation()->hasError('cookieConsent') ? 'has-error' : '' ?>">
        <div class="col-lg-2 control-label">
            <?=$this->getTrans('cookieConsentShow') ?>:
        </div>
        <div class="col-lg-2">
            <div class="flipswitch">
                <input type="radio" class="flipswitch-input" id="cookie-consent-yes" name="cookieConsent" value="1" <?php if ($this->get('cookieConsent') == '1') { echo 'checked="checked"'; } ?> />  
                <label for="cookie-consent-yes" class="flipswitch-label flipswitch-label-on"><?=$this->getTrans('yes') ?></label>  
                <input type="radio" class="flipswitch-input" id="cookie-consent-no" name="cookieConsent" value="0" <?php if ($this->get('cookieConsent') == '0') { echo 'checked="checked"'; } ?> />  
                <label for="cookie-consent-no" class="flipswitch-label flipswitch-label-off"><?=$this->getTrans('no') ?></label>  
                <span class="flipswitch-selection"></span>
            </div>
        </div>
    </div>
    <div class="form-group <?=$this->validation()->hasError('cookieConsentStyle') ? 'has-error' : '' ?>">
        <label for="cookieConsentStyle" class="col-lg-2 control-label">
            <?=$this->getTrans('cookieConsentStyle') ?>:
        </label>
        <div class="col-lg-2">
            <select class="form-control" name="cookieConsentStyle">
                <option <?php if ($this->get('cookieConsentStyle') == 'dark') { echo 'selected="selected"'; } ?> value="dark"><?=$this->getTrans('cookieConsentStyleDark') ?></option>
                <option <?php if ($this->get('cookieConsentStyle') == 'light') { echo 'selected="selected"'; } ?> value="light"><?=$this->getTrans('cookieConsentStyleLight') ?></option>
            </select>
        </div>
    </div>
    <div class="form-group <?=$this->validation()->hasError('cookieConsentPos') ? 'has-error' : '' ?>">
        <label for="cookieConsentPos" class="col-lg-2 control-label">
            <?=$this->getTrans('cookieConsentPos') ?>:
        </label>
        <div class="col-lg-2">
            <select class="form-control" name="cookieConsentPos">
                <option <?php if ($this->get('cookieConsentPos') == 'top') { echo 'selected="selected"'; } ?> value="top"><?=$this->getTrans('cookieConsentPosTop') ?></option>
                <option <?php if ($this->get('cookieConsentPos') == 'floating') { echo 'selected="selected"'; } ?> value="floating"><?=$this->getTrans('cookieConsentPosFloating') ?></option>
                <option <?php if ($this->get('cookieConsentPos') == 'bottom') { echo 'selected="selected"'; } ?> value="bottom"><?=$this->getTrans('cookieConsentPosBottom') ?></option>
            </select>
        </div>
    </div>
    <div class="form-group <?=$this->validation()->hasError('cookieConsentMessage') ? 'has-error' : '' ?>">
        <label for="cookieConsentMessage" class="col-lg-2 control-label">
            <?=$this->getTrans('cookieConsentMessage') ?>:
        </label>
        <div class="col-lg-6">
            <input type="text"
                   class="form-control"
                   name="cookieConsentMessage"
                   value="<?=($this->originalInput('cookieConsentMessage') != '') ? $this->escape($this->originalInput('cookieConsentMessage')) : $this->escape($this->get('cookieConsentMessage')) ?>" />
        </div>
    </div>
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
