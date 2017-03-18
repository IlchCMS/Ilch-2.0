<?php
$profil = $this->get('profil');
?>

<link href="<?=$this->getModuleUrl('static/css/user.css') ?>" rel="stylesheet">

<div class="row">
    <div class="col-lg-12 profile">
        <?php include APPLICATION_PATH.'/modules/user/views/panel/navi.php'; ?>

        <div class="profile-content active">
            <legend><?=$this->getTrans('welcome'); ?> <?=$this->escape($profil->getName()) ?></legend>
        </div>
    </div>
</div>
