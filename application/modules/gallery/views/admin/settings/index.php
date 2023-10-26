<?php

/** @var \Ilch\View $this */
?>
<h1><?=$this->getTrans('settings') ?></h1>
<form class="form-horizontal" method="POST">
    <?=$this->getTokenField() ?>
    <div class="row mb-3 <?=$this->validation()->hasError('picturesPerPage') ? 'has-error' : '' ?>">
        <label for="picturesPerPageInput" class="col-xl-2 control-label">
            <?=$this->getTrans('picturesPerPage') ?>:
        </label>
        <div class="col-xl-1">
            <input type="number"
                   class="form-control"
                   id="picturesPerPageInput"
                   name="picturesPerPage"
                   min="1"
                   value="<?=$this->escape($this->originalInput('picturesPerPage', $this->get('picturesPerPage'))) ?>" />
        </div>
    </div>
    <h1><?=$this->getTrans('box') ?>: <?=$this->getTrans('pictureOfX') ?></h1>
    <div class="row mb-3 <?=$this->validation()->hasError('pictureOfXSource') ? 'has-error' : '' ?>">
        <label for="pictureOfXSource" class="col-xl-2 control-label">
            <?=$this->getTrans('pictureOfXSource') ?>:
        </label>
        <div class="col-xl-4">
            <select class="chosen-select form-control"
                    id="pictureOfXSource"
                    name="pictureOfXSource[]"
                    data-placeholder="<?=$this->getTrans('selectGalleries') ?>"
                    multiple>
                <?php foreach ($this->get('galleries') as $gallery) : ?>
                    <option value="<?=$gallery->getId() ?>" <?=in_array($gallery->getId(), $this->originalInput('pictureOfXSource', $this->get('pictureOfXSource')) ?? []) ? 'selected="selected"' : '' ?>>
                        <?=$this->escape($gallery->getTitle()) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
    </div>
    <div class="row mb-3 <?=$this->validation()->hasError('pictureOfXInterval') ? 'has-error' : '' ?>">
        <?php $selected = ($this->get('pictureOfXInterval')) ? 'selected="selected"' : ''?>
        <label for="pictureOfXInterval" class="col-xl-2 control-label">
            <?=$this->getTrans('pictureOfXInterval') ?>:
        </label>
        <div class="col-xl-4">
            <select class="chosen-select form-control"
                    id="pictureOfXInterval"
                    name="pictureOfXInterval"
                    data-placeholder="<?=$this->getTrans('selectPictureOfXInterval') ?>">
                    <option value="0" <?=(!$this->originalInput('pictureOfXInterval', $this->get('pictureOfXInterval'))) ? 'selected="selected"' : '' ?>><?=$this->getTrans('everytime') ?></option>
                    <option value="1" <?=($this->originalInput('pictureOfXInterval', $this->get('pictureOfXInterval')) == 1) ? 'selected="selected"' : '' ?>><?=$this->getTrans('hourly') ?></option>
                    <option value="2" <?=($this->originalInput('pictureOfXInterval', $this->get('pictureOfXInterval')) == 2) ? 'selected="selected"' : '' ?>><?=$this->getTrans('daily') ?></option>
                    <option value="3" <?=($this->originalInput('pictureOfXInterval', $this->get('pictureOfXInterval')) == 3) ? 'selected="selected"' : '' ?>><?=$this->getTrans('weekly') ?></option>
                    <option value="4" <?=($this->originalInput('pictureOfXInterval', $this->get('pictureOfXInterval')) == 4) ? 'selected="selected"' : '' ?>><?=$this->getTrans('monthly') ?></option>
            </select>
        </div>
    </div>
    <div class="row mb-3 <?=$this->validation()->hasError('pictureOfXRandom') ? 'has-error' : '' ?>">
        <label for="pictureOfXRandom" class="col-xl-2 control-label">
            <?=$this->getTrans('pictureOfXRandom') ?>:
        </label>
        <div class="col-xl-4">
            <div class="flipswitch">
                <input type="radio" class="flipswitch-input" id="pictureOfXRandom-on" name="pictureOfXRandom" value="1" <?=$this->originalInput('pictureOfXRandom', $this->get('pictureOfXRandom')) ? 'checked="checked"' : '' ?> />
                <label for="pictureOfXRandom-on" class="flipswitch-label flipswitch-label-on"><?=$this->getTrans('on') ?></label>
                <input type="radio" class="flipswitch-input" id="pictureOfXRandom-off" name="pictureOfXRandom" value="0" <?=!$this->originalInput('pictureOfXRandom', $this->get('pictureOfXRandom')) ? 'checked="checked"' : '' ?> />
                <label for="pictureOfXRandom-off" class="flipswitch-label flipswitch-label-off"><?=$this->getTrans('off') ?></label>
                <span class="flipswitch-selection"></span>
            </div>
        </div>
    </div>

    <h1><?=$this->getTrans('venoboxSetting') ?></h1>
    <div class="row mb-3 <?=$this->validation()->hasError('venoboxNumeration') ? 'has-error' : '' ?>">
        <label for="venoboxNumeration" class="col-xl-2 control-label">
            <?=$this->getTrans('venoboxNumeration') ?>:
        </label>
        <div class="col-xl-4">
            <div class="flipswitch">
                <input type="radio" class="flipswitch-input" id="venoboxNumeration-on" name="venoboxNumeration" value="1" <?=$this->originalInput('venoboxNumeration', $this->get('venoboxNumeration')) ? 'checked="checked"' : '' ?>  />
                <label for="venoboxNumeration-on" class="flipswitch-label flipswitch-label-on"><?=$this->getTrans('on') ?></label>
                <input type="radio" class="flipswitch-input" id="venoboxNumeration-off" name="venoboxNumeration" value="0" <?=!$this->originalInput('venoboxNumeration', $this->get('venoboxNumeration')) ? 'checked="checked"' : '' ?> />
                <label for="venoboxNumeration-off" class="flipswitch-label flipswitch-label-off"><?=$this->getTrans('off') ?></label>
                <span class="flipswitch-selection"></span>
            </div>
        </div>
    </div>

    <div class="row mb-3 <?=$this->validation()->hasError('venoboxOverlayColor') ? 'has-error' : '' ?>">
        <label for="venoboxOverlayColor" class="col-xl-2 control-label">
            <?=$this->getTrans('venoboxOverlayColor') ?>:
        </label>
        <div class="col-xl-2 input-group">
            <input class="form-control color {hash:true}"
                   id="venoboxOverlayColor"
                   name="venoboxOverlayColor"
                   value="<?=$this->originalInput('venoboxOverlayColor', $this->get('venoboxOverlayColor')) ? : '#ffffff' ?>">
            <span class="input-group-text">
                <span class="fa fa-undo" onclick="document.getElementById('venoboxOverlayColor').color.fromString('ffffff')"></span>
            </span>
        </div>
    </div>

    <div class="row mb-3 <?=$this->validation()->hasError('venoboxInfiniteGallery') ? 'has-error' : '' ?>">
        <label for="venoboxInfiniteGallery" class="col-xl-2 control-label">
            <?=$this->getTrans('venoboxInfiniteGallery') ?>:
        </label>
        <div class="col-xl-4">
            <div class="flipswitch">
                <input type="radio" class="flipswitch-input" id="venoboxInfiniteGallery-on" name="venoboxInfiniteGallery" value="1" <?=$this->originalInput('venoboxInfiniteGallery', $this->get('venoboxInfiniteGallery')) ? 'checked="checked"' : '' ?> />
                <label for="venoboxInfiniteGallery-on" class="flipswitch-label flipswitch-label-on"><?=$this->getTrans('on') ?></label>
                <input type="radio" class="flipswitch-input" id="venoboxInfiniteGallery-off" name="venoboxInfiniteGallery" value="0" <?=!$this->originalInput('venoboxInfiniteGallery', $this->get('venoboxInfiniteGallery')) ? 'checked="checked"' : '' ?> />
                <label for="venoboxInfiniteGallery-off" class="flipswitch-label flipswitch-label-off"><?=$this->getTrans('off') ?></label>
                <span class="flipswitch-selection"></span>
            </div>
        </div>
    </div>

    <div class="row mb-3 <?=$this->validation()->hasError('venoboxBgcolor') ? 'has-error' : '' ?>">
        <label for="venoboxBgcolor" class="col-xl-2 control-label">
            <?=$this->getTrans('venoboxBgcolor') ?>:
        </label>
        <div class="col-xl-2 input-group">
            <input class="form-control color {hash:true}"
                   id="venoboxBgcolor"
                   name="venoboxBgcolor"
                   value="<?=$this->originalInput('venoboxBgcolor', $this->get('venoboxBgcolor')) ? : '#ffffff' ?>">
            <span class="input-group-text">
                <span class="fa fa-undo" onclick="document.getElementById('venoboxBgcolor').color.fromString('ffffff')"></span>
            </span>
        </div>
    </div>

    <div class="row mb-3 <?=$this->validation()->hasError('venoboxBorder') ? 'has-error' : '' ?>">
        <label for="venoboxBorder" class="col-xl-2 control-label">
            <?=$this->getTrans('venoboxBorder') ?>:
        </label>
        <div class="col-xl-2 input-group">
            <input type="text"
                   class="form-control"
                   id="venoboxBorder"
                   name="venoboxBorder"
                   pattern="[0-9]+px"
                   value="<?=$this->escape($this->originalInput('venoboxBorder', $this->get('venoboxBorder'))) ?>" />
            <div class="form-text"><?=$this->getTrans('venoboxExampleText') ?></div>
        </div>
    </div>

    <div class="row mb-3 <?=$this->validation()->hasError('venoboxTitleattr') ? 'has-error' : '' ?>">
        <label for="venoboxTitleattr" class="col-xl-2 control-label">
            <?=$this->getTrans('venoboxTitleattr') ?>:
        </label>
        <div class="col-xl-4">
            <div class="flipswitch">
                <input type="radio" class="flipswitch-input" id="venoboxTitleattr-on" name="venoboxTitleattr" value="title" <?=$this->originalInput('venoboxTitleattr', $this->get('venoboxTitleattr')) == 'title' ? 'checked="checked"' : '' ?> />
                <label for="venoboxTitleattr-on" class="flipswitch-label flipswitch-label-on"><?=$this->getTrans('on') ?></label>
                <input type="radio" class="flipswitch-input" id="venoboxTitleattr-off" name="venoboxTitleattr" value="" <?=$this->originalInput('venoboxTitleattr', $this->get('venoboxTitleattr')) != 'title' ? 'checked="checked"' : '' ?> />
                <label for="venoboxTitleattr-off" class="flipswitch-label flipswitch-label-off"><?=$this->getTrans('off') ?></label>
                <span class="flipswitch-selection"></span>
            </div>
        </div>
    </div>

    <?=$this->getSaveBar() ?>
</form>
<script src="<?=$this->getStaticUrl('js/jscolor/jscolor.js') ?>"></script>
<script>
    $('#pictureOfXSource').chosen();
</script>
