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
            <legend><?=$this->getTrans('settingsSignature'); ?></legend>
            <form action="" class="form-horizontal" method="POST">
                <?=$this->getTokenField(); ?>
                <div class="form-group">
                    <div class="col-lg-12">
                        <textarea class="form-control ckeditor"
                                  id="ck_1"
                                  toolbar="ilch_bbcode"
                                  name="signature"><?=$this->escape($profil->getSignature()) ?></textarea>
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-lg-8">
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
