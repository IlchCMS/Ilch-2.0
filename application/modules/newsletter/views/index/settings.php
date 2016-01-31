<?php
$countMail = $this->get('countMail');
?>

<link href="<?=$this->getModuleUrl('../user/static/css/user.css') ?>" rel="stylesheet">

<div id="panel">
    <div class="row">
        <div class="col-lg-2">
            <?php include APPLICATION_PATH.'/modules/user/views/panel/navi.php'; ?>
        </div>
        <div class="col-lg-10">
            <legend><?=$this->getTrans('menuSettings') ?></legend>
            <form action="" class="form-horizontal" method="POST">
                <?=$this->getTokenField() ?>
     
                <div class="form-group">
                    <label for="opt_newsletter" class="col-lg-3 control-label">
                        <?=$this->getTrans('acceptNewsletter') ?>:
                    </label>
                    <div class="col-lg-4">
                        <div class="flipswitch">
                            <input type="radio" class="flipswitch-input" name="opt_newsletter" value="1" id="newsletter_yes" <?php if ($countMail == '1') { echo 'checked="checked"'; } ?> />
                            <label for="newsletter_yes" class="flipswitch-label flipswitch-label-on"><?=$this->getTrans('yes') ?></label>
                            <input type="radio" class="flipswitch-input" name="opt_newsletter" value="0" id="newsletter_no" <?php if ($countMail == '0') { echo 'checked="checked"'; } ?> />
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
