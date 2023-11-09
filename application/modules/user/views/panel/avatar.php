<?php
$profil = $this->get('profil');
$settingMapper = $this->get('settingMapper');
?>

<link href="<?=$this->getModuleUrl('static/css/user.css') ?>" rel="stylesheet">

<div class="row">
    <div class="col-lg-12 profile">
        <?php include APPLICATION_PATH.'/modules/user/views/panel/navi.php'; ?>

        <div class="profile-content active">
            <h1><?=$this->getTrans('menuAvatar') ?></h1>
            <form class="form-horizontal" method="POST" enctype="multipart/form-data">
                <div class="row mb-3">
                    <?=$this->getTokenField() ?>
                    <div class="col-xl-2 col-lg-2 col-2">
                        <img class="panel-profile-image" src="<?=$this->getBaseUrl().$this->escape($profil->getAvatar()) ?>" title="<?=$this->escape($profil->getName()) ?>">

                        <?php if ($profil->getAvatar() !== 'static/img/noavatar.jpg'): ?>
                            <label for="avatar_delete" style="margin-left: 10px; margin-top: 10px;">
                                <input type="checkbox" id="avatar_delete" name="avatar_delete"> <?=$this->getTrans('avatarDelete') ?>
                            </label>
                        <?php endif; ?>
                    </div>
                    <div class="col-xl-10 col-md-10 col-10">
                        <p><?=$this->getTrans('avatarSize') ?>: <?=$this->get('avatar_width') ?> Pixel <?=$this->getTrans('width') ?>, <?=$this->get('avatar_height') ?> Pixel <?=$this->getTrans('height') ?>.</p>
                        <p><?=$this->getTrans('maxFilesize') ?>: <?=$settingMapper->getNicebytes($this->get('avatar_size')) ?>.</p>
                        <p><?=$this->getTrans('allowedFileExtensions') ?>: <?=str_replace(' ', ', ', $this->get('avatar_filetypes')) ?></p>
                        <div class="input-group col-xl-6">
                            <span class="input-group-btn">
                                <span class="btn btn-primary btn-file">
                                    <?=$this->getTrans('browse') ?> <input type="file" name="avatar" accept="image/*">
                                </span>
                            </span>
                            <input type="text"
                                   class="form-control"
                                   readonly />
                        </div>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="offset-xl-2 col-xl-12">
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

<script>
$(document).on('change', '.btn-file :file', function() {
    var input = $(this),
        numFiles = input.get(0).files ? input.get(0).files.length : 1,
        label = input.val().replace(/\\/g, '/').replace(/.*\//, '');
        input.trigger('fileselect', [numFiles, label]);
});

$(document).ready( function() {
    $('.btn-file :file').on('fileselect', function(event, numFiles, label) {
        var input = $(this).parents('.input-group').find(':text'),
            log = numFiles > 1 ? numFiles + ' files selected' : label;

        if (input.length) {
            input.val(log);
        } else {
            if (log) alert(log);
        }
    });
});
</script>
