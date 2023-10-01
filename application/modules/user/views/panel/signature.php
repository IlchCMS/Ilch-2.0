<?php $profil = $this->get('profil'); ?>

<link href="<?=$this->getModuleUrl('static/css/user.css') ?>" rel="stylesheet">

<div class="row">
    <div class="col-lg-12 profile">
        <?php include APPLICATION_PATH.'/modules/user/views/panel/navi.php'; ?>

        <div class="profile-content active">
            <h1><?=$this->getTrans('settingsSignature') ?></h1>
            <form class="form-horizontal" method="POST">
                <?=$this->getTokenField() ?>
                <div class="row mb-3">
                    <div class="col-lg-12">
                        <textarea class="form-control ckeditor"
                                  id="ck_1"
                                  name="signature"
                                  toolbar="ilch_html_frontend"><?=$this->escape($profil->getSignature()) ?></textarea>
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col-lg-8">
                        <input type="submit"
                               class="btn btn-outline-secondary"
                               name="saveEntry"
                               value="<?=$this->getTrans('profileSubmit') ?>" />
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
