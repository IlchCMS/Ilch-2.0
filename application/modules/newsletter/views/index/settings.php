<?php $countMail = $this->get('countMail'); ?>

<link href="<?=$this->getModuleUrl('../user/static/css/user.css') ?>" rel="stylesheet">

<div id="panel">
    <div class="row">
        <div class="col-lg-2">
            <?php include APPLICATION_PATH.'/modules/user/views/panel/navi.php'; ?>
        </div>
        <div class="col-lg-10">
            <legend><?=$this->getTrans('setting') ?></legend>
            <?php if (!empty($this->get('errors'))): ?>
                <div class="alert alert-danger" role="alert">
                    <strong> <?=$this->getTrans('errorsOccured') ?>:</strong>
                    <ul>
                        <?php foreach ($this->get('errors') as $error): ?>
                            <li><?= $error; ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            <?php endif; ?>
            <form action="" class="form-horizontal" method="POST">
                <?=$this->getTokenField() ?>
                <div class="form-group <?=in_array('acceptNewsletter', $this->get('errorFields')) ? 'has-error' : '' ?>">
                    <div class="col-lg-3 control-label">
                        <?=$this->getTrans('acceptNewsletter') ?>:
                    </div>
                    <div class="col-lg-4">
                        <div class="flipswitch">
                            <input type="radio" class="flipswitch-input" id="newsletter_yes" name="acceptNewsletter" value="1" <?php if ($countMail == '1') { echo 'checked="checked"'; } ?> />
                            <label for="newsletter_yes" class="flipswitch-label flipswitch-label-on"><?=$this->getTrans('yes') ?></label>
                            <input type="radio" class="flipswitch-input" id="newsletter_no" name="acceptNewsletter" value="0" <?php if ($countMail == '0') { echo 'checked="checked"'; } ?> />
                            <label for="newsletter_no" class="flipswitch-label flipswitch-label-off"><?=$this->getTrans('no') ?></label>
                            <span class="flipswitch-selection"></span>
                        </div>
                     </div>
                </div>

                <div class="form-group">
                    <div class="col-lg-offset-3 col-lg-12">
                        <input type="submit"
                               name="saveEntry"
                               class="btn"
                               value="<?=$this->getTrans('submit') ?>" />
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
