<form class="form-horizontal" method="POST" action="<?=$this->getUrl(array('action' => $this->getRequest()->getActionName())) ?>">
    <legend><?=$this->getTrans('menuSettings') ?></legend>
    <?=$this->getTokenField() ?>
    <div class="form-group">
        <label for="regist_accept" class="col-lg-2 control-label">
            <?=$this->getTrans('acceptUserRegis') ?>:
        </label>
        <div class="col-lg-4">
            <label class="checkbox-inline">
                <input type="radio" 
                       name="regist_accept" 
                       id="regist_accept_yes" 
                       value="1" 
                       <?php if ($this->get('regist_accept') == '1') { echo 'checked="checked"';} ?>>
                       <label for="regist_accept_yes"><?=$this->getTrans('yes') ?></label>
            </label>
            <label class="checkbox-inline">
                <input type="radio" 
                       name="regist_accept" 
                       id="regist_accept_no" 
                       value="0" 
                       <?php if ($this->get('regist_accept') == '0') { echo 'checked="checked"';} ?>>
                       <label for="regist_accept_no"><?=$this->getTrans('no') ?></label>
            </label>
        </div>
    </div>
    <div class="form-group">
        <label for="regist_confirm" class="col-lg-2 control-label">
            <?=$this->getTrans('confirmRegistrationEmail') ?>:
        </label>
        <div class="col-lg-4">
            <label class="checkbox-inline">
                <input type="radio" 
                       name="regist_confirm" 
                       id="regist_confirm_yes" 
                       value="1" 
                       <?php if ($this->get('regist_confirm') == '1') { echo 'checked="checked"';} ?>>
                       <label for="regist_confirm_yes"><?=$this->getTrans('yes') ?></label>
            </label>
            <label class="checkbox-inline">
                <input type="radio" 
                       name="regist_confirm" 
                       id="regist_confirm_no" 
                       value="0" 
                       <?php if ($this->get('regist_confirm') == '0') { echo 'checked="checked"';} ?>>
                       <label for="regist_confirm_no"><?=$this->getTrans('no') ?></label>
            </label>
        </div>
    </div>
    <div id="registRules" class="form-group <?php if($this->get('regist_confirm') != '1'){ echo 'hidden'; } ?>">
        <label for="regist_rules" class="col-lg-2 control-label">
            <?=$this->getTrans('rulesForRegist') ?>:
        </label>
        <div class="col-lg-4">
            <textarea class="form-control" 
                      name="regist_rules" 
                      cols="60" 
                      rows="5"><?=$this->get('regist_rules') ?></textarea>
        </div>
    </div>
    <div id="confirmMail" class="form-group <?php if($this->get('regist_confirm') != '1'){ echo 'hidden'; } ?>">
        <label for="regist_confirm_mail" class="col-lg-2 control-label">
            <?=$this->getTrans('mailForRegist') ?>:
            <br /><br />
            <div class="small">
                <b><?=$this->getTrans('settingsRegistVariables') ?></b><br />
                <b>{name}</b> = <?=$this->getTrans('settingsRegistVariablesName') ?><br />
                <b>{sitetitle}</b> = <?=$this->getTrans('settingsRegistVariablesSitetitle') ?><br />
                <b>{comfirm}</b> = <?=$this->getTrans('settingsRegistVariablesComfirm') ?>
            </div>
        </label>
        <div class="col-lg-4">
            <textarea class="form-control" 
                      name="regist_confirm_mail" 
                      cols="60" 
                      id="ilch_html" 
                      rows="5"><?=$this->get('regist_confirm_mail') ?></textarea>
        </div>
    </div>
    <div id="confirmMail" class="form-group">
        <label for="password_change_mail" class="col-lg-2 control-label">
            <?=$this->getTrans('mailForNewPassword') ?>:
            <br /><br />
            <div class="small">
                <b><?=$this->getTrans('settingsRegistVariables') ?></b><br />
                <b>{name}</b> = <?=$this->getTrans('settingsRegistVariablesName') ?><br />
                <b>{sitetitle}</b> = <?=$this->getTrans('settingsRegistVariablesSitetitle') ?><br />
                <b>{comfirm}</b> = <?=$this->getTrans('settingsRegistVariablesComfirm') ?>
            </div>
        </label>
        <div class="col-lg-4">
            <textarea class="form-control" 
                      name="password_change_mail" 
                      cols="60" 
                      id="ilch_html" 
                      rows="5"><?=$this->get('password_change_mail') ?></textarea>
        </div>
    </div>
    
    <legend><?=$this->getTrans('menuSettingsAvatar') ?></legend>
    <div class="form-group">
        <label for="avatar_height" class="col-lg-2 control-label">
            <?=$this->getTrans('avatarHeight') ?>
        </label>
        <div class="col-lg-2">
            <input name="avatar_height" 
                   type="text" 
                   id="avatar_height" 
                   class="form-control required" 
                   value="<?=$this->get('avatar_height') ?>" />
        </div>
    </div>
    <div class="form-group">
        <label for="avatar_width" class="col-lg-2 control-label">
            <?=$this->getTrans('avatarWidth') ?>
        </label>
        <div class="col-lg-2">
            <input name="avatar_width" 
                   type="text" 
                   id="avatar_width" 
                   class="form-control required" 
                   value="<?=$this->get('avatar_width') ?>" />
        </div>
    </div>
    <div class="form-group">
        <label for="avatar_size" class="col-lg-2 control-label">
            <?=$this->getTrans('avatarSizeBytes') ?>
        </label>
        <div class="col-lg-2">
            <input name="avatar_size" 
                   type="text" 
                   id="avatar_size" 
                   class="form-control required" 
                   value="<?=$this->get('avatar_size') ?>" />
        </div>
    </div>
    <div class="form-group">
        <label for="avatar_filetypes" class="col-lg-2 control-label">
            <?=$this->getTrans('avatarAllowedFileExtensions') ?>
        </label>
        <div class="col-lg-2">
            <input name="avatar_filetypes" 
                   type="text" 
                   id="avatar_filetypes" 
                   class="form-control required" 
                   value="<?=$this->get('avatar_filetypes') ?>" />
        </div>
    </div>
    <?=$this->getSaveBar() ?>
</form>

<script>$('[name="regist_accept"]').click(function () {
    if ($(this).val() == "1") {
        $('#registRules').removeClass('hidden');
    } else {
        $('#registRules').addClass('hidden');
    }
});

$('[name="regist_confirm"]').click(function () {
    if ($(this).val() == "1") {
        $('#confirmMail').removeClass('hidden');
    } else {
        $('#confirmMail').addClass('hidden');
    }
});
</script>
