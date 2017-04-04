<h1><?=$this->getTrans('settings') ?></h1>
<form class="form-horizontal" method="POST" action="<?=$this->getUrl(['action' => $this->getRequest()->getActionName()]) ?>">
    <?=$this->getTokenField() ?>
    <div class="form-group">
        <div class="col-lg-2 control-label">
            <?=$this->getTrans('acceptUserRegis') ?>:
        </div>
        <div class="col-lg-4">
            <div class="flipswitch">
                <input type="radio" class="flipswitch-input" id="regist-accept-yes" name="regist_accept" value="1" <?php if ($this->get('regist_accept') == '1') { echo 'checked="checked"'; } ?> />  
                <label for="regist-accept-yes" class="flipswitch-label flipswitch-label-on"><?=$this->getTrans('yes') ?></label>
                <input type="radio" class="flipswitch-input" id="regist-accept-no" name="regist_accept" value="0" <?php if ($this->get('regist_accept') != '1') { echo 'checked="checked"'; } ?> />  
                <label for="regist-accept-no" class="flipswitch-label flipswitch-label-off"><?=$this->getTrans('no') ?></label>
                <span class="flipswitch-selection"></span>
            </div>
        </div>
    </div>
    <div id="registRules" <?php if ($this->get('regist_accept') != '1') { echo 'class="hidden"'; } ?>>
        <div class="form-group">
            <div class="col-lg-2 control-label">
                <?=$this->getTrans('confirmRegistrationEmail') ?>:
            </div>
            <div class="col-lg-4">
                <div class="flipswitch">
                    <input type="radio" class="flipswitch-input" id="regist-confirm-yes" name="regist_confirm" value="1" <?php if ($this->get('regist_confirm') == '1') { echo 'checked="checked"'; } ?> />  
                    <label for="regist-confirm-yes" class="flipswitch-label flipswitch-label-on"><?=$this->getTrans('yes') ?></label>
                    <input type="radio" class="flipswitch-input" id="regist-confirm-no" name="regist_confirm" value="0" <?php if ($this->get('regist_confirm') != '1') { echo 'checked="checked"'; } ?> />  
                    <label for="regist-confirm-no" class="flipswitch-label flipswitch-label-off"><?=$this->getTrans('no') ?></label>
                    <span class="flipswitch-selection"></span>
                </div>
            </div>
        </div>
    </div>
    <div id="rulesForRegist" <?php if ($this->get('regist_accept') != '1') { echo 'class="hidden"'; } ?>>
        <div class="form-group">
            <label for="ck_1" class="col-lg-2 control-label">
                    <?=$this->getTrans('rulesForRegist') ?>:
            </label>
            <div class="col-lg-10">
                <textarea class="form-control ckeditor"
                          id="ck_1"
                          name="regist_rules"
                          toolbar="ilch_html"
                          cols="60"
                          rows="5"><?=$this->get('regist_rules') ?></textarea>
            </div>
        </div>
    </div>

    <h1><?=$this->getTrans('menuSettingsAvatar') ?></h1>
    <div class="form-group">
        <label for="avatar_height" class="col-lg-2 control-label">
            <?=$this->getTrans('avatarHeight') ?>
        </label>
        <div class="col-lg-2">
            <input type="text"
                   class="form-control required"
                   id="avatar_height"
                   name="avatar_height"
                   value="<?=$this->get('avatar_height') ?>" />
        </div>
    </div>
    <div class="form-group">
        <label for="avatar_width" class="col-lg-2 control-label">
            <?=$this->getTrans('avatarWidth') ?>
        </label>
        <div class="col-lg-2">
            <input type="text"
                   class="form-control required"
                   id="avatar_width"
                   name="avatar_width"
                   value="<?=$this->get('avatar_width') ?>" />
        </div>
    </div>
    <div class="form-group">
        <label for="avatar_size" class="col-lg-2 control-label">
            <?=$this->getTrans('avatarSizeBytes') ?>
        </label>
        <div class="col-lg-2">
            <input type="text"
                   class="form-control required"
                   id="avatar_size"
                   name="avatar_size"
                   value="<?=$this->get('avatar_size') ?>" />
        </div>
    </div>
    <div class="form-group">
        <label for="avatar_filetypes" class="col-lg-2 control-label">
            <?=$this->getTrans('allowedFileExtensions') ?>
        </label>
        <div class="col-lg-2">
            <input type="text"
                   class="form-control required"
                   id="avatar_filetypes"
                   name="avatar_filetypes"
                   value="<?=$this->get('avatar_filetypes') ?>" />
        </div>
    </div>

    <h1><?=$this->getTrans('menuSettingsGallery') ?></h1>
    <div class="form-group">
        <div class="col-lg-2 control-label">
            <?=$this->getTrans('usergalleryAllowed') ?>:
        </div>
        <div class="col-lg-4">
            <div class="flipswitch">
                <input type="radio" class="flipswitch-input" id="usergallery-allowed-yes" name="usergallery_allowed" value="1" <?php if ($this->get('usergallery_allowed') == '1') { echo 'checked="checked"'; } ?> />  
                <label for="usergallery-allowed-yes" class="flipswitch-label flipswitch-label-on"><?=$this->getTrans('yes') ?></label>
                <input type="radio" class="flipswitch-input" id="usergallery-allowed-no" name="usergallery_allowed" value="0" <?php if ($this->get('usergallery_allowed') != '1') { echo 'checked="checked"'; } ?> />  
                <label for="usergallery-allowed-no" class="flipswitch-label flipswitch-label-off"><?=$this->getTrans('no') ?></label>
                <span class="flipswitch-selection"></span>
            </div>
        </div>
    </div>
    <div class="form-group">
        <label for="usergallery_filetypes" class="col-lg-2 control-label">
            <?=$this->getTrans('allowedFileExtensions') ?>
        </label>
        <div class="col-lg-2">
            <input type="text"
                   class="form-control required"
                   id="usergallery_filetypes"
                   name="usergallery_filetypes"
                   value="<?=$this->get('usergallery_filetypes') ?>" />
        </div>
    </div>
    <div class="form-group">
        <label for="picturesPerPageInput" class="col-lg-2 control-label">
            <?=$this->getTrans('picturesPerPage') ?>:
        </label>
        <div class="col-lg-1">
            <input type="number"
                   class="form-control"
                   id="picturesPerPageInput"
                   name="picturesPerPage"
                   min="1"
                   value="<?=$this->escape($this->get('picturesPerPage')) ?>" />
        </div>
    </div>
    <?=$this->getSaveBar() ?>
</form>

<?=$this->getDialog('mediaModal', $this->getTrans('media'), '<iframe frameborder="0"></iframe>'); ?>
<script>
$('[name="regist_accept"]').click(function () {
    if ($(this).val() == "1") {
        $('#registRules').removeClass('hidden');
        $('#rulesForRegist').removeClass('hidden');
        $('#registAccept').removeClass('hidden');
    } else {
        $('#registRules').addClass('hidden');
        $('#rulesForRegist').addClass('hidden');
        $('#registAccept').addClass('hidden');
        }
});
</script>
