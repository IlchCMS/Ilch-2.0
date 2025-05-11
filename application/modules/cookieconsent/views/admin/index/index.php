
<?php

/** @var \Ilch\View $this */
?>
<h1><?=$this->getTrans('settings') ?></h1>
<form method="POST">
    <?=$this->getTokenField() ?>
    <div class="row mb-3<?=$this->validation()->hasError('cookieConsent') ? ' has-error' : '' ?>">
        <div class="col-xl-2 col-form-label">
            <?=$this->getTrans('cookieConsentShow') ?>:
        </div>
        <div class="col-xl-2">
            <div class="flipswitch">
                <input type="radio" class="flipswitch-input" id="cookie-consent-yes" name="cookieConsent" value="1" <?=($this->get('cookieConsent') == '1') ? 'checked="checked"' : '' ?> />
                <label for="cookie-consent-yes" class="flipswitch-label flipswitch-label-on"><?=$this->getTrans('yes') ?></label>
                <input type="radio" class="flipswitch-input" id="cookie-consent-no" name="cookieConsent" value="0" <?=($this->get('cookieConsent') == '0') ? 'checked="checked"' : '' ?> />
                <label for="cookie-consent-no" class="flipswitch-label flipswitch-label-off"><?=$this->getTrans('no') ?></label>
                <span class="flipswitch-selection"></span>
            </div>
        </div>
    </div>
    <div class="row mb-3<?=$this->validation()->hasError('cookieConsentPos') ? ' has-error' : '' ?>">
        <label for="cookieConsentPos" class="col-xl-2 col-form-label">
            <?=$this->getTrans('cookieConsentPos') ?>:
        </label>
        <div class="col-xl-2">
            <select class="form-select" name="cookieConsentPos" id="cookieConsentPos">
                <option <?=($this->get('cookieConsentPos') == 'top') ? 'selected="selected"' : '' ?> value="top"><?=$this->getTrans('cookieConsentPosTop') ?></option>
                <option <?=($this->get('cookieConsentPos') == 'middle') ? 'selected="selected"' : '' ?> value="middle"><?=$this->getTrans('cookieConsentPosMiddle') ?></option>
                <option <?=($this->get('cookieConsentPos') == 'bottom') ? 'selected="selected"' : '' ?> value="bottom"><?=$this->getTrans('cookieConsentPosBottom') ?></option>
                <option <?=($this->get('cookieConsentPos') == 'popup') ? 'selected="selected"' : '' ?> value="popup"><?=$this->getTrans('cookieConsentPosPopup') ?></option>
            </select>
        </div>
    </div>
    <div class="row mb-3<?=$this->validation()->hasError('cookieIconPos') ? ' has-error' : '' ?>">
        <label for="cookieIconPos" class="col-xl-2 col-form-label">
            <?=$this->getTrans('cookieIconPos') ?>:
        </label>
        <div class="col-xl-2">
            <select class="form-select" name="cookieIconPos" id="cookieIconPos">
                <option <?=($this->get('cookieIconPos') == 'BottomRight') ? 'selected="selected"' : '' ?> value="BottomRight"><?=$this->getTrans('cookieIconBottomRight') ?></option>
                <option <?=($this->get('cookieIconPos') == 'BottomLeft') ? 'selected="selected"' : '' ?> value="BottomLeft"><?=$this->getTrans('cookieIconBottomLeft') ?></option>
                <option <?=($this->get('cookieIconPos') == 'TopRight') ? 'selected="selected"' : '' ?> value="TopRight"><?=$this->getTrans('cookieIconTopRight') ?></option>
                <option <?=($this->get('cookieIconPos') == 'TopLeft') ? 'selected="selected"' : '' ?> value="TopLeft"><?=$this->getTrans('cookieIconTopLeft') ?></option>
            </select>
        </div>
    </div>
    <div class="row mb-3<?=$this->validation()->hasError('cookieConsentType') ? ' has-error' : '' ?>">
        <label for="cookieConsentType" class="col-xl-2 col-form-label">
            <?=$this->getTrans('cookieConsentType') ?>:
        </label>
        <div class="col-xl-2">
            <select class="form-select" name="cookieConsentType" id="cookieConsentType">
                <option <?=($this->get('cookieConsentType') == 'info') ? 'selected="selected"' : '' ?> value="info"><?=$this->getTrans('cookieConsentTypeInfo') ?></option>
                <option <?=($this->get('cookieConsentType') == 'opt-in') ? 'selected="selected"' : '' ?> value="opt-in"><?=$this->getTrans('cookieConsentTypeOptIn') ?></option>
                <option <?=($this->get('cookieConsentType') == 'opt-out') ? 'selected="selected"' : '' ?> value="opt-out"><?=$this->getTrans('cookieConsentTypeOptOut') ?></option>
            </select>
        </div>
    </div>
    <div class="row mb-3 col-xl-5">
        <label for="serviceSelection" class="col-xl-2 col-form-label">
            <?=$this->getTrans('cookieConsentServices') ?>:
        </label>
        <div id="serviceSelection" class="row mb-3">
            <div class="col-xl-5">
                <select id="selectAvailableServices" class="form-select" size="10" multiple aria-label="<?=$this->getTrans('cookieConsentAvailableServices') ?>"></select>
            </div>
            <div class="col-auto">
                <p><button type="button" class="btn btn-secondary" id="addButton" title="<?=$this->getTrans('addServices') ?>"><i class="fa-solid fa-arrow-right"></i></button></p>
                <p><button type="button" class="btn btn-secondary" id="removeButton" title="<?=$this->getTrans('removeServices') ?>"><i class="fa-solid fa-arrow-left"></i></button></p>
                <p><button type="button" class="btn btn-secondary" id="removeAllButton" title="<?=$this->getTrans('removeAllServices') ?>"><i class="fa-solid fa-backward-fast"></i></button></p>
            </div>
            <div class="col-xl-5">
                <select id="selectSelectedServices" name="cookieConsentServices[]" class="form-select" size="10" multiple aria-label="<?=$this->getTrans('cookieConsentSelectedServices') ?>">
                    <?php foreach ($this->get('cookieConsentServices') as $service): ?>
                        <option value="<?=$service ?>" selected></option>
                    <?php endforeach; ?>
                </select>
            </div>
        </div>
    </div>
    <p><?=$this->getTrans('servicesNeededModificationsDesc') ?></p>

    <h1><?=$this->getTrans('cookieConsentPopUp') ?></h1>
    <div class="row mb-3<?=$this->validation()->hasError('cookieConsentPopUpBGColor') ? ' has-error' : '' ?>">
        <label for="cookieConsentPopUpBGColor" class="col-xl-2 col-form-label">
            <?=$this->getTrans('cookieConsentPopUpBGColor') ?>:
        </label>
        <div class="col-xl-2 input-group">
            <input class="form-control color {hash:true}"
                   id="cookieConsentPopUpBGColor"
                   name="cookieConsentPopUpBGColor"
                   data-jscolor=""
                   value="<?=($this->get('cookieConsentPopUpBGColor') != '') ? $this->get('cookieConsentPopUpBGColor') : '#000000' ?>">
            <span class="input-group-text">
                <span class="fa-solid fa-arrow-rotate-left" onclick="document.getElementById('cookieConsentPopUpBGColor').jscolor.fromString('000000')"></span>
            </span>
        </div>
    </div>
    <div class="row mb-3<?=$this->validation()->hasError('cookieConsentPopUpTextColor') ? ' has-error' : '' ?>">
        <label for="cookieConsentPopUpTextColor" class="col-xl-2 col-form-label">
            <?=$this->getTrans('cookieConsentPopUpTextColor') ?>:
        </label>
        <div class="col-xl-2 input-group">
            <input class="form-control color {hash:true}"
                   id="cookieConsentPopUpTextColor"
                   name="cookieConsentPopUpTextColor"
                   data-jscolor=""
                   value="<?=($this->get('cookieConsentPopUpTextColor') != '') ? $this->get('cookieConsentPopUpTextColor') : '#ffffff' ?>">
            <span class="input-group-text">
                <span class="fa-solid fa-arrow-rotate-left" onclick="document.getElementById('cookieConsentPopUpTextColor').jscolor.fromString('ffffff')"></span>
            </span>
        </div>
    </div>

    <h1><?=$this->getTrans('cookieConsentBtn') ?></h1>
    <div class="row mb-3<?=$this->validation()->hasError('cookieConsentBtnBGColor') ? ' has-error' : '' ?>">
        <label for="cookieConsentBtnBGColor" class="col-xl-2 col-form-label">
            <?=$this->getTrans('cookieConsentBtnBGColor') ?>:
        </label>
        <div class="col-xl-2 input-group">
            <input class="form-control color {hash:true}"
                   id="cookieConsentBtnBGColor"
                   name="cookieConsentBtnBGColor"
                   data-jscolor=""
                   value="<?=($this->get('cookieConsentBtnBGColor') != '') ? $this->get('cookieConsentBtnBGColor') : '#f1d600' ?>">
            <span class="input-group-text">
                <span class="fa-solid fa-arrow-rotate-left" onclick="document.getElementById('cookieConsentBtnBGColor').jscolor.fromString('f1d600')"></span>
            </span>
        </div>
    </div>
    <div class="row mb-3<?=$this->validation()->hasError('cookieConsentBtnTextColor') ? ' has-error' : '' ?>">
        <label for="cookieConsentBtnTextColor" class="col-xl-2 col-form-label">
            <?=$this->getTrans('cookieConsentBtnTextColor') ?>:
        </label>
        <div class="col-xl-2 input-group">
            <input class="form-control color {hash:true}"
                   id="cookieConsentBtnTextColor"
                   name="cookieConsentBtnTextColor"
                   data-jscolor=""
                   value="<?=($this->get('cookieConsentBtnTextColor') != '') ? $this->get('cookieConsentBtnTextColor') : '#000000' ?>">
            <span class="input-group-text">
                <span class="fa-solid fa-arrow-rotate-left" onclick="document.getElementById('cookieConsentBtnTextColor').jscolor.fromString('000000')"></span>
            </span>
        </div>
    </div>
    <?=$this->getSaveBar() ?>
</form>

<script src="<?=$this->getStaticUrl('js/tarteaucitron/build/tarteaucitron.min.js') ?>"></script>
<script src="<?=$this->getStaticUrl('js/tarteaucitron/build/tarteaucitron.services.min.js') ?>"></script>
<script src="<?=$this->getStaticUrl('js/jscolor/jscolor.min.js') ?>"></script>

<script>
    let services = Object.entries(tarteaucitron.services).sort();
    let selectAvailableServices = document.getElementById("selectAvailableServices");
    let selectSelectedServices = document.getElementById("selectSelectedServices");

    services.forEach(item => {
        let option = document.createElement("option");
        option.value = item[1].key;
        option.text = item[1].name;
        selectAvailableServices.appendChild(option);

        for (let selectedItem of selectSelectedServices.selectedOptions) {
            if (item[1].key === selectedItem.value) {
                selectedItem.text = item[1].name;
                return;
            }
        }
    });

    document.getElementById("addButton").addEventListener("click", () => {
        availableServices: for (let item of selectAvailableServices.selectedOptions) {
            for (let selectedItem of selectSelectedServices.selectedOptions) {
                if (item.value === selectedItem.value) {
                    continue availableServices;
                }
            }

            let option = document.createElement("option");
            option.value = item.value;
            option.text = item.text;
            option.setAttribute('selected', '');
            selectSelectedServices.appendChild(option);
        }
    });

    document.getElementById("removeButton").addEventListener("click", () => {
        for (let i = selectSelectedServices.selectedOptions.length - 1; i >= 0; i--) {
            selectSelectedServices.selectedOptions[i].remove();
        }
        for (let option of selectSelectedServices.options) {
            option.selected = true;
        }
    });

    document.getElementById("removeAllButton").addEventListener("click", () => {
        selectSelectedServices.length = 0;
    });
</script>
