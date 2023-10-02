<?php $countMail = $this->get('countMail'); ?>

<link href="<?=$this->getModuleUrl('../user/static/css/user.css') ?>" rel="stylesheet">

<div class="row">
    <div class="col-lg-12 profile">
        <?php include APPLICATION_PATH.'/modules/user/views/panel/navi.php'; ?>

        <div class="profile-content active">
            <h1><?=$this->getTrans('setting') ?></h1>
            <form action="" class="form-horizontal" method="POST">
                <?=$this->getTokenField() ?>
                <div class="row mb-3 <?=$this->validation()->hasError('acceptNewsletter') ? 'has-error' : '' ?>">
                    <div class="col-lg-3 control-label">
                        <?=$this->getTrans('acceptNewsletter') ?>:
                    </div>
                    <div class="col-lg-4">
                        <div class="flipswitch">
                            <input type="radio" class="flipswitch-input" id="newsletter_yes" name="acceptNewsletter" value="1" <?=($countMail == '1') ? 'checked="checked"' : '' ?> />
                            <label for="newsletter_yes" class="flipswitch-label flipswitch-label-on"><?=$this->getTrans('yes') ?></label>
                            <input type="radio" class="flipswitch-input" id="newsletter_no" name="acceptNewsletter" value="0" <?=($countMail == '0') ? 'checked="checked"' : '' ?> />
                            <label for="newsletter_no" class="flipswitch-label flipswitch-label-off"><?=$this->getTrans('no') ?></label>
                            <span class="flipswitch-selection"></span>
                        </div>
                     </div>
                </div>

                <div class="row mb-3">
                    <div class="col-lg-offset-3 col-lg-12">
                        <input type="submit"
                               name="saveEntry"
                               class="btn btn-outline-secondary"
                               value="<?=$this->getTrans('submit') ?>" />
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
