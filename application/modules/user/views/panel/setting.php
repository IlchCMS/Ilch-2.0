<?php
$profil = $this->get('profil');
?>

<link href="<?=$this->getModuleUrl('static/css/user.css') ?>" rel="stylesheet">

<div id="panel">
    <div class="row">
        <div class="col-lg-2">
            <?php include APPLICATION_PATH.'/modules/user/views/panel/navi.php'; ?>
        </div>
        <div class="col-lg-10">
            <legend><?=$this->getTrans('settingsSetting') ?></legend>
            <form action="" class="form-horizontal" method="POST">
                <?=$this->getTokenField() ?>

                <div class="form-group">
                    <label for="opt_mail" class="col-lg-3 control-label">
                        <?=$this->getTrans('optMail') ?>:
                    </label>
                    <div class="col-lg-4">
                        <div class="flipswitch">
                            <input type="radio" class="flipswitch-input" name="opt_mail" value="1" id="opt_mail_yes" <?php if ($profil->getOptMail() == '1') { echo 'checked="checked"'; } ?> />
                            <label for="opt_mail_yes" class="flipswitch-label flipswitch-label-on"><?=$this->getTrans('yes') ?></label>
                            <input type="radio" class="flipswitch-input" name="opt_mail" value="0" id="opt_mail_no" <?php if ($profil->getOptMail() == '0') { echo 'checked="checked"'; } ?> />
                            <label for="opt_mail_no" class="flipswitch-label flipswitch-label-off"><?=$this->getTrans('no') ?></label>
                            <span class="flipswitch-selection"></span>
                        </div>
                     </div>
                </div>

                <div class="form-group">
                    <div class="col-lg-offset-3 col-lg-12">
                        <input type="submit" 
                               name="saveEntry" 
                               class="btn"
                               value="<?=$this->getTrans('profileSubmit') ?>" />
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
