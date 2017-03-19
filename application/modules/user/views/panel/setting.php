<?php $optMail = $this->get('optMail'); ?>

<link href="<?=$this->getModuleUrl('static/css/user.css') ?>" rel="stylesheet">

<div class="row">
    <div class="col-lg-12 profile">
        <?php include APPLICATION_PATH.'/modules/user/views/panel/navi.php'; ?>

        <div class="profile-content active">
            <h1><?=$this->getTrans('settingsSetting') ?></h1>
            <?php if ($this->validation()->hasErrors()): ?>
                <div class="alert alert-danger" role="alert">
                    <strong> <?=$this->getTrans('errorsOccured') ?>:</strong>
                    <ul>
                        <?php foreach ($this->validation()->getErrorMessages() as $error): ?>
                            <li><?=$error ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            <?php endif; ?>
            <form action="" class="form-horizontal" method="POST">
                <?=$this->getTokenField() ?>
                <div class="form-group <?=$this->validation()->hasError('optMail') ? 'has-error' : '' ?>">
                    <div class="col-lg-3 control-label">
                        <?=$this->getTrans('optMail') ?>:
                    </div>
                    <div class="col-lg-4">
                        <div class="flipswitch">
                            <input type="radio" class="flipswitch-input" id="opt_mail_yes" name="optMail" value="1" <?php if ($optMail == '1') { echo 'checked="checked"'; } ?> />
                            <label for="opt_mail_yes" class="flipswitch-label flipswitch-label-on"><?=$this->getTrans('yes') ?></label>
                            <input type="radio" class="flipswitch-input" id="opt_mail_no" name="optMail" value="0" <?php if ($optMail == '0') { echo 'checked="checked"'; } ?> />
                            <label for="opt_mail_no" class="flipswitch-label flipswitch-label-off"><?=$this->getTrans('no') ?></label>
                            <span class="flipswitch-selection"></span>
                        </div>
                     </div>
                </div>

                <div class="form-group">
                    <div class="col-lg-offset-3 col-lg-12">
                        <input type="submit" 
                               class="btn"
                               name="saveEntry" 
                               value="<?=$this->getTrans('profileSubmit') ?>" />
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
