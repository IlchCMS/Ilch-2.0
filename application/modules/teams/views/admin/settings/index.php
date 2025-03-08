<?php

/** @var \Ilch\View $this */
?>
<h1><?=$this->getTrans('settings') ?></h1>
<form method="POST">
    <?=$this->getTokenField() ?>
    <div class="row mb-3<?=$this->validation()->hasError('image_height') ? ' has-error' : '' ?>">
        <label for="image_height" class="col-xl-2 col-form-label">
            <?=$this->getTrans('imageHeight') ?>
        </label>
        <div class="col-xl-2">
            <input type="number"
                   class="form-control required"
                   id="image_height"
                   name="image_height"
                   min="1"
                   value="<?=$this->get('teams_height') ?>"
                   required />
        </div>
    </div>
    <div class="row mb-3<?=$this->validation()->hasError('image_width') ? ' has-error' : '' ?>">
        <label for="image_width" class="col-xl-2 col-form-label">
            <?=$this->getTrans('imageWidth') ?>
        </label>
        <div class="col-xl-2">
            <input type="number"
                   class="form-control required"
                   id="image_width"
                   name="image_width"
                   min="1"
                   value="<?=$this->get('teams_width') ?>"
                   required />
        </div>
    </div>
    <div class="row mb-3<?=$this->validation()->hasError('image_filetypes') ? ' has-error' : '' ?>">
        <label for="image_filetypes" class="col-xl-2 col-form-label">
            <?=$this->getTrans('allowedFileExtensions') ?>
        </label>
        <div class="col-xl-2">
            <input type="text"
                   class="form-control required"
                   id="image_filetypes"
                   name="image_filetypes"
                   value="<?=$this->get('teams_filetypes') ?>" />
        </div>
    </div>
    <div class="row mb-3<?=$this->validation()->hasError('userNotification') ? ' has-error' : '' ?>">
        <div class="col-xl-2 col-form-label">
            <?=$this->getTrans('userNotification') ?>
        </div>
        <div class="col-xl-4">
            <div class="flipswitch">
                <input type="radio" class="flipswitch-input" id="userNotification-on" name="userNotification" value="1" <?=($this->get('userNotification') === '1') ? 'checked="checked"' : '' ?> />
                <label for="userNotification-on" class="flipswitch-label flipswitch-label-on"><?=$this->getTrans('on') ?></label>
                <input type="radio" class="flipswitch-input" id="userNotification-off" name="userNotification" value="0" <?=($this->get('userNotification') !== '1') ? 'checked="checked"' : '' ?> />
                <label for="userNotification-off" class="flipswitch-label flipswitch-label-off"><?=$this->getTrans('off') ?></label>
                <span class="flipswitch-selection"></span>
            </div>
        </div>
    </div>
    <?=$this->getSaveBar() ?>
</form>
