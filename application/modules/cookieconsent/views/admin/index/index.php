<h1><?=$this->getTrans('settings') ?></h1>
<form class="form-horizontal" method="POST">
    <?=$this->getTokenField() ?>
    <div class="row mb-3 <?=$this->validation()->hasError('cookieConsent') ? 'has-error' : '' ?>">
        <div class="col-xl-2 control-label">
            <?=$this->getTrans('cookieConsentShow') ?>:
        </div>
        <div class="col-xl-2">
            <div class="flipswitch">
                <input type="radio" class="flipswitch-input" id="cookie-consent-yes" name="cookieConsent" value="1" <?php if ($this->get('cookieConsent') == '1') { echo 'checked="checked"'; } ?> />
                <label for="cookie-consent-yes" class="flipswitch-label flipswitch-label-on"><?=$this->getTrans('yes') ?></label>
                <input type="radio" class="flipswitch-input" id="cookie-consent-no" name="cookieConsent" value="0" <?php if ($this->get('cookieConsent') == '0') { echo 'checked="checked"'; } ?> />
                <label for="cookie-consent-no" class="flipswitch-label flipswitch-label-off"><?=$this->getTrans('no') ?></label>
                <span class="flipswitch-selection"></span>
            </div>
        </div>
    </div>
    <div class="row mb-3 <?=$this->validation()->hasError('cookieConsentLayout') ? 'has-error' : '' ?>">
        <label for="cookieConsentLayout" class="col-xl-2 control-label">
            <?=$this->getTrans('cookieConsentLayout') ?>:
        </label>
        <div class="col-xl-2">
            <select class="form-control" name="cookieConsentLayout" id="cookieConsentLayout">
                <option <?php if ($this->get('cookieConsentLayout') == 'block') { echo 'selected="selected"'; } ?> value="block"><?=$this->getTrans('cookieConsentLayoutBlock') ?></option>
                <option <?php if ($this->get('cookieConsentLayout') == 'classic') { echo 'selected="selected"'; } ?> value="classic"><?=$this->getTrans('cookieConsentLayoutClassic') ?></option>
                <option <?php if ($this->get('cookieConsentLayout') == 'edgeless') { echo 'selected="selected"'; } ?> value="edgeless"><?=$this->getTrans('cookieConsentLayoutEdgeless') ?></option>
            </select>
        </div>
    </div>
    <div class="row mb-3 <?=$this->validation()->hasError('cookieConsentPos') ? 'has-error' : '' ?>">
        <label for="cookieConsentPos" class="col-xl-2 control-label">
            <?=$this->getTrans('cookieConsentPos') ?>:
        </label>
        <div class="col-xl-2">
            <select class="form-control" name="cookieConsentPos" id="cookieConsentPos">
                <option <?php if ($this->get('cookieConsentPos') == 'top') { echo 'selected="selected"'; } ?> value="top"><?=$this->getTrans('cookieConsentPosTop') ?></option>
                <option <?php if ($this->get('cookieConsentPos') == 'bottom-left') { echo 'selected="selected"'; } ?> value="bottom-left"><?=$this->getTrans('cookieConsentPosFloatingLeft') ?></option>
                <option <?php if ($this->get('cookieConsentPos') == 'bottom-right') { echo 'selected="selected"'; } ?> value="bottom-right"><?=$this->getTrans('cookieConsentPosFloatingRight') ?></option>
                <option <?php if ($this->get('cookieConsentPos') == 'bottom') { echo 'selected="selected"'; } ?> value="bottom"><?=$this->getTrans('cookieConsentPosBottom') ?></option>
            </select>
        </div>
    </div>
    <div class="row mb-3 <?=$this->validation()->hasError('cookieConsentType') ? 'has-error' : '' ?>">
        <label for="cookieConsentType" class="col-xl-2 control-label">
            <?=$this->getTrans('cookieConsentType') ?>:
        </label>
        <div class="col-xl-2">
            <select class="form-control" name="cookieConsentType" id="cookieConsentType">
                <option <?php if ($this->get('cookieConsentType') == 'info') { echo 'selected="selected"'; } ?> value="info"><?=$this->getTrans('cookieConsentTypeInfo') ?></option>
                <option <?php if ($this->get('cookieConsentType') == 'opt-in') { echo 'selected="selected"'; } ?> value="opt-in"><?=$this->getTrans('cookieConsentTypeOptIn') ?></option>
                <option <?php if ($this->get('cookieConsentType') == 'opt-out') { echo 'selected="selected"'; } ?> value="opt-out"><?=$this->getTrans('cookieConsentTypeOptOut') ?></option>
            </select>
        </div>
    </div>

    <h1><?=$this->getTrans('cookieConsentPopUp') ?></h1>
    <div class="row mb-3 <?=$this->validation()->hasError('cookieConsentPopUpBGColor') ? 'has-error' : '' ?>">
        <label for="color" class="col-xl-2 control-label">
            <?=$this->getTrans('cookieConsentPopUpBGColor') ?>:
        </label>
        <div class="col-xl-2 input-group ilch-date">
            <input class="form-control color {hash:true}"
                   id="cookieConsentPopUpBGColor"
                   name="cookieConsentPopUpBGColor"
                   value="<?php if ($this->get('cookieConsentPopUpBGColor') != '') { echo $this->get('cookieConsentPopUpBGColor'); } else { echo '#000000'; } ?>">
            <span class="input-group-text">
                <span class="fa fa-undo" onclick="document.getElementById('cookieConsentPopUpBGColor').color.fromString('000000')"></span>
            </span>
        </div>
    </div>
    <div class="row mb-3 <?=$this->validation()->hasError('cookieConsentPopUpTextColor') ? 'has-error' : '' ?>">
        <label for="color" class="col-xl-2 control-label">
            <?=$this->getTrans('cookieConsentPopUpTextColor') ?>:
        </label>
        <div class="col-xl-2 input-group ilch-date">
            <input class="form-control color {hash:true}"
                   id="cookieConsentPopUpTextColor"
                   name="cookieConsentPopUpTextColor"
                   value="<?php if ($this->get('cookieConsentPopUpTextColor') != '') { echo $this->get('cookieConsentPopUpTextColor'); } else { echo '#ffffff'; } ?>">
            <span class="input-group-text">
                <span class="fa fa-undo" onclick="document.getElementById('cookieConsentPopUpTextColor').color.fromString('ffffff')"></span>
            </span>
        </div>
    </div>

    <h1><?=$this->getTrans('cookieConsentBtn') ?></h1>
    <div class="row mb-3 <?=$this->validation()->hasError('cookieConsentBtnBGColor') ? 'has-error' : '' ?>">
        <label for="color" class="col-xl-2 control-label">
            <?=$this->getTrans('cookieConsentBtnBGColor') ?>:
        </label>
        <div class="col-xl-2 input-group ilch-date">
            <input class="form-control color {hash:true}"
                   id="cookieConsentBtnBGColor"
                   name="cookieConsentBtnBGColor"
                   value="<?php if ($this->get('cookieConsentBtnBGColor') != '') { echo $this->get('cookieConsentBtnBGColor'); } else { echo '#f1d600'; } ?>">
            <span class="input-group-text">
                <span class="fa fa-undo" onclick="document.getElementById('cookieConsentBtnBGColor').color.fromString('f1d600')"></span>
            </span>
        </div>
    </div>
    <div class="row mb-3 <?=$this->validation()->hasError('cookieConsentBtnTextColor') ? 'has-error' : '' ?>">
        <label for="color" class="col-xl-2 control-label">
            <?=$this->getTrans('cookieConsentBtnTextColor') ?>:
        </label>
        <div class="col-xl-2 input-group ilch-date">
            <input class="form-control color {hash:true}"
                   id="cookieConsentBtnTextColor"
                   name="cookieConsentBtnTextColor"
                   value="<?php if ($this->get('cookieConsentBtnTextColor') != '') { echo $this->get('cookieConsentBtnTextColor'); } else { echo '#000000'; } ?>">
            <span class="input-group-text">
                <span class="fa fa-undo" onclick="document.getElementById('cookieConsentBtnTextColor').color.fromString('000000')"></span>
            </span>
        </div>
    </div>
    <?=$this->getSaveBar() ?>
</form>

<script src="<?=$this->getStaticUrl('js/jscolor/jscolor.js') ?>"></script>
