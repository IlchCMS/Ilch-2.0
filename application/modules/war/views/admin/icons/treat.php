<?php $entrie = $this->get('icon'); ?>
<h1><?=(!$entrie) ? $this->getTrans('manageNewGameIcon') : $this->getTrans('treatGameIcon') ?></h1>
<form id="article_form" class="form-horizontal" method="POST" action="" enctype="multipart/form-data">
    <?=$this->getTokenField() ?>
    <div class="form-group<?=$this->validation()->hasError('gameName') ? ' has-error' : '' ?>">
        <label for="gameNameInput" class="col-lg-2 control-label">
            <?=$this->getTrans('nextWarGame') ?>:
        </label>
        <div class="col-lg-4">
            <input type="text"
                   class="form-control"
                   id="gameNameInput"
                   name="gameName"
                   value="<?=$this->escape($this->originalInput('gameName', ($entrie ?? ''))) ?>" />
        </div>
    </div>
    <div class="form-group<?=$this->validation()->hasError('gameName') ? ' has-error' : '' ?>">
        <label for="gameIcon" class="col-lg-2 control-label">
            <?=$this->getTrans('iconSize') ?>: 16 Pixel <?=$this->getTrans('width') ?>, 16 Pixel <?=$this->getTrans('height') ?>.<br>
            <?=$this->getTrans('allowedFileExtensions') ?>: png
        </label>
        <div class="input-group col-lg-4">
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
    <?php if ($entrie && file_exists(APPLICATION_PATH.'/modules/war/static/img/'.$entrie.'.png')): ?>
    <div>
        <div class="col-lg-6">
            <img class="" src="<?=$this->getBaseUrl().'application/modules/war/static/img/'.$entrie.'.png' ?>" title="<?=$this->escape($entrie ?? '') ?>" alt="<?=$this->escape($entrie ?? '') ?>">
        </div>
    </div>
    <?php endif; ?>
    <?=($entrie) ? $this->getSaveBar('updateButton') : $this->getSaveBar('addButton') ?>
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
