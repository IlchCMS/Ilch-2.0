<?php $profil = $this->get('profil'); ?>

<link href="<?=$this->getModuleUrl('static/css/user.css') ?>" rel="stylesheet">

<div class="row">
    <div class="col-lg-12 profile">
        <?php include APPLICATION_PATH.'/modules/user/views/panel/navi.php'; ?>

        <div class="profile-content active">
            <legend><?=$this->getTrans('settingsSignature'); ?></legend>
            <form action="" class="form-horizontal" method="POST">
                <?=$this->getTokenField(); ?>
                <div class="form-group">
                    <div class="col-lg-12">
                        <textarea class="form-control ckeditor"
                                  id="ck_1"
                                  name="signature"
                                  toolbar="ilch_bbcode"><?=$this->escape($profil->getSignature()) ?></textarea>
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-lg-8">
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

<?=$this->getDialog('smiliesModal', $this->getTrans('smilies'), '<iframe frameborder="0"></iframe>'); ?>
