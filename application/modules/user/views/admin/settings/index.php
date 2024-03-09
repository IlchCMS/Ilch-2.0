<h1><?=$this->getTrans('settings') ?></h1>
<form class="form-horizontal" method="POST" action="<?=$this->getUrl(['action' => $this->getRequest()->getActionName()]) ?>">
    <?=$this->getTokenField() ?>
    <div class="row mb-3">
        <div class="col-xl-2 control-label">
            <?=$this->getTrans('acceptUserRegis') ?>:
        </div>
        <div class="col-xl-4">
            <div class="flipswitch">
                <input type="radio" class="flipswitch-input" id="regist-accept-yes" name="regist_accept" value="1" <?=($this->get('regist_accept') == '1') ? 'checked="checked"' : '' ?> />
                <label for="regist-accept-yes" class="flipswitch-label flipswitch-label-on"><?=$this->getTrans('yes') ?></label>
                <input type="radio" class="flipswitch-input" id="regist-accept-no" name="regist_accept" value="0" <?=($this->get('regist_accept') != '1') ? 'checked="checked"' : '' ?> />
                <label for="regist-accept-no" class="flipswitch-label flipswitch-label-off"><?=$this->getTrans('no') ?></label>
                <span class="flipswitch-selection"></span>
            </div>
        </div>
    </div>
    <div id="registRules" <?=($this->get('regist_accept') != '1') ? 'hidden' : '' ?>>
        <div class="row mb-3">
            <div class="col-xl-2 control-label">
                <?=$this->getTrans('confirmRegistrationEmail') ?>:
            </div>
            <div class="col-xl-4">
                <div class="flipswitch">
                    <input type="radio" class="flipswitch-input" id="regist-confirm-yes" name="regist_confirm" value="1" <?=($this->get('regist_confirm') == '1') ? 'checked="checked"' : '' ?> />
                    <label for="regist-confirm-yes" class="flipswitch-label flipswitch-label-on"><?=$this->getTrans('yes') ?></label>
                    <input type="radio" class="flipswitch-input" id="regist-confirm-no" name="regist_confirm" value="0" <?=($this->get('regist_confirm') != '1') ? 'checked="checked"' : '' ?> />
                    <label for="regist-confirm-no" class="flipswitch-label flipswitch-label-off"><?=$this->getTrans('no') ?></label>
                    <span class="flipswitch-selection"></span>
                </div>
            </div>
        </div>
    </div>
    <div id="registSetfree" <?=($this->get('regist_accept') == '1' && $this->get('regist_confirm') == '1') ? 'hidden' : '' ?>>
        <div class="row mb-3">
            <div class="col-xl-2 control-label">
                <?=$this->getTrans('setfreeRegistration') ?>:
            </div>
            <div class="col-xl-4">
                <div class="flipswitch">
                    <input type="radio" class="flipswitch-input" id="regist-setfree-yes" name="regist_setfree" value="1" <?=($this->get('regist_setfree') == '1') ? 'checked="checked"' : '' ?> />
                    <label for="regist-setfree-yes" class="flipswitch-label flipswitch-label-on"><?=$this->getTrans('yes') ?></label>
                    <input type="radio" class="flipswitch-input" id="regist-setfree-no" name="regist_setfree" value="0" <?=($this->get('regist_setfree') != '1') ? 'checked="checked"' : '' ?> />
                    <label for="regist-setfree-no" class="flipswitch-label flipswitch-label-off"><?=$this->getTrans('no') ?></label>
                    <span class="flipswitch-selection"></span>
                </div>
            </div>
        </div>
    </div>
    <div id="rulesForRegist" <?=($this->get('regist_accept') != '1') ? 'class="hidden"' : '' ?>>
        <div class="row mb-3">
            <label for="ck_1" class="col-xl-2 control-label">
                    <?=$this->getTrans('rulesForRegist') ?>:
            </label>
            <div class="col-xl-10">
                <textarea class="form-control ckeditor"
                          id="ck_1"
                          name="regist_rules"
                          toolbar="ilch_html"
                          cols="60"
                          rows="5"><?=$this->get('regist_rules') ?></textarea>
            </div>
        </div>
    </div>
    <div class="row mb-3">
        <label for="delete_time" class="col-xl-2 control-label">
            <?=$this->getTrans('deletetime') ?>
        </label>
        <div class="col-xl-2">
            <input type="text"
                   class="form-control required"
                   id="delete_time"
                   name="delete_time"
                   value="<?=$this->get('delete_time') ?>" />
        </div>
    </div>

    <h1><?=$this->getTrans('menuSettingsAvatar') ?></h1>
    <div class="row mb-3">
        <label for="avatar_height" class="col-xl-2 control-label">
            <?=$this->getTrans('avatarHeight') ?>
        </label>
        <div class="col-xl-2">
            <input type="text"
                   class="form-control required"
                   id="avatar_height"
                   name="avatar_height"
                   value="<?=$this->get('avatar_height') ?>" />
        </div>
    </div>
    <div class="row mb-3">
        <label for="avatar_width" class="col-xl-2 control-label">
            <?=$this->getTrans('avatarWidth') ?>
        </label>
        <div class="col-xl-2">
            <input type="text"
                   class="form-control required"
                   id="avatar_width"
                   name="avatar_width"
                   value="<?=$this->get('avatar_width') ?>" />
        </div>
    </div>
    <div class="row mb-3">
        <label for="avatar_size" class="col-xl-2 control-label">
            <?=$this->getTrans('avatarSizeBytes') ?>
        </label>
        <div class="col-xl-2">
            <input type="text"
                   class="form-control required"
                   id="avatar_size"
                   name="avatar_size"
                   value="<?=$this->get('avatar_size') ?>" />
        </div>
    </div>
    <div class="row mb-3">
        <label for="avatar_filetypes" class="col-xl-2 control-label">
            <?=$this->getTrans('allowedFileExtensions') ?>
        </label>
        <div class="col-xl-2">
            <input type="text"
                   class="form-control required"
                   id="avatar_filetypes"
                   name="avatar_filetypes"
                   value="<?=$this->get('avatar_filetypes') ?>" />
        </div>
    </div>

    <h1><?=$this->getTrans('menuSettingsGallery') ?></h1>
    <div class="row mb-3">
        <div class="col-xl-2 control-label">
            <?=$this->getTrans('usergalleryAllowed') ?>:
        </div>
        <div class="col-xl-4">
            <div class="flipswitch">
                <input type="radio" class="flipswitch-input" id="usergallery-allowed-yes" name="usergallery_allowed" value="1" <?=($this->get('usergallery_allowed') == '1') ? 'checked="checked"' : '' ?> />
                <label for="usergallery-allowed-yes" class="flipswitch-label flipswitch-label-on"><?=$this->getTrans('yes') ?></label>
                <input type="radio" class="flipswitch-input" id="usergallery-allowed-no" name="usergallery_allowed" value="0" <?=($this->get('usergallery_allowed') != '1') ? 'checked="checked"' : '' ?> />
                <label for="usergallery-allowed-no" class="flipswitch-label flipswitch-label-off"><?=$this->getTrans('no') ?></label>
                <span class="flipswitch-selection"></span>
            </div>
        </div>
    </div>
    <div class="row mb-3">
        <label for="usergallery_filetypes" class="col-xl-2 control-label">
            <?=$this->getTrans('allowedFileExtensions') ?>
        </label>
        <div class="col-xl-2">
            <input type="text"
                   class="form-control required"
                   id="usergallery_filetypes"
                   name="usergallery_filetypes"
                   value="<?=$this->get('usergallery_filetypes') ?>" />
        </div>
    </div>
    <div class="row mb-3">
        <label for="picturesPerPageInput" class="col-xl-2 control-label">
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

    <h1><?=$this->getTrans('UserGroupsList') ?></h1>
    <div class="row mb-3">
        <div class="col-xl-2 control-label">
            <?=$this->getTrans('userGroupsAllowed') ?>:
        </div>
        <div class="col-xl-4">
            <div class="flipswitch">
                <input type="radio" class="flipswitch-input" id="userGroupList-allowed-yes" name="userGroupList_allowed" value="1" <?=($this->get('userGroupList_allowed') == '1') ? 'checked="checked"' : '' ?> />
                <label for="userGroupList-allowed-yes" class="flipswitch-label flipswitch-label-on"><?=$this->getTrans('yes') ?></label>

                <input type="radio" class="flipswitch-input" id="userGroupList-allowed-no" name="userGroupList_allowed" value="0" <?=($this->get('userGroupList_allowed') != '1') ? 'checked="checked"' : '' ?> />
                <label for="userGroupList-allowed-no" class="flipswitch-label flipswitch-label-off"><?=$this->getTrans('no') ?></label>
                <span class="flipswitch-selection"></span>
            </div>
        </div>
    </div>

    <div class="row mb-3">
        <div class="col-xl-2 control-label">
            <?=$this->getTrans('userAvatarsAllowed') ?>:
        </div>
        <div class="col-xl-4">
            <div class="flipswitch">
                <input type="radio" class="flipswitch-input" id="userAvatarList-allowed-yes" name="userAvatarList_allowed" value="1" <?=($this->get('userAvatarList_allowed') == '1') ? 'checked="checked"' : '' ?> />
                <label for="userAvatarList-allowed-yes" class="flipswitch-label flipswitch-label-on"><?=$this->getTrans('yes') ?></label>

                <input type="radio" class="flipswitch-input" id="userAvatarList-allowed-no" name="userAvatarList_allowed" value="0" <?=($this->get('userAvatarList_allowed') != '1') ? 'checked="checked"' : '' ?> />
                <label for="userAvatarList-allowed-no" class="flipswitch-label flipswitch-label-off"><?=$this->getTrans('no') ?></label>
                <span class="flipswitch-selection"></span>
            </div>
        </div>
    </div>
    <?=$this->getSaveBar() ?>
</form>

<?=$this->getDialog('mediaModal', $this->getTrans('media'), '<iframe frameborder="0"></iframe>') ?>
<script>
$('[name="regist_accept"]').click(function () {
    if ($(this).val() == "1") {
        $('#registSetfree').removeAttr('hidden');
        $('#registRules').removeAttr('hidden');
        $('#rulesForRegist').removeAttr('hidden');
        $('#registAccept').removeAttr('hidden');
    } else {
        $('#registSetfree').attr('hidden', '');
        $('#registRules').attr('hidden', '');
        $('#rulesForRegist').attr('hidden', '');
        $('#registAccept').attr('hidden', '');
    }
});
$('[name="regist_confirm"]').click(function () {
    if ($(this).val() == "0") {
        $('#registSetfree').removeAttr('hidden');
    } else {
        $('#registSetfree').attr('hidden', '');
    }
});
</script>
