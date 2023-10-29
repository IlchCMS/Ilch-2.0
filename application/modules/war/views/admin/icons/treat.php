<?php $icon = $this->get('icon'); ?>
<h1><?=(!$icon) ? $this->getTrans('createNewGameIcon') : $this->getTrans('treatGameIcon') ?></h1>
<form id="article_form" class="form-horizontal" method="POST" action="" enctype="multipart/form-data">
    <?=$this->getTokenField() ?>
    <div class="row mb-3 <?=$this->validation()->hasError('gameName') ? ' has-error' : '' ?>">
        <label for="gameNameInput" class="col-xl-2 control-label">
            <?=$this->getTrans('gameName') ?>:
        </label>
        <div class="col-xl-4">
            <input type="text"
                   class="form-control"
                   id="gameNameInput"
                   name="gameName"
                   value="<?=$this->escape($this->originalInput('gameName', ($icon ?? ''))) ?>" />
        </div>
    </div>
    <div class="row mb-3 <?=$this->validation()->hasError('gameIcon') ? ' has-error' : '' ?>">
        <label for="gameIcon" class="col-xl-2 control-label">
            <?=$this->getTrans('gameIcon') ?><br>
            <?=$this->getTrans('iconSize') ?>: 16 Pixel <?=$this->getTrans('iconWidth') ?>, 16 Pixel <?=$this->getTrans('iconHeight') ?>.<br>
            <?=$this->getTrans('allowedFileExtensions') ?>: png
        </label>
        <div class="input-group col-xl-4">
            <span class="input-group-btn">
                <span class="btn btn-primary btn-file">
                    <input type="file" name="icon" accept="image/png">
                </span>
            </span>
            <input type="text"
                   class="form-control"
                   id="gameIcon"
                   name="gameIcon"
                   readonly />
        </div>
    </div>
    <?php if ($icon && file_exists(APPLICATION_PATH.'/modules/war/static/img/'.$icon.'.png')): ?>
    <div>
        <div class="col-xl-6">
            <img class="" src="<?=$this->getBaseUrl().'application/modules/war/static/img/'.$icon.'.png' ?>" title="<?=$this->escape($icon) ?>" alt="<?=$this->escape($icon) ?>">
        </div>
    </div>
    <?php endif; ?>
    <?=($icon) ? $this->getSaveBar('updateButton') : $this->getSaveBar('addButton') ?>
</form>

<script>
$(document).on('change', '.btn-file :file', function() {
    let input = $(this),
        numFiles = input.get(0).files ? input.get(0).files.length : 1,
        label = input.val().replace(/\\/g, '/').replace(/.*\//, '');
        input.trigger('fileselect', [numFiles, label]);
});

$(document).ready( function() {
    $('.btn-file :file').on('fileselect', function(event, numFiles, label) {
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
