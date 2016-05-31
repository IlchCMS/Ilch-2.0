<legend><?=$this->getTrans('settings') ?></legend>
<form class="form-horizontal" method="POST" action="">
    <?=$this->getTokenField() ?>
    <div class="form-group">
        <label for="cookieConsent" class="col-lg-2 control-label">
            <?=$this->getTrans('cookieConsentShow') ?>:
        </label>
        <div class="col-lg-2">
            <div class="flipswitch">
                <input type="radio" class="flipswitch-input" name="cookieConsent" value="1" id="cookie-consent-yes" <?php if ($this->get('cookieConsent') == '1') { echo 'checked="checked"'; } ?> />  
                <label for="cookie-consent-yes" class="flipswitch-label flipswitch-label-on"><?=$this->getTrans('yes') ?></label>  
                <input type="radio" class="flipswitch-input" name="cookieConsent" value="0" id="cookie-consent-no" <?php if ($this->get('cookieConsent') == '0') { echo 'checked="checked"'; } ?> />  
                <label for="cookie-consent-no" class="flipswitch-label flipswitch-label-off"><?=$this->getTrans('no') ?></label>  
                <span class="flipswitch-selection"></span>
            </div>
        </div>
    </div>
    <div class="form-group">
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
    <div class="form-group">
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
    <div class="form-group">
        <label for="cookieConsentMessage" class="col-lg-2 control-label">
            <?=$this->getTrans('cookieConsentMessage') ?>:
        </label>
        <div class="col-lg-6">
            <input class="form-control"
                   name="cookieConsentMessage"
                   type="text"
                   value="<?=$this->escape($this->get('cookieConsentMessage')) ?>" />
        </div>
    </div>
    <div class="form-group">
        <label for="cookieConsentText" class="col-lg-2 control-label">
            <?=$this->getTrans('cookieConsentText') ?>:
        </label>
        <div class="col-lg-10">
            <textarea class="form-control ckeditor"
                      name="cookieConsentText"
                      id="ck_1"
                      toolbar="ilch_html"
                      rows="5"><?=$this->escape($this->get('cookieConsentText')) ?></textarea>
        </div>
    </div>
    <?=$this->getSaveBar() ?>
</form>

<?=$this->getDialog('mediaModal', $this->getTrans('media'), '<iframe frameborder="0"></iframe>'); ?>
