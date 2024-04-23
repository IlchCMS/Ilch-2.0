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
    <?=$this->getSaveBar() ?>
</form>
