<?php

/** @var \Ilch\View $this */

/** @var string $icon */
$icon = $this->get('icon');
?>
<h1><?=$this->getTrans(!$icon ? 'createNewGameIcon' : 'treatGameIcon') ?></h1>
<form id="warIcon_form" method="POST" action="" enctype="multipart/form-data">
    <?=$this->getTokenField() ?>
    <div class="row mb-3<?=$this->validation()->hasError('gameName') ? ' has-error' : '' ?>">
        <label for="gameNameInput" class="col-xl-2 col-form-label">
            <?=$this->getTrans('gameName') ?>:
        </label>
        <div class="col-xl-6">
            <input type="text"
                   class="form-control"
                   id="gameNameInput"
                   name="gameName"
                   value="<?=$this->escape($this->originalInput('gameName', ($icon ?? ''))) ?>" />
        </div>
    </div>
    <div class="row mb-3<?=$this->validation()->hasError('gameIcon') ? ' has-error' : '' ?>">
        <label for="gameIcon" class="col-xl-2 col-form-label">
            <?=$this->getTrans('gameIcon') ?><br>
        </label>
        <div class="col-xl-6">
            <div class="input-group">
                <input type="file" class="form-control" name="icon" accept="image/png">
                <input type="text"
                       class="form-control"
                       id="gameIcon"
                       name="gameIcon"
                       aria-describedby="iconHelpBlock"
                       readonly />
                <?php if ($icon && file_exists(APPLICATION_PATH . '/modules/war/static/img/' . $icon . '.png')) : ?>
                    <span class="input-group-text">
                        <img src="<?=$this->getBaseUrl() . 'application/modules/war/static/img/' . $icon . '.png' ?>" title="<?=$this->escape($icon) ?>" alt="<?=$this->escape($icon) ?>">
                    </span>
                <?php endif; ?>
            </div>
            <div class="form-text" id="iconHelpBlock"><?=$this->getTrans('iconSize') ?>: 16 Pixel <?=$this->getTrans('iconWidth') ?>, 16 Pixel <?=$this->getTrans('iconHeight') ?>. <?=$this->getTrans('allowedFileExtensions') ?>: png</div>
        </div>
    </div>
    <?=$this->getSaveBar($icon ? 'updateButton' : 'addButton') ?>
</form>

<script>
$(document).on('change', '.input-group :file', function() {
    let input = $(this),
        numFiles = input.get(0).files ? input.get(0).files.length : 1,
        label = input.val().replace(/\\/g, '/').replace(/.*\//, '');
        input.trigger('fileselect', [numFiles, label]);
});

$(document).ready( function() {
    $('.input-group :file').on('fileselect', function(event, numFiles, label) {
        let input = $(this).parents('.input-group').find(':text'),
            log = numFiles > 1 ? numFiles + ' files selected' : label;

        if (input.length) {
            input.val(log);
        } else {
            if (log) alert(log);
        }
    });
});
</script>
